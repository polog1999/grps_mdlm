<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos;

use App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\Pages\CreateAuditoriaTipoDocumento;
use App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\Pages\EditAuditoriaTipoDocumento;
use App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\Pages\ListAuditoriaTipoDocumentos;
use App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\Schemas\AuditoriaTipoDocumentoForm;
use App\Filament\Clusters\Visitas\Resources\AuditoriaTipoDocumentos\Tables\AuditoriaTipoDocumentosTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\AuditoriaTipoDocumento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AuditoriaTipoDocumentoResource extends Resource
{
    protected static ?string $model = AuditoriaTipoDocumento::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-eye-cog';

    protected static string|UnitEnum|null $navigationGroup = 'Auditorí­a';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'AuditoriaTipoDocumento';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaTipoDocumentoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaTipoDocumentosTable::configure($table);
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
            'index' => ListAuditoriaTipoDocumentos::route('/'),
            // 'create' => CreateAuditoriaTipoDocumento::route('/create'),
            // 'edit' => EditAuditoriaTipoDocumento::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('audit::visitas_tipo_documento');
    }
}
