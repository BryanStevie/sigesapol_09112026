<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstrategiaSanitaria;
use Illuminate\Support\Facades\DB;

use Session;

class EstrategiaSanitariaController extends Controller
{
    public function buscar(Request $request)
{
    $q = trim($request->get('q'));

    $resultados = EstrategiaSanitaria::where('estado', 'A')
        ->where(function ($query) use ($q) {
            $query->where('codigo', 'ilike', "%$q%")
                ->orWhere('nombre', 'ilike', "%$q%")
                ->orWhere('descripcion', 'ilike', "%$q%")
                ->orWhereRaw("('ESTRATEGIA ' || ' - ' || codigo || ' - ' || nombre) ilike ?", ["%$q%"]);
        })
        ->orderBy('nombre')
        ->limit(10)
        ->get();

    return response()->json($resultados);
}

}
