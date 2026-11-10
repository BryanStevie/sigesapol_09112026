<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CategoriaRuaNivel extends Model
{
  protected $table = 'categoria_rua_nivels';
  public $timestamps = false;
  protected $primaryKey = 'id'; 
  protected $fillable = [
      'id_nivel',
      'id_atencion',
      'id_categoria_rua',
      'orden',
      'estado',
      'id_user_created',
      'id_user_update',
      'created_at',
      'updated_at',
      'id_servicio'
  ];

  function getByNivelIdAtencion($id_nivel, $id_tipo_atencion) {
    $categoriasRuas = DB::select("
                              SELECT 
                                  categoria_rua_nivels.id AS id_categoria_rua_nivels,
                                  cr.nombre AS categoria_rua_nombre
                              FROM categoria_rua_nivels
                              INNER JOIN categoria_ruas AS cr ON categoria_rua_nivels.id_categoria_rua = cr.id
                              WHERE categoria_rua_nivels.id_nivel = ?
                                  AND categoria_rua_nivels.id_atencion = ?
                                  AND categoria_rua_nivels.estado = 1
                          ", [$id_nivel, $id_tipo_atencion]);
    return $categoriasRuas;
  }
}
