<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class CqxDiagnosticoNic extends Model
{
  protected $table = 'cqx_diagnostico_nics';
  
  
  public function intervencion_enfermeria(){
    $nics = DB::select("
       SELECT 
        p.id,
        p.descripcion,
        ARRAY_AGG(h.id || '|' || h.descripcion) AS hijos
      FROM 
          cqx_diagnostico_nics p
      LEFT JOIN 
          cqx_diagnostico_nics h ON h.parent = p.id
      WHERE 
          p.parent = 0 
          AND p.flag = 1
      GROUP BY 
          p.id, p.descripcion
      ORDER BY 
        p.id
    ");
    foreach ($nics as $nic) {
      $hijos = [];
  
      // Convertir la cadena a array si es necesario
      if (!empty($nic->hijos) && is_string($nic->hijos)) {
          // Quitar los corchetes si existen y dividir la cadena en elementos individuales
          $hijosArray = explode(',', trim($nic->hijos, '{}'));
  
          foreach ($hijosArray as $hijo) {
              $hijoData = explode('|', $hijo);
              
              if (count($hijoData) === 2) {
                  $hijos[] = [
                    'id' => trim($hijoData[0], '"'), // Quitar comillas
                    'descripcion' => trim($hijoData[1], '"') // Quitar comillas
                  ];
              }
          }
      }
      // Guardar hijos procesados en el objeto
      $nic->hijos = $hijos;
    }
    return $nics;

  }

}
