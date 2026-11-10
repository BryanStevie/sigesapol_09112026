<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CorredoresDetalle extends Model
{   
    protected $table = 'corredores_detalle';
    public $timestamps = false;

    public function listar($p) {
        return $this->readFunctionPostgres('sp_listar_corredores_detalle', $p);
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

    public function obtenerModulosPorServicio($id_servicio_ticket)
    {
        $datos=DB::table('ventanillas') 
                ->select('ventanillas.*')
                ->where('id_servicio', '=', $id_servicio_ticket)
                ->where('estado', '=', '1')
                ->orderBy('nombre', 'asc')
                ->get();
        return $datos;
    }

    public function obtenerRegistroCorredorDetalle($id)
    {
        $datos=DB::table('corredores_detalle')
                ->join('corredores','corredores.id','=','corredores_detalle.id_corredor')
                ->join('ventanillas','ventanillas.id','=','corredores_detalle.id_ventanilla')
                ->join('consultorios','consultorios.id','=','ventanillas.id_servicio')
                ->select('corredores_detalle.*','corredores.id_establecimiento','ventanillas.id_servicio')
                ->where('corredores_detalle.id','=',$id)
                ->where('corredores_detalle.estado','=','1')
                ->where('corredores.estado','=','1')
                ->where('ventanillas.estado','=','1')
                ->where('consultorios.estado_servicio_ticket','=',1)
                ->first();
        return $datos;
    }

    public function limpiarLlamadoTicketPorId($id)
    {
        $ticket = Ticket::Where('seleccionado_us','=',$id)
                            ->orWhere('llamado_us',$id)
                            ->whereIn('id_estado_tv',[1,4])->first();
            if (!empty($ticket)) {
                $ticket->id_estado_tv = 2; // 2
                $ticket->anulado_at = Carbon::now();
                $ticket->anulado_us = $id;
                $ticket->llamado_at = null;
                $ticket->llamado_us = null;
                $ticket->seleccionado_at = null;
                $ticket->seleccionado_us = null;
                $ticket->atendido_at = null;
                $ticket->atendido_us = null;
                $ticket->ventanilla = null;
                //$ticket->orden_llamada = null;
                $ticket->estado_llamada = "0";
                //$ticket->estado = 1;
                $ticket->save();
                //return $ticket;
            } else {
                return 'vacio';
            }
    }
}