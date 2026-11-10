<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Etnia extends Model
{
    protected $table = 'Etnias';

    public function ShowEtnia()
    {
        $cad = "select id, codigo_etnia, nombre_etnia from etnias where estado='1' order by nombre_etnia asc";
        $data = DB::select($cad);
        return $data;
    }

}
