<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamenLaboratorioKardexes extends Model
{
    protected $table = 'examen_laboratorio_kardexes';
    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_kardex_enfermeria', 
        'nombre_procedimiento', 
        'toma_muestra',
        'resultado',
        'fecha',
        'hora',
        'reactivo',
        'id_procedimiento'
    ];
}