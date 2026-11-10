<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auditores;
use App\Models\Auditor_procedimientos;
use App\Models\Auditor_diagnosticos;
use App\Models\Auditor_clinicos;
use App\Models\Auditor_afiliacion;
use App\Models\Auditor_productos;
use DB;

class Auditoria extends Model
{

  public function codigpaquete($id_cups)
  {
    $dato = "SELECT observaciones, COUNT(*)
FROM trama_prestacion tp 
WHERE id_estado <> 0 and observaciones is not null
GROUP BY observaciones order by observaciones desc";
$dato = DB::select($dato);
    return $dato;
  }
  
  public function GetreglasRC_01($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_rc_01_trama', $p);
  }
  public function GetreglasRC_02($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_rc_02_trama', $p);
  }
  public function GetreglasRC_03($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_rc_03_trama', $p);
  }
  public function Getreglas2($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_2_trama', $p);
  }
  public function Getreglas3($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_3_trama', $p);
  }
  public function Getreglas4($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_4_trama', $p);
  }
  public function Getreglas5($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_5_trama', $p);
  }
  public function Getreglas6($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_6_trama', $p);
  }
  public function Getreglas7($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_7_trama', $p);
  }
  public function Getreglas8($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_8_trama', $p);
  }
  public function Getreglas9($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_9_trama', $p);
  }
  public function Getreglas10($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_10_trama', $p);
  }
  public function Getreglas11($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_11_trama', $p);
  }

  public function Getreglas13($id_cups,$tipo_atencion)
  {
    $p[] = $id_cups;
    $p[] = $tipo_atencion;
    return $this->readFunctionPostgres('sp_regla_13_trama', $p);
  }
  public function listar_auditoria_cups_ajax($p)
  {
    return $this->readFunctionPostgres('sp_listar_auditoria_cups', $p);
  }
  
    public function listar_referencia_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_referencias', $p);
  	}

    public function listar_auditoria_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_auditoria', $p);
  	}	

    public function listar_auditoria_hospi_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_auditoria_hodpitalizacion', $p);
  	}	

    public function listar_auditoria_ambu_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_auditoria_ambulatorio', $p);
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

  public function   GetReporteAuditoriaGeneral($date,$id_user, $anio, $mes, $tipo_atencion_detalle, $ipress, $estado_trama, $cod_paquete, $resultado)
  {
    $cad = "SELECT tp.*,cp.id as id_cups,cp.codigo_ipress,
				cp.año,cp.intramural_extramural,e.nombre as nombre_ipress,
				cp.no_referidos_referidos,cp.tipo_atencion,cp.id_ext
         FROM trama_prestacion tp
            inner join cups_pnp cp on cp.id = tp.id_cups
            inner join establecimientos e on e.codigo = tp.codigo_renipress";

            $cad .= " WHERE tp.id_estado != '6' AND tp.id_estado = $estado_trama";
           
    if ($anio != ""):
        $cad .= "AND  tp.numero_lote = '$anio' ";
    endif;
    if ($mes != ""):
        $cad .= "  AND EXTRACT(MONTH FROM TO_DATE(tp.fecha_atencion_fups, 'DD/MM/YYYY')) ='$mes' ";
    endif;
    if ($tipo_atencion_detalle !=0):
      $cad .= " AND cp.tipo_atencion = '$tipo_atencion_detalle' ";
    endif;
    if ($ipress !=0):
      $cad .= "AND tp.codigo_renipress = '$ipress'";
    endif;

    $cad .= " ORDER BY tp.id ";

    $data = DB::select($cad);
    
    foreach($data as $p){
      $precio = 0;
      $proc = "SELECT cpms.valorizacion FROM trama_procedimientos cpms WHERE cpms.id_trama_prestacion = '$p->id'";
      $proc = DB::select($proc);

      $prod = "SELECT prod.valorizacion FROM trama_productos prod WHERE prod.id_trama_prestacion = '$p->id'";
      $prod = DB::select($prod);

      foreach($proc as $pc){
        $precio = $precio + $pc->valorizacion;
      }
      foreach($prod as $pd){
        $precio = $precio + $pd->valorizacion;
      }
      $p->precio = $precio;
    }
    return $data;
  }

    public function getTipoBeneficiarioTitularDHambiente()
    {
      $cad = "select id, descripcion from tipo_beneficiarios f where estado = 'N' and codigo in('07','08') Order By descripcion asc";
      $data = DB::select($cad);
      return $data;
    }
    public function getDatosPantallaAuditoria($id_prestacion)
    {
      $sql = "
      SELECT 
          tp.id as id_trama_prestacion, 
          tp.id_tipodoc_ident,
          tp.numdoc_ident,
          tp.doc_ape_paterno,
          tp.doc_ape_materno,
          tp.doc_nombres,
          tp.id_tipo_prof,
          tp.num_coleg,
          tp.id_especialidad_prof,
          tp.num_rne,
          tp.id_hist_clinica,
          tp.tipo_doc_identidad,
          tp.paciente_nro_doc_identidad,
          tp.paciente_apellido_paterno,
          tp.paciente_apellido_materno,
          tp.paciente_nombres,
          tp.id_tipo_cobert,
          a.fecha_nac::date,
          date_part('year', age(a.fecha_nac::date)) AS edadpaciente,
          a.sexo,
          a.id_departamento,
          a.id_provincia,
          a.id_distrito,
          a.domicilio,
          e2.id_tipo_parentesco,
          e2.telf_conducido,
          e2.fecha_atencion,
          e2.hora_atencion,
          e2.id_servicio_destino,
          p.tiempo_enfermedad,
          p.anamnesis AS relato_cronologico,
          p.motivo_visita,
          p.antecedente,
          p.examen_auxiliar,
          c.id as id_servicio,
          c.nombre as nombre_servicio,
          e2.temperatura,
          e2.frecuencia_respiratoria,
          e2.frecuencia_cardiaca,
          e2.presion_arterial_y,
          e2.presion_arterial_x,
          e2.sa02_porcentaje,
          e2.peso_kg,
          e2.talla_cm,
          e2.tamano_cintura_cm,
          e2.fi02,
          e2.id_tipo_egres_med,e2.id_condi_egres_med,e2.fecha_alta_medica,e2.hora_alta_medica,
          (SELECT array_to_json(array_agg(row_to_json(tpro_data))) AS trama_producto
     FROM (
      SELECT 
            tp2.id_prod_med,
            SUM(tp2.cantidad_prescrita) AS cantidad_prescrita,
            SUM(tp2.cantidad_entregada) AS cantidad_entregada, 
            MIN(tp2.renipress) AS renipress,
            MIN(tp2.numero_lote_fups) AS numero_lote_fups,
            MIN(tp2.numero_fups) AS numero_fups,
            MIN(tp2.id_cie10) AS id_cie10,
            MIN(tp2.precio) AS precio,
            MIN(tp2.valorizacion) AS valorizacion,
            MIN(tp2.correlativo) AS correlativo,
            p2.id AS id_producto,
            p2.nombre AS nombre_producto,
            d.id AS id_diagnostico,
            d.nombre AS nombre_diagnostico,
            MIN(pr.tiempo_periodo) AS tiempo_periodo,
            MIN(pr.stock_farmacia) AS stock_farmacia,
            MIN(pr.posologia) AS posologia,
            MIN(va.descripcion) AS via_administracion
        FROM producto_recetas AS pr
        LEFT JOIN via_administracion AS va ON pr.id_via_administracion = va.id 
        LEFT JOIN receta_vales AS rv ON pr.id_receta_vale = rv.id
        LEFT JOIN trama_prestacion AS tpres ON rv.id_prestacion = tpres.id_prestacion
        LEFT JOIN establecimientos AS e ON tpres.codigo_renipress = e.codigo
        LEFT JOIN diagnosticos AS d ON pr.id_diagnosticos = d.id
        LEFT JOIN productos AS p2 ON pr.id_producto = p2.id
        LEFT JOIN trama_productos AS tp2 ON tp2.id_trama_prestacion = tpres.id AND p2.codigo = tp2.id_prod_med 
        WHERE tpres.id = tp.id
        GROUP BY tp2.id_prod_med, p2.id, p2.nombre, d.id, d.nombre
     ) tpro_data) AS trama_productos,

    (SELECT array_to_json(array_agg(row_to_json(tpro_data))) AS trama_procedimientos_externo
     FROM (
         SELECT
             tpro.id,
             tpro.renipress,
             tpro.numero_lote_fups,
             tpro.numero_fups,
             tpro.id_cie10,
             tpro.id_proced,
             tpro.cantidad_indicada,
             tpro.cantidad_ejecutada,
             tpro.precio,
             tpro.valorizacion,
             tpro.correlativo,
             tpro.fecha_procedimiento,
             tpro.resultado_procedimiento,
             d2.id AS id_diagnostico,
             d2.nombre AS nombre_diagnostico,
             p.id AS id_procedimiento,
             p.codigo AS codigo_procedimiento,
             p.descripcion AS nombre_procedimiento
         FROM trama_procedimientos tpro
         INNER JOIN procedimientos p ON tpro.id_proced = p.codigo
         INNER JOIN diagnosticos d2 ON tpro.id_cie10 = d2.codigo
         WHERE tpro.id_trama_prestacion = tp.id
     ) tpro_data) AS trama_procedimientos_externo,

    (SELECT array_to_json(array_agg(row_to_json(td_data))) AS diagnosticos_externo
     FROM (
         SELECT 
            td.renipress,
            td.numero_lote_fups,
            td.numero_fups,
            td.id_cie10,
            MIN(CASE WHEN td.id_identificador_diag = 'E' THEN 'E' ELSE 'I' END) AS id_identificador_diag,
            td.id_tipo_diag,
            td.id_diagnostico,
            td.tipo_diag,
            d.nombre AS descripcion
        FROM trama_diagnosticos td
        INNER JOIN diagnosticos d ON td.id_diagnostico = d.id
        WHERE td.id_identificador_diag IN ('I', 'E') 
        AND td.id_trama_prestacion = tp.id
        GROUP BY 
            td.renipress,
            td.numero_lote_fups,
            td.numero_fups,
            td.id_cie10,
            td.id_tipo_diag,
            td.id_diagnostico,
            td.tipo_diag,
            d.nombre
     ) td_data) AS diagnosticos_externo,

    (SELECT array_to_json(array_agg(row_to_json(td_data))) AS diagnosticos_ingreso
     FROM (
         SELECT
             td.renipress,
             td.numero_lote_fups,
             td.numero_fups,
             td.id_cie10,
             td.id_identificador_diag,
             td.id_tipo_diag,
             td.id_diagnostico,
             td.tipo_diag,
             d.nombre AS descripcion
         FROM trama_diagnosticos td
         INNER JOIN diagnosticos d ON td.id_diagnostico = d.id
         WHERE td.id_identificador_diag = 'I' AND td.id_trama_prestacion = tp.id
     ) td_data) AS diagnosticos_ingreso,

    (SELECT array_to_json(array_agg(row_to_json(td_data))) AS diagnosticos_alta
     FROM (
         SELECT
             td.renipress,
             td.numero_lote_fups,
             td.numero_fups,
             td.id_cie10,
             td.id_identificador_diag,
             td.id_tipo_diag,
             td.id_diagnostico,
             td.tipo_diag,
             d.nombre AS descripcion
         FROM trama_diagnosticos td
         INNER JOIN diagnosticos d ON td.id_diagnostico = d.id
         WHERE td.id_identificador_diag = 'E' AND td.id_trama_prestacion = tp.id
     ) td_data) AS diagnosticos_alta,

    (SELECT array_to_json(array_agg(row_to_json(exam_data))) AS examen_imagenologia
     FROM (
         SELECT
             t2.id,
             t2.codigo,
             t2.descripcion AS nombre,
             t1.cantidad,
             t1.observacion,
             t3.nombre AS diagnostico,
             t3.codigo AS cod_diag,
             t1.comparativo_radio,
             t1.lado_radio
         FROM prestacion_procedimientos t1
         INNER JOIN radiologias r ON t1.id_lab_rad = r.id
         INNER JOIN procedimientos t2 ON t1.id_procedimiento = t2.id
         LEFT JOIN diagnosticos t3 ON t1.id_diagnostico = t3.id
         WHERE t1.id_prestacion = p.id AND t1.id_estado_reg = 1
     ) exam_data) AS examenes_imagenologias,

    (SELECT array_to_json(array_agg(row_to_json(lab_data))) AS examenes_laboratorio
     FROM (
         SELECT
             t2.id,
             t2.codigo_lab AS codigo,
             t2.descripcion_corta AS nombre,
             p.descripcion AS nombre_completo,
             t1.cantidad,
             t1.observacion,
             d.nombre AS diagnostico
         FROM prestacion_procedimientos t1
         INNER JOIN labnivelatenciones t2 ON t1.id_rua = t2.id
         INNER JOIN procedimientos p ON t1.id_procedimiento = p.id
         LEFT JOIN diagnosticos d ON d.id = t1.id_diagnostico
         WHERE t1.id_prestacion = p.id AND t1.id_estado_reg = 1
     ) lab_data) AS examenes_laboratorio,

    (SELECT array_to_json(array_agg(row_to_json(inter_data))) AS interconsultas
     FROM (
         SELECT
             c.nombre AS servicio,
             i.codigo_upss AS codigo_interconsulta,
             i.motivo_interconsulta,
             tr.nombre AS medico_nombre_tr,
             tr.paterno AS medico_paterno_tr,
             tr.materno AS medico_materno_tr
         FROM interconsultas i
         INNER JOIN consultorios c ON i.id_servicio = c.id
         INNER JOIN medicos tr ON i.personal_salud_interconsulta = tr.id
         WHERE i.id_prestacion = p.id
     ) inter_data) AS interconsultas
        
      FROM trama_prestacion tp
	  left join prestaciones as p on tp.id_prestacion=p.id
	  left join establecimientos as e on p.id_establecimiento  = e.id
	  left join emergencias e2 on p.id_ext=e2.id
      left join asegurados a ON p.id_asegurado = a.id 
      left join establecimientos es ON p.id_establecimiento = es.id 
      left join sub_consultorios sc ON sc.id = p.id_sub_consultorio 
      left join consultorios c ON c.id = sc.id_consultorio 
      left join medicos m ON p.id_medico = m.id 
      where tp.id_cups=$id_prestacion";
      $data = DB::select($sql);

        ////////////////////////DIAGNOSTICOS INGRESO/////////////////////////////////
        $diagnosticos_ingreso = json_decode($data[0]->diagnosticos_ingreso,true);
        //////////////////////FIN DIAGNOSTICOS INGRESO/////////////////////////////

        ////////////////////////DIAGNOSTICOS ALTA/////////////////////////////////
        $diagnosticos_alta = json_decode($data[0]->diagnosticos_alta,true);
        //////////////////////FIN DIAGNOSTICOS ALTA/////////////////////////////

        ////////////////////////EXAMENES DE IMAGENOLOGIA/////////////////////////////////
        $examenes_imagenologias = json_decode($data[0]->examenes_imagenologias,true);
        //////////////////////FIN EXAMENES DE IMAGENOLOGIA/////////////////////////////

        /////////////////////////////EXAMENES LABORATORIO//////////////////////////////
        $examenes_laboratorio = json_decode($data[0]->examenes_laboratorio,true);
        /////////////////////////////FIN EXAMENES LABORATORIO//////////////////////////////

        /////////////////////////////INTERCONSULTA//////////////////////////////
        $interconsultas = json_decode($data[0]->interconsultas,true);
        /////////////////////////////FIN INTERCONSULTA//////////////////////////////

        /////////////////////////////PROCEDIMIENTOS//////////////////////////////
        $trama_procedimientos_externo = json_decode($data[0]->trama_procedimientos_externo,true);
        /////////////////////////////FIN PROCEDIMIENTOS//////////////////////////////

        /////////////////////////////DIAGNOSTICO ALTA//////////////////////////////
        $diagnosticos_externo = json_decode($data[0]->diagnosticos_externo,true);
        /////////////////////////////FIN DIAGNOSTICO ALTA//////////////////////////////

        /////////////////////////////PRODUCTOS MEDICOS//////////////////////////////
        $trama_productos = json_decode($data[0]->trama_productos,true);
        /////////////////////////////FIN PRODUCTOS MEDICOS//////////////////////////////
        
        
        $data = array(
          'med_tipo_documento'=>!empty($data[0]->id_tipodoc_ident) ? $data[0]->id_tipodoc_ident : '',
          'med_n_documento_identidad'=>!empty($data[0]->numdoc_ident) ? $data[0]->numdoc_ident : '',
          'med_ape_paterno'=>!empty($data[0]->doc_ape_paterno) ? $data[0]->doc_ape_paterno : '',
          'med_ape_materno'=>!empty($data[0]->doc_ape_materno) ? $data[0]->doc_ape_materno : '',
          'med_nombres'=>!empty($data[0]->doc_nombres) ? $data[0]->doc_nombres : '',
          'med_id_tipo_profes'=>!empty($data[0]->id_tipo_prof) ? $data[0]->id_tipo_prof : '',
          'med_num_colegiatura'=>!empty($data[0]->num_coleg) ? $data[0]->num_coleg : '',
          'med_id_especialidad_prof'=>!empty($data[0]->id_especialidad_prof) ? $data[0]->id_especialidad_prof : '',
          'med_numero_rne'=>!empty($data[0]->num_rne) ? $data[0]->num_rne : '',
          'fecha_auditoria'=>date('Y-m-d'),
          'id_servicio'=>!empty($data[0]->id_servicio) ? $data[0]->id_servicio : '',
          'nombre_servicio'=>!empty($data[0]->nombre_servicio) ? $data[0]->nombre_servicio : '',
          
          ///////////////////////DATOS DEL AFILIADO///////////////////

          'n_historia_clinica'=>!empty($data[0]->id_hist_clinica) ? $data[0]->id_hist_clinica : '',
          'tipo_documento'=>!empty($data[0]->tipo_doc_identidad) ? $data[0]->tipo_doc_identidad : '',
          'n_documento_identidad'=>!empty($data[0]->paciente_nro_doc_identidad) ? $data[0]->paciente_nro_doc_identidad : '',
          'apellido_paterno'=>!empty($data[0]->paciente_apellido_paterno) ? $data[0]->paciente_apellido_paterno : '',
          'apellido_materno'=>!empty($data[0]->paciente_apellido_materno) ? $data[0]->paciente_apellido_materno : '',
          'nombres'=>!empty($data[0]->paciente_nombres) ? $data[0]->paciente_nombres : '',
          'tipo_seguro'=>!empty($data[0]->id_tipo_cobert) ? $data[0]->id_tipo_cobert : '',
          'fecha_nacimiento'=>!empty(date('Y-m-d',strtotime($data[0]->fecha_nac))) ? date('Y-m-d',strtotime($data[0]->fecha_nac)) : '',
          'edad'=>!empty($data[0]->edadpaciente) ? $data[0]->edadpaciente : '',
          'sexo'=>!empty($data[0]->sexo) ? $data[0]->sexo : '',
          'id_departamento'=>!empty($data[0]->id_departamento) ? $data[0]->id_departamento : '',
          'id_provincia'=>!empty($data[0]->id_provincia) ? $data[0]->id_provincia : '',
          'id_distrito'=>!empty($data[0]->id_distrito) ? $data[0]->id_distrito : '',
          'domicilio_actual'=>!empty($data[0]->domicilio) ? $data[0]->domicilio : '',
          'acompanante'=>!empty($data[0]->id_tipo_parentesco) ? $data[0]->id_tipo_parentesco : '',
          'numero_acompanante'=>!empty($data[0]->telf_conducido) ? $data[0]->telf_conducido : '',
          'fecha_atencion'=>!empty(date('Y-m-d',strtotime($data[0]->fecha_atencion))) ? date('Y-m-d',strtotime($data[0]->fecha_atencion)) : '',
          'hora_atencion'=>!empty(date('h:m',strtotime($data[0]->hora_atencion))) ? date('h:m',strtotime($data[0]->hora_atencion)) : '',
          'desarrollo_cronologico_enf'=>!empty($data[0]->relato_cronologico) ? $data[0]->relato_cronologico : '',
          'motivo_visita'=>!empty($data[0]->motivo_visita) ? $data[0]->motivo_visita : '',
          'antecedentes'=>!empty($data[0]->antecedente) ? $data[0]->antecedente : '',
          'examen_auxiliar'=>!empty($data[0]->examen_auxiliar) ? $data[0]->examen_auxiliar : '',
          'temperatura'=>!empty($data[0]->temperatura) ? $data[0]->temperatura : '',
          'frecuencia_respiratoria'=>!empty($data[0]->frecuencia_respiratoria) ? $data[0]->frecuencia_respiratoria : '',
          'frecuencia_cardiaca'=>!empty($data[0]->frecuencia_cardiaca) ? $data[0]->frecuencia_cardiaca : '',
          'presion_arterial_1'=>!empty($data[0]->presion_arterial_y) ? $data[0]->presion_arterial_y : '',
          'presion_arterial_2'=>!empty($data[0]->presion_arterial_x) ? $data[0]->presion_arterial_x : '',
          'saturacion_oxigeno'=>!empty($data[0]->sa02_porcentaje) ? $data[0]->sa02_porcentaje : '',
          'peso'=>!empty($data[0]->peso_kg) ? $data[0]->peso_kg : 0,
          'talla'=>!empty($data[0]->talla_cm) ? $data[0]->talla_cm : 0,
          'tamano_cintura'=>!empty($data[0]->tamano_cintura_cm) ? $data[0]->tamano_cintura_cm : 0,
          'fi02'=>!empty($data[0]->fi02) ? $data[0]->fi02 : 0,
          'tiempo_enfermedad'=>!empty($data[0]->tiempo_enfermedad) ? $data[0]->tiempo_enfermedad : '',
          'tipo_egreso'=>!empty($data[0]->id_tipo_egres_med) ? $data[0]->id_tipo_egres_med : '',
          'condicion_egreso'=>!empty($data[0]->id_condi_egres_med) ? $data[0]->id_condi_egres_med : '',
          'fecha_egreso'=>!empty($data[0]->fecha_alta_medica) ? $data[0]->fecha_alta_medica : '',
          'hora_egreso'=>!empty($data[0]->hora_alta_medica) ? $data[0]->hora_alta_medica : '',
          'diagnosticos_ingreso'=>!empty($diagnosticos_ingreso)?array_filter($diagnosticos_ingreso): '',
          'diagnosticos_alta'=>!empty($diagnosticos_alta)?array_filter($diagnosticos_alta):'',
          'examenes_imagenologias'=>!empty($examenes_imagenologias)?array_filter($examenes_imagenologias):'',
          'examen_patologia'=>!empty($examenes_laboratorio)?array_filter($examenes_laboratorio): '',
          'interconsultas'=>!empty($interconsultas)?array_filter($interconsultas):'',
          'id_trama_prestacion'=>!empty($data[0]->id_trama_prestacion) ? $data[0]->id_trama_prestacion : '',
          'trama_procedimientos_externo'=>!empty($trama_procedimientos_externo)?array_filter($trama_procedimientos_externo):'',
          'diagnosticos_externo'=>!empty($diagnosticos_externo)?array_filter($diagnosticos_externo):'',
          'trama_productos'=>!empty($trama_productos)?array_filter($trama_productos):''
        );
      
      return $data;
    }
    public function guardarDatosAuditor($datos)
  {
    //dd($datos->id_trama_prestacion);
    //dd(json_encode($datos['id_trama_prestacion']));
    $id_examen_patologia_anulado = !empty($datos['id_examen_patologia_anulado']) ? json_encode($datos['id_examen_patologia_anulado']) : null;
    $id_examen_imagenologia_anulado = !empty($datos['id_examen_imagenologia_anulado']) ? json_encode($datos['id_examen_imagenologia_anulado']) : null;

    $id_diagnosticos_externo_anulado = !empty($datos['id_diagnosticos_externo_anulado']) ? array_filter($datos['id_diagnosticos_externo_anulado']): null;
    $id_diagnostico_alta_anulado = !empty($datos['id_diagnostico_alta_anulado']) ? array_filter($datos['id_diagnostico_alta_anulado']): null;
    $id_procedimiento_externo_anulado = !empty($datos['id_procedimiento_externo_anulado']) ? array_filter($datos['id_procedimiento_externo_anulado']):null;
    $id_producto_anulado = !empty($datos['id_producto_anulado']) ? array_filter($datos['id_producto_anulado']): null;
    $nombre_diagnostico2 = !empty($datos['nombre_diagnostico2']) ? array_filter($datos['nombre_diagnostico2']): null;
    $nombre_diagnostico_alta = !empty($datos['nombre_diagnostico_alta']) ? array_filter($datos['nombre_diagnostico_alta']): null;
    $nombre_procedimiento = !empty($datos['nombre_procedimiento']) ? array_filter($datos['nombre_procedimiento']): null;
    $id_producto = !empty($datos['id_producto']) ? array_filter($datos['id_producto']): null;

    //dd($id_procedimiento_externo_anulado);
    ////////////////AUDITORES///////////////////////
    $auditores_array = array(
      'id_trama_prestacion'=>$datos['id_trama_prestacion'],
      'fecha_auditoria'=>date('Y-m-d'),
      'servicio_auditoria'=>$datos['nombre_servicio'],
      'id_servicio'=>$datos['id_servicio'],
      'asunto_audicion'=>$datos['asunto_audicion'],
      'observaciones_audicion'=>$datos['observaciones_audicion'],
      'id_user_auditor'=>$datos['id_user_auditor']
    );
    $auditores = Auditores::create($auditores_array);
    ////////////////FIN AUDITORES///////////////////////

    ///////////////AUDITOR AFILIADO///////////////////////
    $auditorAfiliacion = new Auditor_afiliacion();
    $auditorAfiliacion->id_auditor=$auditores->id;
    $auditorAfiliacion->id_trama_prestacion=(int)$datos['id_trama_prestacion'];
    $auditorAfiliacion->n_historia_clinica=$datos['n_historia_clinica'];
    $auditorAfiliacion->tipo_documento=$datos['tipo_documento'];
    $auditorAfiliacion->n_documento_identidad=$datos['n_documento_identidad'];
    $auditorAfiliacion->tipo_seguro=(int)$datos['tipo_seguro'];
    $auditorAfiliacion->fecha_nacimiento=$datos['fecha_nacimiento'];
    $auditorAfiliacion->edad=(int)$datos['edad'];
    $auditorAfiliacion->sexo=$datos['sexo'];
    $auditorAfiliacion->id_departamento=(int)$datos['id_departamento'];
    $auditorAfiliacion->id_provincia=(int)$datos['id_provincia'];
    $auditorAfiliacion->id_distrito=(int)$datos['id_distrito'];
    $auditorAfiliacion->domicilio_actual=$datos['domicilio_actual'];
    $auditorAfiliacion->apellido_paterno=$datos['apellido_paterno'];
    $auditorAfiliacion->apellido_materno=$datos['apellido_materno'];
    $auditorAfiliacion->nombres=$datos['nombres'];
    $auditorAfiliacion->acompanante=$datos['acompanante'];
    $auditorAfiliacion->numero_acompanante=$datos['numero_acompanante'];
    $auditorAfiliacion->med_tipo_documento=(int)$datos['med_tipo_documento'];
    $auditorAfiliacion->med_n_documento_identidad=$datos['med_n_documento_identidad'];
    $auditorAfiliacion->med_ape_paterno=$datos['med_ape_paterno'];
    $auditorAfiliacion->med_ape_materno=$datos['med_ape_materno'];
    $auditorAfiliacion->med_nombres=$datos['med_nombres'];
    $auditorAfiliacion->med_num_colegiatura=$datos['med_num_colegiatura'];
    $auditorAfiliacion->med_numero_rne=$datos['med_numero_rne'];
    $auditorAfiliacion->med_id_tipo_profes=(int)$datos['med_id_tipo_profes'];
    $auditorAfiliacion->med_id_especialidad_prof=(int)$datos['med_id_especialidad_prof'];
    $auditorAfiliacion->fecha_atencion = isset($datos['fecha_atencion']) ? $datos['fecha_atencion'] : null;
    $auditorAfiliacion->hora_atencion = isset($datos['hora_atencion']) ? $datos['hora_atencion'] : null;
    $auditorAfiliacion->desarrollo_cronologico_enf= isset($datos['desarrollo_cronologico_enf']) ? $datos['desarrollo_cronologico_enf'] : null;
    $auditorAfiliacion->tiempo_enfermedad= isset($datos['tiempo_enfermedad']) ? $datos['tiempo_enfermedad'] : null;
    $auditorAfiliacion->motivo_visita= isset($datos['motivo_visita']) ? $datos['motivo_visita'] : null;
    $auditorAfiliacion->antecedentes= isset($datos['antecedentes']) ? $datos['antecedentes'] : null;
    ////
    /*
    $auditorAfiliacion->forma_ingreso_emergencia=$datos['id_forma_ingreso'];
    $auditorAfiliacion->estado_ingreso_emergencia=$datos['id_estado_ingreso'];
    $auditorAfiliacion->motivo_ingreso_emergencia=$datos['id_motivo_ingreso'];
    $auditorAfiliacion->prioridad_emergencia=$datos['id_prioridad'];
    $auditorAfiliacion->area_servicio_emergencia=$datos['id_topico'];
    $auditorAfiliacion->motivo_consulta_emergencia=$datos['observacion'];
*/
    $auditorAfiliacion->forma_ingreso_emergencia=!empty($datos['id_forma_ingreso'])?$datos['id_forma_ingreso']:null;
    $auditorAfiliacion->estado_ingreso_emergencia=!empty($datos['id_estado_ingreso'])?$datos['id_estado_ingreso']:null;
    $auditorAfiliacion->motivo_ingreso_emergencia=!empty($datos['id_motivo_ingreso'])?$datos['id_motivo_ingreso']:null;
    $auditorAfiliacion->prioridad_emergencia=!empty($datos['id_prioridad'])?$datos['id_prioridad']:null;
    $auditorAfiliacion->area_servicio_emergencia=!empty($datos['id_topico'])?$datos['id_topico']:null;
    $auditorAfiliacion->motivo_consulta_emergencia=!empty($datos['observacion'])?$datos['observacion']:null;
    //////
    $auditorAfiliacion->examenes_patologia_anulados = $id_examen_patologia_anulado;
    $auditorAfiliacion->examenes_imagenologia_anulados = $id_examen_imagenologia_anulado;
    $auditorAfiliacion->save();
    ///////////////fin AUDITOR AFILIADO///////////////////////

    ///////////////AUDITOR CLINICOS///////////////////////
    $auditores_clinicos_array = array(
      'id_auditor'=>$auditores->id,
      'id_auditor_afiliacion'=>$auditorAfiliacion->id,
      'temperatura'=>isset($datos['temperatura']) ? $datos['temperatura'] : null,
      'frecuencia_respiratoria' => isset($datos['frecuencia_respiratoria']) && is_numeric($datos['frecuencia_respiratoria']) ? (int)$datos['frecuencia_respiratoria'] : null,
      'frecuencia_cardiaca' => isset($datos['frecuencia_cardiaca']) && is_numeric($datos['frecuencia_cardiaca']) ? (int)$datos['frecuencia_cardiaca'] : null,
      'presion_arterial_1' => isset($datos['presion_arterial_1']) && is_numeric($datos['presion_arterial_1']) ? (int)$datos['presion_arterial_1'] : null,
      'presion_arterial_2' => isset($datos['presion_arterial_2']) && is_numeric($datos['presion_arterial_2']) ? (int)$datos['presion_arterial_2'] : null,
      'saturacion_oxigeno' => isset($datos['saturacion_oxigeno']) && is_numeric($datos['saturacion_oxigeno']) ? (int)$datos['saturacion_oxigeno'] : null,
      'peso'=>isset($datos['peso']) ? $datos['peso'] : null,
      'talla'=>isset($datos['talla']) ? $datos['talla'] : null,
      'tamano_cintura'=>isset($datos['tamano_cintura']) ? $datos['tamano_cintura'] : null,
      'fi02'=>isset($datos['fi02']) ? $datos['fi02'] : null,
      'examen_auxiliares'=>isset($datos['examenes_auxiliares']) ? $datos['examenes_auxiliares'] : null,
      'tipo_egreso'=>$datos['tipo_egreso'],
      'condicion_egreso'=>$datos['condicion_egreso'],
      'fecha_egreso'=>$datos['fecha_egreso'],
      'hora_egreso'=>$datos['hora_egreso']
    );
    $auditorClinico = Auditor_clinicos::create($auditores_clinicos_array);
    ///////////////AUDITOR CLINICOS///////////////////////
    ////////////////////////DIAGNOSTICOS////////////////////////
    //DIAGNOSTICOS ANULADOS
    if(!empty($id_diagnosticos_externo_anulado)){
      $diagnosticos_anulados = [];
      foreach ($id_diagnosticos_externo_anulado as $key => $value) {
        $diagnosticos_anulados[] = json_decode($value, true);
      }
      foreach ($diagnosticos_anulados as $key => $value) {
        Auditor_diagnosticos::create(array(
          'id_cie10'=>$value['id_cie10'],
          'id_diagnostico_clasificacion'=>(int)$value['tipo_diag'],
          'renipress'=>$value['renipress'],
          'estado_eliminado'=>$value['estado_eliminado'],
          'id_auditor'=>$auditores->id,
          'id_auditor_afiliacion'=>$auditorAfiliacion->id,
          'id_trama_prestacion'=>$datos['id_trama_prestacion'],
          'tipo_diagnostico'=>$value['id_tipo_diag'],
          'id_diagnostico'=>$value['id_diagnostico'],
          'id_identificador_diag'=>$value['id_identificador_diag']
        ));
      }
    }
    ////INGRESO DE DIAGNOSTICOS 
    if(!empty($nombre_diagnostico2)){
      foreach ($nombre_diagnostico2 as $key => $value) {
        $id_cie10 = explode('-',$value);
        $codigo=trim($id_cie10[0]);
        Auditor_diagnosticos::create(array(
          'id_cie10'=>isset($codigo)?$codigo:'',
          'id_diagnostico_clasificacion'=>(int)$datos['id_clasificacion2'][$key],
          'renipress'=>'',
          //'flag_estado'=>1,//INGRESO
          'estado_eliminado'=>0,
          'id_auditor'=>$auditores->id,
          'id_auditor_afiliacion'=>$auditorAfiliacion->id,
          'tipo_diagnostico'=>(int)$datos['id_diagnostico_externo_nuevos_tipo_diagnostico'][$key],
          'id_diagnostico'=>(int)$datos['id_diagnostico_externo_nuevos'][$key],
          'id_identificador_diag'=>$datos['ingreso_egreso'][$key],
        ));
      }
    }
    //INGRESO DE DIAGNOSTICOS DE ALTA
    if(!empty($nombre_diagnostico_alta)){
      foreach ($nombre_diagnostico_alta as $key => $value) {
        $id_cie10 = explode('-',$value);
        $codigo=trim($id_cie10[0]);
        Auditor_diagnosticos::create(array(
          'id_cie10'=>isset($codigo)?$codigo:'',
          'id_diagnostico_clasificacion'=>(int)$datos['id_clasificacion_alta'][$key],
          'renipress'=>'',
          //'flag_estado'=>2,//INGRESO
          'estado_eliminado'=>0,
          'id_auditor'=>$auditores->id,
          'id_auditor_afiliacion'=>$auditorAfiliacion->id,
          'tipo_diagnostico'=>2,
          'id_diagnostico'=>(int)$datos['id_diagnostico_alta'][$key],
          'id_identificador_diag'=>'E'
        ));
      }
    }
    
    ///////////////////PROCEDIMIENTOS///////////////
    //PROCEDIMIENTOS ANULADOS
    if(!empty($id_procedimiento_externo_anulado)){
      $procedimientos_anulados = [];
      foreach ($id_procedimiento_externo_anulado as $key => $value) {
        $procedimientos_anulados[] = json_decode($value, true);
      }
      foreach ($procedimientos_anulados as $key => $value) {
        Auditor_procedimientos::create(array(
          'id_cie10'=>$value['id_cie10'],
          'cantidad_indicada'=>$value['cantidad_indicada'],
          'cantidad_ejecutada'=>$value['cantidad_ejecutada'],
          'fecha_procedimiento'=>date('Y-m-d',strtotime($value['fecha_procedimiento'])),
          'id_auditor_afiliacion'=>$auditorAfiliacion->id,
          'id_procedimiento'=>$value['id_procedimiento'],
          'id_diagnostico'=>$value['id_diagnostico'],
          'estado_eliminado'=>1 
        ));
      }
    }

    //INGRESO DE PROCEDIMIENTOS
    if(!empty($nombre_procedimiento)){
      foreach ($nombre_procedimiento as $key => $value) {
        $id_cie10 = explode('-',$value);
        $codigo=trim($id_cie10[0]);
        Auditor_procedimientos::create(array(
          'id_cie10'=>isset($codigo)?$codigo:'',
          'cantidad_indicada'=>(int)$datos['procedimiento_cantidad'][$key],
          'cantidad_ejecutada'=>0,
          'fecha_procedimiento'=>date('Y-m-d'),
          'id_auditor_afiliacion'=>$auditorAfiliacion->id,
          'id_procedimiento'=>(int)$datos['id_procedimiento'][$key],
          'id_diagnostico'=>(int)$datos['miSelect'][$key],
          'estado_eliminado'=>0
        ));
      }
    }

    //DIAGNOSTICOS DE ALTA
    if(!empty($id_diagnostico_alta_anulado)){
      $diagnosticos_alta_anulados = [];
      foreach ($id_diagnostico_alta_anulado as $key => $value) {
        $diagnosticos_alta_anulados[] = json_decode($value, true);
      }
      foreach ($diagnosticos_alta_anulados as $key => $value) {
        Auditor_diagnosticos::create(array(
          'id_cie10'=>$value['id_cie10'],
          'id_diagnostico_clasificacion'=>(int)$value['tipo_diag'],
          'renipress'=>$value['renipress'],
          'estado_eliminado'=>1,
          'id_auditor'=>$auditores->id,
          'id_auditor_afiliacion'=>$auditorAfiliacion->id,
          'tipo_diagnostico'=>$value['id_tipo_diag'],
          'id_diagnostico'=>$value['id_diagnostico'],
          'id_identificador_diag'=>$value['id_identificador_diag']
        ));
      }
    }

     //PRODUCTOS ANULADOS
    if(!empty($id_producto_anulado)){
      $productos_anulados = [];
      foreach ($id_producto_anulado as $key => $value) {
        $productos_anulados[] = json_decode($value, true);
      }
      foreach ($productos_anulados as $key => $value) {
        Auditor_productos::create(array(
          'renipress'=>$value['renipress'],
          'id_cie10'=>$value['id_cie10'],
          'id_prod_med'=>$value['id_prod_med'],
          'cantidad_prescrita'=>$value['cantidad_prescrita'],
          'cantidad_entregada'=>$value['cantidad_entregada'],
          'precio'=>$value['precio'],
          'valorizacion'=>$value['valorizacion'],
          'correlativo'=>$value['correlativo'],
          'id_auditor_afiliacion'=>$auditorAfiliacion->id,
          'id_trama_prestacion'=>$datos['id_trama_prestacion'],
          'estado_eliminado'=>1
        ));
      }
    }
    //INGRESO DE PRODUCTOS MEDICOS
    if(!empty($id_producto)){
      foreach ($id_producto as $key => $value) {
        $id_cie10=trim($datos['id_cie10_producto'][$key]);
        Auditor_productos::create(array(
          'renipress'=>'',
          'id_cie10'=>$id_cie10,
          'id_prod_med'=>$datos['codigo_producto'][$key],
          'cantidad_prescrita'=>$datos['nro_med_solictados'][$key],
          'cantidad_entregada'=>$datos['nro_med_dispensados'][$key],
          'id_auditor_afiliacion'=>$auditorAfiliacion->id,
          'id_trama_prestacion'=>$datos['id_trama_prestacion'],
          'estado_eliminado'=>0
        ));
      }
    }
    if(!empty($datos['id_trama_prestacion'])){
      trama_prestacion::where('id', $datos['id_trama_prestacion'])
                ->update(['id_estado' => 8]);
    }
    return true;
  }


    public function getPrestacionById_Auditoria($numero_correlativo)
	{
	  $cad = "select tp.*
	            from 
			        trama_prestacion tp 
              Where tp.id=" . $numero_correlativo;
	  $data = DB::select($cad);
	  return $data[0];
	}
  public function ws_datos_imagenes($id_trama,$tipo_atencion,$fecha_inicio,$fecha_fin,$dni){
    $procedimientos_existentes = "select * from trama_procedimientos where tipo_procedimiento != 1 and id_trama_prestacion = ".$id_trama;
    $procedimientos_existentes = DB::select($procedimientos_existentes);

    $fecha_formateada1 = date("d/m/Y", strtotime($fecha_inicio));
    $fecha_formateada1iam = date("Y-m-d", strtotime($fecha_inicio));
    $fecha_formateada2 = date("d/m/Y", strtotime($fecha_fin));    
    $fecha_formateada1i2am = date("Y-m-d", strtotime($fecha_fin));  
    $fecha_corrido1 = date("Ymd", strtotime($fecha_inicio));                          
    $fecha_corrido2 = date("Ymd", strtotime($fecha_fin)); 
    $wsintermedia = new \App\Ws\LaboratorioLNS;
    if($tipo_atencion == '2'){
      $tablaintermedia = $wsintermedia->CallResultadosLaboratorioSinFiltro($dni,$fecha_corrido1,$fecha_corrido2);
      $tablaimagen = $wsintermedia->CallResultadosImagenes($dni,$fecha_formateada1iam,$fecha_formateada1i2am); 
      //dd($tablaintermedia);
      dd($tablaimagen);
    }elseif($tipo_atencion == '3'){
      $tablaintermedia = $wsintermedia->CallResultadosLaboratorioSinFiltro($dni,$fecha_corrido1,$fecha_corrido2);
      $tablaimagen = $wsintermedia->CallResultadosImagenes($dni,$fecha_formateada1iam,$fecha_formateada1i2am); 
      //dd($tablaintermedia);
      dd($tablaimagen);
    }else{

    }
  
  }

	public function GetPrestacionDiagnosticoAuditoria($numero_correlativo)
	{
	  $cad = "	select td.id,td.id_cie10 as codigo_cie10,
    td.estado,
    td.actualizado,
                      td.id_identificador_diag,
                      d.nombre as nombre_cie10,
                      td.tipo_diag as tipo_atencion,
            td.id_tipo_diag as tipo_diagnostico,
            case
            when td.id_tipo_diag = 1 then 'PRESUNTIVO'
            when td.id_tipo_diag = 2 then 'DEFINITIVO'
            when td.id_tipo_diag = 3 then 'REITERATIVO'
            else ' ' 
            end as nombre_diagnostico,
                      
                      case
                      when td.tipo_diag = 0 then 'Atencion'
                      else 'Evaluacion'
                      end as nombre_tipo_atencion
                from 
                trama_prestacion tp 
                left join trama_diagnosticos td on tp.id = td.id_trama_prestacion
                left join diagnosticos d on d.id = td.id_diagnostico 
                where (td.estado IS NULL OR td.estado != 0)
  AND (td.actualizado IS NULL OR td.actualizado != 0) and tp.id=" . $numero_correlativo . 
                "ORDER BY  tipo_atencion,nombre_cie10 ASC;";
	  $data = DB::select($cad);

	  return $data;
	}
	public function GetPrestacionDiagnosticoEgresos($numero_correlativo)
  {
	$cad = " select td.id,td.id_cie10 as codigo_cie10,
            d.nombre as nombre_cie10,
            td.id_tipo_diag as tipo_diagnostico,
            case
            when td.id_tipo_diag = 1 then 'PRESUNTIVO'
            when td.id_tipo_diag = 2 then 'DEFINITIVO'
            when td.id_tipo_diag = 3 then 'REITERATIVO'
            else ' ' 
            end as nombre_diagnostico,
            case
            when td.tipo_diag = 0 then 'Secundario'
            else 'Principal'
            end as tipo_atencion
          from 
          trama_prestacion tp 
          inner join trama_diagnosticos td on tp.id = td.id_trama_prestacion
          inner join diagnosticos d on d.id = td.id_diagnostico 
          where td.id_identificador_diag = 'E' and tp.id=" . $numero_correlativo . 
          "ORDER BY  tipo_atencion,codigo_cie10 ASC;";
	$data = DB::select($cad);
	return $data;
  }
  
	public function getProcedimientosByAuditoria($numero_correlativo)
	{
	  $cad = "select  
                    tp2.id,
                    tp2.id_proced as codigo_procedimiento,
                    p.descripcion as nombre_procedimiento,
                    tp2.cantidad_indicada as cantidad_indicada,
                    tp2.id_cie10 as diagnostico_relacionado,
                    tp2.precio  as precio_procedimiento,
                     tp2.flag_evolucion,
                    tp2.tipo_procedimiento ,
                    tp2.valorizacion as valorizacion_procedimiento
                from trama_prestacion tp
                inner join trama_procedimientos tp2 ON tp.id = CAST(tp2.id_trama_prestacion AS INTEGER)
                inner join procedimientos p on p.codigo = tp2.id_proced 
                  where (tp2.estado IS NULL OR tp2.estado != 0) and tp.id= " . $numero_correlativo .
                  "ORDER BY tp2.tipo_procedimiento ASC" ;
	  $data = DB::select($cad);
	  return $data;
	}
	public function getProcedimientosByAuditoria_labo($numero_correlativo)
	{
	  $cad = "select  tp2.id,tp2.id_proced as codigo_procedimiento,
                    p.descripcion as nombre_procedimiento,
                    tp2.cantidad_indicada as cantidad_indicada,
                    tp2.id_cie10 as diagnostico_relacionado,
                    tp2.precio  as precio_procedimiento,
                     tp2.flag_evolucion,
                    tp2.tipo_procedimiento ,
                    tp2.valorizacion as valorizacion_procedimiento
                from trama_prestacion tp
                inner join trama_procedimientos tp2 ON tp.id = CAST(tp2.id_trama_prestacion AS INTEGER)
                inner join procedimientos p on p.codigo = tp2.id_proced 
                  where tp2.tipo_procedimiento = '2' and tp2.ima_consulta_ws is null and tp2.lab_consulta_ws is null and tp.id= " . $numero_correlativo ;
	  $data = DB::select($cad);
	  return $data;
	}

  public function getProcedimientosByAuditoria_imag($numero_correlativo)
	{
	  $cad = "select  tp2.id,tp2.id_proced as codigo_procedimiento,
                    p.descripcion as nombre_procedimiento,
                    tp2.cantidad_indicada as cantidad_indicada,
                    tp2.id_cie10 as diagnostico_relacionado,
                    tp2.precio  as precio_procedimiento,
                     tp2.flag_evolucion,
                    tp2.tipo_procedimiento ,
                    tp2.valorizacion as valorizacion_procedimiento
                from trama_prestacion tp
                inner join trama_procedimientos tp2 ON tp.id = CAST(tp2.id_trama_prestacion AS INTEGER)
                inner join procedimientos p on p.codigo = tp2.id_proced 
                  where tp2.tipo_procedimiento = '3' and tp2.ima_consulta_ws is null and tp2.lab_consulta_ws is null and tp.id= " . $numero_correlativo ;
	  $data = DB::select($cad);
	  return $data;
	}
  public function getProcedimientosByAuditoria_total($numero_correlativo)
	{
	  $cad = "select  tp2.id_proced as codigo_procedimiento,
                    p.descripcion as nombre_procedimiento,
                    tp2.cantidad_indicada as cantidad_indicada,
                    tp2.id_cie10 as diagnostico_relacionado,
                    tp2.precio  as precio_procedimiento,
                     tp2.flag_evolucion,
                    tp2.tipo_procedimiento ,
                    tp2.valorizacion as valorizacion_procedimiento
                from trama_prestacion tp
                inner join trama_procedimientos tp2 ON tp.id = CAST(tp2.id_trama_prestacion AS INTEGER)
                inner join procedimientos p on p.codigo = tp2.id_proced 
                  where tp2.tipo_procedimiento = 1 and tp.id= " . $numero_correlativo ;
	  $data = DB::select($cad);
	  return $data;
	}
  
	public function getProductosByNivelAndIdServicio($nivel,$id_servicio,$id_farmacia) {   
    $cad = "select 
productos.*,unidades.abreviatura as unidad,unidades.abreviatura as abreviatura
from productos
inner join tipo_productos on productos.id_tipo=tipo_productos.id
inner join unidades on productos.id_unidad=unidades.id
where productos.estado='1' ";
if($nivel == 1)$cad .= " and productos.nivel in(1)";
if($nivel == 2)$cad .= " and productos.nivel in(1,2)";
if($nivel == 3)$cad .= " and productos.nivel in(1,2,3)";
$cad .= " And productos.id in (select id_producto from producto_servicios where id_farmacia = ".$id_farmacia." And estado=1)
order By tipo_productos.id asc,productos.nombre asc";
    
    $data = DB::select($cad);
    return $data;

}
  public function getProductosByAuditoria($numero_correlativo)
  {
	// $cad = "  select tp2.id_prod_med,p.nombre,
  //                 tp2.cantidad_prescrita,tp2.cantidad_entregada,
  //                 tp2.precio,tp2.valorizacion,tp2.id_cie10
  //                 from trama_prestacion tp 
  //                 inner join trama_productos tp2 on tp.id = CAST(tp2.id_trama_prestacion AS INTEGER)
  //                 inner join productos p on p.codigo = tp2.id_prod_med 
  //                 where  tp.id=" . $numero_correlativo ;
  $cad="SELECT 
  tp2.id,
          tp2.id_prod_med,
          p.nombre,
          SUM(tp2.cantidad_prescrita) AS cantidad_prescrita,
          SUM(tp2.cantidad_entregada) AS cantidad_entregada,
          SUM(tp2.precio) AS precio,
          SUM(tp2.valorizacion) AS valorizacion,
          tp2.id_cie10
        FROM trama_prestacion tp
        INNER JOIN trama_productos tp2 ON tp.id = CAST(tp2.id_trama_prestacion AS INTEGER)
        INNER JOIN productos p ON p.codigo = tp2.id_prod_med
        WHERE (tp2.estado IS NULL OR tp2.estado != 0) and tp.id ='".$numero_correlativo."'
        GROUP BY tp2.id_prod_med,p.nombre,tp2.id_cie10,tp2.id  order by tp2.id desc";
        $data = DB::select($cad);
	return $data;
  }

  public function consulta_graficos($inicio,$fin){
    $cad = "  SELECT 
                  SUM(CASE WHEN tp.id_estado != '6' THEN 1 ELSE 0 END) AS total_enviados,
                  SUM(CASE WHEN tp.id_estado = '0' AND tp.id_estado != '6' THEN 1 ELSE 0 END) AS total_estado_0,
                  SUM(CASE WHEN tp.id_estado != '0' AND tp.id_estado != '6' THEN 1 ELSE 0 END) AS total_estado_1,
                  SUM(CASE WHEN tp.id_estado = '2' AND tp.id_estado != '6' THEN 1 ELSE 0 END) AS total_estado_2,
                  SUM(CASE WHEN tp.id_estado = '3' AND tp.id_estado != '6' THEN 1 ELSE 0 END) AS total_estado_3,
                  SUM(CASE WHEN tp.id_estado = '4' AND tp.id_estado != '6' THEN 1 ELSE 0 END) AS total_estado_4
              FROM trama_prestacion tp
                  where  tp.fech_alta_atenc>= '$inicio' and fech_alta_atenc<= '$fin'";
	$data = DB::select($cad);
	return $data[0];
  }

  public function getProcedimientoByConveniosAuditoria($buscar, $nivel, $genero, $tipo)
  {
    $nivel = $nivel +1; 
    $cad = "select *
from procedimientos
where codigo||descripcion ilike '%" . $buscar . "%' and convenio=1 and estado=1 and ( genero=" . $genero . " or genero=3 ) and 1=1 and nivel<" . $nivel." ";
  /*  switch ($tipo) { //1: 
      case 1: //todo menos lab ni radio
        $cad .= "and codigo_seccion not IN ('1.5','1.4') ";
        break;
      case 2: //laboratorio
        $cad .= "and codigo_seccion='1.5' ";
        break;
      case 3: //radiografia
        $cad .= "and codigo_seccion='1.4' ";
        break;
    }*/
    switch ($nivel) { //1: 
      case 1: //todo menos lab ni radio
        //$query.="t_nivel1 > 0";
        break;
      case 2: //laboratorio
        $cad .= "and t_nivel1 > 0 ";
        break;
      case 3: //radiografia
        $cad .= "and t_nivel2 > 0 ";
        break;
    }
    $cad .= " Order by codigo asc
                  limit 50 ";

    $data = DB::select($cad);
    return $data;
  }

  public function GetDiagnosticoByIdEvaluacion($id)
  {
    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo,t1.id_categoria,
            (case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico=t2.id
            where t1.id_interconsulta=0 and  t1.id_evaluacion=" . $id . "
            and t1.estado=1";
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdEvaluacion($id)
  {
      $query = "SELECT t1.id as cod, t2.codigo, t2.descripcion nombre, t1.cantidad, t3.id_diagnosticos as id_diagnostico
                FROM prestacion_procedimientos t1
                INNER JOIN procedimientos t2 ON t1.id_procedimiento = t2.id
                INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion 
                WHERE  t3.id_evaluacion = " . $id . " AND t1.tipo_procedimiento = 1
                AND t1.id_estado_reg = 1 AND t3.estado = 1 order by t1.id asc
                ";
  
    $data = DB::select($query);
    return $data;
  }
  public function getProductosRecetaFisicaByDniFechas($numero_correlativo,$tipo_atencion,$dni,$fecha_registro_ingreso,$fecha_alta_medica)
  {
	  $cad = "SELECT 
                p.nombre AS nombre_producto, 
                p.codigo AS codigo_producto, 
                pr.cantidad_prescrita,
                pr.cantidad_dispensada,
                p.precio_trama AS precio,
                SUM(p.precio_trama * pr.cantidad_dispensada) AS valorizacion,
                pr.tiempo_periodo,
                pr.stock_farmacia,
                pr.posologia,
                rv.dni_beneficiario,
                pr.id_diagnosticos,
                d2.codigo as codigo_diagnostico,
                (SELECT array_to_json(array_agg(row_to_json(td_data))) AS diagnosticos
                FROM (
                    SELECT
                        d.id AS id_diagnostico,
                        d.codigo AS codigo_diagnostico,
                        d.nombre AS nombre_diagnostico
                    FROM receta_diagnosticos rd
                    INNER JOIN diagnosticos d ON rd.id_diagnostico = d.id
                    WHERE rd.id_receta_vale = rv.id
                ) td_data) AS diagnosticos
            FROM productos p
            INNER JOIN producto_recetas pr ON p.id = pr.id_producto
            INNER JOIN receta_vales rv ON pr.id_receta_vale = rv.id
            LEFT JOIN emergencias e ON (e.dni_beneficiario = rv.dni_beneficiario AND :tipo_atencion = 2)
            LEFT JOIN hospitalizaciones h ON (h.dni_beneficiario = rv.dni_beneficiario AND :tipo_atencion = 3)
            LEFT JOIN diagnosticos d2 on pr.id_diagnosticos=d2.id
            WHERE 
                rv.dni_beneficiario = :dni
                AND (
                    (:tipo_atencion = 2 AND rv.tipo_receta = 'EMERGENCIA' 
                     AND e.fecha_registro_ingreso IS NOT NULL AND e.fecha_alta_medica IS NOT NULL
                     AND e.fecha_registro_ingreso::DATE = :fecha_registro_ingreso AND e.fecha_alta_medica::DATE = :fecha_alta_medica)
                    OR 
                    (:tipo_atencion = 3 AND rv.tipo_receta = 'HOSPITALIZACION' 
                     AND h.fecha_ingreso IS NOT NULL AND h.fecha_alta_medica IS NOT NULL
                     AND h.fecha_ingreso::DATE = :fecha_registro_ingreso AND h.fecha_alta_medica::DATE = :fecha_alta_medica)
                )
                AND COALESCE(rv.id_prestacion, 0) = 0 -- RECETA FISICA QUE NO TIENE PRESTACIONES O ES NULL
                AND p.codigo not in(select tp.id_prod_med from trama_productos tp where tp.id_trama_prestacion='".$numero_correlativo."' and tp.renipress is null)
            GROUP BY 
                p.nombre,
                p.codigo,
                p.precio_trama,
                pr.tiempo_periodo,
                pr.stock_farmacia,
                pr.posologia,
                pr.cantidad_prescrita,
                pr.cantidad_dispensada,
                rv.dni_beneficiario,
                pr.id_diagnosticos,
                d2.codigo,
                rv.id";

    $bindings = [
        'tipo_atencion' => $tipo_atencion,
        'dni' => $dni,
        'fecha_registro_ingreso' => $fecha_registro_ingreso,
        'fecha_alta_medica' => $fecha_alta_medica,
    ];
    $data = DB::select($cad, $bindings);
    return $data;
  }
  public function getDatosParaLaRecetaFisica($id_trama_pretacion)
  {
	  $cad = "SELECT 
                cp.tipo_atencion,
                tp.paciente_nro_doc_identidad as dni,
                CASE 
                    WHEN cp.tipo_atencion = '2' THEN e.fecha_registro_ingreso
                    WHEN cp.tipo_atencion = '3' and tp.id_cpt is null THEN h.fecha_ingreso
                    WHEN cp.tipo_atencion = '3' and tp.id_cpt is not null THEN ct.fecha_egreso_fups::date
                END AS fecha_ingreso,
                CASE 
                    WHEN cp.tipo_atencion = '2' THEN e.fecha_alta_medica
                    WHEN cp.tipo_atencion = '3' and tp.id_cpt is null THEN h.fecha_alta_medica
                    WHEN cp.tipo_atencion = '3' and tp.id_cpt is not null THEN ct.fecha_ingreso_fups::date
                END AS fecha_alta_medica
            FROM 
                trama_prestacion tp
            INNER JOIN 
                cups_pnp cp ON tp.id_cups = cp.id
            LEFT JOIN 
                emergencias e ON e.id = CAST(cp.id_ext AS INTEGER) AND cp.tipo_atencion = '2'
            LEFT JOIN 
                hospitalizaciones h ON h.id = CAST(cp.id_ext AS INTEGER) AND cp.tipo_atencion = '3'
            left join 
            	cpt_tramas ct on ct.id=CAST(cp.id_ext as INTEGER) and cp.tipo_atencion = '3'
            WHERE 
                tp.id ='".$id_trama_pretacion."'";
    $data = DB::select($cad);
    return $data;
  }
  
}