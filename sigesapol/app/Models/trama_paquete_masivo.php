<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class trama_paquete_masivo extends Model {
  protected $table = 'trama_paquete_masivos';
    
    public function enviartramaemergencia($id_emergencia){
        $data = DB::select("SELECT sp_insertar_tramas_emergencia_2 ($id_emergencia)");
      }

      public function enviartramahospitalizacion($id_hospitalizacion){
        $data = DB::select("SELECT sp_insertar_tramas_hospitalizaciones ($id_hospitalizacion)");
      }

      public function enviartramaambulatorio($id_ambulatorios){
        $data = DB::select("SELECT sp_insertar_tramas_ambulatorio ($id_ambulatorios)");
      }

        
}
