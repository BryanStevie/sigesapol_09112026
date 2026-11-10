--DROP FUNCTION IF EXISTS public.sp_listar_procprogramados_paginado(int4, varchar, varchar, varchar, varchar, int4, int4, int4, int4);
CREATE OR REPLACE FUNCTION public.sp_listar_procprogramados_paginado(p_id_establecimiento integer, p_fecha_ini character varying, p_fecha_fin character varying, p_dni_beneficiario character varying, p_nombre_beneficiario character varying, p_id_servicio integer, p_estado integer, p_limit integer, p_offset integer, p_servicios integer[] DEFAULT NULL)
 RETURNS TABLE(id_solicitud integer, fecha_solicitud text, flag_firma integer, nro_doc_ident text, paciente text, fecha_nacimiento text, id_procedimiento integer, codigo_procedimiento text, procedimiento text, cantidad bigint, pendientes bigint, citados bigint, atendidos bigint, en_atencion integer, id_pp integer, diagnostico text, indicacion text, medico text, servicio text, fecha_cita text, consultorio text, fecha_atencion text, responsable_atencion text, fecha_anulacion text, motivo_anulacion text, responsable_anulacion text, estado integer, tipo_atencion text, condicion text, totalrows bigint)
 LANGUAGE plpgsql
AS $function$
BEGIN
  RETURN QUERY
  SELECT t.*, count(*) OVER()::bigint AS totalrows
  FROM (
    SELECT
      s.id::integer                                                     AS id_solicitud,
      to_char(s.fecha_solicitud, 'DD/MM/YYYY HH24:MI')::text            AS fecha_solicitud,
      s.flag::integer                                                   AS flag_firma,
      a.nro_doc_ident::text                                             AS nro_doc_ident,
      (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text         AS paciente,
      a.fecha_nac::text                                                 AS fecha_nacimiento,
      pr.id::integer                                                    AS id_procedimiento,
      pr.codigo::text                                                   AS codigo_procedimiento,
      pr.descripcion::text                                              AS procedimiento,
      count(d.id)::bigint                                               AS cantidad,
      sum(CASE WHEN d.estado = 1 THEN 1 ELSE 0 END)::bigint             AS pendientes,
      sum(CASE WHEN d.estado = 2 THEN 1 ELSE 0 END)::bigint             AS citados,
      sum(CASE WHEN d.estado = 3 THEN 1 ELSE 0 END)::bigint             AS atendidos,
      (CASE WHEN sum(CASE WHEN d.estado = 6 THEN 1 ELSE 0 END) > 0 THEN 1 ELSE 0 END)::integer AS en_atencion,
      min(CASE WHEN d.estado = 1 THEN d.id END)::integer                AS id_pp,
      max(dg.codigo || ' - ' || dg.nombre)::text                        AS diagnostico,
      max(d.indicacion)::text                                           AS indicacion,
      (m.paterno || ' ' || m.materno || ', ' || m.nombre)::text         AS medico,
      c.nombre::text                                                    AS servicio,
      to_char(min(CASE WHEN d.estado = 2 THEN ci.fecha_hora END), 'DD/MM/YYYY HH12:MI AM')::text AS fecha_cita,
      (array_agg(sci.descripcion ORDER BY CASE WHEN d.estado = 2 AND ci.fecha_hora IS NOT NULL THEN 0 ELSE 1 END, ci.fecha_hora))[1]::text AS consultorio,
      to_char(max(CASE WHEN d.estado IN (3, 6) THEN d.fecha_atencion END), 'DD/MM/YYYY - HH12:MI:SS AM')::text AS fecha_atencion,
      (array_agg( ma.paterno || ' ' || ma.materno || ', ' || ma.nombre ORDER BY CASE WHEN d.estado IN (3, 6) AND ma.id IS NOT NULL THEN 0 ELSE 1 END, d.fecha_atencion DESC))[1]::text AS responsable_atencion,
      to_char(max(CASE WHEN d.estado IN (4, 5) THEN d.fecha_anulacion END), 'DD/MM/YYYY  HH24:MI:SS')::text AS fecha_anulacion,
      max(CASE WHEN d.estado IN (4, 5) THEN d.motivo_anulacion END)::text AS motivo_anulacion,
      (array_agg(ua.paterno || ' ' || ua.materno || ', ' || ua.name ORDER BY CASE WHEN d.estado IN (4, 5) AND ua.id IS NOT NULL THEN 0 ELSE 1 END, d.fecha_anulacion DESC))[1]::text AS responsable_anulacion,
      (CASE
        WHEN sum(CASE WHEN d.estado IN (1, 2, 6) THEN 1 ELSE 0 END) > 0 THEN 1
        WHEN sum(CASE WHEN d.estado = 3 THEN 1 ELSE 0 END) > 0 THEN 3
        WHEN sum(CASE WHEN d.estado = 5 THEN 1 ELSE 0 END) > 0 THEN 5
        WHEN sum(CASE WHEN d.estado = 4 THEN 1 ELSE 0 END) > 0 THEN 4
        ELSE 3
      END)::integer                                                     AS estado,
      ta.nombre::text                                                   AS tipo_atencion,
      (CASE WHEN ci.referido = 'NO' THEN 'NO REFERIDO' ELSE 'REFERIDO' END)::text AS condicion
    FROM solicitud_programacion_procedimientos s
    INNER JOIN solicitud_procedimientos_detalle d ON d.id_solicitud = s.id
    INNER JOIN procedimientos pr ON pr.id = d.id_procedimiento
    LEFT JOIN citas ci
    ON ci.id = (
        SELECT cit.id
        FROM citas cit
        WHERE cit.id_solicitud_procedimiento_detalle = d.id
          AND cit.flag_pp = 1
          AND cit.id_estado_reg = 1
        ORDER BY cit.fecha_hora DESC, cit.id DESC
        LIMIT 1
    )
    LEFT JOIN sub_consultorios sci ON sci.id = ci.id_sub_consultorio
    LEFT JOIN medicos ma ON ma.id = d.id_medico_atencion
    LEFT JOIN users ua ON ua.id = d.anulado_us
    INNER JOIN prestaciones p ON p.id = s.id_prestacion
    INNER JOIN asegurados a ON a.id = p.id_asegurado
    LEFT JOIN diagnosticos dg ON dg.id = d.id_diagnostico
    LEFT JOIN medicos m ON m.id = p.id_medico
    LEFT JOIN sub_consultorios sc ON sc.id = p.id_sub_consultorio
    LEFT JOIN consultorios c ON c.id = sc.id_consultorio
    INNER JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
    WHERE p.id_estado_reg = 1
      AND p.id_establecimiento = p_id_establecimiento
      AND (coalesce(p_fecha_ini, '') = '' OR s.fecha_solicitud >= to_date(p_fecha_ini, 'DD-MM-YYYY'))
      AND (coalesce(p_fecha_fin, '') = '' OR s.fecha_solicitud < to_date(p_fecha_fin, 'DD-MM-YYYY') + 1)
      AND (coalesce(p_dni_beneficiario, '') = '' OR a.nro_doc_ident = p_dni_beneficiario)
      AND (coalesce(p_nombre_beneficiario, '') = ''
           OR upper(a.paterno || ' ' || a.materno || ' ' || a.nombre) LIKE '%' || upper(p_nombre_beneficiario) || '%')
      AND (coalesce(p_id_servicio, 0) = 0 OR sc.id_consultorio = p_id_servicio)
      -- Solo los servicios del médico (NULL = sin restricción)
      AND (p_servicios IS NULL OR sc.id_consultorio = ANY(p_servicios))
    GROUP BY s.id, s.fecha_solicitud, s.flag, a.nro_doc_ident, a.paterno, a.materno, a.nombre, a.fecha_nac, ci.referido, ta.nombre,
      pr.id, pr.codigo, pr.descripcion, m.paterno, m.materno, m.nombre, c.nombre
    HAVING max(d.requiere_cita) = 0 OR sum(CASE WHEN d.estado >= 2 THEN 1 ELSE 0 END) > 0
  ) t
  WHERE coalesce(p_estado, 0) = 0 OR t.estado = p_estado
  ORDER BY t.id_solicitud DESC, t.codigo_procedimiento
  LIMIT p_limit OFFSET p_offset;
END;
$function$
;


--AGREGAR COLUMNA PARA FLAG_SUBIR_INFORME
ALTER TABLE procedimientos ADD COLUMN requiere_informe integer;
ALTER TABLE procedimientos ALTER COLUMN requiere_informe SET DEFAULT 0;

--AGREGAR COLUMNAS PARA SUBIR INFORME
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN ruta_informe varchar(255);
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN fecha_informe timestamp;
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN informe_us integer;


