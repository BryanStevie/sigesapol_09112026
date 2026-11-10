<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Cama_emergencia extends Model
{
	public function ShowallById_Ipress($id_ipress){
		
		$cad = "select T1.*,T2.nombre topico
from cama_emergencias T1
inner join topicos T2 on T1.id_topico=T2.id
where T1.id_establecimiento=".$id_ipress;
		$cad .= " And COALESCE(T1.estado,1)!=3  ";
        $data = DB::select($cad);
        return $data;
		
    }
	
	public function ExisteCama($nombre, $id_topico, $id_establecimiento, $id=0) {
        $data = Cama_emergencia::where('nombre', '=', $nombre)->where('id_topico', '=', $id_topico)->where('id_establecimiento', '=', $id_establecimiento)->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }
	
	
	
}
