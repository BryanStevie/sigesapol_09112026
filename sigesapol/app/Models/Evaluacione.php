<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;

class Evaluacione extends Model
{
  public function GetEvaluacionesByPacienteEmergencia($id_paciente, $estado) {
      
    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,t2.sexo,tb.descripcion parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,to_char(t4.created_at , 'YYYY-MM-DD') fecha_evaluacion,to_char(t4.created_at , 'HH:MI AM') hora,
t2.nro_doc_ident,t1.fecha_ingreso dia,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso,t10.descripcion nom_prioridad,t11.descripcion consultorio
      FROM emergencias t1
      inner join evaluaciones t4 on t4.id_emergencia = t1.id 
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t4.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_beneficiarios tb on t1.id_tipo_beneficiario=tb.id
      inner join triajes t11 on t4.ubica_topico=t11.id
      inner join prioridades t10 on t4.ubica_prioridad=t10.id
            
      Where t4.id_estado_reg=1 and t1.estado=". $estado ." and t1.id=".$id_paciente." order by t4.id desc"; 
          $data = DB::select($cad);
          return $data;
  }

  public function GetEvaluacionesByPacienteEmergencia2($id_asegurado) {
      
    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,t2.sexo,tb.descripcion parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,to_char(t4.created_at , 'YYYY-MM-DD') fecha_evaluacion,to_char(t4.created_at , 'HH:MI AM') hora,
      t2.nro_doc_ident,t1.fecha_ingreso dia,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso,t10.descripcion nom_prioridad,t11.descripcion consultorio
      FROM emergencias t1
      inner join evaluaciones t4 on t4.id_emergencia = t1.id 
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t4.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_beneficiarios tb on t1.id_tipo_beneficiario=tb.id
      inner join triajes t11 on t4.ubica_topico=t11.id
      inner join prioridades t10 on t4.ubica_prioridad=t10.id
      Where t4.id_estado_reg=1 and t2.id=".$id_asegurado;
          $data = DB::select($cad);
          return $data;
  }

  public function GetEvaluacionesEmergenciaByNroDocIdent($nro_doc_ident) {
    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,t2.sexo,tb.descripcion parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,to_char(t4.created_at , 'YYYY-MM-DD') fecha_evaluacion,to_char(t4.created_at , 'HH:MI AM') hora,
      t2.nro_doc_ident,t1.fecha_ingreso dia,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso,t10.descripcion nom_prioridad,t11.descripcion consultorio
      FROM emergencias t1
      inner join evaluaciones t4 on t4.id_emergencia = t1.id 
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t4.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_beneficiarios tb on t1.id_tipo_beneficiario=tb.id
      inner join triajes t11 on t4.ubica_topico=t11.id
      inner join prioridades t10 on t4.ubica_prioridad=t10.id
      Where t4.id_estado_reg=1 and t2.nro_doc_ident = '". $nro_doc_ident . "' and t2.id_estado_reg = 1";
          $data = DB::select($cad);
          return $data;
  }

  public function GetEvaluacionesByAsegurado2($id_emergencia, $estado) {
      
      if($id > 0){

              $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,sexo,tb.descripcion parentesco,grado,fecha_nac,tipo_doc_ident,
      t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.id_establecimiento,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion servicio,t4.descripcion consultorio,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno
      ,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion
      FROM emergencias t1
      inner join evaluaciones t4 on t4.id_paciente = t1.id 
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t4.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_beneficiarios tb on t1.id_tipo_beneficiario=tb.id
      Where t1.estado=". $estado ." and t4.id=".$id."  and t1.id=".$id_emergencia; 

          $data = DB::select($cad);
          return $data[0];
      }
  }

  public function GetEvaluacion($id_evaluacion) {
      
    $cad = "select t1.*, to_char(t1.created_at::date,'DD/MM/YYYY') AS fecha_evaluacion, to_char(t1.created_at, 'HH:MI:SS AM')  AS hora_evaluacion,
    m.nombre medico_nombre,
    m.materno medico_materno,
    m.paterno medico_paterno,
    m.dni medico_dni,
    p2.nombre medico_profecion
    FROM evaluaciones t1    
    left join medicos as m on t1.id_medico = m.id
    left join profesiones p2 on m.id_profesion = p2.id 
    Where t1.id=". $id_evaluacion." and t1.id_estado_reg = 1 order by t1.id desc"; 
    $data = DB::select($cad);
    return $data[0];
      
  }


  public function GetObtenerServicioEvaluacion($id, $id_tipo_atencion=2) {
        
        if($id > 0){
          $cad = "";
          if ($id_tipo_atencion == 2) {
                $cad = "select t10.descripcion servicio,t4.descripcion consultorio, t1.ubica_prioridad, t1.ubica_topico, t1.cama_estado cama, t1.fecha_inicio_estado, t1.hora_estado 
                        FROM evaluaciones t1
                        inner join triajes t4 on t1.ubica_topico=t4.id
                        inner join prioridades t10 on t1.ubica_prioridad=t10.id
                        Where t1.id=".$id; 

          } elseif ($id_tipo_atencion == 3) {
            $cad = "select p2.nombre as pabellon,c3.nombre as servicio_sala,c2.nombre as cuarto,c.nombre as cama,e.fecha_inicio_estado,e.hora_estado
                    FROM evaluaciones e
                    inner join camas c on e.cama_estado = c.id
                    inner join cuartos c2 on c.id_cuarto = c2.id
                    inner join asignacion_servicios as2 on c2.id_asignacion_servicio = as2.id
                    inner join consultorios c3 on as2.id_consultorio = c3.id
                    inner join pisos p on as2.id_piso = p.id
                    inner join pabellones p2 on p.id_pabellon = p2.id
                    Where e.id=".$id; 
          }
            $data = DB::select($cad);
            return $data[0];
        }
    }

  
  public function registrarEvaluacion($id_emergencia,$id_prestacion,$id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico ,$id_user, $examen_fisico,$temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo,$antecedente,$antecedente_familiar,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama, $p_diagnostico, $valor) { //32
                                
    $cad = "Select sp_insert_evaluacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_emergencia,$id_prestacion,$id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico ,$id_user, $examen_fisico,$temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo,$antecedente,$antecedente_familiar,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama,$p_diagnostico, $valor));

    return $data[0]->sp_insert_evaluacion;

  }

  /**
   * Copia de registrarEvaluacion, pero con fecha atencion timestamp
   */
  public function registrarEvaluacion_v2($id_emergencia,$id_prestacion,$id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico ,$id_user, $examen_fisico,$temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo,$antecedente,$antecedente_familiar,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama, $p_diagnostico, $valor) { //32
                                
    $cad = "Select sp_insert_evaluacion_v2(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_emergencia,$id_prestacion,$id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico ,$id_user, $examen_fisico,$temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo,$antecedente,$antecedente_familiar,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama,$p_diagnostico, $valor));

    return $data[0]->sp_insert_evaluacion_v2;

  }

  public function GetActualizaEvaluacion($id_evaluacion) {
    DB::table('evaluaciones')
            ->where('id', '=', $id_evaluacion)
            ->update([
              'fecha_firma' => date("d-m-Y H:i:s"),
              'ruta_evaluacion' => date("Y/m/d")."/cf_evaluacion_".$id_evaluacion.".pdf",
              'flag' => 2,
        ]);
  }

  public function GetEvaluationById($id)
  {

    $cad = "select t1.*,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,t2.sexo,'' parentesco,'' grado,fecha_nac,tipo_doc_ident,t3.nombre establecimiento,
      t2.nro_doc_ident,t1.fecha_atencion dia,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.nombre tipo_atencion, '' servicio
      FROM evaluaciones t1
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado 
      inner join emergencias t6 on t1.id_emergencia=t6.id
      inner join establecimientos t3 on t6.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t1.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_atenciones t4 on t1.id_tipo_atencion=t4.id
      Where t1.id=" . $id;

      $data = DB::select($cad);
      return $data[0];
  }
  public function GetDocumentosPendientesFirmarEvaluacionId($id)
  {
    $query_string = "with tbl as (
        select id, id id_prestacion,fecha_firma, flag, 'evaluaciones' tipo 
        from evaluaciones e where id = {$id}
        ) select * from tbl
        where flag = 0";
    return DB::select($query_string);
  }


  public function listar_evaluacion_emergencia($p){
    return $this->readFunctionPostgres('sp_listar_evaluaciones_emergencias',$p);
  }

  public function reporte_evaluacion_emergencia($p){
    return $this->readFunctionPostgres('sp_reporte_evaluaciones_emergencias',$p);
  }
  public function listar_evaluacion_ajax($p){
    return $this->readFunctionPostgres('sp_evaluacion_paginado',$p);
  }
  public function listar_evaluacion_by_prestacion_ajax($p){
    return $this->readFunctionPostgres('sp_evaluacion_by_prestacion_paginado',$p);
  }


  public function readFunctionPostgres($function, $parameters = null){
  
      $_parameters = '';
      if (count($parameters) > 0) {
          $_parameters = implode("','", $parameters);
          $_parameters = "'" . $_parameters . "',";
      }
    $data = DB::select("BEGIN;");
    $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";
    $data = DB::select($cad);
    $cad = "FETCH ALL IN ref_cursor;";
    $data = DB::select($cad);
      return $data;
   }  

  public function GetDiagnosticoByIdEvaluacion($id)
  {
    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo,t1.id_categoria,
            (case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico=t2.id
            left join receta_vales rv on rv.id = t1.id_receta_vale
            where t1.id_interconsulta=0 and  t1.id_evaluacion=" . $id . "
            and t1.estado=1
            and rv.flag_origen is null";
    $data = DB::select($cad);
    return $data;
  }
   public function GetProcedimientosByIdEvaluacion($id, $tipo)
  {
      $query = "SELECT t1.id as cod, t2.codigo, t2.descripcion nombre, t1.cantidad, t3.id_diagnosticos as id_diagnostico
    FROM prestacion_procedimientos t1
    INNER JOIN procedimientos t2 ON t1.id_procedimiento = t2.id
    INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion --AND t3.id_procedimientos = t1.id_procedimiento
    WHERE  t3.id_evaluacion = " . $id . " AND t1.tipo_procedimiento = " . $tipo . "
    AND t1.id_estado_reg = 1 AND t3.estado = 1  AND t3.id_evaluacion = 0
    order by t1.id asc
    ";
  
    $data = DB::select($query);
    return $data;
  }

  public function GetEvaluacionByIdEmergencia($id)
  {

    $cad = "select t1.*, t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,
    dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, to_char(t1.created_at::date,'DD/MM/YYYY') AS fecha_evaluacion, 
    to_char(t1.created_at, 'HH:MI:SS AM')  AS hora_evaluacion
      FROM evaluaciones t1
      left join medicos t5 on t5.id= t1.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      Where t1.id=" . $id;
      $data = DB::select($cad);
      return $data[0];
  }

  public function GetEvaluacioneById($id_evolucion){
    $datos = DB::table('evaluaciones')->where('id', $id_evolucion)->first();
    return $datos;
  }

  public function GetEvaluacionesByIdExt($id_ext)
  {

    $cad = "select *
            from evaluaciones t1
            where t1.id_emergencia=" . $id_ext . "
            and t1.id_estado_reg=1";
    $data = DB::select($cad);
    return $data;
  }

  /*public function GetEvaluacionesByPacienteHospitalizacion($id_paciente, $estado) {
      
    $cad = "select t4.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,
      t2.nro_doc_ident,t1.fecha_ingreso dia,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso, t4.hora_ingreso hora_ingreso
      FROM hospitalizaciones t1
      inner join evaluacions t4 on t4.id_paciente = t1.id 
      inner join asegurados t2 on t4.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t2.id = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t4.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      Where t1.estado=". $estado ." and t1.id=".$id_paciente; 

          $data = DB::select($cad);
          return $data;
  }

  public function RegistrarEvaluacionEnfermeria($id_prestacion,$id_paciente , $id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico , $id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $hora_ingreso , $id_servicio , $sintomas, $p_procedimiento, $p_diagnostico) {
                                
    $cad = "Select sp_insert_evaluacion_enfermeria(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_prestacion,$id_paciente , $id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico , $id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $hora_ingreso , $id_servicio , $sintomas, $p_procedimiento, $p_diagnostico));

    return $data[0]->sp_insert_evaluacion_enfermeria;

  }

  public function GetEvaluacionesByPacienteEnfermeria($id_paciente, $id_prestacion) {
      
    $cad = "select t2.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,tb.descripcion parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,
      t2.nro_doc_ident,t1.fecha_ingreso dia,to_char(t1.created_at , 'HH:MI AM') hora,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso, t4.hora_ingreso hora_ingreso
      FROM hospitalizaciones t1
      inner join evaluacion_enfermerias t4 on t4.id_paciente = t1.id 
      inner join prestaciones t6 on t6.id = t4.id_prestacion 
      inner join asegurados t2 on t1.dni_beneficiario = t2.nro_doc_ident 
      inner join asegurado_historias t9 on t2.id = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t4.id_enfermera
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_beneficiarios tb on t2.id_tipo_beneficiario=tb.id
      Where  t4.id=".$id_prestacion." and  t1.id=".$id_paciente; 
          $data = DB::select($cad);
          return $data;
  }

  public function GetEvaluacionEnfermeria($id_evaluacion) {
      
    $cad = "select t1.* FROM evaluacion_enfermerias t1 Where t1.id=". $id_evaluacion." and t1.id_estado_reg = 1"; 
    $data = DB::select($cad);
    return $data[0];
      
  }

  public function listar_evaluacion_ajax_enfermeria($p){
    return $this->readFunctionPostgres('sp_evaluacion_enfermeria_paginado',$p);
  }

  public function GetEvolutionEnfermeriaById($id)
  {

    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,sexo,'' parentesco,'' grado,fecha_nac,tipo_doc_ident,
      t2.nro_doc_ident,t1.fecha_atencion dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t1.id_estado_reg, t1.id, t1.fecha_atencion , t1.hora_ingreso hora_ingreso, t4.nombre tipo_atencion, '' servicio, 
      t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, 
                    t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.sintomas
      FROM evaluacion_enfermerias t1
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t1.id_enfermera
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_atenciones t4 on t1.id_tipo_atencion=t4.id
      Where t1.id=" . $id;

      $data = DB::select($cad);
      return $data[0];
  }
*/
  public function GetEstadoFirmaEvaluacion($id)
  {
    $query_string = "select id, fecha_firma, ruta_evaluacion, id_tipo_atencion from evaluaciones where id = '{$id}'";
    return DB::select($query_string);
  }

  public static function RegistrarProcedimientoEvaluacion($id_evaluacion,$id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evolucion)
  {
    $cad = "Select sp_insert_procedimiento_evaluacion(?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evolucion));
    
    return $data[0]->sp_insert_procedimiento;
  }

  public function GetAtencionesByIdExt($id_ext, $id_tipo_atencion)
  {
    $cad = "select 
            t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
            t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t6.nombre servicio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg,
            t10.descripcion nom_prioridad,t11.descripcion consultorio
            , em.cod_siteds_susalud ,em.updated_at_cod_siteds ,em.updated_us_cod_siteds ,em.id_asegurado ,t6.id id_consultorio,em.id_establecimiento, em.id id_emergencia,
                CASE
                    WHEN CURRENT_TIMESTAMP - updated_at_cod_siteds < INTERVAL '1 day' THEN 1
                    ELSE 0
                END AS is_valid_cod_siteds
            FROM prestaciones t1
            inner join emergencias em on t1.id_ext = em.id 
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join establecimientos t3 on t1.id_establecimiento=t3.id
            inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
            inner join consultorios t6 on t4.id_consultorio=t6.id
            inner join medicos t5 on t1.id_medico=t5.id
            inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
            inner join triajes t11 on em.id_triaje=t11.id
            inner join prioridades t10 on em.id_prioridad=t10.id
            WHERE t1.id_ext=" . $id_ext . " and t1.id_tipo_atencion=" . $id_tipo_atencion . "
            And t1.id_estado_reg = 1
            ORDER BY t1.created_at desc";
    $data = DB::select($cad);
    return $data;
  }
  public function GetAtencionesByEvaluacionesHospi($id_ext)
  {
    $cad = "select 
            t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
            t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t6.nombre servicio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg,
            ''::varchar as nom_prioridad, ''::varchar as consultorio
            , em.cod_siteds_susalud ,em.updated_at_cod_siteds ,em.updated_us_cod_siteds ,em.id_asegurado ,t6.id id_consultorio,em.id_establecimiento, em.id id_emergencia,
            CASE
                WHEN CURRENT_TIMESTAMP - updated_at_cod_siteds < INTERVAL '1 day' THEN 1
                ELSE 0
            END AS is_valid_cod_siteds
            FROM prestaciones t1
            inner join hospitalizaciones em on t1.id_ext = em.id 
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join establecimientos t3 on t1.id_establecimiento=t3.id
            inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
            inner join consultorios t6 on t4.id_consultorio=t6.id
            inner join medicos t5 on t1.id_medico=t5.id
            inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
            WHERE t1.id_ext=" . $id_ext . " and t1.id_tipo_atencion=3
            And t1.id_estado_reg = 1
            order by t1.created_at ASC LIMIT 1";
    $data = DB::select($cad);
    return $data;
  }
  public function validacionBeneficiarioDiasPorExamenRua_emergencia_evaluaciones($nrodoctitular, $id_examen_lab)
    {   
        // $cad = "Select sp_consulta_beneficiario_dias_por_examen_rua(?,?,?)";
        // $data = DB::select($cad, array($dni_beneficiario, $id_examen_lab, $id_servicio));
        // return $data[0]->sp_consulta_beneficiario_dias_por_examen_rua;

        $cad = "select pp.id_rua, 
        l.codigo_lab, 
        a.nombre as nombre_beneficiario, 
        a.paterno as paterno_beneficiario, 
        a.materno as materno_beneficiario, 
        a.id as dni,l.descripcion_corta, 
        p2.descripcion, 
        TO_DATE(l2.fecha_firma, '"."DD-MM-YYYY"."') as fecha_expedicion, 
        DATE_PART('"."day"."', NOW() - TO_TIMESTAMP(l2.fecha_firma, '"."DD-MM-YYYY HH24:MI:SS"."')) AS dias_transcurridos,
        l2.secuencial as lab_secuencia, COUNT(*) OVER() as total_registros
        from prestacion_procedimientos pp 
        inner join prestaciones p on p.id = pp.id_prestacion 
        inner join asegurados a on a.id = p.id_asegurado 
        inner join labnivelatenciones l on l.id = pp.id_rua
        inner join laboratorios l2 on l2.id_prestacion = p.id 
        inner join procedimientos p2 on p2.id = l.id_procedimiento 
        inner join citas c on p.id_cita = c.id and c.id_estado_cita = 4
        WHERE pp.created_at >= (NOW() - INTERVAL '3 months') AND pp.created_at <= NOW()
        ";
        $cad .= " and a.nro_doc_ident = '".$nrodoctitular."' and l2.id_evaluacion IS NOT null";
        $cad .= " and l.id = ".$id_examen_lab;

        $data = DB::select($cad);
        return $data; 
    }
    public function listar_evaluacion_diagnosticos($p){
      return $this->readFunctionPostgres('sp_evaluacion_diagnosticos_paginado',$p);
    }
    public function listar_evaluacion_procedimientos($p){
      return $this->readFunctionPostgres('sp_evaluacion_procedimientos_paginado',$p);
    }

  public function GetEvaluacionesOrdenar($id_ext, $orden)
  {

    $cad = "select *
            from evaluaciones t1
            where t1.id_emergencia=" . $id_ext . "
            and t1.id_estado_reg=1 order by t1.id " . $orden . "";
    $data = DB::select($cad);
    return $data[0];
  }

  public function listar_evolucion_hospitalizacion($p){
    return $this->readFunctionPostgres('sp_listar_evoluciones_hospitalizacion',$p);
  }

  public function GetBuscaHospitalizacionEvaluacion($id, $id_medico, $estado) {
    $data = null;
    
    if($id > 0){

      $cad = "select 
            t1.estado,
            t1.id_asegurado,
            t2.nombre nombre_titular,
            t2.nombre asegurado_nombre,
            t2.paterno paterno_titular,
            t2.paterno asegurado_paterno,
            t2.materno materno_titular,
            t2.materno asegurado_materno,
            t9.nro_historia,
            t2.sexo,
            t1.tipo_beneficiario parentesco,
            fecha_nac,
            tipo_doc_ident,
            t2.tipo_doc_ident tipo_doc_ident_titular,
            t2.nro_doc_ident,
            t2.nro_doc_ident nro_doc_ident_titular,
            t1.fecha_ingreso dia,
            t1.hora_ingreso hora,
            t1.id_establecimiento,
            t3.nombre establecimiento,
            --t4.id_servicio,
            --t4.id_sub_consultorio id_sub_consultorio,
            --t10.descripcion nom_prioridad,
            --t4.descripcion consultorio,
            t5.id id_medico,
            t5.nombre medico_nombre,
            t5.paterno medico_paterno,
            t5.materno medico_materno,
            date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente,
            t2.grado,
            t2.id_asegurado_titular,
            t2.cip,
            t2.grado,
            t5.dni nro_dni_medico,
            t5.colegiatura colegiatura_medico,
            t5.rne rne_medico,
            t7.nombre especializacion_medico,
            t8.nombre profesion_medico,
            t1.created_at,
            t1.secuencia,
            c.nombre servicio,
            t1.id,
            t1.id_consultorio,
            t1.cod_siteds_susalud,
            t1.updated_us_cod_siteds,
            t1.updated_at_cod_siteds
          FROM hospitalizaciones t1
            left join asegurados t2 on t1.id_asegurado = t2.id 
            left join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
            left join establecimientos t3 on t1.id_establecimiento=t3.id
            left join medicos t5 on t5.id=".$id_medico." 
            left join especializaciones t7 on t5.id_especializacion=t7.id
            left join profesiones t8 on t5.id_profesion=t8.id
            inner join consultorios c on t1.id_consultorio = c.id
          Where t1.estado=". $estado ." and t1.id=".$id;

      $data = DB::select($cad);

      if(count($data)>0):
        return $data[0];
      else:
        return $data;
      endif;
    }
  }

  public function GetBuscaPacienteHospitalizacionEvaluacion($id)
  {
    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,t2.fecha_nac,t2.tipo_doc_ident,t1.id_cama_estado cama,t2.grado,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento
        FROM hospitalizaciones t1
        inner join asegurados t2 on t1.id_asegurado = t2.id 
        inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        Where t1.id=".$id; 

        $data = DB::select($cad);
    return $data[0];
    
  }

  public function GetEvaluacionById($id)
  {
    // $cad = "select t1.*, t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t5.dni,t5.colegiatura,t5.rne,t7.nombre especializacion,t8.nombre profesion, to_char(t1.created_at::date,'DD/MM/YYYY') AS fecha_evaluacion, to_char(t1.created_at, 'HH:MI:SS AM')  AS hora_evaluacion
    //   FROM evaluaciones t1
    //   left join medicos t5 on t5.id= t1.id_medico
    //   left join especializaciones t7 on t5.id_especializacion=t7.id
    //   left join profesiones t8 on t5.id_profesion=t8.id
    //   Where t1.id=" . $id;

    $cad = "select t1.*, t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t5.dni,t5.colegiatura,t5.rne,t7.nombre especializacion,t8.nombre profesion, to_char(t1.created_at::date,'DD/MM/YYYY') AS fecha_evaluacion, to_char(t1.created_at, 'HH:MI:SS AM')  AS hora_evaluacion,
t9.id as id_puerperio, t9.fecha_parto as puerperio_fecha_parto ,t10.id as puerperio_id_tipo_parto, t10.nombre as puerperio_tipo_parto, t9.complicaciones as puerperio_complicaciones, t9.descripcion as puerperio_descripcion, t1.fecha_atencion
      FROM evaluaciones t1
      left join medicos t5 on t5.id= t1.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join puerperios t9 on t1.id_puerperio = t9.id
      left join tipo_partos t10 on t10.id = t9.id_tipo_parto 
      Where t1.id=" . $id;

      $data = DB::select($cad);
      return $data[0];
  }

  public function listar_evolucion_hospi_ajax($p){
    return $this->readFunctionPostgres('sp_evolucion_hospi_paginado',$p);
  }

  public function GetEvolucionesByPacienteHospitalizacion($id_hospitalizacion, $estado) {

    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t1.nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,to_char(t4.fecha_atencion , 'YYYY-MM-DD') fecha_evaluacion,to_char(t4.fecha_atencion , 'HH:MI AM') hora,
          t2.nro_doc_ident,t1.fecha_ingreso dia,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso/*,t10.descripcion nom_prioridad,t11.descripcion consultorio*/
          FROM hospitalizaciones t1
          inner join evaluaciones t4 on t4.id_emergencia = t1.id and t4.id_tipo_atencion = 3
          inner join asegurados t2 on t1.id_asegurado = t2.id 
          inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
          inner join establecimientos t3 on t1.id_establecimiento=t3.id
          left join medicos t5 on t5.id= t4.id_medico
          left join especializaciones t7 on t5.id_especializacion=t7.id
          left join profesiones t8 on t5.id_profesion=t8.id
          --left join tipo_beneficiarios tb on t1.id_tipo_beneficiario=tb.id
          --inner join triajes t11 on t4.ubica_topico=t11.id
          --inner join prioridades t10 on t4.ubica_prioridad=t10.id
                
          Where t4.id_estado_reg=1 and t1.estado=". $estado ." and t1.id=".$id_hospitalizacion." order by t4.id desc"; 
              $data = DB::select($cad);
              return $data;

  }

  public function GetEvolucionesByPacienteHospitalizacion2($id_asegurado) {

    $cad = "select t13.id,t13.id_estado_reg,t13.id_tipo_atencion,
            t5.paterno as medico_paterno, t5.materno as medico_materno, t5.nombre as medico_nombre,
            to_char(t13.fecha_atencion,'DD/MM/YYYY') AS fecha_evaluacion, to_char(t13.fecha_atencion, 'HH24:MI AM') AS hora,
            c3.nombre as consultorio, 
            c.nombre as cama, servicio_encargado.nombre as servicio_encargado_atencion
            FROM evaluaciones t13
            inner join prestaciones t1 on t13.id_prestacion = t1.id
            inner join medicos t5 on t13.id_medico=t5.id
            inner join cambio_camas cc on t1.id = cc.id_prestacion and t13.id = cc.id_evolucion
            inner join camas c on cc.id_cama_atencion = c.id
            inner join cuartos c2 on c.id_cuarto = c2.id
            inner join asignacion_servicios as2 on c2.id_asignacion_servicio = as2.id
            inner join consultorios c3 on as2.id_consultorio = c3.id
            left join consultorios as servicio_encargado on cc.id_servicio_encargado_atencion = servicio_encargado.id
            Where t13.id_tipo_atencion = 3 and t13.id_estado_reg = 1 and t1.id_estado_reg = 1 and t13.id_asegurado =".$id_asegurado." order by t13.id desc"; 
              $data = DB::select($cad);
              return $data;

  }

    public function GetEvolucionesHospitalizacionByNroDocIden($nro_doc_ident) {

    $cad = "select t13.id,t13.id_estado_reg,t13.id_tipo_atencion,
            t5.paterno as medico_paterno, t5.materno as medico_materno, t5.nombre as medico_nombre,
            to_char(t13.fecha_atencion,'DD/MM/YYYY') AS fecha_evaluacion, to_char(t13.fecha_atencion, 'HH24:MI AM') AS hora,
            c3.nombre as consultorio, 
            c.nombre as cama, servicio_encargado.nombre as servicio_encargado_atencion
            FROM evaluaciones t13
            inner join prestaciones t1 on t13.id_prestacion = t1.id
            inner join asegurados a on t13.id_asegurado = a.id 
            inner join medicos t5 on t13.id_medico=t5.id
            inner join cambio_camas cc on t1.id = cc.id_prestacion and t13.id = cc.id_evolucion
            inner join camas c on cc.id_cama_atencion = c.id
            inner join cuartos c2 on c.id_cuarto = c2.id
            inner join asignacion_servicios as2 on c2.id_asignacion_servicio = as2.id
            inner join consultorios c3 on as2.id_consultorio = c3.id
            left join consultorios as servicio_encargado on cc.id_servicio_encargado_atencion = servicio_encargado.id
            Where t13.id_tipo_atencion = 3 and t13.id_estado_reg = 1 and t1.id_estado_reg = 1 and a.nro_doc_ident = '" . $nro_doc_ident . "' and a.id_estado_reg = 1 order by t13.id desc"; 
              $data = DB::select($cad);
              return $data;
  }



  public function registrarEvaluacionHospitalizacion($id_emergencia,$id_prestacion,$id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico ,$id_user, $examen_fisico,$temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02, $diuresis, $balance_hidrico, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo,$antecedente,$antecedente_familiar,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama, $p_diagnostico, $valor,$cod_siteds_susalud,$apreciacion_subjetiva,$apreciacion_objetiva,$verificacion_tratamiento,$interpretacion_examenes,$procedimientos,$flag_alta_servicio,$observacion_alta_servicio,$id_establecimiento, $id_servicio_cama,$id_tipo_cambio_cama,$id_upss_encargado,$id_servicio_encargado,$regularizar_datos_encargado,$id_upss_encargado_regularizacion,$id_servicio_encargado_regularizacion,$id_servicio_cama_regularizacion) { //38
    //antes era esta funcion: sp_insert_evaluacion con 40 parametros
    $cad = "Select sp_insert_hospitalizacion_evolucion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_emergencia,$id_prestacion,$id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico ,$id_user, $examen_fisico,$temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02, $diuresis, $balance_hidrico, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo,$antecedente,$antecedente_familiar,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama,$p_diagnostico, $valor,$cod_siteds_susalud,$apreciacion_subjetiva,$apreciacion_objetiva,$verificacion_tratamiento,$interpretacion_examenes,$procedimientos,$flag_alta_servicio,$observacion_alta_servicio,$id_establecimiento,$id_servicio_cama,$id_tipo_cambio_cama,$id_upss_encargado,$id_servicio_encargado,$regularizar_datos_encargado,$id_upss_encargado_regularizacion,$id_servicio_encargado_regularizacion,$id_servicio_cama_regularizacion));

    return $data[0]->sp_insert_hospitalizacion_evolucion;

  }
  public function listar_evaluacion_hospitalizacion($p){
    return $this->readFunctionPostgres('sp_listar_evaluacion_hospita',$p);
  }
  public function listar_alta_hospitalizacion($p){
    return $this->readFunctionPostgres('sp_listar_alta_hospi',$p);
  }
  public function listar_altadAdministrativa_hospitalizacion($p){
    return $this->readFunctionPostgres('sp_listar_altAadministrativa_hospi',$p);
  }

  public function validar_Siteds_Emergencia($cod_siteds)
    {
      $query_string = "SELECT count(id) as cantidad_siteds from evaluaciones where cod_siteds_susalud = '{$cod_siteds}'";
      $data = DB::SELECT($query_string);
      if ($data != null || count($data) > 0){
        return $data[0];
      }else{
        return $data;
      }; 
    }

  public function ActualizaSiteds($codigo_siteds, $id) {

      $datos = Evaluacione::where('id', $id)->first();
      $datos->cod_siteds_susalud = $codigo_siteds;
      return $datos->save();
      
      // return true;

  }   

  public function getCodigoSitedsEvolucionEnElDia($id_ext, $id_tipo_atencion) {
    $evolucion = Evaluacione::where('id_emergencia', $id_ext)
              ->where('id_tipo_atencion', $id_tipo_atencion)
              ->whereRaw('cast(fecha_atencion as date) = cast(now() as date)')
              ->where('id_estado_reg', 1)
              ->select('cod_siteds_susalud')
              ->orderBy('fecha_atencion','desc')
              ->orderBy('id','desc')
              ->first();
    $codigo_siteds = $evolucion != null ? $evolucion->cod_siteds_susalud : "";
    return $codigo_siteds;
  }

  /**
   * Obtener ultima atencion activa segun el tipo de atencion
   * Puede ser de los tipo de atenciones que cuentan con evoluciones.
   */
  public function getUltimaAtencionByTipoAtencion($id_hospitalizacion, $id_tipo_atencion) {
    if ($id_tipo_atencion == 3) {
      $ultima_atencion = null;
      $ultima_atencion = Evaluacione::where('id_emergencia', $id_hospitalizacion)
        ->where('id_tipo_atencion', 3)
        ->where('id_estado_reg', 1)
        ->orderBy('id', 'desc')
        ->first();
      if ($ultima_atencion == null) {
        $ultima_atencion = Prestaciones::where('id_ext', $id_hospitalizacion)
          ->where('id_tipo_atencion', 3)
          ->where('id_estado_reg', 1)
          ->orderBy('id', 'desc')
          ->first();
        // Adicionar campo temporal <tipo_atencion_ultima> para identificar tabla de datos
        $ultima_atencion->tipo_atencion_ultima = "prestacion";
      } else {
        // Adicionar campo temporal <tipo_atencion_ultima> para identificar tabla de datos
        $ultima_atencion->tipo_atencion_ultima = "evolucion";
      }
    }
    return $ultima_atencion;
  }

  public function GetIndicacionesTerapeuticas($id_hospitalizacion) {
        $datos = DB::table('evaluaciones')->where('id_tipo_atencion', '=', 3)->where('id_emergencia', '=', $id_hospitalizacion)->where('id_estado_reg', '<>', 0)->get();
        return $datos;
    }
  public function GetTrasladoEvaluacion($id_prestacion,$id_evaluacion) {
      $datos = Evaluacione::where('id_tipo_atencion', '=', 3)->where('id_prestacion', '=', $id_prestacion)->where('id', '=', $id_evaluacion)->where('id_estado_reg', '<>', 0)->get();
      return $datos[0];
  }

}