<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Oftalmologia extends Model
{ 
    protected $table = 'oftalmologia'; 
    
    public static function RegistrarOftalmologia($prestacion_id, 
    $od_av_sc, $od_av_cc,    $od_av_cae,    $od_pio,    $oi_av_sc,    $oi_av_cc,    $oi_av_cae,    $oi_pio,    $od_lejos_sph,    $od_lejos_cil,    $od_lejos_eje,
    $od_lejos_dip,    $od_lejos_av,    $od_cerca_sph,    $od_cerca_cil,    $od_cerca_eje,    $od_cerca_dip,    $oi_lejos_sph,    $oi_lejos_cil,
    $oi_lejos_eje,    $oi_lejos_dip,    $oi_lejos_av,    $oi_cerca_sph,    $oi_cerca_cil,    $oi_cerca_eje,    $oi_cerca_dip,    $refraccion_add,
    $od_motilidad_1,    $od_motilidad_2,    $od_motilidad_3,    $od_motilidad_4,    $od_motilidad_5,    $od_motilidad_6,
    $oi_motilidad_1,    $oi_motilidad_2,    $oi_motilidad_3,    $oi_motilidad_4,    $oi_motilidad_5,    $oi_motilidad_6,    $motilidad_test,    
    $od_parpado,    $od_conjuntiva,    $od_cornea,    $od_ca,    $od_pupila,    $od_cristalino,    
    $oi_parpado,    $oi_conjuntiva,    $oi_cornea,    $oi_ca,    $oi_pupila,    $oi_cristalino,
    $od_cristalino_fondo,    $od_disco_optico,    $od_disco_macula,    $od_retina,    $od_vasos_retinianos,
    $oi_cristalino_fondo,    $oi_disco_optico,    $oi_disco_macula,    $oi_retina,    $oi_vasos_retinianos,
    $imag_biometria,    $imag_retinografia,    $imag_campimetria,    $imag_ecografia_ocular, $created_us)
    {
        $cad = "Select sp_insert_oftalmologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
        ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        //$data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico,  $id_establecimiento, $p_interconsulta));
        $data = DB::select($cad, array($prestacion_id, 
        $od_av_sc, $od_av_cc,    $od_av_cae,    $od_pio,    $oi_av_sc,    $oi_av_cc,    $oi_av_cae,    $oi_pio,    $od_lejos_sph,    $od_lejos_cil,    $od_lejos_eje,
        $od_lejos_dip,    $od_lejos_av,    $od_cerca_sph,    $od_cerca_cil,    $od_cerca_eje,    $od_cerca_dip,    $oi_lejos_sph,    $oi_lejos_cil,
        $oi_lejos_eje,    $oi_lejos_dip,    $oi_lejos_av,    $oi_cerca_sph,    $oi_cerca_cil,    $oi_cerca_eje,    $oi_cerca_dip,    $refraccion_add,
        $od_motilidad_1,    $od_motilidad_2,    $od_motilidad_3,    $od_motilidad_4,    $od_motilidad_5,    $od_motilidad_6,
        $oi_motilidad_1,    $oi_motilidad_2,    $oi_motilidad_3,    $oi_motilidad_4,    $oi_motilidad_5,    $oi_motilidad_6,    $motilidad_test,    
        $od_parpado,    $od_conjuntiva,    $od_cornea,    $od_ca,    $od_pupila,    $od_cristalino,    
        $oi_parpado,    $oi_conjuntiva,    $oi_cornea,    $oi_ca,    $oi_pupila,    $oi_cristalino,
        $od_cristalino_fondo,    $od_disco_optico,    $od_disco_macula,    $od_retina,    $od_vasos_retinianos,
        $oi_cristalino_fondo,    $oi_disco_optico,    $oi_disco_macula,    $oi_retina,    $oi_vasos_retinianos,
        $imag_biometria,    $imag_retinografia,    $imag_campimetria,    $imag_ecografia_ocular, $created_us));
        return $data[0]->sp_insert_oftalmologia;
    }

}


