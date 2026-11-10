<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CqxSuspension extends Model
{
    protected $table = 'cqx_suspensiones';
    protected $primaryKey = 'id_suspension';
    public $timestamps = true; 

    protected $fillable = [
        'id_intervencion', 
        'fecha_hora', 
        'motivo_de_suspension', 
        'otro_detalle', 
        'motivo', 
        'observacion', 
        'tipo',
        'created_us'
    ];
}