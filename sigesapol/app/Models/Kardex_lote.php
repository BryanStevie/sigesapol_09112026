<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kardex_lote extends Model
{
    protected $table = 'kardex_lotes';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'id_stock_lote',
        'id_kardex',
        'cantidad',
        'fecha_registro',
        'registro_sanitario',
        'lote',
        'fecha_vencimiento',
        'precio_unitario',
        'id_ingreso_salida',
        'estado',
        'presentacion_comercial',
    ];
}
