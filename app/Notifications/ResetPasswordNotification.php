<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Facades\Filament;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token)
    {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage 
    { 
        $resetUrl = Filament::getResetPasswordUrl($this->token, $notifiable);
        $minutes = (int) config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);

        return (new MailMessage)
            ->subject('🔐 Restablecer Contraseña')
            ->greeting("Hola {$notifiable->name},")
            ->line('Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.')
            ->line('Si fuiste tú quien lo solicitó, haz clic en el botón de abajo:')
            ->action('Restablecer Contraseña', $resetUrl)
            ->line("**Este enlace expirará en {$minutes} minutos.**")
            ->line('Por seguridad, te recomendamos usar una contraseña segura con letras, números y símbolos.')
            ->line('---')
            ->line('**Si no solicitaste restablecer tu contraseña**, ignora este correo.')
            ->salutation('Atentamente, ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
