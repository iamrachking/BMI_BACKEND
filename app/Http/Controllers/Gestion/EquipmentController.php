<?php

namespace App\Http\Controllers\Gestion;

use App\Models\Gestion\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends BaseController
{
    public function index(Request $request): View
    {
        $query = Equipment::query()
            ->withCount(['maintenances'])
            ->withCount(['failures as failures_count' => fn ($q) => $q->whereNull('resolved_at')]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('reference', 'like', "%{$s}%")
                    ->orWhere('brand', 'like', "%{$s}%");
            });
        }

        $equipments = $query->latest()->paginate(12)->withQueryString();

        return view('gestion.equipments.index', compact('equipments'));
    }

    public function create(): View
    {
        return view('gestion.equipments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'reference'         => 'nullable|string|max:100',
            'brand'             => 'nullable|string|max:100',
            'model'             => 'nullable|string|max:100',
            'installation_date' => 'nullable|date',
            'status'            => 'required|in:active,panne,maintenance',
            'location'          => 'nullable|string|max:255',
            'description'       => 'nullable|string',
        ]);

        Equipment::create($validated);

        return redirect()->route('equipments.index')->with('success', 'Équipement créé avec succès.');
    }

    public function show(Equipment $equipment): View
    {
        $equipment->load(['maintenances.user', 'failures']);

        return view('gestion.equipments.show', compact('equipment'));
    }

    public function edit(Equipment $equipment): View
    {
        return view('gestion.equipments.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'reference'         => 'nullable|string|max:100',
            'brand'             => 'nullable|string|max:100',
            'model'             => 'nullable|string|max:100',
            'installation_date' => 'nullable|date',
            'status'            => 'required|in:active,panne,maintenance',
            'location'          => 'nullable|string|max:255',
            'description'       => 'nullable|string',
        ]);

        $equipment->update($validated);

        return redirect()->route('equipments.show', $equipment)->with('success', 'Équipement mis à jour.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        $equipment->delete();

        return redirect()->route('equipments.index')->with('success', 'Équipement supprimé.');
    }
}
