<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Enfermeria_cpt extends Model
{
    protected $table = 'nota_enfermeria_cpt';
    public $timestamps = false;

    public function getlista_nota_ingreso_cpt($p)
    {
        $fecha_evolucion_bus_inicial_listado = $p[0];
        $fecha_evolucion_bus_final_listado = $p[1];
        $id_turno = $p[2];
        $tipo_atencion = $p[3];
        $id_asegurado = $p[4];
        $id_enfermeria = $p[5];
        $id_ext = $p[6];

        $hasNull = (is_null($p[0]) || $p[0] === '') &&
            (is_null($p[1]) || $p[1] === '') &&
            (is_null($p[2]) || $p[2] === '') &&
            (is_null($p[3]) || $p[3] === '') &&
            (is_null($p[5]) || $p[5] === '') &&
            (is_null($p[6]) || $p[6] === '');

        $query = DB::table('nota_enfermeria_cpt as ne_c')
            ->leftJoin('tipo_atenciones as tip_a', 'ne_c.id_tipo_atenciones', '=', 'tip_a.id')
            ->leftJoin('turnos as t', 'ne_c.id_turno', '=', 't.id')
            ->leftJoin('users', 'ne_c.id_usuario_registro', '=', 'users.id')
            ->select(
                't.descripcion as descripcion_turno',
                'ne_c.*',
                'ne_c.id as id_cpt',
                DB::raw("to_char(ne_c.updated_at::date,'DD/MM/YYYY') as fecha_cpt"),
                DB::raw("TO_CHAR(ne_c.updated_at::time,'HH:MI:SS AM') as hora_cpt"),
                'tip_a.nombre as descripcion_tipo_atenciones',
                'users.name',
                'users.paterno',
                'users.materno'
            )
            ->where('ne_c.estado', '=', 1)
            ->where('ne_c.id_ext', '=', $id_ext)
            ->where('ne_c.id_asegurado', '=', $id_asegurado);

        if (!$hasNull) {
            /* if (!empty($id_enfermeria)) {
                $query->where('ne_c.id_enfermeria', '=', $id_enfermeria);
            } */ 
            /* if (!empty($id_ext)) {
                $query->where('ne_c.id_ext', '=', $id_ext);
            } */ 
            if (!empty($tipo_atencion)) {
                $query->where('ne_c.id_tipo_atenciones', $tipo_atencion);
            }
            if ($fecha_evolucion_bus_inicial_listado != "" && $fecha_evolucion_bus_final_listado != "") {
                $query->whereBetween(DB::raw('CAST(ne_c.created_at AS DATE)'), [$fecha_evolucion_bus_inicial_listado, $fecha_evolucion_bus_final_listado]);
            }
            if ($id_turno != "") {
                $query->where('ne_c.id_turno', $id_turno);
            }
        }

        $data = $query->get();
        if ($data == null) {
            return null;
        } else {
            return $data;
        }
    }

    public function getver_nota_ingreso_cpt($id_cpt)
    {
        $cad = "SELECT users.name as nombre_usuario, users.paterno as paterno_usuario, users.materno as materno_usuario,t.descripcion as turno, ne_e.*, ne_e.id as id_cpt, to_char(ne_e.updated_at::date,'DD/MM/YYYY') fecha_cpt, 
    TO_CHAR(ne_e.updated_at::time,'HH:MI:SS AM') AS hora_cpt,
    tip_a.nombre as descripcion_tipo_atenciones 
    FROM nota_enfermeria_cpt ne_e 
    LEFT join tipo_atenciones  as  tip_a on ne_e.id_tipo_atenciones = tip_a.id
    LEFT join turnos  as  t on ne_e.id_turno = t.id
    LEFT join users  on  ne_e.id_usuario_registro = users.id 
    WHERE ne_e.id= " . $id_cpt;
        $data = DB::select($cad);
        if ($data == null) {
            return null;
        } else {
            return $data;
        }
    }

    public function diagnosticos_hospi_admision($id_ext_enfermeria)
    {
        $diagnosticos_hospi_id = Hospitalizacione::where("id", "=", $id_ext_enfermeria)->select("id_diagnostico_inicial", "id_dx_2", "id_dx_3")->first();
        $diagnosticos = Diagnostico::whereIn("id", [$diagnosticos_hospi_id->id_diagnostico_inicial, $diagnosticos_hospi_id->id_dx_2, $diagnosticos_hospi_id->id_dx_3])->select([
            'id as id_diagnostico',
            'codigo',
            'nombre'
        ])->get();
        return $diagnosticos;
    }

    public function diagnosticos_pacientes_prestacion($id_prestacion)
    {
        $cad = "SELECT 
            codigo,
            t1.id_diagnostico,
            (CASE 
            WHEN COALESCE(t1.id_tipo_diagnostico, 0) = 1 THEN 'PRESUNTIVO'
            WHEN COALESCE(t1.id_tipo_diagnostico, 0) = 2 THEN 'DEFINITIVO'
            WHEN COALESCE(t1.id_tipo_diagnostico, 0) = 3 THEN 'REITERATIVO'
            ELSE 'OTRO'
            END) as tipo,
            t1.id_categoria,
              (CASE 
                WHEN codigo = 'Z21.X' OR codigo ILIKE 'B20%' THEN '' 
                ELSE nombre
            END) AS nombre,
            td.descripcion,
            'Atencion' as clasificacion 
            FROM 
              receta_diagnosticos t1
            INNER JOIN diagnosticos t2 ON t1.id_diagnostico = t2.id AND t1.id_interconsulta = 0
            INNER JOIN tipo_diagnosticos td ON t1.id_tipo_diagnostico = td.id
            WHERE t1.id_prestacion ='" . $id_prestacion . "' AND t1.estado = 1
            GROUP BY 
              codigo, 
              t1.id_diagnostico,
              t1.id_tipo_diagnostico ,
            -- t1.id_evaluacion, 
              t1.id_categoria,
              nombre,
              td.descripcion,
              clasificacion 
            ORDER BY 
              id_diagnostico desc";

        $data = DB::select($cad);
        return $data;
    }

    public function getver_nota_ingreso_cpt_cpms($id_cpt)
    {
        $data = DB::table('nota_enfermeria_cpt as nee')
            ->leftJoin('cpt_cpms as cpms', 'nee.id', '=', 'cpms.id_nota_enfermeria_cpt')
            ->leftJoin('procedimientos as proc', 'cpms.id_procedimiento', '=', 'proc.id')
            ->leftJoin('diagnosticos as d', 'cpms.id_diagnostico', '=', 'd.id')
            ->where('nee.id', $id_cpt)
            ->where(function ($query) {
                $query->where('cpms.estado_reg', 1)
                    ->orWhereNull('cpms.estado_reg');
            })
            ->select(
                'cpms.*',
                'proc.codigo as codigo_procedimiento',
                'proc.descripcion as nombre_procedimiento',
                'd.id as id_diagnostico',
                'd.nombre as nombre_diagnostico',
                'd.codigo as codigo_diagnostico'
            )
            ->get();
        return $data;
    }
}
