<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Situacion extends Model {
    protected $table = 'situaciones';

    public function ShowSituacion(){
    	$cad = "select id, descripcion from Situaciones Order By id asc";
        $data = DB::select($cad);
        return $data;
    }

     public function BuscaSituacion($compara){
    	$cad = "select id  from Situaciones where descripcion ='".$compara."'";
        $data = DB::select($cad);
        return $data[0];
    }

    public function BuscaSituacionD($compara){
        $cad = "select descripcion  from Situaciones where id =".$compara."";
        $data = DB::select($cad);
        return $data[0];
    }

    public function GetAll(){
        $datos=DB::table('situaciones') 
                ->select('situaciones.*')
                ->orderBy('descripcion', 'asc')
                ->get();
        return $datos;   
    }

}
