<?php

namespace App\Filament\Resources\CertificadoInspeccions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CertificadoInspeccionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('cin_anio')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tie_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cin_numero')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cin_area')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cin_capacidad')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cin_fecha')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('cin_fec_inicio')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('cin_fec_fin')
                    ->date()
                    ->placeholder('-'),
                IconEntry::make('cin_indeterminado')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('cin_filafecha')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('cin_filaoriginal')
                    ->boolean()
                    ->placeholder('-'),
                IconEntry::make('cin_filaeliminada')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('usa_id')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('cin_consello')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('lic_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cin_departamento')
                    ->placeholder('-'),
                TextEntry::make('cin_provincia')
                    ->placeholder('-'),
                TextEntry::make('cin_licencia')
                    ->placeholder('-'),
                TextEntry::make('cin_procedimiento')
                    ->placeholder('-'),
                TextEntry::make('cin_distrito')
                    ->placeholder('-'),
                TextEntry::make('cin_expediente')
                    ->placeholder('-'),
                TextEntry::make('cin_ubicacion')
                    ->placeholder('-'),
                TextEntry::make('cin_nota')
                    ->placeholder('-'),
                TextEntry::make('cin_resolucion_sigla')
                    ->placeholder('-'),
                TextEntry::make('cin_giro')
                    ->placeholder('-'),
                TextEntry::make('cin_resolucion')
                    ->placeholder('-'),
                TextEntry::make('cin_establecimiento')
                    ->placeholder('-'),
                TextEntry::make('cin_solicitante')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
