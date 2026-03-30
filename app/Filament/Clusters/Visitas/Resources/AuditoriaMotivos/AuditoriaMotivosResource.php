<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos;

use App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\Pages\CreateAuditoriaMotivos;
use App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\Pages\EditAuditoriaMotivos;
use App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\Pages\ListAuditoriaMotivos;
use App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\Schemas\AuditoriaMotivosForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaMotivos\Tables\AuditoriaMotivosTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Motivo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaMotivosResource extends Resource
{
    protected static ?string $model = Motivo::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Motivo';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaMotivosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaMotivosTable::configure($table);
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
            'index' => ListAuditoriaMotivos::route('/'),
            // 'create' => CreateAuditoriaMotivos::route('/create'),
            // 'edit' => EditAuditoriaMotivos::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('audit::visitas_motivo');
    }
}
