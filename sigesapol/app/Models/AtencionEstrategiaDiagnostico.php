<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtencionEstrategiaDiagnostico extends Model
{
    protected $table = 'atencion_estrategia_diagnostico';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_atencion_estrategia',
        'id_diagnostico',
        'id_tipo_diagnostico',
        'observacion'
    ];

    public function atencion()
    {
        return $this->belongsTo(AtencionEstrategiaSanitaria::class, 'id_atencion_estrategia');
    }

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class, 'id_diagnostico');
    }
}
