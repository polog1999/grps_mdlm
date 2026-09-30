<?php

namespace App\Filament\Clusters\Sil\Resources\AnunciosBajas\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AnunciosBajasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('anuncio.n_anuncio')
                    ->label('N° Anuncio')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('anuncio', function (Builder $q) use ($search) {
                            $q->whereRaw("CAST(n_anuncio AS TEXT) LIKE ?", ["%{$search}%"]);
                        });
                    }),

                TextColumn::make('anuncio.licencia.lic_numlic')
                    ->label('N° Licencia')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereExists(function ($sub) use ($search) {
                            $sub->select(DB::raw(1))
                                ->from('anuncios.anuncios')
                                // Hacemos el JOIN manual haciendo CAST para que Postgres no falle
                                ->join('licencia.licencia', DB::raw('CAST(anuncios.anuncios.id_licencia AS TEXT)'), '=', DB::raw('CAST(licencia.licencia.lic_id AS TEXT)'))
                                ->whereColumn('anuncios.anuncios_baja.anuncio_id', 'anuncios.anuncios.id')
                                ->where('licencia.licencia.lic_numlic', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('anuncio.expediente.n_expediente')
                    ->label('N° Expediente')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('anuncio.expediente', function (Builder $q) use ($search) {
                            $q->whereRaw("CAST(n_expediente AS TEXT) LIKE ?", ["%{$search}%"]);
                        });
                    }),

                TextColumn::make('usuario.name')
                    ->label('Usuario')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('usuario', function (Builder $q) use ($search) {
                            $q->whereRaw("CAST(name AS TEXT) LIKE ?", ["%{$search}%"]);
                        });
                    }),

                TextColumn::make('a_razon_baja')
                    ->label('Razón de Baja')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("CAST(a_razon_baja AS TEXT) LIKE ?", ["%{$search}%"]);
                    }),

                TextColumn::make('created_at')
                    ->label('Fecha de Baja')
                    ->dateTime('d/m/Y h:i a'),
            ]);
    }
}
