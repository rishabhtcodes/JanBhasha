<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                abort_if(!$request->user()?->isAdmin(), 403, 'Admin access required.');
                return $next($request);
            }),
        ];
    }

    // ── Dashboard ────────────────────────────────────
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            $stats = [
                'total_orgs'         => Organisation::count(),
                'active_orgs'        => Organisation::where('is_active', true)->count(),
                'total_users'        => User::count(),
                'total_translations' => Translation::count(),
                'this_month'         => Translation::whereMonth('created_at', now()->month)
                                            ->whereYear('created_at', now()->year)->count(),
                'completed'          => Translation::where('status', 'completed')->count(),
                'failed'             => Translation::where('status', 'failed')->count(),
            ];

            $recentOrgs = Organisation::latest()->limit(5)->get();
            foreach ($recentOrgs as $org) {
                $org->users_count = User::where('organisation_id', $org->id)->count();
                $org->translations_count = Translation::where('organisation_id', $org->id)->count();
            }

            $recentTranslations = Translation::with(['user', 'organisation'])
                ->latest()->limit(10)->get();
        } else {
            $org = $user->organisation;
            abort_if(!$org, 403, 'You are not associated with any organisation.');

            $stats = [
                'total_orgs'         => 1,
                'active_orgs'        => $org->is_active ? 1 : 0,
                'total_users'        => User::where('organisation_id', $org->id)->count(),
                'total_translations' => Translation::where('organisation_id', $org->id)->count(),
                'this_month'         => Translation::where('organisation_id', $org->id)
                                            ->whereMonth('created_at', now()->month)
                                            ->whereYear('created_at', now()->year)->count(),
                'completed'          => Translation::where('organisation_id', $org->id)
                                            ->where('status', 'completed')->count(),
                'failed'             => Translation::where('organisation_id', $org->id)
                                            ->where('status', 'failed')->count(),
            ];

            $recentOrgs = collect([$org]);
            $org->users_count = $stats['total_users'];
            $org->translations_count = $stats['total_translations'];

            $recentTranslations = Translation::where('organisation_id', $org->id)
                ->with(['user', 'organisation'])
                ->latest()->limit(10)->get();
        }

        return view('admin.dashboard', compact('stats', 'recentOrgs', 'recentTranslations'));
    }

    // ── Users ─────────────────────────────────────────
    public function users(Request $request)
    {
        $currentUser = $request->user();

        if ($currentUser->isSuperAdmin()) {
            $users = User::with('organisation')
                ->when($request->filled('organisation'), fn ($q) => $q->where('organisation_id', $request->organisation))
                ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
                ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                    $q2->where('name', 'like', '%'.$request->search.'%')
                       ->orWhere('email', 'like', '%'.$request->search.'%');
                }))
                ->latest()
                ->paginate(20)
                ->withQueryString();

            $organisations = Organisation::orderBy('name')->get();
        } else {
            $orgId = $currentUser->organisation_id;
            abort_if(!$orgId, 403, 'You are not associated with any organisation.');

            $users = User::where('organisation_id', $orgId)
                ->where('role', '!=', 'super_admin') // Never show super admin details or super admin users!
                ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
                ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                    $q2->where('name', 'like', '%'.$request->search.'%')
                       ->orWhere('email', 'like', '%'.$request->search.'%');
                }))
                ->latest()
                ->paginate(20)
                ->withQueryString();

            $organisations = Organisation::where('id', $orgId)->get();
        }

        return view('admin.users.index', compact('users', 'organisations'));
    }

    public function createUser()
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            $organisations = Organisation::where('id', $currentUser->organisation_id)->get();
        } else {
            $organisations = Organisation::where('is_active', true)->orderBy('name')->get();
        }

        return view('admin.users.create', compact('organisations'));
    }

    public function storeUser(Request $request)
    {
        $currentUser = $request->user();

        if (!$currentUser->isSuperAdmin()) {
            $data = $request->validate([
                'name'            => ['required', 'string', 'max:255'],
                'email'           => ['required', 'email', 'unique:users,email'],
                'password'        => ['required', Password::defaults()],
                'role'            => ['required', 'in:admin,translator'], // Standard admins cannot create a super_admin!
            ]);

            $data['organisation_id'] = $currentUser->organisation_id;
        } else {
            $data = $request->validate([
                'name'            => ['required', 'string', 'max:255'],
                'email'           => ['required', 'email', 'unique:users,email'],
                'password'        => ['required', Password::defaults()],
                'role'            => ['required', 'in:super_admin,admin,translator'],
                'organisation_id' => ['nullable', 'exists:organisations,id'],
            ]);
        }

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        // Send welcome email to manually created user
        Mail::to($user->email)->queue(new WelcomeMail($user));

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$data['name']}' created successfully.");
    }

    public function editUser(User $user)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            // Standard admin checks: user must belong to their organisation and must not be a super admin
            abort_if($user->organisation_id !== $currentUser->organisation_id || $user->isSuperAdmin(), 403, 'Unauthorized.');

            $organisations = Organisation::where('id', $currentUser->organisation_id)->get();
        } else {
            $organisations = Organisation::where('is_active', true)->orderBy('name')->get();
        }

        return view('admin.users.edit', compact('user', 'organisations'));
    }

    public function updateUser(Request $request, User $user)
    {
        $currentUser = $request->user();

        if (!$currentUser->isSuperAdmin()) {
            // Standard admin checks: user must belong to their organisation and must not be a super admin
            abort_if($user->organisation_id !== $currentUser->organisation_id || $user->isSuperAdmin(), 403, 'Unauthorized.');

            $data = $request->validate([
                'name'            => ['required', 'string', 'max:255'],
                'email'           => ['required', 'email', 'unique:users,email,'.$user->id],
                'role'            => ['required', 'in:admin,translator'], // Standard admins cannot promote to super_admin!
                'password'        => ['nullable', Password::defaults()],
            ]);

            $data['organisation_id'] = $currentUser->organisation_id;
        } else {
            $data = $request->validate([
                'name'            => ['required', 'string', 'max:255'],
                'email'           => ['required', 'email', 'unique:users,email,'.$user->id],
                'role'            => ['required', 'in:super_admin,admin,translator'],
                'organisation_id' => ['nullable', 'exists:organisations,id'],
                'password'        => ['nullable', Password::defaults()],
            ]);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            // Standard admin checks: user must belong to their organisation and must not be a super admin
            abort_if($user->organisation_id !== $currentUser->organisation_id || $user->isSuperAdmin(), 403, 'Unauthorized.');
        }

        abort_if($user->id === auth()->id(), 403, 'Cannot delete your own account.');
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}
