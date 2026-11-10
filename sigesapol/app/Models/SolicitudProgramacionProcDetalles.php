<?php

namespace App\Models;

use App\Models\Procedimiento;
use Illuminate\Database\Eloquent\Model;
use DB;

class SolicitudProgramacionProcDetalles extends Model
{
    protected $table = 'solicitud_procedimientos_detalle';

    protected $fillable = [
        'id_solicitud',
        'id_procedimiento',
        'requiere_cita',
        'requiere_informe',
        'id_diagnostico',
        'cantidad',
        'indicacion',
        'created_us',
        'updated_us',
    ];

    public $timestamps = true;

    public function solicitud()
    {
        return $this->belongsTo(SolicitudProgramacionProcedimientos::class, 'id_solicitud');
    }

    public function procedimiento()
    {
        return $this->belongsTo(Procedimiento::class, 'id_procedimiento');
    }

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class, 'id_diagnostico');
    }

    public function GetProcedimientosPorAtenderPP($id_solicitud, $id_procedimiento)
    {
        return DB::select("select d.id, pr.codigo, pr.descripcion, d.cantidad, d.indicacion, d.estado,
            dg.codigo || ' - ' || dg.nombre as diagnostico,
            d.id_diagnostico, dg.codigo as diagnostico_codigo, dg.nombre as diagnostico_nombre
          from solicitud_procedimientos_detalle d
          inner join procedimientos pr on pr.id = d.id_procedimiento
          left join diagnosticos dg on dg.id = d.id_diagnostico
          where d.id_solicitud = ? and d.id_procedimiento = ?
            and (d.estado = 2 or (d.estado = 1 and d.requiere_cita = 0))
          order by d.id", [$id_solicitud, $id_procedimiento]);
    }

    public function GetIdsPorAtenderValidosPP($id_solicitud, $ids, $id_establecimiento)
    {
        if (count($ids) == 0) return array();
        $marcas = implode(',', array_fill(0, count($ids), '?'));
        $filas = DB::select("select d.id from solicitud_procedimientos_detalle d
            inner join solicitud_programacion_procedimientos s on s.id = d.id_solicitud
            inner join prestaciones p on p.id = s.id_prestacion
          where d.id_solicitud = ? and p.id_establecimiento = ?
            and (d.estado = 2 or (d.estado = 1 and d.requiere_cita = 0))
            and d.id in (" . $marcas . ")", array_merge(array($id_solicitud, $id_establecimiento), $ids));
        return array_map(function ($f) { return intval($f->id); }, $filas);
    }

    public function GetIdsPorEstadoPP($id_solicitud, $id_procedimiento, $estados, $id_establecimiento)
    {
        return DB::table('solicitud_procedimientos_detalle as d')
            ->join('solicitud_programacion_procedimientos as s', 's.id', '=', 'd.id_solicitud')
            ->join('prestaciones as p', 'p.id', '=', 's.id_prestacion')
            ->where('d.id_solicitud', $id_solicitud)
            ->where('d.id_procedimiento', $id_procedimiento)
            ->whereIn('d.estado', (array) $estados)
            ->where('p.id_establecimiento', $id_establecimiento)
            ->lists('d.id');
    }

    public function GetHistorialAtencionesPP($nro_doc)
    {
        return DB::select("select s.id as id_solicitud, d.id_procedimiento, p.id_establecimiento,
            e.nombre as establecimiento, s.asegurado_dni as nro_doc, c.nombre as servicio_solicitante,
            to_char(d.fecha_atencion, 'DD/MM/YYYY HH12:MI AM') as fecha_atencion,
            to_char(d.fecha_atencion, 'YYYYMMDDHH24MISS') as fecha_orden,
            ma.paterno || ' ' || ma.materno || ', ' || ma.nombre as medico,
            pr.codigo, pr.descripcion,
            (select string_agg(i.id::text, ',' order by i.id) from solicitud_procedimientos_informes i
              where i.id_solicitud = s.id and i.id_procedimiento = d.id_procedimiento and i.id_estado_reg = 1) as ids_informes,
            case when exists (select 1 from solicitud_procedimientos_informes i
              where i.id_solicitud = s.id and i.id_procedimiento = d.id_procedimiento and i.id_estado_reg = 1) then 1 else 0 end as tiene_informe
          from solicitud_procedimientos_detalle d
          inner join solicitud_programacion_procedimientos s on s.id = d.id_solicitud
          inner join prestaciones p on p.id = s.id_prestacion
          inner join establecimientos e on e.id = p.id_establecimiento
          inner join procedimientos pr on pr.id = d.id_procedimiento
          left join sub_consultorios sc on sc.id = p.id_sub_consultorio
          left join consultorios c on c.id = sc.id_consultorio
          left join medicos ma on ma.id = d.id_medico_atencion
          where s.asegurado_dni = ? and d.estado = 3
          group by s.id, d.id_procedimiento, p.id_establecimiento, e.nombre, s.asegurado_dni, c.nombre, d.fecha_atencion,
            ma.paterno, ma.materno, ma.nombre, pr.codigo, pr.descripcion
          order by d.fecha_atencion desc", [trim($nro_doc)]);
    }

    public function GetUltimaAtencionPP($id_solicitud, $id_procedimiento)
    {
        return DB::select("select d.id, pr.codigo, pr.descripcion, d.cantidad, d.indicacion, d.estado,
            dg.codigo || ' - ' || dg.nombre as diagnostico, d.observacion_atencion, d.id_medico_atencion,
            to_char(d.fecha_atencion, 'DD/MM/YYYY - HH12:MI:SS AM') as fecha_atencion,
            d.id_diagnostico, dg.codigo as diagnostico_codigo, dg.nombre as diagnostico_nombre
          from solicitud_procedimientos_detalle d
          inner join procedimientos pr on pr.id = d.id_procedimiento
          left join diagnosticos dg on dg.id = d.id_diagnostico
          where d.id_solicitud = ? and d.id_procedimiento = ? and d.estado in (3, 6)
            and d.fecha_atencion = (select max(d2.fecha_atencion) from solicitud_procedimientos_detalle d2
                                    where d2.id_solicitud = d.id_solicitud and d2.id_procedimiento = d.id_procedimiento and d2.estado in (3, 6))
          order by d.id", [$id_solicitud, $id_procedimiento]);
    }

    // SOLICITUD PROGRAMACIÓN PROCEDIMIENTO ÓRDENES
    public static function ppDiagnosticosOrden($tipo, $id_orden)
    {
        if ($tipo == 'receta') {
            $origen = "select distinct pr.id_diagnosticos as id_diagnostico, rv.id_prestacion
                from producto_recetas pr inner join receta_vales rv on rv.id = pr.id_receta_vale
                where pr.id_receta_vale = ? and pr.estado <> 0";
        } else {
            $origen = "select distinct pp.id_diagnostico, pp.id_prestacion
                from prestacion_procedimientos pp
                where pp.id_lab_rad = ? and pp.tipo_procedimiento = " . ($tipo == 'laboratorio' ? 2 : 3) . " and pp.id_estado_reg = 1";
        }
        return DB::select("select coalesce(rd.id, 0) as id, dg.id as id_diagnostico, dg.id as t2_id, dg.codigo,
                (case when dg.codigo = 'Z21.X' or dg.codigo ilike 'B20%' then '' else dg.nombre end) as nombre,
                coalesce(rd.id_tipo_diagnostico, 0) as id_tipo_diagnostico, td.descripcion as tipo,
                left(td.descripcion, 1) as primera_letra_descripcion, rd.id_clasificacion_diagnosticos, rd.created_at
              from ($origen) o
              inner join diagnosticos dg on dg.id = o.id_diagnostico
              left join receta_diagnosticos rd on rd.id = (select min(x.id) from receta_diagnosticos x
                  where x.id_prestacion = o.id_prestacion and x.id_diagnostico = o.id_diagnostico and x.estado = 1)
              left join tipo_diagnosticos td on td.id = rd.id_tipo_diagnostico
              order by dg.id", [intval($id_orden)]);
    }
}
