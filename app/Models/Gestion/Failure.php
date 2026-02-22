<?php

namespace App\Models\Gestion;

use App\Models\Auth\User;
use App\Models\Gestion\Equipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Failure extends Model
{
    protected $fillable = [
        'equipment_id',
        'detected_at',
        'severity',
        'description',
        'resolved_at',
        'assigned_to',
        'assigned_at',
        'intervention_report',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the equipment that owns the failure.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Get the user (technicien) assigned to this failure.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
