<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Punto;
use Auth;

class PlanoController extends Controller
{   
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function agregarPunto(Request $request){
        $punto = new Punto;
        $punto->x = $request->x;
        $punto->y = $request->y;
        $punto->color = $request->color;
        if($request->tamanio_proceso==10){
            $punto->x = ($request->x-3);
            $punto->y = ($request->y-3);
        }elseif($request->tamanio_proceso==40){
            $punto->x = ($request->x-18);
            $punto->y = ($request->y-18);
        }elseif($request->tamanio_proceso==60){
            $punto->x = ($request->x-30);
            $punto->y = ($request->y-30);
        }
        $punto->tama = $request->tamanio_proceso;
        $punto->id_usuario =  Auth::user()->id; 
        $punto->id_enfermeria = $request->id_enfermeria; // Si $request->id_atencion está vacío, asigna null
        $punto->id_tipo_atenciones = $request->id_tipo_atenciones ; // Si $request->id_tipo_atenciones está vacío, asigna null
        $punto->descripcion_proceso = $request->descripcion_proceso;
        $punto->tipo_registro = $request->tipo_registro;
        // echo "<pre>"; 
        // var_dump( $punto);
        // echo "</pre>";
        $punto->save();
        return json_encode(1);
        
    }

    public function pintarPuntos($id_atencion_admision, $tipo_registro, $id_tipo_atenciones){ 
        $punto = new Punto;
        $puntos = $punto->getPuntosAdmision($id_atencion_admision, $tipo_registro, $id_tipo_atenciones);
        return json_encode($puntos); 
    }
 
    public function destroyPuntos($punto_id){
        $punto = Punto::find($punto_id); 
        $punto->delete();
        return json_encode(1);
    }

}