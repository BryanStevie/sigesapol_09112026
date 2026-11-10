<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessReniecSignedFiles;
use Archive7z\Archive7z;
use Illuminate\Support\Facades\Log;
ini_set('memory_limit', '-1');
set_time_limit(0);

class RefirmaController extends Controller
{
  public function getFile(Request $request)
  {
    $documentName = $_REQUEST["documentName"];
    $separa = DIRECTORY_SEPARATOR;

    $tmp = dirname(tempnam(null, ''));

    $archivo = $tmp . $separa . "upload" . $separa . $file_name . $documentName;

    header('Content-Type: application/pdf');
    header('Content-Disposition:attachment;filename="' . $documentName . '"');
    readfile($archivo);
  }

  public function postArguments(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"47",
                    "posy":"50",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }
  public function postArgumentsImagenologia(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"425",
                    "posy":"50",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }
  public function postArgumentsHojaHis(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"47",
                    "posy":"50",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }
  //"dcfilter": ".*FIR.*|.*FAU.*",
  //"dcfilter":".*FAU.*' . $request->entidad . '.*",
  public function upload(Request $request)
  {
    Log::info("******** REFIRMA - 1 ********");
    $file = $request->file('MyForm');
    Log::info("******** REFIRMA - 2 ********");
    if (isset($file)) {
    Log::info("******** REFIRMA - 3 ********");
      $separa = DIRECTORY_SEPARATOR;
    Log::info("******** REFIRMA - 4 ********");
      $file_name = str_replace("%", "_", $file->getClientOriginalName());
    Log::info("******** REFIRMA - 5 ********");
      $file_tmp = $file->getRealPath();
    Log::info("******** REFIRMA - 6 ********");
      $tamanio = $file->getSize();
    Log::info("******** REFIRMA - 7 ********");
      $tipo = $file->getMimeType();
    Log::info("******** REFIRMA - 8 ********");
      $upload_dir = public_path('tmp/zip/firmados/');
    Log::info("******** REFIRMA - 9 ********");
      move_uploaded_file($file_tmp, $upload_dir . $file_name);
    Log::info("******** REFIRMA - 10 ********");
      //descomprime documentos firmados
      $obj = new Archive7z(public_path("tmp/zip/firmados/${file_name}"));
    Log::info("******** REFIRMA - 11 ********");
    Log::info(["obj->getEntries()" => $obj->getEntries()]);
      foreach ($obj->getEntries() as $entry) {
    Log::info("******** REFIRMA - 12 ********");
        $obj->renameEntry($entry->getPath(), 'cf_' . str_replace("[R]", "", $entry->getPath()));
    Log::info("******** REFIRMA - 13 ********");
      }
    Log::info("******** REFIRMA - 14 ********");
      $obj->setOutputDirectory(public_path('tmp/pdf/firmados/'))->extract();
    Log::info("******** REFIRMA - 15 ********");
      //elimina archivos 7z temporales
      //$zip_sin_firma = substr($file_name,0,18).".7z";
      //unlink(public_path("tmp/zip/firmados/${file_name}"));
      //unlink(public_path("tmp/zip/sin_firma/${zip_sin_firma}"));
    }
  }

  function uploadAsync(Request $request)
  {
    Log::info("***** uploadAsync *****");
    $file = $request->file('MyForm');

    if (!$file || !$file->isValid()) {
      return response()->json(['error' => 'Archivo no válido'], 400);
    }

    // Validación básica: solo .7z
    if ($file->getClientOriginalExtension() !== '7z') {
      return response()->json(['error' => 'Solo archivos .7z permitidos'], 400);
    }

    // Carpeta temporal rápida
    $tmpDir = storage_path('app/tmp/zip/firmados/');
    if (!is_dir($tmpDir)) mkdir($tmpDir, 0775, true);

    // Nombre único seguro
    $fileName = uniqid('reniec_') . '_' . str_replace('%', '_', $file->getClientOriginalName());
    $tmpPath = $tmpDir . $fileName;

    // Guardar archivo
    if (!move_uploaded_file($file->getRealPath(), $tmpPath)) {
      return response()->json(['error' => 'No se pudo guardar el archivo'], 500);
    }

    // Encolar Job async
    $this->dispatch(new ProcessReniecSignedFiles($fileName));

    // RESPUESTA INMEDIATA
    return response()->json([
      'status' => 'accepted',
      'file'   => $fileName
    ], 200);
  }

  public function getArguments()
  {
    echo substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 40) . ".PDF";
  }
  public function postArgumentsEmergenciaTriaje(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"47",
                    "posy":"10",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }

  public function postArgumentsEmergenciaAlta(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"400",
                    "posy":"10",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }

  public function postArgumentsLaboratorio(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"80",
                    "posy":"510",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
    //"fileDownloadUrl": url de los pdf sin firmar, cuando el invoker es llamado obtiene busca el pdf en esta url paraluego devolverlos firmados
    //"fileUploadUrl": EndPoint para obtener pdf firmados y guardarlos localmente
  }

  public function postArgumentsKardexEnfermeria(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"20",
                    "posy":"5",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
    //"fileDownloadUrl": url de los pdf sin firmar, cuando el invoker es llamado obtiene busca el pdf en esta url paraluego devolverlos firmados
    //"fileUploadUrl": EndPoint para obtener pdf firmados y guardarlos localmente
  }

  public function postArgumentsRadiologia(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"1",
                    "posx":"70",
                    "posy":"490",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
    //"fileDownloadUrl": url de los pdf sin firmar, cuando el invoker es llamado obtiene busca el pdf en esta url paraluego devolverlos firmados
    //"fileUploadUrl": EndPoint para obtener pdf firmados y guardarlos localmente
  }

  public function postArgumentsConsultaExternaEnfermeria(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"20",
                    "posy":"5",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }

  public function postArgumentsNotaEnfermeria(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"20",
                    "posy":"5",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }
  public function postArgumentsOrdenTrasladoSolicitar(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"47",
                    "posy":"50",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }

  public function postArgumentsOrdenTrasladoRecibir(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"400",
                    "posy":"100",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }

  public function postArgumentsConsejeriaHospitalizacion(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"20",
                    "posy":"5",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }
  
  public function postArgumentsConsejeriaEmergencia(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"20",
                    "posy":"5",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }
  public function postArgumentsCqxIntervencionNivel2(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    
    if ($request->type == "W") {
      $param = '{
                  "app":"pcx",
                  "mode":"lot-p",
                  "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                  "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                  "idFile":"MyForm",
                  "protocol":"' . $protocol . '",
                  "type":"W",
                  "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                  "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                  "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                  "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                  "contentFile":"' . $request->documentName . '",
                  "reason":"Soy el autor del documento",
                  "isSignatureVisible":"true",
                  "stampAppearanceId":"0",
                  "pageNumber":"0",
                  "posx":"20",
                  "posy":"5",
                  "fontSize":"7",
                  "dcfilter": ".*FIR.*|.*FAU.*",
                  "signatureLevel":"0",
                  "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }
}
