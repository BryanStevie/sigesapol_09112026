<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CqxMotivoSuspension extends Model
{
    protected $table = 'cqx_motivo_suspension';

    protected $fillable = [
        'descripcion',
        'estado'
    ];
    public $timestamps = false;
}