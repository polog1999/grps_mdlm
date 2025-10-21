<?php

namespace App\Filament\Resources\CertificadoInspeccions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CertificadoInspeccionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
           ->components([
                TextInput::make('cin_anio')
                    ->label('Año')
                    ->required()  
                    ->default(date('Y'))
                    ->numeric()
                    ->helperText('Ingrese el año del certificado.'),
                TextInput::make('tie_id')
                    ->label('Tipo de Edificación')
                    ->numeric(),
                TextInput::make('cin_numero')
                    ->label('Número')
                    ->type('number')            
                    ->numeric()               
                    ->minValue(1) 
                    ->placeholder('Ej. 6829')  
                    ->required()                 
                    ->rules(['integer', 'min:1']) 
                    ->helperText('Ingrese un número entero positivo.'),
                TextInput::make('cin_area')
                    ->label('Área (m²)')          
                    ->type('number')          
                    ->numeric()                   
                    ->minValue(0.01)          
                    ->step('0.01')               
                    ->suffix('m²')                
                    ->placeholder('Ej. 150.50')  
                    ->required(),                 

                TextInput::make('cin_capacidad')
                    ->label('Capacidad')
                    ->type('number')            
                    ->numeric()               
                    ->minValue(1) 
                    ->placeholder('Ej. 1')  
                    ->required()                 
                    ->rules(['integer', 'min:1']) 
                    ->helperText('Ingrese un número entero positivo.'),

                DatePicker::make('cin_fecha')
                    ->label('Fecha')
                    ->required()   
                    ->default(today())
                    ->helperText('Seleccione la fecha de expedicion.'),

                Toggle::make('cin_indeterminado')
                    ->label('Indeterminado')
                    ->reactive()
                    ->default(true),

                // Las fechas de inicio/fin se muestran SOLO cuando cin_indeterminado es false
                DatePicker::make('cin_fec_inicio')
                    ->label('Fecha Inicio')
                    ->reactive()
                    ->hidden(fn (callable $get) => $get('cin_indeterminado'))
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('cin_fec_fin', \Carbon\Carbon::parse($state)->addYears(2)->toDateString());
                        }
                    })
                    ->helperText('Seleccione la fecha de inicio del certificado.'),

                DatePicker::make('cin_fec_fin')
                    ->label('Fecha Fin')
                    ->hidden(fn (callable $get) => $get('cin_indeterminado'))
                    ->disabled()
                    ->helperText('La fecha de fin se calcula automáticamente como dos años después de la fecha de inicio.'),

                DateTimePicker::make('cin_filafecha')
                    ->label('Fila Fecha'),
                Toggle::make('cin_filaoriginal')
                    ->label('Fila Original'),
                Toggle::make('cin_filaeliminada')
                    ->label('Fila Eliminada'),
                TextInput::make('usa_id')
                    ->label('Usuario ID')
                    ->numeric(),
                Toggle::make('cin_consello')
                    ->label('Consejo'),
                TextInput::make('lic_id')
                    ->label('Licencia ID')
                    ->numeric(),

                TextInput::make('cin_licencia')
                    ->label('Licencia'),
                TextInput::make('cin_procedimiento')
                    ->label('Procedimiento'),
                TextInput::make('cin_departamento')
                    ->label('Departamento')
                    ->default('Lima')
                    ->disabled()
                    ->dehydrateStateUsing(fn ($state) => 'Lima'),

                TextInput::make('cin_provincia')
                    ->label('Provincia')
                    ->default('Lima')
                    ->disabled()
                    ->dehydrateStateUsing(fn ($state) => 'Lima'),

                TextInput::make('cin_distrito')
                    ->label('Distrito')
                    ->default('La Molina')
                    ->disabled()
                    ->dehydrateStateUsing(fn ($state) => 'La Molina'),
                TextInput::make('cin_expediente')
                    ->label('Expediente'),
                TextInput::make('cin_ubicacion')
                    ->label('Ubicación'),
                Textarea::make('cin_nota')
                    ->label('Nota'),
                TextInput::make('cin_resolucion_sigla')
                    ->label('Resolución Sigla'),
                TextInput::make('cin_giro')
                    ->label('Giro o actividad de la Edificación'),
                TextInput::make('cin_resolucion')
                    ->label('Resolución'),
                TextInput::make('cin_establecimiento')
                    ->label('Establecimiento'),
                TextInput::make('cin_solicitante')
                    ->label('Solicitante'),
            ]);
    }
}
