<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Siteds extends Model
{
    public function getUltimoCodigoSitedsEnElDia($id_ext, $id_tipo_atencion, $tipo_atencion_ultima) {
        $cod_siteds_susalud = "";
        switch ($tipo_atencion_ultima) {
        case 'prestacion':
            if ($id_tipo_atencion == 3) {
                $atencion = DB::table('hospitalizaciones')
                    ->where('id', $id_ext)
                    // ->whereRaw('cast(created_at as date) = cast(now() as date)')
                    ->whereRaw('cast(updated_at_cod_siteds as date) = cast(now() as date)')
                    ->select('cod_siteds_susalud')
                    ->first();
                $cod_siteds_susalud = ($atencion != null) ? $atencion->cod_siteds_susalud : "";
            }
            break;
        case 'evolucion':
            if ($id_tipo_atencion == 3) {
                $atencion = DB::table('evaluaciones')
                    ->where('id_emergencia', $id_ext)
                    ->where('id_tipo_atencion', $id_tipo_atencion)
                    // ->whereRaw('cast(created_at as date) = cast(now() as date)')
                    ->whereRaw('cast(updated_at_cod_siteds as date) = cast(now() as date)')
                    ->where('id_estado_reg', 1)
                    ->select('cod_siteds_susalud')
                    ->orderBy('created_at','desc')
                    ->orderBy('id','desc')
                    ->first();
                $cod_siteds_susalud = ($atencion != null) ? $atencion->cod_siteds_susalud : "";
            }
            break;        
        }
        return $cod_siteds_susalud;
    }
}