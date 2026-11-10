<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class Tipo_trabajo extends Model
{
    public function GetAll(){
        $datos=DB::table('tipo_trabajos') 
                ->select('tipo_trabajos.*')
                ->where('estado','1')
                ->orderBy('nombre', 'asc')
                ->get();
        return $datos;   
    }
}
