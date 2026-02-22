@extends('gestion.layouts.dashboard')

@section('title', 'Détail panne')
@section('header', 'Détail de la panne')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 shadow-sm space-y-4">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><dt class="text-sm text-gray-500">Équipement</dt><dd class="font-medium"><a href="{{ route('equipments.show', $failure->equipment) }}" class="text-indigo-600 hover:text-indigo-700">{{ $failure->equipment->name }}</a></dd></div>
            <div><dt class="text-sm text-gray-500">Détectée le</dt><dd class="font-medium text-gray-900">{{ $failure->detected_at->format('d/m/Y H:i') }}</dd></div>
            <div><dt class="text-sm text-gray-500">Gravité</dt>
                <dd>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        @if($failure->severity === 'critique') bg-red-100 text-red-700
                        @elseif($failure->severity === 'moyen') bg-amber-100 text-amber-700
                        @else bg-gray-100 text-gray-700 @endif">{{ $failure->severity }}</span>
                </dd>
            </div>
            <div><dt class="text-sm text-gray-500">Résolue le</dt><dd class="font-medium text-gray-900">{{ $failure->resolved_at?->format('d/m/Y H:i') ?? 'Non résolue' }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-sm text-gray-500">Assignée à</dt><dd class="font-medium text-gray-900">{{ $failure->assignedTo?->name ?? '—' }} @if($failure->assigned_at)<span class="text-gray-500 text-sm">({{ $failure->assigned_at->format('d/m/Y H:i') }})</span>@endif</dd></div>
        </dl>
        @if($failure->description)
            <div class="pt-4 border-t border-gray-100">
                <dt class="text-sm text-gray-500">Description</dt>
                <dd class="mt-1 text-gray-700">{{ $failure->description }}</dd>
            </div>
        @endif

        @if($failure->resolved_at)
            <div class="pt-4 border-t border-gray-100">
                <dt class="text-sm text-gray-500">Rapport d'intervention</dt>
                <dd class="mt-1 text-gray-700 whitespace-pre-wrap">{{ $failure->intervention_report ?? '—' }}</dd>
            </div>
        @endif

        @php
            $isAssignedTechnician = $failure->assigned_to && (string) $failure->assigned_to === (string) auth()->id();
            $canResolve = ($isAssignedTechnician || auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire')) && !$failure->resolved_at;
        @endphp
        @if($canResolve)
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Marquer comme résolue</h3>
                <p class="text-sm text-gray-500 mb-3">Après réparation sur le terrain, enregistrez le rapport d'intervention et marquez la panne comme résolue. L'équipement repassera en statut « actif ».</p>
                <form action="{{ route('failures.update', $failure) }}" method="post" class="space-y-3">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="mark_resolved" value="1">
                    <div>
                        <label for="intervention_report" class="block text-sm font-medium text-gray-700 mb-1">Rapport d'intervention <span class="text-red-500">*</span></label>
                        <textarea name="intervention_report" id="intervention_report" rows="4" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Décrivez les actions réalisées, pièces remplacées, etc.">{{ old('intervention_report') }}</textarea>
                        @error('intervention_report')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">Marquer comme résolue</button>
                </form>
            </div>
        @endif

        @if((auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire')) && $techniciens->isNotEmpty())
            <div class="pt-4 border-t border-gray-100">
                <form action="{{ route('failures.update', $failure) }}" method="post" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
                    @csrf
                    @method('patch')
                    <div class="flex-1 min-w-0">
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assigner / modifier le technicien</label>
                        <select name="assigned_to" id="assigned_to" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">— Aucun —</option>
                            @foreach($techniciens as $t)
                                <option value="{{ $t->id }}" {{ old('assigned_to', $failure->assigned_to) == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447] shrink-0">Enregistrer</button>
                </form>
            </div>
        @endif

        <div class="pt-4 flex flex-wrap gap-3">
            <a href="{{ route('failures.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">← Retour à l'historique</a>
        </div>
    </div>
</div>
@endsection
