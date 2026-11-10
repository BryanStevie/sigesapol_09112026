<?php

namespace App\Ws;
use Illuminate\Support\Facades\Auth;
use SoapClient;
require_once('funciones/function.php');
class Reniec
{
  public function BuscarDNI($nro_doc)
  {

    if (!Auth::check()):
      return redirect('/auth/login');
  endif;

    $soapClient = new SoapClient(env('RENIEC_URI'), array('trace' => 1, "encoding" => "ISO-8859-1"));
    /*if ($soapClient->getError()) {
      echo "no se pudo conectar al ws";
      die();
    }*/

    //DIRSAPOL
    $parametros = array(
      "clienteUsuario" => "DIRSAPOL",
      "clienteClave" => "WUK9XPhx",
      "servicioCodigo" => "WS_RENIEC_MAY_MEN",
      "clienteSistema" => "SOAP_DESARROLLO",
      "clienteIp" => "172.31.2.249",
      "clienteMac" => "AA:BB:CC:DD:EE:FF",
      "dniAutorizado" => "46142906",
      "tipoDocUserClieFin" => "1",
      "nroDocUserClieFin" => "391402",
      "inDni" => $nro_doc,
      "inPioridad" => "priority"
    );

    //LNS
    /*$parametros = array(
      "clienteUsuario" => "R3NI3C_HOSPITAL",
      "clienteClave" => "Hospital@2020",
      "clienteSistema" => "Seguridad",
      "clienteIp" => "172.31.42.129",
      "clienteMac" => "50-E0-85-FB-30-60",
      "dniAutorizado" => "42214047",
      "tipoDocUserClieFin" => "1",
      "nroDocUserClieFin" => "00000000",
      "inDni" => $nro_doc,
      "inPioridad" => "priority"
    );*/

    $respuesta = $soapClient->consultarDniMenor($parametros);
    //$respuesta = $soapClient->CallConsultarDniMayor($parametros);
    //$respuesta->resultadoDniMayor = $respuesta->CallConsultarDniMayorResult;

    switch ($respuesta->resultadoDniMenor->codigoMensaje) {
      case 'MR':
        $datos_rinec = array(
          "codigoMensaje" => $respuesta->resultadoDniMenor->codigoMensaje,
          "descripcionMensaje" => "No se encontraron datos en RENIEC relacionados al número del documento"
        );

        return json_encode($datos_rinec, TRUE);
        break;
      case '17':
        $datos_rinec = array(
          "codigoMensaje" => $respuesta->resultadoDniMenor->codigoMensaje,
          "descripcionMensaje" => "Surgieron problemas al conectarse al servidor RENIEC, intente de nuevo"
        );

        return json_encode($datos_rinec, TRUE);
        break;
    }

    //$gradoInstruccion = utf8_encode($respuesta->resultadoDniMayor->gradoInstruccion);
    //$foto = base64_encode($respuesta->resultadoDniMayor->foto);
if($respuesta->resultadoDniMenor->codigoMensaje=='00'){
    $foto_persona="";
    if(isset($respuesta->resultadoDniMenor->foto)){
      $foto_persona=base64_encode($respuesta->resultadoDniMenor->foto);
    }
    $datos_rinec = array(
      "dni" => $respuesta->resultadoDniMenor->dni,
      "paterno" => utf8_encode($respuesta->resultadoDniMenor->paterno),
      "materno" => utf8_encode($respuesta->resultadoDniMenor->materno),
      "nombres" => utf8_encode($respuesta->resultadoDniMenor->nombres),
      "sexo" => utf8_encode($respuesta->resultadoDniMenor->sexo),
      "fecha_nac" => utf8_encode($respuesta->resultadoDniMenor->fechaNacimiento),
      "foto" => $foto_persona,
      "codigoMensaje" => $respuesta->resultadoDniMenor->codigoMensaje,
      "descripcionMensaje" => $respuesta->resultadoDniMenor->descripcionMensaje,	  
      "id_sexo" => substr($respuesta->resultadoDniMenor->sexo,0,1),
	    "vfecha_nac" => vfecha( $respuesta->resultadoDniMenor->fechaNacimiento),
	    "direccionDomicilio" => utf8_encode($respuesta->resultadoDniMenor->direccionDomicilio),
      "departamentoDomicilio" => utf8_encode($respuesta->resultadoDniMenor->departamentoDomicilio),
      "provinciaDomicilio" => utf8_encode($respuesta->resultadoDniMenor->provinciaDomicilio),
      "distritoDomicilio" => utf8_encode($respuesta->resultadoDniMenor->distritoDomicilio),
      "vfecha_expedicion" => vfecha($respuesta->resultadoDniMenor->fechaExpedicion),
      "estado_civil" => $respuesta->resultadoDniMenor->estadoCivil,
      "vfecha_caducidad" => vfecha($respuesta->resultadoDniMenor->caducidad),
      "localidad_domicilio" => utf8_encode($respuesta->resultadoDniMenor->localidadDomicilio),
      "departamento_nacimiento" => utf8_encode($respuesta->resultadoDniMenor->departamentoNacimiento),
      "provincia_nacimiento" => utf8_encode($respuesta->resultadoDniMenor->provinciaNacimiento),
      "distrito_nacimiento" => utf8_encode($respuesta->resultadoDniMenor->distritoNacimiento)
    );

   
}else{
   $datos_rinec = array(
   "codigoMensaje" => $respuesta->resultadoDniMenor->codigoMensaje,
   "descripcionMensaje" => $respuesta->resultadoDniMenor->descripcionMensaje,  
   );  
}
   $json = json_encode($datos_rinec, TRUE);
    return $json;

  }

  public function BuscarNombre($nombre, $paterno, $materno)
  {
    $soapClient = new SoapClient(env('RENIEC_URI'), array('trace' => 1, "encoding" => "ISO-8859-1"));
    /*if ($soapClient->getError()) {
      echo "no se pudo conectar al ws";
      die();
    }*/

    //DIRSAPOL
    $parametros = array(
      "clienteUsuario" => "DIRSAPOL",
      "clienteClave" => "WUK9XPhx",
      "servicioCodigo" => "WS_RENIEC_MAY_MEN",
      "clienteSistema" => "SOAP_DESARROLLO",
      "clienteIp" => "172.31.2.249",
      "clienteMac" => "AA:BB:CC:DD:EE:FF",
      "dniAutorizado" => "46142906",
      "tipoDocUserClieFin" => "1",
      "nroDocUserClieFin" => "391402",
      "inNombre" => $nombre,
      "inPaterno" => $paterno,
      "inMaterno" => $materno
      //"inCasado" => $casado
    );

    $respuesta = $soapClient->consultarNombreMayores($parametros);
    //$respuesta = $soapClient->consultarNombreMenores($parametros);
    //dd($respuesta);
    //$respuesta = $soapClient->CallConsultarDniMayor($parametros);
    //$respuesta->resultadoDniMayor = $respuesta->CallConsultarDniMayorResult;

    switch ($respuesta->resultadoNombres->codigoMensaje) {
      case 'MR':
        $datos_rinec = array(
          "codigoMensaje" => $respuesta->resultadoNombres->codigoMensaje,
          "descripcionMensaje" => "No se encontraron datos en RENIEC relacionados al número del documento"
        );

        return json_encode($datos_rinec, TRUE);
        break;
      case '17':
        $datos_rinec = array(
          "codigoMensaje" => $respuesta->resultadoNombres->codigoMensaje,
          "descripcionMensaje" => "Surgieron problemas al conectarse al servidor RENIEC, intente de nuevo"
        );

        return json_encode($datos_rinec, TRUE);
        break;
    }

    $datos_rinec = array(
      "dni" => $respuesta->resultadoNombres->dni,
      "paterno" => utf8_encode($respuesta->resultadoNombres->apellidoPaterno),
      "materno" => utf8_encode($respuesta->resultadoNombres->apellidoMaterno),
      "nombres" => utf8_encode($respuesta->resultadoNombres->nombres),
    );
    $json = json_encode($datos_rinec, TRUE);
    return $json;
    
  }
}