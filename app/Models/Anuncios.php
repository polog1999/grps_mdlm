<?php

namespace App\Models;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\AsuntoAnuncio;
use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\VigenciaAnuncio;
use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\Dictamen;
use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\EstadoAnuncio;
use Illuminate\Support\Facades\DB;


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
        'giro_especifico_snapshot',
        'latitud',
        'longitud',
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
        'asunto' => AsuntoAnuncio::class,
        'vigencia' => VigenciaAnuncio::class,
        'dictamen' => Dictamen::class,
        'estado_anuncio' => EstadoAnuncio::class,
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
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

    public function licencia(): BelongsTo
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'id_licencia');
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

    protected function mapsUrl(): Attribute
    {
        return Attribute::make()
            ->get(function ($value, $attributes) {
                return "https://www.google.com/maps/search/?api=1&query=" . $attributes['latitud'] . "," . $attributes['longitud'];
            });
    }

    public static function getSiguienteNumero(): ?string
    {
        // DB::scalar ejecuta la consulta y devuelve directamente el primer valor de la primera fila
        return DB::scalar('SELECT anuncios.fn_obtener_siguiente_n_anuncio()');
    }

}

