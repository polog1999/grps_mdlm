<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;


class Anuncios extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'anuncios.anuncios';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'n_anuncio',
        'expediente_id',
        'fecha_recepcion_evaluar',
        'asunto',
        'caracteristica_fisica_id',
        'tipo_anuncio_id',
        'id_licencia',
        'descripcion',
        'ancho_m',
        'alto_m',
        'espesor_cm',
        'ubicacion_del_anuncio',
        'n_de_caras',
        'vigencia',
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia',
        'dictamen',
        'obs',
        'estado_anuncio',
        'derivado_a_legal_user_id',
        'fecha_derivado',
        'materiales_descripcion',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected $casts = [
        'fecha_recepcion_evaluar' => 'date',
        'ancho_m' => 'decimal:2',
        'alto_m' => 'decimal:2',
        'espesor_cm' => 'decimal:2',
        'n_de_caras' => 'integer',
        'fecha_inicio_vigencia' => 'date',
        'fecha_fin_vigencia' => 'date',
        'fecha_derivado' => 'date',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(ExpedientesAnuncios::class, 'expediente_id');
    }

    public function caracteristicaFisica(): BelongsTo
    {
        return $this->belongsTo(CaracteristicasFisicas::class, 'caracteristica_fisica_id');
    }

    public function tipoAnuncio(): BelongsTo
    {
        return $this->belongsTo(TipoAnuncio::class, 'tipo_anuncio_id');
    }

    public function colores(): BelongsToMany
    {
        return $this->belongsToMany(
            Colores::class,
            'anuncios.anuncio_colores',
            'anuncio_id',
            'color_id'
        );
    }


    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentosAnuncio::class, 'anuncio_id');
    }

    public function derivadoLegal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'derivado_a_legal_user_id');
    }
}

