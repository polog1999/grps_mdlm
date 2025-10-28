<?php

namespace App\Filament\Resources\CertificadoInspeccions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use App\Models\CertificadoInspeccion;
use App\Services\CertificadoInspeccionService;
use Filament\Actions\Action;

class CertificadoInspeccionsTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipoEdificacion.tie_descripcion')
                    ->label('Edificación')
                    ->numeric()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'RIESGO BAJO' => 'info',
                        'RIESGO MEDIO' => 'warning',
                        'RIESGO ALTO' => 'danger-high',
                        'RIESGO MUY ALTO' => 'danger-very-high', 
                        "EX POST" => 'info',
                        "EX ANTE" => 'info',
                        "DE PARTE" => 'info',
                        "DE DETALLE" => 'info',
                    })
                    ->sortable(),
                TextColumn::make('cin_anio')
                    ->label('Año')
                    ->sortable(),
                TextColumn::make('cin_numero')
                    ->label('N° Certificado')
                    ->sortable(),
                TextColumn::make('cin_expediente')
                    ->label('Expediente')
                    ->searchable(),
                TextColumn::make('cin_resolucion')
                    ->label('Resolución')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('cin_resolucion_sigla')
                    ->label('Resolución Sigla')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('cin_resolucion_completa')
                    ->label('Resolución Completa')
                    ->getStateUsing(fn ($record) => $record->cin_resolucion. $record->cin_resolucion_sigla)
                    ->searchable(),
                TextColumn::make('cin_solicitante')
                    ->label('Solicitante')
                    ->searchable(),
                TextColumn::make('cin_ubicacion')
                    ->label('Ubicación')
                    ->searchable(),
                TextColumn::make('cin_giro')
                    ->label('Giro')
                    ->searchable(),
                TextColumn::make('cin_fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('cin_fec_inicio')
                    ->label('Vig. Fec. Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('cin_fec_fin')
                    ->label('Vig. Fec. Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('cin_capacidad')
                    ->label('Capacidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cin_area')
                    ->label('Área (m²)')
                    ->numeric()
                    ->sortable(), 


        
                IconColumn::make('cin_indeterminado')
                    ->label('Indeterminado')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('cin_filafecha')
                    ->label('Fecha de Fila')
                    ->dateTime()
                    ->hidden()
                    ->sortable(),
                IconColumn::make('cin_filaoriginal')
                    ->label('Fila Original')
                    ->hidden()
                    ->boolean(),
                IconColumn::make('cin_filaeliminada')
                    ->label('Fila Eliminada')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('usa_id')
                    ->numeric()
                    ->hidden(),

                IconColumn::make('cin_consello')
                    ->label('Consello')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('lic_id')
                    ->label('Licencia ID')
                    ->numeric()
                    ->hidden(),
                TextColumn::make('cin_departamento')
                    ->label('Departamento')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_provincia')
                    ->label('Provincia')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_distrito')
                    ->label('Distrito')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_licencia')
                    ->label('Licencia Número')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_procedimiento')
                    ->label('Procedimiento')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_expediente')
                    ->label('Expediente')
                    ->hidden()
                    ->searchable(),

                TextColumn::make('cin_nota')
                    ->label('Nota')
                    ->hidden()
                    ->searchable(),
                
   

                TextColumn::make('cin_establecimiento')
                    ->label('Establecimiento')
                    ->hidden()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->hidden()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime()
                    ->hidden()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tie_id')
                    ->label('Tipo de Edificación')
                    ->relationship('tipoEdificacion', 'tie_descripcion')
                    ->searchable(),

                SelectFilter::make('cin_anio')
                    ->label('Año')
                    ->options(fn () => CertificadoInspeccion::query()
                        ->distinct()
                        ->orderBy('cin_anio', 'desc')
                        ->pluck('cin_anio', 'cin_anio')
                        ->toArray())
                    ->searchable(),

                //Numero de certificado
                SelectFilter::make('cin_numero')
                    ->label('Número de Certificado')
                    ->options(fn () => CertificadoInspeccion::query()
                        ->distinct()
                        ->orderBy('cin_numero', 'desc')
                        ->pluck('cin_numero', 'cin_numero')
                        ->toArray())
                    ->searchable(),

                //Solicitante
                SelectFilter::make('cin_solicitante')
                    ->label('Solicitante')
                    ->options(fn () => CertificadoInspeccion::query()
                        ->distinct()
                        ->orderBy('cin_solicitante', 'asc')
                        ->pluck('cin_solicitante', 'cin_solicitante')
                        ->toArray())
                    ->searchable(),
                //Ubicacion, pero solo buscar por los primero 4 caracteres
                SelectFilter::make('cin_ubicacion') 
                    ->label('Ubicación') 
                    ->searchable() 
                    ->getSearchResultsUsing(function (string $search): array {
                        $service = new CertificadoInspeccionService(); 
                        $ubicaciones = $service->buscarUbicacion($search); 
                        return array_combine($ubicaciones, $ubicaciones);
                    })
                    ->getOptionLabelUsing(fn ($value): string => $value),

                SelectFilter::make('cin_giro')
                    ->label('Giro')
                    ->options(fn () => CertificadoInspeccion::query()
                        ->distinct()
                        ->orderBy('cin_giro', 'asc')
                        ->pluck('cin_giro', 'cin_giro')
                        ->toArray())
                    ->searchable(),
                SelectFilter::make('cin_fecha')
                    ->label('Fecha Certificado')
                    ->form([
                        TextInput::make('from')
                            ->label('Desde')
                            ->type('date'),
                        TextInput::make('to')
                            ->label('Hasta')
                            ->type('date'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['from']) {
                            $query->whereDate('cin_fecha', '>=', Carbon::parse($data['from']));
                        }
                        if ($data['to']) {
                            $query->whereDate('cin_fecha', '<=', Carbon::parse($data['to']));
                        }
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
               ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip('Ver detalles del certificado')
                    ->color('info'),

                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Editar certificado')
                    ->color('warning'),
             Action::make('exportar')
                    ->label('Exportar')
                    ->icon('heroicon-o-document')
                    ->tooltip('Exportar certificado (PDF)')
                    ->iconButton()
                    ->color('success')
                    ->url(fn ($record) => route('test.certificadoInspeccion.exportarPdf', ['certificadoId' => $record->cin_id]))
                    ->openUrlInNewTab()
                

            ])
            ->modifyQueryUsing(fn ($query) => $query->where('cin_filaeliminada', false))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
        }
    }