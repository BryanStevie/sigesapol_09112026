<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class CqxHojaRecuperacion extends Model
{
  protected $table = "cqx_hoja_recuperaciones";  

  public function diagnosticos_pacientes_prestacion($id_prestacion){
    $data = DB::table('receta_diagnosticos as t1')
      ->select(
          't2.codigo',
          't1.id_diagnostico',
          DB::raw("CASE
              WHEN COALESCE(t1.id_tipo_diagnostico, 0) = 1 THEN 'PRESUNTIVO'
              WHEN COALESCE(t1.id_tipo_diagnostico, 0) = 2 THEN 'DEFINITIVO'
              WHEN COALESCE(t1.id_tipo_diagnostico, 0) = 3 THEN 'REITERATIVO'
              ELSE 'OTRO'
          END as tipo"),
          't1.id_categoria',
          DB::raw("CASE
              WHEN t2.codigo = 'Z21.X' OR t2.codigo ILIKE 'B20%' THEN ''
              ELSE t2.nombre
          END as nombre"),
          'td.descripcion',
          DB::raw("'Atencion' as clasificacion")
      )
      ->join('diagnosticos as t2', function ($join) {
          $join->on('t1.id_diagnostico', '=', 't2.id')
              ->where('t1.id_interconsulta', '=', 0);
      })
      ->join('tipo_diagnosticos as td', 't1.id_tipo_diagnostico', '=', 'td.id')
      ->where('t1.id_prestacion', $id_prestacion)
      ->where('t1.estado', 1)
      ->groupBy(
          't2.codigo',
          't1.id_diagnostico',
          't1.id_tipo_diagnostico',
          't1.id_categoria',
          't2.nombre',
          'td.descripcion'
      )
      ->orderBy('t1.id_diagnostico', 'desc')
      ->get();

      return $data;
  }

  public function procedimientos_intervencion($id_intervencion){
    $procedimientos = DB::table('cqx_procedimientos as cqxp')
    ->leftJoin('procedimientos as p', 'cqxp.id_procedimiento', '=', 'p.id')
    ->select('cqxp.id','p.codigo','p.descripcion')
    ->where('cqxp.id_estado_reg',1)
    ->where('cqxp.id_cqx_intervenciones',$id_intervencion)
    ->orderBy('cqxp.id') 
    ->get();
    return $procedimientos;
  }
  public function guardar($data,$iduser){
    //dd($data);
    $hoja = new CqxHojaRecuperacion();
    if ($data['tiene_tratamiento'] === 'false') {
      $data['descripcion_tratamiento'] = "";
    }
    if ($data['tiene_analisis'] === 'false') {
      $data['descripcion_analisis'] = "";
      $data['fecha_analisis'] = null;
      $data['cvc_analisis'] = "";
      $data['sf_analisis'] = "";
      $data['la_analisis'] = "";
      $data['lpp_analisis'] = "";
    }
    if ($data['tiene_diagnostico_enfermeria'] === 'false') {
      $data['campo1_diagnostico_enfermeria'] = false;
      $data['campo2_diagnostico_enfermeria'] = false;
      $data['campo3_diagnostico_enfermeria'] = false;
    }
    
    $hoja->id_intraoperatorio = isset($data['id_intraoperatorio'])?$data['id_intraoperatorio']:null;
    $hoja->id_intervencion = $data['id_intervencion'];
    $hoja->id_asegurado = $data['id_asegurado'];
    $hoja->tiene_analisis = filter_var($data['tiene_analisis'], FILTER_VALIDATE_BOOLEAN);
    $hoja->tiene_balance_hidrico = filter_var($data['tiene_balance_hidrico'], FILTER_VALIDATE_BOOLEAN);
    $hoja->tiene_tratamiento = filter_var($data['tiene_tratamiento'], FILTER_VALIDATE_BOOLEAN);
    $hoja->tiene_funciones_vitales = filter_var($data['tiene_funciones_vitales'], FILTER_VALIDATE_BOOLEAN);
    $hoja->tiene_diagnostico_enfermeria = filter_var($data['tiene_diagnostico_enfermeria'], FILTER_VALIDATE_BOOLEAN);
    $hoja->descripcion_tratamiento = $data['descripcion_tratamiento'];
    $hoja->descripcion_analisis = $data['descripcion_analisis'];
    $hoja->fecha_analisis = $data['fecha_analisis'];
    $hoja->cvc_analisis = $data['cvc_analisis'];
    $hoja->sp_analisis = $data['sf_analisis'];
    $hoja->la_analisis = $data['la_analisis'];
    $hoja->lpp_analisis = $data['lpp_analisis'];
    $hoja->campo1_diagnostico_enfermeria = $data['campo1_diagnostico_hidden'] == 1 ? true : false;
    $hoja->campo2_diagnostico_enfermeria = $data['campo2_diagnostico_hidden'] == 1 ? true : false;
    $hoja->campo3_diagnostico_enfermeria = $data['campo3_diagnostico_hidden'] == 1 ? true : false;
    $hoja->created_us = $iduser;
    $hoja->created_at = Carbon::now()->format('Y-m-d H:i:s');
    $hoja->cama = $data['cama'];
    $hoja->save();
    if (!empty($data['id_procedimiento_hr_form']) && is_array($data['id_procedimiento_hr_form'])) {
      foreach ($data['id_procedimiento_hr_form'] as $key => $value) {
        $p= [
          'id_hoja_recuperacion'=>$hoja->id,
          'id_prestacion'=>$data['id_prestacion'],
          'id_procedimiento'=>$value,
          'id_estado_reg'=>1,
          'cantidad'=>$data['procedimiento_cantidad'][$key],
          'quirurgico'=>1,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'created_us' => $iduser,
        ];
        DB::table('prestacion_procedimientos')->insert($p);
        $dp = [
          'id_hoja_recuperacion'=>$hoja->id,
          'id_prestacion' => $data['id_prestacion'],
          'id_procedimientos' => $value,
          'id_diagnosticos'=>$data['miSelect'][$key],
          'estado' => 1,
        ];          
        DB::table('procedimientos_diagnoticos')->insert($dp);
      }
    }

  return $hoja->id;

  }

  public function datos_paciente($id_intervencion){
    $result = DB::table('cqx_intervenciones as ci')
    ->select(
        DB::raw('a.nro_doc_ident::TEXT AS dni'),
        'a.nombre as asegurado_nombre',
        'a.paterno as asegurado_paterno',
        'a.materno as asegurado_materno',
        DB::raw('a.sexo::varchar AS sexo'),
        DB::raw('EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios'),
        DB::raw('EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses'),
        DB::raw('EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias'),
        DB::raw('a.fecha_nac::date AS asegurado_fecha_nacimiento'),
        'tb.descripcion as asegurado_tipo_beneficiario',
        'sa.nombre as sala',
        'ec.nombre as estado_civil',
        'dep.descripcion as departamento',
        'prov.descripcion as provincia',
        'dist.descripcion as distrito',
        'a.domicilio'
    )
    ->leftJoin('cq_salas as sa', 'ci.id_sala_intervencion', '=', 'sa.id')
    ->leftJoin('hospitalizaciones as h', 'ci.id_hospitalizacion', '=', 'h.id')
    ->leftJoin('prestaciones as p', 'p.id_ext', '=', 'h.id')
    ->join('asegurados as a', function($join) {
        $join->on('p.id_asegurado', '=', 'a.id')
             ->on('ci.id_asegurado', '=', 'a.id');
    })
    ->leftJoin('estado_civiles as ec', 'a.id_estado_civil', '=', 'ec.id')
    ->leftJoin('departamentos as dep', 'a.id_departamento', '=', 'dep.id_departamento')
    ->leftJoin('provincias as prov', 'a.id_provincia', '=', 'prov.id_provincia')
    ->leftJoin('distritos as dist', 'a.id_distrito', '=', 'dist.id_distrito')
    ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
    ->where('ci.id', $id_intervencion)
    ->first();
    return $result;
  }

  function funciones_vitales_by_hr($id_hoja_recuperacion){
    $vitales = DB::table('cqx_vitales as cv')
    ->join('caracter_fisicos as cf', 'cv.id_caracter_fisico', '=', 'cf.id')
    ->select(
        'cv.codigo_agrupacion', 
        'cv.valor_caracter_fisico', 
        'cv.hora_registro', 
        'cv.id_caracter_fisico', 
        'cf.descripcion_corta as descripcion', 
        'cv.id_hoja_recuperacion', 
        'cv.fecha_registro',
        'cv.secuencia'
    )
    ->where('cv.estado', 1)
    ->where('cv.id_hoja_recuperacion', $id_hoja_recuperacion)
    ->orderBy('cv.codigo_agrupacion')  // Ordenar por el campo padre
    ->orderBy('cv.secuencia')          // Ordenar por secuencia de los hijos
    ->get();  // Obtener los resultados como una colección

    // Agrupación manual por 'codigo_agrupacion'
    $resultado = [];

    foreach ($vitales as $registro) {
        $codigoAgrupacion = $registro->codigo_agrupacion;

        if (!isset($resultado[$codigoAgrupacion])) {
            // Si no existe en el array resultado, inicializa el padre con el código de agrupación
            $resultado[$codigoAgrupacion] = [
                'codigo_agrupacion' => $codigoAgrupacion,
                'registros' => []  // Lista de hijos (registros asociados)
            ];
        }

        // Agregar el registro actual a la lista de registros del padre correspondiente
        $resultado[$codigoAgrupacion]['registros'][] = [
            'valor_caracter_fisico' => $registro->valor_caracter_fisico,
            'hora_registro' => $registro->hora_registro,
            'descripcion' => $registro->descripcion,
            'fecha_registro' => $registro->fecha_registro,
            'secuencia' => $registro->secuencia
        ];
    }

    // Convertir el resultado a un array
    $resultadoArray = array_values($resultado);
    return $resultadoArray;
  
  }
  function datos_paciente_intervencion($id_intervencion){
    $resultado = CqxIntervencion::select([
        'a.paterno', 'a.materno', 'a.nombre',
        DB::raw("a.nombre || ' ' || a.paterno || ' ' || a.materno AS paciente"),
        DB::raw("to_char(age(TO_DATE(a.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"') as fec_nacim_descrip"),
        DB::raw("to_char(age(TO_DATE(a.fecha_nac , 'DD/MM/YYYY')), 'YY \"\"') as edad_corta"),
        'sa.nombre AS sala',
        'p.antecedente AS antecedente_personal',
        'p.antecedente_familiar',
        'hr.cama'
    ])
    ->leftJoin('cq_salas AS sa', 'cqx_intervenciones.id_sala_intervencion', '=', 'sa.id')
    //->join('hospitalizaciones AS h', 'cqx_intervenciones.id_hospitalizacion', '=', 'h.id')
    ->join('prestaciones AS p', 'p.id', '=', 'cqx_intervenciones.id_prestacion')
    ->join('asegurados AS a', 'p.id_asegurado', '=', 'a.id')
    ->leftJoin('cqx_hoja_recuperaciones as hr', 'cqx_intervenciones.id', '=', 'hr.id_intervencion')
    ->where('cqx_intervenciones.id', $id_intervencion)
    ->limit(1)
    ->first();
    return $resultado;
  }
  function diagnostico_operatorio($id_intervencion){
    $diagnosticos = DB::table('receta_diagnosticos as rd')
        ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
        ->join('tipo_diagnosticos as td', 'rd.id_tipo_diagnostico', '=', 'td.id')
        ->select('d.codigo', 'd.nombre','td.descripcion')
        ->where('rd.id_intervencion', $id_intervencion)
        ->get();

    return $diagnosticos;
  }
  function tipos_anestesia_aq($idIntervencion){
    $descripcionAnestesia = DB::table('anestesia_quirurgicas as aq')
    ->join('cqx_tipo_anestesia_detalles as ctad', 'aq.id', '=', 'ctad.id_anestesia')
    ->join('cqx_tipo_anestesia as cta', 'ctad.id_tipo_anestesia', '=', 'cta.id')
    ->select('cta.descripcion')
    ->where('aq.id_intervencion', $idIntervencion)
    ->get();
    return $descripcionAnestesia;
  }
  function profesionales_intraoperatorio($tipo,$idIntervencion){
    $cirujanos = DB::table('cqx_profesionales as pro')
        ->join('medicos as m', 'pro.id_medico', '=', 'm.id')
        ->join('cqx_intraoperatorios as ci', 'pro.id_intraoperatorio', '=', 'ci.id')
        ->join('cqx_tipo_professionales as tp', 'pro.id_tipo_professional', '=', 'tp.id')
        ->select(DB::raw("m.nombre || ' ' || m.paterno || ' ' || m.materno as medico"), 'tp.descripcion as tipo')
        ->where('pro.flag_personal', $tipo)
        ->where('ci.id_intervencion', $idIntervencion)
        ->get();
    return $cirujanos;
  }
  function profesionales($tipo,$idIntervencion){
    $profesional = DB::table('cqx_profesionales as pro')
        ->join('medicos as m', 'pro.id_medico', '=', 'm.id')
        ->join('cqx_tipo_professionales as tp', 'pro.id_tipo_professional', '=', 'tp.id')
        ->select(DB::raw("m.nombre || ' ' || m.paterno || ' ' || m.materno as medico"), 'tp.descripcion as tipo')
        ->where('pro.flag_personal', $tipo)
        ->where('pro.id_cqx_intervenciones', $idIntervencion)
        ->get();
    return $profesional;
  }

  public function procedimientos_hoja_recuperacion($id_hoja_recuperacion){
    $procedimientos = DB::table('prestacion_procedimientos as pp')
    ->leftJoin('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
    ->leftJoin('procedimientos_diagnoticos as pd', 'pp.id_procedimiento', '=', 'pd.id_procedimientos')
    ->leftJoin('diagnosticos as d', 'pd.id_diagnosticos', '=', 'd.id')
    ->select('p.id','pp.cantidad','p.codigo','p.descripcion','pd.id_diagnosticos')
    ->where('pp.id_estado_reg',1)
    ->where('pp.id_hoja_recuperacion',$id_hoja_recuperacion)
    ->where('pd.id_hoja_recuperacion',$id_hoja_recuperacion)
    ->orderBy('pp.id') 
    ->get();
    return $procedimientos;
  }

  public function actualizar($data,$iduser){
    $modelo_hoja = new CqxHojaRecuperacion();
    $hoja = $modelo_hoja::find($data['id_hoja_recuperacion']);

    if ($data['tiene_tratamiento'] === 'false') {
      $data['descripcion_tratamiento'] = "";
    }
    if ($data['tiene_analisis'] === 'false') {
      $data['descripcion_analisis'] = "";
      $data['fecha_analisis'] = null;
      $data['cvc_analisis'] = "";
      $data['sf_analisis'] = "";
      $data['la_analisis'] = "";
      $data['lpp_analisis'] = "";
    }
    if ($data['tiene_diagnostico_enfermeria'] === 'false') {
      $data['campo1_diagnostico_hidden'] = false;
      $data['campo2_diagnostico_hidden'] = false;
      $data['campo3_diagnostico_hidden'] = false;
    }
    
    $hoja->id_intraoperatorio = $data['id_intraoperatorio'];
    $hoja->id_intervencion = $data['id_intervencion'];
    $hoja->id_asegurado = $data['id_asegurado'];
    $hoja->tiene_analisis = filter_var($data['tiene_analisis'], FILTER_VALIDATE_BOOLEAN);
    $hoja->tiene_balance_hidrico = filter_var($data['tiene_balance_hidrico'], FILTER_VALIDATE_BOOLEAN);
    $hoja->tiene_tratamiento = filter_var($data['tiene_tratamiento'], FILTER_VALIDATE_BOOLEAN);
    $hoja->tiene_funciones_vitales = filter_var($data['tiene_funciones_vitales'], FILTER_VALIDATE_BOOLEAN);
    $hoja->tiene_diagnostico_enfermeria = filter_var($data['tiene_diagnostico_enfermeria'], FILTER_VALIDATE_BOOLEAN);
    $hoja->descripcion_analisis = $data['descripcion_analisis'];
    $hoja->descripcion_tratamiento = $data['descripcion_tratamiento'];
    $hoja->fecha_analisis = $data['fecha_analisis'];
    $hoja->cvc_analisis = $data['cvc_analisis'];
    $hoja->sp_analisis = $data['sf_analisis'];
    $hoja->la_analisis = $data['la_analisis'];
    $hoja->lpp_analisis = $data['lpp_analisis'];
    $hoja->campo1_diagnostico_enfermeria = $data['campo1_diagnostico_hidden'] == 1 ? true : false;
    $hoja->campo2_diagnostico_enfermeria = $data['campo2_diagnostico_hidden'] == 1 ? true : false;
    $hoja->campo3_diagnostico_enfermeria = $data['campo3_diagnostico_hidden'] == 1 ? true : false;
    $hoja->created_us = $iduser;
    $hoja->created_at = Carbon::now()->format('Y-m-d H:i:s');
    $hoja->cama = $data['cama'];
    $hoja->save();
    
    if (!empty($data['id_procedimiento']) && is_array($data['id_procedimiento'])) {
      foreach ($data['id_procedimiento'] as $key => $value) {
        if(isset($data['accionRowProcedimiento'][$key]) && $data['accionRowProcedimiento'][$key]=='edit'){
           DB::table('prestacion_procedimientos')
          ->where('id_procedimiento',$value)
          ->where('id_hoja_recuperacion', $data['id_hoja_recuperacion'])
          ->update([
              'cantidad' => $data['procedimiento_cantidad'][$key],
              'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
              'created_us' => $iduser,
          ]);
          DB::table('procedimientos_diagnoticos')
          ->where('id_hoja_recuperacion', $data['id_hoja_recuperacion'])
          ->where('id_procedimientos', $value)
          ->update([
              'id_diagnosticos'=>$data['miSelect'][$key],
          ]);
        }else if(isset($data['accionRowProcedimiento'][$key]) && $data['accionRowProcedimiento'][$key]=='delete'){
          DB::table('prestacion_procedimientos')
          ->where('id_procedimiento',$value)
          ->where('id_hoja_recuperacion', $data['id_hoja_recuperacion'])
          ->update([
              'id_estado_reg' => 0,
              'deleted_at' => Carbon::now()->format('Y-m-d H:i:s'),
              'deleted_us' => $iduser
          ]);
          DB::table('procedimientos_diagnoticos')
          ->where('id_hoja_recuperacion', $data['id_hoja_recuperacion'])
          ->where('id_diagnosticos', $data['id_diagnostico_hidden'][$key])
          ->where('id_procedimientos', $value)
          ->update([
              'estado' => 0
          ]);

        }else if(isset($data['accionRowProcedimiento'][$key]) && $data['accionRowProcedimiento'][$key]=='nuevo'){
          $p= [
            'id_hoja_recuperacion'=>$hoja->id,
            'id_prestacion'=>$data['id_prestacion'],
            'id_procedimiento'=>$value,
            'id_estado_reg'=>1,
            'cantidad'=>$data['procedimiento_cantidad'][$key],
            'quirurgico'=>1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'created_us' => $iduser,
          ];

          DB::table('prestacion_procedimientos')->insert($p);
          $dp = [
            'id_hoja_recuperacion'=>$hoja->id,
            'id_prestacion' => $data['id_prestacion'],
            'id_procedimientos' => $value,
            'id_diagnosticos'=>$data['miSelect'][$key],
            'estado' => 1,
          ];        
          DB::table('procedimientos_diagnoticos')->insert($dp);
        }
      }
    }

    return $hoja->id;

  }
  
}
