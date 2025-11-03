<?php

namespace App\Filament\Pages\Auth;

use Exception;
use App\Notifications\ResetPasswordNotification;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\CanResetPassword;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Support\Facades\Lang;

/**
 * Página personalizada de Filament para solicitar restablecimiento de contraseña.
 *
 * Esta clase extiende la página base de Filament para el restablecimiento de contraseña,
 * personalizando el proceso para usar una notificación personalizada (ResetPasswordNotification)
 * en lugar de la notificación por defecto de Laravel. Esto permite enviar correos electrónicos
 * con contenido personalizado, como texto en español y enlaces específicos.
 *
 * Funcionalidades principales:
 * - Limita la tasa de solicitudes (2 por minuto) para prevenir abuso.
 * - Envía el enlace de restablecimiento usando la notificación personalizada.
 * - Maneja errores y muestra notificaciones apropiadas al usuario.
 * - Limpia el formulario después de una solicitud exitosa.
 *
 * Uso:
 * - Registrada en AdminPanelProvider como página de passwordReset.
 * - Se accede desde /admin/forgot-password cuando un usuario olvida su contraseña.
 */
class RequestPasswordReset extends BaseRequestPasswordReset
{
    /**
     * Procesa la solicitud de restablecimiento de contraseña.
     *
     * Valida la tasa de solicitudes, envía el enlace de restablecimiento usando
     * el broker de contraseñas de Filament y la notificación personalizada,
     * y muestra notificaciones de éxito o error según el resultado.
     *
     * Proceso:
     * 1. Aplica límite de tasa (2 solicitudes por minuto).
     * 2. Obtiene datos del formulario.
     * 3. Envía enlace usando Password::sendResetLink con callback personalizado.
     * 4. El callback crea y envía ResetPasswordNotification con el token.
     * 5. Muestra notificación de éxito/error y limpia el formulario.
     *
     * @throws Exception Si el modelo User no tiene método notify().
     * @return void
     */
    public function request(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $translationKey = 'filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled';

            // Obtener la traducción y comprobar si es un array (puede ser string)
            $throttled = Lang::get($translationKey);
            $body = null;

            if (is_array($throttled) && array_key_exists('body', $throttled)) {
                $body = __($translationKey . '.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]);
            }

            Notification::make()
                ->title(__($translationKey . '.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body($body)
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();

        $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            $data,
            /**
             * Callback personalizado para enviar la notificación de restablecimiento.
             *
             * En lugar de usar la notificación por defecto de Laravel, crea una instancia
             * de ResetPasswordNotification personalizada con el token generado.
             * Esto permite personalizar el contenido del email, idioma y URL de restablecimiento.
             *
             * @param CanResetPassword $user El usuario que solicita el restablecimiento.
             * @param string $token El token único para el restablecimiento.
             * @throws Exception Si el modelo no tiene método notify().
             */
            function (CanResetPassword $user, string $token): void {
                // Verificación en tiempo de ejecución (ya presente)
                if (! method_exists($user, 'notify')) {
                    $userClass = get_class($user);
                    throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                }

                // Ayuda a los analizadores estáticos (Intelephense) a conocer el tipo User concreto:
                /** @var \App\Models\User $user */
                $notification = new ResetPasswordNotification($token);
                $user->notify($notification);
            },
        );

        if ($status !== Password::RESET_LINK_SENT) {
            Notification::make()
                ->title(__($status))
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title(__($status))
            ->success()
            ->send();

        $this->form->fill();
    }
}