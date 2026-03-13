<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaCargos;

use App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\Pages\CreateAuditoriaCargo;
use App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\Pages\EditAuditoriaCargo;
use App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\Pages\ListAuditoriaCargos;
use App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\Schemas\AuditoriaCargoForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaCargos\Tables\AuditoriaCargosTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\AuditoriaCargo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaCargoResource extends Resource
{
    protected static ?string $model = AuditoriaCargo::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'AuditoriaCargo';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaCargoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaCargosTable::configure($table);
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
            'index' => ListAuditoriaCargos::route('/'),
            // 'create' => CreateAuditoriaCargo::route('/create'),
            // 'edit' => EditAuditoriaCargo::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('audit::visitas_cargo');
    }
}
