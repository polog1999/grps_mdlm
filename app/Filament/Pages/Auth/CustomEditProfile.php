<?php
namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Schemas\Schema;

class CustomEditProfile extends EditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // CAMPO NOMBRE
                $this->getNameFormComponent()
                    ->disabled(fn () => !auth()->user()->hasRole('Administrador OTIE')),
                
                // CAMPO EMAIL
                $this->getEmailFormComponent()
                    ->disabled(fn () => !auth()->user()->hasRole('Administrador OTIE')),
                
                // CAMPO CONTRASEÑA (Este siempre habilitado por defecto en la base)
                $this->getPasswordFormComponent(),
                
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }

}