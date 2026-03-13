<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas;

use App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Pages\CreateAuditoriaVisita;
use App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Pages\EditAuditoriaVisita;
use App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Pages\ListAuditoriaVisitas;
use App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Schemas\AuditoriaVisitaForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaVisitas\Tables\AuditoriaVisitasTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\AuditoriaVisita;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaVisitaResource extends Resource
{
    protected static ?string $model = AuditoriaVisita::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'AuditoriaVisita';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaVisitaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaVisitasTable::configure($table);
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
            'index' => ListAuditoriaVisitas::route('/'),
            // 'create' => CreateAuditoriaVisita::route('/create'),
            // 'edit' => EditAuditoriaVisita::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('audit::visitas_visita');
    }
}
