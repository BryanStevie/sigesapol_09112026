<?php

namespace App\Http\Controllers;

use App\Models\Hospitalizacione;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Establecimiento;
use App\Models\Paciente;
use App\Models\Asegurado_historia;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Persona_datos_adicional;
use App\Models\Titular_categoria;
use App\Models\Titular_grado;
use App\Models\Tipo_sangre;
use App\Models\Situacion;
use App\Models\Unidadpago;
use App\Models\User;
use Exception;
use PhpParser\Node\Stmt\Catch_;
use SoapClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Session;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebServiceController;
use App\Models\Antecedente;
use App\Models\Asegurado;
use App\Models\Tipo_antecedente;
use App\Models\Historia;
use Carbon\Carbon;
use App\Models\Kardexe;
use App\Models\MotivoTransferencia;
use App\Models\Consultorio;
use App\Models\Medico;
use App\Models\Tipo_parentescos;
use App\Models\Tipo_documento_identidad;
use App\Models\Tutor;
use App\Ws\Saludpol;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use stdClass;

include('funciones/function.php');

class PacienteController extends Controller
{

	function __construct()
	{
		$this->middleware('auth');
	}

	public function index($id_user = 0)
	{

		ini_set('memory_limit', '1280M');
		ini_set('max_execution_time', 300); // 5 minutes

		if (!Auth::check()) :
			return redirect('/auth/login');
		else:
			if (Auth::user()->actualizo_passw == 0):
				return redirect('auth/password/change');
			endif;
		endif;

		$id_user = Auth::user()->id;
		$model = new User;

		$modelpaciente = new Paciente();
		$pacientes = $modelpaciente->ShowAllPacientes();

		$ruta = Route::getCurrentRoute()->getPath();
		if (count($ruta = explode("/", $ruta)) > 1);
		$ruta = $ruta[0];
		$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
		$permiso = $menu[0];

		$model_tipo_documento_identidad = new Tipo_documento_identidad();
		$tipo_documento_identidad = $model_tipo_documento_identidad->ShowTipoDocumentoIdentidad();

		$model_beneficiario = new \App\Models\Tipo_beneficiario;
		$tipo_beneficiario = $model_beneficiario->ShowTipo_beneficiario();

		$model_tipo_sangre = new \App\Models\Tipo_sangre();
		$tipo_sangre = $model_tipo_sangre->ShowAll();

		$model_departamento = new \App\Models\Departamento();
		$departamento = $model_departamento->ShowAll();

		$provincia = null;
		if (isset($personal) && $personal->id_departamento != null) {
			$model_provincia = new \App\Models\Provincia();
			$provincia = $model_provincia->ShowAllByIdDepartamento($personal->id_departamento);
		}

		$distrito = null;
		if (isset($personal) && $personal->id_provincia != null) {
			$model_distrito = new \App\Models\Distrito();
			$distrito = $model_distrito->ShowDistrito($personal->id_provincia);
		}

		$provincia_nac = null;
		if (isset($personal) && $personal->id_departamento_nacimiento != null){
			$model_provincia = new \App\Models\Provincia();
			$provincia_nac = $model_provincia->ShowAllByIdDepartamento($personal->id_departamento_nacimiento);
		}

		$distrito_nac = null;
		if (isset($personal) && $personal->id_provincia_nacimiento != null){
			$model_distrito = new \App\Models\Distrito();
			$distrito_nac = $model_distrito->ShowDistrito($personal->id_provincia_nacimiento);
		}

		$model_grado = new \App\Models\Grado_instruccion();
		$grado_inst = $model_grado->ShowGradoInstruccion();

		$model_ocupacion = new \App\Models\Ocupacion();
		$ocupacion = $model_ocupacion->ShowOcupacion();

		$model_estadocivil = new \App\Models\Estado_civil();
		$estado_civil = $model_estadocivil->ShowEstadoCivil();

		$model_religion = new \App\Models\Religion();
		$religion = $model_religion->ShowReligion();

		$model_etnia = new \App\Models\Etnia();
		$etnia = $model_etnia->ShowEtnia();

		$model_tipo_parentescos = new \App\Models\Tipo_parentescos();
		$parentesco = $model_tipo_parentescos->getTipo_parentescos();

		// $grados_lista = DB::table('asegurados')->select('id_grado as id', 'grado')->distinct()->orderBy('grado', 'asc')->get();
		$grados_lista = DB::table('asegurados')->select('grado')->whereNotNull('grado')->whereRaw("TRIM(grado) <> ''")->where('grado', '<>', '-')->distinct()->orderBy('grado', 'asc')->get();

		// $situaciones_lista = DB::table('asegurados')->select('id_situacion as id', 'situacion_paciente')->distinct()->orderBy('situacion_paciente', 'asc')->get();
		$situaciones_lista = DB::table('asegurados')->select('situacion_paciente')->whereNotNull('situacion_paciente')->whereRaw("TRIM(situacion_paciente) <> ''")->where('situacion_paciente', '<>', '-')
					       ->distinct()->orderBy('situacion_paciente', 'asc')->get();

		$establecimientotot = $model->GetById($id_user);
		$id_establecimiento = $establecimientotot->id_establecimiento;

		$modelpaciente = new Paciente();
		$id_ipress = $id_establecimiento;

		$modelIpress = new Establecimiento();
		$establecimiento = $modelIpress->GetById($id_ipress);

		if ($permiso->lectura == "")
			return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

		$is_personal_unitic = Auth::user()->flag_rrhh == 1;

			return view('frontend.pacientes.all2',compact('is_personal_unitic','pacientes','permiso','tipo_documento_identidad','tipo_beneficiario','tipo_sangre','departamento','provincia','distrito','grado_inst','ocupacion','estado_civil','religion','etnia','parentesco','grados_lista','situaciones_lista','establecimiento','provincia_nac','distrito_nac'));
			//return view('frontend.pacientes.all',compact('pacientes','permiso','tipo_documento_identidad','tipo_beneficiario','tipo_sangre','departamento','provincia','distrito','grado_inst','ocupacion','estado_civil','religion','etnia','parentesco','grados_lista','situaciones_lista','establecimiento','provincia_nac','distrito_nac'));
	}

	public function listar_pacientes_ajax(Request $request)
	{
		$model_procedimiento = new Paciente();
		$draw = $request->draw;
		$length = (int)$request->input('length', 10);
		if ($length <= 0) {
			$length = 10;
		}

		$start = ((int)$request->start / $length) + 1;
		$search_value = $request->search["value"];

		$dni_beneficiario = $request->input('dni_beneficiario', '');
    	$nombre_beneficiario = $request->input('nombre_beneficiario', '');

		$p[] = (string)$start;
		$p[] = (string)$length;
		$p[] = $search_value;
		$p[] = $dni_beneficiario;
      	$p[] = $nombre_beneficiario;

		$data = $model_procedimiento->listar_pacientes_ajax($p);

		$iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
		$result["draw"] = $draw;
		$result["recordsTotal"] = $iTotalDisplayRecords;
		$result["recordsFiltered"] = $iTotalDisplayRecords;
		$result["data"] = $data;

		return response()->json($result);
	}

	public function buscar_persona(Request $request)
	{
		$id_tipo_doc_ident = $request->tipo_documento;
		$dni = $request->dni;
		$tipo_busqueda = $request->tipo_busqueda; //reniec, tabla_asegurados, dirrehum, saludpol, siteds

		if ($tipo_busqueda == "reniec" && $id_tipo_doc_ident == "1") {
			$WsReniec = new \App\Ws\Reniec;
			$personal_reniec = $WsReniec->BuscarDNI($dni);
			$personal = json_decode($personal_reniec, true);
		} else if ($tipo_busqueda == "tabla_asegurados") {
			$modelmedico = new Paciente();
			$asegurado = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident);
			//datos del titular
			if ($asegurado != null) {
				$id_asegurado_titular = $asegurado->id;
				$model_personadatosadicional = new Persona_datos_adicional();
				$personadatosadicional = $model_personadatosadicional->persona_datos_Adicionales($id_asegurado_titular);

				if ($personadatosadicional != null) {
					$personal = $personadatosadicional;
				} else {
					$personal = $asegurado;
				}
			} else {
				$personal = $asegurado;
			}
		} else if ($tipo_busqueda == "dirrehum") {
			$Dirrehum = new \App\Ws\Dirrehum;
			$result = $Dirrehum->BuscarTitularFamiliar($id_tipo_doc_ident, $dni);
			$personal = json_encode($result->CallBuscarTitularFamiliarResult->TitularFamiliarResponse, true);
		} else if ($tipo_busqueda == "saludpol") {
			//$client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));
			//$personal = $client->get_busafi_activo($dni);
			$Saludpol = new \App\Ws\Saludpol;
			$result = $Saludpol->BuscarTitularFamiliar($id_tipo_doc_ident, $dni, '');
			$personal = $result;
		}
		echo json_encode($personal);
	}

	public function buscar_paciente_ipress(Request $request)
	{
		$id_establecimiento = $request->id_ipress;
		$id_Asegurado = $request->id_ase;

		$modelpaciente = new Paciente();
		$paciente_est = $modelpaciente->paciente_yaregistrado("", $id_establecimiento, "", $id_Asegurado);
		echo json_encode($paciente_est);
	}

	public function editar_paciente2($id)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$id_asegurado = $id;
		$id_user = Auth::user()->id;
		$model = new User;
		$establecimientotot = $model->GetById($id_user);
		$id_establecimiento = $establecimientotot->id_establecimiento;
		$existe = $establecimientotot->flagestablecimientos;

		if ($existe == "1") { //todos
			$establecimiento = "TODOS LOS ESTABLECIMIENTOS";
			$modelpaciente = new Paciente();
			$paciente_registrado = $modelpaciente->paciente_yaregistrado("", "", "", $id);
			$establecimiento = $paciente_registrado;

			$cantidad_p = count($paciente_registrado); //1 o mas
			if ($cantidad_p == "1") {
				//establecimiento que tiene historia
				$id_ipress = $paciente_registrado[0]->id_establecimiento;
				$nro_historia = $paciente_registrado[0]->nro_historia;
				$nombre_establecimiento = $paciente_registrado[0]->establecimiento;
			} else {
				$id_ipress = 0;
				$nro_historia = "";
			}
		} else { //0				
			$modelpaciente = new Paciente();
			$paciente_registrado = $modelpaciente->paciente_yaregistrado("", $id_establecimiento, "", $id);
			$id_ipress = $id_establecimiento;
			$modelIpress = new Establecimiento();
			$establecimiento = $modelIpress->GetById($id_ipress);
			$nombre_establecimiento = $establecimiento[0]->nombre;
			$nro_historia = "";
		}
		//datos del paciente
		$model_paciente = new Paciente();
		$personal = $model_paciente->GetAseguradoById($id);

		//datos del titular
		$id_asegurado_titular = $personal->id_asegurado_titular;
		$model_personadatosadicional = new Persona_datos_adicional();
		$personadatosadicional = null;
		if ($id_asegurado_titular > 0) {
			$personadatosadicional = $model_personadatosadicional->persona_datos_Adicionales($id_asegurado_titular);
		}

		$model_beneficiario = new \App\Models\Tipo_beneficiario;
		$tipo_beneficiario = $model_beneficiario->ShowTipo_beneficiario();

		$model_categoria = new \App\Models\Titular_categoria();
		$categoria = $model_categoria->ShowTipoCategoria();

		$model_grado = new \App\Models\Titular_grado();
		$grado = $model_grado->ShowTipoGrado();

		$model_situacion = new \App\Models\Situacion();
		$situacion = $model_situacion->ShowSituacion();

		$model_unidadpago = new \App\Models\Unidadpago();
		$unidadpago = $model_unidadpago->ShowUnidadpago();

		$model_tipo_documento_identidad = new \App\Models\Tipo_documento_identidad();
		$tipo_documento_identidad = $model_tipo_documento_identidad->ShowTipoDocumentoIdentidad();

		return view('frontend.pacientes.editar_paciente', compact('establecimiento', 'personal', 'personadatosadicional', 'tipo_beneficiario', 'categoria', 'grado',  'situacion', 'unidadpago', 'tipo_documento_identidad', 'id_ipress', 'nombre_establecimiento', 'nro_historia', 'id_asegurado'));
	}

	public function update_paciente(Request $request)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$id_user = Auth::user()->id;

		//asegurados
		$update = Paciente::where('id', '=', $request->id_asegurado)->first();
		//$update->id= $request->id_asegurado;
		$update->tipo_doc_ident = $request->tipo_doc_ident;
		$update->nro_doc_ident = $request->dni;
		$update->cip = $request->cip;
		$update->nombre = $request->nombre;
		$update->paterno = $request->paterno;
		$update->materno = $request->materno;
		$update->sexo = $request->sexo;
		$update->fecha_nac = $request->fecha_nac;
		$update->id_estado_reg = "1";
		$update->observacion = $request->observacion;
		//$update->updated_at= $request->updated_at;
		$update->updated_us = $id_user;
		//$update->deleted_at= $request->deleted_at;
		//$update->deleted_us= $request->deleted_us;
		$update->telefono = $request->telefono;
		$update->domicilio = $request->domicilio;
		$update->correo = $request->correo;
		$update->id_tipo_doc_ident = $request->id_tipo_doc_ident;
		$update->id_tipo_beneficiario = $request->id_tipo_beneficiario;

		if ($request->id_asegurado_titular == "") {
			$update->id_asegurado_titular = 0;
		} else {
			$update->id_asegurado_titular = $request->id_asegurado_titular;
		}
		//$update->foto= $request->foto;
		$update->save();

		//asegurado_historias

		$objpaciente = new Paciente();
		if (isset($request->id_establecimiento)) { //actualizar 1 sóla historia
			$update_historia = Asegurado_historia::where('id_asegurado', '=', $request->id_asegurado)->where('id_establecimiento', '=', $request->id_establecimiento)->first();
			$update_historia->nro_historia = $request->nro_historia;
			$update_historia->id_estado_reg = "1";
			$update_historia->updated_us = $id_user;
			$update_historia->save();
			$objpaciente->UpdateRecetaVale($request->dni, $request->id_establecimiento, $request->nro_historia);
		} else {
			if (!empty($request->input("id_establecimientos")) && is_array($request->input("id_establecimientos"))) {
				$ii = 0;
				foreach ($request->input("id_establecimientos") as $id_estab) {
					$update_historia = Asegurado_historia::where('id_asegurado', '=', $request->id_asegurado)->where('id_establecimiento', '=', $id_estab)->first();
					$update_historia->nro_historia =  $request->input("nro_historias")[$ii];
					$update_historia->id_estado_reg = "1";
					$update_historia->updated_us = $id_user;
					$update_historia->save();
					$objpaciente->UpdateRecetaVale($request->dni, $id_estab, $request->input("nro_historias")[$ii]);
					$ii++;
				}
			}
		}
		//actualizar en receta vale



		//persona_datos_adicionales
		if ($request->id_asegurado_titular > 0) {
			$update_datos_adicionales = Persona_datos_adicional::where('id_asegurado', '=', $request->id_asegurado_titular)->where('estado', '=', "1")->first();
			//cambiar el texto a id
			$model_1 = new Situacion();
			$model_2 = new Unidadpago();
			$model_3 = new Titular_grado();
			$model_4 = new Titular_categoria();

			if ($request->id_situacion != '' or $request->id_situacion != null):
				$busca_situacion = $model_1->BuscaSituacion($request->id_situacion);
				$id_situacion = $busca_situacion->id;
			else:
				$id_situacion = 8;
			endif;

			if ($request->id_unidad != '' or $request->id_unidad != null):
				$busca_unidad = $model_2->BuscaUnidad($request->id_unidad);
				$id_unidad = $busca_unidad->id;
			else:
				$id_unidad = 441;
			endif;

			if ($request->id_grado != '' or $request->id_grado != null):
				$busca_grado = $model_3->BuscaGrado($request->id_grado);
				$id_grado = $busca_grado->id;
			else:
				$id_grado = '999';
			endif;

			if ($request->id_categoria != '' or $request->id_categoria != null):
				$busca_categoria = $model_4->BuscaTipoCategoria($request->id_categoria);
				$id_categoria = $busca_categoria->id;
			else:
				$id_categoria = '99';
			endif;


			if ($request->id_situacion)

				if (count($update_datos_adicionales) > 0) { //actualizar
					$update_datos_adicionales->id_asegurado	= $request->id_asegurado_titular;
					$update_datos_adicionales->cip		    = $request->cip;
					$update_datos_adicionales->id_situacion	= $id_situacion;
					//$update_datos_adicionales->id_situacion	= $request->id_situacion;	
					$update_datos_adicionales->id_unidad    = $id_unidad;
					//$update_datos_adicionales->id_unidad    = $request->id_unidad;
					$update_datos_adicionales->id_grado		= $id_grado;
					//$update_datos_adicionales->id_grado		= $request->id_grado;
					$update_datos_adicionales->id_categoria	= $id_categoria;
					//$update_datos_adicionales->id_categoria	= $request->id_categoria;
					if ($request->fecha_inicio_servicio != "") {
						$update_datos_adicionales->fecha_inicio_servicio	= $request->fecha_inicio_servicio;
					}
					$update_datos_adicionales->updated_us = $id_user;
					$update_datos_adicionales->save();
				} else { //registrar
					$model_datos_adicionales = new Persona_datos_adicional();
					$model_datos_adicionales->id_asegurado	= $request->id_asegurado_titular;
					$model_datos_adicionales->cip		    = $request->cip;
					$model_datos_adicionales->id_situacion	= $id_situacion;
					//$model_datos_adicionales->id_situacion	= $request->id_situacion;	
					$model_datos_adicionales->id_unidad    = $id_unidad;
					//$model_datos_adicionales->id_unidad    = $request->id_unidad;
					$model_datos_adicionales->id_grado		= $id_grado;
					//$model_datos_adicionales->id_grado		= $request->id_grado;
					$model_datos_adicionales->id_categoria	= $id_categoria;
					//$model_datos_adicionales->id_categoria	= $request->id_categoria;
					if ($request->fecha_inicio_servicio != "") {
						$model_datos_adicionales->fecha_inicio_servicio	= $request->fecha_inicio_servicio;
					}
					$model_datos_adicionales->estado = "1";
					$model_datos_adicionales->created_us = $id_user;
					$model_datos_adicionales->save();
				}
		}
		Session::flash('flash_message', 'Actualización exitosa');
		return redirect('/all_asegurados');
	}

	public function update_paciente2(Request $request)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$id_user = Auth::user()->id;
		//asegurados
		$update = Paciente::where('id', '=', $request->id_asegurado)->first();

		// $update->tipo_doc_ident= $request->tipo_doc_ident;
		$update->id_tipo_doc_ident = $request->id_tipo_doc_ident;
		$update->nro_doc_ident = $request->nro_doc_ident;
		$update->id_tipo_beneficiario = $request->id_tipo_beneficiario;
		$update->nombre = $request->nombre;
		$update->paterno = $request->paterno;
		$update->materno = $request->materno;
		$update->sexo = $request->sexo;
		$update->fecha_nac = $request->fecha_nac;
		$update->telefono = $request->telefono;
		$update->id_tipo_sangre = $request->id_tipo_sangre;
		$update->correo = $request->correo;
		$update->observacion = $request->observacion;
		$update->id_departamento = $request->id_departamento;
		$update->id_provincia = $request->id_provincia;
		$update->id_distrito = $request->id_distrito;
		$update->domicilio = $request->domicilio;
		$update->id_grado_instruccion = $request->id_grado_instruccion;
		$update->id_ocupacion = $request->id_ocupacion;
		$update->id_estado_civil = $request->id_estado_civil;
		$update->id_religion = $request->id_religion;
		$update->id_etnia = $request->id_etnia;
		// $update->id_tutor = $request->id_tutor;
		$update->id_tipo_parentesco = ($request->has('id_parentesco') && $request->get('id_parentesco') !== '') ? $request->id_parentesco : null;
		$update->discapacidad = $request->has('discapacidad') ? $request->discapacidad : 0;
		$update->otro_seguro = ($request->has('otro_seguro') && $request->get('otro_seguro') !== '') ? $request->otro_seguro : null;
		$update->discapacidad_carnet = ($request->has('discapacidad_carnet') && $request->get('discapacidad_carnet') !== '') ? $request->discapacidad_carnet : null;
		$update->nro_carnet_conadis = ($request->has('nro_carnet_conadis') && $request->get('nro_carnet_conadis') !== '') ? $request->nro_carnet_conadis : null;
		$update->color_carnet_conadis = ($request->has('color_carnet_conadis') && $request->get('color_carnet_conadis') !== '') ? $request->color_carnet_conadis : null;

		if ($request->id_tipo_beneficiario == 8) {
			$update->tipo_doc_titular = $request->tipo_doc_titular2 ?: null;
			$update->dni_titular = $request->dni_titular2 ?: null;
			$update->nombre_titular = $request->nombre_titular2 ?: null;
			$update->materno_titular = $request->materno_titular2 ?: null;
			$update->paterno_titular = $request->paterno_titular2 ?: null;
			$update->cip = $request->cips2 ?: null;
			$update->grado = $request->grado2 ?: null;
			$update->situacion_paciente = $request->situacion_paciente2 ?: null;
		} else {
			$update->tipo_doc_titular = $request->tipo_doc_titular ?: null;
			$update->dni_titular = $request->dni_titular ?: null;
			$update->nombre_titular = $request->nombre_titular ?: null;
			$update->materno_titular = $request->materno_titular ?: null;
			$update->paterno_titular = $request->paterno_titular ?: null;
			$update->cip = $request->cips ?: null;
			$update->grado = $request->grado ?: null;
			$update->situacion_paciente = $request->situacion_paciente ?: null;
		}

		$update->updated_us = $id_user;
		$update->id_estado_reg = "1";
		$update->save();


		if (!empty($request->id_parentesco)) {
			$model_tutor = new Tutor();

			$obj = new stdClass();
			$obj->tipo_documento    = $request->tipo_documento;
			$obj->numero_documento  = $request->numero_documento;
			$obj->nombres           = $request->nombres;
			$obj->apellidos         = $request->apellidos;
			$obj->telefono_tutor    = $request->telefono_tutor;
			$obj->observacion_tutor = $request->observacion_tutor;
			$obj->id_parentesco     = $request->id_parentesco;
			$obj->direccion         = $request->direccion;
			$obj->flag              = 1;

			if (!empty($update->id_tutor)) {
				$obj->id_tutor = $update->id_tutor;
				$model_tutor->actualizarTutor($obj);
			} else {
				$insert_tutor = $model_tutor->registrarTutor($obj);
				if ($insert_tutor) {
					$update->id_tutor = $insert_tutor;
					$update->save();
				}
			}
		}

		if (!empty($request->input("id_asegurado_historia")) && is_array($request->input("id_asegurado_historia"))):
			
			foreach ($request->input("id_asegurado_historia") as $key => $id_asegurado_historia):
				if (intval($id_asegurado_historia) > 0):
					if (strval($request->nro_historias_temp[$key]) !== strval($request->nro_historias[$key])):
						$update_historia = Asegurado_historia::find($id_asegurado_historia);
						$update_historia->nro_historia = $request->input("nro_historias")[$key];
						$update_historia->id_estado_reg = 1;
						$update_historia->updated_us = $id_user;

						if ($update_historia->save()):
							// $objpaciente = new Paciente();
							// $objpaciente->UpdateRecetaVale($request->dni, $request->id_establecimientos[$key], $request->input("nro_historias")[$key]);
						else:
							throw new \Exception("Error al intentar actualizar los N° de Historia Clínicas.");
						endif;
					endif;
				else:
					if (intval($request->id_establecimiento_nuevo) > 0 && trim($request->nro_historia_nuevo) !== ""):
						$asegurado_historia = new Asegurado_historia();
						$asegurado_historia->id_asegurado = $request->id_asegurado;
						$asegurado_historia->id_establecimiento = $request->id_establecimiento_nuevo;
						$asegurado_historia->nro_historia = $request->nro_historia_nuevo;
						$asegurado_historia->id_estado_reg = 1;
						$asegurado_historia->created_at = Carbon::now();
						$asegurado_historia->created_us = $id_user;

						if ($asegurado_historia->save()):
							// $objpaciente = new Paciente();
							// $objpaciente->UpdateRecetaVale($request->dni, $request->id_establecimiento_nuevo, $request->nro_historia_nuevo);
						else:
							throw new \Exception("Error al intentar registrar el nuevo N° de Historia Clínica.");
						endif;
					endif;
				endif;
			endforeach;
		endif;

		DB::table('asegurado_auditores')->insert([
			'operacion'            => '1',
			'id_asegurado'         => $update->id,
			'nro_doc_beneficiario' => $update->nro_doc_ident,
			'paterno_asegurado'    => $update->paterno,
			'materno_asegurado'    => $update->materno,
			'nombre_asegurado'     => $update->nombre,
			'id_asegurado_titular' => $update->id_asegurado_titular, 
			'id_tipo_beneficiario' => $update->id_tipo_beneficiario,
			'grado'                => $update->grado,
			'situacion'            => $update->situacion_paciente,
			'nro_doc_titular'      => !empty($update->dni_titular)  ? $update->dni_titular : $update->nro_doc_ident,
			'paterno_titular'      => $update->paterno_titular,
			'materno_titular'      => $update->materno_titular,
			'nombre_titular'       => $update->nombre_titular,
			'fechaini_afiliacion'  => $update->fechaini_afiliacion,
			'fechafin_afiliacion'  => $update->fechafin_afiliacion,
			'estado_afiliacion'    => $update->estado_afiliacion,
			'condicion_asegurado'  => $update->condicion_asegurado,
			'estado_old'           => '0',
			'estado_new'           => '1',
			'created_us'           => $id_user,
			'created_at'           => Carbon::now(),       
			'incial_created_us'    => $id_user,            
			'iniciail_created_at'  => Carbon::now(),       
		]);
		
		Session::flash('flash_message', 'Actualización exitosa');
		// return redirect('/editar_paciente/'.$request->id_asegurado);
		return redirect('/editar_paciente/' . $request->id_asegurado . '?tutor_registrado=1');
	}


// 	public function desactivar($id, $estado)
// 	{

// 		if (!Auth::check()) :
// 			return redirect('/auth/login');
// 		endif;

//     $id_user = Auth::user()->id;
//     if ($estado == "1") { //anular
//       $update = Paciente::where('id', '=', $id)->first();
//       $update->id_estado_reg = 0;
//       $update->deleted_us = $id_user;
//       $update->deleted_at = date('Y-m-d H:i:s');
//       $update->save();
//       Session::flash('flash_message', 'Se desactivo correctamente');
//     } else {
//     //   $update = Medico::where('id', '=', $id)->first();
// 	  $update = Paciente::where('id', '=', $id)->first();
//       $update->id_estado_reg = 1;
//       $update->updated_us = $id_user;
//       $update->updated_at = date('Y-m-d H:i:s');
//       $update->save();
//       Session::flash('flash_message', 'Se activo correctamente');
//     }
//   }
	/////////////////////////////////////////////////////////////////////////// NUEVO DESACTIVAR
	public function desactivar($id, $estado) {
		if (!Auth::check()) {
			return redirect('/auth/login');
		}

		$id_user  = Auth::user()->id;
		$update   = Paciente::where('id', $id)->firstOrFail();

		$original_created_us = $update->created_us;
		$original_created_at = substr((string) $update->getAttributes()['created_at'], 0, 19);

		if ($estado == "1") { // Desactivar
			$update->id_estado_reg = 0;
			$update->deleted_us    = $id_user;
			$update->deleted_at    = Carbon::now();
			$update->save();

			$estado_new = '0';
			Session::flash('flash_message', 'Se desactivó correctamente');
		} else { // Activar
			$update->id_estado_reg = 1;
			$update->updated_us    = $id_user;
			$update->updated_at    = Carbon::now();
			$update->save();

			$estado_new = '1';
			Session::flash('flash_message', 'Se activó correctamente');
		}

		DB::table('asegurado_auditores')->insert([
			'operacion'            => '2',
			'id_asegurado'         => $update->id,
			'nro_doc_beneficiario' => $update->nro_doc_ident,
			'paterno_asegurado'    => $update->paterno,
			'materno_asegurado'    => $update->materno,
			'nombre_asegurado'     => $update->nombre,
			'id_asegurado_titular' => $update->id_asegurado_titular, 
			'id_tipo_beneficiario' => $update->id_tipo_beneficiario,
			'grado'                => $update->grado,
			'situacion'            => $update->situacion_paciente,
			'nro_doc_titular'      => !empty($update->dni_titular)  ? $update->dni_titular : $update->nro_doc_ident,
			'paterno_titular'      => $update->paterno_titular,
			'materno_titular'      => $update->materno_titular,
			'nombre_titular'       => $update->nombre_titular,
			'fechaini_afiliacion'  => $update->fechaini_afiliacion,
			'fechafin_afiliacion'  => $update->fechafin_afiliacion,
			'estado_afiliacion'    => $update->estado_afiliacion,
			'condicion_asegurado'  => $update->condicion_asegurado,
			'estado_old'           => $estado == "1" ? 1 : 0,
			'estado_new'           => $estado_new,
			'created_us'           => $id_user,
			'created_at'           => Carbon::now()->toDateTimeString(),
			'incial_created_us'    => $original_created_us,
			'iniciail_created_at'  => $original_created_at,
		]);
	}


	public function bloquear_atencion($id, $flag)
	{
		if (!Auth::check()) {
			return response()->json(['sw' => false], 401);
		}

		if (Auth::user()->flag_rrhh != 1) {
			return response()->json(['sw' => false], 403);
		}

		$flag = intval($flag) === 1 ? 1 : 0;

		$motivo = trim((string) request()->input('motivo', ''));
		if ($motivo === '') {
			return response()->json(['sw' => false, 'msg' => 'El motivo es obligatorio.'], 422);
		}
		$motivo = mb_substr($motivo, 0, 100);

		$actualizado = DB::transaction(function () use ($id, $flag, $motivo) {

			$actualizado = DB::table('asegurados')->where('id', $id)->update([
				'flag_bloqueo_atencion' => $flag
			]);

			if ($actualizado > 0) {
				DB::table('atenciones_bloqueo_historial')->insert([
					'id_asegurado' => $id,
					'accion'       => $flag === 1 ? 1 : 2, // 1 bloqueo, 2 desbloqueo
					'motivo'       => $motivo,
					'created_at'   => Carbon::now(),
					'created_us'   => Auth::user()->id,
				]);
			}

			return $actualizado;
		});

		return response()->json(['sw' => $actualizado > 0, 'flag_bloqueo_atencion' => $flag]);
	}

	public function buscar_persona_ajax($tipo_doc, $nro_documento)
	{
		$obj_paciente = new Paciente();
		$paciente = $obj_paciente->GetAseguradoByDNI($nro_documento, $tipo_doc);

		$ws = new WebServiceController;

		if ($paciente != null) {
			if ($paciente->foto == null) {
				$result = $ws->Reniec($nro_documento);
				$paciente->foto = $result['foto'];
			}

			if ($paciente->id_tipo_beneficiario == null) {
				$result = $ws->SaludpolBuscarTitularFamiliar($tipo_doc, $nro_documento);
				//dd($result); return;
				if ($result == null) {
					$paciente->id_tipo_beneficiario = 13;
				} else {
					$paciente->id_tipo_beneficiario = $this->getTipoBeneficiario($tipo_doc, $nro_documento);
				}
			}

			$obj_paciente->ActualizaPaciente($paciente);
		} else {
			$result = $ws->Reniec($nro_documento);

			//$obj_paciente->tipo_doc_ident = $tipo_doc;
			$obj_paciente->nro_doc_ident = $nro_documento;
			$obj_paciente->nombre = $result['nombres'];
			$obj_paciente->paterno = $result['paterno'];
			$obj_paciente->materno = $result['materno'];
			$obj_paciente->sexo = substr($result['sexo'], 0, 1);
			$obj_paciente->fecha_nac = $result['fecha_nac'];
			$obj_paciente->id_estado_reg = 1;
			$obj_paciente->domicilio = $result['direccionDomicilio'];
			$obj_paciente->foto = $result['foto'];
			$obj_paciente->id_tipo_doc_ident = $tipo_doc;


			$obj_paciente->id_tipo_beneficiario = $this->getTipoBeneficiario($tipo_doc, $nro_documento);

			$paciente = json_decode(json_encode($obj_paciente, true));
		}

		echo json_encode($paciente, true);
	}

	public function buscar_asegurado_id($id_asegurado)
	{
		$user = Auth::user();
		$model_asegurado = new Paciente;
		$asegurado = $model_asegurado->GetCustomAseguradoById($id_asegurado, $user->id_establecimiento);
		return json_encode($asegurado, true);
	}

	private function getTipoBeneficiario($tipo_doc, $nro_documento)
	{
		$ws = new WebServiceController;
		$result = $ws->SaludpolBuscarTitularFamiliar($tipo_doc, $nro_documento);

		if (json_encode($result) == '[{"Error":"No se encontro ning\u00fan resultado"}]') {
			return 13;
		} else {
			$objTipoBeneficiario = new \App\Models\Tipo_beneficiario;
			$tipo_beneficiario = $objTipoBeneficiario->getTipoBeneficiarioDescripcion($result->parentesco);
			return $tipo_beneficiario->id;
		}
	}

	public function paciente_antecedentes($dni, $tipo_atencion, $id_cita)
	{

		$pacientes = Paciente::where('nro_doc_ident', $dni)->first();

		$id_paciente = $pacientes->id;
		$model_antecedente = new Antecedente();
		$model_tipo_antecedente = new Tipo_antecedente();
		$antecedente_paciente = $model_antecedente->getAntecedentePacientes($id_paciente);
		$antecedente = Antecedente::where('paciente_id', $id_paciente)->first();

		if (!is_object($antecedente)) {
			$antecedente = new Antecedente();
			$antecedente->alergia_medicamentos = '';
			$antecedente->alergia_alimentos = '';
			$antecedente->alergia_picadura_insectos = '';
			$antecedente->alergia_otros = '';
			$antecedente->vacuna_antitetano_dosis = '';
			$antecedente->vacuna_hepatitis_dosis = '';
			$antecedente->vacuna_influenza_dosis = '';
			$antecedente->vacuna_covid_dosis = '';
			$antecedente->vacuna_antitetano_fecha = '';
			$antecedente->vacuna_hepatitis_fecha = '';
			$antecedente->vacuna_influenza_fecha = '';
			$antecedente->vacuna_covid_fecha = '';
			$antecedente->tratamiento_actual = '';
		}
		$antecedente_1 = $model_tipo_antecedente->getTipoAntecedentes(1);
		$antecedente_2 = $model_tipo_antecedente->getTipoAntecedentes(2);
		$antecedente_3 = $model_tipo_antecedente->getTipoAntecedentes(3);

		return view('frontend.pacientes.antecedentes_paciente')->with('pacientes', $pacientes)->with('antecedente_1', $antecedente_1)->with('antecedente_2', $antecedente_2)->with('antecedente_3', $antecedente_3)->with('id_paciente', $id_paciente)->with('antecedente_paciente', $antecedente_paciente)->with('antecedente_pac', $antecedente)->with('tipo_atencion', $tipo_atencion)->with('id_cita', $id_cita);
	}

	public function update_paciente_antecedentes(Request $request)
	{
		$pacientes = Paciente::Where('id', $request->id_paciente)->first();
		$id_paciente = $pacientes->id;
		$model_antecedente = new Antecedente();
		$antecedente_paciente = $model_antecedente->getAntecedentePacientes($id_paciente);
		$pacientes->antecedentepacientes()->detach($request->antecedentes_1);
		$pacientes->antecedentepacientes()->attach($request->antecedentes_1);

		$objAntecedente = Antecedente::where('paciente_id', $id_paciente)->first();

		if (!is_object($objAntecedente)) {
			$objAntecedente = new Antecedente();
		}

		$objAntecedente->paciente_id = $id_paciente;
		$objAntecedente->alergia_medicamentos = $request->alergia_medicamentos;
		$objAntecedente->alergia_alimentos = $request->alergia_alimentos;
		$objAntecedente->alergia_picadura_insectos = $request->alergia_picadura_insectos;
		$objAntecedente->alergia_otros = $request->alergia_otros;
		$objAntecedente->vacuna_antitetano_dosis = $request->vacuna_antitetano_dosis;
		$objAntecedente->vacuna_hepatitis_dosis = $request->vacuna_hepatitis_dosis;
		$objAntecedente->vacuna_influenza_dosis = $request->vacuna_influenza_dosis;
		$objAntecedente->vacuna_covid_dosis = $request->vacuna_covid_dosis;
		$objAntecedente->vacuna_antitetano_fecha = $request->vacuna_antitetano_fecha;
		$objAntecedente->vacuna_hepatitis_fecha = $request->vacuna_hepatitis_fecha;
		$objAntecedente->vacuna_influenza_fecha = $request->vacuna_influenza_fecha;
		$objAntecedente->vacuna_covid_fecha = $request->vacuna_covid_fecha;
		$objAntecedente->tratamiento_actual = $request->tratamiento_actual;
		$objAntecedente->save();

		switch ($request->id_tipo_atencion) {
			case 1:
				return redirect('/formulario_atencion/' . $request->id_cita)->withFlashSuccess("Se ha actualizado correctamente.");
				break;
			case 2:
				return redirect('/formulario_atencion_emergencia/' . $request->id_cita)->withFlashSuccess("Se ha actualizado correctamente.");
				break;
		}
	}


	public function obtener_datos_persona_ajax(Request $request)
	{
		//return null; //!TODO: eliminar

		$id_tipo_doc_ident = $request->tipo_documento;
		$dni = $request->dni;
		$flag_api_sp = $request->usarApi;
		$origen = $request->origen;
		//$tipo_busqueda = $request->tipo_busqueda; //reniec, tabla_asegurados, dirrehum, saludpol, siteds

		$personal = null;
		$objAsegurado = new Paciente();

		//VALIDA TIPO DOC NULO EN ASEGURADOS
		$tipoDocNullAse = $objAsegurado->GetAseguradosSoloDNI($dni);
		//dd($tipoDocNullAse);
		if ($tipoDocNullAse) {
			if ($tipoDocNullAse->id_tipo_doc_ident == null) {
				$objAseguradoEdit = Paciente::find($tipoDocNullAse->id);
				if ($objAseguradoEdit->tipo_doc_ident == 'DNI') {
					$objAseguradoEdit->id_tipo_doc_ident = '1';
					$objAseguradoEdit->save();
				} else if ($objAseguradoEdit->tipo_doc_ident == 'CARNET DE EXTRANJERIA' || $objAseguradoEdit->tipo_doc_ident == 'Carnet Extranjeria') {
					$objAseguradoEdit->id_tipo_doc_ident = '2';
					$objAseguradoEdit->save();
				} else if ($objAseguradoEdit->tipo_doc_ident == 'PASAPORTE' || $objAseguradoEdit->tipo_doc_ident == 'Pasaporte') {
					$objAseguradoEdit->id_tipo_doc_ident = '3';
					$objAseguradoEdit->save();
				} else if ($objAseguradoEdit->tipo_doc_ident == 'DOCUMENTO DE IDENTIDAD EXTRANJERO' || $objAseguradoEdit->tipo_doc_ident == 'Documento de Identidad extranjero') {
					$objAseguradoEdit->id_tipo_doc_ident = '4';
					$objAseguradoEdit->save();
				}
			}
		}
		//datos del ASEGURADO
		$modelmedico = new Paciente();
		$asegurado = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident); //! verifica si esta en la tabla aseugrad	
		//dd($asegurado);
		$id_tipo_beneficiario = '';

		if ($asegurado != null) {
			$id_asegurado = $asegurado->id;
			$model_personadatosadicional = new Persona_datos_adicional();
			$personadatosadicional = $model_personadatosadicional->persona_datos_Adicionales($id_asegurado);

			if ($personadatosadicional != null) {
				$personal = $personadatosadicional;
			} else {
				$personal = $asegurado;
			}
			$id_tipo_beneficiario = $asegurado->id_tipo_beneficiario;
		} else {
			try {
				$objAseguradoHistoria = new Asegurado_historia;
				$objAseguradoHistoria->id_asegurado = $tipoDocNullAse->id;
				$objAseguradoHistoria->id_establecimiento = Auth::user()->id_establecimiento;
				$objAseguradoHistoria->nro_historia = $dni;
				$objAseguradoHistoria->id_estado_reg = 1;
				$objAseguradoHistoria->created_us = Auth::user()->id;
				$objAseguradoHistoria->save();

				$personal = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident);   //08085318
			} catch (\Exception $e) {
				$personal = null;
			}
		}

		$valor_encontrado = 0;
		$id_departamento  = '';
		$id_provincia  = '';
		$id_distrito  = '';
		$codigo_ubig  = '';

		if ($personal == null && $id_tipo_doc_ident == "1") {
			//Log::info("Ingresa a personal == null && id_tipo_doc_ident == 1");
			$Saludpol = new \App\Ws\Saludpol;
			$wscontroller   =   new \App\Http\Controllers\WebServiceController();
			/* $WsReniec = new \App\Ws\Reniec; */

			try {
				if($flag_api_sp == 'api'){
					//Log::info("Ingresa a personal == null && id_tipo_doc_ident == 1 -> api");
					//DATOS API SALUDPOL
					$origen_f = !empty($origen) ? 'Saludpol_API_'.$origen : 'Saludpol_API2';
					$request = new Request(['tipoDocumento' => $id_tipo_doc_ident, 'numeroDocumento' => $dni,'origen'=> $origen_f]);
					$result = $wscontroller->consultarSaludpol2($request);
				}else{
					//Log::info("Ingresa a personal == null && id_tipo_doc_ident == 1 -> ws");
					//DATOS WS SALUDPOL
					$result = $Saludpol->BuscarTitularFamiliar($id_tipo_doc_ident, $dni, '');

					$origen_f = !empty($origen) ? 'Saludpol_ws_'.$origen : 'Saludpol';
					$log = new App\Models\LogWs;
					$log->ws = $origen_f;
					$log->tipo_doc_buscado = $id_tipo_doc_ident;
					$log->doc_buscado = $dni;
					$log->fechafin_afiliacion = !empty($result->caducidad) ? Carbon::createFromFormat('Ymd', $result->caducidad)->format('Y-m-d'): null;
					$log->estado_afiliacion = !empty($result->estado) ? $result->estado : null;
					$log->created_at = Carbon::now();
					$log->created_us = Auth::user()->id;
					$log->save();
				}
			} catch (Exception $e) {
				//Log::error("Error Servicio Saludpol: " . $e->getMessage());
				$result = null;
			}

		} else {
			$valor_encontrado = 1;
			if ($id_tipo_doc_ident == "1") {
				/* $WsReniec = new \App\Ws\Reniec;
				try {
					$personal_reniec = $WsReniec->BuscarDNI($dni);
					$datosRec=json_decode($personal_reniec, true);
					//codigoMensaje = 17 cuando reniec es inestable
					//codigoMensaje = 03 cuando los datos de conexión son incorrectos
					if(!empty($datosRec)){
							if($datosRec['codigoMensaje']!='MR' && $datosRec['codigoMensaje']!='17' && $datosRec['codigoMensaje']!='03'
							&& $datosRec['codigoMensaje']!='05' && $datosRec['codigoMensaje']!='Usuario cliente caducado'){ 
								$personal->nombre = $datosRec["nombres"];
								$personal->paterno = $datosRec["paterno"];
								$personal->materno = $datosRec["materno"];
								if ($datosRec["sexo"] == 'FEMENINO') {
									$personal->sexo    = 'F';
									$personal->bak_sexo    = 'F';
								} else {
									$personal->sexo    = 'M';
									$personal->bak_sexo    = 'M';
								}
								$personal->fecha_nac    = $datosRec["fecha_nac"]; 
								$personal->foto    = $datosRec["foto"];
								
								if($datosRec["provinciaDomicilio"]!=null && $datosRec["provinciaDomicilio"]!=''&&
									$datosRec["distritoDomicilio"]!=null && $datosRec["distritoDomicilio"]!=''){ 
									if($datosRec['provinciaDomicilio']=='CALLAO'){
										$descripcDepartamento ='CALLAO';
									}else{
										$descripcDepartamento = $datosRec["departamentoDomicilio"];
									}
				
									$departamento_actual = new Departamento();
									$ubica_departamento = $departamento_actual->getObtenerId_by_descripcion($descripcDepartamento);
									if($ubica_departamento){
										//dd($ubica_departamento[0]->id_departamento); 
										if ($ubica_departamento != null) {
											$id_departamento    = $ubica_departamento[0]->id_departamento;
										}
									}else{
										$id_departamento  = '';
									}
				
									if($id_departamento != null){
										if($datosRec["provinciaDomicilio"]){
											$provincia_actual = new Provincia();
											$ubica_provincia = $provincia_actual->getObtenerId_by_descripcion_and_idDepartamento($datosRec["provinciaDomicilio"], $ubica_departamento[0]->id_departamento);
											if($ubica_provincia){
												//dd($ubica_provincia[0]->id_provincia); 
												if ($ubica_provincia != null) {
													$id_provincia    = $ubica_provincia[0]->id_provincia;
												}
											}else{
												$id_provincia  = '';
											}
										}else{
											$id_provincia  = '';
										}
									}else{
										$id_provincia  = '';
									}
				
									if($id_provincia != null){
										$distrito_actual = new Distrito();
										$ubica_distrito = $distrito_actual->getObtenerId_by_descripcion_and_iProvincia($ubica_provincia[0]->id_provincia, $datosRec["distritoDomicilio"]);
										if($ubica_distrito){
											//dd($ubica_distrito[0]->codigo); 
											if ($ubica_distrito != null) {
												$id_distrito    = $ubica_distrito[0]->id_distrito;
												$codigo_ubig    = $ubica_distrito[0]->codigo;
											} 
										}else{
											$id_distrito  = '';
											$codigo_ubig  = '';
										}
									}else{
										$id_distrito  = '';
										$codigo_ubig  = '';
									}
									$personal->domicilio    = $datosRec["direccionDomicilio"];
								}
								////////////obtener ubigeo actual////////////
								////////////obtener ubigeo actual//////////// 
							} 
					}
				} catch (Exception $e) {
					Log::error("Error Servicio Reniec 2: " . $e->getMessage());
					$personal_reniec=null;
					$datosRec=null;
				}  */
			}
		}


		//dd($personal);
		if ($id_tipo_doc_ident == "1" || $id_tipo_doc_ident == "2" || $id_tipo_doc_ident == "3" || $id_tipo_doc_ident == "4") {
			//Log::info("Ingresa a id_tipo_doc_ident ==1 || id_tipo_doc_ident == 2 || id_tipo_doc_ident == 3 || id_tipo_doc_ident == 4");
			//datos de Saludpol
			$Saludpol = new \App\Ws\Saludpol;
			$wscontroller   =   new \App\Http\Controllers\WebServiceController();
			try {
				if($flag_api_sp == 'api'){
					//Log::info("Ingresa id_tipo_doc_ident ==1 || id_tipo_doc_ident == 2 || id_tipo_doc_ident == 3 || id_tipo_doc_ident == 4 -> api");
					//DATOS API SALUDPOL
					$origen_f = !empty($origen) ? 'Saludpol_API_'.$origen : 'Saludpol_API2';
					$request1 = new Request(['tipoDocumento' => $id_tipo_doc_ident, 'numeroDocumento' => $dni, 'origen' => $origen_f]);
					$result = $wscontroller->consultarSaludpol2($request1);
				}else{
					//Log::info("Ingresa id_tipo_doc_ident ==1 || id_tipo_doc_ident == 2 || id_tipo_doc_ident == 3 || id_tipo_doc_ident == 4 -> ws");
					//DATOS WS SALUDPOL
					$result = $Saludpol->BuscarTitularFamiliar($id_tipo_doc_ident, $dni, '');
					
					$origen_f = !empty($origen) ? 'Saludpol_ws_'.$origen : 'Saludpol';
					$log = new App\Models\LogWs;
					$log->ws = $origen_f;
					$log->tipo_doc_buscado = $id_tipo_doc_ident;
					$log->doc_buscado = $dni;
					$log->fechafin_afiliacion = !empty($result->caducidad) ? Carbon::createFromFormat('Ymd', $result->caducidad)->format('Y-m-d'): null;
					$log->estado_afiliacion = !empty($result->estado) ? $result->estado : null;
					$log->created_at = Carbon::now();
					$log->created_us = Auth::user()->id;
					$log->save();
				}
				/* dd($result); */
			} catch (Exception $e) {
				//Log::error("Error Servicio Saludpol: " . $e->getMessage());
				$result = null;
			}
			if (!empty($result)) {
				//Log::info("Ingresa a !empty(result)");
				if (is_array($result)) {
					//Log::info("Ingresa a is_array(result)");
					if ($result[0]) {
						//Log::info("Ingresa a result[0]");
						$rest = [
							'estado_titular' => $result[0]->Error
						];
						//Log::info($rest);
						if ($rest['estado_titular'] == 'El asegurado no esta activo' || trim($rest['estado_titular']) == 'No se encuentra afiliado..!') {
							if ($personal != null) {
								//Log::info("Ingresa a personal != null");
								try {
									if ($valor_encontrado == 1) {
										//Log::info("Ingresa a valor_encontrado == 1");
										$personal->id_tipo_beneficiario = 13;
										$personal->tipo_seguro_estadows = 'El asegurado no esta activo';
									} else {
										//Log::info("Ingresa a valor_encontrado == 0");
										//Log::info("Ingresa a else asegurado no activo");
										$personal['id_tipo_beneficiario'] = 13;
										$personal['tipo_seguro_estadows'] = 'El asegurado no esta activo';
									}
								} catch (\Throwable $th) {
								}
							}
						}
						//Log::info("antes del return");
						return json_encode($personal);
					}
				}
				if (is_object($result) && isset($result->success) && $result->success === false) {
					//Log::info("Ingresa a result API con error (success === false)");
					if ($result->status == 300 || $result->status == 404) {
						$tipo_seguro_estadows = ($result->status == 300) ? 'El asegurado no esta activo' : 'No se encuentra afiliado';
						if ($personal != null) {
							try {
								if ($valor_encontrado == 1) {
									$personal->id_tipo_beneficiario = 13;
									$personal->tipo_seguro_estadows = $tipo_seguro_estadows;
								} else {
									$personal['id_tipo_beneficiario'] = 13;
									$personal['tipo_seguro_estadows'] = $tipo_seguro_estadows;
								}
							} catch (\Throwable $th) {
							}
						} else {
							$personal = ['tipo_seguro_estadows' => $tipo_seguro_estadows];
						}
					}
					return json_encode($personal);
				}
				if (isset($result->cip)) {
					//Log::info("Ingresa a isset(result->cip)");
					if ($personal == null) {
						//Log::info("Ingresa a personal == null");
						if($flag_api_sp == 'api'){
							//Log::info("Ingresa a personal == null ->flag_api_sp == 'api'");
							//DATOS API SALUDPOL
							$request2 = new Request(['tipoDocumento' => 1, 'numeroDocumento' => $result->nrodoctitular]);
							$resultTitular = $wscontroller->consultarSaludpol2($request2);
						}else{
							//Log::info("Ingresa a personal == null ->flag_api_sp == 'api'");
							//DATOS WS SALUDPOL
							$resultTitular = $Saludpol->BuscarTitularFamiliar(1, $result->nrodoctitular, '');
						}	
						$personal = $result;
						$personal->id_situacion    = $result->situacion;
						//Log::info("Ingresa a personal->id_situacion");
						if ($result->nrodoctitular == '00000000') {
							$personal->situaciontitular    = 'SOBREVIVIENTE';
						} else {
							//Log::info("Ingresa a result->nrodoctitular != '00000000'");
							$personal->situaciontitular = isset($resultTitular->situacion) ? $resultTitular->situacion : null;
						}
						$personal->sexotitular = isset($resultTitular->nomsexo) ? $resultTitular->nomsexo : null;
						$personal->gradotitular = isset($resultTitular->grado) ? $resultTitular->grado : null;
						$personal->unidad_pnptitular = isset($resultTitular->unidad_pnp) ? $resultTitular->unidad_pnp : null;
						
						$tipoBenef = new \App\Models\Tipo_beneficiario;
						$personal->id_tipo_beneficiario = $tipoBenef->getTipoBeneficiarioDescripcion($result->parentesco)->id;

						$personal->estado_seguro    = $result->estado;
						$personal->caducidad_seguro    = $result->caducidad;

						if ($result->ubigeo != null) {
							//Log::info("Ingresa a result->ubigeo != null");
							$distrito_actual = new Distrito();
							$ubica_distrito = $distrito_actual->getObtenerUbigeo_by_codigo_ubigeo($result->ubigeo);
							if ($ubica_distrito) {
								//Log::info("Ingresa a ubica_distrito");
								$ubica_departamento = $ubica_distrito[0]->id_departamento;
								if ($ubica_departamento != null) {
									//Log::info("Ingresa a ubica_departamento != null");
									$personal->id_departamento    = $ubica_departamento;

									$ubica_provincia = $ubica_distrito[0]->id_provincia;
									if ($ubica_provincia != null) {
										//Log::info("Ingresa a ubica_departamento != null");
										$personal->id_provincia    = $ubica_provincia;

										$ubica_distrito = $ubica_distrito[0]->id_distrito;
										if ($ubica_distrito != null) {
											//Log::info("Ingresa a ubica_distrito != null");
											$personal->id_distrito    = $ubica_distrito;
											$personal->codigo_ubig    = $result->ubigeo;
										}
									}
								}
							}
						}

						//$personal->id_grado = $result->grado;
					} else {
						//Log::info("Ingresa a personal != null");
						if($flag_api_sp == 'api'){
							//DATOS API SALUDPOL
							$wscontroller   =   new \App\Http\Controllers\WebServiceController();
							$request2 = new Request(['tipoDocumento' => 1, 'numeroDocumento' => $result->nrodoctitular]);
							$resultNewTitular = $wscontroller->consultarSaludpol2($request2);
						}else{
							$resultNewTitular = $Saludpol->BuscarTitularFamiliar(1, $result->nrodoctitular, '');
						}
						if (isset($resultNewTitular->cip)) {
							//Log::info("Ingresa a isset(resultNewTitular->cip");
							try {
								$personal->id_situacion    = $result->situacion;
								$personal->id_grado = $resultNewTitular->grado;
								$personal->grado = $resultNewTitular->grado;

								$personal->nomtipdoctitular = $resultNewTitular->nomtipdoctitular;
								$personal->nrodoctitular = $resultNewTitular->nrodoctitular;
								if ($resultNewTitular->nrodoctitular == '00000000') {
									//Log::info("Ingresa a resultNewTitular->nrodoctitular == '00000000'");
									$personal->situaciontitular    = 'SOBREVIVIENTE';
								} else {
									//Log::info("Ingresa a resultNewTitular->nrodoctitular != '00000000'");
									$personal->situaciontitular    = $resultNewTitular->situacion;
								}
								$personal->apepattitular = $resultNewTitular->apepattitular;
								$personal->apemattitular = $resultNewTitular->apemattitular;
								$personal->apecastitular = $resultNewTitular->apecastitular;
								$personal->nomtitular    = $resultNewTitular->nomtitular;
								$personal->ciptitular    = $resultNewTitular->cip;
								$personal->ubigeotitular    = $resultNewTitular->ubigeo;
								$personal->gradotitular    = $resultNewTitular->grado;
								$personal->discapacidadtitular    = $resultNewTitular->discapacidad;
								$personal->otrosegurotitular    = $resultNewTitular->otroseguro;
								$personal->unidad_pnptitular    = $resultNewTitular->unidad_pnp;
								$personal->sexotitular    = $resultNewTitular->nomsexo;
								$personal->estado_titular    = 'ACTIVO';
								$personal->estado_seguro    = $result->estado;
								$personal->caducidad_seguro    = $result->caducidad;

								$tipoBenef = new \App\Models\Tipo_beneficiario;
								if ($result->parentesco != null) {
									//Log::info("Ingresa a result->parentesco != null");
									$personal->id_tipo_beneficiario = $tipoBenef->getTipoBeneficiarioDescripcion($result->parentesco)->id;
								} else {
									//Log::info("Ingresa a resultNewTitular->parentesco != null");
									$personal->id_tipo_beneficiario = $id_tipo_beneficiario;
								}
							} catch (Exception $e) {
								//Log::info("Ingresa a catch->Exception");
								$personal['id_situacion'] = $result->situacion;
								$personal['id_grado'] = $result->grado;
								$personal['grado'] = $result->grado;
								$personal['nomtipdoctitular'] = $result->nomtipdoctitular;
								if ($result->nrodoctitular == '00000000') {
									$personal['situaciontitular'] = 'SOBREVIVIENTE';
								} else {
									$personal['situaciontitular'] = $result->situacion;
								}
								$personal['nrodoctitular'] = $result->nrodoctitular;
								$personal['apepattitular'] = $result->apepattitular;
								$personal['apemattitular'] = $result->apemattitular;
								$personal['apecastitular'] = $result->apecastitular;
								$personal['nomtitular'] = $result->nomtitular;
								$personal['ciptitular'] = $result->cip;
								$personal['ubigeotitular'] = $result->ubigeo;
								$personal['gradotitular'] = $result->grado;
								$personal['discapacidadtitular'] = $result->discapacidad;
								$personal['otrosegurotitular'] = $result->otroseguro;
								$personal['unidad_pnptitular'] = $result->unidad_pnp;
								$personal['sexotitular'] = $result->nomsexo;
								$personal['estado_titular'] = $result->estado;
								$personal['estado_seguro'] = $result->estado;
								$personal['caducidad_seguro'] = $result->caducidad;
								$personal['fecnacafiliado'] = $result->fecnacafiliado;
								$personal['codigoMensaje'] = '';
								$personal['nompaisdelafiliado'] = 'PER';

								$tipoBenef = new \App\Models\Tipo_beneficiario;
								if ($result->parentesco != null) {
									$personal['id_tipo_beneficiario'] = $tipoBenef->getTipoBeneficiarioDescripcion($result->parentesco)->id;
								} else {
									$personal['id_tipo_beneficiario'] = $id_tipo_beneficiario;
								}
							}
						} else {
							//Log::info("Ingresa a resultNewTitular->cip = null ");
							try {
								$personal->id_grado = $result->grado;
								$personal->grado = $result->grado;
								$personal->id_situacion    = $result->situacion;
								$personal->nomtipdoctitular = $result->nomtipdoctitular;
								$personal->nrodoctitular = $result->nrodoctitular;
								$personal->apepattitular = $result->apepattitular;
								$personal->apemattitular = $result->apemattitular;
								$personal->apecastitular = $result->apecastitular;
								$personal->nomtitular = $result->nomtitular;
								$personal->ciptitular = $result->cip;
								$personal->ubigeotitular = $result->ubigeo;
								$personal->gradotitular = '-';
								if ($result->nrodoctitular == '00000000') {
									//Log::info("Ingresa a result->nrodoctitular == '00000000'1 ");
									$personal->situaciontitular    = 'SOBREVIVIENTE';
								} else {
									//Log::info("Ingresa a result->nrodoctitular != '00000000'1 ");
									$personal->situaciontitular    = $result->situacion;
								}
								$personal->discapacidadtitular = $result->discapacidad;
								$personal->otrosegurotitular = $result->otroseguro;
								$personal->unidad_pnptitular = $result->unidad_pnp;
								$personal->sexotitular = '-';
								$personal->estado_seguro = $result->estado;
								$personal->caducidad_seguro = $result->caducidad;

								$tipoBenef = new \App\Models\Tipo_beneficiario;
								if ($result->parentesco != null) {
									$personal->id_tipo_beneficiario = $tipoBenef->getTipoBeneficiarioDescripcion($result->parentesco)->id;
								} else {
									$personal->id_tipo_beneficiario = $id_tipo_beneficiario;
								}
							} catch (Exception $e) {
								$personal['id_grado'] = $result->grado;
								$personal['grado'] = $result->grado;
								$personal['id_situacion'] = $result->situacion;
								$personal['nomtipdoctitular'] = $result->nomtipdoctitular;
								$personal['nrodoctitular'] = $result->nrodoctitular;
								$personal['apepattitular'] = $result->apepattitular;
								$personal['apemattitular'] = $result->apemattitular;
								$personal['apecastitular'] = $result->apecastitular;
								$personal['nomtitular'] = $result->nomtitular;
								$personal['ciptitular'] = $result->cip;
								$personal['ubigeotitular'] = $result->ubigeo;
								$personal['gradotitular'] = '-';
								if ($result->nrodoctitular == '00000000') {
									$personal['situaciontitular'] = 'SOBREVIVIENTE';
								} else {
									$personal['situaciontitular'] = $result->situacion;
								}
								$personal['discapacidadtitular'] = $result->discapacidad;
								$personal['otrosegurotitular'] = $result->otroseguro;
								$personal['unidad_pnptitular'] = $result->unidad_pnp;
								$personal['sexotitular'] = '-';
								$personal['estado_seguro'] = $result->estado;
								$personal['caducidad_seguro'] = $result->caducidad;
								$personal['codigoMensaje'] = '';
								$personal['nompaisdelafiliado'] = 'PER';

								$tipoBenef = new \App\Models\Tipo_beneficiario;
								if ($result->parentesco != null) {
									$personal['id_tipo_beneficiario'] = $tipoBenef->getTipoBeneficiarioDescripcion($result->parentesco)->id;
								} else {
									$personal['id_tipo_beneficiario'] = $id_tipo_beneficiario;
								}
							}
						}
					}
				} else {
					//Log::info("Ingresa a redatos dirrehum");
					//datos de Dirrehum
					if ($id_tipo_doc_ident == "1") { //1, dni titulares, 2 cip del titular, 3 dni de familiar
						$Dirrehum = new \App\Ws\Dirrehum;
						try {
							$result = $Dirrehum->BuscarTitularFamiliar($id_tipo_doc_ident, $dni);
							$personal_d = $result->BuscarTitularFamiliarResult->TitularFamiliar;
						} catch (Exception $e) {
							Log::error("Error Servicio Dirrehum: " . $e->getMessage());
							$personal_d = null;
						}
						if (!empty($personal_d)) {
							if (count($personal_d) > 1) {
								$value = Str::contains($personal_d[0]->CARNE, 'incorrecto');
							} else {
								$value = Str::contains($personal_d->CARNE, 'incorrecto');
							}
							if ($value == false) {
								if (isset($personal_d->CARNE)) {
									if ($personal == null) {
										$personal = $personal_d;
										$personal->id_tipo_beneficiario = $personal_d->TIPO;
										$personal->id_grado = $personal_d->GRADO;
									} else {
										//$personal = $personal_d;			
										$personal->cip = $personal_d->CARNE;
										$personal->id_situacion = $personal_d->SITUACION;
										$personal->id_tipo_beneficiario = $personal_d->TIPO;
										$personal->id_grado = $personal_d->GRADO;
									}
								}
							}
						}
					} //FIN datos de Dirrehum

				}
			}
		}

		return json_encode($personal);
	}

	public function obtener_datos_persona_ajax2(Request $request)
	{
		$id_tipo_doc_ident = $request->tipo_documento;
		$dni = $request->dni;
		$personal = null;
		$objAsegurado = new Paciente();

		//VALIDA TIPO DOC NULO EN ASEGURADOS
		$tipoDocNullAse = $objAsegurado->GetAseguradosSoloDNI($dni);
		//dd($tipoDocNullAse);
		if ($tipoDocNullAse) {
			if ($tipoDocNullAse->id_tipo_doc_ident == null) {
				$objAseguradoEdit = Paciente::find($tipoDocNullAse->id);
				if ($objAseguradoEdit->tipo_doc_ident == 'DNI') {
					$objAseguradoEdit->id_tipo_doc_ident = '1';
					$objAseguradoEdit->save();
				} else if ($objAseguradoEdit->tipo_doc_ident == 'CARNET DE EXTRANJERIA' || $objAseguradoEdit->tipo_doc_ident == 'Carnet Extranjeria') {
					$objAseguradoEdit->id_tipo_doc_ident = '2';
					$objAseguradoEdit->save();
				} else if ($objAseguradoEdit->tipo_doc_ident == 'PASAPORTE' || $objAseguradoEdit->tipo_doc_ident == 'Pasaporte') {
					$objAseguradoEdit->id_tipo_doc_ident = '3';
					$objAseguradoEdit->save();
				} else if ($objAseguradoEdit->tipo_doc_ident == 'DOCUMENTO DE IDENTIDAD EXTRANJERO' || $objAseguradoEdit->tipo_doc_ident == 'Documento de Identidad extranjero') {
					$objAseguradoEdit->id_tipo_doc_ident = '4';
					$objAseguradoEdit->save();
				}
			}
		}
		//datos del ASEGURADO
		$modelmedico = new Paciente();
		$asegurado = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident); //! verifica si esta en la tabla aseugrad	
		//dd($asegurado);
		$id_tipo_beneficiario = '';

		if ($asegurado != null) {
			$id_asegurado = $asegurado->id;
			$model_personadatosadicional = new Persona_datos_adicional();
			$personadatosadicional = $model_personadatosadicional->persona_datos_Adicionales($id_asegurado);

			if ($personadatosadicional != null) {
				$personal = $personadatosadicional;
			} else {
				$personal = $asegurado;
			}
			$id_tipo_beneficiario = $asegurado->id_tipo_beneficiario;
		} else {
			try {
				$objAseguradoHistoria = new Asegurado_historia;
				$objAseguradoHistoria->id_asegurado = $tipoDocNullAse->id;
				$objAseguradoHistoria->id_establecimiento = Auth::user()->id_establecimiento;
				$objAseguradoHistoria->nro_historia = $dni;
				$objAseguradoHistoria->id_estado_reg = 1;
				$objAseguradoHistoria->created_us = Auth::user()->id;
				$objAseguradoHistoria->save();

				$personal = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident);   //08085318
			} catch (\Exception $e) {
				$personal = null;
			}
		}
		return json_encode($personal);
	}
	public function obtener_datos_persona_ajax_local(Request $request)
	{
		$id_tipo_doc_ident = $request->tipo_documento;
		$dni = $request->dni;

		$personal = null;
		//datos del ASEGURADO
		$modelmedico = new Paciente();
		$asegurado = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident); //! verifica si esta en la tabla asegurado

		if ($asegurado != null) {
			$id_asegurado = $asegurado->id;
			$model_personadatosadicional = new Persona_datos_adicional();
			$personadatosadicional = $model_personadatosadicional->persona_datos_Adicionales($id_asegurado);

			if ($personadatosadicional != null) {
				$personal = $personadatosadicional;
			} else {
				$personal = $asegurado;
			}
		}
		return json_encode($personal);
	}

	public function obtener_datos_paciente_ajax_anterior(Request $request)
	{
		$id_tipo_doc_ident = $request->tipo_documento;
		$dni = $request->dni;
		//$tipo_busqueda = $request->tipo_busqueda; //reniec, tabla_asegurados, dirrehum, saludpol, siteds

		$personal = null;
		$modelmedico = new Paciente();
		$asegurado = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident);

		//datos del titular
		if ($asegurado != null) {
			$id_asegurado = $asegurado->id;
			//nro historia
			$id_establecimiento = Auth::user()->id_establecimiento;
			$modelo_historia = Asegurado_historia::where('id_asegurado', '=', $asegurado->id)->where('id_establecimiento', '=', $id_establecimiento)->first();
			if ($modelo_historia != null) {
				$asegurado->nro_historia = $modelo_historia->nro_historia;
			}

			//datos adicionales
			$model_personadatosadicional = new Persona_datos_adicional();
			$personadatosadicional = $model_personadatosadicional->persona_datos_Adicionales($id_asegurado);

			//DATOS TITULAR
			if ($asegurado->id_tipo_beneficiario != "TITULAR" && $asegurado->id_tipo_beneficiario != "CIVIL") { //NO ES TITULAR, ni CIVIL
				$model_personadatosadicional = new Persona_datos_adicional();
				$persona_titular = $model_personadatosadicional->persona_datos_Adicionales($asegurado->id_asegurado_titular);

				$asegurado->nomtipdoctitular = $persona_titular->tipo_doc_ident;
				$asegurado->nrodoctitular = $persona_titular->nro_doc_ident;
				$asegurado->apepattitular = $persona_titular->paterno;
				$asegurado->apemattitular = $persona_titular->materno;
				//$asegurado->apecastitular = $persona_titular->apecastitular;
				$asegurado->nomtitular    = $persona_titular->nombre;
			}

			if ($personadatosadicional != null) {
				//$personal= $personadatosadicional;
				echo json_encode($personadatosadicional);
				exit();
			} else {
				$personal = $asegurado;
			}
		} else {
			$personal = $asegurado;
		}

		//datos de la Reniec
		if ($id_tipo_doc_ident == "1" && $personal == null) {
			$WsReniec = new \App\Ws\Reniec;
			$personal_reniec = $WsReniec->BuscarDNI($dni);
			$personal = json_decode($personal_reniec, true);
		}

		//datos de Saludpol
		$Saludpol = new \App\Ws\Saludpol;
		$result = $Saludpol->BuscarTitularFamiliar($id_tipo_doc_ident, $dni, '');
		if (isset($result->cip)) {
			if ($personal == null) {
				$personal = $result;
				$personal->id_tipo_beneficiario = $result->parentesco;
				$personal->id_grado = $result->grado;
			} else {
				//$personal = $result;		
				$personal->cip = $result->cip;
				$personal->id_situacion = $result->situacion;
				$personal->id_tipo_beneficiario = $result->parentesco;
				$personal->id_grado = $result->grado;
				if ($personal->id_tipo_beneficiario != "TITULAR") { //NO ES TITULAR
					$personal->nomtipdoctitular = $result->nomtipdoctitular;
					$personal->nrodoctitular = $result->nrodoctitular;
					$personal->apepattitular = $result->apepattitular;
					$personal->apemattitular = $result->apemattitular;
					//$personal->apecastitular = $result->apecastitular;
					$personal->nomtitular    = $result->nomtitular;
				}
			}
		} else {

			//datos de Dirrehum
			if ($id_tipo_doc_ident == "1") { //1, dni titulares, 2 cip del titular, 3 dni de familiar
				$Dirrehum = new \App\Ws\Dirrehum;
				$result = $Dirrehum->BuscarTitularFamiliar($id_tipo_doc_ident, $dni);
				$personal_d = $result->CallBuscarTitularFamiliarResult->TitularFamiliarResponse;
				//echo json_encode($result->CallBuscarTitularFamiliarResult->TitularFamiliarResponse, true);			
				if (isset($personal_d->CARNE)) {
					if ($personal == null) {
						$personal = $personal_d;
						$personal->id_tipo_beneficiario = $personal_d->TIPO;
						$personal->id_grado = $personal_d->GRADO;
					} else {
						//$personal = $personal_d;			
						$personal->cip = $personal_d->CARNE;
						$personal->id_situacion = $personal_d->SITUACION;
						$personal->id_tipo_beneficiario = $personal_d->TIPO;
						$personal->id_grado = $personal_d->GRADO;
					}
				}
			} //FIN datos de Dirrehum

		}
		echo json_encode($personal);
	}
	public function obtener_datos_paciente_ajax(Request $request)
	{
		$id_tipo_doc_ident = $request->tipo_documento;
		$dni = $request->dni;
		$flag_api_sp = $request->usarApi;
		$origen = $request->origen;
		if($flag_api_sp == 'api'){
			$wscontroller   =   new \App\Http\Controllers\WebServiceController();
			$origen_f = !empty($origen) ? 'Saludpol_API_'.$origen : 'Saludpol_API2';
			$request = new Request(['tipoDocumento' => $id_tipo_doc_ident, 'numeroDocumento' => $dni,'origen'=> $origen_f]);
			$response_saludpol = $wscontroller->consultarSaludpol2($request);
		}else{
		//$tipo_busqueda = $request->tipo_busqueda; //reniec, tabla_asegurados, dirrehum, saludpol, siteds
			$ws_saludpol = new Saludpol();
			$response_saludpol = $ws_saludpol->BuscarTitularFamiliar($id_tipo_doc_ident, $dni);
			$origen_f = !empty($origen) ? 'Saludpol_ws_'.$origen : 'Saludpol';
			$log = new App\Models\LogWs;
			$log->ws = $origen_f;
			$log->tipo_doc_buscado = $id_tipo_doc_ident;
			$log->doc_buscado = $dni;
			$log->fechafin_afiliacion = !empty($result->caducidad) ? Carbon::createFromFormat('Ymd', $result->caducidad)->format('Y-m-d'): null;
			$log->estado_afiliacion = !empty($result->estado) ? $result->estado : null;
			$log->created_at = Carbon::now();
			$log->created_us = Auth::user()->id;
			$log->save();
		}

		// $personal = null;
		$data_asegurado = array();
		if ($response_saludpol != null) {

			if ($flag_api_sp == 'api' && isset($response_saludpol->success) && $response_saludpol->success === false) {

				$data_asegurado[] = 'ERROR_API_SALUDPOL';
				$data_asegurado[] = [
					'status'  => $response_saludpol->status,
					'message' => $response_saludpol->message,
					'error'   => $response_saludpol->error,
				];

			}elseif (isset($response_saludpol->cip)) {

				$data_asegurado[] = "00";
				$data_asegurado[] = $response_saludpol;

				if ($id_tipo_doc_ident == 1) {

					/*$sitio = @fopen(env('RENIEC_URI'), 'r');
					if ($sitio) {
						$WsReniec = new \App\Ws\Reniec;
						$response_reniec = $WsReniec->BuscarDNI($dni);
						$response_reniec_decode = json_decode($response_reniec);
						
						if ($response_reniec_decode->codigoMensaje == "00") {
							$data_asegurado[] = "00";
							$data_asegurado[] = $response_reniec_decode;
						} else {
							$data_asegurado[] = "NO_DATA_RENIEC";
							$data_asegurado[] = null;
						}
					} else {*/
					$data_asegurado[] = "NO_DATA_RENIEC: Sin conexion";
					$data_asegurado[] = null;
					//}

				} else {
					$data_asegurado[] = "NO_DATA_RENIEC";
					$data_asegurado[] = null;
				}

				if ($response_saludpol->parentesco == "TITULAR") {
					try {
						$sitio = @fopen(env('DIRREHUM_URI'), 'r');
						if ($sitio) {
							$ws_dirrehum = new \App\Ws\Dirrehum;
							$response_dirrehum = $ws_dirrehum->BuscarTitularFamiliar($id_tipo_doc_ident, $dni);
							$personal_d = $response_dirrehum->BuscarTitularFamiliarResult->TitularFamiliar;

							if (isset($personal_d)) {
								if (is_array($personal_d) && count($personal_d) > 0) {
									$personal_d = $personal_d[0];
								}
								$GRADO = isset($personal_d->GRADO) ? $personal_d->GRADO : null;
								$SITUACION = isset($personal_d->SITUACION) ? $personal_d->SITUACION : null;
								$requestUpdateDirrehum = ['grado' => $GRADO, 'situacion_paciente' => $SITUACION];
								$model_asegurado = new Asegurado();
								$model_asegurado->updateDataDirrehumByNroDocument($dni, $requestUpdateDirrehum);

								$data_asegurado[] = "00";
								$data_asegurado[] = $personal_d;
							} else {
								$data_asegurado[] = "NO_DATA_DIRREHUM";
								$data_asegurado[] = null;
							}
						} else {
							$data_asegurado[] = "NO_DATA_DIRREHUM: Sin conexion";
							$data_asegurado[] = null;
						}
					} catch (Exception $e) {
						// En caso de excepción (problema con SOAP u otro error), también manejarlo
						$data_asegurado[] = "NO_DATA_DIRREHUM: Sin conexion";
						$data_asegurado[] = null; // Datos nulos
					}
				} else {
					$data_asegurado[] = "NO_DATA_DIRREHUM";
					$data_asegurado[] = null;
				}

				/* Validar id_tipo_doc_ident del paciente */
				$tipo_doc_ident_saludpol = $response_saludpol->nomtipdocafiliado;
				$asegurado = Asegurado::where('nro_doc_ident', $dni)
					->where('tipo_doc_ident', $tipo_doc_ident_saludpol)
					->where('id_estado_reg', 1)
					->first();

				$id_tipo_doc_ident_update = 0;
				switch ($tipo_doc_ident_saludpol) {
					case "DNI":
						$id_tipo_doc_ident_update = 1;
						break;
					case "CE":
						$id_tipo_doc_ident_update = 2;
						break;
					case "PAS":
						$id_tipo_doc_ident_update = 3;
						break;
					case "DIE":
						$id_tipo_doc_ident_update = 4;
						break;
				}

				if (empty($asegurado->id_tipo_doc_ident) && $asegurado != null) {
					$asegurado = Asegurado::find($asegurado->id);
					$asegurado->id_tipo_doc_ident = $id_tipo_doc_ident_update;
					$asegurado->save();
				}
				/* Fin de Validar id_tipo_doc_ident del paciente */


				$model_paciente = new Paciente();
				$asegurado = $model_paciente->GetAseguradoByIdTipoDocumentoYNroDocumento($id_tipo_doc_ident, $dni);

				//datos del titular
				if ($asegurado != null) {
					$id_asegurado = $asegurado->id;
					//nro historia
					$id_establecimiento = Auth::user()->id_establecimiento;
					$modelo_historia = Asegurado_historia::where('id_asegurado', '=', $asegurado->id)->where('id_establecimiento', '=', $id_establecimiento)->first();
					if ($modelo_historia != null) {
						$asegurado->nro_historia = $modelo_historia->nro_historia; //Siempre será la historia de la IPRESS en donde se accede ( historia local )
					}

					//datos adicionales
					$model_personadatosadicional = new Persona_datos_adicional();
					$personadatosadicional = $model_personadatosadicional->persona_datos_Adicionales($id_asegurado);

					//DATOS TITULAR
					if ($asegurado->id_asegurado_titular != null && $asegurado->id_asegurado_titular > 0) { //NO ES TITULAR, ni CIVIL
						$model_personadatosadicional = new Persona_datos_adicional();
						$persona_titular = $model_personadatosadicional->persona_datos_Adicionales($asegurado->id_asegurado_titular);

						if (count($persona_titular) > 0) {
							$asegurado->nomtipdoctitular = $persona_titular->tipo_doc_ident;
							$asegurado->nrodoctitular = $persona_titular->nro_doc_ident;
							$asegurado->apepattitular = $persona_titular->paterno;
							$asegurado->apemattitular = $persona_titular->materno;
							//$asegurado->apecastitular = $persona_titular->apecastitular;
							$asegurado->nomtitular    = $persona_titular->nombre;
							$asegurado->cip_titular    = $persona_titular->cip;
							$asegurado->situacion_titular    = $persona_titular->situacion;
							$asegurado->grado_titular    = $persona_titular->grado;
						}
					}

					$data_asegurado[] = "00";
					$data_asegurado[] = $asegurado;
				} else {
					$data_asegurado[] = "NO_DATA_BD_LOCAL";
					$data_asegurado[] = null;
				}
			} else {
				$data_asegurado[] = "NO_DATA_SALUDPOL";
			}
		} else {
			$data_asegurado[] = "SIN_CONEXION_SALUDPOL";
		}
		// dd($data_asegurado);
		echo json_encode($data_asegurado);
	}

	public function editar_paciente($id){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$ruta = "all_asegurados";
		
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

        $permiso = null;
        if (!empty($menu)):
            $permiso = $menu[0];
        endif;

        if (is_null($permiso) || $permiso->lectura == "0"):
            return redirect('/dashboard')->withInput()->withErrors(array('No cuenta con permisos para acceder a esta opción.'));
        endif;

		$id_asegurado = $id;
		$id_user = Auth::user()->id;
		$user_model = new User;
		$user_data = $user_model->GetById($id_user);

		$establecimiento_model = new Establecimiento();
		$establecimiento_user_data = $establecimiento_model->GetByIdEstablecimiento($user_data->id_establecimiento);

		$modelpaciente = new Paciente();
		$historias_por_establecimiento = $modelpaciente->paciente_yaregistrado("", "", "", $id);
		
		//datos del paciente
		$model_paciente = new Paciente();
		$personal = $model_paciente->GetAseguradoById($id);

		//datos del titular
		$id_asegurado_titular = $personal->id_asegurado_titular;
		$model_personadatosadicional = new Persona_datos_adicional();
		$personadatosadicional = null;
		if ($id_asegurado_titular > 0) {
			$personadatosadicional = $model_personadatosadicional->persona_datos_Adicionales($id_asegurado);
		}

		$model_beneficiario = new \App\Models\Tipo_beneficiario;
		$tipo_beneficiario = $model_beneficiario->ShowTipo_beneficiario();

		$model_tipo_sangre = new \App\Models\Tipo_sangre();
		$tipo_sangre = $model_tipo_sangre->ShowAll();

		$model_categoria = new \App\Models\Titular_categoria();
		$categoria = $model_categoria->ShowTipoCategoria();

		$model_grado = new \App\Models\Titular_grado();
		$grado = $model_grado->ShowTipoGrado();

		$model_situacion = new \App\Models\Situacion();
		$situacion = $model_situacion->ShowSituacion();

		$model_unidadpago = new \App\Models\Unidadpago();
		$unidadpago = $model_unidadpago->ShowUnidadpago();

		$model_tipo_documento_identidad = new \App\Models\Tipo_documento_identidad();
		$tipo_documento_identidad = $model_tipo_documento_identidad->ShowTipoDocumentoIdentidad();

		$model_departamento = new \App\Models\Departamento();
		$departamento = $model_departamento->ShowAll();

		$provincia = null;
		if ($personal->id_departamento != null):
			$model_provincia = new \App\Models\Provincia();
			$provincia = $model_provincia->ShowAllByIdDepartamento($personal->id_departamento);
		endif;

		$distrito = null;
		if ($personal->id_provincia != null):
			$model_distrito = new \App\Models\Distrito();
			$distrito = $model_distrito->ShowDistrito($personal->id_provincia);
		endif;

		$provincia_nac = null;
		if ($personal->id_departamento_nacimiento != null):
			$model_provincia = new \App\Models\Provincia();
			$provincia_nac = $model_provincia->ShowAllByIdDepartamento($personal->id_departamento_nacimiento);
		endif;

		$distrito_nac = null;
		if ($personal->id_provincia_nacimiento != null):
			$model_distrito = new \App\Models\Distrito();
			$distrito_nac = $model_distrito->ShowDistrito($personal->id_provincia_nacimiento);
		endif;

		$model_grado = new \App\Models\Grado_instruccion();
		$grado_inst = $model_grado->ShowGradoInstruccion();

		$model_ocupacion = new \App\Models\Ocupacion();
		$ocupacion = $model_ocupacion->ShowOcupacion();

		$model_estadocivil = new \App\Models\Estado_civil();
		$estado_civil = $model_estadocivil->ShowEstadoCivil();

		$model_religion = new \App\Models\Religion();
		$religion = $model_religion->ShowReligion();

		$model_etnia = new \App\Models\Etnia();
		$etnia = $model_etnia->ShowEtnia();

		$model_tipo_parentescos = new \App\Models\Tipo_parentescos();
		$parentesco = $model_tipo_parentescos->getTipo_parentescos();

		$model_tutor  =  new \App\Models\Tutor();
		$tutor = null;
		if (!is_null($personal->id_tutor)) {
			$tutor = $model_tutor->obtener_tutor($personal->id_tutor);
		}

		$registro_tutor = null;
		if (!is_null($personal->id_tutor)) {
			$registro_tutor = Tutor::find($personal->id_tutor);
		}

		$titular_asegurado = DB::table('asegurados')->where('id', $id_asegurado)->first();
		// $grados_lista = DB::table('asegurados')->select('id_grado as id', 'grado')->distinct()->orderBy('grado', 'asc')->get();
		$grados_lista = DB::table('asegurados')->select('grado')->whereNotNull('grado')->whereRaw("TRIM(grado) <> ''")->where('grado', '<>', '-')->distinct()->orderBy('grado', 'asc')->get();
	    // dd($grados_lista);
		// $situaciones_lista = DB::table('asegurados')->select('id_situacion as id', 'situacion_paciente')->distinct()->orderBy('situacion_paciente', 'asc')->get();
		$situaciones_lista = DB::table('asegurados')->select('situacion_paciente')->whereNotNull('situacion_paciente')->whereRaw("TRIM(situacion_paciente) <> ''")->where('situacion_paciente', '<>', '-')
	                       ->distinct()->orderBy('situacion_paciente', 'asc')->get();
	
		if ($titular_asegurado !== null) {
			$cips = $titular_asegurado->cip;
			$grado_seleccionado = $titular_asegurado->grado;
			$situacion_seleccionada = $titular_asegurado->situacion_paciente;
		} else {
			$cips = null;
			$grado_seleccionado = null;
			$situacion_seleccionada = null;
		}

		$model_hospitalizacion = new Hospitalizacione();
		$ultimo = Hospitalizacione::whereNotNull('fecha_ingreso')->orderBy('fecha_ingreso', 'desc')->first();

		$fecha_ini = $ultimo ? Carbon::parse($ultimo->fecha_ingreso)->subDay()->format('d-m-Y') : '';
		$fecha_fin = Carbon::now()->format('d-m-Y');

		$establecimientos = $establecimiento_model->ShowAll();

		$tiene_afiliacion = DB::table('asegurados')->where('id', $id)->whereNotNull('estado_afiliacion')->exists();

		return view('frontend.pacientes.editar_paciente2',compact('historias_por_establecimiento','personal','personadatosadicional','tipo_beneficiario','categoria','grados_lista','situaciones_lista','unidadpago','tipo_documento_identidad','id_asegurado','tipo_sangre','departamento','provincia','distrito','tutor','registro_tutor','fecha_ini','fecha_fin','grado_inst','ocupacion','estado_civil','religion','etnia','parentesco','cips','id_grado','id_situacion','tit_cip','grado_seleccionado','situacion_seleccionada','establecimientos','user_data','establecimiento_user_data','permiso','provincia_nac','distrito_nac','tiene_afiliacion'));
		//return view('frontend.pacientes.editar_paciente',compact('historias_por_establecimiento','personal','personadatosadicional','tipo_beneficiario','categoria','grados_lista','situaciones_lista','unidadpago','tipo_documento_identidad','id_asegurado','tipo_sangre','departamento','provincia','distrito','tutor','registro_tutor','fecha_ini','fecha_fin','grado_inst','ocupacion','estado_civil','religion','etnia','parentesco','cips','id_grado','id_situacion','tit_cip','grado_seleccionado','situacion_seleccionada','establecimientos','user_data','establecimiento_user_data','permiso'));
	}

	public function titular_busqueda($tipo_busqueda, $valor)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$model_pda = new Persona_datos_adicional();
		$p[] = $tipo_busqueda;
		$p[] = $valor;

		$data = $model_pda->consulta_titular($p);
		echo json_encode($data);
	}

	public function consulta_carga_familiar($tipo_busqueda, $id_asegurado_titular)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$model_pda = new Persona_datos_adicional();
		$p[] = $tipo_busqueda;
		$p[] = $id_asegurado_titular;

		$data = $model_pda->consulta_carga_familiar($p);
		echo json_encode($data);
	}

	public function modal_carga_familiar($tipo_busqueda, $id_asegurado_titular)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$model_pda = new Persona_datos_adicional();
		$p[] = $tipo_busqueda;
		$p[] = $id_asegurado_titular;

		$data = $model_pda->consulta_carga_familiar($p);
		return view('frontend.pacientes.modal_titulares', compact('data'));
	}

	public function listar_paciente_ajax(Request $request)
	{
		$model_paciente = new App\Models\Paciente;
		$p[] = $request->nro_documento;
		$p[] = $request->apellidos;
		$p[] = $request->nombres;
		$p[] = $request->NumeroPagina;
		$p[] = $request->NumeroRegistros;
		$data = $model_paciente->listar_paciente_ajax($p);
		$iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

		$result["PageStart"] = $request->NumeroPagina;
		$result["pageSize"] = $request->NumeroRegistros;
		$result["SearchText"] = "";
		$result["ShowChildren"] = true;
		$result["iTotalRecords"] = $iTotalDisplayRecords;
		$result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
		$result["aaData"] = $data;
		echo json_encode($result);
	}

	public function all_movimiento_hc()
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$id_farmacia = Auth::user()->id_farmacia;

		$model_farmacia = new Farmacia;
		$model_historia = new Historia;
		$model_consultorio = new Consultorio;
		//----------------------------------------------------------------------        
		$date = Carbon::now();
		$fechaHasta = $date->format('d-m-Y');
		$date->modify('first day of this month');
		$fechaDesde = $date->format('d-m-Y');
		$farmacia = $model_farmacia->GetIpress($id_farmacia);
		$id_consultorio = Auth::user()->id_consultorio;

		if ($id_consultorio != 0):
			$movimiento_historias = $model_historia->allMovimientoByEstablecimiento($farmacia->id, $id_consultorio, $fechaDesde, $fechaHasta);
			$nombre_consultorio = $model_consultorio->GetNombreById($id_consultorio);

			$date = Carbon::now();
			$fecha = $date->format('d-m-Y');
			$fechaActual = Carbon::parse($fecha);

			$ruta = Route::getCurrentRoute()->getPath();

			if (count($ruta = explode("/", $ruta)) > 1)
				$ruta = $ruta[0];
			$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
			$permiso = $menu[0];

			if ($permiso->lectura == "")
				return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
			return view('frontend.hc.all_movimiento_hc', compact('movimiento_historias', 'farmacia', 'permiso', 'fechaActual', 'fechaDesde', 'fechaHasta', 'nombre_consultorio'));
		else:
			return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
		endif;
	}

	public function busca_movimiento_hc(Request $request)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$id_farmacia = Auth::user()->id_farmacia;

		$model_farmacia = new Farmacia;
		$model_historia = new Historia;
		$model_consultorio = new Consultorio;
		//----------------------------------------------------------------------        
		$farmacia = $model_farmacia->GetIpress($id_farmacia);
		$id_consultorio = Auth::user()->id_consultorio;
		//-----------------------------------------------
		if ($request->fechaDesde != "") {
			$fechaDesde = $request->fechaDesde;
			$fechaHasta = $request->fechaHasta;
		} else {
			$date = Carbon::now();
			$fechaHasta = $date->format('d-m-Y');
			$date->modify('first day of this month');
			$fechaDesde = $date->format('d-m-Y');
		}

		$almacen = 1;
		$movimiento_historias = $model_historia->allMovimientoByEstablecimiento($farmacia->id, $id_consultorio, $fechaDesde, $fechaHasta);
		$nombre_consultorio = $model_consultorio->GetNombreById($id_consultorio);

		$date = Carbon::now();
		$fecha = $date->format('d-m-Y');
		$fechaActual = Carbon::parse($fecha);

		$ruta = "all_movimiento_hc";
		if (count($ruta = explode("/", $ruta)) > 1);
		$ruta = $ruta[0];
		$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
		$permiso = $menu[0];

		if ($permiso->lectura == "")
			return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
		return view('frontend.hc.all_movimiento_hc', compact('movimiento_historias', 'farmacia', 'permiso', 'fechaActual', 'almacen', 'fechaDesde', 'fechaHasta', 'nombre_consultorio'));
	}

	public function add_hc_registro_salida($id_user)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$model_farmacia = new Farmacia;
		$model_historia = new Historia;

		$model_kardex = new Kardexe();
		$fechaServidor = $model_kardex->getFechaServidor();

		$hoy = date('Y-m-j');
		$ano_mov = date('Y');
		$farmacia = $model_farmacia->GetIpressByIduser($id_user);

		$rango_registro = 30;
		$id_farmacia = $farmacia->id;
		$nro_mov = $model_historia->getNroMovHC(Auth::user()->id_establecimiento, Auth::user()->id_consultorio);

		$fecha_hace_treinta_dias = strtotime('-' . $rango_registro . ' day', strtotime($hoy));
		$fecha_hace_treinta_dias = date('d-m-Y', $fecha_hace_treinta_dias);

		$origen = Auth::user()->id_consultorio;
		$id = 0;
		$flag = 0;
		if (Auth::user()->id_consultorio == 0 or Auth::user()->tipo_registro != 8):
			$flag = 1;
		else:
			$flag = 0;
		endif;

		$model_consultorio = new Consultorio();
		$consultorios = $model_consultorio->ShowConsultoriosAllX(Auth::user()->id_consultorio, Auth::user()->id_establecimiento);
		$nombre_consultorio = $model_consultorio->GetNombreById(Auth::user()->id_consultorio);

		//----------------------------------------------------------------------

		return view('frontend.hc.documento_salida_hc', compact('farmacia', 'id_farmacia', 'fecha_hace_treinta_dias', 'rango_registro', 'nro_mov', 'fechaServidor', 'consultorios', 'ano_mov', 'flag', 'origen', 'id', 'nombre_consultorio'));
	}

	public function registrar_salida_hc(Request $request)
	{
		$model_hc = new \App\Models\Historia;

		$id_mov_hc = $model_hc->registrarMovimientoHistoria($request->id_establecimiento, $request->id_consultorio, $request->nro_mov, $request->fecha, $request->id_area, $request->id_turno, $request->id_tipo_citado, $request->id_tipo_doc, $request->observacion, $request->id_origen, $request->responsable_origen, $request->responsable_destino, $request->id_user, $request->ano_mov, $request->id);

		if ($request->id != 0)
			$id_mov_hc = $request->id;

		$date = Carbon::now();
		$fechaDesde = $date->format('d-m-Y');
		$fechaHasta = $date->format('d-m-Y');


		Session::flash('flash_message', 'Se registro el documento del movimiento de la historia, continuar con el registro de los historias a enviar!');

		return redirect('/registrar_movimiento_hc/' . $id_mov_hc . '/' . $request->id_origen . '/' . $fechaDesde . '/' . $fechaHasta);
	}



	public function editar_movimiento_hc($id)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$model_farmacia = new Farmacia;
		$model_historia = new Historia;

		$model_kardex = new Kardexe();
		$fechaServidor = $model_kardex->getFechaServidor();

		$hoy = date('Y-m-j');
		$ano_mov = date('Y');
		$farmacia = $model_farmacia->GetIpressByIduser(Auth::user()->id);


		$movimiento_historias = $model_historia->GetMovimientoHCById($id);

		$rango_registro = 30;
		$id_farmacia = $farmacia->id;
		//$nro_mov = $model_historia->getNroMovHC($id_farmacia);


		$fecha_hace_treinta_dias = strtotime('-' . $rango_registro . ' day', strtotime($hoy));
		$fecha_hace_treinta_dias = date('d-m-Y', $fecha_hace_treinta_dias);

		$model_consultorio = new Consultorio;
		$id_establecimiento = Auth::user()->id_establecimiento;

		$model_medico = new Medico;
		$responsable_destinos = $model_medico->profesionales($id_establecimiento, $movimiento_historias->id_consultorio);
		$nro_mov = $model_historia->getNroMovHC($id_establecimiento, $movimiento_historias->id_consultorio);

		$lugar = $movimiento_historias->id_consultorio;

		$origen = Auth::user()->id_consultorio;
		$flag = 0;
		if (Auth::user()->flagestablecimientos == 1):
			$origen = 198;
		else:
			if (Auth::user()->id_consultorio == 0 or Auth::user()->tipo_registro != 8):
				$flag = 1;
			else:
				$flag = 0;
			endif;
		endif;

		$model_consultorio = new Consultorio();
		$consultorios = $model_consultorio->ShowConsultoriosAllX(Auth::user()->id_consultorio, $id_establecimiento);
		$nombre_consultorio = $model_consultorio->GetNombreById(Auth::user()->id_consultorio);
		//----------------------------------------------------------------------

		return view('frontend.hc.editar_movimiento_hc', compact('farmacia', 'id_farmacia', 'fecha_hace_treinta_dias', 'rango_registro', 'nro_mov', 'fechaServidor', 'consultorios', 'ano_mov', 'flag', 'origen', 'movimiento_historias', 'lugar', 'responsable_destinos', 'id', 'nombre_consultorio'));
	}

	public function registrar_movimiento_hc($id_nro_mov, $id_origen, $fechaDesde, $fechaHasta)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$model_farmacia = new Farmacia;
		$model_hc = new Historia;
		$model_kardex = new Kardexe;

		$movimiento_historias = $model_hc->GetMovimientoHCById($id_nro_mov);
		$destino = Consultorio::where('id', $movimiento_historias->id_consultorio)->select('nombre')->first();
		$id_destino = $movimiento_historias->id_consultorio;


		$id_farmacia = Auth::user()->id_farmacia;
		$historias_asignadas = $model_hc->ShowHistoriasById_movimiento_hc($id_nro_mov);
		$origen = Consultorio::where('id', $movimiento_historias->id_origen)->select('nombre')->first();
		$id_origen = $movimiento_historias->id_consultorio;
		$farmacia = $model_farmacia->GetIpress($id_farmacia);

		$date = Carbon::now();
		if ($fechaDesde == ""):
			$fechaDesde = $date->format('d-m-Y');
		endif;

		if ($fechaHasta == ""):
			$fechaHasta = $date->format('d-m-Y');
		endif;

		return view('frontend.hc.registrar_movimiento_hc', compact('movimiento_historias', 'destino', 'id_destino', 'id_farmacia', 'lotes', 'productos', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_nro_mov', 'historias_asignadas', 'origen'));
	}



	public function obtener_hc_dni($buscar, $id_establecimiento, $id_consultorio)
	{

		$model_consultorio = new \App\Models\Historia;
		$dni_hc = $model_consultorio->getBuscarHCDNI($buscar, $id_establecimiento, $id_consultorio);
		$array = array();
		foreach ($dni_hc as $leer) {
			$array[] = array(
				"value" => $leer->nro_doc_ident . " - " . $leer->nro_historia . " - " . $leer->nombre . " - " . $leer->paterno . " - " . $leer->materno,
				"label" => $leer->nro_doc_ident . " - " . $leer->nro_historia . " - " . $leer->nombre . " - " . $leer->paterno . " - " . $leer->materno,
				"id" => $leer->id,
			);
		}

		echo json_encode($array);
	}

	public function agregar_historias_clinicas(Request $request)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$id_user = Auth::user()->id;
		$id_establecimiento = Auth::user()->id_establecimiento;
		$id_consultorio = $request->id_servicio;
		$p_estado = $request->p_estado;

		/***************************************************************************/
		$model_historia = new \App\Models\Historia;

		if (isset($request->id_dni_hc)) :
			$p_historias = "";
			$p_historias .= "{";
			foreach ($request->id_dni_hc as $key => $value) :
				if ($request->id_dni_hc[$key] != "") {
					$p_historias .= "{";
					$p_historias .= $request->id_dni_hc[$key];
					$p_historias .= "},";
				}
			endforeach;
			if (strlen($p_historias) > 1) $p_historias = substr($p_historias, 0, -1);
			$p_historias .= "}";

			if ($request->id_dni_hc[0] != ""):
				$id_movimiento_hc = $model_historia->RegistrarHistoriasClinicasServicio($request->id_nro_movimiento, $id_user, $id_establecimiento,  $id_consultorio, $p_estado, $p_historias);
			endif;
		endif;

		if ($id_movimiento_hc == 0) {
			Session::flash('flash_message', 'No se pudo registrar las Historias Clinicas, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
			return redirect('/all_movimiento_hc');
		} else {
			Session::flash('flash_message', 'Registro exitoso');
			return redirect('/registrar_movimiento_hc/' . $request->id_nro_movimiento . '/' . $id_establecimiento . '/' . $request->fecha_ini . '/' . $request->fecha_fin);
		}
	}

	public function registrar_paciente_historia(Request $request)
	{

		$model_historia = new \App\Models\Historia;
		$id_establecimiento = Auth::user()->id_establecimiento;

		$id_registro_paciente_historia = $model_historia->registrarPacienteHistoria($id_establecimiento, $request->id_user, $request->tipodoc_beneficiario, $request->nrodocafiliado, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $request->nro_historia, $request->grado, $request->fecnacafiliado, $request->nomsexo, $request->cip_beneficiario, $request->domicilio, $request->telefono);

		if ($id_registro_paciente_historia == 0) {
			Session::flash('flash_message', 'No se pudo registrar la Historia Clinicas, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
			return redirect('/all_movimiento_hc');
		} else {
			Session::flash('flash_message', 'Registro exitoso');
			return redirect('/registrar_movimiento_hc/' . $request->id_nro_movimiento_hc . '/' . $id_establecimiento . '/' . $request->fechaDesde . '/' . $request->fechaHasta);
		}
	}

	public function eliminar_hc_servicio($id, $id_nro_movimiento_hc, $fechaDesde, $fechaHasta)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$model_historia = new \App\Models\Historia;

		$id_user = Auth::user()->id;
		$id_establecimiento = Auth::user()->id_establecimiento;
		$id_consultorio = Auth::user()->id_consultorio;
		$id_elimina_hc = $model_historia->EliminarHistoriasClinicasServicio($id, $id_user, $id_consultorio);

		Session::flash('flash_message', 'Se elimino correctamente de la lista, la historia clinica');
		return redirect('/registrar_movimiento_hc/' . $id_nro_movimiento_hc . '/' . $id_establecimiento . '/' . $fechaDesde . '/' . $fechaHasta);
	}

	public function cerrar_envio_hc_servicio($id_nro_movimiento_hc, $id_estado)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$model_historia = new \App\Models\Historia;

		$id_user = Auth::user()->id;
		$id_finalizar_envio = $model_historia->CambiaEstadoHCServicio($id_nro_movimiento_hc, $id_estado, $id_user, 0);

		Session::flash('flash_message', 'Se envio correctamente las Historias al Servicio');
		return redirect('/all_movimiento_hc/');
	}

	public function all_historias_recibidas($id_user)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$id_farmacia = Auth::user()->id_farmacia;

		$model_farmacia = new Farmacia;
		$model_historia = new Historia;
		$model_consultorio = new Consultorio;
		//----------------------------------------------------------------------        
		$date = Carbon::now();
		$fechaHasta = $date->format('d-m-Y');
		$date->modify('first day of this month');
		$fechaDesde = $date->format('d-m-Y');
		$establecimiento = $model_farmacia->GetIpress($id_farmacia);
		$nombre_consultorio = $model_consultorio->GetNombreById(Auth::user()->id_consultorio);

		$movimiento_historias = $model_historia->allMovimientoRecibidosByEstablecimiento($establecimiento->id, Auth::user()->id_consultorio, $fechaDesde, $fechaHasta);

		$date = Carbon::now();
		$fecha = $date->format('d-m-Y');
		$fechaActual = Carbon::parse($fecha);

		return view('frontend.hc.all_historias_recibidas', compact('movimiento_historias', 'establecimiento', 'fechaActual', 'fechaDesde', 'fechaHasta', 'nombre_consultorio'));
	}

	public function busca_hc_recibidas(Request $request)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$id_farmacia = Auth::user()->id_farmacia;

		$model_farmacia = new Farmacia;
		$model_historia = new Historia;
		$model_consultorio = new Consultorio;
		//----------------------------------------------------------------------        
		$establecimiento = $model_farmacia->GetIpress($id_farmacia);

		//-----------------------------------------------
		if ($request->fechaDesde != "") {
			$fechaDesde = $request->fechaDesde;
			$fechaHasta = $request->fechaHasta;
		} else {
			$date = Carbon::now();
			$fechaHasta = $date->format('d-m-Y');
			$date->modify('first day of this month');
			$fechaDesde = $date->format('d-m-Y');
		}

		$almacen = 1;
		$movimiento_historias = $model_historia->allMovimientoRecibidosByEstablecimiento($establecimiento->id, Auth::user()->id_consultorio, $fechaDesde, $fechaHasta);

		$date = Carbon::now();
		$fecha = $date->format('d-m-Y');
		$fechaActual = Carbon::parse($fecha);

		$nombre_consultorio = $model_consultorio->GetNombreById(Auth::user()->id_consultorio);

		return view('frontend.hc.all_historias_recibidas', compact('movimiento_historias', 'establecimiento', 'fechaActual', 'almacen', 'fechaDesde', 'fechaHasta', 'nombre_consultorio'));
	}

	public function recepcionar_hc_servicio(Request $request)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$model_historia = new \App\Models\Historia;
		$id_nro_movimiento_hc = $request->id_nro_movimiento;
		$id_establecimiento = $request->id_establecimiento;
		$id_servicio = $request->id_servicio;
		$anio = date("Y");
		$nro_entrada =  $request->nro_entrada . '-' . $anio;

		$id_user = Auth::user()->id;
		$id_finalizar_envio = $model_historia->CambiaEstadoHCServicio($id_nro_movimiento_hc, 3, $id_user, $nro_entrada);

		$id_correlativo = $model_historia->GuardarCorrelativo($id_establecimiento, $id_servicio, $anio, $id_user, $request->nro_entrada);

		Session::flash('flash_message', 'Se recepcionó correctamente las Historias en el Servicio');
		return redirect('/all_historias_recibidas/' . $id_user);
	}

	public function ver_movimiento_hc($id_nro_mov, $id_establecimiento)
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$model_farmacia = new Farmacia;
		$model_hc = new Historia;
		$model_consultorio = new Consultorio;

		$movimiento_historias = $model_hc->GetMovimientoHCById($id_nro_mov);
		$origen = Consultorio::where('id', $movimiento_historias->id_origen)->select('nombre')->first();
		$id_destino = Auth::user()->id_consultorio;

		$farma = $model_farmacia->GetFarmaByIdfarma(Auth::user()->id_farmacia);
		$farmacia_origen = $farma->nombre;
		$id_farmacia = Auth::user()->id_farmacia;
		$historias_asignadas = $model_hc->ShowHistoriasById_movimiento_hc($id_nro_mov);

		$farmacia = $model_farmacia->GetFarmaByUser(Auth::user()->id);
		$id_farmacia = $farmacia->id;
		$modo_validacion = $farmacia->modo_validacion;

		$establecimiento = $model_farmacia->GetIpress($id_farmacia);
		$nombre_consultorio = $model_consultorio->GetNombreById(Auth::user()->id_consultorio);

		$nrohc = $model_hc->obtenerUltiNroHCByEstablecimientoAndAnio($id_establecimiento, $id_destino);
		$hc = $nrohc[0];

		return view('frontend.hc.recepcionar_hc', compact('movimiento_historias', 'farmacia_origen',  'origen', 'id_farmacia', 'lotes', 'productos', 'farmacia', 'id_nro_mov', 'historias_asignadas', 'hc', 'id_destino', 'establecimiento', 'nombre_consultorio'));
	}

	public function eliminar_movimiento_hc($id)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$model_historia = new \App\Models\Historia;

		$id_user = Auth::user()->id;
		$id_finalizar_envio = $model_historia->CambiaEstadoHCServicio($id, 0, $id_user, 0);

		Session::flash('flash_message', 'Se elimino correctamente las Historias al Servicio');
		return redirect('/all_movimiento_hc/');
	}

	/*public function buscar_movimiento_historia()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_farmacia = new Farmacia;
    $model_hc = new Historia;
    $model_consultorio = new Consultorio;

    $id_farmacia = Auth::user()->id_farmacia;
    $farmacia = $model_farmacia->GetIpress($id_farmacia);
    
    $nombre_consultorio = $model_consultorio->GetNombreById(Auth::user()->id_consultorio);
    return view('frontend.hc.buscar_historia', compact( 'destino', 'id_destino', 'id_farmacia','lotes','productos','farmacia','fechaDesde','fechaHasta','id_nro_mov','historias_asignadas','origen','nombre_consultorio'));
  } */

	public function nueva_historia()
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$model_farmacia = new Farmacia;
		$model_hc = new Historia;
		$model_consultorio = new Consultorio;

		$id_farmacia = Auth::user()->id_farmacia;
		$farmacia = $model_farmacia->GetIpress($id_farmacia);

		$nombre_consultorio = $model_consultorio->GetNombreById(Auth::user()->id_consultorio);
		return view('frontend.hc.nueva_historia', compact('destino', 'id_destino', 'id_farmacia', 'lotes', 'productos', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_nro_mov', 'historias_asignadas', 'origen', 'nombre_consultorio'));
	}

	public function buscar_movimiento_historia()
	{
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$model_farmacia = new Farmacia;
		$model_hc = new Historia;
		$model_consultorio = new Consultorio;
		$id_farmacia = Auth::user()->id_farmacia;
		$farmacia = $model_farmacia->GetIpress($id_farmacia);
		$nombre_consultorio = $model_consultorio->GetNombreById(Auth::user()->id_consultorio);
		return view('frontend.hc.buscar_historia', compact('farmacia', 'nombre_consultorio'));
	}

	public function listar_historia_consultorio(Request $request)
	{

		$model_historia = new Historia();
		$p[] = $request->fecha_ini;
		$p[] = $request->fecha_fin;
		$p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
		$p[] = Auth::user()->id_establecimiento;
		$p[] = $request->estado;
		$p[] = $request->NumeroPagina;
		$p[] = $request->NumeroRegistros;
		$data = $model_historia->listar_historia_consultorio($p);
		$iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

		$result["PageStart"] = $request->NumeroPagina;
		$result["pageSize"] = $request->NumeroRegistros;
		$result["SearchText"] = "";
		$result["ShowChildren"] = true;
		$result["iTotalRecords"] = $iTotalDisplayRecords;
		$result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
		$result["aaData"] = $data;
		echo json_encode($result);
	}

	public function buscar_historias_movimientos(Request $request)
	{

		$model_historia = new Historia();

		$p[] = Auth::user()->id_establecimiento;
		$p[] = (isset($request->id_dni_hc) && $request->id_dni_hc != "") ? $request->id_dni_hc : 0;
		$p[] = $request->NumeroPagina;
		$p[] = $request->NumeroRegistros;
		$data = $model_historia->busca_historia_movimiento($p);
		$iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

		$result["PageStart"] = $request->NumeroPagina;
		$result["pageSize"] = $request->NumeroRegistros;
		$result["SearchText"] = "";
		$result["ShowChildren"] = true;
		$result["iTotalRecords"] = $iTotalDisplayRecords;
		$result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
		$result["aaData"] = $data;
		echo json_encode($result);
	}

	public function actualiza_beneficiario_datos()
	{


		$usuarios = DB::table('asegurados')
			->where('id', '>', 461107)
			->where('id', '<', 470000)
			->orderby('id', 'asc')->get();

		$i = 1;
		$errorWS = 0;
		$personal = null;
		foreach ($usuarios as $bene) {
			$Dirrehum = new \App\Ws\Dirrehum;
			$result = $Dirrehum->BuscarTitularFamiliar(1, $bene->nro_doc_ident);
			$personal = json_encode($result->BuscarTitularFamiliarResult, true);
			if ($personal != "{}") {
				$personal_d = $result->BuscarTitularFamiliarResult->TitularFamiliar;
				if (count($personal_d) > 1) {
					$value = Str::contains($personal_d[0]->CARNE, 'incorrecto');
				} else {
					$value = Str::contains($personal_d->CARNE, 'incorrecto');
				}
				if ($value == false) {
					if (count($personal_d) > 1) {
						if (isset($personal_d[0]->CARNE)) {
							$db_update = DB::table('asegurados')
								->where('nro_doc_ident', $bene->nro_doc_ident)
								->update(['situacion_paciente' => $personal_d[0]->SITUACION, 'grado' => $personal_d[0]->GRADO, 'unidad_pnptitular' => $personal_d[0]->UNIDAD]);
						}
					} else {
						if (isset($personal_d->CARNE)) {
							$db_update = DB::table('asegurados')
								->where('nro_doc_ident', $bene->nro_doc_ident)
								->update(['situacion_paciente' => $personal_d->SITUACION, 'grado' => $personal_d->GRADO, 'unidad_pnptitular' => $personal_d->UNIDAD]);
						}
					}
				}
				echo $i++;
			} else {
				$client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));


				$beneficiario = $client->getAseguradoValidate(1, $bene->nro_doc_ident);
				$estado = 'si';
				$errorWS = 1;
				if (!is_object($beneficiario)) {
					$errorWS = 0;
				}

				if ($errorWS == 1) {
					$db_update = DB::table('asegurados')
						->where('nro_doc_ident', $bene->nro_doc_ident)
						->update(['grado' => $beneficiario->grado]);
				}
				echo $i++;
			}
		}
	}

	public function obtener_datos_solo_reniec_ajax(Request $request)
	{
		$personal = null;
		if ($request->tipo_documento == "1") {
			$WsReniec = new \App\Ws\Reniec;
			$personal_reniec = $WsReniec->BuscarDNI($request->dni);
			$personal = json_decode($personal_reniec, true);

			$data['codigoMensaje'] = $personal['codigoMensaje'];
			$data['dni'] = $personal['dni'];
			$data['paterno'] = $personal['paterno'];
			$data['materno'] = $personal['materno'];
			$data['nombres'] = $personal['nombres'];
			$data['id_sexo'] = $personal['id_sexo'];
			$data['sexo'] = $personal['sexo'];
			$data['fecha_nac'] = $personal['fecha_nac'];
			$data['vfecha_nac'] = $personal['vfecha_nac'];
			$data['vfecha_expedicion'] = $personal['vfecha_expedicion'];
			$data['vfecha_caducidad'] = $personal['vfecha_caducidad'];
			$data['foto'] = $personal['foto'];
			$data['estado_civil'] = $personal['estado_civil'];
			$data['descripcionMensaje'] = $personal['descripcionMensaje'];
			$data['direccionDomicilio'] = $personal['direccionDomicilio'];

			/*--------------------------- DEFINICION DE UBIGEO ---------------------------*/
			/*--------------------------- DEFINICION DE UBIGEO ---------------------------*/
			$data['provinciaDomicilio'] = $personal['provinciaDomicilio'];
			$data['distritoDomicilio'] = $personal['distritoDomicilio'];

			///VAMIDAMOS CALLAO
			if ($personal['provinciaDomicilio'] == 'CALLAO') {
				$descripcDepartamento = 'CALLAO';
				$data['departamentoDomicilio'] = 'CALLAO';
			} else {
				$descripcDepartamento = $personal["departamentoDomicilio"];
				$data['departamentoDomicilio'] = $personal["departamentoDomicilio"];
			}

			////////////obtener ubigeo actual//////////// 
			$departamento_actual = new Departamento();
			$ubica_departamento = $departamento_actual->getObtenerId_by_descripcion($descripcDepartamento);
			if ($ubica_departamento != null) {
				$data['id_departamentoDomicilio'] = $ubica_departamento[0]->id_departamento;
			}


			$provincia_actual = new Provincia();
			$ubica_provincia = $provincia_actual->getObtenerId_by_descripcion_and_idDepartamento($personal['provinciaDomicilio'], $ubica_departamento[0]->id_departamento);
			if ($ubica_provincia != null) {
				$data['id_provinciaDomicilio'] = $ubica_provincia[0]->id_provincia;
			}

			$distrito_actual = new Distrito();
			$ubica_distrito = $distrito_actual->getObtenerId_by_descripcion_and_iProvincia($ubica_provincia[0]->id_provincia, $personal['distritoDomicilio']);
			if ($ubica_distrito != null) {
				$data['id_distritoDomicilio'] = $ubica_distrito[0]->id_distrito;
				$data['codigo_ubigDomicilio'] = $ubica_distrito[0]->codigo;
			}
			/*--------------------------- DEFINICION DE UBIGEO ---------------------------*/
			/*--------------------------- DEFINICION DE UBIGEO ---------------------------*/

			///VAMIDAMOS CALLAO
			if ($personal['departamento_nacimiento'] == 'CALLAO') {
				$descripcDepartamento_nac = 'CALLAO';
			} else {
				$descripcDepartamento_nac = $personal["departamento_nacimiento"];
			}

			$data['localidad_domicilio'] = $personal['localidad_domicilio'];
			$data['departamento_nacimiento'] = $descripcDepartamento_nac;
			$data['provincia_nacimiento'] = $personal['provincia_nacimiento'];
			$data['distrito_nacimiento'] = $personal['distrito_nacimiento'];
		}

		echo json_encode($data);
	}
	public function getAseguradoby_id($id_asegurado)
	{
		$modelpaciente = new Paciente();
		$paciente = $modelpaciente->geaAseguradoby_id($id_asegurado);

		return json_encode($paciente);
	}

	public function getAseguradoby_id_withSP($id_enfermeria)
	{
		$paciente = DB::select('SELECT * FROM sp_get_cabecera_enfermeria(?)', [$id_enfermeria]);
		return json_encode($paciente);
	}

	public function getAseguradoby_id_withSP_sa($id_enfermeria)
	{
		$paciente = DB::select('SELECT * FROM sp_get_cabecera_enfermeria_sa(?)', [$id_enfermeria]);
		return json_encode($paciente);
	}

	public function getAseguradoby_id_withSPforHospi($id_enfermeria)
	{
		$paciente = DB::select('SELECT * FROM sp_get_cabecera_hospitalizacion_enfermeria(?)', [$id_enfermeria]);
		return json_encode($paciente);
	}

	public function registrar_paciente(Request $request)
	{
		if (!Auth::check()) {
			return redirect('/auth/login');
		}

		$id_user = Auth::user()->id;

		$paciente = new Paciente();

		$paciente->id_tipo_doc_ident    = $request->id_tipo_doc_ident;
		$paciente->tipo_doc_ident       = $request->tipo_doc_ident;
		$paciente->nro_doc_ident        = $request->nro_doc_ident;
		$paciente->id_tipo_beneficiario = $request->id_tipo_beneficiario;
		$paciente->nombre               = $request->nombre;
		$paciente->paterno              = $request->paterno;
		$paciente->materno              = $request->materno;
		$paciente->sexo                 = $request->sexo;
		$paciente->fecha_nac            = $request->fecha_nac;
		$paciente->telefono             = $request->telefono;
		$paciente->id_tipo_sangre       = $request->id_tipo_sangre;
		$paciente->correo               = $request->correo;
		$paciente->observacion          = $request->observacion;
		$paciente->id_departamento      = $request->id_departamento;
		$paciente->id_provincia         = $request->id_provincia;
		$paciente->id_distrito          = $request->id_distrito;
		$paciente->domicilio            = $request->domicilio;
		$paciente->id_grado_instruccion = $request->id_grado_instruccion;
		$paciente->id_ocupacion         = $request->id_ocupacion;
		$paciente->id_estado_civil      = $request->id_estado_civil;
		$paciente->id_religion          = $request->id_religion;
		$paciente->id_etnia             = $request->id_etnia;
		$paciente->id_tipo_parentesco   = ($request->has('id_parentesco') && $request->get('id_parentesco') !== '') ? $request->id_parentesco : null;
		$paciente->discapacidad         = $request->has('discapacidad') ? $request->discapacidad : 0;
		$paciente->otro_seguro          = ($request->has('otro_seguro') && $request->get('otro_seguro') !== '') ? $request->otro_seguro : null;
		$paciente->discapacidad_carnet  = ($request->has('discapacidad_carnet') && $request->get('discapacidad_carnet') !== '') ? $request->discapacidad_carnet : null;
		$paciente->nro_carnet_conadis   = ($request->has('nro_carnet_conadis') && $request->get('nro_carnet_conadis') !== '') ? $request->nro_carnet_conadis : null;
		$paciente->color_carnet_conadis = ($request->has('color_carnet_conadis') && $request->get('color_carnet_conadis') !== '') ? $request->color_carnet_conadis : null;

		if ($request->id_tipo_beneficiario == 8) {
			$paciente->tipo_doc_titular   = $request->tipo_doc_titular;
			$paciente->dni_titular        = $request->dni_titular2;
			$paciente->nombre_titular     = $request->nombre_titular2;
			$paciente->materno_titular    = $request->materno_titular2;
			$paciente->paterno_titular    = $request->paterno_titular2;
			$paciente->cip                = $request->cips2;
			$paciente->grado              = $request->grado2;
			$paciente->situacion_paciente = $request->situacion_paciente2;
		} else {
			$paciente->tipo_doc_titular   = $request->tipo_doc_titular;
			$paciente->dni_titular        = $request->dni_titular;
			$paciente->nombre_titular     = $request->nombre_titular;
			$paciente->materno_titular    = $request->materno_titular;
			$paciente->paterno_titular    = $request->paterno_titular;
			$paciente->cip                = $request->cips;
			$paciente->grado              = $request->grado;
			$paciente->situacion_paciente = $request->situacion_paciente;
		}
		$paciente->created_at    = Carbon::now();
		$paciente->created_us    = $id_user;
		$paciente->id_estado_reg = 1;
		$paciente->save();

		$paciente->id_asegurado_titular = $paciente->id;
		$paciente->save();

		if (!empty($request->id_parentesco)) {
			$model_tutor = new Tutor();

			$obj = new \stdClass();
			$obj->tipo_documento    = $request->tipo_documento;
			$obj->numero_documento  = $request->numero_documento;
			$obj->nombres           = $request->nombres;
			$obj->apellidos         = $request->apellidos;
			$obj->telefono_tutor    = $request->telefono_tutor;
			$obj->observacion_tutor = $request->observacion_tutor;
			$obj->id_parentesco     = $request->id_parentesco;
			$obj->direccion         = $request->direccion;
			$obj->flag              = 1;

			$insert_tutor = $model_tutor->registrarTutor($obj);

			if ($insert_tutor) {
				$paciente->id_tutor = $insert_tutor;
				$paciente->save();
			}
		}

		if (isset($request->id_establecimiento) && !empty($request->nro_historia)) {
			$new_historia = new Asegurado_historia();
			$new_historia->id_asegurado          = $paciente->id;
			$new_historia->id_establecimiento    = $request->id_establecimiento;
			$new_historia->nro_historia          = $request->nro_historia;
			$new_historia->id_estado_reg         = 1;
			$new_historia->created_us            = $id_user;
			$new_historia->id_user               = $id_user;
			$new_historia->id_user_hc            = null;
			$new_historia->id_consultorio_origen = null;
			$new_historia->estado_hc             = 0;
			$new_historia->id_consultorio_destino = null;
			$new_historia->bak_historia          = null;
			$new_historia->nro_historia_lns      = null;
			$new_historia->save();
		}

		DB::table('asegurado_auditores')->insert([
			'operacion'            => '0',
			'id_asegurado'         => $paciente->id,
			'nro_doc_beneficiario' => $paciente->nro_doc_ident,
			'paterno_asegurado'    => $paciente->paterno,
			'materno_asegurado'    => $paciente->materno,
			'nombre_asegurado'     => $paciente->nombre,
			'id_asegurado_titular' => $paciente->id_asegurado_titular, 
			'id_tipo_beneficiario' => $paciente->id_tipo_beneficiario,
			'grado'                => $paciente->grado,
			'situacion'            => $paciente->situacion_paciente,
			'nro_doc_titular'      => $paciente->dni_titular,
			'paterno_titular'      => $paciente->paterno_titular,
			'materno_titular'      => $paciente->materno_titular,
			'nombre_titular'       => $paciente->nombre_titular,
			'fechaini_afiliacion'  => $paciente->fechaini_afiliacion,
			'fechafin_afiliacion'  => $paciente->fechafin_afiliacion,
			'estado_afiliacion'    => $paciente->estado_afiliacion,
			'condicion_asegurado'  => $paciente->condicion_asegurado,
			'estado_old'           => '0',
			'estado_new'           => '1',
			'created_us'           => $id_user,
			'created_at'           => Carbon::now(),       
			'incial_created_us'    => $id_user,            
			'iniciail_created_at'  => Carbon::now(),       
		]);
		
		Session::flash('flash_message', 'Registro exitoso');
		return redirect('/all_asegurados');
	}

	public function validarDocumento(Request $request)
	{
		$existe = Paciente::where('nro_doc_ident', $request->nro_doc_ident)->where('id_estado_reg', 1)->exists();
		return response()->json(['existe' => $existe]);
	}


	private function obtener_beneficiario_local($id_tiporeceta, $tipodoc_beneficiario, $dni_beneficiario)
	{
		$model_hospitalizacion = new \App\Models\Hospitalizacione;
		$array = array();
		$id_farmacia = Auth::user()->id_farmacia;
		$numero_historia = "";
		$beneficiario = "";
		$msg = "";
		$sw = false;
		$establecimiento = "";
		$cita = "";
		$domicilio = "";
		$telefono = "";
		$correo = "";

		$model_farma = new Farmacia;
		$farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
		$id_establecimiento = $farma->id_establecimiento;

		$asegurado = new \App\Models\Asegurado;
		$beneficiario = $asegurado->busca_asegurado_como_wssaludpol($tipodoc_beneficiario, $dni_beneficiario);
		//$client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
		//$beneficiario = $client->getAseguradoValidate($tipodoc_beneficiario, $dni_beneficiario);

		if ($id_tiporeceta == 3) :
			//foreach ($beneficiario as $key => $ben) if (!is_object($ben)) $beneficiario->$key = utf8_decode($ben);

			if (is_object($beneficiario)) :
				$beneficiario->nompaisdelafiliado = '';
				$beneficiario->apecasafiliado = '';
				$beneficiario->edadafiliado = '';
				$beneficiario->estado = '';
				$beneficiario->apecastitular = '';
				$beneficiario->ubigeo = '';
				$beneficiario->caducidad = '';
				$beneficiario->discapacidad = '';
				$beneficiario->otroseguro = '';
				$beneficiario->unidad_pnp = '';

				$hospitalizado = DB::table('hospitalizaciones')->select('hospitalizaciones.*')->where('dni_beneficiario', $dni_beneficiario)->orderby('id', 'desc')->first();
				//echo "ok";print_r($hospitalizado);
				//if (is_object($hospitalizado)):
				if ($id_farmacia == 14 && !is_object($hospitalizado)) {
					$msg = "No se encuentra hospitalizado..!";
				}
				if ($msg == "") :
					$sw = true;
					$existe_beneficiario = \App\Models\Receta_vale::where('dni_beneficiario', $dni_beneficiario)->where('id_farmacia', $id_farmacia)->get();
					if (count($existe_beneficiario) > 0) :
						$numero_historia = $existe_beneficiario[0]->nro_historia;
					else :
						$existe_beneficiario2 = DB::table('hospitalizaciones')->where('dni_beneficiario', $dni_beneficiario)->where('id_farmacia', $id_farmacia)->get();
						if (count($existe_beneficiario2) > 0) :
							$numero_historia = $existe_beneficiario2[0]->nro_historia;
						endif;
					endif;
					if (is_object($hospitalizado)) {
						$hospitalizacion = $model_hospitalizacion->ShowByIdHospitalizacionId_farmacia($hospitalizado->id, $hospitalizado->id_farmacia);
						$array["hospitalizacion"] = isset($hospitalizacion[0]) ? $hospitalizacion[0] : $hospitalizado;
					}
				endif;
			else :
				$msg = "No se encuentra afiliado..!";
			endif;
			$cantidad_cita = 0;
		else :
			//foreach ($beneficiario as $key => $ben) if (!is_object($ben)) $beneficiario->$key = utf8_decode($ben);
			$model_establecimiento = new \App\Models\Establecimiento;
			if (isset($beneficiario->ubigeo)) $establecimiento = $model_establecimiento->GetEstablecimientoUbigeosByUbigeo($beneficiario->ubigeo);

			if (is_object($beneficiario)) :
				$beneficiario->nompaisdelafiliado = '';
				$beneficiario->apecasafiliado = '';
				$beneficiario->edadafiliado = '';
				$beneficiario->estado = '';
				$beneficiario->apecastitular = '';
				$beneficiario->ubigeo = '';
				$beneficiario->caducidad = '';
				$beneficiario->discapacidad = '';
				$beneficiario->otroseguro = '';
				$beneficiario->unidad_pnp = '';
				//buscar historia en la ipress
				$sw = true;
				$model_asegurado = new \App\Models\Asegurado;
				$existe_datos_asegurado = $model_asegurado->busca_datos_asegurados($dni_beneficiario, $id_establecimiento);

				if (count($existe_datos_asegurado) == 0) :
					$existe_nro_historia_receta_vale = $model_asegurado->existe_nro_historia_receta_vale($dni_beneficiario, $id_establecimiento);

					if (count($existe_nro_historia_receta_vale) > 0) :
						$numero_historia = $existe_nro_historia_receta_vale;
					endif;
				else :
					$numero_historia = $existe_datos_asegurado[0]->nro_historia;
					$domicilio = $existe_datos_asegurado[0]->domicilio;
					$telefono = $existe_datos_asegurado[0]->telefono;
					$correo = $existe_datos_asegurado[0]->correo;
				endif;
			else :
				$msg = "No se encuentra afiliado..!";
			endif;
			$model_cita = new \App\Models\Cita;
			$cita = $model_cita->GetCitaByAseguradoAndEstablecimiento($tipodoc_beneficiario, $dni_beneficiario, $id_establecimiento);
			$medico_turno = new \App\Models\Medico_turno;
			$horario = $medico_turno->GetCitasByDniFecha($dni_beneficiario);
			$cantidad_cita = count($horario);
		endif;
		$array["sw"] = $sw;
		$array["msg"] = $msg;
		$array["beneficiario"] = $beneficiario;
		$array["numero_historia"] = $numero_historia;
		$array["domicilio"] = $domicilio;
		$array["telefono"] = $telefono;
		$array["correo"] = $correo;
		$array["establecimiento"] = $establecimiento;
		$array["cita"] = $cita;
		$array["cantidad_cita"] = $cantidad_cita;
		$array["domicilio"] = $domicilio;
		$array["telefono"] = $telefono;
		$array["correo"] = $correo;
		$array["cip"] = "123";

		$objeto = (object) $beneficiario;
		return $objeto;
	}


	/* public function datosPaciente(Request $request)
	{
	    $tipo = $request->input('tipo_doc');
	    $dni = $request->input('nro_doc');
	    $asegurado = new Asegurado();
	    $datos = $asegurado->busca_datos_aseguradosxtiponro($tipo, $dni);
	
	    if (!empty($datos) && count($datos) > 0) {
	        // Si solo necesitas el primer resultado:
	        return response()->json($datos[0]);
	    } else {
	        return response()->json([], 404);
	    }
	} */

	public function datosPaciente(Request $request)
	{
		$tipo = $request->input('tipo_doc');
		$dni = $request->input('nro_doc');

		$asegurado = new Asegurado();
		$saludpol = new Saludpol();
		$datos = $asegurado->busca_datos_aseguradosxtiponroV2($tipo, $dni);
		$usuarioRegistrador = User::where("id", Auth::id())->first();

		if (!empty($datos) && count($datos) > 0) {
			$historiaEnIpress = Asegurado_historia::where("id_asegurado", $datos[0]->id_asegurado)->where("id_establecimiento", $usuarioRegistrador->id_establecimiento)->first();
			if ($historiaEnIpress) {
				/* Asegurado_historia::create([
					'id_asegurado' => $datos->id,
					'id_establecimiento' => $usuarioRegistrador->id_establecimiento,
					'nro_historia' => "",
					'id_estado_reg' => 1,
					'created_at' => Carbon::now(),
					'created_us' => Auth::id(),
				]); */
				return response()->json([
					'status' => 'success',
					'message' => 'Usuario encontrado con historia registrada.',
					'data' => [
						'asegurado_id' => $datos[0]->id_asegurado,
						'nombre' => $datos[0]->nombre,
						'tiene_historia' => true,
						'asegurado' => $datos[0],
						'nro_historia' => $historiaEnIpress->nro_historia
					],
				], 200);
			} else {
				return response()->json([
					'status' => 'success',
					'message' => 'Usuario encontrado sin historia registrada.',
					'keyword' => 'historia',
					'data' => [
						'asegurado_id' => $datos[0]->id_asegurado,
						'nombre' => $datos[0]->nombre,
						'tiene_historia' => false,
					],
					'modal' => [
						'mensaje' => 'El usuario no tiene historia registrada. ¿Deseas crear una?',
						'accion' => 'registrar_historia',
						'mostrar_modal' => true,
					]
				], 200);
			}
		} else {
			$wsData = $saludpol->BuscarTitularFamiliar($tipo, $dni);
			if (is_object($wsData) && $wsData->estado == "ACTIVO") {
				$nuevoAsegurado = $asegurado->registrarDesdeWS($wsData);
				return response()->json([
					'status' => 'success',
					'message' => 'Usuario no existia en la base de datos, se ha registrado con exito desde SALUDPOL pero sin numero de historia.',
					'keyword' => 'historia',
					'data' => [
						'asegurado_id' => $nuevoAsegurado->id,
						'nombre' => $nuevoAsegurado->nombre,
						'tiene_historia' => false,
					],
					'modal' => [
						'mensaje' => 'El usuario no tiene historia registrada. ¿Deseas crear una?',
						'accion' => 'registrar_historia',
						'mostrar_modal' => true,
					]
				], 200);
			} else {
				return response()->json([
					'status' => 'error',
					'message' => 'Usuario no encontrado.',
					'keyword' => 'usuario',
					'modal' => [
						'mensaje' => 'El usuario no se encuetra registrado en el sistema ni esta afiliado a SALUDPOL. ¿Deseas registrar al usuario?',
						'accion' => 'registrar_usuario',
						'mostrar_modal' => true,
					]
				], 404);
			}
		}
	}

	public function registrarNroHistoria(Request $request)
	{
		/* $asegurado = Asegurado::where("nro_doc_ident", $request->asegurado_doc)->first();
		$usuarioRegistrador = User::where("id", Auth::id())->first();
		Asegurado_historia::create([
			'id_asegurado' => $asegurado->id,
			'id_establecimiento' => $usuarioRegistrador->id_establecimiento,
			'nro_historia' => $request->nroHistoria,
			'id_estado_reg' => 1,
			'created_at' => Carbon::now(),
			'created_us' => Auth::id(),
		]); */
		// Buscar al asegurado
		$asegurado = Asegurado::where("nro_doc_ident", $request->asegurado_doc)->first();

		if (!$asegurado) {
			return response()->json([
				'success' => false,
				'message' => 'No se encontró al asegurado con el documento proporcionado.'
			], 404);
		}

		// Usuario que está registrando
		$usuarioRegistrador = User::find(Auth::id());

		// Registrar la historia
		$historia = Asegurado_historia::create([
			'id_asegurado' => $asegurado->id,
			'id_establecimiento' => $usuarioRegistrador->id_establecimiento,
			'nro_historia' => $request->nro_historia,
			'id_estado_reg' => 1,
			'created_at' => Carbon::now(),
			'created_us' => Auth::id(),
		]);

		// Hacemos un join para traer datos del asegurado con su historia
		$data = Asegurado::select(
			'asegurados.*',
			'asegurado_historias.nro_historia'
		)
			->join('asegurado_historias', 'asegurados.id', '=', 'asegurado_historias.id_asegurado')
			->where('asegurados.id', $asegurado->id)
			->where('asegurado_historias.id', $historia->id) // última historia registrada
			->first();

		$model_asegurado = new Asegurado();
		$datos = $model_asegurado->busca_datos_aseguradosxtiponroV2($asegurado->id_tipo_doc_ident, $asegurado->nro_doc_ident);
		$historiaEnIpress = Asegurado_historia::where("id_asegurado", $datos[0]->id_asegurado)->where("id_establecimiento", $usuarioRegistrador->id_establecimiento)->first();

		return response()->json([
			'success' => true,
			'message' => 'Historia registrada correctamente.',
			'asegurado' => $datos[0],
			'nro_historia' => $historiaEnIpress->nro_historia
		], 200);
	}
	/* public function registrarAseguradoES(Request $request) {

	} */

	public function detalle_paciente_creacion($id) {
		$id_establecimiento = Auth::user()->id_establecimiento;
		$dato = DB::table('asegurados as a')
			->join('users as u', DB::raw('a.created_us'), '=', DB::raw('u.id::varchar'))
			->leftJoin('users as u2', DB::raw('a.updated_us'), '=', DB::raw('u2.id::varchar'))
			->leftJoin('asegurado_historias as ah', function ($join) use ($id_establecimiento) {
				$join->on('ah.id_asegurado', '=', 'a.id')
					->where('ah.id_establecimiento', '=', $id_establecimiento)
					->where('ah.id_estado_reg', '=', 1);
			})
			->leftJoin('users as u3', DB::raw('ah.created_us'), '=', DB::raw('u3.id::varchar'))
			->leftJoin('users as u4', DB::raw('ah.updated_us'), '=', DB::raw('u4.id::varchar'))
			->where('a.id', $id)
			->select(
				DB::raw("to_char(a.created_at, 'DD/MM/YYYY HH24:MI:SS') as fecha_creacion"),
				DB::raw("(u.\"name\" || ' ' || u.paterno || ' ' || u.materno || ' - ' || u.dni) as dato_creador"),
				DB::raw("to_char(a.updated_at, 'DD/MM/YYYY HH24:MI:SS') as fecha_actualizacion"),
				DB::raw("(u2.\"name\" || ' ' || u2.paterno || ' ' || u2.materno || ' - ' || u2.dni) as dato_actualizador"),
				'ah.nro_historia',
				DB::raw("to_char(ah.created_at, 'DD/MM/YYYY HH24:MI:SS') as fecha_creacion_historia"),
				DB::raw("(u3.\"name\" || ' ' || u3.paterno || ' ' || u3.materno || ' - ' || u3.dni) as dato_creador_historia"),
				DB::raw("to_char(ah.updated_at, 'DD/MM/YYYY HH24:MI:SS') as fecha_actualizacion_historia"),
				DB::raw("(u4.\"name\" || ' ' || u4.paterno || ' ' || u4.materno || ' - ' || u4.dni) as dato_actualizador_historia")
			)
    		->get();

		// Ultimo registro del historial de bloqueo de atenciones
		$bloqueo = DB::table('atenciones_bloqueo_historial as abh')
			->leftJoin('users as ub', 'ub.id', '=', 'abh.created_us')
			->where('abh.id_asegurado', $id)
			->orderBy('abh.id', 'desc')
			->select(
				'abh.accion as bloqueo_accion',
				'abh.motivo as bloqueo_motivo',
				DB::raw("to_char(abh.created_at, 'DD/MM/YYYY HH24:MI:SS') as bloqueo_fecha"),
				'ub.dni as bloqueo_usuario'
			)
			->first();

		foreach ($dato as $fila) {
			$fila->bloqueo_accion  = $bloqueo ? $bloqueo->bloqueo_accion : null;
			$fila->bloqueo_motivo  = $bloqueo ? $bloqueo->bloqueo_motivo : null;
			$fila->bloqueo_fecha   = $bloqueo ? $bloqueo->bloqueo_fecha : null;
			$fila->bloqueo_usuario = $bloqueo ? $bloqueo->bloqueo_usuario : null;
		}

		return response()->json($dato);
	}


	public function update_paciente_new(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		//dd($request->all());
		$id_user = Auth::user()->id;
		//asegurados
		$update = Paciente::where('id', '=', $request->id_asegurado)->first();

		//$update->tipo_doc_ident= $request->tipo_doc_ident;
		//id asegurado titular
		if ($request->id_asegurado_titular == "") {
			$update->id_asegurado_titular = 0;
		} else {
			$update->id_asegurado_titular = $request->id_asegurado_titular;
		}
		//id_tipo doc_identidad del asegurado
		$update->id_tipo_doc_ident = $request->id_tipo_doc_ident;
		//nro_doc_identidad del asegurado
		$update->nro_doc_ident = $request->nro_doc_ident;
		//parentesco del asegurado
		$update->id_tipo_beneficiario = $request->prtc_aseg;
		//nombre del asegurado
		$update->nombre = $request->nombre_aseg;
		//Ap_paterno del asegurado
		$update->paterno = $request->paterno_aseg;
		//Ap_materno del asegurado
		$update->materno = $request->materno_aseg;
		//sexo del asegurado
		$update->sexo = $request->sexo_aseg;
		//fecha_nacimiento del asegurado
		$update->fecha_nac = $request->fecha_nac_aseg;
		//Teléfono del asegurado
		$update->telefono = $request->cel_aseg;
		//id_tipo de sangre del asegurado
		$update->id_tipo_sangre = $request->tipo_sang_aseg;
		//correo del asegurado
		$update->correo = $request->email_aseg;
		//situacion del asegurado
		$update->situacion_paciente = $request->sit_aseg;
		//dep_domicilio del asegurado
		$update->id_departamento = $request->dep_domicilio_aseg;
		//prov_domicilio del asegurado
		$update->id_provincia = $request->prov_domicilio_aseg;
		//dist_domicilio del asegurado
		$update->id_distrito = $request->dist_domicilio_aseg;
		//dist_procedencia
		$update->id_distrito_procedencia = $request->dist_domicilio_aseg;
		//dirección domicilio del asegurado
		$update->domicilio = $request->domicilio;
		//grado de instrucción del asegurado
		$update->id_grado_instruccion = $request->id_grado_instruccion;
		//ocupación del asegurado
		$update->id_ocupacion = $request->id_ocupacion;
		//estado civil del asegurado
		$update->id_estado_civil = $request->estado_civil_aseg;
		//id_religión del asegurado
		$update->id_religion = $request->id_religion;
		//id_etnia del asegurado
		$update->id_etnia = $request->id_etnia;
		//Ubigeo del asegurado
		$update->codigo_ubig = $request->ubig_aseg ?: null;
		//id_tipo_parentesco tutor
		$update->id_tipo_parentesco = ($request->has('id_parentesco') && $request->get('id_parentesco') !== '') ? $request->id_parentesco : null;
		//discapacidad del asegurado
		$update->discapacidad = $request->has('discapacidad_aseg') ? $request->discapacidad_aseg : 0;
		//nro carnet conadis
		$update->nro_carnet_conadis = ($request->has('nro_conadis') && $request->get('nro_conadis') !== '') ? $request->nro_conadis : null;
		//tipo de documento del titular 
		$update->tipo_doc_titular = $request->tipo_doc_titu ?: null;
		//nro de documento del titular 
		$update->dni_titular = $request->nro_doc_titu_drh ?: null;
		//nombre del titular 
		$update->nombre_titular = $request->nombre_titu ?: null;
		//Ap_materno del titular 
		$update->materno_titular = $request->materno_titu ?: null;
		//Ap_paterno del titular 
		$update->paterno_titular = $request->paterno_titu ?: null;
		//Cip del titular 
		$update->cip = $request->nro_cip_titu ?: null;
		//Grado del titular 
		$update->grado = $request->grado_titu ?: null;
		//situación del titular 
		$update->situacion_titular = $request->situacion_titu ?: null;
		//Unidad del titular
		$update->unidad_pnptitular = $request->unidad_titu ?: null;
		//Departamento nacimiento del asegurado
		$update->id_departamento_nacimiento = $request->id_departamento_nacimiento ?: null;
		//Provincia nacimiento del asegurado
		$update->id_provincia_nacimiento = $request->id_provincia_nacimiento ?: null;
		//Distrito nacimiento del asegurado
		$update->id_distrito_nacimiento = $request->id_distrito_nacimiento ?: null;
		//Condicion del asegurado
		$update->condicion_asegurado = $request->condicion_asegurado ?: null;
		// Fecha inicio afiliación saludpol
		$update->fechaini_afiliacion = $request->fechaini_afiliacion ?: null;
		// Fecha fin afiliación saludpol
		$update->fechafin_afiliacion = $request->fechafin_afiliacion ?: null;
		// Estado afiliación saludpol
		$update->estado_afiliacion = $request->estado_afiliacion ?: null;
		// usuario que actualiza
		$update->updated_us = $id_user;
		// fecha que actualiza
		$update->updated_at = Carbon::now();
		// estado de registro
		$update->id_estado_reg = "1";
		//guardado
		$update->save();

		/////////////////////////////////////////////////////////////////// ASIGNAR TUTOR
		if (!empty($request->id_parentesco)) {
			$model_tutor = new Tutor();

			$obj = new stdClass();
			$obj->tipo_documento    = $request->tipo_documento;
			$obj->numero_documento  = $request->numero_documento;
			$obj->nombres           = $request->nombres;
			$obj->apellidos         = $request->apellidos;
			$obj->telefono_tutor    = $request->telefono_tutor;
			$obj->observacion_tutor = $request->observacion_tutor;
			$obj->id_parentesco     = $request->id_parentesco;
			$obj->direccion         = $request->direccion;
			$obj->flag              = 1;

			if (!empty($update->id_tutor)) {
				$obj->id_tutor = $update->id_tutor;
				$model_tutor->actualizarTutor($obj);
			} else {
				$insert_tutor = $model_tutor->registrarTutor($obj);
				if ($insert_tutor) {
					$update->id_tutor = $insert_tutor;
					$update->save();
				}
			}
		}

		/*if (!empty($request->input("id_asegurado_historia")) && is_array($request->input("id_asegurado_historia"))):
			
			foreach ($request->input("id_asegurado_historia") as $key => $id_asegurado_historia):
				if (intval($id_asegurado_historia) > 0):
					if (strval($request->nro_historias_temp[$key]) !== strval($request->nro_historias[$key])):
						$update_historia = Asegurado_historia::find($id_asegurado_historia);
						$update_historia->nro_historia = $request->input("nro_historias")[$key];
						$update_historia->id_estado_reg = 1;
						$update_historia->updated_us = $id_user;

						if ($update_historia->save()):
							// $objpaciente = new Paciente();
							// $objpaciente->UpdateRecetaVale($request->dni, $request->id_establecimientos[$key], $request->input("nro_historias")[$key]);
						else:
							throw new \Exception("Error al intentar actualizar los N° de Historia Clínicas.");
						endif;
					endif;
				else:
					if (intval($request->id_establecimiento_nuevo) > 0 && trim($request->nro_historia_nuevo) !== ""):
						$asegurado_historia = new Asegurado_historia();
						$asegurado_historia->id_asegurado = $request->id_asegurado;
						$asegurado_historia->id_establecimiento = $request->id_establecimiento_nuevo;
						$asegurado_historia->nro_historia = $request->nro_historia_nuevo;
						$asegurado_historia->id_estado_reg = 1;
						$asegurado_historia->created_at = Carbon::now();
						$asegurado_historia->created_us = $id_user;

						if ($asegurado_historia->save()):
							// $objpaciente = new Paciente();
							// $objpaciente->UpdateRecetaVale($request->dni, $request->id_establecimiento_nuevo, $request->nro_historia_nuevo);
						else:
							throw new \Exception("Error al intentar registrar el nuevo N° de Historia Clínica.");
						endif;
					endif;
				endif;
			endforeach;
		endif;*/
		// consulta para obtener el creador inicial y la fecha inicial
		$aseg_auditor = DB::table('asegurados')->where('id', $request->id_asegurado)->select('created_us','created_at')->first();

		DB::table('asegurado_auditores')->insert([
			'operacion'            => '1',
			'id_asegurado'         => $update->id,
			'nro_doc_beneficiario' => $update->nro_doc_ident,
			'paterno_asegurado'    => $update->paterno,
			'materno_asegurado'    => $update->materno,
			'nombre_asegurado'     => $update->nombre,
			'id_asegurado_titular' => $update->id_asegurado_titular, 
			'id_tipo_beneficiario' => $update->id_tipo_beneficiario,
			'grado'                => $update->grado,
			'situacion'            => $update->situacion_paciente,
			'nro_doc_titular'      => !empty($update->dni_titular)  ? $update->dni_titular : $update->nro_doc_ident,
			'paterno_titular'      => $update->paterno_titular,
			'materno_titular'      => $update->materno_titular,
			'nombre_titular'       => $update->nombre_titular,
			'fechaini_afiliacion'  => $update->fechaini_afiliacion,
			'fechafin_afiliacion'  => $update->fechafin_afiliacion,
			'estado_afiliacion'    => $update->estado_afiliacion,
			'condicion_asegurado'  => $update->condicion_asegurado,
			'estado_old'           => '0',
			'estado_new'           => '1',
			'created_us'           => $id_user,
			'created_at'           => Carbon::now(),       
			// 'incial_created_us'    => $id_user,
			'incial_created_us'    => $aseg_auditor->created_us,             
			// 'iniciail_created_at'  => Carbon::now(),    
			'iniciail_created_at'  => $aseg_auditor->created_at,     
		]);
		
		Session::flash('flash_message', 'Actualización exitosa');
		return redirect('/editar_paciente/' . $request->id_asegurado);
	}

	// Registro de historia clinica nueva
	public function registrar_historia_clinica(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
		$id_user = Auth::user()->id;

		if (intval($request->id_establecimiento_nuevo) > 0 && trim($request->nro_historia_nuevo) !== ""):
			$id_establecimiento = intval($request->id_establecimiento_nuevo);
			$nro = trim($request->nro_historia_nuevo);

			// Establecimiento 76: se completa con ceros a la izquierda
			if ($id_establecimiento === 76):
				$nro = str_pad($nro, 8, '0', STR_PAD_LEFT);
			endif;

			try {
				DB::transaction(function () use ($request, $id_establecimiento, $nro, $id_user) {
					// Bloqueo por (establecimiento, N° de historia) hasta el fin de la transacción:
					// evita que dos registros simultáneos pasen la validación a la vez
					DB::select("select pg_advisory_xact_lock(?, hashtext(?))", [$id_establecimiento, $nro]);

					$existe = Asegurado_historia::where('nro_historia', $nro)
						->where('id_establecimiento', $id_establecimiento)
						->where('id_estado_reg', 1)
						->exists();

					if ($existe):
						throw new \Exception('El N° de Historia Clínica ' . $nro . ' ya existe y está asignado a otro paciente.');
					endif;

					$asegurado_historia = new Asegurado_historia();
					$asegurado_historia->id_asegurado = $request->id_asegurado;
					$asegurado_historia->id_establecimiento = $id_establecimiento;
					$asegurado_historia->nro_historia = $nro;
					$asegurado_historia->id_estado_reg = 1;
					$asegurado_historia->created_at = Carbon::now();
					$asegurado_historia->created_us = $id_user;
					$asegurado_historia->save();
				});
			} catch (\Exception $e) {
				Session::flash('flash_danger', $e->getMessage());
				return redirect('/editar_paciente/' . $request->id_asegurado);
			}
		endif;

		Session::flash('flash_message', 'Actualización exitosa');
		return redirect('/editar_paciente/' . $request->id_asegurado);
	}

	public function obtenerDetalleAsegurado ($id) {
		if (!Auth::check()) :
		return redirect('/auth/login');
		endif;

		$asegurado = DB::table('asegurados as a')
			->select(
				'a.id_tipo_doc_ident',
				'a.tipo_doc_ident',
				'a.nro_doc_ident',
				DB::raw("CONCAT(a.nombre, ' ', a.paterno, ' ', a.materno) as nombres_asegurado"),
				'a.condicion_asegurado',
				'tb.descripcion as parentesco_asegurado',
				'a.fecha_nac',
				'a.sexo',
				'a.telefono',
				'a.correo',
				'a.codigo_ubig',
				'a.grado',
				'a.domicilio',
				DB::raw("CASE WHEN a.discapacidad = 1 THEN 'SI' ELSE 'NO' END as discapacidad"),
				'a.nro_carnet_conadis',
				'gi.nombre as grado_instruccion',
				DB::raw("CONCAT(dp.descripcion, ' / ', p.descripcion, ' / ', d.descripcion) as dep_nac"),
				DB::raw("CONCAT(dp1.descripcion, ' / ', p1.descripcion, ' / ', d1.descripcion) as dep_dom"),
				'ts.descripcion as tipo_sangre',
				'ec.nombre as estado_civil',
				'r.nombre as religion',
				'o.nombre as ocupacion',
				'e.nombre_etnia as etnia',
				't.tipo_documento as tipo_documento_tutor',
				't.numero_documento as nro_doc_tutor',
				'tp.descripcion as parentesco_tutor',
				't.telefono_tutor',
				DB::raw("CONCAT(t.nombres, ' ', t.apellidos) as nombres_tutor"),
				't.direccion as domicilio_tutor',
				'a.tipo_doc_titular',
				'a.dni_titular',
				'a.cip as cip_titular',
				'a.situacion_titular',
				'a.situacion_paciente',
				'a.unidad_pnptitular',
				'a.fechaini_afiliacion',
				'a.fechafin_afiliacion',
				'a.estado_afiliacion',
				DB::raw("CONCAT(a.nombre_titular, ' ', a.paterno_titular, ' ', a.materno_titular) as nombres_titu"),
				DB::raw("CONCAT(u.name, ' ', u.paterno, ' ', u.materno) as nombres_user_actualiza"),
				'u.dni as dni_user_actualiza',
				DB::raw("CONCAT(u1.name, ' ', u1.paterno, ' ', u1.materno) as nombres_user_crea"),
				'u1.dni as dni_user_crea'
			)
			->leftJoin('tipo_beneficiarios as tb', 'tb.id', '=', 'a.id_tipo_beneficiario')
			->leftJoin('tipo_sangres as ts', 'ts.id', '=', 'a.id_tipo_sangre')
			->leftJoin('estado_civiles as ec', 'ec.id', '=', 'a.id_estado_civil')
			->leftJoin('religiones as r', 'r.id', '=', 'a.id_religion')
			->leftJoin('ocupaciones as o', 'o.id', '=', 'a.id_ocupacion')
			->leftJoin('etnias as e', 'e.id', '=', 'a.id_etnia')
			->leftJoin('tutores as t', 't.id', '=', 'a.id_tutor')
			->leftJoin('tipo_parentescos as tp', 'tp.id', '=', 't.id_parentesco')
			->leftJoin('grado_instrucciones as gi', 'gi.id', '=', 'a.id_grado_instruccion')
			->leftJoin('departamentos as dp', 'dp.id_departamento', '=', 'a.id_departamento_nacimiento')
			->leftJoin('provincias as p', 'p.id_provincia', '=', 'a.id_provincia_nacimiento')
			->leftJoin('distritos as d', 'd.id_distrito', '=', 'a.id_distrito_nacimiento')
			->leftJoin('departamentos as dp1', 'dp1.id_departamento', '=', 'a.id_departamento')
			->leftJoin('provincias as p1', 'p1.id_provincia', '=', 'a.id_provincia')
			->leftJoin('distritos as d1', 'd1.id_distrito', '=', 'a.id_distrito')
			->leftJoin('users as u', 'u.id', '=', DB::raw('a.updated_us::integer'))
			->leftJoin('users as u1', 'u1.id', '=', DB::raw('a.created_us::integer'))
			->where('a.id', $id)
			->first();

			$log = new \App\Models\LogWs;
			$log->ws = 'visualizar_asegurado';
			$log->tipo_doc_buscado = $asegurado->id_tipo_doc_ident;
			$log->doc_buscado = $asegurado->nro_doc_ident;
			$log->created_at = Carbon::now();
			$log->created_us = Auth::user()->id;
			$log->save();
			
		return response()->json($asegurado);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////// NUEVO ASEGURADO
	public function registrar_paciente2(Request $request){
		if (!Auth::check()) {
			return redirect('/auth/login');
		}

		$id_user = Auth::user()->id;

		$paciente = new Paciente();
		//////////////////////////////////////////////////////////////////////////////////////
		//id_tipo doc_identidad del asegurado
		$paciente->id_tipo_doc_ident = $request->id_tipo_doc_ident;
		//nro_doc_identidad del asegurado
		$paciente->nro_doc_ident = $request->nro_doc_ident;
		//parentesco del asegurado
		$paciente->id_tipo_beneficiario = $request->prtc_aseg;
		//nombre del asegurado
		$paciente->nombre = $request->nombre_aseg;
		//Ap_paterno del asegurado
		$paciente->paterno = $request->paterno_aseg;
		//Ap_materno del asegurado
		$paciente->materno = $request->materno_aseg;
		//sexo del asegurado
		$paciente->sexo = $request->sexo_aseg;
		//fecha_nacimiento del asegurado
		$paciente->fecha_nac = $request->fecha_nac_aseg;
		//Teléfono del asegurado
		$paciente->telefono = $request->cel_aseg;
		//id_tipo de sangre del asegurado
		$paciente->id_tipo_sangre = $request->tipo_sang_aseg;
		//correo del asegurado
		$paciente->correo = $request->email_aseg;
		//situacion del asegurado
		$paciente->situacion_paciente = $request->sit_aseg;
		//dep_domicilio del asegurado
		$paciente->id_departamento = $request->dep_domicilio_aseg;
		//prov_domicilio del asegurado
		$paciente->id_provincia = $request->prov_domicilio_aseg;
		//dist_domicilio del asegurado
		$paciente->id_distrito = $request->dist_domicilio_aseg;
		$paciente->id_distrito_procedencia = $request->dist_domicilio_aseg;

		//dirección domicilio del asegurado
		$paciente->domicilio = $request->domicilio;
		//grado de instrucción del asegurado
		$paciente->id_grado_instruccion = $request->id_grado_instruccion;
		//ocupación del asegurado
		$paciente->id_ocupacion = $request->id_ocupacion;
		//estado civil del asegurado
		$paciente->id_estado_civil = $request->estado_civil_aseg;
		//id_religión del asegurado
		$paciente->id_religion = $request->id_religion;
		//id_etnia del asegurado
		$paciente->id_etnia = $request->id_etnia;
		//Ubigeo del asegurado
		$paciente->codigo_ubig = $request->ubig_aseg ?: null;
		//id_tipo_parentesco tutor
		$paciente->id_tipo_parentesco = ($request->has('id_parentesco') && $request->get('id_parentesco') !== '') ? $request->id_parentesco : null;
		//discapacidad del asegurado
		$paciente->discapacidad = $request->has('discapacidad_aseg') ? $request->discapacidad_aseg : 0;
		//nro carnet conadis
		$paciente->nro_carnet_conadis = ($request->has('nro_conadis') && $request->get('nro_conadis') !== '') ? $request->nro_conadis : null;
		//tipo de documento del titular 
		$paciente->tipo_doc_titular = $request->tipo_doc_titu ?: null;
		//nro de documento del titular 
		$paciente->dni_titular = $request->nro_doc_titu_drh ?: null;
		//nombre del titular 
		$paciente->nombre_titular = $request->nombre_titu ?: null;
		//Ap_materno del titular 
		$paciente->materno_titular = $request->materno_titu ?: null;
		//Ap_paterno del titular 
		$paciente->paterno_titular = $request->paterno_titu ?: null;
		//Cip del titular 
		$paciente->cip = $request->nro_cip_titu ?: null;
		//Grado del titular 
		$paciente->grado = $request->grado_titu ?: null;
		//situación del titular 
		$paciente->situacion_titular = $request->situacion_titu ?: null;
		//Unidad del titular
		$paciente->unidad_pnptitular = $request->unidad_titu ?: null;
		//Departamento nacimiento del asegurado
		$paciente->id_departamento_nacimiento = $request->id_departamento_nacimiento ?: null;
		//Provincia nacimiento del asegurado
		$paciente->id_provincia_nacimiento = $request->id_provincia_nacimiento ?: null;
		//Distrito nacimiento del asegurado
		$paciente->id_distrito_nacimiento = $request->id_distrito_nacimiento ?: null;
		//Condicion del asegurado
		$paciente->condicion_asegurado = $request->condicion_asegurado ?: null;
		// Fecha inicio afiliación saludpol
		$paciente->fechaini_afiliacion = $request->fechaini_afiliacion ?: null;
		// Fecha fin afiliación saludpol
		$paciente->fechafin_afiliacion = $request->fechafin_afiliacion ?: null;
		// Estado afiliación saludpol
		$paciente->estado_afiliacion = $request->estado_afiliacion ?: null;
		//tipo doc identidad
		$paciente->tipo_doc_ident       = $request->tipo_doc_ident;
		//////////////////////////////////////////////////////////////////////////////////////
		$paciente->created_at    = Carbon::now();
		$paciente->created_us    = $id_user;
		$paciente->id_estado_reg = 1;
		$paciente->save();

		$paciente->id_asegurado_titular = $paciente->id;
		$paciente->save();

		if (!empty($request->id_parentesco)) {
			$model_tutor = new Tutor();

			$obj = new \stdClass();
			$obj->tipo_documento    = $request->tipo_documento;
			$obj->numero_documento  = $request->numero_documento;
			$obj->nombres           = $request->nombres;
			$obj->apellidos         = $request->apellidos;
			$obj->telefono_tutor    = $request->telefono_tutor;
			$obj->observacion_tutor = $request->observacion_tutor;
			$obj->id_parentesco     = $request->id_parentesco;
			$obj->direccion         = $request->direccion;
			$obj->flag              = 1;

			$insert_tutor = $model_tutor->registrarTutor($obj);

			if ($insert_tutor) {
				$paciente->id_tutor = $insert_tutor;
				$paciente->save();
			}
		}

		if (isset($request->id_establecimiento) && !empty($request->nro_historia)) {
			$new_historia = new Asegurado_historia();
			$new_historia->id_asegurado          = $paciente->id;
			$new_historia->id_establecimiento    = $request->id_establecimiento;
			$new_historia->nro_historia          = $request->nro_historia;
			$new_historia->id_estado_reg         = 1;
			$new_historia->created_us            = $id_user;
			$new_historia->id_user               = $id_user;
			$new_historia->id_user_hc            = null;
			$new_historia->id_consultorio_origen = null;
			$new_historia->estado_hc             = 0;
			$new_historia->id_consultorio_destino = null;
			$new_historia->bak_historia          = null;
			$new_historia->nro_historia_lns      = null;
			$new_historia->save();
		}

		DB::table('asegurado_auditores')->insert([
			'operacion'            => '0',
			'id_asegurado'         => $paciente->id,
			'nro_doc_beneficiario' => $paciente->nro_doc_ident,
			'paterno_asegurado'    => $paciente->paterno,
			'materno_asegurado'    => $paciente->materno,
			'nombre_asegurado'     => $paciente->nombre,
			'id_asegurado_titular' => $paciente->id_asegurado_titular, 
			'id_tipo_beneficiario' => $paciente->id_tipo_beneficiario,
			'grado'                => $paciente->grado,
			'situacion'            => $paciente->situacion_paciente,
			'nro_doc_titular'      => $paciente->dni_titular,
			'paterno_titular'      => $paciente->paterno_titular,
			'materno_titular'      => $paciente->materno_titular,
			'nombre_titular'       => $paciente->nombre_titular,
			'fechaini_afiliacion'  => $paciente->fechaini_afiliacion,
			'fechafin_afiliacion'  => $paciente->fechafin_afiliacion,
			'estado_afiliacion'    => $paciente->estado_afiliacion,
			'condicion_asegurado'  => $paciente->condicion_asegurado,
			'estado_old'           => '0',
			'estado_new'           => '1',
			'created_us'           => $id_user,
			'created_at'           => Carbon::now(),       
			'incial_created_us'    => $id_user,            
			'iniciail_created_at'  => Carbon::now(),       
		]);
		
		Session::flash('flash_message', 'Registro exitoso');
		return redirect('/all_asegurados');
	}

}
