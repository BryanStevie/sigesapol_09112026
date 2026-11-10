<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfusionEnfermeria extends Model
{
    protected $table = 'infusiones_enfermerias';
    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = ['descripcion'];
}
