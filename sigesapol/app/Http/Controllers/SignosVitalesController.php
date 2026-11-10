<?php

namespace App\Http\Controllers;

use App\Models\Asegurado;
use App\Models\Cama;
use App\Models\caracterFisico;
use App\Models\Cuarto;
use App\Models\Emergencia;
use App\Models\Enfermeria;
use App\Models\Prestaciones;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Nota_enfermeria;
use App\Models\Nota_enfermeria_evoluciones;
use App\Models\Nota_enfermeria_evoluciones_detalle;
use App\Models\Hospitalizacione;
use App\Models\Paciente;
use App\Models\Parametros;
use App\Models\signosVitales;
use App\Models\Signo_vitales_historico;
use App\Models\signosVitalesDetalles;
use App\Models\User;
use Archive7z\Archive7z;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SignosVitalesController extends Controller
{

    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function formulario_nota_ingreso($id_atencion, $id_tipo_atenciones)
    {
    }
    public function signosVitales_Global($id_asegurado)
    {

        $modelpaciente = new Paciente();
        $paciente_signos_vitales = $modelpaciente->geaAseguradoby_id_signos_vitales($id_asegurado);
        $caracteres_fisico = CaracterFisico::where('flag_signos_vitales', 1)
            ->where('estado', 1)
            ->get();

        $caracteresFisicoArray = $caracteres_fisico->pluck('descripcion', 'id')->toArray();
       
        $signovital = DB::table('signo_vitales')
        ->select('signo_vitales.*')
        ->where('id_enfermeria',$paciente_signos_vitales->id_enfermeria)
        ->orderBy('created_at', 'desc')
        ->first();
        if (isset($signovital) ){
            
            $signovital_data_1 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital',$signovital->id)
            ->where('id_caracter_fisico','1')
            ->orderBy('created_at', 'desc')
            ->first();
       
            $signovital_data_2 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital',$signovital->id)
            ->where('id_caracter_fisico','2')
            ->first();
    
            $signovital_data_3 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital',$signovital->id)
            ->where('id_caracter_fisico','4')
            ->orderBy('created_at', 'desc')
            ->first();
       
            $signovital_data_4 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital',$signovital->id)
            ->where('id_caracter_fisico','6')
            ->orderBy('created_at', 'desc')
            ->first();
      
            $signovital_data_5 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital',$signovital->id)
            ->where('id_caracter_fisico','9')
            ->orderBy('created_at', 'desc')
            ->first();
    
            $signovital_data_6 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital',$signovital->id)
            ->where('id_caracter_fisico','17')
            ->orderBy('created_at', 'desc')
            ->first();

        }else{

            $signovital_data_1 = null;
            $signovital_data_2 = null;
            $signovital_data_3 = null;
            $signovital_data_4 = null;
            $signovital_data_5 = null;
            $signovital_data_6 = null;
        }


        return response()->json([
            'paciente' => $paciente_signos_vitales,
            'caracteres_fisico' => $caracteresFisicoArray,
            'signovital' => $signovital,
            'signovital_data_6' => $signovital_data_6,
            'signovital_data_5' => $signovital_data_5,
            'signovital_data_4' => $signovital_data_4,
            'signovital_data_3' => $signovital_data_3,
            'signovital_data_2' => $signovital_data_2,
            'signovital_data_1' => $signovital_data_1
        ]);
    }

    public function guardarSignosVitales(Request $request)
    {
        $user = Auth::user();
      
        $enfermeria = Enfermeria::find($request->id_enfermeria_register);
        
        if (!$enfermeria) {
            return response()->json(['error' => 'Enfermeria no encontrada'], 404);
        }

    
        $signosVitales = new SignosVitales();
        $signosVitales->id_tipo_atencion = 2; 
        $signosVitales->id_enfermeria = $request->id_enfermeria_register;
        $signosVitales->dias_estancia = $request->dias_estancia;
        $signosVitales->created_us = $user->id;
        $signosVitales->fecha_atencion  = date('Y-m-d H:i:s'); 
        $signosVitales->created_at  = date('Y-m-d H:i:s');
        $signosVitales->save();

        $id_ext_enfermeria = $enfermeria->id_ext_enfermeria;
        $emergencia = Emergencia::find($id_ext_enfermeria);
        $cama_estado = $emergencia ? $emergencia->cama_estado : null;

        $cama = Cama::find($cama_estado);
        $nombre_cama = $cama ? $cama->nombre : null;
        $nombre_atencion = $cama ? $cama->nombre_atencion : null;

        $cuarto = $cama ? Cuarto::find($cama->id_cuarto) : null;
        $nombre_cuarto = $cuarto ? $cuarto->nombre : null;

        $signosVitales['name_profesional'] = $user->name;
        $signosVitales['id_ext_enfermeria'] = $id_ext_enfermeria;
        $signosVitales['cama_estado'] = $cama_estado;
        $signosVitales['nombre_cama'] = $nombre_cama;
        $signosVitales['nombre_cuarto'] = $nombre_cuarto;
        $signosVitales['nombre_atencion'] = $nombre_atencion;
        $signosVitales['diuresis'] = $user->diuresis;
        $signosVitales['deposiciones'] = $user->deposiciones;

        $detallesSignos = [];
        if ($request->has('caracter_fisico')) {
            foreach ($request->caracter_fisico as $index => $id_caracter_fisico) {
                $signosVitalesDetalles = new signosVitalesDetalles();
                $signosVitalesDetalles->id_signo_vital = $signosVitales->id;
                $signosVitalesDetalles->id_caracter_fisico = $id_caracter_fisico;
                $signosVitalesDetalles->id_turno = $request->input("id_turno.$index");
                $signosVitalesDetalles->descripcion = $request->input("caracter_fisico_valor_$id_caracter_fisico");
                $signosVitalesDetalles->created_us = Auth::id();
                $signosVitalesDetalles->save();

                $caracterFisico = $id_caracter_fisico;
                $descripcion = $request->input("caracter_fisico_valor_$id_caracter_fisico");

                $detallesSignos[] = [
                    'id_caracter_fisico' => $id_caracter_fisico,
                    'descripcion' => $descripcion
                ];
            }
        }

        $signosVitales['detalles_signos'] = $detallesSignos;

        return response()->json(['message' => 'Signos vitales guardados correctamente', 'data' => $signosVitales]);
    }

    public function obtenerSignoVitalPorAsegurado(Request $request)
    {
        $id_asegurado = $request->id_asegurado;
       
        $emergencias = Emergencia::where('id_asegurado', $id_asegurado)->get();
    
        if ($emergencias->isEmpty()) {
            return response()->json(['error' => 'No se encontró emergencia para el asegurado'], 404);
        }
    
        $enfermeria = null;
        $selectedEmergencia = $emergencias;
    
        foreach ($emergencias as $emergencia) {
            $enfermeria = Enfermeria::where('id_ext_enfermeria', $emergencia->id)->first();
            if ($enfermeria) {
                $selectedEmergencia = $emergencia;
                break;
            }
        }

        if (!$enfermeria) {
            return response()->json(['error' => 'No se encontró enfermería para la emergencia'], 404);
        }
      
        $user = User::where('id', $selectedEmergencia->id_medico_triaje)->first();
        $userName = $user ? $user->name : 'Desconocido';
    

        $cama = Cama::select('id','nombre','nombre_atencion','id_cuarto')->where('id',$selectedEmergencia->cama_estado)->first();
   
        $cuartos = Cuarto::select('id','nombre')->where('id',$cama->id_cuarto)->first();
   
        $signoVitales = "SELECT
                             * 
                        FROM signo_vitales sv
                        LEFT JOIN signo_vitales_detalles svd ON sv.id = svd.id_signo_vital
                        LEFT JOIN caracter_fisicos cf on svd.id_caracter_fisico = cf.id 
                            where id_enfermeria = '$enfermeria->id'";
        $signoVitales = DB::select($signoVitales);

        foreach ($signoVitales as $signoVital) {
            $signoVital->user_name = $userName;
            
            $signoVital->nombre=$cama->nombre;
            $signoVital->nombre_atencion=$cama->nombre_atencion;
            $signoVital->cuartos_nombre=$cuartos->nombre;

        }
       
        return response()->json(['data' => $signoVitales]);
    }
    
    public function signos_vitales_listado($id_asegurado,$id_enfermeria)
    {
        $enfermeria =  DB::table('enfermerias')
        ->select('enfermerias.*')
        ->where('id',$id_enfermeria)
        ->first();
        $id_tipo_atenciones = '2';
        $nota_ingreso = new Nota_enfermeria; 
        $model_user = new User();
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_enfermeria, $id_tipo_atenciones); 
        $status_token = $model_user->TieneTokenActivo(Auth::id());
      
        $signoVitales_nota = "SELECT
                         sv.*,cf.descripcion as descripcion_cf
                        FROM notas_vitales_enfermerias sv
                        LEFT JOIN caracter_fisicos cf on sv.id_caracter_fisico = cf.id 
                            where id_enfermeria = '$id_enfermeria'";
        $signoVitales_nota = DB::select($signoVitales_nota);
        
        
        $existeAtencionMedica = Prestaciones::where('id_ext', '=', $datos_nota_ingreso[0]->id_ext_enfermeria)
                                ->where('id_tipo_atencion', $id_tipo_atenciones)
                                ->exists();
        
        if (!$existeAtencionMedica) {
            $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforEmergencia_sa($id_enfermeria);
        } else {
            $dato_cabecera_jhon = "SELECT
                    e3.nombre as establecimiento,
                    ah.nro_historia AS historia_clinica,
                    e2.id::varchar as id_emergencia,
                    LPAD(e2.id::VARCHAR, 10, '0')::varchar AS nro_emergencia,
                    a.nro_doc_ident::TEXT AS dni,
                    a.nombre AS asegurado_nombre,
                    a.paterno AS asegurado_paterno,
                    a.materno AS asegurado_materno,
                    a.sexo::varchar AS sexo,
                    EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios,
                    EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses,
                    EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias,
                    a.fecha_nac::date AS asegurado_fecha_nacimiento,
                    a.grado AS asegurado_grado,
                    a.cip AS asegurado_carnet,        
                    tb.descripcion AS asegurado_tipo_beneficiario,
                    e2.talla_cm AS asegurado_talla,
                    e2.peso_kg AS asegurado_peso,
                    e2.temperatura AS asegurado_temperatura,
                    e2.presion_arterial_x AS asegurado_presion_arterial_x,
                    e2.presion_arterial_y AS asegurado_presion_arterial_y,
                    e2.sa02_porcentaje AS asegurado_sa02_porcentaje,
                    e2.frecuencia_respiratoria AS asegurado_frecuencia_respiratoria,
                    e2.frecuencia_cardiaca AS asegurado_frecuencia_cardiaca,
                    e2.fi02 AS asegurado_fi02,
                    a.tipo_sangre::varchar AS asegurado_tipo_sangre,
                    e2.nombre_conducido AS asegurado_acompañante,
                    e2.fecha_registro_ingreso AS fecha_registro_ingreso,
                    e3.nivel::varchar AS nivel_ipress,
                    e3.codigo AS codigo_renipress,
                    t.descripcion AS servicio,
                    p3.nombre AS pabellon,
                    p2.nombre AS piso,
                    c2.nombre AS cuarto,
                    c.nombre AS cama,
                    m.name AS nombre_medico,
                    m.paterno AS paterno_medico,
                    m.materno AS materno_medico,
                    m.dni AS dni_medico,
                    e4.nombre AS especialidad_medico
                FROM
                    enfermerias e
                LEFT JOIN emergencias e2 ON
                    e.id_ext_enfermeria = e2.id
                LEFT JOIN prestaciones p ON
                    p.id_ext = e2.id 
                LEFT JOIN asegurados a ON
                    e2.id_asegurado = a.id 
                LEFT JOIN asegurado_historias ah ON
                    ah.id_asegurado = e2.id_asegurado AND ah.id_establecimiento = e2.id_establecimiento
                LEFT JOIN tipo_beneficiarios tb ON
                    a.id_tipo_beneficiario = tb.id
                LEFT JOIN establecimientos e3 ON
                    e2.id_establecimiento = e3.id
                LEFT JOIN triajes t ON
                    e2.id_triaje = t.id
                LEFT JOIN pisos p2 ON
                    p.id_establecimiento = p2.id_establecimiento AND p2.nombre_atencion = 'EMERGENCIA'
                LEFT JOIN pabellones p3 ON
                    p2.id_pabellon = p3.id
                INNER JOIN camas c ON
                    e2.cama_estado = c.id
                INNER JOIN cuartos c2 ON
                    c.id_cuarto = c2.id 
                LEFT JOIN users m ON
                    e2.id_medico_triaje = m.id
                left join medicos m2 on
                    m2.dni = m.dni
                LEFT JOIN especializaciones e4 ON
                    m2.id_especializacion = e4.id
                WHERE
                    e.id ='$id_enfermeria' ";
            $dato_cabecera_jhon = DB::select($dato_cabecera_jhon);
        }

        /* $grado_asegurado = Asegurado::where('asegurados.nro_doc_ident', '=', $dato_cabecera_jhon[0]->dni)->select('asegurados.grado as asegurado_grado')->get();
        $cip_asegurado = Asegurado::where('asegurados.nro_doc_ident', '=', $dato_cabecera_jhon[0]->dni)->select('asegurados.cip as asegurado_cip')->get(); */

        $antecedentes = DB::table('antecedentes_enfermerias')
        ->select('antecedentes_enfermerias.*')
        ->where('id_enfermeria',$id_enfermeria)
        ->first();

        $estado = '1';
        $arreglo_lista = [];
        $i = 0;

$dato_signo_vitales = DB::select("SELECT * FROM signo_vitales WHERE id_enfermeria = ?", [$id_enfermeria]);

if (is_null($signoVitales_nota[4]->descripcion)) {
    $dato_peso =  $dato_cabecera_jhon[0]->asegurado_peso;
}else{
    $dato_peso = $signoVitales_nota[4]->descripcion;
}

$fecha_ingreso = $dato_cabecera_jhon[0]->fecha_registro_ingreso;
$fechaIngreso = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_ingreso);
$fechaActual = Carbon::now();
$diasTranscurridos = $fechaIngreso->diffInDays($fechaActual);

if($diasTranscurridos == 0){
    $diasTranscurridos = $diasTranscurridos + 1;
}

$fecha_actual = $fechaActual->format('d-m-Y');
$madrugada = 0;
$mañana = 0;
$tarde = 0;
$noche = 0;
if (empty($dato_signo_vitales)) {
    $estado = '0';
    $datosPuntos = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos2 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos3 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos4 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos5 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos6 = [
        ["x" => 0, "y" => 0],
    ];
    $datosBarras = [
        ['label' => 'SIN DATOS', 'start' => 0, 'end' => 0],
    ];
} else {

    foreach($dato_signo_vitales as $signo) {
        $arreglo_lista[$i] = new \stdClass();

        $actualizado = DB::table('signo_vitales_historico')
        ->select('signo_vitales_historico.*')
        ->where('id_signo_vital',$signo->id)
        ->first();
        if ($actualizado === null) {
            $arreglo_lista[$i]->seactualizo = 0;
        } else {
            $arreglo_lista[$i]->seactualizo = 1;
        }
        $solo_fecha = 0;

        $dato1 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '1')
            ->first();

        $dato2 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '2')
            ->first();
    
        $dato3 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '4')
            ->first();
    
        $dato4 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '6')
            ->first();
    
        $dato5 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '9')
            ->first();
    
        $dato6 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '17')
            ->first();

        $dato7 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '58')
            ->first();

            $dato1CreatedAt = Carbon::parse($dato1->created_at);

            if ($dato1CreatedAt->isSameDay($fechaActual)) {
                if($dato1->id_turno == 1){
                    $madrugada = 1;
                }
                if($dato1->id_turno == 2){
                    $mañana = 1;
                }
                if($dato1->id_turno == 3){
                    $tarde = 1;
                }
                if($dato1->id_turno == 4){
                    $noche = 1;
                }
            } 
            $codigo_fecha = $dato1->created_at;
            list($fecha, $hora) = explode(' ', $codigo_fecha);
            list($anio, $mes, $dia) = explode('-', $fecha);
            $mes = (int)$mes;
            $dia = (int)$dia;
            $diamesanio = $dia . $mes . $anio;
            $solo_fecha = substr($codigo_fecha, 0, 10);
            $arreglo_lista[$i]->codigo_turno = $diamesanio;
            $ini = $solo_fecha.' 00:00:00';
            $fin = $solo_fecha.' 23:59:59';
            $dato_balance_hidrico1 =("SELECT * 
            FROM balance_hidricos
            WHERE id_enfermeria =  '$id_enfermeria'
            AND created_at BETWEEN '$ini' AND '$fin' ");
             $dato_balance_hidrico = DB::select($dato_balance_hidrico1);
            if (empty($dato_balance_hidrico)) {
                $arreglo_lista[$i]->balance_turno = '-';
                $arreglo_lista[$i]->balance_x24 = '-';
                $arreglo_lista[$i]->diuresis = '-';
                $arreglo_lista[$i]->deposisiones  = '-';
                $arreglo_lista[$i]->drenajes = '-';
            }else{
                foreach($dato_balance_hidrico as $balance){
                            $calcula_turno = "SELECT 
                                                (SUM(CASE WHEN tipo_balance = 1 AND id_caracter_fisico != 46 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 OR (tipo_balance = 1 AND es_visible != 0 AND id_caracter_fisico = 46) THEN total_m ELSE 0 END)) AS diferencia_total_m
                                            FROM (
                                                SELECT 
                                                    balance_hidrico_detalles.codigo_agrupacion,
                                                    SUM(CASE 
                                                            WHEN balance_hidrico_detalles.id_turno = '$dato1->id_turno' THEN balance_hidrico_detalles.valor_caracter_fisico
                                                        ELSE 0 END) AS total_m,
                                                    balance_hidrico_detalles.tipo_balance,
                                                    balance_hidrico_detalles.id_caracter_fisico,
                                                    balance_hidrico_detalles.es_visible
                                                FROM balance_hidrico_detalles
                                                JOIN balance_hidricos ON balance_hidrico_detalles.id_balance_hidrico = balance_hidricos.id
                                                WHERE balance_hidrico_detalles.estado = 1
                                                AND balance_hidrico_detalles.es_visible = 1
                                                AND (balance_hidrico_detalles.tipo_balance = 1 OR balance_hidrico_detalles.tipo_balance = 2)
                                                AND balance_hidricos.created_at BETWEEN '$ini' AND '$fin'
                                                AND balance_hidricos.id_asegurado = '$id_asegurado'
                                                GROUP BY balance_hidrico_detalles.codigo_agrupacion, balance_hidrico_detalles.descripcion, balance_hidrico_detalles.id_caracter_fisico, balance_hidrico_detalles.es_visible, balance_hidrico_detalles.tipo_balance
                                            ) AS subquery;";
                                $calcula_turno = DB::select($calcula_turno);
                                $calcula_turno = $calcula_turno[0];
                                $balance_xturno = $calcula_turno->diferencia_total_m;
                            ////////////////////////////
                            $calcula_24h = "SELECT 
                                                /* (SUM(CASE WHEN tipo_balance = 1 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 THEN total_m ELSE 0 END)) AS diferencia_total_m */
                                                (SUM(CASE WHEN tipo_balance = 1 AND id_caracter_fisico != 46 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 OR (tipo_balance = 1 AND es_visible != 0 AND id_caracter_fisico = 46) THEN total_m ELSE 0 END)) AS diferencia_total_m
                                            FROM (
                                                SELECT 
                                                    balance_hidrico_detalles.codigo_agrupacion,
                                                    SUM(CASE 
                                                            WHEN balance_hidrico_detalles.id_turno = 1 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 2 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 3 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 4 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                        ELSE 0 END) AS total_m,
                                                    balance_hidrico_detalles.tipo_balance,
                                                    balance_hidrico_detalles.id_caracter_fisico,
                                                    balance_hidrico_detalles.es_visible
                                                FROM balance_hidrico_detalles
                                                JOIN balance_hidricos ON balance_hidrico_detalles.id_balance_hidrico = balance_hidricos.id
                                                WHERE balance_hidrico_detalles.estado = 1
                                                AND balance_hidrico_detalles.es_visible = 1
                                                AND (balance_hidrico_detalles.tipo_balance = 1 OR balance_hidrico_detalles.tipo_balance = 2)
                                                AND balance_hidricos.created_at BETWEEN '$ini' AND '$fin'
                                                AND balance_hidricos.id_asegurado = '$id_asegurado'
                                                GROUP BY balance_hidrico_detalles.codigo_agrupacion, balance_hidrico_detalles.descripcion, balance_hidrico_detalles.id_caracter_fisico, balance_hidrico_detalles.es_visible, balance_hidrico_detalles.tipo_balance
                                            ) AS subquery;";
                                $calcula_24h = DB::select($calcula_24h);
                                $calcula_24h = $calcula_24h[0];
                                $balance_x24 = $calcula_24h->diferencia_total_m;
                              
                            $arreglo_lista[$i]->balance_turno = $balance_xturno;
                            $arreglo_lista[$i]->balance_x24 = $balance_x24 ;

                        
                }
            } 
        $responsable = $dato1 ? DB::table('users')->select('users.*')->where('id', $dato1->created_us)->first() : null;
         
        $arreglo_lista[$i]->presionx = $dato1 ? $dato1->descripcion : 'N/A';
        $arreglo_lista[$i]->presiony = $dato2 ? $dato2->descripcion : 'N/A';
        $arreglo_lista[$i]->cardiaca = $dato3 ? $dato3->descripcion : 'N/A';
        $arreglo_lista[$i]->respiratorio = $dato4 ? $dato4->descripcion : 'N/A';
        $arreglo_lista[$i]->temperatura = $dato5 ? $dato5->descripcion : 'N/A';
        $arreglo_lista[$i]->presionmedia = $dato6 ? $dato6->descripcion : 'N/A';
        $fecha = $dato1 ? $dato1->created_at : 'N/A';
        $arreglo_lista[$i]->fecha = $fecha;
        $arreglo_lista[$i]->peso = $dato_peso;
        $arreglo_lista[$i]->id_enfermeria = $id_enfermeria;
    
        $partes = explode(' ', $fecha);
        $soloFecha = $partes[0];
    
        if ($dato1 && $dato1->id_turno == '1') {
            $datoturo = 'MADRUGRADA';
        } elseif ($dato1 && $dato1->id_turno == '2') {
            $datoturo = 'MAÑANA';
        } elseif ($dato1 && $dato1->id_turno == '3') {
            $datoturo = 'TARDE';
        } else {
            $datoturo = 'NOCHE';
        }
        $arreglo_lista[$i]->id_turno = $dato1->id_turno;
        $arreglo_lista[$i]->turno = $datoturo;
        $arreglo_lista[$i]->responsable = $responsable ? $responsable->name . ', ' . $responsable->paterno . ' ' . $responsable->materno : 'N/A';
        $arreglo_lista[$i]->ubica = isset($dato_cabecera_jhon[0]) ? $dato_cabecera_jhon[0]->cuarto : 'N/A';
        $arreglo_lista[$i]->id_signos_vitales = $signo->id;
        $arreglo_lista[$i]->numero = $i + 1;
        $arreglo_lista[$i]->id_signo_vital = $dato1->id_signo_vital;

        $cardiaca = $dato1 ? $dato1->descripcion : 0;
        $cardiaca1 = $dato2 ? $dato2->descripcion : 0;
        $cardiaca2 = $dato3 ? $dato3->descripcion : 0;
        $cardiaca3 = $dato4 ? $dato4->descripcion : 0;
        $cardiaca4 = $dato5 ? $dato5->descripcion : 0;
        $cardiaca5 = $dato6 ? $dato6->descripcion : 0;

        $datosPuntos[] = [
            'x' => $datoturo.' - '.$soloFecha,
            'y' => $cardiaca
        ];
        $datosPuntos2[] = [
            'x' =>  $datoturo.' - '.$soloFecha,
            'y' => $cardiaca1
        ];
        $datosPuntos3[] = [
            'x' =>  $datoturo.' - '.$soloFecha,
            'y' => $cardiaca2
        ];
        $datosPuntos4[] = [
            'x' => $datoturo.' - '.$soloFecha,
            'y' => $cardiaca3
        ];
        if ($cardiaca4 && floatval($cardiaca4) >= 30) {
            $datosPuntos5[] = [
                'x' => $datoturo.' - '.$soloFecha,
                'y' => $cardiaca4
            ];
        }
        else{
            $datosPuntos5[] = ["x" => 0, "y" => 0];
        }
        $datosPuntos6[] = [
            'x' => $datoturo.' - '.$soloFecha,
            'y' => $cardiaca5
        ];
        $datosBarras[] = [
            ['label' => $soloFecha . ' - ' . $datoturo, 'start' => $cardiaca1, 'end' => $cardiaca],
        ];
        $i++;
    }
}   
foreach($arreglo_lista as $listado){
        $diuresis = "SELECT bhd.* FROM balance_hidricos bh 
                        left join balance_hidrico_detalles bhd on bhd.id_balance_hidrico = bh.id 
                    where bh.id_enfermeria = '$listado->id_enfermeria' AND bhd.id_turno = '$listado->id_turno'
                     AND bhd.codigo_fecha_24 = '$listado->codigo_turno' AND bhd.id_caracter_fisico = '57'";
                $diuresis =  DB::select($diuresis);
                if (is_array($diuresis) && empty($diuresis)) {
                    $listado->diuresis = '---';
                } else {
                        $diuresis = $diuresis[0];
                    $listado->diuresis = $diuresis->valor_caracter_fisico;
                }
        $deposisiones = "SELECT bhd.* FROM balance_hidricos bh 
                            left join balance_hidrico_detalles bhd on bhd.id_balance_hidrico = bh.id 
                        where bh.id_enfermeria = '$listado->id_enfermeria' AND bhd.id_turno = '$listado->id_turno'
                        AND bhd.codigo_fecha_24 = '$listado->codigo_turno' AND bhd.id_caracter_fisico = '58'";
                    $deposisiones =  DB::select($deposisiones);
                    if (is_array($deposisiones) && empty($deposisiones)) {
                    $listado->deposisiones = '---';
                    } else {
                        $deposisiones = $deposisiones[0];
                    $listado->deposisiones = $deposisiones->valor_caracter_fisico;
                    }
        $drenajes  = "SELECT bhd.* FROM balance_hidricos bh 
                            left join balance_hidrico_detalles bhd on bhd.id_balance_hidrico = bh.id 
                        where bh.id_enfermeria = '$listado->id_enfermeria' AND bhd.id_turno = '$listado->id_turno'
                        AND bhd.codigo_fecha_24 = '$listado->codigo_turno' AND bhd.id_caracter_fisico = '58'";
                    $drenajes  =  DB::select($drenajes );
                    if (is_array($drenajes ) && empty($drenajes )) {
                    $listado->drenajes  = '---';
                    } else {
                        $drenajes  = $drenajes [0];
                    $listado->drenajes  = $drenajes ->valor_caracter_fisico;
                    }        
}
        return  view('frontend.emergencia_enfermeria.ver_listado_signosvitales',
        compact('datos_nota_ingreso','antecedentes','dato_cabecera_jhon','enfermeria','signoVitales_nota','estado','fecha_actual',
        'datosPuntos2','datosPuntos3','datosPuntos4','datosPuntos5','datosPuntos6','datosBarras','dPuntos2','dPuntos','diasTranscurridos',
        'madrugada','mañana','tarde','noche',
        'id_enfermeria','id_asegurado','arreglo_lista','datosPuntos', 'status_token', 'dato_signo_vitales', 'existeAtencionMedica')
        );

    }
    public function signos_vitales_listado_hospi($id_asegurado,$id_enfermeria)
    {
        $enfermeria =  DB::table('enfermerias')
        ->select('enfermerias.*')
        ->where('id',$id_enfermeria)
        ->first();
        $id_tipo_atenciones = '3';
        $nota_ingreso = new Nota_enfermeria; 
        $model_user = new User();
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_enfermeria, $id_tipo_atenciones); 
        $status_token = $model_user->TieneTokenActivo(Auth::id());
        $signoVitales_nota = "SELECT
                         sv.*,cf.descripcion as descripcion_cf
                        FROM notas_vitales_enfermerias sv
                        LEFT JOIN caracter_fisicos cf on sv.id_caracter_fisico = cf.id 
                            where id_enfermeria = '$id_enfermeria'";
        $signoVitales_nota = DB::select($signoVitales_nota);
        
        $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforHospi($id_enfermeria);

        $antecedentes = DB::table('antecedentes_enfermerias')
        ->select('antecedentes_enfermerias.*')
        ->where('id_enfermeria',$id_enfermeria)
        ->first();

        $estado = '1';
$arreglo_lista = [];
$i = 0;

$model_nota_enfermeria = new Nota_enfermeria();
$datos_atencion = $model_nota_enfermeria->getHospitalizacionById_farmacia_new2($enfermeria->id_ext_enfermeria);

$dato_signo_vitales = DB::select("SELECT * FROM signo_vitales WHERE id_enfermeria = ?", [$id_enfermeria]);

if (is_null($signoVitales_nota[4]->descripcion)) {
    $dato_peso =  $dato_cabecera_jhon[0]->asegurado_peso;
}else{
    $dato_peso = $signoVitales_nota[4]->descripcion;
}

$fecha_ingreso = $dato_cabecera_jhon[0]->fecha_registro_ingreso;
$fechaIngreso = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_ingreso);
$fechaActual = Carbon::now();
$diasTranscurridos = $fechaIngreso->diffInDays($fechaActual);

if($diasTranscurridos == 0){
    $diasTranscurridos = $diasTranscurridos + 1;
}

$fecha_actual = $fechaActual->format('d-m-Y');
$madrugada = 0;
$mañana = 0;
$tarde = 0;
$noche = 0;
if (empty($dato_signo_vitales)) {
    $estado = '0';
    $datosPuntos = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos2 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos3 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos4 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos5 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos6 = [
        ["x" => 0, "y" => 0],
    ];
    $datosBarras = [
        ['label' => 'SIN DATOS', 'start' => 0, 'end' => 0],
    ];
} else {

    foreach($dato_signo_vitales as $signo) {
        $arreglo_lista[$i] = new \stdClass();

        $actualizado = DB::table('signo_vitales_historico')
        ->select('signo_vitales_historico.*')
        ->where('id_signo_vital',$signo->id)
        ->first();
        if ($actualizado === null) {
            $arreglo_lista[$i]->seactualizo = 0;
        } else {
            $arreglo_lista[$i]->seactualizo = 1;
        }
        $solo_fecha = 0;

        $dato1 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '1')
            ->orderBy('created_at', 'desc')
            ->first();
      
        $dato2 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '2')
            ->first();
    
        $dato3 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '4')
            ->first();
    
        $dato4 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '6')
            ->first();
    
        $dato5 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '9')
            ->first();
    
        $dato6 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '17')
            ->first();

        $dato7 = DB::table('signo_vitales_detalles')
            ->select('signo_vitales_detalles.*')
            ->where('id_signo_vital', $signo->id)
            ->where('id_caracter_fisico', '58')
            ->first();

            $dato1CreatedAt = Carbon::parse($dato1->created_at);

            if ($dato1CreatedAt->isSameDay($fechaActual)) {
                if($dato1->id_turno == 1){
                    $madrugada = 1;
                }
                if($dato1->id_turno == 2){
                    $mañana = 1;
                }
                if($dato1->id_turno == 3){
                    $tarde = 1;
                }
                if($dato1->id_turno == 4){
                    $noche = 1;
                }
            } 
            $codigo_fecha = $dato1->created_at;
            list($fecha, $hora) = explode(' ', $codigo_fecha);
            list($anio, $mes, $dia) = explode('-', $fecha);
            $mes = (int)$mes;
            $dia = (int)$dia;
            $diamesanio = $dia . $mes . $anio;
            $solo_fecha = substr($codigo_fecha, 0, 10);
            $arreglo_lista[$i]->codigo_turno = $diamesanio;
            $ini = $solo_fecha.' 00:00:00';
            $fin = $solo_fecha.' 23:59:59';
            $dato_balance_hidrico1 =("SELECT * 
            FROM balance_hidricos
            WHERE id_enfermeria =  '$id_enfermeria'
            AND created_at BETWEEN '$ini' AND '$fin' ");
             $dato_balance_hidrico = DB::select($dato_balance_hidrico1);
             
            if (empty($dato_balance_hidrico)) {
                $arreglo_lista[$i]->balance_turno = '-';
                $arreglo_lista[$i]->balance_x24 = '-';
                $arreglo_lista[$i]->diuresis = '-';
                $arreglo_lista[$i]->deposisiones  = '-';
                $arreglo_lista[$i]->drenajes = '-';
            }else{
                foreach($dato_balance_hidrico as $balance){  
                              //  dd($balance->id_turno);  
                            $calcula_turno = "SELECT 
                                                /* (SUM(CASE WHEN tipo_balance = 1 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 THEN total_m ELSE 0 END)) AS diferencia_total_m */
                                                (SUM(CASE WHEN tipo_balance = 1 AND id_caracter_fisico != 46 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 OR (tipo_balance = 1 AND es_visible != 0 AND id_caracter_fisico = 46) THEN total_m ELSE 0 END)) AS diferencia_total_m
                                            FROM (
                                                SELECT 
                                                    balance_hidrico_detalles.codigo_agrupacion,
                                                    SUM(CASE 
                                                            WHEN balance_hidrico_detalles.id_turno = '$dato1->id_turno' THEN balance_hidrico_detalles.valor_caracter_fisico
                                                        ELSE 0 END) AS total_m,
                                                    balance_hidrico_detalles.tipo_balance,
                                                    balance_hidrico_detalles.id_caracter_fisico,
                                                    balance_hidrico_detalles.es_visible
                                                FROM balance_hidrico_detalles
                                                JOIN balance_hidricos ON balance_hidrico_detalles.id_balance_hidrico = balance_hidricos.id
                                                WHERE balance_hidrico_detalles.estado = 1
                                                AND balance_hidrico_detalles.es_visible = 1
                                                AND (balance_hidrico_detalles.tipo_balance = 1 OR balance_hidrico_detalles.tipo_balance = 2)
                                                AND balance_hidricos.created_at BETWEEN '$ini' AND '$fin'
                                                AND balance_hidricos.id_asegurado = '$id_asegurado'
                                                GROUP BY balance_hidrico_detalles.codigo_agrupacion, balance_hidrico_detalles.descripcion, balance_hidrico_detalles.tipo_balance, balance_hidrico_detalles.es_visible, balance_hidrico_detalles.id_caracter_fisico
                                            ) AS subquery;";
                                $calcula_turno = DB::select($calcula_turno);
                                $calcula_turno = $calcula_turno[0];
                                $balance_xturno = $calcula_turno->diferencia_total_m;
                            ////////////////////////////
                            $calcula_24h = "SELECT 
                                                /* (SUM(CASE WHEN tipo_balance = 1 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 THEN total_m ELSE 0 END)) AS diferencia_total_m */
                                                (SUM(CASE WHEN tipo_balance = 1 AND id_caracter_fisico != 46 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 OR (tipo_balance = 1 AND es_visible != 0 AND id_caracter_fisico = 46) THEN total_m ELSE 0 END)) AS diferencia_total_m
                                            FROM (
                                                SELECT 
                                                    balance_hidrico_detalles.codigo_agrupacion,
                                                    SUM(CASE 
                                                            WHEN balance_hidrico_detalles.id_turno = 1 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 2 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 3 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 4 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                        ELSE 0 END) AS total_m,
                                                    balance_hidrico_detalles.tipo_balance,
                                                    balance_hidrico_detalles.es_visible,
                                                    balance_hidrico_detalles.id_caracter_fisico
                                                FROM balance_hidrico_detalles
                                                JOIN balance_hidricos ON balance_hidrico_detalles.id_balance_hidrico = balance_hidricos.id
                                                WHERE balance_hidrico_detalles.estado = 1
                                                AND balance_hidrico_detalles.es_visible = 1
                                                AND (balance_hidrico_detalles.tipo_balance = 1 OR balance_hidrico_detalles.tipo_balance = 2)
                                                AND balance_hidricos.created_at BETWEEN '$ini' AND '$fin'
                                                AND balance_hidricos.id_asegurado = '$id_asegurado'
                                                GROUP BY balance_hidrico_detalles.codigo_agrupacion, balance_hidrico_detalles.descripcion, balance_hidrico_detalles.tipo_balance, balance_hidrico_detalles.es_visible, balance_hidrico_detalles.id_caracter_fisico
                                            ) AS subquery;";
                                $calcula_24h = DB::select($calcula_24h);
                                $calcula_24h = $calcula_24h[0];
                                $balance_x24 = $calcula_24h->diferencia_total_m;
                              
                            $arreglo_lista[$i]->balance_turno = $balance_xturno;
                            $arreglo_lista[$i]->balance_x24 = $balance_x24 ;

                        
                }
            } 
        $responsable = $dato1 ? DB::table('users')->select('users.*')->where('id', $dato1->created_us)->first() : null;
         
        $arreglo_lista[$i]->presionx = $dato1 ? $dato1->descripcion : 'N/A';
        $arreglo_lista[$i]->presiony = $dato2 ? $dato2->descripcion : 'N/A';
        $arreglo_lista[$i]->cardiaca = $dato3 ? $dato3->descripcion : 'N/A';
        $arreglo_lista[$i]->respiratorio = $dato4 ? $dato4->descripcion : 'N/A';
        $arreglo_lista[$i]->temperatura = $dato5 ? $dato5->descripcion : 'N/A';
        $arreglo_lista[$i]->presionmedia = $dato6 ? $dato6->descripcion : 'N/A';
        $fecha = $dato1 ? $dato1->created_at : 'N/A';
        $arreglo_lista[$i]->fecha = $fecha;
        $arreglo_lista[$i]->peso = $dato_peso;
        $arreglo_lista[$i]->id_enfermeria = $id_enfermeria;
    
        $partes = explode(' ', $fecha);
        $soloFecha = $partes[0];
    
        if ($dato1 && $dato1->id_turno == '1') {
            $datoturo = 'MADRUGRADA';
        } elseif ($dato1 && $dato1->id_turno == '2') {
            $datoturo = 'MAÑANA';
        } elseif ($dato1 && $dato1->id_turno == '3') {
            $datoturo = 'TARDE';
        } else {
            $datoturo = 'NOCHE';
        }
        $arreglo_lista[$i]->id_turno = $dato1->id_turno;
        $arreglo_lista[$i]->turno = $datoturo;
        $arreglo_lista[$i]->responsable = $responsable ? $responsable->name . ', ' . $responsable->paterno . ' ' . $responsable->materno : 'N/A';
        $arreglo_lista[$i]->ubica = isset($dato_cabecera_jhon[0]) ? $dato_cabecera_jhon[0]->cuarto : 'N/A';
        $arreglo_lista[$i]->id_signos_vitales = $signo->id;
        $arreglo_lista[$i]->numero = $i + 1;
        $arreglo_lista[$i]->id_signo_vital = $dato1->id_signo_vital;

        $cardiaca = $dato1 ? $dato1->descripcion : 0;
        $cardiaca1 = $dato2 ? $dato2->descripcion : 0;
        $cardiaca2 = $dato3 ? $dato3->descripcion : 0;
        $cardiaca3 = $dato4 ? $dato4->descripcion : 0;
        $cardiaca4 = $dato5 ? $dato5->descripcion : 0;
        $cardiaca5 = $dato6 ? $dato6->descripcion : 0;

        $datosPuntos[] = [
            'x' => $datoturo.' - '.$soloFecha,
            'y' => $cardiaca
        ];
        $datosPuntos2[] = [
            'x' =>  $datoturo.' - '.$soloFecha,
            'y' => $cardiaca1
        ];
        $datosPuntos3[] = [
            'x' =>  $datoturo.' - '.$soloFecha,
            'y' => $cardiaca2
        ];
        $datosPuntos4[] = [
            'x' => $datoturo.' - '.$soloFecha,
            'y' => $cardiaca3
        ];
        if ($cardiaca4 && floatval($cardiaca4) >= 30) {
            $datosPuntos5[] = [
                'x' => $datoturo.' - '.$soloFecha,
                'y' => $cardiaca4
            ];
        }
        else{
            $datosPuntos5[] = ["x" => 0, "y" => 0];
        }
        $datosPuntos6[] = [
            'x' => $datoturo.' - '.$soloFecha,
            'y' => $cardiaca5
        ];
        $datosBarras[] = [
            ['label' => $soloFecha . ' - ' . $datoturo, 'start' => $cardiaca1, 'end' => $cardiaca],
        ];
        $i++;
    }
}   

foreach($arreglo_lista as $listado){
        $diuresis = "SELECT bhd.* FROM balance_hidricos bh 
                        left join balance_hidrico_detalles bhd on bhd.id_balance_hidrico = bh.id 
                    where bh.id_enfermeria = '$listado->id_enfermeria' AND bhd.id_turno = '$listado->id_turno'
                     AND bhd.codigo_fecha_24 = '$listado->codigo_turno' AND bhd.id_caracter_fisico = '57'";
                $diuresis =  DB::select($diuresis);
                if (is_array($diuresis) && empty($diuresis)) {
                    $listado->diuresis = '---';
                } else {
                        $diuresis = $diuresis[0];
                    $listado->diuresis = $diuresis->valor_caracter_fisico;
                }
        $deposisiones = "SELECT bhd.* FROM balance_hidricos bh 
                            left join balance_hidrico_detalles bhd on bhd.id_balance_hidrico = bh.id 
                        where bh.id_enfermeria = '$listado->id_enfermeria' AND bhd.id_turno = '$listado->id_turno'
                        AND bhd.codigo_fecha_24 = '$listado->codigo_turno' AND bhd.id_caracter_fisico = '58'";
                    $deposisiones =  DB::select($deposisiones);
                    if (is_array($deposisiones) && empty($deposisiones)) {
                    $listado->deposisiones = '---';
                    } else {
                        $deposisiones = $deposisiones[0];
                    $listado->deposisiones = $deposisiones->valor_caracter_fisico;
                    }
        $drenajes  = "SELECT bhd.* FROM balance_hidricos bh 
                            left join balance_hidrico_detalles bhd on bhd.id_balance_hidrico = bh.id 
                        where bh.id_enfermeria = '$listado->id_enfermeria' AND bhd.id_turno = '$listado->id_turno'
                        AND bhd.codigo_fecha_24 = '$listado->codigo_turno' AND bhd.id_caracter_fisico = '58'";
                    $drenajes  =  DB::select($drenajes );
                    if (is_array($drenajes ) && empty($drenajes )) {
                    $listado->drenajes  = '---';
                    } else {
                        $drenajes  = $drenajes [0];
                    $listado->drenajes  = $drenajes ->valor_caracter_fisico;
                    }        
}
        return  view('frontend.hospitalizacion_enfermeria.ver_listado_signosvitales',
        compact('datos_nota_ingreso','antecedentes','dato_cabecera_jhon','enfermeria','signoVitales_nota','estado','fecha_actual',
        'datosPuntos2','datosPuntos3','datosPuntos4','datosPuntos5','datosPuntos6','datosBarras','dPuntos2','dPuntos','diasTranscurridos',
        'madrugada','mañana','tarde','noche', 'id_enfermeria','id_asegurado','arreglo_lista','datosPuntos', 'datos_atencion', 'status_token', 'dato_signo_vitales'));

    }
    
    public function guarda_signos_vitales_vista(Request $request)
    {
        $presionArteriax = $request->presionarteriax;
        $presionArteriaY = $request->presionarteriay;
        $calculo = ($presionArteriax + ($presionArteriaY * 2)) / 3;
        $calculoFormateado = number_format($calculo, 2);

        $tipo_atencion = $request->tipo_atencion;

        $signos_vitales = new signosVitales;
        $signos_vitales->id_enfermeria = $request->id_enfermeria;
        $signos_vitales->id_tipo_atencion = $tipo_atencion == null ? 2 : 3;
        $signos_vitales->fecha_atencion = date('Y-m-d');
        $signos_vitales->dias_estancia = $request->dias_estancia;
        $signos_vitales->created_us = Auth::id();
        $signos_vitales->created_at = date('Y-m-d H:i:s');
        $signos_vitales->save();
      
        $signos_vitales_detalles1 = new signosVitalesDetalles;
        $signos_vitales_detalles1->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles1->id_caracter_fisico = '1';
        $signos_vitales_detalles1->id_turno =  $request->turno;
        $signos_vitales_detalles1->descripcion = $request->presionarteriax;
        $signos_vitales_detalles1->created_us = Auth::id();
        $signos_vitales_detalles1->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles1->save();

        $signos_vitales_detalles2 = new signosVitalesDetalles;
        $signos_vitales_detalles2->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles2->id_caracter_fisico = '2';
        $signos_vitales_detalles2->id_turno =  $request->turno;
        $signos_vitales_detalles2->descripcion = $request->presionarteriay;
        $signos_vitales_detalles2->created_us = Auth::id();
        $signos_vitales_detalles2->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles2->save();

        $signos_vitales_detalles3 = new signosVitalesDetalles;
        $signos_vitales_detalles3->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles3->id_caracter_fisico = '4';
        $signos_vitales_detalles3->id_turno =  $request->turno;
        $signos_vitales_detalles3->descripcion = $request->freccardiaca;
        $signos_vitales_detalles3->created_us = Auth::id();
        $signos_vitales_detalles3->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles3->save();

        $signos_vitales_detalles4 = new signosVitalesDetalles;
        $signos_vitales_detalles4->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles4->id_caracter_fisico = '6';
        $signos_vitales_detalles4->id_turno =  $request->turno;
        $signos_vitales_detalles4->descripcion = $request->frecrespiratoria;
        $signos_vitales_detalles4->created_us = Auth::id();
        $signos_vitales_detalles4->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles4->save();

        $signos_vitales_detalles4 = new signosVitalesDetalles;
        $signos_vitales_detalles4->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles4->id_caracter_fisico = '8';
        $signos_vitales_detalles4->id_turno =  $request->turno;
        $signos_vitales_detalles4->descripcion = $request->sa02;
        $signos_vitales_detalles4->created_us = Auth::id();
        $signos_vitales_detalles4->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles4->save();

        $signos_vitales_detalles5 = new signosVitalesDetalles;
        $signos_vitales_detalles5->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles5->id_caracter_fisico = '9';
        $signos_vitales_detalles5->id_turno =  $request->turno;
        $signos_vitales_detalles5->descripcion = $request->temperatura;
        $signos_vitales_detalles5->created_us = Auth::id();
        $signos_vitales_detalles5->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles5->save();
   
        $calculo = ($request->presionarteriax + ($request->presionarteriay*2))/3;
        $signos_vitales_detalles6 = new signosVitalesDetalles;
        $signos_vitales_detalles6->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles6->id_caracter_fisico = '17';
        $signos_vitales_detalles6->id_turno =  $request->turno;
        $signos_vitales_detalles6->descripcion = $calculoFormateado;
        $signos_vitales_detalles6->created_us = Auth::id();
        $signos_vitales_detalles6->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles6->save();

        $calculo = ($request->presionarteriax + ($request->presionarteriay*2))/3;
        $signos_vitales_detalles6 = new signosVitalesDetalles;
        $signos_vitales_detalles6->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles6->id_caracter_fisico = '79';
        $signos_vitales_detalles6->id_turno =  $request->turno;
        $signos_vitales_detalles6->descripcion =  $request->opcion;
        $signos_vitales_detalles6->created_us = Auth::id();
        $signos_vitales_detalles6->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles6->save();

        $calculo = ($request->presionarteriax + ($request->presionarteriay*2))/3;
        $signos_vitales_detalles6 = new signosVitalesDetalles;
        $signos_vitales_detalles6->id_signo_vital = $signos_vitales->id;
        $signos_vitales_detalles6->id_caracter_fisico = '78';
        $signos_vitales_detalles6->id_turno =  $request->turno;
        $signos_vitales_detalles6->descripcion =  $request->detalle_oxi;
        $signos_vitales_detalles6->created_us = Auth::id();
        $signos_vitales_detalles6->created_at = date('Y-m-d H:i:s');
        $signos_vitales_detalles6->save();

        error_log('tipo atencion ==> '. $tipo_atencion);

        if ($tipo_atencion == '3') {
            return redirect('/signos_vitales_listado_hospi/' . $request->id_asegurado.'/' . $request->id_enfermeria); 
        }
        return redirect('/signos_vitales_listado/' . $request->id_asegurado.'/' . $request->id_enfermeria); 
    }

    public function showChart() {
        $arreglo_lista = $this->getArregloLista(); // Método para obtener los datos
        $numeros = [];
        $cardiacas = [];
    
        foreach ($arreglo_lista as $item) {
            $numeros[] = $item->numero;
            $cardiacas[] = $item->cardiaca;
        }
    
        $numeros_json = json_encode($numeros);
        $cardiacas_json = json_encode($cardiacas);
    
        return view('chart')->with([
            'numeros_json' => $numeros_json,
            'cardiacas_json' => $cardiacas_json
        ]);
    }


    public function ver_pdf_signos_vitales_hospi($id_asegurado, $id_enfermeria){
      
        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
   
        $id_nota_ingreso = $id_enfermeria;
        $id_tipo_atenciones = '3';
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //--- -----------
		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
		$nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id_hospi($id_nota_ingreso, $id_tipo_atenciones); 
        
        $usuario_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id',$datos_nota_ingreso[0]->created_us)
        ->first();

        $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforHospi($id_nota_ingreso);
        if (is_array($dato_cabecera_jhon) && !empty($dato_cabecera_jhon)) {
            $servicio = $dato_cabecera_jhon[0]->servicio;
            $pabellon = $dato_cabecera_jhon[0]->pabellon;
            $piso = $dato_cabecera_jhon[0]->piso;
            $cuarto = $dato_cabecera_jhon[0]->cuarto;
            $cama = $dato_cabecera_jhon[0]->cama;
        } else {
            $servicio = '';
            $pabellon = '';
            $piso = '';
            $cuarto = '';
            $cama = '';
        }

		if (is_object($datos_nota_ingreso[0])){   
         
            $id_emergencia='';
            $codigo_ubig_responsable='';
            $codigo_ubig_apoderado='';
            $datos_atencion='';
            $diagnosticos='';

                $id_emergencia = $datos_nota_ingreso[0]->id_ext_enfermeria;
                $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_emergencia);
                $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_emergencia);
                $datos_atencion = $model_receta->getHospitalizacionById_farmacia_new($id_emergencia, $codigo_ubig_responsable, $codigo_ubig_apoderado); 

                $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($id_emergencia); 

            $nota_detalle='';

            $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
            ->select('antecedentes_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
  
$valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
            ->select('valoraciones_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();

$signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
$signos_vitales = DB::select($signos_vitales);

$valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                ->select('valoraciones_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                ->select('pieldispositivo_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$enfermeria_escalas =  DB::table('enfermeria_escalas')
            ->select('enfermeria_escalas.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
          
$nombre = '';
if(isset($antecedentesenfermeria->cardio_parametro_1)){
$dato1 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                ->first();
$nombre = $nombre .' '. $dato1->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_2)){
$dato2 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                ->first();
$nombre = $nombre .' - '. $dato2->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_3)){
$dato3 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                ->first();
$nombre = $nombre .' - '. $dato3->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_4)){
$dato4 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                ->first();
$nombre = $nombre .' - '. $dato4->descrip_corta ;
}

$ingresa_por = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_ingresa_por)
->first();

$condicion_ingreso = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
->first();

$estado_conciencia = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_estado_conciencia)
->first();

$funcion_motora = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_funcion_motora)
->first();

$respiratorio = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_respiratorio)
->first();

$detalle_oxigeno  = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
->first();

$piel = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_piel)
->first();

$integridad = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_integridad)
->first();

$abdomen = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_abdomen)
->first();

$puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
$puntosenviados = DB::select($puntos);      

$emergencia_data_jhon = DB::table('emergencias')
->select('emergencias.*')
->where('id',$id_emergencia)
->first();

if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
->where('id_estado_reg','1')
->first();
}else{
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
->where('id_estado_reg','1')
->first();
}

///////////
$antecedentes =  DB::table('antecedentes_enfermerias')
->select('antecedentes_enfermerias.*')
->where('id_enfermeria',$id_enfermeria)
->first();

$estado = '1';
$arreglo_lista = [];
$i = 0;

$dato_signo_vitales = DB::select("SELECT * FROM signo_vitales WHERE id_enfermeria = ?", [$id_enfermeria]);

if (empty($dato_signo_vitales)) {
    $estado = '0';
    $datosPuntos = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos2 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos3 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos4 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos5 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos6 = [
        ["x" => 0, "y" => 0],
    ];
    $datosBarras = [
        ['label' => 'SIN DATOS', 'start' => 0, 'end' => 0],
    ];
} else {
foreach($dato_signo_vitales as $signo) {

$arreglo_lista[$i] = new \stdClass();

$dato1 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '1')
    ->first();

$dato2 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '2')
    ->first();

$dato3 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '4')
    ->first();

$dato4 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '6')
    ->first();

$dato5 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '9')
    ->first();

$dato6 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '17')
    ->first();

    $responsable = $dato1 ? DB::table('users')->select('users.*')->where('id', $dato1->created_us)->first() : null;
         
    $arreglo_lista[$i]->presionx = $dato1 ? $dato1->descripcion : 'N/A';
    $arreglo_lista[$i]->presiony = $dato2 ? $dato2->descripcion : 'N/A';
    $arreglo_lista[$i]->cardiaca = $dato3 ? $dato3->descripcion : 'N/A';
    $arreglo_lista[$i]->respiratorio = $dato4 ? $dato4->descripcion : 'N/A';
    $arreglo_lista[$i]->temperatura = $dato5 ? $dato5->descripcion : 'N/A';
    $arreglo_lista[$i]->presionmedia = $dato6 ? $dato6->descripcion : 'N/A';
    $fecha = $dato1 ? $dato1->created_at : 'N/A';
    $arreglo_lista[$i]->fecha = $fecha;

    $partes = explode(' ', $fecha);
    $soloFecha = $partes[0];

    if ($dato1 && $dato1->id_turno == '1') {
        $datoturo = 'MADRUGRADA';
    } elseif ($dato1 && $dato1->id_turno == '2') {
        $datoturo = 'MAÑANA';
    } elseif ($dato1 && $dato1->id_turno == '3') {
        $datoturo = 'TARDE';
    } else {
        $datoturo = 'NOCHE';
    }
    $arreglo_lista[$i]->turno = $datoturo;
    $arreglo_lista[$i]->responsable = $responsable ? $responsable->name . ', ' . $responsable->paterno . ' ' . $responsable->materno : 'N/A';
    $arreglo_lista[$i]->ubica = isset($dato_cabecera_jhon[0]) ? $dato_cabecera_jhon[0]->cuarto : 'N/A';
    $arreglo_lista[$i]->id_signos_vitales = $signo->id;
    $arreglo_lista[$i]->numero = $i + 1;

    $cardiaca = $dato1 ? $dato1->descripcion : 0;
    $cardiaca1 = $dato2 ? $dato2->descripcion : 0;
    $cardiaca2 = $dato3 ? $dato3->descripcion : 0;
    $cardiaca3 = $dato4 ? $dato4->descripcion : 0;
    $cardiaca4 = $dato5 ? $dato5->descripcion : 0;
    $cardiaca5 = $dato6 ? $dato6->descripcion : 0;

    $datosPuntos[] = [
        'x' => $datoturo.' - '.$soloFecha,
        'y' => $cardiaca
    ];
    $datosPuntos2[] = [
        'x' =>  $datoturo.' - '.$soloFecha,
        'y' => $cardiaca1
    ];
    $datosPuntos3[] = [
        'x' =>  $datoturo.' - '.$soloFecha,
        'y' => $cardiaca2
    ];
    $datosPuntos4[] = [
        'x' => $datoturo.' - '.$soloFecha,
        'y' => $cardiaca3
    ];
    $datosPuntos5[] = [
        'x' => $datoturo.' - '.$soloFecha,
        'y' => $cardiaca4
    ];
    $datosPuntos6[] = [
        'x' => $datoturo.' - '.$soloFecha,
        'y' => $cardiaca5
    ];
    $datosBarras[] = [
        ['label' => $soloFecha . ' - ' . $datoturo, 'start' => $cardiaca1, 'end' => $cardiaca],
    ];
    $i++;
}
}

$imprime_pdf = $responsable->name .', '. $responsable->paterno .' ' .  $responsable->materno;
            $pdf = \PDF::loadView('frontend.hospitalizacion_enfermeria.pdf_signo_vitales_enfermeria_hospitalizacion',
            compact(
                'valores_Cardio',
                'datos_nota_ingreso',  
                'datos_atencion',
                'diagnosticos' ,
                'nota_detalle' ,
                'nombre_establecimiento',
                'fechaActual','i',
                'horaActual','usuario_enfermeria','estado','imprime_pdf','datosPuntos6',
                'servicio','pabellon','piso','cuarto','cama','arreglo_lista','datosBarras','datosPuntos3','datosPuntos4','datosPuntos5',
                'datos_nota_ingreso','datos_atencion','id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                'estado_conciencia','funcion_motora','respiratorio','detalle_oxigeno','piel','integridad','abdomen','puntosenviados','asegurado_titular',
                'establecimiento','diagnosticos','diagnosticos_evaluacion'
            ));
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('nota_ingreso_emergencia.pdf'); 

        }else{
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}
    public function ver_pdf_signos_vitales($id_asegurado, $id_enfermeria){
      
        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
   
        $id_nota_ingreso = $id_enfermeria;
        $id_tipo_atenciones = '2';
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //--- -----------
		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
		$nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones); 
        
        $usuario_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id',$datos_nota_ingreso[0]->created_us)
        ->first();

        $dato_cabecera_jhon = "    SELECT
		e3.nombre as establecimiento,
        ah.nro_historia AS historia_clinica,
		e2.id::varchar as id_emergencia,
        LPAD(e2.id::VARCHAR, 10, '0')::varchar AS nro_emergencia,
        a.nro_doc_ident::TEXT AS dni,
        a.nombre AS asegurado_nombre,
        a.paterno AS asegurado_paterno,
        a.materno AS asegurado_materno,
        a.sexo::varchar AS sexo,
        EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios,
        EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses,
        EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias,
        a.fecha_nac::date AS asegurado_fecha_nacimiento,
        tb.descripcion AS asegurado_tipo_beneficiario,
        e2.talla_cm AS asegurado_talla,
        e2.peso_kg AS asegurado_peso,
        a.tipo_sangre::varchar AS asegurado_tipo_sangre,
        e2.nombre_conducido AS asegurado_acompañante,
        e2.fecha_registro_ingreso AS fecha_registro_ingreso,
        e3.nivel::varchar AS nivel_ipress,
        e3.codigo AS codigo_renipress,
        t.descripcion AS servicio,
        p3.nombre AS pabellon,
        p2.nombre AS piso,
        c2.nombre AS cuarto,
        c.nombre AS cama,
        m.name AS nombre_medico,
        m.paterno AS paterno_medico,
        m.materno AS materno_medico,
        m.dni AS dni_medico,
        e4.nombre AS especialidad_medico
    FROM
        enfermerias e
    LEFT JOIN emergencias e2 ON
        e.id_ext_enfermeria = e2.id
    LEFT JOIN prestaciones p ON
        p.id_ext = e2.id
    LEFT JOIN asegurados a ON
        p.id_asegurado = a.id 
    LEFT JOIN asegurado_historias ah ON
        ah.id_asegurado = p.id_asegurado AND ah.id_establecimiento = p.id_establecimiento
    LEFT JOIN tipo_beneficiarios tb ON
        a.id_tipo_beneficiario = tb.id
    LEFT JOIN establecimientos e3 ON
        p.id_establecimiento = e3.id
    LEFT JOIN triajes t ON
        e2.id_triaje = t.id
    LEFT JOIN pisos p2 ON
        p.id_establecimiento = p2.id_establecimiento AND p2.nombre_atencion = 'EMERGENCIA'
    LEFT JOIN pabellones p3 ON
        p2.id_pabellon = p3.id
    INNER JOIN camas c ON
        e2.cama_estado = c.id
    INNER JOIN cuartos c2 ON
        c.id_cuarto = c2.id
    LEFT JOIN users m ON
        e2.id_medico_triaje = m.id
    left join medicos m2 on
    	m2.dni = m.dni
    LEFT JOIN especializaciones e4 ON
        m2.id_especializacion = e4.id
    WHERE
        e.id ='$id_nota_ingreso' ";
        
        $dato_cabecera_jhon = DB::select($dato_cabecera_jhon);

        if (is_array($dato_cabecera_jhon) && !empty($dato_cabecera_jhon)) {
            $servicio = $dato_cabecera_jhon[0]->servicio;
            $pabellon = $dato_cabecera_jhon[0]->pabellon;
            $piso = $dato_cabecera_jhon[0]->piso;
            $cuarto = $dato_cabecera_jhon[0]->cuarto;
            $cama = $dato_cabecera_jhon[0]->cama;
        } else {
            $servicio = '';
            $pabellon = '';
            $piso = '';
            $cuarto = '';
            $cama = '';
        }

		if (is_object($datos_nota_ingreso[0])){   
         
            $id_emergencia='';
            $codigo_ubig_responsable='';
            $codigo_ubig_apoderado='';
            $datos_atencion='';
            $diagnosticos='';

                $id_emergencia = $datos_nota_ingreso[0]->id_ext_enfermeria;
                $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_emergencia);
                $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_emergencia);
                $datos_atencion = $model_receta->getEmergenciaById_farmacia_new($id_emergencia, $codigo_ubig_responsable, $codigo_ubig_apoderado); 

                $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($id_emergencia); 

            $nota_detalle='';

            $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
            ->select('antecedentes_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
  
$valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
            ->select('valoraciones_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();

$signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
$signos_vitales = DB::select($signos_vitales);

$valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                ->select('valoraciones_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                ->select('pieldispositivo_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$enfermeria_escalas =  DB::table('enfermeria_escalas')
            ->select('enfermeria_escalas.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
          
$nombre = '';
if(isset($antecedentesenfermeria->cardio_parametro_1)){
$dato1 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                ->first();
$nombre = $nombre .' '. $dato1->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_2)){
$dato2 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                ->first();
$nombre = $nombre .' - '. $dato2->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_3)){
$dato3 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                ->first();
$nombre = $nombre .' - '. $dato3->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_4)){
$dato4 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                ->first();
$nombre = $nombre .' - '. $dato4->descrip_corta ;
}

$ingresa_por = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_ingresa_por)
->first();

$condicion_ingreso = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
->first();

$estado_conciencia = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_estado_conciencia)
->first();

$funcion_motora = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_funcion_motora)
->first();

$respiratorio = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_respiratorio)
->first();

$detalle_oxigeno  = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
->first();

$piel = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_piel)
->first();

$integridad = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_integridad)
->first();

$abdomen = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_abdomen)
->first();

$puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
$puntosenviados = DB::select($puntos);      

$emergencia_data_jhon = DB::table('emergencias')
->select('emergencias.*')
->where('id',$id_emergencia)
->first();

if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
->where('id_estado_reg','1')
->first();
}else{
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
->where('id_estado_reg','1')
->first();
}

///////////
$antecedentes =  DB::table('antecedentes_enfermerias')
->select('antecedentes_enfermerias.*')
->where('id_enfermeria',$id_enfermeria)
->first();

$estado = '1';
$arreglo_lista = [];
$i = 0;

$dato_signo_vitales = DB::select("SELECT * FROM signo_vitales WHERE id_enfermeria = ?", [$id_enfermeria]);

if (empty($dato_signo_vitales)) {
    $estado = '0';
    $datosPuntos = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos2 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos3 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos4 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos5 = [
        ["x" => 0, "y" => 0],
    ];
    $datosPuntos6 = [
        ["x" => 0, "y" => 0],
    ];
    $datosBarras = [
        ['label' => 'SIN DATOS', 'start' => 0, 'end' => 0],
    ];
} else {
foreach($dato_signo_vitales as $signo) {

$arreglo_lista[$i] = new \stdClass();

$dato1 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '1')
    ->first();

$dato2 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '2')
    ->first();

$dato3 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '4')
    ->first();

$dato4 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '6')
    ->first();

$dato5 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '9')
    ->first();

$dato6 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '17')
    ->first();

    $responsable = $dato1 ? DB::table('users')->select('users.*')->where('id', $dato1->created_us)->first() : null;
         
    $arreglo_lista[$i]->presionx = $dato1 ? $dato1->descripcion : 'N/A';
    $arreglo_lista[$i]->presiony = $dato2 ? $dato2->descripcion : 'N/A';
    $arreglo_lista[$i]->cardiaca = $dato3 ? $dato3->descripcion : 'N/A';
    $arreglo_lista[$i]->respiratorio = $dato4 ? $dato4->descripcion : 'N/A';
    $arreglo_lista[$i]->temperatura = $dato5 ? $dato5->descripcion : 'N/A';
    $arreglo_lista[$i]->presionmedia = $dato6 ? $dato6->descripcion : 'N/A';
    $fecha = $dato1 ? $dato1->created_at : 'N/A';
    $arreglo_lista[$i]->fecha = $fecha;

    $partes = explode(' ', $fecha);
    $soloFecha = $partes[0];

    if ($dato1 && $dato1->id_turno == '1') {
        $datoturo = 'MADRUGRADA';
    } elseif ($dato1 && $dato1->id_turno == '2') {
        $datoturo = 'MAÑANA';
    } elseif ($dato1 && $dato1->id_turno == '3') {
        $datoturo = 'TARDE';
    } else {
        $datoturo = 'NOCHE';
    }
    $arreglo_lista[$i]->turno = $datoturo;
    $arreglo_lista[$i]->responsable = $responsable ? $responsable->name . ', ' . $responsable->paterno . ' ' . $responsable->materno : 'N/A';
    $arreglo_lista[$i]->ubica = isset($dato_cabecera_jhon[0]) ? $dato_cabecera_jhon[0]->cuarto : 'N/A';
    $arreglo_lista[$i]->id_signos_vitales = $signo->id;
    $arreglo_lista[$i]->numero = $i + 1;

    $cardiaca = $dato1 ? $dato1->descripcion : 0;
    $cardiaca1 = $dato2 ? $dato2->descripcion : 0;
    $cardiaca2 = $dato3 ? $dato3->descripcion : 0;
    $cardiaca3 = $dato4 ? $dato4->descripcion : 0;
    $cardiaca4 = $dato5 ? $dato5->descripcion : 0;
    $cardiaca5 = $dato6 ? $dato6->descripcion : 0;

    $datosPuntos[] = [
        'x' => $datoturo.' - '.$soloFecha,
        'y' => $cardiaca
    ];
    $datosPuntos2[] = [
        'x' =>  $datoturo.' - '.$soloFecha,
        'y' => $cardiaca1
    ];
    $datosPuntos3[] = [
        'x' =>  $datoturo.' - '.$soloFecha,
        'y' => $cardiaca2
    ];
    $datosPuntos4[] = [
        'x' => $datoturo.' - '.$soloFecha,
        'y' => $cardiaca3
    ];
    $datosPuntos5[] = [
        'x' => $datoturo.' - '.$soloFecha,
        'y' => $cardiaca4
    ];
    $datosPuntos6[] = [
        'x' => $datoturo.' - '.$soloFecha,
        'y' => $cardiaca5
    ];
    $datosBarras[] = [
        ['label' => $soloFecha . ' - ' . $datoturo, 'start' => $cardiaca1, 'end' => $cardiaca],
    ];
    $i++;
}
}
$imprime_pdf = $responsable->name .', '. $responsable->paterno .' ' .  $responsable->materno;
            $pdf = \PDF::loadView('frontend.emergencia_enfermeria.pdf_signo_vitales_enfermeria_emergencia',
            compact(
                'valores_Cardio',
                'datos_nota_ingreso',  
                'datos_atencion',
                'diagnosticos' ,
                'nota_detalle' ,
                'nombre_establecimiento',
                'fechaActual','i',
                'horaActual','usuario_enfermeria','estado','imprime_pdf','datosPuntos6',
                'servicio','pabellon','piso','cuarto','cama','arreglo_lista','datosBarras','datosPuntos3','datosPuntos4','datosPuntos5',
                'datos_nota_ingreso','datos_atencion','id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                'estado_conciencia','funcion_motora','respiratorio','detalle_oxigeno','piel','integridad','abdomen','puntosenviados','asegurado_titular',
                'establecimiento','diagnosticos','diagnosticos_evaluacion'
            

            ));
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('nota_ingreso_emergencia.pdf'); 

        }else{
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}

    public function editar_signo_vitales($id_signo_vital, $id_tipo_atencion = 2){

        $model_enfermeria = new Nota_enfermeria();
        $id_signo_vital = $id_signo_vital;
        $datos_signos_vitales =  DB::table('signo_vitales')
        ->select('signo_vitales.*')
        ->where('id', $id_signo_vital)
        ->first();
        $datos_enfermeria =  DB::table('enfermerias')
        ->select('enfermerias.*')
        ->where('id', $datos_signos_vitales->id_enfermeria)
        ->first();
        $responsable_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_enfermeria->created_us)
        ->first();
        $responsable_signos_vitales = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_signos_vitales->created_us)
        ->first();
        $id_editor = Auth::user()->id;
        $responsable_editor = DB::table('users')
        ->select('users.*')
        ->where('id', $id_editor)
        ->first();

        $id_tipo_atenciones = $id_tipo_atencion;
        $nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($datos_enfermeria->id, $id_tipo_atenciones); 

        $dato_cabecera_jhon = null;

        $dato_cabecera_jhon = "    SELECT
		e3.nombre as establecimiento,
        ah.nro_historia AS historia_clinica,
		e2.id::varchar as id_emergencia,
        LPAD(e2.id::VARCHAR, 10, '0')::varchar AS nro_emergencia,
        a.nro_doc_ident::TEXT AS dni,
        a.nombre AS asegurado_nombre,
        a.paterno AS asegurado_paterno,
        a.materno AS asegurado_materno,
        a.sexo::varchar AS sexo,
        EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios,
        EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses,
        EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias,
        a.fecha_nac::date AS asegurado_fecha_nacimiento,
        a.grado AS asegurado_grado,
        a.cip AS asegurado_carnet,        
        tb.descripcion AS asegurado_tipo_beneficiario,
        e2.talla_cm AS asegurado_talla,
        e2.peso_kg AS asegurado_peso,
        e2.temperatura AS asegurado_temperatura,
        e2.presion_arterial_x AS asegurado_presion_arterial_x,
        e2.presion_arterial_y AS asegurado_presion_arterial_y,
        e2.sa02_porcentaje AS asegurado_sa02_porcentaje,
        e2.frecuencia_respiratoria AS asegurado_frecuencia_respiratoria,
        e2.frecuencia_cardiaca AS asegurado_frecuencia_cardiaca,
        e2.fi02 AS asegurado_fi02,
        a.tipo_sangre::varchar AS asegurado_tipo_sangre,
        e2.nombre_conducido AS asegurado_acompañante,
        e2.fecha_registro_ingreso AS fecha_registro_ingreso,
        e3.nivel::varchar AS nivel_ipress,
        e3.codigo AS codigo_renipress,
        t.descripcion AS servicio,
        p3.nombre AS pabellon,
        p2.nombre AS piso,
        c2.nombre AS cuarto,
        c.nombre AS cama,
        m.name AS nombre_medico,
        m.paterno AS paterno_medico,
        m.materno AS materno_medico,
        m.dni AS dni_medico,
        e4.nombre AS especialidad_medico

    FROM
        enfermerias e
    LEFT JOIN emergencias e2 ON
        e.id_ext_enfermeria = e2.id
    LEFT JOIN prestaciones p ON
        p.id_ext = e2.id
    LEFT JOIN asegurados a ON
        e2.id_asegurado = a.id 
    LEFT JOIN asegurado_historias ah ON
        ah.id_asegurado = e2.id_asegurado AND ah.id_establecimiento = e2.id_establecimiento
    LEFT JOIN tipo_beneficiarios tb ON
        a.id_tipo_beneficiario = tb.id
    LEFT JOIN establecimientos e3 ON
        e2.id_establecimiento = e3.id
    LEFT JOIN triajes t ON
        e2.id_triaje = t.id
    LEFT JOIN pisos p2 ON
        p.id_establecimiento = p2.id_establecimiento AND p2.nombre_atencion = 'EMERGENCIA'
    LEFT JOIN pabellones p3 ON
        p2.id_pabellon = p3.id
    INNER JOIN camas c ON
        e2.cama_estado = c.id
    INNER JOIN cuartos c2 ON
        c.id_cuarto = c2.id
    LEFT JOIN users m ON
        e2.id_medico_triaje = m.id
    left join medicos m2 on
    	m2.dni = m.dni
    LEFT JOIN especializaciones e4 ON
        m2.id_especializacion = e4.id
    WHERE
        e.id ='$datos_enfermeria->id' ";

        $existeAtencionMedica = Prestaciones::where('id_ext', '=', $datos_nota_ingreso[0]->id_ext_enfermeria)
        ->where('id_tipo_atencion', $id_tipo_atenciones)
        ->exists();

        if ($id_tipo_atenciones == '2') {
            if ($existeAtencionMedica) {
                $dato_cabecera_jhon = DB::select($dato_cabecera_jhon);
            } else {
                $dato_cabecera_jhon = $model_enfermeria->getCabecera_id_withSPforEmergencia_sa($datos_signos_vitales->id_enfermeria);
            }
        } else if ($id_tipo_atenciones == '3') {
            $dato_cabecera_jhon = $model_enfermeria->getCabecera_id_withSPforHospi($datos_signos_vitales->id_enfermeria);
        }

        $fecha_ingreso = $dato_cabecera_jhon[0]->fecha_registro_ingreso;
        $fechaIngreso = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_ingreso);
        $fechaActual = Carbon::now();
        $diasTranscurridos = $fechaIngreso->diffInDays($fechaActual);
        
        if($diasTranscurridos == 0){
            $diasTranscurridos = $diasTranscurridos + 1;
        }

        $antecedentes =  DB::table('antecedentes_enfermerias')
        ->select('antecedentes_enfermerias.*')
        ->where('id_enfermeria',$datos_enfermeria->id)
        ->first();

        $detalle_signo_enfermeria = "SELECT * FROM signo_vitales_detalles where id_signo_vital = '$datos_signos_vitales->id' order by id_caracter_fisico asc ";
        $detalle_signo_enfermeria = DB::select($detalle_signo_enfermeria);
        if($detalle_signo_enfermeria[0]->id_turno == 1){
            $dato_turno = 'MADRUGADA';
        }elseif($detalle_signo_enfermeria[0]->id_turno == 2){
            $dato_turno = 'MAÑANA';
        }elseif($detalle_signo_enfermeria[0]->id_turno == 3){
            $dato_turno = 'TARDE';
        }else{
            $dato_turno = 'NOCHE';
        }
        $numero_filas = count($detalle_signo_enfermeria);
        $prsionx = $detalle_signo_enfermeria[0]->descripcion; 
        $prsiony = $detalle_signo_enfermeria[1]->descripcion; 
        $cardiaca = $detalle_signo_enfermeria[2]->descripcion; 
        $respiratorio = $detalle_signo_enfermeria[3]->descripcion; 
        $sa02 = $detalle_signo_enfermeria[4]->descripcion; 
        $temperatura = $detalle_signo_enfermeria[5]->descripcion; 
        $pam = $detalle_signo_enfermeria[6]->descripcion; 
        if($numero_filas > 7){
            $flag_oxi = $detalle_signo_enfermeria[8]->descripcion; 
            $detalle_oxi = $detalle_signo_enfermeria[7]->descripcion;
        }else{
            $flag_oxi = 0; 
            $detalle_oxi = ' ';
        }
        $fecha_creacion = $detalle_signo_enfermeria[0]->created_at;

        $fechaedicion = Carbon::now();
        return  view('frontend.emergencia_enfermeria.edicion_signos_vitales',
        compact('datos_signos_vitales','datos_enfermeria','responsable_enfermeria','responsable_signos_vitales','fecha_creacion',
        'responsable_editor','diasTranscurridos','dato_cabecera_jhon','datos_nota_ingreso','antecedentes','id_signo_vital',
        'dato_turno','prsionx','prsiony','cardiaca','respiratorio','sa02','temperatura','pam','flag_oxi','detalle_oxi','fechaedicion', 'id_tipo_atenciones', 'existeAtencionMedica'));
    }

    public function guardar_edicion_signos_vitales(Request $request){

        $model_enfermeria = new Nota_enfermeria();
        $id_signo_vital = $request->id_signo_vital;
        $datos_signos_vitales =  DB::table('signo_vitales')
        ->select('signo_vitales.*')
        ->where('id', $id_signo_vital)
        ->first();

        $datos_enfermeria =  DB::table('enfermerias')
        ->select('enfermerias.*')
        ->where('id', $datos_signos_vitales->id_enfermeria)
        ->first();
        $responsable_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_enfermeria->created_us)
        ->first();
        $responsable_signos_vitales = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_signos_vitales->created_us)
        ->first();
        $id_editor = Auth::user()->id;
        $responsable_editor = DB::table('users')
        ->select('users.*')
        ->where('id', $id_editor)
        ->first();

        $id_tipo_atenciones = $request->tipo_atencion;
        $nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($datos_enfermeria->id, $id_tipo_atenciones); 

        if ($id_tipo_atenciones == 2) {

            $dato_cabecera_jhon = "    SELECT
		e3.nombre as establecimiento,
        ah.nro_historia AS historia_clinica,
		e2.id::varchar as id_emergencia,
        LPAD(e2.id::VARCHAR, 10, '0')::varchar AS nro_emergencia,
        a.nro_doc_ident::TEXT AS dni,
        a.nombre AS asegurado_nombre,
        a.paterno AS asegurado_paterno,
        a.materno AS asegurado_materno,
        a.sexo::varchar AS sexo,
        EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios,
        EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses,
        EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias,
        a.fecha_nac::date AS asegurado_fecha_nacimiento,
        a.grado AS asegurado_grado,
        a.cip AS asegurado_carnet,        
        tb.descripcion AS asegurado_tipo_beneficiario,
        e2.talla_cm AS asegurado_talla,
        e2.peso_kg AS asegurado_peso,
        e2.temperatura AS asegurado_temperatura,
        e2.presion_arterial_x AS asegurado_presion_arterial_x,
        e2.presion_arterial_y AS asegurado_presion_arterial_y,
        e2.sa02_porcentaje AS asegurado_sa02_porcentaje,
        e2.frecuencia_respiratoria AS asegurado_frecuencia_respiratoria,
        e2.frecuencia_cardiaca AS asegurado_frecuencia_cardiaca,
        e2.fi02 AS asegurado_fi02,
        a.tipo_sangre::varchar AS asegurado_tipo_sangre,
        e2.nombre_conducido AS asegurado_acompañante,
        e2.fecha_registro_ingreso AS fecha_registro_ingreso,
        e3.nivel::varchar AS nivel_ipress,
        e3.codigo AS codigo_renipress,
        t.descripcion AS servicio,
        /* p3.nombre AS pabellon, */
        /* p2.nombre AS piso,
        c2.nombre AS cuarto,
        c.nombre AS cama, */
        m.name AS nombre_medico,
        m.paterno AS paterno_medico,
        m.materno AS materno_medico,
        m.dni AS dni_medico,
        e4.nombre AS especialidad_medico

    FROM
        enfermerias e
    LEFT JOIN emergencias e2 ON
        e.id_ext_enfermeria = e2.id
    /* LEFT JOIN prestaciones p ON
        p.id_ext = e2.id */
    LEFT JOIN asegurados a ON
        e2.id_asegurado = a.id 
    LEFT JOIN asegurado_historias ah ON
        ah.id_asegurado = e2.id_asegurado AND ah.id_establecimiento = e2.id_establecimiento
    LEFT JOIN tipo_beneficiarios tb ON
        a.id_tipo_beneficiario = tb.id
    LEFT JOIN establecimientos e3 ON
        e2.id_establecimiento = e3.id
    LEFT JOIN triajes t ON
        e2.id_triaje = t.id
    /* LEFT JOIN pisos p2 ON
        p.id_establecimiento = p2.id_establecimiento AND p2.nombre_atencion = 'EMERGENCIA'
    LEFT JOIN pabellones p3 ON
        p2.id_pabellon = p3.id
    INNER JOIN camas c ON
        e2.cama_estado = c.id
    INNER JOIN cuartos c2 ON
        c.id_cuarto = c2.id */
    LEFT JOIN users m ON
        e2.id_medico_triaje = m.id
    left join medicos m2 on
    	m2.dni = m.dni
    LEFT JOIN especializaciones e4 ON
        m2.id_especializacion = e4.id
    WHERE
    e.id ='$datos_signos_vitales->id_enfermeria' ";
            $dato_cabecera_jhon = DB::select($dato_cabecera_jhon);
        } else if ($id_tipo_atenciones == 3) {
            $dato_cabecera_jhon = $model_enfermeria->getCabecera_id_withSPforHospi($datos_signos_vitales->id_enfermeria);
        }
        $fecha_ingreso = $dato_cabecera_jhon[0]->fecha_registro_ingreso;
        $fechaIngreso = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_ingreso);
        $fechaActual = Carbon::now();
        $diasTranscurridos = $fechaIngreso->diffInDays($fechaActual);
        
        if($diasTranscurridos == 0){
            $diasTranscurridos = $diasTranscurridos + 1;
        }

        $antecedentes =  DB::table('antecedentes_enfermerias')
        ->select('antecedentes_enfermerias.*')
        ->where('id_enfermeria',$datos_enfermeria->id)
        ->first();

        $detalle_signo_enfermeria = "SELECT * FROM signo_vitales_detalles where id_signo_vital = '$datos_signos_vitales->id' order by id_caracter_fisico asc";
        $detalle_signo_enfermeria = DB::select($detalle_signo_enfermeria);
        if($detalle_signo_enfermeria[0]->id_turno == 1){
            $dato_turno = 'MADRUGADA';
        }elseif($detalle_signo_enfermeria[0]->id_turno == 2){
            $dato_turno = 'MAÑANA';
        }elseif($detalle_signo_enfermeria[0]->id_turno == 3){
            $dato_turno = 'TARDE';
        }else{
            $dato_turno = 'NOCHE';
        }

        $numero_filas = count($detalle_signo_enfermeria);
        $prsionx = $detalle_signo_enfermeria[0]->descripcion; 
        $prsiony = $detalle_signo_enfermeria[1]->descripcion; 
        $cardiaca = $detalle_signo_enfermeria[2]->descripcion; 
        $respiratorio = $detalle_signo_enfermeria[3]->descripcion; 
        $sa02 = $detalle_signo_enfermeria[4]->descripcion; 
        $temperatura = $detalle_signo_enfermeria[5]->descripcion; 
        $pam = $detalle_signo_enfermeria[6]->descripcion; 
        if($numero_filas > 7){
            $flag_oxi = $detalle_signo_enfermeria[8]->descripcion; 
            $detalle_oxi = $detalle_signo_enfermeria[7]->descripcion;
        }else{
            $flag_oxi = 0; 
            $detalle_oxi = ' ';
        }
        $fecha_creacion = $detalle_signo_enfermeria[0]->created_at;

        $fechaedicion = Carbon::now();

        $datos_signos_vitales =  DB::table('signo_vitales')
        ->select('signo_vitales.*')
        ->where('id', $id_signo_vital)
        ->first();
      
        $id_editor = Auth::user()->id;
        $edito =DB::table('users')
        ->select('users.*')
        ->where('id',$id_editor)
        ->first();

        $fechaedicion = Carbon::now();
        $detalle_signo_enfermeria = "SELECT * FROM signo_vitales_detalles where id_signo_vital = '$datos_signos_vitales->id' order by id_caracter_fisico asc";
        $detalle_signo_enfermeria = DB::select($detalle_signo_enfermeria);
        $numero_filas = count($detalle_signo_enfermeria);
        $prsionx = $detalle_signo_enfermeria[0]->descripcion ? $detalle_signo_enfermeria[0]->descripcion : null; 
        $prsiony = $detalle_signo_enfermeria[1]->descripcion ? $detalle_signo_enfermeria[1]->descripcion : null; 
        $cardiaca = $detalle_signo_enfermeria[2]->descripcion ? $detalle_signo_enfermeria[2]->descripcion : null; 
        $respiratorio = $detalle_signo_enfermeria[3]->descripcion ? $detalle_signo_enfermeria[3]->descripcion : null; 
        $sa02 = $detalle_signo_enfermeria[4]->descripcion ? $detalle_signo_enfermeria[4]->descripcion : null; 
        $temperatura = $detalle_signo_enfermeria[5]->descripcion ? $detalle_signo_enfermeria[5]->descripcion : null;  
        $pam = $detalle_signo_enfermeria[6]->descripcion ? $detalle_signo_enfermeria[6]->descripcion : null; 
        if($numero_filas > 7){
            $flag_oxi = $detalle_signo_enfermeria[8]->descripcion ? $detalle_signo_enfermeria[8]->descripcion : null; 
            $detalle_oxi = $detalle_signo_enfermeria[7]->descripcion ? $detalle_signo_enfermeria[7]->descripcion : null;
        }else{
            $flag_oxi = 0; 
            $detalle_oxi = ' ';
        }
        $presionArteriax = $request->presionarteriax;
        $presionArteriaY = $request->presionarteriay;
        $calculo = ($presionArteriax + ($presionArteriaY * 2)) / 3;
        $calculoFormateado = number_format($calculo, 2);
        $Signovitaleshistorico = new Signo_vitales_historico;
        $Signovitaleshistorico->id_signo_vital = $id_signo_vital;
        $Signovitaleshistorico->presion_aretiral_x_antes = $prsionx;
        $Signovitaleshistorico->presion_aretiral_x_nuevo = $request->presionarteriax ?: 0;
        $Signovitaleshistorico->presion_aretiral_y_antes = $prsiony;
        $Signovitaleshistorico->presion_aretiral_y_nuevo = $request->presionarteriay ?: 0;
        $Signovitaleshistorico->cardiaca_antes = $cardiaca;
        $Signovitaleshistorico->cardiaca_nuevo = $request->freccardiaca ?: 0;
        $Signovitaleshistorico->respiratorio_antes = $respiratorio;
        $Signovitaleshistorico->respiratorio_nuevo = $request->frecrespiratoria ?: 0;
        $Signovitaleshistorico->sa02_antes = $sa02;
        $Signovitaleshistorico->sa02_nuevo = $request->sa02 ?: 0;
        $Signovitaleshistorico->temperatura_antes = $temperatura;
        $Signovitaleshistorico->temperatura_nuevo = $request->temperatura ?: 0;
        $Signovitaleshistorico->pam_antes = $pam; 
        $Signovitaleshistorico->pam_nuevo = $calculoFormateado;
        $Signovitaleshistorico->flag_oxi_antes = $flag_oxi;
        $Signovitaleshistorico->flag_oxi_nuevo = $request->opcion;
        $Signovitaleshistorico->detalle_oxi_antes = $detalle_oxi;
        $Signovitaleshistorico->detalle_oxi_nuevo =$request->detalle_oxi;
        $Signovitaleshistorico->created_us = $id_editor;
        $Signovitaleshistorico->created_at = $fechaedicion;
        $Signovitaleshistorico->usuario_creacion_antes = $detalle_signo_enfermeria[0]->created_us;
        $Signovitaleshistorico->fecha_creacion_antes = $detalle_signo_enfermeria[0]->created_at;
        $Signovitaleshistorico->save();

        $data_historica =  DB::table('signo_vitales_historico')
        ->select('signo_vitales_historico.*')
        ->where('id', $Signovitaleshistorico->id)
        ->first();

        if(!empty($request->presionarteriax)){
            $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[0]->id);
            $model_detalles->descripcion = $request->presionarteriax;
            $model_detalles->updated_us = $id_editor;
            $model_detalles->updated_at = $fechaedicion;
            $model_detalles->save();
        }
        if(!empty($request->presionarteriay)){
            $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[1]->id);
            $model_detalles->descripcion = $request->presionarteriay;
            $model_detalles->updated_us = $id_editor;
            $model_detalles->updated_at = $fechaedicion;
            $model_detalles->save();
        }
        if(!empty($request->freccardiaca)){
            $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[2]->id);
            $model_detalles->descripcion = $request->freccardiaca;
            $model_detalles->updated_us = $id_editor;
            $model_detalles->updated_at = $fechaedicion;
            $model_detalles->save();
        }
        if(!empty($request->frecrespiratoria)){
            $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[3]->id);
            $model_detalles->descripcion = $request->frecrespiratoria;
            $model_detalles->updated_us = $id_editor;
            $model_detalles->updated_at = $fechaedicion;
            $model_detalles->save();
        }
        if(!empty($request->sa02)){
            $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[4]->id);
            $model_detalles->descripcion = $request->sa02;
            $model_detalles->updated_us = $id_editor;
            $model_detalles->updated_at = $fechaedicion;
            $model_detalles->save();
        }
        if(!empty($request->temperatura)){
            $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[5]->id);
            $model_detalles->descripcion = $request->temperatura;
            $model_detalles->updated_us = $id_editor;
            $model_detalles->updated_at = $fechaedicion;
            $model_detalles->save();
        }
        if(!empty($request->presionarteriax)){
            $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[6]->id);
            $model_detalles->descripcion = $calculoFormateado;
            $model_detalles->updated_us = $id_editor;
            $model_detalles->updated_at = $fechaedicion;
            $model_detalles->save();
        }
        if($numero_filas > 7){
            if(!empty($request->flag_oxi)){
                $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[8]->id);
                $model_detalles->descripcion = $request->flag_oxi;
                $model_detalles->updated_us = $id_editor;
                $model_detalles->updated_at = $fechaedicion;
                $model_detalles->save();
            }
            if(!empty($request->detalle_oxi)){
                $model_detalles = \App\Models\signosVitalesDetalles::find($detalle_signo_enfermeria[7]->id);
                $model_detalles->descripcion = $request->detalle_oxi;
                $model_detalles->updated_us = $id_editor;
                $model_detalles->updated_at = $fechaedicion;
                $model_detalles->save();
            }
        }
        /* return  view('frontend.emergencia_enfermeria.ver_edicion_signos_vitales',
        compact('datos_signos_vitales','datos_enfermeria','responsable_enfermeria','responsable_signos_vitales','fecha_creacion',
        'responsable_editor','diasTranscurridos','dato_cabecera_jhon','datos_nota_ingreso','antecedentes','id_signo_vital','data_historica',
        'dato_turno','prsionx','prsiony','cardiaca','respiratorio','sa02','temperatura','pam','flag_oxi','detalle_oxi','fechaedicion','edito', 'id_tipo_atenciones')); */
        if ($id_tipo_atenciones == 3) {
            return redirect('/ver_edicion_signo_vitales/'. $id_signo_vital .'/3');
        } 
        return redirect('/ver_edicion_signo_vitales/'. $id_signo_vital);
    }

    public function ver_edicion_signo_vitales($id_signo_vital, $id_tipo_atencion = 2){
    
        $model_enfermeria = new Nota_enfermeria();
        $datos_signos_vitales =  DB::table('signo_vitales')
        ->select('signo_vitales.*')
        ->where('id', $id_signo_vital)
        ->first();

        $datos_enfermeria =  DB::table('enfermerias')
        ->select('enfermerias.*')
        ->where('id', $datos_signos_vitales->id_enfermeria)
        ->first();
        $responsable_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_enfermeria->created_us)
        ->first();
        $responsable_signos_vitales = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_signos_vitales->created_us)
        ->first();
        $id_editor = Auth::user()->id;
        $responsable_editor = DB::table('users')
        ->select('users.*')
        ->where('id', $id_editor)
        ->first();

        $data_historica =  DB::table('signo_vitales_historico')
        ->select('signo_vitales_historico.*')
        ->where('id_signo_vital', $id_signo_vital)
        ->first();
        $id_tipo_atenciones = $id_tipo_atencion;
        $nota_ingreso = new Nota_enfermeria; 
        if ($id_tipo_atencion == 2) {
            $datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($datos_enfermeria->id, $id_tipo_atenciones); 
            $existeAtencionMedica = Prestaciones::where('id_ext', '=', $datos_nota_ingreso[0]->id_ext_enfermeria)
                                ->where('id_tipo_atencion', $id_tipo_atenciones)
                                ->exists();
            $dato_cabecera_jhon = "    SELECT
		e3.nombre as establecimiento,
        ah.nro_historia AS historia_clinica,
		e2.id::varchar as id_emergencia,
        LPAD(e2.id::VARCHAR, 10, '0')::varchar AS nro_emergencia,
        a.nro_doc_ident::TEXT AS dni,
        a.nombre AS asegurado_nombre,
        a.paterno AS asegurado_paterno,
        a.materno AS asegurado_materno,
        a.sexo::varchar AS sexo,
        EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios,
        EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses,
        EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias,
        a.fecha_nac::date AS asegurado_fecha_nacimiento,
        a.grado AS asegurado_grado,
        a.cip AS asegurado_carnet,        
        tb.descripcion AS asegurado_tipo_beneficiario,
        e2.talla_cm AS asegurado_talla,
        e2.peso_kg AS asegurado_peso,
        e2.temperatura AS asegurado_temperatura,
        e2.presion_arterial_x AS asegurado_presion_arterial_x,
        e2.presion_arterial_y AS asegurado_presion_arterial_y,
        e2.sa02_porcentaje AS asegurado_sa02_porcentaje,
        e2.frecuencia_respiratoria AS asegurado_frecuencia_respiratoria,
        e2.frecuencia_cardiaca AS asegurado_frecuencia_cardiaca,
        e2.fi02 AS asegurado_fi02,
        a.tipo_sangre::varchar AS asegurado_tipo_sangre,
        e2.nombre_conducido AS asegurado_acompañante,
        e2.fecha_registro_ingreso AS fecha_registro_ingreso,
        e3.nivel::varchar AS nivel_ipress,
        e3.codigo AS codigo_renipress,
        t.descripcion AS servicio,
        p3.nombre AS pabellon,
        p2.nombre AS piso,
        c2.nombre AS cuarto,
        c.nombre AS cama,
        m.name AS nombre_medico,
        m.paterno AS paterno_medico,
        m.materno AS materno_medico,
        m.dni AS dni_medico,
        e4.nombre AS especialidad_medico,
        e.id as id_enfermeria
    FROM
        enfermerias e
    LEFT JOIN emergencias e2 ON
        e.id_ext_enfermeria = e2.id
    LEFT JOIN prestaciones p ON
        p.id_ext = e2.id
    LEFT JOIN asegurados a ON
        e2.id_asegurado = a.id 
    LEFT JOIN asegurado_historias ah ON
        ah.id_asegurado = e2.id_asegurado AND ah.id_establecimiento = e2.id_establecimiento
    LEFT JOIN tipo_beneficiarios tb ON
        a.id_tipo_beneficiario = tb.id
    LEFT JOIN establecimientos e3 ON
        e2.id_establecimiento = e3.id
    LEFT JOIN triajes t ON
        e2.id_triaje = t.id
    LEFT JOIN pisos p2 ON
        p.id_establecimiento = p2.id_establecimiento AND p2.nombre_atencion = 'EMERGENCIA'
    LEFT JOIN pabellones p3 ON
        p2.id_pabellon = p3.id
    INNER JOIN camas c ON
        e2.cama_estado = c.id
    INNER JOIN cuartos c2 ON
        c.id_cuarto = c2.id
    LEFT JOIN users m ON
        e2.id_medico_triaje = m.id
    left join medicos m2 on
    	m2.dni = m.dni
    LEFT JOIN especializaciones e4 ON
        m2.id_especializacion = e4.id
    WHERE
            e.id ='$datos_signos_vitales->id_enfermeria' ";
            if ($existeAtencionMedica) {
                $dato_cabecera_jhon = DB::select($dato_cabecera_jhon);
            } else {
                $dato_cabecera_jhon = $model_enfermeria->getCabecera_id_withSPforEmergencia_sa($datos_signos_vitales->id_enfermeria);
            }
            $fecha_ingreso = $dato_cabecera_jhon[0]->fecha_registro_ingreso;
            $fechaIngreso = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_ingreso);
            $fechaActual = Carbon::now();
            $diasTranscurridos = $fechaIngreso->diffInDays($fechaActual);
            if($diasTranscurridos == 0){
                $diasTranscurridos = $diasTranscurridos + 1;
            }
        } else {
            $datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id_hospi($datos_enfermeria->id, $id_tipo_atenciones); 
            $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforHospi($datos_enfermeria->id);
            $fecha_ingreso = $dato_cabecera_jhon[0]->fecha_registro_ingreso;
            $fechaIngreso = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_ingreso);
            $fechaActual = Carbon::now();
            $diasTranscurridos = $fechaIngreso->diffInDays($fechaActual);
            if($diasTranscurridos == 0){
                $diasTranscurridos = $diasTranscurridos + 1;
            }
        }

        Log::info(['dato_cabecera_jhon' => $dato_cabecera_jhon]);

        $model_asegurado = new Asegurado();
        $datos_asegurado = $model_asegurado->busca_asegurado_por_dni($dato_cabecera_jhon[0]->dni);

        $antecedentes =  DB::table('antecedentes_enfermerias')
        ->select('antecedentes_enfermerias.*')
        ->where('id_enfermeria',$datos_enfermeria->id)
        ->first();

        $detalle_signo_enfermeria = "SELECT * FROM signo_vitales_detalles where id_signo_vital = '$datos_signos_vitales->id' order by id_caracter_fisico asc";
        $detalle_signo_enfermeria = DB::select($detalle_signo_enfermeria);
        if($detalle_signo_enfermeria[0]->id_turno == 1){
            $dato_turno = 'MADRUGADA';
        }elseif($detalle_signo_enfermeria[0]->id_turno == 2){
            $dato_turno = 'MAÑANA';
        }elseif($detalle_signo_enfermeria[0]->id_turno == 3){
            $dato_turno = 'TARDE';
        }else{
            $dato_turno = 'NOCHE';
        }

        $numero_filas = count($detalle_signo_enfermeria);
        $prsionx = $detalle_signo_enfermeria[0]->descripcion; 
        $prsiony = $detalle_signo_enfermeria[1]->descripcion; 
        $cardiaca = $detalle_signo_enfermeria[2]->descripcion; 
        $respiratorio = $detalle_signo_enfermeria[3]->descripcion; 
        $sa02 = $detalle_signo_enfermeria[4]->descripcion; 
        $temperatura = $detalle_signo_enfermeria[5]->descripcion; 
        $pam = $detalle_signo_enfermeria[6]->descripcion; 
        if($numero_filas > 7){
            $flag_oxi = $detalle_signo_enfermeria[8]->descripcion; 
            $detalle_oxi = $detalle_signo_enfermeria[7]->descripcion;
        }else{
            $flag_oxi = 0; 
            $detalle_oxi = ' ';
        }
        $fecha_creacion = $detalle_signo_enfermeria[0]->created_at;

        $fechaedicion = Carbon::now();

        $datos_signos_vitales =  DB::table('signo_vitales')
        ->select('signo_vitales.*')
        ->where('id', $id_signo_vital)
        ->first();
      
        $id_editor = Auth::user()->id;
        $edito =DB::table('users')
        ->select('users.*')
        ->where('id',$id_editor)
        ->first();

        $fechaedicion = Carbon::now();
        $existeAtencionMedica = Prestaciones::where('id_ext', '=', $datos_nota_ingreso[0]->id_ext_enfermeria)
                                ->where('id_tipo_atencion', $id_tipo_atenciones)
                                ->exists();
        return  view('frontend.emergencia_enfermeria.ver_edicion_signos_vitales',
        compact('datos_signos_vitales','datos_enfermeria','responsable_enfermeria','responsable_signos_vitales','fecha_creacion',
        'responsable_editor','diasTranscurridos','dato_cabecera_jhon','datos_nota_ingreso','antecedentes','id_signo_vital','data_historica','datos_asegurado',
        'dato_turno','fechaedicion','edito', 'id_tipo_atenciones', 'existeAtencionMedica'));
    }

    public function ver_pdf_signos_vitales_unidad($id_signo_vital){
      
        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
   
        $id_signo_vital = $id_signo_vital;
        $datos_signos_vitales =  DB::table('signo_vitales')
        ->select('signo_vitales.*')
        ->where('id', $id_signo_vital)
        ->first();

        $datos_enfermeria =  DB::table('enfermerias')
        ->select('enfermerias.*')
        ->where('id', $datos_signos_vitales->id_enfermeria)
        ->first();
        $responsable_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_enfermeria->created_us)
        ->first();
        $responsable_signos_vitales = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_signos_vitales->created_us)
        ->first();
        $id_editor = Auth::user()->id;
        $responsable_editor = DB::table('users')
        ->select('users.*')
        ->where('id', $id_editor)
        ->first();
        $id_enfermeria = $datos_enfermeria->id;
        $id_nota_ingreso = $datos_enfermeria->id;
        $id_tipo_atenciones = '2';
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //--- -----------
		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
		$nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones); 
        
        $usuario_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id',$datos_nota_ingreso[0]->created_us)
        ->first();

        $dato_cabecera_jhon = "    SELECT
		e3.nombre as establecimiento,
        ah.nro_historia AS historia_clinica,
		e2.id::varchar as id_emergencia,
        LPAD(e2.id::VARCHAR, 10, '0')::varchar AS nro_emergencia,
        a.nro_doc_ident::TEXT AS dni,
        a.nombre AS asegurado_nombre,
        a.paterno AS asegurado_paterno,
        a.materno AS asegurado_materno,
        a.sexo::varchar AS sexo,
        EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios,
        EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses,
        EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias,
        a.fecha_nac::date AS asegurado_fecha_nacimiento,
        tb.descripcion AS asegurado_tipo_beneficiario,
        e2.talla_cm AS asegurado_talla,
        e2.peso_kg AS asegurado_peso,
        a.tipo_sangre::varchar AS asegurado_tipo_sangre,
        e2.nombre_conducido AS asegurado_acompañante,
        e2.fecha_registro_ingreso AS fecha_registro_ingreso,
        e3.nivel::varchar AS nivel_ipress,
        e3.codigo AS codigo_renipress,
        t.descripcion AS servicio,
        p3.nombre AS pabellon,
        p2.nombre AS piso,
        c2.nombre AS cuarto,
        c.nombre AS cama,
        m.name AS nombre_medico,
        m.paterno AS paterno_medico,
        m.materno AS materno_medico,
        m.dni AS dni_medico,
        e4.nombre AS especialidad_medico
    FROM
        enfermerias e
    LEFT JOIN emergencias e2 ON
        e.id_ext_enfermeria = e2.id
    LEFT JOIN prestaciones p ON
        p.id_ext = e2.id
    LEFT JOIN asegurados a ON
        p.id_asegurado = a.id 
    LEFT JOIN asegurado_historias ah ON
        ah.id_asegurado = p.id_asegurado AND ah.id_establecimiento = p.id_establecimiento
    LEFT JOIN tipo_beneficiarios tb ON
        a.id_tipo_beneficiario = tb.id
    LEFT JOIN establecimientos e3 ON
        p.id_establecimiento = e3.id
    LEFT JOIN triajes t ON
        e2.id_triaje = t.id
    LEFT JOIN pisos p2 ON
        p.id_establecimiento = p2.id_establecimiento AND p2.nombre_atencion = 'EMERGENCIA'
    LEFT JOIN pabellones p3 ON
        p2.id_pabellon = p3.id
    INNER JOIN camas c ON
        e2.cama_estado = c.id
    INNER JOIN cuartos c2 ON
        c.id_cuarto = c2.id
    LEFT JOIN users m ON
        e2.id_medico_triaje = m.id
    left join medicos m2 on
    	m2.dni = m.dni
    LEFT JOIN especializaciones e4 ON
        m2.id_especializacion = e4.id
    WHERE
        e.id ='$id_nota_ingreso' ";
        
        $dato_cabecera_jhon = DB::select($dato_cabecera_jhon);

        if (is_array($dato_cabecera_jhon) && !empty($dato_cabecera_jhon)) {
            $servicio = $dato_cabecera_jhon[0]->servicio;
            $pabellon = $dato_cabecera_jhon[0]->pabellon;
            $piso = $dato_cabecera_jhon[0]->piso;
            $cuarto = $dato_cabecera_jhon[0]->cuarto;
            $cama = $dato_cabecera_jhon[0]->cama;
        } else {
            $servicio = '';
            $pabellon = '';
            $piso = '';
            $cuarto = '';
            $cama = '';
        }

		if (is_object($datos_nota_ingreso[0])){   
         
            $id_emergencia='';
            $codigo_ubig_responsable='';
            $codigo_ubig_apoderado='';
            $datos_atencion='';
            $diagnosticos='';

                $id_emergencia = $datos_nota_ingreso[0]->id_ext_enfermeria;
                $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_emergencia);
                $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_emergencia);
                $datos_atencion = $model_receta->getEmergenciaById_farmacia_new($id_emergencia, $codigo_ubig_responsable, $codigo_ubig_apoderado); 

                $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($id_emergencia); 

            $nota_detalle='';

            $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
            ->select('antecedentes_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
  
$valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
            ->select('valoraciones_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();

$signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
$signos_vitales = DB::select($signos_vitales);

$valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                ->select('valoraciones_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                ->select('pieldispositivo_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$enfermeria_escalas =  DB::table('enfermeria_escalas')
            ->select('enfermeria_escalas.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
          
$nombre = '';
if(isset($antecedentesenfermeria->cardio_parametro_1)){
$dato1 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                ->first();
$nombre = $nombre .' '. $dato1->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_2)){
$dato2 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                ->first();
$nombre = $nombre .' - '. $dato2->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_3)){
$dato3 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                ->first();
$nombre = $nombre .' - '. $dato3->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_4)){
$dato4 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                ->first();
$nombre = $nombre .' - '. $dato4->descrip_corta ;
}

$ingresa_por = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_ingresa_por)
->first();

$condicion_ingreso = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
->first();

$estado_conciencia = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_estado_conciencia)
->first();

$funcion_motora = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_funcion_motora)
->first();

$respiratorio = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_respiratorio)
->first();

$detalle_oxigeno  = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
->first();

$piel = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_piel)
->first();

$integridad = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_integridad)
->first();

$abdomen = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_abdomen)
->first();

$puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
$puntosenviados = DB::select($puntos);      

$emergencia_data_jhon = DB::table('emergencias')
->select('emergencias.*')
->where('id',$id_emergencia)
->first();

if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
->where('id_estado_reg','1')
->first();
}else{
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
->where('id_estado_reg','1')
->first();
}

///////////
$antecedentes =  DB::table('antecedentes_enfermerias')
->select('antecedentes_enfermerias.*')
->where('id_enfermeria',$id_enfermeria)
->first();

$estado = '1';
$arreglo_lista = [];
$i = 0;

$dato_signo_vitales = DB::select("SELECT * FROM signo_vitales WHERE id = '$id_signo_vital' and id_enfermeria = ?", [$id_enfermeria] );

foreach($dato_signo_vitales as $signo) {

$arreglo_lista[$i] = new \stdClass();

$dato1 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '1')
    ->first();

$dato2 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '2')
    ->first();

$dato3 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '4')
    ->first();

$dato4 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '6')
    ->first();

$dato5 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '9')
    ->first();

$dato6 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '17')
    ->first();

$dato7 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '8')
    ->first();

$dato8 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '79')
    ->first();

$dato9 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '78')
    ->first();

    if($dato8->descripcion == 1){
        $oxige = 'SI';
    }else{
        $oxige = 'NO';
    }
    $responsable = $dato1 ? DB::table('users')->select('users.*')->where('id', $dato1->created_us)->first() : null;
         
    $arreglo_lista[$i]->presionx = $dato1 ? $dato1->descripcion : 'N/A';
    $arreglo_lista[$i]->presiony = $dato2 ? $dato2->descripcion : 'N/A';
    $arreglo_lista[$i]->cardiaca = $dato3 ? $dato3->descripcion : 'N/A';
    $arreglo_lista[$i]->respiratorio = $dato4 ? $dato4->descripcion : 'N/A';
    $arreglo_lista[$i]->temperatura = $dato5 ? $dato5->descripcion : 'N/A';
    $arreglo_lista[$i]->presionmedia = $dato6 ? $dato6->descripcion : 'N/A';
    $arreglo_lista[$i]->sa02 = $dato7 ? $dato7->descripcion : 'N/A';
    $arreglo_lista[$i]->flag = $oxige;
    $arreglo_lista[$i]->defalle_ = $dato9 ? $dato9->descripcion : 'N/A';
    $fecha = $dato1 ? $dato1->created_at : 'N/A';
    $arreglo_lista[$i]->fecha = $fecha;

    $partes = explode(' ', $fecha);
    $soloFecha = $partes[0];

    if ($dato1 && $dato1->id_turno == '1') {
        $datoturo = 'MADRUGRADA';
    } elseif ($dato1 && $dato1->id_turno == '2') {
        $datoturo = 'MAÑANA';
    } elseif ($dato1 && $dato1->id_turno == '3') {
        $datoturo = 'TARDE';
    } else {
        $datoturo = 'NOCHE';
    }
    $arreglo_lista[$i]->turno = $datoturo;
    $arreglo_lista[$i]->responsable = $responsable ? $responsable->name . ', ' . $responsable->paterno . ' ' . $responsable->materno : 'N/A';
    $arreglo_lista[$i]->ubica = isset($dato_cabecera_jhon[0]) ? $dato_cabecera_jhon[0]->cuarto : 'N/A';
    $arreglo_lista[$i]->id_signos_vitales = $signo->id;
    $arreglo_lista[$i]->numero = $i + 1;

    $cardiaca = $dato1 ? $dato1->descripcion : 0;
    $cardiaca1 = $dato2 ? $dato2->descripcion : 0;
    $cardiaca2 = $dato3 ? $dato3->descripcion : 0;
    $cardiaca3 = $dato4 ? $dato4->descripcion : 0;
    $cardiaca4 = $dato5 ? $dato5->descripcion : 0;
    $cardiaca5 = $dato6 ? $dato6->descripcion : 0;
    $i++;

}

$imprime_pdf = $responsable->name .', '. $responsable->paterno .' ' .  $responsable->materno;
            $pdf = \PDF::loadView('frontend.emergencia_enfermeria.pdf_signo_vitales_individual',
            compact(
                'valores_Cardio',
                'datos_nota_ingreso',  
                'datos_atencion',
                'diagnosticos' ,
                'nota_detalle' ,
                'nombre_establecimiento',
                'fechaActual','i',
                'horaActual','usuario_enfermeria','estado','imprime_pdf','datosPuntos6',
                'servicio','pabellon','piso','cuarto','cama','arreglo_lista','datosBarras','datosPuntos3','datosPuntos4','datosPuntos5',
                'datos_nota_ingreso','datos_atencion','id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                'estado_conciencia','funcion_motora','respiratorio','detalle_oxigeno','piel','integridad','abdomen','puntosenviados','asegurado_titular',
                'establecimiento','diagnosticos','diagnosticos_evaluacion'
            

            ));
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('nota_ingreso_emergencia.pdf'); 

        }else{
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}
    public function ver_pdf_signos_vitales_unidad_hospi($id_signo_vital){
      
        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
   
        $id_signo_vital = $id_signo_vital;
        $datos_signos_vitales =  DB::table('signo_vitales')
        ->select('signo_vitales.*')
        ->where('id', $id_signo_vital)
        ->first();

        $datos_enfermeria =  DB::table('enfermerias')
        ->select('enfermerias.*')
        ->where('id', $datos_signos_vitales->id_enfermeria)
        ->first();
        $responsable_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_enfermeria->created_us)
        ->first();
        $responsable_signos_vitales = DB::table('users')
        ->select('users.*')
        ->where('id', $datos_signos_vitales->created_us)
        ->first();
        $id_editor = Auth::user()->id;
        $responsable_editor = DB::table('users')
        ->select('users.*')
        ->where('id', $id_editor)
        ->first();
        $id_enfermeria = $datos_enfermeria->id;
        $id_nota_ingreso = $datos_enfermeria->id;
        $id_tipo_atenciones = '3';
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //--- -----------
		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
		$nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id_hospi($id_nota_ingreso, $id_tipo_atenciones); 
        
        $usuario_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id',$datos_nota_ingreso[0]->created_us)
        ->first();

        $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforHospi($id_nota_ingreso);

        if (is_array($dato_cabecera_jhon) && !empty($dato_cabecera_jhon)) {
            $servicio = $dato_cabecera_jhon[0]->servicio;
            $pabellon = $dato_cabecera_jhon[0]->pabellon;
            $piso = $dato_cabecera_jhon[0]->piso;
            $cuarto = $dato_cabecera_jhon[0]->cuarto;
            $cama = $dato_cabecera_jhon[0]->cama;
        } else {
            $servicio = '';
            $pabellon = '';
            $piso = '';
            $cuarto = '';
            $cama = '';
        }

		if (is_object($datos_nota_ingreso[0])){   
         
            $id_emergencia='';
            $codigo_ubig_responsable='';
            $codigo_ubig_apoderado='';
            $datos_atencion='';
            $diagnosticos='';

                $id_emergencia = $datos_nota_ingreso[0]->id_ext_enfermeria;
                $datos_atencion = $model_receta->getHospitalizacionById_farmacia_new($id_emergencia); 

                $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($id_emergencia); 

            $nota_detalle='';

            $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
            ->select('antecedentes_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
  
$valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
            ->select('valoraciones_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();

$signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
$signos_vitales = DB::select($signos_vitales);

$valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                ->select('valoraciones_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                ->select('pieldispositivo_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$enfermeria_escalas =  DB::table('enfermeria_escalas')
            ->select('enfermeria_escalas.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
          
$nombre = '';
if(isset($antecedentesenfermeria->cardio_parametro_1)){
$dato1 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                ->first();
$nombre = $nombre .' '. $dato1->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_2)){
$dato2 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                ->first();
$nombre = $nombre .' - '. $dato2->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_3)){
$dato3 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                ->first();
$nombre = $nombre .' - '. $dato3->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_4)){
$dato4 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                ->first();
$nombre = $nombre .' - '. $dato4->descrip_corta ;
}

$ingresa_por = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_ingresa_por)
->first();

$condicion_ingreso = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
->first();

$estado_conciencia = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_estado_conciencia)
->first();

$funcion_motora = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_funcion_motora)
->first();

$respiratorio = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_respiratorio)
->first();

$detalle_oxigeno  = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
->first();

$piel = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_piel)
->first();

$integridad = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_integridad)
->first();

$abdomen = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_abdomen)
->first();

$puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
$puntosenviados = DB::select($puntos);      

/* $emergencia_data_jhon = DB::table('emergencias')
->select('emergencias.*')
->where('id',$id_emergencia)
->first();

if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
->where('id_estado_reg','1')
->first();
}else{
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
->where('id_estado_reg','1')
->first();
} */

///////////
$antecedentes =  DB::table('antecedentes_enfermerias')
->select('antecedentes_enfermerias.*')
->where('id_enfermeria',$id_enfermeria)
->first();

$estado = '1';
$arreglo_lista = [];
$i = 0;

$dato_signo_vitales = DB::select("SELECT * FROM signo_vitales WHERE id = '$id_signo_vital' and id_enfermeria = ?", [$id_enfermeria] );

foreach($dato_signo_vitales as $signo) {

$arreglo_lista[$i] = new \stdClass();

$dato1 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '1')
    ->first();

$dato2 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '2')
    ->first();

$dato3 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '4')
    ->first();

$dato4 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '6')
    ->first();

$dato5 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '9')
    ->first();

$dato6 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '17')
    ->first();

$dato7 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '8')
    ->first();

$dato8 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '79')
    ->first();

$dato9 = DB::table('signo_vitales_detalles')
    ->select('signo_vitales_detalles.*')
    ->where('id_signo_vital', $signo->id)
    ->where('id_caracter_fisico', '78')
    ->first();

    if($dato8->descripcion == 1){
        $oxige = 'SI';
    }else{
        $oxige = 'NO';
    }
    $responsable = $dato1 ? DB::table('users')->select('users.*')->where('id', $dato1->created_us)->first() : null;
         
    $arreglo_lista[$i]->presionx = $dato1 ? $dato1->descripcion : 'N/A';
    $arreglo_lista[$i]->presiony = $dato2 ? $dato2->descripcion : 'N/A';
    $arreglo_lista[$i]->cardiaca = $dato3 ? $dato3->descripcion : 'N/A';
    $arreglo_lista[$i]->respiratorio = $dato4 ? $dato4->descripcion : 'N/A';
    $arreglo_lista[$i]->temperatura = $dato5 ? $dato5->descripcion : 'N/A';
    $arreglo_lista[$i]->presionmedia = $dato6 ? $dato6->descripcion : 'N/A';
    $arreglo_lista[$i]->sa02 = $dato7 ? $dato7->descripcion : 'N/A';
    $arreglo_lista[$i]->flag = $oxige;
    $arreglo_lista[$i]->defalle_ = $dato9 ? $dato9->descripcion : 'N/A';
    $fecha = $dato1 ? $dato1->created_at : 'N/A';
    $arreglo_lista[$i]->fecha = $fecha;

    $partes = explode(' ', $fecha);
    $soloFecha = $partes[0];

    if ($dato1 && $dato1->id_turno == '1') {
        $datoturo = 'MADRUGRADA';
    } elseif ($dato1 && $dato1->id_turno == '2') {
        $datoturo = 'MAÑANA';
    } elseif ($dato1 && $dato1->id_turno == '3') {
        $datoturo = 'TARDE';
    } else {
        $datoturo = 'NOCHE';
    }
    $arreglo_lista[$i]->turno = $datoturo;
    $arreglo_lista[$i]->responsable = $responsable ? $responsable->name . ', ' . $responsable->paterno . ' ' . $responsable->materno : 'N/A';
    $arreglo_lista[$i]->ubica = isset($dato_cabecera_jhon[0]) ? $dato_cabecera_jhon[0]->cuarto : 'N/A';
    $arreglo_lista[$i]->id_signos_vitales = $signo->id;
    $arreglo_lista[$i]->numero = $i + 1;

    $cardiaca = $dato1 ? $dato1->descripcion : 0;
    $cardiaca1 = $dato2 ? $dato2->descripcion : 0;
    $cardiaca2 = $dato3 ? $dato3->descripcion : 0;
    $cardiaca3 = $dato4 ? $dato4->descripcion : 0;
    $cardiaca4 = $dato5 ? $dato5->descripcion : 0;
    $cardiaca5 = $dato6 ? $dato6->descripcion : 0;
    $i++;

}

$imprime_pdf = $responsable->name .', '. $responsable->paterno .' ' .  $responsable->materno;
            $pdf = \PDF::loadView('frontend.hospitalizacion_enfermeria.pdf_signo_vitales_individual',
            compact(
                'valores_Cardio',
                'datos_nota_ingreso',  
                'datos_atencion',
                'diagnosticos' ,
                'nota_detalle' ,
                'nombre_establecimiento',
                'fechaActual','i',
                'horaActual','usuario_enfermeria','estado','imprime_pdf','datosPuntos6',
                'servicio','pabellon','piso','cuarto','cama','arreglo_lista','datosBarras','datosPuntos3','datosPuntos4','datosPuntos5',
                'datos_nota_ingreso','datos_atencion','id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                'estado_conciencia','funcion_motora','respiratorio','detalle_oxigeno','piel','integridad','abdomen','puntosenviados',/* 'asegurado_titular', */
                'establecimiento','diagnosticos','diagnosticos_evaluacion'
            

            ));
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('nota_ingreso_emergencia.pdf'); 

        }else{
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}

    public function FirmarPDFSignos(Request $request)
    {
        $path = public_path('tmp/csv');
        $path_zip = public_path('tmp/zip');
        if (!is_dir($path)) {
            mkdir($path);
        }
        if (!is_dir($path_zip)) {
            mkdir($path_zip);
            mkdir(public_path('tmp/zip/firmados'));
            mkdir(public_path('tmp/zip/sin_firma'));
        }
        $csv = "";
        if (Auth::check()):
            $csv .= Auth::user()->id;
        endif;
        $enfermeria_id = $request->enfermeria_id;
        $id_tipo_atencion = $request->tipo_atencion;
        $csv .= "_".date("YmdHis");
        Excel::create($csv, function($excel) use($enfermeria_id, $id_tipo_atencion){
            $excel->sheet('GENERAL', function($sheet) use($enfermeria_id, $id_tipo_atencion) {
                $variable = [];
                $this->GenerarPDFFirmarSignosConsolidado($enfermeria_id, $id_tipo_atencion);
                array_push($variable, array(url().'/tmp/pdf/sin_firma/signos_vitales_consolidado_'.$enfermeria_id.'.pdf', url().'/upload_firma/?name=cf_signos_vitales_consolidado_'.$enfermeria_id.'.pdf&id_tipo=signos_consolidado&id_resol='.$enfermeria_id, 'cf_signos_vitales_consolidado_'.$enfermeria_id.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
                $sheet->fromArray($variable, null, '', false, false);
            });
        })->store('csv',public_path('tmp/csv'));
        $obj = new Archive7z(public_path('tmp/zip/sin_firma/'.$csv.'.7z'));
        $obj->addEntry(public_path('/tmp/pdf/sin_firma/signos_vitales_consolidado_'.$enfermeria_id.'.pdf'));
        $resultado["csv"]=$csv;
        $resultado["sw"]=true;
        return response()->json($resultado); 
    }

    public function GenerarPDFFirmarSignosConsolidado($enfermeria_id, $id_tipo_atencion)
    {
        $path = public_path('tmp/pdf');
		if (!is_dir($path)) {
			mkdir($path);
		}
		$path = public_path('tmp/pdf/sin_firma/');
		if (!is_dir($path)) {
			mkdir($path);
		}

        $id_nota_ingreso = $enfermeria_id;
        $id_tipo_atenciones = $id_tipo_atencion;
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //--- -----------
		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
		$nota_ingreso = new Nota_enfermeria; 

        $datos_nota_ingreso = null;
        $dato_cabecera_jhon = null;
        $codigo_ubig_responsable = null;
        $codigo_ubig_apoderado = null;
        $datos_atencion = null;
        $diagnosticos = null;

        if ($id_tipo_atenciones == 3) {
            $datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id_hospi($id_nota_ingreso, $id_tipo_atenciones); 
            $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforHospi($id_nota_ingreso);
        } else if ($id_tipo_atenciones == 2) {
            $datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones); 
            /* $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforEmergencia($id_nota_ingreso); */
            $dato_cabecera_jhon = "    SELECT
		e3.nombre as establecimiento,
        ah.nro_historia AS historia_clinica,
		e2.id::varchar as id_emergencia,
        LPAD(e2.id::VARCHAR, 10, '0')::varchar AS nro_emergencia,
        a.nro_doc_ident::TEXT AS dni,
        a.nombre AS asegurado_nombre,
        a.paterno AS asegurado_paterno,
        a.materno AS asegurado_materno,
        a.sexo::varchar AS sexo,
        EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_anios,
        EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_meses,
        EXTRACT(DAY FROM AGE(CURRENT_DATE, a.fecha_nac::DATE))::INT AS asegurado_edad_dias,
        a.fecha_nac::date AS asegurado_fecha_nacimiento,
        tb.descripcion AS asegurado_tipo_beneficiario,
        e2.talla_cm AS asegurado_talla,
        e2.peso_kg AS asegurado_peso,
        a.tipo_sangre::varchar AS asegurado_tipo_sangre,
        e2.nombre_conducido AS asegurado_acompañante,
        e2.fecha_registro_ingreso AS fecha_registro_ingreso,
        e3.nivel::varchar AS nivel_ipress,
        e3.codigo AS codigo_renipress,
        t.descripcion AS servicio,
        p3.nombre AS pabellon,
        p2.nombre AS piso,
        c2.nombre AS cuarto,
        c.nombre AS cama,
        m.name AS nombre_medico,
        m.paterno AS paterno_medico,
        m.materno AS materno_medico,
        m.dni AS dni_medico,
        e4.nombre AS especialidad_medico
    FROM
        enfermerias e
    LEFT JOIN emergencias e2 ON
        e.id_ext_enfermeria = e2.id
    LEFT JOIN prestaciones p ON
        p.id_ext = e2.id
    LEFT JOIN asegurados a ON
        p.id_asegurado = a.id 
    LEFT JOIN asegurado_historias ah ON
        ah.id_asegurado = p.id_asegurado AND ah.id_establecimiento = p.id_establecimiento
    LEFT JOIN tipo_beneficiarios tb ON
        a.id_tipo_beneficiario = tb.id
    LEFT JOIN establecimientos e3 ON
        p.id_establecimiento = e3.id
    LEFT JOIN triajes t ON
        e2.id_triaje = t.id
    LEFT JOIN pisos p2 ON
        p.id_establecimiento = p2.id_establecimiento AND p2.nombre_atencion = 'EMERGENCIA'
    LEFT JOIN pabellones p3 ON
        p2.id_pabellon = p3.id
    INNER JOIN camas c ON
        e2.cama_estado = c.id
    INNER JOIN cuartos c2 ON
        c.id_cuarto = c2.id
    LEFT JOIN users m ON
        e2.id_medico_triaje = m.id
    left join medicos m2 on
    	m2.dni = m.dni
    LEFT JOIN especializaciones e4 ON
        m2.id_especializacion = e4.id
    WHERE
        e.id ='$id_nota_ingreso' ";
        
        $dato_cabecera_jhon = DB::select($dato_cabecera_jhon);
        }

        $usuario_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id',$datos_nota_ingreso[0]->created_us)
        ->first();

        if (is_array($dato_cabecera_jhon) && !empty($dato_cabecera_jhon)) {
            $servicio = $dato_cabecera_jhon[0]->servicio;
            $pabellon = $dato_cabecera_jhon[0]->pabellon;
            $piso = $dato_cabecera_jhon[0]->piso;
            $cuarto = $dato_cabecera_jhon[0]->cuarto;
            $cama = $dato_cabecera_jhon[0]->cama;
        } else {
            $servicio = '';
            $pabellon = '';
            $piso = '';
            $cuarto = '';
            $cama = '';
        }

		if (is_object($datos_nota_ingreso[0])){   
         
            $id_ext = $datos_nota_ingreso[0]->id_ext_enfermeria;
            if ($id_tipo_atenciones == 3) {
                /* $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable($id_ext);
                $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado($id_ext); */
                $datos_atencion = $model_receta->getHospitalizacionById_farmacia_new($id_ext); 
                $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($id_ext); 
            } else if ($id_tipo_atenciones == 2) {
                /* $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_ext);
                $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_ext); */
                $datos_atencion = $model_receta->getEmergenciaById_farmacia_new($id_ext); 
                $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($id_ext); 
            }

            $nota_detalle='';

            $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
            ->select('antecedentes_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
  
            $valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
            ->select('valoraciones_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();

            $signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
            $signos_vitales = DB::select($signos_vitales);

            $valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
            ->select('valoraciones_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();

            $pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
            ->select('pieldispositivo_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();

            $enfermeria_escalas =  DB::table('enfermeria_escalas')
            ->select('enfermeria_escalas.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
          
            $nombre = '';
            if(isset($antecedentesenfermeria->cardio_parametro_1)){
            $dato1 = DB::table('parametros')
                            ->select('parametros.*')
                            ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                            ->first();
            $nombre = $nombre .' '. $dato1->descrip_corta;
            }
            if(isset($antecedentesenfermeria->cardio_parametro_2)){
            $dato2 = DB::table('parametros')
                            ->select('parametros.*')
                            ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                            ->first();
            $nombre = $nombre .' - '. $dato2->descrip_corta;
            }
            if(isset($antecedentesenfermeria->cardio_parametro_3)){
            $dato3 = DB::table('parametros')
                            ->select('parametros.*')
                            ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                            ->first();
            $nombre = $nombre .' - '. $dato3->descrip_corta;
            }
            if(isset($antecedentesenfermeria->cardio_parametro_4)){
            $dato4 = DB::table('parametros')
                            ->select('parametros.*')
                            ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                            ->first();
            $nombre = $nombre .' - '. $dato4->descrip_corta ;
            }

            $ingresa_por = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->id_ingresa_por)
            ->first();

            $condicion_ingreso = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
            ->first();

            $estado_conciencia = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->id_estado_conciencia)
            ->first();

            $funcion_motora = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->id_funcion_motora)
            ->first();

            $respiratorio = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->id_respiratorio)
            ->first();

            $detalle_oxigeno  = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
            ->first();

            $piel = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->id_piel)
            ->first();

            $integridad = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->id_integridad)
            ->first();

            $abdomen = DB::table('parametros')
            ->select('parametros.*')
            ->where('id',$valoraciones_enfermerias->id_abdomen)
            ->first();

            $puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
            $puntosenviados = DB::select($puntos);      

            ///////////
            $antecedentes =  DB::table('antecedentes_enfermerias')
            ->select('antecedentes_enfermerias.*')
            ->where('id_enfermeria',$enfermeria_id)
            ->first();

            $estado = '1';
            $arreglo_lista = [];
            $i = 0;

            $dato_signo_vitales = DB::select("SELECT * FROM signo_vitales WHERE id_enfermeria = ?", [$enfermeria_id]);

            if (empty($dato_signo_vitales)) {
                $estado = '0';
                $datosPuntos = [
                    ["x" => 0, "y" => 0],
                ];
                $datosPuntos2 = [
                    ["x" => 0, "y" => 0],
                ];
                $datosPuntos3 = [
                    ["x" => 0, "y" => 0],
                ];
                $datosPuntos4 = [
                    ["x" => 0, "y" => 0],
                ];
                $datosPuntos5 = [
                    ["x" => 0, "y" => 0],
                ];
                $datosPuntos6 = [
                    ["x" => 0, "y" => 0],
                ];
                $datosBarras = [
                    ['label' => 'SIN DATOS', 'start' => 0, 'end' => 0],
                ];
            } else {
                foreach($dato_signo_vitales as $signo) {

                    $arreglo_lista[$i] = new \stdClass();

                    $dato1 = DB::table('signo_vitales_detalles')
                        ->select('signo_vitales_detalles.*')
                        ->where('id_signo_vital', $signo->id)
                        ->where('id_caracter_fisico', '1')
                        ->first();

                    $dato2 = DB::table('signo_vitales_detalles')
                        ->select('signo_vitales_detalles.*')
                        ->where('id_signo_vital', $signo->id)
                        ->where('id_caracter_fisico', '2')
                        ->first();

                    $dato3 = DB::table('signo_vitales_detalles')
                        ->select('signo_vitales_detalles.*')
                        ->where('id_signo_vital', $signo->id)
                        ->where('id_caracter_fisico', '4')
                        ->first();

                    $dato4 = DB::table('signo_vitales_detalles')
                        ->select('signo_vitales_detalles.*')
                        ->where('id_signo_vital', $signo->id)
                        ->where('id_caracter_fisico', '6')
                        ->first();

                    $dato5 = DB::table('signo_vitales_detalles')
                        ->select('signo_vitales_detalles.*')
                        ->where('id_signo_vital', $signo->id)
                        ->where('id_caracter_fisico', '9')
                        ->first();

                    $dato6 = DB::table('signo_vitales_detalles')
                        ->select('signo_vitales_detalles.*')
                        ->where('id_signo_vital', $signo->id)
                        ->where('id_caracter_fisico', '17')
                        ->first();

                    $responsable = $dato1 ? DB::table('users')->select('users.*')->where('id', $dato1->created_us)->first() : null;
                        
                    $arreglo_lista[$i]->presionx = $dato1 ? $dato1->descripcion : 'N/A';
                    $arreglo_lista[$i]->presiony = $dato2 ? $dato2->descripcion : 'N/A';
                    $arreglo_lista[$i]->cardiaca = $dato3 ? $dato3->descripcion : 'N/A';
                    $arreglo_lista[$i]->respiratorio = $dato4 ? $dato4->descripcion : 'N/A';
                    $arreglo_lista[$i]->temperatura = $dato5 ? $dato5->descripcion : 'N/A';
                    $arreglo_lista[$i]->presionmedia = $dato6 ? $dato6->descripcion : 'N/A';
                    $fecha = $dato1 ? $dato1->created_at : 'N/A';
                    $arreglo_lista[$i]->fecha = $fecha;

                    $partes = explode(' ', $fecha);
                    $soloFecha = $partes[0];

                    if ($dato1 && $dato1->id_turno == '1') {
                        $datoturo = 'MADRUGRADA';
                    } elseif ($dato1 && $dato1->id_turno == '2') {
                        $datoturo = 'MAÑANA';
                    } elseif ($dato1 && $dato1->id_turno == '3') {
                        $datoturo = 'TARDE';
                    } else {
                        $datoturo = 'NOCHE';
                    }
                    $arreglo_lista[$i]->turno = $datoturo;
                    $arreglo_lista[$i]->responsable = $responsable ? $responsable->name . ', ' . $responsable->paterno . ' ' . $responsable->materno : 'N/A';
                    $arreglo_lista[$i]->ubica = isset($dato_cabecera_jhon[0]) ? $dato_cabecera_jhon[0]->cuarto : 'N/A';
                    $arreglo_lista[$i]->id_signos_vitales = $signo->id;
                    $arreglo_lista[$i]->numero = $i + 1;

                    $cardiaca = $dato1 ? $dato1->descripcion : 0;
                    $cardiaca1 = $dato2 ? $dato2->descripcion : 0;
                    $cardiaca2 = $dato3 ? $dato3->descripcion : 0;
                    $cardiaca3 = $dato4 ? $dato4->descripcion : 0;
                    $cardiaca4 = $dato5 ? $dato5->descripcion : 0;
                    $cardiaca5 = $dato6 ? $dato6->descripcion : 0;

                    $datosPuntos[] = [
                        'x' => $datoturo.' - '.$soloFecha,
                        'y' => $cardiaca
                    ];
                    $datosPuntos2[] = [
                        'x' =>  $datoturo.' - '.$soloFecha,
                        'y' => $cardiaca1
                    ];
                    $datosPuntos3[] = [
                        'x' =>  $datoturo.' - '.$soloFecha,
                        'y' => $cardiaca2
                    ];
                    $datosPuntos4[] = [
                        'x' => $datoturo.' - '.$soloFecha,
                        'y' => $cardiaca3
                    ];
                    $datosPuntos5[] = [
                        'x' => $datoturo.' - '.$soloFecha,
                        'y' => $cardiaca4
                    ];
                    $datosPuntos6[] = [
                        'x' => $datoturo.' - '.$soloFecha,
                        'y' => $cardiaca5
                    ];
                    $datosBarras[] = [
                        ['label' => $soloFecha . ' - ' . $datoturo, 'start' => $cardiaca1, 'end' => $cardiaca],
                    ];
                    $i++;
                }
            }

            $imprime_pdf = $responsable->name .', '. $responsable->paterno .' ' .  $responsable->materno;
            $carpeta = date("Y")."/".date("m")."/".date("d");
            $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_signos_vitales_consolidado_".$enfermeria_id.".pdf";
            $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_signos_vitales_consolidado_" . $enfermeria_id . ".pdf");
            $pdf = \PDF::loadView('frontend.hospitalizacion_enfermeria.pdf_signo_vitales_enfermeria_cf',
                    compact(
                        'valores_Cardio',
                        'datos_nota_ingreso',
                        'url_descarga',
                        'codigo_qr',
                        'dato_cabecera_jhon',  
                        'datos_atencion',
                        'diagnosticos' ,
                        'nota_detalle' ,
                        'nombre_establecimiento',
                        'fechaActual','i',
                        'horaActual','usuario_enfermeria','estado','imprime_pdf','datosPuntos6',
                        'servicio','pabellon','piso','cuarto','cama','arreglo_lista','datosBarras','datosPuntos3','datosPuntos4','datosPuntos5',
                        'datos_nota_ingreso','datos_atencion','id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                        'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                        'estado_conciencia','funcion_motora','respiratorio','detalle_oxigeno','piel','integridad','abdomen','puntosenviados','asegurado_titular',
                        'establecimiento','diagnosticos','diagnosticos_evaluacion', 'id_tipo_atencion'
                    ));
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('A4', 'portrait');
            file_put_contents($path."signos_vitales_consolidado_".$enfermeria_id. ".pdf", $pdf->output());
        }
    }

    public function ActualizarFirmaSignosConsolidado($enfermeria_id)
    {
        $signosConsolidadosDeUnaEnfermeria = signosVitales::where("id_enfermeria", $enfermeria_id)->get();
        if (count($signosConsolidadosDeUnaEnfermeria) == 0) {
            return response()->json([
                "message" => "No existe registro de los signos."
            ], 404);
        }
        foreach ($signosConsolidadosDeUnaEnfermeria as $key => $signoUnidad) {
            $signoUnidad->update([
                "ruta_firma" => "cf_signos_vitales_consolidado_{$enfermeria_id}.pdf",
                "fecha_firma" => Carbon::now(),
                "flag_firma" => 1
            ]);
        }
        return response()->json([
            "message" => "Se actualizo la firma de la nota de enfermeria exitosamente"
        ], 200);
    }
}