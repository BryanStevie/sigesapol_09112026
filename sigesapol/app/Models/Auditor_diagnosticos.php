<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Auditor_diagnosticos extends Model
{
    protected $table = 'auditor_diagnosticos';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_auditor_afiliacion',
        'id_diagnostico',
        'id_cie10',
        'id_diagnostico_clasificacion',
        'renipress',
        'delete_us',
        'delete_at',
        'flag_estado',
        'estado_eliminado',
        'id_auditor',
        'tipo_diagnostico',
        'comentario',
        'id_identificador_diag'
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