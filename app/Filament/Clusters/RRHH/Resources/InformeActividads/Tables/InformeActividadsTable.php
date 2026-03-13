<?php

namespace App\Filament\Clusters\RRHH\Resources\InformeActividads\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class InformeActividadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('fecha_subida', 'desc')
            ->recordUrl(null)
            ->columns([
                TextColumn::make('numero_informe')
                    ->searchable(),
                TextColumn::make('usuario.name')
                    ->searchable(),
                TextColumn::make('fecha_subida')
                    ->date('d/m/y')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('fecha_subida')
                    ->form([
                        DatePicker::make('desde')
                            ->label('Desde'),
                        DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['desde'], fn(Builder $q, $date) => $q->whereDate('fecha_subida', '>=', $date))
                            ->when($data['hasta'], fn(Builder $q, $date) => $q->whereDate('fecha_subida', '<=', $date));
                    }),
            ])
            ->recordActions([
                Action::make('descargar')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($record) {
                        $path = $record->url_archivo;

                        if (!file_exists($path)) {
                            Notification::make()
                                ->title('Archivo no encontrado')
                                ->body('El archivo PDF no se encuentra en el servidor.')
                                ->danger()
                                ->send();

                            return;
                        }

                        return response()->download($path, $record->numero_informe . '.pdf');
                    }),

                Action::make('reemplazar')
                    ->label('Reemplazar PDF')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->modalHeading('Reemplazar Informe PDF')
                    ->modalDescription('Sube un nuevo archivo PDF para reemplazar el actual.')
                    ->modalSubmitActionLabel('Reemplazar')
                    ->form([
                        FileUpload::make('nuevo_pdf')
                            ->label('Nuevo archivo PDF')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->disk('informes_tecnicos_teletrabajo')
                            ->visibility('public')
                            ->helperText('Solo se permiten archivos PDF. Tamaño máximo: 10 MB.'),
                    ])
                    ->action(function ($record, array $data) {
                        // Eliminar el archivo anterior si existe
                        $oldPath = $record->url_archivo;
                        if ($oldPath && file_exists($oldPath)) {
                            unlink($oldPath);
                        }

                        // Guardar la nueva ruta
                        $newPath = Storage::disk('informes_tecnicos_teletrabajo')->path($data['nuevo_pdf']);

                        $record->update([
                            'url_archivo' => $newPath,
                        ]);

                        Notification::make()
                            ->title('PDF reemplazado')
                            ->body('El informe "' . $record->numero_informe . '" ha sido actualizado correctamente.')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([

            ]);
    }
}

