<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoreo_detalles extends Model
{
    protected $table = 'monitoreo_detalles';

    protected $fillable = [
        'id_monitoreo',
        'id_caracter_fisico',
        'hora_monitoreo',
        'descripcion',
        'id_turno',
        'fecha_firma',
        'ruta_firma',
        'flag_firma',
        'created_at',
        'created_us',
        'updated_at',
        'updated_us'
    ];

    public static function getInfusiones($id_monitoreo, $fecha_hora)
    {
        
        $infusiones = Monitoreo_detalles::join('caracter_fisicos', 'caracter_fisicos.id', '=', 'monitoreo_detalles.id_caracter_fisico')
            ->where('monitoreo_detalles.id_monitoreo', $id_monitoreo)
            ->where('caracter_fisicos.keyword', 'infusiones')
            ->where('monitoreo_detalles.hora_monitoreo', $fecha_hora)
            ->select('monitoreo_detalles.id', 'caracter_fisicos.keyword', 'monitoreo_detalles.descripcion')
            ->first();
        return $infusiones;
    }

    public static function getDetallePorKeyword($id_monitoreo, $fecha_hora, $keyword)
    {
        $detalle = Monitoreo_detalles::join('caracter_fisicos', 'caracter_fisicos.id', '=', 'monitoreo_detalles.id_caracter_fisico')
            ->where('monitoreo_detalles.id_monitoreo', $id_monitoreo)
            ->where('caracter_fisicos.keyword', $keyword)
            ->where('monitoreo_detalles.hora_monitoreo', $fecha_hora)
                        // ACA DEBE ESTAR INCLUIDO EL CAMPO ID_TURNO
            ->select('monitoreo_detalles.id', 'caracter_fisicos.keyword', 'monitoreo_detalles.descripcion', 'monitoreo_detalles.id_turno')
            ->first();
        return $detalle;
    }

    public function monitoreo() 
    {
        return $this->belongsTo(Monitoreo::class, 'id');
    }
}
