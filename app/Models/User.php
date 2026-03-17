<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles; // <-- 1. Importar el Trait de Spatie
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }


    public function canAccessPanel(Panel $panel): bool
    {
        // 1. **Verificación de Entorno (como ya tenías)**
        if (app()->isLocal()) {
            return true;
        }

        // 2. **Integración con Spatie (Verifica si el usuario tiene algún rol asignado)**
        // Esto previene que usuarios sin roles puedan iniciar sesión en el panel.
        if ($this->roles->isEmpty()) {
            return false;
        }

        // 3. **Verificación de Email (como ya tenías)**
        return !is_null($this->email_verified_at);
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
        return $this->belongsTo(Sede::class, 'sede_id','id_sede');
    }
}
