<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pielDispositivosEnfermerias extends Model
{

    protected $table = 'pieldispositivo_enfermerias';

    protected $fillable = [
        'id_enfermeria', 
        'id_dispositivo_invasivos',
        'id_via_periferica',
        'id_via_central',
        'id_caracter_alto_flujo',
        'id_sonda',
        'descripcion_sonda',
        'id_linea_arterial',
        'id_tiene_drenaje',
        'descripcion_drenaje',
        'descripcion_piel_dispositivo',
        'descripcion_miccion_espontanea',
        'created_us',
        'created_at'
    ];


    public function enfermeria() {
        return $this->belongsTo(Enfermeria::class, 'id_enfermeria');
    }


}
