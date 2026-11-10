<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Distrito extends Model {
    protected $table = 'distritos';

    function ShowDepartamento(){
        $cad = "select codigo,  descripcion,    id_departamento,    codigodep_inei from departamentos";
        $data = DB::select($cad);
        return $data;
    }  

    function ShowProvincia($id_departamento){
        $cad = "select codigo,  descripcion,    id_provincia,   id_departamento,    codigoprov_inei from provincias
                where id_departamento = ".$id_departamento." ";
        $data = DB::select($cad);
        return $data;
    }  

    function ShowDistrito($id_provincia){
        $cad = "select codigo,  descripcion,    id_distrito,    id_provincia,   codigo_inei from distritos
                where  id_provincia = ".$id_provincia." ";
        $data = DB::select($cad);
        return $data;
    }    

    public function getObtenerId_by_descripcion_and_iProvincia($id_provincia,$descripcion_distrito)
    {
      $data = DB::table('distritos')
        ->select('distritos.*')
        ->where('distritos.id_provincia', $id_provincia) 
        ->where('distritos.descripcion', $descripcion_distrito)
        ->get();
      return $data;
    }
    public function getObtenerUbigeo_by_codigo_ubigeo($codigo_ubigeo)
    {
      $cad ="select

      dis.id_distrito as id_distrito,
      dis.descripcion as descripcion_distrito,
      pro.id_provincia as id_provincia,
      pro.descripcion as descripcion_provincia,
      dep.id_departamento as id_departamento,
      dep.descripcion as descripcion_departamento
 
      from distritos as dis

      inner join provincias pro on dis.id_provincia = pro.id_provincia 
      inner join departamentos dep on pro.id_departamento = dep.id_departamento
      
      where dis.codigo='".$codigo_ubigeo."'";
      $data = DB::select($cad);
      return $data;
    }
    public function getObtenerUbigeo_by_id_distrito($id_distrito)
    {
      $cad ="select

      dis.id_distrito as id_distrito,
      dis.descripcion as descripcion_distrito,
      pro.id_provincia as id_provincia,
      pro.descripcion as descripcion_provincia,
      dep.id_departamento as id_departamento,
      dep.descripcion as descripcion_departamento
 
      from distritos as dis

      inner join provincias pro on dis.id_provincia = pro.id_provincia 
      inner join departamentos dep on pro.id_departamento = dep.id_departamento
      
      where dis.id_distrito='".$id_distrito."'";
      $data = DB::select($cad);
      return $data;
    }
}