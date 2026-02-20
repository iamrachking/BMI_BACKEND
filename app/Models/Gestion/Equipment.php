<?php

namespace App\Models\Gestion;

use App\Models\Gestion\Failure;
use App\Models\Gestion\Maintenance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'name',
        'reference',
        'brand',
        'model',
        'installation_date',
        'status',
        'location',
        'description',
    ];

    protected $casts = [
        'installation_date' => 'date',
    ];

    /**
     * Get the maintenances for the equipment.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Get the failures for the equipment.
     */
    public function failures(): HasMany
    {
        return $this->hasMany(Failure::class);
    }
}
