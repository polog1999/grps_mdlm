<?php

namespace App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DataLevantamientoConsolidaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('feclevan'),
                TextInput::make('sml'),
                TextInput::make('mza_urb'),
                TextInput::make('lot_urb'),
                TextInput::make('img_edificacion'),
                TextInput::make('npisos'),
                TextInput::make('usopredom'),
                Textarea::make('flg_otrousos')
                    ->columnSpanFull(),
                TextInput::make('det_ousos'),
                TextInput::make('numacteco'),
                TextInput::make('giro1'),
                TextInput::make('img_licencia'),
                TextInput::make('img_itse'),
                TextInput::make('giro2'),
                TextInput::make('img_lic_g2'),
                TextInput::make('giro3'),
                TextInput::make('tienelf1'),
                TextInput::make('img_lic_g3'),
                TextInput::make('giro4'),
                TextInput::make('tienelf5'),
                TextInput::make('img_lf_gir4'),
                TextInput::make('giro5'),
                TextInput::make('tienelf2'),
                TextInput::make('tienelf3'),
                TextInput::make('tienelf4'),
                TextInput::make('img_lf_gir31'),
                TextInput::make('img_lf_gir41'),
                TextInput::make('img_lf_gir5'),
                TextInput::make('ei_cam_vigil'),
                TextInput::make('publicidad'),
                TextInput::make('ei_estacionam'),
                TextInput::make('reja'),
                TextInput::make('ei_otros'),
                TextInput::make('ei_dotros'),
                TextInput::make('num_estacionam'),
                TextInput::make('img_ei'),
                TextInput::make('numacteco1'),
                TextInput::make('ae_ambul_giro1'),
                TextInput::make('ae_tipo_estructura_1'),
                TextInput::make('otro_amb_01'),
                TextInput::make('img_ae_amb_01'),
                TextInput::make('ae_ambul_giro2'),
                TextInput::make('ae_tipo_estructura_2'),
                TextInput::make('img_ae_amb_02'),
                TextInput::make('ae_ambul_giro3'),
                TextInput::make('ae_tipo_estructura_3'),
                TextInput::make('otro_amb_02'),
                TextInput::make('otro_amb_03'),
                TextInput::make('img_ae_amb_021'),
                TextInput::make('observa'),
                TextInput::make('autoriza_gir1'),
                TextInput::make('certif_itse1'),
                TextInput::make('cesto_basura'),
                TextInput::make('estamb_01'),
                TextInput::make('estamb02'),
                Textarea::make('esp_otro')
                    ->columnSpanFull(),
                Textarea::make('estado_terreno')
                    ->columnSpanFull(),
                Textarea::make('hidrante')
                    ->columnSpanFull(),
                Textarea::make('otros_usos_espec')
                    ->columnSpanFull(),
                Textarea::make('publicidad_externa')
                    ->columnSpanFull(),
                TextInput::make('correo'),
                Textarea::make('num_act_amb')
                    ->columnSpanFull(),
            ]);
    }
}
