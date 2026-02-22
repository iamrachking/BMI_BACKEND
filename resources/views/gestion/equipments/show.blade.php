@extends('gestion.layouts.dashboard')

@section('title', $equipment->name)
@section('header', $equipment->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                @if($equipment->status === 'active') bg-emerald-100 text-emerald-800
                @elseif($equipment->status === 'panne') bg-red-100 text-red-800
                @else bg-amber-100 text-amber-800 @endif">
                {{ $equipment->status }}
            </span>
            @if($equipment->reference)<span class="text-sm text-gray-500">{{ $equipment->reference }}</span>@endif
        </div>
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))
            <div class="flex gap-2">
                <a href="{{ route('equipments.edit', $equipment) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-pen"></i> Modifier
                </a>
                <form action="{{ route('equipments.destroy', $equipment) }}" method="post" class="delete-form" data-title="Supprimer cet équipement ?" data-confirm="Toutes les maintenances et l'historique des pannes associés seront également supprimés. Cette action est irréversible.">
                    @csrf
                    @method('delete')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800 mb-4">Fiche technique</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><dt class="text-sm text-gray-500">Marque</dt><dd class="font-medium text-gray-900">{{ $equipment->brand ?? '—' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Modèle</dt><dd class="font-medium text-gray-900">{{ $equipment->model ?? '—' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Date d'installation</dt><dd class="font-medium text-gray-900">{{ $equipment->installation_date?->format('d/m/Y') ?? '—' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Emplacement</dt><dd class="font-medium text-gray-900">{{ $equipment->location ?? '—' }}</dd></div>
                </dl>
                @if($equipment->description)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <dt class="text-sm text-gray-500">Description</dt>
                        <dd class="mt-1 text-gray-700">{{ $equipment->description }}</dd>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Historique des maintenances</h3>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))
                        <a href="{{ route('maintenances.create') }}?equipment_id={{ $equipment->id }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">+ Ajouter</a>
                    @endif
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse($equipment->maintenances as $m)
                        <li class="px-5 py-3 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-800">{{ $m->type }}</span>
                                <span class="text-xs text-gray-500 ml-2">{{ $m->scheduled_date->format('d/m/Y') }}</span>
                                @if($m->user)<span class="text-sm text-gray-500 block">{{ $m->user->name }}</span>@endif
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($m->status === 'termine') bg-gray-100 text-gray-600
                                @elseif($m->status === 'en_cours') bg-indigo-100 text-indigo-700
                                @else bg-amber-100 text-amber-700 @endif">{{ $m->status }}</span>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-gray-500 text-sm">Aucune maintenance.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Pannes</h3>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))
                        <a href="{{ route('failures.create') }}?equipment_id={{ $equipment->id }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">+ Déclarer</a>
                    @endif
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse($equipment->failures as $f)
                        <li class="px-5 py-3">
                            <a href="{{ route('failures.show', $f) }}" class="block">
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    @if($f->severity === 'critique') bg-red-100 text-red-700
                                    @elseif($f->severity === 'moyen') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-600 @endif">{{ $f->severity }}</span>
                                <p class="text-sm text-gray-700 mt-1">{{ $f->detected_at->format('d/m/Y H:i') }}</p>
                                @if($f->resolved_at)<p class="text-xs text-gray-500">Résolue le {{ $f->resolved_at->format('d/m/Y') }}</p>@endif
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-gray-500 text-sm">Aucune panne.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
