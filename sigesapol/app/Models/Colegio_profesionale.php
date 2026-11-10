<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Colegio_profesionale extends Model
{
    public function ShowAll(){
		$cad = "select colegio_profesionales.*
                from colegio_profesionales";
		$cad .= " Where COALESCE(colegio_profesionales.estado,1)!=3 ";
		$data = DB::select($cad);
        return $data;
    }

    public function ShowAll_v2(){
		$cad = "select colegio_profesionales.*
                from colegio_profesionales";
		$cad .= " Where COALESCE(colegio_profesionales.estado,1)!=3 ";
    $cad .= " order by descripcion asc";
		$data = DB::select($cad);
        return $data;
    }
    
    public function ObtenerColegioProfesional($id_medico) {
        $datos=DB::table('medicos') 
                ->join('colegio_profesionales','medicos.id_colegio_profesional','=','colegio_profesionales.id')
                ->where('medicos.id','=',$id_medico)
                ->select('colegio_profesionales.*','medicos.nombre as nombre_medico','medicos.paterno','medicos.materno')                
                ->first();
        return $datos;
    }
	
}
