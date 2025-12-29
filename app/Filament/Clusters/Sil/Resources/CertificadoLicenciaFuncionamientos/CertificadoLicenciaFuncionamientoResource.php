<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\CreateCertificadoLicenciaFuncionamiento;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\EditCertificadoLicenciaFuncionamiento;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\DuplicateCertificadoLicenciaFuncionamiento;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\TransferirCertificadoLicenciaFuncionamiento;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\CesionarioCertificadoLicenciaFuncionamiento;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages\ListCertificadoLicenciaFuncionamientos;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\CertificadoLicenciaFuncionamientoForm;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Tables\CertificadoLicenciaFuncionamientosTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\CertificadoLicenciaFuncionamiento;
use BackedEnum;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CertificadoLicenciaFuncionamientoResource extends Resource
{
    protected static ?string $model = CertificadoLicenciaFuncionamiento::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'Certificado Licencia de Funcionamiento';
    protected static ?string $navigationLabel = 'Certificados de Licencia de Funcionamiento';
    protected static ?string $pluralModelLabel = 'Certificados de Licencia de Funcionamiento';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $cluster = SilCluster::class;



    public static function form(Schema $schema): Schema
    {
        return CertificadoLicenciaFuncionamientoForm::configure($schema);
    }

    /**
     * Determina si el usuario actual puede acceder a este recurso.
     * 
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->can('view::certificado_licencia_funcionamiento') ?? false;
    }


    public static function infolist(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return CertificadoLicenciaFuncionamientosTable::configure($table)
            ->bulkActions([

            ]);
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
            'index' => ListCertificadoLicenciaFuncionamientos::route('/'),
            'create' => CreateCertificadoLicenciaFuncionamiento::route('/create'),
            'edit' => EditCertificadoLicenciaFuncionamiento::route('/{record}/edit'),
            'duplicate' => DuplicateCertificadoLicenciaFuncionamiento::route('/{record}/duplicate'),
            'transfer' => TransferirCertificadoLicenciaFuncionamiento::route('/{record}/transfer'),
            'cesionario' => CesionarioCertificadoLicenciaFuncionamiento::route('/{record}/cesionario'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->limit(1000);
    }


}
