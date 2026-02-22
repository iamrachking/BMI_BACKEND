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

        // Administration e-commerce (admin + gestionnaire)
        if ($user->hasRole('admin') || $user->hasRole('gestionnaire')) {
            $items[] = [
                'label' => 'Boutique',
                'url'   => route('admin.dashboard'),
                'icon'  => 'fa-store',
                'route' => 'admin.dashboard',
            ];
            $items[] = [
                'label' => 'Catégories',
                'url'   => route('admin.categories.index'),
                'icon'  => 'fa-folder',
                'route' => 'admin.categories.*',
            ];
            $items[] = [
                'label' => 'Produits',
                'url'   => route('admin.products.index'),
                'icon'  => 'fa-box',
                'route' => 'admin.products.*',
            ];
            $items[] = [
                'label' => 'Commandes',
                'url'   => route('admin.orders.index'),
                'icon'  => 'fa-shopping-cart',
                'route' => 'admin.orders.*',
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
