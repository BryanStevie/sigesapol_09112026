<?php
namespace App\Http\Controllers;

use App\Models\Nota_enfermeria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Balance_hidrico;
use App\Models\Balance_hidrico_detalle;
use App\Models\Emergencia;
use App\Models\Hospitalizacione;
use App\Models\Prestaciones;
use App\Models\TipoAtencione;
use App\Models\Turno;
use Illuminate\Support\Collection;
use Archive7z\Archive7z;
use DateTime;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Support\Facades\DB;

class EnfermeriaBalanceHidricoController extends Controller
{    
  function __construct()
    {
        $this->middleware('auth'); 
    } 
  
    public function registrarBalanceHidrico(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
        $valor_response=0;
        //dd($request->all());
        $data = $request->all();
        try{
            DB::beginTransaction();
            $dia = date("j");  // Día sin ceros a la izquierda (1 a 31)
            $mes = date("n");  // Mes sin ceros a la izquierda (1 a 12)
            $año = date("Y");  // Año con cuatro dígitos (e.g., 2024)
            // Concatenar los componentes para formar el código
            $codigo_fecha_24 = $dia.$mes.$año;

            $exists = Balance_hidrico::where('codigo_fecha_24', $codigo_fecha_24)->where('id_turno',$request->turno_bh)->where('id_asegurado',$request->id_asegurado)->exists();
            if($exists){
              return json_encode(array('existe_balance'=>true)); 
            }else{
              $objBalance_hidrico = new Balance_hidrico;
              $objBalance_hidrico->created_us = Auth::user()->id;
              $objBalance_hidrico->id_enfermeria = $request->id_enfermeria;
              $objBalance_hidrico->id_asegurado = $request->id_asegurado;
              $objBalance_hidrico->id_turno = $request->turno_bh;
              $objBalance_hidrico->fecha_registro = Carbon::now(); // Asigna la fecha en formato 'YYYY-MM-DD'
              $hora = Carbon::createFromFormat('H:i', $request->input('hora_bh'));
              $objBalance_hidrico->hora_registro = $hora->toTimeString(); 
              $objBalance_hidrico->created_at = Carbon::now();
              $objBalance_hidrico->estado = 1;
              $objBalance_hidrico->codigo_fecha_24=$codigo_fecha_24;//Este codigo es unico por dia, para agrupar los turnos
              $objBalance_hidrico->flag_firma=0;
              $objBalance_hidrico->peso = floatval($request->peso_kg);  
              $objBalance_hidrico->save();
              $id_balance_hidrico = $objBalance_hidrico->id;
              if(!empty($request->id_caracter_fisico_ingreso)){
                foreach ($request->id_caracter_fisico_ingreso as $key => $value) {
                  $detalle = new Balance_hidrico_detalle();
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
                  $detalle->id_turno = $request->turno_bh;
                  $detalle->codigo_fecha_24=$codigo_fecha_24;//Este codigo es unico por dia, para agrupar los turnos
                  $detalle->id_balance_hidrico=$id_balance_hidrico;
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
                  $detalle->save();
                }
                
              }
              if(!empty($request->id_caracter_fisico_egreso)){
                foreach ($request->id_caracter_fisico_egreso as $key => $value) {
                  $detalle = new Balance_hidrico_detalle();
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
                  $detalle->id_turno = $request->turno_bh;
                  $detalle->codigo_fecha_24=$codigo_fecha_24;//Este codigo es unico por dia, para agrupar los turnos
                  $detalle->id_balance_hidrico=$id_balance_hidrico;
                  $detalle->id_caracter_fisico=$data['id_caracter_fisico_egreso'][$key]?$data['id_caracter_fisico_egreso'][$key]:null;
                  $detalle->id_valor=$data['valor_tabla_egreso'][$key]?$data['valor_tabla_egreso'][$key]:null;
                  $detalle->valor_caracter_fisico=$data['valor_egreso'][$key];
                  $detalle->tipo_balance=2;//tipo_balance: 2=egreso
                  $detalle->es_visible=1;
                  $detalle->estado=1;
                  $detalle->save();
                }
              }
              $valor_response= $objBalance_hidrico;
              DB::commit();
            }
        }catch(\Exception $e) { 
          Log::error('ERROR EN ENFERMERIA AL GUARDAR BALANCE HIDRICO: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString(),
          ]);
            DB::rollback();
        }
        return json_encode($valor_response); 
	}
  public function actualizarBalanceHidrico(Request $request){
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
        $objBalance_hidrico = new Balance_hidrico;
        $objBalance_hidrico = Balance_hidrico::find($data['id_balance']);
        $exists = Balance_hidrico::where('codigo_fecha_24', $codigo_fecha_24)
        ->where('id_turno',$request->turno_bh_editar)
        ->where('id_asegurado',$request->id_asegurado)
        ->exists();
        if ($exists && ($objBalance_hidrico->id_turno != $request->turno_bh_editar)) {
          return json_encode(array('existe_balance'=>true)); 
        }
        $objBalance_hidrico->updated_us = Auth::user()->id;
        $objBalance_hidrico->updated_at = Carbon::now();
        $objBalance_hidrico->id_turno = $request->turno_bh_editar;
        $horaSinSegundos = substr($request->input('hora_bh_editar'), 0, 5);
        $hora = Carbon::createFromFormat('H:i', $horaSinSegundos);
        $objBalance_hidrico->hora_registro = $hora->toTimeString(); 
        $objBalance_hidrico->peso = $request->peso_kg_editar;
        $objBalance_hidrico->save(); 
        $id_balance_hidrico = $objBalance_hidrico->id; 
        if(!empty($request->id_caracter_fisico_ingreso)){
          foreach ($request->id_caracter_fisico_ingreso as $key => $value) {
            $modelo_balance_detalle = new Balance_hidrico_detalle();
            $detalle = $modelo_balance_detalle::find($data['id_detalle_ingreso'][$key]);

            if($detalle){//SI EXISTE EL ID ATUALIZA LOS DATOS
              if (isset($data['descripcion_caracter_fisico_id_ingreso'][$key]) && !empty($data['descripcion_caracter_fisico_id_ingreso'][$key])) {
                $codigo_agrupacion = $data['descripcion_caracter_fisico_id_ingreso'][$key];
                $detalle->descripcion=$data['descripcion_caracter_fisico_ingreso'][$key];
                $detalle->codigo_agrupacion=$codigo_agrupacion;
              }
              if(!empty($data['valor_tabla_ingreso'][$key]) && empty($data['descripcion_caracter_fisico_id_ingreso'][$key])){
               
                $terap = DB::table('intervencion_terapeuticas')
                ->where('id',$data['valor_tabla_ingreso'][$key])
                ->first();
                $detalle->id_valor=$terap->id;
                $detalle->descripcion=$terap->descripcion_intervencion_t;
                $detalle->codigo_agrupacion=$terap->id;
              }
              if(empty($data['descripcion_caracter_fisico_id_ingreso'][$key]) && empty($data['valor_tabla_ingreso'][$key])){
                $detalle->descripcion=$data['descripcion_ingreso'][$key];
              }
              $detalle->id_caracter_fisico=$data['id_caracter_fisico_ingreso'][$key]?$data['id_caracter_fisico_ingreso'][$key]:null;
              $detalle->es_visible=$data['es_visible'][$key];
              $detalle->valor_caracter_fisico=$data['valor_ingreso'][$key];
              $detalle->save();
            }else{
              //SI NO EXISTE
              /***********INSERTA NUEVO DETALLE**************/
              $detalle = new Balance_hidrico_detalle();
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
              $detalle->id_turno = $objBalance_hidrico->id_turno;
              $detalle->codigo_fecha_24=$objBalance_hidrico->codigo_fecha_24;
              $detalle->id_balance_hidrico=$objBalance_hidrico->id;
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
              $detalle->tipo_balance=1;
              $detalle->estado=1;
              $detalle->save();
              /***********INSERTA NUEVO DETALLE**************/
            }
          }
          
        }
        if(!empty($request->id_caracter_fisico_egreso)){
          foreach ($request->id_caracter_fisico_egreso as $key => $value) {
            $modelo_balance_detalle = new Balance_hidrico_detalle();
            $detalle = $modelo_balance_detalle::find($data['id_detalle_egreso'][$key]);
            if($detalle){//SI EXISTE ACTUALIZA LOS DATOS
              if (isset($data['descripcion_caracter_fisico_id_egreso'][$key]) && !empty($data['descripcion_caracter_fisico_id_egreso'][$key])) {
                $detalle->descripcion=$data['descripcion_caracter_fisico_egreso'][$key];
              } else {
                $detalle->descripcion=$data['descripcion_egreso'][$key];
              }
              $detalle->valor_caracter_fisico=$data['valor_egreso'][$key];
              $detalle->save();
            }else{//SI NO EXISTE REALIZA NUEVO REGISTRO
              $detalle = new Balance_hidrico_detalle();
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
              $detalle->secuencia=$data['secuencia'][$key];
              $detalle->id_turno = $objBalance_hidrico->id_turno;
              $detalle->codigo_fecha_24=$objBalance_hidrico->codigo_fecha_24;
              $detalle->id_balance_hidrico=$objBalance_hidrico->id;
              $detalle->id_caracter_fisico=$data['id_caracter_fisico_egreso'][$key]?$data['id_caracter_fisico_egreso'][$key]:null;
              $detalle->id_valor=$data['valor_tabla_egreso'][$key]?$data['valor_tabla_egreso'][$key]:null;
              $detalle->valor_caracter_fisico=$data['valor_egreso'][$key];
              $detalle->tipo_balance=2;//tipo_balance: 2=egreso
              $detalle->es_visible=1;
              $detalle->estado=1;
              $detalle->save();
            }
          }
        }
        $valor_response = $id_balance_hidrico;
        DB::commit();

    }catch(\Exception $e) { 
      Log::error('ERROR EN ENFERMERIA AL GUARDAR BALANCE HIDRICO: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
      ]);
        DB::rollback();
    }
    return json_encode($valor_response); 
	}

  public function pesoAlRegistrar($id_balance)
  {
    return DB::table('balance_hidricos as bh')->select('bh.peso as peso_al_registrar_bh')->where('bh.id', $id_balance)->get();
  }

  public function turnoSelecionadoPorRegistro($id_balance)
  {
    return DB::table('balance_hidricos as bh')->select('bh.id_turno as turno_seleccionado')->where('bh.id', $id_balance)->get();
  }

  public function horaRegistro($id_balance)
  {
    return DB::table('balance_hidricos as bh')->select('bh.hora_registro as hora_registro_bh')->where('bh.id', $id_balance)->get();
  }

	public function listar(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif; 
    $fecha_desde_formatted = Carbon::createFromFormat('d/m/Y', $request->fecha_desde)->startOfDay();
    $fecha_hasta_formatted = Carbon::createFromFormat('d/m/Y', $request->fecha_hasta)->endOfDay();

    $query = DB::table('balance_hidricos as bh')
        ->leftJoin('balance_hidrico_detalles as bhd', 'bh.id', '=', 'bhd.id_balance_hidrico')
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
            'bh.flag_firma',
            'bh.ruta_firma',
            'bh.peso as peso_al_registrar_bh',
            DB::raw('TO_CHAR(bh.fecha_registro, \'DD/MM/YYYY\') AS fecha_registro'),
            DB::raw('
                LPAD(EXTRACT(HOUR FROM bh.hora_registro)::text, 2, \'0\') || \':\' ||
                LPAD(EXTRACT(MINUTE FROM bh.hora_registro)::text, 2, \'0\') || \':\' ||
                LPAD(EXTRACT(SECOND FROM bh.hora_registro)::text, 2, \'0\') AS hora_registro'
            ),
            DB::raw('SUM(CASE WHEN bhd.tipo_balance = 1 AND es_visible != 0 AND bhd.id_caracter_fisico != 46 THEN bhd.valor_caracter_fisico ELSE 0 END) - 
                    SUM(CASE WHEN (bhd.tipo_balance = 2 AND es_visible != 0) 
                    OR (bhd.tipo_balance = 1 AND es_visible != 0 AND bhd.id_caracter_fisico = 46)
                    THEN bhd.valor_caracter_fisico ELSE 0 END) AS diferencia')
        )
        ->leftJoin(DB::raw("(
            SELECT bh.codigo_fecha_24,
                  SUM(CASE WHEN bhd.tipo_balance = 1 AND es_visible != 0 AND bhd.id_caracter_fisico != 46 THEN bhd.valor_caracter_fisico ELSE 0 END) - 
                  SUM(CASE WHEN (bhd.tipo_balance = 2 AND es_visible != 0) 
                  OR (bhd.tipo_balance = 1 AND es_visible != 0 AND bhd.id_caracter_fisico = 46)
                  THEN bhd.valor_caracter_fisico ELSE 0 END) AS bh24
            FROM balance_hidricos AS bh
            LEFT JOIN balance_hidrico_detalles AS bhd ON bh.id = bhd.id_balance_hidrico
            where bh.id_enfermeria = ".$request->id_enfermeria_bh."
            GROUP BY bh.codigo_fecha_24
        ) AS subquery"), 'bh.codigo_fecha_24', '=', 'subquery.codigo_fecha_24')
        ->addSelect('subquery.bh24')
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

    if (!empty($fecha_desde_formatted) && !empty($fecha_hasta_formatted)) {
        $query->whereBetween('bh.fecha_registro', [$fecha_desde_formatted, $fecha_hasta_formatted]);
    } elseif (!empty($fecha_desde_formatted)) {
        $query->where('bh.fecha_registro', '>=', $fecha_desde_formatted);
    } elseif (!empty($fecha_hasta_formatted)) {
        $query->where('bh.fecha_registro', '<=', $fecha_hasta_formatted);
    }

    if (!empty($request->turno)) {
        $query->where('bh.id_turno', $request->turno);
    }
    $query->where('bh.id_asegurado', $request->id_asegurado);
    $results = $query->get();
    return json_encode($results);   
    
  }
	public function hojaBalanceHidrico(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
    $data=array();
    $data['ingresos']=$this->datosIngresoHojaBalance($request->codigo_fecha_24,$request->id_asegurado, $request->id_tipo_atencion);
    $data['egresos']=$this->datosEgresoHojaBalance($request->codigo_fecha_24,$request->id_asegurado, $request->id_tipo_atencion);
    $data['datos_paciente']=$this->getDatosPaciente($request->id_asegurado);
    $data['bh_general']=$this->pesoAlRegistrar($request->id_balance);
    return json_encode($data);   
  }
  public function datosGlobales(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif; 
    $data['tipo_atenciones']= $this->tipoAtenciones();
    $data['turnos']= $this->turnosV2();
    $data['datos_paciente']= $this->getDatosPaciente($request->id_asegurado);
    $data['bh_general']=$this->pesoAlRegistrar($request->id_balance);
    $data['turno_seleccionado']=$this->turnoSelecionadoPorRegistro($request->id_balance);
    $data['hora_registro']=$this->horaRegistro($request->id_balance);
    $data['intervencion_terapeuticas']=$this->intervencionTerapeuticas();
    if(!empty($request->id_balance)){
      $data['caracter_fisico']=$this->balanceHidrico($request->id_balance);
    }else{
      $data['caracter_fisico']=$this->caracterFisico();//solo de balance hidrico
    }
   
    return json_encode($data);   
  }
  public function tipoAtenciones() {
      $model_atencione = new TipoAtencione;
      $atencione = $model_atencione->ShowTipoAtencionAll();
      return $atencione;
  }
  public function turnos($id_area){
    $query = DB::table('turnos')
    ->select('turnos.*');
    // if ($id_area == 1 || $id_area == 12) {
    //     $query->whereIn('id',[1,2,3,4]);
    // }else{
    //   $query->whereIn('id',[2,3,4]);
    // }
    $datos = $query->get();
    return $datos;
  }
  public function turnosV2(){
    $model_turno = new Turno();
    $turnos = $model_turno->ShowAll();
    return $turnos;
  }
  public function getDatosPaciente($id_asegurado){
    $data = DB::table('asegurados')
    ->leftJoin('asegurado_historias', 'asegurado_historias.id_asegurado', '=', 'asegurados.id')
    ->leftJoin('asegurados_alergias', 'asegurados_alergias.id_asegurado', '=', 'asegurados.id')
    ->leftJoin('tipo_beneficiarios as tb', 'tb.id', '=', 'asegurados.id_tipo_beneficiario')
    ->leftJoin('tipo_sangres as ts', 'asegurados.id_tipo_sangre', '=', 'ts.id')
    ->select(
        'asegurados.fecha_nac as fecha_nacimiento',
        'ts.descripcion_corta as tipo_sangre',
        'tb.descripcion as parentesgo',
        DB::raw("
            CASE 
                WHEN EXISTS (
                    SELECT 1
                    FROM asegurados_alergias aa
                    WHERE aa.id_asegurado = asegurados.id
                    AND aa.estado = true
                )
                THEN 1
                ELSE 0
            END AS tiene_alergias
        "),
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
  public function intervencionTerapeuticasAJax(){
    $registros = DB::table('intervencion_terapeuticas')
    ->orderBy('secuencia')
    ->get();
    return json_encode($registros);
  }
  public function balanceHidrico($id){
    $results = collect(DB::table('balance_hidricos as bh')
    ->leftJoin('balance_hidrico_detalles as bhd', 'bh.id', '=', 'bhd.id_balance_hidrico')
    ->leftJoin('caracter_fisicos as cf', 'bhd.id_caracter_fisico', '=', 'cf.id')
    ->select([
        'bhd.id',
        'cf.multiple',
        'cf.tabla',
        'cf.id as id_cf',
        'cf.multiple',
        'cf.tabla',
        'cf.tabla_tipo',
        'cf.secuencia',
        'cf.descripcion as descripcion_cf',
        'bhd.descripcion',
        'bhd.valor_caracter_fisico',
        'bhd.tipo_balance',
        'bhd.id_valor'
    ])
    ->where('bh.id',$id)
    ->where('bhd.estado',1)
    ->orderBy('bhd.secuencia')
    ->get());
    //dd($results);

    $groupedResults = $results->groupBy('id_cf')->map(function (Collection $group) {
      $parent = $group->first();
      if($parent->multiple==1 || $parent->multiple==1 && $parent->tabla==1){
        $parent->datos = $group->map(function ($item) {
            return [
                'id' => $item->id,
                'secuencia' => $item->secuencia,
                'id_cf' => $item->id_cf,
                'tabla_tipo'=>$item->tabla_tipo,
                'id_valor'=>$item->id_valor,
                'descripcion' => $item->descripcion,
                'valor_caracter_fisico' => $item->valor_caracter_fisico,
                'tipo_balance' => $item->tipo_balance
            ];
        })->values();
      }
      
      return $parent;
      
    });
  
  // Imprime el resultado agrupado (para depuración)
  return $groupedResults->values();
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

        $balanceModelo = new Balance_hidrico; 
        $balance_cabecera = $balanceModelo::leftJoin('enfermerias', 'balance_hidricos.id_enfermeria', '=', 'enfermerias.id')
                            ->leftJoin('users', 'balance_hidricos.created_us', '=', 'users.id')
                            ->select('users.*','balance_hidricos.*', 'enfermerias.id_tipo_atencion','enfermerias.id_ext_enfermeria') 
                            ->where('balance_hidricos.id', $id_balance)
                            ->first();
        $ingresos=$this->datosIngresoHojaBalance($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado, 2);
        $egresos=$this->datosEgresoHojaBalance($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado, 2);
        $ingreso460=$this->datoIngreso460($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado, $id_balance);

        $tipo_atencion = "";
        $codigo_cups = '';
       if($balance_cabecera->id_tipo_atencion==2){
          $tipo_atencion = "Emergencia";
          $bhModelo = new Balance_hidrico();
          $existeAtencionMedica = Prestaciones::where('id_ext', '=', $balance_cabecera->id_ext_enfermeria)
                                ->where('id_tipo_atencion', 2)
                                ->exists();
          if ($existeAtencionMedica) {
            $datos_atencion = $bhModelo->datosPacienteBH($balance_cabecera->id_enfermeria);
          } else {
            $datos_atencion = $bhModelo->datosPacienteBH_sa($balance_cabecera->id_enfermeria);
          }
          $codigo_cups = Emergencia::select('emergencias.cups_pnp as codigo_cups')->where('emergencias.id', $datos_atencion[0]->id_emergencia)->first();
          $codigo_cups = $codigo_cups->codigo_cups;
       }else if($balance_cabecera->id_tipo_atencion==3){
          $tipo_atencion = "Hospitalización";
          $datos_atencion = DB::table('hospitalizaciones as h')
                   ->leftJoin('camas as c1', 'h.id_cama', '=', 'c1.id')
                   ->leftJoin('cuartos as c2', 'h.id_cuarto', '=', 'c2.id')
                   ->selectRaw("h.id,COALESCE(c2.nombre, 'SIN ASIGNAR') AS nombre_cuarto, COALESCE(c1.nombre, 'SIN ASIGNAR') AS nombre_cama")
                   ->where('h.id', $balance_cabecera->id_ext_enfermeria)
                   ->first();
       }
      
        $pdf = \PDF::loadView('frontend.emergencia_enfermeria.balance_hidrico.pdf_balance.pdf_balance_hidrico', 
        compact(
            'datos_paciente',
            'balance_cabecera',
            'tipo_atencion',
            'ingreso460',
            'ingresos',
            'egresos',
            'datos_atencion',
            'codigo_cups',
            'nombre_establecimiento',
            'fechaActual',
            'horaActual' 
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait'); //Horizontal
        return $pdf->stream('balance_hidrico.pdf'); 
 
  }
  public function pdf_balance_hidrico_hospi($id_balance){
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

        $balanceModelo = new Balance_hidrico; 
        $balance_cabecera = $balanceModelo::leftJoin('enfermerias', 'balance_hidricos.id_enfermeria', '=', 'enfermerias.id')
                            ->leftJoin('users', 'balance_hidricos.created_us', '=', 'users.id')
                            ->select('users.*','balance_hidricos.*', 'enfermerias.id_tipo_atencion','enfermerias.id_ext_enfermeria') 
                            ->where('balance_hidricos.id', $id_balance)
                            ->first();


        $ingresos=$this->datosIngresoHojaBalance($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado, 3);
        $egresos=$this->datosEgresoHojaBalance($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado, 3);
        $ingreso460=$this->datoIngreso460($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado, $id_balance);
        $tipo_atencion = "";
       if($balance_cabecera->id_tipo_atencion==2){
          $tipo_atencion = "Emergencia";
          
       }else if($balance_cabecera->id_tipo_atencion==3){
          $tipo_atencion = "Hospitalización";
          $bhModelo = new Balance_hidrico();
          $datos_atencion = $bhModelo->datosPacienteBHHospi($balance_cabecera->id_enfermeria);
          $codigo_cups = Hospitalizacione::select('hospitalizaciones.cups_pnp as codigo_cups')->where('hospitalizaciones.id', $datos_atencion[0]->id_hospitalizacion)->first();
          $codigo_cups = $codigo_cups->codigo_cups;
       }
        $pdf = \PDF::loadView('frontend.hospitalizacion_enfermeria.balance_hidrico.pdf_balance.pdf_balance_hidrico', 
        compact(
            'datos_paciente',
            'balance_cabecera',
            'tipo_atencion',
            'ingresos',
            'egresos',
            'ingreso460',
            'datos_atencion',
            'nombre_establecimiento',
            'fechaActual',
            'horaActual',
            'codigo_cups'
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait'); //Horizontal
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

        $balanceModelo = new Balance_hidrico; 
        $balance_cabecera = $balanceModelo::leftJoin('enfermerias', 'balance_hidricos.id_enfermeria', '=', 'enfermerias.id')
                            ->leftJoin('users', 'balance_hidricos.created_us', '=', 'users.id')
                            ->select('users.*','balance_hidricos.*', 'enfermerias.id_tipo_atencion','enfermerias.id_ext_enfermeria') 
                            ->where('balance_hidricos.id', $id_balance)
                            ->first();
        $ingresos=$this->datosIngresoHojaBalancePorTurno($id_balance);
        $egresos=$this->datosEgresoHojaBalancePorTurno($id_balance);

        $tipo_atencion = "";
        $codigo_cups = '';
       if($balance_cabecera->id_tipo_atencion==2){
          $tipo_atencion = "Emergencia";
          $bhModelo = new Balance_hidrico();
          $datos_atencion = $bhModelo->datosPacienteBH($balance_cabecera->id_enfermeria);
          $codigo_cups = Emergencia::select('emergencias.cups_pnp as codigo_cups')->where('emergencias.id', $datos_atencion[0]->id_emergencia)->first();
          $codigo_cups = $codigo_cups->codigo_cups;
       }else if($balance_cabecera->id_tipo_atencion==3){
          $tipo_atencion = "Hospitalización";
          $bhModelo = new Balance_hidrico();
          $datos_atencion = $bhModelo->datosPacienteBHHospi($balance_cabecera->id_ext_enfermeria);
          $codigo_cups = Hospitalizacione::select('hospitalizaciones.cups_pnp as codigo_cups')->where('hospitalizaciones.id', $datos_atencion[0]->id_hospitalizacion)->first();
          $codigo_cups = $codigo_cups->codigo_cups;
       }
      
        $pdf = \PDF::loadView('frontend.emergencia_enfermeria.balance_hidrico.pdf_balance.pdf_balance_hidrico', 
        compact(
            'datos_paciente',
            'balance_cabecera',
            'tipo_atencion',
            'ingresos',
            'egresos',
            'datos_atencion',
            'nombre_establecimiento',
            'codigo_cups',
            'fechaActual',
            'horaActual' 
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait'); //Horizontal
        return $pdf->stream('balance_hidrico.pdf'); 
 
  }
  /***DE TODOS LOS TURNOS */
  function datosIngresoHojaBalance($codigo_fecha_24,$id_asegurado, $id_tipo_atencion){
    $ingresos = DB::table('balance_hidricos')
    ->join('balance_hidrico_detalles', 'balance_hidricos.id', '=', 'balance_hidrico_detalles.id_balance_hidrico')
    ->join('enfermerias', 'balance_hidricos.id_enfermeria', '=', 'enfermerias.id')
    // ->join('caracter_fisicos', 'balance_hidrico_detalles.id_caracter_fisico', '=', 'caracter_fisicos.id')
    ->select(
        'balance_hidrico_detalles.codigo_agrupacion','balance_hidrico_detalles.descripcion as caracter_fisico',
        // 'caracter_fisicos.descripcion as caracter_fisico',
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 1 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 2 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 3 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 4 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
        DB::raw('SUM(balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
    )
    ->where('balance_hidrico_detalles.estado', 1)
    ->where('balance_hidrico_detalles.es_visible', 1)
    ->where('balance_hidrico_detalles.tipo_balance', 1)
    ->where('enfermerias.id_tipo_atencion', $id_tipo_atencion)
    ->where('balance_hidricos.codigo_fecha_24', $codigo_fecha_24)
    ->where('balance_hidricos.id_asegurado', $id_asegurado)
    ->groupBy('balance_hidrico_detalles.codigo_agrupacion','balance_hidrico_detalles.descripcion','balance_hidrico_detalles.secuencia')
    ->orderBy('balance_hidrico_detalles.secuencia')
    ->get();
    return $ingresos;
  }

  function datoIngreso460($codigo_fecha_24,$id_asegurado, $id_balance){
    $ingreso460 = DB::table('balance_hidricos')
    ->join('balance_hidrico_detalles', 'balance_hidricos.id', '=', 'balance_hidrico_detalles.id_balance_hidrico')
    ->select(
        'balance_hidrico_detalles.codigo_agrupacion','balance_hidrico_detalles.descripcion as caracter_fisico',
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 1 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 2 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 3 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 4 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
        DB::raw('SUM(balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
    )
    ->where('balance_hidrico_detalles.estado', 1)
    ->where('balance_hidrico_detalles.es_visible', 1)
    ->where('balance_hidrico_detalles.tipo_balance', 1)
    ->where('balance_hidricos.codigo_fecha_24', $codigo_fecha_24)
    ->where('balance_hidricos.id_asegurado', $id_asegurado)
    ->where('balance_hidrico_detalles.codigo_agrupacion', 460)
    ->where('balance_hidrico_detalles.id_balance_hidrico', $id_balance)
    ->groupBy('balance_hidrico_detalles.codigo_agrupacion','balance_hidrico_detalles.descripcion','balance_hidrico_detalles.secuencia')
    ->orderBy('balance_hidrico_detalles.secuencia')
    ->first();
    return $ingreso460;
  }

  function datosEgresoHojaBalance($codigo_fecha_24,$id_asegurado, $id_tipo_atencion){
    $egresos = DB::table('balance_hidricos')
    ->join('balance_hidrico_detalles', 'balance_hidricos.id', '=', 'balance_hidrico_detalles.id_balance_hidrico')
    ->join('enfermerias', 'balance_hidricos.id_enfermeria', '=', 'enfermerias.id')
    //->join('caracter_fisicos', 'balance_hidrico_detalles.id_caracter_fisico', '=', 'caracter_fisicos.id')
    ->select(
        'balance_hidrico_detalles.codigo_agrupacion','balance_hidrico_detalles.descripcion as caracter_fisico',
        //'caracter_fisicos.descripcion as caracter_fisico',
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 1 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 2 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 3 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 4 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
        DB::raw('SUM(balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
    )
    ->where('balance_hidrico_detalles.estado', 1)
    ->where('balance_hidrico_detalles.tipo_balance', 2)
    ->where('enfermerias.id_tipo_atencion', $id_tipo_atencion)
    ->where('balance_hidricos.codigo_fecha_24', $codigo_fecha_24)
    ->where('balance_hidricos.id_asegurado', $id_asegurado)
    ->groupBy('balance_hidrico_detalles.codigo_agrupacion','balance_hidrico_detalles.descripcion','balance_hidrico_detalles.secuencia')
    ->orderBy('balance_hidrico_detalles.secuencia')
    ->get();
    return $egresos;
  }
  /***FIN DE TODOS LOS TURNOS ***/

  /*****POR UN SOLO TURNO ***/
  function datosIngresoHojaBalancePorTurno($id_balance_hidrico){
    $ingresos = DB::table('balance_hidricos')
    ->join('balance_hidrico_detalles', 'balance_hidricos.id', '=', 'balance_hidrico_detalles.id_balance_hidrico')
    ->select(
        'balance_hidrico_detalles.id','balance_hidrico_detalles.descripcion as caracter_fisico', 'balance_hidrico_detalles.codigo_agrupacion',
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 1 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 2 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 3 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 4 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
        DB::raw('SUM(balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
    )
    ->where('balance_hidrico_detalles.estado', 1)
    ->where('balance_hidrico_detalles.es_visible', 1)
    ->where('balance_hidrico_detalles.tipo_balance', 1)
    ->where('balance_hidricos.id', $id_balance_hidrico)
    ->groupBy('balance_hidrico_detalles.codigo_agrupacion', 'balance_hidrico_detalles.id','balance_hidrico_detalles.descripcion','balance_hidrico_detalles.secuencia')
    ->orderBy('balance_hidrico_detalles.secuencia')
    ->get();
    return $ingresos;
  }
  function datosEgresoHojaBalancePorTurno($id_balance_hidrico){
    $egresos = DB::table('balance_hidricos')
    ->join('balance_hidrico_detalles', 'balance_hidricos.id', '=', 'balance_hidrico_detalles.id_balance_hidrico')
    ->select(
        'balance_hidrico_detalles.id','balance_hidrico_detalles.descripcion as caracter_fisico',
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 1 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_md'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 2 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_m'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 3 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_t'),
        DB::raw('SUM(CASE WHEN balance_hidrico_detalles.id_turno = 4 THEN balance_hidrico_detalles.valor_caracter_fisico ELSE 0 END) AS total_n'),
        DB::raw('SUM(balance_hidrico_detalles.valor_caracter_fisico) AS total_caracter_fisico')
    )
    ->where('balance_hidrico_detalles.estado', 1)
    ->where('balance_hidrico_detalles.tipo_balance', 2)
    ->where('balance_hidricos.id', $id_balance_hidrico)
    ->groupBy('balance_hidrico_detalles.id','balance_hidrico_detalles.descripcion','balance_hidrico_detalles.secuencia')
    ->orderBy('balance_hidrico_detalles.secuencia')
    ->get();
    return $egresos;
  }
  /*****FIN POR UN SOLO TURNO ***/
  public function anularItemBalanceDetalle(Request $request){
    $balance = Balance_hidrico_detalle::find($request->id);
    if ($balance) {
        $balance->estado = 0;
        $balance->delete_us = Auth::user()->id;
        $balance->delete_at = Carbon::now();
        $balance->save();
    } else {
        return response()->json(['mensaje' => 'Registro no econtrado'], 404);
    }
    return json_encode(1); 
  }
  public function balanceHidricoPDFfirmar($id_balance_hidrico, $id_tipo_atencion = 2){
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
      $csv.= "_balance_hidrico";
      Excel::create($csv, function($excel) use($id_balance_hidrico, $id_balance_hidrico, $id_tipo_atencion){
          $excel->sheet('GENERAL', function($sheet) use($id_balance_hidrico, $id_balance_hidrico, $id_tipo_atencion) {
              $variable = [];
              $this->generarPdfParaFirma($id_balance_hidrico, $id_tipo_atencion);
              array_push($variable, array(url().DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'balance_hidrico_'.$id_balance_hidrico.'.pdf', url().DIRECTORY_SEPARATOR.'upload_firma'.DIRECTORY_SEPARATOR.'?name=cf_balance_hidrico_'.$id_balance_hidrico.'.pdf&id_tipo=balance_hidrico&id_resol='.$id_balance_hidrico, 'cf_balance_hidrico_'.$id_balance_hidrico.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'escudo.jpg','1','7'));                             
              $sheet->fromArray($variable, null, '', false, false);
          });
        })->store('csv',public_path('tmp'.DIRECTORY_SEPARATOR.'csv'));
        
        $obj = new Archive7z(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.$csv.'.7z'));
        $obj->addEntry(public_path(DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'balance_hidrico_'.$id_balance_hidrico.'.pdf')); 
        
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

  public function generarPdfParaFirma($id_balance_hidrico, $id_tipo_atencion){
    $path = public_path('tmp/pdf');
    if (!is_dir($path)) {
      mkdir($path);
    }
    $path = public_path('tmp/pdf/sin_firma/');
    if (!is_dir($path)) {
      mkdir($path);
    }

    $date = Carbon::now();
    $fechaActual = $date->format('d/m/Y');
    $horaActual = $date->format('H:i:s');  

    $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
    $model_establecimiento = new \App\Models\Establecimiento();
    $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
    $nombre_establecimiento = $establecimiento[0]->nombre;

    $balanceModelo = new Balance_hidrico; 
    $balance_cabecera = $balanceModelo::leftJoin('enfermerias', 'balance_hidricos.id_enfermeria', '=', 'enfermerias.id')
                        ->leftJoin('users', 'balance_hidricos.created_us', '=', 'users.id')
                        ->select('users.*','balance_hidricos.*', 'enfermerias.id_tipo_atencion','enfermerias.id_ext_enfermeria') 
                        ->where('balance_hidricos.id', $id_balance_hidrico)
                        ->first();
    $ingresos=$this->datosIngresoHojaBalancePorTurno($id_balance_hidrico);
    $egresos=$this->datosEgresoHojaBalancePorTurno($id_balance_hidrico);
    $ingreso460=$this->datoIngreso460($balance_cabecera->codigo_fecha_24,$balance_cabecera->id_asegurado, $id_balance_hidrico);
    $tipo_atencion = "";
    $codigo_cups = '';
    $bhModelo = new Balance_hidrico();
    if($balance_cabecera->id_tipo_atencion==2){
      $existeAtencionMedica = Prestaciones::where('id_ext', '=', $balance_cabecera->id_ext_enfermeria)
                                ->where('id_tipo_atencion', 2)
                                ->exists();
      if ($existeAtencionMedica) {
        $datos_atencion = $bhModelo->datosPacienteBH($balance_cabecera->id_enfermeria);
      } else {
        $datos_atencion = $bhModelo->datosPacienteBH_sa($balance_cabecera->id_enfermeria);
      }
      $tipo_atencion = "Emergencia";
      $codigo_cups = Emergencia::select('emergencias.cups_pnp as codigo_cups')->where('emergencias.id', $datos_atencion[0]->id_emergencia)->first();
      $codigo_cups = $codigo_cups->codigo_cups;
    }else if($balance_cabecera->id_tipo_atencion==3){
      $tipo_atencion = "Hospitalización";
      $datos_atencion = $bhModelo->datosPacienteBHHospi($balance_cabecera->id_enfermeria);
      $codigo_cups = Hospitalizacione::select('hospitalizaciones.cups_pnp as codigo_cups')->where('hospitalizaciones.id', $datos_atencion[0]->id_hospitalizacion)->first();
      $codigo_cups = $codigo_cups->codigo_cups;
    }
    $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".date("Y/m/d")."/cf_balance_hidrico_".$id_balance_hidrico.".pdf";
    $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".date("Y/m/d")."/cf_balance_hidrico_".$id_balance_hidrico.".pdf");
    $pdf = \PDF::loadView('frontend.emergencia_enfermeria.balance_hidrico.pdf_balance.pdf_balance_hidrico_firmado', 
    compact(
        'url_descarga',
        'codigo_qr',
        'datos_paciente',
        'balance_cabecera',
        'tipo_atencion',
        'id_tipo_atencion',
        'ingreso460',
        'ingresos',
        'egresos',
        'datos_atencion',
        'codigo_cups',
        'nombre_establecimiento',
        'fechaActual',
        'horaActual' 
    ));
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait');
    file_put_contents($path."balance_hidrico_".$id_balance_hidrico.".pdf", $pdf->output());
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
    $bh = Balance_hidrico::where('id', $id)->first();
    if (!$bh) {
      return response()->json(['message' => 'No existe registro del balance hídrico'], 404);
    }
    if($bh->fecha_firma == ""){
        $bh->fecha_firma = date("d-m-Y H:i:s");
        $bh->ruta_firma = date("Y/m/d")."/cf_balance_hidrico_".$bh->id.".pdf";
        $bh->flag_firma = 1;
        $bh->save();
    }
    return "ok";
  }
  public function actualizarFirmaSinToken(Request $request){
    $ok=false;
    $id = intval($request->id_resol);
    $bh = Balance_hidrico::where('id', $id)->first();
    if($bh->fecha_firma == ""){
        $bh->fecha_firma = date("d-m-Y H:i:s");
        $bh->ruta_firma = date("Y/m/d")."/cf_balance_hidrico_".$bh->id.".pdf";
        $bh->flag_firma = 2;
        $bh->save();
        $ok=true;
    }
    echo $ok;
  }
  public function datosFirmaBalancePorId($id){
    $balanceHidrico = Balance_hidrico::select('fecha_firma', 'ruta_firma', 'flag_firma')
    ->where('id', $id)
    ->first();
    return $balanceHidrico;
  }

  public function getPesoDesdeNotaEnfermeria($id_nota) {
    $model_nota = new Nota_enfermeria();
    $res = $model_nota->extraerPesoNotaEnfermeria($id_nota);
    return response()->json($res);
  }
  
}