@extends('gestion.layouts.dashboard')

@section('title', 'Maintenances')
@section('header', 'Maintenances')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form action="{{ route('maintenances.index') }}" method="get" class="flex flex-wrap items-center gap-2">
            <select name="status" class="rounded-lg border-gray-300 text-sm">
                <option value="">Tous les statuts</option>
                <option value="planifie" {{ request('status') === 'planifie' ? 'selected' : '' }}>Planifié</option>
                <option value="en_cours" {{ request('status') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="termine" {{ request('status') === 'termine' ? 'selected' : '' }}>Terminé</option>
            </select>
            <button type="submit" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filtrer</button>
        </form>
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))
            <a href="{{ route('maintenances.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">
                <i class="fas fa-plus"></i> Planifier une maintenance
            </a>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Équipement</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date prévue</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Technicien</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($maintenances as $m)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('equipments.show', $m->equipment) }}" class="font-medium text-indigo-600 hover:text-indigo-700">{{ $m->equipment->name }}</a>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $m->type }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $m->scheduled_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $m->user->name }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($m->status === 'termine') bg-gray-100 text-gray-700
                                    @elseif($m->status === 'en_cours') bg-indigo-100 text-indigo-700
                                    @else bg-amber-100 text-amber-700 @endif">{{ $m->status }}</span>
                            </td>
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('maintenances.edit', $m) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">Modifier</a>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire') ? 6 : 5 }}" class="px-5 py-10 text-center text-gray-500">Aucune maintenance.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($maintenances->hasPages())
            <div class="px-5 py-3 border-t border-gray-200">{{ $maintenances->links() }}</div>
        @endif
    </div>
</div>
@endsection
