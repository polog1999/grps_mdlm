<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;

class SectionHeaderActions
{
    public static function make(string $section): array
    {
        return [
            self::guardarAction($section),
            self::editarAction($section),
        ];
    }

    private static function guardarAction(string $section): Action
    {
        return Action::make("guardar_{$section}")
            ->label('Guardar')
            ->icon('heroicon-o-check')
            ->color('success')
            ->visible(fn ($get) => $get("_section_{$section}_saved") !== true)
            ->action(function ($set) use ($section) {
                $set("_section_{$section}_saved", true);
                self::notify('success', ucfirst($section) . ' Guardado', 'Los datos han sido guardados.');
            });
    }

    private static function editarAction(string $section): Action
    {
        return Action::make("editar_{$section}")
            ->label('Editar')
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->visible(fn ($get) => $get("_section_{$section}_saved") === true)
            ->action(function ($set) use ($section) {
                $set("_section_{$section}_saved", false);
                self::notify('info', 'Modo edición', 'Ahora puede editar los datos.');
            });
    }

    private static function notify(string $type, string $title, string $body): void
    {
        Notification::make()->$type()->title($title)->body($body)->send();
    }
}
