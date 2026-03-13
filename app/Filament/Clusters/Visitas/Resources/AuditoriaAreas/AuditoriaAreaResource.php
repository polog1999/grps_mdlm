<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaAreas;

use App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\Pages\CreateAuditoriaArea;
use App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\Pages\EditAuditoriaArea;
use App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\Pages\ListAuditoriaAreas;
use App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\Schemas\AuditoriaAreaForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaAreas\Tables\AuditoriaAreasTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\AuditoriaArea;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaAreaResource extends Resource
{
    protected static ?string $model = AuditoriaArea::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'AuditoriaArea';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaAreaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaAreasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('audit::visitas_area');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditoriaAreas::route('/'),
            // 'create' => CreateAuditoriaArea::route('/create'),
            // 'edit' => EditAuditoriaArea::route('/{record}/edit'),
        ];
    }
}
