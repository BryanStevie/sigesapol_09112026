<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class EstablecimientoReporte extends Model
{

  public function ShowAll()
  {
    $datos = DB::table('establecimientos')
      ->select('establecimientos.*')
      ->where('establecimientos.id_estado_reg', 1)
      ->where('establecimientos.nombre', '!=', 'ALMACEN LOGISTICO DIRSAPOL')
      ->where('establecimientos.nombre', '!=', 'SALUDPOL')
      ->orderBy('nombre', 'asc')
      ->get();
    return $datos;
  }

  public function ShowAllForMesa()
  {
    $cad = "select * from establecimientos 
        where establecimientos.id_estado_reg = 1 
        and establecimientos.nombre != 'ALMACEN LOGISTICO DIRSAPOL'
        and establecimientos.nombre != 'SANIDAD PNP'
        and establecimientos.nombre != 'SALUDPOL'
        and establecimientos.nombre != 'DIRSAPOL'
        order by establecimientos.nombre asc";
    $data = DB::select($cad);
    return $data;
  }

  public function generar_secuencia_numero_ticket()
  {
    $cad = "SELECT COUNT(numero) FROM tickets where letra='A' AND to_char(created_at::date,'YYYY-MM-DD') = to_char( now()::date,'YYYY-MM-DD')
              and id_totem ='6'";
    $data = DB::select($cad);
    if (empty($data)) {
      return null;
    }
    return $data[0];
  }

  public function buscar_id_consultorio_totem_opciones($id_totem_opcion)
  {
    $cad = "SELECT id_consultorio FROM totem_opciones WHERE id ='" . $id_totem_opcion . "'";
    $data = DB::select($cad);
    if (empty($data)) {
      return null;
    }
    return $data[0];
  }
  public function buscar_id_sub_consultorios_opciones($id_totem, $id_totem_opcion)
  {
    $cad = "SELECT id FROM sub_consultorios WHERE id_establecimiento = (SELECT id_establecimiento FROM totems WHERE id = '" . $id_totem . "') AND id_consultorio = '" . $id_totem_opcion . "' ORDER BY id LIMIT 1;";
    $data = DB::select($cad);
    if (empty($data)) {
      return null;
    }
    return $data[0];
  }

  public function ShowAllEstablecimientos($id_establecimiento = 0)
  {
    $cad = "select T1.* 
                from establecimientos T1
                where 1=1
                And Length(codigo)=8 And id_estado_reg>0 ";
    if ($id_establecimiento > 0) $cad .= " And T1.id!=" . $id_establecimiento;
    $data = DB::select($cad);
    return $data;
  }

  public function GetById($id)
  {
    $datos = DB::table('establecimientos')
      ->select('establecimientos.nombre', 'establecimientos.id', 'establecimientos.nivel', 'establecimientos.id_egestion', 'establecimientos.codigo', 'establecimientos.red_region', 'establecimientos.ubigeo')
      ->where('establecimientos.id', '=', $id)
      ->get();
    return $datos;
  }

  public function Get($id)
  {
    $query = 'select * from establecimientos where id = ' . intval($id);
    $result = DB::select($query);
    if (empty($result)) {
      return null;
    }
    return $result[0];
  }

  public function GetByIdFarmacia2($id)
  {
    $datos = DB::table('establecimientos')
      ->join('farmacias', 'establecimientos.id', '=', 'farmacias.id_establecimiento')
      ->select('establecimientos.id', 'establecimientos.codigo', 'establecimientos.nombre', 'establecimientos.nivel')
      ->where('farmacias.id', '=', $id)
      ->first();
    return $datos;
  }

  public function GetByIdFarmacia($id)
  {
    $datos = DB::connection('pgsql_reportes')->table('establecimientos')
      ->join('farmacias', 'establecimientos.id', '=', 'farmacias.id_establecimiento')
      ->select('establecimientos.id', 'establecimientos.codigo', 'establecimientos.nombre', 'establecimientos.nivel')
      ->where('farmacias.id', '=', $id)
      ->first();
    return $datos;
  }

  public function EstablecimientosSinSistema()
  {
    $cad = "SELECT *
                FROM establecimientos
                WHERE id NOT IN (SELECT id_establecimiento FROM farmacias)";
    $data = DB::select($cad);
    return $data;
  }

  public function ShowEstablecimientoByUbigeo($ubigeo)
  {

    $cad = "select * from establecimientos T1 where ubigeo=" . $ubigeo . " order by nombre asc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetEstablecimientoUbigeosByUbigeo($ubigeo)
  {

    $cad = "select T1.*,T2.nombre establecimiento 
		from establecimiento_ubigeos T1 
		inner join establecimientos T2 on T1.id_establecimiento = T2.id
		where T1.ubigeo='" . $ubigeo . "' order by T1.id asc";
    $data = DB::select($cad);
    return $data;
  }

  public function ShowEstablecimientoNivel($nivel)
  {

    $cad = "select * from establecimientos T1 where nivel<=" . $nivel . " order by nombre asc";
    $data = DB::select($cad);
    return $data;
  }

  public function establecimiento_yaregistrado($codigo)
  {
    $datos = DB::table('establecimientos')
      ->where('establecimientos.codigo', '=', $codigo)
      ->select('establecimientos.*')
      ->first();
    return $datos;
  }

  public function GetByIdEstablecimiento($id)
  {
    $datos = DB::table('establecimientos')
      ->select('establecimientos.nombre', 'establecimientos.id', 'establecimientos.nivel', 'establecimientos.id_egestion', 'establecimientos.codigo', 'establecimientos.direccion_establecimiento')
      ->where('establecimientos.id', '=', $id)
      ->first();
    return $datos;
  }

  public function GetNombre($id_establecimiento)
  {
    $query = 'select nombre from establecimientos where id = ' . intval($id_establecimiento);
    $result = DB::select($query);
    return empty($result) ? '' : $result[0]->nombre;
  }

  /**
   * Obtener Establecimiento de nivel 1 y 2
   */
  public function GetEstablecimientosNivel1Y2()
  {
    $data = DB::table('establecimientos')
      ->whereIn('establecimientos.nivel', [1, 2])
      ->where('establecimientos.id_estado_reg', '=', 1)
      ->orderBy('nombre')
      ->get();
    return $data;
  }

  public function solicitudes()
  {
    return $this->hasMany('App\Models\MesaRequerimiento');
  }

  public function ShowConsultoriosEstablecimientos($id_ipress)
  {

    $cad = "select T1.id id_establecimiento,T3.id id_consultorio,T3.nombre, T2.id_consultorio checkeado
                from establecimientos T1
                inner join consultorio_establecimientos T2 on T2.id_establecimiento=T1.id 
                inner JOIN consultorios T3 on T3.id=T2.id_consultorio
                Where T2.estado=1 and T1.id=" . $id_ipress . " and COALESCE(T3.estado,1)!=3 group by T1.id,T3.id, T2.id_consultorio Order By T3.nombre Asc";
    $data = DB::select($cad);
    return $data;
  }

  public function ShowAllConsultoriosEstablecimientos($p)
  {
    return $this->readFunctionPostgres('sp_listar_consultorio_establecimiento', $p);
  }

  public static function RegistrarConsultorioEstablecimiento($id_user, $id_establecimiento, $p_consultorios)
  {
    $cad = "Select sp_insert_consultorio_establecimiento(?,?,?)";
    $data = DB::select($cad, array($id_user, $id_establecimiento, $p_consultorios));
    return $data[0]->sp_insert_consultorio_establecimiento;
  }

  public function readFunctionPostgres($function, $parameters = null)
  {

    $_parameters = '';
    if (count($parameters) > 0) {
      $_parameters = implode("','", $parameters);
      $_parameters = "'" . $_parameters . "',";
    }
    $data = DB::select("BEGIN;");
    $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";
    $data = DB::select($cad);
    $cad = "FETCH ALL IN ref_cursor;";
    $data = DB::select($cad);
    return $data;
  }

  public function GetProvinciaForUbego($codigo)
  {
    $cad = "select p.descripcion from distritos d 
      left join provincias p on d.id_provincia = p.id_provincia 
      where d.codigo = '$codigo'";
    $data = DB::select($cad);
    return $data;
  }
}
