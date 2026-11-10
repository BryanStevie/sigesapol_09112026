<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class KardexeReporte extends Model
{

    protected $connection = 'pgsql_reportes';
    protected $table = 'kardexes';

    public function ShowMedicamentos($nombre_farmacia)
    {
        $data = DB::table('stock_farmacias')
            ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->where('stock_farmacias.nombre_farmacia', $nombre_farmacia)
            ->select('stock_farmacias.codigo_producto', 'productos.nombre as nombre_producto', 'stock_farmacias.cantidad', 'unidades.abreviatura as unidad')
            ->orderBy('stock_farmacias.nombre_producto', 'asc')
            ->get();
        return $data;
    }

    public function ShowMedicamentosByTipo($nombre_farmacia, $tipo)
    {
        $data = DB::table('stock_farmacias')
            ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->where('stock_farmacias.nombre_farmacia', $nombre_farmacia)
            ->select('stock_farmacias.codigo_producto', 'productos.nombre as nombre_producto', 'stock_farmacias.cantidad', 'unidades.abreviatura as unidad')
            ->where('productos.id_tipo', '=', $tipo)
            ->orderBy('stock_farmacias.nombre_producto', 'asc')
            ->get();

        return $data;
    }

    public function ObtenerRecetaByIdkardex($id) {}

    public function GetRecetaByNroRecetaAndCodProductoAndFarma($nro_receta, $id_producto, $nombre_farmacia)
    {
        $data = DB::table('kardexes')
            ->where('kardexes.id_producto', $id_producto)
            ->where('kardexes.nombre_farmacia', $nombre_farmacia)
            ->where('kardexes.numero_movimiento', $nro_receta)
            ->where('kardexes.estado', 1)
            ->select('kardexes.*')
            ->first();
        return $data;
    }

    public function ShowTipoMov()
    {
        $data = DB::table('tipo_movimientos')
            ->select('id', 'codgo', 'nombre', 'orden')
            ->orderBy('orden', 'asc')
            ->get();
        return $data;
    }

    public static function ShowNroMesByProd($id_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin)
    {
        $data = DB::table('kardexes')
            ->join('tipo_movimientos', 'kardexes.id_tipo_movimiento', '=', 'tipo_movimientos.id')
            ->select(DB::raw('count(distinct extract (month from fecha_movimiento)) as nro_mes'))
            ->where('id_producto', '=', $id_producto)
            ->where('nombre_farmacia', '=', $nombre_farmacia)
            ->where('id_tipo_movimiento', '>', 3)
            ->where('fecha_movimiento', '>=', $fecha_inicio)
            ->where('fecha_movimiento', '<=', $fecha_fin)
            ->where('estado', '=', 1)
            ->first();
        return $data;
    }

    public function ShowTotalesByMov($id_producto, $nombre_farmacia, $id_tipo_movimiento, $fecha_inicio, $fecha_fin)
    {
        $data = DB::table('kardexes')
            ->select(DB::raw('sum(cantidad) as suma'))
            ->where('id_producto', '=', $id_producto)
            ->where('nombre_farmacia', '=', $nombre_farmacia)
            ->where('id_tipo_movimiento', '=', $id_tipo_movimiento)
            ->where('fecha_movimiento', '>=', $fecha_inicio)
            ->where('fecha_movimiento', '<=', $fecha_fin)
            ->where('estado', '=', 1)
            ->first();
        return $data;
    }

    public function ShowTotalesByMov_2($id_producto, $nombre_farmacia, $id_tipo_movimiento, $fecha_fin)
    {
        $data = DB::table('kardexes')
            ->select(DB::raw('sum(cantidad) as suma'))
            ->where('id_producto', '=', $id_producto)
            ->where('nombre_farmacia', '=', $nombre_farmacia)
            ->where('id_tipo_movimiento', '=', $id_tipo_movimiento)
            ->where('fecha_movimiento', '<=', $fecha_fin)
            ->where('estado', '=', 1)
            ->first();
        return $data;
    }

    public function ShowSaldoInicialAnteriorByProd($id_producto, $nombre_farmacia, $fecha_inicio)
    {
        $data = DB::table('kardexes')
            ->join('tipo_movimientos', 'kardexes.id_tipo_movimiento', '=', 'tipo_movimientos.id')
            ->select(DB::raw('coalesce(sum(kardexes.cantidad*tipo_movimientos.accion),0) as suma'))
            ->where('id_producto', $id_producto)
            ->where('nombre_farmacia', $nombre_farmacia)
            ->where('fecha_movimiento', '<', $fecha_inicio)
            ->where('estado', '=', 1)
            ->first();
        return $data;
    }

    public static function ShowTotalByProd($id_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin)
    {
        $cad = "select productos.id id_producto,
            productos.codigo codigo_producto,productos.nombre nombre_producto,unidades.abreviatura,
            coalesce(round(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion)::numeric,3),0)::float as total,
            farmacias.id as id_farmacia
            from kardexes
            inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
            inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
            inner join productos on kardexes.id_producto=productos.id 
            inner join unidades on productos.id_unidad=unidades.id 
            inner join farmacias on farmacias.id = kardexes.id_farmacia 
            where kardexes.estado=1 and kardex_lotes.estado=1 and nombre_farmacia='{$nombre_farmacia}'
            And id_producto='{$id_producto}'
            And fecha_movimiento<='$fecha_fin}' 
            group by productos.id,unidades.id,farmacias.id";

        $data = DB::select($cad);
        if (isset($data[0]))
            return $data[0];
        else
            return $data;
    }

    public static function ShowTotalByIdProductoAndIdFarmacia($id_producto, $id_farmacia, $fecha_fin)
    {

        $cad = "select 
farmacias.nombre farmacia,productos.codigo codigo_producto,unidades.abreviatura,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total,farmacias.id as id_farmacia
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id 
inner join farmacias on farmacias.id = kardexes.id_farmacia 
where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'
And productos.id='" . $id_producto . "'
And fecha_movimiento<='" . $fecha_fin . "' 
group by productos.id,unidades.id,farmacias.id";

        $data = DB::select($cad);
        if (isset($data[0]))
            return $data[0];
        else
            return $data;
    }

    public static function ShowTotalCantidadByIdProductoAndIdEstablecimiento($id_producto, $id_establecimiento, $fecha_fin)
    {
        $cad = "select productos.codigo codigo_producto,productos.nombre nombre_producto,/*unidades.abreviatura,*/coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total,farmacias.id_establecimiento
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id
--inner join unidades on productos.id_unidad=unidades.id 
inner join farmacias on farmacias.id = kardexes.id_farmacia 
where kardexes.estado=1 and kardex_lotes.estado=1 and id_establecimiento='" . $id_establecimiento . "'
And productos.id='" . $id_producto . "'
And fecha_movimiento<='" . $fecha_fin . "' 
group by productos.id,/*unidades.id,*/farmacias.id_establecimiento
/*having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0)>0*/
order by coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) desc";

        $data = DB::select($cad);
        if (isset($data[0]))
            return $data[0];
        else
            return $data;
    }

    public static function ShowTotalByIdProductoAndIdEstablecimiento($id_producto, $id_establecimiento, $fecha_fin)
    {
        $cad = "select farmacias.nombre farmacia,id_producto,nombre_producto,unidades.abreviatura,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total,farmacias.id as id_farmacia
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id 
inner join farmacias on farmacias.id = kardexes.id_farmacia 
where kardexes.estado=1 and kardex_lotes.estado=1 and id_establecimiento='" . $id_establecimiento . "'
And productos.id='" . $id_producto . "'
And fecha_movimiento<='" . $fecha_fin . "' 
group by id_producto,nombre_producto,unidades.abreviatura,farmacias.id
having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0)>0
order by coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) desc";

        $data = DB::select($cad);
        return $data;
    }

    public function ShowTotalKardexByFarmacia__($nombre_farmacia = "", $fecha_fin, $tipo = 0)
    {

        $cad = "select 
codigo_producto,nombre_producto,unidades.abreviatura unidad,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total
from kardexes 
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
Where kardexes.estado=1 and kardex_lotes.estado=1 and 1=1 ";
        if ($nombre_farmacia != "")
            $cad .= " And nombre_farmacia='" . $nombre_farmacia . "'";

        $cad .= " and fecha_movimiento<='" . $fecha_fin . "'
		and kardex_lotes.cantidad > 0 ";
        if ($tipo > 0)
            $cad .= " AND productos.id_tipo=" . $tipo;

        $cad .= " group by codigo_producto,nombre_producto,unidades.abreviatura
				Order by nombre_producto asc";

        $data = DB::select($cad);

        return $data;
    }
    public function ShowTotalKardexByFarmaciaResumen2($id_farmacia = "", $id_producto = "", $fecha_fin = "")
    {

        $cad = "select id_producto,T5.abreviatura unidad,coalesce(sum(T2.cantidad*T3.accion),0) as total
from kardexes T1
left join kardex_lotes T2 on T1.id=T2.id_kardex 
inner join tipo_movimientos T3 on T1.id_tipo_movimiento=T3.id
inner join productos T4 on T1.id_producto=T4.id
inner join unidades T5 on T4.id_unidad=T5.id
inner join farmacias T6 on T1.nombre_farmacia=T6.nombre 
Where T1.estado=1 and T2.estado=1 and 1=1 ";
        if ($id_farmacia != "")
            $cad .= " And T6.id='" . $id_farmacia . "'";
        if ($fecha_fin != "")
            $cad .= " and T1.fecha_movimiento<='" . $fecha_fin . "'";
        if ($id_producto > 0)
            $cad .= " AND T4.id=" . $id_producto;

        $cad .= " group by id_producto, T5.abreviatura,T1.nombre_farmacia
Order by id_producto asc";

        $data = DB::select($cad);

        return $data;
    }

    public function ShowTotalKardexByFarmaciaResumen($id_farmacia = "", $id_producto = "", $fecha_fin = "")
    {
        $cad = "select id_producto,T5.abreviatura unidad,coalesce(sum(T2.cantidad*T3.accion),0) as total
from kardexes T1
left join kardex_lotes T2 on T1.id=T2.id_kardex 
inner join tipo_movimientos T3 on T1.id_tipo_movimiento=T3.id
inner join productos T4 on T1.id_producto=T4.id
inner join unidades T5 on T4.id_unidad=T5.id
inner join farmacias T6 on T1.nombre_farmacia=T6.nombre 
Where T1.estado=1 and T2.estado=1 and 1=1 ";

        if ($id_farmacia != "")
            $cad .= " And T6.id='" . $id_farmacia . "'";
        if ($fecha_fin != "")
            $cad .= " and T1.fecha_movimiento<='" . $fecha_fin . "'";
        if ($id_producto > 0)
            $cad .= " AND T4.id=" . $id_producto;

        $cad .= " group by id_producto, T5.abreviatura,T1.nombre_farmacia
Order by id_producto asc";

        $data = DB::connection('pgsql_reportes')->select($cad);

        return $data;
    }



    public function ShowTotalKardexByFarmacia_old($nombre_farmacia = "", $fecha_fin, $tipo = 0, $id_consultorio = 0)
    {
        $cad = "Select * From sp_stock_total_by_farmacia ('" . $nombre_farmacia . "','" . $fecha_fin . "','" . $tipo . "','" . $id_consultorio . "');";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowTotalKardexByFarmacia3($nombre_farmacia = "", $fecha_fin, $tipo = 0, $id_consultorio = 0)
    {
        $cad = "Select * From sp_show_total_kardex_by_farmacia ('" . $nombre_farmacia . "','" . $fecha_fin . "','" . $tipo . "','" . $id_consultorio . "');";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowTotalKardexByFarmacia2($nombre_farmacia = "", $fecha_fin, $tipo = 0, $id_consultorio = 0)
    {
        return DB::select("SELECT * FROM sp_show_total_kardex_by_farmacia(?, ?, ?, ?)", [
            $nombre_farmacia,
            $fecha_fin,
            $tipo,
            $id_consultorio
        ]);
    }

    public function ShowTotalKardexByFarmacia($nombre_farmacia = "", $fecha_fin, $tipo = 0, $id_consultorio = 0)
    {
        return DB::connection('pgsql_reportes')->select("SELECT * FROM sp_show_total_kardex_by_farmacia(?, ?, ?, ?)", [
            $nombre_farmacia,
            $fecha_fin,
            $tipo,
            $id_consultorio
        ]);
    }


    public function ShowTotalKardexByFarmacia_old_2($nombre_farmacia = "", $fecha_fin, $tipo = 0, $id_consultorio = 0, $id_farmacia = 0)
    {

        $cad = "select 
codigo_producto,nombre_producto,T5.abreviatura unidad,T6.nombre tipo_producto,coalesce(sum(T2.cantidad*T3.accion),0) as total
,(
select coalesce(sum(kardex_lotes.cantidad),0) as total 
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto = T1.id_producto
and nombre_farmacia = T1.nombre_farmacia
and id_tipo_movimiento>3
and id_tipo_movimiento<8
and fecha_movimiento>=(CAST(now() AS DATE) - CAST('6 month' AS INTERVAL))::date
and fecha_movimiento<=now() limit 1
)egresos
,(
select coalesce(sum(kardex_lotes.cantidad),0) as recetas_devueltas
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto = T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=8
and fecha_movimiento>=(CAST(now() AS DATE) - CAST('6 month' AS INTERVAL))::date
and fecha_movimiento<=now() limit 1
)recetas_devueltas
,(
select count(distinct extract (month from fecha_movimiento)) as nro_mes
from kardexes
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento>3
and fecha_movimiento>=(CAST(now() AS DATE) - CAST('6 month' AS INTERVAL))::date
and fecha_movimiento<=now()
)nro_mes
from kardexes T1
left join kardex_lotes T2 on T1.id=T2.id_kardex 
inner join tipo_movimientos T3 on T1.id_tipo_movimiento=T3.id
inner join productos T4 on T1.id_producto=T4.id
inner join tipo_productos T6 on T6.id=T4.id_tipo
-- inner join producto_servicios T6 on T4.id=T6.id_producto
inner join unidades T5 on T4.id_unidad=T5.id
Where 1=1 ";
        if ($nombre_farmacia != "")
            $cad .= " And nombre_farmacia='" . $nombre_farmacia . "'";
        $cad .= " and fecha_movimiento<='" . $fecha_fin . "'
			and T2.cantidad > 0 ";
        if ($tipo > 0)
            $cad .= " AND T4.id_tipo=" . $tipo;
        if ($id_consultorio > 0)
            $cad .= " AND T4.id in (Select id_producto From producto_servicios Where id_servicio=" . $id_consultorio . " and id_farmacia=" . $id_farmacia . ")";

        $cad .= " group by codigo_producto,nombre_producto,T5.abreviatura,T6.nombre,T1.nombre_farmacia
Order by nombre_producto asc";

        $data = DB::select($cad);

        return $data;
    }

    public function ShowTotalKardexByFarmaciaConsolidado_old($nombre_farmacia = "", $fecha_ini, $fecha_fin, $id_anaquel, $id_consultorio, $tipo = 0)
    {

        $cad = "select 
codigo_producto,nombre_producto,T5.abreviatura unidad,T6.nombre nombre_tipoproducto
,max(T2.precio_unitario)precio_unitario, to_char(max(T2.fecha_vencimiento),'mm-yyyy') fecha_vencimiento
,(
select coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total 
from kardexes
left join receta_vales on receta_vales.nro_receta=kardexes.numero_movimiento
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where codigo_producto = T1.codigo_producto
and nombre_farmacia = T1.nombre_farmacia
and fecha_movimiento<'" . $fecha_ini . "'
)stock_desde

,(
select coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total 
from kardexes
left join receta_vales on receta_vales.nro_receta=kardexes.numero_movimiento
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where codigo_producto = T1.codigo_producto
and nombre_farmacia = T1.nombre_farmacia
and fecha_movimiento<='" . $fecha_fin . "'
)stock_hasta

,(
select coalesce(sum(kardex_lotes.cantidad),0) as total 
from kardexes
left join receta_vales on receta_vales.nro_receta=kardexes.numero_movimiento
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where codigo_producto = T1.codigo_producto
and nombre_farmacia = T1.nombre_farmacia
and id_tipo_movimiento in (2,9)
and fecha_movimiento>='" . $fecha_ini . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)ingresos
,(
select coalesce(sum(kardex_lotes.cantidad),0) as total 
from kardexes
left join receta_vales on receta_vales.nro_receta=kardexes.numero_movimiento
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where codigo_producto = T1.codigo_producto
and nombre_farmacia = T1.nombre_farmacia
and id_tipo_movimiento=8
and fecha_movimiento>='" . $fecha_ini . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)devueltas
,(
select coalesce(sum(kardex_lotes.cantidad),0) as total 
from kardexes
left join receta_vales on receta_vales.nro_receta=kardexes.numero_movimiento
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where codigo_producto = T1.codigo_producto
and nombre_farmacia = T1.nombre_farmacia
and id_tipo_movimiento>3
and id_tipo_movimiento<=7
and fecha_movimiento>='" . $fecha_ini . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)egresos
, ana.nombre anaquel 
from kardexes T1
left join receta_vales RV on RV.nro_receta=T1.numero_movimiento
left join kardex_lotes T2 on T1.id=T2.id_kardex
inner join tipo_movimientos T3 on T1.id_tipo_movimiento=T3.id
inner join productos T4 on T1.codigo_producto=T4.codigo
inner join unidades T5 on T4.id_unidad=T5.id
inner join tipo_productos T6 on T4.id_tipo=T6.id
Inner Join producto_anaqueles pana On T4.id=pana.id_producto
Inner Join farmacias fa On T1.nombre_farmacia = fa.nombre
Inner Join anaqueles ana On pana.id_anaquel=ana.id And ana.id_farmacia=fa.id
Where T1.estado=1 and pana.estado=1  And 1=1";
        if ($nombre_farmacia != "")
            $cad .= " And nombre_farmacia='" . $nombre_farmacia . "'";
        $cad .= " and fecha_movimiento<='" . $fecha_fin . "'
			and T2.cantidad > 0 ";
        if ($tipo > 0)
            $cad .= " AND T4.id_tipo=" . $tipo;

        if ($id_anaquel != "0")
            $cad .= " And T4.id in (Select id_producto From producto_anaqueles Where  estado=1 and id_anaquel=" . $id_anaquel . ")";

        if ($id_consultorio > 0)
            $cad .= " AND T4.id in (Select id_producto From producto_servicios Where id_servicio=" . $id_consultorio . ") AND RV.id_consultorio=" . $id_consultorio;
        $cad .= " group by codigo_producto,nombre_producto,T5.abreviatura,T1.nombre_farmacia,T6.nombre, ana.nombre
Order by ana.nombre, nombre_producto asc";

        $data = DB::select($cad);
        return $data;
    }

    public function ShowTotalKardexByFarmaciaConsolidado($id_farmacia = 0, $fecha_ini, $fecha_fin, $id_anaquel = 0, $id_consultorio = 0, $tipo = 0)
    {

        ini_set('memory_limit', '1280M');
        ini_set('max_execution_time', 300); // 5 minutes
        $p[] = $id_farmacia;
        $p[] = $fecha_ini;
        $p[] = $fecha_fin;
        $p[] = $id_anaquel;
        $p[] = $id_consultorio;
        $p[] = $tipo;

        return $this->readFunctionPostgres('fn_stock_consolidado', $p);
    }


    public function ShowTotalKardexByFarmaciaIci($nombre_farmacia = "", $fecha_fin, $id_establecimiento = 0)
    {

        $cad = "select id_producto
codigo_producto,nombre_producto,T5.abreviatura unidad,coalesce(sum(T2.cantidad*T3.accion),0) as total
,(
select coalesce(sum(kardex_lotes.cantidad),0) as total 
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto = T1.id_producto
and nombre_farmacia = T1.nombre_farmacia
and id_tipo_movimiento>3
and id_tipo_movimiento<8
and fecha_movimiento>=(CAST(now() AS DATE) - CAST('6 month' AS INTERVAL))::date
and fecha_movimiento<=now() limit 1
)egresos
,(
select coalesce(sum(kardex_lotes.cantidad),0) as recetas_devueltas
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto = T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=8
and fecha_movimiento>=(CAST(now() AS DATE) - CAST('6 month' AS INTERVAL))::date
and fecha_movimiento<=now() limit 1
)recetas_devueltas
,(
select count(distinct extract (month from fecha_movimiento)) as nro_mes
from kardexes
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento>3
and fecha_movimiento>=(CAST(now() AS DATE) - CAST('6 month' AS INTERVAL))::date
and fecha_movimiento<=now()
)nro_mes
,(
select 
sum(cantidad) as total
from cans
where id_producto=T1.id_producto
and id_establecimiento=" . $id_establecimiento . "
and ano=2016
limit 1
)can
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=1
and fecha_movimiento<=now()
limit 1
)saldo_inicial
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=2
and fecha_movimiento<=now()
limit 1
)ingreso_regular
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=3
and fecha_movimiento<=now()
limit 1
)ingreso_junta_medica
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=4
and fecha_movimiento<=now()
limit 1
)salida_receta
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=5
and fecha_movimiento<=now()
limit 1
)salida_diaria
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=6
and fecha_movimiento<=now()
limit 1
)salida_junta_medica
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=7
and fecha_movimiento<=now()
limit 1
)transferencia
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=8
and fecha_movimiento<=now()
limit 1
)receta_devuelta
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=9
and fecha_movimiento<=now()
limit 1
)ingreso_transferencia
from kardexes T1
left join kardex_lotes T2 on T1.id=T2.id_kardex 
inner join tipo_movimientos T3 on T1.id_tipo_movimiento=T3.id
inner join productos T4 on T1.id_producto=T4.id
inner join unidades T5 on T4.id_unidad=T5.id
Where T1.estado=1 and T2.estado=1 and 1=1 ";
        if ($nombre_farmacia != "")
            $cad .= " And nombre_farmacia='" . $nombre_farmacia . "'";
        $cad .= " and fecha_movimiento<='" . $fecha_fin . "'
			and T2.cantidad > 0 ";

        $cad .= " group by id_producto, codigo_producto,nombre_producto,T5.abreviatura,T1.nombre_farmacia
Order by nombre_producto asc";
        //echo $cad;

        $data = DB::select($cad);
        return $data;
    }

    public function ShowTotalKardexByFarmaciaSinStock($nombre_farmacia = "", $fecha_fin, $tipo = 0)
    {

        $cad = "select 
productos.codigo codigo_producto,productos.nombre nombre_producto,unidades.abreviatura unidad,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total
from kardexes 
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
Where kardexes.estado=1 and kardex_lotes.estado=1 and 1=1 ";
        if ($nombre_farmacia != "")
            $cad .= " And nombre_farmacia='" . $nombre_farmacia . "'";
        $cad .= " and fecha_vencimiento<='" . $fecha_fin . "'
				/*and kardex_lotes.cantidad <= 0 */";
        if ($tipo > 0)
            $cad .= " AND productos.id_tipo=" . $tipo;
        $cad .= "group by productos.id,unidades.abreviatura
		having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) <= 0 
		-- having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0 
				Order by nombre_producto asc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function ShowTotalKardexByFarmaciaSinStockKardex2($nombre_farmacia = "", $fecha_fin, $tipo = 0)
    {

        $cad = "select 
productos.codigo codigo_producto,productos.nombre nombre_producto,unidades.abreviatura unidad,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total
from kardexes 
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
Where kardexes.estado=1 and kardex_lotes.estado=1 and 1=1 ";
        if ($nombre_farmacia != "")
            $cad .= " And nombre_farmacia='" . $nombre_farmacia . "'";
        $cad .= " and fecha_movimiento<='" . $fecha_fin . "'
				/*and kardex_lotes.cantidad <= 0 */";
        if ($tipo > 0)
            $cad .= " AND productos.id_tipo=" . $tipo;
        $cad .= "group by productos.id,unidades.abreviatura
		having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) <= 0 
		-- having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0 
				Order by nombre_producto asc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function ShowTotalKardexByFarmaciaSinStockKardex($nombre_farmacia = "", $fecha_fin, $tipo = 0)
    {
        $cad = "select 
productos.codigo codigo_producto,productos.nombre nombre_producto,unidades.abreviatura unidad,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as total
from kardexes 
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
Where kardexes.estado=1 and kardex_lotes.estado=1 and 1=1 ";

        if ($nombre_farmacia != "")
            $cad .= " And nombre_farmacia='" . $nombre_farmacia . "'";

        $cad .= " and fecha_movimiento<='" . $fecha_fin . "'
				/*and kardex_lotes.cantidad <= 0 */";

        if ($tipo > 0)
            $cad .= " AND productos.id_tipo=" . $tipo;

        $cad .= " group by productos.id,unidades.abreviatura
		having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) <= 0 
		-- having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0 
				Order by nombre_producto asc";

        $data = DB::connection('pgsql_reportes')->select($cad);
        return $data;
    }


    public function ShowCountTotalKardexByFarmacia2($nombre_farmacia, $fecha_fin, $tipo = 0)
    {

        $cad = "
select count(*) total from(
select 
productos.codigo codigo_producto,productos.nombre nombre_producto,unidades.abreviatura
from kardexes 
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
Where kardexes.estado=1 and kardex_lotes.estado=1 and nombre_farmacia='" . $nombre_farmacia . "'
and fecha_movimiento<='" . $fecha_fin . "'
and kardex_lotes.cantidad > 0";
        if ($tipo > 0)
            $cad .= " AND productos.id_tipo=" . $tipo;
        $cad .= "group by productos.id,unidades.abreviatura
)R";

        $data = DB::select($cad);
        return $data[0]->total;
    }

    public function ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, $tipo = 0)
    {
        $cad = "
select count(*) total from(
select 
productos.codigo codigo_producto,productos.nombre nombre_producto,unidades.abreviatura
from kardexes 
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
Where kardexes.estado=1 and kardex_lotes.estado=1 and nombre_farmacia='" . $nombre_farmacia . "'
and fecha_movimiento<='" . $fecha_fin . "'
and kardex_lotes.cantidad > 0";
        if ($tipo > 0)
            $cad .= " AND productos.id_tipo=" . $tipo;
        $cad .= "group by productos.id,unidades.abreviatura
)R";

        $data = DB::connection('pgsql_reportes')->select($cad);
        return $data[0]->total;
    }


    public function GetCantidadCAN($id_producto, $id_establecimiento)
    {
        $data = DB::table('cans')
            ->select(DB::raw('sum(cantidad) as total'))
            ->where('codido_producto', $id_producto)
            ->where('id_establecimiento', $id_establecimiento)
            ->where('ano', '=', 2016)
            ->first();
        return $data;
    }

    public function ShowIngresosByProd($id_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin)
    {
        $data = DB::table('kardexes')
            ->join('tipo_movimientos', 'kardexes.id_tipo_movimiento', '=', 'tipo_movimientos.id')
            ->select(DB::raw('coalesce(sum(kardexes.cantidad*tipo_movimientos.accion),0) as total'))
            ->where('id_producto', $id_producto)
            ->where('nombre_farmacia', $nombre_farmacia)
            ->where('id_tipo_movimiento', '<=', 3)
            ->where('fecha_movimiento', '>=', $fecha_inicio)
            ->where('fecha_movimiento', '<=', $fecha_fin)
            ->where('kardexes.estado', '=', 1)
            ->first();
        return $data;
    }

    public static function ShowEgresosByProd($id_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin)
    {
        /*
          $data = DB::table('kardexes')
          ->join('tipo_movimientos', 'kardexes.id_tipo_movimiento', '=', 'tipo_movimientos.id')
          ->select(DB::raw('coalesce(sum(kardexes.cantidad),0) as total'))
          ->where('codigo_producto', $codigo_producto)
          ->where('nombre_farmacia', $nombre_farmacia)
          ->where('id_tipo_movimiento', '>', 3)
          ->where('id_tipo_movimiento', '<', 8)
          ->where('fecha_movimiento', '>=', $fecha_inicio)
          ->where('fecha_movimiento', '<=', $fecha_fin)
          ->first();
          return $data;
         */
        $cad = "select coalesce(sum(kardex_lotes.cantidad),0) as total 
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and id_producto = '" . $id_producto . "'
and nombre_farmacia = '" . $nombre_farmacia . "'
and id_tipo_movimiento>3
and id_tipo_movimiento<8
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1";

        $data = DB::select($cad);
        return $data[0];
    }

    public static function ShowRecetasDevueltas($id_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin)
    {
        /*
          $data = DB::table('kardexes')
          ->join('tipo_movimientos', 'kardexes.id_tipo_movimiento', '=', 'tipo_movimientos.id')
          ->select(DB::raw('coalesce(sum(kardexes.cantidad),0) as recetas_devueltas'))
          ->where('codigo_producto', $codigo_producto)
          ->where('nombre_farmacia', $nombre_farmacia)
          ->where('id_tipo_movimiento', '=', 8)
          ->where('fecha_movimiento', '>=', $fecha_inicio)
          ->where('fecha_movimiento', '<=', $fecha_fin)
          ->first();
          return $data;
         */

        $cad = "select coalesce(sum(kardex_lotes.cantidad),0) as recetas_devueltas
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and id_producto = '" . $id_producto . "'
and nombre_farmacia='" . $nombre_farmacia . "'
and id_tipo_movimiento=8
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1";

        $data = DB::select($cad);
        return $data[0];
    }

    public function ShowSaldosInicialesByMovAndFarmacia($tipo_movimiento, $nombre_farmacia)
    {
        $data = DB::table('kardexes')
            ->select('kardexes.*')
            ->where('kardexes.tipo_movimiento', '=', $tipo_movimiento)
            ->where('kardexes.nombre_farmacia', '=', $nombre_farmacia)
            ->where('kardexes.estado', '=', 1)
            ->orderBy('kardexes.nombre_producto', 'asc')
            ->get();
        return $data;
    }

    public function GetKardexSaldoInicialById($id)
    {
        $data = DB::table('kardexes')
            ->select('kardexes.*')
            ->where('kardexes.id', '=', $id)
            ->where('kardexes.estado', '=', 1)
            ->first();
        return $data;
    }

    public function Movimientos($nombre_farmacia)
    {
        $data = DB::table('stock_farmacias')
            ->join('kardexes', 'stock_farmacias.codigo_producto', '=', 'kardexes.codigo_producto')
            ->select('stock_farmacias.codigo_producto', 'stock_farmacias.nombre_producto', DB::raw('sum(kardexes.cantidad) as suma'), 'kardexes.tipo_movimiento')
            ->where('stock_farmacias.nombre_farmacia', $nombre_farmacia)
            ->where('kardexes.estado', '=', 1)
            ->groupBy('stock_farmacias.codigo_producto', 'stock_farmacias.nombre_producto', 'kardexes.tipo_movimiento')
            ->orderBy('stock_farmacias.nombre_producto', 'asc')
            ->get();
        //var_dump($reclamos); exit;
        return $data;
    }

    public function PorMovimiento($nombre_farmacia)
    {
        $data = DB::table('stock_farmacias')
            ->join('kardexes', 'stock_farmacias.codigo_producto', '=', 'kardexes.codigo_producto')
            ->select('stock_farmacias.codigo_producto', 'stock_farmacias.nombre_producto', DB::raw('sum(kardexes.cantidad) as suma'), 'kardexes.tipo_movimiento')
            ->where('stock_farmacias.nombre_farmacia', $nombre_farmacia)
            ->where('kardexes.tipo_movimiento', 'GE')
            ->where('kardexes.estado', '=', 1)
            ->groupBy('stock_farmacias.codigo_producto', 'stock_farmacias.nombre_producto', 'kardexes.tipo_movimiento')
            ->orderBy('stock_farmacias.nombre_producto', 'asc')
            ->get();
        //var_dump($data); exit;
        return $data;
    }

    public function CantSalidasDiarias($tipo_movimiento, $fecha_movimiento, $nombre_farmacia)
    {
        $data = DB::table('kardexes')
            ->select('codigo_producto', 'nombre_producto', 'fecha_movimiento', DB::raw('sum(cantidad) as cantidad'))
            ->where('tipo_movimiento', $tipo_movimiento)
            ->where('fecha_movimiento', $fecha_movimiento)
            ->where('nombre_farmacia', $nombre_farmacia)
            ->where('kardexes.estado', '=', 1)
            ->groupBy('id_producto', 'nombre_producto', 'fecha_movimiento')
            ->get();
        return $data;
    }

    public function ShowAnos()
    {

        $cad = "select distinct extract(year from fecha_movimiento) ano from kardexes where kardexes.estado=1 order by extract(year from fecha_movimiento) desc";
        $data = DB::select($cad);
        return $data;
    }

    static function getFechaServidor2()
    {

        $cad = "SELECT now()::date fechaservidor";
        $data = DB::select($cad);
        return $data[0]->fechaservidor;
    }

    public static function getFechaServidor()
    {
        $cad = "SELECT now()::date fechaservidor";
        $data = DB::connection('pgsql_reportes')->select($cad);
        return $data[0]->fechaservidor;
    }


    static function getHoraServidor()
    {

        $cad = "SELECT now()::TIME horaservidor";
        $data = DB::select($cad);
        return $data[0]->horaservidor;
    }

    function getFechaServidorRestaMeses2($mes)
    {

        $cad = "SELECT (CAST(now() AS DATE) - CAST('" . $mes . " month' AS INTERVAL))::date fechaservidor";
        $data = DB::select($cad);
        return $data[0]->fechaservidor;
    }

    function getFechaServidorRestaMeses($mes)
    {
        $cad = "SELECT (CAST(now() AS DATE) - CAST('" . $mes . " month' AS INTERVAL))::date fechaservidor";
        $data = DB::connection('pgsql_reportes')->select($cad);
        return $data[0]->fechaservidor;
    }

    public function GetKardexLotesByCodigoProductoAndIdFarmacia($id_producto, $id_farmacia)
    {
        $cad = "select productos.id id_producto,
productos.nombre prod_farmaceutico,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as cantidad 
,min(kardexes.created_at)created_at, productos.presentacion_comercial as presentacion, kardex_lotes.presentacion_comercial
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
inner join farmacias on farmacias.id = kardexes.id_farmacia 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'";
        if ($id_producto != "")
            $cad .= " and kardexes.id_producto ='" . $id_producto . "'";
        $cad .= " group by productos.id,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario, kardex_lotes.presentacion_comercial, productos.presentacion_comercial, 
to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0
order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc";
        $data = DB::select($cad);
        return $data;
    }

    public function GetKardexProductoByIdFarmacia($id_farmacia)
    {
        $cad = "Select p.id id_producto,p.nombre prod_farmaceutico,p.codigo codigo_producto,f.id id_farmacia From kardexes k
Left Join kardex_lotes l On k.id=l.id_kardex
Inner Join productos p On k.id_producto=p.id
Inner Join unidades d On p.id_unidad=d.id
Inner Join farmacias f On k.nombre_farmacia=f.nombre 
Inner Join tipo_movimientos tm On k.id_tipo_movimiento=tm.id
Where k.estado=1 and l.estado=1 and f.id='" . $id_farmacia . "'
Group By p.id,p.nombre,p.codigo,f.id
Having Coalesce(sum(l.cantidad*l.presentacion_comercial*tm.accion),0) > 0;";
        $data = DB::select($cad);
        return $data;
    }

    public function GetKardexProductoByIdFarmaciaExterna($id_farmacia, $id_establecimiento)
    {

        $cad = "select b.codigo codeess, b.nombre eess, b.nivel niveleess, a.id_producto, a.codigo_producto, a.prod_farmaceutico, a.nivel prdnivel, a.id_farmacia,
    a.nivel1, a.nivel2, a.nivel3 from
    (
    Select p.id id_producto,p.nombre prod_farmaceutico,p.codigo codigo_producto, p.nivel, f.id id_farmacia,
    case when p.nivel in(1) then 1 end NIVEL1, case when p.nivel in(1,2) then 2 end NIVEL2,
    case when p.nivel in (1,2,3) then 3 end NIVEL3 From kardexes k
    Left Join kardex_lotes l On k.id=l.id_kardex
    Inner Join productos p On k.id_producto=p.id
    Inner Join unidades d On p.id_unidad=d.id
    Inner Join farmacias f On k.nombre_farmacia=f.nombre 
    Inner Join tipo_movimientos tm On k.id_tipo_movimiento=tm.id
    Where k.estado=1 and l.estado=1 and f.id='" . $id_farmacia . "'
    Group By p.id,p.nombre,p.codigo, p.nivel, f.id
    Having Coalesce(sum(l.cantidad*l.presentacion_comercial*tm.accion),0) > 0
    )a right join
    (select * from establecimientos) b 
    on a.nivel1 = b.nivel or a.nivel2=b.nivel or a.nivel3=b.nivel
    where b.id='" . $id_establecimiento . "'";
        $data = DB::select($cad);
        return $data;
    }

    public function GetKardexProductoByIdFarmaciaSinSistema($id_establecimiento)
    {

        $cad = "select b.codigo codeess, b.nombre eess, b.nivel niveleess, a.id_producto, a.codigo_producto, a.prod_farmaceutico, a.nivel prdnivel, a.id_farmacia,
    a.nivel1, a.nivel2, a.nivel3 from
    (
    Select p.id id_producto,p.nombre prod_farmaceutico,p.codigo codigo_producto, p.nivel, f.id id_farmacia,
    case when p.nivel in(1) then 1 end NIVEL1, case when p.nivel in(1,2) then 2 end NIVEL2,
    case when p.nivel in (1,2,3) then 3 end NIVEL3 From kardexes k
    Left Join kardex_lotes l On k.id=l.id_kardex
    Inner Join productos p On k.id_producto=p.id
    Inner Join unidades d On p.id_unidad=d.id
    Inner Join farmacias f On k.nombre_farmacia=f.nombre 
    Inner Join tipo_movimientos tm On k.id_tipo_movimiento=tm.id
    Where k.estado=1 and l.estado=1 and f.id='" . $id_farmacia . "'
    Group By p.id,p.nombre,p.codigo, p.nivel, f.id
    Having Coalesce(sum(l.cantidad*l.presentacion_comercial*tm.accion),0) > 0
    )a right join
    (select * from establecimientos) b 
    on a.nivel1 = b.nivel or a.nivel2=b.nivel or a.nivel3=b.nivel
    where b.id='" . $id_establecimiento . "'";
        $data = DB::select($cad);
        return $data;
    }


    public function GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia($id_producto, $lote, $fecha_vencimiento, $registro_sanitario, $id_farmacia, $precio_unitario, $id_transferencia = 0)
    {

        $cad = "select 
productos.id id_producto,productos.nombre prod_farmaceutico,tipo_productos.nombre nombre_tipo,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
,COALESCE(kardex_lotes.precio_unitario,'0')precio_unitario
,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
,coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion),0) as cantidad ,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as cantidad2, kardex_lotes.presentacion_comercial
,min(kardexes.created_at)created_at, productos.presentacion_comercial as presentacion
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join farmacias on farmacias.id = kardexes.id_farmacia 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'";
        if ($id_producto != "")
            $cad .= " and kardexes.id_producto ='" . $id_producto . "'";
        if ($lote != "")
            $cad .= " and kardex_lotes.lote ='" . $lote . "'";
        if ($fecha_vencimiento != "")
            $cad .= " and coalesce(to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy'),' ') ='" . $fecha_vencimiento . "'";
        if ($registro_sanitario != "")
            $cad .= " and kardex_lotes.registro_sanitario ='" . $registro_sanitario . "'";
        if ($precio_unitario != "")
            $cad .= " and kardex_lotes.precio_unitario =" . $precio_unitario . "";
        $cad .= " group by productos.id,tipo_productos.nombre,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario,kardex_lotes.precio_unitario,kardex_lotes.presentacion_comercial,
to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
having coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion),0) > 0
order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc";

        $data = DB::select($cad);
        return $data;
    }

    public function GetReporteKardexLotes($id_farmacia, $fecha_fin)
    {

        $cad = "select 
productos.id id_producto,productos.nombre prod_farmaceutico,tipo_productos.nombre nombre_tipo,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
,COALESCE(kardex_lotes.precio_unitario,'0')precio_unitario
,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
,coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion),0) as cantidad ,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as cantidad2, kardex_lotes.presentacion_comercial
,min(kardexes.created_at)created_at, productos.presentacion_comercial as presentacion
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join farmacias on farmacias.id = kardexes.id_farmacia 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "' and kardexes.fecha_movimiento<='" . $fecha_fin . "'";
        $cad .= " group by productos.id,tipo_productos.nombre,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario,kardex_lotes.precio_unitario,kardex_lotes.presentacion_comercial,
to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
having coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion),0) > 0
order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc";

        $data = DB::select($cad);
        return $data;
    }
    public function GetKardexLotesByIdProductoAndIdFarmacia($id_producto, $id_farmacia)
    {

        $cad = "Select p.id id_producto,p.nombre prod_farmaceutico,p.codigo codigo_producto,f.id as id_farmacia,um.abreviatura,l.lote,l.registro_sanitario
,to_char(l.fecha_vencimiento,'mm-yyyy') fecha_vencimiento, l.precio_unitario
,coalesce(sum(l.cantidad*l.presentacion_comercial*tm.accion),0) cantidad ,min(k.created_at)created_at, p.presentacion_comercial as presentacion, l.presentacion_comercial From kardexes k
Left Join kardex_lotes l On k.id=l.id_kardex
Inner Join productos p On k.id_producto=p.id
Inner Join unidades um On p.id_unidad=um.id
Inner Join farmacias f On k.nombre_farmacia=f.nombre 
Inner Join tipo_movimientos tm On k.id_tipo_movimiento=tm.id
Where k.estado=1 and l.estado=1 and f.id='" . $id_farmacia . "' And p.id='" . $id_producto . "'
Group By p.id,f.id,um.abreviatura,l.lote,l.registro_sanitario, l.precio_unitario,p.presentacion_comercial,l.presentacion_comercial,
--to_char(l.fecha_vencimiento,'mm-yyyy')
EXTRACT(YEAR FROM l.fecha_vencimiento), EXTRACT(MONTH FROM l.fecha_vencimiento), to_char(l.fecha_vencimiento,'mm-yyyy')
Having Coalesce(sum(l.cantidad*l.presentacion_comercial*tm.accion),0) > 0
Order By  EXTRACT(YEAR FROM l.fecha_vencimiento), EXTRACT(MONTH FROM l.fecha_vencimiento);
--Order By to_char(l.fecha_vencimiento,'mm-yyyy') Asc;";
        $data = DB::select($cad);
        return $data;
    }

    public function GetKardexLotesByIdProductoAndIdFarmaciaAndCantidad($id_producto, $id_farmacia, $cantidad_prescrita)
    {

        $cad = "Select 
productos.nombre prod_farmaceutico,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento, kardex_lotes.precio_unitario, kardex_lotes.presentacion_comercial
,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as cantidad
,min(kardexes.created_at)created_at
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join productos on kardexes.id_producto=productos.id 
Inner join unidades on productos.id_unidad=unidades.id
inner join farmacias on farmacias.id = kardexes.id_farmacia 
Inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'
and productos.id ='" . $id_producto . "'
Group by productos.id,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario, kardex_lotes.precio_unitario, kardex_lotes.presentacion_comercial,
to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy'), to_char(kardex_lotes.fecha_vencimiento,'yyyy-mm')
Having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0
Order by to_char(kardex_lotes.fecha_vencimiento,'yyyy-mm') Asc";
        $data = DB::select($cad);
        return $data;
    }

    public static function GetStockActualByIdEstablecimientoAndIdProducto($id_establecimiento, $id_producto)
    {

        $cad = "Select * From sp_report_stock_actual_by_establicimiento(" . $id_establecimiento . "," . $id_producto . ");";
        $data = DB::select($cad);
        return $data;
    }

    public static function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento2($id_producto, $id_farmacia, $fecha_inicio, $fecha_fin)
    {

        $cad = "select productos.id id_producto,
productos.nombre prod_farmaceutico,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
,coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion),0) as cantidad 
,min(kardexes.created_at)created_at, productos.presentacion_comercial as presentacion
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
inner join farmacias on farmacias.id = kardexes.id_farmacia 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'";
        if ($id_producto != "")
            $cad .= " and kardexes.id_producto ='" . $id_producto . "'";

        //$cad .= " And kardex_lotes.fecha_vencimiento>='" . $fecha_inicio . "'";
        $cad .= " And kardex_lotes.fecha_vencimiento<='" . $fecha_fin . "'";

        $cad .= " group by productos.id,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario, productos.presentacion_comercial,
to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
having coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion),0) > 0
order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc";
        $data = DB::select($cad);
        return $data;
    }

    public static function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento($id_producto, $id_farmacia, $fecha_inicio, $fecha_fin)
    {
        $cad = "select productos.id id_producto,
productos.nombre prod_farmaceutico,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
,coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion),0) as cantidad 
,min(kardexes.created_at)created_at, productos.presentacion_comercial as presentacion
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
inner join farmacias on farmacias.id = kardexes.id_farmacia 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'";

        if ($id_producto != "")
            $cad .= " and kardexes.id_producto ='" . $id_producto . "'";

        //$cad .= " And kardex_lotes.fecha_vencimiento>='" . $fecha_inicio . "'";
        $cad .= " And kardex_lotes.fecha_vencimiento<='" . $fecha_fin . "'";

        $cad .= " group by productos.id,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario, productos.presentacion_comercial,
to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
having coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial*tipo_movimientos.accion),0) > 0
order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc";

        $data = DB::connection('pgsql_reportes')->select($cad);
        return $data;
    }


    public function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido2($id_producto, $id_farmacia, $fecha_vencimiento)
    {

        $cad = "select 
productos.nombre prod_farmaceutico,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as cantidad 
,min(kardexes.created_at)created_at
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join productos on kardexes.id_producto=productos.id 
inner join unidades on productos.id_unidad=unidades.id
inner join farmacias on farmacias.id = kardexes.id_farmacia 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'";
        if ($id_producto != "")
            $cad .= " and kardexes.id_producto ='" . $id_producto . "'";

        $cad .= " And kardex_lotes.fecha_vencimiento < '" . $fecha_vencimiento . "'";

        $cad .= " group by productos.id,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario,
to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0
order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc";
        $data = DB::select($cad);
        return $data;
    }

    public function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido($id_producto, $id_farmacia, $fecha_vencimiento)
    {
        $cad = "select 
        productos.nombre prod_farmaceutico,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
        ,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
        ,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as cantidad 
        ,min(kardexes.created_at)created_at
        from kardexes
        inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
        inner join productos on kardexes.id_producto=productos.id 
        inner join unidades on productos.id_unidad=unidades.id
        inner join farmacias on farmacias.id = kardexes.id_farmacia 
        inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
        where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'";

        if ($id_producto != "") {
            $cad .= " and kardexes.id_producto ='" . $id_producto . "'";
        }

        $cad .= " And kardex_lotes.fecha_vencimiento < '" . $fecha_vencimiento . "'";

        $cad .= " group by productos.id,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario,
        to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
        having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0
        order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc";

        $data = DB::connection('pgsql_reportes')->select($cad);
        return $data;
    }


    public function GetCargaPecosaByIdGuiaAndIdFarmacia($id_guia_entrada, $id_farmacia)
    {

        $cad = "select T2.id_producto,T3.codigo,T3.nombre,T2.id_producto,SUM(T2.cantidad)cantidad,T4.nombre nombre_farmacia
-- ,precio_promedio
-- ,(select precio_promedio from carga_pecosas c where c.id_guia_entrada=T2.id_guia_entrada and id_producto=T2.id_producto limit 1)
from guia_entradas T1
inner join carga_pecosas T2 on T1.id=T2.id_guia_entrada 
inner join productos T3 on T2.id_producto=T3.id
inner join farmacias T4 on T1.id_farmacia=T4.id
where T1.id_farmacia=" . $id_farmacia . " 
And T1.id = " . $id_guia_entrada . "
And T2.estado=1   
Group by T2.id_guia_entrada,T3.codigo,T3.nombre,T2.id_producto,T4.nombre";
        $data = DB::select($cad);
        return $data;
    }

    public function GetCargaPecosaByIdGuiaAndIdFarmaciaAndIdProducto($id_guia_entrada, $id_farmacia, $id_producto)
    {

        $cad = "select 
T2.lote,T2.fecha_vencimiento,T2.registro_sanitario,T2.cantidad,T2.precio_promedio 
from guia_entradas T1
inner join carga_pecosas T2 on T1.id=T2.id_guia_entrada 
inner join productos T3 on T2.id_producto=T3.id
where T1.id_farmacia=" . $id_farmacia . " 
and T1.id = " . $id_guia_entrada . " 
and T2.id_producto=" . $id_producto;
        $data = DB::select($cad);
        return $data;
    }

    public function UpdateIdAnaquelByProducto($id_producto, $id_anaquel, $id_farmacia, $id_user)
    {
        $cad = "Update kardexes Set id_anaquel=" . $id_anaquel . ", id_user_update=" . $id_user . ", updated_at=now() Where id_producto=" . $id_producto . " And id_farmacia=" . $id_farmacia . " And id_anaquel isNull";
        $data = DB::select($cad);
        return $data;
    }

    public static function RegistrarTransferenciaServicio($id_transferencia, $id_usuario, $id_farmacia)
    {

        $cad = "Select sp_insert_transferencia_servicio(" . $id_transferencia . "," . $id_usuario . "," . $id_farmacia . ");";
        $data = DB::select($cad);

        return $data[0];
    }

    public static function postRegistrarIngreso($id_pecosa, $id_usuario, $id_farmacia, $ano_nota, $nota_entrada)
    {

        $cad = "Select sp_insert_kardexpecosa(" . $id_pecosa . "," . $id_usuario . "," . $id_farmacia . "," . $ano_nota . ",'" . $nota_entrada . "');";
        $data = DB::select($cad);
        return $data[0];
    }

    public static function RegistrarTransferencia($id_tr, $iansferencia, $id_usuario, $id_farmacia, $ano_nota, $nota_entrada)
    {

        $cad = "Select sp_insert_kardexpecosa(" . $id_pecosa . "," . $id_usuario . "," . $id_farmacia . "," . $ano_nota . ",'" . $nota_entrada . "');";
        $data = DB::select($cad);
        return $data[0];
    }

    public function GetProductoPecosa($id_pecosa, $id_producto)
    {
        $cad = "Select id,id_producto, registro_sanitario, lote, precio_promedio precio, fecha_vencimiento, cantidad, presentacion_comercial 
            From carga_pecosas Where id_guia_entrada=" . $id_pecosa . " And id_producto=" . $id_producto . " And estado=1 ";
        $data = DB::select($cad);
        return $data;
    }
    public function GetBuscaTransferencia($id_farmacia, $id_transferencia)
    {
        $cad = "Select * From transferencias inner join kardexes on kardexes.id_nro_movimiento=transferencias.id Where kardexes.id_farmacia=" . $id_farmacia . " And kardexes.id_nro_movimiento=" . $id_transferencia . " and transferencias.estado>0 and kardexes.estado=1 and tipo_movimiento = 'TRANS'";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowTotalKardexByFarmaciaIciMes($nombre_establecimiento = "", $fecha_inicio, $fecha_fin)
    {
        $cad = "Select * From sp_show_total_ici_by_farmacia ('" . $nombre_establecimiento . "','" . $fecha_inicio . "','" . $fecha_fin . "');";
        $data = DB::select($cad);
        return $data;
    }


    public function ShowTotalKardexByFarmaciaIciMes_old($nombre_farmacia = "", $fecha_inicio, $fecha_fin, $id_establecimiento = 0)
    {
        $cad = "select 
T4.id id_producto, T4.codigo codigo_producto, T4.nombre nombre_producto,max(T2.precio_unitario)precio, to_char(max(T2.fecha_vencimiento),'mm-yyyy') fecha, T5.abreviatura unidad,coalesce(sum(T2.cantidad*T3.accion),0) as total, T4.id_tipo
,(
select coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial),0) as total 
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto = T1.id_producto
and nombre_farmacia = T1.nombre_farmacia
and id_tipo_movimiento>3
and id_tipo_movimiento<8
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)egresos
,(
select coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial),0) as recetas_devueltas
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto = T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=8
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)recetas_devueltas
,(
select count(distinct extract (month from fecha_movimiento)) as nro_mes
from kardexes
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento>3
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)nro_mes
,(
select 
sum(cantidad) as total
from cans
where id_producto=T1.id_producto
and id_establecimiento=" . $id_establecimiento . "
and ano=2016
limit 1
)can
,(
select 
coalesce(sum(KL.cantidad*TM.accion*KL.presentacion_comercial),0) as suma
from kardexes
left join kardex_lotes KL on kardexes.id=KL.id_kardex 
inner join tipo_movimientos TM on kardexes.id_tipo_movimiento=TM.id
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and fecha_movimiento<'" . $fecha_inicio . "'
and kardexes.estado=1 and KL.estado=1
limit 1
)saldo_inicial
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=2
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
limit 1
)ingreso_regular
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=3
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
limit 1
)ingreso_junta_medica
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=4
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
limit 1
)salida_receta
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=5
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
limit 1
)salida_diaria
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=6
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
limit 1
)salida_junta_medica
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=7
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
limit 1
)transferencia
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=8
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
limit 1
)receta_devuelta
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
where id_producto=T1.id_producto
and nombre_farmacia=T1.nombre_farmacia
and id_tipo_movimiento=9
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
limit 1
)ingreso_transferencia
from kardexes T1
left join kardex_lotes T2 on T1.id=T2.id_kardex 
inner join tipo_movimientos T3 on T1.id_tipo_movimiento=T3.id
inner join productos T4 on T1.id_producto=T4.id
inner join unidades T5 on T4.id_unidad=T5.id
Where T1.estado=1 and T2.estado=1 and 1=1 ";
        if ($nombre_farmacia != "")
            $cad .= " And nombre_farmacia='" . $nombre_farmacia . "'";
        $cad .= " and fecha_movimiento<='" . $fecha_fin . "'
            and T2.cantidad > 0 ";

        $cad .= " group by T4.id, T4.nombre,T5.abreviatura,T1.nombre_farmacia, T1.id_producto, T4.id_tipo
Order by nombre_producto asc";


        $data = DB::select($cad);
        return $data;
    }

    public function ShowCountTotalKardexByEstablecimiento($nombre_establecimiento, $fecha_inicio, $fecha_fin, $tipo = 0)
    {
        $cad = "
select count(*) total from(
select id_producto
codigo_producto,nombre_producto,unidades.abreviatura
from kardexes 
inner join farmacias on farmacias.id = kardexes.id_farmacia
inner join establecimientos on establecimientos.id = farmacias.id_establecimiento
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner join productos on kardexes.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
Where kardexes.estado=1 and kardex_lotes.estado=1 and establecimientos.nombre='" . $nombre_establecimiento . "'
and fecha_movimiento<='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and kardex_lotes.cantidad > 0";
        if ($tipo > 0)
            $cad .= " AND productos.id_tipo=" . $tipo;
        $cad .= "group by id_producto,codigo_producto,nombre_producto,unidades.abreviatura
)R";

        $data = DB::select($cad);
        return $data[0]->total;
    }

    public function ShowTotalKardexByFarmaciaIciPorEstablecimiento($nombre_establecimiento = "", $fecha_inicio, $fecha_fin, $id_establecimiento = 0)
    {
        $cad = "select 
id_producto, codigo_producto, nombre_producto,max(T2.precio_unitario)precio, to_char(max(T2.fecha_vencimiento),'mm-yyyy') fecha, T5.abreviatura 

unidad,coalesce(sum(T2.cantidad*T3.accion),0) as total, T4.id_tipo
,(
select coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial),0) as total 
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto = T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento>3
and id_tipo_movimiento<8
and id_establecimiento=" . $id_establecimiento . "
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)egresos
,(
select coalesce(sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial),0) as recetas_devueltas
from kardexes
inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto = T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento=8
and id_establecimiento=" . $id_establecimiento . "
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)recetas_devueltas
,(
select count(distinct extract (month from fecha_movimiento)) as nro_mes
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento>3
and id_establecimiento=" . $id_establecimiento . "
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "' limit 1
)nro_mes
,(
select 
sum(cantidad) as total
from cans
where id_producto=T1.id_producto
and id_establecimiento=" . $id_establecimiento . "
and ano=2016
limit 1
)can
,(
select 
coalesce(sum(KL.cantidad*TM.accion*KL.presentacion_comercial),0) as suma
from kardexes
left join kardex_lotes KL on kardexes.id=KL.id_kardex 
inner join tipo_movimientos TM on kardexes.id_tipo_movimiento=TM.id
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and fecha_movimiento<'" . $fecha_inicio . "'
and id_establecimiento=" . $id_establecimiento . "
and kardexes.estado=1 and KL.estado=1
limit 1
)saldo_inicial
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento=2
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and id_establecimiento=" . $id_establecimiento . "
limit 1
)ingreso_regular
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento=3
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and id_establecimiento=" . $id_establecimiento . "
limit 1
)ingreso_junta_medica
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento=4
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and id_establecimiento=" . $id_establecimiento . "
limit 1
)salida_receta
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento=5
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and id_establecimiento=" . $id_establecimiento . "
limit 1
)salida_diaria
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento=6
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and id_establecimiento=" . $id_establecimiento . "
limit 1
)salida_junta_medica
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento=7
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and id_establecimiento=" . $id_establecimiento . "
limit 1
)transferencia
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_tipo_movimiento=8
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and id_establecimiento=" . $id_establecimiento . "
limit 1
)receta_devuelta
,(
select 
coalesce(sum(cantidad),0) as suma
from kardexes
inner Join farmacias f On f.id=kardexes.id_farmacia 
inner Join establecimientos e On e.id=f.id_establecimiento  
where id_producto=T1.id_producto
and f.id_establecimiento=E.id
and id_establecimiento=" . $id_establecimiento . "
and id_tipo_movimiento=9
and fecha_movimiento>='" . $fecha_inicio . "'
and fecha_movimiento<='" . $fecha_fin . "'
and id_establecimiento=" . $id_establecimiento . "
limit 1
)ingreso_transferencia
from kardexes T1
left join kardex_lotes T2 on T1.id=T2.id_kardex 
inner join tipo_movimientos T3 on T1.id_tipo_movimiento=T3.id
inner join productos T4 on T1.id_producto=T4.id
inner join unidades T5 on T4.id_unidad=T5.id
inner Join farmacias F On F.id=T1.id_farmacia 
inner Join establecimientos E On E.id=F.id_establecimiento  
Where T1.estado=1 and T2.estado=1 and 1=1 ";
        if ($nombre_establecimiento != "")
            $cad .= " And E.nombre='" . $nombre_establecimiento . "'";
        $cad .= " and fecha_movimiento<='" . $fecha_fin . "'
            and T2.cantidad > 0 ";

        $cad .= " group by id_producto, codigo_producto, nombre_producto,T5.abreviatura,E.id,E.nombre, T1.id_producto, T4.id_tipo
Order by nombre_producto asc";


        $data = DB::select($cad);
        return $data;
    }


    public function ShowTotalKardexByEstablecimiento($nombre_establecimiento = "", $fecha_inicio, $fecha_fin)
    {
        $cad = "Select * From sp_show_total_kardex_by_establecimiento ('" . $nombre_establecimiento . "','" . $fecha_inicio . "','" . $fecha_fin . "');";

        $data = DB::select($cad);
        return $data;
    }

    public function ShowSaldoInicial($id_producto, $id_farmacia, $fecha_fin)
    {
        $cad = "
		select 
		coalesce(sum(KL.cantidad*TM.accion*KL.presentacion_comercial),0) as suma
		from kardexes
		left join kardex_lotes KL on kardexes.id=KL.id_kardex 
		inner join tipo_movimientos TM on kardexes.id_tipo_movimiento=TM.id
		Where kardexes.estado=1 and KL.estado=1 and id_producto =" . $id_producto . "
		and fecha_movimiento<='" . $fecha_fin . "'
		and id_farmacia=" . $id_farmacia;

        $data = DB::select($cad);
        return $data[0]->suma;
    }

    public function ShowCargaInicial($id_farmacia = "", $id_producto = "", $fecha_fin = "")
    {

        $cad = "select 
		cantidad 
		from kardexes T1
		Where id_tipo_movimiento= 1 and T1.estado=1 and 1=1 ";
        if ($id_farmacia != "")
            $cad .= " And T1.id_farmacia='" . $id_farmacia . "'";
        if ($fecha_fin != "")
            $cad .= " and T1.fecha_movimiento<='" . $fecha_fin . "'";
        if ($id_producto > 0)
            $cad .= " AND T1.id_producto=" . $id_producto;
        echo $cad;
        $data = DB::select($cad);

        return $data;
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
        $closeCursor = DB::select("CLOSE ref_cursor;");
        return $data;
    }

    public function GetBuscaMovimiento($id_producto, $id_farmacia, $fecha_carga, $registro_sanitario, $lote, $fecha_vencimiento, $precio_promedio)
    {

        $cad = "Select count(*) mov
From kardexes k
Left Join kardex_lotes l On k.id=l.id_kardex
Where k.estado=1 and l.estado=1 and k.id_tipo_movimiento>3 and k.id_tipo_movimiento<8 and k.id_farmacia='" . $id_farmacia . "' And k.id_producto=" . $id_producto . " and k.fecha_movimiento >='" . $fecha_carga . "' And l.registro_sanitario='" . $registro_sanitario . "' And l.lote='" . $lote . "' And l.precio_unitario=" . $precio_promedio . " ;";
        $data = DB::select($cad);
        return $data[0];
    }

    public function ShowTotalCantidadByIdProductoAndIdEstablecimiento2($id_producto, $id_establecimiento, $id_farmacia)
    {
        $p[] = $id_producto;
        $p[] = $id_establecimiento;
        $p[] = $id_farmacia;
        return $this->readFunctionPostgres('sp_get_stock_x_producto', $p);
    }

    public function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencimientoV3($id_producto, $id_farmacia, $fecha_fin)
    {

        $cad = "select count(*) from (
                select 
                productos.nombre prod_farmaceutico,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
                ,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
                ,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as cantidad 
                ,min(kardexes.created_at)created_at
                from kardexes
                inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
                inner join productos on kardexes.id_producto=productos.id 
                inner join unidades on productos.id_unidad=unidades.id
                inner join farmacias on farmacias.id = kardexes.id_farmacia 
                inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
                where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'";
        if ($id_producto != "") {
            $cad .= " and kardexes.id_producto ='" . $id_producto . "'";
        }
        $cad .= " And kardex_lotes.fecha_vencimiento < '" . $fecha_fin . "'";
        $cad .= " group by productos.id,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario,
                to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
                having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0
                order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc)
                as subquery";
        $data = DB::select($cad);
        return $data[0];
    }

    //TODO: Revisar funcion para bd replica
    public function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencimientoV2($id_producto, $id_farmacia, $fecha_fin)
    {
        $cad = "select count(*) from (
            select 
            productos.nombre prod_farmaceutico,productos.codigo codigo_producto,farmacias.id as id_farmacia,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario
            ,to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
            ,coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) as cantidad 
            ,min(kardexes.created_at)created_at
            from kardexes
            inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex
            inner join productos on kardexes.id_producto=productos.id 
            inner join unidades on productos.id_unidad=unidades.id
            inner join farmacias on farmacias.id = kardexes.id_farmacia 
            inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
            where kardexes.estado=1 and kardex_lotes.estado=1 and farmacias.id='" . $id_farmacia . "'";
        if ($id_producto != "") {
            $cad .= " and kardexes.id_producto ='" . $id_producto . "'";
        }
        $cad .= " And kardex_lotes.fecha_vencimiento < '" . $fecha_fin . "'";
        $cad .= " group by productos.id,farmacias.id,unidades.abreviatura,kardex_lotes.lote,kardex_lotes.registro_sanitario,
            to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy')
            having coalesce(sum(kardex_lotes.cantidad*tipo_movimientos.accion),0) > 0
            order by to_char(kardex_lotes.fecha_vencimiento,'mm-yyyy') asc)
            as subquery";

        $data = DB::connection('pgsql_reportes')->select($cad);
        return $data[0];
    }


    /**
     * 29jun2024
     * Copia de GetStockActualByIdEstablecimientoAndIdProducto
     * Cambio de funcion para probar tabla de stock historia kardex_points_2
     */
    public static function GetStockActualByIdEstablecimientoAndIdProducto_Prueba($id_establecimiento, $id_producto)
    {
        $cad = "Select * From sp_report_stock_actual_by_establicimiento_2(" . $id_establecimiento . "," . $id_producto . ");";
        $data = DB::select($cad);
        return $data;
    }
}
