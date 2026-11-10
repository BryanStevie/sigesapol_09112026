<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Eventos_epidemiologicos extends Model
{
    protected $table = 'eventos_epidemiologicos';
    protected $fillable = [
    'id',
    'descripcion',
    'estado',
    'created_at',
    'created_us',
    'updated_at',
    'updated_us'
  ];

  function getEventosEpidemiologicos() {
    $cad = "Select id, descripcion From eventos_epidemiologicos Order By id";
    $data = DB::select($cad);
    return $data;
  }

  public function listar_eventos_ajax($p){
        return $this->readFunctionPostgres('sp_eventos_vigilancia',$p);
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
      $closeCursor = DB::select("CLOSE ref_cursor;");
      return $data;
   }

}
