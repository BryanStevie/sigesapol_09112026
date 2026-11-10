<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class CqxTipoAnestesia extends Model
{
    protected $table = 'cqx_tipo_anestesia';
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

    public function GetAllTipoCirugiasActivos()
    {
        $tipo_cirugias = DB::table('cqx_tipo_cirugias')
            ->where('estado', '=', 1)
            ->orderBy('descripcion', 'asc')
            ->get();

        return $tipo_cirugias;
    }

    public function GetAllActivos()
    {
        $tipo_cirugias = DB::table('cqx_tipo_anestesia')
            ->where('estado', '=', 1)
            ->orderBy('descripcion', 'asc')
            ->get();

        return $tipo_cirugias;
    }
}
