<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClaseProgramacion;
use App\Models\TipoProgramacion;
use App\Http\Requests;
use Illuminate\Http\JsonResponse;
use DB;

class UbicacionServicioController extends Controller
{
    public function index()
    { 
        $clases = ClaseProgramacion::orderBy('estado', 'desc')
                          ->orderBy('nombre_clase', 'asc')
                          ->get();
        return view('frontend.ubicacion_servicio.index', compact('clases'));
    }

  

    public function storeClase(Request $request)
    {
        $claseExistente = ClaseProgramacion::where('nombre_clase', $request->nombre_clase)->first();

    if ($claseExistente) {
        if ($claseExistente->estado == 0) {
            
            return redirect()->back()
                ->with('warning', 'La clase ya existe pero está inactiva. ¿Desea activarla?')
                ->with('claseInactivaId', $claseExistente->id_clase); // Para usar en JavaScript
        } else {
            
            return redirect()->back()
                ->with('error', 'La clase ya existe y está activa.')
                ->withInput();
        }
    }

        ClaseProgramacion::create([
            'nombre_clase' => $request->nombre_clase,
            'estado' => 1
        ]);

        return redirect()->back()->with('success', 'Clase registrada correctamente.');
    }

    public function storeTipo(Request $request)
    {
        TipoProgramacion::create([
            'id_clase' => $request->id_clase,
            'nombre_tipo' => $request->nombre_tipo,
            'estado' => 1
        ]);

        return redirect()->back()->with('success', 'Tipo registrado correctamente.');
    }

    public function eliminarTipo($id)
    {
        $tipo = TipoProgramacion::find($id);
        if ($tipo) {
             if($tipo->estado == 1) {
                $tipo->estado = 0;
             }else{
                $tipo->estado = 1;
             }
            $tipo->save();
        }
        return response()->json(['success' => true]);
    }

    public function eliminarClase($id)
    {
        $clase = ClaseProgramacion::find($id);
        if ($clase) {
            if($clase->estado == 1) {
                $clase->estado = 0;
            }else{
                $clase->estado = 1;
            }
            
            $clase->save();
        }
        return response()->json(['success' => true]);
    }

    public function updateClase(Request $request)
{
    $clase = ClaseProgramacion::find($request->id_clase);
    if ($clase) {
        $clase->nombre_clase = $request->nombre_clase;
        $clase->save();
    }
    return redirect()->back()->with('success', 'Clase actualizada correctamente.');
}

public function updateTipo(Request $request)
{
    $tipo = TipoProgramacion::find($request->id_tipo);
    if ($tipo) {
        $tipo->nombre_tipo = $request->nombre_tipo;
        $tipo->id_clase = $request->id_clase;
        $tipo->save();
    }
    return redirect()->back()->with('success', 'Tipo actualizado correctamente.');
}

public function listarTodosTipos()
{
    $tipos = TipoProgramacion::with('clase')
                  ->orderBy('estado', 'desc')
                          ->orderBy('nombre_tipo', 'asc')
                    ->get();
    return response()->json($tipos);
}


  public function listarTipos($id_clase)
    {
        $tipos = TipoProgramacion::with('clase')->where('id_clase', $id_clase)
                    ->orderBy('estado', 'desc')
                          ->orderBy('nombre_tipo', 'asc')
                    ->get();
        return response()->json($tipos);
    }
}
