<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Salida_diaria extends Model
{
    //
    public function GetCantidadConsolidado($id_farmacia, $fecha_salida) {
        $data = Salida_diaria::where('id_farmacia', '=', $id_farmacia) 
                ->where('fecha_salida', '=', $fecha_salida)                
                ->select(DB::raw('sum(cant_gral_receta) as cantidad'),'fecha_salida')
                ->groupBy('fecha_salida')
                ->first();
        return $data;
    }
}
