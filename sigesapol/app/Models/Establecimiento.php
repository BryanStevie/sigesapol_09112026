<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Establecimiento extends Model
{
  protected $table = 'establecimientos';
  public $timestamps = false;
  protected $primaryKey = 'id'; 
  protected $fillable = [
    'codigo', 
    'nombre',
    'ubigeo',
    'red_region',
    'id_egestion',
    'nivel',
    'id_estado_reg',
    'created_at',
    'created_us',
    'updated_at',
    'updated_us',
    'deleted_at',
    'deleted_us',
    'direccion_establecimiento',
    'intervalo_cita',
    'id_lab_core',
    'flag_colas',
    'is_ipress',
    'observacion',
    'nivel_real'
  ];

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

  public function ShowAllEstablecimientosPersonalSalud () {
    $cad = "select id, codigo, nombre from establecimientos order by nombre";
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
      ->select('establecimientos.nombre', 'establecimientos.id', 'establecimientos.nivel', 'establecimientos.id_egestion', 'establecimientos.codigo', 'establecimientos.red_region', 'establecimientos.ubigeo', 'flag_firma_electronica')
      ->where('establecimientos.id', '=', $id)
      ->get();
    return $datos;
  }

    public function GetByNivelEstablecimiento()
    {
        $datos = DB::table('establecimientos as e')
            ->select('*')
            ->whereIn('e.nivel', [2, 3])
            ->where('e.id_estado_reg', 1)
            ->whereRaw("
                e.nombre NOT ILIKE '%almacen%' 
                AND e.nombre <> 'DIRSAPOL'
                AND e.nombre NOT ILIKE '%odontol%'
            ")
            ->get();

        return $datos;
    }

  public function Get($id){
    $query = 'select * from establecimientos where id = ' . intval($id);
    $result = DB::select($query);
    if (empty($result)) {
      return null;
    }
    return $result[0];
  }

  public function GetByIdFarmacia($id)
  {
    $datos = DB::table('establecimientos')
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

  public function GetEstablecimientoPorServicio($id_servicio)
  {
    $data = DB::table('consultorio_establecimientos as ce')
    ->join('establecimientos as e', 'ce.id_establecimiento', '=', 'e.id')
    ->where('ce.id_consultorio', $id_servicio)
    ->select('ce.id', 'ce.id_establecimiento', 'e.nombre', 'ce.estado', 'ce.has_guardia')
    ->orderBy('e.nombre', 'asc')
    ->get();

    return $data;
  }

  public function GetServicioEstablecimientoPorId($id_servicio_establecimiento)
  {
    $data = DB::table('consultorio_establecimientos as ce')
      ->where('ce.id', $id_servicio_establecimiento)
      ->first();

    return $data;
  }

  // Procedimientos programados: solo para IPRESS de nivel 3 o el establecimiento 76
  public static function ppHabilitado($id_establecimiento)
  {
    static $cache = array();
    $id_establecimiento = intval($id_establecimiento);
    if ($id_establecimiento == 76) return true;
    if (!isset($cache[$id_establecimiento])) {
      $nivel = DB::table('establecimientos')->where('id', $id_establecimiento)->value('nivel');
      $cache[$id_establecimiento] = intval($nivel) == 3;
    }
    return $cache[$id_establecimiento];
  }

}
