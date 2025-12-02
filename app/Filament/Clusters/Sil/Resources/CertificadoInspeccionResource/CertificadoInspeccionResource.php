<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource;

use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages\CreateCertificadoInspeccion;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages\EditCertificadoInspeccion;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages\ListCertificadoInspeccions;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages\ViewCertificadoInspeccion;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\CertificadoInspeccionForm;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\CertificadoInspeccionInfolist;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Tables\CertificadoInspeccionsTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\CertificadoInspeccion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Group;


/**
 * Recurso de Filament para Certificados de Inspección.
 *
 * Esta clase define cómo se muestran y administran los registros de
 * `CertificadoInspeccion` dentro del panel administrativo Filament.
 *
 * Responsabilidades:
 * - Proveer el `form` (schema) para crear/editar certificados.
 * - Proveer el `infolist` para la vista detallada del registro.
 * - Delegar la configuración de la `table` a la clase CertificadoInspeccionsTable.
 * - Registrar las páginas (index, create, view, edit) usadas por la Resource.
 */
class CertificadoInspeccionResource extends Resource
{
    /**
     * Modelo Eloquent asociado a la Resource.
     *
     * @var class-string|null
     */
    protected static ?string $model = CertificadoInspeccion::class;


    protected static ?string $recordTitleAttribute = 'Certificado Inspeccion';

    // Etiquetas personalizadas para FilamentnavigationLabel

    protected static ?string $cluster = SilCluster::class;



    //ICON FOR NAVIGATIONGROUP
    protected static ?string $navigationLabel = 'Certificados de Inspección';
    protected static ?string $pluralModelLabel = 'Certificados de Inspección';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    /**
     * Configura y devuelve el esquema del formulario usado para crear/editar.
     *
     * Este método delega en `CertificadoInspeccionForm::configure` para
     * construir la estructura de campos (Schema) consumida por Filament.
     *
     * @param Schema $schema Instancia de Schema proporcionada por Filament.
     * @return Schema Schema configurado con los componentes del formulario.
     */
    public static function form(Schema $schema): Schema
    {
        return CertificadoInspeccionForm::configure($schema);
    }

    /**
     * Construye y devuelve el infolist (vista detallada) para un registro.
     *
     * El infolist se delega en `CertificadoInspeccionInfolist::configure` y se
     * complementa con secciones que muestran datos principales, vigencia,
     * establecimiento e información adicional.
     *
     * @param Schema $schema Instancia de Schema para configurar el infolist.
     * @return Schema Schema del infolist completo.
     */
    public static function infolist(Schema $schema): Schema
    {
        $infolist = CertificadoInspeccionInfolist::configure($schema);
        return $infolist
            ->schema([
                Group::make()
                    ->columns(1)
                    ->schema([
                        // --- Sección 1: Datos Principales del Certificado ---
                        Section::make('Información del Certificado')
                            ->description('Datos principales de identificación y estado del certificado')
                            ->icon('heroicon-o-document-text')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('cin_numero')
                                    ->label('Número de Certificado')
                                    ->icon('heroicon-o-identification')
                                    ->badge()
                                    ->color('info')
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold),

                                TextEntry::make('cin_anio')
                                    ->label('Año')
                                    ->badge()
                                    ->color('gray'),

                                TextEntry::make('cin_expediente')
                                    ->label('Expediente')
                                    ->badge()
                                    ->color('primary')
                                    ->icon('heroicon-o-folder-open'),
                                TextEntry::make('cin_licencia')
                                    ->label('Número de Licencia')
                                    ->badge()
                                    ->color('info')
                                    ->placeholder('No especificado'),


                                TextEntry::make('cin_resolucion_completa')
                                    ->label('Resolución')
                                    ->getStateUsing(fn($record) => $record->cin_resolucion . ' ' . $record->cin_resolucion_sigla)
                                    ->badge()
                                    ->color('primary')
                                    ->icon('heroicon-o-clipboard-document-check')
                                    ->columnSpan(2),
                            ]),
                        // --- Sección 2: Datos de Vigencia y Capacidad ---
                        Section::make('Vigencia y Capacidad')
                            ->description('Periodo de validez y características del establecimiento')
                            ->icon('heroicon-o-calendar')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('cin_fecha')
                                    ->label('Fecha de Emisión')
                                    ->date('d/m/Y')
                                    ->icon('heroicon-o-calendar-days')
                                    ->badge()
                                    ->color('gray'),

                                TextEntry::make('cin_fec_inicio')
                                    ->label('Inicio de Vigencia')
                                    ->date('d/m/Y')
                                    ->icon('heroicon-o-calendar')
                                    ->badge()
                                    ->color('success')
                                    ->placeholder('No especificada'),

                                TextEntry::make('cin_fec_fin')
                                    ->label('Fin de Vigencia')
                                    ->date('d/m/Y')
                                    ->icon('heroicon-o-calendar')
                                    ->badge()
                                    ->color(fn($record) => $record->cin_indeterminado ? 'gray' : 'warning')
                                    ->placeholder('No especificada')
                                    ->visible(fn($record) => !$record->cin_indeterminado),

                                IconEntry::make('cin_indeterminado')
                                    ->label('Vigencia Indeterminada')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('info')
                                    ->falseColor('gray')
                                    ->visible(fn($record) => $record->cin_indeterminado),

                                TextEntry::make('cin_capacidad')
                                    ->label('Capacidad')
                                    ->numeric()
                                    ->icon('heroicon-o-users')
                                    ->suffix(' personas')
                                    ->badge()
                                    ->color('primary')
                                    ->placeholder('No especificada'),

                                TextEntry::make('cin_area')
                                    ->label('Área Total')
                                    ->numeric(
                                        decimalPlaces: 2,
                                        decimalSeparator: '.',
                                        thousandsSeparator: ',',
                                    )
                                    ->suffix(' m²')
                                    ->icon('heroicon-o-square-3-stack-3d')
                                    ->badge()
                                    ->color('primary')
                                    ->placeholder('No especificada')
                                    ->columnSpan(2),
                            ]),

                    ]),

                Section::make('Establecimiento')
                    ->description('Información del establecimiento inspeccionado')
                    ->icon('heroicon-o-building-office')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('tipoEdificacion.tie_descripcion')
                            ->label('Tipo de Edificación')
                            ->badge()
                            ->size(TextSize::Large)
                            ->color(fn(string $state) => match ($state) {
                                'RIESGO BAJO' => 'info',
                                'RIESGO MEDIO' => 'warning',
                                'RIESGO ALTO' => 'danger',
                                'RIESGO MUY ALTO' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn(string $state) => match ($state) {
                                'RIESGO BAJO' => 'heroicon-o-shield-check',
                                'RIESGO MEDIO' => 'heroicon-o-shield-exclamation',
                                'RIESGO ALTO' => 'heroicon-o-exclamation-triangle',
                                'RIESGO MUY ALTO' => 'heroicon-o-fire',
                                default => 'heroicon-o-shield-exclamation',
                            })
                            ->columnSpanFull(),

                        TextEntry::make('cin_establecimiento')
                            ->label('Nombre del Establecimiento')
                            ->icon('heroicon-o-building-storefront')
                            ->weight(FontWeight::Medium)
                            ->columnSpanFull(),

                        TextEntry::make('cin_solicitante')
                            ->label('Solicitante')
                            ->icon('heroicon-o-user')
                            ->placeholder('No especificado')
                            ->columnSpanFull(),

                        TextEntry::make('cin_ubicacion')
                            ->label('Ubicación')
                            ->icon('heroicon-o-map-pin')
                            ->placeholder('No especificada')
                            ->columnSpanFull(),

                        TextEntry::make('cin_giro')
                            ->label('Giro del Negocio')
                            ->icon('heroicon-o-briefcase')
                            ->placeholder('No especificado')
                            ->columnSpanFull(),
                    ]),

                // --- Sección 3: Vigencia y Capacidad ---

                // --- Sección 4: Información Administrativa ---
                Section::make('Información Adicional')
                    ->description('Detalles técnicos y administrativos del certificado')
                    ->icon('heroicon-o-document-text')
                    ->columnSpanFull()
                    ->schema([

                        TextEntry::make('cin_nota')
                            ->label('Nota')
                            ->placeholder('Sin notas')
                            ->columnSpanFull(),


                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return CertificadoInspeccionsTable::configure($table)
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
            'index' => ListCertificadoInspeccions::route('/'),
            'create' => CreateCertificadoInspeccion::route('/create'),
            'view' => ViewCertificadoInspeccion::route('/{record}'),
            'edit' => EditCertificadoInspeccion::route('/{record}/edit'),
        ];
    }

}
