<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoBorrados;

use App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Pages\CreateCertificadoBorrado;
use App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Pages\EditCertificadoBorrado;
use App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Pages\ListCertificadoBorrados;
use App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Schemas\CertificadoBorradoForm;
use App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Tables\CertificadoBorradosTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\CertificadoBorrado;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CertificadoBorradoResource extends Resource
{
    protected static ?string $model = CertificadoBorrado::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = SilCluster::class;

    protected static ?string $recordTitleAttribute = 'CertificadoBorrado';

    public static function form(Schema $schema): Schema
    {
        return CertificadoBorradoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CertificadoBorradosTable::configure($table);
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
            'index' => ListCertificadoBorrados::route('/'),
            'create' => CreateCertificadoBorrado::route('/create'),
            'edit' => EditCertificadoBorrado::route('/{record}/edit'),
        ];
    }
}
