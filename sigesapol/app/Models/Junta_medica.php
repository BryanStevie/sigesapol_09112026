<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Junta_medica extends Model
{
    //
    public function ShowAllJuntas($id_farmacia) {
        $data = DB::table('junta_medicas')                
                ->join('users','junta_medicas.id_user','=','users.id')
                ->where('junta_medicas.id_farmacia', '=', $id_farmacia)
                ->select('junta_medicas.*','users.name')
                ->orderBy('junta_medicas.id','desc')
                ->get();
        return $data; 
        
    }
}
