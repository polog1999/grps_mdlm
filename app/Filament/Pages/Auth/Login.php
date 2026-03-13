<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema; // Asegúrate de que este import sea el correcto para v4
use MarcoGermani87\FilamentCaptcha\Forms\Components\CaptchaField;

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
}