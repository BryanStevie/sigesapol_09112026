<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Evaluacion extends Model
{
  protected $table = 'evaluaciones';
  public $timestamps = false;
  protected $primaryKey = 'id';
  protected $fillable = [
    'id_emergencia',
    'id_prestacion',
    'id_asegurado',
    'id_tipo_atencion',
    'fecha_atencion',
    'id_medico',
    'id_estado_reg',
    'examen_fisico',
    'fecha_firma',
    'ruta_evaluacion',
    'antecedente',
    'plan_trabajo',
    'temperatura',
    'sa02_porcentaje',
    'presion_arterial_x',
    'presion_arterial_y',
    'talla_cm',
    'tamano_cintura_cm',
    'frecuencia_respiratoria',
    'peso_kg',
    'frecuencia_cardiaca_fetal',
    'frecuencia_cardiaca',
    'fi02',
    'motivo_visita',
    'tiempo_enfermedad',
    'examen_clinico',
    'examen_auxiliar',
    'tratamiento_auxiliar',
    'flag',
    'antecedente_familiar',
    'ubica_prioridad',
    'ubica_topico',
    'cama_estado',
    'fecha_inicio_estado',
    'hora_estado',
    'created_at',
    'created_us',
    'updated_at',
    'updated_us',
    'deleted_at',
    'deleted_us',
    'cod_siteds_susalud',
    'apreciacion_subjetiva',
    'apreciacion_objetiva',
    'verificacion_tratamiento',
    'interpretacion_examenes',
    'diuresis',
    'balance_hidrico',
    'updated_us_cod_siteds',
    'updated_at_cod_siteds',
    'id_puerperio',
    'flag_orden_traslado', // flag para validar si se genero la orden traslado
    'fecha_solicitud_orden_traslado', // fecha que se genero la orden de traslado
    'fecha_recibe_orden_traslado', // fecha que se genero la orden de traslado
    'ruta_orden_traslado', // url de la orden de traslado en pdf
    'estado_orden_traslado', // estado en que se encuentra la orden de traslado: 0 => pendiente firmado, 1=> pendiendo aprobacion, 2=> aprobado, 3 => rechazado
    'id_medico_solicita_traslado', // id del medico que solicito el traslado
    'id_medico_recibe_traslado' // id del medico que recibe el traslado
  ];

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
            
      Where t1.estado=". $estado ." and t1.id=".$id_paciente." order by t4.id desc"; 
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
      
    $cad = "select t1.*, to_char(t1.created_at::date,'DD/MM/YYYY') AS fecha_evaluacion, to_char(t1.created_at, 'HH:MI:SS AM')  AS hora_evaluacion FROM evaluaciones t1 Where t1.id=". $id_evaluacion." and t1.id_estado_reg = 1 order by t1.id desc"; 
    $data = DB::select($cad);
    return $data[0];
      
  }

  public function GetObtenerServicioEvaluacion($id) {
        
        if($id > 0){

                $cad = "select t10.descripcion servicio,t4.descripcion consultorio, t1.ubica_prioridad, t1.ubica_topico, t1.cama_estado cama, t1.fecha_inicio_estado, t1.hora_estado 
                        FROM evaluaciones t1
                        inner join triajes t4 on t1.ubica_topico=t4.id
                        inner join prioridades t10 on t1.ubica_prioridad=t10.id
                        Where t1.id=".$id; 

            $data = DB::select($cad);
            return $data[0];
        }
    }

  
  public function registrarEvaluacion($id_emergencia,$id_prestacion,$id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico ,$id_user, $examen_fisico,$temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo,$antecedente,$antecedente_familiar,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama) { //32
                                
    $cad = "Select sp_insert_evaluacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_emergencia,$id_prestacion,$id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico ,$id_user, $examen_fisico,$temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo,$antecedente,$antecedente_familiar,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama));

    return $data[0]->sp_insert_evaluacion;

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

    $cad = "select t1.*,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,sexo,'' parentesco,'' grado,fecha_nac,tipo_doc_ident,t3.nombre establecimiento,
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

  public function listar_evaluacion_ajax($p){
    return $this->readFunctionPostgres('sp_evaluacion_paginado',$p);
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

    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo,
            (case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico=t2.id
            where t1.id_evaluacion=" . $id . "
            and t1.estado=1";
    $data = DB::select($cad);
    return $data;
  }

   public function GetProcedimientosByIdEvaluacion($id, $tipo)
  {

    $cad = "select codigo,descripcion nombre, cantidad
            from prestacion_procedimientos t1
            inner join procedimientos t2 on t1.id_procedimiento=t2.id
            where t1.id_evaluacion=" . $id . " and t2.tipo_procedimiento=" . $tipo . "
            and t1.id_estado_reg=1";
            
    $data = DB::select($cad);
    return $data;
  }

  public function GetEvaluacionByIdEmergencia($id)
  {

    $cad = "select t1.*, t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, to_char(t1.created_at::date,'DD/MM/YYYY') AS fecha_evaluacion, to_char(t1.created_at, 'HH:MI:SS AM')  AS hora_evaluacion
      FROM evaluaciones t1
      left join medicos t5 on t5.id= t1.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      Where t1.id=" . $id;

      $data = DB::select($cad);
      return $data[0];
  }

  public function GetEvaluacionesByIdExt($id_ext)
  {

    $cad = "select *
            from evaluaciones t1
            where t1.id_paciente=" . $id_ext . "
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
    $query_string = "select id, fecha_firma, ruta_evaluacion from evaluaciones where id = '{$id}'";
    return DB::select($query_string);
  }

  public static function RegistrarProcedimientoEvaluacion($id_evaluacion,$id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evolucion)
  {
    $cad = "Select sp_insert_procedimiento_evaluacion(?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evolucion));
    
    return $data[0]->sp_insert_procedimiento;
  }

  public function GetAtencionesByIdExt($id_ext)
  {
    $cad = "select 
            t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
            t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t6.nombre servicio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg,
            t10.descripcion nom_prioridad,t11.descripcion consultorio
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
            WHERE t1.id_ext=" . $id_ext . "
            And t1.id_estado_reg = 1
            ORDER BY t1.created_at desc";
    $data = DB::select($cad);
    return $data;
  }

}