<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use Carbon\Carbon;

class User extends Model {

    //
    public function ShowAll() {
        $cad = "select T1.*,T2.nombre farmacia,T3.nombre consultorio,T4.nombre establecimiento 
        from users T1
        left join establecimientos T4 on T1.id_establecimiento=T4.id
        left join farmacias T2 on T1.id_farmacia=T2.id
        left join consultorios T3 on T1.id_consultorio=T3.id
        where T1.status <> '3'
        order by paterno";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllActivos() {
        $cad = "select T1.*,T2.nombre farmacia,T3.nombre consultorio,T4.nombre establecimiento 
from users T1
left join establecimientos T4 on T1.id_establecimiento=T4.id
left join farmacias T2 on T1.id_farmacia=T2.id
left join consultorios T3 on T1.id_consultorio=T3.id
where T1.status <> 0 and T1.status <> 3
order by paterno";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllIdFarmacia($id_farmacia = 0) {
        $cad = "select T1.*,T2.nombre farmacia,T3.nombre consultorio,T4.nombre establecimiento 
from users T1
left join establecimientos T4 on T1.id_establecimiento=T4.id
left join farmacias T2 on T1.id_farmacia=T2.id
left join consultorios T3 on T1.id_consultorio=T3.id";
if($id_farmacia != 0) $cad.= " Where T1.status <> '3' and T1.id_farmacia=".$id_farmacia."";
$cad.= "Order by paterno";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllIdFarmaciaActivos($id_farmacia = 0) {
        $cad = "select T1.*,T2.nombre farmacia,T3.nombre consultorio,T4.nombre establecimiento 
from users T1
left join establecimientos T4 on T1.id_establecimiento=T4.id
left join farmacias T2 on T1.id_farmacia=T2.id
left join consultorios T3 on T1.id_consultorio=T3.id";
if($id_farmacia != 0) $cad.= " Where T1.status <> 0 and T1.id_farmacia=".$id_farmacia."";
$cad.= "Order by paterno";
        $data = DB::select($cad);
        return $data;
    }

    public function getAsignacionRol($user_id) {
        $cad = "select id from assigned_roles where user_id = " . $user_id . " and role_id=3";
        $data = DB::select($cad);
        return $data;
    }

    public function GetById($id) {
        $datos = DB::table('users')
                ->select('users.*')
                ->where('users.id', '=', $id)
                ->first();
        return $datos;
    }

    public function TieneTokenActivo($id_user)
    {
        $res = DB::table('users')
                ->select('users.status_token')
                ->where('users.id', '=', $id_user)
                ->first();
        return $res->status_token;
    }

    public function ExisteEmail($email, $id) {
        $data = User::where('email', '=', $email)->where('id', '!=', $id)->first();
        //var_dump($data); exit;
        return isset($data) ? true : false;
    }
	
	public function ShowAllIdEstablecimiento($id_establecimiento= 0) {
        $cad = "select T1.*,T2.nombre farmacia,T3.nombre consultorio,T4.nombre establecimiento 
		from users T1
		left join establecimientos T4 on T1.id_establecimiento=T4.id
		left join farmacias T2 on T1.id_farmacia=T2.id
		left join consultorios T3 on T1.id_consultorio=T3.id";
		if($id_establecimiento != 0) $cad.= " Where T1.status <> '3' and T1.id_establecimiento=".$id_establecimiento."";
		$cad.= "Order by paterno";
        $data = DB::select($cad);
        return $data;
    }

    public function GetUserWithDocProfileByDni($dni)
    {
        $cad = "";
    }

    public function listarUsuariosConPerfilImagenes($establecimiento) {
        $datos = DB::table('users')
                ->select('users.*')
                ->where('users.status', '=', 1)
                ->where('users.id_establecimiento', '=', $establecimiento)
                ->whereIn('users.tipo_registro',[6,7])
                ->get();
        return $datos;
    }
    
    public function GetUserByEstablecimiento($id) {
        $datos = DB::table('users')
                ->select('users.*')
                ->where('users.id_establecimiento', '=', $id)
                ->get();
        return $datos;
    }
    
}
