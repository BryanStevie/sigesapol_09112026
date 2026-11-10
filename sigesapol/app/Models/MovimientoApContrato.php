<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoApContrato extends Model
{
    protected $table = 'movimientos_ap_contratos';

    protected $fillable = [
        'movimiento_ap_id',
        'proveedor_id',
        'tipo_contrato',
        'numero_contrato',
        'fecha_contrato',
        'monto_contrato',
    ];

    /**
     * Relación: contrato pertenece a un movimiento AP
     */
    public function movimientoAp()
    {
        return $this->belongsTo(
            RequerimientoPfdm_MovimientosAp::class,
            'movimiento_ap_id'
        );
    }

    /**
     * Relación: contrato pertenece a un proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(
            Proveedor::class,
            'proveedor_id'
        );
    }
}
