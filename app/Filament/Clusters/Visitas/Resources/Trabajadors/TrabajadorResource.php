<?php

namespace App\Filament\Clusters\Visitas\Resources\Trabajadors;

use App\Filament\Clusters\Visitas\Resources\Trabajadors\Pages\CreateTrabajador;
use App\Filament\Clusters\Visitas\Resources\Trabajadors\Pages\EditTrabajador;
use App\Filament\Clusters\Visitas\Resources\Trabajadors\Pages\ListTrabajadors;
use App\Filament\Clusters\Visitas\Resources\Trabajadors\Schemas\TrabajadorForm;
use App\Filament\Clusters\Visitas\Resources\Trabajadors\Tables\TrabajadorsTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Trabajador;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrabajadorResource extends Resource
{
    protected static ?string $model = Trabajador::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Trabajador';

    public static function form(Schema $schema): Schema
    {
        return TrabajadorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrabajadorsTable::configure($table);
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
            'index' => ListTrabajadors::route('/'),
            'create' => CreateTrabajador::route('/create'),
            'edit' => EditTrabajador::route('/{record}/edit'),
        ];
    }
}
