<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaseProgramacion extends Model
{
    protected $table = 'clase_programacion';
    protected $primaryKey = 'id_clase';
    public $timestamps = true;

    protected $fillable = [
        'nombre_clase', 'estado'
    ];

    public function tipos()
    {
        return $this->hasMany(TipoProgramacion::class, 'id_clase', 'id_clase');
    }
}







