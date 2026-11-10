<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Prestacion_procedimiento extends Model
{
  protected $table = 'prestacion_procedimientos';
  public $timestamps = false;
  protected $primaryKey = 'id'; 
  protected $fillable = [
    'id',
    'id_prestacion',
    'id_procedimiento',
    'cantidad',
    'id_estado_reg',
    'created_at',
    'created_us',
    'updated_at',
    'updatet_us',
    'deleted_at',
    'deleted_us',
    'informe_parrafo',
    'transcribed_us',
    'transcribed_at',
    'flag_informe',
    'validated_us',
    'validated_at',
    'flag_validated',
    'id_rua',
    'id_lab_rad',
    'confirmar_toma',
    'id_evaluacion',
    'tipo_procedimiento',
    'flag',
    'fecha_firma',
    'ruta_receta',
    'total_informe',
    'tipo_plantilla',
    'observacion',
    'comparativo_radio',
    'lado_radio',
    'id_diagnostico',
    'id_interconsulta',
    'tipo_muestra_microbiologia',
    'id_solicitud_procedimiento',
    'enfermeria',
    'observacion_registro_informe',
    'created_at_registro_informe',
    'created_us_registro_informe',
    'updated_at_registro_informe',
    'updated_us_registro_informe',
    'id_evolucion_enfermeria',
    'id_enfermeria',
    'id_consejeria',
    'archivo_resultado',
    'reembolso',
    'respuestas_urocultivo',
    'confirmar_toma_created_at',
    'confirmar_toma_created_us',
    'migrado_lab',
    'tiempo'
  ];

  protected $casts = [
    'respuestas_urocultivo' => 'array'
  ];

  public function getByConsejeriaId($consejeria_id, $id_estado_reg = 1) {
    $procedimientos = DB::table('prestacion_procedimientos as pp')
    ->select([
        'pp.id as prestacion_procedimientos_id',
        'p.id as procedimiento_id',
        'pp.id as cod',
        'p.codigo',
        'p.descripcion as nombre',
        'pp.cantidad',
        'pp.tiempo',
        'pp.id_diagnostico'
    ])
    ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
    ->where('pp.id_consejeria', $consejeria_id)
    ->where('pp.id_evaluacion', 0)
    // ->where('pp.id_estado_reg', 1)
    ->where('pp.tipo_procedimiento', 1);
    // ->orderBy('pp.id')
    // ->get();
    if (!is_null($id_estado_reg)) {
      $procedimientos->where('pp.id_estado_reg', $id_estado_reg);
    }
    return $procedimientos->orderBy('pp.id')->get();
    // return $procedimientos;
  }


  public function getByArrayConsejeriaId($array_id_consejeria) {
    $procedimientos = DB::table('prestacion_procedimientos as pp')
                          ->select([
                              'p.id as id_procedimiento',
                              'pp.id as cod',
                              'p.codigo',
                              'p.descripcion as nombre',
                              'pp.cantidad',
                              'pp.id_diagnostico',
                              DB::raw("'-' as codigo_diagnostico")
                          ])
                          ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                          ->whereIn('pp.id_consejeria', $array_id_consejeria)
                          ->where('pp.id_evaluacion', 0)
                          ->where('pp.id_estado_reg', 1)
                          ->where('pp.tipo_procedimiento', 1)
                          ->orderBy('pp.id')
                          ->get();
    return $procedimientos;
  }
}
