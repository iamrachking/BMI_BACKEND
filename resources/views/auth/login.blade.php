<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    <!-- Form Title -->
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Se Connecter</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Mot de Passe')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="Mot de Passe" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Login Button -->
        <x-primary-button class="w-full">
            {{ __('Se Connecter') }}
        </x-primary-button>

        <!-- Mot de passe oublié -->
        @if (Route::has('password.request'))
            <div class="mt-4 text-center">
                <a class="text-sm text-gray-600 hover:text-gray-900 transition" href="{{ route('password.request') }}">
                    {{ __('Mot de Passe Oublié ?') }}
                </a>
            </div>
        @endif

        <!-- Confidentialités -->
        <div class="mt-4 pt-4 border-t border-gray-100 text-center">
            <a href="#" class="inline-flex items-center justify-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>Confidentialités Générales d'Utilisation</span>
            </a>
        </div>
    </form>
</x-guest-layout>
