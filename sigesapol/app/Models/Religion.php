<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Religion extends Model
{
    protected $table = 'Religiones';
  

    public static function ShowReligion()
    {
        $cad = "select id, nombre from religiones where estado='1' order by nombre asc";
        $data = DB::select($cad);
        return $data;
    }
}
