<?php

namespace App\Filament\Clusters\Visitas\Resources\Sedes;

use App\Filament\Clusters\Visitas\Resources\Sedes\Pages\CreateSede;
use App\Filament\Clusters\Visitas\Resources\Sedes\Pages\EditSede;
use App\Filament\Clusters\Visitas\Resources\Sedes\Pages\ListSedes;
use App\Filament\Clusters\Visitas\Resources\Sedes\Schemas\SedeForm;
use App\Filament\Clusters\Visitas\Resources\Sedes\Tables\SedesTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Sede;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SedeResource extends Resource
{
    protected static ?string $model = Sede::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Sede';

    public static function form(Schema $schema): Schema
    {
        return SedeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SedesTable::configure($table);
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
            'index' => ListSedes::route('/'),
            // 'create' => CreateSede::route('/create'),
            // 'edit' => EditSede::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('view::visitas_sede');
    }
}
