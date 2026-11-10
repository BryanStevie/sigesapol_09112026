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
use App\Models\Farmacia;
use App\Models\Receta_vale;
use App\Models\Cita;
use App\Models\Prestaciones;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\Kardexe;
use SoapClient;

class GestionCitaController extends Controller
{ 

    function __construct()
    {
        $this->middleware('auth'); 
    }
	
	public function modal_asignar_consultorio(){
		
		$id_user = Auth::user()->id;
		$model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
		$model_consultorio = new Consultorio;
		
		//$consultorio = $model_consultorio->ShowAllConsultorios($farmacia->id_establecimiento);
		$consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
		//$historia = \App\Models\Asegurado_historia::find($id);
		//$asegurado = \App\Models\Asegurado::find($historia->id_asegurado);
		return view('frontend.citas.modal_asignar_consultorio',compact('consultorio')/*,compact('medicos','horario','fecha_atencion','id_ipress','id_consultorio')*/);
	}
	
	public function asignar_cita(Request $request){
		
		foreach ($request->id_cita as $key => $value):
			$cita = Cita::where('id', '=', $value)->first();
			$cita->id_sub_consultorio = $request->id_consultorio;
			$cita->id_medico = $request->id_medico;
			$cita->save();
		endforeach;
		
	}
	
	
	
	
	public function obtener_consultorio_cita($id_establecimiento,$tipodoc_beneficiario,$dni_beneficiario) {
	
		$model_cita = new Cita();
        $consultorio = $model_cita->GetServicioCitaByAseguradoAndEstablecimiento($tipodoc_beneficiario,$dni_beneficiario,$id_establecimiento);
        return $consultorio;
    }
	
	public function obtener_consultorio_cita_general($id_establecimiento,$tipodoc_beneficiario,$dni_beneficiario) {
	
		$model_cita = new Cita();
        $consultorio = $model_cita->GetServicioCitaGeneralByAseguradoAndEstablecimiento($tipodoc_beneficiario,$dni_beneficiario,$id_establecimiento);
        return $consultorio;
    }
	
	public function anular_cita($id_cita) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cita = Cita::where('id', $id_cita)->first(); 
		$cita->id_estado_reg = 0;
		$cita->id_estado_cita = 5;
        $cita->save();
		
        $array["sw"] = true;
        echo json_encode($array);
		
    }
	
	public function anular_prestacion($id_prestacion) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $prestacion = Prestaciones::where('id', $id_prestacion)->first(); 
		$prestacion->id_estado_reg = 0;
        $prestacion->save();
		
		$cita = Cita::where('id', $prestacion->id_cita)->first(); 
		$cita->id_estado_reg = 0;
		$cita->id_estado_cita = 5;
        $cita->save();
		
		$recetas = Receta_vale::where('id_prestacion', '=', $id_prestacion)->where('estado', '<>', 0)->get();
		foreach ($recetas as $rv) {
            $rv->estado = 0;
            $rv->save();
        }
		
        $array["sw"] = true;
        echo json_encode($array);
		
    }
	
	public function update_historia(Request $request) {
        //$usuario = new User;
		$historia = \App\Models\Asegurado_historia::find($request->id);
        //$historia = new \App\Models\Asegurado_historia;
        //$historia->id_asegurado = $request->id_asegurado;
		//$historia->id_establecimiento = 76;
        $historia->nro_historia = $request->nro_historia;
		//$historia->id_estado_reg = 1;
		$historia->updated_at = Carbon::now();
		$historia->updated_us = $request->id_user;
        $historia->save();

        return redirect('/all_citas_medico_paciente')->withFlashSuccess("Se actualizo correctamente la Historia");
    }
	
	public function formulario_gestion_cita(Request $request) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
		$id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
		
        $model_kardex = new Kardexe();
		$model_usuario = new User;
		
        $fechaServidor = $model_kardex->getFechaServidor();
		$user = $model_usuario->GetById($id_user);
		$flagemergenciareferencia = $user->flagemergenciareferencia;		

		return view('frontend.gestion_citas.form_cita', compact('fechaServidor','flagemergenciareferencia'));
		
	}

	public function mostrar_cita_medico($id_ipress,$id_consultorio,$fecha_atencion="",$id_turno="",$id_medico=""){
		
		$medico_turno = new \App\Models\Medico_turno;
		$model_consultorio = new Consultorio;
		$model_kardex = new Kardexe();	
		
        

        $fechaServidor = $model_kardex->getFechaServidor();
		
		$consultorio = $model_consultorio->GetNombreById($id_consultorio);
		//$id_ipress="75";
		//$id_consultorio="105";
		//$fecha_atencion="24-10-2019";
		$turno = $medico_turno->GetTurnos();
		//aqui muestro medicos
		$medicos = $medico_turno->GetMedicoByIdIpressAndIdConsultorio($id_ipress,$id_consultorio,$id_medico,$id_turno,$fecha_atencion);		
		//$medicos = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndTurno($id_ipress,$id_consultorio,$id_turno,$fecha_atencion);	
		//dd($medicos);	
		//aqui muestro consultorios
		//$medicos = $medico_turno->GetSubConsultorioByIdConsultorio($id_consultorio,$id_ipress);
		//$horario = $medico_turno->GetHorarioMedicoByIdIpressAndIdConsultorio($id_ipress,$id_consultorio,$fecha_atencion);
		//dd($medicos);
		//return view('frontend.citas.modal_cita_medico',compact('medicos','fecha_atencion','id_ipress','id_consultorio','turno','consultorio'));
		return view('frontend.gestion_citas.modal_cita_sub_consultorio',compact('medicos','fecha_atencion','id_ipress','id_consultorio','turno','consultorio','fechaServidor'));
	
	}
	
	public function mostrar_turnos($id_ipress,$id_consultorio,$fecha_atencion){
		
		$medico_turno = new \App\Models\Medico_turno;
		$model_consultorio = new Consultorio;
		//$horario = $medico_turno->GetHorarioTurno($id_ipress,$id_consultorio,$fecha_atencion);
		$turno = $medico_turno->GetTurnos();
		$consultorio = $model_consultorio->GetNombreById($id_consultorio);
		//print_r($horario);
		return view('frontend.gestion_citas.modal_turno',compact('medicos','turno','fecha_atencion','id_ipress','id_consultorio','consultorio'));
	
	}
	
	public function mostrar_historial_cita($dni_beneficiario){
		
		$medico_turno = new \App\Models\Medico_turno;
		$horario = $medico_turno->GetCitasByDni($dni_beneficiario);
		return view('frontend.gestion_citas.modal_historial',compact('horario')/*,compact('medicos','horario','fecha_atencion','id_ipress','id_consultorio')*/);
	
	}
	
	public function mostrar_historia($id){
		
		//$medico_turno = new \App\Models\Medico_turno;
		//$horario = $medico_turno->GetCitasByDni($dni_beneficiario);
		$historia = \App\Models\Asegurado_historia::find($id);
		$asegurado = \App\Models\Asegurado::find($historia->id_asegurado);
		return view('frontend.gestion_citas.modal_historia',compact('id','asegurado')/*,compact('medicos','horario','fecha_atencion','id_ipress','id_consultorio')*/);
	
	}	

	public function registrar_gestion_cita(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_cita = new \App\Models\Cita;
		$id_cita = 0;
		//echo date("Y-m-d", strtotime($request->fecha_cita));exit();
		$id_medico = 0;
		$id_cita = $model_cita->registrarCita($id_cita, $request->id_establecimiento, $request->id_consultorio/*id_servicio*/, /*$id_medico*/ NULL, $request->id_user, $request->fecha_cita,$request->tipodoc_beneficiario, $request->nrodocafiliado, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $request->nro_historia, $request->grado,$request->fecnacafiliado,$request->nomsexo,$request->cip_beneficiario,$request->tipodoc_titular,$request->nrodoctitular,$request->nombre_titular,$request->paterno_titular,$request->materno_titular, NULL, 1, 1,$request->domicilio,$request->celular,$request->correo);
		
		$medico_turno = new \App\Models\Medico_turno;
		$fecha_atencion = date("Y-m-d", strtotime($request->fecha_cita));
		$medico_turno->registrarMedicoCita($fecha_atencion,$request->id_consultorio);
		
		/*if($id_cita > 0){
			//$cita = $model_cita->GetCitasById($id_cita);
			$cita = $model_cita->GetCitasConsultorioById($id_cita);
			if($cita->correo != ""){
				view('emails.mensaje');
				Mail::send('emails.mensaje', ['cita' => $cita], function ($m) use ($cita) {
					$m->from(env('MAIL_USERNAME'), 'SALUDPOL');
					$m->to($cita->correo)->subject('SALUDPOL: Cita Programada');
				});
			}
		}
		*/
		echo $id_cita;
    }

    public function ver_cita_registrada($id_cita) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        //--------------------------------------------
		$model_cita = new \App\Models\Cita;
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');        
		$cita = $model_cita->GetCitasConsultorioById($id_cita);

        return view('frontend.gestion_citas.ver_cita', compact('cita'));
    }

    public function ver_modal_cita_registrada($id_cita) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        //--------------------------------------------
		$model_cita = new \App\Models\Cita;
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');        
		$cita = $model_cita->GetCitasConsultorioById($id_cita);

        return view('frontend.gestion_citas.modal_ver_cita', compact('cita'));
    }

	public function cronograma_gestion_cita($id_ipress,$id_consultorio){
		
		$medico_turno = new \App\Models\Medico_turno;
		$model_consultorio = new Consultorio;
		
		$id_ipress="33";
		$id_consultorio="8";
		$consultorio = $model_consultorio->GetNombreById($id_consultorio);
		
		$cronograma = $medico_turno->GetCalendarioByIdIpressAndIdConsultorio($id_ipress,$id_consultorio,"",$consultorio->dura_cons);
		
		dd($cronograma);
		$data = array();
		foreach($cronograma as $key=>$row):
			$datos = array();
			$datos["end"] = $row->end;
			$datos["start"] = $row->start;
			$datos["nro_citas"] = $row->nro_citas;
			$datos["nro_programado"] = $row->nro_programado;
			$disponible = $row->nro_programado - $row->nro_citas;
			$datos["title"] = $disponible." Disponbiles \n ".$row->nro_citas." Programado";
			//$datos["title"] = $disponible." Disponbile";
			$datos["display"] = 'background';			
			$datos["color"] = "#d4edda";
			$data[$key] = $datos;
		endforeach;
		
		foreach($cronograma as $row):
			$key++;
			$datos["end"] = $row->end;
			$datos["start"] = $row->start;
			$datos["nro_citas"] = $row->nro_citas;
			$datos["nro_programado"] = $row->nro_programado;
			$disponible = $row->nro_programado - $row->nro_citas;
			$datos["title"] = $row->nro_citas." Programado";
			$datos["display"] = 'background';
			$datos["color"] = "#fff3cd";
			$data[$key] = $datos;
		endforeach;
		
		dd($data);
		echo json_encode($data);
		//return view('frontend.citas.modal_cita_medico',compact('medicos','horario','fecha_atencion','id_ipress','id_consultorio'));
		
	
	}

	public function cronograma_gestion_cita_medico($id_ipress,$id_consultorio,$id_medico){
		
		$medico_turno = new \App\Models\Medico_turno;
		$model_consultorio = new Consultorio;
		//$id_ipress="75";
		//$id_consultorio="105";
		$consultorio = $model_consultorio->GetNombreById($id_consultorio);
		$cronograma = $medico_turno->GetCalendarioByIdIpressAndIdConsultorio($id_ipress,$id_consultorio,"",$consultorio->dura_cons);
		
		$data = array();
		foreach($cronograma as $key=>$row):
			$datos = array();
			$datos["end"] = $row->end;
			$datos["start"] = $row->start;
			$datos["nro_citas"] = $row->nro_citas;
			$datos["nro_programado"] = $row->nro_programado;
			$disponible = $row->nro_programado - $row->nro_citas;
			//$datos["title"] = $disponible." Disponbiles \n ".$row->nro_citas." Programado";
			$datos["title"] = $disponible." Disponbile";
			$datos["color"] = "#d4edda";
			$data[$key] = $datos;
		endforeach;
		
		foreach($cronograma as $row):
			$key++;
			$datos["end"] = $row->end;
			$datos["start"] = $row->start;
			$datos["nro_citas"] = $row->nro_citas;
			$datos["nro_programado"] = $row->nro_programado;
			$disponible = $row->nro_programado - $row->nro_citas;
			$datos["title"] = $row->nro_citas." Programado";
			$datos["color"] = "#fff3cd";
			$data[$key] = $datos;
		endforeach;
		
		echo json_encode($data);
		//return view('frontend.citas.modal_cita_medico',compact('medicos','horario','fecha_atencion','id_ipress','id_consultorio'));
	
	}
	
    public function buscar_obtener_beneficiario($tipodoc_beneficiario, $dni_beneficiario, $id_establecimiento, $id_servicio) {
		
		$array = array();
		$id_farmacia = Auth::user()->id_farmacia;
		$numero_historia = "";
		$domicilio = "";
		$celular = "";
		$beneficiario = "";
		$msg = "";
		$sw = false;
		$establecimiento = "";
		$asegurado = "";
		$cita = "";
		
		/*
		if (App::environment('desarrollo')):
			$client = new SoapClient("http://wsdl.saludpol.gob.pe:8083/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => 'D5p-R4v-@cci.ws.sp', 'password' => "wS5p#cC1#D5p-"));
		elseif (App::environment('local')):
			$client = new SoapClient("http://localhost:8083/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => 'D5p-R4v-@cci.ws.sp', 'password' => "wS5p#cC1#D5p-"));
		endif;
		
		*/

		$client = new SoapClient("http://wsdl.saludpol.gob.pe:8083/ws/WS_AfiliadoSP_Serv.php?wsdl", array(.login. => .D5p-R4v-@cci.ws.sp., .password. => "wS5p#cC1#D5p-"));
		$beneficiario = $client->getAseguradoValidate($tipodoc_beneficiario, $dni_beneficiario);
		foreach($beneficiario as $key=>$ben)if(!is_object($ben))$beneficiario->$key = utf8_decode($ben);
		
		$model_establecimiento = new \App\Models\Establecimiento;
    	if(isset($beneficiario->ubigeo))$establecimiento = $model_establecimiento->GetEstablecimientoUbigeosByUbigeo($beneficiario->ubigeo);
		
		if (is_object($beneficiario)){
			$sw = true;
			$existe_beneficiario = Receta_vale::where('dni_beneficiario', $dni_beneficiario)->where('id_farmacia', $id_farmacia)->get();
			
			if (count($existe_beneficiario) > 0){
				$numero_historia = $existe_beneficiario[0]->nro_historia;
			}		
					
			$cad = "select ah.nro_historia, a.domicilio, a.celular
	                from asegurados a
	                inner join asegurado_historias ah on ah.id_asegurado = a.id
	                where ah.id_establecimiento=".$id_establecimiento." and a.nro_doc_ident ='".$dni_beneficiario."'";
        	
        	$asegurado = DB::select($cad);

        	

            if (count($asegurado) > 0){
				if($numero_historia==""){
					$numero_historia = $asegurado[0]->nro_historia;
				}
				$domicilio=$asegurado[0]->domicilio;
				$celular=$asegurado[0]->celular;
			}			
		}
		else
		{
			$msg = "No se encuentra afiliado..!";
		}
		
		
		$model_cita = new \App\Models\Cita;
    	$cita = $model_cita->BuscaCitaByAseguradoAndEstablecimiento($tipodoc_beneficiario, $dni_beneficiario,$id_establecimiento, $id_servicio);		
			
		$array["sw"] = $sw;
		$array["msg"] = $msg;
		$array["beneficiario"] = $beneficiario;
		$array["domicilio"] = $domicilio;
		$array["celular"] = $celular;
		$array["numero_historia"] = $numero_historia;
		$array["establecimiento"] = $establecimiento;
		$array["cita"] = $cita;

        echo json_encode($array);
    }

    public function buscar_cita_beneficiario($fecha_cita, $dni_beneficiario, $id_establecimiento, $id_servicio) {
		
		$array = array();
		$id_farmacia = Auth::user()->id_farmacia;		
		$msg1 = "";
		$msg2 = "";
		$sw1 = false;
		$sw2 = false;		
		$cita_mes = "";
		$cita_servicio = "";
		
		$model_cita = new \App\Models\Cita;
    	$cita_servicio = $model_cita->BuscaCitaByAseguradoAndEstablecimiento(1, $dni_beneficiario,$id_establecimiento, $id_servicio,$fecha_cita);
    	$cita_mes = $model_cita->BuscaCitaByAseguradoAndEstablecimiento(1, $dni_beneficiario,$id_establecimiento, 0, $fecha_cita);
    	$nro_cita_mes=count($cita_mes);
    	
    	if (is_object($cita_servicio)):
    		$sw1 = false;
    		$msg1 = "Ya ha sido programado con una cita este mes en este mismo servicio, seleccione otro mes..!";
    	else:
			$sw1 = true;	
		endif;

		if (is_object($cita_mes)):
    		$sw2 = false;
    		$msg2 = "Ya tiene 5 citas programadas este mes, seleccione otro mes..!";
    	else:
			$sw2 = true;	
		endif;

		$array["sw1"] = $sw1;
		$array["sw2"] = $sw2;
		$array["msg1"] = $msg1;
		$array["msg2"] = $msg2;		
		$array["nro_cita_mes"] = $nro_cita_mes;		
		$array["cita"] = $cita_servicio;
        echo json_encode($array);
    }

    public function all_citas_de_paciente($id_user = 0) {
        $model_medico = new \App\Models\Medico();
        $model_usuario = new User;
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
		if($ruta=="listar_citas_de_paciente");$ruta="all_citas_de_paciente";
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        
        $id_user = Auth::user()->id;
        $usuario_actual = $model_usuario->GetById($id_user);
		$flagemergenciareferencia = $usuario_actual->flaghistoria;
		$id_consultorio_actual = $usuario_actual->id_consultorio;
		
        $dni_beneficiario = "";
        $nombre_beneficiario = "";
        $estado = "1";

        $date = Carbon::now();
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');

        //====================
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        $id_establecimiento = $farmacia->id_establecimiento;

		
		$medico = 0;
		if($flagemergenciareferencia!=1){
			$medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni,$farmacia->id_establecimiento);
			$medico = $medico_actual->id;
		}
     	
		$model_consultorio = new Consultorio;		
		
		$consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

		//$model_citas = new Cita();

        //$citas = $model_citas->AllCitaConfirmaPacienteFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
        
        //$cantidad = $model_citas->GetCountCitaConfirmaPacienteFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
		
        return view('frontend.gestion_citas.all_citas_de_paciente', compact(/*'citas','cantidad',*/ 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia','consultorio','medico','id_consultorio_actual','id_establecimiento'));
    }
	
    public function listar_citas_de_paciente(Request $request) {
        
    	

        $model_medico = new \App\Models\Medico();
        $model_usuario = new User;

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_citas_de_paciente');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        $id_establecimiento = $farmacia->id_establecimiento;
        //----------------------------------------------------------------------
        $id_user = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nombre_beneficiario = strtoupper($request->nombre_beneficiario);
        
        $estado = $request->estado;

        $usuario_actual = $model_usuario->GetById($id_user);
		$flagemergenciareferencia = $usuario_actual->flaghistoria;
        
		$medico = 0;
		if($flagemergenciareferencia!=1){
			$medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni,$farmacia->id_establecimiento);
			$medico = $medico_actual->id;
		}
		
        
        $model_citas = new Cita();

        $citas = $model_citas->AllCitaConfirmaPacienteFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
        
        $cantidad = $model_citas->GetCountCitaConfirmaPacienteFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
        //$cantidad = '1';

        return view('frontend.gestion_citas.all_citas_de_paciente', compact('citas', 'cantidad', 'farmacia', 'fechaDesde',  'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia','id_establecimiento'));
    }
		
public function listar_citas_ajax(Request $request){

		$model_citas = new Cita();
		$p[]=$request->fecha_ini;
		$p[]=$request->fecha_fin;
		$p[]=$request->dni_beneficiario;
		$p[]=$request->nombre_beneficiario;
		$p[]=$request->id_servicio;
		$p[]=$request->id_consultorio;
		$p[]=$request->id_medico;
		$p[]=$request->id_establecimiento;
		//$p[]=0;
		//$p[]=0;
		//$p[]=0;
		$p[]=$request->estado;
		$p[]=$request->NumeroPagina;
		$p[]=$request->NumeroRegistros;
		$data = $model_citas->listar_citas_ajax($p);
		$iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;
		//print_r($afiliacion);exit();
		
		$result["PageStart"] = $request->NumeroPagina;
		$result["pageSize"] = $request->NumeroRegistros;
		$result["SearchText"] = "";
		$result["ShowChildren"] = true;
		$result["iTotalRecords"] = $iTotalDisplayRecords;
		$result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
		$result["aaData"] = $data;
		
		echo json_encode($result);
		
	}

	public function all_cita_por_farmacia($id_user = 0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $id_user = Auth::user()->id;

        $model_kardex = new Kardexe();
        $fechaDesde = $model_kardex->getFechaServidor();
        $fechaHasta = $fechaDesde . " 23:59";
        $fechaDesde = $fechaDesde . " 00:00";
        //==================== 
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        //====================
        $model_recetas = new Receta_vale();
        $recetas = $model_recetas->AllCitaByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta);
        $cantidad = $model_recetas->GetCountCitaByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta);

        //====================
        return view('frontend.gestion_citas.all_cita_by_farmacia', compact('recetas', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta'));
    }

    public function modal_nro_historial_cita($dni_beneficiario){
		
		$medico_turno = new \App\Models\Medico_turno;
		$horario = $medico_turno->GetCitasByDni($dni_beneficiario);
		return view('frontend.gestion_citas.modal_historial',compact('horario'));
	
	}
	
	public function modal_nro_historia($id){
		
		$historia = \App\Models\Asegurado_historia::find($id);
		$asegurado = \App\Models\Asegurado::find($historia->id_asegurado);
		return view('frontend.gestion_citas.modal_historia',compact('id','asegurado'));
	
	}

	public function update_nro_historia(Request $request) {
        
		$historia = \App\Models\Asegurado_historia::find($request->id);
        $historia->nro_historia = $request->nro_historia;
		$historia->updated_at = Carbon::now();
		$historia->updated_us = $request->id_user;
        $historia->save();

        return redirect('/all_citas_de_paciente')->withFlashSuccess("Se actualizo correctamente la Historia");
    }
	
	public function anular_nro_cita($id_cita) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cita = Cita::where('id', $id_cita)->first(); 
		$cita->id_estado_reg = 0;
		$cita->id_estado_cita = 5;
        $cita->save();
		
        $array["sw"] = true;
        echo json_encode($array);
		
    }
	
	

}


