<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ViaAdministracion;
use Auth;

class ViaAdministracionController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function getAll()
    {
        $result = ViaAdministracion::where('estado', 1)->get();
        return response()->json($result, 200);
    }
}
