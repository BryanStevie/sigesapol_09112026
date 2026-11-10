<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contador_receta extends Model
{
    public function ObtenerContadorReceta($id_farmacia) {
        $data=  Contador_receta::where('id_farmacia',$id_farmacia)->first();
        return $data;
    }
}
