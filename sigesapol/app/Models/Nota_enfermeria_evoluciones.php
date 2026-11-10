<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Punto;
use DB;


class Nota_enfermeria_evoluciones extends Model
{

    protected $table = 'nota_enfermeria_evoluciones';

    public function getlista_nota_ingreso_evoluciones($p)
    {
        $fecha_evolucion_bus_inicial_listado = $p[0];
        $fecha_evolucion_bus_final_listado = $p[1];
        $id_turno = $p[2];
        $tipo_atencion = $p[3];
        $id_asegurado = $p[4];

        $hasNull = (is_null($p[0]) || $p[0] === '') &&
            (is_null($p[1]) || $p[1] === '') &&
            (is_null($p[2]) || $p[2] === '') &&
            (is_null($p[3]) || $p[3] === '') &&
            (is_null($p[4]) || $p[4] === '');

        $query = DB::table('nota_enfermeria_evoluciones as ne_e')
            ->leftJoin('tipo_atenciones as tip_a', 'ne_e.id_tipo_atenciones', '=', 'tip_a.id')
            ->leftJoin('turnos as t', 'ne_e.id_turno', '=', 't.id')
            ->leftJoin('users', 'ne_e.id_usuario_registro', '=', 'users.id')
            ->select(
                't.descripcion as descripcion_turno',
                'ne_e.*',
                'ne_e.id as id_evoluciones',
                DB::raw("to_char(ne_e.created_at::date,'DD/MM/YYYY') as fecha_evol"),
                DB::raw("TO_CHAR(ne_e.created_at::time,'HH:MI:SS AM') as hora_evol"),
                'tip_a.nombre as descripcion_tipo_atenciones',
                'users.name',
                'users.paterno',
                'users.materno'
            )
            ->where('ne_e.estado', '=', 1)
            ->where('ne_e.id_asegurado', '=', $id_asegurado);

        if (!$hasNull) {
            if (!empty($tipo_atencion)) {
                $query->where('ne_e.id_tipo_atenciones', $tipo_atencion);
            }
            if ($fecha_evolucion_bus_inicial_listado != "" && $fecha_evolucion_bus_final_listado != "") {
                $query->whereBetween(DB::raw('CAST(ne_e.created_at AS DATE)'), [$fecha_evolucion_bus_inicial_listado, $fecha_evolucion_bus_final_listado]);
            }
            if ($id_turno != "") {
                $query->where('ne_e.id_turno', $id_turno);
            }
        }

        $data = $query->get();
        if ($data == null) {
            return null;
        } else {
            return $data;
        }
    }

    public function getver_nota_ingreso_evoluciones($id_evoluciones)
    {

        $cad = "SELECT users.name as nombre_usuario,users.paterno as paterno_usuario,users.materno as materno_usuario,t.descripcion as turno,enf.id_tipo_atencion,enf.id_ext_enfermeria,ne_e.*, ne_e.id as id_evoluciones, to_char(ne_e.created_at::date,'DD/MM/YYYY') fecha_evol, 
    TO_CHAR(ne_e.created_at::time,'HH:MI:SS AM') AS hora_evol,
    tip_a.nombre as descripcion_tipo_atenciones 
    FROM nota_enfermeria_evoluciones ne_e 
    LEFT join enfermerias  as  enf on ne_e.id_enfermeria = enf.id
    LEFT join tipo_atenciones  as  tip_a on ne_e.id_tipo_atenciones = tip_a.id
    LEFT join turnos  as  t on ne_e.id_turno = t.id
    LEFT join users  on  ne_e.id_usuario_registro = users.id 
    WHERE ne_e.id= " . $id_evoluciones;
        $data = DB::select($cad);
        if ($data == null) {
            return null;
        } else {
            return $data;
        }
    }

    public function getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evoluciones, $tipo_de_seleccion)
    {

        $cad = "SELECT ne_evo_d.id as id_detalle,ne_evo_d.*, parm.*
    FROM nota_enfermeria_evoluciones_detalles ne_evo_d
    LEFT JOIN parametros parm ON  ne_evo_d.id_de_seleccion = parm.id
    WHERE ne_evo_d.estado=1 and ne_evo_d.tipo_de_seleccion= '" . $tipo_de_seleccion . "' AND ne_evo_d.id_enfermeria_evoluciones= " . $id_evoluciones;
        $data = DB::select($cad);
        if ($data == null) {
            return null;
        } else {
            return $data;
        }
    }

    public function getver_nota_ingreso_evoluciones_procedimientos($id_evoluciones)
    {
        $evolucion = Nota_enfermeria_evoluciones::where("id", "=", $id_evoluciones)->first();
        if ($evolucion->id_prestacion) {
            $data = DB::table('nota_enfermeria_evoluciones as nee')
                ->leftJoin('procedimientos_diagnoticos as pd', 'nee.id', '=', 'pd.id_evolucion')
                ->leftJoin('prestacion_procedimientos as pp', function ($join) {
                    $join->on('pd.id_procedimientos', '=', 'pp.id_procedimiento')
                        ->on('pp.id_evolucion_enfermeria', '=', 'nee.id');
                })
                ->leftJoin('procedimientos as proc', 'pp.id_procedimiento', '=', 'proc.id')
                ->leftJoin('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
                ->where('pp.enfermeria', 1)
                ->where('pp.id_estado_reg', 1)
                ->where('nee.id', $id_evoluciones)
                ->select(
                    'pp.id_evolucion_enfermeria',
                    'pp.id',
                    'pp.tiempo as procedimiento_tiempo',
                    'pp.id_procedimiento',
                    'proc.codigo',
                    'proc.descripcion as nombre_procedimiento',
                    'pp.cantidad',
                    'd.id as id_diagnostico',
                    'd.nombre as nombre_diagnostico'
                )
                ->get();
            if ($data == null) {
                $procedimientos_evolucion_admision =  DB::table('evolucion_procedimiento_diagnosticos as epd')
                    ->join('procedimientos as p', 'epd.id_procedimiento', '=', 'p.id')
                    ->join('diagnosticos as d', 'epd.id_diagnostico', '=', 'd.id')
                    ->where('id_evolucion_enfermeria', $id_evoluciones)
                    ->select(
                        'epd.id',
                        'epd.id_procedimiento',
                        'epd.id_diagnostico',
                        'epd.cantidad',
                        'p.descripcion as nombre_procedimiento',
                        'p.codigo'
                    )
                    ->get();
                if ($procedimientos_evolucion_admision == null) {
                    return null;
                } else {
                    return $procedimientos_evolucion_admision;
                }
            } else {
                return $data;
            }
        } else {
            $procedimientos_evolucion_admision =  DB::table('evolucion_procedimiento_diagnosticos as epd')
                ->join('procedimientos as p', 'epd.id_procedimiento', '=', 'p.id')
                ->join('diagnosticos as d', 'epd.id_diagnostico', '=', 'd.id')
                ->where('id_evolucion_enfermeria', $id_evoluciones)
                ->select(
                    'epd.id',
                    'epd.id_procedimiento',
                    'epd.id_diagnostico',
                    'epd.cantidad',
                    'p.descripcion as nombre_procedimiento',
                    'p.codigo'
                )
                ->get();
            if ($procedimientos_evolucion_admision == null) {
                return null;
            } else {
                return $procedimientos_evolucion_admision;
            }
        }
    }

    public function getver_nota_ingreso_evoluciones_invasivos($id_evoluciones)
    {

        $cad = "select inv.id,inv.descripcion_corta as tipo,inv2.fecha,inv2.lugar,accion,inv2.id as id_evolucion_invasivo  
        from invasivos as inv 
        left join nota_enfermeria_evoluciones_invasivos as inv2 on inv.id =inv2.tipo
        where inv2.id_evolucion='" . $id_evoluciones . "' and estado=1 order by inv.id";
        $data = DB::select($cad);
        if ($data == null) {
            return null;
        } else {
            return $data;
        }
    }

    public function lista_nota_ingreso_evoluciones(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones;
        $p[] = $request->fecha_evolucion_bus_inicial_listado;  //0
        $p[] = $request->fecha_evolucion_bus_final_listado;  //1 
        $p[] = $request->id_turno;  //2 
        $p[] = $request->tipo_atenciones;  //3

        $data = $objNota_enfermeria_evoluciones->getlista_nota_ingreso_evoluciones($p);
        return json_encode($data);
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

    public function agregarPuntos($data)
    {
        $data = [
            'x' => $data['x'],
            'y' => $data['y'],
            'color' => $data['color'],
            'tama' => $data['tama'],
            'id_usuario' => Auth::user()->id,
            'id_enfermeria' =>  $data['id_enfermeria'],
            'id_tipo_atenciones' => $data['id_tipo_atenciones'],
            'descripcion_proceso' => $data['descripcion_proceso'],
            'tipo_registro' => $data['tipo_registro']
        ];
        $data = DB::table('puntos')->insert($data);
        return $data;
    }
    public function pintarPuntosEvolucion($id_enfermeria, $id_tipo_atenciones)
    {
        $modelPuntos = new Punto();
        $data = $modelPuntos::where('id_enfermeria', $id_enfermeria)->where('id_tipo_atenciones', $id_tipo_atenciones)->whereIn('tipo_registro', ['NOTA_ENFERMERIA', 'EVOLUCION'])->get();
        return $data;
    }
}
