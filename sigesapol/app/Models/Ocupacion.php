<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Ocupacion extends Model
{
    protected $table = 'Ocupaciones';
  
    public function ShowOcupacion()
    {
        $cad = "select id, nombre from ocupaciones where estado='1' order by nombre asc";
        $data = DB::select($cad);
        return $data;
    }
}
