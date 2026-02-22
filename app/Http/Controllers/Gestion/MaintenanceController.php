<?php

namespace App\Http\Controllers\Gestion;

use App\Models\Auth\User;
use App\Models\Gestion\Equipment;
use App\Models\Gestion\Maintenance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends BaseController
{
    public function index(Request $request): View
    {
        $query = Maintenance::query()->with(['equipment', 'user']);

        $user = auth()->user();
        if ($user->hasRole('technicien')) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $maintenances = $query->latest('scheduled_date')->paginate(15)->withQueryString();

        return view('gestion.maintenances.index', compact('maintenances'));
    }

    public function create(): View
    {
        $equipments = Equipment::orderBy('name')->get();
        $techniciens = User::whereHas('role', fn ($q) => $q->where('name', 'technicien'))->orderBy('name')->get();

        return view('gestion.maintenances.create', compact('equipments', 'techniciens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'equipment_id'   => 'required|exists:equipments,id',
            'user_id'       => 'required|exists:users,id',
            'type'          => 'required|in:preventive,corrective',
            'description'   => 'nullable|string',
            'scheduled_date' => 'required|date',
            'status'        => 'required|in:planifie,en_cours,termine',
        ]);

        Maintenance::create($validated);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance enregistrée.');
    }

    public function edit(Maintenance $maintenance): View
    {
        $maintenance->load('equipment');
        $equipments = Equipment::orderBy('name')->get();
        $techniciens = User::whereHas('role', fn ($q) => $q->where('name', 'technicien'))->orderBy('name')->get();

        return view('gestion.maintenances.edit', compact('maintenance', 'equipments', 'techniciens'));
    }

    public function update(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $validated = $request->validate([
            'equipment_id'   => 'required|exists:equipments,id',
            'user_id'       => 'required|exists:users,id',
            'type'          => 'required|in:preventive,corrective',
            'description'   => 'nullable|string',
            'scheduled_date' => 'required|date',
            'status'        => 'required|in:planifie,en_cours,termine',
        ]);

        $maintenance->update($validated);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance mise à jour.');
    }
}
