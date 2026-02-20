<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AI4BMI') }} - Authentification</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                /* Taille du logo : modifiez cette valeur pour changer la taille (ex: 6rem, 8rem, 10rem, 12rem, 200px...) */
                --auth-logo-height: 13rem;
            }
            .auth-background {
                background-image: url('{{ asset('images/background.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 100vh;
            }
            .auth-logo {
                height: var(--auth-logo-height);
                width: auto;
                object-fit: contain;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex auth-background">
            <!-- Left Section - Branding (desktop only) -->
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-center items-center pl-32 pr-16">
                <div class="max-w-lg text-center">
                    <!-- Logo -->
                    <div class="mb-6 flex justify-center">
                        <img src="{{ asset('images/bmi-logo-removebg.png') }}" alt="BMI Logo" class="auth-logo">
                    </div>
                    
                    <!-- Description -->
                    <p class="text-xl font-bold text-white leading-relaxed px-4">
                        Plateforme web de Gestion et de Suivi des Équipements Industriels à Bénin Moto Industry
                    </p>
                </div>
            </div>

            <!-- Right Section - Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12">
                <div class="w-full max-w-md">
                    <!-- Form Card (mobile: formulaire seul, sans logo ni texte) -->
                    <div class="bg-white rounded-lg shadow-xl p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
