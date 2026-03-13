<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors;

use App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Pages\CreateAuditoriaTrabajador;
use App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Pages\EditAuditoriaTrabajador;
use App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Pages\ListAuditoriaTrabajadors;
use App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Schemas\AuditoriaTrabajadorForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Tables\AuditoriaTrabajadorsTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\AuditoriaTrabajador;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaTrabajadorResource extends Resource
{
    protected static ?string $model = AuditoriaTrabajador::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'AuditoriaTrabajador';
    protected static ?string $navigationLabel = 'Auditoría Trabajadores'; // <-- Cambia el nombre en el menú
    protected static ?string $pluralModelLabel = 'Auditoría Trabajadores'; // Corrige el título principal y las migas de pan
    protected static ?string $slug = 'auditoria-trabajadores';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaTrabajadorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaTrabajadorsTable::configure($table);
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
            'index' => ListAuditoriaTrabajadors::route('/'),
            // 'create' => CreateAuditoriaTrabajador::route('/create'),
            // 'edit' => EditAuditoriaTrabajador::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('audit::visitas_trabajador');
    }
}
