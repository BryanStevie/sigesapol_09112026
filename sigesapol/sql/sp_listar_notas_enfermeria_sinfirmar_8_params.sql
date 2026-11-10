CREATE OR REPLACE FUNCTION public.sp_listar_notas_enfermeria_sinfirmar(
    p_id_establecimiento integer,
    p_fecha_ini character varying,
    p_fecha_fin character varying,
    p_dni_beneficiario character varying,
    p_id_user integer,
    p_pagina integer,
    p_limit integer,
    p_estado character varying DEFAULT 'P'::character varying
)
 RETURNS TABLE(
    id_usuario integer,
    id_principal integer,
    id_prestacion integer,
    documento_por_firmar text,
    id_tipo_atencion integer,
    tipo_atencion text,
    id_establecimiento integer,
    tipo_doc_ident text,
    nro_doc_ident text,
    nombre_paciente text,
    edad text,
    sexo text,
    servicio text,
    id_asegurado integer,
    fecha_atencion text,
    usuario_medico text,
    fecha_firma timestamp without time zone,
    ruta_firma text,
    flag integer,
    tiempo_firma text,
    orden_fecha timestamp without time zone,
    totalrows integer
)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    WITH enfermeria_contexto AS (
        -- CONSULTA EXTERNA
        SELECT
            enf.id AS id_enfermeria,
            enf.id_tipo_atencion,
            'AMBULATORIO'::text AS tipo_atencion,
            c.id_establecimiento,
            a.id AS id_asegurado,
            a.tipo_doc_ident::text,
            a.nro_doc_ident::text,
            (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text AS nombre_paciente,
            (EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date)) || ' años, ' ||
             EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date)) || ' meses, ' ||
             EXTRACT(DAY FROM age(current_date, a.fecha_nac::date)) || ' días')::text AS edad,
            (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text AS sexo,
            co.nombre::text AS servicio,
            co.id AS id_consultorio
        FROM enfermerias enf
        JOIN citas c ON c.id = enf.id_ext_enfermeria
        JOIN asegurados a ON a.id = c.id_asegurado
        JOIN sub_consultorios sc ON sc.id = c.id_sub_consultorio
        JOIN consultorios co ON co.id = sc.id_consultorio
        WHERE enf.id_tipo_atencion = 1
          AND a.id_estado_reg = 1
          AND c.id_establecimiento = p_id_establecimiento

        UNION ALL

        -- EMERGENCIA
        SELECT
            enf.id,
            enf.id_tipo_atencion,
            'EMERGENCIA'::text,
            e1.id_establecimiento,
            a.id,
            a.tipo_doc_ident::text,
            a.nro_doc_ident::text,
            (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
            (EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date)) || ' años, ' ||
             EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date)) || ' meses, ' ||
             EXTRACT(DAY FROM age(current_date, a.fecha_nac::date)) || ' días')::text,
            (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
            co.nombre::text,
            NULL::integer
        FROM enfermerias enf
        JOIN emergencias e1 ON e1.id = enf.id_ext_enfermeria
        JOIN asegurados a ON a.id = e1.id_asegurado
        LEFT JOIN triajes t ON t.id = e1.id_triaje
        LEFT JOIN consultorios co ON co.id = t.id_servicio
        WHERE enf.id_tipo_atencion = 2
          AND a.id_estado_reg = 1
          AND e1.id_establecimiento = p_id_establecimiento

        UNION ALL

        -- HOSPITALIZACIÓN
        SELECT
            enf.id,
            enf.id_tipo_atencion,
            'HOSPITALIZACION'::text,
            h.id_establecimiento,
            a.id,
            a.tipo_doc_ident::text,
            a.nro_doc_ident::text,
            (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text,
            (EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date)) || ' años, ' ||
             EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date)) || ' meses, ' ||
             EXTRACT(DAY FROM age(current_date, a.fecha_nac::date)) || ' días')::text,
            (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text,
            co.nombre::text,
            NULL::integer
        FROM enfermerias enf
        JOIN hospitalizaciones h ON h.id = enf.id_ext_enfermeria
        JOIN asegurados a ON a.id = h.id_asegurado
        LEFT JOIN consultorios co ON co.id = h.id_servicio_encargado
        WHERE enf.id_tipo_atencion = 3
          AND a.id_estado_reg = 1
          AND h.id_establecimiento = p_id_establecimiento
    ),
    cpt_contexto AS (
        -- CPT EMERGENCIA
        SELECT
            cpt.id AS id_cpt,
            2 AS id_tipo_atencion,
            'EMERGENCIA'::text AS tipo_atencion,
            e1.id_establecimiento,
            a.id AS id_asegurado,
            a.tipo_doc_ident::text,
            a.nro_doc_ident::text,
            (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text AS nombre_paciente,
            (EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date)) || ' años, ' ||
             EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date)) || ' meses, ' ||
             EXTRACT(DAY FROM age(current_date, a.fecha_nac::date)) || ' días')::text AS edad,
            (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text AS sexo,
            co.nombre::text AS servicio
        FROM nota_enfermeria_cpt cpt
        JOIN emergencias e1 ON e1.id = cpt.id_ext
        JOIN asegurados a ON a.id = e1.id_asegurado
        LEFT JOIN triajes t ON t.id = e1.id_triaje
        LEFT JOIN consultorios co ON co.id = t.id_servicio
        WHERE cpt.id_tipo_atenciones = 2
          AND a.id_estado_reg = 1
          AND e1.id_establecimiento = p_id_establecimiento

        UNION ALL

        -- CPT HOSPITALIZACIÓN
        SELECT
            cpt.id AS id_cpt,
            3 AS id_tipo_atencion,
            'HOSPITALIZACION'::text AS tipo_atencion,
            h.id_establecimiento,
            a.id AS id_asegurado,
            a.tipo_doc_ident::text,
            a.nro_doc_ident::text,
            (a.paterno || ' ' || a.materno || ', ' || a.nombre)::text AS nombre_paciente,
            (EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date)) || ' años, ' ||
             EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date)) || ' meses, ' ||
             EXTRACT(DAY FROM age(current_date, a.fecha_nac::date)) || ' días')::text AS edad,
            (CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END)::text AS sexo,
            co.nombre::text AS servicio
        FROM nota_enfermeria_cpt cpt
        JOIN hospitalizaciones h ON h.id = cpt.id_ext
        JOIN asegurados a ON a.id = h.id_asegurado
        LEFT JOIN consultorios co ON co.id = h.id_servicio_encargado
        WHERE cpt.id_tipo_atenciones = 3
          AND a.id_estado_reg = 1
          AND h.id_establecimiento = p_id_establecimiento
    ),
    notas AS (
        -- 1) NOTA DE ENFERMERÍA
        SELECT
            u1.id AS id_usuario,
            enf.id::integer AS id_principal,
            (CASE enf.id_tipo_atencion
                WHEN 1 THEN (SELECT p2.id FROM prestaciones p2 WHERE p2.id_cita = enf.id_ext_enfermeria ORDER BY p2.id DESC LIMIT 1)
                WHEN 2 THEN (SELECT p2.id FROM prestaciones p2 WHERE p2.id_ext = enf.id_ext_enfermeria AND p2.id_tipo_atencion = 2 ORDER BY p2.id DESC LIMIT 1)
                WHEN 3 THEN (SELECT p2.id FROM prestaciones p2 WHERE p2.id_ext = enf.id_ext_enfermeria AND p2.id_tipo_atencion = 3 ORDER BY p2.id DESC LIMIT 1)
            END)::integer AS id_prestacion,
            'NOTA DE ENFERMERIA'::text AS documento_por_firmar,
            ec.id_tipo_atencion, ec.tipo_atencion, ec.id_establecimiento,
            ec.tipo_doc_ident, ec.nro_doc_ident, ec.nombre_paciente, ec.edad, ec.sexo, ec.servicio, ec.id_asegurado,
            to_char(enf.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion,
            (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text AS usuario_medico,
            enf.fecha_firma::timestamp AS fecha_firma,
            enf.ruta_firma::text AS ruta_firma,
            COALESCE(enf.flag_firma, 0) AS flag,
            CASE WHEN p_estado = 'F' THEN
                'Firmado el ' || to_char(enf.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI')
            ELSE
                TRIM(BOTH ', ' FROM CONCAT_WS(', ',
                    CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - enf.created_at))) > 0 THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - enf.created_at)))::text || ' Año(s)' END,
                    CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - enf.created_at))) > 0 THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - enf.created_at)))::text || ' Mes(es)' END,
                    CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - enf.created_at))) > 0 THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - enf.created_at)))::text || ' Día(s)' END,
                    CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - enf.created_at))) > 0 THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - enf.created_at)))::text || ' Hora(s)' END,
                    CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - enf.created_at))) > 0 THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - enf.created_at)))::text || ' Minuto(s)' END
                )) || ' Sin Firmar'
            END AS tiempo_firma,
            enf.created_at AS orden_fecha
        FROM enfermerias enf
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = enf.id
        JOIN users u1 ON u1.id = COALESCE(enf.updated_us, enf.created_us)
        WHERE (
                (p_estado = 'P' AND enf.flag_firma IS NULL)
                OR
                (p_estado = 'F' AND enf.flag_docsinfirmar = 1 AND enf.fecha_firma IS NOT NULL)
              )
          AND enf.id_estado_reg = 1
          AND (
                (p_estado = 'P' AND enf.created_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
                OR
                (p_estado = 'F' AND enf.fecha_firma::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
              )
          AND (p_id_user IS NULL OR u1.id = p_id_user)
          AND (p_dni_beneficiario IS NULL OR ec.nro_doc_ident = p_dni_beneficiario)
          AND (ec.id_tipo_atencion <> 1 OR ec.id_consultorio NOT IN (156,105,157,266,76,265))

        UNION ALL

        -- 2) SIGNOS VITALES
        SELECT
            u1.id AS id_usuario,
            ec.id_enfermeria::integer AS id_principal,
            NULL::integer AS id_prestacion,
            'SIGNOS VITALES'::text AS documento_por_firmar,
            ec.id_tipo_atencion, ec.tipo_atencion, ec.id_establecimiento,
            ec.tipo_doc_ident, ec.nro_doc_ident, ec.nombre_paciente, ec.edad, ec.sexo, ec.servicio, ec.id_asegurado,
            to_char(sv.fecha_repr, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion,
            (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text AS usuario_medico,
            NULL::timestamp AS fecha_firma,
            sv.ruta_firma_repr AS ruta_firma,
            COALESCE(sv.flag_firma_repr, 0) AS flag,
            CASE WHEN p_estado = 'F' THEN
                'Firmado el ' || to_char(sv.fecha_firma_repr::timestamp, 'DD/MM/YYYY HH24:MI')
            ELSE
                TRIM(BOTH ', ' FROM CONCAT_WS(', ',
                    CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - sv.fecha_repr))) > 0 THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - sv.fecha_repr)))::text || ' Año(s)' END,
                    CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - sv.fecha_repr))) > 0 THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - sv.fecha_repr)))::text || ' Mes(es)' END,
                    CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - sv.fecha_repr))) > 0 THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - sv.fecha_repr)))::text || ' Día(s)' END,
                    CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - sv.fecha_repr))) > 0 THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - sv.fecha_repr)))::text || ' Hora(s)' END,
                    CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - sv.fecha_repr))) > 0 THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - sv.fecha_repr)))::text || ' Minuto(s)' END
                )) || ' Sin Firmar'
            END AS tiempo_firma,
            sv.fecha_repr AS orden_fecha
        FROM (
            SELECT DISTINCT ON (s.id_enfermeria)
                s.id_enfermeria,
                COALESCE(s.updated_us, s.created_us) AS id_usuario_resp,
                s.created_at AS fecha_repr,
                s.flag_firma AS flag_firma_repr,
                s.fecha_firma AS fecha_firma_repr,
                s.ruta_firma AS ruta_firma_repr
            FROM signo_vitales s
            WHERE (
                    (p_estado = 'P' AND s.flag_firma IS NULL)
                    OR
                    (p_estado = 'F' AND s.flag_docsinfirmar = 1 AND s.fecha_firma IS NOT NULL)
                  )
            ORDER BY s.id_enfermeria, s.created_at DESC
        ) sv
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = sv.id_enfermeria
        JOIN users u1 ON u1.id = sv.id_usuario_resp
        WHERE (
                (p_estado = 'P' AND sv.fecha_repr::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
                OR
                (p_estado = 'F' AND sv.fecha_firma_repr::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
              )
          AND (p_id_user IS NULL OR u1.id = p_id_user)
          AND (p_dni_beneficiario IS NULL OR ec.nro_doc_ident = p_dni_beneficiario)

        UNION ALL

        -- 3) KARDEX
        SELECT
            u1.id AS id_usuario,
            k.id::integer AS id_principal,
            NULL::integer AS id_prestacion,
            'KARDEX'::text AS documento_por_firmar,
            ec.id_tipo_atencion, ec.tipo_atencion, ec.id_establecimiento,
            ec.tipo_doc_ident, ec.nro_doc_ident, ec.nombre_paciente, ec.edad, ec.sexo, ec.servicio, ec.id_asegurado,
            to_char(k.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion,
            (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text AS usuario_medico,
            NULL::timestamp AS fecha_firma,
            k.ruta_firma::text AS ruta_firma,
            COALESCE(k.flag_firma, 0) AS flag,
            CASE WHEN p_estado = 'F' THEN
                'Firmado el ' || to_char(k.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI')
            ELSE
                TRIM(BOTH ', ' FROM CONCAT_WS(', ',
                    CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - k.created_at))) > 0 THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - k.created_at)))::text || ' Año(s)' END,
                    CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - k.created_at))) > 0 THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - k.created_at)))::text || ' Mes(es)' END,
                    CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - k.created_at))) > 0 THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - k.created_at)))::text || ' Día(s)' END,
                    CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - k.created_at))) > 0 THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - k.created_at)))::text || ' Hora(s)' END,
                    CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - k.created_at))) > 0 THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - k.created_at)))::text || ' Minuto(s)' END
                )) || ' Sin Firmar'
            END AS tiempo_firma,
            k.created_at AS orden_fecha
        FROM kardex_enfermerias k
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = k.id_enfermeria
        JOIN users u1 ON u1.id = k.created_us
        WHERE (
                (p_estado = 'P' AND k.flag_firma IS NULL)
                OR
                (p_estado = 'F' AND k.flag_docsinfirmar = 1 AND k.fecha_firma IS NOT NULL)
              )
          AND (
                (p_estado = 'P' AND k.created_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
                OR
                (p_estado = 'F' AND k.fecha_firma::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
              )
          AND (p_id_user IS NULL OR u1.id = p_id_user)
          AND (p_dni_beneficiario IS NULL OR ec.nro_doc_ident = p_dni_beneficiario)

        UNION ALL

        -- 4) BALANCE HÍDRICO
        SELECT
            u1.id AS id_usuario,
            bh.id::integer AS id_principal,
            NULL::integer AS id_prestacion,
            'BALANCE HIDRICO'::text AS documento_por_firmar,
            ec.id_tipo_atencion, ec.tipo_atencion, ec.id_establecimiento,
            ec.tipo_doc_ident, ec.nro_doc_ident, ec.nombre_paciente, ec.edad, ec.sexo, ec.servicio, ec.id_asegurado,
            to_char(bh.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion,
            (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text AS usuario_medico,
            NULL::timestamp AS fecha_firma,
            bh.ruta_firma::text AS ruta_firma,
            COALESCE(bh.flag_firma, 0) AS flag,
            CASE WHEN p_estado = 'F' THEN
                'Firmado el ' || CASE
                    WHEN bh.fecha_firma ~ '^\d{2}-\d{2}-\d{4}'
                    THEN to_char(to_timestamp(bh.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'), 'DD/MM/YYYY HH24:MI')
                    ELSE COALESCE(bh.fecha_firma::text, '')
                END
            ELSE
                TRIM(BOTH ', ' FROM CONCAT_WS(', ',
                    CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - bh.created_at))) > 0 THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - bh.created_at)))::text || ' Año(s)' END,
                    CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - bh.created_at))) > 0 THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - bh.created_at)))::text || ' Mes(es)' END,
                    CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - bh.created_at))) > 0 THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - bh.created_at)))::text || ' Día(s)' END,
                    CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - bh.created_at))) > 0 THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - bh.created_at)))::text || ' Hora(s)' END,
                    CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - bh.created_at))) > 0 THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - bh.created_at)))::text || ' Minuto(s)' END
                )) || ' Sin Firmar'
            END AS tiempo_firma,
            bh.created_at AS orden_fecha
        FROM balance_hidricos bh
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = bh.id_enfermeria
        JOIN users u1 ON u1.id = COALESCE(bh.updated_us, bh.created_us)
        WHERE (
                (p_estado = 'P' AND (bh.flag_firma IS NULL OR bh.flag_firma <> 1))
                OR
                (p_estado = 'F' AND bh.flag_docsinfirmar = 1 AND bh.fecha_firma IS NOT NULL)
              )
          AND (
                (p_estado = 'P' AND bh.created_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
                OR
                (p_estado = 'F' AND CASE
                    WHEN bh.fecha_firma ~ '^\d{2}-\d{2}-\d{4}'
                    THEN to_timestamp(bh.fecha_firma, 'DD-MM-YYYY HH24:MI:SS')::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
                    ELSE false
                END)
              )
          AND (p_id_user IS NULL OR u1.id = p_id_user)
          AND (p_dni_beneficiario IS NULL OR ec.nro_doc_ident = p_dni_beneficiario)

        UNION ALL

        -- 5) EVOLUCIÓN DE ENFERMERÍA
        SELECT
            u1.id AS id_usuario,
            ev.id::integer AS id_principal,
            ev.id_prestacion::integer AS id_prestacion,
            'EVOLUCION DE ENFERMERIA'::text AS documento_por_firmar,
            ec.id_tipo_atencion, ec.tipo_atencion, ec.id_establecimiento,
            ec.tipo_doc_ident, ec.nro_doc_ident, ec.nombre_paciente, ec.edad, ec.sexo, ec.servicio, ec.id_asegurado,
            to_char(ev.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion,
            (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text AS usuario_medico,
            NULL::timestamp AS fecha_firma,
            ev.ruta_firma::text AS ruta_firma,
            COALESCE(ev.flag_firma, 0) AS flag,
            CASE WHEN p_estado = 'F' THEN
                'Firmado el ' || CASE
                    WHEN ev.fecha_firma ~ '^\d{2}-\d{2}-\d{4}'
                    THEN to_char(to_timestamp(ev.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'), 'DD/MM/YYYY HH24:MI')
                    ELSE COALESCE(ev.fecha_firma::text, '')
                END
            ELSE
                TRIM(BOTH ', ' FROM CONCAT_WS(', ',
                    CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - ev.created_at))) > 0 THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - ev.created_at)))::text || ' Año(s)' END,
                    CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - ev.created_at))) > 0 THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - ev.created_at)))::text || ' Mes(es)' END,
                    CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - ev.created_at))) > 0 THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - ev.created_at)))::text || ' Día(s)' END,
                    CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - ev.created_at))) > 0 THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - ev.created_at)))::text || ' Hora(s)' END,
                    CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - ev.created_at))) > 0 THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - ev.created_at)))::text || ' Minuto(s)' END
                )) || ' Sin Firmar'
            END AS tiempo_firma,
            ev.created_at AS orden_fecha
        FROM nota_enfermeria_evoluciones ev
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = ev.id_enfermeria
        JOIN users u1 ON u1.id = ev.id_usuario_registro
        WHERE (
                (p_estado = 'P' AND (ev.flag_firma IS NULL OR ev.flag_firma <> 1))
                OR
                (p_estado = 'F' AND ev.flag_docsinfirmar = 1 AND ev.fecha_firma IS NOT NULL)
              )
          AND (
                (p_estado = 'P' AND ev.created_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
                OR
                (p_estado = 'F' AND CASE
                    WHEN ev.fecha_firma ~ '^\d{2}-\d{2}-\d{4}'
                    THEN to_timestamp(ev.fecha_firma, 'DD-MM-YYYY HH24:MI:SS')::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
                    ELSE false
                END)
              )
          AND (p_id_user IS NULL OR u1.id = p_id_user)
          AND (p_dni_beneficiario IS NULL OR ec.nro_doc_ident = p_dni_beneficiario)

        UNION ALL

        -- 6) CPT
        SELECT
            u1.id AS id_usuario,
            cpt.id::integer AS id_principal,
            NULL::integer AS id_prestacion,
            'CPT'::text AS documento_por_firmar,
            ec.id_tipo_atencion, ec.tipo_atencion, ec.id_establecimiento,
            ec.tipo_doc_ident, ec.nro_doc_ident, ec.nombre_paciente, ec.edad, ec.sexo, ec.servicio, ec.id_asegurado,
            to_char(cpt.created_at, 'DD/MM/YYYY HH24:MI:SS') AS fecha_atencion,
            (u1.paterno || ' ' || u1.materno || ', ' || u1.name)::text AS usuario_medico,
            NULL::timestamp AS fecha_firma,
            cpt.ruta_firma::text AS ruta_firma,
            COALESCE(cpt.flag_firma, 0) AS flag,
            CASE WHEN p_estado = 'F' THEN
                'Firmado el ' || CASE
                    WHEN cpt.fecha_firma ~ '^\d{2}-\d{2}-\d{4}'
                    THEN to_char(to_timestamp(cpt.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'), 'DD/MM/YYYY HH24:MI')
                    ELSE COALESCE(cpt.fecha_firma::text, '')
                END
            ELSE
                TRIM(BOTH ', ' FROM CONCAT_WS(', ',
                    CASE WHEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - cpt.created_at))) > 0 THEN EXTRACT(YEAR FROM justify_hours(justify_days(now() - cpt.created_at)))::text || ' Año(s)' END,
                    CASE WHEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - cpt.created_at))) > 0 THEN EXTRACT(MONTH FROM justify_hours(justify_days(now() - cpt.created_at)))::text || ' Mes(es)' END,
                    CASE WHEN EXTRACT(DAY FROM justify_hours(justify_days(now() - cpt.created_at))) > 0 THEN EXTRACT(DAY FROM justify_hours(justify_days(now() - cpt.created_at)))::text || ' Día(s)' END,
                    CASE WHEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - cpt.created_at))) > 0 THEN EXTRACT(HOUR FROM justify_hours(justify_days(now() - cpt.created_at)))::text || ' Hora(s)' END,
                    CASE WHEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - cpt.created_at))) > 0 THEN EXTRACT(MINUTE FROM justify_hours(justify_days(now() - cpt.created_at)))::text || ' Minuto(s)' END
                )) || ' Sin Firmar'
            END AS tiempo_firma,
            cpt.created_at AS orden_fecha
        FROM nota_enfermeria_cpt cpt
        JOIN cpt_contexto ec ON ec.id_cpt = cpt.id
        JOIN users u1 ON u1.id = cpt.id_usuario_registro
        WHERE (
                (p_estado = 'P' AND COALESCE(cpt.flag_firma, 0) = 0)
                OR
                (p_estado = 'F' AND cpt.flag_docsinfirmar = 1 AND cpt.fecha_firma IS NOT NULL)
              )
          AND cpt.estado = 1
          AND (
                (p_estado = 'P' AND cpt.created_at::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date)
                OR
                (p_estado = 'F' AND CASE
                    WHEN cpt.fecha_firma ~ '^\d{2}-\d{2}-\d{4}'
                    THEN to_timestamp(cpt.fecha_firma, 'DD-MM-YYYY HH24:MI:SS')::date BETWEEN p_fecha_ini::date AND p_fecha_fin::date
                    ELSE false
                END)
              )
          AND (p_id_user IS NULL OR u1.id = p_id_user)
          AND (p_dni_beneficiario IS NULL OR ec.nro_doc_ident = p_dni_beneficiario)
    ),
    conteo AS (
        SELECT COUNT(*)::integer AS total FROM notas
    ),
    filtradas AS (
        SELECT *
        FROM notas
        ORDER BY orden_fecha ASC
        LIMIT p_limit
        OFFSET (p_pagina - 1) * p_limit
    )
    SELECT
        f.id_usuario, f.id_principal, f.id_prestacion, f.documento_por_firmar,
        f.id_tipo_atencion, f.tipo_atencion, f.id_establecimiento,
        f.tipo_doc_ident, f.nro_doc_ident, f.nombre_paciente, f.edad, f.sexo, f.servicio, f.id_asegurado,
        f.fecha_atencion, f.usuario_medico, f.fecha_firma, f.ruta_firma, f.flag, f.tiempo_firma, f.orden_fecha,
        c.total AS totalrows
    FROM filtradas f, conteo c;
END;
$function$



-- CAMBIOS EN LAS TABLAS ENFERMERÍA
ALTER TABLE public.enfermerias ADD COLUMN flag_docsinfirmar int DEFAULT 0;
ALTER TABLE public.signo_vitales ADD COLUMN flag_docsinfirmar int DEFAULT 0;
ALTER TABLE public.kardex_enfermerias ADD COLUMN flag_docsinfirmar int DEFAULT 0;
ALTER TABLE public.balance_hidricos ADD COLUMN flag_docsinfirmar int DEFAULT 0;
ALTER TABLE public.nota_enfermeria_evoluciones ADD COLUMN flag_docsinfirmar int DEFAULT 0;
ALTER TABLE public.nota_enfermeria_cpt ADD COLUMN flag_docsinfirmar int DEFAULT 0;
