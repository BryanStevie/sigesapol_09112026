<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escalasEnfermeria extends Model
{
    protected $table = 'enfermeria_escalas';

    protected $fillable = [
        'id_enfermeria', 
        'created_us',
        'created_at'
    ];


    public function enfermeria() {
        return $this->belongsTo(Enfermeria::class, 'id_enfermeria');
    }   //
}
