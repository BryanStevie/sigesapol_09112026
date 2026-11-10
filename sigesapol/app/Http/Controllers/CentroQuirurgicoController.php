<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use App\Exceptions\SigesapolException;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;
use App\Models\Farmacia;
use App\Models\Receta_vale;
use App\Models\Cita;
use App\Models\Prestaciones;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\Medico;
use App\Models\Kardexe;
use App\Models\CqSalas;
use App\Models\CqSalasTurnos;
use App\Models\Producto;
use App\Models\Medico_consultorio;
use App\Models\Hospitalizacione;
use App\Models\Stock_farmacia;
use App\Models\Asegurado;
use App\Models\Evaluacione;
use App\Models\Establecimiento;
use App\Models\Admision_enfermeria;
use App\Models\Paciente;
use App\Models\Asegurado_historia;
use App\Models\Tipo_grado;
use App\Models\Triaje;
use App\Models\Distrito;
use App\Models\Tipo_parentescos;
use App\Models\Emergencia;
use SoapClient;
use App\Models\Upss;
use App\Models\Cama;
use App\Models\Centro_quirurgico;
use App\Models\CqxCita;
use App\Models\CqxFormatoEscaneado;
use App\Models\HospitalizacionAntecedentes;
use App\Models\Producto_receta;
use App\Models\Receta_diagnostico;
use App\Models\SubConsultorio;
use App\Models\CqxIntervencion;
use App\Models\CqxProcedimiento;
use App\Models\CqxProfesional;
use App\Models\CqxTipoAnestesia;
use App\Models\CqxTipoCirugia;
use App\Models\HospitalizacionOrden;
use App\Models\Interconsulta;
use App\UseCases\CentroQuirurgico\GenerarPdfByIdUseCase;
use App\UseCases\CentroQuirurgico\ObtenerIntervencionByIdUseCase;
use App\UseCases\CentroQuirurgico\RegistrarAltaIntervencionNivel2UseCase;
//use App\UseCases\CentroQuirurgico\registrarAtencionCqxNivel2UseCase;
use App\UseCases\CentroQuirurgico\SuspenderCqxIntervencionNivel2UseCase;
use App\UseCases\CentroQuirurgico\VerDetalleIntervencionNivel2UseCase;
use App\UseCases\CentroQuirurgico\AltaIntervencionNivel2UseCase;
use Illuminate\Support\Facades\Log;
use Archive7z\Archive7z;
use Exception;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CqxObservacion;
use App\UseCases\CentroQuirurgico\FirmarPdfIntervencionNivel2UseCase;
use App\UseCases\CentroQuirurgico\GenerarPdfAltaCrisisUseCase;
use App\UseCases\CentroQuirurgico\GenerarPdfPapeletaEgresoUseCase;
use App\UseCases\CentroQuirurgico\ConfirmarFirmaPdfIntervencionNivel2UseCase;
use App\UseCases\CentroQuirurgico\ObtenerAltaIntervencionNivel2UseCase;
use App\UseCases\CentroQuirurgico\FirmarPdfAltaIntervencionNivel2UseCase;
use App\UseCases\CentroQuirurgico\ConfirmarFirmaAltaIntervencionNivel2UseCase;
use App\UseCases\CentroQuirurgico\GeneraPdfAdmisionHospitalizacionUseCase;
use App\UseCases\CentroQuirurgico\GenerarReporteNivel2UseCase;
use App\UseCases\CentroQuirurgico\RegistrarAtencionCqxNivel2UseCase;
use Validator;  
class CentroQuirurgicoController extends Controller
{
  function __construct()
  {
      $this->middleware('auth'); 
  } 
  
  public function lista_pendiente()
  {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      $id_user = Auth::user()->id;
      $id_farmacia = Auth::user()->id_farmacia;
      $id_establecimiento = Auth::user()->id_establecimiento;

      $origen = 'centro';
      $date = Carbon::now();
      $fechaHasta = $date->format('d-m-Y');
      $fechaDesdex = $date->format('d-m-Y');
      $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
      $fechaDesde = date('d-m-Y', $fechaDesdey);
      
      $dni_beneficiario = '';
      $nombre_beneficiario = '';
      $estado = 0;

      /*==========SECCION DE UPSS HOSPITALIZACION==========*/
      $model_upss = new Consultorio();
      $upss = $model_upss->getServiciosQuirurgicos();
      /*==========SECCION DE UPSS HOSPITALIZACION==========*/

      $medicos_quirurgicos=$this->medicosQuirurgicos();

      $establecimiento = Establecimiento::where('id', $id_establecimiento)->first();
      $id_user = Auth::user()->id;

      $model_medicos = new Medico();
      $medico = $model_medicos->GetDatosMedicoByDni(Auth::user()->dni);

      $medico_consultorio = new Medico_consultorio(); 
      $BancoSangre = $medico_consultorio->ServicioBancoSangre($medico->id_establecimiento,$medico->id);

      /* if ($establecimiento && intval($establecimiento->nivel) < 3) {
        return view('frontend.centro_quirurgico.nivel_2.all_pendientes_nivel_2', compact('origen','fechaDesde','BancoSangre','fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'upss', 'id_user', 'id_establecimiento', 'id_farmacia','medicos_quirurgicos', 'establecimiento'));
      } */

      $model_tipo_cirugia = new CqxTipoAnestesia();
      $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();
      $motivo_suspension = App\Models\CqxMotivoSuspension::where('estado', 1)->get();
      return view('frontend.centro_quirurgico.all_pendientes2', compact('origen','fechaDesde','BancoSangre', 'consultoriomedico','fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'upss', 'id_user', 'id_establecimiento', 'id_farmacia','medicos_quirurgicos','tipo_cirugias','motivo_suspension')
      );
    }

    public function pre_anestesiologo()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.centro_quirurgico.pre_anestesiologo');
    }

    public function post_atencion()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.centro_quirurgico.post_atencion');
    }

    public function registrar_intraoperario()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.centro_quirurgico.form_intraoperario'/*,compact()*/);

    }
    
    public function registro_seguridad()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.centro_quirurgico.form_registro_seguridad'/*,compact()*/);

    }
  
    public function instrumentos_quirurgicos()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.centro_quirurgico.form_instrumentos'/*,compact()*/);
    }

    public function listar_prestacion_intervencionqx_ajax(Request $request)
    {
        $model_citas = new Cita();
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = $request->fecha_ini_intervencion;
        $p[] = $request->fecha_fin_intervencion;
        $p[] = $request->dni_beneficiario;
        $p[] = $request->nombre_beneficiario;
        $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
        $p[] = $request->estado;
        $p[] = (isset($request->id_tipo_atencion) && $request->id_tipo_atencion != "") ? $request->id_tipo_atencion : 0;
        $p[] = Auth::user()->id_establecimiento;
        $p[] = !empty($request->id_medico)?$request->id_medico:null;
        $p[] = !empty($request->id_estado_pendiente)?$request->id_estado_pendiente:null;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        //$data = $model_citas->listar_prestacion_intervencionqx_ajax_v2($p);
        $data = $model_citas->listar_prestacion_intervencionqx_ajax_v3($p);
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

    public function formulario_cita_riesgo_quirurgico($id_prestacion, $id_cqx_cita, $id_servicio,$id_cqx_intervencion){
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        $model_cqx_cita = new \App\Models\CqxCita();
        $data_cqx_cita = $model_cqx_cita->GetById($id_cqx_cita);
        if ($data_cqx_cita != null && $data_cqx_cita->id_estado_reg == 1):
          if ($data_cqx_cita->flag == 1 || $data_cqx_cita->flag == 3):
        
            $model_kardex = new Kardexe();
            $model_usuario = new User;
          
            $fechaServidor = $model_kardex->getFechaServidor();
            $user = $model_usuario->GetById(Auth::user()->id);
            $flagemergenciareferencia = $user->flagemergenciareferencia;
          
            $model_prestacion = new Prestaciones();
            $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

            $model_consultorio = new Consultorio();
            $servicio = $model_consultorio->GetNombreById($id_servicio);
            $servicios_cqx = $model_consultorio->ListarServiciosCqxById($id_cqx_intervencion);
            return view('frontend.centro_quirurgico.form_cita_riesgo_quirurgico', compact('fechaServidor', 'flagemergenciareferencia', 'prestacion', 'servicio', 'id_cqx_cita','servicios_cqx','id_cqx_intervencion'));

          elseif ($data_cqx_cita->flag == 2):
            Session::flash('flash_warning', 'La cita ya fue registrada anteriormente, la que se muestra a continuación.');
            return redirect('/ver_cita_por_medico/' . Auth::user()->id . '/' . $data_cqx_cita->id_cita);
          else:
            Session::flash('flash_warning', 'La cita ya fue atendida, favor de actualizar el listado de Intervenciones Quirúrgicas.');
            return redirect('/cq_lista_pendiente');
          endif;
        else:
          Session::flash('flash_danger', 'Datos no existentes.');
          return redirect('/dashboard');
        endif;
    }
    public function hoja_recuperacion()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.centro_quirurgico.form_recuperacion');
    }
    public function programacion_salas()
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      else:
        if(Auth::user()->actualizo_passw==0):
          return redirect('auth/password/change');
        endif;
      endif;

      $id_user = Auth::user()->id;
      
      $model_usuario = new User;
      $establecimientotot = $model_usuario->GetById($id_user);
      $flagservicios = $establecimientotot->flagservicios;
      $modelfarma = new Farmacia();
      $modelmedico = new Medico();
      $model_consultorio = new Consultorio;
      $farmacias = $modelfarma->GetFarmaByUser($id_user);
      $id_establecimiento = $farmacias->id_establecimiento;
      $consultorio_quirurgico = $model_consultorio->ShowAllConsultoriosSubConsultorioQuirurgico($id_establecimiento);

      $cq_salas = CqSalas::select('id','nombre')->where('estado',1)->get();

      $fecha = $modelmedico->getFechaServidorUltimoDiaMes();
      $mes = $fecha->mes;
      $ano = $fecha->ano;
      $ruta = Route::getCurrentRoute()->getPath();
      if (count($ruta = explode("/", $ruta)) > 1);
      $ruta = $ruta[0];
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      $permiso = $menu[0];

      if ($permiso->lectura == "") {
        return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
      }
      //REGISTRO DE CENTRO QUIRURGICO
      $tipoatenciones = \App\Models\Cita::GetTipoDeAtension();
      $tipo_atenciones = array_filter($tipoatenciones, function($atencion) {
        return $atencion['id'] == 4;
      });

      return view(
        'frontend.centro_quirurgico.programacion_salas',
        compact('permiso', 'mes', 'ano', 'id_establecimiento','tipo_atenciones', 'flagservicios','consultorio_quirurgico','cq_salas')
      );
    }
    public function all_programacion_detalle($id_establecimiento,$id_sala,$mes,$ano,$id_consultorio)
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
    
      $cq_salas_turnos = new CqSalasTurnos();
      $fechaBuscar = "01-" . $mes . "-" . $ano;
      $fecha = $cq_salas_turnos->getFechaServidorUltimoDiaMes($fechaBuscar);
      
      $diaFin = $fecha->dia;
      $fechaServidor = $cq_salas_turnos->getFechaServidor();
      $modelfarma = new Farmacia();
      $farmacias = $modelfarma->GetFarmaByUser(Auth::user()->id);
      $id_establecimiento = $farmacias->id_establecimiento;

      //TURNOS POR SERVICIO Y SALA INGRESANTE
      $turno = $cq_salas_turnos->getServicioSalaAndMes($id_establecimiento,$id_consultorio,$id_sala,$mes,$ano);
      if (count($turno) > 0) :
        $turno = $this->listarTurnosConFechaDistinct($turno);
      endif;

      //TURNOS POR SALA DIFERENTE AL SERVICIO INGRESANTE
      $turnosServicio = $cq_salas_turnos->getTurnosSala($id_establecimiento,$id_consultorio,$id_sala, $ano, $mes); 

      $aHorario = null;

      $aTurno["MD"] = "Madrugada";
      $aTurno["M"] = "Manana";
      $aTurno["T"] = "Tarde";
      $aTurno["N"] = "Noche";

      // Si en algun momento ya se registro la programacion
      if (count($turno) > 0) {
        $aHorario['config'] = ['opc' => 2];
        $fechaServerTmp = "";
        //echo "opc 2";
        foreach ($turno as $key => $rowTurno) {
          $disabled = true;
            $sw = true;
            $fechaServer = $rowTurno->fecha_atencion;
            if ($fechaServidor > $fechaServer) {
              $sw = false;
            }
            $date = new \DateTime($rowTurno->fecha_atencion);
            $dia = $date->format('d');

            $validaDiaOtroServicio=false;
            $turno_sala=false;
            $servicioConDiasTurnos="";
            foreach ($turnosServicio as $item) {
              $turno_sala=true;
              if($item->dia_atencion==$dia && $item->turno==$rowTurno->turno){
                $validaDiaOtroServicio=true;
                $servicioConDiasTurnos=$item->servicio;
              }
            }
   
          if($fechaServer == $fechaServerTmp) {
              $checked="";
              $servicioDiasTurnos="";
              if ($id_establecimiento == '76') {
                if($turno_sala==true &&  $sw==true){
                  $disabled = $validaDiaOtroServicio;
                  $checked=$validaDiaOtroServicio ? true : false;
                  $servicioDiasTurnos=$servicioConDiasTurnos;
                }else{
                  $disabled = $sw == true?false:true;
                  $checked=$rowTurno->estado == 1 ? true : false;
                  $servicioDiasTurnos="";
                }
              }else if ($id_establecimiento == '77') {
                
                  $disabled = $sw == true?false:true;
                  $checked=$rowTurno->estado == 1 ? true : false;
                  $servicioDiasTurnos="";
                
              }else{
                if($turno_sala==true &&  $sw==true){
                  $disabled = $validaDiaOtroServicio;
                }else{
                  $disabled = $sw == true?false:true;
                }
              }
              //sssssssssssssssss
              $aHorario[$rowTurno->fecha_atencion]['turno'][$rowTurno->turno] = [
                'checkbox' => [
                  'name' => 'turno' . $aTurno[$rowTurno->turno] . '[' . $fechaServer . ']',
                  'value' => $rowTurno->turno,
                  'disabled' => $disabled,
                  'checked' => $checked,
                  'class' => $rowTurno->turno,
                  'servicioConDiasTurnos'=>$servicioDiasTurnos,

                  
                ],
                'hidden' => [
                  'name' => 'id' . $aTurno[$rowTurno->turno] . '[' . $fechaServer . ']',
                  'value' => $rowTurno->id
                ]
              ]; 
          } else {
              $disabled =  $sw == true?false:true;
              $aHorario[$rowTurno->fecha_atencion]['fecha'] = App\Helpers\Util::FormatoFechaATexto($rowTurno->fecha_atencion);
              $aHorario[$rowTurno->fecha_atencion]['input_fecha'] = true;
              $aHorario[$rowTurno->fecha_atencion]['turno']['MD'] = [
                'checkbox' => [
                  'name' => 'turnoMadrugada[' . $fechaServer . ']',
                  'value' => 'MD',
                  'disabled' => $disabled,
                  'checked' => $rowTurno->estado == 1 ? true : false,
                  'class' => 'MD',
                  'servicioConDiasTurnos'=>'',
                ],
                'hidden' => [
                  'name' => 'idMadrugada[' . $fechaServer . ']',
                  'value' => $rowTurno->id
                ]
              ];
          }
          $fechaServerTmp = $fechaServer;
        }
      } else {
        $aHorario['config'] = ['opc' => 1];
        $sw = 1;
        $tarde['T'] = 'T';
        //echo "opc 1";
        for ($i = 1; $i <= $diaFin; $i++) {
          $dia = str_pad((int) $i, 2, '0', STR_PAD_LEFT);
          $fechaServer = $ano . '-' . $mes . '-' . $dia;
          $disabled = true;
          $sw = true;
          if ($fechaServidor > $fechaServer) {
            $sw = false;
          }
          $aHorario[$fechaServer]['fecha'] = App\Helpers\Util::FormatoFechaATexto($fechaServer);
          $aHorario[$fechaServer]['input_fecha'] = true;

          foreach ($aTurno as $key => $rowTurno) {
            $validaDiaOtroServicio=false;
            $turno_sala=false;
            $servicioConDiasTurnos="";
            $sw2 = true;
            if ($fechaServidor > $fechaServer) {
              $sw2 = false;
            }
            foreach ($turnosServicio as $item) {
              $turno_sala=true;
              if($item->dia_atencion==$dia && $item->turno==$key){
                $validaDiaOtroServicio=true;
                $servicioConDiasTurnos=$item->servicio;
                $sw2=false;
              }
            }
         
            if ($id_establecimiento == '76') {
              if($turno_sala==true &&  $sw==true){
                $disabled = $validaDiaOtroServicio;
              }else{
                $disabled = $sw == true?false:true;
              }
            }else if ($id_establecimiento == '77') {
              if($turno_sala==true){
                $disabled = $sw2 == true?false:true;
              }else{
                $disabled = $sw == true?false:true;
              }
            } else {
              $disabled = $sw == true?false:true;
            }
            
            $aHorario[$fechaServer]['turno'][$key] = [
              'checkbox' => [
                'name' => 'turno' . $rowTurno . '[' . $fechaServer . ']',
                'value' => $key,
                'disabled' => $disabled,
                'class' => $key,
                'checked' => $validaDiaOtroServicio ? true : false,
                'servicioConDiasTurnos'=>$servicioConDiasTurnos,
              ]
            ];
          }
        }
      }
      return view('frontend.centro_quirurgico.programacion_salas_detalle', compact('aHorario'));
    }
    public function registrar_programacion_salas(Request $request)
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
      $id_user = Auth::user()->id;
      $modelfarma = new Farmacia();
      $model_usuario = new User;
      $farmacias = $modelfarma->GetFarmaByUser($id_user);
      $id_ipress = $farmacias->id_establecimiento;
      $usuarios = $model_usuario->GetById($id_user);
      $id_consultorio = $request->id_consultorio;
      $id_sala = $request->id_sala;
      $fecactu = strtotime(date("Y-m-d"));
      $tipo_atencion_nombre ="CENTRO QUIRURGICO";

      //SI TIENE TURNOS
      if ($request->opc == 1) :
        if (count($request->fecha) > 0) :

          $primera_fecha = "";
          // Obtener la primera fecha para la validacion posterior
          foreach ($request->fecha as $key => $value) :
            $primera_fecha = $request->fecha[$key]; break;
          endforeach;
          
          $cq_salas_turnos = new CqSalasTurnos();
          $cant_turnos = CqSalasTurnos::where('id_establecimiento',$id_ipress)
          ->where('id_tipo_atencion',  $tipo_atencion_nombre)
          ->where('id_sala', $id_sala)
          ->where('estado', 1)
          ->whereRaw('extract(month from fecha_atencion) = extract(month from ?::date)', [$primera_fecha])
          ->whereRaw('extract(year from fecha_atencion) = extract(year from ?::date)', [$primera_fecha])
          ->count();
          
          // Si no cuenta con registros, pasamos a registrar los turnos
          if ($cant_turnos == 0) :
            foreach ($request->fecha as $key => $value) :
              
              // Por cada fecha registramos los 4 turnos (Madrugada, Mañana, Tarde y Noche)
              $rpta = $cq_salas_turnos->registrarCqTurnoXFecha(
                $request->fecha[$key],
                $id_ipress,
                $tipo_atencion_nombre,
                $id_sala,
                $id_consultorio,
                $id_user,
                (isset($request->turnoMadrugada[$key])) ? 1 : 0,
                (isset($request->turnoManana[$key])) ? 1 : 0,
                (isset($request->turnoTarde[$key])) ? 1 : 0,
                (isset($request->turnoNoche[$key])) ? 1 : 0
              );
            endforeach;
          else :
            Session::flash('flash_danger', 'Ya cuenta con programación registrada.');
            return redirect('/programacion_salas_qx');
          endif;

        endif;
      //SI NO TIENE TURNOS
      else :
        if (count($request->fecha) > 0) :
          foreach ($request->fecha as $key => $value) :
            $fecselec = strtotime($request->fecha[$key]);
            if ($fecselec >= $fecactu) {
             
              $sala_turno = CqSalasTurnos::where('id', '=', $request->idMadrugada[$key])->first();
              $sala_turno->fecha_atencion = $request->fecha[$key];
              $sala_turno->turno = "MD";
              $sala_turno->id_tipo_atencion = $tipo_atencion_nombre;
              $sala_turno->estado = (isset($request->turnoMadrugada[$key])) ? 1 : 0;
              $sala_turno->id_establecimiento = $id_ipress;
              $sala_turno->id_sala = $id_sala;
              $sala_turno->id_consultorio = $id_consultorio;
              $sala_turno->id_user_update = $id_user;
              $sala_turno->id_turno = 1;
              $sala_turno->updated_at = date('Y-m-d H:i:s');
              $sala_turno->save();

              $sala_turno = CqSalasTurnos::where('id', '=', $request->idManana[$key])->first();
              $sala_turno->fecha_atencion = $request->fecha[$key];
              $sala_turno->turno = "M";
              $sala_turno->id_tipo_atencion = $tipo_atencion_nombre;
              $sala_turno->estado = (isset($request->turnoManana[$key])) ? 1 : 0;
              $sala_turno->id_establecimiento = $id_ipress;
              $sala_turno->id_sala = $id_sala;
              $sala_turno->id_consultorio = $id_consultorio;
              $sala_turno->id_user_update = $id_user;
              $sala_turno->id_turno = 2;
              $sala_turno->updated_at = date('Y-m-d H:i:s');
              $sala_turno->save();

              $sala_turno = CqSalasTurnos::where('id', '=', $request->idTarde[$key])->first();
              $sala_turno->fecha_atencion = $request->fecha[$key];
              $sala_turno->turno = "T";
              $sala_turno->id_tipo_atencion = $tipo_atencion_nombre;
              $sala_turno->estado = (isset($request->turnoTarde[$key])) ? 1 : 0;
              $sala_turno->id_establecimiento = $id_ipress;
              $sala_turno->id_sala = $id_sala;
              $sala_turno->id_consultorio = $id_consultorio;
              $sala_turno->id_user_update = $id_user;
              $sala_turno->id_turno = 3;
              $sala_turno->updated_at = date('Y-m-d H:i:s');
              $sala_turno->save();

              $sala_turno = CqSalasTurnos::where('id', '=', $request->idNoche[$key])->first();
              $sala_turno->fecha_atencion = $request->fecha[$key];
              $sala_turno->turno = "N";
              $sala_turno->id_tipo_atencion = $tipo_atencion_nombre;
              $sala_turno->estado = (isset($request->turnoNoche[$key])) ? 1 : 0;
              $sala_turno->id_establecimiento = $id_ipress;
              $sala_turno->id_sala = $id_sala;
              $sala_turno->id_consultorio = $id_consultorio;
              $sala_turno->id_user_update = $id_user;
              $sala_turno->id_turno = 4;
              $sala_turno->updated_at = date('Y-m-d H:i:s');
              $sala_turno->save();
            }
          endforeach;
        endif;
      endif;

      Session::flash('flash_message', 'Actualización exitosa');
      return redirect('/programacion_salas_qx');
    }
    public function listarTurnosConFechaDistinct($lista_turnos) {
      $lista_return = array();
      for ($i=0; $i < count($lista_turnos); $i++) {
        if ($i == 0): $lista_return[] = $lista_turnos[$i]; endif;
        for ($k=0; $k < count($lista_return); $k++) {
          if ($lista_return[$k]->fecha_atencion == $lista_turnos[$i]->fecha_atencion
              && $lista_return[$k]->turno == $lista_turnos[$i]->turno) {
            break;
          } else {
            if ($k+1 == count($lista_return)) {
              $lista_return[] = $lista_turnos[$i];
              break;
            }
          }
        }
      }
      return $lista_return;
    }

    public function validar_paciente_con_intervencion_quirurgica($tipo_doc_asegurado, $numero_doc_asegurado, $id_establecimiento, $id_servicio) {
      $model_intervencion_quirurgica = new App\Models\CqxIntervencion();
      $response = $model_intervencion_quirurgica->ValidarTieneSolicitudIntervencionQuirurgica($tipo_doc_asegurado, $numero_doc_asegurado, $id_establecimiento, $id_servicio);
      return json_encode($response);
    }
  
    public function showCqanestesia($id_prestacion_pre, $id_intervencion)
    {
  
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
      $model_siteds = new \App\Models\Siteds;
      $model_prestacion = new \App\Models\Prestaciones;
  
      //**** */
      $model_intervencion = new \App\Models\CqxIntervencion();
      $row_anestesia_quirurgica = $model_intervencion::select(
        'cqx_intervenciones.id_prestacion',
        'cqx_intervenciones.id_hospitalizacion',
        'cs.nombre as sala',
        'cqx_intervenciones.id_consultorio',
        'cqx_intervenciones.id_tipo_anestesia',
        'a.id as id_asegurado',
        'a.cip',
        'a.fecha_nac',
        'a.grado',
        'a.situacion_paciente',
        'a.sexo',
        'a.domicilio',
        'a.telefono',
        'a.telefono2',
        'a.nro_doc_ident',
        'a.tipo_doc_ident',
        'a.paterno as asegurado_paterno',
        'a.materno as asegurado_materno',
        'a.nombre as asegurado_nombre',
        'a.tipo_doc_titular',
        'a.dni_titular',
        'a.nombre_titular',
        'a.materno_titular',
        'a.paterno_titular',
        DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(a.fecha_nac, 'DD/MM/YYYY'))) AS edadafiliado"),
        'ts.descripcion_corta as tipo_sangre',
        'es_nuevo_admision_hospitalizacion',
        'es_traslado_hospitalizacion_acqx',
        'cqx_intervenciones.id_consultorio as id_servicio',
        'h.id_establecimiento',
        'e.nombre as establecimiento',
        'h.nro_historia',
        'h.created_at',
        'tp.descripcion as tipo_parentesco',
        'aq.id as id_anestesia_quirurgica',
        'aq.id_prestacion as id_prestacion_anestesio',
        'cqx_intervenciones.emergencia_duracion_cirugia',
        'cqx_intervenciones.id_sala_intervencion',
        'aq.tiene_observacion',
        'aq.fecha_intervencion',
        'cqx_intervenciones.fecha_intervencion as fecha_intervencion_validacion',
        'aq.tiene_intervencion',
        'aq.duracion_cirugia',
        'aq.id_sala_intervencion as id_sala_intervencion_anestesio',
        'cqx_intervenciones.id_evolucion'
        )
      ->leftJoin('anestesia_quirurgicas as aq', 'cqx_intervenciones.id', '=', 'aq.id_intervencion')
      ->leftJoin('cq_salas as cs', 'cqx_intervenciones.id_sala_intervencion', '=', 'cs.id')
      ->leftJoin('asegurados as a', 'cqx_intervenciones.id_asegurado', '=', 'a.id')
      ->leftJoin('tipo_sangres as ts', 'a.id_tipo_sangre', '=', 'ts.id')
      ->leftJoin('hospitalizaciones as h', 'cqx_intervenciones.id_hospitalizacion', '=', 'h.id')
      ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
      ->leftJoin('tipo_parentescos as tp', 'tp.id', '=', 'h.id_tipo_acompaniante')
      ->where('cqx_intervenciones.id',$id_intervencion)->first();
      
      if (!empty($row_anestesia_quirurgica) && !empty($row_anestesia_quirurgica->id_prestacion_anestesio)) {
        $id_prestacion = $row_anestesia_quirurgica->id_prestacion_anestesio;
      } else {
        $id_prestacion = $id_prestacion_pre;
      }
      //$prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion_pre)->first();
     
        // $row_anestesia_quirurgica = DB::table('anestesia_quirurgicas')
        //                           ->where('anestesia_quirurgicas.id_prestacion_pre','=',$id_prestacion_pre)
        //                           ->select('anestesia_quirurgicas.*')
        //                           ->first();

        // if (isset($row_anestesia_quirurgica) && !is_null($row_anestesia_quirurgica)) {
        //   $id_prestacion = $row_anestesia_quirurgica->id_prestacion;
        // } else {
        //   $id_prestacion = $id_prestacion_pre;
        // }
      //$id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
     
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $referencia = !empty($prestacion->referido)?$prestacion->referido:'';
      // switch ($id_tipo_atencion) {
      //     case '1': //CONSULTA EXTERNA
      //         $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      //         $referencia = $prestacion->referido;
      //         // if ($referencia == "SI") {
      //         //     $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
      //         //     $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
      //         // }
      //         break;
              
      //     case '2': //EMERGENCIA 
      //         $prestacion = $model_prestacion->GetPrestacionEmergenciaByIdAnestesiologia([$id_prestacion]);
      //         $referencia = $prestacion->id_motivo_ingreso;
      //         //if ($prestacion->id_motivo_ingreso == 4) {
      //             //En emergencia no usa el módulo de referencia
      //             //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
      //             //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
      //         //}
      //         break;
      // }      
      $model_receta = new \App\Models\Receta_vale;
      $model_kardex = new \App\Models\Kardexe;
      $fechaServidor = $model_kardex->getFechaServidor();
  
      $model_farma = new \App\Models\Farmacia;
      $farmacia = $model_farma->GetFarmaByIdfarma(auth()->user()->id_farmacia);
      $farmacias = $model_farma->GetFarmaciaByIdEstablecimiento($farmacia->id_establecimiento);
      /*****************************/
      $id_ipress = $farmacia->id_establecimiento;
      
      $model_establecimiento = new \App\Models\Establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      //$establecimientos = $model_establecimiento->ShowAllEstablecimientos();
      $ipress = $establecimiento[0];

      
      if ($id_ipress == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }
      
      $flag_anestesia_quirurgica = false;
      $flag_observacion = false;

      $id_prestacion_anestesia = 0;
      $id_anestesia_quirurgica = 0;
      $prestacion_anestesia = null;
      $prestacionFirmada = false;
      $procedimientocq = null;
      $diagnosticocq = null;
      $recetacq = null;
      $listaTipoAnestesiacq = null;
      $anetesiologos1 = null;
      $anetesiologos2 = null;
      $anetesiologos = null;
      $ids_adicionales_procedimiento = array();
      $ids_adicionales_diagnostico = array();

      if (!empty($row_anestesia_quirurgica)) {  //Existe atención quirurgica de anestesia
        
        $flag_anestesia_quirurgica = true;
        
        $id_prestacion_anestesia = !empty($row_anestesia_quirurgica->id_prestacion_anestesio)?$row_anestesia_quirurgica->id_prestacion_anestesio:0;
        $id_anestesia_quirurgica = $row_anestesia_quirurgica->id_anestesia_quirurgica;
        if(!empty($row_anestesia_quirurgica->id_prestacion_anestesio)){
          $procedimientocq = $model_prestacion->GetProcedimientosByIdPrestacionydiag(!empty($row_anestesia_quirurgica->id_prestacion_anestesio)?$row_anestesia_quirurgica->id_prestacion_anestesio:$row_anestesia_quirurgica->id_prestacion, 1);
          foreach ($procedimientocq as $p) {
            $ids_adicionales_procedimiento[] = $p->id_procedimiento;
          }
          $diagnosticocq = $model_prestacion->GetDiagnosticoByIdPrestacion(!empty($row_anestesia_quirurgica->id_prestacion_anestesio)?$row_anestesia_quirurgica->id_prestacion_anestesio:$row_anestesia_quirurgica->id_prestacion);
          foreach ($diagnosticocq as $d) {
            $ids_adicionales_diagnostico[] = $d->id_diagnostico;
          }
          $recetacq = $model_receta->GetDatosRecetasByIdPrestacion($row_anestesia_quirurgica->id_prestacion_anestesio);
        }else{
          $diagnosticocq = $model_prestacion->GetDiagnosticoByIdIntervencion($id_intervencion);
         
          if(!empty($row_anestesia_quirurgica->id_evolucion)){
            $procedimientocq = DB::table('prestacion_procedimientos as pp')
            ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            ->join('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
            ->select(
                'pp.id as id_prestacion_procedimiento',
                'pp.id_prestacion',
                'pp.id_evaluacion',
                'p.codigo',
                'p.descripcion as nombre',
                'pp.cantidad',
                'd.codigo as codigo_diagnostico',
                'pp.id_diagnostico',
                'pp.id_procedimiento'
            )
            ->where('pp.id_prestacion', $row_anestesia_quirurgica->id_prestacion)
            ->where('pp.id_evaluacion', $row_anestesia_quirurgica->id_evolucion)
            ->where('pp.id_estado_reg', 1)
            ->where('pp.tipo_procedimiento',1)
            ->orderBy('pp.id')
            ->get();   
          }else{
            $procedimientocq = $model_prestacion->GetProcedimientosByIdPrestacionydiag($row_anestesia_quirurgica->id_prestacion, 1);
          } 
          
          foreach ($procedimientocq as $p) {
            $ids_adicionales_procedimiento[] = $p->id_procedimiento;
          }
        }

        if ($row_anestesia_quirurgica->observacion !== "") {
          $flag_observacion = true;
        }

        $listaTipoAnestesiacq = DB::table('cqx_tipo_anestesia_detalles')
            ->where('id_anestesia', $row_anestesia_quirurgica->id_anestesia_quirurgica)
            ->where('id_estado', 1)
            ->select('id_tipo_anestesia')
            ->get();
        $idsSeleccionados = array_map(function($item) {
            return $item->id_tipo_anestesia;
        }, $listaTipoAnestesiacq);
        $idsSeleccionados = !empty($idsSeleccionados)?$idsSeleccionados:[$row_anestesia_quirurgica->id_tipo_anestesia];

        // $anetesiologos1 = DB::table('cqx_profesionales as p')
        //                     ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
        //                     ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
        //                     ->select('p.id', 'p.estado', 'm.nombre', 'm.paterno', 'm.materno', 'tp.descripcion')
        //                     ->where('p.flag_personal', 1)  // Filtrar por tipo de personal
        //                     ->where('p.id_cqx_intervenciones', $id_intervencion)  // Filtrar por tipo de personal
        //                     ->whereIn('p.estado', [0, 1])  // Filtrar solo los profesionales activos
        //                     ->orderBy('p.id')
        //                     ->get();
        // $anetesiologos2 = DB::table('cqx_profesionales as p')
        //                     ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
        //                     ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
        //                     ->select('p.id', 'p.estado', 'm.nombre', 'm.paterno', 'm.materno', 'tp.descripcion')
        //                     ->where('p.flag_personal', 1)  // Filtrar por tipo de personal
        //                     ->where('p.id_cqx_anestesia', $row_anestesia_quirurgica->id)  // Filtrar por tipo de personal
        //                     ->whereIn('p.estado', [0, 1])  // Filtrar solo los profesionales activos
        //                     ->orderBy('p.id')
        //                     ->get();
        // $anetesiologos = array_merge($anetesiologos1, $anetesiologos2); 
        $anetesiologos = DB::table('cqx_profesionales as p')
            ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
            ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
            ->select('p.id', 'p.estado', 'm.nombre', 'm.paterno', 'm.materno', 'tp.descripcion')
            ->where('p.flag_personal', 1)  // Filtrar por tipo de personal
            ->where('p.id_cqx_intervenciones', $id_intervencion)  // Filtrar por tipo de personal
            ->whereIn('p.estado', [0, 1])  // Filtrar solo los profesionales activos
            ->orderBy('p.id')
            ->get();  
        if(!empty($id_prestacion_anestesia)){
          $prestacion_anestesia = DB::table('prestaciones')
                                  ->where('id','=',$id_prestacion_anestesia)
                                  ->select('prestaciones.*')
                                  ->first();  
        }                       
        if (!empty($prestacion_anestesia) && !is_null($prestacion_anestesia->fecha_firma) && $prestacion_anestesia->fecha_firma !== "") {
          $prestacionFirmada = true;
        }

        // $especialista = DB::table('medicos')
        //                   ->leftjoin('especializaciones','especializaciones.id','=','medicos.id_especializacion')
        //                   ->where('medicos.id','=',!empty($prestacion_anestesia)&& $prestacion_anestesia->id_medico?)
        //                   ->select('medicos.dni','especializaciones.nombre as profesion','medicos.paterno as medico_paterno','medicos.materno as medico_materno','medicos.nombre as medico_nombre')
        //                   ->first();            
      } 

      $anetesiologos = DB::table('cqx_profesionales as p')
                  ->leftJoin('cqx_tipo_professionales as tp', 'p.id_tipo_professional', '=', 'tp.id')
                  ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id')
                  ->select('p.id', 'p.estado', 'm.nombre', 'm.paterno', 'm.materno', 'tp.descripcion')
                  ->where('p.flag_personal', 1)  // Filtrar por tipo de personal
                  ->where('p.id_cqx_intervenciones', $id_intervencion)  // Filtrar por tipo de personal
                  ->whereIn('p.estado', [0, 1])  // Filtrar solo los profesionales activos
                  ->orderBy('p.id')
                  ->get();
      
      $especialista = DB::table('medicos')
                    ->leftjoin('especializaciones','especializaciones.id','=','medicos.id_especializacion')
                    ->where('medicos.dni','=',auth()->user()->dni)
                    ->select('medicos.dni','especializaciones.nombre as profesion','medicos.paterno as medico_paterno','medicos.materno as medico_materno','medicos.nombre as medico_nombre')
                    ->first();

      $tipo_anestesia = DB::table('cqx_tipo_anestesia')
                            ->select()
                            ->get();
      $salas = DB::table('cq_salas')
              ->select()
              ->get();

      $id_prestacion_cq = "";

      $model_tipo_profesionalInterviene = new \App\Models\CqxTipoProfesional;
      $tipo_profesional_interviene_anestesiologo = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional',2)->get();

      $via_administracion = DB::table('via_administracion')
                            ->select()
                            ->get(); 

      $model_intervencion = new \App\Models\CqxIntervencion();
      $datos_intervencion = $model_intervencion::select(
        'cqx_intervenciones.id_prestacion',
        'cqx_intervenciones.id_hospitalizacion',
        'cs.nombre as sala',
        'cqx_intervenciones.id_consultorio')
      ->leftJoin('cq_salas as cs', 'cqx_intervenciones.id_sala_intervencion', '=', 'cs.id')
      ->where('cqx_intervenciones.id',$id_intervencion)->first();

      //$medicamentos =[];
      $hospitalizacion="";
      $datos_servicio=null;
      if(!empty($prestacion->id_tipo_atencion) && $prestacion->id_tipo_atencion==2){//SI VIENE DE EMRGENCIA
        $datos_servicio = DB::table('hospitalizacion_ordenes as ho')
        ->join('prestaciones as p', 'ho.id_ext', '=', 'p.id_ext')
        ->join('upsses as u', 'ho.id_upss_traslado', '=', 'u.id')
        ->join('consultorios as c', 'u.id_consultorio', '=', 'c.id')
        ->select(
            'c.id as id_servicio',
            'u.descripcion_upss',
            'u.codigo',
            'c.nombre as sericio',
            'ho.id_upss_responsable',
            'ho.id_upss_traslado',
            'ho.id_sala',
            'ho.id_establecimiento',
            'ho.fecha_derivacion'
        )
        ->where('p.id',$id_prestacion_pre)
        ->first();
        $id_servicio=isset($datos_servicio->id_servicio)?$datos_servicio->id_servicio:0;
        $id_hospitalizacion=null;
        
      }else if(!empty($prestacion->id_tipo_atencion) &&  $prestacion->id_tipo_atencion==1){//SI VIENE AMBULATORIO
        $hospitalizacion = \App\Models\HospitalizacionOrden::where('id_ext', $id_intervencion)->exists();
        if (!$hospitalizacion) {
             return response()->json([
                'success' => false,
                'message' => 'No existe una orden de hospitalización porque aún no se ha generado la atención de programación correspondiente.'
            ], 422);
        } 

        $datos_servicio = DB::table('hospitalizacion_ordenes as ho')
        ->join('upsses as u', 'ho.id_upss_traslado', '=', 'u.id')
        ->join('consultorios as c', 'u.id_consultorio', '=', 'c.id')
        ->select(
            'c.id as id_servicio',
            'u.descripcion_upss',
            'u.codigo',
            'c.nombre as sericio',
            'ho.id_upss_responsable',
            'ho.id_upss_traslado',
            'ho.id_sala',
            'ho.id_establecimiento',
            'ho.fecha_derivacion'
        )
        ->where('ho.id_ext',$id_intervencion)
        ->first();
        $id_servicio=isset($datos_servicio->id_servicio)?$datos_servicio->id_servicio:0;
        $id_hospitalizacion=null;
      }else{
        if(!$datos_intervencion->es_nuevo_admision_hospitalizacion){
          $hospitalizacion = DB::table('hospitalizaciones as h')
          ->leftJoin('cqx_intervenciones as ci', 'ci.id_hospitalizacion', '=', 'h.id')
          ->select('h.id','h.id_servicio_destino','h.id_servicio_encargado')
          ->where('ci.id', $id_intervencion)->first();
        }
        $id_servicio=isset($hospitalizacion->id_servicio_cargo)?$hospitalizacion->id_servicio_cargo:$datos_intervencion->id_consultorio;
        $id_hospitalizacion = !empty($datos_intervencion->id_hospitalizacion)?$datos_intervencion->id_hospitalizacion:0;

      }
      $model_consultorio = new Consultorio();
      $servicio = $model_consultorio->GetNombreById($id_servicio);
      $codigo_upss = $model_consultorio->ShowCodigoUpssIdTipoAtencion($id_servicio,3);


      $model_medicos = new Medico;
      $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
      $puede_atennder_servicios_cq = DB::table('medico_consultorios as mc')
                            ->join('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
                            ->where('mc.id_establecimiento', $id_ipress)
                            ->where('mc.id_medico', $medico->id)
                            ->where('mc.estado', 1)
                            ->where('c.id',72)//SOLO EL SERVICIO DE ANESTESIOLOGIA
                            ->exists();

      // $intervencion = DB::table('cqx_intervenciones')
      //                           ->where('id',$id_intervencion)
      //                           ->select()
      //                           ->first();

      $id_atencion = $id_hospitalizacion;
      //$id_tipo_atencion=3;//ID DEL TIPO DE ATENCION
      $tipo_atencion_ultima="";
      $codigo_siteds="";
      $model_intraoperatorio = new \App\Models\IntraOperatorio;
      if(!empty($id_atencion)){
        $tipo_atencion_ultima = $model_intraoperatorio->getUltimaAtencionXAtencion($id_atencion, 3);  
      }
      if(!empty($tipo_atencion_ultima)){
        $codigo_siteds = $model_siteds->getUltimoCodigoSitedsEnElDia($id_atencion, 3, $tipo_atencion_ultima->tipo_atencion_ultima);
      }
      $diagnosticos_pre="";
      if(!empty($prestacion) && $prestacion->id_tipo_atencion==1 || $datos_intervencion->es_nuevo_admision_hospitalizacion){
        $id_tipo_atencion = 1;
        $diagnosticos_pre = DB::table('receta_diagnosticos as rd')
        ->join('prestaciones as p', 'rd.id_prestacion', '=', 'p.id')
        ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
        ->join('tipo_diagnosticos as td', 'rd.id_tipo_diagnostico', '=', 'td.id')
        ->join('cqx_citas as cc', function ($join) {
            $join->on('p.id', '=', 'cc.id_prestacion')
                ->on('cc.tipo', '=', DB::raw(2));
        })
        ->where('cc.id_cqx_intervenciones', $id_intervencion)
        ->select('d.codigo', 'd.nombre', 'td.descripcion')
        ->get();
      }else if(!empty($prestacion) && $prestacion->id_tipo_atencion==2){
        $id_tipo_atencion = 2;
        $diagnosticos_pre = DB::table('receta_diagnosticos as rd')
          ->join('prestaciones as p', 'rd.id_prestacion', '=', 'p.id')
          ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
          ->join('tipo_diagnosticos as td', 'rd.id_tipo_diagnostico', '=', 'td.id')
          ->where('rd.id_intervencion', $id_intervencion)
          ->whereNull('rd.id_receta_vale')
          ->select('d.codigo', 'd.nombre', 'td.descripcion')
          ->get();
      }else{
        $id_tipo_atencion = 3;
      }

      $diagnosticos_post = DB::table('receta_diagnosticos as rd')
        ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
        ->join('tipo_diagnosticos as td', 'rd.id_tipo_diagnostico', '=', 'td.id')
        ->join('cqx_atenciones as ca', 'rd.id_prestacion', '=', 'ca.id_prestacion')
        ->where('rd.id_intervencion', $id_intervencion)
        ->where('ca.id_cqx_intervencion', $id_intervencion)
        ->select('d.codigo', 'd.nombre', 'td.descripcion')
        ->get();
      $model_intraoperatorio = new \App\Models\IntraOperatorio;
      $procedimientos_intervencion = $model_intraoperatorio->procedimientos_intervencion($id_intervencion);
      $intervencion= $datos_intervencion;
      return view('frontend.centro_quirurgico.anestesiologia.form_anestesiologia', compact('prestacion','id_tipo_atencion','referencia','id_cita','fechaServidor','farmacias','nivel','id_prestacion','id_prestacion_cq','flag_anestesia_quirurgica','procedimientocq','diagnosticocq','recetacq','anetesiologos','tipo_profesional_interviene_anestesiologo','id_prestacion_anestesia','id_anestesia_quirurgica','tipo_anestesia','salas','row_anestesia_quirurgica','flag_observacion','via_administracion','id_intervencion','listaTipoAnestesiacq','especialista','prestacion_anestesia','prestacionFirmada','ids_adicionales_diagnostico','ids_adicionales_procedimiento','medicamentos','puede_atennder_servicios_cq','id_servicio','servicio','datos_servicio','intervencion','codigo_siteds','ipress','procedimientos_intervencion','diagnosticos_pre','diagnosticos_post','hospitalizacion','medico','tiene_intervencion','idsSeleccionados'));
    }
    
    public function storeOrUpdateCqanestesia(Request $request)
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      DB::beginTransaction();
      try {
          $firma_receta_existente=0;
          $recetas = $request->input('firma_receta_existente_anestesio');
          if (isset($recetas)):
            foreach ($recetas as $key => $valorFirma) {
              if ($valorFirma == "1") {
                $firma_receta_existente=$key;
              }
            }
          endif;
          $id_receta=0;

          $id_prestacion_anestesia=$request->id_prestacion_anestesia;
          $id_prestacion=$request->id_prestacion;
          $prestacion_unico = DB::table('prestaciones as pre')->where('pre.flag_intervencion_quirurgica','=',true)->where('pre.id_intervencion','=',$request->id_intervencion)
                                   ->select('pre.*')->first(); 
          $id_prestacion = !empty($prestacion_unico)  ? $prestacion_unico->id  : $request->id_prestacion ; 
          
          if ($id_prestacion_anestesia=="0") {
            //dd("Entro en condición");
            $model_prestacion = new \App\Models\CqxAnestesia;
        
            $p_diagnostico = "";
            $p_diagnostico .= "{";
            if (isset($request->id_diagnostico_adicional_anestesia)) :
              foreach ($request->id_diagnostico_adicional_anestesia as $key => $value) :
                if ($request->id_diagnostico_adicional_anestesia[$key] != "") {
                  $p_diagnostico .= "{";
                  $p_diagnostico .= $request->id_diagnostico_adicional_anestesia[$key] . ",";
                  $p_diagnostico .= (isset($request->id_tipo_diagnostico_adicional_anestesia[$key]) && $request->id_tipo_diagnostico_adicional_anestesia[$key] > 0) ? $request->id_tipo_diagnostico_adicional_anestesia[$key] : "0";
                  $p_diagnostico .= "},";
                }
              endforeach;
              if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
            endif;
            $p_diagnostico .= "}";
        
            $p_procedimiento = "";
            $p_procedimiento .= "{";
            if (isset($request->id_procedimiento_adicional_anestesia)) :
              foreach ($request->id_procedimiento_adicional_anestesia as $key => $value) :
                if ($request->id_procedimiento_adicional_anestesia[$key] != "") {
                  $p_procedimiento .= "{";
                  $p_procedimiento .= $request->id_procedimiento_adicional_anestesia[$key] . ",";
                  $p_procedimiento .= (isset($request->procedimiento_cantidad_adicional_anestesia[$key]) && $request->procedimiento_cantidad_adicional_anestesia[$key] > 0) ? $request->procedimiento_cantidad_adicional_anestesia[$key] : "0";
                  $p_procedimiento .= "},";
                }
              endforeach;
              if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
            endif;
            $p_procedimiento .= "}";
            //$id_prestacion = 0;
            $fecha_alta = "";
            $id_motivo_alta = 0;
            $id_estado_reg = 1;
            $observacion = "";
            $id_receta_vale = 0;
            $id_anestesiologo = 0;
            $obs_registro = "";
            $id_receta_vale_bus = 0;
            $id_admision = 0;
            $tipo_via = "";
            $sub_via = "";
            $final_via = "";
            $nro_guia = "";
            $interconsulta = "";
            $interconsulta_dias = 0;
            $nro_receta = "";
            $digitos_adicionales = "";
            $id_tipo_num = 0;
            $id_servicio_derivar = (isset($request->id_servicio_derivar)) ? $request->id_servicio_derivar : 0;
            $fecha_expedicion =  (isset($request->fecha_expedicion)) ? $request->fecha_expedicion : "";
            $anamnesis = (isset($request->anamnesis)) ? $request->anamnesis : "";
            $examen_fisico = (isset($request->examen_fisico)) ? $request->examen_fisico : "";
            $aspectos_generales = (isset($request->aspectos_generales)) ? $request->aspectos_generales : "";
            $estado_receta = 2;
            $condicion = (isset($request->condicion)) ? $request->condicion : 0;
            $cantidadReceta = (isset($request->cantidadReceta) && $request->cantidadReceta > 0) ? $request->cantidadReceta : 1;
            $id_asegurado   = $request->id_asegurado;
            $receta_prescrita        =  0;
            if($request->id_tipo_atencion==1){
              $id_cita= $request->id_cita;
              $id_emergencia = 0;
              $id_hospitalizacion = 0;
            }
            else
            {
              $request->id_modalidad = 1;
              if($request->id_tipo_atencion==2){
                $id_emergencia = $request->id_cita;
                $id_hospitalizacion = 0;
                $id_cita= 0;
              }
              else
              {
                $id_hospitalizacion = $request->id_cita;
                $id_cita= 0; 
                $id_emergencia = 0;
              }
            }
            $p_producto = "";
            $p_producto .= "{";
            if (isset($request->id_producto_adicional_nuevo)) :
              $receta_prescrita=1;
              foreach ($request->id_producto_adicional_nuevo as $key => $value) :
                if ($request->id_producto_adicional_nuevo[$key] != "") {
                  $p_producto .= "{";
                  $p_producto .= $request->id_producto_adicional_nuevo[$key] . ",";
                  $p_producto .= $request->tiempo_periodo_adicional_nuevo[$key] . ",";
                  $p_producto .= $request->nro_med_solictados_anestesia_adicional_nuevo[$key] . ",";
                  $p_producto .= (isset($request->nro_med_entregados_adicional_nuevo[$key]) && $request->nro_med_entregados_adicional_nuevo[$key] != "") ? $request->nro_med_entregados_adicional_nuevo[$key] : 0;
                  $p_producto .= ",";
                  $p_producto .= (isset($request->nro_stocks_almacen_adicional_nuevo[$key]) && $request->nro_stocks_almacen_adicional_nuevo[$key] != "") ? $request->nro_stocks_almacen_adicional_nuevo[$key] : 0;
                  $p_producto .= ",";
                  $p_producto .= (isset($request->nro_stocks_establecimiento_adicional_nuevo[$key]) && $request->nro_stocks_establecimiento_adicional_nuevo[$key] != "") ? $request->nro_stocks_establecimiento_adicional_nuevo[$key] : 0;
                  $p_producto .= ",";
                  $p_producto .= (isset($request->nro_stocks_adicional_nuevo[$key]) && $request->nro_stocks_adicional_nuevo[$key] != "") ? $request->nro_stocks_adicional_nuevo[$key] : 0;
                  $p_producto .= ",";
                  $p_producto .= (isset($request->posologiaAdicional_nuevo[$key]) && $request->posologiaAdicional_nuevo[$key] != "") ? str_replace(",", "###", $request->posologiaAdicional_nuevo[$key]) : "-";
                  $p_producto .= ",";
                  $p_producto .= (isset($request->miSelectProdAdicionalAnestesia_nuevo[$key]) && $request->miSelectProdAdicionalAnestesia_nuevo[$key] != "") ? $request->miSelectProdAdicionalAnestesia_nuevo[$key] : 0;
                  $p_producto .= ",";
                  $p_producto .=(isset($request->id_via_administracion_anestesia_adicional_nuevo[$key]) && $request->id_via_administracion_anestesia_adicional_nuevo[$key] != "") ? $request->id_via_administracion_anestesia_adicional_nuevo[$key] : 0;
                  $p_producto .= "},";
                }
              endforeach;
              if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
            endif;
            $p_producto .= "}";
        
            $ipress_bene    = $request->ipress_bene;
            $upss_bene        = $request->upss_bene;
            $id_farmacia_receta        =  ($request->id_farmacia!="") ? $request->id_farmacia : 0;
            if (Auth::user()->tipo_registro == 3) $id_receta_vale_bus = 1; 
        
            //$id_sub_consultorio=0;
        
            // if($request->id_modalidad==2){
            //   $medico_consultorio = new Medico_consultorio();
            //   $sub_consultorio_medico = $medico_consultorio->GetSubConsultorioByIdEstablecimientoAndIdConsultorio( $request->id_establecimiento, $request->id_servicio, $request->id_tipo_atencion);  
            //   $id_sub_consultorio =$sub_consultorio_medico[0]->id;
            // }
            // else
            // {
        
        
            //   if($request->id_tipo_atencion==2){
            //     $id_sub_consultorio=545;
            //   }
            //   else{
            //     if($request->id_tipo_atencion==3){
            //       $id_sub_consultorio=551;
            //     }
            //     else{
            //       $id_sub_consultorio=$request->id_sub_consultorio;
            //     }
            //   }
            
            // }
            
            $model_cita = new \App\Models\Cita;
            $model_kardex = new Kardexe();
            $id_cita = 0;
            $fecha_cita = $model_kardex->getFechaServidor();
            $correo = "";$domicilio = "";$telefono = ""; $telefono2 = ""; $tipo_cita = $request->id_modalidad; $adicional = 1 ;
            
            $model_medico = new \App\Models\Medico;
            $id_medico = $model_medico->GetMedicoByDNI( auth()->user()->dni );

            $resultado = DB::table('sub_consultorios')
            ->where('id_consultorio', $request->id_servicio)
            ->where('id_establecimiento', Auth::user()->id_establecimiento)
            ->where('id_estado_reg', 1)
            ->orderBy('id', 'asc')
            ->value('id');
            
            $id_cita = $model_cita->registrarCita($id_cita, $request->id_establecimiento, $resultado, $id_medico->id, $request->id_user, $fecha_cita, $request->tipo_doc_ident, $request->dni_beneficiario_h, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $request->nro_historia, $request->grado, $request->fecnacafiliado, $request->nomsexo, $request->cip_beneficiario, $request->tipodoc_titular, $request->nrodoctitular, $request->nombre_titular, $request->paterno_titular, $request->materno_titular, NULL, 1, 1, $correo,$domicilio,$telefono,$telefono2,$tipo_cita,2,'NO',0,'','','','','','','','');
            
            $cita = $model_cita->GetCitasByIdCita($id_cita);
            $id_asegurado = $cita->id_asegurado;
            //1=si registra cita
            //0=no registra cita
            $registrar_cita = $request->registrar_cita == "false" ? 1 : 0;    
        
            // $model_consultorio = new \App\Models\Consultorio;
            // $codupss = $model_consultorio->ShowCodigoUpssIdTipoAtencion(72,1);
        
            // foreach ($codupss as $row) {
            //   if ($row->codigo == "040000") {
            //     $codigo_upss = $row->codigo;
            //   }
            // }

            //1ra  opcion: $codigo_upss=040000
            $codigo_upss="040000";//SOLO PARA ANESTESIOLOGIA -> CENTRO QUIRÚRGICO Y ANESTESIOLOGÍA
            $fechahora = Carbon::parse($request->fecha_hora_atencion)->format('Y-m-d H:i:s');
            $id_prestacion_update = $model_prestacion->registrarPrestacionAnestesiologia(
                              $id_cita,
                              $id_asegurado,
                              3,
                              $request->id_establecimiento,
                              72,
                              $fechahora,
                              empty($fecha_alta) ? null : $fecha_alta,
                              $id_medico->id,
                              $id_motivo_alta,
                              $id_estado_reg,
                              $observacion,
                              $id_receta_vale,
                              $id_farmacia_receta,
                              $request->id_user,
                              $nro_receta,
                              empty($digitos_adicionales) ? null : $digitos_adicionales,
                              $id_anestesiologo == 0 ? null : $id_anestesiologo,
                              empty($obs_registro) ? null : $obs_registro,
                              $id_receta_vale_bus == 0 ? null : $id_receta_vale_bus,
                              $id_admision == 0 ? null : $id_admision,
                              empty($tipo_via) ? null : $tipo_via,
                              empty($sub_via) ? null : $sub_via,
                              empty($final_via) ? null : $final_via,
                              empty($nro_guia) ? null : $nro_guia,
                              empty($interconsulta) ? null : $interconsulta,
                              empty($interconsulta_dias) ? null : $interconsulta_dias,
                              empty($fecha_expedicion) ? null : $fecha_expedicion,
                              $id_servicio_derivar,
                              $id_tipo_num,
                              $anamnesis,
                              $examen_fisico,
                              $aspectos_generales,
                              $estado_receta,
                              $condicion,
                              $cantidadReceta,
                              empty($p_procedimiento) ? null : $p_procedimiento,
                              empty($p_producto) ? null : $p_producto,
                              empty($p_diagnostico) ? null : $p_diagnostico,
                              $ipress_bene,
                              $upss_bene,
                              $receta_prescrita,
                              $request->id_modalidad,
                              $request->nomsexo,
                              $request->fecnacafiliado,
                              $codigo_upss,
                              $adicional,
                              $registrar_cita,
                              ( !empty($prestacion_unico)  ? $id_prestacion : 0), // id_prestacion , verifica IdUnico de prestacion quirurgica
                              0  // flag_ref_cref
                          );
            // ANESTESIA QUIRUGICA
            
            DB::table('anestesia_quirurgicas')->insert([
                'cod_siteds_susalud'=>!empty($request->cod_siteds_susalud)?$request->cod_siteds_susalud:null,
                'id_prestacion' => $id_prestacion,
                'id_asegurado' => $id_asegurado,
                'id_establecimiento' => $request->id_establecimiento,
                'id_servicio' => $request->id_servicio,
                'id_prestacion_pre' => $request->id_prestacion,
                'id_estado_reg' => 1,
                'fecha_intervencion' => !empty($request->fecha_intervencion) ? $request->fecha_intervencion : null,
                'hora_inicio_intervencion' => !empty($request->hora_inicio_intervencion) ? $request->hora_inicio_intervencion : null,
                'hora_fin_intervencion' => !empty($request->hora_fin_intervencion) ? $request->hora_fin_intervencion : null,
                'id_sala_intervencion' => !empty($request->id_sala) ? $request->id_sala : null,
                //'id_tipo_anestesia' => !empty($request->id_tipo_anestesia) ? $request->id_tipo_anestesia : null,
                'observacion' => !empty($request->observacion) ? $request->observacion : null,
                'id_intervencion' => !empty($request->id_intervencion) ? $request->id_intervencion : null,
                'tiene_intervencion' => !empty($request->tiene_intervencion) && $request->tiene_intervencion == 1 ? true : false,
                'tiene_observacion' => !empty($request->tiene_observacion) && $request->tiene_observacion == 1 ? true : false,
                'created_us' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'duracion_cirugia' =>!empty($request->duracion_cirugia)?$request->duracion_cirugia:0
            ]);

            $anestesia_quirurgica = DB::table('anestesia_quirurgicas')->where('anestesia_quirurgicas.id_prestacion', '=', $id_prestacion)->select('anestesia_quirurgicas.*')->first();

            //==================== Tipo de Anestesia ========================
            if (!is_null($request->id_tipo_anestesia)) {
                foreach ($request->id_tipo_anestesia as $key => $value) {
                  DB::table('cqx_tipo_anestesia_detalles')->insert([
                    'id_anestesia' => $anestesia_quirurgica->id,
                    'id_tipo_anestesia' => $request->id_tipo_anestesia[$key],
                    'id_estado' => 1,
                    'created_us' => auth()->user()->id,
                    'created_at' => Carbon::now()
                  ]);
                }
            }


            if (isset($request->anestesiologosEliminados)) {
              foreach ($request->anestesiologosEliminados as $ae) {
                $rowaffect2 = DB::table('cqx_profesionales')
                ->where('cqx_profesionales.id', '=', $ae)
                ->update([
                'estado' => 3,
                'deleted_us' => auth()->user()->id,
                'deleted_at' => Carbon::now()
                ]);
                
                Log::info('Eliminar en cqx_profesionales', ['rowaffect2' => $rowaffect2]);
              }
            }

            if (isset($request->id_anestesiologo_anestesia)) {
              foreach ($request->id_anestesiologo_anestesia as $key=> $value) {
                  DB::table('cqx_profesionales')->insert([
                    'id_cqx_anestesia' => $anestesia_quirurgica->id,
                    'id_medico' => $value,
                    'id_tipo_professional' => $request->tipo_profesional_anestesiologo_anestesia[$key],
                    'flag_personal' => 1,
                    'flag_validacion' => 0,
                    'estado' => 1,
                    'created_us' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'id_cqx_intervenciones'=>$request->id_intervencion
                  ]);
              }
            }

            //insertar procedimientos x diagnosticos
            if(isset($request->id_procedimiento_adicional_anestesia)):
              if(isset($request->miSelectProdAdicionalAnestesia)):
                $arrayprocedimientos=$request->id_procedimiento_adicional_anestesia;
                $arraydiagnosticos=$request->miSelectProdAdicionalAnestesia;
                for($iterador=0;$iterador<count($arrayprocedimientos);$iterador++){
          
                  $model_procedimiento_metodos = new \App\Models\Procedimiento;
                  $idprocedimiento= $arrayprocedimientos[$iterador];
                
                  $iddiagnostico = $arraydiagnosticos[$iterador];
              
                  $idprestacionxDiagProc = $id_prestacion;
                  $mensajeSucess = $model_procedimiento_metodos->RegistrarProcedimientoxDiagnostico($idprestacionxDiagProc,$idprocedimiento,$iddiagnostico,1);  
                }
        
              endif;
            endif;
        
              /***************CUPS**************************/
                    switch($request->id_tipo_atencion){
                case 1 : $corte_adm = 'CE_00001'; $id_tipo_atencion = 1; break;
                case 2 : $corte_adm = 'EM_00001'; $id_tipo_atencion = 2; break;
                case 3 : $corte_adm = 'HO_00001'; $id_tipo_atencion = 3; break;
                case 4 : $corte_adm = 'CQ_00001'; $id_tipo_atencion = 4; break;
                case 5 : $corte_adm = 'CE_00001'; $id_tipo_atencion = 1; break;
                case 6 : $corte_adm = 'HO_00001'; $id_tipo_atencion = 2; break;
                case 6 : $corte_adm = 'HO_00001'; $id_tipo_atencion = 2; break;
                case 7 : $corte_adm = 'CE_00001'; $id_tipo_atencion = 1; break;
                case 8 : $corte_adm = 'HO_00001'; $id_tipo_atencion = 2; break;
              }
        
              $date = Carbon::now(); 
              $fechaActual = $date->format('d-m-Y');
              $horaActual = $date->format('H:i:s');
            
              $objEstablecimiento= Establecimiento::where('id', '=', Auth::user()->id_establecimiento)->first(); //CODIGO ESTABLECIMIENTO
              $objCita= Cita::where('id', '=', $id_cita)->first(); //CODIGO ESTABLECIMIENTO
              $objCita->codigo_siteds = $request->codigo_siteds;
              $objCita->save();
              $objPrestacion= Prestaciones::where('id', '=', $id_prestacion)->first(); //CODIGO ESTABLECIMIENTO
              $objCups_pnp = new \App\Models\Cups_pnp;
              $objCups_pnp->codigo_ipress =$objEstablecimiento->codigo;
              $objCups_pnp->año = substr($date->format('Y'), 2, 4); 
              $objCups_pnp->intramural_extramural = 'I'; 
              if($objCita->referido=='NO'){
                $objCups_pnp->no_referidos_referidos = 0; 
              }else{
                $objCups_pnp->no_referidos_referidos = 1; 
              }
              $objCups_pnp->tipo_atencion = $id_tipo_atencion; 
              $objCups_pnp->id_ext = $id_prestacion; 
              $objCups_pnp->corte_adm = $corte_adm; 
              $objCups_pnp->fecha_ejecucion_atencion = $fechaActual;  
              $objCups_pnp->save();
              
        
            ///AGREGAMOS CUPS PNP PARA LA TABLA DE EMERGENCIA AGREGAMOS CUPS PNP PARA LA TABLA DE EMERGENCIA/// 
              $valor_cups_pnp=($objCups_pnp->codigo_ipress."-".$objCups_pnp->año ."-". $objCups_pnp->intramural_extramural 
              ."-". $objCups_pnp->no_referidos_referidos ."-". $objCups_pnp->tipo_atencion ."-". str_pad($id_prestacion, 9, '0', STR_PAD_LEFT)) ;
              
              $objPrestacion->cups_pnp = $valor_cups_pnp;
              $objPrestacion->save();

            if ($id_prestacion_update == 0) {
              DB::rollback();
              return response()->json(['success' => false]);
              // Session::flash('flash_message', 'No se pudo registrar la Prestacion, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
              // return redirect('/cq_lista_pendiente');
            } else {
              DB::commit();
              return response()->json(['success' => true,'id_prestacion'=>$id_prestacion]);
              // Session::flash('flash_message', 'Prestación exitosa');
              // return redirect('/cq_lista_pendiente');
            }

          } else {
            //dd($request);
            $model_cqxanestesia = new \App\Models\CqxAnestesia;
            $id_anestesia_quirurgica = $request->id_anestesia_quirurgica;
            
            //dd(['id_anestesia_quirurgica' => $id_anestesia_quirurgica]);

            $rowaffect = DB::table('anestesia_quirurgicas')
              ->where('anestesia_quirurgicas.id', '=', $id_anestesia_quirurgica)
              ->update([
              'fecha_intervencion' => !empty($request->fecha_intervencion) ? $request->fecha_intervencion : null,
              'hora_inicio_intervencion' => !empty($request->hora_inicio_intervencion) ? $request->hora_inicio_intervencion : null,
              'hora_fin_intervencion' => !empty($request->hora_fin_intervencion) ? $request->hora_fin_intervencion : null,
              'id_sala_intervencion' => !empty($request->id_sala) ? $request->id_sala : null,
              //'id_tipo_anestesia' => isset($request->id_tipo_anestesia) ? $request->id_tipo_anestesia : null,
              'observacion' => !empty($request->observacion) ? $request->observacion : null,
              'tiene_intervencion' => !empty($request->tiene_intervencion) && $request->tiene_intervencion == 1 ? true : false,
              'tiene_observacion' => !empty($request->tiene_observacion) && $request->tiene_observacion == 1 ? true : false,
              'updated_us' => auth()->user()->id,
              'updated_at' => Carbon::now()
            ]);
            //Log::info('id_anestesia_quirurgica', ['id_anestesia_quirurgica' => $id_anestesia_quirurgica]);
            //Log::info('Actualización en anestesia_quirurgicas', ['rowaffect' => $rowaffect]);

            //============ Diagnosticos Adicionales ==================
            $p_diagnostico_adicional = "";
            $p_diagnostico_adicional .= "{";
            if (isset($request->id_diagnostico_adicional_anestesia)):
              foreach ($request->id_diagnostico_adicional_anestesia as $key => $value) :
                if ($request->id_diagnostico_adicional_anestesia[$key] != "") {
                  $p_diagnostico_adicional .= "{";
                  $p_diagnostico_adicional .= $request->id_diagnostico_adicional_anestesia[$key] . ",";
                  $p_diagnostico_adicional .= (isset($request->id_tipo_diagnostico_adicional_anestesia[$key]) && $request->id_tipo_diagnostico_adicional_anestesia[$key] > 0) ? $request->id_tipo_diagnostico_adicional_anestesia[$key] : "0";
                  $p_diagnostico_adicional .= "},";
                }
              endforeach;
              if (strlen($p_diagnostico_adicional) > 1) $p_diagnostico_adicional = substr($p_diagnostico_adicional, 0, -1);
            endif;
            $p_diagnostico_adicional .= "}";
            if (isset($request->id_diagnostico_adicional_anestesia)):
              $res_diagnostico_adicional_anestesia = $model_cqxanestesia->addDiagnosticoAdicional($id_prestacion_anestesia, $p_diagnostico_adicional, $request->id_user);
              Log::info('Agregando Diagnosticos Adicionales', ['addDiagnosticoAdicional' => $res_diagnostico_adicional_anestesia]);
            endif;
            //============================================================
            
            //=========== Procedimientos Adicionales =====================
            $p_procedimiento_adicional = "";
            $p_procedimiento_adicional .= "{";
            if (isset($request->id_procedimiento_adicional_anestesia)) :
              foreach ($request->id_procedimiento_adicional_anestesia as $key => $value) :
                if ($request->id_procedimiento_adicional_anestesia[$key] != "") {
                  $p_procedimiento_adicional .= "{";
                  $p_procedimiento_adicional .= $request->id_procedimiento_adicional_anestesia[$key] . ",";
                  $p_procedimiento_adicional .= (isset($request->procedimiento_cantidad_adicional_anestesia[$key]) && $request->procedimiento_cantidad_adicional_anestesia[$key] > 0) ? $request->procedimiento_cantidad_adicional_anestesia[$key] : "0";
                  $p_procedimiento_adicional .= "},";
                }
              endforeach;
              if (strlen($p_procedimiento_adicional) > 1) $p_procedimiento_adicional = substr($p_procedimiento_adicional, 0, -1);
            endif;
            $p_procedimiento_adicional .= "}";
            if (isset($request->id_procedimiento_adicional_anestesia)) :
              $res_procedimiento_adicional_anestesia = $model_cqxanestesia->addProcedimientoAdicional($id_prestacion_anestesia, $p_procedimiento_adicional, $request->id_user);
              Log::info('Agregando Procedimientos Adicionales', ['addProcedimientoAdicional' => $res_procedimiento_adicional_anestesia]);
            endif;
            
            //insertar procedimientos x diagnosticos de adicionales
            if(isset($request->id_procedimiento_adicional_anestesia)):
              if(isset($request->miSelectProdAdicionalAnestesia)):
                $arrayprocedimientosadicional=$request->id_procedimiento_adicional_anestesia;
                $arraydiagnosticosadicional=$request->miSelectProdAdicionalAnestesia;
                for($iterador=0;$iterador<count($arrayprocedimientosadicional);$iterador++){
          
                  $model_procedimiento_metodos = new \App\Models\Procedimiento;
                  $idprocedimientoadicional= $arrayprocedimientosadicional[$iterador];
                
                  $iddiagnosticoadicional = $arraydiagnosticosadicional[$iterador];
              
                  $idprestacionxDiagProcAdicional = $id_prestacion_anestesia;
                  $mensajeSucessAdicional = $model_procedimiento_metodos->RegistrarProcedimientoxDiagnostico($idprestacionxDiagProcAdicional,$idprocedimientoadicional,$iddiagnosticoadicional,1);  
                  Log::info('Agregando Procedimientos Diagnosticos Adicionales', ['RegistrarProcedimientoxDiagnostico' => $mensajeSucessAdicional]);
                }
        
              endif;
            endif;
            //============================================================
            //============ Medicamentos Editados o eliminados ============
            for ($i=0; $i < count($request->id_producto_receta_editar) ; $i++) {
               $accion = isset($request->accionRowProductoAnestesia[$i]) ? $request->accionRowProductoAnestesia[$i] : null;
              switch ($accion) {
                case 'edit':
                  $rowaffect2 = DB::table('producto_recetas')
                  ->where('producto_recetas.id', '=', $request->id_producto_receta_editar[$i])
                  ->update([
                  'cantidad_prescrita' => !empty($request->cantidad_prescrita_editar[$i]) ? $request->cantidad_prescrita_editar[$i] : null,
                  'tiempo_periodo' => !empty($request->tiempo_periodo_editar[$i]) ? $request->tiempo_periodo_editar[$i] : null,
                  'posologia' => !empty($request->posologia_editar[$i]) ? $request->posologia_editar[$i] : null,
                  'id_via_administracion' => !empty($request->id_via_administracion[$i]) ? $request->id_via_administracion[$i] : null,
                  ]);
                  break;
                
                case 'delete':  //Validar el cambio de estado a cero
                  //dd('Entro en Delete');
                  $rowaffect2 = DB::table('producto_recetas')
                  ->where('producto_recetas.id', '=', $request->id_producto_receta_editar[$i])
                  ->update([
                  'estado' => 0
                  ]);
                  break;
              }
            }
            
            //================== AGREGAR NUEVOS MEDICAMENTOS DE UNA RECETA EXISTENTE =======================
            // if ( $request->id_producto_adicional != null ) {
              
            //   $p_producto_adicional = "";
            //   $p_producto_adicional .= "{";
            //   if (true) :
            //     $receta_prescrita=1;
            //     foreach ($request->id_producto_adicional as $key => $value) :
            //       if ($request->id_producto_adicional[$key] != "") {
            //         $p_producto_adicional .= "{";
            //         $p_producto_adicional .= $request->id_producto_adicional[$key] . ",";
            //         $p_producto_adicional .= $request->tiempo_periodo_adicional[$key] . ",";
            //         $p_producto_adicional .= $request->nro_med_solictados_anestesia_adicional[$key] . ",";
            //         $p_producto_adicional .= (isset($request->nro_med_entregados_adicional[$key]) && $request->nro_med_entregados_adicional[$key] != "") ? $request->nro_med_entregados_adicional[$key] : 0;
            //         $p_producto_adicional .= ",";
            //         $p_producto_adicional .= (isset($request->nro_stocks_almacen_adicional[$key]) && $request->nro_stocks_almacen_adicional[$key] != "") ? $request->nro_stocks_almacen_adicional[$key] : 0;
            //         $p_producto_adicional .= ",";
            //         $p_producto_adicional .= (isset($request->nro_stocks_establecimiento_adicional[$key]) && $request->nro_stocks_establecimiento_adicional[$key] != "") ? $request->nro_stocks_establecimiento_adicional[$key] : 0;
            //         $p_producto_adicional .= ",";
            //         $p_producto_adicional .= (isset($request->nro_stocks_adicional[$key]) && $request->nro_stocks_adicional[$key] != "") ? $request->nro_stocks_adicional[$key] : 0;
            //         $p_producto_adicional .= ",";
            //         $p_producto_adicional .= (isset($request->posologiaAdicional[$key]) && $request->posologiaAdicional[$key] != "") ? str_replace(",", "###", $request->posologiaAdicional[$key]) : "-";
            //         $p_producto_adicional .= ",";
            //         $p_producto_adicional .= (isset($request->miSelectProdAdicionalAnestesia[$key]) && $request->miSelectProdAdicionalAnestesia[$key] != "") ? $request->miSelectProdAdicionalAnestesia[$key] : 0;
            //         $p_producto_adicional .= ",";
            //         $p_producto_adicional .=(isset($request->id_via_administracion_anestesia_adicional[$key]) && $request->id_via_administracion_anestesia_adicional[$key] != "") ? $request->id_via_administracion_anestesia_adicional[$key] : 0;
            //         $p_producto_adicional .= "},";
            //       }
            //     endforeach;
            //     if (strlen($p_producto_adicional) > 1) $p_producto_adicional = substr($p_producto_adicional, 0, -1);
            //   endif;
            //   $p_producto_adicional .= "}";
            //   //dd(["id_receta_vale : " => $request->id_receta_vale_editar,"producto_adicionales : " => $p_producto_adicional]);
            //   if(!empty($firma_receta_existente)){
            //     $id_receta=$model_cqxanestesia->agregarProductoRecetas($request->id_receta_vale_editar,$p_producto_adicional);
            //     DB::commit(); 
            //     return response()->json(['success' => true,'id_receta'=>$id_receta]);
            //   }else{
            //     $model_cqxanestesia->agregarProductoRecetas($request->id_receta_vale_editar,$p_producto_adicional);
            //   }
              
            // }
            //PARA MULTIPLES RECETAS EXISTENTES
            if ($request->id_producto_adicional != null) {
                $receta_prescrita = 1;
                foreach ($request->id_producto_adicional as $idReceta => $productos) {
                    $p_producto_adicional = "{";
                    foreach ($productos as $i => $idProducto) {

                        if (!$idProducto) continue;

                        $p_producto_adicional .= "{";

                        $p_producto_adicional .= $idProducto . ",";
                        $p_producto_adicional .= (isset($request->tiempo_periodo_adicional[$idReceta][$i]) ? $request->tiempo_periodo_adicional[$idReceta][$i] : '-') . ",";
                        $p_producto_adicional .= (isset($request->nro_med_solictados_anestesia_adicional[$idReceta][$i]) ? $request->nro_med_solictados_anestesia_adicional[$idReceta][$i] : 0) . ",";
                        $p_producto_adicional .= (isset($request->nro_med_entregados_adicional[$idReceta][$i]) ? $request->nro_med_entregados_adicional[$idReceta][$i] : 0) . ",";
                        $p_producto_adicional .= (isset($request->nro_stocks_almacen_adicional[$idReceta][$i]) ? $request->nro_stocks_almacen_adicional[$idReceta][$i] : 0) . ",";
                        $p_producto_adicional .= (isset($request->nro_stocks_establecimiento_adicional[$idReceta][$i]) ? $request->nro_stocks_establecimiento_adicional[$idReceta][$i] : 0) . ",";
                        $p_producto_adicional .= (isset($request->nro_stocks_adicional[$idReceta][$i]) ? $request->nro_stocks_adicional[$idReceta][$i] : 0) . ",";
                        $p_producto_adicional .= (isset($request->posologiaAdicional[$idReceta][$i]) 
                            ? str_replace(",", "###", $request->posologiaAdicional[$idReceta][$i]) 
                            : "-");
                        $p_producto_adicional .= ",";
                        $p_producto_adicional .= (isset($request->miSelectProdAdicionalAnestesia[$idReceta][$i]) ? $request->miSelectProdAdicionalAnestesia[$idReceta][$i] : 0) . ",";
                        $p_producto_adicional .= (isset($request->id_via_administracion_anestesia_adicional[$idReceta][$i]) ? $request->id_via_administracion_anestesia_adicional[$idReceta][$i] : 0);

                        $p_producto_adicional .= "},";
                    }
                    if (strlen($p_producto_adicional) > 1) {
                        $p_producto_adicional = rtrim($p_producto_adicional, ',');
                    }
                    $p_producto_adicional .= "}";
                    if(!empty($firma_receta_existente)){
                      $id_receta=$model_cqxanestesia->agregarProductoRecetas($request->id_receta_vale_editar,$p_producto_adicional);
                      DB::commit(); 
                      return response()->json(['success' => true,'id_receta'=>$id_receta]);
                    }else{
                      $model_cqxanestesia->agregarProductoRecetas($idReceta,$p_producto_adicional);
                    }
                    // $array[]=array(
                    //   'id_receta_vale_editar'=>$request->id_receta_vale_editar,
                    //   'idReceta'=>$idReceta,
                    //   'p_producto_adicional'=>$p_producto_adicional
                    // );
                }
            }
            //FIN PARA MULTIPLES RECETAS EXISTENTES

            ////================== AGREGAR NUEVOS MEDICAMENTOS DE UNA RECETA NUEVA =======================
            if ( $request->id_producto_adicional_nuevo != null ) {
              $p_producto_adicional_nuevo = "";
              $p_producto_adicional_nuevo .= "{";
              if (!empty($request->id_producto_adicional_nuevo) && is_array($request->id_producto_adicional_nuevo)) :
                foreach ($request->id_producto_adicional_nuevo as $key => $value) :
                  if ($request->id_producto_adicional_nuevo[$key] != "") {
                    $p_producto_adicional_nuevo .= "{";
                    $p_producto_adicional_nuevo .= $request->id_producto_adicional_nuevo[$key] . ",";
                    $p_producto_adicional_nuevo .= $request->tiempo_periodo_adicional_nuevo[$key] . ",";
                    $p_producto_adicional_nuevo .= $request->nro_med_solictados_anestesia_adicional_nuevo[$key] . ",";
                    $p_producto_adicional_nuevo .= (isset($request->nro_med_entregados_adicional_nuevo[$key]) && $request->nro_med_entregados_adicional_nuevo[$key] != "") ? $request->nro_med_entregados_adicional_nuevo[$key] : 0;
                    $p_producto_adicional_nuevo .= ",";
                    $p_producto_adicional_nuevo .= (isset($request->nro_stocks_almacen_adicional_nuevo[$key]) && $request->nro_stocks_almacen_adicional_nuevo[$key] != "") ? $request->nro_stocks_almacen_adicional_nuevo[$key] : 0;
                    $p_producto_adicional_nuevo .= ",";
                    $p_producto_adicional_nuevo .= (isset($request->nro_stocks_establecimiento_adicional_nuevo[$key]) && $request->nro_stocks_establecimiento_adicional_nuevo[$key] != "") ? $request->nro_stocks_establecimiento_adicional_nuevo[$key] : 0;
                    $p_producto_adicional_nuevo .= ",";
                    $p_producto_adicional_nuevo .= (isset($request->nro_stocks_adicional_nuevo[$key]) && $request->nro_stocks_adicional_nuevo[$key] != "") ? $request->nro_stocks_adicional_nuevo[$key] : 0;
                    $p_producto_adicional_nuevo .= ",";
                    $p_producto_adicional_nuevo .= (isset($request->posologiaAdicional_nuevo[$key]) && $request->posologiaAdicional_nuevo[$key] != "") ? str_replace(",", "###", $request->posologiaAdicional_nuevo[$key]) : "-";
                    $p_producto_adicional_nuevo .= ",";
                    $p_producto_adicional_nuevo .= (isset($request->miSelectProdAdicionalAnestesia_nuevo[$key]) && $request->miSelectProdAdicionalAnestesia_nuevo[$key] != "") ? $request->miSelectProdAdicionalAnestesia_nuevo[$key] : 0;
                    $p_producto_adicional_nuevo .= ",";
                    $p_producto_adicional_nuevo .=(isset($request->id_via_administracion_anestesia_adicional_nuevo[$key]) && $request->id_via_administracion_anestesia_adicional_nuevo[$key] != "") ? $request->id_via_administracion_anestesia_adicional_nuevo[$key] : 0;
                    $p_producto_adicional_nuevo .= "},";
                  }
                endforeach;
                if (strlen($p_producto_adicional_nuevo) > 1) $p_producto_adicional_nuevo = substr($p_producto_adicional_nuevo, 0, -1);
              endif;
              $p_producto_adicional_nuevo .= "}";
              $id_farmacia_receta        =  ($request->id_farmacia!="") ? $request->id_farmacia : 0;
              $id_receta_vale_bus = 0;
              if (Auth::user()->tipo_registro == 3) $id_receta_vale_bus = 1;
              
              $hora = date('H');
              if ($hora > 6 && $hora < 13) $turno = "M";
              elseif ($hora >= 13 && $hora < 19) $turno = "T";
              elseif ($hora >= 19 && $hora <= 24) $turno = "N";
              else $turno = "MD";

              $model_prestacion = new \App\Models\Prestaciones;
              $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
              $data=array(
                'p_id_receta_vale'=>0, 
                'p_id_farmacia'=>$id_farmacia_receta, 
                'p_id_user'=> Auth::user()->id,
                'p_id_consultorio'=> 72,
                'p_nro_receta'=>'', 
                'p_digitos_adicionales'=>'', 
                'p_dni_beneficiario'=>$prestacion->nro_doc_ident, 
                'p_cip_beneficiario'=>$prestacion->cip, 
                'p_tipdoc_beneficiario'=>$prestacion->tipo_doc_ident, 
                'p_nombre_beneficiario'=>$prestacion->asegurado_nombre, 
                'p_paterno_beneficiario'=>$prestacion->asegurado_paterno, 
                'p_materno_beneficiario'=>$prestacion->asegurado_materno, 
                'p_tipo_beneficiario'=>$prestacion->parentesco, 
                'p_dni_titular'=>$request->nrodoctitular, 
                'p_tipdoc_titular'=>$request->tipodoc_titular, 
                'p_nombre_titular'=>$request->nombre_titular, 
                'p_paterno_titular'=>$request->paterno_titular, 
                'p_materno_titular'=>$request->materno_titular,
                'p_fecha_expedicion'=>(isset($request->fecha_expedicion)) ? $request->fecha_expedicion : '', 
                'p_fecha_atencion'=>Carbon::parse($request->fecha_hora_atencion)->format('Y-m-d'), 
                'p_nro_historia'=>$prestacion->nro_historia, 
                'p_grado'=>$prestacion->grado, 
                'p_id_medico'=>$prestacion->id_medico, 
                'p_tipo_receta'=>$prestacion->id_tipo_atencion, 
                'p_turno'=>$turno, 
                'p_obs_registro'=>'',
                'p_id_receta_vale_bus'=>$id_receta_vale_bus, 
                'p_id_admision'=>0, 
                'p_tipo_via'=>'', 
                'p_sub_via'=>'', 
                'p_final_via'=>'', 
                'p_nro_guia'=>'', 
                'p_interconsulta'=>'', 
                'p_interconsulta_dias'=>0, 
                'p_id_prestacion'=>$id_prestacion, 
                'p_id_tipo_num'=>0, 
                'p_estado'=>2, //estado de la receta
                'p_detalle'=>$p_producto_adicional_nuevo,  
                'p_sexo'=>$prestacion->sexo,
                'p_fecha_nacimiento'=>$prestacion->fecha_nac, 
                'p_codigo_upss'=>$prestacion->codigo_upss
              );

              if($request->firma_receta_adicional_anestesio==1){
                $id_receta=$model_prestacion->registrarRecetaValeAtencionDetalle_cqx($data);
                DB::commit();
                return response()->json(['success' => true,'id_receta'=>$id_receta]);
              }else{
                $id_receta=$model_prestacion->registrarRecetaValeAtencionDetalle_cqx($data);
              }

            }
            //============================================================
            //================== TIPO DE ANESTESIA =======================
            
            //dd('Paso la función del registro de productos...');
            $tipo_anestesia_elegidos = DB::table('cqx_tipo_anestesia_detalles')
                                              ->where('id_anestesia', $id_anestesia_quirurgica)
                                              ->where('id_estado', 1)
                                              ->select()
                                              ->get();   

            $existe = "no";
            //dd('Entro ewn condición');
            if (!is_null($request->id_tipo_anestesia)) {
                // Registrar nuevos subconsultorios
                
                foreach ($request->id_tipo_anestesia as $key => $value) {
                    $existe = "no";
                    foreach ($tipo_anestesia_elegidos as $rowConsul) :
                        if ($request->id_tipo_anestesia[$key] == $rowConsul->id_tipo_anestesia) {
                            $existe = "si";
                        }
                    endforeach;
                    if ($existe == "no") {
                        DB::table('cqx_tipo_anestesia_detalles')->insert([
                          'id_anestesia' => $id_anestesia_quirurgica,
                          'id_tipo_anestesia' => $request->id_tipo_anestesia[$key],
                          'id_estado' => 1,
                          'created_us' => auth()->user()->id,
                          'created_at' => Carbon::now()
                        ]);
                    }
                }

                // Deshabilitar subconsultorios
                foreach ($tipo_anestesia_elegidos as $rowConsul) :
                    $existe = "no";
                    foreach ($request->id_tipo_anestesia as $key => $value) {
                        if ($rowConsul->id_tipo_anestesia == $request->id_tipo_anestesia[$key]) {
                            $existe = "si";
                        }
                    }
                    if ($existe == "no") {
                      $rowaffect2 = DB::table('cqx_tipo_anestesia_detalles')
                      ->where('id', '=', $rowConsul->id)
                      ->update([
                      'id_estado' => 0,
                      'updated_us' => auth()->user()->id,
                      'updated_at' => Carbon::now()
                      ]);
                    }
                endforeach;
            }
            //============================================================


            if (isset($request->anestesiologosEliminados)) {
              foreach ($request->anestesiologosEliminados as $ae) {
                $rowaffect2 = DB::table('cqx_profesionales')
                ->where('cqx_profesionales.id', '=', $ae)
                ->update([
                'estado' => 3,
                'deleted_us' => auth()->user()->id,
                'deleted_at' => Carbon::now()
                ]);
                
                Log::info('Eliminar en cqx_profesionales', ['rowaffect2' => $rowaffect2]);
              }
            }
            if (isset($request->id_anestesiologo_anestesia)) {
              foreach ($request->id_anestesiologo_anestesia as $key=> $value) {
                  DB::table('cqx_profesionales')->insert([
                    'id_cqx_anestesia' => $id_anestesia_quirurgica,
                    'id_medico' => $value,
                    'id_tipo_professional' => $request->tipo_profesional_anestesiologo_anestesia[$key],
                    'flag_personal' => 1,
                    'flag_validacion' => 0,
                    'estado' => 1,
                    'created_us' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'id_cqx_intervenciones'=>$request->id_intervencion
                  ]);
              }
            }

            $fechahora = Carbon::parse($request->fecha_hora_atencion)->format('Y-m-d H:i:s');
            $prestaciones = Prestaciones::find($id_prestacion_anestesia);
            $prestaciones->fecha_atencion = $fechahora;
            $prestaciones->save();

            if ($rowaffect === 0) {
              DB::rollback();
              return response()->json(['success' => false]);
              // Session::flash('flash_message', 'No se pudo guardas tus cambios, hubo un problema con la actuzalización, vuelva a intentarlo nuevamente');
              // return redirect('/cq_lista_pendiente');
            } else {
              DB::commit(); 
              return response()->json(['success' => true]);
              //Session::flash('flash_message', 'Se guardo correctamente.');
              //return redirect('/cq_lista_pendiente');
            }
          
          }
      } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al querer guardar datos de Atencion de Anestesiologia', [
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

    public function form_validacion_programacion_qx($id_intervencion_quirurgica,$id_tipo_atencion_origen)
    {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;
      $model_tipo_cirugia = "";
      $tipo_cirugias = "";
      $diagnosticos_cqx = "";
      $procedimientos_cqx_emergencia ="";
      $profesionales_cqx_emergencia ="";

      $model_medico = new Medico();
      $data_medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);

      if (count($data_medico) > 0) {
        $id_medico = $data_medico->id;

        $model_intervencion = new CqxIntervencion();
        $intervencion = $model_intervencion->GetById($id_intervencion_quirurgica);
       
        /**************DATOS PARA HOSPITALIZACION NUEVO ADMISION CLINICA DE DIA****************/
        $es_nuevo_admision_hospitalizacion = $intervencion->es_nuevo_admision_hospitalizacion;
        $es_traslado_hospitalizacion_acqx = $intervencion->es_traslado_hospitalizacion_acqx;
        $id_tipo_origen="";
        $fecha_registro_admision="";
        if(!empty($intervencion->id_hospitalizacion) && $es_nuevo_admision_hospitalizacion && !$es_traslado_hospitalizacion_acqx){
          $hospitalizacion = Hospitalizacione::find($intervencion->id_hospitalizacion);
          $fecha_registro_admision = !empty($hospitalizacion->fecha_ingreso)?$hospitalizacion->fecha_ingreso:'';
          $id_tipo_origen = !empty($hospitalizacion->id_tipo_origen)?$hospitalizacion->id_tipo_origen:0;

          $id_especialidad_todos = 0;
          $lista_profesionales_cirujanos = $model_medico->GetProfesionalesXEspecialidad(Auth::user()->id_establecimiento, $id_especialidad_todos);

          $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
          $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();
          $diagnosticos_cqx = DB::table('receta_diagnosticos as rd')
              ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
              ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
              ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
              ->where('rd.id_intervencion', '=', $intervencion->id)
              ->get();
          $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
          $procedimientos_cqx_emergencia = $model_cq_procedimiento->GetByIdIntervencionQuirugica($intervencion->id);
          //PROFESIONALES QUIRURGICOS
          $model_cq_profesional = new \App\Models\CqxProfesional();
          $profesionales_cqx_emergencia = $model_cq_profesional->GetByIdIntervencionQuirugicaYTipoPersonal($intervencion->id, 0);
        }
        /**************FIN DATOS PARA HOSPITALIZACION NUEVO ADMISION CLINICA DE DIA****************/

        $is_origen_emergencia = $intervencion->id_tipo_atencion == 2 ? "1" : "0";
        
        $model_cq_procedimiento = new CqxProcedimiento();
        $procedimientos = $model_cq_procedimiento->GetByIdIntervencionQuirugica($id_intervencion_quirurgica);

        $model_cq_profesional = new CqxProfesional();
        $profesionales_quirugicos = $model_cq_profesional->GetByIdIntervencionQuirugicaYTipoPersonal($id_intervencion_quirurgica, 0);
        
        $id_establecimiento = Auth::user()->id_establecimiento;
        // $model_medico = new Medico();
        $id_especialidad_anestesiologia = 72;
        $lista_profesionales_anestesiologos = $model_medico->GetProfesionalesXEspecialidad($id_establecimiento, $id_especialidad_anestesiologia);

        $model_cqx_dia = new \App\Models\CqxDia();
        $cqx_dias = $model_cqx_dia->GetAllActivos();

        $model_cqx_tipo_profesional = new \App\Models\CqxTipoProfesional();
        $cqx_tipo_profesionales = $model_cqx_tipo_profesional->GetAllActivos();

        $model_cq_sala = new \App\Models\CqSalas();
        $cq_salas = $model_cq_sala->GetAllActivos();

        $model_tipo_anestesia = new \App\Models\CqxTipoAnestesia();
        $tipos_anestesia = $model_tipo_anestesia->GetAllActivos();

        // Datos si la Programacion Quirurgica esta validada
        $profesionales_anestesiologos = $model_cq_profesional->GetByIdIntervencionQuirugicaYTipoPersonal($id_intervencion_quirurgica, 1);
        
        $lista_profesionales_cirujanos = null;
        if ($is_origen_emergencia || $es_traslado_hospitalizacion_acqx) {
          $id_especialidad_todos = 0;
          $lista_profesionales_cirujanos = $model_medico->GetProfesionalesXEspecialidad($id_establecimiento, $id_especialidad_todos);

          $model_procedimiento = new \App\Models\Procedimiento();
          $procedimientos = $model_procedimiento->procedimientosxestado();
          // $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
          // $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();
          $diagnosticos_cqx = DB::table('receta_diagnosticos as rd')
              ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
              ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
              ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
              ->where('rd.id_intervencion', '=', $intervencion->id)
              ->where(function($q) use ($intervencion) {
              $q->where('rd.id_prestacion', $intervencion->id_prestacion)
                    ->orWhereNull('rd.id_prestacion');
              })
              ->get();
          $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
          $procedimientos_cqx_emergencia = $model_cq_procedimiento->GetByIdIntervencionQuirugica($intervencion->id);

          //PROFESIONALES QUIRURGICOS
          $model_cq_profesional = new \App\Models\CqxProfesional();
          $profesionales_cqx_emergencia = $model_cq_profesional->GetByIdIntervencionQuirugicaYTipoPersonal($intervencion->id, 0);

        }
        $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
        $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();
        $observaciones = $observaciones = CqxObservacion::with('medico')->where('id_intervencion', $id_intervencion_quirurgica)->orderBy('fecha_registro', 'desc')->get();
        
        $datos_interconsulta=null;
        if(!empty($intervencion->id_interconsulta)){
          $datos_interconsulta = \App\Models\Interconsulta::where('id',$intervencion->id_interconsulta)->where('id_prestacion',$intervencion->id_prestacion)->first();
        }
        return view('frontend.centro_quirurgico.form_validacion_programacion', compact('intervencion', 'procedimientos', 'profesionales_quirugicos', 'lista_profesionales_anestesiologos', 'profesionales_anestesiologos', 'cqx_dias', 'cqx_tipo_profesionales', 'id_medico', 'cq_salas', 'tipos_anestesia', 'is_origen_emergencia', 'lista_profesionales_cirujanos','tipo_cirugias','diagnosticos_cqx','procedimientos_cqx_emergencia','profesionales_cqx_emergencia','id_tipo_origen','fecha_registro_admision','es_nuevo_admision_hospitalizacion','es_traslado_hospitalizacion_acqx','id_tipo_atencion_origen','data_medico','observaciones','datos_interconsulta'));
      } else {
        Session::flash('flash_danger', 'No se encuentra registrado como Profesional Médico para realizar la Validación.');
        return redirect('/cq_lista_pendiente');
      }
    }

    public function validar_programacion_quirurgica(Request $request)
    {
      //dd($request->all());
      $id_intervencion_quirurgica = $request->id_intervencion_quirurgica;
      $id_user = Auth::user()->id;

      DB::beginTransaction();

      try {
          $is_observado = $request->is_observado;
        if($is_observado==1){
          $model_medicos = new Medico;
          $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
          if(empty($medico))
          {
            return response()->json([
                'status' => 'no_medico',
                'tipo' => '',
                'message' => 'La observacion solo es registrada para los medicos.',
                'observaciones' =>'',
                'sugerencia' =>''
            ]);
          }
          $observacion = new CqxObservacion();
          $fecha_registro_observacion = Carbon::createFromFormat('Y-m-d', $request->fecha_registro_observacion);
          $hora_registro_observacion  = Carbon::createFromFormat('H:i', $request->hora_registro_observacion);
          
          $observacion->fecha_registro = $fecha_registro_observacion;
          $observacion->hora_registro  = $hora_registro_observacion;
          $observacion->motivo         = $request->motivo_observacion;
          $observacion->id_intervencion = $id_intervencion_quirurgica;
          $observacion->created_us     = $medico->id;//id_medico
          $observacion->created_at     = Carbon::now();
          $observacion->save();
          //actualizar el flag a estado pendiente
          $intervencion = CqxIntervencion::find($id_intervencion_quirurgica);
          if ($intervencion) {
              $intervencion->flag = 1;
              $intervencion->save();
          }
          $tipo="observacion";
          $msg="Observación registrada correctamente.";
        }else{
          if ((int)$request->id_tipo_atencion_origen == 1) { // SOLO PARA AMBULATORIO
              $hospitalizacionExiste = \App\Models\HospitalizacionOrden::where('id_ext', $id_intervencion_quirurgica)->exists();
              if (!$hospitalizacionExiste) {
                  return response()->json([
                      'status' => false, // Indica claramente que no hay hospitalización
                      'tipo' => null,
                      'message' => 'No se encontró la Orden de hospitalización asociada a esta atención ambulatoria.',
                      'observaciones' => null,
                      'sugerencia' => null
                  ], 200);
              }
          }
          $id_lugar_operacion = $request->id_lugar_operacion;
          $fecha_intervencion = $request->fecha_intervencion;
          $hora_inicio_intervencion = $request->hora_inicio_intervencion;
          $hora_fin_intervencion = $request->hora_fin_intervencion;
          //if (!$request->is_origen_emergencia):
          //$fecha_intervencion_formateada = Carbon::createFromFormat('d-m-Y', $fecha_intervencion);
          // $fecha_hora_inicio_intervencion = $fecha_intervencion_formateada->format('Y-m-d') . ' ' . $hora_inicio_intervencion;
          // $fecha_hora_fin_intervencion = $fecha_intervencion_formateada->format('Y-m-d') . ' ' . $hora_fin_intervencion;
          //endif;

          $id_prioridad_programacion = $request->id_prioridad_programacion;
          $is_requiere_internamiento_preqx = $request->has_requiere_internamiento_preqx;
          $is_hospitalizacion_postqx =  $request->is_requiere_hospitalizacion_postqx;
          $is_prioridad_programacion_semanas = $request->has_prioridad_programacion_semanas;
          
          // if (!$request->is_origen_emergencia):
          // $fecha_inicio_intervencion = Carbon::createFromFormat('Y-m-d H:i', $fecha_hora_inicio_intervencion);
          // $fecha_fin_intervencion = Carbon::createFromFormat('Y-m-d H:i', $fecha_hora_fin_intervencion);
          // endif;
          $id_sala_intervencion = $request->id_sala_intervencion;
          $id_tipo_anestesia = $request->id_tipo_anestesia;
          $id_medico_validacion = $request->id_medico;
          $fecha_validacion = Carbon::now();

          $id_tiempo_internamiento_preqx = null;
          $id_tiempo_hospitalizacion_postqx = null;
          if ($is_requiere_internamiento_preqx):
            $id_tiempo_internamiento_preqx = $request->id_tiempo_internamiento_preqx;
          endif;
          
          if ($is_hospitalizacion_postqx):
            $id_tiempo_hospitalizacion_postqx = $request->id_tiempo_hospitalizacion_postqx;
          endif;

          $id_prioridad_programacion = null;
          if ($is_prioridad_programacion_semanas):
            $id_prioridad_programacion = $request->id_prioridad_programacion;
          endif;

          if (!$request->is_origen_emergencia):
            $model_intervencion = new \App\Models\CqxIntervencion();
            $response = $model_intervencion->ValidarProgramacionQuirurgica($id_intervencion_quirurgica, $id_prioridad_programacion, $is_requiere_internamiento_preqx, $is_hospitalizacion_postqx, $id_sala_intervencion, $id_tipo_anestesia, $id_medico_validacion, $fecha_validacion, $id_tiempo_internamiento_preqx, $id_tiempo_hospitalizacion_postqx, $id_lugar_operacion,$is_prioridad_programacion_semanas);
          endif;

          // $fecha_intervencion = !empty($request->fecha_intervencion)?Carbon::createFromFormat('Y-m-d', $request->fecha_intervencion):null;
          // $fecha_probable_intervencion = !empty($request->fecha_probable_intervencion)?Carbon::createFromFormat('Y-m-d', $request->fecha_probable_intervencion):null;
          // $fecha_registro = Carbon::createFromFormat('Y-m-d', $request->fecha_registro);
          // $hora_registro  = Carbon::createFromFormat('H:i', $request->hora_registro);
          // $intervencion = CqxIntervencion::find($id_intervencion_quirurgica);
          $fecha_intervencion = !empty($request->fecha_intervencion) ? Carbon::parse($request->fecha_intervencion) : null;
          $fecha_probable_intervencion = !empty($request->fecha_probable_intervencion) ? Carbon::parse($request->fecha_probable_intervencion) : null;
          $fecha_registro = !empty($request->fecha_registro) ? Carbon::parse($request->fecha_registro) : Carbon::now();
          $hora_registro  = !empty($request->hora_registro) ? Carbon::parse($request->hora_registro) : Carbon::now();
          $intervencion = CqxIntervencion::find($id_intervencion_quirurgica);
 
          if ($request->is_origen_emergencia || $intervencion->es_nuevo_admision_hospitalizacion){
          // $intervencion->id_tipo_origen == 1 && $intervencion->es_nuevo_admision_hospitalizacion
            // $intervencion = CqxIntervencion::find($id_intervencion_quirurgica);
            //es_nuevo_admision_hospitalizacion ->clinica de dia
            // if(!$intervencion->es_nuevo_admision_hospitalizacion){
            //   $intervencion->emergencia_tipo_cirugia = $request->id_tipo_cirugia;
            // }
            $intervencion->emergencia_duracion_cirugia = $request->duracion_cirugia ? floatval($request->duracion_cirugia) : null;
            $intervencion->validated_us = $id_medico_validacion;
            $intervencion->validated_at = $fecha_validacion;
            $intervencion->id_sala_intervencion = $request->id_sala_intervencion;
            $intervencion->id_tipo_anestesia = $request->id_tipo_anestesia;
            $intervencion->flag = 3;
            $intervencion->fecha_registro = $fecha_registro->format('Y-m-d');
            $intervencion->hora_registro = $hora_registro->format('H:i:s');
            $intervencion->fecha_probable_intervencion = $fecha_probable_intervencion;
            $intervencion->fecha_intervencion = $fecha_intervencion;
            $intervencion->id_tipo_atencion_origen = $request->id_tipo_atencion_origen;
            
            //Registro de diagnosticos cqx
            if (isset($request->id_diagnostico_quirurgico)) :
              $dignostico_cqx = new \App\Models\Receta_diagnostico();
              foreach ($request->id_diagnostico_quirurgico as $key => $value) :
                if ($request->id_diagnostico_quirurgico[$key] != "") {
                  $dignostico_cqx->id_diagnostico = $request->id_diagnostico_quirurgico[$key];
                  $dignostico_cqx->id_tipo_diagnostico = $request->id_tipo_diagnostico_quirurgico[$key];
                  $dignostico_cqx->estado = 1;
                  $dignostico_cqx->id_prestacion = $request->id_prestacionx;
                  $dignostico_cqx->created_at=Carbon::now();
                  $dignostico_cqx->created_us=$request->id_user;
                  $dignostico_cqx->id_intervencion=$id_intervencion_quirurgica;
                  $dignostico_cqx->save();
                }
              endforeach;
            endif; 
            
            // Registro de Procedimientos de Intervencion quirurgica
            if(!$intervencion->es_nuevo_admision_hospitalizacion){
              if (isset($request->id_procedimiento_intervencion)) :
                $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
                foreach ($request->id_procedimiento_intervencion as $key => $value) :
                  if ($request->id_procedimiento_intervencion[$key] != "") {
                    $p_id_procedimiento = $request->id_procedimiento_intervencion[$key];
                    $model_cq_procedimiento->RegistrarProcedimientoPreQx($id_intervencion_quirurgica, $p_id_procedimiento, Auth::user()->id, 0,1,false);
                  }
                endforeach;
              endif;
            }

            // Registro de Medicos Cirujanos
            if (isset($request->id_medico_cirujano)) :
              $tipo_personal = 0; //Cirujanos
              $model_cq_profesional = new \App\Models\CqxProfesional();
              foreach ($request->id_medico_cirujano as $key => $value) :
                if ($request->id_medico_cirujano[$key] != "") {
                  $p_id_medico = $request->id_medico_cirujano[$key];
                  $p_id_medico_cargo = (isset($request->id_medico_cirujano_cargo[$key]) && $request->id_medico_cirujano_cargo[$key] > 0 ? $request->id_medico_cirujano_cargo[$key] : 0);
                  $response = $model_cq_profesional->RegistrarProfesionalesPreQx($id_intervencion_quirurgica, $p_id_medico, $p_id_medico_cargo, $id_user, $tipo_personal, 0);
                }
              endforeach;
            endif;
          }else{
            $intervencion->fecha_registro = $fecha_registro->format('Y-m-d');
            $intervencion->hora_registro = $hora_registro->format('H:i:s');
            $intervencion->fecha_probable_intervencion = $fecha_probable_intervencion;
            $intervencion->fecha_intervencion = $fecha_intervencion;
            $intervencion->emergencia_duracion_cirugia = $request->duracion_cirugia ? floatval($request->duracion_cirugia) : null;
            $intervencion->id_sala_intervencion = $request->id_sala_intervencion;
            $intervencion->id_tipo_anestesia = $request->id_tipo_anestesia;
          }
          $intervencion->emergencia_tipo_cirugia = $request->id_tipo_cirugia;
          $intervencion->sugerencia_medica = !empty($request->sugerencia_medica) ? $this->limpiarTextarea($request->sugerencia_medica) : '';
          $intervencion->is_sugerencia_medica = !empty($request->is_sugerencia_medica) && $request->is_sugerencia_medica == 1 ? true : false;
          $intervencion->save();
          
          // Registro de Medicos Anestesiologos 
          if (isset($request->id_medico_anestesiologia)) :
            $tipo_personal = 1; //Anestesiologos
            $model_cq_profesional = new \App\Models\CqxProfesional();
            foreach ($request->id_medico_anestesiologia as $key => $value) :
              if ($request->id_medico_anestesiologia[$key] != "") {
                $p_id_medico = $request->id_medico_anestesiologia[$key];
                $p_id_medico_cargo = (isset($request->id_medico_anestesiologia_cargo[$key]) && $request->id_medico_anestesiologia_cargo[$key] > 0 ? $request->id_medico_anestesiologia_cargo[$key] : 0);
                $response = $model_cq_profesional->RegistrarProfesionalesPreQx($id_intervencion_quirurgica, $p_id_medico, $p_id_medico_cargo, $id_user, $tipo_personal, 0);
              }
            endforeach;
          endif;
          
          if (!$request->is_origen_emergencia && !$intervencion->es_nuevo_admision_hospitalizacion && !$intervencion->es_traslado_hospitalizacion_acqx):
          $model_hospitalizacion_orden = new HospitalizacionOrden();
          // $response = $model_hospitalizacion_orden->ValidarOrdenDeCentroQuirurgico(4, $id_intervencion_quirurgica);
          if(HospitalizacionOrden::where('id_ext', $id_intervencion_quirurgica)->exists()){
          $response = $model_hospitalizacion_orden->ValidarOrdenDeCentroQuirurgico($request->id_tipo_atencion_origen, $id_intervencion_quirurgica);
          }
          endif;
          
          $model_intervencion = new CqxIntervencion();
          $response = $model_intervencion->ValidarIntervencionQuirurgica($id_intervencion_quirurgica);
          $tipo="validacion";
          $msg="Observación registrada correctamente";
        }
        $observaciones = $observaciones = CqxObservacion::with('medico')->where('id_intervencion', $id_intervencion_quirurgica)->orderBy('fecha_registro', 'desc')->get();
        DB::commit();
        return response()->json([
            'status' => 'success',
            'tipo' => $tipo,
            'message' => $msg,
            'observaciones' => $observaciones->toArray(),
            'sugerencia' =>!empty($intervencion->sugerencia_medica)?$intervencion->sugerencia_medica:''
        ]);
        //Session::flash('flash_message', 'Se validó correctamente.');
        //return redirect('/cq_lista_pendiente');

      } catch (\Throwable $e) {
          // Revertir la transacción en caso de error
          DB::rollBack();
                Log::error('Error al validar la Programación Quirúrgica, Registro id intervencion '.$id_intervencion_quirurgica, [
              'mensaje' => $e->getMessage(),
              'archivo' => $e->getFile(),
              'linea'   => $e->getLine(),
              'trace'   => $e->getTraceAsString(),
          ]);

          // Session::flash('flash_danger', 'Registro id intervencion '.$id_intervencion_quirurgica.', No se pudo validar la Programación Quirúrgica. Intente de nuevo.'. $e->getMessage());
          // return redirect('/cq_lista_pendiente');
          return response()->json([
          'status' => 'error',
          'message' => 'Error: ' . $e->getMessage()
      ], 500);
      }
    }

    public function registrar_suspencion_intervencion(Request $request)
    {
      // dd($request);
      if (!Auth::check()):
        return redirect('/auth/login');
      endif;

      DB::beginTransaction();

      try {
        $id_establecimiento = Auth::user()->id_establecimiento;

        $model_intervencion = new CqxIntervencion();
        $id_intervencion_quirurgica = $request->id_intervencion_quirurgica_suspender;

        $suspender = $model_intervencion->SuspenderSegunRegla($id_intervencion_quirurgica, $id_establecimiento);
    
        if (!empty($suspender) && isset($suspender[0]->resultado)) {
            $resultado = (int) $suspender[0]->resultado;
            $mensaje = trim((string) $suspender[0]->mensaje);

            if ($resultado === 1) {
                Session::flash('flash_danger', $mensaje);
            } elseif ($resultado === 0) {
                $id_user = Auth::user()->id;
                $fecha_suspension = Carbon::now();
                $motivo = $request->suspended_motivo;
                $model_intervencion->Suspender($id_intervencion_quirurgica, $id_user, $fecha_suspension, $motivo);
                DB::commit();
                Session::flash('flash_message', 'La intervención quirúrgica fue suspendida correctamente.');
            }
        } else {
            // Si por alguna razón no se obtuvo un resultado válido
            Session::flash('flash_message', 'Error al validar la suspensión. Intente nuevamente o contacte al administrador.');
        }
        return redirect('/cq_lista_pendiente');

      } catch (\Exception $e) {
          // Revertir la transacción en caso de error
          DB::rollBack();
          Log::error('Error al querer suspender paciente intervencion quirurgica', [
              'message' => $e->getMessage(),
              'code' => $e->getCode(),
              'file' => $e->getFile(),
              'line' => $e->getLine(),
              'trace' => $e->getTraceAsString(),
          ]);
          Session::flash('flash_danger', 'No se pudo suspender la Intervención Quirúrgica. Intente de nuevo.');
          return redirect('/cq_lista_pendiente');
      }
    }

    public function obtenerIntervencionParaAnestesia($id_intervencion)
    {
      $intervencion = DB::table('cqx_intervenciones')
                                ->where('id',$id_intervencion)
                                ->select()
                                ->first();
      return json_encode($intervencion);
    }
    public function mostrar_modal_hc_cqx($id_asegurado)
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
      $model_paciente = new \App\Models\Paciente;
      $model_prestacion = new \App\Models\Prestaciones;
      $paciente = $model_paciente->getById($id_asegurado);
      $atenciones = $model_prestacion->GetAtencionesByIdAsegurado($paciente->id_asegurado);

      $id_usuario = Auth::user()->id;
      $accesos = DB::table('users')
      ->select('users.*')
      ->where('id',$id_usuario)
      ->first();
      return view('frontend.centro_quirurgico.modal_hc', compact('paciente','atenciones','accesos'));
    }
    function emptyToNull($value) {
      return ($value === '' || $value === null) ? null : $value;
    }

    /******************FIRMA RECETA ATENCION QUIRURGICA ANESTESIOLOGIA*****************/
    public function recetaPDFfirmarAnestesio($id_receta){
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
        $csv.= "_receta_atencion_quirurgica_anestesiologia";
        Excel::create($csv, function($excel) use($id_receta){
            $excel->sheet('GENERAL', function($sheet) use($id_receta) {
                $variable = [];
                $this->recetaGenerarPdfParaFirmaAnestesio($id_receta);
                array_push($variable, array(url().DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'receta_atencion_quirurgica_anestesiologia_'.$id_receta.'.pdf', url().DIRECTORY_SEPARATOR.'upload_firma'.DIRECTORY_SEPARATOR.'?name=cf_receta_atencion_quirurgica_anestesiologia_'.$id_receta.'.pdf&id_tipo=receta_atencion_quirurgica_anestesiologia&id_resol='.$id_receta, 'cf_receta_atencion_quirurgica_anestesiologia_'.$id_receta.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'escudo.jpg','1','7'));                             
                $sheet->fromArray($variable, null, '', false, false);
            });
          })->store('csv',public_path('tmp'.DIRECTORY_SEPARATOR.'csv'));
          $obj = new Archive7z(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.$csv.'.7z'));
          $obj->addEntry(public_path(DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'receta_atencion_quirurgica_anestesiologia_'.$id_receta.'.pdf')); 
          
        $resultado["csv"]=$csv;
        $resultado["sw"]=true;
        
      }catch(\Exception $e) { 
        Log::error('ERROR EN ATENCION QUIRURGICA  FIRMA RECETA PDF: ' . $e->getMessage(), [
          'exception' => $e,
          'trace' => $e->getTraceAsString(),
        ]);
        $resultado["sw"] = false;
        $resultado["msg"] = "Error al generar PDF para firma: " . $e->getMessage();
      }
      return response()->json($resultado); 
    }

    public function recetaPDFfirmarAnestesioSinToken($id_receta){
      $resultado=array();
      try {
        
        $receta = Receta_vale::find($id_receta);
        if($receta->fecha_firma == ""){
          $receta->fecha_firma = date("d-m-Y H:i:s");
          $receta->ruta_receta = date("Y/m/d")."/cf_receta_atencion_quirurgica_".$receta->id.".pdf";
          $receta->flag = 2; //sintoken
          $receta->save();
        }

        $resultado["id_prestacion"] = $receta->id_prestacion;
        $resultado["sw"]=true;
        
      }catch(\Exception $e) { 
        Log::error('ERROR EN ATENCION QUIRURGICA FIRMA ELECTRONICA RECETA PDF: ' . $e->getMessage(), [
          'exception' => $e,
          'trace' => $e->getTraceAsString(),
        ]);
        $resultado["sw"] = false;
        $resultado["msg"] = "Error al generar PDF para firma: " . $e->getMessage();
      }
      return response()->json($resultado); 
    }

    public function recetaGenerarPdfParaFirmaAnestesio($id_receta){
      $path = public_path('tmp/pdf');
      if (!is_dir($path)) {
        mkdir($path);
      }
      $path = public_path('tmp/pdf/sin_firma/');
      if (!is_dir($path)) {
        mkdir($path);
      }
      $model_receta = new \App\Models\Receta_vale;
      $model_farmacia = new Farmacia();
      $model_receta = new Receta_vale;
      $model_productos_receta = new Producto_receta;
      //--------------------------------------------  
      
      $receta = $model_receta->ShowAllById($id_receta);
      $receta = $receta[0];
      $dni_beneficiario = $receta->dni_beneficiario;
      $establecimiento = $model_farmacia->GetEstablecimientoPNP($receta->id_farmacia);
      $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($id_receta);
      
      $productos = $model_receta->ProductosRecetaByIdV3($id_receta);
      $model_receta_diagnostico = new Receta_diagnostico();
      $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdReceta($id_receta);
      
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
      
      $pdf = \PDF::loadView('frontend.centro_quirurgico.anestesiologia.pdf_receta_vale_firmado', 
      compact('medicamentos','receta','establecimiento','productos','diagnosticos'));
      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portrait');
      file_put_contents($path."receta_atencion_quirurgica_anestesiologia_".$id_receta.".pdf", $pdf->output());
    }
    public function recetaPostArgumentsAnestesio(Request $request)
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
    public function recetaActualizarFirmaConTokenAnestesio(Request $request){
      try {
          $id = intval($request->id_resol_receta);
          $receta = \App\Models\Receta_vale::where('id', '=', $id)->first();
          if ($receta && ($receta->fecha_firma === null || $receta->fecha_firma === '')) {
              $receta->fecha_firma = date("d-m-Y H:i:s");
              $receta->ruta_receta = date("Y/m/d") . "/cf_receta_atencion_quirurgica_anestesiologia_" . $id . ".pdf";
              $receta->flag = 1;
              $receta->save();
          }
          return response()->json([
            'status' => 'ok'
          ]);
      } catch (\Throwable $e) {
          Log::error('Error al querer Actualizar Firma Con Token Receta', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
          return response()->json([
              'status' => 'error',
              'message' => $e->getMessage()
          ], 400);
      }
    }
    /******************FIN FIRMA RECETA ATENCION QUIRURGICA ANESTESIOLOGIA*****************/

  /**
   * @param \Illuminate\Http\Request $request Petición con parámetros en query string:
   * - string $tipo_doc_ident         tipo de documento del asegurado
   * - string $nro_doc_ident          numero de documento del asegurado
   * - string $id_establecimiento     id del establecimiento
   * - string $id_servicio            id del servicio
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  function validarIntervencion(Request $request) {
    $tipo_doc_ident = $request->tipo_doc_ident;
    $nro_doc_ident = $request->nro_doc_ident;
    $id_establecimiento = $request->id_establecimiento;
    $id_servicio = $request->id_servicio;
    
    $asegurado = DB::table('asegurados')->where('tipo_doc_ident', $tipo_doc_ident)->where('nro_doc_ident', $nro_doc_ident)->first();
    if (!isset($asegurado) || !isset($asegurado->id)) {
      return response()->json([
        "message" => "no existe asegurado",
        "data" => null
      ], JsonResponse::HTTP_BAD_REQUEST); 
    }

    $intervencion = DB::table('cqx_intervenciones as cqx_int')
        ->join('prestaciones as pr', 'cqx_int.id_prestacion', '=', 'pr.id')
        ->where('cqx_int.id_asegurado', $asegurado->id)
        ->where('cqx_int.id_establecimiento', $id_establecimiento)
        ->where('cqx_int.id_consultorio', $id_servicio)
        ->where('cqx_int.flag', 1)
        ->where('pr.id_tipo_atencion', 1)
        ->select('cqx_int.id', 'cqx_int.id_prestacion', 'cqx_int.fecha_solicitud', 'cqx_int.fecha_probable', 'cqx_int.fecha_probable_intervencion')
        ->first();
    if (!isset($intervencion) || !isset($intervencion->id)) {
      return response()->json([
        "message" => "no existe intervencion quirurgica",
        "data" => null
      ], JsonResponse::HTTP_BAD_REQUEST); 
    }

    //$cita = DB::table('cqx_citas as cc')
    //            ->join('cqx_intervenciones as ci', 'ci.id', '=', 'cc.id_cqx_intervenciones')
    //            ->where('cc.id_cqx_intervenciones', $intervencion->id)
    //            ->where('cc.id_estado_reg', 1)
    //            ->where('ci.id_estado_reg', 1)
    //            ->where('cc.flag', 2)
    //            ->whereNotNull('cc.id_cita')
    //            ->orderBy('cc.id', 'desc')
    //            ->select('cc.id as id_cqx_cita', 'ci.id as id_cqx_intervencion', 'cc.id_cita', 'cc.tipo')
    //            ->first();
    //if (!isset($cita)) {
    //  return response()->json([
    //    "message" => "no existe cita quirurgica",
    //    "data" => null
    //  ], JsonResponse::HTTP_BAD_REQUEST); 
    //}
    
    return response()->json([
      "message" => "validacion exitosa",
      "data" => [
        "cqx_intervencion" => $intervencion,
        //"cqx_cita" => $cita,
      ]
    ], JsonResponse::HTTP_OK); 
  }

  function obtenerIntervencionPorId($id_cqx_intervenciones) {
    $cqxIntervencion = CqxIntervencion::find($id_cqx_intervenciones);
    if (!isset($cqxIntervencion) || empty($cqxIntervencion) || !isset($cqxIntervencion->id)) {
      return response()->json([
        "message" => "no existe intervencion quirurgica",
        "data" => null
      ], JsonResponse::HTTP_BAD_REQUEST); 
    }

    $cqxCitas = CqxCita::where('id_cqx_intervenciones', $cqxIntervencion->id)->get();
    $cqxIntervencion->cqxCitas = isset($cqxCitas) ? $cqxCitas : [];

    $cqxProcedimientos = DB::table('cqx_procedimientos as cp')
                          ->join('procedimientos as p', 'cp.id_procedimiento', '=', 'p.id')
                          ->where('cp.id_cqx_intervenciones', $cqxIntervencion->id)
                          ->select('cp.id as id_cqx_procedimiento', 'p.codigo as procedimiento_codigo', 'p.descripcion as procedimiento')
                          ->get();
    $cqxIntervencion->cqxProcedimientos = isset($cqxProcedimientos) ? $cqxProcedimientos : [];
    
    $cqxFormatoEscaneados = CqxFormatoEscaneado::where('id_intervencion', $cqxIntervencion->id)->get();
    $cqxIntervencion->cqxFormatoEscaneados = isset($cqxFormatoEscaneados) ? $cqxFormatoEscaneados : [];

    $cqxIntervencion->interconsultas = [];
    if (isset($cqxIntervencion->id_prestacion)) {
      $interconsultas = DB::table('interconsultas as i')
                            ->join('consultorios as c', 'i.id_servicio', '=', 'c.id')
                            ->where('i.id_prestacion', 3837909)
                            ->select('i.id as id_interconsulta', 'c.id as id_consultorio', 'c.nombre as consultorio', 'i.motivo_interconsulta')
                            ->get();
      $cqxIntervencion->interconsultas = isset($interconsultas) ? $interconsultas : [];
    }

    return response()->json([
      "message" => "se encontro intervencion quirurgica correctamente",
      "data" => $cqxIntervencion
    ], JsonResponse::HTTP_OK);
  }
  function medicosQuirurgicos(){
    $medicos = DB::table('medicos as m')
    ->join('medico_consultorios as mc', 'm.id', '=', 'mc.id_medico')
    ->join('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
    ->where('c.flag_quirurgico', 1)
    ->select('m.id', 'm.dni', 'm.nombre', 'm.paterno', 'm.materno')
    ->groupBy('m.id', 'm.dni', 'm.nombre', 'm.paterno', 'm.materno')
    ->get();

    return  $medicos;
  }

  public function actualizarTelefono(Request $request)
  {
      $asegurado = Asegurado::find($request->id_asegurado);
      if ($asegurado) {
          $asegurado->telefono = $request->telefono1;
          $asegurado->telefono2 = $request->telefono2;
          $asegurado->updated_at = Carbon::now();
          $asegurado->updated_us = Auth::user()->id;
          $asegurado->save();

          return response()->json(['success' => true]);
      }
      return response()->json(['success' => false]);
  }

  function limpiarTextarea($valor) {
    $valor = trim($valor); 
    $valor = preg_replace('/\r\n|\r|\n/', "\n", $valor);
    $valor = preg_replace('/[ \t]+/', ' ', $valor); 
    return $valor;
  }

  function obtenerIntervencionesNivel2(Request $request) {
    $model_centro_quirurgico = new Centro_quirurgico();

    $offset = ($request->pagina - 1) * $request->cantidad_pagina;
    $limit = $request->cantidad_pagina;

    $id_establecimiento = Auth::user()->id_establecimiento;

    $fecha_solicitud_inicio = null;
    if (isset($request->fecha_solicitud_inicio) && !empty($request->fecha_solicitud_inicio)) {
      $fecha_solicitud_inicio = Carbon::createFromFormat('d-m-Y', $request->fecha_solicitud_inicio)->format('Y-m-d');
    }

    $fecha_solicitud_fin = null;
    if (isset($request->fecha_solicitud_fin) && !empty($request->fecha_solicitud_fin)) {
      $fecha_solicitud_fin = Carbon::createFromFormat('d-m-Y', $request->fecha_solicitud_fin)->format('Y-m-d');
    }

    $p = [];
    $p[0] = $fecha_solicitud_inicio;
    $p[1] = $fecha_solicitud_fin;
    $p[2] = isset($request->nro_doc_ident) && !empty($request->nro_doc_ident) ? $request->nro_doc_ident : null;
    $p[3] = $id_establecimiento;
    $p[4] = isset($request->id_tipo_atencion) && !empty($request->id_tipo_atencion) ? $request->id_tipo_atencion : null;
    $p[5] = isset($request->id_servicio) && !empty($request->id_servicio) ? $request->id_servicio : null;
    $p[6] = isset($request->id_estado) && !empty($request->id_estado) ? $request->id_estado : null;
    $p[7] = isset($request->nombres_apellido) && !empty($request->nombres_apellido) ? $request->nombres_apellido : null;
    $p[8] = $limit;
    $p[9] = $offset;

    $result = $model_centro_quirurgico->obtenerIntervencionesNivel2($p);
    $recordsTotal = isset($result[0]) && isset($result[0]->total_registros) ? $result[0]->total_registros : 0;
    $response = [
        "draw" => intval($request->draw),
        "page" => intval($request->pagina),
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsTotal,
        "data" => isset($result) ? $result : []
    ];

    return response()->json($response);
  }

  function obtenerIntervencionNivel2ById($id, ObtenerIntervencionByIdUseCase $useCase) 
  {
    try {
      $result = $useCase->execute($id);
      return response()->json($result, $result['success'] ? 200 : 500);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Registrar intervención quirúrgica y atenciones adicionales.
   *
   * @param Number $id
   * @param Request $request
   * @param SuspenderCqxIntervencionNivel2UseCase $useCase
   * @return \Illuminate\Http\JsonResponse
   */
  function suspenderIntervencionNivel2($id, Request $request, SuspenderCqxIntervencionNivel2UseCase $useCase) 
  {
    try {
      $data = $request->all();
      $result = $useCase->execute($id, $data);
      return response()->json($result, $result['success'] ? 200 : 500);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  function atenderIntervencionNivel2($id) {
    $medico_model = new Medico();
    $medico = $medico_model->GetMostrarMedicoByDNI(Auth::user()->dni);

    $tiposAnestesia = CqxTipoAnestesia::where('estado', 1)->where('nivel_ipress', 2)->get();
    $tiposProgramacion = CqxTipoCirugia::where('estado', 1)->where('nivel_ipress', 2)->get();

    $upsses = DB::table('upsses as up')
                  ->join('consultorios as co', 'co.id', '=', 'up.id_consultorio')
                  ->select([ 'up.id as id_upss', 'up.descripcion_upss', 'up.codigo as codigo_upss', 'co.estado'])
                  ->where('up.estado', 1)
                  ->where('up.id_tipo_atencion', 3)
                  ->distinct()
                  ->orderBy('up.descripcion_upss')
                  ->get();

    $consultorios = Consultorio::where('flag_quirurgico', 1)->get();
   
    return view('frontend.centro_quirurgico.nivel_2.form_atencion_nivel_2', compact('id', 'medico', 'tiposAnestesia', 'tiposProgramacion', 'upsses', 'consultorios'));
  }

  /**
   * Registrar la atencion en centro quirurgico para nivel 2
   *
   * @param Request $request
   * @param RegistrarAtencionCqxNivel2UseCase $useCase
   * @return \Illuminate\Http\JsonResponse
   */
  function registrarAtencionCqxNivel2(Request $request, RegistrarAtencionCqxNivel2UseCase $useCase)
  {
    try {
      $result = $useCase->execute($request);
      return response()->json($result, $result['success'] ? 200 : 500);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => $e->getMessage(),
      ], 500);
    }  
  }

  /**
   * Registrar la atencion en centro quirurgico para nivel 2
   *
   * @param Number $id
   * @param GenerarPdfByIdUseCase $useCase
   * @return \Illuminate\Http\JsonResponse
   */
  function generarPdfAtencionCqxNivel2($id, GenerarPdfByIdUseCase $useCase)
  {
    try {
      return $useCase->execute($id);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => $e->getMessage(),
      ], 500);
    }  
  }

  /**
   * Registrar la atencion en centro quirurgico para nivel 2
   *
   * @param Number $id
   * @param GenerarPdfAltaCrisisUseCase $useCase
   * @return \Illuminate\Http\JsonResponse
   */
  function generarPdfAltaCrisisCqxNivel2($id, GenerarPdfAltaCrisisUseCase $useCase)
  {
    try {
      return $useCase->execute($id);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    } 
  }

  /**
   * Registrar la atencion en centro quirurgico para nivel 2
   *
   * @param Number $id
   * @param GenerarPdfPapeletaEgresoUseCase $useCase
   * @return \Illuminate\Http\JsonResponse
   */
  function generarPdfPapeletaEgresoCqxNivel2($id, GenerarPdfPapeletaEgresoUseCase $useCase)
  {
    try {
      return $useCase->execute($id);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    } 
  }


  function verDetalleIntervencionNivel2($id, VerDetalleIntervencionNivel2UseCase $useCase) 
  {
    try {
      $data = $useCase->execute($id);
      return view('frontend.centro_quirurgico.nivel_2.form_cq_ver_detalle_nivel_2', $data);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    }
  }

  function altaIntervencionNivel2($id, AltaIntervencionNivel2UseCase $useCase) {
    try {
      return $useCase->execute($id);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Registrar la atencion en centro quirurgico para nivel 2
   *
   * @param Request $request
   * @param RegistrarAltaIntervencionNivel2UseCase $useCase
   * @return \Illuminate\Http\JsonResponse
   */
  function registrarAltaIntervencionNivel2(Request $request, RegistrarAltaIntervencionNivel2UseCase $useCase)
  {
    try {
      $data = $useCase->execute($request);

      return response()->json([
        "success" => true,
        "message" => "Alta registrada correctamente.",
        "data"    => $data
      ], 200);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    }
  }

  /**
   * Registrar la atencion en centro quirurgico para nivel 2
   *
   * @param number $id
   * @param ObtenerAltaIntervencionNivel2UseCase $useCase
   * @return \Illuminate\Http\JsonResponse
   */
  function obtenerAltaIntervencionNivel2($id, ObtenerAltaIntervencionNivel2UseCase $useCase)
  {
    try {
      $data = $useCase->execute($id);

      return response()->json([
        "success" => true,
        "message" => "consulta de alta exitosamente",
        "data"    => $data
      ], 200);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    }
  }
  
    public function vista_mopri_nivel_2()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        $id_establecimiento = Auth::user()->id_establecimiento;

        $date = Carbon::now();
        $fechaHasta = $date->format('d-m-Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
        $fechaDesde = date('d-m-Y', $fechaDesdey);
        
        $dni_beneficiario = '';
        $nombre_beneficiario = '';
        $estado = 0;

        /*==========SECCION DE UPSS HOSPITALIZACION==========*/
        $model_upss = new \App\Models\Consultorio();
        $upss = $model_upss->getServiciosQuirurgicos();
        /*==========SECCION DE UPSS HOSPITALIZACION==========*/
        $medicos_quirurgicos=$this->medicosQuirurgicos();
        return view('frontend.centro_quirurgico.vista_mopri_nivel_2', compact('fechaDesde', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'upss', 'id_user', 'id_establecimiento', 'id_farmacia','medicos_quirurgicos')
        );
    }

     public function listar_mopri_nivel2_ajax(Request $request)
    {
        $model_citas = new Cita();
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = $request->fecha_ini_intervencion;
        $p[] = $request->fecha_fin_intervencion;
        $p[] = $request->dni_beneficiario;
        $p[] = $request->nombre_beneficiario;
        $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
        $p[] = $request->estado;
        $p[] = (isset($request->id_tipo_atencion) && $request->id_tipo_atencion != "") ? $request->id_tipo_atencion : 0;
        $p[] = 76;
        $p[] = !empty($request->id_medico)?$request->id_medico:null;
        $p[] = !empty($request->id_estado_pendiente)?$request->id_estado_pendiente:null;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        //$data = $model_citas->listar_prestacion_intervencionqx_ajax_v2($p);
        $data = $model_citas->listar_prestacion_intervencionqx_ajax_v3($p);
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

  public function medicoDatosAnulacion(Request $request)
  {
      $medico = DB::table('medicos')
                ->leftJoin('especializaciones', 'especializaciones.id', '=', 'medicos.id_especializacion')
                ->leftJoin('tipo_documento_identidad as td', 'td.id', '=', 'medicos.tipo_documento')
                ->leftJoin('profesiones as p', 'p.id', '=', 'medicos.id_profesion')
                ->where('medicos.dni', auth()->user()->dni)
                ->select([
                    'td.descripcion as tipo_documento',
                    'medicos.dni as numero_documento',
                    'medicos.paterno as apellido_paterno',
                    'medicos.materno as apellido_materno',
                    'medicos.nombre as nombres',
                    'p.nombre as profesion',
                    'medicos.colegiatura as num_colegiatura',
                    'medicos.rne',
                    'especializaciones.nombre as especialidad'
                ])->first();

      if (!$medico) {
          return response()->json(['error' => 'Médico no encontrado'], 404);
      }
      
      return response()->json([
          'medico' => [
              'tipo_doc'        => $medico->tipo_documento,
              'num_doc'         => $medico->numero_documento,
              'apellido_paterno'=> $medico->apellido_paterno,
              'apellido_materno'=> $medico->apellido_materno,
              'nombres'         => $medico->nombres,
              'profesion'       => $medico->profesion,
              'colegiatura'     => $medico->num_colegiatura,
              'rne'             => $medico->rne,
              'especialidad'    => $medico->especialidad
          ],
          'fecha' => date("Y-m-d"),
          'hora'  => date("H:i")
      ]);
  }

  public function anularIntervencion(Request $request)    
  {
     // Validación manual
      $validator = Validator::make($request->all(), [
          'id_intervencion_anular' => 'required|integer|exists:cqx_intervenciones,id',
          'motivo_anulacion'       => 'required|string|max:500',
      ]);

      if ($validator->fails()) {
          return response()->json([
              'error' => true,
              'mensaje' => $validator->errors()->all()
          ], 422);
      }

      try {

        DB::beginTransaction();
        $model_medicos = new Medico;
        $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
        $existe = DB::table('medico_consultorios')
          ->where('id_establecimiento', Auth::user()->id_establecimiento)
          ->where('id_medico', $medico->id)
          ->where('estado', 1)
          ->where('id_consultorio', $request->form_id_servicio)
          ->exists();
        $servicio=\App\Models\Consultorio::find($request->form_id_servicio);
        $nombre_servicio = !empty($servicio->nombre)&& !empty($servicio->nombre)?$servicio->nombre:'';

        if (!$existe) {
            // No existe el consultorio para este médico, no se procede a anular
            return response()->json([
                'success' => false,
                'mensaje' => 'No esta permitido anular a ese registro , solo esta permitido a medicos con la especialidad de <strong>'.$nombre_servicio.'</strong>'
            ]);
        }

        $idIntervencion = $request->input('id_intervencion_anular');
        $motivo = $request->input('motivo_anulacion');

        //SI HAY PRESTACIONES DE ALGUN SERVIVICIO DE NEUMO,CARDIO O ANESTESIO
        //$existePrestacion = DB::table('cqx_citas')
        //    ->where('id_cqx_intervenciones', $idIntervencion)
        //    ->where('id_estado_reg', 1)
        //    ->where('id_prestacion', '>', 0)
        //    ->whereIn('id_consultorio', [63, 27, 72])
        //    ->exists();

        //if ($existePrestacion) {
        //    DB::rollBack();
        //    return response()->json([
        //        'success' => false,
        //        'mensaje' => 'No se puede anular. La intervención tiene otras <strong>prestaciones</strong> relacionadas <strong>Neumología, Cardiología o Anestesiología</strong>'
        //    ]);
        //}

        //CITAS SIN PRESTAIONES
        $citasLibres = collect(
            DB::table('cqx_citas')
                ->where('id_cqx_intervenciones', $idIntervencion)
                ->where('id_estado_reg', 1)
                ->where('id_prestacion',0)
                ->Where('id_cita', '<>',0)
                ->get()
        );

      
        if (!$citasLibres->isEmpty()) {

            $idsCitasCqx = $citasLibres->pluck('id')->toArray();

            DB::table('cqx_citas')
                ->whereIn('id', $idsCitasCqx)
                ->update([
                    'id_estado_reg' => 0,
                    'id_prestacion' => 0,
                    'id_cita' => 0,
                    'flag' => 1,
                ]);
            
            $idsCitas = $citasLibres
                ->filter(function ($cita) {
                    return !is_null($cita->id_cita) &&
                          $cita->id_cita != 0 &&
                          $cita->id_cita != '0';
                })
                ->pluck('id_cita')
                ->toArray();

            if (!empty($idsCitas)) {
                DB::table('citas')
                    ->whereIn('id', $idsCitas)
                    ->update([
                        'id_estado_reg' => 0,
                        'id_estado_cita' => 5,
                    ]);
            }
        }

        DB::table('cqx_intervenciones')
            ->where('id', $idIntervencion)
            ->update([
                'id_estado_reg'   => 0,
                'flag'   => 0,
                'motivo_anulacion' => $motivo,
                'fecha_anulacion'  => date('Y-m-d'),
                'hora_anulacion'   => date('H:i:s'),
                'usuario_anulo'    => Auth::user()->id,
            ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'mensaje' => 'Intervención anulada correctamente.'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'error' => true,
            'mensaje' => 'Ocurrió un error al intentar anular la intervención: ' . $e->getMessage()
        ], 500);
    }


  }

  public function buscarDatosProgramacion(Request $request)
    {
        $id = $request->id_intervencion;
        $data = DB::table('cqx_intervenciones as ci')
        ->join('cqx_citas as c', 'ci.id', '=', 'c.id_cqx_intervenciones')
        ->select(
            'c.id',
            'c.id_cita',
            'c.id_prestacion as id_prestacion_programacion',
            'ci.emergencia_tipo_cirugia as id_tipo_cirugia',
            'ci.id_prioridad_programacion as turno',
            'ci.is_requiere_internamiento_preqx',
            'ci.id_tiempo_internamiento_preqx',
            'ci.is_hospitalizacion_postqx',
            'ci.id_tiempo_hospitalizacion_postqx',
            'ci.id_lugar_operacion',
            'c.fecha_probable',
            'ci.hora_inicio_prog_qx',
            'ci.hora_fin_prog_qx',
            'ci.emergencia_duracion_cirugia',
            'ci.fecha_solicitud'
        )
        ->where('ci.id', $id)
        ->where('c.is_programacion_quirurgica', true)
        ->orderBy('c.id', 'DESC')
        ->first();
        $cqx_cita_profesionales=null;
        $cqx_cita_procedimientos=null;
        $model_cqx_cita = new \App\Models\CqxCita;
        $data_cqx_cita = $model_cqx_cita->GetCitaByIdPrestacion($data->id_prestacion_programacion);
        if ($data_cqx_cita != null) {
          $model_cqx_profesional = new \App\Models\CqxProfesional();
          $cqx_cita_profesionales = $model_cqx_profesional->GetByIdCqxIdCita($data_cqx_cita->id,$data_cqx_cita->id_cita);
          $model_cqx_procedimiento = new \App\Models\CqxProcedimiento();
          $cqx_cita_procedimientos = $model_cqx_procedimiento->GetByIdCqxIdCita($data_cqx_cita->id,$data_cqx_cita->id_cita);
        }

        $formatosMopri = CqxFormatoEscaneado::where('id_intervencion', $id)
        ->where('estado', 1)
        ->first();


       return response()->json([
          'programacion'   => $data,
          'cirujanos'      => $cqx_cita_profesionales,
          'procedimientos' => $cqx_cita_procedimientos,
          'formatos_mopri' =>$formatosMopri,
      ]);
    }

    public function eliminarFormatoMopri(Request $request)
    {
        $formato = CqxFormatoEscaneado::find($request->id);
        if (!$formato) {
            return response()->json(['success' => false, 'message' => 'Formato no encontrado']);
        }
        $formato->estado = 0;
        $formato->updated_us = Auth::user()->id;
        $formato->save();

        return response()->json(['success' => true]);
    }

    public function eliminarProcedimiento(Request $request)
    {
        $id = $request->id_procedimiento;
        try {
            DB::table('cqx_procedimientos')
                ->where('id', $id)
                ->update([
                    'id_estado_reg' => 0,
                    'updated_at' => Carbon::now(),
                    'updated_us'=>Auth::user()->id
                ]);

            return response()->json([
                'status' => 'ok',
                'message' => 'Eliminado correctamente'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar'
            ]);
        }
    }

    public function eliminarProfesional(Request $request)
    {
        $id = $request->id_cirujano;
        try {

            DB::table('cqx_profesionales')
                ->where('id', $id)
                ->update([
                    'estado' => 3,//estado eliminado
                    'updated_at' => Carbon::now(),
                    'deleted_at'=> Carbon::now(),
                    'deleted_us'=> Auth::user()->id
                ]);

            return response()->json([
                'status' => 'ok',
                'message' => 'Profesional eliminado'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar'
            ]);
        }
    }

    public function actualizarSuspensionGeneral(Request $request, $id_intervencion, $id_prestacion)
    {
      
        DB::beginTransaction();

        try {
            $accion  = $request->get('accion'); 
            $accion2  = $request->get('accion2');
            $flag_qx = (int) $request->get('flag_qx');    // 2 | 3
            $id_consultorio_suspension = (int) $request->get('id_consultorio_suspension'); 
            $msg="";

            // parseo seguro de fecha/hora
            try {
                // $fechaHora = Carbon::createFromFormat('d-m-Y H:i', trim($request->get('fecha_hora_registro')));
                // $fecha_hora_egreso = null;
                // if ($request->has('fecha_hora_egreso')) {
                //     $egreso = trim($request->get('fecha_hora_egreso'));
                //     if ($egreso !== '' && strtolower($egreso) !== 'null') {
                //         $fecha_hora_egreso = Carbon::createFromFormat('d-m-Y H:i', $egreso);
                //     }
                // }

                // ===== FECHA / HORA REGISTRO =====
                $fhRegistro = trim($request->get('fecha_hora_registro'));
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $fhRegistro)) {
                    $fechaHora = Carbon::createFromFormat('d-m-Y H:i', $fhRegistro . ' 00:00');
                } else {
                    $fechaHora = Carbon::createFromFormat('d-m-Y H:i', $fhRegistro);
                }
                // ===== FECHA / HORA EGRESO =====
                $fecha_hora_egreso = null;
                if ($request->has('fecha_hora_egreso')) {
                    $egreso = trim($request->get('fecha_hora_egreso'));
                    if ($egreso !== '' && strtolower($egreso) !== 'null') {
                        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $egreso)) {
                            $fecha_hora_egreso = Carbon::createFromFormat('d-m-Y H:i', $egreso . ' 00:00');
                        } else {
                            $fecha_hora_egreso = Carbon::createFromFormat('d-m-Y H:i', $egreso);
                        }
                    }
                }
            } catch (\Exception $ex) {
                Log::error('Error Formato de fecha/hora inválido', [
                    'error' =>$ex->getMessage(),
                    'trace' =>$ex->getTraceAsString(),
                    'request' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Formato de fecha/hora inválido'
                ], 422);
            }

            $model_medicos = new Medico;
            $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);

            $tiene_anestesiologia = DB::table('medico_consultorios')
                ->where('id_establecimiento', Auth::user()->id_establecimiento)
                ->where('id_medico', $medico->id)
                ->where('estado', 1)
                ->where('id_consultorio', 72)
                ->exists();

            $tiene_consultorio_solicitado = DB::table('medico_consultorios')
                ->where('id_establecimiento', Auth::user()->id_establecimiento)
                ->where('id_medico', $medico->id)
                ->where('estado', 1)
                ->where('id_consultorio', $id_consultorio_suspension)
                ->exists();
            // $existe = DB::table('medico_consultorios')
            //   ->where('id_establecimiento', Auth::user()->id_establecimiento)
            //   ->where('id_medico', $medico->id)
            //   ->where('estado', 1)
            //   ->where('id_consultorio', $id_consultorio_suspension)
            //   ->exists();
            $servicio=\App\Models\Consultorio::find($id_consultorio_suspension);
            $nombre_servicio = !empty($servicio)&& !empty($servicio->nombre)?$servicio->nombre:'';
            
            if (!$tiene_anestesiologia && !$tiene_consultorio_solicitado) {
                // No existe el consultorio para este médico, no se procede a anular o sea diferente al servicio de anestesiologia
                return response()->json([
                'success' => false,
                'message' => 'No cuenta con la especialidad  de <strong>'.$nombre_servicio.'</strong>'
                ], 422);
            }         

            $id_prestacion_programacion = $id_prestacion;
            $interv = CqxIntervencion::findOrFail($id_intervencion);

            $cqxcita = CqxCita::where('id_cqx_intervenciones', $id_intervencion)
                        ->where('id_prestacion', $id_prestacion_programacion)
                        ->first();

            // ---------- FLAG 2 (centro quirurgico) ----------
            if ($flag_qx === 2) {
                if ($accion === 'reprogramacion') {
                    $this->reeprogramacion($request, $cqxcita, $id_intervencion, $interv);
                    $cqxcita->id_lugar_operacion=$request->get('id_lugar_operacion');
                    $cqxcita->save();
                }

                $interv->update([
                    'updated_us' => Auth::user()->id,
                    'updated_at' => Carbon::now(),
                    'flag' => ($accion === 'reevaluacion') ? 1 : 2,
                ]);

                // Insert suspension (usar Eloquent si tienes modelo cqx_suspensiones)
                DB::table('cqx_suspensiones')->insert([
                    'id_intervencion' => $id_intervencion,
                    'fecha_hora' => $fechaHora,
                    'tipo' => $accion,
                    'observacion_reevaluar' => $accion === 'reevaluacion' ? $request->get('motivo_reevaluar') : null,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'fecha_probable_anterior' => ($accion === 'reprogramacion') ? $request->get('fecha_probable_pq_anterior') : null,
                    'id_prestacion_programacion'=>$id_prestacion_programacion,
                    'fecha_hora_egreso'=>$fecha_hora_egreso
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'La actualización se registró correctamente.'
                ]);
            }

            // ---------- FLAG 3 (hospitalizacion) ----------
            if ($flag_qx === 3) {
                // Exigir que la intervención tenga hospitalización asociada
                if (!$interv->id_hospitalizacion) {
                  $msg="El paciente no se encuentra admisionado no se mostrara la papeleta de egreso";
                   if ($accion2 === 'reprogramacion') {
                      CqxIntervencion::where('id', $id_intervencion)->update(['is_validated' => false,'flag'=>2,'is_suspended' => true]);
                      $this->reeprogramacion($request, $cqxcita, $id_intervencion, $interv);
                    }
                    // return response()->json([
                    //     'success' => false,
                    //     'message' => 'Para realizar esta operación el paciente debe estar admisionado en hospitalización.'
                    // ], 422);
                }
                if (!empty($interv->id_hospitalizacion)) {

                  $hospitalizacion = Hospitalizacione::find($interv->id_hospitalizacion);
                  if (!$hospitalizacion) {
                      return response()->json([
                          'success' => false,
                          'message' => 'Registro de hospitalización no encontrado.'
                      ], 404);
                  }
                  if ($accion2 === 'reprogramacion') {
                      CqxIntervencion::where('id', $id_intervencion)->update(['is_validated' => false,'flag'=>2,'is_suspended' => true]);
                      $this->reeprogramacion($request, $cqxcita, $id_intervencion, $interv);
                  }
                  /*
                    EN LA ADMISION DE HOSPITALIZACION SE CAMBIA EL ESTADO DE LA TABLA HOSPITALIZACION_ORDENES EL CAMPO FLAG A "1" Y 
                    EN LA TABLA HOSPITALIZACION EL ESTADO SE CAMBIA A "0".
                  */
                  //$nuevoEstado = ($hospitalizacion->estado === 1) ? 0 : 1;
                  // $hospitalizacion->update(['estado' => 0,'id_condicion_alta'=>4,'suspension_user'=>Auth::user()->id]);
                  // Solo si id_cama_estado es null cambia el estado de lo contrario no debe cambiar el estado
                  if ($hospitalizacion) {
                      $data = [
                          'id_condicion_alta' => 4,
                          'suspension_user'   => Auth::user()->id
                      ];
                      if (empty($hospitalizacion->id_cama_estado)) {//si viene 0, "0", null, false y ''
                          $data['estado'] = 0;
                          // Actualizar ordenes relacionadas (si corresponde actualizar muchas filas, esto lo hará)
                          HospitalizacionOrden::where('id_ext', $interv->id)->update(['flag' => 0]);
                      }
                      $hospitalizacion->update($data);
                  }
                  
                }
                //Esta parte es para reevaluacion y reeprogramacion
                //Esta parte es solo para reevaluacion
                if ($accion2 === "reevaluacion") {
                    $interv->update([
                        'is_suspended' => true,
                        'is_validated' => false,
                        'suspended_motivo' => $request->get('observacion_suspension'),
                        'updated_us' => Auth::user()->id,
                        'suspended_us'=>Auth::user()->id,
                        'updated_at' => Carbon::now(),
                        'suspended_at' => Carbon::now(),
                        'flag' => 1
                    ]);
                }
                //Fin Esta parte es para reevaluacion y reeprogramacion

                DB::table('cqx_suspensiones')->insert([
                    'id_intervencion' => $id_intervencion,
                    'fecha_hora' => $fechaHora,
                    'tipo' => $accion2,
                    'is_suspension' => true,
                    'motivo_de_suspension' => $request->get('motivo_suspension') ?: '',
                    'otro_detalle' => $request->get('motivo_otro') ?: '',
                    'observacion_suspension' => $request->get('observacion_suspension') ?: '',
                    'observacion_reevaluar' => $accion2 === 'reevaluacion' ? $request->get('motivo_reevaluar') : null,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'fecha_probable_anterior' => ($accion2 === 'reprogramacion') ? $request->get('fecha_probable_pq_anterior') : null,
                    'id_prestacion_programacion'=>$id_prestacion_programacion,
                    'fecha_hora_egreso'=>$fecha_hora_egreso
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => !empty($msg)?$msg:'Actualización realizada correctamente. Debe volver a Admisión para registrar la nueva programación.'
                ]);
            }

            // Si flag_qx no es 2 ni 3
            return response()->json([
                'success' => false,
                'message' => 'Operación inválida.'
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error actualizarSuspensionGeneral', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reeprogramacion($request,$cqxcita,$id_intervencion,$interv){
    $cirujanos = json_decode($request->get('cirujanos') ?: '[]', true);
    $procedimientos = json_decode($request->get('procedimientos') ?: '[]', true);
    $id_cqx_cita= $request->get('id_cqx_cita');
      if ($cqxcita) {
          $cqxcita->update([
              'fecha_probable' => $request->get('fecha_probable_pq'),
              'updated_us' => Auth::user()->id,
              'updated_at' => Carbon::now(),
          ]);
      }
      if ($id_cqx_cita==null) {
        $id_cqx_cita= 0 ;
      }
      $interv->update([
          'emergencia_tipo_cirugia' => $request->get('id_tipo_cirugia'),
          'id_prioridad_programacion' => $request->get('id_prioridad_programacion'),
          'is_requiere_internamiento_preqx' => $request->get('valueToogleRequiereInternPreQx_pq'),
          'id_tiempo_internamiento_preqx' => $request->get('id_tiempo_internamiento_preqx'),
          'is_hospitalizacion_postqx' => $request->get('valueToogleDiasHospitPostQx_pq'),
          'id_tiempo_hospitalizacion_postqx' => $request->get('id_tiempo_hospitalizacion_postqx'),
          'id_lugar_operacion' => $request->get('id_lugar_operacion'),
          'emergencia_duracion_cirugia' => $request->get('tiempo_curacion_cqx'),
          'hora_inicio_prog_qx' => $request->get('hora_inicio_pq'),
          'hora_fin_prog_qx' => $request->get('hora_fin_pq'),
          'fecha_probable' => $request->get('fecha_probable_pq'),
          'updated_us' => Auth::user()->id,
          'updated_at' => Carbon::now(),
      ]);
      
      foreach ($cirujanos as $c) {

          $idExistente = isset($c['id_cirujano_existente']) ? $c['id_cirujano_existente'] : null;

          if (!empty($idExistente)) {
              DB::table('cqx_profesionales')
                  ->where('id_medico', $idExistente)
                  ->update([
                      'id_medico' => $c['cqx_profesional'],
                      'id_tipo_professional' => $c['cqx_tipo_professional'],
                      'flag_validacion'=>0,
                      'updated_us' => Auth::user()->id,
                      'updated_at' => Carbon::now(),
                  ]);

          } else {
              DB::table('cqx_profesionales')->insert([
                  'id_cqx_intervenciones' => $id_intervencion,
                  'id_cqx_cita'=>$id_cqx_cita,
                  'id_medico' => $c['cqx_profesional'],
                  'flag_validacion'=>0,
                  'flag_personal'=>0,//cirujanos
                  'estado'=>0,
                  'id_tipo_professional' => $c['cqx_tipo_professional'],
                  'created_us' => Auth::user()->id,
                  'created_at' => Carbon::now(),
              ]);
          }
      }
      foreach ($procedimientos as $p) {

          $idProcExistente = isset($p['id_procedimiento_existente']) ? $p['id_procedimiento_existente'] : null;

          if (!empty($idProcExistente)) {
              DB::table('cqx_procedimientos')
                  ->where('id', $idProcExistente)
                  ->update([
                      'id_procedimiento' => $p['cqx_procedure_id_pq'],
                      'updated_us' => Auth::user()->id,
                      'updated_at' => Carbon::now(),
                  ]);
          } else {
              DB::table('cqx_procedimientos')->insert([
                  'id_cqx_intervenciones' => $id_intervencion,
                  'id_cqx_cita'=>$id_cqx_cita,
                  'id_procedimiento' => $p['cqx_procedure_id_pq'],
                  'flag_pre_post'=>1,
                  'created_us' => Auth::user()->id,
                  'created_at' => Carbon::now(),
              ]);
          }
      }
      if ($request->hasFile('fileMopri_pq')) {
          foreach ($request->file('fileMopri_pq') as $file) {
              $this->guardarArchivoMopri($file, $interv);
          }
      }
    }

    private function guardarArchivoMopri($file, $intervencion)
    {
      $timestamp = Carbon::now()->format('YmdHis');
      $uniqueId = uniqid();
      $extension = $file->getClientOriginalExtension();
      $original_name = $file->getClientOriginalName();
      $size = $file->getSize();
      $tipoDocumento = $file->getClientMimeType();

      $directorio = 'formatos_escaneados';
      $nombreArchivo = "mopri_{$timestamp}_{$uniqueId}." . strtolower($extension);
      $rutaRelativa = $directorio . '/' . $nombreArchivo;

      if (!Storage::disk('local')->exists($directorio)) {
        Storage::disk('local')->makeDirectory($directorio);
      }

      Storage::disk('local')->put($rutaRelativa, file_get_contents($file->getRealPath()));

      CqxFormatoEscaneado::create([
        'id_tipo_formato' => 2,
        'id_intervencion' => $intervencion->id,
        'id_asegurado' => $intervencion->id_asegurado,
        'tipo_documento' => $tipoDocumento,
        'tamano_archivo' => $size,
        'fecha_creacion' => Carbon::now()->toDateString(),
        'hora_creacion' => Carbon::now()->toTimeString(),
        'created_us' => Auth::user()->id,
        'created_at' => Carbon::now()->toDateString(),
        'estado' => 1,
        'ruta' => $directorio . '/',
        'nombre_archivo' => $nombreArchivo,
        'flag_origen_escaneado' => 1,
        'nombre_simple' => $original_name
      ]);
    }

  function firmarPdfIntervencionNivel2($id, FirmarPdfIntervencionNivel2UseCase $useCase) 
  {
    try {
      $resultado = $useCase->execute($id);

      return response()->json($resultado);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage() ?: "Error inesperado en el servidor."
      ], 500);
    }
  }

  function firmarPdfAltaIntervencionNivel2($id, FirmarPdfAltaIntervencionNivel2UseCase $useCase) 
  {
    try {
      $resultado = $useCase->execute($id);

      return response()->json($resultado);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage() ?: "Error inesperado en el servidor."
      ], 500);
    }
  }
   
  function confirmarFirmaPdfIntervencionNivel2(Request $request, ConfirmarFirmaPdfIntervencionNivel2UseCase $useCase)
  {
    try {
      $result = $useCase->execute($request);

      return response()->json([
        "success" => true,
        "message" => "Los datos de la firma se actualizaron correctamente.",
        "data" => $result
      ], JsonResponse::HTTP_OK);
      
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    }
  }

  function confirmarFirmaAltaIntervencionNivel2(Request $request, ConfirmarFirmaAltaIntervencionNivel2UseCase $useCase)
  {
    try {
      $result = $useCase->execute($request);

      return response()->json([
        "success" => true,
        "message" => "Los datos de la firma se actualizaron correctamente.",
        "data" => $result
      ], JsonResponse::HTTP_OK);
      
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    }
  }

    /**
   * Registrar la atencion en centro quirurgico para nivel 2
   *
   * @param Number $id
   * @param GeneraPdfAdmisionHospitalizacionUseCase $useCase
   * @return \Illuminate\Http\JsonResponse
   */
  function generaPdfAdmisionHospitalizacion($id, GeneraPdfAdmisionHospitalizacionUseCase $useCase)
  {
    try {
      return $useCase->execute($id);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    } 
  }


  /**
   * Registrar la atencion en centro quirurgico para nivel 2
   *
   * @param Request $request
   * @param GenerarReporteNivel2UseCase $useCase
   */
  function generarReporteNivel2(Request $request, GenerarReporteNivel2UseCase $useCase)
  {
    try {
      $useCase->execute($request);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    } 
  }

  
  public function validarIntervencionAltaPendienteEmergencia(Request $request)
  {
    $id_emergencia = $request->id_emergencia;
    $id_prestacion = $request->id_prestacion;

    $resultado = DB::table('interconsultas as i')
      ->join('cqx_intervenciones as ci', 'i.id', '=', 'ci.id_interconsulta')
      ->join('cqx_tipo_cirugias as tc', 'ci.emergencia_tipo_cirugia', '=', 'tc.id')
      ->select(
          'ci.id as id_intervencion',
          'ci.pendiente_alta_emergencia',
          'ci.id_prioridad_programacion as turno',
          // duración en HH:MM
          DB::raw("
              LPAD(FLOOR(ci.emergencia_duracion_cirugia / 60)::text, 2, '0')
              || ':' ||
              LPAD((ci.emergencia_duracion_cirugia % 60)::text, 2, '0')
              AS duracion
          "),
          // descripción del tipo de cirugía
          'tc.descripcion as tipo_cirugia',
          // clasificación de cirugía
          DB::raw("
              CASE ci.id_clasificacion_cirugia
                  WHEN 1 THEN 'CIRUGIA MAYOR'
                  WHEN 2 THEN 'CIRUGIA MENOR'
                  WHEN 3 THEN 'CIRUGIA MEDIANA'
                  ELSE 'SIN CLASIFICAR'
              END AS clasificacion_cirugia
          ")
      )
      ->where('i.id_ext', $id_emergencia)
      ->where('ci.id_prestacion', $id_prestacion)
      ->first();

    /* =============================
      Inicializar variables
    ============================= */
    $diagnosticos_cqx = [];
    $procedimientos_cqx_emergencia = [];
    $profesionales_quirugicos = [];

    if ($resultado) {

        $id_intervencion_quirurgica = $resultado->id_intervencion;
        /* ==== CIRUJANOS ==== */
        $model_cq_profesional = new CqxProfesional();
        $profesionales_quirugicos = $model_cq_profesional->GetByIdIntervencionQuirugicaYTipoPersonal($id_intervencion_quirurgica,0);
        /* ==== DIAGNÓSTICOS ==== */
        $diagnosticos_cqx = DB::table('receta_diagnosticos as rd')
            ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            ->select(
                'd.nombre as descripcion_diagnostico',
                'd.codigo as codigo_diagnostico',
                'rd.id_tipo_diagnostico',
                'td.descripcion as descripcion_tipo_diagnosticos'
            )
            ->where('rd.id_intervencion', $id_intervencion_quirurgica)
            ->where(function ($q) use ($id_prestacion) {
                $q->where('rd.id_prestacion', $id_prestacion)
                  ->orWhereNull('rd.id_prestacion');
            })
            ->get();
        /* ==== PROCEDIMIENTOS ==== */
        $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
        $procedimientos_cqx_emergencia = $model_cq_procedimiento->GetByIdIntervencionQuirugica($id_intervencion_quirurgica);
    }
    return response()->json([
        'existe' => $resultado ? true : false,
        'id_intervencion'=>!empty($resultado->id_intervencion)?$resultado->id_intervencion:null,
        'turno'=>!empty($resultado->turno)?$resultado->turno:null,
        'duracion'=>!empty($resultado->duracion)?$resultado->duracion:null,
        'tipo_cirugia'=>!empty($resultado->tipo_cirugia)?$resultado->tipo_cirugia:null,
        'clasificacion_cirugia'=>!empty($resultado->clasificacion_cirugia)?$resultado->clasificacion_cirugia:null,
        'pendiente_alta_emergencia' => $resultado ? $resultado->pendiente_alta_emergencia : false,
        'diagnosticos_cqx' => !empty($diagnosticos_cqx)?$diagnosticos_cqx:null,
        'procedimientos_cqx_emergencia' => !empty($procedimientos_cqx_emergencia)?$procedimientos_cqx_emergencia:null,
        'profesionales_quirugicos' => !empty($profesionales_quirugicos)?$profesionales_quirugicos:null,
    ]);
  }
}

