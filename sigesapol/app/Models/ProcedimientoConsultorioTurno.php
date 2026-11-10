<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProcedimientoConsultorioTurno extends Model
{
  protected $table = 'procedimientos_consultorio_turnos';

  public static function DiasSemana()
  {
    return array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
  }

  public function ObtenerTurnosPorSubConsultorio($id_sub_consultorio)
  {
    $cad = "select t1.id, t1.id_turno, t2.descripcion turno, t2.abreviatura abreviatura_turno,
                   t1.hora_ini, t1.hora_fin, t1.dia, t1.id_estado_reg, t1.tiempo_atencion,
                   to_char(t1.fecha_inicio, 'DD-MM-YYYY') fecha_inicio, to_char(t1.fecha_fin, 'DD-MM-YYYY') fecha_fin
            from procedimientos_consultorio_turnos t1
            inner join turnos t2 on t1.id_turno = t2.id
            where t1.id_sub_consultorio = ? and t1.id_estado_reg <> 0
            order by t1.id_turno asc,
                     case t1.dia when 'Lunes' then 1 when 'Martes' then 2 when 'Miercoles' then 3
                                 when 'Jueves' then 4 when 'Viernes' then 5 when 'Sabado' then 6 when 'Domingo' then 7 end,
                     t1.hora_ini::time asc";
    return DB::select($cad, array($id_sub_consultorio));
  }

  public function ObtenerTurnosDelDia($id_sub_consultorio, $fecha)
  {
    $dias = array(1 => 'Lunes', 2 => 'Martes', 3 => 'Miercoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sabado', 7 => 'Domingo');
    $n = intval(date('N', strtotime($fecha)));
    if (!isset($dias[$n])) return array();

    $cad = "select t1.id id_sub_consultorio_turno, t1.id_sub_consultorio, sc.descripcion nombre,
                   coalesce(t1.tiempo_atencion, sc.tiempo_atencion) tiempo_atencion,
                   t1.hora_ini, t1.hora_fin, t1.id_turno, t2.abreviatura turno, t1.dia
            from procedimientos_consultorio_turnos t1
            inner join turnos t2 on t1.id_turno = t2.id
            inner join sub_consultorios sc on sc.id = t1.id_sub_consultorio
            where t1.id_sub_consultorio = ? and t1.dia = ? and t1.id_estado_reg = 1 and sc.id_estado_reg = 1
              and (t1.fecha_inicio is null or t1.fecha_inicio <= CAST(? AS date))
              and (t1.fecha_fin is null or t1.fecha_fin >= CAST(? AS date))
            order by t1.hora_ini::time asc";
    return DB::select($cad, array($id_sub_consultorio, $dias[$n], $fecha, $fecha));
  }

  public function ObtenerCuposHorario($id_pct, $fecha, $dura_cons, $hora_ini, $hora_fin)
  {
    $id_pct = intval($id_pct);
    $dura_cons = intval($dura_cons);
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) || $dura_cons <= 0) return array();

    $cad = "
      WITH vals AS (
        SELECT
          TO_TIMESTAMP(CAST(:fecha1 AS text) || ' ' || CAST(:hora_ini AS text), 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_start
          ,TO_TIMESTAMP(CAST(:fecha2 AS text) || ' ' || CAST(:hora_fin AS text), 'YYYY-MM-DD HH-MI AM')::TIMESTAMP AS frame_end
          , (CAST(:dura AS text) || ' min')::interval AS t_interval
      ),
      grid AS (
        SELECT start_time, lead(start_time, 1, frame_end) OVER (ORDER BY start_time) AS end_time
        FROM (
          SELECT generate_series(frame_start, frame_end, t_interval) AS start_time , frame_end
          FROM vals
        ) x
      )
      SELECT th.*, tm.id_medico AS medico, tm.nombre, tm.paterno, tm.materno
      FROM (
        SELECT
          pct.id_turno, sc.id_establecimiento, pct.id_sub_consultorio,
          to_char(start_time, 'HH:MI AM') fecha_hora, to_char(end_time, 'HH:MI AM') fecha_fin, start_time, end_time,
          to_char(start_time, 'YYYY-MM-DD HH24:MI') fecha_cita,
          pct.id_sub_consultorio || '#' || (select count(*) from citas cit where cit.id_sub_consultorio = pct.id_sub_consultorio
                                             and cit.fecha_hora >= start_time and cit.fecha_hora < end_time and cit.id_estado_reg = 1) id_medico
        FROM grid g
        INNER JOIN procedimientos_consultorio_turnos pct
                ON TO_TIMESTAMP(CAST(:fecha3 AS text) || ' ' || pct.hora_ini, 'YYYY-MM-DD HH-MI AM') <= g.start_time
               AND TO_TIMESTAMP(CAST(:fecha4 AS text) || ' ' || pct.hora_fin, 'YYYY-MM-DD HH-MI AM') >= (g.end_time - interval '1 minute')
        INNER JOIN sub_consultorios sc on sc.id = pct.id_sub_consultorio
        WHERE pct.id = :id_pct
          AND pct.id_estado_reg = 1 and sc.id_estado_reg = 1
          AND to_char(start_time, 'HH:MI AM') <> to_char(end_time, 'HH:MI AM')
        ORDER BY start_time
       ) AS th
       LEFT JOIN (
        SELECT mt.id_medico, mt.id_establecimiento, mt.id_turno, mt.id_consultorio, mt.id_sub_consultorio, m.nombre, m.paterno, m.materno
        FROM medico_turnos AS mt
        LEFT JOIN medicos AS m ON mt.id_medico = m.id AND m.estado = 1
        WHERE mt.estado = 1 AND mt.fecha_atencion = :fecha5
       ) AS tm ON th.id_turno = tm.id_turno AND th.id_establecimiento = tm.id_establecimiento AND th.id_sub_consultorio = tm.id_sub_consultorio
       ORDER BY th.id_sub_consultorio desc, th.start_time ASC
    ";
    return DB::select($cad, array(
      'fecha1' => $fecha, 'hora_ini' => $hora_ini, 'fecha2' => $fecha, 'hora_fin' => $hora_fin, 'dura' => $dura_cons,
      'fecha3' => $fecha, 'fecha4' => $fecha, 'id_pct' => $id_pct, 'fecha5' => $fecha
    ));
  }

  public function ObtenerSolapamientoDia($id_sub_consultorio, $dia, $hora_ini, $hora_fin, $excluir_id = 0, $fecha_inicio = null, $fecha_fin = null)
  {
    $filas = DB::select("select t1.id, t1.hora_ini, t1.hora_fin, t1.dia, t2.descripcion turno
            from procedimientos_consultorio_turnos t1
            inner join turnos t2 on t1.id_turno = t2.id
            where t1.id_sub_consultorio = ? and t1.id_estado_reg = 1 and t1.id <> ? and t1.dia = ?
              and (t1.fecha_inicio is null or CAST(? AS date) is null or t1.fecha_inicio <= CAST(? AS date))
              and (t1.fecha_fin is null or CAST(? AS date) is null or t1.fecha_fin >= CAST(? AS date))",
      array($id_sub_consultorio, $excluir_id, $dia, $fecha_fin, $fecha_fin, $fecha_inicio, $fecha_inicio));

    $ini = strtotime('2000-01-01 ' . $hora_ini);
    $fin = strtotime('2000-01-01 ' . $hora_fin);
    foreach ($filas as $f) {
      $ini_otro = strtotime('2000-01-01 ' . $f->hora_ini);
      $fin_otro = strtotime('2000-01-01 ' . $f->hora_fin);
      if ($ini_otro === false || $fin_otro === false || $fin_otro <= $ini_otro) continue;
      if ($ini < $fin_otro && $fin > $ini_otro) return $f;
    }
    return null;
  }

  public function asignarMedicoCitasPp($fecha, $id_sub_consultorio)
  {
    $id_sub_consultorio = intval($id_sub_consultorio);
    $time_fecha = strtotime($fecha);
    if ($id_sub_consultorio <= 0 || $time_fecha === false) return;
    $fecha = date('Y-m-d', $time_fecha);

    $es_procedimiento = DB::table('sub_consultorios')
      ->where('id', $id_sub_consultorio)
      ->where('flag_procedimiento', 1)
      ->exists();
    if (!$es_procedimiento) return;

    $turnos = $this->ObtenerTurnosDelDia($id_sub_consultorio, $fecha);
    foreach ($turnos as $turno) {
      $time_ini = strtotime($fecha . ' ' . $turno->hora_ini);
      $time_fin = strtotime($fecha . ' ' . $turno->hora_fin);
      if ($time_ini === false || $time_fin === false) continue;

      $medico = DB::table('medico_turnos')
        ->where('estado', 1)
        ->where('fecha_atencion', $fecha)
        ->where('id_sub_consultorio', $id_sub_consultorio)
        ->where('id_turno', $turno->id_turno)
        ->orderBy('updated_at', 'desc')
        ->select('id_medico')
        ->first();
      $id_medico = $medico ? $medico->id_medico : null;

      $ids_citas = DB::table('citas')
        ->where('flag_pp', 1)
        ->where('id_estado_reg', 1)
        ->where('id_sub_consultorio', $id_sub_consultorio)
        ->where('fecha_hora', '>=', date('Y-m-d H:i:s', $time_ini))
        ->where('fecha_hora', '<=', date('Y-m-d H:i:s', $time_fin))
        ->lists('id');
      if (count($ids_citas) == 0) continue;

      DB::table('citas')->whereIn('id', $ids_citas)->update(['id_medico' => $id_medico]);
      DB::table('citas_llamados')->whereIn('id_cita', $ids_citas)->update(['id_medico' => $id_medico]);
    }
  }
}
