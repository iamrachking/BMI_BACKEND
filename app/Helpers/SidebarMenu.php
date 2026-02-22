<?php

namespace App\Helpers;

use App\Models\Auth\User;

class SidebarMenu
{
    public static function items(User $user): array
    {
        $items = [];

        $items[] = [
            'label' => 'Tableau de bord',
            'url'   => route('dashboard'),
            'icon'  => 'fa-dashboard',
            'route' => 'dashboard',
        ];

        if ($user->hasRole('admin') || $user->hasRole('gestionnaire') || $user->hasRole('technicien')) {
            $items[] = [
                'label' => 'Équipements',
                'url'   => route('equipments.index'),
                'icon'  => 'fa-cogs',
                'route' => 'equipments.*',
            ];
            $items[] = [
                'label' => 'Maintenances',
                'url'   => route('maintenances.index'),
                'icon'  => 'fa-wrench',
                'route' => 'maintenances.*',
            ];
            $items[] = [
                'label' => 'Historique des pannes',
                'url'   => route('failures.index'),
                'icon'  => 'fa-triangle-exclamation',
                'route' => 'failures.*',
            ];
        }

        if ($user->hasRole('admin')) {
            $items[] = [
                'label' => 'Utilisateurs',
                'url'   => route('users.index'),
                'icon'  => 'fa-users',
                'route' => 'users.*',
            ];
        }

        $items[] = [
            'label' => 'Aide',
            'url'   => route('help'),
            'icon'  => 'fa-circle-question',
            'route' => 'help',
        ];
        $items[] = [
            'label' => 'Profil',
            'url'   => route('profile.edit'),
            'icon'  => 'fa-user',
            'route' => 'profile.edit',
        ];
        $items[] = [
            'label'     => 'Déconnexion',
            'icon'      => 'fa-sign-out-alt',
            'is_logout' => true,
        ];

        return $items;
    }
}
