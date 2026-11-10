<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cenare extends Model {

    /*
    public function buscar_trama_cenares($p){
        return $this->readFunctionPostgres('sp_show_plot_cenares',$p);
    }

    public function readFunctionPostgres($function, $parameters = null){
    
      $_parameters = '';
      if (count($parameters) > 0) {
          $_parameters = implode("','", $parameters);
          $_parameters = "'" . $_parameters . "',";
      }
      
      $data = DB::select("BEGIN;");
      $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";

      $data = DB::select($cad);
      $cad = "FETCH ALL IN ref_cursor;";
      $data = DB::select($cad);

      return $data;
   }    */

   public function buscar_trama_cenares($fecha_fin, $tipo = 0) {
        $cad = "Select * From sp_show_plot_cenares ('".$fecha_fin."','".$tipo."');";

        $data = DB::select($cad);

        

        return $data;
    }
}
