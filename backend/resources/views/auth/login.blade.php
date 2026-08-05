<x-guest-layout>
    <h2 class="text-xl font-bold text-gray-800 mb-1">Welcome back</h2>
    <p class="text-sm text-gray-500 mb-6">Sign in to your organisation account</p>

    <!-- Session Status -->
    @if(session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="input-field" placeholder="you@organisation.gov.in">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Forgot password?</a>
                @endif
            </div>
            <div class="relative">
                <input id="password" type="password" name="password" required
                       class="input-field pr-10" placeholder="••••••••">
                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none">
                    <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600">
            <label for="remember_me" class="text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit" class="btn-primary mt-2">Sign In →</button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-4">
        Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Create Account</a>
    </p>

    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Change to Eye Off Icon (crossed eye)
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
            `;
        } else {
            passwordInput.type = 'password';
            // Change to Eye Icon
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }
    </script>
</x-guest-layout>
