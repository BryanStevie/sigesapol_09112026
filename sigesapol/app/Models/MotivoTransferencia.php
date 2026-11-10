<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MotivoTransferencia extends Model {

	function getMotivoTransferenciaActivo() {
        $cad = "Select id, nombre From motivo_transferencias Where tipo_motivo='A' And estado='1' Order By orden";
        $data = DB::select($cad);
        return $data;
    }

    function getMotivoTransferenciaNotaEntrada() {
        $cad = "Select id, nombre From motivo_transferencias Where tipo_motivo='N' And estado='1' Order By orden";
        $data = DB::select($cad);
        return $data;
    }
	public function ExisteMotivoTransferencia($nombre, $id=0) {
        $data = MotivoTransferencia::where('nombre', '=', trim($nombre))->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }
	
    public function ShowMotivoTransferenciaAll(){
        $data = DB::select("Select * From sp_listar_motivo_transferencia();");
        return $data;
    }
    
	public function getNroOrden(){
		$cad = "select (count(*)+1) nro_orden from motivo_transferencias";
		$data = DB::select($cad);
        return $data;
	}

}