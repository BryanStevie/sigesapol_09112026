<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KardexInterconsultas extends Model
{
    protected $table = 'kardex_interconsultas';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_kardex_enfermeria', 
        'id_consultorios', 
        'fecha_envio',
        'fecha_respuesta',
        'hora_envio',
        'hora_respuesta'
    ];
}
