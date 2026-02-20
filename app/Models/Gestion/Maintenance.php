<?php

namespace App\Models\Gestion;

use App\Models\Auth\User;
use App\Models\Gestion\Equipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'equipment_id',
        'user_id',
        'type',
        'description',
        'scheduled_date',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    /**
     * Get the equipment that owns the maintenance.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Get the user (technicien) assigned to the maintenance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
