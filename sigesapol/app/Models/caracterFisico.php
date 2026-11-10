<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaracterFisico extends Model
{
    protected $table = 'caracter_fisicos';

    public function enfermeriaDetalles()
    {
        return $this->hasMany(EnfermeriaDetalle::class, 'id_caracter_fisico');
    }

    public function signosVitalesDetalles()
    {
        return $this->hasMany(SignosVitalesDetalles::class, 'id_caracter_fisico');
    }
}