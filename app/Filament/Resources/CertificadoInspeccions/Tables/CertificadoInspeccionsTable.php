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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Indicator;
use App\Models\CertificadoInspeccion;
use App\Services\CertificadoInspeccionService;
use Filament\Actions\Action;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Notifications\Notification;

class CertificadoInspeccionsTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('cin_fecha', 'desc')
            ->searchable(true)
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_expediente')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cin_resolucion')->label('Resolución')->hidden()->searchable(),
                TextColumn::make('cin_resolucion_sigla')->label('Resolución Sigla')->hidden()->searchable(),

                TextColumn::make('cin_resolucion_completa')
                    ->label('Resolución')
                    ->getStateUsing(fn ($record) => $record->cin_resolucion. $record->cin_resolucion_sigla),
                TextColumn::make('cin_solicitante')
                    ->label('Solicitante')
                    ->searchable(),
                TextColumn::make('cin_ubicacion')
                    ->label('Ubicación')
                    ->searchable()
                    ->extraAttributes([
                        'style' => 'max-width:320px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;',
                    ])
                    ->tooltip(fn ($record) => $record->cin_ubicacion),
                TextColumn::make('cin_giro')
                    ->label('Giro')
                    ->searchable()
                    ->extraAttributes([
                        'style' => 'max-width:320px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;',
                    ])
                    ->tooltip(fn ($record) => $record->cin_giro),
                TextColumn::make('cin_fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_fec_inicio')
                    ->label('Vig. Fec. Inicio')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_fec_fin')
                    ->label('Vig. Fec. Fin')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_capacidad')
                    ->label('Capacidad')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_area')
                    ->label('Área (m²)')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ','
                    )
                    ->searchable()
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

                TextColumn::make('cin_nota')
                    ->label('Nota')
                    ->hidden()
                    ->searchable(),
                
   

                TextColumn::make('cin_establecimiento')
                    ->label('Establecimiento')
                    ->hidden()
                    ->searchable(),
            ])
->filters([
    //title filter
    SelectFilter::make('tie_id')
        ->label('Tipo de Edificación')
        ->relationship('tipoEdificacion', 'tie_descripcion')
        ->searchable()
        ->preload()
        ->indicator('Tipo de Edificación')
        ->placeholder('Todos los tipos'),

    SelectFilter::make('cin_anio')
        ->label('Año')
        ->options(fn () => CertificadoInspeccion::query()
            ->distinct()
            ->orderBy('cin_anio', 'desc')
            ->pluck('cin_anio', 'cin_anio')
            ->toArray())
        ->searchable()
        ->indicator('Año')
        ->placeholder('Todos los años')
        ->native(false),

    SelectFilter::make('cin_numero')
          ->label('N° Certificado')
        ->options(fn () => CertificadoInspeccion::query()
            ->select('cin_numero')
            ->distinct()
            ->whereNotNull('cin_numero')
            ->orderByDesc('cin_numero')
            ->pluck('cin_numero')
            ->mapWithKeys(fn ($v) => [(string) $v => (string) $v])
            ->toArray())
        ->searchable()
        ->indicator('N° Certificado')
        ->placeholder('Buscar número...')
        ->native(false),

    SelectFilter::make('cin_solicitante')
        ->label('Solicitante')
        ->options(fn () => CertificadoInspeccion::query()
            ->distinct()
            ->whereNotNull('cin_solicitante')
            ->where('cin_solicitante', '!=', '')
            ->orderBy('cin_solicitante', 'asc')
            ->pluck('cin_solicitante', 'cin_solicitante')
            ->toArray())
        ->searchable()
        ->indicator('Solicitante')
        ->placeholder('Buscar solicitante...')
        ->native(false),

    SelectFilter::make('cin_ubicacion') 
        ->label('Ubicación') 
        ->searchable() 
        ->getSearchResultsUsing(function (string $search): array {
            $service = new CertificadoInspeccionService(); 
            $ubicaciones = $service->buscarUbicacion($search); 
            return array_combine($ubicaciones, $ubicaciones);
        })
        ->getOptionLabelUsing(fn ($value): string => $value)
        ->indicator('Ubicación')
        ->placeholder('Buscar ubicación...')
        ->native(false),

    SelectFilter::make('cin_giro')
        ->label('Giro del Negocio')
        ->options(fn () => CertificadoInspeccion::query()
            ->distinct()
            ->whereNotNull('cin_giro')
            ->where('cin_giro', '!=', '')
            ->orderBy('cin_giro', 'asc')
            ->pluck('cin_giro', 'cin_giro')
            ->toArray())
        ->searchable()
        ->indicator('Giro')
        ->placeholder('Buscar giro...')
        ->native(false),

    Filter::make('cin_fecha')
        ->label('Fecha del Certificado')
        ->form([
            DatePicker::make('from')
                ->label('Desde')
                ->placeholder('Seleccionar fecha inicial')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->maxDate(now()),
            DatePicker::make('to')
                ->label('Hasta')
                ->placeholder('Seleccionar fecha final')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->maxDate(now()),
        ])
        ->query(function ($query, array $data) {
            return $query
                ->when($data['from'], fn ($query, $date) => 
                    $query->whereDate('cin_fecha', '>=', Carbon::parse($date))
                )
                ->when($data['to'], fn ($query, $date) => 
                    $query->whereDate('cin_fecha', '<=', Carbon::parse($date))
                );
        })
        ->indicateUsing(function (array $data): array {
            $indicators = [];
            
            if ($data['from'] ?? null) {
                $indicators[] = Indicator::make('Desde: ' . Carbon::parse($data['from'])->format('d/m/Y'))
                    ->removeField('from');
            }
            
            if ($data['to'] ?? null) {
                $indicators[] = Indicator::make('Hasta: ' . Carbon::parse($data['to'])->format('d/m/Y'))
                    ->removeField('to');
            }
            
            return $indicators;
        }),

    SelectFilter::make('cin_expediente')
        ->label('N° Expediente')
        ->options(fn () => CertificadoInspeccion::query()
            ->distinct()
            ->whereNotNull('cin_expediente')
            ->where('cin_expediente', '!=', '')
            ->orderBy('cin_expediente', 'desc')
            ->pluck('cin_expediente', 'cin_expediente')
            ->toArray())
        ->searchable()
        ->indicator('N° Expediente')
        ->placeholder('Buscar expediente...')
        ->native(false),

], layout: FiltersLayout::Modal)
    ->filtersFormColumns(4)
    ->filtersFormMaxHeight('400px')
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
                ->openUrlInNewTab(),
        Action::make('borrar')
                ->label('Borrar')
                ->icon('heroicon-o-trash')
                ->tooltip('Borrar certificado')
                ->iconButton()
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Eliminar Certificado')
                ->modalDescription('¿Está seguro que desea eliminar este certificado? Esta acción no se puede revertir.')
                ->modalSubmitActionLabel('Sí, eliminar')
                ->modalCancelActionLabel('Cancelar')
                ->action(function ($record) {
                    $record->cin_filaeliminada = true;
                    $record->save();
                    Notification::make()
                        ->success()
                        ->title('Certificado eliminado')
                        ->body('El certificado ha sido marcado como eliminado correctamente.')
                        ->send();
                })
                ->successRedirectUrl(fn () => request()->header('Referer') ?? route('filament.admin.resources.certificado-inspeccions.index')),
                        
            ], position: RecordActionsPosition::BeforeCells)
    ->modifyQueryUsing(fn ($query) => $query->where('cin_filaeliminada', false))
    ->toolbarActions([
        BulkActionGroup::make([
            DeleteBulkAction::make(),
        ]),
    ])
    ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filtros')
                    ->modalHeading('Filtros Avanzados de Certificados')
                    ->modalDescription('Utilice los filtros para refinar la lista de certificados según sus criterios.')
                    ->modalIcon('heroicon-o-funnel')
                    ->color('info')
                    ->modalSubmitActionLabel('Buscar Certificados')
                    ->modalCancelActionLabel('Cancelar')
    );

    }

}