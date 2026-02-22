@extends('gestion.layouts.dashboard')

@section('title', 'Équipements')
@section('header', 'Équipements')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form action="{{ route('equipments.index') }}" method="get" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher…" class="rounded-lg border-gray-300 text-sm w-48">
            <select name="status" class="rounded-lg border-gray-300 text-sm">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="panne" {{ request('status') === 'panne' ? 'selected' : '' }}>Panne</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <button type="submit" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200"><i class="fas fa-search mr-1"></i> Filtrer</button>
        </form>
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))
            <a href="{{ route('equipments.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">
                <i class="fas fa-plus"></i> Ajouter un équipement
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($equipments as $equipment)
            <a href="{{ route('equipments.show', $equipment) }}" class="block bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $equipment->name }}</h3>
                        @if($equipment->reference)<p class="text-sm text-gray-500 mt-0.5">{{ $equipment->reference }}</p>@endif
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($equipment->status === 'active') bg-emerald-100 text-emerald-800
                                @elseif($equipment->status === 'panne') bg-red-100 text-red-800
                                @else bg-amber-100 text-amber-800 @endif">
                                {{ $equipment->status }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $equipment->maintenances_count }} maintenances</span>
                            <span class="text-xs text-gray-400">{{ $equipment->failures_count }} panne(s) en cours</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 mt-1"></i>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-xl border border-gray-200 bg-gray-50 p-12 text-center text-gray-500">
                Aucun équipement. @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))<a href="{{ route('equipments.create') }}" class="text-indigo-600 hover:underline">Ajouter le premier</a>@endif
            </div>
        @endforelse
    </div>

    @if($equipments->hasPages())
        <div class="mt-4">{{ $equipments->links() }}</div>
    @endif
</div>
@endsection
