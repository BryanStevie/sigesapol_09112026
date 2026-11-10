<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kardex_nota_es extends Model
{
    protected $table = 'kardex_nota_es';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_enfermeria', 
        'fecha_firma', 
        'ruta_firma',
        'flag_firma',
        'frecuencia',
        'created_usuario',
        'created_at',
        'updated_usuario',
        'updated_at',
    ];
    
/*     public function enfermeria()
    {
        return $this->belongsTo(Enfermeria::class);
    } */
}
