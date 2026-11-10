<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class Coredor extends Model
{
    protected $table = 'corredores';
    public function ShowAll()
    {
        $datos = DB::table('corredores')
            ->select('corredores.*')
            ->where('corredores.estado', 1)
            ->orderBy('id', 'asc')
            ->get();
        return $datos;
    }
  //
}
