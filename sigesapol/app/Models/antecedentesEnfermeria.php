<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class antecedentesEnfermeria extends Model
{

    protected $table = 'antecedentes_enfermerias';
    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_enfermeria',
        'id_hipertension',
        'descripcion_hipertension',
        'id_diabetes_millitus',
        'descripcion_diabetes_millitus',
        'id_alergia',
        'descripcion_alergia',
        'id_hemodialisis',
        'descripcion_hemodialisis',
        'cardiologico',
        'descripcion_observacion',
        'descripcion_otro',
        'cardio_parametro_1',
        'cardio_parametro_2',
        'cardio_parametro_3',
        'cardio_parametro_4',
        'created_us',
        'created_at',
        'updated_us',
        'updated_at',
    ];    

    // protected $casts = [
    //     'id_hipertension' => 'boolean'
    // ];

    public function enfermeria() {
        return $this->belongsTo(Enfermeria::class, 'id_enfermeria');
    }
}
