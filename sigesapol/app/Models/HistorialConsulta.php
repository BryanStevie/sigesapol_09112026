<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialConsulta extends Model
{
    protected $table = 'historial_consulta';
    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_prestacion', 
        'motivo',
        'fecha',
        'hora',
        'id_user'
    ];
}
