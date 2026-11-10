<?php

namespace App\Models;

use App\Models\Monitoreo_detalles;
use App\Models\Enfermeria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Monitoreo extends Model
{
    protected $table = 'monitoreos';

    protected $fillable = [
        'id_enfermeria',
        /* 'fecha_firma',
        'ruta_firma',
        'flag_firma', */
        'created_us',
        'updated_us',
        'created_at',
        'updated_at',
    ];

    public function lista_monitoreos_por_turno($id_enfermeria, $fechaInicial, $fechaFinal, $id_turno){
        $monitoreos = DB::select('SELECT * FROM sp_get_monitoreos(?, ?, ?, ?)', [$id_enfermeria, $fechaInicial, $fechaFinal, $id_turno]);
		return $monitoreos;
	}

    public function lista_monitoreos_por_turno_hospi($id_enfermeria, $fechaInicial, $fechaFinal, $id_turno){
        $monitoreos = DB::select('SELECT * FROM sp_get_monitoreos_hospitalizacion(?, ?, ?, ?)', [$id_enfermeria, $fechaInicial, $fechaFinal, $id_turno]);
		return $monitoreos;
	}

    public function allMonitoreosPorIdAsegurado($asegurado_id, $tipo_atencion_id)
    {
        if ($tipo_atencion_id == 2) {
            $monitoreos = DB::select('SELECT * FROM sp_get_monitoreos_id_asegurado(?)', [$asegurado_id]);
        } else if ($tipo_atencion_id == 3) {
            $monitoreos = DB::select('SELECT * FROM sp_get_monitoreos_hospitalizacion_id_asegurado(?)', [$asegurado_id]);
        }
		return $monitoreos;
    }

    public function lista_monitoreos_por_dia($id_enfermeria, $fechaFinal){
        $monitoreos = DB::select('SELECT * FROM sp_get_monitoreos_all(?, ?, ?)', [$id_enfermeria, $fechaFinal, $fechaFinal]);
		return $monitoreos;
	}

    public function lista_monitoreos_por_dia_hospi($id_enfermeria, $fechaFinal){
        $monitoreos = DB::select('SELECT * FROM sp_get_monitoreos_hospitalizaciones_all(?, ?, ?)', [$id_enfermeria, $fechaFinal, $fechaFinal]);
		return $monitoreos;
	}

    public function buscarRegistrosDeUnMonitoreoPorHora($id_monitoreo, $fecha_hora, $id_usuario)
    {
        $monitoreoDetalles = Monitoreo_detalles::from('monitoreo_detalles as md')
                        ->join('caracter_fisicos as cf', 'cf.id', '=','md.id_caracter_fisico') 
                        ->where('md.id_monitoreo', $id_monitoreo)
                        ->where('md.created_us', $id_usuario) // SI O SI DEBE SER REGISTROS DEL USUARIO
                        ->where('md.hora_monitoreo', $fecha_hora)
                        ->select('md.id_monitoreo', 'cf.keyword', 'md.descripcion', 'md.hora_monitoreo', 'md.id_turno as turno')
                        ->get();
        return $monitoreoDetalles;
    }

    public function detallarMonitoreo($id_monitoreo, $fecha_hora/* , $id_turno, $id_usuario */)
    {
        $monitoreo = Monitoreo_detalles::from('monitoreo_detalles as md')
                        ->join('caracter_fisicos as cf', 'cf.id', '=','md.id_caracter_fisico') 
                        ->where('md.id_monitoreo', $id_monitoreo)
                        /* ->where('md.created_us', $id_usuario) */
                        ->where('md.hora_monitoreo', $fecha_hora)
                        /* ->where('md.id_turno', $id_turno) */
                        ->select('md.id', 'md.id_monitoreo', 'cf.keyword', 'md.descripcion', 'md.hora_monitoreo', 'md.id_turno as turno', 'md.ruta_firma')
                        ->get();
        return $monitoreo;
    }

    public function notaEnfermeria() 
    {
        return $this->belongsTo(Enfermeria::class, 'id');
    }

    public function monitoreoDetalles()
    {
        return $this->hasMany(Monitoreo_detalles::class, 'id_monitoreo');
    }
}