<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class Paciente extends Model
{
   protected $table = 'asegurados';
   
   public function listar_pacientes_ajax($p)
  {
    return readFunctionPostgres('sp_listar_pacientes', $p);
  }
  
 
   
   public function ShowAllPacientes(){
    $cad = "select ase.id, ase.id_tipo_doc_ident, ase.tipo_doc_ident, ase.nro_doc_ident, ase.cip, ase.nombre, ase.paterno, ase.materno, ase.sexo, ase.fecha_nac,
			ase.telefono, ase.domicilio, ase.correo, ase.id_estado_reg as estado
			from asegurados ase where ase.id_estado_reg='1'
			ORDER BY id DESC FETCH FIRST 100 ROWS ONLY";
    $data = DB::select($cad);
    return $data;
  }  
  
  public function paciente_yaregistrado($dni, $id_ipress, $id_tipo_doc_ident, $id_asegurado)
  {   
	 $cad = "select ase.id, ase.id_tipo_doc_ident, ase.tipo_doc_ident, ase.nro_doc_ident, ase.cip, ase.nombre, ase.paterno, ase.materno, ase.sexo, ase.fecha_nac,
			ase.telefono, ase.domicilio, ase.correo,
			his.id_asegurado, his.id_establecimiento, his.nro_historia, his.id_estado_reg as estado,
			est.nombre as establecimiento, his.id as id_asegurado_historia
			from asegurados ase
			Inner Join asegurado_historias his on ase.id = his.id_asegurado
			Inner Join establecimientos est On his.id_establecimiento = est.id			
			where ase.id_estado_reg='1' and his.id_estado_reg='1' ";
		if(trim($dni)!=""){
			$cad .= " and ase.nro_doc_ident='$dni' ";
		}
		if(trim($id_ipress)!=""){
			$cad .= " and his.id_establecimiento='$id_ipress' ";
		}
		if(trim($id_tipo_doc_ident)!=""){
			$cad .= " and ase.id_tipo_doc_ident='$id_tipo_doc_ident' ";
		}
		if(trim($id_asegurado)!=""){
			$cad .= " and his.id_asegurado='$id_asegurado' ";
		}
		$cad .= " ORDER BY id DESC FETCH FIRST 100 ROWS ONLY";
		
    $data = DB::select($cad);  
     return $data;
  }
  
  public function GetAseguradoById($id)
  {
    $datos = DB::table('asegurados')
      ->where('asegurados.id', '=', $id)
      ->select('asegurados.*')
      ->where('asegurados.id_estado_reg', '=', 1)
      ->first();
    //->get();
    return $datos;
  }

  public function GetCustomAseguradoById($id_asegurado, $id_establecimiento)
  {
    $cad = "select *, ah.nro_historia, tb.descripcion as parentesco  
    from asegurados a 
    inner join asegurado_historias ah on a.id = ah.id_asegurado
    left join tipo_beneficiarios tb on tb.id = a.id_tipo_beneficiario
    where a.id = ". $id_asegurado ." and ah.id_establecimiento = ". $id_establecimiento;
    $data = DB::select($cad);
    return $data[0];
  }

  public function GetAseguradosSoloDNI($nro_doc_ident)
  {
    $datos = DB::table('asegurados')
      ->where('asegurados.nro_doc_ident', '=', $nro_doc_ident) 
      ->select('asegurados.*')
      ->where('asegurados.id_estado_reg', '=', 1)
      ->first();
    //->get();
    return $datos;
  }
  public function GetAsegurados_historiaSoloDNI($id_asegurado, $id_establecimiento)
  {
    $datos = DB::table('asegurado_historias')
    ->where('asegurado_historias.id_asegurado', '=', $id_asegurado)
    ->where('asegurado_historias.id_establecimiento', '=', $id_establecimiento)
      ->select('asegurado_historias.*')
      ->first(); 
    return $datos;
  }
  
  public function GetAseguradoByDNI($dni, $id_tipo_doc_ident)
  {
    $datos = DB::table('asegurados')
    ->join('asegurado_historias', 'asegurado_historias.id_asegurado', '=', 'asegurados.id')
      ->where('asegurados.id_estado_reg', '=', 1)
      ->where('asegurados.nro_doc_ident', '=', $dni)
	  ->where('asegurados.id_tipo_doc_ident', '=', $id_tipo_doc_ident)
      ->select('asegurados.*','asegurado_historias.nro_historia')
      ->first();
    return $datos;
  }	
	
  public function UpdateRecetaVale($dni_beneficiario, $id_establecimiento, $nro_historia){
	$cad = "UPDATE receta_vales SET nro_historia='$nro_historia'  
			where dni_beneficiario='$dni_beneficiario' and (select far.id_establecimiento from farmacias far where far.id=receta_vales.id_farmacia)='$id_establecimiento';";
	$data = DB::select($cad);  
	return $data;
  }


  public function ActualizaPaciente($paciente)
  {
    $cad = "update asegurados set foto = '{$paciente->foto}', id_tipo_beneficiario = {$paciente->id_tipo_beneficiario} where id = {$paciente->id}";
    DB::select($cad);
  }
  
  public function antecedentepacientes(){
        return $this->belongsToMany('App\Models\Tipo_antecedente');
  }

  public function listar_paciente_ajax($p){
		return $this->readFunctionPostgres('sp_listar_paciente',$p);
	}

  public function readFunctionPostgres($function, $parameters = null){
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

  public function getById($id)
  {
    $cad = "SELECT
          a.id id_asegurado,
          a.nro_doc_ident,
          a.nombre asegurado_nombre,
          a.paterno asegurado_paterno,
          a.materno asegurado_materno,
          a.sexo,
          a.fecha_nac,
          tb.descripcion parentesco,
          tdi.descripcion tipo_documento
          FROM asegurados a
          LEFT JOIN tipo_beneficiarios tb ON a.id_tipo_beneficiario = tb.id
          LEFT JOIN tipo_documento_identidad tdi ON a.id_tipo_doc_ident = tdi.id
          WHERE a.id=$id";

    $data = DB::select($cad);
    return $data[0];
  }

  public function GetAseguradoByIdTipoDocumentoYNroDocumento($id_tipo_doc_ident, $nro_doc_ident)
  {
    $datos = DB::table('asegurados')
      ->leftJoin('tipo_beneficiarios', 'asegurados.id_tipo_beneficiario','=','tipo_beneficiarios.id')
      ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
      ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
      ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
      ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
      ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
      ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
      ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
      ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
      ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
      ->where('asegurados.id_estado_reg', '=', 1)
      ->where('asegurados.nro_doc_ident', '=', $nro_doc_ident)
	    ->where('asegurados.id_tipo_doc_ident', '=', $id_tipo_doc_ident)
      ->select('asegurados.*', 'tipo_beneficiarios.descripcion as parentesco','distritos.id_distrito','provincias.id_provincia','departamentos.id_departamento',
      'distrito_procedencia.id_distrito as id_distrito_procedencia','provincia_procedencia.id_provincia as id_provincia_procedencia','departamento_procedencia.id_departamento as id_departamento_procedencia',
      'distrito_nacimiento.id_distrito as id_distrito_nacimiento','provincia_nacimiento.id_provincia as id_provincia_nacimiento','departamento_nacimiento.id_departamento as id_departamento_nacimiento',
      DB::raw("to_char(age(TO_DATE(asegurados.fecha_nac, 'DD/MM/YYYY')), 'YY \"AÑOS\" \"CON\" MM \"MESES\" \"Y\" DD \"DIAS\"') as edad_detallada"),
      DB::raw("DATE_PART('YEAR',AGE(TO_DATE(asegurados.fecha_nac, 'DD/MM/YYYY'))) as edad"))
      ->first();
    return $datos;
  }

  public function GetAllRazas()
  {
    $datos = DB::table('razas')
      ->where('estado', '=', '1')
      ->orderBy('nombre','asc')
      ->get();
    return $datos;
  }

  public function GetAllTipoSangres()
  {
    $datos = DB::table('tipo_sangres')
      ->where('estado', '=', '1')
      ->orderBy('descripcion','asc')
      ->get();
    return $datos;
  }

  public function GetAllEstadoCiviles()
  {
    $datos = DB::table('estado_civiles')
      ->where('estado', '=', '1')
      ->orderBy('nombre','asc')
      ->get();
    return $datos;
  }

  public function GetAllReligiones()
  {
    $datos = DB::table('religiones')
      ->where('estado', '=', '1')
      ->orderBy('nombre','asc')
      ->get();
    return $datos;
  }

  public function GetAllGradoInstrucciones()
  {
    $datos = DB::table('grado_instrucciones')
      ->where('estado', '=', '1')
      ->orderBy('nombre','asc')
      ->get();
    return $datos;
  }

  public function GetAllOcupaciones()
  {
    $datos = DB::table('ocupaciones')
      ->where('estado', '=', '1')
      ->orderBy('nombre','asc')
      ->get();
    return $datos;
  }
  public function geaAseguradoby_id_signos_vitales($id_asegurado)
  {
    $dato = "select   e2.id as id_enfermeria,
    e.id as id_emergencia, ts.descripcion_corta as tipo_sangre,tb.descripcion as parentesgo,to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"A\"')  as edad_corta,
    to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"') 
    as fec_nacim_descrip,LTRIM( asegurado_historias.nro_historia) as nro_historia, asegurados.*
    FROM asegurados INNER JOIN asegurado_historias ON asegurado_historias.id_asegurado = asegurados.id
    left join tipo_beneficiarios tb on tb.id =asegurados.id_tipo_beneficiario
    left join tipo_sangres ts on asegurados.id_tipo_sangre = ts.id 
    left join emergencias e on asegurados.id=e.id_asegurado 
    left join enfermerias e2 on e2.id_ext_enfermeria =e.id 
    WHERE asegurados.id_estado_reg='1' AND asegurados.id='$id_asegurado'";

    $data = DB::select($dato);
    if ($data != null || count($data) > 0):
      return $data[0];
    else:
      return null;
    endif; 
  }
  public function geaAseguradoby_id($id_asegurado)
  {
    $dato = "select 
      asegurados.*,
      e2.id_ext_enfermeria as id_enfermeria,
      e.id as id_emergencia,
      ts.descripcion_corta as tipo_sangre,
      tb.descripcion as parentesco,
      LTRIM( asegurado_historias.nro_historia) as nro_historia
    FROM asegurados INNER JOIN asegurado_historias ON asegurado_historias.id_asegurado = asegurados.id
    left join tipo_beneficiarios tb on tb.id =asegurados.id_tipo_beneficiario 
    left join tipo_sangres ts on asegurados.id_tipo_sangre = ts.id 
    left join emergencias e on asegurados.id=e.id_asegurado 
    left join enfermerias e2 on e2.id_ext_enfermeria = e.id
    WHERE asegurados.id_estado_reg='1' AND asegurados.id = '$id_asegurado'";
    
    $data = DB::select($dato);
    if ($data != null || count($data) > 0):
      return $data[0];
    else:
      return null;
    endif; 
  }

  public function GetPrestacionById_aseg($id)  {
		$cad = "
		select 
			p.id,p.id_asegurado,asg.nombre asegurado_nombre,asg.paterno asegurado_paterno,asg.materno asegurado_materno,ah.nro_historia,asg.sexo,p.condicion,asg.fecha_nac, date_part('year',age(to_date(to_char(asg.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, 
			ci.parentesco,asg.grado,asg.tipo_doc_ident,p.id_ext,p.temperatura, p.sa02_porcentaje, p.presion_arterial_x, p.presion_arterial_y, p.talla_cm, p.tamano_cintura_cm, p.frecuencia_respiratoria, p.peso_kg, p.frecuencia_cardiaca_fetal, p.frecuencia_cardiaca, 
			p.fi02, p.motivo_visita, p.tiempo_enfermedad, p.examen_clinico, p.examen_auxiliar, p.tratamiento_auxiliar, p.plan_trabajo,p.antecedente_familiar, p.antecedente,asg.id_asegurado_titular,asg.cip,asg.situacion_titular,asg.discapacidad,asg.discapacidad_carnet,
      asg.nro_carnet_conadis,asg.color_carnet_conadis, asg.presc_producto_apoyo,asg.usa_product_apoyo,asg.tipo_producto,asg.nro_tipo_producto, asg.nro_doc_ident,p.ipress,  p.upss,(CASE WHEN ups.codigo IS NOT NULL THEN ups.descripcion_upss else '-' END) as nombre_upss,
			p.fecha_alta,p.id_motivo_alta,p.id_estado_reg, p.id_establecimiento,e.nombre establecimiento,p.id_sub_consultorio,sc.id_consultorio,c.nombre servicio,sc.descripcion consultorio,p.id_medico,m.nombre medico_nombre,m.paterno medico_paterno,m.materno medico_materno, 
			p.id_modalidad, m.colegiatura colegiatura_medico, dni,colegiatura,rne,esp.nombre especializacion,prof.nombre profesion,rv.id id_receta,to_char(p.fecha_atencion,'dd-mm-YYYY')fecha_atencion,ta.nombre tipo_atencion,anamnesis,examen_fisico,p.aspectos_generales,
			c1.nombre servicio_derivar,p.id_cita , p.fecha_firma, p.id_tipo_atencion, p.id_ext, p.codigo_upss, p.registrar_cita_por_medico, asg.correo, asg.domicilio, asg.telefono, asg.telefono2, p.fecha_hora_update, p.motivo_update,p.informe_pp,asg.nombre_titular,asg.paterno_titular,
			asg.materno_titular,asg.dni_titular as dni_titular,asg.grado as grado_titular ,ci.codigo_siteds,p.t_enfermedad,p.periodo,p.tiempo_enfermedad
		FROM prestaciones p
			inner join tipo_atenciones ta on p.id_tipo_atencion=ta.id 
			left join upsses ups on p.codigo_upss=ups.codigo
			inner join asegurados asg on p.id_asegurado = asg.id 
			inner join asegurado_historias ah on p.id_asegurado = ah.id_asegurado And p.id_establecimiento = ah.id_establecimiento 
			inner join establecimientos e on p.id_establecimiento=e.id
			inner join sub_consultorios sc on p.id_sub_consultorio=sc.id
			inner join consultorios c on sc.id_consultorio=c.id
			inner join medicos m on p.id_medico=m.id
			left join especializaciones esp on m.id_especializacion=esp.id
			left join profesiones prof on m.id_profesion=prof.id
			left join receta_vales rv on p.id=rv.id_prestacion And rv.estado<>0 
			left join citas ci on p.id_cita=ci.id
			left join citas ci1 on p.id_cita=ci1.id_parent_cita
			left join consultorios c1 on ci1.id_sub_consultorio=c1.id
		Where p.id=" . $id;
		$data = DB::select($cad);
		return $data[0];
	}
}