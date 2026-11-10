<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tv extends Model
{
    public static function llamar_citado_tv($id_cita) {
        $cad = "select sp_llamar_citado_tv(?)";
        $data = DB::select($cad, array($id_cita));

        return $data[0]->sp_llamar_citado_tv;
    }

    public function listar($p) {
        return $this->readFunctionPostgres('sp_listar_tvs', $p);
    }

    public function listar_area($p) {
        return $this->readFunctionPostgres('sp_listar_tv_areas', $p);
    }

    public function readFunctionPostgres($function, $parameters = null) {
        $_parameters = '';
        if (count($parameters) > 0) {
            $_parameters = implode("','", $parameters);
            $_parameters = "'" . $_parameters . "',";
        }
        //echo $_parameters;
        $data = DB::select("BEGIN;");
        $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";

        $data = DB::select($cad);
        $cad = "FETCH ALL IN ref_cursor;";
        $data = DB::select($cad);
        return $data;
    }

    public function showAllPorEstablecimiento($id_ipress){
        $datos=DB::table('tvs') 
                ->join('consultorios','tvs.id_consultorio','=','consultorios.id')
                ->select('tvs.id','consultorios.nombre')
                ->where('tvs.estado', '=', '1')
                ->where('tvs.id_establecimiento', '=', $id_ipress)
                ->orderBy('tvs.id', 'asc')
                ->get();
        return $datos;
        
    }

    public function showSubConsultoriosPorIpressAndConsultorio($id_ipress, $id_consultorio){
        $datos=DB::table('sub_consultorios') 
                ->join('consultorios','sub_consultorios.id_consultorio','=','consultorios.id')
                ->select('sub_consultorios.id','sub_consultorios.descripcion')
                ->where('sub_consultorios.id_establecimiento', '=', $id_ipress)
                ->where('sub_consultorios.id_consultorio', '=', $id_consultorio)
                ->where('sub_consultorios.id_estado_reg', '=', '1')
                ->orderBy('sub_consultorios.descripcion', 'asc')
                ->get();
        return $datos;
        
    }

    public function getConsultoriosPorTv($id_tv){
        $datos=DB::table('tvs') 
                ->join('tv_servicios','tvs.id','=','tv_servicios.id_tv')
                ->join('consultorios','tv_servicios.id_consultorio','=','consultorios.id')
                ->select('consultorios.*')
                ->where('tvs.id','=',$id_tv)
                ->where('tv_servicios.id_estado','=','1')
                ->orderBy('tv_servicios.created_at','asc')
                ->get();
        return $datos;
    }

    public function llamar_citado_ticket($id_ticket, $id_user) {
        $cad = "select sp_llamar_citado_ticket(?,?)";
        $data = DB::select($cad, array($id_ticket, $id_user));
        return $data[0]->sp_llamar_citado_ticket;
    }

    public function llamar_citado_ticket2($id_ticket, $id_user, $ventanilla) {
        $cad = "select sp_llamar_citado_ticket(?,?,?)";
        $data = DB::select($cad, array($id_ticket, $id_user, $ventanilla));
        return $data[0]->sp_llamar_citado_ticket;
    }

    public  function llamar_citado_tv_tickets($id_cita, $id_user) {
        $cad = "select sp_llamar_citado_tv_tickets(?,?)";
        $data = DB::select($cad, array($id_cita, $id_user));
        return $data[0]->sp_llamar_citado_tv_tickets;
    }

    public function obtenerSubConsultorioDeTvPorIdTv($id_tv)
    {
        $datos = DB::table('tv_sub_consultorios')
                    ->join('sub_consultorios','sub_consultorios.id','=','tv_sub_consultorios.id_sub_consultorios')
                    ->join('consultorios','consultorios.id','=','sub_consultorios.id_consultorio')
                    ->select('tv_sub_consultorios.id', 'consultorios.nombre as nombre_consultorio','sub_consultorios.id as id_sub_consultorio', 'sub_consultorios.descripcion as nombre_sub_consultorio')
                    ->where('tv_sub_consultorios.id_tv','=',$id_tv)
                    ->where('tv_sub_consultorios.estado','=',1)
                    ->orderBy('consultorios.nombre','asc')
                    ->get();
        return $datos;
    }

    public function readFunctionPostgres2($function, $parameters = null) {
        $_parameters = '';
        if ($parameters && count($parameters) > 0) {
            $_parameters = implode("','", $parameters);
            $_parameters = "'" . $_parameters . "',";
        }
    
        DB::beginTransaction();
    
        try {
            // Ejecutar la función y abrir el cursor
            $cad = "SELECT " . $function . "(" . $_parameters . "'ref_cursor');";
            DB::select($cad);
    
            // Recuperar los resultados del cursor
            $cad = "FETCH ALL IN ref_cursor;";
            $data = DB::select($cad);
    
            DB::commit();
    
            return $data;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public  function insertTvSubConsultorio($p) {
        return $this->readFunctionPostgres2('sp_registrar_tv_sub_consultorio', $p);
    }
    
    public  function eliminarTvSubConsultorio($p) {
        return $this->readFunctionPostgres2('sp_eliminar_tv_sub_consultorio', $p);
    }
    
}