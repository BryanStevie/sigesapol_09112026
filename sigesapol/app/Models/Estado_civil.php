<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Estado_civil extends Model
{
    protected $table = 'Estado_civiles';

    public function ShowEstadoCivil()
    {
        $cad = "select id, nombre from estado_civiles where estado='1' order by nombre asc";
        $data = DB::select($cad);
        return $data;
    }

}
