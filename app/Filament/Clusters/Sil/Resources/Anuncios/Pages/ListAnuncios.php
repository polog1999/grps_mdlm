<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Pages;

use App\Filament\Clusters\Sil\Resources\Anuncios\AnunciosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\CreateColorAction;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\CreateMaterialAction;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\CreateCaracteristicasFisicasAction;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class ListAnuncios extends ListRecords
{
    protected static string $resource = AnunciosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('create_color')
                ->label('Color')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    TextInput::make('descripcion')
                        ->label('Descripción del Color')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),
                ])
                ->action(function ($data) {
                    $color = (new CreateColorAction())->execute($data['descripcion']);

                    Notification::make()
                        ->title('Color creado exitosamente')
                        ->success()
                        ->send();
                }),
            Action::make('create_material')
                ->label('Material')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    TextInput::make('descripcion')
                        ->label('Descripción del Material')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),
                ])
                ->action(function ($data) {
                    $material = (new CreateMaterialAction())->execute($data['descripcion']);

                    Notification::make()
                        ->title('Material creado exitosamente')
                        ->success()
                        ->send();
                }),
            Action::make('create_caracteristica_fisica')
                ->label('Caract. Fisica')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    TextInput::make('descripcion')
                        ->label('Descripción de la Caracteristica Fisica')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),
                ])
                ->action(function ($data) {
                    $caracteristicaFisica = (new CreateCaracteristicasFisicasAction())->execute($data['descripcion']);

                    Notification::make()
                        ->title('Caracteristica Fisica creada exitosamente')
                        ->success()
                        ->send();
                }),
        ];


    }
}
