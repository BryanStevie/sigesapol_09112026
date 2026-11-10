<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProgramacion extends Model
{
    protected $table = 'tipo_programacion';
    protected $primaryKey = 'id_tipo';
    public $timestamps = true;

    protected $fillable = [
        'id_clase', 'nombre_tipo', 'estado'
    ];

    public function clase()
    {
        return $this->belongsTo(ClaseProgramacion::class, 'id_clase', 'id_clase');
    }
}
