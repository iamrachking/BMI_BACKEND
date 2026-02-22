<?php

namespace App\Http\Controllers\Gestion;

use App\Models\Gestion\Equipment;
use App\Models\Gestion\Failure;
use App\Models\Gestion\Maintenance;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends BaseController
{
    public function __invoke(): View
    {
        $user = Auth::user();

        $stats = [
            'equipments_total'   => Equipment::count(),
            'equipments_active'  => Equipment::where('status', 'active')->count(),
            'equipments_panne'   => Equipment::where('status', 'panne')->count(),
            'equipments_maintenance' => Equipment::where('status', 'maintenance')->count(),
            'maintenances_planifie' => Maintenance::where('status', 'planifie')->count(),
            'maintenances_en_cours' => Maintenance::where('status', 'en_cours')->count(),
            'failures_recent'    => Failure::with('equipment')->latest('detected_at')->take(5)->get(),
        ];

        if ($user->hasRole('technicien')) {
            $stats['my_maintenances'] = Maintenance::where('user_id', $user->id)
                ->whereIn('status', ['planifie', 'en_cours'])
                ->with('equipment')
                ->orderBy('scheduled_date')
                ->get();
            $stats['my_assigned_failures'] = Failure::where('assigned_to', $user->id)
                ->whereNull('resolved_at')
                ->with('equipment')
                ->latest('assigned_at')
                ->take(10)
                ->get();
        }

        return view('gestion.dashboard.index', compact('stats'));
    }
}
