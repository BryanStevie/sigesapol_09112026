<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;

class AseguradoHistoriasController extends Controller
{
    public function getHistoriaByAseguradoIdAndEstablecimientoId($aseguradoId, $establecimientoId) {
		$paciente = DB::table('asegurado_historias as ah')
        ->leftJoin('asegurados as a', 'ah.id_asegurado', '=', 'a.id')
        ->leftJoin('tipo_sangres as ts', 'a.id_tipo_sangre', '=', 'ts.id')
        ->leftJoin('establecimientos as e', 'ah.id_establecimiento', '=', 'e.id')
        ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
        ->where('ah.id_asegurado', $aseguradoId)
        ->where('ah.id_establecimiento', $establecimientoId)
        ->orderBy('ah.id', 'desc')
        ->select([
            'ah.id',
            'ah.id_asegurado',
            'ah.id_establecimiento',
            'ah.nro_historia',
            'ah.id_estado_reg',
            'ah.estado_hc',
            'ah.bak_historia',
            'ah.nro_historia_lns',
            'a.nro_doc_ident',
            'a.nombre as asegurado_nombre',
            'a.materno as asegurado_materno',
            'a.paterno as asegurado_paterno',
            'a.sexo as asegurado_sexo',
            'a.fecha_nac',
            DB::raw("DATE_PART('year', AGE(a.fecha_nac::DATE)) as asegurado_edad"),
            'ts.descripcion_corta',
            'e.nombre as establecimiento_nombre',
            'e.codigo as establecimiento_codigo',
            'tb.descripcion as asegurado_tipo_beneficiario'
        ])
        ->first();
	
		return response()->json($paciente, 200); 
	}

    // Actualizar Nro de historia clinica individual
    public function actualizarNroHistoria(Request $request) {
        $this->validate($request, [
            'id_asegurado_historia' => 'required|integer',
            'nro_historia' => 'required|string|max:15',
        ]);

        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, 'all_asegurados');
        if (empty($menu) || $menu[0]->modificar !== "1") {
            return response()->json(['error' => 'No cuenta con permisos para modificar.'], 403);
        }

        $id_establecimiento = DB::table('asegurado_historias')
            ->where('id', $request->input('id_asegurado_historia'))
            ->value('id_establecimiento');
        
        $nro_historia = trim($request->input('nro_historia'));

        // Establecimiento 76: se completa con ceros a la izquierda hasta 8 caracteres
        if (intval($id_establecimiento) === 76) {
            $nro_historia = str_pad($nro_historia, 8, '0', STR_PAD_LEFT);
        }

        $historia = new \App\Models\Historia();
        $res = $historia->actualizarNroHistoria(
            $request->input('id_asegurado_historia'),
            $nro_historia,
            Auth::user()->id
        );

        if (!$res['ok']) {
            return response()->json(['error' => $res['mensaje']], $res['status']);
        }

        return response()->json(['nro_historia' => $res['nro_historia']], 200);
    }

    // Aperturar Nro de historia clinica automatico
    public function aperturarNroHistoria(Request $request) {
        $this->validate($request, [
            'id_asegurado' => 'required|integer',
            'id_establecimiento' => 'required|integer',
        ]);

        try {
            $historia = new \App\Models\Historia();
            $nro = $historia->GenerarNroHistoriaClinica(
                $request->input('id_asegurado'),
                $request->input('id_establecimiento'),
                Auth::user()->id
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['nro_historia' => $nro], 200);
    }
}
