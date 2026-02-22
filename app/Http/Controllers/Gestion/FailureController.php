<?php

namespace App\Http\Controllers\Gestion;

use App\Mail\FailureAssignedNotification;
use App\Models\Auth\User;
use App\Models\Gestion\Equipment;
use App\Models\Gestion\Failure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class FailureController extends BaseController
{
    public function index(Request $request): View
    {
        $query = Failure::query()->with(['equipment', 'assignedTo']);

        $user = auth()->user();
        if ($user->hasRole('technicien')) {
            $query->where('assigned_to', $user->id);
        } else {
            if ($request->filled('severity')) {
                $query->where('severity', $request->severity);
            }
            if ($request->filled('equipment_id')) {
                $query->where('equipment_id', $request->equipment_id);
            }
        }

        $failures = $query->latest('detected_at')->paginate(15)->withQueryString();
        $equipments = Equipment::orderBy('name')->get();

        return view('gestion.failures.index', compact('failures', 'equipments'));
    }

    public function show(Failure $failure): View
    {
        $failure->load(['equipment', 'assignedTo']);
        $techniciens = collect();
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('gestionnaire')) {
            $techniciens = User::whereHas('role', fn ($q) => $q->where('name', 'technicien'))->orderBy('name')->get();
        }

        return view('gestion.failures.show', compact('failure', 'techniciens'));
    }

    public function create(): View
    {
        $equipments = Equipment::orderBy('name')->get();
        $techniciens = User::whereHas('role', fn ($q) => $q->where('name', 'technicien'))->orderBy('name')->get();

        return view('gestion.failures.create', compact('equipments', 'techniciens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'detected_at'  => 'required|date',
            'severity'     => 'required|in:faible,moyen,critique',
            'description'  => 'nullable|string',
            'assigned_to'  => 'nullable|exists:users,id',
        ]);

        $assignedTo = $validated['assigned_to'] ?? null;
        unset($validated['assigned_to']);
        if ($assignedTo) {
            $validated['assigned_to'] = $assignedTo;
            $validated['assigned_at'] = now();
        }

        $failure = Failure::create($validated);
        $failure->equipment->update(['status' => 'panne']);

        if ($failure->assigned_to) {
            $failure->load(['equipment', 'assignedTo']);
            Mail::to($failure->assignedTo->email)->send(new FailureAssignedNotification($failure));
        }

        return redirect()->route('failures.index')->with('success', 'Panne enregistrée.' . ($failure->assigned_to ? ' Le technicien a été notifié par email.' : ''));
    }

    public function update(Request $request, Failure $failure): RedirectResponse
    {
        $user = auth()->user();
        $canResolve = $failure->assigned_to && (string) $failure->assigned_to === (string) $user->id;
        $canAssign = $user->hasRole('admin') || $user->hasRole('gestionnaire');

        // Action "Marquer comme résolue" (technicien assigné ou admin/gestionnaire)
        if ($request->has('mark_resolved') && ($canResolve || $canAssign)) {
            $request->validate(['intervention_report' => 'required|string|max:65535']);

            $failure->resolved_at = now();
            $failure->intervention_report = $request->input('intervention_report');
            $failure->save();

            // Ne repasser l'équipement en « actif » que s'il n'a plus d'autre panne non résolue
            $otherOpenFailure = Failure::where('equipment_id', $failure->equipment_id)
                ->whereNull('resolved_at')
                ->where('id', '!=', $failure->id)
                ->exists();
            if (! $otherOpenFailure) {
                $failure->equipment->update(['status' => 'active']);
            }

            return redirect()->route('failures.show', $failure)->with('success', 'Panne marquée comme résolue. Rapport d\'intervention enregistré.');
        }

        // Assignation (admin / gestionnaire uniquement)
        if (! $canAssign) {
            return redirect()->route('failures.show', $failure)->with('error', 'Action non autorisée.');
        }

        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $previousAssignedTo = $failure->assigned_to;
        $newAssignedTo = $validated['assigned_to'] ?? null;

        $failure->assigned_to = $newAssignedTo;
        $failure->assigned_at = $newAssignedTo ? now() : null;
        $failure->save();

        if ($newAssignedTo && (string) $newAssignedTo !== (string) $previousAssignedTo) {
            $failure->load(['equipment', 'assignedTo']);
            Mail::to($failure->assignedTo->email)->send(new FailureAssignedNotification($failure));
        }

        return redirect()->route('failures.show', $failure)->with('success', 'Assignation mise à jour.' . ($newAssignedTo && (string) $newAssignedTo !== (string) $previousAssignedTo ? ' Le technicien a été notifié par email.' : ''));
    }
}
