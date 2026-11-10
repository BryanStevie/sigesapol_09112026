<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Referencia extends Model
{

    public static function registrarReferencia($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico, $id_establecimiento_referencia, $id_uci,$id_uci_cv,$id_ambulancia,$id_ambulancia_ref,$id_tipo_ambulancia,$id_pasaje,$id_acompanante,$id_situacion,$observacion_referencia,$id_establecimiento)
    {
        $cad = "Select sp_insert_referencia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $id_medico, $id_establecimiento_referencia, $id_uci,$id_uci_cv,$id_ambulancia,$id_ambulancia_ref,$id_tipo_ambulancia,$id_pasaje,$id_acompanante,$id_situacion,$observacion_referencia,$id_establecimiento));
        return $data[0]->sp_insert_referencia;
    }

    public static function registrarefcref($id_prestacion, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_sub_consultorio,$observaciones, $fecha_atencion, $id_medico,$estado,$upss_referencia)
    {
        $cad = "Select sp_insert_registrarefcref(?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_sub_consultorio,$observaciones,$fecha_atencion,$id_medico,$estado,$upss_referencia));
        return $data[0]->sp_insert_registrarefcref;
    }

    public function buscar_asegurado_pasiente($p,$ipress)
  	{
    	$cad = "
      select a.id,a.nro_doc_ident,a.tipo_doc_ident,a.cip,a.nombre,a.paterno,a.materno,a.sexo,a.fecha_nac,ah.nro_historia,a.tipo_sangre,a.telefono,a.correo,
      ah.id_establecimiento,d.descripcion as distrito ,p.descripcion as provincia ,d2.descripcion as departamento,a.grado,a.id_tipo_beneficiario ,tb.descripcion as parentesco 
      from asegurados a
      inner join asegurado_historias ah on ah.id_asegurado = a.id 
       left join distritos d on a.id_distrito = d.id_distrito
       left join provincias p on p.id_provincia = a.id_provincia 
       left join departamentos d2 on d2.id_departamento = a.id_departamento 
       left join tipo_beneficiarios tb on tb.id = a.id_tipo_beneficiario 
      where a.id = '$p' and ah.id_establecimiento = '$ipress' and a.id_estado_reg = '1' and ah.id_estado_reg = '1'
      ";
      $data = DB::select($cad);
      $data = $data[0];
    	return $data;
    }

    public function buscar_procedimiento_referencia($p)
  	{
    	$cad = "
      select rp.observacion,p.codigo,p.descripcion  from referencia_procedimientos rp 
      left join procedimientos p on p.id = rp.id_procedimiento 
      where rp.id_referencia = '$p' and rp.id_estado_reg = '1'
      ";
      $data = DB::select($cad);

    	return $data;
    }

    public function listar_referencia_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_referencias', $p);
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

  public function GetReferenciaByIdPrestacion($id)
    {
      $cad = "select 
      t8.id as id_referencia,
      t8.id_prestacion,
      t8.id_asegurado,
      t8.id_tipo_atencion,
      t8.fecha_atencion,
      t8.id_medico_referencia,
      t8.id_establecimiento,
      t8.observacion_referencia,
      t8.fecha_firma_referencia,
      t8.ruta_referencia,
      t8.origen_referencia,
      t8.clasificacion_preliminar,
      t8.ups,
      t8.id_servicio,
      t8.recomendaciones,
      t8.condicion,
      t8.id_medico_contrareferencia,
      t8.fecha_firma_contrareferencia,
      t8.ruta_contrareferencia,
      t8.id_estado_reg,
      t8.flag,
      t8.id_evaluacion,
              t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              left join referencias t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);

       return $data[0];
    }

    public function GetContraReferenciaByIdPrestacion($id)
    {
      $cad = "select 
      t8.id as id_referencia,
      t8.id_prestacion,
      t8.id_asegurado,
      t8.id_tipo_atencion,
      t8.fecha_atencion,
      t8.id_medico_referencia,
      t8.id_establecimiento,
      t8.observacion_referencia,
      t8.fecha_firma_referencia,
      t8.ruta_referencia,
      t8.origen_referencia,
      t8.clasificacion_preliminar,
      t8.ups,
      t8.id_servicio,
      t8.recomendaciones,
      t8.condicion,
      t8.id_medico_contrareferencia,t8.fecha_firma_contrareferencia,t8.ruta_contrareferencia,t8.id_estado_reg,t8.flag,t8.id_evaluacion,t8.id_servicio_contrareferencia,T8.observacion_contrareferencia,
              t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              left join referencias t8 on t8.id_prestacion_contrareferencia = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion_contrareferencia=".$id."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);

       return $data[0];
    }

  public function GetReferenciaById($id_prestacion, $id_referencia)
    {
      $cad = "select 
              t8.*, t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              inner join referencias t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id_prestacion." 
               AND t8.id=".$id_referencia."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);
      return $data[0];
    }

    public function GetReferenciaByIdPrest($id_prestacion)
    {
      $datos = Referencia::where('id_prestacion', '=', $id_prestacion)->first();
      return $datos;
    }

    public function GetBuscaReferenciaById($id_referencia) {

        $cad = " select c3.nombre as nombre_servicio_origen,c3.id as id_servicio_origen,e.nombre as nombre_establecimiento,e.id as id_establecimiento, c.nombre as nombre_servicio,c2.nombre as nombre_servicio_contrareferencia,
                        r.*,m.nombre ||','||m.paterno ||' '||m.materno as nombre_doctor
                 from referencias r
                 left join establecimientos e on e.id = r.id_establecimiento
                 left join consultorios c on c.id = r.id_servicio
                 left join medicos m on m.id = r.id_medico_contrareferencia
                 left join consultorios c2 on c2.id = r.id_servicio_contrareferencia
                 left join prestaciones p on p.id = r.id_prestacion
                 left join sub_consultorios sc on sc.id = p.id_sub_consultorio
                 left join consultorios c3 on c3.id = sc.id_consultorio

                 where r.id = '$id_referencia' order by c.nombre desc"; 

        $data = DB::select($cad);
        return $data[0];
    }
    
    public function busca_servicio_por_ipres_origen_referencia($id_referencia) {

      $cad = "  select distinct c.id,c.nombre  from establecimientos e 
                inner join sub_consultorios sc on e.id = sc.id_establecimiento
                inner join consultorios c on c.id = sc.id_consultorio 
                where e.id_estado_reg = '1' and c.estado = '4' and e.id = '$id_referencia' order by c.nombre desc "; 

      $data = DB::select($cad);
      return $data;
  }

    public function ActualizaIdEvolucion($id, $id_evolucion) {

      $datos = Referencia::where('id', $id)->first();
      $datos->id_evolucion = $id_evolucion;
      $datos->save();
      
      return true;
  
  }

    public function busca_ipress_x_servicio($id_servicio,$id_establecimiento,$nivel) {

      $cad = " 
      select distinct e.id,e.codigo,e.nombre as nombre_ipress,e.nivel as nivel_ipres,c.nombre as nombre_servicio,c.id as id_servicio 
      from establecimientos e 
      inner join sub_consultorios sc on e.id = sc.id_establecimiento 
      inner join consultorios c on c.id = sc.id_consultorio 
      where e.nivel != 0 and e.codigo is not null and c.estado is not null and c.id not in  ('204','255','256','118','242','254','253') and
      c.id=".$id_servicio." and e.id != ".$id_establecimiento." and e.nivel>= ".$nivel." and e.nivel != '3' order by e.nivel asc
      ";
      $data = DB::select($cad);

return $data;
    
    }

    public function obtener_si_existe_referencia($tipodoc_beneficiario,$dni_beneficiario) {

      $cad = " 
      select a.flag_referencia,a.id_establecimiento_referido
      from asegurados a 
      where a.id_estado_reg = '1' and  a.id_tipo_doc_ident='$tipodoc_beneficiario' and  a.nro_doc_ident='$dni_beneficiario'
      ";
      $data = DB::select($cad);

return $data[0];
    
    }

    public function busca_ipress_x_servicio_contra($id_servicio,$id_establecimiento,$nivel) {

      $cad = " 
      select distinct e.id,e.codigo,e.nombre as nombre_ipress,e.nivel as nivel_ipres,c.nombre as nombre_servicio,c.id as id_servicio 
      from establecimientos e 
      inner join sub_consultorios sc on e.id = sc.id_establecimiento 
      inner join consultorios c on c.id = sc.id_consultorio 
      where e.nivel != 0 and e.codigo is not null and c.estado is not null and c.id not in  ('204','255','256','118','242','254','253') and
      c.id=".$id_servicio." and e.id != ".$id_establecimiento." and e.nivel<= ".$nivel." order by e.nombre asc
      ";
      $data = DB::select($cad);

return $data;
    
    }

    public function GetReferenciaByIdPrestacionEvaluacion($id_prestacion, $id_evaluacion)
    {
      $cad = "select 
              t8.*, t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              left join referencias t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id_prestacion." and t8.id_evaluacion=".$id_evaluacion."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);

       return $data[0];
    }

    public function GetReferenciaByIdEvaluacion($id_evaluacion)
    {
      $datos = Referencia::where('id_evaluacion', '=', $id_evaluacion)->first();
      return $datos;
    }

    public function GetDiagnosticoByIdPrestacion($id)
    {
  
      $cad = "select 
  where t1.id_prestacion=" . $id . "
  and t1.estado=1";
      
      $data = DB::select($cad);
      return $data;
    }

    public function GetMostrarReferenciaByIdPrestacion($id)
    {
      $cad = "select 
      t8.id as id_referencia,
      t8.id_prestacion,
      t8.id_asegurado,
      t8.id_tipo_atencion,
      t8.fecha_atencion,
      t8.id_medico_referencia,
      t8.id_establecimiento,
      t8.id_uci,
      t8.id_uci_cv,
      t8.id_ambulancia,
      t8.id_ambulancia_ref,
      t8.id_tipo_ambulancia,
      t8.id_pasaje,
      t8.id_acompanante,
      t8.id_situacion,
      t8.observacion_referencia,
      t8.fecha_firma_referencia,
      t8.ruta_referencia,
      t8.origen_referencia,
      t8.clasificacion_preliminar,
      t8.ups,
      t8.id_servicio,
      t8.recomendaciones,
      t8.condicion,
      t8.id_medico_contrareferencia,
      t8.fecha_firma_contrareferencia,
      t8.ruta_contrareferencia,
      t8.id_estado_reg,
      t8.flag,
      t8.id_evaluacion,
              t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              left join referencias t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);

       return $data[0];
    }

    public function obtenerDatosDeRefereanciaParaAtencionLabPorId($id_referencia)
    {
        $cad = "Select * from sp_obtener_datos_referencia_laboratorio(?)";
        $data = DB::select($cad, array($id_referencia));
        return $data[0];
    }
        public function get_historial_id_asegurado($id_asegurado)
  	{
    	$cad = "select r.id,a.tipo_doc_ident,a.nro_doc_ident,
      to_char(p.created_at , 'YYYY-MM-DD') dia,to_char(p.created_at , 'HH:MI AM') hora,
      e.nombre as nombre_establecimiento,ta.nombre as tipo_atencion,c.nombre as nombre_servicio,sc.descripcion consultorio,
      m.nombre ||','||m.paterno ||' '||m.materno as nombre_doctor,
      r.id_prestacion as id_prestacion 
      from referencias r
      inner join prestaciones p on r.id_prestacion=p.id
      inner join tipo_atenciones ta on r.id_tipo_atencion=ta.id
      inner join asegurados a on r.id_asegurado=a.id
      left join establecimientos e on e.id = r.id_establecimiento
      left join sub_consultorios sc on p.id_sub_consultorio=sc.id
      left join consultorios c on  sc.id_consultorio=c.id
      left join medicos m on m.id = r.id_medico_referencia 
      where r.id_asegurado='".$id_asegurado."'";
      $data = DB::select($cad);
    	return $data;
    }

    public function get_historial_dni_asegurado($dni_beneficiario)
  	{
    	$cad = "select r.id,a.tipo_doc_ident,a.nro_doc_ident,
      to_char(p.created_at , 'YYYY-MM-DD') dia,to_char(p.created_at , 'HH:MI AM') hora,
      e.nombre as nombre_establecimiento,ta.nombre as tipo_atencion,c.nombre as nombre_servicio,sc.descripcion consultorio,
      m.nombre ||','||m.paterno ||' '||m.materno as nombre_doctor,
      r.id_prestacion as id_prestacion 
      from referencias r
      inner join prestaciones p on r.id_prestacion=p.id
      inner join tipo_atenciones ta on r.id_tipo_atencion=ta.id
      inner join asegurados a on r.id_asegurado=a.id
      left join establecimientos e on e.id = r.id_establecimiento
      left join sub_consultorios sc on p.id_sub_consultorio=sc.id
      left join consultorios c on  sc.id_consultorio=c.id
      left join medicos m on m.id = r.id_medico_referencia 
      where a.nro_doc_ident='".$dni_beneficiario."' and a.id_estado_reg = '1'";
      $data = DB::select($cad);
    	return $data;
    }

    public function get_formato_fups_hospitalizacion($id_prestacion) {

      $cad = "select
	------------------NÚMERO DE FORMATO---------------------
	tp.codigo_renipress,
	right(tp.numero_lote::varchar,2),
	tp.numero_correlativo,
	---DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD---
	tp.codigo_renipress as codigo_unico_de_renipress,
	e.nombre as nombre_ipress_atencion,
	'INTRAMURAL' as lugar_de_atencion,
	ta.nombre as tipo_ingreso,
	--REFERENCIA--
	tp.ipress_referencia_fups as cod_ipress,
	e2.nombre as nombre_de_la_ipress,
	tp.hoja_referencia_fups as hoja_referencia,
	---------------DEL ASEGURADO / USUARIO------------------
	 case
		when tp.tipo_doc_identidad = '1' then 'DNI'
		when tp.tipo_doc_identidad = '2' then 'CE'
		when tp.tipo_doc_identidad = '3' then 'PAS'
		when tp.tipo_doc_identidad = '4' then 'DIE'
		when tp.tipo_doc_identidad = '5' then 'CUI'
		when tp.tipo_doc_identidad = '6' then 'CNV'
		when tp.tipo_doc_identidad = '7' then 'SDI'
	end as tdi,
	tp.paciente_nro_doc_identidad as nro_doc_ident,
	tp.id_hist_clinica as historia_clinica,
	case
		when a.sexo = 'F' then 'Femenino'
		when a.sexo = 'M' then 'Masculino'
	end as sexo,
	extract (day from a.fecha_nac::date) as dia_nac,
	extract (month from a.fecha_nac::date) as mes_nac,
	extract (year from a.fecha_nac::date) as anio_nac,
	extract (day from tp.fecha_fallece::date) as dia_fall,
	extract (month from tp.fecha_fallece::date) as mes_fall,
	extract (year from tp.fecha_fallece::date) as anio_fall,
	tp.paciente_apellido_paterno as ape_paterno,
	tp.paciente_apellido_materno as ape_materno,
	SUBSTRING(tp.paciente_nombres FROM 1 FOR POSITION(' ' IN paciente_nombres || ' ') - 1) AS primer_nombre,
	SUBSTRING(tp.paciente_nombres FROM POSITION(' ' IN paciente_nombres) + 1) AS otros_nombres,
	-------------SALUD MATERNA----------------------------
	case
		when tp.estado_paci_mujer_fups = '01' then 'Gestante'
		when tp.estado_paci_mujer_fups = '02' then 'No Gestante'
		when tp.estado_paci_mujer_fups = '03' then 'Puerpera'
	end as etapa,
	case
		when tp.fecha_parto_fups is null then extract(day from (tp.fecha_probable_parto_fups::date))
		else extract(day from (tp.fecha_parto_fups::date))
	end as dia_prob_part,
	case
		when tp.fecha_parto_fups is null then extract(month from (tp.fecha_probable_parto_fups::date))
		else extract(month from (tp.fecha_parto_fups::date))
	end as mes_prob_part,
	case
		when tp.fecha_parto_fups is null then extract(year from (tp.fecha_probable_parto_fups::date))
		else extract(year from (tp.fecha_parto_fups::date))
	end as mes_prob_part,
	------------DE LA ATENCION----------------------------
	ta.nombre as tipo_prestacion,
	null as dia,
	null as mes,
	null as anio,
	--NUMERO DE FUPS A VINCULAR--
	null as renipress,
	null as lote,
	null as correlativo,
	u.codigo as UPSS,
	u.descripcion_upss as descripcion,
	extract (day from emer.fecha_atencion::date) as dia_ingreso,
	extract (month from emer.fecha_atencion::date) as mes_ingreso,
	extract (year from emer.fecha_atencion::date) as anio_ingreso,
	extract (hour from emer.fecha_atencion) as hora_fecha_ingreso,
	extract (minute from emer.fecha_atencion) as min_fecha_ingreso,
	---CORTE ADMINISTRATIVO---
	extract (day from tp.fecha_inicio_adm::date) as dia_inicio_ca,
	extract (month from tp.fecha_inicio_adm::date) as mes_inicio_ca,
	extract (year from tp.fecha_inicio_adm::date) as anio_inicio_ca,
	extract (hour from tp.fecha_inicio_adm::date) as hora_inicio_ca,
	extract (minute from tp.fecha_inicio_adm::date) as min_inicio_ca,
	extract (day from tp.fecha_cierre_adm_fups::date) as dia_fin_ca,
	extract (month from tp.fecha_cierre_adm_fups::date) as mes_fin_ca,
	extract (year from tp.fecha_cierre_adm_fups::date) as anio_fin_ca,
	extract (hour from tp.fecha_cierre_adm_fups::date) as hora_fin_ca,
	extract (minute from tp.fecha_cierre_adm_fups::date) as min_fin_ca,
	extract (day from emer.fecha_alta_medica::date) as dia_fecha_Egreso,
	extract (month from emer.fecha_alta_medica::date) as mes_fecha_Egreso,
	extract (year from emer.fecha_alta_medica::date) as anio_fecha_Egreso,
	extract (hour from emer.hora_alta_medica) as hora_fecha_Egreso,
	extract (minute from emer.hora_alta_medica) as min_fecha_Egreso,
	---------------------SE REFIERE O CONTRAREFIERE--------
	case
		when r.flag = '1' then 'referencia'
		when r.flag = '2' then 'contrareferencia'
	end as tipo_destino,
	case
		when r.flag = '1' then ed.id
		when r.flag = '2'then ec.id
	end as codigo_renae,
	case
		when r.flag = '1' then ed.nombre
		when r.flag = '2'then ec.nombre
	end as codigo_nombre,
	case
		when r.flag = '1' then r.id
		when r.flag = '2'then r.id
	end as n_hoja_ref_contra,
	---------------RESPONSABLE DE LA ATENCION-----------------
	case
		when tp.id_tipodoc_ident = '1' then 'DNI'
		when tp.id_tipodoc_ident = '2' then 'CE'
		when tp.id_tipodoc_ident = '3' then 'PAS'
		when tp.id_tipodoc_ident = '4' then 'DIE'
		when tp.id_tipodoc_ident = '5' then 'CUI'
		when tp.id_tipodoc_ident = '6' then 'CNV'
		when tp.id_tipodoc_ident = '7' then 'SDI'
	end as tipo_doc,
	tp.numdoc_ident as nro_doc,
	(tp.doc_nombres || ' '|| tp.doc_ape_paterno|| ' '|| tp.doc_ape_materno) as nombre_responsable,
	cp.descripcion as tipo_prof,
	esp.nombre as especialidad,
	tp.num_rne as rne,
	tp.num_coleg as colegiatura,
	p.observacion as observaciones
from
	trama_prestacion as tp
inner join prestaciones as p on
	tp.id_prestacion = p.id
inner join establecimientos as e on
	p.id_establecimiento = e.id
inner join tipo_atenciones as ta on
	p.id_tipo_atencion = ta.id
left join establecimientos as e2 on
	tp.ipress_referencia_fups = e2.codigo
inner join asegurados as a on
	p.id_asegurado = a.id
inner join upsses as u on
	tp.id_upss_fups = u.codigo
left join referencias as r on
	p.id = r.id_prestacion
left join establecimientos as ed on
	r.id_establecimiento_des = ed.id
left join establecimientos as ec on
	r.id_establecimiento_contrareferencia = ec.id
left join colegio_profesionales as cp on
	tp.id_tipo_prof = cp.codigo
left join especializaciones as esp on
	tp.id_especialidad_prof = esp.cod_saludpol
left join emergencias as emer on
	p.id_ext = emer.id
where p.id ='".$id_prestacion."'";
      $data = DB::select($cad);
      return $data[0];
    }
    public function get_formato_fups($id_prestacion) {

      $cad = "select
      ------------------NÚMERO DE FORMATO---------------------
      tp.codigo_renipress as numero_formato_1,
      right(tp.numero_lote::varchar,2) as numero_formato_2,
      tp.numero_correlativo as numero_formato_3,
      ---DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD---
      tp.codigo_renipress as codigo_unico_de_renipress,
      e.nombre as nombre_ipress_atencion,
      'INTRAMURAL' as lugar_de_atencion,
      ta.nombre as tipo_ingreso,
      --REFERENCIA--
      tp.ipress_referencia_fups as cod_ipress,
      e2.nombre as nombre_de_la_ipress,
      tp.hoja_referencia_fups as hoja_referencia,
      ---------------DEL ASEGURADO / USUARIO------------------
       case
        when tp.tipo_doc_identidad = '1' then 'DNI'
        when tp.tipo_doc_identidad = '2' then 'CE'
        when tp.tipo_doc_identidad = '3' then 'PAS'
        when tp.tipo_doc_identidad = '4' then 'DIE'
        when tp.tipo_doc_identidad = '5' then 'CUI'
        when tp.tipo_doc_identidad = '6' then 'CNV'
        when tp.tipo_doc_identidad = '7' then 'SDI'
      end as tdi,
      tp.paciente_nro_doc_identidad as nro_doc_ident,
      tp.id_hist_clinica as historia_clinica,
      case
        when a.sexo = 'F' then 'Femenino'
        when a.sexo = 'M' then 'Masculino'
      end as sexo,
      extract (day from a.fecha_nac::date) as dia_nac,
      extract (month from a.fecha_nac::date) as mes_nac,
      extract (year from a.fecha_nac::date) as anio_nac,
      extract (day from tp.fecha_fallece::date) as dia_fall,
      extract (month from tp.fecha_fallece::date) as mes_fall,
      extract (year from tp.fecha_fallece::date) as anio_fall,
      tp.paciente_apellido_paterno as ape_paterno,
      tp.paciente_apellido_materno as ape_materno,
      SUBSTRING(tp.paciente_nombres FROM 1 FOR POSITION(' ' IN paciente_nombres || ' ') - 1) AS primer_nombre,
      SUBSTRING(tp.paciente_nombres FROM POSITION(' ' IN paciente_nombres) + 1) AS otros_nombres,
      -------------SALUD MATERNA----------------------------
      case
        when tp.estado_paci_mujer_fups = '01' then 'Gestante'
        when tp.estado_paci_mujer_fups = '02' then 'NoGestante'
        when tp.estado_paci_mujer_fups = '03' then 'Puerpera'
      end as etapa,
      case
        when tp.fecha_parto_fups is null then extract(day from tp.fecha_probable_parto_fups::date)
        else extract(day from tp.fecha_parto_fups::date)
      end as dia_prob_part,
      case
        when tp.fecha_parto_fups is null then extract(month from tp.fecha_probable_parto_fups::date)
        else extract(month from tp.fecha_parto_fups::date)
      end as mes_prob_part,
      case
        when tp.fecha_parto_fups is null then extract(year from tp.fecha_probable_parto_fups::date)
        else extract(year from tp.fecha_parto_fups::date)
      end as anio_prob_part,
      ------------DE LA ATENCION----------------------------
      ta.id as tipo_prestacion,
      null as dia, --FECHA ATENCION/PRESTACION
      null as mes,--FECHA ATENCION/PRESTACION
      null as anio,--FECHA ATENCION/PRESTACION
      null as anio,--FECHA ATENCION/PRESTACION
      --NUMERO DE FUPS A VINCULAR--
      tp.renipress_vincula_fups as vincular_renipress,
      tp.num_lote_vincula_fups as vincular_lote,
      tp.num_vincula_fups as vincular_correlativo,
      u.codigo as UPSS,
      u.descripcion_upss as descripcion,
      extract (day from emer.fecha_atencion::date) as dia_ingreso,
      extract (month from emer.fecha_atencion::date) as mes_ingreso,
      extract (year from emer.fecha_atencion::date) as anio_ingreso,
      extract (hour from emer.fecha_atencion) as hora_fecha_ingreso,
      extract (minute from emer.fecha_atencion) as min_fecha_ingreso,
      ---CORTE ADMINISTRATIVO---
	extract (day from tp.fecha_inicio_adm::date) as dia_inicio_ca,
	extract (month from tp.fecha_inicio_adm::date) as mes_inicio_ca,
	extract (year from tp.fecha_inicio_adm::date) as anio_inicio_ca,
	extract (hour from tp.fecha_inicio_adm::date) as hora_inicio_ca,
	extract (minute from tp.fecha_inicio_adm::date) as min_inicio_ca,
	extract (day from tp.fecha_cierre_adm_fups::date) as dia_fin_ca,
	extract (month from tp.fecha_cierre_adm_fups::date) as mes_fin_ca,
	extract (year from tp.fecha_cierre_adm_fups::date) as anio_fin_ca,
	extract (hour from tp.fecha_cierre_adm_fups::date) as hora_fin_ca,
	extract (minute from tp.fecha_cierre_adm_fups::date) as min_fin_ca,
	extract (day from emer.fecha_alta_medica::date) as dia_fecha_Egreso,
	extract (month from emer.fecha_alta_medica::date) as mes_fecha_Egreso,
	extract (year from emer.fecha_alta_medica::date) as anio_fecha_Egreso,
	extract (hour from emer.hora_alta_medica) as hora_fecha_Egreso,
	extract (minute from emer.hora_alta_medica) as min_fecha_Egreso,
      ---------------------SE REFIERE O CONTRAREFIERE--------
      case
        when r.flag = '1' then 'referencia'
        when r.flag = '2' then 'contrareferencia'
      end as tipo_destino,
      case
        when r.flag = '1' then ed.id
        when r.flag = '2'then ec.id
      end as codigo_renae,
      case
        when r.flag = '1' then ed.nombre
        when r.flag = '2'then ec.nombre
      end as codigo_nombre,
      case
        when r.flag = '1' then r.id
        when r.flag = '2'then r.id
      end as n_hoja_ref_contra,
      ---------------RESPONSABLE DE LA ATENCION-----------------
      case
        when tp.id_tipodoc_ident = '1' then 'DNI'
        when tp.id_tipodoc_ident = '2' then 'CE'
        when tp.id_tipodoc_ident = '3' then 'PAS'
        when tp.id_tipodoc_ident = '4' then 'DIE'
        when tp.id_tipodoc_ident = '5' then 'CUI'
        when tp.id_tipodoc_ident = '6' then 'CNV'
        when tp.id_tipodoc_ident = '7' then 'SDI'
      end as tipo_doc,
      tp.numdoc_ident as nro_doc,
      (tp.doc_nombres || ' '|| tp.doc_ape_paterno|| ' '|| tp.doc_ape_materno) as nombre_responsable,
      cp.descripcion as tipo_prof,
      esp.nombre as especialidad,
      tp.num_rne as rne,
      tp.num_coleg as colegiatura,
      p.observacion as observaciones
    from
      trama_prestacion as tp
    inner join prestaciones as p on
      tp.id_prestacion = p.id
    inner join establecimientos as e on
      p.id_establecimiento = e.id
    inner join tipo_atenciones as ta on
      p.id_tipo_atencion = ta.id
    left join establecimientos as e2 on
      tp.ipress_referencia_fups = e2.codigo
    inner join asegurados as a on
      p.id_asegurado = a.id
    inner join upsses as u on
      tp.id_upss_fups = u.codigo
    left join referencias as r on
      p.id = r.id_prestacion
    left join establecimientos as ed on
      r.id_establecimiento_des = ed.id
    left join establecimientos as ec on
      r.id_establecimiento_contrareferencia = ec.id
    left join colegio_profesionales as cp on
      tp.id_tipo_prof = cp.codigo
    left join especializaciones as esp on
      tp.id_especialidad_prof = esp.cod_saludpol
    left join emergencias as emer on
      p.id_ext = emer.id
    where p.id ='".$id_prestacion."'";
      $data = DB::select($cad);
      return $data[0];
    }
    public function sp_formato_fups_por_id_prestacion($p)
  	{
    	return $this->readFunctionPostgres('sp_formato_fups_por_id_prestacion', $p);
  	}
    public function fups_nacidos($id_prestacion)
  	{
    	$cad = " select  pre.id,n.num_doc 
                from puerperios as p 
                inner join natis as n on p.id = n.id_puerperio
                inner join emergencias as e on e.id_puerperio = p.id inner join prestaciones as pre on pre.id_ext = e.id
                where pre.id = '".$id_prestacion."'";
      $data = DB::select($cad);
    	return $data;
    }
    public function fups_diagnosticos($id_prestacion)
  	{
    	$cad = " select
      td.id_cie10,
      d.nombre,
      td.id_identificador_diag ,
      case
      when td.id_tipo_diag = '1' then 'Presuntivo'
      when td.id_tipo_diag = '2' then 'Definitivo'
      when td.id_tipo_diag = '3' then 'Repetido'
      end as tipo_diagnostico
      from
      trama_diagnosticos as td
      inner join diagnosticos as d on
      td.id_diagnostico = d.id
      inner join trama_prestacion as tp on
      tp.id = td.id_trama_prestacion
      where tp.id_prestacion ='".$id_prestacion."'";
      $data = DB::select($cad);
    	return $data;
    }
    public function fups_productos_famaceuticos($id_prestacion){
      $sql = "select
              tp.id_prod_med as codigo,
              p.nombre as nombre,
              u.nombre as presentacion,
              p.form_farm as forma_farmaceutica,
              p.concentracion as concentracion,
              tp.cantidad_prescrita as preescrito,
              tp.cantidad_entregada as entregado,
              tp.id_cie10 as dx
            from
              trama_productos as tp
            inner join productos as p on
              tp.id_prod_med = p.codigo
            left join unidades as u on
              p.id_unidad = u.id
            inner join trama_prestacion as tpre on
              tpre.id = tp.id_trama_prestacion
            where p.codigo like '%PF%' and tpre.id_prestacion = '".$id_prestacion."'";
      $data = DB::select($sql);
    	return $data;
    }
    public function fups_dispositivos_medicos($id_prestacion){
      $sql = "select
              tp.id_prod_med as codigo,
              p.nombre as nombre,
              u.nombre as presentacion,
              p.descripcion_siga as caracteristica,
              tp.cantidad_prescrita as preescrito,
              tp.cantidad_entregada as entregado,
              tp.id_cie10 as DX
              from
                trama_productos as tp
              inner join productos as p on
                tp.id_prod_med = p.codigo
              left join unidades as u on
                p.id_unidad = u.id
              inner join trama_prestacion as tpre on
                tpre.id = tp.id_trama_prestacion
              where tp.id_prod_med not like '%PF%' and tpre.id_prestacion ='".$id_prestacion."'";
      $data = DB::select($sql);
    	return $data;
    }
    public function fups_procedimientos($id_prestacion){
        $sql = "select
                tp.id_proced as codigo,
                p.descripcion as nombre_procedimiento,
                tp.cantidad_indicada as indicado,
                tp.cantidad_ejecutada as ejecutado,
                tp.id_cie10 as DX
                from
                trama_procedimientos as tp
                inner join procedimientos as p on
                tp.id_proced = p.codigo
                inner join trama_prestacion as tpre on
                tpre.id = tp.id_trama_prestacion
                where tpre.id_prestacion ='".$id_prestacion."'";
      $data = DB::select($sql);
      return $data;
    }

}


