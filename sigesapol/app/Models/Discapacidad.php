<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discapacidad extends Model
{
    protected $table = 'discapacidades';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'descripcion',
        'id_estado_reg',
        'created_at'
    ];
}
