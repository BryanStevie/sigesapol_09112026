<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tv_area extends Model
{
    public function getEstablecimientoPorTvArea($id_tv_area){
        $datos=DB::table('tv_areas') 
                ->join('tvs','tv_areas.id_tv','=','tvs.id')
                ->join('establecimientos','tvs.id_establecimiento','=','establecimientos.id')
                ->select('tvs.id_establecimiento','establecimientos.nombre')
                ->get();
        return $datos;
    }

    public function getSubConsultoriosPorTvArea($id_tv_area){
        $datos=DB::table('tv_areas') 
                ->join('tv_area_detalles','tv_areas.id','=','tv_area_detalles.id_tv_area')
                ->join('sub_consultorios','tv_area_detalles.id_sub_consultorio','=','sub_consultorios.id')
                ->select('tv_area_detalles.id','sub_consultorios.id as id_sub_consultorio','sub_consultorios.descripcion')
                ->where('tv_areas.id','=',$id_tv_area)
                ->where('tv_area_detalles.id_estado','=','1')
                ->orderBy('tv_area_detalles.created_at','asc')
                ->get();
        return $datos;
    }
}