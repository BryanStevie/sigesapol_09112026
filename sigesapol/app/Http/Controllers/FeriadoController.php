<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Feriado;
use Carbon\Carbon;
use DB;

class FeriadoController extends Controller
{
    /**
     * Valida si la fecha actual es feriado
     */
    public function validar_fecha_actual_es_feriado()
    {
        $es_feriado = Feriado::whereRaw('EXTRACT(MONTH FROM fecha) = EXTRACT(MONTH FROM CURRENT_DATE)')
                            ->whereRaw('EXTRACT(DAY FROM fecha) = EXTRACT(DAY FROM CURRENT_DATE)')
                            ->where('estado', 'A')
                            ->exists();

        return $es_feriado ? 1 : 0;
    }

    public function get_datos_dia_actual() {
        $datos_raw = DB::select("
            SELECT 
                CAST(NOW() AS DATE) AS fecha_actual,
                EXTRACT(DOW FROM NOW()) AS dia_semana_numero,
                TO_CHAR(NOW(), 'HH24:MI:SS') AS hora_actual,
                EXISTS (
                    SELECT 1 
                    FROM feriados 
                    WHERE estado = 'A'
                    AND EXTRACT(MONTH FROM fecha) = EXTRACT(MONTH FROM CURRENT_DATE)
                    AND EXTRACT(DAY FROM fecha) = EXTRACT(DAY FROM CURRENT_DATE)
                ) AS es_feriado
        ");
        $datos = count($datos_raw) > 0 ? $datos_raw[0] : null;
        return json_encode($datos);
    }
    
}
