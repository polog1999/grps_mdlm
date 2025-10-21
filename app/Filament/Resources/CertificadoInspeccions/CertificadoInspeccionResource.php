<?php

namespace App\Filament\Resources\CertificadoInspeccions;

use App\Filament\Resources\CertificadoInspeccions\Pages\CreateCertificadoInspeccion;
use App\Filament\Resources\CertificadoInspeccions\Pages\EditCertificadoInspeccion;
use App\Filament\Resources\CertificadoInspeccions\Pages\ListCertificadoInspeccions;
use App\Filament\Resources\CertificadoInspeccions\Pages\ViewCertificadoInspeccion;
use App\Filament\Resources\CertificadoInspeccions\Schemas\CertificadoInspeccionForm;
use App\Filament\Resources\CertificadoInspeccions\Schemas\CertificadoInspeccionInfolist;
use App\Filament\Resources\CertificadoInspeccions\Tables\CertificadoInspeccionsTable;
use App\Models\CertificadoInspeccion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CertificadoInspeccionResource extends Resource
{
    protected static ?string $model = CertificadoInspeccion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Certificado Inspeccion';

    public static function form(Schema $schema): Schema
    {
        return CertificadoInspeccionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CertificadoInspeccionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CertificadoInspeccionsTable::configure($table);
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
            'index' => ListCertificadoInspeccions::route('/'),
            'create' => CreateCertificadoInspeccion::route('/create'),
            'view' => ViewCertificadoInspeccion::route('/{record}'),
            'edit' => EditCertificadoInspeccion::route('/{record}/edit'),
        ];
    }
}
