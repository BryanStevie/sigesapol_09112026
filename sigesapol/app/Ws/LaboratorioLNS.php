<?php

namespace App\Ws;

use Exception;
use SoapClient;
use SoapFault;
use DB;

class LaboratorioLNS
{
  /*public function InsertarOrdenLaboratorio($listaPruebasLab)
  {
    try {
      $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));

      $parametros = array(
        "clienteUsuario" => "u5_fdm-mr*",
        "clienteClave" => "p@_55LN5#",
        "nombrepc" => "PC",
        "clienteIp" => "000.00.00.00",
        "clienteMac" => "XX-XX-XX-XX-XX-XX",
        "lista" => $listaPruebasLab
      );

      $respuesta = $soapClient->InsertOrdenLab($parametros);

      return $respuesta->InsertOrdenLabResult;

    } catch (SoapFault $e) {
      // Manejar errores relacionados con SOAP
      // Log o respuesta adecuada
      return "Error al conectar con el servicio web: " . $e->getMessage();
    } catch (Exception $e) {
        // Otros errores
        // Log o respuesta adecuada
        return "Error: " . $e->getMessage();
    }
  }*/

  public function CallVerOrdenesLabPorDocumento($documento)
  {
    $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));

    $parametros = array(
      "clienteUsuario" => "u5_fdm-mr*",
      "clienteClave" => "p@_55LN5#",
      "nombrepc" => "PC",
      "clienteIp" => "000.00.00.00",
      "clienteMac" => "XX-XX-XX-XX-XX-XX",
      "numDocumento" => $documento
    );

    $respuesta = $soapClient->CallVerOrdenesLabPorDocumento_Nuevo($parametros);
    // $respuesta = $soapClient->CallVerOrdenesLabPorDocumento($parametros);

    return $respuesta->CallVerOrdenesLabPorDocumento_NuevoResult;
    // return $respuesta->CallVerOrdenesLabPorDocumentoResult;
  }
///// auditorai
  public function CallResultadosLaboratorioSinFiltro($documento,$ini,$fin)
  {
    $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));
    $parametros = array(
      "clienteUsuario" => "u5_fdm-mr*",
      "clienteClave" => "p@_55LN5#",
      "numDocumento" =>  $documento,
      "fechaInicio" => $ini,
      "fechaFin" => $fin
    );
    $respuesta = $soapClient->CallResultadosLaboratorioSinFiltroFecha($parametros);
   
    return $respuesta->CallResultadosLaboratorioSinFiltroFechaResult;
  }

  public function CallResultadosImagenes($documento,$ini,$fin)
  {
    $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));
    $parametros = array(
      "clienteUsuario" => "u5_fdm-mr*",
      "clienteClave" => "p@_55LN5#",
      "nombrepc" => "PC",
      "clienteIp" => "000.00.00.00",
      "clienteMac" => "XX-XX-XX-XX-XX-XX",
      "numDocumentoPaciente" =>  $documento,
      "fechaInicioAtencion" => $ini,
      "fechaFinAtencion" => $fin

    );
    $respuesta = $soapClient->CallExamenesRadCpt($parametros);

    return $respuesta->CallExamenesRadCptResult;
  }
////auditoria
  public function CallVistaResultadosLaboratorio($documento, $fecha_toma, $nro_orden)
  {
    $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));

    $parametros = array(
      "clienteUsuario" => "u5_fdm-mr*",
      "clienteClave" => "p@_55LN5#",
      "numDocumento" => $documento,
      "fechaToma" => $fecha_toma,
      "nroOrden" => $nro_orden
    );

    $respuesta = $soapClient->CallVistaResultadosLaboratorio_Prueba_V2($parametros);
    //$respuesta = $soapClient->CallVistaResultadosLaboratorio($parametros);
//->ResultadosLabView
    return $respuesta->CallVistaResultadosLaboratorio_Prueba_V2Result;
    //return $respuesta->CallVistaResultadosLaboratorioResult;
  }

  public function CallVerSecuencialLaboratorio()
  {
    try {

      $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));

      $parametros = array(
        "clienteUsuario" => "u5_fdm-mr*",
        "clienteClave" => "p@_55LN5#",
        "nombrepc" => "PC",
        "clienteIp" => "000.00.00.00",
        "clienteMac" => "XX-XX-XX-XX-XX-XX",
        //"codigoPrueba" => "DEV"
        "codigoPrueba" => "PROD"
      );

      $respuesta = $soapClient->ObtenerSecuencial($parametros);

      return $respuesta->ObtenerSecuencialResult;

    } catch (SoapFault $sf) {
      // Manejo de errores SOAP
      return 'Error en la llamada al servicio SOAP ObtenerSecuencial: ' . $sf->getMessage();
    } catch (Exception $e) {
        // Otros errores
        return 'Error no relacionado con SOAP en ObtenerSecuencial: ' . $e->getMessage();
    }
  }

  public function CallResultadoExamenPorCodCPMS($documento, $examen)
  {
    try {
      $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));

      $parametros = array(
        "clienteUsuario" => "u5_fdm-mr*",
        "clienteClave" => "p@_55LN5#",
        "clienteIp" => "000.00.00.00",
        "clienteMac" => "XX-XX-XX-XX-XX-XX",
        "numDocumento" => $documento,
        "codCpmsExamen" => $examen
      );

      $respuesta = $soapClient->CallResultadoExamenPorCodCPMS($parametros);
      //->ResultadosLabView
      $result = $respuesta->CallResultadoExamenPorCodCPMSResult;
      //$result = isset($result->ResultadosLabView) ? null : $result->ResultadosLabView;
      return $result->ResultadosLabView;
    } catch (SoapFault $e) {
      // Manejar errores relacionados con SOAP
      // Log o respuesta adecuada
      return "Error al conectar con el servicio web: " . $e->getMessage();
    } catch (Exception $e) {
        // Otros errores
        // Log o respuesta adecuada
        return "";
    }
    
  }

  public function UpdateOrdenesLaboratorioValidados($codigosJSON)
  {
    try {
      $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));

      $parametros = array(
        "clienteUsuario" => "u5_fdm-mr*",
        "clienteClave" => "p@_55LN5#",
        "clienteIp" => "000.00.00.00",
        "clienteMac" => "XX-XX-XX-XX-XX-XX",
        "codigosJSON" => $codigosJSON
      );

      $respuesta = $soapClient->CallValidarEstados($parametros);

      return $respuesta->CallValidarEstadosResult->RespuestaOrdenesValidados;

    } catch (SoapFault $e) {
      // Manejar errores relacionados con SOAP
      // Log o respuesta adecuada
      return "Error al conectar con el servicio web: " . $e->getMessage();
    } catch (Exception $e) {
        // Otros errores
        // Log o respuesta adecuada
        return "Error: " . $e->getMessage();
    }
  }

  public function CallReporteLab($fecha_inicio, $fecha_fin, $codigo_cpms)
  {
    $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));

    $parametros = array(
      "clienteUsuario" => "u5_fdm-mr*",
      "clienteClave" => "p@_55LN5#",
      "nombrepc" => "PC",
      "clienteIp" => "000.00.00.00",
      "clienteMac" => "XX-XX-XX-XX-XX-XX",
      "fechaInicio" => $fecha_inicio,
      "fechaFin" => $fecha_fin,
      "codigoCPMS" => $codigo_cpms
    );
    
    $respuesta = $soapClient->CallReporteLab($parametros);
    return $respuesta->CallReporteLabResult->RespuestaReporte;
  }

  
  public function ObtenerOrdenes($array_cod_ordenes)
  {
    $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array('trace' => 1, "encoding" => "UTF-8"));

    $parametros = array(
      "clienteUsuario" => "u5_fdm-mr*",
      "clienteClave" => "p@_55LN5#",
      "nombrepc" => "PC",
      "clienteIp" => "000.00.00.00",
      "clienteMac" => "XX-XX-XX-XX-XX-XX",
      "ordenes" => $array_cod_ordenes
    );
    
    $respuesta = $soapClient->CallListarOrdenesConSinValidacion($parametros);
    return $respuesta;
  }

  public function InsertarOrdenLaboratorioNuevo($listaPruebasLab)
  {
    try {
      $soapClient = new SoapClient(env('LABORATORIO_LNS_URI'), array(
        'trace' => 1, 
        "encoding" => "UTF-8",
        'exceptions' => true,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS
      ));
      // Atributo de tiempo conexion cuando sea necesaria
      // 'connection_timeout' => 30,

      $parametros = array(
        "clienteUsuario" => "u5_fdm-mr*",
        "clienteClave" => "p@_55LN5#",
        "nombrepc" => "PC",
        "clienteIp" => "000.00.00.00",
        "clienteMac" => "XX-XX-XX-XX-XX-XX",
        "lista" => $listaPruebasLab,
        "codigoPrueba" => "PROD"
      );

      $respuesta = $soapClient->InsertOrdenLab($parametros);
      return $respuesta->InsertOrdenLabResult;

    } catch (SoapFault $e) {
      return "Error al conectar con el servicio web: " . $e->getMessage();
    } catch (Exception $e) {
      return "Error: " . $e->getMessage();
    }
  }

  public function Test_CallVerSecuencialLaboratorio() {
    $resultado = DB::table('generador_secuencia')
    ->select('secuencia as secuencial')
    ->where('id_generador_secuencia', '2')
    ->first();

    $secuencial_nuevo = intval($resultado->secuencial) + 1;

    $filas_afectadas = DB::table('generador_secuencia')
      ->where('id_generador_secuencia', 2)
      ->update([
          'secuencia' => $secuencial_nuevo
      ]);

    return (object) [
      'codigoMensaje' => '00',
      'descripcionMensaje' => $resultado->secuencial
    ];
  }

  public function Test_InsertarOrdenLaboratorioNuevo($listaPruebasLab)
  {
    return (object) [
      'codigoMensaje' => '00',
      'descripcionMensaje' => 'Mensaje por Default'
    ];
  }

}