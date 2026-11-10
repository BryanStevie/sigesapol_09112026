<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Plantilla_radiologia extends Model
{
    //
    public function ShowAll($nivel){
		$cad = "select plantilla_radiologias.*, categoria_radiologias.nombre
        from plantilla_radiologias
        inner join categoria_radiologias ON categoria_radiologias.id = plantilla_radiologias.id_categoria_radiologia
        inner join consultorios on consultorios.id = categoria_radiologias.id_servicio
        where categoria_radiologias.nivel = {$nivel}
        ";
		//$cad .= " Where COALESCE(plantilla_radiologias.estado,'1')!='3' ";
		$data = DB::select($cad);
        return $data;
		
    }
    
    public function ExistePlantilla($nombre, $id_categoria_radiologia) {
        $data = Plantilla_radiologia::where(DB::raw('upper(nombre_plantilla)'), '=', trim(strtoupper($nombre)))->where('estado', '=', '1')->where('id_categoria_radiologia', '=', $id_categoria_radiologia)->first();
        return isset($data) ? true : false;
    }

    public function PlantillaServicio($id, $nivel){
        
        $cad = "select plantilla_radiologias.*, categoria_radiologias.nombre
        from plantilla_radiologias
        inner join categoria_radiologias on categoria_radiologias.id = plantilla_radiologias.id_categoria_radiologia
        inner join consultorios on consultorios.id = categoria_radiologias.id_servicio
        ";
        $cad .= " Where COALESCE(plantilla_radiologias.estado,'1')!='3' and categoria_radiologias.nivel = {$nivel} and plantilla_radiologias.id_categoria_radiologia='".$id."'";
        $data = DB::select($cad);
        return $data;
    }
	
}
