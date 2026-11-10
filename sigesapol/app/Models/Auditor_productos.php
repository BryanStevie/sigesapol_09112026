<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Auditor_productos extends Model
{
    protected $table = 'auditor_productos';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'renipress',
        'numero_lote_fups',
        'numero_fups',
        'id_cie10',
        'id_prod_med',
        'cantidad_prescrita',
        'cantidad_entregada',
        'precio',
        'valorizacion',
        'correlativo',
        'id_auditor_afiliacion',
        'id_trama_prestacion',
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