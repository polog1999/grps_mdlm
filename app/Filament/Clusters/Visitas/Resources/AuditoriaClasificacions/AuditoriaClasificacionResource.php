<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions;

use App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\Pages\CreateAuditoriaClasificacion;
use App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\Pages\EditAuditoriaClasificacion;
use App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\Pages\ListAuditoriaClasificacions;
use App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\Schemas\AuditoriaClasificacionForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaClasificacions\Tables\AuditoriaClasificacionsTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\AuditoriaClasificacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaClasificacionResource extends Resource
{
    protected static ?string $model = AuditoriaClasificacion::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';
    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'AuditoriaClasificacion';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaClasificacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaClasificacionsTable::configure($table);
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
            'index' => ListAuditoriaClasificacions::route('/'),
            // 'create' => CreateAuditoriaClasificacion::route('/create'),
            // 'edit' => EditAuditoriaClasificacion::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('audit::visitas_clasificacion');
    }
}
