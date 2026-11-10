<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Gastroenterologia extends Model
{ 
    protected $table = 'gastroenterologia'; 
    
    public static function RegistrarInformeGastro($prestacion_id, 
    $baja_indicaciones,    $baja_medicacion,   $baja_estatica,    $baja_dinamica,    $baja_tacto_rectal, $preparacion,   $baja_escala_cd,    $baja_escala_ct,    $baja_escala_ci,    $baja_escala_total, $baja_insercion,    $baja_canal_anal,    $baja_recto,    $baja_sigmoide,    $baja_descendente,    $baja_transverso,    $baja_ascendente, $baja_ciego,    $baja_biopsia,    $baja_complicaciones,  $baja_observaciones,  $baja_diag_endoscopico,    $alta_indicaciones,    $alta_medicacion,    $alta_esofago,    $alta_cardias,    $alta_fondo,    $alta_cuerpo,    $alta_antro, $alta_bulbo,    $alta_seg_porcion,    $alta_biopsia,    $alta_complicaciones, $alta_observaciones,$alta_diag_endoscopico, $created_us,$alta, $baja)
    {
        $cad = "Select sp_insert_informe_gastroenterologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        
        $data = DB::select($cad, array($prestacion_id,  $baja_indicaciones,    $baja_medicacion,    $baja_estatica,    $baja_dinamica,    $baja_tacto_rectal, $preparacion,   $baja_escala_cd,    $baja_escala_ct,    $baja_escala_ci,    $baja_escala_total, $baja_insercion,   $baja_canal_anal,    $baja_recto,    $baja_sigmoide,    $baja_descendente,    $baja_transverso,    $baja_ascendente, $baja_ciego,    $baja_biopsia,    $baja_complicaciones,  $baja_observaciones,  $baja_diag_endoscopico,    $alta_indicaciones,    $alta_medicacion,   $alta_esofago,    $alta_cardias,    $alta_fondo,    $alta_cuerpo,    $alta_antro, $alta_bulbo,    $alta_seg_porcion,    $alta_biopsia,    $alta_complicaciones, $alta_observaciones,$alta_diag_endoscopico, $created_us, $alta, $baja));
        return $data[0]->sp_insert_informe_gastroenterologia;
    }

}


