<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tipo_producto extends Model
{
    //
    public function ShowAll(){
        $datos=DB::table('tipo_productos') 
                ->select('tipo_productos.*')
                ->get();
        return $datos;        
    }
    
    public function GetById($id){
        $datos=DB::table('tipo_productos') 
                ->select('tipo_productos.*')
                ->where('tipo_productos.id','=',$id)
                ->first();
        return $datos;
    }
}
