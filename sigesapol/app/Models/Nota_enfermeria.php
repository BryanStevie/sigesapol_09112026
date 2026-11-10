<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class Nota_enfermeria extends Model
{
    
  protected $table = 'nota_enfermeria';

    public function caracterFisico()
    {
        return $this->hasMany(caracterFisico::class, 'nota_enfermeria');
    }
  // public function obtener_descripcion_cardiologico($valores_id){ 
  //   $cad = "select string_agg(descrip_corta, ', ') as descripcion_CArdiologico  from parametros where descrip_tipo='CARDIOLOGICO_NOT_ENF' and id in ($valores_id)";
  //   $data = DB::select($cad);
    
  //   if ($data != null || count($data) > 0){
  //     return $data[0];
  //   }else{
  //     return null;
  //   }; 
  // }
  public function obtener_descripcion_cardiologico_editar($valores_id){ 
    $cad = "select  descrip_corta,id
    from parametros 
    
    
    where descrip_tipo='CARDIOLOGICO_NOT_ENF' and id in ($valores_id)";
    $data = DB::select($cad);
    $descripciones = [];
    foreach ($data as $item) {
      //dd($item->descrip_corta);

        $descripciones[$item->id] = $item->descrip_corta; // Asumiendo que descrip_corta es el campo que contiene las descripciones cortas
    }
   // dd($descripciones);
    return $descripciones;
}
	public function getObtener_nota_ingreso_by_id_hospi($id_nota_ingreso, $id_tipo_atenciones) {
    $cad = "SELECT enfermerias.*, 
    to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"AÑOS\" \"CON\" MM \"MESES\"  \"y\" DD \"DÍAS\"') as fec_nacim_descrip,
    a.tipo_doc_ident  AS nomtipdoctitular, 
    a.nro_doc_ident  AS nrodoctitular, 
    a.paterno  AS apepattitular, 
    a.materno  AS apemattitular, 
    a.nombre  AS nomtitular, 
    a.cip  AS ciptitular,  
    a.grado  AS gradotitular,  
    a.situacion_paciente  AS situaciontitular,  
    a.unidad_pnptitular  AS unidad_pnptitular,
    m.dni as dni_medico,
    m.nombre as nombre_medico,
    m.paterno as paterno_medico,
    m.materno as materno_medico,
    m.colegiatura as colegiatura_medico,
    esp.nombre as especializacion_medico,
    prof.nombre as profesion_medico,
    enfermerias.flag_firma as tiene_firma,
    enfermerias.ruta_firma as ruta_firma

    FROM enfermerias ";

    $cad .= "LEFT JOIN hospitalizaciones ON enfermerias.id_ext_enfermeria = hospitalizaciones.id 
    LEFT JOIN asegurados ON hospitalizaciones.id_asegurado = asegurados.id
    LEFT JOIN asegurados as a ON  asegurados.id_asegurado_titular = a.id
    LEFT JOIN users u on u.id = enfermerias.created_us
    LEFT JOIN medicos m on m.dni = u.dni
    LEFT JOIN especializaciones esp on esp.id = m.id_especializacion
    LEFT JOIN profesiones prof on prof.id = m.id_profesion
    WHERE enfermerias.id ='$id_nota_ingreso' and enfermerias.id_tipo_atencion = '$id_tipo_atenciones'";
    $data = DB::select($cad);
    
    return $data;
  }
	public function getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones) {
    $cad = "SELECT enfermerias.*, 
    to_char(age(TO_DATE(a.fecha_nac , 'DD/MM/YYYY')), 'YY \"AÑOS\" \"CON\" MM \"MESES\"  \"y\" DD \"DÍAS\"') as fec_nacim_descrip,
    a.tipo_doc_ident  AS nomtipdoctitular, 
    a.nro_doc_ident  AS nrodoctitular, 
    a.paterno  AS apepattitular, 
    a.materno  AS apemattitular, 
    a.nombre  AS nomtitular, 
    a.cip  AS ciptitular,  
    a.grado  AS gradotitular,  
    a.situacion_paciente  AS situaciontitular,  
    a.unidad_pnptitular  AS unidad_pnptitular,
    enfermerias.flag_firma as tiene_firma,
    enfermerias.ruta_firma as ruta_firma

    FROM enfermerias ";

    $cad .= "LEFT JOIN emergencias ON enfermerias.id_ext_enfermeria = emergencias.id 
    LEFT JOIN asegurados ON emergencias.id_asegurado = asegurados.id
    LEFT JOIN asegurados as a ON  asegurados.id_asegurado_titular = a.id
    WHERE enfermerias.id ='$id_nota_ingreso'";
    $data = DB::select($cad);
    
    return $data;
  }

  public function getHospitalizacionById_farmacia_new2($id_hospitalizacion) {  
    $cad = "select distinct a.grado as grado_titular, a.telefono as telefono_titular, d4.nombre as nom_diagnostico ,ee.observacion as observacion_evacuados,to_char(ee.created_at ::date,'DD-MM-YYYY') AS fecha_referencia,ee.numero_oficio ,e4.nombre as esta_referencia, e3.nombre as nombre_estab , e3.codigo as codigo_ups, h.dni_titular , a.cip, a.domicilio ,h.obs_traslado as observaciones_benefi,h.telefono_acompaniante,EXTRACT(YEAR FROM age(current_date, fecha_nac::date)) as edad_beneficiario,h.tipo_beneficiario,a.tipo_sangre as sangre_beneficiario,a.grado as grado_beneficiario,a.situacion_paciente , a.cip as cip_beneficiario ,a.cip as cip_beneficiario,p5.nombre as profesion_medico, h.dni_beneficiario as nro_historiass ,e3.codigo as codigoipress,e3.nivel as nivel_ipress , a.fecha_nac as fecha_nac_paciente, a.paterno , a.nro_doc_ident , a.tipo_doc_ident as tipo_doc_ident_asegurado, e2.nombre as especialidad_medico , med.id_especializacion  , med.colegiatura ,med.rne ,med.nombre as profesional_nombre,med.paterno as profesional_paterno ,med.materno as profesional_materno ,med.dni as profesional_dni, h.id_medico ,h.telefono_acompaniante as telefono_acompaniante,h.obs_traslado as observacion_acompaniante, h.nombre_acompaniante as nombre_acompaniante, h.nro_doc_acompaniante as numero_documento_acompaniante, c.nombre as cama,c2.nombre as cuarto,p3.nombre piso, p4.nombre pabellon,ta.id as origen,to_char(h.fecha_ingreso ::date,'DD/MM/YYYY') AS fecha_ingreso, 
    to_char(h.hora_ingreso ::time,'HH:MI:SS AM') AS hora_ingreso, 
     to_char(h.created_at ::date,'DD/MM/YYYY') AS fecha_creacion, 
    to_char(h.created_at, 'HH:MI:SS AM')  AS hora_creacion, 
    tp.descripcion as conducio_por,
    ups1.codigo as codigo_ups_2,
    p2.descripcion as pronvincia_actual, p2.descripcion as pronvincia_actual,d2.descripcion as distrito_actual, d.descripcion as departamento_actual, a.domicilio as domicilio_actual,a.codigo_ubig as codigo_ubig,a.id_departamento , r.nombre as religion, a.id_religion , ts.descripcion_corta as descripcion_tipo_sangres, a.sexo ,a.fecha_nac as fecha_nacimiento_beneficiario, u2s.name as nombre_usuario,u2s.paterno paterno_usuario,u2s.materno materno_usuario, h.id_user , a.tipo_doc_ident as tipo_doc_ident_beneficiario, a.nombre ,  ah.nro_historia , a.id, h.*, m.nombre, tb.descripcion as tipo_beneficiario ,c3.nombre as nombre_consultorio,h.fecha_ingreso as fecha_ingreso ,h.estado , h.dni_beneficiario 
    ,a.nombre as nombre_beneficiario ,a.paterno as paterno_beneficiario ,a.materno as materno_beneficiario, c2.id ,
        c2.nombre AS nombre_cuarto,
    c.id ,c.nombre AS nombre_cama,
    co.nombre as servicio_hospi,
    ca.nombre as cuarto_hospi,
    pi.nombre as piso_hospi
            from hospitalizaciones h 
            left join camas  c on c.id =h.id_cama
            left join cuartos c2 on c2.id =h.id_cuarto 
            left join asignacion_servicios as2 on as2.id =c2.id_asignacion_servicio 
            left join pisos p3  on p3.id =as2.id_piso 
            left join pabellones p4 on p4.id =h.id_pabellon 
            left join asegurados a on a.id =h.id_asegurado 
            left join prestaciones p on p.id_ext =h.id 
            left join sub_consultorios sc on p.id_sub_consultorio =sc.id 
            left join consultorios c3 on c3.id =sc.id_consultorio
            left join upsses ups1 on ups1.id = h.id_upss_encargado
            left join tipo_beneficiarios tb on tb.id =a.id_tipo_beneficiario 
            left join medicos m on m.id_consultorio =p.id_medico 
            left join asegurado_historias ah on ah.id =a.id 
            left join tipo_sangres ts on ts.id =a.id_tipo_sangre 
            left join religiones r on r.id =a.id_religion 
            left join departamentos d on a.id_departamento = d.id_departamento 
            left join distritos d2 on d2.id_distrito  =a.id_distrito  
            left join provincias p2 on p2.id_provincia =a.id_provincia 
            left join tipo_atenciones ta on ta.id =p.id_tipo_atencion 
            --para traer la data del profesional medico que se llama medico_triaje el campo deberia llamarse id_usuario no id_medico_triaje
            left join users u2s on u2s.id = h.id_medico
            left join medicos med on med.dni =u2s.dni 
            --esto es el tipo_acompaniante
            left join tipo_parentescos tp on tp.id = h.id_tipo_acompaniante 
            left join especializaciones e2 on e2.id =med.id_especializacion 
            left join profesiones p5 on p5.id=med.id_profesion 
            left join establecimientos e3 on h.id_establecimiento =e3.id 
            left join emergencia_evacuados ee on ee.id_emergencia =h.id 
            left join establecimientos e4 on e4.id =ee.unidad_policial 
            left join receta_diagnosticos rd on rd.id_emergencia = h.id 
            left join diagnosticos d4 on d4.id =rd.id_diagnostico
            left join consultorios co on co.id = h.id_consultorio
            left join cuartos ca on ca.id = h.id_cuarto
            left join pisos pi on pi.id = h.id_piso
    Where  h.id=" . $id_hospitalizacion . " ";

 $data = DB::select($cad);
return $data;
}


  public function getMostrarNotaEnfermeria($id)
  {
    $cad = "select n_e.id,
                   n_e.presion_arterial_x,
                   n_e.presion_arterial_y,
                   n_e.frecuencia_cardiaca,
                   n_e.ritmo_cardiaco,
                   n_e.temperatura,
                   n_e.sa02_porcentaje,
                   n_e.frecuencia_respiratoria,
                   ps.descrip_larga,
                   n_E.grado_dependencia,
                   n_e.fi02
              from nota_enfermeria n_e
              inner join parametros ps 
              on n_e.id_estado_conciencia=ps.id
              where n_e.id_atencion =".$id;

    $data = DB::select($cad);
    
    return $data;
  }

  public function extraerPesoNotaEnfermeria($id_nota) {
    $cad = "select nve.descripcion as nota_peso 
                from notas_vitales_enfermerias nve 
                where nve.id_enfermeria = ". $id_nota ." 
                and nve.id_caracter_fisico = 5";
    $data = DB::select($cad);
    return $data[0];
  }

  public function DiagnosticosById($id_diagnostico) {
    $data = DB::table('diagnosticos as d')
        ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico')
        /* ->selectRaw('"DE ADMISION" as descripcion_tipo_diagnosticos, 1 as id_evaluacion') */
        ->where('d.id', '=', $id_diagnostico)
        ->get();
    $parsedData = collect($data);
    $parsedData = $parsedData->map(function ($diagnostico) {
      $diagnostico->descripcion_tipo_diagnosticos = 'DE ADMISION';
      $diagnostico->id_evaluacion = 1;
      return $diagnostico;
    });

    /* dd($parsedData->toArray()); */

    $data = $parsedData->toArray();
    return empty($data) ? null : $data[0];
}

public function getCabecera_id_withSPforHospi($id_enfermeria)
	{
		$paciente = DB::select('SELECT * FROM sp_get_cabecera_hospitalizacion_enfermeria(?)', [$id_enfermeria]);
		return $paciente;
	}
public function getCabecera_id_withSPforEmergencia($id_enfermeria)
	{
		$paciente = DB::select('SELECT * FROM sp_get_cabecera_enfermeria(?)', [$id_enfermeria]);
		return $paciente;
	}

  public function getCabecera_id_withSPforEmergencia_sa($id_enfermeria)
	{
		$paciente = DB::select('SELECT * FROM sp_get_cabecera_enfermeria_sa(?)', [$id_enfermeria]);
		return $paciente;
	}
  
  public function getIdExtByIdEnfermeria($id_enfermeria)
  {
    $id_ext = Enfermeria::select('id_ext_enfermeria', 'id_prestacion')->where('id', $id_enfermeria)->get();
    return $id_ext;
  }
   
}