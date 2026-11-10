<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use Carbon\Carbon;

class CanMonitoreo extends Model
{
    public function BuscarMonitoreo($id_can, $id_establecimiento){
        $data = DB::table('monitoreo_cans')
                ->where('id_can',$id_can) //cambiar 22
                ->where('id_establecimiento',$id_establecimiento)//cambiar 1
                ->get();
        return $data;      
    }

    public function GetArchivos($id_can, $id_establecimiento){
        $data = DB::table('can_archivos')        
                ->where('establecimiento_id',$id_establecimiento)
                ->where('can_id', $id_can)
                ->where('servicio_id',0)
                ->where('estado',1)
                ->get();
        return $data; 
    }

    public function GetObservacion($id_can, $id_establecimiento){
        $observaciones= DB::table('can_observaciones')
                        ->where('establecimiento_id',$id_establecimiento)
                        ->where('can_id', $id_can)
                        ->where('estado',1)
                        ->get();
        return $observaciones; 
    }

    public function GetById($id_can, $id_establecimiento){
        $cad = "select mc.*
        from monitoreo_cans mc
        inner join establecimientos e on e.id = mc.id_establecimiento
        where mc.estado = 1 and mc.id_servicio =  0 and mc.id_establecimiento = '".$id_establecimiento."' and mc.id_can = '".$id_can."'" ;
        $data = DB::select($cad);
        return $data[0];      
    }

    public function ListarServicioCan($id_can, $id_establecimiento) {
        $cad = "select mc.*, c.nombre
        from monitoreo_cans mc
        inner join establecimientos e on e.id = mc.id_establecimiento
        inner join consultorios c on c.id = mc.id_servicio
        where mc.estado = 1 and mc.id_establecimiento = '".$id_establecimiento."' and mc.id_can = '".$id_can."'
        order by c.nombre, e.nivel desc" ;
        $data = DB::select($cad);
        return $data;
    }

    public function listar_can_servicio_ajax($p){
        return $this->readFunctionPostgres('sp_listar_servicio_can',$p);
    }

    public function listar_can_productos_establecimiento_servicio_ajax($p){
        return $this->readFunctionPostgres('sp_listar_can_ipress_servicio',$p);
    }

    public function listar_can_productos_establecimiento_ajax($p){
        return $this->readFunctionPostgres('listar_can_productos_establecimiento_ajax',$p);
    }

    
    public function ActivarCan($id, $medicamento, $dispositivo,$id_user,$accion){
        $cad = "Select sp_activar_can(?,?,?,?,?)";
        $data = DB::select($cad, array($id, $medicamento, $dispositivo,$id_user,$accion));
        return $data[0]->sp_activar_can;
    }

    public function ActivarTodosCan($id_establecimiento, $id_can, $medicamento, $dispositivo,$id_user){
        $cad = "Select sp_activar_todos_can(?,?,?,?,?)";
        $data = DB::select($cad, array($id_establecimiento, $id_can, $medicamento, $dispositivo,$id_user));
        return $data[0]->sp_activar_todos_can;
    }

    public function AmpliarTiempoCan($id, $id_establecimiento, $id_can, $id_servicio, $id_user, $fin_first_login, $accion){
        $cad = "Select sp_ampliar_tiempo_can(?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id, $id_establecimiento, $id_can, $id_servicio, $id_user, $fin_first_login, $accion));
        return $data[0]->sp_ampliar_tiempo_can;
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

   public function BuscarEstablecimientoServicioCan($id) {
        $cad = "select mc.*
        from monitoreo_cans mc
        where mc.estado = 1 and mc.id = '".$id."'" ;
        $data = DB::select($cad);
        return $data[0];
    }

    public function ListarArchivosCan($id_can, $id_establecimiento,$id_servicio) {
        $cad = "select a.*
        from can_archivos a
        where a.estado = 1 and a.id_establecimiento = '".$id_establecimiento."' and a.id_can = '".$id_can."'";

        if($id_servicio!=0){
            $cad .= " and a.id_servicio = '".$id_servicio."'";
        }
        
        $data = DB::select($cad);
        return $data;
    }

    public static function ActualizarConsultorioCAN($id_monitoreo, $medicina_interna,$odontologia,$obstetricia,$id_user)
    {
        $cad = "Select sp_actualizar_consultorio_can(?,?,?,?,?)";
        $data = DB::select($cad, array($id_monitoreo, $medicina_interna,$odontologia,$obstetricia,$id_user));
        return $data[0]->sp_actualizar_consultorio_can;
    }

    public function ShowAllProductosEstablecimientos($p){
        return $this->readFunctionPostgres('sp_listar_producto_establecimiento',$p);
    }

    public static function ActualizaNewProducto($id, $necesidad1, $cpma,$mes1,$mes2,$mes3,$mes4,$mes5,$mes6,$mes7,$mes8,$mes9,$mes10,$mes11,$mes12) {

        $cad = "Select sp_actualiza_productos_new_nivel_1(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id, $necesidad1, $cpma,$mes1,$mes2,$mes3,$mes4,$mes5,$mes6,$mes7,$mes8,$mes9,$mes10,$mes11,$mes12));
        
        return $data[0];

    }

    public static function ActualizaNewProductoNivel2y3($id, $necesidad1, $mes1,$mes2,$mes3,$mes4,$mes5,$mes6,$mes7,$mes8,$mes9,$mes10,$mes11,$mes12) {

        $cad = "Select sp_actualiza_productos_new_nivel_2y3(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id, $necesidad1, $mes1,$mes2,$mes3,$mes4,$mes5,$mes6,$mes7,$mes8,$mes9,$mes10,$mes11,$mes12));
        
        return $data[0];

    }

    public static function ActualizaFinalProductoNivel2y3($petitorio_id, $can_id, $establecimiento_id) {

        $cad = "Select sp_actualiza_productos_final_nivel_2y3(?,?,?)";
        $data = DB::select($cad, array($petitorio_id, $can_id, $establecimiento_id));
        
        return $data[0];

    }

    public static function EliminaProductoCan($id) {

        $cad = "Select sp_elimina_producto_can(?)";
        $data = DB::select($cad, array($id));
        
        return $data[0];

    }

    public static function GetProductosTipoRegistrados($can_id, $establecimiento_id, $tipo) {

        $data = DB::table('can_ipress as e')
                    ->select('e.id_tipo_dispositivo')
                    ->where('e.id_establecimiento',$establecimiento_id)
                    ->where('e.id_can',$can_id)
                    ->where('e.id_tipo_dispositivo',$tipo)
                    ->where('e.estado','<>',2)
                    ->get();
        return $data;
    }

    public static function CerrarPetitorioCan($can_id, $establecimiento_id, $tipo_cerrado) {
        DB::table('monitoreo_cans')
            ->where('id_can', $can_id)
            ->where('id_establecimiento', $establecimiento_id)
            ->update([
                $tipo_cerrado => 2,
                'updated_at'=>Carbon::now()
        ]);
    }

    public static function ConsultaEstimacionFarmaceuticos($can_id, $establecimiento_id, $tipo_dispositivo, $valor) {

        if($valor == 1 ): //avance
            $data=DB::table('can_ipress')
                        ->where('id_can',$can_id) //cambiar 22
                        ->where('id_tipo_dispositivo',$tipo_dispositivo)                                 
                        ->where('estado','<>',2)    
                        ->where('petitorio','=',1)                                 
                        ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                        ->orderby ('id_tipo_dispositivo','asc')  
                        ->orderby ('descripcion','asc')  
                        ->get();
        else:
            $data=DB::table('can_ipress')
                        ->where('necesidad_anual','>',0)
                        ->where('id_can',$can_id) //cambiar 22
                        ->where('id_tipo_dispositivo',$tipo_dispositivo)                                 
                        ->where('estado','<>',2)                                 
                        ->where('petitorio','=',1)                                 
                        ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                        ->orderby ('id_tipo_dispositivo','asc')  
                        ->orderby ('descripcion','asc')  
                        ->get();
        endif;


        return $data;
    }

    public static function ConsultaEstimacionDispositivos($can_id, $establecimiento_id, $tipo_dispositivo, $valor) {

        if($valor == 1 ): //avance
            $data=DB::table('can_ipress')
                        ->where('id_can',$can_id) //cambiar 22
                        ->where('id_tipo_dispositivo','>=',$tipo_dispositivo) 
                        ->where('estado','<>',2)                   
                        ->where('petitorio','=',1)                                                                      
                        ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                        ->orderby ('id_tipo_dispositivo','asc')  
                        ->orderby ('descripcion','asc')  
                        ->get();
        else:
            $data=DB::table('can_ipress')
                        ->where('necesidad_anual','>',0)
                        ->where('id_can',$can_id) //cambiar 22
                        ->where('id_tipo_dispositivo','>=',$tipo_dispositivo)                                
                        ->where('estado','<>',2)                    
                        ->where('petitorio','=',1)                                              
                        ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                        ->orderby ('id_tipo_dispositivo','asc')  
                        ->orderby ('descripcion','asc')  
                        ->get();
        endif;

        return $data;
    }

    public static function ProductosIngresados2y3($can_id, $establecimiento_id, $tipo_dispositivo) {

        $data=DB::table('can_ipress')
                    ->where('id_can',$can_id) 
                    ->where('id_tipo_dispositivo','>',1)   
                    ->where('id_establecimiento',$establecimiento_id)
                    ->where('estado','<>',2)
                    ->where('petitorio','=',1)
                    ->orderby('descripcion','asc')
                    ->get();
        
        return $data;
    }

}
