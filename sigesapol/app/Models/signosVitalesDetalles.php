<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class signosVitalesDetalles extends Model
{
    protected $table = 'signo_vitales_detalles';
    public $timestamps = true;

    protected $fillable = [
        'id_signo_vital',
        'id_caracter_fisico',
        'id_turno',
        'descripcion',
        'created_us',
        'created_at'
    ];

    public function signosVitales()
    {
        return $this->belongsTo(SignosVitales::class, 'id_signo_vital');
    }

    public function caracterFisico()
    {
        return $this->belongsTo(CaracterFisico::class, 'id_caracter_fisico');
    }
}



