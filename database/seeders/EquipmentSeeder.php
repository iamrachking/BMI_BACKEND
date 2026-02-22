<?php

namespace Database\Seeders;

use App\Models\Gestion\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Crée au moins 10 équipements (via factory ou liste fixe).
     */
    public function run(): void
    {
        $equipments = [
            ['name' => 'Pompe hydraulique ligne 1', 'reference' => 'EQ-P001', 'brand' => 'Siemens', 'model' => 'PM100-02', 'location' => 'Atelier A'],
            ['name' => 'Moteur convoyeur principal', 'reference' => 'EQ-M002', 'brand' => 'WEG', 'model' => 'W22-15', 'location' => 'Ligne 1'],
            ['name' => 'Ventilateur extraction atelier', 'reference' => 'EQ-V003', 'brand' => 'Schneider', 'model' => 'VF-200', 'location' => 'Atelier B'],
            ['name' => 'Compresseur air comprimé', 'reference' => 'EQ-C004', 'brand' => 'ABB', 'model' => 'AC-50', 'location' => 'Zone technique'],
            ['name' => 'Variateur vitesse moteur 2', 'reference' => 'EQ-V005', 'brand' => 'Schneider', 'model' => 'ATV320', 'location' => 'Ligne 1'],
            ['name' => 'Armoire électrique ligne A', 'reference' => 'EQ-A006', 'brand' => 'Rockwell', 'model' => 'CAB-12', 'location' => 'Atelier A'],
            ['name' => 'Pompe refroidissement', 'reference' => 'EQ-P007', 'brand' => 'Leroy-Somer', 'model' => 'LS-30', 'location' => 'Atelier B'],
            ['name' => 'Convoyeur à bande sortie', 'reference' => 'EQ-C008', 'brand' => 'Bonfiglioli', 'model' => 'CONV-08', 'location' => 'Ligne 2'],
            ['name' => 'Capteur niveau cuve', 'reference' => 'EQ-S009', 'brand' => 'Siemens', 'model' => 'SN-400', 'location' => 'Stock'],
            ['name' => 'Moteur élévateur', 'reference' => 'EQ-M010', 'brand' => 'SEW', 'model' => 'DR-63', 'location' => 'Zone production'],
        ];

        foreach ($equipments as $i => $eq) {
            Equipment::firstOrCreate(
                ['reference' => $eq['reference']],
                [
                    'name' => $eq['name'],
                    'brand' => $eq['brand'],
                    'model' => $eq['model'],
                    'location' => $eq['location'],
                    'installation_date' => now()->subYears(rand(1, 5))->subDays(rand(0, 365)),
                    'status' => $i < 8 ? 'active' : 'active', // tous actifs par défaut
                    'description' => null,
                ]
            );
        }

        // Option : créer des équipements supplémentaires avec la factory si on veut plus de 10
        if (Equipment::count() < 10) {
            Equipment::factory()->count(10 - Equipment::count())->active()->create();
        }
    }
}
