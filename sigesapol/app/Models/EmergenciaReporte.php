<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmergenciaReporte extends Model
{
    protected $connection = 'pgsql_reportes';
    protected $table = 'emergencias';

    public function ListarTriajesAtendidosRepo(
        $id_establecimiento,
        $fechaInicio,
        $fechaFinal,
        $dniPaciente,
        $nombrePaciente,
        $estado,
        $ubicacion,
        $id_prioridad,
        $id_topico,
        $id_motivo_ingreso,
        $id_tipo_violencia,
        $edad_desde,
        $edad_hasta,
        $tipo_consulta,
        $NumeroPagina,
        $NumeroRegistros
    ) {

        $fechaInicio = $fechaInicio != "" ? $fechaInicio : "";
        $fechaFinal = $fechaFinal != "" ? $fechaFinal : "";
        $dniPaciente = $dniPaciente != "" ? strtoupper($dniPaciente) : "";
        $nombrePaciente = $nombrePaciente != "" ? strtoupper($nombrePaciente) : "";

        $where = " WHERE ";
        if ($fechaInicio != "" && $fechaFinal != "") $where .= " CAST(em.created_at AS DATE) BETWEEN '" . $fechaInicio . "' AND '" . $fechaFinal . "' AND ";
        if ($nombrePaciente != "")                   $where .= " CONCAT(ase.nro_doc_ident,' ',ase.paterno,' ',ase.materno,' ',ase.nombre) LIKE '%" . $nombrePaciente . "%' AND ";
        if ($edad_desde != "" && $edad_hasta != "")  $where .= " 
      DATE_PART('year', CURRENT_DATE) - DATE_PART('year', TO_DATE(ase.fecha_nac, 'DD-MM-YY')) >= '" . $edad_desde . "' AND 
      DATE_PART('year', CURRENT_DATE) - DATE_PART('year', TO_DATE(ase.fecha_nac, 'DD-MM-YY')) <= '" . $edad_hasta . "' AND ";

        $where .= " em.id_establecimiento = " . $id_establecimiento;

        if ($estado == '0') {
            $where .= " AND em.estado =  " . $estado;
        } else if ($estado == '1') {
            $where .= " AND em.estado >=1  ";
        }

        if ($ubicacion == 1) { //! TRIAJE = 1
            $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NULL 
                    AND em.fecha_atencion IS NULL 
                    AND em.fecha_alta_medica IS NULL
                    AND em.fecha_egreso_administrativa IS NULL ";
        } else if ($ubicacion == 2) { //! ADMISIÓN = 2
            $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NULL 
                    AND em.fecha_alta_medica IS NULL
                    AND em.fecha_egreso_administrativa IS NULL ";
        } else if ($ubicacion == 3) { //!  ATENSIÓN MÉDICA = 3
            $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NOT NULL 
                    AND em.fecha_alta_medica IS NULL
                    AND em.fecha_egreso_administrativa IS NULL ";
        } else if ($ubicacion == 4) { //!EVALUACIÓN= 4
            $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NOT NULL 
                    AND em.fecha_alta_medica IS NULL
                    AND em.fecha_egreso_administrativa IS NULL ";
        } else if ($ubicacion == 5) { //!   ALTA MÉDICA = 5
            $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NOT NULL 
                    AND em.fecha_alta_medica IS NOT NULL
                    AND em.fecha_egreso_administrativa IS NULL ";
        } else if ($ubicacion == 6) { //!  ALTA ADMINISTRATIVA = 6
            $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NOT NULL 
                    AND em.fecha_alta_medica IS NOT NULL
                    AND em.fecha_egreso_administrativa IS NOT NULL ";
        }

        if ($id_prioridad != '') {
            $where .= " AND em.id_prioridad = " . $id_prioridad;
        }

        if ($id_topico != '') {
            $where .= " AND em.id_triaje = " . $id_topico;
        }

        if ($id_motivo_ingreso != '') {
            $where .= " AND em.id_motivo_ingreso = " . $id_motivo_ingreso;
        }

        if ($id_tipo_violencia != '') {
            //if($tipo_consulta=='exportar'){ 
            $where .= " AND tv.id_violencia = " . $id_tipo_violencia;
            //}  
        }



        $cad = "WITH ultimas_evaluaciones AS (
                    SELECT DISTINCT ON (ev.id_emergencia)
                        ev.id_emergencia,
                        ev.id_medico,
                        ev.fecha_atencion
                    FROM evaluaciones ev
                        JOIN prestaciones pr ON ev.id_prestacion = pr.id
                    WHERE ev.id_tipo_atencion = 2
                        and pr.id_establecimiento = " . $id_establecimiento . "
                    ORDER BY ev.id_emergencia, ev.id DESC
                )
                SELECT ROW_NUMBER() OVER(ORDER BY em.created_at DESC) AS num,
                em.id as cod_emergencia, pri.descripcion as prioridad,  tr.descripcion as topico, to_char(em.created_at::date,'DD/MM/YYYY') AS fecha_triaje, 
                to_char(em.created_at, 'HH:MI:SS AM')  AS hora_triaje, tb.descripcion as parentesco, ase.sexo as sexo_asegurado, em.cod_siteds_susalud,
                date_part('year',age(to_date(to_char(ase.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) as edad_asegurado, em.dni_conducido, em.id_condi_egres_med,
                ase.nro_doc_ident, ase.paterno, ase.materno, ase.nombre, ase.tipo_doc_ident, ase.fecha_nac, em.observacion_triaje, em.nombre_conducido,
                med.paterno as apepaterno_profesional, med.materno as apematerno_profesional, med.name as nombre_profesional, em.estado, em.secuencia, 
                em.flag_triaje, em.ruta_triaje, em.fecha_firma_triaje, em.secuencia, ase.telefono, ase.telefono2, ase.domicilio, em.estado,
                to_char(em.totem_at::date,'DD/MM/YYYY') AS fecha_totem,  ase.tipo_doc_ident as tipo_doc_ident,
                to_char(em.totem_at::time, 'HH:MI:SS AM')  AS hora_totem, em.observacion_admision,
                est.nombre as establecimiento, for_ing.descripcion as forma_ingreso, mot_ing.descripcion as motivo_ingreso, e_ing.descripcion as estado_ingreso,v.descripcion as violencia,
                em.id_tipo_egres_med, em.cod_totem ,
                to_char(em.fecha_registro_admision::date,'DD/MM/YYYY') AS fecha_admision ,
                to_char(em.fecha_registro_admision::time,'HH:MI:SS AM') AS hora_admision,
                user_adm.paterno as apepaterno_user_adm, user_adm.materno as apematerno_user_adm, user_adm.name as nombre_user_adm , 
                to_char(em.a_llamado_at::date,'DD/MM/YYYY') AS fecha_llamado, 
                to_char(em.a_llamado_at, 'HH:MI:SS AM')  AS hora_llamado ,
                user_llama.paterno as apematerno_llamado, user_llama.materno as apepaterno_llamado, user_llama.name as nombre_llamado, 

                to_char(em.fecha_alta_medica::date,'DD/MM/YYYY') AS fecha_alta_medica , 
                TO_CHAR(em.hora_alta_medica::time,'HH:MI:SS AM') AS hora_alta_medica_alterna,
                TO_CHAR(em.fecha_alta_medica::time,'HH:MI:SS AM') AS hora_alta_medica,
                consul.nombre as servicio_destino,

                user_alta.paterno as apepaterno_user_alta, 
                user_alta.materno as apematerno_user_alta, 
                user_alta.name as nombre_user_alta, 
                
                user_atencion.paterno as apepaterno_user_atencion, 
                user_atencion.materno as apematerno_user_atencion, 
                user_atencion.name as nombre_user_atencion, 
                
                case 
                	when em.id_tipo_egres_med = '1' then 'ALTA'
                	when em.id_tipo_egres_med = '2' then 'HOSPITALIZACIÓN'
                	when em.id_tipo_egres_med = '3' then 'TRASLADO'
                	when em.id_tipo_egres_med = '4' then 'FALLECIDO'
                  when em.id_tipo_egres_med = '6' then 'FUGA'
                	when em.id_tipo_egres_med = '7' then 'RETIRO VOLUNTARIO'
                end as tipo_egreso_medico,
                
                to_char(em.fecha_egreso_administrativa::date,'DD/MM/YYYY') AS fecha_egreso_administrativa , 
                TO_CHAR(em.fecha_egreso_administrativa::time,'HH:MI:SS AM') AS hora_egreso_administrativa,
                TO_CHAR(em.hora_egreso_administrativa::time,'HH:MI:SS AM') AS hora_egreso_administrativa_alterna,

                user_adminis.paterno as apepaterno_user_admins, 
                user_adminis.materno as apematerno_user_admins, 
                user_adminis.name as nombre_user_admins,

                to_char(em.fecha_registro_ingreso::date,'DD/MM/YYYY') AS fecha_registro_ingreso_triaje , 
                to_char(em.hora_registro_ingreso::time,'HH:MI:SS AM') AS hora_registro_ingreso_triaje,   
                to_char(em.fecha_registro_admision::date,'DD/MM/YYYY') AS fecha_registro_admision ,   
                diag_cab.nombre as diagnostico_cab, diag_cab1.nombre as diagnostico_cab1, diag_cab2.nombre as diagnostico_cab2, 
                to_char(em.fecha_atencion::date,'DD/MM/YYYY') AS fecha_atencion , 
                TO_CHAR(em.fecha_atencion::time,'HH:MI:SS AM') AS hora_atencion,

                case
               		when em.fecha_registro_ingreso is null then ''
               		when totem_at is null then ''
               		when TO_CHAR(em.fecha_registro_ingreso::time,'HH24:MI:SS') != '00:00:00'  then 
               		CONCAT(DATE_PART('day',AGE(em.fecha_registro_ingreso,totem_at)),' DÍAS  ',
	                DATE_PART('hour',AGE(em.fecha_registro_ingreso,totem_at)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_registro_ingreso,totem_at)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_registro_ingreso + em.hora_registro_ingreso ::time),totem_at)),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_registro_ingreso + em.hora_registro_ingreso ::time),totem_at)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_registro_ingreso + em.hora_registro_ingreso ::time),totem_at)),' MINUTOS')
               	end as trama_triaje

,

				case
               		when em.fecha_registro_admision is null then ''
               		when em.fecha_registro_ingreso is null  then ''
               		
	                when TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_registro_ingreso::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_registro_admision,em.fecha_registro_ingreso)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_registro_admision,em.fecha_registro_ingreso)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_registro_admision,em.fecha_registro_ingreso)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_registro_ingreso::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_registro_admision,(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_registro_admision,(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_registro_admision,(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_registro_ingreso::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),em.fecha_registro_ingreso)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),em.fecha_registro_ingreso)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),em.fecha_registro_ingreso)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' MINUTOS')
	                
               	end as trama_admision
,

				case
               		when em.fecha_atencion is null then ''
               		when em.fecha_registro_admision is null then ''
               		
	                
	                when TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_atencion,em.fecha_registro_admision)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_atencion,em.fecha_registro_admision)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_atencion,em.fecha_registro_admision)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_atencion,(em.fecha_registro_admision + em.hora_registro_admision ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_atencion,(em.fecha_registro_admision + em.hora_registro_admision ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_atencion,(em.fecha_registro_admision + em.hora_registro_admision ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_atencion + em.hora_atencion ::time),em.fecha_registro_admision)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_atencion + em.hora_atencion ::time),em.fecha_registro_admision)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_atencion + em.hora_atencion ::time),em.fecha_registro_admision)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_atencion + em.hora_atencion ::time),(em.fecha_registro_admision + em.hora_registro_admision ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_atencion + em.hora_atencion ::time),(em.fecha_registro_admision + em.hora_registro_admision ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_atencion + em.hora_atencion ::time),(em.fecha_registro_admision + em.hora_registro_admision ::time))),' MINUTOS')
	           end as trama_atencion

,
                COALESCE(m_ev.paterno || ' ' || m_ev.materno || ', ' || m_ev.nombre,'')::VARCHAR AS ultimo_personal_evaluacion,
                COALESCE(to_char(eva.fecha_atencion,'DD/MM/YYYY'), '') AS ultima_fecha_atencion_evaluacion, 
                COALESCE(TO_CHAR(eva.fecha_atencion,'HH:MI:SS AM'), '') AS ultima_hora_atencion_evaluacion,
                case
               		when eva.fecha_atencion is null then ''
               		when em.fecha_atencion is null then ''
	                else 
               		CONCAT(DATE_PART('day',AGE(eva.fecha_atencion, em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE(eva.fecha_atencion, em.fecha_atencion)),' HORAS ', 
	                DATE_PART('minute',AGE(eva.fecha_atencion, em.fecha_atencion)),' MINUTOS')
	            end as trama_ultima_atencion_evaluacion,

				case
               		when em.fecha_alta_medica is null then ''
               		when em.fecha_atencion is null  then ''
               		
	                when TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_alta_medica,em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_alta_medica,em.fecha_atencion)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_alta_medica,em.fecha_atencion)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_alta_medica,(em.fecha_atencion + em.hora_atencion ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_alta_medica,(em.fecha_atencion + em.hora_atencion ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_alta_medica,(em.fecha_atencion + em.hora_atencion ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),em.fecha_atencion)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),em.fecha_atencion)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),(em.fecha_atencion + em.hora_atencion ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),(em.fecha_atencion + em.hora_atencion ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),(em.fecha_atencion + em.hora_atencion ::time))),' MINUTOS')
	                
	                
	                
               	end as trama_alta_medica
,

				case
               		when em.fecha_egreso_administrativa is null then ''
               		when em.fecha_alta_medica is null  then ''
               		when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_egreso_administrativa,em.fecha_alta_medica)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_egreso_administrativa,em.fecha_alta_medica)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_egreso_administrativa,em.fecha_alta_medica)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_egreso_administrativa,(em.fecha_alta_medica + em.hora_alta_medica ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_egreso_administrativa,(em.fecha_alta_medica + em.hora_alta_medica ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_egreso_administrativa,(em.fecha_alta_medica + em.hora_alta_medica ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_alta_medica)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_alta_medica)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_alta_medica)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_alta_medica + em.hora_alta_medica ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_alta_medica + em.hora_alta_medica ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_alta_medica + em.hora_alta_medica ::time))),' MINUTOS')
               	end as trama_alta_administrativa
,

				case
               		when em.fecha_egreso_administrativa is null then ''
               		when em.fecha_atencion is null  then ''
               		
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_egreso_administrativa,em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_egreso_administrativa,em.fecha_atencion)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_egreso_administrativa,em.fecha_atencion)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_egreso_administrativa,(em.fecha_atencion + em.hora_atencion ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_egreso_administrativa,(em.fecha_atencion + em.hora_atencion ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_egreso_administrativa,(em.fecha_atencion + em.hora_atencion ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_atencion)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_atencion)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_atencion + em.hora_atencion ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_atencion + em.hora_atencion ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_atencion + em.hora_atencion ::time))),' MINUTOS')
	            end as trama_alta_administrativa_sin_medica,

                u_triaje_anu.id as id_user_anula_triaje,
	              concat(u_triaje_anu.paterno,' ',u_triaje_anu.materno,' ',u_triaje_anu.name) as user_anula_triaje,
                concat(u_admision_anu.paterno,' ',u_admision_anu.materno,' ',u_admision_anu.name) as user_anula_admision,
                concat(u_atencion_anu.paterno,' ',u_atencion_anu.materno,' ',u_atencion_anu.name) as user_anula_atencion,
                concat(u_alta_anul.paterno,' ',u_alta_anul.materno,' ',u_alta_anul.name) as user_anula_alta,
                case 
                  when em.id_usuario_anulacion is not null and (em.motivo_anulacion = '' or em.motivo_anulacion is null) then 'EL USUARIO NO HA CONSIGADO MOTIVO DE ANULACION EN TRIAJE'
                  else em.motivo_anulacion
                end as motivo_anulacion_triaje,
                case 
                  when em.id_usuario_anulacion_admision is not null and (em.motivo_anulacion_admision = '' or em.motivo_anulacion_admision is null) then 'EL USUARIO NO HA CONSIGADO MOTIVO DE ANULACION EN ADMISION'
                  else em.motivo_anulacion_admision
                end as motivo_anulacion_admisión,
                case 
                  when em.id_delete_atencion is not null and (em.observacion_elimina_paciente = '' or em.observacion_elimina_paciente is null) then 'EL USUARIO NO HA CONSIGADO MOTIVO DE ANULACION EN ATENCIÓN'
                  else em.observacion_elimina_paciente
                end as motivo_anulacion_atencion,
                em.observacion_elimina_paciente,
                case 
                  when em.id_anula_alta is not null and (em.motivo_anula_alta = '' or em.motivo_anula_alta is null) then 'EL USUARIO NO HA CONSIGADO MOTIVO DE ANULACION EN ALTA'
                  else em.motivo_anula_alta
                end as motivo_anulacion_alta


                ";

        if ($tipo_consulta == 'exportar') {
            $cad .= "
                  ,p.id_ext as id_emergencia,
                  p.id as id_prestacion,
                  to_char(p.created_at::date,'DD/MM/YYYY') as fecha_procedimiento , 
                  to_char(p.created_at, 'HH:MI:SS AM')  AS hora_procedimiento,
                  concat(pr.codigo,' - ',pr.descripcion)  as procedimiento, 
                  concat(d.codigo,' - ',d.nombre)  as diagnostico, 
                  concat(m.paterno,' ',m.materno,' ',m.nombre) as medico_responsable,
                  c2.comisaria  ,
                  ev.lugar_accidente ,
                  v.descripcion as violencia,
                  tv.descripcion as tipo_violencia
                   ";
        }

        $cad .= "  
                FROM emergencias em 
                INNER JOIN asegurados ase ON em.id_asegurado = ase.id
                LEFT JOIN tipo_beneficiarios tb ON ase.id_tipo_beneficiario = tb.id
                LEFT JOIN users med ON em.id_medico_triaje = med.id
                LEFT JOIN triajes tr ON em.id_triaje = tr.id
                LEFT JOIN prioridades pri ON tr.prioridad = pri.id 
                LEFT JOIN establecimientos est ON em.id_establecimiento = est.id 
                LEFT JOIN forma_ingresos for_ing ON em.id_forma_ingreso = for_ing.id   
                LEFT JOIN estado_ingresos e_ing ON em.id_estado_ingreso = e_ing.id
                LEFT JOIN categorias mot_ing ON em.id_motivo_ingreso = mot_ing.id
                LEFT JOIN users user_adm ON em.id_usuario_admision = user_adm.id
                LEFT JOIN users user_llama ON em.a_llamado_us = user_llama.id
                LEFT JOIN consultorios consul ON em.id_medico_egreso = consul.id
                LEFT JOIN users user_alta ON em.id_medico_egreso = user_alta.id
                LEFT JOIN users user_adminis ON em.id_user_egreso_administrativo = user_adminis.id
                
                LEFT JOIN diagnosticos diag_cab ON em.id_diag_cab = diag_cab.id  
                LEFT JOIN diagnosticos diag_cab1 ON em.id_diag_cuer1 = diag_cab1.id  
                LEFT JOIN diagnosticos diag_cab2 ON em.id_diag_cuer2 = diag_cab2.id 
                 
                LEFT JOIN users user_atencion ON em.id_user_atencion = user_atencion.id 
                
                ---nuevo 24/08
                LEFT join users u_triaje_anu on u_triaje_anu.id = em.id_usuario_anulacion
                LEFT join users u_admision_anu on u_admision_anu.id = em.id_usuario_anulacion_admision
                LEFT join users u_atencion_anu on u_atencion_anu.id = em.id_delete_atencion
                LEFT join users u_alta_anul on u_alta_anul.id = cast(em.id_anula_alta as integer)
                ---fin nuevo 24/08
                LEFT JOIN emergencia_violencias ev on em.id = ev.id_emergencia
                LEFT JOIN violencia v on ev.id_violencia = v.id
                LEFT JOIN tipo_violencia tv on ev.id_modalidad=tv.id_tipo_violencia     
                LEFT JOIN ultimas_evaluaciones eva ON eva.id_emergencia = em.id
				LEFT JOIN medicos m_ev ON eva.id_medico = m_ev.id AND eva.id_emergencia IS NOT NULL            
                ";

        if ($tipo_consulta == 'exportar') {
            $cad .= "  
                  
                  LEFT JOIN comisarias c2 on c2.id = ev.id_comisaria 
                  LEFT JOIN prestaciones p on em.id = p.id_ext
                  LEFT JOIN medicos m on p.id_medico = m.id
                  LEFT JOIN prestacion_procedimientos pp on p.id = pp.id_prestacion 
                  LEFT JOIN procedimientos pr on pp.id_procedimiento = pr.id 
                  LEFT JOIN receta_diagnosticos rd on p.id = rd.id_prestacion 
                  LEFT JOIN diagnosticos d on rd.id_diagnostico = d.id  
                ";
        }

        //$tipo_consulta : exportar & consultar

        $cad .= $where;
        if ($tipo_consulta == 'exportar') {
            $cad .= " ORDER BY p.created_at";
        } else if ($tipo_consulta == 'consultar') {
            $cad_total =  $cad;
            $cad .= " ORDER BY em.created_at ASC LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . ";";
        }

        $data = DB::connection('pgsql_reportes')->SELECT($cad);
        if ($tipo_consulta == 'consultar') {
            $totalrows = count(DB::connection('pgsql_reportes')->SELECT($cad_total));
            return array('data' => $data, 'totalrows' => $totalrows);
        } else {
            return array('data' => $data);
        }
    }

    public function GetReporteEmergencia1($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia1', $p);
    }
    public function GetReporteEmergencia2($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia2', $p);
    }


    public function GetReporteEmergencia3g($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3g', $p);
    }

    public function GetReporteEmergencia3t($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3t', $p);
    }

    public function GetReporteEmergencia3c($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3c', $p);
    }

    public function GetReporteEmergencia3a($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3a', $p);
    }

    public function GetReporteEmergencia3o($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3o', $p);
    }
    public function GetReporteEmergencia3m($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3m', $p);
    }
    public function GetReporteEmergencia3p($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3p', $p);
    }
    public function GetReporteEmergencia3ha($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3ha', $p);
    }
    public function GetReporteEmergencia3ho($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia3ho', $p);
    }

    public function GetReporteEmergencia4g($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4g', $p);
    }

    public function GetReporteEmergencia4t($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4t', $p);
    }

    public function GetReporteEmergencia4c($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4c', $p);
    }

    public function GetReporteEmergencia4a($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4a', $p);
    }

    public function GetReporteEmergencia4o($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4o', $p);
    }
    public function GetReporteEmergencia4m($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4m', $p);
    }
    public function GetReporteEmergencia4p($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4p', $p);
    }
    public function GetReporteEmergencia4ha($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4ha', $p);
    }
    public function GetReporteEmergencia4ho($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia4ho', $p);
    }

    public function GetReporteEmergencia5($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia5', $p);
    }

    public function GetReporteEmergencia6($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia6', $p);
    }

    public function GetReporteEmergencia7($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia7', $p);
    }
    public function GetReporteEmergencia8($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia8', $p);
    }
    public function GetReporteEmergencia9($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia9', $p);
    }
    public function GetReporteEmergencia10($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia10', $p);
    }
    public function GetReporteEmergencia11($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia11', $p);
    }

    public function GetReporteEmergencia12($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia12', $p);
    }
    public function GetReporteEmergencia13($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia13', $p);
    }
    public function GetReporteEmergencia14($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia14', $p);
    }
    public function GetReporteEmergencia15($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia15', $p);
    }
    public function GetReporteEmergencia16($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia16', $p);
    }
    public function GetReporteEmergencia17($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia17', $p);
    }
    public function GetReporteEmergencia18($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia18', $p);
    }

    public function enviartrama($id_emergencia)
    {
        $data = DB::select("SELECT sp_insertar_tramas_emergencia_2 ($id_emergencia)");
    }

    public function GetReporteEmergencia19($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia19', $p);
    }
    public function GetReporteEmergencia20($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;

        return $this->readFunctionPostgres('GetReporteEmergencia20', $p);
    }

    public function GetReporteEmergencia21($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;
        return $this->readFunctionPostgres('GetReporteEmergencia21', $p);
    }

    public function GetReporteEmergencia22($fechaDesde, $fechaHasta, $id_establecimiento)
    {
        $p = array();
        $p[0] = $fechaDesde;
        $p[1] = $fechaHasta;
        $p[2] = $id_establecimiento;
        $p[3] = 1000000000;
        $p[4] = 1000000000;
        $p[5] = 1000000000;
        return $this->readFunctionPostgres('GetReporteEmergencia22', $p);
    }

    public function readFunctionPostgres2($function, $parameters = null)
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

    public function readFunctionPostgres($function, $parameters = null)
    {
        $_parameters = '';
        if (!empty($parameters) && count($parameters) > 0) {
            $_parameters = implode("','", $parameters);
            $_parameters = "'" . $_parameters . "',";
        }

        // Usar la conexión alterna
        $db = DB::connection('pgsql_reportes');

        // Iniciar la transacción en PostgreSQL
        $db->select("BEGIN;");

        // Ejecutar la función almacenada
        $cad = "SELECT " . $function . "(" . $_parameters . "'ref_cursor');";
        $db->select($cad);

        // Obtener los resultados del cursor
        $cad = "FETCH ALL IN ref_cursor;";
        $data = $db->select($cad);

        return $data;
    }
}
