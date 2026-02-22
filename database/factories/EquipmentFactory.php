<?php

namespace Database\Factories;

use App\Models\Gestion\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gestion\Equipment>
 */
class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['Siemens', 'Schneider', 'ABB', 'Rockwell', 'WEG', 'Leroy-Somer', 'Bonfiglioli', 'SEW'];
        $types = ['Pompe', 'Moteur', 'Convoyeur', 'Ventilateur', 'Compresseur', 'Variateur', 'Armoire', 'Capteur'];
        $type = fake()->randomElement($types);
        $ref = strtoupper(fake()->unique()->bothify('EQ-####'));

        return [
            'name' => $type . ' ' . fake()->optional(0.7)->randomElement(['principal', 'n°1', 'ligne A', 'atelier']) . ' - ' . fake()->buildingNumber(),
            'reference' => $ref,
            'brand' => fake()->randomElement($brands),
            'model' => fake()->optional(0.8)->regexify('[A-Z]{2}[0-9]{3}-[0-9]{2}'),
            'installation_date' => fake()->dateTimeBetween('-5 years', '-6 months'),
            'status' => fake()->randomElement(['active', 'active', 'active', 'maintenance']),
            'location' => fake()->randomElement(['Atelier A', 'Atelier B', 'Ligne 1', 'Ligne 2', 'Stock', 'Zone production']),
            'description' => fake()->optional(0.4)->sentence(8),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'active']);
    }

    public function panne(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'panne']);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'maintenance']);
    }
}
