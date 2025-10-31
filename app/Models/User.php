<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Filament\Panel;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name','email','password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
     protected $hidden = [
        'password','two_factor_secret','two_factor_recovery_codes','remember_token',
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
        // Ejemplo seguro: permite siempre en local, y en otros entornos exige email verificado.
        if (app()->isLocal()) {
            return true;
        }

        // Si quieres además restringir por dominio, descomenta y ajusta:
        // if (! str_ends_with($this->email, '@tu-dominio.com')) {
        //     return false;
        // }

        // Si no usas MustVerifyEmail, basta revisar el timestamp:
        return ! is_null($this->email_verified_at);
        // Si implementas MustVerifyEmail, puedes usar:
        // return $this->hasVerifiedEmail();
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }




}
