<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote1 extends Model
{
    use HasFactory;

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'pgsql_finereport';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lote1';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'gid';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sector_cat',
        'mzna_cat',
        'lote_cat',
        'cod_lote_cat',
        'peligrosidad',
        'ubicacionareaverde',
        'subsector',
        'zonif_anterior',
        'lote_urbano',
        'mz_urbana',
        'arealoteurbano',
        'zonif_actual',
        'altura_actual',
        'cod_cat_anterior',
        'altura_anterior',
        'geometry',
        'geometrycentroid',
        'arealotecartografico',
        'zonif_trama',
        'lot_esquina',
        'sublote_urbano',
        'lot_fecha',
        'lot_usuariobd',
        'lot_catsc',
        'lot_catmz',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'peligrosidad' => 'integer',
        'ubicacionareaverde' => 'integer',
        'arealoteurbano' => 'float',
        'arealotecartografico' => 'float',
        'zonif_trama' => 'boolean',
        'lot_esquina' => 'integer',
        'lot_fecha' => 'datetime',
        'lot_catsc' => 'integer',
        'lot_catmz' => 'integer',
    ];
}
