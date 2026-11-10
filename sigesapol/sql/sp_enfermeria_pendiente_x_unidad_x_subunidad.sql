CREATE OR REPLACE FUNCTION public.sp_enfermeria_pendiente_x_unidad_x_subunidad(p_id_establecimiento integer,p_fecha_ini date,p_fecha_fin date,p_id_consultorio_division integer,p_id_consultorio_departamento integer,p_id_usuario integer,p_pagina integer,p_limit integer)
 RETURNS TABLE(
    enfermero character varying,
    estado_enfermero character varying,
    unidad_pertenece character varying,
    sub_unidad_pertenece character varying,
    unidad_atencion character varying,
    sub_unidad_atencion character varying,
    total_pendientes_firmas bigint,
    totalrows bigint
 )
 LANGUAGE sql
AS $function$
WITH
    enfermeria_contexto AS (
        -- AMBULATORIO
        SELECT
            enf.id AS id_enfermeria,
            enf.id_tipo_atencion,
            co.id AS id_consultorio
        FROM enfermerias enf
        JOIN citas c ON c.id = enf.id_ext_enfermeria
        JOIN sub_consultorios sc ON sc.id = c.id_sub_consultorio
        JOIN consultorios co ON co.id = sc.id_consultorio
        WHERE enf.id_tipo_atencion = 1
          AND c.id_establecimiento = p_id_establecimiento

        UNION ALL

        -- EMERGENCIA
        SELECT
            enf.id,
            enf.id_tipo_atencion,
            co.id
        FROM enfermerias enf
        JOIN emergencias e1 ON e1.id = enf.id_ext_enfermeria
        LEFT JOIN triajes t ON t.id = e1.id_triaje
        LEFT JOIN consultorios co ON co.id = t.id_servicio
        WHERE enf.id_tipo_atencion = 2
          AND e1.id_establecimiento = p_id_establecimiento

        UNION ALL

        -- HOSPITALIZACIÓN
        SELECT
            enf.id,
            enf.id_tipo_atencion,
            co.id
        FROM enfermerias enf
        JOIN hospitalizaciones h ON h.id = enf.id_ext_enfermeria
        LEFT JOIN consultorios co ON co.id = h.id_servicio_encargado
        WHERE enf.id_tipo_atencion = 3
          AND h.id_establecimiento = p_id_establecimiento
    ),
    cpt_contexto AS (
        -- CPT EMERGENCIA
        SELECT
            cpt.id AS id_cpt,
            co.id AS id_consultorio
        FROM nota_enfermeria_cpt cpt
        JOIN emergencias e1 ON e1.id = cpt.id_ext
        LEFT JOIN triajes t ON t.id = e1.id_triaje
        LEFT JOIN consultorios co ON co.id = t.id_servicio
        WHERE cpt.id_tipo_atenciones = 2
          AND e1.id_establecimiento = p_id_establecimiento

        UNION ALL

        -- CPT HOSPITALIZACIÓN
        SELECT
            cpt.id,
            co.id
        FROM nota_enfermeria_cpt cpt
        JOIN hospitalizaciones h ON h.id = cpt.id_ext
        LEFT JOIN consultorios co ON co.id = h.id_servicio_encargado
        WHERE cpt.id_tipo_atenciones = 3
          AND h.id_establecimiento = p_id_establecimiento
    ),
    base AS (
        -- 1) NOTA DE ENFERMERÍA
        SELECT
            u1.id AS id_usuario, u1.dni,
            ec.id_consultorio,
            'NOTA DE ENFERMERIA'::varchar AS dscp,
            COUNT(*)::int4 AS total_pendientes_firmas
        FROM enfermerias enf
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = enf.id
        JOIN users u1 ON u1.id = COALESCE(enf.updated_us, enf.created_us)
        WHERE enf.flag_firma IS NULL
          AND enf.id_estado_reg = 1
          AND enf.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND (ec.id_tipo_atencion <> 1 OR ec.id_consultorio NOT IN (156,105,157,266,76,265))
          AND (
                (p_id_usuario IS NULL) OR (p_id_usuario = 0) OR (p_id_usuario > 0 AND u1.id = p_id_usuario)
              )
        GROUP BY u1.id, u1.dni, ec.id_consultorio

        UNION ALL

        -- 2) SIGNOS VITALES
        SELECT
            u1.id, u1.dni, ec.id_consultorio,
            'SIGNOS VITALES'::varchar,
            COUNT(*)::int4
        FROM (
            SELECT DISTINCT ON (s.id_enfermeria)
                s.id_enfermeria,
                COALESCE(s.updated_us, s.created_us) AS id_usuario_resp,
                s.created_at AS fecha_repr
            FROM signo_vitales s
            WHERE s.flag_firma IS NULL
            ORDER BY s.id_enfermeria, s.created_at DESC
        ) sv
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = sv.id_enfermeria
        JOIN users u1 ON u1.id = sv.id_usuario_resp
        WHERE sv.fecha_repr::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND (
                (p_id_usuario IS NULL) OR (p_id_usuario = 0) OR (p_id_usuario > 0 AND u1.id = p_id_usuario)
              )
        GROUP BY u1.id, u1.dni, ec.id_consultorio

        UNION ALL

        -- 3) KARDEX
        SELECT
            u1.id, u1.dni, ec.id_consultorio,
            'KARDEX'::varchar,
            COUNT(*)::int4
        FROM kardex_enfermerias k
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = k.id_enfermeria
        JOIN users u1 ON u1.id = k.created_us
        WHERE k.flag_firma IS NULL
          AND k.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND (
                (p_id_usuario IS NULL) OR (p_id_usuario = 0) OR (p_id_usuario > 0 AND u1.id = p_id_usuario)
              )
        GROUP BY u1.id, u1.dni, ec.id_consultorio

        UNION ALL

        -- 4) BALANCE HÍDRICO
        SELECT
            u1.id, u1.dni, ec.id_consultorio,
            'BALANCE HIDRICO'::varchar,
            COUNT(*)::int4
        FROM balance_hidricos bh
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = bh.id_enfermeria
        JOIN users u1 ON u1.id = COALESCE(bh.updated_us, bh.created_us)
        WHERE (bh.flag_firma IS NULL OR bh.flag_firma <> 1)
          AND bh.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND (
                (p_id_usuario IS NULL) OR (p_id_usuario = 0) OR (p_id_usuario > 0 AND u1.id = p_id_usuario)
              )
        GROUP BY u1.id, u1.dni, ec.id_consultorio

        UNION ALL

        -- 5) EVOLUCIÓN DE ENFERMERÍA
        SELECT
            u1.id, u1.dni, ec.id_consultorio,
            'EVOLUCION DE ENFERMERIA'::varchar,
            COUNT(*)::int4
        FROM nota_enfermeria_evoluciones ev
        JOIN enfermeria_contexto ec ON ec.id_enfermeria = ev.id_enfermeria
        JOIN users u1 ON u1.id = ev.id_usuario_registro
        WHERE (ev.flag_firma IS NULL OR ev.flag_firma <> 1)
          AND ev.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND (
                (p_id_usuario IS NULL) OR (p_id_usuario = 0) OR (p_id_usuario > 0 AND u1.id = p_id_usuario)
              )
        GROUP BY u1.id, u1.dni, ec.id_consultorio

        UNION ALL

        -- 6) CPT
        SELECT
            u1.id, u1.dni, ec.id_consultorio,
            'CPT'::varchar,
            COUNT(*)::int4
        FROM nota_enfermeria_cpt cpt
        JOIN cpt_contexto ec ON ec.id_cpt = cpt.id
        JOIN users u1 ON u1.id = cpt.id_usuario_registro
        WHERE COALESCE(cpt.flag_firma, 0) = 0
          AND cpt.estado = 1
          AND cpt.created_at::date BETWEEN p_fecha_ini AND p_fecha_fin
          AND (
                (p_id_usuario IS NULL) OR (p_id_usuario = 0) OR (p_id_usuario > 0 AND u1.id = p_id_usuario)
              )
        GROUP BY u1.id, u1.dni, ec.id_consultorio
    ),
    agrupado AS (
        SELECT
            (u.dni || ' - ' || u.paterno || ' ' || u.materno || ', ' || u.name)::varchar AS enfermero,
            CASE WHEN m.estado = 1 THEN 'ACTIVO'::varchar ELSE 'INACTIVO'::varchar END AS estado_enfermero,
            (COALESCE(uni.nombre, 'OTROS'))::varchar AS unidad_pertenece,
            (COALESCE(subuni.nombre, 'OTROS'))::varchar AS sub_unidad_pertenece,
            (COALESCE(lcd.nombre, 'OTROS'))::varchar AS unidad_atencion,
            (COALESCE(lcde.nombre, 'OTROS') || ' - ' || b.dscp)::varchar AS sub_unidad_atencion,
            SUM(b.total_pendientes_firmas)::bigint AS total_pendientes_firmas
        FROM base b
        JOIN users u ON u.id = b.id_usuario
        JOIN medicos m ON m.dni = u.dni AND m.id_establecimiento = p_id_establecimiento
        LEFT JOIN tipo_consultorio_divisiones uni ON uni.id = m.id_unidad
        LEFT JOIN tipo_consultorio_departamentos subuni ON subuni.id = m.id_sub_unidad
        LEFT JOIN consultorios co1 ON co1.id = b.id_consultorio
        LEFT JOIN tipo_consultorio_divisiones lcd ON lcd.id = co1.id_consultorio_division
        LEFT JOIN tipo_consultorio_departamentos lcde ON lcde.id = co1.id_consultorio_departamento
        WHERE (
                (p_id_consultorio_division IS NULL) OR (p_id_consultorio_division = 0) OR (p_id_consultorio_division > 0 AND uni.id = p_id_consultorio_division)
              )
          AND (
                (p_id_consultorio_departamento IS NULL) OR (p_id_consultorio_departamento = 0) OR (p_id_consultorio_departamento > 0 AND subuni.id = p_id_consultorio_departamento)
              )
        GROUP BY u.dni, u.paterno, u.materno, u.name, m.estado, uni.nombre, subuni.nombre, lcd.nombre, lcde.nombre, b.dscp
    ),
    conteo AS (
        SELECT COUNT(*)::bigint AS total FROM agrupado
    ),
    paginado AS (
        SELECT *
        FROM agrupado
        ORDER BY total_pendientes_firmas DESC, enfermero, unidad_pertenece, sub_unidad_pertenece, unidad_atencion, sub_unidad_atencion
        LIMIT p_limit
        OFFSET (p_pagina - 1) * p_limit
    )
    SELECT
        p.enfermero,
        p.estado_enfermero,
        p.unidad_pertenece,
        p.sub_unidad_pertenece,
        p.unidad_atencion,
        p.sub_unidad_atencion,
        p.total_pendientes_firmas,
        c.total AS totalrows
    FROM paginado p, conteo c;
$function$;
