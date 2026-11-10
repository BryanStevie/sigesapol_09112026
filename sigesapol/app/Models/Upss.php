<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Upss extends Model {

    public function ShowConsultoriosUpss($id_servicio, $id_tipo_atencion=""){
        
        //$data = DB::select("Select * From sp_listar_upss_consultorio2($id_servicio);");
        
        $cad = "select u.id, ta.nombre, u.codigo, u.descripcion_upss , c.nombre,  u.estado, ta.id id_atencion
            from upsses as u
            inner join consultorios as c ON  c.id = u.id_consultorio 
            inner join tipo_atenciones as ta ON  ta.id = u.id_tipo_atencion 
            where u.id_consultorio = ".$id_servicio;
        if ($id_tipo_atencion != "")
            $cad .= " and u.estado=1 and u.id_tipo_atencion = " . $id_tipo_atencion . "";
        $cad .= " order By u.codigo asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultoriosUpssHosConsExt($id_servicio)
    {
        $cad = "SELECT u.id, ta.nombre, u.codigo, u.descripcion_upss, c.nombre AS consultorio_nombre, u.estado, ta.id AS id_atencion
                FROM upsses AS u
                INNER JOIN consultorios AS c ON c.id = u.id_consultorio
                INNER JOIN tipo_atenciones AS ta ON ta.id = u.id_tipo_atencion
                WHERE c.id = " . intval($id_servicio) . "
                  AND u.estado = 1
                  AND ta.id IN (1, 3)
                ORDER BY u.codigo ASC";
        return DB::select($cad);

    }

    public function ShowConsultoriosUpssConsExt($id_servicio)
    {
        $cad = "SELECT u.id, ta.nombre, u.codigo, u.descripcion_upss, c.nombre AS consultorio_nombre, u.estado, ta.id AS id_atencion
                FROM upsses AS u
                INNER JOIN consultorios AS c ON c.id = u.id_consultorio
                INNER JOIN tipo_atenciones AS ta ON ta.id = u.id_tipo_atencion
                WHERE u.id_consultorio = " . intval($id_servicio) . "
                  AND u.estado = 1
                  AND ta.id IN (1, 2)
                ORDER BY u.codigo ASC";
        return DB::select($cad);
    }

    public function ShowConsultoriosUpss2($id, $id_tipo_atencion = "")
    {
        $cad = "SELECT u.id, ta.nombre, u.codigo, u.descripcion_upss, u.estado, ta.id as id_atencion 
                FROM upsses AS u
                INNER JOIN triajes t on t.id_ups = u.id 
                INNER JOIN emergencias e on e.id_triaje = t.id 
                INNER JOIN consultorios c on t.id_servicio = c.id  
                INNER JOIN tipo_atenciones AS ta ON ta.id = u.id_tipo_atencion 
                WHERE u.estado = '1' AND e.id = ".$id." AND ta.id = ".$id_tipo_atencion." 
                ORDER BY u.codigo ASC";
        $data = DB::select($cad);
        return $data;
    }
    public function showconsultoriohospit($id, $id_servicio) {
      $cad = "
          SELECT 
              up.codigo,
              up.descripcion_upss,
              c2.nombre AS upss_cargo_especialidad, 
              c_cargo.nombre AS servicio
          FROM consejerias c 
          INNER JOIN consultorios c2 ON c.id_servicio_especialista = c2.id
          INNER JOIN consultorios c_cargo ON c.id_servicio = c_cargo.id
          INNER JOIN upsses up ON CAST(c.upss_especialista AS INTEGER) = up.id 
          WHERE up.id_consultorio = ? AND c.id = ?
      ";
  
      $data = \DB::select($cad, [$id_servicio, $id]);
      return $data;
  }
    public function ExisteCodigoUpss($codigo, $excluir_id = null) {
        $data = Upss::where('codigo', '=', $codigo)->where('estado', '=', 1);
        if (!empty($excluir_id)) {
          $data = $data->where('id', '<>', (int)$excluir_id);
        }
        $data = $data->first();
        return isset($data) ? true : false;
    }

    public function ExisteAtencionUpss($id_servicio, $id_atencion, $excluir_id = null) {
        $data = Upss::where('id_consultorio', '=', $id_servicio)
          ->where('id_tipo_atencion', '=', $id_atencion)
          ->where('estado', '=', 1);
        if (!empty($excluir_id)) {
          $data = $data->where('id', '<>', (int)$excluir_id);
        }
        $data = $data->first();
        return isset($data) ? true : false;
    }

    public function ShowConsultoriosTipoAtencion($p) {
    	return readFunctionPostgres('sp_listar_upss_por_tipoatencion', $p);
    }

    public function upssPorServicioPorIdAtencion($id_servicio, $id_atencion) {
      $data = Upss::where('id_consultorio', '=', $id_servicio)
        ->where('id_tipo_atencion', '=', $id_atencion)
        ->where('estado', '=', 1)->first();
      return json_decode($data);
    }

    public function upss_by_id($id_ups, $id_atencion) {
      $data = Upss::where('id', '=', $id_ups) 
        ->where('id_tipo_atencion', '=', $id_atencion)
        ->where('estado', '=', 1)->first();
      return json_decode($data);
    }

    public function ShowUPSS_by_Hospitalizacion() {
      $upss = "select distinct c.id as id_upss, c.nombre, c.id as id_consultorio from upsses u 
              inner join consultorios c on u.id_consultorio = c.id 
              where u.id_tipo_atencion = 3 and u.estado = 1
              order by c.nombre";
      $upss = DB::select($upss);
      return $upss;
    }

    public function ShowUPSS_Procedimiento_Hospitalizacion() {
      $upss = "SELECT DISTINCT ON (TRIM(LOWER(c.nombre))) 
                    c.id AS id_upss,
                    c.nombre,
                    c.id AS id_consultorio
              FROM upsses u
              INNER JOIN consultorios c ON u.id_consultorio = c.id
              WHERE c.tipo=1 and u.estado = 1
              ORDER BY TRIM(LOWER(c.nombre)), c.id;
      ";
      return DB::select($upss);
    }

     public function ShowUPSS_Procedimiento_Emergencia() {
        $upss = "SELECT DISTINCT ON (TRIM(LOWER(c.nombre))) 
                      c.id AS id_upss,
                      c.nombre,
                      c.id AS id_consultorio
                FROM upsses u
                INNER JOIN consultorios c ON u.id_consultorio = c.id
                WHERE c.tipo=1 and u.estado = 1
                ORDER BY TRIM(LOWER(c.nombre)), c.id;
        ";
        return DB::select($upss);
      }

      public function ShowUPSS_mergencia()
      {
          $cad = "SELECT u.codigo, t.id, t.descripcion 
                  FROM triajes AS t
                  INNER JOIN upsses u on t.id_ups = u.id 
                  WHERE u.estado = '1' AND t.estado = 'N' --AND u.codigo !='230500' AND u.codigo !='230100'
                  ORDER BY t.descripcion ASC";
          $data = DB::select($cad);
          return $data;
      }

      public function ShowConsultoriosUpssHospitalizacion($id_servicio)
    {
        $cad = "SELECT u.id, u.codigo, u.descripcion_upss
                FROM upsses AS u
                INNER JOIN consultorios AS c ON c.id = u.id_consultorio
                INNER JOIN tipo_atenciones AS ta ON ta.id = u.id_tipo_atencion
                WHERE c.id = " . intval($id_servicio) . "
                  AND u.estado = 1
                  AND ta.id IN (3)
                ORDER BY u.codigo ASC";
        $data = DB::select($cad);
        return $data;

    }

   }