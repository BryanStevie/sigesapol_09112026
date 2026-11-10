<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Permiso extends Model
{
	
	function getPermisoPrincipalByUsuario($idusuario){
		$cad = "select m.id idmenu
from menus m left join permisos p on p.idmenu=m.id AND p.idusuario=to_number('".$idusuario."','99999999')
where m.flagregistro='1' 
and parentid=0
and exists (		select 1
			from menus m1 left join permisos p1 on p1.idmenu=m1.id AND p1.idusuario=to_number('".$idusuario."','99999999')
			where m1.flagregistro='1' 
			and parentid=m.id 
			and (crear='1' or modificar='1' or eliminar='1' or lectura='1')
			)";
		$data = DB::select($cad);
        return $data;
	}

  public function getPermissionsByUserId($userId)
  {
    $permissions = DB::table('permisos')
        ->select('idmenu', 'crear', 'modificar', 'eliminar', 'lectura')
        ->where('idusuario', $userId)
        ->get();
    return $permissions;  
  }
}
