<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViaAdministracion extends Model
{
    protected $table = 'via_administracion';
    protected $primaryKey = 'id'; 
    public $timestamps = false;
    protected $fillable = [
        'descripcion', 
        'created_at', 
        'updated_at',
        'estado',
    ];
}
