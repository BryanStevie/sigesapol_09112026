<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Carga_inicial_stock;
use App\Models\Farmacia;
use App\Models\Importacione;
use Carbon\Carbon;
use DB;
use Validator;
use Session;
use Auth;

class ImportacionesController extends Controller {

    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function indexmedicos($id_user) {
        $modelfarmacia = new Farmacia;
        $modelImportaciones= new Importacione;
        $usuarios = $id_user;
        $establecimiento = $modelfarmacia->GetIpressByIduser($id_user);
        $id_establecimiento=$establecimiento->id;
        $importaciones=$modelImportaciones->ShowAllByIdEstablecimiento($id_establecimiento,'medicos');
        return view('frontend.medicos.importaciones', compact('usuarios', 'establecimiento','importaciones'));
    }
    
    public function formulario_importar_stock($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelfarmacia = new Farmacia;
        $modelImportaciones = new Importacione;
        $usuarios = $id_user;
        $establecimiento = $modelfarmacia->GetIpressByIduser($id_user);
        $id_establecimiento = $establecimiento->id;
        
		//$importaciones = $modelImportaciones->ShowAllByIdEstablecimiento($id_establecimiento, 'stock');
		$importaciones = $modelImportaciones->ShowAllByIdFarmacia($id_farmacia, 'stock');
        //$importacionesvalid = $modelImportaciones->GetValidByIdFarmaciaPendiente($id_farmacia);
        $model_cargainicial = new Carga_inicial_stock();
        $cargainicial = $model_cargainicial->GetCargaRegistradaByFarmaciaFinalizados($id_farmacia);
        $cargainicialpen = $model_cargainicial->GetCargaRegistradaByFarmacia($id_farmacia);
		//$cargainicialval = $model_cargainicial->GetCargaRegistradaByFarmaciaAndEstado($id_farmacia,5);
		$importacionesvalid = $modelImportaciones->GetValidByIdFarmaciaAndEstado($id_farmacia,0);
		$cargainicialact = $model_cargainicial->GetCargaRegistradaByFarmacia($id_farmacia,1);
		
		//if(count($cargainicial)==0)return redirect('/formulario_importar_stock/' . $id_user);
		if(count($cargainicialact)<>0)return redirect('/from_registrar_cabecera_carga_inicial/' . $id_farmacia);
		
		
        return view('frontend.stocks.formulario_importar_stock', compact('usuarios', 'establecimiento', 'importaciones', 'cargainicial', 'cargainicialpen','cargainicialact','importacionesvalid'));
    }

    public function subir_stock_farmacias(Request $request){
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $validator = Validator::make($request->all(), ['url_data' => 'required',]);
        if ($validator->fails()) {
            return redirect('/formulario_importar_stock/' . $request->id_user)->withInput()->withErrors($validator);
        }     
        
        $id_farmacia = Auth::user()->id_farmacia;
        
        $importacion = new Importacione;
        $importacion->id_user = $request->id_user;
        $importacion->id_establecimiento = $request->id_establecimiento;
        $importacion->tabla = 'stock';
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y-His');
        //----------------------------------------------------------------------        
        $file_url_data = $request->file('url_data');
        $url_data = $file_url_data->getClientOriginalName();
        $nombre_url_data =$fecha."_" .$url_data;
        \Storage::disk('local')->put($nombre_url_data, \File::get($file_url_data));
        //----------------------------------------------------------------------
		
        $importacion->url_data = $nombre_url_data;
        $importacion->estado = 0;
        $importacion->id_farmacia = $id_farmacia;
        $importacion->save();
		
		
        Session::flash('flash_message', 'Registro exitoso');
        return redirect('/formulario_importar_stock/' . $request->id_user );
    }

    public function subir_data_medicos(Request $request) {
        $importacion_medico = new Importacione;
        $importacion_medico->id_user = $request->id_user;
        $importacion_medico->id_establecimiento = $request->id_establecimiento;
        $importacion_medico->tabla = 'medicos';
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //----------------------------------------------------------------------        
        $file_url_data = $request->file('url_data');
        $url_data = $file_url_data->getClientOriginalName();
        //value($url_data); exit;
        $nombre_url_data =$fecha."_" .$url_data;
        \Storage::disk('local')->put($nombre_url_data, \File::get($file_url_data));
        //----------------------------------------------------------------------
        $importacion_medico->url_data = $nombre_url_data;
        $importacion_medico->estado = 0;
        $importacion_medico->save();
        Session::flash('flash_message', 'Registro exitoso');
        return redirect('/importaciones_medicos/' . $request->id_user );
    }
}
