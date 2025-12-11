<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Facades\Filament;

/**
 * Notificación personalizada para el restablecimiento de contraseña en Filament.
 *
 * Esta clase envía un correo electrónico con un enlace seguro para restablecer
 * la contraseña del usuario. Utiliza el sistema de notificaciones de Laravel
 * y se integra con Filament para generar la URL de reset.
 *
 * El mensaje incluye instrucciones claras, un botón de acción y recomendaciones
 * de seguridad, todo en español para una mejor experiencia del usuario.
 */
class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Constructor de la notificación.
     *
     * @param string $token Token único generado para el restablecimiento de contraseña.
     */
    public function __construct(private readonly string $token)
    {
    }

    /**
     * Define los canales por los que se enviará la notificación.
     *
     * En este caso, solo se envía por correo electrónico.
     *
     * @param object $notifiable El objeto notifiable (usuario).
     * @return array Lista de canales de notificación.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Construye el mensaje de correo electrónico para el restablecimiento de contraseña.
     *
     * Genera la URL de reset usando Filament, calcula el tiempo de expiración
     * desde la configuración y arma un mensaje amigable con líneas de texto,
     * un botón de acción y una despedida personalizada.
     *
     * @param object $notifiable El objeto notifiable (usuario).
     * @return MailMessage Instancia de MailMessage configurada.
     */
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

    /**
     * Representación en array de la notificación (para otros canales).
     *
     * Actualmente vacío, ya que esta notificación solo se envía por mail.
     *
     * @param object $notifiable El objeto notifiable (usuario).
     * @return array Array vacío.
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
