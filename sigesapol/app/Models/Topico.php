<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Topico extends Model
{

    public function ShowallById_Ipress($id_ipress){
		
		$cad = "select *
from topicos
where topicos.id_establecimiento=".$id_ipress;
		$cad .= " And COALESCE(topicos.estado,1)!=3  ";
        $data = DB::select($cad);
        return $data;
		
    }

	public function ExisteTopico($nombre, $id_establecimiento, $id=0) {
        $data = Topico::where('nombre', '=', $nombre)->where('id_establecimiento', '=', $id_establecimiento)->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }    
    
}
