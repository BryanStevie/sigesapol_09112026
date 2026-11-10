<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class trama_procedimientos extends Model
{
  protected $table = 'trama_procedimientos';
  protected $primaryKey = 'id';
  public $timestamps = false;
  protected $fillable = [
    'renipress',
    'numero_lote_fups',
    'numero_fups',
    'id_cie10',
    'id_proced',
    'cantidad_indicada',
    'cantidad_ejecutada',
    'precio',
    'valorizacion',
    'correlativo',
    'id_trama_prestacion',
    'tipo_procedimiento',
    'flag_evolucion',
    'lab_consulta_ws',
    'ima_consulta_ws',
    'fecha_procedimiento',
    'resultado_procedimiento',
    'fecha_registro',
    'dni_medico',
    'estado',
    'actualizado',
    'historico',
    'created_us',
    'updated_us',
  ];
}
