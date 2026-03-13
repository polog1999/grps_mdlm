<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReciboPago extends Model
{
    protected $table = 'anuncios.recibo_pago';

    protected $fillable = [
        'n_recibo_pago',
        'monto',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function expediente(): HasOne
    {
        return $this->hasOne(ExpedientesAnuncios::class, 'recibo_pago_id');
    }
}
