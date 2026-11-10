<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestacion_eventos extends Model
{
    protected $table = 'prestacion_eventos';
    protected $fillable = [
    'id_prestacion',
    'id_evento',
    'id_evolucion',
    'estado',
    'created_us',
    'updated_us'
  ];
}
