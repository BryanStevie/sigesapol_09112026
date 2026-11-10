<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CqxProcedimiento extends Model
{
    // protected $table = "cqx_procedimientos";
    protected $table = 'cqx_procedimientos';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_cqx_intervenciones',
        'id_procedimiento',
        'flag',
        'id_estado_reg',
        'created_us',
        'created_at',
        'updated_us',
        'updated_at',
        'id_cqx_cita',
        'flag_pre_post',
        'flag_examen_patologia',
    ];
    
    public function RegistrarProcedimientoPreQx($id_intervencion_quirurgica, $id_procedimiento, $id_usuario, $id_cqx_cita, $flag_pre_post,$flag_examen_patologia) {
        $profesional = new CqxProcedimiento();
        $profesional->id_cqx_intervenciones = $id_intervencion_quirurgica;
        $profesional->id_procedimiento = $id_procedimiento;
        $profesional->flag = 1;
        $profesional->flag_pre_post = $flag_pre_post;//1 pre operatorio; 2 post operatorio
        $profesional->created_us = $id_usuario;
        $profesional->id_cqx_cita = $id_cqx_cita;
        $profesional->flag_examen_patologia = $flag_examen_patologia;
        return $profesional->save();
    }

    public function GetByIdIntervencionQuirugica($id_intervencion_quirurgica)
    {
        $procedimientos = DB::table('cqx_procedimientos as p')
            ->join('procedimientos as p2', 'p.id_procedimiento', '=', 'p2.id')
            ->where('p.id_cqx_intervenciones', $id_intervencion_quirurgica)
            ->where('p.flag_examen_patologia', false)
            ->where('p.id_estado_reg', 1)
            ->select('p.*', 'p2.codigo as codigo_cpms', 'p2.descripcion as descripcion_cpms')
            ->get();
        return $procedimientos;
    }

    public function GetByIdCqxCita($id_cqx_cita)
    {
        $procedimientos = DB::table('cqx_procedimientos as p')
            ->join('procedimientos as p2', 'p.id_procedimiento', '=', 'p2.id')
            ->where('p.id_cqx_cita', $id_cqx_cita)
            ->where('p.flag_examen_patologia', false)
            ->where('p.id_estado_reg', 1)
            ->select('p.*', 'p2.codigo as codigo_cpms', 'p2.descripcion as descripcion_cpms')
            ->get();
        return $procedimientos;
    }
    public function GetByIdCqxIdCita($id_cita,$id_cqx_cita)
    {
        $procedimientos = DB::table('cqx_procedimientos as p')
            ->join('procedimientos as p2', 'p.id_procedimiento', '=', 'p2.id')
            ->where(function ($q) use ($id_cita, $id_cqx_cita) {
                $q->where('p.id_cqx_cita', $id_cita)
                ->orWhere('p.id_cqx_cita', $id_cqx_cita);
            })
            ->where('p.flag_examen_patologia', false)
            ->where('p.id_estado_reg', 1)
            ->select('p.*', 'p2.codigo as codigo_cpms', 'p2.descripcion as descripcion_cpms')
            ->get();
        return $procedimientos;
    }
}
