<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamenImagenKardexes extends Model
{
    protected $table = 'examen_imagen_kardexes';
    protected $primaryKey = 'id'; 
    public $timestamps = false;
    protected $fillable = [
        'id_kardex_enfermeria', 
        'nombre_imagen', 
        'examen',
        'toma_imagen',
        'fecha',
        'hora',
        'id_procedimiento'
    ];
}