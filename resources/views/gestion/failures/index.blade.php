@extends('gestion.layouts.dashboard')

@section('title', 'Historique des pannes')
@section('header', 'Historique des pannes')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        @if(!auth()->user()->hasRole('technicien'))
            <form action="{{ route('failures.index') }}" method="get" class="flex flex-wrap items-center gap-2">
                <select name="severity" class="rounded-lg border-gray-300 text-sm w-full sm:w-auto">
                    <option value="">Toutes gravités</option>
                    <option value="faible" {{ request('severity') === 'faible' ? 'selected' : '' }}>Faible</option>
                    <option value="moyen" {{ request('severity') === 'moyen' ? 'selected' : '' }}>Moyen</option>
                    <option value="critique" {{ request('severity') === 'critique' ? 'selected' : '' }}>Critique</option>
                </select>
                <select name="equipment_id" class="rounded-lg border-gray-300 text-sm min-w-0 sm:min-w-[180px]">
                    <option value="">Tous les équipements</option>
                    @foreach($equipments as $eq)
                        <option value="{{ $eq->id }}" {{ request('equipment_id') == $eq->id ? 'selected' : '' }}>{{ $eq->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filtrer</button>
            </form>
        @else
            <p class="text-sm text-gray-600">Pannes qui vous sont assignées.</p>
        @endif
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire'))
            <a href="{{ route('failures.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447] shrink-0">
                <i class="fas fa-plus"></i> Déclarer une panne
            </a>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Équipement</th>
                        @if(!auth()->user()->hasRole('technicien'))
                            <th class="px-4 sm:px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Assigné à</th>
                        @endif
                        <th class="px-4 sm:px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Détectée le</th>
                        <th class="px-4 sm:px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gravité</th>
                        <th class="px-4 sm:px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Résolue le</th>
                        <th class="px-4 sm:px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($failures as $f)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 sm:px-5 py-3">
                                <a href="{{ route('failures.show', $f) }}" class="font-medium text-indigo-600 hover:text-indigo-700">{{ $f->equipment->name }}</a>
                            </td>
                            @if(!auth()->user()->hasRole('technicien'))
                                <td class="px-4 sm:px-5 py-3 text-sm text-gray-700 hidden sm:table-cell">{{ $f->assignedTo?->name ?? '—' }}</td>
                            @endif
                            <td class="px-4 sm:px-5 py-3 text-sm text-gray-700">{{ $f->detected_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 sm:px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($f->severity === 'critique') bg-red-100 text-red-700
                                    @elseif($f->severity === 'moyen') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-700 @endif">{{ $f->severity }}</span>
                            </td>
                            <td class="px-4 sm:px-5 py-3 text-sm text-gray-700 hidden md:table-cell">{{ $f->resolved_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 sm:px-5 py-3 text-right">
                                <a href="{{ route('failures.show', $f) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">Détail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ auth()->user()->hasRole('technicien') ? 5 : 6 }}" class="px-4 sm:px-5 py-10 text-center text-gray-500">Aucune panne.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($failures->hasPages())
            <div class="px-5 py-3 border-t border-gray-200">{{ $failures->links() }}</div>
        @endif
    </div>
</div>
@endsection
