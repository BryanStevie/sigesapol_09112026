<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntervencionTerapeutica extends Model
{
    protected $table = 'intervencion_terapeuticas';

    public static function GetIntervencionesTipo2($query) {
        return self::where('descripcion_intervencion_t', 'LIKE', "%{$query}%")
                    ->where('tipo', 2)
                    ->select('id', 'descripcion_intervencion_t as value', 'secuencia')
                    ->get();
    }

}
