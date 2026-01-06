<?php

namespace App\Filament\Clusters\Sil\Resources\TipoLicencias;

use App\Filament\Clusters\Sil\Resources\TipoLicencias\Pages\CreateTipoLicencia;
use App\Filament\Clusters\Sil\Resources\TipoLicencias\Pages\EditTipoLicencia;
use App\Filament\Clusters\Sil\Resources\TipoLicencias\Pages\ListTipoLicencias;
use App\Filament\Clusters\Sil\Resources\TipoLicencias\Schemas\TipoLicenciaForm;
use App\Filament\Clusters\Sil\Resources\TipoLicencias\Tables\TipoLicenciasTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\TipoLicencia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoLicenciaResource extends Resource
{
    protected static ?string $model = TipoLicencia::class;
    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = SilCluster::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view::tipo_licencias') ?? false;
    }
    protected static ?string $recordTitleAttribute = 'TipoLicencia';

    public static function form(Schema $schema): Schema
    {
        return TipoLicenciaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoLicenciasTable::configure($table);
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
            'index' => ListTipoLicencias::route('/'),
            'create' => CreateTipoLicencia::route('/create'),
            'edit' => EditTipoLicencia::route('/{record}/edit'),
        ];
    }
}
