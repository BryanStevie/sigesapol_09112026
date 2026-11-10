<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;

use App\Models\IntraOperatorio;
use App\Models\CqxHojaRecuperacion;
use App\Models\CqxBalanceHidrico;
use App\Models\CqxBalanceHidricoDetalle;
use App\Models\CqxVital;
use App\Models\Farmacia;
use App\Models\Establecimiento;
use App\Models\Hospitalizacione;
use App\Models\CqxIntervencion;
use App\Models\CqxAtencione;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Archive7z\Archive7z;

class HojadeRecuperacionController extends Controller
{
  function __construct()
  {
      $this->middleware('auth'); 
  }
  
  public function registrar(Request $request)
    {
      if (!Auth::check()) {
        return redirect('/auth/login');
      }
      $modelfarma = new Farmacia();
      $model_establecimiento = new Establecimiento;

      $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
      $id_ipress = $farmacia->id_establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      $nivel = $id_ipress == 1 ? 2 : $establecimiento[0]->nivel;

      $data = $request->all();
      $intraoperatorio = $data['intraoperatorio'];
      $asegurado = $data['asegurado'];
      $model_hojarecuperacion = new CqxHojaRecuperacion();
      //DATOS ENCABEZADO
      $datos_paciente_intervencion= $model_hojarecuperacion->datos_paciente_intervencion($asegurado['id_intervencion']);
      $diagnostico_operatorio= $model_hojarecuperacion->diagnostico_operatorio($asegurado['id_intervencion']);
      $tipos_anestesia_aq= $model_hojarecuperacion->tipos_anestesia_aq($asegurado['id_intervencion']);
      //$cirujanos= $model_hojarecuperacion->profesionales_intraoperatorio(0,$intraoperatorio['id_intervencion']);
      $cirujanos= $model_hojarecuperacion->profesionales(0,$asegurado['id_intervencion']);
      // $anestesiologos= $model_hojarecuperacion->profesionales_intraoperatorio(1,$intraoperatorio['id_intervencion']);
      $anestesiologos= $model_hojarecuperacion->profesionales(1,$asegurado['id_intervencion']);
      $cama="";
      $model_atencione = new CqxAtencione();
      $ultimoRegistro = $model_atencione::where('id_cqx_intervencion',$asegurado['id_intervencion'])->first();
      if(!empty($ultimoRegistro) && $ultimoRegistro->id_prestacion){
        $cama = DB::table('cambio_camas as cc')
        ->join('camas as c', 'cc.id_cama', '=', 'c.id')
        ->select('c.nombre as cama')
        ->where('cc.id_prestacion', $ultimoRegistro->id_prestacion)
        ->orderBy('cc.id', 'desc')
        ->limit(1)
        ->first();
        
      }
      
      $diagnosticos = $model_hojarecuperacion->diagnosticos_pacientes_prestacion($asegurado['id_prestacion']);
      $procedimientos_intervencion = $model_hojarecuperacion->procedimientos_intervencion($asegurado['id_intervencion']);//PROCEDIMIENTOS DE INTERVENCION

      return view('frontend.centro_quirurgico.hoja_recuperacion.form_hoja_recuperacion', compact('cirujanos','anestesiologos','tipos_anestesia_aq','datos_paciente_intervencion','diagnostico_operatorio','intraoperatorio','diagnosticos','procedimientos_intervencion','asegurado','nivel','cama'));
      
    }
    public function guardar(Request $request)
    {
      if (!Auth::check()) {
          return redirect('/auth/login');
      }
      DB::beginTransaction();
      try {
        //dd($request->all());
        $rules = [];
        $messages = [];

        // Validaciones condicionales según los indicadores
        $rules['cama'] = ['required'];
        if ($request->input('tiene_tratamiento') === 'true') {
          $rules['descripcion_tratamiento'] = ['required'];
        }
        if ($request->input('tiene_analisis') === 'true') {
          $rules['descripcion_analisis'] = 'required';
          $rules['fecha_analisis'] = 'required';
          $rules['cvc_analisis'] = 'required';
          $rules['sf_analisis'] = 'required';
          $rules['la_analisis'] = 'required';
          $rules['lpp_analisis'] = 'required';
          $messages['descripcion_analisis.required'] = 'El campo "Descripcion" es obligatorio cuando tiene_analisis es verdadero.';
          $messages['sf_analisis.required'] = 'El campo "SF análisis" es obligatorio cuando tiene_analisis es verdadero.';
          $messages['la_analisis.required'] = 'El campo "LA análisis" es obligatorio cuando tiene_analisis es verdadero.';
          $messages['lpp_analisis.required'] = 'El campo "LPP análisis" es obligatorio cuando tiene_analisis es verdadero.';
        }
        if ($request->input('tiene_diagnostico_enfermeria') === 'true') {
          $rules['campo1_diagnostico_enfermeria'] = 'required';
          $rules['campo2_diagnostico_enfermeria'] = 'required';
          $rules['campo3_diagnostico_enfermeria'] = 'required';
        }
        
        if ($request->input('tiene_balance_hidrico') === 'true') {
            $rules['balance_hidrico_campo'] = 'required';
            $messages['balance_hidrico_campo.required'] = 'El campo "balance hídrico" es obligatorio cuando tiene_balance_hidrico es verdadero.';
        }
        // Validaciones de arrays
        $rules['nombre_procedimiento'] = 'required|array';
        $rules['procedimiento_cantidad'] = 'required|array';
        $rules['miSelect'] = 'required|array';

        // Validación de los elementos de cada array
        $nombre_procedimiento = $request->input('nombre_procedimiento', []);
        foreach ($nombre_procedimiento as $key => $value) {
            $rules["nombre_procedimiento.$key"] = 'required';
            $messages["nombre_procedimiento.$key.required"] = "El campo nombre procedimiento $key es obligatorio.";
        }

        $procedimiento_cantidad = $request->input('procedimiento_cantidad', []);
        foreach ($procedimiento_cantidad as $key => $value) {
            $rules["procedimiento_cantidad.$key"] = 'required';
            $messages["procedimiento_cantidad.$key.required"] = "El campo procedimiento cantidad $key es obligatorio.";
        }

        $miSelect = $request->input('miSelect', []);
        foreach ($miSelect as $key => $value) {
            $rules["miSelect.$key"] = 'required';
            $messages["miSelect.$key.required"] = "El campo miSelect $key es obligatorio.";
        }

        // Realizar la validación
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'errors' => $validator->errors(),
          ]);
        }

        $modeloHojaR = new CqxHojaRecuperacion();
        $id_hoja_recuperacion=$modeloHojaR->guardar($request->all(),Auth::user()->id);
        DB::commit(); 
        return response()->json(['success' => true,'id_hoja_recuperacion'=>$id_hoja_recuperacion]);
      } catch (\Exception $e) {
          DB::rollback();
          Log::error('Error al querer guardar datos de HOJA DE RECUPERACION', [
              'message' => $e->getMessage(),
              'code' => $e->getCode(),
              'file' => $e->getFile(),
              'line' => $e->getLine(),
              'trace' => $e->getTraceAsString(),
          ]);
      } catch (\Throwable $th) {
          DB::rollback();
          throw $th;
      }
    }
    public function existRegistroIntraoperatorioByIdItervencion($id_intervencion,$id_asegurado){
      if(empty($id_intervencion) && empty($id_asegurado)){
        return response()->json(['success' => false,'msg'=>'El paciente con id {$id_asegurado} no tiene intervencion quirurgica']);
      }
      // $intraoperatorios = IntraOperatorio::where('id_asegurado', $id_asegurado)
      // ->where('id_intervencion', $id_intervencion)
      // ->whereNotNull('salida_paciente')
      // ->first();
      // if(empty($intraoperatorios)){
      //   return response()->json(['success' => false,'msg'=>'El paciente no fue registrado en Intraoperatorio']);
      // }
      $hoja_recuperacion = CqxHojaRecuperacion::where('id_asegurado', $id_asegurado)
      ->where('id_intervencion', $id_intervencion)
      ->first();
      if(!empty($hoja_recuperacion)){
        return response()->json(['success' => true,'id_hoja_recuperacion' => $hoja_recuperacion->id,'flag_firma' => $hoja_recuperacion->flag_firma,'hoja_recuperacion'=>true]);
      }else{
        return response()->json(['success' => true,'data' => $id_intervencion,'hoja_recuperacion'=>false]);
      }
    }
    function detalleHojaRecuperacion($id_hoja_recuperacion){
      $model_hoja = new CqxHojaRecuperacion();
      if(empty($id_hoja_recuperacion)){
        return response()->json([
          'success' => false,
          'msg' => 'El ID de la hoja de recuperación no se ha proporcionado o está vacío. Por favor, verifica y vuelve a intentarlo.'
        ]);
      }
      $hoja_recuperacion = $model_hoja::find($id_hoja_recuperacion);
      if (empty($hoja_recuperacion)) {
        return response()->json([
            'success' => false,
            'data' =>'No se encontró la hoja de recuperación con el ID especificado. '
        ]);
      }
      $datos_paciente_intervencion= $model_hoja->datos_paciente_intervencion($hoja_recuperacion->id_intervencion);
      $diagnostico_operatorio= $model_hoja->diagnostico_operatorio($hoja_recuperacion->id_intervencion);
      $tipos_anestesia_aq= $model_hoja->tipos_anestesia_aq($hoja_recuperacion->id_intervencion);
      $cirujanos= $model_hoja->profesionales_intraoperatorio(0,$hoja_recuperacion->id_intervencion);
      $anestesiologos= $model_hoja->profesionales_intraoperatorio(1,$hoja_recuperacion->id_intervencion);
      $encabezado_fv=$this->datosCaracterFisicoVitales();
      $funciones_vitales=$model_hoja->funciones_vitales_by_hr($id_hoja_recuperacion);
      $ingresos=$this->datosIngresoHojaBalance($hoja_recuperacion->codigo_fecha_24,$hoja_recuperacion->id_asegurado);
      $egresos=$this->datosEgresoHojaBalance($hoja_recuperacion->codigo_fecha_24,$hoja_recuperacion->id_asegurado);
      return view('frontend.centro_quirurgico.hoja_recuperacion.detalle_hoja_recuperacion', compact('cirujanos','anestesiologos','tipos_anestesia_aq','diagnostico_operatorio','datos_paciente_intervencion','hoja_recuperacion','encabezado_fv','funciones_vitales','ingresos','egresos'));
    }

    function editarHojaRecuperacion($id_hoja_recuperacion){
      $model_hoja = new CqxHojaRecuperacion();
      if(empty($id_hoja_recuperacion)){
        return response()->json([
          'success' => false,
          'msg' => 'El ID de la hoja de recuperación no se ha proporcionado o está vacío. Por favor, verifica y vuelve a intentarlo.'
        ]);
      }
      
      $hoja_recuperacion = $model_hoja::find($id_hoja_recuperacion);
      if (empty($hoja_recuperacion)) {
        return response()->json([
            'success' => false,
            'data' =>'No se encontró la hoja de recuperación con el ID especificado. '
        ]);
      }

      $intraoperatorio = IntraOperatorio::select('id')->where('id_asegurado', $hoja_recuperacion->id_asegurado)
      ->where('id_intervencion', $hoja_recuperacion->id_intervencion)
      ->first();
      if(empty($intraoperatorio)){
        return response()->json(['success' => false,'msg'=>'El paciente no fue registrado en Intraoperatorio']);
      }

      $modelfarma = new Farmacia();
      $model_establecimiento = new Establecimiento;

      $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
      $id_ipress = $farmacia->id_establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      $nivel = $id_ipress == 1 ? 2 : $establecimiento[0]->nivel;

      $model_intervencion = new CqxIntervencion();
      $datos_intervencion = $model_intervencion::select(
      'cqx_intervenciones.id',
      'cqx_intervenciones.id_prestacion',
      'cqx_intervenciones.id_asegurado')
      ->where('cqx_intervenciones.id',$hoja_recuperacion->id_intervencion)->first();

      $model_hojarecuperacion = new CqxHojaRecuperacion();

      $datos_paciente_intervencion= $model_hojarecuperacion->datos_paciente_intervencion($hoja_recuperacion->id_intervencion);
      $diagnostico_operatorio= $model_hojarecuperacion->diagnostico_operatorio($hoja_recuperacion->id_intervencion);
      $tipos_anestesia_aq= $model_hojarecuperacion->tipos_anestesia_aq($hoja_recuperacion->id_intervencion);
      $cirujanos= $model_hojarecuperacion->profesionales_intraoperatorio(0,$hoja_recuperacion->id_intervencion);
      $anestesiologos= $model_hojarecuperacion->profesionales_intraoperatorio(1,$hoja_recuperacion->id_intervencion);
      $diagnosticos = $model_hojarecuperacion->diagnosticos_pacientes_prestacion($datos_intervencion->id_prestacion);
      $procedimientos_intervencion = $model_hojarecuperacion->procedimientos_intervencion($hoja_recuperacion->id_intervencion);//PROCEDIMIENTOS DE INTERVENCION
      $procedimientos_hoja_recuperacion = $model_hojarecuperacion->procedimientos_hoja_recuperacion($hoja_recuperacion->id);//PROCEDIMIENTOS DE HOJA DE RECUPERACION
      
      return view('frontend.centro_quirurgico.hoja_recuperacion.editar_hoja_recuperacion', compact('cirujanos','anestesiologos','tipos_anestesia_aq','datos_paciente_intervencion','diagnostico_operatorio','intraoperatorio','diagnosticos','procedimientos_intervencion','asegurado','nivel','datos_intervencion','hoja_recuperacion','procedimientos_hoja_recuperacion'));
    }

    public function registrarBalanceHidrico(Request $request){
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
          $valor_response=0;
          $data = $request->all();
          try{
              DB::beginTransaction();
              $dia = date("j");  // Día sin ceros a la izquierda (1 a 31)
              $mes = date("n");  // Mes sin ceros a la izquierda (1 a 12)
              $año = date("Y");  // Año con cuatro dígitos (e.g., 2024)
              // Concatenar los componentes para formar el código
              $codigo_fecha_24 = $dia.$mes.$año;
              $exists = CqxBalanceHidrico::where('codigo_fecha_24', $codigo_fecha_24)->where('id_turno',$request->turno)->where('id_asegurado',$request->id_asegurado)->exists();
              if($exists){
                return json_encode(array('existe_balance'=>true)); 
              }else{

                $hojaRecuperacion = CqxHojaRecuperacion::find($request->id_hoja_recuperacion);
                $hojaRecuperacion->codigo_fecha_24 = $codigo_fecha_24;
                $hojaRecuperacion->save();

                $objBalance_hidrico = new CqxBalanceHidrico();
                $objBalance_hidrico->created_us = Auth::user()->id;
                $objBalance_hidrico->id_hoja_recuperacion = $request->id_hoja_recuperacion;
                $objBalance_hidrico->id_asegurado = $request->id_asegurado;
                $objBalance_hidrico->id_turno = $request->turno;
                $objBalance_hidrico->fecha_registro = Carbon::now()->toDateString(); 
                $objBalance_hidrico->hora_registro = Carbon::now()->toTimeString(); 
                $objBalance_hidrico->created_at = Carbon::now();
                $objBalance_hidrico->estado = 1;
                $objBalance_hidrico->codigo_fecha_24 = $codigo_fecha_24; // Código único por día para agrupar turnos
                $objBalance_hidrico->save(); 
                $id_balance_hidrico = $objBalance_hidrico->id;

                if(!empty($request->id_caracter_fisico_ingreso)){
                  foreach ($request->id_caracter_fisico_ingreso as $key => $value) {
                    $detalle = new CqxBalanceHidricoDetalle();
                    if (isset($data['descripcion_caracter_fisico_id_ingreso'][$key]) && !empty($data['descripcion_caracter_fisico_id_ingreso'][$key])) {
                      $codigo_agrupacion = $data['descripcion_caracter_fisico_id_ingreso'][$key];
                      $detalle->descripcion=$data['descripcion_caracter_fisico_ingreso'][$key];
                      $detalle->codigo_agrupacion=$codigo_agrupacion;
                    } else {
                      if ($data['id_caracter_fisico_ingreso'][$key] <= 9) {
                        $codigo_agrupacion = $data['id_caracter_fisico_ingreso'][$key].'00';
                      }else if ($data['id_caracter_fisico_ingreso'][$key] >= 10 && $data['id_caracter_fisico_ingreso'][$key] <= 99) {
                        $codigo_agrupacion = $data['id_caracter_fisico_ingreso'][$key].'0';
                      }
                      $detalle->descripcion=$data['descripcion_ingreso'][$key];
                      $detalle->codigo_agrupacion=$codigo_agrupacion;
                    }
                   
                    $detalle->secuencia=$data['secuencia'][$key];
                    $detalle->id_turno = $request->turno;
                    $detalle->codigo_fecha_24=$codigo_fecha_24;//Este codigo es unico por dia, para agrupar los turnos
                    $detalle->id_caracter_fisico=$data['id_caracter_fisico_ingreso'][$key]?$data['id_caracter_fisico_ingreso'][$key]:null;
                    if(!empty($data['valor_tabla_ingreso'][$key])){
                      $detalle->id_valor=$data['valor_tabla_ingreso'][$key];
                      $descripcion = DB::table('intervencion_terapeuticas')
                      ->where('id',$data['valor_tabla_ingreso'][$key])
                      ->value('descripcion_intervencion_t');
                      $detalle->descripcion=$descripcion;
                      $detalle->codigo_agrupacion=$data['valor_tabla_ingreso'][$key];
                    }else{
                      $detalle->id_valor==null;
                    }
                    $detalle->es_visible=$data['es_visible'][$key];
                    $detalle->valor_caracter_fisico=$data['valor_ingreso'][$key];
                    $detalle->tipo_balance=1;//tipo_balance: 1=ingreso
                    $detalle->estado=1;
                    $detalle->peso=$request->peso_kg;
                    $detalle->id_asegurado = $request->id_asegurado;
                    $detalle->id_balance_hidrico=$id_balance_hidrico;
                    $detalle->save();
                  }
                  
                }
                if(!empty($request->id_caracter_fisico_egreso)){
                  foreach ($request->id_caracter_fisico_egreso as $key => $value) {
                    $detalle = new CqxBalanceHidricoDetalle();
                    if (isset($data['descripcion_caracter_fisico_id_egreso'][$key]) && !empty($data['descripcion_caracter_fisico_id_egreso'][$key])) {
                      $codigo_agrupacion = $data['descripcion_caracter_fisico_id_egreso'][$key];
                      $detalle->descripcion=$data['descripcion_caracter_fisico_egreso'][$key];
                    } else {
                      if ($data['id_caracter_fisico_egreso'][$key] <= 9) {
                        $codigo_agrupacion = $data['id_caracter_fisico_egreso'][$key].'00';
                      }else if ($data['id_caracter_fisico_egreso'][$key] >= 10 && $data['id_caracter_fisico_egreso'][$key] <= 99) {
                        $codigo_agrupacion = $data['id_caracter_fisico_egreso'][$key].'0';
                      }
                      $detalle->descripcion=$data['descripcion_egreso'][$key];
                    }
                    $detalle->codigo_agrupacion=$codigo_agrupacion;
                    $detalle->secuencia=$key+1;
                    $detalle->id_turno = $request->turno;
                    $detalle->codigo_fecha_24=$codigo_fecha_24;//Este codigo es unico por dia, para agrupar los turnos
                    $detalle->id_caracter_fisico=$data['id_caracter_fisico_egreso'][$key]?$data['id_caracter_fisico_egreso'][$key]:null;
                    $detalle->id_valor=$data['valor_tabla_egreso'][$key]?$data['valor_tabla_egreso'][$key]:null;
                    $detalle->valor_caracter_fisico=$data['valor_egreso'][$key];
                    $detalle->tipo_balance=2;//tipo_balance: 2=egreso
                    $detalle->es_visible=1;
                    $detalle->estado=1;
                    $detalle->peso=$request->peso_kg;
                    $detalle->id_asegurado = $request->id_asegurado;
                    $detalle->id_balance_hidrico=$id_balance_hidrico;
                    $detalle->save();
                  }
                }
                DB::commit();
              }
              $valor_response= $request->id_hoja_recuperacion;
          }catch(\Exception $e) { 
            Log::error('ERROR EN HOJA DE RECUPERACION AL GUARDAR BALANCE HIDRICO: ' . $e->getMessage(), [
              'exception' => $e,
              'trace' => $e->getTraceAsString(),
            ]);
              DB::rollback();
          }
          return json_encode(array('id_hoja_recuperacion'=>$valor_response)); 
    }

    public function datosGlobales(Request $request){
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif; 
      // $data['tipo_atenciones']= $this->tipoAtenciones();
      $data['turnos']= $this->turnos();
      $data['datos_paciente']= $this->getDatosPaciente($request->id_asegurado);
      
      $data['intervencion_terapeuticas']=$this->intervencionTerapeuticas();
      // if(!empty($request->id_balance)){
      //   $data['caracter_fisico']=$this->balanceHidrico($request->id_balance);
      // }else{
      $data['caracter_fisico']=$this->caracterFisico();//solo de balance hidrico
      //}
      $data['caracter_fisico_vitales']=$this->caracterFisicoVitales();
     
      return json_encode($data);   
    }
    public function caracterFisico(){
      $registros = DB::table('caracter_fisicos')
      ->where('flag_balance_hidrico', 1)
      ->where('estado',1)
      ->orderBy('secuencia')
      ->get();
      return $registros;
    }
    public function intervencionTerapeuticas(){
      $registros = DB::table('intervencion_terapeuticas')
      ->orderBy('secuencia')
      ->get();
      return $registros;
    }

    public function turnos(){
      $query = DB::table('turnos')
      ->select('turnos.*');
      $datos = $query->get();
      return $datos;
    }
    public function getDatosPaciente($id_asegurado){
      $data = DB::table('asegurados')
      ->join('asegurado_historias', 'asegurado_historias.id_asegurado', '=', 'asegurados.id')
      ->leftJoin('tipo_beneficiarios as tb', 'tb.id', '=', 'asegurados.id_tipo_beneficiario')
      ->leftJoin('tipo_sangres as ts', 'asegurados.id_tipo_sangre', '=', 'ts.id')
      ->select(
          'asegurados.fecha_nac as fecha_nacimiento',
          'ts.descripcion_corta as tipo_sangre',
          'tb.descripcion as parentesgo',
          DB::raw("to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"A\"') as edad_corta"),
          DB::raw("to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"') as fec_nacim_descrip"),
          DB::raw("LTRIM(asegurado_historias.nro_historia) as nro_historia"),
          'asegurados.nro_doc_ident',
          'asegurados.paterno',
          'asegurados.materno',
          'asegurados.nombre',
          'asegurados.sexo'
      )
      ->where('asegurados.id_estado_reg', 1)
      ->where('asegurados.id', $id_asegurado)
      ->first();
      return $data;
    }

    public function listarBalanceHidricoByHojaRecuperacion($id_hoja_recuperacion){
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif; 
      $query = DB::table('cqx_balance_hidricos as bh')
        ->leftJoin('cqx_balance_hidrico_detalles as bhd', 'bh.id', '=', 'bhd.id_balance_hidrico')
        ->leftJoin('users as u', 'bh.created_us', '=', 'u.id')
        ->leftJoin('turnos as t', 'bh.id_turno', '=', 't.id')
        ->select(
            'bh.id',
            'bh.codigo_fecha_24',
            'bh.id_turno',
            'u.name as nombre',
            'u.paterno',
            'u.materno',
            't.abreviatura as turno',
            DB::raw('TO_CHAR(bh.fecha_registro, \'DD/MM/YYYY\') AS fecha_registro'),
            DB::raw('
                LPAD(EXTRACT(HOUR FROM bh.hora_registro)::text, 2, \'0\') || \':\' ||
                LPAD(EXTRACT(MINUTE FROM bh.hora_registro)::text, 2, \'0\') || \':\' ||
                LPAD(EXTRACT(SECOND FROM bh.hora_registro)::text, 2, \'0\') AS hora_registro'
            ),
            DB::raw('SUM(CASE WHEN bhd.tipo_balance = 1 AND es_visible != 0 THEN bhd.valor_caracter_fisico ELSE 0 END) - 
                    SUM(CASE WHEN bhd.tipo_balance = 2 AND es_visible != 0 THEN bhd.valor_caracter_fisico ELSE 0 END) AS diferencia')
        )
        ->leftJoin(DB::raw("(
            SELECT bh.codigo_fecha_24,
                  SUM(CASE WHEN bhd.tipo_balance = 1 AND es_visible != 0 THEN bhd.valor_caracter_fisico ELSE 0 END) - 
                  SUM(CASE WHEN bhd.tipo_balance = 2 AND es_visible != 0 THEN bhd.valor_caracter_fisico ELSE 0 END) AS bh24
            FROM cqx_balance_hidricos AS bh
            LEFT JOIN cqx_balance_hidrico_detalles AS bhd ON bh.id = bhd.id_balance_hidrico
            GROUP BY bh.codigo_fecha_24
        ) AS subquery"), 'bh.codigo_fecha_24', '=', 'subquery.codigo_fecha_24')
        ->addSelect('subquery.bh24')
        ->where('bh.id_hoja_recuperacion', '=', $id_hoja_recuperacion)
        ->groupBy(
            'bh.id',
            'bh.codigo_fecha_24',
            'u.name',
            'u.paterno',
            'u.materno',
            't.abreviatura',
            'bh.fecha_registro',
            'bh.hora_registro',
            'subquery.bh24',
            'bh.id_turno'
        );

      $results = $query->get();
      return response()->json(['results' => $results]); 
      
    }

    public function pdf_balance_hidrico($id_balance){
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif; 
      
          $date = Carbon::now();
          $fechaActual = $date->format('d/m/Y');
          $horaActual = $date->format('H:i:s');  
          //--- ESTABLECIMIENTO
          $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
          $model_establecimiento = new \App\Models\Establecimiento();
          $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
          $nombre_establecimiento = $establecimiento[0]->nombre;
  
          $balanceModelo = new CqxBalanceHidrico; 
          $balance_cabecera = $balanceModelo::leftJoin('cqx_hoja_recuperaciones', 'cqx_balance_hidricos.id_hoja_recuperacion', '=', 'cqx_hoja_recuperaciones.id')
                              ->leftJoin('users', 'cqx_balance_hidricos.created_us', '=', 'users.id')
                              ->select('users.*','cqx_balance_hidricos.*') 
                              ->where('cqx_balance_hidricos.id', $id_balance)
                              ->first();
          $datos_paciente = $this->getDatosPaciente($balance_cabecera->id_asegurado);
          $id_hoja_recuperacion=$balance_cabecera->id_hoja_recuperacion;
          $ingresos=$this->datosIngresoHojaBalance($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado);
          $egresos=$this->datosEgresoHojaBalance($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado);
        
         

          $pdf = \PDF::loadView('frontend.centro_quirurgico.hoja_recuperacion.pdf_balance_hidrico', 
          compact(
              'id_hoja_recuperacion',
              'datos_paciente',
              'balance_cabecera',
              'ingresos',
              'egresos',
              'nombre_establecimiento',
              'fechaActual',
              'horaActual' 
          ));
          $pdf->getDomPDF()->set_option("enable_php", true);
          $pdf->setPaper('A4', 'portrait');
      return $pdf->stream('balance_hidrico.pdf'); 
   
    }
    public function pdf_balance_hidrico_turno($id_balance){
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif; 
      
          $date = Carbon::now();
          $fechaActual = $date->format('d/m/Y');
          $horaActual = $date->format('H:i:s');  
          //--- ESTABLECIMIENTO
          $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
          $model_establecimiento = new \App\Models\Establecimiento();
          $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
          $nombre_establecimiento = $establecimiento[0]->nombre;
  
          $balanceModelo = new CqxBalanceHidrico; 
          $balance_cabecera = $balanceModelo::leftJoin('cqx_hoja_recuperaciones', 'cqx_balance_hidricos.id_hoja_recuperacion', '=', 'cqx_hoja_recuperaciones.id')
                              ->leftJoin('users', 'cqx_balance_hidricos.created_us', '=', 'users.id')
                              ->leftJoin('turnos', 'cqx_balance_hidricos.id_turno', '=', 'turnos.id')
                              ->select('turnos.abreviatura as turno','users.*','cqx_balance_hidricos.*') 
                              ->where('cqx_balance_hidricos.id', $id_balance)
                              ->first();
                           
          $datos_paciente = $this->getDatosPaciente($balance_cabecera->id_asegurado);
          $id_hoja_recuperacion=$balance_cabecera->id_hoja_recuperacion;
          $turno=$balance_cabecera->turno;
          $ingresos=$this->datosIngresoHojaBalancePorTurno($id_balance);
          $egresos=$this->datosEgresoHojaBalancePorTurno($id_balance);

          $pdf = \PDF::loadView('frontend.centro_quirurgico.hoja_recuperacion.pdf_balance_hidrico_turno', 
          compact(
              'turno',
              'id_hoja_recuperacion',
              'datos_paciente',
              'balance_cabecera',
              'ingresos',
              'egresos',
              'nombre_establecimiento',
              'fechaActual',
              'horaActual' 
          ));
          $pdf->getDomPDF()->set_option("enable_php", true);
          $pdf->setPaper('A4', 'portrait');
      return $pdf->stream('balance_hidrico.pdf'); 
   
    }

      /***DE TODOS LOS TURNOS */
    function datosIngresoHojaBalance($codigo_fecha_24,$id_asegurado){
      $ingresos = DB::table('cqx_balance_hidricos')
      ->join('cqx_balance_hidrico_detalles', 'cqx_balance_hidricos.id', '=', 'cqx_balance_hidrico_detalles.id_balance_hidrico')
      ->select(
          'cqx_balance_hidrico_detalles.codigo_agrupacion','cqx_balance_hidrico_detalles.descripcion as caracter_fisico',
          DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 1 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
          DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 2 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
          DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 3 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
          DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 4 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
          DB::raw('SUM(cqx_balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
      )
      ->where('cqx_balance_hidrico_detalles.estado', 1)
      ->where('cqx_balance_hidrico_detalles.es_visible', 1)
      ->where('cqx_balance_hidrico_detalles.tipo_balance', 1)
      ->where('cqx_balance_hidricos.codigo_fecha_24', $codigo_fecha_24)
      ->where('cqx_balance_hidricos.id_asegurado', $id_asegurado)
      ->groupBy('cqx_balance_hidrico_detalles.codigo_agrupacion','cqx_balance_hidrico_detalles.descripcion','cqx_balance_hidrico_detalles.secuencia')
      ->orderBy('cqx_balance_hidrico_detalles.secuencia')
      ->get();
      return $ingresos;
    }
    
    function datosEgresoHojaBalance($codigo_fecha_24,$id_asegurado){
      $egresos = DB::table('cqx_balance_hidricos')
      ->join('cqx_balance_hidrico_detalles', 'cqx_balance_hidricos.id', '=', 'cqx_balance_hidrico_detalles.id_balance_hidrico')
      ->select(
          'cqx_balance_hidrico_detalles.codigo_agrupacion','cqx_balance_hidrico_detalles.descripcion as caracter_fisico',
          DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 1 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
          DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 2 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
          DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 3 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
          DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 4 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
          DB::raw('SUM(cqx_balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
      )
      ->where('cqx_balance_hidrico_detalles.estado', 1)
      ->where('cqx_balance_hidrico_detalles.tipo_balance', 2)
      ->where('cqx_balance_hidricos.codigo_fecha_24', $codigo_fecha_24)
      ->where('cqx_balance_hidricos.id_asegurado', $id_asegurado)
      ->groupBy('cqx_balance_hidrico_detalles.codigo_agrupacion','cqx_balance_hidrico_detalles.descripcion','cqx_balance_hidrico_detalles.secuencia')
      ->orderBy('cqx_balance_hidrico_detalles.secuencia')
      ->get();
      return $egresos;
    }
    /***FIN DE TODOS LOS TURNOS ***/

     /*****POR UN SOLO TURNO ***/
  function datosIngresoHojaBalancePorTurno($id_balance_hidrico){
    $ingresos = DB::table('cqx_balance_hidricos')
    ->join('cqx_balance_hidrico_detalles', 'cqx_balance_hidricos.id', '=', 'cqx_balance_hidrico_detalles.id_balance_hidrico')
    ->select(
        'cqx_balance_hidrico_detalles.id','cqx_balance_hidrico_detalles.descripcion as caracter_fisico',
        DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 1 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
        DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 2 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
        DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 3 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
        DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 4 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
        DB::raw('SUM(cqx_balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
    )
    ->where('cqx_balance_hidrico_detalles.estado', 1)
    ->where('cqx_balance_hidrico_detalles.es_visible', 1)
    ->where('cqx_balance_hidrico_detalles.tipo_balance', 1)
    ->where('cqx_balance_hidricos.id', $id_balance_hidrico)
    ->groupBy('cqx_balance_hidrico_detalles.id','cqx_balance_hidrico_detalles.descripcion','cqx_balance_hidrico_detalles.secuencia')
    ->orderBy('cqx_balance_hidrico_detalles.secuencia')
    ->get();
    return $ingresos;
  }
  function datosEgresoHojaBalancePorTurno($id_balance_hidrico){
    $egresos = DB::table('cqx_balance_hidricos')
    ->join('cqx_balance_hidrico_detalles', 'cqx_balance_hidricos.id', '=', 'cqx_balance_hidrico_detalles.id_balance_hidrico')
    ->select(
        'cqx_balance_hidrico_detalles.id','cqx_balance_hidrico_detalles.descripcion as caracter_fisico',
        DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 1 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
        DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 2 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
        DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 3 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
        DB::raw('SUM(CASE WHEN cqx_balance_hidrico_detalles.id_turno = 4 THEN cqx_balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
        DB::raw('SUM(cqx_balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
    )
    ->where('cqx_balance_hidrico_detalles.estado', 1)
    ->where('cqx_balance_hidrico_detalles.tipo_balance', 2)
    ->where('cqx_balance_hidricos.id', $id_balance_hidrico)
    ->groupBy('cqx_balance_hidrico_detalles.id','cqx_balance_hidrico_detalles.descripcion','cqx_balance_hidrico_detalles.secuencia')
    ->orderBy('cqx_balance_hidrico_detalles.secuencia')
    ->get();
    return $egresos;
  }
  /*****FIN POR UN SOLO TURNO ***/


  /***********FUNCIONES VITALES***********/
  public function caracterFisicoVitales(){
    
    return response()->json(['results' =>$this->datosCaracterFisicoVitales()]); 
  }
  public function datosCaracterFisicoVitales(){
    $registros = DB::table('caracter_fisicos')
    ->where('flag_qx_hoja_rec_v', 1)
    ->where('estado',1)
    ->select('id','descripcion_corta')
    ->orderBy('secuencia')
    ->get();
    return $registros;
  }

  /*******REGISTRAR FUNCIONE SVITALES*******/
  function registrarFuncionesVitales(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
        $valor_response=[];
        $data = $request->all();
        try{
            DB::beginTransaction();
            $rules = [];
            $messages = [];
           // $caracter_fisico[] = $request->input('caracter_fisico', []);
            foreach ($data['caracter_fisico'] as $key => $value) {
                $rules["caracter_fisico.$key"] = 'required';
                $messages["caracter_fisico.$key.required"] = "El campo caracter_fisico $key es obligatorio.";
            }
            // Realizar la validación
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ]);
            }

            $maxCodigoAgrupacion = CqxVital::whereNotNull('codigo_agrupacion')->max('codigo_agrupacion');
            $nuevo_codigo_agrupacion = $maxCodigoAgrupacion ? $maxCodigoAgrupacion + 1 : 1;
            
            $model = new CqxHojaRecuperacion();
            if(isset($data['caracter_fisico'])) {
              foreach ($data['caracter_fisico_id'] as $key => $valor) {
                $vital = new CqxVital();
                $vital->id_hoja_recuperacion = $request->id_hoja_recuperacion;
                $vital->id_caracter_fisico = $valor; 
                if ($key==0) {
                  $vital->valor_caracter_fisico =null;
                  $vital->hora_registro = $data['caracter_fisico'][$key];
                }else if (isset($data['caracter_fisico'][$key]) ) {
                  $vital->valor_caracter_fisico = $data['caracter_fisico'][$key];
                }
                $vital->secuencia = $key+1;
                $vital->fecha_registro = Carbon::now()->toDateString();
                $vital->estado = 1;  
                $vital->created_us = Auth::user()->id;
                $vital->created_at = Carbon::now()->toDateString(); 
                $vital->codigo_agrupacion=$nuevo_codigo_agrupacion;
                $vital->save(); 
              }
            }
            $lista = $model->funciones_vitales_by_hr($request->id_hoja_recuperacion);
            DB::commit();
            $valor_response = $lista;
            return response()->json(['success' => true,'encabezado'=>$this->datosCaracterFisicoVitales(),'lista' => $valor_response]); 
        }catch(\Exception $e) { 
          Log::error('ERROR EN HOJA DE RECUPERACION AL GUARDAR FUNCIONES VITALES: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString(),
          ]);
            DB::rollback();
        }
  }

  public function listarFuncionesVitalesByHojaRecuperacion($id_hoja_recuperacion){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif; 
    $query = DB::table('cqx_balance_hidricos as bh')
      ->leftJoin('cqx_balance_hidrico_detalles as bhd', 'bh.id', '=', 'bhd.id_balance_hidrico')
      ->leftJoin('users as u', 'bh.created_us', '=', 'u.id')
      ->leftJoin('turnos as t', 'bh.id_turno', '=', 't.id')
      ->select(
          'bh.id',
          'bh.codigo_fecha_24',
          'bh.id_turno',
          'u.name as nombre',
          'u.paterno',
          'u.materno',
          't.abreviatura as turno',
          DB::raw('TO_CHAR(bh.fecha_registro, \'DD/MM/YYYY\') AS fecha_registro'),
          DB::raw('
              LPAD(EXTRACT(HOUR FROM bh.hora_registro)::text, 2, \'0\') || \':\' ||
              LPAD(EXTRACT(MINUTE FROM bh.hora_registro)::text, 2, \'0\') || \':\' ||
              LPAD(EXTRACT(SECOND FROM bh.hora_registro)::text, 2, \'0\') AS hora_registro'
          ),
          DB::raw('SUM(CASE WHEN bhd.tipo_balance = 1 AND es_visible != 0 THEN bhd.valor_caracter_fisico ELSE 0 END) - 
                  SUM(CASE WHEN bhd.tipo_balance = 2 AND es_visible != 0 THEN bhd.valor_caracter_fisico ELSE 0 END) AS diferencia')
      )
      ->leftJoin(DB::raw("(
          SELECT bh.codigo_fecha_24,
                SUM(CASE WHEN bhd.tipo_balance = 1 AND es_visible != 0 THEN bhd.valor_caracter_fisico ELSE 0 END) - 
                SUM(CASE WHEN bhd.tipo_balance = 2 AND es_visible != 0 THEN bhd.valor_caracter_fisico ELSE 0 END) AS bh24
          FROM cqx_balance_hidricos AS bh
          LEFT JOIN cqx_balance_hidrico_detalles AS bhd ON bh.id = bhd.id_balance_hidrico
          GROUP BY bh.codigo_fecha_24
      ) AS subquery"), 'bh.codigo_fecha_24', '=', 'subquery.codigo_fecha_24')
      ->addSelect('subquery.bh24')
      ->where('bh.id_hoja_recuperacion', '=', $id_hoja_recuperacion)
      ->groupBy(
          'bh.id',
          'bh.codigo_fecha_24',
          'u.name',
          'u.paterno',
          'u.materno',
          't.abreviatura',
          'bh.fecha_registro',
          'bh.hora_registro',
          'subquery.bh24',
          'bh.id_turno'
      );

    $results = $query->get();
    return response()->json(['results' => $results]); 
    
  }
  public function pdfGeneral($id_recuperacion){
    $date = Carbon::now();
    $model_hoja = new CqxHojaRecuperacion();
    $fechaActual = $date->format('d/m/Y');
    $horaActual = $date->format('H:i:s');  
    //--- ESTABLECIMIENTO
    $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
    $model_establecimiento = new \App\Models\Establecimiento();
    $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
    $nombre_establecimiento = $establecimiento[0]->nombre;
    
    $hoja_recuperacion = CqxHojaRecuperacion::find($id_recuperacion);
    $codigo_cups="";
    $hopitalizacion = Hospitalizacione::select('hospitalizaciones.id', 'hospitalizaciones.cups_pnp')
    ->join('cqx_intervenciones', 'hospitalizaciones.id', '=', 'cqx_intervenciones.id_hospitalizacion')
    ->where('cqx_intervenciones.id',  $hoja_recuperacion->id_intervencion)
    ->first();
    if(!empty($hopitalizacion->cups_pnp)){
      $codigo_cups=$hopitalizacion->cups_pnp;
    }
    $datos_paciente_intervencion= $model_hoja->datos_paciente_intervencion($hoja_recuperacion->id_intervencion);
    $diagnostico_operatorio= $model_hoja->diagnostico_operatorio($hoja_recuperacion->id_intervencion);
    $tipos_anestesia_aq= $model_hoja->tipos_anestesia_aq($hoja_recuperacion->id_intervencion);
    $cirujanos= $model_hoja->profesionales_intraoperatorio(0,$hoja_recuperacion->id_intervencion);
    $anestesiologos= $model_hoja->profesionales_intraoperatorio(1,$hoja_recuperacion->id_intervencion);
    $encabezado_fv=$this->datosCaracterFisicoVitales();
    $funciones_vitales=$hoja_recuperacion->funciones_vitales_by_hr($id_recuperacion);
    $ingresos=$this->datosIngresoHojaBalance($hoja_recuperacion->codigo_fecha_24,$hoja_recuperacion->id_asegurado);
    
    $egresos=$this->datosEgresoHojaBalance($hoja_recuperacion->codigo_fecha_24,$hoja_recuperacion->id_asegurado);

    $pdf = \PDF::loadView('frontend.centro_quirurgico.hoja_recuperacion.pdf_hoja_recuperacion', 
    compact(
      'datos_paciente_intervencion',
      'diagnostico_operatorio',
      'tipos_anestesia_aq',
      'cirujanos',
      'anestesiologos',
      'hoja_recuperacion',
      'encabezado_fv',
      'funciones_vitales',
      'nombre_establecimiento',
      'ingresos',
      'egresos',
      'fechaActual',
      'horaActual',
      'codigo_cups'
    ));
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait');
    return $pdf->stream('hoja_recuperacion.pdf');  
  }

    /******************FIRMA*****************/
    public function hojarecuperacionPDFfirmar($id_hoja_recuperacion){
      $resultado=array();
      try{
        $path = public_path('tmp'.DIRECTORY_SEPARATOR.'csv');
        $path_zip = public_path('tmp'.DIRECTORY_SEPARATOR.'zip');
      
        if (!is_dir($path)) {
            mkdir($path);
        }
        if (!is_dir($path_zip)) {
            mkdir($path_zip);
            mkdir(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'firmados'));
            mkdir(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'sin_firma'));
        } 
        $csv = "";
        if (Auth::check()):
            $csv .= Auth::user()->id;
        endif;
  
        $csv .= "_".date("YmdHis");
        $csv.= "_hoja_recuperacion";
        Excel::create($csv, function($excel) use($id_hoja_recuperacion){
            $excel->sheet('GENERAL', function($sheet) use($id_hoja_recuperacion) {
                $variable = [];
                $this->generarPdfParaFirma($id_hoja_recuperacion);
                array_push($variable, array(url().DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'hoja_recuperacion'.$id_hoja_recuperacion.'.pdf', url().DIRECTORY_SEPARATOR.'upload_firma'.DIRECTORY_SEPARATOR.'?name=cf_hoja_recuperacion_'.$id_hoja_recuperacion.'.pdf&id_tipo=hoja_recuperacion&id_resol='.$id_hoja_recuperacion, 'cf_hoja_recuperacion_'.$id_hoja_recuperacion.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'escudo.jpg','1','7'));                             
                $sheet->fromArray($variable, null, '', false, false);
            });
          })->store('csv',public_path('tmp'.DIRECTORY_SEPARATOR.'csv'));
          $obj = new Archive7z(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.$csv.'.7z'));
          $obj->addEntry(public_path(DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'hoja_recuperacion_'.$id_hoja_recuperacion.'.pdf')); 
          
        $resultado["csv"]=$csv;
        $resultado["sw"]=true;
        
      }catch(\Exception $e) { 
        Log::error('ERROR EN BALANCE HIDRICO FIRMA PDF: ' . $e->getMessage(), [
          'exception' => $e,
          'trace' => $e->getTraceAsString(),
        ]);
      }
      return response()->json($resultado); 
    }
  
    public function generarPdfParaFirma($id_hoja_recuperacion){
      $path = public_path('tmp/pdf');
      if (!is_dir($path)) {
        mkdir($path);
      }
      $path = public_path('tmp/pdf/sin_firma/');
      if (!is_dir($path)) {
        mkdir($path);
      }
      
      $date = Carbon::now();
      $model_hoja = new CqxHojaRecuperacion();
      $fechaActual = $date->format('d/m/Y');
      $horaActual = $date->format('H:i:s');  
      //--- ESTABLECIMIENTO
      $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
      $model_establecimiento = new \App\Models\Establecimiento();
      $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
      $nombre_establecimiento = $establecimiento[0]->nombre;
      
      $hoja_recuperacion = CqxHojaRecuperacion::find($id_hoja_recuperacion);
      $codigo_cups="";
      $hopitalizacion = Hospitalizacione::select('hospitalizaciones.id', 'hospitalizaciones.cups_pnp')
      ->join('cqx_intervenciones', 'hospitalizaciones.id', '=', 'cqx_intervenciones.id_hospitalizacion')
      ->where('cqx_intervenciones.id',  $hoja_recuperacion->id_intervencion)
      ->first();
      if(!empty($hopitalizacion->cups_pnp)){
        $codigo_cups=$hopitalizacion->cups_pnp;
      }
      $datos_paciente_intervencion= $model_hoja->datos_paciente_intervencion($hoja_recuperacion->id_intervencion);
      $diagnostico_operatorio= $model_hoja->diagnostico_operatorio($hoja_recuperacion->id_intervencion);
      $tipos_anestesia_aq= $model_hoja->tipos_anestesia_aq($hoja_recuperacion->id_intervencion);
      $cirujanos= $model_hoja->profesionales_intraoperatorio(0,$hoja_recuperacion->id_intervencion);
      $anestesiologos= $model_hoja->profesionales_intraoperatorio(1,$hoja_recuperacion->id_intervencion);

      $encabezado_fv=$this->datosCaracterFisicoVitales();
      $funciones_vitales=$hoja_recuperacion->funciones_vitales_by_hr($id_hoja_recuperacion);
      $datos_paciente = $this->getDatosPaciente($hoja_recuperacion->id_asegurado);
      $ingresos=$this->datosIngresoHojaBalance($hoja_recuperacion->codigo_fecha_24,$hoja_recuperacion->id_asegurado);
      $egresos=$this->datosEgresoHojaBalance($hoja_recuperacion->codigo_fecha_24,$hoja_recuperacion->id_asegurado);

      $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".date("Y/m/d")."/cf_hoja_recuperacion_".$id_hoja_recuperacion.".pdf";
      $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".date("Y/m/d")."/cf_hoja_recuperacion_".$id_hoja_recuperacion.".pdf");
      $pdf = \PDF::loadView('frontend.centro_quirurgico.hoja_recuperacion.pdf_hoja_recuperacion_firmado', 
      compact(
        'url_descarga',
        'codigo_qr',
        'datos_paciente_intervencion',
        'diagnostico_operatorio',
        'tipos_anestesia_aq',
        'cirujanos',
        'anestesiologos',
        'hoja_recuperacion',
        'encabezado_fv',
        'funciones_vitales',
        'nombre_establecimiento',
        'ingresos',
        'egresos',
        'fechaActual',
        'horaActual',
        'codigo_cups'
      ));
      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portrait');
      file_put_contents($path."hoja_recuperacion_".$id_hoja_recuperacion.".pdf", $pdf->output());
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
    public function actualizarFirmaConToken(Request $request){
      $id = intval($request->id_resol);
      $hoja_recuperacion = CqxHojaRecuperacion::where('id', $id)->first();
      if($hoja_recuperacion->fecha_firma == ""){
          $hoja_recuperacion->fecha_firma = date("d-m-Y H:i:s");
          $hoja_recuperacion->ruta_firma = date("Y/m/d")."/cf_hoja_recuperacion_".$hoja_recuperacion->id.".pdf";
          $hoja_recuperacion->flag_firma = 1;
          $hoja_recuperacion->save();
      }
      return "ok";
    }
    public function actualizarFirmaSinToken(Request $request){
      $ok=false;
      $id = intval($request->id_resol);
      $hoja_recuperacion = CqxHojaRecuperacion::where('id', $id)->first();
      if($hoja_recuperacion->fecha_firma == ""){
          $hoja_recuperacion->fecha_firma = date("d-m-Y H:i:s");
          $hoja_recuperacion->ruta_firma = date("Y/m/d")."/cf_intraoperatorio_".$hoja_recuperacion->id.".pdf";
          $hoja_recuperacion->flag_firma = 2;
          $hoja_recuperacion->save();
          $ok=true;
      }
      echo $ok;
    }

    public function updateHojaRecuperacion(Request $request)
    {
      if (!Auth::check()) {
          return redirect('/auth/login');
      }
      DB::beginTransaction();
      try {
        //dd($request->all());
        $rules = [];
        $messages = [];

        // Validaciones condicionales según los indicadores
        $rules['cama'] = ['required'];
        if ($request->input('tiene_tratamiento') === 'true') {
          $rules['descripcion_tratamiento'] = ['required'];
        }
        if ($request->input('tiene_analisis') === 'true') {
          $rules['descripcion_analisis'] = 'required';
          $rules['fecha_analisis'] = 'required';
          $rules['cvc_analisis'] = 'required';
          $rules['sf_analisis'] = 'required';
          $rules['la_analisis'] = 'required';
          $rules['lpp_analisis'] = 'required';
          $messages['descripcion_analisis.required'] = 'El campo "Descripcion" es obligatorio cuando tiene_analisis es verdadero.';
          $messages['sf_analisis.required'] = 'El campo "SF análisis" es obligatorio cuando tiene_analisis es verdadero.';
          $messages['la_analisis.required'] = 'El campo "LA análisis" es obligatorio cuando tiene_analisis es verdadero.';
          $messages['lpp_analisis.required'] = 'El campo "LPP análisis" es obligatorio cuando tiene_analisis es verdadero.';
        }
        if ($request->input('tiene_diagnostico_enfermeria') === 'true') {
          $rules['campo1_diagnostico_enfermeria'] = 'required';
          $rules['campo2_diagnostico_enfermeria'] = 'required';
          $rules['campo3_diagnostico_enfermeria'] = 'required';
        }
        
        if ($request->input('tiene_balance_hidrico') === 'true') {
            $rules['balance_hidrico_campo'] = 'required';
            $messages['balance_hidrico_campo.required'] = 'El campo "balance hídrico" es obligatorio cuando tiene_balance_hidrico es verdadero.';
        }
        // Validaciones de arrays
        // $rules['nombre_procedimiento'] = 'required|array';
        // $rules['procedimiento_cantidad'] = 'required|array';
        // $rules['miSelect'] = 'required|array';

        $accionRow = $request->input('accionRowProcedimiento', []);

        foreach ($accionRow as $key => $accion) {
            if (in_array($accion, ['nuevo'])) {
                $rules["id_procedimiento.$key"] = 'required';
                $messages["id_procedimiento.$key.required"] = "El campo nombre del procedimiento en la fila $key es obligatorio.";

                $rules["procedimiento_cantidad.$key"] = 'required';
                $messages["procedimiento_cantidad.$key.required"] = "La cantidad en la fila $key es obligatoria.";

                $rules["miSelect.$key"] = 'required';
                $messages["miSelect.$key.required"] = "Debe seleccionar un diagnóstico para la fila $key.";
                
            }else if (in_array($accion, ['edit'])) {
                $rules["procedimiento_cantidad.$key"] = 'required';
                $messages["procedimiento_cantidad.$key.required"] = "La cantidad en la fila $key es obligatoria.";

                $rules["miSelect.$key"] = 'required';
                $messages["miSelect.$key.required"] = "Debe seleccionar un diagnóstico para la fila $key.";
            }
        }

        // Realizar la validación
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'errors' => $validator->errors(),
          ]);
        }
        

        $modeloHojaR = new CqxHojaRecuperacion();
        $id_hoja_recuperacion=$modeloHojaR->actualizar($request->all(),Auth::user()->id);
        DB::commit(); 
        return response()->json(['success' => true,'id_hoja_recuperacion'=>$request->id_hoja_recuperacion]);
      } catch (\Exception $e) {
          DB::rollback();
          Log::error('Error al querer actualizar los datos de HOJA DE RECUPERACION', [
              'message' => $e->getMessage(),
              'code' => $e->getCode(),
              'file' => $e->getFile(),
              'line' => $e->getLine(),
              'trace' => $e->getTraceAsString(),
          ]);
      } catch (\Throwable $th) {
          DB::rollback();
          throw $th;
      }
    }

  
  
}
