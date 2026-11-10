<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class CqSalasTurnos extends Model
{
    protected $table = 'cq_salas_turnos';

    public function getFechaServidorUltimoDiaMes($fechaBuscar = "now()")
    {
      $cad = "select extract (day from (select date_trunc('month', '" . $fechaBuscar . "'::date) + interval '1 month') - interval '1 day') dia
      ,LPAD((extract(month from '" . $fechaBuscar . "'::date)::text),2,'0')mes
      ,extract(year from '" . $fechaBuscar . "'::date)ano";
      
      $data = DB::select($cad);

      return $data[0];
    }

    function getFechaServidor() {

      $cad = "SELECT now()::date fechaservidor";
      $data = DB::select($cad);
      return $data[0]->fechaservidor;
    }
    public function getServicioSalaAndMes($id_estab,$id_consu,$id_sala,$mes,$ano)
    {
      $query = '
        SELECT 
          id, turno, 
          created_at, updated_at, fecha_atencion, estado,id_establecimiento, 
          id_user_created, id_user_update, id_tipo_atencion, id_turno 
        FROM cq_salas_turnos
        WHERE 
          id_establecimiento = ' . $id_estab . ' 
          AND id_consultorio = \'' . $id_consu . '\' 
          AND id_sala = ' . $id_sala . ' 
          AND extract(month from fecha_atencion) = \'' . $mes . '\' 
          AND extract(year from fecha_atencion) = \'' . $ano . '\' 
        ORDER BY 
          fecha_atencion, 
          CASE 
            WHEN turno=\'MD\' THEN 1 
            When turno=\'M\' THEN 2 
            When turno=\'T\' THEN 3 
            Else 4 
          END;';
      $data = DB::select($query);
      return $data;
    }
    public function registrarCqTurnoXFecha($fecha, $id_establecimiento, $tipo_atencion, $id_sala, $id_consultorio, $id_user_created, $estado_turno_madrugada, $estado_turno_manana, $estado_turno_tarde, $estado_turno_noche)
    {
      $cad = "Select sp_cq_registrar_turno_x_fecha(?,?,?,?,?,?,?,?,?,?)";
      $data = DB::select($cad, array($fecha, $id_establecimiento, $tipo_atencion, $id_sala, $id_consultorio, $id_user_created, $estado_turno_madrugada, $estado_turno_manana, $estado_turno_tarde, $estado_turno_noche));
      return $data[0]->sp_cq_registrar_turno_x_fecha;
    }
    public function getTurnosSala($id_establecimiento,$id_consultorio,$id_sala, $year, $month) {
      
      $sql_establecimiento="";
      if($id_establecimiento==76){
        $sql_establecimiento="AND st.id_establecimiento='".(int)$id_establecimiento."'";
      }else{
        $sql_establecimiento="AND st.id_establecimiento <> '".(int)$id_establecimiento."'";
      }
      $query = "
              SELECT st.turno, EXTRACT(DAY FROM st.fecha_atencion) AS dia_atencion,c.nombre as servicio
              FROM cq_salas_turnos AS st
              inner join consultorios c on st.id_consultorio=c.id
              WHERE 
              EXTRACT ( YEAR FROM st.fecha_atencion ) = " . (int)$year . " 
              AND EXTRACT ( MONTH FROM st.fecha_atencion ) = " . (int)$month . "  
               ".$sql_establecimiento."
              AND st.id_consultorio <> " . (int)$id_consultorio . " 
              AND st.id_sala = " . (int)$id_sala . " 	
              AND st.estado = 1";
      return DB::select($query);
    }

}
