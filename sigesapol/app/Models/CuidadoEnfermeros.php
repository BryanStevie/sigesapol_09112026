<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuidadoEnfermeros extends Model
{
    protected $table = 'cuidado_enfermeros';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_kardex_enfermeria', 
        'cfv', 
        'bhe',
        'cabecera',
        'id_oxigeno_terapia',
        'oxigenoterapia_cantidad',
        'movilizacion',
        'hemoglucotest'
    ];
}
