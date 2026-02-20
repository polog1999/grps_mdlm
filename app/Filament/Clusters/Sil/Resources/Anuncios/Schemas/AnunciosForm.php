<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AnunciosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('n_anuncio')
                    ->required(),
                Select::make('expediente_id')
                    ->relationship('expediente', 'id')
                    ->required(),
                DatePicker::make('fecha_recepcion_evaluar'),
                Textarea::make('asunto')
                    ->columnSpanFull(),
                Select::make('caracteristica_fisica_id')
                    ->relationship('caracteristicaFisica', 'id')
                    ->required(),
                Select::make('tipo_anuncio_id')
                    ->relationship('tipoAnuncio', 'id')
                    ->required(),
                TextInput::make('id_licencia'),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                TextInput::make('ancho_m')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('alto_m')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('espesor_cm')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('ubicacion_del_anuncio'),
                TextInput::make('n_de_caras')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('dictamen'),
                Textarea::make('obs')
                    ->columnSpanFull(),
                TextInput::make('estado_anuncio')
                    ->required(),
                TextInput::make('derivado_a_legal_user_id')
                    ->numeric(),
                DatePicker::make('fecha_derivado'),
                TextInput::make('created_by_user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('updated_by_user_id')
                    ->numeric(),
                TextInput::make('vigencia')
                    ->required()
                    ->default('INDETERMINADA'),
                DatePicker::make('fecha_inicio_vigencia'),
                DatePicker::make('fecha_fin_vigencia'),
            ]);
    }
}
