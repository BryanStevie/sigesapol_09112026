<?php

namespace App\Http\Controllers;

use App\Constants;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Asegurado;
use App\Models\Cita;
use App\Models\Consultorio;
use App\Models\Emergencia;
use App\Models\Establecimiento;
use App\Models\Evaluacione;
use App\Models\Hospitalizacione;
use App\Models\Laboratorio;
use App\Models\Radiologia;
use App\Models\Receta_vale;
use App\Models\TipoAtencione;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request as RequestLaravel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDOException;
use Illuminate\Support\Str;

class WsDirsapolSitedsController extends Controller
{
    
    /**
     *  consumer api dirsapol.api.siteds for generate codes siteds
     * 
     * @param Request $request
     * @return JsonResponse
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function generateCodeSiteds(Request $request) {
        $coTiCobertura = "4";
        $coSubTiCobertura = "100";
        $forceTempCodeGeneration = isset($request->forceTempCodeGeneration) ? $request->forceTempCodeGeneration : false;

        if (isset($request->tipoAtencionId)) {
            $tipoAtencion = TipoAtencione::find($request->tipoAtencionId);
            if (isset($tipoAtencion) && $tipoAtencion->cod_saludpol) {
                $coTiCobertura = $tipoAtencion->cod_saludpol;
            }
        }
        
        if (isset($request->servicioId)) {
            $servicio = Consultorio::find($request->servicioId);
            if (isset($servicio) && $servicio->codigo_sub_cobertura) {
                $coSubTiCobertura = $servicio->codigo_sub_cobertura;
            }
        }

        try {
            $urlEndpoint = "pasarela/generate-code-siteds";
            
            $tiDoPaciente = $this->handlerTypeDoc($request->tiDoPaciente);

            //! TODO: ELIMINAR O COMENTAR - solo para pruebas de mejora al manejar codigo 1300
            // throw new RequestException(
            //     'No existe el afiliado en la base de datos de la IAFAS',
            //     new RequestLaravel('POST', 'http://192.168.10.107:4000/api/v1/pasarela/generate-code-siteds'),  // Request
            //     new Response(400, [], json_encode([
            //         'code' => '1300',
            //         'status' => 400,
            //         'message' => 'No hemos encontrado su afiliación en la IAFAS. Le recomendamos ingresar al SISTEDS WEB para verificar su estado de afiliación.',
            //         'codeExternalAPI' => '1300',
            //     ]))
            // );

            $body = [
                "idRemitente" => $request->idRemitente, // Código de IPRESS- Renipress (El contenido será alineado a la izquierda rellenado con espacios a la derecha) 
                "nuRucRemitente" => Constants::RUC_DIRSAPOL, // Código de Identificación( RUC IPRESS) - POR DEFECTO
                "idCorrelativo" => $request->idCorrelativo, // Correlativo generado por el emisor de la transacción
                "tiDoPaciente" => $tiDoPaciente, // Número de Documento de Identidad
                "nuDoPaciente" => $request->nuDoPaciente, // Número de Documento de Identidad
                "coTiCobertura" => $coTiCobertura, // Código de Tipo de Cobertura, consume Tabla => SSTM_TIPO_COBERTURA
                "coSubTiCobertura" => $coSubTiCobertura, // Código de Subtipo de Cobertura, consume Tabla => SSTM_SUB_TIPOCOBERTURA
                "noResponsableAut" => Constants::NOM_RESP_ACRED,
                "noPaResponsableAut" => Constants::APELL_PATER_RESP_ACRED,
                "noMaResponsableAut" => Constants::APELL_MATERNO_RESP_ACRED,
                "nuDoResponsableAut" => Constants::NUM_DOC_RESP_ACRED,
                "coAdmisionista" => $request->coAdmisionista,
                "beMaxInicial" => $request->beMaxInicial, // Monto Monetario(Beneficio máximo Inicial) 
                "coTiMoneda" => $request->coTiMoneda, // Código tipo Moneda, Consume Tabla => SSTM_TIPO_MONEDA (1 => SOLES, 2 => DOLARES)
                "coPagoFijo" => $request->coPagoFijo, // Copago Fijo, Deducible 
                "coCalServicio" => $request->coCalServicio, // Código de Calificación de Servicio, consume tabla 'SSTM_CALIF_SERVICIOS'
                "coPagoVariable" => $request->coPagoVariable, // Copago Variable, Coaseguro(Porcentaje en formato Decimal) 
                "flagCaGarantia" => $request->flagCaGarantia, // Flag Carta de Garantía( 1 => Necesita, 0 => No Necesita, 2 => Ambos) 
                "deFlagCartaGarantia" => $request->deFlagCartaGarantia, // Descripción de Plan de Cobertura( Breve Descripción - referencia código en EB01) 
            ];

            if (isset($request->fechaAtencion)) {
                $body["fechaAtencion"] = $request->fechaAtencion;
                // $body["fechaAtencion"] = "2014-12-30";
            }

            Log::info("[REQUEST API_SITEDS] => ", ['body' => $body]);

            $client = new Client();
            
            $urlWs = Constants::URL_WS_DIRSAPOL_SITEDS . $urlEndpoint;

            $response = $client->request('POST', $urlWs, ['json' => $body, 'connect_timeout' => 3]);

            Log::info("[RESPONSE API_SITEDS] => ", ['response' => $response]);

            $data = $response->getBody()->getContents(); 

            return response()->json(json_decode($data), $response->getStatusCode());
            
        } catch (RequestException $e) {
            return $this->handlerRequestException($e, $coTiCobertura, $coSubTiCobertura, $request->nuDoPaciente, $forceTempCodeGeneration);
        } catch (PDOException $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         } catch (\Exception $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         } catch (\RuntimeException $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         }
    }

    /**
     *  consumer api dirsapol.api.siteds for generate codes siteds v2
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function generateCodeSitedsv2(Request $request) {
        $idRemitente = "00013591";
        $tiDoPaciente = "1";
        $nuDoPaciente = "00000000";
        $coTiCobertura = "4";
        $coSubTiCobertura = "100";
        $forceTempCodeGeneration = isset($request->forceTempCodeGeneration) ? $request->forceTempCodeGeneration : false;

        if (!isset($request->aseguradoId)) {
            return new JsonResponse(["message" => "Es necesario enviar el id del asegurado", "code" => "404"], 404);
        }

        if (!isset($request->establecimientoId)) {
            return new JsonResponse(["message" => "Es necesario enviar el id del establecimiento", "code" => "404"], 404);
        }

        $asegurado = Asegurado::where("id", "=", $request->aseguradoId)->select("id", "nro_doc_ident", "id_tipo_doc_ident")->first();
        $establecimiento = Establecimiento::where("id", "=", $request->establecimientoId)->select("id", "codigo")->first();

        $idRemitente = $establecimiento->codigo;
        $nuDoPaciente = $asegurado->nro_doc_ident;
        $tiDoPaciente = $asegurado->id_tipo_doc_ident;
        
        if (isset($request->tipoAtencionId)) {
            $tipoAtencion = TipoAtencione::find($request->tipoAtencionId);
            if (isset($tipoAtencion) && $tipoAtencion->cod_saludpol) {
                $coTiCobertura = $tipoAtencion->cod_saludpol;
            }
        }
        
        if (isset($request->servicioId)) {
            $servicio = Consultorio::find($request->servicioId);
            if (isset($servicio) && $servicio->codigo_sub_cobertura) {
                $coSubTiCobertura = $servicio->codigo_sub_cobertura;
            }
        }

        try {
            $tiDoPacienteResult = $this->handlerTypeDoc($tiDoPaciente);
            $body = [
                "idRemitente" => $idRemitente, // Código de IPRESS- Renipress (El contenido será alineado a la izquierda rellenado con espacios a la derecha) 
                "nuRucRemitente" => Constants::RUC_DIRSAPOL, // Código de Identificación( RUC IPRESS) - POR DEFECTO
                "idCorrelativo" => $request->idCorrelativo, // Correlativo generado por el emisor de la transacción
                "tiDoPaciente" => $tiDoPacienteResult, // Número de Documento de Identidad
                "nuDoPaciente" => $nuDoPaciente, // Número de Documento de Identidad
                "coTiCobertura" => $coTiCobertura, // Código de Tipo de Cobertura, consume Tabla => SSTM_TIPO_COBERTURA
                "coSubTiCobertura" => $coSubTiCobertura, // Código de Subtipo de Cobertura, consume Tabla => SSTM_SUB_TIPOCOBERTURA
                "noResponsableAut" => Constants::NOM_RESP_ACRED,
                "noPaResponsableAut" => Constants::APELL_PATER_RESP_ACRED,
                "noMaResponsableAut" => Constants::APELL_MATERNO_RESP_ACRED,
                "nuDoResponsableAut" => Constants::NUM_DOC_RESP_ACRED,
                "coAdmisionista" => $nuDoPaciente,
                "beMaxInicial" => "0", // Monto Monetario(Beneficio máximo Inicial) 
                "coTiMoneda" => "1", // Código tipo Moneda, Consume Tabla => SSTM_TIPO_MONEDA (1 => SOLES, 2 => DOLARES)
                "coPagoFijo" => "0", // Copago Fijo, Deducible 
                "coCalServicio" => "VS", // Código de Calificación de Servicio, consume tabla 'SSTM_CALIF_SERVICIOS'
                "coPagoVariable" => "100", // Copago Variable, Coaseguro(Porcentaje en formato Decimal) 
                "flagCaGarantia" => "0", // Flag Carta de Garantía( 1 => Necesita, 0 => No Necesita, 2 => Ambos) 
                "deFlagCartaGarantia" => "sigesapol 1.0", // Descripción de Plan de Cobertura( Breve Descripción - referencia código en EB01) 
            ];

            Log::info("[REQUEST API_SITEDS] => ", ['body' => $body]);

            $client = new Client();
            
            $urlWs = Constants::URL_WS_DIRSAPOL_SITEDS . "pasarela/generate-code-siteds";

            $response = $client->request('POST', $urlWs, ['json' => $body, 'connect_timeout' => 3]);

            Log::info("[RESPONSE API_SITEDS] => ", ['response' => $response]);

            $data = $response->getBody()->getContents(); 
            
            return response()->json(json_decode($data), $response->getStatusCode());
            
        } catch (RequestException $e) {
            return $this->handlerRequestException($e, $coTiCobertura, $coSubTiCobertura, $nuDoPaciente, $forceTempCodeGeneration);
        } catch (PDOException $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         } catch (\Exception $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         } catch (\RuntimeException $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         }
    }



    /**
     *  @param Request $request
     *  @return JsonResponse
     *  endpoint para regularizar codigos temporales de siteds
     *  JSON EXAMPLE
     *  {
            "recordId" : 100,                     -> requerido      -> id del registro
            "moduleId" : 1,                       -> requerido      -> id del modulo
            "dateAttention" : "2024-08-10",       -> opcional       -> fecha de atencion
            "dateUpdateSiteds" : "2024-08-10",    -> opcional       -> fecha ultima actualizacion siteds
            "establecimientoId" : 76,             -> requerido      -> id de la ipress
            "tipoAtencionId" : 3,                 -> requerido      -> id del atención
            "servicioId" : 3                      -> requerido      -> id del servicio
            "nuAutorizacion" : "000003232874"     -> opcional       -> codigo siteds 
            "update" : "000003232874"             -> opcional       -> codigo siteds 
            "flagRegularizeOutOfDate" : false     -> opcional       -> regularizar codigos siteds fuera de fecha                   -> Default: false
     *  }
     *  moduleId => 1: RECETA_VALE, 2: LABORATORIO, 3: EMERGENCIA, 4: EVALUACIONES , 5: IMAGENES, 6: HOSPITALIZACION
     */
    public function regularizeMultipleSitedsTemp(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->input("recordId");
            $moduleId = $request->input("moduleId") ? $request->input("moduleId") : 1;
            $dateAttention = $request->input("dateAttention");
            $dateUpdateSiteds = $request->input("dateUpdateSiteds");
            $nuAutorizacionRequest = $request->input("nuAutorizacion");
    
            $nuAutorizacion = null;
            $flagGenerateOutOfDate = false;
    
            if (!empty($nuAutorizacionRequest) && isset($nuAutorizacionRequest)) {
                $nuAutorizacion = $nuAutorizacionRequest;
            }
    
            if ($request->has('flagGenerateOutOfDate') && !empty($request->input('flagGenerateOutOfDate'))) {
                $flagGenerateOutOfDate = $request->input("flagGenerateOutOfDate");
            }
    
            $dateLimit = !empty($dateAttention) ? $dateAttention : (!empty($dateUpdateSiteds) ? $dateUpdateSiteds : Carbon::now()->format('Y-m-d'));
            $dateLimitFormat = Carbon::parse($dateLimit)->format('d-m-Y');
    
            if (intval($moduleId) < 1 && intval($moduleId) > 5) {
                return new JsonResponse(["message" => "Modulo no soportado para regularizacion multiple de codigos siteds temporales"], JsonResponse::HTTP_BAD_REQUEST);
            }
    
            $record = null;
            $arrayCodes = null;
    
            if (intval($moduleId) === 1) {
                $record = Receta_vale::selectRaw('id, UPPER(codigo_siteds) as codigo_siteds, id_asegurado')->where('id', $id)->first();
                if (!isset($record) || !isset($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "No existe una receta con codigo siteds temporal pendiente a regularizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
                if (!$flagGenerateOutOfDate && !$this->isCurrentDateDifferent($dateLimit)) {
                    return new JsonResponse(["message" => "Lamentamos informarle que el plazo para completar la acreditación ha expirado. La fecha límite para la acreditación era el <b>{$dateLimitFormat}</b>."], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
                if (!$this->isCodeSitedsTemporary($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "La receta no tiene un siteds temporal a regualizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
               
                $arrayCodes = Receta_vale::select('id', 'codigo_siteds')->whereRaw('UPPER(codigo_siteds) = UPPER(?)', [$record->codigo_siteds])->get();
    
                if (isset($arrayCodes) && count($arrayCodes) > 0) {
    
                    if (!isset($nuAutorizacion)) {
                        $requestSiteds = new Request([
                            'idCorrelativo' => $record->id,
                            'aseguradoId' => $record->id_asegurado,
                            'establecimientoId' => $request->input("establecimientoId"),
                            'tipoAtencionId' => $request->input("tipoAtencionId"),
                            'servicioId' => $request->input("servicioId")
                        ]);
                        $resultGenerate  = $this->generateCodeSitedsv2($requestSiteds);
                        $resultGenerateContent = json_decode((string) $resultGenerate->getContent());
                        $nuAutorizacion = $resultGenerateContent->nuAutorizacion;
                        if ($resultGenerate->getStatusCode() !== JsonResponse::HTTP_OK && $resultGenerate->getStatusCode() !== JsonResponse::HTTP_CREATED) {
                            return response()->json(["message" =>  "El servicio de SUSALUD sigue fallando, así que no podemos regularizar los códigos SITEDS ahora. Intenta más tarde"], JsonResponse::HTTP_SERVICE_UNAVAILABLE);
                        }
                    }
    
                    foreach ($arrayCodes as $key => $itemCode) {
                        $recetaValeUpdate = Receta_vale::where('id', $itemCode->id)->update([
                            'codigo_siteds' => $nuAutorizacion,
                            'updated_at_cod_siteds' => Carbon::now()
                        ]);
                    }
                }
            }
            
            if (intval($moduleId) === 2) {
                $record = Laboratorio::selectRaw('laboratorios.id, UPPER(cod_siteds_susalud) as codigo_siteds, prestaciones.id_asegurado')
                                    ->join('prestaciones', 'laboratorios.id_prestacion', '=', 'prestaciones.id')
                                    ->where('laboratorios.id', $id)
                                    ->first();
                if (!isset($record) || !isset($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "No existe una examen de laboratorio con siteds temporal pendiente a regularizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
                if (!$flagGenerateOutOfDate && !$this->isCurrentDateDifferent($dateLimit)) {
                    return new JsonResponse(["message" => "Lamentamos informarle que el plazo para completar la acreditación ha expirado. La fecha límite para la acreditación era el <b>{$dateLimitFormat}</b>."], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
                if (!$this->isCodeSitedsTemporary($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "El examen de laboratorio no tiene un siteds temporal a regularizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
    
                $arrayCodes = Laboratorio::select('id', 'cod_siteds_susalud')->whereRaw('UPPER(cod_siteds_susalud) = UPPER(?)', [$record->codigo_siteds])->get();
    
                if (isset($arrayCodes) && count($arrayCodes) > 0) {
                    
                    if (!isset($nuAutorizacion)) {
                        $requestSiteds = new Request([
                            'idCorrelativo' => $record->id,
                            'aseguradoId' => $record->id_asegurado,
                            'establecimientoId' => $request->input("establecimientoId"),
                            'tipoAtencionId' => $request->input("tipoAtencionId"),
                            'servicioId' => $request->input("servicioId")
                        ]);
                        $resultGenerate  = $this->generateCodeSitedsv2($requestSiteds);
                        $resultGenerateContent = json_decode((string) $resultGenerate->getContent());
                        $nuAutorizacion = $resultGenerateContent->nuAutorizacion;
                        if ($resultGenerate->getStatusCode() !== JsonResponse::HTTP_OK && $resultGenerate->getStatusCode() !== JsonResponse::HTTP_CREATED) {
                            return response()->json(["message" =>  "El servicio de SUSALUD sigue fallando, así que no podemos regularizar los códigos SITEDS ahora. Intenta más tarde"], JsonResponse::HTTP_SERVICE_UNAVAILABLE);
                        }
                    }
    
                    foreach ($arrayCodes as $key => $itemCode) {
                        $laboratorioUpdate = Laboratorio::where('id', $itemCode->id)->update([
                            'cod_siteds_susalud' => $nuAutorizacion,
                            'updated_at_cod_siteds' => Carbon::now(),
                            'updated_us_cod_siteds' => Auth::user()->id,
                        ]);
                    }
                }
            }
    
            if (intval($moduleId) === 3) {
                $record = Emergencia::selectRaw('id, UPPER(cod_siteds_susalud) AS codigo_siteds, updated_at_cod_siteds, updated_us_cod_siteds, id_asegurado')->where('id', $id)->first();
                if (!isset($record) || !isset($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "No existe una emergencia con siteds temporal pendiente a regularizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
                if (!$flagGenerateOutOfDate && !$this->isCurrentDateDifferent($dateLimit)) {
                    return new JsonResponse(["message" => "Lamentamos informarle que el plazo para completar la acreditación ha expirado. La fecha límite para la acreditación era el <b>{$dateLimitFormat}</b>."], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
                if (!$this->isCodeSitedsTemporary($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "El examen de laboratorio no tiene un siteds temporal a regualizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
    
                if (!isset($nuAutorizacion)) {
                    $requestSiteds = new Request([
                        'idCorrelativo' => $record->id,
                        'aseguradoId' => $record->id_asegurado,
                        'establecimientoId' => $request->input("establecimientoId"),
                        'tipoAtencionId' => $request->input("tipoAtencionId"),
                        'servicioId' => $request->input("servicioId")
                    ]);
                    $resultGenerate  = $this->generateCodeSitedsv2($requestSiteds);
                    $resultGenerateContent = json_decode((string) $resultGenerate->getContent());
                    $nuAutorizacion = $resultGenerateContent->nuAutorizacion;
                    if ($resultGenerate->getStatusCode() !== JsonResponse::HTTP_OK && $resultGenerate->getStatusCode() !== JsonResponse::HTTP_CREATED) {
                        return response()->json(["message" =>  "El servicio de SUSALUD sigue fallando, así que no podemos regularizar los códigos SITEDS ahora. Intenta más tarde"], JsonResponse::HTTP_SERVICE_UNAVAILABLE);
                    }
                }
    
                $emergenciaUpdated = Emergencia::where('id', $record->id)->update([
                    'cod_siteds_susalud' => $nuAutorizacion,
                    'updated_at_cod_siteds' => Carbon::now(),
                    'updated_us_cod_siteds' => Auth::user()->id,
                ]);
    
                $arrayCodesEvaluacion = Evaluacione::select('id', 'cod_siteds_susalud')->where('id_emergencia', $id)->whereRaw('UPPER(cod_siteds_susalud) = UPPER(?)', [$record->codigo_siteds])->get();
                if (isset($arrayCodesEvaluacion) && count($arrayCodesEvaluacion) > 0) {
                    foreach ($arrayCodesEvaluacion as $key => $itemCode) {
                        $evaluacionUpdated = Evaluacione::where('id', $itemCode->id)->update([
                            'cod_siteds_susalud' => $nuAutorizacion,
                            'updated_at_cod_siteds' => Carbon::now(),
                            'updated_us_cod_siteds' => Auth::user()->id,
                        ]);
                    }
                }
            }
    
            if (intval($moduleId) === 4) {
                $record = Evaluacione::selectRaw('id, UPPER(cod_siteds_susalud) AS codigo_siteds, updated_at_cod_siteds, id_asegurado')->where('id', $id)->first();
                if (!isset($record) || !isset($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "No existe una evolucion con siteds temporal pendiente a regularizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
                if (!$flagGenerateOutOfDate && !$this->isCurrentDateDifferent($dateLimit)) {
                    return new JsonResponse(["message" => "Lamentamos informarle que el plazo para completar la acreditación ha expirado. La fecha límite para la acreditación era el <b>{$dateLimitFormat}</b>."], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
                if (!$this->isCodeSitedsTemporary($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "La Evolución no tiene un siteds temporal a regualizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
    
                if (!isset($nuAutorizacion)) {
                    $requestSiteds = new Request([
                        'idCorrelativo' => $record->id,
                        'aseguradoId' => $record->id_asegurado,
                        'establecimientoId' => $request->input("establecimientoId"),
                        'tipoAtencionId' => $request->input("tipoAtencionId"),
                        'servicioId' => $request->input("servicioId")
                    ]);
                    $resultGenerate  = $this->generateCodeSitedsv2($requestSiteds);
                    $resultGenerateContent = json_decode((string) $resultGenerate->getContent());
                    $nuAutorizacion = $resultGenerateContent->nuAutorizacion;
    
                    if ($resultGenerate->getStatusCode() !== JsonResponse::HTTP_OK && $resultGenerate->getStatusCode() !== JsonResponse::HTTP_CREATED) {
                        return response()->json(["message" =>  "El servicio de SUSALUD sigue fallando, así que no podemos regularizar los códigos SITEDS ahora. Intenta más tarde"], JsonResponse::HTTP_SERVICE_UNAVAILABLE);
                    }
                }
    
                $arrayCodesEvaluacion = Evaluacione::select('id', 'cod_siteds_susalud')->whereRaw('UPPER(cod_siteds_susalud) = UPPER(?)', [$record->codigo_siteds])->get();
                if (isset($arrayCodesEvaluacion) && count($arrayCodesEvaluacion) > 0) {
                    foreach ($arrayCodesEvaluacion as $key => $itemArrayEva) {
                        $evaluacionUpdated = Evaluacione::where('id', $itemArrayEva->id)->update([
                            'cod_siteds_susalud' => $nuAutorizacion,
                            'updated_at_cod_siteds' => Carbon::now(),
                            'updated_us_cod_siteds' => Auth::user()->id,
                        ]);
                    }
                }
    
                $arrayEmergencies = Emergencia::select('id', 'cod_siteds_susalud')->whereRaw('UPPER(cod_siteds_susalud) = UPPER(?)', [$record->codigo_siteds])->get();
                if (isset($arrayEmergencies) && count($arrayEmergencies)) {
                    foreach ($arrayEmergencies as $key => $itemEmergency) {
                        $emergencyUpdate = Emergencia::where('id', $itemEmergency->id)->update([
                            'cod_siteds_susalud' => $nuAutorizacion,
                            'updated_at_cod_siteds' => Carbon::now(),
                            'updated_us_cod_siteds' => Auth::user()->id,
                        ]);
                    }
                }
            }

            if (intval($moduleId) === 5) {
                $record = Radiologia::join('prestaciones as p', 'radiologias.id_prestacion', '=', 'p.id')->where('radiologias.id', $id)->select(['radiologias.id', 'radiologias.cod_siteds_susalud as codigo_siteds', 'p.id_asegurado'])->first();

                if (!isset($record) || !isset($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "No existe examen de imagen con codigo siteds temporal pendiente a regularizar"], JsonResponse::HTTP_BAD_REQUEST);
                }
                if (!$this->isCodeSitedsTemporary($record->codigo_siteds)) {
                    return new JsonResponse(["message" => "El examen de imagen no tiene un siteds temporal a regualizar"], JsonResponse::HTTP_BAD_REQUEST);
                }

                if (!isset($nuAutorizacion)) {
                    $requestSiteds = new Request([
                        'idCorrelativo' => $record->id,
                        'aseguradoId' => $record->id_asegurado,
                        'establecimientoId' => $request->input("establecimientoId"),
                        'tipoAtencionId' => $request->input("tipoAtencionId"),
                        'servicioId' => $request->input("servicioId")
                    ]);
                    $resultGenerate  = $this->generateCodeSitedsv2($requestSiteds);
                    $resultGenerateContent = json_decode((string) $resultGenerate->getContent());
                    $nuAutorizacion = $resultGenerateContent->nuAutorizacion;
                    if ($resultGenerate->getStatusCode() !== JsonResponse::HTTP_OK && $resultGenerate->getStatusCode() !== JsonResponse::HTTP_CREATED) {
                        return response()->json(["message" =>  "El servicio de SUSALUD sigue fallando, así que no podemos regularizar los códigos SITEDS ahora. Intenta más tarde"], JsonResponse::HTTP_SERVICE_UNAVAILABLE);
                    }
                }

                $radiologiaUpdate = Radiologia::where('id', $record->id)->update([
                    'cod_siteds_susalud' => $nuAutorizacion,
                    'updated_at_cod_siteds' => Carbon::now(),
                    'updated_us_cod_siteds' => Auth::user()->id,
                ]);
            }
    
            if (intval($moduleId) === 6) {
                $record = Hospitalizacione::select('id', 'id_asegurado', 'cod_siteds_susalud', 'fecha_ingreso')->where('id', $id)->first();
                if (!isset($record) || !isset($record->cod_siteds_susalud)) {
                    return new JsonResponse(["message" => "No existe la admision con codigo siteds temporal pendiente a regularizar", "code" => "404"], JsonResponse::HTTP_NOT_FOUND);
                }
                if (!$this->isCodeSitedsTemporary($record->cod_siteds_susalud)) {
                    return new JsonResponse(["message" => "La admsión no tiene un siteds temporal a regualizar", "code" => "405"], JsonResponse::HTTP_BAD_REQUEST);
                }
                if (!$flagGenerateOutOfDate && !$this->isCurrentDateDifferent($dateLimit)) {
                    return new JsonResponse(["message" => "Lamentamos informarle que el plazo para completar la acreditación ha expirado. La fecha límite para la acreditación era el <b>{$dateLimitFormat}</b>.", "code" => "406"], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
                if (!isset($nuAutorizacion)) {
                    $requestSiteds = new Request([
                        'idCorrelativo' => $record->id,
                        'aseguradoId' => $record->id_asegurado,
                        'establecimientoId' => $request->input("establecimientoId"),
                        'tipoAtencionId' => $request->input("tipoAtencionId"),
                        'servicioId' => $request->input("servicioId")
                    ]);
                    $resultGenerate  = $this->generateCodeSitedsv2($requestSiteds);
                    if ($resultGenerate->getStatusCode() !== JsonResponse::HTTP_OK && $resultGenerate->getStatusCode() !== JsonResponse::HTTP_CREATED) {
                        return response()->json([
                            "message" =>  "El servicio de SUSALUD sigue fallando, así que no podemos regularizar los códigos SITEDS ahora. Intenta más tarde",
                            "code" => "501"
                        ], JsonResponse::HTTP_SERVICE_UNAVAILABLE);
                    }
                    $resultGenerateContent = json_decode((string) $resultGenerate->getContent());
                    $nuAutorizacion = $resultGenerateContent->nuAutorizacion;
                }
                $hospitalizacionUpdate = Hospitalizacione::where('id', $record->id)->update([
                    'cod_siteds_susalud' => $nuAutorizacion,
                    'updated_at_cod_siteds' => Carbon::now(),
                    'updated_us_cod_siteds' => Auth::user()->id,
                ]);
            }

            DB::commit();

            return response()->json(["message" =>  "¡La regularización de los códigos SITEDS temporales se completó con éxito!", "code" => "000"], JsonResponse::HTTP_OK);
        }  catch (RequestException $e) {
            DB::rollback();
            return new JsonResponse([
                'code' => "E0001",
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (PDOException $e) {
            DB::rollback();
            return new JsonResponse([
                'code' => "E0002",
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         } catch (\Exception $e) {
            DB::rollback();
            return new JsonResponse([
                'code' => "E0003",
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         } catch (\RuntimeException $e) {
            DB::rollback();
            return new JsonResponse([
                'code' => "E0004",
                'message' => $e->getMessage(),
                'description' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
         }
    }
    /**
     *  consumer api dirsapol.api.siteds for generate codes siteds massive on Consulta Exterana Module
     * 
     * @return JsonResponse
     */
    public function generateCodeSitedsMassiveCitas() {
        set_time_limit(0);
        $modelo_cita = new Cita();
        $result = $modelo_cita->obtenerCodigosSitedsTemporales();
        $quantityError = 0;
        $quantitySuccess = 0;
        $arrayResponseError = [];

        if (isset($result) && count($result) > 0) {
            $bodyRequest = new Request();
            foreach ($result as $index => $row) {
                $body = [
                    "idCorrelativo" => $row->cita_id,
                    "aseguradoId" => $row->id_asegurado,
                    "establecimientoId" => $row->id_establecimiento,
                    "tipoAtencionId" => isset($row->id_tipo_atencion) ? $row->id_tipo_atencion : 19,
                    "servicioId" => $row->id_consultorio
                ];
                $bodyRequest->merge($body);
                $resultGenerate = $this->generateCodeSitedsv2($bodyRequest);
                
                $resultData = json_decode((string) $resultGenerate->getContent());
                $resultData->cita_id = $row->cita_id;
                
                if ($resultGenerate->getStatusCode() === JsonResponse::HTTP_OK || $resultGenerate->getStatusCode() === JsonResponse::HTTP_CREATED) {
                    $citaUpdated = Cita::find($row->cita_id);
                    $citaUpdated->codigo_siteds = $resultData->nuAutorizacion;
                    $citaUpdated->updated_at_cod_siteds = Carbon::now();
                    $citaUpdated->updated_us_cod_siteds = Auth::user()->id;
                    $citaUpdated->save();
                    $quantitySuccess++;
                } else {
                    if($resultGenerate->getStatusCode() === JsonResponse::HTTP_ACCEPTED){
                        if (isset($resultData->sitedsWebActive) && intval($resultData->sitedsWebActive) === 1) {
                            $resultData->message = "Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para generar el SITEDS manualmente. <a href=". Constants::URL_SITEDS_WEB . " target='_blank'>SITEDS WEB</a>";
                        } else {
                            $resultData->message = "Servicio SITEDS no disponible, Intentarlo nuevamente más rato, por favor.";
                        }
                    }
                    array_push($arrayResponseError, $resultData);
                    $quantityError++;
                }

                if ($index < count($result) - 1) {
                    sleep(20);
                }
            }
        }

        $message = "La generación de códigos siteds de forma masiva. Se ejecutó exitosamente.";
        if (isset($result) && count($result) > 0) {
            if (intval($quantitySuccess) === 0) {
                $message = "Todos los códigos siteds temporales a regularizar fueron observadores.";
            } else {
                if (intval($quantitySuccess) < count($result)) {
                    $message = "Algunos de los códigos siteds temporales a regularizar fueron observadores.";
                }
            }
            
        } else {
            $message = "No hay códigos siteds a regularizar.";
        }

        return response()->json([
            "code" => JsonResponse::HTTP_OK,
            "cantidad_total" => count($result),
            "cantidad_generados_exito" => $quantitySuccess,
            "cantidad_generados_error" => $quantityError,
            "respuesta_error" => $arrayResponseError,
            "message" =>  $message
        ], JsonResponse::HTTP_OK);
    }

     /**
     *  consumer api dirsapol.api.siteds for generate codes siteds massive on Farmavia Module
     * 
     * @return JsonResponse
     */
    public function generateCodeSitedsMassiveRecetas() {
        set_time_limit(0);
        $modelo_receta_vale = new Receta_vale();
        $result = $modelo_receta_vale->obtenerRecetasSitedsTemporal();
        $quantityError = 0;
        $quantitySuccess = 0;
        $arrayResponseError = [];

        if (isset($result) && count($result) > 0) {
            $bodyRequest = new Request();
            foreach ($result as $index => $row) {
                $body = [
                    "idCorrelativo" => $row->id,
                    "aseguradoId" => $row->id_asegurado,
                    "establecimientoId" => $row->id_establecimiento,
                    "tipoAtencionId" => "19",
                    "servicioId" => $row->id_consultorio
                ];
                $bodyRequest->merge($body);
                $resultGenerate = $this->generateCodeSitedsv2($bodyRequest);
                
                $resultData = json_decode((string) $resultGenerate->getContent());
                $resultData->receta_vale_id = $row->id;
                
                if ($resultGenerate->getStatusCode() === JsonResponse::HTTP_OK || $resultGenerate->getStatusCode() === JsonResponse::HTTP_CREATED) {
                    $recetaValeUpdated = Receta_vale::find($row->id);
                    $recetaValeUpdated->codigo_siteds = $resultData->nuAutorizacion;
                    $recetaValeUpdated->updated_at_cod_siteds = Carbon::now();
                    $recetaValeUpdated->save();
                    $quantitySuccess++;
                } else {
                    if($resultGenerate->getStatusCode() === JsonResponse::HTTP_ACCEPTED){
                        if (isset($resultData->sitedsWebActive) && intval($resultData->sitedsWebActive) === 1) {
                            $resultData->message = "Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para generar el SITEDS manualmente. <a href=". Constants::URL_SITEDS_WEB . " target='_blank'>SITEDS WEB</a>";
                        } else {
                            $resultData->message = "Servicio SITEDS no disponible, Intentarlo nuevamente más rato, por favor.";
                        }
                    }
                    array_push($arrayResponseError, $resultData);
                    $quantityError++;
                }

                if ($index < count($result) - 1) {
                    sleep(20);
                }
            }
        }

        $message = "La generación de códigos siteds de forma masiva. Se ejecutó exitosamente.";
        if (isset($result) && count($result) > 0) {
            if (intval($quantitySuccess) === 0) {
                $message = "Todos los códigos siteds temporales a regularizar fueron observadores.";
            } else {
                if (intval($quantitySuccess) < count($result)) {
                    $message = "Algunos de los códigos siteds temporales a regularizar fueron observadores.";
                }
            }
            
        } else {
            $message = "No hay códigos siteds a regularizar.";
        }

        return response()->json([
            "code" => JsonResponse::HTTP_OK,
            "cantidad_total" => count($result),
            "cantidad_generados_exito" => $quantitySuccess,
            "cantidad_generados_error" => $quantityError,
            "respuesta_error" => $arrayResponseError,
            "message" =>  $message
        ], JsonResponse::HTTP_OK);
    }

     /**
     *  consumer api dirsapol.api.siteds for get data of catalog
     * 
     * @param int $numVersion
     * @return JsonResponse
     */
    function getUpdatedCatalogByVersion($numVersion) {
        try {
            $client = new Client();

            $urlWs = Constants::URL_WS_DIRSAPOL_SITEDS . "catalogo/obtener-catalogo-db/" . $numVersion;

            $response = $client->get($urlWs);

            if (!$response || $response === null) {
                return response()->json([
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => "Servicio no disponible. Por favor, inténtelo de nuevo más tarde. Por favor"
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            $data = $response->getBody()->getContents();

            return response()->json(json_decode($data), $response->getStatusCode());

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $exception = (string) $e->getResponse()->getBody();
                $exception = json_decode($exception);
                $message = $exception->message;
                $response = [
                    "message" => $message ? $message : "Servicio no disponible. Por favor, inténtelo de nuevo más tarde. Por favor",
                    "code" => $exception->codeMQ ? $exception->codeMQ : $e->getCode(),
                    "codeMQ" => $exception->codeMQ ? $exception->codeMQ : $e->getCode(),
                ];
                return new JsonResponse($response, $e->getCode());
            } else {
                return new JsonResponse([
                    'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "Servicio no disponible. Por favor, inténtelo de nuevo más tarde. Por favor",
                    "codeMQ" => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
    
    /**
     *  updated master table of the data api dirsapol.api.siteds where SUSALUD
     * 
     * @param Request $request
     * @return JsonResponse
     */
    function updatedCatalog(Request $request) {
        try {
            $jsonData = $request->getContent();
            
            $versions = [];
            $numTables = [];
            $nomTables = [];
            

            $data = json_decode($jsonData, true);

            Log::info("[data] =>" , ["data" => $data]);

            if (isset($data['versions']) && is_array($data['versions'])) {
                foreach ($data['versions'] as $version) {
                    if (isset($version['numero'])) {
                        array_push($versions, $version['numero']);
                        $registros = $version["datos"]['registos'];
                        if (isset($registros) && is_array($registros)) {
                            foreach ($registros as $registro) {
                                if (isset($registro["tabla"])) {
                                    $nomTable = $this->handlerUpdateTableCatalogByNumTable($registro["tabla"], $registro["datos"]);
                                    array_push($nomTables, $nomTable);
                                    array_push($numTables, $registro["tabla"]);
                                } 
                            }
                        }
                    }
                }
            }

            return response()->json([
                "response" => [
                    "versions" => $versions,
                    "numTables" => $numTables,
                    "nomTables" => $nomTables,
                ],
                "message" => "Se actualizo exitosamente la información"
            ]);
        } catch (Exception $e) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => "Ocurrio un error actualizando la información",
                "codeMQ" => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //TODO: se debe manejar desde la base de datos
     /**
     *  get code documentType 
     * 
     * @param int $tipo_documento_identidad
     * @return int
     */
    function handlerTypeDoc($tipo_documento_identidad) {
        if (!isset($tipo_documento_identidad)) {
            return 1;
        }
        $tipoDocumento = intval($tipo_documento_identidad);
        $codTipoDocumento = 1;
        switch ($tipoDocumento) {
            case 1: // DNI
                $codTipoDocumento = 1; // Documento Nacional de Identidad
                break;
            case 2: // Carnet Extranjeria
                $codTipoDocumento = 2; // Carné de extranjería
                break;
            case 3: // Pasaporte
                $codTipoDocumento = 3; // Pasaporte
                break;
            case 4: // Documento de Identidad extranjero
                $codTipoDocumento = 4; // Documento de Identidad Extranjero
                break;
            case 99: // SDI
                $codTipoDocumento = 7; // Sin Documento de Identidad
                break;
            default:
                $codTipoDocumento = 1;
                break;
        }
        return strval($codTipoDocumento);
    }

    /**
     *  Updated data of masters table where data SUSALUD 
     * 
     * @param string $numTable
     * @param array $dataUpdated
     * @return string
     */
    function handlerUpdateTableCatalogByNumTable($numTable, $dataUpdated) {
        switch (intval($numTable)) {
            case 46:
                return "SSTM_TIPO_COBERTURA";
                break;
            case 71:
                /* if ($dataUpdated) {
                    $CO_ID2 = $dataUpdated[2];
                    $valueUpdated = $dataUpdated[6]["valor"];
                    if (isset($CO_ID2["valor"])) {
                        Consultorio::where('codigo_sub_cobertura', $CO_ID2["valor"])
                            ->update([
                                'nombre' => $valueUpdated,
                            ]);
                    }
                } */
                return "SSTM_SUB_TIPOCOBERTURA";
                break;
            default:
                return "No se encontró la tabla a actualizar con el número " . $numTable ;
                break;
        }
    }

    /**
     * handler exception of generate code siteds v1 and v2
     * 
     * @param RequestException $exception
     * @param string $coTiCobertura
     * @param string $coSubTiCobertura
     * @param string $nro_doc_ident
     * @return JsonResponse
     */
    function handlerRequestException($exception, $coTiCobertura, $coSubTiCobertura, $nro_doc_ident, $forceTempCodeGeneration = false) {
        $message = "Servicio SITEDS no disponible. Por favor, inténtelo de nuevo más tarde";
        $code = null;
        $nuAutorizacionTemp = $this->generateSitedsTemp($coTiCobertura, $coSubTiCobertura);
        $description = null;
        $status = null;
        $sitedsWebActive = 0;

        if ($exception->hasResponse()) {
            $response = json_decode((string) $exception->getResponse()->getBody());
            Log::info(["response" => $response]);
    
            $code = isset($response->code) ? $response->code : $response->status;
            $message = isset($response->message) ? $response->message : null;
            $description = isset($response->stackTrace) ? $response->stackTrace : null;
        }

        if ($exception->getMessage() !== null && $exception->getMessage() !== '') {
            $description = !isset($description) ? $exception->getMessage() : $description;
        }
        if ($exception->getCode() !== null) {
            $code = !isset($code) ? $exception->getCode() : $code;
            $status = intval($exception->getCode()) > 0 ? $exception->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;;
            Log::info(["code" => $code]);
            
            if (
                intval($exception->getCode()) === 0 ||
                intval($exception->getCode()) === JsonResponse::HTTP_INTERNAL_SERVER_ERROR ||
                intval($exception->getCode()) === JsonResponse::HTTP_NOT_IMPLEMENTED ||
                intval($exception->getCode()) === JsonResponse::HTTP_BAD_GATEWAY ||
                intval($exception->getCode()) === JsonResponse::HTTP_SERVICE_UNAVAILABLE ||
                intval($exception->getCode()) === JsonResponse::HTTP_GATEWAY_TIMEOUT ||
                intval($exception->getCode()) === JsonResponse::HTTP_VERSION_NOT_SUPPORTED ||
                intval($exception->getCode()) === JsonResponse::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL ||
                intval($exception->getCode()) === JsonResponse::HTTP_INSUFFICIENT_STORAGE ||
                intval($exception->getCode()) === JsonResponse::HTTP_LOOP_DETECTED ||
                intval($exception->getCode()) === JsonResponse::HTTP_NOT_EXTENDED ||
                intval($exception->getCode()) === JsonResponse::HTTP_NETWORK_AUTHENTICATION_REQUIRED ||
                intval($exception->getCode()) === JsonResponse::HTTP_REQUEST_TIMEOUT ||
                (isset($code) && (strval($code) === "9999" || strval($code) === "9898" || strval($code) === "9000" || strval($code) === "0020" || strval($code) === "1005")) 
                || (strval($code) === "1300" && $forceTempCodeGeneration)
            ) {
                $sitedsWebActive = $this->checkSitedsWeb();
                if (intval($sitedsWebActive) == 1) {
                    $message = "Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para generar el SITEDS manualmente. <a href=". Constants::URL_SITEDS_WEB . " target='_blank'>SITEDS WEB</a>";
                } else {
                    $message = "!Aviso¡ Se ha generado un código provisional. Por favor, tenga en cuenta que deberá regularizarlo una vez que el servicio esté disponible nuevamente.";
                }
                $status = JsonResponse::HTTP_ACCEPTED;
            } 
        }

        return new JsonResponse([
            'message' => $message,
            'code' => $code,
            'nroDocIdent' => $nro_doc_ident,
            "nuAutorizacion" => $nuAutorizacionTemp,
            'description' => $description,
            'sitedsWebActive' => $sitedsWebActive,
        ], $status);
    }
    
    /**
     * generate code siteds temporary
     * 
     * @param string $coTiCobertura
     * @param string $coSubTiCobertura
     * @return string
     */
    function generateSitedsTemp($coTiCobertura, $coSubTiCobertura) {
        $currentDateTime = Carbon::now();
        $formattedTime = $currentDateTime->format('His');
        $numberRandom = Str::random(1);
        $sitedsTemp = $formattedTime . $numberRandom . $coTiCobertura . $coSubTiCobertura . "T";
        return str_pad($sitedsTemp, 12, '0', STR_PAD_LEFT);
    }

    /**
     * check web siteds http://app8.susalud.gob.pe:8080/wb-siteds/login.htm 
     * 
     * @return bool
     */
    function checkSitedsWeb() {
        try {
            $client = new Client();
            $response = $client->request('GET', Constants::URL_SITEDS_WEB, [
                'timeout' => 10,
                'http_errors' => false,
                'verify' => false,
            ]);
            $statusCode = $response->getStatusCode();
            if (intval($statusCode) !== 200) {
                return false;
            }
            return true;
        } catch (RequestException $e) {
            return false;
        }
    }

    function isCodeSitedsTemporary($codeSiteds)
    {
        if (!isset($codeSiteds)) {
            return true;
        }
        if (substr($codeSiteds, -1) === 'T' || strlen($codeSiteds) <= 6) {
            return true;
        }
        return false;
    }

    function isCurrentDateDifferent($date) {
        $dateCarbon = Carbon::parse($date)->startOfDay();
        $currentDate = Carbon::today();
        return $dateCarbon->eq($currentDate);
    }
}