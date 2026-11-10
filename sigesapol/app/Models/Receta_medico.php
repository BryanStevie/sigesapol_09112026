<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Receta_medico extends Model {

    public function CirujanonByIdReceta($id_receta_vale) {
        $data = DB::table('receta_medicos')
                ->join('medicos', 'receta_medicos.id_medico', '=', 'medicos.id')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->where('receta_medicos.id_receta_vale', '=', $id_receta_vale)
                ->where('receta_medicos.estado', '=', 1)
                ->select('medicos.*', 'especializaciones.nombre as nombre_especialidad', 'receta_medicos.id as id_receta_medico')
                ->get();
        return $data;
    }

    public function AnestesiologoByIdReceta($id_receta_vale) {
        $data = DB::table('receta_vales')
                ->join('medicos', 'receta_vales.id_anestesiologo', '=', 'medicos.id')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->where('receta_vales.id', '=', $id_receta_vale)
                ->where('receta_vales.estado', '=', 1)
                ->select('medicos.nombre as nombre_anestesiologo','medicos.paterno as paterno_anestesiologo','medicos.materno as materno_anestesiologo ', 'especializaciones.nombre as nombre_especialidad')
                ->get();

        $data_count = DB::table('receta_vales')
                ->join('medicos', 'receta_vales.id_anestesiologo', '=', 'medicos.id')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->where('receta_vales.id', '=', $id_receta_vale)
                ->where('receta_vales.estado', '=', 1)
                ->select('medicos.nombre as nombre_anestesiologo','medicos.paterno as paterno_anestesiologo','medicos.materno as materno_anestesiologo ', 'especializaciones.nombre as nombre_especialidad')
                ->count();
        if($data_count==0)
            return $data;
        else
            return $data[0];
    }

    public function obtenerProfesionalesPorEspecialidad($nombre_especializacion) {
        $data=DB::table('medicos')
                ->join('especializaciones','medicos.id_especializacion','=','especializaciones.id')
                ->where('especializaciones.nombre','=',$nombre_especializacion)
                ->select('medicos.*')
                ->get();
        return $data;
    }
}
