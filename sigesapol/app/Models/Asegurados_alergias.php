<?php

namespace App\Models;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class Asegurados_alergias extends Model
{
    protected $table = 'asegurados_alergias';

    public function listaAlergiasMedicasByFiltro($tipoatenciones, $estado)
    {
        $sql = "";
        if (!empty($tipoatenciones) && !empty($estado)) {
            $sql = "where ta.id='" . $tipoatenciones . "' and a.estado='" . $estado . "'";
        } else if (empty($tipoatenciones) && !empty($estado)) {
            $sql = "where a.estado='" . $estado . "'";
        } else if (!empty($tipoatenciones) && empty($estado)) {
            $sql = "where ta.id='" . $tipoatenciones . "'";
        }

        $cad = "select a.id,a.estado,to_char(a.created_at::date,'DD/MM/YYYY HH:MM:SS') as fecha,p.nombre as medicamento,u.name as nombre,u.paterno,u.materno,ta.nombre as tipo_atencion from asegurados_alergias a
        left join users u ON a.id_usuario_creacion=u.id
        left join productos p ON a.id_medicamento=p.id and p.id_tipo=1 and p.estado='1'
        left join tipo_atenciones as ta ON a.id_tipo_atencion=ta.id
        $sql";
        $data = DB::select($cad);
        return $data;
    }

    public function listaAlergiasMedicasByFiltroV2($id_asegurado, $tipoatenciones, $estado)
    {
        $sql = " WHERE a.id_asegurado = '" . $id_asegurado . "' ";

        if (!empty($tipoatenciones)) {
            $sql .= " AND ta.id = '" . $tipoatenciones . "' ";
        }

        if ($estado !== null && $estado !== '') {
            $sql .= " AND a.estado = '" . $estado . "' ";
        }

        $cad = "
            SELECT
                a.id,
                a.id_asegurado,
                a.estado,
                TO_CHAR(a.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha,
                p.nombre AS medicamento,
                u.name AS nombre,
                u.paterno,
                u.materno,
                ta.nombre AS tipo_atencion
            FROM asegurados_alergias a
            LEFT JOIN users u
                ON a.id_usuario_creacion = u.id
            LEFT JOIN productos p
                ON a.id_medicamento = p.id
            LEFT JOIN tipo_atenciones ta
                ON a.id_tipo_atencion = ta.id
            " . $sql;

        return DB::select($cad);
    }

    public function verAlergiasMedicasById($id)
    {
        $cad = "select a.id_medicamento,a.id,a.causa,a.resumen,to_char(a.created_at::date,'DD/MM/YYYY HH:MM:SS') as fecha,p.nombre as medicamento,u.name as nombre,u.paterno,u.materno,ta.nombre as tipo_atencion from asegurados_alergias a
        left join users u ON a.id_usuario_creacion=u.id
        left join productos p ON a.id_medicamento=p.id
        left join tipo_atenciones as ta ON a.id_tipo_atencion=ta.id
        where a.id='" . $id . "'";
        $data = DB::select($cad);
        return $data;
    }
}
