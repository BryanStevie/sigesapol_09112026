<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ProcedimientoDiagnotico;
use Auth;

class ProcedimientoDiagnoticoController extends Controller
{
  function __construct()
  {
      $this->middleware('auth'); 
  } 
  
  public function getByPrestacionIdAndTipo($prestacionId, $tipoProcedimiento)
    {
      $procedimientoDiagnoticoModel = new ProcedimientoDiagnotico();
      $procedimientos = $procedimientoDiagnoticoModel->getByPrestacionIdAndTipo($prestacionId, $tipoProcedimiento);
      return response()->json($procedimientos, 200);
    }
}