<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('Le rôle admin n\'existe pas. Exécutez d\'abord RoleSeeder.');
            return;
        }

        User::updateOrCreate(
            ['email' => 'abdoulrachard@gmail.com'],
            [
                'name' => 'Administrateur',
                'email' => 'abdoulrachard@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'phone' => null,
                'address' => null,
            ]
        );

        $this->command->info('Administrateur créé : abdoulrachard@gmail.com (mot de passe par défaut : password)');
    }
}
