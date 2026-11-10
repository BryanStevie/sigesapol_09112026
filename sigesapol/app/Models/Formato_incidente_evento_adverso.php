<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Medico;


class Formato_incidente_evento_adverso extends Model
{

    protected $table = 'formato_incidente_evento_adverso';


    public function listaFormatoIncidenciaEventoA($tipo,$fecha_inicial,$fecha_final){
        $sql = "";
        if(!empty($tipo) && !empty($fecha_inicial) && !empty($fecha_final)){
            $sql ="where f.id_tipo_atencion ='".$tipo."' and notificador_fecha_evento between '".$fecha_inicial."' and '".$fecha_final."'";  
        }else if(empty($tipo) && !empty($fecha_inicial) && !empty($fecha_final)){
            $sql ="where notificador_fecha_evento between '".$fecha_inicial."' and '".$fecha_final."'"; 
        }
        
        $cad = "select f.id as id_formato,
        to_char( f.notificador_fecha_evento::date,'DD/MM/YYYY') AS notificador_fecha_evento ,
        f.notificador_hora_evento,h.nombre_consultorio,u.name,u.paterno,u.materno,ta.nombre as tipo_atencion from formato_incidente_evento_adverso f
        INNER JOIN hospitalizaciones as h ON f.id_hospitalizacion =h.id
        INNER JOIN users as u ON f.id_usuario_creacion = u.id
        INNER JOIN tipo_atenciones as ta ON f.id_tipo_atencion = ta.id
        $sql";
        $data = DB::select($cad);
        return $data;
    }

    public function listaFormatoIncidenciaEventoA_Emergencia($tipo,$fecha_inicial,$fecha_final){
      $sql = "";
      if(!empty($tipo) && !empty($fecha_inicial) && !empty($fecha_final)){
          $sql ="where f.id_tipo_atencion ='".$tipo."' and notificador_fecha_evento between '".$fecha_inicial."' and '".$fecha_final."'";  
      }else if(empty($tipo) && !empty($fecha_inicial) && !empty($fecha_final)){
          $sql ="where notificador_fecha_evento between '".$fecha_inicial."' and '".$fecha_final."'"; 
      }
      
      $cad = "select c.id as id_servicio, f.id as id_formato,c.nombre as nombre_consultorio,
      to_char( f.notificador_fecha_evento::date,'DD/MM/YYYY') AS notificador_fecha_evento ,
      f.notificador_hora_evento,e.id_triaje,u.name,u.paterno,u.materno,ta.nombre as tipo_atencion
      from formato_incidente_evento_adverso f
      INNER JOIN emergencias e ON f.id_emergencia=e.id
      INNER JOIN users u ON f.id_usuario_creacion = u.id
      INNER JOIN tipo_atenciones as ta ON f.id_tipo_atencion = ta.id
      inner join triajes t on t.id =e.id_triaje 
      inner join consultorios c on c.id =t.id_servicio 
      $sql";
      $data = DB::select($cad);
      return $data;
  }

    public function getFormatoIncidenteEventoAdversoById($id_incidente){
        $medico = new Medico;

        $sql="select us.name as nombre_usuario,us.materno  as materno_usuario,us.paterno as paterno_usuario,
        e.codigo as codigo_ipress,
        e.nivel as nivel_ipress,
        titular.tipo_doc_ident as tipo_doc_ident_titular,
        titular.nro_doc_ident as dni_titular,
        titular.nombre||' '||titular.paterno||' '||titular.materno as nombre_titular,
        titular.cip as contacto_titular,
        tbe.descripcion as tipo_beneficiario,
        tseg.descripcion as tipo_seguro,
        ts.descripcion_corta as tipo_sangre,
        to_char(age(TO_DATE(a.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"días\"') as fec_nacim_descrip,
        a.tipo_doc_ident as paciente_tipo_doc,
        a.nombre||' '||a.paterno||' '||a.materno as paciente,
        a.nro_doc_ident as dni_beneficiario,
        a.fecha_nac,
        a.sexo,
        a.tipo_seguro_numero,
        a.id as id_asegurado,
        ne.talla,
        ne.peso,
        ne.descripcion_acompaniado_por,
        ne.acompaniado_por,
        h.nro_hospitalizacion,
        to_char(h.fecha_ingreso::date,'DD/MM/YYYY') AS fecha_ingreso, 
        to_char(h.hora_ingreso::time,'HH:MI:SS AM') AS hora_ingreso, 
        ah.nro_historia as hc,
        ah.nro_historia as hca,
        f.responsable_categoria_adverso,
        f.responsable_tipo_adverso,
        f.id as id_formato,
        f.notificador_tipo_personal_descripcion,
        f.notificador_tipo_personal,
        f.notificador_tipo_registro,
        f.notificador_observaciones,
        f.notificador_tipo_evento_a,
        to_char( f.fecha_notificacion::date,'DD/MM/YYYY') AS fecha_notificacion, 
        to_char( f.notificador_fecha_evento::date,'DD/MM/YYYY') AS notificador_fecha_evento,
        f.notificador_hora_evento,u.name,u.paterno,u.materno,
        f.responsable_personal_convocado,
        f.responsable_analisis_causas,
        f.planmejora_acciones_correctivas as acciones_correctivas,
        f.planmejora_acciones_preventivas as acciones_preventivas,
        f.verificacion_plan_mejora,
        to_char(f.fecha_verificacion::date,'DD/MM/YYYY') as fecha_verificacion,
        ta.nombre as tipo_atencion,
        pa.descrip_corta as categoria_eadverso,
        pd.descrip_larga as tipo_eadverso,
        pab.nombre pabellon,
        pis.nombre piso,
        ca.nombre cama,
        cua.nombre cuarto,
        cons.nombre as nombre_servicio
        from formato_incidente_evento_adverso f
        LEFT JOIN hospitalizaciones as h ON f.id_hospitalizacion =h.id
        LEFT JOIN asegurado_historias as ah ON f.id_asegurado = ah.id_asegurado
        LEFT JOIN users as u ON f.id_usuario_creacion = u.id
        LEFT JOIN tipo_atenciones as ta ON f.id_tipo_atencion = ta.id
        LEFT JOIN parametros as pa ON f.responsable_categoria_adverso =pa.id
        LEFT JOIN parametros_detalle as pd ON f.responsable_tipo_adverso=pd.id
        LEFT JOIN nota_enfermeria as ne ON f.id_nota_enfermeria=ne.id
        LEFT JOIN asegurados as a ON f.id_asegurado=a.id
        LEFT JOIN asegurados as titular ON a.id_asegurado_titular=titular.id
        LEFT JOIN tipo_seguros as tseg ON a.id_tipo_seguro=tseg.id
        LEFT JOIN tipo_sangres as ts ON a.id_tipo_sangre=ts.id
        LEFT JOIN tipo_beneficiarios as tbe ON a.id_tipo_beneficiario=tbe.id
        LEFT JOIN establecimientos as e ON f.id_establecimiento=e.id
        LEFT JOIN camas ca on h.id_cama=ca.id
        LEFT JOIN cuartos cua on h.id_cuarto=cua.id
        LEFT JOIN asignacion_servicios ass on cua.id_asignacion_servicio = ass.id
        LEFT JOIN consultorios cons on ass.id_consultorio=cons.id 
        LEFT JOIN pisos pis on ass.id_piso=pis.id
		    LEFT JOIN pabellones pab on pis.id_pabellon=pab.id 
        LEFT JOIN users as us on f.id_usuario_creacion=us.id 
        where f.id='".$id_incidente."'";
        $resultado = DB::select($sql);
        $data = array();
        $data['nombre_usuario']=$resultado[0]->nombre_usuario;
        $data['materno_usuario']=$resultado[0]->materno_usuario;
        $data['paterno_usuario']=$resultado[0]->paterno_usuario;
        
        $data['tipo_doc_ident_titular']=$resultado[0]->tipo_doc_ident_titular;
        $data['dni_titular']=$resultado[0]->dni_titular;
        $data['nombre_titular']=$resultado[0]->nombre_titular;
        $data['contacto_titular']=$resultado[0]->contacto_titular;
        
        $data['paciente_tipo_doc']=$resultado[0]->paciente_tipo_doc; 
        $data['tipo_beneficiario']=$resultado[0]->tipo_beneficiario; 
        $data['tipo_seguro_numero']=$resultado[0]->tipo_seguro_numero; 
        $data['tipo_seguro']=$resultado[0]->tipo_seguro; 
        $data['tipo_sangre']=$resultado[0]->tipo_sangre;
        $data['tipo_atencion']=$resultado[0]->tipo_atencion;
        $data['edad']=$resultado[0]->fec_nacim_descrip;
        $data['paciente']=$resultado[0]->paciente;
        $data['dni_beneficiario']=$resultado[0]->dni_beneficiario;
        $data['fecha_nac']=$resultado[0]->fecha_nac;
        $data['talla']=$resultado[0]->talla;
        $data['descripcion_acompaniado_por']=$resultado[0]->descripcion_acompaniado_por;
        $data['acompaniado_por']=$resultado[0]->acompaniado_por;
        $data['peso']=$resultado[0]->peso;
        $data['sexo']=$resultado[0]->sexo;
        $data['nombre_servicio']=$resultado[0]->nombre_servicio;
        $data['hc']=$resultado[0]->hc;
        $data['hca']=$resultado[0]->hca;
        $data['id_asegurado']=$resultado[0]->id_asegurado;
        $data['nro_hospitalizacion']=$resultado[0]->nro_hospitalizacion;
        $data['fecha_verificacion']=$resultado[0]->fecha_verificacion;
        $data['notificador_tipo_personal_descripcion']=$resultado[0]->notificador_tipo_personal_descripcion;
        $data['notificador_tipo_personal']=$resultado[0]->notificador_tipo_personal;
        $data['notificador_tipo_registro']=$resultado[0]->notificador_tipo_registro;
        $data['notificador_tipo_evento_a']=$resultado[0]->notificador_tipo_evento_a;
        $data['fecha_notificacion']=$resultado[0]->fecha_notificacion;
        $data['notificador_fecha_evento']=$resultado[0]->notificador_fecha_evento;
        $data['notificador_observaciones']=$resultado[0]->notificador_observaciones;
        $data['notificador_hora_evento']=$resultado[0]->notificador_hora_evento;
        $data['categoria_eadverso']=$resultado[0]->categoria_eadverso;
        $data['tipo_eadverso']=$resultado[0]->tipo_eadverso;
        $data['usuario_registrador']=$resultado[0]->name.' '.$resultado[0]->paterno.' '.$resultado[0]->materno;
        $data['analisis_causas']=$resultado[0]->responsable_analisis_causas;
        $data['id_incidente']=$resultado[0]->id_formato;
        $data['fecha_ingreso']=$resultado[0]->fecha_ingreso;
        $data['hora_ingreso']=$resultado[0]->hora_ingreso;
        $data['nivel_ipress']=$resultado[0]->nivel_ipress;
        $data['codigo_ipress']=$resultado[0]->codigo_ipress;
        $data['pabellon']=$resultado[0]->pabellon;
        $data['piso']=$resultado[0]->piso;
        $data['cama']=$resultado[0]->cama;
        $data['cuarto']=$resultado[0]->cuarto;
        $data['verificacion_plan_mejora']=$resultado[0]->verificacion_plan_mejora;
        $data['responsable_categoria_adverso']=$resultado[0]->responsable_categoria_adverso;
        $data['responsable_tipo_adverso']=$resultado[0]->responsable_tipo_adverso;
        
        $responsable_personal_convocado = "";
        $personal_convocados = array();
        if(!empty($resultado[0]->responsable_personal_convocado)){
          $responsable_personal_convocado = json_decode($resultado[0]->responsable_personal_convocado);
          
          foreach($responsable_personal_convocado as $pc){
            $medicoDato=$medico->GetMedicoById($pc->personal_convocado);
            $personal_convocados[]=array(
              'id'=>$pc->personal_convocado,
              'nombre'=>$medicoDato->nombre.' '.$medicoDato->paterno.' '.$medicoDato->materno,
            );
          }
        }
        $data['convocados']=$personal_convocados;

        $plan_mejora_acciones_correctivas = "";
        $p_acciones_correctivas = array();
        if(!empty($resultado[0]->acciones_correctivas)){
          $plan_mejora_acciones_correctivas = json_decode($resultado[0]->acciones_correctivas);
          foreach($plan_mejora_acciones_correctivas as $ac){
            $p_acciones_correctivas[]= array('acciones_correctivas'=>$ac->acciones_correctivas,'implementado'=>$ac->implementado);
          }
        }
        $data['correctivas']=$p_acciones_correctivas;

        $plan_mejora_acciones_preventivas = "";
        $p_acciones_preventivas = array();
        if(!empty($resultado[0]->acciones_correctivas)){
          $plan_mejora_acciones_preventivas = json_decode($resultado[0]->acciones_preventivas);
          foreach($plan_mejora_acciones_preventivas as $ap){
            $p_acciones_preventivas[]= array('acciones_preventivas'=>$ap->acciones_preventivas,'implementado'=>$ap->implementado);
          }
        }
        $data['preventivas']=$p_acciones_preventivas;
        return $data;
    }
    // public function getDiagnosticosByIdPacienteAndIdHospitalizacion($id_hospitalizacion,$dni_paciente){
    //   $sql ="select  d.nombre as diagnosticos from hospitalizaciones h 
    //         inner join receta_diagnosticos rd on h.id = rd.id_hospitalizacion 
    //         inner join diagnosticos d on rd.id_diagnostico =d.id 
    //         where h.dni_beneficiario='".$dni_paciente."' and h.id='".$id_hospitalizacion."'";
    //         $data = DB::select($sql);
    //     return $data;
    // }
    public function getDiagnosticosByIdPacienteAndIdHospitalizacion($id_hospitalizacion,$dni_paciente){
      $sql ="select  d.nombre as diagnosticos from hospitalizaciones h 
            inner join receta_diagnosticos rd on h.id = rd.id_hospitalizacion 
            inner join diagnosticos d on rd.id_diagnostico =d.id 
            where h.dni_beneficiario='".$dni_paciente."' and h.id='".$id_hospitalizacion."'";
            $data = DB::select($sql);
        return $data;
    }
    public function getDiagnosticosByIdPacienteAndId_Emergencia($id_emergencia,$dni_paciente){
      $sql ="select  d.nombre as diagnosticos 
      from emergencias e
               inner join prestaciones p on e.id =p.id_ext 
              inner join receta_diagnosticos rd on rd.id_prestacion =p.id  
              inner join diagnosticos d on rd.id_diagnostico =d.id
            where e.dni_beneficiario='".$dni_paciente."' and e.id='".$id_emergencia."'";
            $data = DB::select($sql);
        return $data;
    }

    public function getFormatoIncidenteEventoAdversoById_emergencia($id_incidente){
      $medico = new Medico;

      $sql="select us.name as nombre_usuario,us.materno  as materno_usuario,us.paterno as paterno_usuario,
      e.codigo as codigo_ipress,
      e.nivel as nivel_ipress,
      titular.tipo_doc_ident as tipo_doc_ident_titular,
      titular.nro_doc_ident as dni_titular,
      titular.nombre||' '||titular.paterno||' '||titular.materno as nombre_titular,
      titular.cip as contacto_titular,
      tbe.descripcion as tipo_beneficiario,
      tseg.descripcion as tipo_seguro,
      ts.descripcion_corta as tipo_sangre,
      to_char(age(TO_DATE(a.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"días\"') as fec_nacim_descrip,
      a.tipo_doc_ident as paciente_tipo_doc,
      a.nombre||' '||a.paterno||' '||a.materno as paciente,
      a.nro_doc_ident as dni_beneficiario,
      a.fecha_nac,
      a.sexo,
      a.tipo_seguro_numero,
      a.id as id_asegurado,
      ne.talla,
      ne.peso,
      ne.descripcion_acompaniado_por,
      ne.acompaniado_por,
      h.cups_pnp,
      to_char(h.fecha_ingreso::date,'DD/MM/YYYY') AS fecha_ingreso, 
      to_char(h.hora_ingreso::time,'HH:MI:SS AM') AS hora_ingreso, 
      ah.nro_historia as hc,
      ah.nro_historia as hca,
      f.responsable_categoria_adverso,
      f.responsable_tipo_adverso,
      f.id as id_formato,
      f.notificador_tipo_personal_descripcion,
      f.notificador_tipo_personal,
      f.notificador_tipo_registro,
      f.notificador_observaciones,
      f.notificador_tipo_evento_a,
      to_char( f.fecha_notificacion::date,'DD/MM/YYYY') AS fecha_notificacion, 
      to_char( f.notificador_fecha_evento::date,'DD/MM/YYYY') AS notificador_fecha_evento,
      f.notificador_hora_evento,u.name,u.paterno,u.materno,
      f.responsable_personal_convocado,
      f.responsable_analisis_causas,
      f.planmejora_acciones_correctivas as acciones_correctivas,
      f.planmejora_acciones_preventivas as acciones_preventivas,
      f.verificacion_plan_mejora,
      to_char(f.fecha_verificacion::date,'DD/MM/YYYY') as fecha_verificacion,
      ta.nombre as tipo_atencion,
      pa.descrip_corta as categoria_eadverso,
      pd.descrip_larga as tipo_eadverso,
      pab.nombre pabellon,
      pis.nombre piso,
      ca.nombre cama,
      cua.nombre cuarto,
      cons.nombre as nombre_servicio
      from formato_incidente_evento_adverso f
      LEFT JOIN emergencias as h ON f.id_emergencia =h.id
      LEFT JOIN asegurado_historias as ah ON f.id_asegurado = ah.id_asegurado
      LEFT JOIN users as u ON f.id_usuario_creacion = u.id
      LEFT JOIN tipo_atenciones as ta ON f.id_tipo_atencion = ta.id
      LEFT JOIN parametros as pa ON f.responsable_categoria_adverso =pa.id
      LEFT JOIN parametros_detalle as pd ON f.responsable_tipo_adverso=pd.id
      LEFT JOIN nota_enfermeria as ne ON f.id_nota_enfermeria=ne.id
      LEFT JOIN asegurados as a ON f.id_asegurado=a.id
      LEFT JOIN asegurados as titular ON a.id_asegurado_titular=titular.id
      LEFT JOIN tipo_seguros as tseg ON a.id_tipo_seguro=tseg.id
      LEFT JOIN tipo_sangres as ts ON a.id_tipo_sangre=ts.id
      LEFT JOIN tipo_beneficiarios as tbe ON a.id_tipo_beneficiario=tbe.id
      LEFT JOIN establecimientos as e ON f.id_establecimiento=e.id
      LEFT JOIN camas ca on h.cama_estado =ca.id
      LEFT JOIN cuartos cua on ca.id_cuarto=cua.id
      LEFT JOIN asignacion_servicios ass on cua.id_asignacion_servicio = ass.id
      LEFT JOIN consultorios cons on ass.id_consultorio=cons.id 
      LEFT JOIN pisos pis on ass.id_piso=pis.id
      LEFT JOIN pabellones pab on pis.id_pabellon=pab.id 
      LEFT JOIN users as us on f.id_usuario_creacion=us.id 
      where f.id='".$id_incidente."'";
     

      $resultado = DB::select($sql);
      $data = array();
      $data['nombre_usuario']=$resultado[0]->nombre_usuario;
      $data['materno_usuario']=$resultado[0]->materno_usuario;
      $data['paterno_usuario']=$resultado[0]->paterno_usuario;
      
      $data['tipo_doc_ident_titular']=$resultado[0]->tipo_doc_ident_titular;
      $data['dni_titular']=$resultado[0]->dni_titular;
      $data['nombre_titular']=$resultado[0]->nombre_titular;
      $data['contacto_titular']=$resultado[0]->contacto_titular;
      
      $data['paciente_tipo_doc']=$resultado[0]->paciente_tipo_doc; 
      $data['tipo_beneficiario']=$resultado[0]->tipo_beneficiario; 
      $data['tipo_seguro_numero']=$resultado[0]->tipo_seguro_numero; 
      $data['tipo_seguro']=$resultado[0]->tipo_seguro; 
      $data['tipo_sangre']=$resultado[0]->tipo_sangre;
      $data['tipo_atencion']=$resultado[0]->tipo_atencion;
      $data['edad']=$resultado[0]->fec_nacim_descrip;
      $data['paciente']=$resultado[0]->paciente;
      $data['dni_beneficiario']=$resultado[0]->dni_beneficiario;
      $data['fecha_nac']=$resultado[0]->fecha_nac;
      $data['talla']=$resultado[0]->talla;
      $data['descripcion_acompaniado_por']=$resultado[0]->descripcion_acompaniado_por;
      $data['acompaniado_por']=$resultado[0]->acompaniado_por;
      $data['peso']=$resultado[0]->peso;
      $data['sexo']=$resultado[0]->sexo;
      $data['nombre_servicio']=$resultado[0]->nombre_servicio;
      $data['hc']=$resultado[0]->hc;
      $data['hca']=$resultado[0]->hca;
      $data['id_asegurado']=$resultado[0]->id_asegurado;
      $data['cups_pnp']=$resultado[0]->cups_pnp;
      $data['fecha_verificacion']=$resultado[0]->fecha_verificacion;
      $data['notificador_tipo_personal_descripcion']=$resultado[0]->notificador_tipo_personal_descripcion;
      $data['notificador_tipo_personal']=$resultado[0]->notificador_tipo_personal;
      $data['notificador_tipo_registro']=$resultado[0]->notificador_tipo_registro;
      $data['notificador_tipo_evento_a']=$resultado[0]->notificador_tipo_evento_a;
      $data['fecha_notificacion']=$resultado[0]->fecha_notificacion;
      $data['notificador_fecha_evento']=$resultado[0]->notificador_fecha_evento;
      $data['notificador_observaciones']=$resultado[0]->notificador_observaciones;
      $data['notificador_hora_evento']=$resultado[0]->notificador_hora_evento;
      $data['categoria_eadverso']=$resultado[0]->categoria_eadverso;
      $data['tipo_eadverso']=$resultado[0]->tipo_eadverso;
      $data['usuario_registrador']=$resultado[0]->name.' '.$resultado[0]->paterno.' '.$resultado[0]->materno;
      $data['analisis_causas']=$resultado[0]->responsable_analisis_causas;
      $data['id_incidente']=$resultado[0]->id_formato;
      $data['fecha_ingreso']=$resultado[0]->fecha_ingreso;
      $data['hora_ingreso']=$resultado[0]->hora_ingreso;
      $data['nivel_ipress']=$resultado[0]->nivel_ipress;
      $data['codigo_ipress']=$resultado[0]->codigo_ipress;
      $data['pabellon']=$resultado[0]->pabellon;
      $data['piso']=$resultado[0]->piso;
      $data['cama']=$resultado[0]->cama;
      $data['cuarto']=$resultado[0]->cuarto;
      $data['verificacion_plan_mejora']=$resultado[0]->verificacion_plan_mejora;
      $data['responsable_categoria_adverso']=$resultado[0]->responsable_categoria_adverso;
      $data['responsable_tipo_adverso']=$resultado[0]->responsable_tipo_adverso;
      
      $responsable_personal_convocado = "";
      $personal_convocados = array();
      if(!empty($resultado[0]->responsable_personal_convocado)){
        $responsable_personal_convocado = json_decode($resultado[0]->responsable_personal_convocado);
        
        foreach($responsable_personal_convocado as $pc){
          $medicoDato=$medico->GetMedicoById($pc->personal_convocado);
          $personal_convocados[]=array(
            'id'=>$pc->personal_convocado,
            'nombre'=>$medicoDato->nombre.' '.$medicoDato->paterno.' '.$medicoDato->materno,
          );
        }
      }
      $data['convocados']=$personal_convocados;

      $plan_mejora_acciones_correctivas = "";
      $p_acciones_correctivas = array();
      if(!empty($resultado[0]->acciones_correctivas)){
        $plan_mejora_acciones_correctivas = json_decode($resultado[0]->acciones_correctivas);
        foreach($plan_mejora_acciones_correctivas as $ac){
          $p_acciones_correctivas[]= array('acciones_correctivas'=>$ac->acciones_correctivas,'implementado'=>$ac->implementado);
        }
      }
      $data['correctivas']=$p_acciones_correctivas;

      $plan_mejora_acciones_preventivas = "";
      $p_acciones_preventivas = array();
      if(!empty($resultado[0]->acciones_correctivas)){
        $plan_mejora_acciones_preventivas = json_decode($resultado[0]->acciones_preventivas);
        foreach($plan_mejora_acciones_preventivas as $ap){
          $p_acciones_preventivas[]= array('acciones_preventivas'=>$ap->acciones_preventivas,'implementado'=>$ap->implementado);
        }
      }
      $data['preventivas']=$p_acciones_preventivas;
      return $data;
  }
}
