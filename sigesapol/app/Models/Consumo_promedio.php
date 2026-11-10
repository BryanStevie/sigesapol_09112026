<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Consumo_promedio extends Model {

    public function listarProductoPorIdFarmacia($id_farmacia=0){
        $data = DB::select("Select * From sp_listar_productos_x_farmacia_cpm(".$id_farmacia.");");
        return $data;
    }
    
    public function ExisteProductoRegistrado($id_producto,$id_farmacia) {
        $data = Consumo_promedio::where('id_producto', '=', $id_producto)->where('id_farmacia', '=', $id_farmacia)->where('estado', '=', 1)->first();
        return isset($data) ? true : false;
    }

    public function BuscarProductoCP($id_consumo_promedio) {
        $cad = "select c.id, c.cpm, p.nombre from consumo_promedios c Inner Join productos p On c.id_producto = p.id Where c.estado=1 And c.id=".$id_consumo_promedio;
        $data = DB::select($cad);

        return $data[0];
    }
    
    public function ShowStockCpmByFarmacia($nombre_farmacia = "", $fecha_fin) {
        $cad = "Select * From sp_show_stock_cpm_by_farmacia ('".$nombre_farmacia."','".$fecha_fin."');";

        $data = DB::select($cad);
        return $data;
    }

    public function ProductosDesabastecidos($id_farmacia="", $fecha_fin){
        $cad ="Select count(*) total
                From kardexes k Inner Join kardex_lotes kl On k.id=kl.id_kardex 
                Inner Join tipo_movimientos tm On k.id_tipo_movimiento=tm.id 
                Inner Join productos p On k.id_producto=p.id 
                Inner Join tipo_productos tp on tp.id=p.id_tipo
                Where k.estado=1 and kl.estado=1 and k.id_farmacia=".$id_farmacia."and k.fecha_movimiento<='".$fecha_fin."'";
        $cad= "Group By k.id_producto 
             Having Coalesce(Sum(kl.cantidad*kl.presentacion_comercial*tm.accion),0)=0";
        $data = DB::select($cad);
        return $data[0]->total;
    
    }

    public function AlmacenHospital($id_farmacia=""){
   
        $cad ="Select count(*) alamcen from farmacias f2 where nombre like 'Almac%' and id_establecimiento<78 and id!=11 and id!=54 and id=".$id_farmacia;
        $data = DB::select($cad);
        return $data[0]->almacen;
    }

    public function ShowNuevoIngresoSB_old($id_farmacia,$fecha_ini,$fecha_fin){
            $cad = "select p.id, p.codigo, p.nombre, un.abreviatura, sum(cp.cantidad) as stock_cant
                    from carga_pecosas cp 
                    inner join guia_entradas ge on cp.id_guia_entrada = ge.id
                    inner join productos p on cp.id_producto = p.id
                    inner join unidades un on un.id=p.id_unidad                    
                    Where cp.id_farmacia=113  and coalesce(to_char(cp.created_at,'yyyy-mm-dd'))>='".$fecha_ini."' and coalesce(to_char(cp.created_at,'yyyy-mm-dd'))<='".$fecha_fin."' and cp.estado=1 and ge.estado = 2
                    group by p.id, un.abreviatura 
                    order By p.id desc ";                                
            
            $data = DB::select($cad);
            
        return $data;
    }

    public function ShowNuevoIngresoSB($id_farmacia,$fecha_ini,$fecha_fin){
        $fecha_inicial=$fecha_ini.' 00:00:00.000'; 
        $fecha_final=$fecha_fin.' 23:59:00.000'; 

            $cad = "select p.id, p.codigo, p.nombre, un.abreviatura, sum(cp.cantidad) as stock_cant
                    from kardex_lotes cp 
                    inner join kardexes ge on cp.id_kardex = ge.id
                    inner join productos p on ge.id_producto = p.id
                    inner join unidades un on un.id=p.id_unidad                     
                    Where ge.id_farmacia=113  and cp.created_at>='".$fecha_inicial."' and cp.created_at<='".$fecha_final."' and cp.estado=1 and ge.estado = 1 and ge.id_tipo_movimiento = 2 and ge.tipo_movimiento='IR'
                    group by p.id, un.abreviatura 
                    order By p.id desc ";                                
            
            $data = DB::select($cad);
            
        return $data;
    }
    
    public function ShowStockByFarmaciaNivel($fecha_inicio="", $fecha_fin="", $nivel="") {
        
        $cad = "Select * From sp_show_total_kardex_by_nivel ('".$fecha_inicio."','".$fecha_fin."','".$nivel."');";
        //dd($cad);
        $data = DB::select($cad);
        return $data;
    }

    public function ShowCountTotalNivel($nivel, $fecha_fin) {

        $cad = "
                select count(*) total from(
                select 
                productos.id id_producto,productos.nombre nombre_producto,unidades.abreviatura
                from kardexes 
                inner join kardex_lotes on kardexes.id=kardex_lotes.id_kardex 
                inner join tipo_movimientos on kardexes.id_tipo_movimiento=tipo_movimientos.id
                inner join productos on kardexes.id_producto=productos.id
                inner join unidades on productos.id_unidad=unidades.id
                Inner Join farmacias f On kardexes.id_farmacia=f.id 
                Inner Join establecimientos e On f.id_establecimiento=e.id
                Where kardexes.estado=1 and kardex_lotes.estado=1 
                and fecha_movimiento<='" . $fecha_fin . "'
                and kardex_lotes.cantidad > 0";
                        if ($nivel != 0):
                            if ($nivel != 3):
                                $cad .= " AND e.nivel=" . $nivel;
                            else:
                                $cad .= " AND e.nivel=" . $nivel . " AND e.id=76 ";
                            endif;
                        endif;
                        $cad .= "group by productos.id,unidades.abreviatura
                )R";

        $data = DB::select($cad);
        return $data[0]->total;
    }
}
