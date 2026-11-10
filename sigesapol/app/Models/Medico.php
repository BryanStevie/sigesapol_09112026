<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;

class Medico extends Model
{
  //

  public function ShowMedicosByIpress($id_farmacia)
  {
    $datos = DB::table('medicos')
      ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')
      ->join('farmacias', 'establecimientos.id', '=', 'farmacias.id_establecimiento')

      ->select('medicos.*', 'especializaciones.nombre as especializacion')
      ->where('farmacias.id', '=', $id_farmacia)
      ->get();

    return $datos;
  }

  public function ShowMedicosByIpressServicio($id_ipress, $id_servicio)
  {
    $datos = DB::table('medicos')
      ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')
      ->select('medicos.*', 'especializaciones.nombre as especializacion')
      ->where('medicos.id_establecimiento', '=', $id_ipress)
      ->where('medicos.id_consultorio', '=', $id_servicio)
      ->get();

    return $datos;
  }

  public function ShowallById_farmacia($id_farmacia)
  {
    $datos = DB::table('medicos')
      ->where('medicos.id_farmacia', '=', $id_farmacia)
      ->select('medicos.*')
      ->get();
    return $datos;
  }

  public function ShowallById_Ipress($id_ipress, $id_profesion = 0)
  {
    $cad = "select m.* , est.nombre establecimiento, esp.nombre as especializacion, pr.nombre as profesion, tco.nombre as tipo_contrato from medicos m
                Inner Join establecimientos est On m.id_establecimiento = est.id
                Inner Join especializaciones esp On m.id_especializacion=esp.id
                Left Join profesiones pr On m.id_profesion=pr.id
                Left Join tipo_contratos tco On m.tipo_contrato=tco.id Where m.estado = 1";
    if ($id_ipress > 0) $cad .= " And m.id_establecimiento=" . $id_ipress . "";
    if ($id_profesion > 0) $cad .= " And m.id_profesion =" . $id_profesion;
    $cad .= " Order By m.estado, m.paterno, m.materno, m.nombre";
    $data = DB::select($cad);
    return $data;
  }

  public function ShowAllMedicos()
  {
    $cad = "select m.* , est.nombre establecimiento, esp.nombre as especializacion, pr.nombre as profesion, tco.nombre as tipo_contrato 
      from medicos m
      Inner Join establecimientos est On m.id_establecimiento = est.id
      Inner Join especializaciones esp On m.id_especializacion=esp.id
      Left Join profesiones pr On m.id_profesion=pr.id
      Left Join tipo_contratos tco On m.tipo_contrato=tco.id Where 1=1
      Order By m.estado, m.paterno, m.materno, m.nombre";
    $data = DB::select($cad);
    return $data;
  }

  public function GetMedicoById($id)
  {
    $datos = DB::table('medicos')
      ->where('medicos.id', '=', $id)
      ->select('medicos.*')
      ->where('medicos.estado', '=', '1')
      ->first();
    return $datos;
  }

  public function GetMedicoByDNI($dni)
  {
    $datos = DB::table('medicos')
      ->where('medicos.dni', '=', $dni)
      ->where('medicos.estado', '=', '1')
      ->select('medicos.*')
      ->first();
    //->get();
    return $datos;
  }

  public function GetActiveMedicoByDNI($dni)
  {
    $datos = DB::table('medicos')
      ->where('medicos.dni', '=', $dni)
      ->where('medicos.estado', '=', '1')
      ->select('medicos.*')
      ->first();
    return $datos;
  }

  /*public function prueba($dni){
	}*/

  public function GetMedicoByDniAndEstablecimiento($dni, $id_establecimiento = 0)
  {

    $cad = "select distinct t1.id
from medicos t1
inner join medico_consultorios t2 on t1.id=t2.id_medico
where dni='" . $dni . "' 
and t1.estado = 1
and t2.id_establecimiento=" . $id_establecimiento;
    //echo $cad;exit();
    $data = DB::select($cad);
    return $data[0];
  }

  public function GetDatosMedicoByDniAndEstablecimiento($dni, $id_establecimiento = 0)
  {

    $cad = "select t1.*,t3.nombre profesion,t4.nombre especializacion
            from medicos t1
            left join profesiones t3 on t1.id_profesion=t3.id
            left join especializaciones t4 on t1.id_especializacion=t4.id
            where t1.estado=1 and dni='" . $dni . "' 
            and t1.id_establecimiento=" . $id_establecimiento;
    $data = DB::select($cad);
    if(count($data)>0):
      return $data[0];
    else:
      return $data;
    endif;
  }
  public function GetResponsableProcedimientoProgramado($dni, $id_establecimiento)
  {
    $medico = DB::select("select m.id, m.dni, m.paterno || ' ' || m.materno || ', ' || m.nombre as nombre_completo,
        m.colegiatura as cmp, m.rne, coalesce(es.nombre, m.especialidad) as especialidad
      from medicos m
      left join especializaciones es on es.id = m.id_especializacion
      where m.dni = ? and m.id_establecimiento = ?
      order by m.estado desc, m.id desc
      limit 1", [$dni, $id_establecimiento]);
    return count($medico) > 0 ? $medico[0] : null;
  }

  public function GetResponsableProcedimientoProgramadoPorId($id_medico)
  {
    $medico = DB::select("select m.id, m.dni, m.paterno || ' ' || m.materno || ', ' || m.nombre as nombre_completo,
        m.colegiatura as cmp, m.rne, coalesce(es.nombre, m.especialidad) as especialidad
      from medicos m
      left join especializaciones es on es.id = m.id_especializacion
      where m.id = ?", [$id_medico]);
    return count($medico) > 0 ? $medico[0] : null;
  }

  public function GetDatosMedicoByDniAndEstablecimientoSaenzLeguia($dni, $id_establecimientos)
  {
      return DB::table('medicos')
          ->where('dni', $dni)
          ->whereIn('id_establecimiento', $id_establecimientos)
          ->first();
  }
  public function GetDatosMedicoByDni($dni)
  {
      return DB::table('medicos')
          ->where('dni', $dni)
          ->where('estado', 1)
          ->first();
  }

  public function GetDatosMedicoAndEstablecimiento($dni, $id_establecimiento = 0)
  {

    $cad = "select t1.*,t3.nombre profesion,t4.nombre especializacion
            from medicos t1
            inner join profesiones t3 on t1.id_profesion=t3.id
            inner join especializaciones t4 on t1.id_especializacion=t4.id
            where t1.estado=1 and dni='" . $dni . "' 
            and t1.id_establecimiento=" . $id_establecimiento;

    $data = DB::select($cad);
    return $data;
  }

  public function ShowMedicosPorEspecializacion($nombre, $id_ipress)
  {
    $datos = DB::table('medicos')
      ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      ->where('especializaciones.nombre', '=', $nombre)
      ->where('medicos.id_establecimiento', '=', $id_ipress)
      ->select('medicos.*')
      ->get();
    return $datos;
  }



  public function ShowSoloMedicos($id_ipress)
  {
    $datos = DB::table('medicos')
      ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      ->where('especializaciones.id', '!=', 6)
      ->where('especializaciones.id', '!=', 7)
      ->where('medicos.estado', '=', 1)
      ->where('medicos.id_establecimiento', '=', $id_ipress)
      ->select('medicos.*')
      ->get();
    return $datos;
  }

  public function exite_medico($dni)
  {
    $datos = Medico::where('dni', '=', $dni)->count();
    return $datos;
  }


  public function medico_yaregistrado($dni, $id_ipress)
  {
    if ($id_ipress == 76) {
      $datos = DB::table('medicos as m')
                ->join('establecimientos as est', 'm.id_establecimiento', '=', 'est.id')
                ->leftJoin('especializaciones as esp', 'm.id_especializacion', '=', 'esp.id')
                ->leftJoin('medico_consultorios as mc', function ($join) {
                    $join->on('m.id', '=', 'mc.id_medico');
                    $join->where('mc.estado', '=', 1);
                    $join->on('mc.id_establecimiento', '=', 'm.id_establecimiento');
                })
                ->whereIn('m.estado', array(0, 1))
                ->where('m.dni', '=', $dni)
                ->groupBy(
                    'm.id',
                    'm.dni',
                    'est.nombre',
                    'est.id',
                    'esp.nombre'
                )
                ->select(
                    'm.*',
                    'est.nombre as establecimiento',
                    DB::raw("COALESCE(esp.nombre, 'SIN ESPECIALIZACIÓN') as especializacion"),
                    'est.id as ipress',
                    'm.estado'
                )
                ->first();
    } else {
      $datos = DB::table('medicos')
        ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
        ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')
        ->where('medicos.dni', '=', $dni)
        ->where('medicos.id_establecimiento', '=', $id_ipress)
        ->where('medicos.estado', 1)
        ->select('medicos.*', 'especializaciones.nombre as especializacion', 'establecimientos.nombre as ipress')
        ->first();
    }
    return $datos;
  }

  public function profesionalesInstitucionalByFarma($id_farmacia, $tipo)
  {
    //var_dump("hola"); exit;
    $datos = DB::table('medicos')
      ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')
      ->join('farmacias', 'establecimientos.id', '=', 'farmacias.id_establecimiento')
      ->where('farmacias.id', '=', $id_farmacia)
      ->where('medicos.tipo_contrato', '=', $tipo)->count();

    return $datos;
  }

  public function getFechaServidorUltimoDiaMes($fechaBuscar = "now()")
  {

    $cad = "select extract (day from (select date_trunc('month', '" . $fechaBuscar . "'::date) + interval '1 month') - interval '1 day') dia
		,LPAD((extract(month from '" . $fechaBuscar . "'::date)::text),2,'0')mes
		,extract(year from '" . $fechaBuscar . "'::date)ano";
    
    $data = DB::select($cad);

    return $data[0];
  }

  public function profesionales($id_ipress, $id_servicio)
  {
    $cad = " select c.id,m.id, m.nombre as name, m.paterno as paterno_usuario, m.materno as materno_usuario
                from medicos m
                Inner Join medico_consultorios mc On m.id=mc.id_medico
                Inner Join consultorios c On c.id=mc.id_consultorio

                Inner Join establecimientos est On m.id_establecimiento = est.id
                Where m.id_establecimiento=" . $id_ipress . " and c.id=" . $id_servicio . " and m.estado=1
                group By c.id, m.id, m.nombre, m.paterno, m.materno
                Order By m.paterno";

    $dato = DB::select($cad);

    return $dato;
  }

  public function responsable_ipress($id_farmacia)
  {

    $dato = DB::table('users')
      ->join('assigned_roles', 'users.id', '=', 'assigned_roles.user_id')
      ->join('roles', 'assigned_roles.role_id', '=', 'roles.id')
      ->where('users.id_farmacia', $id_farmacia)
      ->where('roles.id', 3)
      ->where('users.status', 1)
      ->select('users.id', 'users.name', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario')
      ->groupBy('users.id', 'users.name', 'users.paterno', 'users.materno')
      ->orderBy('users.id')
      ->get();

    return $dato;
  }

  public function GetDatosMedicoByIdAndEstablecimiento($id, $id_establecimiento = 0)
  {

    $cad = "select t1.*,t3.nombre profesion,t4.nombre especializacion
from medicos t1
inner join medico_consultorios t2 on t1.id=t2.id_medico
left join profesiones t3 on t1.id_profesion=t3.id
left join especializaciones t4 on t1.id_especializacion=t4.id
where t1.id='" . $id . "' 
and t2.id_establecimiento=" . $id_establecimiento;
    //echo $cad;exit();
    $data = DB::select($cad);
    return $data[0];
  }

  public function MostrarMedico($id)
  {
    $cad = "select m.* , est.nombre establecimiento, esp.nombre as especializacion, pr.nombre as profesion, tco.nombre as tipo_contrato 
      from medicos m
      Inner Join establecimientos est On m.id_establecimiento = est.id
      Left Join especializaciones esp On m.id_especializacion=esp.id
      Left Join profesiones pr On m.id_profesion=pr.id
      Left Join tipo_contratos tco On m.tipo_contrato=tco.id 
      Where m.id =".$id;
    $data = DB::select($cad);
    return $data[0];
  }

  public function listar_personal_ajax($p)
  {
    return $this->readFunctionPostgres('sp_listar_personal_salud', $p);
  }
  public function listar_personal_ajax_por_ipress($p)
  {
    return $this->readFunctionPostgres('sp_listar_personal_salud_v5', $p);
  }

  public function listar_personal_ajax_por_ipress_v2($p)
  {
    return $this->readFunctionPostgres('sp_listar_personal_salud_v6', $p);
  }

  public function listar_personal_ajax_general($p)
  {
    return $this->readFunctionPostgres('sp_listar_personal_salud_general', $p);
  }

  public function readFunctionPostgres($function, $parameters = null)
  {

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

   public function GetMostrarMedicoByDNI($dni)
  {
    $datos = DB::table('medicos')
      ->join('tipo_documento_identidad', 'medicos.tipo_documento', '=', 'tipo_documento_identidad.id')
      ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')
      ->leftJoin('profesiones', 'medicos.id_profesion', '=', 'profesiones.id')
      ->where('medicos.dni', '=', $dni)
      ->where('medicos.estado', '=', 1)
      ->select('medicos.*', 'especializaciones.nombre as especializacion', 'establecimientos.nombre as ipress', 'profesiones.nombre as profesion', 'tipo_documento_identidad.descripcion as tipo_documento_nombre' )
      ->first();
    return $datos;
  }


public function GetMostrarAtencionesMedicoByHIS($tipo_atencion, $id_establecimiento, $fecha_atencion,$id_medico, $id_consultorio)
{
    $cad = "select t1.id, t2.nombre paciente_nombre,t2.paterno paciente_paterno,t2.materno paciente_materno,t10.nro_historia,
            tipo_doc_ident,t2.nro_doc_ident,
            t11.nombre atencion, to_char(t1.created_at, 'HH:MI AM') horario,to_char(t1.fecha_atencion, 'DD/MM/YYYY') fecha_atencion,
            substring(t1.upss, 1, 1) upss,
            substring(t1.ipress, 1, 1) ipress
            /*(case when (select count(*) 
            FROM prestaciones t1_
            inner join sub_consultorios t4_ on t1_.id_sub_consultorio=t4_.id
            inner join consultorios t7_ on t4_.id_consultorio=t7_.id
            where t1_.id_estado_reg=1 and t1_.id_asegurado=t2.id And t1.fecha_atencion <= '".$fecha_atencion."' and t4_.id_consultorio=t12.id 
            )>1 Then 'C' else 'N' end) upss,
            (case when (select count(*) 
            FROM prestaciones t1_
            where t1_.id_estado_reg=1 and t1_.id_asegurado=t1.id_asegurado  and t1_.id_establecimiento=t1.id_establecimiento 
            )>1 Then 'C' else 'N' end) ipress*/
            FROM prestaciones t1
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join asegurado_historias t10 on t1.id_asegurado = t10.id_asegurado And t1.id_establecimiento = t10.id_establecimiento And t10.id_estado_reg<>'0' 
            inner join establecimientos t3 on t1.id_establecimiento=t3.id
            inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
            inner join consultorios t12 on t4.id_consultorio=t12.id
            inner join medicos t5 on t1.id_medico=t5.id
            inner join tipo_atenciones t11 on t1.id_tipo_atencion=t11.id 
            inner join users t13 on t13.dni=t5.dni 
            Where t1.id_tipo_atencion = ".$tipo_atencion."
                  And t3.id = ".$id_establecimiento."
                  And to_char(t1.fecha_atencion, 'DD-MM-YYYY') = '".$fecha_atencion."'
                  And t1.id_medico = ".$id_medico."
                  And t4.id_consultorio =".$id_consultorio."
                  And t1.id_estado_reg = 1
                  group by t1.id, t2.id, t10.id, t11.id, t12.id
            Order By t1.id Desc";
            
    $data = DB::select($cad);
    return $data;
}

public function GetMostrarAtencionesMedicoByHISHO($tipo_atencion, $id_establecimiento, $fecha_atencion,$id_medico, $id_consultorio)
{
    $cad = "select t1.id, t2.nombre paciente_nombre,t2.paterno paciente_paterno,t2.materno paciente_materno,t10.nro_historia,
            tipo_doc_ident,t2.nro_doc_ident,
            t11.nombre atencion, to_char(t1.created_at, 'HH:MI AM') horario,to_char(t1.fecha_atencion, 'DD/MM/YYYY') fecha_atencion,
            substring(t1.upss, 1, 1) upss,
            substring(t1.ipress, 1, 1) ipress
            FROM prestaciones t1
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join asegurado_historias t10 on t1.id_asegurado = t10.id_asegurado And t1.id_establecimiento = t10.id_establecimiento And t10.id_estado_reg<>'0' 
            inner join establecimientos t3 on t1.id_establecimiento=t3.id
            inner join consultorios t12 on t1.id_servicio=t12.id
            inner join medicos t5 on t1.id_medico=t5.id
            inner join tipo_atenciones t11 on t1.id_tipo_atencion=t11.id 
            inner join users t13 on t13.dni=t5.dni 
            Where t1.id_tipo_atencion = ".$tipo_atencion."
                  And t3.id = ".$id_establecimiento."
                  And to_char(t1.fecha_atencion, 'DD-MM-YYYY') = '".$fecha_atencion."'
                  And t1.id_medico = ".$id_medico."
                  And t12.id =".$id_consultorio."
                  And t1.id_estado_reg = 1
                  group by t1.id, t2.id, t10.id, t11.id, t12.id
            Order By t1.id Desc";
            
    $data = DB::select($cad);
    return $data;
}

public function GetMostrarEvaluacionesMedicoByHIS($tipo_atencion, $id_establecimiento, $fecha_atencion,$id_medico)
{
    $cad = "select t1.id, t2.nombre paciente_nombre,t2.paterno paciente_paterno,t2.materno paciente_materno,t10.nro_historia, tipo_doc_ident,t2.nro_doc_ident, t11.nombre atencion, to_char(t1.created_at, 'HH:MI AM') horario,to_char(t1.fecha_atencion, 'DD/MM/YYYY') fecha_atencion, t1.id_prestacion
            FROM evaluaciones t1 
            inner join emergencias te on te.id = t1.id_emergencia
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join asegurado_historias t10 on t1.id_asegurado = t10.id_asegurado And te.id_establecimiento = t10.id_establecimiento And t10.id_estado_reg<>'0' 
            inner join establecimientos t3 on te.id_establecimiento=t3.id  
            inner join medicos t5 on t1.id_medico=t5.id 
            inner join tipo_atenciones t11 on t1.id_tipo_atencion=t11.id 
            inner join users t13 on t13.dni=t5.dni 
            Where t1.id_tipo_atencion = ".$tipo_atencion."
                  And t3.id = ".$id_establecimiento."
                  And to_char(t1.fecha_atencion, 'DD-MM-YYYY') = '".$fecha_atencion."'
                  And t1.id_medico = ".$id_medico."
                  And t1.id_estado_reg = 1
                  group by t1.id, t2.id, t10.id, t11.id
            Order By t1.id Desc";
            
    $data = DB::select($cad);
    return $data;
}

public function GetMostrarEvaluacionesMedicoByHISHO($tipo_atencion, $id_establecimiento, $fecha_atencion,$id_medico)
{
    $cad = "select t1.id, t2.nombre paciente_nombre,t2.paterno paciente_paterno,t2.materno paciente_materno,t10.nro_historia, tipo_doc_ident,t2.nro_doc_ident, t11.nombre atencion, to_char(t1.created_at, 'HH:MI AM') horario,to_char(t1.fecha_atencion, 'DD/MM/YYYY') fecha_atencion, t1.id_prestacion
            FROM evaluaciones t1 
            inner join hospitalizaciones te on te.id = t1.id_emergencia
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join asegurado_historias t10 on t1.id_asegurado = t10.id_asegurado And te.id_establecimiento = t10.id_establecimiento And t10.id_estado_reg<>'0' 
            inner join establecimientos t3 on te.id_establecimiento=t3.id  
            inner join medicos t5 on t1.id_medico=t5.id 
            inner join tipo_atenciones t11 on t1.id_tipo_atencion=t11.id 
            inner join users t13 on t13.dni=t5.dni 
            Where t1.id_tipo_atencion = ".$tipo_atencion."
                  And t3.id = ".$id_establecimiento."
                  And to_char(t1.fecha_atencion, 'DD-MM-YYYY') = '".$fecha_atencion."'
                  And t1.id_medico = ".$id_medico."
                  And t1.id_estado_reg = 1
                  group by t1.id, t2.id, t10.id, t11.id
            Order By t1.id Desc";
    $data = DB::select($cad);
    return $data;
}

public function GetDocumentosPendientesFirmarHojaHis($tipo_atencion, $id_establecimiento, $fecha_atencion,$id_medico, $id_consultorio)
  {
    $cad = "  select id,ruta_hoja_his, fecha_firma, flag
              from hoja_his t1 
              Where t1.id_tipo_atencion = {$tipo_atencion}
                And t1.id_establecimiento = {$id_establecimiento}
                And t1.fecha_atencion = '{$fecha_atencion}'
                And t1.id_medico = {$id_medico}
                And t1.id_sub_consultorio ={$id_consultorio}
              ";
    //echo $cad;
    $data = DB::select($cad);
    return $data;
  }

/*  public function GetDatosMedicoAndEstablecimiento2($dni, $id_establecimiento = 0)
  {
    $cad = "select t1.*,t3.nombre profesion,t4.nombre especializacion, tdi.descripcion tipo_doc_ident, t1.colegiatura
            from medicos t1
            inner join profesiones t3 on t1.id_profesion=t3.id
            inner join especializaciones t4 on t1.id_especializacion=t4.id
            left join tipo_documento_identidad tdi ON t1.id = tdi.id
            where t1.estado=1 and dni='" . $dni . "' 
            and t1.id_establecimiento=" . $id_establecimiento;

    $data = DB::select($cad);
    return $data;
  }
*/
  public function GetDatosMedicoAndEstablecimiento2($dni, $id_establecimiento = 0)
  {
    $cad = "select t1.*,t3.nombre profesion,t4.nombre especializacion, tdi.descripcion tipo_doc_ident
            from medicos t1
            inner join profesiones t3 on t1.id_profesion=t3.id
            inner join especializaciones t4 on t1.id_especializacion=t4.id
            left join tipo_documento_identidad tdi ON t1.tipo_documento = tdi.id
            where t1.estado=1 and dni='" . $dni . "' 
            and t1.id_establecimiento=" . $id_establecimiento;

    $data = DB::select($cad);
    return $data;
  }
public function GetMostrarMedicoById($id)
  {
    $datos = DB::table('medicos')
      ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      ->join('profesiones', 'medicos.id_profesion', '=', 'profesiones.id')
      ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')
      ->where('medicos.id', '=', $id)
      ->where('medicos.estado', '=', 1)
      ->select('medicos.*', 'especializaciones.nombre as especializacion', 'establecimientos.nombre as ipress')
      ->first();
    return $datos;
  }

  public static function RegistrarHojaHis($id_atencion,$id_establecimiento,$fecha_atencion,$id_medico,$id_servicio) {
        $cad = "Select sp_insert_hoja_his(?,?,?,?,?)";
        $data = DB::select($cad, array($id_atencion,$id_establecimiento,$fecha_atencion,$id_medico,$id_servicio));
        return $data[0]->sp_insert_hoja_his;
  }

  public static function ActualizaHojaHis($id_hoja_his,$fecha_firma,$ruta_hoja_his) {
        $cad = "Select sp_actualizar_hoja_his(?,?,?,?)";
        $data = DB::select($cad, array($id_hoja_his,$fecha_firma,$ruta_hoja_his));
        return $data[0]->sp_actualizar_hoja_his;
  }

  public function GetMedicoById2($id)
  {
    $datos = DB::table('medicos')
      ->leftJoin('tipo_documento_identidad','medicos.tipo_documento','=','tipo_documento_identidad.id')
      ->where('medicos.id', '=', $id)
      ->select('medicos.*','tipo_documento_identidad.descripcion as tipo_doc_ident')
      ->first();
    return $datos;
  }
  
  public function GetMedicoById3($id) {
    $datos = DB::table('medicos')
      ->leftJoin('profesiones','medicos.id_profesion','=','profesiones.id')
      ->leftJoin('especializaciones','medicos.id_especializacion','=','especializaciones.id')
      ->where('medicos.id', '=', $id)
      ->select('medicos.*','profesiones.nombre as profesion','especializaciones.nombre as especializacion')
      ->first();
    return $datos;
  }
  protected $fillable = ['dni', 'colegiatura', 'nombre', 'paterno', 'materno', 'especialidad', 'id_establecimiento', 'id_especializacion', 'tipo_contrato', 'tipo_documento'];
  protected $delimiter  = ';';


  public function buscarSitedsByEmergenciaId($emergenciaId)
  {
    $result = DB::table('emergencias')
                ->select('id', 'cod_siteds_susalud', 'updated_at_cod_siteds')
                ->whereRaw('updated_at_cod_siteds::date = CURRENT_DATE')
                ->where('id', $emergenciaId)
                ->first();
    if (!isset($result)) {
      $result = DB::table('evaluaciones')
              ->select('id', 'cod_siteds_susalud', 'updated_at_cod_siteds')
              ->where('id_emergencia', $emergenciaId)
              ->whereRaw('updated_at_cod_siteds::date = CURRENT_DATE')
              ->orderBy('created_at', 'desc')
              ->limit(1)
              ->first();
    }
    return $result;
  }

  public function GetProfesionalesXEspecialidad($id_establecimiento, $id_especialidad)
  {
    // Solo Medicos
    $query = DB::table('medicos as m')
              ->join('especializaciones as es', 'm.id_especializacion', '=', 'es.id')
              ->join('medico_consultorios as mco', function ($join) {
                  $join->on('m.id', '=', 'mco.id_medico')
                      ->where('mco.estado', '=', 1);
              })
              ->join('consultorios as co', 'mco.id_consultorio', '=', 'co.id')
              ->leftJoin('profesiones as pr', 'm.id_profesion', '=', 'pr.id')
              ->leftJoin('tipo_contratos as tc', 'm.tipo_contrato', '=', 'tc.id')
              ->where('mco.id_establecimiento', $id_establecimiento)
              ->whereRaw("COALESCE(m.estado, '1') != '3'")
              ->whereIn('pr.id', [20, 4, 51, 52, 1])
              ->select('m.*', 'es.nombre as especializacion', DB::raw("string_agg(co.nombre, ', ') as consultorios"), 'pr.nombre as profesion', 'tc.nombre as tipo_contrato');
    
    if ($id_especialidad > 0) {
      $query->where('mco.id_consultorio', $id_especialidad);
    }

    $data = $query->groupBy('m.id', 'es.nombre', 'pr.nombre', 'tc.nombre')
                  ->orderBy('m.paterno')
                  ->orderBy('m.materno')
                  ->orderBy('m.nombre')
                  ->get();
    
    return $data;
  }

  public function GetMedicoColegioByDNI($dni)
  {
    $datos = DB::table('medicos')
        ->join('colegio_profesionales', 'medicos.id_colegio_profesional', '=', 'colegio_profesionales.id')
        ->join('tipo_documento_identidad', 'medicos.tipo_documento', '=', 'tipo_documento_identidad.id')
        ->where('medicos.dni', '=', $dni)
        ->where('medicos.estado', '=', '1')
        ->select(
            'medicos.*',
            'colegio_profesionales.descripcion as colegio_descripcion',
            'tipo_documento_identidad.descripcion as tipo_documento_descripcion'
        )
        ->first();

    return $datos;
  }

  public static function reporteData()
    {
        return DB::select("
            SELECT 
                m.id, m.cip, m.dni, m.fecha_nacimiento, m.sexo,
                m.nro_hijos, m.email_institucional,
                m.celular, m.email, m.fecha_inicio, m.fecha_cese,
                m.colegiatura, m.rne,
                CASE WHEN m.estado_colegiatura = 1 THEN 'HABILITADO'
                     WHEN m.estado_colegiatura = 0 THEN 'INHABILITADO'
                     ELSE '' END AS estado_colegiatura,
                m.fecha_inicio_habilidad, m.fecha_termino_habilidad,

                CASE WHEN m.condicion = 1 THEN 'SI' ELSE 'NO' END AS civil,
                CASE WHEN m.condicion = 2 THEN 'SI' ELSE 'NO' END AS policial,
                CASE WHEN m.grupo_trabajo = 1 THEN 'SI' ELSE 'NO' END AS asistencial,
                CASE WHEN m.grupo_trabajo = 2 THEN 'SI' ELSE 'NO' END AS administrativo,

                CONCAT(m.paterno,' ',m.materno,', ',m.nombre) AS nombre_completo,
                EXTRACT(YEAR FROM AGE(CURRENT_DATE, m.fecha_nacimiento))::INTEGER AS edad,
                CONCAT(tdi.descripcion,' - ', m.dni) AS tipodoc,

                e.nombre AS especializacion,
                tg.descripcion AS gradodescr,
                ec.nombre AS estadocivil,
                d.descripcion AS departamento,
                p.descripcion AS provincia,
                di.descripcion AS distrito,
                sp.descripcion AS situacionpersonal,
                cl.descripcion AS condicionlaboral,
                pr.nombre AS profesion,
                cp.descripcion AS colegioprofesional,
                es.nombre AS ipress,
                u.nombre AS unidad,
                su.nombre AS subunidad

            FROM medicos m
            LEFT JOIN especializaciones e ON m.id_especializacion = e.id
            LEFT JOIN titular_grados tg ON m.id_grado = tg.id
            LEFT JOIN tipo_documento_identidad tdi ON m.tipo_documento = tdi.id
            LEFT JOIN estado_civiles ec ON m.id_estado_civil = ec.id
            LEFT JOIN departamentos d ON m.id_departamento = d.id_departamento
            LEFT JOIN provincias p ON m.id_provincia = p.id_provincia
            LEFT JOIN distritos di ON m.id_distrito = di.id_distrito
            LEFT JOIN situacion_personal sp ON m.id_situacion_personal = sp.id
            LEFT JOIN condicion_laboral cl ON m.id_condicion_laboral = cl.id
            LEFT JOIN profesiones pr ON m.id_profesion = pr.id
            LEFT JOIN colegio_profesionales cp ON m.id_colegio_profesional = cp.id
            LEFT JOIN establecimientos es ON m.id_establecimiento = es.id
            LEFT JOIN tipo_consultorio_divisiones u ON m.id_unidad = u.id
            LEFT JOIN tipo_consultorio_departamentos su ON m.id_sub_unidad = su.id
        ");
    }
    public static function descargar_pdf($id)
    {
        return DB::select("
          SELECT 
              medicos.id,
              medicos.cip,
              medicos.dni,
              medicos.fecha_nacimiento,
              medicos.sexo,
              medicos.celular,
              medicos.nro_hijos,
              medicos.cel_emergencia,
              medicos.email,
              medicos.email_institucional,
              medicos.domicilio,
              medicos.fecha_inicio,
              medicos.fecha_cese,
              medicos.cargo,
              medicos.created_at,
              medicos.colegiatura,
              medicos.rne,
              CASE 
                  WHEN medicos.estado_colegiatura = 1 THEN 'HABILITADO' 
                  WHEN medicos.estado_colegiatura = 0 THEN 'INHABILITADO'
                  ELSE '' 
              END as estado_colegiatura,
              medicos.fecha_inicio_habilidad,
              medicos.fecha_termino_habilidad,
              
              CASE WHEN medicos.condicion = 1 THEN 'CIVIL' ELSE 'POLICIAL' END as condicion,
              CASE WHEN medicos.grupo_trabajo = 1 THEN 'ASISTENCIAL' ELSE 'ADMINISTRATIVO' END as grupo_trabajo,
              
              CONCAT(medicos.paterno, ' ', medicos.materno, ' ', medicos.nombre) as nombre_completo,
              EXTRACT(YEAR FROM AGE(CURRENT_DATE, medicos.fecha_nacimiento))::INTEGER as edad,
              CONCAT(EXTRACT(YEAR FROM AGE(CURRENT_DATE, medicos.fecha_nacimiento)), ' años ', 
                    EXTRACT(MONTH FROM AGE(CURRENT_DATE, medicos.fecha_nacimiento)), ' meses ', 
                    EXTRACT(DAY FROM AGE(CURRENT_DATE, medicos.fecha_nacimiento)), ' días') as edad_detallada,
              
              tipo_documento_identidad.descripcion as tipo_documento,
              especializaciones.nombre as especializacion,
              titular_grados.descripcion as grado,
              estado_civiles.nombre as estado_civil,
              departamentos.descripcion as departamento,
              provincias.descripcion as provincia,
              distritos.descripcion as distrito,
              situacion_personal.descripcion as situacion_laboral,
              condicion_laboral.descripcion as condicion_laboral,
              profesiones.nombre as profesion,
              colegio_profesionales.descripcion as colegio_profesional,
              establecimientos.nombre as establecimiento,
              tipo_consultorio_divisiones.nombre as unidad,
              tipo_consultorio_departamentos.nombre as subunidad
          FROM medicos
          LEFT JOIN especializaciones ON medicos.id_especializacion = especializaciones.id
          LEFT JOIN titular_grados ON medicos.id_grado = titular_grados.id
          LEFT JOIN tipo_documento_identidad ON medicos.tipo_documento = tipo_documento_identidad.id
          LEFT JOIN estado_civiles ON medicos.id_estado_civil = estado_civiles.id
          LEFT JOIN departamentos ON medicos.id_departamento = departamentos.id_departamento
          LEFT JOIN provincias ON medicos.id_provincia = provincias.id_provincia
          LEFT JOIN distritos ON medicos.id_distrito = distritos.id_distrito
          LEFT JOIN situacion_personal ON medicos.id_situacion_personal = situacion_personal.id
          LEFT JOIN condicion_laboral ON medicos.id_condicion_laboral = condicion_laboral.id
          LEFT JOIN profesiones ON medicos.id_profesion = profesiones.id
          LEFT JOIN colegio_profesionales ON medicos.id_colegio_profesional = colegio_profesionales.id
          LEFT JOIN establecimientos ON medicos.id_establecimiento = establecimientos.id
          LEFT JOIN tipo_consultorio_divisiones ON medicos.id_unidad = tipo_consultorio_divisiones.id
          LEFT JOIN tipo_consultorio_departamentos ON medicos.id_sub_unidad = tipo_consultorio_departamentos.id
          WHERE medicos.id = ?
      ", [$id]);
    }

    public function getEstadoHistorialMedicoDesactivar () {
      return DB::table('estado_historial_medico')
        ->select('id', 'descripcion')
        ->where('id', '!=', 1) // ejemplo
        ->get();
    }

    public function getEstadoHistorialMedicoActivar () {
      return DB::table('estado_historial_medico')
        ->select('id', 'descripcion')
        ->where('id', '=', 1)
        ->get();
    }

    public function getEstadoMedico ($dni, $establecimiento) {
      return DB::table('medicos')
                ->where('dni', $dni)
                ->where('id_establecimiento', $establecimiento)
                ->value('estado');
    }
}