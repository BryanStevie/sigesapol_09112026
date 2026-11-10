<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Comisaria extends Model
{

  public function ShowComisaria()
  {
    $cad = "select * from comisarias where estado= '1' Order By id asc";
    $data = DB::select($cad);
    return $data;
  }
}