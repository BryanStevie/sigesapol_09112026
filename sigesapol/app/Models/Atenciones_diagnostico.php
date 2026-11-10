<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Atenciones_diagnostico extends Model {

	public function ShowAtenciones_diagnostico_porClase($id_emergencia,$id_clasificacion_diagnosticos){
		$cad = "select id, id_emergencia, id_hospitalizacion, id_clasificacion_diagnosticos, id_tipo_diagnostico,	id_diagnostico
 				from atenciones_diagnosticos where id_emergencia = '$id_emergencia' and id_clasificacion_diagnosticos = '$id_clasificacion_diagnosticos'";
        $data = DB::select($cad);
        return $data;
	}
}
