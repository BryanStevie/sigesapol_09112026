<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequerimientoPfdm_MovimientosAp extends Model
{
    protected $table = "requerimiento_pfdm_movimientos_ap";

    protected $fillable = [
        'requerimiento_id',
        'etapa',
        'observado',
        'tipo_movimiento',
        'observacion',
        'created_us',
    ];

    public function requerimiento()
    {
        return $this->belongsTo(RequerimientoPfdm::class, 'requerimiento_id');
    }

    public function contrato()
    {
        return $this->hasOne(MovimientoApContrato::class, 'movimiento_ap_id');
    }
}
