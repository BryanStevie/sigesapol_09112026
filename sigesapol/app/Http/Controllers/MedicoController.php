<?php

namespace App\Http\Controllers;

use App\Models\Medicos_especialidades;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Especializacione;
use App\Models\Establecimiento;
use App\Models\Medico;
use App\Models\Medico_turno;
use App\Models\Medico_consultorio;
use App\Models\Referencia;
use App\Models\Consultorio;
use App\Models\Receta_medico;
use App\Models\Receta_vale;
use App\Models\User;
use App\Models\Emergencia;
use App\Models\Prestaciones;
use App\Models\Hospitalizacione;
use App\Models\TipoAtencione;
use App\Models\Cama;
use App\Models\Alta_medica_anulada;
use App\Helpers\Util;
use App\Models\Colegio_profesionale;
use App\Models\Tipo_documento_identidad;
use App\Models\Receta_diagnostico;
use App\Models\Producto;
use App\Models\Asegurado_historia;

use SoapClient;
use Session;
use Carbon\Carbon;
use DB;
use App;
use App\Http\Requests\AltaFormRequest;
use App\Models\Asegurado;
use App\Models\Cita;
use App\Models\Gestacion;
use App\Models\Puerperio;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

require_once 'funciones/function.php';

class MedicoController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    } 

  public function update_medico_saenz(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;

    $update = Medico::where('id', '=', $request->id_medico)->first();
    $especialidades = json_decode($request->input('especialidades_json'), true);
    
    $update->condicion = $request->condicion;
    $update->id_grado = $request->id_grado ?: null;
    $update->cip = $request->txtNroCip ?: null;

    $update->paterno = mb_strtoupper($request->paterno);
    $update->materno = mb_strtoupper($request->materno);
    $update->nombre  = mb_strtoupper($request->nombre);

    $update->fecha_nacimiento = $request->fechaNacimiento ?: null;
    $update->id_estado_civil = $request->id_estadocivil ?: null;
    $update->nro_hijos = $request->nro_hijos ?: null;
    $update->sexo = $request->id_sexo ?: null;
    $update->celular = $request->txtCelular ?: null;
    $update->cel_emergencia = $request->txtEmergencia ?: null;

    $update->email = mb_strtoupper($request->email);
    $update->email_institucional = mb_strtoupper($request->email_inst);
    $update->domicilio = mb_strtoupper($request->txtDomicilio);

    $update->id_departamento = $request->id_departamento ?: null;
    $update->id_provincia = $request->id_provincia ?: null;
    $update->id_distrito = $request->id_distrito ?: null;

    $update->grupo_trabajo = $request->grupoTrabajo;
    $update->fecha_inicio = $request->txtInicioLabo ?: null;
    $update->fecha_cese = $request->txtCeseLab ?: null;

    $update->id_situacion_personal = $request->id_situacion_actual ?: null;
    $update->id_condicion_laboral = $request->id_condicion_laboral ?: null;
    $update->cargo = mb_strtoupper($request->cargo);

    $update->id_establecimiento = $request->id_establecimiento ?: null;
    $update->id_unidad = $request->id_unidad ?: null;
    $update->id_sub_unidad = $request->id_sub_unidad ?: null;

    $update->id_profesion = null;
    $update->especialidad = null;
    $update->id_especializacion = null;
    $update->id_colegio_profesional = null;
    $update->colegiatura = null;
    $update->rne = null;
    $update->estado_colegiatura = null;
    $update->fecha_inicio_habilidad = null;
    $update->fecha_termino_habilidad = null;

    if (!empty($especialidades)) {
      foreach ($especialidades as $esp) {
        if ($esp['especialidadPrincipal'] == true) {
            $update->id_profesion = $esp['id_profesion'] ? $esp['id_profesion'] : null;
            $update->especialidad = !empty($esp['nombre_especialidad']) ? $esp['nombre_especialidad'] : null;
            $update->id_especializacion = $esp['id_especialidad'] ? $esp['id_especialidad'] : null;
            $update->id_colegio_profesional = !empty($esp['id_colegio_profesional']) ? $esp['id_colegio_profesional'] : null;
            $update->colegiatura = !empty($esp['colegiatura']) ? $esp['colegiatura'] : null;
            $update->rne = !empty($esp['rne']) ? $esp['rne'] : null;
            
            $idColegiatura = ($esp['estado_colegiatura'] === "")
                             ? null : (($esp['estado_colegiatura'] === 'HABILITADO' || $esp['estado_colegiatura'] == 1) ? 1 : 0);
            
            $update->estado_colegiatura = $idColegiatura;

            $update->fecha_inicio_habilidad = isset($esp['fecha_inicio_habilidad']) && $esp['fecha_inicio_habilidad'] !== '' ? $esp['fecha_inicio_habilidad'] : null;
            $update->fecha_termino_habilidad = isset($esp['fecha_termino_habilidad']) && $esp['fecha_termino_habilidad'] !== '' ? $esp['fecha_termino_habilidad'] : null;
            break;
        }
      }
    }
    
    $update->estado = 1;
    $update->id_user_update = $id_user;
    $update->actualiza_datos_alerta = 1;

    if ($request->hasFile('archivo_firma')) {
      $imagen = $request->file('archivo_firma');
      $nombreimagen = Str::slug($update->dni).".".$imagen->guessExtension();
      $ruta = public_path("images/profesionales/");
      $imagen->move($ruta,$nombreimagen);
      $update->nombre_firma = $nombreimagen;
    }
    $update->save();

    $medico_turno = new Medico_turno();
    $medico_turno->DesactivaTurnoMedicoOtrasIpress($request->id_medico, $request->id_establecimiento, date('Y-m-d'));

    if (!empty($especialidades)) {
      foreach ($especialidades as $esp) {
        if (!empty($esp['id'])) {
          $medicos_especialidades = Medicos_especialidades::find($esp['id']);

          if ($medicos_especialidades) {
            $medicos_especialidades->id_profesion            = $esp['id_profesion'];
            $medicos_especialidades->id_especialidad         = $esp['id_especialidad'];
            $medicos_especialidades->id_estado_reg           = $esp['id_estado_reg'];
            $medicos_especialidades->id_colegio_profesional  = $esp['id_colegio_profesional'];

            $idColegiatura = ($esp['estado_colegiatura'] === "") ? null : ($esp['estado_colegiatura']);
            $medicos_especialidades->estado_colegiatura      = $idColegiatura;

            $medicos_especialidades->colegiatura             = !empty($esp['colegiatura']) ? $esp['colegiatura'] : null;
            $medicos_especialidades->rne                     = !empty($esp['rne']) ? $esp['rne'] : null;
            $medicos_especialidades->fecha_inicio_habilidad  = isset($esp['fecha_inicio_habilidad']) && $esp['fecha_inicio_habilidad'] !== '' ? $esp['fecha_inicio_habilidad'] : null;
            $medicos_especialidades->fecha_termino_habilidad = isset($esp['fecha_termino_habilidad']) && $esp['fecha_termino_habilidad'] !== '' ? $esp['fecha_termino_habilidad'] : null;
            $medicos_especialidades->updated_us              = $id_user;
            $medicos_especialidades->save();
          }
        }
        else {
          if ($esp['id_estado_reg'] == 1) {
            $medicos_especialidades = new Medicos_especialidades();
            $medicos_especialidades->id_medico               = $update->id;
            $medicos_especialidades->id_profesion            = $esp['id_profesion'];
            $medicos_especialidades->id_especialidad         = $esp['id_especialidad'];
            $medicos_especialidades->id_estado_reg           = $esp['id_estado_reg'];
            $medicos_especialidades->id_colegio_profesional  = $esp['id_colegio_profesional'];

            $idColegiatura = ($esp['estado_colegiatura'] == "HABILITADO")
                             ? $esp['id_estado_colegiatura'] : (($esp['estado_colegiatura'] == "INHABILITADO")
                             ? 0 : $esp['estado_colegiatura']);

            $medicos_especialidades->estado_colegiatura      = $idColegiatura;
            
            $medicos_especialidades->colegiatura             = !empty($esp['colegiatura']) ? $esp['colegiatura'] : null;
            $medicos_especialidades->rne                     = !empty($esp['rne']) ? $esp['rne'] : null;
            $medicos_especialidades->fecha_inicio_habilidad  = isset($esp['fecha_inicio_habilidad']) && $esp['fecha_inicio_habilidad'] !== '' ? $esp['fecha_inicio_habilidad'] : null;
            $medicos_especialidades->fecha_termino_habilidad = isset($esp['fecha_termino_habilidad']) && $esp['fecha_termino_habilidad'] !== '' ? $esp['fecha_termino_habilidad'] : null;
            $medicos_especialidades->created_us              = $id_user;
            $medicos_especialidades->save();
          }
        }
      }
    }

    $medico_consultorio = new Medico_consultorio();
    $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($request->id_establecimiento, $request->id_medico);
    $existe = "no";

    if (!is_null($request->id_consultorio)) {
      foreach ($request->id_consultorio as $key => $value) {
        $existe = "no";
        foreach ($consultoriomedico as $rowConsul) :
          if ($request->id_consultorio[$key] == $rowConsul->id_consultorio) {
            $existe = "si";
          }
        endforeach;
        if ($existe == "no") {
          $nuevoconsultorio = new Medico_consultorio;
          $nuevoconsultorio->id_establecimiento = $request->id_establecimiento;
          $nuevoconsultorio->id_medico = $request->id_medico;
          $nuevoconsultorio->id_consultorio = $request->id_consultorio[$key];
          $nuevoconsultorio->estado = 1;
          $nuevoconsultorio->id_user_created = $id_user;
          $nuevoconsultorio->save();
        }
      }

      $medico_consultorio = new Medico_consultorio();
      $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($request->id_establecimiento, $request->id_medico);

      foreach ($consultoriomedico as $rowConsul) :
        $existe = "no";
        foreach ($request->id_consultorio as $key => $value) {
          if ($rowConsul->id_consultorio == $request->id_consultorio[$key]) {
            $existe = "si";
          }
        }
        if ($existe == "no") {
          $update = Medico_consultorio::where('id_medico', '=', $request->id_medico)->where('id_establecimiento', '=', $request->id_establecimiento)->where('id_consultorio', '=', $rowConsul->id_consultorio)->where('estado', '=', 1)->first();
          $update->estado = 2;
          $update->id_user_update = $id_user;
          $update->save();
        }
      endforeach;
    }

    Session::flash('flash_message', 'Actualización exitosa');
    return redirect('/gestion_medicos');
  }

  public function obtenerMedicosPorIdIpress_IdConsultorio($id_ipress, $id_consultorio)
  {
    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndSoloEmergencia($id_ipress, $id_consultorio);
    return $turno;
  }

  public function all_medicos_programacion()
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

    $existe = $establecimientotot->flagestablecimientos;
    $flagservicios = $establecimientotot->flagservicios;

    $modelfarma = new Farmacia();
    $modelmedico = new Medico();
    $farmacias = $modelfarma->GetFarmaByUser($id_user);
    $id_establecimiento = $farmacias->id_establecimiento;
    $farmacia = $farmacias->nombre;
    $usuarios = $model_usuario->GetById($id_user);
    $id_consultorio = $usuarios->id_consultorio;
    $medico_consultorio = new Medico_consultorio();

    $model_consultorio = new Consultorio;

    $consultorio = null;
    if ($flagservicios == 1) {
      $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($id_establecimiento);
      $consultorio_quirurgico = $model_consultorio->ShowAllConsultoriosSubConsultorioQuirurgico($id_establecimiento);
    } else {
      $consultorio = $model_consultorio->getConsultorioPorEstablecimiento($id_establecimiento, $id_consultorio);
    }

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

    $tipo_atenciones = \App\Models\Cita::GetTipoDeAtension();

    return view(
      'frontend.medicos.all_medico_programacion',
      compact('medicos', 'permiso', 'mes', 'ano', 'id_consultorio', 'id_establecimiento', 'consultorio', 'tipo_atenciones', 'flagservicios','consultorio_quirurgico')
    );
  }

  public function obtener_servicio_por_id_establecimiento_and_id_medico($id_establecimiento, $id_medico)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $id_consultorio = $establecimientotot->id_consultorio;
    $existe = $establecimientotot->flagestablecimientos;
    $existeser = $establecimientotot->flagservicios;
    $medico_consultorio = new Medico_consultorio();
    if ($existe == "1") {
      $consultorio = $medico_consultorio->GetConsultorioByIdEstablecimientoAndIdMedico($id_establecimiento, $id_medico);
    } else {
      if ($existeser == "1") {
        $consultorio = $medico_consultorio->GetConsultorioByIdEstablecimientoAndIdMedico($id_establecimiento, $id_medico, $id_consultorio);
      } else {
        $consultorio = $medico_consultorio->GetConsultorioByIdEstablecimientoAndIdMedico($id_establecimiento, $id_medico, $id_consultorio);
      }
    }

    return $consultorio;
  }

  public function obtener_medico_por_id_establecimiento_and_id_servicio($id_establecimiento, $id_consultorio)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    //$id_consultorio = $establecimientotot->id_consultorio;
    $existe = $establecimientotot->flagestablecimientos;
    $existeser = $establecimientotot->flagservicios;
    $medico_consultorio = new Medico_consultorio();
    $medicos = $medico_consultorio->ShowallById_IpressAndIdConsultorio($id_establecimiento, $id_consultorio);

    return $medicos;
  }

  public function obtener_sub_consultorio_por_id_establecimiento_and_id_servicio($id_establecimiento, $id_consultorio, $id_tipo_atencion=0)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    //$id_consultorio = $establecimientotot->id_consultorio;
    $existe = $establecimientotot->flagestablecimientos;
    $existeser = $establecimientotot->flagservicios;
    $medico_consultorio = new Medico_consultorio();

    //$sub_consultorio = $medico_consultorio->GetSubConsultorioByIdEstablecimientoAndIdConsultorio($id_establecimiento, $id_consultorio, $id_tipo_atencion);
    $sub_consultorio = $medico_consultorio->GetSubConsultorioByIdEstablecimientoIdConsultorio($id_establecimiento, $id_consultorio, $id_tipo_atencion);
    //print_r($sub_consultorio);
    return $sub_consultorio;
  }

  public function obtener_medico_por_id_establecimiento_and_id_sub_consultorio($id_establecimiento, $id_sub_consultorio, $fecha)
  {

    $medico = DB::table('medico_turnos')
      ->join('medicos', 'medico_turnos.id_medico', '=', 'medicos.id')
      ->select('medico_turnos.id_medico', DB::raw("medicos.paterno||' '||medicos.materno||' ,'||medicos.nombre as medico"))
      ->Where('medico_turnos.id_establecimiento', '=', $id_establecimiento)
      ->where('medico_turnos.estado', '=', 1)
      ->where('medico_turnos.fecha_atencion', '=', $fecha)
      ->where('medico_turnos.id_sub_consultorio', '=', $id_sub_consultorio)
      ->get();
    return $medico;
  }

  public function all_medicos_programacion_detalle($id_establecimiento, $id_medico, $id_consultorio, $mes, $ano, $id_tipo_receta, $id_servicio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_tipo_receta = \App\Models\Cita::GetTipoDeAtensionNombre($id_tipo_receta);

    $modelmedico = new Medico();
    $fechaBuscar = "01-" . $mes . "-" . $ano;
    $fecha = $modelmedico->getFechaServidorUltimoDiaMes($fechaBuscar);
    $diaFin = $fecha->dia;
    $model_receta = new Receta_vale;
    $fechaServidor = $model_receta->getFechaServidor();
    $modelfarma = new Farmacia();
    $farmacias = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_establecimiento = $farmacias->id_establecimiento;
    $medicoturno = new Medico_turno();

    // Obtener todos los registros de MEDICO_TURNOS que coinciden
    $turno = $medicoturno->getMedicoTurnoByMedicoAndMes($id_establecimiento, $id_medico, $id_consultorio, $mes, $ano, $id_tipo_receta);
    if (count($turno) > 0) :

      // Validacion de ejecutar logica de duplicados en la programacion de medicos
      $turno = $this->listarTurnosConFechaDistinct($turno);
    endif;

    //obtener id_turno de subconsultorio
    $turno_disponible = $medicoturno->GetSubConsultorioTurnoByIdConsultorio($id_consultorio);
    $turnosProgramados = $medicoturno->getTurnosProgramados($id_establecimiento, $id_consultorio, $id_medico, $id_tipo_receta, $ano, $mes);

    if ($id_tipo_receta == "EMERGENCIA"):
      $turnosMedico = $medicoturno->getTurnosMedico($id_medico, $ano, $mes);
    else:
      $turnosMedico = $medicoturno->getTurnosMedicoSinEmergencia($id_medico, $ano, $mes);
    endif;

    $aTurnosProgramados = null;
    foreach ($turnosProgramados as $item) {
      $aTurnosProgramados[$item->tipo][$item->key] = $item->medico;
    }
    $aTurnosMedico = null;
    foreach ($turnosMedico as $item) {
      $aTurnosMedico[$item->key] = 1;
    }
    unset($turnos_programados);
    $aHorario = null;

    /** */

    $aTurno["MD"] = "Madrugada";
    $aTurno["M"] = "Manana";
    $aTurno["T"] = "Tarde";
    $aTurno["N"] = "Noche";

    // Matriz de días de la semana
    $days_dias = array(
      'Monday'=>'Lunes',
      'Tuesday'=>'Martes',
      'Wednesday'=>'Miercoles',
      'Thursday'=>'Jueves',
      'Friday'=>'Viernes',
      'Saturday'=>'Sabado',
      'Sunday'=>'Domingo'
      );
    //==========================//
    $i = 1;
    $arrayDia = [];
    $conSinTurno = empty($turno_disponible) ? true : false; //Consultorios sin turnos agregados
    foreach ($turno_disponible as $key) {
      $arrayDia[$i] = $key->dia !== null ? $key->dia : 'SinDia';
      $i++;
    }
    //dd($arrayDia);
    //=========================//
    $i = 1;
    $arrayTurnoConDia = [];
    foreach ($turno_disponible as $key) {
      $arrayTurnoConDia[$i] = $key->turno_con_dia !== null ? $key->turno_con_dia : $key->turno.'SinDia';
      $i++;
    }
    $arraTurnoDisponible = [];
    if (count($turno_disponible) == 0) {
      foreach ($aTurno as $key => $rowTurno) {
        $arraTurnoDisponible[$key] = $key;
      }
    } else {
      foreach ($turno_disponible as $rowTurno) {
        $arraTurnoDisponible[$rowTurno->turno] = $rowTurno->turno;
      }
    }

    // Si en algun momento ya se registro la programacion del profesional
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

        //Buscar día
        $textDia =  $days_dias[date('l', strtotime($fechaServer))];
        //=========================================================//
        //Buscar Indice
        $indiceDiaTurnoTodosMD = array_search("MDTodos",$arrayTurnoConDia,true);
        $indiceDiaTurnoTodosM = array_search("MTodos",$arrayTurnoConDia,true);
        $indiceDiaTurnoTodosT = array_search("TTodos",$arrayTurnoConDia,true);
        $indiceDiaTurnoTodosN = array_search("NTodos",$arrayTurnoConDia,true);
        $indiceDiaTurnoSinDiaMD = array_search("MDSinDia",$arrayTurnoConDia,true);
        $indiceDiaTurnoSinDiaM = array_search("MSinDia",$arrayTurnoConDia,true);
        $indiceDiaTurnoSinDiaT = array_search("TSinDia",$arrayTurnoConDia,true);
        $indiceDiaTurnoSinDiaN = array_search("NSinDia",$arrayTurnoConDia,true);
        if ((array_search('SinDia',$arrayDia,true)) || (array_search('Todos',$arrayDia,true)) || (array_search($textDia,$arrayDia,true))) {
          $indiceDiaTurno = true;
        } else {
          $indiceDiaTurno = false;
        }
        //dd($indiceDiaTurno);
        //==========================================================
        if ($fechaServer == $fechaServerTmp) {
          $diaConTurno = $rowTurno->turno.$textDia;
          //dd($diaConTurno);
          if ((array_search('SinDia',$arrayTurnoConDia,true)) || (array_search('Todos',$arrayTurnoConDia,true)) || (array_search($diaConTurno,$arrayTurnoConDia,true))) {
            $indiceDiaConTurno = true;
          } else {
            $indiceDiaConTurno = false;
          }

          if ($id_establecimiento == '76') {
            //TODOS ESPECIFICADO POR TURNO
            if (($indiceDiaTurnoTodosMD == true && $rowTurno->turno == 'MD') || ($indiceDiaTurnoTodosM == true && $rowTurno->turno == 'M') || ($indiceDiaTurnoTodosT == true && $rowTurno->turno == 'T') || ($indiceDiaTurnoTodosN == true && $rowTurno->turno == 'N') || ($indiceDiaTurnoSinDiaMD == true && $rowTurno->turno == 'MD') || ($indiceDiaTurnoSinDiaM == true && $rowTurno->turno == 'M') || ($indiceDiaTurnoSinDiaT == true && $rowTurno->turno == 'T') || ($indiceDiaTurnoSinDiaN == true && $rowTurno->turno == 'N')) {
              $disabled = $sw == true && in_array($rowTurno->turno, $arraTurnoDisponible) ? false : true;
            } elseif ($conSinTurno == true) {
              $disabled = $sw == true && in_array($rowTurno->turno, $arraTurnoDisponible) ? false : true;
          } else {
              $disabled = $sw == true && in_array($rowTurno->turno, $arraTurnoDisponible) && $indiceDiaTurno == true && $indiceDiaConTurno == true ? false : true;
            }
          } else {
            $disabled = $sw == true && in_array($rowTurno->turno, $arraTurnoDisponible) ? false : true;
          }
          $aHorario[$rowTurno->fecha_atencion]['turno'][$rowTurno->turno] = [
            'checkbox' => [
              'name' => 'turno' . $aTurno[$rowTurno->turno] . '[' . $fechaServer . ']',
              'value' => $rowTurno->turno,
              'disabled' => $disabled,
              'checked' => $rowTurno->estado == 1 ? true : false,
              'class' => $rowTurno->turno,
              'dia' => $textDia,
              'indiceDia' => $indiceDiaTurno,
              'indiceDiaConTurno' => $indiceDiaConTurno
            ],
            'hidden' => [
              'name' => 'id' . $aTurno[$rowTurno->turno] . '[' . $fechaServer . ']',
              'value' => $rowTurno->id
            ]
          ];
        } else {
          $disabled = $sw == true && in_array($rowTurno->turno, $arraTurnoDisponible) ? false : true;
          $aHorario[$rowTurno->fecha_atencion]['fecha'] = App\Helpers\Util::FormatoFechaATexto($rowTurno->fecha_atencion);
          $aHorario[$rowTurno->fecha_atencion]['input_fecha'] = true;
          $aHorario[$rowTurno->fecha_atencion]['turno']['MD'] = [
            'checkbox' => [
              'name' => 'turnoMadrugada[' . $fechaServer . ']',
              'value' => 'MD',
              'disabled' => $disabled,
              'checked' => $rowTurno->estado == 1 ? true : false,
              'class' => 'MD'
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
        //Buscar día
        $textDia =  $days_dias[date('l', strtotime($fechaServer))];
        //=========================================================//
        //Buscar Indice
        $indiceDiaTurnoTodosMD = array_search("MDTodos",$arrayTurnoConDia,true);
        $indiceDiaTurnoTodosM = array_search("MTodos",$arrayTurnoConDia,true);
        $indiceDiaTurnoTodosT = array_search("TTodos",$arrayTurnoConDia,true);
        $indiceDiaTurnoTodosN = array_search("NTodos",$arrayTurnoConDia,true);
        $indiceDiaTurnoSinDiaMD = array_search("MDSinDia",$arrayTurnoConDia,true);
        $indiceDiaTurnoSinDiaM = array_search("MSinDia",$arrayTurnoConDia,true);
        $indiceDiaTurnoSinDiaT = array_search("TSinDia",$arrayTurnoConDia,true);
        $indiceDiaTurnoSinDiaN = array_search("NSinDia",$arrayTurnoConDia,true);
        if ((array_search('SinDia',$arrayDia,true)) || (array_search('Todos',$arrayDia,true)) || (array_search($textDia,$arrayDia,true))) {
          $indiceDiaTurno = true;
        } else {
          $indiceDiaTurno = false;
        }
        //dd($indiceDiaTurno);
        //==========================================================
        $aHorario[$fechaServer]['input_fecha'] = true;
        foreach ($aTurno as $key => $rowTurno) {
          $diaConTurno = $key.$textDia;
          if ((array_search('SinDia',$arrayTurnoConDia,true)) || (array_search('Todos',$arrayTurnoConDia,true)) || (array_search($diaConTurno,$arrayTurnoConDia,true))) {
            $indiceDiaConTurno = true;
          } else {
            $indiceDiaConTurno = false;
          }

          if ($id_establecimiento == '76') {
            //TODOS ESPECIFICADO POR TURNO
            if (($indiceDiaTurnoTodosMD == true && $key == 'MD') || ($indiceDiaTurnoTodosM == true && $key == 'M') || ($indiceDiaTurnoTodosT == true && $key == 'T') || ($indiceDiaTurnoTodosN == true && $key == 'N') || ($indiceDiaTurnoSinDiaMD == true && $key == 'MD') || ($indiceDiaTurnoSinDiaM == true && $key == 'M') || ($indiceDiaTurnoSinDiaT == true && $key == 'T') || ($indiceDiaTurnoSinDiaN == true && $key == 'N')) {
              $disabled = $sw == true && in_array($key, $arraTurnoDisponible) ? false : true;
            } elseif ($conSinTurno == true) {
                $disabled = $sw == true && in_array($key, $arraTurnoDisponible) ? false : true;
            }else{
                $disabled = $sw == true && in_array($key, $arraTurnoDisponible) && $indiceDiaTurno == true && $indiceDiaConTurno == true ? false : true;
            }
          } else {
            $disabled = $sw == true && in_array($key, $arraTurnoDisponible) ? false : true;
          }
          $aHorario[$fechaServer]['turno'][$key] = [
            'checkbox' => [
              'name' => 'turno' . $rowTurno . '[' . $fechaServer . ']',
              'value' => $key,
              'disabled' => $disabled,
              'class' => $key,
              'dia' => $textDia,
              'indiceDia' => $indiceDiaTurno,
              'indiceDiaConTurno' => $indiceDiaConTurno
            ]
          ];
        }
      }
    }

    return view('frontend.medicos.all_medico_programacion_detalle', compact('aHorario', 'aTurnosProgramados', 'aTurnosMedico'));
  }
  public function registrar_datos_madrugada()
  {

    /*
        // ENERO
        $datos = DB::table('medico_turnos')
        ->where('fecha_atencion', '>=', '2019-01-01')
        ->where('fecha_atencion', '<=', '2019-01-31')
        ->select('id_medico', 'id_establecimiento', 'id_user_created', 'id_consultorio', 'id_tipo_atencion')
        ->orderBy('id_medico', 'asc')
        ->groupby('id_medico', 'id_establecimiento', 'id_user_created', 'id_consultorio', 'id_tipo_atencion')
        ->get();


        foreach ($datos as $key => $value):
            for($i=1;$i<=31;$i++){
                $medico_turno = new Medico_turno;
                $medico_turno->id_medico = $value->id_medico;
                if($i<10){
                    $medico_turno->fecha_atencion = '2019-01-0'.$i;
                }
                else
                {
                    $medico_turno->fecha_atencion = '2019-01-'.$i;
                }


                $medico_turno->turno = "MD";
                $medico_turno->estado = 0;
                $medico_turno->id_establecimiento = $value->id_establecimiento;
                $medico_turno->id_tipo_atencion = $value->id_tipo_atencion;
                $medico_turno->id_consultorio = $value->id_consultorio;
                $medico_turno->id_user_created = $value->id_user_created;
                $medico_turno->save();
            }

        endforeach;

        // FEBRERO
        $datos = DB::table('medico_turnos')
        ->where('fecha_atencion', '>=', '2019-02-01')
        ->where('fecha_atencion', '<=', '2019-02-28')
        ->select('id_medico', 'id_establecimiento', 'id_user_created', 'id_consultorio', 'id_tipo_atencion')
        ->orderBy('id_medico', 'asc')
        ->groupby('id_medico', 'id_establecimiento', 'id_user_created', 'id_consultorio', 'id_tipo_atencion')
        ->get();

        foreach ($datos as $key => $value):
            for($i=1;$i<=28;$i++){
                $medico_turno = new Medico_turno;
                $medico_turno->id_medico = $value->id_medico;
                if($i<10){
                    $medico_turno->fecha_atencion = '2019-02-0'.$i;
                }
                else
                {
                    $medico_turno->fecha_atencion = '2019-02-'.$i;
                }


                $medico_turno->turno = "MD";
                $medico_turno->estado = 0;
                $medico_turno->id_establecimiento = $value->id_establecimiento;
                $medico_turno->id_tipo_atencion = $value->id_tipo_atencion;
                $medico_turno->id_consultorio = $value->id_consultorio;
                $medico_turno->id_user_created = $value->id_user_created;
                $medico_turno->save();
            }

        endforeach;

        //marzo
        $datos = DB::table('medico_turnos')
        ->where('fecha_atencion', '>=', '2019-03-01')
        ->where('fecha_atencion', '<=', '2019-03-31')
        ->select('id_medico', 'id_establecimiento', 'id_user_created', 'id_consultorio', 'id_tipo_atencion')
        ->orderBy('id_medico', 'asc')
        ->groupby('id_medico', 'id_establecimiento', 'id_user_created', 'id_consultorio', 'id_tipo_atencion')
        ->get();

        foreach ($datos as $key => $value):
            for($i=1;$i<=31;$i++){
                $medico_turno = new Medico_turno;
                $medico_turno->id_medico = $value->id_medico;
                if($i<10){
                    $medico_turno->fecha_atencion = '2019-03-0'.$i;
                }
                else
                {
                    $medico_turno->fecha_atencion = '2019-03-'.$i;
                }


                $medico_turno->turno = "MD";
                $medico_turno->estado = 0;
                $medico_turno->id_establecimiento = $value->id_establecimiento;
                $medico_turno->id_tipo_atencion = $value->id_tipo_atencion;
                $medico_turno->id_consultorio = $value->id_consultorio;
                $medico_turno->id_user_created = $value->id_user_created;
                $medico_turno->save();
            }

        endforeach; */
  }
  public function registrar_medico_programacion(Request $request)
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
    $id_sub_consultorio = $request->id_sub_consultorio;
    $fecactu = strtotime(date("Y-m-d"));

    $tipo_atencion_nombre = \App\Models\Cita::GetTipoDeAtensionNombre($request->id_tipo_atencion);
    if($request->id_tipo_atencion=="4"){//CENTRO QUIRURGICO
      $id_sub_consultorio=null;
    }

    $existeotro = "no";
    if (count($request->fecha) > 0) :
      foreach ($request->fecha as $key => $value) :
        $fecselec = strtotime($request->fecha[$key]);
        if ($fecselec >= $fecactu) {
          if (isset($request->turnoMadrugada[$key])) {
            $modelmedicoturno = new Medico_turno();
            // Si TIPO DE ATENCION es Emergencia
            if ($request->id_tipo_atencion == 2):
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpressEmergencia($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'MD');
            else:
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'MD', $tipo_atencion_nombre);
            endif;
            // $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'MD', $tipo_atencion_nombre);

            if (count($medicoturno) > 0) {
              return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashSuccess("Existe conflito de programación con el servicio " . $medicoturno->consultorio . ", turno MADRUGADA, en la fecha: " . $request->fecha[$key]);
              // return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashWarning("El profesional <b>" . $medicoturno->paterno." ".$medicoturno->materno." ".$medicoturno->nombre . "</b> ya se encuentra programado en el consultorio <b>" . $medicoturno->consultorio . "</b>, el día <b>" . date('d/m/Y', strtotime($request->fecha[$key])) . "</b> en el turno <b>MADRUGADA</b>.");
            }
          }
          if (isset($request->turnoManana[$key])) {
            $modelmedicoturno = new Medico_turno();
            // Si TIPO DE ATENCION es Emergencia
            if ($request->id_tipo_atencion == 2):
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpressEmergencia($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'M');
            else:
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'M', $tipo_atencion_nombre);
            endif;
            // $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'M', $tipo_atencion_nombre);

            if (count($medicoturno) > 0) {
              return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashSuccess("Existe conflito de programación con el servicio " . $medicoturno->consultorio . ", turno MAÑANA, en la fecha: " . $request->fecha[$key]);
              // return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashWarning("El profesional <b>" . $medicoturno->paterno." ".$medicoturno->materno." ".$medicoturno->nombre . "</b> ya se encuentra programado en el consultorio <b>" . $medicoturno->consultorio . "</b>, el día <b>" . date('d/m/Y', strtotime($request->fecha[$key])) . "</b> en el turno <b>MAÑANA</b>.");
            }
          }

          if (isset($request->turnoTarde[$key])) {
            $modelmedicoturno = new Medico_turno();
            // Si TIPO DE ATENCION es Emergencia
            if ($request->id_tipo_atencion == 2):
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpressEmergencia($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'T');
            else:
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'T', $tipo_atencion_nombre);
            endif;
            // $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'T', $tipo_atencion_nombre);

            if (count($medicoturno) > 0) {
              return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashSuccess("Existe conflito de programación con el servicio " . $medicoturno->consultorio . ", turno TARDE, en la fecha: " . $request->fecha[$key]);
              // return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashWarning("El profesional <b>" . $medicoturno->paterno." ".$medicoturno->materno." ".$medicoturno->nombre . "</b> ya se encuentra programado en el consultorio <b>" . $medicoturno->consultorio . "</b>, el día <b>" . date('d/m/Y', strtotime($request->fecha[$key])) . "</b> en el turno <b>TARDE</b>.");
            }
          }

          if (isset($request->turnoNoche[$key])) {
             $modelmedicoturno = new Medico_turno();
            // Si TIPO DE ATENCION es Emergencia
            if ($request->id_tipo_atencion == 2):
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpressEmergencia($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'N');
            else:
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'N', $tipo_atencion_nombre);
            endif;
            // $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndIpress($id_ipress, $request->id_medico, $id_sub_consultorio, $request->fecha[$key], 'N', $tipo_atencion_nombre);

            if (count($medicoturno) > 0) {
              return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashSuccess("Existe conflito de programación con el servicio " . $medicoturno->consultorio . ", turno NOCHE, en la fecha: " . $request->fecha[$key]);
              // return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashWarning("El profesional <b>" . $medicoturno->paterno." ".$medicoturno->materno." ".$medicoturno->nombre . "</b> ya se encuentra programado en el consultorio <b>" . $medicoturno->consultorio . "</b>, el día <b>" . date('d/m/Y', strtotime($request->fecha[$key])) . "</b> en el turno <b>NOCHE</b>.");
            }
          }
        }
      endforeach;

      foreach ($request->fecha as $key => $value) :
        $fecselec = strtotime($request->fecha[$key]);
        if ($fecselec >= $fecactu) {
          if (isset($request->turnoMadrugada[$key])) {
            $modelmedicoturno = new Medico_turno();
            // Si TIPO DE ATENCION es Emergencia
            if ($request->id_tipo_atencion == 2):
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndOtraIpressEmergencia($id_ipress, $request->dni_medico, $request->fecha[$key], 'MD');
            else:
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndOtraIpress($id_ipress, $request->dni_medico, $request->fecha[$key], 'MD', $tipo_atencion_nombre);
            endif;
            if (count($medicoturno) > 0) {
              return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashSuccess("Existe conflicto de programación con el servicio " . $medicoturno->consultorio . ", turno MADRUGADA, en la fecha: " . $request->fecha[$key] . " del establecimiento " . $medicoturno->nom_establecimiento . "");
            }
          }

          if (isset($request->turnoManana[$key])) {
            $modelmedicoturno = new Medico_turno();
            // Si TIPO DE ATENCION es Emergencia
            if ($request->id_tipo_atencion == 2):
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndOtraIpressEmergencia($id_ipress, $request->dni_medico, $request->fecha[$key], 'M');
            else:
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndOtraIpress($id_ipress, $request->dni_medico, $request->fecha[$key], 'M', $tipo_atencion_nombre);
            endif;
            if (count($medicoturno) > 0) {
              return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashSuccess("Existe conflicto de programación con el servicio " . $medicoturno->consultorio . ", turno MAÑANA, en la fecha: " . $request->fecha[$key] . " del establecimiento " . $medicoturno->nom_establecimiento . "");
            }
          }

          if (isset($request->turnoTarde[$key])) {
            $modelmedicoturno = new Medico_turno();
            // Si TIPO DE ATENCION es Emergencia
            if ($request->id_tipo_atencion == 2):
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndOtraIpressEmergencia($id_ipress, $request->dni_medico, $request->fecha[$key], 'T');
            else:
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndOtraIpress($id_ipress, $request->dni_medico, $request->fecha[$key], 'T', $tipo_atencion_nombre);
            endif;
            if (count($medicoturno) > 0) {
              return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashSuccess("Existe conflicto de programación con el servicio " . $medicoturno->consultorio . ", turno TARDE, en la fecha: " . $request->fecha[$key] . " del establecimiento " . $medicoturno->nom_establecimiento . "");
            }
          }

          if (isset($request->turnoNoche[$key])) {
            $modelmedicoturno = new Medico_turno();
            // Si TIPO DE ATENCION es Emergencia
            if ($request->id_tipo_atencion == 2):
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndOtraIpressEmergencia($id_ipress, $request->dni_medico, $request->fecha[$key], 'N');
            else:
              $medicoturno = $modelmedicoturno->GetValidaProgramacionMedicoByFechaAndOtraIpress($id_ipress, $request->dni_medico, $request->fecha[$key], 'N', $tipo_atencion_nombre);
            endif;
            if (count($medicoturno) > 0) {
              return redirect('/all_medicos_programacion/' . $request->id_farmacia)->withFlashSuccess("Existe conflicto de programación con el servicio " . $medicoturno->consultorio . ", turno NOCHE, en la fecha: " . $request->fecha[$key] . " del establecimiento " . $medicoturno->nom_establecimiento . "");
            }
          }
        }
      endforeach;
    endif;

    if ($request->opc == 1) :
      if (count($request->fecha) > 0) :

        $primera_fecha = "";
        // Obtener la primera fecha para la validacion posterior
        foreach ($request->fecha as $key => $value) :
          $primera_fecha = $request->fecha[$key]; break;
        endforeach;

        $medico_turno = new Medico_turno;
        // Obtenemos cantidad de registros de la programacion del profesional en el tipo de atencion, consultorio y mes indicado
        $cant_turnos = $medico_turno->getCantidadTurnos($request->id_medico, $primera_fecha, $id_ipress, $tipo_atencion_nombre, $id_sub_consultorio);

        // Si no cuenta con registros, pasamos a registrar los turnos
        if ($cant_turnos == 0) :
          foreach ($request->fecha as $key => $value) :

            // Por cada fecha registramos los 4 turnos (Madrugada, Mañana, Tarde y Noche)
            $rpta = $medico_turno->registrarTurnoXFecha(
              $request->id_medico,
              $request->fecha[$key],
              $id_ipress,
              $tipo_atencion_nombre,
              $id_sub_consultorio?$id_sub_consultorio:null,//id_sub_consultorio es null cuando es centro quirurgico
              $id_consultorio,
              $id_user,
              (isset($request->turnoMadrugada[$key])) ? 1 : 0,
              (isset($request->turnoManana[$key])) ? 1 : 0,
              (isset($request->turnoTarde[$key])) ? 1 : 0,
              (isset($request->turnoNoche[$key])) ? 1 : 0
            );

            //$medico_turno->registrarMedicoCita($request->fecha[$key], $id_sub_consultorio);
            $medico_turno->registrarMedicoCita2($request->fecha[$key], $id_sub_consultorio,$request->id_medico);
            $this->asignarMedicoCitasProcedimiento($request->fecha[$key], $id_sub_consultorio);

          endforeach;
        else :
          Session::flash('flash_danger', 'Ya cuenta con programación registrada.');
          return redirect('/all_medicos_programacion/' . $request->id_farmacia);
        endif;

      endif;
    else :
      if (count($request->fecha) > 0) :
        foreach ($request->fecha as $key => $value) :
          $fecselec = strtotime($request->fecha[$key]);
          if ($fecselec >= $fecactu) {
            $medico_turno = Medico_turno::where('id', '=', $request->idMadrugada[$key])->first();
            $medico_turno->id_medico = $request->id_medico;
            $medico_turno->fecha_atencion = $request->fecha[$key];
            $medico_turno->turno = "MD";
            $medico_turno->id_tipo_atencion = $tipo_atencion_nombre;
            $medico_turno->estado = (isset($request->turnoMadrugada[$key])) ? 1 : 0;
            $medico_turno->id_establecimiento = $id_ipress;
            $medico_turno->id_sub_consultorio = $id_sub_consultorio;
            $medico_turno->id_consultorio = $id_consultorio;
            $medico_turno->id_user_update = $id_user;
            $medico_turno->id_turno = 1;
            $medico_turno->updated_at = date('Y-m-d H:i:s');
            $medico_turno->save();

            $medico_turno = Medico_turno::where('id', '=', $request->idManana[$key])->first();
            $medico_turno->id_medico = $request->id_medico;
            $medico_turno->fecha_atencion = $request->fecha[$key];
            $medico_turno->turno = "M";
            $medico_turno->id_tipo_atencion = $tipo_atencion_nombre;
            $medico_turno->estado = (isset($request->turnoManana[$key])) ? 1 : 0;
            $medico_turno->id_establecimiento = $id_ipress;
            $medico_turno->id_sub_consultorio = $id_sub_consultorio;
            $medico_turno->id_consultorio = $id_consultorio;
            $medico_turno->id_user_update = $id_user;
            $medico_turno->id_turno = 2;
            $medico_turno->updated_at = date('Y-m-d H:i:s');
            $medico_turno->save();

            $medico_turno = Medico_turno::where('id', '=', $request->idTarde[$key])->first();
            $medico_turno->id_medico = $request->id_medico;
            $medico_turno->fecha_atencion = $request->fecha[$key];
            $medico_turno->turno = "T";
            $medico_turno->id_tipo_atencion = $tipo_atencion_nombre;
            $medico_turno->estado = (isset($request->turnoTarde[$key])) ? 1 : 0;
            $medico_turno->id_establecimiento = $id_ipress;
            $medico_turno->id_sub_consultorio = $id_sub_consultorio;
            $medico_turno->id_consultorio = $id_consultorio;
            $medico_turno->id_user_update = $id_user;
            $medico_turno->id_turno = 3;
            $medico_turno->updated_at = date('Y-m-d H:i:s');
            $medico_turno->save();

            $medico_turno = Medico_turno::where('id', '=', $request->idNoche[$key])->first();
            $medico_turno->id_medico = $request->id_medico;
            $medico_turno->fecha_atencion = $request->fecha[$key];
            $medico_turno->turno = "N";
            $medico_turno->id_tipo_atencion = $tipo_atencion_nombre;
            $medico_turno->estado = (isset($request->turnoNoche[$key])) ? 1 : 0;
            $medico_turno->id_establecimiento = $id_ipress;
            $medico_turno->id_sub_consultorio = $id_sub_consultorio;
            $medico_turno->id_consultorio = $id_consultorio;
            $medico_turno->id_user_update = $id_user;
            $medico_turno->id_turno = 4;
            $medico_turno->updated_at = date('Y-m-d H:i:s');
            $medico_turno->save();

            //$medico_turno->registrarMedicoCita($request->fecha[$key], $id_sub_consultorio);
            $medico_turno->registrarMedicoCita2($request->fecha[$key], $id_sub_consultorio,$request->id_medico);
            $this->asignarMedicoCitasProcedimiento($request->fecha[$key], $id_sub_consultorio);
          }
        endforeach;
      endif;
    endif;

    Session::flash('flash_message', 'Actualización exitosa');
    return redirect('/all_medicos_programacion/' . $request->id_farmacia);
  }

  // Procedimientos programados: asigna el médico programado a las citas de procedimiento (flag_pp = 1).
  // Si falla, no interrumpe el guardado de la programación.
  private function asignarMedicoCitasProcedimiento($fecha, $id_sub_consultorio)
  {
    if (!$id_sub_consultorio) return;
    try {
      $model_pct = new \App\Models\ProcedimientoConsultorioTurno;
      $model_pct->asignarMedicoCitasPp($fecha, $id_sub_consultorio);
    } catch (\Exception $e) {
      \Log::error('Asignar médico a citas PP (' . $fecha . ', subconsultorio ' . $id_sub_consultorio . '): ' . $e->getMessage());
    }
  }

  public function registrar_medico_programacion_12092017(Request $request)
  {

    if ($request->opc == 1) :
      if (count($request->fecha) > 0) :
        foreach ($request->fecha as $key => $value) :

          $medico_turno = new Medico_turno;
          $medico_turno->id_medico     = $request->id_medico;
          $medico_turno->fecha_atencion   = $request->fecha[$key];
          $medico_turno->turno       = "M";
          //$medico_turno->estado 			= (isset($request->turnoManana[$key]))?1:0;
          $medico_turno->estado       = (isset($request->turnoManana[$key]) && $request->turnoManana[$key] > 0) ? 1 : 0;
          $medico_turno->numero_atencion  = (isset($request->turnoManana[$key]) && $request->turnoManana[$key] > 0) ? $request->turnoManana[$key] : 0;
          $medico_turno->save();

          $medico_turno = new Medico_turno;
          $medico_turno->id_medico     = $request->id_medico;
          $medico_turno->fecha_atencion   = $request->fecha[$key];
          $medico_turno->turno       = "T";
          //$medico_turno->estado 			= (isset($request->turnoTarde[$key]))?1:0;
          $medico_turno->estado       = (isset($request->turnoTarde[$key]) && $request->turnoTarde[$key] > 0) ? 1 : 0;
          $medico_turno->numero_atencion  = (isset($request->turnoTarde[$key]) && $request->turnoTarde[$key] > 0) ? $request->turnoTarde[$key] : 0;
          $medico_turno->save();

          $medico_turno = new Medico_turno;
          $medico_turno->id_medico     = $request->id_medico;
          $medico_turno->fecha_atencion   = $request->fecha[$key];
          $medico_turno->turno       = "N";
          //$medico_turno->estado 			= (isset($request->turnoNoche[$key]))?1:0;
          $medico_turno->estado       = (isset($request->turnoNoche[$key]) && $request->turnoNoche[$key] > 0) ? 1 : 0;
          $medico_turno->numero_atencion  = (isset($request->turnoNoche[$key]) && $request->turnoNoche[$key] > 0) ? $request->turnoNoche[$key] : 0;
          $medico_turno->save();

          $medico_turno = new Medico_turno;
          $medico_turno->id_medico     = $request->id_medico;
          $medico_turno->fecha_atencion   = $request->fecha[$key];
          $medico_turno->turno       = "MD";
          //$medico_turno->estado 			= (isset($request->turnoMadrugada[$key]))?1:0;
          $medico_turno->estado       = (isset($request->turnoMadrugada[$key]) && $request->turnoMadrugada[$key] > 0) ? 1 : 0;
          $medico_turno->numero_atencion  = (isset($request->turnoMadrugada[$key]) && $request->turnoMadrugada[$key] > 0) ? $request->turnoMadrugada[$key] : 0;
          $medico_turno->save();
        endforeach;
      endif;
    else :
      if (count($request->fecha) > 0) :
        foreach ($request->fecha as $key => $value) :

          $medico_turno = Medico_turno::where('id', '=', $request->idManana[$key])->first();
          $medico_turno->id_medico     = $request->id_medico;
          $medico_turno->fecha_atencion   = $request->fecha[$key];
          $medico_turno->turno       = "M";
          //$medico_turno->estado 			= (isset($request->turnoManana[$key]))?1:0;
          $medico_turno->estado       = (isset($request->turnoManana[$key]) && $request->turnoManana[$key] > 0) ? 1 : 0;
          $medico_turno->numero_atencion  = (isset($request->turnoManana[$key]) && $request->turnoManana[$key] > 0) ? $request->turnoManana[$key] : 0;
          $medico_turno->save();

          $medico_turno = Medico_turno::where('id', '=', $request->idTarde[$key])->first();
          $medico_turno->id_medico     = $request->id_medico;
          $medico_turno->fecha_atencion   = $request->fecha[$key];
          $medico_turno->turno       = "T";
          //$medico_turno->estado 			= (isset($request->turnoTarde[$key]))?1:0;
          $medico_turno->estado       = (isset($request->turnoTarde[$key]) && $request->turnoTarde[$key] > 0) ? 1 : 0;
          $medico_turno->numero_atencion  = (isset($request->turnoTarde[$key]) && $request->turnoTarde[$key] > 0) ? $request->turnoTarde[$key] : 0;
          $medico_turno->save();

          $medico_turno = Medico_turno::where('id', '=', $request->idNoche[$key])->first();
          $medico_turno->id_medico     = $request->id_medico;
          $medico_turno->fecha_atencion   = $request->fecha[$key];
          $medico_turno->turno       = "N";
          //$medico_turno->estado 			= (isset($request->turnoNoche[$key]))?1:0;
          $medico_turno->estado       = (isset($request->turnoNoche[$key]) && $request->turnoNoche[$key] > 0) ? 1 : 0;
          $medico_turno->numero_atencion  = (isset($request->turnoNoche[$key]) && $request->turnoNoche[$key] > 0) ? $request->turnoNoche[$key] : 0;
          $medico_turno->save();

          $medico_turno = Medico_turno::where('id', '=', $request->idMadrugada[$key])->first();
          $medico_turno->id_medico     = $request->id_medico;
          $medico_turno->fecha_atencion   = $request->fecha[$key];
          $medico_turno->turno       = "MD";
          //$medico_turno->estado 			= (isset($request->turnoMadrugada[$key]))?1:0;
          $medico_turno->estado       = (isset($request->turnoMadrugada[$key]) && $request->turnoMadrugada[$key] > 0) ? 1 : 0;
          $medico_turno->numero_atencion  = (isset($request->turnoMadrugada[$key]) && $request->turnoMadrugada[$key] > 0) ? $request->turnoMadrugada[$key] : 0;
          $medico_turno->save();
        endforeach;
      endif;
    endif;

    return redirect('/all_medicos_programacion/' . $request->id_farmacia);
  }

  public function cambiar_medico_principal($id_receta_vale)
  {
    $receta = Receta_vale::where('id', $id_receta_vale)->first();
    $id_medico = $receta->id_medico;
    $model_medico = new Receta_medico();
    $model_especializacion = new Especializacione();
    //----------------------------------------------------------------------
    $datos_profesional = $model_especializacion->ObtenerEspcialidadDelProfesional($id_medico);
    $nombre_especializacion = $datos_profesional->nombre;
    $profesionales = $model_medico->obtenerProfesionalesPorEspecialidad($nombre_especializacion);
    return view('frontend.receta_vales.cambiar_medico_principal', compact('datos_profesional', 'profesionales', 'id_receta_medico', 'id_receta_vale'));
  }

  public function cambiar_profesional_salud($id_receta_medico)
  {
    $model_medico = new Receta_medico();
    $model_especializacion = new Especializacione();
    $profesional = Receta_medico::where('id', $id_receta_medico)->first();
    $id_medico = $profesional->id_medico;
    $id_receta_vale = $profesional->id_receta_vale;
    $datos_profesional = $model_especializacion->ObtenerEspcialidadDelProfesional($id_medico);
    $nombre_especializacion = $datos_profesional->nombre;
    $profesionales = $model_medico->obtenerProfesionalesPorEspecialidad($nombre_especializacion);
    return view('frontend.receta_vales.cambiar_profesionales', compact('datos_profesional', 'profesionales', 'id_receta_medico', 'id_receta_vale'));
  }

  public function editar_medico_saenz($id, $id_farmacia)
  { 

    if (!Auth::check()) : 
      return redirect('/auth/login'); 
    endif; 

    $id_user = Auth::user()->id; 

    $model_farma = new Farmacia; 
    $farma = $model_farma->GetFarmaByUser($id_user); 
    $id_farmacia = $farma->id; 

    $medico = DB::table('medicos')->leftJoin('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id') 
              ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')->where('medicos.id', '=', $id)->where('medicos.estado', 1)
              ->select( 
                'medicos.*', 
                DB::raw("COALESCE(especializaciones.nombre, 'SIN ESPECIALIZACIÓN') AS especializacion"), 
                'establecimientos.nombre as ipress' 
              ) 
              ->first(); 

    $dni = $medico->dni;  
    /*$flag_rrhh = DB::table('medicos') 
            ->join('users', 'medicos.dni', '=', 'users.dni') 
            ->where('medicos.dni', $dni) 
            ->value('users.flag_rrhh'); */
    $flag_rrhh = Auth::user()->flag_rrhh;
    
    $rne_medico = $medico->rne;  
    $id_profesion_medico = $medico->id_profesion; 

    $especialidades_medico = DB::table('medicos_especialidades') 
    ->leftJoin('especializaciones', 'medicos_especialidades.id_especialidad', '=', 'especializaciones.id') 
    ->leftJoin('medicos', 'medicos_especialidades.id_medico', '=', 'medicos.id') 
    ->leftJoin('profesiones', 'medicos_especialidades.id_profesion', '=', 'profesiones.id') 
    ->leftJoin('colegio_profesionales', 'medicos_especialidades.id_colegio_profesional', '=', 'colegio_profesionales.id') 
    ->select( 
        'medicos_especialidades.*', 
        'profesiones.id as id_profesion', 
        DB::raw("COALESCE(especializaciones.nombre, '') as nombre_especialidad"), 
        DB::raw("COALESCE(profesiones.nombre, '') as nombre_profesion"), 
        DB::raw("COALESCE(colegio_profesionales.descripcion, '') as nom_colegio") 
    ) 
    ->where('medicos_especialidades.id_medico', $id) 
    ->where('medicos_especialidades.id_estado_reg', 1) 
    ->get(); 


    $especialidades_array = array(); 
    foreach ($especialidades_medico as $item) { 
        $especialidades_array[] = (array) $item; 
    } 
    $establecimientos = DB::table('establecimientos')->where('id_estado_reg',1)->where('codigo','!=','')->get(); 
    $medico_turno = DB::table('medico_turnos')->where('medico_turnos.id_medico', '=', $id)->orderBy('medico_turnos.dia', 'asc')->get(); 
    $model_consultorio = new \App\Models\Consultorio; 

    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorioAll($farma->id_establecimiento); 

    $modelcolegioprofesional = new Colegio_profesionale(); 
    $colegio_profesional = $modelcolegioprofesional->ShowAll_v2(); 

    $modelespecializaciones = new Especializacione(); 
    $especializaciones = $modelespecializaciones->ShowAll_v2(); 

    $model_profesion = new \App\Models\Profesione; 
    $profesion = $model_profesion->ShowAll_v2(); 

    $grados_model = new \App\Models\Titular_grado; 
    $grados_lista = $grados_model->ShowTipoGrado_v2(); 

    $model_situacion_actual = new \App\Models\Situacion_actual; 
    $situacion_actual = $model_situacion_actual->ShowSituacionActual(); 

    $model_condicion_laboral = new \App\Models\Condicion_laboral; 
    $condicion_laboral = $model_condicion_laboral->ShowCondicionActual(); 

    $modelIpress = new Establecimiento(); 
    $establecimiento_data = $modelIpress->ShowAllEstablecimientosPersonalSalud(); 

    $unidad_model = new Consultorio; 
    $unidad_lista = $unidad_model->getDivisiones(); 

    $sub_unidad_lista = null; 
		if ($medico->id_unidad != null): 
			$model_sub_unidad = new Consultorio; 
			$sub_unidad_lista = $model_sub_unidad->getDepartamentosPorDivision($medico->id_unidad); 
    endif; 

    $model_departamento = new \App\Models\Departamento; 
		$departamento = $model_departamento->ShowAll(); 

    $provincia = null; 
		if ($medico->id_departamento != null): 
			$model_provincia = new \App\Models\Provincia; 
			$provincia = $model_provincia->ShowAllByIdDepartamento($medico->id_departamento); 
    endif; 

    $distrito = null; 
		if ($medico->id_provincia != null): 
			$model_distrito = new \App\Models\Distrito; 
			$distrito = $model_distrito->ShowDistrito($medico->id_provincia); 
		endif; 

    $model_tipo = new \App\Models\Tipo_contrato; 
    $tipo = $model_tipo->ShowAll(); 
    $medico_consultorio = new Medico_consultorio(); 
    $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($medico->id_establecimiento, $id); 
    $model_tipo_documento = new Tipo_documento_identidad(); 
    $tipo_documento = $model_tipo_documento->BuscaTipoDocumentoIdentidad($medico->tipo_documento); 

    $model_estadocivil = new \App\Models\Estado_civil(); 
		$estado_civil = $model_estadocivil->ShowEstadoCivil(); 

    $tipo_documento_id = $tipo_documento->id; 
    $tipo_documento_text = $tipo_documento->descripcion; 
    $id_establecimiento = isset(Auth::user()->id_establecimiento) ? Auth::user()->id_establecimiento : null; 

    $bloquear_nav = (request()->query('actualizar') == '1') && ($medico->actualiza_datos_alerta == 0);

    if (request()->query('actualizar') == '1') {

	session()->flash(
		'flash_danger',
		'⚠️ Debe actualizar sus datos antes de continuar.'
	);
}
    return view( 
      'frontend.medicos.editar_medico_v2', 
      compact( 
        'medico', 
        'establecimientos', 
                   'id_farmacia', 
                   'medico_turno', 
                   'consultorio', 
                   'consultoriomedico', 
                   'especializaciones', 
                   'profesion', 
                   'tipo', 
                   'colegio_profesional', 
                   'tipo_documento_id', 
                   'tipo_documento_text', 
                   'id_establecimiento', 
                   'estado_civil', 
                   'departamento', 
                   'provincia', 
                   'distrito', 
                   'grados_lista', 
                   'situacion_actual', 
                   'condicion_laboral', 
                   'establecimiento_data', 
                   'unidad_lista', 
                   'sub_unidad_lista', 
                   'especialidades_medico', 
                   'especialidades_array', 
                   'rne_medico', 
                   'id_profesion_medico', 
                   'flag_rrhh',
                   'bloquear_nav'
                  ) 
                ); 
  } 

  public function obtener_turno_por_idipress_idconsultorio($id_ipress, $id_consultorio, $fecha)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetTurnoByIdIpressAndIdConsultorio($id_ipress, $id_consultorio, $fecha);
    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio_turno($id_ipress, $id_consultorio, $turno, $fecha)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndTurno($id_ipress, $id_consultorio, $turno, $fecha);
    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio_turno_quirurgico($id_ipress, $id_consultorio)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndTurnoQuirurgico($id_ipress, $id_consultorio);
    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio($id_ipress, $id_consultorio)
  {

    $medico_turno = new Medico_consultorio;
    $turno = $medico_turno->GetValidaConsultorioByIpressAndConsultorio($id_ipress, $id_consultorio);

    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio_turno_anestesiologo($id_ipress, $id_consultorio)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndTurnoAnestesiologo($id_ipress, $id_consultorio);
    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio_turno_hospitalizacion_motivo($id_ipress, $id_consultorio, $turno, $fecha, $motivo)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndTurnoHospitalizacionMotivo($id_ipress, $id_consultorio, $turno, $fecha, $motivo);
    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio_turno_hospitalizacion($id_ipress, $id_consultorio, $turno, $fecha)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndTurnoHospitalizacion($id_ipress, $id_consultorio, $turno, $fecha);
    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio_turno_emergencia($id_ipress, $id_consultorio, $turno, $fecha)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndTurnoEmergencia($id_ipress, $id_consultorio, $turno, $fecha);
    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio_solo_emergencia($id_ipress, $id_consultorio)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndSoloEmergencia($id_ipress, $id_consultorio);
    return $turno;
  }

  public function obtener_medico_por_idipress_idconsultorio_turno_origen($id_ipress, $id_consultorio, $turno, $fecha)
  {

    $medico_turno = new Medico_turno;
    $turno = $medico_turno->GetMedicoByIdIpressAndIdConsultorioAndTurnoOrigen($id_ipress, $id_consultorio, $turno, $fecha);
    return $turno;
  }

  public function obtener_servicio_por_idipress_fecha($id_ipress, $fecha)
  {

    $model_consultorio = new \App\Models\Consultorio;
    $servicio = $model_consultorio->getConsultoriosProgramadosByEstablecimientoAndFecha($id_ipress, $fecha);
    return $servicio;
  }


  public function prescriptoresByIpress($id_farmacia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
    $nombre_farmacia = $farma->nombre;
    $model_medico = new \App\Models\Medico;
    //----------------------------------------------------------------------
    $modelmedico = new Medico;
    $medicos = $modelmedico->ShowMedicosByIpress($id_farmacia);
    //var_dump($medicos); exit;
    $institucion = $model_medico->profesionalesInstitucionalByFarma($id_farmacia, 1);
    $contratado = $model_medico->profesionalesInstitucionalByFarma($id_farmacia, 2);
    $serumista = $model_medico->profesionalesInstitucionalByFarma($id_farmacia, 3);

    $menu = App\Models\Menu::getMenuByUsuario(Auth::user()->id, 'otros_reportes_by_farma/');
    //----------------------------------------------------------------------
    return view('frontend.direoss.stocks.form_reporte_by_medicos_by_farma', compact('id_farmacia', 'nombre_farmacia', 'medicos', 'institucion', 'contratado', 'serumista', 'menu'));
  }


  public function create_personal_institucional($id_user)
  {
    $modelfarma = new Farmacia();
    $modelIpress = new Establecimiento();
    $farmacias = $modelfarma->GetFarmaByUser($id_user);
    $id_ipress = $farmacias->id_establecimiento;
    $establecimiento = $modelIpress->GetById($id_ipress);
    return view('frontend.medicos.create_personal_institucional', compact('establecimiento'));
  }

  public function existe_personal($dni)
  {
    /*
		if(App::environment('desarrollo')):
        	$client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		elseif(App::environment('local')):
        	$client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		endif;
		*/
    $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));

    $personal = $client->get_busafi_activo($dni);
    if (count($personal) > 0) {
      $dato = 1;
      return $dato;
    } else {
      $dato = 0;
      return $dato;
    }
  }

  public function verpersonal($dni)
  {
    /*
		if(App::environment('desarrollo')):
        	$client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		elseif(App::environment('local')):
        	$client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		endif;
		*/
    $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));

    $personal = $client->get_busafi_activo($dni);

    return ($personal);
  }

  public function evitar_duplicados_medicos($dni)
  {
    $model_medico = new Medico;
    $medico = $model_medico->exite_medico($dni);
    return $medico;
  }


  public function buscar_ajax_medico_dni($dni)
  {
    /*
		if(App::environment('desarrollo')):
        	$client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		elseif(App::environment('local')):
        	$client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		endif;
		*/
    $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));

    $personal = $client->get_busafi_activo($dni);
    return $personal;
  }


  public function buscar_medico_dni(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (!$request->dni) :
      return redirect('/inicio');
    endif;

    $id_establecimiento_user = Auth::user()->id_establecimiento;

    $dni = $request->dni;

    $flag_rrhh = DB::table('users')
                ->where('dni', $dni)
                ->value('flag_rrhh');

    $modelfarma = new Farmacia();
    $modelIpress = new Establecimiento();
    $farmacia = $modelfarma->GetFarmaByIdfarma($request->id_farmacia);
    $id_establecimiento = $farmacia->id_establecimiento;

    $model_departamento = new \App\Models\Departamento();
    $departamento = $model_departamento->ShowAll();

    $model_estadocivil = new \App\Models\Estado_civil();
		$estado_civil = $model_estadocivil->ShowEstadoCivil();

    $model_situacion_actual = new \App\Models\Situacion_actual();
    $situacion_actual = $model_situacion_actual->ShowSituacionActual();

    $model_condicion_laboral = new \App\Models\Condicion_laboral();
    $condicion_laboral = $model_condicion_laboral->ShowCondicionActual();

    $situaciones_lista = DB::table('asegurados')->select('id_situacion as id', 'situacion_paciente')->distinct()->orderBy('situacion_paciente', 'asc')->get();

    $unidad_model = new \App\Models\Consultorio();
    $unidad_lista = $unidad_model->getDivisiones();

    $id_user = Auth::user()->id;
    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    if ($existe == "1") {
      $establecimiento = $modelIpress->GetById($id_establecimiento);
    } else {
      $establecimiento = $modelIpress->GetById($id_establecimiento);
    }

    $establecimiento_data = $modelIpress->ShowAllEstablecimientosPersonalSalud();

    $modelmedico = new Medico();
    $medico_registrado = $modelmedico->medico_yaregistrado($dni, $id_establecimiento);

    $personal = $modelmedico->GetMedicoByDNI($dni);

    if (count($personal) > 0) {
      $existe = 1;
    } else {
      /* 
            AQUI MODIFIQUE PARA TRABAJAR LOCALMENTE*/
      /*
            if (App::environment('desarrollo')):
                $client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
            elseif (App::environment('local')):
                $client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
            endif;
			*/
      $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));
      $personal = $client->get_busafi_activo($dni);
      /*
            $personal=DB::table('beneficiarios')
                    ->select('beneficiarios.*')
                    ->where('dni_titular',$dni)
                    ->first();
            */
      if (count($personal) > 0) {
        $existe = 2;
      } else {
        //$WsReniec = new \App\Ws\Reniec;
        //$datos_reniec = $WsReniec->BuscarDNI($dni);
        //$personal = json_decode($datos_reniec, true);
        
        //if($personal["codigoMensaje"] == "MR"):
          $existe = 4;
        //else:
        //  $existe =  3;
        //endif;
        
      }
    }

    $modelespecializaciones = new Especializacione();
    $especializaciones = $modelespecializaciones->ShowAll_v2();
    $modelcolegioprofesional = new Colegio_profesionale();
    $colegio_profesional = $modelcolegioprofesional->ShowAll_v2();

    $model_consultorio = new \App\Models\Consultorio;
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorioAll($id_establecimiento);
    $model_profesion = new \App\Models\Profesione;
    $profesion = $model_profesion->ShowAll_v2();

    $model_tipo = new \App\Models\Tipo_contrato;
    $tipo = $model_tipo->ShowAll();

    $tipo_documento_id = $request->tipo_documento;
    $tipo_documento_text = $request->tipo_documento_text;

    $grados_model = new \App\Models\Titular_grado;
    $grados_lista = $grados_model->ShowTipoGrado_v2();

    if ($id_establecimiento_user == 76) {
      return view('frontend.medicos.create_profesional_prescriptor_v2', compact('establecimiento', 'id_establecimiento', 'personal', 'existe', 'especializaciones', 'dni', 'medico_registrado', 'consultorio', 'profesion', 'tipo', 'tipo_documento_text', 'tipo_documento_id','colegio_profesional','departamento','estado_civil','situaciones_lista','establecimiento_data', 'unidad_lista', 'situacion_actual', 'condicion_laboral', 'grados_lista', 'personal', 'flag_rrhh'));
    }else {
      return view('frontend.medicos.create_profesional_prescriptor', compact('establecimiento', 'id_establecimiento', 'personal', 'existe', 'especializaciones', 'dni', 'medico_registrado', 'consultorio', 'profesion', 'tipo', 'tipo_documento_text', 'tipo_documento_id','colegio_profesional'));
    }
  }

  public function select_tipo_personal()
  {
    return view('frontend.medicos.select_tipo_personal');
  }

  public function buscar_medico()
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    return view('frontend.medicos.buscar_medico');
  }


  public function index($id_user = 0)
  {
    $id_establecimiento_user = Auth::user()->id_establecimiento;

    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $ruta = "gestion_medicos";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    $permiso = null;
    if (!empty($menu)):
      $permiso = $menu[0];
    endif;

    if (is_null($permiso) || $permiso->lectura == "0"):
      return redirect('/dashboard')->withInput()->withErrors(array('No cuenta con permisos para acceder a esta opción.'));
    endif;


    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];

    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    //print_r($permiso);exit();

    if ($id_establecimiento_user == 76) {
      return view('frontend.medicos.all_v2', ['permiso' => $permiso]);
    } else {
      return view('frontend.medicos.all', ['permiso' => $permiso]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($id_user)
  {
    //
    $modelfarma = new Farmacia();
    $modelIpress = new Establecimiento();
    $farmacias = $modelfarma->GetFarmaByUser($id_user);
    $id_ipress = $farmacias->id_establecimiento;
    $establecimiento = $modelIpress->GetById($id_ipress);
    return view('frontend.medicos.create', compact('establecimiento'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\Response
   */
  public function store_saenz(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelmedico = new Medico();
    $medico_registrado = $modelmedico->medico_yaregistrado($request->dni,  $request->id_establecimiento);
    if (count($medico_registrado) > 0) {
      $dato = "El Profesional Prescriptor <b>" . $medico_registrado->nombre . " " . $medico_registrado->paterno . " " . $medico_registrado->materno . " </b> identificado con DNI <b>" . $medico_registrado->dni . "</b> ya se encuentra registrado en la IPRESS PNP
                        <b>" . $medico_registrado->ipress . "</b>, en estado ";
      $dato .= $medico_registrado->estado == "1" ? "<b>ACTIVO</b>" : "<b>INACTIVO</b>";
      return redirect('/gestion_medicos/' . $request->id_user)->withInput()->withErrors(array($dato));
    } else {

      $id_user = Auth::user()->id;
      $medico = new Medico;

      $especialidades = json_decode($request->input('especialidades_json'), true);
      
      $medico->condicion = $request->condicion;
      $medico->id_grado = !empty($request->id_grado) ? $request->id_grado : null;
      $medico->cip = !empty($request->txtNroCip) ? trim($request->txtNroCip) : null;

      $medico->dni = trim($request->dni);
      $medico->tipo_documento = !empty($request->tipo_documento_id) ? $request->tipo_documento_id : null;
      $medico->paterno = trim(mb_strtoupper($request->paterno));
      $medico->materno = trim(mb_strtoupper($request->materno));
      $medico->nombre = trim(mb_strtoupper($request->nombre));

      $medico->fecha_nacimiento = !empty($request->fechaNacimiento) ? $request->fechaNacimiento : null;
      $medico->id_estado_civil = !empty($request->id_estadocivil) ? $request->id_estadocivil : null;
      $medico->nro_hijos = ($request->nro_hijos !== null && $request->nro_hijos !== '') ? (int) $request->nro_hijos : null;
      $medico->sexo = !empty($request->id_sexo) ? $request->id_sexo : null;
      $medico->celular = !empty($request->txtCelular) ? trim($request->txtCelular) : null;
      $medico->cel_emergencia = !empty($request->txtEmergencia) ? trim($request->txtEmergencia) : null;

      $medico->email = !empty(mb_strtoupper($request->email)) ? trim(mb_strtoupper($request->email)) : null;
      $medico->email_institucional = !empty(mb_strtoupper($request->email)) ? trim(mb_strtoupper($request->email_inst)) : null;
      $medico->domicilio = !empty(mb_strtoupper($request->txtDomicilio)) ? mb_strtoupper($request->txtDomicilio) : null;

      $medico->id_departamento = !empty($request->id_departamento) ? $request->id_departamento : null;
      $medico->id_provincia = !empty($request->id_provincia) ? $request->id_provincia : null;
      $medico->id_distrito = !empty($request->id_distrito) ? $request->id_distrito : null;

      $medico->id_estado_historial = 1;

      $medico->grupo_trabajo = !empty($request->grupoTrabajo) ? $request->grupoTrabajo : null;
      $medico->fecha_inicio = !empty($request->txtInicioLabo) ? $request->txtInicioLabo : null;
      $medico->fecha_cese = !empty($request->txtCeseLab) ? $request->txtCeseLab : null;

      $medico->id_situacion_personal = !empty($request->id_situacion_actual) ? $request->id_situacion_actual : null;
      $medico->id_condicion_laboral = !empty($request->id_condicion_laboral) ? $request->id_condicion_laboral : null;
      $medico->cargo = !empty(mb_strtoupper($request->cargo)) ? mb_strtoupper($request->cargo) : null;

      $medico->id_establecimiento = !empty($request->id_establecimiento) ? $request->id_establecimiento : null;
      $medico->id_unidad = !empty($request->id_unidad) ? $request->id_unidad : null;
      $medico->id_sub_unidad = !empty($request->id_sub_unidad) ? $request->id_sub_unidad : null;

      if (!empty($especialidades)) {
        foreach ($especialidades as $esp) {
          if ($esp['especialidadPrincipal'] == true) {
            $medico->id_profesion = $esp['id_profesion'];
            $medico->especialidad = $esp['especialidad'];
            $medico->colegiatura = $esp['nro_colegiatura'];
            $medico->rne = $esp['nro_especialidad'];
            $medico->fecha_inicio_habilidad = isset($esp['fechaInicioHabilidad']) && $esp['fechaInicioHabilidad'] !== '' ? $esp['fechaInicioHabilidad'] : null;
            $medico->fecha_termino_habilidad = isset($esp['fechaFinHabilidad']) && $esp['fechaFinHabilidad'] !== '' ? $esp['fechaFinHabilidad'] : null;
            $medico->id_especializacion = $esp['id_especializacion'];
            $medico->id_colegio_profesional = $esp['id_colegio_profesional'];
            $medico->estado_colegiatura = $esp['id_estado_colegiatura'];
            break;
          }
        }
      }

      $medico->estado = 1;
      $medico->id_user_created = $id_user;

      $medico->actualiza_datos_alerta = 0;

      if ($request->hasFile('archivo_firma')) {
        $imagen = $request->file('archivo_firma');
        $nombreimagen = Str::slug($request->dni).".".$imagen->guessExtension();
        $ruta = public_path("images/profesionales/");
        $imagen->move($ruta,$nombreimagen);
        $medico->nombre_firma = $nombreimagen;
      }

      $medico->save();

      if (!empty($especialidades)) {
        foreach ($especialidades as $esp) {
          $medicos_especialidades = new Medicos_especialidades();
          $medicos_especialidades->id_medico               = $medico->id;
          $medicos_especialidades->id_profesion            = $esp['id_profesion'];
          $medicos_especialidades->id_especialidad         = $esp['id_especializacion'];
          $medicos_especialidades->id_estado_reg           = !empty($esp['estado']) ? $esp['estado'] : 1;
          $medicos_especialidades->id_colegio_profesional  = $esp['id_colegio_profesional'];
          $medicos_especialidades->rne                     = $esp['nro_especialidad'];
          $medicos_especialidades->colegiatura             = $esp['nro_colegiatura'];
          $medicos_especialidades->estado_colegiatura      = $esp['id_estado_colegiatura'];
          $medicos_especialidades->fecha_inicio_habilidad  = isset($esp['fechaInicioHabilidad']) && $esp['fechaInicioHabilidad'] !== '' ? $esp['fechaInicioHabilidad'] : null;
          $medicos_especialidades->fecha_termino_habilidad = isset($esp['fechaFinHabilidad']) && $esp['fechaFinHabilidad'] !== '' ? $esp['fechaFinHabilidad'] : null;
          $medicos_especialidades->save();
        }
      }

      $id_medico = $medico->id;

      $consultorios = (array) $request->id_consultorio;

      $consultorios = array_filter($consultorios);

      if (empty($consultorios)) {
          $consultorioDefault = 1;

          $medico_consultorio = new Medico_consultorio;
          $medico_consultorio->id_establecimiento = $request->id_establecimiento;
          $medico_consultorio->id_medico = $id_medico;
          $medico_consultorio->id_consultorio = $consultorioDefault;
          $medico_consultorio->estado = 1;
          $medico_consultorio->id_user_created = $id_user;
          $medico_consultorio->save();

      } 
      else {
          foreach ($request->id_consultorio as $key => $value) {
            $medico_consultorio = new Medico_consultorio;
            $medico_consultorio->id_establecimiento = $request->id_establecimiento;
            $medico_consultorio->id_medico = $id_medico;
            $medico_consultorio->id_consultorio = $request->id_consultorio[$key];
            $medico_consultorio->estado = 1;
            $medico_consultorio->id_user_created = $id_user;
            $medico_consultorio->save();
          }
      }

      Session::flash('flash_message', 'Registro exitoso');
      return redirect('/gestion_medicos/' . $request->id_user);
    }
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
  
  public function desactivar_saenz(Request $request, $id, $estado)
  {
    if (!Auth::check()) :
        return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    
    if ($estado == "1") {

        $update = Medico::where('id', '=', $id)->first();
        
        if(!$update){
            return response()->json(['error' => 'Médico no encontrado'], 404);
        }

        $idEstadoHistorial = $request->input('id_estado_historial');
        $update->id_estado_historial = ($idEstadoHistorial !== null && $idEstadoHistorial !== '') ? (int) $idEstadoHistorial : null;
        $update->estado = 0;
        $update->id_user_desactivate = $id_user;
        $update->fec_desactivate = date('Y-m-d H:i:s');
        $update->save();
        
        $codigo_condicion = $request->input('codigo_condicion');
        $codigo_condicion = ($codigo_condicion === '' || $codigo_condicion === null) ? null : (int) $codigo_condicion;

        $total_periodo = $request->input('total_periodo');
        $total_periodo = ($total_periodo === '' || $total_periodo === null) ? null : (int) $total_periodo;

        $periodo = $request->input('periodo');
        $periodo = ($periodo === '' || $periodo === null) ? null : (int) $periodo;

        if($request->has('motivo')){
            DB::table('historial_medicos_desactivados')->insert([
                'id_medico' => $id,
                'motivo' => $request->input('motivo'),
                'fecha_deshabilitacion' => date('Y-m-d H:i:s'),
                'estado' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_us' => $id_user,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_us' => $id_user,
                'id_estado_historial' => (int) $request->input('id_estado_historial'),
                'total_periodo' => $total_periodo,
                'periodo' => $periodo,
                'codigo_condicion' => $codigo_condicion
            ]);
        }
        
        Session::flash('flash_message', 'Se desactivó correctamente');
        return response()->json(['success' => true, 'message' => 'Médico desactivado correctamente']);
        
    } else {
      
        $update = Medico::where('id', '=', $id)->first();
        
        if(!$update){
            return response()->json(['error' => 'Médico no encontrado'], 404);
        }
        
        $idEstadoHistorial = $request->input('id_estado_historial');
        $update->id_estado_historial = ($idEstadoHistorial !== null && $idEstadoHistorial !== '') ? (int) $idEstadoHistorial : null;
        $update->estado = 1;
        $update->id_user_activate = $id_user;
        $update->fec_activate = date('Y-m-d H:i:s');
        $update->save();

        \Log::info('Datos recibidos en activar_medico', [
            'id_medico' => $id,
            'estado' => $estado,
            'request' => $request->all()
        ]);

        if($request->has('motivo')){
            DB::table('historial_medicos_desactivados')->insert([
                'id_medico' => $id,
                'motivo' => $request->input('motivo'),
                'fecha_deshabilitacion' => date('Y-m-d H:i:s'),
                'estado' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_us' => $id_user,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_us' => $id_user,
                'id_estado_historial' => (int) $request->input('id_estado_historial')
            ]);
        }
        
        Session::flash('flash_message', 'Se activó correctamente');
        return response()->json(['success' => true, 'message' => 'Médico activado correctamente']);
    }
  }

  public function obtener_reniec($dni)
  {
    $WsReniec = new \App\Ws\Reniec;
    $datos_reniec = $WsReniec->BuscarDNI($dni);
    echo json_encode($datos_reniec);
  }

  public function listar_consultorios_ajax($id_tipo_servicio)
  {
    $p[] = $id_tipo_servicio;

    $model_upss = new \App\Models\Upss;
    $data = $model_upss->ShowConsultoriosTipoAtencion($p);

    $result["data"] = $data;
    echo json_encode($result);
  }

  public function listar_consultorios_hospitalizacion_desocupados($id_ipress)
  {
    $model_consultorio = new \App\Models\Consultorio;
    $data = $model_consultorio->ShowConsultorioHospitalizacionDesocupadosById_Ipress($id_ipress);

    $result["data"] = $data;
    echo json_encode($result);
  }

  public function listar_consultorios($id_ipress, $id_tipo_atencion)
  {
    $model_consultorio = new \App\Models\Consultorio;
    $data = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_ipress, $id_tipo_atencion);

    $result["data"] = $data;
    echo json_encode($result);
  }

  //alta medica

  public function alta_medica($id_user, $id_emergencia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_emergencia)->first();
    if(is_object($emergencia_prestacion)):
    $evaluacion="SELECT * from evaluaciones e where id_prestacion = '$emergencia_prestacion->id'";
    $evaluacion = DB::select($evaluacion);
    $evoluci=array();
    if(is_object($evaluacion)){
      $evoluci[0]=$emergencia_prestacion;
      $evoluci[1]=$evaluacion;

    }else{
      $evoluci[0]=$emergencia_prestacion;

    }

    //DATOS EMERGENCIA
    $id_establecimiento=Auth::user()->id_establecimiento;
    $model_emergencia = new Emergencia();
    $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
    $id_prestacion =  $prestacion->id;

    $emergencia = Emergencia::where('id', '=', $id_emergencia)->first();

    if($emergencia->id_referencia != 0){
      $model_referencia = new \App\Models\Referencia;
      $referencias = $model_referencia->GetBuscaReferenciaById($emergencia->id_referencia);
      $model_prestacionreferncia = new \App\Models\Prestaciones;
      $diagnostico_prestacion_referencia = $model_prestacionreferncia->GetDiagnosticoByIdPrestacion($referencias->id_prestacion);
      $procedimientos_refencia = $model_referencia->buscar_procedimiento_referencia($emergencia->id_referencia);
      $servicios_ipress_origen = $model_referencia->busca_servicio_por_ipres_origen_referencia($referencias->id_establecimiento);

    }else{
      $diagnostico_prestacion_referencia = "";
      $referencias = "";
      $procedimientos_refencia="";
      $servicios_ipress_origen="";
    }

    $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_emergencia,$emergencia->estado);
    $egreso = $model_emergencia->GetEgresoEmergencia($id_emergencia);
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_kardex = new \App\Models\Kardexe();

    $modelfarma = new \App\Models\Farmacia;
    $model_producto = new \App\Models\Producto;

    $fechaServidor = $model_kardex->getFechaServidor();
    $horaservidor = $model_kardex->getHoraServidor();

    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    $fecha_calculo = $emergencia->fecha_registro_ingreso;
    $dias_estancia = $model_emergencia->GetDiaMayorEstancia($emergencia->fecha_registro_ingreso);

    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

    if($prestacion->id_ext!=0):
      if($prestacion->tipo_atencion=='EMERGENCIA'):
        $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
        if($dato_emergencia->id_prioridad<4):
          $model_emergencia = new \App\Models\Emergencia;
          $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
          $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
          $sw = 1;
          $prestacion->servicio= $obtenerdato->servicio;
          $prestacion->consultorio= $obtenerdato->consultorio;
          $prestacion->id_ups_origen= $obtenerdato->id_ups;
          $prestacion->id_servicio_origen= $obtenerdato->id_servicio;
        else:
          $prestacion->servicio="IV - PATOLOGÍA AGUDA COMÚN";
          $prestacion->consultorio="CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
          $prestacion->consultorio= 0;
          $prestacion->consultorio= 8;
          $sw = 0;
        endif;
      endif;
    else:
         return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
    endif;
    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
    $estable = "select * from establecimientos e where id_estado_reg = '1'";
    $estable=  DB::select($estable);
     $model_consultorio = new \App\Models\Consultorio;
     $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);

     $hospitalizacion= "SELECT
     concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
     u.id as id_upss,
     u.codigo as codigo_upss,
     c.id as id_consultorio,
     c.nombre as nombre_consultorio
     from consultorios c
     inner join upsses u on u.id_consultorio = c.id
     where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc ";
    $hospitalizacion = DB::select($hospitalizacion);

    /*$upss="SELECT
    concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
    u.id as id_upss,
    u.codigo as codigo_upss,
    c.id as id_consultorio,
    c.nombre as nombre_consultorio
    from consultorios c
    inner join upsses u on u.id_consultorio = c.id
    where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc";
    $upss= DB::select($upss);*/

    $diagnosticos = "SELECT * from diagnosticos d where estado = '1' order by nombre desc";
    $diagnosticos = DB::select($diagnosticos);
    //VALIDAR MEDICO
    $dni=Auth::user()->dni;

    $servicio_referen = "SELECT * from consultorios where estado = '4' order by nombre desc ";
    $servicio_referen = DB::select($servicio_referen);
    $model_medico = new Medico();
    $exite_medico=$model_medico->exite_medico($dni);
    $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
    $medico = $model_medico->GetDatosMedicoAndEstablecimiento($dni, $id_establecimiento);
    $id_asegurado= $prestacion->id_asegurado;
    $model_emergencias= new Emergencia();
    //$datos_emergencia = $model_emergencias->GetEmergenciaByIdAsegurado($id_asegurado);
    $datos_emergencia = $model_emergencias->GetEmergenciaByIdAseguradoEmergencia($id_asegurado, $prestacion->id_ext);
    $dia_estancia = $datos_emergencia->dia_estancia;
    $codigo_siteds = $model_medico->buscarSitedsByEmergenciaId($id_emergencia);

    /*$upsses = "SELECT DISTINCT up.id id_upss, up.descripcion_upss, up.codigo codigo_upss, co.estado
      FROM upsses as up
      INNER JOIN consultorios as co ON co.id = up.id_consultorio
      WHERE up.estado = 1
      And up.id_tipo_atencion = 3
      ORDER BY up.descripcion_upss;";
    $upsses= DB::select($upsses);*/

    $model_cama = new Cama();
    $pabellones = $model_cama->GetAllPabellones($id_establecimiento, 'HOSPITALIZACION');

    $model_cqx_tipo_profesional = new \App\Models\CqxTipoProfesional();
    $cqx_tipo_profesionales = $model_cqx_tipo_profesional->GetAllActivos();
    $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
    $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();

    $fecha_ultima_atencion = null;
    $id_ultima_evolucion = 0;
    $sql_existe_evolucion = "SELECT count(e.id) from emergencias e
                        inner join evaluaciones e2 on e2.id_emergencia = e.id
                        where e.id = '$id_emergencia' and e2.id_estado_reg <> 0";
    $existe_evolucion = DB::select($sql_existe_evolucion);
    $existe_evolucion = $existe_evolucion[0]->count;

    if (intval($existe_evolucion) === 0) {
      $fecha_ultima_atencion = $emergencia->fecha_atencion;
    } else {
      $sql_evaluaciones = "SELECT e2.* from emergencias e
                        inner join evaluaciones e2 on e2.id_emergencia = e.id
                        where e.id = '$id_emergencia' and e2.id_estado_reg <> 0 order by e2.created_at desc";
      $dato_evalucion = DB::select($sql_evaluaciones);
      $id_ultima_evolucion = $dato_evalucion[0]->id;
      $fecha_ultima_atencion = $dato_evalucion[0]->created_at;
    }

    $timestamp = strtotime($fecha_ultima_atencion);
    $fecha_ultima_atencion = date("d-m-Y H:i:s", $timestamp);

    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_ipress = $farmacia->id_establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    if ($id_ipress == 1){
      $nivel = 2;
      $id_farmacia = Auth::user()->id_farmacia;
    }else{
      if($id_ipress==76){
        $id_farmacia = 19;
      }
      else{
        $id_farmacia = Auth::user()->id_farmacia;
      }
      $nivel = $establecimiento[0]->nivel;
    }

    $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
    $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia);
    $id_tipo_atencion = $prestacion->id_tipo_atencion;

    if($exite_medico==0){
       Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
      return redirect('/all_atenciones_by_emergencia');
    }else if($exite_medico_ipress==""){
      $model_establecimiento = new Establecimiento();
      $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
      Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
      return redirect('/all_atenciones_by_emergencia');
    }else{
       return view('frontend.medicos.alta_medica', compact('id_tipo_atencion','farmacias','id_ipress','producto','farmacia','evoluci','servicio_referen','dias_estancia','fecha_calculo',/*'upss',*/'estable','fechaEgreso','servicios_ipress_origen','referencias','procedimientos_refencia','diagnostico_prestacion_referencia','horaEgreso', 'prestacion', 'diagnosticos', 'diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor','nivel', 'flag_pendinte_firma','hospitalizacion','consulta_externa','paciente_emergencia','emergencia','dia_estancia','sw','datoadicional','medico', 'codigo_siteds','cqx_tipo_profesionales','tipo_cirugias',/*'upsses',*/'pabellones', 'fecha_ultima_atencion', 'id_establecimiento'));
    }
  else:
    Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Medica');
    return redirect('/all_atenciones_by_emergencia');
  endif;
  }
  public function alta_medica_ver($id_user, $id_emergencia)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_emergencia)->first();
    if(is_object($emergencia_prestacion)):
    $evaluacion = null;
    $dato_traslado = null;
    $id_establecimiento=Auth::user()->id_establecimiento;
    $model_emergencia = new Emergencia();
    $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
    $id_prestacion =  $prestacion->id;
    $emergencia = Emergencia::where('id', '=', $id_emergencia)->first();
    $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_emergencia,$emergencia->estado);
    $egreso = $model_emergencia->GetEgresoEmergencia($id_emergencia);
    $dato_traslado = $model_emergencia->GetVerUbicacionTraslado2($id_emergencia);
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_kardex = new \App\Models\Kardexe();
    $model_evaluacion = new \App\Models\Evaluacione();
    $fechaServidor = $model_kardex->getFechaServidor();
    $horaservidor = $model_kardex->getHoraServidor();

    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
    $diagnosticos_egreso = $model_prestacion->GetDiagnosticoByIdAltaPrestacion($id_prestacion);
    $evaluacion = $model_evaluacion->GetEvaluacionesByIdExt($id_emergencia);

    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;

    $id_principal = null;

    if($prestacion->id_ext!=0):
      if($prestacion->tipo_atencion=='EMERGENCIA'):
        $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
        $id_principal = $dato_emergencia->id;
        if($dato_emergencia->id_prioridad<4):
          $model_emergencia = new \App\Models\Emergencia;
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
    else:
         return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
    endif;

    $gestacion = null;
    $puerperio = null;
    $natis = null;
    if (isset($emergencia -> id_gestacion)) {
      $gestacion = Gestacion::find($emergencia -> id_gestacion);
      error_log('HAY GESTACION EN ALTA: '.(string)$gestacion);
    }
    if (isset($emergencia -> id_puerperio)) {
      $puerperio = Puerperio::find($emergencia -> id_puerperio);
      error_log('HAY PUERPERIO EN ALTA: '.(string)$puerperio);
      $natis = $puerperio->natis;
      error_log('sus natis: '.(string)$natis);
    }

    $fechaEgreso = $model_kardex->getFechaServidor();
    $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    $is_habilitado_firma_electronica = $establecimiento[0]->flag_firma_electronica;
    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
    if($emergencia->id_tipo_egres_med==3){
      $estable = "select * from establecimientos e where id = '$emergencia->id_establecimiento_traslado'";
      $estable=  DB::select($estable);
      $estable = $estable[0];
    }

     $model_consultorio = new \App\Models\Consultorio;
     $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
     $hospitalizacion = "select u.descripcion_upss as nombre,
                        u.id,
                        u.codigo
                        from consultorios c
                        inner join upsses u on u.id_consultorio = c.id
                        where u.id_tipo_atencion = '3' and u.estado = '1' and u.descripcion_upss like 'H%'";
      $hospitalizacion = DB::select($hospitalizacion);

      $model_receta = new Receta_vale;
      $modelfarma = new Farmacia;
      $model_producto = new Producto;

      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $receta_alta = $model_receta->GetDatosRecetasAltasByIdPrestacion($id_prestacion);

      $id_ipress = $farmacia->id_establecimiento;
      if($id_ipress==76): 
        $id_farmacia = 19; 
      else:
        $id_farmacia = $farmacia->id;
      endif;
      
      $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
      $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);

      if($emergencia->id_tipo_egres_med=="2"){
        $upss="SELECT
        concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
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
        $upss="0";
      }
      $prioridad="SELECT * from prioridades p where id = '$emergencia->id_prioridad'";
      $prioridad= DB::select($prioridad);
      $prioridad=$prioridad[0];

      $triajes="SELECT * from triajes where id = '$emergencia->id_triaje'";
      $triajes= DB::select($triajes);
      $triajes=$triajes[0];
      $dni=Auth::user()->dni;
      $model_medico = new Medico();
      $exite_medico=$model_medico->exite_medico($dni);
      $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);

      $model_usuario = new User();
      $usuario=$model_usuario->GetById($emergencia->id_medico_egreso);
      $medico = $model_medico->GetDatosMedicoAndEstablecimiento($usuario->dni, $usuario->id_establecimiento);

      $model_intervencion = new \App\Models\CqxIntervencion;
      $existe_intervencion = $model_intervencion::where('id_prestacion',$id_prestacion)->exists();
      $datos_intervencion="";
      $procedimientos_cqx="";
      $profesionales_cqx="";
      if($existe_intervencion){
        $datos_intervencion = $model_intervencion::select('cqx_intervenciones.id','tc.descripcion as tipo_cirugia','cqx_intervenciones.emergencia_duracion_cirugia')
        ->leftJoin('cqx_tipo_cirugias as tc', 'cqx_intervenciones.emergencia_tipo_cirugia', '=', 'tc.id')
        ->where('cqx_intervenciones.id_prestacion',$id_prestacion)->first();
        //DIAGNOSTICOS QUIRURGICOS
        $diagnosticos_cqx = DB::table('receta_diagnosticos as rd')
              ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
              ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
              ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
              ->where('rd.id_intervencion', '=', $datos_intervencion->id)
              ->where('rd.id_prestacion', '=', $id_prestacion)
              ->get();

        //PROCEDIMIENTOS QUIRURGICOS
        $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
        $procedimientos_cqx = $model_cq_procedimiento->GetByIdIntervencionQuirugica($datos_intervencion->id);
        //PROFESIONALES QUIRURGICOS
        $model_cq_profesional = new \App\Models\CqxProfesional();
        $profesionales_cqx = $model_cq_profesional->GetByIdIntervencionQuirugicaYTipoPersonal($datos_intervencion->id, 0);
      }

      $pabellon = DB::table('pabellones')
                  ->where('pabellones.id', $emergencia->id_pabellon_ubicacion)
                  ->first();

      $upss_alta = DB::table('upsses')
                    ->where('upsses.id', $emergencia->id_upss_alta)
                    ->first();

      if($exite_medico==0){
        Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
        return redirect('/all_atenciones_by_emergencia');
      }else if($exite_medico_ipress==""){
        $model_establecimiento = new Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
        return redirect('/all_atenciones_by_emergencia');
      }else{
        return view('frontend.medicos.alta_medica_ver1', compact('diagnosticos_egreso','id_farmacia','id_principal','producto','farmacias','receta_alta','triajes','prioridad','prestacion','estable','upss','diagnosti','diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'paciente_emergencia', 'emergencia','medico','datoadicional', 'sw' , 'gestacion', 'puerperio', 'natis','evaluacion','existe_intervencion','datos_intervencion','diagnosticos_cqx','procedimientos_cqx','profesionales_cqx','dato_traslado', 'pabellon', 'upss_alta', 'is_habilitado_firma_electronica'));
      }
      else:
        Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Medica');
        return redirect('/all_atenciones_by_emergencia');
     endif;
  }

  public function registrar_alta_medica(Request $request){
      if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_prestacion = new \App\Models\Prestaciones;
    $model_emergencia = new \App\Models\Emergencia;
    $id_user =  Auth::user()->id;

    //$updateid=$model_prestacion->AgregarIdExt($id_prestacion,$id_emergencia);

    $derivar_consulta = $request->id_servicio_derivar_egreso != '' ? 'SI' : 'NO';
    $id_sala_egreso = $request->servicio_egreso != '' ? $request->servicio_egreso : 0;
    $id_condicion_egreso = $request->condicion_egreso != '' ? $request->condicion_egreso : 0;
    $id_servicio_deriva_egreso = $request->id_servicio_derivar_egreso != '' ? $request->id_servicio_derivar_egreso : 0;

    $updateEgreso = $model_emergencia->RegistrarEgreso($request->id_emergencia, $id_user, $request->fecha_egreso, $request->hora_egreso, $request->tipo_egreso, $request->observacion_egreso, $id_sala_egreso, $id_condicion_egreso, $derivar_consulta, $id_servicio_deriva_egreso);

    if ($updateEgreso):
      Session::flash('flash_message', 'Alta Medica exitosa');
      return redirect('/alta_medica/' . $id_user . '/' . $request->id_emergencia);
    else:
      Session::flash('flash_message', 'No se pudo registrar el alta Medica');
      return redirect('/all_atenciones_by_emergencia');
    endif;
  //   $model_emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
  //   $model_emergencia->estado= "5";
  //   $model_emergencia->secuencia= 5;
  //   $model_emergencia->fecha_egreso = $request->fecha_egreso;
  //   $model_emergencia->hora_egreso = $request->hora_egreso;
  //   $model_emergencia->fecha_registro_egreso    = date('Y-m-d');
  //   $model_emergencia->hora_registro_egreso = date('H:i:s');
  //   $model_emergencia->id_medico_egreso= Auth::user()->id;
  //   $model_emergencia->id_tipo_egreso= $request->id_tipo_egreso;

  //   if($request->id_tipo_egreso=="1"){ //ALTA
  //       $model_emergencia->derivar= $request->derivar;
  //       $model_emergencia->id_servicio_destino= $request->id_servicio_consulta_externa;
  //       $model_emergencia->necropsia="";
  //       $model_emergencia->causas_muerte="";
  //       $model_emergencia->id_condicion_alta= $request->id_condicion_alta;

  //   }else if($request->id_tipo_egreso=="2"){ //HOSPITALIZACIÓN
  //       $model_emergencia->id_servicio_destino= $request->id_servicio_hospitalizacion;
  //       $model_emergencia->derivar="";
  //       $model_emergencia->necropsia="";
  //       $model_emergencia->causas_muerte="";

  //   }else if($request->id_tipo_egreso=="3"){ //DEFUNCIÓN
  //       $model_emergencia->necropsia= $request->necropsia;
  //       $model_emergencia->causas_muerte= $request->causas_muerte;
  //       //$model_emergencia->id_servicio_destino="";
  //       $model_emergencia->derivar="";

  //   }else if($request->id_tipo_egreso=="4"){ //FUGADO
  //       /*$model_emergencia->necropsia= "";
  //       $model_emergencia->causas_muerte= "";
  //       $model_emergencia->id_servicio_destino="";
  //       $model_emergencia->derivar="";*/
  //   }

  //   $model_emergencia->save();

  //  //diagnostico
  //   $id_prestacion =  $request->id_prestacionx;
  //   $id_user =  Auth::user()->id;

  //   if (isset($request->id_diagnostico)) :
  //     foreach ($request->id_diagnostico as $key => $value) :
  //       if ($request->id_diagnostico[$key] != "") {
  //         $model_receta_diagnostico = new \App\Models\Receta_diagnostico;
  //         //$id_receta_vale
  //         $model_receta_diagnostico->id_diagnostico = $request->id_diagnostico[$key];
  //         $model_receta_diagnostico->id_tipo_diagnostico = $request->id_tipo_diagnostico[$key];
  //         $model_receta_diagnostico->estado  = "1";
  //         $model_receta_diagnostico->id_prestacion = $id_prestacion;
  //         //created_at    updated_at  updated_us  deleted_at  deleted_us
  //         $model_receta_diagnostico->created_us = $id_user;
  //         $model_receta_diagnostico->id_clasificacion_diagnosticos="3";
  //         $model_receta_diagnostico->save();
  //       }
  //     endforeach;
  //   endif;

      // if ($request->id_sub_consultorio != 976) {
      //   Session::flash('flash_message', 'Alta Medica exitosa');
      //   return redirect('/alta_medica/' . $id_user . '/' . $request->id_emergencia);
      // } else {
      //   Session::flash('flash_message', 'Alta Medica exitosa');
      //   return redirect('/alta_medica/' . $request->id_user . '/' . $request->id_emergencia);
      // }
  }

  //alta administrativa
  public function alta_medica_administrativa($id_user, $id_emergencia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    //DATOS EMERGENCIA
    $model_emergencia = new Emergencia();

    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_emergencia)->first();
    if(is_object($emergencia_prestacion)):
      $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
      $id_prestacion =  $prestacion->id;


      $emergencia = Emergencia::where('id', '=', $id_emergencia)->first();

      $modelfarma = new \App\Models\Farmacia;
      $model_receta = new \App\Models\Receta_vale;
      $model_producto = new \App\Models\Producto;
      $model_productos_receta = new \App\Models\Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_kardex = new \App\Models\Kardexe();

      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $id_farmacia = $farmacia->id;
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

      if($prestacion->id_ext!=0):
        if($prestacion->tipo_atencion=='EMERGENCIA'):
          $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
          if($dato_emergencia->id_prioridad<4):
            $model_emergencia = new \App\Models\Emergencia;
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
      else:
           return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;
      $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
      $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

      $id_ipress = $farmacia->id_establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      if ($id_ipress == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }

       $model_upss = new \App\Models\Upss;
       $p[] =  '1';
       $consulta_externa = $model_upss->ShowConsultoriosTipoAtencion($p);

       $h[] =  '3';
       $hospitalizacion = $model_upss->ShowConsultoriosTipoAtencion($h);

      if($emergencia->fecha_egreso!=''){
        $fechaEgreso = $emergencia->fecha_egreso;
        $horaEgreso = $emergencia->hora_egreso;
        $dato = 1; //alta medica
      }
      else
      {
        $fechaEgreso = $model_kardex->getFechaServidor();
        $horaEgreso = $model_kardex->getHoraServidor();
        $dato = 2;
      }

      if($emergencia->fecha_egreso_administrativa!=''){
        $fechaEgresoAdmin = $emergencia->fecha_egreso_administrativa;
        $horaEgresoAdmin = $emergencia->hora_egreso_administrativa;
      }
      else
      {
        $fechaEgresoAdmin = $model_kardex->getFechaServidor();
        $horaEgresoAdmin = $model_kardex->getHoraServidor();
      }

      $inicio= ($emergencia->fecha_registro_ingreso);
      $final= ($emergencia->fecha_alta_medica);
      $inicios= strtotime($emergencia->fecha_registro_ingreso);
      $finals= strtotime($emergencia->fecha_alta_medica);

        $resulta=($finals-$inicios)/86400;

        if($resulta == "0"){
          $resta = '1';
        }else{
          $resta=$resulta;
        }

      if($emergencia->id_tipo_egres_med == '1'){//solo alta medica
        return view('frontend.medicos.alta_adminisa', compact('farmacia','resta','prestacion','diagc','diagnostico', 'id_prestacion', 'fechaServidor2', 'horaservidor2', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'emergencia', 'fechaEgreso','horaEgreso', 'dato', 'fechaEgresoAdmin','horaEgresoAdmin' ));
      }else if($emergencia->id_tipo_egres_med == '2'){
        Session::flash('flash_message', 'No Se Realiza Alta Administrativa Cuando Se Tiene Un Alta Medica De Hospitalizado');
        return redirect('/all_altas_medica_by_emergencia');
      }else if($emergencia->id_tipo_egres_med == '3'){
        Session::flash('flash_message', 'No Se Realiza Alta Administrativa Cuando Se Tiene Un Alta Medica De Traslado');
        return redirect('/all_altas_medica_by_emergencia');
      }else if($emergencia->id_tipo_egres_med == '4'){
        Session::flash('flash_message', 'No Se Realiza Alta Administrativa Cuando Se Tiene Un Alta Medica De Fallecido');
        return redirect('/all_altas_medica_by_emergencia');
      }

      return view('frontend.medicos.alta_administrativa', compact('farmacia','prestacion','diagnostico', 'id_prestacion', 'fechaServidor2', 'horaservidor2', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'emergencia', 'fechaEgreso','horaEgreso', 'dato', 'fechaEgresoAdmin','horaEgresoAdmin' ));


    else:
      Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Administrativa');
      return redirect('/all_altas_medica_by_emergencia');
    endif;
  }

  //public function registrar_alta_medica_emer(Request $request){
  public function registrar_alta_medica_emer(AltaFormRequest $request) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');
    $model_emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
    $dni = Auth::user()->dni;
    $titular_prueba_jhon = Asegurado::where('id', '=', $model_emergencia->id_asegurado)->first();
    $dni_prueba_jhon = $titular_prueba_jhon->nro_doc_ident;

    $celular = Auth::user()->celular;

    if ($celular == '2') {
      $titular = Asegurado::where('id', '=', $model_emergencia->id_asegurado)->first();
      $titular->nro_doc_ident = '1234567';
      $titular->save();
    }

    if ($celular == '3') {
      $titular = Asegurado::where('id', '=', $model_emergencia->id_asegurado)->first();
      $titular->sexo = 'F';
      $titular->save();
    }

    $medico = DB::table('medicos')
      ->select('medicos.*')
      ->where('dni', $dni)
      ->first();

    //SE PASO A ESTA PARTE PARA REUTILIZAR
    $model_receta  = new \App\Models\Receta_vale;
    $model_emergencia2 = new Emergencia();
    $valida_prestacion = $model_emergencia2->getIdPrestacion($request->id_emergencia);
    $model_intervencion_validar = new \App\Models\CqxIntervencion;
    $existe_intervencion_cqx = $model_intervencion_validar::where('id_prestacion', $valida_prestacion->id)->exists();
    //FIN SE PASO A ESTA PARTE PARA REUTILIZAR

    //SI YA EXISTE LA INTERVENCION QUIRURGICA DE LA LA PRESTACION
    if ($existe_intervencion_cqx) {
      if (intval($request->pendiente_alta_emergencia_interconsulta_qx) == 0) {
        //SI SELECCIONO INTERVENCION QUIRURGICA
        if ((string)$request->requiere_intervencion_quirurgica === '1') {
          //RETORNARA EL MENSAJE QUE LA PRESTACION YA HA SIDO REGISTRADO
          Session::flash('flash_message', 'No se pudo realizar el alta médica del paciente que requiere intervención quirúrgica, ya que la prestación correspondiente ya ha sido registrada en el módulo de Centro Quirúrgico.');
          return redirect('/alta_medica/' . Auth::user()->id . '/' . $request->id_emergencia);
        }
      }
    }

    if ($model_emergencia->id_referencia != 0) {
      if ($request->id_tipo_egreso == 3) {
        $datos_referencia = Referencia::find($model_emergencia->id_referencia);

        $model_actuliza_estado = new \App\Models\Actualizacion_estado;
        $model_actuliza_estado->estado_antiguo = $datos_referencia->estado_proceso;
        $model_actuliza_estado->estado_nuevo = 8;
        $model_actuliza_estado->id_referencia = $datos_referencia->id;
        $model_actuliza_estado->motivo_actualizacion = "Se guardo el alta en emergencia realizandoce la contrareferencia";
        $model_actuliza_estado->created_us = Auth::user()->id;
        $model_actuliza_estado->flag_estado_reg = 1;
        $model_actuliza_estado->save();

        $datos_referencia->estado_proceso = 8;
        $datos_referencia->id_servicio_contrareferencia = $request->especialida_destino;
        $datos_referencia->observacion_contrareferencia = $request->especialidad_destino_otro;
        $datos_referencia->id_establecimiento_contrareferencia = $request->id_establecimiento_traslado;
        $datos_referencia->id_medico_contrareferencia =  $medico->id;
        $datos_referencia->id_prestacion_contrareferencia = $request->id_emergencia;
        $datos_referencia->fecha_atencion_cotrareferencia = $fechaActual;
        $datos_referencia->save();
      } else {
        $datos_referencia = Referencia::find($model_emergencia->id_referencia);

        $model_actuliza_estado = new \App\Models\Actualizacion_estado;
        $model_actuliza_estado->estado_antiguo = $datos_referencia->estado_proceso;
        $model_actuliza_estado->estado_nuevo = 9;
        $model_actuliza_estado->id_referencia = $datos_referencia->id;
        $model_actuliza_estado->motivo_actualizacion = "Se guardo el alta en emergencia pero no se selecciono traslado para la contrareferencia";
        $model_actuliza_estado->created_us = Auth::user()->id;
        $model_actuliza_estado->flag_estado_reg = 1;
        $model_actuliza_estado->save();

        $datos_referencia->estado_proceso = 9;
        $datos_referencia->id_servicio_contrareferencia = 9;
        $datos_referencia->observacion_contrareferencia = "";
        $datos_referencia->id_medico_contrareferencia =  $medico->id;
        $datos_referencia->id_prestacion_contrareferencia = $request->id_emergencia;
        $datos_referencia->fecha_atencion_cotrareferencia = $fechaActual;
        $datos_referencia->save();
      }
    }
    $fecha_cierre = $request->fecha_egreso;
    $hora_cierre = $request->hora_egreso;
    $fecha_cierre = date('Y-m-d', strtotime($fecha_cierre));
    $date = $fecha_cierre;
    $fechaActual = $fecha_cierre;
    $horaActual = $hora_cierre;
    $fechaHoraGuardadoAlta = Carbon::now();
    $fechaAltaReal =$fecha_cierre;
    $horaAltaReal = $hora_cierre; 
    $id_user =  Auth::user()->id;
    $model_emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $request->id_emergencia)->first();

    if (isset($request->id_producto)) :
        if ($request->id_producto[0] != "ru='undefined'") :
            $p_producto = "{";
            foreach ($request->id_producto as $key => $value) :
                if ($request->id_producto[$key] != "") {
                    $p_producto .= "{";
                    $p_producto .= $request->id_producto[$key] . ",";
                    $p_producto .= (isset($request->tiempo_periodo[$key]) && $request->tiempo_periodo[$key] != "") ? $request->tiempo_periodo[$key] : 0;
                    $p_producto .= ",";
                    $p_producto .= (isset($request->nro_med_solictados[$key]) && $request->nro_med_solictados[$key] != "") ? $request->nro_med_solictados[$key] : 0;
                    $p_producto .= ",";
                    $p_producto .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
                    $p_producto .= ",";
                    $p_producto .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
                    $p_producto .= ",";
                    $p_producto .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
                    $p_producto .= ",";
                    $p_producto .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
                    $p_producto .= ",";
                    $p_producto .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
                    $p_producto .= ",";
                    $p_producto .= (isset($request->miSelectProd[$key]) && $request->miSelectProd[$key] != "") ? $request->miSelectProd[$key] : 0;
                    $p_producto .= ",";
                    $p_producto .= (isset($request->id_via_administracion[$key]) && $request->id_via_administracion[$key] != "") ? $request->id_via_administracion[$key] : 0;
                    $p_producto .= "},";
                }
            endforeach;

            if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
            $p_producto .= "}";

            $p_diagnostico = "{";
            if (isset($request->id_diagnostico)) :
                foreach ($request->id_diagnostico as $key => $value) :
                    if ($request->id_diagnostico[$key] != "") {
                        $tipo_diag = (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] != "") ? $request->id_tipo_diagnostico[$key] : 2;
                        $p_diagnostico .= "{" . $request->id_diagnostico[$key] . "," . $tipo_diag . "}";
                        $p_diagnostico .= ",";
                    }
                endforeach;
                if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
            endif;
            $p_diagnostico .= "}";

            $id_receta_vale      = 0;
            $id_anestesiologo    = 0;
            $id_receta_vale_bus  = (Auth::user()->tipo_registro == 3) ? 1 : 0;
            $id_admision         = 0;
            $tipo_via = "";
            $sub_via = "";
            $final_via = "";
            $nro_guia = "";
            $interconsulta = "";
            $interconsulta_dias  = 0;
            $nro_receta = "";
            $digitos_adicionales = "";
            $id_tipo_num         = 0;
            $estado_receta       = 2;
            $obs_registro        = "";
            $id_consultorio      = 76;
            $id_tiporeceta       = 2; 

            $hora = Carbon::now()->toTimeString();
            if ($hora > '00:00' && $hora < '07:31') {
                $turno = 'MD';
            } elseif ($hora > '07:30' && $hora < '13:31') {
                $turno = 'M';
            } elseif ($hora > '13:30' && $hora < '19:31') {
                $turno = 'T';
            } else {
                $turno = 'N';
            }

            $asegurado = Asegurado::find($emergencia_prestacion->id_asegurado);
            $cita = Cita::find($emergencia_prestacion->id_cita);
            $titular = Asegurado::find($asegurado->id_asegurado_titular);
            $historia = Asegurado_historia::where('id_asegurado',$cita->id_asegurado)->where('id_establecimiento',$cita->id_establecimiento)->first();

            $tipo_beneficiario = $cita->parentesco;
            $grado_ase = $cita->grado;
            $codigo_upss_req = $emergencia_prestacion->codigo_upss;
            $id_medico_receta = $medico->id;
            $id_establecimiento_hc = $cita->id_establecimiento;
            $nro_historia = isset($historia->nro_historia) ? $historia->nro_historia : "";
            
            $receta_flag_origen = 1; 

            $recetaExistente = DB::table('receta_vales')->where('id_prestacion', $emergencia_prestacion->id)
                                ->where('flag_origen', $receta_flag_origen)->where('estado', 2)->first();
                                
            if ($recetaExistente) {
                $id_receta_vale = $recetaExistente->id;
            }else{
                  $id_receta_vale = $model_receta->postRegistrarRecetaValeAltas(
                    $id_receta_vale,
                    $request->id_farmacia_receta,
                    $request->id_user,
                    $id_consultorio,
                    $nro_receta,
                    $digitos_adicionales,
                    $asegurado->nro_doc_ident,        
                    $asegurado->cip,                  
                    $asegurado->tipo_doc_ident,       
                    $asegurado->nombre,                
                    $asegurado->paterno,               
                    $asegurado->materno,                
                    $tipo_beneficiario,                 
                    $titular->nro_doc_ident,           
                    $titular->tipo_doc_ident,          
                    $titular->nombre,                  
                    $titular->paterno,                 
                    $titular->materno,                  
                    $request->fecha_expedicion,
                    $fechaActual,
                    $nro_historia,                   
                    $grado_ase,                         
                    $id_medico_receta,
                    $id_anestesiologo,
                    $id_tiporeceta,
                    $turno,
                    $obs_registro,
                    $id_receta_vale_bus,
                    $id_admision,
                    $tipo_via,
                    $sub_via,
                    $final_via,
                    $nro_guia,
                    $interconsulta,
                    $interconsulta_dias,
                    $emergencia_prestacion->id,          
                    $id_tipo_num,
                    $estado_receta,
                    $p_producto,
                    "{}",   
                    $asegurado->sexo,                  
                    $asegurado->fecha_nac,               
                    $codigo_upss_req,
                    $receta_flag_origen                 
                  );
          
            if (intval($id_receta_vale) <= 0) {
                throw new \Exception("Error al intentar registrar la Receta en el Alta Médica.");
            }
          }
     endif;
    endif;

    if (isset($request->id_diagnostico)) :
        foreach ($request->id_diagnostico as $key => $value) :
            if ($request->id_diagnostico[$key] != "") {

                $tipo_diag = (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] != "")
                    ? $request->id_tipo_diagnostico[$key]
                    : 2; 

                $yaExiste = \App\Models\Receta_diagnostico::where('id_prestacion', $emergencia_prestacion->id)
                    ->where('id_diagnostico', $request->id_diagnostico[$key])
                    ->where('id_clasificacion_diagnosticos', 3)
                    ->where('estado', 1)
                    ->exists();

                if (!$yaExiste) {
                    $diag_egreso = new Receta_diagnostico();
                    $diag_egreso->id_diagnostico               = $request->id_diagnostico[$key];
                    $diag_egreso->id_tipo_diagnostico           = $tipo_diag;
                    $diag_egreso->id_clasificacion_diagnosticos = 3; 
                    $diag_egreso->id_prestacion                 = $emergencia_prestacion->id;
                    $diag_egreso->id_receta_vale                = null;
                    $diag_egreso->id_evaluacion                 = 0;
                    $diag_egreso->id_consejeria                 = 0;
                    $diag_egreso->id_interconsulta              = 0;
                    $diag_egreso->estado                        = 1;
                    $diag_egreso->created_at                    = Carbon::now();
                    $diag_egreso->created_us                    = $request->id_user;
                    $diag_egreso->save();
                }
            }
        endforeach;
    endif;

    $existeevolucion = "SELECT count(e.id)  from emergencias e
                        inner join evaluaciones e2 on e2.id_emergencia = e.id
                        where e.id = '$request->id_emergencia' and e2.id_estado_reg <> 0";
    $existeevolucion = DB::select($existeevolucion);
    $existeevolucion = $existeevolucion[0]->count;

    if ($existeevolucion == 0) {
      $fecha_hora_ingreso = $model_emergencia->fecha_atencion;
      $id_evaluacion = 0;
    } else {
      $datoevalucion = "SELECT e2.* from emergencias e
                        inner join evaluaciones e2 on e2.id_emergencia = e.id
                        where e.id = '$request->id_emergencia' and e2.id_estado_reg <> 0 order by e2.created_at desc";
      $datoevalucion = DB::select($datoevalucion);
      $fecha_hora_ingreso = $datoevalucion[0]->created_at;
      $id_evaluacion = $datoevalucion[0]->id;
    }

    $unido = ($fecha_cierre . " " . $hora_cierre);
    $resulta = ((strtotime($unido)) - (strtotime($fecha_hora_ingreso))) / 86400;

    if ($resulta <= 0) {
      Session::flash('flash_message', 'NO SE REGISTRO EL ALTA - LA FECHA DE ALTA INGRESADA ' . $unido . ' ES MENOR A LA FECHA DE LA ULTIMA ATENCION ' . $fecha_hora_ingreso . ' - FORMATO DE FECHA 24 HORAS, MODIFIQUE LA FECHA DE ALTA');
      return redirect('/alta_medica/' . $id_user . '/' . $request->id_emergencia);
    }

    $dato_triaje_cpms = "SELECT * from triajes where id = '$model_emergencia->id_triaje'";
    $dato_triaje_cpms = DB::select($dato_triaje_cpms);

    $resulta_hora = ((strtotime($unido)) - (strtotime($fecha_hora_ingreso))) / 43200;
   
    $estable_nivel =  DB::table('establecimientos')
      ->select('establecimientos.*')
      ->where('id', $model_emergencia->id_establecimiento)
      ->first();

    if (intval($estable_nivel->nivel) === 3) {
      if (intval($model_emergencia->id_triaje) === 1 || intval($model_emergencia->id_triaje) === 12) {
        if ($resulta_hora < 1) {
          $codigo_cpms = "99234";
        } else {
          $codigo_cpms = "99295";
        }
      } else if (intval($model_emergencia->id_triaje) === 20) {
        $codigo_cpms = "";
      } else {
        if ($resulta_hora < 1) {
          $codigo_cpms = "99234";
        } else {
          $codigo_cpms = "99231.15";
        }
      }
    } else {
      if (intval($model_emergencia->id_prioridad) === 1) {
        $codigo_cpms = "99285";
      } else if (intval($model_emergencia->id_prioridad) === 2) {
        $codigo_cpms = "99284";
      } else if (intval($model_emergencia->id_prioridad) === 3) {
        $codigo_cpms = "99282";
      } else {
        $codigo_cpms = "99281";
      }
    }

    $emergencia_model = new \App\Models\Emergencia;

    /**********Calcula estancia*************/
    $inicio = ($model_emergencia->fecha_registro_ingreso);
    $final = ($date);
    $inicios = strtotime($model_emergencia->fecha_registro_ingreso);
    $fecha_alta = explode(" ", $date);
    $hora_alta = explode(" ", $horaActual);
    $fecha_finals = $fecha_alta[0] . ' ' . $hora_alta[0];
    $finals = strtotime($fecha_finals);
    $resulta = ($finals - $inicios) / 86400;
    $resulta = round($resulta);

    if ($resulta == "0") {
      $dia_estancia = '1';
    } else {
      $dia_estancia = $resulta;
    }

    $update_alta = $emergencia_model->InsertaEstancia($request->id_emergencia, $emergencia_prestacion->id, $id_evaluacion, 2, $codigo_cpms, $model_emergencia->fecha_atencion, $id_user, $dia_estancia);

    $model_emergencia->origen_alta = 1;
    $model_emergencia->cpms_alta = $codigo_cpms;
    $model_emergencia->fecha_alta_medica = $fechaAltaReal; 
    $model_emergencia->hora_alta_medica = $horaAltaReal; 
    $model_emergencia->id_tipo_egres_med = $request->id_tipo_egreso;
    $model_emergencia->flag_egreso = '1';
    $model_emergencia->estado = '5';
    $model_emergencia->id_medico_egreso = $id_user;
    $p_diagnostico = "";
    $id_1 = 0;
    $id_2 = 0;
    $id_3 = 0;

    if (isset($request->id_diagnostico)):
      foreach ($request->id_diagnostico as $key => $value):
        if ($key == 0 && $request->id_diagnostico[$key] != "") {
          $id_1 = $request->id_diagnostico[$key];
        }
        if ($key == 1 && $request->id_diagnostico[$key] != "") {
          $id_2 = $request->id_diagnostico[$key];
        }
        if ($key == 2 && $request->id_diagnostico[$key] != "") {
          $id_3 = $request->id_diagnostico[$key];
        }
      endforeach;
    endif;

    $model_emergencia->id_diag_cab = $id_1;
    $model_emergencia->id_diag_cuer1 = $id_2;
    $model_emergencia->id_diag_cuer2 = $id_3;
    if ($request->id_tipo_egreso == '1') { //alta
      $model_emergencia->id_condi_egres_med = $request->id_condicion_alta;
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->id_establecimiento_traslado = "0";
      $model_emergencia->necropsia = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    } else if ($request->id_tipo_egreso == '2') { //hospitalizado
      $upss = "SELECT
              concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
              u.id as id_upss,
              u.codigo as codigo_upss,
              c.id as id_consultorio,
              c.nombre as nombre_consultorio
              from consultorios c
              inner join upsses u on u.id_consultorio = c.id
              where u.id_tipo_atencion = '3' and u.estado = '1' and u.id='$request->id_servicio_hospitalizacion'";
      $upss = DB::select($upss);
      $upss = $upss[0];

      $model_emergencia->id_condi_egres_med = "3";
      $model_emergencia->id_servicio_destino = $upss->id_consultorio; //servicio encargado
      $model_emergencia->id_upss_alta = $upss->id_upss; //upss encargado
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->necropsia = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";

      if (intval($request->id_establecimiento) === 76):
        $model_emergencia->id_edificio_ubicacion = $request->id_edificio_ubicacion;
        $model_emergencia->id_pabellon_ubicacion = $request->id_pabellon_ubicacion;
      else:
        $model_emergencia->id_edificio_ubicacion = 0;
        $model_emergencia->id_pabellon_ubicacion = 0;
      endif;

    } else if ($request->id_tipo_egreso == '3') { //traslado
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta = "0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->id_condi_egres_med = "3";
      $model_emergencia->obs_traslado = $request->obs_traslado;
      $model_emergencia->id_establecimiento_traslado = $request->id_establecimiento_traslado;
      $model_emergencia->necropsia = "0";
      $model_emergencia->condicion_traslado = $request->condicion_traslado;
      $model_emergencia->personal_acompañante = $request->personal_acompañante;
      $model_emergencia->especialida_destino = $request->especialida_destino;
      $model_emergencia->especialidad_destino_otro = $request->especialidad_destino_otro;
      $model_emergencia->causas_muerte = "0";
    } else if ($request->id_tipo_egreso == '4') { //fallecido
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->id_condi_egres_med = "6";
      $model_emergencia->necropsia = $request->necropsia;
      $model_emergencia->causas_muerte = $request->causas_muerte;
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    } else if ($request->id_tipo_egreso == '7') { //retiro voluntario
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->id_condi_egres_med = "4";
      $model_emergencia->necropsia = "0";
      $model_emergencia->detalle_alta_admin = $request->obs_retiro;
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    } else if ($request->id_tipo_egreso == '6') { //fuga
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->id_condi_egres_med = "5";
      $model_emergencia->necropsia = "0";
      $model_emergencia->detalle_alta_admin = $request->obs_fuga;
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    } else {
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->necropsia = "0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->id_condi_egres_med = "0";
      $model_emergencia->id_condi_egres_med = "1";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    }

    if ($model_emergencia->id_tipo_beneficiario == '12') {
      $model_emergencia->numero_boleta = $request->numeroboleta;
      $model_emergencia->numero_documento_coronel_guardia  = $request->numeroguar;
    } else {
      $model_emergencia->numero_boleta = "0";
      $model_emergencia->numero_documento_coronel_guardia  = "0";
    }

    if (isset($model_emergencia->cama_estado)) {
      $model_cama = Cama::where('id', '=', $model_emergencia->cama_estado)->first();
      $model_cama->estado = 0; // 1 - No disponible (Estos datos son los que van a ir a produccion)
      $model_cama->situacion = '1'; // '5' - En Desinfeccion (Estos datos son los que van a ir a produccion)
      $model_cama->save();
    }

    $model_emergencia->save();

    //PARA NO SE PUEDA GENERAR DUPLICACACION
    if (!$existe_intervencion_cqx) { //PARA NO SE PUEDA GENERAR DUPLICACACION
      if ($request->id_tipo_egreso == '2') {
          $flag_quirurgico = 0;
        if (isset($request->requiere_intervencion_quirurgica) && intval($request->requiere_intervencion_quirurgica) == 1) {
          $flag_quirurgico = $request->requiere_intervencion_quirurgica;

          $model_hospitalizacion_orden = new \App\Models\HospitalizacionOrden();
          $response = $model_hospitalizacion_orden->RegistrarOrdenDeHospitalizacionDesdeEmergencia($request->id_emergencia, $flag_quirurgico);

          $hora_inicio_intervencion = !empty($request->hora_inicio_intervencion) ? $request->hora_inicio_intervencion : null;
          $hora_fin_intervencion = !empty($request->hora_fin_intervencion) ? $request->hora_fin_intervencion : null;

          // En caso de Id Upps sea de Centro Quirugico Registrar Intervencion Quirurgica
          // switch ($request->id_servicio_hospitalizacion) {
          //   case 213: case 214: case 216: case 215:
          //   case 217: case 218: case 219:
          $duracion_cirugia = $request->duracion_cirugia ? floatval($request->duracion_cirugia) : null;
          $modelintervencion = new \App\Models\CqxIntervencion;
          $cqx_id_intervencion = $modelintervencion->RegistrarIntervencionQxDesdeEmergencia($request->id_emergencia, Auth::user()->id, $request->id_tipo_cirugia, $duracion_cirugia, $fecha_cierre, $hora_inicio_intervencion, $hora_fin_intervencion);
              //break;
          //}

          //Registro de diagnosticos cqx
          if (isset($request->id_diagnostico_quirurgico)):
            $dignostico_cqx = new Receta_diagnostico();
            foreach ($request->id_diagnostico_quirurgico as $key => $value):
              if ($request->id_diagnostico_quirurgico[$key] != "") {
                $dignostico_cqx->id_diagnostico = $request->id_diagnostico_quirurgico[$key];
                $dignostico_cqx->id_tipo_diagnostico = $request->id_tipo_diagnostico_quirurgico[$key];
                $dignostico_cqx->estado = 1;
                $dignostico_cqx->id_prestacion = $request->id_prestacionx;
                $dignostico_cqx->created_at = Carbon::now();
                $dignostico_cqx->created_us = $request->id_user;
                $dignostico_cqx->id_intervencion = $cqx_id_intervencion;
                $dignostico_cqx->save();
              }
            endforeach;
          endif;

          // Registro de Medicos Cirujanos
          if (isset($request->id_medico_cirujano)):
            $model_cq_profesional = new \App\Models\CqxProfesional();
            foreach ($request->id_medico_cirujano as $key => $value):
              if ($request->id_medico_cirujano[$key] != "") {
                $p_id_medico = $request->id_medico_cirujano[$key];
                $p_id_medico_cargo = (isset($request->id_medico_cirujano_cargo[$key]) && $request->id_medico_cirujano_cargo[$key] > 0 ? $request->id_medico_cirujano_cargo[$key] : 0);
                $flag_personal = 0; // Cirujanos
                $response = $model_cq_profesional->RegistrarProfesionalesPreQx($cqx_id_intervencion, $p_id_medico, $p_id_medico_cargo, $request->id_user, $flag_personal, 0);
              }
            endforeach;
          endif;

          // Registro de Procedimientos de Intervencion quirurgica
          if (isset($request->id_procedimiento_intervencion)) :
            $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
            foreach ($request->id_procedimiento_intervencion as $key => $value) :
              if ($request->id_procedimiento_intervencion[$key] != "") {
                $p_id_procedimiento = $request->id_procedimiento_intervencion[$key];
                $model_cq_procedimiento->RegistrarProcedimientoPreQx($cqx_id_intervencion, $p_id_procedimiento, Auth::user()->id, 0, 1, false);
              }
            endforeach;
          endif;
        } else {
          $model_hospitalizacion_orden = new \App\Models\HospitalizacionOrden();
          $response = $model_hospitalizacion_orden->RegistrarOrdenDeHospitalizacionDesdeEmergencia($request->id_emergencia, $flag_quirurgico);
        }
      }
    }
    if (intval($request->pendiente_alta_emergencia_interconsulta_qx) == 1) {
      $id_intervencion = $request->id_intervencion;
      $intervencion = \App\Models\CqxIntervencion::find($id_intervencion);
      $intervencion->pendiente_alta_emergencia = false;
      $intervencion->save();

      $emergencia = DB::table('emergencias as e')
      ->join('users as u', 'e.id_medico_egreso', '=', 'u.id')
      ->join('medicos as m', function($join) {
          $join->on('u.dni', '=', 'm.dni')
              ->on('u.id_establecimiento', '=', 'm.id_establecimiento');
      })
      ->where('e.id', '=', $request->id_emergencia)
      ->select('e.id', 'e.id_establecimiento', 'e.id_asegurado', DB::raw("CAST((CAST(e.fecha_alta_medica AS date) || ' ' || e.hora_alta_medica) AS timestamp) as fecha_alta_medica"), 'm.id as id_medico', 'e.id_servicio_destino', 'e.id_medico_egreso as id_usuario_egreso', 'e.id_upss_alta','e.id_servicio_destino_ho','e.id_pabellon_destino','e.id_cuarto_destino')
      ->first();
      
      $orden = \App\Models\HospitalizacionOrden::where('id_ext', $emergencia->id)->first();
      if ($orden) {
          $orden->id_sala = $emergencia->id_servicio_destino ?: null;
          $orden->id_upss_responsable = $emergencia->id_upss_alta ?: null;
          $orden->id_upss_traslado = $emergencia->id_upss_alta ?: null;
          $orden->save();
      }
    }

    $celular = Auth::user()->celular;
    if ($celular == '1') {
      $titular = Asegurado::where('id', '=', $model_emergencia->id_asegurado)->first();
      $titular->nro_doc_ident = $dni_prueba_jhon;
      $titular->save();
    }

    $model_trama = new \App\Models\Emergencia;
    $model_evaluacion = new \App\Models\Evaluacione;
    $enviado_dato = $model_trama->enviartrama($request->id_emergencia);

    Session::flash('flash_message', 'Alta Medica exitosa');
    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $request->id_emergencia)->first();

    if (is_object($emergencia_prestacion)):
      $id_establecimiento = Auth::user()->id_establecimiento;
      $model_emergencia = new Emergencia();
      $prestacion = $model_emergencia->getIdPrestacion($request->id_emergencia);
      $dato_traslado = $model_emergencia->GetVerUbicacionTraslado2($request->id_emergencia);
      $id_prestacion =  $prestacion->id;
      $emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
      $evaluacion = $model_evaluacion->GetEvaluacionesByIdExt($request->id_emergencia);

      $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($request->id_emergencia, $emergencia->estado);
      $egreso = $model_emergencia->GetEgresoEmergencia($request->id_emergencia);
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_kardex = new \App\Models\Kardexe();
      $fechaServidor = $model_kardex->getFechaServidor();
      $horaservidor = $model_kardex->getHoraServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion2($id_prestacion);
      $diagnosticos_egreso = $model_prestacion->GetDiagnosticoByIdAltaPrestacion($id_prestacion);
      $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;

      if ($prestacion->id_ext != 0):
        if ($prestacion->tipo_atencion == 'EMERGENCIA'):
          $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
          if ($dato_emergencia->id_prioridad < 4):
            $model_emergencia = new \App\Models\Emergencia;
            $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
            $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
            $sw = 1;
            $prestacion->servicio = $obtenerdato->servicio;
            $prestacion->consultorio = $obtenerdato->consultorio;
          else:
            $prestacion->servicio = "IV - PATOLOGÍA AGUDA COMÚN";
            $prestacion->consultorio = "CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
            $sw = 0;
          endif;
        endif;
      else:
        return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;

      $gestacion = null;
      $puerperio = null;
      $natis = null;
      if (isset($emergencia->id_gestacion)) {
        $gestacion = Gestacion::find($emergencia->id_gestacion);
        error_log('HAY GESTACION EN ALTA: ' . (string)$gestacion);
      }
      if (isset($emergencia->id_puerperio)) {
        $puerperio = Puerperio::find($emergencia->id_puerperio);
        error_log('HAY PUERPERIO EN ALTA: ' . (string)$puerperio);
        $natis = $puerperio->natis;
        error_log('sus natis: ' . (string)$natis);
      }

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
      $establecimiento = $model_establecimiento->GetById($id_establecimiento);
      $is_habilitado_firma_electronica = $establecimiento[0]->flag_firma_electronica;
      if ($id_establecimiento == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }
      if ($emergencia->id_tipo_egres_med == 3) {
        $estable = "select * from establecimientos e where id = '$emergencia->id_establecimiento_traslado'";
        $estable =  DB::select($estable);
        $estable = $estable[0];
      }

      $model_consultorio = new \App\Models\Consultorio;
      $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento, 1);

      /** ACTUALIZAR EL CODIGO SITEDS */
      if (isset($request->codigo_siteds)) {
        DB::beginTransaction();
        $resulUpdateSitedsEmer = Emergencia::where('id', $request->id_emergencia)->update([
          'cod_siteds_susalud' => $request->codigo_siteds,
          'updated_at_cod_siteds' => Carbon::now(),
          'updated_us_cod_siteds' => Auth::user()->id
        ]);
        $resulUpdateSitedsCita = Cita::where('id', $prestacion->id_cita)->update([
          'codigo_siteds' => $request->codigo_siteds,
          'updated_at_cod_siteds' => Carbon::now(),
          'updated_us_cod_siteds' => Auth::user()->id
        ]);
        DB::commit();
      }
      /** ACTUALIZAR EL CODIGO SITEDS  END */

      $dni = Auth::user()->dni;
      $model_medico = new Medico();
      $exite_medico = $model_medico->exite_medico($dni);
      $model_usuario = new User();
      $usuario = $model_usuario->GetById($emergencia->id_medico_egreso);
      $exite_medico_ipress = $model_medico->medico_yaregistrado($dni, $id_establecimiento);
      $medico = $model_medico->GetDatosMedicoAndEstablecimiento($usuario->dni, $usuario->id_establecimiento);

      /**********HASTA AQUI YA EISTE EL REGISTRO DE INTERVENCION*******/
      $model_intervencion = new \App\Models\CqxIntervencion;
      $existe_intervencion = $model_intervencion::where('id_prestacion', $id_prestacion)->exists();
      $datos_intervencion = "";
      $procedimientos_cqx = "";
      $profesionales_cqx = "";
      if ($existe_intervencion) {
        $datos_intervencion = $model_intervencion::select('cqx_intervenciones.id', 'tc.descripcion as tipo_cirugia', 'cqx_intervenciones.emergencia_duracion_cirugia')
          ->leftJoin('cqx_tipo_cirugias as tc', 'cqx_intervenciones.emergencia_tipo_cirugia', '=', 'tc.id')
          ->where('cqx_intervenciones.id_prestacion', $id_prestacion)->first();
        //DIAGNOSTICOS QUIRURGICOS
        $diagnosticos_cqx = DB::table('receta_diagnosticos as rd')
          ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
          ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
          ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
          ->where('rd.id_intervencion', '=', $datos_intervencion->id)
          ->where('rd.id_prestacion', '=', $id_prestacion)
          ->get();

        //PROCEDIMIENTOS QUIRURGICOS
        $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
        $procedimientos_cqx = $model_cq_procedimiento->GetByIdIntervencionQuirugica($datos_intervencion->id);
        //PROFESIONALES QUIRURGICOS
        $model_cq_profesional = new \App\Models\CqxProfesional();
        $profesionales_cqx = $model_cq_profesional->GetByIdIntervencionQuirugicaYTipoPersonal($datos_intervencion->id, 0);
      }

      $pabellon = DB::table('pabellones')
                ->where('pabellones.id', $emergencia->id_pabellon_ubicacion)
                ->first();

      $upss_alta = DB::table('upsses')
                  ->where('upsses.id', $emergencia->id_upss_alta)
                  ->first();

      $receta_alta = $model_receta->GetDatosRecetasAltasByIdPrestacion($id_prestacion);
    
      $model_farmacia = new \App\Models\Farmacia();
      $id_farmacia = Auth::user()->id_farmacia;
      $id_principal=$dato_emergencia->id;

      $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);
      $id_ipress=$farmacia->id_establecimiento;

      $model_producto = new Producto;
      $model_establecimiento = new \App\Models\Establecimiento;
      $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
              
      $id_ipress = $farmacia->id_establecimiento;
      if($id_ipress==76): //lns
        $id_farmacia = 19; //farmacia de emergencia
      else:
        $id_farmacia = $farmacia->id;
      endif;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      if ($id_ipress == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }
      
      $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia);
      $farmacias = $model_farmacia->GetFarmaciaById($id_farmacia);
      
     if ($exite_medico == 0) {
        Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
        return redirect('/all_atenciones_by_emergencia');
      } else if ($exite_medico_ipress == "") {
        $model_establecimiento = new Establecimiento();
        $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
        Session::flash('flash_message', 'Usted no es Médico en el establecimiento: ' . $establecimiento[0]->nombre);
        return redirect('/all_atenciones_by_emergencia');
      } else {
        return view('frontend.medicos.alta_medica_ver1', compact('diagnosticos_egreso','id_farmacia','id_principal','producto','farmacias','receta_alta','triajes', 'prioridad', 'prestacion', 'estable', 'upss', 'diagnosti', 'diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'paciente_emergencia', 'emergencia', 'medico', 'datoadicional', 'sw', 'gestacion', 'puerperio', 'natis', 'evaluacion', 'existe_intervencion', 'datos_intervencion', 'diagnosticos_cqx', 'procedimientos_cqx', 'profesionales_cqx', 'dato_traslado', 'pabellon', 'upss_alta', 'is_habilitado_firma_electronica'));
      }
    else:
      Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Medica');
      return redirect('/all_atenciones_by_emergencia');
    endif;
  }

  public function registrar_alta_administrativa(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');

    $model_kardex = new \App\Models\Kardexe();

    $fechaEgresoAdmin = $model_kardex->getFechaServidor();
    $horaEgresoAdmin = $model_kardex->getHoraServidor();

    $model_emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
    $model_emergencia->estado= "6";
    $model_emergencia->secuencia= 6;
    $model_emergencia->fecha_egreso_administrativa = $date;
    $model_emergencia->hora_egreso_administrativa = $horaActual;
    $model_emergencia->id_tipo_egreso = $request->id_tipo_egreso;
    $model_emergencia->detalle_alta_admin = $request->detalle_alta_adm;
    $model_emergencia->id_condicion_alta = $request->id_condicion_alta;
    $model_emergencia->id_user_egreso_administrativo = Auth::user()->id;

    $model_emergencia->save();
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_emergencia = new Emergencia();

    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $request->id_emergencia)->first();

      $prestacion= $model_emergencia->getIdPrestacion($request->id_emergencia);
      $id_prestacion =  $prestacion->id;
      $id_user =  Auth::user()->id;
      $emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();

      $modelfarma = new \App\Models\Farmacia;
      $model_receta = new \App\Models\Receta_vale;
      $model_producto = new \App\Models\Producto;
      $model_productos_receta = new \App\Models\Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_kardex = new \App\Models\Kardexe();

      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $id_farmacia = $farmacia->id;
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

      if($prestacion->id_ext!=0):
        if($prestacion->tipo_atencion=='EMERGENCIA'):
          $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
          if($dato_emergencia->id_prioridad<4):
            $model_emergencia = new \App\Models\Emergencia;
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
      else:
           return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;

      $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
      $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

      $id_ipress = $farmacia->id_establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      if ($id_ipress == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }

       $model_upss = new \App\Models\Upss;
       $p[] =  '1';
       $consulta_externa = $model_upss->ShowConsultoriosTipoAtencion($p);

       $h[] =  '3';
       $hospitalizacion = $model_upss->ShowConsultoriosTipoAtencion($h);

      if($emergencia->fecha_egreso!=''){
        $fechaEgreso = $emergencia->fecha_egreso;
        $horaEgreso = $emergencia->hora_egreso;
        $dato = 1; //alta medica
      }
      else
      {
        $fechaEgreso = $model_kardex->getFechaServidor();
        $horaEgreso = $model_kardex->getHoraServidor();
        $dato = 2;
      }


        $fechaEgresoAdmin = $model_kardex->getFechaServidor();
        $horaEgresoAdmin = $model_kardex->getHoraServidor();

      $inicio= ($emergencia->fecha_registro_ingreso);
      $final= ($emergencia->fecha_alta_medica);
      $inicios= strtotime($emergencia->fecha_registro_ingreso);
      $finals= strtotime($emergencia->fecha_alta_medica);

        $resulta=($finals-$inicios)/86400;

        if($resulta == "0"){
          $resta = '1';
        }else{
          $resta=$resulta;
        }


     Session::flash('flash_message', 'Alta Administrativa exitosa');
    return view('frontend.medicos.alta_administrativa_firmar', compact('prestacion','fechaEgresoAdmin','horaEgresoAdmin','upss','estable', 'diagnosti','diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma', 'consulta_externa', 'paciente_emergencia', 'emergencia' ));
  }

  public function registrar_alta_adminisa(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');
    $model_kardex = new \App\Models\Kardexe();

      $fechaEgresoAdmin = $model_kardex->getFechaServidor();
      $horaEgresoAdmin = $model_kardex->getHoraServidor();

    $model_emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
    $model_emergencia->estado= "6";
    $model_emergencia->secuencia= 6;
    $model_emergencia->fecha_egreso_administrativa = $fechaActual;
    $model_emergencia->hora_egreso_administrativa = $horaActual;
    $model_emergencia->id_user_egreso_administrativo = Auth::user()->id;

    $model_emergencia->save();

     Session::flash('flash_message', 'Alta Administrativa exitosa');

     $prestacion= $model_emergencia->getIdPrestacion($request->id_emergencia);
      $id_prestacion =  $prestacion->id;


      $emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
      $id_user =  Auth::user()->id;
      $modelfarma = new \App\Models\Farmacia;
      $model_receta = new \App\Models\Receta_vale;
      $model_producto = new \App\Models\Producto;
      $model_productos_receta = new \App\Models\Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_kardex = new \App\Models\Kardexe();

      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $id_farmacia = $farmacia->id;
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

      if($prestacion->id_ext!=0):
        if($prestacion->tipo_atencion=='EMERGENCIA'):
          $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
          if($dato_emergencia->id_prioridad<4):
            $model_emergencia = new \App\Models\Emergencia;
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
      else:
           return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;

      $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
      $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

      $id_ipress = $farmacia->id_establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      if ($id_ipress == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }

       $model_upss = new \App\Models\Upss;
       $p[] =  '1';
       $consulta_externa = $model_upss->ShowConsultoriosTipoAtencion($p);

       $h[] =  '3';
       $hospitalizacion = $model_upss->ShowConsultoriosTipoAtencion($h);

      if($emergencia->fecha_egreso!=''){
        $fechaEgreso = $emergencia->fecha_egreso;
        $horaEgreso = $emergencia->hora_egreso;
        $dato = 1; //alta medica
      }
      else
      {
        $fechaEgreso = $model_kardex->getFechaServidor();
        $horaEgreso = $model_kardex->getHoraServidor();
        $dato = 2;
      }

      if($emergencia->fecha_egreso_administrativa!=''){
        $fechaEgresoAdmin = $emergencia->fecha_egreso_administrativa;
        $horaEgresoAdmin = $emergencia->hora_egreso_administrativa;
      }
      else
      {
        $fechaEgresoAdmin = $model_kardex->getFechaServidor();
        $horaEgresoAdmin = $model_kardex->getHoraServidor();
      }

      $inicio= ($emergencia->fecha_registro_ingreso);
      $final= ($emergencia->fecha_alta_medica);
      $inicios= strtotime($emergencia->fecha_registro_ingreso);
      $finals= strtotime($emergencia->fecha_alta_medica);

        $resulta=($finals-$inicios)/86400;

        if($resulta == "0"){
          $resta = '1';
        }else{
          $resta=$resulta;
        }
     return view('frontend.medicos.alta_adminisa_firmar', compact('farmacia','resta','prestacion','diagnostico', 'id_prestacion', 'fechaServidor2', 'horaservidor2', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'emergencia', 'fechaEgreso','horaEgreso', 'dato', 'fechaEgresoAdmin','horaEgresoAdmin' ));

  }



  public function alta_administrativa_ver($id_user, $id_emergencia)
    {

      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      //DATOS EMERGENCIA
      $model_emergencia = new Emergencia();

      $emergencia_prestacion = Prestaciones::where('id_ext', '=', $id_emergencia)->first();

        $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
        $id_prestacion =  $prestacion->id;

        $emergencia = Emergencia::where('id', '=', $id_emergencia)->first();

        $modelfarma = new \App\Models\Farmacia;
        $model_receta = new \App\Models\Receta_vale;
        $model_producto = new \App\Models\Producto;
        $model_productos_receta = new \App\Models\Producto_receta;
        $model_establecimiento = new \App\Models\Establecimiento;
        $model_prestacion = new \App\Models\Prestaciones;
        $model_laboratorio = new \App\Models\Laboratorio;
        $model_radiologia = new \App\Models\Radiologia;
        $model_kardex = new \App\Models\Kardexe();

        $farmacia = $modelfarma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $flag_receta_dias = $farmacia->flag_receta_dias;

        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

        if($prestacion->id_ext!=0):
          if($prestacion->tipo_atencion=='EMERGENCIA'):
            $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
            if($dato_emergencia->id_prioridad<4):
              $model_emergencia = new \App\Models\Emergencia;
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
        else:
             return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
        endif;

        $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
        $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

        $id_ipress = $farmacia->id_establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        if ($id_ipress == 1) {
          $nivel = 2;
        } else {
          $nivel = $establecimiento[0]->nivel;
        }

         $model_upss = new \App\Models\Upss;
         $p[] =  '1';
         $consulta_externa = $model_upss->ShowConsultoriosTipoAtencion($p);

         $h[] =  '3';
         $hospitalizacion = $model_upss->ShowConsultoriosTipoAtencion($h);

        if($emergencia->fecha_egreso!=''){
          $fechaEgreso = $emergencia->fecha_egreso;
          $horaEgreso = $emergencia->hora_egreso;
          $dato = 1; //alta medica
        }
        else
        {
          $fechaEgreso = $model_kardex->getFechaServidor();
          $horaEgreso = $model_kardex->getHoraServidor();
          $dato = 2;
        }

        if($emergencia->fecha_egreso_administrativa!=''){
          $fechaEgresoAdmin = $emergencia->fecha_egreso_administrativa;
          $horaEgresoAdmin = $emergencia->hora_egreso_administrativa;
        }
        else
        {
          $fechaEgresoAdmin = $model_kardex->getFechaServidor();
          $horaEgresoAdmin = $model_kardex->getHoraServidor();
        }

        $inicio= ($emergencia->fecha_registro_ingreso);
        $final= ($emergencia->fecha_alta_medica);
        $inicios= strtotime($emergencia->fecha_registro_ingreso);
        $finals= strtotime($emergencia->fecha_alta_medica);

          $resulta=($finals-$inicios)/86400;

          if($resulta == "0"){
            $resta = '1';
          }else{
            $resta=$resulta;
          }

      if($emergencia->flag_egreso!=0){
        return view('frontend.medicos.alta_adminisa_ver', compact('farmacia','resta','prestacion','diagnostico', 'id_prestacion', 'fechaServidor2', 'horaservidor2', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'emergencia', 'fechaEgreso','horaEgreso', 'dato', 'fechaEgresoAdmin','horaEgresoAdmin' ));
      }else{
        return view('frontend.medicos.alta_administrativa_ver', compact('farmacia','resta','prestacion','diagnostico', 'id_prestacion', 'fechaServidor2', 'horaservidor2', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'emergencia', 'fechaEgreso','horaEgreso', 'dato', 'fechaEgresoAdmin','horaEgresoAdmin' ));

      }
      }

    public function alta_medica_hospitalizado($id_user, $id_hospitalizacion)
    {

        if (!Auth::check()) :
        return redirect('/auth/login');
        endif;

        //DATOS EMERGENCIA
        $model_emergencia = new Emergencia();


        // var emergencia luego cambiar por hospitalizado
        $emergencia = Hospitalizacione::where('id', '=', $id_hospitalizacion)->first();
        if(is_object($emergencia)):
            $busca_atencion = Prestaciones::where('id_asegurado', '=', $emergencia->id_asegurado)->first();

            if(!is_object($busca_atencion)):

                $modelfarma = new \App\Models\Farmacia;
                $model_receta = new \App\Models\Receta_vale;
                $model_producto = new \App\Models\Producto;
                $model_productos_receta = new \App\Models\Producto_receta;
                $model_establecimiento = new \App\Models\Establecimiento;
                $model_prestacion = new \App\Models\Prestaciones;
                $model_laboratorio = new \App\Models\Laboratorio;
                $model_radiologia = new \App\Models\Radiologia;
                $model_kardex = new \App\Models\Kardexe();

                $farmacia = $modelfarma->GetFarmaByUser($id_user);
                $id_farmacia = $farmacia->id;
                $flag_receta_dias = $farmacia->flag_receta_dias;

                $fechaServidor = $model_kardex->getFechaServidor();
                $horaservidor = $model_kardex->getHoraServidor();

                $date = Carbon::now();
                $fecha_actual = $date->format('d-m-Y');
                $prestacion= $model_emergencia->getIdPrestacion($id_hospitalizacion);
                $id_prestacion =  $prestacion->id;
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

                if($prestacion->id_ext!=0):
                  if($prestacion->tipo_atencion=='EMERGENCIA'):
                    $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
                    if($dato_emergencia->id_prioridad<4):
                      $model_emergencia = new \App\Models\Emergencia;
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
                else:
                     return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
                endif;
            $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
                $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

                $id_ipress = $farmacia->id_establecimiento;
                $establecimiento = $model_establecimiento->GetById($id_ipress);
                if ($id_ipress == 1) {
                $nivel = 2;
                } else {
                $nivel = $establecimiento[0]->nivel;
                }

                $model_upss = new \App\Models\Upss;
                $p[] =  '1';
                $consulta_externa = $model_upss->ShowConsultoriosTipoAtencion($p);

                $h[] =  '3';
                $hospitalizacion = $model_upss->ShowConsultoriosTipoAtencion($h);
                //dd($hospitalizacion);

                //VALIDAR MEDICO
                $id_establecimiento=Auth::user()->id_establecimiento;//76
                $dni=Auth::user()->dni;

                $model_medico = new Medico();
                $exite_medico=$model_medico->exite_medico($dni);
                $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);

                $establecimientos = $model_establecimiento->ShowAllEstablecimientos();

                if ($exite_medico == 0) {
                    Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
                    return redirect('/all_hospitalizado_by_establecimiento');

                } else if ($exite_medico_ipress == "") {
                    $model_establecimiento = new Establecimiento();
                    $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
                    Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
                    return redirect('/all_hospitalizado_by_establecimiento');

                } else {
                    return view('frontend.medicos.alta_medica_hospitalizado', compact('farmacia', 'prestacion', 'diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'emergencia', 'establecimientos'));
                }

            else:
                Session::flash('flash_message', 'No tiene ninguna atención, registrar atención');
                return redirect('/hospitalizado_by_atender');
            endif;

        else:

                Session::flash('flash_message', 'No se encuentra hospitalizado, registrar paciente');
                return redirect('/all_pendientes_hospitalizacion');
        endif;


    }


    public function registrar_alta_medica_hospitalizado(Request $request){
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $model_hospitalizacione = Hospitalizacione::where('id', '=', $request->id_hospitalizado)->first();
        $model_hospitalizacione->estado= "7";
	$model_hospitalizacione->secuencia= 7;
        $model_hospitalizacione->fecha_egreso = $request->fecha_egreso;
        $model_hospitalizacione->hora_egreso = $request->hora_egreso;
        $model_hospitalizacione->fecha_hora_registro_egreso = date('Y-m-d');
        // $model_hospitalizacione->hora_registro_egreso = date('H:i:s');
        $model_hospitalizacione->id_medico_alta= Auth::user()->id;
        $model_hospitalizacione->id_tipo_egreso= $request->id_condicion_alta;

        if ($request->id_condicion_alta=="1"){ //MEJORADO

            $model_hospitalizacione->derivar= $request->derivar;
            if ($request->derivar == "SI")
                $model_hospitalizacione->id_servicio_deriva = $request->id_servicio_consulta_externa;
            $model_hospitalizacione->necropsia="";
            $model_hospitalizacione->causas_muerte="";
            $model_hospitalizacione->referencia="";

        } else if ($request->id_condicion_alta=="2"){ //CURADO

            $model_hospitalizacione->derivar= $request->derivar;
            if ($request->derivar == "SI")
                $model_hospitalizacione->id_servicio_deriva = $request->id_servicio_consulta_externa;
            $model_hospitalizacione->necropsia ="";
            $model_hospitalizacione->causas_muerte ="";
            $model_hospitalizacione->referencia ="";

        } else if ($request->id_condicion_alta=="3"){ //TRANSFERIDO

            // $model_hospitalizacione->id_servicio_deriva = "";
            $model_hospitalizacione->derivar = "";
            $model_hospitalizacione->necropsia = "";
            $model_hospitalizacione->causas_muerte = "";
            $model_hospitalizacione->referencia = $request->id_referencia;

            if ($request->id_referencia == 'SI') {
                $model_referencia = new \App\Models\Referencia;
                if (isset($request->id_establecimiento_referencia)) :
                    if($request->id_establecimiento_referencia!=""):
                        if($request->id_uci==2){
                        $id_uci_cv=0;
                        }
                        else{
                        $id_uci_cv=$request->id_uci_cv;
                        }
                        if($request->id_ambulancia==2){
                        $id_ambulancia_ref=0;$id_tipo_ambulancia=0;
                        }
                        else{
                        $id_ambulancia_ref=$request->id_ambulancia_ref;$id_tipo_ambulancia=$request->id_tipo_ambulancia;
                        }

                        $id_referencia = $model_referencia->RegistrarReferencia($request->id_prestacion, $request->id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento_referencia, $request->id_uci,$id_uci_cv,$request->id_ambulancia,$id_ambulancia_ref,$id_tipo_ambulancia,$request->id_pasaje,$request->id_acompanante,$request->id_situacion,$request->observacion_referencia,$request->id_establecimiento);

                        // if($id_emergencia!=0):
                        // $model_emergencia = new \App\Models\Emergencia;
                        // $update=$model_emergencia->ActualizaEstado($id_emergencia,4);
                        // endif;

                    endif;
                endif;
            }

        } else if ($request->id_condicion_alta=="4"){ //DEFUNCIÓN
            $model_hospitalizacione->necropsia = $request->necropsia;
            $model_hospitalizacione->causas_muerte = $request->causas_muerte;
            // $model_hospitalizacione->id_servicio_deriva=0;
            $model_hospitalizacione->derivar="";
            $model_hospitalizacione->referencia="";

        }
        // $model_hospitalizacione->id_condicion_alta= $request->id_condicion_alta;
        $model_hospitalizacione->save();

        //diagnostico
        $id_prestacion =  $request->id_prestacionx;
        $id_user =  Auth::user()->id;

        if (isset($request->id_diagnostico)) :
            foreach ($request->id_diagnostico as $key => $value) :
                if ($request->id_diagnostico[$key] != "") {
                    $model_receta_diagnostico = new \App\Models\Receta_diagnostico;
                    //$id_receta_vale
                    $model_receta_diagnostico->id_diagnostico = $request->id_diagnostico[$key];
                    $model_receta_diagnostico->id_tipo_diagnostico = $request->id_tipo_diagnostico[$key];
                    $model_receta_diagnostico->estado  = "1";
                    $model_receta_diagnostico->id_prestacion = $id_prestacion;
                    //created_at    updated_at  updated_us  deleted_at  deleted_us
                    $model_receta_diagnostico->created_us = $id_user;
                    $model_receta_diagnostico->id_clasificacion_diagnosticos="3";
                    $model_receta_diagnostico->save();
                }
            endforeach;
        endif;

        if ($request->id_sub_consultorio != 976) {
            Session::flash('flash_message', 'Alta Medica exitosa');
            return redirect('/alta_medica_hospitalizado/' . $id_user . '/' . $request->id_hospitalizado);
        } else {
            Session::flash('flash_message', 'Alta Medica exitosa');
            return redirect('/alta_medica_hospitalizado/' . $id_user . '/' . $request->id_hospitalizado);
        }
    }

  public function listar_medicos_ajax_por_ipress_saenz(Request $request)
  {
    $model_personal = new Medico();
    $draw = $request->draw;
    //$start = ($request->start == 0) ? 1 : (int)$request->start / 5 + 1;
    $start = ($request->start == 0) ? 1 : intval($request->start / $request->length) + 1;
    
    $length = 10;
    $search_value = $request->search["value"];
    $id_establecimiento = $request->id_establecimiento;

    $dni_beneficiario = $request->input('dni_beneficiario', '');
    $nombre_beneficiario = $request->input('nombre_beneficiario', '');

    $p = [
      (string) $start,
      (string) $length,
      $search_value,
      $id_establecimiento,
      $dni_beneficiario,
      $nombre_beneficiario
    ];

    try {
        $data = $model_personal->listar_personal_ajax_por_ipress_v2($p);
        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

        return response()->json([
            "draw" => $draw,
            "recordsTotal" => $iTotalDisplayRecords,
            "recordsFiltered" => $iTotalDisplayRecords,
            "data" => $data
        ]);
    } catch (\Exception $e) {
        return response()->json([
            "draw" => $draw,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
            "error" => $e->getMessage()
        ]);
    }
  }

  public function listar_medicos_ajax_general(Request $request)
  {

    $model_personal = new Medico();
    $draw = $request->draw;
    $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
    $length = 10;
    $search_value = $request->search["value"];

    $p[] = (string)$start;
    $p[] = (string)$length;
    $p[] = $search_value;

    $data = $model_personal->listar_personal_ajax_general($p);

    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["draw"] = $draw;
    $result["recordsTotal"] = $iTotalDisplayRecords;
    $result["recordsFiltered"] = $iTotalDisplayRecords;
    $result["data"] = $data;

    echo json_encode($result);
  }

  public function listar_personal_ajax(Request $request)
  {

    $model_personal = new Medico();
    $draw = $request->draw;
    $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
    $length = 10;
    $search_value = $request->search["value"];
    $id_establecimiento = $request->id_establecimiento;

    $p[] = (string)$start;
    $p[] = (string)$length;
    $p[] = $search_value;
    $p[] = $id_establecimiento;

    $data = $model_personal->listar_personal_ajax($p);

    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["draw"] = $draw;
    $result["recordsTotal"] = $iTotalDisplayRecords;
    $result["recordsFiltered"] = $iTotalDisplayRecords;
    $result["data"] = $data;

    echo json_encode($result);
  }

  public function buscar_medico_ipress($dni)
  {

    $modelmedico = new Medico();
    $result = $modelmedico->GetMostrarMedicoByDNI($dni);
    echo json_encode($result);
  }

  public function obtener_subconsultorios_servicio_establecimiento_medico($id_establecimiento, $id_medico, $fecha_atencion, $id_consultorio)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $medico_consultorio = new Medico_consultorio();
    $sub_consultorio = $medico_consultorio->GetSubConsultorioByIdEstablecimientoAndIdMedico($id_establecimiento, $id_medico, $fecha_atencion, $id_consultorio);

    if(count($sub_consultorio)==0)
      $sub_consultorio = $medico_consultorio->GetSubConsultorioByIdEstablecimientoAndIdConsultorio($id_establecimiento, $id_consultorio, 1);

    return $sub_consultorio;
  }
  public function all_reporte_hoja_his() {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");


        $id_farmacia = Auth::user()->id_farmacia;
        $id_establecimiento = Auth::user()->id_establecimiento;
        $dni_medico = Auth::user()->dni;
        //------------------------------------------------------
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
        $model_consultorio = new Consultorio;

        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');

        $model_atencion = new TipoAtencione;
        if (Auth::user()->flagservicios == 1 || Auth::user()->identificativo==1):
          $atenciones = $model_atencion->ShowTipoAtencionAll();
        else:
          $atenciones = $model_farma->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        endif;

        $model_medico = new Medico();
        $medico = $model_medico->GetDatosMedicoAndEstablecimiento($dni_medico, $id_establecimiento);

        //$consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        if (count($medico)>0) {
          $medico_consultorio = new Medico_consultorio();
          $consultorio_medico = $medico_consultorio->GetConsultorioByIdEstablecimientoAndIdMedico($id_establecimiento, $medico[0]->id);
          $id_medico = $medico[0]->id;//310

          //------------------------------------------------------
          return view('frontend.medicos.reportes_hojas_his_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_actual','atenciones','consultorio_medico','id_medico'));
        }else{
          if(Auth::user()->identificativo==1){
            $medico_consultorio = new Medico_consultorio();
            $consultorio_medico = $medico_consultorio->GetConsultorioByIdEstablecimiento($id_establecimiento);
            //------------------------------------------------------
            return view('frontend.medicos.reportes_hojas_his_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_actual','atenciones','consultorio_medico'));
          }
          else{
          Session::flash('flash_message', 'Médico no encontrado');
          return redirect('/inicio');
          }
        }
    }

    public function ver_hoja_his(Request $request) {

        if($request->id_atencion!=0 && $request->id_medico!=0):

          if($request->id_atencion==2){
            $request->id_servicio = 76;
          }
          $model_consultorio = new Consultorio;
          $upss = $model_consultorio->ShowCodigoUpssIdTipoAtencion($request->id_servicio,$request->id_atencion);

          if(count($upss)>0):

            $id_servicio=$upss[0]->id;
            $codigo_upss=$upss[0]->codigo;
            $nombre_upss=$upss[0]->nombre;

            $dni_medico=Auth::user()->dni;
            $id_establecimiento=Auth::user()->id_establecimiento;

            $model_establecimiento = new Establecimiento();
            $establecimiento = $model_establecimiento->GetByIdEstablecimiento($id_establecimiento);

            $modelmedico = new Medico();

            if(Auth::user()->flagservicios==1 || Auth::user()->identificativo==1){
                $medico = $modelmedico->GetMostrarMedicoById($request->id_medico);
            }else
            {
                $medico = $modelmedico->GetMostrarMedicoByDNI($dni_medico);
            }

            $fecha_atencion = $request->fecha_atencion;

            if($request->id_atencion==3){
              $prestaciones = $modelmedico->GetMostrarAtencionesMedicoByHISHO($request->id_atencion,$id_establecimiento,$request->fecha_atencion,$request->id_medico, $request->id_servicio);

            }
            else{
              $prestaciones = $modelmedico->GetMostrarAtencionesMedicoByHIS($request->id_atencion,$id_establecimiento,$request->fecha_atencion,$request->id_medico, $request->id_servicio);
            }
            $tipo_atencion=$request->id_atencion;
            switch ($request->id_atencion) {
                case '1': $nombre_atencion='AMBULATORIO';break;
                case '2': $nombre_atencion='EMERGENCIA';break;
                case '3': $nombre_atencion='HOSPITALIZACION';break;
                case '4': $nombre_atencion='OTRO';break;
            }

            if($request->id_atencion==2){
              $evaluaciones = $modelmedico->GetMostrarEvaluacionesMedicoByHIS($request->id_atencion,$id_establecimiento,$request->fecha_atencion,$request->id_medico);
            }else{
              $evaluaciones = $modelmedico->GetMostrarEvaluacionesMedicoByHISHO($request->id_atencion,$id_establecimiento,$request->fecha_atencion,$request->id_medico);
            }

            $model_medico = new \App\Models\Medico;
            $dato_hoja_his = $model_medico->GetDocumentosPendientesFirmarHojaHis($request->id_atencion,$id_establecimiento,$request->fecha_atencion,$request->id_medico, $request->id_servicio);

            if(count($dato_hoja_his) > 0){
              if($dato_hoja_his[0]->ruta_hoja_his!=null){ $flag_pendiente_firma = 1; }
              else{ $flag_pendiente_firma = 0; }
            }else{$flag_pendiente_firma = 0; }
            $id_atencion=$request->id_atencion;
            $fecha_atencion=$request->fecha_atencion;
            $id_medico=$request->id_medico;
            $id_consultorio = $request->id_servicio;
            $dni_medico = $medico->dni;
            return view('frontend.medicos.ver_hoja_his', compact('establecimiento','codigo_upss','medico','fecha_atencion','prestaciones','nombre_atencion','tipo_atencion','nombre_upss','flag_pendiente_firma','id_atencion','fecha_atencion','id_medico','id_consultorio','id_establecimiento','dato_hoja_his','dni_medico','evaluaciones'));
          else:
                Session::flash('flash_message', 'El servicio seleccionado no existe para este tipo de atencion, verifique el tipo de atención y el servicio');
                return redirect('/all_reporte_hoja_his');

          endif;
        else:
            Session::flash('flash_message', 'flash_message', 'Debe de seleccionar un médico, el servicio, y el tipo de atencion');
                return redirect('/all_reporte_hoja_his');
        endif;

      }

      public function reportes_emergencia_general($id_user = 0) {
            $model_medico = new \App\Models\Medico();
            $model_usuario = new User;
            if (!Auth::check()) :
              return redirect('/auth/login');
            else:
              if(Auth::user()->actualizo_passw==0):
                return redirect('auth/password/change');
              endif;
            endif;

            $date = Carbon::now();
            $fechaHasta = $date->format('d-m-Y');
            $fechaDesdex = $date->format('d-m-Y');
            $fechaDesdey = strtotime('-7 day', strtotime($fechaDesdex));
            $fechaDesde = date('d-m-Y', $fechaDesdey);
            //====================
            $id_establecimiento='';
            $dni_beneficiario='';
            $nombre_beneficiario='';
            $dni_administrador='';
            $nombre_administrador='';
            $estado=1; //activo
            $opcion='Super Usuario';

            $establecimientos  = "SELECT * from establecimientos";
            $establecimientos  = DB::select($establecimientos);

            return view('frontend.medicos.ver_reportes_energencia', compact('farmacia', 'fechaDesde', 'establecimientos', 'fechaHasta','dni_beneficiario','nombre_beneficiario','dni_administrador','nombre_administrador','estado','opcion'));
        }

  public function anular_alta_medica_ver($id_user, $id_emergencia)
  {
    if (!Auth::check()) :
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
    $fecha_actual = $date->format('d-m-Y');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;

    if($prestacion->id_ext!=0):
      if($prestacion->tipo_atencion=='EMERGENCIA'):
        $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
        if($dato_emergencia->id_prioridad<4):
          $model_emergencia = new \App\Models\Emergencia;
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
    else:
         return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
    endif;

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
    if($emergencia->id_tipo_egres_med==3){
      $estable = "select * from establecimientos e where id = '$emergencia->id_establecimiento_traslado'";
      $estable=  DB::select($estable);
      $estable = $estable[0];
    }


     $model_consultorio = new \App\Models\Consultorio;
     $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
     $hospitalizacion = "select u.descripcion_upss as nombre,
                        u.id,
                        u.codigo
                        from consultorios c
                        inner join upsses u on u.id_consultorio = c.id
                        where u.id_tipo_atencion = '3' and u.estado = '1' and u.descripcion_upss like 'H%'";
                $hospitalizacion = DB::select($hospitalizacion);

                $diagnosti= array();
                $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
                $diagnos = DB::select($diagnos);
                $diagnos = $diagnos[0];
                $diagnos->cabecera = "PRINCIPAL";
                $diagnos->tipo = "DEFINITIVO";
                $diagnosti[0]=$diagnos;
                if($emergencia->id_diag_cuer1>0){

                    $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer1'";
                    $diagnos = DB::select($diagnos);
                    $diagnos = $diagnos[0];
                    $diagnos->cabecera = "SECUNDARIO";
                    $diagnos->tipo = "DEFINITIVO";
                    $diagnosti[1]=$diagnos;
                }elseif($emergencia->id_diag_cuer2>0){

                    $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer2'";
                    $diagnos = DB::select($diagnos);
                    $diagnos = $diagnos[2];
                    $diagnos->cabecera = "SECUNDARIO";
                    $diagnos->tipo = "DEFINITIVO";
                    $diagnosti[2]=$diagnos;
                }

                if($emergencia->id_tipo_egres_med=="2"){
                $upss="SELECT
                concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
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
                $upss="0";
              }
              $prioridad="SELECT * from prioridades p where id = '$emergencia->id_prioridad'";
              $prioridad= DB::select($prioridad);
              $prioridad=$prioridad[0];

              $triajes="SELECT * from triajes where id = '$emergencia->id_triaje'";
              $triajes= DB::select($triajes);
              $triajes=$triajes[0];
    $dni=Auth::user()->dni;
    $model_medico = new Medico();
    $exite_medico=$model_medico->exite_medico($dni);
    $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);

    $model_usuario = new User();
    $usuario=$model_usuario->GetById($emergencia->id_medico_egreso);
    $medico = $model_medico->GetDatosMedicoAndEstablecimiento($usuario->dni, $usuario->id_establecimiento);
    $datosanulados = "";
    $datosposanulados = "";
    if($exite_medico==0){
       Session::flash('flash_message', 'Sólo los médicos pueden anular el alta médica');
      return redirect('/all_atenciones_by_emergencia');
    }else if($exite_medico_ipress==""){
      $model_establecimiento = new Establecimiento();
      $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
      Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
      return redirect('/all_atenciones_by_emergencia');
    }else{

      return view('frontend.medicos.anular_alta_medica', compact('datosposanulados','datosanulados','triajes','prioridad','prestacion','estable','upss','diagnosti','diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'paciente_emergencia', 'emergencia','medico','datoadicional', 'sw', 'alta_anulada','usuario_anulador' ));

     }
  else:
    Session::flash('flash_message', 'Necesita tener un alta para anular el Alta Medica');
    return redirect('/all_atenciones_by_emergencia');
  endif;
  }

  public function registrar_alta_medica_anular(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $id_user =  Auth::user()->id;

    $model_emergencia = new Emergencia();
    $id_anulacion_alta = $model_emergencia->RegistrarAnulacionAlta($request->id,2,$id_user,$fechaActual,$request->motivo,$request->id_establecimiento);

      if (empty($id_anulacion_alta)) {
        Session::flash('flash_message', 'No se pudo anular el alta, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
        return redirect('/all_atenciones_by_emergencia/');
      } else {
        Session::flash('flash_message', 'Alta anulada');
        return redirect('/ver_alta_medica_anulada/' . $id_user . '/' . $request->id);
      }
  }

  public function ver_alta_medica_anulada($id_user, $id_emergencia)
  {
    if (!Auth::check()) :
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
    $fecha_actual = $date->format('d-m-Y');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;


    if($prestacion->id_ext!=0):
      if($prestacion->tipo_atencion=='EMERGENCIA'):
        $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first();
        if($dato_emergencia->id_prioridad<4):
          $model_emergencia = new \App\Models\Emergencia;
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
    else:
         return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
    endif;

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);

    $alta_anulada = Alta_medica_anulada::where('id_alta', '=', $id_emergencia)->where('estado',1)->first();

    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
    if($alta_anulada->id_tipo_egres_med==3){
      $estable = "select * from establecimientos e where id = '$alta_anulada->id_establecimiento_traslado'";
      $estable=  DB::select($estable);
      $estable = $estable[0];
    }


     $model_consultorio = new \App\Models\Consultorio;
     $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
     $hospitalizacion = "select u.descripcion_upss as nombre,
                        u.id,
                        u.codigo
                        from consultorios c
                        inner join upsses u on u.id_consultorio = c.id
                        where u.id_tipo_atencion = '3' and u.estado = '1' and u.descripcion_upss like 'H%'";
                $hospitalizacion = DB::select($hospitalizacion);

                $diagnosti= array();
                $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$alta_anulada->id_diag_cab'";
                $diagnos = DB::select($diagnos);
                $diagnos = $diagnos[0];
                $diagnos->cabecera = "PRINCIPAL";
                $diagnos->tipo = "DEFINITIVO";
                $diagnosti[0]=$diagnos;
                if($alta_anulada->id_diag_cuer1>0){

                    $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$alta_anulada->id_diag_cuer1'";
                    $diagnos = DB::select($diagnos);
                    $diagnos = $diagnos[0];
                    $diagnos->cabecera = "SECUNDARIO";
                    $diagnos->tipo = "DEFINITIVO";
                    $diagnosti[1]=$diagnos;
                }elseif($alta_anulada->id_diag_cuer2>0){

                    $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$alta_anulada->id_diag_cuer2'";
                    $diagnos = DB::select($diagnos);
                    $diagnos = $diagnos[2];
                    $diagnos->cabecera = "SECUNDARIO";
                    $diagnos->tipo = "DEFINITIVO";
                    $diagnosti[2]=$diagnos;
                }

                if($alta_anulada->id_tipo_egres_med=="2"){
                $upss="SELECT
                concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
                u.id as id_upss,
                u.codigo as codigo_upss,
                c.id as id_consultorio,
                c.nombre as nombre_consultorio
                from consultorios c
                inner join upsses u on u.id_consultorio = c.id
                where u.id_tipo_atencion = '3' and u.estado = '1' and u.descripcion_upss like 'H%' and u.id='$alta_anulada->id_upss_alta'";
                $upss= DB::select($upss);
                $upss=$upss[0];
              }else{
                $upss="0";
              }
              $prioridad="SELECT * from prioridades p where id = '$emergencia->id_prioridad'";
              $prioridad= DB::select($prioridad);
              $prioridad=$prioridad[0];

              $triajes="SELECT * from triajes where id = '$emergencia->id_triaje'";
              $triajes= DB::select($triajes);
              $triajes=$triajes[0];
    $dni=Auth::user()->dni;
    $model_medico = new Medico();
    $exite_medico=$model_medico->exite_medico($dni);
    $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);

    $model_usuario = new User();
    $usuario=$model_usuario->GetById($alta_anulada->id_medico_egreso);
    $medico = $model_medico->GetDatosMedicoAndEstablecimiento($usuario->dni, $usuario->id_establecimiento);

    if($alta_anulada->id_user_anulacion <> null):
      $usuario2=$model_usuario->GetById($alta_anulada->id_user_anulacion);
      $usuario_anulador = $model_medico->GetDatosMedicoAndEstablecimiento($usuario2->dni, $usuario->id_establecimiento);
    endif;
    $datosanulados = "SELECT * from alta_medica_anuladas where id_alta = '$id_emergencia'";
    $datosanulados= DB::select($datosanulados);
    $datosanulados=$datosanulados[0];


    if($datosanulados->id_diag_cab <> 0){
        $datocie101a = "SELECT * FROM diagnosticos where id = '$datosanulados->id_diag_cab'";
        $datocie101a= DB::select($datocie101a);
        $datocie101a=$datocie101a[0];
        $datosanulados->id_diag_cab = $datocie101a->codigo . " - " .  $datocie101a->nombre;
     }else{
      $datosanulados->id_diag_cab =  "Se Elimino";
     }

   if($datosanulados->id_diag_cuer1 <> 0){
      $datocie102a = "SELECT * FROM diagnosticos where id = '$datosanulados->id_diag_cuer1'";
      $datocie102a= DB::select($datocie102a);
      $datocie102a=$datocie102a[0];
      $datosanulados->id_diag_cuer1 = $datocie102a->codigo . " - " .  $datocie102a->nombre;
    }else{
    $datosanulados->id_diag_cuer1 =  "Se Elimino";
    }
    if($datosanulados->id_diag_cuer2 <> 0){
      $datocie103a = "SELECT * FROM diagnosticos where id = '$datosanulados->id_diag_cuer2'";
      $datocie103a= DB::select($datocie103a);
      $datocie103a=$datocie103a[0];
      $datosanulados->id_diag_cuer2 = $datocie103a->codigo . " - " .  $datocie103a->nombre;
    }else{
    $datosanulados->id_diag_cuer2 =  "Se Elimino";
    }


    $datosposanulados = "SELECT * from emergencias where id = '$id_emergencia'";
    $datosposanulados= DB::select($datosposanulados);
    $datosposanulados=$datosposanulados[0];

    if($datosposanulados->id_diag_cab <> 0){
      $datocie101d = "SELECT * FROM diagnosticos where id = '$datosposanulados->id_diag_cab'";
      $datocie101d= DB::select($datocie101d);
      $datocie101d=$datocie101d[0];
      $datosposanulados->id_diag_cab = $datocie101d->codigo . " - " .  $datocie101d->nombre;
    }else{
      $datosposanulados->id_diag_cab =  "Se Elimino";
    }

    if($datosposanulados->id_diag_cuer1 <> 0){
        $datocie102d = "SELECT * FROM diagnosticos where id = '$datosposanulados->id_diag_cuer1'";
        $datocie102d= DB::select($datocie102d);
        $datocie102d=$datocie102d[0];
        $datosposanulados->id_diag_cuer1 = $datocie102d->codigo . " - " .  $datocie102d->nombre;
      }else{
      $datosposanulados->id_diag_cuer1 =  "Se Elimino";
      }
    if($datosposanulados->id_diag_cuer2 <> 0){
        $datocie103d = "SELECT * FROM diagnosticos where id = '$datosposanulados->id_diag_cuer2'";
        $datocie103d= DB::select($datocie103d);
        $datocie103d=$datocie103d[0];
        $datosposanulados->id_diag_cuer2 = $datocie103d->codigo . " - " .  $datocie103d->nombre;
      }else{
      $datosposanulados->id_diag_cuer2 =  "Se Elimino";
      }

      switch($datosanulados->id_tipo_egres_med){
        case 1:
          $datosanulados->id_tipo_egres_med = "Alta";break;
        case 2:
          $datosanulados->id_tipo_egres_med = "Hospitalizacion";break;
        case 3:
          $datosanulados->id_tipo_egres_med = "Traslado";break;
        case 4:
          $datosanulados->id_tipo_egres_med = "Fallecido";break;
        case 6:
          $datosanulados->id_tipo_egres_med = "Fuga";break;
        case 7:
          $datosanulados->id_tipo_egres_med = "Retiro Voluntario";break;

      }
      switch($datosanulados->id_condi_egres_med){
        case 1:
          $datosanulados->id_condi_egres_med = "Mejorado";break;
        case 2:
          $datosanulados->id_condi_egres_med = "Curado";break;
        case 3:
          $datosanulados->id_condi_egres_med = "Transferido";break;
        case 4:
          $datosanulados->id_condi_egres_med = "Retiro Voluntario";break;
        case 5:
          $datosanulados->id_condi_egres_med = "Fugado";break;
        case 6:
          $datosanulados->id_condi_egres_med = "Fallecido";break;

      }

      switch($datosposanulados->id_tipo_egres_med){
        case 1:
          $datosposanulados->id_tipo_egres_med = "Alta";break;
        case 2:
          $datosposanulados->id_tipo_egres_med = "Hospitalizacion";break;
        case 3:
          $datosposanulados->id_tipo_egres_med = "Traslado";break;
        case 4:
          $datosposanulados->id_tipo_egres_med = "Fallecido";break;
        case 6:
          $datosposanulados->id_tipo_egres_med = "Fuga";break;
        case 7:
          $datosposanulados->id_tipo_egres_med = "Retiro Voluntario";break;

      }
      switch($datosposanulados->id_condi_egres_med){
        case 1:
          $datosposanulados->id_condi_egres_med = "Mejorado";break;
        case 2:
          $datosposanulados->id_condi_egres_med = "Curado";break;
        case 3:
          $datosposanulados->id_condi_egres_med = "Transferido";break;
        case 4:
          $datosposanulados->id_condi_egres_med = "Retiro Voluntario";break;
        case 5:
          $datosposanulados->id_condi_egres_med = "Fugado";break;
        case 6:
          $datosposanulados->id_condi_egres_med = "Fallecido";break;

      }


    if($exite_medico==0){
       Session::flash('flash_message', 'Sólo los médicos pueden anular el alta médica');
      return redirect('/all_atenciones_by_emergencia');
    }else if($exite_medico_ipress==""){
      $model_establecimiento = new Establecimiento();
      $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
      Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
      return redirect('/all_atenciones_by_emergencia');
    }else{

        return view('frontend.medicos.anular_alta_medica', compact('datosposanulados','datosanulados','triajes','prioridad','prestacion','estable','upss','diagnosti','diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma',  'hospitalizacion', 'consulta_externa', 'paciente_emergencia', 'emergencia','medico','datoadicional', 'sw', 'alta_anulada','usuario_anulador' ));

     }
  else:
    Session::flash('flash_message', 'Necesita tener un alta para anular el Alta Medica');
    return redirect('/all_atenciones_by_emergencia');
  endif;
  }

  /*
   * Funcion que separa los duplicados de los registros ya existentes de la programacion de profesionales
   * para darle prioridad a registros activos.
   */
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

  public function obtener_profesional_by_id($id) {
    $model_medico = new Medico();
    $profesional = $model_medico->GetMedicoById3($id);
    return json_encode($profesional);
  }

  public function get_profesionales_x_especialidad($id_establecimiento, $id_especialidad) {
    $model_medico = new Medico();
    $profesionales = $model_medico->GetProfesionalesXEspecialidad($id_establecimiento, $id_especialidad);
    return json_encode($profesionales);
  }


  public function obtenerDetalleMedico ($id, $id_farmacia) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $medico = DB::table('medicos')
              ->select(
                  'id','condicion','grupo_trabajo','estado_colegiatura','dni','colegiatura','nombre',
                  'paterno','materno','especialidad','email','nro_hijos','email_institucional','rne','nombre_firma','cip','fecha_nacimiento',
                  'sexo','celular','cel_emergencia','domicilio','fecha_inicio','fecha_cese','cargo',
                  'fecha_inicio_habilidad','fecha_termino_habilidad','id_especializacion','id_establecimiento',
                  'id_grado','tipo_documento','id_estado_civil','id_departamento','id_provincia','id_distrito',
                  'id_situacion_personal','id_condicion_laboral','id_profesion',
                  'id_colegio_profesional','id_unidad','id_sub_unidad'
              )
              ->where('id', $id)
              ->first();
    
    $dni = $medico->dni;
    $flag_rrhh = DB::table('medicos')
            ->join('users', 'medicos.dni', '=', 'users.dni')
            ->where('medicos.dni', $dni)
            ->value('users.flag_rrhh');

    $especializacion      = DB::table('especializaciones')->where('id', $medico->id_especializacion)->value('nombre');
    $grado                = DB::table('titular_grados')->where('id', $medico->id_grado)->value('descripcion');
    $tipoDoc              = DB::table('tipo_documento_identidad')->where('id', $medico->tipo_documento)->value('descripcion');
    $estadoCivil          = DB::table('estado_civiles')->where('id', $medico->id_estado_civil)->value('nombre');
    $departamento         = DB::table('departamentos')->where('id_departamento', $medico->id_departamento)->value('descripcion');
    $provincia            = DB::table('provincias')->where('id_provincia', $medico->id_provincia)->value('descripcion');
    $distrito             = DB::table('distritos')->where('id_distrito', $medico->id_distrito)->value('descripcion');
    $situacion            = DB::table('situacion_personal')->where('id', $medico->id_situacion_personal)->value('descripcion');
    $condicion            = DB::table('condicion_laboral')->where('id', $medico->id_condicion_laboral)->value('descripcion');
    $profesion            = DB::table('profesiones')->where('id', $medico->id_profesion)->value('nombre');
    $colegio              = DB::table('colegio_profesionales')->where('id', $medico->id_colegio_profesional)->value('descripcion');
    $ipress               = DB::table('establecimientos')->where('id', $medico->id_establecimiento)->value('nombre');
    $unidad               = DB::table('tipo_consultorio_divisiones')->where('id', $medico->id_unidad)->value('nombre');
    $subUnidad            = DB::table('tipo_consultorio_departamentos')->where('id', $medico->id_sub_unidad)->value('nombre');
    
    $consultorios_nombres = DB::table('medico_consultorios as mc')
                            ->join('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
                            ->where('mc.id_medico', $medico->id)
                            ->lists('c.nombre');

    $profesiones =  DB::table('medicos_especialidades as me')
                    ->leftJoin('profesiones as p', 'me.id_profesion', '=', 'p.id')
                    ->leftJoin('especializaciones as e', 'me.id_especialidad', '=', 'e.id')
                    ->where('me.id_medico', $medico->id)
                    ->select(
                            'p.nombre as profesion',
                            'e.nombre as especialidad',
                            'me.colegiatura',
                            'me.rne'
                    )
                    ->get();

    $medico->especializacion    = $especializacion;
    $medico->gradoDescr         = $grado;
    $medico->tipoDoc            = $tipoDoc;
    $medico->estadoCivil        = $estadoCivil;
    $medico->departamento       = $departamento;
    $medico->provincia          = $provincia;
    $medico->distrito           = $distrito;
    $medico->situacionPersonal  = $situacion;
    $medico->condicionLaboral   = $condicion;
    $medico->profesion          = $profesion;
    $medico->colegioProfesional = $colegio;
    $medico->ipress             = $ipress;
    $medico->unidad             = $unidad;
    $medico->subUnidad          = $subUnidad;

    $medico->flag_rrhh          = $flag_rrhh;

    $medico->profesiones = $profesiones;
    
    if ($flag_rrhh != 1) {
      $medico->servicio_atencion = null;
    } else {
      $medico->servicio_atencion = $consultorios_nombres;
    }

    return response()->json($medico);
  }

  private function etiquetasReporte()
  {
      return [
          'policial' => 'POLICIAL',
          'civil' => 'CIVIL',
          'gradodescr' => 'GRADO',
          'cip' => 'N° DE CIP',
          'tipodoc' => 'TIPO Y N° DE DOC',
          'nombre_completo' => 'APELLIDOS Y NOMBRES',
          'fecha_nacimiento' => 'FECHA DE NACIMIENTO',
          'edad' => 'EDAD',
          'estadocivil' => 'ESTADO CIVIL',
          'nro_hijos' => 'NRO HIJOS',
          'sexo' => 'SEXO',
          'celular' => 'N° DE CEL',
          'email' => 'CORREO ELECTRONICO',
          'email_institucional' => 'CORREO INSTITUCIONAL',
          'departamento' => 'DEPARTAMENTO',
          'provincia' => 'PROVINCIA',
          'distrito' => 'DISTRITO',
          'asistencial' => 'ASISTENCIAL',
          'administrativo' => 'ADMINISTRATIVO',
          'fecha_inicio' => 'F. INICIO DE LABORES',
          'fecha_cese' => 'F. CESE DE LABORES',
          'situacionpersonal' => 'SITUACION ACTUAL',
          'condicionlaboral' => 'CONDICION LABORAL',
          'profesion' => 'PROFESIÓN',
          'especializacion' => 'ESPECIALIDAD',
          'colegioprofesional' => 'COLEGIO PROFESIONAL',
          'colegiatura' => 'N° CMP',
          'rne' => 'N° RNE',
          'estado_colegiatura' => 'ESTADO DE COLEGIATURA',
          'fecha_inicio_habilidad' => 'F. INICIO DE HABILIDAD',
          'fecha_termino_habilidad' => 'F. FIN DE HABILIDAD',
          'ipress' => 'ESTABLECIMIENTO',
          'unidad' => 'UNIDAD',
          'subunidad' => 'SUB UNIDAD'
      ];
  }

  public function generar_reporte(Request $request)
  {
    if (!Auth::check()) :
        return redirect('/auth/login');
    endif;

    $campos = json_decode($request->campos, true);

    if (!$campos || count($campos) == 0) {
        return back()->with('error', 'Seleccione al menos un campo.');
    }

    $etiquetas = $this->etiquetasReporte();

    $medicos = Medico::reporteData();
    
    if (count($medicos) > 0) {
        $primerMedico = (array) $medicos[0];
    }

    return \Excel::create('reporte_'.time(), function($excel) use ($medicos, $campos, $etiquetas) {
        $excel->sheet('Reporte', function($sheet) use ($medicos, $campos, $etiquetas) {
            $encabezados = array_map(function($campo) use ($etiquetas) {
                return isset($etiquetas[$campo]) ? $etiquetas[$campo] : strtoupper($campo);
            }, $campos);
            
            $sheet->row(1, $encabezados);

            $i = 2;
            foreach ($medicos as $medico) {
                $fila = [];
                foreach ($campos as $campo) {
                    $campoLower = strtolower($campo);
                    $fila[] = isset($medico->$campoLower) ? $medico->$campoLower : '';
                }
                $sheet->row($i, $fila);
                $i++;
            }
        });
    })->download('xlsx');
  }

  private function censurarCelular($celular)
  {
      if (empty($celular)) return '-';
      $longitud = strlen($celular);
      if ($longitud <= 3) return $celular;
      return str_repeat('*', $longitud - 3) . substr($celular, -3);
  }

  private function censurarEmail($email)
  {
      if (empty($email)) return '-';
      
      $partes = explode('@', $email);
      if (count($partes) != 2) return $email;
      
      $usuario = $partes[0];
      $dominio = $partes[1];
      
      $longitud = strlen($usuario);
      
      if ($longitud <= 4) {
          return str_repeat('*', $longitud) . '@' . $dominio;
      }
      
      $inicio = substr($usuario, 0, 2);
      $fin = substr($usuario, -2);
      $medio = str_repeat('*', $longitud - 4);
      
      return $inicio . $medio . $fin . '@' . $dominio;
  }

  private function censurarDomicilio($domicilio)
  {
      if (empty($domicilio)) return '-';
      return str_repeat('*', strlen($domicilio));
  }

  public function descargarDetallePDF($id, $id_farmacia)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $usuario_dni = Auth::user()->dni;
    $fecha_hora_impresion = date('d/m/Y H:i:s');

    $medico = Medico::descargar_pdf($id);

    if (empty($medico)) {
      return back()->with('error', 'Médico no encontrado.');
    }

    $medico = $medico[0];

    $medico->celular_censurado = $this->censurarCelular($medico->celular);
    $medico->cel_emergencia_censurado = $this->censurarCelular($medico->cel_emergencia);
    $medico->email_censurado = $this->censurarEmail($medico->email);
    $medico->email_institucional_censurado = $this->censurarEmail($medico->email_institucional);
    $medico->domicilio_censurado = $this->censurarDomicilio($medico->domicilio);

    $fechaCreacion = $medico->created_at;

    $pdf = \PDF::loadView('frontend.medicos.ficha_pdf', compact('medico', 'usuario_dni', 'fecha_hora_impresion', 'fechaCreacion'));
    $pdf->getDomPDF()->set_option("enable_php", true);

    return $pdf->stream('ficha_medico_' . $medico->dni . '.pdf');
  }

  public function get_estados_historial(Request $request)
  {
    $estado = $request->input('estado');

    $medico = new Medico();

    if ($estado == "1") {
        $estados = $medico->getEstadoHistorialMedicoDesactivar();
    } else {
        $estados = $medico->getEstadoHistorialMedicoActivar();
    }

    return response()->json($estados);
  }

  public function listar_medicos_ajax_por_ipress(Request $request)
  {

    $model_personal = new Medico();
    $draw = $request->draw;
    $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
    $length = 10;
    $search_value = $request->search["value"];
    $id_establecimiento = $request->id_establecimiento;

    $p[] = (string)$start;
    $p[] = (string)$length;
    $p[] = $search_value;
    $p[] = $id_establecimiento;

    $data = $model_personal->listar_personal_ajax_por_ipress($p);

    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["draw"] = $draw;
    $result["recordsTotal"] = $iTotalDisplayRecords;
    $result["recordsFiltered"] = $iTotalDisplayRecords;
    $result["data"] = $data;

    echo json_encode($result);
  }

  public function editar_medico($id, $id_farmacia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $medico = DB::table('medicos')->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
      ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')->where('medicos.id', '=', $id)
      ->select('medicos.*', 'especializaciones.nombre as especializacion', 'establecimientos.nombre as ipress')->first();
    //dd($medico);

    $establecimientos = DB::table('establecimientos')->where('id_estado_reg',1)->where('codigo','!=','')->get();

    $medico_turno = DB::table('medico_turnos')->where('medico_turnos.id_medico', '=', $id)->orderBy('medico_turnos.dia', 'asc')->get();
    $model_consultorio = new \App\Models\Consultorio;
    //$consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
    //$consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farma->id_establecimiento);
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorioAll($farma->id_establecimiento);

    $modelcolegioprofesional = new Colegio_profesionale();
    $colegio_profesional = $modelcolegioprofesional->ShowAll();
    $modelespecializaciones = new Especializacione();
    $especializaciones = $modelespecializaciones->ShowAll();
    $model_profesion = new \App\Models\Profesione;
    $profesion = $model_profesion->ShowAll();
    $model_tipo = new \App\Models\Tipo_contrato;
    $tipo = $model_tipo->ShowAll();
    $medico_consultorio = new Medico_consultorio();
    $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($medico->id_establecimiento, $id);
    $model_tipo_documento = new Tipo_documento_identidad();
    $tipo_documento = $model_tipo_documento->BuscaTipoDocumentoIdentidad($medico->tipo_documento);

    $tipo_documento_id = $tipo_documento->id;
    $tipo_documento_text = $tipo_documento->descripcion;
    $id_establecimiento = isset(Auth::user()->id_establecimiento) ? Auth::user()->id_establecimiento : null;
    $flagestablecimientos = Auth::user()->flagestablecimientos;
    if($flagestablecimientos == 1){
      $programacion_asistencial = "SELECT 
                                        p.periodo,
                                        e.id as id_estabe,
                                        e.nombre as id_establecimiento,
                                        SUM(p.horas_programadas) AS horas_programadas,
                                        SUM(p.horas_efectivas) AS horas_efectivas
                                    FROM programacion_asistencial p
                                    INNER JOIN establecimientos e on e.id = p.id_establecimiento
                                    WHERE p.id_personal = '$id'
                                      AND p.estado = '1'
                                    GROUP BY p.periodo, e.nombre,e.id
                                    ORDER BY p.periodo ASC";
        $programacion_asistencial= DB::select($programacion_asistencial);
    }else{
      $programacion_asistencial = "SELECT 
                                        p.periodo,
                                        e.id as id_estabe,
                                        e.nombre as id_establecimiento,
                                        SUM(horas_programadas) AS horas_programadas,
                                        SUM(horas_efectivas) AS horas_efectivas
                                    FROM programacion_asistencial p
                                    INNER JOIN establecimientos e on e.id = p.id_establecimiento
                                    WHERE p.id_personal = '$id'
                                      AND p.estado = '1'
                                      AND p.id_establecimiento = '$farma->id_establecimiento'
                                    GROUP BY p.periodo,e.nombre,e.id
                                    ORDER BY p.periodo ASC;";
        $programacion_asistencial= DB::select($programacion_asistencial);
    }
    //dd($programacion_asistencial);
    //print_r($establecimientos);
    //dd($consultoriomedico);
    return view('frontend.medicos.editar_medico', compact('medico', 'establecimientos', 'id_farmacia', 'medico_turno', 'consultorio', 'programacion_asistencial',
                'consultoriomedico', 'especializaciones', 'profesion', 'tipo','colegio_profesional','tipo_documento_id','tipo_documento_text','id_establecimiento'));
  }

  public function update_medico(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    //if ($request->id_consultorio > 0):
    $update = Medico::where('id', '=', $request->id_medico)->first();
    //$update->id_consultorio = $request->id_consultorio;
    $update->nombre = $request->nombre;
    $update->paterno = $request->paterno;
    $update->materno = $request->materno;
    $update->rne = $request->rne;
    $update->id_especializacion = $request->id_especializacion;
    $update->id_profesion = $request->id_profesion;
    $update->tipo_contrato = $request->tipo_contrato;
    $update->colegiatura = $request->colegiatura;
    $update->id_colegio_profesional = $request->id_colegio_profesional;
    $update->email = $request->email;
    $update->id_establecimiento = $request->id_establecimiento;
    $update->id_user_update = $id_user;
    if ($request->hasFile('archivo_firma')) {
      $imagen = $request->file('archivo_firma');
      $nombreimagen = Str::slug($update->dni).".".$imagen->guessExtension();
      //$nombreimagen = $imagen->getClientOriginalName();
      $ruta = public_path("images/profesionales/");
      $imagen->move($ruta,$nombreimagen);

      $update->nombre_firma = $nombreimagen;
    }
    $update->save();

    $medico_turno = new Medico_turno();
    $medico_turno->DesactivaTurnoMedicoOtrasIpress($request->id_medico, $request->id_establecimiento, date('Y-m-d'));

    $medico_consultorio = new Medico_consultorio();
    $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($request->id_establecimiento, $request->id_medico);
    $existe = "no";

    if (!is_null($request->id_consultorio)) {
      foreach ($request->id_consultorio as $key => $value) {
        $existe = "no";
        foreach ($consultoriomedico as $rowConsul) :
          if ($request->id_consultorio[$key] == $rowConsul->id_consultorio) {
            $existe = "si";
          }
        endforeach;
        if ($existe == "no") {
          $nuevoconsultorio = new Medico_consultorio;
          $nuevoconsultorio->id_establecimiento = $request->id_establecimiento;
          $nuevoconsultorio->id_medico = $request->id_medico;
          $nuevoconsultorio->id_consultorio = $request->id_consultorio[$key];
          $nuevoconsultorio->estado = 1;
          $nuevoconsultorio->id_user_created = $id_user;
          $nuevoconsultorio->save();
        }
      }

      $medico_consultorio = new Medico_consultorio();
      $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($request->id_establecimiento, $request->id_medico);

      foreach ($consultoriomedico as $rowConsul) :
        $existe = "no";
        foreach ($request->id_consultorio as $key => $value) {
          if ($rowConsul->id_consultorio == $request->id_consultorio[$key]) {
            $existe = "si";
          }
        }
        if ($existe == "no") {
          $update = Medico_consultorio::where('id_medico', '=', $request->id_medico)->where('id_establecimiento', '=', $request->id_establecimiento)->where('id_consultorio', '=', $rowConsul->id_consultorio)->where('estado', '=', 1)->first();
          $update->estado = 2;
          $update->id_user_update = $id_user;
          $update->save();
        }
      endforeach;
    }

    /*endif;
        if ($request->id_establecimiento > 0):
            $update = Medico::where('id', '=', $request->id_medico)->first();
            $update->id_establecimiento = $request->id_establecimiento;
            $update->id_user_update = $id_user;
            $update->save();

            $establecimiento = DB::table('establecimientos')->where('id', '=', $request->id_establecimiento)->first();
            Session::flash('flash_message', 'Actualización exitosa. El Profesional de Salud ' . $request->nombre . ' ' . $request->paterno . ' fue asiganado a la IPRESS PNP ' . $establecimiento->nombre);
        endif;*/

    Session::flash('flash_message', 'Actualización exitosa');
    return redirect('/gestion_medicos');
  }

  public function desactivar($id, $estado)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    if ($estado == "1") {
      $update = Medico::where('id', '=', $id)->first();
      $update->estado = 3;
      $update->id_user_desactivate = $id_user;
      $update->fec_desactivate = date('Y-m-d H:i:s');
      $update->save();

      Session::flash('flash_message', 'Se desactivo correctamente');
    } else {
      $update = Medico::where('id', '=', $id)->first();
      $update->estado = 1;
      $update->id_user_activate = $id_user;
      $update->fec_activate = date('Y-m-d H:i:s');
      $update->save();
      Session::flash('flash_message', 'Se activo correctamente');
    }
  }
  public function store(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelmedico = new Medico();
    $medico_registrado = $modelmedico->medico_yaregistrado($request->dni,  $request->id_establecimiento);
    if (count($medico_registrado) > 0) {
      $dato = "El Profesional Prescriptor <b>" . $medico_registrado->nombre . " " . $medico_registrado->paterno . " " . $medico_registrado->materno . " </b> identificado con DNI <b>" . $medico_registrado->dni . "</b> ya se encuentra registrado en la IPRESS PNP
                        <b>" . $medico_registrado->ipress . "</b>, en estado ";
      $dato .= $medico_registrado->estado == "1" ? "<b>ACTIVO</b>" : "<b>INACTIVO</b>";
      return redirect('/gestion_medicos/' . $request->id_user)->withInput()->withErrors(array($dato));
    } else {
      $id_user = Auth::user()->id;
      $medico = new Medico;
      $medico->tipo_documento = $request->tipo_documento_id;
      $medico->dni = $request->dni;
      $medico->colegiatura = $request->colegiatura;
      $medico->id_establecimiento = $request->id_establecimiento;
      $medico->tipo_contrato = $request->tipo_contrato;
      $medico->id_especializacion = $request->id_especializacion;
      $medico->nombre = trim($request->nombre);
      $medico->paterno = trim($request->paterno);
      $medico->materno = trim($request->materno);
      $medico->especialidad = $request->especialidad;
      $medico->rne = $request->rne;
      $medico->email = $request->email;
      $medico->id_colegio_profesional = $request->id_colegio_profesional;
      //$medico->id_consultorio = $request->id_consultorio;
      $medico->id_profesion = $request->id_profesion;
      $medico->estado = 1;
      $medico->id_user_created = $id_user;
      if ($request->hasFile('archivo_firma')) {
        $imagen = $request->file('archivo_firma');
        $nombreimagen = Str::slug($request->dni).".".$imagen->guessExtension();
        //$nombreimagen = $imagen->getClientOriginalName();
        $ruta = public_path("images/profesionales/");
        $imagen->move($ruta,$nombreimagen);
  
        $medico->nombre_firma = $nombreimagen;
      }
      $medico->save();
      $id_medico = $medico->id;
      //-----Registro de la tabla Medico_consultorio ------------------------
      foreach ($request->id_consultorio as $key => $value) {
        $medico_consultorio = new Medico_consultorio;
        $medico_consultorio->id_establecimiento = $request->id_establecimiento;
        $medico_consultorio->id_medico = $id_medico;
        $medico_consultorio->id_consultorio = $request->id_consultorio[$key];
        $medico_consultorio->estado = 1;
        $medico_consultorio->id_user_created = $id_user;
        $medico_consultorio->save();
      }
      Session::flash('flash_message', 'Registro exitoso');
      return redirect('/gestion_medicos/' . $request->id_user);
    }
  }

  public function descargarConstanciaTrabajo ($id, $id_farmacia) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $usuario_dni = Auth::user()->dni;
    $fecha_hora_impresion = date('d/m/Y H:i:s');

    $medico = Medico::descargar_pdf($id);

    if (empty($medico)) {
      return back()->with('error', 'Médico no encontrado.');
    }

    $medico = $medico[0];

    $fechaCreacion = $medico->created_at;

    $pdf = \PDF::loadView('frontend.medicos.constancia_trabajo_pdf', compact('medico', 'usuario_dni', 'fecha_hora_impresion', 'fechaCreacion'));
    $pdf->getDomPDF()->set_option("enable_php", true);

    return $pdf->stream('ficha_medico_' . $medico->dni . '.pdf');
  }

  function getMedicoByTurnoEstablecimientoConsultorio($turno, $establecimiento_id, $consultorio_id)
  {
    $query = DB::table('medico_turnos as mt')
                  ->join('medicos as m', 'm.id', '=', 'mt.id_medico')
                  ->select('m.*')
                  ->where('mt.id_consultorio', $consultorio_id)
                  ->where('mt.id_establecimiento', $establecimiento_id);

    if (!empty($turno) && $turno !== "0") {
      $query->where('mt.turno', $turno);
    }
    $medicos = $query->groupBy('m.id')->get();
    
    return response()->json($medicos, 200);
  }

  public function obtener_ultimo_diagnostico_emergencia($id_emergencia)
  {
    try {
      $prestacion = DB::table('prestaciones')
        ->where('id_ext', $id_emergencia)
        ->where('id_tipo_atencion', 2)
        ->where('id_estado_reg', 1)
        ->first();

      if (!$prestacion) return response()->json([]);

      $evaluacion = DB::table('evaluaciones')
        ->where('id_emergencia', $id_emergencia)
        ->where('id_tipo_atencion', 2)
        ->where('id_estado_reg', 1)
        ->orderBy('created_at', 'desc')
        ->first();

      $id_evaluacion = $evaluacion ? $evaluacion->id : 0;

      $diagnosticos = DB::select("
        SELECT DISTINCT
          t1.id_diagnostico,
          COALESCE(t1.id_tipo_diagnostico, 0) AS id_tipo_diagnostico,
          t2.codigo,
          t1.id_evaluacion,
          t1.id_categoria,
          (CASE WHEN t2.codigo = 'Z21.X' OR t2.codigo ILIKE 'B20%' THEN '' ELSE t2.nombre END) AS nombre,
          t1.id_clasificacion_diagnosticos,
          td.descripcion
        FROM receta_diagnosticos t1
          INNER JOIN diagnosticos t2 ON t1.id_diagnostico = t2.id AND t1.id_interconsulta = 0
          INNER JOIN tipo_diagnosticos td ON t1.id_tipo_diagnostico = td.id
        WHERE t1.id_prestacion = ?
          AND t1.id_evaluacion = ?
          AND t1.id_consejeria = 0
          AND t1.id_interconsulta = 0
          AND t1.estado = 1
      ", [$prestacion->id, $id_evaluacion]);

      return response()->json($diagnosticos);

    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

    	public function detalle_medico_creacion($id)
	{
		$id_establecimiento = Auth::user()->id_establecimiento;
$dato = DB::table('medicos as m')

    ->join('users as u', 'm.id_user_created', '=', 'u.id')
    ->leftJoin('users as u2', 'm.id_user_update', '=', 'u2.id')

    ->where('m.id', $id)

    ->select(
        'm.created_at as fecha_creacion',
        DB::raw("(u.\"name\" || ' ' || u.paterno || ' ' || u.materno || ' - ' || u.dni) as dato_creador"),

        'm.updated_at as fecha_actualizacion',
        DB::raw("(u2.\"name\" || ' ' || u2.paterno || ' ' || u2.materno || ' - ' || u2.dni) as dato_actualizador")
    )

    ->get();
		return response()->json($dato);
	}
}