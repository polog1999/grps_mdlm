<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaSedes;

use App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\Pages\CreateAuditoriaSede;
use App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\Pages\EditAuditoriaSede;
use App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\Pages\ListAuditoriaSedes;
use App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\Schemas\AuditoriaSedeForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaSedes\Tables\AuditoriaSedesTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\AuditoriaSede;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaSedeResource extends Resource
{
    protected static ?string $model = AuditoriaSede::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'AuditoriaSede';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaSedeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaSedesTable::configure($table);
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
            'index' => ListAuditoriaSedes::route('/'),
            'create' => CreateAuditoriaSede::route('/create'),
            'edit' => EditAuditoriaSede::route('/{record}/edit'),
        ];
    }
}
