<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoHcDetalle extends Model
{
    protected $table = 'movimiento_hc_detalle';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_movimiento_hc_solicitud',
        'id_asegurado_historia',
        'estado',
        'id_movimiento_hc_recepcion',
        'observacion_archivo_recepcion',
        'id_estado_reg',
        'created_at',
        'created_us',
        'updated_at',
        'updated_us',
        'deleted_at',
        'deleted_us',
    ];
}
