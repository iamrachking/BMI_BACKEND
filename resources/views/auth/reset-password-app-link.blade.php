<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Réinitialisation du mot de passe</h2>

    <p class="mb-4 text-sm text-gray-600 text-center">
        Sur mobile, l’application BMI devrait s’ouvrir automatiquement. Si ce n’est pas le cas, ou si vous êtes sur ordinateur, cliquez ci-dessous pour accéder au formulaire de réinitialisation.
    </p>

    <a href="{{ $webResetUrl }}" class="block w-full text-center rounded-lg bg-[#2e4053] px-4 py-3 text-sm font-semibold text-white shadow hover:opacity-90 transition">
        Réinitialiser mon mot de passe (formulaire web)
    </a>

    <div class="mt-4 text-center">
        <a class="text-sm text-gray-600 hover:text-gray-900 transition" href="{{ route('login') }}">
            Retour à la connexion
        </a>
    </div>
</x-guest-layout>
