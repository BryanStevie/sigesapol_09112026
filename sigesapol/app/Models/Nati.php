<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Nati extends Model
{
    protected $table = 'natis';
    public $timestamps = false;
    protected $fillable = [
        'id_puerperio',
        'estado',
        'id_tipo_doc_nati',
        'num_doc',
        'sexo',
        'talla',
        'peso', 'created_at', 'created_us', 'updated_at', 'updated_us'
    ];

    public function puerperio()
    {
        return $this->belongsTo('App\Models\Puerperio');
    }

    public function getByIdPuerpera($id_puerpera)
    {
        $result = DB::table('natis')
        ->join('tipo_doc_nati', 'natis.id_tipo_doc_nati', '=', 'tipo_doc_nati.id')
        ->select('*')
        ->where('natis.id_puerperio', '=', $id_puerpera)
        ->get();

        return $result;
    }
}
