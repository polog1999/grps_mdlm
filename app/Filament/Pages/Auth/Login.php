<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema; // Asegúrate de que este import sea el correcto para v4
use MarcoGermani87\FilamentCaptcha\Forms\Components\CaptchaField;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        // No necesitas makeForm() ni getForms() porque BaseLogin ya lo hace por ti.
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),

                // El campo de Captcha
                CaptchaField::make('captcha')
                    ->required()
                    ->validationMessages([
                        'required' => 'Por favor, introduce el código de seguridad.',
                    ]),
            ]);
    }
    
    /**
     * Personalizamos el campo Email para que actúe como "Usuario"
     */
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Correo electrónico')
            ->placeholder('ejemplo: ediaz')
            // IMPORTANTE: Quitamos ->email() si el usuario solo pondrá "ediaz" 
            // para que la validación de formato no lo bloquee antes de enviar.
            ->required()
            ->autocomplete()
            ->autofocus()
            ->suffix('@munimolina.gob.pe');
    }

    /**
     * Modificamos las credenciales antes de que Filament intente el Login
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        $loginValue = $data['email'];

        // Si el usuario NO escribió el @ por su cuenta, se lo concatenamos
        if (! str_contains($loginValue, '@')) {
            $loginValue .= '@munimolina.gob.pe';
        }

        return [
            'email' => $loginValue,
            'password' => $data['password'],
        ];
    }
}