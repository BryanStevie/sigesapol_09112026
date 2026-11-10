<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Gestacion extends Model
{
    protected $table = 'gestaciones';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['duda', 'fech_ult_regla', 'fech_prob_parto', 'semanas_gestacion', 'num_controles'];
    public function showSituacion_estado() {
      // $data = DB::table('gestaciones')
      //             ->rightJoin('tipo_estado_paciente', 'gestaciones.id_tipo_estado_paciente', '=', 'tipo_estado_paciente.id')
      //             ->select('tipo_estado_paciente.id','tipo_estado_paciente.nombre')
      //             ->distinct()
      //             ->get();
      // return $data;
  }
     public function GetPrestacionById($id)
  {

    $cad = "select t1.id,t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t11.nro_historia,t2.sexo,t1.condicion,t2.fecha_nac, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t12.parentesco,t2.grado,t2.tipo_doc_ident,t1.id_ext,t1.temperatura, t1.sa02_porcentaje, t1.presion_arterial_x, t1.presion_arterial_y, t1.talla_cm, t1.tamano_cintura_cm, t1.frecuencia_respiratoria, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.frecuencia_cardiaca, t1.fi02, t1.motivo_visita, t1.tiempo_enfermedad, t1.examen_clinico, t1.examen_auxiliar, t1.tratamiento_auxiliar, t1.plan_trabajo,t1.antecedente_familiar, t1.antecedente,t2.id_asegurado_titular,t2.cip, t2.situacion_paciente,t2.discapacidad, t2.discapacidad_carnet,t2.nro_carnet_conadis,t2.color_carnet_conadis, 
    t2.presc_producto_apoyo,t2.usa_product_apoyo,t2.tipo_producto,t2.nro_tipo_producto, t2.nro_doc_ident,t1.ipress,  t1.upss,(CASE WHEN ups.codigo IS NOT NULL THEN ups.descripcion_upss else '-' END) as nombre_upss,t1.fecha_alta,t1.id_motivo_alta,t1.id_estado_reg,
    t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t4.id_consultorio,t15.nombre servicio,t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno, t1.id_modalidad, t5.colegiatura colegiatura_medico,
    dni,colegiatura,rne/*,t6.nombre servicio*/,t7.nombre especializacion,t8.nombre profesion,t9.id id_receta,to_char(t1.fecha_atencion,'dd-mm-YYYY')fecha_atencion,t10.nombre tipo_atencion
    ,anamnesis,examen_fisico,t1.aspectos_generales,t14.nombre servicio_derivar,t1.id_cita , t1.fecha_firma, t1.id_tipo_atencion, t1.id_ext, t1.codigo_upss, t1.registrar_cita_por_medico, t2.correo, t2.domicilio, t2.telefono, t2.telefono2, t1.fecha_hora_update, t1.motivo_update
    ,t1.informe_pp,t2.nombre_titular,t2.paterno_titular,t2.materno_titular,at.nro_doc_ident as dni_titular,at.grado as grado_titular,t12.codigo_siteds,t1.t_enfermedad,t1.periodo,t1.tiempo_enfermedad
    FROM prestaciones t1
    inner join tipo_atenciones t10 on t1.id_tipo_atencion=t10.id 
    left join upsses ups on t1.codigo_upss=ups.codigo
    inner join asegurados t2 on t1.id_asegurado = t2.id and t2.id_estado_reg = 1
    inner join asegurado_historias t11 on t1.id_asegurado = t11.id_asegurado And t1.id_establecimiento = t11.id_establecimiento 
    inner join establecimientos t3 on t1.id_establecimiento=t3.id
    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
    inner join consultorios t15 on t4.id_consultorio=t15.id
    inner join medicos t5 on t1.id_medico=t5.id
    left join especializaciones t7 on t5.id_especializacion=t7.id
    left join profesiones t8 on t5.id_profesion=t8.id
    left join receta_vales t9 on t1.id=t9.id_prestacion And t9.estado<>0 
    left join citas t12 on t1.id_cita=t12.id
    left join citas t13 on t1.id_cita=t13.id_parent_cita
    left join consultorios t14 on t13.id_sub_consultorio=t14.id
    left join asegurados at on at.id = t2.id_asegurado_titular 
Where t1.id=" . $id;
    /*$cad = "select t1.id,t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t11.nro_historia,(case when sexo='M' then 'MASCULINO' when 'F' then 'FEMENINO' end)sexo,
		fecha_nac,tipo_doc_ident,t2.nro_doc_ident,
t1.id_establecimiento,t3.nombre establecimiento,t1.id_servicio,t4.nombre servicio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno
,dni,colegiatura,rne,t6.nombre servicio,t7.nombre especializacion,t8.nombre profesion,t9.id id_receta,t1.fecha_atencion,t10.nombre tipo_atencion
,anamnesis,examen_fisico
FROM prestaciones t1
inner join tipo_atenciones t10 on t1.id_tipo_atencion=t10.id 
inner join asegurados t2 on t1.id_asegurado = t2.id 
inner join asegurado_historias t11 on t1.id_asegurado = t11.id_asegurado And t1.id_establecimiento = t11.id_establecimiento 
inner join establecimientos t3 on t1.id_establecimiento=t3.id
inner join consultorios t4 on t1.id_servicio=t4.id
inner join medicos t5 on t1.id_medico=t5.id
inner join consultorios t6 on t1.id_servicio=t6.id
left join especializaciones t7 on t5.id_especializacion=t7.id
left join profesiones t8 on t5.id_profesion=t8.id
left join receta_vales t9 on t1.id=t9.id_prestacion
Where t1.id=".$id;*/
    //echo $cad;
    //var_dump($cad);

    $data = DB::select($cad);

    return $data[0];
  }

  public function getByAseguradoDNI($aseguradoDNI)
  {
    $query = "SELECT
                g.id ,
                g.duda ,
                TO_CHAR(g.fech_ult_regla , 'dd-mm-yyyy') as fech_ult_regla ,
                TO_CHAR(g.fech_prob_parto , 'dd-mm-yyyy') as fech_prob_parto ,
                g.semanas_gestacion ,
                g.num_controles,
                TO_CHAR(p.fecha_atencion , 'dd-mm-yyyy') as fecha_atencion 
              FROM prestaciones p 
              INNER JOIN gestaciones g ON p.id_gestacion = g.id 
              inner join asegurados a on p.id_asegurado = a.id 
              WHERE a.nro_doc_ident = '" . strval($aseguradoDNI) . "'";
    $result = DB::select($query);
    return $result;
  }

  public function getLastByAseguradoDNI($aseguradoDNI)
  {
    $query = "
      select
        p.id as id_prestacion,
        g.id as id_gestacion,
        g.duda ,
        g.fech_ult_regla,
        TO_CHAR(g.fech_ult_regla , 'dd-mm-yyyy') as fech_ult_regla_format ,
        g.fech_prob_parto,
        TO_CHAR(g.fech_prob_parto , 'dd-mm-yyyy') as fech_prob_parto_format ,
        g.semanas_gestacion ,
        g.num_controles,
        p.fecha_atencion,
        TO_CHAR(p.fecha_atencion , 'dd-mm-yyyy') as fecha_atencion_format,
        e.id as id_establecimiento,
        e.codigo as establecimiento_codigo,
        e.nombre as establecimiento_nombre
      from prestaciones p
        inner join asegurados a on p.id_asegurado = a.id
        inner join gestaciones g on p.id_gestacion = g.id
        inner join establecimientos e on p.id_establecimiento = e.id
      where 
      a.nro_doc_ident = '" . strval($aseguradoDNI) . "'  
      and g.fech_prob_parto  >= date_trunc('month', CURRENT_DATE - INTERVAL '10 months')  
      order by p.id desc limit 1;";
    $result = DB::select($query);
    return $result;
  }

    public function prestacion()
    {
        return $this->belongsTo('App\Models\Prestaciones');
    }

    /* function puerperio() {
        return $this->hasOne('App\Models\Puerperio', 'id_gestacion');
    } */
}

   