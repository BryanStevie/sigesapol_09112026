<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Menu extends Model
{
	static function getMenuByUsuario($idusuario,$url=""){
		$cad = "select m.id idmenu,m.nombremenu,m.parentid,m.url,m.icono,
p.idusuario, p.crear ,p.modificar,p.eliminar,p.lectura,
/*case when m.parentid =  0 then lpad(''||m.id,4,'0')||lpad(''||m.parentid,4,'0') else lpad(''||m.parentid,4,'0')||lpad(''||m.id,4,'0')   end as orden,*/
m.orden,
(select count(*) from menus mx where mx.parentid=m.id ) hijos
from menus m 
left join menus mp on m.parentid=mp.id 
left join permisos p on p.idmenu=m.id AND p.idusuario=".$idusuario."::integer
where m.flagregistro='1' ";
		if($url!="")$cad .= " and m.url like '/".$url."%'";
		//$cad .= " order by orden";
		//$cad .= " order by m.orden::integer";
		$cad .= " order by coalesce(mp.orden::integer,m.orden::integer)
,(case when m.parentid=0 THEN 1 else m.orden::integer end )
,m.parentid ";
		//$data = DB::select($cad);
		$data = DB::connection('pgsql_reportes')->select($cad);
        return $data;
	}
	
	public function ShowAllMenuPrincipal(){
        $cad = "select T1.*
from menus T1
/*inner join menus T2 on T1.parentid=T2.id*/
Where T1.flagregistro='1' 
and T1.parentid=0 
order by T1.orden::integer";
		$data = DB::select($cad);
        return $data;
        
    }

	public function ShowAllSub_menus(){
        $cad = "select T1.*
		from menus T1
		/*inner join menus T2 on T1.parentid=T2.id*/
		Where T1.flagregistro='1' 
		and T1.parentid!=0 
		order by T1.id::integer";
		$data = DB::select($cad);
        return $data;
        
    }

	static function getMenuByUsuariojefedarmacia($idusuario,$url=""){
		$cad = "select m.id idmenu,m.nombremenu,m.parentid,m.url,m.icono,
p.idusuario, p.crear ,p.modificar,p.eliminar,p.lectura,
/*case when m.parentid =  0 then lpad(''||m.id,4,'0')||lpad(''||m.parentid,4,'0') else lpad(''||m.parentid,4,'0')||lpad(''||m.id,4,'0')   end as orden,*/
m.orden,
(select count(*) from menus mx where mx.parentid=m.id ) hijos
from menus m 
left join menus mp on m.parentid=mp.id 
left join permisos p on p.idmenu=m.id AND p.idusuario=to_number('".$idusuario."','99999999')
where m.flagregistro='1' and m.id != '94' and m.id != '47' and m.id != '140'   ";
		if($url!="")$cad .= " and m.url like '/".$url."%'";
		//$cad .= " order by orden";
		//$cad .= " order by m.orden::integer";
		$cad .= " order by coalesce(mp.orden::integer,m.orden::integer)
,(case when m.parentid=0 THEN 1 else m.orden::integer end )
,m.parentid ";
		$data = DB::select($cad);
        return $data;
	}

	static function getMenuByUsuarionivel2($idusuario,$url=""){
		$cad = "select m.id idmenu,m.nombremenu,m.parentid,m.url,m.icono,
p.idusuario, p.crear ,p.modificar,p.eliminar,p.lectura,
/*case when m.parentid =  0 then lpad(''||m.id,4,'0')||lpad(''||m.parentid,4,'0') else lpad(''||m.parentid,4,'0')||lpad(''||m.id,4,'0')   end as orden,*/
m.orden,
(select count(*) from menus mx where mx.parentid=m.id ) hijos
from menus m 
left join menus mp on m.parentid=mp.id 
left join permisos p on p.idmenu=m.id AND p.idusuario=to_number('".$idusuario."','99999999')
where m.flagregistro='1' and m.id != '19' and m.id != '1' and m.id != '94' and m.id != '47' and m.id != '140' and m.id != '18' ";
		if($url!="")$cad .= " and m.url like '/".$url."%'";
		//$cad .= " order by orden";
		//$cad .= " order by m.orden::integer";
		$cad .= " order by coalesce(mp.orden::integer,m.orden::integer)
,(case when m.parentid=0 THEN 1 else m.orden::integer end )
,m.parentid ";
		$data = DB::select($cad);
        return $data;
	}
	public function ShowAllMenu($tipo=0){
        $cad = "select T1.*,T2.nombremenu menuprincipal
from menus T1
inner join menus T2 on T1.parentid=T2.id
Where T1.flagregistro='1' ";
		if($tipo > 0)$cad .= " And T1.parentid=".$tipo;
$cad .=" order by T2.orden::integer,T1.orden::integer";
		$data = DB::select($cad);
        return $data;
        
    }
	
}
