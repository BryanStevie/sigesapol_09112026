<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class EstablecimientoSalud extends Model
{
    //
    public function ShowAll(){
        $datos=DB::table('establecimientos') 
                ->select('establecimientos.*')
                ->get();
        return $datos;
        
    }

    
    public function ShowAllEstablecimientos($id_establecimiento = 0){
        $cad = "select T1.* 
                from establecimientos T1
                where 1=1
                And Length(codigo)=8 And id_estado_reg>0 ";
                if($id_establecimiento > 0)$cad .= " And T1.id!=".$id_establecimiento;
        $data = DB::select($cad);
        return $data;        
    }

    public function GetById($id){
        $datos=DB::table('establecimientos') 
                ->select('establecimientos.nombre','establecimientos.id','establecimientos.nivel','establecimientos.id_egestion','establecimientos.codigo')
                ->where('establecimientos.id','=',$id)
                ->get();
        return $datos;       
    }

    public function GetByIdFarmacia($id){
        $datos=DB::table('establecimientos') 
                ->join('farmacias','establecimientos.id','=','farmacias.id_establecimiento')
                ->select('establecimientos.id','establecimientos.codigo','establecimientos.nombre','establecimientos.nivel')
                ->where('farmacias.id','=',$id)
                ->first();
        return $datos;       
    }    

    public function EstablecimientosSinSistema(){
        $cad = "SELECT *
                FROM establecimientos
                WHERE id NOT IN (SELECT id_establecimiento FROM farmacias)";
        $data = DB::select($cad);
        return $data;        
    }

    public function ShowEstablecimientoByUbigeo($ubigeo) {
	
        $cad = "select * from establecimientos T1 where ubigeo=".$ubigeo." order by nombre asc";
        $data = DB::select($cad);
        return $data;
    }
	
	public function GetEstablecimientoUbigeosByUbigeo($ubigeo) {
	
        $cad = "select T1.*,T2.nombre establecimiento 
		from establecimiento_ubigeos T1 
		inner join establecimientos T2 on T1.id_establecimiento = T2.id
		where T1.ubigeo='".$ubigeo."' order by T1.id asc";
        $data = DB::select($cad);
        return $data;
    }
	
	public function ShowEstablecimientoNivel($nivel) {
    
        $cad = "select * from establecimientos T1 where nivel<=".$nivel." order by nombre asc";
        $data = DB::select($cad);
        return $data;
    }

    public function establecimiento_yaregistrado($codigo){
        $datos=DB::table('establecimientos') 
                ->where('establecimientos.codigo','=',$codigo)
                ->select('establecimientos.*')
                ->first();
        return $datos;                      
    }
	
	
}
