<x-guest-layout>
    <!-- Form Title -->
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Mot de Passe Oublié</h2>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Indiquez votre adresse email et nous vous enverrons un lien de réinitialisation de mot de passe.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <x-primary-button class="w-full">
            {{ __('Envoyer le Lien de Réinitialisation') }}
        </x-primary-button>

        <div class="mt-4 text-center">
            <a class="text-sm text-gray-600 hover:text-gray-900 transition" href="{{ route('login') }}">
                {{ __('Retour à la connexion') }}
            </a>
        </div>

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
