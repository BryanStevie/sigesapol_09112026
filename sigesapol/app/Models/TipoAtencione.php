<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TipoAtencione extends Model {

 	
	public function ExisteTipoAtencion($nombre, $id=0) {
        $data = TipoAtencione::where('nombre', '=', trim($nombre))->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }
	
    public function ShowTipoAtencionAll(){
        $data = DB::select("Select * From sp_listar_tipo_atencion();");
        return $data;
    }
    
    public function VerTipoAtencion($id){
        $data = TipoAtencione::where('id', '=', $id)->first();
        return $data;
    }
    
    public function listarTipoAtencionesLaboratorio()
    {
        $data = DB::select('select * from tipo_atenciones ta order by ta.id asc limit 3');
        return $data;
    }

    public function listarTipoAtencionesLaboratorioPorId($id)
    {
        $cad = "select * from tipo_atenciones ta where ta.id = " . $id;
        $data = DB::select($cad);
        return $data;
    }
}