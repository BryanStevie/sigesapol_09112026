<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;
use Auth;

class Prestaciones extends Model
{
  protected $table = 'prestaciones';
  protected $primaryKey = 'id';
  public $timestamps = false;
  protected $fillable = [
    'id_cita',
    'id_asegurado',
    'id_tipo_atencion',
    'id_establecimiento',
    'id_sub_consultorio',
    'fecha_atencion',
    'fecha_alta',
    'id_medico',
    'id_motivo_alta',
    'id_estado_reg',
    'anamnesis',
    'examen_fisico',
    'fecha_firma',
    'ruta_prestacion',
    'condicion',
    'observacion',
    'id_servicio',
    'created_at',
    'created_us',
    'updated_us',
    'deleted_us',
    'antecedente',
    'plan_trabajo',
    'interconsulta',
    'aspectos_generales',
    'temperatura',
    'sa02_porcentaje',
    'presion_arterial_x',
    'presion_arterial_y',
    'talla_cm',
    'tamano_cintura_cm',
    'frecuencia_respiratoria',
    'peso_kg',
    'frecuencia_cardiaca_fetal',
    'frecuencia_cardiaca',
    'fi02',
    'motivo_visita',
    'tiempo_enfermedad',
    'examen_clinico',
    'examen_auxiliar',
    'tratamiento_auxiliar',
    'ipress',
    'upss',
    'flag',
    'id_ext',
    'id_modalidad',
    'codigo_upss',
    'receta_prescrita',
    'antecedente_familiar',
    'fecha_triaje',
    'id_profesional_triaje',
    'adicional',
    'observaciones_especiales',
    'registrar_cita_por_medico',
    'id_gestacion',
    'cups_pnp',
    'correlativo',
    'examen_clinico_regional',
    'indicacion_terapeutica',
    'examen_ayuda_diagnostica',
    'procedimiento_medico_quirurgico',
    'flag_ref_cref',
    'correlativo_antes',
    'id_profesional_update',
    'motivo_update',
    'fecha_hora_update',
    'estado_update',
    'flag_firma_update',
    'ruta_firma_update',
    'fecha_firma_update',
    'flag_alta_servicio',
    'fecha_alta_servicio',
    'hora_alta_servicio',
    'id_cama_alta_servicio',
    'observacion_alta_servicio',
    'flag_intervencion_quirurgica',
    'id_cita_dependiente_laboratorio',
    'id_cpt'
  ];

  public function establecimiento()
  {
    return $this->belongsTo(Establecimiento::class, 'id_establecimiento');
  }

  public function getLastGestacionByDniAseguradoForFormAtencion($asegurado_dni) {
    $model_puerperio = new Puerperio();
    $lastPuerperio = $model_puerperio -> getLastPuerperioByAseguradoDni($asegurado_dni);
    $model_gestacion = new Gestacion();
    $ultima_gestacion = null;
    if (!empty($lastPuerperio)) {
        $ultima_gestacion = $model_gestacion->getLastByAseguradoDNI($asegurado_dni);
       // dd($ultima_gestacion);
        if (!empty($ultima_gestacion)) {
            $fecha_parto = $lastPuerperio[0] -> fecha_parto;
            $fecha_atencion = $ultima_gestacion[0] -> fecha_atencion;

            if($fecha_atencion > $fecha_parto){
              return $ultima_gestacion[0];
            }
        }
        return empty($lastPuerperio) ? null : $lastPuerperio[0];
    }
    $ultima_gestacion = $model_gestacion->getLastByAseguradoDNI($asegurado_dni);
    if (empty($ultima_gestacion)) {
        return empty($lastPuerperio) ? null : $lastPuerperio[0];
    }
    return $ultima_gestacion[0];
  }

  public function registrarPrestacion($id_cita, $id_asegurado,
      $id_tipo_atencion, $id_establecimiento, $id_servicio,
      $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta,
      $id_estado_reg, $observacion, $id_receta_vale,
       $id_farmacia, $id_user, $nro_receta, $digitos_adicionales,
       $id_anestesiologo, $obs_registro, $id_receta_vale_bus,
      $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia,
      $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales,
      $p_estado,/*$tipodoc_beneficiario,$nrodocafiliado,$nombre_beneficiario,$paterno_beneficiario,$materno_beneficiario,$
      tipo_beneficiario,$nro_historia,$grado,$fecnacafiliado,$nomsexo,$cip_beneficiario,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,*/ $condicion, $cantidadReceta, $p_procedimiento,     
      $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita, $id_prestacion,$flag_ref_cref)
  {
    //print_r(array($id_cita, $id_asegurado, $id_tipo_atencion,$id_establecimiento,$id_servicio,$fecha_atencion,$fecha_alta,$id_medico,$id_motivo_alta,$id_estado_reg,$observacion,$id_receta_vale,$id_farmacia,$id_user,$nro_receta,$digitos_adicionales,$id_anestesiologo,$obs_registro,$id_receta_vale_bus,$id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias,$fecha_expedicion,$id_servicio_derivar,$id_tipo_num,$p_procedimiento, $p_producto, $p_diagnostico));exit();
    //$cad = "Select sp_insert_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $cad = "Select sp_insert_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; //49 parametros
    
    /*echo "Select sp_insert_prestacion(".$id_cita.", ".$id_asegurado.", ".$id_tipo_atencion.",".$id_establecimiento.",".$id_servicio.",'".$fecha_atencion."','".$fecha_alta."',".$id_medico.",".$id_motivo_alta.",".$id_estado_reg.",'".$observacion."',".$id_receta_vale.",".$id_farmacia.",".$id_user.",'".$nro_receta."','".$digitos_adicionales."',".$id_anestesiologo.",'".$obs_registro."',".$id_receta_vale_bus.",".$id_admision.",'".$tipo_via."','".$sub_via."','".$final_via."','".$nro_guia."','".$interconsulta."',".$interconsulta_dias.",'".$fecha_expedicion."',".$id_servicio_derivar.",".$id_tipo_num.",'".$anamnesis."','".$examen_fisico."','".$p_estado."',".$condicion.",".$cantidadReceta.",'".$p_procedimiento."', '".$p_producto."', '".$p_diagnostico."')";exit();*/
    $data = DB::select($cad, array($id_cita, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_servicio, $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia, $id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $p_estado,/*$tipodoc_beneficiario,$nrodocafiliado,$nombre_beneficiario,$paterno_beneficiario,$materno_beneficiario,$tipo_beneficiario,$nro_historia,$grado,$fecnacafiliado,$nomsexo,$cip_beneficiario,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular, 33 */ $condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita, $id_prestacion,$flag_ref_cref));

    return $data[0]->sp_insert_prestacion;
  }

  public function registrarPrestacionNew($id_cita, $id_asegurado,
      $id_tipo_atencion, $id_establecimiento, $id_servicio,
      $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta,
      $id_estado_reg, $observacion, $id_receta_vale,
       $id_farmacia, $id_user, $nro_receta, $digitos_adicionales,
       $id_anestesiologo, $obs_registro, $id_receta_vale_bus,
      $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia,
      $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales,
      $p_estado,/*$tipodoc_beneficiario,$nrodocafiliado,$nombre_beneficiario,$paterno_beneficiario,$materno_beneficiario,$
      tipo_beneficiario,$nro_historia,$grado,$fecnacafiliado,$nomsexo,$cip_beneficiario,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,*/ $condicion, $cantidadReceta, $p_procedimiento,     
      $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita)
  {
    //print_r(array($id_cita, $id_asegurado, $id_tipo_atencion,$id_establecimiento,$id_servicio,$fecha_atencion,$fecha_alta,$id_medico,$id_motivo_alta,$id_estado_reg,$observacion,$id_receta_vale,$id_farmacia,$id_user,$nro_receta,$digitos_adicionales,$id_anestesiologo,$obs_registro,$id_receta_vale_bus,$id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias,$fecha_expedicion,$id_servicio_derivar,$id_tipo_num,$p_procedimiento, $p_producto, $p_diagnostico));exit();
    //$cad = "Select sp_insert_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $cad = "Select sp_insert_prestacion_new(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    /*echo "Select sp_insert_prestacion(".$id_cita.", ".$id_asegurado.", ".$id_tipo_atencion.",".$id_establecimiento.",".$id_servicio.",'".$fecha_atencion."','".$fecha_alta."',".$id_medico.",".$id_motivo_alta.",".$id_estado_reg.",'".$observacion."',".$id_receta_vale.",".$id_farmacia.",".$id_user.",'".$nro_receta."','".$digitos_adicionales."',".$id_anestesiologo.",'".$obs_registro."',".$id_receta_vale_bus.",".$id_admision.",'".$tipo_via."','".$sub_via."','".$final_via."','".$nro_guia."','".$interconsulta."',".$interconsulta_dias.",'".$fecha_expedicion."',".$id_servicio_derivar.",".$id_tipo_num.",'".$anamnesis."','".$examen_fisico."','".$p_estado."',".$condicion.",".$cantidadReceta.",'".$p_procedimiento."', '".$p_producto."', '".$p_diagnostico."')";exit();*/
    $data = DB::select($cad, array($id_cita, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_servicio, $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia, $id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $p_estado,/*$tipodoc_beneficiario,$nrodocafiliado,$nombre_beneficiario,$paterno_beneficiario,$materno_beneficiario,$tipo_beneficiario,$nro_historia,$grado,$fecnacafiliado,$nomsexo,$cip_beneficiario,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular, 33 */ $condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita));

    return $data[0]->sp_insert_prestacion_new;
  }
  public function registrardiagnosticoNuevo($p_diagnostico,$iduser,$idprestacionuevo)
{
//print_r(array($id_cita, $id_asegurado, $id_tipo_atencion,$id_establecimiento,$id_servicio,$fecha_atencion,$fecha_alta,$id_medico,$id_motivo_alta,$id_estado_reg,$observacion,$id_receta_vale,$id_farmacia,$id_user,$nro_receta,$digitos_adicionales,$id_anestesiologo,$obs_registro,$id_receta_vale_bus,$id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias,$fecha_expedicion,$id_servicio_derivar,$id_tipo_num,$p_procedimiento, $p_producto, $p_diagnostico));exit();
//$cad = "Select sp_insert_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$cad = "Select insertar_nuevosdiagnosticoseditar(?,?,?)";
/*echo "Select sp_insert_prestacion(".$id_cita.", ".$id_asegurado.", ".$id_tipo_atencion.",".$id_establecimiento.",".$id_servicio.",'".$fecha_atencion."','".$fecha_alta."',".$id_medico.",".$id_motivo_alta.",".$id_estado_reg.",'".$observacion."',".$id_receta_vale.",".$id_farmacia.",".$id_user.",'".$nro_receta."','".$digitos_adicionales."',".$id_anestesiologo.",'".$obs_registro."',".$id_receta_vale_bus.",".$id_admision.",'".$tipo_via."','".$sub_via."','".$final_via."','".$nro_guia."','".$interconsulta."',".$interconsulta_dias.",'".$fecha_expedicion."',".$id_servicio_derivar.",".$id_tipo_num.",'".$anamnesis."','".$examen_fisico."','".$p_estado."',".$condicion.",".$cantidadReceta.",'".$p_procedimiento."', '".$p_producto."', '".$p_diagnostico."')";exit();*/
$data = DB::select($cad, array($p_diagnostico,$iduser,$idprestacionuevo));

return $data[0]->insertar_nuevosdiagnosticoseditar;
}

public function registrarPrestacion_procedimiento_Diagnostico($p_procedimiento,$iduser,$idprestacionuevo)
{
//print_r(array($id_cita, $id_asegurado, $id_tipo_atencion,$id_establecimiento,$id_servicio,$fecha_atencion,$fecha_alta,$id_medico,$id_motivo_alta,$id_estado_reg,$observacion,$id_receta_vale,$id_farmacia,$id_user,$nro_receta,$digitos_adicionales,$id_anestesiologo,$obs_registro,$id_receta_vale_bus,$id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias,$fecha_expedicion,$id_servicio_derivar,$id_tipo_num,$p_procedimiento, $p_producto, $p_diagnostico));exit();
//$cad = "Select sp_insert_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$cad = "Select insertarprocedimientosxprestacion(?,?,?)";
/*echo "Select sp_insert_prestacion(".$id_cita.", ".$id_asegurado.", ".$id_tipo_atencion.",".$id_establecimiento.",".$id_servicio.",'".$fecha_atencion."','".$fecha_alta."',".$id_medico.",".$id_motivo_alta.",".$id_estado_reg.",'".$observacion."',".$id_receta_vale.",".$id_farmacia.",".$id_user.",'".$nro_receta."','".$digitos_adicionales."',".$id_anestesiologo.",'".$obs_registro."',".$id_receta_vale_bus.",".$id_admision.",'".$tipo_via."','".$sub_via."','".$final_via."','".$nro_guia."','".$interconsulta."',".$interconsulta_dias.",'".$fecha_expedicion."',".$id_servicio_derivar.",".$id_tipo_num.",'".$anamnesis."','".$examen_fisico."','".$p_estado."',".$condicion.",".$cantidadReceta.",'".$p_procedimiento."', '".$p_producto."', '".$p_diagnostico."')";exit();*/
$data = DB::select($cad, array($p_procedimiento,$iduser,$idprestacionuevo));

return $data[0]->insertarprocedimientosxprestacion;
}

  public function registrarRecetaComplementos($idprestaciong,$id_cita, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_servicio, $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia, $id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $p_estado,/*$tipodoc_beneficiario,$nrodocafiliado,$nombre_beneficiario,$paterno_beneficiario,$materno_beneficiario,$tipo_beneficiario,$nro_historia,$grado,$fecnacafiliado,$nomsexo,$cip_beneficiario,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular, 33 */ $condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita)
{
//print_r(array($id_cita, $id_asegurado, $id_tipo_atencion,$id_establecimiento,$id_servicio,$fecha_atencion,$fecha_alta,$id_medico,$id_motivo_alta,$id_estado_reg,$observacion,$id_receta_vale,$id_farmacia,$id_user,$nro_receta,$digitos_adicionales,$id_anestesiologo,$obs_registro,$id_receta_vale_bus,$id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias,$fecha_expedicion,$id_servicio_derivar,$id_tipo_num,$p_procedimiento, $p_producto, $p_diagnostico));exit();
//$cad = "Select sp_insert_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$cad = "Select sp_receta_Complementos(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
/*echo "Select sp_insert_prestacion(".$id_cita.", ".$id_asegurado.", ".$id_tipo_atencion.",".$id_establecimiento.",".$id_servicio.",'".$fecha_atencion."','".$fecha_alta."',".$id_medico.",".$id_motivo_alta.",".$id_estado_reg.",'".$observacion."',".$id_receta_vale.",".$id_farmacia.",".$id_user.",'".$nro_receta."','".$digitos_adicionales."',".$id_anestesiologo.",'".$obs_registro."',".$id_receta_vale_bus.",".$id_admision.",'".$tipo_via."','".$sub_via."','".$final_via."','".$nro_guia."','".$interconsulta."',".$interconsulta_dias.",'".$fecha_expedicion."',".$id_servicio_derivar.",".$id_tipo_num.",'".$anamnesis."','".$examen_fisico."','".$p_estado."',".$condicion.",".$cantidadReceta.",'".$p_procedimiento."', '".$p_producto."', '".$p_diagnostico."')";exit();*/
$data = DB::select($cad, array($idprestaciong,$id_cita, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_servicio, $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia, $id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $p_estado,/*$tipodoc_beneficiario,$nrodocafiliado,$nombre_beneficiario,$paterno_beneficiario,$materno_beneficiario,$tipo_beneficiario,$nro_historia,$grado,$fecnacafiliado,$nomsexo,$cip_beneficiario,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular, 33 */ $condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita));

return $data[0]->sp_receta_complementos;
}
 
  public function adicionarDatosPrestacion($id_prestacion, $temperatura, $sa02_porcentaje, $presion_arterial_x, $presion_arterial_y, $talla_cm, $tamano_cintura_cm, $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, $fi02, $motivo_visita, $tiempo_enfermedad,$t_enfermedad,$periodo, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo, $antecedente, $antecedente_familiar)
  {
    $cad = "Select sp_adicionar_datos_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_prestacion, $temperatura, $sa02_porcentaje, $presion_arterial_x, $presion_arterial_y, $talla_cm, $tamano_cintura_cm, $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, $fi02, $motivo_visita, $tiempo_enfermedad,$t_enfermedad,$periodo,$examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo, $antecedente, $antecedente_familiar));

    return $data[0]->sp_adicionar_datos_prestacion;
  }



  public function getPrestacionHospitalizados($id){
      $cad = "

    select 
    c.nombre servicio_cargo, 
    t1.id,t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t11.nro_historia,t2.sexo,t1.condicion,t2.fecha_nac, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t12.parentesco,t2.grado,t1.id_ext,t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.motivo_visita, t1.tiempo_enfermedad, t1.examen_clinico, t1.examen_auxiliar, t1.tratamiento_auxiliar, t1.plan_trabajo,t1.antecedente_familiar, t1.antecedente,t2.id_asegurado_titular,t2.cip, t2.situacion_paciente,
    t2.nro_doc_ident,t1.ipress,  t1.upss,t1.fecha_alta,t1.id_motivo_alta,t1.id_estado_reg,t1.flag_ref_cref,
    t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t4.id_consultorio,t15.nombre servicio,t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno, t1.id_modalidad, t5.colegiatura colegiatura_medico,
    dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion,t9.id id_receta,to_char(t1.fecha_atencion,'DD/MM/YYYY HH:MI AM')fecha_atencion,t10.nombre tipo_atencion
    ,anamnesis,examen_fisico,t1.aspectos_generales,t14.nombre servicio_derivar,t1.id_cita , t1.fecha_firma, t1.id_tipo_atencion, t1.id_ext, t1.codigo_upss, t1.registrar_cita_por_medico, t2.correo, t2.domicilio, t2.telefono, t2.telefono2, t2.cip as cip_asegurado, at.tipo_doc_ident as tipodoc_titular, at.tipo_doc_ident as tipodoc_titular, at.nombre nombre_titular,at.paterno paterno_titular,at.materno materno_titular, at.nro_doc_ident nrodoctitular, t2.tipo_doc_ident,
    t12.referido, t12.establecimiento_referencia, t3.codigo establecimiento_codigo,  t2.id_tipo_doc_ident id_tipo_doc_ident , t12.numero_doc, t15.reporte_columnas_lab, t12.id_cita_lab, t3.nivel as nivel_estable, t3.codigo as codigo_renaes, t2.situacion_paciente, t1.flag as flag_atencion_medica, t1.ruta_prestacion as ruta_prestacion_atencion_medica, t1.examen_clinico_regional, t1.indicacion_terapeutica, t1.examen_ayuda_diagnostica, t1.procedimiento_medico_quirurgico, t1.interconsulta, t12.id_referencia, d.descripcion as departamento -- ,t16.nombre as servicio_referencia
    ,t1.indicacion_terapeutica, t1.cups_pnp as codigo_cups, t1.procedimiento_medico_quirurgico,t12.codigo_siteds ,t12.updated_at_cod_siteds ,t12.updated_us_cod_siteds , CASE WHEN t12.updated_at_cod_siteds::DATE != CURRENT_DATE THEN 0 ELSE 1 END AS is_valid_cod_siteds,
    lab.rua,g.id as id_gestacion, g.duda, g.fech_ult_regla ,g.fech_prob_parto ,g.semanas_gestacion ,g.num_controles, t1.fecha_atencion as fecha_hora_atencion, CASE WHEN DATE(t1.created_at) = CURRENT_DATE THEN '1' ELSE '0' END AS is_editable 
    FROM prestaciones t1
    inner join tipo_atenciones t10 on t1.id_tipo_atencion=t10.id 
    inner join asegurados t2 on t1.id_asegurado = t2.id 
    inner join asegurado_historias t11 on t1.id_asegurado = t11.id_asegurado And t1.id_establecimiento = t11.id_establecimiento 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t15 on t4.id_consultorio=t15.id
    inner join medicos t5 on t1.id_medico=t5.id
    inner join consultorios c on t1.id_servicio = c.id 
    left join departamentos d on t2.id_departamento =d.id_departamento 
    left join especializaciones t7 on t5.id_especializacion=t7.id
    left join profesiones t8 on t5.id_profesion=t8.id
    left join receta_vales t9 on t1.id=t9.id_prestacion And t9.estado<>0 
    left join citas t12 on t1.id_cita=t12.id
    left join citas t13 on t1.id_cita=t13.id_parent_cita
    left join consultorios t14 on t13.id_sub_consultorio=t14.id
    left join referencias on referencias.id_prestacion = t1.id
    left join asegurados at on at.id = t2.id
    --left join consultorios t16 on CAST(t12.servicio_referencia AS INTEGER) = t16.id
    --left join establecimientos t17 on referencias.id_establecimiento_des=t17.id
    left join gestaciones g on t1.id_gestacion = g.id 
    left join laboratorios lab on t1.id = lab.id_prestacion
    Where t1.id=" . $id;
    
    
    $data = DB::select($cad);
    if(count($data)!= 0){
      if($data[0]->id_ext != null){        
        if($data[0]->id_tipo_atencion == 3){//hospitalizacion
          $idExt = $data[0]->id_ext;
          $cama_info = DB::select("select c.nombre nombre_cama,cuar.nombre nombre_cuarto, p.nombre pabellon
          from hospitalizaciones h
          left join camas c on h.id_cama_estado = c.id
          left join cuartos cuar on c.id_cuarto=cuar.id
          left join pabellones p on p.id=h.id_pabellon
          where h.id = $idExt;");
        }else{//emergencia
          $idExt = $data[0]->id_ext;
          $cama_info = DB::select("select ca.nombre nombre_cama 
          ,cuar.nombre nombre_cuarto,TO_CHAR(e.created_at, 'DD-MM-YYYY') fecha_evaluacion
          from emergencias e  
          left join camas ca on e.cama_estado =ca.id 
          left join cuartos cuar on ca.id_cuarto=cuar.id
          
          where e.id = $idExt;");
        }
        
        
        $data[0]->nmro_cama = "";
        $data[0]->ubicasion = "";
        $data[0]->fecha_evaluacion = "";
        if(count($cama_info)!= 0){
          $data[0]->nmro_cama = $cama_info[0]->nombre_cama;
          if($data[0]->id_tipo_atencion == 3){//hospitalizacion
            $data[0]->ubicacion = $cama_info[0]->pabellon;
          }else{//emergencia
            $data[0]->fecha_evaluacion  = $cama_info[0]->fecha_evaluacion;
            $data[0]->ubicacion = $cama_info[0]->nombre_cuarto;
          }
          
        }
      }
     
    }
    

    return $data[0];
  }

public function GetPrestacionById($id)
  {
    
    $cad = "
    select t1.created_at,t1.id,t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t11.nro_historia,t2.sexo,t1.condicion,t2.fecha_nac,tb.descripcion, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t12.parentesco,t2.grado,t1.id_ext,t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.motivo_visita, t1.tiempo_enfermedad, t1.t_enfermedad,t1.periodo, t1.examen_clinico, t1.examen_auxiliar, t1.tratamiento_auxiliar, t1.plan_trabajo,t1.antecedente_familiar, t1.antecedente,t2.id_asegurado_titular,t2.cip, t2.situacion_paciente,
    t2.nro_doc_ident,t1.ipress,  t1.upss, (CASE WHEN ups.codigo IS NOT NULL THEN ups.descripcion_upss else '-' END) as nombre_upss,t1.fecha_alta,t1.id_motivo_alta,t1.id_estado_reg,t1.flag_ref_cref,
    t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t4.id_consultorio,t15.nombre servicio,t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,tdi.descripcion,t5.colegiatura, t1.id_modalidad, t5.colegiatura colegiatura_medico,
    dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion,t9.id id_receta,to_char(t1.fecha_atencion,'dd-mm-YYYY')fecha_atencion,t10.nombre tipo_atencion
    ,anamnesis,examen_fisico,t1.aspectos_generales,t14.nombre servicio_derivar,t1.id_cita , t1.fecha_firma, t1.id_tipo_atencion, t1.id_ext, t1.codigo_upss, t1.registrar_cita_por_medico, t2.correo, t2.domicilio, t2.telefono, t2.telefono2, t2.cip as cip_asegurado, at.tipo_doc_ident as tipodoc_titular, at.nombre nombre_titular,at.paterno paterno_titular,at.materno materno_titular, at.nro_doc_ident nrodoctitular,at.cip cip_titular,at.grado grado_titular, t2.tipo_doc_ident,
    t12.referido, t12.establecimiento_referencia, t3.codigo establecimiento_codigo,  t2.id_tipo_doc_ident id_tipo_doc_ident , t12.numero_doc, t15.reporte_columnas_lab, t12.id_cita_lab, t3.nivel as nivel_estable, t3.codigo as codigo_renaes, t2.situacion_paciente, t1.flag as flag_atencion_medica, t1.ruta_prestacion as ruta_prestacion_atencion_medica, t1.examen_clinico_regional, t1.indicacion_terapeutica, t1.examen_ayuda_diagnostica, t1.procedimiento_medico_quirurgico, t1.interconsulta, t12.id_referencia, d.descripcion as departamento -- ,t16.nombre as servicio_referencia
    ,t1.indicacion_terapeutica, t1.cups_pnp as codigo_cups, t1.procedimiento_medico_quirurgico,t12.codigo_siteds ,t12.updated_at_cod_siteds ,t12.updated_us_cod_siteds , CASE WHEN t12.updated_at_cod_siteds::DATE != CURRENT_DATE THEN 0 ELSE 1 END AS is_valid_cod_siteds,
    lab.rua,g.id as id_gestacion, g.duda, g.fech_ult_regla ,g.fech_prob_parto ,g.semanas_gestacion ,g.num_controles, 
    t1.fecha_atencion as fecha_hora_atencion,
    t1.flag as flag_firma, 
    CASE WHEN DATE(t1.created_at) = CURRENT_DATE THEN '1' ELSE '0' END AS is_editable ,t15.flag_quirurgico
    FROM prestaciones t1
    inner join tipo_atenciones t10 on t1.id_tipo_atencion=t10.id 
    left join upsses ups on t1.codigo_upss=ups.codigo
    inner join asegurados t2 on t1.id_asegurado = t2.id and t2.id_estado_reg = 1
    inner join asegurado_historias t11 on t1.id_asegurado = t11.id_asegurado And t1.id_establecimiento = t11.id_establecimiento 
    left join tipo_beneficiarios tb on t2.id_tipo_beneficiario =tb.id 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t15 on t4.id_consultorio=t15.id
    inner join medicos t5 on t1.id_medico=t5.id
    left join departamentos d on t2.id_departamento =d.id_departamento 
    left join especializaciones t7 on t5.id_especializacion=t7.id
    left join profesiones t8 on t5.id_profesion=t8.id
    left join receta_vales t9 on t1.id=t9.id_prestacion And t9.estado<>0 
    left join citas t12 on t1.id_cita=t12.id
    left join citas t13 on t1.id_cita=t13.id_parent_cita
    left join consultorios t14 on t13.id_sub_consultorio=t14.id
    left join referencias on referencias.id_prestacion = t1.id
    left join asegurados at on at.id = t2.id_asegurado_titular
    LEFT JOIN tipo_documento_identidad tdi ON CAST(t5.tipo_documento AS INTEGER) = CAST(tdi.codigo AS INTEGER)
    --left join consultorios t16 on CAST(t12.servicio_referencia AS INTEGER) = t16.id
    --left join establecimientos t17 on referencias.id_establecimiento_des=t17.id
    left join gestaciones g on t1.id_gestacion = g.id 
    left join laboratorios lab on t1.id = lab.id_prestacion
    Where t1.id=" . $id;
    
    
    
    $data = DB::select($cad);
    if (empty($data)) {
        return null; 
    }
      if(count($data)!= 0 ){
        //dd($data[0]);
        if($data[0]->id_ext != null){        
          if($data[0]->id_tipo_atencion == 3){//hospitalizacion
            $idExt = $data[0]->id_ext;
            $cama_info = DB::select("select c.nombre nombre_cama,cuar.nombre nombre_cuarto, p.nombre pabellon
            from hospitalizaciones h
            left join camas c on h.id_cama_estado = c.id
            left join cuartos cuar on c.id_cuarto=cuar.id
            left join pabellones p on p.id=h.id_pabellon
            where h.id = $idExt;");
            //dd($data);
          }elseif($data[0]->id_tipo_atencion == 2){//emergencia
            $idExt = $data[0]->id_ext;
            $cama_info = DB::select("select ca.nombre nombre_cama 
            ,cuar.nombre nombre_cuarto,TO_CHAR(e.created_at, 'DD-MM-YYYY') fecha_evaluacion
            from emergencias e  
            left join camas ca on e.cama_estado =ca.id 
            left join cuartos cuar on ca.id_cuarto=cuar.id
            
            where e.id = $idExt;");
          }
          $data[0]->nmro_cama = "";
          $data[0]->ubicacion = "";
          $data[0]->fecha_evaluacion = "";
          if(count($cama_info)!= 0){
            $data[0]->nmro_cama = $cama_info[0]->nombre_cama;
            if($data[0]->id_tipo_atencion == 3){//hospitalizacion
              // dd("hola");
              $data[0]->ubicacion = $cama_info[0]->pabellon;
            }else{//emergencia
              $data[0]->fecha_evaluacion  = $cama_info[0]->fecha_evaluacion;
              $data[0]->ubicacion = $cama_info[0]->nombre_cuarto;
            }
            
          }
        }
      }
    return $data[0];
  }

public function GetPrestacionByIdHospitalizacion($id)
  {
    
    $cad = "
    select t1.id,t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t11.nro_historia,t2.sexo,t1.condicion,t2.fecha_nac,tb.descripcion, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t12.parentesco,t2.grado,t1.id_ext,t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.motivo_visita, t1.tiempo_enfermedad, t1.examen_clinico, t1.examen_auxiliar, t1.tratamiento_auxiliar, t1.plan_trabajo,t1.antecedente_familiar, t1.antecedente,t2.id_asegurado_titular,t2.cip, t2.situacion_paciente,
    t2.nro_doc_ident,t1.ipress,  t1.upss,t1.fecha_alta,t1.id_motivo_alta,t1.id_estado_reg,t1.flag_ref_cref,
    t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t4.id_consultorio,t15.nombre servicio,t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,tdi.descripcion,t5.colegiatura, t1.id_modalidad, t5.colegiatura colegiatura_medico,
    dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion,TO_CHAR(TO_TIMESTAMP(t1.fecha_firma, 'DD-MM-YYYY'), 'DD/MM/YYYY HH12:MI:SS AM') AS fecha_atencion,t10.nombre tipo_atencion
    ,anamnesis,examen_fisico,t1.aspectos_generales,t14.nombre servicio_derivar,t1.id_cita , t1.fecha_firma, t1.id_tipo_atencion, t1.id_ext, t1.codigo_upss, t1.registrar_cita_por_medico, t2.correo, t2.domicilio, t2.telefono, t2.telefono2, t2.cip as cip_asegurado, at.tipo_doc_ident as tipodoc_titular, at.tipo_doc_ident as tipodoc_titular, at.nombre nombre_titular,at.paterno paterno_titular,at.materno materno_titular, at.nro_doc_ident nrodoctitular, t2.tipo_doc_ident,
    t12.referido, t12.establecimiento_referencia, t3.codigo establecimiento_codigo,  t2.id_tipo_doc_ident id_tipo_doc_ident , t12.numero_doc, t15.reporte_columnas_lab, t12.id_cita_lab, t3.nivel as nivel_estable, t3.codigo as codigo_renaes, t2.situacion_paciente, t1.flag as flag_atencion_medica, t1.ruta_prestacion as ruta_prestacion_atencion_medica, t1.examen_clinico_regional, t1.indicacion_terapeutica, t1.examen_ayuda_diagnostica, t1.procedimiento_medico_quirurgico, t1.interconsulta, t12.id_referencia, d.descripcion as departamento -- ,t16.nombre as servicio_referencia
    ,t1.indicacion_terapeutica, t1.cups_pnp as codigo_cups, t1.procedimiento_medico_quirurgico,t12.codigo_siteds ,t12.updated_at_cod_siteds ,t12.updated_us_cod_siteds , CASE WHEN t12.updated_at_cod_siteds::DATE != CURRENT_DATE THEN 0 ELSE 1 END AS is_valid_cod_siteds,
    t1.fecha_atencion as fecha_hora_atencion, 
    CASE WHEN DATE(t1.created_at) = CURRENT_DATE THEN '1' ELSE '0' END AS is_editable 
    FROM prestaciones t1
    inner join tipo_atenciones t10 on t1.id_tipo_atencion=t10.id 
    inner join asegurados t2 on t1.id_asegurado = t2.id 
    inner join asegurado_historias t11 on t1.id_asegurado = t11.id_asegurado And t1.id_establecimiento = t11.id_establecimiento 
    inner join tipo_beneficiarios tb on t2.id_tipo_beneficiario =tb.id 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t15 on t4.id_consultorio=t15.id
    inner join medicos t5 on t1.id_medico=t5.id
    left join departamentos d on t2.id_departamento =d.id_departamento 
    left join especializaciones t7 on t5.id_especializacion=t7.id
    left join profesiones t8 on t5.id_profesion=t8.id
    left join citas t12 on t1.id_cita=t12.id
    left join citas t13 on t1.id_cita=t13.id_parent_cita
    left join consultorios t14 on t13.id_sub_consultorio=t14.id
    left join referencias on referencias.id_prestacion = t1.id
    left join asegurados at on at.id = t2.id
    LEFT JOIN tipo_documento_identidad tdi ON CAST(t5.tipo_documento AS INTEGER) = CAST(tdi.codigo AS INTEGER)
    Where t1.id=" . $id;
    
    
    $data = DB::select($cad);
      if(count($data)!= 0 ){
        //dd($data[0]);
        if($data[0]->id_ext != null){        
          if($data[0]->id_tipo_atencion == 3){//hospitalizacion
            $idExt = $data[0]->id_ext;
            $cama_info = DB::select("select c.nombre nombre_cama,cuar.nombre nombre_cuarto, p.nombre pabellon
            from hospitalizaciones h
            left join camas c on h.id_cama_estado = c.id
            left join cuartos cuar on c.id_cuarto=cuar.id
            left join pabellones p on p.id=h.id_pabellon
            where h.id = $idExt;");
            //dd($data);
          }elseif($data[0]->id_tipo_atencion == 2){//emergencia
            $idExt = $data[0]->id_ext;
            $cama_info = DB::select("select ca.nombre nombre_cama 
            ,cuar.nombre nombre_cuarto,TO_CHAR(e.created_at, 'DD-MM-YYYY') fecha_evaluacion
            from emergencias e  
            left join camas ca on e.cama_estado =ca.id 
            left join cuartos cuar on ca.id_cuarto=cuar.id
            
            where e.id = $idExt;");
          }
          $data[0]->nmro_cama = "";
          $data[0]->ubicacion = "";
          $data[0]->fecha_evaluacion = "";
          if(count($cama_info)!= 0){
            $data[0]->nmro_cama = $cama_info[0]->nombre_cama;
            if($data[0]->id_tipo_atencion == 3){//hospitalizacion
              // dd("hola");
              $data[0]->ubicacion = $cama_info[0]->pabellon;
            }else{//emergencia
              $data[0]->fecha_evaluacion  = $cama_info[0]->fecha_evaluacion;
              $data[0]->ubicacion = $cama_info[0]->nombre_cuarto;
            }
            
          }
        }
      }
    
    //dd($data[0]);
    return $data[0];
  }



  public function GetPrestacionByIdPrestacion($id_prestacion)
  {
    $datos = Prestaciones::where('id', '=', $id_prestacion)->first();
    return $datos;
  }

  
  public function getMedicoForId($idMedico){
    $cad = "select m.*, p.nombre nombre_profecion from medicos m 
            left join profesiones p on p.id = m.id_profesion 
            where m.id =$idMedico
    "; 
    $data = DB::select($cad);
    return $data[0];
  }

  public function GetDiagnosticoByIdPrestacion($id)
  {
    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,t2.codigo, t1.id_evaluacion, t1.id_categoria
,(case when t2.codigo='Z21.X' or t2.codigo ilike 'B20%' Then '' else t2.nombre end)nombre, t1.id_clasificacion_diagnosticos,td.descripcion
from receta_diagnosticos t1
inner join diagnosticos t2 on t1.id_diagnostico=t2.id and t1.id_interconsulta=0
inner join tipo_diagnosticos td on t1.id_tipo_diagnostico =td.id 
where t1.id_prestacion=" . $id . " and t1.id_evaluacion = 0  and t1.id_consejeria = 0 and t1.id_interconsulta = 0
and t1.estado=1 
ORDER BY t1.id_diagnostico ASC";
    
    $data = DB::select($cad);
    return $data;
  }
  public function GetDiagnosticoByIdPrestacion2($id)
  {
    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,t2.codigo, t1.id_evaluacion, t1.id_categoria
            ,(case when t2.codigo='Z21.X' or t2.codigo ilike 'B20%' Then '' else t2.nombre end)nombre, t1.id_clasificacion_diagnosticos,td.descripcion
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico=t2.id and t1.id_interconsulta=0
            inner join tipo_diagnosticos td on t1.id_tipo_diagnostico =td.id 
            where t1.id_prestacion=" . $id . " and t1.id_evaluacion = 0  and t1.id_consejeria = 0 and t1.id_interconsulta = 0
            and t1.estado=1 
            and not exists (
                select 1 from receta_vales rv 
                where rv.id = t1.id_receta_vale 
                and rv.flag_origen = 1
            )
            and (t1.id_clasificacion_diagnosticos is null or t1.id_clasificacion_diagnosticos <> 3)
            ORDER BY t1.id_diagnostico ASC";
    $data = DB::select($cad);
    return $data;
  }

  public function GetDiagnosticoByIdEgresoAlta($id)
  {
    $cad = "select distinct t1.id_diagnostico, coalesce(t1.id_tipo_diagnostico,0) id_tipo_diagnostico, t2.codigo,
            t1.id_evaluacion, t1.id_categoria,
            (case when t2.codigo='Z21.X' or t2.codigo ilike 'B20%' Then '' else t2.nombre end) nombre,
            t1.id_clasificacion_diagnosticos, td.descripcion
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico = t2.id and t1.id_interconsulta = 0
            inner join tipo_diagnosticos td on t1.id_tipo_diagnostico = td.id
            where t1.id_prestacion = ?
            and t1.estado = 1
            and t1.id_clasificacion_diagnosticos = 3
            ORDER BY t1.id_diagnostico ASC";
    $data = DB::select($cad, [$id]);
    return $data;
  }

  public function GetDiagnosticoByIdAltaPrestacion($id)
  {
    $cad = "select distinct t1.id_diagnostico, coalesce(t1.id_tipo_diagnostico,0) id_tipo_diagnostico, t2.codigo, 
            t1.id_evaluacion, t1.id_categoria,
            (case when t2.codigo='Z21.X' or t2.codigo ilike 'B20%' Then '' else t2.nombre end) nombre, 
            t1.id_clasificacion_diagnosticos, td.descripcion
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico = t2.id and t1.id_interconsulta = 0
            inner join tipo_diagnosticos td on t1.id_tipo_diagnostico = td.id 
            where t1.id_prestacion = " . $id . " and t1.id_evaluacion = 0  and t1.id_consejeria = 0 and t1.id_interconsulta = 0
            and t1.estado = 1 
            and t1.id_clasificacion_diagnosticos = 3
            ORDER BY t1.id_diagnostico ASC";
    $data = DB::select($cad);
    return $data;
  }
  public function GetDiagnosticoByIdIntervencion($id)
  {
    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,t2.codigo, t1.id_evaluacion, t1.id_categoria
,(case when t2.codigo='Z21.X' or t2.codigo ilike 'B20%' Then '' else t2.nombre end)nombre, t1.id_clasificacion_diagnosticos,td.descripcion
from receta_diagnosticos t1
inner join diagnosticos t2 on t1.id_diagnostico=t2.id and t1.id_interconsulta=0
inner join tipo_diagnosticos td on t1.id_tipo_diagnostico =td.id 
where t1.id_intervencion=" . $id . " and t1.id_evaluacion = 0  and t1.id_consejeria = 0 and t1.id_interconsulta = 0
and t1.estado=1 
ORDER BY t1.id_diagnostico ASC";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetDiagnosticoByIdPrestacionHospitalizacion($id)
  {

    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo,t1.id_categoria
,(case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos, LEFT(td.descripcion, 1) AS primera_letra_descripcion
from receta_diagnosticos t1
inner join diagnosticos t2 on t1.id_diagnostico=t2.id
inner join tipo_diagnosticos td on t1.id_tipo_diagnostico = td.id
where t1.id_prestacion=" . $id . " and t1.id_evaluacion = 0 and t1.id_interconsulta=0 and t1.id_consejeria = 0
and t1.estado=1
group by t1.id_diagnostico,t1.id_tipo_diagnostico, t1.id_categoria,t1.id_clasificacion_diagnosticos ,  t2.codigo, t2.nombre, td.descripcion
order by t1.id_diagnostico asc
";
    $data = DB::select($cad);
    return $data;
  }

  public function GetDiagnosticoByIdPrestacionHospitalizacionEvolucion($id)
  {

    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo,t1.id_categoria
,(case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos, LEFT(td.descripcion, 1) AS primera_letra_descripcion
from receta_diagnosticos t1
inner join diagnosticos t2 on t1.id_diagnostico=t2.id
inner join tipo_diagnosticos td on t1.id_tipo_diagnostico = td.id
where t1.id_prestacion=" . $id . " and t1.id_evaluacion != 0 and t1.id_interconsulta=0
and t1.estado=1
group by t1.id_diagnostico,t1.id_tipo_diagnostico, t1.id_categoria,t1.id_clasificacion_diagnosticos ,  t2.codigo, t2.nombre, td.descripcion
order by t1.id_diagnostico asc
";
    $data = DB::select($cad);
    return $data;
  }
  
  public function GetDiagnosticoByIdPrestacionEmergencia($id)
  {
    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,t2.codigo, t1.id_evaluacion, t1.id_categoria
            ,(case when t2.codigo='Z21.X' or t2.codigo ilike 'B20%' Then '' else t2.nombre end)nombre, t1.id_clasificacion_diagnosticos, LEFT(td.descripcion, 1) AS descripcion
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico=t2.id and t1.id_interconsulta=0
            inner join tipo_diagnosticos td on t1.id_tipo_diagnostico =td.id 
            where t1.id_prestacion=" . $id . " and t1.id_evaluacion = 0  and t1.id_consejeria = 0 and t1.id_interconsulta = 0
            and t1.estado=1 
            and not exists (
                select 1 from receta_vales rv 
                where rv.id = t1.id_receta_vale 
                and rv.flag_origen = 1
            )
            and (t1.id_clasificacion_diagnosticos is null or t1.id_clasificacion_diagnosticos <> 3)
            ORDER BY t1.id_diagnostico ASC";
    $data = DB::select($cad);
    return $data;
  }
  public function GetDiagnosticoByIdConsejeriaEmergencia($id)
  {
    $cad = "select distinct t1.id, t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo,t1.id_categoria
          ,(case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos, LEFT(td.descripcion, 1) AS primera_letra_descripcion
          from receta_diagnosticos t1
          inner join diagnosticos t2 on t1.id_diagnostico=t2.id
          inner join tipo_diagnosticos td on t1.id_tipo_diagnostico = td.id
          where t1.id_consejeria=" . $id . " and t1.id_evaluacion = 0 and t1.id_interconsulta=0
          and t1.estado=1
          order by t1.id asc
          ";
    $data = DB::select($cad);
    return $data;
  }
  public function GetDiagnosticoByIdPrestacionEmergencia2($id)
  {

    $cad = "select distinct t1.id, t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo as codigo_diagnostico,t1.id_categoria
,(case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos, LEFT(td.descripcion, 1) AS primera_letra_descripcion, t2.nombre as descripcion_diagnostico
, td.descripcion as descripcion_tipo_diagnosticos, t1.id_evaluacion
from receta_diagnosticos t1
inner join diagnosticos t2 on t1.id_diagnostico=t2.id
inner join tipo_diagnosticos td on t1.id_tipo_diagnostico = td.id
where t1.id_prestacion=" . $id . " and t1.id_evaluacion = 0 and t1.id_interconsulta=0 and t1.id_consejeria = 0
and t1.estado=1
order by t1.id asc
";
    $data = DB::select($cad);
    return $data;
  }

  public function GetCargaDiagnosticoByIdPrestacion($id)
  {

    $cad = "select distinct t1.id_diagnostico as id,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo
,(case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos
from receta_diagnosticos t1
inner join diagnosticos t2 on t1.id_diagnostico=t2.id
where t1.id_prestacion=" . $id . "
and t1.estado=1";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetDiagnosticoByIdPrestacionEvaluacion($id, $id_evaluacion,$id_interconsulta=0)
  {
    $cad = "SELECT t1.id, t1.id_diagnostico, COALESCE(t1.id_tipo_diagnostico, 0) AS id_tipo_diagnostico, codigo,
          (CASE 
              WHEN codigo = 'Z21.X' OR codigo ILIKE 'B20%' THEN ''
              ELSE nombre
          END) AS nombre, id_clasificacion_diagnosticos, LEFT(td.descripcion, 1) AS primera_letra_descripcion, t1.created_at 
      FROM receta_diagnosticos t1
      INNER JOIN diagnosticos t2 ON t1.id_diagnostico = t2.id
      INNER JOIN tipo_diagnosticos td ON t1.id_tipo_diagnostico = td.id
      WHERE t1.id_prestacion = " . $id;

      // if ($id_evaluacion != 0) {
      //     $cad .= " AND t1.id_interconsulta=0 AND t1.id_evaluacion = " . $id_evaluacion;
      // } else {
      //   $cad .=" AND t1.id_interconsulta=0 AND t1.id_evaluacion=0";
      // }
      if ($id_evaluacion != 0 && $id_interconsulta != 0) {
          $cad .= " AND t1.id_evaluacion=0 AND t1.id_interconsulta = " . $id_interconsulta;
      }elseif($id_evaluacion != 0 && $id_interconsulta == 0) {
          $cad .= " AND t1.id_interconsulta=0 AND t1.id_evaluacion = " . $id_evaluacion;
      }else{
        $cad .=" AND t1.id_interconsulta=0 AND t1.id_evaluacion=0";
      }

      $cad .= " AND t1.estado = 1 
      ORDER BY t1.id ASC";

      $data = DB::select($cad);
      return $data;
  }
  /*public function GetDiagnosticoByIdPrestacionInterconsulta($id, $id_interconsulta)
  {

    $cad = "select distinct t1.id, t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo
,(case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos
from receta_diagnosticos t1
inner join diagnosticos t2 on t1.id_diagnostico=t2.id
where t1.id_prestacion=" . $id;
if($id_interconsulta!=null):
  $cad .=" and t1.id_interconsulta=".$id_interconsulta;
endif;
$cad .="
  and t1.estado=1
  order by t1.id asc
";
    $data = DB::select($cad);
    return $data;
  }
*/
  public function GetProcedimientoByIdPrestacion($id)
  {

    $cad = "select codigo,descripcion nombre, cantidad
from prestacion_procedimientos t1
inner join procedimientos t2 on t1.id_procedimiento=t2.id
where t1.id_prestacion=" . $id . " and t1.id_consejeria = 0 and id_enfermeria is null
and t1.id_estado_reg=1";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientoByIdPrestacionNew($id)
  {

    $cad = "select codigo,descripcion nombre, cantidad
from prestacion_procedimientos t1
inner join procedimientos t2 on t1.id_procedimiento=t2.id
where t1.id_prestacion=" . $id . " and t2.tipo_procedimiento=1
and t1.id_estado_reg=1 and t2.tipo_procedimiento=1  and t1.id_consejeria = 0 and id_enfermeria is null
order by t1.id asc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacion($id, $tipo)
  {

    $cad = "select t1.id as cod, codigo,descripcion nombre, cantidad
from prestacion_procedimientos t1
inner join procedimientos t2 on t1.id_procedimiento=t2.id
--INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
where t1.id_prestacion=" . $id . " and t1.tipo_procedimiento=" . $tipo . "
and t1.id_estado_reg=1 and t1.id_evolucion_enfermeria  is null  and t1.id_consejeria = 0  /*AND t3.id_evaluacion = 0  AND t3.id_interconsulta = 0 */";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionEmergencia($id, $tipo)
  {

    $cad = "select t1.id as cod, codigo,descripcion nombre, cantidad
from prestacion_procedimientos t1
inner join procedimientos t2 on t1.id_procedimiento=t2.id
INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
where t1.id_prestacion=" . $id . " AND t3.id_evaluacion = 0 and t1.tipo_procedimiento=" . $tipo . "
and t1.id_estado_reg=1 AND t3.id_interconsulta = 0 and t1.id_evolucion_enfermeria  is null  and t1.id_consejeria = 0 
order by t1.id asc
";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionHospitalizacion($id, $tipo)
  {

    $cad = "select codigo,descripcion nombre, cantidad
from prestacion_procedimientos t1
inner join procedimientos t2 on t1.id_procedimiento=t2.id
INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
where t1.id_prestacion=" . $id . " AND t3.id_evaluacion = 0 and t1.tipo_procedimiento=" . $tipo . "
and t1.id_estado_reg=1 AND t3.id_interconsulta = 0
group by codigo,descripcion, cantidad 
order by codigo asc
";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionydiag($id, $tipo)
{
  $query = "SELECT t1.id as cod, t2.codigo, t2.descripcion nombre, t1.cantidad, t3.id_diagnosticos as id_diagnostico, t1.id_procedimiento
  FROM prestacion_procedimientos t1
  INNER JOIN procedimientos t2 ON t1.id_procedimiento = t2.id
  INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
  WHERE t1.id_prestacion = " . $id . " AND t3.id_evaluacion = 0 AND t1.tipo_procedimiento = " . $tipo . "
  AND t1.id_estado_reg = 1 AND t3.estado = 1 AND t3.id_interconsulta = 0 
  order by t1.id asc
  ";
    $data = DB::select($query);
    return $data;
}

public function GetProcedimientosByIdPrestacionEmergenciaPNP($id, $tipo)
  {

    $cad = "select codigo,descripcion nombre
from prestacion_procedimientos t1
inner join procedimientos t2 on t1.id_procedimiento=t2.id
INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
where t1.id_prestacion=" . $id . "  and t1.tipo_procedimiento=" . $tipo . "
and t1.id_estado_reg=1 AND t3.id_interconsulta = 0  and t1.id_consejeria = 0 
group by codigo,descripcion
order by codigo asc
";
    
    $data = DB::select($cad);
    return $data;
  }


public function GetProcedimientosByIdPrestacionydiagEvaluacion($id, $tipo, $id_evaluacion)
{
  $query = "select p.id cod, p.codigo, p.descripcion nombre, 1 cantidad, d.id id_diagnostico
  from procedimientos_diagnoticos pd 
  inner join procedimientos p on p.id = pd.id_procedimientos
  inner join diagnosticos d on d.id = pd.id_diagnosticos
  where pd.id_prestacion = " . $id . " AND pd.id_evaluacion = " . $id_evaluacion . "
   AND pd.estado = 1 AND pd.id_interconsulta = 0 
  order by p.id asc
  ";
  
    $data = DB::select($query);
    return $data;
}

/*public function GetProcedimientosByIdPrestacionydiagEvaluacion($id, $tipo, $id_evaluacion)
{
  $query = "SELECT t1.id as cod, t2.codigo, t2.descripcion nombre, t1.cantidad, t3.id_diagnosticos as id_diagnostico
  FROM prestacion_procedimientos t1
  INNER JOIN procedimientos t2 ON t1.id_procedimiento = t2.id
  INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion --AND t3.id_procedimientos = t1.id_procedimiento
  WHERE t1.id_prestacion = " . $id . " AND t3.id_evaluacion = " . $id_evaluacion . " AND t1.tipo_procedimiento = " . $tipo . "
  AND t1.id_estado_reg = 1 AND t3.estado = 1 AND t3.id_interconsulta = 0 
  order by t1.id asc
  ";
  
    $data = DB::select($query);
    return $data;
}*/
/*
public function GetProcedimientosByIdPrestacionydiagInterconsulta($id, $tipo, $id_interconsulta)
{
  $query = "SELECT t1.id as cod, t2.codigo, t2.descripcion nombre, t1.cantidad, t3.id_diagnosticos as id_diagnostico
  FROM prestacion_procedimientos t1
  INNER JOIN procedimientos t2 ON t1.id_procedimiento = t2.id
  INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
  WHERE t1.id_prestacion = " . $id . " AND t3.id_interconsulta = " . $id_interconsulta . " AND t1.tipo_procedimiento = " . $tipo . "
  AND t1.id_estado_reg = 1 AND t3.estado = 1 
  order by t1.id asc
  ";
  
    $data = DB::select($query);
    return $data;
}
 */ 
public function GetProcedimientosByIdPrestacionydiagInterconsulta($id, $tipo, $id_interconsulta)
{
  $query = "
select p.id cod, p.codigo, p.descripcion nombre, 1 cantidad, d.id id_diagnostico
  from procedimientos_diagnoticos pd 
  inner join procedimientos p on p.id = pd.id_procedimientos
  inner join diagnosticos d on d.id = pd.id_diagnosticos
  where pd.id_prestacion = " . $id . " AND pd.id_interconsulta = " . $id_interconsulta . "
   AND pd.estado = 1
  order by p.id asc
";

  
    $data = DB::select($query);
    return $data;
}


  public function GetProcedimientosByIdPrestacionLab($id)
  {

    $cad = "select t2.id, t2.codigo_lab codigo, t2.descripcion_corta nombre, t1.cantidad
from prestacion_procedimientos t1
inner join labnivelatenciones t2 on t1.id_rua=t2.id
where t1.id_prestacion=" . $id . "
and t1.id_estado_reg=1";
    
    $data = DB::select($cad);
    return $data;
  }
  
   public function GetPrestacionLaboratorio($id,$id_laboratorio)
  {
    $cad = "
          select
          t2.id,
          t2.codigo_lab codigo,
          t2.descripcion_corta as nombre,
          p.descripcion as nombre_completo,
          t1.cantidad,
          t1.created_at,
          t2.id_procedimiento,
          t1.observacion,
          d.nombre diagnostico,
          d.codigo || ' - ' || d.nombre as relacion_diagnostico,
          d.codigo as cod_diag,
          t1.tipo_muestra_microbiologia,
          t1.id_solicitud_procedimiento,
          t1.id as id_prestacion_procedimiento,
          tm.nombre nombre_tipo_muestra,
          t1.cantidad ,
          t1.id_diagnostico ,
          t1.archivo_resultado ,
          t1.observacion ,
          t1.confirmar_toma,
          case when ppp.pendiente_laboratorio is null then ''
            when ppp.pendiente_laboratorio is true then 'SI'
            when ppp.pendiente_laboratorio is false then 'NO' end pendiente_laboratorio
        from prestacion_procedimientos t1
        inner join labnivelatenciones t2 on t1.id_rua = t2.id
        inner join procedimientos p on t1.id_procedimiento = p.id
        left join diagnosticos d on d.id = t1.id_diagnostico
        left join tipo_muestra tm on tm.id = t2.id_tipo_muestra
        LEFT JOIN prestacion_procedimientos_proveedor ppp on t1.id = ppp.id_prestacion_procedimientos
        where t1.id_prestacion = $id and t1.id_lab_rad = $id_laboratorio and t1.id_estado_reg = 1
        order by t1.id asc
        ";    
    $data = DB::select($cad);
    return $data;
  }

  public function GetPrestacionLaboratorio_agrupado($id_cita)
  {
    $cad = "select
            t2.id,
            t2.codigo_lab codigo,
            t2.descripcion_corta as nombre,
            p.descripcion as nombre_completo,
            t1.cantidad,
            t1.created_at,
            t2.id_procedimiento,
            d.nombre diagnostico,
            d.codigo || ' - ' || d.nombre as relacion_diagnostico,
            d.codigo as cod_diag,
            t1.tipo_muestra_microbiologia,
            t1.id_solicitud_procedimiento,
            t1.id as id_prestacion_procedimiento,
            tm.nombre nombre_tipo_muestra,
            t1.cantidad ,
            t1.id_diagnostico ,
            t1.archivo_resultado ,
            t1.confirmar_toma,
            t1.reembolso,
            t1.tiempo,
            t1.id_lab_rad,
            co.nombre servicio,
            pr.id_asegurado,
            t2.cpms_personalizado,
            pr.id as id_prestacion,
            t1.id_rua,
            t1.migrado_lab,
            ppp.pendiente_laboratorio
            /*case when ppp.pendiente_laboratorio is null then ''
              when ppp.pendiente_laboratorio is true then 'SI'
              when ppp.pendiente_laboratorio is false then 'NO' end pendiente_laboratorio*/
          from prestaciones pr
            inner join sub_consultorios sc on sc.id = pr.id_sub_consultorio 
            inner join consultorios co on co.id = sc.id_consultorio
            inner join prestacion_procedimientos t1 on pr.id = t1.id_prestacion
            inner join labnivelatenciones t2 on t1.id_rua = t2.id
            inner join procedimientos p on t1.id_procedimiento = p.id
            left join diagnosticos d on d.id = t1.id_diagnostico
            left join tipo_muestra tm on tm.id = t2.id_tipo_muestra
            LEFT JOIN prestacion_procedimientos_proveedor ppp on t1.id = ppp.id_prestacion_procedimientos
          where 
            pr.id_cita_dependiente_laboratorio = $id_cita
            and t1.tipo_procedimiento = 2 
            and t1.id_estado_reg = 1
          order by t1.id asc
        ";    
    $data = DB::select($cad);
    return $data;
  }

  public function GetPrestacionLaboratorioEvaluacion($id,$id_laboratorio,$id_evaluacion)
  {

    $cad = "select t2.id, t2.codigo_lab codigo, t2.descripcion_corta as nombre, p.descripcion as nombre_completo, t1.cantidad,  t1.created_at, t2.id_procedimiento,t1.observacion, d.nombre diagnostico, d.codigo || ' - ' || d.nombre as relacion_diagnostico, d.codigo as cod_diag, case when ppp.pendiente_laboratorio is null then '' when ppp.pendiente_laboratorio is true then 'SI' when ppp.pendiente_laboratorio is false then 'NO' end pendiente_laboratorio
from prestacion_procedimientos t1
inner join labnivelatenciones t2 on t1.id_rua=t2.id
inner join procedimientos p on t1.id_procedimiento=p.id
LEFT JOIN prestacion_procedimientos_proveedor ppp on t1.id = ppp.id_prestacion_procedimientos
left join diagnosticos d on d.id = t1.id_diagnostico
where t1.id_prestacion=" . $id . "
and t1.id_lab_rad=" . $id_laboratorio. " 
and t1.id_evaluacion=" . $id_evaluacion. " 
and t1.id_estado_reg=1 order by t1.id desc";
    
    $data = DB::select($cad);
    return $data;
  }
/*
  public function GetProcedimientosByIdPrestacionAndIdLaboratorio($id_prestacion, $id_laboratorio, $tipo)
  {
    $cad = "select codigo,descripcion nombre, cantidad, observacion
		from prestacion_procedimientos t1
		inner join procedimientos t2 on t1.id_procedimiento=t2.id
		where t1.id_prestacion={$id_prestacion} 
		and t1.id_lab_rad={$id_laboratorio} 
		and t2.tipo_procedimiento={$tipo}
		and t1.id_estado_reg=1";
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionAndIdLaboratorioEva($id_prestacion, $id_laboratorio, $tipo, $id_evaluacion)
  {
    $cad = "select codigo,descripcion nombre, cantidad, observacion
    from prestacion_procedimientos t1
    inner join procedimientos t2 on t1.id_procedimiento=t2.id
    where t1.id_prestacion={$id_prestacion} 
    and t1.id_lab_rad={$id_laboratorio} 
    and t2.tipo_procedimiento={$tipo}
    and t1.id_evaluacion={$id_evaluacion}
    and t1.id_estado_reg=1";

    $data = DB::select($cad);
    return $data;
  } */
  public function GetProcedimientosByIdPrestacionAndIdLaboratorio($id_prestacion, $id_laboratorio, $tipo)
  {
    $cad = "select t2.codigo,t2.descripcion nombre, radnivelatenciones.descripcion_corta as nombre_corto, t1.cantidad, t1.observacion, t3.nombre diagnostico, t3.codigo cod_diag, t1.comparativo_radio,t1.lado_radio, t1.radiologia_portatil
		from prestacion_procedimientos t1
    inner join radnivelatenciones on radnivelatenciones.id = t1.id_rua
		inner join procedimientos t2 on radnivelatenciones.id_procedimiento=t2.id
    left join diagnosticos t3 on t1.id_diagnostico=t3.id
		where t1.id_prestacion={$id_prestacion} 
		and t1.id_lab_rad={$id_laboratorio}
		and t1.id_estado_reg=1
    order by t1.id";
    $data = DB::select($cad);
    return $data;
  }

  // public function GetProcedimientosByIdPrestacionAndIdLaboratorio_hsp($id_prestacion, $id_laboratorio, $tipo)
  // {
  //   // $cad = "select t2.codigo,t2.descripcion nombre, radnivelatenciones.descripcion_corta as nombre_corto, t1.cantidad, t1.observacion, t3.nombre diagnostico, t3.codigo cod_diag, t1.comparativo_radio,t1.lado_radio, t1.radiologia_portatil
	// 	// from prestacion_procedimientos t1
  //   // inner join radnivelatenciones on radnivelatenciones.id_procedimiento = t1.id_procedimiento
	// 	// inner join procedimientos t2 on radnivelatenciones.id_procedimiento=t2.id
  //   // left join diagnosticos t3 on t1.id_diagnostico=t3.id
	// 	// where t1.id_prestacion={$id_prestacion} 
	// 	// and t1.id_lab_rad={$id_laboratorio}
	// 	// and t1.id_estado_reg=1
  //   // order by t1.id
  //   // limit 1";
  //   $cad = "select p.codigo,p.descripcion nombre, rad.codigo_rad,rad.descripcion_corta as nombre_corto, pp.cantidad, pp.observacion, t3.nombre diagnostico, t3.codigo cod_diag, pp.comparativo_radio,pp.lado_radio, pp.radiologia_portatil     from radiologias r 
  //           inner join prestacion_procedimientos pp  on r.id=pp.id_lab_rad
  //           left join radnivelatenciones rad on rad.id_procedimiento = pp.id_procedimiento
  //           left join procedimientos p on pp.id_prestacion =p.id
  //           left join diagnosticos t3 on pp.id_diagnostico=t3.id
  //           where  pp.id_lab_rad ={$id_laboratorio}
  //           and r.id_prestacion = {$id_prestacion}
  //           and pp.id_procedimiento = pp.id_rua 
  //           order by pp.id desc
  //           limit 1";

  //   $data = DB::select($cad);
  //   //dd($cad);
  //   return $data;
  // }

  public function GetProcedimientosByIdPrestacionAndIdLaboratorioEva($id_prestacion, $id_laboratorio, $tipo, $id_evaluacion)
  {
    $cad = "select t2.codigo,t2.descripcion nombre, t1.cantidad, t1.observacion, t3.nombre diagnostico , t1.observacion, t3.codigo cod_diag, t1.comparativo_radio,t1.lado_radio, t1.radiologia_portatil
    from prestacion_procedimientos t1
    inner join procedimientos t2 on t1.id_procedimiento=t2.id
    left join diagnosticos t3 on t1.id_diagnostico=t3.id
    where t1.id_prestacion={$id_prestacion} 
    and t1.id_lab_rad={$id_laboratorio} 
    and t1.id_evaluacion={$id_evaluacion}
    and t1.id_estado_reg=1
    order by t1.id asc";

    $data = DB::select($cad);
    return $data;
  }
  public function GetAtencionesByDni($p)
  {
    return $this->readFunctionPostgres('sp_obtener_atenciones_por_dni', $p);
  }

  public function GetAtencionesByDni_1($p)
  {
    return $this->readFunctionPostgres('sp_obtener_atenciones_por_dni_1', $p);
  }
  public function GetAtencionesByDni_2($p)
  {
    return $this->readFunctionPostgres('sp_obtener_atenciones_por_dni_2', $p);
  }

  public function GetAtencionesByDni_3($p)
  {
    return $this->readFunctionPostgres('sp_obtener_atenciones_por_dni_3', $p);
  }

  public function GetEstadoFirmaPrestacion($id)
  {
    $query_string = "select id, fecha_firma, ruta_prestacion, id_tipo_atencion,id_establecimiento, flag from prestaciones where id = '{$id}'";
    return DB::select($query_string);
  }

  public function GetEstadoFirmaPrestacionModificada($id)
  {
    $query_string = "select id, fecha_firma_update, ruta_firma_update from prestaciones where id = '{$id}'";
    return DB::select($query_string);
  }

  public function GetDocumentosPendintesFirmarPrestacionId($id)
  {
    $query_string = "with tbl as (
				select id, id id_prestacion,fecha_firma, flag, 'prestacion' tipo 
				from prestaciones p where id = {$id}
				union all
				select id, id_prestacion, to_char(fecha_firma,'YYYY-MM-DD HH24:MI:SS') fecha_firma, flag, 'recetas' tipo 
				from receta_vales rv where id_prestacion = {$id}
				union all
				select id, id_prestacion, fecha_firma, flag, 'laboratorio' tipo 
				from laboratorios l where id_prestacion = {$id}
				union all
				select id, id_prestacion, fecha_firma, flag, 'radiologias' tipo 
				from radiologias r where id_prestacion = {$id}
        		union all
        		select id, id_prestacion,fecha_firma_interconsulta as fecha_firma, flag, 'interconsulta' tipo 
				from interconsultas i where id_prestacion = {$id}
        		union all
        		select id, id_prestacion,fecha_rpta_firma_interconsulta as fecha_firma, flag_rpta, 'interconsulta2' tipo 
        		from interconsultas irpta where id_prestacion = {$id} and flag = 0
        		union all
				select id, id_prestacion, fecha_firma, flag, 'evaluacion' tipo 
				from evaluaciones e where id_prestacion = {$id}
				union all
				select id, id_prestacion, fecha_firma_referencia as fecha_firma, flag, 'referencia' tipo 
				from referencias r where id_prestacion = {$id}
        union all
        select id, id_prestacion, fecha_firma as fecha_firma, flag, 'imagenologia' tipo 
        from prestacion_procedimientos pp where id_prestacion = {$id}
        ) select * from tbl
	  where flag = 0";
    return DB::select($query_string);
  }

  public function GetAtencionesByDniEmergencia($dni_beneficiario, $id_emergencia)
  {
    $cad = "select 
t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t6.nombre servicio,t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
FROM prestaciones t1
inner join emergencias t8 on t1.id_asegurado = t8.id_asegurado 
inner join asegurados t2 on t1.id_asegurado = t2.id 
inner join establecimientos t3 on t1.id_establecimiento=t3.id
inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
inner join consultorios t6 on t4.id_consultorio=t6.id
inner join medicos t5 on t1.id_medico=t5.id
inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
WHERE t8.id_prioridad < 3 and t8.estado = 3 and t8.id='" . $id_emergencia . "' and t7.id = 2 and t2.nro_doc_ident='" . $dni_beneficiario . "'
And t1.id_estado_reg = 1
ORDER BY t1.created_at desc";
    $data = DB::select($cad);
    return $data;
  }

  public function AgregarIdExt($id_prestacion, $id_ext) {

    $datos = Prestaciones::where('id', $id_prestacion)->first(); 
    $datos->id_ext = $id_ext;
    return $datos->save();
    
    // return true;
  }

  public function GetProcedimientosByIdEvaluacion($id, $tipo, $id_evaluacion)
  {

    $cad = "select codigo,descripcion nombre, cantidad
from prestacion_procedimientos t1
inner join procedimientos t2 on t1.id_procedimiento=t2.id
where t1.id_prestacion=" . $id . " and t2.tipo_procedimiento=" . $tipo . " and t1.id_evaluacion=" . $id_evaluacion . "  
and t1.id_estado_reg=1";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionLabEvo($id, $id_evaluacion)
  {

    $cad = "select t2.id, t2.codigo_lab codigo, t2.descripcion_corta nombre, t1.cantidad
from prestacion_procedimientos t1
inner join labnivelatenciones t2 on t1.id_rua=t2.id
where t1.id_prestacion=" . $id . "  and t1.id_evaluacion=" . $id_evaluacion . " 
and t1.id_estado_reg=1";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionLabEvaluacion($id, $id_evaluacion)
  {

    $cad = "select t2.id, t2.codigo_lab codigo, t2.descripcion_corta nombre, t1.cantidad, t3.fecha_firma, t3.estado
from prestacion_procedimientos t1
inner join labnivelatenciones t2 on t1.id_rua=t2.id
inner join laboratorios t3 on t3.id_prestacion = t1.id_prestacion
where t3.id_prestacion=" . $id . "  and t3.id_evaluacion=" . $id_evaluacion . " 
and t1.id_estado_reg=1";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionRadEvaluacion($id, $tipo, $id_evaluacion)
  {

    $cad = "select codigo,descripcion nombre, cantidad, t3.fecha_firma, t3.estado, t3.id
from prestacion_procedimientos t1
inner join procedimientos t2 on t1.id_procedimiento=t2.id
inner join radiologias t3 on t3.id_prestacion = t1.id_prestacion
where t1.id_prestacion=" . $id . " and t2.tipo_procedimiento=" . $tipo . " and t1.id_evaluacion=" . $id_evaluacion . "  
and t1.id_estado_reg=1";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetPrestacionByIdPrestacionExt($id_ext)
  {
    $datos = Prestaciones::where('id_ext', '=', $id_ext)->first();
    return $datos;
  }

  public function GetAtencionesByDniHospitalizacion($dni_beneficiario, $id_hospitalizado)
  {
    $cad = "select 
t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t6.nombre servicio,t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
FROM prestaciones t1
inner join asegurados t2 on t1.id_asegurado = t2.id 
inner join hospitalizaciones t8 on t8.dni_beneficiario = t2.nro_doc_ident 
inner join establecimientos t3 on t1.id_establecimiento=t3.id
inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
inner join consultorios t6 on t4.id_consultorio=t6.id
inner join medicos t5 on t1.id_medico=t5.id
inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
WHERE t8.estado = 3 and t8.id='" . $id_hospitalizado . "' and t7.id = 3 and t2.nro_doc_ident='" . $dni_beneficiario . "'
And t1.id_estado_reg = 1
ORDER BY t1.created_at desc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetCountAtenciones($dni_beneficiario, $id_establecimiento, $tipo_atencion, $consultorio= 0)
  {
    $cad = "select 
            count(*) atencion
            FROM prestaciones t1
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join establecimientos t3 on t1.id_establecimiento=t3.id
            inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
            inner join consultorios t5 on t4.id_consultorio=t5.id
            WHERE t1.id_establecimiento=" . $id_establecimiento . " and t1.id_tipo_atencion = ".$tipo_atencion." and t2.nro_doc_ident='" . $dni_beneficiario ."'" ;
  
    if($consultorio != 0):
      $cad .= " and t5.id=" . $consultorio ;
    endif;
    $cad .= " and t1.id_estado_reg = 1";
    $data = DB::select($cad);
    return $data[0];
  }

  /*
  Funcion para las condiciones de Continuador y Reingreso
  */
  public function GetCountAtencionesPorAnioActual($dni_beneficiario, $id_establecimiento, $tipo_atencion, $consultorio= 0)
  {
    $cad = "SELECT
            count(t1.id) atenciones
          FROM prestaciones t1
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join establecimientos t3 on t1.id_establecimiento=t3.id
            inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
            inner join consultorios t5 on t4.id_consultorio=t5.id
          WHERE t1.id_establecimiento=" . $id_establecimiento . " and t1.id_tipo_atencion = ".$tipo_atencion." and t2.nro_doc_ident='" . $dni_beneficiario ."'" ;
  
    if($consultorio != 0):
      $cad .= " and t5.id=" . $consultorio ;
    endif;
    $cad .= " and t1.id_estado_reg = 1 AND EXTRACT(YEAR FROM t1.fecha_atencion) = EXTRACT(YEAR FROM now());";
    $data = DB::select($cad);
    return $data[0];
  }

  /*
  Funcion para la condición de Nuevo
  */
  public function GetCountAtencionesActual($dni_beneficiario, $id_establecimiento, $tipo_atencion, $consultorio= 0)
  {
    $cad = "SELECT 
            count(t1.id) atenciones
          FROM prestaciones t1
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join establecimientos t3 on t1.id_establecimiento=t3.id
            inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
            inner join consultorios t5 on t4.id_consultorio=t5.id
          WHERE t1.id_establecimiento=" . $id_establecimiento . " and t1.id_tipo_atencion = ".$tipo_atencion." and t2.nro_doc_ident='" . $dni_beneficiario ."'" ;
  
    if($consultorio != 0):
      $cad .= " and t5.id=" . $consultorio ;
    endif;
    $cad .= " and t1.id_estado_reg = 1;";
    $data = DB::select($cad);
    return $data[0];
  }

  /*
  Obtener procedimientos segun el IdPrestacion, IdRadiologia, TipoProcedimiento y IdServicioRadiologia
  */
  //public function GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id_radiologia, $id_servicio_radiologia, $id_pp,$id_cita_multiple_rad)
    public function GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id_radiologia, $id_servicio_radiologia, $id_pp)
  {
    $cad = "select 
    t1.id,
    t1.validated_at,
    rad.codigo_rad codigo,
    t2.descripcion nombre_completo, 
    rad.descripcion_corta as nombre, 
    t1.cantidad, 
    t1.informe_parrafo, 
    t1.flag_informe, 
    t1.total_informe, 
    t1.lado_radio, 
    t1.observacion, 
    t1.comparativo_radio, 
    t1.tipo_plantilla, 
    t3.codigo diagnostico, 
    t3.nombre nombre_diagnostico, 
    t1.observacion_registro_informe, 
    t1.confirmar_toma, 
    ud1.paterno || ' ' || ud1.materno || ', ' || ud1.name as created_nombre_digitador_radiologia, 
    ud1.dni as created_nrodoc_digitador_radiologia,
    m1.colegiatura as colegiatura_digitador, 
    ud2.paterno || ' ' || ud2.materno || ', ' || ud2.name as updated_nombre_digitador_radiologia, 
    ud2.dni as updated_nrodoc_digitador_radiologia, 
    uv1.paterno || ' ' || uv1.materno || ', ' || uv1.name as created_nombre_registrador_informe, 
    uv1.dni as created_nrodoc_registrador_informe, 
    uv2.paterno || ' ' || uv2.materno || ', ' || uv2.name as updated_nombre_registrador_informe,
    uv2.dni as updated_nrodoc_registrador_informe,
    uval.paterno || ' ' || uval.materno || ', ' || uval.name as nombre_validador,
    uval.dni as dni_medico_validacion,
    t1.flag,
    t1.ruta_prestacion_procedimiento,
    t1.radiologia_portatil
		from prestacion_procedimientos t1
    inner join radnivelatenciones rad on rad.id = t1.id_rua
    inner join categoria_radiologias on categoria_radiologias.id = rad.id_categoria
		inner join procedimientos t2 on t1.id_procedimiento=t2.id
    left join diagnosticos t3 on t1.id_diagnostico = t3.id
    left join users ud1 on ud1.id = t1.confirmar_toma_created_us
    left join medicos m1 on ud1.dni= m1.dni and m1.estado = 1
    left join users ud2 on ud2.id = t1.confirmar_toma_updated_us 
    left join users uv1 on uv1.id = t1.created_us_registro_informe
    left join users uv2 on uv2.id = t1.updated_us_registro_informe
    left join users uval on uval.id = t1.validated_us";
    $cad .= " where t1.id_lab_rad=" . $id_radiologia . " and t1.tipo_procedimiento=3 "; 
    $cad .= " and t1.id = " . $id_pp;
    $cad .= " AND categoria_radiologias.id_servicio=".$id_servicio_radiologia." and t1.id_estado_reg=1 order by t1.id desc";
    // if(!empty($id_cita_multiple_rad)){
    //       $cad .= " and t1.id_citas_multiples_rad = '".$id_cita_multiple_rad."'";
    //   }
    //   //$cad .= " and categoria_radiologias.id_servicio=".$id_servicio_radiologia.
    //   $cad .= "and t1.id_estado_reg=1 order by t1.id desc";
    //dd($cad);
    $data = DB::select($cad);
    return $data;
  }
  public function GetProcedimientosRadiologicosByIdPrestacion($id_prestacion)
  {
    $cad = "select codigo,descripcion nombre, cantidad, c.nombre servicio
            from prestacion_procedimientos t1
            inner join procedimientos t2 on t1.id_procedimiento=t2.id
            LEFT JOIN consultorios c ON t2.id_servicio_radiologia = c.id 
            where t1.id_prestacion={$id_prestacion}
            and t2.tipo_procedimiento=3
            and t1.id_estado_reg=1";

    $data = DB::select($cad);
    return $data;
  }

  public function GetResumenPrecioPorAtencion($id_prestacion)
  {
    $cad = "WITH cte AS (
              --procedimientos
              SELECT '1' orden, t1.id, 'PROCEDIMIENTO' tipo, t2.codigo, upper(t2.descripcion) nombre, t1.cantidad, t2.t_nivel3 
              from prestacion_procedimientos t1
              inner join procedimientos t2 on t1.id_procedimiento=t2.id
              where t1.id_prestacion={$id_prestacion} and t2.tipo_procedimiento=1
              and t1.id_estado_reg=1
              UNION 
              --laboratorio
              select '2' orden, t1.id, 'EXAMEN LABORATORIO' tipo, t2.codigo_lab codigo, upper(t2.descripcion_corta) nombre, t1.cantidad, t3.t_nivel3 
              from prestacion_procedimientos t1
              inner join labnivelatenciones t2 on t1.id_rua=t2.id
              INNER JOIN procedimientos t3 ON t2.id_procedimiento = t3.id
              where t1.id_prestacion={$id_prestacion}
              and t1.id_estado_reg=1
              UNION 
              --radiologia
              select '3' orden, t1.id, 'EXAMEN RADIOLOGICO' tipo, t2.codigo, upper(t2.descripcion) nombre, t1.cantidad, t2.t_nivel3 
              from prestacion_procedimientos t1
              inner join procedimientos t2 on t1.id_procedimiento=t2.id
              where t1.id_prestacion={$id_prestacion}
              and t2.tipo_procedimiento=3
              and t1.id_estado_reg=1
              
            )
            SELECT * FROM cte c
            UNION 
            SELECT '5',0,'TOTAL PRECIO','TOTAL PRECIO','TOTAL PRECIO',0, sum(c2.t_nivel3)
            FROM cte c2
            ORDER BY orden, id;";

    $data = DB::select($cad);
    return $data;
  }

  public function GetAtencionesByIdAsegurado($id_asegurado)
  {
    $cad = "select 
            t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
            t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t6.nombre servicio,t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
            FROM prestaciones t1
            inner join asegurados t2 on t1.id_asegurado = t2.id 
            inner join establecimientos t3 on t1.id_establecimiento=t3.id
            inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
            inner join consultorios t6 on t4.id_consultorio=t6.id
            inner join medicos t5 on t1.id_medico=t5.id
            inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
            WHERE t2.id = ".$id_asegurado."
            And t1.id_estado_reg = 1
            ORDER BY t1.created_at desc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetPrestacionByIdCita($id_cita) {
    $datos = DB::table('prestaciones')
				        ->where('prestaciones.id_cita','=',$id_cita)
                ->select('prestaciones.*')
                ->first();
    return $datos;
  }
/*  public function GetPrestacionByIdCita($id_cita)
  {
    $datos = Prestaciones::where('id_cita', '=', $id_cita)->first();
    return $datos;
  }*/

  //ITEM SERVICIO
  public function getItemServicio($id_asegurado)
  {
    $datos = "select * 
              from prestaciones p 
              where p.id_asegurado = ".$id_asegurado." 
              and p.id_estado_reg = 1 order by id desc limit 1";
    $data = DB::select($datos);
    return $data;
  }

  //Reporte Estados Por Servicio y rango de fecha
  public function getReporteByRangoDeFechaByConsultorio($fecha_inicio, $fecha_fin, $idconsultorio = 0, $id_farmacia, $estado = 1){
    $query = "select  c.id,
                      c2.nombre ,	
                      to_char(c.fecha_hora, 'DD-MM-YYYY') as fecha_cita,
                      to_char(c.fecha_hora, 'HH24:MI') as hora_cita,
                      to_char(c.created_at, 'DD-MM-YYYY') as fecha_registro,
                      a.tipo_doc_ident,
                      a.nro_doc_ident as nro_doc_paciente, 
                      a.nombre ||' '|| a.paterno ||' '|| a.materno as nombre_paciente,
                      u.dni as nro_doc_respo_cita,
                      u.name ||' '|| u.paterno ||' '|| u.materno as nombre_responsable_cita,
                      m.dni as nro_doc_medico,
                      m.nombre ||' '|| m.paterno ||' '|| m.materno as nombre_medico, 
                      --c.updated_us as nombre_modificacion,
                      u2.name ||' '|| u2.paterno ||' '|| u2.materno as nombre_modificacion,
                      e.descripcion
              from citas c 
              left join prestaciones p on c.id = p.id_cita 
              inner join estados e on e.id = c.id_estado_cita 
              inner join sub_consultorios sc on sc.id = c.id_sub_consultorio
              inner join consultorios c2 on sc.id_consultorio = c2.id 
              inner join consultorio_farmacias cf on c2.id = cf.id_consultorio
              inner join asegurados a on c.id_asegurado = a.id
              inner join medicos m on c.id_medico = m.id
              inner join users u on u.id = c.created_us
              left join users u2 on u2.id  = c.updated_us
              where c.fecha_hora between '".$fecha_inicio."' and '".$fecha_fin."'
              and c.id_estado_cita  = ".$estado."";
              if ($idconsultorio>0) {
                $query .= "and cf.id_farmacia = ".$id_farmacia."
                and c2.id = ".$idconsultorio."
                group by c.id, 
                    c2.nombre,  
                    c.fecha_hora, 
                    a.tipo_doc_ident, 
                    a.nro_doc_ident, 
                    a.nombre, 
                    a.paterno, 
                    a.materno, 
                    u.dni, 
                    u.name, 
                    u.paterno, 
                    u.materno, 
                    m.dni, 
                    m.nombre, 
                    m.paterno, 
                    m.materno,
                    u2.name,
                    u2.paterno,
                    u2.materno,
                    e.descripcion
                order by c.id  desc"; 
              }else{
                $query.= "and cf.id_farmacia = ".$id_farmacia."
                group by c.id, 
                    c2.nombre,  
                    c.fecha_hora, 
                    a.tipo_doc_ident, 
                    a.nro_doc_ident, 
                    a.nombre, 
                    a.paterno, 
                    a.materno, 
                    u.dni, 
                    u.name, 
                    u.paterno, 
                    u.materno, 
                    m.dni, 
                    m.nombre, 
                    m.paterno, 
                    m.materno,
                    u2.name,
                    u2.paterno,
                    u2.materno,
                    e.descripcion
                order by c.id  desc";
              }

    $data= DB::select($query);
    return $data;
  }

  public function getServicioPorIdPrestacion($id_prestacion)
  {
    $datos = "select c.id as id_servicio_origen, c.nombre as nombre_servicio_origen, 
    case when p.flag_ref_cref = 1 then 'SI'
    else 'NO' end referido_origen,
     a.nro_doc_ident, a.id_tipo_doc_ident , e.id as id_iprees_origen, e.nombre as nombre_iprees_origen 
              from prestaciones p 
              inner join sub_consultorios sc on sc.id = p.id_sub_consultorio 
              inner join consultorios c on c.id = sc.id_consultorio 
              inner join citas c2 on c2.id = p.id_cita 
              inner join asegurados a on a.id = p.id_asegurado 
              inner join establecimientos e on e.id = p.id_establecimiento 
              where p.id = ".$id_prestacion." 
              and p.id_estado_reg = 1 order by p.id desc limit 1";
    $data = DB::select($datos);
    return $data;
  }

  public function getDataPorIdPrestacion($id_prestacion)
  {
    $datos = "select c.id as id_servicio_origen, c.nombre as nombre_servicio_origen, 
     a.nro_doc_ident, a.id_tipo_doc_ident , e.id as id_iprees_origen, e.nombre as nombre_iprees_origen 
              from prestaciones p 
              inner join sub_consultorios sc on sc.id = p.id_sub_consultorio 
              inner join consultorios c on c.id = sc.id_consultorio 
              inner join citas c2 on c2.id = p.id_cita 
              inner join asegurados a on a.id = p.id_asegurado 
              inner join establecimientos e on e.id = p.id_establecimiento 
              where p.id = ".$id_prestacion;
    $data = DB::select($datos);
    return $data;
  }

  public function GetPrestacionLaboratoriocabecera($id_prestacion, $id_laboratorio)
  {

    $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo codigo_lab, pp.id_rua, pp.confirmar_toma, l.cpms_personalizado
            from labnivelatenciones l
            inner join categoria_ruas cr on cr.id = l.id_categoria 
            --inner join categoria_rua_nivels crn on crn.id_categoria_rua  = cr.id 
            inner join procedimientos p on l.id_procedimiento = p.id 
            left join prestacion_procedimientos pp on pp.id_rua = l.id  
            and pp.id_prestacion =" . $id_prestacion . " and pp.id_lab_rad  =". $id_laboratorio. "
            where   l.id_tipo_atencion =2 
            group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo, pp.id_rua, pp.confirmar_toma
            ORDER BY cr.nombre asc";
            //Se comenta temporalmente para evitar el error al listar en el pdf laboratorio
            // --CASE 
            // --WHEN cr.nombre = 'SERVICIO DE BIOQUIMICA' THEN 1
            // --WHEN cr.nombre = 'SERVICIO DE INMUNOSEROLOGIA' THEN 2
            // --WHEN cr.nombre = 'SERVICIO DE HEMATOLOGIA' THEN 3
            // --WHEN cr.nombre = 'SERVICIO DE BANCO DE SANGRE' THEN 4
            //         WHEN cr.nombre = 'SERVICIO DE MICROBIOLOGÍA' THEN 5
            // --WHEN cr.nombre = 'MARCADORES HORMONALES' THEN 6
            // --WHEN cr.nombre = 'MARCADORES CARDIACOS' THEN 7
            // --WHEN cr.nombre = 'MARCADORES TUMORALES' THEN 8
            // --ELSE 9
            // --END, l.descripcion_corta, 
            // cr.nombre asc";

    $data = DB::select($cad);
    return $data;
  }

  public function GetPrestacionLaboratorio1($id,$id_laboratorio)
  {

    $cad = "select t2.id, t2.codigo_lab , t2.descripcion_corta nombre, t1.cantidad,  t1.created_at, t2.id_procedimiento
            from prestacion_procedimientos t1
            inner join labnivelatenciones t2 on t1.id_rua=t2.id
            where t1.id_prestacion=" . $id . "
            and t1.id_lab_rad=" . $id_laboratorio. " 
            and t1.id_estado_reg=1";

    $result = DB::select($cad);

    $data = []; // Initialize an empty array to collect the values

    foreach ($result as $row) {
        $data[] = $row->codigo_lab; // Add each value to the array
    }

    return $data;
    }
  
  public function ubicacion_paciente($id_asegurado)
  {

    $cad = "select t.id ,t.descripcion 
    from emergencias e 
    inner join triajes t on e.ubica_topico =t.id where e.id_asegurado =". $id_asegurado ."";

    $data = DB::select($cad);
    return $data;
  }

  public function getNumCategoriaTotal($nivel="", $tipo_atencion="", $id_servicio=""){
    $cad = "select count(*) total
          from categoria_rua_nivels a
          where a.estado= 1  and a.id_nivel = 3 ";
    $data = DB::select($cad);
    return $data[0];
  }

  public function GetPrestacionEmergenciaByIdAnestesiologia($p)
  {
    $data = $this->readFunctionPostgres2('prestacion_emergencia_anestesiologia', $p);
    return $data[0];
  }
  public function GetPrestacionEmergenciaById($p)
  {

    $data = $this->readFunctionPostgres2('sp_examen_laboratorio_emergencia', $p);
    return $data[0];
  }

  public function obtenerPrestacionProcedimientoPorIdPrestacion($id_prestacion)
  {   
      $data = DB::table('prestacion_procedimientos')
          ->select('prestacion_procedimientos.*')
          ->where('id_prestacion', '=', $id_prestacion)
          ->where('id_rua', '<>', 0)
          ->where('id_evaluacion', '<>', 0) // Agregando la nueva condición
          ->orderBy('id', 'desc')
          ->get();

      return $data;
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
    $closeCursor = DB::select("CLOSE ref_cursor;");

    return $data;
  }

  public function readFunctionPostgres2($function, $parameters = null)
  {
    $_parameters = '';
    if (count($parameters) > 0) {
        $_parameters = implode("','", $parameters);
        $_parameters = "'" . $_parameters . "',";
    }
    $data = DB::select("BEGIN;");
    $cad = "select " . $function . "(" . $_parameters . "'ref_cursor1');";
    
    $data = DB::select($cad);
    $cad = "FETCH ALL IN ref_cursor1;";
    $data = DB::select($cad);
    $closeCursor = DB::select("CLOSE ref_cursor1;");

    return $data;
  }

  public function getByIdExtYIdTipoAtencion($id_ext, $id_tipo_atencion) {
    $data = DB::table('prestaciones')
          ->where('prestaciones.id_ext','=',$id_ext)
          ->where('prestaciones.id_tipo_atencion','=',$id_tipo_atencion)
          ->whereRaw('COALESCE(prestaciones.flag_intervencion_quirurgica, false) = false')
          ->select('prestaciones.*')
          ->orderBy('prestaciones.fecha_atencion', 'desc')
          ->first();
    return $data;
  }
  
    function gestacion() {
    return $this->hasOne('App\Models\Gestacion', 'id_prestacion');
}
  function puerperio() {
    return $this->hasOne('App\Models\Puerperio', 'id_prestacion');
}

public function getLastGestacionByDniPaciente($dni) {
$cad = "select p.id, a.nro_doc_ident ,p.id_gestacion ,p.id_asegurado ,p.fecha_atencion, g.fech_ult_regla ,g.fech_prob_parto 
from prestaciones p
inner join asegurados a on p.id_asegurado = a.id
inner join gestaciones g on p.id_gestacion = g.id
where 
a.nro_doc_ident = '".$dni."'  
and g.fech_prob_parto  >= date_trunc('month', CURRENT_DATE - INTERVAL '10 months')  
order by p.id desc limit 1;";
$data = DB::select($cad);
return $data;
}

  /**
   * Funcion para registrar primera atencion de Hospitalizacion, se adiciono 5 nuevos campos segun la normal tecnica NTS N° 139-MINSA/2018/DGAIN
   */
  public function adicionarDatosPrestacion2($id_prestacion, $temperatura, $sa02_porcentaje, $presion_arterial_x, $presion_arterial_y, $talla_cm, $tamano_cintura_cm, $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, $fi02, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo, $antecedente, $antecedente_familiar, $examen_clinico_regional, $indicacion_terapeutica, $examen_ayuda_diagnostica, $procedimiento_medico_quirurgico, $interconsulta)
  {
    $cad = "Select sp_adicionar_datos_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_prestacion, $temperatura, $sa02_porcentaje, $presion_arterial_x, $presion_arterial_y, $talla_cm, $tamano_cintura_cm, $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, $fi02, $motivo_visita, $tiempo_enfermedad, $examen_clinico, $examen_auxiliar, $tratamiento_auxiliar, $plan_trabajo, $antecedente, $antecedente_familiar, $examen_clinico_regional, $indicacion_terapeutica, $examen_ayuda_diagnostica, $procedimiento_medico_quirurgico, $interconsulta));

    return $data[0]->sp_adicionar_datos_prestacion;
  }

  /**
   * Lista procedimientos y su diagnostico asociado de una Evolucion, segun el tipo de procedimiento
   */
  public function GetProcedimientosByIdPrestacionYIdEvolucionYTipo($id_prestacion, $id_evolucion, $tipo)
  {
    /*$query = "select pp.id id_prestacion_procedimiento, pp.id_prestacion, pp.id_evaluacion, p.codigo codigo_procedimiento, p.descripcion descripcion_procedimiento, pp.cantidad, d.codigo codigo_diagnostico
    from prestacion_procedimientos pp 
    join procedimientos p on pp.id_procedimiento = p.id
    join procedimientos_diagnoticos pd on pp.id_prestacion = pd.id_prestacion and pp.id_evaluacion = pd.id_evaluacion 
    join diagnosticos d on pd.id_diagnosticos = d.id 
    where pp.id_prestacion = $id_prestacion and pp.id_evaluacion = $id_evolucion and pp.id_estado_reg = 1 and pd.estado = 1 and pp.tipo_procedimiento = $tipo and pd.id_interconsulta = 0
    order by pp.id";*/

    $query = "select pp.id id_prestacion_procedimiento, pp.id_prestacion, pp.id_evaluacion, p.codigo codigo_procedimiento, p.descripcion descripcion_procedimiento, pp.cantidad, d.codigo codigo_diagnostico, pp.id_diagnostico, pp.id_procedimiento
    from prestacion_procedimientos pp 
    join procedimientos p on pp.id_procedimiento = p.id
    join diagnosticos d on pp.id_diagnostico = d.id 
    where pp.id_prestacion = $id_prestacion and pp.id_evaluacion = $id_evolucion and pp.id_estado_reg = 1 and pp.tipo_procedimiento = $tipo 
    order by pp.id";
    
    $data = DB::select($query);
    return $data;
  }

  /**
   * Listar Procedimientos y su diagnostico asociado de una atencion, segun el tipo de procedimiento
   */
  public function GetProcedimientosConDiagnosticoByIdPrestacion($id, $tipo)
  {
    /*$cad = "select t1.id as cod, t2.codigo, t2.descripcion nombre, t1.cantidad, d.codigo cod_diagnostico
    from prestacion_procedimientos t1
    inner join procedimientos t2 on t1.id_procedimiento=t2.id
    inner join procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
    inner join diagnosticos d on t3.id_diagnosticos = d.id 
    where t1.id_prestacion = " . $id . " and t1.tipo_procedimiento = " . $tipo . " and t1.id_estado_reg = 1 and t1.id_estado_reg = 1 and t3.estado = 1
    order by t1.id"; */
    $cad = " select p.codigo, p.descripcion nombre, pp.cantidad, d.codigo cod_diagnostico
    from receta_diagnosticos rd 
    inner join prestacion_procedimientos pp on pp.id_prestacion = rd.id_prestacion
    inner join diagnosticos d on rd.id_diagnostico = d.id
    inner join procedimientos p on p.id = pp.id_procedimiento
    where rd.id_prestacion = " . $id . " and pp.tipo_procedimiento = " . $tipo . "
    group by pp.cantidad, pp.id_procedimiento, d.codigo, d.nombre, p.codigo, p.descripcion
    order by p.codigo";
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosConDiagnosticoByIdPrestacionHo($id, $tipo)
  {
    $cad = " select p.codigo, p.descripcion nombre, pp.cantidad, d.codigo cod_diagnostico
    from receta_diagnosticos rd 
    inner join prestacion_procedimientos pp on pp.id_prestacion = rd.id_prestacion
    inner join diagnosticos d on rd.id_diagnostico = d.id
    inner join procedimientos p on p.id = pp.id_procedimiento
    where rd.id_prestacion = " . $id . " and rd.id_evaluacion = 0  and pp.tipo_procedimiento = " . $tipo . "
    group by pp.cantidad, pp.id_procedimiento, d.codigo, d.nombre, p.codigo, p.descripcion
    order by p.codigo";
    echo $cad;
    $data = DB::select($cad);
    return $data;
  }

  public function GetTodosProcedimientosConDiagnosticoByIdAtencion($id, $id_tipo_atencion) 
  {
    $prestacion = DB::table('prestaciones')
        ->where('prestaciones.id_ext', $id)
        ->where('prestaciones.id_tipo_atencion',$id_tipo_atencion)
        ->where('prestaciones.id_estado_reg',1)
        ->first();
    $cad = "select p.codigo, p.descripcion, pp.cantidad
      from prestacion_procedimientos pp 
      join procedimientos p on pp.id_procedimiento = p.id
      where pp.id_prestacion = ".$prestacion->id ." and pp.id_estado_reg = 1 and pp.tipo_procedimiento = 1
      group by p.codigo, p.descripcion, pp.cantidad
      order by p.codigo";
    $data = DB::select($cad);
    return $data;
  }

  public function GetPrestacionDiagnosticoAuditoria($id_trama)
  {
    $cad = "SELECT DISTINCT td.*, d.id, d.nombre, d.codigo, rd.id_tipo_diagnostico, rd.id_diagnostico ,rd.id_clasificacion_diagnosticos
    FROM trama_diagnosticos td
    INNER JOIN trama_prestacion tp ON td.id_trama_prestacion = tp.id
    LEFT JOIN diagnosticos d ON d.codigo = td.id_cie10
    INNER JOIN receta_diagnosticos rd ON rd.id_prestacion = tp.id
      where tp.id=" . $id_trama;
    $data = DB::select($cad);
    //dd($data[0]);
    return $data;
  }

  public function GetPrestacionLaboratorio2($id,$id_laboratorio)
  {
    $data = DB::table('prestacion_procedimientos')
            ->join('labnivelatenciones', 'prestacion_procedimientos.id_rua', '=', 'labnivelatenciones.id')
            ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
            ->leftJoin('prestacion_procedimientos_proveedor as ppp', 'prestacion_procedimientos.id', '=', 'ppp.id_prestacion_procedimientos')
            ->where('prestacion_procedimientos.id_prestacion', $id)
            ->where('prestacion_procedimientos.id_lab_rad', $id_laboratorio)
            ->where('prestacion_procedimientos.id_estado_reg', 1)
            ->select(
              'labnivelatenciones.id as id',
              'labnivelatenciones.codigo_lab as codigo',
              'labnivelatenciones.descripcion_corta as nombre',
              'procedimientos.descripcion as nombre_completo',
              'prestacion_procedimientos.cantidad',
              'prestacion_procedimientos.created_at',
              'labnivelatenciones.id_procedimiento',
              'prestacion_procedimientos.observacion',
              'diagnosticos.codigo as codigo_diagnostico',
              'diagnosticos.nombre as diagnostico',
              'prestacion_procedimientos.tipo_muestra_microbiologia',
              'prestacion_procedimientos.id_solicitud_procedimiento',
              DB::raw("case when ppp.pendiente_laboratorio is null then '' when ppp.pendiente_laboratorio is true then 'SI' when ppp.pendiente_laboratorio is false then 'NO' end pendiente_laboratorio")
            )
            ->orderBy('prestacion_procedimientos.id', 'asc')
            ->get();
    
    return $data;
  }

  public function GetPrestacionLaboratorioEvaluacion2($id,$id_laboratorio,$id_evaluacion)
  {

    $data = DB::table('prestacion_procedimientos as t1')
      ->join('labnivelatenciones as t2', 't1.id_rua', '=', 't2.id')
      ->join('procedimientos as p', 't1.id_procedimiento', '=', 'p.id')
      ->leftJoin('diagnosticos as d', 'd.id', '=', 't1.id_diagnostico')
      ->leftJoin('prestacion_procedimientos_proveedor as ppp', 't1.id', '=', 'ppp.id_prestacion_procedimientos')
      ->where('t1.id_prestacion', $id)
      ->where('t1.id_lab_rad', $id_laboratorio)
      ->where('t1.id_evaluacion', $id_evaluacion)
      ->where('t1.id_estado_reg', 1)
      ->select(
        't2.id',
        't2.codigo_lab as codigo',
        't2.descripcion_corta as nombre',
        'p.descripcion as nombre_completo',
        't1.cantidad',
        't1.created_at',
        't2.id_procedimiento',
        't1.observacion',
        'd.codigo as codigo_diagnostico',
        'd.nombre as diagnostico',
        DB::raw("case when ppp.pendiente_laboratorio is null then '' when ppp.pendiente_laboratorio is true then 'SI' when ppp.pendiente_laboratorio is false then 'NO' end pendiente_laboratorio")
      )
      ->orderBy('t1.id', 'asc')
      ->get();
    
    return $data;
  }
  
  public function registrarPrestacionHospitalizacion($id_cita, $id_asegurado,
      $id_tipo_atencion, $id_establecimiento, $id_servicio,
      $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta,
      $id_estado_reg, $observacion, $id_receta_vale,
       $id_farmacia, $id_user, $nro_receta, $digitos_adicionales,
       $id_anestesiologo, $obs_registro, $id_receta_vale_bus,
      $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia,
      $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales,
      $p_estado,/*$tipodoc_beneficiario,$nrodocafiliado,$nombre_beneficiario,$paterno_beneficiario,$materno_beneficiario,$
      tipo_beneficiario,$nro_historia,$grado,$fecnacafiliado,$nomsexo,$cip_beneficiario,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,*/ $condicion, $cantidadReceta, $p_procedimiento,     
      $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita,$id_hospitalizacion,$fecha_inicio_cama,$hora_inicio_cama,$id_cama,$flag_alta_servicio,$observacion_alta_servicio,$id_servicio_cama,$id_upss_encargado,$id_servicio_encargado,$select_cambiar_cama,$regularizar_datos_encargado,$id_servicio_cama_actual)
  {
    //print_r(array($id_cita, $id_asegurado, $id_tipo_atencion,$id_establecimiento,$id_servicio,$fecha_atencion,$fecha_alta,$id_medico,$id_motivo_alta,$id_estado_reg,$observacion,$id_receta_vale,$id_farmacia,$id_user,$nro_receta,$digitos_adicionales,$id_anestesiologo,$obs_registro,$id_receta_vale_bus,$id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias,$fecha_expedicion,$id_servicio_derivar,$id_tipo_num,$p_procedimiento, $p_producto, $p_diagnostico));exit();
    //$cad = "Select sp_insert_prestacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $cad = "Select sp_insert_prestacion_hospitalizacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    /*echo "Select sp_insert_prestacion(".$id_cita.", ".$id_asegurado.", ".$id_tipo_atencion.",".$id_establecimiento.",".$id_servicio.",'".$fecha_atencion."','".$fecha_alta."',".$id_medico.",".$id_motivo_alta.",".$id_estado_reg.",'".$observacion."',".$id_receta_vale.",".$id_farmacia.",".$id_user.",'".$nro_receta."','".$digitos_adicionales."',".$id_anestesiologo.",'".$obs_registro."',".$id_receta_vale_bus.",".$id_admision.",'".$tipo_via."','".$sub_via."','".$final_via."','".$nro_guia."','".$interconsulta."',".$interconsulta_dias.",'".$fecha_expedicion."',".$id_servicio_derivar.",".$id_tipo_num.",'".$anamnesis."','".$examen_fisico."','".$p_estado."',".$condicion.",".$cantidadReceta.",'".$p_procedimiento."', '".$p_producto."', '".$p_diagnostico."')";exit();*/
    $data = DB::select($cad, array($id_cita, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_servicio, $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia, $id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $p_estado,/*$tipodoc_beneficiario,$nrodocafiliado,$nombre_beneficiario,$paterno_beneficiario,$materno_beneficiario,$tipo_beneficiario,$nro_historia,$grado,$fecnacafiliado,$nomsexo,$cip_beneficiario,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular, 33 */ $condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita,$id_hospitalizacion,$fecha_inicio_cama,$hora_inicio_cama,$id_cama,$flag_alta_servicio,$observacion_alta_servicio,$id_servicio_cama,$id_upss_encargado,$id_servicio_encargado,$select_cambiar_cama,$regularizar_datos_encargado,$id_servicio_cama_actual));

    return $data[0]->sp_insert_prestacion_hospitalizacion;
  }
  
  public function updatePrestacionDiagnostico($id_prestacion, $diagnosticos, $id_profesional, $motivo_modificacion) {
    $cad = "Select sp_update_prestacion_diagnostico(?,?,?,?)";
    $data = DB::select($cad, array($id_prestacion, $diagnosticos, $id_profesional, $motivo_modificacion));
    return $data[0]->sp_update_prestacion_diagnostico;
  }
  
  public function GetDiagnosticoByIdPrestacionModificada($id)
  {
    $cad = "select t1.id_diagnostico, t1.id_tipo_diag id_tipo_diagnostico, t2.codigo, t2.nombre
      from modificar_prestaciones mp
      inner join modificar_diagnosticos t1 on mp.id = t1.id_modificar_prestacion
      inner join diagnosticos t2 on t1.id_diagnostico = t2.id 
      inner join tipo_diagnosticos td on t1.id_tipo_diag = td.id 
      where mp.id_prestacion = " . $id . "
      order by t1.id asc";
    
    $data = DB::select($cad);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionYTipo($id, $tipo)
  {
    $query = "select pp.id_procedimiento, pp.id as cod, p.codigo, p.descripcion nombre, pp.cantidad, pp.id_diagnostico, d.codigo codigo_diagnostico
      from prestacion_procedimientos pp 
      join procedimientos p on pp.id_procedimiento = p.id
      join diagnosticos d on pp.id_diagnostico = d.id 
      where pp.id_prestacion = $id and pp.id_evaluacion = 0 and pp.id_estado_reg = 1 and pp.id_consejeria = 0 and pp.id_interconsulta = 0 and pp.tipo_procedimiento = $tipo 
      order by pp.id";
    $data = DB::select($query);
    return $data;
  }

  public function GetProcedimientosByIdPrestacionYTipoHoEvo($id, $tipo)
  {
    $query = "select p.codigo, p.descripcion nombre, pp.cantidad, pp.id_diagnostico, d.codigo codigo_diagnostico
      from prestacion_procedimientos pp 
      join procedimientos p on pp.id_procedimiento = p.id
      join diagnosticos d on pp.id_diagnostico = d.id 
      where pp.id_prestacion = $id and pp.id_evaluacion != 0 and pp.id_estado_reg = 1 and pp.tipo_procedimiento = $tipo 
      group by p.codigo, p.descripcion, pp.cantidad, pp.id_diagnostico, d.codigo
      order by p.codigo";
    $data = DB::select($query);
    return $data;
  }
  
  public function existePrestacionModificada($id_prestacion) {
    $data = DB::table('modificar_prestaciones')
      ->where('modificar_prestaciones.id_prestacion', $id_prestacion)
      ->where('flag', 1)
      ->select('modificar_prestaciones.id','modificar_prestaciones.created_at')
      ->orderBy('modificar_prestaciones.id')
      ->get();
    return $data;
  }
	
  public function GetPrestacionRadiologia($id_prestacion, $id_laboratorio, $tipo = 0,$id_evaluacion = 0)
  {
    $cad = "select t2.id, t2.codigo_rad codigo, t2.descripcion_corta as nombre, p.descripcion as nombre_completo, t1.cantidad, t1.observacion, t3.nombre diagnostico, t3.codigo cod_diag, t1.comparativo_radio,t1.lado_radio, t1.radiologia_portatil
		from prestacion_procedimientos t1
    inner join radnivelatenciones t2 on t1.id_rua=t2.id
		inner join procedimientos p on t1.id_procedimiento=p.id
    left join diagnosticos t3 on t1.id_diagnostico=t3.id
		where t1.id_prestacion={$id_prestacion} 
		and t1.id_lab_rad={$id_laboratorio}
    --and t1.tipo_procedimiento={$tipo}";
    if ($id_evaluacion>0) {
      $cad .= " and t1.id_evaluacion={$id_evaluacion} ";
    }
    $cad .= "and t1.id_estado_reg=1 order by t1.id ";

    $data = DB::select($cad);
    return $data;
  }
  // public function GetPrestacionRadiologia_hsp($id_prestacion, $id_laboratorio, $tipo = 0,$id_evaluacion = 0)
  // {
  //   $cad = "select t2.id, t2.codigo_rad codigo, t2.descripcion_corta as nombre, p.descripcion as nombre_completo, t1.cantidad, t1.observacion, t3.nombre diagnostico, t3.codigo cod_diag, t1.comparativo_radio,t1.lado_radio, t1.radiologia_portatil
	// 	from prestacion_procedimientos t1
  //   inner join radnivelatenciones t2 on t1.id_procedimiento=t2.id_procedimiento
	// 	inner join procedimientos p on t1.id_procedimiento=p.id
  //   left join diagnosticos t3 on t1.id_diagnostico=t3.id
	// 	where t1.id_prestacion={$id_prestacion} 
	// 	and t1.id_lab_rad={$id_laboratorio}
  //   --and t1.tipo_procedimiento={$tipo}";
  //   if ($id_evaluacion>0) {
  //     $cad .= " and t1.id_evaluacion={$id_evaluacion} ";
  //   }
  //   $cad .= "and t1.id_estado_reg=1 order by t1.id ";
  //   //dd($cad);
  //   $data = DB::select($cad);
  //   return $data;
  // }

  public function GetPrestacionEmergenciaRadiologiaById($p)
  {
    $data = $this->readFunctionPostgres('sp_examen_radiologia_emergencia', $p);
    //$data = $this->readFunctionPostgres('sp_examen_radiologia_emergencia_cq', $p);
	return $data[0];
  }

  public function getPresProceById($id)
  {
    $res = DB::table('prestacion_procedimientos')
          ->select(
            'prestacion_procedimientos.id',
            'radnivelatenciones.codigo_rad as codigo',
            'procedimientos.descripcion as nombre_completo',
            'radnivelatenciones.descripcion_corta as nombre',
            'prestacion_procedimientos.cantidad', 
            'prestacion_procedimientos.informe_parrafo', 
            'prestacion_procedimientos.flag_informe', 
            'prestacion_procedimientos.total_informe', 
            'prestacion_procedimientos.lado_radio', 
            'prestacion_procedimientos.observacion', 
            'prestacion_procedimientos.comparativo_radio', 
            'prestacion_procedimientos.tipo_plantilla', 
            'prestacion_procedimientos.id_lab_rad as id_radiologia', 
            'diagnosticos.codigo as diagnostico', 
            'diagnosticos.nombre as nombre_diagnostico', 
            'prestacion_procedimientos.observacion_registro_informe',
            'categoria_radiologias.id_servicio as id_servicio_categoria_radiologia'
          )
          ->join('radnivelatenciones','radnivelatenciones.id','=','prestacion_procedimientos.id_rua')
          ->join('categoria_radiologias','categoria_radiologias.id','=','radnivelatenciones.id_categoria')
          ->join('procedimientos','procedimientos.id','=','prestacion_procedimientos.id_procedimiento')
          ->leftJoin('diagnosticos','diagnosticos.id','=','prestacion_procedimientos.id_diagnostico')
          ->where('prestacion_procedimientos.id','=',$id)
          ->where('prestacion_procedimientos.id_estado_reg','=',1)
          ->first();
    return $res;
  }

  public function obtenerPresProcePorIdServicioRadiologia($id_radiologia, $tipo, $id_servicio_radiologia, $id_pp)
  {

    $res = DB::table('prestacion_procedimientos')
          ->select(
            'prestacion_procedimientos.id',
            'radnivelatenciones.codigo_rad as codigo',
            'procedimientos.descripcion as nombre_completo',
            'radnivelatenciones.descripcion_corta as nombre',
            'prestacion_procedimientos.cantidad', 
            'prestacion_procedimientos.informe_parrafo', 
            'prestacion_procedimientos.flag_informe', 
            'prestacion_procedimientos.total_informe', 
            'prestacion_procedimientos.lado_radio', 
            'prestacion_procedimientos.observacion', 
            'prestacion_procedimientos.comparativo_radio', 
            'prestacion_procedimientos.tipo_plantilla', 
            'prestacion_procedimientos.id_lab_rad as id_radiologia', 
            'diagnosticos.codigo as diagnostico', 
            'diagnosticos.nombre as nombre_diagnostico', 
            'prestacion_procedimientos.observacion_registro_informe',
            'categoria_radiologias.id_servicio as id_servicio_categoria_radiologia'
          )
          ->join('radnivelatenciones','radnivelatenciones.id','=','prestacion_procedimientos.id_rua')
          ->join('categoria_radiologias','categoria_radiologias.id','=','radnivelatenciones.id_categoria')
          ->join('procedimientos','procedimientos.id','=','prestacion_procedimientos.id_procedimiento')
          ->leftJoin('diagnosticos','diagnosticos.id','=','prestacion_procedimientos.id_diagnostico')
          ->where('prestacion_procedimientos.id_lab_rad','=',$id_radiologia)
          ->where('prestacion_procedimientos.tipo_procedimiento','=',$tipo)
          ->where('categoria_radiologias.id_servicio','=',$id_servicio_radiologia)
          ->where('prestacion_procedimientos.id_estado_reg','=',1)
          ->where('prestacion_procedimientos.id','=',$id_pp)
          ->whereIn('prestacion_procedimientos.confirmar_toma', ['0', '3'])
          ->orderBy('prestacion_procedimientos.id', 'desc')
          ->get();
    return $res;
  }

    public function getIdPrestacionSiExiste($id_ext, $id_tipo_atencion)
    {
      $res = Prestaciones::select('id')
                        ->where('id_tipo_atencion', $id_tipo_atencion)
                        ->where('id_ext', $id_ext)
                        ->get();
      return $res;                        
    }

    public function getOneIdPrestacionSiExiste($id_ext, $id_tipo_atencion)
    {
      $res = Prestaciones::select('id')
        ->where('id_tipo_atencion', $id_tipo_atencion)
        ->where('id_ext', $id_ext)
        ->first();
      return $res;                        
    }

    public function getIdPrestacionSiExistev2($id_prestacion)
    {
      $res = Prestaciones::select('id')
                        ->where('id', $id_prestacion)
                        ->get();
      return $res;                        
    }

    public function registrarPrestacion_cqx($id_cita, $id_asegurado,
        $id_tipo_atencion, $id_establecimiento, $id_servicio,
        $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta,
        $id_estado_reg, $observacion, $id_receta_vale,
        $id_farmacia, $id_user, $nro_receta, $digitos_adicionales,
        $id_anestesiologo, $obs_registro, $id_receta_vale_bus,
        $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia,
        $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar,
        $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales,
        $p_estado, $condicion, $cantidadReceta, $p_procedimiento,
        $p_producto, $p_producto_postqx,$id_farmacia_receta_postQ,$fecha_expedicion_postQ,                  // NUEVO
        $p_diagnostico, $ipress_bene, $upss_bene,
        $receta_prescrita, $receta_prescrita_postqx,      // NUEVO
        $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss,
        $adicional, $registrar_cita, $id_prestacion, $flag_ref_cref, $id_hospitalizacion)
    {
        $params = array(
            $id_cita, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_servicio,
            $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta, $id_estado_reg,
            $observacion, $id_receta_vale, $id_farmacia, $id_user, $nro_receta,
            $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus,
            $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta,
            $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num,
            $anamnesis, $examen_fisico, $aspectos_generales, $p_estado, $condicion,
            $cantidadReceta, $p_procedimiento, $p_producto, $p_producto_postqx,$id_farmacia_receta_postQ,$fecha_expedicion_postQ,
            $p_diagnostico, $ipress_bene, $upss_bene, $receta_prescrita,
            $receta_prescrita_postqx, $id_modalidad, $sexo, $fecha_nacimiento,
            $codigo_upss, $adicional, $registrar_cita, $id_prestacion,
            $flag_ref_cref, $id_hospitalizacion
        );

        // Genera los ? automáticamente (52) para evitar errores de conteo
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $data = DB::select("SELECT sp_insert_prestacion_cqx($placeholders)", $params);

        return $data[0]->sp_insert_prestacion_cqx;
    }

  public function registrarPrestacion_cqxPrincipal($id_intervencion_quirurgica,$id_cita,$id_asegurado, $id_tipo_atencion, $id_establecimiento,$fecha_atencion,  $id_medico, $id_estado_reg, $id_user, $ipress_bene,$upss_bene, $id_modalidad,  $codigo_upss,$p_adicional,$p_registrar_cita,$p_flag_ref_cref)
  {
    $cad = "Select sp_registrar_prestacion_cqx_principal(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; //49 parametros
    $data = DB::select($cad, array($id_intervencion_quirurgica,$id_cita,$id_asegurado, $id_tipo_atencion, $id_establecimiento, $fecha_atencion,  $id_medico, $id_estado_reg, $id_user, $ipress_bene,$upss_bene, $id_modalidad,$codigo_upss,$p_adicional,$p_registrar_cita,$p_flag_ref_cref ));
    return $data[0]->sp_registrar_prestacion_cqx_principal;
  }


  function searchByCodigoUpssAseguradoFechaAtencion($codigo_upss, $nro_doc_ident, $id_tipo_doc_ident, $fecha_atencion) {
    $prestaciones = DB::table('prestaciones as p')
      ->join('asegurados as a', 'p.id_asegurado', '=', 'a.id')
      ->where('p.codigo_upss', $codigo_upss)
      ->where('a.nro_doc_ident', $nro_doc_ident)
      ->where('a.id_tipo_doc_ident', $id_tipo_doc_ident)
      ->where('p.flag_intervencion_quirurgica',false)
      ->whereRaw("DATE(p.fecha_atencion) = '$fecha_atencion'")
      ->get();

    return $prestaciones;
  }

  function obtenerTerapeuticasByAseguradoId($aseguradoId) {
      // $sql = "select
      //           p.id as prestacion_id,
      //           p.fecha_atencion::date as prestacion_fecha_atencion,
      //           p.fecha_atencion::time as prestacion_hora_atencion,
      //           m.id as medico_id,
      //           m.dni as medico_dni,
      //           m.nombre as medico_nombre,
      //           p.indicacion_terapeutica 
      //           from prestaciones p 
      //           left join medicos m on p.id_medico = m.id 
      //           where p.id_asegurado = :id_asegurado;";

      $sql = "select
                p.id as prestacion_id,
                p.fecha_atencion::date as prestacion_fecha_atencion,
                p.fecha_atencion::time as prestacion_hora_atencion,
                m.id as medico_id,
                m.dni as medico_dni,
                m.nombre as medico_nombre,
                p.indicacion_terapeutica
            from prestaciones p 
            left join medicos m on p.id_medico = m.id 
            where p.id_asegurado = :id_asegurado and p.fecha_atencion is not null
            UNION ALL
              select
                p.id as prestacion_id,
                p.fecha_atencion::date as prestacion_fecha_atencion,
                p.fecha_atencion::time as prestacion_hora_atencion,
                m.id as medico_id,
                m.dni as medico_dni,
                m.nombre as medico_nombre,
                p.tratamiento_auxiliar as indicacion_terapeutica 
            from evaluaciones p 
            left join medicos m on p.id_medico = m.id 
            where p.id_asegurado = :id_asegurado and p.fecha_atencion is not null
            order by prestacion_fecha_atencion desc";
      $result = DB::select($sql, ['id_asegurado' => $aseguradoId]);

      return $result;
    }

  /*function obtenerTerapeuticasByAseguradoId($aseguradoId) {
      $sql = "select
                p.id as prestacion_id,
                p.fecha_atencion::date as prestacion_fecha_atencion,
                p.fecha_atencion::time as prestacion_hora_atencion,
                m.id as medico_id,
                m.dni as medico_dni,
                m.nombre as medico_nombre,
                p.indicacion_terapeutica 
                from prestaciones p 
                left join medicos m on p.id_medico = m.id 
                where p.id_asegurado = :id_asegurado
              UNION ALL
              select
                p.id as prestacion_id,
                p.fecha_atencion::date as prestacion_fecha_atencion,
                p.fecha_atencion::time as prestacion_hora_atencion,
                m.id as medico_id,
                m.dni as medico_dni,
                m.nombre as medico_nombre,
                p.tratamiento_auxiliar as indicacion_terapeutica 
                from evaluaciones p 
                left join medicos m on p.id_medico = m.id 
                where p.id_asegurado = :id_asegurado
                order by prestacion_fecha_atencion desc
                ";
      $result = DB::select($sql, ['id_asegurado' => $aseguradoId]);

      return $result;
    }*/

    public function registrarRecetaValeAtencionDetalle_cqx($data){
      $cad = "Select sp_insert_recetavale_atencion_detalle_cqx(?,?,?,?,?,?,?,?,?,?,
                                                               ?,?,?,?,?,?,?,?,?,?,
                                                               ?,?,?,?,?,?,?,?,?,?,
                                                               ?,?,?,?,?,?,?,?,?,?,?) as p_id_receta_vale"; //41 parametros
      $data = DB::select($cad,
        array(
            $data['p_id_receta_vale'], 
            $data['p_id_farmacia'], 
            $data['p_id_user'],
            $data['p_id_consultorio'],
            $data['p_nro_receta'],
            $data['p_digitos_adicionales'],
            $data['p_dni_beneficiario'],
            $data['p_cip_beneficiario'], 
            $data['p_tipdoc_beneficiario'], 
            $data['p_nombre_beneficiario'], 
            $data['p_paterno_beneficiario'], 
            $data['p_materno_beneficiario'],
            $data['p_tipo_beneficiario'], 
            $data['p_dni_titular'], 
            $data['p_tipdoc_titular'], 
            $data['p_nombre_titular'],
            $data['p_paterno_titular'], 
            $data['p_materno_titular'], 
            $data['p_fecha_expedicion'], 
            $data['p_fecha_atencion'],
            $data['p_nro_historia'],
            $data['p_grado'], 
            $data['p_id_medico'],
            $data['p_tipo_receta'], 
            $data['p_turno'], 
            $data['p_obs_registro'],
            $data['p_id_receta_vale_bus'],
            $data['p_id_admision'],
            $data['p_tipo_via'], 
            $data['p_sub_via'], 
            $data['p_final_via'], 
            $data['p_nro_guia'], 
            $data['p_interconsulta'], 
            $data['p_interconsulta_dias'],
            $data['p_id_prestacion'],
            $data['p_id_tipo_num'],
            $data['p_estado'],//estado de la receta
            $data['p_detalle'],  
            $data['p_sexo'],
            $data['p_fecha_nacimiento'],
            $data['p_codigo_upss']
        )
      );
      return $data[0]->p_id_receta_vale;
    }
  
  public function GetProcedimientosByIdPrestacionydiag_Test($id, $tipo, $id_evaluacion = 0)
  {
    $query = "SELECT distinct t1.id as cod, t2.codigo, t2.descripcion nombre, t1.cantidad, t3.id_diagnosticos as id_diagnostico, t1.id_procedimiento
    FROM prestacion_procedimientos t1
    INNER JOIN procedimientos t2 ON t1.id_procedimiento = t2.id
    INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
    WHERE t1.id_prestacion = " . $id . " AND t3.id_evaluacion = 0 AND t1.tipo_procedimiento = " . $tipo . "
    AND t1.id_estado_reg = 1 AND t3.estado = 1 AND t3.id_interconsulta = 0 AND t3.id_consejeria = 0 and t3.id_evolucion is null
    order by t1.id asc
    ";
      $data = DB::select($query);
      return $data;
  }

  /**
   * Copia de GetProcedimientosByIdPrestacionydiag que no considera id_consejeria y varios modulos lo usan
   * 15ABR2026 - Actualmente solo para Radiologia
   */
  public function GetProcedimientosByIdPrestacionydiag2($id, $tipo)
  {
    $query = "SELECT t1.id as cod, t2.codigo, t2.descripcion nombre, t1.cantidad, t3.id_diagnosticos as id_diagnostico, t1.id_procedimiento
    FROM prestacion_procedimientos t1
    INNER JOIN procedimientos t2 ON t1.id_procedimiento = t2.id
    INNER JOIN procedimientos_diagnoticos t3 ON t3.id_prestacion = t1.id_prestacion AND t3.id_procedimientos = t1.id_procedimiento
    WHERE t1.id_prestacion = " . $id . " AND t3.id_evaluacion = 0 AND t1.tipo_procedimiento = " . $tipo . "
    AND t1.id_estado_reg = 1 AND t3.estado = 1 AND t3.id_interconsulta = 0 and t1.id_consejeria = 0
    order by t1.id asc";

    $data = DB::select($query);
    return $data;
  }
  /**
   * Copia de GetPrestacionById
   * 
   * Cambio: no se considera que el paciente este activo su registro
   */
  public function GetPrestacionById_2($id)
  { 
    $cad = "
    select t1.created_at,t1.id,t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t11.nro_historia,t2.sexo,t1.condicion,t2.fecha_nac,tb.descripcion, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t12.parentesco,t2.grado,t1.id_ext,t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.motivo_visita, t1.tiempo_enfermedad, t1.t_enfermedad,t1.periodo, t1.examen_clinico, t1.examen_auxiliar, t1.tratamiento_auxiliar, t1.plan_trabajo,t1.antecedente_familiar, t1.antecedente,t2.id_asegurado_titular,t2.cip, t2.situacion_paciente,
    t2.nro_doc_ident,t1.ipress,  t1.upss, (CASE WHEN ups.codigo IS NOT NULL THEN ups.descripcion_upss else '-' END) as nombre_upss,t1.fecha_alta,t1.id_motivo_alta,t1.id_estado_reg,t1.flag_ref_cref,
    t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t4.id_consultorio,t15.nombre servicio,t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,tdi.descripcion,t5.colegiatura, t1.id_modalidad, t5.colegiatura colegiatura_medico,
    dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion,t9.id id_receta,to_char(t1.fecha_atencion,'dd-mm-YYYY')fecha_atencion,t10.nombre tipo_atencion
    ,anamnesis,examen_fisico,t1.aspectos_generales,t14.nombre servicio_derivar,t1.id_cita , t1.fecha_firma, t1.id_tipo_atencion, t1.id_ext, t1.codigo_upss, t1.registrar_cita_por_medico, t2.correo, t2.domicilio, t2.telefono, t2.telefono2, t2.cip as cip_asegurado, at.tipo_doc_ident as tipodoc_titular, at.nombre nombre_titular,at.paterno paterno_titular,at.materno materno_titular, at.nro_doc_ident nrodoctitular,at.cip cip_titular,at.grado grado_titular, t2.tipo_doc_ident,
    t12.referido, t12.establecimiento_referencia, t3.codigo establecimiento_codigo,  t2.id_tipo_doc_ident id_tipo_doc_ident , t12.numero_doc, t15.reporte_columnas_lab, t12.id_cita_lab, t3.nivel as nivel_estable, t3.codigo as codigo_renaes, t2.situacion_paciente, t1.flag as flag_atencion_medica, t1.ruta_prestacion as ruta_prestacion_atencion_medica, t1.examen_clinico_regional, t1.indicacion_terapeutica, t1.examen_ayuda_diagnostica, t1.procedimiento_medico_quirurgico, t1.interconsulta, t12.id_referencia, d.descripcion as departamento -- ,t16.nombre as servicio_referencia
    ,t1.indicacion_terapeutica, t1.cups_pnp as codigo_cups, t1.procedimiento_medico_quirurgico,t12.codigo_siteds ,t12.updated_at_cod_siteds ,t12.updated_us_cod_siteds , CASE WHEN t12.updated_at_cod_siteds::DATE != CURRENT_DATE THEN 0 ELSE 1 END AS is_valid_cod_siteds,
    lab.rua,g.id as id_gestacion, g.duda, g.fech_ult_regla ,g.fech_prob_parto ,g.semanas_gestacion ,g.num_controles, 
    t1.fecha_atencion as fecha_hora_atencion,
    t1.flag as flag_firma, 
    CASE WHEN DATE(t1.created_at) = CURRENT_DATE THEN '1' ELSE '0' END AS is_editable ,t15.flag_quirurgico
    FROM prestaciones t1
    inner join tipo_atenciones t10 on t1.id_tipo_atencion=t10.id 
    left join upsses ups on t1.codigo_upss=ups.codigo
    inner join asegurados t2 on t1.id_asegurado = t2.id
    inner join asegurado_historias t11 on t1.id_asegurado = t11.id_asegurado And t1.id_establecimiento = t11.id_establecimiento 
    left join tipo_beneficiarios tb on t2.id_tipo_beneficiario =tb.id 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t15 on t4.id_consultorio=t15.id
    inner join medicos t5 on t1.id_medico=t5.id
    left join departamentos d on t2.id_departamento =d.id_departamento 
    left join especializaciones t7 on t5.id_especializacion=t7.id
    left join profesiones t8 on t5.id_profesion=t8.id
    left join receta_vales t9 on t1.id=t9.id_prestacion And t9.estado<>0 
    left join citas t12 on t1.id_cita=t12.id
    left join citas t13 on t1.id_cita=t13.id_parent_cita
    left join consultorios t14 on t13.id_sub_consultorio=t14.id
    left join referencias on referencias.id_prestacion = t1.id
    left join asegurados at on at.id = t2.id_asegurado_titular
    LEFT JOIN tipo_documento_identidad tdi ON CAST(t5.tipo_documento AS INTEGER) = CAST(tdi.codigo AS INTEGER)
    --left join consultorios t16 on CAST(t12.servicio_referencia AS INTEGER) = t16.id
    --left join establecimientos t17 on referencias.id_establecimiento_des=t17.id
    left join gestaciones g on t1.id_gestacion = g.id 
    left join laboratorios lab on t1.id = lab.id_prestacion
    Where t1.id=" . $id;
    
    
    
    $data = DB::select($cad);
    if (empty($data)) {
        return null; 
    }
      if(count($data)!= 0 ){
        //dd($data[0]);
        if($data[0]->id_ext != null){        
          if($data[0]->id_tipo_atencion == 3){//hospitalizacion
            $idExt = $data[0]->id_ext;
            $cama_info = DB::select("select c.nombre nombre_cama,cuar.nombre nombre_cuarto, p.nombre pabellon
            from hospitalizaciones h
            left join camas c on h.id_cama_estado = c.id
            left join cuartos cuar on c.id_cuarto=cuar.id
            left join pabellones p on p.id=h.id_pabellon
            where h.id = $idExt;");
            //dd($data);
          }elseif($data[0]->id_tipo_atencion == 2){//emergencia
            $idExt = $data[0]->id_ext;
            $cama_info = DB::select("select ca.nombre nombre_cama 
            ,cuar.nombre nombre_cuarto,TO_CHAR(e.created_at, 'DD-MM-YYYY') fecha_evaluacion
            from emergencias e  
            left join camas ca on e.cama_estado =ca.id 
            left join cuartos cuar on ca.id_cuarto=cuar.id
            
            where e.id = $idExt;");
          }
          $data[0]->nmro_cama = "";
          $data[0]->ubicacion = "";
          $data[0]->fecha_evaluacion = "";
          if(count($cama_info)!= 0){
            $data[0]->nmro_cama = $cama_info[0]->nombre_cama;
            if($data[0]->id_tipo_atencion == 3){//hospitalizacion
              // dd("hola");
              $data[0]->ubicacion = $cama_info[0]->pabellon;
            }else{//emergencia
              $data[0]->fecha_evaluacion  = $cama_info[0]->fecha_evaluacion;
              $data[0]->ubicacion = $cama_info[0]->nombre_cuarto;
            }
            
          }
        }
      }
    
    //dd($data[0]);
    return $data[0];
  }

  public function GetPrestacionByIdDatos($id)
  { 
    $cad = "select t1.*,t15.flag_quirurgico, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente,t4.id_consultorio,t2.sexo,t2.nro_doc_ident,t2.tipo_doc_ident,
    t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno , t12.parentesco, t2.fecha_nac, t2.cip, t2.grado
    FROM prestaciones t1
    inner join asegurados t2 on t1.id_asegurado = t2.id and t2.id_estado_reg = 1
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t15 on t4.id_consultorio=t15.id
    left join citas t12 on t1.id_cita=t12.id
    Where t1.id=" . $id;
  
    $data = DB::select($cad);
    if (empty($data)) {
      return null; 
    }
    else{
      return $data[0];  
    }
    //dd($data[0]);
    
  }

  public function GetPrestacionByIdLaboratorioN3($id)
  {
    $cad = "
    select t1.created_at,t1.id,t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t11.nro_historia,t2.sexo,t1.condicion,t2.fecha_nac,tb.descripcion, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t12.parentesco,t2.grado,t1.id_ext,t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.motivo_visita, t1.tiempo_enfermedad, t1.t_enfermedad,t1.periodo, t1.examen_clinico, t1.examen_auxiliar, t1.tratamiento_auxiliar, t1.plan_trabajo,t1.antecedente_familiar, t1.antecedente,t2.id_asegurado_titular,t2.cip, t2.situacion_paciente,
    t2.nro_doc_ident,t1.ipress,  t1.upss, (CASE WHEN ups.codigo IS NOT NULL THEN ups.descripcion_upss else '-' END) as nombre_upss,t1.fecha_alta,t1.id_motivo_alta,t1.id_estado_reg,t1.flag_ref_cref,
    t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t4.id_consultorio,t15.nombre servicio,t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,tdi.descripcion,t5.colegiatura, t1.id_modalidad, t5.colegiatura colegiatura_medico,
    dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion,/*t9.id id_receta,*/to_char(t1.fecha_atencion,'dd-mm-YYYY')fecha_atencion,t10.nombre tipo_atencion
    ,anamnesis,examen_fisico,t1.aspectos_generales,t14.nombre servicio_derivar,t1.id_cita , t1.fecha_firma, t1.id_tipo_atencion, t1.id_ext, t1.codigo_upss, t1.registrar_cita_por_medico, t2.correo, t2.domicilio, t2.telefono, t2.telefono2, t2.cip as cip_asegurado, at.tipo_doc_ident as tipodoc_titular, at.nombre nombre_titular,at.paterno paterno_titular,at.materno materno_titular, at.nro_doc_ident nrodoctitular,at.cip cip_titular,at.grado grado_titular, t2.tipo_doc_ident,
    t12.referido, t12.establecimiento_referencia, t3.codigo establecimiento_codigo,  t2.id_tipo_doc_ident id_tipo_doc_ident , t12.numero_doc, t15.reporte_columnas_lab, t12.id_cita_lab, t3.nivel as nivel_estable, t3.codigo as codigo_renaes, t2.situacion_paciente, t1.flag as flag_atencion_medica, t1.ruta_prestacion as ruta_prestacion_atencion_medica, t1.examen_clinico_regional, t1.indicacion_terapeutica, t1.examen_ayuda_diagnostica, t1.procedimiento_medico_quirurgico, t1.interconsulta, t12.id_referencia, d.descripcion as departamento -- ,t16.nombre as servicio_referencia
    ,t1.indicacion_terapeutica, t1.cups_pnp as codigo_cups, t1.procedimiento_medico_quirurgico,t12.codigo_siteds ,t12.updated_at_cod_siteds ,t12.updated_us_cod_siteds , CASE WHEN t12.updated_at_cod_siteds::DATE != CURRENT_DATE THEN 0 ELSE 1 END AS is_valid_cod_siteds,
    /*lab.rua,*/g.id as id_gestacion, g.duda, g.fech_ult_regla ,g.fech_prob_parto ,g.semanas_gestacion ,g.num_controles, 
    t1.fecha_atencion as fecha_hora_atencion,
    t1.flag as flag_firma, 
    CASE WHEN DATE(t1.created_at) = CURRENT_DATE THEN '1' ELSE '0' END AS is_editable ,t15.flag_quirurgico, c.nombre servicio_cargo
    FROM prestaciones t1
    inner join tipo_atenciones t10 on t1.id_tipo_atencion=t10.id 
    left join upsses ups on t1.codigo_upss=ups.codigo
    inner join asegurados t2 on t1.id_asegurado = t2.id and t2.id_estado_reg = 1
    inner join asegurado_historias t11 on t1.id_asegurado = t11.id_asegurado And t1.id_establecimiento = t11.id_establecimiento 
    left join tipo_beneficiarios tb on t2.id_tipo_beneficiario =tb.id 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t15 on t4.id_consultorio=t15.id
    inner join medicos t5 on t1.id_medico=t5.id
    inner join consultorios c on t1.id_servicio = c.id 
    left join departamentos d on t2.id_departamento =d.id_departamento 
    left join especializaciones t7 on t5.id_especializacion=t7.id
    left join profesiones t8 on t5.id_profesion=t8.id
    --left join receta_vales t9 on t1.id=t9.id_prestacion And t9.estado<>0 
    left join citas t12 on t1.id_cita=t12.id
    left join citas t13 on t1.id_cita=t13.id_parent_cita
    left join consultorios t14 on t13.id_sub_consultorio=t14.id
    left join referencias on referencias.id_prestacion = t1.id
    left join asegurados at on at.id = t2.id_asegurado_titular
    LEFT JOIN tipo_documento_identidad tdi ON CAST(t5.tipo_documento AS INTEGER) = CAST(tdi.codigo AS INTEGER)
    --left join consultorios t16 on CAST(t12.servicio_referencia AS INTEGER) = t16.id
    --left join establecimientos t17 on referencias.id_establecimiento_des=t17.id
    left join gestaciones g on t1.id_gestacion = g.id 
    --left join laboratorios lab on t1.id = lab.id_prestacion
    Where t1.id=" . $id;
    
    $data = DB::select($cad);
    if (empty($data)) {
        return null; 
    }
      if(count($data)!= 0 ){
        //dd($data[0]);
        if($data[0]->id_ext != null){        
          if($data[0]->id_tipo_atencion == 3){//hospitalizacion
            $idExt = $data[0]->id_ext;
            $cama_info = DB::select("select c.nombre nombre_cama,cuar.nombre nombre_cuarto, p.nombre pabellon
            from hospitalizaciones h
            left join camas c on h.id_cama_estado = c.id
            left join cuartos cuar on c.id_cuarto=cuar.id
            left join pabellones p on p.id=h.id_pabellon
            where h.id = $idExt;");
            //dd($data);
          }elseif($data[0]->id_tipo_atencion == 2){//emergencia
            $idExt = $data[0]->id_ext;
            $cama_info = DB::select("select ca.nombre nombre_cama 
            ,cuar.nombre nombre_cuarto,TO_CHAR(e.created_at, 'DD-MM-YYYY') fecha_evaluacion
            from emergencias e  
            left join camas ca on e.cama_estado =ca.id 
            left join cuartos cuar on ca.id_cuarto=cuar.id
            
            where e.id = $idExt;");
          }
          $data[0]->nmro_cama = "";
          $data[0]->ubicacion = "";
          $data[0]->fecha_evaluacion = "";
          if(count($cama_info)!= 0){
            $data[0]->nmro_cama = $cama_info[0]->nombre_cama;
            if($data[0]->id_tipo_atencion == 3){//hospitalizacion
              // dd("hola");
              $data[0]->ubicacion = $cama_info[0]->pabellon;
            }else{//emergencia
              $data[0]->fecha_evaluacion  = $cama_info[0]->fecha_evaluacion;
              $data[0]->ubicacion = $cama_info[0]->nombre_cuarto;
            }
            
          }
        }
      }
    return $data[0];
  }
}