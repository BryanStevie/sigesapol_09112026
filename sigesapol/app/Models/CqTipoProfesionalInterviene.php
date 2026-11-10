<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class CqTipoProfesionalInterviene extends Model
{
  protected $table = 'cq_tipo_profesional_interviene';

  public function medicosByEspecialidadConsultorio($nombre, $id_ipress)
  {
      // Construcción de la consulta principal
      $query = Medico::join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      ->whereRaw("CONCAT(medicos.colegiatura, ' ',medicos.dni, ' ',medicos.nombre, ' ',medicos.paterno, ' ',medicos.materno) ILIKE ?", ['%' . $nombre . '%'])
      ->where('medicos.estado', 1)
      ->where('medicos.id_establecimiento', $id_ipress);
      
      // Si la especialización no es 8, aplicar LEFT JOIN con medico_consultorios y consultorios
          $query->leftJoin('medico_consultorios as mc', 'medicos.id', '=', 'mc.id_medico')
                ->leftJoin('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
                ->where('c.flag_quirurgico', 1);
     
      // Finalizar consulta y obtener los resultados
      $medicos = $query->distinct()
      ->select('medicos.*', 'especializaciones.nombre as especializacion')
      ->orderBy('medicos.paterno', 'asc')
      ->limit(50)
      ->get();

      return $medicos;
  }

}

