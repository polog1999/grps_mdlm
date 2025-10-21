<?php

namespace App\Filament\Resources\CertificadoInspeccions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CertificadoInspeccionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cin_anio')
                    ->numeric(),
                TextInput::make('tie_id')
                    ->numeric(),
                TextInput::make('cin_numero')
                    ->numeric(),
                TextInput::make('cin_area')
                    ->numeric(),
                TextInput::make('cin_capacidad')
                    ->numeric(),
                DatePicker::make('cin_fecha'),
                DatePicker::make('cin_fec_inicio'),
                DatePicker::make('cin_fec_fin'),
                Toggle::make('cin_indeterminado'),
                DateTimePicker::make('cin_filafecha'),
                Toggle::make('cin_filaoriginal'),
                Toggle::make('cin_filaeliminada'),
                TextInput::make('usa_id')
                    ->numeric(),
                Toggle::make('cin_consello'),
                TextInput::make('lic_id')
                    ->numeric(),
                TextInput::make('cin_departamento'),
                TextInput::make('cin_provincia'),
                TextInput::make('cin_licencia'),
                TextInput::make('cin_procedimiento'),
                TextInput::make('cin_distrito'),
                TextInput::make('cin_expediente'),
                TextInput::make('cin_ubicacion'),
                TextInput::make('cin_nota'),
                TextInput::make('cin_resolucion_sigla'),
                TextInput::make('cin_giro'),
                TextInput::make('cin_resolucion'),
                TextInput::make('cin_establecimiento'),
                TextInput::make('cin_solicitante'),
            ]);
    }
}
