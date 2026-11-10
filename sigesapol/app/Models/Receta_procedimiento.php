<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Receta_procedimiento extends Model
{
    public function ProcedimietosByIdReceta($id_receta_vale) {
       $data=DB::table("receta_procedimientos")
               ->join('procedimientos','receta_procedimientos.id_procedimiento','=','procedimientos.id')
               ->select('procedimientos.*')
               ->where('receta_procedimientos.id_receta_vale','=',$id_receta_vale)
               ->where('receta_procedimientos.estado','=',1)
               ->get();
       return($data);
    }
}
