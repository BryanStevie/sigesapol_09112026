<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Diagnostico extends Model
{
    //
    public function ShowDiagnosticos() {        
        $datos=DB::table('diagnosticos')
                ->select('diagnosticos.*')   
                ->orderby('nombre','asc')
                ->where('estado',1)
                ->whereRaw('LENGTH(codigo) > 4')                
                ->get();
        return $datos;
    }
    
    public function GetDiagnosticoByCie($cie){
        $datos=DB::table('diagnosticos')
                ->select('diagnosticos.*')   
                ->where('codigo',$cie)
                ->where('estado',1)
                ->whereRaw('LENGTH(codigo) > 4')
                ->first();
        return $datos;
    }
    
    public function GetDiagnosticoByCieGet($id){
        $datos=DB::table('diagnosticos')
                ->select('diagnosticos.*')   
                ->where('codigo',$id)
                ->where('estado',1)
                ->whereRaw('LENGTH(codigo) > 4')
                ->get();
        return $datos;
    }

    public function GetDiagnosticoById($id){
        $datos=DB::table('diagnosticos')
                ->select('diagnosticos.*')   
                ->where('id',$id)
                ->where('estado',1)
                ->whereRaw('LENGTH(codigo) > 4')
                ->get();
        return $datos;
    }
	
	public function GetDiagnosticoxId($id){
        $datos=DB::table('diagnosticos')
                ->select('diagnosticos.*')   
                ->where('estado',1)
                ->where('id',$id)
                ->first();
        return $datos;
    }
    public function GetDiagnosticoxId2($id){
      $datos=DB::table('diagnosticos')
              ->select('diagnosticos.id as id_diagnostico','diagnosticos.nombre','diagnosticos.codigo')   
              ->where('estado',1)
              ->where('id',$id)
              ->first();
      return $datos;
  }
	
	public function getDiagnosticoByNombre($buscar) {
        
        $cad = "select *
                from diagnosticos
                where codigo||nombre ilike '%".$buscar."%' and length(codigo) >4 and estado=1  
                order By nombre asc
                limit 50 ";
        
        $data = DB::select($cad);
        return $data;
    }

    public function getDiagnosticoByGenero($buscar, $genero) {
        
        $cad = "select *
                from diagnosticos
                where codigo||nombre ilike '%".$buscar."%' and length(codigo) >4 and estado=1 and ( genero=".$genero." or genero=3 )  
                order By nombre asc
                limit 50 ";
        /*if(Auth::user()->id==115):
            echo $cad;
        endif;*/
        $data = DB::select($cad);
        return $data;       
    }

    function getDiagnosticos() {
        $cad = "Select id,codigo, nombre,estado, genero From diagnosticos Order By id";
        $data = DB::select($cad);
        return $data;
    }

    public function diagnostico_yaregistrada($abreviatura){
        $datos=DB::table('diagnosticos') 
                ->where('diagnosticos.codigo','=',$abreviatura)
                ->where('estado',1)
                ->select('diagnosticos.*')
                ->first();
        return $datos;                      
    }

 public function listar_diagnosticos_ajax($p)
  {
    return $this->readFunctionPostgres('sp_listar_diagnosticos', $p);
  }

  public function readFunctionPostgres($function, $parameters = null)
  {

    $_parameters = '';
    if (count($parameters) > 0) {
      $_parameters = implode("','", $parameters);
      $_parameters = "'" . $_parameters . "',";
    }
    $data = DB::select("BEGIN;");
    $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";

    $data = DB::select($cad);
    $cad = "FETCH ALL IN ref_cursor;";
    $data = DB::select($cad);



    return $data;
  }
  
    public function getDiagnosticoByGenero_by_id($id_diagnostico) { 
        $cad = "select * from diagnosticos
                where id= '".$id_diagnostico."' and length(codigo) >4 and estado=1 order By nombre asc limit 50 ";
        $data = DB::select($cad);
        return $data;       
    }

    public function getDiagnosticoOnCreateConsejeriaById($id_diagnostico) {
        $diagnostico = DB::table('diagnosticos as d')
                    ->select(
                        'd.id',
                        DB::raw('d.id as id_diagnostico'),
                        DB::raw("'2' as id_tipo_diagnostico"),
                        'd.codigo',
                        DB::raw("'-' as id_categoria"),
                        DB::raw("(CASE WHEN d.codigo = 'Z21.X' OR d.codigo ILIKE 'B20%' THEN '' ELSE d.nombre END) as nombre"),
                        DB::raw("'-' as id_clasificacion_diagnosticos"),
                        DB::raw("'-' as primera_letra_descripcion")
                    )
                    ->where('d.id', $id_diagnostico)
                    ->first();
        return $diagnostico;
    }
}
