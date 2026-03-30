<?php

namespace App\Filament\Clusters\Visitas\Resources\Regimens;

use App\Filament\Clusters\Visitas\Resources\Regimens\Pages\CreateRegimen;
use App\Filament\Clusters\Visitas\Resources\Regimens\Pages\EditRegimen;
use App\Filament\Clusters\Visitas\Resources\Regimens\Pages\ListRegimens;
use App\Filament\Clusters\Visitas\Resources\Regimens\Schemas\RegimenForm;
use App\Filament\Clusters\Visitas\Resources\Regimens\Tables\RegimensTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Regimen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegimenResource extends Resource
{
    protected static ?string $model = Regimen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = VisitasCluster::class;

    
    protected static ?string $recordTitleAttribute = 'Regímen';
    protected static ?string $navigationLabel = 'Regímenes'; // <-- Cambia el nombre en el menú
    protected static ?string $pluralModelLabel = 'Regímenes'; // Corrige el título principal y las migas de pan
    protected static ?string $slug = 'regimenes';

    public static function form(Schema $schema): Schema
    {
        return RegimenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegimensTable::configure($table);
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
            'index' => ListRegimens::route('/'),
            // 'create' => CreateRegimen::route('/create'),
            // 'edit' => EditRegimen::route('/{record}/edit'),
        ];
    }
     public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('view::visitas_regimen');
    }
}
