<?php

namespace App\Filament\Clusters\Visitas\Resources\Cargos;

use App\Filament\Clusters\Visitas\Resources\Cargos\Pages\CreateCargo;
use App\Filament\Clusters\Visitas\Resources\Cargos\Pages\EditCargo;
use App\Filament\Clusters\Visitas\Resources\Cargos\Pages\ListCargos;
use App\Filament\Clusters\Visitas\Resources\Cargos\Schemas\CargoForm;
use App\Filament\Clusters\Visitas\Resources\Cargos\Tables\CargosTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Cargo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CargoResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Cargo::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Cargo';

    public static function form(Schema $schema): Schema
    {
        return CargoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CargosTable::configure($table);
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
            'index' => ListCargos::route('/'),
            // 'create' => CreateCargo::route('/create'),
            // 'edit' => EditCargo::route('/{record}/edit'),
        ];
    }
     public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('view::visitas_cargo');
    }
}
