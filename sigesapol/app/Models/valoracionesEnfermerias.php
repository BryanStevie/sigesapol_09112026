<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class valoracionesEnfermerias extends Model
{
    protected $table = 'valoraciones_enfermerias';

    protected $fillable = [
        'id_enfermeria', 
        'id_ingresa_por',
        'id_funcion_motora',
        'id_condicion_ingreso',
        'id_respiratorio',
        'id_estado_conciencia',
        'id_secrecionbronquiales',
        'grado_dependencia',
        'id_oxigenoterapia',
        'descripcion_oxigenoterapia',
        'id_miccion_espontanea',
        'descripcion_miccion_espontanea',
        'punto_calculado_glasgow',
        'descripcion_calculado_glasgow',
        'id_acompanante',
        'descrip_acompanante',
        'numero_acompanante',
        'id_hemoptisis',
        'descripcion_hemoptisis',
        'id_edema',
        'descripcion_edema',
        'id_integridad',
        'id_abdomen',
        'id_mallampaty',
        'descripcion_mallampaty',
        'created_us',
        'created_at'
    ];


    public function enfermeria() {
        return $this->belongsTo(Enfermeria::class, 'id_enfermeria');
    }
}   //

