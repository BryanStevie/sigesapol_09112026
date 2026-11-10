<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\InfusionEnfermeria;
use Auth;

class InfusionEnfermeriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function getAll()
    {
        $result = InfusionEnfermeria::get();
        return response()->json($result, 200);
    }

    public function buscar(Request $request)
    {
        $descripcionTerm = $request->query('descripcion');

        $query = InfusionEnfermeria::query();

        if ($descripcionTerm) {
            $query->whereRaw('descripcion ILIKE ?', ["%{$descripcionTerm}%"]);
        }

        $result = $query->get();

        return response()->json($result, 200);
    }

}