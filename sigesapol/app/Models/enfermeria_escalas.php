<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class enfermeria_escalas extends Model
{

    protected $table = 'enfermeria_escalas'; 

    protected $fillable = [
        'id_caracter_fisico', 
        'id_enfermeria', 
        'descripcion', 
        'fechacreacion', 
        'updated_us'
    ];

    public function caracterFisico()
    {
        return $this->belongsTo(caracterFisico::class, 'id');
    }
    
    public function enfermeria()
    {
        return $this->belongsTo(Enfermeria::class, 'id');
    }
}
