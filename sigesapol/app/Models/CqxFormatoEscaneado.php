<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class CqxFormatoEscaneado extends Model
{
  protected $table = "cqx_formato_escaneados";
  protected $primaryKey = 'id';
  public $timestamps = false;
  protected $fillable = [
    'id_tipo_formato',
    'id_intervencion',
    'id_asegurado',
    'tipo_documento',
    'tamano_archivo',
    'fecha_creacion',
    'hora_creacion',
    'created_us',
    'estado',
    'ruta',
    'nombre_archivo',
    'updated_at',
    'created_at',
    'flag_origen_escaneado',
    'nombre_simple',
  ];
  
 public function tipoFormato()
  {
      return $this->belongsTo(CqxFormatoTipo::class, 'id_tipo_formato');
  }
}
