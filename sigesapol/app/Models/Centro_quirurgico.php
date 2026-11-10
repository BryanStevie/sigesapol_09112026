<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Centro_quirurgico extends Model
{
	function getNroQuirurgico($id_establecimiento,$dni_beneficiario){
		$cad = "select LPAD(((count(*)+1)::text),2,'0')nro_quirurgico 
from centro_quirurgicos T1
inner join farmacias T2 on T1.id_farmacia = T2.id 
inner join admisiones T3 on T1.id_admision=T3.id 
where T2.id_establecimiento = ".$id_establecimiento." 
and T1.estado = 1 
and T3.dni_beneficiario='".$dni_beneficiario."'
and ano = extract(year from now())::text";
		$data = DB::select($cad);
        return $dni_beneficiario.$data[0]->nro_quirurgico;
	}
    //
	
   	public function GetAdmisionByNroAdmision($nro_admision,$id_establecimiento) {
        
        $cad = "SELECT T1.*,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.nro_admision = '".$nro_admision."'";
		
		$cad .= " AND T4.id_establecimiento=".$id_establecimiento;
		
        $data = DB::select($cad);
        return $data[0];
    }
    
	public function GetAdmisionByIdFarmaciaAndDni($id_establecimiento,$dni) {
        
         $cad = "SELECT T1.*
,coalesce(T1.cantidad_receta,1)cantidad_receta
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' when turno='MD' then 'MADRUGADA' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id 
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.dni_beneficiario = '".$dni."'
and T4.id_establecimiento = ".$id_establecimiento;
		
		$cad .= " AND coalesce(T1.cantidad_receta,1) > coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)";
		$cad .= " ORDER BY T1.id desc ";
		//echo $cad;
        $data = DB::select($cad);
        return $data;
    }
	
	public function GetAdmisionById($id) {
        
         $cad = "SELECT T1.*
,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' when turno='MD' then 'MADRUGADA' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id 
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.id = ".$id;
		$cad .= " ORDER BY T1.id desc ";
        $data = DB::select($cad);
        return $data;
    }
	
    function obtenerIntervencionesNivel2($p) {
        $result = DB::select('SELECT * FROM sp_obtener_intervencionesqx_nivel_2(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $p);
        return $result;
    }
}
