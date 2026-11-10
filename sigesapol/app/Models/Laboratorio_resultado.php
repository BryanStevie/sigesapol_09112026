<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Laboratorio_resultado extends Model {

    public $timestamps = false;

    public function ExisteResultado($dni_beneficiario, $fecha_toma_muestra, $nro_orden) {
        $datos = DB::table('laboratorio_resultados')
                // ->where('laboratorio_resultados.h_numero', '=', $dni_beneficiario)     
                // ->where('laboratorio_resultados.o_fecha_toma', '=', $fecha_toma_muestra)
                ->where('o_numero', '=', $nro_orden)
                // ->orderBy('prestacion_procedimientos.id', 'asc')
                ->get();
        return $datos;
    }

    public function MostrarServicios($dni_beneficiario, $nro_orden) {
        $datos = DB::table('laboratorio_resultados')
                ->select('laboratorio_resultados.h_numero','laboratorio_resultados.o_numero','laboratorio_resultados.g_nombre','laboratorio_resultados.usr_name','laboratorio_resultados.o_fecha_toma','laboratorio_resultados.usr_login','laboratorio_resultados.g_orden_imp')
                ->where('laboratorio_resultados.l_estado', '>=', '4')  
                ->where('laboratorio_resultados.h_numero', '=', $dni_beneficiario)  
                ->where('laboratorio_resultados.o_numero', '=', $nro_orden)
                ->groupBy('h_numero','o_numero','g_nombre','usr_name', 'o_fecha_toma', 'usr_login', 'g_orden_imp')
                ->orderBy('laboratorio_resultados.g_orden_imp', 'asc')
                ->get();
        return $datos;
    }

    public function MostrarResultadosReportexServicio($dni_beneficiario, $servicio, $orden, $usr_login) {
        // $datos = DB::table('laboratorio_resultados')
        //         ->select("laboratorio_resultados.p_nombre","laboratorio_resultados.l_resultado"
        //         ,DB::raw("(CASE WHEN ra_texto <> '' THEN ra_texto ELSE CONCAT(l_ref_inf,' - ',l_ref_sup) END) AS Rango_referencial_ant")
        //         ,"laboratorio_resultados.l_unidades","laboratorio_resultados.g_nombre"
        //         ,"laboratorio_resultados.usr_name","laboratorio_resultados.l_resultcomp"
        //         ,"laboratorio_resultados.p_comentario"
        //         ,"case when l_ralt_id='0' then CONCAT(l_ref_inf,' - ',l_ref_sup) ELSE ra_texto end as Rango_referencial")
        //         ->where('laboratorio_resultados.l_estado', '>=', '4')
        //         ->where('laboratorio_resultados.h_numero', '=', $dni_beneficiario)
        //         ->where('laboratorio_resultados.g_nombre', '=', $servicio)
        //         ->where('laboratorio_resultados.o_numero', '=', $orden)
        //         ->where('laboratorio_resultados.usr_login', '=', $usr_login)
        //         ->where('laboratorio_resultados.l_resultcomp', '=', '')
        //         ->orderBy('laboratorio_resultados.l_orden_imp', 'asc')
        //         ->get();
        // return $datos;

        $cad = "select 
                p_nombre,
                e_nombre 
                , l_resultado 
                , (CASE WHEN ra_texto <> '' THEN ra_texto ELSE CONCAT(l_ref_inf,' - ',l_ref_sup) END) AS rango_referencial_ant
                , l_unidades 
                , g_nombre 
                , usr_name 
                , l_resultcomp as Compuesto
                , p_comentario
                ,l_ref_inf
                ,l_ref_sup
                ,ra_texto
                ,l_fecha_val
                , o_profesional_solicita
                , o_departamento_solicita
                , p_item_comentario comentarios
                , (case when l_ralt_id='0' then CONCAT(l_ref_inf,' - ',l_ref_sup) ELSE ra_texto end)::TEXT as rango_referencial
                from laboratorio_resultados
                --where o_numero = '2412240237'
                --where h_numero  = '92807750'  
                where l_estado >= '4' and h_numero = '".$dni_beneficiario."' and g_nombre = '".$servicio."'
                and o_numero='".$orden."' 
                order by l_orden_imp;";
                // print($cad);
                // exit();
        $data = DB::select($cad);
        return $data;
    }

    public function MostrarResultadosReportexServicioCompuesto($dni_beneficiario, $servicio, $orden, $usr_login) {
        // $datos = DB::table('laboratorio_resultados')
        //         ->select("laboratorio_resultados.p_nombre","laboratorio_resultados.l_resultado"
        //         ,"CONCAT(l_ref_inf,' - ',l_ref_sup) AS Rango_referencial_ant"
        //         ,"laboratorio_resultados.l_unidades","laboratorio_resultados.g_nombre"
        //         ,"laboratorio_resultados.usr_name","laboratorio_resultados.l_resultcomp"
        //         ,"laboratorio_resultados.p_comentario"
        //         ,"case when l_ralt_id='0' then CONCAT(l_ref_inf,' - ',l_ref_sup) ELSE ra_texto end as Rango_referencial")
        //         ->where('laboratorio_resultados.l_estado', '>=', '4')
        //         ->where('laboratorio_resultados.h_numero', '=', $dni_beneficiario)
        //         ->where('laboratorio_resultados.g_nombre', '=', $servicio)
        //         ->where('laboratorio_resultados.o_numero', '=', $orden)
        //         ->where('laboratorio_resultados.usr_login', '=', $usr_login)
        //         ->where('laboratorio_resultados.l_resultcomp', '<>', '')
        //         ->orderBy('laboratorio_resultados.l_orden_imp', 'asc')
        //         ->get();
        // return $datos;

        $cad = "select 
                p_nombre 
                , l_resultado 
                , CONCAT(l_ref_inf,' - ',l_ref_sup) AS rango_referencial_ant
                , l_unidades 
                , g_nombre 
                , usr_name 
                , l_resultcomp 
                , p_comentario
                , case when l_ralt_id='0' then CONCAT(l_ref_inf,' - ',l_ref_sup) ELSE ra_texto end as rango_referencial
                from laboratorio_resultados
                where l_estado >= '4' and h_numero = '".$dni_beneficiario."' and g_nombre = '".$servicio."' 
                and o_numero='".$orden."' and usr_login='".$usr_login."' and l_resultcomp <> ''
                order by l_orden_imp";
        $data = DB::select($cad);
        return $data;
    }
    public function ObtenerFirmaResultadoM($dni_beneficiario, $nro_orden, $usr_login) {
        $datos = DB::table('laboratorio_resultados')
                ->select('h_numero','usr_image', 'usr_name', 'g_nombre', 'usr_login')
                ->where('laboratorio_resultados.l_estado', '>=', '4')  
                ->where('laboratorio_resultados.h_numero', '=', $dni_beneficiario)  
                ->where('laboratorio_resultados.o_numero', '=', $nro_orden)
                ->where('laboratorio_resultados.usr_login', '=', $usr_login)
                ->offset(0)
                ->limit(1)
                ->get();
        return $datos;
    }


    public function eliminarResultadoDifenretes($cod_orden){
      DB::table('laboratorio_resultados')
        ->where('o_numero', $cod_orden)
        ->delete();
    }


    public function obtenerPrestacionesProcedimientos($cod_orden){
      $cad = "select l.cod_orden_lab,pro.codigo ,pro.descripcion  from laboratorios l 
              inner join prestaciones p on p.id = l.id_prestacion 
              inner join prestacion_procedimientos pp on p.id = pp.id_prestacion 
              inner join procedimientos pro on pro.id = pp.id_procedimiento 
              where l.cod_orden_lab ='$cod_orden' 
              order by l.id desc";
      return DB::select($cad);
    }


}
