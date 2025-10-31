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

class RequestPasswordReset extends BaseRequestPasswordReset
{
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
             * @param \App\Models\User $user
             */
            function (CanResetPassword $user, string $token): void {
                // runtime guard (already present)
                if (! method_exists($user, 'notify')) {
                    $userClass = get_class($user);
                    throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                }

                // help static analysers (Intelephense) know the concrete User type:
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