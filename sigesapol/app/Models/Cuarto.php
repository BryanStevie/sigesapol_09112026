<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Cuarto extends Model
{
	
	public function GetConsultorioByIdPiso($id_piso) {
		/*
        $cad = "select T1.id,T2.nombre,T1.id_consultorio
				from asignacion_servicios T1
				inner join consultorios T2 on T1.id_consultorio=T2.id
				where id_piso=" . $id_piso . " And T2.estado is NULL And T1.estado=0";
		*/
		$cad = "select T1.id,T2.nombre,T1.id_consultorio
				from asignacion_servicios T1
				inner join consultorios T2 on T1.id_consultorio=T2.id 
				where id_piso=" . $id_piso . " And T1.estado=0";

        $data = DB::select($cad);
        return $data;
    }

    public function GetPisoConsultorioByIdAsignacionServicio($id_asignacion_servicio){
		
		$cad = "select *
from asignacion_servicios T1
where T1.id=".$id_asignacion_servicio;
        $data = DB::select($cad);
        return $data[0];
		
    }
	
	
	public function GetCuartoById($id) {
        
        $cad = "select T1.*,T1.id_asignacion_servicio,T2.id_piso,T4.id_pabellon
				from cuartos T1
				inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
				inner join consultorios T3 on T2.id_consultorio=T3.id 
				inner join pisos T4 on T2.id_piso=T4.id
				inner join pabellones T5 on T4.id_pabellon=T5.id
				where T1.id=".$id." 
				limit 1 ";
        $data = DB::select($cad);
        return $data[0];
    }

	public function GetCuartoCamaId($id_cama) {
        
        $cad = "select T1.id,T1.nombre,T1.id_cuarto,T3.id as id_asignacion_servicio,T4.id as id_consultorio,T4.nombre as nombre_consultorio,T5.id as id_piso,T6.id as id_pabellon
				from camas T1
				inner join cuartos T2 on T1.id_cuarto = T2.id
				inner join asignacion_servicios T3 on T2.id_asignacion_servicio = T3.id
				inner join consultorios T4 on T3.id_consultorio=T4.id 
				inner join pisos T5 on T3.id_piso=T5.id
				inner join pabellones T6 on T5.id_pabellon=T6.id
				where T1.id=".$id_cama."
				limit 1";
        $data = DB::select($cad);
        return $data;
    }

    public function GetCuartoTodosId($id, $id_ipress) {
        
        $cad = "select T1.*,T1.id_asignacion_servicio,T2.id_piso,T4.id_pabellon, T2.id_consultorio
				from cuartos T1
				inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
				inner join consultorios T3 on T2.id_consultorio=T3.id 
				inner join pisos T4 on T2.id_piso=T4.id
				inner join pabellones T5 on T4.id_pabellon=T5.id
				where T2.id=".$id." and T1.estado=0 and T1.id_establecimiento=".$id_ipress."
				order by T1.id
				";
        $data = DB::select($cad);
        return $data;
    }

    public function GetConsultorioId($id_ipress) {
        
        $cad = "select  T1.*
				from asignacion_servicios T1
				inner join cuartos T2 on T2.id_asignacion_servicio = T1.id
				inner join consultorios T3 on T1.id_consultorio=T3.id 
				inner join pisos T4 on T1.id_piso=T4.id
				inner join pabellones T5 on T4.id_pabellon=T5.id
				where T1.estado=0 and T1.id_establecimiento=".$id_ipress."
				order by T1.id
				";
        $data = DB::select($cad);
        if(count($data)>0)
			return $data[0];
		else
			return $data;
        
    }
	
	
	/*
	public function ShowallById_Ipress($id_ipress){
		$cad = "select T1.*,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon
from cuartos T1
inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
inner join consultorios T3 on T2.id_consultorio=T3.id
inner join pisos T4 on T2.id_piso=T4.id
inner join pabellones T5 on T4.id_pabellon=T5.id
where T1.id_establecimiento=".$id_ipress;
		//$cad .= " And COALESCE(T1.estado,1)!=3  ";
		
        $data = DB::select($cad);
        return $data;
		
    } */


    public function ShowallById_Ipress($id_ipress){
    $cad = "select T1.*,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon, T5.nombre prioridad
			from cuartos T1
			inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
			inner join consultorios T3 on T2.id_consultorio=T3.id
			inner join pisos T4 on T2.id_piso=T4.id
			inner join pabellones T5 on T4.id_pabellon=T5.id
			where T1.id_establecimiento=".$id_ipress." and T1.nombre_atencion ='HOSPITALIZACION'
                    And COALESCE(T1.estado,1)!=3  

            union all 

            select T1.*,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon, T7.descripcion prioridad
			from cuartos T1
			inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
			inner join consultorios T3 on T2.id_consultorio=T3.id
			inner join pisos T4 on T2.id_piso=T4.id
			inner join pabellones T5 on T4.id_pabellon=T5.id
			--inner join triajes T6 on T2.id_consultorio=T6.id
            inner join prioridades T7 on T2.id_prioridad=T7.id
			where T1.id_establecimiento=".$id_ipress." and T1.nombre_atencion ='EMERGENCIA'
                    And COALESCE(T1.estado,1)!=3 ";
	$data = DB::select($cad);
        return $data;
    }

    public function ShowallCuartosId_Ipress($id_ipress){
    $cad = "select T1.*,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon, T5.nombre prioridad
			from cuartos T1
			inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
			inner join consultorios T3 on T2.id_consultorio=T3.id
			inner join pisos T4 on T2.id_piso=T4.id
			inner join pabellones T5 on T4.id_pabellon=T5.id
			where T1.id_establecimiento=".$id_ipress." 
                    And COALESCE(T1.estado,1)!=3  ";
	$data = DB::select($cad);
        return $data;
    }

	
	public function ExisteCuarto($nombre, $id_asignacion_servicio, $id_establecimiento, $id=0) {
        $data = Cuarto::where(DB::raw('upper(nombre)'), '=', strtoupper(trim($nombre)))->where('id_asignacion_servicio', '=', $id_asignacion_servicio)->where('id_establecimiento', '=', $id_establecimiento)->WhereNull('estado')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }
	
	
	public function consultaCama($id){
        
        $cad = "select T1.*,T1.id_asignacion_servicio,T2.id_piso,T4.id_pabellon
		from cuartos T1
		inner join camas T6 on T6.id_cuarto = T1.id
		inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
		inner join consultorios T3 on T2.id_consultorio=T3.id 
		inner join pisos T4 on T2.id_piso=T4.id
		inner join pabellones T5 on T4.id_pabellon=T5.id
		where T1.id=".$id."		
		";
        $nro_camas = DB::select($cad);
        return count($nro_camas);

    }

    public function consultaCamaOcupada($id){
        
        $cad = "select T6.*, T1.nombre
		from cuartos T1
		inner join camas T6 on T6.id_cuarto = T1.id
		inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
		inner join consultorios T3 on T2.id_consultorio=T3.id 
		inner join pisos T4 on T2.id_piso=T4.id
		inner join pabellones T5 on T4.id_pabellon=T5.id
		where T6.estado=1 and T1.id=".$id."		
		";
        $nro_camas = DB::select($cad);
        return count($nro_camas);

    }

    public function actualizaEstado($id,$estado){
        
        $update=Cuarto::where('id','=',$id)->first();
        $update->estado=$estado;
        $update->save();
    }


    public function BuscaCuarto($id_cuarto,$id_ipress){
        
    $cad_cuarto = "select * 
    				from cuartos 
    				where (id_establecimiento=" . $id_ipress . " and estado=0) or (id=" . $id_cuarto . " and estado=1)";

    $data = DB::select($cad_cuarto);
    return $data[0];
    }

	public function GetByIdEstablecimientoYNombreAtencion($id_establecimiento, $nombre_atencion){
        $datos = DB::table('cuartos') 
                ->where('cuartos.id_establecimiento','=',$id_establecimiento)
				->where('cuartos.nombre_atencion','=',$nombre_atencion)
                ->select('cuartos.*')
				->orderBy('cuartos.nombre','asc')
                ->get();
		return $datos;
    }

}
