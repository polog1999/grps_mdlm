<?php

namespace App\Filament\Clusters\Visitas\Resources\TipoDocumentos;

use App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Pages\CreateTipoDocumento;
use App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Pages\EditTipoDocumento;
use App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Pages\ListTipoDocumentos;
use App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Schemas\TipoDocumentoForm;
use App\Filament\Clusters\Visitas\Resources\TipoDocumentos\Tables\TipoDocumentosTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\TipoDocumento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoDocumentoResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = TipoDocumento::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'TipoDocumento';

    public static function form(Schema $schema): Schema
    {
        return TipoDocumentoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoDocumentosTable::configure($table);
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
            'index' => ListTipoDocumentos::route('/'),
            // 'create' => CreateTipoDocumento::route('/create'),
            // 'edit' => EditTipoDocumento::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('view::visitas_tipo_documento');
    }
}
