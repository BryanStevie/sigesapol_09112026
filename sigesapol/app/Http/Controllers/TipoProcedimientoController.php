<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tipo_procedimiento;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;


class TipoProcedimientoController extends Controller
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

        $model = new Tipo_procedimiento;
        $tipo_procedimientos = $model->ShowAll();
        $ruta = Route::getCurrentRoute()->getPath();
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.tipo_procedimientos.all', compact('tipo_procedimientos', 'permiso'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        return view('frontend.tipo_procedimientos.create');
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
        $nombre = trim($request->nombre);
        $tipo = new Tipo_procedimiento;
        $tipo->descripcion_tipo_procedimiento = trim($request->nombre);
        $tipo->estado = 1;
        $tipo->id_user_created = Auth::user()->id;

        $model_tipo = new Tipo_procedimiento;
        $existe = $model_tipo->ExisteTipoProcedimiento($nombre);
        if ($existe) {
            return redirect('/all_tipo_procedimiento')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Tipo de Procedimiento ya existe'));
        } else {
            $tipo->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_tipo_procedimiento');
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
    public function edit($id)
    {
        //
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $tipo_procedimiento = Tipo_procedimiento::findOrFail($id);
        return view('frontend.tipo_procedimientos.edit', compact('tipo_procedimiento'));
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
        //
        $nombre=trim($request->nombre);
        $update=Tipo_procedimiento::where('id','=',$request->id)->first();
        $update->descripcion_tipo_procedimiento=trim($request->nombre);
        $update->id_user_update = Auth::user()->id;
        
        $model_tipo=new Tipo_procedimiento;
        $existe = $model_tipo->ExisteTipoProcedimiento($nombre, $request->id);
        if ($existe) {
            return redirect('/all_tipo_procedimiento')
                            ->withInput()
                            ->withErrors(array('No se ha podido actualizar, Tipo de Procedimiento ya existe'));
        }else{
            $update->save();
            return redirect('/all_tipo_procedimiento')->withFlashSuccess("Se actualizo correctamente");
        }
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

    public function eliminar($id){
        $update=Tipo_procedimiento::where('id','=',$id)->first();
        $update->estado=3;
        $update->id_user_update = Auth::user()->id;
        $update->save();
        Session::flash('flash_message', 'Se elimino correctamente');
    }
}
