<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VuLicencia extends Model
{
    protected $table = 'licencia.vu_licencia';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = null;

    protected $guarded = [];
}
