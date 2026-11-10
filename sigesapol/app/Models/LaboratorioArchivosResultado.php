<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratorioArchivosResultado extends Model
{
  protected $table = 'laboratorio_archivos_resultados';
  public $timestamps = false;
  protected $primaryKey = 'id'; 
  protected $fillable = [
    'id_prestacion_procedimiento',
    'uri',
    'original_name',
    'estado',
    'extension',
    'size',
    'created_at',
    'created_us',
    'updated_at',
    'updated_us'
  ];
}
