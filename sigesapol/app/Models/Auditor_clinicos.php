<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Auditor_clinicos extends Model
{
    protected $table = 'auditor_clinicos';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_auditor_afiliacion',
        'id_auditor',
        'examen_patologia',
        'examen_diagnosticos_imagenes',
        'procedimiento_imagenes',
        'examen_laboratorio',
        'procedimientohistoria_clinica',
        'temperatura',
        'frecuencia_resperatoria',
        'frecuencia_cardiaca',
        'presion_arterial_1',
        'presion_arterial_2',
        'saturacion_oxigeno',
        'peso',
        'talla',
        'tamano_cintura',
        'fi02',
        'examen_fisico',
        'examen_auxiliares',
        'tipo_egreso',
        'condicion_egreso',
        'fecha_egreso',
        'hora_egreso',
        'create_us',
        'create_at',
        'update_us',
        'update_at',
        'delete_us',
        'delete_at',
        'estado_eliminado',
        'interconsulta'
    ];

    protected static function boot()
    {
      parent::boot();
      static::creating(function ($model) {
          $model->created_at = Carbon::now();
      });
      static::updating(function ($model) {
          $model->updated_at = Carbon::now();
      });
    }
}
