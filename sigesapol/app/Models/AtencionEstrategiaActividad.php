<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtencionEstrategiaActividad extends Model
{
    protected $table = 'atencion_estrategia_actividad';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
       
        'id_estrategia',
        'id_atencion_estrategia',
        'observacion'
    ];

    public function atencion()
    {
        return $this->belongsTo(AtencionEstrategiaSanitaria::class, 'id_atencion_estrategia');
    }

    public function estrategia()
    {
        return $this->belongsTo(EstrategiaSanitaria::class, 'id_estrategia');
    }
}
