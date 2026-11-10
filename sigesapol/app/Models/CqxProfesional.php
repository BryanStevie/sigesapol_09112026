<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CqxProfesional extends Model
{
    protected $table = 'cqx_profesionales';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_cqx_intervenciones',
        'id_intraoperatorio',
        'id_medico',
        'id_tipo_professional',
        'flag_validacion',
        'flag_personal',
        'estado',
        'motivo_anulacion',
        'created_us',
        'created_at',
        'updated_us',
        'updated_at',
        'deleted_us',
        'deleted_at',
        'id_cqx_cita',
        'id_cqx_anestesia',
    ];

    // IMPORTARTE : AGREGAR $tipo_personal EN 0 PARA CIRUJANOS EN FORM ATENCION
    public function RegistrarProfesionalesPreQx($id_intervencion_quirurgica, $id_medico, $id_cargo, $id_usuario, $tipo_personal, $id_cqx_cita) {
        $profesional = new CqxProfesional();
        $profesional->id_cqx_intervenciones = $id_intervencion_quirurgica;
        $profesional->id_medico = $id_medico;
        $profesional->id_tipo_professional = $id_cargo;
        $profesional->flag_validacion = 1;
        // $profesional->flag = 1;
        $profesional->flag_personal = $tipo_personal;
        $profesional->created_us = $id_usuario;
        $profesional->estado = 0;
        if ($id_cqx_cita != 0) {
            $profesional->id_cqx_cita = $id_cqx_cita;
        }
        $profesional->save();
    }

    public function GetByIdIntervencionQuirugicaYTipoPersonal($id_intervencion_quirurgica, $tipo_personal)
    {
        $profesionales = DB::table('cqx_profesionales as p')
            ->join('medicos as m', 'p.id_medico', '=', 'm.id')
            ->join('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
            ->where('p.id_cqx_intervenciones', $id_intervencion_quirurgica)
            ->where('p.flag_personal', $tipo_personal)
            ->where('p.estado', 0)
            ->select('p.*', 'm.paterno', 'm.materno', 'm.nombre', 'tp.descripcion as tipo_profesional')
            ->get();
        return $profesionales;
    }

    public function GetByIdCqxCita($id_cqx_cita)
    {
        $profesionales = DB::table('cqx_profesionales as p')
            ->join('medicos as m', 'p.id_medico', '=', 'm.id')
            ->join('cqx_tipo_professionales as t', 'p.id_tipo_professional', '=', 't.id')
            ->where('p.id_cqx_cita', $id_cqx_cita)
            ->where('p.estado', 0)
            ->select('p.*','m.id as id_medico','m.paterno', 'm.materno', 'm.nombre', 't.descripcion as tipo_profesional')
            ->get();
        return $profesionales;
    }
    public function GetByIdCqxIdCita($id_cita,$id_cqx_cita)
    {
        $profesionales = DB::table('cqx_profesionales as p')
            ->join('medicos as m', 'p.id_medico', '=', 'm.id')
            ->join('cqx_tipo_professionales as t', 'p.id_tipo_professional', '=', 't.id')
            ->where(function ($q) use ($id_cita, $id_cqx_cita) {
                $q->where('p.id_cqx_cita', $id_cita)
                ->orWhere('p.id_cqx_cita', $id_cqx_cita);
            })
            ->where('p.estado', 0)
            ->select(
                'p.*',
                'm.id as id_medico',
                'm.paterno',
                'm.materno',
                'm.nombre',
                't.descripcion as tipo_profesional'
            )
            ->get();
        return $profesionales;
    }
}
