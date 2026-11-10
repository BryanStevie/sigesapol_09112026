<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SolicitudProcedimiento extends Model
{
    
    public function obtenerSolicitudProcedimientoPorId($id_solicitud_procedimiento)
    {
        $cad = "Select * from solicitud_procedimiento where id = " . $id_solicitud_procedimiento;
        $data = DB::select($cad);
        return $data;
    }
}
