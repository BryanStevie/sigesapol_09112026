<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductoReporte extends Model
{
  protected $connection = 'pgsql_reportes';
  protected $table = 'productos';

  public function total_producto_establecimiento()
  {
    return $this->readFunctionProductos('sp_stock_general');
  }

  public static function all2($columns = ['*'])
  {
    $columns = is_array($columns) ? $columns : func_get_args();

    $instance = new static;

    return $instance->newQuery()->get($columns);
  }

  public static function all($columns = ['*'])
  {
    return static::on('pgsql_reportes')->get($columns);
  }


  public function readFunctionProductos($function)
  {

    $data = DB::connection('pgsql_reportes')->select("BEGIN;");
    $cad = "select " . $function . "('ref_cursor');";
    $data = DB::connection('pgsql_reportes')->select($cad);
    $cad = "FETCH ALL IN ref_cursor;";
    $data = DB::connection('pgsql_reportes')->select($cad);

    return $data;
  }


  public function ProductosStockCirgia($id_farmacia)
  {
    $productos =  DB::table('productos')
      ->join('stock_farmacias', 'productos.id', '=', 'stock_farmacias.id_producto')
      ->join('tipo_productos', 'productos.id_tipo', '=', 'tipo_productos.id')
      ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
      //->where('productos.estado','=',1)
      ->where('stock_farmacias.id_farmacia', '=', $id_farmacia)
      ->select('productos.*', 'unidades.abreviatura as unidad', 'unidades.abreviatura as abreviatura')
      ->orderBy('tipo_productos.id', 'asc')
      ->get();
    return $productos;
  }


  public function ProductosFarmaciasSegunNivel2($nivel)
  {

    switch ($nivel) {
      case 3:
        $productos =  DB::table('productos')
          ->join('tipo_productos', 'productos.id_tipo', '=', 'tipo_productos.id')
          ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
          ->where('productos.estado', '=', 1)
          ->where('productos.petitorio', '=', 'SI')
          ->select('productos.*', 'unidades.abreviatura as unidad', 'unidades.abreviatura as abreviatura')
          //->orderBy('tipo_productos.id','asc')
          ->orderBy('productos.nombre', 'asc')
          ->get();
        break;
      case 2:
        $productos =  DB::table('productos')
          ->join('tipo_productos', 'productos.id_tipo', '=', 'tipo_productos.id')
          ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
          ->where('productos.nivel', '<', 3)
          ->where('productos.estado', '=', 1)
          ->where('productos.petitorio', '=', 'SI')
          ->select('productos.*', 'unidades.abreviatura as unidad', 'unidades.abreviatura as abreviatura')
          //->orderBy('tipo_productos.id','asc')
          ->orderBy('productos.nombre', 'asc')
          ->get();
        break;
      case 1:
        $productos =  DB::table('productos')
          ->join('tipo_productos', 'productos.id_tipo', '=', 'tipo_productos.id')
          ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
          ->where('productos.nivel', '<', 2)
          ->where('productos.estado', '=', 1)
          ->where('productos.petitorio', '=', 'SI')
          ->select('productos.*', 'unidades.abreviatura as unidad', 'unidades.abreviatura as abreviatura')
          ->orderBy('productos.nombre', 'asc')
          ->get();
        break;
    }

    return $productos;
  }

  public function ProductosFarmaciasSegunNivel($nivel)
  {
    $query = DB::connection('pgsql_reportes')
      ->table('productos')
      ->join('tipo_productos', 'productos.id_tipo', '=', 'tipo_productos.id')
      ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
      ->where('productos.estado', '=', 1)
      ->where('productos.petitorio', '=', 'SI')
      ->select('productos.*', 'unidades.abreviatura as unidad', 'unidades.abreviatura as abreviatura')
      ->orderBy('productos.nombre', 'asc');

    switch ($nivel) {
      case 3:
        // No se filtra por nivel en el caso 3
        break;
      case 2:
        $query->where('productos.nivel', '<', 3);
        break;
      case 1:
        $query->where('productos.nivel', '<', 2);
        break;
    }

    return $query->get();
  }


  public function ProductosFarmaciasSegunNivelAndTipoProducto($nivel, $id_tipo)
  {
    switch ($nivel) {
      case 3:
        $cad = "select productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
from productos
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join unidades on productos.id_unidad=unidades.id
where productos.petitorio='SI' AND productos.estado='1' or (productos.id = 14596) or (productos.id = 12538) ";
        if ($id_tipo > 0) $cad .= " and id_egestion = " . $id_tipo;
        $cad .= " order By tipo_productos.id asc";
        break;
      case 2:
        $cad = "select productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
from productos
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join unidades on productos.id_unidad=unidades.id
where productos.nivel<3
and productos.petitorio='SI' AND productos.estado='1'";
        if ($id_tipo > 0) $cad .= " and id_egestion = " . $id_tipo;
        $cad .= " order By tipo_productos.id asc";
        break;
      case 1:
        $cad = "select productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura from productos
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join unidades on productos.id_unidad=unidades.id
where productos.nivel<2
and productos.petitorio='SI' AND productos.estado='1'";
        if ($id_tipo > 0) $cad .= " and id_egestion = " . $id_tipo;
        $cad .= " order By productos.nombre asc";
        break;
    }
    //echo $cad;
    $data = DB::select($cad);
    return $data;
  }

  public function ProductosEspecialidadSegunFarmacia($id_farmacia)
  {

    $cad = "select 
productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
from productos
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join unidades on productos.id_unidad=unidades.id
where productos.estado='1'
and productos.id in (select id_producto from producto_servicios 
                   where id_servicio in (select  id_servicio from farmacia_servicios where id_farmacia = " . $id_farmacia . "))
order By tipo_productos.id asc";

    $data = DB::select($cad);
    return $data;
  }

  public function getProductosByNivelAndIdServicio($nivel, $id_servicio, $id_farmacia)
  {
    $cad = "select 
  productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
  from productos
  inner join tipo_productos on productos.id_tipo=tipo_productos.id
  inner join unidades on productos.id_unidad=unidades.id
  where productos.estado='1' ";
    if ($nivel == 1) $cad .= " and productos.nivel in(1)";
    if ($nivel == 2) $cad .= " and productos.nivel in(1,2)";
    if ($nivel == 3) $cad .= " and productos.nivel in(1,2,3)";
    $cad .= " And productos.id in (select id_producto from producto_servicios where id_servicio = " . $id_servicio . " And id_farmacia = " . $id_farmacia . " And estado=1)
  order By tipo_productos.id asc,productos.nombre asc";

    $data = DB::select($cad);
    return $data;
  }

  public function getProductosByNivelAndIdServicioConRestringido($nivel, $id_servicio, $id_servicio2, $id_farmacia)
  {
    $cad = "select 
  productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
  from productos
  inner join tipo_productos on productos.id_tipo=tipo_productos.id
  inner join unidades on productos.id_unidad=unidades.id
  where (productos.estado='1' ";
    if ($nivel == 1) $cad .= " and productos.nivel in(1)";
    if ($nivel == 2) $cad .= " and productos.nivel in(1,2)";
    if ($nivel == 3) $cad .= " and productos.nivel in(1,2,3)";
    $cad .= " And productos.id in (select id_producto from producto_servicios where id_servicio = " . $id_servicio . " And id_farmacia = " . $id_farmacia . " And estado=1)) And productos.ru IS NULL 
      UNION
          select 
      productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
      from productos
      inner join tipo_productos on productos.id_tipo=tipo_productos.id
      inner join unidades on productos.id_unidad=unidades.id
      where (productos.estado='1' ";
    if ($nivel == 1) $cad .= " and productos.nivel in(1)";
    if ($nivel == 2) $cad .= " and productos.nivel in(1,2)";
    if ($nivel == 3) $cad .= " and productos.nivel in(1,2,3)";
    $cad .= " And productos.id in (select id_producto from producto_servicios where id_servicio = " . $id_servicio2 . " And id_farmacia = " . $id_farmacia . " And estado=1)) And productos.ru !=''
      order By productos.nombre asc";


    $data = DB::select($cad);
    return $data;
  }

  public function getProductosByNivelAndIdServicioSinRestringido($nivel, $id_servicio, $id_farmacia)
  {
    $cad = "select 
  productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
  from productos
  inner join tipo_productos on productos.id_tipo=tipo_productos.id
  inner join unidades on productos.id_unidad=unidades.id
  where (productos.estado='1' ";
    if ($nivel == 1) $cad .= " and productos.nivel in(1)";
    if ($nivel == 2) $cad .= " and productos.nivel in(1,2)";
    if ($nivel == 3) $cad .= " and productos.nivel in(1,2,3)";
    $cad .= " And productos.id in (select id_producto from producto_servicios where id_servicio = " . $id_servicio . " And id_farmacia = " . $id_farmacia . " And estado=1)) And productos.ru IS NULL 
  order By productos.nombre asc";

    $data = DB::select($cad);
    return $data;
  }

  public function getProductosByNivelAndIdServicioQuirurgico($nivel, $id_servicio)
  {

    $cad = "select 
productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
from productos
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join unidades on productos.id_unidad=unidades.id
where productos.estado='1' and productos.nivel in(1,2,3) And productos.id in (select id_producto from producto_servicios where (id_servicio = " . $id_servicio . " OR id_servicio = 72)  And estado=1)
order By tipo_productos.id asc,productos.nombre asc";

    $data = DB::select($cad);
    return $data;
  }

  public function getProductosByNivelAndIdServicio2($nivel, $id_servicio)
  {



    $cad = "select 
productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
from productos
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join unidades on productos.id_unidad=unidades.id
where productos.estado='1' ";
    if ($nivel == 1) $cad .= " and productos.nivel in(1)";
    if ($nivel == 2) $cad .= " and productos.nivel in(1,2)";
    if ($nivel == 3) $cad .= " and productos.nivel in(1,2,3)";
    $cad .= " And productos.id in (select id_producto from producto_servicios where id_servicio = " . $id_servicio . " And estado=1)
order By tipo_productos.id asc,productos.nombre asc";

    $data = DB::select($cad);
    return $data;
  }

  public function getProductosByNivelAndIdServicioHospitalizacion($id_servicio, $id_farmacia, $dni)
  {
    $cad = "select productos.id,productos.codigo,productos.nombre,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
                  from productos
                  inner join tipo_productos on productos.id_tipo=tipo_productos.id
                  inner join unidades on productos.id_unidad=unidades.id
                  where productos.nivel in(1,2,3) And productos.id in (select id_producto from producto_servicios where id_servicio = " . $id_servicio . " And id_farmacia = " . $id_farmacia . " And estado=1)                    
                  UNION
                  select productos.id,productos.codigo,productos.nombre,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
                  from  producto_recetas 
                  inner join productos on  producto_recetas.id_producto = productos.id 
                  inner join tipo_productos on productos.id_tipo=tipo_productos.id
                  inner join unidades on productos.id_unidad=unidades.id
                  where producto_recetas.id_receta_vale IN (select id from receta_vales where tipo_receta='HOSPITALIZACION' and dni_beneficiario='" . $dni . "' and interconsulta='SI')
                  order By 3
                  ";
    $data = DB::select($cad);
    return $data;
  }

  public function getProductoHospitalizacionmasRestringido($id_farmacia, $id_servicio, $id_servicio2)
  {
    $cad = "select productos.id,productos.codigo,productos.nombre,productos.ru,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
                  from  producto_servicios 
                  inner join productos on  producto_servicios.id_producto = productos.id                     
                  inner join unidades on productos.id_unidad=unidades.id
                  where producto_servicios.id_servicio = " . $id_servicio . " And producto_servicios.id_farmacia = " . $id_farmacia . " And producto_servicios.estado=1
                  union
                  select productos.id,productos.codigo,productos.nombre,productos.ru,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
                  from  producto_servicios 
                  inner join productos on  producto_servicios.id_producto = productos.id                     
                  inner join unidades on productos.id_unidad=unidades.id
                  where producto_servicios.id_servicio = " . $id_servicio2 . " And producto_servicios.id_farmacia = " . $id_farmacia . " And producto_servicios.estado=1

                  order By 3
                  ";

    $data = DB::select($cad);
    return $data;
  }

  public function ShowProductosByIdegestion($id_egestion)
  {
    $data = DB::table('productos')
      ->join('tipo_productos', 'productos.id_tipo', '=', 'tipo_productos.id')
      ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
      ->where('tipo_productos.id_egestion', '=', $id_egestion)
      ->select('productos.*', 'unidades.abreviatura as unidad')
      ->get();
    return $data;
  }

  public function ShowProductosByIdegestionAndNivel($id_egestion, $nivel)
  {
    $data = DB::table('productos')
      ->join('tipo_productos', 'productos.id_tipo', '=', 'tipo_productos.id')
      ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
      ->where('tipo_productos.id_egestion', '=', $id_egestion)
      ->where('productos.nivel', '=', $nivel)
      ->select('productos.*', 'unidades.abreviatura as unidad')
      ->get();
    return $data;
  }

  public function ShowAll()
  {
    $datos = DB::table('productos')
      //->join('tipo_productos','productos.id_tipo','=','tipo_productos.id')
      ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
      //->select('productos.*','tipo_productos.nombre as tipo','unidades.abreviatura as abrev')
      //->select('productos.*','tipo_productos.nombre as tipo')
      ->select('productos.*', 'unidades.abreviatura')
      ->orderby('productos.id', 'asc')
      ->get();
    //var_dump($datos); exit;
    return $datos;
  }

  public function ShowAllMantenimiento($tipo_producto = 0)
  {

    $cad = "select 
productos.*,tipo_productos.nombre tipo_producto,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
from productos
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join unidades on productos.id_unidad=unidades.id
where productos.estado='1'";
    if ($tipo_producto > 0) $cad .= " and productos.id_tipo=" . $tipo_producto;
    $cad .= "order By tipo_productos.id asc,productos.nombre asc";

    $data = DB::select($cad);
    return $data;
  }

  public function getProductoByNombre($buscar)
  {

    $cad = "Select * From productos
Where codigo||nombre ilike '%" . $buscar . "%' And estado='1'
order By nombre asc
limit 10 ";

    $data = DB::select($cad);
    return $data;
  }


  public function GetProductoById_producto2($id)
  {
    $datos = DB::table('productos')
      ->select('productos.*')
      ->where('productos.id', $id)
      ->first();
    return $datos;
  }

  public function GetProductoById_producto($id)
  {
    $datos = DB::connection('pgsql_reportes') // Especificamos la conexión
      ->table('productos')
      ->select('productos.*')
      ->where('productos.id', $id)
      ->first();

    return $datos;
  }


  public function GetIdProdByCodMed($codigo)
  {
    $datos = DB::table('productos')
      ->select('productos.id', 'productos.nombre', 'productos.codigo')
      ->where('productos.codigo', $codigo)
      ->first();
    return $datos;
  }

  public function GetIdUnidadByAbreviatura($codigo)
  {
    $datos = DB::table('unidades')
      ->select('unidades.id', 'unidades.abreviatura', 'unidades.nombre')
      ->where('unidades.abreviatura', $codigo)
      ->first();
    return $datos;
  }

  public function GetIdTipoProductoByIdGestion($id_egestion)
  {
    $datos = DB::table('tipo_productos')
      ->select('tipo_productos.id', 'tipo_productos.nombre')
      ->where('tipo_productos.id_egestion', $id_egestion)
      ->first();
    return $datos;
  }


  public function GetDatosProductoByCodigo($codigo)
  {
    $datos = DB::table('productos')
      ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
      ->where('codigo', '=', $codigo)
      ->select('productos.*', 'unidades.abreviatura')
      ->first();
    return $datos;
  }

  public function GetNombreProdyCod($codigo)
  {
    $data = Producto::where('codigo', '=', $codigo)->first();
    return $data;
  }

  public function ShowByTipo($id_tipo)
  {
    $datos = DB::table('productos')
      ->join('tipo_productos', 'productos.id_tipo', '=', 'tipo_productos.id')
      ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
      ->select('productos.*', 'tipo_productos.nombre as tipo', 'unidades.abreviatura')
      ->where('productos.id_tipo', '=', $id_tipo)
      ->orderby('productos.id', 'asc')
      ->get();
    return $datos;
  }

  public function ExisteProducto($codigo, $id)
  {
    $data = Producto::where('codigo', '=', $codigo)->first();
    return isset($data) ? true : false;
  }

  public function GetIdByCodigoProducto($codigo)
  {
    $data = Producto::where('codigo', '=', $codigo)->first();
    return $data;
  }

  public function GetCodByIdProducto($id_producto)
  {
    $data = Producto::where('id', '=', $id_producto)->first();
    return $data;
  }

  public function buscar_producto_establecimiento($p)
  {
    return $this->readFunctionPostgres('sp_stock_producto_establecimiento', $p);
  }

  public function readFunctionPostgres2($function, $parameters = null)
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

    $data2 = DB::select("CLOSE ref_cursor;");
    $data2 = DB::select("END;");

    return $data;
  }

  public function readFunctionPostgres3($function, $parameters = [], $connection = 'pgsql_reportes')
  {
    $_parameters = '';

    if (!empty($parameters)) {
      $_parameters = "'" . implode("','", $parameters) . "',";
    }

    $query = "
        BEGIN;
        SELECT {$function}({$_parameters}'ref_cursor');
        FETCH ALL IN ref_cursor;
        CLOSE ref_cursor;
        END;
    ";

    return DB::connection($connection)->select($query);
  }

  public function readFunctionPostgres($function, $parameters = null)
  {
    $_parameters = '';
    if (count($parameters) > 0) {
      $_parameters = implode("','", $parameters);
      $_parameters = "'" . $_parameters . "',";
    }
    $data = DB::connection('psql_reportes')->select("BEGIN;");
    $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";
    $data = DB::connection('psql_reportes')->select($cad);
    $cad = "FETCH ALL IN ref_cursor;";
    $data = DB::connection('psql_reportes')->select($cad);
    return $data;
  }



  public function buscar_producto_farmacia($pr)
  {
    return $this->readFunctionPostgres('sp_stock_producto', $pr);
  }

  public function buscar_stock_producto_farmacia($pr)
  {
    return $this->readFunctionPostgres('sp_stock_producto_farmacia', $pr);
  }
}
