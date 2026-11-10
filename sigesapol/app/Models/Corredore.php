<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Corredore extends Model
{
    public function ShowAll(){
        $datos=DB::table('corredores') 
                ->select('corredores.*')
		        ->where('corredores.estado','1')
                ->orderBy('corredores.nombre', 'asc')
                ->get();
        return $datos;
    }

    public function ShowAllByIdEstablecimiento($id_establecimiento){
        $datos=DB::table('corredores') 
                ->select('corredores.*')
                ->where('corredores.id_establecimiento',$id_establecimiento)
		        ->where('corredores.estado','1')
                ->orderBy('corredores.nombre', 'asc')
                ->get();
        return $datos;
    }

    public function ShowAllWithEstablecimiento() {
        $datos = DB::table('corredores')
                ->join('establecimientos', 'corredores.id_establecimiento','=','establecimientos.id')
                ->select('corredores.*', 'establecimientos.nombre as establecimiento')
                ->get();
        return $datos;
    }

    public function Desactivar($id, $id_user) {
        $corredor = Corredore::find($id);
        $corredor->estado = '0';
        $corredor->updated_at = Carbon::now();
        $corredor->updated_us = $id_user;
        return $corredor->save();
    }

    public function Activar($id, $id_user) {
        $corredor = Corredore::find($id);
        $corredor->estado = '1';
        $corredor->updated_at = Carbon::now();
        $corredor->updated_us = $id_user;
        return $corredor->save();
    }

    public function Registrar($id_establecimiento, $nombre, $id_user) {
        $corredor = new Corredore;
        $corredor->id_establecimiento = $id_establecimiento;
        $corredor->nombre = $nombre;
        $corredor->estado = '1';
        $corredor->created_at = Carbon::now();
        $corredor->created_us = $id_user;
        return $corredor->save();
    }

    public function Modificar($id_corredor, $id_establecimiento, $nombre, $id_user) {
        $corredor = Corredore::find($id_corredor);
        $corredor->id_establecimiento = $id_establecimiento;
        $corredor->nombre = $nombre;
        $corredor->updated_at = Carbon::now();
        $corredor->updated_us = $id_user;
        return $corredor->save();
    }

    public function GetById($id) {
        $corredor = Corredore::find($id);
        return $corredor;
    }
}
