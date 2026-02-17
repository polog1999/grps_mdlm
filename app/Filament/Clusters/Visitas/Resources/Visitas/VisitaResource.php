<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas;

use App\Filament\Clusters\Visitas\Resources\Visitas\Pages\CreateVisita;
use App\Filament\Clusters\Visitas\Resources\Visitas\Pages\EditVisita;
use App\Filament\Clusters\Visitas\Resources\Visitas\Pages\ListVisitas;
use App\Filament\Clusters\Visitas\Resources\Visitas\Schemas\VisitaForm;
use App\Filament\Clusters\Visitas\Resources\Visitas\Tables\VisitasTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Visita;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VisitaResource extends Resource
{
    protected static ?string $model = Visita::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Visita';

    public static function form(Schema $schema): Schema
    {
        return VisitaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VisitasTable::configure($table);
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
            'index' => ListVisitas::route('/'),
            'create' => CreateVisita::route('/create'),
            'edit' => EditVisita::route('/{record}/edit'),
        ];
    }
}
