<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Docsinfirmar extends Model
{
    public function listar_docsinfirmar_ajax($p){
        $sql = "SELECT * FROM sp_listar_doc_sinfirmar_1(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::connection('pgsql_reportes')->select($sql, [
            $p[0], // id_establecimiento
            $p[1], // fecha_ini
            $p[2], // fecha_fin
            $p[3], // dni_beneficiario
            $p[4], // estado
            $p[5], // id_user
            $p[6], // pagina
            $p[7]  // limit
        ]);
        return $result;
    }

    //===================================== MOSTRAR NOTAS DE ENFERMERÍA PENDIENTES DE FIRMA ===================================================
    public function listar_notas_enfermeria_sinfirmar_ajax($p){
        $sql = "SELECT * FROM sp_listar_notas_enfermeria_sinfirmar(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::connection('pgsql_reportes')->select($sql, [
            $p[0], // id_establecimiento
            $p[1], // fecha_ini
            $p[2], // fecha_fin
            $p[3], // dni_beneficiario
            $p[4], // id_user
            $p[5], // pagina
            $p[6], // limit
            $p[7]  // estado ('P' pendiente, 'F' firmado)
        ]);
        return $result;
    }

    public function GetDocsPendientesFirmaByIdServicio($id_establecimiento, $id_servicio, $id_user)
    {
        $fechaInicio  = Carbon::now()->subMonths(1)->format('Y-m-d');
        $fechaFin     = Carbon::now()->format('Y-m-d');

        $cad = "SELECT sp_contar_total_general(?, ?, ?, ?)";
        $data = DB::connection('pgsql_reportes')->select($cad, [
            $fechaInicio,
            $fechaFin,
            $id_user,
            $id_establecimiento
        ]);

        $total = isset($data[0]->sp_contar_total_general) ? $data[0]->sp_contar_total_general : 0;

        return [
            (object)[
                //'id_tipo_atencion' => $tipo_atencion,
                'cantidad' => (string)$total
            ]
        ];
    }
    //===================================== MOSTRAR DOCUMENTOS FIRMADOS ===================================================
    public function getDocsfirmadosUsuario($p){
        $sql = "SELECT * FROM sp_listar_doc_firmadosv1(?, ?, ?, ?, ?, ?)";
        $result = DB::connection('pgsql_reportes')->select($sql, [
            $p[0], // id_establecimiento
            $p[1], // id_user
            $p[2], // fecha_ini
            $p[3], // fecha_fin
            $p[4], // pagina
            $p[5]  // limit
        ]);
        return $result;
    }
    //==================================== MOSTRAR FIRMAS PENDIENTES =======================================================
    public function getDocsfirmasPend($p){
        $sql = "SELECT * FROM sp_firmas_pendiente_x_medico_x_unidad_x_subunidad_v6(?, ?, ?, ?, ?, ?, ?, ?)";

        $result = DB::select($sql, [
            $p[0], // id_establecimiento
            $p[1], // fecha_ini
            $p[2], // fecha_fin
            $p[3], // division
            $p[4], // departamento
            $p[5], // usuario
            $p[6], // pagina
            $p[7]  // limit
        ]);
        return $result;
    }
    //==================================== MOSTRAR FIRMAS PENDIENTES ENFERMERÍA =======================================================
    public function getFirmasPendEnfermeria($p){
        $sql = "SELECT * FROM sp_enfermeria_pendiente_x_unidad_x_subunidad(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::select($sql, [
            $p[0], // id_establecimiento
            $p[1], // fecha_ini
            $p[2], // fecha_fin
            $p[3], // division
            $p[4], // departamento
            $p[5], // usuario
            $p[6], // pagina
            $p[7]  // limit
        ]);
        return $result;
    }
    //==================================== EXPORTAR FIRMAS PENDIENTES ENFERMERÍA =======================================================
    public function AllFirmasPendientesReporteEnfermeria($id_establecimiento,$fecha_desde,$fecha_hasta,$division = null,$departamento = null,$user = null, $pagina = null ,$limit = null){
        $sql = "SELECT * FROM sp_enfermeria_pendiente_x_unidad_x_subunidad(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::select($sql, [$id_establecimiento,$fecha_desde,$fecha_hasta,$division,$departamento,$user,$pagina,$limit]);
        return $result;
    }
    //==================================== EXPORTAR FIRMAS PENDIENTES =======================================================    
    public function AllFirmasPendientesReportePrestaciones($id_establecimiento,$fecha_desde,$fecha_hasta,$division = null,$departamento = null,$user = null, $pagina = null ,$limit = null){
        $sql = "SELECT * FROM sp_firmas_pendiente_x_medico_x_unidad_x_subunidad_v6(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::select($sql, [$id_establecimiento,$fecha_desde,$fecha_hasta,$division,$departamento,$user,$pagina,$limit]);
        return $result;
    }

    //==================================== MOSTRAR ALTAS EMERGENCIA PENDIENTES =======================================================
    public function getAltasEmgPend($p){
        $sql = "SELECT * FROM sp_altas_emergencia_pendientes_v4(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::select($sql, [
            $p[0], // id_establecimiento
            $p[1], // fecha_ini
            $p[2], // fecha_fin
            $p[3], // division
            $p[4], // departamento
            $p[5], // usuario
            $p[6], // pagina
            $p[7]  // limit
        ]);
        return $result;
    }
    //==================================== MOSTRAR ALTAS EMERGENCIA PENDIENTES =======================================================
    public function getAltasEmgPendet($p){
        $sql = "SELECT * FROM sp_altas_emergencia_pendientes_detallado(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::select($sql, [
            $p[0], // id_establecimiento
            $p[1], // fecha_ini
            $p[2], // fecha_fin
            $p[3], // division
            $p[4], // departamento
            $p[5], // usuario
            $p[6], // pagina
            $p[7]  // limit
        ]);
        return $result;
    }
    //==================================== MOSTRAR ALTAS HOSPITALIZACION PENDIENTES =======================================================
    public function getAltasHspgPend($p){
        $sql = "SELECT * FROM sp_altas_hospitalizacion_pendientes_v1(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::select($sql, [
            $p[0], // id_establecimiento
            $p[1], // fecha_ini
            $p[2], // fecha_fin
            $p[3], // division
            $p[4], // departamento
            $p[5], // usuario
            $p[6], // pagina
            $p[7]  // limit
        ]);
        return $result;
    }
    //==================================== MOSTRAR ALTAS HOSPITALIZACION PENDIENTES DETALLADO =======================================================
    public function getAltasHspgPendet($p){
        $sql = "SELECT * FROM sp_altas_hospitalizacion_pendientes_detallado(?, ?, ?, ?, ?, ?, ?, ?)";
        $result = DB::select($sql, [
            $p[0], // id_establecimiento
            $p[1], // fecha_ini
            $p[2], // fecha_fin
            $p[3], // division
            $p[4], // departamento
            $p[5], // usuario
            $p[6], // pagina
            $p[7]  // limit
        ]);
        return $result;
    }
}