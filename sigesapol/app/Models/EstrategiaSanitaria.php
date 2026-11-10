<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstrategiaSanitaria extends Model
{
    protected $table = 'estrategia_sanitaria';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'estado',
    ];
}
    