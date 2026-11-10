<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
    //
    
    
    public function GetContadorByIdFarmacia($id_farmacia){
        $year = date('Y');
        $data= DB::table('requerimientos')
                ->select(DB::raw('coalesce(max(contador),0) as numero'))
                ->where('id_farmacia',$id_farmacia)
                ->where('id_establecimiento','=',0)
                ->where('ano',$year)
                ->first();
        return $data;
    }

    public function GetContadorByIdConsultorio($id_consultorio, $id_establecimiento){
        $year = date('Y');
        $data= DB::table('requerimientos')
                ->select(DB::raw('coalesce(max(contador),0) as numero'))
                ->where('id_consultorio',$id_consultorio)
                ->where('id_establecimiento',$id_establecimiento)
                ->where('ano',$year)
                ->first();
        return $data;
    }
    
    public function ShowRequerimientosByIdFarmacia($id_farmacia,$id_consultorio = 0,$id_establecimiento = 0){
        /*$data= DB::table('requerimientos')
                ->select(DB::raw('extract(year from created_at) as anio_reque'), 'requerimientos.*')
                ->where('id_farmacia',$id_farmacia)
                ->where('estado','>',0)
                ->orderBy('requerimientos.id','desc')
                ->get();*/
        $cad = "Select r.*, extract(year from r.created_at) as anio_reque, f.nombre nom_destino From requerimientos r
Inner Join farmacias f On r.id_destino = f.id
Where r.estado>'0' ";
        if($id_consultorio!='' or $id_consultorio>0):
            $cad .= " And r.id_consultorio=".$id_consultorio." And r.id_establecimiento=".$id_establecimiento;
        else:
            $cad .= " And r.id_farmacia=".$id_farmacia." And r.id_establecimiento=0";
        endif;
        $cad .= "  Order By r.created_at desc";
        
        $data = DB::select($cad);
        return $data;
    }

    public function ShowRequerimientosByFarmacia($id_destino){
        $cad = "Select r.*, extract(year from r.created_at) as anio_reque, f.nombre nom_destino, c.nombre nombre_consultorio From requerimientos r
Inner Join farmacias f On r.id_farmacia = f.id
Left Join establecimientos e On e.id = r.id_establecimiento
Left Join consultorios c On c.id = r.id_consultorio
Where r.id_destino=".$id_destino." And r.estado>'1' Order By r.id desc";
        $data = DB::select($cad);
        return $data;
    }
    
    public function ShowRequerimientosByIdRequerimiento($id_requerimiento) {
        $data= DB::table('requerimientos')
                ->select('requerimientos.*')
                ->where('id',$id_requerimiento)
                ->first();
        return $data;
    }
    
    public function ShowRequerimientoByNroRequerimientoAndIdFarmacia($nro_requerimiento,$id_farmacia) {
        $data= DB::table('requerimientos')
                ->select('requerimientos.*')
                ->where('contador','=',$nro_requerimiento)
                ->where('id_farmacia','=',$id_farmacia)
                ->where('estado','=','2')
                ->first();
        return $data;
    }
    
    public function GetRequerimientoById($id) {
        /*$data= DB::table('requerimientos')
                ->select(DB::raw('extract(year from created_at) as anio_reque'), 'requerimientos.*')
                ->where('id','=',$id)
                ->first();*/

        $cad = "Select r.*, extract(year from r.created_at) as anio_reque, f.nombre nom_destino, u_create.name as nombre_user_create, u_create.paterno as apellido_paterno_create, u_create.materno as apellido_materno_create, u_update.name as nombre_user_update, u_update.paterno as apellido_paterno_update, u_update.materno as apellido_materno_update, c.nombre nombre_consultorio From requerimientos r
Inner Join farmacias f On r.id_destino = f.id
Left Join establecimientos e On r.id_establecimiento = e.id
Left Join consultorios c On r.id_consultorio = c.id
left Join users u_create On r.id_user_created = u_create.id
left Join users u_update On r.id_user_update = u_update.id
Where r.id=".$id." limit 1";
        $data = DB::select($cad);
        return $data[0];
    }
	
	public function GetRequerimientoPendienteAll($id_farmacia = 0) {
		/*
        $data= DB::table('requerimientos')
                ->select('requerimientos.*')
				->where('cod_pecosa','>',0)
                ->get();
        return $data;
		*/
	/*	$cad = "select *
				from requerimientos
				where requerimientos.cod_pecosa > 0
				and requerimientos.cod_pecosa::text not in (select numero from guia_entradas)
				order By requerimientos.id asc";
        Wilian*/
            
            $cad = "Select id id_reque, contador nro_reque, extract(year from created_at) anio_reque From requerimientos 
Where estado='2' And id_farmacia=".$id_farmacia." Order By requerimientos.id Desc";
        $data = DB::select($cad);
        return $data;
    }
    
    public function GetNroRequerimientoPendienteByIdOrigen($id_destino, $id_farmacia) {
            $cad = "Select id id_reque, contador nro_reque, extract(year from created_at) anio_reque From requerimientos 
Where estado='2' And id_destino='".$id_destino."' And id_farmacia=".$id_farmacia." Order By requerimientos.id Desc";
        $data = DB::select($cad);
        return $data;
    }
    
    public function showProductosRequerimientoByIdPecosa($id_reque){
            $cad = "Select r.id id_reque, contador nro_reque, extract(year from r.created_at) anio_reque, p.id id, p.codigo codigo, p.nombre,p.presentacion_comercial From requerimientos r
Inner Join req_productos rp On r.id = rp.id_requerimiento
Inner join productos p On rp.codigo_producto = p.codigo
Where rp.estado='1' And r.id=".$id_reque."
Order By p.nombre";
        $data = DB::select($cad);
        return $data;
    }
    
    
}
