<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Session;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller {

	/**
	 * @return \Illuminate\View\View
	 */
	public function index($sistema="")
	{
		javascript()->put([
			'test' => 'it works!'
		]);
		
		/*
		if($sistema=="admision" || $sistema=="programacion" || $sistema=="farmacia"):
			$nombreSistema = "Gesti&oacute;n de Farmacias";
			if(isset($sistema) && $sistema == "admision")$nombreSistema = "Gesti&oacute;n de Admisi&oacute;n";
			if(isset($sistema) && $sistema == "programacion")$nombreSistema = "Gesti&oacute;n de Programaci&oacute;n Medico";
			if(isset($sistema) && $sistema == "farmacia")$nombreSistema = "Gesti&oacute;n de Farmacias";
			Session::put('nombreSistema', $nombreSistema);
		endif;
		*/
		//return view('frontend.index');
    return redirect('/auth/login');
	}
	
	public function index2($sistema="")
	{
		javascript()->put([
			'test' => 'it works!'
		]);
		
		
		if($sistema=="admision" || $sistema=="programacion" || $sistema=="farmacia"):
			$nombreSistema = "Gesti&oacute;n de Farmacias";
			if(isset($sistema) && $sistema == "admision")$nombreSistema = "Gesti&oacute;n de Admisi&oacute;n";
			if(isset($sistema) && $sistema == "programacion")$nombreSistema = "Gesti&oacute;n de Programaci&oacute;n Medico";
			if(isset($sistema) && $sistema == "farmacia")$nombreSistema = "Gesti&oacute;n de Farmacias";
			Session::put('nombreSistema', $nombreSistema);
		endif;
		
		//return view('frontend.index');
    return redirect('/auth/login');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function macros()
	{
		return view('frontend.macros');
	}
}