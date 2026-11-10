<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Puerperio extends Model
{
    protected $table = 'puerperios';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['fecha_parto', 'id_tipo_parto', 'complicaciones', 'descripcion', 'created_at', 'created_us', 'updated_at', 'updated_us'];

    public function prestacion()
    {
        return $this->belongsTo('App\Models\Prestaciones');
    }

    public function natis() {
        return $this->hasMany('App\Models\Nati', 'id_puerperio');
    }

    public function getByIdPrestacion($id_prestacion)
    {
        return DB::table('puerperios')
        ->join('prestaciones', 'puerperios.id_prestacion', '=', 'prestaciones.id')
        ->select('*')
        ->where('puerperios.id_prestacion', '=', $id_prestacion)
        ->limit(1)
        ->get();
    }

    public function getLastPuerperioByAseguradoDni($asegurado_dni) 
    {
        $query = "
        SELECT p.id, p.fecha_parto FROM puerperios p
        INNER JOIN emergencias e ON p.id = e.id_puerperio
        inner join asegurados a on e.id_asegurado = a.id
        where a.nro_doc_ident  = '".$asegurado_dni."'
        order by p.id desc limit 1
        ";
        $result = DB::select($query);
        return $result;
    }
}
