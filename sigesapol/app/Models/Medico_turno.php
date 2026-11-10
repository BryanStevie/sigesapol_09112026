<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use PDO;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Medico_turno extends Model
{
  public function GetTurnoByIdIpressAndIdConsultorio($id_ipress, $id_consultorio, $fecha = "")
  {

    $cad = "select distinct turno 
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
inner join sub_consultorios T3 on T1.id_sub_consultorio=T3.id
where T1.id_establecimiento=" . $id_ipress . "
and T3.id_consultorio=" . $id_consultorio . "
-- and dia= to_number(to_char(current_date,'d'),'999999999')
and fecha_atencion = '" . $fecha . "'::date 
and T1.estado = 1
";
    $data = DB::select($cad);
    return $data;
  }

  public function GetMedicoByIdIpressAndIdConsultorioAndTurno($id_ipress, $id_consultorio, $turno, $fecha = "")
  {

    $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura ,rne, T1.turno
,numero_atencion
--,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
,(select count(1) from admisiones 
where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
inner join sub_consultorios T3 on T1.id_sub_consultorio=T3.id
where T1.id_establecimiento=" . $id_ipress . "
and T3.id_consultorio=" . $id_consultorio . "
and turno = '" . $turno . "'
and id_tipo_atencion = 'AMBULATORIO'
--and dia= to_number(to_char(current_date,'d'),'999999999')
and fecha_atencion = '" . $fecha . "'::date 
and T1.estado = 1";
    $data = DB::select($cad);
    return $data;
  }

  /*public function GetMedicoByIdIpressAndIdConsultorioAndTurnoAnestesiologo($id_ipress, $id_consultorio)
  {
    $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura ,rne
,numero_atencion
--,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
,(select count(1) from admisiones 
where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
inner join sub_consultorios T3 on T1.id_sub_consultorio=T3.id
where T1.id_establecimiento=" . $id_ipress . "
and T3.id_consultorio=" . $id_consultorio . "
and T1.estado = 1";
    $data = DB::select($cad);
    return $data;
  }
*/
public function GetMedicoByIdIpressAndIdConsultorioAndTurnoAnestesiologo($id_ipress, $id_consultorio)
  {
    $cad = "select m.id,nombre,paterno,materno,colegiatura ,rne
            from medicos m 
            inner join medico_consultorios mc on mc.id_medico = m.id
            where m.id_establecimiento=" . $id_ipress . "
            and mc.id_consultorio=" . $id_consultorio . "
            and m.estado = 1";
    $data = DB::select($cad);
    return $data;
  }
  
  public function GetMedicoByIdIpressAndIdConsultorioAndTurnoQuirurgico($id_ipress, $id_consultorio)
  {

    $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura ,rne
,numero_atencion
--,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
,(select count(1) from admisiones 
where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
inner join sub_consultorios T3 on T1.id_sub_consultorio=T3.id
where T1.id_establecimiento=" . $id_ipress . "
and T3.id_consultorio=" . $id_consultorio . "
--and dia= to_number(to_char(current_date,'d'),'999999999')
and T1.estado = 1";
    $data = DB::select($cad);
    return $data;
  }

  public function GetMedicoByIdIpressAndIdConsultorioAndSoloEmergencia($id_ipress, $id_consultorio)
  {

    $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura ,rne,dni
    from medico_consultorios T1
    inner join medicos T2 on T1.id_medico=T2.id
    where T1.id_establecimiento=" . $id_ipress . "
    and T1.id_consultorio=" . $id_consultorio . "
    and T1.estado = 1";
    $data = DB::select($cad);
    return $data;
  }

  public function GetMedicoByIdIpressAndIdConsultorioAndTurnoHospitalizacionMotivo($id_ipress, $id_consultorio, $turno, $fecha = "", $motivo)
  {
    /*
    if($motivo!='FA'){
        $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura,rne
    
,numero_atencion
,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
,(select count(1) from admisiones 
where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
where T1.id_establecimiento=" . $id_ipress . "
and T1.id_consultorio=" . $id_consultorio . "
and turno = '" . $turno . "'
and id_tipo_atencion = 'HOSPITALIZACION'
--and dia= to_number(to_char(current_date,'d'),'999999999')
and fecha_atencion = '" . $fecha . "'::date 
and T1.estado = 1";
    }
    else
    {

        $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura,rne
,numero_atencion
,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
,(select count(1) from admisiones 
where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
where T1.id_establecimiento=" . $id_ipress . "
and turno = '" . $turno . "'
and (id_tipo_atencion = 'HOSPITALIZACION' or id_tipo_atencion = 'EMERGENCIA')
--and dia= to_number(to_char(current_date,'d'),'999999999')
and fecha_atencion = '" . $fecha . "'::date 
and T1.estado = 1";
     
    } */
    $cad = "
            select distinct T2.id,nombre,paterno,materno,colegiatura,rne
        ,numero_atencion
        --,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
        ,(select count(1) from admisiones 
        where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
        from medico_turnos T1
        inner join medicos T2 on T1.id_medico=T2.id
        where T1.id_establecimiento=75
        and T1.estado = 1 AND T2.estado=1";


    $data = DB::select($cad);
    return $data;
  }

  public function GetMedicoAltaTurnoHospitalizacionMotivo($id_ipress, $id_consultorio, $turno, $fecha = "", $motivo)
  {


    if ($motivo != 'FA') {
      $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura ,rne
                    from medico_turnos T1
                    inner join medicos T2 on T1.id_medico=T2.id
					inner join sub_consultorios T3 on T1.id_sub_consultorio=T3.id
                    where T1.id_establecimiento=" . $id_ipress . "
                    and T3.id_consultorio=" . $id_consultorio . "
                    and turno = '" . $turno . "'
                    and id_tipo_atencion = 'HOSPITALIZACION'
                    and fecha_atencion = '" . $fecha . "'::date 
                    and T1.estado = 1";
    } else {

      $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura ,rne
                    from medico_turnos T1
                    inner join medicos T2 on T1.id_medico=T2.id
                    where T1.id_establecimiento=" . $id_ipress . "
                    and turno = '" . $turno . "'
                    and (id_tipo_atencion = 'HOSPITALIZACION' or id_tipo_atencion = 'EMERGENCIA')
                    and T1.estado = 1";
    }

    $data = DB::select($cad);


    return $data;
  }

  public function GetMedicoByIdIpressAndIdConsultorioAndTurnoHospitalizacion($id_ipress, $id_consultorio, $turno, $fecha = "")
  {


    $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura,rne
    
,numero_atencion
--,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
,(select count(1) from admisiones 
where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
inner join sub_consultorios T3 on T1.id_sub_consultorio=T3.id
where T1.id_establecimiento=" . $id_ipress . "
and T3.id_consultorio=" . $id_consultorio . "
and turno = '" . $turno . "'
and id_tipo_atencion = 'HOSPITALIZACION'
--and dia= to_number(to_char(current_date,'d'),'999999999')
and fecha_atencion = '" . $fecha . "'::date 
and T1.estado = 1";
    $data = DB::select($cad);
    return $data;
  }

  public function GetMedicoByIdIpressAndIdConsultorioAndTurnoEmergencia($id_ipress, $id_consultorio, $turno, $fecha = "")
  {

    $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura,rne 
    ,numero_atencion
    --,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
    ,(select count(1) from admisiones 
    where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
    from medico_turnos T1
    inner join medicos T2 on T1.id_medico=T2.id
    inner join sub_consultorios T3 on T1.id_sub_consultorio=T3.id
    where T1.id_establecimiento=" . $id_ipress ;
    if ($id_consultorio !== '') {
      $cad .= "and T3.id_consultorio=" . $id_consultorio . "
      and turno = '" . $turno . "'
      and id_tipo_atencion = 'EMERGENCIA'
      --and dia= to_number(to_char(current_date,'d'),'999999999')
      and fecha_atencion = '" . $fecha . "'::date 
      and T1.estado = 1";
      $data = DB::select($cad);
      return $data;
    }else{
      $cad .= "and turno = '" . $turno . "'
      and id_tipo_atencion = 'EMERGENCIA'
      --and dia= to_number(to_char(current_date,'d'),'999999999')
      and fecha_atencion = '" . $fecha . "'::date 
      and T1.estado = 1";
      $data = DB::select($cad);
      return $data;
    }
  }

  public function GetMedicoByIdIpressAndIdConsultorioAndTurnoEmergenciaNew($id_ipress, $turno, $fecha = "")
  {

    $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura,rne 
    ,numero_atencion
    --,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
    ,(select count(1) from admisiones 
    where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
    from medico_turnos T1
    inner join medicos T2 on T1.id_medico=T2.id
    inner join sub_consultorios T3 on T1.id_sub_consultorio=T3.id
    where T1.id_establecimiento=" . $id_ipress . " and turno = '" . $turno . "'
      and id_tipo_atencion = 'EMERGENCIA'
      --and dia= to_number(to_char(current_date,'d'),'999999999')
      and fecha_atencion = '" . $fecha . "'::date 
      and T1.estado = 1"; 
      //dd($cad );

      $data = DB::select($cad);
      return $data; 
  }
  public function GetMedicoByIdIpressAndIdConsultorioAndTurnoOrigen($id_ipress, $id_origen, $turno, $fecha = "")
  {
    /*
        $cad = "select distinct T2.id,nombre,paterno,materno,colegiatura 
,numero_atencion
,(select count(1) from citas where fecha_cita=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_cita
,(select count(1) from admisiones 
where fecha_admision=fecha_atencion and id_medico=T1.id_medico and turno=T1.turno and id_consultorio=T2.id_consultorio)numero_admision 
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
where T1.id_establecimiento=" . $id_ipress . "
and T1.id_consultorio=" . $id_consultorio . "
and turno = '" . $turno . "'

and id_tipo_atencion = 'HOSPITALIZACION'
and fecha_atencion = '" . $fecha . "'::date 
and T1.estado = 1";
*/
    $cad = "select T2.id,nombre,paterno,materno,colegiatura,fecha_atencion,rne
        from medico_turnos T1
        inner join medicos T2 on T1.id_medico=T2.id
        where turno = '" . $turno . "'
        and fecha_atencion = '" . $fecha . "'::date 
        and T1.id_establecimiento=" . $id_ipress;
    if ($id_origen == 'O1') :
      $cad .= " and id_tipo_atencion = 'EMERGENCIA'";
    endif;
    if ($id_origen == 'O2') :
      $cad .= " and id_tipo_atencion = 'AMBULATORIO'";
    endif;
    if ($id_origen == 'O3') :
      $cad .= " and id_tipo_atencion = 'HOSPITALIZACION'";
    endif;
    $cad .= " and T1.estado = 1";

    $data = DB::select($cad);
    return $data;
  }


  public function getMedicoTurnoByMedicoAndMes($id_establecimiento, $id_medico, $id_consultorio, $mes, $ano, $id_tipo_atencion)
  {
    //$cad = "Select id, id_medico, dia, turno, numero_atencion, created_at, updated_at, fecha_atencion, estado, id_establecimiento, id_user_created, id_user_update, id_consultorio, id_tipo_atencion 
    $sql_consultorio="";
    if(empty($id_consultorio)){
      $sql_consultorio="AND id_sub_consultorio is null";
    }else{
      $sql_consultorio="AND id_sub_consultorio=$id_consultorio";
    }
    $query = '
      SELECT 
        id, id_medico, dia, turno, numero_atencion, 
        created_at, updated_at, fecha_atencion, estado, mt.id_establecimiento, 
        id_user_created, id_user_update, id_sub_consultorio, id_tipo_atencion, id_turno 
      FROM medico_turnos mt
      WHERE 
        mt.id_establecimiento = ' . $id_establecimiento . ' 
        AND id_tipo_atencion = \'' . $id_tipo_atencion . '\' 
        AND id_medico = ' . $id_medico . ' 
        '.$sql_consultorio.' 
        AND extract(month from mt.fecha_atencion) = \'' . $mes . '\' 
        AND extract(year from mt.fecha_atencion) = \'' . $ano . '\' 
      ORDER BY 
        mt.fecha_atencion, 
        CASE 
          WHEN mt.turno=\'MD\' THEN 1 
          When mt.turno=\'M\' THEN 2 
          When mt.turno=\'T\' THEN 3 
          Else 4 
        END;';
    $data = DB::select($query);
    return $data;
  }

  public function GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $id_medico, $id_sub_consultorio, $fecha, $turno, $id_tipo_atencion)
  {
    $data = DB::table('medico_turnos')
      ->join('sub_consultorios', 'medico_turnos.id_sub_consultorio', '=', 'sub_consultorios.id')
      ->join('medicos', 'medico_turnos.id_medico', '=', 'medicos.id')
      ->where('medico_turnos.id_establecimiento', '=', $id_ipress)
      ->where('medico_turnos.id_medico', '=', $id_medico)
      //->where('medico_turnos.id_tipo_atencion', '=', $id_tipo_atencion) //Antes del whereNotIn
      ->whereNotIn('medico_turnos.id_tipo_atencion', ['EMERGENCIA', 'AMBULATORIO', 'HOSPITALIZACION', 'CENTRO QUIRURGICO'])
      ->where('medico_turnos.id_sub_consultorio', '<>', $id_sub_consultorio)
      ->where('medico_turnos.fecha_atencion', '=', $fecha)
      ->where('medico_turnos.turno', '=', $turno)
      ->where('medico_turnos.estado', '=', '1')
      ->select(
        'medico_turnos.*',
        'sub_consultorios.descripcion as consultorio',
        'medicos.paterno','medicos.materno','medicos.nombre'
      )
      ->first();
    return $data;
  }

  public function GetValidaProgramacionMedicoByFechaAndOtraIpress($id_ipress, $dni_medico, $fecha, $turno, $id_tipo_atencion)
  {
    $data = DB::table('medico_turnos')
      ->join('sub_consultorios', 'medico_turnos.id_sub_consultorio', '=', 'sub_consultorios.id')
      ->join('medicos', 'medico_turnos.id_medico', '=', 'medicos.id')
      ->join('establecimientos', 'medico_turnos.id_establecimiento', '=', 'establecimientos.id')
      ->where('medico_turnos.id_establecimiento', '<>', $id_ipress)
      ->where('medicos.dni', '=', $dni_medico)
      ->where('medico_turnos.id_tipo_atencion', '<>', $id_tipo_atencion)
      ->where('medico_turnos.fecha_atencion', '=', $fecha)
      ->where('medico_turnos.turno', '=', $turno)
      ->where('medico_turnos.estado', '=', '1')
      ->select(
        'medico_turnos.*',
        'sub_consultorios.descripcion as consultorio',
        'establecimientos.nombre as nom_establecimiento'
      )
      ->first();
    return $data;
  }

  public function postUpdateDesactivateMedico($id_ipress, $id_medico, $fecha = "")
  {

    $cad = "Update medico_turnos Set estado=0 "
      . "Where id_medico=" . $id_medico . " And id_establecimiento=" . $id_ipress . " And fecha_atencion>'" . $fecha . "'";
    $data = DB::select($cad);
    return $data;
  }

  public function postUpdateDesactivateMedicoAndConsultorio($id_ipress, $id_medico, $id_consultorio, $fecha = "")
  {
    /*
        $cad = "Update medico_turnos Set estado=0 "
    	. "Where id_medico=".$id_medico." And id_establecimiento=".$id_ipress." 
		And id_consultorio=".$id_consultorio." 
		And fecha_atencion>'".$fecha."'";
		*/
    $cad = "Update medico_turnos Set estado=0 "
      . "Where id_medico=" . $id_medico . " And id_establecimiento=" . $id_ipress . " 
		And id_sub_consultorio in (select id from sub_consultorios where id_consultorio=" . $id_consultorio . " and id_establecimiento=" . $id_ipress . ")
		And fecha_atencion>'" . $fecha . "'";

    $data = DB::select($cad);
    return $data;
  }

  /*
	public function GetMedicoByIdIpressAndIdConsultorio($id_ipress, $id_consultorio, $fecha = "") {

        $cad = "SELECT distinct id_medico,t2.nombre,t2.paterno,t2.materno
FROM medico_turnos t1
inner join medicos t2 on t1.id_medico=t2.id
inner join sub_consultorios t3 on t1.id_sub_consultorio=t3.id
where t1.id_establecimiento=" . $id_ipress . "
and t3.id_consultorio=" . $id_consultorio . "
and t1.id_tipo_atencion = 'AMBULATORIO'
and t1.fecha_atencion = '" . $fecha . "'::date 
and t1.estado = 1 
order by id_medico asc";
        $data = DB::select($cad);
        return $data;
    }
*/

  public function GetMedicoByIdIpressAndIdConsultorio($id_ipress, $id_consultorio, $id_medico = "", $id_turno = "", $fecha = "")
  {

    $cad = "SELECT distinct t3.id, t2.nombre,t2.paterno,t2.materno,t1.turno, t2.id as id_medico
FROM medico_turnos t1
inner join medicos t2 on t1.id_medico=t2.id
inner join sub_consultorios t3 on t1.id_sub_consultorio=t3.id
inner join sub_consultorio_turnos t4 on t4.id_sub_consultorio=t3.id
where t1.id_establecimiento=" . $id_ipress . "
and t3.id_consultorio=" . $id_consultorio;
    if ($id_turno != "null") :
      $cad .= " and t1.turno='" . $id_turno . "'";
    endif;
    if ($id_medico != "null") :
      $cad .= " and t2.id=" . $id_medico;
    endif;
    $cad .= " 
and t1.id_tipo_atencion = 'AMBULATORIO'
and t1.fecha_atencion = '" . $fecha . "'::date 
and t1.estado = 1 
and T3.id_estado_reg=1
order by t1.turno asc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetSubConsultorioByIdConsultorio($id_consultorio, $id_establecimiento)
  {

    $cad = "select t1.id,descripcion nombre, t1.id_consultorio
		from sub_consultorios t1
		where id_consultorio=" . $id_consultorio . " 
		and t1.id_establecimiento=" . $id_establecimiento . " 
		and t1.id_estado_reg = 1 order by 1 asc";
    $data = DB::select($cad);
    return $data;
  }
  
  public function GetSubConsultorioByIdConsultorio_fecha($id_consultorio, $id_establecimiento,$fecha_cita, $id_servicio_radiologia = 0)
  {

    $cad = "  select t1.id,descripcion nombre, t1.id_consultorio,t1.*
		from sub_consultorios t1
		where t1.id_estado_reg = 1 and t1.id_consultorio=" . $id_consultorio . "
		and t1.id_establecimiento=" . $id_establecimiento; 
    if ($id_servicio_radiologia > 0) {
      $cad .=" and t1.id_servicio_radiologia =" . $id_servicio_radiologia;
    }
    $cad .=" AND (
                  t1.fecha_inicio_actividades IS NULL or t1.fecha_inicio_actividades = ''
                  OR '$fecha_cita' >= date(t1.fecha_inicio_actividades)
              )
            AND (
                  t1.fecha_final_actividades IS NULL or t1.fecha_final_actividades = ''
                  OR '$fecha_cita' <= date(t1.fecha_final_actividades)
              )
            ORDER BY t1.id ASC";

    $data = DB::select($cad);
    return $data;
  }

 public function GetSubConsultorioByIdConsultorioxfecha($id_consultorio, $id_establecimiento, $fecha_actual_formado)
  {

    $cad = "SELECT 
    t1.id,
    descripcion AS nombre,
    t1.id_consultorio,
    t1.fecha_inicio_actividades,
    t1.fecha_final_actividades,
    CASE 
        WHEN t1.fecha_inicio_actividades IS NULL AND t1.fecha_final_actividades IS NULL THEN ''
        WHEN (t1.fecha_inicio_actividades IS NOT NULL OR t1.fecha_inicio_actividades <> '') AND (t1.fecha_final_actividades IS NULL OR t1.fecha_final_actividades = '') THEN
            CASE 
                WHEN to_date('$fecha_actual_formado', 'DD-MM-YYYY') >= to_date(t1.fecha_inicio_actividades, 'DD-MM-YYYY') THEN ''
                ELSE '1'
            END
        WHEN to_date('$fecha_actual_formado', 'DD-MM-YYYY') >= to_date(t1.fecha_inicio_actividades, 'DD-MM-YYYY') AND to_date('$fecha_actual_formado', 'DD-MM-YYYY') <= to_date(t1.fecha_final_actividades, 'DD-MM-YYYY') THEN ''
        ELSE '1'
    END AS validacion
FROM 
    sub_consultorios t1
WHERE 
    id_consultorio = " . $id_consultorio . " 
    AND t1.id_establecimiento = " . $id_establecimiento . " 
    AND t1.id_estado_reg = 1
ORDER BY 
    1 ASC;";
    $data = DB::select($cad);
   
    return $data;
  }

  public function GetSubConsultorioByIdConsultorioCAR($id_consultorio, $id_establecimiento, $id_sub_consultorios)
  {

    $cad = "select t1.id,descripcion nombre
		from sub_consultorios t1
		where id_consultorio=" . $id_consultorio . " 
		and t1.id_establecimiento=" . $id_establecimiento . "
		and t1.id=" . $id_sub_consultorios . "  
		and t1.id_estado_reg = 1 order by 1 asc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetSubConsultorioTurnoByIdConsultorio__($id_consultorio)
  {

    $cad = "select t1.id,descripcion nombre,t2.tiempo_atencion,hora_ini,hora_fin 
		from sub_consultorios t1
		inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio
		where id_consultorio=" . $id_consultorio . " and t1.id_estado_reg = 1 order by 1 asc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetSubConsultorioTurnoByIdConsultorio($id_consultorio,$fecha_atencion="")
  {

    $cad = "select t1.id,t2.id id_sub_consultorio_turno,t1.descripcion nombre,t2.tiempo_atencion,t2.hora_ini,t2.hora_fin,t2.id_turno,t3.abreviatura turno, t2.dia, t3.abreviatura||t2.dia as turno_con_dia
		from sub_consultorios t1
		inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio
		inner join turnos t3 on t2.id_turno=t3.id
		
		and t1.id='" . $id_consultorio . "'";


    if($fecha_atencion!= "")

      $cad .=  " and t2.dia = case when extract(dow from '". $fecha_atencion."'::date) = '1' then 'Lunes' 
				when extract(dow from '".$fecha_atencion."'::date) = '2' then 'Martes'
				when extract(dow from '".$fecha_atencion."'::date) = '3' then 'Miercoles' 
        when extract(dow from '".$fecha_atencion."'::date) = '4' then 'Jueves'
        when extract(dow from '".$fecha_atencion."'::date) = '5' then 'Viernes' 
      
        when extract(dow from '".$fecha_atencion."'::date) = '6' then 'Sabado' end ";





        $cad .= "and t1.id_estado_reg = 1 and t2.id_estado_reg = 1 order by t2.hora_ini::time asc";
    //echo $cad; exit;
    $data = DB::select($cad);
    return $data;
  }

  public function GetHorarioMedicoByIdIpressAndIdConsultorio($id_ipress, $id_consultorio, $fecha = "", $id_turno, $dura_cons)
  {
    /*********************/

    $cad = "WITH vals AS (
   SELECT 
   		('" . $fecha . "'||' 00:00:00')::timestamp   AS frame_start  
        , ('" . $fecha . "'||' 23:59:59')::timestamp AS frame_end
        , interval '" . $dura_cons . " min' AS t_interval
   )
,    grid AS (
   SELECT start_time
        , lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time
   FROM   (
      SELECT generate_series(frame_start, frame_end, t_interval) AS start_time
           , frame_end
      FROM vals
      ) x
   )
SELECT to_char(start_time, 'HH-MI AM') fecha_hora, count(e.fecha_hora) nro_citas,end_time,to_char(start_time, 'YYYY-MM-DD HH24:MI')fecha_cita, 
(case when TO_TIMESTAMP('" . $fecha . " '||t.hora_fin,'YYYY-MM-DD HH-MI AM') > g.start_time then 1 else 2 end) situacion,
(SELECT 
array_to_string(array_agg(t1.id_medico||'#'||(select count(*) from citas cit where id_medico=t1.id_medico and cit.fecha_hora >= start_time AND cit.fecha_hora < end_time And cit.id_estado_reg = 1) ORDER BY id_medico ASC), ',')id_medico
FROM medico_turnos t1
inner join turnos t2 on t1.id_turno=t2.id
where fecha_atencion = '" . $fecha . "'::date
and t1.id_establecimiento=" . $id_ipress . " and id_consultorio=" . $id_consultorio . " and id_tipo_atencion='AMBULATORIO'
and TO_TIMESTAMP(to_char(start_time, 'YYYY-MM-DD HH-MI AM'),'YYYY-MM-DD HH-MI AM') between TO_TIMESTAMP('" . $fecha . " '||hora_ini,'YYYY-MM-DD HH-MI AM') and TO_TIMESTAMP('" . $fecha . " '||hora_fin_turno,'YYYY-MM-DD HH-MI AM')
and t1.estado=1
GROUP BY fecha_atencion)

FROM   grid       g
LEFT   JOIN citas e ON e.fecha_hora >= g.start_time AND e.fecha_hora <  g.end_time And e.id_estado_reg = 1
left join turnos t ON TO_TIMESTAMP('" . $fecha . " '||t.hora_ini,'YYYY-MM-DD HH-MI AM') <= g.start_time AND TO_TIMESTAMP('" . $fecha . " '||t.hora_fin_turno,'YYYY-MM-DD HH-MI AM') >= (g.end_time - interval '1 minute')
where t.id = " . $id_turno . "
GROUP  BY start_time,end_time,hora_fin
ORDER  BY start_time";
    //echo $cad;
    $data = DB::select($cad);
    return $data;
  }

  public function GetCalendarioByIdIpressAndIdConsultorio($id_ipress, $id_consultorio, $fecha = "", $dura_cons)
  {

    $start = new Carbon('first day of this month');
    $end = new Carbon('last day of this month');
    $fecha_start = date("Y-m-d", strtotime($start));
    $fecha_end = date("Y-m-d", strtotime($end));
    $cad = "
select * from (		
WITH vals AS (
   SELECT '" . $fecha_start . " 00:00:00'::timestamp AS frame_start
        , ('" . $fecha_end . "'||' 23:59:59')::timestamp AS frame_end
        , interval '1 day' AS t_interval
   )
,    grid AS (
   SELECT start_time
        , lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time
   FROM   (
      SELECT generate_series(frame_start, frame_end, t_interval) AS start_time
           , frame_end
      FROM vals
      ) x
   )
SELECT count(e.fecha_hora) nro_citas,to_char(start_time, 'YYYY-MM-DD')as start ,to_char(start_time, 'YYYY-MM-DD') as end

/********************/

,(select COALESCE(sum(count),0)nro_programado from (WITH vals AS (
   SELECT start_time::date   AS frame_start 
        , (to_char(start_time, 'YYYY-MM-DD')||' 23:59:59')::timestamp AS frame_end
        , interval '" . $dura_cons . " min' AS t_interval
   )
,    grid AS (
   SELECT start_time_
        , lead(start_time_, 1, frame_end) OVER (ORDER BY start_time_) AS end_time
   FROM   (
      SELECT generate_series(frame_start, frame_end, t_interval) AS start_time_
           , frame_end
      FROM vals
      ) x
   )
SELECT 

(SELECT 
count(*)
FROM medico_turnos t1
inner join turnos t2 on t1.id_turno=t2.id
where fecha_atencion = g.start_time_::date
and t1.id_establecimiento=" . $id_ipress . "  and id_consultorio=" . $id_consultorio . " and id_tipo_atencion='AMBULATORIO'
and TO_TIMESTAMP(to_char(start_time_, 'YYYY-MM-DD HH-MI AM'),'YYYY-MM-DD HH-MI AM') between TO_TIMESTAMP(to_char(start_time_, 'YYYY-MM-DD')||' '||hora_ini,'YYYY-MM-DD HH-MI AM') and TO_TIMESTAMP(to_char(start_time_, 'YYYY-MM-DD')||' '||hora_fin_turno,'YYYY-MM-DD HH-MI AM')
and t1.estado=1
GROUP BY fecha_atencion)

FROM   grid       g
LEFT   JOIN citas e ON e.fecha_hora >= g.start_time_ AND e.fecha_hora <  g.end_time
GROUP  BY start_time_,end_time
ORDER  BY start_time_)R)

/********************/
FROM   grid       g
LEFT   JOIN citas e ON e.fecha_hora >= g.start_time AND e.fecha_hora <  g.end_time
And id_establecimiento = " . $id_ipress . " 
And id_servicio=" . $id_consultorio . "
GROUP  BY start_time,end_time 
--having count(e.fecha_hora)>0 
ORDER  BY start_time
)R where nro_programado > 0
";
    $data = DB::select($cad);
    //echo $cad;
    return $data;
  }

  public function ShowConsultorios($dni, $id_farmacia)
  {
    $cad = "select t5.id,t5.nombre
from users t1
inner join farmacias t2 on t1.id_farmacia=t2.id
inner join medicos t3 on t1.dni=t3.dni and t2.id_establecimiento=t3.id_establecimiento
inner join medico_turnos t4 on t4.id_medico=t3.id and t4.id_establecimiento=t3.id_establecimiento
inner join consultorios t5 on t4.id_consultorio=t5.id
where t3.dni='" . $dni . "' and t1.id_farmacia=" . $id_farmacia . "
and t4.fecha_atencion = current_date
and substring(current_time::varchar,1,8)
between (case 
when t4.turno = 'MD' then '00:00:00'
when t4.turno = 'M' then '06:00:00'
when t4.turno = 'T' then '12:00:00'
when t4.turno = 'N' then '18:00:00'
end) and 
(case 
when t4.turno = 'MD' then '05:59:59'
when t4.turno = 'M' then '11:59:59'
when t4.turno = 'T' then '17:59:59'
when t4.turno = 'N' then '29:59:59'
end)";
    $data = DB::select($cad);
    return $data;
  }

  public function getMedicoTurnoByDniAndFarmacia($dni, $id_farmacia)
  {
    $cad = "select distinct t4.id_medico,t3.nombre,t3.paterno,t3.materno,t4.turno
,(case 
when t4.turno = 'MD' then 'MADRUGADA'
when t4.turno = 'M' then 'MA&Ntilde;ANA'
when t4.turno = 'T' then 'TARDE'
when t4.turno = 'N' then 'NOCHE'
end)nombre_turno
from users t1
inner join farmacias t2 on t1.id_farmacia=t2.id
inner join medicos t3 on t1.dni=t3.dni and t2.id_establecimiento=t3.id_establecimiento
inner join medico_turnos t4 on t4.id_medico=t3.id and t4.id_establecimiento=t3.id_establecimiento
where t3.dni='" . $dni . "' and t1.id_farmacia=" . $id_farmacia . "
and t4.fecha_atencion = current_date
and substring(current_time::varchar,1,8)
between (case 
when t4.turno = 'MD' then '00:00:00'
when t4.turno = 'M' then '06:00:00'
when t4.turno = 'T' then '12:00:00'
when t4.turno = 'N' then '18:00:00'
end) and 
(case 
when t4.turno = 'MD' then '05:59:59'
when t4.turno = 'M' then '11:59:59'
when t4.turno = 'T' then '17:59:59'
when t4.turno = 'N' then '29:59:59'
end)";
    $data = DB::select($cad);

    if (isset($data[0]))
      return $data[0];
    else
      return $data;
  }

  public function GetHorarioTurno($id_ipress, $id_consultorio, $fecha = "")
  {

    $cad = "select tur.id id_turno,descripcion,hora_ini,hora_fin,
(select 
(fecha_hora||'#'||fecha_cita||'#'||id_medico||'#'||(select nombre||' '||paterno||' '||materno from medicos where id=id_medico))horario
from (
WITH vals AS
  (SELECT 
	TO_TIMESTAMP('" . $fecha . " '||hora_ini, 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_start,
	TO_TIMESTAMP('" . $fecha . " '||hora_fin, 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_end,
          interval '15 min' AS t_interval),
     grid AS
  (SELECT start_time,lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time FROM
  (SELECT generate_series(frame_start, frame_end, t_interval) AS start_time,frame_end FROM vals) x)
SELECT to_char(start_time, 'HH-MI AM') fecha_hora,
       end_time,
       to_char(start_time, 'YYYY-MM-DD HH24:MI')fecha_cita,
  (SELECT min(t1.id_medico)id_medico
   FROM medico_turnos t1
   INNER JOIN turnos t2 ON t1.id_turno=t2.id
   LEFT JOIN citas t3 ON (t1.id_medico=t3.id_medico AND t3.fecha_hora >= start_time AND t3.fecha_hora < end_time)
   WHERE fecha_atencion = '" . $fecha . "'::date
     AND t1.id_establecimiento=" . $id_ipress . " 
     AND id_consultorio=" . $id_consultorio . "
     AND id_tipo_atencion='AMBULATORIO'
     AND TO_TIMESTAMP(to_char(start_time, 'YYYY-MM-DD HH-MI AM'), 'YYYY-MM-DD HH-MI AM') BETWEEN TO_TIMESTAMP('" . $fecha . " '||hora_ini, 'YYYY-MM-DD HH-MI AM') AND TO_TIMESTAMP('" . $fecha . " '||hora_fin, 'YYYY-MM-DD HH-MI AM')
     AND t1.estado=1
     And t3.id_medico is null
     limit 1
   )
FROM grid g
ORDER BY start_time
)R where id_medico is not null
limit 1 )
from turnos tur
where id_estado_reg=0
order by id_turno ";
    //echo $cad;
    $data = DB::select($cad);
    return $data;
  }

  public static function GetHorarioByTurno($id_ipress, $id_consultorio, $fecha = "", $id_turno, $dura_cons)
  {

    $cad = "SELECT * FROM (select tur.id id_turno,descripcion,hora_ini,hora_fin,
(select 
(fecha_hora||'#'||fecha_cita||'#'||id_medico||'#'||(select nombre||' '||paterno||' '||materno from medicos where id=id_medico))horario
from (
WITH vals AS
  (SELECT 
	TO_TIMESTAMP('" . $fecha . " '||hora_ini, 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_start,
	TO_TIMESTAMP('" . $fecha . " '||hora_fin, 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_end,
          interval '" . $dura_cons . " min' AS t_interval),
     grid AS
  (SELECT start_time,lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time FROM
  (SELECT generate_series(frame_start, frame_end, t_interval) AS start_time,frame_end FROM vals) x)
SELECT to_char(start_time, 'HH-MI AM') fecha_hora,
       end_time,
       to_char(start_time, 'YYYY-MM-DD HH24:MI')fecha_cita,
  (SELECT min(t1.id_medico)id_medico
   FROM medico_turnos t1
   INNER JOIN turnos t2 ON t1.id_turno=t2.id
   LEFT JOIN citas t3 ON (t1.id_medico=t3.id_medico AND t3.fecha_hora >= start_time AND t3.fecha_hora < end_time And t3.id_estado_reg = 1)
   WHERE fecha_atencion = '" . $fecha . "'::date
     AND t1.id_establecimiento=" . $id_ipress . " 
     AND id_consultorio=" . $id_consultorio . "
     AND id_tipo_atencion='AMBULATORIO'
     AND TO_TIMESTAMP(to_char(start_time, 'YYYY-MM-DD HH-MI AM'), 'YYYY-MM-DD HH-MI AM') BETWEEN TO_TIMESTAMP('" . $fecha . " '||hora_ini, 'YYYY-MM-DD HH-MI AM') AND TO_TIMESTAMP('" . $fecha . " '||hora_fin, 'YYYY-MM-DD HH-MI AM')
	 AND TO_TIMESTAMP(to_char(start_time, 'YYYY-MM-DD HH-MI AM'), 'YYYY-MM-DD HH-MI AM') >= now()
     AND t1.estado=1
     And t3.id_medico is null
     limit 1
   )
FROM grid g
ORDER BY start_time
)R where id_medico is not null
limit 1 )
from turnos tur
where id_estado_reg=1
and id = " . $id_turno . "
)R where horario is not null";
    //echo $cad;
    $data = DB::select($cad);
    if (count($data) > 0) return $data[0];
  }

  public static function verificaHorarioTurno($id_ipress, $id_consultorio, $fecha_atencion, $id_turno)
  {

    $cad = "SELECT * 
From medico_turnos t1
INNER JOIN turnos t2 ON t1.id_turno=t2.id
Where id_establecimiento=" . $id_ipress . "
And id_consultorio = " . $id_consultorio . "
And id_turno = " . $id_turno . "
/*And fecha_atencion >= '" . $fecha_atencion . "'*/
And fecha_atencion > '" . $fecha_atencion . "'
/*AND now() BETWEEN TO_TIMESTAMP('" . $fecha_atencion . " '||hora_ini, 'YYYY-MM-DD HH-MI AM') AND TO_TIMESTAMP('" . $fecha_atencion . " '||hora_fin, 'YYYY-MM-DD HH-MI AM')*/
/*AND now() <= TO_TIMESTAMP('" . $fecha_atencion . " '||hora_fin, 'YYYY-MM-DD HH-MI AM')*/
And id_tipo_atencion = 'AMBULATORIO'
And estado = 1
Order by fecha_atencion desc";
    //echo $cad;
    $data = DB::select($cad);
    //if(count($data) > 0) return $data[0];
    return $data;
  }

  public function GetTurnos()
  {
    $cad = "select * from turnos order by 1 asc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetCitasByDni($dni_beneficiario)
  {

    $cad = "select 
    t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
    t2.nro_doc_ident,to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t3.id as id_establecimiento,t3.nombre establecimiento, t6.id AS id_servicio, t6.nombre servicio,
    t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,
    t1.id_estado_cita,t1.id_estado_reg, tc.descripcion as tipo_cita
    ,
(case 
  when t1.id_estado_cita=1 Then 'Programado'
  when t1.id_estado_cita=2 Then 'Confirmado'
  when t1.id_estado_cita=3 Then 'Derivado'
  when t1.id_estado_cita=4 Then 'Atendido'
  when t1.id_estado_cita=5 Then 'Anulado'
  when t1.id_estado_cita=6 Then 'Reservado'
  when t1.id_estado_cita=7 Then 'Observado'
  when t1.id_estado_cita=8 Then 'Migrado'
  when t1.id_estado_cita=9 Then 'Atendido en Triaje'
  when t1.id_estado_cita=10 Then 'Re-Programado'
end)estado_cita, t1.observacion,
concat(u.paterno, ' ', u.materno, ' ', u.name) as nombre_anula,
	CASE 
    	WHEN t1.id_estado_cita = 5 THEN t1.updated_at
    	ELSE NULL
	END AS fecha_anulacion


    FROM citas t1
    inner join asegurados t2 on t1.id_asegurado = t2.id 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t6 on t4.id_consultorio=t6.id
    left join medicos t5 on t1.id_medico=t5.id
    left join tipo_cita tc on tc.id = t1.id_tipo_cita
    left join users u on u.id = t1.updated_us  and t1.id_estado_cita = 5
    WHERE t2.nro_doc_ident='" . $dni_beneficiario . "' 
    AND t6.id <> 114
    ORDER BY t1.fecha_hora DESC";
    $data = DB::select($cad);
    return $data;
  }

  public function GetCitasByDniFecha($dni_beneficiario)
  {

    $cad = "select 
    t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
    t2.nro_doc_ident,to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t3.nombre establecimiento, t6.id AS id_servicio, t6.nombre servicio,
    t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,
    t1.id_estado_cita,t1.id_estado_reg, tc.descripcion as tipo_cita
    , r2.descripcion_corta as nombre_examen,r2.codigo_rad
    FROM citas t1
    left join prestacion_procedimientos pp on pp.id_citas_multiples_rad =t1.id
    left join radnivelatenciones r2 on r2.id = pp.id_rua
    inner join asegurados t2 on t1.id_asegurado = t2.id 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t6 on t4.id_consultorio=t6.id
    left join medicos t5 on t1.id_medico=t5.id
    left join tipo_cita tc on tc.id = t1.id_tipo_cita 
    WHERE t1.fecha_hora>=now()::date and t2.nro_doc_ident='" . $dni_beneficiario . "'
    And t1.id_estado_reg = 1 and t1.id_estado_cita < 3
    ORDER BY t1.fecha_hora ASC";
    $data = DB::select($cad);
    return $data;
  }

  /**********************/

  public function GetHorarioConsultorioByIdConsultorio__($id_ipress, $id_consultorio, $fecha = "", $id_turno, $dura_cons)
  {
    /*********************/

    $cad = "WITH vals AS (
   SELECT 
   		('" . $fecha . "'||' 00:00:00')::timestamp   AS frame_start  
        , ('" . $fecha . "'||' 23:59:59')::timestamp AS frame_end
        , interval '" . $dura_cons . " min' AS t_interval
   )
,    grid AS (
   SELECT start_time
        , lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time
   FROM   (
      SELECT generate_series(frame_start, frame_end, t_interval) AS start_time
           , frame_end
      FROM vals
      ) x
   )
SELECT to_char(start_time, 'HH-MI AM') fecha_hora, count(e.fecha_hora) nro_citas,end_time,to_char(start_time, 'YYYY-MM-DD HH24:MI')fecha_cita, 
(case when TO_TIMESTAMP('" . $fecha . " '||t.hora_fin,'YYYY-MM-DD HH-MI AM') > g.start_time then 1 else 2 end) situacion,
(SELECT 
array_to_string(array_agg(t1.id_sub_consultorio||'#'||(select count(*) from citas cit where id_sub_consultorio=t1.id_sub_consultorio and cit.fecha_hora >= start_time AND cit.fecha_hora < end_time And cit.id_estado_reg = 1) ORDER BY t1.id_sub_consultorio ASC), ',')id_medico
FROM sub_consultorio_turnos t1
INNER JOIN sub_consultorios t2 on t1.id_sub_consultorio=t2.id
INNER JOIN turnos t3 ON t1.id_turno=t3.id
where 1=1
and t2.id_consultorio=" . $id_consultorio . " 
and TO_TIMESTAMP(to_char(start_time, 'YYYY-MM-DD HH-MI AM'),'YYYY-MM-DD HH-MI AM') between TO_TIMESTAMP('" . $fecha . " '||t1.hora_ini,'YYYY-MM-DD HH-MI AM') and TO_TIMESTAMP('" . $fecha . " '||t1.hora_fin,'YYYY-MM-DD HH-MI AM')
and t1.id_estado_reg=1
)

FROM   grid       g
LEFT   JOIN citas e ON e.fecha_hora >= g.start_time AND e.fecha_hora <  g.end_time And e.id_estado_reg = 1
left join turnos t ON TO_TIMESTAMP('" . $fecha . " '||t.hora_ini,'YYYY-MM-DD HH-MI AM') <= g.start_time AND TO_TIMESTAMP('" . $fecha . " '||t.hora_fin_turno,'YYYY-MM-DD HH-MI AM') >= (g.end_time - interval '1 minute')
where t.id = " . $id_turno . "
GROUP  BY start_time,end_time,hora_fin
ORDER  BY start_time";
    //echo $cad;
    $data = DB::select($cad);
    return $data;
  }

  public function GetHorarioConsultorioByIdConsultorio($id_ipress, $id_consultorio, $fecha = "", $dura_cons, $hora_ini, $hora_fin)
  {
    /*********************/

    $cad = "WITH vals AS (
   SELECT 
   		--('" . $fecha . "'||' 00:00:00')::timestamp   AS frame_start  
		TO_TIMESTAMP('" . $fecha . " '||'" . $hora_ini . "', 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_start
        --, ('" . $fecha . "'||' 23:59:59')::timestamp AS frame_end
		,TO_TIMESTAMP('" . $fecha . " '||'" . $hora_fin . "', 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_end
        , interval '" . $dura_cons . " min' AS t_interval
   )
,    grid AS (
   SELECT start_time
        , lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time
   FROM   (
      SELECT generate_series(frame_start, frame_end, t_interval) AS start_time
           , frame_end
      FROM vals
      ) x
   )
SELECT to_char(start_time, 'HH:MI AM') fecha_hora,to_char(end_time, 'HH:MI AM') fecha_fin, count(e.fecha_hora) nro_citas,end_time,to_char(start_time, 'YYYY-MM-DD HH24:MI')fecha_cita, 
(case when TO_TIMESTAMP('" . $fecha . " '||sct.hora_fin,'YYYY-MM-DD HH-MI AM') > g.start_time then 1 else 2 end) situacion,
(SELECT 
array_to_string(array_agg(t1.id_sub_consultorio||'#'||(select count(*) from citas cit where id_sub_consultorio=t1.id_sub_consultorio and cit.fecha_hora >= start_time AND cit.fecha_hora < end_time And cit.id_estado_reg = 1) ORDER BY t1.id_sub_consultorio ASC), ',')id_medico
FROM sub_consultorio_turnos t1
INNER JOIN sub_consultorios t2 on t1.id_sub_consultorio=t2.id
INNER JOIN turnos t3 ON t1.id_turno=t3.id
where 1=1
and t1.id_sub_consultorio=" . $id_consultorio . " 
and TO_TIMESTAMP(to_char(start_time, 'YYYY-MM-DD HH-MI AM'),'YYYY-MM-DD HH-MI AM') between TO_TIMESTAMP('" . $fecha . " '||t1.hora_ini,'YYYY-MM-DD HH-MI AM') and TO_TIMESTAMP('" . $fecha . " '||t1.hora_fin,'YYYY-MM-DD HH-MI AM')
and t1.id_estado_reg=1
)

FROM   grid       g
LEFT   JOIN citas e ON e.fecha_hora >= g.start_time AND e.fecha_hora <  g.end_time And e.id_estado_reg = 1
INNER JOIN sub_consultorio_turnos sct  ON TO_TIMESTAMP('" . $fecha . " '||sct.hora_ini,'YYYY-MM-DD HH-MI AM') <= g.start_time AND TO_TIMESTAMP('" . $fecha . " '||sct.hora_fin,'YYYY-MM-DD HH-MI AM') >= (g.end_time - interval '1 minute')
INNER JOIN sub_consultorios sc on sc.id=sct.id_sub_consultorio
Where sct.id_sub_consultorio = " . $id_consultorio . "
GROUP  BY start_time,end_time,sct.hora_fin
ORDER  BY start_time";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetHorarioConsultorioByIdConsultorioTurno($id_ipress, $id_consultorio, $fecha = "", $dura_cons, $hora_ini, $hora_fin)
  {
    $cad = "
      WITH vals AS (
        SELECT 
          TO_TIMESTAMP('" . $fecha . " '||'" . $hora_ini . "', 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_start
          ,TO_TIMESTAMP('" . $fecha . " '||'" . $hora_fin . "', 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_end
          , interval '" . $dura_cons . " min' AS t_interval
      ),
      grid AS (
        SELECT start_time, lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time
        FROM (
          SELECT generate_series(frame_start, frame_end, t_interval) AS start_time , frame_end
          FROM vals
        ) x
      )
      SELECT th.*, tm.id_medico AS medico, tm.nombre, tm.paterno, tm.materno
      FROM (
        SELECT 
          sct.id_turno, sc.id_establecimiento, sct.id_sub_consultorio,
          to_char(start_time, 'HH:MI AM') fecha_hora,to_char(end_time, 'HH:MI AM') fecha_fin, count(e.fecha_hora) nro_citas, start_time, end_time,to_char(start_time, 'YYYY-MM-DD HH24:MI')fecha_cita,
          (case when TO_TIMESTAMP('" . $fecha . " '||sct.hora_fin,'YYYY-MM-DD HH-MI AM') > g.start_time then 1 else 2 end) situacion,
          (SELECT 
            array_to_string(array_agg(t1.id_sub_consultorio||'#'||(select count(*) from citas cit where id_sub_consultorio=t1.id_sub_consultorio and cit.fecha_hora >= start_time AND cit.fecha_hora < end_time And cit.id_estado_reg = 1) ORDER BY t1.id_sub_consultorio ASC), ',')id_medico
            FROM sub_consultorio_turnos t1
            INNER JOIN sub_consultorios t2 on t1.id_sub_consultorio=t2.id
            INNER JOIN turnos t3 ON t1.id_turno=t3.id
            where 1=1
            and t1.id=" . $id_consultorio . " 
            and t1.id_estado_reg = 1 and t2.id_estado_reg = 1 
            and TO_TIMESTAMP(to_char(start_time, 'YYYY-MM-DD HH-MI AM'),'YYYY-MM-DD HH-MI AM') between TO_TIMESTAMP('" . $fecha . " '||t1.hora_ini,'YYYY-MM-DD HH-MI AM') and TO_TIMESTAMP('" . $fecha . " '||t1.hora_fin,'YYYY-MM-DD HH-MI AM')
            and t1.id_estado_reg=1
            )
        FROM grid g
        LEFT JOIN citas e ON e.fecha_hora >= g.start_time AND e.fecha_hora <  g.end_time And e.id_estado_reg = 1
        INNER JOIN sub_consultorio_turnos sct  ON TO_TIMESTAMP('" . $fecha . " '||sct.hora_ini,'YYYY-MM-DD HH-MI AM') <= g.start_time AND TO_TIMESTAMP('" . $fecha . " '||sct.hora_fin,'YYYY-MM-DD HH-MI AM') >= (g.end_time - interval '1 minute')
        INNER JOIN sub_consultorios sc on sc.id=sct.id_sub_consultorio
        WHERE sct.id = " . $id_consultorio . "
          AND sct.id_estado_reg = 1 and sc.id_estado_reg = 1 
          AND to_char(start_time, 'HH:MI AM') <> to_char(end_time, 'HH:MI AM') 
        GROUP BY sct.id_turno, sc.id_establecimiento, sct.id_sub_consultorio, start_time, end_time, sct.hora_fin
        ORDER BY start_time
       ) AS th
       LEFT JOIN (
        SELECT mt.id_medico, mt.id_establecimiento, mt.id_turno, mt.id_consultorio, mt.id_sub_consultorio, m.nombre, m.paterno, m.materno
        FROM medico_turnos AS mt
        LEFT JOIN medicos AS m ON mt.id_medico = m.id AND m.estado = 1 
        WHERE mt.estado = 1 AND mt.fecha_atencion = '$fecha'
       ) AS tm ON th.id_turno = tm.id_turno AND th.id_establecimiento = tm.id_establecimiento AND th.id_sub_consultorio = tm.id_sub_consultorio
       ORDER BY th.id_sub_consultorio desc, th.start_time ASC
    ";
 
    $data = DB::select($cad);
    return $data;
  }

  public function GetHorarioConsultorioByIdConsultorioTurnoCAR($id_turno, $id_consultorio, $fecha = "", $dura_cons, $hora_ini, $hora_fin)
  {
 
    $cad = "
      WITH vals AS (
        SELECT 
          TO_TIMESTAMP('" . $fecha . " '||'" . $hora_ini . "', 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_start
          ,TO_TIMESTAMP('" . $fecha . " '||'" . $hora_fin . "', 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_end
          , interval '" . $dura_cons . " min' AS t_interval
      ),
      grid AS (
        SELECT start_time, lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time
        FROM (
          SELECT generate_series(frame_start, frame_end, t_interval) AS start_time , frame_end
          FROM vals
        ) x
      )
      SELECT th.*, tm.id_medico AS medico, tm.nombre, tm.paterno, tm.materno
      FROM (
        SELECT 
          sct.id_turno, sc.id_establecimiento, sct.id_sub_consultorio,
          to_char(start_time, 'HH:MI AM') fecha_hora,to_char(end_time, 'HH:MI AM') fecha_fin, count(e.fecha_hora) nro_citas, start_time, end_time,to_char(start_time, 'YYYY-MM-DD HH24:MI')fecha_cita, 
          (case when TO_TIMESTAMP('" . $fecha . " '||sct.hora_fin,'YYYY-MM-DD HH-MI AM') > g.start_time then 1 else 2 end) situacion,
          (SELECT 
            array_to_string(array_agg(t1.id_sub_consultorio||'#'||(select count(*) from citas cit where id_sub_consultorio=t1.id_sub_consultorio and cit.fecha_hora >= start_time AND cit.fecha_hora < end_time And cit.id_estado_reg = 1) ORDER BY t1.id_sub_consultorio ASC), ',')id_medico
            FROM sub_consultorio_turnos t1
            INNER JOIN sub_consultorios t2 on t1.id_sub_consultorio=t2.id
            INNER JOIN turnos t3 ON t1.id_turno=t3.id
            where 1=1
            and t1.id=" . $id_consultorio . "  
            and t1.id_estado_reg = 1 and t2.id_estado_reg = 1 
            and TO_TIMESTAMP(to_char(start_time, 'YYYY-MM-DD HH-MI AM'),'YYYY-MM-DD HH-MI AM') between TO_TIMESTAMP('" . $fecha . " '||t1.hora_ini,'YYYY-MM-DD HH-MI AM') and TO_TIMESTAMP('" . $fecha . " '||t1.hora_fin,'YYYY-MM-DD HH-MI AM')
            and t1.id_estado_reg=1 
            )
        FROM grid g
        LEFT JOIN citas e ON e.fecha_hora >= g.start_time AND e.fecha_hora <  g.end_time And e.id_estado_reg = 1
        INNER JOIN sub_consultorio_turnos sct  ON TO_TIMESTAMP('" . $fecha . " '||sct.hora_ini,'YYYY-MM-DD HH-MI AM') <= g.start_time AND TO_TIMESTAMP('" . $fecha . " '||sct.hora_fin,'YYYY-MM-DD HH-MI AM') >= (g.end_time - interval '1 minute')
        INNER JOIN sub_consultorios sc on sc.id=sct.id_sub_consultorio
        WHERE sct.id = " . $id_consultorio . "
          AND sct.id_estado_reg = 1 and sc.id_estado_reg = 1  
          AND to_char(start_time, 'HH:MI AM') <> to_char(end_time, 'HH:MI AM') 
        GROUP BY sct.id_turno, sc.id_establecimiento, sct.id_sub_consultorio, start_time, end_time, sct.hora_fin 
        ORDER BY start_time 
       ) AS th
       LEFT JOIN (
        SELECT mt.id_medico, mt.id_establecimiento, mt.id_turno, mt.turno, mt.id_consultorio, mt.id_sub_consultorio, m.nombre, m.paterno, m.materno
        FROM medico_turnos AS mt
        LEFT JOIN medicos AS m ON mt.id_medico = m.id AND m.estado = 1 
        WHERE mt.estado = 1 AND mt.fecha_atencion = '$fecha'  
       ) AS tm ON th.id_turno = tm.id_turno AND th.id_establecimiento = tm.id_establecimiento AND th.id_sub_consultorio = tm.id_sub_consultorio
       ORDER BY th.id_sub_consultorio desc, th.start_time ASC 
    ";    
    
    $data = DB::select($cad);
    return $data;
  }
  public function registrarMedicoCita($fecha, $id_sub_consultorio)
  {
    $cad = "Select sp_genera_medico_cita(?,?)";
    $data = DB::select($cad, array($fecha, $id_sub_consultorio));
    return $data[0]->sp_genera_medico_cita;
  }

  public function registrarMedicoCitaHorario($id_cita, $fecha, $id_sub_consultorio, $id_turno)
  {
    $sql = '
      update citas set id_medico = (
        select id_medico  
        from medico_turnos 
        where estado=1
        and id_sub_consultorio = ' . (int)$id_sub_consultorio. ' 
        and to_char(fecha_atencion,\'YYYY-mm-dd\') = \'' . $fecha . '\'
        and id_turno = ' . (int)$id_turno . ' 
        limit 1
      )  
      where id = ' . (int)$id_cita;
    $data = DB::select($sql);
    
    $sql2 = '
      update citas_llamados set id_medico = (
        select id_medico  
        from medico_turnos 
        where estado=1
        and id_sub_consultorio = ' . (int)$id_sub_consultorio. ' 
        and to_char(fecha_atencion,\'YYYY-mm-dd\') = \'' . $fecha . '\'
        and id_turno = ' . (int)$id_turno . ' 
        limit 1
      )  
      where id_cita = ' . (int)$id_cita;
    $data2 = DB::select($sql2);

  }

  public function GetSubConsultorioTurnoByIdConsultorioAndTurno($id_consultorio, $turno)
  {

    $cad = "select t1.id,t2.id id_sub_consultorio_turno,t1.descripcion nombre,tiempo_atencion,t2.hora_ini,t2.hora_fin,t2.id_turno,t3.abreviatura turno
        from sub_consultorios t1
        inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio
        inner join turnos t3 on t2.id_turno=t3.id
        where t1.id=" . $id_consultorio . " and t3.abreviatura='" . $turno . "' and t1.id_estado_reg = 1 and t2.id_estado_reg = 1 order by t2.id_turno asc";
    $data = DB::select($cad);
    return $data;
  }

  public function DesactivaTurnoMedicoOtrasIpress($id_medico, $id_establecimiento, $fecha)
  {
    $sql = "update medico_turnos 
    set estado = 0
    Where id_medico = {$id_medico}
    And id_establecimiento <> {$id_establecimiento}
    and fecha_atencion >= '{$fecha}'";
    $data = DB::select($sql);
  }

  public function getTurnosProgramados($id_establecimiento, $id_sub_consultorio, $id_medico, $id_tipo_atencion, $year, $month){
    $sql_sub_consultorio="";
    if(empty($id_sub_consultorio)){
      $sql_sub_consultorio="AND mt.id_sub_consultorio is null";
    }else{
      $sql_sub_consultorio="AND mt.id_sub_consultorio = '".(int)$id_sub_consultorio."'";
    }
    $query = '
      SELECT *
      FROM (
        SELECT \'1\' AS tipo, mt.turno || \'-\'||mt.fecha_atencion AS key, MAX(m.nombre||\' \'||m.paterno||\' \'||m.materno) AS medico
        FROM medico_turnos AS mt
        INNER JOIN medicos AS m ON m.id = mt.id_medico AND m.id_establecimiento = mt.id_establecimiento AND m.estado = 1
        WHERE mt.id_establecimiento = ' . (int)$id_establecimiento . '  
          AND EXTRACT(YEAR FROM mt.fecha_atencion) = ' . (int)$year . ' 
          AND EXTRACT(MONTH FROM mt.fecha_atencion) = ' . (int)$month . ' 
          '.$sql_sub_consultorio.' 
          AND mt.id_tipo_atencion = \'' . $id_tipo_atencion . '\'  
          AND mt.estado = 1
          AND mt.id_medico <> ' . (int)$id_medico . ' 
        GROUP BY mt.turno, mt.fecha_atencion
        UNION 
        SELECT \'2\', mt.turno || \'-\' || mt.fecha_atencion AS KEY, MAX ( M.nombre || \' \' || M.paterno || \' \' || M.materno ) AS medico 
        FROM medico_turnos AS mt
        INNER JOIN medicos AS M ON M.ID = mt.id_medico AND M.id_establecimiento = mt.id_establecimiento AND M.estado = 1 
        WHERE 
          EXTRACT(YEAR FROM mt.fecha_atencion) = ' . (int)$year . ' 
          AND EXTRACT(MONTH FROM mt.fecha_atencion) = ' . (int)$month . ' 
           '.$sql_sub_consultorio.' 
          AND mt.estado = 1 
        GROUP BY mt.turno, mt.fecha_atencion 
      ) AS tmp
    ';
    return DB::select($query);
  }

  public function getTurnosMedico($id_medico, $year, $month) {
    $query = '
      SELECT mt.turno || \'-\' || mt.fecha_atencion AS KEY
      FROM medico_turnos AS mt
      WHERE
        EXTRACT ( YEAR FROM mt.fecha_atencion ) = ' . (int)$year . ' 
        AND EXTRACT ( MONTH FROM mt.fecha_atencion ) = ' . (int)$month . '  
        AND mt.id_medico = ' . (int)$id_medico . ' 		
        AND mt.id_tipo_atencion = \'EMERGENCIA\'
        AND mt.estado = 1 
      GROUP BY mt.turno, mt.fecha_atencion 
    ';
    return DB::select($query);
  }

  /**
   * Opciones:
   * 0 = Fecha actual hacia adelante
   * 1 = Solo fecha actual
   */
  public function GetCitasByDniFechaServicio($dni_beneficiario, $servicio, $opcion = 0)
  {

    $cad = "select t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident, t2.nro_doc_ident,to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t3.id id_establecimiento,t3.nombre establecimiento, t6.id AS id_servicio, t6.nombre servicio, t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno, t1.id_estado_cita, e.descripcion estado_cita, t1.id_estado_reg, tc.descripcion as tipo_cita, t4.descripcion nombre_subconsultorio 
      FROM citas t1 
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id 
      inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id 
      inner join consultorios t6 on t4.id_consultorio=t6.id 
      inner join estados e on e.id=t1.id_estado_cita
      left join medicos t5 on t1.id_medico=t5.id 
      left join tipo_cita tc on tc.id = t1.id_tipo_cita";

    if ($opcion == 0) :
      $cad .= " WHERE t1.fecha_hora::date>=now()::date ";
    elseif ($opcion == 1) :
      $cad .= " WHERE t1.fecha_hora::date=now()::date ";
    endif;
    $cad .= " and t6.id=" . $servicio . " and t2.nro_doc_ident='" . $dni_beneficiario . "'
          And t1.id_estado_reg = 1
          ORDER BY t1.fecha_hora ASC;";
    $data = DB::select($cad);
    return $data;
  }
  public function GetSubConsultorioTurnoByIdConsultorio2($id_servicio, $id_consultorio="", $fecha_atencion="",$id_establecimiento)
  {
      if($id_establecimiento==76 ){
        /*$cad0 = "select t2.dia , t1.id
        from sub_consultorios t1 
        inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio
        where (t2.dia = 'Todos' OR t2.dia IS NULL) and t1.id='" . $id_consultorio . "' and t1.id_estado_reg = 1 and t2.id_estado_reg = 1";
      $data0 = DB::select($cad0);

        if(count($data0) >= 1):*/
        $cad = "select t1.id,t2.id id_sub_consultorio_turno,t1.descripcion nombre,case when t2.dia = 'Todos' then t2.tiempo_atencion when t2.dia is null then t1.tiempo_atencion when t2.dia <> 'Todos' or t2.dia is not null then t2.tiempo_atencion end as tiempo_atencion, t2.hora_ini,t2.hora_fin,t2.id_turno,t3.abreviatura turno, t2.dia, t3.abreviatura||t2.dia as turno_con_dia, t2.fecha_inicio_turno, t2.fecha_fin_turno
        from sub_consultorios t1
        inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio 
        inner join turnos t3 on t2.id_turno=t3.id
        where t1.id='" . $id_consultorio . "'";
        
        $cad .= " AND (
                      t2.fecha_inicio_turno IS NULL or t2.fecha_inicio_turno = ''
                      OR '$fecha_atencion' >= date(t2.fecha_inicio_turno)
                  )
                AND (
                      t2.fecha_fin_turno IS NULL or t2.fecha_fin_turno = ''
                      OR '$fecha_atencion' <= date(t2.fecha_fin_turno)
                  ) ";
        /*else:
        $cad = "select t1.id,t2.id id_sub_consultorio_turno,t1.descripcion nombre,case when t2.dia <> 'Todos' or t2.dia is not null then t2.tiempo_atencion end as tiempo_atencion,t2.hora_ini,t2.hora_fin,t2.id_turno,t3.abreviatura turno, t2.dia, t3.abreviatura||t2.dia as turno_con_dia
        from sub_consultorios t1
        inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio 
        inner join turnos t3 on t2.id_turno=t3.id
        and t1.id='" . $id_consultorio . "' and t1.id_estado_reg = 1"; 
          */
    if($fecha_atencion!= ""):
    $cad .=  " and (t2.dia = case when extract(dow from '". $fecha_atencion."'::date) = '1' then 'Lunes'  
              when extract(dow from '".$fecha_atencion."'::date) = '2' then 'Martes'  
              when extract(dow from '".$fecha_atencion."'::date) = '3' then 'Miercoles'  
              when extract(dow from '".$fecha_atencion."'::date) = '4' then 'Jueves' 
              when extract(dow from '".$fecha_atencion."'::date) = '5' then 'Viernes'  
              when extract(dow from '".$fecha_atencion."'::date) = '6' then 'Sabado' end or t2.dia = 'Todos' or t2.dia is null) and t2.id_estado_reg = 1 order by t2.hora_ini::time asc";
  endif;
  //endif;
} 
else {
   $cad = "select t1.id,t2.id id_sub_consultorio_turno,t1.descripcion nombre,t1.tiempo_atencion,t2.hora_ini,t2.hora_fin,t2.id_turno,t3.abreviatura turno
  from sub_consultorios t1
  inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio
  inner join turnos t3 on t2.id_turno=t3.id
  where t1.id=" . $id_consultorio . " and t1.id_estado_reg = 1 and t2.id_estado_reg = 1 order by t2.hora_ini::time asc";

      }


    
    // $cad .= "and t1.id_estado_reg = 1 and t2.id_estado_reg = 1 order by t2.hora_ini::time asc";

  
    
    // $cad = "select t1.id,t2.id id_sub_consultorio_turno,t1.descripcion nombre,t2.tiempo_atencion,t2.hora_ini,t2.hora_fin,t2.id_turno,t3.abreviatura turno, t2.dia, t3.abreviatura||t2.dia as turno_con_dia
		// from sub_consultorios t1
		// inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio 
		// inner join turnos t3 on t2.id_turno=t3.id 
		
		// and t1.id='" . $id_consultorio . "'"; 

      
      

    // if($fecha_atencion!= "")
    
    //   $cad .=  " and t2.dia = case when extract(dow from '". $fecha_atencion."'::date) = '1' then 'Lunes'  
		// 		when extract(dow from '".$fecha_atencion."'::date) = '2' then 'Martes'  
		// 		when extract(dow from '".$fecha_atencion."'::date) = '3' then 'Miercoles'  
    //     when extract(dow from '".$fecha_atencion."'::date) = '4' then 'Jueves' 
    //     when extract(dow from '".$fecha_atencion."'::date) = '5' then 'Viernes'  
    //     when extract(dow from '".$fecha_atencion."'::date) = '6' then 'Sabado'end ";
        
        
        
    
        
        

       
    //     $cad .= "and t1.id_estado_reg = 1 and t2.id_estado_reg = 1 order by t2.hora_ini::time asc";
		

    //echo $cad; exit;
    $data = DB::select($cad);
    return $data;
}

public function GetCitasByDniFechaServicioProximaCita($dni_beneficiario, $servicio){

  $cad = "select 
    t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
    t2.nro_doc_ident,to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t3.nombre establecimiento, t6.id AS id_servicio, t6.nombre servicio,
    t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,
    t1.id_estado_cita,t1.id_estado_reg, tc.descripcion as tipo_cita
    FROM citas t1
    inner join asegurados t2 on t1.id_asegurado = t2.id 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t6 on t4.id_consultorio=t6.id
    left join medicos t5 on t1.id_medico=t5.id
    left join tipo_cita tc on tc.id = t1.id_tipo_cita 
    WHERE t1.fecha_hora>=now()::date and t2.nro_doc_ident='" . $dni_beneficiario . "'  and t6.id=".$servicio."
    And t1.id_estado_reg = 1 and t1.id_estado_cita < 3
    ORDER BY t1.fecha_hora ASC";
    $data = DB::select($cad);
    return $data;
}

public function GetSubConsultorioTurnoByIdConsultorioCAR( $id_consultorio="", $id_establecimiento, $id_turno)
{
    if($id_establecimiento==76 ){
 
      $cad = "select t1.id,t2.id id_sub_consultorio_turno,t1.descripcion nombre,case when t2.dia = 'Todos' 
      then t2.tiempo_atencion when t2.dia is null then t1.tiempo_atencion when t2.dia <> 'Todos' or 
      t2.dia is not null then t2.tiempo_atencion end as tiempo_atencion, t2.hora_ini,
      t2.hora_fin,t2.id_turno,t3.abreviatura turno, t2.dia, t3.abreviatura||t2.dia as turno_con_dia

      from sub_consultorios t1

      inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio 
      inner join turnos t3 on t2.id_turno=t3.id
      where t1.id='" . $id_consultorio . "' and t2.id_estado_reg=1
      order by t3.id asc";  
    } else {
      $cad = "select t1.id,t2.id id_sub_consultorio_turno,t1.descripcion nombre,t1.tiempo_atencion,t2.hora_ini,t2.hora_fin,t2.id_turno,t3.abreviatura turno
      from sub_consultorios t1
      inner join sub_consultorio_turnos t2 on t1.id=t2.id_sub_consultorio
      inner join turnos t3 on t2.id_turno=t3.id 
      where t1.id=" . $id_consultorio . " and t1.id_estado_reg = 1 and t2.id_estado_reg = 1 order by t2.hora_ini::time asc";

    } 
    //dd($cad);
  $data = DB::select($cad);
  return $data;
}
public function registrarMedicoCita2($fecha, $id_sub_consultorio, $id_medico)
  {
    $cad = "Select sp_genera_medico_cita(?,?,?)";
    $data = DB::select($cad, array($fecha, $id_sub_consultorio,$id_medico));
    return $data[0]->sp_genera_medico_cita;
  }

  public function GetValidaProgramacionMedicoByFechaAndIpressEmergencia($id_ipress, $id_medico, $id_sub_consultorio, $fecha, $turno)
  {
    $data = DB::table('medico_turnos')
      ->join('sub_consultorios', 'medico_turnos.id_sub_consultorio', '=', 'sub_consultorios.id')
      ->where('medico_turnos.id_establecimiento', '=', $id_ipress)
      ->where('medico_turnos.id_medico', '=', $id_medico)
      ->where('medico_turnos.id_tipo_atencion', '<>', "EMERGENCIA")
      ->where('medico_turnos.id_tipo_atencion', '<>', "AMBULATORIO")
      ->where('medico_turnos.id_tipo_atencion', '<>', "HOSPITALIZACION")
      ->where('medico_turnos.id_tipo_atencion', '<>', "CENTRO QUIRURGICO")
      ->where('medico_turnos.id_sub_consultorio', '<>', $id_sub_consultorio)
      ->where('medico_turnos.fecha_atencion', '=', $fecha)
      ->where('medico_turnos.turno', '=', $turno)
      ->where('medico_turnos.estado', '=', '1')
      ->select(
        'medico_turnos.*',
        'sub_consultorios.descripcion as consultorio'
      )
      ->first();
    return $data;
  }

  public function GetValidaProgramacionMedicoByFechaAndOtraIpressEmergencia($id_ipress, $dni_medico, $fecha, $turno)
  {
    $data = DB::table('medico_turnos')
      ->join('sub_consultorios', 'medico_turnos.id_sub_consultorio', '=', 'sub_consultorios.id')
      ->join('medicos', 'medico_turnos.id_medico', '=', 'medicos.id')
      ->join('establecimientos', 'medico_turnos.id_establecimiento', '=', 'establecimientos.id')
      ->where('medico_turnos.id_establecimiento', '<>', $id_ipress)
      ->where('medicos.dni', '=', $dni_medico)
      ->where('medico_turnos.id_tipo_atencion', '=', 'EMERGENCIA')
      ->where('medico_turnos.fecha_atencion', '=', $fecha)
      ->where('medico_turnos.turno', '=', $turno)
      ->where('medico_turnos.estado', '=', '1')
      ->select(
        'medico_turnos.*',
        'sub_consultorios.descripcion as consultorio',
        'establecimientos.nombre as nom_establecimiento'
      )
      ->first();
    return $data;
  }

  public function getTurnosMedicoSinEmergencia($id_medico, $year, $month) {
    $query = "
      SELECT mt.turno || '-' || mt.fecha_atencion AS KEY
      FROM medico_turnos AS mt
      WHERE
        EXTRACT ( YEAR FROM mt.fecha_atencion ) = " . (int)$year . " 
        AND EXTRACT ( MONTH FROM mt.fecha_atencion ) = " . (int)$month . "  
        AND mt.id_medico = " . (int)$id_medico . " 		
        AND mt.id_tipo_atencion <> 'EMERGENCIA'
        AND mt.estado = 1 
      GROUP BY mt.turno, mt.fecha_atencion 
    ";
    return DB::select($query);
  }

  public function getCantidadTurnos($id_medico, $fecha, $id_establecimiento, $tipo_atencion, $id_sub_consultorio)
  {
    $cad = "Select sp_get_cantidad_turnos(?,?,?,?,?)";
    $data = DB::select($cad, array($id_medico, $fecha, $id_establecimiento, $tipo_atencion, $id_sub_consultorio));
    return $data[0]->sp_get_cantidad_turnos;
  }

  public function registrarTurnoXFecha($id_medico, $fecha, $id_establecimiento, $tipo_atencion, $id_sub_consultorio, $id_consultorio, $id_user_created, $estado_turno_madrugada, $estado_turno_manana, $estado_turno_tarde, $estado_turno_noche)
  {
    $cad = "Select sp_registrar_turno_x_fecha(?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_medico, $fecha, $id_establecimiento, $tipo_atencion, $id_sub_consultorio, $id_consultorio, $id_user_created, $estado_turno_madrugada, $estado_turno_manana, $estado_turno_tarde, $estado_turno_noche));
    return $data[0]->sp_registrar_turno_x_fecha;
  }
  
    
  public function getTurnoProgramadoDelProfesional($id_medico, $id_establecimiento, $id_servicio, $tipo_atencion) {
    $p[] = $id_medico;
    $p[] = $id_establecimiento;
    $p[] = $id_servicio;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_get_programacion_profesional_ahora', $p);
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

  public function GetSubConsultorioByIdConsultorio_fecha_cqx($id_consultorio, $id_establecimiento,$fecha_cita, $id_servicio_radiologia = 0)
  {
    $sql = "SELECT * FROM obtener_sub_consultorios_cqx(?, ?, ?, ?)";
    $result = DB::select($sql, [$id_consultorio, $id_establecimiento, $fecha_cita, $id_servicio_radiologia]);
    return $result; 
  }
  public function GetSubConsultorioTurnoByIdConsultorioCqx($id_consultorio="", $fecha_atencion="",$id_establecimiento)
  {
    $fecha = isset($fecha_atencion) ? $fecha_atencion : null;
    /*if(Auth::user()->id == 115):
      dd($id_consultorio);
    endif;*/
    $data = DB::select('SELECT * FROM public.get_sub_consultorio_turno_by_id_consultorio_cqx(?, ?, ?)', [
        $id_consultorio,
        $fecha,
        $id_establecimiento
    ]);
    return $data;
  }
  public function GetHorarioConsultorioByIdConsultorioTurnoCqx($p_consultorios_turnos)
  {
    $result = DB::select("
        SELECT * FROM public.get_horario_consultorio_by_id_consultorio_turno(?::text[])
    ", [$p_consultorios_turnos]);

    return $result;
  }

}
