--DROP FUNCTION public.fn_listar_radiologias_filtros(int4, date, date, int4, text, int4, text);

CREATE OR REPLACE FUNCTION public.fn_listar_radiologias_filtros(
    p_id_establecimiento integer,
    p_fecha_inicio date,
    p_fecha_fin date,
    p_id_tipo_atencion integer DEFAULT NULL::integer,
    p_nro_doc_ident text DEFAULT NULL::text,
    p_servicio_imagenes integer DEFAULT NULL::integer,
    p_codigo_cpms text DEFAULT NULL::text
)
 RETURNS TABLE(id_ext integer, id_pp integer, id_evaluacion integer, id_rad integer, establecimiento text, fecha_orden date, hora_orden time without time zone, fecha_cita_radiologia date, hora_cita_radiologia time without time zone, id_estado_cita integer, nombre_estado_cita text, fecha_toma_muestra date, hora_toma_muestra time without time zone, fecha_validacion_examen date, hora_validacion_examen time without time zone, fecha_firma_informe date, hora_firma_informe time without time zone, cantidad_adendas_rad integer, id_tipo_atencion_prestacion integer, nombre_tipo_atencion text, consultorio text, consultorio_nombre text, nom_servicio text, codigo_cpms text, descripcion_cpms text, tipo_doc_ident text, nro_doc_ident text, nombre_completo text, parentesco text, fecha_de_nacimiento date, sexo text, edad_completa text, estado_toma text, estado_informe_detalle text, referido text, id_evolucion_emergencia integer, fecha_ev_atencion_emergencia timestamp without time zone, id_evolucion_hospitalizacion integer, fecha_ev_atencion_hospitalizacion timestamp without time zone, codigo_diag text, nombre_diag text, sub_consultorio_desc text, medico_solicitante text, lado text)
 LANGUAGE plpgsql
AS $function$
BEGIN
    RETURN QUERY
    SELECT
        p.id_ext,
        pp.id::integer,
        pp.id_evaluacion,
        r.id,
        e.nombre::text,
        CASE
            WHEN ta.id = 1 THEN r.created_at::date
            WHEN ta.id = 2 AND pp.id_evaluacion != 0 AND pp.id_evaluacion IS NOT NULL THEN ev_emg.fecha_atencion::date
            WHEN ta.id = 3 AND pp.id_evaluacion != 0 AND pp.id_evaluacion IS NOT NULL THEN ev_hsp.fecha_atencion::date
            ELSE r.created_at::date
        END AS fecha_orden,
        CASE
            WHEN ta.id = 1 THEN r.created_at::time
            WHEN ta.id = 2 AND pp.id_evaluacion != 0 AND pp.id_evaluacion IS NOT NULL THEN ev_emg.fecha_atencion::time
            WHEN ta.id = 3 AND pp.id_evaluacion != 0 AND pp.id_evaluacion IS NOT NULL THEN ev_hsp.fecha_atencion::time
            ELSE r.created_at::time
        END AS hora_orden,
        c.fecha_hora::date,
        c.fecha_hora::time,
        c.id_estado_cita::int,
        CASE
            WHEN c.id_estado_cita = 1  THEN 'Programado'::text
            WHEN c.id_estado_cita = 2  THEN 'Confirmado'::text
            WHEN c.id_estado_cita = 3  THEN 'Derivado'::text
            WHEN c.id_estado_cita = 4  THEN 'Atendido'::text
            WHEN c.id_estado_cita = 5  THEN 'Anulado'::text
            WHEN c.id_estado_cita = 6  THEN 'Reservado'::text
            WHEN c.id_estado_cita = 7  THEN 'Observado'::text
            WHEN c.id_estado_cita = 8  THEN 'Migrado'::text
            WHEN c.id_estado_cita = 9  THEN 'Atendido en Triaje'::text
            WHEN c.id_estado_cita = 10 THEN 'Re-Programado'::text
            ELSE 'Sin cita'::text
        END AS nombre_estado_cita,
        pp.confirmar_toma_created_at::date,
        pp.confirmar_toma_created_at::time,
        pp.validated_at::date,
        pp.validated_at::time,
        pp.fecha_firma::date,
        pp.fecha_firma::time,
        pp.cantidad_adendas_rad::int,
        ta.id,
        ta.nombre::text,
        CASE
            WHEN ta.id = 1 THEN co.nombre::text
            WHEN ta.id = 2 THEN cone.nombre::text
            WHEN ta.id = 3 THEN conh.nombre::text
        END::text,
        co.nombre::text,
        cr.nombre::text,
        r2.codigo_rad::text,
        r2.descripcion_corta::text,
        a.tipo_doc_ident::text,
        a.nro_doc_ident::text,
        (a.paterno || ' ' || a.materno || ' ' || a.nombre)::text,
        CASE
            WHEN tb.descripcion IN ('HIJO MENOR DE EDAD','HIJO MAYOR DE EDAD','HIJO INCAPACITADO','HIJO NO INCAPACITADO','HIJO SOBREVIVIENTE','HIJO INCAPACITADO SOBREVIVIENTE','HIJO NO INCAPACITADO SOBREVIVIENTE','HIJO ESTUDIANTE','HIJO MAYOR DE EDAD NO ESTUDIANTE') THEN 'HIJO'::text
            ELSE tb.descripcion::text
        END AS parentesco,
        a.fecha_nac::date,
        a.sexo::text,
        (
          EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date))  || ' años, '  ||
          EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date)) || ' meses, ' ||
          EXTRACT(DAY FROM age(current_date, a.fecha_nac::date))   || ' días'
        )::text,
        CASE WHEN pp.confirmar_toma_created_at IS NOT NULL THEN 'Realizado toma'::text
             ELSE 'Pendiente toma'::text END,
        CASE WHEN pp.validated_at IS NOT NULL THEN 'Realizado informe'::text
             ELSE 'Pendiente informe'::text END,
        CASE WHEN c.referido IS NULL OR c.referido = '0' OR c.referido = '' THEN 'NO'::text
             ELSE 'SI'::text
        END AS referido,
        ev_emg.id,
        ev_emg.fecha_atencion,
        ev_hsp.id,
        ev_hsp.fecha_atencion,
        d.codigo::text,
        d.nombre::text,
        sc.descripcion::text AS sub_consultorio_desc,
        concat(u.paterno || ' ' || u.materno || ', ' || u.name)::text AS medico_solicitante,
        -- NUEVO 04-11-2026: lado (1 = Izquierdo, 2 = Derecho, 3 = Ambos)
        CASE pp.lado_radio::text
            WHEN '1' THEN 'Izquierdo'::text
            WHEN '2' THEN 'Derecho'::text
            WHEN '3' THEN 'Ambos'::text
            ELSE ''::text
        END AS lado
    FROM radiologias r
        INNER JOIN prestacion_procedimientos pp   ON pp.id_lab_rad = r.id AND pp.tipo_procedimiento = 3 AND pp.id_estado_reg = 1
        INNER JOIN prestaciones p                 ON pp.id_prestacion = p.id AND p.id_estado_reg = 1
        INNER JOIN radnivelatenciones r2          ON pp.id_rua = r2.id AND r2.estado = 1
        INNER JOIN categoria_radiologias cr       ON r2.id_categoria = cr.id
        INNER JOIN tipo_atenciones ta             ON p.id_tipo_atencion = ta.id
        INNER JOIN asegurados a                   ON p.id_asegurado = a.id
        INNER JOIN tipo_beneficiarios tb          ON a.id_tipo_beneficiario = tb.id
        INNER JOIN procedimientos p2              ON pp.id_procedimiento = p2.id
        LEFT JOIN emergencias e2                  ON p.id_ext = e2.id
        LEFT JOIN triajes t                       ON e2.id_triaje = t.id
        LEFT JOIN evaluaciones ev_emg             ON pp.id_evaluacion = ev_emg.id AND ev_emg.id_estado_reg = 1
        LEFT JOIN evaluaciones ev_hsp             ON ev_hsp.id = r.id_evaluacion
        LEFT JOIN citas c                         ON pp.id_citas_multiples_rad = c.id AND c.id_estado_reg = 1
        LEFT JOIN citas ci                        ON ci.id = p.id_cita
        LEFT JOIN sub_consultorios sc             ON ci.id_sub_consultorio = sc.id
        LEFT JOIN consultorios c2                 ON sc.id_consultorio = c2.id
        LEFT JOIN consultorios conh               ON p.id_servicio = conh.id
        INNER JOIN sub_consultorios               ON p.id_sub_consultorio = sub_consultorios.id
        INNER JOIN consultorios co                ON sub_consultorios.id_consultorio = co.id
        LEFT JOIN consultorios cone               ON cone.id = t.id_servicio
        LEFT JOIN citas_llamados cl               ON cl.id_cita = c.id
        LEFT JOIN establecimientos e              ON p.id_establecimiento = e.id
        LEFT JOIN diagnosticos d                  ON d.id = pp.id_diagnostico
        LEFT JOIN users u                         ON u.id = pp.created_us::integer
    WHERE ((p.id_tipo_atencion = 1 AND c.id_estado_cita NOT IN (5,7))
           OR (p.id_tipo_atencion <> 1 AND (c.id_estado_cita NOT IN (5,7) OR c.id_estado_cita IS NULL)))
        AND r.fecha_atencion IS NULL
        AND r.estado = 1
        AND r.fecha_firma IS NOT NULL
        AND r.fecha_firma <> ''
        AND pp.confirmar_toma_created_at IS NOT NULL  -- NUEVO 21-01-2026
        AND r.id_establecimiento = p_id_establecimiento
        AND (p_nro_doc_ident IS NULL OR a.nro_doc_ident = p_nro_doc_ident)
        AND (p_id_tipo_atencion IS NULL OR p.id_tipo_atencion = p_id_tipo_atencion)
        -- OFICIO N° 000052-2026-CG-DIRSAPOL-SUBDSP-CHPNPLNS-DIVADT - 27-02-2026
        AND ((p_fecha_inicio IS NULL AND p_fecha_fin IS NULL)
             OR (pp.confirmar_toma_created_at >= p_fecha_inicio
                 AND pp.confirmar_toma_created_at < (p_fecha_fin::timestamp + INTERVAL '1 day')))
        AND (p_codigo_cpms IS NULL OR r2.codigo_rad = p_codigo_cpms)
        AND (p_servicio_imagenes IS NULL OR p_servicio_imagenes = 0 OR cr.id_servicio = p_servicio_imagenes)
    ORDER BY r.created_at DESC;
END;
$function$;


--AGREGAR COLUMNAS OBSERVACION EN LA TABLA PREST_PROC
ALTER TABLE public.prestacion_procedimientos ADD COLUMN obs_imagenes    text      NULL;
ALTER TABLE public.prestacion_procedimientos ADD COLUMN obs_imagenes_us int4      NULL;
ALTER TABLE public.prestacion_procedimientos ADD COLUMN obs_imagenes_at timestamp NULL;

COMMENT ON COLUMN public.prestacion_procedimientos.obs_imagenes    IS 'Motivo de observación del examen de imágenes (opción del modal u "Otros: <texto>")';
COMMENT ON COLUMN public.prestacion_procedimientos.obs_imagenes_us IS 'Usuario que registró la observación del examen';
COMMENT ON COLUMN public.prestacion_procedimientos.obs_imagenes_at IS 'Fecha y hora en que se registró la observación del examen';
