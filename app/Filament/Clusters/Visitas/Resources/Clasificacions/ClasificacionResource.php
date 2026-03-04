<?php

namespace App\Filament\Clusters\Visitas\Resources\Clasificacions;

use App\Filament\Clusters\Visitas\Resources\Clasificacions\Pages\CreateClasificacion;
use App\Filament\Clusters\Visitas\Resources\Clasificacions\Pages\EditClasificacion;
use App\Filament\Clusters\Visitas\Resources\Clasificacions\Pages\ListClasificacions;
use App\Filament\Clusters\Visitas\Resources\Clasificacions\Schemas\ClasificacionForm;
use App\Filament\Clusters\Visitas\Resources\Clasificacions\Tables\ClasificacionsTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Clasificacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClasificacionResource extends Resource
{
    protected static ?string $model = Clasificacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;



    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Clasificacion';

    protected static ?string $navigationLabel = 'Clasificaciones'; // <-- Cambia el nombre en el menú
    protected static ?string $pluralModelLabel = 'Clasificaciones'; // Corrige el título principal y las migas de pan

    public static function form(Schema $schema): Schema
    {
        return ClasificacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClasificacionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClasificacions::route('/'),
            'create' => CreateClasificacion::route('/create'),
            'edit' => EditClasificacion::route('/{record}/edit'),
        ];
    }
}
