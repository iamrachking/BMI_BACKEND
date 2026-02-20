<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'technicien'],
            ['name' => 'gestionnaire'],
            ['name' => 'customer'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
