<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;

use App\Models\CqxFormatoEscaneado;
use App\Models\CqxFormatoTipo;
use App\Models\CqxFormato;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FormatoEscaneadoController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    }
  
    public function formDocumento(Request $request)
  {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }
    $tipo_formatos = CqxFormatoTipo::all();
    $data = $request->all();
    $asegurado = $data['asegurado'];
    return view('frontend.centro_quirurgico.formatos_escaneados.form_formatos_escaneados', compact('asegurado','tipo_formatos'));
  }
  public function subirDocumento(Request $request)
  {
    // Verifica si el archivo fue recibido
    if ($request->file('archivo')) {
        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $directorio = storage_path('app/formatos_escaneados');//CAMBIOS
        $tipoDocumento = $archivo->getClientMimeType();
        // Ruta destino completa en la carpeta 'public/formatos_escaneados'
        $rutaDestino = $directorio . '/' . $nombreArchivo;
        // Verifica si el directorio existe, si no, lo crea
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true); // Crea el directorio con permisos
        }

        try {
            // Mueve el archivo al directorio en 'public/formatos_escaneados'
            $archivo->move($directorio, $nombreArchivo);
            // Si el archivo se movió correctamente, obtenemos su tamaño
            if (file_exists($rutaDestino)) {
                $tamanoArchivo = filesize($rutaDestino); // Obtiene el tamaño del archivo
                // Crear y guardar el registro en la base de datos
                $documento = new CqxFormatoEscaneado();
                $documento->id_asegurado = $request->id_asegurado;
                $documento->id_intervencion = $request->id_intervencion;
                $documento->id_tipo_formato = $request->tipo_formato;
                $documento->tipo_documento = $tipoDocumento;
                $documento->nombre_archivo = $nombreArchivo;
                $documento->ruta = 'formatos_escaneados/'; // Ruta relativa
                $documento->tamano_archivo = $tamanoArchivo;
                $documento->created_us = auth()->user() ? auth()->user()->id : null;
                $documento->fecha_creacion = Carbon::now()->format('Y-m-d');
                $documento->hora_creacion = Carbon::now()->format('H:i:s');
                $documento->estado = 1;
                $documento->save();
                return response()->json(['success' => true, 'message' => 'Archivo subido exitosamente']);
            }
        } catch (\Exception $e) {
            Log::error('Error al subir el documento: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'No se pudo subir el archivo'], 500);
        }
    }

    return response()->json(['success' => false, 'message' => 'No se pudo subir el archivo'], 500);
  }
  
  public function obtenerFormatosSubidos(Request $request){
    try {
     
      $id_intervencion = $request->input('id_intervencion');
      $documentos = CqxFormatoEscaneado::with('tipoFormato') // Cargar la relación tipoFormato
      ->where('id_intervencion', $id_intervencion)
      ->get();

      return response()->json([
          'success' => true,
          'data' => $documentos
      ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al obtener los documentos']);
    }
  }
  public function formatos(){
    $formatos = CqxFormato::select('id','nombre_archivo','ruta')->where('estado',1)->get();
    try {
      $formatos = CqxFormato::select('id','nombre_archivo','ruta')->where('estado',1)->get();
      return response()->json([
          'success' => true,
          'data' => $formatos
      ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al obtener los documentos']);
    }
  }
  public function formato_view($nombre_archivo)
  {
    if (!Auth::check()) {
      return response()->json(['error' => 'Acceso no autorizado.'], 403);
    }
    $fullPath = storage_path('app/formatos/' . $nombre_archivo);
    if (!file_exists($fullPath)) {
        return response()->json(['error' => 'Archivo no encontrado.'], 404);
    }
    $fileContent = file_get_contents($fullPath);
    return response($fileContent, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', "inline; filename=${nombre_archivo}.pdf");
  }
  public function formato_escaneado_view($nombre_archivo)
  {
    if (!Auth::check()) {
      return response()->json(['error' => 'Acceso no autorizado.'], 403);
    }
    $fullPath = storage_path('app/formatos_escaneados/' . $nombre_archivo);
    if (!file_exists($fullPath)) {
        return response()->json(['error' => 'Archivo no encontrado.'], 404);
    }
    $fileContent = file_get_contents($fullPath);
    return response($fileContent, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', "inline; filename=${nombre_archivo}.pdf");
  }

  public function subirDocumento_archivo(Request $request)
  {
    if ($request->file('archivo')) {
        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $directorio = storage_path('app/formatos_escaneados');
        $tipoDocumento = $archivo->getClientMimeType();
        $flag_origen_escaneado = $request->flag_origen_escaneado;
        $rutaDestino = $directorio . '/' . $nombreArchivo;
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }
        try {
            $archivo->move($directorio, $nombreArchivo);
            if (file_exists($rutaDestino)) {
                $tamanoArchivo = filesize($rutaDestino);
                $documento = new CqxFormatoEscaneado();
                $documento->id_asegurado = $request->id_asegurado;
                $documento->id_intervencion = $request->id_intervencion;
                $documento->id_tipo_formato = $request->tipo_formato;
                $documento->tipo_documento = $tipoDocumento;
                $documento->nombre_archivo = $nombreArchivo;
                $documento->ruta = 'formatos_escaneados/'; 
                $documento->tamano_archivo = $tamanoArchivo;
                $documento->created_us = auth()->user() ? auth()->user()->id : null;
                $documento->fecha_creacion = Carbon::now()->format('Y-m-d');
                $documento->hora_creacion = Carbon::now()->format('H:i:s');
                $documento->nombre_simple = $archivo->getClientOriginalName();
                $documento->estado = 1;
                $documento->flag_origen_escaneado = $flag_origen_escaneado;
                $documento->save();
                return response()->json(['success' => true, 'message' => 'Archivo subido exitosamente']);
            }
        } catch (\Exception $e) {
            Log::error('Error al subir el documento: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'No se pudo subir el archivo'], 500);
        }
    }
    return response()->json(['success' => false, 'message' => 'No se pudo subir el archivo'], 500);
  }

  public function ver_formatos_escaneado_tele_salud($idIntervencion)
{

    $formatos_escaneados = DB::select("SELECT * from cqx_formato_escaneados WHERE id_intervencion = ? AND flag_origen_escaneado = '4'", [$idIntervencion]);

    return response()->json([
        'formatos_escaneados' => $formatos_escaneados
    ]);
}
}