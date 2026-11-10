<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequerimientoPfdm extends Model
{
    protected $table = "requerimientos_pfdm";

    protected $fillable = [
        'numero_requerimiento',
        'tipo_documento',
        'numero_oficio',
        'fecha_oficio',
        'numero_informe',
        'fecha_informe',
        'id_tipo_requerimiento',
        'observacion_requerimiento',
        'prioridad',
        'observacion_prioridad',
        'id_procedimiento_seleccion',
        'id_tipo_proceso',
        'id_tipo_producto',
        'id_servicio',
        'numero_pedido_siga',
        'numero_sgd_ht',
        'denominacion',
        'id_unidad_destino',
        'id_etapa',
        'id_responsable',
        'unidad_medida',
        'cantidad_total',
        'id_producto',
        'created_us',
        'updated_us',
        'estado_id',
        'etapa_id',
        'ubicacion_id',
        'proceso_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id');
    }

    public function tipoProducto()
    {
        return $this->belongsTo(Tipo_producto::class, 'id_tipo_producto', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(RequerimientoPfdm_detalle::class, 'id_requerimiento', 'id');
    }

    public function movimientosAp()
    {
        return $this->hasOne(RequerimientoPfdm_MovimientosAp::class, 'requerimiento_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Requerimientos_movimientos::class, 'requerimiento_id', 'id');
    }
}
