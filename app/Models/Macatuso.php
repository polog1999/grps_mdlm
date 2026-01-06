<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
/**
 * Modelo para la tabla DS_INFOCAT.MACATUSO (Oracle)
 * 
 * Representa el maestro de usos catastrales
 */
class Macatuso extends Model
{
    /**
     * Conexión a la base de datos Oracle
     */
    protected $connection = 'oracle';

    /**
     * Nombre de la tabla
     */
    protected $table = 'DS_INFOCAT.MACATUSO';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'CODUSO';

    /**
     * Tipo de clave primaria
     */
    protected $keyType = 'string';

    /**
     * Indica si la clave primaria es auto-incremental
     */
    public $incrementing = false;

    /**
     * Indica si el modelo debe usar timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'CODUSO',
        'DESCUSO',
        'USUARCREA',
        'FECHCREA',
        'HORACREA',
        'PCCREA',
        'PCMOD',
        'HORAMOD',
        'FECHMOD',
        'USUARMOD',
        'USOPRCODI',
        'USOPRDESC',
        'FLAGRRSS',
        'FLAGSER',
        'USOPRGRUPO',
        'CLASRRSS',
        'USOPRGRUPO08',
        'FLAGRRSS08',
        'FLAGSER08',
        'USOPRGRUPO09',
        'FLAGRRSS09',
        'FLAGSER09',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'CODUSO' => 'string',
        'DESCUSO' => 'string',
        'USUARCREA' => 'string',
        'FECHCREA' => 'date',
        'HORACREA' => 'string',
        'PCCREA' => 'string',
        'PCMOD' => 'string',
        'HORAMOD' => 'string',
        'FECHMOD' => 'date',
        'USUARMOD' => 'string',
        'USOPRCODI' => 'string',
        'USOPRDESC' => 'string',
        'FLAGRRSS' => 'string',
        'FLAGSER' => 'string',
        'USOPRGRUPO' => 'string',
        'CLASRRSS' => 'string',
        'USOPRGRUPO08' => 'string',
        'FLAGRRSS08' => 'string',
        'FLAGSER08' => 'string',
        'USOPRGRUPO09' => 'string',
        'FLAGRRSS09' => 'string',
        'FLAGSER09' => 'string',
    ];

    /**
     * Scope para buscar por código de uso
     */
    public function scopeByCodigo($query, string $codigo)
    {
        return $query->where('CODUSO', $codigo);
    }

    /**
     * Scope para buscar por descripción
     */
    public function scopeByDescripcion($query, string $descripcion)
    {
        return $query->where('DESCUSO', 'LIKE', "%{$descripcion}%");
    }

    /**
     * Scope para filtrar por código de uso principal
     */
    public function scopeByUsoPrincipal($query, string $codigoUsoPrincipal)
    {
        return $query->where('USOPRCODI', $codigoUsoPrincipal);
    }

    /**
     * Scope para filtrar por grupo de uso principal
     */
    public function scopeByGrupoUso($query, string $grupoUso)
    {
        return $query->where('USOPRGRUPO', $grupoUso);
    }

    /**
     * Scope para filtrar por flag de residuos sólidos
     */
    public function scopeConResiduosSolidos($query)
    {
        return $query->where('FLAGRRSS', 'S');
    }

    /**
     * Scope para filtrar por flag de servicio
     */
    public function scopeConServicio($query)
    {
        return $query->where('FLAGSER', 'S');
    }

    /**
     * Scope para filtrar por clasificación de residuos sólidos
     */
    public function scopeByClasificacionResiduos($query, string $clasificacion)
    {
        return $query->where('CLASRRSS', $clasificacion);
    }

    /**
     * Accessor para obtener el código de uso limpio (sin espacios)
     */
    public function getCodigoUsoAttribute()
    {
        return trim($this->CODUSO);
    }

    /**
     * Accessor para obtener la descripción limpia (sin espacios)
     */
    public function getDescripcionUsoAttribute()
    {
        return trim($this->DESCUSO);
    }

    /**
     * Verifica si tiene residuos sólidos
     */
    public function tieneResiduosSolidos(): bool
    {
        return trim($this->FLAGRRSS) === 'S';
    }

    /**
     * Verifica si tiene servicio
     */
    public function tieneServicio(): bool
    {
        return trim($this->FLAGSER) === 'S';
    }

    /**
     * Relación con fichas catastrales
     * Un uso catastral puede tener muchas fichas
     */
    public function fichasCatastrales()
    {
        return $this->hasMany(Mvcatfind::class, 'CODUSO', 'CODUSO');
    }
}

