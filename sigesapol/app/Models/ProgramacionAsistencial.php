<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramacionAsistencial extends Model
{
    protected $table = 'programacion_asistencial';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'periodo',
        'id_establecimiento',
        'id_personal',
        'id_clase',
        'id_tipo',
        'responsable_pnp',
        'responsable_susalud',
        'horas_programadas',
        'horas_efectivas',
        'estado',
    ];

    // Relación con establecimientos
    public function establecimiento()
    {
        return $this->belongsTo('App\Models\Establecimiento', 'id_establecimiento', 'id');
    }

    // Relación con personal (médico)
    public function personal()
    {
        return $this->belongsTo('App\Models\Medico', 'id_personal', 'id');
    }

    // Relación con clase de programación
    public function clase()
    {
        return $this->belongsTo('App\Models\ClaseProgramacion', 'id_clase', 'id_clase');
    }

    // Relación con tipo de programación
    public function tipo()
    {
        return $this->belongsTo('App\Models\TipoProgramacion', 'id_tipo', 'id_tipo');
    }
}
