<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class CqSalas extends Model
{
    protected $table = 'cq_salas';

    public function GetAllActivos()
    {
        $salas = DB::table('cq_salas')
            ->where('estado', '=', 1)
            ->orderBy('nombre', 'asc')
            ->get();
        return $salas;
    }
}
