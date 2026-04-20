<?php

namespace App\Filament\Clusters\Visitas\Resources\Oficinas;

use App\Filament\Clusters\Visitas\Resources\Oficinas\Pages\CreateOficina;
use App\Filament\Clusters\Visitas\Resources\Oficinas\Pages\EditOficina;
use App\Filament\Clusters\Visitas\Resources\Oficinas\Pages\ListOficinas;
use App\Filament\Clusters\Visitas\Resources\Oficinas\Schemas\OficinaForm;
use App\Filament\Clusters\Visitas\Resources\Oficinas\Tables\OficinasTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Oficina;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OficinaResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Oficina::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Oficina';

    public static function form(Schema $schema): Schema
    {
        return OficinaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OficinasTable::configure($table);
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
            'index' => ListOficinas::route('/'),
            // 'create' => CreateOficina::route('/create'),
            // 'edit' => EditOficina::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('view::visitas_oficina');
    }
}
