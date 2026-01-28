<?php

namespace App\Filament\Clusters\Sil\Resources\SolicitudPermisos;

use App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Pages\CreateSolicitudPermiso;
use App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Pages\EditSolicitudPermiso;
use App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Pages\ListSolicitudPermisos;
use App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Schemas\SolicitudPermisoForm;
use App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Tables\SolicitudPermisosTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\SolicitudPermiso;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SolicitudPermisoResource extends Resource
{
    protected static ?string $model = SolicitudPermiso::class;

    protected static string|BackedEnum|null $navigationIcon = 'ionicon-ticket-outline';

    protected static ?string $cluster = SilCluster::class;

    protected static ?string $recordTitleAttribute = 'Solicitud de Permisos';
    protected static ?string $navigationLabel = 'Solicitud de Permisos';
    protected static ?string $pluralModelLabel = 'Solicitud de Permisos';

    public static function form(Schema $schema): Schema
    {
        return SolicitudPermisoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SolicitudPermisosTable::configure($table);
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
            'index' => ListSolicitudPermisos::route('/'),
            //'create' => CreateSolicitudPermiso::route('/create'),
            'edit' => EditSolicitudPermiso::route('/{record}/edit'),
        ];
    }
}
