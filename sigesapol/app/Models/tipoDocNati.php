<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipoDocNati extends Model
{
    protected $table = 'tipo_doc_nati';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    public function nati()
    {
        return $this->belongsTo('App\Models\Nati');
    }
}
