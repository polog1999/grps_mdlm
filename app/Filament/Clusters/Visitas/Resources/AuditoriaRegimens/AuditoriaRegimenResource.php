<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens;

use App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\Pages\CreateAuditoriaRegimen;
use App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\Pages\EditAuditoriaRegimen;
use App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\Pages\ListAuditoriaRegimens;
use App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\Schemas\AuditoriaRegimenForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaRegimens\Tables\AuditoriaRegimensTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\AuditoriaRegimen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaRegimenResource extends Resource
{
    protected static ?string $model = AuditoriaRegimen::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'AuditoriaRegimen';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaRegimenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaRegimensTable::configure($table);
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
            'index' => ListAuditoriaRegimens::route('/'),
            'create' => CreateAuditoriaRegimen::route('/create'),
            'edit' => EditAuditoriaRegimen::route('/{record}/edit'),
        ];
    }
}
