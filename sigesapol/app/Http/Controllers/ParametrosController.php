<?php

namespace App\Http\Controllers;

use App\Models\Parametros;
use App\Models\Tipo_sangre;
use App\Models\Tipo_religiones;
use App\Models\Tipo_ocupaciones;
use App\Models\Tipo_parentescos;
use App\Models\Tipo_seguros;
use App\Models\Parametros_detalle;
use App\Models\Farmacia;
use App\Models\Consultorio;
use App\Models\Distrito;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Auth;

class ParametrosController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    } 
    
  public function listar_parametros_by_tipo($descrip_tipo_parametro)
  {
      $parametro = new Parametros;
      $parametros = $parametro->getParametros_by_tipo($descrip_tipo_parametro);
      return json_encode($parametros);
  }
  public function listar_parametros_by_tipo_de_sangre()
  {
      $parametro = new Tipo_sangre;
      $parametros = $parametro->ShowAll();
      return json_encode($parametros);
  }
  public function listar_parametros_by_listar_etnias()
  {
      $parametro = new Parametros;
      $parametros = $parametro->getTipo_etnias();
      return json_encode($parametros);
  }
  public function listar_parametros_by_listar_idiomas()
  {
      $parametro = new Parametros;
      $parametros = $parametro->getTipo_idiomas();
      return json_encode($parametros);
  }
  public function listar_parametros_by_listar_estado_civil()
  {
      $parametro = new Parametros;
      $parametros = $parametro->getTipo_estado_civil();
      return json_encode($parametros);
  }
  public function listar_parametros_by_listar_religiones()
  {
      $parametro = new Tipo_religiones;
      $parametros = $parametro->getTipo_religiones();
      return json_encode($parametros);
  }
  public function listar_parametros_by_listar_grados_instruccion()
  {
      $parametro = new Parametros;
      $parametros = $parametro->getTipo_grados_instruccion();
      return json_encode($parametros);
  }
  public function listar_parametros_by_listar_ocupaciones()
  {
      $parametro = new Tipo_ocupaciones ;
      $parametros = $parametro->getTipo_ocupaciones();
      return json_encode($parametros);
  }
  public function listar_parametros_by_listar_parentescos()
  {
      $parametro = new Tipo_parentescos ;
      $parametros = $parametro->getTipo_parentescos();
      return json_encode($parametros);
  }
    public function listar_parametros_by_id($id_parametro)
    {
        $parametro_detalle = new Parametros;
        $parametros_detalle = $parametro_detalle->getParametros_detalle_by_id($id_parametro);
        return json_encode($parametros_detalle);
    }
    public function listar_parametros_by_tipo_autocomplete($buscar, $descrip_tipo_parametro)
    {
        $parametro = new Parametros;
        $parametros = $parametro->getParametros_by_tipo_autocomplete($buscar, $descrip_tipo_parametro);
        $array = array();
        foreach ($parametros as $leer) {
          $array[] = array(
            "value" => $leer->descrip_corta,
            "label" => $leer->descrip_corta,
            "id" => $leer->id,
          );
        }
    
        return json_encode($array);
    } 
    public function listar_personal_convocado_by_medico($buscar)
    {
        $medico = new Parametros;
        $medicos = $medico->getPersonal_convocado_by_medico_autocomplete($buscar,Auth::user()->id_establecimiento);
       // dd($medicos);
        $array = array();
        foreach ($medicos as $leer) {
          $array[] = array( 
            "value" => $leer->dni . " - " . $leer->paterno ." ". $leer->materno." " .$leer->nombre,
            "label" => $leer->dni . " - " . $leer->paterno ." ". $leer->materno." " .$leer->nombre,
            "id" => $leer->id,
          );
        }
    
        return json_encode($array);
    }
    public function listar_profesional_by_dni_autocomplete($buscar)
    {
        $medico = new Parametros;
        $medicos = $medico->getPersonal_convocado_by_medico_autocomplete($buscar,Auth::user()->id_establecimiento);
        $array = array();
        foreach ($medicos as $leer) {
          $array[] = array( 
            "value" => $leer->dni . " - " . $leer->paterno ." ". $leer->materno." " .$leer->nombre,
            "label" => $leer->dni . " - " . $leer->paterno ." ". $leer->materno." " .$leer->nombre,
            "id" => $leer->id,
          );
        }
    
        return json_encode($array);
    }
    public function listar_especialidades_not_enfer()
    {
        $modelFarmacia = new Farmacia();
        $farma = $modelFarmacia->GetFarmaByUser(Auth::user()->id); 
        $model_consultorio = new Consultorio;
		    $consultorios = $model_consultorio->ShowConsultorios($farma->id); 
        return json_encode($consultorios);
    }
    public function listar_profesional_autocomplete_by_dni($buscar)
    {
        $medico = new Parametros;
        $medicos = $medico->getProfesional_autocomplete_by_dni($buscar);
        $array = array();
        foreach ($medicos as $leer) {
          $array[] = array( 
            "value" => ' ',
            "label" => $leer->dni  ." - ".$leer->paterno ." ". $leer->materno." " .$leer->nombre,
            "dni" => $leer->dni ,
            "profesional" => $leer->paterno ." ". $leer->materno." " .$leer->nombre,
            "paterno" => $leer->paterno ,
            "materno" => $leer->materno ,
            "nombre" => $leer->nombre ,
            "especializacion" => $leer->especializacion ,
            "colegiatura" => $leer->colegiatura ,
            "tipo_documento" => 'DNI' ,
            "rne" => $leer->rne ,
            "id" => $leer->id,
            "profesion" => $leer->profesion ,
          );
        }
    
        return json_encode($array);
    }

     

    
    public function listar_departamentos()
    {
        $departamento = new Distrito;
        $departamentos = $departamento->ShowDepartamento();
        return json_encode($departamentos);
    }
    public function listar_provincias_by_departamento($id_departamento)
    {
        $provincia = new Distrito;
        $provincias = $provincia->ShowProvincia($id_departamento);
        return json_encode($provincias);
    }
    public function listar_distritos_by_provincia($id_provincia)
    {
        $distrito = new Distrito;
        $distritos = $distrito->ShowDistrito($id_provincia);
        return json_encode($distritos);
    }
    public function listar_profesional_autocomplete_by_ubigeo($buscar_ubigeo)
    {
        $medico = new Parametros;
        $medicos = $medico->getUbigeo_autocomplete($buscar_ubigeo);
        $array = array();
        foreach ($medicos as $leer) {
          $array[] = array( 
            "value" => $leer->codigo_ubigeo,
            "label" => $leer->descripcion_distrito .' | '.$leer->descripcion_provincia  .' | '.$leer->descripcion_departamento ." (".$leer->codigo_ubigeo .")",
            "codigo_ubigeo" => $leer->codigo_ubigeo , 
            "id_departamento" => $leer->id_departamento ,
            "descripcion_departamento" => $leer->descripcion_departamento ,
            "id_provincia" => $leer->id_provincia ,
            "descripcion_provincia" => $leer->descripcion_provincia ,
            "id_distrito" => $leer->id_distrito ,
            "descripcion_distrito" =>  $leer->descripcion_distrito , 
          );
        }
    
        return json_encode($array);
    }
    public function listar_profesional_autocomplete_by_ipress_origen($buscar_ipress_origen, $institucion)
    {
        $medico = new Parametros;
        $medicos = $medico->getIpress_origen_autocomplete($buscar_ipress_origen, $institucion);
        $array = array();
        foreach ($medicos as $leer) {
          $array[] = array( 
            "value" => $leer->establecimiento,
            "label" => $leer->establecimiento ,
            "id" => $leer->id , 
            "establecimiento" => $leer->establecimiento 
          );
        }
    
        return json_encode($array);
    }
   
    public function datos_adicionales_paciente_by_dni($dni,$tipo_documento)
    {
      $paciente = new Paciente; 
      $data['data'] = $paciente->GetAseguradoByDNI($dni, $tipo_documento); 
      return json_encode($data);
    }
   
    public function validar_existencia_ubigeo_by_codigo(Request $request)
    {
      $codigo_ubige = new Parametros;
      $data = $codigo_ubige->getValidar_existencia_ubigeo_by_codigo($request->codigo_ubigeo); 
      return json_encode($data);
    }
    public function listar_tipo_seguros()
    {
        $tipo_seguro = new Tipo_seguros ;
        $datos_tipo_seguro = $tipo_seguro->ShowAll();
        return json_encode($datos_tipo_seguro);
    }
    
    public function listar_profesional_autocomplete_by_codigo_origen($buscar_codigo_origen, $institucion)
    {
        $ipre = new Parametros;
        $ipress = $ipre->getIpress_origen_by_codigo($buscar_codigo_origen, $institucion);  
        return json_encode($ipress);
    }
}
