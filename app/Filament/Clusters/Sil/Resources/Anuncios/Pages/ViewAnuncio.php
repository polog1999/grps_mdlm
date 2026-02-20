<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Pages;

use App\Filament\Clusters\Sil\Resources\Anuncios\AnunciosResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\CreateColorAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;


class ViewAnuncio extends ViewRecord
{
    protected static string $resource = AnunciosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('create_color')
                ->label('Crear Color')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    TextInput::make('descripcion')
                        ->label('Descripción del Color')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true), // Evita duplicados
                ])
                ->action(function ($data) {
                    $color = (new CreateColorAction())->execute($data['descripcion']);

                    Notification::make()
                        ->title('Color creado exitosamente')
                        ->success()
                        ->send();

                    // Opcional: Recargar la página o actualizar el select
                    return redirect()->refresh();
                }),
        ];
    }
}
