<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Unidade extends Model
{
    //
    public function ShowAll(){
        $datos=DB::table('unidades') 
                ->select('unidades.*')
                ->get();
        return $datos;
        
    }
    
    public function GetById($id){
        $datos=DB::table('unidades') 
                ->select('unidades.*')
                ->where('unidades.id','=',$id)
                ->first();
        return $datos;
    }
    
    public function ExisteUnidad($abreviatura, $id) {
        $data = Unidade::where('abreviatura', '=', $abreviatura)->first();
        //var_dump($data); exit;
        return isset($data) ? true : false;
    }
}
