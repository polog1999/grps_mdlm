<?php

namespace App\Filament\Clusters\Sil\Resources\TipoResolucions;

use App\Filament\Clusters\Sil\Resources\TipoResolucions\Pages\CreateTipoResolucion;
use App\Filament\Clusters\Sil\Resources\TipoResolucions\Pages\EditTipoResolucion;
use App\Filament\Clusters\Sil\Resources\TipoResolucions\Pages\ListTipoResolucions;
use App\Filament\Clusters\Sil\Resources\TipoResolucions\Schemas\TipoResolucionForm;
use App\Filament\Clusters\Sil\Resources\TipoResolucions\Tables\TipoResolucionsTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\TipoResolucion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoResolucionResource extends Resource
{
    protected static ?string $model = TipoResolucion::class;

    protected static ?int $navigationSort = 8;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = SilCluster::class;

    protected static ?string $recordTitleAttribute = 'TipoResolucion';

    public static function form(Schema $schema): Schema
    {
        return TipoResolucionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoResolucionsTable::configure($table);
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
            'index' => ListTipoResolucions::route('/'),
            'create' => CreateTipoResolucion::route('/create'),
            'edit' => EditTipoResolucion::route('/{record}/edit'),
        ];
    }
}
