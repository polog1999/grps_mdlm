<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Pais extends Model
{
    protected $connection = 'pgsql_public';
    protected $table = 'public.pais';

    protected $primaryKey = 'pai_id';


    const CREATED_AT = 'pai_filafecha';
    const UPDATED_AT = null;

    protected $fillable = [
        'pai_codigo',
        'pai_descripcion',
        'pai_activo',
        'pai_filafecha',
        'pai_filaoriginal',
        'pai_filaeliminada',
        'usa_id',
    ];

    protected $casts = [
        'pai_activo' => 'boolean',
        'pai_filaoriginal' => 'boolean',
        'pai_filaeliminada' => 'boolean',
        'pai_filafecha' => 'datetime',
        'pai_codigo' => 'integer',
        'usa_id' => 'integer',
    ];

    public function accesos(): HasMany
    {
        return $this->hasMany(QrAcceso::class, 'pai_id', 'pai_id');
    }
}