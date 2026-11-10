<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Contador_receta;
use Carbon\Carbon;
use DB;
use Session;
use App;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Beneficiario;
use App\Models\Medico;
use App\Models\Medico_consultorio;
use App\Models\Prestaciones;
use App\Models\SubConsultorio;
use App\Models\Cita;
use App\Models\Asegurado;
use App\Models\Asegurado_historia;
use SoapClient;
use Mail;
class RecetaValeController extends Controller {
	public function obtener_beneficiario($id_tiporeceta, $tipodoc_beneficiario, $dni_beneficiario) {		
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;		
		$model_hospitalizacion = new \App\Models\Hospitalizacione;
		$array = array();
		$id_farmacia = Auth::user()->id_farmacia;
		$numero_historia = "";
        $domicilio = "";
        $telefono = "";
		$beneficiario = "";
        $correo = "";
		$msg = "";
		$sw = false;
		$establecimiento = "";
		$cita = "";
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $id_establecimiento = $farma->id_establecimiento;		
		/******************Esto llama a la WebServices **********************************/
        $client = new SoapClient("http://dev-wsdl.saludpol.gob.pe:8083/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => 'D5p-R4v-@cci.ws.sp', 'password' => "wS5p#cC1#D5p-"));        
        /******************* Aqui es donde se conecta a la funcion y devuelve el valor *******************/
		$beneficiario = $client->getAseguradoValidate($tipodoc_beneficiario, $dni_beneficiario);
    	/***************Programacion para saber si es beneficiario o no *********************************/
    			foreach($beneficiario as $key=>$ben)
                    if(!is_object($ben))
                            $beneficiario->$key = utf8_decode($ben);
    			$model_establecimiento = new \App\Models\Establecimiento;
            	if(isset($beneficiario->ubigeo))$establecimiento = $model_establecimiento->GetEstablecimientoUbigeosByUbigeo($beneficiario->ubigeo);
                
    			if (is_object($beneficiario)):
                    $sw = true;
                    $model_asegurado = new \App\Models\Asegurado;
                    $existe_datos_asegurado=$model_asegurado->busca_datos_asegurados($dni_beneficiario, $id_establecimiento);

                    if (count($existe_datos_asegurado) == 0):
                        $existe_nro_historia_receta_vale=$model_asegurado->existe_nro_historia_receta_vale($dni_beneficiario, $id_establecimiento);

                        if (count($existe_nro_historia_receta_vale) > 0):
                				$numero_historia = $existe_nro_historia_receta_vale;
            			endif;
                    else:
                            $numero_historia = $existe_datos_asegurado[0]->nro_historia;
                            $domicilio = $existe_datos_asegurado[0]->domicilio;
                            $telefono = $existe_datos_asegurado[0]->telefono;
                            $correo = $existe_datos_asegurado[0]->correo;
                    endif;
    			else:
    				$msg = "No se encuentra afiliado..!";
    			endif;
    			$model_cita = new \App\Models\Cita;
            	$cita = $model_cita->GetCitaByAseguradoAndEstablecimiento($tipodoc_beneficiario, $dni_beneficiario,76);
        $array["sw"] = $sw;
		$array["msg"] = $msg;
		$array["beneficiario"] = $beneficiario;
		$array["numero_historia"] = $numero_historia;
        $array["domicilio"] = $domicilio;
        $array["telefono"] = $telefono;
        $array["correo"] = $correo;
		$array["establecimiento"] = $establecimiento;
		$array["cita"] = $cita;
        echo json_encode($array);
    }
}
