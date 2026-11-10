<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SubConsultorioTurno extends Model
{

  public function ObtenerSubConsultorioTurnoPorId($id)
  {
    $cad = "select t1.id,
                   t1.id_sub_consultorio,
                   t2.abreviatura abreviatura_turno,
                   t2.descripcion turno,
                   t1.hora_ini,
                   t1.hora_fin,
                   sc.tiempo_atencion,
                   t1.tiempo_atencion as tiempo_atencion_sct,
                   t1.id_estado_reg ,
                   t1.dia,
                   c.id as idservicio,
                   t1.fecha_inicio_turno,
                   t1.fecha_fin_turno
          from sub_consultorio_turnos t1
          inner join turnos t2 on t1.id_turno=t2.id
          inner join sub_consultorios sc on sc.id = t1.id_sub_consultorio
          inner join consultorios c on sc.id_consultorio = c.id 
          where t1.id_sub_consultorio=" . $id . "
          Order By id_turno ASC";
    $data = DB::select($cad);
    return $data;
  }

  public function ExisteConsultorioTurno($id_sub_consultorio, $id_turno = 0, $hora_ini = "")
  {
    $data = SubConsultorioTurno::where('id_sub_consultorio', '=', $id_sub_consultorio)->where('id_turno', '=', $id_turno)->where('hora_ini', '=', $hora_ini)->where('id_estado_reg', '=', 1)->first();
    return isset($data) ? true : false;
  }

  public static function DesactivarTurnos($id_sub_consultorio, $id_turno) 
  {
    $query = '
      UPDATE sub_consultorio_turnos 
      SET id_estado_reg = 0 
      WHERE id_sub_consultorio = ' . (int)$id_sub_consultorio . ' AND id_turno = ' . (int)$id_turno . ' 
    ';
    return DB::select($query);
  }

}
