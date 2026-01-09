<?php

namespace App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas;

use App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Pages\CreateDataLevantamientoConsolida;
use App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Pages\EditDataLevantamientoConsolida;
use App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Pages\ListDataLevantamientoConsolidas;
use App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Schemas\DataLevantamientoConsolidaForm;
use App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Tables\DataLevantamientoConsolidasTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\DataLevantamientoConsolida;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DataLevantamientoConsolidaResource extends Resource
{
    protected static ?string $model = DataLevantamientoConsolida::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-map-pin-area-bold';
    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = SilCluster::class;

    protected static ?string $recordTitleAttribute = 'DataLevantamientoConsolida';

    protected static ?string $navigationLabel = 'Data de Levantamiento';
    protected static ?string $pluralModelLabel = 'Data de Levantamiento';

    public static function form(Schema $schema): Schema
    {
        return DataLevantamientoConsolidaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataLevantamientoConsolidasTable::configure($table);
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
            'index' => ListDataLevantamientoConsolidas::route('/'),
            'create' => CreateDataLevantamientoConsolida::route('/create'),
            //'edit' => EditDataLevantamientoConsolida::route('/{record}/edit'),
        ];
    }
}
