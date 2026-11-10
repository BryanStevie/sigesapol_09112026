<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Recursos_salud_detalle extends Model
{
    protected $table = 'ipress_recurso_salud_detalle';
     
    protected $primaryKey = 'id';
    
   /* public function ShowAll($id_ipress) {
    $cad = "SELECT * ,r.id as id_ipress_recurso_salud_detalle,
     r.estado as estado_rec FROM ipress_recurso_salud_detalle r
            inner join users u on r.id_user_created = u.id
            
            WHERE id_ipress = ? 
            ORDER BY periodo DESC";

        return DB::select($cad, [$id_ipress]);
    }*/

    public function ShowAll($id_ipress, $fechaDesde = null, $fechaHasta = null) {
    $sql = "SELECT *, r.id as id_ipress_recurso_salud_detalle,
                   r.estado as estado_rec , r.created_at as fecha_creacion_recurso
            FROM ipress_recurso_salud_detalle r
            INNER JOIN users u ON r.id_user_created = u.id
            WHERE r.id_ipress = ?";

    $params = [$id_ipress];

    if ($fechaDesde) {
        $sql .= " AND DATE(r.created_at) >= ?";
        $params[] = $fechaDesde;
    }

    if ($fechaHasta) {
        $sql .= " AND DATE(r.created_at) <= ?";
        $params[] = $fechaHasta;
    }

    $sql .= " ORDER BY r.created_at desc,  r.periodo DESC";

    return DB::select($sql, $params);
}



}
