<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Auditores extends Model
{
    protected $table = 'auditores';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_trama_prestacion',
        'id_servicio',
        'fecha_auditoria',
        'servicio_auditoria',
        'asunto_audicion',
        'observaciones_audicion',
        'id_user_auditor',
    ];
}