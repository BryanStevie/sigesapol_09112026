<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CqxObservacion extends Model
{
    protected $table = 'cqx_observaciones';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'fecha_registro',
        'hora_registro',
        'motivo',
        'id_intervencion',
        'created_us',
        'created_at',
    ];

    public function intervencion()
    {
        return $this->belongsTo(CqxIntervencion::class, 'id_intervencion', 'id');
    }
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'created_us','id');
    }
}