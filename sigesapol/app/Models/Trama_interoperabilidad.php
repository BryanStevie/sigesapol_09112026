<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\trama_prestacion;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
class Trama_interoperabilidad extends Model {
    
    public function enviartramaemergencia($id_emergencia){
        $data = DB::select("SELECT sp_insertar_tramas_emergencia_2 ($id_emergencia)");
      }

      public function enviartramahospitalizacion($id_hospitalizacion){
        $data = DB::select("SELECT sp_insertar_tramas_hospitalizaciones ($id_hospitalizacion)");
      }

      public function enviartramaambulatorio($id_ambulatorios){
        $data = DB::select("SELECT sp_insertar_tramas_ambulatorio ($id_ambulatorios)");
      }

      public function Envio_trama_masivo_1($opcion,$dato_corelativo,$dni_user){

        //$url = 'https://wadl.saludpol.gob.pe:20083/wa/prestacion_sugps_masivo.php';
        //$url = 'https://test-wsdl.saludpol.gob.pe:8083/wa/prestacion_sugps_masivo.php';
        $url = 'https://dev-ios-be.saludpol.gob.pe:31289/api/v1/spi/prestaciones-temp/cargar-masivo';
        $data = array(
          'acceso' => array(
            /*
              'usuario' => 'wAiP5uG9s@cci.wa.sp',
              'clave' => '7P^Q6s*m6S@e5P',
              'v_usuario_crea' => '47980205',
              'v_cod_paquete' => $dato_corelativo
              */
              'v_usuario_crea' => "47980205",
              'v_id_proveedor_convenio' => "001"
          ),
          'prestacion' => [] 
      
      );
      
     
        $prestaciones_masivo = "SELECT p.id_tipo_atencion,tp.* FROM trama_prestacion tp  left join prestaciones p on tp.id_prestacion = p.id where tp.id = '$opcion'";

      $prestaciones_masivo = DB::select($prestaciones_masivo);
      foreach ($prestaciones_masivo as $prestacion) {
      $asegurado =  DB::table('asegurados')
      ->select('asegurados.*')
      ->where('nro_doc_ident', $prestacion->paciente_nro_doc_identidad)
      ->first();
      $sexo_asegurado = ($asegurado->sexo == 'F') ? 2 : 1;

      $search  = array("+", ",","'","/");
      $replace = array("\+", "\,", "\'", "\/");
      
      $prestacion->paciente_nombres = str_replace($search,$replace,$prestacion->paciente_nombres);
      $codigo_renipress = str_pad($prestacion->codigo_renipress, 8, "0", STR_PAD_LEFT);
      $numero_lote = strval($prestacion->numero_lote);
      $numero_correlativo = strval($prestacion->numero_correlativo);
      $ipress_referencia_fups = strval($prestacion->ipress_referencia_fups);
      $hoja_referencia_fups = strval($prestacion->hoja_referencia_fups);
      $tipo_doc_identidad = strval($prestacion->tipo_doc_identidad);
      $id_hist_clinica = strval($prestacion->id_hist_clinica);
      $id_tipo_cobert = strval($prestacion->id_tipo_cobert);
      $id_upss_fups = strval($prestacion->id_upss_fups);
      $fecha_atencion_fups = strval($prestacion->fecha_atencion_fups);
      $fecha_ingreso_fups = strval($prestacion->fecha_ingreso_fups);
      $fecha_inicio_adm = strval($prestacion->fecha_inicio_adm);
      $fecha_cierre_adm_fups = strval($prestacion->fecha_cierre_adm_fups);
      $fecha_egreso_fups = strval($prestacion->fecha_egreso_fups);
      $renipress_vincula_fups = strval($prestacion->renipress_vincula_fups);
      $num_lote_vincula_fups = strval($prestacion->num_lote_vincula_fups);
      $num_vincula_fups = strval($prestacion->num_vincula_fups);
      $estado_paci_mujer_fups = strval($prestacion->estado_paci_mujer_fups);
      $fecha_probable_parto_fups = strval($prestacion->fecha_probable_parto_fups);
      $fecha_parto_fups = strval($prestacion->fecha_parto_fups);
           // dd($fecha_probable_parto_fups ? date('d/m/Y', strtotime($fecha_probable_parto_fups)) : $fecha_parto_fups ? date('d/m/Y', strtotime($fecha_parto_fups)) : null);
      $id_destino_aseg_fups = strval($prestacion->id_destino_aseg_fups);
      $ipress_destino_fups = strval($prestacion->ipress_destino_fups);
      $num_hoja_destino_fups = strval($prestacion->num_hoja_destino_fups);
      $fecha_fallece = strval($prestacion->fecha_fallece);
      $numero_acreditacion_siteds = strval($prestacion->numero_acreditacion_siteds);
      $id_tipodoc_ident = strval($prestacion->id_tipodoc_ident);
      $numdoc_ident = strval($prestacion->numdoc_ident);
      $doc_ape_paterno = strval($prestacion->doc_ape_paterno);
      $doc_ape_materno = strval($prestacion->doc_ape_materno);
      $doc_nombres = strval($prestacion->doc_nombres);
      $id_tipo_prof = strval($prestacion->id_tipo_prof);
      $num_coleg = strval($prestacion->num_coleg);
      $id_especialidad_prof = $prestacion->id_especialidad_prof;
      if ($id_especialidad_prof === null || $id_especialidad_prof === '') {
        $id_especialidad_prof = '';
      } else {
          $id_especialidad_prof = str_pad($id_especialidad_prof, 3, '0', STR_PAD_LEFT);
      }
      $num_rne = strval($prestacion->num_rne);
      $observacion_fups = strval($prestacion->num_rne);
      $ruc_ipress = strval($prestacion->ruc_ipress);

      $fecha_corte_admin = strval($prestacion->fecha_corte_admin);

      $prestaciones = array(
            'v_id_ipress_prest' => $codigo_renipress,
            'v_lote_prest' => $numero_lote,
            'v_num_correlativo_prest'=> $numero_correlativo,
            't_fec_atencion_prest' => $fecha_atencion_fups ? date('d/m/Y', strtotime($fecha_atencion_fups)) : null,
            'v_hora_atencion_prest'=> '',
            'i_id_lugar_atencion' => '1',   
            'v_id_tipo_ingreso'=> $prestacion->id_tipo_atencion, 
            "v_id_ipress_ref" => $ipress_referencia_fups,
            'v_num_hoja_ref'=> $hoja_referencia_fups,
            'v_id_upss'=> $prestacion->id_upss_fups,
            'v_id_beneficio'=> '1', // 1 ccee // 2 emer // 3 hosp
            'v_id_tipdoc_identidad_aseg' => $tipo_doc_identidad,
            'v_numdoc_identidad_aseg' => $prestacion->paciente_nro_doc_identidad,
            'v_hist_clinica' => $prestacion->id_hist_clinica,
            'i_id_sexo'=> $sexo_asegurado,
            't_fec_nacimiento' => $asegurado->fecha_nac ,
            't_fecha_fallece' => $fecha_fallece ? date('d/m/Y', strtotime($fecha_fallece)): null,         
            'v_ap_paterno'=> $prestacion->paciente_apellido_paterno,
            'v_ap_materno' => $prestacion->paciente_apellido_materno,
            'v_nombres'=> $prestacion->paciente_nombres,
            'v_id_estado_mujer'=> $estado_paci_mujer_fups,
            't_fec_parto' => !empty($fecha_probable_parto_fups) ? date('d/m/Y', strtotime($fecha_probable_parto_fups)) :
    (!empty($fecha_parto_fups) ? date('d/m/Y', strtotime($fecha_parto_fups)) : null),
            't_fec_ingreso' => $fecha_ingreso_fups ? date('d/m/Y', strtotime($fecha_ingreso_fups)) : null,
            'v_hora_ingreso' => $prestacion->hora_ingreso,
            'v_id_tipo_cobert' => $id_tipo_cobert,
            'v_id_dest_aseg' => '' ,
            'v_id_ipress_rc' => $ipress_destino_fups ,
            'v_num_hoja_rc' => $num_hoja_destino_fups,
            't_fec_corte_admin' => $fecha_corte_admin ? date('d/m/Y', strtotime($fecha_corte_admin)) : null,
            'v_ipress_corte_admin' => $prestacion->ipress_corte_admin,
            'v_lote_corte_admin' => $prestacion->lote_corte_admin,
            'v_num_correlativo_corte_admin' => $prestacion->num_correlativo_corte_admin,
            't_fec_alta' => $fecha_egreso_fups ? date('d/m/Y', strtotime($fecha_egreso_fups)) : null,
            'v_hora_alta' => $prestacion->hora_alta,
            'v_id_tipdoc_identidad_prof' => $id_tipodoc_ident,
            'v_numdoc_identidad_prof'=> $numdoc_ident,
            'v_id_tipo_prof' => $id_tipo_prof,
            'v_num_coleg'=> $num_coleg,
            'v_id_especialidad_prof' => $id_especialidad_prof,
            'v_num_rne'=> $num_rne,
            'v_observacion_fups' => '',
            "v_codigo_autorizacion"=> '',
            'diagnosticos' => [] 
      );
      
      
      $diagnosticos_data = "SELECT * FROM trama_diagnosticos WHERE id_trama_prestacion = '$prestacion->id'  AND (estado <> 0 OR estado IS NULL) and estado = '1'";
      $diagnosticos_data = DB::select($diagnosticos_data);
      
      foreach ($diagnosticos_data as $diagnosticos_uno) {

    $procedimientos_query = "SELECT * FROM trama_procedimientos WHERE id_trama_prestacion = '$prestacion->id' AND id_cie10 = '$diagnosticos_uno->id_cie10'  AND (estado <> 0 OR estado IS NULL) and cantidad_ejecutada != 0";
          $procedimientos = DB::select($procedimientos_query);
            //dd($procedimientos);
                    $productos_query = "SELECT p.codigo_sismed AS sismed_producto,tp.* FROM trama_productos tp INNER JOIN productos p ON p.codigo = tp.id_prod_med WHERE tp.id_trama_prestacion = '$prestacion->id' AND (tp.estado <> 0 OR tp.estado IS NULL) AND tp.id_cie10 = '$diagnosticos_uno->id_cie10'";
          $productos = DB::select($productos_query);
          $diag = $diagnosticos_uno->id_cie10;
       
          $diagnostico = array(
              'v_cod_cie10' => $diag,
              'i_id_tipo_diag' => $diagnosticos_uno->id_tipo_diag,
              'v_id_diag_ie' => $diagnosticos_uno->id_identificador_diag,
              'procedimientos' => [],
              'productos' => []
          );
      
          foreach ($productos as $producto) {
 $diagnostico['productos'][] = array(
        'v_cod_medicamento_insumo' => !empty($producto->sismed_producto)
            ? $producto->sismed_producto
            : $producto->id_prod_med,

        'i_cant_prescrita' => $producto->cantidad_prescrita,
        'i_cant_entregada' => $producto->cantidad_entregada,
        'i_precio_envio' => intval($producto->precio),
    );
          }
      
          foreach ($procedimientos as $procedimiento) {
              $diagnostico['procedimientos'][] = array(
                  'v_cod_cpms' => $procedimiento->id_proced,
'i_cant_indicada' => (empty($procedimiento->cantidad_indicada) || $procedimiento->cantidad_indicada == 0)
    ? $procedimiento->cantidad_ejecutada
    : $procedimiento->cantidad_indicada,
                  'i_cant_ejecutada' => $procedimiento->cantidad_ejecutada,
                  'i_precio_envio' => $procedimiento->precio,
                 // 't_fecha_procedimiento' => $procedimiento->fecha_procedimiento,
                  'v_resultado_procedimiento' => $procedimiento->resultado_procedimiento,
              );
          }
      
          $prestaciones['diagnosticos'][] = $diagnostico;
      
        }
      
          $data['prestacion'][] = $prestaciones;
          
      }
      //print json_encode($data, JSON_UNESCAPED_SLASHES);
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen(json_encode($data)),
          'Authorization: Bearer ' . $dni_user
      ));  
      
      $response = curl_exec($ch);      
      $response = json_decode($response);
       return $response;

      curl_close($response);
      
      }

public function envioObservacionPrestacion($prestacion, $token)
{
    try {

        $client = new Client();
        
        $url = 'https://dev-ios-be.saludpol.gob.pe:31289/api/v1/spi/prestaciones-hist/general/prestacion';

        $idConvenio = '000';

        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ],
            'query' => [
                'idConvenio' => $idConvenio,
                'ipress' => $prestacion->codigo_renipress,
                'lote' => $prestacion->numero_lote,
                'correlativo' => $prestacion->numero_correlativo,
            ]
        ]);

        $body = $response->getBody()->getContents();

        return json_decode($body, true);
         
    } catch (\Exception $e) {

        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

public function observacionPrestacion($prestacion, $token)
{
    try {

        $client = new Client();
        
        $url = 'https://dev-ios-be.saludpol.gob.pe:31289/api/v1/spi/observacion-reglas/detalle/prestacion';

        $idConvenio = '000';

        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ],
            'query' => [
                'idConvenio' => $idConvenio,
                'ipress' => $prestacion->codigo_renipress,
                'lote' => $prestacion->numero_lote,
                'correlativo' => $prestacion->numero_correlativo,
            ]
        ]);

        $body = $response->getBody()->getContents();

        return json_decode($body, true);
         
    } catch (\Exception $e) {

        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

public function Envio_trama_masivo($opcion,$dato_corelativo,$dni_user){

  //$url = 'https://wadl.saludpol.gob.pe:20083/wa/prestacion_sugps_masivo.php';
  //$url = 'https://test-wsdl.saludpol.gob.pe:8083/wa/prestacion_sugps_masivo.php';
  $url = 'https://dev-ios-be.saludpol.gob.pe:31289/api/v1/spi/prestaciones-temp/cargar-masivo';

  $data = array(
    'acceso' => array(
        'usuario' => 'wAiP5uG9s@cci.wa.sp',
        'clave' => '7P^Q6s*m6S@e5P-',
        'v_usuario_crea' => $dni_user,
        'v_cod_paquete' => $dato_corelativo
    ),
    'prestacion' => [] 

  );

if($opcion == '0'){//emer = 0
  $prestaciones_masivo = "SELECT p.id_tipo_atencion,tp.* FROM trama_prestacion tp left join prestaciones p on tp.id_prestacion = p.id where tp.id_estado = '0' and tp.fecha_atencion_fups <= '2026-01-31 23:59' and p.id_tipo_atencion in ('2') and tp.id_upss_fups != '012000' and tp.id_upss_fups != '110500' and tp.numero_lote = '2026' and tp.numero_correlativo is not null  order by id desc limit 2000";
}else if($opcion == '1'){ // hosp   = 1
  $prestaciones_masivo = "SELECT p.id_tipo_atencion,tp.* FROM trama_prestacion tp left join prestaciones p on tp.id_prestacion = p.id where tp.id_estado = '0' and tp.fecha_atencion_fups <= '2026-01-31 23:59' and p.id_tipo_atencion in ('3') and tp.id_upss_fups != '012000' and tp.id_upss_fups != '110500' and tp.numero_lote = '2026' and tp.numero_correlativo is not null  order by id desc limit 4000";
}else{/// ccee = 2
  $prestaciones_masivo = "SELECT p.id_tipo_atencion,tp.* FROM trama_prestacion tp left join prestaciones p on tp.id_prestacion = p.id where tp.id_estado = '0' and tp.fecha_atencion_fups <= '2026-01-31 23:59' and p.id_tipo_atencion in ('1') and tp.id_upss_fups != '012000' and tp.id_upss_fups != '110500' and tp.numero_lote = '2026' and tp.numero_correlativo is not null  order by id desc limit 6000";
}
$prestaciones_masivo = DB::select($prestaciones_masivo);
//dd($prestaciones_masivo);
foreach ($prestaciones_masivo as $prestacion) {
    $model_auditoria = trama_prestacion::find($prestacion->id);
     $model_auditoria->id_estado = '1';
     $model_auditoria->save();
  
  $asegurado =  DB::table('asegurados')
  ->select('asegurados.*')
  ->where('nro_doc_ident', $prestacion->paciente_nro_doc_identidad)
  ->first();
  $sexo_asegurado = ($asegurado->sexo == 'F') ? 2 : 1;
$search  = array("+", ",","'","/");
$replace = array("\+", "\,", "\'", "\/");

$prestacion->paciente_nombres = str_replace($search,$replace,$prestacion->paciente_nombres);
      $codigo_renipress = str_pad($prestacion->codigo_renipress, 8, "0", STR_PAD_LEFT);
      $numero_lote = strval($prestacion->numero_lote);
      $numero_correlativo = strval($prestacion->numero_correlativo);
      $ipress_referencia_fups = strval($prestacion->ipress_referencia_fups);
      $hoja_referencia_fups = strval($prestacion->hoja_referencia_fups);
      $tipo_doc_identidad = strval($prestacion->tipo_doc_identidad);
      $id_hist_clinica = strval($prestacion->id_hist_clinica);
      $id_tipo_cobert = strval($prestacion->id_tipo_cobert);
      $id_upss_fups = strval($prestacion->id_upss_fups);
      $fecha_atencion_fups = strval($prestacion->fecha_atencion_fups);
      $fecha_ingreso_fups = strval($prestacion->fecha_ingreso_fups);
      $fecha_inicio_adm = strval($prestacion->fecha_inicio_adm);
      $fecha_cierre_adm_fups = strval($prestacion->fecha_cierre_adm_fups);
      $fecha_egreso_fups = strval($prestacion->fecha_egreso_fups);
      $renipress_vincula_fups = strval($prestacion->renipress_vincula_fups);
      $num_lote_vincula_fups = strval($prestacion->num_lote_vincula_fups);
      $num_vincula_fups = strval($prestacion->num_vincula_fups);
      $estado_paci_mujer_fups = strval($prestacion->estado_paci_mujer_fups);
      $fecha_probable_parto_fups = strval($prestacion->fecha_probable_parto_fups);
      $fecha_parto_fups = strval($prestacion->fecha_parto_fups);
      $id_destino_aseg_fups = strval($prestacion->id_destino_aseg_fups);
      $ipress_destino_fups = strval($prestacion->ipress_destino_fups);
      $num_hoja_destino_fups = strval($prestacion->num_hoja_destino_fups);
      $fecha_fallece = strval($prestacion->fecha_fallece);
      $numero_acreditacion_siteds = strval($prestacion->numero_acreditacion_siteds);
      $id_tipodoc_ident = strval($prestacion->id_tipodoc_ident);
      $numdoc_ident = strval($prestacion->numdoc_ident);
      $doc_ape_paterno = strval($prestacion->doc_ape_paterno);
      $doc_ape_materno = strval($prestacion->doc_ape_materno);
      $doc_nombres = strval($prestacion->doc_nombres);
      $id_tipo_prof = strval($prestacion->id_tipo_prof);
      $num_coleg = strval($prestacion->num_coleg);
      $id_especialidad_prof = $prestacion->id_especialidad_prof;
      if ($id_especialidad_prof === null || $id_especialidad_prof === '') {
        $id_especialidad_prof = '';
    } else {
        $id_especialidad_prof = str_pad($id_especialidad_prof, 3, '0', STR_PAD_LEFT);
    }
      $num_rne = strval($prestacion->num_rne);
      $observacion_fups = strval($prestacion->num_rne);
      $ruc_ipress = strval($prestacion->ruc_ipress);

      $fecha_corte_admin = strval($prestacion->fecha_corte_admin);

      $prestaciones = array(
            'v_id_ipress_prest' => $codigo_renipress,
            'v_lote_prest' => $numero_lote,
            'v_num_correlativo_prest'=> $numero_correlativo,
            't_fec_atencion_prest' => $fecha_atencion_fups ? date('d/m/Y', strtotime($fecha_atencion_fups)) : null,
            'v_hora_atencion_prest'=> '',
            'i_id_lugar_atencion' => '1',   
            'v_id_tipo_ingreso'=> $prestacion->id_tipo_atencion, 
            "v_id_ipress_ref" => $ipress_referencia_fups,
            'v_num_hoja_ref'=> $hoja_referencia_fups,
            'v_id_upss'=> $prestacion->id_upss_fups,
            'v_id_beneficio'=> '1', // 1 ccee // 2 emer // 3 hosp
            'v_id_tipdoc_identidad_aseg' => $tipo_doc_identidad,
            'v_numdoc_identidad_aseg' => $prestacion->paciente_nro_doc_identidad,
            'v_hist_clinica' => $prestacion->id_hist_clinica,
            'i_id_sexo'=> $sexo_asegurado,
            't_fec_nacimiento' => $asegurado->fecha_nac,
            't_fecha_fallece' => $fecha_fallece ? date('d/m/Y', strtotime($fecha_fallece)): null,         
            'v_ap_paterno'=> $prestacion->paciente_apellido_paterno,
            'v_ap_materno' => $prestacion->paciente_apellido_materno,
            'v_nombres'=> $prestacion->paciente_nombres,
            'v_id_estado_mujer'=> $estado_paci_mujer_fups,
            't_fec_parto' => !empty($fecha_probable_parto_fups) ? date('d/m/Y', strtotime($fecha_probable_parto_fups)) :
    (!empty($fecha_parto_fups) ? date('d/m/Y', strtotime($fecha_parto_fups)) : null),
            't_fec_ingreso' => $fecha_ingreso_fups ? date('d/m/Y', strtotime($fecha_ingreso_fups)) : null,
            'v_hora_ingreso' => $prestacion->hora_ingreso,
            'v_id_tipo_cobert' => $id_tipo_cobert,
            'v_id_dest_aseg' => '' ,
            'v_id_ipress_rc' => $ipress_destino_fups ,
            'v_num_hoja_rc' => $num_hoja_destino_fups,
            't_fec_corte_admin' => $fecha_corte_admin ? date('d/m/Y', strtotime($fecha_corte_admin)) : null,
            'v_ipress_corte_admin' => $prestacion->ipress_corte_admin,
            'v_lote_corte_admin' => $prestacion->lote_corte_admin,
            'v_num_correlativo_corte_admin' => $prestacion->num_correlativo_corte_admin,
            't_fec_alta' => $fecha_egreso_fups ? date('d/m/Y', strtotime($fecha_egreso_fups)) : null,
            'v_hora_alta' => $prestacion->hora_alta,
            'v_id_tipdoc_identidad_prof' => $id_tipodoc_ident,
            'v_numdoc_identidad_prof'=> $numdoc_ident,
            'v_id_tipo_prof' => $id_tipo_prof,
            'v_num_coleg'=> $num_coleg,
            'v_id_especialidad_prof' => $id_especialidad_prof,
            'v_num_rne'=> $num_rne,
            'v_observacion_fups' => '',
            "v_codigo_autorizacion"=> '',
      'diagnosticos' => [] 
);

$diagnosticos_data = "SELECT * FROM trama_diagnosticos WHERE id_trama_prestacion = '$prestacion->id'  AND (estado <> 0 OR estado IS NULL) and estado = '1'";
$diagnosticos_data = DB::select($diagnosticos_data);

foreach ($diagnosticos_data as $diagnosticos_uno) {
    
    $procedimientos_query = "SELECT * FROM trama_procedimientos WHERE id_trama_prestacion = '$prestacion->id' AND id_cie10 = '$diagnosticos_uno->id_cie10'  AND (estado <> 0 OR estado IS NULL) and cantidad_ejecutada != 0";
    $procedimientos = DB::select($procedimientos_query);


                    $productos_query = "SELECT p.codigo_sismed AS sismed_producto,tp.* FROM trama_productos tp INNER JOIN productos p ON p.codigo = tp.id_prod_med WHERE tp.id_trama_prestacion = '$prestacion->id' AND (tp.estado <> 0 OR tp.estado IS NULL) AND tp.id_cie10 = '$diagnosticos_uno->id_cie10'";
          $productos = DB::select($productos_query);
    $diag = $diagnosticos_uno->id_cie10;
    //$diag = str_replace('.', '', $diagnosticos_uno->id_cie10);
    $diagnostico = array(
      'v_cod_cie10' => $diag,
      'i_id_tipo_diag' => $diagnosticos_uno->id_tipo_diag,
      'v_id_diag_ie' => $diagnosticos_uno->id_identificador_diag,
        'procedimientos' => [],
        'productos' => []
    );

          foreach ($productos as $producto) {
 $diagnostico['productos'][] = array(
        'v_cod_medicamento_insumo' => !empty($producto->sismed_producto)
            ? $producto->sismed_producto
            : $producto->id_prod_med,

        'i_cant_prescrita' => $producto->cantidad_prescrita,
        'i_cant_entregada' => $producto->cantidad_entregada,
        'i_precio_envio' => intval($producto->precio),
    );
          }

    foreach ($procedimientos as $procedimiento) {
        $diagnostico['procedimientos'][] = array(
          'v_cod_cpms' => $procedimiento->id_proced,
'i_cant_indicada' => (empty($procedimiento->cantidad_indicada) || $procedimiento->cantidad_indicada == 0)
    ? $procedimiento->cantidad_ejecutada
    : $procedimiento->cantidad_indicada,
          'i_cant_ejecutada' => $procedimiento->cantidad_ejecutada,
          'i_precio_envio' => $procedimiento->precio,
         // 't_fecha_procedimiento' => $procedimiento->fecha_procedimiento,
          'v_resultado_procedimiento' => $procedimiento->resultado_procedimiento,
        );
    }

    $prestaciones['diagnosticos'][] = $diagnostico;

  }

    $data['prestacion'][] = $prestaciones;
 //   dd($data);
    
}
 //dd($data);
//print json_encode($data, JSON_UNESCAPED_SLASHES);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($data)),
    'Authorization: Bearer ' . $dni_user
));  
$response = curl_exec($ch);      
$response = json_decode($response);

      return $response;

curl_close($response);


}



public function BuscarEstadoTramaInteroperabilidad($codigo)
{
  
  $url = 'https://wadl.saludpol.gob.pe:20083/wa/prestacion_sugps_consulta.php';

  $data = array(
    'acceso' => array(
        'usuario' => 'wAiP5uG9s@cci.wa.sp',
        'clave' => '7P^Q6s*m6S@e5P-'
    ),
    'prestacion' => array(
      'v_cod_paquete' => $codigo,
  )

);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($data))
));  
$response = curl_exec($ch);
return $response;
}

public function token_saludpol($codigo)
{
    // =========================================================
    // 1. DATOS DE LA API
    // =========================================================

    $url = "https://dev-ios-be.saludpol.gob.pe:31289/api/v1/auth/token";

    $username = env('SALUDPOL_TOKEN_USERNAME');
    $password = env('SALUDPOL_TOKEN_PASSWORD');

    $payload = json_encode([
        "username" => $username,
        "password" => $password
    ]);

    $host = "dev-ios-be.saludpol.gob.pe";
    $puerto = 31289;


    // =========================================================
    // 2. OBTENER IP PÚBLICA DEL SERVIDOR
    // =========================================================

    $chIp = curl_init();

    curl_setopt_array($chIp, [
        CURLOPT_URL => "https://api.ipify.org",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $ipPublica = curl_exec($chIp);

    $ipPublicaError = null;

    if ($ipPublica === false) {
        $ipPublicaError = curl_error($chIp);
        $ipPublica = null;
    }

    curl_close($chIp);


    // =========================================================
    // 3. COMPROBAR DNS
    // =========================================================

    $ipDestino = gethostbyname($host);


    // =========================================================
    // 4. COMPROBAR CONEXIÓN TCP AL PUERTO 31289
    // =========================================================

    $inicio = microtime(true);

    $socket = @fsockopen(
        $host,
        $puerto,
        $socketErrno,
        $socketError,
        10
    );

    $tiempoConexion = round(
        microtime(true) - $inicio,
        3
    );

    if ($socket) {

        fclose($socket);

        $conexionPuerto = [
            "conectado" => true,
            "tiempo" => $tiempoConexion
        ];

    } else {

        $conexionPuerto = [
            "conectado" => false,
            "errno" => $socketErrno,
            "error" => $socketError,
            "tiempo" => $tiempoConexion
        ];
    }


    // =========================================================
    // 5. REALIZAR PETICIÓN A SALUDPOL
    // =========================================================

    $ch = curl_init();

    curl_setopt_array($ch, [

        CURLOPT_URL => $url,

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS => $payload,

        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json"
        ],

        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,

        CURLOPT_USERAGENT => "PostmanRuntime/7.36.0",

        // Solo para diagnóstico.
        // En producción sería mejor validar el certificado.
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,

        CURLOPT_CONNECTTIMEOUT => 15,

        CURLOPT_TIMEOUT => 30,
    ]);


    // =========================================================
    // 6. EJECUTAR CURL
    // =========================================================

    $response = curl_exec($ch);

    $curlInfo = curl_getinfo($ch);


    // =========================================================
    // 7. SI CURL FALLA
    // =========================================================

    if ($response === false) {

        $error = curl_error($ch);
        $errno = curl_errno($ch);

        curl_close($ch);

        dd([

            "success" => false,

            "ip_publica_servidor" => [
                "ip" => $ipPublica,
                "error" => $ipPublicaError
            ],

            "dns" => [
                "host" => $host,
                "ip" => $ipDestino
            ],

            "destino" => [
                "host" => $host,
                "puerto" => $puerto
            ],

            "conexion_puerto" => $conexionPuerto,

            "curl" => [
                "errno" => $errno,
                "error" => $error,
                "info" => $curlInfo
            ]
        ]);
    }


    // =========================================================
    // 8. RESPUESTA HTTP
    // =========================================================

    $httpCode = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);


    return $response;
    // =========================================================
    // 9. MOSTRAR RESULTADO
    // =========================================================
/*
    // =========================================================
    // 9. MOSTRAR RESULTADO
    // =========================================================

    dd([

        "success" => true,

        "ip_publica_servidor" => [
            "ip" => $ipPublica,
            "error" => $ipPublicaError
        ],

        "dns" => [
            "host" => $host,
            "ip" => $ipDestino
        ],

        "destino" => [
            "host" => $host,
            "puerto" => $puerto
        ],

        "conexion_puerto" => $conexionPuerto,

        "curl" => [
            "http_code" => $httpCode,
            "response" => $response,
            "info" => $curlInfo
        ]
    ]);*/
}



}