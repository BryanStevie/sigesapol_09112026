<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use App\Models\Establecimiento;


class Parametros extends Model
{

  protected $table = 'parametros';

  public function getParametros_by_tipo($descrip_tipo_parametro)
  {
    $data = Parametros::where('descrip_tipo', $descrip_tipo_parametro)->where('estado', 'ACTIVO')->get();
    return $data;
  }

  public function getParametros_by_tipo_autocomplete($buscar, $descrip_tipo_parametro)
  {
    $cad = "select * from parametros where descrip_corta ilike '%" . $buscar . "%' and 
            estado='ACTIVO' and descrip_tipo='" . $descrip_tipo_parametro . "'  
            order By descrip_corta asc limit 50; ";
    $data = DB::select($cad);
    return $data;
  }

  public function getPersonal_convocado_by_medico_autocomplete($buscar, $id_establecimiento)
  {
    $cad = "select * from medicos
                where CONCAT (colegiatura, ' ', dni,' ', nombre, ' ',paterno, ' ', materno) ilike '%" . $buscar . "%' and  estado=1  
                and id_establecimiento = '" . $id_establecimiento . "' order By paterno asc limit 50;";
    $data = DB::select($cad);
    return $data;
  }

  public function getProfesional_autocomplete_by_dni($dni)
  {
    $id_establecimiento = Auth::user()->id_establecimiento;
    $datos = "select 
      medicos.id ,medicos.dni , medicos.colegiatura ,medicos.nombre ,medicos.paterno ,medicos.materno ,medicos.especialidad,
      medicos.id_establecimiento ,medicos.email ,medicos.rne ,medicos.id_especializacion ,medicos.tipo_contrato ,
      medicos.id_consultorio ,medicos.id_profesion , profesiones.nombre as profesion,medicos.estado ,medicos.id_user_created ,medicos.id_user_update ,
      medicos.id_user_activate ,medicos.fec_activate ,medicos.id_user_desactivate ,medicos.fec_desactivate ,
      medicos.nombre_firma ,medicos.tipo_documento , especializaciones.nombre as especializacion, establecimientos.nombre as ipress 
      from medicos as medicos 
      left join especializaciones on medicos.id_especializacion=especializaciones.id 
      left join establecimientos on medicos.id_establecimiento=establecimientos.id 
      left join profesiones on medicos.id_profesion=profesiones.id 
      where CONCAT (medicos.colegiatura, ' ', medicos.rne, ' ', medicos.dni,' ', medicos.nombre, ' ',medicos.paterno, ' ', medicos.materno) ilike '%" . $dni . "%' 
      and medicos.estado='1' and id_establecimiento = '" . $id_establecimiento . "' and especializaciones.id not in (7,8,15,40,31,34,47,43,63,22,62,64,21,54)  
      order By medicos.nombre asc limit 50";
    $data = DB::select($datos);

    return $data;
  }
  

  public function getTipo_etnias()
  {
    $cad = "select * from tipo_etnias where estado='ACTIVO' Order By id asc";
    $data = DB::select($cad);
    return $data;
  }
  public function getTipo_idiomas()
  {
    $cad = "select * from tipo_idiomas where estado='ACTIVO' Order By id asc";
    $data = DB::select($cad);
    return $data;
  }
  public function getTipo_estado_civil()
  {
    $cad = "select * from tipo_estado_civil where estado='ACTIVO' Order By id asc";
    $data = DB::select($cad);
    return $data;
  }
  public function getTipo_grados_instruccion()
  {
    $cad = "select * from tipo_grados_instruccion where estado='ACTIVO' Order By id asc";
    $data = DB::select($cad);
    return $data;
  }
  public function getUbigeo_autocomplete($buscar_ubigeo)
  {
    $datos = "select 
        departamentos.id_departamento as id_departamento,
        departamentos.descripcion as descripcion_departamento,
        provincias.id_provincia as id_provincia,
        provincias.descripcion as descripcion_provincia,
        distritos.id_distrito as id_distrito,
        distritos.descripcion as descripcion_distrito,
        distritos.codigo as codigo_ubigeo
        from departamentos as departamentos 
        inner join provincias as provincias on departamentos.id_departamento=provincias.id_departamento
        inner join distritos as distritos on provincias.id_provincia=distritos.id_provincia
        where CONCAT (distritos.codigo, ' ', distritos.descripcion) ilike '%" . $buscar_ubigeo . "%' 
        order By distritos.descripcion asc ";
    $data = DB::select($datos);
    return $data;
  }
  public function getIpress_origen_autocomplete($buscar_ipress_origen, $institucion)
  {
    $establecimiento = Establecimiento::where('id', Auth::user()->id_establecimiento)->first();

    if ($institucion == '-') {
      $cad = "select * from ipress where establecimiento ilike '%" . $buscar_ipress_origen . "%' 
                    and institucion != 'SANIDAD DE LA POLICIA NACIONAL DEL PERU' 
                    and institucion!='SIN ASIGNACION' and estado='ACTIVO' 
                    and codigo!='" . $establecimiento->codigo . "' order by establecimiento asc;";
    } else {
      $cad = "select * from ipress where establecimiento ilike '%" . $buscar_ipress_origen . "%'  
                    and institucion = 'SANIDAD DE LA POLICIA NACIONAL DEL PERU' 
                    and institucion!='SIN ASIGNACION' 
                    and estado='ACTIVO'  
                    and codigo!='" . $establecimiento->codigo . "' order by establecimiento asc;";
    }
    $data = DB::select($cad);
    return $data;
  }
  public function getValidar_existencia_ubigeo_by_codigo($codigo_ubigeo)
  {
    $cad = "select * from distritos where codigo='" . $codigo_ubigeo . "'";
    $data = DB::select($cad);
    return $data;
  }
  public function getIpress_origen_by_codigo($codigo, $institucion)
  {
    $establecimiento = Establecimiento::where('id', Auth::user()->id_establecimiento)->first();

    if ($institucion == '-') {
      $cad = "select * from ipress where codigo = '" . $codigo . "' 
                    and institucion != 'SANIDAD DE LA POLICIA NACIONAL DEL PERU' 
                    and institucion!='SIN ASIGNACION' and estado='ACTIVO' 
                    and codigo!='" . $establecimiento->codigo . "' order by establecimiento asc;";
    } else {
      $cad = "select * from ipress where codigo = '" . $codigo . "'  
                    and institucion = 'SANIDAD DE LA POLICIA NACIONAL DEL PERU' 
                    and institucion!='SIN ASIGNACION' 
                    and estado='ACTIVO'  
                    and codigo!='" . $establecimiento->codigo . "' order by establecimiento asc;";
    }
    $data = DB::select($cad);
    if ($data == null) {
      return null;
    } else {
      return $data[0];
    }
  }

  /**
   * Listar profesionales de un establecimiento indicado, excepto los profesionales con las especialidades indicadas en la condicion
   * Para Admision de Hospitalizacion.
  */
  public function getProfesionalesByIdEstablecimiento($id_establecimiento)
  {
    $datos = "select 
      medicos.id ,medicos.dni , medicos.colegiatura ,medicos.nombre ,medicos.paterno ,medicos.materno ,medicos.especialidad,
      medicos.id_establecimiento ,medicos.email ,medicos.rne ,medicos.id_especializacion ,medicos.tipo_contrato ,
      medicos.id_consultorio ,medicos.id_profesion , profesiones.nombre as profesion,medicos.estado ,medicos.id_user_created ,medicos.id_user_update ,
      medicos.id_user_activate ,medicos.fec_activate ,medicos.id_user_desactivate ,medicos.fec_desactivate ,
      medicos.nombre_firma ,medicos.tipo_documento , especializaciones.nombre as especializacion, medicos.id_establecimiento, establecimientos.nombre as ipress 
      from medicos as medicos 
      left join especializaciones on medicos.id_especializacion=especializaciones.id 
      left join establecimientos on medicos.id_establecimiento=establecimientos.id 
      left join profesiones on medicos.id_profesion=profesiones.id 
      where medicos.id_establecimiento = $id_establecimiento and medicos.estado='1' and especializaciones.id not in (7,8,15,40,31,34,47,43,63,22,62,64,21,54)  ";
    $data = DB::select($datos);

    return $data;
  }
  public function getParametros_detalle_by_id($id_categoria)
  {
   $datos = "select * from parametros_detalle where id_parametro = '" . $id_categoria . "' order by id asc;";
  
    $data = DB::select($datos);
    if ($data == null) {
      return '';
    } else {
      return $data;
    }
  }
}


