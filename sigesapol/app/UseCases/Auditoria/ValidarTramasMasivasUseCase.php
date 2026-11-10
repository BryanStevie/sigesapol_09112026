<?php

namespace App\UseCases\Auditoria;

use App\Models\Medico;
use App\Models\Prestaciones;
use App\Models\Procedimiento;
use App\Models\trama_diagnosticos;
use App\Models\trama_prestacion;
use App\Models\trama_procedimientos;
use App\Models\trama_productos;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SoapClient;
use SoapFault;

class ValidarTramasMasivasUseCase
{
  /**
   * Ejecuta el registro de la intervención quirúrgica y su historial.
   *
   * @param Request $request
   * @return array
   * @throws Exception
   */
  public function execute(Request $request)
  {
    DB::beginTransaction();

    try {
      $trama = null;
      $resultValidate = [];
      $resultTramaValidate = [];
      $status_validate = 0;

      foreach ($request->ids as $key => $tramaId) {
        $resultTramaValidate = [];
        $trama = trama_prestacion::where('id', $tramaId)->first();
        if (!empty($trama)) {
          $validateRegla1 = $this->regla1($trama);
          if ($validateRegla1 && !$validateRegla1['isOk']) {
            $resultTramaValidate[] = $validateRegla1;
          }

          $validateRegla2 = $this->regla2($trama);
          if ($validateRegla2 && !$validateRegla2['isOk']) {
            $resultTramaValidate[] = $validateRegla2;
          }

          $validateRegla3 = $this->regla3($trama);
          if ($validateRegla3 && !$validateRegla3['isOk']) {
            $resultTramaValidate[] = $validateRegla3;
          }

          $validateRegla5 = $this->regla5($trama);
          if ($validateRegla5 && !$validateRegla5['isOk']) {
            $resultTramaValidate[] = $validateRegla5;
          }

          $validateRegla6 = $this->regla6($trama);
          if ($validateRegla6 && !$validateRegla6['isOk']) {
            $resultTramaValidate[] = $validateRegla6;
          }

          $validateRegla7 = $this->regla7($trama);
          if ($validateRegla7 && !$validateRegla7['isOk']) {
            $resultTramaValidate[] = $validateRegla7;
          }

          $validateRegla8 = $this->regla8($trama);
          if ($validateRegla8 && !$validateRegla8['isOk']) {
            $resultTramaValidate[] = $validateRegla8;
          }

          $validateRegla9 = $this->regla9($trama);
          if ($validateRegla9 && !$validateRegla9['isOk']) {
            $resultTramaValidate[] = $validateRegla9;
          }

          $validateRegla10 = $this->regla10($trama);
          if ($validateRegla10 && !$validateRegla10['isOk']) {
            $resultTramaValidate[] = $validateRegla10;
          }

          $validateRegla11 = $this->regla11($trama);
          if ($validateRegla11 && !$validateRegla11['isOk']) {
            $resultTramaValidate[] = $validateRegla11;
          }

          $validateRegla12 = $this->regla12($trama);
          if ($validateRegla12 && !$validateRegla12['isOk']) {
            $resultTramaValidate[] = $validateRegla12;
          }

          $validateRegla13 = $this->regla13($trama);
          if ($validateRegla13 && !$validateRegla13['isOk']) {
            $resultTramaValidate[] = $validateRegla13;
          }

          $validateRegla14 = $this->regla14($trama);
          if ($validateRegla14 && !$validateRegla14['isOk']) {
            $resultTramaValidate[] = $validateRegla14;
          }

          $validateRegla15 = $this->regla15($trama);
          if ($validateRegla15 && !$validateRegla15['isOk']) {
            $resultTramaValidate[] = $validateRegla15;
          }

          $validateRegla16 = $this->regla16($trama);
          if ($validateRegla16 && !$validateRegla16['isOk']) {
            $resultTramaValidate[] = $validateRegla16;
          }

          $validateRegla17 = $this->regla17($trama);
          if ($validateRegla17 && !$validateRegla17['isOk']) {
            $resultTramaValidate[] = $validateRegla17;
          }

          $validateRegla18 = $this->regla18($trama);
          if ($validateRegla18 && !$validateRegla18['isOk']) {
            $resultTramaValidate[] = $validateRegla18;
          }

          $validateRegla19 = $this->regla19($trama);
          if ($validateRegla19 && !$validateRegla19['isOk']) {
            $resultTramaValidate[] = $validateRegla19;
          }

          $validateRegla20 = $this->regla20($trama);
          if ($validateRegla20 && !$validateRegla20['isOk']) {
            $resultTramaValidate[] = $validateRegla20;
          }

          $validateRegla21 = $this->regla21($trama);
          if ($validateRegla21 && !$validateRegla21['isOk']) {
            $resultTramaValidate[] = $validateRegla21;
          }

          $validateRegla22 = $this->regla22($trama);
          if ($validateRegla22 && !$validateRegla22['isOk']) {
            $resultTramaValidate[] = $validateRegla22;
          }

          $validateRegla23 = $this->regla23($trama);
          if ($validateRegla23 && !$validateRegla23['isOk']) {
            $resultTramaValidate[] = $validateRegla23;
          }

          $validateRegla24 = $this->regla24($trama);
          if ($validateRegla24 && !$validateRegla24['isOk']) {
            $resultTramaValidate[] = $validateRegla24;
          }

          $validateRegla25 = $this->regla25($trama);
          if ($validateRegla25 && !$validateRegla25['isOk']) {
            $resultTramaValidate[] = $validateRegla25;
          }

          $validateRegla26 = $this->regla26($trama);
          if ($validateRegla26 && !$validateRegla26['isOk']) {
            $resultTramaValidate[] = $validateRegla26;
          }

          if (count($resultTramaValidate) === 0) {
            $status_validate = 1;
            if (intval($trama->estado_validacion) === 2) {
              $status_validate = 3;
            }
            $resultValidate[] = ['trama_id' => $trama->id, "isValid" => true, 'detail' => $resultTramaValidate];
          } else {
            $status_validate = 2; // No válido
            $resultValidate[] = ['trama_id' => $trama->id, "isValid" => false, 'detail' => $resultTramaValidate];
          }

          // Actualizar estado de validación en la trama_prestacion
          $trama->estado_validacion = $status_validate;
          $trama->save();
        }
      }
      DB::commit();
      return [
        'data' => $resultValidate,
        'success' => true,
        'message' => 'Intervención quirúrgica suspendida correctamente.'
      ];
    } catch (Exception $e) {
      DB::rollBack();
      Log::error('[SuspenderCqxIntervencionNivel2UseCase]', ['mensaje' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
      $mensaje = !empty($e->getMessage()) ? $e->getMessage() : 'Error al suspender la intervención quirúrgica nivel 2.';
      throw new Exception($mensaje);
    }
  }

  function regla1($trama)
  {
    $result = array('isOk' => true, 'regla' => 1, 'message' => '', 'data' => null);

    $diagnosticos = DB::table('trama_diagnosticos')
      ->where('id_trama_prestacion', $trama->id)
      ->get();

    $procedimientos = DB::table('trama_procedimientos')
      ->where('id_trama_prestacion', $trama->id)
      ->get();

    $productos = DB::table('trama_productos')
      ->where('id_trama_prestacion', $trama->id)
      ->get();

    $rules = array(
      'tipo_doc_identidad' => array(
        'required' => true,
        'maxLength' => 1,
      ),

      'paciente_nro_doc_identidad' => array(
        'required' => true,
        'maxLength' => 15,
      ),

      'asegurado_fecha_nacimiento' => array(
        'dateYYYYMMDD' => true,
        'notFutureCurrentDate' => true,
        'maxLength' => 10,
      ),

      'fecha_atencion_fups' => array(
        'dateYYYYMMDD' => true,
        'notFutureCurrentDate' => true,
        'maxLength' => 10,
      ),

      'fech_alta_atenc' => array(
        'required' => $trama['id_tipo_cobert'] == 4 ? false : true,
        'null' => $trama['id_tipo_cobert'] == 4 ? true : false,
        'dateYYYYMMDD' => true,
        'notFutureCurrentDate' => $trama['id_tipo_cobert'] == 4 ? false : true,
        'maxLength' => 10,
        'notPastDateAtencion' => $trama['id_tipo_cobert'] == 4 ? false : true
      ),

      'id_sexo' => array(
        'required' => true,
        'maxLength' => 1,
      ),

      'id_tipo_ingreso' => array(
        'required' => true,
        'maxLength' => 1,
      ),

      'codigo_renipress' => array(
        'required' => true,
        'maxLength' => 8,
      ),

      'id_tipodoc_ident' => array(
        'required' => true,
        'maxLength' => 1,
      ),

      'numdoc_ident' => array(
        'required' => true,
        'maxLength' => 15,
      ),

      'id_upss_fups' => array(
        'required' => true,
        'maxLength' => 6,
      ),

    );

    $multipleRules = array(
      'id_cie10' => array(
        'required' => true,
        'maxLength' => 6,
      ),

      'id_tipo_diag' => array(
        'required' => true,
        'maxLength' => 1,
      ),

      'id_proced' => array(
        'required' => true,
        'maxLength' => 8,
      ),

      'cantidad_indicada' => array(
        'required' => true,
      ),

      'tipo_producto' => array(
        'required' => true,
        'maxLength' => 1,
      ),

      'id_prod_med' => array(
        'required' => true,
      ),

      'cantidad_prescrita' => array(
        'required' => true,
      ),
    );

    try {
      $messages = array();

      foreach ($rules as $field => $ruleSet) {
        $value = isset($trama[$field]) ? $trama[$field] : null;

        // REQUIRED
        if (!empty($ruleSet['required']) && ($value === null || $value === '')) {
          $messages[] = array(
            'Tipo de dato' => $field,
            'Detalle' => "$field es nulo o indefinido."
          );
          $result["isOk"] = false;
          continue;
        }

        // debe ser NULL
        if (!empty($ruleSet['null']) && ($value !== null || $value !== '')) {
          $messages[] = array(
            'Tipo de dato' => $field,
            'Detalle' => "$field debe ser nulo."
          );
          $result["isOk"] = false;
          continue;
        }

        // MAX LENGTH
        if (isset($ruleSet['maxLength']) && strlen($value) > $ruleSet['maxLength']) {
          $max = $ruleSet['maxLength'];
          $palabra = ($max == 1) ? 'caracter' : 'caracteres';
          $messages[] = array(
            'Tipo de dato' => $field,
            'Detalle' => "$field excede {$ruleSet['maxLength']} $palabra."
          );
          $result["isOk"] = false;
        }

        // NUMERIC
        if (!empty($ruleSet['numeric']) && !ctype_digit((string)$value)) {
          $messages[] = array(
            'Tipo de dato' => $field,
            'Detalle' => "$field no es un número válido."
          );
          $result["isOk"] = false;
        }

        // FECHA YYYY-mm-dd
        /* if (!empty($ruleSet['dateYYYYMMDD']) && !isRealDateYYYYMMDD($value)) {
          $messages[] = array(
            'Tipo de dato' => $field,
            'message' => "El $field no tiene formato YYYY-mm-dd."
          );
        } */

        // FECHA alta pasada la fecha actual
        if (!empty($ruleSet['notFutureCurrentDate'])) {
          if (/* isRealDateYYYYMMDD($value) &&  */$this->isFutureDate($value)) {
            $messages[] = array(
              'Tipo de dato' => $field,
              'Detalle' => "$field es mayor a la fecha actual."
            );
          }
        }

        // FECHA alta antes de la fecha ATENCION
        if (!empty($ruleSet['notPastDateAtencion'])) {
          if (/* isRealDateYYYYMMDD($value) &&  */$this->isPasteDatePrestacion($value, $trama["fecha_atencion_fups"])) {
            $messages[] = array(
              'Tipo de dato' => $field,
              'Detalle' => "$field es mayor a la fecha actual."
            );
          }
        }
      }

      foreach ($diagnosticos as $index => $diagnostico) {
        $newMessages = $this->validarCampos($diagnostico, $multipleRules, 2);
        $messages = array_merge(
          $messages,
          $newMessages
        );
        if (count($newMessages) > 0) {
          $result["isOk"] = false;
        }
      }

      foreach ($procedimientos as $index => $procedimiento) {
        $newMessages = $this->validarCampos($procedimiento, $multipleRules, 3);
        $messages = array_merge(
          $messages,
          $newMessages
        );
        if (count($newMessages) > 0) {
          $result["isOk"] = false;
        }
      }

      foreach ($productos as $index => $producto) {
        $newMessages = $this->validarCampos($producto, $multipleRules, 4);
        $messages = array_merge(
          $messages,
          $newMessages
        );
        if (count($newMessages) > 0) {
          $result["isOk"] = false;
        }
      }
      if (count($messages) > 0) {
        $result["message"] = "La estructura de datos de la trama prestacion no es correcta.";
        $result["data"] = $messages;
      }
    } catch (Exception $e) {
      $result["isOk"] = false;
      $result["message"] = "Error inesperado durante la validación del asegurado. " . $e->getMessage();
    }
    return $result;
  }

  function isFutureDate($date)
  {
    if (empty($date)) {
      return false;
    }

    // Crear DateTime desde YYYY-mm-dd
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateObj) {
      return false;
    }

    // Fecha actual (sin hora)
    $today = new DateTime('today');

    return $dateObj > $today;
  }

  function isPasteDatePrestacion($value, $fechaAtencion)
  {
    if (empty($value) || empty($fechaAtencion)) {
      return false;
    }

    $valueDate = DateTime::createFromFormat('Y-m-d', $value);
    $atencionDate = DateTime::createFromFormat('Y-m-d', $fechaAtencion);

    if (!$valueDate || !$atencionDate) {
      return false;
    }

    // Comparación sin hora
    return $valueDate < $atencionDate;
  }

  public function validarCampos($row, $rules, $nro_trama)
  {
    $messages = [];

    $map = array(
      2 => 'Diagnostico',
      3 => 'Procedimiento',
    );

    $tipo_trama = isset($map[$nro_trama]) ? $map[$nro_trama] : 'Producto';

    $data = (array) $row;

    foreach ($rules as $field => $rule) {
      if (!array_key_exists($field, $data)) {
        continue;
      }

      $value = isset($data[$field]) ? $data[$field] : null;

      // requerido
      if (!empty($rule['required']) && ($value === null || $value === '')) {
        $messages[] = [
          /* 'id_' . $tipo_trama */"$tipo_trama ID" => $data['id'],
          'Tipo de dato' => $field,
          'Detalle' => "{$field} es nulo o indefinido."
        ];
      }

      // longitud máxima
      if (isset($rule['maxLength']) && $value !== null) {
        if (strlen((string)$value) > $rule['maxLength']) {
          $max = $rule['maxLength'];
          $palabra = ($max == 1) ? 'caracter' : 'caracteres';
          $messages[] = [
            /* 'id_' . $tipo_trama */"$tipo_trama ID" => $data['id'],
            'Tipo de dato' => $field,
            'Detalle' => "{$field} excede {$rule['maxLength']} $palabra."
          ];
        }
      }
    }

    return $messages;
  }

  function regla2($trama)
  {
    $resultTramaValidate = array("isOk" => true, "regla" => 2, "message" => "");
    try {
      $numeroAcreditacionSiteds = null;
      if (isset($trama->numero_acreditacion_siteds) && $trama->numero_acreditacion_siteds != '') {
        $numeroAcreditacionSiteds = $trama->numero_acreditacion_siteds;
      }
      if ($numeroAcreditacionSiteds === null || strtoupper(substr($numeroAcreditacionSiteds, -1)) === 'T') {
        $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD'), 'exceptions' => true, 'trace' => true));
        $beneficiario = $client->getAseguradoValidate($trama->tipo_doc_identidad, $trama->paciente_nro_doc_identidad);
        if (empty($beneficiario) || !is_object($beneficiario) || !isset($beneficiario->caducidad)) {
          $resultTramaValidate["isOk"] = false;
          $resultTramaValidate["message"] = "No se pudo validar la vigencia del paciente (respuesta inválida del servicio).";
          return $resultTramaValidate;
        }
        $fechaCaducidad = Carbon::createFromFormat('Ymd', $beneficiario->caducidad)->startOfDay();
        $currentDate = Carbon::today();

        if ($fechaCaducidad->lt($currentDate)) {
          $resultTramaValidate["isOk"] = false;
          $resultTramaValidate["message"] = "Paciente no se encuentra vigente en la fecha de atención.";
        }
      }
    } catch (SoapFault $e) {
      $resultTramaValidate["isOk"] = false;
      $resultTramaValidate["message"] = "Error de comunicación con el servicio de validación del asegurado. " . $e->getMessage();
    } catch (Exception $e) {
      $resultTramaValidate["isOk"] = false;
      $resultTramaValidate["message"] = "Error inesperado durante la validación del asegurado. " . $e->getMessage();
    }
    return $resultTramaValidate;
  }

  function regla3($trama)
  {
    $resultTramaValidate = array("isOk" => true, "regla" => 3, "message" => "");
    try {
      // TODO: Implementar consulta con tabla de ipress validadas en el convenio de saludpol.
    } catch (Exception $e) {
      $resultTramaValidate["isOk"] = false;
      $resultTramaValidate["message"] = "Error inesperado durante la validación del asegurado. " . $e->getMessage();
    }

    return $resultTramaValidate;
  }

  function regla5($trama)
  {
    $result = array('isOk' => true, 'regla' => 5, 'message' => '', 'data' => null);

    try {
      $id_tipo_doc_paciente = $trama["id_tipodoc_ident"];
      $nro_doc_paciente = $trama["paciente_nro_doc_identidad"];
      $upss_atencion = $trama["id_upss_fups"];
      $fecha_atencion = $trama["fecha_atencion_fups"];
      $codigo_ipress = $trama["codigo_renipress"];
      $id_tipo_doc_medico = $trama["tipo_doc_identidad"];
      $nro_doc_medico = $trama["numdoc_ident"];

      $tramasDuplicadas = trama_prestacion::whereDate('fecha_atencion_fups', '=', $fecha_atencion)
        ->where('paciente_nro_doc_identidad', $nro_doc_paciente)
        ->where('id_upss_fups', $upss_atencion)
        ->where('codigo_renipress', $codigo_ipress)
        ->where('id', '!=', $trama["id"])
        ->get();

      if (count($tramasDuplicadas) > 0) {
        $result["isOk"] = false;
        $result["message"] = "Existen tramas repetidas bajo los parametros de nro de documento de paciente, id de upss y codigo renipress.";
        $result['data'] = $tramasDuplicadas;
      }
    } catch (Exception $e) {
      $result["isOk"] = false;
      $result["message"] = "Error inesperado durante la validación del asegurado. " . $e->getMessage();
    }

    return $result;
  }

  function regla6($trama)
  {
    $result = array('isOk' => true, 'regla' => 6, 'message' => '', 'data' => null);
    try {
      $diagnosticos = trama_diagnosticos::where('id_trama_prestacion', $trama->id)->get();
      $conteo = array();
      foreach ($diagnosticos as $d) {
        if (isset($d->id_cie10)) {
          $conteo[$d->id_cie10] = isset($conteo[$d->id_cie10]) ? $conteo[$d->id_cie10] + 1 : 1;
        }
      }
      $repetidos = array();
      foreach ($conteo as $id => $cantidad) {
        if ($cantidad > 1) {
          $repetidos[] = ['Codígo Diagnostico' => $id, 'Cantidad Repetidos' => $cantidad];
        }
      }
      if (!empty($repetidos)) {
        $result['isOk'] = false;
        $result['message'] = 'Existen diagnósticos repetidos en la trama de prestación.';
        $result['data'] = $repetidos;
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 6.' . $e->getMessage();
    }
    return $result;
  }

  function regla7($trama)
  {
    $result = array('isOk' => true, 'regla' => 7, 'message' => '', 'data' => null);

    try {
      $trama_id = $trama['id'];

      $procedimientosRepetidos = trama_procedimientos::select('id_proced')
        ->where('id_trama_prestacion', '=', $trama['id'])
        ->groupBy('id_proced')
        ->havingRaw('COUNT(*) > 1')
        ->get();

      if (count($procedimientosRepetidos) > 0) {
        $result["isOk"] = false;
        $result["message"] = "Existen procedimientos repetidos bajo la trama $trama_id";
        $result['data'] = $procedimientosRepetidos;
      }
    } catch (Exception $e) {
      $result["isOk"] = false;
      $result["message"] = "Error inesperado durante la validación del asegurado. " . $e->getMessage();
    }

    return $result;
  }

  function regla8($trama)
  {
    $result = array('isOk' => true, 'regla' => 8, 'message' => '', 'data' => null);
    try {
      $productos = trama_productos::where('id_trama_prestacion', $trama->id)->get();
      $conteo = array();
      foreach ($productos as $d) {
        if (isset($d->id_prod_med)) {
          $conteo[$d->id_prod_med] = isset($conteo[$d->id_prod_med]) ? $conteo[$d->id_prod_med] + 1 : 1;
        }
      }
      $repetidos = array();
      foreach ($conteo as $id => $cantidad) {
        if ($cantidad > 1) {
          $repetidos[] = ['Codigo Producto' => $id, 'Cantidad Repetidos' => $cantidad];
        }
      }
      if (!empty($repetidos)) {
        $result['isOk'] = false;
        $result['message'] = 'Existen productos médicos repetidos en la trama de prestación.';
        $result['data'] = $repetidos;
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 8.' . $e->getMessage();
    }
    return $result;
  }

  function validarProcedimientosConvenio($procedimientosTrama)
  {
    $idsProced = array();

    foreach ($procedimientosTrama as $item) {
      if (!in_array($item->id_proced, $idsProced)) {
        $idsProced[] = $item->id_proced;
      }
    }

    if (empty($idsProced)) {
      return array();
    }

    $procedimientos = Procedimiento::whereIn('codigo', $idsProced)
      ->where('convenio', 0)
      ->get();

    // Agrupar manualmente (por id_proced)
    $agrupados = array();

    foreach ($procedimientos as $proc) {
      $agrupados[$proc->id][] = $proc;
    }

    return $agrupados;
  }

  function regla9($trama)
  {
    $result = array('isOk' => true, 'regla' => 9, 'message' => '', 'data' => null);

    try {
      $trama_id = $trama['id'];

      $procedimientosTrama = trama_procedimientos::where('id_trama_prestacion', '=', $trama_id)
        ->get();

      $procedimientosInvalidos = $this->validarProcedimientosConvenio($procedimientosTrama);

      if (count($procedimientosInvalidos) > 0) {
        $result["isOk"] = false;
        $result["message"] = "La trama $trama_id cuenta con procedimientos no aceptados por el convenio.";
        $result['data'] = $procedimientosInvalidos;
      }
    } catch (Exception $e) {
      $result["isOk"] = false;
      $result["message"] = "Error inesperado durante la validación del asegurado. " . $e->getMessage();
    }

    return $result;
  }

  function regla10($trama)
  {
    $result = array('isOk' => true, 'regla' => 10, 'message' => '', 'data' => null);
    try {
      $productos = DB::table('trama_productos as tp')
        ->join('productos as p', 'tp.id_prod_med', '=', 'p.codigo')
        ->where('tp.id_trama_prestacion', $trama->id)
        ->select(['tp.id as trama_producto_id', 'p.id as producto_id', 'p.codigo as producto_codigo', 'p.nombre as producto', 'p.petitorio as convenio', 'tp.id_prod_med'])
        ->get();
      $productosNoPetitorio = array();
      foreach ($productos as $producto) {
        if (empty($producto->convenio) || strtoupper($producto->convenio) === "NO") {
          $productosNoPetitorio[] = ["Codigo Producto" => $producto->id_prod_med];
        }
      }

      if (count($productosNoPetitorio) > 0) {
        $result['isOk'] = false;
        $result['message'] = 'Existen productos médicos que no cumplen con lo establecido en el convenio.';
        $result['data'] = $productosNoPetitorio;
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 10.' . $e->getMessage();
    }
    return $result;
  }

  function regla11($trama)
  {
    $result = array('isOk' => true, 'regla' => 11, 'message' => '');
    try {
      // Sexo femenino → OK directo
      if ($trama->id_sexo == 2) {
        $result['message'] = 'El paciente es de sexo femenino.';
        return $result;
      }

      $upssAtencion = (string) $trama->id_upss_fups;
      $sexoPaciente = ((string) $trama->id_sexo) == '1' ? 'MASCULINO' : 'FEMENINO';

      $upssNoPermitidas = ['221500', '221502', '221504', '221600', '241500', '241700'];

      $mensajeError = "La upss {$upssAtencion} de la atención no corresponde al género $sexoPaciente del paciente.";

      // Si la UPS no está restringida → OK
      if (!in_array($upssAtencion, $upssNoPermitidas, true)) {
        $result['message'] = 'La atención del paciente masculino no se encuentra bajo una upss restringida.';
        return $result;
      }

      // Caso especial: UPS 221600
      if ($upssAtencion === '221600') {

        $procedimientosTrama = trama_procedimientos::where('id_trama_prestacion', $trama->id)
          ->get()
          ->pluck('id_proced')
          ->toArray();

        $cpmsPermitidos = ['99208', '99208.02', '99402.04', '99402'];

        if (!array_intersect($procedimientosTrama, $cpmsPermitidos)) {
          $result['isOk'] = false;
          $result['message'] = $mensajeError;
          return $result;
        }

        return $result;
      }

      // Otras UPS no permitidas
      $result['isOk'] = false;
      $result['message'] = $mensajeError;

      return $result;
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 11.' . $e->getMessage();
    }
    return $result;
  }

  function regla12($trama)
  {
    $result = array('isOk' => true, 'regla' => 12, 'message' => '', 'data' => null);
    try {
      if (empty($trama->id_upss_fups)) {
        $result['isOk'] = false;
        $result['message'] = 'El campo id_upss_fups es obligatorio para la validación.';
        return $result;
      }
      if (empty($trama->asegurado_fecha_nacimiento)) {
        $result['isOk'] = false;
        $result['message'] = 'El campo asegurado_fecha_nacimiento es obligatorio para la validación.';
        return $result;
      }
      if (empty($trama->id_tipo_cobert)) {
        $result['isOk'] = false;
        $result['message'] = 'El campo id_tipo_cobert es obligatorio para la validación.';
        return $result;
      }

      $fechaNacimiento = Carbon::parse($trama->asegurado_fecha_nacimiento);
      $edad = $fechaNacimiento->age;

      $upssAllowedCe = array('220500', '222000', '224700', '225800', '260000');
      $upssAllowedEmerHospi = array('230106', '240700', '242300', '250105', '250108', '250201');

      if (intval($trama->id_tipo_cobert) == 4) { // CE
        if (in_array($trama->id_upss_fups, $upssAllowedCe, true) && intval($edad) < 18) {
          $result['isOk'] = false;
          $result['message'] = 'La prestación corresponde a una atención de Consulta Externa (CE) en la upss ' . $trama->id_upss_fups . ' que no atiende menores de 18 años.';
        }
      }

      if (intval($trama->id_tipo_cobert) == 5 || intval($trama->id_tipo_cobert) == 6) { // Emergencia / Hospitalización
        if (in_array($trama->id_upss_fups, $upssAllowedEmerHospi, true) && intval($edad) < 18) {
          $result['isOk'] = false;
          $result['message'] = 'La prestación corresponde a una atención de Emergencia / Hospitalización en la upss ' . $trama->id_upss_fups . ' que no atiende menores de 18 años.';
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 12.' . $e->getMessage();
    }
    return $result;
  }

  function regla13($trama)
  {
    $result = array('isOk' => true, 'regla' => 13, 'message' => '', 'data' => null);
    try {
      $trama_id = (int) $trama->id;
      $tipo_atencion = (int) $trama->id_tipo_ingreso;

      if ($tipo_atencion == 1) {
        /* $cie10s = trama_productos::where('id_trama_prestacion', $trama_id)
          ->get(['id_cie10'])
          ->unique('id_cie10')
          ->pluck('id_cie10')
          ->values(); */
        /* $diagnosticosPresuntivos = trama_diagnosticos::where('id_trama_prestacion', $trama_id)
          ->whereIn('id_cie10', $cie10s)
          ->where("id_tipo_diag", 1)
          ->get(); */
        $cie10Presuntivos = trama_diagnosticos::where('id_trama_prestacion', $trama_id)
          ->where('id_tipo_diag', 1)
          ->get(['id_cie10'])
          ->unique('id_cie10')
          ->pluck('id_cie10')
          ->values();
        $productosConDiagPresuntivo = trama_productos::where('id_trama_prestacion', $trama_id)
          ->whereIn('id_cie10', $cie10Presuntivos)
          ->get();
        if (count($productosConDiagPresuntivo) > 0) {
          $result['isOk'] = false;
          $result['data'] = $productosConDiagPresuntivo;
          $result['message'] = 'Existen productos asociados a diagnosticos presuntivos.';
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 13.' . $e->getMessage();
    }
    return $result;
  }

  function regla14($trama)
  {
    $result = array('isOk' => true, 'regla' => 14, 'message' => '', 'data' => null);
    try {
      if ((int) $trama->id_tipo_cobert === 4) { // CE
        $proceduresNotAllowed = array('99231', '99221', '99231.15', '99262', '99263', '99295');
        $proceduresFound = array();

        $procedimientos = trama_procedimientos::where('id_trama_prestacion', $trama->id)->get();

        $countDaysHospitalized = 0;

        foreach ($procedimientos as $key => $procedimiento) {
          if (in_array((string) $procedimiento->id_proced, $proceduresNotAllowed, true)) {
            $countDaysHospitalized += (int) $procedimiento->cantidad_ejecutada;
            $proceduresFound[] = ["Codígo Procedimiento" => (string) $procedimiento->id_proced];
          }
        }

        if ((int) $countDaysHospitalized > 0) {
          $result['isOk'] = false;
          $result['message'] = 'La prestación corresponde a una atención de Consulta Externa (CE) y contiene procedimientos propios de atenciones hospitalarias.';
          $result['data'] = $proceduresFound;
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 14.' . $e->getMessage();
    }
    return $result;
  }

  function regla15($trama)
  {
    $result = array('isOk' => true, 'regla' => 15, 'message' => '', 'data' => null);
    try {
      $trama_id = (int) $trama->id;
      $tipo_atencion = (int) $trama->id_tipo_ingreso;
      $cpmsHospitalizacion = ["99295", "99221", "99231", "99262", "99263", "99231.15"];

      if ($tipo_atencion == 2) {
        $procedimientosTrama = trama_procedimientos::whereIn("id_proced", $cpmsHospitalizacion)->where("id_trama_prestacion", $trama_id)->get();
        if (count($procedimientosTrama) > 0) {
          $result['isOk'] = false;
          $result['data'] = $procedimientosTrama;
          $result['message'] = 'Existen CPMS no correspondientes al nivel 2 (EMERGENCIA) en esta prestacion.';
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 13.' . $e->getMessage();
    }
    return $result;
  }

  function regla16($trama)
  {
    $result = array('isOk' => true, 'regla' => 16, 'message' => '', 'data' => null);
    try {
      if (intval($trama->id_tipo_cobert) === 5) {
        $proceduresForEvaluation = array('99231', '99221', '99231.15', '99262', '99295', '99263');
        $proceduresFound = array();

        if (empty($trama->fecha_ingreso_fups) || empty($trama->fech_alta_atenc)) {
          $result['isOk'] = false;
          $result['message'] = 'Faltan datos necesarios para la validación de la regla 16.';
          return $result;
        }

        $fechaIngreso = Carbon::parse($trama->fecha_ingreso_fups);
        $fechaFin = Carbon::parse($trama->fech_alta_atenc);
        $diasEstancia = $fechaIngreso->diffInDays($fechaFin) + 1;

        $procedimientos = trama_procedimientos::where('id_trama_prestacion', $trama->id)->get();

        $sumCantidadProcedimientos = 0;
        foreach ($procedimientos as $key => $procedimiento) {
          if (in_array((string) $procedimiento->id_proced, $proceduresForEvaluation, true) && !empty($procedimiento->cantidad_ejecutada)) {
            $sumCantidadProcedimientos += (int) $procedimiento->cantidad_ejecutada;
            $proceduresFound[] = ["Codígo Procedimiento" => (string) $procedimiento->id_proced];
            break;
          }
        }
        if ((int) $sumCantidadProcedimientos > (int) $diasEstancia) {
          $result['isOk'] = false;
          $result['message'] = 'La cantidad de procedimientos de evaluación médica (' . $sumCantidadProcedimientos . ') excede el número de días de estancia (' . $diasEstancia . ').';
          $result['data'] = $proceduresFound;
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 16.' . $e->getMessage();
    }
    return $result;
  }

  function regla17($trama)
  {
    $result = array('isOk' => true, 'regla' => 17, 'message' => '');
    try {
      $trama_id = (int) $trama->id;
      $tipo_atencion = (int) $trama->id_tipo_ingreso;
      $fechaIngreso = new DateTime($trama->fecha_ingreso_fups);
      $fechaEgreso  = new DateTime($trama->fecha_egreso_fups);

      $diff = $fechaIngreso->diff($fechaEgreso);
      $diasEstancia = $diff->days + 1;

      $cpmsHospitalarios = ["99295", "99221", "99231", "99262", "99263", "99231.15"];

      if ($tipo_atencion == 3) {
        $cantidadesProcedimientoTrama = trama_procedimientos::where("id_trama_prestacion", $trama_id)
          ->whereIn("id_proced", $cpmsHospitalarios)
          ->get()
          ->pluck('cantidad_indicada')
          ->values();
        $sumaCantidadesProcedimiento = $cantidadesProcedimientoTrama->sum();
        if ($sumaCantidadesProcedimiento > $diasEstancia) {
          $result['isOk'] = false;
          $result['message'] = 'La cantidad de procedimientos hospitalarios excede a los dias de estancia.';
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 17.' . $e->getMessage();
    }
    return $result;
  }

  function regla18($trama)
  {
    $result = array('isOk' => true, 'regla' => 18, 'message' => '', 'data' => null);
    try {
      if (intval($trama->id_tipo_cobert) == 5 && intval($trama->id_tipo_cobert) == 6) { // Emergencia / Hospitalización
        $proceduresNotAllowed = array('99213', '99201', '99202', '99203', '99204', '99211', '99212', '99215', '99215.02');
        $proceduresNotAllowedFound = array();

        $procedimientos = trama_procedimientos::where('id_trama_prestacion', $trama->id)->get();
        foreach ($procedimientos as $key => $procedimiento) {
          if (in_array((string) $procedimiento->id_proced, $proceduresNotAllowed, true)) {
            $proceduresNotAllowedFound[] = ["Codígo Procedimiento" => (string) $procedimiento->id_proced];
          }
        }
        if (count($proceduresNotAllowedFound) > 0) {
          $result['isOk'] = false;
          $result['message'] = 'La prestación contiene procedimientos no permitidos para el tipo de atención ambulatoria.';
          $result['data'] = $proceduresNotAllowedFound;
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 18.' . $e->getMessage();
    }
    return $result;
  }

  function regla19($trama)
  {
    $result = array('isOk' => true, 'regla' => 19, 'message' => '', 'data' => null);
    try {
      $trama_id = (int) $trama->id;
      $tipo_atencion = (int) $trama->id_tipo_ingreso;
      $tipo_profesional = (string) $trama->id_tipo_prof;
      $codigo_upss = (string) $trama->id_upss_fups;

      $profesionalesPermitidos = ['01', '07'];
      $upssPermitidas = [
        '224102',
        '224103',
        '224104',
        '224105',
        '224200',
        '224203',
        '260900',
        '260902',
      ];

      if (
        $tipo_atencion == 1 &&
        in_array($tipo_profesional, $profesionalesPermitidos, true) &&
        in_array($codigo_upss, $upssPermitidas, true)
      ) {
        $procedimientos = trama_procedimientos::where("id_trama_prestacion", $trama_id)->where("id_proced", "99207")->get();
        if (count($procedimientos) > 0) {
          $result['isOk'] = false;
          $result['data'] = $procedimientos;
          $result['message'] = 'Existe procedimiento 99207 asociado a una prestacion del tipo de atencion AMBULATORIO y con el tipo de profesional de Medico o Psicologo.';
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 19.' . $e->getMessage();
    }
    return $result;
  }

  function regla20($trama)
  {
    $result = array('isOk' => true, 'regla' => 20, 'message' => '', 'data' => null);
    try {
      $idNutricion = 12;
      $isEvaluation = false;
      $procedimientos = trama_procedimientos::where('id_trama_prestacion', $trama->id)->get();
      foreach ($procedimientos as $key => $procedimiento) {
        if ((string) $procedimiento->id_proced === '99209') {
          $isEvaluation = true;
          break;
        }
      }

      if ($isEvaluation && intval($trama->id_tipo_cobert) === 4) {
        $result['isOk'] = false;
        $result['message'] = 'La prestación corresponde a una evaluación nutricional bajo cobertura de Consulta Externa (CE), por lo que el profesional debe ser Nutricionista.';

        $model_medico = new Medico();
        $medicos = $model_medico->GetMedicoByDNI($trama->numdoc_ident);
        if (empty($medicos)) {
          $result['isOk'] = false;
          $result['message'] = 'El médico con DNI ' . $trama->numdoc_ident . ' no está registrado en el sistema.';
          return $result;
        }

        if (intval($medicos->id_profesion) === $idNutricion && $trama->id_upss_fups !== '200000') {
          $result['isOk'] = true;
          $result['message'] = 'La prestación corresponde a una evaluación nutricional bajo cobertura de Consulta Externa (CE) y el profesional es Nutricionista.';
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 20.' . $e->getMessage();
    }
    return $result;
  }

  function regla21($trama)
  {
    $result = array('isOk' => true, 'regla' => 21, 'message' => '');
    try {
      $trama_id = (int) $trama->id;
      $tipo_atencion = (int) $trama->id_tipo_ingreso;
      if (
        $tipo_atencion == 1
      ) {
        $procedimientoQuirugico = trama_procedimientos::where("id_trama_prestacion", $trama_id)
          ->where("id_proced", "99199.01")
          ->get()
          ->pluck('cantidad_indicada')
          ->values();
        if (count($procedimientoQuirugico) == 1) {
          if ($procedimientoQuirugico->sum() > 1) {
            $result['isOk'] = false;
            $result['message'] = 'El procedimiento quirurgico 99199.01 excede a la maxima cantidad de veces indicada (1) en la prestacion del tipo AMBULATORIO.';
          }
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 21.' . $e->getMessage();
    }
    return $result;
  }

  function regla22($trama)
  {
    $result = array('isOk' => true, 'regla' => 22, 'message' => '', 'data' => null);
    try {
      $procedimientos = trama_procedimientos::where('id_trama_prestacion', $trama->id)->get();
      foreach ($procedimientos as $key => $procedimiento) {
        if ((string) $procedimiento->id_proced === '93000' && (int) $procedimiento->cantidad_ejecutada > 1) {
          $result['isOk'] = false;
          $result['message'] = 'Evitar el registro de prestaciones ambulatorias con más de una ejecución del procedimiento de electrocardiograma.';
          break;
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 22.' . $e->getMessage();
    }
    return $result;
  }

  function regla23($trama)
  {
    $result = array('isOk' => true, 'regla' => 23, 'message' => '', 'data' => null);
    try {
      $trama_id = (int) $trama->id;
      $tipo_atencion = (int) $trama->id_tipo_ingreso;
      $tipo_profesional = (string) $trama->id_tipo_prof;
      $codigo_upss = (string) $trama->id_upss_fups;

      $profesionalesPermitidos = ['03'];
      $upssPermitidas = [
        '221700',
        '221800',
        '221801',
        '221802',
        '221900',
        '222000',
        '222100',
        '222200',
        '222300',
      ];

      if (
        $tipo_atencion == 1 &&
        in_array($tipo_profesional, $profesionalesPermitidos, true) &&
        in_array($codigo_upss, $upssPermitidas, true)
      ) {
        $diagnosticos = trama_diagnosticos::where("id_trama_prestacion", $trama_id)->where("id_cie10", "D0160")->get();
        if (count($diagnosticos) > 0) {
          $result['isOk'] = false;
          $result['data'] = $diagnosticos;
          $result['message'] = 'Existe diagnostico D0160 asociado a una prestacion del tipo de atencion AMBULATORIO y con el tipo de profesional de Odontologo.';
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 23.' . $e->getMessage();
    }
    return $result;
  }

  function regla24($trama)
  {
    $result = array('isOk' => true, 'regla' => 24, 'message' => '', 'data' => null);
    try {
      $codesDiagnosticosNotAllowed = ["Z41.1", "Z41.0", "V90.2", "V90.3", "V90.4", "V90.5", "V90.7", "V90.8", "V90.9", "V91.3", "V91.4", "V91.5", "V91.7", "V91.8", "V91.9", "V92.0", "V92.1", "V92.2", "V92.3", "V92.4", "V92.5", "V92.6", "V92.7", "V92.8", "V92.9", "V94.0", "V94.1", "V94.2", "V94.3", "V94.4", "V94.5", "V94.6", "V94.7", "V94.8", "V94.9", "V95.1", "V95.2", "V95.3", "V95.8", "V95.9", "V96.0", "V96.1", "V96.2", "V96.8", "V96.9", "V97.1", "V97.2", "O04.8"];
      $codesDiagnosticosNotAllowedFound = array();

      $diagnosticos = trama_diagnosticos::where('id_trama_prestacion', $trama->id)->get();
      foreach ($diagnosticos as $key => $diagnostico) {
        if (in_array((string) $diagnostico->id_cie10, $codesDiagnosticosNotAllowed, true)) {
          $codesDiagnosticosNotAllowedFound[] = ["Codígo Diagnostico" => (string) $diagnostico->id_cie10];
          break;
        }
      }
      if (count($codesDiagnosticosNotAllowedFound) > 0) {
        $result['isOk'] = false;
        $result['message'] = 'La prestación contiene diagnósticos no permitidos por SALUDPOL.';
        $result['data'] = $codesDiagnosticosNotAllowedFound;
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 24.' . $e->getMessage();
    }
    return $result;
  }

  function regla25($trama)
  {
    $result = array('isOk' => true, 'regla' => 25, 'message' => '', 'data' => null);
    try {
      $trama_id = (int) $trama->id;
      $sexo_paciente = (int) $trama->id_sexo;
      $sexo_paciente_descripcion = $sexo_paciente == 1 ? "MASCULINO" : "FEMENINO";
      $diagnosticosNoCorrespondientes = trama_diagnosticos::where("id_trama_prestacion", $trama_id)
        ->leftJoin(
          'diagnosticos',
          'diagnosticos.id',
          '=',
          'trama_diagnosticos.id_diagnostico'
        )
        ->select("trama_diagnosticos.*")
        ->where('diagnosticos.genero', '!=', $sexo_paciente)
        ->where('diagnosticos.genero', '!=', 3)
        ->get();
      if (count($diagnosticosNoCorrespondientes) > 0) {
        $result['isOk'] = false;
        $result['data'] = $diagnosticosNoCorrespondientes;
        $result['message'] = "Existen diagnosticos no correspondientes al sexo del paciente ($sexo_paciente_descripcion).";
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 25.' . $e->getMessage();
    }
    return $result;
  }

  function regla26($trama)
  {
    $result = array('isOk' => true, 'regla' => 26, 'message' => '', 'data' => null);
    try {
      $genero = $trama->id_sexo;
      $proceduresForEvaluation = [
        ["code" => "59515", "genero" => 2],
        ["code" => "59510", "genero" => 2],
        ["code" => "59610", "genero" => 2],
        ["code" => "59514", "genero" => 2],
        ["code" => "76816", "genero" => 2],
        ["code" => "76801", "genero" => 2],
        ["code" => "58210", "genero" => 2],
        ["code" => "55700", "genero" => 1],
        ["code" => "55706", "genero" => 1],
        ["code" => "78761", "genero" => 1],
        ["code" => "52450", "genero" => 1],
        ["code" => "54120", "genero" => 1],
        ["code" => "55705", "genero" => 1],
        ["code" => "54360", "genero" => 1],
        ["code" => "54300", "genero" => 1],
        ["code" => "55720", "genero" => 2],
        ["code" => "99401.02", "genero" => 2],
        ["code" => "99401.03", "genero" => 2],
        ["code" => "99401.13", "genero" => 2],
        ["code" => "99401.15", "genero" => 1],
        ["code" => "99401.16", "genero" => 2],
        ["code" => "88141.01", "genero" => 2],
        ["code" => "99386.01", "genero" => 1],
        ["code" => "99386.02", "genero" => 2],
        ["code" => "59400", "genero" => 2],
        ["code" => "59409", "genero" => 2],
        ["code" => "59410", "genero" => 2],
        ["code" => "59610", "genero" => 2],
        ["code" => "59612", "genero" => 2],
        ["code" => "59614", "genero" => 2],
        ["code" => "59514", "genero" => 2],
        ["code" => "59515", "genero" => 2],
        ["code" => "59510", "genero" => 2],
        ["code" => "59618", "genero" => 2],
        ["code" => "59620", "genero" => 2],
        ["code" => "59622", "genero" => 2],
        ["code" => "56405", "genero" => 2],
        ["code" => "56420", "genero" => 2],
        ["code" => "56440", "genero" => 2],
        ["code" => "56441", "genero" => 2],
        ["code" => "56501", "genero" => 2],
        ["code" => "56515", "genero" => 2],
        ["code" => "56605", "genero" => 2],
        ["code" => "56606", "genero" => 2],
        ["code" => "56620", "genero" => 2],
        ["code" => "56625", "genero" => 2],
        ["code" => "56630", "genero" => 2],
        ["code" => "56631", "genero" => 2],
        ["code" => "56632", "genero" => 2],
        ["code" => "56633", "genero" => 2],
        ["code" => "56634", "genero" => 2],
        ["code" => "56637", "genero" => 2],
        ["code" => "56640", "genero" => 2],
        ["code" => "56700", "genero" => 2],
        ["code" => "56720", "genero" => 2],
        ["code" => "56740", "genero" => 2],
        ["code" => "56800", "genero" => 2],
        ["code" => "57061", "genero" => 2],
        ["code" => "57065", "genero" => 2],
        ["code" => "57100", "genero" => 2],
        ["code" => "57105", "genero" => 2],
        ["code" => "57106", "genero" => 2],
        ["code" => "57107", "genero" => 2],
        ["code" => "57109", "genero" => 2],
        ["code" => "57110", "genero" => 2],
        ["code" => "57111", "genero" => 2],
        ["code" => "57112", "genero" => 2],
        ["code" => "57150", "genero" => 2],
        ["code" => "57200", "genero" => 2],
        ["code" => "57210", "genero" => 2],
        ["code" => "57240", "genero" => 2],
        ["code" => "57250", "genero" => 2],
        ["code" => "57260", "genero" => 2],
        ["code" => "57265", "genero" => 2],
        ["code" => "57280", "genero" => 2],
        ["code" => "57282", "genero" => 2],
        ["code" => "57284", "genero" => 2],
        ["code" => "57335", "genero" => 2],
        ["code" => "57410", "genero" => 2],
        ["code" => "57415", "genero" => 2],
        ["code" => "57452", "genero" => 2],
        ["code" => "57454", "genero" => 2],
        ["code" => "57460", "genero" => 2],
        ["code" => "57500", "genero" => 2],
        ["code" => "57505", "genero" => 2],
        ["code" => "57510", "genero" => 2],
        ["code" => "57513", "genero" => 2],
        ["code" => "57520", "genero" => 2],
        ["code" => "57522", "genero" => 2],
        ["code" => "57530", "genero" => 2],
        ["code" => "57531", "genero" => 2],
        ["code" => "57700", "genero" => 2],
        ["code" => "57720", "genero" => 2],
        ["code" => "57800", "genero" => 2],
        ["code" => "57820", "genero" => 2],
        ["code" => "58100", "genero" => 2],
        ["code" => "58120", "genero" => 2],
        ["code" => "58140", "genero" => 2],
        ["code" => "58145", "genero" => 2],
        ["code" => "58150", "genero" => 2],
        ["code" => "58152", "genero" => 2],
        ["code" => "58180", "genero" => 2],
        ["code" => "58200", "genero" => 2],
        ["code" => "58210", "genero" => 2],
        ["code" => "58240", "genero" => 2],
        ["code" => "58260", "genero" => 2],
        ["code" => "58262", "genero" => 2],
        ["code" => "58263", "genero" => 2],
        ["code" => "58267", "genero" => 2],
        ["code" => "58270", "genero" => 2],
        ["code" => "58275", "genero" => 2],
        ["code" => "58280", "genero" => 2],
        ["code" => "58285", "genero" => 2],
        ["code" => "58300", "genero" => 2],
        ["code" => "58301", "genero" => 2],
        ["code" => "58340", "genero" => 2],
        ["code" => "58345", "genero" => 2],
        ["code" => "58400", "genero" => 2],
        ["code" => "58410", "genero" => 2],
        ["code" => "58520", "genero" => 2],
        ["code" => "58540", "genero" => 2],
        ["code" => "58600", "genero" => 2],
        ["code" => "58605", "genero" => 2],
        ["code" => "58611", "genero" => 2],
        ["code" => "58615", "genero" => 2],
        ["code" => "58700", "genero" => 2],
        ["code" => "58720", "genero" => 2],
        ["code" => "58740", "genero" => 2],
        ["code" => "58750", "genero" => 2],
        ["code" => "58752", "genero" => 2],
        ["code" => "58760", "genero" => 2],
        ["code" => "58770", "genero" => 2],
        ["code" => "58800", "genero" => 2],
        ["code" => "58805", "genero" => 2],
        ["code" => "58820", "genero" => 2],
        ["code" => "58822", "genero" => 2],
        ["code" => "58823", "genero" => 2],
        ["code" => "58825", "genero" => 2],
        ["code" => "58900", "genero" => 2],
        ["code" => "58920", "genero" => 2],
        ["code" => "58925", "genero" => 2],
        ["code" => "58940", "genero" => 2],
        ["code" => "58943", "genero" => 2],
        ["code" => "58950", "genero" => 2],
        ["code" => "58951", "genero" => 2],
        ["code" => "58952", "genero" => 2],
        ["code" => "58960", "genero" => 2],
        ["code" => "54000", "genero" => 1],
        ["code" => "54001", "genero" => 1],
        ["code" => "54015", "genero" => 1],
        ["code" => "54050", "genero" => 1],
        ["code" => "54056", "genero" => 1],
        ["code" => "54057", "genero" => 1],
        ["code" => "54060", "genero" => 1],
        ["code" => "54065", "genero" => 1],
        ["code" => "54100", "genero" => 1],
        ["code" => "54105", "genero" => 1],
        ["code" => "54110", "genero" => 1],
        ["code" => "54111", "genero" => 1],
        ["code" => "54112", "genero" => 1],
        ["code" => "54115", "genero" => 1],
        ["code" => "54120", "genero" => 1],
        ["code" => "54125", "genero" => 1],
        ["code" => "54130", "genero" => 1],
        ["code" => "54135", "genero" => 1],
        ["code" => "54150", "genero" => 1],
        ["code" => "54152", "genero" => 1],
        ["code" => "54160", "genero" => 1],
        ["code" => "54161", "genero" => 1],
        ["code" => "54220", "genero" => 1],
        ["code" => "54300", "genero" => 1],
        ["code" => "54304", "genero" => 1],
        ["code" => "54308", "genero" => 1],
        ["code" => "54312", "genero" => 1],
        ["code" => "54316", "genero" => 1],
        ["code" => "54318", "genero" => 1],
        ["code" => "54360", "genero" => 1],
        ["code" => "54380", "genero" => 1],
        ["code" => "54385", "genero" => 1],
        ["code" => "54390", "genero" => 1],
        ["code" => "54450", "genero" => 1],
        ["code" => "54500", "genero" => 1],
        ["code" => "54505", "genero" => 1],
        ["code" => "54510", "genero" => 1],
        ["code" => "54520", "genero" => 1],
        ["code" => "54530", "genero" => 1],
        ["code" => "54535", "genero" => 1],
        ["code" => "54550", "genero" => 1],
        ["code" => "54560", "genero" => 1],
        ["code" => "54600", "genero" => 1],
        ["code" => "54640", "genero" => 1],
        ["code" => "54650", "genero" => 1],
        ["code" => "54670", "genero" => 1],
        ["code" => "54700", "genero" => 1],
        ["code" => "54800", "genero" => 1],
        ["code" => "54820", "genero" => 1],
        ["code" => "54830", "genero" => 1],
        ["code" => "54840", "genero" => 1],
        ["code" => "54860", "genero" => 1],
        ["code" => "54861", "genero" => 1],
        ["code" => "54900", "genero" => 1],
        ["code" => "54901", "genero" => 1],
        ["code" => "55040", "genero" => 1],
        ["code" => "55041", "genero" => 1],
        ["code" => "55060", "genero" => 1],
        ["code" => "55100", "genero" => 1],
        ["code" => "55110", "genero" => 1],
        ["code" => "55120", "genero" => 1],
        ["code" => "55150", "genero" => 1],
        ["code" => "55175", "genero" => 1],
        ["code" => "55180", "genero" => 1],
        ["code" => "55200", "genero" => 1],
        ["code" => "55250", "genero" => 1],
        ["code" => "55300", "genero" => 1],
        ["code" => "55400", "genero" => 1],
        ["code" => "55450", "genero" => 1],
        ["code" => "55500", "genero" => 1],
        ["code" => "55520", "genero" => 1],
        ["code" => "55530", "genero" => 1],
        ["code" => "55535", "genero" => 1],
        ["code" => "55540", "genero" => 1],
        ["code" => "55680", "genero" => 1],
        ["code" => "55700", "genero" => 1],
        ["code" => "55705", "genero" => 1],
        ["code" => "55720", "genero" => 1],
        ["code" => "55725", "genero" => 1],
        ["code" => "55801", "genero" => 1],
        ["code" => "55810", "genero" => 1],
        ["code" => "55812", "genero" => 1],
        ["code" => "55815", "genero" => 1],
        ["code" => "55821", "genero" => 1],
        ["code" => "55831", "genero" => 1],
        ["code" => "55840", "genero" => 1],
        ["code" => "55842", "genero" => 1],
        ["code" => "55845", "genero" => 1],
        ["code" => "55859", "genero" => 1],
        ["code" => "55860", "genero" => 1],
        ["code" => "55862", "genero" => 1],
        ["code" => "55865", "genero" => 1],
      ];
      $procedimientos = trama_procedimientos::where('id_trama_prestacion', $trama->id)->get();
      foreach ($procedimientos as $key => $procedimiento) {
        foreach ($proceduresForEvaluation as $procedure) {
          if ((string) $procedimiento->id_proced === $procedure["code"] && intval($genero) !== intval($procedure["genero"])) {
            $result['isOk'] = false;
            $result['message'] = 'Se han identificado procedimientos en los que el género del paciente no coincide con el género requerido para su ejecución.';
            $result['data'][] = ['Codígo Procedimiento' => (string) $procedimiento->id_proced];
          }
        }
      }
    } catch (Exception $e) {
      $result['isOk'] = false;
      $result['message'] = 'Error inesperado durante la validación de la regla 26.' . $e->getMessage();
    }
    return $result;
  }
}
