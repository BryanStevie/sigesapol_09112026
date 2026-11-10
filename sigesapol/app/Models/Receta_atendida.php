<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Receta_atendida extends Model
{
    //
    public function RecetaSiAtendida($nro_historia){
        $datos=DB::table('receta_atendidas') 
                ->where('receta_atendidas.nro_historia','=',$nro_historia)
                ->select('receta_atendidas.*')
                ->get();
        return $datos;
    }
    
    public function ExisteRecetaAtendida($nro_historia) {
        $data = Receta_atendida::where('nro_historia', '=', $nro_historia)->first();
        return isset($data) ? true : false;
    }
    //
}
