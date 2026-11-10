<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
class Fema_ficha extends Model
{
    protected $table = 'fema_fichas';
    
    public function ShowPrioridad(){
    	$cad = "select id, codigo, descripcion as prioridad  from prioridades f where estado = 'N' Order By id asc";
        $data = DB::select($cad);
        return $data;
    }
    
    public function GetDatosMedicoAndEstablecimiento2($dni, $id_establecimiento = 0)
    {
      $cad = "select t1.*,t3.nombre profesion,t4.nombre especializacion, tdi.descripcion tipo_doc_ident
            from medicos t1
            inner join profesiones t3 on t1.id_profesion=t3.id
            inner join especializaciones t4 on t1.id_especializacion=t4.id
            left join tipo_documento_identidad tdi ON t1.id = tdi.id
            where t1.estado=1 and dni='" . $dni . "' 
            and t1.id_establecimiento=" . $id_establecimiento;

      $data = DB::select($cad);
      return $data;
    }
    
    public function GetById($id_ficha) {
        $cad = "SELECT 
               
                ff.id, 
                e.nombre establecimiento,
                CAST(ff.created_at AS date) fecha_reg,
                CAST(ff.created_at AS time) hora_reg,
                ff.tipo_doc_ident,
                ff.nro_doc_ident,
                ff.carnet,
                ff.paterno,
                ff.materno,
                ff.nombre,
                ff.grado,
                s.descripcion situacion_pnp,
                tt.nombre tipo_trabajo,
                ff.edad,
                ff.sexo,
                ff.celular,
                ff.unidades_pnp,
                ff.foto,
                ff.codigo_laboratorio,
                ff.codigo_cardiologia,
                ff.codigo_respiratorio,
                fp.nombre proceso,
                fp.abreviatura, 
                ftp.nombre tipo_proceso,
                ft.temperatura,
                ft.talla_cm,
                ft.peso_kg,
                ft.presion_arterial_x,
                ft.presion_arterial_y,
                ft.frecuencia_respiratoria,
                ft.frecuencia_cardiaca,
                ft.sa02_porcentaje,
                ft.historia_clinica,
                ft.historia_clinica_ipress,
                ff.id_situacion_pnp,
                ff.id_tipo_trabajo
                FROM fema_fichas ff 
                INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
                INNER JOIN fema_procesos fp ON ftp.id_proceso = fp.id 
                INNER JOIN establecimientos e ON ff.id_establecimiento = e.id
                INNER JOIN fema_triajes ft ON ff.id = ft.id_fema_ficha 
                LEFT JOIN situaciones s ON ff.id_situacion_pnp = s.id 
                LEFT JOIN tipo_trabajos tt ON ff.id_tipo_trabajo = tt.id

                WHERE  ff.estado = 1 and ff.id = $id_ficha;";
      $data = DB::select($cad);
      return $data;
    }
    
    public function GetAll($fechaDesde, $fechaHasta, $nroDocumento, $idProceso, $idSubProceso, $nroFicha) {
        $cad = "SELECT 
                ff.id,
                e.id as id_establecimiento,
                e.nombre as establecimiento,
                CAST(ff.created_at AS date) fecha_reg,
                CAST(ff.created_at AS time) hora_reg,
                ff.tipo_doc_ident,
                ff.nro_doc_ident,
                ff.carnet,
                ff.paterno,
                ff.materno,
                ff.nombre,
                ff.edad,
                fp.nombre proceso, 
                ftp.nombre tipo_proceso,
                u.paterno usu_paterno,
                u.materno usu_materno,
                u.name usu_nombre,
                ftp.id_proceso,
                ff.sexo,
                ff.estado,
                ft.created_at fh_reg_triaje
                FROM fema_fichas ff 
                INNER JOIN fema_triajes ft ON ff.id = ft.id_fema_ficha 
                INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
                INNER JOIN fema_procesos fp ON ftp.id_proceso = fp.id 
                INNER JOIN establecimientos e ON ff.id_establecimiento = e.id
                INNER JOIN users u ON ff.created_us = u.id";
        $cad .= " WHERE ff.estado = 1 and ft.estado = 1";
        if ($fechaDesde != ""):
            $cad .= " AND CAST(ff.created_at AS DATE) >= '$fechaDesde'";
        endif;
        if ($fechaHasta != ""):
            $cad .= " AND CAST(ff.created_at AS DATE) <= '$fechaHasta'";
        endif;
        if ($nroDocumento != ""):
            $cad .= " AND ff.nro_doc_ident = '$nroDocumento'";
        endif;
        if ($idProceso != ""):
            $cad .= " AND ftp.id_proceso = $idProceso";
        endif;
        if ($idSubProceso != ""):
            $cad .= " AND ff.id_fema_tipo_proceso = $idSubProceso";
        endif;
        if ($nroFicha != ""):
            $cad .= " AND ff.id = '$nroFicha'";
        endif;
        $cad .= " ORDER BY ff.created_at, ff.paterno, ff.materno";
        
        $data = DB::select($cad);

        return $data;
    }

    public function ExisteAtencionExamen($id_ficha, $id_proceso, $id_tipo_examen) {
        $cad = "SELECT fe.id FROM fema_examenes fe 
                INNER JOIN fema_fichas ff ON fe.id_fema_ficha = ff.id
                INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
                WHERE  ff.estado = 1 and fe.estado = 1 and fe.id_fema_ficha = ?
                AND ftp.id_proceso = ?
                AND fe.id_tipo_examen = ?";
        $results = DB::select($cad, array($id_ficha, $id_proceso, $id_tipo_examen));
        return $results;
    }

    public function VerFichaByIdFichaAndIdProcesoAndIdTipoExamen($id_ficha, $id_proceso, $id_tipo_examen) {
        $cad = "SELECT fe.*
                FROM fema_examenes fe 
                INNER JOIN fema_fichas ff ON fe.id_fema_ficha = ff.id
                INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
                WHERE  ff.estado = 1 and fe.estado = 1 and  fe.id_fema_ficha = ?
                AND ftp.id_proceso = ?
                AND fe.id_tipo_examen = ?";
        $results = DB::select($cad, array($id_ficha, $id_proceso, $id_tipo_examen));
        return $results;
    }

    public function GetObservacionesByIdFemaExamen($id) {
        $cad = "SELECT fo.*
                FROM fema_observaciones fo 
                WHERE fo.id_fema_examen = ?
                ORDER BY fo.id;";
        $results = DB::select($cad, array($id));
        return $results;
    }

    public function GetDiagnosticosByIdFemaExamen($id) {
        $cad = "SELECT fd.*, d.codigo cod_cie, d.nombre diagnostico, 
                CASE WHEN fd.id_tipo_diagnostico = 1 THEN 'PRESUNTIVO' 
                WHEN fd.id_tipo_diagnostico = 2 THEN 'DEFINITIVO' 
                WHEN fd.id_tipo_diagnostico = 3 THEN 'REPETITIVO' END AS tipo_diagnostico
                FROM fema_diagnosticos fd 
                INNER JOIN diagnosticos d ON fd.id_diagnostico = d.id
                WHERE fd.id_fema_examen = ?
                ORDER BY fd.id;";
        $results = DB::select($cad, array($id));
        return $results;
    }

    public function registrarFichaOdonto($id_proceso, $id_fema_ficha, $id_tipo_examen, $dientes, $cod_odontograma, $capacidadmasticatoria, $descripcion_odontograma, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento)
    {
            $cad = "Select sp_insert_fema_odonto(?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $dientes, $cod_odontograma, $capacidadmasticatoria, $descripcion_odontograma, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento));
            return $data[0]->sp_insert_fema_odonto;
    }

    public function registrarFichaExamenClinico($id_proceso, $id_fema_ficha, $id_tipo_examen, $antecedentes, $examen_clinico, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento)
    {
        $cad = "Select sp_insert_fema_examen_clinico(?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $antecedentes, $examen_clinico, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento));
        return $data[0]->sp_insert_fema_examen_clinico;
    }

    public function registrarFichaSaludMental($id_proceso, $id_fema_ficha, $id_tipo_examen, $area_emocional, $area_cognitiva, $area_neurobiologica, $derivacion, $observacion ,$alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento)
    {
        $cad = "Select sp_insert_fema_salud_mental(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $area_emocional, $area_cognitiva, $area_neurobiologica, $derivacion, $observacion ,$alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento));
        return $data[0]->sp_insert_fema_salud_mental;
    }

    public function registrarFichaRespiratorio($id_proceso, $id_fema_ficha, $id_tipo_examen, $examen_clinico, $examen_radiologico, $examen_esputo, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento)
    {
        $cad = "Select sp_insert_fema_respiratorio(?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $examen_clinico, $examen_radiologico, $examen_esputo, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento));
        return $data[0]->sp_insert_fema_respiratorio;
    }

    public function registrarFichaGinecologico($id_proceso, $id_fema_ficha, $id_tipo_examen, $g, $p, $menarquia, $mamografia, $irs, $urs, $fur, $andria, $mac, $antecedentes, $examen_pap, $fecha_examen_pap, $resultado_examen_pap, $prueba_iva, $resultado_iva, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$gestante,$motivo_no_pap,$id_establecimiento)
    {
        $cad = "Select sp_insert_fema_ginecologico(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $g, $p, $menarquia, $mamografia, $irs, $urs, $fur, $andria, $mac, $antecedentes, $examen_pap, $fecha_examen_pap, $resultado_examen_pap, $prueba_iva, $resultado_iva, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$gestante,$motivo_no_pap,$id_establecimiento));
        return $data[0]->sp_insert_fema_ginecologico;
    }

    public function registrarFichaCardiovascular($id_proceso, $id_fema_ficha, $id_tipo_examen, $examen_clinico, $pa_sistolica, $pa_diastolica, $ecg, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento)
    {
        $cad = "Select sp_insert_fema_cardiovascular(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $examen_clinico, $pa_sistolica, $pa_diastolica, $ecg, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento));
        return $data[0]->sp_insert_fema_cardiovascular;
    }
    
    public function registrarFichaOftalmologica($id_proceso, $id_fema_ficha, $id_tipo_examen, $vision_cercana_od, $vision_cercana_od_corregida, $vision_cercana_oi, $vision_cercana_oi_corregida, $vision_cercana_od2, $vision_cercana_od_corregida2, $vision_cercana_oi2, $vision_cercana_oi_corregida2, $tonometria, $pupilas, $motil_ocular, $fondo_ojo, $vision_colores, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento)
    {
        $cad = "Select sp_insert_fema_oftalmologica(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $vision_cercana_od, $vision_cercana_od_corregida, $vision_cercana_oi, $vision_cercana_oi_corregida, $vision_cercana_od2, $vision_cercana_od_corregida2, $vision_cercana_oi2, $vision_cercana_oi_corregida2, $tonometria, $pupilas, $motil_ocular, $fondo_ojo, $vision_colores, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento));
        return $data[0]->sp_insert_fema_oftalmologica;
    }

    public function registrarFichaLaboratorio($id_proceso, $id_fema_ficha, $id_tipo_examen, $vdrl, $leucocitos, $hematies, $glucosa_paquete, $albumina, $nitritos, $hiv, $glucosa, $colesterol, $trigliceridos, $hepatitis_b, $anti_hct, $hto, $psa, $otros, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento)
    {
        $cad = "Select sp_insert_fema_laboratorio(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $vdrl, $leucocitos, $hematies, $glucosa_paquete, $albumina, $nitritos, $hiv, $glucosa, $colesterol, $trigliceridos, $hepatitis_b, $anti_hct, $hto, $psa, $otros, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento));
        return $data[0]->sp_insert_fema_laboratorio;
    }

   public function registrarFichaOtorrinolaringologia($id_proceso, $id_fema_ficha, $id_tipo_examen, $agudezaod, $suficienciaod, $agudezaoi, $suficienciaoi, $examennariz, $menbranatimpanica, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento)
    {
        $cad = "Select sp_insert_fema_otorrinolaringologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_proceso, $id_fema_ficha, $id_tipo_examen, $agudezaod, $suficienciaod, $agudezaoi, $suficienciaoi, $examennariz, $menbranatimpanica, $alerta, $id_profesional, $id_usuario, $p_observacion, $p_diagnostico,$id_establecimiento));
        return $data[0]->sp_insert_fema_otorrinolaringologia;
    }


    public function GetAllcierre($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento) {
      $date = new Carbon();
      $anio = $date->format('Y');
        $cad = "SELECT 
                ff.id, 
                e.nombre establecimiento,
                CAST(ff.created_at AS date) fecha_reg,
                CAST(ff.created_at AS time) hora_reg,
                CAST(ff.cierre_updated_at AS date) fecha_cierre,
                CAST(ff.cierre_updated_at AS time) hora_cierre,
                ff.tipo_doc_ident,
                ff.nro_doc_ident,
                ff.carnet,
                ff.grado,
		            ff.paterno,
                ff.materno,
                ff.nombre,
                fp.nombre proceso, 
                ftp.nombre tipo_proceso,
                u.paterno usu_paterno,
                u.materno usu_materno,
                u.name usu_nombre,
                ftp.id_proceso,
                ff.sexo,
                ff.edad,
                ft.created_at fh_reg_triaje,
                ff.cierre_updated_at fh_reg_cierre
                FROM fema_fichas ff 
                INNER JOIN fema_triajes ft ON ff.id = ft.id_fema_ficha 
                INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
                INNER JOIN fema_procesos fp ON ftp.id_proceso = fp.id 
                INNER JOIN establecimientos e ON ff.id_establecimiento = e.id
                INNER JOIN users u ON ff.created_us = u.id";
        $cad .= " WHERE ff.estado = 1  and ft.estado = 1  ";
        if ($fechaDesde != ""):
            $cad .= " AND CAST(ff.created_at AS DATE) >= '$fechaDesde'";
        endif;
        if ($fechaHasta != ""):
            $cad .= " AND CAST(ff.created_at AS DATE) <= '$fechaHasta'";
        endif;
        if ($nroDocumento != ""):
            $cad .= " AND ff.nro_doc_ident = '$nroDocumento'";
        endif;
        if ($id_establecimiento != ""):
            $cad .= " AND ff.id_establecimiento = '$id_establecimiento'";
        endif;
        $cad .= " ORDER BY ff.created_at, ff.paterno, ff.materno";
        
        $data = DB::select($cad);

        return $data;
    }

    public function GetAllFema1($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento) {
      $cad = "SELECT 
              ff.*, 
              e.nombre establecimiento,
              CAST(ff.created_at AS date) fecha_reg,
              CAST(ff.created_at AS time) hora_reg,
              fp.nombre proceso, 
              ftp.nombre tipo_proceso,
              u.paterno usu_paterno,
              u.materno usu_materno,
              u.name usu_nombre,
              ftp.id_proceso,
              s.descripcion situacion_pnp
              FROM fema_fichas ff 
              LEFT JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
              LEFT JOIN fema_procesos fp ON ftp.id_proceso = fp.id 
              LEFT JOIN establecimientos e ON ff.id_establecimiento = e.id
              LEFT JOIN situaciones s ON ff.id_situacion_pnp = s.id
              LEFT JOIN users u ON ff.created_us = u.id";
      $cad .= " WHERE ff.estado = 1";
      if ($fechaDesde != ""):
          $cad .= " AND CAST(ff.created_at AS DATE) >= '$fechaDesde'";
      endif;
      if ($fechaHasta != ""):
          $cad .= " AND CAST(ff.created_at AS DATE) <= '$fechaHasta'";
      endif;
      if ($nroDocumento !=0):
        $cad .= " AND ff.nro_doc_ident = '$nroDocumento'";
      endif;
      if ($id_establecimiento !=0):
        $cad .= " AND ff.id_establecimiento = $id_establecimiento";
      endif;
      $cad .= " ORDER BY ff.created_at, ff.paterno, ff.materno";
      
      $data = DB::select($cad);
     
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
    return $data;
  }

  public function GetAllFema($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento) {

  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$nroDocumento;
  $p[3]=$id_establecimiento;
  $p[4]=1000000000;
  $p[5]=1000000000;
  $p[6]=1000000000;

  return $this->readFunctionPostgres('GetAllFema',$p);
}

  public function GetAllFemaatencion($fechaDesde, $fechaHasta, $nroDocumento=0, $id_establecimiento=0, $id_tipo_examen=0) {
    $cad = "SELECT distinct ff.id,
            ff.*, 
            e.nombre establecimiento,
            CAST(ff.created_at AS date) fecha_reg,
            CAST(ff.created_at AS time) hora_reg,
            fp.nombre proceso, 
            ftp.nombre tipo_proceso,
            u.paterno usu_paterno,
            u.materno usu_materno,
            u.name usu_nombre,
            ftp.id_proceso,
            s.descripcion situacion_pnp
            FROM fema_examenes fex
            INNER JOIN fema_fichas ff ON ff.id = fex.id_fema_ficha 
            INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
            inner join fema_triajes ft on ff.id = ft.id_fema_ficha 
            LEFT JOIN fema_procesos fp ON ftp.id_proceso = fp.id 
            LEFT JOIN establecimientos e ON ff.id_establecimiento = e.id
            LEFT JOIN situaciones s ON ff.id_situacion_pnp = s.id
            LEFT JOIN users u ON ff.created_us = u.id";
            $cad .= " WHERE ff.estado = 1 and ft.estado = 1";
           
    if ($fechaDesde != ""):
        $cad .= " AND CAST(fex.created_at AS DATE) >= '$fechaDesde'";
    endif;
    if ($fechaHasta != ""):
        $cad .= " AND CAST(fex.created_at AS DATE) <= '$fechaHasta'";
    endif;
    if ($nroDocumento !=0):
      $cad .= " AND ff.nro_doc_ident = '$nroDocumento'";
    endif;
    if ($id_tipo_examen !=0):
      $cad .= " AND fex.id_tipo_examen = $id_tipo_examen";
    endif;
    if ($id_establecimiento !=0):
      $cad .= " AND ff.id_establecimiento = $id_establecimiento";
    endif;
    $cad .= " ORDER BY ff.id ";
    
    $data = DB::select($cad);
  
    return $data;
}
  public function GetTriaje($id_ficha="",$fechaDesde="", $fechaHasta="") {
    $cad = "SELECT 
            ft.*, 
            CAST(ft.created_at AS date) fecha_reg,
            CAST(ft.created_at AS time) hora_reg,
            u.paterno usu_paterno,
            u.materno usu_materno,
            u.name usu_nombre
            FROM fema_triajes ft 
            INNER JOIN users u ON ft.created_us = u.id";
    $cad .= " WHERE 1=1 and ft.estado=1 ";
    if ($id_ficha != ""):
      $cad .= " AND ft.id_fema_ficha = $id_ficha";
    endif;
    if ($fechaDesde != ""):
        $cad .= " AND CAST(ft.created_at AS DATE) >= '$fechaDesde'";
    endif;
    if ($fechaHasta != ""):
        $cad .= " AND CAST(ft.created_at AS DATE) <= '$fechaHasta'";
    endif;
    $cad .= " ORDER BY ft.created_at, u.paterno, u.materno";
    
    $data = DB::select($cad);

    return $data;
  }

  public function GetExamen($id_ficha=0,$id_examen=0,$fechaDesde="", $fechaHasta="") {
    $cad = "SELECT 
            fe.*, 
            CAST(fe.created_at AS date) fecha_reg_ex,
            CAST(fe.created_at AS time) hora_reg_ex,
            u.paterno usu_paterno_ex,
            u.materno usu_materno_ex,
            u.name usu_nombre_ex
            FROM fema_examenes fe 
            INNER JOIN users u ON fe.created_us = u.id";
    $cad .= " WHERE 1=1 and fe.estado = 1 ";
    if ($id_ficha != 0):
      $cad .= " AND fe.id_fema_ficha = $id_ficha";
    endif;
    if ($id_examen != 0):
      $cad .= " AND fe.id_tipo_examen = $id_examen";
    endif;
    if ($fechaDesde != ""):
        $cad .= " AND CAST(fe.created_at AS DATE) >= '$fechaDesde'";
    endif;
    if ($fechaHasta != ""):
        $cad .= " AND CAST(fe.created_at AS DATE) <= '$fechaHasta'";
    endif;
    $cad .= " ORDER BY fe.created_at, u.paterno, u.materno";
    
    $data = DB::select($cad);

    return $data;
  }

  public function GetAllTriaje1($fechaDesde="", $fechaHasta="", $nroDocumento, $id_establecimiento) {

    $cad = "SELECT 
            ft.*, ff.*, 
            CAST(ft.created_at AS date) fecha_reg,
            CAST(ft.created_at AS time) hora_reg,
            u.paterno usu_paterno,
            u.materno usu_materno,
            u.name usu_nombre,
            e.nombre establecimiento,
            fp.nombre proceso, 
            ftp.nombre tipo_proceso,
            s.descripcion situacion_pnp
            FROM fema_triajes ft 
            INNER JOIN fema_fichas ff ON ff.id = ft.id_fema_ficha 
            INNER JOIN users u ON ft.created_us = u.id
            INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
            INNER JOIN fema_procesos fp ON ftp.id_proceso = fp.id 
            LEFT JOIN situaciones s ON ff.id_situacion_pnp = s.id
            LEFT JOIN establecimientos e ON ff.id_establecimiento = e.id";
    $cad .= " WHERE 1=1 and ft.estado=1 and  ff.estado = 1";
    if ($fechaDesde != ""):
        $cad .= " AND CAST(ft.created_at AS DATE) >= '$fechaDesde'";
    endif;
    if ($fechaHasta != ""):
        $cad .= " AND CAST(ft.created_at AS DATE) <= '$fechaHasta'";
    endif;
    if ($nroDocumento !=""):
        $cad .= " AND ff.nro_doc_ident = '$nroDocumento'";
      endif;
      if ($id_establecimiento !=""):
        $cad .= " AND ff.id_establecimiento = $id_establecimiento";
      endif;
      
    $cad .= " ORDER BY ft.created_at, u.paterno, u.materno";
    
    $data = DB::select($cad);

    return $data;
  }

  public function GetAllTriaje($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento) {
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$nroDocumento;
    $p[3]=$id_establecimiento;
    $p[4]=1000000000;
    $p[5]=1000000000;
    $p[6]=1000000000;
    return $this->readFunctionPostgres('getalltriaje',$p);
  }

  public function GetAllEpidemiologia1($fechaDesde="", $fechaHasta="", $nroDocumento=0, $id_establecimiento=0) {
    $cad = "SELECT 
    ff.*,
    fd.derivado_us,
    u2.paterno as derivador_paterno, 
    u2.materno as derivador_materno,
    u2.name as derivador_nombre,
    CAST(ft.created_at AS date) fecha_reg,
    CAST(ft.created_at AS time) hora_reg,
    u.paterno usu_paterno,
    u.materno usu_materno,
    u.name usu_nombre,
    e.nombre establecimiento,
    fp.nombre proceso, 
    ftp.nombre tipo_proceso,
    s.descripcion situacion_pnp,
    d.nombre _diagnostico,
    d.codigo codigo,
    m.nombre m_nombre,
    m.paterno m_paterno,
    m.materno m_materno,
    te.nombre consultorioderivador,
    c.nombre _consultorio
    FROM fema_triajes ft 
    INNER JOIN fema_fichas ff ON ff.id = ft.id_fema_ficha 
    INNER JOIN users u ON ft.created_us = u.id
    INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
    INNER JOIN fema_procesos fp ON ftp.id_proceso = fp.id 
    left JOIN situaciones s ON ff.id_situacion_pnp = s.id
    left JOIN establecimientos e ON ff.id_establecimiento = e.id
    INNER JOIN fema_examenes fe on fe.id_fema_ficha  = ff.id 
    INNER JOIN fema_diagnosticos fd on fd.id_fema_examen = fe.id
    INNER JOIN diagnosticos d on d.id = fd.id_diagnostico
    left JOIN medicos m ON fe.id_profesional = m.id 
    left JOIN tipo_examenes te ON fe.id_tipo_examen = te.id
    left join users u2 on fd.derivado_us = u2.id 
    left join consultorios c on c.id  = fd.id_servicio_derivacion ";
            
    $cad .= " WHERE 1=1 AND  ff.estado = 1  AND fe.estado = 1 AND d.estado_fema  = '1' ";
    if ($fechaDesde != ""):
        $cad .= " AND CAST(ft.created_at AS DATE) >= '$fechaDesde'";
    endif;
    if ($fechaHasta != ""):
        $cad .= " AND CAST(ft.created_at AS DATE) <= '$fechaHasta'";
    endif;
    if ($nroDocumento !=0):
        $cad .= " AND ff.nro_doc_ident = '$nroDocumento'";
      endif;
      if ($id_establecimiento !=0):
        $cad .= " AND ff.id_establecimiento = $id_establecimiento";
      endif;
      
    $cad .= " ORDER BY ft.created_at, u.paterno, u.materno";
    
    $data = DB::select($cad);

    return $data;
  }

  public function getallepidemiologia($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento) {

    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$nroDocumento;
    $p[3]=$id_establecimiento;
    $p[4]=1000000000;
    $p[5]=1000000000;
    $p[6]=1000000000;
    return $this->readFunctionPostgres('getallepidemiologia',$p);
  }

  public function getalllaboratorio1($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento) {

    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$nroDocumento;
    $p[3]=$id_establecimiento;
    $p[4]=1000000000;
    $p[5]=1000000000;
    $p[6]=1000000000;
    return $this->readFunctionPostgres('getalllaboratorio1',$p);
  }

  public function getalllaboratorio2($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento) {

    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$nroDocumento;
    $p[3]=$id_establecimiento;
    $p[4]=1000000000;
    $p[5]=1000000000;
    $p[6]=1000000000;
    return $this->readFunctionPostgres('getalllaboratorio2',$p);
  }

  public function GetAllCierreFema1($fechaDesde="", $fechaHasta="", $nroDocumento=0, $id_establecimiento=0) {
    $cad = "SELECT 
          ff.sexo as sexo_asegurado,
            CAST(ff.created_at AS date) fecha_reg,
            CAST(ff.created_at AS time) hora_reg,
            e.nombre establecimiento,
            fp.nombre proceso, 
            ftp.nombre tipo_proceso,
            s.descripcion situacion_pnp,
            ff.cierre_updated_at as cierre, 
            CASE
        WHEN ff.cierre_updated_at IS NOT NULL THEN 'CERRADA'
        WHEN ff.sexo = 'M' AND ((CASE 
                                    WHEN fe1.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe2.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe3.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe4.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe5.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe6.created_at IS NOT NULL THEN 1 ELSE 0 END)) = 5 
        THEN 'CONCLUIDA'
        WHEN ff.sexo = 'F' AND ((CASE 
                                    WHEN fe1.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe2.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe3.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe4.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe5.created_at IS NOT NULL THEN 1 ELSE 0 END) +
                                (CASE WHEN fe6.created_at IS NOT NULL THEN 1 ELSE 0 END)) = 6 
        THEN 'CONCLUIDA'
        ELSE 'NO CONCLUIDA'
    END AS estado_ficha,
         ff.*
            FROM  fema_fichas ff
            INNER JOIN fema_tipo_procesos ftp ON ff.id_fema_tipo_proceso = ftp.id
            INNER JOIN fema_procesos fp ON ftp.id_proceso = fp.id 
            INNER JOIN situaciones s ON ff.id_situacion_pnp = s.id
            INNER JOIN establecimientos e ON ff.id_establecimiento = e.id
            LEFT JOIN fema_examenes  fe1 on ff.id = fe1.id_fema_ficha and fe1.id_tipo_examen = '1' and fe1.estado = '1'
            LEFT JOIN fema_examenes  fe2 on ff.id = fe2.id_fema_ficha and fe2.id_tipo_examen = '3' and fe1.estado = '1'
            LEFT JOIN fema_examenes  fe3 on ff.id = fe3.id_fema_ficha and fe3.id_tipo_examen = '4' and fe1.estado = '1'
            LEFT JOIN fema_examenes  fe4 on ff.id = fe4.id_fema_ficha and fe4.id_tipo_examen = '8' and fe1.estado = '1'
            LEFT JOIN fema_examenes  fe5 on ff.id = fe5.id_fema_ficha and fe5.id_tipo_examen = '9' and fe1.estado = '1'
            LEFT JOIN fema_examenes  fe6 on ff.id = fe6.id_fema_ficha and fe6.id_tipo_examen = '6' and fe1.estado = '1'
            ";
            
    $cad .= " WHERE 1=1 AND  ff.estado = 1  ";
    if ($fechaDesde != ""):
        $cad .= " AND CAST(ff.created_at AS date) >= '$fechaDesde'";
    endif;
    if ($fechaHasta != ""):
        $cad .= " AND CAST(ff.created_at AS date) <= '$fechaHasta'";
    endif;
    if ($nroDocumento !=0):
        $cad .= " AND ff.nro_doc_ident = '$nroDocumento'";
      endif;
      if ($id_establecimiento !=0):
        $cad .= " AND ff.id_establecimiento = $id_establecimiento";
      endif;
      
    $cad .= " ORDER BY ff.created_at";
    
    $data = DB::select($cad);

    return $data;
  }
  
  public function GetAllCierreFema($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento) {

      $p = array();
      $p[0]=$fechaDesde;
      $p[1]=$fechaHasta;
      $p[2]=$nroDocumento;
      $p[3]=$id_establecimiento;
      $p[4]=1000000000;
      $p[5]=1000000000;
      $p[6]=1000000000; 
      return $this->readFunctionPostgres('GetAllCierreFema',$p);

  }

  public function getDiagnosticoByGeneroExamen($buscar, $genero, $examen) {
    
    $cad = "select *
            from diagnosticos
            where codigo||nombre ilike '%".$buscar."%' and length(codigo) >4 and estado=1 and ( genero=".$genero." or genero=3 )  
            and  (
CASE
      WHEN $examen IN (3, 5, 6, 7, 8, 9) THEN codigo IN (
        'Z10.0', 'E66.9', 'E65.X', 'R63.4', 'B49.X', 'B02.9', 'I83.0', 'I84.9', 'L81.8', 'A16.9',
        'R03.0', 'I45.9', 'I25.9', 'I10.X', 'I49.9', 'R00.0', 'H52.7', 'H11.0', 'H10.5', 'S05.9',
        'H26.9', 'H40.0', 'H35.9', 'H91.9', 'H72.9', 'J35.1', 'J30.4', 'J34.2', 'H66.9', 'J33.8',
        'N63.X', 'R87.6', 'D06.9')
      WHEN $examen = 1 THEN codigo IN (
        'Z10.0', 'K02.0', 'K02.1', 'K02.2', 'K05.0', 'K05.1', 'K05.2', 'K05.3', 'K08.3', 'B37.0')
      WHEN $examen = 2 THEN codigo IN ('Z10.0', 'A53.0', 'R73.9', 'E78.5', 'R75.X', 'R82.5', 'R97.2')
      WHEN $examen = 4 THEN codigo LIKE '%F%' OR codigo LIKE '%Z%'  
      ELSE TRUE
                END
              )
            order By nombre asc
            limit 50 ";
    
    $data = DB::select($cad);
    return $data;       
}
}
