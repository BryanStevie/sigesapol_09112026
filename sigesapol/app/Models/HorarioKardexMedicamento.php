<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioKardexMedicamento extends Model
{
    protected $table = 'horarios_kardex_medicamentos';
    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_kardex', 
        'id_medicamento',
        'hora',
        'aplicacion',
        'ram',
        'frasco',
        'observacion',
        'created_us',
        'created_at',
        'updated_us',
        'updated_at',
        'fecha'
    ];
}