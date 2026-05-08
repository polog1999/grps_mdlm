<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles; // <-- 1. Importar el Trait de Spatie

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles; // <-- 2. Usar el Trait HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sede_id',
        'trabajador_id',
        'sede',
        'nombres_completos',
        'is_active',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'is_active' => 'integer',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // Opcional pero recomendado: Restringir el acceso al panel si no tiene un Rol
    public function canAccessPanel(Panel $panel): bool
    {
        // 1. **Verificación de Entorno (como ya tenías)**
        if (app()->isLocal()) {
            return true;
        }
        // Usar !$this->is_active funciona para false, 0, null o 'f' (si hay cast)
        if (!$this->is_active) {
            return false;
        }

        // 3. **Integración con Spatie (Verifica si el usuario tiene algún rol asignado)**
        // Esto previene que usuarios sin roles puedan iniciar sesión en el panel.
        if ($this->roles()->count() === 0) {
            return false;
        }
        return true;
        // 4. **Verificación de Email (como ya tenías)**
        // return !is_null($this->email_verified_at);
    }

    /**
     * Get the model_has_role record associated with the user.
     */
    public function modelHasRole(): HasOne
    {
        return $this->hasOne(ModelHasRole::class, 'model_id', 'id');
    }
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id', 'id_sede');
    }
    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id', 'id_usuario');
    }
}
