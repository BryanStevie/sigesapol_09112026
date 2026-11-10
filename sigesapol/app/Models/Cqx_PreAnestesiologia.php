<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cqx_PreAnestesiologia extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cqx_preanest_evals';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
      'id',
      'id_cqx_intervenciones',
      'id_estado_reg',
      'fecha_atencion',
      'id_tipo_atencion',
      'id_establecimiento',
      'id_servicio',
      'id_medico', 
      'fecha_firma',
      'ruta_firma',
      'flag_firma',
      'observacion',
      'deleted_reason',
      'flag_reevaluacion',
      'cod_reeval',
      'reevaluacion_reason',
      'created_us',
      'created_at',
      'updated_us', 
      'updated_at' ,
      'deleted_at',
      'deleted_us'
    ];

    public function Intervercion_qx()
    {
      return $this->belongsTo(CqxIntervencion::class, 'id_cqx_intervenciones');
    } 

    public function usuario_creacion()
    {
      return $this->belongsTo(User::class, 'created_us');
    }

     public function usuario_eliminacion()
    {
      return $this->belongsTo(User::class, 'deleted_us');
    }


    public function servicio_get()
    {
      return $this->belongsTo(Consultorio::class, 'id_servicio');
    }

    public function getMedicoForId($idMedico){
    $cad = "select m.*, p.nombre as nombre_profecion from medicos m 
            left join profesiones p on p.id = m.id_profesion 
            where m.id =$idMedico
    "; 
    $data = DB::select($cad);
    return $data[0];
  }

    public function readFunctionPostgres($function, $parameters = null)
    {
        $_parameters = '';
        if (count($parameters) > 0) {
            $_parameters = implode("','", $parameters);
            $_parameters = "'" . $_parameters . "',";
        }
        $data = DB::select("BEGIN;");
        $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";
        $data = DB::select($cad);
        $cad = "FETCH ALL IN ref_cursor;";
        $data = DB::select($cad);

        return $data;
    }

      public function registrarTransac_pre_anastesiologia($p_id_usuario,$p_medicoid,$p_id_intervencion,$p_id_servicio,$p_id_establecimiento,$p_fecha_atencion,$p_antecedente,$p_quirurgico,$v_presion_arterial_x,$v_presion_arterial_y,$v_frec_cardiaca,$v_temperatura,$v_talla,$v_pam,$v_frecuencia_respiratoria,$v_peso,$v_examen_ectoscopia,$v_valoraciones_dist_interincisivos,$v_valoraciones_dist_tirometoneana,$v_valoraciones_mov_cervical,$v_valoraciones_mov_cervical_limitado,$v_valoraciones_protesis,$v_valoraciones_protesis_select,$v_valoraciones_ritmo_cardiaco,$v_valoraciones_soplos,$v_valoraciones_mv_bilateral,$v_valoraciones_estertores,$v_valoraciones_sibilancia,$v_valoraciones_colum_vertebral_mv_bitlateral,$v_valoraciones_colum_vertebral_mv_bitlateral_text,$v_valoraciones_colum_vertebral_estertores,$v_valoraciones_colum_vertebral_estertores_text,$v_valoraciones_colum_vertebral_sibilancis,$v_valoraciones_colum_vertebral_sibilancis_text,$v_valoraciones_extremidades_vascular_superior,$v_valoraciones_extremidades_vascular_superior_obs,$v_valoraciones_extremidades_vascular_inferior,$v_valoraciones_extremidades_vascular_inferior_obs,$v_puntaje_calculado_glasgow_memoria,$v_descripcion_detalle_Glasgow,$estado_conciencia_str,$v_mallampati,$v_observaciones,$p_laboratorio_examenes,$p_riesgo_qx,$v_flg_recomendacion_anestesia_check,$v_recomendacion_anestesia,$v_flg_recomendacion_NPO_check,$v_recomendacion_NPO,$v_flg_recomendacion_RFP_check,$v_recomendacion_RFP,$v_flg_recomendacion_solicitar_check,$v_recomendacion_solicitar,$v_flg_recomendacion_actualizar_check,$v_recomendacion_actualizar,$v_flg_recomendacion_narcotico_check,$v_recomendacion_narcotico,$v_flg_recomendacion_cuantificar_check,$v_recomendacion_cuantificar,$v_flg_recomendacion_traerRx_check,$v_recomendacion_traerRx,$v_flg_recomendacion_otro_check,$v_recomendacion_otro,$v_flg_revaluacion,$v_revaluacion_detalle)
    { 
      $cad = "Select sp_insert_preanastesiologia_evaluacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";  
      //echo "Select sp_insert_preanastesiologia_evaluacion(".$p_id_usuario.", ".$p_id_intervencion.",'".$p_fecha_atencion."','".$p_antecedente."','".$p_quirurgico."')";exit();
      $data = DB::select($cad, array($p_id_usuario,$p_medicoid,$p_id_intervencion,$p_id_servicio,$p_id_establecimiento,$p_fecha_atencion,$p_antecedente,$p_quirurgico,$v_presion_arterial_x,$v_presion_arterial_y,$v_frec_cardiaca,$v_temperatura,$v_talla,$v_pam,$v_frecuencia_respiratoria,$v_peso,$v_examen_ectoscopia,$v_valoraciones_dist_interincisivos,$v_valoraciones_dist_tirometoneana,$v_valoraciones_mov_cervical,$v_valoraciones_mov_cervical_limitado,$v_valoraciones_protesis,$v_valoraciones_protesis_select,$v_valoraciones_ritmo_cardiaco,$v_valoraciones_soplos,$v_valoraciones_mv_bilateral,$v_valoraciones_estertores,$v_valoraciones_sibilancia,$v_valoraciones_colum_vertebral_mv_bitlateral,$v_valoraciones_colum_vertebral_mv_bitlateral_text,$v_valoraciones_colum_vertebral_estertores,$v_valoraciones_colum_vertebral_estertores_text,$v_valoraciones_colum_vertebral_sibilancis,$v_valoraciones_colum_vertebral_sibilancis_text,$v_valoraciones_extremidades_vascular_superior,$v_valoraciones_extremidades_vascular_superior_obs,$v_valoraciones_extremidades_vascular_inferior,$v_valoraciones_extremidades_vascular_inferior_obs,$v_puntaje_calculado_glasgow_memoria,$v_descripcion_detalle_Glasgow,$estado_conciencia_str,$v_mallampati,$v_observaciones,$p_laboratorio_examenes,$p_riesgo_qx,$v_flg_recomendacion_anestesia_check,$v_recomendacion_anestesia,$v_flg_recomendacion_NPO_check,$v_recomendacion_NPO,$v_flg_recomendacion_RFP_check,$v_recomendacion_RFP,$v_flg_recomendacion_solicitar_check,$v_recomendacion_solicitar,$v_flg_recomendacion_actualizar_check,$v_recomendacion_actualizar,$v_flg_recomendacion_narcotico_check,$v_recomendacion_narcotico,$v_flg_recomendacion_cuantificar_check,$v_recomendacion_cuantificar,$v_flg_recomendacion_traerRx_check,$v_recomendacion_traerRx,$v_flg_recomendacion_otro_check,$v_recomendacion_otro,$v_flg_revaluacion,$v_revaluacion_detalle));

      return $data[0]->sp_insert_preanastesiologia_evaluacion;
    }

      public function registrarReevaluacionTransac_pre_anastesiologia($p_id_usuario,$p_medicoid,$p_id_intervencion,$p_id_prestacion,$p_id_servicio,$p_id_establecimiento,$p_fecha_atencion,$p_laboratorio_examenes,$p_riesgo_qx,$v_recomendacion_otro,$p_flg_cirugia_suspendida,$p_receta_diagnosticos,$p_motivo_suspencion,$p_recomendacion_suspencion)
    { 
      $cad = "Select sp_insert_preanastesiologia_reevaluacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";  
      //echo "Select sp_insert_preanastesiologia_evaluacion(".$p_id_usuario.", ".$p_id_intervencion.",'".$p_fecha_atencion."','".$p_antecedente."','".$p_quirurgico."')";exit();
      $data = DB::select($cad, array($p_id_usuario,$p_medicoid,$p_id_intervencion,$p_id_prestacion,$p_id_servicio,$p_id_establecimiento,$p_fecha_atencion,$p_laboratorio_examenes,$p_riesgo_qx,$v_recomendacion_otro,$p_flg_cirugia_suspendida,$p_receta_diagnosticos,$p_motivo_suspencion,$p_recomendacion_suspencion));

      return $data[0]->sp_insert_preanastesiologia_reevaluacion;
    }


 








    public function GetHistorial($p)
    {
        return $this->readFunctionPostgres('sp_historial_preanestesiologia', $p);
    }


    public function getbyId_data($id_pre_anestesiologia)
    { 
        $data = DB::table('cqx_preanest_evals')->where('id', $id_pre_anestesiologia)->first();

        return $data;
    }
     public function GetExamenLaboratorio()
    { 
        $data = DB::table("cqx_procedimiento_items as p_i")
            ->select('p_i.id', 'p_i.nombre') // 👈 aquí defines los 2 campos
            ->orderBy('p_i.created_at', 'desc')
            ->get(); // 👈 importante ejecutar

        return $data;
    }

    public function extraer_antecedente_preanestesio($id_preane){ 
        $data = DB::table('cqx_preanest_evals as pe')
        ->join('cqx_preanest_eval_ant_pats as ant_pa', 'ant_pa.id_cqx_preanest_evals', '=', 'pe.id')
        ->join('cqx_cat_ant_pats as cat_ant', 'cat_ant.id', '=', 'ant_pa.id_cqx_cat_ant_pats')
        ->where('pe.id', $id_preane)
        ->select(
          'ant_pa.id',
            'cat_ant.id as id_categoria',
            'cat_ant.nombre as categoria',
            'ant_pa.otro_ant_pats_nombre as otro_nombre', 
             DB::raw("
                CASE 
                    WHEN ant_pa.tiene_antecedente IS TRUE THEN 'SI'
                    WHEN ant_pa.tiene_antecedente IS FALSE THEN 'NO' 
                    ELSE ' - '
                END as valor_si_no
            "), 
            'ant_pa.tratamiento'
        )->get(); 
        return $data;
    }

    public function extraer_quirurgico_preanestesio($id_preane){ 
        $data = DB::table('cqx_preanest_evals as pe')
        ->join('cqx_preanest_evals_quirurgicos as pre_qui', 'pre_qui.id_cqx_preanest_evals', '=', 'pe.id') 
        ->where('pe.id', $id_preane)
        ->select(
            'pre_qui.id',
            'pre_qui.descripcion as nombre',
            DB::raw("
                CASE 
                    WHEN pre_qui.tipo_anestesia = 0   OR pre_qui.tipo_anestesia IS NULL THEN '-'
                    WHEN pre_qui.tipo_anestesia > 0 THEN  
                        (SELECT cqx_tipo.descripcion FROM cqx_tipo_anestesia cqx_tipo WHERE cqx_tipo.estado=1 AND cqx_tipo.id=pre_qui.tipo_anestesia LIMIT 1)  
                END as tipo_anestesia
            "), 
            'pre_qui.anio_referencial as fecha',
            DB::raw("
                CASE 
                    WHEN pre_qui.complicaciones IS TRUE THEN 'SI'
                    WHEN pre_qui.complicaciones IS FALSE THEN 'NO'
                    ELSE ' - '
                END as complicaciones
            ")
        )->get(); 
        return $data;
    }

    public function extraer_funcVitales_preanestesio($id_preane){ 
        $data = DB::table('cqx_preanest_evals as pe')
        ->join('cqx_preanest_evals_funs_vitales as pre_fv', 'pre_fv.id_cqx_preanest_evals', '=', 'pe.id') 
        ->where('pe.id', $id_preane)
        ->select(
            'pre_fv.id',  
            DB::raw("
                CONCAT(
                    COALESCE(pre_fv.presion_arterial_x::TEXT, ' - '),
                    ' / ',
                    COALESCE(pre_fv.presion_arterial_y::TEXT, ' - ')
                ) as presion_arterial
            "),
            'pre_fv.frecuencia_cardiaca as frec_card',
            'pre_fv.frecuencia_respiratoria as frec_resp',
            'pre_fv.pam as pam',
            'pre_fv.temperatura',
            'pre_fv.talla_cm as talla',
            'pre_fv.peso_kg as peso', 
            DB::raw("
                CASE 
                    WHEN pre_fv.peso_kg IS NOT NULL 
                        AND pre_fv.talla_cm IS NOT NULL 
                        AND pre_fv.talla_cm > 0
                    THEN ROUND(
                        pre_fv.peso_kg / POWER((pre_fv.talla_cm / 100), 2)
                    , 2)
                    ELSE NULL
                END as imc
            ")
           
        )->first(); 
        return $data;
    }

    public function extraer_examen_fisico_preanestesio($id_preane){ 
          $data = DB::table('cqx_preanest_evals as pe')
          ->join('cqx_preanest_evals_examen_fisico as pre_ex_fi', 'pre_ex_fi.id_cqx_preanest_evals', '=', 'pe.id') 
          ->where('pe.id', $id_preane)
          ->select(
              'pre_ex_fi.id',   
              'pre_ex_fi.ectoscopia',
              'pre_ex_fi.dist_interincisivos as dist_interincisivos',
              'pre_ex_fi.dist_tiromentoniana as dist_tiromentoneana',
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.movilidad_cervical IS TRUE  THEN  'SI'
                      WHEN pre_ex_fi.movilidad_cervical IS FALSE THEN  'NO'
                      ELSE '-'
                  END as mov_cervical_sino
              "),
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.movilidad_cervical  THEN CONCAT('LIMITADO: ', COALESCE(pre_ex_fi.limitado, '-'))
                      ELSE 'LIMITADO: -'
                  END as mov_cervical_limitado
              "),
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.protesis_dental IS TRUE  THEN  'SI'
                      WHEN pre_ex_fi.protesis_dental IS FALSE THEN  'NO'
                      ELSE '-'
                  END as prot_dental_sino
              "),
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.protesis_dental  THEN COALESCE(pre_ex_fi.protesis_dental_tipo, '-')
                      ELSE '-'
                  END as prot_dental_tipo
              "),
              'pre_ex_fi.rc as ritmo_cardiaco',
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.soplos IS TRUE  THEN  'SI'
                      WHEN pre_ex_fi.soplos IS FALSE THEN  'NO'
                      ELSE '-'
                  END as soplos_sino
              "),
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.mv_bilateral IS TRUE  THEN  'SI'
                      WHEN pre_ex_fi.mv_bilateral IS FALSE THEN  'NO'
                      ELSE '-'
                  END as mv_bilateral_sino
              "),
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.estertores IS TRUE  THEN  'SI'
                      WHEN pre_ex_fi.estertores IS FALSE THEN  'NO'
                      ELSE '-'
                  END as estertores_sino
              "),
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.sibilancias IS TRUE  THEN  'SI'
                      WHEN pre_ex_fi.sibilancias IS FALSE THEN  'NO'
                      ELSE '-'
                  END as sibilancias_sino
              "),
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.cv_mv_bilateral IS NOT NULL AND pre_ex_fi.cv_mv_bilateral='1'  THEN  'N'
                      WHEN pre_ex_fi.cv_mv_bilateral IS NOT NULL AND pre_ex_fi.cv_mv_bilateral='0'  THEN  'A'
                      ELSE '-'
                  END as cv_mv_bilateral
              "), 
              'pre_ex_fi.cv_mv_bilateral_a_desc as cv_mv_bilateral_detalle', 
              DB::raw("
                  CASE  
                      WHEN pre_ex_fi.cv_estertores IS NOT NULL AND pre_ex_fi.cv_estertores='1'  THEN  'N'
                      WHEN pre_ex_fi.cv_estertores IS NOT NULL AND pre_ex_fi.cv_estertores='0'  THEN  'A'
                      ELSE '-'
                  END as cv_estertores
              "), 
              'pre_ex_fi.cv_estertores_a_desc as cv_estertores_detalle', 
              DB::raw("
                  CASE  
                      WHEN pre_ex_fi.cv_sibilancias IS NOT NULL AND pre_ex_fi.cv_sibilancias='1'  THEN  'N'
                      WHEN pre_ex_fi.cv_sibilancias IS NOT NULL AND pre_ex_fi.cv_sibilancias='0'  THEN  'A'
                      ELSE '-'
                  END as cv_sibilancias
              "), 
              'pre_ex_fi.cv_sibilancias_a_desc as cv_sibilancias_detalle',
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.a_v_superior_cervical IS TRUE  THEN  'SI'
                      WHEN pre_ex_fi.a_v_superior_cervical IS FALSE  THEN  'NO'
                      ELSE '-'
                  END as extremidad_superior_acceso
              "), 
              'pre_ex_fi.a_v_superior_cervical_obs as extremidad_superior_obs', 
              DB::raw("
                  CASE 
                      WHEN pre_ex_fi.a_v_inferior IS TRUE  THEN  'SI'
                      WHEN pre_ex_fi.a_v_inferior IS FALSE  THEN  'NO'
                      ELSE '-'
                  END as extremidad_inferior_acceso
              "), 
              'pre_ex_fi.a_v_inferior_obs as extremidad_inferior_obs',
              DB::raw("
                  COALESCE((
                      SELECT SUM(TRIM(val)::int)
                      FROM unnest(string_to_array(pre_ex_fi.glasgow_escala, ',')) AS val
                  ), 0) as glasgow_total
              "),
              'pre_ex_fi.glasgow_valoracion',
              'pre_ex_fi.conciencia_estado',
               DB::raw("
                  CASE 
                      WHEN pre_ex_fi.mallampati = 1  THEN  'CLASE I: Se visualiza el paladar blando, la úvula completa y los pilares amigdalinos.' 
                      WHEN pre_ex_fi.mallampati = 2  THEN  'CLASE II: Se visualiza el paladar blando y parte de la úvula.' 
                      WHEN pre_ex_fi.mallampati = 3  THEN  'CLASE III: Se visualiza el paladar blando y la base de la úvula.' 
                      WHEN pre_ex_fi.mallampati = 4  THEN  'CLASE IV: Solo se visualiza el paladar duro; imposibilidad de ver el paladar blando o la úvula.' 
                      ELSE '-'
                  END as mallampati_eval
              "), 
              'pre_ex_fi.observaciones'  
        )->first(); 
        return $data;
    }

    public function extraer_laboratorio_preanestesio($id_preane){ 
            $data = DB::table('cqx_preanest_evals as pe')
            ->join('cqx_preanest_evals_lab as ant_lab', 'ant_lab.id_cqx_preanest_evals', '=', 'pe.id')
            ->join('cqx_preanest_evals_lab_detalle as ant_lab_det', 'ant_lab_det.id_cqx_preanest_evals_lab', '=', 'ant_lab.id')
            ->join('cqx_procedimiento_items as lab_item', 'ant_lab_det.id_cqx_procedimiento_items', '=', 'lab_item.id')
            ->where('pe.id', $id_preane)
            ->select(
                'ant_lab.id',
                'ant_lab.fecha as Fecha_lab',

                DB::raw("
                    CASE 
                        WHEN ant_lab_det.id_cqx_procedimiento_items = 13 
                            THEN CONCAT('(OTROS) - ', COALESCE(ant_lab_det.otro_procedimiento_items_nombre, '-'))
                        ELSE lab_item.nombre
                    END as campo
                "), 
                'ant_lab_det.valor'
            )
            ->orderBy('ant_lab.fecha', 'desc')
            ->get(); 

        return $data;
    }

    public function extraer_riesgo_quirurgico_preanestesio($id_preane)
    {
        $rows = DB::table('cqx_preanest_evals as pe')
            ->join('cqx_preanestesia_riesgo_quirurgico as pe_rq', 'pe_rq.id_cqx_preanest_evals', '=', 'pe.id')
            ->join('cqx_prea_rq_especialidades as rq_esp', 'pe_rq.id_cqx_prea_rq_especialidad', '=', 'rq_esp.id')
            ->where('pe.id', $id_preane)
            ->select(
                'pe_rq.id',
                'rq_esp.nombre as nombre_especialidad',
                'pe_rq.clasificacion',
                'pe_rq.fecha_evaluacion',
                'pe_rq.tipo_cirugia',
                'pe_rq.recomendacion'
            ) 
            ->get(); 
       
        $data = []; 
        if (empty($rows)){ return  $data;  } 
        foreach ($rows as $row) {  
            $esASA = $row->nombre_especialidad  == 'SOCIEDAD AMERICANA DE ANESTESIOLOGÍA - ASA' ? true : false ; 
            $data[] = [
                'columna1_label' => $row->nombre_especialidad ? $row->nombre_especialidad :'No Definido',
                'columna1_value' => $row->clasificacion ? $row->clasificacion  : '-',

                'columna2_label' => $esASA ? 'TIPO DE CIRUGIA' : 'FECHA DE EVALUACIÓN',
                'columna2_value' => $esASA  ? ($row->tipo_cirugia ? $row->tipo_cirugia : '-')   : $row->fecha_evaluacion, 
                'columna3_label' => $esASA ? 'OBSERVACION' : 'RECOMENDACION',
                'columna3_value' => $row->recomendacion ? $row->recomendacion : '-'
            ];
        }

        return $data;
}

    

    public function extraer_recomendaciones_preanestesio($id_preane){ 
        

        $row = DB::table('cqx_preanest_evals as pe')
            ->join('cqx_preane_recomendaciones as ane_rec', 'ane_rec.id_cqx_preanest_evals', '=', 'pe.id') 
            ->where('pe.id', $id_preane)
            ->select(
                'ane_rec.anestesia','ane_rec.anestesia_desc',
                'ane_rec.npo','ane_rec.npo_desc',
                'ane_rec.solicitar','ane_rec.solicitar_desc',
                'ane_rec.rfp','ane_rec.rfp_desc',
                'ane_rec.actualizar','ane_rec.actualizar_desc',
                'ane_rec.receta_narcoticos','ane_rec.receta_narcoticos_desc',
                'ane_rec.cuantificar_peso_talla','ane_rec.cuantificar_peso_talla_desc',
                'ane_rec.traer_rx_ekg','ane_rec.traer_rx_ekg_desc',
                'ane_rec.flg_otro','ane_rec.otro_desc'
            )
            ->first();

        $data = [];

        if(!$row) return $data;

        $map = [
            'anestesia' => 'anestesia_desc',
            'npo' => 'npo_desc',
            'solicitar' => 'solicitar_desc',
            'rfp' => 'rfp_desc',
            'actualizar' => 'actualizar_desc',
            'receta_narcoticos' => 'receta_narcoticos_desc',
            'cuantificar_peso_talla' => 'cuantificar_peso_talla_desc',
            'traer_rx_ekg' => 'traer_rx_ekg_desc',
            'flg_otro' => 'otro_desc'
        ];
        $alias=''; 
        foreach ($map as $campo => $desc) {
            if ($row->$campo) {
                switch ($campo) {
                  case 'rfp': $alias='SE DEJA RFP N°';  break;
                  case 'receta_narcoticos': $alias='SE DEJA RECETA DE NARCOTICOS POR';  break;
                  case 'cuantificar_peso_talla': $alias='CUANTIFICAR PESO Y TALLA EXACTO';  break;
                  case 'traer_rx_ekg': $alias='TRAER RX DE PULMONES Y EKG ACTUALES';  break;
                  case 'flg_otro': $alias='OTRO';  break; 
                  default: $alias= strtoupper($campo); break;
                } 



                $data[] = [
                    'campo_recomendaciones'   => '<i class="fa fa-2x fa-check-square-o mr-3 text-success"></i>'.$alias, 
                    'detalle_recomendaciones' => $row->$desc ? $row->$desc : '-'
                ];
            }
        }

        return $data;
    }

      public function GetDiagnosticoByIdPrestacion($id)
      {
        $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,t2.codigo, t1.id_evaluacion, t1.id_categoria
          ,(case when t2.codigo='Z21.X' or t2.codigo ilike 'B20%' Then '' else t2.nombre end)nombre, t1.id_clasificacion_diagnosticos,td.descripcion
          from receta_diagnosticos t1
          inner join diagnosticos t2 on t1.id_diagnostico=t2.id and t1.id_interconsulta=0
          inner join tipo_diagnosticos td on t1.id_tipo_diagnostico =td.id 
          where t1.id_prestacion=" . $id . " and t1.id_evaluacion = 0  and t1.id_consejeria = 0 and t1.id_interconsulta = 0
          and t1.estado=1 
          ORDER BY t1.id_diagnostico ASC";
        
        $data = DB::select($cad);
        return $data;
      }
 

        public function get_cirugia_suspendida($id_preanestesio){ 
            $data = DB::table('cqx_preanest_evals as pe')
            ->join('cqx_intervenciones as cqx_i', 'pe.id_cqx_intervenciones', '=', 'cqx_i.id')
            ->join('cqx_suspensiones as cqx_s', 'cqx_s.id_intervencion', '=', 'cqx_i.id') 
            ->where('pe.id', $id_preanestesio)
            ->select(
                'cqx_s.otro_detalle as motivo_suspension',
                'cqx_s.observacion_suspension as recomendaciones' 
            ) 
            ->first(); 

        return $data;
    }
}