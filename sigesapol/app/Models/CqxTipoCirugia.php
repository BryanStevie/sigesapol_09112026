<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CqxTipoCirugia extends Model
{
    protected $table = 'cqx_tipo_cirugias';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'descripcion',
        'estado',
        'created_us',
        'created_at',
        'updated_us',
        'updated_at',
        'nivel_ipress'
    ];
}
