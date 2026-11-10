<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Auditor_procedimientos extends Model
{
    protected $table = 'auditor_procedimientos';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_auditor_afiliacion',
        'id_procedimiento',
        'id_diagnostico',
        'id_cie10',
        'cantidad_ejecutada',
        'cantidad_indicada',
        'fecha_procedimiento',
        'estado_eliminado'
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