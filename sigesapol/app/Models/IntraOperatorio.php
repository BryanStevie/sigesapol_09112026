<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use App\Models\CqxProfesional;
use Illuminate\Support\Facades\Log;

class IntraOperatorio extends Model
{
  protected $table = 'cqx_intraoperatorios';

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
  public function medicosByEspecialidadConsultorio($id_especializacion, $nombre, $id_ipress)
  {
      $query = Medico::join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      // ->leftJoin('cqx_profesionales', 'cqx_profesionales.id_medico', '=', 'medicos.id') 
      ->leftJoin('profesiones', 'profesiones.id', '=', 'medicos.id_profesion') 
      // ->whereNull('cqx_profesionales.id_medico') 
      ->whereRaw("CONCAT(medicos.colegiatura, ' ', medicos.dni, ' ', medicos.nombre, ' ', medicos.paterno, ' ', medicos.materno) ILIKE ?", ['%' . $nombre . '%'])
      ->where('medicos.estado', 1)
      ->where('medicos.id_establecimiento', $id_ipress);

      if ($id_especializacion == 8) {
      $query->where('especializaciones.id', $id_especializacion);
      }
      if ($id_especializacion != 8) {
      $query->leftJoin('medico_consultorios as mc', 'medicos.id', '=', 'mc.id_medico')
            ->leftJoin('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
            ->where('c.flag_quirurgico', 1);
      }
      $medicos = $query->distinct()
      ->select('medicos.*', 'especializaciones.nombre as especializacion', 'profesiones.nombre as profesion_nombre')
      ->orderBy('medicos.paterno', 'asc')
      ->limit(50)
      ->get();

      return $medicos;
  }
  public function profesiones($tipo_personales,$id_intervencion){
    $medicos = DB::table('cqx_profesionales as p')
    ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
    ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
    ->select('p.id','p.estado','m.nombre', 'm.paterno', 'm.materno','tp.descripcion')
    ->where('p.flag_personal', $tipo_personales)
    // SI ES 0, SE CONSIDERARA ANULADO DESDE EL ANULAR INTRAOPERATORIO GENERAL
    ->where('p.estado', '!=', 4)
    ->where('p.id_cqx_intervenciones', $id_intervencion)
    ->orderBy('p.id') 
    ->get();
    return $medicos;
  }
  
  public function profesiones_activos($tipo_personales,$id_intervencion){
    $medicos = DB::table('cqx_profesionales as p')
    ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
    ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
    ->select('p.id','p.estado','m.nombre', 'm.paterno', 'm.materno','tp.descripcion')
    ->where('p.flag_personal', $tipo_personales)
    ->where('p.id_cqx_intervenciones', $id_intervencion)
    ->whereIn('p.estado', [0, 1]) 
    ->orderBy('p.id') 
    ->get();
    return $medicos;
  }

  public function cirujanosactivos($tipo_personales){
  $medicos = DB::table('cqx_profesionales as p')
  ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
  ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
  ->select('p.id', 'p.estado', 'm.nombre', 'm.paterno', 'm.materno', 'tp.descripcion')
  ->where('p.flag_personal', $tipo_personales)  // Filtrar por tipo de personal
  ->whereIn('p.estado', [0, 1])  // Filtrar solo los profesionales activos
  ->orderBy('p.id')
  ->get();
    return $medicos;
  }

  public function procedimientos_intervencion($id_intervencion){
    $procedimientos = DB::table('cqx_procedimientos as cqxp')
    ->leftJoin('procedimientos as p', 'cqxp.id_procedimiento', '=', 'p.id')
    ->select('cqxp.id','p.codigo','p.descripcion','cqxp.flag_pre_post')
    ->where('cqxp.id_estado_reg',1)
    ->where('cqxp.id_cqx_intervenciones',$id_intervencion)
    ->where('cqxp.flag_examen_patologia',false)
    ->orderBy('cqxp.id') 
    ->get();
    return $procedimientos;
  }
  public function procedimientos_intervencion_examen_pat($id_intervencion){
    $procedimientos = DB::table('cqx_procedimientos as cqxp')
    ->leftJoin('procedimientos as p', 'cqxp.id_procedimiento', '=', 'p.id')
    ->select('cqxp.id','p.codigo','p.descripcion')
    ->where('cqxp.id_estado_reg',1)
    ->where('cqxp.id_cqx_intervenciones',$id_intervencion)
    ->where('cqxp.flag_examen_patologia',true)
    ->orderBy('cqxp.id') 
    ->get();
    return $procedimientos;
  }
  // public function guardar($data,$iduser){
  //   $fechaRegistro = Carbon::now();
  //   $createdAt = Carbon::now();
  //   //INSERTAR INTRAOPERATORIO
  //   $intra = [
  //     'tipo_operacion' =>  $data['tipo_operacion'],
  //     'id_intervencion' => $data['id_intervencion'],
  //     'id_hospitalizacion' => !empty($data['id_hospitalizacion']) ? $data['id_hospitalizacion'] : null,
  //     'id_asegurado' => $data['id_asegurado'],
  //     'comentarios' => $data['comentarios'],
  //     'id_cqx_tipo_anestesia' => $data['tipo_anestesia'],
  //     'cod_siteds_susalud' => $data['cod_siteds_susalud'],
  //     'recepcion_paciente_descripcion' => $data['recepcion_paciente_descripcion'],
  //     'recepcion_paciente_tiempo' => $data['recepcion_paciente_tiempo'],
  //     'anestesia_inicio' => $data['anestesia_inicio'],
  //     'anestesia_termino' => $data['anestesia_termino'],
  //     'anestesia_tipo_id' => $data['tipo_anestesia'],
  //     'cirugia_inicio' => $data['cirugia_inicio'],
  //     'cirugia_termino' => $data['cirugia_termino'],
  //     'inicio_paciente' => !empty($data['inicio_paciente']) ? $data['inicio_paciente'] : null,
  //     'hora_llegada_urpa' => $data['hora_llegada_urpa'],
  //     'tipo_real' =>  $data['tipo_real'],
  //     'salida_paciente' => $data['salida_paciente'],
  //     'complicaciones' =>  $data['complicaciones'],
  //     'cajas_instrumental_descripcion' => $data['cajas_instrumental_descripcion'],
  //     'cajas_instrumental_equipo' => $data['cajas_instrumental_equipo'] == 1 ? 1 : 0,
  //     'cajas_instrumental_insumos' => $data['cajas_instrumental_insumos'] == 1 ? 1 : 0,
  //     'cajas_instrumental_instrumental' => $data['cajas_instrumental_instrumental'] == 1 ? 1 : 0,
  //     'enf1_enf2_vc_insumos' => $data['enf1_enf2_vc_insumos'] == 1 ? 1 : 0,
  //     'enf1_enf2_vc_instrumental' => $data['enf1_enf2_vc_instrumental'] == 1 ? 1 : 0,
  //     'maquina_anestesia_descripcion' => $data['maquina_anestesia_descripcion'],
  //     'oxigeno_descripcion' => $data['oxigeno_descripcion'],
  //     'flag_firma' => 0,
  //     'fecha_registro' => $fechaRegistro,
  //     'hora_registro' => $data['hora_registro'],
  //     'created_us' =>  $iduser,
  //     'created_at' => $createdAt,
  //     'estado' => 1,
  //   ];
  //   $idIntraOperatorio = DB::table('cqx_intraoperatorios')->insertGetId($intra);
  //   //FIN INSERTAR INTRAOPERATORIO
  //   //CIRUJANOS
    
  //   if (!empty($data['id_cirujano']) && is_array($data['id_cirujano'])) {
  //     foreach($data['id_cirujano'] as $key => $p) {
  //         $profesional = new CqxProfesional();
  //         $profesional->id_intraoperatorio = $idIntraOperatorio;
  //         $profesional->id_medico =  $p;
  //         $profesional->id_tipo_professional =(int)$data['tipo_profesional_cirujano'][$key];
  //         $profesional->flag_personal = (int)$data['flag_personales_cirujano'][$key];
  //         $profesional->flag_validacion = 0;
  //         $profesional->estado = 1;
  //         $profesional->created_us = $iduser;
  //         $profesional->created_at =  Carbon::now()->format('Y-m-d H:i:s');
  //         $profesional->save();
  //     }
  //   }
  //   //FIN CIRUJANOS
  //   //ANESTESIOLOGOS
  //   if (!empty($data['id_anestesiologo']) && is_array($data['id_anestesiologo'])) {
  //     foreach($data['id_anestesiologo'] as $key => $p) {
  //         $profesional = new CqxProfesional();
  //         $profesional->id_intraoperatorio = $idIntraOperatorio;
  //         $profesional->id_medico =  $p;
  //         $profesional->id_tipo_professional =(int)$data['tipo_profesional_anestesiologo'][$key];
  //         $profesional->flag_personal = (int)$data['flag_personales_anestesiologo'][$key];
  //         $profesional->flag_validacion = 0;
  //         $profesional->estado = 1;
  //         $profesional->created_us = $iduser;
  //         $profesional->created_at =  Carbon::now()->format('Y-m-d H:i:s');
  //         $profesional->save();
  //     }
  //   }
  //   //FIN ANESTESIOLOGOS
  //   //ENFERMERO INSTRUMENTISTA
  //   if (!empty($data['id_enfermeria_instrumentista']) && is_array($data['id_enfermeria_instrumentista'])) {
  //     foreach($data['id_enfermeria_instrumentista'] as $key => $p) {
  //         $profesional = new CqxProfesional();
  //         $profesional->id_intraoperatorio = $idIntraOperatorio;
  //         $profesional->id_medico =  $p;
  //         $profesional->id_tipo_professional =(int)$data['tipo_instrumentista'][$key];
  //         $profesional->flag_personal = (int)$data['flag_personales_enfermero'][$key];
  //         $profesional->flag_validacion = 0;
  //         $profesional->estado = 1;
  //         $profesional->created_us = $iduser;
  //         $profesional->created_at =  Carbon::now()->format('Y-m-d H:i:s');
  //         $profesional->save();
  //     }
  //   }
  //   //ENFERMERO INSTRUMENTISTA
  //   //PROCEDIMIENTOS DE ENFERMERIA
  //   if (!empty($data['id_procedimiento']) && is_array($data['id_procedimiento'])) {
  //     foreach ($data['id_procedimiento'] as $key => $value) {
  //       $p= [
  //         'id_intraoperatorio'=>$idIntraOperatorio,
  //         'id_prestacion'=>$data['id_prestacion'],
  //         'id_procedimiento'=>$value,
  //         'id_estado_reg'=>1,
  //         'cantidad'=>$data['procedimiento_cantidad'][$key],
  //         'quirurgico'=>1,
  //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
  //         'created_us' => $iduser,
  //       ];
  //       DB::table('prestacion_procedimientos')->insert($p);
  //       $dp = [
  //         'id_intraoperatorio'=>$idIntraOperatorio,
  //         'id_prestacion' => $data['id_prestacion'],
  //         'id_procedimientos' => $value,
  //         'id_diagnosticos'=>$data['miSelect'][$key],
  //         'estado' => 1,
  //       ];          
  //       DB::table('procedimientos_diagnoticos')->insert($dp);
  //     }
  //   }
  //   //FIN PROCEDIMIENTOS DE ENFERMERIA
  //   //DIASNOSTICOS DE ENFERMERIA
  //   if (!empty($data['diagnostico']) && is_array($data['diagnostico'])) {
  //   $diagnostico_estado = array_map('intval', $data['diagnostico_estado']);
  //     foreach($data['diagnostico'] as $key => $de) {
  //         $checked = in_array($de, $diagnostico_estado);
  //         $dnd = [
  //           'id_intraoperatorio' => $idIntraOperatorio, // Variable previamente capturada
  //           'id_diagnostico_nics' => $de,
  //           'estado' =>1,
  //           'correlativo' =>  $key,
  //           'flag' => 0,
  //           'parent' => null,
  //           'checked' => $checked,
  //           'created_us' => $iduser,
  //           'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
  //       ];
  //       DB::table('cqx_diagnostico_nic_detalle')->insertGetId($dnd);
  //     }
  //   }
  //   //INTERVENCION (NIC) DE ENFERMERIA
  //   if (!empty($data['nics_padre']) && is_array($data['nics_padre'])) {
  //     foreach ($data['nics_padre'] as $key => $padre) {
  //         $nics_padre = [
  //           'id_intraoperatorio' => $idIntraOperatorio, // Variable previamente capturada
  //           'id_diagnostico_nics' =>$padre,
  //           'estado' =>1,
  //           'correlativo' =>  null,
  //           'flag' => 1,
  //           'parent' => null,
  //           'checked' => null,
  //           'created_us' => $iduser,
  //           'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
  //       ];
  //       DB::table('cqx_diagnostico_nic_detalle')->insertGetId($nics_padre);
  //       if (isset($data['nics'][$padre])) {
  //           foreach ($data['nics'][$padre] as $key => $hijo) {
  //               $checked = in_array($hijo, $data['nic_estado'][$padre]);
  //               $nics_hijo = [
  //                 'id_intraoperatorio' => $idIntraOperatorio,
  //                 'id_diagnostico_nics' =>$hijo,
  //                 'estado' =>1,
  //                 'correlativo' =>  $key,
  //                 'flag' => 1,
  //                 'parent' => $padre,
  //                 'checked' => $checked ,
  //                 'created_us' => $iduser,
  //                 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
  //             ];
  //             DB::table('cqx_diagnostico_nic_detalle')->insertGetId($nics_hijo);
  //           }
  //       }
  //     }
  //   }
  //   //BALANCE DE FLUIDOS
  //   if (!empty($data['balance_ingreso']) && is_array($data['balance_ingreso'])) {
  //     foreach ($data['balance_ingreso'] as $key => $value) {
  //       $bf = [
  //         'id_intraoperatorio' => $idIntraOperatorio,
  //         'correlativo'=>$key,
  //         'ingreso_fluido'=>$value,
  //         'egreso_fluido'=>$data['balance_egreso'][$key],
  //         'terapeutica_fluido'=>$data['tareapeutica'][$key],
  //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
  //         'created_us' => $iduser,
  //     ];
  //       DB::table('cqx_balance_fluidos')->insertGetId($bf);
  //     }
  //   }
  //   //FIN BALANCE DE FLUIDOS
  //   //RECUENTO DE GASAS
  //   if (!empty($data['enfermeriauno']) && is_array($data['enfermeriauno'])) {
  //     foreach ($data['enfermeriauno'] as $key => $value) {
  //       $cg = [
  //           'id_intraoperatorio' => $idIntraOperatorio,
  //           'tipo_gasa'=>$data['tipo_gasa'][$key],
  //           'enfermeria1'=>(int)$value,
  //           'enfermeria2'=>(int)$data['enfermeriados'][$key],
  //           'enfermeria3'=>(int)$data['enfermeriatres'][$key],
  //           'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
  //           'created_us' => $iduser,
  //       ];
  //       DB::table('cqx_control_gasas')->insertGetId($cg);
  //     }
  //   }
  //   return $idIntraOperatorio;
  // }
  public function guardar($data,$iduser){
    //CIRUJANOS
    $lista_cirujano = []; // Inicializar como arreglo
    if (!empty($data['id_cirujano']) && is_array($data['id_cirujano'])) {
      foreach($data['id_cirujano'] as $key => $p) {
          $lista_cirujano[] = [ // Añadir como nuevo elemento en el arreglo
              'id_medico' => $p,
              'id_tipo_professional' => (int)$data['tipo_profesional_cirujano'][$key],
              'flag_personal' =>  (int)$data['flag_personales_cirujano'][$key],
              'flag_validacion' => 0,
              'estado' => 1,
              'created_us' => $iduser,
              'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ];
      }
    }
    $cirujanos_json = json_encode($lista_cirujano ?: []); 
    //FIN CIRUJANOS

    //ANESTESIOLOGOS
    $lista_anestesiologo = []; // Inicializar como arreglo
    if (!empty($data['id_anestesiologo']) && is_array($data['id_anestesiologo'])) {
      foreach($data['id_anestesiologo'] as $key => $p) {
          $lista_anestesiologo[] = [ // Añadir como nuevo elemento en el arreglo
              'id_medico' => $p,
              'id_tipo_professional' => (int)$data['tipo_profesional_anestesiologo'][$key],
              'flag_personal' =>  (int)$data['flag_personales_anestesiologo'][$key],
              'flag_validacion' => 0,
              'estado' => 1,
              'created_us' => $iduser,
              'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ];
      }
    }
    $anestesiologos_json = json_encode($lista_anestesiologo ?: []); 
    //FIN ANESTESIOLOGOS

    //ENFERMERO INSTRUMENTISTA
    $lista_instrumentista = []; // Inicializar como arreglo
    if (!empty($data['id_enfermeria_instrumentista']) && is_array($data['id_enfermeria_instrumentista'])) {
      foreach($data['id_enfermeria_instrumentista'] as $key => $p) {
          $lista_instrumentista[] = [ // Añadir como nuevo elemento en el arreglo
              'id_medico' => $p,
              'id_tipo_professional' => (int)$data['tipo_instrumentista'][$key],
              'flag_personal' =>  (int)$data['flag_personales_enfermero'][$key],
              'flag_validacion' => 0,
              'estado' => 1,
              'created_us' => $iduser,
              'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ];
      }
    }
    $instrumentista_json = json_encode($lista_instrumentista); 
    //ENFERMERO INSTRUMENTISTA

    //PROCEDIMIENTOS DE ENFERMERIA
    $lista_procededimientos_enf = [];
    if (!empty($data['id_procedimiento']) && is_array($data['id_procedimiento'])) {
      foreach ($data['id_procedimiento'] as $key => $value) {
        $lista_procededimientos_enf[] = [
          'id_prestacion'=>$data['id_prestacion'],
          'id_procedimiento'=>$value,
          'procedimiento_cantidad'=>$data['procedimiento_cantidad'][$key],
          'procedimiento_diagnostico'=>$data['miSelect'][$key],
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'created_us' => $iduser,
        ];
      }
    }
     $procedimientos_json = json_encode($lista_procededimientos_enf); 
     //FIN PROCEDIMIENTOS DE ENFERMERIA

    //DIASNOSTICOS DE ENFERMERIA
    $lista_diagnosticos_enfermeria = []; 
    if (!empty($data['diagnostico_estado']) && is_array($data['diagnostico'])) {
      $diagnostico_estado = array_map('intval', $data['diagnostico_estado']);

      foreach($data['diagnostico'] as $key => $de) {
          $checked = in_array($de, $diagnostico_estado);
          $lista_diagnosticos_enfermeria[] = [
              'id_diagnostico_nics' => (int)$de,
              'estado' => 1,
              'correlativo' =>(int) $key,
              'flag' => 0,
              'created_us' => (int)$iduser,
              'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
              'parent' => null,
              'checked' => $checked,
          ];
      }
    }
    $diagnosticos_enfermeria_json = json_encode($lista_diagnosticos_enfermeria, JSON_UNESCAPED_SLASHES);
    //INTERVENCION (NIC) DE ENFERMERIA
    $lista_nic_enfermeria = [];
    foreach ($data['nics_padre'] as $key => $padre) {
        $lista_nic_enfermeria[] = [
            'id_diagnostico_nics' => $padre,  
            'estado' => 1, 
            'correlativo' => null,  
            'flag' => 1,  
            'created_us' => $iduser,  
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  // Fecha de creación
            'parent' => null,  
            'checked' => null  
        ];
        if (isset($data['nics'][$padre])) {
            foreach ($data['nics'][$padre] as $key => $hijo) {
                // $checked = in_array($hijo, $data['nic_estado'][$padre]);
                $checked = in_array($hijo, (array) (isset($data['nic_estado'][$padre]) ? $data['nic_estado'][$padre] : []));
                $lista_nic_enfermeria[] = [
                    'id_diagnostico_nics' => $hijo,  
                    'estado' => 1,  
                    'correlativo' => $key, 
                    'flag' => 1, 
                    'created_us' => $iduser,  
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  
                    'parent' => $padre,  
                    'checked' => $checked 
                ];
            }
        }
    }
    $intervencion_enfermeria_json = json_encode($lista_nic_enfermeria); 
  
    //BALANCE DE FLUIDOS
    $lista_balancefluidos_enf = [];
    if (!empty($data['balance_ingreso']) && is_array($data['balance_ingreso'])) {
      foreach ($data['balance_ingreso'] as $key => $value) {
        $lista_balancefluidos_enf[] = [
          'correlativo'=>$key,
          'ingreso_fluido'=>$value,
          'egreso_fluido'=>$data['balance_egreso'][$key],
          'terapeutica_fluido'=>$data['tareapeutica'][$key],
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'created_us' => $iduser,
        ];
      }
    }
     $balance_fluidos_json = json_encode($lista_balancefluidos_enf); 
     //FIN BALANCE DE FLUIDOS

    //RECUENTO DE GASAS
    $lista_recuento_gasas_enf = []; 
    if (!empty($data['enfermeriauno']) && is_array($data['enfermeriauno'])) {
      foreach ($data['enfermeriauno'] as $key => $value) {
        $lista_recuento_gasas_enf[] = [
          'tipo_gasa'=>$data['tipo_gasa'][$key],
          'enfermeria1'=>(int)$value,
          'enfermeria2'=>(int)$data['enfermeriados'][$key],
          'enfermeria3'=>(int)$data['enfermeriatres'][$key],
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'created_us' => $iduser,
        ];
      }
    }
     $recuento_gasas_json = json_encode($lista_recuento_gasas_enf); 
     //RECUENTO DE GASAS
    $fechaRegistro = Carbon::now();
    $createdAt = Carbon::now();

    $cad = "Select sp_insertar_intraoperatorio(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    // $data['inicio_paciente']="";
    $resultado = DB::select($cad, 
                array(
                  /* !empty($data['hora_registro']) ? $data['hora_registro'] : null, */
                  $data['hora_registro'],
                  $data['tipo_operacion'],
                  $data['id_intervencion'],
                  !empty($data['id_hospitalizacion']) ? $data['id_hospitalizacion'] : null,
                  $data['id_asegurado'],
                  $data['comentarios'],
                  $data['tipo_anestesia'],
                  !empty($data['cod_siteds_susalud'])?$data['cod_siteds_susalud']:null,
                  $data['recepcion_paciente_descripcion'],
                  !empty($data['recepcion_paciente_tiempo']) ? $data['recepcion_paciente_tiempo'] : null,
                  !empty($data['anestesia_inicio']) ? $data['anestesia_inicio'] : null,
                  !empty($data['anestesia_termino']) ? $data['anestesia_termino'] : null,
                  $data['tipo_anestesia'],
                  !empty($data['cirugia_inicio']) ? $data['cirugia_inicio'] : null,
                  !empty($data['cirugia_termino']) ? $data['cirugia_termino'] : null,
                  !empty($data['inicio_paciente']) ? $data['inicio_paciente'] : null,
                  !empty($data['hora_llegada_urpa']) ? $data['hora_llegada_urpa'] : null,
                  !empty($data['tipo_real']) ? $data['tipo_real'] : null,
                  !empty($data['salida_paciente']) ? $data['salida_paciente'] : null,
                  $data['complicaciones'],
                  $data['cajas_instrumental_descripcion'],
                  $data['cajas_instrumental_equipo']== 1 ? 1 : 0,
                  $data['cajas_instrumental_insumos']== 1 ? 1 : 0,
                  $data['cajas_instrumental_instrumental']== 1 ? 1 : 0,
                  $data['enf1_enf2_vc_insumos']== 1 ? 1 : 0,
                  $data['enf1_enf2_vc_instrumental']== 1 ? 1 : 0,
                  $data['maquina_anestesia_descripcion'],
                  $data['oxigeno_descripcion'],
                  0,
                  /* $fechaRegistro, */
                  $data['fecha_registro'] . " " . $data['hora_registro'],
                  $iduser,
                  $createdAt,
                  $cirujanos_json,
                  $anestesiologos_json,
                  $instrumentista_json,
                  $procedimientos_json,
                  $diagnosticos_enfermeria_json,
                  $intervencion_enfermeria_json,
                  $balance_fluidos_json,
                  $recuento_gasas_json,
                  !empty($data['operacion_programada']) ? $data['operacion_programada'] : null,
                  !empty($data['operacion_emergencia']) ? $data['operacion_emergencia'] : null,
                  !empty($data['operacion_realizada']) ? $data['operacion_realizada'] : null
                )); 
    return $resultado;

  }
  public function actualizar($data_intra, $id_intervencion) {
    $cad = "SELECT sp_actualizar_intraoperatorio(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Ejecutar el procedimiento almacenado con los parámetros necesarios
    $resultado = DB::select($cad, [
        $id_intervencion,                  // p_id_intervencion
        $data_intra['anestesia_inicio'],   // p_anestesia_inicio
        $data_intra['anestesia_termino'],  // p_anestesia_termino
        !empty($data_intra['cirugia_inicio'])?$data_intra['cirugia_inicio']:null,     // p_cirugia_inicio
        !empty($data_intra['cirugia_termino'])?$data_intra['cirugia_termino']:null,    // p_cirugia_termino
        null,    // p_inicio_paciente (nullable)
        $data_intra['hora_llegada_urpa'],  // p_hora_llegada_urpa
        !empty($data_intra['tipo_real'])?$data_intra['tipo_real']:null,          // p_tipo_real
        $data_intra['salida_paciente'],    // p_salida_paciente (nullable)
        $data_intra['complicaciones'],     // p_complicaciones (nullable)
        $data_intra['id_cqx_tipo_anestesia'], // p_id_cqx_tipo_anestesia
        Auth::id(),                        // p_updated_us
        Carbon::now()->format('Y-m-d H:i:s') // p_updated_at
    ]);

    return $resultado;
  }
  public function getUltimaAtencionXAtencion($id_hospitalizacion, $id_tipo_atencion)
  {
      $result = DB::select("select * from sp_get_ultima_atencion_x_atencion(?, ?)", array($id_hospitalizacion, $id_tipo_atencion));
      if (!empty($result)) {
          return $result[0];
      }
      return null;
  }

  public function getUltimaAtencionXAtencion2($id_emergencia, $id_tipo_atencion)
  {
      $result = DB::select("select * from sp_get_ultima_atencion_x_atencion(?, ?)", array($id_emergencia, $id_tipo_atencion));
      if (!empty($result)) {
          return $result[0];
      }
      return null;
  }

  public function diagnosticos_enfermeria($id_intraoperatorio){
    $resultados = DB::select("
    SELECT cdnd.id, dn.descripcion,cdnd.checked  
    FROM cqx_diagnostico_nic_detalle cdnd  
    LEFT JOIN cqx_diagnostico_nics as dn 
    ON cdnd.id_diagnostico_nics = dn.id  
    WHERE cdnd.flag = ? AND cdnd.estado = ? AND id_intraoperatorio = ?
    ", [0, 1,$id_intraoperatorio]);
    return $resultados;
  }
  public function intervencion_enfermeria($id_intraoperatorio){
      $nicsDetalle = DB::select("
      SELECT 
          d.id AS id_padre,
          d.id_intraoperatorio,
          d.id_diagnostico_nics,
          d.estado,
          d.correlativo,
          d.flag,
          n.descripcion, 
          COALESCE(
              ARRAY_AGG(
                  DISTINCT 
                  CASE 
                      WHEN dh.id IS NOT NULL THEN dh.id || '|' || nh.descripcion || '|' || COALESCE(dh.checked::text, 'false')
                  END
              ), 
              ARRAY[]::text[]
          ) AS hijos
      FROM 
          cqx_diagnostico_nic_detalle d
      LEFT JOIN 
          cqx_diagnostico_nics n ON n.id = d.id_diagnostico_nics
      LEFT JOIN 
          cqx_diagnostico_nic_detalle dh 
              ON dh.parent = d.id_diagnostico_nics 
              AND dh.flag = 1 
              AND dh.id_intraoperatorio = d.id_intraoperatorio
      LEFT JOIN 
          cqx_diagnostico_nics nh ON nh.id = dh.id_diagnostico_nics
      WHERE 
          d.parent IS NULL
          AND d.flag = 1
          AND d.id_intraoperatorio = '".$id_intraoperatorio."'
      GROUP BY 
          d.id, d.id_intraoperatorio, d.id_diagnostico_nics, d.estado, d.correlativo, d.flag, n.descripcion
      ORDER BY 
          d.id
    ");
    foreach ($nicsDetalle as $detalle) {
      $hijos = [];
      
      // Verificar que haya hijos
      if (!empty($detalle->hijos)) {
          // Convertir la cadena a array
          //$hijosArray = explode(',', trim($detalle->hijos, '{}'));
          $hijosArray = str_getcsv(trim($detalle->hijos, '{}'), ',', '"');
  
          foreach ($hijosArray as $hijo) {
              $hijoData = explode('|', $hijo);
              
              if (count($hijoData) === 3) {
                $checkedValue = trim($hijoData[2], '"'); 
                $checked = filter_var($checkedValue, FILTER_VALIDATE_BOOLEAN);
                  $hijos[] = [
                      'id' => trim($hijoData[0]), // ID del hijo
                      'descripcion' => trim($hijoData[1]), // Descripción del hijo
                      'checked' =>$checked
                  ];
              }
          }
      }
      
      $detalle->hijos = $hijos; // Asignar hijos procesados al objeto
    }
    return $nicsDetalle;
  } 
  public function datos_para_firma($id_intraoperatorio){
    $intraoperatorio = IntraOperatorio::leftJoin('cqx_tipo_anestesia', 'cqx_intraoperatorios.anestesia_tipo_id', '=', 'cqx_tipo_anestesia.id')
        ->leftJoin('cqx_intervenciones', 'cqx_intraoperatorios.id_intervencion', '=', 'cqx_intervenciones.id')
        ->leftJoin('cq_salas', 'cq_salas.id', '=', 'cqx_intervenciones.id_sala_intervencion')
        ->select(
          'cqx_intraoperatorios.*', 
          'cqx_tipo_anestesia.descripcion as tipo_anestesia',
          'cqx_intervenciones.id_sala_intervencion', 
          'cq_salas.id as id_sala_intervencion', 
          'cq_salas.nombre as sala_intervencion',
          DB::raw("TO_CHAR(cqx_intervenciones.fecha_probable, 'DD-MM-YYYY') as fecha_probable")
          )
        ->where('cqx_intraoperatorios.id', $id_intraoperatorio)
        ->first(); // Utiliza first() para obtener un solo resultado
    return $intraoperatorio;
  }

  public function datos_paciente($id_intervencion){
    $result = DB::table('cqx_intervenciones as ci')
    ->select(
        DB::raw('a.nro_doc_ident::TEXT AS dni'),
        'a.tipo_doc_ident AS tipo_documento',
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
    ->join('asegurados as a', 'ci.id_asegurado', '=', 'a.id')
    ->leftJoin('estado_civiles as ec', 'a.id_estado_civil', '=', 'ec.id')
    ->leftJoin('departamentos as dep', 'a.id_departamento', '=', 'dep.id_departamento')
    ->leftJoin('provincias as prov', 'a.id_provincia', '=', 'prov.id_provincia')
    ->leftJoin('distritos as dist', 'a.id_distrito', '=', 'dist.id_distrito')
    ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
    ->where('ci.id', $id_intervencion)
    ->first();
    return $result;
  }

  public function balance_fluidos_enfermeria($id_intraoperatorio){
    $balanceFluidos = DB::table('cqx_balance_fluidos')
                    ->where('id_intraoperatorio', $id_intraoperatorio)
                    ->select('id', 'ingreso_fluido', 'egreso_fluido', 'terapeutica_fluido')
                    ->get();

    return $balanceFluidos;
  }
  public function recuento_gasas_enfermeria($id_intraoperatorio){
    $controlGasas = DB::table('cqx_control_gasas')
                    ->select('enfermeria1', 'enfermeria2', DB::raw('(enfermeria1 + enfermeria2) as total'))
                    ->where('id_intraoperatorio', $id_intraoperatorio)
                    ->get();
    return $controlGasas;    
  }
  public function profesiones_intrumentista($tipo_personales,$id_intraoperatorio){
    $medicos = DB::table('cqx_profesionales as p')
    ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
    ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
    ->select('p.id','p.estado','m.nombre', 'm.paterno', 'm.materno','tp.descripcion')
    ->where('p.flag_personal', $tipo_personales)
    ->where('p.id_intraoperatorio', $id_intraoperatorio)
    ->orderBy('p.id') 
    ->get();
    return $medicos;
  }
  
  public function profesiones_by_id_io($tipo_personales,$id_intraoperatorio){
    $medicos = DB::table('cqx_profesionales as p')
    ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
    ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
    ->select(DB::raw("STRING_AGG(CONCAT(m.nombre, ' ', m.paterno, ' ', m.materno), ', ') as medicos_nombres"))
    ->where('p.flag_personal', $tipo_personales)
    ->where('p.id_intraoperatorio', $id_intraoperatorio)
    ->groupBy('p.id_intraoperatorio') // Asegura que se agrupe por el campo adecuado
    ->first();
    return $medicos;
  }

  public function profesiones_by_id_io_v2($tipo_personales, $id_intervencion)
  {
    $medicos = DB::table('cqx_profesionales as p')
      ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
      ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
      ->select(DB::raw("STRING_AGG(CONCAT(m.nombre, ' ', m.paterno, ' ', m.materno), ', ') as medicos_nombres"))
      ->where('p.flag_personal', $tipo_personales)
      ->where('p.id_cqx_intervenciones', $id_intervencion)
      ->groupBy('p.id_intraoperatorio') // Asegura que se agrupe por el campo adecuado
      ->first();
    return $medicos;
  }

  public function datos_form($id_intervencion)
  {
    $result = DB::table('cqx_intervenciones as ci')
      ->select(
        'ci.id as id_intervencion',
        'c.nombre as servicio',
        'sa.nombre as sala',
        'ci.fecha_registro',
        'ci.hora_registro',
        'pre.codigo_upss',
        'u.descripcion_upss as nombre_upss',
        'e.nombre as establecimiento',
        'e.codigo as codigo_ipress',
        DB::raw('a.nro_doc_ident::TEXT AS dni'),
        'a.id as id_asegurado',
        'a.tipo_doc_ident as tipo_documento',
        'a.paterno as asegurado_paterno',
        'a.materno as asegurado_materno',
        'a.nombre as asegurado_nombre',
        'tp.descripcion as parentesco',
        DB::raw('a.fecha_nac::date AS asegurado_fecha_nacimiento'),
        'a.cip',
        'a.grado',
        'si.descripcion as situacion',
        DB::raw('a.sexo::varchar AS sexo'),
        DB::raw('EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios'),
        DB::raw('EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses'),
        DB::raw('EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias'),
        'ts.descripcion_corta as tipo_sangre',
        'dep.descripcion as departamento',
        'prov.descripcion as provincia',
        'dist.descripcion as distrito',
        'a.domicilio',
        'ci.id_evolucion',
        'ci.id_prestacion'
      )
      ->join('asegurados as a', 'ci.id_asegurado', '=', 'a.id')
      ->join('consultorios as c', 'ci.id_consultorio', '=', 'c.id')
      ->join('prestaciones as pre', 'ci.id_prestacion', '=', 'pre.id')
      ->join('upsses as u', 'pre.codigo_upss', '=', 'u.codigo')
      ->join('establecimientos as e', 'pre.id_establecimiento', '=', 'e.id')
      ->leftJoin('cq_salas as sa', 'ci.id_sala_intervencion', '=', 'sa.id')
      ->leftJoin('departamentos as dep', 'a.id_departamento', '=', 'dep.id_departamento')
      ->leftJoin('provincias as prov', 'a.id_provincia', '=', 'prov.id_provincia')
      ->leftJoin('distritos as dist', 'a.id_distrito', '=', 'dist.id_distrito')
      ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
      ->leftJoin('tipo_documento_identidad as td', 'a.id_tipo_doc_ident', '=', 'td.id')
      ->leftJoin('situaciones as si', 'a.id_situacion', '=', 'si.id')
      ->leftJoin('tipo_parentescos as tp', 'a.id_tipo_parentesco', '=', 'tp.id')
      ->leftJoin('tipo_sangres as ts', 'a.id_tipo_sangre', '=', 'ts.id')
      ->where('ci.id', $id_intervencion)
      ->first();
    return $result;
  }

  public function procedimientos_intervencion_intraope($id_intraoperatorio)
  {
    return DB::table('prestacion_procedimientos as pp')
      ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
      ->leftJoin('procedimientos_diagnoticos as pd', function ($join) {
        $join->on('pd.id_procedimientos', '=', 'pp.id_procedimiento')
          ->on('pd.id_intraoperatorio', '=', 'pp.id_intraoperatorio');
      })
      ->leftJoin('diagnosticos as d', 'pd.id_diagnosticos', '=', 'd.id')
      ->select(
        'pp.*',
        'p.codigo as codigo_procedimiento',
        'p.descripcion as descripcion_procedimiento',
        'd.*'
      )
      ->where('pp.id_estado_reg', 1)
      ->where('pp.id_intraoperatorio', $id_intraoperatorio)
      ->orderBy('pp.id', 'asc')
      ->get();
  }

  public function actualizacionGeneral($data, $iduser)
  {
    //CIRUJANOS
    $lista_cirujano = []; // Inicializar como arreglo
    if (!empty($data['id_cirujano']) && is_array($data['id_cirujano'])) {
      foreach ($data['id_cirujano'] as $key => $p) {
        $lista_cirujano[] = [ // Añadir como nuevo elemento en el arreglo
          'id_medico' => $p,
          'id_tipo_professional' => (int)$data['tipo_profesional_cirujano'][$key],
          'flag_personal' =>  (int)$data['flag_personales_cirujano'][$key],
          'flag_validacion' => 0,
          'estado' => 1,
          'created_us' => $iduser,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
      }
    }
    $cirujanos_json = json_encode($lista_cirujano ?: []);
    //FIN CIRUJANOS

    //ANESTESIOLOGOS
    $lista_anestesiologo = []; // Inicializar como arreglo
    if (!empty($data['id_anestesiologo']) && is_array($data['id_anestesiologo'])) {
      foreach ($data['id_anestesiologo'] as $key => $p) {
        $lista_anestesiologo[] = [ // Añadir como nuevo elemento en el arreglo
          'id_medico' => $p,
          'id_tipo_professional' => (int)$data['tipo_profesional_anestesiologo'][$key],
          'flag_personal' =>  (int)$data['flag_personales_anestesiologo'][$key],
          'flag_validacion' => 0,
          'estado' => 1,
          'created_us' => $iduser,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
      }
    }
    $anestesiologos_json = json_encode($lista_anestesiologo ?: []);
    //FIN ANESTESIOLOGOS

    //ENFERMERO INSTRUMENTISTA
    $lista_instrumentista = []; // Inicializar como arreglo
    if (!empty($data['id_enfermeria_instrumentista']) && is_array($data['id_enfermeria_instrumentista'])) {
      foreach ($data['id_enfermeria_instrumentista'] as $key => $p) {
        $lista_instrumentista[] = [ // Añadir como nuevo elemento en el arreglo
          'id_medico' => $p,
          'id_tipo_professional' => (int)$data['tipo_instrumentista'][$key],
          'flag_personal' =>  (int)$data['flag_personales_enfermero'][$key],
          'flag_validacion' => 0,
          'estado' => 1,
          'created_us' => $iduser,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
      }
    }
    $instrumentista_json = json_encode($lista_instrumentista);
    //ENFERMERO INSTRUMENTISTA

    //PROCEDIMIENTOS DE ENFERMERIA
    $lista_procededimientos_enf = [];
    if (!empty($data['id_procedimiento']) && is_array($data['id_procedimiento'])) {
      foreach ($data['id_procedimiento'] as $key => $value) {
        $lista_procededimientos_enf[] = [
          'id_prestacion' => $data['id_prestacion'],
          'id_procedimiento' => $value,
          'procedimiento_cantidad' => $data['procedimiento_cantidad'][$key],
          'procedimiento_diagnostico' => $data['miSelect'][$key],
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'created_us' => $iduser,
          'es_nuevo' => (data_get($data, "id_procedimiento_existente.$key") == 0) ? 1 : 0 //1=NUEVO 0=EXISTENTE
        ];
      }
    }
    $procedimientos_json = json_encode($lista_procededimientos_enf);
    //FIN PROCEDIMIENTOS DE ENFERMERIA

    //DIASNOSTICOS DE ENFERMERIA
    $lista_diagnosticos_enfermeria = [];
    /* Log::info($data['diagnostico']);
    Log::info($data['diagnostico_estado']);
    throw new \Exception("adsadasad"); */
    /* if (!empty($data['diagnostico_estado']) && is_array($data['diagnostico'])) {
      $diagnostico_estado = array_map('intval', $data['diagnostico_estado']);

      foreach ($data['diagnostico'] as $key => $de) {
        $checked = in_array($de, $diagnostico_estado);
        $lista_diagnosticos_enfermeria[] = [
          'id_diagnostico_nics' => (int)$de,
          'estado' => 1,
          'correlativo' => (int) $key,
          'flag' => 0,
          'created_us' => (int)$iduser,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'parent' => null,
          'checked' => $checked,
        ];
      }
    } */
    if (!empty($data['diagnostico']) && is_array($data['diagnostico'])) {

      $diagnostico_estado = isset($data['diagnostico_estado'])
        ? array_map('intval', $data['diagnostico_estado'])
        : array();

      foreach ($data['diagnostico'] as $key => $de) {

        $lista_diagnosticos_enfermeria[] = [
          'id_diagnostico_nics' => (int) $de,
          'estado'             => 1,
          'correlativo'        => (int) $key,
          'flag'               => 0,
          'created_us'         => (int) $iduser,
          'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
          'parent'             => null,
          'checked'            => in_array((int)$de, $diagnostico_estado, true),
        ];
      }
    }
    $diagnosticos_enfermeria_json = json_encode($lista_diagnosticos_enfermeria, JSON_UNESCAPED_SLASHES);

    //INTERVENCION (NIC) DE ENFERMERIA
    $lista_nic_enfermeria = [];
    foreach ($data['nics_padre'] as $key => $padre) {
      $lista_nic_enfermeria[] = [
        'id_diagnostico_nics' => $padre,
        'estado' => 1,
        'correlativo' => null,
        'flag' => 1,
        'created_us' => $iduser,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  // Fecha de creación
        'parent' => null,
        'checked' => null
      ];
      if (isset($data['nics'][$padre])) {
        foreach ($data['nics'][$padre] as $key => $hijo) {
          // $checked = in_array($hijo, $data['nic_estado'][$padre]);
          $checked = in_array($hijo, (array) (isset($data['nic_estado'][$padre]) ? $data['nic_estado'][$padre] : []));
          $lista_nic_enfermeria[] = [
            'id_diagnostico_nics' => $hijo,
            'estado' => 1,
            'correlativo' => $key,
            'flag' => 1,
            'created_us' => $iduser,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'parent' => $padre,
            'checked' => $checked
          ];
        }
      }
    }
    $intervencion_enfermeria_json = json_encode($lista_nic_enfermeria);

    //BALANCE DE FLUIDOS
    $lista_balancefluidos_enf = [];
    if (!empty($data['balance_ingreso']) && is_array($data['balance_ingreso'])) {
      foreach ($data['balance_ingreso'] as $key => $value) {
        $lista_balancefluidos_enf[] = [
          'correlativo' => $key,
          'ingreso_fluido' => $value,
          'egreso_fluido' => $data['balance_egreso'][$key],
          'terapeutica_fluido' => $data['tareapeutica'][$key],
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'id_balance_fluido' => !empty($data['id_balance_fluido'][$key]) ? $data['id_balance_fluido'][$key] : 0,
          'es_nuevo' => (data_get($data, "id_balance_fluido_existente.$key") == 1) ? 1 : 0
        ];
      }
    }
    $balance_fluidos_json = json_encode($lista_balancefluidos_enf);
    //FIN BALANCE DE FLUIDOS

    //RECUENTO DE GASAS
    $lista_recuento_gasas_enf = [];
    if (!empty($data['enfermeriauno']) && is_array($data['enfermeriauno'])) {
      foreach ($data['enfermeriauno'] as $key => $value) {
        $lista_recuento_gasas_enf[] = [
          'tipo_gasa' => $data['tipo_gasa'][$key],
          'enfermeria1' => (int)$value,
          'enfermeria2' => (int)$data['enfermeriados'][$key],
          'enfermeria3' => (int)$data['enfermeriatres'][$key],
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'created_us' => $iduser,
          'id_gasa' => !empty($data['id_gasa'][$key]) ? $data['id_gasa'][$key] : 0
        ];
      }
    }
    $recuento_gasas_json = json_encode($lista_recuento_gasas_enf);
    //RECUENTO DE GASAS

    $fechaRegistro = Carbon::now();
    $createdAt = Carbon::now();

    $cad = "Select sp_actualizar_general_intraoperatorio(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $resultado = DB::select(
      $cad,
      array(
        /* !empty($data['hora_registro']) ? $data['hora_registro'] : null, */
        $data['hora_registro'],
        $data['tipo_operacion'],
        $data['id_intervencion'],
        !empty($data['id_hospitalizacion']) ? $data['id_hospitalizacion'] : null,
        $data['id_asegurado'],
        $data['comentarios'],
        $data['tipo_anestesia'],
        !empty($data['cod_siteds_susalud']) ? $data['cod_siteds_susalud'] : null,
        $data['recepcion_paciente_descripcion'],
        !empty($data['recepcion_paciente_tiempo']) ? $data['recepcion_paciente_tiempo'] : null,
        !empty($data['anestesia_inicio']) ? $data['anestesia_inicio'] : null,
        !empty($data['anestesia_termino']) ? $data['anestesia_termino'] : null,
        $data['tipo_anestesia'],
        !empty($data['cirugia_inicio']) ? $data['cirugia_inicio'] : null,
        !empty($data['cirugia_termino']) ? $data['cirugia_termino'] : null,
        !empty($data['inicio_paciente']) ? $data['inicio_paciente'] : null,
        !empty($data['hora_llegada_urpa']) ? $data['hora_llegada_urpa'] : null,
        !empty($data['tipo_real']) ? $data['tipo_real'] : null,
        !empty($data['salida_paciente']) ? $data['salida_paciente'] : null,
        $data['complicaciones'],
        $data['cajas_instrumental_descripcion'],
        $data['cajas_instrumental_equipo'] == 1 ? 1 : 0,
        $data['cajas_instrumental_insumos'] == 1 ? 1 : 0,
        $data['cajas_instrumental_instrumental'] == 1 ? 1 : 0,
        $data['enf1_enf2_vc_insumos'] == 1 ? 1 : 0,
        $data['enf1_enf2_vc_instrumental'] == 1 ? 1 : 0,
        $data['maquina_anestesia_descripcion'],
        $data['oxigeno_descripcion'],
        0,
        /* $fechaRegistro, */
        $data['fecha_registro'] . " " . $data['hora_registro'],
        $iduser,
        $createdAt,
        $cirujanos_json,
        $anestesiologos_json,
        $instrumentista_json,
        $procedimientos_json,
        $diagnosticos_enfermeria_json,
        $intervencion_enfermeria_json,
        $balance_fluidos_json,
        $recuento_gasas_json,
        !empty($data['operacion_programada']) ? $data['operacion_programada'] : null,
        !empty($data['operacion_emergencia']) ? $data['operacion_emergencia'] : null,
        !empty($data['operacion_realizada']) ? $data['operacion_realizada'] : null,
        !empty($data['id_intraoperatorio']) ? $data['id_intraoperatorio'] : null
      )
    );
    return $resultado;
  }
}
