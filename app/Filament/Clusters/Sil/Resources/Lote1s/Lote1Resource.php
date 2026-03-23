<?php

namespace App\Filament\Clusters\Sil\Resources\Lote1s;

use App\Filament\Clusters\Sil\Resources\Lote1s\Pages\CreateLote1;
use App\Filament\Clusters\Sil\Resources\Lote1s\Pages\EditLote1;
use App\Filament\Clusters\Sil\Resources\Lote1s\Pages\ListLote1s;
use App\Filament\Clusters\Sil\Resources\Lote1s\Schemas\Lote1Form;
use App\Filament\Clusters\Sil\Resources\Lote1s\Tables\Lote1sTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\Lote1;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class Lote1Resource extends Resource
{
    protected static ?string $model = Lote1::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static string|UnitEnum|null $navigationGroup = 'Compatibilidad';

    protected static ?string $navigationLabel = 'Zonificación de Sectores';

    protected static ?string $modelLabel = 'Zonificación de Sector';

    protected static ?string $pluralModelLabel = 'Zonificación de Sectores';

    protected static ?string $cluster = SilCluster::class;
    protected static ?int $navigationSort = 6;

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('view::lote1');
    }

    protected static ?string $recordTitleAttribute = 'cod_lote_cat';

    public static function form(Schema $schema): Schema
    {
        return Lote1Form::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Lote1sTable::configure($table);
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
            'index' => ListLote1s::route('/'),
        ];
    }
}
