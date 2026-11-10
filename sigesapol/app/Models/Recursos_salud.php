<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Recursos_salud extends Model
{
    protected $table = 'ipress_recurso_salud';
    
    public function ShowEstablecimeintosAll() {
        $cad = "select *, i.id as id_ipress_recurso_salud , e.id as id_ipress
         from ipress_recurso_salud i
inner join establecimientos e on i.id_ipress =e.id
where e.id_estado_reg=1  and nombre!='ALMACEN LOGISTICO DIRSAPOL' and nombre!='SALUDPOL'
order by e.nombre asc
";
        $data = DB::select($cad);
        return $data;
    }
    
    public function ShowEstablecimeintosAllPorId($id_ipress) {
        $cad = "
select *, i.id as id_ipress_recurso_salud , e.id as id_ipress
 from ipress_recurso_salud i
inner join establecimientos e on i.id_ipress =e.id and e.id = '$id_ipress'
where e.id_estado_reg=1  and nombre!='ALMACEN LOGISTICO DIRSAPOL' and nombre!='SALUDPOL'
and COALESCE(i.estado,1)!=3 order by e.nombre asc
";
        $data = DB::select($cad);
        return $data;
    }

    public function ObtenerRecursosSaludPorIdServicio($id_servicio = 0)
    {
        $cad = "SELECT rs.id, rs.nombre, rs.tipo, rs.estado 
                FROM recursos_salud rs 
                WHERE rs.id_servicio = ? 
                ORDER BY rs.nombre ASC";
        $data = DB::select($cad, [$id_servicio]);
        return $data;
    }

   public function existeIpressRecursoSalud($id_establecimiento)
    {
        return Recursos_salud::where('id_ipress', $id_establecimiento)->exists();
    }



}
