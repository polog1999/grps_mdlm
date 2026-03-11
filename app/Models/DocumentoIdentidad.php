<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoIdentidad extends Model {
    protected $table = 'visitas.documentos_identidades';
    protected $primaryKey = 'id_tipo_docu';
    protected $fillable = ['no_tipo_doc', 'nc_tipo_doc', 'estado'];
}
