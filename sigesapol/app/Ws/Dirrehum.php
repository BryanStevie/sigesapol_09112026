<?php

namespace App\Ws;
use DB;
use SoapClient;
use Illuminate\Support\Facades\Auth;
class Dirrehum
{
  public function BuscarTitularFamiliar($tipo_doc, $nro_doc)
  {

    if (!Auth::check()):
      return redirect('/auth/login');
  endif;

    $soapClient = new SoapClient(env('DIRREHUM_URI'), array('trace' => 1, "encoding" => "UTF-8", 'location' => env('DIRREHUM_URI')));

    $parametros = array(
      "TipoBusqueda" => $tipo_doc,
      "Documento" => $nro_doc,
      "Usuario" => "DirSaPol",
      "Clave" => "6hHPb",
      "Sistema" => "SIGESAPOL",
      "Operador" => "42214047"
    );

    /*$parametros = array(
      "TipoBusqueda" => $tipo_doc,
      "Documento" => $nro_doc,
      "Usuario" => "D1rr3huM",
      "Clave" => "NuevoHost@",
      "Sistema" => "SISPOL",
      "Operador" => "42214047"
    );*/

    return $soapClient->BuscarTitularFamiliar($parametros);
    //return $soapClient->CallBuscarTitularFamiliar($parametros);
  }

  public function FotoPNP($cip)
  {
    if (!Auth::check()):
      return redirect('/auth/login');
  endif;
    $soapClient = new SoapClient(env('DIRREHUM_FOTO_URI'), array('trace' => 1, "encoding" => "UTF-8", 'location' => env('DIRREHUM_FOTO_URI')));

    $parametros = array(
      "CIP" => $cip,
      "Sistema" => "SIGESAPOL",
      "Operador" => "42214047",
      "Usuario" => "DirSaPol",
      "Clave" => "6hHPb"
    );

    
    return $soapClient->ImgPNP($parametros);
    
  }

  public function BuscarPrestacionWsMininter($id)
  {
    if (!Auth::check()):
      return redirect('/auth/login');
  endif;
    $prestacion = DB::table('trama_prestacion')
    ->select('trama_prestacion.*')
    ->where('id',$id)
    ->first();

    $search  = array("+", ",","'","/");
    $replace = array("\+", "\,", "\'", "\/");

    //dd($prestacion->paciente_nombres);
    $prestacion->paciente_nombres = str_replace($search,$replace,$prestacion->paciente_nombres);

    //dd(intval($prestacion->estado_paci_mujer_fups));
    //intval int ///// strval var
    $codigo_renipress = str_pad($prestacion->codigo_renipress, 8, "0", STR_PAD_LEFT);
    $numero_lote = strval($prestacion->numero_lote);
    $numero_correlativo = strval($prestacion->numero_correlativo);
    $ipress_referencia_fups = strval($prestacion->ipress_referencia_fups);
    $hoja_referencia_fups = strval($prestacion->hoja_referencia_fups);
    $tipo_doc_identidad = strval($prestacion->tipo_doc_identidad);
    $id_hist_clinica = strval($prestacion->id_hist_clinica);
    //$id_hist_clinica = '0';
    $id_tipo_cobert = strval($prestacion->id_tipo_cobert);
    //$id_upss_fups = strval($prestacion->id_upss_fups);
    $id_upss_fups = '230100';
    $fecha_atencion_fups = strval($prestacion->fecha_atencion_fups);
    $fecha_ingreso_fups = strval($prestacion->fecha_ingreso_fups);
    $fecha_inicio_adm = strval($prestacion->fecha_inicio_adm);
    $fecha_cierre_adm_fups = strval($prestacion->fecha_cierre_adm_fups);
    $fecha_egreso_fups = strval($prestacion->fecha_egreso_fups);
    $renipress_vincula_fups = strval($prestacion->renipress_vincula_fups);
    $num_lote_vincula_fups = strval($prestacion->num_lote_vincula_fups);
    $num_vincula_fups = strval($prestacion->num_vincula_fups);
    //$estado_paci_mujer_fups = intval($prestacion->estado_paci_mujer_fups);
    $estado_paci_mujer_fups = '0';
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
    //$id_especialidad_prof = strval($prestacion->id_especialidad_prof);
    $id_especialidad_prof = $prestacion->id_especialidad_prof;
    if ($id_especialidad_prof === null) {
      $id_especialidad_prof = '0';
  }
  $especialidad_prof = strval($id_especialidad_prof);
    $num_rne = strval($prestacion->num_rne);
    $observacion_fups = strval($prestacion->num_rne);
    $ruc_ipress = strval($prestacion->ruc_ipress);

    $url = 'https://test-wsdl.saludpol.gob.pe:8083/wa/prestacion_sugps.php';

    $data = array(
      'acceso' => array(
          'usuario' => 'wAiP5uG9s@cci.wa.sp',
          'clave' => '7P^Q6s*m6S@e5P-'
      ),
      'prestacion' => array(
          'v_renipress' => $codigo_renipress,
          'v_numero_lote_fups' => $numero_lote,
          'v_numero_fups'=> $numero_correlativo,
          'i_id_proc_lugar_ate_fups' => '4',
          'v_ipress_referencia_fups' => $ipress_referencia_fups,
          'v_hoja_referencia_fups'=> $hoja_referencia_fups,
          'tipo_doc_identidad' => $tipo_doc_identidad,
          'nro_doc_identidad' => $prestacion->paciente_nro_doc_identidad,
          'apellido_paterno'=> $prestacion->paciente_apellido_paterno,
          'apellido_materno' => $prestacion->paciente_apellido_materno,
          'nombres'=> $prestacion->paciente_nombres,
          //'i_id_hist_clinica' => $id_hist_clinica,
          'v_numero_historia' => $id_hist_clinica,
          'v_id_tipo_cobert' => $id_tipo_cobert,
          'v_id_upss_fups'=> $id_upss_fups,
          't_fecha_atencion_fups' => $fecha_atencion_fups,
          't_fecha_ingreso_fups' => $fecha_ingreso_fups,
          't_fecha_inicio_adm'=> $fecha_inicio_adm,
          't_fecha_cierre_adm_fups' => $fecha_cierre_adm_fups,
          't_fecha_egreso_fups' => $fecha_egreso_fups,
          'v_renipress_vincula_fups'=> $renipress_vincula_fups,
          'v_num_lote_vincula_fups' => $num_lote_vincula_fups,
          'v_num_vincula_fups' => $num_vincula_fups,
          'i_estado_paci_mujer_fups'=> $estado_paci_mujer_fups,
          't_fecha_probable_parto_fups' => $fecha_probable_parto_fups,
          't_fecha_parto_fups' => $fecha_parto_fups,
          'i_id_destino_aseg_fups'=> $id_destino_aseg_fups,
          'v_ipress_destino_fups' => $ipress_destino_fups ,
          'v_num_hoja_destino_fups' => $num_hoja_destino_fups,
          't_fecha_fallece'=> $fecha_fallece,
          'v_numero_documento_autorizacion_principal' => $numero_acreditacion_siteds,
          'v_id_tipodoc_ident' => $id_tipodoc_ident,
          'v_numdoc_ident'=> $numdoc_ident,
          'v_ape_paterno' => $doc_ape_paterno,
          'v_ape_materno'=> $doc_ape_materno,
          'v_nombres' => $doc_nombres,
          'v_id_tipo_prof' => $id_tipo_prof,
          'v_num_coleg'=> $num_coleg,
          'i_id_especialidad_prof' => $especialidad_prof,
          'v_num_rne'=> $num_rne,
          'v_observacion_fups' => $observacion_fups,
          'v_ruc_ipress' => $ruc_ipress,
          'v_tipo_docpago'=> '',
          'v_nro_docpago' => '',
          'v_correlativo' => '',
          'v_nro_lote'=> '',
          
      ),
      'diagnosticos' => array(),
      'productos' => array(),
      'procedimientos' => array()
  );

    $diagnosticos_data = "SELECT * FROM trama_diagnosticos where id_trama_prestacion = '$id'";
    $diagnosticos_data = DB::select($diagnosticos_data);

    foreach ($diagnosticos_data as $diagnosticos) {
      $data['diagnosticos'][] =  array(
      /*  'v_renipress' => $codigo_renipress,
        'v_numero_lote_fups' => $numero_lote,
        'v_numero_fups' => $numero_correlativo, */
        'v_id_cie10' => $diagnosticos->id_cie10,
        'v_id_identificador_diag' => $diagnosticos->id_identificador_diag,
        'i_id_tipo_diag' => $diagnosticos->id_tipo_diag
    );

  }
    
  $procedimientos = "SELECT * FROM trama_procedimientos where id_trama_prestacion = '$id'";
  $procedimientos = DB::select($procedimientos);

  foreach ($procedimientos as $procedimientos) {
    $data['procedimientos'][] = array(
/*      'v_renipress' => $codigo_renipress,
      'v_numero_lote_fups' => $numero_lote,
      'v_numero_fups' =>$numero_correlativo,*/
      'v_id_cie10' => $procedimientos->id_cie10,
      'v_id_proced' => $procedimientos->id_proced,
      'i_cantidad_indicada' => $procedimientos->cantidad_indicada,
      'i_cantidad_ejecutada' => $procedimientos->cantidad_ejecutada,
      'd_precio' => $procedimientos->precio,
      'd_valorizacion' => $procedimientos->valorizacion,
      't_fecha_procedimiento' => $procedimientos->fecha_procedimiento,
      'v_resultado_procedimiento' => $procedimientos->resultado_procedimiento,
  );

}           
$productos = "SELECT * FROM trama_productos where id_trama_prestacion = '$id'";
  $productos = DB::select($productos);

  foreach ($productos as $productos) {
    $data['productos'][] = array(
/*      'v_renipress' => $codigo_renipress,
      'v_numero_lote_fups' => $numero_lote,
      'v_numero_fups' =>$numero_correlativo,*/
      'v_id_cie10' => $productos->id_cie10,
      'i_id_prod_med' => $productos->id_prod_med,
      'i_cantidad_prescrita' => $productos->cantidad_prescrita,
      'i_cantidad_entregada' => $productos->cantidad_entregada,
      //'d_precio' => $productos->precio,
      'd_precio' => intval($productos->precio),
      //'d_valorizacion' => $productos->valorizacion,
      'd_valorizacion' => intval($productos->valorizacion),
  );

} 

  //$data['diagnosticos'] = array();
  //$data['diagnosticos'][] = $diagnosticos;
  //$data['procedimientos'] = array();
  //$data['procedimientos'][] = $procedimientos;
  //$data['productos'] = array();
  //$data['productos'][] = $productos;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ));
     
    $response = curl_exec($ch);
    $response = json_decode($response);
   
    //$response = $response->prestacion;
    //$response = $response[0];
    //dd($response);
    return $response;
  
    curl_close($ch);

  }
  
  public function BuscarPrestacionWsMininterrespuesta($id)
  {
    if (!Auth::check()):
      return redirect('/auth/login');
  endif;
    $url = 'https://test-wsdl.saludpol.gob.pe:8083/wa/prestacion_sugps.php';
    $data = array(
      'acceso' => array(
          'usuario' => 'wAiP5uG9s@cci.wa.sp',
          'clave' => '7P^Q6s*m6S@e5P-'
      ),
      'prestacion' => array(
          'idprestacion' => $id,
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
  $response = json_decode($response);
  $response = $response->prestacion;
  $response = $response[0];

  return $response;

  curl_close($ch);

}
 
  public function MaspePNP($cip)
  {

    $soapClient = new SoapClient(env('DIRREHUM_MASPE_URI'), array('trace' => 1, 'location' => env('DIRREHUM_MASPE_URI')));

     /*
    $parametros = array(
      "CIP" => $cip,
      "Usuario" => "DirSaPol",
      "Clave" => "6hHPb",
      "Sistema" => "SIGESAPOL",
      "Operador" => "42214047"
      
    );
    */
    $parametros = array(
      "Cip" => $cip,
      "Usuario" => "DirSaPol",
      "Clave" => "6hHPb",
      "Sistema" => "Test",
      "Operador" => "31832200"
      
    );

    
    return $soapClient->Maspe($parametros);
    
  }
}