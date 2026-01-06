<?php

namespace App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AuditoriaLicenciaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tli_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tes_id')
                    ->required()
                    ->numeric(),
                TextInput::make('per_idrazonsocial')
                    ->required()
                    ->numeric(),
                TextInput::make('per_idsolicitante')
                    ->required()
                    ->numeric(),
                TextInput::make('lic_numlic'),
                TextInput::make('lic_codigopredial'),
                TextInput::make('lic_expnum'),
                TextInput::make('lic_direccion'),
                TextInput::make('lic_urbanizacion'),
                TextInput::make('lic_area')
                    ->numeric(),
                Toggle::make('lic_mype'),
                TextInput::make('lic_resnum'),
                DatePicker::make('lic_fecharesolucion'),
                DatePicker::make('lic_fechaemision'),
                DatePicker::make('lic_fechavencimiento'),
                TextInput::make('lic_licobs'),
                Toggle::make('lic_filaoriginal')
                    ->required(),
                Toggle::make('lic_filaeliminada')
                    ->required(),
                TextInput::make('lic_giro'),
                TextInput::make('lic_liccodi'),
                TextInput::make('esl_id')
                    ->numeric(),
                Toggle::make('lic_migracion'),
                Toggle::make('lic_cerrado'),
                DateTimePicker::make('lic_filafecha'),
                TextInput::make('lic_horainicio'),
                TextInput::make('lic_horafin'),
                TextInput::make('tir_id')
                    ->numeric(),
                DatePicker::make('lic_fechanotificacion'),
                DatePicker::make('lic_fechalimite'),
                TextInput::make('usa_id')
                    ->numeric(),
                TextInput::make('lic_razonsocial'),
                TextInput::make('lic_obscer'),
                Textarea::make('lic_nota')
                    ->columnSpanFull(),
                TextInput::make('lic_compatibilidad'),
                Textarea::make('lic_rsgparrafo1')
                    ->columnSpanFull(),
                Textarea::make('lic_rsgparrafo2')
                    ->columnSpanFull(),
                TextInput::make('tli_id_ant')
                    ->numeric(),
                TextInput::make('nir_id')
                    ->numeric(),
                DatePicker::make('lic_expfec'),
                TextInput::make('lic_compatibilidadnumero'),
                DatePicker::make('lic_compatibilidadfecha'),
                TextInput::make('lic_creado_por')
                    ->numeric(),
                DateTimePicker::make('lic_creado_en'),
                TextInput::make('lic_actualizado_por')
                    ->numeric(),
                DateTimePicker::make('lic_actualizado_en'),
            ]);
    }
}
