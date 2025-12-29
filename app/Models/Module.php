<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'modules';

    //primarykey
    protected $primaryKey = 'id';


    protected $fillable = [
        'name',
        'filament_class',
        'cluster',
    ];
}