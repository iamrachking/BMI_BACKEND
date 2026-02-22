<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminOrGestionnaire
{
    /**
     * Accès réservé à l'administration e-commerce (admin et gestionnaire).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->hasRole('admin') && ! $request->user()->hasRole('gestionnaire')) {
            abort(403, 'Accès réservé aux administrateurs et gestionnaires.');
        }

        return $next($request);
    }
}
