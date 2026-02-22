<?php

namespace App\Http\Controllers\Gestion;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends BaseController
{
    public function index(Request $request): View
    {
        $query = User::query()->with('role');

        if ($request->filled('role')) {
            $query->whereHas('role', fn ($q) => $q->where('name', $request->role));
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::whereIn('name', ['admin', 'gestionnaire', 'technicien'])->get();

        return view('gestion.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::whereIn('name', ['gestionnaire', 'technicien'])->get();

        return view('gestion.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role_id'  => 'required|exists:roles,id',
            'phone'    => 'nullable|string|max:50',
            'send_invite' => 'nullable|boolean',
        ]);

        $role = Role::findOrFail($validated['role_id']);
        if (! in_array($role->name, ['gestionnaire', 'technicien'], true)) {
            return back()->withInput()->with('error', 'Rôle non autorisé pour cette création.');
        }

        $password = Str::random(12);
        $validated['password'] = Hash::make($password);
        unset($validated['send_invite']);

        $user = User::create($validated);

        $inviteSent = false;
        if ($request->boolean('send_invite')) {
            try {
                $status = \Illuminate\Support\Facades\Password::sendResetLink(['email' => $user->email]);
                $inviteSent = ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Email invitation échoué', ['email' => $user->email, 'error' => $e->getMessage()]);
            }
        }

        $message = 'Utilisateur créé.';
        if ($request->boolean('send_invite')) {
            $message .= $inviteSent ? ' Un email d\'invitation a été envoyé pour définir le mot de passe.' : ' L\'envoi de l\'email a échoué (voir les logs).';
        } else {
            $message .= ' Mot de passe temporaire : ' . $password . ' (à communiquer de manière sécurisée).';
        }

        return redirect()->route('users.index')->with('success', $message);
    }

    public function edit(User $user): View
    {
        $roles = Role::whereIn('name', ['admin', 'gestionnaire', 'technicien'])->get();

        return view('gestion.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'phone'   => 'nullable|string|max:50',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour.');
    }
}
