@extends('gestion.layouts.dashboard')

@section('title', 'Tableau de bord')
@section('header', 'Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- Cartes stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Équipements actifs</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['equipments_active'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">sur {{ $stats['equipments_total'] }} au total</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-check text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">En panne</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['equipments_panne'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <i class="fas fa-triangle-exclamation text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">En maintenance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['equipments_maintenance'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-wrench text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Maintenances à venir</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['maintenances_planifie'] + $stats['maintenances_en_cours'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-calendar-check text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pannes récentes -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Pannes récentes</h2>
                <a href="{{ route('failures.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Voir tout</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($stats['failures_recent'] as $failure)
                    <a href="{{ route('failures.show', $failure) }}" class="block px-4 sm:px-5 py-3 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-800">{{ $failure->equipment->name }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($failure->severity === 'critique') bg-red-100 text-red-700
                                @elseif($failure->severity === 'moyen') bg-amber-100 text-amber-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $failure->severity }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $failure->detected_at->format('d/m/Y H:i') }}</p>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-gray-500 text-sm">Aucune panne enregistrée.</div>
                @endforelse
            </div>
        </div>

        <!-- Mes maintenances (technicien) -->
        @if(auth()->user()->hasRole('technicien') && isset($stats['my_maintenances']))
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Mes maintenances à faire</h2>
                    <a href="{{ route('maintenances.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Voir tout</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($stats['my_maintenances'] as $m)
                        <a href="{{ route('maintenances.edit', $m) }}" class="block px-4 sm:px-5 py-3 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-medium text-gray-800 truncate">{{ $m->equipment->name }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full shrink-0
                                    @if($m->status === 'en_cours') bg-indigo-100 text-indigo-700
                                    @else bg-gray-100 text-gray-600 @endif">{{ $m->status }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $m->scheduled_date->format('d/m/Y') }} · {{ $m->type }}</p>
                        </a>
                    @empty
                        <div class="px-4 sm:px-5 py-8 text-center text-gray-500 text-sm">Aucune maintenance planifiée.</div>
                    @endforelse
                </div>
            </div>
            @if(isset($stats['my_assigned_failures']) && $stats['my_assigned_failures']->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Mes pannes assignées</h2>
                    <a href="{{ route('failures.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Voir tout</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($stats['my_assigned_failures'] as $f)
                        <a href="{{ route('failures.show', $f) }}" class="block px-4 sm:px-5 py-3 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-medium text-gray-800 truncate">{{ $f->equipment->name }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full shrink-0
                                    @if($f->severity === 'critique') bg-red-100 text-red-700
                                    @elseif($f->severity === 'moyen') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-600 @endif">{{ $f->severity }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $f->detected_at->format('d/m/Y H:i') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <!-- Résumé maintenances (admin / gestionnaire) -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Résumé des maintenances</h2>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-sm text-gray-500">Planifiées</p>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['maintenances_planifie'] }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-sm text-gray-500">En cours</p>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['maintenances_en_cours'] }}</p>
                    </div>
                </div>
                <div class="px-5 pb-5">
                    <a href="{{ route('maintenances.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        <i class="fas fa-wrench"></i> Gérer les maintenances
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
