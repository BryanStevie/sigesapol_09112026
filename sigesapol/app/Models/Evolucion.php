<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Evolucion extends Model
{
  public function GetEvolucionesByPacienteEmergencia($id_paciente, $estado) {
      
    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,sexo,tb.descripcion parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,
t2.nro_doc_ident,t1.fecha_ingreso dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso, t4.hora_ingreso hora_ingreso
      FROM emergencias t1
      inner join evolucions t4 on t4.id_paciente = t1.id 
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t4.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_beneficiarios tb on t1.id_tipo_beneficiario=tb.id
      Where t1.estado=". $estado ." and t1.id=".$id_paciente; 

          $data = DB::select($cad);
          return $data;
  }

  public function GetEvolucionesByAsegurado2($id_emergencia, $estado) {
      
      if($id > 0){

              $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,sexo,tb.descripcion parentesco,grado,fecha_nac,tipo_doc_ident,
      t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion servicio,t4.descripcion consultorio,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno
      ,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion
      FROM emergencias t1
      inner join evolucions t4 on t4.id_paciente = t1.id 
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

  public function GetEvolucion($id_evolucion) {
      
    $cad = "select t1.* FROM evolucions t1 Where t1.id=". $id_evolucion." and t1.id_estado_reg = 1"; 
    $data = DB::select($cad);
    return $data[0];
      
  }
  
  public function RegistrarEvolucion($id_prestacion,$id_paciente , $id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico , $id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $hora_ingreso , $id_servicio , $subjetivo , $objetivo , $analisis , $tratamiento , $indicaciones, $id_cama, $p_procedimiento, $p_diagnostico) {
                                
    $cad = "Select sp_insert_evolucion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_prestacion,$id_paciente , $id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico , $id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $hora_ingreso , $id_servicio , $subjetivo , $objetivo , $analisis , $tratamiento , $indicaciones, $id_cama,$p_procedimiento, $p_diagnostico));

    return $data[0]->sp_insert_evolucion;

  }

  public function GetDocumentosPendientesFirmarEvolucionId($id)
  {
    $query_string = "with tbl as (
        select id, id id_prestacion,fecha_firma, flag, 'evolucions' tipo 
        from evolucions e where id = {$id}
        ) select * from tbl
        where flag = 0";
    return DB::select($query_string);
  }

  public function GetEvolucionById($id_evolucion)
  {
    $datos = Evolucion::where('id', '=', $id_evolucion)->first();
    return $datos;
  }

  public function listar_evolucion_ajax($p){
    return $this->readFunctionPostgres('sp_evolucion_paginado',$p);
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

   public function GetDiagnosticoByIdEvolucion($id)
  {

    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo,
            (case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico=t2.id
            where t1.id_evolucion=" . $id . "
            and t1.estado=1";
    $data = DB::select($cad);
    return $data;
  }

   public function GetProcedimientosByIdEvolucion($id, $tipo)
  {

    $cad = "select codigo,descripcion nombre, cantidad
            from prestacion_procedimientos t1
            inner join procedimientos t2 on t1.id_procedimiento=t2.id
            where t1.id_evolucion=" . $id . " and t2.tipo_procedimiento=" . $tipo . "
            and t1.id_estado_reg=1";
    $data = DB::select($cad);
    return $data;
  }

  public function GetEvolutionById($id)
  {

    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,sexo,'' parentesco,'' grado,fecha_nac,tipo_doc_ident,
      t2.nro_doc_ident,t1.fecha_atencion dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t1.id_estado_reg, t1.id, t1.fecha_atencion , t1.hora_ingreso hora_ingreso, t4.nombre tipo_atencion, '' servicio, 
      t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, 
                    t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.subjetivo, t1.objetivo, t1.analisis, t1.tratamiento_dieta, t1.indicaciones
      FROM evolucions t1
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      left join medicos t5 on t5.id= t1.id_medico
      left join especializaciones t7 on t5.id_especializacion=t7.id
      left join profesiones t8 on t5.id_profesion=t8.id
      left join tipo_atenciones t4 on t1.id_tipo_atencion=t4.id
      Where t1.id=" . $id;

      $data = DB::select($cad);
      return $data[0];
  }

  public function GetEvolucionesByIdExt($id_ext)
  {

    $cad = "select *
            from evolucions t1
            where t1.id_paciente=" . $id_ext . "
            and t1.id_estado_reg=1";
    $data = DB::select($cad);
    return $data;
  }

  public function GetEvolucionesByPacienteHospitalizacion($id_paciente, $estado) {
      
    $cad = "select t4.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,
      t2.nro_doc_ident,t1.fecha_ingreso dia,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso, t4.hora_ingreso hora_ingreso
      FROM hospitalizaciones t1
      inner join evolucions t4 on t4.id_paciente = t1.id 
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

  public function RegistrarEvolucionEnfermeria($id_prestacion,$id_paciente , $id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico , $id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $hora_ingreso , $id_servicio , $sintomas, $p_procedimiento, $p_diagnostico) {
                                
    $cad = "Select sp_insert_evolucion_enfermeria(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_prestacion,$id_paciente , $id_asegurado , $id_tipo_atencion , $fecha_atencion, $id_medico , $id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $hora_ingreso , $id_servicio , $sintomas, $p_procedimiento, $p_diagnostico));

    return $data[0]->sp_insert_evolucion_enfermeria;

  }

  public function GetEvolucionesByPacienteEnfermeria($id_paciente, $id_prestacion) {
      
    $cad = "select t2.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,tb.descripcion parentesco,'' grado,fecha_nac,tipo_doc_ident,t4.id_tipo_atencion,
      t2.nro_doc_ident,t1.fecha_ingreso dia,to_char(t1.created_at , 'HH:MI AM') hora,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t4.id_estado_reg, t4.id, t4.fecha_atencion fecha_ingreso, t4.hora_ingreso hora_ingreso
      FROM hospitalizaciones t1
      inner join evolucion_enfermerias t4 on t4.id_paciente = t1.id 
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

  public function GetEvolucionEnfermeria($id_evolucion) {
      
    $cad = "select t1.* FROM evolucion_enfermerias t1 Where t1.id=". $id_evolucion." and t1.id_estado_reg = 1"; 
    $data = DB::select($cad);
    return $data[0];
      
  }

  public function listar_evolucion_ajax_enfermeria($p){
    return $this->readFunctionPostgres('sp_evolucion_enfermeria_paginado',$p);
  }

  public function GetEvolutionEnfermeriaById($id)
  {

    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,sexo,'' parentesco,'' grado,fecha_nac,tipo_doc_ident,
      t2.nro_doc_ident,t1.fecha_atencion dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t1.id_estado_reg, t1.id, t1.fecha_atencion , t1.hora_ingreso hora_ingreso, t4.nombre tipo_atencion, '' servicio, 
      t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, 
                    t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.sintomas
      FROM evolucion_enfermerias t1
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

  public function GetEstadoFirmaEvolucion($id)
  {
    $query_string = "select id, fecha_firma, ruta_evolucion from evolucions where id = '{$id}'";
    return DB::select($query_string);
  }
}