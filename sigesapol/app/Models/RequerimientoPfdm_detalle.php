<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequerimientoPfdm_detalle extends Model
{
    protected $table = "requerimientos_pfdm_detalle";

    public $timestamps = false;

    protected $fillable = [
        'id_requerimiento',
        'numero_entrega',
        'cantidad_parcial',
        'numero_dias',
        'fecha_internamiento',
        'observacion',
        'estado'
    ];

    public function requerimiento()
    {
        return $this->belongsTo(RequerimientoPfdm::class, 'id_requerimiento', 'id');
    }
}
