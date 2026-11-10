<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CqxTipoProfesional extends Model
{
    protected $table = "cqx_tipo_professionales";
    protected $primaryKey = 'id';
    public $timestamps = false; 
    protected $fillable = [
        'descripcion',
        'flag_profesional',
        'estado',
        'motivo_anulacion',
        'created_us',
        'updated_us',
    ];

    public function GetAllActivos()
    {
        $tipos = CqxTipoProfesional::where('estado', 1)
            ->orderBy('id')
            ->get();
        return $tipos;
    }
    public function GetAllCirujanosActivos()
    {
        $tipos = CqxTipoProfesional::where('estado', 1)->where('flag_profesional',1)//cirujanos
            ->orderBy('id')
            ->get();
        return $tipos;
    }
}
