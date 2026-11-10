-- DROP FUNCTION public.sp_listar_pacientes(varchar, varchar, varchar, varchar, varchar, refcursor);

CREATE OR REPLACE FUNCTION public.sp_listar_pacientes(p_pagina character varying, p_limit character varying, p_filtro character varying, p_dni character varying, p_nombre character varying, p_ref refcursor)
 RETURNS refcursor
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_scad varchar;
    v_campos varchar;
    v_tabla varchar;
    v_count varchar;
    v_col_count varchar;
    v_where varchar;
    v_offset integer;
BEGIN

    v_offset := (p_pagina::integer - 1) * p_limit::integer;

    CREATE TEMPORARY TABLE tbl_asegurados ON COMMIT DROP AS
    SELECT
        ROW_NUMBER() OVER (ORDER BY ase.id DESC) AS row_number,
        ase.id,
        ase.id_tipo_doc_ident,
        ase.tipo_doc_ident,
        ase.nro_doc_ident,
        ase.cip,
        ase.nombre,
        ase.paterno,
        ase.materno,
        ase.sexo,
        ase.fecha_nac,
        ase.telefono,
        ase.domicilio,
        ase.correo,
        ase.id_estado_reg,
        CONCAT(ase.paterno, ' ', ase.materno, ' ', ase.nombre) AS nombres_paciente,
        CASE
            WHEN ase.id_estado_reg = 1 THEN 'ACTIVO'
            ELSE 'INACTIVO'
        END AS descripcion_estado,
        ase.condicion_asegurado,
        tb.descripcion AS id_tipo_beneficiario,
		ase.flag_bloqueo_atencion
    FROM asegurados ase
    LEFT JOIN tipo_beneficiarios tb ON tb.id = ase.id_tipo_beneficiario
    WHERE ase.id_estado_reg in(1,0);

    v_campos := ' row_number, ase.id, ase.id_tipo_doc_ident, ase.tipo_doc_ident,
                  ase.nro_doc_ident, ase.cip, ase.nombre, ase.paterno, ase.materno,
                  ase.sexo, ase.fecha_nac, ase.telefono, ase.domicilio, ase.correo,
                  ase.id_estado_reg as estado, descripcion_estado, nombres_paciente,
                  ase.condicion_asegurado, ase.id_tipo_beneficiario, ase.flag_bloqueo_atencion ';

    v_tabla := ' FROM tbl_asegurados ase ';
    v_where := ' WHERE 1=1 ';

    IF p_dni IS NOT NULL AND p_dni <> '' THEN
        v_where := v_where || ' AND ase.nro_doc_ident ILIKE ''%' || p_dni || '%'' ';
    END IF;

    IF p_nombre IS NOT NULL AND p_nombre <> '' THEN
        v_where := v_where || ' AND ase.nombres_paciente ILIKE ''%' || p_nombre || '%'' ';
    END IF;

    IF p_filtro IS NOT NULL AND p_filtro <> '' THEN
        v_where := v_where || ' AND (
            ase.nro_doc_ident ILIKE ''%' || p_filtro || '%'' OR
            ase.nombres_paciente ILIKE ''%' || p_filtro || '%'' OR
            ase.cip ILIKE ''%' || p_filtro || '%''
        ) ';
    END IF;

    EXECUTE 'SELECT count(1) ' || v_tabla || v_where INTO v_count;

    v_col_count := ' , ' || v_count || ' AS TotalRows ';

    IF v_count::integer > p_limit::integer THEN
        v_scad := 'SELECT ' || v_campos || v_col_count || v_tabla ||
                  v_where || ' ORDER BY ase.id DESC LIMIT ' || p_limit ||
                  ' OFFSET ' || v_offset || ';';
    ELSE
        v_scad := 'SELECT ' || v_campos || v_col_count || v_tabla ||
                  v_where || ' ORDER BY ase.id DESC;';
    END IF;

    OPEN p_ref FOR EXECUTE v_scad;
    RETURN p_ref;

END
$function$
;
