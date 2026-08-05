<x-app-layout>
    <x-slot name="header">Admin — Edit {{ $user->name }}</x-slot>

    <div class="max-w-2xl fade-in">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                ← Back to Users
            </a>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100" style="background: linear-gradient(135deg, #7c3aed, #4f46e5);">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden ring-4 ring-white/20 flex-shrink-0">
                        <img src="{{ $user->avatarUrl() }}"
                             alt="{{ $user->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">{{ $user->name }}</h2>
                        <p class="text-purple-200 text-sm">{{ $user->email }}</p>
                        @if($user->avatar_path)
                        <p class="text-purple-300 text-xs mt-0.5">📷 Has profile picture</p>
                        @else
                        <p class="text-purple-400 text-xs mt-0.5">No profile picture uploaded</p>
                        @endif
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="input-field @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="input-field @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current"
                               class="input-field @error('password') border-red-400 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Minimum 8 characters. Leave blank to keep existing password.</p>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                        <select name="role" required class="input-field @error('role') border-red-400 @enderror">
                            <option value="translator"  {{ old('role', $user->role) === 'translator'  ? 'selected' : '' }}>Translator</option>
                            <option value="admin"       {{ old('role', $user->role) === 'admin'       ? 'selected' : '' }}>Admin</option>
                            @if(auth()->user()->isSuperAdmin())
                            <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            @endif
                        </select>
                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    @if(auth()->user()->isSuperAdmin())
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Organisation</label>
                    <select name="organisation_id" class="input-field @error('organisation_id') border-red-400 @enderror">
                        <option value="">None (Super Admin / No Org)</option>
                        @foreach($organisations as $org)
                        <option value="{{ $org->id }}" {{ old('organisation_id', $user->organisation_id) == $org->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('organisation_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    @else
                    <label class="block text-sm font-medium text-gray-500 mb-1.5">Organisation</label>
                    <div class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                        <span class="text-sm font-semibold text-gray-800">{{ auth()->user()->organisation->name }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary text-sm inline-flex items-center gap-2">
                        💾 Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm">Cancel</a>
                    @if($user->id !== auth()->id())
                    <button type="button"
                            onclick="if (confirm('Delete {{ $user->name }}? This cannot be undone.')) { document.getElementById('delete-user-form').submit(); }"
                            class="btn-secondary text-sm text-red-500 hover:border-red-400 ml-auto">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                    @endif
                </div>
            </form>

            @if($user->id !== auth()->id())
            <form id="delete-user-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            @endif
        </div>
    </div>
</x-app-layout>
