<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Auditor_afiliacion extends Model
{
    protected $table = 'auditor_afiliacion';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_auditor',
        'id_trama_prestacion',
        'n_historia_clinica',
        'tipo_documento',
        'n_documento_identidad',
        'tipo_seguro',
        'fecha_nacimiento',
        'edad',
        'sexo',
        'lugar_nacimiento',
        'domicilio_actual',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'acompanante',
        'numero_acompanante',
        'fecha_atencion',
        'hora_atencion',
        'tiempo_enfermedad',
        'desarrollo_cronologico_enf',
        'motivo_visita',
        'antecedentes',
        'signos_vitales',
        'delete_us',
        'delete_at',
        'med_tipo_documento',
        'med_n_documento_identidad',
        'med_ape_paterno',
        'med_ape_materno',
        'med_nombres',
        'med_num_colegiatura',
        'med_numero_rne',
        'med_id_tipo_profes',
        'med_id_especialidad_prof',
        'id_departamento',
        'id_provincia',
        'id_distrito',
        'examenes_patologia_anulados',
        'examenes_imagenologia_anulados',

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