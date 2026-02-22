<?php

namespace App\Models\Auth;

use App\Mail\PasswordResetInvitation;
use App\Models\Auth\Role;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Order;
use App\Models\Gestion\Failure;
use App\Models\Gestion\Maintenance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the cart for the user.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the maintenances assigned to the user (technicien).
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Profile photo URL (storage).
     */
    public function profilePhotoUrl(): ?string
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Email d'invitation en français (logo + bouton #2e4053). Données simples pour éviter les erreurs.
     */
    public function sendPasswordResetNotification($token): void
    {
        $email = $this->getEmailForPasswordReset();
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);
        $firstName = $this->name ? trim(explode(' ', $this->name)[0] ?? '') : '';

        $logoDataUri = null;
        $logoPath = public_path('images/logo.png');
        if (is_file($logoPath) && is_readable($logoPath)) {
            $content = @file_get_contents($logoPath);
            if ($content !== false && strlen($content) > 0 && strlen($content) < 500000) {
                $mime = pathinfo($logoPath, PATHINFO_EXTENSION) === 'png' ? 'image/png' : (mime_content_type($logoPath) ?: 'image/png');
                $logoDataUri = 'data:' . $mime . ';base64,' . base64_encode($content);
            }
        }

        Mail::to($email)->send(new PasswordResetInvitation($resetUrl, $firstName, $logoDataUri));
    }
}
