<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Unidade;
use Illuminate\Support\Facades\Route;
use Auth;

class UnidadController extends Controller
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
        
		if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
		
        $model=new Unidade;
        $unidades=$model->ShowAll();
		
		$ruta = Route::getCurrentRoute()->getPath();
		if(count($ruta=explode("/",$ruta)) > 1);$ruta=$ruta[0];
		$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id,$ruta);
		$permiso = $menu[0];
		if($permiso->lectura == "")return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.unidades.all', ['unidades' => $unidades, 'permiso'=>$permiso]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('frontend.unidades.create');
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
        $unidad= new Unidade;
        $unidad->abreviatura=$request->abreviatura;
        $unidad->nombre=$request->nombre;
        $unidad->estado="1";
        $abrev=$request->abreviatura;
        $existe = $unidad->ExisteUnidad($abrev, 0);
        if ($existe) {
            return redirect('/create_unidad')
                            ->withInput()
                            ->withErrors(array('La Unidad digitada ya existe'));
        } 
        $unidad->save();
        return redirect('/all_unidades')->withFlashSuccess("Se registro correctamente la unidad");
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
        $unidades = Unidade::findOrFail($id);
        return view('frontend.unidades.edit', ['unidades' => $unidades]);
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
        $abrev_actual=$request->abrev_actual;
        $abrev_nueva=$request->abreviatura;
        $unidades = Unidade::find($request->id);
        $unidades->abreviatura=$request->abreviatura;
        $unidades->nombre=$request->nombre;
        $unidades->estado="1";
        if($abrev_nueva==$abrev_actual){
            $unidades->save();            
        }else{
            $existe = $unidades->ExisteUnidad($abrev_nueva, 0);
            if ($existe) {
                return redirect('/all_unidades')
                                ->withInput()
                                ->withErrors(array('La unidad digitada ya existe'));
            }
            $unidades->save();
        }
        return redirect('/all_unidades')->withFlashSuccess("Se actualizo correctamente la unidad");
                
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
