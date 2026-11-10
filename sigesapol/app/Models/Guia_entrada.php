<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Carga_pecosa;

class Guia_entrada extends Model
{
    //    
    function getNroNota($id_farmacia){
        $year = date('Y');
		/*
        $cad = "select LPAD(((count(*)+1)::text),5,'0')numero 
from guia_entradas T1
where T1.id_farmacia = ".$id_farmacia." and id_requerimiento is null and concepto!=1 and concepto!=7
and ano_nota = extract(year from now())::text";
		*/
		$cad = "select LPAD(((count(*)+1)::text),5,'0')numero 
from guia_entradas T1
where T1.id_farmacia = ".$id_farmacia." and id_requerimiento is null
and ano_nota = extract(year from now())::text";

        $data = DB::select($cad);
            return $data[0]->numero;        
    }

    public function ShowNotaEntradaByIdfarmacia($id_farmacia){
			/*
            $cad = "Select ge.*, p.ruc_proveedor, c.id as concepto, c.descripcion_conceptos, t.descripcion_tipo_concepto,p.nombre_proveedor,f.nombre nom_origen, d.nombre_dependencia, e.nombre, pr.nombre_proveedor as proveedores 
                    From guia_entradas ge
                    LEFT Join farmacias f On ge.id_farmacia = f.id
                    left Join proveedores p On ge.proveedor_id = p.id
					left Join proveedores pr On ge.lugar_origen = pr.id
                    left Join conceptos c On ge.concepto = c.id
                    left join tipo_conceptos t On ge.tipo_compra = t.id
                    left join dependencias d On ge.lugar_origen = d.id 
					left join establecimientos e On ge.lugar_origen = e.id 
                    Where ge.id_farmacia=".$id_farmacia." and ge.estado!=0 and id_requerimiento is null order By ge.id desc";
			*/
			$cad = "Select ge.*, p.ruc_proveedor, c.id as concepto, c.descripcion_conceptos, t.descripcion_tipo_concepto,p.nombre_proveedor,f.nombre nom_origen, d.nombre_dependencia, e.nombre, pr.nombre_proveedor as proveedores
                    From guia_entradas ge
                    LEFT Join farmacias f On ge.id_farmacia = f.id
                    left Join proveedores p On ge.proveedor_id = p.id
                    left Join proveedores pr On ge.lugar_origen = pr.id
                    left Join conceptos c On ge.concepto = c.id
                    left join tipo_conceptos t On ge.tipo_compra = t.id
                    left join dependencias d On ge.lugar_origen = d.id
                    left join establecimientos e On ge.lugar_origen = e.id
                    Where ge.id_farmacia=".$id_farmacia." and id_requerimiento is null order By ge.id desc";
					
            $data = DB::select($cad);
        return $data;
    }

    public function ShowAllNotaEntradaByIdfarmacia($id_farmacia,$tipo){
            
            $cad = "Select ge.*, mt.nombre as motivo,f.nombre nom_origen, e.nombre as nombre_est, d.nombre_dependencia, c.nombre as consultorio
                    From guia_entradas ge
                    LEFT Join farmacias f On ge.id_farmacia = f.id
                    left join motivo_transferencias mt On ge.motivo = mt.id
                    left join consultorios c On ge.lugar_origen = c.id
                    left join dependencias d On ge.lugar_origen = d.id
                    left join establecimientos e On ge.lugar_origen = e.id
                    Where ge.id_farmacia=".$id_farmacia."  and id_requerimiento is null order By ge.id desc";
            $data = DB::select($cad);
        return $data;
    }

    public function ShowAllNotaEntradaByIdAlmacen($id_farmacia, $fechaDesde, $fechaHasta){
            
            $cad = "Select ge.*, mt.nombre as motivo,f.nombre nom_origen, e.nombre as nombre_est, d.nombre_dependencia, c.nombre as consultorio, p.nombre_proveedor as proveedores, co.descripcion_conceptos, p.ruc_proveedor, p2.nombre_proveedor as proveedores2
                    From guia_entradas ge
                    LEFT Join farmacias f On ge.id_farmacia = f.id
                    left join motivo_transferencias mt On ge.motivo = mt.id
                    left join consultorios c On ge.lugar_origen = c.id
                    left join dependencias d On ge.lugar_origen = d.id
                    left join establecimientos e On ge.lugar_origen = e.id
                    left join proveedores p On p.id = ge.proveedor_id
                    left join proveedores p2 On p2.id = ge.lugar_origen
                    left join conceptos co On co.id = ge.concepto
                    Where ge.id_farmacia=".$id_farmacia." and ge.fecha_entrada >= '".$fechaDesde."' and ge.fecha_entrada <= '".$fechaHasta."' and ge.estado!=0 and id_requerimiento is null order By ge.id desc";
            $data = DB::select($cad);
        return $data;
    }

    
    public function ShowPecosasByIdfarmacia($id_farmacia, $ano){
            $cad = "Select ge.*, contador nro_reque, extract(year from r.created_at) anio_reque, f.nombre nom_origen From guia_entradas ge
                    Left Join requerimientos r On ge.id_requerimiento = r.id
                    Left Join farmacias f On r.id_destino = f.id
                    Where ge.id_farmacia=".$id_farmacia." and extract(year from ge.created_at) <='".$ano."'  and id_requerimiento is Not null order By ge.id desc";
            $data = DB::select($cad);
        return $data;
    }
    
    public function GetPecosaByIdPecosa($id){
        //$data = Guia_entrada::where('id', '=', $id)->first();
            $cad = "Select ge.*, u.name,u.paterno,u.materno,contador nro_reque, extract(year from r.created_at) anio_reque, f1.nombre nom_origen, f2.nombre nom_destino From guia_entradas ge
                    Left Join requerimientos r On ge.id_requerimiento = r.id
                    Left Join farmacias f1 On r.id_destino = f1.id
                    left join farmacias f2 on r.id_farmacia = f2.id
                    Left Join users u On r.id_user_created = u.id
                    Where ge.id=".$id."";
            $data = DB::select($cad);
        return $data[0];
    }

    public function GetPecosaByIdNotaEntrada($id){
            $cad = "Select ge.*, ge.updated_at ingreso, u.name,u.paterno,u.materno, f.nombre nom_origen, c.nombre as consultorio, e.nombre as nombre_est, d.nombre_dependencia, mt.nombre as motivo_nombre
                    From guia_entradas ge
                    LEFT Join farmacias f On ge.id_farmacia = f.id
                    left join motivo_transferencias mt On ge.motivo = mt.id
                    left join consultorios c On ge.lugar_origen = c.id
                    left join dependencias d On ge.lugar_origen = d.id
                    left join establecimientos e On ge.lugar_origen = e.id
                    Left Join users u On ge.id_user = u.id
                    Where ge.id=".$id." order By ge.id desc";
            $data = DB::select($cad);
        return $data[0];
    }

    public function GetPecosaByIdNotaEntradaAlmacen($id){
            $cad = "Select ge.*, ge.updated_at ingreso, u.name,u.paterno,u.materno, f1.nombre nom_origen, p.ruc_proveedor, c.descripcion_conceptos, t.descripcion_tipo_concepto,p.nombre_proveedor, d.descripcion_tipo_documento, descripcion_tipo_proceso
                    From guia_entradas ge
                    Left Join farmacias f1 On ge.id_farmacia= f1.id
                    left Join proveedores p On ge.proveedor_id = p.id
                    left Join conceptos c On ge.concepto = c.id
                    left join tipo_conceptos t On ge.tipo_compra = t.id 
                    left join tipo_procesos tp On ge.tipo_proceso = tp.id  
                    left join tipo_documentos d On ge.tipo_doc = d.id                    
                    Left Join users u On ge.id_user = u.id
                    Where ge.id=".$id." order By ge.id desc";
            $data = DB::select($cad);
        return $data[0];
    }
    
    public function GetPecosaByfromKardexs($nombre_farmacia, $numero_movimiento){
        $data = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)
                ->where('numero_movimiento', '=', $numero_movimiento)
                ->where('tipo_movimiento', '=', 'IR')
                //->orderBy('id','desc')
                ->get();
        return $data;
    }
    
    public function ShowProductoByPecosa($id_pecosa){
        $productos= DB::table('carga_pecosas')
                ->join('productos','carga_pecosas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('carga_pecosas.id_guia_entrada','=',$id_pecosa)
                ->where('carga_pecosas.estado','>',0)
                ->select('carga_pecosas.*','productos.codigo','productos.nombre','productos.presentacion_comercial as presentacion',
                        'unidades.abreviatura as unidad')
                ->orderBy('carga_pecosas.id','desc')
                ->get();                
        return $productos;
    }

    public function DuplicadoSiaf($siaf, $numero) {
        $datos=  Guia_entrada::where('siaf',$siaf)->where('ano_nota',$numero)->where('estado', '=',1)->first();
        return $datos;
    }

    public function ExisteSiaf($siaf, $numero_guia, $id_farmacia,$id=0) {
        $year = date('Y');
        if($id!=0){
            $data = Guia_entrada::where('nro_exp_siaf', '=', $siaf)->where('concepto', '=',1)->where('estado', '=',1)->where('id_farmacia', '=', $id_farmacia)->where('numero', '=', $numero_guia)->where('ano_nota', '=', $year)->where('id','<>',$id)->count();        
        }
        else
        {
            $data = Guia_entrada::where('nro_exp_siaf', '=', $siaf)->where('concepto', '=',1)->where('numero', '=', $numero_guia)->where('estado', '=',1)->where('id_farmacia', '=', $id_farmacia)->where('ano_nota', '=', $year)->count();           
        }
        return $data;
    }

    //public function ExisteNumeroEntrada($numero_entrada,$id_farmacia) {
	public function ExisteNumeroEntrada($numero_entrada,$id_farmacia,$concepto=0) {
        $year = date('Y');
        $nota_entrada = str_pad($numero_entrada, 7, "0", STR_PAD_LEFT);
        
        $cad = "select * from guia_entradas where  id_farmacia=".$id_farmacia." and estado!=0 and nota_entrada='".$nota_entrada."' and ano_nota='".$year."' ";
        if($concepto==0){
            $cad .= "  and concepto=".$concepto."";
        }
        $cad .= "  order by id desc ";
        
        $data = DB::select($cad);
        
        return $data;        
    }

    public function AllRegistradores($id_farmacia) {
        $cad = "Select ge.id_user, upper(u.name) nombre, upper(u.paterno) paterno, upper(u.materno) materno From guia_entradas ge
Inner Join users u On ge.id_user = u.id
Where ge.id_farmacia =" . $id_farmacia . "
Group By ge.id_user, u.name, u.paterno, u.materno";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllIngresos($id_farmacia, $fechaDesde, $fechaHasta, $l_origen=0, $num_doc="",$num_siaf="",$reg_sanitario="",$lote="", $idconcepto=0,$id_producto=0,$id_tipo_producto=0,$id_proveedor=0,$id_tipo_proceso=0){
        /*
        $cad="select c.descripcion_conceptos conceptos, ge.nota_entrada num_concepto, ge.nro_exp_siaf siaf, ge.fecha_entrada, pv.nombre_proveedor Proveedor, ge.nro_entrega,ge.total_entrega,
        tp.abreviatura_tipo_proceso abre_proceso, ge.nro_proceso, tpr.nombre tipo_producto, p.id id_producto, p.codigo cod_producto,
        p.nombre nombre_producto, cp.registro_sanitario, cp.lote, cp.fecha_vencimiento, cp.cantidad, cp.precio_promedio p_unitario, tpr.cuenta,
        tpr.clas_gasto, cp.factura, cp.guia_entrada,d.nombre_dependencia lugar_origen, f.nombre lugar_destino, u.name as nombre_usuario,u.paterno paterno_usuario, u.materno materno_usuario from guia_entradas ge
        inner join conceptos c on c.id= cast (ge.concepto as int) 
        inner join carga_pecosas cp on ge.id=cp.id_guia_entrada
        inner join productos p on p.id=cp.id_producto
        inner join tipo_productos tpr on tpr.id=p.id_tipo
        inner join proveedores pv on pv.id=ge.proveedor_id
        left join tipo_procesos tp on tp.id=ge.tipo_proceso
        inner join farmacias f on f.id=ge.id_farmacia
        inner join users u on u.id=ge.id_user
        inner join dependencias d on d.id=ge.lugar_origen
        where ge.id_farmacia= ".$id_farmacia." and ge.fecha_entrada >= '".$fechaDesde."' and ge.fecha_entrada <= '".$fechaHasta."'";
		*/
		$cad="select c.descripcion_conceptos conceptos, ge.nota_entrada num_concepto, ge.nro_exp_siaf siaf, ge.fecha_entrada, ge.created_at, pv.nombre_proveedor Proveedor, ge.nro_entrega,ge.total_entrega,
        tp.abreviatura_tipo_proceso abre_proceso, ge.nro_proceso, tpr.nombre tipo_producto, p.id id_producto, p.codigo cod_producto,
        p.nombre nombre_producto, cp.registro_sanitario, cp.lote, cp.fecha_vencimiento, cp.cantidad, cp.precio_promedio p_unitario, tpr.cuenta,
        tpr.clas_gasto, cp.factura, cp.guia_entrada,d.nombre_dependencia lugar_origen_dep, f.nombre lugar_destino, u.name as nombre_usuario,u.paterno paterno_usuario, u.materno materno_usuario, c.id id_concepto, p.presentacion_comercial pc, cp.presentacion_comercial, pv2.nombre_proveedor lugar_origen_prov,  est.nombre lugar_origen_ipress, c.id tipo_concepto
        from guia_entradas ge
        left join conceptos c on c.id=ge.concepto 
        left join carga_pecosas cp on ge.id=cp.id_guia_entrada
        left join productos p on p.id=cp.id_producto
        left join tipo_productos tpr on tpr.id=p.id_tipo
        left join proveedores pv on pv.id=ge.proveedor_id
        left join tipo_procesos tp on tp.id=ge.tipo_proceso
        inner join farmacias f on f.id=ge.id_farmacia
        inner join users u on u.id=ge.id_user
        left join dependencias d on d.id=ge.lugar_origen
        left join proveedores pv2 on pv2.id=ge.lugar_origen
        left join establecimientos est on est.id=ge.lugar_origen
        where ge.estado!=0 and ge.id_farmacia= ".$id_farmacia." and ge.fecha_entrada >= '".$fechaDesde."' and ge.fecha_entrada <= '".$fechaHasta."'";
		
        if ($l_origen):
            $cad .= " and d.id = '" . $l_origen . "'";
        endif;

        if ($num_doc):
            $cad .= " and ge.nota_entrada = '" . $num_doc . "'";
        endif;

        if ($num_siaf):
            $cad .= " and ge.nro_exp_siaf = '" . $num_siaf . "'";
        endif;

        if ($reg_sanitario):
            $cad .= " and cp.registro_sanitario = '" . $reg_sanitario . "'";
        endif;

        if ($lote):
            $cad .= " and cp.lote = '" . $lote . "'";
        endif;


        //if ($idregistrador != "")
            //$cad .= " and ge.id_user = " . $idregistrador . "";
		
		if ($idconcepto != "")
            $cad .= " and ge.concepto = " . $idconcepto . "";
			
        if ($id_producto != "")
            $cad .= " and cp.id_producto = " . $id_producto . "";

        if ($id_tipo_producto != "")
            $cad .= " and tpr.id = " . $id_tipo_producto . "";

        if ($id_proveedor != "")
            $cad .= " and pv.id = " . $id_proveedor . "";

        if ($id_tipo_proceso != "")
            $cad .= " and tp.id = " . $id_tipo_proceso . "";
    
        $cad .= " and cp.estado=1";         
        $cad .= " order by ge.fecha_entrada desc";
        
        $data = DB::select($cad);
        //dd($cad);
        return $data;
    }


    public function ShowAllIngresosFarmacia($id_farmacia, $fechaDesde, $fechaHasta, $num_doc="",$reg_sanitario="",$lote="", $id_producto=0,$id_tipo_producto=0){
        $cad="select k.numero_movimiento, k.tipo_movimiento, k.id_tipo_movimiento,k.fecha_movimiento, 
        k.id_producto, k.codigo_producto,
        k.nombre_producto, kl.registro_sanitario, kl.lote, kl.fecha_vencimiento, kl.cantidad, kl.precio_unitario p_unitario, 
        d.nombre_dependencia lugar_origen_dep, f.nombre lugar_destino, u.name as nombre_usuario,u.paterno paterno_usuario, u.materno materno_usuario,  
        p.presentacion_comercial pc, pv2.nombre_proveedor lugar_origen_prov, f2.nombre as nombre_farmacia, c.nombre as consultorio,  tpr.nombre tipo_producto,
        p.presentacion_comercial pc, kl.presentacion_comercial
        from kardexes k
        inner join kardex_lotes kl on kl.id_kardex = k.id
        left join productos p on p.id=k.id_producto
        left join tipo_productos tpr on tpr.id=p.id_tipo
        left join proveedores pv on pv.id=k.id_origen 
        inner join farmacias f on f.id=k.id_farmacia
        inner join users u on u.id=k.id_user_created 
        left join dependencias d on d.id=k.id_origen
        left join proveedores pv2 on pv2.id=k.id_origen
        left join farmacias f2 on f2.id=k.id_origen
        left join consultorios c on c.id=k.id_origen
        where k.estado!=0 and kl.estado!=0 and k.id_farmacia= ".$id_farmacia." and ( k.id_tipo_movimiento = 2 OR k.id_tipo_movimiento = 9) and k.fecha_movimiento >= '".$fechaDesde."' and k.fecha_movimiento <= '".$fechaHasta."'";
        
        
        if ($num_doc):
            $cad .= " and ge.nota_entrada = '" . $num_doc . "'";
        endif;

        if ($reg_sanitario):
            $cad .= " and kl.registro_sanitario = '" . $reg_sanitario . "'";
        endif;

        if ($lote):
            $cad .= " and kl.lote = '" . $lote . "'";
        endif;

        if ($id_producto != "")
            $cad .= " and k.id_producto = " . $id_producto . "";

        if ($id_tipo_producto != "")
            $cad .= " and tpr.id = " . $id_tipo_producto . "";

        
        $cad .= " order by k.fecha_movimiento desc";
        
        $data = DB::select($cad);
        
        return $data;
    }

/*
    public function GetCountRecetasByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $medicamento = "", $nro_orden = "", $iddispensador = "") {

        $cad = "select count(1) cantidad
from receta_vales
where receta_vales.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta = '" . $nro_receta . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($iddispensador != "")
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_expedicion >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_expedicion <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=1";

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }
*/
	
	public function ShowAllMovimientosProductos($id_farmacia,$fecha_inicio, $fecha_fin, $id_producto){
        /*
        $cad="select c.descripcion_conceptos conceptos, ge.numero,ge.nota_entrada, ge.fecha_entrada, p.id id_producto, p.codigo cod_producto, p.nombre nombre_producto,   cp.cantidad, cp.precio_promedio p_unitario, cp.factura, cp.guia_entrada,ge.total_entrega,cp.total_cantidad, pv.nombre_proveedor proveedor, ge.fecha_doc_recepcion, cp.precio_compra , cp.presentacion_comercial
            from guia_entradas ge            
            inner join conceptos c on c.id= cast (ge.concepto as int) 
            inner join carga_pecosas cp on ge.id=cp.id_guia_entrada
            inner join productos p on p.id=cp.id_producto      
            left join proveedores pv on pv.id=ge.proveedor_id     
            where ge.id_farmacia= ".$id_farmacia." and ge.concepto!=1 and ge.fecha_entrada>= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.id_producto = ".$id_producto." and cp.estado=1 order by ge.fecha_entrada desc
            ";
		*/
		/*
		$cad="select c.descripcion_conceptos conceptos, ge.numero,ge.nota_entrada, ge.fecha_entrada, p.id id_producto, p.codigo cod_producto, p.nombre nombre_producto,   cp.cantidad, cp.precio_promedio p_unitario, cp.factura, cp.guia_entrada,ge.total_entrega,cp.total_cantidad, pv.nombre_proveedor proveedor, ge.fecha_doc_recepcion, cp.precio_compra , cp.presentacion_comercial, p.presentacion_comercial pc, cp.lote, cp.registro_sanitario, cp.fecha_vencimiento, pv2.nombre_proveedor lugar_origen_prov,  est.nombre lugar_origen_ipress, c.id tipo_concepto
            from guia_entradas ge            
            inner join conceptos c on c.id= cast (ge.concepto as int) 
            inner join carga_pecosas cp on ge.id=cp.id_guia_entrada
            inner join productos p on p.id=cp.id_producto      
            left join proveedores pv on pv.id=ge.proveedor_id   
            left join dependencias d on d.id=ge.lugar_origen
            left join proveedores pv2 on pv2.id=ge.lugar_origen
            left join establecimientos est on est.id=ge.lugar_origen  
            where ge.id_farmacia= ".$id_farmacia." and ge.concepto!=1 and ge.fecha_entrada>= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.id_producto = ".$id_producto." and cp.estado=1 and ge.estado!=0 order by ge.fecha_entrada desc
            ";
		*/
		$cad="select c.descripcion_conceptos conceptos, ge.numero,ge.nota_entrada, ge.fecha_entrada, p.id id_producto, p.codigo cod_producto, p.nombre nombre_producto,   cp.cantidad, cp.precio_promedio p_unitario, cp.factura, cp.guia_entrada,ge.total_entrega,cp.total_cantidad, pv.nombre_proveedor proveedor, ge.fecha_doc_recepcion, cp.precio_compra , cp.presentacion_comercial, p.presentacion_comercial pc, cp.lote, cp.registro_sanitario, cp.fecha_vencimiento, pv2.nombre_proveedor lugar_origen_prov,  est.nombre lugar_origen_ipress, c.id tipo_concepto, d.nombre_dependencia lugar_origen_dep 
            from guia_entradas ge            
            inner join conceptos c on c.id=ge.concepto 
            inner join carga_pecosas cp on ge.id=cp.id_guia_entrada
            inner join productos p on p.id=cp.id_producto      
            left join proveedores pv on pv.id=ge.proveedor_id   
            left join dependencias d on d.id=ge.lugar_origen
            left join proveedores pv2 on pv2.id=ge.lugar_origen
            left join establecimientos est on est.id=ge.lugar_origen  
            where ge.id_farmacia= ".$id_farmacia." and ge.concepto!=1 and ge.fecha_entrada>= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.id_producto = ".$id_producto." and cp.estado=1 and ge.estado!=0 order by ge.fecha_entrada desc
            ";
            
            $data = DB::select($cad);
            
            return $data;
    }

    public function ShowAllMovimientosProductosFarmacia($id_farmacia,$fecha_inicio, $fecha_fin, $id_producto){
        $cad="select ge.numero,ge.nota_entrada, ge.fecha_entrada, ge.fecha_doc_recepcion,  ge.total_entrega, ge.tipo, mt.nombre motivo,
                p.id id_producto, p.codigo cod_producto, p.nombre nombre_producto, p.presentacion_comercial pc,
                cp.cantidad, cp.precio_promedio p_unitario, cp.factura, cp.guia_entrada,cp.total_cantidad,cp.precio_compra , cp.presentacion_comercial,cp.lote, cp.registro_sanitario, cp.fecha_vencimiento,
                est.nombre lugar_origen_ipress, 
                d.nombre_dependencia lugar_origen_dep ,
                c.nombre lugar_origen_consultorio 
                from guia_entradas ge 
                inner join carga_pecosas cp on ge.id=cp.id_guia_entrada 
                inner join productos p on p.id=cp.id_producto 
                left join dependencias d on d.id=ge.lugar_origen 
                left join consultorios c on c.id=ge.lugar_origen 
                left join establecimientos est on est.id=ge.lugar_origen 
                left join motivo_transferencias mt On ge.motivo = mt.id
            where ge.id_farmacia= ".$id_farmacia." and ge.fecha_entrada>= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.id_producto = ".$id_producto." and cp.estado=1 and ge.estado!=0 order by ge.fecha_entrada desc
            ";
            //echo $cad;
            $data = DB::select($cad);
            
            return $data;
    }


	public function ShowAllIngresosProductos($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto){
        /*
        $cad="select c.descripcion_conceptos conceptos, ge.nota_entrada,  ge.fecha_entrada, p.id id_producto, p.codigo cod_producto, p.nombre nombre_producto,   cp.cantidad, cp.precio_promedio, cp.guia_entrada, pv.nombre_proveedor proveedor, ge.fecha_doc_recepcion, cp.precio_compra, cp.registro_sanitario, cp.lote, ge.numero , cp.presentacion_comercial
            from guia_entradas ge            
            inner join conceptos c on c.id= cast (ge.concepto as int) 
            inner join carga_pecosas cp on ge.id=cp.id_guia_entrada
            inner join productos p on p.id=cp.id_producto      
            left join proveedores pv on pv.id=ge.proveedor_id     
            where ge.id_farmacia= ".$id_farmacia." and ge.concepto=1 and ge.fecha_entrada >= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.id_producto = ".$id_producto." and cp.estado=1 order by ge.fecha_entrada desc";

            $data = DB::select($cad);
            
            return $data;
		*/
		
		$cad="select ge.fecha_entrada fecha_ingreso, ge.nro_entrega, ge.nota_entrada num_orden, ge.fecha_doc_recepcion fecha_orden,ge.numero,
   cp.fecha_carga fecha_internamiento, pv.nombre_proveedor proveedor, cp.cantidad cantidad_internada, cp.precio_compra , cp.registro_sanitario, cp.lote, cp.cantidad, cp.precio_promedio, cp.guia_entrada,cp.presentacion_comercial, p.presentacion_comercial pc, p.nombre nombre_producto, p.codigo cod_producto, cp.fecha_vencimiento, ge.nro_entrega 
            from carga_pecosas cp
            inner join productos p on p.id=cp.id_producto    
            inner join guia_entradas ge on cp.id_guia_entrada=ge.id 
            left join proveedores pv on pv.id=ge.proveedor_id
   where cp.id_farmacia=".$id_farmacia."  and ge.concepto=1 and cp.id_producto=".$id_producto." and ge.fecha_entrada>= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.estado=1 and ge.estado!=0 ";
            $data = DB::select($cad);            
            return $data;
			
    }


	public function ShowAllSalidasProductos($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto){
        /*
        $cad="select t.nro_acta, t.fecha fecha_salida, tpr.nombre tipo_producto, p.id id_producto, p.codigo cod_producto,t.nombre_jefe_farmacia_local,
        p.nombre nombre_producto, trpr.registro_sanitario, trpr.lote, trpr.fecha_vencimiento, trpr.cantidad, trpr.precio_unitario p_unitario, d.nombre_dependencia lugar_destino_dependencia, f.nombre lugar_destino_farmacia, e.nombre lugar_destino_establecimiento, trpr.presentacion_comercial
        from transferencias t
        inner join transferencia_productos trpr on t.id=trpr.id_transferencia        
        inner join productos p on p.id=trpr.id_producto
        inner join tipo_productos tpr on tpr.id=p.id_tipo
        left join farmacias f on f.id=t.id_farmacia_destino
        left join establecimientos e on t.id_ipress=e.id
        left join dependencias d on t.id_farmacia_destino=d.id
        where t.estado>=3 and  t.id_farmacia= ".$id_farmacia." and trpr.id_producto = ".$id_producto." and trpr.estado=1 and t.fecha >= '".$fecha_inicio."' and t.fecha<='".$fecha_fin."'order by t.fecha desc";
		*/
		/*
		$cad="select t.nro_acta, t.fecha fecha_salida, tpr.nombre tipo_producto, p.id id_producto, p.codigo cod_producto,t.nombre_jefe_farmacia_local,
        p.nombre nombre_producto, trpr.registro_sanitario, trpr.lote, trpr.fecha_vencimiento, trpr.cantidad, trpr.precio_unitario p_unitario, d.nombre_dependencia lugar_destino_dependencia, f.nombre lugar_destino_farmacia, e.nombre lugar_destino_establecimiento, trpr.presentacion_comercial , p.presentacion_comercial pc
        from transferencias t
        inner join transferencia_productos trpr on t.id=trpr.id_transferencia        
        inner join productos p on p.id=trpr.id_producto
        inner join tipo_productos tpr on tpr.id=p.id_tipo
        left join farmacias f on f.id=t.id_farmacia_destino
        left join establecimientos e on t.id_ipress=e.id
        left join dependencias d on t.id_farmacia_destino=d.id
        where t.estado>=3 and  t.id_farmacia= ".$id_farmacia." and trpr.id_producto = ".$id_producto." and trpr.estado=1 and t.fecha >= '".$fecha_inicio."' and t.fecha<='".$fecha_fin."'order by t.fecha desc";
		*/
		$cad="select t.nro_acta, t.fecha fecha_salida, tpr.nombre tipo_producto, p.id id_producto, p.codigo cod_producto,t.nombre_jefe_farmacia_local,
        p.nombre nombre_producto, trpr.registro_sanitario, trpr.lote, trpr.fecha_vencimiento, trpr.cantidad, trpr.precio_unitario p_unitario, d.nombre_dependencia lugar_destino_dependencia, f.nombre lugar_destino_farmacia, e.nombre lugar_destino_establecimiento, trpr.presentacion_comercial , p.presentacion_comercial pc
        from transferencias t
        inner join transferencia_productos trpr on t.id=trpr.id_transferencia        
        inner join productos p on p.id=trpr.id_producto
        inner join tipo_productos tpr on tpr.id=p.id_tipo
        left join farmacias f on f.id=t.id_farmacia_destino
        left join establecimientos e on t.id_ipress=e.id
        left join dependencias d on t.id_farmacia_destino=d.id
        where t.estado!=0 and  t.id_farmacia= ".$id_farmacia." and trpr.id_producto = ".$id_producto." and trpr.estado=1 and t.fecha >= '".$fecha_inicio."' and t.fecha<='".$fecha_fin."'order by t.fecha desc";
        $data = DB::select($cad);
        //dd($cad);
        return $data;
    }

    public function ShowAllSalidasProductosFarmacia($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto){
        $cad="select t.nro_acta, t.fecha fecha_salida, t.nombre_jefe_farmacia_local, t.tipo,  
                tpr.nombre tipo_producto,trpr.presentacion_comercial , trpr.registro_sanitario, trpr.lote, trpr.fecha_vencimiento, trpr.cantidad, trpr.precio_unitario p_unitario, 
                d.nombre_dependencia lugar_destino_dependencia, 
                f.nombre lugar_destino_farmacia, 
                e.nombre lugar_destino_establecimiento, 
                c.nombre consultorio, 
                p.nombre nombre_producto, p.id id_producto, p.codigo cod_producto,p.presentacion_comercial pc 
                from transferencias t 
                inner join transferencia_productos trpr on t.id=trpr.id_transferencia 
                inner join productos p on p.id=trpr.id_producto 
                inner join tipo_productos tpr on tpr.id=p.id_tipo 
                left join farmacias f on f.id=t.id_farmacia_destino 
                left join establecimientos e on t.id_ipress=e.id 
                left join dependencias d on t.id_farmacia_destino=d.id 
                left join consultorios c on t.id_consultorio =c.id 
        where t.estado!=0 and  t.id_farmacia= ".$id_farmacia." and trpr.id_producto = ".$id_producto." and trpr.estado=1 and t.fecha >= '".$fecha_inicio."' and t.fecha<='".$fecha_fin."'order by t.fecha desc";
        //echo $cad;
        $data = DB::select($cad);
        
        return $data;
    }

    public function ShowAllIngresosTransferenciasProductosFarmacia($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto){
        $cad="select t.nro_acta, t.fecha fecha_salida, t.nombre_jefe_farmacia_local, t.tipo, 
                tpr.nombre tipo_producto,trpr.presentacion_comercial , trpr.registro_sanitario, trpr.lote, trpr.fecha_vencimiento, trpr.cantidad, trpr.precio_unitario p_unitario, mt.nombre motivo,
                f.nombre lugar_origen_farmacia, 
                e.nombre lugar_origen_establecimiento, 
                p.nombre nombre_producto, p.id id_producto, p.codigo cod_producto,p.presentacion_comercial pc 
                from transferencias t 
                inner join transferencia_productos trpr on t.id=trpr.id_transferencia 
                inner join productos p on p.id=trpr.id_producto 
                inner join tipo_productos tpr on tpr.id=p.id_tipo 
                left join farmacias f on f.id=t.id_farmacia
                left join establecimientos e on t.id_ipress=e.id 
                left join motivo_transferencias mt on mt.id = t.id_motitransferencia
        where t.estado=4 and  t.id_farmacia_destino= ".$id_farmacia." and trpr.id_producto = ".$id_producto." and trpr.estado=1 and t.fecha >= '".$fecha_inicio."' and t.fecha<='".$fecha_fin."'order by t.fecha desc";
        //echo $cad;
        $data = DB::select($cad);
        
        return $data;
    }

	function getNroNotaConcepto($id_farmacia){
        $year = date('Y');
        $cad = "select LPAD(((count(*)+1)::text),5,'0')numero 
from guia_entradas T1
where T1.id_farmacia = ".$id_farmacia." and concepto!=1 and id_requerimiento is null
and ano_nota = extract(year from now())::text";
        $data = DB::select($cad);
            return $data[0]->numero;        
    }

	public function ShowOnlyProductoByPecosa($id_pecosa,$id_producto){
        $productos= DB::table('carga_pecosas')
                ->join('productos','carga_pecosas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->where('carga_pecosas.id_guia_entrada','=',$id_pecosa)
                ->where('carga_pecosas.id_producto','=',$id_producto)
                ->where('carga_pecosas.estado','>',0)
                ->select('carga_pecosas.*','productos.codigo','productos.nombre','productos.presentacion_comercial as presentacion',
                        'unidades.abreviatura as unidad')
                ->orderBy('carga_pecosas.id','desc')
                ->get();                
        return $productos;
    }
	
	public function ShowAllMovimientosProductosCompra($id_farmacia,$fecha_inicio, $fecha_fin, $id_producto){
        /*
        $cad="select c.descripcion_conceptos conceptos, ge.nota_entrada, ge.nro_exp_siaf siaf, ge.fecha_entrada, ge.nro_entrega,ge.total_entrega, p.id id_producto, p.codigo cod_producto, p.nombre nombre_producto,   cp.cantidad, cp.precio_promedio p_unitario, cp.factura, cp.guia_entrada,ge.total_entrega,cp.total_cantidad, pv.nombre_proveedor proveedor, ge.fecha_doc_recepcion, cp.precio_compra , cp.presentacion_comercial
            from guia_entradas ge            
            inner join conceptos c on c.id= cast (ge.concepto as int) 
            inner join carga_pecosas cp on ge.id=cp.id_guia_entrada
            inner join productos p on p.id=cp.id_producto      
            left join proveedores pv on pv.id=ge.proveedor_id     
            where ge.id_farmacia= ".$id_farmacia." and ge.concepto=1 and ge.fecha_entrada>= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.id_producto = ".$id_producto." and cp.estado=1 
            group by c.descripcion_conceptos, ge.nota_entrada, ge.nro_exp_siaf, ge.fecha_entrada, ge.nro_entrega,ge.total_entrega, p.id, p.codigo , p.nombre, cp.cantidad, cp.precio_promedio, cp.factura, cp.guia_entrada,ge.total_entrega,cp.total_cantidad, pv.nombre_proveedor, ge.fecha_doc_recepcion, cp.precio_compra , cp.presentacion_comercial
            order by ge.fecha_entrada desc
            ";

            $data = DB::select($cad);
            
            return $data;
		*/
		/*
		$cad=" select ge.fecha_doc_recepcion fecha_orden, cp.fecha_carga fecha_registro, ge.nota_entrada num_orden, pv.nombre_proveedor proveedor,
   p.codigo cod_producto, p.nombre nombre_producto, cp.total_cantidad, cp.precio_compra,ge.nro_entrega,cp.presentacion_comercial,cp.precio_compra,ge.total_entrega, 
   (cp.total_cantidad * cp.precio_compra) total_orden, p.presentacion_comercial pc
            from carga_pecosas cp
            inner join guia_entradas ge on cp.id_guia_entrada=ge.id
            inner join productos p on p.id=cp.id_producto      
            left join proveedores pv on pv.id=ge.proveedor_id
   where cp.id_farmacia=".$id_farmacia." and ge.concepto=1 and ge.estado!=0 and cp.id_producto=".$id_producto." and ge.fecha_entrada>= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.estado=1
   group by ge.fecha_doc_recepcion, cp.fecha_carga, ge.nota_entrada, pv.nombre_proveedor,ge.nro_entrega,
   p.codigo, p.nombre, cp.total_cantidad, cp.precio_compra,cp.presentacion_comercial,ge.total_entrega, p.presentacion_comercial
   limit 1;
    ";
		*/
		
		if($id_farmacia>0 && $id_producto>0){
		
		$cad=" select ge.id, ge.fecha_doc_recepcion fecha_orden, cp.fecha_carga fecha_registro, ge.nota_entrada num_orden, pv.nombre_proveedor proveedor,
   p.codigo cod_producto, p.nombre nombre_producto, cp.total_cantidad, cp.precio_compra,cp.presentacion_comercial,cp.precio_compra,ge.total_entrega, 
   (cp.total_cantidad * cp.precio_compra) total_orden, p.presentacion_comercial pc
            from carga_pecosas cp
            inner join guia_entradas ge on cp.id_guia_entrada=ge.id
            inner join productos p on p.id=cp.id_producto      
            left join proveedores pv on pv.id=ge.proveedor_id
   where cp.id_farmacia=".$id_farmacia." and ge.concepto=1 and ge.estado!=0 and cp.id_producto=".$id_producto." and ge.fecha_entrada>= '".$fecha_inicio."' and ge.fecha_entrada<='".$fecha_fin."' and cp.estado=1
   group by ge.id, ge.fecha_doc_recepcion, cp.fecha_carga, ge.nota_entrada, pv.nombre_proveedor,
   p.codigo, p.nombre, cp.total_cantidad, cp.precio_compra,cp.presentacion_comercial,ge.total_entrega, p.presentacion_comercial";
	
    $data = DB::select($cad);            
            return $data;
		
		}
			
			
    }

    

}
