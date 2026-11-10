<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establecimiento;
use App\Models\TipoAtencione;
use App\Models\AtencionEstrategiaSanitaria;
use App\Models\AtencionEstrategiaDiagnostico;
use App\Models\AtencionEstrategiaActividad;
use App\Models\Medico;
use App\Models\User;
use App\Models\Asegurado;
use App\Models\Tipo_documento_identidad;
use Session;
use DB;
use App;
use Auth;
use Response;

class AtencionEstrategiaSanitariaController extends Controller
{
    public function index()
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $id_establecimiento = Auth::user()->id_establecimiento;
        $dni=Auth::user()->dni;
        //$medicos=new Medico();
        $model_user = new User();
        $model_medicos = new Medico();
        //Comentar 
        //$dni="44093614";
        //$medico = $medicos->GetMedicoColegioByDNI($dni);
        $user = $model_user->GetById(Auth::user()->id);
        $medico = $model_medicos->GetMedicoColegioByDNI($user->dni);
        if($medico!=null){
            $medico->id = Auth::user()->id;
        }
        else{
            $medico = $user;
            $medico->colegio_descripcion = "";
            $medico->colegiatura = "";
            $medico->rne = "";
            
        }
        
        if (!$medico) {
           // return redirect('/')->withFlashError("No se encontró el médico asociado a su usuario.");
        }


        $model_establecimiento= new Establecimiento();
        $establecimientos=$model_establecimiento->GetById($id_establecimiento);
        $ugipress=$model_establecimiento->GetById(78);

        $tipoDocumento = new Tipo_documento_identidad();
        $tipos_documento = $tipoDocumento->ShowTipoDocumentoIdentidad();    

        $tiposAtencion = TipoAtencione::where('id', 25) // Filtra por estrategia
            ->orderBy('nombre', 'asc')
            ->get();

        return view(
            'frontend.atencion_estrategia_sanitaria.index',
            compact('establecimientos', 'ugipress', 'id_establecimiento', 'tiposAtencion', 'medico', 'tipos_documento')
        );
    }

    public function listar(Request $request)
    {
        $user = Auth::user();

        $medico = User::where('dni', $user->dni)
            ->where('id_establecimiento', $user->id_establecimiento)
            ->first();

        if (!$medico) {
            return back()->with('error', 'No se encontró un médico asociado a este usuario y establecimiento.');
        }

        $id_establecimiento = Auth::user()->id_establecimiento;

        $query = AtencionEstrategiaSanitaria::with([
            'establecimiento',
            'user', 
            'asegurado',
            'tipoAtencion',
            'upss'
        ])->where('id_establecimiento', $id_establecimiento);

   if ($user->flagfiscalizador != '1') {
        $query->where('id_medico', $medico->id);
    }

        // Filtros
        if (!empty($request->fecha_desde)) {

            $fecha_desde = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha_desde)->format('Y-m-d');
            $query->whereDate('fecha_atencion', '>=', $fecha_desde);
        } else {
            //$fecha_desde = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');

            $query->whereDate('fecha_atencion', '>=', '2021-01-01');
        }


        if (!empty($request->fecha_hasta)) {

            $fecha_hasta = \Carbon\Carbon::createFromFormat('d-m-Y', $request->fecha_hasta)->format('Y-m-d');
            $query->whereDate('fecha_atencion', '<=', $fecha_hasta);
        } else {
            //$fecha_hasta = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
            $query->whereDate('fecha_atencion', '<=', '2060-12-31');
        }

        if (!empty($request->documento)) {
            $query->whereHas('asegurado', function ($q) use ($request) {
                $q->where('nro_doc_ident', 'like', '%' . $request->documento . '%');
            });
        }

        if (!empty($request->tipo_estrategia)) {
            $query->where('tipo_estrategia', $request->tipo_estrategia);
        }

        if (!empty($request->estado_estrategia)) {
            $query->where('estado_estrategia', $request->estado_estrategia);
        }

        $resultados = $query->orderBy('fecha_registro', 'desc')->get();
        $resultados->transform(function ($item) {
            if ($item->relationLoaded('asegurado') && $item->asegurado) {
                unset($item->asegurado->created_at, $item->asegurado->updated_at);
            }
            return $item;
        });
        
        $resultados->transform(function ($item) {
            $medicoES = User::where('id', $item->id_medico)
            ->first();
        if (isset($medicoES->id)) {
            $item->nombre_medico = $medicoES->name;
            $item->apellido_paterno_medico = $medicoES->paterno;
            $item->apellido_materno_medico = $medicoES->materno;
        } else {
            $item->nombre_medico = null;
            $item->apellido_paterno_medico = null;
            $item->apellido_materno_medico = null;
        }
        return $item;
        });
        return response()->json($resultados);
    }

    /*public function listar(Request $request)
    {
        $query = AtencionEstrategiaSanitaria::with(['establecimiento', 'upss', 'medico', 'asegurado']);

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_atencion', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_atencion', '<=', $request->fecha_fin);
        }
        if ($request->filled('nro_documento')) {
            $query->whereHas('asegurado', function ($q) use ($request) {
                $q->where('nro_doc_ident', 'ilike', '%' . $request->nro_documento . '%');
            });
        }
        if ($request->filled('tipo_estrategia')) {
            $query->where('tipo_estrategia', $request->tipo_estrategia);
        }
        if ($request->filled('estado_estrategia')) {
            $query->where('estado', $request->estado_estrategia);
        }

        $datos = $query->orderBy('fecha_atencion', 'desc')->get();

        return response()->json($datos);
    }*/
    /*
    public function guardar(Request $request)
    {
        DB::beginTransaction();
        try {
            $registro = new AtencionEstrategiaSanitaria();
            $registro->id_establecimiento = $request->id_establecimiento;
            $registro->id_tipo_atencion = $request->id_tipo_atencion;
            $registro->id_upss = $request->id_upss;
            $registro->ugipress = $request->ugipress;
            $registro->nro_registro = $request->nro_registro;
            $registro->fecha_atencion = $request->fecha_atencion;
            $registro->fecha_registro = $request->fecha_registro;
            $registro->cant_personas = $request->cant_personas;
            $registro->tipo_estrategia = $request->tipo_estrategia;
            $registro->id_medico = $request->id_medico;
            $registro->id_asegurado = $request->id_asegurado;
            $registro->save();

            // Diagnósticos
            if ($request->has('diagnosticos')) {
                foreach ($request->diagnosticos as $diag) {
                    AtencionEstrategiaDiagnostico::create([
                        'id_atencion_estrategia_sanitaria' => $registro->id,
                        'id_diagnostico' => $diag['id_diagnostico'],
                        'observacion' => $diag['observacion']
                    ]);
                }
            }

            // Actividades
            if ($request->has('actividades')) {
                foreach ($request->actividades as $act) {
                    AtencionEstrategiaActividad::create([
                        'id_atencion_estrategia_sanitaria' => $registro->id,
                        'estado' => $act['estado'],
                        'codigo_estrategia' => $act['codigo_estrategia'],
                        'observacion' => $act['observacion']
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'id' => $registro->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }*/

    public function actualizar(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $registro = AtencionEstrategiaSanitaria::findOrFail($id);

            // Actualizar campos básicos
            $registro->id_upss = $request->id_upss_ind;
            $registro->fecha_atencion = $request->fecha_atencion_ind;
            $registro->cant_personas = isset($request->cant_personas) ? $request->cant_personas : 1;
            $registro->estado_estrategia = $request->estado_estrategia;
            $registro->periodo = $request->periodo;
            $registro->save();

            // Eliminar diagnósticos antiguos y agregar nuevos (solo para individual)
            if ($registro->tipo_estrategia == 1) {
                AtencionEstrategiaDiagnostico::where('id_atencion_estrategia', $id)->delete();

                if (!empty($request->diagnosticos)) {
                    foreach ($request->diagnosticos as $diag) {
                        AtencionEstrategiaDiagnostico::create([
                            'id_atencion_estrategia' => $id,
                            'id_diagnostico' => $diag['id'],
                            'tipo_diagnostico' => $diag['tipo'],
                            'observacion' => $diag['observacion']
                        ]);
                    }
                }
            }

            // Eliminar actividades antiguas y agregar nuevas
            AtencionEstrategiaActividad::where('id_atencion_estrategia', $id)->delete();

            if (!empty($request->estrategias)) {
                foreach ($request->estrategias as $estrategia) {
                    AtencionEstrategiaActividad::create([
                        'id_atencion_estrategia' => $id,
                        'codigo_estrategia' => $estrategia['codigo'],
                        'observacion' => $estrategia['observacion']
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'id' => $registro->id,
                'nro_registro' => $registro->nro_registro
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function guardar(Request $request)
    {
        DB::beginTransaction();
        try {
            // Generar número de registro automático


            // Crear la atención estratégica


            if ($request->id_registro_edit == "") {
                $registro = new AtencionEstrategiaSanitaria();
            } else {
                $registro = AtencionEstrategiaSanitaria::findOrFail($request->id_registro_edit);
            }
            $registro->id_establecimiento = $request->id_establecimiento;
            $registro->id_tipo_atencion = $request->tipo_atencion;
            $registro->codigo_estrategia = isset($request->codigo_estrategia) ? $request->codigo_estrategia : null;
            $registro->id_upss = $request->id_upss_ind;
            $registro->id_medico = $request->id_medico;
            if ($request->id_registro_edit == "") {
                $nroRegistro = $this->generarNumeroRegistro($request->id_establecimiento);
                $registro->nro_registro = $nroRegistro;
            } else {
                $nroRegistro =  $registro->nro_registro = $request->nro_registro;
                //$registro->id=$request->id_registro_edit;
            }

            // Solo para atenciones individuales
            if ($request->tipo_estrategia == 1) {
                if ($request->tipo_registro == "U") {
                    $registro->nombre_civil = $request->nombre_asegurado_es;
                    $registro->paterno_civil = $request->paterno_es;
                    $registro->materno_civil = $request->materno_es;
                    $registro->dni_civil = $request->dni_es;
                    $registro->sexo = $request->sexo_es;
                    $registro->fecha_nac = $request->nacimiento_es;
                    $registro->flag_civil = 1;
                } else {
                    $registro->id_asegurado = $request->idEstPaciente;
                    $registro->flag_civil = 0;
                }
            }

            if ($request->id_registro_edit > 0 && $request->tipo_estrategia == 2) {
                $registro->id_asegurado = null;
            }

            $registro->id_ugipress = $request->ugipress;

            $registro->fecha_atencion = $request->fecha_atencion_ind;

            //$registro->fecha_registro = \Carbon\Carbon::now();

            $registro->cant_personas = isset($request->cant_personas) ? $request->cant_personas : 1;

            $registro->tipo_estrategia = $request->tipo_estrategia;
            $registro->estado_estrategia = $request->estado_estrategia == "" ? null : $request->estado_estrategia;
            $registro->periodo = $request->periodo;

            $registro->save();
            if ($request->id_registro_edit > 0) {

                // Eliminar diagnósticos antiguos y agregar nuevos (solo para individual)
                if ($registro->tipo_estrategia == 1) {
                    AtencionEstrategiaDiagnostico::where('id_atencion_estrategia', $request->id_registro_edit)->delete();

                    if (!empty($request->diagnosticos)) {
                        foreach ($request->diagnosticos as $diag) {
                            AtencionEstrategiaDiagnostico::create([
                                'id_atencion_estrategia' => $request->id_registro_edit,
                                'id_diagnostico' => $diag['id'],
                                'id_tipo_diagnostico' => $diag['tipo'],
                                'observacion' => $diag['observacion']
                            ]);
                        }
                    }
                } else {
                    AtencionEstrategiaDiagnostico::where('id_atencion_estrategia', $request->id_registro_edit)->delete();
                }

                // Eliminar actividades antiguas y agregar nuevas
                AtencionEstrategiaActividad::where('id_atencion_estrategia', $request->id_registro_edit)->delete();

                if (!empty($request->estrategias)) {
                    foreach ($request->estrategias as $estrategia) {
                        AtencionEstrategiaActividad::create([
                            'id_atencion_estrategia' => $request->id_registro_edit,
                            'id_estrategia' => $estrategia['codigo'],
                            'observacion' => $estrategia['observacion']
                        ]);
                    }
                }
            } else {
                $idReg = $registro->id;

                // Guardar diagnósticos (solo para individual)
                if ($request->tipo_estrategia == 1 && !empty($request->diagnosticos)) {

                    foreach ($request->diagnosticos as $diag) {
                        AtencionEstrategiaDiagnostico::create([
                            'id_atencion_estrategia' => $idReg,
                            'id_diagnostico' => $diag['id'],
                            'id_tipo_diagnostico' => $diag['tipo'],
                            'observacion' => $diag['observacion']
                        ]);
                    }
                }


                // Guardar actividades estratégicas
                if (!empty($request->estrategias)) {
                    foreach ($request->estrategias as $estrategia) {
                        AtencionEstrategiaActividad::create([
                            'id_atencion_estrategia' => $idReg,
                            'id_estrategia' => $estrategia['codigo'],
                            'observacion' => $estrategia['observacion']
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'id' => $registro->id,
                'nro_registro' => $nroRegistro
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function generarNumeroRegistro($idEstablecimiento)
    {

        // Obtener el código corto del establecimiento (últimos 3 dígitos)
        $establecimiento = Establecimiento::find($idEstablecimiento);
        $codigoEstablecimiento = substr($establecimiento->codigo, -3);

        // Obtener el año actual (2 dígitos)
        $anio = date('y');
        $anioUlt = str_pad($anio, 4, '20', STR_PAD_LEFT);
        // Obtener el último correlativo para este establecimiento en el año actual
        $ultimoRegistro = AtencionEstrategiaSanitaria::where('id_establecimiento', $idEstablecimiento)
            ->whereRaw("EXTRACT(YEAR FROM fecha_atencion) = ?", [$anioUlt])
            ->orderBy('nro_registro', 'desc')
            ->first();

        $correlativo = $ultimoRegistro ?
            (int)substr($ultimoRegistro->nro_registro, -4) + 1 :
            1;

        // Formatear el número: EE-AA-CCCC (Ej: 125-23-0001)
        return sprintf('%s%s%04d', $anio, $codigoEstablecimiento,  $correlativo);
    }

    public function obtener($id)
    {
        $registro = AtencionEstrategiaSanitaria::with([
            'diagnosticos.diagnostico',
            'actividades.estrategia',
            'medico',
            'asegurado',
            'establecimiento',
            'upss'
        ])->findOrFail($id);

        if ($registro->asegurado) {
            $tipoDoc = $registro->asegurado->id_tipo_doc_ident;
            $nroDoc  = $registro->asegurado->nro_doc_ident;

            $asegurado = new Asegurado();
            $infoAsegurado = $asegurado->busca_datos_aseguradosxtiponro($tipoDoc, $nroDoc);

            if (count($infoAsegurado)) {
                $registro->asegurado_extendido = $infoAsegurado[0]; // lo agregas como propiedad adicional
            }
        }

        if ($registro->relationLoaded('asegurado') && $registro->asegurado) {
            unset($registro->asegurado->created_at, $registro->asegurado->updated_at);
        }

            $medicoES = Medico::where('id', $registro->id_medico)
            ->first();
      
            if (count($medicoES)) {
                $cole = "SELECT * FROM colegio_profesionales where id = '$medicoES->id_colegio_profesional'";
                $cole = DB::select($cole);
                $cole = $cole[0];

                $registro->medicoES = $medicoES;
                 if (count($medicoES)) {
                    $registro->colegioregistro = $cole->descripcion;
                 }else{
                    $registro->colegioregistro = "";
                 }
                $registro->dniMedico = $medicoES->dni;
                $registro->infoMedico = $medicoES->nombre . ' ' . $medicoES->paterno . ' ' . $medicoES->materno; 
                $registro->colMedico = $medicoES->colegiatura;
                $registro->rneMedico = $medicoES->rne;
            }else{
                 $registro->dniMedico = "";
                $registro->infoMedico = "";
                $registro->colMedico = "";
                $registro->rneMedico = "";
                $registro->colegioregistro = "";
            }
    
        return response()->json(['success' => true, 'data' => $registro]);
    }

    public function eliminar($id)
    {
        $registro = AtencionEstrategiaSanitaria::findOrFail($id);
        if ($registro->estado == 0) {
            $registro->estado = 1;
        } else {
            $registro->estado = 0;
        }
        $registro->save();

        return response()->json(['success' => true]);
    }
}
