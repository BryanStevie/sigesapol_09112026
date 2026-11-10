<?php
namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\Nota_enfermeria;
use Illuminate\Support\Facades\DB;

use App;
use Illuminate\Support\Facades\Auth;
use QrCode;
use SoapClient;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Requests;
use App\Models\Medico;
use App\Models\Anaquele;
use App\Models\Farmacia;
use App\Models\Producto;
use App\Models\Proveedore;
use App\Models\Consultorio;
use App\Models\Dependencia;
use App\Models\Receta_vale;
use App\Models\Guia_entrada;
use App\Models\Req_producto;
use Illuminate\Http\Request;
use App\Models\Requerimiento;
use App\Models\Prestaciones;
use App\Models\Transferencia;
use App\Models\Establecimiento;
use Session;
use App\Models\Producto_receta;
use App\Models\Hospitalizacione;
use App\Models\Receta_diagnostico;
use App\Http\Controllers\Controller;
use App\Models\Emergencia;
use App\Models\Asegurado;
use App\Models\Asegurado_historia;
use App\Models\CuidadoEnfermeros;
use App\Models\Diagnostico;
use App\Models\Enfermeria;
use App\Models\Evolucion;
use App\Models\ExamenImagenKardexes;
use App\Models\ExamenLaboratorioKardexes;
use App\Models\Gestacion;
use App\Models\HorarioKardexMedicamento;
use App\Models\KardexEnfermerias;
use App\Models\KardexInterconsultas;
use App\Models\KardexMedicamentoEnfermeria;
use App\Models\Puerperio;
use App\Models\Gastroenterologia;
use App\Models\Prestacion_eventos;
use Mockery\Undefined;
use Exception;
use Hamcrest\Type\IsObject;
use Mpdf\Mpdf;
use App\Ws\Reniec;
use App\Ws\Dirrehum;
use App\Ws\Saludpol;
use Illuminate\Support\Facades\Log;


class PdfController extends Controller
{
    function __construct()
    {
        $this->middleware('auth', ['except' => ['mostrarpdflaboratoriofema', 'mostrarpdfderivado', 'mostrarpdffema1']]); 
        
    } 

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function imprimir_receta($nro_receta,$id_farmacia,$n) {       
        //--------------------------------------------
        //var_dump($n); exit;
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        //--------------------------------------------  
        $recetas = $model_receta->ShowByNroRecetaAndId_farmacia($nro_receta, $id_farmacia);
        $receta = $model_receta->GetIdRecetaByNroRecetaValida($nro_receta,$id_farmacia);
        $id_receta_vale = $receta->id;
        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($id_receta_vale);
        $view = \View::make('pdf.ticket_atencion', compact('medicamentos','recetas'))->render();
        $pdf = \App::make('dompdf.wrapper');  
        $paper_size = array(0,0,300,500);
        $pdf->loadHTML($view)->setPaper($paper_size);
        //return $pdf->download('pdf_reporte_causareclamo');
        return ($pdf->stream());         
    }
	
	public function imprimir_receta_vale($nro_receta, $id_farmacia) {
        //--------------------------------------------
        //var_dump($n); exit;
        $model_farmacia = new Farmacia();
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($id_farmacia);
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        //--------------------------------------------  
        //$receta = $model_receta->ShowByIdRecetaAndId_farmacia($nro_receta, $id_farmacia);
        $receta = $model_receta->ShowAllById($nro_receta);
        //$recetas = $model_receta->ShowByNroRecetaAndId_farmaciaValida($nro_receta, $id_farmacia);
        $receta = $receta[0];
        if($receta->id_evaluacion!=0):
            $receta = $model_receta->ShowAllByIdEvaluacion($nro_receta);
        	$receta = $receta[0];
        endif;
        //$recetas = $model_receta->ShowByNroRecetaAndId_farmaciaValida($nro_receta, $id_farmacia);
        
/* Aun por validar
        if ($receta->tipo_beneficiario != 'TITULAR'):
            $titular=DB::table('asegurados')
            ->select('asegurados.*')
            ->where('nro_doc_ident',$receta->dni_titular)
            ->first();
            
        else:
            $titular=DB::table('asegurados')
            ->select('asegurados.*')
            ->where('nro_doc_ident',$receta->dni_beneficiario)
            ->first();
               
        endif;
        */
        if($receta->dni_titular == '00000000'):
            $beneficiario = $receta->tipo_beneficiario;
            $receta->tipo_beneficiario = "SOBREVIVIENTE". $beneficiario;
        endif;
        $aseguradb = DB::table('asegurados')
        ->select('asegurados.*')
        ->where('nro_doc_ident',$receta->dni_beneficiario)
        ->first();
      if ($receta->tipo_beneficiario != 'TITULAR'):
          $titular=null;
          $titular=DB::table('asegurados')
          ->select('asegurados.*')
          ->where('id',$aseguradb->id_asegurado_titular)
          ->first();
        
          if(count($titular)==0):
                $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
                $beneficiario = $client->getAseguradoValidate('1', $receta->dni_titular);
                $titular = new Asegurado;
                if (is_object($beneficiario)) :
                    $titular->paterno = utf8_decode($beneficiario->apepatafiliado);
                    $titular->materno = utf8_decode($beneficiario->apematafiliado);
                    $titular->nombre = utf8_decode($beneficiario->nomafiliado);
                else:
                    $titular->paterno = '';
                    $titular->materno = '';
                    $titular->nombre = '';
                endif;
          endif;
          
      else:
          $titular=DB::table('asegurados')
          ->select('asegurados.*')
          ->where('nro_doc_ident',$receta->dni_beneficiario)
          ->first();
             
      endif;
      
      if ($receta->tipo_receta=='EMERGENCIA'):
        if($establecimiento->id==76):
            $model_emergencia = new \App\Models\Emergencia;
            if($receta->id_prestacion!=0):
              $obtenerdato = $model_emergencia->GetObtenerServicioReceta($receta->id_prestacion);
              if($obtenerdato->id_prioridad != 4 ):
                  $receta->servicio= $obtenerdato->servicio;
                  $receta->consultorio= $obtenerdato->consultorio;
              else:
                  $receta->servicio= "IV - PATOLOGÍA AGUDA COMÚN";
                  $receta->consultorio= "CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
              endif;
            else:
              $receta->servicio= "EMERGENCIA";
              $receta->consultorio= "EMERGENCIA";
            endif;
        endif;
      endif;

      /**********************************************************************************************************************************/
    $model_emergencia = new \App\Models\Emergencia;
    //Datos adicionales
    if($receta->id_prestacion!=0):
        $model_prestacion = new \App\Models\Prestaciones;
            $prestacion = $model_prestacion->GetPrestacionByIdDatos($receta->id_prestacion);
        
          $receta->talla =$prestacion->talla_cm;
          $receta->peso =$prestacion->peso_kg;
          $receta->cama = null; 
          $dato_adicional = null; 
        if(Auth::user()->id_farmacia==19): //emergencia

          
          if($prestacion->id_ext!=null && $prestacion->id_tipo_atencion == 2):
            $obtenerdato1 = $model_emergencia->GetObtenerServicioReceta($receta->id_prestacion);
            if($obtenerdato1->id_prioridad != 4 ):
                $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
                $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
                $receta->cama = $datoadicional->cuarto.' - '. $datoadicional->cama;
            else:
                $receta->cama = '';
            endif;
          endif;
        else:
          if(Auth::user()->id_farmacia==128): //hospitalizacion
          
            if($prestacion->id_ext!=null && $prestacion->id_tipo_atencion == 3 ):
                if($receta->id_evaluacion!=0):
                    $model_evaluacion = new \App\Models\Evaluacione;
                    $evaluacion = $model_evaluacion->GetEvaluacionById($receta->id_evaluacion);
                    $dato_adicional = $model_emergencia->GetObtenerCama($evaluacion->cama_estado);
                    $receta->cama = $dato_adicional->cama;
                else:
                    $model_hospitalizacion = new \App\Models\Hospitalizacione;
                    $dato_adicional = $model_hospitalizacion->GetObtenerDatos($prestacion->id_ext);
                    $receta->cama = $dato_adicional->cama;
                endif;
            endif;
          endif;
        endif;
    else:
        $receta->talla = null;
        $receta->peso = null;
        $receta->cama = null; 
    endif;
    
    /**********************************************************************************************************************************/

        $dni_beneficiario = $receta->dni_beneficiario;
        //$receta = $model_receta->GetIdRecetaByNroRecetaValida($nro_receta,$id_farmacia);
        //$id_receta_vale = $receta->id;//Wilian
        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($nro_receta);
        //print_r($medicamentos);exit();
        $productos = $model_receta->ProductosRecetaByIdV2($nro_receta);
        $model_receta_diagnostico = new Receta_diagnostico();
        $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdReceta($nro_receta);
        if (empty($diagnosticos)):
            $diagnosticos = $model_receta_diagnostico->GetDiagnosticosByIdReceta2($nro_receta);
        endif;
        
        $diagnostico_distinct = [];
        foreach ($diagnosticos as $key => $value) {
            if ($key === 0):
                $diagnostico_distinct[$value->codigo] = $value;
            else:
                if (!isset($diagnostico_distinct[$value->codigo])):
                    $diagnostico_distinct[$value->codigo] = $value;
                endif;
            endif;
        }
        $diagnosticos = array_values($diagnostico_distinct);
        
        $n_diagnostico = count($diagnosticos); 
        $cups_pnp = DB::table('prestaciones as p')
                    ->select(DB::raw("
                        CASE
                            WHEN p.id_tipo_atencion = '2' THEN e.cups_pnp
                            WHEN p.id_tipo_atencion = '3' AND (h.cups_pnp IS NOT NULL OR h.cups_pnp != '') THEN h.cups_pnp
                            ELSE p.cups_pnp
                        END as cups,
                        p.id_tipo_atencion as tipo_atencion
                    "))
                    ->join('receta_vales as rv', 'p.id', '=', 'rv.id_prestacion')
                    ->leftJoin('emergencias as e', function ($join) {
                        $join->on('p.id_ext', '=', 'e.id')
                            ->where('p.id_tipo_atencion', '=', '2');
                    })
                    ->leftJoin('hospitalizaciones as h', function ($join) {
                        $join->on('p.id_ext', '=', 'h.id')
                            ->where('p.id_tipo_atencion', '=', '3');
                    })
                    ->where('rv.id', '=', $nro_receta)
                    ->first();

        $es_proc_prog = intval(DB::table('receta_vales')->where('id', $nro_receta)->value('id_procp')) > 0;

        if ($receta->tipo_receta=='HOSPITALIZACION'):
            $pdf = \PDF::loadView('pdf.receta_vale_new', ['medicamentos' => $medicamentos, 'receta' => $receta,'titular'=>$titular ,'establecimiento' => $establecimiento, 'productos' => $productos, 'diagnosticos' => $diagnosticos, 'cups_pnp'=>$cups_pnp,'n_diagnostico'=>$n_diagnostico, 'es_proc_prog'=>$es_proc_prog]);
            //return view('pdf.receta_vale_new', ['medicamentos' => $medicamentos, 'receta' => $receta,'titular'=>$titular ,'establecimiento' => $establecimiento, 'productos' => $productos, 'diagnosticos' => $diagnosticos]);
            if(count($productos)>0):
                // $pdf->setPaper('A4', 'portrait');//vertical
                $pdf->setPaper('A4', 'landscape');//horizontal
            // else:
            //     if((count($diagnosticos))>4):
            //         $pdf->setPaper('A4', 'portrait');//vertical
            //     else:
            //         $pdf->setPaper('A4', 'landscape');//horizontal
            //     endif;
            endif;
        else:
            $pdf = \PDF::loadView('pdf.receta_vale_new_otro', ['medicamentos' => $medicamentos, 'receta' => $receta,'titular'=>$titular ,'establecimiento' => $establecimiento, 'productos' => $productos, 'diagnosticos' => $diagnosticos, 'cups_pnp'=>$cups_pnp, 'es_proc_prog'=>$es_proc_prog]);
            //return view('pdf.receta_vale_new', ['medicamentos' => $medicamentos, 'receta' => $receta,'titular'=>$titular ,'establecimiento' => $establecimiento, 'productos' => $productos, 'diagnosticos' => $diagnosticos]);
            if(count($productos)>6):
                $pdf->setPaper('A4', 'portrait');
            else:
                $pdf->setPaper('A4', 'landscape');
            endif;
        endif;

        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf->stream('receta_vale.pdf');
    }
	
	public function ver_receta_vale($nro_receta) {
        //--------------------------------------------
        //var_dump($n); exit;
        $model_farmacia = new Farmacia();
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        //--------------------------------------------  
        $receta = $model_receta->ShowAllById($nro_receta);
        $receta = $receta[0];
        $dni_beneficiario = $receta->dni_beneficiario;
		$establecimiento = $model_farmacia->GetEstablecimientoPNP($receta->id_farmacia);
        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($nro_receta);

        $productos = $model_receta->ProductosRecetaById($nro_receta);
        $model_receta_diagnostico = new Receta_diagnostico();
        $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdReceta($nro_receta);
		
        $pdf = \PDF::loadView('pdf.vista_receta_vale',['medicamentos'=>$medicamentos,'receta'=>$receta,'establecimiento' =>$establecimiento,'productos'=>$productos,'diagnosticos'=>$diagnosticos]);
        //$pdf->setPaper('A4', 'landscape');
        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf->stream('receta_vale.pdf');
    }
	
	public function ver_prestacion_imprimir($id_prestacion) {
        //--------------------------------------------
        //var_dump($n); exit;
		/*
        $model_farmacia = new Farmacia();
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        //--------------------------------------------  
        $receta = $model_receta->ShowAllById($nro_receta);
        $receta = $receta[0];
        $dni_beneficiario = $receta->dni_beneficiario;
		$establecimiento = $model_farmacia->GetEstablecimientoPNP($receta->id_farmacia);
        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($nro_receta);

        $productos = $model_receta->ProductosRecetaById($nro_receta);
        $model_receta_diagnostico = new Receta_diagnostico();
        $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdReceta($nro_receta);
		*/
		//$modelfarma = new Farmacia;
        //$farmacia = $modelfarma->GetFarmaByUser($id_user);
        //$id_farmacia = $farmacia->id;
        //$flag_receta_dias = $farmacia->flag_receta_dias;
        
		//$model_productos_receta = new Producto_receta;
		$model_prestacion = new \App\Models\Prestaciones;
        //$date = Carbon::now();
        //$fecha_actual = $date->format('d-m-Y');
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
		$diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
		$procedimiento = $model_prestacion->GetProcedimientoByIdPrestacion($id_prestacion);
		//$medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($prestacion->id_receta);

        //return view('frontend.atenciones.ver_atencion', compact('farmacia','prestacion','diagnostico','procedimiento','medicamentos'));
        $pdf = \PDF::loadView('pdf.vista_prestacion',compact('farmacia','prestacion','diagnostico','procedimiento','medicamentos'));
        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf->stream('receta_vale.pdf');
    }
	
	
	public function prestacion_imprimir_openpol($idTransaccion){
		/*
		if (App::environment('desarrollo')):
			$client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_OpenPol.php?wsdl_b", array('login' => 'D5p-R4v-@cci.ws.sp', 'password' => "wS5p#cC1#D5p-"));
		elseif (App::environment('local')):
			$client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_OpenPol.php?wsdl_ ", array('login' => 'D5p-R4v-@cci.ws.sp', 'password' => "wS5p#cC1#D5p-"));
		endif;
		*/
		$client = new SoapClient(env('WI_OPENPOL_URL')."/ws/ws_OpenPol.php?wsdl_c", array('login' => env('WI_OPENPOL_USERNAME'), 'password' => env('WI_OPENPOL_PASSWORD')));
		
		$historia = $client->get_historia($idTransaccion);
		//print_r($historia);exit();
		$paciente = $client->getValoresPaciente($idTransaccion);
		$idEncounter = 0;
		$idPersona = 0;
		$tipo = "";
		foreach($historia as $row){
			if($row->type=="ITEM_TYPE_CONTEXT_ENCOUNTERUID")$idEncounter=$row->value;
			if($row->type=="TRANSACTION_TYPE")$tipo=$row->value;
		}
		//echo $idTransaccion;
		if(isset($paciente[0]->personid))$idPersona = $paciente[0]->personid;
		$atencion = $client->getValoresAtencion($idEncounter);
		$imagen = $client->getValoresImagen($idPersona,$idEncounter,$idTransaccion);
		$laboratorio = $client->getValoresLaboratorio($idPersona,$idEncounter,$idTransaccion);
		$diagnosticoIngreso = $client->getValoresDiagIngreso($idEncounter);
		$diagnosticoActual = $client->getValoresDiagActuales($idTransaccion);
		//print_r($diagnosticoActual);exit();
		/*
		if (App::environment('desarrollo')):
			$client2 = new SoapClient(env('WS_URL_DESARROLLO')."/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => 'D5p-R4v-@cci.ws.sp', 'password' => "wS5p#cC1#D5p-"));
		elseif (App::environment('local')):
			$client2 = new SoapClient(env('WS_URL_REMOTO')."/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => 'D5p-R4v-@cci.ws.sp', 'password' => "wS5p#cC1#D5p-"));
		endif;
		*/
		/*
		$client2 = new SoapClient(env('WI_SALUDPOL_URL')."/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
		
		$dni = "";
		if(isset($paciente[0]->DNI))$dni = $paciente[0]->DNI;
		$asegurado = $client2->getAseguradoValidate("1",$dni);
		*/
		
		$view_pdf = "ver_prestacion_openpol_historia";
		if($tipo=="be.mxs.common.model.vo.healthrecord.IConstants.TRANSACTION_TYPE_HOJA_CLINICA_EMERGENCY")$view_pdf = "ver_prestacion_openpol_emergencia";
		if($tipo=="be.mxs.common.model.vo.healthrecord.IConstants.TRANSACTION_TYPE_HISTORIA_HOSPITALIZA")$view_pdf = "ver_prestacion_openpol_hospitalizacion";
		if($tipo=="be.mxs.common.model.vo.healthrecord.IConstants.TRANSACTION_TYPE_HOJA_CLINICAL_CONSULTORIO")$view_pdf = "ver_prestacion_openpol_consultorio";
		
		if($tipo=="be.mxs.common.model.vo.healthrecord.IConstants.TRANSACTION_TYPE_GINECOLOGIA")$view_pdf = "ver_prestacion_openpol_ginecologia";
		if($tipo=="be.mxs.common.model.vo.healthrecord.IConstants.TRANSACTION_TYPE_EVOLUTION_EMERGENCY_NEW")$view_pdf = "ver_prestacion_openpol_evolucion";
		if($tipo=="be.mxs.common.model.vo.healthrecord.IConstants.TRANSACTION_TYPE_EPICRISIS")$view_pdf = "ver_prestacion_openpol_epicrisis";
		//$view_pdf = "ver_prestacion_openpol_ginecologia";
		//$view_pdf = "ver_prestacion_openpol_evolucion";
		//$view_pdf = "ver_prestacion_openpol_epicrisis";
		
		//$carpeta = date("Y")."/".date("m")."/".date("d");
		//$url_descarga = "https://sgf-prv.saludpol.gob.pe:10451/".$carpeta."/cf_prestacion_openpol_".$idTransaccion.".pdf";
		//$codigo_qr = QrCode::format('png')->size(150)->generate("https://sgf-prv.saludpol.gob.pe:10451/".$carpeta."/cf_prestacion_openpol_".$idTransaccion.".pdf");
		
		$pdf = \PDF::loadView('pdf.'.$view_pdf,compact('historia',/*'asegurado',*/'paciente','atencion','idTransaccion','imagen','laboratorio','diagnosticoIngreso','diagnosticoActual'));
        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf->stream('receta_vale.pdf');
		//file_put_contents($path."prestacion_openpol_".$idTransaccion.".pdf", $pdf->output());
		
	}
	
  public function imprimir_reporte_historia($id_user, $fecha_ini, $fecha_fin, $fecha_reg_ini, $fecha_reg_fin, $dni, $paciente, $estado, $id_servicio, $id_consultorio, $id_medico) 
  {
		/*
        $model_farmacia = new Farmacia();
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($id_farmacia);
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        $receta = $model_receta->ShowByIdRecetaAndId_farmacia($nro_receta, $id_farmacia);
        $receta = $receta[0];
        $dni_beneficiario = $receta->dni_beneficiario;
        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($nro_receta);
        $productos = $model_receta->ProductosRecetaById($nro_receta);
        $model_receta_diagnostico = new Receta_diagnostico();
        $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdReceta($nro_receta);
		*/
		if($dni==0)$dni="";
		if($paciente==0)$paciente="";
		if($id_consultorio=="null")$id_consultorio=0;
		
		if($fecha_ini==0)$fecha_ini="";
		if($fecha_fin==0)$fecha_fin="";
		if($fecha_reg_ini==0)$fecha_reg_ini="";
		if($fecha_reg_fin==0)$fecha_reg_fin="";
		
		$modelfarma = new Farmacia;
		$model_usuario = new User;
		$model_medico = new \App\Models\Medico();
		$model_citas = new \App\Models\Cita();
		$farmacia = $modelfarma->GetFarmaByUser($id_user);
		$id_farmacia = $farmacia->id;
		$modo_validacion = $farmacia->modo_validacion;
		//----------------------------------------------------------------------
		$usuario_actual = $model_usuario->GetById($id_user);
		$flagemergenciareferencia = $usuario_actual->flaghistoria;
		$medico = 0;
		if($flagemergenciareferencia!=1){
			$medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni,$farmacia->id_establecimiento);
			$medico = $medico_actual->id;
		}
		
		$nombre_servicio="";
		$nombre_consultorio="";
		if($id_servicio!=0){
			$servicio = \App\Models\Consultorio::find($id_servicio);
			$nombre_servicio = $servicio->nombre;
		}
		if($id_consultorio!=0){
			$consultorio = \App\Models\SubConsultorio::find($id_consultorio);
			$nombre_consultorio = $consultorio->descripcion;
		}
		
		$p[]=$fecha_ini;
		$p[]=$fecha_fin;
		$p[]=$fecha_reg_ini;
		$p[]=$fecha_reg_fin;
		$p[]=$dni;
		$p[]=$paciente;
		$p[]=$id_servicio;
		$p[]=$id_consultorio;
		$p[]=$id_medico;
		$p[]=Auth::user()->id_establecimiento;
		$p[]=$estado;
		$p[]=1;
		$p[]=10000;
    $citas = $model_citas->GetListaCitasReporte($p);

    $model_establecimiento = new \App\Models\Establecimiento();

    $establecimiento_nombre = '';
    $establecimiento = $model_establecimiento->GetById($usuario_actual->id_establecimiento);
    if(!empty($establecimiento) && count($establecimiento) > 0) {
      $establecimiento_nombre = $establecimiento[0]->nombre;
    }
    unset($establecimiento);

    $pdf = \PDF::loadView('pdf.reporte_historia',compact('citas','fecha_ini','nombre_servicio','nombre_consultorio', 'establecimiento_nombre'));
    $pdf->getDomPDF()->set_option("enable_php", true);
    return $pdf->stream('receta_vale.pdf');
  }
	
    public function imprimir_consolidado_stock_pdf(Request $request,$fecha_inicio, $fecha_fin, $id_user, $id_anaquel, $id_consultorio) {
    	
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $idconsultorio =$request->id_consultorio;

        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $id_anaquel = $request->id_anaquel;
        
        if ($id_anaquel == "") $id_anaquel=0;

        $nombre_producto = $request->nombre_producto;
        $id_producto = $request->id_producto;

        $model_farma = new Farmacia;
        $id_user = Auth::user()->id;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $nombre_farmacia = $farma->nombre;
        $id_producto = $request->id_producto;
        $modelReceta = new Receta_vale();
        $model_kardex = new \App\Models\Kardexe();

        if ($fechaDesde == ""):
            $fechaHasta = $model_kardex->getFechaServidor();
            $fechaDesde = strtotime($fechaHasta);
            $fechaDesde = date('d-m-Y', $fechaDesde); //. " 00:00:00";
            $fechaHasta = $fechaDesde; //. " 23:59:59";
        else:
            $fechaDesde = $fechaDesde;
            $fechaHasta = $fechaHasta;
        endif;
        $modelanaquel= new Anaquele();
        $anaqueles=$modelanaquel->ShowallById_IdFarmacia($id_farmacia);

        //$kardex = $model_kardex->ShowTotalKardexByFarmaciaConsolidado($nombre_farmacia, $fechaDesde, $fechaHasta, $id_anaquel, $idconsultorio);
        $kardex = $model_kardex->ShowTotalKardexByFarmaciaConsolidado($id_farmacia, $fechaDesde, $fechaHasta, $id_anaquel, $idconsultorio);
        
        $model_consultorio = new Consultorio;
        $consultorio= $model_consultorio->ShowConsultoriosAll();
        
		return view('pdf.consolidado_stock_pdf', ['kardex'=>$kardex,'nombre_producto' =>$nombre_producto,'id_producto'=>$id_producto,'fechaDesde' =>$fechaDesde,'fechaHasta'=>$fechaHasta,'anaqueles' =>$anaqueles,'id_anaquel'=>$id_anaquel,'consultorio' =>$consultorio,'idconsultorio'=>$idconsultorio,'nombre_farmacia'=>$nombre_farmacia]);
		
		/*$pdf = \PDF::loadView('pdf.consolidado_stock_pdf',['kardex'=>$kardex,'nombre_producto' =>$nombre_producto,'id_producto'=>$id_producto,'fechaDesde' =>$fechaDesde,'fechaHasta'=>$fechaHasta,'anaqueles' =>$anaqueles,'id_anaquel'=>$id_anaquel,'consultorio' =>$consultorio,'idconsultorio'=>$idconsultorio,'nombre_farmacia'=>$nombre_farmacia]);
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('archivo.pdf');
		*/
    }

	public function imprimir_reporte_productos_por_servicios($id_consultorio,$fecha_inicio, $fecha_fin, $id_farmacia) {
    
        $modelConsultorio = new Consultorio;
        $consultorio = $modelConsultorio->GetNombreById($id_consultorio);
        
        //--------------------------------------------
        $model_stock_farmacias = new App\Models\Stock_farmacia;
        
        $medicamentos = $model_stock_farmacias->GetProductoByServicio($id_farmacia,$id_consultorio,$fecha_inicio,$fecha_fin);
        
        $pdf = \PDF::loadView('pdf.imprimir_reporte_productos_por_servicios',['medicamentos'=>$medicamentos,'fecha_inicio' =>$fecha_inicio,'fecha_fin'=>$fecha_fin,'consultorio' =>$consultorio]);
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('imprimir_reporte_productos_por_servicios.pdf');

    }

    /*
    public function imprimir_hospitalizado_nuevo($nro_hospitalizacion, $id_farmacia, $id_user) {
        //--------------------------------------------
        //var_dump($n); exit;
        $model_farmacia = new Farmacia();
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($id_farmacia);
        
        $nivel_ipress = $model_farmacia->ObtenerNivelFarmacia($id_farmacia);
        $nivel=$nivel_ipress->nivel;

        $model_receta = new Hospitalizacione;

        $model_receta_hospi = new Receta_vale;
        //--------------------------------------------  
        //$receta = $model_receta->ShowByIdHospitalizacionId_farmacia($nro_hospitalizacion, $id_farmacia);
        
//        $admision = $model_receta_hospi->getHospitalizacionById_farmacia($nro_hospitalizacion, $id_farmacia);
        //--------------------------------------------
        $model_usuario = new User;
        $user = $model_usuario->GetById($id_user);

        if($user->id_consultorio==17){
            $id_user_consultorio=$user->id_consultorio;
            $admision = $model_receta_hospi->getHospitalizacionById_farmaciaHemodialisis($nro_hospitalizacion, $establecimiento->id);                
        }
        else{
            $admision = $model_receta_hospi->getHospitalizacionById_farmacia($nro_hospitalizacion, $establecimiento->id);  
            $id_user_consultorio=0;              
        }

        $pdf = \PDF::loadView('pdf.nuevo_hospitalizado',['nivel'=>$nivel,'admision'=>$admision,'establecimiento' =>$establecimiento,'id_user_consultorio'=>$id_user_consultorio]);
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_hospitalizado.pdf');

    }
    */
    public function imprimir_hospitalizado_nuevo($nro_hospitalizacion, $id_farmacia, $id_user) {
        //--------------------------------------------
        //var_dump($n); exit;
        if($id_farmacia==14){
            $id_farmacia = 12;
        }
        
        $model_farmacia = new Farmacia();
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($id_farmacia);
        
        $nivel_ipress = $model_farmacia->ObtenerNivelFarmacia($id_farmacia);
        $nivel=$nivel_ipress->nivel;

        $model_receta = new Hospitalizacione;

        $model_receta_hospi = new Receta_vale;
        //--------------------------------------------  
        //$receta = $model_receta->ShowByIdHospitalizacionId_farmacia($nro_hospitalizacion, $id_farmacia);
        
        //$admision = $model_receta_hospi->getHospitalizacionById_farmacia($nro_hospitalizacion, $id_farmacia);
        //--------------------------------------------
        $model_usuario = new User;
        $user = $model_usuario->GetById($id_user);

        if($user->id_consultorio==17){
            $id_user_consultorio=$user->id_consultorio;
            $admision = $model_receta_hospi->getHospitalizacionById_farmaciaHemodialisis($nro_hospitalizacion, $id_farmacia);                
        }
        else{
            $admision = $model_receta_hospi->getHospitalizacionById_farmacia($nro_hospitalizacion, $id_farmacia);  
            $id_user_consultorio=0;              
        }

        //$recetas = $model_receta->ShowByNroRecetaAndId_farmaciaValida($nro_receta, $id_farmacia);
        //$admision = $receta[0];
        //$dni_beneficiario = $receta->dni_beneficiario;
/*        
        $view = \View::make('pdf.nuevo_hospitalizado', compact('admision', 'establecimiento'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $paper_size = array(20, 0, 565, 400);
        $pdf->loadHTML($view)->setPaper($paper_size);
        return ($pdf->stream());

*/

        $pdf = \PDF::loadView('pdf.nuevo_hospitalizado',['nivel'=>$nivel,'admision'=>$admision,'establecimiento' =>$establecimiento,'id_user_consultorio'=>$id_user_consultorio]);
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_hospitalizado.pdf');

    }

    public function imprimir_nuevo_alta($id_hospitalizacion, $id_farmacia) {
        //--------------------------------------------
        //var_dump($n); exit;
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $model_farmacia = new Farmacia();
        $model_receta = new Receta_vale;
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($id_farmacia);
        
        //$model_receta = new Hospitalizacione;
        //--------------------------------------------  
        //$receta = $model_receta->ShowByIdAltaPacienteId_farmacia($id_hospitalizacion, $id_farmacia);
        //$recetas = $model_receta->getHospitalizacionAltaById_farmacia($id_hospitalizacion, $id_farmacia);
        //$recetas = $model_receta->ShowByNroRecetaAndId_farmaciaValida($nro_receta, $id_farmacia);
        $id_user_consultorio = Auth::user()->id_consultorio;
        if($id_user_consultorio==17){
            //$id_user_consultorio=$user->id_consultorio;
            $recetas = $model_receta->getHospitalizacionById_farmaciaAltaHemodialisis($id_hospitalizacion, $establecimiento->id);                
        }
        else{
            $recetas = $model_receta->getHospitalizacionAltaById_farmacia($id_hospitalizacion, $establecimiento->id);                
            $id_user_consultorio=0;
        }
        $admision = $recetas[0];
       
        $nivel_farmacia = $model_farmacia->ObtenerNivelFarmacia($id_farmacia);
        $nivel=$nivel_farmacia->nivel;
        //$dni_beneficiario = $receta->dni_beneficiario;
        $view = \View::make('pdf.alta_paciente', compact('admision', 'nivel','establecimiento','id_user_consultorio'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $paper_size = array(20, 0, 565, 400);
        $pdf->loadHTML($view)->setPaper($paper_size);
        
        return ($pdf->stream());
    }

    public function imprimir_admision($nro_admision,$id_farmacia) {
        //--------------------------------------------
		$model_farmacia= new Farmacia();
        $establecimiento=$model_farmacia->GetEstablecimientoPNP($id_farmacia);
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        //--------------------------------------------  
        //$receta = $model_receta->ShowByNroRecetaAndId_farmacia($nro_receta, $id_farmacia);
		$admision = $model_receta->getAdmisionByNroAdmisionAndId_farmacia($nro_admision, $id_farmacia);
		$admision = $admision[0];
		//$dni_beneficiario=$admision->dni_beneficiario;
        //$id_receta_vale = $receta->id;
        //$medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($id_receta_vale);
		/*if(App::environment('desarrollo')):
        	$client = new SoapClient("http://wsdl.saludpol.gob.pe:8083/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		elseif(App::environment('local')):   
        	$client = new SoapClient("https://wadl.saludpol.gob.pe:20083/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		endif;
		*/
        //$beneficiario = $client->get_busafi_activo($dni_beneficiario);
		//$productos=$model_receta->ProductosRecetaById($id_receta_vale);
        $view = \View::make('pdf.admision', compact('medicamentos','admision','establecimiento','beneficiario','productos'))->render();
        $pdf = \App::make('dompdf.wrapper');  
        $paper_size = array(20,0,565,400);
        $pdf->loadHTML($view)->setPaper($paper_size);
        return ($pdf->stream());         
    }
	
	public function imprimir_centro_quirurgico($nro_quirurgico,$id_farmacia) {
        //--------------------------------------------
		$model_farmacia= new Farmacia();
        $establecimiento=$model_farmacia->GetEstablecimientoPNP($id_farmacia);
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        //--------------------------------------------  
		//$admision = $model_receta->getAdmisionByNroAdmisionAndId_farmacia($nro_admision, $id_farmacia);
		$admision = $model_receta->getQuirurgicoByNroQuirurgicoAndId_farmacia($nro_quirurgico, $id_farmacia);
		$admision = $admision[0];
        $view = \View::make('pdf.centro_quirurgico', compact('medicamentos','admision','establecimiento','beneficiario','productos'))->render();
        $pdf = \App::make('dompdf.wrapper');  
        $paper_size = array(20,0,565,400);
        $pdf->loadHTML($view)->setPaper($paper_size);
        return ($pdf->stream());         
    }
	/*
	public function imprimir_transferencia($id) {
		$id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;

        $model_transferencia = new \App\Models\Transferencia;
		$model_farmacia = new Farmacia;
        
		$productos = $model_transferencia->ShowLotesTransferidosById_transferencia_nuevo($id);
        $buscar_tipo_transferencia = Transferencia::where('id', '=', $id)->first();
		
        //dd($buscar_tipo_transferencia);
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
        
        
        if($buscar_tipo_transferencia->tipo==1){
            $transferencia = $model_transferencia->GetTransferenciaImprimirById($id);
        }
        else
        {
            if($buscar_tipo_transferencia->tipo==2){                
                $transferencia = $model_transferencia->GetTransferenciaImprimirById($id);
            }
            else
            {
                if($buscar_tipo_transferencia->tipo==3){
                    if($buscar_tipo_transferencia->id_farmacia_destino==0){
                        $transferencia = $model_transferencia->GetTransferenciaEstablecimientoImprimirById($id);
                    }
                    else
                    {
                        $transferencia = $model_transferencia->GetTransferenciaImprimirById($id);
                    }

                }
                else
                {
                    $transferencia = $model_transferencia->GetTransferenciaDependenciaImprimirById($id);
                }
            }
        }       
        
		if($farmacia->almacen==1){
			$pdf = \PDF::loadView('pdf.pecosa_almacen',['transferencia'=>$transferencia,'productos'=>$productos,'id_farmacia' =>$id_farmacia,'almacen'=>$farmacia->almacen]);
			$pdf->getDomPDF()->set_option("enable_php", true);
			$pdf->setPaper('A4', 'portrait');
		}
		else
		{             
			$pdf = \PDF::loadView('pdf.transferencia',['transferencia'=>$transferencia,'productos'=>$productos,'id_farmacia' =>$id_farmacia]);
			$pdf->getDomPDF()->set_option("enable_php", true);
			$pdf->setPaper('A4', 'landscape');
		}
		
        return $pdf->stream('transferencia.pdf');       
    }
	*/
	
	public function imprimir_transferencia($id, $tipo = 0) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
		$id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;

        $model_transferencia = new \App\Models\Transferencia;
		$model_farmacia = new Farmacia;
        
		$productos = $model_transferencia->ShowLotesTransferidosById_transferencia_nuevo($id);
        $buscar_tipo_transferencia = Transferencia::where('id', '=', $id)->first();
		
        //dd($buscar_tipo_transferencia);
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
        
        
        if($buscar_tipo_transferencia->tipo==1){
            $transferencia = $model_transferencia->GetTransferenciaImprimirById($id);
        }
        else
        {
            if($buscar_tipo_transferencia->tipo==2){                
                $transferencia = $model_transferencia->GetTransferenciaImprimirById($id);
            }
            else
            {
                if($buscar_tipo_transferencia->tipo==3){
                    if($buscar_tipo_transferencia->id_farmacia_destino==0){
                        $transferencia = $model_transferencia->GetTransferenciaEstablecimientoImprimirById($id);
                    }
                    else
                    {
                        $transferencia = $model_transferencia->GetTransferenciaImprimirById($id);
                    }

                }
                else
                {
                    $transferencia = $model_transferencia->GetTransferenciaDependenciaImprimirById($id);
                }
            }
        }       
        
        
        if($tipo==1){
    		return view('pdf.print_pecosa_almacen', compact('transferencia', 'productos', 'id_farmacia', 'almacen'));
        }
        else
        {
            if($farmacia->almacen==1){
                $pdf = \PDF::loadView('pdf.pecosa_almacen',['transferencia'=>$transferencia,'productos'=>$productos,'id_farmacia' =>$id_farmacia,'almacen'=>$farmacia->almacen]);
                $pdf->getDomPDF()->set_option("enable_php", true);
                $pdf->setPaper('A4', 'portrait');
            }
            else
            {             
                $pdf = \PDF::loadView('pdf.transferencia',['transferencia'=>$transferencia,'productos'=>$productos,'id_farmacia' =>$id_farmacia]);
                $pdf->getDomPDF()->set_option("enable_php", true);
                $pdf->setPaper('A4', 'landscape');
            }
            return $pdf->stream('transferencia.pdf');
        }
        
    }
	
	
	public function imprimir_nota_entrada($id) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
		$id_user = Auth::user()->id;
                $model_transferencia = new \App\Models\Transferencia;
		$model_farmacia = new Farmacia;
		$productos = $model_transferencia->ShowLotesTransferidosById_transferencia_nuevo($id);
                //exit(print_r($productos));
		$transferencia = $model_transferencia->GetTransferenciaImprimirById($id);
		$farmacia = $model_farmacia->GetFarmaByUser($id_user);
		$flag = $model_farmacia->ExisteFarmaciaInternaorExterna($farmacia->id_establecimiento,$transferencia->id_farmacia_destino);
                $tiporep = '1';
        
                //exit(print_r($transferencia));
        //$view = \View::make('pdf.nea', compact('transferencia','productos','flag', 'tiporep'))->render();
        $view = \View::make('pdf.nea', compact('transferencia','productos','flag', 'tiporep'))->render();
        

        $pdf = \App::make('dompdf.wrapper');  
        $paper_size = array(20,0,565,400);
        $pdf->loadHTML($view)->setPaper($paper_size);
        return ($pdf->stream());         
    }
	
	public function imprimir_nota_entrada_pecosa($id) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $model_pecosa = new Guia_entrada();
        $transferencia = $model_pecosa->GetPecosaByIdPecosa($id);
        $productos = $model_pecosa->ShowProductoByPecosa($id);
        
        $id_user = Auth::user()->id;
        $flag = '';
        $tiporep = '2';
        
        $view = \View::make('pdf.nea', compact('transferencia','productos','flag', 'tiporep'))->render();
        $pdf = \App::make('dompdf.wrapper');  
        $paper_size = array(20,0,565,400);
        $pdf->loadHTML($view)->setPaper($paper_size);
        return ($pdf->stream());         
    }

    public function print_nota_entrada($id) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $model_pecosa = new Guia_entrada();
        $transferencia = $model_pecosa->GetPecosaByIdNotaEntrada($id);
        $productos = $model_pecosa->ShowProductoByPecosa($id);
        //dd($transferencia);
        $id_user = Auth::user()->id;
        $flag = '';
        $tiporep = '2';
        $view = \View::make('pdf.nota_entrada', compact('transferencia','productos','flag', 'tiporep'))->render();
        $pdf = \App::make('dompdf.wrapper');  
        $paper_size = array(20,0,565,400);
        $pdf->loadHTML($view)->setPaper($paper_size);
        return ($pdf->stream());     
    }
	/*
    public function print_nota_entrada_almacen($id) {
		
		$model_pecosa = new Guia_entrada();
        $transferencia = $model_pecosa->GetPecosaByIdNotaEntradaAlmacen($id);
        $productos = $model_pecosa->ShowProductoByPecosa($id);
        
        if($transferencia->concepto==1 || $transferencia->concepto==9 || $transferencia->concepto==12){
                $model_dependencia = new Dependencia;
                $destino = $model_dependencia->getTodasDependencias();
            }
            else
            {
                if($transferencia->concepto==14 || $transferencia->concepto==15){
                    $model_proveedor = new Proveedore;
                    $destino = $model_proveedor->getProveedorActivo();
                }
                else
                {
                    $model_establecimiento = new Establecimiento;
                    $destino = $model_establecimiento->ShowAll();
                }
            }   

        $id_user = Auth::user()->id;
        $model_farmacia = new Farmacia;
        $hoy = date('Y-m-j');
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
        $flag = '';
        $tiporep = '2';
		$model_farmacia = new Farmacia;
        $hoy = date('Y-m-j');
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
		
		if($transferencia->concepto==11){
            $model_establecimiento = new Establecimiento;
            $destino = $model_establecimiento->ShowAll();
        }
        else
        {
            if($transferencia->concepto==7 || $transferencia->concepto==8){
                $model_proveedor = new Proveedore;
                $destino = $model_proveedor->getProveedorActivo();
            }
            else
            {
                $model_dependencia = new Dependencia;
                $destino = $model_dependencia->getTodasDependencias();
            }
        }
		
        //$view = \View::make('pdf.nota_entrada_almacen', compact('transferencia','productos','flag', 'tiporep','destino','farmacia'))->render();
        //$pdf = \App::make('dompdf.wrapper');  
        //$paper_size = array(20,0,565,400);
        //$pdf->loadHTML($view)->setPaper($paper_size);
		$pdf = \PDF::loadView('pdf.nota_entrada_almacen',['transferencia'=>$transferencia,'productos'=>$productos,'flag' =>$flag,'tiporep'=>$tiporep,'destino'=>$destino,'farmacia'=>$farmacia]);
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
		
        return ($pdf->stream());
		
    }
	*/

    public function print_nota_entrada_almacen($id,$tipo) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
		/*
        $model_pecosa = new Guia_entrada();
        $transferencia = $model_pecosa->GetPecosaByIdNotaEntradaAlmacen($id);
        $productos = $model_pecosa->ShowProductoByPecosa($id);
        
        $id_user = Auth::user()->id;
        $flag = '';
        $tiporep = '2';
        $view = \View::make('pdf.nota_entrada_almacen', compact('transferencia','productos','flag', 'tiporep'))->render();
        $pdf = \App::make('dompdf.wrapper');  
        $paper_size = array(20,0,565,400);
        $pdf->loadHTML($view)->setPaper($paper_size);
        return ($pdf->stream());
		*/
		
		$model_pecosa = new Guia_entrada();
        $transferencia = $model_pecosa->GetPecosaByIdNotaEntradaAlmacen($id);
        $productos = $model_pecosa->ShowProductoByPecosa($id);
        
        if($transferencia->concepto==1 || $transferencia->concepto==9 || $transferencia->concepto==12){
                $model_dependencia = new Dependencia;
                $destino = $model_dependencia->getTodasDependencias();
            }
            else
            {
                if($transferencia->concepto==14 || $transferencia->concepto==15){
                    $model_proveedor = new Proveedore;
                    $destino = $model_proveedor->getProveedorActivo();
                }
                else
                {
                    $model_establecimiento = new Establecimiento;
                    $destino = $model_establecimiento->ShowAll();
                }
            }   

        $id_user = Auth::user()->id;
        $model_farmacia = new Farmacia;
        $hoy = date('Y-m-j');
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
        $flag = '';
        $tiporep = '2';
		$model_farmacia = new Farmacia;
        $hoy = date('Y-m-j');
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
		
		if($transferencia->concepto==11){
            $model_establecimiento = new Establecimiento;
            $destino = $model_establecimiento->ShowAll();
        }
        else
        {
            if($transferencia->concepto==7 || $transferencia->concepto==8){
                $model_proveedor = new Proveedore;
                $destino = $model_proveedor->getProveedorActivo();
            }
            else
            {
                $model_dependencia = new Dependencia;
                $destino = $model_dependencia->getTodasDependencias();
            }
        }
		
        //$view = \View::make('pdf.nota_entrada_almacen', compact('transferencia','productos','flag', 'tiporep','destino','farmacia'))->render();
        //$pdf = \App::make('dompdf.wrapper');  
        //$paper_size = array(20,0,565,400);
        //$pdf->loadHTML($view)->setPaper($paper_size);
		if($tipo==1)        
        {   return view('pdf.print_nota_entrada_almacen', compact('transferencia', 'productos', 'flag', 'tiporep', 'destino', 'farmacia'));}
        else
        {
            $pdf = \PDF::loadView('pdf.nota_entrada_almacen',['transferencia'=>$transferencia,'productos'=>$productos,'flag' =>$flag,'tiporep'=>$tiporep,'destino'=>$destino,'farmacia'=>$farmacia]);
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            
            return ($pdf->stream());
        }
    }
	
	
	public function imprimir_requerimiento_pdf($id) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $model_requerimiento = new Requerimiento();
        $requerimiento = $model_requerimiento->GetRequerimientoById($id);
        $model_req_producto = new Req_producto();
        $productosbyrequerimientos = $model_req_producto->ProductosByRequerimiento($id);
        $model_farmacia = new Farmacia();
        $id_farmacia = $requerimiento->id_farmacia;
        $id_farmacia_destino = $requerimiento->id_destino;
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($id_farmacia);
        $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);
        $farmacia2 = $model_farmacia->GetFarmaByIdfarma($id_farmacia_destino);
            
        $id_user = Auth::user()->id;
        $flag = '';
        $tiporep = '2';
		$nombre_servicio = '';
        if(Auth::user()->id_consultorio!=''):
        	$servicio = new Consultorio();
        	$nombre_servicio = $servicio->GetNombreById(Auth::user()->id_consultorio)->nombre;
		endif;
		       
        $pdf = \PDF::loadView('pdf.requerimiento',['requerimiento'=>$requerimiento,
                      'farmacia'=>$farmacia,'farmacia2'=>$farmacia2,'establecimiento' =>$establecimiento,
                      'productosbyrequerimientos'=>$productosbyrequerimientos,'nombre_servicio'=>$nombre_servicio]);
        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf->stream('requerimiento.pdf');

    }
    
    public function imprimir_receta_cirugia($id_receta,$id_farmacia) {      
        $model_farmacia= new Farmacia();
        $establecimiento=$model_farmacia->GetEstablecimientoPNP($id_farmacia);        
        $model_receta = new Receta_vale;        
        $receta=$model_receta->DatosRecetaById($id_receta);
        $dni_beneficiario=$receta->dni_beneficiario;
		/*
		if(App::environment('desarrollo')):
        	$client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		elseif(App::environment('local')):   
        	$client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		endif;
		*/
		$client = new SoapClient(env('WI_SALUDPOL_URL')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));
		
        $beneficiario = $client->get_busafi_activo($dni_beneficiario);
        $productos=$model_receta->ProductosRecetaById($id_receta);
        $diagnosticos=$model_receta->DiagnosticosRecetaById($id_receta);
        $procedimientos=$model_receta->ProcedimientosRecetaById($id_receta);
        $cirujanos=$model_receta->CirujanosRecetaById($id_receta);
        $enfermera_principal=$model_receta->EnfermerPrincipalRecetaById($id_receta);
        $view = \View::make('pdf.receta_cirugia', compact('enfermera_principal','beneficiario','receta','productos','diagnosticos','cirujanos','procedimientos','establecimiento'))->render();                        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return ($pdf->stream());           
    }
    
    public function imprimir_receta_cirugia_valorizado($id_receta,$id_farmacia) {          
        $model_farmacia= new Farmacia();
        $farmacia=$model_farmacia->GetFarmaByIdfarma($id_farmacia);
        $establecimiento=$model_farmacia->GetEstablecimientoPNP($id_farmacia);
        $model_receta = new Receta_vale;        
        $receta=$model_receta->DatosRecetaById($id_receta);
        $fecha_registro=$receta->fecha_registro;                
        $dni_beneficiario=$receta->dni_beneficiario;
		/*
		if(App::environment('desarrollo')):
        $client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		elseif(App::environment('local')):
        $client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		endif;
		*/
		$client = new SoapClient(env('WI_SALUDPOL_URL')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));
		
        $beneficiario = $client->get_busafi_activo($dni_beneficiario);
        $productos=$model_receta->ProductosRecetaById($id_receta);
        $pu_productos=$model_receta->PUdeProductosSegunKardex($farmacia->nombre, $fecha_registro);
        $diagnosticos=$model_receta->DiagnosticosRecetaById($id_receta);
        $procedimientos=$model_receta->ProcedimientosRecetaById($id_receta);
        $cirujanos=$model_receta->CirujanosRecetaById($id_receta);
        $enfermera_principal=$model_receta->EnfermerPrincipalRecetaById($id_receta);
        $view = \View::make('pdf.receta_cirugia_valorizado', compact('enfermera_principal','pu_productos','beneficiario','receta','productos','diagnosticos','cirujanos','procedimientos','establecimiento'))->render();                        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return ($pdf->stream());           
    }
    
    public function print_examen_laboratorio($id_laboratorio, $tipo_examen) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_laboratorio = new \App\Models\Laboratorio;        

        $laboratorio = $model_laboratorio->GetLaboratorioById($id_laboratorio);
        $id_prestacion=$laboratorio->id_prestacion;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $proclaboratorio = $model_laboratorio->GetProcedimientosByIdLaboratorio($id_prestacion,$tipo_examen,$id_laboratorio);
        $resultado_examen = $model_laboratorio->MostrarResultadoExamen($id_laboratorio,$tipo_examen);
        
        $pdf = \PDF::loadView('pdf.examen_laboratorio',compact('prestacion','diagnostico','laboratorio','proclaboratorio','resultado_examen','tipo_examen'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('examen.pdf');
    }

    public function print_examen_laboratorio_lns($id_laboratorio, $dni_beneficiario, $dia_toma_muestra, $mes_toma_muestra, $anio_toma_muestra, $nro_orden)
    {
        $ws_laboratorio = new \App\Ws\LaboratorioLNS();
        $fecha_toma_muestra = $anio_toma_muestra . $mes_toma_muestra . $dia_toma_muestra;
        $fecha_toma_muestra2 = $dia_toma_muestra . '/' . $mes_toma_muestra . '/' . $anio_toma_muestra;
        $asegurado = new \App\Models\Asegurado();
        $laboratorio_resultado = new \App\Models\Laboratorio_resultado;
        $asegurado_data =  $asegurado->busca_datos_asegurados2($dni_beneficiario, 76);
        //tabla de solicitudes
        $resultados_lab = $ws_laboratorio->CallVistaResultadosLaboratorio($dni_beneficiario, $fecha_toma_muestra, $nro_orden);

        if (isset($resultados_lab->ResultadosLabView)) {
        } else {
            return redirect()->route('config.mentenimiento')->with('error', 'La ws laboratorio no disponible.');
        }

        $viewData = $resultados_lab->ResultadosLabView;

        // Si es objeto único, lo convertimos en array
        if (is_object($viewData)) {
            $resultados_lab->ResultadosLabView = [$viewData];
        }

        $existeResultado = $resultados_lab->ResultadosLabView;

        // Ahora accedemos seguro
        if (isset($resultados_lab->ResultadosLabView) && $resultados_lab->ResultadosLabView[0]->cod_mensaje == '00') {
            // todo OK
        } else {
            return redirect()->route('config.mentenimiento')->with('error', 'La ws laboratorio no disponible.');
        }

        $examenes = $this->AgruparExamenWs($resultados_lab, $resultados_lab->ResultadosLabView);

        $toma_muestra_fecha_primera = DB::table('laboratorios')->where('cod_orden_lab', $nro_orden)->value(DB::raw("to_char(fecha_registro_toma_muestra, 'DD/MM/YYYY HH24:MI:SS')"));

        if (!empty($resultados_lab->ResultadosLabView)):
            /*if ($id_laboratorio > 0):
                $model_prestacion = new \App\Models\Prestaciones;
            endif;

            $model_laboratorio = new \App\Models\Laboratorio;*/
            $resultados = null;
            /*$servicios = null;
            $laboratorio = null;
            $prestacion = null;*/

            if (false):
                if ($res == 'Y'):
                    if ($id_laboratorio > 0):
                        $laboratorio = $model_laboratorio->GetLaboratorioById($id_laboratorio);
                        $id_prestacion = $laboratorio->id_prestacion;
                        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                    endif;
                    $existeResultado = $laboratorio_resultado->ExisteResultado($dni_beneficiario, $fecha_toma_muestra2, $nro_orden);

                    $pdf = \PDF::loadView('pdf.examen_laboratorio_lns', compact('id_laboratorio', 'prestacion', 'laboratorio', 'resultados', 'servicios', 'asegurado_data'))->setPaper('a4', 'portrait');
                    $pdf->getDomPDF()->set_option("enable_php", true);
                    $pdfContent = $pdf->output(); // Obtiene el contenido del PDF
                    $pdfBase64 = base64_encode($pdfContent);
                    $laboratorio = $model_laboratorio->GetLaboratorioById($id_laboratorio);
                    $id_prestacion = $laboratorio->id_prestacion;
                    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                    $lista_rua = $model_laboratorio->getVerRuaConItemPorIdPrestacion($prestacion->nivel_estable, $prestacion->id_tipo_atencion, $prestacion->id_consultorio, $prestacion->id, $id_laboratorio);

                    return view('frontend.laboratorios.resultado_laboratorio', compact('pdfBase64', 'examenes', 'existeResultado'));
                else:
                    echo "Error: No se ha podido guardar los resultados.";
                    exit();
                endif;
            else:

                $data_reducida = array_map(function ($row) {
                    return (object)[
                        'usr_name'    => $row->usr_name,
                        'h_numero'    => $row->h_numero,
                        'o_numero'    => $row->o_numero,
                        'g_nombre'    => $row->g_nombre,
                        'usr_login'   => $row->usr_login,
                        'g_orden_imp' => $row->g_orden_imp
                    ];
                }, $resultados_lab->ResultadosLabView);

                $unicos = [];
                foreach ($data_reducida as $row) {
                    $clave = $row->o_numero . '|' . $row->g_nombre . '|' . $row->usr_login . '|' . $row->g_orden_imp;
                    // si esa clave aún no existe, lo guardamos
                    $unicos[$clave] = $row;
                }

                $unicos = array_values($unicos);//dd($unicos);

                usort($unicos, function ($a, $b) {
                    if (intval($a->g_orden_imp) == intval($b->g_orden_imp)) return 0;
                    return (intval($a->g_orden_imp) < intval($b->g_orden_imp)) ? -1 : 1;
                });

                $group_servicio_profesional = $unicos;

                // $resultados = $resultados_lab->ResultadosLabView;

                $resultados = array_map(function ($row) {
                    return (object) [
                      'e_cod_alterno' => $row->e_cod_alterno,
                      'e_nombre' => $row->e_nombre,
                      'p_nombre' => $row->p_nombre,
                      'l_resultado' => $row->l_resultado,
                      'rango_referencial_ant' => !empty($row->ra_texto) ?
                        $row->ra_texto :
                        $row->l_ref_inf . " - " . $row->l_ref_sup,
                      'l_unidades' => $row->l_unidades,
                      'g_nombre' => $row->g_nombre,
                      'usr_name' => $row->usr_name,
                      'compuesto' => $row->l_resultcomp,
                      'p_comentario' => $row->p_comentario,
                      'l_ref_inf' => $row->l_ref_inf,
                      'l_ref_sup' => $row->l_ref_sup,
                      'ra_texto' => $row->ra_texto,
                      'l_fecha_val' => $row->l_fecha_val,
                      'l_hora_val' => $row->l_hora_val,
                      'o_profesional_solicita' => $row->o_profesional_solicita,
                      'o_observacion_val' => $row->o_departamento_solicita,
                      'comentarios' => "",
                      'rango_referencial' => $row->l_ralt_id == "0" ?
                        $row->l_ref_inf . " - " . $row->l_ref_sup :
                        $row->ra_texto,
                      'usr_login' => $row->usr_login,
                      'usr_image' => $row->usr_image,
                      'l_fecha_crea' => $row->l_fecha_crea,
                      'l_hora_crea' => $row->l_hora_crea,
                      'o_numero' => $row->o_numero,
                      'l_fecha' => $row->l_fecha,
                      'h_nombres' => $row->h_nombres,
                      's_nombre' => $row->s_nombre,
                      'h_fecha_nacimiento' => $row->h_fecha_nacimiento,
                      'o_fecha_toma' => $row->o_fecha_toma,
                      'h_sexo' => $row->h_sexo,
                      'h_numero' => $row->h_numero,
                      'p_item_comentario' => $row->l_notavalidacion,
                      'pr_descripcion' => $row->pr_descripcion,
                      'codigo_proveedor' => $row->sistema
                    ];
                  }, $resultados_lab->ResultadosLabView);
            endif;
        else:
            echo "No hay resultados para mostrar.";
            exit();
        endif;

        $pdf = \PDF::loadView('pdf.examen_laboratorio_lns', compact('resultados', 'group_servicio_profesional', 'asegurado_data', 'toma_muestra_fecha_primera'))->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        // return $pdf->stream('lab.pdf');
        $pdfContent = $pdf->output(); // Obtiene el contenido del PDF
        $pdfBase64 = base64_encode($pdfContent);

        return view('frontend.laboratorios.resultado_laboratorio', compact('pdfBase64', 'examenes', 'existeResultado'));
    }



  public function AgruparExamenWs($resultados_lab,$existeResultado){
    $solicitudes_agrupadas = [];
              // Verificar si es un array, si no lo es, convertirlo
              if (!is_array($resultados_lab->ResultadosLabView)) {
                $resultados_lab->ResultadosLabView = [ $resultados_lab->ResultadosLabView];
              }
              foreach ($resultados_lab->ResultadosLabView as $solicitud) {
                // dd($solicitud);
                  $cod_alterno = $solicitud->e_cod_alterno;
              
                  // Si no existe en el array agrupado, inicializamos
                  if (!isset($solicitudes_agrupadas[$cod_alterno])) {
                      $solicitudes_agrupadas[$cod_alterno] = [
                          "nombre" => $solicitud->e_nombre,
                          "cod_alterno" => $cod_alterno,
                          "estado" => "Sin resultado" // Estado por defecto
                      ];
                  }
              }
              
            // Crear un array de códigos alternos que tienen resultados
            $codigos_con_resultado = [];

            foreach ($existeResultado as $resultado) {
                $codigos_con_resultado[] = $resultado->e_cod_alterno;
            }

            // Actualizar el estado en `$solicitudes_agrupadas`
            foreach ($solicitudes_agrupadas as $cod_alterno => &$examen) {
                if (in_array($cod_alterno, $codigos_con_resultado)) {
                  /*if ($cod_alterno != "87340" && $cod_alterno != "86705" && $cod_alterno != "86709" 
                    && $cod_alterno != "86803" && $cod_alterno != "86645" && $cod_alterno != "86762.01" 
                    && $cod_alterno != "86778" && $cod_alterno != "86703" && $cod_alterno != "86593"):*/
                    $examen["estado"] = "Con resultado";
                  /*else:
                    $examen["estado"] = "Consultar Resultado con Laboratorio";
                  endif;*/
                }
            }

            // Convertir a formato de objetos para la respuesta final
            $examenes = array_values(array_map(function ($examen) {
                return (object) $examen;
            }, $solicitudes_agrupadas));
          

          return $examenes;
  }

    public function print_examen_radiologia($id_radiologia) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        

        $radiologia = $model_radiologia->GetRadiologiaByUser($id_radiologia);
        
        $id_prestacion=$radiologia->id_prestacion;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_radiologia->GetProcedimientosByIdRadiologia($id_prestacion,$id_radiologia);
        $pdf = \PDF::loadView('pdf.examen_radiologia',compact('prestacion','diagnostico','radiologia','procradiologia'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('examen_radiologia.pdf');
    }

    public function print_referencia($id_prestacion, $id_referencia) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_referencia = new \App\Models\Referencia;        

        $referencia = $model_referencia->GetReferenciaById($id_prestacion,$id_referencia);
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);

        $pdf = \PDF::loadView('pdf.referencia',compact('prestacion','diagnostico','referencia','procedimiento','id_referencia'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('referencia.pdf');
    }

    public function ver_pdf_referencia($id_prestacion, $id_referencia) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_referencia = new \App\Models\Referencia;        
        $referencia = $model_referencia->GetReferenciaById($id_prestacion,$id_referencia);
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $asegurado = DB::table('asegurados')
        ->select('asegurados.*')
        ->where('id',$referencia->id_asegurado)
        ->first();  
        $medicos = DB::table('medicos')
        ->select('medicos.*')
        ->where('id',$prestacion->id_medico)
        ->first();
        $users = DB::table('users')
        ->select('users.*')
        ->where('dni',$medicos->dni)
        ->first();
        $profesion = DB::table('profesiones')
        ->select('profesiones.*')
        ->where('id',$medicos->id_profesion)
        ->first();
        $medico = $medicos->paterno . ' ' . $medicos->materno . ', ' . $medicos->nombre;
        if($asegurado->id_distrito === null){
            $nombre_distrito = '';
        }else{
            $distrito = DB::table('distritos')
            ->select('distritos.*')
            ->where('id_distrito',$asegurado->id_distrito)
            ->first();
            $nombre_distrito = $distrito->descripcion;
        }
        if($asegurado->id_departamento === null){
            $nombre_departamentos = '';
        }else{
            $departamentos = DB::table('departamentos')
            ->select('departamentos.*')
            ->where('id_departamento',$asegurado->id_departamento)
            ->first();
            $nombre_departamentos = $departamentos->descripcion;
        }
        $fecha_nac_str = $asegurado->fecha_nac;
        list($dia_nac, $mes_nac, $anios_nac) = explode('/', $fecha_nac_str);
        $dia_actual = date('j');
        $mes_actual = date('n');
        $anios_actual = date('Y');
        $anios = $anios_actual - $anios_nac;
        if ($mes_actual < $mes_nac || ($mes_actual == $mes_nac && $dia_actual < $dia_nac)) {
            $anios--;
        }
        $meses = $mes_actual - $mes_nac;
        if ($meses < 0) {
            $meses += 12; 
        }
        $dias = $dia_actual - $dia_nac;
        if ($dias < 0) {
            $meses--; 
            if ($mes_actual == 1) {
                $ultimo_dia_mes_anterior = date('t', strtotime(($anios_actual - 1) . '-12-01'));
            } else {
                $ultimo_dia_mes_anterior = date('t', strtotime($anios_actual . '-' . ($mes_actual - 1) . '-01'));
            }
            $dias += $ultimo_dia_mes_anterior; 
        }
        if ($meses < 0) {
            $meses += 12; 
            $anios--; 
        }

        $prestacion_datalle = DB::table('prestaciones')
        ->select('prestaciones.*')
        ->where('id',$id_prestacion)
        ->first();

        $vitales = "SELECT cf.descripcion as denominacion,nve.* FROM enfermerias e 
        inner join notas_vitales_enfermerias nve on nve.id_enfermeria = e.id
        inner join caracter_fisicos cf on nve.id_caracter_fisico = cf.id
        where e.id_ext_enfermeria = '$prestacion_datalle->id_cita'";
        $vitales = DB::select($vitales);
$vitalesData = array();

if (is_array($vitales) && count($vitales) > 0) {

    foreach ($vitales as $v) {

        $vitalesData[$v->denominacion] = $v->descripcion;
    }
}

        $temperatura = isset($vitalesData['Temperatura'])
    ? $vitalesData['Temperatura']
    : $prestacion_datalle->temperatura;

$presionX = isset($vitalesData['Presion_arterial_Sisdolica'])
    ? $vitalesData['Presion_arterial_Sisdolica']
    : $prestacion_datalle->presion_arterial_x;

$presionY = isset($vitalesData['Presion_arterial_y'])
    ? $vitalesData['Presion_arterial_y']
    : $prestacion_datalle->presion_arterial_y;

$frecuenciaRespiratoria = isset($vitalesData['frecuencia_respiratoria'])
    ? $vitalesData['frecuencia_respiratoria']
    : $prestacion_datalle->frecuencia_respiratoria;

$frecuenciaCardiaca = isset($vitalesData['pulso_frecuencia_Cardiaca'])
    ? $vitalesData['pulso_frecuencia_Cardiaca']
    : $prestacion_datalle->frecuencia_cardiaca;


        $referencia_detalle = DB::table('referencias')
        ->select('referencias.*')
        ->where('id',$id_referencia)
        ->first();

        $establecimientos_origen_detalle = DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('id',$referencia_detalle->id_establecimiento)
        ->first();
        $date = Carbon::now();
        $anio_actual = $date->format('y');
        $numero_nombre = $establecimientos_origen_detalle->codigo.'-'.$referencia_detalle->id.'-'.$anio_actual;

        $establecimientos_detalle = DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('id',$referencia_detalle->id_establecimiento_des)
        ->first();
        $id_servicio = DB::table('consultorios')
        ->select('consultorios.*')
        ->where('id',$referencia_detalle->id_servicio)
        ->first();

        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);        
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
        $num_subarreglos = count($diagnostico);
        
        if($num_subarreglos >1){
            $diagnostico1 = $diagnostico[1];
            $texto1= $diagnostico[1]->nombre;
            $texto1 = wordwrap($texto1, 30, "\n");
        }else{
            $diagnostico1 = 0;
            $texto1 = 0;
        }
        if($num_subarreglos >2){
            $diagnostico2 = $diagnostico[2];
            $texto2= $diagnostico[2]->nombre;
            $texto2 = wordwrap($texto2, 30, "\n");
        }else{
            $diagnostico2 = 0;
            $texto2 = 0;
        }

        if ($referencia->id_cita_triaje_referencia === null) {
            $fecha_cita_referencia = '';
            $hora_cita_referencia = '';
            $medico_cita_referencia = '';
        } else {
            $cita = DB::table('citas')
                ->where('id', $referencia->id_cita_triaje_referencia)
                ->first();
                
                if ($cita->id_medico == '' || $cita->id_medico == null){
                    $medico_cita_referencia = '';
                }else{
                    $medico_ref = DB::table('medicos')
                        ->where('id', $cita->id_medico)
                        ->first();
                    $medico_cita_referencia = $medico_ref->paterno . ' ' . $medico_ref->materno . ', ' . $medico_ref->nombre;
                }
                $fecha = Carbon::parse($cita->fecha_hora);
                $fecha_cita_referencia = $fecha->format('d-m-Y');
                $hora_cita_referencia  = $fecha->format('H:i:s');

        }

        $diagnostico = $diagnostico[0];
        $pdf = \PDF::loadView('pdf.ref_pdf_formato',compact('prestacion','asegurado','nombre_departamentos','nombre_distrito','id_servicio','texto1','presionY',
        'texto2','referencia_detalle','fecha_cita_referencia','hora_cita_referencia','medico_cita_referencia','temperatura','presionX','frecuenciaRespiratoria','frecuenciaCardiaca'
        ,'anios','meses','dias','establecimientos_detalle','medico','profesion','users','medicos','prestacion_datalle','num_subarreglos','diagnostico1','diagnostico2','diagnostico','referencia','numero_nombre','procedimiento','id_referencia'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('referencia.pdf');
    }
    /*public function print_interconsulta($id_prestacion, $id_interconsulta) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_interconsulta = new \App\Models\Interconsulta;        

        $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);

        $pdf = \PDF::loadView('pdf.firma_interconsulta_rpta',compact('prestacion','diagnostico','interconsulta','procedimiento','id_interconsulta'));       
        //$pdf = \PDF::loadView('pdf.interconsulta',compact('prestacion','diagnostico','interconsulta','procedimiento','id_interconsulta'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('interconsulta.pdf');
    }*/

    public function print_interconsulta($id_prestacion, $id_interconsulta, $id_evaluacion) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_interconsulta = new \App\Models\Interconsulta;        
        $model_evaluacion = new \App\Models\Evaluacione;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        if($id_evaluacion==0):
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
            $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
            $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
        else:  //evaluacion emergencia
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
            $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
            $interconsulta = $model_interconsulta->GetInterconsultaByIdEvaluacion($id_prestacion,$id_evaluacion,$id_interconsulta);
            $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
            $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
        endif;

        $pdf = \PDF::loadView('pdf.interconsulta',compact('prestacion','diagnostico','interconsulta','procedimiento','id_interconsulta','id_evaluacion','diagnostico_evaluacion','procedimiento_evaluacion'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('interconsulta.pdf');
    }

    public function print_evolucion($id_evolucion) {
        
        $model_evolucion = new \App\Models\Evolucion;
        
        $evolucion = $model_evolucion->GetEvolucionesByPaciente($id_evolucion,3);
        $diagnostico = $model_evolucion->GetDiagnosticoByIdEvolucion($id_evolucion);
        $procedimiento = $model_evolucion->GetProcedimientosByIdEvolucion($id_evolucion, 1);

        $pdf = \PDF::loadView('pdf.evolucion',compact('evolucion','diagnostico','procedimiento'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('evolucion.pdf');
    }

    public function print_egreso($id_evolucion) {
        
        $model_evolucion = new \App\Models\Evolucion;
        
        $evolucion = $model_evolucion->GetEvolucionesByPaciente($id_evolucion,3);
        $diagnostico = $model_evolucion->GetDiagnosticoByIdEvolucion($id_evolucion);
        $procedimiento = $model_evolucion->GetProcedimientosByIdEvolucion($id_evolucion, 1);

        $pdf = \PDF::loadView('pdf.evolucion',compact('evolucion','diagnostico','procedimiento'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('evolucion.pdf');
    }

    public function print_evolucion_enfermeria($id_evolucion) {
        
        $model_evolucion = new \App\Models\Evolucion;
        
        $evolucion = $model_evolucion->GetEvolutionEnfermeriaById($id_evolucion);
        $diagnostico = $model_evolucion->GetDiagnosticoByIdEvolucion($id_evolucion);
        $procedimiento = $model_evolucion->GetProcedimientosByIdEvolucion($id_evolucion, 1);

        $pdf = \PDF::loadView('pdf.evolucion_enfermeria',compact('evolucion','diagnostico','procedimiento'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('evolucion_enfermeria.pdf');
    }

    
      public function print_hoja_his($id_atencion,$id_establecimiento,$fecha_atencion,$id_medico, $id_consultorio) {
        
        $model_consultorio = new Consultorio;
        $upss = $model_consultorio->ShowCodigoUpssIdTipoAtencion($id_consultorio,$id_atencion);
        
        $id_servicio=$upss[0]->id;
        $codigo_upss=$upss[0]->codigo;
        $nombre_upss=$upss[0]->nombre;

        $dni_medico=Auth::user()->dni;
        $id_establecimiento=Auth::user()->id_establecimiento;

        $model_establecimiento = new Establecimiento();
        $establecimiento = $model_establecimiento->GetByIdEstablecimiento($id_establecimiento);
        $modelmedico = new Medico();
        
        if(Auth::user()->flagservicios==1){
            $medico = $modelmedico->GetMostrarMedicoById($id_medico);    
        }else
        {
            $medico = $modelmedico->GetMostrarMedicoByDNI($dni_medico);    
        }

        if($id_atencion==3){
            $prestaciones = $modelmedico->GetMostrarAtencionesMedicoByHISHO($id_atencion,$id_establecimiento,$fecha_atencion,$id_medico, $id_consultorio);
        }
        else{
            $prestaciones = $modelmedico->GetMostrarAtencionesMedicoByHIS($id_atencion,$id_establecimiento,$fecha_atencion,$id_medico, $id_consultorio);
        }
        $tipo_atencion=$id_atencion;
        switch ($id_atencion) {
            case '1': $nombre_atencion='AMBULATORIO';break;
            case '2': $nombre_atencion='EMERGENCIA';break;
            case '3': $nombre_atencion='HOSPITALIZACION';break;
            case '4': $nombre_atencion='OTRO';break;
        }

        //$turno =  "SELECT mt.* from medicos m inner join medico_turnos mt on m.id = mt.id_medico where m.id = '$id_medico' and mt.fecha_atencion = '$fecha_atencion' and mt.estado = '1'";
        //$turno = DB::select($turno);
       
        $pdf = \PDF::loadView('pdf.hoja_his_ambulatorio',compact('establecimiento','codigo_upss','medico','fecha_atencion','prestaciones','nombre_atencion','tipo_atencion','nombre_upss','turno','dni_medico'));               
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('hoja_his_ambulatorio.pdf');
    } 

    public function print_hoja_his_evaluacion($id_atencion,$id_establecimiento,$fecha_atencion,$id_medico,$id_consultorio) {
        
        if($id_atencion==2){
            $id_servicio=76;
            $codigo_upss='230000';
            $nombre_upss='EMERGENCIA';
        }
        else{
           $model_consultorio = new Consultorio;
            $upss = $model_consultorio->ShowCodigoUpssIdTipoAtencion($id_consultorio,$id_atencion);
        
            $id_servicio=$upss[0]->id;
            $codigo_upss=$upss[0]->codigo;
            $nombre_upss=$upss[0]->nombre; 
        }
        
        $dni_medico=Auth::user()->dni;
        $id_establecimiento=Auth::user()->id_establecimiento;

        $model_establecimiento = new Establecimiento();
        $establecimiento = $model_establecimiento->GetByIdEstablecimiento($id_establecimiento);
        $modelmedico = new Medico();
        
        if(Auth::user()->flagservicios==1){
            $medico = $modelmedico->GetMostrarMedicoById($id_medico);    
        }else
        {
            $medico = $modelmedico->GetMostrarMedicoByDNI($dni_medico);    
        }

        $tipo_atencion=$id_atencion;
        switch ($id_atencion) {
            case '1': $nombre_atencion='AMBULATORIO';break;
            case '2': $nombre_atencion='EMERGENCIA';break;
            case '3': $nombre_atencion='HOSPITALIZACION';break;
            case '4': $nombre_atencion='OTRO';break;
        }

        if($id_atencion==2){
            $evaluaciones = $modelmedico->GetMostrarEvaluacionesMedicoByHIS($id_atencion,$id_establecimiento,$fecha_atencion,$id_medico);
        }else{
          $evaluaciones = $modelmedico->GetMostrarEvaluacionesMedicoByHISHO($id_atencion,$id_establecimiento,$fecha_atencion,$id_medico);
        }
        
        $pdf = \PDF::loadView('pdf.hoja_his_emergencia_evaluacion',compact('establecimiento','codigo_upss','medico','fecha_atencion','nombre_atencion','tipo_atencion','nombre_upss','evaluaciones'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('hoja_his_emergencia_evaluacion.pdf');
    }   
    public function print_examen_radiologia_lns($id_radiologia, $id_servicio_radiologia,$idpp) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;
        $model_medico = new \App\Models\Medico;
        $model_consultorio = new \App\Models\Consultorio;

        $radiologia = $model_radiologia->GetRadiologiaById($id_radiologia);
        $id_prestacion=$radiologia->id_prestacion;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $procradiologia = $model_radiologia->GetProcedimientosByIdRadiologiaAndIdServicioRadiologia(/*$id_prestacion,$id_radiologia,$id_servicio_radiologia,*/$idpp);
        // $informe = preg_replace('/\s*class="[^"]*"/', '', $procradiologia[0]->informe_parrafo);
        $informe = str_replace('&nbsp;', ' ', $procradiologia[0]->informe_parrafo);
        $medico = "";
        if (!empty($procradiologia[0]->dni_medico_validacion)):
            $medico = $model_medico->GetMedicoByDNI($procradiologia[0]->dni_medico_validacion);
        endif;
        
        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_informe-radio_".$idpp.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_informe-radio_".$idpp.".pdf");
        
        // Generar contenido HTML
        $pdfContent = view('pdf.examen_radiologia_lns', compact('prestacion', 'radiologia', 'procradiologia', 'medico', 'codigo_qr', 'url_descarga', 'informe'))->render();
        $pdf = \PDF::loadView('pdf.examen_radiologia_lns',compact('prestacion','radiologia','procradiologia','medico','codigo_qr','url_descarga','informe'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('examen_radiologia_lns.pdf');
    }

    public function print_examen_laboratorio_prueba($id_laboratorio, $tipo_examen) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_laboratorio = new \App\Models\Laboratorio;        

        $laboratorio = $model_laboratorio->GetLaboratorioById($id_laboratorio);
        $id_prestacion=$laboratorio->id_prestacion;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $proclaboratorio = $model_laboratorio->GetProcedimientosByIdLaboratorio($id_prestacion,$tipo_examen,$id_laboratorio);
        $resultado_examen = $model_laboratorio->MostrarResultadoExamen($id_laboratorio,$tipo_examen);
        
        $pdf = \PDF::loadView('pdf.rua_emergencia',compact('prestacion','diagnostico','laboratorio','proclaboratorio','resultado_examen','tipo_examen'));            
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('examen.pdf');
    } 

    public function print_plantilla_rua($nivel, $tipo_atencion, $id_consultorio=NULL) {
        
        $model_laboratorio = new \App\Models\Laboratorio;
        if($nivel==1){ //ambulatorio
            $titulo_cabecera = "CONSULTORIOS";
            $model_consultorio = new Consultorio();
            $lista_rua = $model_laboratorio->getVerRua($nivel, $tipo_atencion, $id_consultorio);
            $total_categoria = $model_laboratorio->getNumCategoria($nivel, $tipo_atencion, $id_consultorio);
            $num_categoria = $total_categoria->total;
            $total_examenes = count($lista_rua) + $num_categoria;
            $filas_por_columnas = "";
            $consultorio = $model_consultorio->GetNombreById($id_consultorio);
            //$total_examenes = count($lista_rua) +10;
            $num_columna=$consultorio->reporte_columnas_lab;
            if($num_columna==0 ||$num_columna == null || $num_columna ==""){
              return response()->json(["Object"=> "Error","message"=> "No hay columnas configuradas para este servicio. Por favor agrega Columnas"]);
            }
            $filas_por_columnas = ceil($total_examenes / $num_columna);
            $filas_por_columnas = intval($filas_por_columnas);

            $model_establecimiento = new App\Models\Establecimiento;
            $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
            $Provincia = $model_establecimiento->GetProvinciaForUbego($establecimiento[0]->ubigeo);
            $pdf = \PDF::loadView('pdf.rua_nivel_3_1',compact('adicional','nivel','tipo_atencion','lista_rua','total_examenes','filas_por_columnas','consultorio','establecimiento','titulo_cabecera','Provincia')); 
        } else if ($nivel==2) {    
            if($tipo_atencion==1){ //ambulatorio
                $titulo_cabecera = "CONSULTORIOS";
                $model_consultorio = new Consultorio();
                $lista_rua = $model_laboratorio->getVerRua($nivel, $tipo_atencion, $id_consultorio);
                $total_categoria = $model_laboratorio->getNumCategoria($nivel, $tipo_atencion, $id_consultorio);
                $num_categoria = $total_categoria->total;
                $total_examenes = count($lista_rua) + $num_categoria;
                $filas_por_columnas = "";
                $consultorio = $model_consultorio->GetNombreById($id_consultorio);
                //$total_examenes = count($lista_rua) +10;
                $num_columna=$consultorio->reporte_columnas_lab;
                if($num_columna==0 ||$num_columna == null || $num_columna ==""){
                  return response()->json(["Object"=> "Error","message"=> "No hay columnas configuradas para este servicio. Por favor agrega Columnas"]);
                }
                $filas_por_columnas = ceil($total_examenes / $num_columna);
                $filas_por_columnas = intval($filas_por_columnas);

                $model_establecimiento = new App\Models\Establecimiento;
                $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
                $Provincia = $model_establecimiento->GetProvinciaForUbego($establecimiento[0]->ubigeo);
                $pdf = \PDF::loadView('pdf.rua_nivel_3_1',compact('adicional','nivel','tipo_atencion','lista_rua','total_examenes','filas_por_columnas','consultorio','establecimiento','titulo_cabecera','Provincia')); 
            } else if($tipo_atencion==2) { //emergencia
                $titulo_cabecera = "EMERGENCIA";
                $lista_rua = $model_laboratorio->getVerRuaOrig($nivel, $tipo_atencion);
                $total_categoria = $model_laboratorio->getNumCategoriaOrig($nivel, $tipo_atencion);
                $num_categoria = $total_categoria->total;
                $total_examenes = count($lista_rua) + $num_categoria;
                $filas_por_columnas = 24;
                $sum=$total_examenes%2;
                $adicional = 50-$total_examenes + $sum;
                if($sum!=0){
                    $adicional = 50-$total_examenes;    
                }
                $model_establecimiento = new App\Models\Establecimiento;
                $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
                $Provincia = $model_establecimiento->GetProvinciaForUbego($establecimiento[0]->ubigeo);
                $pdf = \PDF::loadView('pdf.rua_emergencia',compact('adicional','nivel','tipo_atencion','lista_rua','total_examenes','filas_por_columnas','establecimiento','titulo_cabecera','Provincia'));   
            }
        }else if($nivel==3) {
            if($tipo_atencion==1){ //ambulatorio
                $titulo_cabecera = "CONSULTORIOS";
                $model_consultorio = new Consultorio();
                $lista_rua = $model_laboratorio->getVerRua($nivel, $tipo_atencion, $id_consultorio);
                $total_categoria = $model_laboratorio->getNumCategoria($nivel, $tipo_atencion, $id_consultorio);
                $num_categoria = $total_categoria->total;
                $total_examenes = count($lista_rua) + $num_categoria;
                $filas_por_columnas = "";
                $consultorio = $model_consultorio->GetNombreById($id_consultorio);
                //$total_examenes = count($lista_rua) +10;
                $num_columna=$consultorio->reporte_columnas_lab;
                if($num_columna==0 ||$num_columna == null || $num_columna ==""){
                  return response()->json(["Object"=> "Error","message"=> "No hay columnas configuradas para este servicio. Por favor agrega Columnas"]);
                }
                $filas_por_columnas = ceil($total_examenes / $num_columna);
                $filas_por_columnas = intval($filas_por_columnas);

                $model_establecimiento = new App\Models\Establecimiento;
                $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
                $Provincia = $model_establecimiento->GetProvinciaForUbego($establecimiento[0]->ubigeo);
                $pdf = \PDF::loadView('pdf.rua_nivel_3_1',compact('adicional','nivel','tipo_atencion','lista_rua','total_examenes','filas_por_columnas','consultorio','establecimiento','titulo_cabecera','Provincia')); 
            } else if($tipo_atencion==2) { //emergencia
                $model_consultorio = new Consultorio();
                $titulo_cabecera = "EMERGENCIA";
                $lista_rua = $model_laboratorio->getVerRuaOrig($nivel, $tipo_atencion);
                $total_categoria = $model_laboratorio->getNumCategoriaOrig($nivel, $tipo_atencion);
                $num_categoria = $total_categoria->total;
                $total_examenes = count($lista_rua) + $num_categoria;
                $filas_por_columnas = "";
                $consultorio = $model_consultorio->GetNombreById($id_consultorio);
                $num_columna=$consultorio->reporte_columnas_lab;
                if($num_columna==0 ||$num_columna == null || $num_columna ==""){
                  return response()->json(["Object"=> "Error","message"=> "No hay columnas configuradas para este servicio. Por favor agrega Columnas"]);
                }
                $filas_por_columnas = ceil($total_examenes / $num_columna);
                $filas_por_columnas = intval($filas_por_columnas);
                $model_establecimiento = new App\Models\Establecimiento;
                $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
                $Provincia = $model_establecimiento->GetProvinciaForUbego($establecimiento[0]->ubigeo);
                $pdf = \PDF::loadView('pdf.rua_emergencia_2',compact('adicional','nivel','tipo_atencion','lista_rua','total_examenes','filas_por_columnas','establecimiento','titulo_cabecera','consultorio','Provincia', 'num_columna'));   
            } else if($tipo_atencion==3) { //hospitalizacion
                $titulo_cabecera = "HOSPITALIZACIÓN";
                $model_consultorio = new Consultorio();
                $lista_rua = $model_laboratorio->getVerRuaOrig($nivel, $tipo_atencion);
                $total_categoria = $model_laboratorio->getNumCategoriaOrig($nivel, $tipo_atencion);
                $num_categoria = $total_categoria->total;
                $total_examenes = count($lista_rua) + $num_categoria;
                $filas_por_columnas = "";
                $consultorio = $model_consultorio->GetNombreById($id_consultorio);
                //$total_examenes = count($lista_rua) +10;
                $num_columna=$consultorio->reporte_columnas_lab;
                if($num_columna==0 ||$num_columna == null || $num_columna ==""){
                  return response()->json(["Object"=> "Error","message"=> "No hay columnas configuradas para este servicio. Por favor agrega Columnas"]);
                }
                $filas_por_columnas = ceil($total_examenes / $num_columna);
                $filas_por_columnas = intval($filas_por_columnas);
                $model_establecimiento = new App\Models\Establecimiento;
                $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
                $Provincia = $model_establecimiento->GetProvinciaForUbego($establecimiento[0]->ubigeo);
                $pdf = \PDF::loadView('pdf.rua_hospitalizacion_3',compact('adicional','nivel','tipo_atencion','lista_rua','total_examenes','filas_por_columnas','consultorio','establecimiento','titulo_cabecera','Provincia'));
            }
        }
        
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('examen.pdf');
    } 
     
     public function imprimir_historias_servicios($id_nro_mov) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;

        $model_historia = new \App\Models\Historia;
        $model_farmacia = new Farmacia;
        
        $historias_asignadas = $model_historia->ShowHistoriasById_movimiento_hc($id_nro_mov);
        $movimiento_historias = $model_historia->GetMovimientoHCById($id_nro_mov);
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);

        $origen = Consultorio::where('id', $movimiento_historias->id_origen)->select('nombre')->first();
        $destino = Consultorio::where('id', $movimiento_historias->id_consultorio)->select('nombre')->first();
        $id_destino = $movimiento_historias->id_consultorio;
        
        
        
        $pdf = \PDF::loadView('pdf.historia_servicio_print',['historias_asignadas'=>$historias_asignadas,'movimiento_historias'=>$movimiento_historias,'id_farmacia' =>$id_farmacia,'destino' =>$destino,'origen' =>$origen]);
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('hc_servicio.pdf');
        
    }
  
    public function mostrar_alta_medica($id_user, $id_emergencia)   {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_emergencia)->first();
    if(is_object($emergencia_prestacion)):

    $id_establecimiento=Auth::user()->id_establecimiento;
    $model_emergencia = new Emergencia();
    $model_emergencia = new \App\Models\Emergencia;

    //para el foreach de neonatos
    $model_emergencia = new \App\Models\Emergencia;
    $obtenerdato = $model_emergencia->GetObtenerServicio($id_emergencia);
    $dato_traslado = $model_emergencia->GetVerUbicacionTraslado($id_emergencia);
    $emergencia_triaje = $model_emergencia->obtenerReporteSaludMaterna($id_emergencia);
    //$emergencia_triaje = $model_emergencia->obtenerRegistroEmergencia($id_emergencia);
    $model_prestacion = new \App\Models\Prestaciones;

// foreaxh natis
//$prestacion = $model_prestacion->GetPrestacionById($id_emergencia);

      //CARGA DE SALUD MATERNA foreach para neonatos
      $emergencia = Emergencia::find($id_emergencia);
      $natis = null;
      
     
      
      if (isset($emergencia->id_puerperio)) {
          $puerperio = Puerperio::find($emergencia->id_puerperio);
          if ($puerperio) {
              $natis = $puerperio->natis;
          }
      }
    $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
    $id_prestacion =  $prestacion->id;
    $emergencia = Emergencia::where('id', '=', $id_emergencia)->first();
    $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_emergencia,$emergencia->estado);
    $egreso = $model_emergencia->GetEgresoEmergencia($id_emergencia);
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_kardex = new \App\Models\Kardexe();
    $fechaServidor = $model_kardex->getFechaServidor();
    $horaservidor = $model_kardex->getHoraServidor();
    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $diagnosticos_egreso = $model_prestacion->GetDiagnosticoByIdAltaPrestacion($id_prestacion);
    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;
    $aseguradoemer=Asegurado::where('id', '=', $emergencia->id_asegurado)->first();
    $tipo_benefi="SELECT * from tipo_beneficiarios where id= '$aseguradoemer->id_tipo_beneficiario'";
    $tipo_benefi = DB::select($tipo_benefi);
    $tipo_benefi=$tipo_benefi[0];
    $formaingreso="SELECT * from forma_ingresos where id= '$emergencia->id_forma_ingreso'";
    $formaingreso = DB::select($formaingreso);
    $formaingreso=$formaingreso[0];
    $estadoingre="SELECT * from estado_ingresos where id= '$emergencia->id_estado_ingreso'";
    $estadoingre = DB::select($estadoingre);
    $estadoingre=$estadoingre[0];
    $motivoingre="SELECT * from categorias where id= '$emergencia->id_motivo_ingreso'";
    $motivoingre = DB::select($motivoingre);
    $motivoingre=$motivoingre[0];
    $condiegre="SELECT * from condiciones where codigo_salupol= '$emergencia->id_condi_egres_med'";
    $condiegre = DB::select($condiegre);
    $condiegre=$condiegre[0];
    $priori="SELECT * from prioridades where id= '$emergencia->id_prioridad'";
    $priori = DB::select($priori);
    $priori=$priori[0];

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }

    $model_consultorio = new \App\Models\Consultorio;
    $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
    $hospitalizacion = "select c.nombre,
                        u.id,
                        u.codigo 
                        from consultorios c 
                        inner join upsses u on u.id_consultorio = c.id 
                        where u.id_tipo_atencion = '3' and u.estado = '1'";
    $hospitalizacion = DB::select($hospitalizacion);
    
    $dni=Auth::user()->dni;
    $model_medico = new Medico();
    $exite_medico=$model_medico->exite_medico($dni);
    $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
  
    $establecimie = "select * from establecimientos e where id = '$prestacion->id_establecimiento'";
    $establecimie = DB::select($establecimie);
    $establecimie= $establecimie[0];
    
    if(isset($emergencia->cama_estado)){
        $cama = "select * from camas c where id = '$emergencia->cama_estado'";
        $cama = DB::select($cama);
        $cama= $cama[0];  
        $existecama = '1';
    }else{
        
           $cama="SELECT * from camas c where id = '506'";
           $cama = DB::select($cama);
             $cama= $cama[0]; 
             $existecama = '0';
    }
    
   $diagnosticos_egreso = array();
    if ($emergencia->id_diag_cab > 0) {
        $diagnos = DB::table('diagnosticos')->where('id', $emergencia->id_diag_cab)->first();

        if ($diagnos) {
            $diagnos->cabecera = "PRINCIPAL";
            $diagnos->tipo = "DEFINITIVO";
            $diagnosticos_egreso[] = $diagnos;
        }
    }
    $diagnosticos_secundarios = array(
        $emergencia->id_diag_cuer1,
        $emergencia->id_diag_cuer2
    );
    foreach ($diagnosticos_secundarios as $id_diagnostico) {
        if ($id_diagnostico > 0){
            $diagnos = DB::table('diagnosticos')->where('id', $id_diagnostico)->first();
            if ($diagnos) {
                $diagnos->cabecera = "SECUNDARIO";
                $diagnos->tipo = "DEFINITIVO";
                $diagnosticos_egreso[] = $diagnos;
            }
        }
    }

    if($emergencia->id_tipo_egres_med=="2"){
        $upss="SELECT 
        concat(c.nombre,' / ',u.codigo ) as nombre,
        u.id as id_upss,
        u.codigo as codigo_upss,
        c.id as id_consultorio,
        c.nombre as nombre_consultorio
        from consultorios c 
        inner join upsses u on u.id_consultorio = c.id 
        where u.id_tipo_atencion = '3' and u.estado = '1' and u.id='$emergencia->id_upss_alta'";
        $upss= DB::select($upss);
        $upss=$upss[0];
      }else{
        $upss=0;
    }
     
    $medicoegreso = "SELECT * from users u  where id = '$emergencia->id_medico_egreso'";
    $medicoegreso = DB::select($medicoegreso);
    $medicoegreso=$medicoegreso[0];
     
    $asegu= "SELECT * from asegurados where id= '$emergencia->id_asegurado'";
    $asegu = DB::select($asegu);
    $asegu=$asegu[0];
  
    //$tiempo="SELECT DATE_PART('YEAR',AGE('$asegu->fecha_nac'::date))";
    $tiempo="SELECT to_char(age(TO_DATE('$asegu->fecha_nac' , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"días\"') as fec_nacim_descrip";
    $tiempo = DB::select($tiempo);
    $tiempo=$tiempo[0]->fec_nacim_descrip;

    $triajes = "SELECT * from triajes where id = '$emergencia->id_triaje'";
    $triajes = DB::select($triajes);
    $triajes=$triajes[0];
    
    $inicio= ($emergencia->fecha_registro_ingreso);
    $final= ($emergencia->fecha_alta_medica);
    $inicios= strtotime($emergencia->fecha_registro_ingreso);
    $fecha_alta=explode(" ",$emergencia->fecha_alta_medica);
    $hora_alta=explode(" ",$emergencia->hora_alta_medica);
    $fecha_finals=$fecha_alta[0].' '.$hora_alta[0];
    //$finals= strtotime($emergencia->fecha_alta_medica);
    $finals= strtotime($fecha_finals);
    
    $resulta=($finals-$inicios)/86400;
    $resulta=round($resulta);
    
    if($resulta == "0"){
      $resta = '1';
    }else{
      $resta=$resulta;
    }

    $datotitu = null;
    if($aseguradoemer->id_tipo_beneficiario != 8 && $aseguradoemer->id_tipo_beneficiario != 13){
        $datosasegura = "SELECT * from asegurados a where nro_doc_ident = '$aseguradoemer->nro_doc_ident' and id_estado_reg = '1'";
        $datosasegura = DB::select($datosasegura);
        $datosasegura=$datosasegura[0];

    $datotitu = "SELECT * from asegurados a where id = '$datosasegura->id_asegurado_titular' ";
    if($datotitu!=null):
        $datotitu = DB::select($datotitu);
        $datotitu=$datotitu[0];
    endif;
    }else{
    $datotitu = "SELECT * from asegurados a where nro_doc_ident = '$aseguradoemer->nro_doc_ident'";
    if($datotitu!=null):
        $datotitu = DB::select($datotitu);
        $datotitu=$datotitu[0];  
    endif;
    }

    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);  
      
            $pdf = \PDF::loadView('pdf.mostrar_alta_medica_a',[
                'datotitu'=>$datotitu,
                'dato_traslado'=>$dato_traslado,
                'medicoegreso'=>$medicoegreso,
                'upss'=>$upss,
                'resta'=>$resta,
                'triajes'=>$triajes,
                'existecama'=>$existecama,
                'prestacion'=>$prestacion,
                'fechaActual'=>$fechaActual,
                'horaActual'=>$horaActual,
                'cama'=>$cama,
                'tipo_benefi'=>$tipo_benefi,
                'tiempo'=>$tiempo,
                'procedimiento'=>$procedimiento,
                'condiegre'=>$condiegre,
                'priori'=>$priori,
                'motivoingre'=>$motivoingre,
                'estadoingre'=>$estadoingre,
                'formaingreso'=>$formaingreso,
                'aseguradoemer'=>$aseguradoemer,
                'establecimie'=>$establecimie, 
                'diagnosticos_egreso'=>$diagnosticos_egreso, 
                'id_prestacion'=>$id_prestacion,
                'nivel'=>$nivel, 
                'hospitalizacion'=>$hospitalizacion, 
                'consulta_externa'=>$consulta_externa,
                'paciente_emergencia'=>$paciente_emergencia, 
                'emergencia_triaje'=>$emergencia_triaje, 
                'natis'=>$natis, 
                'prestacion'=>$prestacion, 
                'emergencia'=>$emergencia]);
            
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('hc_servicio.pdf');
       
        else:
            Session::flash('flash_message', 'Documento no encontrado, verificar el dni o coordine con el administrador del sistema');
            return redirect('/dashboard');
        endif;
    }

    
    public function mostrar_indicaciones($id_user, $id_emergencia)   {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_emergencia)->first();
    if(is_object($emergencia_prestacion)):

    $id_establecimiento=Auth::user()->id_establecimiento;
    $model_emergencia = new Emergencia();
    $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
    $id_prestacion =  $prestacion->id;
    $emergencia = Emergencia::where('id', '=', $id_emergencia)->first();
    $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_emergencia,$emergencia->estado);
    $egreso = $model_emergencia->GetEgresoEmergencia($id_emergencia);
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_kardex = new \App\Models\Kardexe();
    $fechaServidor = $model_kardex->getFechaServidor();
    $horaservidor = $model_kardex->getHoraServidor();
    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;
    $aseguradoemer=Asegurado::where('id', '=', $emergencia->id_asegurado)->first();
    $tipo_benefi="SELECT * from tipo_beneficiarios where id= '$aseguradoemer->id_tipo_beneficiario'";
    $tipo_benefi = DB::select($tipo_benefi);
    $tipo_benefi=$tipo_benefi[0];
    $formaingreso="SELECT * from forma_ingresos where id= '$emergencia->id_forma_ingreso'";
    $formaingreso = DB::select($formaingreso);
    $formaingreso=$formaingreso[0];
    $estadoingre="SELECT * from estado_ingresos where id= '$emergencia->id_estado_ingreso'";
    $estadoingre = DB::select($estadoingre);
    $estadoingre=$estadoingre[0];
    $motivoingre="SELECT * from categorias where id= '$emergencia->id_motivo_ingreso'";
    $motivoingre = DB::select($motivoingre);
    $motivoingre=$motivoingre[0];
    $condiegre="SELECT * from condiciones where codigo_salupol= '$emergencia->id_condi_egres_med'";
    $condiegre = DB::select($condiegre);
    $condiegre=$condiegre[0];
    $priori="SELECT * from prioridades where id= '$emergencia->id_prioridad'";
    $priori = DB::select($priori);
    $priori=$priori[0];

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }

     $model_consultorio = new \App\Models\Consultorio;
     $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
     $hospitalizacion = "select c.nombre,
                        u.id,
                        u.codigo 
                        from consultorios c 
                        inner join upsses u on u.id_consultorio = c.id 
                        where u.id_tipo_atencion = '3' and u.estado = '1'";
                $hospitalizacion = DB::select($hospitalizacion);
    
    $dni=Auth::user()->dni;
    $model_medico = new Medico();
    $exite_medico=$model_medico->exite_medico($dni);
    $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
  
    $establecimie = "select * from establecimientos e where id = '$prestacion->id_establecimiento'";
    $establecimie = DB::select($establecimie);
    $establecimie= $establecimie[0];
    
    if(isset($emergencia->cama_estado)){
        $cama = "select * from camas c where id = '$emergencia->cama_estado'";
        $cama = DB::select($cama);
        $cama= $cama[0];  
        $existecama = '1';
    }else{
        
           $cama="SELECT * from camas c where id = '506'";
           $cama = DB::select($cama);
             $cama= $cama[0]; 
             $existecama = '0';
    }
    
    $diagnosticos=array();
    $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
    $diagnos = DB::select($diagnos);
    $diagnos = $diagnos[0];
    $diagnos->cabecera = "PRINCIPAL";
    $diagnos->tipo = "DEFINITIVO";
    $diagnosticos[0]=$diagnos;
    if($emergencia->id_diag_cuer1>0){
       
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer1'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "SECUNDARIO";
        $diagnos->tipo = "DEFINITIVO";
        $diagnosticos[1]=$diagnos;
    }elseif($emergencia->id_diag_cuer2>0){
       
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer2'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[2];
        $diagnos->cabecera = "SECUNDARIO";
        $diagnos->tipo = "DEFINITIVO";
        $diagnosticos[2]=$diagnos;
    }
    
    if($emergencia->id_tipo_egres_med=="2"){
        $upss="SELECT 
        concat(c.nombre,' / ',u.codigo ) as nombre,
        u.id as id_upss,
        u.codigo as codigo_upss,
        c.id as id_consultorio,
        c.nombre as nombre_consultorio
        from consultorios c 
        inner join upsses u on u.id_consultorio = c.id 
        where u.id_tipo_atencion = '3' and u.estado = '1' and u.id='$emergencia->id_upss_alta'";
        $upss= DB::select($upss);
        $upss=$upss[0];
      }else{
        $upss=0;
      }
    
      $medicoegreso = "SELECT * from users u  where id = '$emergencia->id_medico_egreso'";
      $medicoegreso = DB::select($medicoegreso);
      $medicoegreso=$medicoegreso[0];

    $asegu= "SELECT * from asegurados where id= '$emergencia->id_asegurado'";
    $asegu = DB::select($asegu);
    $asegu=$asegu[0];
    
    //$tiempo="SELECT DATE_PART('YEAR',AGE('$asegu->fecha_nac'::date))";
    $tiempo="SELECT to_char(age(TO_DATE('$asegu->fecha_nac' , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"días\"') as fec_nacim_descrip";
    $tiempo = DB::select($tiempo);
    $tiempo=$tiempo[0]->fec_nacim_descrip;

    $triajes = "SELECT * from triajes where id = '$emergencia->id_triaje'";
    $triajes = DB::select($triajes);
    $triajes=$triajes[0];
    
        $inicio= ($emergencia->fecha_registro_ingreso);
        $final= ($emergencia->fecha_alta_medica);
        $inicios= strtotime($emergencia->fecha_registro_ingreso);
        $fecha_alta=explode(" ",$emergencia->fecha_alta_medica);
        $hora_alta=explode(" ",$emergencia->hora_alta_medica);
        $fecha_finals=$fecha_alta[0].' '.$hora_alta[0];
        //$finals= strtotime($emergencia->fecha_alta_medica);
        $finals= strtotime($fecha_finals);
  
          $resulta=($finals-$inicios)/86400;
          $resulta=round($resulta);
         
          if($resulta == "0"){
            $resta = '1';
          }else{
            $resta=$resulta;
          }

          $buscareceta = "SELECT count(id)  from receta_vales rv where id_prestacion = '$prestacion->id'";
          $buscareceta = DB::select($buscareceta);
          $buscareceta=$buscareceta[0]->count;
         
          if($buscareceta != 0){
            $recetas = "SELECT
            rv.nro_receta,
            rv.fecha_firma,
            pr.id_producto,
            pr.cantidad_prescrita,
            p2.nombre,
            pr.posologia,
            rv.fecha_expedicion 
            from prestaciones p 
            inner join receta_vales rv on rv.id_prestacion = p.id 
            inner join producto_recetas pr on pr.id_receta_vale = rv.id
            inner join productos p2 on p2.id = pr.id_producto 
            where p.id = '$prestacion->id'";
            $recetas = DB::select($recetas);
          }else{
            $recetas = "SELECT
            rv.nro_receta,
            rv.fecha_firma,
            pr.id_producto,
            pr.cantidad_prescrita,
            p2.nombre,
            pr.posologia,
            rv.fecha_expedicion 
          from prestaciones p 
          inner join receta_vales rv on rv.id_prestacion = p.id 
          inner join producto_recetas pr on pr.id_receta_vale = rv.id
          inner join productos p2 on p2.id = pr.id_producto 
          where p.id = '1162594'";
          $recetas = DB::select($recetas);
          }

    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);  
    
        if( $emergencia->id_tipo_egres_med =="2"){ //ALTA MEDICA
            $pdf = \PDF::loadView('pdf.indicaciones_alta_medica_h',[
                'medicoegreso'=>$medicoegreso,
                'upss'=>$upss,
                'recetas'=>$recetas,
                'buscareceta'=>$buscareceta,
                'resta'=>$resta,
                'triajes'=>$triajes,
                'existecama'=>$existecama,
                'prestacion'=>$prestacion,
                'fechaActual'=>$fechaActual,
                'horaActual'=>$horaActual,
                'cama'=>$cama,
                'tipo_benefi'=>$tipo_benefi,
                'tiempo'=>$tiempo,
                'procedimiento'=>$procedimiento,
                'condiegre'=>$condiegre,
                'priori'=>$priori,
                'motivoingre'=>$motivoingre,
                'estadoingre'=>$estadoingre,
                'formaingreso'=>$formaingreso,
                'aseguradoemer'=>$aseguradoemer,
                'establecimie'=>$establecimie, 
                'diagnosticos'=>$diagnosticos, 
                'diagnostico'=>$diagnostico, 
                'id_prestacion'=>$id_prestacion,
                'nivel'=>$nivel, 
                'hospitalizacion'=>$hospitalizacion, 
                'consulta_externa'=>$consulta_externa,
                'paciente_emergencia'=>$paciente_emergencia, 
                 'emergencia'=>$emergencia]);
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('hc_servicio.pdf');
        
        };
            
        else:
            Session::flash('flash_message', 'Documento no encontrado, verificar el dni o coordine con el administrador del sistema');
            return redirect('/dashboard');
        endif;
    }

    public function mostrar_epicrisis_emer($id_user, $id_emergencia)   {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_emergencia)->first();
    if(is_object($emergencia_prestacion)):

    $id_establecimiento=Auth::user()->id_establecimiento;
    $model_emergencia = new Emergencia();
    $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
    $id_prestacion =  $prestacion->id;
    $emergencia = Emergencia::where('id', '=', $id_emergencia)->first();
    $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_emergencia,$emergencia->estado);
    $egreso = $model_emergencia->GetEgresoEmergencia($id_emergencia);
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_kardex = new \App\Models\Kardexe();
    $fechaServidor = $model_kardex->getFechaServidor();
    $horaservidor = $model_kardex->getHoraServidor();
    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $evaluacion = "SELECT * from evaluaciones e where id_emergencia = '$id_emergencia' order by fecha_atencion desc";
    $evaluacion = DB::select($evaluacion);
    $evaluacion = $prestacion;
    
   /* if(isset($evaluacion)){
        $evaluacion = $evaluacion[0];

    }else{
        $evaluacion = $prestacion;
    } */
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;
    $aseguradoemer=Asegurado::where('id', '=', $emergencia->id_asegurado)->first();
    $tipo_benefi="SELECT * from tipo_beneficiarios where id= '$aseguradoemer->id_tipo_beneficiario'";
    $tipo_benefi = DB::select($tipo_benefi);
    $tipo_benefi=$tipo_benefi[0];
    $formaingreso="SELECT * from forma_ingresos where id= '$emergencia->id_forma_ingreso'";
    $formaingreso = DB::select($formaingreso);
    $formaingreso=$formaingreso[0];
    $estadoingre="SELECT * from estado_ingresos where id= '$emergencia->id_estado_ingreso'";
    $estadoingre = DB::select($estadoingre);
    $estadoingre=$estadoingre[0];
    $motivoingre="SELECT * from categorias where id= '$emergencia->id_motivo_ingreso'";
    $motivoingre = DB::select($motivoingre);
    $motivoingre=$motivoingre[0];
    $condiegre="SELECT * from condiciones where codigo_salupol= '$emergencia->id_condi_egres_med'";
    $condiegre = DB::select($condiegre);
    $condiegre=$condiegre[0];
    $priori="SELECT * from prioridades where id= '$emergencia->id_prioridad'";
    $priori = DB::select($priori);
    $priori=$priori[0];

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }

     $model_consultorio = new \App\Models\Consultorio;
     $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
     $hospitalizacion = "select c.nombre,
                        u.id,
                        u.codigo 
                        from consultorios c 
                        inner join upsses u on u.id_consultorio = c.id 
                        where u.id_tipo_atencion = '3' and u.estado = '1'";
                $hospitalizacion = DB::select($hospitalizacion);
    
    $dni=Auth::user()->dni;
    $model_medico = new Medico();
    $exite_medico=$model_medico->exite_medico($dni);
    $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
  
    $establecimie = "select * from establecimientos e where id = '$prestacion->id_establecimiento'";
    $establecimie = DB::select($establecimie);
    $establecimie= $establecimie[0];
    
    if(isset($emergencia->cama_estado)){
        $cama = "select * from camas c where id = '$emergencia->cama_estado'";
        $cama = DB::select($cama);
        $cama= $cama[0];  
        $existecama = '1';
    }else{
        
           $cama="SELECT * from camas c where id = '506'";
           $cama = DB::select($cama);
             $cama= $cama[0]; 
             $existecama = '0';
    }
    
    $diagnosticos=array();
    $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
    $diagnos = DB::select($diagnos);
    $diagnos = $diagnos[0];
    $diagnos->cabecera = "PRINCIPAL";
    $diagnos->tipo = "DEFINITIVO";
    $diagnosticos[0]=$diagnos;
    if($emergencia->id_diag_cuer1>0){
       
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer1'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "SECUNDARIO";
        $diagnos->tipo = "DEFINITIVO";
        $diagnosticos[1]=$diagnos;
    }elseif($emergencia->id_diag_cuer2>0){
       
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer2'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[2];
        $diagnos->cabecera = "SECUNDARIO";
        $diagnos->tipo = "DEFINITIVO";
        $diagnosticos[2]=$diagnos;
    }
    if($emergencia->id_tipo_egres_med=="2"){
        $upss="SELECT 
        concat(c.nombre,' / ',u.codigo ) as nombre,
        u.id as id_upss,
        u.codigo as codigo_upss,
        c.id as id_consultorio,
        c.nombre as nombre_consultorio
        from consultorios c 
        inner join upsses u on u.id_consultorio = c.id 
        where u.id_tipo_atencion = '3' and u.estado = '1' and u.id='$emergencia->id_upss_alta'";
        $upss= DB::select($upss);
        $upss=$upss[0];
      }else{
        $upss=0;
      }
      
      $medicoegreso = "SELECT * from users u  where id = '$emergencia->id_medico_egreso'";
      $medicoegreso = DB::select($medicoegreso);
      $medicoegreso=$medicoegreso[0];

    $asegu= "SELECT * from asegurados where id= '$emergencia->id_asegurado'";
    $asegu = DB::select($asegu);
    $asegu=$asegu[0];
    
    //$tiempo="SELECT DATE_PART('YEAR',AGE('$asegu->fecha_nac'::date))";
    $tiempo="SELECT to_char(age(TO_DATE('$asegu->fecha_nac' , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"días\"') as fec_nacim_descrip";
    $tiempo = DB::select($tiempo);
    $tiempo=$tiempo[0]->fec_nacim_descrip;

    $triajes = "SELECT * from triajes where id = '$emergencia->id_triaje'";
    $triajes = DB::select($triajes);
    $triajes=$triajes[0];
    
        $inicio= ($emergencia->fecha_registro_ingreso);
        $final= ($emergencia->fecha_alta_medica);
        $inicios= strtotime($emergencia->fecha_registro_ingreso);
        $fecha_alta=explode(" ",$emergencia->fecha_alta_medica);
        $hora_alta=explode(" ",$emergencia->hora_alta_medica);
        $fecha_finals=$fecha_alta[0].' '.$hora_alta[0];
        //$finals= strtotime($emergencia->fecha_alta_medica);
        $finals= strtotime($fecha_finals);
  
          $resulta=($finals-$inicios)/86400;
          $resulta=round($resulta);
         
          if($resulta == "0"){
            $resta = '1';
          }else{
            $resta=$resulta;
          }

          $buscareceta = "SELECT count(id)  from receta_vales rv where id_prestacion = '$prestacion->id'";
          $buscareceta = DB::select($buscareceta);
          $buscareceta=$buscareceta[0]->count;
         
          if($buscareceta != 0){
            $recetas = "SELECT
            rv.nro_receta,
            rv.fecha_firma,
            pr.id_producto,
            pr.cantidad_prescrita,
            p2.nombre,
            pr.posologia,
            rv.fecha_expedicion 
            from prestaciones p 
            inner join receta_vales rv on rv.id_prestacion = p.id 
            inner join producto_recetas pr on pr.id_receta_vale = rv.id
            inner join productos p2 on p2.id = pr.id_producto 
            where p.id = '$prestacion->id'";
            $recetas = DB::select($recetas);
          }else{
            $recetas = "SELECT
            rv.nro_receta,
            rv.fecha_firma,
            pr.id_producto,
            pr.cantidad_prescrita,
            p2.nombre,
            pr.posologia,
            rv.fecha_expedicion 
          from prestaciones p 
          inner join receta_vales rv on rv.id_prestacion = p.id 
          inner join producto_recetas pr on pr.id_receta_vale = rv.id
          inner join productos p2 on p2.id = pr.id_producto 
          where p.id = '1162594'";
          $recetas = DB::select($recetas);
          }

    //$procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);  
    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionEmergenciaPNP($id_prestacion, 1); 
       
            $pdf = \PDF::loadView('pdf.mostrar_epicrisis_emerge',[
                'medicoegreso'=>$medicoegreso,
                'evaluacion'=>$evaluacion,
                'upss'=>$upss,
                'recetas'=>$recetas,
                'buscareceta'=>$buscareceta,
                'resta'=>$resta,
                'triajes'=>$triajes,
                'existecama'=>$existecama,
                'prestacion'=>$prestacion,
                'fechaActual'=>$fechaActual,
                'horaActual'=>$horaActual,
                'cama'=>$cama,
                'tipo_benefi'=>$tipo_benefi,
                'tiempo'=>$tiempo,
                'procedimiento'=>$procedimiento,
                'condiegre'=>$condiegre,
                'priori'=>$priori,
                'motivoingre'=>$motivoingre,
                'estadoingre'=>$estadoingre,
                'formaingreso'=>$formaingreso,
                'aseguradoemer'=>$aseguradoemer,
                'establecimie'=>$establecimie, 
                'diagnosticos'=>$diagnosticos, 
                'diagnostico'=>$diagnostico, 
                'id_prestacion'=>$id_prestacion,
                'nivel'=>$nivel, 
                'hospitalizacion'=>$hospitalizacion, 
                'consulta_externa'=>$consulta_externa,
                'paciente_emergencia'=>$paciente_emergencia, 
                 'emergencia'=>$emergencia]);
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('hc_servicio.pdf');
      
        else:
            Session::flash('flash_message', 'Documento no encontrado, verificar el dni o coordine con el administrador del sistema');
            return redirect('/dashboard');
        endif;
    }

    public function mostrar_ref_contra_emer($id_user, $id_emergencia)   {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_emergencia)->first();
    if(is_object($emergencia_prestacion)):

    $id_establecimiento=Auth::user()->id_establecimiento;
    $model_emergencia = new Emergencia();
    $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
    $id_prestacion =  $prestacion->id;
    $emergencia = Emergencia::where('id', '=', $id_emergencia)->first();
    $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_emergencia,$emergencia->estado);
    $egreso = $model_emergencia->GetEgresoEmergencia($id_emergencia);
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_kardex = new \App\Models\Kardexe();
    $fechaServidor = $model_kardex->getFechaServidor();
    $horaservidor = $model_kardex->getHoraServidor();
    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $evaluacion = "SELECT * from evaluaciones e where id_emergencia = '$id_emergencia' order by fecha_atencion desc";
    $evaluacion = DB::select($evaluacion);
    if(isset($evaluacion) && $evaluacion != [] ){
        $evaluacion = $evaluacion[0];

    }else{
        $evaluacion = $prestacion;
    } 
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;
    $aseguradoemer=Asegurado::where('id', '=', $emergencia->id_asegurado)->first();
    $tipo_benefi="SELECT * from tipo_beneficiarios where id= '$aseguradoemer->id_tipo_beneficiario'";
    $tipo_benefi = DB::select($tipo_benefi);
    $tipo_benefi=$tipo_benefi[0];
    $formaingreso="SELECT * from forma_ingresos where id= '$emergencia->id_forma_ingreso'";
    $formaingreso = DB::select($formaingreso);
    $formaingreso=$formaingreso[0];
    $estadoingre="SELECT * from estado_ingresos where id= '$emergencia->id_estado_ingreso'";
    $estadoingre = DB::select($estadoingre);
    $estadoingre=$estadoingre[0];
    $motivoingre="SELECT * from categorias where id= '$emergencia->id_motivo_ingreso'";
    $motivoingre = DB::select($motivoingre);
    $motivoingre=$motivoingre[0];
    $condiegre="SELECT * from condiciones where codigo_salupol= '$emergencia->id_condi_egres_med'";
    $condiegre = DB::select($condiegre);
    $condiegre=$condiegre[0];
    $priori="SELECT * from prioridades where id= '$emergencia->id_prioridad'";
    $priori = DB::select($priori);
    $priori=$priori[0];

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }

     $model_consultorio = new \App\Models\Consultorio;
     $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
     $hospitalizacion = "select c.nombre,
                        u.id,
                        u.codigo 
                        from consultorios c 
                        inner join upsses u on u.id_consultorio = c.id 
                        where u.id_tipo_atencion = '3' and u.estado = '1'";
                $hospitalizacion = DB::select($hospitalizacion);
    
    $dni=Auth::user()->dni;
    $model_medico = new Medico();
    $exite_medico=$model_medico->exite_medico($dni);
    $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
  
    $establecimie = "select * from establecimientos e where id = '$prestacion->id_establecimiento'";
    $establecimie = DB::select($establecimie);
    $establecimie= $establecimie[0];
    
    if(isset($emergencia->cama_estado)){
        $cama = "select * from camas c where id = '$emergencia->cama_estado'";
        $cama = DB::select($cama);
        $cama= $cama[0];  
        $existecama = '1';
    }else{
        
           $cama="SELECT * from camas c where id = '506'";
           $cama = DB::select($cama);
             $cama= $cama[0]; 
             $existecama = '0';
    }
    
    $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
    $diagnos = DB::select($diagnos);
    $diagnos = $diagnos[0];
    $diagnos->cabecera = "1) ";
    $diagnos->tipo = "D";
    $diagnosticos1=$diagnos;
    if($emergencia->id_diag_cuer1>0){
       
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer1'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "2) ";
        $diagnos->tipo = "D";
        $diagnosticos2=$diagnos;
    }else{
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "2) ";
        $diagnos->tipo = "D";
        $diagnosticos2=$diagnos;
    }
    
    if($emergencia->id_diag_cuer2>0){
       
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer2'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "3) ";
        $diagnos->tipo = "D";
        $diagnosticos3=$diagnos;
    }else{
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "3) ";
        $diagnos->tipo = "D";
        $diagnosticos3=$diagnos;
    }
   
    if($emergencia->id_tipo_egres_med=="2"){
        $upss="SELECT 
        concat(c.nombre,' / ',u.codigo ) as nombre,
        u.id as id_upss,
        u.codigo as codigo_upss,
        c.id as id_consultorio,
        c.nombre as nombre_consultorio
        from consultorios c 
        inner join upsses u on u.id_consultorio = c.id 
        where u.id_tipo_atencion = '3' and u.estado = '1' and u.id='$emergencia->id_upss_alta'";
        $upss= DB::select($upss);
        $upss=$upss[0];
      }else{
        $upss=0;
      }
      
      $medicoegreso = "SELECT * from users u  where id = '$emergencia->id_medico_egreso'";
      $medicoegreso = DB::select($medicoegreso);
      $medicoegreso=$medicoegreso[0];

    $asegu= "SELECT * from asegurados where id= '$emergencia->id_asegurado'";
    $asegu = DB::select($asegu);
    $asegu=$asegu[0];
    
    //$tiempo="SELECT DATE_PART('YEAR',AGE('$asegu->fecha_nac'::date))";
    $tiempoe="SELECT to_char(age(TO_DATE('$asegu->fecha_nac' , 'DD/MM/YYYY')), 'YY') as fec_nacim_descrip";
    $tiempoe = DB::select($tiempoe);
    $tiempoe=$tiempoe[0]->fec_nacim_descrip;

    $tiempom="SELECT to_char(age(TO_DATE('$asegu->fecha_nac' , 'DD/MM/YYYY')), 'MM') as fec_nacim_descrip";
    $tiempom = DB::select($tiempom);
    $tiempom=$tiempom[0]->fec_nacim_descrip;

    $tiempod="SELECT to_char(age(TO_DATE('$asegu->fecha_nac' , 'DD/MM/YYYY')), 'DD') as fec_nacim_descrip";
    $tiempod = DB::select($tiempod);
    $tiempod=$tiempod[0]->fec_nacim_descrip;

    $triajes = "SELECT * from triajes where id = '$emergencia->id_triaje'";
    $triajes = DB::select($triajes);
    $triajes=$triajes[0];
    
        $inicio= ($emergencia->fecha_registro_ingreso);
        $final= ($emergencia->fecha_alta_medica);
        $inicios= strtotime($emergencia->fecha_registro_ingreso);
        $fecha_alta=explode(" ",$emergencia->fecha_alta_medica);
        $hora_alta=explode(" ",$emergencia->hora_alta_medica);
        $fecha_finals=$fecha_alta[0].' '.$hora_alta[0];
        //$finals= strtotime($emergencia->fecha_alta_medica);
        $finals= strtotime($fecha_finals);
  
          $resulta=($finals-$inicios)/86400;
          $resulta=round($resulta);
         
          if($resulta == "0"){
            $resta = '1';
          }else{
            $resta=$resulta;
          }

          $buscareceta = "SELECT count(id)  from receta_vales rv where id_prestacion = '$prestacion->id'";
          $buscareceta = DB::select($buscareceta);
          $buscareceta=$buscareceta[0]->count;
         
          if($buscareceta != 0){
            $recetas = "SELECT
            rv.nro_receta,
            rv.fecha_firma,
            pr.id_producto,
            pr.cantidad_prescrita,
            p2.nombre,
            pr.posologia,
            rv.fecha_expedicion 
            from prestaciones p 
            inner join receta_vales rv on rv.id_prestacion = p.id 
            inner join producto_recetas pr on pr.id_receta_vale = rv.id
            inner join productos p2 on p2.id = pr.id_producto 
            where p.id = '$prestacion->id'";
            $recetas = DB::select($recetas);
          }else{
            $recetas = "SELECT
            rv.nro_receta,
            rv.fecha_firma,
            pr.id_producto,
            pr.cantidad_prescrita,
            p2.nombre,
            pr.posologia,
            rv.fecha_expedicion 
          from prestaciones p 
          inner join receta_vales rv on rv.id_prestacion = p.id 
          inner join producto_recetas pr on pr.id_receta_vale = rv.id
          inner join productos p2 on p2.id = pr.id_producto 
          where p.id = '1162594'";
          $recetas = DB::select($recetas);
          }
          
          if(isset($emergencia->id_establecimiento_traslado)){
            $establedesti="SELECT * from establecimientos e where id = '$emergencia->id_establecimiento_traslado'";
            $establedesti = DB::select($establedesti);
            $establedesti= $establedesti[0];
          }
          
    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);  
    
    if(isset($emergencia->id_departamento)){
        $depa = "SELECT * from departamentos d where id_departamento = '$emergencia->id_departamento'";
    $depa = DB::select($depa);
    $depa=$depa[0];  
    $existedepa="1";
    }else{
        $depa = "SELECT * from departamentos d where id_departamento = '14'";
        $depa = DB::select($depa);
        $depa=$depa[0];  
        $existedepa="0"; 
    }
   
    if(isset($emergencia->id_provincia)){
        $provin = "SELECT * from provincias p where id_provincia = '$emergencia->id_provincia'";
    $provin = DB::select($provin);
    $provin=$provin[0];  
    $existeprovin="1";
    }else{
        $provin = "SELECT * from provincias p where id_provincia = '127'";
        $provin = DB::select($provin);
        $provin=$provin[0];  
        $existeprovin="0"; 
    }

    if(isset($emergencia->id_distrito)){
        $distri = "SELECT * from distritos d where id_distrito = '$emergencia->id_distrito'";
    $distri = DB::select($distri);
    $distri=$distri[0];  
    $existedistri="1";
    }else{
        $distri = "SELECT * from distritos d where id_distrito = '1246'";
        $distri = DB::select($distri);
        $distri=$distri[0];  
        $existedistri="0"; 
    }
  
    $dividido5 = str_split($diagnosticos1->nombre, 35);
    $dividido3 = str_split($aseguradoemer->domicilio, 26);
    $dividido4 = str_split($distri->descripcion, 12);
    $dividido0 = str_split($prestacion->anamnesis, 78);
    $dividido = str_split($evaluacion->examen_fisico, 78);
    $dividido1 = str_split($evaluacion->examen_auxiliar, 60);
    $dividido2 = str_split($prestacion->plan_trabajo, 40);
    $dividido6 = str_split($prestacion->tratamiento_auxiliar, 50);

            $pdf = \PDF::loadView('pdf.ref_contra_eme',[
                'dividido0'=>$dividido0,
                'dividido'=>$dividido,
                'dividido1'=>$dividido1,
                'dividido2'=>$dividido2,
                'dividido3'=>$dividido3,
                'dividido4'=>$dividido4,
                'dividido5'=>$dividido5,
                'dividido6'=>$dividido6,
                'medicoegreso'=>$medicoegreso,
                'evaluacion'=>$evaluacion,
                'existedistri'=>$existedistri,
                'distri'=>$distri,
                'existeprovin'=>$existeprovin,
                'provin'=>$provin,
                'existedepa'=>$existedepa,
                'depa'=>$depa,
                'establedesti'=>$establedesti,
                'upss'=>$upss,
                'recetas'=>$recetas,
                'buscareceta'=>$buscareceta,
                'resta'=>$resta,
                'triajes'=>$triajes,
                'existecama'=>$existecama,
                'prestacion'=>$prestacion,
                'fechaActual'=>$fechaActual,
                'horaActual'=>$horaActual,
                'cama'=>$cama,
                'tipo_benefi'=>$tipo_benefi,
                'tiempoe'=>$tiempoe,
                'tiempod'=>$tiempod,
                'tiempom'=>$tiempom,
                'procedimiento'=>$procedimiento,
                'condiegre'=>$condiegre,
                'priori'=>$priori,
                'motivoingre'=>$motivoingre,
                'estadoingre'=>$estadoingre,
                'formaingreso'=>$formaingreso,
                'aseguradoemer'=>$aseguradoemer,
                'establecimie'=>$establecimie, 
                'diagnosticos1'=>$diagnosticos1, 
                'diagnosticos2'=>$diagnosticos2, 
                'diagnosticos3'=>$diagnosticos3, 
                'diagnostico'=>$diagnostico, 
                'id_prestacion'=>$id_prestacion,
                'nivel'=>$nivel, 
                'hospitalizacion'=>$hospitalizacion, 
                'consulta_externa'=>$consulta_externa,
                'paciente_emergencia'=>$paciente_emergencia, 
                 'emergencia'=>$emergencia]);
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('hc_servicio.pdf');
        

        else:
            Session::flash('flash_message', 'Documento no encontrado, verificar el dni o coordine con el administrador del sistema');
            return redirect('/dashboard');
        endif;
    }


    public function mostrarpdffema1($f)   {

          $personal=DB::table('fema_fichas')
          ->select('fema_fichas.*')
          ->where('id',$f)
          ->first();
        
          $asegurado=DB::table('asegurados')
          ->select('asegurados.*')
          ->where('nro_doc_ident',$personal->nro_doc_ident)
          ->first();

          $codigo = $personal->carnet;

          $sin_aptitud = "SELECT id,carnet FROM fema_fichas where dato_year_ficha = '2026' and estado = '1' and aptitud_maspe is null and carnet is not null limit 10000";
          $sin_aptitud = DB::select($sin_aptitud);

          foreach($sin_aptitud as $sina){
            $codigo = $sina->carnet;
            $wsdirehum = new \App\Ws\Dirrehum;
            $datos = $wsdirehum->MaspePNP($codigo);
            $datos = $datos->MaspeResult;
            $dato_aptitud = $datos->TCIDDM_DESC;
            if(isset($dato_aptitud)) {
                    $model_ficha_fema_envio = \App\Models\Fema_ficha::find($sina->id);
                    $model_ficha_fema_envio->aptitud_maspe = $dato_aptitud;
                    $model_ficha_fema_envio->save();
               
            };

          }
        
            $codigo = $personal->carnet;

            $wsdirehum = new \App\Ws\Dirrehum;
            $datos = $wsdirehum->MaspePNP($codigo);
            $datos = $datos->MaspeResult;
            $dato_aptitud = $datos->TCIDDM_DESC;
        
            if(isset($dato_aptitud)) {
    
                if($personal->aptitud_maspe == "" or $personal->aptitud_maspe == "NULL"){
                    $model_ficha_fema_envio = \App\Models\Fema_ficha::find($personal->id);
                    $model_ficha_fema_envio->aptitud_maspe   = $dato_aptitud;
                    $model_ficha_fema_envio->save();
                };
            };

          $tipo_doc = 1;
          $nro_doc = $personal->nro_doc_ident;

          if($personal->grado == '0'){
            $datogrado = $wsdirehum->BuscarTitularFamiliar($tipo_doc, $nro_doc);
            $datogrado = $datogrado->BuscarTitularFamiliarResult;
            $datos_faltante = $datogrado->TitularFamiliar;
            $model_ficha_fema_envio = \App\Models\Fema_ficha::find($personal->id);
            $model_ficha_fema_envio->grado   = $datos_faltante->GRADO;
            $model_ficha_fema_envio->save();
   
          }

                    if($personal->carnet == '0'){
            $datogrado = $wsdirehum->BuscarTitularFamiliar($tipo_doc, $nro_doc);
            $datogrado = $datogrado->BuscarTitularFamiliarResult;
            $datos_faltante = $datogrado->TitularFamiliar;
            $model_ficha_fema_envio = \App\Models\Fema_ficha::find($personal->id);
            $model_ficha_fema_envio->carnet   = $datos_faltante->CARNE;
            $model_ficha_fema_envio->save();
   
          }
          
          $personal=DB::table('fema_fichas')
          ->select('fema_fichas.*')
          ->where('id',$personal->id)
          ->first();
  
          if(count($personal)>0):
             
              if($personal->establecimiento_cierre == "NULL" or $personal->establecimiento_cierre == ""){
                  $personal->establecimiento_cierre = $personal->id_establecimiento;
              }
  
              $localescierre=DB::table('establecimientos')
              ->select('establecimientos.*')
              ->where('id',$personal->establecimiento_cierre)
              ->first(); 
  
  
              $subprocesos=DB::table('fema_tipo_procesos')
              ->select('fema_tipo_procesos.*')
              ->where('id',$personal->id_fema_tipo_proceso)
              ->first(); 
  
              $procesos=DB::table('fema_procesos')
              ->select('fema_procesos.*')
              ->where('id',$subprocesos->id_proceso)
              ->first();
  
              $triaje=DB::table('fema_triajes')
              ->select('fema_triajes.*')
              ->where('id_fema_ficha',$personal->id)
              ->first();
  
              $locales=DB::table('establecimientos')
              ->select('establecimientos.*')
              ->where('id',$personal->id_establecimiento)
              ->first(); 
  
              $deri="SELECT 
              count(d.codigo) as fuederivado
              from fema_fichas ff 
              inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
              inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
              inner join diagnosticos d on d.id = fd.id_diagnostico 
              where ff.id = '$f' and d.estado_fema = '1'";
              $deri=DB::select($deri);
              $deri=$deri[0]->fuederivado;
              
              $verttatu="SELECT 
              count(ff.id) as tatuaje 
              from fema_fichas ff 
              inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
              inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
              inner join diagnosticos d on d.id = fd.id_diagnostico 
              inner join consultorios c on c.id  = fd.id_servicio_derivacion
              where ff.id = '$f' and d.estado_fema = '1' and d.codigo = 'L81.8'";
              $verttatu=DB::select($verttatu);
              $verttatu=$verttatu[0]->tatuaje;

              if($verttatu == '0'):
                $versi="SELECT 
                u.name,
                u.paterno,
                u.materno,  
                d.codigo,
                c.nombre as consulto1,
                ff.flag_firma as consulto2,
                fd.id_servicio_derivacion,
                fd.derivado_at 
                from fema_fichas ff 
                inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
                inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
                inner join diagnosticos d on d.id = fd.id_diagnostico 
                inner join consultorios c on c.id  = fd.id_servicio_derivacion
                left join users u on u.id = fd.derivado_us 
                where ff.id = '$f' and d.estado_fema = '1'";
                $derivado = DB::select($versi);
                else:
                    $versi="SELECT 
                    u.name,
                    u.paterno,
                    u.materno,
                    d.codigo,
                    c.nombre as consulto1,
                    fd.id_servicio_derivacion,
                    c2.nombre as consulto2,
                    fd.id_servicio_derivacion2,
                    fd.derivado_at 
                    from fema_fichas ff 
                    inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
                    inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
                    inner join diagnosticos d on d.id = fd.id_diagnostico 
                    inner join consultorios c on c.id  = fd.id_servicio_derivacion
                    left join consultorios c2 on c2.id  = fd.id_servicio_derivacion2
                    left join users u on u.id = fd.derivado_us 
                    where ff.id = '$f' and d.estado_fema = '1'";
                    $derivado = DB::select($versi);
                endif;
               $date = Carbon::now();
               $fechaActual = $date->format('d/m/Y');
               $horaActual = $date->format('H:i:s');
              $pdf = \PDF::loadView('pdf.fema_cierre_cie10_0',['personal'=>$personal,'triaje'=>$triaje,'horaActual'=>$horaActual,'fechaActual'=>$fechaActual,'localescierre'=>$localescierre, 'locales'=>$locales,'procesos'=>$procesos,'subprocesos'=>$subprocesos, 'deri'=>$deri,'derivado'=>$derivado,'verttatu'=>$verttatu]);
              $pdf->getDomPDF()->set_option("enable_php", true);
              $pdf->setPaper('A4', 'portrait');
              return $pdf->stream('hc_servicio.pdf');
          else:
              Session::flash('flash_message', 'Documento no encontrado, verificar el dni o coordine con el administrador del sistema');
              return redirect('/dashboard');
          endif;
      }
      public function mostrarpdflaboratoriofema($f)   {
        $personal= "SELECT * FROM fema_fichas where id = '$f' ";
        $personal = DB::select($personal);
        $personal = $personal[0];
      
        $laboratorio=DB::table('fema_examenes')
        ->select('fema_examenes.*')
        ->where('id_fema_ficha',$personal->id)
        ->where('id_tipo_examen','2')
        ->first();

        $profesional=DB::table('medicos')
        ->select('medicos.*')
        ->where('id',$laboratorio->id_profesional)
        ->first();

        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');
         $pdf = \PDF::loadView('pdf.mostrarPDFlaboratorioFEMA',['personal'=>$personal,'laboratorio'=>$laboratorio,'horaActual'=>$horaActual, 'fechaActual'=>$fechaActual,'profesional'=>$profesional]);
         $pdf->getDomPDF()->set_option("enable_php", true);
         $pdf->setPaper('A4', 'portrait');
         return $pdf->stream('PDF_lab_fema.pdf');
        }

    public function mostrarpdfderivado($f)   {

        $personal=DB::table('fema_fichas')
        ->select('fema_fichas.*')
        ->where('id',$f)
        ->first();
       
        if(count($personal)>0):

            $subprocesos=DB::table('fema_tipo_procesos')
            ->select('fema_tipo_procesos.*')
            ->where('id',$personal->id_fema_tipo_proceso)
            ->first(); 

            $procesos=DB::table('fema_procesos')
            ->select('fema_procesos.*')
            ->where('id',$subprocesos->id_proceso)
            ->first();

            $triaje=DB::table('fema_triajes')
            ->select('fema_triajes.*')
            ->where('id_fema_ficha',$personal->id)
            ->first();

            $locales=DB::table('establecimientos')
            ->select('establecimientos.*')
            ->where('id',$personal->id_establecimiento)
            ->first(); 

            $deri="SELECT 
            count(d.codigo) as fuederivado
            from fema_fichas ff 
            inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
            inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
            inner join diagnosticos d on d.id = fd.id_diagnostico 
            where ff.id = '$f' and d.estado_fema = '1'";
            $deri=DB::select($deri);
            $deri=$deri[0]->fuederivado;

            $verttatu="SELECT 
            count(ff.id) as tatuaje 
            from fema_fichas ff 
            inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
            inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
            inner join diagnosticos d on d.id = fd.id_diagnostico 
            inner join consultorios c on c.id  = fd.id_servicio_derivacion
            where ff.id = '$f' and d.estado_fema = '1' and d.codigo = 'L81.8'";
            $verttatu=DB::select($verttatu);
            $verttatu=$verttatu[0]->tatuaje;
            
            if($verttatu == '0'):
                $versi="SELECT 
              u.name,
              u.paterno,
              u.materno,  
              d.codigo,
              c.nombre as consulto1,
              ff.flag_firma as consulto2,
              fd.id_servicio_derivacion,
              fd.derivado_at 
              from fema_fichas ff 
              inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
              inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
              inner join diagnosticos d on d.id = fd.id_diagnostico 
              inner join consultorios c on c.id  = fd.id_servicio_derivacion
              left join users u on u.id = fd.derivado_us 
              where ff.id = '$f' and d.estado_fema = '1'";
            $derivado = DB::select($versi);
            else:
                $versi="SELECT 
                  u.name,
                  u.paterno,
                  u.materno,
                  d.codigo,
                  c.nombre as consulto1,
                  fd.id_servicio_derivacion,
                  c2.nombre as consulto2,
                  fd.id_servicio_derivacion2,
                  fd.derivado_at 
                  from fema_fichas ff 
                  inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
                  inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
                  inner join diagnosticos d on d.id = fd.id_diagnostico 
                  inner join consultorios c on c.id  = fd.id_servicio_derivacion
                  left join consultorios c2 on c2.id  = fd.id_servicio_derivacion2
                  left join users u on u.id = fd.derivado_us 
                  where ff.id = '$f' and d.estado_fema = '1'";
                $derivado = DB::select($versi);
            endif;
           $date = Carbon::now();
           $fechaActual = $date->format('d/m/Y');
           $horaActual = $date->format('H:i:s');
            $pdf = \PDF::loadView('pdf.mostrarpdfderivado',['personal'=>$personal,'triaje'=>$triaje,'horaActual'=>$horaActual, 'fechaActual'=>$fechaActual,'locales'=>$locales,'procesos'=>$procesos,'subprocesos'=>$subprocesos, 'deri'=>$deri,'derivado'=>$derivado,'verttatu'=>$verttatu]);
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('hc_servicio.pdf');
        else:

            Session::flash('flash_message', 'Documento no encontrado, verificar el dni o coordine con el administrador del sistema');
            return redirect('/dashboard');
        endif;
    }

    public function mostrarpdfconsolidado($f)   {

        $personal=DB::table('fema_fichas')
        ->select('fema_fichas.*')
        ->where('id',$f)
        ->first();

        if(count($personal)>0):
           
            if($personal->establecimiento_cierre == "NULL" or $personal->establecimiento_cierre == ""){
                $personal->establecimiento_cierre = $personal->id_establecimiento;
            }
            
            $personalapertura = DB::table('users')
            ->select('users.*')
            ->where('id',$personal->created_us)
            ->first();

            $personalcierre = DB::table('users')
            ->select('users.*')
            ->where('id',$personal->cierre_created_us)
            ->first();

            $localescierre=DB::table('establecimientos')
            ->select('establecimientos.*')
            ->where('id',$personal->establecimiento_cierre)
            ->first(); 
            
            $usuario=DB::table('asegurados')
            ->select('asegurados.*')
            ->where('nro_doc_ident',$personal->nro_doc_ident)
            ->where('id_estado_reg','1')
            ->first();

            $subprocesos=DB::table('fema_tipo_procesos')
            ->select('fema_tipo_procesos.*')
            ->where('id',$personal->id_fema_tipo_proceso)
            ->first(); 

            $procesos=DB::table('fema_procesos')
            ->select('fema_procesos.*')
            ->where('id',$subprocesos->id_proceso)
            ->first();

            $triaje=DB::table('fema_triajes')
            ->select('fema_triajes.*')
            ->where('id_fema_ficha',$personal->id)
            ->first();

            $personaltriaje = DB::table('users')
            ->select('users.*')
            ->where('id',$triaje->created_us)
            ->first();

            $locales=DB::table('establecimientos')
            ->select('establecimientos.*')
            ->where('id',$personal->id_establecimiento)
            ->first(); 

            $deri="SELECT 
            count(d.codigo) as fuederivado
            from fema_fichas ff 
            inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
            inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
            inner join diagnosticos d on d.id = fd.id_diagnostico 
            where ff.id = '$f' and d.estado_fema = '1'";
            $deri=DB::select($deri);
            $deri=$deri[0]->fuederivado;
               
                if($deri == "0"):
                        $verttatu = 0;
                        $derivado = 0;
                else:
                    $verttatu="SELECT 
                    count(ff.id) as tatuaje 
                    from fema_fichas ff 
                    inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
                    inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
                    inner join diagnosticos d on d.id = fd.id_diagnostico 
                    inner join consultorios c on c.id  = fd.id_servicio_derivacion
                    where ff.id = '$f' and d.estado_fema = '1' and d.codigo = 'L81.8'";
                    $verttatu=DB::select($verttatu);
                    $verttatu=$verttatu[0]->tatuaje;
                 
                    if($verttatu == '0'):
                      $versi="SELECT 
                      u.name,
                      u.paterno,
                      u.materno,  
                      d.codigo,
                      c.nombre as consulto1,
                      ff.flag_firma as consulto2,
                      fd.id_servicio_derivacion,
                      fd.derivado_at 
                      from fema_fichas ff 
                      inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
                      inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
                      inner join diagnosticos d on d.id = fd.id_diagnostico 
                      left join consultorios c on c.id  = fd.id_servicio_derivacion
                      left join users u on u.id = fd.derivado_us 
                      where ff.id = '$f' and d.estado_fema = '1'";
                      $derivado = DB::select($versi);
                      else:
                          $versi="SELECT 
                          u.name,
                          u.paterno,
                          u.materno,
                          d.codigo,
                          c.nombre as consulto1,
                          fd.id_servicio_derivacion,
                          c2.nombre as consulto2,
                          fd.id_servicio_derivacion2,
                          fd.derivado_at 
                          from fema_fichas ff 
                          inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
                          inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
                          inner join diagnosticos d on d.id = fd.id_diagnostico 
                          left join consultorios c on c.id  = fd.id_servicio_derivacion
                          left join consultorios c2 on c2.id  = fd.id_servicio_derivacion2
                          left join users u on u.id = fd.derivado_us 
                          where ff.id = '$f' and d.estado_fema = '1'";
                          $derivado = DB::select($versi);
                      endif;
                      $derivado=$derivado[0];
                endif;
                        
              $examen1 ="SELECT 
                          fe.id_tipo_examen as servicio,
                          u.name,
                          u.paterno,
                          u.materno,
                          fe.capacidad_masticatoria,
                          fe.cod_odontograma,
                          fe.descripcion_odontograma,
                          e.nombre as nombre_establecimiento,
                          fe.created_at as fecha
                          from fema_fichas ff
                          inner join fema_examenes fe on ff.id = fe.id_fema_ficha 
                          inner join users u  on fe.created_us = u.id 
                          left join establecimientos e on e.id = fe.id_establecimiento 
                          where ff.id = '$personal->id' and ff.estado <> 0 and fe.id_tipo_examen = 1 and fe.estado <> 0";
              $examen1 = DB::select($examen1);
              $examen1=$examen1[0];

              $examen3 ="SELECT 
                          fe.id_tipo_examen as servicio,
                          u.name,
                          u.paterno,
                          u.materno,
                          fe.antecedentes,
                          fe.examen_clinico,
                          e.nombre as nombre_establecimiento,
                          fe.created_at as fecha
                          from fema_fichas ff
                          inner join fema_examenes fe on ff.id = fe.id_fema_ficha 
                          inner join users u  on fe.created_us = u.id 
                          left join establecimientos e on e.id = fe.id_establecimiento 
                          where ff.id = '$personal->id' and ff.estado <> 0 and fe.id_tipo_examen = 3 and fe.estado <> 0";
              $examen3 = DB::select($examen3);
              $examen3=$examen3[0];

              $examen4 ="SELECT 
                          fe.id_tipo_examen as servicio,
                          u.name,
                          u.paterno,
                          u.materno,
                          fe.area_emocional,
                          fe.area_cognitiva,
                          fe.derivacion,
                          e.nombre as nombre_establecimiento,
                          fe.created_at as fecha
                          from fema_fichas ff
                          inner join fema_examenes fe on ff.id = fe.id_fema_ficha 
                          inner join users u  on fe.created_us = u.id 
                          left join establecimientos e on e.id = fe.id_establecimiento 
                          where ff.id = '$personal->id' and ff.estado <> 0 and fe.id_tipo_examen = 4 and fe.estado <> 0";
              $examen4 = DB::select($examen4);
              $examen4=$examen4[0];
              
              $examen8 ="SELECT 
              fe.id_tipo_examen as servicio,
              u.name,
              u.paterno,
              u.materno,
              fe.vision_cercana_od,
              fe.vision_cercana_od_corregida,
              fe.vision_cercana_oi,
              fe.vision_cercana_oi_corregida,
              fe.vision_cercana_od2,
              fe.vision_cercana_od_corregida2,
              fe.vision_cercana_oi2,
              fe.vision_cercana_oi_corregida2,
              fe.tonometria,
              fe.pupilas,
              fe.motil_ocular,
              fe.fondo_ojo,
              fe.vision_colores,
              e.nombre as nombre_establecimiento,
              fe.created_at as fecha
              from fema_fichas ff
              inner join fema_examenes fe on ff.id = fe.id_fema_ficha 
              inner join users u  on fe.created_us = u.id 
              left join establecimientos e on e.id = fe.id_establecimiento 
              where ff.id = '$personal->id' and ff.estado <> 0 and fe.id_tipo_examen = 8 and fe.estado <> 0";
              $examen8 = DB::select($examen8);
              $examen8=$examen8[0];

              $examen9 ="SELECT 
              fe.id_tipo_examen as servicio,
              u.name,
              u.paterno,
              u.materno,
              fe.agudeza_auditiva_od,
              fe.agudeza_auditiva_od_suficiente,
              fe.agudeza_auditiva_oi,
              fe.agudeza_auditiva_oi_suficiente,
              fe.examen_nariz_garganta,
              fe.membrana_timpanica,
              e.nombre as nombre_establecimiento,
              fe.created_at as fecha
              from fema_fichas ff
              inner join fema_examenes fe on ff.id = fe.id_fema_ficha 
              inner join users u  on fe.created_us = u.id 
              left join establecimientos e on e.id = fe.id_establecimiento 
              where ff.id = '$personal->id' and ff.estado <> 0 and fe.id_tipo_examen = 9 and fe.estado <> 0";
              $examen9 = DB::select($examen9);
              $examen9=$examen9[0];

              if($usuario->sexo == "F"){
                  $examen6 ="SELECT 
                  fe.id_tipo_examen as servicio,
                  u.name,
                  u.paterno,
                  u.materno,
                  fe.g,
                  fe.p,
                  fe.menarquia,
                  fe.mamografia,
                  fe.irs,
                  fe.urs,
                  fe.fur,
                  fe.andria,
                  fe.mac,
                  fe.antecedentes_personales,
                  fe.examen_pap,
                  fe.fecha_examen_pap,
                  fe.resultado_examen_pap,
                  fe.prueba_iva,
                  fe.resultado_iva,
                  fe.gestante,
                  e.nombre as nombre_establecimiento,
                  fe.created_at as fecha
                  from fema_fichas ff
                  inner join fema_examenes fe on ff.id = fe.id_fema_ficha 
                  inner join users u  on fe.created_us = u.id 
                  left join establecimientos e on e.id = fe.id_establecimiento 
                  where ff.id = '$personal->id' and ff.estado <> 0 and fe.id_tipo_examen = 6 and fe.estado <> 0";
                  $examen6 = DB::select($examen6);
                  $examen6=$examen6[0];
              }else{
                  $examen6 ="SELECT 
                  fe.id_tipo_examen as servicio,
                  u.name,
                  u.paterno,
                  u.materno,
                  fe.g,
                  fe.p,
                  fe.menarquia,
                  fe.mamografia,
                  fe.irs,
                  fe.urs,
                  fe.fur,
                  fe.andria,
                  fe.mac,
                  fe.antecedentes_personales,
                  fe.examen_pap,
                  fe.fecha_examen_pap,
                  fe.resultado_examen_pap,
                  fe.prueba_iva,
                  fe.resultado_iva,
                  fe.gestante,
                  e.nombre as nombre_establecimiento,
                  fe.created_at as fecha
                  from fema_fichas ff
                  inner join fema_examenes fe on ff.id = fe.id_fema_ficha 
                  inner join users u  on fe.created_us = u.id 
                  left join establecimientos e on e.id = fe.id_establecimiento 
                  where ff.id = '$personal->id' and ff.estado <> 0 and fe.id_tipo_examen = 1 and fe.estado <> 0";
                  $examen6 = DB::select($examen6);
                  $examen6=$examen6[0];
              }
              $id_usuari = Auth::user()->id;
              $personaimprimer=DB::table('users')
              ->select('users.*')
              ->where('id',$id_usuari)
              ->first();
             $date = Carbon::now();
             $fechaActual = $date->format('d/m/Y');
             $horaActual = $date->format('H:i:s');
            $pdf = \PDF::loadView('pdf.fema_consolidado',['usuario'=>$usuario,'personaimprimer'=>$personaimprimer,'fechaActual'=>$fechaActual,'horaActual'=>$horaActual,'personalcierre'=>$personalcierre,'personalapertura'=>$personalapertura,'personaltriaje'=>$personaltriaje,'examen1'=>$examen1,'examen3'=>$examen3,'examen4'=>$examen4,'examen6'=>$examen6,'examen8'=>$examen8,'examen9'=>$examen9,'personal'=>$personal,'triaje'=>$triaje,'localescierre'=>$localescierre, 'locales'=>$locales,'procesos'=>$procesos,'subprocesos'=>$subprocesos, 'deri'=>$deri,'derivado'=>$derivado,'verttatu'=>$verttatu]);
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('hc_servicio.pdf');
        else:
            Session::flash('flash_message', 'Documento no encontrado, verificar el dni o coordine con el administrador del sistema');
            return redirect('/dashboard');
        endif;
    }


    public function mostrarPdfEmergenciaTriaje($id)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
        $model_emergencia_triaje = new Emergencia();
        $WsReniec = new \App\Ws\Reniec;
        $apersonal = array();
        $emergencia_triaje = $model_emergencia_triaje->obtenerReporteSaludMaterna($id);
        //$emergencia_triaje = $model_emergencia_triaje->obtenerRegistroEmergencia($id);
        
        $dni = $emergencia_triaje->dni_beneficiario;
 
        //===================WS Reniec===================
       // $datos_reniec = $WsReniec->BuscarDNI($dni);
       // $datos_reniec = ""; // Asigna una cadena vacía en lugar de null
//$personal = json_decode($datos_reniec, true);
       //$datos_reniec = $WsReniec->BuscarDNI($dni);
       //$personal = json_decode($datos_reniec, true);
        $personal = false; //se coloco xq no se cuenta con reniec actualmente
        if($personal){ 
            try {
                $apersonal['estado_civil'] = empty($personal['estado_civil']) ? '-' : $personal['estado_civil'];
                if($emergencia_triaje->domicilio_aseg !=null && $emergencia_triaje->departamento_descripcion !=null && 
                    $emergencia_triaje->provincia_descripcion !=null && $emergencia_triaje->distrito_descripcion !=null){ 
                        $apersonal['direccionDomicilio'] = $emergencia_triaje->domicilio_aseg;
                        $apersonal['departamentoDomicilio'] = $emergencia_triaje->departamento_descripcion;
                        $apersonal['provinciaDomicilio'] = $emergencia_triaje->provincia_descripcion;
                        $apersonal['distritoDomicilio'] = $emergencia_triaje->distrito_descripcion;
                }else{
                    $apersonal['direccionDomicilio'] = empty($personal['direccionDomicilio']) ? '-' : $personal['direccionDomicilio'];
                    if($personal['distritoDomicilio']=='CALLAO'){
                        $apersonal['departamentoDomicilio'] ='CALLAO';
                    }else{
                        $apersonal['departamentoDomicilio'] = empty($personal['departamentoDomicilio']) ? 'CALLAO' : $personal['departamentoDomicilio'];
                    }
                    $apersonal['provinciaDomicilio'] = empty($personal['provinciaDomicilio']) ? '-' : $personal['provinciaDomicilio'];
                    $apersonal['distritoDomicilio'] = empty($personal['distritoDomicilio']) ? '-' : $personal['distritoDomicilio'];
                }
            } catch (Exception $e) {
                if($emergencia_triaje->domicilio_aseg !=null && $emergencia_triaje->departamento_descripcion !=null && 
                    $emergencia_triaje->provincia_descripcion !=null && $emergencia_triaje->distrito_descripcion !=null){ 
                        $apersonal['direccionDomicilio'] = $emergencia_triaje->domicilio_aseg;
                        $apersonal['departamentoDomicilio'] = $emergencia_triaje->departamento_descripcion;
                        $apersonal['provinciaDomicilio'] = $emergencia_triaje->provincia_descripcion;
                        $apersonal['distritoDomicilio'] = $emergencia_triaje->distrito_descripcion;
                }else{
                    $apersonal['estado_civil'] = '-';
                    $apersonal['direccionDomicilio'] = '-';
                    $apersonal['departamentoDomicilio'] = '-';
                    $apersonal['provinciaDomicilio'] = '-';
                    $apersonal['distritoDomicilio'] = '-';
                }
            }
        } else{ 
            if($emergencia_triaje->domicilio_aseg !=null && $emergencia_triaje->departamento_descripcion !=null && 
                $emergencia_triaje->provincia_descripcion !=null && $emergencia_triaje->distrito_descripcion !=null){ 
                $apersonal['direccionDomicilio'] = $emergencia_triaje->domicilio_aseg;
                $apersonal['departamentoDomicilio'] = $emergencia_triaje->departamento_descripcion;
                $apersonal['provinciaDomicilio'] = $emergencia_triaje->provincia_descripcion;
                $apersonal['distritoDomicilio'] = $emergencia_triaje->distrito_descripcion;
                $apersonal['estado_civil'] = '-';
            }else{
                $apersonal['estado_civil'] = '-';
                $apersonal['direccionDomicilio'] = '-';
                $apersonal['departamentoDomicilio'] = '-';
                $apersonal['provinciaDomicilio'] = '-';
                $apersonal['distritoDomicilio'] = '-';
            }
        }
        //==============================================
         
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
        $model_establecimiento = new Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //--- -----------
        
        $pdf = \PDF::loadView('pdf.firmar_triaje_emergencia', compact(
            'emergencia_triaje', 
             'apersonal',
             'nombre_establecimiento',
             'fechaActual',
             'horaActual'));

        $pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->setPaper('A5', 'portrait');
        $pdf->setPaper('A4', 'portrait'); //Horizontal
        return $pdf->stream('emergencia-triaje.pdf');
    }

    public function mostrarPdfEmergenciaAdmision($id)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
        $apersonal = array();
        $model_emergencia_triaje = new Emergencia();
        $emergencia_triaje = $model_emergencia_triaje->obtenerRegistroEmergencia($id); 
  
        //======================================WS Reniec======================================
        //======================================WS Reniec====================================== 
            /*$WsReniec = new \App\Ws\Reniec;
            $datos_reniec = $WsReniec->BuscarDNI($emergencia_triaje->dni_beneficiario);
            $personal = json_decode($datos_reniec, true); 
            if($personal){ 
                try {
                    $apersonal['estado_civil'] = empty($personal['estado_civil']) ? '-' : $personal['estado_civil'];
                    if($emergencia_triaje->domicilio_aseg !=null && $emergencia_triaje->departamento_descripcion !=null && 
                        $emergencia_triaje->provincia_descripcion !=null && $emergencia_triaje->distrito_descripcion !=null){ 
                        $apersonal['direccionDomicilio'] = $emergencia_triaje->domicilio_aseg;
                        $apersonal['departamentoDomicilio'] = $emergencia_triaje->departamento_descripcion;
                        $apersonal['provinciaDomicilio'] = $emergencia_triaje->provincia_descripcion;
                        $apersonal['distritoDomicilio'] = $emergencia_triaje->distrito_descripcion;
                    } else{
                        $apersonal['direccionDomicilio'] = empty($personal['direccionDomicilio']) ? '-' : $personal['direccionDomicilio'];
                        if($personal['distritoDomicilio']=='CALLAO'){
                            $apersonal['departamentoDomicilio'] ='CALLAO';
                        }else{
                            $apersonal['departamentoDomicilio'] = empty($personal['departamentoDomicilio']) ? 'CALLAO' : $personal['departamentoDomicilio'];
                        }
                        $apersonal['provinciaDomicilio'] = empty($personal['provinciaDomicilio']) ? '-' : $personal['provinciaDomicilio'];
                        $apersonal['distritoDomicilio'] = empty($personal['distritoDomicilio']) ? '-' : $personal['distritoDomicilio'];
                    }
                } catch (Exception $e) {
                    $apersonal['estado_civil'] = '-';
                    if($emergencia_triaje->domicilio_aseg !=null && $emergencia_triaje->departamento_descripcion !=null && 
                        $emergencia_triaje->provincia_descripcion !=null && $emergencia_triaje->distrito_descripcion !=null){ 
                        $apersonal['direccionDomicilio'] = $emergencia_triaje->domicilio_aseg;
                        $apersonal['departamentoDomicilio'] = $emergencia_triaje->departamento_descripcion;
                        $apersonal['provinciaDomicilio'] = $emergencia_triaje->provincia_descripcion;
                        $apersonal['distritoDomicilio'] = $emergencia_triaje->distrito_descripcion;
                    }else{
                        $apersonal['direccionDomicilio'] = '-';
                        $apersonal['departamentoDomicilio'] = '-';
                        $apersonal['provinciaDomicilio'] = '-';
                        $apersonal['distritoDomicilio'] = '-';
                    }
                }
            } else{ */
                if($emergencia_triaje->domicilio_aseg !=null && $emergencia_triaje->departamento_descripcion !=null && 
                    $emergencia_triaje->provincia_descripcion !=null && $emergencia_triaje->distrito_descripcion !=null){ 
                    $apersonal['estado_civil'] = $emergencia_triaje->estado_civil;
                    $apersonal['direccionDomicilio'] = $emergencia_triaje->domicilio_aseg;
                    $apersonal['departamentoDomicilio'] = $emergencia_triaje->departamento_descripcion;
                    $apersonal['provinciaDomicilio'] = $emergencia_triaje->provincia_descripcion;
                    $apersonal['distritoDomicilio'] = $emergencia_triaje->distrito_descripcion;
                }else{
                    $apersonal['estado_civil'] = '-';
                    $apersonal['direccionDomicilio'] = '-';
                    $apersonal['departamentoDomicilio'] = '-';
                    $apersonal['provinciaDomicilio'] = '-';
                    $apersonal['distritoDomicilio'] = '-';
                }
            // }  
        //======================================WS Reniec======================================
        //======================================WS Reniec======================================
        
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');
        //============================================== 
        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
        $model_establecimiento = new Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //--- -----------

        $pdf = \PDF::loadView('pdf.firmar_admision_emergencia', 
        compact(
            'emergencia_triaje',  
            'apersonal',
            'nombre_establecimiento',
            'fechaActual',
            'horaActual'
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->setPaper('A5', 'portrait');
        $pdf->setPaper('A4', 'portrait'); //Horizontal
        return $pdf->stream('emergencia-admision.pdf');
    }

    public function imprimir_oftalmo($prestacion_id)
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $datos_oftalmo = Oftalmologia::where('prestacion_id', '=', $prestacion_id)->first();
        $model_prestacion = new \App\Models\Prestaciones;
        $prestacion = $model_prestacion->GetPrestacionById($prestacion_id);
        //dd($prestacion);
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');
        $fechaOftalmo = $datos_oftalmo->created_at->format('d/m/Y H:i:s');
        //dd($fechaOftalmo);
        //=====CONSULTA PARA OBTENER LA EDAD DESCRIPTIVA 
        $paciente = new Paciente();
        $datos_paciente = Paciente::where('id', '=', $prestacion->id_asegurado)->first();
        //dd($datos_paciente);
        $edad_descriptiva_paciente=$paciente->obtener_edad_descriptiva_paciente($datos_paciente->nro_doc_ident); 
        //dd($edad_descriptiva_paciente);
        
        $pdf = \PDF::loadView('pdf.nuevo_hospitalizado_oftalmo',
        compact(
            'datos_paciente',  
            'edad_descriptiva_paciente',
            'fechaActual',  
            'horaActual',
            'datos_oftalmo',
            'fechaOftalmo',
            'prestacion'
        ));                  
        //dd($pdf);
        
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Oftalmologia.pdf');

       
    }

    public function ver_anexo_3($id_referencia,$id_cita)   {

        $data = DB::table('referencias')
          ->select('asegurados.nombre', 'asegurados.paterno', 'asegurados.materno', 'consultorios.nombre as consultorio', 
            DB::raw("obtener_nombre_establecimiento(referencias.id_establecimiento) as ipress_origen"),
            DB::raw("obtener_nombre_establecimiento(referencias.id_establecimiento_des) as ipress_destino"),
            'consultorios.nombre as servicio_referencia','medicos.nombre as nombre_medico','medicos.paterno as paterno_medico','medicos.materno as materno_medico',
            DB::raw("to_char(citas.fecha_hora, 'YYYY-MM-DD') dia"),DB::raw("to_char(citas.fecha_hora, 'HH:MI AM') hora"),
            DB::raw("sub_consultorios.descripcion as  numero_consultorio_sala_atencion"))
          ->join('citas','referencias.id','=','citas.id_referencia')
          ->join('medicos','referencias.id_medico_referencia','=','medicos.id')
          ->join('prestaciones', 'referencias.id_prestacion', '=', 'prestaciones.id')
          ->join('asegurados', 'prestaciones.id_asegurado', '=', 'asegurados.id')
          ->join('sub_consultorios', 'prestaciones.id_sub_consultorio', '=', 'sub_consultorios.id')
          ->join('consultorios', 'sub_consultorios.id_consultorio', '=', 'consultorios.id')
          ->where('referencias.id', $id_referencia)
          ->first();
          $servicio_referencia="";
          $paciente="";
          $consultorio="";
          $ipress_origen="";
          $ipress_destino="";
          $medico="";
          $dia="";
          $hora="";
          $numero_consultorio_sala_atencion="";

          if(!empty($data->servicio_referencia)){
            $servicio_referencia = $data->servicio_referencia; 
          }
          if(!empty($data->nombre)){
            $paciente = $data->paterno.' '.$data->materno.' , '.$data->nombre; 
          }
          if(!empty($data->consultorio)){
            $consultorio = $data->consultorio; 
          }
          if(!empty($data->ipress_origen)){
            $ipress_origen = $data->ipress_origen; 
          }
          if(!empty($data->ipress_destino)){
            $ipress_destino = $data->ipress_destino; 
          }
          if(!empty($data->nombre_medico)){
            $medico = $data->paterno_medico.' '.$data->materno_medico.' , '.$data->nombre_medico;  
          }
          if(!empty($data->dia)){
            $dia = $data->dia; 
          }
          if(!empty($data->hora)){
            $hora = $data->hora; 
          }
          if(!empty($data->numero_consultorio_sala_atencion)){
            $numero_consultorio_sala_atencion = $data->numero_consultorio_sala_atencion; 
          }
          
            $referencias = DB::table('referencias')
          ->select('referencias.*')
          ->where('id',$id_referencia)
          ->first();

          $detalle_cita = DB::table('citas')
          ->select('citas.*')
          ->where('id',$referencias->id_cita_triaje_referencia)
          ->first();

          $servicios_ha_referir = DB::table('consultorios')
          ->select('consultorios.*')
          ->where('id',$referencias->id_servicio)
          ->first();

          $responsable_cita  = DB::table('users')
          ->select('users.*')
          ->where('id',$detalle_cita->created_us)
          ->first();

          if($detalle_cita->id_medico === null){
            $medico_atencion_cita= '';
          }else{
            $medico_atencioncita = DB::table('medicos')
            ->select('medicos.*')
            ->where('id',$detalle_cita->id_medico)
            ->first();
            $medico_atencion_cita= $medico_atencioncita->paterno .' '.$medico_atencioncita->materno.','.$medico_atencioncita->nombre;
          }
         
          $prestaciones = DB::table('prestaciones')
          ->select('prestaciones.*')
          ->where('id',$referencias->id_prestacion)
          ->first();
          
          $subconsultio = DB::table('sub_consultorios')
          ->select('sub_consultorios.*')
          ->where('id',$prestaciones->id_sub_consultorio)
          ->first();
    
          $servicios_de_referencia = DB::table('consultorios')
          ->select('consultorios.*')
          ->where('id',$subconsultio->id_consultorio)
          ->first();
     
          $servicio_referencia = $servicios_ha_referir->nombre;
           $date = Carbon::now();
           $fechaActual = $date->format('d/m/Y');
           $horaActual = $date->format('H:i:s');

          $pdf = \PDF::loadView('pdf.anexo_03',[
            'detalle_cita'=>$detalle_cita,
            'servicio_referencia'=>$servicio_referencia,
            'paciente'=>$paciente,
            'consultorio'=>$consultorio,
            'ipress_origen'=>$ipress_origen,
            'ipress_destino'=>$ipress_destino,
            'medico'=>$medico,
            'dia'=>$dia,
            'hora'=>$hora,
            'medico_atencion_cita'=>$medico_atencion_cita,
            'responsable_cita'=>$responsable_cita,
            'numero_consultorio_sala_atencion'=>$numero_consultorio_sala_atencion,
            'fechaActual'=>$fechaActual
          ]);
          $pdf->getDomPDF()->set_option("enable_php", true);
          $pdf->setPaper('A4', 'portrait');
          return $pdf->stream('hc_servicio.pdf');
   

}

    public function print_hospitalizacion_atencion($id_prestacion) {

        $prestacion = DB::table('prestaciones')
            ->join('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->join('cambio_camas', function ($join) {
                $join->on('prestaciones.id', '=', 'cambio_camas.id_prestacion')
                     ->where('cambio_camas.id_evolucion', '=', 0);
            })
            ->join('camas','cambio_camas.id_cama_atencion','=','camas.id')
            ->join('cuartos','camas.id_cuarto','=','cuartos.id')
            ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
            ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')

            ->select('prestaciones.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion','camas.nombre as cama','consultorios.nombre as servicio_sala')
            ->where('prestaciones.id', $id_prestacion)
            //->where('prestaciones.id_ext', $id_hospitalizacion)
            //->where('prestaciones.id_tipo_atencion', 3)
            ->where('prestaciones.id_estado_reg', 1)
            ->first();
      
        $hospitalizacion = DB::table('hospitalizaciones')
                ->join('diagnosticos', 'hospitalizaciones.id_diagnostico_inicial','=','diagnosticos.id')
                ->join('camas','hospitalizaciones.id_cama_estado','=','camas.id')
                ->join('cuartos','camas.id_cuarto','=','cuartos.id')
                ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
                ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')
                ->select('hospitalizaciones.*','diagnosticos.codigo as codigo_cie','camas.nombre as cama','consultorios.nombre as servicio_sala')
                ->where('hospitalizaciones.id',$prestacion->id_ext)
                ->first();

        $atencion = DB::table('hospitalizacion_atenciones')
            ->where('id_prestacion', $prestacion->id)
            ->first();

        $recetas = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->where('receta_vales.estado', '<>', 0)
            ->where('receta_vales.id_evaluacion', '=', 0)
            ->where('receta_vales.id_interconsulta', '=', 0)
            ->orderBy('receta_vales.id', 'asc')
            ->get();
        
        $receta_productos = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->where('receta_vales.id_prestacion', $prestacion->id)
            ->select(
                'receta_vales.id as id_receta',
                'producto_recetas.id as id_producto_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            ->orderBy('receta_vales.id', 'asc')
            ->orderBy('producto_recetas.id', 'asc')
            ->get();
        
        $ordenes_laboratorio = DB::table('laboratorios')
            ->where('id_prestacion', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', 0)
            ->select(
                'id',
                'fecha_atencion',
                'fecha_firma',
                'ruta_laboratorio',
                'flag',
                'secuencial',
                'fecha_registro_toma_muestra',
                'estado',
                DB::raw("to_char(fecha_registro_toma_muestra, 'DD/MM/YYYY') AS fecha_toma_muestra")
            )
            ->orderBy('id', 'asc')
            ->get();
        
        $orden_lab_examenes = DB::table('prestacion_procedimientos')
            ->join('labnivelatenciones', 'prestacion_procedimientos.id_rua', '=', 'labnivelatenciones.id')
            ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
            ->where('prestacion_procedimientos.id_prestacion', $prestacion->id)
            ->where('prestacion_procedimientos.id_estado_reg', 1)
            ->select(
                'labnivelatenciones.id as id',
                'prestacion_procedimientos.id_lab_rad as id_orden',
                'labnivelatenciones.codigo_lab as codigo',
                'labnivelatenciones.descripcion_corta as nombre',
                'procedimientos.descripcion as nombre_completo',
                'prestacion_procedimientos.cantidad',
                'prestacion_procedimientos.created_at',
                'labnivelatenciones.id_procedimiento',
                'prestacion_procedimientos.observacion',
                'diagnosticos.codigo as codigo_diagnostico',
                'diagnosticos.nombre as diagnostico',
                'prestacion_procedimientos.tipo_muestra_microbiologia',
                'prestacion_procedimientos.id_solicitud_procedimiento'
            )
            ->orderBy('prestacion_procedimientos.id', 'asc')
            ->get();
        
        $ordenes_imagenologia = DB::table('radiologias')
            ->where('id_prestacion', '=', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', '=', 0)
            ->orderBy('id','asc')
            ->get();

        $orden_img_examenes = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id')
            ->get();

        $interconsultas = DB::table('interconsultas as t8')
            ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
            ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
            ->join('medicos as tr', 't8.personal_salud_interconsulta', '=', 'tr.id')
            ->where('t8.id_prestacion', $prestacion->id)
            ->where('t8.id_evaluacion', 0)
            ->where('t8.id_estado_reg', 1)
            ->select(
                't8.id',
                't3.nombre as establecimiento',
                't8.id_servicio',
                't6.nombre as servicio',
                'tr.nombre as profesional_nombre',
                'tr.paterno as profesional_paterno',
                'tr.materno as profesional_materno',
                'tr.colegiatura as profesional_colegiatura',
                't8.codigo_upss as codigo_interconsulta',
                't8.motivo_interconsulta',
                't8.resumen_interconsulta',
                't8.fecha_firma_interconsulta',
                't8.id as id_interconsulta',
                't8.created_at'
            )
            ->orderBy('t8.created_at', 'ASC')
            ->get();
    

        $diagnosticos = DB::table('receta_diagnosticos as rd')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->select('rd.*', 'd.codigo as cod_dx', 'd.nombre as nombre_dx')
            ->where('rd.id_prestacion', $prestacion->id)
            ->where('rd.id_clasificacion_diagnosticos', '<>', 9)
            ->where(function ($sql) {
                $sql->whereNull('rd.id_evaluacion')
                    ->orWhere('rd.id_evaluacion', 0);
            })
            //->whereNull('rd.id_receta_vale')
            ->orderBy('rd.id')
            ->orderBy('rd.id_categoria')
            ->orderBy('rd.id_tipo_diagnostico')
            ->get();

        $procedimientos = DB::table('prestacion_procedimientos as pp')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->selectRaw('DISTINCT ON (p.codigo) pp.id, pp.cantidad, p.codigo as cod_cpms, p.descripcion as descripcion_cpms')
            ->where('pp.id_prestacion', $prestacion->id)
            ->where('pp.id_evaluacion', 0)
            ->where('pp.tipo_procedimiento', 1)
            ->where('pp.id_estado_reg', 1)
            ->orderBy('p.codigo')
            ->orderBy('pp.id')
            ->get();

        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();

        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();

                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
        ->where('estado', 1)
        ->lists('id_evento')
        ->toArray();
        $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::select('ha.*', 
            'p.fecha_parto', 
            'p.id_tipo_parto as puerperio_id_tipo_parto', 
            'tp.nombre as puerperio_tipo_parto', 
            'p.complicaciones as puerperio_complicaciones', 
            'p.descripcion as puerperio_descripcion')
        ->from('hospitalizacion_atenciones as ha')
        ->leftJoin('puerperios as p', 'ha.id_puerperio', '=', 'p.id')
        ->leftJoin('tipo_partos as tp', 'p.id_tipo_parto', '=', 'tp.id')
        ->where('ha.id_prestacion', '=', $id_prestacion) // Cambié aquí a 'ha.id' para que coincida con el contexto
        ->first();
        $gestacion = Gestacion::where("id", $obj_hospitalizacion_atencion->id_gestacion)->first();
        $puerperio = Puerperio::where("id", $obj_hospitalizacion_atencion->id_puerperio)->first();

        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;

        $pdf = \PDF::loadView('pdf.hospitalizacion_atencion', compact('atencion','hospitalizacion','prestacion','puerperio','gestacion','eventosMarcados','obj_hospitalizacion_atencion','atencion','asegurado','titular','persona_datos_adicionales','grado','diagnosticos','procedimientos','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','interconsultas','nombre_establecimiento'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_hospitalizado.pdf');
    }

    public function mostrarPdfhospitalizacionAdmision($id)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $apersonal = array();
        $model_receta = new Receta_vale;
        $hospitalizacion = $model_receta->getHospitalizacionById_farmacia_new_x($id);
        $hospitalizacion = ($hospitalizacion != null ? $hospitalizacion[0] : null);
        $id_user_consultorio = 0;      
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');
        //--- ESTABLECIMIENTO
        $id_establecimiento = Auth::user()->id_establecimiento;
        $model_establecimiento = new Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        /* agregado */
        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();

        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();

                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        //BUSCA LA PRESTACION DE LA PROGRAMACION QUIRURGICA POR EL ID DE HOSPITALIZACION EN CENTRO QUIRURGICO
        $id_prestacion = DB::table('cqx_intervenciones as ci')
        ->join('cqx_citas as cc', 'ci.id', '=', 'cc.id_cqx_intervenciones')
        ->where('cc.is_programacion_quirurgica', true)
        ->where('ci.id_hospitalizacion', $id)
        ->value('cc.id_prestacion');
        //POR EL ID DE PRESTACION TRAERA LOS DIAGNOSTICOS DE LA ATENCION - PROGRAMACION QUIRURGICA
        $diagnosticos_programacion_qx=null;
        if(!empty($id_prestacion)){
            $model_prestacion = new \App\Models\Prestaciones;
            $diagnosticos_programacion_qx = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        }
        $pdf = \PDF::loadView('pdf.firmar_admision_hospitalizacion',
        compact(
            'apersonal',
            'asegurado',
            'fechaActual',
            'grado',
            'horaActual',
            'id_user_consultorio',
            'hospitalizacion',
            'nombre_establecimiento',
            'persona_datos_adicionales',
            'titular',
            'diagnosticos_programacion_qx'
        ));

        $pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->setPaper('A5', 'portrait');
        $pdf->setPaper('A4', 'portrait'); //Horizontal

        return $pdf->stream('hospitalizacion-admision.pdf');
    }

    public function mostrarPdfHospitalizacionAltaAdministrativa($id_hospitalizacion)   {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_establecimiento=Auth::user()->id_establecimiento;

        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getDatosEgresoAltaAdministrativaById($id_hospitalizacion);
        
        if(is_object($hospitalizacion)):
        $model_prestacion = new Prestaciones();
        $prestacion = $model_prestacion->getByIdExtYIdTipoAtencion($id_hospitalizacion, 3);
        if ($prestacion) {
            $id_prestacion = $prestacion->id;
            $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        }

        $aseguradoHospitalizacion = Asegurado::where('id', '=', $hospitalizacion->id_asegurado)->first();
        $tipo_beneficiario_query = "SELECT * from tipo_beneficiarios where id= '$aseguradoHospitalizacion->id_tipo_beneficiario'";
        $tipo_beneficiario = DB::select($tipo_beneficiario_query);
        $tipo_beneficiario = $tipo_beneficiario[0];

        /*$model_medico = new Medico();
        $model_usuario = new User();
        $usuario = $model_usuario->GetById($hospitalizacion->id_medico_alta);
        $medico = $model_medico->GetDatosMedicoAndEstablecimiento($usuario->dni, $usuario->id_establecimiento);
        $medico = $medico[0];

        if($prestacion->id_ext!=0):
            if($prestacion->tipo_atencion=='HOSPITALIZACION'):
                $model_emergencia = new \App\Models\Emergencia;
                $datoadicional = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                $sw = 1;
            endif;
        else:
            return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
        endif;

        $diagnosti= array();
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cab'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "PRINCIPAL";
        $diagnos->tipo = "DEFINITIVO";
        $diagnosti[0]=$diagnos;
        if($hospitalizacion->id_diag_cuer1>0){
            $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cuer1'";
            $diagnos = DB::select($diagnos);
            $diagnos = $diagnos[0];
            $diagnos->cabecera = "SECUNDARIO";
            $diagnos->tipo = "DEFINITIVO";
            $diagnosti[1]=$diagnos;
        }elseif($hospitalizacion->id_diag_cuer2>0){
            $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cuer2'";
            $diagnos = DB::select($diagnos);
            $diagnos = $diagnos[2];
            $diagnos->cabecera = "SECUNDARIO";
            $diagnos->tipo = "DEFINITIVO";
            $diagnosti[2]=$diagnos;
        }






        $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_hospitalizacion)->first();
        if(is_object($emergencia_prestacion)):
            $id_establecimiento=Auth::user()->id_establecimiento;
            $model_emergencia = new Emergencia();

            $obtenerdato = $model_emergencia->GetObtenerServicio($id_hospitalizacion);
            $emergencia_triaje = $model_emergencia->obtenerReporteSaludMaterna($id_hospitalizacion);
            //$emergencia_triaje = $model_emergencia->obtenerRegistroEmergencia($id_emergencia);
            $model_prestacion = new \App\Models\Prestaciones;
            // foreaxh natis
            $prestacion = $model_prestacion->GetPrestacionById($id_hospitalizacion);

            //CARGA DE SALUD MATERNA foreach para neonatos
            $emergencia = Emergencia::find($id_hospitalizacion);
            $natis = null;
            if (isset($emergencia->id_puerperio)) {
                $puerperio = Puerperio::find($emergencia->id_puerperio);
                if ($puerperio) {
                    $natis = $puerperio->natis;
                }
            }
            $prestacion= $model_emergencia->getIdPrestacion($id_hospitalizacion);
            $id_prestacion =  $prestacion->id;
            $emergencia = Emergencia::where('id', '=', $id_hospitalizacion)->first();
            $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_hospitalizacion,$emergencia->estado);
            $egreso = $model_emergencia->GetEgresoEmergencia($id_hospitalizacion);
            $model_establecimiento = new \App\Models\Establecimiento;
            $model_prestacion = new \App\Models\Prestaciones;
            $model_kardex = new \App\Models\Kardexe();
            $fechaServidor = $model_kardex->getFechaServidor();
            $horaservidor = $model_kardex->getHoraServidor();*/
            $date = Carbon::now();
            $fechaActual = $date->format('d/m/Y');
            $horaActual = $date->format('H:i:s');
            /*$prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
            // $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
            $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;
            $aseguradoemer=Asegurado::where('id', '=', $emergencia->id_asegurado)->first();
            $tipo_benefi="SELECT * from tipo_beneficiarios where id= '$aseguradoemer->id_tipo_beneficiario'";
            $tipo_benefi = DB::select($tipo_benefi);
            $tipo_benefi=$tipo_benefi[0];
            $formaingreso="SELECT * from forma_ingresos where id= '$emergencia->id_forma_ingreso'";
            $formaingreso = DB::select($formaingreso);
            $formaingreso=$formaingreso[0];
            $estadoingre="SELECT * from estado_ingresos where id= '$emergencia->id_estado_ingreso'";
            $estadoingre = DB::select($estadoingre);
            $estadoingre=$estadoingre[0];
            $motivoingre="SELECT * from categorias where id= '$emergencia->id_motivo_ingreso'";
            $motivoingre = DB::select($motivoingre);
            $motivoingre=$motivoingre[0];
            $condiegre="SELECT * from condiciones where codigo_salupol= '$emergencia->id_condi_egres_med'";
            $condiegre = DB::select($condiegre);
            $condiegre=$condiegre[0];
            $priori="SELECT * from prioridades where id= '$emergencia->id_prioridad'";
            $priori = DB::select($priori);
            $priori=$priori[0];

            $fechaEgreso = $model_kardex->getFechaServidor();
            $horaEgreso = $model_kardex->getHoraServidor();
            $establecimiento = $model_establecimiento->GetById($id_establecimiento);
            if ($id_establecimiento == 1) {
                $nivel = 2;
            } else {
                $nivel = $establecimiento[0]->nivel;
            }

            $model_consultorio = new \App\Models\Consultorio;
            $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
            // $hospitalizacion = "select c.nombre, u.id, u.codigo from consultorios c inner join upsses u on u.id_consultorio = c.id where u.id_tipo_atencion = '3' and u.estado = '1'";
            // $hospitalizacion = DB::select($hospitalizacion);
        
            $dni=Auth::user()->dni;
            $model_medico = new Medico();
            $exite_medico=$model_medico->exite_medico($dni);
            $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
        
            $establecimie = "select * from establecimientos e where id = '$prestacion->id_establecimiento'";
            $establecimie = DB::select($establecimie);
            $establecimie= $establecimie[0];
        
            if(isset($emergencia->cama_estado)){
                $cama = "select * from camas c where id = '$emergencia->cama_estado'";
                $cama = DB::select($cama);
                $cama= $cama[0];  
                $existecama = '1';
            } else {
                
                $cama="SELECT * from camas c where id = '506'";
                $cama = DB::select($cama);
                $cama= $cama[0]; 
                $existecama = '0';
            }
        
            $diagnosticos=array();
            $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
            $diagnos = DB::select($diagnos);
            $diagnos = $diagnos[0];
            $diagnos->cabecera = "PRINCIPAL";
            $diagnos->tipo = "DEFINITIVO";
            $diagnosticos[0]=$diagnos;
            if($emergencia->id_diag_cuer1>0){
                $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer1'";
                $diagnos = DB::select($diagnos);
                $diagnos = $diagnos[0];
                $diagnos->cabecera = "SECUNDARIO";
                $diagnos->tipo = "DEFINITIVO";
                $diagnosticos[1]=$diagnos;
            }elseif($emergencia->id_diag_cuer2>0){
                $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer2'";
                $diagnos = DB::select($diagnos);
                $diagnos = $diagnos[2];
                $diagnos->cabecera = "SECUNDARIO";
                $diagnos->tipo = "DEFINITIVO";
                $diagnosticos[2]=$diagnos;
            }
            if($emergencia->id_tipo_egres_med=="2"){
                $upss="SELECT 
                concat(c.nombre,' / ',u.codigo ) as nombre,
                u.id as id_upss,
                u.codigo as codigo_upss,
                c.id as id_consultorio,
                c.nombre as nombre_consultorio
                from consultorios c 
                inner join upsses u on u.id_consultorio = c.id 
                where u.id_tipo_atencion = '3' and u.estado = '1' and u.id='$emergencia->id_upss_alta'";
                $upss= DB::select($upss);
                $upss=$upss[0];
            }else{
                $upss=0;
            }


        
            $asegu= "SELECT * from asegurados where id= '$emergencia->id_asegurado'";
            $asegu = DB::select($asegu);
            $asegu=$asegu[0];
        
            //$tiempo="SELECT DATE_PART('YEAR',AGE('$asegu->fecha_nac'::date))";
            $tiempo="SELECT to_char(age(TO_DATE('$asegu->fecha_nac' , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"días\"') as fec_nacim_descrip";
            $tiempo = DB::select($tiempo);
            $tiempo=$tiempo[0]->fec_nacim_descrip;

            $triajes = "SELECT * from triajes where id = '$emergencia->id_triaje'";
            $triajes = DB::select($triajes);
            $triajes=$triajes[0];
        
            $inicio= ($emergencia->fecha_registro_ingreso);
            $final= ($emergencia->fecha_alta_medica);
            $inicios= strtotime($emergencia->fecha_registro_ingreso);
            $fecha_alta=explode(" ",$emergencia->fecha_alta_medica);
            $hora_alta=explode(" ",$emergencia->hora_alta_medica);
            $fecha_finals=$fecha_alta[0].' '.$hora_alta[0];
            //$finals= strtotime($emergencia->fecha_alta_medica);
            $finals= strtotime($fecha_finals);
            
            $resulta=($finals-$inicios)/86400;
            $resulta=round($resulta);
            
            if($resulta == "0"){
                $resta = '1';
            }else{
                $resta=$resulta;
            }
            if($aseguradoemer->id_tipo_beneficiario != 8 && $aseguradoemer->id_tipo_beneficiario != 13){
                $datosasegura = "SELECT * from asegurados a where nro_doc_ident = '$aseguradoemer->nro_doc_ident' and id_estado_reg = '1'";
                $datosasegura = DB::select($datosasegura);
                $datosasegura=$datosasegura[0];

                $datotitu = "SELECT * from asegurados a where id = '$datosasegura->id_asegurado_titular' ";
                $datotitu = DB::select($datotitu);
                $datotitu=$datotitu[0];
            }else{
                $datotitu = "SELECT * from asegurados a where nro_doc_ident = '$aseguradoemer->nro_doc_ident'";
                $datotitu = DB::select($datotitu);
                $datotitu=$datotitu[0];  
            }

            $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);*/
            $user_actual = Auth::user();
            
            /* agregado */
            $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();

            $titular = null;
            $persona_datos_adicionales = null;
            $grado = null;

            if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
                $titular = DB::table('asegurados')
                    ->select('asegurados.*')
                    ->where('id',$asegurado->id_asegurado_titular)
                    ->where('id_estado_reg', 1)
                    ->first();
                
                if ($titular != null) {
                    $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                        ->select('persona_datos_adicionales.*')
                        ->where('id_asegurado',$titular->id)
                        ->first();

                    if ($persona_datos_adicionales != null) {
                        $grado = DB::table('titular_grados')
                        ->select('titular_grados.*')
                        ->where('id',$persona_datos_adicionales->id_grado)
                        ->first();
                    }
                }
            }
            $pdf = \PDF::loadView('pdf.hospitalizacion_alta_administrativa',[
                'hospitalizacion'=>$hospitalizacion,
                /*'prestacion'=>$prestacion,//12
                'aseguradoHospitalizacion' => $aseguradoHospitalizacion,*/
                'tipo_beneficiario' => $tipo_beneficiario,
                /*'medico'=>$medico,
                'datoadicional' => $datoadicional,
                'sw' => $sw,
                'diagnostico'=>$diagnostico,
                'diagnosti' => $diagnosti,

                'datotitu'=>$datotitu,
                'upss'=>$upss,
                //'recetas'=>$recetas,
                //'buscareceta'=>$buscareceta,
                'resta'=>$resta,
                'triajes'=>$triajes,
                'existecama'=>$existecama,*/
                'fechaActual'=>$fechaActual,
                //'tipo_benefi'=>$tipo_benefi,
                'horaActual'=>$horaActual,
                /*'cama'=>$cama,
                'tiempo'=>$tiempo,
                'procedimiento'=>$procedimiento,
                'condiegre'=>$condiegre,
                'priori'=>$priori,
                'motivoingre'=>$motivoingre,
                'estadoingre'=>$estadoingre,
                'formaingreso'=>$formaingreso,
                'aseguradoemer'=>$aseguradoemer,
                'establecimie'=>$establecimie, 
                'diagnosticos'=>$diagnosticos, 
                'id_prestacion'=>$id_prestacion,
                'nivel'=>$nivel, 
                'consulta_externa'=>$consulta_externa,
                'paciente_emergencia'=>$paciente_emergencia, 
                'emergencia_triaje'=>$emergencia_triaje, 
                'natis'=>$natis, 
                'emergencia'=>$emergencia,*/
                'user_actual'=>$user_actual,
                'grados',
                'apersonal',
                'asegurado' => $asegurado,
                'grado' => $grado,
                'id_user_consultorio',
                'nombre_establecimiento',
                'persona_datos_adicionales' => $persona_datos_adicionales,
                'titular' =>$titular
            ]);
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('hospitalizacion_alta_administrativa.pdf');
       
        else:
            Session::flash('flash_warning', 'No se ha encontrado información del Alta Administrativa.');
            return redirect('/dashboard');
        endif;
    }
    public function mostrarPdfHospitalizacionFichaEstadistica($id_hospitalizacion){ //PDF FICHA ESTADISTICA

        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getDatosHospitalizacionFichaEstadistica($id_hospitalizacion);

        if(!is_object($hospitalizacion)){
            Session::flash('flash_warning', 'No se ha encontrado información de la ficha estadística.');
            return redirect('/dashboard');
        }
        
        $model_asegurado_historia = new Asegurado_historia();
        $asegurado_historia = $model_asegurado_historia
                            ->where('id_asegurado', $hospitalizacion->id_asegurado)
                            ->where('id_establecimiento', $hospitalizacion->id_establecimiento)->first();

        $model_diagnostico = new Diagnostico();

        $asegurado = DB::table('asegurados as a')
                        ->join('razas as r', 'r.id', '=', 'a.id_raza')
                        ->join('tipo_beneficiarios as tb', 'tb.id', '=', 'a.id_tipo_beneficiario')
                        ->select(
                            'a.id as id_asegurado',
                            'a.id_asegurado_titular',
                            'r.nombre as raza',
                            'tb.descripcion as tipo_beneficiario'
                        )
                        ->where('a.id', '=', $hospitalizacion->id_asegurado)
                        ->first();
        $diagnostico_inicial = $model_diagnostico->where('id', $hospitalizacion->id_diagnostico_inicial)->first();

        $dias_en_hospital = 0;
        $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_ingreso);
        
        /* CALCULAR ESTANCIA EN HOSPITAL EN BASE A ALTA MEDICA*/

        $fecha_alta_medica = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_alta_medica);
        $dias_calculados = $fecha_alta_medica->diffInDays($fecha_ingreso);
        
        $dias_en_hospital = $dias_calculados + 1;
        //Busqueda datos Reniec
        $busquedaReniec = null;
        try {//comentar para pruebas
            $Reniec = new Reniec();
            $ReniecQuery = $Reniec->BuscarDNI($hospitalizacion->asegurado_ndocumento);
            $busquedaReniec = json_decode($ReniecQuery);
        } catch (Exception $e) {
            Log::error($e);
            Log::error('No carga la ws de Reniec');
        }

        //Busqueda datos Dirrehum
        $busquedaDirrehum = null;
        try {//comentar para pruebas
            $Dirrehum = new Dirrehum();
            $DirrehumQuery = $Dirrehum->BuscarTitularFamiliar('1', $hospitalizacion->asegurado_ndocumento);
            $busquedaDirrehum = $DirrehumQuery->BuscarTitularFamiliarResult->TitularFamiliar;
        } catch (Exception $e) {
            Log::error($e);
            Log::error('No carga la ws de Dirrehum');
        }
        
        $hospitalizacion_atenciones = DB::table('hospitalizacion_atenciones as ha')
                ->select(
                    'ha.id',
                    'ha.id_ext as id_hospitalizacion',
                    'ha.sintomas_signos_principal as sintomas_signos_principal'
                )
                ->where('ha.id_ext', $id_hospitalizacion)
                ->first();
        $upss_alta = DB::table('hospitalizaciones as h')
                    ->join('upsses as u', function ($join) {
                        $join->on('u.id_consultorio', '=', 'h.id_consultorio')
                            ->where('u.id_tipo_atencion', '=', 3);
                    })
                    ->where('h.id', $id_hospitalizacion)
                    ->select('u.codigo as codigo')
                    ->first();
        $pdf = \PDF::loadView('pdf.a_ficha_estadistica',[
            'id_hospitalizacion' => $id_hospitalizacion,
            'asegurado' => $asegurado,
            'asegurado_historia' => $asegurado_historia,
            'busquedaReniec' => $busquedaReniec,
            'busquedaDirrehum' => $busquedaDirrehum,
            'dias_en_hospital' => $dias_en_hospital,
            'diagnostico_inicial' => $diagnostico_inicial,
            'hospitalizacion' => $hospitalizacion,
            'hospitalizacion_atenciones' => $hospitalizacion_atenciones,
            'upss_alta' => $upss_alta
        ]);
        
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('hospitalizacion_ficha_estadistica.pdf');

    }
    public function print_hospitalizacion_evolucion($id_evolucion) {
        
        $evolucion = DB::table('evaluaciones as e')
            ->select('e.*', 'm.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura', 'h.paterno_beneficiario as paciente_paterno', 'h.materno_beneficiario as paciente_materno', 'h.nombre_beneficiario as paciente_nombre','ah.nro_historia','camas.nombre as cama','consultorios.nombre as servicio_sala','p.nombre as profesional_profesion')
            ->join('hospitalizaciones as h', function ($join) {
                $join->on('e.id_emergencia', '=', 'h.id')
                    ->where('e.id_tipo_atencion', '=', 3);
            })
            ->join('medicos as m', 'e.id_medico', '=', 'm.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->leftJoin('asegurado_historias as ah', function ($join) {
                $join->on('h.id_asegurado', '=', 'ah.id_asegurado')
                    ->on('h.id_establecimiento', '=', 'ah.id_establecimiento');
            })
            ->join('cambio_camas', function ($join) {
                $join->on('e.id_prestacion', '=', 'cambio_camas.id_prestacion')
                     ->on('cambio_camas.id_evolucion', '=', 'e.id');
            })
            ->join('camas','cambio_camas.id_cama_atencion','=','camas.id')
            ->join('cuartos','camas.id_cuarto','=','cuartos.id')
            ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
            ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')
            ->where('e.id', $id_evolucion)
            ->first();

        $recetas = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            ->where('receta_vales.id_evaluacion', '=', $id_evolucion)
            ->where('receta_vales.estado', '<>', 0)
            ->orderBy('receta_vales.id', 'asc')
            ->get();

        $receta_productos = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->select(
                'producto_recetas.id as id_producto_receta',
                'producto_recetas.id_receta_vale as id_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            ->where('receta_vales.id_evaluacion', $id_evolucion)
            ->orderBy('producto_recetas.id', 'ASC')
            ->get();
            
        $ordenes_laboratorio = DB::table('laboratorios')
            ->where('id_evaluacion', '=', $id_evolucion)
            ->where('estado', '<>', 0)
            ->orderBy('id', 'ASC')
            ->get();

        $orden_lab_examenes = DB::table('prestacion_procedimientos as t1')
            ->join('labnivelatenciones as t2', 't1.id_rua', '=', 't2.id')
            ->join('procedimientos as p', 't1.id_procedimiento', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'd.id', '=', 't1.id_diagnostico')
            ->where('t1.id_evaluacion', $id_evolucion)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo_lab as codigo',
                't2.descripcion_corta as nombre',
                'p.descripcion as nombre_completo',
                't1.cantidad',
                't1.created_at',
                't2.id_procedimiento',
                't1.observacion',
                'd.codigo as codigo_diagnostico',
                'd.nombre as diagnostico'
            )
            ->orderBy('t1.id', 'ASC')
            ->get();

        $ordenes_imagenologia = DB::table('radiologias')
            ->where('id_evaluacion', $id_evolucion)
            ->where('estado', '<>', 0)
            ->orderBy('id', 'ASC')
            ->get();

        $orden_img_examenes = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_evaluacion', $id_evolucion)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id', 'asc')
            ->get();
        
        $interconsultas = DB::table('interconsultas as t8')
            ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
            ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
            ->join('medicos as t5', 't8.personal_salud_interconsulta', '=', 't5.id')
            ->where('t8.id_evaluacion', $id_evolucion)
            ->where('t8.id_estado_reg', 1)
            ->select(
                't8.id',
                't3.nombre as establecimiento',
                't6.nombre as servicio',
                't5.nombre as profesional_nombre',
                't5.paterno as profesional_paterno',
                't5.materno as profesional_materno',
                't8.motivo_interconsulta',
                't8.codigo_upss as codigo_interconsulta',
                't5.colegiatura as profesional_colegiatura'
            )
            ->orderBy('t8.id')
            ->get();
        
        $diagnosticos = DB::table('receta_diagnosticos as rd')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->select('rd.*', 'd.codigo as cod_dx', 'd.nombre as nombre_dx')
            ->where('rd.id_evaluacion', $id_evolucion)
            ->where('rd.id_clasificacion_diagnosticos', '<>', 9)
            ->where('rd.estado', 1)
            ->orderBy('rd.id')
            ->orderBy('rd.id_categoria')
            ->get();

        $procedimientos = DB::table('prestacion_procedimientos as pp')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->select('pp.id', 'pp.cantidad', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms')
            ->where('pp.id_evaluacion', $id_evolucion)
            ->where('pp.tipo_procedimiento', 1)
            ->where('pp.id_estado_reg', 1)
            ->orderBy('pp.id')
            ->get();

        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;

        $pdf = \PDF::loadView('pdf.hospitalizacion_evolucion', compact('evolucion','diagnosticos','procedimientos','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','interconsultas','nombre_establecimiento'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_hospitalizado.pdf');
    }

    public function print_hospitalizacion_alta_medica_hc($id_hospitalizacion) {

        $id_establecimiento = Auth::user()->id_establecimiento;

        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getHospitalizacionById($id_hospitalizacion);    

        $prestaciones = DB::table('prestaciones')
            ->join('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->select('prestaciones.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion')
            ->where('prestaciones.id_ext', $id_hospitalizacion)
            ->where('prestaciones.id_tipo_atencion', 3)
            ->where('prestaciones.id_estado_reg', 1)
            ->orderBy('prestaciones.created_at', 'desc')
            ->get();

        $atenciones = array();
        $recetas = array();
        $receta_productos = array();
        $ordenes_laboratorio = array();
        $orden_lab_examenes = array();
        $ordenes_imagenologia = array();
        $orden_img_examenes = array();
        $interconsultas = array();
        $diagnosticos = array();
        $procedimientos = array();

        $evoluciones = array();
        $recetas_evolucion = array();
        $receta_productos_evolucion = array();
        $ordenes_laboratorio_evolucion = array();
        $orden_lab_examenes_evolucion = array();
        $ordenes_imagenologia_evolucion = array();
        $orden_img_examenes_evolucion = array();
        $interconsultas_evolucion = array();
        $diagnosticos_evolucion = array();
        $procedimientos_evoluciones = array();

        foreach ($prestaciones as $prestacion):    

        $atencion_actual = DB::table('hospitalizacion_atenciones')
            ->where('id_prestacion', $prestacion->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($atencion_actual) {
            $atenciones[] = $atencion_actual;
        }

        $recetas_actuales = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->where('receta_vales.estado', '<>', 0)
            ->where('receta_vales.id_evaluacion', '=', 0)
            ->where('receta_vales.id_interconsulta', '=', 0)
            ->orderBy('receta_vales.id', 'asc')
            ->get();

        foreach ($recetas_actuales as $receta) {
            $recetas[] = $receta;
        }

        $receta_productos_actuales = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->where('receta_vales.id_prestacion', $prestacion->id)
            ->select(
                'receta_vales.id as id_receta',
                'producto_recetas.id as id_producto_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            ->orderBy('receta_vales.id', 'asc')
            ->orderBy('producto_recetas.id', 'asc')
            ->get();
        
        foreach ($receta_productos_actuales as $receta_producto) {
            $receta_productos[] = $receta_producto;
        }
        
        $ordenes_laboratorio_actuales = DB::table('laboratorios')
            ->where('id_prestacion', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', 0)
            ->select(
                'id',
                'id_prestacion',
                'fecha_atencion',
                'fecha_firma',
                'ruta_laboratorio',
                'flag',
                'secuencial',
                'fecha_registro_toma_muestra',
                'estado',
                DB::raw("to_char(fecha_registro_toma_muestra, 'DD/MM/YYYY') AS fecha_toma_muestra")
            )
            ->orderBy('id', 'asc')
            ->get();
        
        foreach ($ordenes_laboratorio_actuales as $orden_laboratorio) {
            $ordenes_laboratorio[] = $orden_laboratorio;
        }

        $orden_lab_examenes_actuales = DB::table('prestacion_procedimientos')
            ->join('labnivelatenciones', 'prestacion_procedimientos.id_rua', '=', 'labnivelatenciones.id')
            ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
            ->where('prestacion_procedimientos.id_prestacion', $prestacion->id)
            ->where('prestacion_procedimientos.id_estado_reg', 1)
            ->select(
                'labnivelatenciones.id as id',
                'prestacion_procedimientos.id_lab_rad as id_orden',
                'labnivelatenciones.codigo_lab as codigo',
                'labnivelatenciones.descripcion_corta as nombre',
                'procedimientos.descripcion as nombre_completo',
                'prestacion_procedimientos.cantidad',
                'prestacion_procedimientos.created_at',
                'labnivelatenciones.id_procedimiento',
                'prestacion_procedimientos.observacion',
                'diagnosticos.codigo as codigo_diagnostico',
                'diagnosticos.nombre as diagnostico',
                'prestacion_procedimientos.tipo_muestra_microbiologia',
                'prestacion_procedimientos.id_solicitud_procedimiento'
            )
            ->orderBy('prestacion_procedimientos.id', 'asc')
            ->get();

        foreach ($orden_lab_examenes_actuales as $orden_lab_examen) {
            $orden_lab_examenes[] = $orden_lab_examen;
        }

        $ordenes_imagenologia_actuales = DB::table('radiologias')
            ->where('id_prestacion', '=', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', '=', 0)
            ->orderBy('id','asc')
            ->get();

        foreach ($ordenes_imagenologia_actuales as $orden_imagenologia) {
            $ordenes_imagenologia[] = $orden_imagenologia;
        }

        $orden_img_examenes_actuales = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id')
            ->get();

        foreach ($orden_img_examenes_actuales as $orden_img_examen) {
            $orden_img_examenes[] = $orden_img_examen;
        }

        $interconsultas_actuales = DB::table('interconsultas as t8')
            ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
            ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
            ->leftJoin('medicos as tr', 't8.personal_salud_interconsulta', '=', 'tr.id')
            ->where('t8.id_prestacion', $prestacion->id)
            ->where('t8.id_evaluacion', 0)
            ->where('t8.id_estado_reg', 1)
            ->select(
                't8.id',
                't8.id_prestacion',
                't3.nombre as establecimiento',
                't8.id_servicio',
                't6.nombre as servicio',
                'tr.nombre as profesional_nombre',
                'tr.paterno as profesional_paterno',
                'tr.materno as profesional_materno',
                'tr.colegiatura as profesional_colegiatura',
                't8.codigo_upss as codigo_interconsulta',
                't8.motivo_interconsulta',
                't8.resumen_interconsulta',
                't8.fecha_firma_interconsulta',
                't8.id as id_interconsulta',
                't8.created_at'
            )
            ->orderBy('t8.created_at', 'ASC')
            ->get();

        foreach ($interconsultas_actuales as $interconsulta) {
            $interconsultas[] = $interconsulta;
        }

        $diagnosticos_actuales = DB::table('receta_diagnosticos as rd')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->select('rd.*', 'd.codigo as cod_dx', 'd.nombre as nombre_dx')
            ->where('rd.id_prestacion', $prestacion->id)
            ->where(function ($sql) {
                $sql->whereNull('rd.id_evaluacion')
                    ->orWhere('rd.id_evaluacion', 0);
            })
            //->whereNull('rd.id_receta_vale')
            ->orderBy('rd.id')
            ->orderBy('rd.id_categoria')
            ->orderBy('rd.id_tipo_diagnostico')
            ->get();

        foreach ($diagnosticos_actuales as $diagnostico) {
            $diagnosticos[] = $diagnostico;
        }

        $procedimientos_actuales = DB::table('prestacion_procedimientos as pp')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->select('pp.id', 'pp.id_prestacion', 'pp.cantidad', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms')
            ->where('pp.id_prestacion', $prestacion->id)
            ->where('pp.id_evaluacion', 0)
            ->where('pp.tipo_procedimiento', 1)
            ->where('pp.id_estado_reg', 1)
            ->orderBy('pp.id')
            ->get();

        foreach ($procedimientos_actuales as $procedimiento) {
            $procedimientos[] = $procedimiento;
        }

        $evoluciones_actuales = DB::table('evaluaciones as e')
            ->select('e.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion','camas.nombre as cama','consultorios.nombre as servicio_sala')
            ->join('medicos as m', 'e.id_medico', '=', 'm.id')
            ->join('camas','e.cama_estado','=','camas.id')
            ->join('cuartos','camas.id_cuarto','=','cuartos.id')
            ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
            ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->where('e.id_prestacion', $prestacion->id)
            ->where('e.id_estado_reg', 1)
            ->orderBy('e.created_at', 'desc')
            ->get();

        foreach ($evoluciones_actuales as $evolucion) {
            $evoluciones[] = $evolucion;
        }

        $recetas_evolucion_actuales = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            // ->where('receta_vales.id_evaluacion', '=', $id_evolucion)
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->where('receta_vales.estado', '<>', 0)
            ->orderBy('receta_vales.id_evaluacion', 'desc')
            ->orderBy('receta_vales.id', 'asc')
            ->get();

        foreach ($recetas_evolucion_actuales as $receta_evolucion) {
            $recetas_evolucion[] = $receta_evolucion;
        }

        $receta_productos_evolucion_actuales = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->select(
                'producto_recetas.id as id_producto_receta',
                'receta_vales.id_evaluacion as id_evolucion',
                'producto_recetas.id_receta_vale as id_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            // ->where('receta_vales.id_evaluacion', $id_evolucion)
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->orderBy('receta_vales.id_evaluacion', 'desc')
            ->orderBy('producto_recetas.id', 'asc')
            ->get();

        foreach ($receta_productos_evolucion_actuales as $receta_producto_evolucion) {
            $receta_productos_evolucion[] = $receta_producto_evolucion;
        }

        $ordenes_laboratorio_evolucion_actuales = DB::table('laboratorios')
            ->where('id_prestacion', '=', $prestacion->id)
            ->where('estado', '<>', 0)
            ->orderBy('id', 'ASC')
            ->get();

        foreach ($ordenes_laboratorio_evolucion_actuales as $orden_laboratorio_evolucion) {
            $ordenes_laboratorio_evolucion[] = $orden_laboratorio_evolucion;
        }

        $orden_lab_examenes_evolucion_actuales = DB::table('prestacion_procedimientos as t1')
            ->join('labnivelatenciones as t2', 't1.id_rua', '=', 't2.id')
            ->join('procedimientos as p', 't1.id_procedimiento', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'd.id', '=', 't1.id_diagnostico')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo_lab as codigo',
                't2.descripcion_corta as nombre',
                'p.descripcion as nombre_completo',
                't1.cantidad',
                't1.created_at',
                't2.id_procedimiento',
                't1.observacion',
                'd.codigo as codigo_diagnostico',
                'd.nombre as diagnostico'
            )
            ->orderBy('t1.id', 'ASC')
            ->get();

        foreach ($orden_lab_examenes_evolucion_actuales as $orden_lab_examen_evolucion) {
            $orden_lab_examenes_evolucion[] = $orden_lab_examen_evolucion;
        }
        
        $ordenes_imagenologia_evolucion_actuales = DB::table('radiologias')
            ->where('id_prestacion', $prestacion->id)
            ->where('estado', '<>', 0)
            ->orderBy('id', 'ASC')
            ->get();

        foreach ($ordenes_imagenologia_evolucion_actuales as $orden_imagenologia_evolucion) {
            $ordenes_imagenologia_evolucion[] = $orden_imagenologia_evolucion;
        }

        $orden_img_examenes_evolucion_actuales = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id', 'asc')
            ->get();

        foreach ($orden_img_examenes_evolucion_actuales as $orden_img_examen_evolucion) {
            $orden_img_examenes_evolucion[] = $orden_img_examen_evolucion;
        }

        $interconsultas_evolucion_actuales = DB::table('interconsultas as t8')
            ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
            ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
            ->leftJoin('medicos as t5', 't8.personal_salud_interconsulta', '=', 't5.id')
            ->where('t8.id_prestacion', $prestacion->id)
            ->where('t8.id_estado_reg', 1)
            ->select(
                't8.id',
                't8.id_evaluacion as id_evolucion',
                't3.nombre as establecimiento',
                't6.nombre as servicio',
                't5.nombre as profesional_nombre',
                't5.paterno as profesional_paterno',
                't5.materno as profesional_materno',
                't8.motivo_interconsulta',
                't8.codigo_upss as codigo_interconsulta',
                't5.colegiatura as profesional_colegiatura'
            )
            ->orderBy('t8.id')
            ->get();

        foreach ($interconsultas_evolucion_actuales as $interconsulta_evolucion) {
            $interconsultas_evolucion[] = $interconsulta_evolucion;
        }

        $diagnosticos_evoluciones_actuales = DB::table('evaluaciones as e')
            ->join('receta_diagnosticos as rd', 'e.id', '=', 'rd.id_evaluacion')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->select('e.id as id_evolucion', 'd.codigo as cod_dx', 'd.nombre as nombre_dx', 'rd.id_tipo_diagnostico', 'rd.id_clasificacion_diagnosticos', 'rd.id_categoria')
            ->where('e.id_prestacion', $prestacion->id)
            ->where('e.id_estado_reg', 1)
            ->where('rd.estado', 1)
            ->orderBy('e.id')
            ->orderBy('rd.id')
            ->get();

        foreach ($diagnosticos_evoluciones_actuales as $diagnostico_evolucion) {
            $diagnosticos_evoluciones[] = $diagnostico_evolucion;
        }

        $procedimientos_evoluciones_actuales = DB::table('evaluaciones as e')
            ->join('prestacion_procedimientos as pp', 'e.id', '=', 'pp.id_evaluacion')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->select('e.id as id_evolucion', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms', 'pp.cantidad')
            ->where('e.id_prestacion', $prestacion->id)
            ->where('e.id_estado_reg', 1)
            ->where('pp.tipo_procedimiento', 1)
            ->where('pp.id_estado_reg', 1)
            ->orderBy('e.id')
            ->orderBy('pp.id')
            ->get();

        foreach ($procedimientos_evoluciones_actuales as $procedimiento_evolucion) {
            $procedimientos_evoluciones[] = $procedimiento_evolucion;
        }
        
        endforeach;

        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();


        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();

                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }

        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;

        $pdf = \PDF::loadView('pdf.hospitalizacion_atencion_hc', compact('atenciones','procedimientos','hospitalizacion','prestaciones','asegurado','titular','persona_datos_adicionales','grado','diagnosticos','evoluciones','diagnosticos_evoluciones','procedimientos_evoluciones'
                                                                        ,'camas.nombre as cama','consultorios.nombre as servicio_sala','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','interconsultas'
                                                                        ,'recetas_evolucion','receta_productos_evolucion','ordenes_laboratorio_evolucion','orden_lab_examenes_evolucion','ordenes_imagenologia_evolucion','orden_img_examenes_evolucion','interconsultas_evolucion','nombre_establecimiento'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_hospitalizado.pdf');

    }
    public function print_hospitalizacion_papeleta_egreso($id_hospitalizacion){

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_establecimiento=Auth::user()->id_establecimiento;

        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getDatosPapeletaEgreso($id_hospitalizacion);

        if(!is_object($hospitalizacion)){
            Session::flash('flash_warning', 'El paciente aún no se le ha dado de alta.');
            return redirect('/dashboard');
        }

        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        $dias_estancia = 0;
        $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_ingreso);

        /* CALCULAR ESTANCIA EN HOSPITAL EN BASE A ALTA MEDICA*/

        $fecha_alta_administrativa = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_alta_medica);
        $dias_calculados = $fecha_alta_administrativa->diffInDays($fecha_ingreso);
        
        $dias_estancia = $dias_calculados + 1;

        $user_actual = Auth::user();
            
        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();

        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();
                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        $concurrencias = DB::table('hospitalizacion_concurrencias as hc')
                ->join('consultorios as c', 'c.id', '=', 'hc.id_servicio')
                ->select('hc.id', 'hc.id_hospitalizacion', 'hc.id_servicio', 'c.nombre')
                ->where('hc.id_hospitalizacion', $id_hospitalizacion)
                ->get();
        
        $cqx_atencion = DB::table('cqx_atenciones')->select('cqx_atenciones.id_prestacion')->where('cqx_atenciones.id_hospitalizacion', $id_hospitalizacion)->first();  
        $diagnosticos = null;
        if ($cqx_atencion):
            $model_prestacion = new \App\Models\Prestaciones; 
            $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($cqx_atencion->id_prestacion);
        endif;

        $pdf = \PDF::loadView('pdf.hospitalizacion_papeleta_egreso',
                [
                    'asegurado' => $asegurado,
                    'concurrencias' => $concurrencias,
                    'dias_estancia' => $dias_estancia,
                    'fechaActual' => $fechaActual,
                    'grado' => $grado,
                    'horaActual' => $horaActual,
                    'hospitalizacion'=> $hospitalizacion,
                    'persona_datos_adicionales' => $persona_datos_adicionales,
                    'titular' => $titular,
                    'user_actual' => $user_actual,
                    'diagnosticos'=>$diagnosticos
                ]
        );

        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("papeleta_egreso_$id_hospitalizacion.pdf");
    }

public function ver_formato_ref($id_referencia)   {

    $data = DB::table('referencias')
      ->select('asegurados.nombre', 'asegurados.paterno', 'asegurados.materno', 'consultorios.nombre as consultorio', 
        DB::raw("obtener_nombre_establecimiento(referencias.id_establecimiento) as ipress_origen"),
        DB::raw("obtener_nombre_establecimiento(referencias.id_establecimiento_des) as ipress_destino"),
        'consultorios.nombre as servicio_referencia','medicos.nombre as nombre_medico','medicos.paterno as paterno_medico','medicos.materno as materno_medico',
        DB::raw("to_char(citas.fecha_hora, 'YYYY-MM-DD') dia"),DB::raw("to_char(citas.fecha_hora, 'HH:MI AM') hora"),
        DB::raw("sub_consultorios.id_consultorio||'-'||sub_consultorios.descripcion as  numero_consultorio_sala_atencion"))
      ->join('citas','referencias.id','=','citas.id_referencia')
      ->join('medicos','referencias.id_medico_referencia','=','medicos.id')
      ->join('prestaciones', 'referencias.id_prestacion', '=', 'prestaciones.id')
      ->join('asegurados', 'prestaciones.id_asegurado', '=', 'asegurados.id')
      ->join('sub_consultorios', 'prestaciones.id_sub_consultorio', '=', 'sub_consultorios.id')
      ->join('consultorios', 'sub_consultorios.id_consultorio', '=', 'consultorios.id')
      ->where('referencias.id', $id_referencia)
      ->first();
      $servicio_referencia="";
      $paciente="";
      $consultorio="";
      $ipress_origen="";
      $ipress_destino="";
      $medico="";
      $dia="";
      $hora="";
      $numero_consultorio_sala_atencion="";

      if(!empty($data->servicio_referencia)){
        $servicio_referencia = $data->servicio_referencia; 
      }
      if(!empty($data->nombre)){
        $paciente = $data->paterno.' '.$data->materno.' , '.$data->nombre; 
      }
      if(!empty($data->consultorio)){
        $consultorio = $data->consultorio; 
      }
      if(!empty($data->ipress_origen)){
        $ipress_origen = $data->ipress_origen; 
      }
      if(!empty($data->ipress_destino)){
        $ipress_destino = $data->ipress_destino; 
      }
      if(!empty($data->nombre_medico)){
        $medico = $data->paterno_medico.' '.$data->materno_medico.' , '.$data->nombre_medico;  
      }
      if(!empty($data->dia)){
        $dia = $data->dia; 
      }
      if(!empty($data->hora)){
        $hora = $data->hora; 
      }
      if(!empty($data->numero_consultorio_sala_atencion)){
        $numero_consultorio_sala_atencion = $data->numero_consultorio_sala_atencion; 
      }

       $date = Carbon::now();
       $fechaActual = $date->format('d/m/Y');
       $horaActual = $date->format('H:i:s');

       $referencias = DB::table('referencias')
        ->select('referencias.*')
        ->where('id',$id_referencia)
        ->first();
    
       $prestaciones = DB::table('prestaciones')
        ->select('prestaciones.*')
        ->where('id',$referencias->id_prestacion)
        ->first();

        $consultorios = DB::table('consultorios')
        ->select('consultorios.*')
        ->where('id',$referencias->id_servicio)
        ->first();
       
        $asegurado = DB::table('asegurados')
        ->select('asegurados.*')
        ->where('id',$prestaciones->id_asegurado)
        ->first();
        $parentesco = '';
        if(isset($asegurado->id_tipo_beneficiario)){
            $paren= DB::table('tipo_beneficiarios')
            ->select('tipo_beneficiarios.*')
            ->where('id',$asegurado->id_tipo_beneficiario)
            ->first();
            $parentesco = $paren->descripcion;
        }
        $model_prestacion = new \App\Models\Prestaciones;
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($prestaciones->id);
        
        $asegurados_h = DB::table('asegurado_historias')
        ->select('asegurado_historias.*')
        ->where('id_asegurado',$asegurado->id)
        ->where('id_establecimiento',$prestaciones->id_establecimiento)
        ->first();

        $tiempoe="SELECT to_char(age(TO_DATE('$asegurado->fecha_nac' , 'DD/MM/YYYY')), 'YY') as fec_nacim_descrip";
        $tiempoe = DB::select($tiempoe);
        $tiempoe=$tiempoe[0]->fec_nacim_descrip;
        $tiempom="SELECT to_char(age(TO_DATE('$asegurado->fecha_nac' , 'DD/MM/YYYY')), 'MM') as fec_nacim_descrip";
        $tiempom = DB::select($tiempom);
        $tiempom=$tiempom[0]->fec_nacim_descrip;
        $tiempod="SELECT to_char(age(TO_DATE('$asegurado->fecha_nac' , 'DD/MM/YYYY')), 'DD') as fec_nacim_descrip";
        $tiempod = DB::select($tiempod);
        $tiempod=$tiempod[0]->fec_nacim_descrip;

        $dividido = str_split($prestaciones->created_at, 1);
        $numeroceros = str_pad($referencias->id, 7, "0", STR_PAD_LEFT);
        $divididonumer = str_split($numeroceros, 1);
        $distrito = '';
        $departamento = '';
     
        if(isset($asegurado->id_departamento)){
            $depa = DB::table('departamentos')
            ->select('departamentos.*')
            ->where('id_departamento',$asegurado->id_departamento)
            ->first();
            $departamento = $depa->descripcion;
        }
        if(isset($asegurado->id_distrito)){
            $distri = DB::table('distritos')
            ->select('distritos.*')
            ->where('id_distrito',$asegurado->id_distrito)
            ->first();
            $distrito = $distri->descripcion;
        }

        $nom_diag_0 = "";
        $nom_diag_1 = "";
        $nom_diag_2 = "";
        $cie_diag_0 = "";
        $cie_diag_1 = "";
        $cie_diag_2 = "";
        $tip_diag_0 = "";
        $tip_diag_1 = "";
        $tip_diag_2 = "";

        if(isset($diagnostico[0]->nombre)){
            $nom_diag_0 = $diagnostico[0]->nombre;
            $nom_diag_0 = str_split($nom_diag_0, 42);
            $tip_diag_0 = $diagnostico[0]->id_tipo_diagnostico;
            $cie_diag_0 = $diagnostico[0]->codigo;
        }
        if(isset($diagnostico[1]->nombre)){
            $nom_diag_1 = $diagnostico[1]->nombre;
            $nom_diag_1 = str_split($nom_diag_1, 42);
            $tip_diag_1 = $diagnostico[1]->id_tipo_diagnostico;
            $cie_diag_1 = $diagnostico[1]->codigo;
        }
        if(isset($diagnostico[2]->nombre)){
            $nom_diag_2 = $diagnostico[2]->nombre;
            $nom_diag_2 = str_split($nom_diag_2, 42);
            $tip_diag_2 = $diagnostico[2]->id_tipo_diagnostico;
            $cie_diag_2 = $diagnostico[2]->codigo;
        }

        $dato_jhon_ipress_orig = DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('establecimientos.id', $referencias->id_establecimiento)
        ->first();
        $dato_jhon_ipress_dest = DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('establecimientos.id', $referencias->id_establecimiento_des)
        ->first();

        $ipress_origen = $dato_jhon_ipress_orig->nombre;
        $ipress_destino = $dato_jhon_ipress_dest->nombre;

      $pdf = \PDF::loadView('pdf.fucs_ref_jhon',[
        'servicio_referencia'=>$servicio_referencia,
        'paciente'=>$paciente,
        'consultorio'=>$consultorio,
        'ipress_origen'=>$ipress_origen,
        'ipress_destino'=>$ipress_destino,
        'medico'=>$medico,
        'dia'=>$dia,
        'hora'=>$hora,
        'parentesco'=>$parentesco,
        'numero_consultorio_sala_atencion'=>$numero_consultorio_sala_atencion,
        'referencias'=>$referencias,
        'dividido'=>$dividido,
        'asegurado'=>$asegurado,
        'asegurados_h'=>$asegurados_h,
        'tiempoe'=>$tiempoe,
        'tiempom'=>$tiempom,
        'tiempod'=>$tiempod,
        'distrito'=>$distrito,
        'departamento'=>$departamento,
        'prestaciones'=>$prestaciones,
        'diagnostico'=>$diagnostico,
        'nom_diag_0'=>$nom_diag_0,
        'nom_diag_1'=>$nom_diag_1,
        'nom_diag_2'=>$nom_diag_2,
        'cie_diag_0'=>$cie_diag_0,
        'cie_diag_1'=>$cie_diag_1,
        'cie_diag_2'=>$cie_diag_2,
        'tip_diag_0'=>$tip_diag_0,
        'tip_diag_1'=>$tip_diag_1,
        'tip_diag_2'=>$tip_diag_2,
        'divididonumer'=>$divididonumer,
        'consultorios'=>$consultorios,
        'fechaActual'=>$fechaActual
      ]);
      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portrait');
      return $pdf->stream('hc_servicio.pdf');


}

    public function print_hospitalizacion_epicrisis($id_hospitalizacion) 
    {
        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getHospitalizacionById($id_hospitalizacion);
        //$data_estancia = $model_hospitalizacion->GetDiasEstanciaByIdHospitalizacion($id_hospitalizacion);
        //$dia_estancia = $data_estancia->dia_estancia;
        $dias_estancia = 0;
        $fecha_ingreso = Carbon::createFromFormat('Y-m-d H:i:s', $hospitalizacion->fecha_ingreso);
        $fecha_alta_medica = Carbon::createFromFormat('Y-m-d H:i:s', $hospitalizacion->fecha_alta_medica);
        $diferencia_dias = $fecha_alta_medica->diffInDays($fecha_ingreso);
        $dia_estancia = $diferencia_dias + 1;
        
        $servicios_concurrencia = null;
        if ($hospitalizacion->id_tipo_egreso == 1) {
            $servicios_concurrencia = $model_hospitalizacion->getServiciosConcurrenciaByIdHospitalizacion($id_hospitalizacion);
        }

        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;

        $pdf = \PDF::loadView('pdf.hospitalizacion_epicrisis', compact('hospitalizacion','dia_estancia','servicios_concurrencia','nombre_establecimiento'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('hospitalizacion_epicrisis.pdf');
    }

    public function print_plantilla_rad($nivel, $tipo_atencion, $id_consultorio=NULL) {
        $model_radiologia = new \App\Models\Radiologia;
        switch ($tipo_atencion) {
            case "1":
                $titulo_reporte = "CONSULTORIOS";
                break;
            case "2":
                $titulo_reporte = "EMERGENCIA";
                break;
            case "3":
                $titulo_reporte = "HOSPITALIZACIÓN";
                break;
        }
        $model_consultorio = new Consultorio();
        $lista_rad = $model_radiologia->getVerRad($nivel, $tipo_atencion, $id_consultorio);
        $total_categoria = $model_radiologia->getNumCategoria($nivel, $tipo_atencion, $id_consultorio);
        $num_categoria = $total_categoria->total;
        $total_examenes = count($lista_rad) + $num_categoria;
        $filas_por_columnas = "";
        $consultorio = $model_consultorio->GetNombreById($id_consultorio);
        $num_columna=$consultorio->reporte_columnas_rad;
        if($num_columna==0 ||$num_columna == null || $num_columna ==""){
          return response()->json(["Object"=> "Error","message"=> "No hay columnas configuradas para este servicio. Por favor agrega Columnas"]);
        }
        $filas_por_columnas = ceil($total_examenes / $num_columna);
        $filas_por_columnas = intval($filas_por_columnas);
        $model_establecimiento = new App\Models\Establecimiento;
        $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
        
        $pdf = \PDF::loadView('pdf.rad_plantilla_reporte',compact('titulo_reporte','adicional','nivel','tipo_atencion','lista_rad','total_examenes','filas_por_columnas','consultorio','establecimiento'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('radiologia.pdf');
    }

    public function print_plantilla_rad_nivel1y2($nivel,$tipo_atencion, $id_consultorio=NULL) {
        $model_radiologia = new \App\Models\Radiologia;
        switch ($tipo_atencion) {
            case "1":
                $titulo_reporte = "CONSULTORIOS";
                break;
            case "2":
                $titulo_reporte = "EMERGENCIA";
                break;
            case "3":
                $titulo_reporte = "HOSPITALIZACIÓN";
                break;
        }
        $model_consultorio = new Consultorio();
        $lista_rad = $model_radiologia->getVerRad($nivel, $tipo_atencion, $id_consultorio);
        $total_categoria = $model_radiologia->getNumCategoria($nivel, $tipo_atencion, $id_consultorio);
        $num_categoria = $total_categoria->total;
        $total_examenes = count($lista_rad) + $num_categoria;
        $filas_por_columnas = "";
        $consultorio = $model_consultorio->GetNombreById($id_consultorio);
        $num_columna=$consultorio->reporte_columnas_rad;
        if($num_columna==0 ||$num_columna == null || $num_columna ==""){
          return response()->json(["Object"=> "Error","message"=> "No hay columnas configuradas para este servicio. Por favor agrega Columnas"]);
        }
        $filas_por_columnas = ceil($total_examenes / $num_columna);
        $filas_por_columnas = intval($filas_por_columnas);
        $model_establecimiento = new App\Models\Establecimiento;
        $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
        
        $pdf = \PDF::loadView('pdf.rad_plantilla_reporte_nivel1y2',compact('titulo_reporte','adicional','nivel','tipo_atencion','lista_rad','total_examenes','filas_por_columnas','consultorio','establecimiento'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('radiologia.pdf');
    } 
	

    public function print_examen_radiologia_lns_adicional($id_radiologia, $id_servicio_radiologia,$idpp,$id_informe_adicional) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;
        $model_medico = new \App\Models\Medico;
        $model_consultorio = new \App\Models\Consultorio;

        $radiologia = $model_radiologia->GetRadiologiaById($id_radiologia);
        $id_prestacion=$radiologia->id_prestacion;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $procradiologia = $model_radiologia->GetProcedimientosByIdRadiologiaAndIdServicioRadiologiaInformeAdicional($id_prestacion,$id_radiologia,$id_servicio_radiologia,$idpp,$id_informe_adicional);
        $medico = "";
        if (!empty($procradiologia[0]->dni_medico_validacion_act)):
            $medico = $model_medico->GetMedicoByDNI($procradiologia[0]->dni_medico_validacion_act);
        endif;
        
        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_informe-radio-adicional_".$idpp.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_informe-radio-adicional_".$idpp.".pdf");

        $pdf = \PDF::loadView('pdf.examen_radiologia_lns_adicional',compact('prestacion','radiologia','procradiologia','medico','codigo_qr','url_descarga'));                  
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('examen_radiologia_lns.pdf');
    }

    public function ver_pdf_informe_gastro_imprimir($id_prestacion, $contador) {
        //--------------------------------------------
        
        $path = public_path('tmp/pdf');
        if (!is_dir($path)) {
          mkdir($path);
        }
        
        $path = public_path('tmp/pdf/sin_firma/');
        if (!is_dir($path)) {
          mkdir($path);
        }
    
        $model_prestacion = new \App\Models\Prestaciones;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procedimiento = $model_prestacion->GetProcedimientoByIdPrestacion($id_prestacion);
        
        //--------------------------------------------  
        $datos_gastro = Gastroenterologia::where('prestacion_id', '=', $id_prestacion)->first();
        $carpeta = date("Y")."/".date("m")."/".date("d");
        if($contador==1):
            if($datos_gastro->baja==1):
                $descripcion='baja';
                $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_informe_gastro_".$descripcion."_".$id_prestacion.".pdf";
                $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_informe_gastro_".$descripcion."_".$id_prestacion.".pdf");
            
                $pdf = \PDF::loadView('pdf.firmar_informe_gastro_baja',compact('prestacion','diagnostico','procedimiento','contador','datos_gastro','codigo_qr','url_descarga'));
            endif;
        else:
            if($datos_gastro->alta==1):
                $descripcion='alta';
                $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_informe_gastro_".$descripcion."_".$id_prestacion.".pdf";
                $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_informe_gastro_".$descripcion."_".$id_prestacion.".pdf");
        
                $pdf = \PDF::loadView('pdf.firmar_informe_gastro_alta',compact('prestacion','diagnostico','procedimiento','contador','datos_gastro','codigo_qr','url_descarga'));
            endif;
        endif;
        
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('hc_servicio.pdf');
    
    }
  
    public function hospitalizacion_orden($estado, $id_orden )
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $model_hospitalizacion_orden = new \App\Models\HospitalizacionOrden();
        $model_hospitalizacion = new \App\Models\Hospitalizacione();
        
        $orden = null;

        if($estado == 0){   //PENDIENTES
            $orden = $model_hospitalizacion_orden->GetOrdenById($id_orden);
        }else if($estado == 1){ //ATENDIDOS
            $orden = $model_hospitalizacion->GetOrdenById($id_orden);
        }else{ // ANULADOS
            $orden = null;
        }
        
        if ($orden != null) {
            $diagnosticos = $model_hospitalizacion_orden->GetDiagnosticosByIdExtYTipoAtencion($orden->id_ext, $orden->id_tipo_atencion);
            $pdf = \PDF::loadView('pdf.hospitalizacion_orden', compact('orden', 'diagnosticos'));
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('hospitalizacion_orden.pdf');

        } else {
            Session::flash('flash_danger', 'La orden de hospitalización no existe.');
            return redirect('/dashboard');
        }
    }
  
    public function generatePdfKardexEnfermeria($id)
    {
        $kardexEnfermeria = KardexEnfermerias::where("id", $id)->first();

        $enfermeriaModel = new Enfermeria;
        $enfermeria = $enfermeriaModel->ObtenerCabeceraPdfById_sa($kardexEnfermeria->id_enfermeria);

		$model_receta_diagnostico = new Receta_diagnostico;
        $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($enfermeria->id_emergencia); 

        $examenLaboratorioKardexes = ExamenLaboratorioKardexes::where('id_kardex_enfermeria', $id)->get();
        $examenImagenKardexes = ExamenImagenKardexes::where('id_kardex_enfermeria', $id)->get();
        $cuidadoEnfermeros = CuidadoEnfermeros::join('parametros', 'cuidado_enfermeros.id_oxigeno_terapia', '=', 'parametros.id')
        ->select("cuidado_enfermeros.*", "parametros.descrip_larga as oxigeno_terapia_descripcion")
        ->where('cuidado_enfermeros.id_kardex_enfermeria', $id)
        ->first();
        $interconsultas = KardexInterconsultas::join('consultorios', 'kardex_interconsultas.id_consultorios', '=', 'consultorios.id')->select("kardex_interconsultas.*", "consultorios.nombre as consultorio_nombre")->where('kardex_interconsultas.id_kardex_enfermeria', $id)->get();
        $kardex_medicamento_enfermeria = KardexMedicamentoEnfermeria::select(
            'kardex_medicamentos_enfermerias.*',
            'infusiones_enfermerias.descripcion as infusion_descripcion',
            'via_administracion.id as id_via_administracion',
            'via_administracion.descripcion as via_administracion_descripcion'
        )
        ->leftJoin('infusiones_enfermerias', 'kardex_medicamentos_enfermerias.id_infusion', '=', 'infusiones_enfermerias.id')
        ->leftJoin('via_administracion', 'kardex_medicamentos_enfermerias.id_via_administracion', '=', 'via_administracion.id')
        ->where('kardex_medicamentos_enfermerias.id_kardex_enfermeria', $id)
        ->get();


        if (isset($kardex_medicamento_enfermeria) && count($kardex_medicamento_enfermeria) > 0) {
            $kardex_medicamento_enfermeria = $kardex_medicamento_enfermeria->map(function ($item) {
                $item->horarios = HorarioKardexMedicamento::where("id_medicamento", $item->id)->orderBy('id', 'ASC')->get();
                return $item;
            });
        }

        //Agrupar Medicamentos por tipo
        $kardex_medicamento_enfermeria_group = [];
        foreach ($kardex_medicamento_enfermeria as $medicamento) {
            $flag = $medicamento['flag_registro'];
            if (!isset($kardex_medicamento_enfermeria_group[$flag])) {
                $kardex_medicamento_enfermeria_group[$flag] = [];
            }
            $kardex_medicamento_enfermeria_group[$flag][] = $medicamento;
        }


        // dd($examenLaboratorioKardexes, $examenImagenKardexes, $cuidadoEnfermeros, $interconsultas, $kardex_medicamento_enfermeria, $kardex_medicamento_enfermeria_group, $kardexEnfermeria, $enfermeria, $diagnosticos);

        $pdf = \PDF::loadView('pdf.kardex_enfermeria', compact("kardexEnfermeria", "enfermeria", "diagnosticos", "examenLaboratorioKardexes", "examenImagenKardexes", "cuidadoEnfermeros", "interconsultas", "kardex_medicamento_enfermeria", "kardex_medicamento_enfermeria_group"));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_hospitalizado.pdf');
    }
    public function generatePdfKardexEnfermeriaHospi($id)
    {
        $kardexEnfermeria = KardexEnfermerias::where("id", $id)->first();

        $enfermeriaModel = new Enfermeria;
        $nota_enfermeria = new Nota_enfermeria();
        $enfermeria = $enfermeriaModel->ObtenerCabeceraPdfByIdHospi($kardexEnfermeria->id_enfermeria);
		$model_receta_diagnostico = new Receta_diagnostico;
        $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($enfermeria->id_hospitalizacion); 
        /* $signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$kardexEnfermeria->id_enfermeria' order by id_caracter_fisico asc";
        $signos_vitales = DB::select($signos_vitales);
        dd($signos_vitales); */
        $examenLaboratorioKardexes = ExamenLaboratorioKardexes::where('id_kardex_enfermeria', $id)->get();
        $examenImagenKardexes = ExamenImagenKardexes::where('id_kardex_enfermeria', $id)->get();
        $cuidadoEnfermeros = CuidadoEnfermeros::join('parametros', 'cuidado_enfermeros.id_oxigeno_terapia', '=', 'parametros.id')
        ->select("cuidado_enfermeros.*", "parametros.descrip_larga as oxigeno_terapia_descripcion")
        ->where('cuidado_enfermeros.id_kardex_enfermeria', $id)
        ->first();
        $interconsultas = KardexInterconsultas::join('consultorios', 'kardex_interconsultas.id_consultorios', '=', 'consultorios.id')->select("kardex_interconsultas.*", "consultorios.nombre as consultorio_nombre")->where('kardex_interconsultas.id_kardex_enfermeria', $id)->get();
        $kardex_medicamento_enfermeria = KardexMedicamentoEnfermeria::select(
            'kardex_medicamentos_enfermerias.*',
            'infusiones_enfermerias.descripcion as infusion_descripcion',
            'via_administracion.id as id_via_administracion',
            'via_administracion.descripcion as via_administracion_descripcion'
        )
        ->leftJoin('infusiones_enfermerias', 'kardex_medicamentos_enfermerias.id_infusion', '=', 'infusiones_enfermerias.id')
        ->leftJoin('via_administracion', 'kardex_medicamentos_enfermerias.id_via_administracion', '=', 'via_administracion.id')
        ->where('kardex_medicamentos_enfermerias.id_kardex_enfermeria', $id)
        ->get();


        if (isset($kardex_medicamento_enfermeria) && count($kardex_medicamento_enfermeria) > 0) {
            $kardex_medicamento_enfermeria = $kardex_medicamento_enfermeria->map(function ($item) {
                $item->horarios = HorarioKardexMedicamento::where("id_medicamento", $item->id)->orderBy('id', 'ASC')->get();
                return $item;
            });
        }

        $kardex_medicamento_enfermeria_group = [];
        foreach ($kardex_medicamento_enfermeria as $medicamento) {
            $flag = $medicamento['flag_registro'];
            if (!isset($kardex_medicamento_enfermeria_group[$flag])) {
                $kardex_medicamento_enfermeria_group[$flag] = [];
            }
            $kardex_medicamento_enfermeria_group[$flag][] = $medicamento;
        }

        // dd($examenLaboratorioKardexes, $examenImagenKardexes, $cuidadoEnfermeros, $interconsultas, $kardex_medicamento_enfermeria, $kardexEnfermeria, $enfermeria, $diagnosticos);

        $pdf = \PDF::loadView('pdf.kardex_enfermeria_hospi', compact("kardexEnfermeria", "enfermeria", "diagnosticos", "examenLaboratorioKardexes", "examenImagenKardexes", "cuidadoEnfermeros", "interconsultas", "kardex_medicamento_enfermeria", "kardex_medicamento_enfermeria_group"));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_hospitalizado.pdf');
    }
    
    public function print_hospitalizacion_atencion_cq_anestesia($id_prestacion) {

        $prestacion = DB::table('prestaciones')
            ->join('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->leftJoin('cambio_camas', function ($join) {
                $join->on('prestaciones.id', '=', 'cambio_camas.id_prestacion')
                     ->where('cambio_camas.id_evolucion', '=', 0);
            })
            ->leftJoin('camas','cambio_camas.id_cama_atencion','=','camas.id')
            ->leftJoin('cuartos','camas.id_cuarto','=','cuartos.id')
            ->leftJoin('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
            ->leftJoin('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')

            ->select('prestaciones.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion','camas.nombre as cama','consultorios.nombre as servicio_sala')
            ->where('prestaciones.id', $id_prestacion)
            ->where('prestaciones.id_estado_reg', 1)
            ->first();
        
        //Comentado por anestesia
        // $hospitalizacion = DB::table('hospitalizaciones')
        //         ->join('diagnosticos', 'hospitalizaciones.id_diagnostico_inicial','=','diagnosticos.id')
        //         ->join('camas','hospitalizaciones.id_cama_estado','=','camas.id')
        //         ->join('cuartos','camas.id_cuarto','=','cuartos.id')
        //         ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
        //         ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')
        //         ->select('hospitalizaciones.*','diagnosticos.codigo as codigo_cie','camas.nombre as cama','consultorios.nombre as servicio_sala')
        //         ->where('hospitalizaciones.id',$prestacion->id_ext)
        //         ->first();
                
        // $atencion = DB::table('hospitalizacion_atenciones')
        //     ->where('id_prestacion', $prestacion->id)
        //     ->first();

        $anestesia_quirurgica = DB::table('anestesia_quirurgicas')
                                  ->where('anestesia_quirurgicas.id_prestacion','=',$id_prestacion)
                                  ->select('anestesia_quirurgicas.*')
                                  ->first();
        
        $sala_intervencion = DB::table('cq_salas')
                                ->where('id','=',$anestesia_quirurgica->id_sala_intervencion)
                                ->select()
                                ->first();

        $tipoAnestesiacq = DB::table('cqx_tipo_anestesia_detalles')
                                ->join('cqx_tipo_anestesia','cqx_tipo_anestesia.id','=','cqx_tipo_anestesia_detalles.id_tipo_anestesia')
                                ->where('id_anestesia',$anestesia_quirurgica->id)
                                ->where('id_estado',1)
                                ->select('cqx_tipo_anestesia.descripcion')
                                ->get();
        
        $anetesiologos1 = DB::table('cqx_profesionales as p')
                                ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
                                ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
                                ->select('p.id', 'p.estado', 'm.nombre', 'm.paterno', 'm.materno', 'tp.descripcion')
                                ->where('p.flag_personal', 1)  // Filtrar por tipo de personal
                                ->where('p.id_cqx_intervenciones', $anestesia_quirurgica->id_intervencion)  // Filtrar por tipo de personal
                                ->whereIn('p.estado', [0, 1])  // Filtrar solo los profesionales activos
                                ->orderBy('p.id')
                                ->get();
        
        $anetesiologos2 = DB::table('cqx_profesionales as p')
                                ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
                                ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
                                ->select('p.id', 'p.estado', 'm.nombre', 'm.paterno', 'm.materno', 'tp.descripcion')
                                ->where('p.flag_personal', 1)  // Filtrar por tipo de personal
                                ->where('p.id_cqx_anestesia', $anestesia_quirurgica->id)  // Filtrar por tipo de personal
                                ->whereIn('p.estado', [0, 1])  // Filtrar solo los profesionales activos
                                ->orderBy('p.id')
                                ->get();
        
        $anetesiologos = array_merge($anetesiologos1, $anetesiologos2);
        
        $recetas = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->where('receta_vales.estado', '<>', 0)
            ->where('receta_vales.id_evaluacion', '=', 0)
            ->where('receta_vales.id_interconsulta', '=', 0)
            ->orderBy('receta_vales.id', 'asc')
            ->get();
            
        $receta_productos = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->where('receta_vales.id_prestacion', $prestacion->id)
            ->select(
                'receta_vales.id as id_receta',
                'producto_recetas.id as id_producto_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            ->orderBy('receta_vales.id', 'asc')
            ->orderBy('producto_recetas.id', 'asc')
            ->get();
            
        $ordenes_laboratorio = DB::table('laboratorios')
            ->where('id_prestacion', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', 0)
            ->select(
                'id',
                'fecha_atencion',
                'fecha_firma',
                'ruta_laboratorio',
                'flag',
                'secuencial',
                'fecha_registro_toma_muestra',
                'estado',
                DB::raw("to_char(fecha_registro_toma_muestra, 'DD/MM/YYYY') AS fecha_toma_muestra")
            )
            ->orderBy('id', 'asc')
            ->get();
            
        $orden_lab_examenes = DB::table('prestacion_procedimientos')
            ->join('labnivelatenciones', 'prestacion_procedimientos.id_rua', '=', 'labnivelatenciones.id')
            ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
            ->where('prestacion_procedimientos.id_prestacion', $prestacion->id)
            ->where('prestacion_procedimientos.id_estado_reg', 1)
            ->select(
                'labnivelatenciones.id as id',
                'prestacion_procedimientos.id_lab_rad as id_orden',
                'labnivelatenciones.codigo_lab as codigo',
                'labnivelatenciones.descripcion_corta as nombre',
                'procedimientos.descripcion as nombre_completo',
                'prestacion_procedimientos.cantidad',
                'prestacion_procedimientos.created_at',
                'labnivelatenciones.id_procedimiento',
                'prestacion_procedimientos.observacion',
                'diagnosticos.codigo as codigo_diagnostico',
                'diagnosticos.nombre as diagnostico',
                'prestacion_procedimientos.tipo_muestra_microbiologia',
                'prestacion_procedimientos.id_solicitud_procedimiento'
            )
            ->orderBy('prestacion_procedimientos.id', 'asc')
            ->get();
            
        $ordenes_imagenologia = DB::table('radiologias')
            ->where('id_prestacion', '=', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', '=', 0)
            ->orderBy('id','asc')
            ->get();
            
        $orden_img_examenes = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id')
            ->get();
            
        $interconsultas = DB::table('interconsultas as t8')
            ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
            ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
            ->join('medicos as tr', 't8.personal_salud_interconsulta', '=', 'tr.id')
            ->where('t8.id_prestacion', $prestacion->id)
            ->where('t8.id_evaluacion', 0)
            ->where('t8.id_estado_reg', 1)
            ->select(
                't8.id',
                't3.nombre as establecimiento',
                't8.id_servicio',
                't6.nombre as servicio',
                'tr.nombre as profesional_nombre',
                'tr.paterno as profesional_paterno',
                'tr.materno as profesional_materno',
                'tr.colegiatura as profesional_colegiatura',
                't8.codigo_upss as codigo_interconsulta',
                't8.motivo_interconsulta',
                't8.resumen_interconsulta',
                't8.fecha_firma_interconsulta',
                't8.id as id_interconsulta',
                't8.created_at'
            )
            ->orderBy('t8.created_at', 'ASC')
            ->get();
            

        $diagnosticos = DB::table('receta_diagnosticos as rd')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->select(
                'rd.id_diagnostico',
                'd.codigo as cod_dx',
                'd.nombre as nombre_dx',
                'rd.id_tipo_diagnostico',
                'rd.id_categoria'
            )
            ->where('rd.id_prestacion', $prestacion->id)
            ->where(function ($query) {
                $query->whereNull('rd.id_evaluacion')
                    ->orWhere('rd.id_evaluacion', 0);
            })
            ->distinct()
            ->orderBy('rd.id_categoria')
            ->orderBy('rd.id_tipo_diagnostico')
            ->get();
            
        $procedimientos = DB::table('prestacion_procedimientos as pp')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->select('pp.id', 'pp.cantidad', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms')
            ->where('pp.id_prestacion', $prestacion->id)
            ->where('pp.id_evaluacion', 0)
            ->where('pp.tipo_procedimiento', 1)
            ->where('pp.id_estado_reg', 1)
            ->orderBy('pp.id')
            ->get();
            
        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->leftJoin('tipo_beneficiarios', 'tipo_beneficiarios.id','=','asegurados.id_tipo_beneficiario')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2','tipo_beneficiarios.descripcion as tipo_beneficiario')
            ->where('asegurados.id',$prestacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();

        $asegurado_historia = DB::table('asegurado_historias')
            ->select('asegurado_historias.*')
            ->where('id_asegurado',$prestacion->id_asegurado)
            ->where('id_estado_reg', 1)
            ->first();
        
        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();

                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        
        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf");
        $pdf = \PDF::loadView('pdf.cq_anestesia_atencion', compact('hospitalizacion','prestacion','atencion','asegurado','titular','persona_datos_adicionales','grado','diagnosticos','procedimientos','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','interconsultas','asegurado_historia','anestesia_quirurgica','codigo_qr','url_descarga','sala_intervencion','tipoAnestesiacq','anetesiologos'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_hospitalizado.pdf');

    }
    public function print_hc($id_prestacion) {
        $model_prestacion = new \App\Models\Prestaciones;
        $model_receta = new Receta_vale;
       
        // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $prestacion = DB::table('prestaciones')->where('id', $id_prestacion)->first();
        if($prestacion->id_tipo_atencion==2){
            return $this->print_hc_emergencia($prestacion->id_ext);
        }else if ($prestacion->id_tipo_atencion==3){
            return $this->print_hc_hospitalizacion($prestacion->id_ext);
        }else{
            return $this->print_hc_ambulatorio($prestacion->id);
        }        
    }
    public function print_hc_ambulatorio($id_prestacion) {
        $model_prestacion = new Prestaciones;
        $model_receta = new Receta_vale;
        $prestacion = DB::table('prestaciones')
        ->join('citas as c', 'prestaciones.id_cita', '=', 'c.id')
        ->join('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
        ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
        ->leftJoin('asegurados', 'prestaciones.id_asegurado', '=', 'asegurados.id')
        ->leftJoin('establecimientos as e', 'prestaciones.id_establecimiento', '=', 'e.id')
        ->leftJoin('asegurado_historias as ah', function ($join) {
            $join->on('asegurados.id', '=', 'ah.id_asegurado')
                 ->on('e.id', '=', 'ah.id_establecimiento'); // Se agrega la condición aquí
        })
        ->leftJoin('tipo_beneficiarios as tb', 'asegurados.id_tipo_beneficiario', '=', 'tb.id')
        ->leftJoin('distritos', 'prestaciones.id_asegurado', '=', 'distritos.id_distrito')
        ->leftJoin('provincias', 'distritos.id_provincia', '=', 'provincias.id_provincia')
        ->leftJoin('departamentos', 'provincias.id_departamento', '=', 'departamentos.id_departamento')
        ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento', '=', 'distrito_nacimiento.id_distrito')
        ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia', '=', 'provincia_nacimiento.id_provincia')
        ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento', '=', 'departamento_nacimiento.id_departamento')
        ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia', '=', 'distrito_procedencia.id_distrito')
        ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia', '=', 'provincia_procedencia.id_provincia')
        ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento', '=', 'departamento_procedencia.id_departamento')
        ->leftJoin('razas', 'asegurados.id_raza', '=', 'razas.id')
        ->leftJoin('estado_civiles', 'asegurados.id_estado_civil', '=', 'estado_civiles.id')
        ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion', '=', 'grado_instrucciones.id')
        ->leftJoin('ocupaciones', 'asegurados.id_ocupacion', '=', 'ocupaciones.id')
        ->leftJoin('religiones', 'asegurados.id_religion', '=', 'religiones.id')
        ->leftJoin('tipo_sangres as ts','asegurados.id_tipo_sangre','=','ts.id')
        ->leftJoin('sub_consultorios as sc','prestaciones.id_sub_consultorio','=','sc.id')
        ->leftJoin('consultorios as co','sc.id_consultorio','=','co.id')
        ->select(
            'asegurados.*', 
            'distritos.descripcion as distrito', 
            'provincias.descripcion as provincia', 
            'departamentos.descripcion as departamento', 
            'razas.nombre as etnia',
            'estado_civiles.nombre as estado_civil', 
            'grado_instrucciones.nombre as grado_instruccion', 
            'ocupaciones.nombre as ocupacion', 
            'religiones.nombre as religion',
            'distrito_nacimiento.descripcion as distrito_nacimiento2', 
            'provincia_nacimiento.descripcion as provincia_nacimiento2', 
            'departamento_nacimiento.descripcion as departamento_nacimiento2',
            'distrito_procedencia.descripcion as distrito_procedencia2', 
            'provincia_procedencia.descripcion as provincia_procedencia2', 
            'departamento_procedencia.descripcion as departamento_procedencia2',
            DB::raw("date_part('year', age(to_date(to_char(asegurados.fecha_nac::date, 'DD/MM/YYYY'), 'DD/MM/YYYY'))) as edadpaciente"),
            'prestaciones.*',
            DB::raw("to_char(prestaciones.created_at, 'HH:MI AM') hora_ingreso"),
            DB::raw("to_char(prestaciones.fecha_atencion, 'DD/MM/YYYY') fecha_ingreso"),
            'm.paterno as medico_paterno', 
            'm.materno as medico_materno', 
            'm.nombre as medico_nombre', 
            'm.colegiatura as profesional_colegiatura',
            'p.nombre as profesional_profesion',
            'ah.nro_historia',
            'tb.descripcion as tipo_beneficiario',
            'c.codigo_siteds',
            'prestaciones.cups_pnp',
            'ts.descripcion_corta as grupo_sangre',
            'co.nombre as servicio',
            'sc.descripcion as consultorio'
        )
        ->where('asegurados.id_estado_reg', 1)
        ->where('prestaciones.id', $id_prestacion)
        ->where('prestaciones.id_tipo_atencion', 1)
        ->where('prestaciones.id_estado_reg', 1)
        ->first();
            
        $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procedimientos = $model_prestacion->GetProcedimientoByIdPrestacionNew($id_prestacion);
        //$recetas = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);
        $recetas = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->where('receta_vales.estado', '<>', 0)
            ->where('receta_vales.id_evaluacion', '=', 0)
            ->where('receta_vales.id_interconsulta', '=', 0)
            ->orderBy('receta_vales.id', 'asc')
            ->get();
        
        $receta_productos = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->where('receta_vales.id_prestacion', $prestacion->id)
            ->select(
                'receta_vales.id as id_receta',
                'producto_recetas.id as id_producto_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            ->orderBy('receta_vales.id', 'asc')
            ->orderBy('producto_recetas.id', 'asc')
            ->get();
        
        
        $ordenes_laboratorio = DB::table('laboratorios')
            ->where('id_prestacion', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', 0)
            ->select(
                'id',
                'fecha_atencion',
                'fecha_firma',
                'ruta_laboratorio',
                'flag',
                'secuencial',
                'fecha_registro_toma_muestra',
                'estado',
                DB::raw("to_char(fecha_registro_toma_muestra, 'DD/MM/YYYY') AS fecha_toma_muestra")
            )
            ->orderBy('id', 'asc')
            ->get();
                
        $orden_lab_examenes = DB::table('prestacion_procedimientos')
            ->join('labnivelatenciones', 'prestacion_procedimientos.id_rua', '=', 'labnivelatenciones.id')
            ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
            ->where('prestacion_procedimientos.id_prestacion', $prestacion->id)
            ->where('prestacion_procedimientos.id_estado_reg', 1)
            ->select(
                'labnivelatenciones.id as id',
                'prestacion_procedimientos.id_lab_rad as id_orden',
                'labnivelatenciones.codigo_lab as codigo',
                'labnivelatenciones.descripcion_corta as nombre',
                'procedimientos.descripcion as nombre_completo',
                'prestacion_procedimientos.cantidad',
                'prestacion_procedimientos.created_at',
                'labnivelatenciones.id_procedimiento',
                'prestacion_procedimientos.observacion',
                'diagnosticos.codigo as codigo_diagnostico',
                'diagnosticos.nombre as diagnostico',
                'prestacion_procedimientos.tipo_muestra_microbiologia',
                'prestacion_procedimientos.id_solicitud_procedimiento'
            )
            ->orderBy('prestacion_procedimientos.id', 'asc')
            ->get();
        
        $ordenes_imagenologia = DB::table('radiologias')
            ->where('id_prestacion', '=', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', '=', 0)
            ->orderBy('id','asc')
            ->get();

        $orden_img_examenes = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id')
            ->get();
        
        $alergias = DB::table('enfermerias as e')
            ->join('antecedentes_enfermerias as ae', 'e.id', '=', 'ae.id_enfermeria')
            ->where('e.id_prestacion', $prestacion->id)
            ->where('ae.id_alergia', 1)
            ->select('ae.descripcion_alergia')
            ->first();

        
        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($prestacion->id_asegurado_titular != null || $prestacion->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$prestacion->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();

                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        
        $pdf = \PDF::loadView('pdf.hc_ambulatorio', compact('prestacion','diagnosticos','procedimientos','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','titular','grado','persona_datos_adicionales','alergias'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('paciente_historia_clinica_ce.pdf');
    }
    public function print_hc_emergencia($id_emergencia) {

        /*********************************************/
        $model_emergencia = new Emergencia();
        $emergencia = $model_emergencia->GetBuscaEmergenciaById($id_emergencia);  

        $prestacion = DB::table('prestaciones')
            ->join('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->select('prestaciones.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion')
            ->where('prestaciones.id_ext', $id_emergencia)
            ->where('prestaciones.id_tipo_atencion', 2)
            ->where('prestaciones.id_estado_reg', 1)
            ->first();
        $sw = 0;
        if($prestacion->id_ext!=0):
            $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first(); 
            $model_emergencia = new \App\Models\Emergencia;
            if($dato_emergencia->id_prioridad<4):
                $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
                $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
                $sw = 1;
                $prestacion->servicio= $obtenerdato->servicio;
                $prestacion->consultorio= $obtenerdato->consultorio;
            else:
                $prestacion->servicio="IV - PATOLOGÍA AGUDA COMÚN";
                $prestacion->consultorio="CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
                $sw = 0;
            endif;
        endif;

        $model_evaluaciones = new \App\Models\Evaluacione;
        $atenciones = $model_evaluaciones->GetAtencionesByIdExt($id_emergencia,2);

        $recetas = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->where('receta_vales.estado', '<>', 0)
            ->where('receta_vales.id_evaluacion', '=', 0)
            ->where('receta_vales.id_interconsulta', '=', 0)
            ->orderBy('receta_vales.id', 'asc')
            ->get();
        
        $receta_productos = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->where('receta_vales.id_prestacion', $prestacion->id)
            ->select(
                'receta_vales.id as id_receta',
                'producto_recetas.id as id_producto_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            ->orderBy('receta_vales.id', 'asc')
            ->orderBy('producto_recetas.id', 'asc')
            ->get();
        
        $ordenes_laboratorio = DB::table('laboratorios')
            ->where('id_prestacion', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', 0)
            ->select(
                'id',
                'fecha_atencion',
                'fecha_firma',
                'ruta_laboratorio',
                'flag',
                'secuencial',
                'fecha_registro_toma_muestra',
                'estado',
                DB::raw("to_char(fecha_registro_toma_muestra, 'DD/MM/YYYY') AS fecha_toma_muestra")
            )
            ->orderBy('id', 'asc')
            ->get();
        
        $orden_lab_examenes = DB::table('prestacion_procedimientos')
            ->join('labnivelatenciones', 'prestacion_procedimientos.id_rua', '=', 'labnivelatenciones.id')
            ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
            ->where('prestacion_procedimientos.id_prestacion', $prestacion->id)
            ->where('prestacion_procedimientos.id_estado_reg', 1)
            ->select(
                'labnivelatenciones.id as id',
                'prestacion_procedimientos.id_lab_rad as id_orden',
                'labnivelatenciones.codigo_lab as codigo',
                'labnivelatenciones.descripcion_corta as nombre',
                'procedimientos.descripcion as nombre_completo',
                'prestacion_procedimientos.cantidad',
                'prestacion_procedimientos.created_at',
                'labnivelatenciones.id_procedimiento',
                'prestacion_procedimientos.observacion',
                'diagnosticos.codigo as codigo_diagnostico',
                'diagnosticos.nombre as diagnostico',
                'prestacion_procedimientos.tipo_muestra_microbiologia',
                'prestacion_procedimientos.id_solicitud_procedimiento'
            )
            ->orderBy('prestacion_procedimientos.id', 'asc')
            ->get();
        
        $ordenes_imagenologia = DB::table('radiologias')
            ->where('id_prestacion', '=', $prestacion->id)
            ->where('estado', '<>', 0)
            ->where('id_evaluacion', '=', 0)
            ->orderBy('id','asc')
            ->get();

        $orden_img_examenes = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id')
            ->get();

        $interconsultas = DB::table('interconsultas as t8')
            ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
            ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
            ->leftjoin('medicos as tr', 't8.personal_salud_interconsulta', '=', 'tr.id')
            ->where('t8.id_prestacion', $prestacion->id)
            ->where('t8.id_evaluacion', 0)
            ->where('t8.id_estado_reg', 1)
            ->select(
                't8.id',
                't3.nombre as establecimiento',
                't8.id_servicio',
                't6.nombre as servicio',
                'tr.nombre as profesional_nombre',
                'tr.paterno as profesional_paterno',
                'tr.materno as profesional_materno',
                'tr.colegiatura as profesional_colegiatura',
                't8.codigo_upss as codigo_interconsulta',
                't8.motivo_interconsulta',
                't8.resumen_interconsulta',
                't8.fecha_firma_interconsulta',
                't8.id as id_interconsulta',
                't8.created_at'
            )
            ->orderBy('t8.created_at', 'ASC')
            ->get();

        $diagnosticos = DB::table('receta_diagnosticos as rd')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->select('rd.*', 'd.codigo as cod_dx', 'd.nombre as nombre_dx')
            ->where('rd.id_prestacion', $prestacion->id)
            ->where('rd.estado', 1)
            ->where(function ($sql) {
                $sql->whereNull('rd.id_evaluacion')
                    ->orWhere('rd.id_evaluacion', 0);
            })
            // ->whereNull('rd.id_receta_vale')
            ->orderBy('rd.id')
            ->orderBy('rd.id_categoria')
            ->orderBy('rd.id_tipo_diagnostico')
            ->get();
        
        $procedimientos = DB::table('prestacion_procedimientos as pp')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->select('pp.id', 'pp.cantidad', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms')
            ->where('pp.id_prestacion', $prestacion->id)
            ->where('pp.id_evaluacion', 0)
            ->where('pp.tipo_procedimiento', 1)
            ->where('pp.id_estado_reg', 1)
            ->orderBy('pp.id')
            ->get();

        $evaluaciones = DB::table('evaluaciones as e')
            ->select('e.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 
            'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion','camas.nombre as cama','consultorios.nombre as servicio_sala,
            pre.temperatura,pre.sa02_porcentaje,pre.presion_arterial_x,pre.presion_arterial_y,pre.talla_cm,pre.tamano_cintura_cm,pre.frecuencia_respiratoria,
            pre.peso_kg,pre.frecuencia_cardiaca_fetal,pre.frecuencia_cardiaca,pre.fi02,pre.motivo_visita,pre.tiempo_enfermedad,pre.examen_clinico,
            pre.examen_auxiliar,pre.tratamiento_auxiliar,pre.plan_trabajo,pre.antecedente,pre.antecedente_familiar')
            ->join('prestaciones as pre', 'e.id_prestacion', '=', 'pre.id')
            ->join('medicos as m', 'e.id_medico', '=', 'm.id')
            ->join('camas','e.cama_estado','=','camas.id')
            ->join('cuartos','camas.id_cuarto','=','cuartos.id')
            ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
            ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->where('e.id_prestacion', $prestacion->id)
            ->where('e.id_estado_reg', 1)
            ->orderBy('e.created_at', 'desc')
            ->get();

            
        $recetas_evaluaciones = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->where('receta_vales.estado', '<>', 0)
            ->orderBy('receta_vales.id_evaluacion', 'desc')
            ->orderBy('receta_vales.id', 'asc')
            ->get();

        $receta_productos_evaluaciones = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->select(
                'producto_recetas.id as id_producto_receta',
                'receta_vales.id_evaluacion as id_evolucion',
                'producto_recetas.id_receta_vale as id_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            ->where('receta_vales.id_prestacion', '=', $prestacion->id)
            ->orderBy('receta_vales.id_evaluacion', 'desc')
            ->orderBy('producto_recetas.id', 'asc')
            ->get();
            
        $ordenes_laboratorio_evaluaciones = DB::table('laboratorios')
            ->where('id_prestacion', '=', $prestacion->id)
            ->where('estado', '<>', 0)
            ->orderBy('id', 'ASC')
            ->get();

        $orden_lab_examenes_evaluaciones = DB::table('prestacion_procedimientos as t1')
            ->join('labnivelatenciones as t2', 't1.id_rua', '=', 't2.id')
            ->join('procedimientos as p', 't1.id_procedimiento', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'd.id', '=', 't1.id_diagnostico')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo_lab as codigo',
                't2.descripcion_corta as nombre',
                'p.descripcion as nombre_completo',
                't1.cantidad',
                't1.created_at',
                't2.id_procedimiento',
                't1.observacion',
                'd.codigo as codigo_diagnostico',
                'd.nombre as diagnostico'
            )
            ->orderBy('t1.id', 'ASC')
            ->get();

        $ordenes_imagenologia_evaluaciones = DB::table('radiologias')
            ->where('id_prestacion', $prestacion->id)
            ->where('estado', '<>', 0)
            ->orderBy('id', 'ASC')
            ->get();

        $orden_img_examenes_evaluaciones = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_prestacion', $prestacion->id)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id', 'asc')
            ->get();
        
        $interconsultas_evaluaciones = DB::table('interconsultas as t8')
            ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
            ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
            ->leftjoin('medicos as t5', 't8.personal_salud_interconsulta', '=', 't5.id')
            ->where('t8.id_prestacion', $prestacion->id)
            ->where('t8.id_estado_reg', 1)
            ->select(
                't8.id',
                't8.id_evaluacion as id_evaluacion',
                't3.nombre as establecimiento',
                't6.nombre as servicio',
                't5.nombre as profesional_nombre',
                't5.paterno as profesional_paterno',
                't5.materno as profesional_materno',
                't8.motivo_interconsulta',
                't8.codigo_upss as codigo_interconsulta',
                't5.colegiatura as profesional_colegiatura'
            )
            ->orderBy('t8.id')
            ->get();

        $diagnosticos_evaluaciones = DB::table('evaluaciones as e')
            ->join('receta_diagnosticos as rd', 'e.id', '=', 'rd.id_evaluacion')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->select('e.id as id_evaluacion', 'd.codigo as cod_dx', 'd.nombre as nombre_dx', 'rd.id_tipo_diagnostico', 'rd.id_clasificacion_diagnosticos', 'rd.id_categoria')
            ->where('e.id_prestacion', $prestacion->id)
            ->where('e.id_estado_reg', 1)
            ->where('rd.estado', 1)
            ->orderBy('e.id')
            ->orderBy('rd.id')
            ->get();

            

        $procedimientos_evaluaciones = DB::table('evaluaciones as e')
            ->join('prestacion_procedimientos as pp', 'e.id', '=', 'pp.id_evaluacion')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->select('e.id as id_evaluacion', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms', 'pp.cantidad')
            ->where('e.id_prestacion', $prestacion->id)
            ->where('e.id_estado_reg', 1)
            ->where('pp.tipo_procedimiento', 1)
            ->where('pp.id_estado_reg', 1)
            ->orderBy('e.id')
            ->orderBy('pp.id')
            ->get();

        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$emergencia->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();


        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();

                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        if (intval($prestacion->id) === 3904132):
            $autoPrint = true;
            return view('pdf.hc_emergencia', compact('datoadicional','sw','procedimientos','emergencia','prestacion','asegurado','titular','persona_datos_adicionales','grado','diagnosticos','evaluaciones','diagnosticos_evaluaciones','procedimientos_evaluaciones'
                                                                        ,'camas.nombre as cama','consultorios.nombre as servicio_sala','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','interconsultas'
                                                                        ,'recetas_evaluaciones','receta_productos_evaluaciones','ordenes_laboratorio_evaluaciones','orden_lab_examenes_evaluaciones','ordenes_imagenologia_evaluaciones','orden_img_examenes_evaluaciones','interconsultas_evaluaciones','autoPrint'));
        else:
            $pdf = \PDF::loadView('pdf.hc_emergencia', compact('datoadicional','sw','procedimientos','emergencia','prestacion','asegurado','titular','persona_datos_adicionales','grado','diagnosticos','evaluaciones','diagnosticos_evaluaciones','procedimientos_evaluaciones'
                                                                        ,'camas.nombre as cama','consultorios.nombre as servicio_sala','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','interconsultas'
                                                                        ,'recetas_evaluaciones','receta_productos_evaluaciones','ordenes_laboratorio_evaluaciones','orden_lab_examenes_evaluaciones','ordenes_imagenologia_evaluaciones','orden_img_examenes_evaluaciones','interconsultas_evaluaciones'));
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('paciente_historia_clinica_e.pdf');
        endif;
    }
    public function print_hc_hospitalizacion($id_hospitalizacion) {

        $id_establecimiento = Auth::user()->id_establecimiento;
        $model_hospitalizacion = new Hospitalizacione();
        $atenciones = $model_hospitalizacion->getAtencionesHCById($id_hospitalizacion);
        // if (Auth::user()->dni != '48839708'):
        // $pdf = \PDF::loadView('pdf.hc_hospitalizacion', compact('atenciones'));
        // $pdf->getDomPDF()->set_option("enable_php", true);
        // return $pdf->stream('paciente_historia_clinica_h.pdf');
        // else:
        // return view('pdf.hc_hospitalizacion', compact('atenciones'));
        // endif;
        $autoPrint = true;
        return view('pdf.hc_hospitalizacion', compact('atenciones', 'autoPrint'));
    }
    public function print_hospitalizacion_papeleta_egreso_atencion_quirurgica($id_hospitalizacion){

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_prestacion = new \App\Models\Prestaciones;
        $id_establecimiento=Auth::user()->id_establecimiento;

        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getDatosPapeletaEgresoAtencionQuirurgica($id_hospitalizacion);
        $prestacion = DB::table('cqx_atenciones')
        ->select('id_prestacion')
        ->where('id_hospitalizacion', $id_hospitalizacion)
        ->first();
 
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($prestacion->id_prestacion);

        if(!is_object($hospitalizacion)){
            Session::flash('flash_warning', 'El paciente aún no se le ha dado de alta.');
            return redirect('/dashboard');
        }

        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        $dias_estancia = 0;
        $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_ingreso);

        /* CALCULAR ESTANCIA EN HOSPITAL EN BASE A ALTA MEDICA*/

        $fecha_alta_administrativa = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_alta_medica);
        $dias_calculados = $fecha_alta_administrativa->diffInDays($fecha_ingreso);
        
        $dias_estancia = $dias_calculados + 1;

        $user_actual = Auth::user();
            
        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();

        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();
                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        $concurrencias = DB::table('hospitalizacion_concurrencias as hc')
                ->join('consultorios as c', 'c.id', '=', 'hc.id_servicio')
                ->select('hc.id', 'hc.id_hospitalizacion', 'hc.id_servicio', 'c.nombre')
                ->where('hc.id_hospitalizacion', $id_hospitalizacion)
                ->get();

        $pdf = \PDF::loadView('pdf.hospitalizacion_papeleta_egreso_atencion_quirurgica',
                [
                    'asegurado' => $asegurado,
                    'concurrencias' => $concurrencias,
                    'dias_estancia' => $dias_estancia,
                    'fechaActual' => $fechaActual,
                    'grado' => $grado,
                    'horaActual' => $horaActual,
                    'hospitalizacion'=> $hospitalizacion,
                    'persona_datos_adicionales' => $persona_datos_adicionales,
                    'titular' => $titular,
                    'user_actual' => $user_actual,
                    'diagnostico'=>$diagnostico
                ]
        );

        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("papeleta_egreso_atencion_quirurgica_$id_hospitalizacion.pdf");
    }
    public function print_hospitalizacion_reporte_operatorio_cq($id_hospitalizacion){

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_farmacia = Auth::user()->id_farmacia;
        $model_establecimiento = new \App\Models\Establecimiento;
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $id_ipress = $farmacia->id_establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        

        $model_hospitalizacion = new Hospitalizacione();
        $datos = $model_hospitalizacion->get_reporte_operatorio_cqx($id_hospitalizacion);
        $pdf = \PDF::loadView('pdf.hospitalizacion_reporte_operatorio_cq',
                [
                  'datos'=>$datos,
                  'establecimiento'=>!empty($establecimiento[0]->nombre)?$establecimiento[0]->nombre:''
                ]
        );
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("reporte_operatorio_cq_$id_hospitalizacion.pdf");
    }


    public function laboratorio_ver_rua_sr($id_laboratorio){

      $laboratorio = Laboratorio::find($id_laboratorio);
      $establecimiento = Establecimiento::where("id",$laboratorio->id_establecimiento)->get();
      $laboratrio_model = new Laboratorio();
      $model_prestacion = new Prestaciones();
                  $prestacion = $model_prestacion->GetPrestacionById($laboratorio->id_prestacion);
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEvaluacion($laboratorio->id_prestacion, $laboratorio->id_evaluacion);
                          $lista_rua = $laboratrio_model->getPrestacionProcedimiento($id_laboratorio);

      $consultorio = $laboratrio_model->obtener_consultorio_id_lab($id_laboratorio);
      // return view('frontend.laboratorios.rua_sr',
      // compact('establecimiento','consultorio','diagnostico','prestacion'));
      $pdf = \PDF::loadView('frontend.laboratorios.rua_sr',
      compact('establecimiento','consultorio','diagnostico','prestacion','lista_rua','laboratorio')
      ) ->setPaper('A5', 'portrait'); // 'portrait' o 'landscape'
      $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("reporte_operatorio.pdf");
    }

    public function print_mopri_cqx($id_intervencion){
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_hospitalizacion = new Hospitalizacione();
        $datos = $model_hospitalizacion->get_reporte_mopri_cqx($id_intervencion);
        $pdf = \PDF::loadView('pdf.print_mopri_cq',
                [
                  'datos'=>$datos 
                ]
        );
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("reporte_mopri_cq_$id_intervencion.pdf");
    }
    
    public function print_hospitalizacion_informe_egreso($id_hospitalizacion){

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_establecimiento=Auth::user()->id_establecimiento;

        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getDatosPapeletaEgreso($id_hospitalizacion);

        $hospi = DB::table('hospitalizaciones')
        ->select('hospitalizaciones.*')
        ->where('hospitalizaciones.id',$id_hospitalizacion)
        ->first();
        $diag_inicio = [];
        
        $diag_inicio[0] =DB::table('diagnosticos')
        ->select('diagnosticos.*')
        ->where('diagnosticos.id',$hospi->id_diagnostico_inicial)
        ->first();
        if(!is_null($hospi->id_dx_2)){
            $diag_inicio[1] =DB::table('diagnosticos')
        ->select('diagnosticos.*')
        ->where('diagnosticos.id',$hospi->id_dx_2)
        ->first();
        }
        if(!is_null($hospi->id_dx_3)){
            $diag_inicio[2] =DB::table('diagnosticos')
        ->select('diagnosticos.*')
        ->where('diagnosticos.id',$hospi->id_dx_3)
        ->first();
        }

        $procedimiento="SELECT pp.cantidad,p2.codigo,p2.descripcion from prestaciones p
            inner join hospitalizaciones h on p.id_ext = h.id
            inner join prestacion_procedimientos pp on pp.id_prestacion = p.id
            inner join procedimientos p2 on p2.id = pp.id_procedimiento
            where h.id = '$id_hospitalizacion'";
        $procedimiento = DB::select($procedimiento);

        $camas = DB::table('camas')
        ->select('camas.*')
        ->where('id',$hospi->id_cama)
        ->first();

        if(!is_object($hospitalizacion)){
            Session::flash('flash_warning', 'El paciente aún no se le ha dado de alta.');
            return redirect('/dashboard');
        }

        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        $dias_estancia = 0;
        $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_ingreso);

        /* CALCULAR ESTANCIA EN HOSPITAL EN BASE A ALTA MEDICA*/

        $fecha_alta_administrativa = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_alta_medica);
        $dias_calculados = $fecha_alta_administrativa->diffInDays($fecha_ingreso);
        
        $dias_estancia = $dias_calculados + 1;

        $user_actual = Auth::user();
            
        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();

        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();
                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        $concurrencias = DB::table('hospitalizacion_concurrencias as hc')
                ->join('consultorios as c', 'c.id', '=', 'hc.id_servicio')
                ->select('hc.id', 'hc.id_hospitalizacion', 'hc.id_servicio', 'c.nombre')
                ->where('hc.id_hospitalizacion', $id_hospitalizacion)
                ->get();
        $pdf = \PDF::loadView('pdf.hospitalizacion_informe_egreso',
                [
                    'asegurado' => $asegurado,
                    'concurrencias' => $concurrencias,
                    'dias_estancia' => $dias_estancia,
                    'fechaActual' => $fechaActual,
                    'grado' => $grado,
                    'horaActual' => $horaActual,
                    'hospitalizacion'=> $hospitalizacion,
                    'hospi' => $hospi,
                    'diag_inicio' => $diag_inicio,
                    'camas' => $camas,
                    'procedimiento' => $procedimiento,
                    'persona_datos_adicionales' => $persona_datos_adicionales,
                    'titular' => $titular,
                    'user_actual' => $user_actual
                ]
        );

        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("informe_egreso_$id_hospitalizacion.pdf");
    }

    public function print_sop_cqx($id_intervencion){

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        // $model_hospitalizacion = new Hospitalizacione();
        // $datos = $model_hospitalizacion->get_reporte_operatorio_cqx($id_intervencion);
        $pdf = \PDF::loadView('pdf.sop_programacion_operatorio_cq',
                [
                  'datos'=>'' 
                ]
        );
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("reporte_sop_programacion_operatorio_cq_$id_intervencion.pdf");
    }
    public function pdf_solicitud_teleinterconsulta($id_prestacion, $id_referencia) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_referencia = new \App\Models\Referencia;        
        $referencia = $model_referencia->GetReferenciaById($id_prestacion,$id_referencia);
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $ipress_origen = DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('id',$referencia->id_establecimiento)
        ->first();
        $asegurado = DB::table('asegurados')
        ->select('asegurados.*')
        ->where('id',$prestacion->id_asegurado)
        ->first();
    
        $asegurado_hc = DB::table('asegurado_historias')
        ->select('asegurado_historias.*')
        ->where('id_asegurado',$asegurado->id)
        ->where('id_establecimiento',$ipress_origen->id)
        ->first();

        $consultorios = DB::table('sub_consultorios')
            ->join('consultorios', 'consultorios.id', '=', 'sub_consultorios.id_consultorio')
            ->select('consultorios.id', 'consultorios.nombre')
            ->where('sub_consultorios.id',$prestacion->id_sub_consultorio)
            ->first();
        
        $diagnosticos = "SELECT DISTINCT ON (d.id) d.* from procedimientos_diagnoticos pd
                         inner join diagnosticos d on d.id = pd.id_diagnosticos
                           where pd.id_prestacion =  '$id_prestacion' ";
        $diagnosticos = DB::select($diagnosticos);

        $ipress_destino = DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('id',$referencia->id_establecimiento_des)
        ->first();
        $date = Carbon::now();
        $fechaActual = $date->format('d-m-Y');
        $horaActual = $date->format('H:i:s');

        $tiempo="SELECT to_char(age(TO_DATE('$asegurado->fecha_nac' , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"días\"') as fec_nacim_descrip";
        $tiempo = DB::select($tiempo);
        $tiempo=$tiempo[0]->fec_nacim_descrip;
        if (!empty($referencia->fecha_solicitud_tele)) {
            list($fecha, $horaCompleta) = explode(' ', $referencia->fecha_solicitud_tele);
            $hora = substr($horaCompleta, 0, 5);
            list($anio, $mes, $dia) = explode('-', $fecha);
            $fecha_formateada = $dia . '/' . $mes . '/' . $anio;
        } else {
            $fecha_formateada = '—';
            $hora = '—';
        }

    $pdf = \PDF::loadView('pdf.solicitud_teleconsulta', compact('diagnosticos','ipress_destino','fecha_formateada','hora','asegurado','tiempo','consultorios','asegurado_hc','ipress_origen','emergencia','prestacion','referencia','fechaActual','horaActual'));
    $pdf->getDomPDF()->set_option("enable_php", true);
    return $pdf->stream('solicitud_teleconsulta.pdf');
    }

        public function pdf_respuesta_teleinterconsulta($id_prestacion, $id_referencia) {
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_referencia = new \App\Models\Referencia;        
        $referencia = $model_referencia->GetReferenciaById($id_prestacion,$id_referencia);
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $ipress_origen = DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('id',$referencia->id_establecimiento)
        ->first();
        $asegurado = DB::table('asegurados')
        ->select('asegurados.*')
        ->where('id',$prestacion->id_asegurado)
        ->first();
    
        $asegurado_hc = DB::table('asegurado_historias')
        ->select('asegurado_historias.*')
        ->where('id_asegurado',$asegurado->id)
        ->where('id_establecimiento',$ipress_origen->id)
        ->first();

        $consultorios = DB::table('sub_consultorios')
            ->join('consultorios', 'consultorios.id', '=', 'sub_consultorios.id_consultorio')
            ->select('consultorios.id', 'consultorios.nombre')
            ->where('sub_consultorios.id',$prestacion->id_sub_consultorio)
            ->first();
        
        $diagnosticos = "SELECT DISTINCT ON (d.id) d.* from procedimientos_diagnoticos pd
                         inner join diagnosticos d on d.id = pd.id_diagnosticos
                           where pd.id_prestacion =  '$id_prestacion' ";
        $diagnosticos = DB::select($diagnosticos);

        $ipress_destino = DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('id',$referencia->id_establecimiento_des)
        ->first();
        $date = Carbon::now();
        $fechaActual = $date->format('d-m-Y');
        $horaActual = $date->format('H:i:s');

        $tiempo="SELECT to_char(age(TO_DATE('$asegurado->fecha_nac' , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"días\"') as fec_nacim_descrip";
        $tiempo = DB::select($tiempo);
        $tiempo=$tiempo[0]->fec_nacim_descrip;
        if (!empty($referencia->fecha_solicitud_tele)) {
            list($fecha, $horaCompleta) = explode(' ', $referencia->fecha_solicitud_tele);
            $hora = substr($horaCompleta, 0, 5);
            list($anio, $mes, $dia) = explode('-', $fecha);
            $fecha_formateada = $dia . '/' . $mes . '/' . $anio;
        } else {
            $fecha_formateada = '—';
            $hora = '—';
        }

    $pdf = \PDF::loadView('pdf.respuesta_teleconsulta', compact('diagnosticos','ipress_destino','fecha_formateada','hora','asegurado','tiempo','consultorios','asegurado_hc','ipress_origen','emergencia','prestacion','referencia','fechaActual','horaActual'));
    $pdf->getDomPDF()->set_option("enable_php", true);
    return $pdf->stream('solicitud_teleconsulta.pdf');
    }

    public function print_atencion_quirurgica_epicrisis($id_intervencion) 
    {
       $epicrisis = DB::table('cqx_atenciones as a')
        ->join('cqx_intervenciones as i', 'a.id_cqx_intervencion', '=', 'i.id')
        ->select('a.*', 'i.id')
        ->where('i.id', $id_intervencion)
        ->first();
        
        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getHospitalizacionByIdCqx($epicrisis->id_hospitalizacion);
  
        $dias_estancia = 0;
        $fecha_ingreso = Carbon::createFromFormat('Y-m-d H:i:s', $hospitalizacion->fecha_ingreso);
        $fecha_alta_medica = Carbon::createFromFormat('Y-m-d H:i:s', $hospitalizacion->fecha_alta_medica);
        $diferencia_dias = $fecha_alta_medica->diffInDays($fecha_ingreso);
        $dia_estancia = $diferencia_dias + 1;
        
        $servicios_concurrencia = null;
        if ($hospitalizacion->id_tipo_egreso == 1) {
            $servicios_concurrencia = $model_hospitalizacion->getServiciosConcurrenciaByIdHospitalizacion($epicrisis->id_hospitalizacion);
        }

        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;

        $model_prestacion = new \App\Models\Prestaciones;
        $diagnosticos_egreso = $model_prestacion->GetDiagnosticoByIdPrestacion($epicrisis->id_prestacion);

        $pdf = \PDF::loadView('pdf.atencion_quirurgica_epicrisis', compact('hospitalizacion','dia_estancia','servicios_concurrencia','nombre_establecimiento','epicrisis','diagnosticos_egreso'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('atencion_quirurgica_epicrisis.pdf');
    }

    public function ver_prestacion_atencion_imprimir($id_prestacion) {
        //--------------------------------------------
        $model_prestacion = new \App\Models\Prestaciones;
        $prestacion = $model_prestacion->GetPrestacionById_2($id_prestacion);
        $procedimiento = $model_prestacion->GetProcedimientoByIdPrestacionNew($id_prestacion);
        //$model_solicitud_pp = new \App\Models\SolicitudProgramacionProcedimientos;
        //$ordenProcedimiento = $model_solicitud_pp->GetOrdenByIdPrestacion($id_prestacion);
        $solicitudPp = null;
        $detallesAgrupados = null;
        /*if ($ordenProcedimiento) {
            $solicitudPp = \App\Models\SolicitudProgramacionProcedimientos::with([
                'prestacion' => function ($query) {
                    $query->select('prestaciones.id', 'prestaciones.id_establecimiento', 'prestaciones.id_asegurado', 'prestaciones.updated_at', 'prestaciones.id_tipo_atencion', 'prestaciones.id_sub_consultorio', 'prestaciones.id_medico')
                    ->with(['establecimiento' => function ($q) {
                        $q->select('establecimientos.id', 'establecimientos.nombre');
                    }]);
                }
            ])->with("detalles.procedimiento")->where("id", $ordenProcedimiento->id)->first();
            $detallesAgrupados = $solicitudPp->detalles->groupBy('id_procedimiento');
        }*/
        $emergencia_triaje = null;
        $natis = null;
        $informe_html = null;
        $datoadicional = null;
        /*if ($prestacion->informe_pp) {
            $informe_html = $prestacion->informe_pp;
        }*/
        /*
        |------------------------------------------------------------------------------------
        | VALIDACIÓN NUEVA
        |------------------------------------------------------------------------------------
        | Si es tipo atención 2 o 3 y no tiene id_ext, atender como tipo_atencion = 1.
        |-----------------------------------------------------------------------------------
        */
        if (($prestacion->id_tipo_atencion == 2 || $prestacion->id_tipo_atencion == 3) && empty($prestacion->id_ext)) {
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        } else {
            if ($prestacion->id_tipo_atencion == 2):
                if ($prestacion->id_establecimiento == 76 || $prestacion->id_establecimiento == 77 || $prestacion->id_establecimiento == 26 || $prestacion->id_establecimiento == 75):
                    $model_emergencia = new \App\Models\Emergencia;
                    $obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);
                    //$emergencia_triaje = $model_emergencia->obtenerRegistroEmergencia($prestacion->id_ext);
                    $emergencia_triaje = $model_emergencia->obtenerReporteSaludMaterna($prestacion->id_ext);
                    // para el foreach de neonatos
                    $emergencia = Emergencia::find($prestacion->id_ext);
                    if ($emergencia && isset($emergencia->id_puerperio)) {
                        $puerperio = Puerperio::find($emergencia->id_puerperio);
                        if ($puerperio) {
                            $natis = $puerperio->natis;
                        }
                    }
    
                    if ($obtenerdato) {
                        if ($obtenerdato->id_prioridad != 4):
                            $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
                            $prestacion->servicio = $obtenerdato->servicio;
                            $prestacion->consultorio = $obtenerdato->consultorio;
                            if ($datoadicional) {
                                $prestacion->piso = $datoadicional->piso;
                                $prestacion->pabellon = $datoadicional->pabellon;
                                $prestacion->cuarto = $datoadicional->cuarto;
                                $prestacion->cama = $datoadicional->cama;
                            } else {
                                $prestacion->piso = "";
                                $prestacion->pabellon = "";
                                $prestacion->cuarto = "";
                                $prestacion->cama = "";
                            }
                        else:
                            $prestacion->servicio = "IV - PATOLOGÍA AGUDA COMÚN";
                            $prestacion->consultorio = "CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
                            $prestacion->piso = "";
                            $prestacion->pabellon = "";
                            $prestacion->cuarto = "";
                            $prestacion->cama = "";
                        endif;
                    }
                    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
                else:
                    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
                endif;
            else:
                if ($prestacion->id_tipo_atencion == 3):
                    $controller_pdfcontroller = new \App\Http\Controllers\PdfController;
                    $cq_anestesia = DB::table('anestesia_quirurgicas')->where('anestesia_quirurgicas.id_prestacion', '=', $prestacion->id)->select('anestesia_quirurgicas.*')->first();
                    if (!is_null($cq_anestesia)) {
                        $pdf_hospitalizacion_atencion = $controller_pdfcontroller->print_hospitalizacion_atencion_cq_anestesia($prestacion->id);
                    } else {
                        $pdf_hospitalizacion_atencion = $controller_pdfcontroller->print_hospitalizacion_atencion($prestacion->id);
                    }
                    return $pdf_hospitalizacion_atencion;
                else:
                    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
                endif;
            endif;
        }
        $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
        ->where('estado', 1)
        ->lists('id_evento')
        ->toArray();
        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate($url_descarga);
        $pdf = \PDF::loadView('pdf.firmar_prestacion',compact('natis',/*'ordenProcedimiento',*/'detallesAgrupados',/*'informe_html',*/ 'emergencia_triaje','prestacion','eventosMarcados','diagnostico','procedimiento','codigo_qr','url_descarga'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        
        return $pdf->stream('firmar_prestacion.pdf');
    }

    public function ver_receta_vale_sinfirma($nro_receta) {

        $model_farmacia = new Farmacia();
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        //--------------------------------------------  
        $receta = $model_receta->ShowAllById($nro_receta);
        $receta = $receta[0];

        if($receta->id_evaluacion!=0 && empty($receta->id_interconsulta)):
            $receta = $model_receta->ShowAllByIdEvaluacion($nro_receta);
            $receta = $receta[0];
        endif;
        
        $dni_beneficiario = $receta->dni_beneficiario;
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($receta->id_farmacia);
        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($nro_receta);

        $productos = $model_receta->ProductosRecetaByIdV2($nro_receta);
        $model_receta_diagnostico = new Receta_diagnostico();
        $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdReceta($nro_receta);

        if ($receta->tipo_receta=='EMERGENCIA'):
            if($establecimiento->id==76):
                $model_emergencia = new \App\Models\Emergencia;
                $obtenerdato = $model_emergencia->GetObtenerServicioReceta($receta->id_prestacion);
                if($obtenerdato->id_prioridad != 4 ):
                    $receta->servicio= $obtenerdato->servicio;
                    $receta->consultorio= $obtenerdato->consultorio;
                else:
                    $receta->servicio= "IV - PATOLOGÍA AGUDA COMÚN";
                    $receta->consultorio= "CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
                endif;
            endif;
        endif;

        $datoadicional=null;
        if($receta->id_prestacion!=0):
            $model_prestacion = new \App\Models\Prestaciones;
            $prestacion = $model_prestacion->GetPrestacionById($receta->id_prestacion);
              $receta->talla =$prestacion->talla_cm;
              $receta->peso =$prestacion->peso_kg;
              $receta->cama = null; 
              $dato_adicional = null; 
              if($prestacion->id_ext!=null && $prestacion->id_tipo_atencion == 2):
                $model_emergencia = new \App\Models\Emergencia;
                $obtenerdato1 = $model_emergencia->GetObtenerServicioReceta($receta->id_prestacion);
                if($obtenerdato1->id_prioridad != 4 ):
                    $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
                    $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
                    $receta->cama = $datoadicional->cuarto.' - '. $datoadicional->cama;
                else:
                    $receta->cama = '';
                endif;
            else:
                if($prestacion->id_ext!=null && $prestacion->id_tipo_atencion == 3 ):
                  $model_cama = new \App\Models\Cama;
                  $dato_adicional = $model_cama->GetHospitalizacionCamaActualByHospitalizacionId($prestacion->id_ext);
                  $receta->cama = $dato_adicional ? $dato_adicional->cuarto.' - '. $dato_adicional->cama : '';
                endif;
            endif;
        else:
            $receta->talla = null;
            $receta->peso = null;
            $receta->cama = null; 
        endif;
        
        /**************************************************************************************************************/

        $titular = null;
        if ($receta->tipo_beneficiario != 'TITULAR'):
            $paciente_model = new \App\Models\Paciente();
            $titular = $paciente_model->GetAseguradoById($receta->id_asegurado_titular);
        endif;
    
        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_receta_".$nro_receta.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_receta_".$nro_receta.".pdf");
        // SOLICITUD PROGRAMACIÓN PROCEDIMIENTO ÓRDENES
        $es_proc_prog = intval(DB::table('receta_vales')->where('id', $nro_receta)->value('id_procp')) > 0;
        $pdf = \PDF::loadView('pdf.firmar_receta_vale',compact('medicamentos','receta','establecimiento','productos','diagnosticos','codigo_qr','url_descarga','titular','es_proc_prog'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        
        return $pdf->stream('firmar_receta_vale.pdf');
    
    }

    public function ver_receta_vale_alta($nro_receta) {
        $model_farmacia = new Farmacia();
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        $receta = $model_receta->ShowAllById($nro_receta);
        $receta = $receta[0];

        if($receta->id_evaluacion!=0):
        $receta = $model_receta->ShowAllByIdEvaluacion($nro_receta);
        $receta = $receta[0];
        endif;
        
        $dni_beneficiario = $receta->dni_beneficiario;
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($receta->id_farmacia);
        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($nro_receta);
        $productos = $model_receta->ProductosRecetaByIdV2($nro_receta);
        $model_receta_diagnostico = new Receta_diagnostico();
        $diagnosticos_egreso = $model_receta_diagnostico->GetDiagnosticosByIdRecetaEgreso($nro_receta);
        
        if ($receta->tipo_receta=='EMERGENCIA'):
        if($establecimiento->id==76  || $establecimiento->id==26 || $establecimiento->id==77 || $establecimiento->id==75):
            $model_emergencia = new \App\Models\Emergencia;
            $obtenerdato = $model_emergencia->GetObtenerServicioReceta($receta->id_prestacion);
            if($obtenerdato->id_prioridad != 4 ):
                $receta->servicio= $obtenerdato->servicio;
                $receta->consultorio= $obtenerdato->consultorio;
            else:
                $receta->servicio= "IV - PATOLOGÍA AGUDA COMÚN";
                $receta->consultorio= "CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
            endif;
        endif;
        endif;

        if($receta->id_prestacion!=0):
            $model_prestacion = new \App\Models\Prestaciones;
            $prestacion = $model_prestacion->GetPrestacionById($receta->id_prestacion);
            $receta->talla = $prestacion->talla_cm;
            $receta->peso = $prestacion->peso_kg;
            $receta->cama = null;
            $dato_adicional = null;
            if($prestacion->id_ext!=null && $prestacion->id_tipo_atencion == 2):
                $model_emergencia = new \App\Models\Emergencia;
                $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
                $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
                $receta->cama = $datoadicional->cuarto.' - '.$datoadicional->cama;
            elseif($prestacion->id_ext!=null && $prestacion->id_tipo_atencion == 3):
                $model_hospitalizacion = new \App\Models\Hospitalizacione;
                $dato_adicional = $model_hospitalizacion->GetObtenerDatos($prestacion->id_ext);
                $receta->cama = $dato_adicional->cuarto.' - '.$dato_adicional->cama;
            endif;
        else:
            $receta->talla = null;
            $receta->peso = null;
            $receta->cama = null;
        endif;
        
        $titular = null;
        if ($receta->tipo_beneficiario != 'TITULAR'):
            $paciente_model = new \App\Models\Paciente();
            $titular = $paciente_model->GetAseguradoById($receta->id_asegurado_titular);
        endif;

        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_receta_".$nro_receta.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_receta_".$nro_receta.".pdf");
        $pdf = \PDF::loadView('pdf.receta_vale_alta', compact('medicamentos','receta','establecimiento','productos','diagnosticos_egreso','codigo_qr','url_descarga','titular'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('receta_vale.pdf');
    }

    public function ver_receta_vale_alta_sinfirma($nro_receta) {
        $model_farmacia = new Farmacia();
        $model_receta = new Receta_vale;
        $model_productos_receta = new Producto_receta;
        //--------------------------------------------  
        $receta = $model_receta->ShowAllById($nro_receta);
        $receta = $receta[0];

        if($receta->id_evaluacion!=0):
        $receta = $model_receta->ShowAllByIdEvaluacion($nro_receta);
        $receta = $receta[0];
        endif;
        
        $dni_beneficiario = $receta->dni_beneficiario;
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($receta->id_farmacia);
        $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($nro_receta);

        $productos = $model_receta->ProductosRecetaByIdV2($nro_receta);

        $model_receta_diagnostico = new Receta_diagnostico();
        $diagnosticos_egreso = $model_receta_diagnostico->GetDiagnosticosByIdRecetaEgreso($nro_receta);
        
        if ($receta->tipo_receta=='EMERGENCIA'):
        if($establecimiento->id==76  || $establecimiento->id==26 || $establecimiento->id==77 || $establecimiento->id==75):
            $model_emergencia = new \App\Models\Emergencia;
            $obtenerdato = $model_emergencia->GetObtenerServicioReceta($receta->id_prestacion);
            if($obtenerdato->id_prioridad != 4 ):
                $receta->servicio= $obtenerdato->servicio;
                $receta->consultorio= $obtenerdato->consultorio;
            else:
                $receta->servicio= "IV - PATOLOGÍA AGUDA COMÚN";
                $receta->consultorio= "CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
            endif;
        endif;
        endif;

        /******************************************************************************************************************/

        //Datos adicionales
        if($receta->id_prestacion!=0):
            $model_prestacion = new \App\Models\Prestaciones;
            $prestacion = $model_prestacion->GetPrestacionById($receta->id_prestacion);
                $receta->talla =$prestacion->talla_cm;
                $receta->peso =$prestacion->peso_kg;
                $receta->cama = null; 
                $dato_adicional = null; 
            if($prestacion->id_ext!=null && $prestacion->id_tipo_atencion == 2):
                $model_emergencia = new \App\Models\Emergencia;
                $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
                $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
                $receta->cama = $datoadicional->cuarto.' - '. $datoadicional->cama;
            else:
                if($prestacion->id_ext!=null && $prestacion->id_tipo_atencion == 3 ):
                    $model_hospitalizacion = new \App\Models\Hospitalizacione;
                    $dato_adicional = $model_hospitalizacion->GetObtenerDatos($prestacion->id_ext);
                    $receta->cama = $dato_adicional->cuarto.' - '. $dato_adicional->cama;
                endif;
            endif;
        else:
            $receta->talla = null;
            $receta->peso = null;
            $receta->cama = null; 
        endif;
        
        /**************************************************************************************************************/
        $titular = null;
        if ($receta->tipo_beneficiario != 'TITULAR'):
            $paciente_model = new \App\Models\Paciente();
            $titular = $paciente_model->GetAseguradoById($receta->id_asegurado_titular);
        endif;
    
        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_receta_".$nro_receta.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_receta_".$nro_receta.".pdf");
        $pdf = \PDF::loadView('pdf.firmar_receta_vale_alta',compact('medicamentos','receta','establecimiento','productos','diagnosticos_egreso','codigo_qr','url_descarga','titular'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('firmar_receta_vale.pdf');
    }

    public function ver_evaluacion_firmar($id_evaluacion){
        $model_evaluacion = new \App\Models\Evaluacione;
        $evaluacion = $model_evaluacion->GetEvaluationById($id_evaluacion);
        $cups_pnp = DB::table('prestaciones as p')
                    ->select(DB::raw("
                        CASE
                            WHEN p.id_tipo_atencion = '2' THEN e.cups_pnp
                            WHEN p.id_tipo_atencion = '3' AND (h.cups_pnp IS NOT NULL OR h.cups_pnp != '') THEN h.cups_pnp
                            ELSE p.cups_pnp
                        END as cups,
                        p.id_tipo_atencion as tipo_atencion
                    "))
                    ->join('evaluaciones as e2', 'p.id', '=', 'e2.id_prestacion')
                    ->leftJoin('emergencias as e', function ($join) {
                        $join->on('p.id_ext', '=', 'e.id')
                            ->where('p.id_tipo_atencion', '=', '2');
                    })
                    ->leftJoin('hospitalizaciones as h', function ($join) {
                        $join->on('p.id_ext', '=', 'h.id')
                            ->where('p.id_tipo_atencion', '=', '3');
                    })
                    ->where('e2.id', '=', $id_evaluacion)
                    ->first();
        $diagnostico = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
        $procedimiento = $model_evaluacion->GetProcedimientosByIdEvaluacion($id_evaluacion, 1);
        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_evolucion_".$id_evaluacion.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_evaluacion_".$id_evaluacion.".pdf");
        $model_emergencia = new Emergencia();
        $emergencia_triaje = $model_emergencia->obtenerReporteSaludMaterna($evaluacion->id_emergencia);
        $puerperio = Puerperio::where("id", $emergencia_triaje->id_puerperio)->first();
        $natis = null;$eventosMarcados=null;
        if($puerperio!=null):
            $natis = $puerperio->natis;
            $eventosMarcados = Prestacion_eventos::where('id_prestacion', $evaluacion->id_prestacion)
            ->where('estado', 1)
            ->lists('id_evento')
            ->toArray();
        endif;
        $pdf = \PDF::loadView('pdf.firmar_evaluacion',compact('evaluacion','diagnostico','procedimiento','codigo_qr','url_descarga','id_evaluacion', 'cups_pnp','emergencia_triaje','natis','eventosMarcados'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('firmar_evaluacion.pdf');
    }
    
    public function ver_evolucion_firmar($id_evolucion){
        $evolucion = DB::table('evaluaciones as e')
            ->select('e.*', 'm.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura', 'h.paterno_beneficiario as paciente_paterno', 'h.materno_beneficiario as paciente_materno', 'h.nombre_beneficiario as paciente_nombre','ah.nro_historia','camas.nombre as cama','consultorios.nombre as servicio_sala','p.nombre as profesional_profesion')
            ->join('hospitalizaciones as h', function ($join) {
                $join->on('e.id_emergencia', '=', 'h.id')
                    ->where('e.id_tipo_atencion', '=', 3);
            })
            ->join('medicos as m', 'e.id_medico', '=', 'm.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->leftJoin('asegurado_historias as ah', function ($join) {
                $join->on('h.id_asegurado', '=', 'ah.id_asegurado')
                    ->on('h.id_establecimiento', '=', 'ah.id_establecimiento');
            })
            ->join('cambio_camas', function ($join) {
                $join->on('e.id_prestacion', '=', 'cambio_camas.id_prestacion')
                     ->on('cambio_camas.id_evolucion', '=', 'e.id');
            })
            ->join('camas','cambio_camas.id_cama_atencion','=','camas.id')
            ->join('cuartos','camas.id_cuarto','=','cuartos.id')
            ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
            ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')
            ->where('e.id', $id_evolucion)
            ->first();

        $recetas = DB::table('receta_vales')
            ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
            ->select('receta_vales.*', 'farmacias.nombre as farmacia')
            ->where('receta_vales.id_evaluacion', '=', $id_evolucion)
            ->where('receta_vales.estado', '<>', 0)
            ->orderBy('receta_vales.id', 'asc')
            ->get();

        $receta_productos = DB::table('receta_vales')
            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
            ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
            ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
            ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
            ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
            ->select(
                'producto_recetas.id as id_producto_receta',
                'producto_recetas.id_receta_vale as id_receta',
                'productos.codigo',
                'productos.nombre',
                'productos.id as id_producto',
                'productos.presentacion_comercial as presentacion',
                'producto_recetas.*',
                'unidades.abreviatura',
                'farmacias.nombre as farmacia',
                'via_administracion.descripcion as via'
            )
            ->where('receta_vales.id_evaluacion', $id_evolucion)
            ->orderBy('producto_recetas.id', 'ASC')
            ->get();
            
        $ordenes_laboratorio = DB::table('laboratorios')
            ->where('id_evaluacion', '=', $id_evolucion)
            ->where('estado', '<>', 0)
            ->orderBy('id', 'ASC')
            ->get();

        $orden_lab_examenes = DB::table('prestacion_procedimientos as t1')
            ->join('labnivelatenciones as t2', 't1.id_rua', '=', 't2.id')
            ->join('procedimientos as p', 't1.id_procedimiento', '=', 'p.id')
            ->leftJoin('diagnosticos as d', 'd.id', '=', 't1.id_diagnostico')
            ->where('t1.id_evaluacion', $id_evolucion)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo_lab as codigo',
                't2.descripcion_corta as nombre',
                'p.descripcion as nombre_completo',
                't1.cantidad',
                't1.created_at',
                't2.id_procedimiento',
                't1.observacion',
                'd.codigo as codigo_diagnostico',
                'd.nombre as diagnostico'
            )
            ->orderBy('t1.id', 'ASC')
            ->get();

        $ordenes_imagenologia = DB::table('radiologias')
            ->where('id_evaluacion', $id_evolucion)
            ->where('estado', '<>', 0)
            ->orderBy('id', 'ASC')
            ->get();

        $orden_img_examenes = DB::table('prestacion_procedimientos as t1')
            ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
            ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
            ->where('t1.id_evaluacion', $id_evolucion)
            ->where('t1.tipo_procedimiento', 3)
            ->where('t1.id_estado_reg', 1)
            ->select(
                't1.id',
                't1.id_lab_rad as id_orden',
                't2.codigo',
                't2.descripcion as nombre',
                't1.cantidad',
                't1.observacion',
                't3.nombre as diagnostico',
                't3.codigo as cod_diag',
                't1.comparativo_radio',
                't1.lado_radio'
            )
            ->orderBy('t1.id', 'asc')
            ->get();
        
        $interconsultas = DB::table('interconsultas as t8')
            ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
            ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
            ->leftJoin('medicos as t5', 't8.personal_salud_interconsulta', '=', 't5.id')
            ->where('t8.id_evaluacion', $id_evolucion)
            ->where('t8.id_estado_reg', 1)
            ->select(
                't8.id',
                't3.nombre as establecimiento',
                't6.nombre as servicio',
                't5.nombre as profesional_nombre',
                't5.paterno as profesional_paterno',
                't5.materno as profesional_materno',
                't8.motivo_interconsulta',
                't8.codigo_upss as codigo_interconsulta',
                't5.colegiatura as profesional_colegiatura'
            )
            ->orderBy('t8.id')
            ->get();
        
        $diagnosticos = DB::table('receta_diagnosticos as rd')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->select('rd.*', 'd.codigo as cod_dx', 'd.nombre as nombre_dx')
            ->where('rd.id_evaluacion', $id_evolucion)
            ->where('rd.id_clasificacion_diagnosticos', '<>', 9)
            ->where('rd.estado', 1)
            ->orderBy('rd.id')
            ->orderBy('rd.id_categoria')
            ->get();

        $procedimientos = DB::table('prestacion_procedimientos as pp')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->leftJoin('procedimientos_diagnoticos as prd', 'pp.id_evaluacion', '=', 'prd.id_evaluacion')
            ->select('pp.id', 'pp.cantidad', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms', 'prd.tiempo')
            ->where('pp.id_evaluacion', $id_evolucion)
            ->where('pp.tipo_procedimiento', 1)
            ->where('pp.id_estado_reg', 1)
            ->orderBy('pp.id')
            ->get();

        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_evolucion_".$id_evolucion.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_evolucion_".$id_evolucion.".pdf");
        $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::select('ha.*', 
            'p.fecha_parto', 
            'p.id_tipo_parto as puerperio_id_tipo_parto', 
            'tp.nombre as puerperio_tipo_parto', 
            'p.complicaciones as puerperio_complicaciones', 
            'p.descripcion as puerperio_descripcion')
        ->from('hospitalizacion_atenciones as ha')
        ->leftJoin('puerperios as p', 'ha.id_puerperio', '=', 'p.id')
        ->leftJoin('tipo_partos as tp', 'p.id_tipo_parto', '=', 'tp.id')
        ->where('ha.id_prestacion', '=', $evolucion->id_prestacion)
        ->first();
        $eventosMarcados = Prestacion_eventos::where('id_prestacion', $evolucion->id_prestacion)
        ->where('estado', 1)
        ->lists('id_evento')
        ->toArray();
        $gestacion = Gestacion::where("id", $obj_hospitalizacion_atencion->id_gestacion)->first();
        $puerperio = Puerperio::where("id", $obj_hospitalizacion_atencion->id_puerperio)->first();

        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        $nivel_establecimiento = $establecimiento[0]->nivel;

        $pdf = \PDF::loadView('pdf.firmar_evolucion_hospitalizacion',compact('evolucion','diagnosticos','procedimientos','obj_hospitalizacion_atencion','eventosMarcados','gestacion','puerperio','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','interconsultas','codigo_qr','url_descarga','id_evolucion','nombre_establecimiento', 'nivel_establecimiento'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        
        return $pdf->stream('firmar_evolucion_hospitalizacion.pdf');
    }

    public function ver_alta_hospitalizacion($id_hospitalizacion){
        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion_alta = $model_hospitalizacion->getHospitalizacionById($id_hospitalizacion);
        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion_alta->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();
        
        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();
                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }

        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        $pdf = \PDF::loadView('pdf.firmar_alta_hospitalizacion',
                [
                    'asegurado' => $asegurado,
                    'grado' => $grado,
                    'hospitalizacion'=> $hospitalizacion_alta,
                    'persona_datos_adicionales' => $persona_datos_adicionales,
                    'titular' => $titular,
                    'nombre_establecimiento' => $nombre_establecimiento,
                ]
        );
        $pdf->getDomPDF()->set_option("enable_php", true);
        
        return $pdf->stream('firmar_alta_hospitalizacion.pdf');
    }


    public function print_hospitalizacion_papeleta_egreso_atencion_quirurgica_no_tratado($id_hospitalizacion,$id_prestacion_programacion){

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_prestacion = new \App\Models\Prestaciones;

        $model_hospitalizacion = new Hospitalizacione();
        $hospitalizacion = $model_hospitalizacion->getDatosPapeletaEgresoAtencionQuirurgicaNoTratado($id_hospitalizacion);
 
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($id_prestacion_programacion);

        if(!is_object($hospitalizacion)){
            Session::flash('flash_warning', 'El paciente aún no se le ha dado de alta.');
            return redirect('/dashboard');
        }

        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

   
        $user_actual = Auth::user();
            
        $asegurado = DB::table('asegurados')
            ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
            ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
            ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
            ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
            ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
            ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
            ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
            ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
            ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
            ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
            ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
            ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
            ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
            ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
            ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
            ->where('asegurados.id',$hospitalizacion->id_asegurado)
            ->where('asegurados.id_estado_reg', 1)
            ->first();

        $titular = null;
        $persona_datos_adicionales = null;
        $grado = null;

        if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
            $titular = DB::table('asegurados')
                ->select('asegurados.*')
                ->where('id',$asegurado->id_asegurado_titular)
                ->where('id_estado_reg', 1)
                ->first();
            
            if ($titular != null) {
                $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                    ->select('persona_datos_adicionales.*')
                    ->where('id_asegurado',$titular->id)
                    ->first();
                if ($persona_datos_adicionales != null) {
                    $grado = DB::table('titular_grados')
                    ->select('titular_grados.*')
                    ->where('id',$persona_datos_adicionales->id_grado)
                    ->first();
                }
            }
        }
        $concurrencias = DB::table('hospitalizacion_concurrencias as hc')
                ->join('consultorios as c', 'c.id', '=', 'hc.id_servicio')
                ->select('hc.id', 'hc.id_hospitalizacion', 'hc.id_servicio', 'c.nombre')
                ->where('hc.id_hospitalizacion', $id_hospitalizacion)
                ->get();

        
        $pdf = \PDF::loadView('pdf.hospitalizacion_papeleta_egreso_atencion_quirurgica_no_tratado',
                [
                    'asegurado' => $asegurado,
                    'concurrencias' => $concurrencias,
                    'fechaActual' => $fechaActual,
                    'grado' => $grado,
                    'horaActual' => $horaActual,
                    'hospitalizacion'=> $hospitalizacion,
                    'persona_datos_adicionales' => $persona_datos_adicionales,
                    'titular' => $titular,
                    'user_actual' => $user_actual,
                    'diagnostico'=>$diagnostico
                ]
        );

        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("papeleta_egreso_atencion_quirurgica_no_tratado_$id_hospitalizacion.pdf");
    }
}

