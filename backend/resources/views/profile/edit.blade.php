<x-app-layout>
    <x-slot name="header">Profile Settings</x-slot>

    <style>
        /* ── Profile Page Styles ── */
        .profile-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; padding: 28px; }

        .avatar-drop-zone {
            width: 120px; height: 120px; border-radius: 50%; position: relative;
            cursor: pointer; flex-shrink: 0;
        }
        .avatar-drop-zone img {
            width: 100%; height: 100%; border-radius: 50%; object-fit: cover;
            box-shadow: 0 0 0 4px rgba(79,70,229,0.35), 0 12px 36px rgba(0,0,0,0.35);
            transition: all 0.3s;
        }
        .avatar-drop-zone:hover img { box-shadow: 0 0 0 4px rgba(79,70,229,0.7), 0 16px 48px rgba(0,0,0,0.4); }
        .avatar-overlay {
            position: absolute; inset: 0; border-radius: 50%;
            background: rgba(0,0,0,0.55); opacity: 0;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            transition: opacity 0.2s; gap: 2px;
        }
        .avatar-drop-zone:hover .avatar-overlay { opacity: 1; }
        .avatar-overlay span { color: #fff; font-size: 11px; font-weight: 700; }

        /* Light mode */
        body.light-mode .profile-card { background: #fff; border-color: #e2e8f0; box-shadow: 0 2px 16px rgba(0,0,0,0.05); }
        body.light-mode .profile-card h2 { color: #1e293b !important; }
        body.light-mode .profile-card p  { color: #64748b !important; }
        body.light-mode .profile-card label { color: #374151 !important; }
        body.light-mode .profile-card .input-field { background: #f8fafc !important; border-color: #cbd5e1 !important; color: #1e293b !important; }
        body.light-mode .profile-card .input-field::placeholder { color: #94a3b8 !important; }
        body.light-mode .profile-card .text-white { color: #1e293b !important; }
        body.light-mode .profile-card .text-slate-300 { color: #374151 !important; }
        body.light-mode .profile-card .text-slate-400 { color: #64748b !important; }
        body.light-mode .profile-card .text-slate-500 { color: #94a3b8 !important; }
        body.light-mode .profile-card .text-slate-600 { color: #94a3b8 !important; }
        body.light-mode .profile-card .border-white\/10 { border-color: #e2e8f0 !important; }
        body.light-mode .profile-card .bg-white\/5 { background: #f1f5f9 !important; }
    </style>

    <div class="max-w-2xl space-y-6">

        {{-- ── Profile Picture Card ── --}}
        <div class="profile-card">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-xl bg-indigo-600/20 flex items-center justify-center text-base">📷</div>
                <div>
                    <h2 class="text-base font-semibold text-white leading-tight">Profile Picture</h2>
                    <p class="text-slate-500 text-xs">Visible to you and administrators</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                {{-- Clickable Avatar --}}
                <div class="avatar-drop-zone" onclick="document.getElementById('avatar-input').click()" title="Click to change photo">
                    <img id="avatar-preview" src="{{ auth()->user()->avatarUrl() }}" alt="Profile Picture">
                    <div class="avatar-overlay">
                        <span>📷</span>
                        <span>Change</span>
                    </div>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-semibold text-white mb-0.5">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 mb-4">{{ auth()->user()->email }}</p>

                    <div class="flex gap-2 flex-wrap">
                        <button type="button" onclick="document.getElementById('avatar-input').click()"
                                class="btn-primary text-xs py-2 px-4">
                            📷 Upload Photo
                        </button>
                        @if(auth()->user()->avatar_path)
                        <form method="POST" action="{{ route('profile.avatar.remove') }}" class="inline"
                              onsubmit="return confirm('Remove profile picture?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-secondary text-xs py-2 px-4">🗑️ Remove</button>
                        </form>
                        @endif
                    </div>
                    <p class="text-xs text-slate-600 mt-2">JPG, PNG, WEBP or GIF · Max 2 MB</p>
                </div>
            </div>

            {{-- Hidden upload form --}}
            <form method="POST" action="{{ route('profile.avatar.upload') }}" enctype="multipart/form-data" id="avatar-form">
                @csrf
                <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden"
                       onchange="previewAndSubmit(this)">
            </form>

            @if(session('status') === 'avatar-updated')
            <div class="mt-4 flash-success text-sm fade-in">✅ Profile picture updated successfully!</div>
            @endif
            @if(session('status') === 'avatar-removed')
            <div class="mt-4 flash-success text-sm fade-in">✅ Profile picture removed.</div>
            @endif
            @error('avatar')
            <div class="mt-4 flash-error text-sm">❌ {{ $message }}</div>
            @enderror
        </div>

        {{-- ── My History Link ── --}}
        <div class="profile-card flex items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-white mb-1">📋 My Translation History</h2>
                <p class="text-slate-500 text-sm">View all translations you've personally submitted. Private to you only.</p>
            </div>
            <a href="{{ route('profile.history') }}" class="btn-primary text-sm whitespace-nowrap flex-shrink-0">
                View History →
            </a>
        </div>

        {{-- ── Update Profile Info ── --}}
        <div class="profile-card">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-xl bg-blue-600/20 flex items-center justify-center text-base">👤</div>
                <div>
                    <h2 class="text-base font-semibold text-white leading-tight">Profile Information</h2>
                    <p class="text-slate-500 text-xs">Update your display name and email address</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf @method('PATCH')

                <div>
                    <label for="name" class="block text-sm text-slate-400 mb-1.5 font-medium">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="input-field" placeholder="Your full name">
                    @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm text-slate-400 mb-1.5 font-medium">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="input-field" placeholder="you@example.com">
                    @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="btn-primary">Save Changes</button>
                    @if(session('status') === 'profile-updated')
                    <span class="text-sm text-emerald-400 fade-in">✅ Saved!</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Change Password ── --}}
        <div class="profile-card">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-xl bg-amber-600/20 flex items-center justify-center text-base">🔑</div>
                <div>
                    <h2 class="text-base font-semibold text-white leading-tight">Change Password</h2>
                    <p class="text-slate-500 text-xs">Use a strong, unique password to keep your account secure</p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm text-slate-400 mb-1.5 font-medium">Current Password</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="input-field" placeholder="••••••••">
                    @error('current_password', 'updatePassword')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm text-slate-400 mb-1.5 font-medium">New Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" class="input-field" placeholder="Min 8 characters">
                    @error('password', 'updatePassword')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm text-slate-400 mb-1.5 font-medium">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="input-field" placeholder="Repeat new password">
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="btn-primary">Update Password</button>
                    @if(session('status') === 'password-updated')
                    <span class="text-sm text-emerald-400 fade-in">✅ Password updated!</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Delete Account ── --}}
        <div class="profile-card" style="border-color: rgba(220,38,38,0.2); background: rgba(220,38,38,0.03);">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-xl bg-red-600/20 flex items-center justify-center text-base">⚠️</div>
                <div>
                    <h2 class="text-base font-semibold text-red-400 leading-tight">Delete Account</h2>
                    <p class="text-slate-500 text-xs">Permanently removes all your data. Cannot be undone.</p>
                </div>
            </div>
            <button onclick="document.getElementById('delete-modal').classList.remove('hidden')" class="btn-danger text-sm">
                🗑️ Delete My Account
            </button>
        </div>

    </div>

    {{-- ── Delete Account Modal ── --}}
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4" style="background:rgba(2,8,23,.85); backdrop-filter:blur(8px);">
        <div class="card border border-red-900/40 p-8 max-w-md w-full" style="background:#0f172a;">
            <div class="text-center mb-6">
                <div class="text-4xl mb-3">⚠️</div>
                <h3 class="text-xl font-bold text-white mb-2">Confirm Account Deletion</h3>
                <p class="text-slate-400 text-sm">This action is permanent and irreversible. All your translations and data will be deleted.</p>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf @method('DELETE')

                <div>
                    <label for="del_password" class="block text-sm text-slate-400 mb-1.5">Enter your password to confirm</label>
                    <input id="del_password" name="password" type="password" required class="input-field" placeholder="••••••••" autofocus>
                    @error('password', 'userDeletion')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')" class="btn-secondary flex-1 text-sm">Cancel</button>
                    <button type="submit" class="btn-danger flex-1 text-sm">Delete Account</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function previewAndSubmit(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
        setTimeout(() => document.getElementById('avatar-form').submit(), 300);
    }
    </script>
</x-app-layout>
