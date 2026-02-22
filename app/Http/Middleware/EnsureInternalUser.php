<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInternalUser
{
    /**
     * Les rôles autorisés sur la plateforme web (module gestion).
     * Les clients (customer) utilisent uniquement l'application mobile.
     */
    protected array $internalRoles = ['admin', 'gestionnaire', 'technicien'];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role->name ?? '', $this->internalRoles, true)) {
            abort(403, 'Accès réservé à la plateforme interne. Les clients doivent utiliser l\'application mobile.');
        }

        return $next($request);
    }
}
