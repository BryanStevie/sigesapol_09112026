ALTER TABLE public.hospitalizaciones ADD COLUMN fecha_hora_cu TIMESTAMP;
ALTER TABLE public.hospitalizaciones ADD COLUMN flag_exa_cu int;
ALTER TABLE public.hospitalizaciones ADD COLUMN flag_hc_cu int;
ALTER TABLE public.hospitalizaciones ADD COLUMN id_user_exa_CU int;
ALTER TABLE public.hospitalizaciones ADD COLUMN id_user_hc_CU int;
ALTER TABLE public.hospitalizaciones add fecha_limite_hc_cu timestamp without time zone;
ALTER TABLE public.hospitalizaciones add fecha_hora_hc_cu timestamp without time zone;
ALTER TABLE public.hospitalizaciones add hc_cu_fuera_plazo bool default false;
ALTER TABLE public.hospitalizacion_atenciones ADD estado_general int2 NULL;
ALTER TABLE public.hospitalizacion_atenciones ADD estado_hidratacion int2 NULL;
ALTER TABLE public.hospitalizacion_atenciones ADD estado_nutricion int2 NULL;
ALTER TABLE public.hospitalizacion_atenciones ADD tiempo_enfermedad_valor int2 NULL;
ALTER TABLE public.hospitalizacion_atenciones ADD tiempo_enfermedad_unidad varchar(10) NULL;



-- SP NUEVO CON VALIDACIONES PARA UCI 2 VISTAS 
-- DROP FUNCTION public.sp_listar_doc_sinfirmar_1(int4, varchar, varchar, varchar, int4, int4, int4, int4);

CREATE OR REPLACE FUNCTION public.sp_listar_doc_sinfirmar_1(p_id_establecimiento integer, p_fecha_ini character varying, p_fecha_fin character varying, p_dni_beneficiario character varying, p_estado integer, p_id_user integer, p_pagina integer, p_limit integer)
 RETURNS TABLE(id_usuario integer, id_prestacion integer, documento_por_firmar text, fecha_atencion text, id_principal integer, tipo_doc_ident text, nro_doc_ident text, id_establecimiento integer, nombre_paciente text, edad text, sexo text, id_tipo_atencion integer, tipo_atencion text, servicio text, examen text, fecha_atencion_prestacion text, usuario_medico text, fecha_firma timestamp without time zone, ruta_firma text, ruta_indicaciones text, ruta_epicrisis text, flag integer, flag_prestacion integer, id_evaluacion integer, tipo_egreso integer, id_servicio_radiologia integer, id_rad integer, id_ira integer, id_interconsulta integer, tiempo_firma text, totalrows integer)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    with ultima_evolucion_emergencia as(select
	max(e2.id) as id_evolucion,
	e.id as id_emergencia
	from emergencias e inner join evaluaciones e2 on e.id = e2.id_emergencia and e2.id_tipo_atencion =2 group by e.id),
	ultima_evolucion_hospitalizacion AS (SELECT
	MAX(e2.id) AS id_evolucion,
	h.id AS id_hospitalizacion
    FROM hospitalizaciones h
    INNER JOIN evaluaciones e2 ON h.id = e2.id_emergencia  AND e2.id_tipo_atencion = 3 GROUP BY h.id),
	docs AS (
        -- 1️ ATENCIÓN
            SELECT
                1 AS orden_tipo,
                u.id AS id_usuario,
                p.id::integer AS id_prestacion,
                'ATENCIÓN'::text AS documento_por_firmar,
                to_char(p.fecha_atencion::date + ci.fecha_hora::time, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_txt,
                p.id::integer AS id_principal,
                a.tipo_doc_ident::text,
                a.nro_doc_ident::text,
                p.id_establecimiento,
                (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text AS nombre_paciente,
                (EXTRACT(YEAR FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' años, ' ||
                 EXTRACT(MONTH FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' meses, ' ||
                 EXTRACT(DAY FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' días')::text AS edad,
                (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text AS sexo,
                p.id_tipo_atencion,
                ta.nombre::text AS tipo_atencion,
                c.nombre::text AS servicio,
                NULL::text AS examen,
                to_char(p.fecha_atencion::date + ci.fecha_hora::time, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_prestacion,
                (u.paterno || ' ' || u.materno || ', ' || u.name)::text AS usuario_medico,
                p.fecha_firma::timestamp AS fecha_firma,
                p.ruta_prestacion::text AS ruta_firma,
				null::text as ruta_indicaciones,
				null::text as ruta_epicrisis,
                p.flag,
				p.flag as flag_prestacion,
				null as id_ev,
				null::integer  as tipo_egreso,
				null::integer as id_servicio_radiologia,
				null::integer as id_rad,
				null::integer as id_ira,
				null::integer as id_interconsulta,
				CASE
				    WHEN p.flag = 0 THEN
				        (
				            SELECT TRIM(BOTH ', ' FROM CONCAT_WS(
				                ', ',
				                CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - ci.fecha_hora))) > 0
				                    THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - ci.fecha_hora)))::text || ' Año(s)' END,
				                CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - ci.fecha_hora))) > 0
				                    THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - ci.fecha_hora)))::text || ' Mes(es)' END,
				                CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - ci.fecha_hora))) > 0
				                    THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - ci.fecha_hora)))::text || ' Día(s)' END,
				                CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - ci.fecha_hora))) > 0
				                    THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - ci.fecha_hora)))::text || ' Hora(s)' END,
				                CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - ci.fecha_hora))) > 0
				                    THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - ci.fecha_hora)))::text || ' Minuto(s)' END
				            ))
				        ) || ' Sin Firmar'
				    ELSE
				        COALESCE(to_char(p.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI'), 'Firmado')
				END AS tiempo_firma
            FROM prestaciones p
            INNER JOIN asegurados a ON a.id = p.id_asegurado
            INNER JOIN tipo_atenciones ta ON ta.id = p.id_tipo_atencion
            LEFT JOIN sub_consultorios sc ON sc.id = p.id_sub_consultorio
            LEFT JOIN consultorios c ON c.id = sc.id_consultorio
            INNER JOIN citas ci ON ci.id = p.id_cita
            INNER JOIN users u ON u.id = p.created_us
            LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
            WHERE
				p.flag in('0','2')
			  AND p.id_estado_reg=1
			  AND (
				      p.id_tipo_atencion = 1
				      OR (
				            p.id_tipo_atencion <> 1
				            AND (p.flag_intervencion_quirurgica = false OR p.flag_intervencion_quirurgica IS NULL)
				         )
				    )
			  AND p.id_establecimiento = p_id_establecimiento
              AND p.fecha_atencion::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
              AND (p_id_user IS NULL OR u.id = p_id_user)
              AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)
			  AND p.id_medico is not null
			  AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)
            UNION
		-- 2 EVOLUCION
		SELECT
	        2 AS orden_tipo,
	        u1.id,
	        p.id::integer AS id_prestacion,
	        'EVOLUCION'::text,
	        to_char(ev.fecha_atencion, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_txt,
	        ev.id::integer AS id_principal,
	        a.tipo_doc_ident::text,
	        a.nro_doc_ident::text,
	        p.id_establecimiento,
	        (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
	        (EXTRACT(YEAR FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' años, ' ||
	         EXTRACT(MONTH FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' meses, ' ||
	         EXTRACT(DAY FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' días')::text,
	        (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
	        ev.id_tipo_atencion,
	        ta.nombre::text,
	        co.nombre::text,
	        null,
	        to_char(ev.fecha_atencion, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_prestacion,
	        (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text,
	        ev.fecha_firma::timestamp,
	        ev.ruta_evaluacion::text,
			null::text as ruta_indicaciones,
			null::text as ruta_epicrisis,
	        ev.flag,
			p.flag as flag_prestacion,
			ev.id::integer as id_ev,
			null::integer  as tipo_egreso,
			null::integer as id_servicio_radiologia,
			null::integer as id_rad,
			null::integer as id_ira,
			null::integer as id_interconsulta,
	        CASE
			    WHEN ev.flag = 0 THEN
			        TRIM(
			            BOTH ', ' FROM
			            CONCAT_WS(
			                ', ',
			                CASE
			                    WHEN EXTRACT(YEAR FROM age(now(), ev.created_at)) > 0
			                        THEN EXTRACT(YEAR FROM age(now(), ev.created_at))::text || ' Año(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(MONTH FROM age(now(), ev.created_at)) > 0
			                        THEN EXTRACT(MONTH FROM age(now(), ev.created_at))::text || ' Mes(es)'
			                END,
			                CASE
			                    WHEN EXTRACT(DAY FROM age(now(), ev.created_at)) > 0
			                        THEN EXTRACT(DAY FROM age(now(), ev.created_at))::text || ' Día(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(HOUR FROM age(now(), ev.created_at)) > 0
			                        THEN EXTRACT(HOUR FROM age(now(), ev.created_at))::text || ' Hora(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(MINUTE FROM age(now(), ev.created_at)) > 0
			                        THEN EXTRACT(MINUTE FROM age(now(), ev.created_at))::text || ' Minuto(s)'
			                END
			            )
			        ) || ' Sin Firmar'
			    ELSE
			        COALESCE(to_char(ev.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
			END::text AS tiempo_firma
	    FROM prestaciones p
		JOIN evaluaciones ev ON ev.id_prestacion = p.id
        JOIN users u1 ON u1.id = ev.created_us
        JOIN asegurados a ON p.id_asegurado = a.id
        JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
        JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
        JOIN consultorios co ON sc.id_consultorio = co.id
	    WHERE ev.flag in('0','2')
          AND ev.id_estado_reg = 1
          AND ev.id_tipo_atencion in (2,3)
          AND p.id_establecimiento = p_id_establecimiento
          AND ev.created_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
          AND (p_id_user IS NULL OR u1.id = p_id_user)
		  AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)
			UNION
            -- 3 RECETA
            SELECT
                3 AS orden_tipo,
                u1.id AS id_usuario,
                p.id::integer AS id_prestacion,
                'RECETA'::TEXT AS documento_por_firmar,
                to_char(rv.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_txt,
                rv.id::integer AS id_principal,
                a.tipo_doc_ident,
                a.nro_doc_ident,
                p.id_establecimiento,
                (a.paterno || ' ' || a.materno || ', ' || a.nombre)::TEXT AS nombre_paciente,
                (EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date)) || ' años, ' ||
                 EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date)) || ' meses, ' ||
                 EXTRACT(DAY FROM age(current_date, a.fecha_nac::date)) || ' días')::TEXT AS edad,
                (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::TEXT AS sexo,
                p.id_tipo_atencion,
                ta.nombre::TEXT AS tipo_atencion,
                co.nombre::TEXT AS servicio,
                NULL::TEXT AS examen,
                to_char(rv.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_prestacion,
                (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::TEXT AS usuario_medico,
                rv.fecha_firma::timestamp AS fecha_firma,
                rv.ruta_receta::TEXT AS ruta_firma,
				null::text as ruta_indicaciones,
				null::text as ruta_epicrisis,
                rv.flag,
				p.flag as flag_prestacion,
				rv.id_evaluacion::integer as id_ev,
				null::integer  as tipo_egreso,
				null::integer as id_servicio_radiologia,
				null::integer as id_rad,
				null::integer as id_ira,
				rv.id_interconsulta::integer as id_interconsulta,
                CASE
				    WHEN rv.flag = 0 THEN
				        TRIM(
				            BOTH ', ' FROM
				            CONCAT_WS(
				                ', ',
				                CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - rv.created_at))) > 0
				                    THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - rv.created_at)))::text || ' Año(s)' END,
				                CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - rv.created_at))) > 0
				                    THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - rv.created_at)))::text || ' Mes(es)' END,
				                CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - rv.created_at))) > 0
				                    THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - rv.created_at)))::text || ' Día(s)' END,
				                CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - rv.created_at))) > 0
				                    THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - rv.created_at)))::text || ' Hora(s)' END,
				                CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - rv.created_at))) > 0
				                    THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - rv.created_at)))::text || ' Minuto(s)' END
				            )
				        ) || ' Sin Firmar'
				    ELSE
				        COALESCE(to_char(rv.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
				END::TEXT AS tiempo_firma

            FROM prestaciones p
            JOIN receta_vales rv ON p.id = rv.id_prestacion AND rv.id_prestacion <> 0
            inner join medicos m    on rv.id_medico  = m.id
        	inner JOIN users u1     ON u1.dni = m.dni and m.id_establecimiento = u1.id_establecimiento
            JOIN asegurados a ON rv.dni_beneficiario = a.nro_doc_ident
            JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
            JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
            JOIN consultorios co ON sc.id_consultorio = co.id
            LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
            WHERE rv.flag in ('0','2')
              AND rv.estado != 0
              AND p.id_establecimiento = p_id_establecimiento
              AND rv.created_at::DATE BETWEEN p_fecha_ini::date AND p_fecha_fin::date
              AND (p_id_user IS NULL OR u1.id = p_id_user)
			  AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)
			  AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)

            UNION

            -- 4 LABORATORIO
            SELECT
                4 AS orden_tipo,
                u1.id AS id_usuario,
                p.id::integer AS id_prestacion,
                'ORDEN DE LABORATORIO'::TEXT AS documento_por_firmar,
                to_char(l.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_txt,
                l.id::integer AS id_principal,
                a.tipo_doc_ident,
                a.nro_doc_ident,
                l.id_establecimiento,
                (a.paterno || ' ' || a.materno || ', ' || a.nombre)::TEXT AS nombre_paciente,
                (EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date)) || ' años, ' ||
                 EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date)) || ' meses, ' ||
                 EXTRACT(DAY FROM age(current_date, a.fecha_nac::date)) || ' días')::TEXT AS edad,
                (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::TEXT AS sexo,
                p.id_tipo_atencion,
                ta.nombre::TEXT AS tipo_atencion,
                co.nombre::TEXT AS servicio,
				null AS examen,
                to_char(l.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_prestacion,
                (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::TEXT AS usuario_medico,
                l.fecha_firma::timestamp AS fecha_firma,
                l.ruta_laboratorio::TEXT AS ruta_firma,
				null::text as ruta_indicaciones,
				null::text as ruta_epicrisis,
                l.flag,
				p.flag as flag_prestacion,
				l.id_evaluacion::integer as id_ev,
				null::integer  as tipo_egreso,
				null::integer as id_servicio_radiologia,
				null::integer as id_rad,
				null::integer as id_ira,
				l.id_interconsulta::integer as id_interconsulta,
                CASE
				    WHEN l.flag = 0 THEN
				        TRIM(
				            BOTH ', ' FROM
				            CONCAT_WS(
				                ', ',
				                CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - l.created_at))) > 0
				                    THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - l.created_at)))::text || ' Año(s)' END,
				                CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - l.created_at))) > 0
				                    THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - l.created_at)))::text || ' Mes(es)' END,
				                CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - l.created_at))) > 0
				                    THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - l.created_at)))::text || ' Día(s)' END,
				                CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - l.created_at))) > 0
				                    THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - l.created_at)))::text || ' Hora(s)' END,
				                CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - l.created_at))) > 0
				                    THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - l.created_at)))::text || ' Minuto(s)' END
				            )
				        ) || ' Sin Firmar'
				    ELSE
				        COALESCE(to_char(l.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
				END::TEXT AS tiempo_firma
            FROM prestaciones p
            JOIN laboratorios l ON p.id = l.id_prestacion
            JOIN users u1 ON l.created_us = u1.id
            JOIN asegurados a ON p.id_asegurado = a.id
            JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
            JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
            JOIN consultorios co ON sc.id_consultorio = co.id
            LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
            WHERE l.flag in('0','2')
              AND p.id_estado_reg = 1
              AND l.estado = 1
              AND l.id_establecimiento = p_id_establecimiento
              AND l.created_at::DATE BETWEEN p_fecha_ini::date AND p_fecha_fin::date
              AND (p_id_user IS NULL OR u1.id = p_id_user)
              AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)
              AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)

            UNION

            -- 5 RADIOLOGÍA
            SELECT
                5 AS orden_tipo,
                u1.id,
                p.id::integer AS id_prestacion,
                'ORDEN DE DIAGNOSTICO POR IMÁGENES'::text,
                to_char(r.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_txt,
                r.id::integer AS id_principal,
                a.tipo_doc_ident::text,
                a.nro_doc_ident::text,
                r.id_establecimiento,
                (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
                (EXTRACT(YEAR FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' años, ' ||
                 EXTRACT(MONTH FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' meses, ' ||
                 EXTRACT(DAY FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' días')::text,
                (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
                p.id_tipo_atencion,
                ta.nombre::text,
                co.nombre::text,
                null,
                to_char(r.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_prestacion,
                (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text,
                r.fecha_firma::timestamp,
                r.ruta_radiologia::text,
				null::text as ruta_indicaciones,
				null::text as ruta_epicrisis,
                r.flag,
				p.flag as flag_prestacion,
				r.id_evaluacion::integer as id_ev,
				null::integer  as tipo_egreso,
				null::integer as id_servicio_radiologia,
				null::integer as id_rad,
				null::integer as id_ira,
				r.id_interconsulta::integer as id_interconsulta,
                CASE
				    WHEN r.flag = 0 THEN
				        TRIM(
				            BOTH ', ' FROM
				            CONCAT_WS(
				                ', ',
				                CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - r.created_at))) > 0
				                    THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - r.created_at)))::text || ' Año(s)' END,
				                CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - r.created_at))) > 0
				                    THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - r.created_at)))::text || ' Mes(es)' END,
				                CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - r.created_at))) > 0
				                    THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - r.created_at)))::text || ' Día(s)' END,
				                CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - r.created_at))) > 0
				                    THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - r.created_at)))::text || ' Hora(s)' END,
				                CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - r.created_at))) > 0
				                    THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - r.created_at)))::text || ' Minuto(s)' END
				            )
				        ) || ' Sin Firmar'
				    ELSE
				        COALESCE(to_char(r.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
				END::text AS tiempo_firma
            FROM prestaciones p
            JOIN radiologias r ON p.id = r.id_prestacion
            JOIN users u1 ON r.created_us = u1.id
            JOIN asegurados a ON p.id_asegurado = a.id
            JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
            JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
            JOIN consultorios co ON sc.id_consultorio = co.id
            JOIN prestacion_procedimientos pp ON p.id = pp.id_prestacion and pp.id_lab_rad = r.id
            JOIN radnivelatenciones rn ON pp.id_rua = rn.id
            LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
            WHERE r.flag in('0','2')
              AND p.id_estado_reg = 1
              AND r.estado = 1
              AND r.id_establecimiento = p_id_establecimiento
              AND r.created_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
              AND (p_id_user IS NULL OR u1.id = p_id_user)
              AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)
              AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)

			UNION
		--6 INTERCONSULTA
		    SELECT
		        6 AS orden_tipo,
		        u1.id,
		        p.id::integer AS id_prestacion,
		        'INTERCONSULTA'::text,
		        to_char(i.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_txt,
		        i.id::integer AS id_principal,
		        a.tipo_doc_ident::text,
		        a.nro_doc_ident::text,
		        i.id_establecimiento,
		        (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
		        (EXTRACT(YEAR FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' años, ' ||
		         EXTRACT(MONTH FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' meses, ' ||
		         EXTRACT(DAY FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' días')::text,
		        (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
		        i.id_tipo_atencion,
		        ta.nombre::text,
		        co.nombre::text,
		        null,
		        to_char(i.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_prestacion,
		        (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text,
		        i.fecha_firma_interconsulta::timestamp,
		        i.ruta_interconsulta::text,
				null::text as ruta_indicaciones,
				null::text as ruta_epicrisis,
		        i.flag,
				p.flag as flag_prestacion,
				i.id_evaluacion::integer as id_ev,
				null::integer  as tipo_egreso,
				null::integer as id_servicio_radiologia,
				null::integer as id_rad,
				null::integer as id_ira,
				NULL::integer as id_interconsulta,
		        CASE
				    WHEN i.flag = 0 THEN
				        TRIM(
				            BOTH ', ' FROM
				            CONCAT_WS(
				                ', ',
				                CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - i.created_at))) > 0
				                    THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - i.created_at)))::text || ' Año(s)' END,
				                CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - i.created_at))) > 0
				                    THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - i.created_at)))::text || ' Mes(es)' END,
				                CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - i.created_at))) > 0
				                    THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - i.created_at)))::text || ' Día(s)' END,
				                CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - i.created_at))) > 0
				                    THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - i.created_at)))::text || ' Hora(s)' END,
				                CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - i.created_at))) > 0
				                    THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - i.created_at)))::text || ' Minuto(s)' END
				            )
				        ) || ' Sin Firmar'
				    ELSE
				        COALESCE(to_char(i.fecha_firma_interconsulta::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
				END::text AS tiempo_firma
		    FROM prestaciones p
		    JOIN interconsultas i on i.id_prestacion = p.id
		    JOIN medicos m on m.id = i.id_medico_solicitante
		    JOIN users u1 on u1.dni = m.dni
		    JOIN asegurados a ON p.id_asegurado = a.id
		    JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
		    JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
		    JOIN consultorios co ON sc.id_consultorio = co.id
		    LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
		    WHERE i.flag in('0','2')
			  AND i.id_tipo_atencion in (2,3)
		      AND p.id_estado_reg = 1
		      AND i.id_estado_reg = 1
		      AND i.id_establecimiento = p_id_establecimiento
		      AND i.created_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
		      AND (p_id_user IS NULL OR u1.id = p_id_user)
			  AND (
				      p.id_tipo_atencion = 1
				      OR (
				            p.id_tipo_atencion <> 1
				            AND (p.flag_intervencion_quirurgica = false OR p.flag_intervencion_quirurgica IS NULL)
				         )
				    )
		      AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)
		      AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)

		UNION
		-- 7 ALTA EMERGENCIA
			SELECT
	        7 AS orden_tipo,
	        u1.id,
	        p.id::integer AS id_prestacion,
	        'ALTA EMERGENCIA'::text,
	        concat(to_char(e.fecha_alta_medica, 'DD/MM/YYYY'),' ',to_char(e.hora_alta_medica::time,'HH24:MI:SS')) AS fecha_atencion_txt,
	        e.id::integer AS id_principal,
	        a.tipo_doc_ident::text,
	        a.nro_doc_ident::text,
	        e.id_establecimiento,
	        (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
	        (EXTRACT(YEAR FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' años, ' ||
	         EXTRACT(MONTH FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' meses, ' ||
	         EXTRACT(DAY FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' días')::text,
	        (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
	        p.id_tipo_atencion,
	        ta.nombre::text,
	        co.nombre::text,
	        null,
	        concat(to_char(e.fecha_alta_medica, 'DD/MM/YYYY'),' ',to_char(e.hora_alta_medica::time,'HH24:MI:SS')) AS fecha_atencion_prestacion,
	        (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text,
	        e.fecha_firma_egreso::timestamp,
	        e.ruta_egreso::text,
			null::text as ruta_indicaciones,
			null::text as ruta_epicrisis,
	        e.flag_egreso as flag,
			p.flag as flag_prestacion,
			ev.id_evolucion as id_ev,
			e.id_tipo_egres_med::integer as tipo_egreso,
			null::integer as id_servicio_radiologia,
			null::integer as id_rad,
			null::integer as id_ira,
			null::integer as id_interconsulta,
	        CASE
			    WHEN e.ruta_egreso IS NULL OR e.ruta_egreso = '' THEN
			        TRIM(
			            BOTH ', ' FROM
			            CONCAT_WS(
			                ', ',
			                CASE
			                    WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time))))::text || ' Año(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time))))::text || ' Mes(es)'
			                END,
			                CASE
			                    WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time))))::text || ' Día(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time))))::text || ' Hora(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - (e.fecha_alta_medica + e.hora_alta_medica::time))))::text || ' Minuto(s)'
			                END
			            )
			        ) || ' Sin Firmar'
			    ELSE
			        COALESCE(to_char(e.fecha_firma_egreso::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
			END::text AS tiempo_firma
	    FROM prestaciones p
		JOIN emergencias e ON e.id = p.id_ext and p.id_tipo_atencion=2
		LEFT JOIN ultima_evolucion_emergencia ev ON ev.id_emergencia = e.id
	    JOIN users u1 ON u1.id = e.id_medico_egreso
	    JOIN asegurados a ON e.dni_beneficiario = a.nro_doc_ident
	    JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
	    left join triajes t on t.id = e.id_triaje
	    JOIN consultorios co ON t.id_servicio = co.id
	    WHERE e.flag_egreso = 1 and e.flag_firma_egreso <> 1
	      AND e.estado = 5
	      AND p.id_tipo_atencion = 2
	      AND e.id_establecimiento = p_id_establecimiento
	      AND e.fecha_alta_medica::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
	      AND (p_id_user IS NULL OR u1.id = p_id_user)
		  AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)

		UNION
		-- 8 ALTA HOSPITALIZACION
		select
		    8 AS orden_tipo,
		    u1.id,
		    p.id::integer AS id_prestacion,
		    'ALTA HOSPITALIZACION'::text,
		    concat(to_char(h.fecha_alta_medica, 'DD/MM/YYYY'),' ',to_char(h.hora_alta_medica::time,'HH24:MI:SS')) AS fecha_atencion_txt,
		    h.id::integer AS id_principal,
		    a.tipo_doc_ident::text,
		    a.nro_doc_ident::text,
		    h.id_establecimiento,
		    (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
		    (EXTRACT(YEAR FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' años, ' ||
		     EXTRACT(MONTH FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' meses, ' ||
		     EXTRACT(DAY FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' días')::text,
		    (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
		    p.id_tipo_atencion,
		    ta.nombre::text,
		    co.nombre::text,
		    null,
		    concat(to_char(h.fecha_alta_medica, 'DD/MM/YYYY'),' ',to_char(h.hora_alta_medica::time,'HH24:MI:SS')) AS fecha_atencion_prestacion,
		    (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text,
		    h.fecha_firma_alta_medica::timestamp,
		    h.ruta_alta_medica::text,
			h.ruta_papeleta_egreso::text as ruta_papeleta_egreso,
			h.ruta_epicrisis::text as ruta_epicrisis,
		    COALESCE(h.flag_alta_medica::integer, 0) AS flag,
			p.flag::integer as flag_prestacion,
			ev.id_evolucion as id_ev,
			null as tipo_egreso,
			null::integer as id_servicio_radiologia,
			null::integer as id_rad,
			null::integer as id_ira,
			null::integer as id_interconsulta,
		    CASE
			    WHEN h.ruta_alta_medica IS NULL OR h.ruta_alta_medica = '' THEN
			        TRIM(
			            BOTH ', ' FROM
			            CONCAT_WS(
			                ', ',
			                CASE
			                    WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time))))::text || ' Año(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time))))::text || ' Mes(es)'
			                END,
			                CASE
			                    WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time))))::text || ' Día(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time))))::text || ' Hora(s)'
			                END,
			                CASE
			                    WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time)))) > 0
			                        THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - (h.fecha_alta_medica + h.hora_alta_medica::time))))::text || ' Minuto(s)'
			                END
			            )
			        ) || ' Sin Firmar'
			    ELSE
			        COALESCE(to_char(h.fecha_firma_alta_medica::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
			END::text AS tiempo_firma
		FROM hospitalizaciones h
		JOIN prestaciones p ON p.id = (select p2.id from prestaciones p2 where h.id = p2.id_ext and p2.id_tipo_atencion = 3 order by p2.id desc limit 1)
		LEFT JOIN ultima_evolucion_hospitalizacion ev ON ev.id_hospitalizacion = h.id
		JOIN users u1 ON u1.id = h.id_medico_alta
		JOIN asegurados a ON h.dni_beneficiario = a.nro_doc_ident
		JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
		left join consultorios co on co.id = p.id_servicio
		WHERE p.id_tipo_atencion = 3
		  AND (p.flag_intervencion_quirurgica = false OR p.flag_intervencion_quirurgica IS NULL)
		  AND h.id_establecimiento = 76
		  and p.id_servicio = h.id_servicio_encargado
		  and h.flag_alta_medica is null
		  and h.estado in (6,7)
		  AND h.fecha_alta_medica::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
		  AND (p_id_user IS NULL OR u1.id = p_id_user)
		  AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)

		UNION
		-- 9 INFORME RADIOLOGÍA
            SELECT
			9 AS orden_tipo,
			u1.id,
			p.id::integer AS id_prestacion,
			'INFORME RADIOLOGIA'::text,
			to_char(pp.validated_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_txt,
			pp.id::integer AS id_principal,
			a.tipo_doc_ident::text,
			a.nro_doc_ident::text,
			p.id_establecimiento,
			(a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
			(EXTRACT(YEAR FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' años, ' ||
			 EXTRACT(MONTH FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' meses, ' ||
			 EXTRACT(DAY FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' días')::text,
			(CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
			p.id_tipo_atencion,
			ta.nombre::text,
			co.nombre::text,
			null::text,
			to_char(pp.validated_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_prestacion,
			(u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text,
			pp.fecha_firma::timestamp,
			pp.ruta_prestacion_procedimiento::text,
			null::text as ruta_indicaciones,
			null::text as ruta_epicrisis,
			pp.flag,
			p.flag as flag_prestacion,
			pp.id_evaluacion::integer as id_ev,
			null::integer  as tipo_egreso,
			proc.id_servicio_radiologia::integer,
			r.id::integer as id_rad,
			null::integer as id_ira,
			null::integer as id_interconsulta,
			CASE
			    WHEN pp.flag = 0 THEN
			        TRIM(
			            BOTH ', ' FROM
			            CONCAT_WS(
			                ', ',
			                CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - pp.validated_at))) > 0
			                    THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - pp.validated_at)))::text || ' Año(s)' END,
			                CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - pp.validated_at))) > 0
			                    THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - pp.validated_at)))::text || ' Mes(es)' END,
			                CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - pp.validated_at))) > 0
			                    THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - pp.validated_at)))::text || ' Día(s)' END,
			                CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - pp.validated_at))) > 0
			                    THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - pp.validated_at)))::text || ' Hora(s)' END,
			                CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - pp.validated_at))) > 0
			                    THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - pp.validated_at)))::text || ' Minuto(s)' END
			            )
			        ) || ' Sin Firmar'
			    ELSE
			        COALESCE(to_char(pp.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
				END::text AS tiempo_firma
			    FROM prestaciones p
			    JOIN radiologias r ON p.id = r.id_prestacion
			    JOIN prestacion_procedimientos pp ON p.id = pp.id_prestacion and pp.id_lab_rad = r.id
				left join procedimientos proc on pp.id_procedimiento = proc.id
			    JOIN users u1 ON pp.validated_us = u1.id
			    JOIN asegurados a ON p.id_asegurado = a.id
			    JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
			    JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
			    JOIN consultorios co ON sc.id_consultorio = co.id
			    JOIN radnivelatenciones rn ON pp.id_rua = rn.id
		    WHERE p.id_establecimiento <> 76 and pp.flag = 0
		      AND p.id_estado_reg = 1
		      AND r.estado = 1
              AND p.id_establecimiento = p_id_establecimiento
              AND pp.validated_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
              AND (p_id_user IS NULL OR u1.id = p_id_user)
              AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)

		UNION
		-- 10 INFORME RADIOLOGÍA ADICIONAL
		SELECT
			10 AS orden_tipo,
			u1.id,
			p.id::integer AS id_prestacion,
			'INFORME RADIOLOGIA ADICIONAL(ADENDA)'::text,
			to_char(ira.verific_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_txt,
			ira.id::integer AS id_principal,
			a.tipo_doc_ident::text,
			a.nro_doc_ident::text,
			p.id_establecimiento,
			(a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
			(EXTRACT(YEAR FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' años, ' ||
			 EXTRACT(MONTH FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' meses, ' ||
			 EXTRACT(DAY FROM age(p.fecha_atencion, a.fecha_nac::date)) || ' días')::text,
			(CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
			p.id_tipo_atencion,
			ta.nombre::text,
			co.nombre::text,
			null::text,
			to_char(ira.verific_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion_prestacion,
			(u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text,
			ira.fecha_firma::timestamp,
			ira.ruta_informe_radiologia_adicional::text,
			null::text as ruta_indicaciones,
			null::text as ruta_epicrisis,
			ira.flag,
			p.flag as flag_prestacion,
			pp.id_evaluacion::integer as id_ev,
			null::integer  as tipo_egreso,
			cr.id_servicio::integer,
			r.id::integer as id_rad,
			ira.id_prestacion_procedimiento::integer as id_ira,
			null::integer as id_interconsulta,
			CASE
			    WHEN ira.flag = 0 THEN
			        TRIM(
			            BOTH ', ' FROM
			            CONCAT_WS(
			                ', ',
			                CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - ira.verific_at))) > 0
			                    THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - ira.verific_at)))::text || ' Año(s)' END,
			                CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - ira.verific_at))) > 0
			                    THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - ira.verific_at)))::text || ' Mes(es)' END,
			                CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - ira.verific_at))) > 0
			                    THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - ira.verific_at)))::text || ' Día(s)' END,
			                CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - ira.verific_at))) > 0
			                    THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - ira.verific_at)))::text || ' Hora(s)' END,
			                CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - ira.verific_at))) > 0
			                    THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - ira.verific_at)))::text || ' Minuto(s)' END
			            )
			        ) || ' Sin Firmar'
			    ELSE
			        COALESCE(to_char(ira.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI'), 'No Firmado')
				END::text AS tiempo_firma

		FROM prestaciones p
		    JOIN radiologias r ON p.id = r.id_prestacion
		    JOIN prestacion_procedimientos pp ON p.id = pp.id_prestacion and pp.id_lab_rad = r.id
			JOIN informes_radiologias_adicionales ira on ira.id_prestacion_procedimiento =pp.id
		    JOIN users u1 ON ira.verif_us = u1.id
		    JOIN asegurados a ON p.id_asegurado = a.id
		    JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
		    JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
		    JOIN consultorios co ON sc.id_consultorio = co.id
		    JOIN radnivelatenciones rn ON pp.id_rua = rn.id
			LEFT JOIN categoria_radiologias cr on cr.id = rn.id_categoria
		WHERE p.id_establecimiento <> 76 and ira.flag = 0
		    AND p.id_estado_reg = 1
		    AND r.estado = 1
		    AND p.id_establecimiento = p_id_establecimiento
		    AND ira.verific_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
		    AND (p_id_user IS NULL OR u1.id = p_id_user)
			AND (p_dni_beneficiario IS NULL OR a.nro_doc_ident = p_dni_beneficiario)
        ),
	conteo AS (
	    SELECT COUNT(*)::integer AS total FROM docs
	),
    filtradas AS (
        SELECT *
        FROM docs
        ORDER BY id_prestacion, orden_tipo, fecha_atencion_txt DESC
        LIMIT p_limit
        OFFSET (p_pagina - 1) * p_limit
    )
    SELECT f.id_usuario,
           f.id_prestacion,
           f.documento_por_firmar,
           f.fecha_atencion_txt AS fecha_atencion,
           f.id_principal,
           f.tipo_doc_ident,
           f.nro_doc_ident,
           f.id_establecimiento,
           f.nombre_paciente,
           f.edad,
           f.sexo,
           f.id_tipo_atencion,
           f.tipo_atencion,
           f.servicio,
           f.examen,
           f.fecha_atencion_prestacion,
           f.usuario_medico,
           f.fecha_firma,
           f.ruta_firma,
		   f.ruta_indicaciones,
		   f.ruta_epicrisis,
           f.flag,
           f.flag_prestacion,
		   f.id_ev,
		   f.tipo_egreso,
		   f.id_servicio_radiologia,
		   f.id_rad,
		   f.id_ira,
		   f.id_interconsulta,
           f.tiempo_firma,
           c.total AS totalrows
    FROM filtradas f, conteo c;
END;
$function$
;



-- SP DE CONTAR TOTAL GENERAL CON LA VALIDACIÓN UCI
-- DROP FUNCTION public.sp_contar_total_general(date, date, int4, int4);

CREATE OR REPLACE FUNCTION public.sp_contar_total_general(p_fecha_ini date, p_fecha_fin date, p_id_usuario integer, p_id_establecimiento integer)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_total INTEGER := 0;
BEGIN
    
    WITH
    -- 1 ATENCIONES
    atenciones AS (
        SELECT COUNT(*) AS total
        FROM prestaciones p
        JOIN asegurados a         ON a.id = p.id_asegurado
        JOIN tipo_atenciones ta   ON ta.id = p.id_tipo_atencion
        LEFT JOIN sub_consultorios sc ON sc.id = p.id_sub_consultorio  
        LEFT JOIN consultorios c  ON c.id = sc.id_consultorio
        JOIN citas ci             ON ci.id = p.id_cita
        JOIN users u              ON u.id = p.created_us
		LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
        WHERE p.fecha_atencion::date BETWEEN p_fecha_ini AND p_fecha_fin
          --AND p.ruta_prestacion IS NULL
          AND p.flag in('0','2')
		  AND p.id_estado_reg=1 
          AND p.id_establecimiento = p_id_establecimiento
          AND u.id = p_id_usuario
		  AND (		
			      p.id_tipo_atencion = 1
			      OR (
			            p.id_tipo_atencion <> 1
			            AND (p.flag_intervencion_quirurgica = false OR p.flag_intervencion_quirurgica IS NULL)
			         )
			    )
		AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)
    ),

    -- 2 RECETAS
    recetas AS (
        SELECT COUNT(*) AS total
        FROM prestaciones p
        JOIN receta_vales rv    ON rv.id_prestacion = p.id AND rv.id_prestacion <> 0
        inner join medicos m    on rv.id_medico  = m.id  
        inner JOIN users u1     ON u1.dni = m.dni and m.id_establecimiento = u1.id_establecimiento
        JOIN asegurados a       ON a.nro_doc_ident = rv.dni_beneficiario
		LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
        WHERE rv.flag in('0','2')
          AND rv.estado != 0
          AND a.id_estado_reg = 1
          AND rv.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND p.id_establecimiento = p_id_establecimiento
          AND u1.id = p_id_usuario
		  --AND (p.flag_intervencion_quirurgica = false or p.flag_intervencion_quirurgica = null)		-- COMENTADO PARA CQ 14-04-2026
		 AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)
    ),

    -- 3 LABORATORIO
    laboratorio AS (
        SELECT COUNT(*) AS total
        FROM prestaciones p
        JOIN laboratorios l     ON l.id_prestacion = p.id
        JOIN users u1           ON u1.id = l.created_us
        JOIN asegurados a       ON a.id = p.id_asegurado
        JOIN tipo_atenciones ta ON ta.id = p.id_tipo_atencion
        JOIN sub_consultorios sc ON sc.id = p.id_sub_consultorio
        JOIN consultorios co    ON co.id = sc.id_consultorio
		LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
        WHERE l.flag in('0','2')
          AND l.estado = 1
          AND p.id_estado_reg = 1
          AND l.id_establecimiento = p_id_establecimiento
          AND l.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND u1.id = p_id_usuario
		  AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)
    ),

    -- 4 RADIOLOGÍA
    radiologia AS (
        SELECT COUNT(*) AS total
        FROM prestaciones p
        JOIN radiologias r      ON r.id_prestacion = p.id
        JOIN users u1           ON u1.id = r.created_us
        JOIN asegurados a       ON a.id = p.id_asegurado
        JOIN tipo_atenciones ta ON ta.id = p.id_tipo_atencion
        JOIN sub_consultorios sc ON sc.id = p.id_sub_consultorio
        JOIN consultorios co    ON co.id = sc.id_consultorio
		LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
        WHERE r.flag in('0','2')
          AND r.estado = 1
          AND p.id_estado_reg = 1
          AND r.id_establecimiento = p_id_establecimiento
          AND r.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND u1.id = p_id_usuario
		  AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)
    ),

    -- 5 INTERCONSULTAS
    interconsulta AS (
        SELECT COUNT(*) AS total
        FROM prestaciones p
        JOIN interconsultas i   ON i.id_prestacion = p.id
        JOIN medicos m          ON m.id = i.id_medico_solicitante
        JOIN users u1           ON u1.dni = m.dni
        JOIN asegurados a       ON a.id = p.id_asegurado
        JOIN tipo_atenciones ta ON ta.id = p.id_tipo_atencion
        JOIN sub_consultorios sc ON sc.id = p.id_sub_consultorio
        JOIN consultorios co    ON co.id = sc.id_consultorio
		LEFT JOIN hospitalizaciones h ON h.id = p.id_ext AND p.id_tipo_atencion = 3
        WHERE i.flag in('0','2')
		  AND i.id_tipo_atencion in (2,3)
          AND i.id_estado_reg = 1
          AND p.id_estado_reg = 1
          AND i.id_establecimiento = p_id_establecimiento
          AND i.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND u1.id = p_id_usuario
		  AND (		
			      p.id_tipo_atencion = 1
			      OR (
			            p.id_tipo_atencion <> 1
			            AND (p.flag_intervencion_quirurgica = false OR p.flag_intervencion_quirurgica IS NULL)
			         )
			    )
		AND (p.id_tipo_atencion <> 3 OR h.id_servicio_encargado IS DISTINCT FROM 80 OR h.flag_hc_cu IS NOT NULL)
    ),

    -- 6 ALTAS EMERGENCIA
    altas_emergencia AS (
        SELECT COUNT(*) AS total
        FROM prestaciones p
        JOIN emergencias e      ON e.id = p.id_ext AND p.id_tipo_atencion = 2
        JOIN users u1           ON u1.id = e.id_medico_egreso
        JOIN asegurados a       ON a.nro_doc_ident = e.dni_beneficiario
        JOIN tipo_atenciones ta ON ta.id = p.id_tipo_atencion
        LEFT JOIN triajes t     ON t.id = e.id_triaje
        JOIN consultorios co    ON co.id = t.id_servicio
        WHERE e.flag_egreso = 1
          AND e.estado = 5
          AND e.ruta_egreso IS NULL
          AND a.id_estado_reg = 1
          AND e.id_establecimiento = p_id_establecimiento
          AND e.fecha_alta_medica::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND u1.id = p_id_usuario
    ),

    -- 7 EVOLUCIONES
    evoluciones AS (
        SELECT COUNT(*) AS total
        FROM prestaciones p
        JOIN evaluaciones ev    ON ev.id_prestacion = p.id
        JOIN users u1           ON u1.id = ev.created_us
        JOIN asegurados a       ON a.id = p.id_asegurado
        JOIN tipo_atenciones ta ON ta.id = p.id_tipo_atencion
        JOIN sub_consultorios sc ON sc.id = p.id_sub_consultorio
        JOIN consultorios co    ON co.id = sc.id_consultorio
        WHERE ev.flag in('0','2')
          AND ev.id_estado_reg = 1
          AND a.id_estado_reg = 1
          AND p.id_establecimiento = p_id_establecimiento
          AND ev.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND u1.id = p_id_usuario
    ),
	
    -- 8 ALTAS HOSPITALIZACIÓN
    altas_hospitalizacion AS (
        SELECT COUNT(*) AS total
        FROM hospitalizaciones h
		JOIN prestaciones p ON p.id = (select p2.id from prestaciones p2 where h.id = p2.id_ext and p2.id_tipo_atencion = 3 order by p2.id desc limit 1)
        JOIN users u ON u.id = h.id_medico_alta
        JOIN asegurados a       ON a.id = p.id_asegurado
        JOIN tipo_atenciones ta ON ta.id = p.id_tipo_atencion
		LEFT JOIN consultorios co on co.id = p.id_servicio
        WHERE p.id_tipo_atencion = 3
		  AND (p.flag_intervencion_quirurgica = false OR p.flag_intervencion_quirurgica IS NULL) -- AGREGADO EL 31-03-2026
		  AND h.id_establecimiento = p_id_establecimiento
		  AND p.id_servicio = h.id_servicio_encargado
		  AND h.flag_alta_medica is null
		  AND h.fecha_alta_medica::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
		  AND h.estado in (6,7)
		  AND u.id = p_id_usuario
    ),

	-- 9 INFORME RADIOLÓGICO
    informe_radiologia AS (
        SELECT COUNT(*) AS total
        FROM prestaciones p
	    JOIN radiologias r ON p.id = r.id_prestacion
	    JOIN prestacion_procedimientos pp ON p.id = pp.id_prestacion and pp.id_lab_rad = r.id
	    JOIN users u1 ON pp.validated_us = u1.id
	    JOIN asegurados a ON p.id_asegurado = a.id
	    JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
	    JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
	    JOIN consultorios co ON sc.id_consultorio = co.id
	    JOIN radnivelatenciones rn ON pp.id_rua = rn.id
		WHERE p.id_establecimiento <> 76 and pp.flag = 0
		  AND p.id_estado_reg = 1
		  AND r.estado = 1
		  AND a.id_estado_reg = 1
		  AND p.id_establecimiento = p_id_establecimiento
		  AND pp.validated_at BETWEEN p_fecha_ini AND (p_fecha_fin::date+1)
		  AND (p_id_usuario IS NULL OR u1.id = p_id_usuario)
    ),

	-- 10 INFORME RADIOLÓGICO ADICIONAL
    informe_radiologia_adicional AS (
	    SELECT COUNT(*) AS total 
			FROM informes_radiologias_adicionales ira
	    JOIN prestacion_procedimientos pp ON ira.id_prestacion_procedimiento = pp.id
	    JOIN radiologias r ON pp.id_lab_rad = r.id
	    JOIN prestaciones p ON p.id = pp.id_prestacion
	    JOIN users u1 ON ira.verif_us = u1.id
	    JOIN asegurados a ON p.id_asegurado = a.id
	    JOIN radnivelatenciones rn ON pp.id_rua = rn.id
	    JOIN sub_consultorios sc ON p.id_sub_consultorio = sc.id
	    JOIN consultorios co ON sc.id_consultorio = co.id
	    JOIN tipo_atenciones ta ON p.id_tipo_atencion = ta.id
	    LEFT JOIN procedimientos proc ON pp.id_procedimiento = proc.id
	    WHERE p.id_establecimiento <> 76 and ira.flag = 0
	      AND p.id_estado_reg = 1
	      AND r.estado = 1
	      AND a.id_estado_reg = 1
	      AND p.id_establecimiento = p_id_establecimiento
	      AND ira.verific_at BETWEEN p_fecha_ini AND (p_fecha_fin::date+1)
	      AND (p_id_usuario IS NULL OR u1.id = p_id_usuario)
	)

    SELECT
        COALESCE((SELECT total FROM atenciones),0) +
        COALESCE((SELECT total FROM recetas),0) +
        COALESCE((SELECT total FROM laboratorio),0) +
        COALESCE((SELECT total FROM radiologia),0) +
        COALESCE((SELECT total FROM interconsulta),0) +
        COALESCE((SELECT total FROM altas_emergencia),0) +
        COALESCE((SELECT total FROM evoluciones),0) +
		COALESCE((SELECT total FROM altas_hospitalizacion),0) +
		COALESCE((SELECT total FROM informe_radiologia),0) +
		COALESCE((SELECT total FROM informe_radiologia_adicional),0)
    INTO v_total;

    RETURN v_total;
END;
$function$
;
