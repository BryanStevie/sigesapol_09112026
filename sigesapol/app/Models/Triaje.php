<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Triaje extends Model {

    public function ShowPrioridad(){
    	$cad = "select id, codigo, descripcion as prioridad  from prioridades f where estado = 'N' Order By id asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowTriaje($prioridad) {
//        $cad = "select id, codigo,id_servicio, descripcion from triajes where estado = 'N' and prioridad='". $prioridad ."' Order By descripcion Asc";
        $cad = "select id, codigo, descripcion from triajes where estado = 'N' and prioridad='". $prioridad ."' Order By descripcion Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowTriajes() {
        $cad = "select id, codigo, descripcion from triajes where estado = 'N' Order By descripcion Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllTriajes() {
        $cad = "select t.id, t.descripcion, t.estado, p.descripcion as prioridad from triajes t inner join prioridades p on p.id = t.prioridad Order By descripcion Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function getTopicoByIdPrioridad($prioridad) {
        $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'
	and prioridad='".$prioridad."' Order By descripcion Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function getTopicoByIdPrioridad_Sexo_edad($prioridad, $sexo, $edad) {
        if($edad>=15){
            $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'  
            and prioridad='".$prioridad."' and descripcion !='PEDIATRIA' Order By descripcion Asc";
            if($sexo=='M'){ 
                $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'  
                and prioridad='".$prioridad."' and descripcion !='GINECOLOGÍA Y OBSTETRICIA'  
                and descripcion !='PEDIATRIA' Order By descripcion Asc";
            }
        }else{ 
            $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'  
            and prioridad='".$prioridad."' Order By descripcion Asc";
            if($sexo=='M'){ 
                $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'  
                and prioridad='".$prioridad."' and descripcion !='GINECOLOGÍA Y OBSTETRICIA'
                Order By descripcion Asc";
            }
        } 

        $data = DB::select($cad);
        return $data;
    }
    public function getTopicoByIdPrioridad_Sexo_edad_triaje($prioridad, $sexo, $edad) {
        if($edad>=15){
            $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'  
            and prioridad='".$prioridad."' and descripcion !='PEDIATRIA' and descripcion !='UNIDAD VIGILANCIA INTENSIVA' Order By descripcion Asc";
            if($sexo=='M'){ 
                $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'  
                and prioridad='".$prioridad."' and descripcion !='GINECOLOGÍA Y OBSTETRICIA'  
                and descripcion !='PEDIATRIA' and descripcion !='UNIDAD VIGILANCIA INTENSIVA' Order By descripcion Asc";
            }
        }else{ 
            $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'  
            and prioridad='".$prioridad."' Order By descripcion Asc";
            if($sexo=='M'){ 
                $cad = "select id, codigo, descripcion, id_servicio from triajes where estado = 'N'  
                and prioridad='".$prioridad."' and descripcion !='GINECOLOGÍA Y OBSTETRICIA' and descripcion !='UNIDAD VIGILANCIA INTENSIVA'
                Order By descripcion Asc";
            }
        } 

        $data = DB::select($cad);
        return $data;
    }
    public function ShowConsultorios(){
        $cad = "select id, codigo, descripcion as nombre  from prioridades f where estado = 'N' Order By id asc";
        $data = DB::select($cad);
        return $data;
    }

    public function getConsultorioPorIdTriaje($id_servicio){
        $cad = "select * from triajes t where t.id = ".$id_servicio." and t.estado = 'N' limit 1";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowTriajeAsignacion($prioridad, $id_asignacion_servicio) {
        $cad = "select a.id, t.codigo,t.id_servicio, t.descripcion 
                from triajes t
                inner join asignacion_servicios a on a.id_prioridad = t.prioridad 
                where t.estado = 'N' and t.prioridad='". $prioridad ."' and a.id='". $id_asignacion_servicio ."' Order By t.descripcion Asc";
        $data = DB::select($cad);
        return $data;
    }
    
        public function getConsultorioPorIdTriaje_TODO($id_servicio){
        $cad = "select * from triajes t where t.id = ".$id_servicio." limit 1";
        $data = DB::select($cad);
        return $data;
    }

   public function obtenerTriajeLaoratorio($id_establecimiento){
        $cad = "SELECT DISTINCT ON (descripcion) *
                FROM triajes
                WHERE id_establecimiento = $id_establecimiento
                ORDER BY descripcion, id;";
        $data = DB::select($cad);
        return $data;
    }

}
