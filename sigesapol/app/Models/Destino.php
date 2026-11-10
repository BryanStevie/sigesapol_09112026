<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Destino extends Model {

    public $timestamps = false;

    public function ShowDestino($id_condicion){
    	$cad = "select id, codigo, descripcion, estado, id_condicion
 				from destinos where estado = 'N' and id_condicion = '$id_condicion' Order by descripcion asc";
        $data = DB::select($cad);
        return $data;
    }

    public function TipoDestino() {
        $destinos = Destino::select('id', 'codigo', 'descripcion', 'estado', 'id_condicion')
                           ->whereIn('estado', ['N', 'I'])
                           ->orderBy('descripcion', 'asc')
                           ->get();
        
        return $destinos;
    }

}