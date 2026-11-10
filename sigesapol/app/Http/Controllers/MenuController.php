<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\Menu;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class MenuController extends Controller
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
  
  public function index(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $model = new \App\Models\Menu;
    //$consultorios=$model->ShowConsultoriosAll();
    //$ruta = Route::getCurrentRoute()->getPath();
    $tipo = $request->id_tipo;
    //echo $tipo;exit();
    $tipos = $model->ShowAllMenuPrincipal();
    $menus = $model->ShowAllMenu($tipo);
    //$permiso = $menu[0];

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "") return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    return view('frontend.menus.all', compact('menus', 'tipos', 'permiso'));
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

    //$model= new Establecimiento();
    //$establecimientos=$model->ShowAll();
    $model_menu = new \App\Models\Menu;
    $menus = $model_menu->ShowAllMenuPrincipal();
    //return view('frontend.consultorios.create');
    return view('frontend.menus.create', compact('menus'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $menu = new Menu;
    $menu->nombremenu = $request->nombremenu;
    $menu->parentid = $request->id_menu;
    //if($request->id_menu==0):
      $menu->url = '/' . $request->url;
    //else:
    //  $menu->url = $request->url;
    //endif;
    $menu->flagregistro = 1;
    $menu->icono = 'fa fa-home';
    $menu->orden = $request->orden;
    $menu->save();
    Session::flash('flash_message', 'Registro exitoso');
    return redirect('/all_menu');
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
      if (!Auth::check()) {
          return redirect('/auth/login');
      }
  
      $model_menu = new \App\Models\Menu;
      $menus = $model_menu->ShowAllMenuPrincipal();
      $menu = Menu::findOrFail($id);
  
      // Convertir el array de submen�s en una colecci�n y aplicar el filtro para evitar que se seleccione el mismo menu que sera editado
      $menus_sub = collect($model_menu->ShowAllSub_menus())->filter(function ($sub) use ($id) {
          return $sub->id != $id;

      });
  
      return view('frontend.menus.edit', compact('menu', 'menus', 'menus_sub'));
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
      // Find the menu item by its ID
      $update = Menu::where('id', '=', $request->id)->first();
  
      // Check if a submenu is selected and update the parentid accordingly
      if ($request->id_submenu != 0) {
          // Update the parentid with the submenu ID
          $update->parentid = $request->id_submenu;
      } else if ($request->id_menu != 0) {
          // Update the parentid with the menu principal ID if no submenu is selected
          $update->parentid = $request->id_menu;
      }
  
      // Update other fields
      $update->nombremenu = $request->nombremenu;
      $update->orden = $request->orden;
  
      // Save the updates
      $update->save();
  
      // Return to the menu list with a success message
      return redirect('/all_menu')->withFlashSuccess("Se actualiz� correctamente");
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
}
