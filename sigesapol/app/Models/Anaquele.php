<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Anaquele extends Model
{

    public function ShowallById_IdFarmacia($id_farmacia){
		
		$cad = "select *
from anaqueles
where anaqueles.id_farmacia=".$id_farmacia;
		$cad .= " And COALESCE(anaqueles.estado,1)!=3 ";
		//echo $cad;
        $data = DB::select($cad);
        return $data;
		
    }

	public function ExisteAnaquel($nombre, $id_farmacia, $id=0) {
        $data = Anaquele::where('nombre', '=', trim($nombre))->where('id_farmacia', '=', $id_farmacia)->WhereNull('estado')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }  

    public function UpdateProductoAnaquel($id, $id_user) {
        
        $cad = "update producto_anaqueles set estado = 0, id_user_update =".$id_user."   where id_anaquel =".$id;
		$data = DB::update($cad);
		return 1;

    }    
    
}
