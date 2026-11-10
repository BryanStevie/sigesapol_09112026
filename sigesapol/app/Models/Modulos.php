<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Modulos extends Model
{
    protected $table = 'ventanillas';
    public $timestamps = false;

    public function obtenerServicioParaTickets($id_servicio)
    {
        $datos = DB::table('ventanillas')
                        ->join('consultorios','consultorios.id','=','ventanillas.id_servicio')
                        ->select('ventanillas.*','consultorios.nombre')
                        ->where('consultorios.id','=',$id_servicio)
                        ->where('consultorios.estado_servicio_ticket','=',1)
                        ->get();
        return $datos;
    }

    public function mostrarVentanillas() {
        $datos = DB::table('ventanillas')
                ->join('consultorios', 'consultorios.id','=','ventanillas.id_servicio')
                ->select('ventanillas.*', 'consultorios.nombre as nom_servicio')
                ->get();
        return $datos;
    }

    public function mostrarModuloPorId($id_modulo)
    {
        $datos = DB::table('ventanillas')
                ->join('consultorios', 'consultorios.id','=','ventanillas.id_servicio')
                ->select('ventanillas.*', 'consultorios.nombre as nom_servicio')
                ->where('ventanillas.id','=',$id_modulo)
                ->first();
        return $datos;
    }

}
