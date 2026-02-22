<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de bord') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root { --sidebar-bg: #2D2A4F; --sidebar-width: 260px; --header-h: 64px; }
        body { font-family: 'Figtree', sans-serif; }
        .sidebar-link.active { background: rgba(255,255,255,0.12); color: #fff; }
        .sidebar-link:hover:not(.active) { background: rgba(255,255,255,0.06); color: #e0e7ff; }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-[var(--sidebar-width)] bg-[var(--sidebar-bg)] text-gray-300 flex flex-col transition-transform duration-200 lg:translate-x-0 -translate-x-full" style="background-color: var(--sidebar-bg);">
            <div class="flex h-[var(--header-h)] items-center justify-between px-4 border-b border-white/10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-9 w-auto" />
                    <span class="font-semibold text-white text-lg hidden sm:inline">AI4BMI</span>
                </a>
                <button type="button" id="sidebar-close" class="p-2 rounded-lg hover:bg-white/10 lg:hidden" aria-label="Fermer le menu">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <ul class="space-y-1">
                    @foreach(\App\Helpers\SidebarMenu::items(Auth::user()) as $item)
                        <li>
                            @if(!empty($item['is_logout']))
                                <button type="button" onclick="handleLogout()" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-left text-gray-300 hover:bg-white/10 hover:text-indigo-100">
                                    <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                                    <span>{{ $item['label'] }}</span>
                                </button>
                            @else
                                <a href="{{ $item['url'] }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs($item['route'] ?? '') ? 'active' : '' }}">
                                    <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                                    <span>{{ $item['label'] }}</span>
                                    @if(!empty($item['badge']))<span class="ml-auto bg-indigo-500/80 text-xs px-2 py-0.5 rounded-full">{{ $item['badge'] }}</span>@endif
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>
            <div class="p-4 border-t border-white/10 text-center text-xs text-gray-400">
                © {{ date('Y') }} {{ config('app.name') }}
            </div>
        </aside>
        <div class="fixed inset-0 z-30 bg-black/50 hidden" id="sidebar-overlay" aria-hidden="true"></div>

        <!-- Main -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-[var(--sidebar-width)]">
            <!-- Header -->
            <header class="sticky top-0 z-20 flex h-[var(--header-h)] items-center justify-between gap-4 border-b border-gray-200 bg-white px-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <button type="button" id="sidebar-toggle" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 lg:hidden" aria-label="Ouvrir le menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-800 truncate">@yield('header', 'Tableau de bord')</h1>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <span class="relative p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100" title="Notifications">
                        <i class="fas fa-bell"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-emerald-500"></span>
                        @endif
                    </span>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 text-gray-700 min-w-0">
                            <span class="hidden sm:inline text-sm font-medium truncate">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->profilePhotoUrl())
                                <img src="{{ Auth::user()->profilePhotoUrl() }}" alt="" class="h-8 w-8 sm:h-9 sm:w-9 rounded-full object-cover border border-gray-200 shrink-0" />
                            @else
                                <div class="h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-medium shrink-0">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            @endif
                            <i class="fas fa-chevron-down text-xs text-gray-400 shrink-0"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute right-0 mt-1 w-48 rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-user mr-2 w-4"></i>Profil</a>
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <button type="button" onclick="handleLogout()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- jQuery (requis pour Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Configuration Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            showDuration: '300',
            hideDuration: '1000',
            timeOut: '4000',
            extendedTimeOut: '1000',
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };

        // Messages flash (succès / erreur) via Toastr
        (function() {
            var flash = @json(['success' => session('success'), 'error' => session('error')]);
            if (flash.success) toastr.success(flash.success, 'Succès');
            if (flash.error) toastr.error(flash.error, 'Erreur');
        })();

        // SweetAlert : déconnexion
        function handleLogout() {
            Swal.fire({
                title: 'Déconnexion',
                text: 'Êtes-vous sûr de vouloir vous déconnecter ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Oui, me déconnecter',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }

        // SweetAlert : confirmation des suppressions (formulaires avec .delete-form)
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var toggle = document.getElementById('sidebar-toggle');
            var closeBtn = document.getElementById('sidebar-close');
            function openSidebar() { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
            function closeSidebar() { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); }
            if (toggle) toggle.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            document.querySelectorAll('.delete-form').forEach(function(form) {
                form.removeAttribute('onsubmit');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var formEl = this;
                    var submitBtn = formEl.querySelector('button[type="submit"]');
                    var confirmText = formEl.getAttribute('data-confirm') || 'Cette action est irréversible !';
                    var title = formEl.getAttribute('data-title') || 'Êtes-vous sûr ?';

                    Swal.fire({
                        title: title,
                        text: confirmText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6366f1',
                        confirmButtonText: 'Oui, supprimer !',
                        cancelButtonText: 'Annuler',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Suppression...';
                            }
                            formEl.submit();
                        }
                    });
                    return false;
                }, true);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
