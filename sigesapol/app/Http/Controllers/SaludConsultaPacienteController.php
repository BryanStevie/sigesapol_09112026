<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use SoapClient;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Consultorio;
use App\Models\Farmacia;
use App\Models\Paciente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;


class SaludConsultaPacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function index()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if( Auth::user()->actualizo_passw == 0):
            return redirect('/dashboard')->withInput()->withErrors(array('Debe actualizar su contraseña.'));
        endif;

        if( Auth::user()->flag_consultor == 1):
                return view('frontend.salud_consulta_paciente.salud_consulta_paciente');
        else:
            return redirect('/dashboard')->withInput()->withErrors(array('No tiene acceso al módulo de consultas.'));
        endif;
    }
    public function getAseguradoDataByDNI($dni)
    {
        $asegurado = new Paciente();
        $dataAseguradoById = $asegurado->GetAseguradosSoloDNI($dni);
        if (!isset($dataAseguradoById)) {
            return response()->json([
                'data' => $dataAseguradoById,
                'message' => 'El DNI ingresado no pertenece a un asegurado.'
            ], 404);
        }
        return response()->json($dataAseguradoById);
    }
    public function getAseguradoDataByDocument($tipo_doc, $nro_doc){
        $tipoDoc = $tipo_doc;
        $nroDoc = $nro_doc;

        $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
        $asegurado = $client->getAseguradoValidate($tipoDoc, $nroDoc);

        if (!isset($asegurado->nrodoctitular)) {
            return response()->json([
                'message' => 'El numero de documento ingresado no pertenece a un asegurado.'
            ], 404);
        }
        return response()->json($asegurado);
    }
    public function getServiciosByIpress($idFarmacia)
    {
        $results = '';

        if($idFarmacia != 0){

            $iPress = DB::table('farmacias as f')
            ->join('establecimientos as e', 'f.id_establecimiento', '=', 'e.id')
            ->where('f.id', '=', $idFarmacia)
            ->select('e.id as id_establecimiento', 'e.nombre as nombre_establecimiento')
            ->first();
            
            $results = DB::table('sub_consultorios as sc')
            ->join('establecimientos as e', 'sc.id_establecimiento', '=', 'e.id')
            ->join('consultorios as c', function ($join) {
                $join->on('sc.id_consultorio', '=', 'c.id')
                ->where('c.estado', '=', 4);
            })
            ->where('e.id', '=', $iPress->id_establecimiento)
            ->groupBy('sc.id_establecimiento', 'e.nombre', 'sc.id_consultorio', 'c.nombre')
            ->select('sc.id_establecimiento', 'e.nombre', 'sc.id_consultorio', 'c.nombre')->orderBy('c.nombre', 'asc')
            ->get();
        }else{
            $results = DB::table('sub_consultorios as sc')
            ->join('establecimientos as e', 'sc.id_establecimiento', '=', 'e.id')
            ->join('consultorios as c', function ($join) {
                $join->on('sc.id_consultorio', '=', 'c.id')
                ->where('c.estado', '=', 4);
            })
            ->groupBy('sc.id_consultorio', 'c.nombre')
            ->select('sc.id_consultorio', 'c.nombre')->orderBy('c.nombre', 'asc')
            ->get();
        }

        return response()->json($results);
    }
    public function getIpressFromFarmacia($idFarmacia)
    {
        $farmacia = new Farmacia();
        $ipress = $farmacia->GetIpress($idFarmacia);
        return response()->json($ipress);
    }
    public function listarCitas(Request $request)
    {
        /* CONSULTA */
        $citas = new Cita();
        $p[] = $request->fechaInic; //FECHA INICIO
        $p[] = $request->fechaFin;  //FECHA FIN
        $p[] = ""; //FECHA REGI INICIAL
        $p[] = ""; //FECHA REGI FINAL
        $p[] = $request->dni; //DNI
        $p[] = "";  //NOMBRE
        $p[] = $request->idServicio;  //ID SERVICIO
        $p[] = 0; //ID CONSULTORIO
        $p[] = 0; //ID MEDICO
        $p[] = /* $request->id_establecimiento */$request->idIpress; //ID ESTABLECIMIENTO
        $p[] = 0; //ID CONDICION ESTABLECIMIENTO
        $p[] = 0; //ID CONDICION SERVICIO
        $p[] = 0; //ID_ESTADO
        $p[] = $request->NumeroPagina; //P_PAGINA
        $p[] = $request->NumeroRegistros;  //P_LIMIT
        $data = $citas->readFunctionPostgres('sp_cita_moduloconsultor',$p);
        $dataTotal = $citas->readFunctionPostgres('sp_cita_moduloconsultor_total',$p);

        $iTotalDisplayRecords = isset($dataTotal[0]->totalrows) ? $dataTotal[0]->totalrows : 0;
        
        $result["PageStart"] = $request->NumeroPagina;
        $result["pageSize"] = $request->NumeroRegistros;
        $result["SearchText"] = "";
        $result["ShowChildren"] = true;
        $result["iTotalRecords"] = $iTotalDisplayRecords;
        $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
    }
    public function getCitaById($id_cita){

        $cita = DB::table('citas as c')
        ->select(
            'c.id as cita_id',
            'ase.id as ase_id',
            'ase.nro_doc_ident as ase_dni',
            'e.nombre as establecimiento',
            'ase.nombre as ase_nombre',
            'ase.paterno as ase_paterno',
            'ase.materno as ase_materno',
            'c.parentesco as cita_parentesco',
            DB::raw("TO_CHAR(c.fecha_hora, 'DD/MM/YY') as cita_fecha"),
            DB::raw("TO_CHAR(c.fecha_hora, 'HH24:MI AM') as cita_hora"),
            DB::raw("CONCAT(m.paterno, ' ', m.materno, ' ', m.nombre) as medico_nombres"),
            'c.grado as ase_grado',
            'ase.correo as ase_correo',
            'tc.descripcion as cita_tipo_cita',
            'cons.nombre as cita_servicio',
            'sc.descripcion as cita_consultorio'
        )
        ->join('asegurados as ase', 'c.id_asegurado', '=', 'ase.id')
        ->leftJoin('medicos as m', 'c.id_medico', '=', 'm.id')
        ->leftJoin('tipo_cita as tc', 'c.id_tipo_cita', '=', 'tc.id')
        ->join('sub_consultorios as sc', 'c.id_sub_consultorio', '=', 'sc.id')
        ->join('consultorios as cons', 'sc.id_consultorio', '=', 'cons.id')
        ->join('establecimientos as e', 'c.id_establecimiento', '=', 'e.id')
        ->where('c.id', '=', $id_cita)
        ->first();

        echo(json_encode($cita));
    }
    public function listar_atenciones_medicas(Request $request){
        $model_citas = new Cita();
        $p[] = $request->fechaInic; //FECHA INICIO
        $p[] = $request->fechaFin;  //FECHA FIN
        $p[] = $request->dni;   //DNI
        $p[] = "";  //NOMBRES
        $p[] = $request->idServicio;   //ID SERVICIO
        $p[] = 0;   //ID CONSULTORIO
        $p[] = 0; // ID MEDICO
        $p[] = '';   //ID ESTADO
        $p[] = 0;   //TIPO ATENCION
        $p[] = $request->idIpress;  //ID ESTABLECIMIENTO
        $p[] = 0;   //CONDICION ESTABLECIMIENTO
        $p[] = 0;   //CONDICION SERVICIO
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;

        $data = $model_citas->readFunctionPostgres('sp_atenciones_moduloconsultor',$p);
        $dataTotal = $model_citas->readFunctionPostgres('sp_atenciones_moduloconsultor_total',$p);
        
        $iTotalDisplayRecords = isset($dataTotal[0]->totalrows) ? $dataTotal[0]->totalrows : 0;

        $result["PageStart"] = $request->NumeroPagina;
        $result["pageSize"] = $request->NumeroRegistros;
        $result["SearchText"] = "";
        $result["ShowChildren"] = true;
        $result["iTotalRecords"] = $iTotalDisplayRecords;
        $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
    }
    public function accesoAtencionMedicaPdf(Request $request){

        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
            'motivo' => 'required'
        ]);

        // Verificacion de credenciales
        if ($request->email === Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {

            $message = 'AUTH';
            $registrarIdPrestacion = null;
            $registrarIdUser = Auth::user()->id;
            $registrarIdMotivo = $request->motivo;
            $atencionPdfData = null;

            if ($request->has('id_atencion') && isset($request->id_atencion)) {
                $atencionPdfData = DB::table('prestaciones')
                    ->select('id as id_cita', 'id_asegurado', 'ruta_prestacion as ruta')
                    ->where('id', $request->id_atencion)
                    ->first();
                $registrarIdPrestacion = $request->id_atencion;
            }
            $this->registrarAccesoPdfAtencionMedica($registrarIdPrestacion, $registrarIdUser, $registrarIdMotivo);
            return response()->json([
                'response' => $message,
                'message' => $atencionPdfData
            ], JsonResponse::HTTP_OK);
        }
        return response()->json([
            'response' => 'ERROR',
            'message' => 'Las credenciales ingresadas no coinciden con el usuario activo.'
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
    public function registrarAccesoPdfAtencionMedica($id_prestacion, $id_user, $motivo){

        DB::table('historial_consulta')->insert([
            'id_prestacion' => $id_prestacion,  // Reemplaza con el valor correspondiente
            'motivo' => $motivo,  // Motivo de la consulta
            'fecha' => date('Y-m-d'),  // Fecha actual
            'hora' => date('H:i:s'),  // Hora actual
            'id_user' => $id_user  // Reemplaza con el id de usuario correspondiente
        ]);
    }
    public function listar_procedimientos(Request $request){
        $model_citas = new Cita();
        $p[] = $request->fechaInic; //FECHA INICIO
        $p[] = $request->fechaFin;  //FECHA FIN
        $p[] = $request->dni;   //DNI
        $p[] = "";  //NOMBRES
        $p[] = $request->idServicio;   //ID SERVICIO
        $p[] = 0;   //ID CONSULTORIO
        $p[] = 0; // ID MEDICO
        $p[] = '';   //ID ESTADO
        $p[] = 0;   //TIPO ATENCION
        $p[] = $request->idIpress;  //ID ESTABLECIMIENTO
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        $data = $model_citas->readFunctionPostgres('sp_procedimiento_atenciones_modulo_consultor',$p);
        $dataTotal = $model_citas->readFunctionPostgres('sp_procedimiento_atenciones_modulo_consultor_total',$p);

        $iTotalDisplayRecords = count($dataTotal);

        $result["PageStart"] = $request->NumeroPagina;
        $result["pageSize"] = $request->NumeroRegistros;
        $result["SearchText"] = "";
        $result["ShowChildren"] = true;
        $result["iTotalRecords"] = $iTotalDisplayRecords;
        $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
    }
    public function listar_recetas(Request $request){
        $model_citas = new Cita();
        $p[] = $request->idIpress;  //ID ESTABLECIMIENTO
        $p[] = $request->fechaInic; //FECHA INICIO
        $p[] = $request->fechaFin;  //FECHA FIN
        $p[] = $request->dni;   //DNI
        $p[] = $request->idServicio;   //ID SERVICIO
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;

        $data = $model_citas->readFunctionPostgres('sp_recetas_moduloconsultor',$p);
        $dataTotal = $model_citas->readFunctionPostgres('sp_recetas_moduloconsultor_total',$p);

        $iTotalDisplayRecords = count($dataTotal);

        $result["PageStart"] = $request->NumeroPagina;
        $result["pageSize"] = $request->NumeroRegistros;
        $result["SearchText"] = "";
        $result["ShowChildren"] = true;
        $result["iTotalRecords"] = $iTotalDisplayRecords;
        $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
    }
    public function getDetallesRecetaById($id_receta){

        //DETALLES DE LA RECETA
        $receta_query = DB::table('receta_vales as rv')
            ->join('farmacias as f', 'f.id', '=', 'rv.id_farmacia')
            ->join('establecimientos as e', 'e.id', '=', 'f.id_establecimiento')
            ->leftJoin('diagnosticos as d', 'd.id', '=', 'rv.id_diagnostico')
            ->join('consultorios as c', 'c.id', '=', 'rv.id_consultorio')
            ->select(
                'rv.id as id_receta_vale',
                'rv.nro_receta as nro_receta',
                'rv.id_admision',
                'e.codigo as codigo_ipress',
                DB::raw("TO_CHAR(rv.fecha_registro, 'DD/MM/YY') as fecha_atencion"),
                'rv.tipo_receta as tipo_atencion',
                'rv.turno as turno',
                'rv.dni_beneficiario as dni',
                DB::raw("CONCAT(rv.paterno_beneficiario, ' ', rv.materno_beneficiario, ' ', rv.nombre_beneficiario) as paciente"),
                'c.nombre as servicio',
                'd.nombre as diagnostico',
                'rv.nro_historia as historia_clinica',
                'rv.ruta_receta as url_receta'
            )
            ->where('rv.id', $id_receta)
            ->first();

        //LISTADO DE PRODUCTOS DE LA RECETA
        $productos_receta_query = DB::table('producto_recetas as pr')
            ->join('productos as p', 'p.id', '=', 'pr.id_producto')
            ->join('unidades as u', 'u.id', '=', 'p.id_unidad')
            ->select(
                'pr.id_receta_vale as receta_id',
                'pr.id_producto as producto_id',
                'p.nombre as producto',
                'u.nombre as unidad',
                'pr.cantidad_prescrita as cantidad_prescrita',
                'pr.tiempo_periodo as tiempo',
                'pr.cantidad_dispensada as cantidad_dispensada',
                'pr.posologia as posologia'
            )
            ->where('pr.id_receta_vale', $id_receta)
            ->get();

        return response()->json([
            'data' =>[
                'receta_details' =>$receta_query,
                'receta_productos_details' => $productos_receta_query
            ]
        ]);
    }
    public function getUrlPdfRecetaById($id_receta){
        $receta_query = DB::table('receta_vales as rv')
            ->select(
                'rv.id as id_receta_vale',
                'rv.nro_receta as nro_receta',
                'rv.ruta_receta as url_receta',
                'rv.flag as flag_receta'
            )
            ->where('rv.id', $id_receta)
            ->first();
        if($receta_query->url_receta){
            return response()->json([
                'response' => 'READY',
                'message' => $receta_query
            ]);
        }
        return response()->json([
            'response' => 'ERROR',
            'message' => 'No ha sido posible ubicar el archivo PDF de la receta.'
        ], 404);
    }
    public function listar_examenes_auxiliares(Request $request){
        $model_citas = new Cita();
        $p[] = $request->idIpress;  //ID ESTABLECIMIENTO
        $p[] = $request->fechaInic; //FECHA INICIO
        $p[] = $request->fechaFin;  //FECHA FIN
        $p[] = $request->dni;   //DNI
        $p[] = $request->idServicio;   //ID SERVICIO
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;

        $data = $model_citas->readFunctionPostgres('sp_examenes_auxiliare_consultor',$p);
        $iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;

        $result["PageStart"] = $request->NumeroPagina;
        $result["pageSize"] = $request->NumeroRegistros;
        $result["SearchText"] = "";
        $result["ShowChildren"] = true;
        $result["iTotalRecords"] = $iTotalDisplayRecords;
        $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
    }
}
