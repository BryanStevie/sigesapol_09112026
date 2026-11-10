<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Ws\Reniec;
use App\Ws\Dirrehum;
use App\Ws\Saludpol;
use App\Services\API_Saludpol;
use Auth;

class WebServiceController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    } 
  
    public function Reniec($nro_documento)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $Reniec = new Reniec();
    $result = $Reniec->BuscarDNI($nro_documento);
    
    $log = new \App\Models\LogWs;
    $log->ws = 'ws_reniec';
    $log->tipo_doc_buscado = 1;
    $log->doc_buscado = $nro_documento;
    $log->created_at = Carbon::now();
    $log->created_us = Auth::user()->id;
    $log->save();

    return json_decode($result,true);
  }  
  
  public function ReniecName($nombre,$paterno,$materno)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $Reniec = new Reniec();
    $result = $Reniec->BuscarNombre($nombre,$paterno,$materno);
    return json_decode($result,true);
  }

  public function Dirrehum($tipo_doc, $nro_documento)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $Dirrehum = new Dirrehum();
    $result = $Dirrehum->BuscarTitularFamiliar($tipo_doc, $nro_documento);

    $log = new \App\Models\LogWs;
    $log->ws = 'ws_dirrehum';
    $log->tipo_doc_buscado = $tipo_doc;
    $log->doc_buscado = $nro_documento;
    $log->created_at = Carbon::now();
    $log->created_us = Auth::user()->id;
    $log->save();

    return json_encode($result->BuscarTitularFamiliarResult->TitularFamiliar, true);
  }

  public function DirrehumFoto($cip)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $Dirrehum = new Dirrehum();
    $result = $Dirrehum->FotoPNP($cip);

    $log = new \App\Models\LogWs;
    $log->ws = 'ws_dirrehum_foto';
    $log->tipo_doc_buscado = null;
    $log->doc_buscado = $cip;
    $log->created_at = Carbon::now();
    $log->created_us = Auth::user()->id;
    $log->save();
    
    return json_encode($result->ImgPNPResult->ImgFotoPNP, true);
  }

  public function DirrehumMaspe($cip)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $Dirrehum = new Dirrehum();
    $result = $Dirrehum->MaspePNP($cip);

    $log = new \App\Models\LogWs;
    $log->ws = 'ws_dirrehum_maspe';
    $log->tipo_doc_buscado = null;
    $log->doc_buscado = $cip;
    $log->created_at = Carbon::now();
    $log->created_us = Auth::user()->id;
    $log->save();

    return json_encode($result->MaspeResult, true);
  }
  public function SaludpolBuscarTitularFamiliar($tipo_doc, $nro_documento, $fecha_validacion = '')
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $Saludpol = new Saludpol();
    $result = $Saludpol->BuscarTitularFamiliar($tipo_doc, $nro_documento, $fecha_validacion);
    
    $log = new \App\Models\LogWs;
    $log->ws = 'ws_saludpol_titular';
    $log->tipo_doc_buscado = $tipo_doc;
    $log->doc_buscado = $nro_documento;
    $log->created_at = Carbon::now();
    $log->created_us = Auth::user()->id;
    $log->save();

    return json_encode($result) ;
  }

  public function SaludpolBuscarMedico($nro_documento)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $Saludpol = new Saludpol();
    $result = $Saludpol->BuscarMedico($nro_documento);
    return $result;
  }

  public function Saludpol($tipo_doc, $nro_documento, $fecha_validacion = '')
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $Saludpol = new Saludpol();
    $result = $Saludpol->BuscarTitularFamiliar($tipo_doc, $nro_documento, $fecha_validacion);
    
    $log = new \App\Models\LogWs;
    $log->ws = 'ws_saludpol';
    $log->tipo_doc_buscado = $tipo_doc;
    $log->doc_buscado = $nro_documento;
    $log->created_at = Carbon::now();
    $log->created_us = Auth::user()->id;
    $log->save();

    return json_encode($result);
  }

  public function Saludpol_paciente($id_tipo_doc_ident, $nro_doc_ident, $fecha_validacion = '')
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $Saludpol = new Saludpol();
    $result = $Saludpol->BuscarTitularFamiliar_paciente($id_tipo_doc_ident, $nro_doc_ident, $fecha_validacion);
    return json_encode($result);
  }

  public function ws_mininter($id_prestacion)
  {
    $Saludpol = new Dirrehum();
    $result = $Saludpol->BuscarPrestacionWsMininter($id_prestacion);
    return json_encode($result);
  }

  //NUEVA FUNCIÓN PARA EL MÓDULO ASEGURADOS
  private function obtenerDatosSaludpol(Request $request,$ws = 'saludpol_API') {
      $api = new API_Saludpol();

      $datos = $api->post('asegurados/afiliacion/completo', [
          'tipoDocumento'   => $request->input('tipoDocumento', '1'),
          'numeroDocumento' => $request->input('numeroDocumento'),
          'fechaConsulta'   => Carbon::now()->format('Y-m-d'),
      ]);

      $log = new \App\Models\LogWs;
      $log->ws = $ws;
      $log->tipo_doc_buscado = $request->input('tipoDocumento');
      $log->doc_buscado = $request->input('numeroDocumento');
      $log->fechaini_afiliacion = !empty($datos['data']['fechaInicioAfiliacion']) ? Carbon::createFromFormat('d/m/Y', $datos['data']['fechaInicioAfiliacion'])->format('Y-m-d') : null;
      $log->fechafin_afiliacion = !empty($datos['data']['fechaFinAfiliacion']) ? Carbon::createFromFormat('d/m/Y', $datos['data']['fechaFinAfiliacion'])->format('Y-m-d') : null;
      $log->estado_afiliacion = !empty($datos['data']['estadoAsegurado']) ? $datos['data']['estadoAsegurado']  : null;
      $log->created_at = Carbon::now();
      $log->created_us = Auth::user()->id;
      $log->save();

      return $datos;
  }

  private function extraerStatusCode($mensaje) {
    if (preg_match('/resulted in a `?(\d{3})/', $mensaje, $match)) {
      return (int) $match[1];
    } elseif (preg_match('/\b(400|401|403|404|422|500|502|503)\b/', $mensaje, $match)) {
      return (int) $match[1];
    }
    return 500;
  }

  private function mensajePorCodigo($status){
    switch ($status) {
      case 400:
        return 'Verifique el tipo y número de documento ingresados.';
      case 401:
        //return 'No autorizado por el servicio de Saludpol';
        return 'Problema de conexión con Saludpol. Vuelva a intentarlo en unos minutos.';
      case 404:
        return 'Asegurado no encontrado en registro de Saludpol';
      case 500:
        return 'Error interno del servicio de Saludpol';
      default:
        return 'Error al consultar Saludpol (código: ' . $status . ')';
    }
  }

  // NUEVA FUNCIÓN PARA EL MÓDULO ASEGURADOS
  public function consultarSaludpol(Request $request) {
    try {
      $datos = $this->obtenerDatosSaludpol($request, 'saludpol_API');
      return response()->json(['success' => true, 'data' => $datos], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json(['success' => false, 'message' => 'Datos inválidos', 'errors' => $e->errors()], 400);
    } catch (\Illuminate\Auth\AuthenticationException $e) {
      return response()->json(['success' => false, 'message' => 'No autorizado para consultar Saludpol'], 401);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['success' => false, 'message' => 'No se encontró información en Saludpol'], 404);
    } catch (\Exception $e) {
      $mensaje = $e->getMessage();
      $status = 500;
      if (preg_match('/resulted in a `?(\d{3})/', $mensaje, $match)) {
        $status = (int) $match[1];
      } elseif (preg_match('/\b(400|401|403|404|422|500|502|503)\b/', $mensaje, $match)) {
        $status = (int) $match[1];
      }
      return response()->json([
        'success' => false,
        'message' => $this->mensajePorCodigo($status),
        'error'   => $mensaje
      ], $status);
    }
  }
  
  public function consultarSaludpol2(Request $request){
    try {
      $datos = $this->obtenerDatosSaludpol($request, $request->origen);

      if (!isset($datos['data'])) {
        return (object)[];
      }

      $data = $datos['data'];

      if (!isset($data['estadoAsegurado'])) {
        $status = 404;
        return (object)[
          'success' => false,
          'status'  => $status,
          'message' => $this->mensajePorCodigo($status),
          'error'   => 'Paciente no encontrado en SALUDPOL'
        ];
      }
      if($data['estadoAsegurado'] != 'ACTIVO'){
        $status = 300;
        return (object)[
          'success' => false,
          'status'  => $status,
          'message' => $this->mensajePorCodigo($status),
          'error'   => 'estadoAsegurado != ACTIVO'
        ];
      }

      // return json_decode(json_encode($data));
      return json_decode(json_encode($this->adaptarApiASaludpolWs($data)));
    } catch (\Exception $e) {
      $mensaje = $e->getMessage();
      $status  = $this->extraerStatusCode($mensaje);

      return (object)[
        'success' => false,
        'status'  => $status,
        'message' => $this->mensajePorCodigo($status),
        'error'   => $mensaje
      ];
    }
  }

  private function valor($array, $key, $default = ''){
    return isset($array[$key]) ? $array[$key] : $default;
  }

  private function calcularEdad($fecha){
    if (empty($fecha)) {
      return '';
    }
    return \Carbon\Carbon::createFromFormat('d/m/Y', $fecha)->age;
  }

  private function adaptarApiASaludpolWs($api){
    return array(
      //IGUALAR A LOS CAMPOS WS SALUDPOL
      'nompaisdelafiliado'  => '',
      'nomtipdocafiliado'   => $this->valor($api, 'documentoAsegurado'),
      'nrodocafiliado'      => $this->valor($api, 'numeroDocumentoAsegurado'),
      'apepatafiliado'      => $this->valor($api, 'apellidoPaternoAsegurado'),
      'apematafiliado'      => $this->valor($api, 'apellidoMaternoAsegurado'),
      'apecasafiliado'      => '',
      'nomafiliado'         => $this->valor($api, 'nombreAsegurado'),
      'estado'              => $this->valor($api, 'estadoAsegurado'),
      'situacion'           => $this->valor($api, 'situacionAsegurado'),
      'parentesco'          => $this->valor($api, 'parentescoAsegurado'),
      'fecnacafiliado'      => $this->valor($api, 'fechaNacimientoAsegurado'),
      'nomsexo'             => strtoupper(substr($this->valor($api, 'sexo'), 0, 1)),
      'cip'                 => $this->valor($api, 'cipTitular'),
      'grado'               => $this->valor($api, 'gradoTitular'),
      'ubigeo'              => $this->valor($api, 'ubigeoDomicilio'),
      'discapacidad'        => $this->valor($api, 'discapacidad') == 'SI' ? '1' : '0',
      'nomtipdoctitular'    => $this->valor($api, 'documentoTitular'),
      'nrodoctitular'       => $this->valor($api, 'numeroDocumentoTitular'),
      'nomtitular'          => $this->valor($api, 'nombreTitular'),
      'apepattitular'       => $this->valor($api, 'apellidoPaternoTitular'),
      'apemattitular'       => $this->valor($api, 'apellidoMaternoTitular'),
      'apecastitular'       => '',
      'caducidad'           => !empty($this->valor($api, 'fechaFinAfiliacion')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $this->valor($api, 'fechaFinAfiliacion'))->format('Ymd'): null,
      'otroseguro'          => '',
      'unidad_pnp'          => '',
      'edadafiliado'        => $this->calcularEdad($this->valor($api, 'fechaNacimientoAsegurado')),
      // CAMPOS ADICIONALES QUE RETORNA EL API SALUDPOL
      'situacionTitular'        => $this->valor($api, 'situacionTitular'),
      'tipoSangre'              => $this->valor($api, 'tipoSangre'),
      'departamentoDomicilio'   => $this->valor($api, 'departamentoDomicilio'),
      'provinciaDomicilio'      => $this->valor($api, 'provinciaDomicilio'),
      'distritoDomicilio'       => $this->valor($api, 'distritoDomicilio'),
      'direccionDomicilio'      => $this->valor($api, 'direccionDomicilio'),
      'telefonoAsegurado'       => $this->valor($api, 'telefonoAsegurado'),
      'emailAsegurado'          => $this->valor($api, 'emailAsegurado'),
      'estadoCivilAsegurado'    => $this->valor($api, 'estadoCivilAsegurado'),
      'fechaConsulta'           => $this->valor($api, 'fechaConsulta'),
      'fechaInicioAfiliacion'   => $this->valor($api, 'fechaInicioAfiliacion'),
      'condicionAsegurado'      => $this->valor($api, 'condicionAsegurado')
    );
  }
}
