<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtencionEstrategiaSanitaria extends Model
{
    protected $table = 'atencion_estrategia_sanitaria';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_establecimiento',
        'id_tipo_atencion',
        'estado',
        'id_upss',
        'id_medico',
        'id_asegurado',
        'ugipress',
        'nro_registro',
        'fecha_atencion',
        'fecha_registro',
        'periodo',
        'cant_personas',
        'tipo_estrategia',
        'estado_estrategia',
        'periodo'
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento');
    }

    public function tipoAtencion()
    {
        return $this->belongsTo(TipoAtencione::class, 'id_tipo_atencion');
    }

    public function upss()
    {
        return $this->belongsTo(Upss::class, 'id_upss');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_medico');
    }

    public function asegurado()
    {
        return $this->belongsTo(Asegurado::class, 'id_asegurado');
    }

    public function diagnosticos()
    {
        return $this->hasMany(AtencionEstrategiaDiagnostico::class, 'id_atencion_estrategia');
    }

    public function actividades()
    {
        return $this->hasMany(AtencionEstrategiaActividad::class, 'id_atencion_estrategia');
    }

    public function getDateFormat()
{
    return 'Y-m-d';
}
}
