<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\HistorialConsulta;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class HistorialConsultaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function store(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveReasonWithAuthSession(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $this->validate($request,[
            'id_prestacion' => 'required',
            'motivo' => 'required'
        ]);

        $newConsulta= HistorialConsulta::create([
            'id_prestacion' => $request->id_prestacion,
            'motivo' => $request->motivo,
            'fecha' => Carbon::now()->toDateString(),
            'hora' => Carbon::now()->toTimeString(),
            'id_user' => Auth::user()->id,
        ]);

        return response()->json([
            "message" => "Se registro la historia de consulta exitosamente"
        ], JsonResponse::HTTP_CREATED);
    }
}
