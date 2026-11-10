<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProcedimientoDiagnotico extends Model
{
    protected $table = 'procedimientos_diagnoticos';
    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = false;

    protected $fillable = [
        'id_prestacion',
        'id_procedimientos',
        'id_diagnosticos',
        'estado',
        'id_evaluacion',
        'id_interconsulta',
        'id_intraoperatorio',
        'id_hora_recuperacion',
        'id_consejeria',
        'created_at',
        'created_us',
        'updated_at',
        'updated_us',
        'deleted_at',
        'deleted_us'
    ];

    public function getByPrestacionIdAndTipo($prestacionId, $tipoProcedimiento)
    {
      $query = "(select pd.id_prestacion, p2.fecha_atencion::date as fecha, pd.id_procedimientos, p.codigo, p.descripcion 
                from procedimientos_diagnoticos pd 
                inner join procedimientos p on pd.id_procedimientos = p.id
                inner join prestaciones  p2 on pd.id_prestacion = p2.id
                where p.tipo_procedimiento = $tipoProcedimiento and pd.id_prestacion = $prestacionId)
                union
                (select pp.id_prestacion, pp.created_at::date as fecha, pp.id_procedimiento, p2.codigo, p2.descripcion
                from prestacion_procedimientos pp inner join procedimientos p2 on pp.id_procedimiento = p2.id
                where p2.tipo_procedimiento = $tipoProcedimiento and pp.id_prestacion = $prestacionId);";

      $data = DB::select($query);
      return $data;
    }
}