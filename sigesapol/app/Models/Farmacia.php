<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Farmacia extends Model
{
    //
    protected $guarded = ['id'];
    
    public function ObtenerNivelFarmacia($id_farmacia) {
        //var_dump($id_farmacia); exit;
        $datos=DB::table('farmacias') 
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->where('farmacias.id','=',$id_farmacia)
                ->select('establecimientos.nivel')
                ->first();       
        return $datos;
    }
    
    public function ShowFarmacias(){
        $datos=DB::table('farmacias') 
                ->select('farmacias.*')
                ->where('farmacias.id','<>',11)
                ->where('farmacias.id','<>',2)
                ->where('farmacias.id','<>',48)
                ->orderBy('farmacias.nombre','asc')
                ->get();
        return $datos;
    }
    
    public function ObtenerUsuarioCreador($id) {
        $datos=DB::table('users') 
                ->select('users.*')
                ->where('users.id',$id)
                ->first();
        return $datos;        
    }
    
    
    public function ObtenerFarmaciasInternas($id_establecimiento, $id_farmacia) {
        $datos=DB::table('farmacias') 
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->where('farmacias.id','!=',$id_farmacia)
                ->where('establecimientos.id','=',$id_establecimiento)
                ->where('farmacias.id','<>',11)
                ->where('farmacias.id','<>',2)
                ->where('farmacias.id','<>',48)
                ->select('farmacias.*')
                ->get();
        return $datos;
    }
    
    public function GetIdFarmadByNomFarma($nombre){
        $datos=DB::table('farmacias')
                ->select('farmacias.id','farmacias.almacen','farmacias.id_establecimiento','farmacias.almacen_ipress')
                ->where('farmacias.nombre',$nombre)
                ->where('farmacias.id','<>',11)
                ->where('farmacias.id','<>',2)
                ->where('farmacias.id','<>',48)
                ->first();
        return $datos;
    }
    
    public function NombreFarmacias(){
        $datos=DB::table('farmacias') 
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->where('farmacias.id','<>',11)
                ->where('farmacias.id','<>',2)
                ->where('farmacias.id','<>',48)
                //->where('establecimientos.id','<=',77)
                ->select('farmacias.*','establecimientos.nombre as ipress')
                ->orderBy('farmacias.nombre','asc')
                ->get();
        return $datos;
    }
    
    public function OnlyFarmacias($id_establecimiento = 0){
        /*
        $datos=DB::table('farmacias') 
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->where('establecimientos.id','<=',77)
                //->where('farmacias.nombre','!=','DIREOSS')
                //->where('farmacias.nombre','!=','SALUDPOL')
                ->select('farmacias.*')
                ->get(20);
        return $datos;
        */
        /*
        $cad = "select T1.* 
from farmacias T1
inner join establecimientos T2 on T1.id_establecimiento=T2.id
where 1=1
And T2.id <= 77 ";
        */
        $cad = "select T1.* 
from farmacias T1
inner join establecimientos T2 on T1.id_establecimiento=T2.id
where 1=1
And Length(T2.codigo)=8 ";

        if($id_establecimiento > 0){
            if($id_establecimiento==76){
                $cad .= " And T1.id_establecimiento=".$id_establecimiento;
                //$cad .= " And T1.id!=11 And T1.id!=2 And T1.id!=".$id_farmacia;
            }
            else
            {
                if($id_establecimiento==80){
                    $cad .= " And T1.id!=48 And T1.id_establecimiento=".$id_establecimiento;
                }
                else
                {
                    $cad .= " And T1.id_establecimiento=".$id_establecimiento;   
                }
            }
            
        }

        $data = DB::select($cad);
        return $data;
        
        
    }
    
    public function ShowFarmaciasInterna($id_establecimiento = 0,$id_farmacia=0,$almacen=0){
        /*
        $cad = "select T1.* 
            from farmacias T1
            inner join establecimientos T2 on T1.id_establecimiento=T2.id
            where 1=1
            And T2.id Not In(78,79,80,81) ";
        */
        $cad = "select T1.* 
from farmacias T1
inner join establecimientos T2 on T1.id_establecimiento=T2.id
where 1=1
And Length(T2.codigo)=8 ";

        if($id_establecimiento > 0)$cad .= " And T1.id_establecimiento=".$id_establecimiento;
        if($id_farmacia > 0){
            if($almacen == 1){ 
                if($id_establecimiento==76)//CENTRAL
                    $cad .= " And T1.id!=2 And T1.id!=3 And T1.id!=10 And T1.id!=11 And  T1.id!=17 And T1.id!=18 And T1.id!=19 And T1.id!=20 And T1.id!=21 And T1.id!=121 And T1.id!=122 And T1.id!=123 And T1.id!=128 And T1.id!=".$id_farmacia;
                else{
                    if($id_establecimiento==80)
                        $cad .= " And T1.id!=48 And T1.id=".$id_farmacia;
                    else
                    {
                        if($id_establecimiento==75){
                            $cad .= " And T1.id!=12 And T1.id!=13 And T1.id!=".$id_farmacia;                
                        }
                        else{
                            if($id_establecimiento==77){
                                $cad .= " And T1.id!=22 And T1.id!=25 And T1.id!=".$id_farmacia;
                            }
                            else
                            {   /*
                                if($id_establecimiento==1)
                                    $cad .= " And T1.id!=36 And T1.id!=".$id_farmacia;
                                else
                                    $cad .= " And T1.id!=".$id_farmacia;
                                */
                                if($id_establecimiento==1){ 
                                    $cad .= " And T1.id!=36 And T1.id!=".$id_farmacia;
                                }else
                                {   
                                    if($id_establecimiento==39){ 
                                        $cad .= " And T1.id!=111 And T1.id!=".$id_farmacia;
                                    }else
                                    {   
                                        if($id_establecimiento==26){ 
                                            $cad .= " And T1.id!=107 And T1.id!=".$id_farmacia;
                                        }else
                                        { 
                                            $cad .= " And T1.id!=".$id_farmacia;
                                        }
                                    }                                    
                                }
                                
                                
                            }
                        }   
                    }
                }
            }
            else
            {
                if($id_establecimiento==76) //CENTRAL
                    $cad .= " And T1.id!=11 And T1.id!=2 And T1.id!=20 And T1.id!=122 And T1.id!=123 And T1.id!=".$id_farmacia;
                else{
                    if($id_establecimiento==80)
                        $cad .= " And T1.id!=48 And T1.id=".$id_farmacia;
                    else
                    {
                        if($id_establecimiento==75){ //GERIAT
                            $cad .= " And T1.id!=12 And T1.id!=13 And T1.id!=".$id_farmacia;                
                        }
                        else
                            $cad .= " And T1.id!=".$id_farmacia;
                    }
                }
            }
        }            
        $data = DB::select($cad);
        return $data;
    }

    public function BuscaFarmacias($id_establecimiento = 0){
        /*
        $cad = "select T1.* 
from farmacias T1
inner join establecimientos T2 on T1.id_establecimiento=T2.id
where 1=1
And T2.id <= 77 ";
        */
        $cad = "select T1.* 
from farmacias T1
inner join establecimientos T2 on T1.id_establecimiento=T2.id
where 1=1
And Length(T2.codigo)=8 ";

        if($id_establecimiento > 0)$cad .= " And T1.id_establecimiento=".$id_establecimiento;
        if($id_farmacia > 0){
            if($id_establecimiento==76)
                $cad .= " And T1.id!=11 And T1.id!=2 And T1.id!=20 And T1.id!=122 And T1.id!=123 And T1.id!=".$id_farmacia;
            else{
                if($id_establecimiento==80)
                    $cad .= " And T1.id!=48 And T1.id=".$id_farmacia;
                else
                    $cad .= " And T1.id!=".$id_farmacia;
            }
        }
        $data = DB::select($cad);
        return $data;
    }
    
    
    public function ShowFarmaciasExterna($id_establecimiento = 0){
        /*
        $cad = "select T1.* 
from farmacias T1
inner join establecimientos T2 on T1.id_establecimiento=T2.id
where 1=1
And T2.id <= 77 ";
        */
        $cad = "select T1.* 
from farmacias T1
inner join establecimientos T2 on T1.id_establecimiento=T2.id
where 1=1
And Length(T2.codigo)=8";

        if($id_establecimiento > 0)$cad .= " And T1.id_establecimiento=".$id_establecimiento;
        if($id_farmacia > 0){
            if($id_establecimiento==76)
                $cad .= " And T1.id!=11 And T1.id!=2 And T1.id!=20 And T1.id!=122 And T1.id!=123 And T1.id!=".$id_farmacia;
            else{
                if($id_establecimiento==80)
                    $cad .= " And T1.id!=48 And T1.id=".$id_farmacia;
                else
                    $cad .= " And T1.id!=".$id_farmacia;
            }
        }

        $data = DB::select($cad);
        return $data;
    }
    
    
    public function UsuariosByFarmacia($id_farmacia=0) {
        if($id_farmacia > 0):
        $usuarios=DB::table('users')
                ->join('farmacias','users.id_farmacia','=','farmacias.id')
                ->where('farmacias.id','=',$id_farmacia)
                ->where('users.id','!=',8)
                ->where('users.id','!=',9)
                ->where('users.id','!=',23)
                ->where('users.id','!=',48)
                ->where('users.id','!=',50)
                ->where('users.id','!=',58)
                ->where('users.id','!=',33)
                ->select('farmacias.nombre as nombre_farmacia','users.*')
                ->orderBy('farmacias.id','asc')
                ->get();
        else:
            $usuarios=DB::table('users')
                ->join('farmacias','users.id_farmacia','=','farmacias.id')
                //->where('farmacias.id','=',$id_farmacia)
                ->where('users.id','!=',8)
                ->where('users.id','!=',9)
                ->where('users.id','!=',23)
                ->where('users.id','!=',48)
                ->where('users.id','!=',50)
                ->where('users.id','!=',58)
                ->where('users.id','!=',33)
                ->select('farmacias.nombre as nombre_farmacia','users.*')
                ->orderBy('farmacias.id','asc')
                ->get();
        endif;
        
        return $usuarios;
    }
    
    public function AllUsuariosIpress() {
        $usuarios=DB::table('users')
                ->join('farmacias','users.id_farmacia','=','farmacias.id')
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->where('establecimientos.id','<=',77)
                ->where('users.id','!=',8)
                ->where('users.id','!=',9)
                ->where('users.id','!=',23)
                ->where('users.id','!=',48)
                ->where('users.id','!=',50)
                ->where('users.id','!=',58)
                ->where('users.id','!=',33)
                ->select('farmacias.nombre as nombre_farmacia','users.*')
                ->orderBy('farmacias.id','asc')
                ->get();
        return $usuarios;
    }
   
    public function SoloFarmacias(){
        $datos=DB::table('farmacias') 
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->where('establecimientos.id','<=',77)
                ->where('farmacias.id','<>',2)
                ->where('farmacias.id','<>',11)
                ->where('farmacias.id','<>',48)
                ->select('farmacias.*')
                ->get();
        return $datos;
    }
    
    public function ShowAll(){
        /*
        $datos=DB::table('farmacias') 
                ->select('farmacias.*')
                ->orderBy('farmacias.id','desc')
                ->get();
        return $datos;
        */
        $cad = "select T1.*,T2.nombre establecimiento 
from farmacias T1
left join establecimientos T2 on T1.id_establecimiento=T2.id 
";
        $cad .= " Where COALESCE(T1.estado,1)!=3 And T1.id!=11 And T1.id!=20 And T1.id!=122 And T1.id!=123 and T1.id!=48 and T1.id!=2";
        $cad .= " Order By T1.id desc";
        $data = DB::select($cad);
        return $data;
    }
    
    public function GetFarmaByIdfarmacia($id){
        $datos=DB::table('farmacias')
                ->select('farmacias.*')
                ->first();
        return $datos;
    }
    
    public function GetFarmaciaById($id){
        $datos=DB::table('farmacias')
                ->where('farmacias.id','=',$id)
                ->select('farmacias.*')
                ->get();
        return $datos;
    }
    
    public function GetFarmaByIdfarma($id_farmacia){
        $datos=DB::table('farmacias')
                ->where('farmacias.id','=',$id_farmacia)
                ->select('farmacias.*')
                ->first();
        return $datos;
    }

    public function GetIpress($id_farmacia) {
        $datos=DB::table('farmacias')
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->select('establecimientos.nombre','establecimientos.id','establecimientos.codigo')
		->where('farmacias.id','=',$id_farmacia)
                ->first();
        return $datos;
    }

    public function GetAllIpress()
    {
        $establecimientos = Establecimiento::where("id_estado_reg", 1)->select("nombre", "id", "ubigeo", "codigo")->get();
        return $establecimientos;
    }
    
    public function GetEstablecimientoPNP($id_farmacia) {
        $datos=DB::table('farmacias')
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->where('farmacias.id','=',$id_farmacia)
                ->select('establecimientos.nombre','establecimientos.id','establecimientos.codigo','establecimientos.direccion_establecimiento','establecimientos.id_egestion')
                ->first();
        return $datos;
    }
    
    
    public function GetIpressByIduser($id_user) {
        $datos=DB::table('farmacias')
                ->join('users','farmacias.id','=','users.id_farmacia')
                ->join('establecimientos','farmacias.id_establecimiento','=','establecimientos.id')
                ->select('establecimientos.nombre','establecimientos.codigo','establecimientos.id','establecimientos.nivel')
                ->where('users.id',$id_user)
                ->first();
        return $datos;
    }

    public function GetFarmaByUser($id_user){
        $datos=DB::table('farmacias')
                ->join('users','farmacias.id','=','users.id_farmacia')
                ->select('farmacias.*','users.flagfarmacias','users.flagestablecimientos')
                ->where('users.id','=',$id_user)
                ->first();
        //var_dump($datos); exit;
        return $datos;
    }

    public function ExisteFarmacia($nombre, $id_establecimiento, $id=0) {
        $data = Farmacia::where('nombre', '=', trim($nombre))->where('id_establecimiento', '=', $id_establecimiento)->WhereNull('estado')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }
    
    public function GetIdByNombreFarmacia($nombre_farmacia){
        $data=  Farmacia::where('nombre','=',$nombre_farmacia)->first();
        return $data;
    }
    
    public function GetNombreUsuarioByIdUser($id_user) {
        $datos=DB::table('users')
                ->where('id','=',$id_user)->first();
        return $datos;
    }
    
    public static function GetFarmaciaByIdEstablecimiento($id_establecimiento) {
        $cad = "select * from farmacias T1
where T1.id!=1 And T1.id!=2 And T1.id!=4 And T1.id!=11 And T1.id!=12 And T1.id!=13 And T1.id!=15 And T1.id!=20 And T1.id!=24 And T1.id!=27 And T1.id!=30 And T1.id!=35 And T1.id!=45 And T1.id!=46 And T1.id!=47 And T1.id!=52 And T1.id!=122 And T1.id_establecimiento=".$id_establecimiento." And estado isNull";
//where T1.id!=48 And T1.id!=2 And T1.id!=11 And T1.id_establecimiento=".$id_establecimiento." And estado isNull  and nombre not like 'Al%'";
        $data = DB::select($cad);
        return $data;
    }
    
    public static function ExisteFarmaciaInternaorExterna($id_establecimiento, $id) {
        $data = Farmacia::where('id_establecimiento', '=', $id_establecimiento)->where('id', '=', $id)->first();
        return isset($data) ? 1 : 2;
    }
    
    public static function GetFarmaciaDestinoRequerimiento() {
        /*
        $cad = "Select f.id id_farmacia, f.nombre, id_establecimiento From farmacias f
Inner Join establecimientos e On f.id_establecimiento = e.id
Where e.nivel=0 And estado isNull";
        
        $cad = "Select f.id id_farmacia, f.nombre, id_establecimiento From farmacias f
Inner Join establecimientos e On f.id_establecimiento = e.id
Where (e.nivel=0 or e.nivel=3) And estado isnull and e.id_egestion is null"; */

$cad = " Select f.id id_farmacia, f.nombre, id_establecimiento 
From farmacias f
Inner Join establecimientos e On f.id_establecimiento = e.id
Where f.almacen_ipress = 1";

        $data = DB::select($cad);
        return $data;
    }

     public static function GetEstablecimientoDestinoRequerimiento($id_establecimiento) {
        /*
        $cad = "Select f.id id_farmacia, f.nombre, id_establecimiento From farmacias f
Inner Join establecimientos e On f.id_establecimiento = e.id
Where e.nivel=0 And estado isNull";
        
        $cad = "Select f.id id_farmacia, f.nombre, id_establecimiento From farmacias f
Inner Join establecimientos e On f.id_establecimiento = e.id
Where (e.nivel=0 or e.nivel=3) And estado isnull and e.id_egestion is null"; */

$cad = " Select f.id id_farmacia, f.nombre, id_establecimiento 
From farmacias f
Inner Join establecimientos e On f.id_establecimiento = e.id
Where f.almacen_ipress = 1 And f.id_establecimiento=".$id_establecimiento." or f.id=113";

        $data = DB::select($cad);
        return $data;
    }

    public function GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia){
        $data = DB::select("Select * From sp_listar_farmacia_x_tipo_atencion($id_farmacia);");
        return $data;
    }

  /*  public function ExisteFarmaciaTipoAtencion($id_farmacia, $id_tipo_atencion) {
        $data = Farmacia_tipo_atencione::where('id_farmacia', '=', $id_farmacia)->where('id_tipo_atencion', '=', $id_tipo_atencion)->where('estado', '=', '1')->first();
        return isset($data) ? true : false;
    } */

    public static function postRegistrarTipoAtencion($op, $id_farmacia, $id_user, $id_tipo_atencion) {
    
        $cad = "Select sp_crud_farmacia_tipo_atencion(?,?,?,?)";
        $data = DB::select($cad, array($op, $id_farmacia, $id_user, $id_tipo_atencion));
        return $data[0]->sp_crud_farmacia_tipo_atencion;
    }   
    
    public function listarTipoAtencionPorIdServicioIdFarmacia($id_farmacia, $id_consultorio){
        $data = DB::select("Select * From sp_listar_tipo_atencion_x_farmacia_consultorio($id_farmacia, $id_consultorio);");
        return $data;
    } 

    public static function postRegistrarTipoAtencionFarmaciaServicio($op, $id_farmacia, $id_consultorio, $id_tipo_atencion, $id_user) {
    
        $cad = "Select sp_crud_farmacia_consultorio_tipo_atencion(?,?,?,?,?)";
        $data = DB::select($cad, array($op, $id_farmacia, $id_consultorio, $id_tipo_atencion, $id_user));
        return $data[0]->sp_crud_farmacia_consultorio_tipo_atencion;
    } 
    
    public function OnlyFarmaciasEstablecimiento(){
        /*
        $cad = "select T2.* 
                from farmacias T1
                inner join establecimientos T2 on T1.id_establecimiento=T2.id
                where 1=1
                And (T2.id <= 77 or T2.id>81)
                group by T2.id
                order by T2.id asc ";
        */
        $cad = "select T2.* 
                from farmacias T1
                inner join establecimientos T2 on T1.id_establecimiento=T2.id
                where 1=1
                And Length(T2.codigo)=8                
                group by T2.id
                order by T2.id asc ";
                
        $data = DB::select($cad);
        return $data;
    }

    public static function GetFarmaciaByIdEstablecimientoAndIdatencion($id_establecimiento, $id_tipo_atencion) {
        $cad = "select T1.id, T1.nombre from farmacias T1
                inner join farmacia_tipo_atenciones T2 on T1.id = T2.id_farmacia
                inner join tipo_atenciones T3 on T2.id_tipo_atencion = T3.id
                where T1.id!=10 And T1.id!=17 And T1.id!=18 And T1.id!=21 And T1.id!=109 And T1.id!=48 And T1.id!=2 And T1.id!=11 And T1.id!=122 And T1.id!=123 And T1.id_establecimiento=".$id_establecimiento." And T3.id=".$id_tipo_atencion." And T1.estado isNull  and T1.nombre not like 'Al%'";

        $data = DB::select($cad);
        return $data;
    }

    public function OnlyFarmaciasXEstablecimiento($id_establecimiento){
        $cad = "select * from farmacias T1
                where almacen_ipress=0 And T1.id_establecimiento=".$id_establecimiento." And estado isNull ";
                
        $data = DB::select($cad);
        return $data;
    }
    
    public function GetFarmaciasHospitalizacion($id_establecimiento){ //18: Centro Quirurgico , 20: Dosis Unitaria
        $datos=DB::table('farmacias')
                ->where('id_establecimiento','=',$id_establecimiento)
                ->whereIn('farmacias.id', [20])
                ->select('farmacias.*')
                ->get();
        return $datos;
    }

    /**
     * Del listado de este metodo GetFarmaciaByIdEstablecimiento se reduce por tipo de atencion y establecimiento
     * ya que la tabla "farmacia_tipo_atenciones" solo comtempla un tipo de atencion por el momento
     * Por ahora solo se usara para las interconsultas
     */
    public function GetFarmaciaPorTipoAtencionInterconsulta($id_establecimiento, $id_tipo_atencion) {
        $lista = $this->GetFarmaciaByIdEstablecimiento($id_establecimiento);
        $lista_return = [];

        $farmacias_hospitalizacion = [10, 21, 128,132];
        $farmacias_emergencia = [19,132];
        $farmacias_abl = [22];
        $farmacias_aqa = [107];
        $farmacias_ger = [14];
        $farmacias_chi = [111];

        foreach ($lista as $farmacia) {
            switch ($id_establecimiento) {
                case 76: // Hospital LNS
                    if ($id_tipo_atencion == 2): // Emergencia
                        foreach ($farmacias_emergencia as $f) {
                            if ($farmacia->id == $f) {
                                $lista_return[] = $farmacia;
                                break;
                            }
                        }
                    elseif ($id_tipo_atencion == 3): // Hospitalizacion
                        foreach ($farmacias_hospitalizacion as $f) {
                            if ($farmacia->id == $f) {
                                $lista_return[] = $farmacia;
                                break;
                            }
                        }
                    endif;
                    break;
                case 77: // Hospital ABL
                        foreach ($farmacias_abl as $f) {
                            if ($farmacia->id == $f) {
                                $lista_return[] = $farmacia;
                                break;
                            }
                        }
                    
                    break;
                case 75: // Hospital GER
                        foreach ($farmacias_ger as $f) {
                            if ($farmacia->id == $f) {
                                $lista_return[] = $farmacia;
                                break;
                            }
                        }
                    
                    break;
                case 26: // Hospital AQA
                        foreach ($farmacias_aqa as $f) {
                            if ($farmacia->id == $f) {
                                $lista_return[] = $farmacia;
                                break;
                            }
                        }
                    
                    break;
                     case 39: // Hospital CHI
                        foreach ($farmacias_chi as $f) {
                            if ($farmacia->id == $f) {
                                $lista_return[] = $farmacia;
                                break;
                            }
                        }
                    
                    break;
            }
        }

        return $lista_return;
    }
}


