<x-guest-layout>
    <h2 class="text-xl font-bold text-gray-800 mb-1">Create Account</h2>
    <p class="text-sm text-gray-500 mb-6">Register to access the translation portal</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="input-field" placeholder="Your name">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="input-field" placeholder="you@organisation.gov.in">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required
                   class="input-field" placeholder="Min 8 characters">
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="input-field" placeholder="Repeat password">
        </div>

        <button type="submit" class="btn-primary mt-2">Create Account →</button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Already registered? <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Sign in</a>
        </p>
    </form>
</x-guest-layout>
