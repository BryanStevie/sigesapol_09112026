<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bitacora_edicione;
use App\Models\Farmacia;
use App\Models\Establecimiento;
use App\Models\Consultorio;
use App\Models\Permiso;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Auth;
use App\Models\Totem;
use App\Models\CorredoresDetalle;
use App\Models\SubConsultorio;
use App\Models\Medico;
use SoapClient;
use Session;
use DB;
use App;
use App\Models\Menu;
use Carbon\Carbon;
class UsuarioController extends Controller


{

  function __construct()
    {
        $this->middleware('auth'); 
    } 
  
    public function index()
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      else:
        if(Auth::user()->actualizo_passw==0):
          return redirect('auth/password/change');
        endif;
      endif;

      $ruta = Route::getCurrentRoute()->getPath();
      if (count($ruta = explode("/", $ruta)) > 1);
      $ruta = $ruta[0];
      
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      $permiso = $menu[0];
      //print_r($permiso);

      if ($permiso->lectura == "")
        return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
      
      return view('frontend.usuarios.all', array(
        'permiso' => $permiso
      ));
    }

  public function all_permiso()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;
    if ($existe == "1") {
      $usuarios = $model->ShowAllActivos();
    } else {
      $usuarios = $model->ShowAllIdFarmaciaActivos($id_farmacia);
    }

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    return view('frontend.permisos.all', ['usuarios' => $usuarios, 'permiso' => $permiso]);
  }

  public function obtener_permiso_por_idusuario($iduser = 0)
  {
    $id_user = Auth::user()->id;
    $user_editor = "SELECT * from users where id = '$id_user'";
    $user_editor = DB::select($user_editor);
    $user_editor = $user_editor[0];
    
    if($user_editor->nivel_user == "3"){
      $model_menu = new \App\Models\Menu;
      //$menu = $model_menu->getMenuByUsuario($iduser);
      $menu = Menu::find($iduser);
    }else{
      if($user_editor->flagresponsablefarmacia == '1' && $user_editor->flagfarmacias == '1' && $user_editor->responsable == '1'){
        $model_menu = new \App\Models\Menu;
        // TODO: Trae todos los menus principales, disponibles para el usuario
        //$menu = $model_menu->getMenuByUsuariojefedarmacia($iduser);
        $menu = Menu::where('parentid', '0')->orderBy('orden', 'asc')->select('id as idmenu', '*')->get();
      }else{
        $model_menu = new \App\Models\Menu;
        $menu = $model_menu->getMenuByUsuarionivel2($iduser);
      }

    }

    return $menu;
  }

  public function obtener_permiso_por_idusuario_menuprincipal($iduser)
  {
    $permisos = "SELECT * from permisos p where idusuario = '$iduser'";
    $permisos = DB::select($permisos);
    
    ////// busca el id del usuario editor que brindara los permisos para saber que menus puede dar segun su nivel de usuario editor
    $id_user = Auth::user()->id;
    $user_editor = "SELECT * from users where id = '$id_user'";
    $user_editor = DB::select($user_editor);
    $user_editor = $user_editor[0];
    //////

    $menu_primero = "SELECT m.id,m.nombremenu,m.parentid,m.orden,'0' as crear,'0' as modificar,'0' as eliminar,'0' as lectura,'1' as nivel_orden
          FROM menus m
          WHERE parentid = '0' and flagregistro = '1'
          ORDER BY CAST(m.orden AS int) ASC;";
    $menu_primero = DB::select($menu_primero);

    $menu_secundario = "SELECT m.id,m.nombremenu,m.parentid,m.orden,'0' as crear,'0' as modificar,'0' as eliminar,'0' as lectura,'2' as nivel_orden
          FROM menus m
          WHERE url = '/#' and flagregistro = '1' and m.parentid != '0'
          ORDER BY CAST(m.orden AS int) ASC;";
    $menu_secundario = DB::select($menu_secundario);

    $sub_menu = "SELECT m.id,m.nombremenu,m.parentid,m.orden,'0' as crear,'0' as modificar,'0' as eliminar,'0' as lectura,'3' as nivel_orden
          FROM menus m
          WHERE url != '/#' and flagregistro = '1' and m.parentid != '0'
          ORDER BY CAST(m.orden AS int) ASC;";
    $sub_menu = DB::select($sub_menu);
  
    $dato = [];
    $i = 0;
    foreach($menu_primero as $primero):
        $id_padre = $primero->id;
        $dato[$i] = $primero;
            foreach($menu_secundario as $secundario):
             
                if($id_padre == $secundario->parentid){
                  $i=$i+1;
                  $id_sub_padre = $secundario->id;
                  $dato[$i] = $secundario;
                    foreach($sub_menu as $tercero):
                      
                      if($id_sub_padre == $tercero->parentid){
                        $i=$i+1;
                        $dato[$i] = $tercero;
                      };
                    endforeach;
                };
              endforeach;

              foreach($sub_menu as $terceros):
                if($id_padre == $terceros->parentid){
                    $terceros->nivel_orden = 4;
                    $i=$i+1;
                    $dato[$i] = $terceros;
                };
              endforeach;
            
              $i=$i+1;    
    endforeach;

    foreach($dato as $menus):
      foreach($permisos as $permiso):
          if($menus->id == $permiso->idmenu){
              $menus->crear = $permiso->crear;
              $menus->modificar = $permiso->modificar;
              $menus->eliminar = $permiso->eliminar;
              $menus->lectura = $permiso->lectura;
          }
      endforeach;
    endforeach;
  
    $menu = $dato;

    return $menu;
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model = new Farmacia();
    $model_consultorio = new Consultorio();
    $model_establecimiento = new Establecimiento();
    $usuario = Auth::user()->id;
    $user = "SELECT * from users where id = '$usuario' and status = '1'";
    $user = DB::select($user);
    $user = $user[0];
    $farmacias = $model->GetFarmaciaByIdEstablecimiento(Auth::user()->id_establecimiento);
    $consultorios = $model_consultorio->ShowConsultoriosAll();
    $establecimientos = $model_establecimiento->ShowAll();
    $totems = Totem::where('id_estado', '=', 1)->get();
    return view('frontend.usuarios.create', compact('farmacias', 'consultorios', 'establecimientos','totems','user'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store_permiso(Request $request)
  {
    $id_user = Auth::user()->id;
    if($request->id_usuario == "" or $request->id_usuario == "0"){
      return redirect('/all_permiso')->withFlashSuccess("Debe Seleccionar algun Usuario para dar Permisos");
    }else{
      $usuario = User::find($request->id_usuario);
    
      $usuario->fecha_actualizacion = new Carbon();
      $usuario->usuario_actualizador = $id_user;
      $usuario->detalle_actualizacion = 2;
      $usuario->detalle_actualiza_servicio = "";
      $usuario->detalle_actualiza_flag = "";
      $usuario->save();
  
      foreach ($request->idmenu as $key => $value) :
        $updatePermiso = Permiso::where('idusuario', $request->id_usuario)->where('idmenu', $value)->first();
        if (count($updatePermiso) == 0)
          $updatePermiso = new \App\Models\Permiso;
        $updatePermiso->idusuario = $request->id_usuario;
        $updatePermiso->idmenu = $value;
        $updatePermiso->updated_us = Auth::user()->id;
        $updatePermiso->crear = (isset($request->crear[$value]) && $request->crear[$value] > 0) ? $request->crear[$value] : 0;
        $updatePermiso->modificar = (isset($request->modificar[$value]) && $request->modificar[$value] > 0) ? $request->modificar[$value] : 0;
        $updatePermiso->eliminar = (isset($request->eliminar[$value]) && $request->eliminar[$value] > 0) ? $request->eliminar[$value] : 0;
        $updatePermiso->lectura = (isset($request->lectura[$value]) && $request->lectura[$value] > 0) ? $request->lectura[$value] : 0;
        $updatePermiso->save();
      endforeach;
      /*
            $model_permiso = new \App\Models\Permiso;
            $permiso = $model_permiso->getPermisoPrincipalByUsuario($request->id_usuario);
  
            foreach ($permiso as $per):
            $insertPermiso = new \App\Models\Permiso;
            $insertPermiso->idusuario 	= $request->id_usuario;
            $insertPermiso->idmenu 	= $per->idmenu;
            $insertPermiso->crear 		= 0;
            $insertPermiso->modificar	= 0;
            $insertPermiso->eliminar 	= 0;
            $insertPermiso->lectura 	= 1;
            $insertPermiso->save();
            endforeach;
           */
  
      return redirect('/all_permiso')->withFlashSuccess("Se registro correctamente el Permiso");
    }
  
  }

  public function store(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $id_user = Auth::user()->id;
    $usuario = new User;
    $usuario->dni = $request->dni;
    $usuario->name = $request->name;
    $usuario->paterno = $request->paterno;
    $usuario->materno = $request->materno;
    $usuario->tipo_registro = $request->tipo_registro;
    $usuario->email = $request->email;
    $password = bcrypt($request->password);
    $usuario->password = $password;
    $usuario->confirmation_code = md5(uniqid(mt_rand(), true));
    $usuario->remember_token = $request->_token;
    //$usuario->id_establecimiento = $request->id_establecimiento;
    $usuario->detalle_actualizacion = 10;
    if(isset($request->nivel_user)){
      $usuario->nivel_user = $request->nivel_user;
    }else{
      $usuario->nivel_user = 1;
    }
  
    $usuario->usuario_actualizador = $id_user;
    $usuario->fecha_actualizacion = new Carbon();
    if (Auth::user()->flagestablecimientos == 1) :
      $usuario->id_establecimiento = $request->id_establecimiento;
    else :
      $usuario->id_establecimiento = Auth::user()->id_establecimiento;
    endif;
    $usuario->id_farmacia = $request->id_farmacia;
    $usuario->id_consultorio = $request->id_consultorio;
    $usuario->id_totem = $request->id_totem;
    $usuario->celular = $request->celular;
    //$usuario->confirmed=isset($request->confirmed) ? 1 : 0;
    $usuario->confirmed = 1;
    $usuario->status = isset($request->status) ? 1 : 0;
    $usuario->status_token = isset($request->status_token) ? 1 : 0;
    $usuario->responsable = isset($request->responsable) ? 1 : 0;
    $usuario->flagestablecimientos = isset($request->flagestablecimientos) ? 1 : 0;
    $usuario->flagfarmacias = isset($request->flagfarmacias) ? 1 : 0;
    $usuario->flagservicios = isset($request->flagservicios) ? 1 : 0;
    $usuario->flagresponsablefarmacia = isset($request->flagresponsablefarmacia) ? 1 : 0;
    $usuario->flagfiscalizador = isset($request->flagfiscalizador) ? 1 : 0;
    $usuario->flaghistoria = isset($request->flaghistoria) ? 1 : 0;
    $usuario->flagemergenciareferencia = isset($request->flagemergenciareferencia) ? 1 : 0;
    $usuario->flaghospitalizacion = isset($request->flaghospitalizacion) ? 1 : 0;
    $usuario->flagemergencia = isset($request->flagemergencia) ? 1 : 0;
    $usuario->flag_can = isset($request->flag_can) ? 1 : 0;
    $usuario->id_user_created = $id_user;
    $usuario->id_user_update = $id_user;
    
    // cambios
    $usuario->flag_rrhh = isset($request->flagRRHH) ? 1 : 0;
    
    //===============VALIDACIÓN DE VENTANILLAS=====================//
    if ($request->id_establecimiento == 76 && $request->id_farmacia == 121) {
      if (isset($request->flagventanilla)) {
        $usuario->flagventanilla = 1;
        $usuario->id_ventanilla = null;
      } else if (isset($request->flagventanilla) == false && $request->id_ventanilla == 0) {
        $usuario->flagventanilla = 0;
        $usuario->id_ventanilla = null;
      } else {
        $usuario->flagventanilla = 0;
        $usuario->id_ventanilla = $request->id_ventanilla;
      }
    } else {
      $usuario->id_ventanilla = null;
      $usuario->flagventanilla = 0;
    }
    //=============================================================

    //=============Agrergar nuevos campos===============
    //print_r($usuario);
    //exit();
    /*
          $existe = $unidad->ExisteUnidad($abrev, 0);
          if ($existe) {
          return redirect('/create_unidad')
          ->withInput()
          ->withErrors(array('La Unidad digitada ya existe'));
          }
         */

    $model_usuario = new User;
    $existe = $model_usuario->ExisteEmail($request->email, 0);
    if ($existe) {
      return redirect('/all_usuario')
        ->withInput()
        ->withErrors(array('No se ha podido registrar, Email ya existe'));
    } else {
      $usuario->save();
      $user_id = $usuario->id;
      if (isset($request->responsable)) :
        $roles = new \App\Models\Assigned_role;
        $roles->user_id = $user_id;
        $roles->role_id = 3;
        $roles->save();
      /*
              else:
              $delete = \App\Models\Assigned_role::where('id', '=', $id_rol)->first();
              $delete->delete();
             */
      endif;
              
      if($usuario->tipo_registro == "1"){

        $permiso_procesos = new Permiso;//cabecera procesos
        $permiso_procesos->idmenu = 3;
        $permiso_procesos->crear = 1;
        $permiso_procesos->modificar = 1;
        $permiso_procesos->eliminar = 1;
        $permiso_procesos->lectura = 1;
        $permiso_procesos->created_at = new carbon;
        $permiso_procesos->idusuario = $user_id;
        $permiso_procesos->save();

        $permiso_cita = new Permiso;
        $permiso_cita->idmenu = 83;
        $permiso_cita->crear = 1;
        $permiso_cita->modificar = 1;
        $permiso_cita->eliminar = 1;
        $permiso_cita->lectura = 1;
        $permiso_cita->created_at = new carbon;
        $permiso_cita->idusuario = $user_id;
        $permiso_cita->save();

        $permiso_atenciones = new Permiso;
        $permiso_atenciones->idmenu = 84;
        $permiso_atenciones->crear = 1;
        $permiso_atenciones->modificar = 1;
        $permiso_atenciones->eliminar = 1;
        $permiso_atenciones->lectura = 1;
        $permiso_atenciones->created_at = new carbon;
        $permiso_atenciones->idusuario = $user_id;
        $permiso_atenciones->save();
        
        $permiso_consultas = new Permiso;//cabecera consultas
        $permiso_consultas->idmenu = 20;
        $permiso_consultas->crear = 1;
        $permiso_consultas->modificar = 1;
        $permiso_consultas->eliminar = 1;
        $permiso_consultas->lectura = 1;
        $permiso_consultas->created_at = new carbon;
        $permiso_consultas->idusuario = $user_id;
        $permiso_consultas->save();

        $permiso_hoja = new Permiso;
        $permiso_hoja->idmenu = 104;
        $permiso_hoja->crear = 1;
        $permiso_hoja->modificar = 1;
        $permiso_hoja->eliminar = 1;
        $permiso_hoja->lectura = 1;
        $permiso_hoja->created_at = new carbon;
        $permiso_hoja->idusuario = $user_id;
        $permiso_hoja->save();

        if( $usuario->id_establecimiento == "76"){
          $permiso_interconsulta = new Permiso;
          $permiso_interconsulta->idmenu = 101;
          $permiso_interconsulta->crear = 1;
          $permiso_interconsulta->modificar = 1;
          $permiso_interconsulta->eliminar = 1;
          $permiso_interconsulta->lectura = 1;
          $permiso_interconsulta->created_at = new carbon;
          $permiso_interconsulta->idusuario = $user_id;
          $permiso_interconsulta->save();
      }

        if( $usuario->id_establecimiento == "76"){
            $permiso_llamado = new Permiso;
            $permiso_llamado->idmenu = 113;
            $permiso_llamado->crear = 1;
            $permiso_llamado->modificar = 1;
            $permiso_llamado->eliminar = 1;
            $permiso_llamado->lectura = 1;
            $permiso_llamado->created_at = new carbon;
            $permiso_llamado->idusuario = $user_id;
            $permiso_llamado->save();
        }
      }
       /*
      if (isset($request->responsable)) :
        $roles = new \App\Models\Assigned_role;
        $roles->user_id = $user_id;
        $roles->role_id = 3;
        $roles->save();
      
              else:
              $delete = \App\Models\Assigned_role::where('id', '=', $id_rol)->first();
              $delete->delete();
             
      endif;*/
    }


    //$usuario->save();
    return redirect('/all_usuario')->withFlashSuccess("Se registro correctamente el Usuario");
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
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_usuario = new User;
    $model_farmacia = new Farmacia();
    $model_consultorio = new Consultorio();
    $model_sub_consultorio = new SubConsultorio();
    //$model_ventanilla = new TvController();
    $usuarios = $model_usuario->GetById($id);
    $farmacias = $model_farmacia->ShowFarmacias();
    $consultorios = $model_consultorio->ShowConsultoriosAll();
    $ticket_servicios = $model_consultorio->ShowConsultoriosTicketsAll();
    $sub_consultorio_t = $model_sub_consultorio->mostrarSubConsultoriosPorIdConsultorio($usuarios->id_establecimiento,$usuarios->id_consultorio_ticket);
    $model_establecimiento = new Establecimiento();
    $establecimientos = $model_establecimiento->ShowAll();
    $totems = Totem::where('id_estado', '=', 1)->get();
    $id_establecimiento = $usuarios->id_establecimiento;
    $farmacia = $model_farmacia->GetFarmaByUser($id);
    $usuario = Auth::user()->id;
    $user = "SELECT * from users where id = '$usuario' and status = '1'";
    $user = DB::select($user);
    $user = $user[0];
    //$ventanillas = $model_ventanilla->obtenerVentanillaPorServicio($usuarios->id_consultorio);
    //$vent = $usuarios->id_ventanilla;
    if ($farmacia)
      $id_establecimiento = $farmacia->id_establecimiento;

    $rol = $model_usuario->getAsignacionRol($id);
    if (isset($rol[0]->id))
      $id_rol = $rol[0]->id;
//  return view('frontend.usuarios.edit', compact('totems','usuarios', 'farmacias', 'consultorios', 'establecimientos', 'id_establecimiento', 'id_rol','ventanillas', 'vent'));
return view('frontend.usuarios.edit', compact('totems','usuarios','user', 'farmacias', 'consultorios', 'establecimientos', 'id_establecimiento', 'id_rol','ticket_servicios', 'sub_consultorio_t'));

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    //$usuario = new User;

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $detalle="";
    //dd($request->id);
    $id_user = Auth::user()->id;
    $usuario = User::find($request->id);
    //inicio consultas datos generales 
    $consulta_email = $usuario->email;
    $consulta_id_establecimiento = $usuario->id_establecimiento;
    $consulta_id_consultorio_ticket = $usuario->id_consultorio_ticket;
    $consulta_id_farmacia = $usuario->id_farmacia;
    $consulta_id_consultorio = $usuario->id_consultorio;
    $consulta_nivel_user = $usuario->nivel_user;
    $consulta_id_totem = $usuario->id_totem;
    $consulta_tipo_registro = $usuario->tipo_registro;
    $consulta_celular = $usuario->celular;
    $consulta_id_servicio_ticket = $usuario->id_servicio_ticket;
    // consulta flags 
    $consulta_status = $usuario->status;
    $consulta_status_token = $usuario->status_token;
    $consulta_flagestablecimientos = $usuario->flagestablecimientos;
    $consulta_flagfarmacias = $usuario->flagfarmacias;
    $consulta_flagservicios = $usuario->flagservicios;
    $consulta_flagresponsablefarmacia = $usuario->flagresponsablefarmacia;
    /*----------------------------- REPORTE IMAGENOLOGÍA ---------------------------------------*/
    $consulta_flag_reporte_imagenes = $usuario->flag_reporte_imagenes;
    /*------------------------------------------------------------------------------------------*/
    /*----------------------------- FIRMAS PENDIENTES ---------------------------------------*/
    $consulta_flag_firmas_pendientes = $usuario->flag_firmas_pendientes;
    /*------------------------------------------------------------------------------------------*/
    $consulta_flagfiscalizador = $usuario->flagfiscalizador;
    $consulta_flaghistoria = $usuario->flaghistoria;
    $consulta_flagemergenciareferencia = $usuario->flagemergenciareferencia;
    $consulta_flaghospitalizacion = $usuario->flaghospitalizacion;
    $consulta_flagemergencia = $usuario->flagemergencia;
    $consulta_flagllamadacita = $usuario->flagllamadacita;
    $consulta_flagmodulollamado = $usuario->flagmodulollamado;
    $consulta_flagemergencia = $usuario->flagemergencia;
    $consulta_responsable = $usuario->responsable;
    $flag_estadistica_vista = $usuario->flag_estadistica_vista;
    //fin consulta 

    $usuario->dni = $request->dni;
    $usuario->name = $request->name;
    $usuario->paterno = $request->paterno;
    $usuario->materno = $request->materno;
    $validaservicio = 0;
    $detalleservicio = "";
    $validabitacora = 0;
    $validabitacorafarmacia = 0;
    $validabitacoraestablecimiento = 0;
    $validabitacoraservicio = 0;
    $usuario->nivel_user = $request->nivel_user;
    if($consulta_nivel_user == $usuario->nivel_user ){
      $detalleservicio = $detalleservicio ."";
    }else{
      $detalleservicio = $detalleservicio ."nivel_user ";
      $validaservicio = $validaservicio + 1;
    }
    $usuario->email = $request->email;
    if($consulta_email == $usuario->email ){
      $detalleservicio = $detalleservicio ."";
    }else{
      $detalleservicio = $detalleservicio ."email ";
      $validaservicio = $validaservicio + 1;
    }
    $usuario->id_farmacia = $request->id_farmacia;
    if($consulta_id_farmacia == $usuario->id_farmacia ){
      $detalleservicio = $detalleservicio ."";

    }else{
      $detalleservicio = $detalleservicio ."id_farmacia ";
      $validaservicio = $validaservicio + 1;
      $validabitacora = $validabitacora + 1;
      $validabitacorafarmacia = 1;

    }
    $usuario->id_consultorio = $request->id_consultorio;
    if($consulta_id_consultorio == $usuario->id_consultorio ){
      $detalleservicio = $detalleservicio ."";
    }else{
      $detalleservicio = $detalleservicio ."id_consultorio ";
      $validaservicio = $validaservicio + 1;
      $validabitacora = $validabitacora + 1;
      $validabitacoraservicio = 1;
    }
    $usuario->id_consultorio_ticket = $request->id_servicio_ticket;
    if($consulta_id_consultorio_ticket == $usuario->id_consultorio_ticket ){
      $detalleservicio = $detalleservicio ."";
    }else{
      $detalleservicio = $detalleservicio ."id_consultorio_ticket ";
      $validaservicio = $validaservicio + 1;
    }

    $usuario->id_sub_consultorio_cita_ticket = $request->id_sub_consultorio_ticket;

    $usuario->id_totem = $request->id_totem;
    if($consulta_id_totem == $usuario->id_totem ){
      $detalleservicio = $detalleservicio ."";
    }else{
      $detalleservicio = $detalleservicio ."id_totem ";
      $validaservicio = $validaservicio + 1;
    }
    $usuario->tipo_registro = $request->tipo_registro;
    if($consulta_tipo_registro == $usuario->tipo_registro ){
      $detalleservicio = $detalleservicio ."";
    }else{
      $detalleservicio = $detalleservicio ."tipo_registro ";
      $validaservicio = $validaservicio + 1;
    }
    $usuario->celular = $request->celular;
    if($consulta_celular == $usuario->celular ){
      $detalleservicio = $detalleservicio ."";
    }else{
      $detalleservicio = $detalleservicio ."celular ";
      $validaservicio = $validaservicio + 1;
    }
    //$usuario->confirmed=isset($request->confirmed) ? 1 : 0;
    $usuario->confirmed = 1;
    $validaestado = 0;
    $usuario->status = isset($request->status) ? 1 : 0;
    if($consulta_status == isset($request->status) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $validaestado = 1;
    }
    $validaflag = 0;
    $usuario->status_token = isset($request->status_token) ? 1 : 0;
    if($consulta_status_token == isset($request->status_token) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."status_token ";
      $validaflag = $validaflag + 1;
    }
    /////////
    $usuario->flag_estadistica_vista = $request->flag_estadistica_vista;
    if($flag_estadistica_vista == $usuario->flag_estadistica_vista ){
      $detalleservicio = $detalleservicio ."";
    }else{
      $detalleservicio = $detalleservicio ."flag_estadistica_vista ";
      $validaservicio = $validaservicio + 1;
    }
    //===============VALIDACIÓN DE VENTANILLAS=====================//
    /*if ($request->id_establecimiento == 76 && $request->id_farmacia == 121) {
      if (isset($request->flagventanilla)) {
        $usuario->flagventanilla = 1;
        $usuario->id_ventanilla = null;
      } else if (isset($request->flagventanilla) == false && $request->id_ventanilla == 0) {
        $usuario->flagventanilla = 0;
        $usuario->id_ventanilla = null;
      } else {
        $usuario->flagventanilla = 0;
        $usuario->id_ventanilla = $request->id_ventanilla;
      }
    } else {
      $usuario->id_ventanilla = null;
      $usuario->flagventanilla = 0;
    }*/
    $user = User::where('id','=',$request->id)->first();
    if ($user->id_corredores_detalle !== null) {
        $corredores_detalle = CorredoresDetalle::where('id','=',$user->id_corredores_detalle)->first();
        $user->id_corredores_detalle = null;
        $user->id_ventanilla = null;
        $corredores_detalle->updated_us = null;
        $corredores_detalle->updated_at = null;
        $user->save();
        $corredores_detalle->save();
    }
    if ($request->id_servicio_ticket !== 114) {
      $user->id_sub_consultorio_cita_ticket = null;
      $user->save();
    }
    //=============================================================
    if (Auth::user()->flagestablecimientos == 1) :
      $usuario->id_establecimiento = $request->id_establecimiento;
      if($consulta_id_establecimiento == $usuario->id_establecimiento ){
        $detalleservicio = $detalleservicio ."";
      }else{
        $detalleservicio = $detalleservicio ."id_establecimiento ";
        $validaservicio = $validaservicio + 1;
        $validabitacora = $validabitacora + 1;
        $validabitacoraestablecimiento = 1;
      }
    endif;

    $usuario->flagestablecimientos = isset($request->flagestablecimientos) ? 1 : 0;
    if($consulta_flagestablecimientos == isset($request->flagestablecimientos) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."establecimientos ";
      $validaflag = $validaflag + 1;
    }

    $usuario->flagfarmacias = isset($request->flagfarmacias) ? 1 : 0;
    if($consulta_flagfarmacias == isset($request->flagfarmacias) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."farmacias ";
      $validaflag = $validaflag + 1;
    }

    $usuario->flagservicios = isset($request->flagservicios) ? 1 : 0;
    if($consulta_flagservicios == isset($request->flagservicios) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."servicios ";
      $validaflag = $validaflag + 1;
    }

    $usuario->flagresponsablefarmacia = isset($request->flagresponsablefarmacia) ? 1 : 0;
    if($consulta_flagresponsablefarmacia == isset($request->flagresponsablefarmacia) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."responsablefarmacia ";
      $validaflag = $validaflag + 1;
    }
/*----------------------------- REPORTE IMAGENOLOGÍA ---------------------------------------*/
    $usuario->flag_reporte_imagenes = isset($request->flag_reporte_imagenes) ? 1 : 0;
    if($consulta_flag_reporte_imagenes == isset($request->flag_reporte_imagenes) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."reporteimagenes ";
      $validaflag = $validaflag + 1;
    }
/*-------------------------------------------------------------------------------------------*/
/*----------------------------- FIRMAS PENDIENTES ---------------------------------------*/
    $usuario->flag_firmas_pendientes = isset($request->flag_firmas_pendientes) ? 1 : 0;
    if($consulta_flag_firmas_pendientes == isset($request->flag_firmas_pendientes) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."firmas_pendientes ";
      $validaflag = $validaflag + 1;
    }
/*-------------------------------------------------------------------------------------------*/
    $usuario->flagfiscalizador = isset($request->flagfiscalizador) ? 1 : 0;
    if($consulta_flagfiscalizador == isset($request->flagfiscalizador) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."fiscalizador ";
      $validaflag = $validaflag + 1;
    }

    $usuario->flaghistoria = isset($request->flaghistoria) ? 1 : 0;
    if($consulta_flaghistoria == isset($request->flaghistoria) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."historia ";
      $validaflag = $validaflag + 1;
    }

    $usuario->flagemergenciareferencia = isset($request->flagemergenciareferencia) ? 1 : 0;
    if($consulta_flagemergenciareferencia == isset($request->flagemergenciareferencia) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."emergencia referencia ";
      $validaflag = $validaflag + 1;
    }

    $usuario->flaghospitalizacion = isset($request->flaghospitalizacion) ? 1 : 0;
    if($consulta_flaghospitalizacion == isset($request->flaghospitalizacion) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."hospitalizacion ";
      $validaflag = $validaflag + 1;
    }

    $usuario->flagemergencia = isset($request->flagemergencia) ? 1 : 0;
    if($consulta_flagemergencia == isset($request->flagemergencia) ? 1 : 0){
      $detalle=$detalle ."";
    }else{
      $detalle=$detalle ."emergencia ";
      $validaflag = $validaflag + 1;
    }

    $usuario->flagllamadacita = isset($request->flagllamadacita) ? 1 : 0;
    if($consulta_flagllamadacita == isset($request->flagllamadacita) ? 1 : 0){
      $detalle = $detalle ."";
    }else{
      $detalle = $detalle ."llamadacita ";
      $validaflag = $validaflag + 1;
    }

    $usuario->id_user_update = $id_user;
    $usuario->usuario_actualizador = $id_user;
    $usuario->fecha_actualizacion = new Carbon();

    $usuario->flagmodulollamado = isset($request->flagmodulollamado) ? 1 : 0;
    if($consulta_flagmodulollamado == isset($request->flagmodulollamado) ? 1 : 0){
      $detalle = $detalle ."";
    }else{
      $detalle = $detalle ."modulollamado ";
      $validaflag = $validaflag + 1;
    }
    /* dd("RESPONSABLE: ". $request->responsable); */
    $usuario->responsable = isset($request->responsable) ? 1 : 0;
    if($consulta_responsable == isset($request->responsable) ? 1 : 0){
      $detalle = $detalle ."";
    }else{
      $detalle = $detalle ."responsablealmacen ";
      $validaflag = $validaflag + 1;
    }
    $datodetalle = "";

    $usuario->flag_editor_siteds = isset($request->flag_editor_siteds) ? 1 : 0;
    $usuario->flag_can = isset($request->flag_can) ? 1 : 0;
    $usuario->flag_consultor = isset($request->flag_consultor) ? $request->flag_consultor : 0;

    // FLAG RRHH
    $usuario->flag_rrhh = isset($request->flagRRHH) ? $request->flagRRHH : 0;

    $usuario->vistas_consultor = json_encode([
      "flag_citas_consultor" => isset($request->flag_citas_consultor) ? $request->flag_citas_consultor : 0,
      "flag_atenciones_consultor" => isset($request->flag_atenciones_consultor) ? $request->flag_atenciones_consultor : 0,
      "flag_procedimientos_consultor" => isset($request->flag_procedimientos_consultor) ? $request->flag_procedimientos_consultor : 0,
      "flag_recetas_consultor" => isset($request->flag_recetas_consultor) ? $request->flag_recetas_consultor : 0,
      "flag_examenes_consultor" => isset($request->flag_examenes_consultor) ? $request->flag_examenes_consultor : 0,

    ]);

    if($validaestado > 0){
        if($validaservicio > 0){
            if($validaflag > 0){
                $datodetalle = 9;
            }else{
                $datodetalle = 6;
            }
        }else{
            if($validaflag > 0){
                $datodetalle = 7;
            }else{
                $datodetalle = 3;
            }
        }
    }else{
        if($validaservicio > 0){
            if($validaflag > 0){
                $datodetalle = 8;
            }else{
                $datodetalle = 4;
            }
        }else{
            if($validaflag > 0){
              $datodetalle = 5;
            }else{
                $datodetalle = 10;
            }
        }
    }
   
    if($validabitacora >0){
   
      $model_bitacora = new Bitacora_edicione;
      $model_bitacora->id_user_editador = Auth::user()->id;
      $model_bitacora->id_user_modificado = $request->id;
      $model_bitacora->created_at = new Carbon();
      if($validabitacoraestablecimiento > 0){
        $model_bitacora->id_establecimiento_a = $consulta_id_establecimiento;
        $model_bitacora->id_establecimiento_d = $usuario->id_establecimiento;
      }
      if($validabitacoraservicio > 0){
        $model_bitacora->id_consultorio_a = $consulta_id_consultorio;
        $model_bitacora->id_consultorio_d = $usuario->id_consultorio;
      }
      if($validabitacorafarmacia > 0){
        $model_bitacora->id_farmacia_a = $consulta_id_farmacia;
        $model_bitacora->id_farmacia_d = $usuario->id_farmacia;
      }
      $model_bitacora->save(); 
    
    }
  
    $usuario->detalle_actualizacion = $datodetalle;
    $usuario->detalle_actualiza_flag = $detalle;
    $usuario->detalle_actualiza_servicio = $detalleservicio;

    /*$model_usuario = new User;
    $existe = $model_usuario->ExisteEmail($request->email, $request->id);
    if ($existe) {
      return redirect('/all_usuario')
        ->withInput()
        ->withErrors(array('No se ha podido actualizar, Email ya existe'));
    } else {
      $usuario->save(); 
      
      if (isset($request->responsable)) : //(isset($request->responsable) && $request->id_rol == "") 
        $flagalmacen = 1;
        $roles = new \App\Models\Assigned_role;
        $roles->user_id = $request->id;
        $roles->role_id = 3;
        $roles->save();
      elseif (!isset($request->responsable)) ://(!isset($request->responsable) && $request->id_rol > 0) 
        $delete = \App\Models\Assigned_role::where('user_id', '=', $request->id)->first();
        $delete->delete();
      endif;
      
    } */
    $model_usuario = new User;
    $existe = $model_usuario->ExisteEmail($request->email, $request->id);
    if ($existe) {
      return redirect('/all_usuario')
        ->withInput()
        ->withErrors(array('No se ha podido actualizar, Email ya existe'));
    } else {
      $usuario->save();
      if (isset($request->responsable) && $request->id_rol == "") :
        $roles = new \App\Models\Assigned_role;
        $roles->user_id = $request->id;
        $roles->role_id = 3;
        $roles->save();
      elseif (!isset($request->responsable) && $request->id_rol > 0) :
        $delete = \App\Models\Assigned_role::where('id', '=', $request->id_rol)->first();
        $delete->delete();
      endif;
    }

    $update_medico = Medico::where('dni', '=', $request->dni)->where('estado', '=', 1)->first();
    if($update_medico!=null):
            $update_medico->id_establecimiento = $request->id_establecimiento;
      $update_medico->save();
    endif;

    return redirect('/all_usuario')->withFlashSuccess("Se actualizo correctamente el Usuario");
  }

  public function editar_usuario($id_establecimiento, $id_farmacia)
  {
    //$usuario = new User;
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_bitacora = new Bitacora_edicione;
    $id_user = Auth::user()->id;
    $usuario = User::find($id_user);
    $comparaestablecimiento = $usuario->id_establecimiento;
    $comparafarmacia = $usuario->id_farmacia;
    if($comparaestablecimiento == $id_establecimiento){
      $usuario->detalle_actualiza_servicio = "id_farmacia";
    }else{
    $usuario->detalle_actualiza_servicio = "id_establecimiento id_farmacia";
        $model_bitacora->id_establecimiento_a = $comparaestablecimiento;
        $model_bitacora->id_establecimiento_d = $id_establecimiento;
     }
     $usuario->detalle_actualiza_flag = "";
     $usuario->detalle_actualizacion = 4;
    $usuario->fecha_actualizacion = new Carbon();
    $usuario->id_establecimiento = $id_establecimiento;
    $usuario->usuario_actualizador = $id_user;
    $usuario->id_farmacia = $id_farmacia;
    $usuario->save();

    $model_bitacora->id_user_editador = Auth::user()->id;
    $model_bitacora->id_user_modificado = Auth::user()->id;
    $model_bitacora->created_at = new Carbon();
    $model_bitacora->id_farmacia_a = $comparafarmacia;
    $model_bitacora->id_farmacia_d = $id_farmacia;
    $model_bitacora->save();

    $update_medico = Medico::where('dni', '=', $usuario->dni)->where('estado', '=', 1)->first();
    if($update_medico!=null):
            $update_medico->id_establecimiento = $id_establecimiento;
      $update_medico->save();
    endif;
    //return redirect('/all_usuario')->withFlashSuccess("Se actualizo correctamente el Usuario");
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

  public function validar_documento($nro_documento)
  {
    $WsReniec = new \App\Ws\Reniec;

    $datos_reniec = $WsReniec->BuscarDNI($nro_documento);
    return json_decode($datos_reniec, true);
  }
  public function get_sc_servicio_ticket($id_establecimiento,$id_servicio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_sub_consultorio = new SubConsultorio();
    $sc = $model_sub_consultorio->mostrarSubConsultoriosPorIdConsultorio($id_establecimiento,$id_servicio);
    return $sc;
  }

  public function datatable(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $user = Auth::user();
    $id_user = $user->id;

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);

    $query = User::select(
      'users.*',
      'establecimientos.nombre as establecimiento',
      'farmacias.nombre as farmacia',
      'consultorios.nombre as consultorio'
    )
    ->leftJoin('establecimientos', 'establecimientos.id', '=', 'users.id_establecimiento')
    ->leftJoin('farmacias', 'farmacias.id', '=', 'users.id_farmacia')
    ->leftJoin('consultorios', 'consultorios.id', '=', 'users.id_consultorio');

    if ($establecimientotot->flagestablecimientos == "1") {
      // no se filtra
    } elseif ($establecimientotot->flagfarmacias == "1") {
      $query->where('users.id_establecimiento', $establecimientotot->id_establecimiento);
    } else {
      $query->where('users.id_farmacia', $id_farmacia);
    }

    $recordsTotal = $query->count();

    if ($request->has('search')) {
      $search = $request->input('search.value');
      
      if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->whereRaw('LOWER(users.name) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(users.paterno) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(users.materno) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(users.dni) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhere('users.email', 'LIKE', '%'.$search.'%')
              ->orWhereRaw('LOWER(establecimientos.nombre) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(farmacias.nombre) LIKE ?', ['%' . strtolower($search) . '%'])
              ->orWhereRaw('LOWER(consultorios.nombre) LIKE ?', ['%' . strtolower($search) . '%']);
        });
      }
    }

    $recordsFiltered = $query->count();

    $usuarios = $query
      ->skip($request->start)
      ->take($request->length)
      ->get();

    $data = array();
    $n = $request->start + 1;

    $permiso_modificar = $request->input('permiso_modificar', 0);
  
    foreach ($usuarios as $usu) {
      switch ($usu->tipo_registro) {
        case '1': $tipo = 'PRESCRIPTOR MEDICO'; break;
        case '2': $tipo = 'DIGITADOR DE FARMACIA'; break;
        case '3': $tipo = 'DIGITADOR DE CONSULTORIO'; break;
        case '4': $tipo = 'DIGITADOR PRESCRIPTOR'; break;
        case '5': $tipo = 'DIGITADOR DE LABORATORIO'; break;
        case '6': $tipo = 'DIGITADOR DE RADIOLOGIA'; break;
        case '7': $tipo = 'PROFESIONAL DE IMAGENOLOGIA'; break;
        case '8': $tipo = 'PROFESIONAL DE ARCHIVO'; break;
        case '9': $tipo = 'PROFESIONAL DE TRIAJE'; break;
        case '10': $tipo = 'PROFESIONAL DE EJECUTORA 020'; break;
        case '11': $tipo = 'PROFESIONAL COMITE'; break;
        case '12': $tipo = 'PROFESIONAL SIGA'; break;
        default: $tipo = ''; break;
      }

      switch ($usu->status) {
          case '1': $estado = 'ACTIVO'; break;
          default: $estado = 'INACTIVO'; break;
      }
          
      $acciones = '';
      if ($permiso_modificar == 1) {
        $acciones =
          '<a class="btn btn-success btn-xs" href="/edit_usuario/'.$usu->id.'">
            <i class="fa fa-edit"></i>
          </a>
          <a class="btn btn-info btn-xs" href="/auth/password/actualizarClaveUsuario/'.$usu->id.'">
            <i class="fa fa-refresh"></i>
          </a>';
      }

      $data[] = array(
          $n++,
          $usu->paterno.' '.$usu->materno.', '.$usu->name,
          $usu->dni,
          $usu->email,
          $usu->establecimiento,
          $usu->farmacia,
          $usu->consultorio,
          $tipo,
          $estado,
          $acciones
      );
    }

    return response()->json([
      'draw' => intval($request->draw),
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    ]);
  }
}
