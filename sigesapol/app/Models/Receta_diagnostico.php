<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Receta_diagnostico extends Model
{
    protected $table = 'receta_diagnosticos';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_receta_vale',
        'id_diagnostico',
        'id_tipo_diagnostico',
        'estado',
        'id_prestacion',
        'created_at',
        'created_us',
        'updated_at',
        'updated_us',
        'deleted_at',
        'deleted_us',
        'id_clasificacion_diagnosticos',
        'id_evaluacion',
        'id_emergencia',
        'id_hospitalizacion',
        'id_interconsulta',
        'id_categoria',
        'id_cambio_cama',
        'id_intervencion',
        'id_consejeria',
        'clasificacion_cqx'
    ];
    public function DiagnosticosByIdReceta($id_receta_vale)
    {
        /*
       $data=DB::table("receta_diagnosticos")
               ->join('diagnosticos','receta_diagnosticos.id_diagnostico','=','diagnosticos.id')
               ->select('diagnosticos.*')
               ->where('receta_diagnosticos.id_receta_vale','=',$id_receta_vale)
               ->where('receta_diagnosticos.estado','=',1)
               ->get();
		*/
        $cad = "select t1.id,t2.id as id_diagnostico, t1,id_prestacion, t2.codigo,t1.id_tipo_diagnostico ,t3.descripcion as tipo,(case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre
from receta_diagnosticos t1
inner join diagnosticos t2 on t1.id_diagnostico=t2.id
left join tipo_diagnosticos t3 on t1.id_tipo_diagnostico=t3.id
where t1.id_receta_vale=" . $id_receta_vale . "
and t1.estado=1
order by t1.id asc
";
        $data = DB::select($cad);
        return $data;
    }
    public function DiagnosticosByIdRecetaEgreso($id_receta_vale)
    {
        $cad = "select t1.id,t2.id as id_diagnostico, t1.id_prestacion, t2.codigo,t1.id_tipo_diagnostico ,t3.descripcion as tipo,(case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre
                from receta_diagnosticos t1
                inner join diagnosticos t2 on t1.id_diagnostico=t2.id
                inner join receta_vales rv on rv.id = t1.id_receta_vale 
                left join tipo_diagnosticos t3 on t1.id_tipo_diagnostico=t3.id
                where t1.id_receta_vale=" . $id_receta_vale . "
                and t1.estado=1
                and rv.flag_origen = 1
                order by t1.id asc
                ";
        $data = DB::select($cad);
        return $data;
    }
    public function DiagnosticosByIdHospitalizacion($id_hospitalizacion)
    {
        $data = DB::table("hospitalizaciones")
            ->join('diagnosticos', 'hospitalizaciones.id_diagnostico_inicial', '=', 'diagnosticos.id')
            ->select('diagnosticos.codigo as codigo_diagnostico', 'diagnosticos.nombre as descripcion_diagnostico')
            ->where('hospitalizaciones.id', '=', $id_hospitalizacion)
            ->where('hospitalizaciones.estado', '=', 1)
            ->get();
        return ($data);
    }

    // PONER TAMBIEN AND OSEA SERIA e.id', '=', $id_emergencia AND and rd.id_evaluacion !=0
    public function DiagnosticosByEmergencia_Enfermeria($id_emergencia)
    {
        $data = DB::table('emergencias as e')
            ->leftJoin('prestaciones as p', 'e.id', '=', 'p.id_ext')
            ->leftJoin('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
            ->where('e.id', '=', $id_emergencia)
            ->where('rd.id_evaluacion', '=', 0)
            ->get();

        return $data;
    }
    public function DiagnosticosByEmergencia_Hospitalizacion($id_hospitalizacion)
    {
        $data = DB::table('hospitalizaciones as h')
            ->leftJoin('prestaciones as p', 'h.id', '=', 'p.id_ext')
            ->leftJoin('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
            ->where('h.id', '=', $id_hospitalizacion)
            ->where('rd.id_evaluacion', '=', 0)
            ->get();
        return $data;
    }
    public function DiagnosticosByEmergencia_Enfermeria_evaluacion($id_emergencia)
    {
        $data = DB::table('emergencias as e')
            ->leftJoin('prestaciones as p', 'e.id', '=', 'p.id_ext')
            ->leftJoin('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
            ->where('e.id', '=', $id_emergencia)
            ->where('rd.id_evaluacion', '!=', 0)
            ->get();

        return $data;
    }
    public function DiagnosticosByHospitalizacion_Enfermeria_evaluacion($id_hospitalizacion)
    {
        $data = DB::table('hospitalizaciones as h')
            ->leftJoin('prestaciones as p', 'h.id', '=', 'p.id_ext')
            ->leftJoin('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
            ->where('h.id', '=', $id_hospitalizacion)
            ->where('rd.id_evaluacion', '!=', 0)
            ->get();

        return $data;
    }


    public function DiagnosticosByEmergencia_Enfermeria_formulario_prueba($id_emergencia)
    {
        $data = DB::table('emergencias as e')
            ->leftJoin('prestaciones as p', 'e.id', '=', 'p.id_ext')
            ->leftJoin('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos', 'rd.id_evaluacion')
            ->where('e.id', '=', $id_emergencia)
            ->get();

        return $data;
    }

    public function DiagnosticosByHospitalizacion_Enfermeria($id_hospitalizacion)
    {
        $data = DB::table('hospitalizaciones as h')
            ->leftJoin('prestaciones as p', 'h.id', '=', 'p.id_ext')
            ->leftJoin('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            ->where('p.id_tipo_atencion', '=', 3)
            ->where('h.id', '=', $id_hospitalizacion)
            ->where('rd.id_interconsulta', '=', 0)
            ->where('rd.id_consejeria', '=', 0)
            ->where('rd.id_evaluacion', '=', 0)
            ->groupBy('d.id', 'd.nombre', 'd.codigo')
            ->select(
                'd.id as id_diagnostico',
                'd.nombre as descripcion_diagnostico',
                'd.codigo as codigo_diagnostico',
                DB::raw('MIN(rd.id_tipo_diagnostico) as id_tipo_diagnostico'),
                DB::raw('MIN(td.descripcion) as descripcion_tipo_diagnosticos'),
                DB::raw('MIN(rd.id_evaluacion) as id_evaluacion')
            )
            ->get();
        return $data;
    }

    public function DiagnosticosByHospitalizacionEvaluacion_Enfermeria($id_hospitalizacion)
    {
        $data = DB::table('hospitalizaciones as h')
            ->leftJoin('prestaciones as p', 'h.id', '=', 'p.id_ext')
            ->leftJoin('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            ->where('p.id_tipo_atencion', '=', 3)
            ->where('h.id', '=', $id_hospitalizacion)
            ->where('rd.id_interconsulta', '=', 0)
            ->where('rd.id_consejeria', '=', 0)
            ->where('rd.id_evaluacion', '!=', 0)
            ->groupBy('d.id', 'd.nombre', 'd.codigo')
            ->select(
                'd.id as id_diagnostico',
                'd.nombre as descripcion_diagnostico',
                'd.codigo as codigo_diagnostico',
                DB::raw('MIN(rd.id_tipo_diagnostico) as id_tipo_diagnostico'),
                DB::raw('MIN(td.descripcion) as descripcion_tipo_diagnosticos'),
                DB::raw('MIN(rd.id_evaluacion) as id_evaluacion')
            )
            ->get();
        return $data;
    }

    public function diagnosticoEmergenciaPorId($id_emergencia)
    {
        $card = "select rd.id as id_receta_diagnosticos, rd.id_diagnostico, 
                d.codigo || ' - ' || d.nombre as nombre_diagnostico, rd.id_tipo_diagnostico, 
                td.descripcion as descripcion_tipo_diagnostico
                from receta_diagnosticos rd 
                inner join diagnosticos d on d.id = rd.id_diagnostico 
                inner join tipo_diagnosticos td on td.id = rd.id_tipo_diagnostico 
                where rd.id_emergencia = ".$id_emergencia." 
                and rd.estado = 1 
                and d.estado = 1
                and rd.id_interconsulta = 0";
        $data = DB::select($card);
        return $data;
    }

    public function receta()
    {
        return $this->belongsTo('App\Models\Receta_vale', 'id_receta_vale', 'id');
    }

    public function getDiagnosticosTrasladoByIdCambioCama($id_cambio_cama)
    {
        $result = DB::table('receta_diagnosticos as rd')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->join('tipo_diagnosticos as td', 'rd.id_tipo_diagnostico', '=', 'td.id')
            ->select(
                'rd.id',
                'd.id as id_diagnostico',
                'd.codigo',
                'd.nombre',
                'td.id as id_tipo_diagnostico',
                'td.descripcion as tipo_diagnostico_descripcion'
            )
            ->where('rd.id_cambio_cama', $id_cambio_cama)
            ->where('rd.id_consejeria', 0)
            ->where('rd.id_interconsulta', 0)
            ->where('rd.estado', 1)
            ->get();

        return $result;
    }


    public function getDiagnosticosByConsejeriaId($id_consejeria, $estado = 1)
    {
        $diagnosticos = DB::table('receta_diagnosticos as t1')
            ->select([
                't1.id',
                't1.id_diagnostico',
                DB::raw('COALESCE(t1.id_tipo_diagnostico, 0) as id_tipo_diagnostico'),
                't2.codigo',
                't1.id_categoria',
                DB::raw("(CASE WHEN t2.codigo = 'Z21.X' OR t2.codigo ILIKE 'B20%' THEN '' ELSE t2.nombre END) as nombre"),
                't1.id_clasificacion_diagnosticos',
                DB::raw('LEFT(td.descripcion, 1) AS primera_letra_descripcion')
            ])
            ->join('diagnosticos as t2', 't1.id_diagnostico', '=', 't2.id')
            ->join('tipo_diagnosticos as td', 't1.id_tipo_diagnostico', '=', 'td.id')
            ->where('t1.id_consejeria', $id_consejeria)
            ->where('t1.id_evaluacion', 0)
            ->where('t1.id_interconsulta', 0);
        // ->where('t1.estado', 1)
        // ->distinct()
        // ->orderBy('t1.id', 'asc')
        // ->get();
        if (!is_null($estado)) {
            $diagnosticos->where('t1.estado', $estado);
        }
        return $diagnosticos->distinct()->orderBy('t1.id', 'asc')->get();
        // return $diagnosticos;                           
    }

    public function getDiagnosticosByArrayConsejeriaId($array_id_consejeria)
    {
        $result = DB::table('receta_diagnosticos as t1')
            ->select([
                't1.id',
                't1.id_diagnostico',
                DB::raw('COALESCE(t1.id_tipo_diagnostico, 0) as id_tipo_diagnostico'),
                't2.codigo',
                't1.id_categoria',
                DB::raw("(CASE WHEN t2.codigo = 'Z21.X' OR t2.codigo ILIKE 'B20%' THEN '' ELSE t2.nombre END) as nombre"),
                't1.id_clasificacion_diagnosticos',
                DB::raw('LEFT(td.descripcion, 1) AS primera_letra_descripcion')
            ])
            ->join('diagnosticos as t2', 't1.id_diagnostico', '=', 't2.id')
            ->join('tipo_diagnosticos as td', 't1.id_tipo_diagnostico', '=', 'td.id')
            ->whereIn('t1.id_consejeria', $array_id_consejeria)
            ->where('t1.id_evaluacion', 0)
            ->where('t1.id_interconsulta', 0)
            ->where('t1.estado', 1)
            ->distinct()
            ->orderBy('t1.id', 'asc')
            ->get();

        return $result;
    }

    /**
     * Obtener diagnosticos de Prestacion ($id_prestacion y $id_evaluacion = 0)
     * Obtener diagnosticos de Evaluacion (Emergencia) o Evolucion (Hospitalizacion) ($id_prestacion y $id_evaluacion)
     * 
     * Reemplazar Prestaciones -> GetDiagnosticoByIdPrestacionEmergencia($id)
     * Remplazar Evaluacione -> GetDiagnosticoByIdEvaluacion($id)
     */
    public function GetDiagnosticosPrestacionOrEvaluacion($id_prestacion, $id_evaluacion) {
        $data = DB::table('receta_diagnosticos as rd')
            ->select(
                'rd.id',
                'rd.id_diagnostico',
                DB::raw('coalesce(rd.id_tipo_diagnostico, 0) as id_tipo_diagnostico'),
                'd.codigo',
                'rd.id_categoria',
                DB::raw("(case when d.codigo = 'Z21.X' or d.codigo ilike 'B20%' then '' else d.nombre end) as nombre"),
                'rd.id_clasificacion_diagnosticos',
                DB::raw('td.descripcion as tipo_diagnostico')
            )
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'rd.id_tipo_diagnostico', '=', 'td.id')
            ->where('rd.id_prestacion', $id_prestacion)
            ->where('rd.id_evaluacion', $id_evaluacion)
            ->where('rd.id_interconsulta', 0)
            ->where('rd.estado', 1)
            ->orderBy('rd.id', 'asc')
            ->get();

        return $data;
    }

    public function GetDiagnosticosByIdReceta2($id_receta_vale)
    {
        $data = DB::table('receta_vales as rv')
            ->join('receta_diagnosticos as rd', function($join) {
                $join->on('rv.id_prestacion', '=', 'rd.id_prestacion')
                    ->on('rv.id_evaluacion', '=', 'rd.id_evaluacion');
            })
            ->join('diagnosticos as t2', 'rd.id_diagnostico', '=', 't2.id')
            ->leftJoin('tipo_diagnosticos as t3', 'rd.id_tipo_diagnostico', '=', 't3.id')
            ->select(
                'rd.id',
                't2.id as t2_id',
                't2.codigo',
                'rd.id_tipo_diagnostico',
                't3.descripcion as tipo',
                DB::raw("(CASE 
                    WHEN t2.codigo = 'Z21.X' OR t2.codigo ILIKE 'B20%' 
                    THEN '' 
                    ELSE t2.nombre 
                END) as nombre")
            )
            ->where('rv.id', $id_receta_vale)
            /*->where('rd.id_interconsulta', 0)
            ->where(DB::raw('COALESCE(rd.id_intervencion, 0)'), 0)
            ->where('rd.id_consejeria', 0)*/
            ->where('rd.estado', 1)
            ->orderBy('t2.id', 'asc')
            ->distinct()
            ->get();

        return $data;
    }
  
    public function GetDiagnosticosByIdRecetaEgreso($id_receta_vale)
    {
        $data = DB::table('receta_vales as rv')
            ->join('receta_diagnosticos as rd', function($join) {
                $join->on('rv.id_prestacion', '=', 'rd.id_prestacion')
                    ->on('rv.id_evaluacion', '=', 'rd.id_evaluacion');
            })
            ->join('diagnosticos as t2', 'rd.id_diagnostico', '=', 't2.id')
            ->leftJoin('tipo_diagnosticos as t3', 'rd.id_tipo_diagnostico', '=', 't3.id')
            ->selectRaw("
                DISTINCT ON (rd.id_diagnostico)
                rd.id,
                t2.id AS t2_id,
                t2.codigo,
                rd.id_tipo_diagnostico,
                t3.descripcion AS tipo,
                CASE
                    WHEN t2.codigo = 'Z21.X' OR t2.codigo ILIKE 'B20%'
                    THEN ''
                    ELSE t2.nombre
                END AS nombre
            ")
            ->where('rv.id', $id_receta_vale)
            ->where('rd.id_interconsulta', 0)
            ->where(DB::raw('COALESCE(rd.id_intervencion, 0)'), 0)
            ->where('rd.id_consejeria', 0)
            ->where('rd.estado', 1)
            ->where('rd.id_clasificacion_diagnosticos', 3)
            ->orderBy('rd.id_diagnostico', 'asc')
            ->orderBy('rd.id', 'asc')
            ->get();
        return $data;
    }
}
