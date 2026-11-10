<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratorioTomaMuestraHistorial extends Model
{
    protected $table = 'laboratorio_toma_muestra_historial';
    public $timestamps = false;

    protected $fillable = [
        'id_laboratorio',
        'observacion',
        'id_estado_reg',
        'created_us',
        'created_at',
    ];
}
