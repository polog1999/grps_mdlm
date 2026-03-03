<?php

namespace App\Livewire;

use App\Models\Visita;
use App\Models\VisitaHistorico;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Concerns\InteractsWithActions; // <--- AÑADIR ESTO
use Filament\Actions\Contracts\HasActions;         // <--- AÑADIR ESTO
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder; // <--- ESTA ES LA QUE IMPORTA
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class PublicVisitasTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions; // <--- USAR EL TRAIT AQUÍ

    public function table(Table $table): Table
    {
        return $table
            ->query(VisitaHistorico::query())
            ->defaultPaginationPageOption(10)
            // Aplicamos un scope global para vaciar la tabla si no hay filtro
            ->modifyQueryUsing(function (Builder $query) {
                // Accedemos al estado de los filtros de Livewire
                $dateFilter = $this->tableFilters['hora_ingreso']['fecha'] ?? null;

                if (blank($dateFilter)) {
                    return $query->whereRaw('1 = 0');
                }
            })
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex() // <--- Esta es la clave en Filament v3
                    ->alignCenter(),
                TextColumn::make('numero_documento')
                    ->label('Documento')
                    ->searchable(),

                TextColumn::make('Apellidos y nombres'),
                TextColumn::make('area')
                    ->label('Área')
                    ->searchable(),
                TextColumn::make('Autorizado por')
                    ->label('Autorizado por')
                    ->searchable(),
                TextColumn::make('sede')
                    ->label('Sede')
                    ->searchable(),
                TextColumn::make('hora_ingreso')
                    ->label('Ingreso')
                    ->searchable(),
                TextColumn::make('hora_salida')
                    ->label('Salida')
                    ->searchable(),
                TextColumn::make('motivo')
                    ->label('Motivo de Visita'),
            ])
            ->filters([
                Filter::make('hora_ingreso')
                    ->form([ // Cambiado de schema() a form() que es más estándar en v3
                        DatePicker::make('fecha')
                            ->label('Fecha de Visita'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha'],
                                fn(Builder $query, $date): Builder => $query->whereDate('fecha', $date),
                            );
                    })
            ]);
    }

    public function render()
    {
        return view('livewire.public-visitas-table');
    }
}
