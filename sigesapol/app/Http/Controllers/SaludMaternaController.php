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
use App\Models\Saludmaterna;
use App\Models\Tv;
use App\Models\Prestaciones;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\TipoAtencione;
use App\Models\Asegurado;
use App\Models\Laboratorio;
use App\Models\Radiologia;
use App\Models\Totem;
use Exception;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\json_encode;

class SaludMaternaController extends Controller
{
 
  function __construct()
    {
        $this->middleware('auth'); 
    } 
    
  public function all_atenciones_puerpera($id_user = 0)
  {

    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_user = Auth::user()->id;
    $usuario_actual = $model_usuario->GetById($id_user);
    $flagemergenciareferencia = $usuario_actual->flaghistoria;
    $id_consultorio_actual = $usuario_actual->id_consultorio;

    $tipo_registro = $usuario_actual->tipo_registro;

    $dni_beneficiario = "";
    $nombre_beneficiario = "";
    $estado = "0";
    $id_atencione = "0";
    
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');

    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $modo_validacion = $farmacia->modo_validacion;

    $medico = 0;
    if ($flagemergenciareferencia != 1) {
      if (Auth::user()->tipo_registro == 1) {
        $buscamedico = $model_medico->medico_yaregistrado($usuario_actual->dni, Auth::user()->id_establecimiento);
        if (is_object($buscamedico)) :
          $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($buscamedico->dni, $farmacia->id_establecimiento);
          $medico = $medico_actual->id;
        else :
          return redirect('/dashboard')->withFlashDanger("Tiene que ser médico de este establecimiento; para entrar a esta opción: coordinar con el administrador del Sistema");
        endif;
      }
    }

    $model_consultorio = new Consultorio;
   
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

    $model_atencione = new TipoAtencione;
    $atencione = $model_atencione->ShowTipoAtencionAll();

      return view('frontend.saludmaterna.all_atenciones_puerpera', compact('tipo_registro', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual', 'atencione', 'id_atencione'));

  }
  
  public function listar_salud_materna_puerpera(Request $request)
  {

    $model_saludmaterna = new Saludmaterna();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    $p[] = $request->id_medico;
    $p[] = $request->estado;
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $data = $model_saludmaterna->listar_salud_materna_puerpera($p);
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

  public function all_atenciones_gestantes_puerpera($id_user = 0)
  {

    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_user = Auth::user()->id;
    $usuario_actual = $model_usuario->GetById($id_user);
    $flagemergenciareferencia = $usuario_actual->flaghistoria;
    $id_consultorio_actual = $usuario_actual->id_consultorio;

    $tipo_registro = $usuario_actual->tipo_registro;

    $dni_beneficiario = "";
    $nombre_beneficiario = "";
    $estado = "0";
    $id_atencione = "0";
    
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');

    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $modo_validacion = $farmacia->modo_validacion;

    $medico = 0;
    if ($flagemergenciareferencia != 1) {
      if (Auth::user()->tipo_registro == 1) {
        $buscamedico = $model_medico->medico_yaregistrado($usuario_actual->dni, Auth::user()->id_establecimiento);
        if (is_object($buscamedico)) :
          $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($buscamedico->dni, $farmacia->id_establecimiento);
          $medico = $medico_actual->id;
        else :
          return redirect('/dashboard')->withFlashDanger("Tiene que ser médico de este establecimiento; para entrar a esta opción: coordinar con el administrador del Sistema");
        endif;
      }
    }

    $model_consultorio = new Consultorio;
   
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

    $model_atencione = new TipoAtencione;
    $atencione = $model_atencione->ShowTipoAtencionAll();

      return view('frontend.saludmaterna.all_atenciones_gestantes_puerpera', compact('tipo_registro', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual', 'atencione', 'id_atencione'));

  }

  public function listar_salud_materna(Request $request)
  {

    $model_saludmaterna = new Saludmaterna();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    $p[] = $request->id_medico;
    $p[] = $request->estado;
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $data = $model_saludmaterna->listar_salud_materna($p);
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

  public function all_gestantes_individual($id_user = 0)
  {

    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_user = Auth::user()->id;
    $id_establecimiento = Auth::user()->id_establecimiento;
    $usuario_actual = $model_usuario->GetById($id_user);
    $flagemergenciareferencia = $usuario_actual->flaghistoria;

    $tipo_registro = $usuario_actual->tipo_registro;

    $dni_beneficiario = "";
    $nombre_beneficiario = "";
    $estado = "0";
  
    
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');

      return view('frontend.saludmaterna.gestantes_individual', compact('tipo_registro', 'fechaDesde', 'fechaHasta', 'id_establecimiento', 'dni_beneficiario', 'nombre_beneficiario', 'estado'));

  }

  public function listar_gestantes_individual_vista(Request $request)
  {

    $model_saludmaterna = new Saludmaterna();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    $p[] = $request->estado;
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $data = $model_saludmaterna->listar_gestantes_individual_vista($p);
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

  public function hc_electronica_salud_materna($id_asegurado) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_paciente = new \App\Models\Paciente;
    $model_prestacion = new \App\Models\Saludmaterna;
    $paciente = $model_paciente->getById($id_asegurado);
    $atenciones = $model_prestacion->GetAtencionesBYSaludMaternaByIdAsegurado($paciente->id_asegurado);
  
    return view('frontend.saludmaterna.hc_electronica_salud_materna', compact('paciente','atenciones'));
  }

}
