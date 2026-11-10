<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KardexMedicamentoEnfermeria extends Model
{
    protected $table = 'kardex_medicamentos_enfermerias';
    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_kardex_enfermeria', 
        'fecha',
        'hidratacion', // 0 => HIDRATACION, 1 => VIA SALINAS
        'id_infusion',
        'medicamento', 
        'volumen_infusion', 
        'id_via_administracion', 
        'frasco', 
        'hora', 
        'titulable', 
        'frecuencia', 
        'dias_administracion', 
        'condicional', 
        'terapia_rescate', 
        'paquetes', 
        'flag_registro',
        'created_us',
        'created_at',
        'id_medicamento',
        'estado'
    ];
}
