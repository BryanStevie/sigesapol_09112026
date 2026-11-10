<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SubConsultorio extends Model
{

  public function ObtenerSubConsultorioPorIdServicio($id_establecimiento = 0, $id_servicio = 0)
  {
    $cad = " select t1.id,t2.nombre establecimiento,t1.id_consultorio,t1.fecha_inicio_actividades,t1.fecha_final_actividades,t1.descripcion,t1.abreviatura,t1.id_estado_reg,t1.descripcion_tv,t1.tiempo_atencion, t2.id id_establecimiento,t1.adicional,ta.nombre tipo_atencion,
              case 
              when t1.fecha_inicio_actividades is not null then 'SI'
              else 'NO' end tiene_inicio,t1.mopri_cqx
              from sub_consultorios t1
              inner join establecimientos t2 on t1.id_establecimiento=t2.id
              LEFT JOIN tipo_atenciones ta ON t1.id_tipo_atencion_1 = ta.id
              where t1.id_consultorio=" . $id_servicio;

    if ($id_establecimiento > 0) $cad .= " And t1.id_establecimiento=" . $id_establecimiento;
    $cad .= " Order By t2.nombre Asc, t1.descripcion Asc";
    //$cad .= "Order By t1.id_estado_reg Desc,t2.nombre Asc,t1.descripcion Asc";

    $data = DB::select($cad);
    return $data;
  }

  public function ExisteConsultorioServicio($id_establecimiento, $id_servicio = 0, $descripcion = "", $id_tipo_atencion = 0)
  {
    if ($id_establecimiento == 76):
      $data = SubConsultorio::where('id_establecimiento', '=', $id_establecimiento)->where('id_tipo_atencion_1', '=', $id_tipo_atencion)->where('id_consultorio', '=', $id_servicio)->where('descripcion', '=', $descripcion)->where('id_estado_reg', '=', 1)->first();
    else:
      $data = SubConsultorio::where('id_establecimiento', '=', $id_establecimiento)->where('id_consultorio', '=', $id_servicio)->where('descripcion', '=', $descripcion)->where('id_estado_reg', '=', 1)->first();
    endif;
    return isset($data) ? true : false;
  }

  public function GetNombreById($id_sub_consultorio)
  {
    $datos = DB::table('sub_consultorios')
      ->select('sub_consultorios.*')
      ->where('sub_consultorios.id', $id_sub_consultorio)
      ->first();
    return $datos;
  }

  public function GetAdicional($sub_consultorio) { 
		
    $cad = "select t1.adicional  
            FROM sub_consultorios t1
            Where t1.id=".$sub_consultorio;
    $data = DB::select($cad);
    return $data[0];

    
}

  public function mostrarSubConsultoriosPorIdConsultorio($id_establecimiento,$id_servicio) {

    $cad = DB::table('sub_consultorios')
                ->where('id_consultorio',$id_servicio)
                ->where('id_establecimiento',$id_establecimiento)
                ->where('id_estado_reg',1)->get();
    return $cad;
  }

  public function GetSubConsultorioByIdEstablecimientoIdConsultorio($id_ipress, $id_consultorio=0, $id_tv){
    
		$cad = "Select sc.id, sc.descripcion nombre 
            From sub_consultorios sc
            Where sc.id_consultorio=".$id_consultorio." 
            And sc.id_establecimiento=".$id_ipress." 
            And sc.id_estado_reg = 1";
    $cad .= " AND NOT EXISTS (
              SELECT 1
              FROM tv_sub_consultorios tvsc 
              WHERE tvsc.id_sub_consultorios = sc.id 
              AND tvsc.estado = 1 
              AND tvsc.id_tv =".$id_tv.")";
    $cad .= " Order by sc.descripcion";
            
    $data = DB::select($cad);
    return $data;
  }

}
