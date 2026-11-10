<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProgramacionAsistencial;
use App\Models\ClaseProgramacion;
use App\Models\TipoProgramacion;
use App\Models\Farmacia;
use App\Models\Establecimiento;
use App\Models\Medico;
use Illuminate\Support\Facades\Auth;
use DB;

class ProgramacionAsistencialController extends Controller
{
    function __construct()
  {
      $this->middleware('auth'); 
  }
  
    public function index()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        
     
    }

    public function listarTipos($id_clase)
    {if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return response()->json(
            TipoProgramacion::where('estado', 1)
                ->where('id_clase', $id_clase)
                ->get()
        );
    }

    public function buscar(Request $request)
{
if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    $query = ProgramacionAsistencial::with(['personal', 'clase', 'tipo'])
        ->where('periodo', $request->input('periodo'))
        ->where('id_establecimiento', $request->input('id_establecimiento'))
        ->where('estado', 1);

       if ($request->filled('id_personal')) {
        print_r($request->input("id_personal"));
        $query->where('id_personal', $request->input('id_personal'));
    }

    $registros = $query->get();

    return response()->json([
        'success' => true,
        'data' => $registros,
        'message' => 'Búsqueda completada'
    ]);
}

     public function buscarPorEstablecimiento(Request $request)
{
if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    $query = ProgramacionAsistencial::with(['personal', 'clase', 'tipo'])
    ->where('estado', 1);
    if ($request->has('periodo')) {       
         $query->where('periodo', $request->input('periodo'));
    }

    if ($request->has('id_establecimiento')) {       
         $query->where('id_establecimiento', $request->input('id_establecimiento'));
    } 
   
    
        if ($request->has('id_personal')) {       
         $query->where('id_personal', $request->input('id_personal'));
        }

    $registros = $query->get();

    // Respuesta estructurada
    return response()->json([
        'success' => true,
        'data' => $registros,
        'message' => 'Búsqueda completada'
    ]);
}

public function update(Request $request, $id)
{
    if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    try {
        $registro = ProgramacionAsistencial::findOrFail($id);
        $registro->update([
            'periodo' => $request->input('periodo'),
            'id_establecimiento' => $request->input('id_establecimiento'),
            'id_personal' => $request->input('id_personal'),
            'id_clase' => $request->input('id_clase'),
            'id_tipo' => $request->input('id_tipo'),
            'responsable_pnp' => $request->input('responsable_pnp', 0),
'responsable_susalud' => $request->input('responsable_susalud', 0),
            'horas_programadas' => $request->input('horas_programadas'),
            'horas_efectivas' => $request->input('horas_efectivas')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registro actualizado correctamente',
            'data' => $registro
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar: ' . $e->getMessage()
        ], 500);
    }
}




    public function store(Request $request)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        try {
            $registro = ProgramacionAsistencial::create([
                'periodo' => $request->input('periodo'),
                'id_establecimiento' => $request->input('id_establecimiento'),
                'id_personal' => $request->input('id_personal'),
                'id_clase' => $request->input('id_clase'),
                'id_tipo' => $request->input('id_tipo'),
                'responsable_pnp' => $request->input('responsable_pnp', 0),
'responsable_susalud' => $request->input('responsable_susalud', 0),
                'horas_programadas' => $request->input('horas_programadas'),
                'horas_efectivas' => $request->input('horas_efectivas'),
                'estado' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro guardado correctamente',
                'data' => $registro
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

 public function destroy($id)
{
    if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    try {
        $registro = ProgramacionAsistencial::findOrFail($id);
        $registro->estado = 0;
        $registro->save();

        return response()->json([
            'success' => true,
            'message' => 'Registro eliminado correctamente'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar: ' . $e->getMessage()
        ], 500);
    }
}


public function eliminar(Request $request)
{
    if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    try {
        $registro = ProgramacionAsistencial::findOrFail($request->id);
        $registro->estado = 0;
        $registro->save();

        return response()->json([
            'success' => true,
            'message' => 'Registro eliminado correctamente'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar: ' . $e->getMessage()
        ], 500);
    }
}


    public function eliminarTodos(Request $request)
{
    if (!Auth::check()):
            return redirect('/auth/login');
        endif;

    try {
        

        $deleted = ProgramacionAsistencial::where('periodo', $request->periodo)
            ->where('id_establecimiento', $request->id_establecimiento)
            ->where('id_personal', $request->id_personal)
            ->where('estado', 1)
            ->update(['estado' => 0]);

        return response()->json([
            'success' => true,
            'message' => $deleted . ' registros eliminados correctamente'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar: ' . $e->getMessage()
        ], 500);
    }
}

public function editar_programacion_asistencial_1($id_persona,$periodo,$establecimiento){

            $establecimiento = Auth::user()->id_establecimiento;
            $id_persona = $id_persona;
            $periodo = $periodo;
            $dato =  DB::table('programacion_asistencial')
                     ->select('*')
                     ->where('periodo',$periodo)
                     ->where('id_personal',$id_persona)
                     ->where('id_establecimiento',$establecimiento)
                     ->where('estado','1')
                     ->first();                     
    if ($dato) {
        return redirect()->back()->with('error', 
            'Ya existe programación asistencial para ese periodo en esa IPRESS para el médico. Por favor verificar el listado mostrado.'
        );
    }
   $data = collect($dato)->keyBy('id_tipo');
    $c24 = $data->get(24);
    $c21 = $data->get(21);
    $c23 = $data->get(23);
    $c22 = $data->get(22);
    $c4  = $data->get(4);
    $c8  = $data->get(8);
    $c9  = $data->get(9);
    $c10 = $data->get(10);
    $c12 = $data->get(12);
    $c16 = $data->get(16);
    $c17 = $data->get(17);
    $c1  = $data->get(1);
    $c2  = $data->get(2);
    $c3  = $data->get(3);
    $c5  = $data->get(5);
    $c6  = $data->get(6);
    $c7  = $data->get(7);
    $c11 = $data->get(11);
    $c13 = $data->get(13);
    $c14 = $data->get(14);
    $c15 = $data->get(15);
    $c18 = $data->get(18);
    $c19 = $data->get(19);

$total_col1 = $data->sum(function($item){
    return isset($item->horas_programadas) ? $item->horas_programadas : 0;
});

$total_col2 = $data->sum(function($item){
    return isset($item->horas_efectivas) ? $item->horas_efectivas : 0;
});
       
    return view('frontend.seis.editar_programacion_asistencial', compact(
        'establecimiento',
        'id_persona',
        'periodo',
        'c24','c21','c23','c22','c4','c8','c9','c10','c12','c16','c17',
        'c1','c2','c3','c5','c6','c7','c11','c13','c14','c15','c18','c19','total_col2','total_col1'
    ));
}

public function editar_programacion_asistencial_2($id_persona,$periodo,$establecimiento){

            $establecimiento = Auth::user()->id_establecimiento;
            $id_persona = $id_persona;
            $periodo = $periodo;
            $dato =  "SELECT * FROM programacion_asistencial WHERE periodo = '$periodo' and id_personal = '$id_persona' and id_establecimiento = '$establecimiento'";
            $dato = DB::select($dato); 
                        
    $data = collect($dato)->keyBy('id_tipo');
    $c24 = $data->get(24);
    $c21 = $data->get(21);
    $c23 = $data->get(23);
    $c22 = $data->get(22);
    $c4  = $data->get(4);
    $c8  = $data->get(8);
    $c9  = $data->get(9);
    $c10 = $data->get(10);
    $c12 = $data->get(12);
    $c16 = $data->get(16);
    $c17 = $data->get(17);
    $c1  = $data->get(1);
    $c2  = $data->get(2);
    $c3  = $data->get(3);
    $c5  = $data->get(5);
    $c6  = $data->get(6);
    $c7  = $data->get(7);
    $c11 = $data->get(11);
    $c13 = $data->get(13);
    $c14 = $data->get(14);
    $c15 = $data->get(15);
    $c18 = $data->get(18);
    $c19 = $data->get(19);

$total_col1 = $data->sum(function($item){
    return isset($item->horas_programadas) ? $item->horas_programadas : 0;
});

$total_col2 = $data->sum(function($item){
    return isset($item->horas_efectivas) ? $item->horas_efectivas : 0;
});
       
    return view('frontend.seis.editar_programacion_asistencial', compact(
        'establecimiento',
        'id_persona',
        'periodo',
        'c24','c21','c23','c22','c4','c8','c9','c10','c12','c16','c17',
        'c1','c2','c3','c5','c6','c7','c11','c13','c14','c15','c18','c19','total_col2','total_col1'
    ));
    
}

public function guardar_programacion_asistencial_1(Request $request){
                    $id_user = Auth::user()->id;
                   $model_farma = new Farmacia;
                    $farma = $model_farma->GetFarmaByUser($id_user);
                    $id_farmacia = $farma->id;

                $establecimiento = $request->establecimiento;
                $id_persona = $request->id_persona;
                $periodo = $request->periodo;
                for ($i = 0; $i < count($request->col1); $i++) {

                    if (!empty($request->col1[$i])) {

                        $concepto_val = $request->concepto[$i];
                        $col1_val = $request->col1[$i];
                        if (isset($request->col2[$i]) && trim($request->col2[$i]) !== '') {
                                $col2_val = $request->col2[$i];
                            } else {
                                $col2_val = 0;
                            }
                        $check1 = $request->check1[$i];
                        $check2 = $request->check2[$i];
                        $dato =  DB::table('tipo_programacion')
                                 ->select('*')
                                 ->where('id_tipo',$concepto_val)
                                 ->first();

                        $existe =  DB::table('programacion_asistencial')
                                 ->select('id')
                                 ->where('id_personal',$id_persona)
                                 ->where('periodo',$periodo)
                                 ->where('id_establecimiento',$establecimiento)
                                 ->where('id_tipo',$concepto_val)
                                 ->first();
                       if ($existe) {

    ProgramacionAsistencial::where('id_personal', $id_persona)
    ->where('periodo', $periodo)
    ->where('id_establecimiento', $establecimiento)
    ->where('id_tipo', $concepto_val)
    ->update([
        
        'periodo' => $periodo,
        'id_establecimiento' => $establecimiento,
        'id_personal' => $id_persona,
        'id_clase' => $dato->id_clase,
        'id_tipo' => $concepto_val,
        'responsable_pnp' => $check1,
        'responsable_susalud' => $check2,
        'horas_programadas' => $col1_val,
        'horas_efectivas' => $col2_val,
        'estado' => 1
    ]);

} else {

    ProgramacionAsistencial::create([
        'periodo' => $periodo,
        'id_establecimiento' => $establecimiento,
        'id_personal' => $id_persona,
        'id_clase' => $dato->id_clase,
        'id_tipo' => $concepto_val,
        'responsable_pnp' => $check1,
        'responsable_susalud' => $check2,
        'horas_programadas' => $col1_val,
        'horas_efectivas' => $col2_val,
        'estado' => 1
    ]);
                    }
                    }
                }
    return response()->json([
    'redirect' => url('editar_medico/'.$id_persona.'/'.$id_farmacia),
    'message' => 'Programación guardada correctamente'
]);
}

public function buscar_programacion_asistencial_1(Request $request)
{
    $id = $request->id_personal;
    $periodo = $request->periodo;

    $establecimiento = Auth::user()->id_establecimiento;
    $flagestablecimientos = Auth::user()->flagestablecimientos;

    if ($periodo != '') {

        if ($flagestablecimientos == 1) {

            $sql = "
                SELECT 
                    p.periodo,
                    e.nombre as id_establecimiento,
                    SUM(p.horas_programadas) AS horas_programadas,
                    SUM(p.horas_efectivas) AS horas_efectivas
                FROM programacion_asistencial p
                INNER JOIN establecimientos e 
                    ON e.id = p.id_establecimiento
                WHERE p.id_personal = ?
                AND p.estado = '1'
                AND p.periodo = ?
                GROUP BY p.periodo, e.nombre
                ORDER BY p.periodo ASC
            ";

            $programacion_asistencial = DB::select($sql, [$id, $periodo]);

        } else {

            $sql = "
                SELECT 
                    p.periodo,
                    e.nombre as id_establecimiento,
                    SUM(p.horas_programadas) AS horas_programadas,
                    SUM(p.horas_efectivas) AS horas_efectivas
                FROM programacion_asistencial p
                INNER JOIN establecimientos e 
                    ON e.id = p.id_establecimiento
                WHERE p.id_personal = ?
                AND p.estado = '1'
                AND p.periodo = ?
                AND p.id_establecimiento = ?
                GROUP BY p.periodo, e.nombre
                ORDER BY p.periodo ASC
            ";

            $programacion_asistencial = DB::select($sql, [$id, $periodo, $establecimiento]);
        }

    } else {

        if ($flagestablecimientos == 1) {

            $sql = "
                SELECT 
                    p.periodo,
                    e.nombre as id_establecimiento,
                    SUM(p.horas_programadas) AS horas_programadas,
                    SUM(p.horas_efectivas) AS horas_efectivas
                FROM programacion_asistencial p
                INNER JOIN establecimientos e 
                    ON e.id = p.id_establecimiento
                WHERE p.id_personal = ?
                AND p.estado = '1'
                GROUP BY p.periodo, e.nombre
                ORDER BY p.periodo ASC
            ";

            $programacion_asistencial = DB::select($sql, [$id]); // ✅ CORRECTO

        } else {

            $sql = "
                SELECT 
                    p.periodo,
                    e.nombre as id_establecimiento,
                    SUM(p.horas_programadas) AS horas_programadas,
                    SUM(p.horas_efectivas) AS horas_efectivas
                FROM programacion_asistencial p
                INNER JOIN establecimientos e 
                    ON e.id = p.id_establecimiento
                WHERE p.id_personal = ?
                AND p.estado = '1'
                AND p.id_establecimiento = ?
                GROUP BY p.periodo, e.nombre
                ORDER BY p.periodo ASC
            ";

            $programacion_asistencial = DB::select($sql, [$id, $establecimiento]); // ✅ CORRECTO
        }
    }

    return response()->json($programacion_asistencial);
}

}