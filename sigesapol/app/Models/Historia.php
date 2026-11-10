<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class Historia extends Model
{
    //
    
    
    
    
    protected $fillable = ['dni', 'colegiatura','nombre','paterno','materno','especialidad','id_establecimiento','id_especializacion','tipo_contrato'];
    protected $delimiter  = ';';
    
    public function registrarMovimientoHistoria($id_establecimiento, $id_consultorio, $nro_movimiento, $fecha, $id_area, $id_turno, $id_tipo_citado, $id_tipo_doc, $observacion, $id_origen, $responsable_origen, $responsable_destino, $id_user, $ano_mov, $id) {     
        $cad = "Select sp_insert_movimiento_hc(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_establecimiento, $id_consultorio, $nro_movimiento, $fecha, $id_area, $id_turno, $id_tipo_citado, $id_tipo_doc, $observacion, $id_origen, $responsable_origen, $responsable_destino, $id_user, $ano_mov, $id));
        return $data[0]->sp_insert_movimiento_hc;
    }

    function getNroMovHC($id_ipress, $id_servicio){
        $year = date('Y');

        $cad = "select LPAD(((count(*)+1)::text),5,'0')nro_mov 
                        from movimiento_historias T1
                        where T1.id_ipress = ".$id_ipress." 
                        and T1.id_origen = ".$id_servicio." and ano_mov = extract(year from now())::text";
        $data = DB::select($cad);
        
        return $data[0]->nro_mov;
        
    }
    
    public function GetMovimientoHCById($id_nro) {
        $datos = DB::table('movimiento_historias')
                ->join('consultorios','movimiento_historias.id_consultorio','=','consultorios.id')
                ->select('movimiento_historias.*','consultorios.nombre as consultorio_destino')
                ->where('movimiento_historias.id', '=', $id_nro)
                ->first();
        return $datos;
    }

    public function GetTransferenciaById($id_transferencia) {
        $datos = DB::table('transferencias')
                ->join('farmacias','transferencias.id_farmacia','=','farmacias.id')
                ->select('transferencias.*','farmacias.nombre as farmacia_destino')
                ->where('transferencias.id', '=', $id_transferencia)
                ->first();
        return $datos;
    }
    
    
    
    
    function GetTransferenciaImprimirById($id){
        
        $cad = "select T1.* 
,T2.nombre as farmacia_origen
,T3.nombre as farmacia_destino
,T4.nombre as consultorio_destino
,mt.nombre motivo
from transferencias T1
left join farmacias T2 on T1.id_farmacia=T2.id
left join farmacias T3 on T1.id_farmacia_destino=T3.id
left join consultorios T4 on T1.id_consultorio=T4.id
left join motivo_transferencias mt On T1.id_motitransferencia = mt.id
where T1.id = ".$id;
        $data = DB::select($cad);
        
        return $data[0];
    }

    function GetTransferenciaEstablecimientoImprimirById($id){
        $cad = "select * 
,T2.nombre as farmacia_origen
,T3.nombre as farmacia_destino
,T4.nombre as consultorio_destino
,mt.nombre motivo
from transferencias T1
left join farmacias T2 on T1.id_farmacia=T2.id
left join establecimientos T3 on T1.id_ipress=T3.id
left join consultorios T4 on T1.id_consultorio=T4.id
inner join motivo_transferencias mt On T1.id_motitransferencia = mt.id
where T1.id = ".$id;
        $data = DB::select($cad);
        
        return $data[0];
    }

    function GetTransferenciaDependenciaImprimirById($id){
        $cad = "select T1.* 
,T2.nombre as farmacia_origen
,T3.nombre_dependencia as farmacia_destino
,T4.nombre as consultorio_destino
,mt.nombre motivo
from transferencias T1
left join farmacias T2 on T1.id_farmacia=T2.id
left join dependencias T3 on T1.id_farmacia_destino=T3.id
left join consultorios T4 on T1.id_consultorio=T4.id
inner join motivo_transferencias mt On T1.id_motitransferencia = mt.id
where T1.id = ".$id;
        $data = DB::select($cad);
        
        return $data[0];
    }

    
    function allTransferenciasById_farmacia($id_farmacia){
        /*
        $cad = "select T1.* 
,T2.nombre as farmacia_destino
,T3.nombre as establecimiento_destino
,T4.nombre as consultorio_destino
,T5.nombre_dependencia as dependencia_destino
,mt.nombre motivo
from transferencias T1
left join farmacias T2 on T1.id_farmacia_destino=T2.id
left join establecimientos T3 on T1.id_ipress=T3.id
left join consultorios T4 on T1.id_consultorio=T4.id
left join dependencias T5 on T1.id_farmacia_destino=T5.id
left join motivo_transferencias mt On T1.id_motitransferencia = mt.id
where T1.estado>=1 and  T1.id_farmacia = ".$id_farmacia;
        */
        $cad = "select T1.* 
,T2.nombre as farmacia_destino
,T3.nombre as establecimiento_destino
,T4.nombre as consultorio_destino
,T5.nombre_dependencia as dependencia_destino
,mt.nombre motivo
from transferencias T1
left join farmacias T2 on T1.id_farmacia_destino=T2.id
left join establecimientos T3 on T1.id_ipress=T3.id
left join consultorios T4 on T1.id_consultorio=T4.id
left join dependencias T5 on T1.id_farmacia_destino=T5.id
left join motivo_transferencias mt On T1.id_motitransferencia = mt.id
where T1.id_farmacia = ".$id_farmacia;
        $cad.= " order by T1.id desc ";
        $data = DB::select($cad);
        
        return $data;
    }
    
    /*
    public function allTransferenciasById_farmacia($id_farmacia) {
        $datos = DB::table('transferencias')
                ->join('motivo_transferencia','transferencias.id_motitransferencia','=','motivo_transferencia.id')
                ->where('transferencias.id_farmacia', '=', $id_farmacia)
                ->where('transferencias.estado','>=',1)
                ->select('transferencias.*', 'motivo_transferencia.nombre')
                ->orderBy('transferencias','desc')
                ->get();
        return $datos;
    }
    */
    public function allTransferenciasRecibidasById_farmacia($id_farmacia_destino) {
        $datos = DB::table('transferencias')
                ->join('farmacias','transferencias.id_farmacia','=','farmacias.id')
                ->where('transferencias.id_farmacia_destino', '=', $id_farmacia_destino)
                ->where('transferencias.estado','>=',3)
                ->where('transferencias.estado','<',5)
                ->where('transferencias.tipo','<>',4)
                ->select('transferencias.*','farmacias.nombre as farmacia_origen')
                ->orderBy('transferencias','desc')
                ->get();
        return $datos;
    }
        
    public function DuplicadoTransferencias($nro_acta, $id_farmacia,$id=0) {
        $anio = date("Y");
        $datos =  Transferencia::where('nro_acta',$nro_acta)->where('id_farmacia',$id_farmacia)->where('id', '!=',$id)->where('ano_acta',$anio)->first(); 
        return $datos;
    }
    
    

    public function GetTransferenciaByIdEstablecimiento($id_transferencia) {
        $datos = DB::table('transferencias')
                ->join('establecimientos','transferencias.id_ipress','=','establecimientos.id')
                ->select('transferencias.*','establecimientos.nombre as farmacia_destino')
                ->where('transferencias.id', '=', $id_transferencia)
                ->first();
        return $datos;
    }

    public function GetTransferenciaByIdDependencia($id_transferencia) {
        $datos = DB::table('transferencias')
                ->join('dependencias','transferencias.id_farmacia_destino','=','dependencias.id')
                ->select('transferencias.*','dependencias.nombre_dependencia as farmacia_destino')
                ->where('transferencias.id', '=', $id_transferencia)
                ->first();
        return $datos;
    }
    
    public function ObtenerTipoTransferencia($id_transferencia) {
        $datos = Transferencia::where('id', '=', $id_transferencia)->select('tipo')->first();

        return $datos;
    }
    
    public function ShowLotesTransferidosById_transferencia($id_transferencia) {
        $datos = DB::table('transferencia_productos')
                ->join('transferencias','transferencia_productos.id_transferencia','=','transferencias.id')
                ->join('productos','transferencia_productos.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->join('stock_lotes','transferencia_productos.id_stock_lote','=','stock_lotes.id')
                ->where('transferencias.id', '=', $id_transferencia)
                ->where('transferencia_productos.estado', '=', 1)
                ->select('stock_lotes.*','productos.nombre as nombre_producto','productos.codigo as codigo_producto','unidades.nombre as unidad',
                        'transferencia_productos.cantidad as cantidad_transferida','transferencia_productos.id as id_transferencia_producto')
                ->get();
        return $datos;
    }
    
    public function ShowLotesTransferidosById_transferencia_nuevo($id_transferencia=0,$id_transferencia_producto=0) {
        $cad = "select transferencia_productos.id id_transferencia_producto,
productos.nombre as nombre_producto,productos.codigo as codigo_producto,unidades.nombre as unidad,
transferencia_productos.cantidad as cantidad_transferida,transferencia_productos.id as id_transferencia_producto
,lote,to_char(transferencia_productos.fecha_vencimiento,'mm-yyyy')fecha_vencimiento,registro_sanitario,precio_unitario,transferencia_productos.presentacion_comercial, productos.presentacion_comercial as presentacion
from transferencia_productos
inner join transferencias on transferencia_productos.id_transferencia=transferencias.id
inner join productos on transferencia_productos.id_producto=productos.id
inner join unidades on productos.id_unidad=unidades.id
where transferencias.id=".$id_transferencia;
        if($id_transferencia_producto > 0)$cad .=" and transferencia_productos.id=".$id_transferencia_producto;
        //$cad .=" and transferencia_productos.estado=1";
        $cad .=" and transferencia_productos.estado=1 order by productos.nombre";
        $data = DB::select($cad);
        return $data;
    }

    public function ProductosSegunIdTransferencia($id_transferencia) {
        $data=DB::table('transferencia_productos')
                ->join('transferencias','transferencia_productos.id_transferencia','=','transferencias.id')
                ->where('transferencias.id', '=', $id_transferencia)  
                ->where('transferencia_productos.estado','=',1)
                ->select('transferencia_productos.*')
                ->get();
        return $data;
    }
    
    public function ExisteTransferencia($nro_acta, $id_farmacia, $id=0) {
        $anio = date("Y");
        $data = Transferencia::where('nro_acta', '=', $nro_acta)->where('id_farmacia', '=', $id_farmacia)->where('id', '!=', $id)->where('ano_acta',$anio)->first();
        return isset($data) ? true : false;
    }
        
    public function ExisteTransferenciaPendienteByProducto($id_producto, $id_farmacia, $id_transferencia=0) {
        $cad = "Select id_transferencia, nro_acta From transferencias t
Inner Join transferencia_productos tp On t.id=tp.id_transferencia
Where id_producto=".$id_producto." And (t.estado>0 And t.estado<4) And tp.estado='1' And id_farmacia=".$id_farmacia." And t.id <> ".$id_transferencia."";
        $data = DB::select($cad);
        if(isset($data[0])) $data = $data[0];
        return $data;
    }
    
    public function GetCantByCodigoProductoAndRegSanitarioAndLoteAndFechaVencimientoAndPrecioUni($codigo_producto, $lote, $fecha_vencimiento, $registro_sanitario, $precio_unitario,$id_farmacia){
        $data = array();
        $cad= "Select p.id id_producto,p.nombre prod_farmaceutico,p.codigo codigo_producto,t.id_farmacia,u.abreviatura,tp.lote,tp.registro_sanitario
,COALESCE(tp.precio_unitario,'0')precio_unitario, to_char(tp.fecha_vencimiento,'mm-yyyy')fecha_vencimiento
,coalesce(sum(tp.cantidad*tp.presentacion_comercial),0) as cantidad , p.presentacion_comercial as presentacion From transferencias t
Inner Join transferencia_productos tp On t.id=tp.id_transferencia
Inner join productos p on tp.id_producto=p.id
Inner join unidades u on p.id_unidad=u.id
Where p.id_producto ='".$id_producto."'
And tp.registro_sanitario ='".$registro_sanitario."'
And tp.lote ='".$lote."'
And coalesce(to_char(tp.fecha_vencimiento,'mm-yyyy'),' ') ='".$fecha_vencimiento."'
And tp.precio_unitario =".$precio_unitario."
And t.id_farmacia=".$id_farmacia."
And (t.estado>0 And t.estado<4) And tp.estado='1'
Group By p.id,t.id_farmacia,u.abreviatura,tp.lote,tp.registro_sanitario, p.presentacion
,COALESCE(tp.precio_unitario,'0'), to_char(tp.fecha_vencimiento,'mm-yyyy')";
        $data = DB::select($cad);
        if(isset($data[0])) $data = $data[0];
        return $data;
    }
    
    public function ExisteProductoTransferencias($id_producto,$registro_sanitario="", $lote="",$id_farmacia="",$precio_uni=0, $fecha_vencimiento="") {
        $cad = "select 
coalesce(string_agg(nro_acta,','),'')nro_acta,sum(cantidad)cantidad 
from transferencias T1
inner join transferencia_productos T2 on T1.id=T2.id_transferencia
where T2.id_producto=".$id_producto."
and T2.registro_sanitario='".$registro_sanitario."'
and T2.lote='".$lote."'
and T2.precio_unitario=".$precio_uni."
and to_char(T2.fecha_vencimiento,'mm-yyyy')='".$fecha_vencimiento."'
and T1.id_farmacia=".$id_farmacia."
and (T1.estado>0 And T1.estado<4) And T2.estado='1'";
        $data = DB::select($cad);
        //return $cad;
        return $data[0];
        
    }
    
    public function AllTransferenciaByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "") {

        $cad = "select receta_vales.*,users.name nombre_usuario, users.paterno as paterno_usuario,users.materno as materno_usuario,receta_vales.created_at as fec_creacion
,admisiones.nro_admision, c.nombre consultorio 
from receta_vales
Inner Join consultorios c On receta_vales.id_consultorio=c.id
inner join users on receta_vales.id_user=users.id
inner join farmacias on receta_vales.id_farmacia=farmacias.id
left join admisiones on receta_vales.id_admision=admisiones.id
where receta_vales.id_farmacia=" . $id_farmacia;
        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta like '%" . $nro_receta . "%'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($iddispensador != "")
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_expedicion >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_expedicion <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=1
order By receta_vales.fecha_expedicion desc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function AllRegistradores($id_farmacia) {
        $cad = "Select t.id_user, upper(u.name) nombre, upper(u.paterno) paterno, upper(u.materno) materno From transferencias t
Inner Join users u On t.id_user = u.id
Where t.id_farmacia =" . $id_farmacia . "
Group By t.id_user, u.name, u.paterno, u.materno";
        $data = DB::select($cad);
        return $data;
    }


    public function ShowAllSalidas2($id_producto, $fechaDesde, $fechaHasta){

        $cad = "Select * From sp_report_orden_compra (".$id_producto.",'".$fechaDesde."','".$fechaHasta."');";
        $data = DB::select($cad);
        return $data;

        }
    public function ShowAllSalidas($id_farmacia, $fechaDesde, $fechaHasta,$estado=0,$reg_sanitario="",$lote="",$id_producto=0,$id_tipo_producto=0,$l_establecimiento=0,$l_dependencia=0){
        /*
        $cad="select t.nro_acta, t.fecha fecha_salida, tpr.nombre tipo_producto, p.id id_producto, p.codigo cod_producto,t.nombre_jefe_farmacia_local,
        p.nombre nombre_producto, trpr.registro_sanitario, trpr.lote, trpr.fecha_vencimiento, trpr.cantidad, trpr.precio_unitario p_unitario, d.nombre_dependencia lugar_destino_dependencia, f.nombre lugar_destino_farmacia, e.nombre lugar_destino_establecimiento
        from transferencias t
        inner join transferencia_productos trpr on t.id=trpr.id_transferencia        
        inner join productos p on p.id=trpr.id_producto
        inner join tipo_productos tpr on tpr.id=p.id_tipo
        left join farmacias f on f.id=t.id_farmacia_destino
        left join establecimientos e on t.id_ipress=e.id
        left join dependencias d on t.id_farmacia_destino=d.id
        where t.estado>3 and t.id_farmacia= ".$id_farmacia." and t.fecha >= '".$fechaDesde."' and t.fecha <= '".$fechaHasta."'";
        */
        $cad="select t.nro_acta, t.fecha fecha_salida, tpr.nombre tipo_producto, p.id id_producto, p.codigo cod_producto,t.nombre_jefe_farmacia_local, p.nombre nombre_producto, trpr.registro_sanitario, trpr.lote, trpr.fecha_vencimiento, trpr.cantidad, trpr.precio_unitario p_unitario, d.nombre_dependencia lugar_destino_dependencia, f.nombre lugar_destino_farmacia, e.nombre lugar_destino_establecimiento, mt.nombre,t.nombre_jefe_servicio, trpr.presentacion_comercial,p.presentacion_comercial pc, t.estado  
        from transferencias t
        inner join transferencia_productos trpr on t.id=trpr.id_transferencia 
        inner join motivo_transferencias mt on mt.id=t.id_motitransferencia  
        inner join productos p on p.id=trpr.id_producto
        inner join tipo_productos tpr on tpr.id=p.id_tipo
        left join farmacias f on f.id=t.id_farmacia_destino
        left join establecimientos e on t.id_ipress=e.id
        left join dependencias d on t.id_farmacia_destino=d.id
        where trpr.estado=1 and t.id_farmacia= ".$id_farmacia." and t.fecha >= '".$fechaDesde."' and t.fecha <= '".$fechaHasta."'";
        
        if ($l_dependencia):
            $cad .= " and d.id = '" . $l_dependencia . "'";
        endif;

        if ($l_establecimiento):
            $cad .= " and e.id = '" . $l_establecimiento . "'";
        endif;

        if ($reg_sanitario):
            $cad .= " and trpr.registro_sanitario = '" . $reg_sanitario . "'";
        endif;

        if ($lote):
            $cad .= " and trpr.lote = '" . $lote . "'";
        endif;

        if ($id_producto != "")
            $cad .= " and trpr.id_producto = " . $id_producto . "";

        if ($id_tipo_producto != "")
            $cad .= " and tpr.id = " . $id_tipo_producto . "";
            
        if ($estado != "")
            $cad .= " and t.estado=".$estado."";

        $cad .= " and trpr.estado=1";

        $cad .= " order by t.fecha desc";
        
        $data = DB::select($cad);
        //dd($cad);
        return $data;
    }
    
    public function ShowAllSalidasFarmacia($id_farmacia, $fechaDesde, $fechaHasta,$reg_sanitario="",$lote="",$id_producto=0,$id_tipo_producto=0){
        $cad="select t.nro_acta, t.fecha fecha_salida, tpr.nombre tipo_producto, p.id id_producto, p.codigo cod_producto,t.nombre_jefe_farmacia_local, p.nombre nombre_producto, trpr.registro_sanitario, trpr.lote, trpr.fecha_vencimiento, trpr.cantidad, trpr.precio_unitario p_unitario, d.nombre_dependencia lugar_destino_dependencia, f.nombre lugar_destino_farmacia, e.nombre lugar_destino_establecimiento, mt.nombre,t.nombre_jefe_servicio, trpr.presentacion_comercial,p.presentacion_comercial pc, t.estado, co.nombre lugar_destino_consultorio  
        from transferencias t
        inner join transferencia_productos trpr on t.id=trpr.id_transferencia 
        inner join motivo_transferencias mt on mt.id=t.id_motitransferencia  
        inner join productos p on p.id=trpr.id_producto
        inner join tipo_productos tpr on tpr.id=p.id_tipo
        left join farmacias f on f.id=t.id_farmacia_destino
        left join establecimientos e on t.id_ipress=e.id
        left join dependencias d on t.id_farmacia_destino=d.id
        left join consultorios co on t.id_consultorio=co.id
        where trpr.estado=1 and t.id_farmacia= ".$id_farmacia." and t.fecha >= '".$fechaDesde."' and t.fecha <= '".$fechaHasta."'";
        
        if ($reg_sanitario):
            $cad .= " and trpr.registro_sanitario = '" . $reg_sanitario . "'";
        endif;

        if ($lote):
            $cad .= " and trpr.lote = '" . $lote . "'";
        endif;

        if ($id_producto != "")
            $cad .= " and trpr.id_producto = " . $id_producto . "";

        if ($id_tipo_producto != "")
            $cad .= " and tpr.id = " . $id_tipo_producto . "";
            
        $cad .= " and trpr.estado=1";

        $cad .= " order by t.fecha desc";
        
        $data = DB::select($cad);
        //dd($cad);
        return $data;
    }
    
    function allMovimientoByEstablecimiento($id_establecimiento, $id_origen, $fechaDesde, $fechaHasta){
     
        $cad = "select T1.* ,T4.nombre as consultorio_destino
from movimiento_historias T1
inner join consultorios T4 on T1.id_consultorio=T4.id
where T1.id_ipress = ".$id_establecimiento." and T1.id_origen = ".$id_origen." and T1.fecha >= '".$fechaDesde."' and T1.fecha <= '".$fechaHasta."'";        
        $cad.= " order by T1.id desc ";
        
        $data = DB::select($cad);
        return $data;
    }

    function allMovimientoRecibidosByEstablecimiento($id_establecimiento, $id_consultorio, $fechaDesde, $fechaHasta){
     
        $cad = "select T1.* ,T4.nombre as consultorio_origen
from movimiento_historias T1
inner join consultorios T4 on T1.id_origen=T4.id
where T1.id_ipress = ".$id_establecimiento." and T1.id_consultorio = ".$id_consultorio." and T1.estado >=2 and T1.anulado=1 and T1.fecha between '".$fechaDesde."' and '".$fechaHasta."'";        
        $cad.= " order by T1.id desc ";
        
        $data = DB::select($cad);
        return $data;
    }

    public function getBuscarHCDNI($buscar, $id_establecimiento, $id_consultorio)
    {

        $cad = "select ah.id, ah.nro_historia, a.nro_doc_ident, a.nombre, a.paterno, a.materno
            from asegurados a
            inner join asegurado_historias ah on ah.id_asegurado = a.id
            where a.paterno||a.materno||a.nombre||a.nro_doc_ident||ah.nro_historia  ilike '%" . $buscar . "%' AND estado_hc=0  AND COALESCE(a.id_estado_reg,1)!=3 AND id_establecimiento =".$id_establecimiento." AND id_consultorio_origen =".$id_consultorio."";

            $cad .= " Order by a.nro_doc_ident asc
            limit 10 ";

            $data = DB::select($cad);
                return $data;
    }

    public static function RegistrarHistoriasClinicasServicio($id_nro_movimiento, $id_user, $id_establecimiento,$id_consultorio, $p_estado, $p_historias)
    {
        $cad = "Select sp_insert_movimiento_hc_servicio(?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_nro_movimiento, $id_user, $id_establecimiento,$id_consultorio, $p_estado, $p_historias));
        return $data[0]->sp_insert_movimiento_hc_servicio;
    }

    public function ShowHistoriasById_movimiento_hc($id_nro_movimiento=0) {
        $cad = "select mah.id, a.tipo_doc_ident, a.nro_doc_ident, a.nombre, a.paterno, a.materno, ah.nro_historia
                from movimiento_asegurado_historias mah 
                inner join asegurado_historias ah on mah.id_asegurado_historia=ah.id 
                inner join asegurados a on ah.id_asegurado=a.id

                where mah.id_movimiento=".$id_nro_movimiento." and mah.estado=1 order by ah.nro_historia";

        $data = DB::select($cad);
        return $data;
    }

    public function registrarPacienteHistoria($id_ipress,$id_user,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$domicilio="",$telefono="") {     
        $cad = "Select sp_insert_paciente_historia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_ipress,$id_user,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$domicilio,$telefono));
        return $data[0]->sp_insert_paciente_historia;
    }

    public function EliminarHistoriasClinicasServicio($id, $id_user, $id_consultorio) {     
        $cad = "Select sp_elimina_hc_lista(?,?,?)";
        $data = DB::select($cad, array($id, $id_user, $id_consultorio));
        return $data[0]->sp_elimina_hc_lista;
    }

    public function CambiaEstadoHCServicio($id,$id_estado, $id_user, $nro_entrada) {     
        $cad = "Select sp_cambia_estado_hc_servicio(?,?,?,?)";
        $data = DB::select($cad, array($id, $id_estado, $id_user, $nro_entrada));
        return $data[0]->sp_cambia_estado_hc_servicio;
    }

    public function obtenerUltiNroHCByEstablecimientoAndAnio($id_establecimiento, $id_servicio) {

        $cad = "Select COALESCE(max(nro_hc),0) + 1 nro_hc From genera_correlativohcs Where anio_hc=".date("Y")." And id_establecimiento=".$id_establecimiento." And id_servicio=".$id_servicio;
        $data = DB::select($cad);
        return $data;
    }

    public function GuardarCorrelativo($id_establecimiento, $id_servicio, $anio, $id_user, $nro_entrada) {     
        $cad = "Select sp_guardar_correlatico(?,?,?,?,?)";
        $data = DB::select($cad, array($id_establecimiento, $id_servicio, $anio, $id_user, $nro_entrada));
        return $data[0]->sp_guardar_correlatico;
    }
    
    public function listar_historia_consultorio($p){
        return $this->readFunctionPostgres('sp_historia_consultorio',$p);
    }   

     public function readFunctionPostgres($function, $parameters = null){
    
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

    public function busca_historia_movimiento($p){
        return $this->readFunctionPostgres('sp_busca_historia_movimiento',$p);
    }

    // Actualizar nro de historia individual
    public function actualizarNroHistoria($id_asegurado_historia, $nro_historia, $id_user) {
        return DB::transaction(function () use ($id_asegurado_historia, $nro_historia, $id_user) {
            $historia = DB::table('asegurado_historias')->where('id', $id_asegurado_historia)->first();

            if (!$historia) {
                return ['ok' => false, 'status' => 404, 'mensaje' => 'No se encontró la Historia Clínica.'];
            }

            // Bloqueo por (establecimiento, N° de historia) hasta el fin de la transacción,
            // el mismo que usa PacienteController@registrar_historia_clinica
            DB::select("select pg_advisory_xact_lock(?, hashtext(?))", [$historia->id_establecimiento, $nro_historia]);

            $existe = DB::table('asegurado_historias')
                ->where('id_establecimiento', $historia->id_establecimiento)
                ->where('nro_historia', $nro_historia)
                ->where('id_estado_reg', 1)
                ->where('id', '<>', $id_asegurado_historia)
                ->exists();

            if ($existe) {
                return ['ok' => false, 'status' => 422, 'mensaje' => 'El N° de Historia Clínica ' . $nro_historia . ' ya existe en este establecimiento.'];
            }

            DB::table('asegurado_historias')->where('id', $id_asegurado_historia)->update([
                'nro_historia' => $nro_historia,
                'id_estado_reg' => 1,
                'updated_us' => $id_user,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return ['ok' => true, 'status' => 200, 'nro_historia' => $nro_historia];
        });
    }

    // Generar nro de historia clinica automatico
    public function GenerarNroHistoriaClinica($id_asegurado, $id_establecimiento, $id_user) {
        return DB::transaction(function () use ($id_asegurado, $id_establecimiento, $id_user) {
            $secuencia = DB::table('generador_secuencia')
                ->where('id_generador_secuencia', 3)
                ->lockForUpdate()
                ->value('secuencia');

            if ($secuencia === null) {
                throw new \Exception('No se encontró la secuencia de apertura');
            }

            $nueva = intval($secuencia) + 1;

            // Actualizar ultimo numero de historia clinica generada
            DB::table('generador_secuencia')
                ->where('id_generador_secuencia', 3)
                ->update(['secuencia' => $nueva]);
            
            $nro_historia = str_pad($nueva, 8, '0', STR_PAD_LEFT);

            DB::table('asegurado_historias')->insert([
                'id_asegurado' => $id_asegurado,
                'id_establecimiento' => $id_establecimiento,
                'nro_historia' => $nro_historia,
                'id_estado_reg' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_us' => $id_user,
            ]);

            return $nro_historia;
        });
    }
}