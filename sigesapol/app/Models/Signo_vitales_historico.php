<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Signo_vitales_historico extends Model
{
    protected $table = 'signo_vitales_historico';

    public function enfermeria()
    {
        return $this->belongsTo(Enfermeria::class, 'id');
    }

    // Relación con Emergencia

    public function signoVitalDetalle()
    {
        return $this->hasMany(signosVitalesDetalles::class, 'id_signo_vital');
    }
}
