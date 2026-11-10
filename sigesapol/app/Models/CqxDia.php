<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CqxDia extends Model
{
    public function GetAllActivos()
    {
        $dias = CqxDia::where('id_estado_reg', 1)
            ->OrderBy('id', 'asc')
            ->OrderBy('flag', 'asc')
            ->get();
        return $dias;
    }
}
