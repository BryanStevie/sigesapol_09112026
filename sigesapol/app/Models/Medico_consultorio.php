<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Medico_consultorio extends Model
{
  public function GetConsultorioByIdIpressAndMedico($id_ipress, $id_medico)
  {
    $cad = "Select * From medico_consultorios Where id_establecimiento=" . $id_ipress . " And id_medico=" . $id_medico . " And estado=1";
    $data = DB::select($cad);
    return $data;
  }

  public function ServicioBancoSangre($id_ipress, $id_medico)
  {
      $sql = "SELECT * FROM medico_consultorios 
              WHERE id_establecimiento = ?
              AND id_medico = ?
              AND estado = 1
              AND id_consultorio = 125";
      return DB::select($sql, [$id_ipress, $id_medico]);
  }

  public function GetValidaConsultorioByIpressAndMedicoAndConsultorio($id_ipress, $id_medico, $id_consultorio)
  {
    $datos = Medico_consultorio::where('id_establecimiento', '=', $id_ipress)
      ->where('id_medico', '=', $id_medico)
      ->where('id_consultorio', '=', $id_consultorio)
      ->where('estado', '=', 1)
      ->count();
    return $datos;
  }

  public function GetValidaConsultorioByIpressAndConsultorio($id_ipress, $id_consultorio)
  {
    $cad = "Select m.id, m.dni, m.nombre, m.materno, m.paterno, m.email, m.colegiatura,m.rne From medicos m
      Inner Join medico_consultorios mco On m.id=mco.id_medico And mco.estado=1
      Where mco.id_establecimiento=" . $id_ipress . " And mco.id_consultorio=" . $id_consultorio . " And COALESCE(m.estado,'1')!='3'
      Group By m.id, m.dni, m.nombre, m.materno, m.paterno,m.rne
      Order By m.paterno,m.materno,m.nombre";
    $data = DB::select($cad);
    return $data;
  }

  public function GetConsultorioByIdIpressAndMedicos($id_ipress, $id_medico)
  {
    $cad = "Select consultorios.* From medico_consultorios inner join consultorios on consultorios.id=medico_consultorios.id_consultorio Where id_establecimiento=" . $id_ipress . " And id_medico=" . $id_medico . " And medico_consultorios.estado=1";
    $data = DB::select($cad);
    return $data;
  }
  /*
    public function ShowallById_IpressAndIdConsultorio($id_ipress,$id_consultorio=0){
		$cad = "Select m.id, m.dni, m.nombre, m.materno, m.paterno, m.email, m.colegiatura From medicos m
Inner Join medico_consultorios mco On m.id=mco.id_medico And mco.estado=1
Where mco.id_establecimiento=".$id_ipress." And mco.id_consultorio=".$id_consultorio." And COALESCE(m.estado,'1')!='3'
Group By m.id, m.dni, m.nombre, m.materno, m.paterno
Order By m.paterno,m.materno,m.nombre";
        $data = DB::select($cad);
        return $data;
    }
	*/

  public function ShowallById_IpressAndIdConsultorio($id_ipress, $id_consultorio = 0)
  {
    $cad = "Select m.*,es.nombre especializacion, co.nombre consultorio
      ,pr.nombre profesion, tc.nombre tipo_contrato From medicos m
      Inner Join especializaciones es On m.id_especializacion=es.id
      Inner Join medico_consultorios mco On m.id=mco.id_medico
      Inner Join consultorios co On mco.id_consultorio=co.id And mco.estado=1
      Left Join profesiones pr On m.id_profesion=pr.id
      Left Join tipo_contratos tc On m.tipo_contrato=tc.id
      Where mco.id_establecimiento=" . $id_ipress . " And mco.id_consultorio=" . $id_consultorio . " And m.estado='1'
      Order By m.paterno,m.materno,m.nombre";
    $data = DB::select($cad);
    return $data;
  }

  public function ShowallById_Ipress($id_ipress)
  {
    $cad = "Select m.id, m.dni, m.nombre, m.materno, m.paterno, m.email, m.colegiatura From medicos m
      Inner Join medico_consultorios mco On m.id=mco.id_medico And mco.estado=1
      Where mco.id_establecimiento=" . $id_ipress . " And COALESCE(m.estado,'1')!='3'
      Group By m.id, m.dni, m.nombre, m.materno, m.paterno
      Order By m.paterno,m.materno,m.nombre";
    //echo $cad;
    $data = DB::select($cad);
    return $data;
  }

  public function GetConsultorioByIdEstablecimientoAndIdMedico($id_ipress, $id_medico, $id_consultorio = 0)
  {
    $cad = "Select co.id id_consultorio, co.nombre nom_consultorio, co.flag_quirurgico From medicos m
      Inner Join medico_consultorios mco On m.id=mco.id_medico
      Inner Join consultorios co On mco.id_consultorio=co.id And mco.estado=1
      Where mco.id_establecimiento=" . $id_ipress . " And m.id=" . $id_medico . " And COALESCE(m.estado,'1')!='3'";
    if ($id_consultorio != 0) $cad .= " And mco.id_consultorio=" . $id_consultorio . "";
    $cad .= " Order by co.nombre";

    $data = DB::select($cad);
    return $data;
  }

  public function GetSubConsultorioByIdEstablecimientoIdConsultorio($id_ipress, $id_consultorio=0, $id_tipo_atencion=0){
		$cad = "Select sc.id, sc.descripcion nombre 
            From sub_consultorios sc
            Where sc.id_consultorio=".$id_consultorio." 
            And sc.id_establecimiento=".$id_ipress." 
            And sc.id_estado_reg = 1";
    if ($id_tipo_atencion!=0) $cad.=" And sc.id_tipo_atencion_1=".$id_tipo_atencion;
    $cad.=" Order by sc.descripcion";
		//echo $cad;
        $data = DB::select($cad);
        return $data;
    }
  
  public function GetSubConsultorioByIdEstablecimientoAndIdConsultorio($id_ipress, $id_consultorio, $id_tipo_atencion=0)
  {
    $cad = "Select distinct sc.id, sc.descripcion nombre 
      From sub_consultorios sc
      inner join upsses u on u.id_consultorio =sc.id_consultorio
      Where sc.id_consultorio=" . $id_consultorio . " 
      And sc.id_establecimiento=" . $id_ipress;

    if($id_tipo_atencion!=0) {
      $cad .=" And u.id_tipo_atencion=" . $id_tipo_atencion;
      $cad.=" And sc.id_tipo_atencion_1 = " . $id_tipo_atencion;
    }
    $cad.=" And sc.id_estado_reg = 1";
    $cad.=" And u.estado = 1";
//      And u.id_tipo_atencion=" . $id_tipo_atencion . " 
//And sc.id_estado_reg = 1";

    $cad .= " Order by sc.descripcion";
          //   exit;
    $data = DB::select($cad);
    return $data;
  }


 public function GetSubConsultorioByIdEstablecimientoAndIdMedico($id_ipress, $id_medico,$fecha_atencion, $id_consultorio){
        $cad = "select sc.id, sc.descripcion  nombre
from consultorios c 
inner join sub_consultorios sc on c.id = sc.id_consultorio 
inner join medico_turnos mt on mt.id_consultorio  = c.id
Where  mt.id_medico=".$id_medico." And c.id=".$id_consultorio." And sc.id_establecimiento=".$id_ipress." And mt.fecha_atencion ='".$fecha_atencion."' And sc.id_estado_reg = 1 and sc.id_tipo_atencion_1 = 1";
$cad.=" group by sc.id, sc.descripcion 
        Order by sc.descripcion";
        
        $data = DB::select($cad);
        return $data;
    }

public function GetConsultorioByIdEstablecimiento($id_ipress)
  {
    $cad = "Select co.id id_consultorio, co.nombre nom_consultorio From medicos m
      Inner Join medico_consultorios mco On m.id=mco.id_medico
      Inner Join consultorios co On mco.id_consultorio=co.id And mco.estado=1
      Where mco.id_establecimiento=" . $id_ipress . " And COALESCE(m.estado,'1')!='3'";
    
    $cad .= " Order by co.nombre";

    $data = DB::select($cad);
    return $data;
  }
}