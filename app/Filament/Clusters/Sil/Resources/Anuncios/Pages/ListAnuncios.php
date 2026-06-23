<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Pages;

use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\VerDatoItseAction;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\VerDatoLicenciaAction;
use App\Filament\Clusters\Sil\Resources\Anuncios\AnunciosResource;
use App\Filament\Clusters\Sil\Resources\Anuncios\Widgets\AnunciosStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\CreateColorAction;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\CreateMaterialAction;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\CreateCaracteristicasFisicasAction;
use App\Filament\Clusters\Sil\Resources\Anuncios\Actions\ExportAnunciosAction;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Models\Anuncios;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Builder;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListAnuncios extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = AnunciosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn() => auth()->user()->hasPermissionTo('create::anuncios')),
            ExportAnunciosAction::make()->visible(fn() => auth()->user()->hasPermissionTo('export::anuncios')),
            VerDatoLicenciaAction::make(),
            VerDatoItseAction::make(),
            Action::make('create_color')
                ->label('Color')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->visible(fn() => auth()->user()->hasPermissionTo('create_color::anuncios'))
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
            Action::make('create_caracteristica_fisica')
                ->label('Caract. Fisica')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->visible(fn() => auth()->user()->hasPermissionTo('create_physical_feature::anuncios'))
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

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos')
                ->badge(Anuncios::count()),

            // --- Filtros por Dictamen ---
            'procedentes' => Tab::make('Procedentes')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('dictamen', 'PROCEDENTE'))
                ->badge(Anuncios::where('dictamen', 'PROCEDENTE')->count())
                ->badgeColor('success'),

            'improcedentes' => Tab::make('Improcedentes')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('dictamen', 'IMPROCEDENTE'))
                ->badge(Anuncios::where('dictamen', 'IMPROCEDENTE')->count())
                ->badgeColor('danger'),

            // --- Filtros por Vigencia ---
            'temporales' => Tab::make('Temporales')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('vigencia', 'TEMPORAL'))
                // Si prefieres usar tu Enum sería: ->where('vigencia', VigenciaAnuncio::TEMPORAL->value)
                ->badge(Anuncios::where('vigencia', 'TEMPORAL')->count())
                ->badgeColor('warning'), // Un color amarillo/naranja para distinguirlos

            'indeterminadas' => Tab::make('Indeterminadas')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('vigencia', 'INDETERMINADA'))
                ->badge(Anuncios::where('vigencia', 'INDETERMINADA')->count())
                ->badgeColor('info'), // Un color azul
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AnunciosStatsOverview::class,
        ];
    }
}
