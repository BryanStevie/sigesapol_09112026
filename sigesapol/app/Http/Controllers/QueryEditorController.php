<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
class QueryEditorController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function nuevo_query_ruta()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;
    $id_user = Auth::user()->id;
  
    if($id_user == "3068" or $id_user == "115"  or $id_user == "3263" or $id_user == "2327" or $id_user == "3461" or $id_user == "3051" or $id_user == "4224" or $id_user == "2657" or $id_user == "5802"){
      return view('frontend.query_editor.query_editor_previo');
    }else{
      return redirect('/dashboard')->withFlashDanger("No tiene permisos para este proceso");
    }
  }
  
  public function nuevo_query()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $id_user = Auth::user()->id;
   
    if($id_user == "3068" or $id_user == "115"  or $id_user == "3263" or $id_user == "2327" or $id_user == "3461" or $id_user == "3051" or $id_user == "4224" or $id_user == "2657" or $id_user == "5802"){
      return view('frontend.query_editor.query_editor');
    }else{
      return redirect('/dashboard')->withFlashDanger("No tiene permisos para este proceso");
    }
  }
  public function procesa_query(Request $request)
  {
    $tipo_query = explode(' ', $request->txtquery);

    try {

      switch (strtolower($tipo_query[0])) {
        case 'create':
        case 'alter':
        case 'drop':
          $affected = DB::connection('pgsql')->statement($request->txtquery);
          $result_query = "Registros afectados: {$affected}";
          break;
        default:
          $result_query = DB::connection('pgsql')->select($request->txtquery);
      }

      $result_keys = array_keys((array)$result_query[0]);
      $query_string = $request->txtquery;
      return view('frontend.query_editor.query_editor', compact('query_string', 'result_query', 'result_keys'));
    } catch (\Exception $e) {
      try {
        $affected = DB::connection('pgsql')->unprepared($request->txtquery);
        $result_query = "Registros afectados: {$affected}";
        $result_keys = array_keys((array)$result_query[0]);
        $query_string = $request->txtquery;
        return view('frontend.query_editor.query_editor', compact('query_string', 'result_query', 'result_keys'));
      } catch (\Exception $e) {
        return back()->withError($e->getMessage())->withInput();
      }
    }
  }
}