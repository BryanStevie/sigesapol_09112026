<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class enfermeriaDetalle extends Model
{

    protected $table = 'notas_vitales_enfermerias'; // Especifica el nombre de la tabla
    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_caracter_fisico', 
        'id_enfermeria', 
        'descripcion', 
        'created_us', 
        'created_at', 
        'updated_us',
        'updated_at',
    ];

    public function caracterFisico()
    {
        //se hace una relacion de muchos a uno es decir muchos caraccteres pertenecen a un registro
        return $this->belongsTo(caracterFisico::class, 'id');
    }
    public function enfermeria()
    {
                //se hace una relacion de muchos a uno es decir muchos caraccteres pertenecen a un registro

        return $this->belongsTo(Enfermeria::class, 'id');
    }
}
