<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requerimientos_movimientos extends Model
{
    protected $table = 'requerimiento_pfdm_movimientos';

    public $timestamps = false;

    protected $fillable = [
        'requerimiento_id',
        'estado_id',
        'etapa_id',
        'ubicacion_id',
        'proceso_id',
        'orden',
        'id_registrador',
        'fecha_ingreso',
        'observacion'
    ];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    public function requerimiento()
    {
        return $this->belongsTo(RequerimientoPfdm::class);
    }

    public function estado()
    {
        return $this->belongsTo(Requerimiento_estados::class);
    }

    public function etapa()
    {
        return $this->belongsTo(Requerimiento_etapas::class);
    }

    public function ubicacion()
    {
        return $this->belongsTo(Requerimiento_ubicaciones::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
