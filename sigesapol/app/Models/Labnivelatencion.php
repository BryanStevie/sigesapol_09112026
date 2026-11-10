<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Labnivelatencion extends Model
{
  protected $table = 'labnivelatenciones';
  public $timestamps = false;
  protected $primaryKey = 'id'; 
  protected $fillable = [
      'id_procedimiento',
      'codigo_lab',
      'genero',
      'id_tipo_atencion',
      'id_nivel',
      'id_categoria',
      'estado',
      'descripcion_corta',
      'id_user_created',
      'id_user_update',
      'created_at',
      'updated_at',
      'id_consultorio',
      'codigo_proveedor',
      'cpms_personalizado',
      'id_tipo_muestra'
  ];

  function getProcedimientosNivelTipoAtencionCategoria($id_nivel, $id_tipo_atencion, $id_categoria) {
    $procedimientos = DB::select("
                                  SELECT 
                                      l.id AS id_labnivelatencione,
                                      l.descripcion_corta,
                                      p.id AS id_procedimiento,
                                      p.codigo AS procedimiento_codigo,
                                      p.descripcion AS procedimiento_descripcion,
                                      l.id_categoria
                                  FROM  labnivelatenciones l
                                  INNER JOIN procedimientos p ON l.id_procedimiento = p.id
                                  WHERE 
                                      l.id_nivel = ?
                                      AND l.id_tipo_atencion = ?
                                      AND l.estado = 1
                                      AND p.estado = 1
                                      AND l.id_categoria = ?
                              ", [$id_nivel, $id_tipo_atencion, $id_categoria]);

    if ($procedimientos && count( $procedimientos) > 0) {
      return  $procedimientos;
    }
    return [];
  }
}
