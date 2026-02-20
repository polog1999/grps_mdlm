<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExpedientesAnuncios extends Model
{
    use HasUuids;

    protected $table = 'anuncios.expedientes';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'n_expediente',
        'per_id_solicitante',
        'snapshot_solicitante_nombre_completo',
        'snapshot_solicitante_dni',
        'snapshot_solicitante_telefono',
        'snapshot_solicitante_direccion',
        'per_id_legal',
        'snapshot_legal_nombre',
        'snapshot_legal_dni_ruc',
        'snapshot_legal_telefono',
        'snapshot_legal_direccion',
        'folios',
        'zonificacion_id',
        'recibo_pago_id',
    ];

    protected $casts = [
        'folios' => 'integer',
    ];

    public function zonificacion(): BelongsTo
    {
        return $this->belongsTo(Zonificacion::class, 'zonificacion_id');
    }

    public function reciboPago(): BelongsTo
    {
        return $this->belongsTo(ReciboPago::class, 'recibo_pago_id');
    }

    public function anuncios(): HasMany
    {
        return $this->hasMany(Anuncios::class, 'expediente_id');
    }
}
