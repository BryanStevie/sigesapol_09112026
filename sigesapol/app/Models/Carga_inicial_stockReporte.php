<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Carga_inicial_stockReporte extends Model
{
    //
    public function GetCargaRegistradaByFarmacia2($id_farmacia)
    {
        $datos = DB::table('carga_inicial_stocks')
            ->where('carga_inicial_stocks.id_farmacia', $id_farmacia)
            ->where('carga_inicial_stocks.estado', 1)
            ->first();
        return $datos;
    }

    public function GetCargaRegistradaByFarmacia($id_farmacia)
    {
        $datos = DB::connection('pgsql_reportes')->table('carga_inicial_stocks')
            ->where('carga_inicial_stocks.id_farmacia', $id_farmacia)
            ->where('carga_inicial_stocks.estado', 1)
            ->first();
        return $datos;
    }


    public function GetCargaRegistradaByFarmaciaAndEstado($id_farmacia, $estado)
    {
        $datos = DB::table('carga_inicial_stocks')
            ->where('carga_inicial_stocks.id_farmacia', $id_farmacia)
            ->where('carga_inicial_stocks.estado', $estado)
            ->first();
        return $datos;
    }

    public function GetCargaRegistradaByFarmaciaFinalizados($id_farmacia)
    {
        $datos = DB::table('carga_inicial_stocks')
            ->where('carga_inicial_stocks.id_farmacia', $id_farmacia)
            ->where('carga_inicial_stocks.estado', 2)
            ->first();
        return $datos;
    }

    public function GetCargaRegistradaByFarmaciaAll($id_farmacia)
    {
        $datos = DB::table('carga_inicial_stocks')
            ->where('carga_inicial_stocks.id_farmacia', $id_farmacia)
            ->first();
        return $datos;
    }
}
