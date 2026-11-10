<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Beneficiario_oncologias extends Model
{

    //
    /*public function ShowAll() {
        $cad = "select T1.*,T2.nombre farmacia,T3.nombre consultorio,T4.nombre establecimiento 
from users T1
left join establecimientos T4 on T1.id_establecimiento=T4.id
left join farmacias T2 on T1.id_farmacia=T2.id
left join consultorios T3 on T1.id_consultorio=T3.id
order by paterno";
        $data = DB::select($cad);
        return $data;
    }
	
    public function GetByBeneficiario($id) {
        
        $beneficiario=DB::table('beneficiarios3')
                    ->select('beneficiarios3.*')
                    ->where('dni_titular',$id)
                    ->first();                          
        
        return $beneficiario;
    } */

}


