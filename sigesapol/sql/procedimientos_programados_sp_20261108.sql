-- TABLA PARA ALMACENAR SOLICITUDES DE PROGRAMACIÓN PROCEDIMIENTO
CREATE TABLE public.solicitud_programacion_procedimientos (
    id serial4 NOT NULL,
    id_prestacion int4 NOT NULL,
    id_tipo_atencion int4 NOT NULL,
    fecha_solicitud timestamp DEFAULT now() NULL,
    created_at timestamp DEFAULT now() NOT NULL,
    created_us int4 NOT NULL,
    updated_at timestamp NULL,
    updated_us int4 NULL,
    programado bool DEFAULT false NOT NULL,
    asegurado_dni varchar(8) DEFAULT ''::character varying NOT NULL,
    tiene_pendientes bool DEFAULT true NOT NULL,
    flag int4 NULL,
    fecha_firma timestamp NULL,
    ruta_firma varchar NULL,
    id_estado_reg int2 DEFAULT 1 NOT NULL,
    fecha_anulacion timestamp NULL,
    anulado_us int4 NULL,
    CONSTRAINT solicitud_programacion_procedimientos_pkey PRIMARY KEY (id)
);

CREATE INDEX idx_solicitud_pp_fecha_solicitud ON public.solicitud_programacion_procedimientos (fecha_solicitud);
CREATE INDEX idx_solicitud_pp_id_prestacion ON public.solicitud_programacion_procedimientos (id_prestacion);
ALTER TABLE public.solicitud_programacion_procedimientos ADD CONSTRAINT fk_prestacion FOREIGN KEY (id_prestacion) REFERENCES public.prestaciones(id);
COMMENT ON COLUMN public.solicitud_programacion_procedimientos.id_estado_reg IS '1: activa, 0: anulada';

-- TABLA PARA ATENDER LOS PROCEDIMIENTOS PROGRAMADOS
CREATE TABLE public.solicitud_procedimientos_detalle (
	id serial4 NOT NULL,
	id_solicitud int4 NOT NULL,
	id_procedimiento int4 NOT NULL,
	cantidad int4 DEFAULT 1 NOT NULL,
	indicacion text NULL,
	created_at timestamp DEFAULT now() NOT NULL,
	created_us int4 NOT NULL,
	updated_at timestamp NULL,
	updated_us int4 NULL,
	estado int4 DEFAULT 1 NOT NULL,
	id_diagnostico int4 NOT NULL,
	fecha_atencion timestamp NULL,
	id_medico_atencion int4 NULL,
	atendido_us int4 NULL,
	observacion_atencion text NULL,
	id_cita_reevaluacion int4 NULL,
	fecha_anulacion timestamp NULL,
	motivo_anulacion text NULL,
	anulado_us int4 NULL,
	CONSTRAINT solicitud_procedimientos_detalle_pkey PRIMARY KEY (id)
);

CREATE INDEX idx_sol_proc_detalle_id_solicitud ON solicitud_procedimientos_detalle USING btree (id_solicitud);
ALTER TABLE public.solicitud_procedimientos_detalle ADD CONSTRAINT fk_procedimiento FOREIGN KEY (id_procedimiento) REFERENCES public.procedimientos(id);
ALTER TABLE public.solicitud_procedimientos_detalle ADD CONSTRAINT fk_solicitud_programacion FOREIGN KEY (id_solicitud) REFERENCES public.solicitud_programacion_procedimientos(id);
ALTER TABLE public.solicitud_procedimientos_detalle ADD CONSTRAINT fk_solicitud_programacion_detalle_diagnostico FOREIGN KEY (id_diagnostico) REFERENCES public.diagnosticos(id);

-- AGREGAR LA TABLA DE CONSIGURACIÓN TURNOS PARA PROCEDIMIENTOS PROGRAMADOS
CREATE TABLE procedimientos_consultorio_turnos (
  id                 serial PRIMARY KEY,
  id_sub_consultorio integer NOT NULL REFERENCES sub_consultorios(id),
  id_turno           integer NOT NULL REFERENCES turnos(id),
  dia                varchar(15) NOT NULL,
  hora_ini           varchar(10) NOT NULL,
  hora_fin           varchar(10) NOT NULL,
  tiempo_atencion    integer,
  fecha_inicio       date,
  fecha_fin          date,
  id_estado_reg      smallint NOT NULL DEFAULT 1,
  created_at         timestamp,
  created_us         integer,
  updated_at         timestamp,
  updated_us         integer
);
COMMENT ON COLUMN procedimientos_consultorio_turnos.id_estado_reg IS '1 activo, 3 desactivado, 0 eliminado';

-- AGREGAR LA CONFIGURACIÓN DE PROCEDIMIENTOS POR SERVICIO
CREATE TABLE procedimientos_servicios (
  id               serial PRIMARY KEY,
  id_procedimiento integer NOT NULL REFERENCES procedimientos(id),
  id_servicio      integer NOT NULL REFERENCES consultorios(id),
  orden            integer,
  id_estado_reg    smallint NOT NULL DEFAULT 1,
  created_at       timestamp,
  created_us       integer,
  updated_at       timestamp,
  updated_us       integer,
  CONSTRAINT uq_procedimientos_servicios UNIQUE (id_procedimiento, id_servicio)
);

-- AGREGAR A SUBCONSULTORIOS UNA COLUMNA PARA PROCEDIMIENTOS PROGRAMADOS
ALTER TABLE sub_consultorios ADD COLUMN flag_procedimiento smallint NOT NULL DEFAULT 0;

--AGREGAR UNA COLUMNA A PROCEDIMIENTOS
ALTER TABLE procedimientos ADD COLUMN requiere_cita smallint NOT NULL DEFAULT 0;
ALTER TABLE procedimientos ADD COLUMN updated_at_req_cita timestamp;
ALTER TABLE procedimientos ADD COLUMN updated_us_req_cita integer;

-- CAMBIO SP PRESTACION PAGINADO
CREATE OR REPLACE FUNCTION public.sp_prestacion_paginado(p_fecha_ini character varying, p_fecha_fin character varying, p_dni_beneficiario character varying, p_nombre_beneficiario character varying, p_id_servicio character varying, p_id_consultorio character varying, p_id_medico character varying, p_id_estado character varying, p_tipo_atencion character varying, p_id_establecimiento character varying, p_id_condicion_establecimiento character varying, p_id_condicion_servicio character varying, p_id_prestacion character varying, p_pagina character varying, p_limit character varying, p_ref refcursor)
 RETURNS refcursor
 LANGUAGE plpgsql
AS $function$
Declare
--v_id numeric;
--v_numinf character varying;
v_scad varchar;
v_campos varchar;
v_tabla varchar;
v_where varchar;
v_count varchar;
v_col_count varchar;
--v_perfil varchar;

Begin

	p_pagina=(p_pagina::Integer-1)*p_limit::Integer;

	v_campos=' t1.id, t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t10.nro_historia,
(case when t2.sexo=''M'' then ''MASCULINO'' when ''F'' then ''FEMENINO'' end)sexo,parentesco,t9.grado,fecha_nac,tipo_doc_ident,t2.nro_doc_ident,
t11.nombre atencion,t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t12.nombre servicio,t4.descripcion consultorio,t1.id_medico,
t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t5.dni,t5.colegiatura,t5.rne,/*t6.nombre servicio,*/t7.nombre especializacion
,t8.nombre profesion, to_char(t1.created_at, ''HH:MI AM'') horario,to_char(t1.fecha_atencion, ''DD/MM/YYYY'') fecha_atencion,ruta_prestacion
,t1.condicion, t9.codigo_siteds,t9.id id_cita,
(select array_agg(rv.ruta_receta order by rv.id) from receta_vales rv where rv.id_prestacion=t1.id and rv.estado<>0)ruta_receta,
(select array_agg(rva.flag order by rva.id) from receta_vales rva where rva.id_prestacion=t1.id and rva.estado<>0)flag_receta,
(select array_agg(la.ruta_laboratorio order by la.id) from laboratorios la where la.id_prestacion=t1.id and la.estado=1)ruta_laboratorio,
(select array_agg(lab.flag order by lab.id) from laboratorios lab where lab.id_prestacion=t1.id and lab.estado<>0)flag_laboratorio,
(select array_agg(l.id) from laboratorios l where l.id_prestacion=t1.id and l.estado=1)lid,
(select array_agg(ra.ruta_radiologia order by ra.id) from radiologias ra where ra.id_prestacion=t1.id  and ra.estado=1)ruta_radiologia,
(select array_agg(rad.flag order by rad.id) from radiologias rad where rad.id_prestacion=t1.id and rad.estado<>0)flag_radiologia,
(select array_agg(r.id order by r.id) from radiologias r where r.id_prestacion=t1.id  and r.estado=1)rid,
(select array_agg(ref.ruta_referencia order by ref.id) from referencias ref where ref.id_prestacion=t1.id  and ref.id_estado_reg=1)ruta_referencia,
(select array_agg(refe.flag order by refe.id) from referencias refe where refe.id_prestacion=t1.id and refe.id_estado_reg<>0)flag_referencia,
(select array_agg(i.ruta_interconsulta order by i.id) from interconsultas i where i.id_prestacion=t1.id  and i.id_estado_reg=1)ruta_interconsulta,
(select array_agg(inter.flag order by inter.id) from interconsultas inter where inter.id_prestacion=t1.id and inter.id_estado_reg<>0)flag_interconsulta,
(select spp.id   from solicitud_programacion_procedimientos spp where spp.id_prestacion=t1.id and spp.id_estado_reg=1 order by spp.id desc limit 1) pp_id,
(select spp.flag from solicitud_programacion_procedimientos spp where spp.id_prestacion=t1.id and spp.id_estado_reg=1 order by spp.id desc limit 1) pp_flag,
(select spp.ruta_firma from solicitud_programacion_procedimientos spp where spp.id_prestacion=t1.id and spp.id_estado_reg=1 order by spp.id desc limit 1) pp_ruta_firma,
(select array_agg(sp1.id order by sp1.id) from solicitud_programacion_procedimientos sp1 where sp1.id_prestacion=t1.id and sp1.id_estado_reg=1) pp_ids,
(select array_agg(coalesce(sp2.flag,0) order by sp2.id) from solicitud_programacion_procedimientos sp2 where sp2.id_prestacion=t1.id and sp2.id_estado_reg=1) pp_flags,
(select array_agg(coalesce(sp3.ruta_firma,'''') order by sp3.id) from solicitud_programacion_procedimientos sp3 where sp3.id_prestacion=t1.id and sp3.id_estado_reg=1) pp_rutas_firma,

t1.id_estado_reg,
to_char(t2.fecha_nac::date,''DD/MM/YYYY'')fecha_nac,date_part(''year'',age(to_date(to_char(t2.fecha_nac::date,''DD/MM/YYYY''),''DD/MM/YYYY''))) edadafiliado,
t13.paterno||'' ''||t13.materno||'', ''||t13.name usuario_cita,t9.parentesco, t13.status_token,t1.flag,
case when t2.sexo=''M'' then ''MASCULINO'' when t2.sexo=''F'' then ''FEMENINO'' else ''MASCULINO'' end sexo,
t1.estado_update, t1.ruta_firma_update, t1.flag_firma_update, t1.id_profesional_update, m.paterno profesional_paterno_update, m.materno profesional_materno_update,
m.nombre profesional_nombre_update, to_char(t1.fecha_hora_update, ''DD/MM/YYYY HH:MI AM'') fecha_hora_update,
case when t1.fecha_atencion between ''20230901'' and ''20231231'' and t1.id_tipo_atencion = 1 then ''1'' else ''0'' end para_modificar,
(case when (select count(*)
FROM prestaciones t1_
inner join sub_consultorios t4_ on t1_.id_sub_consultorio=t4_.id
inner join consultorios t7_ on t4_.id_consultorio=t7_.id
where t1_.id_estado_reg=1 and t1_.id_asegurado=t1.id_asegurado and t4_.id_consultorio=t4.id_consultorio  and t1_.fecha_atencion <= '''||p_fecha_fin||' :23:59''
)>1 Then ''CONTINUADOR'' else ''NUEVO''end) condicion_servicio,

(case when (select count(*)
FROM prestaciones t1_
where t1_.id_estado_reg=1 and t1_.id_asegurado=t1.id_asegurado and t1_.id_establecimiento=t1.id_establecimiento
)>1 Then ''CONTINUADOR'' else ''NUEVO''end) condicion_establecimiento,
g.baja_ruta_firma, g.alta_ruta_firma, t4.id_consultorio, g.prestacion_id, g.alta, g.baja
, t12.id consultorio_id, t11.id tipo_atencion_id, t3.nivel nivel_establecimiento'; -- YMC -- 27/06/2025

	v_tabla='FROM prestaciones t1
inner join asegurados t2 on t1.id_asegurado = t2.id
inner join asegurado_historias t10 on t1.id_asegurado = t10.id_asegurado And t1.id_establecimiento = t10.id_establecimiento And t10.id_estado_reg<>''0''
inner join establecimientos t3 on t1.id_establecimiento=t3.id
inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
inner join consultorios t12 on t4.id_consultorio=t12.id
inner join medicos t5 on t1.id_medico=t5.id
inner join tipo_atenciones t11 on t1.id_tipo_atencion=t11.id
left join especializaciones t7 on t5.id_especializacion=t7.id
left join profesiones t8 on t5.id_profesion=t8.id
left join citas t9 on t1.id_cita=t9.id --And t9.id_estado_reg=1
left join users t13 on t9.created_us=t13.id
left join medicos m on t1.id_profesional_update = m.id
left join gastroenterologia g on g.prestacion_id = t1.id and coalesce(g.id_procp, 0) = 0
';

	v_where = ' Where 1=1 ';

	If p_id_prestacion<>'' Then
	 v_where:=v_where||'And t1.id = '|| p_id_prestacion;
	End If;

	If p_tipo_atencion<>'0' Then
		v_where:=v_where||'And t1.id_tipo_atencion = '''||p_tipo_atencion||'''';
	else
		v_where:=v_where||'And t1.id_tipo_atencion != 2 And t1.id_tipo_atencion != 3  ';
	End If;
	If p_id_establecimiento<>'0' Then
		v_where:=v_where||'And t3.id = '''||p_id_establecimiento||'''';
	End If;

	If p_fecha_ini<>'' Then
	 v_where:=v_where||'And t1.fecha_atencion >= '''||p_fecha_ini||' :00:00'' ';
	End If;
	If p_fecha_fin<>'' Then
	 v_where:=v_where||'And t1.fecha_atencion <= '''||p_fecha_fin||' :23:59'' ';
	End If;

	If p_dni_beneficiario<>'' Then
	 v_where:=v_where||' And t2.nro_doc_ident = '''||p_dni_beneficiario||'''';
	End If;

	If p_nombre_beneficiario<>'' Then
	 v_where:=v_where||' And UPPER(t2.paterno)  || '' '' || UPPER(t2.materno)  || '' '' || UPPER(t2.nombre) like ''%'||UPPER(p_nombre_beneficiario)||'%''';
	End If;

	If p_id_servicio<>'0' Then
	 v_where:=v_where||' And t4.id_consultorio = '|| p_id_servicio;
	End If;

	If p_id_consultorio<>'0' Then
	 v_where:=v_where||' And t1.id_sub_consultorio = '|| p_id_consultorio;
	End If;

	If p_id_medico<>'0' Then
	 v_where:=v_where||' And t1.id_medico = '|| p_id_medico;
	End If;

	If p_id_estado<>'' Then
		If p_id_estado<>'2' Then
			v_where:=v_where||' And t1.id_estado_reg = '|| p_id_estado;
		Else
			v_where:=v_where||' And t1.id_estado_reg = 1 And t1.estado_update=''1''';
		End If;
	End If;

	If p_id_condicion_servicio<>'0' Then
		v_where:=v_where||' And (case when (select count(*)
					FROM prestaciones t1_
					inner join sub_consultorios t4_ on t1_.id_sub_consultorio=t4_.id
					inner join consultorios t7_ on t4_.id_consultorio=t7_.id
					where t1_.id_estado_reg=1 and t1_.id_asegurado=t1.id_asegurado and t4_.id_consultorio=t4.id_consultorio
					)>1 Then 2 else 1 end) = '|| p_id_condicion_servicio;
	End If;

	If p_id_condicion_establecimiento<>'0' Then
		v_where:=v_where||' And (case when (select count(*)
					FROM prestaciones t1_
					where t1_.id_estado_reg=1 and t1_.id_asegurado=t1.id_asegurado and t1_.id_establecimiento=t1.id_establecimiento
					)>1 Then 2 else 1 end) = '|| p_id_condicion_establecimiento;
	End If;

	EXECUTE ('SELECT count(1) '||v_tabla||v_where) INTO v_count;
	v_col_count:=' ,'||v_count||' as TotalRows ';

	If v_count::Integer > p_limit::Integer then
		v_scad:='SELECT '||v_campos||v_col_count||v_tabla||v_where||' Order By t1.fecha_atencion Asc LIMIT '||p_limit||' OFFSET '||p_pagina||';';
	else
		v_scad:='SELECT '||v_campos||v_col_count||v_tabla||v_where||' Order By t1.fecha_atencion Asc;';
	End If;

	Raise Notice '%',v_scad;
	Open p_ref For Execute(v_scad);
	Return p_ref;
End
$function$;


-- AGREGAR LA COLUMNA Id_procedimiento_programado
ALTER TABLE receta_vales              ADD COLUMN id_procp integer;
ALTER TABLE receta_vales              ALTER COLUMN id_procp SET DEFAULT 0;
ALTER TABLE receta_diagnosticos       ADD COLUMN id_procp integer;
ALTER TABLE receta_diagnosticos       ALTER COLUMN id_procp SET DEFAULT 0;
ALTER TABLE laboratorios              ADD COLUMN id_procp integer;
ALTER TABLE laboratorios              ALTER COLUMN id_procp SET DEFAULT 0;
ALTER TABLE radiologias               ADD COLUMN id_procp integer;
ALTER TABLE radiologias               ALTER COLUMN id_procp SET DEFAULT 0;
ALTER TABLE prestacion_procedimientos ADD COLUMN id_procp integer;
ALTER TABLE prestacion_procedimientos ALTER COLUMN id_procp SET DEFAULT 0;
ALTER TABLE gastroenterologia         ADD COLUMN id_procp integer;
ALTER TABLE gastroenterologia         ALTER COLUMN id_procp SET DEFAULT 0;

--flag para gastro
ALTER TABLE gastroenterologia ADD COLUMN flag integer;
ALTER TABLE gastroenterologia ALTER COLUMN flag SET DEFAULT 0;

--AGREGAR SP "sp_listar_procprogramados_paginado" **********************************************

-- agregar columna EN CITAS
ALTER TABLE public.citas ADD id_solicitud_procedimiento_detalle int4 NULL;

-- PROCEDIMIENTO QUE NECESITA SUBIR INFORME (global por procedimiento, como requiere_cita): 0 = no, 1 = si
ALTER TABLE procedimientos ADD COLUMN requiere_informe integer;
ALTER TABLE procedimientos ALTER COLUMN requiere_informe SET DEFAULT 0;

-- INFORME PDF SUBIDO EN LA ATENCIÓN DE PROC. PROGRAMADOS (procedimientos con requiere_informe = 1)
-- El archivo se guarda en storage/app/pp_informes
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN ruta_informe varchar(255);
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN fecha_informe timestamp;
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN informe_us integer;

-- AGREGAR DESCRIPCIÓN CORTA A CADA PROCEDIMIENTO
ALTER TABLE procedimientos_servicios ADD COLUMN descripcion_corta varchar(100);

-- AGREGAR ESTABLECIMIENTO A LA CONFIGURACIÓN DE PROCEDIMIENTOS PROGRAMADOS
ALTER TABLE procedimientos_servicios ADD COLUMN id_establecimiento integer REFERENCES establecimientos(id);

--SI YA HUBIERAN PROCEDIMIENTOS CREADOS SE ACTUALIZAN AL ESTABLECIMIENTO DEL USUARIO QUE LOS CREÓ
--UPDATE procedimientos_servicios ps SET id_establecimiento = u.id_establecimiento FROM users u WHERE u.id = ps.created_us AND ps.id_establecimiento IS NULL;

--AGREGAR LA COLUMNA ESTABLECIMIENTO EN LA TABLA DE CONFIG PP 
ALTER TABLE procedimientos_servicios ALTER COLUMN id_establecimiento SET NOT NULL;
ALTER TABLE procedimientos_servicios DROP CONSTRAINT uq_procedimientos_servicios;
ALTER TABLE procedimientos_servicios ADD CONSTRAINT uq_procedimientos_servicios UNIQUE (id_procedimiento, id_servicio, id_establecimiento);

--AGREGAR COLUMNA PARA ALMACENAR QUIEN EDITA LA OBSERVACIÓN DE PP
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN observacion_editada_us integer;
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN observacion_editada_at timestamp;


--Configuración por IPRESS y servicio
ALTER TABLE procedimientos_servicios ADD COLUMN requiere_cita smallint NOT NULL DEFAULT 0;
ALTER TABLE procedimientos_servicios ADD COLUMN requiere_informe smallint NOT NULL DEFAULT 0;

--Copia la configuración antigua de cita e informe, que estaba por procedimiento en procedimientos
--UPDATE procedimientos_servicios ps SET requiere_cita = coalesce(p.requiere_cita, 0), requiere_informe = coalesce(p.requiere_informe, 0) FROM procedimientos p WHERE p.id = ps.id_procedimiento;

--Cada orden guarda los valores vigentes al solicitarse
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN requiere_cita smallint NOT NULL DEFAULT 0;
ALTER TABLE solicitud_procedimientos_detalle ADD COLUMN requiere_informe smallint NOT NULL DEFAULT 0;

UPDATE solicitud_procedimientos_detalle d
   SET requiere_cita = coalesce(p.requiere_cita, 0),
       requiere_informe = coalesce(p.requiere_informe, 0)
  FROM procedimientos p
 WHERE p.id = d.id_procedimiento;

-- DO $$
-- DECLARE
--   f record;
--   def text;
-- BEGIN
--   FOR f IN SELECT oid FROM pg_proc WHERE proname = 'sp_listar_procprogramados_paginado' LOOP
--     def := pg_get_functiondef(f.oid);
--     def := replace(def, 'pr.descripcion, pr.requiere_cita, m.paterno', 'pr.descripcion, m.paterno');
--     def := replace(def, 'HAVING pr.requiere_cita = 0 OR', 'HAVING max(d.requiere_cita) = 0 OR');
--     IF position('pr.requiere_cita' IN def) > 0 THEN
--       RAISE EXCEPTION 'sp_listar_procprogramados_paginado (oid %) sigue usando pr.requiere_cita', f.oid;
--     END IF;
--     EXECUTE def;
--   END LOOP;
-- END $$;

--CREA LA TABLA PARA ALMACENAR LOS INFORMES SUBIDOS DE PP
CREATE TABLE public.solicitud_procedimientos_informes (
	id serial NOT NULL,
	id_solicitud integer NOT NULL,
	id_procedimiento integer NOT NULL,
	ruta varchar(255) NOT NULL,
	nombre_archivo varchar(255),
	id_estado_reg smallint NOT NULL DEFAULT 1,
	created_us integer,
	created_at timestamp NOT NULL DEFAULT now(),
	deleted_us integer,
	deleted_at timestamp,
	CONSTRAINT solicitud_procedimientos_informes_pkey PRIMARY KEY (id),
	CONSTRAINT fk_sol_proc_informes_solicitud FOREIGN KEY (id_solicitud) REFERENCES public.solicitud_programacion_procedimientos(id),
	CONSTRAINT fk_sol_proc_informes_procedimiento FOREIGN KEY (id_procedimiento) REFERENCES public.procedimientos(id)
);

CREATE INDEX idx_sol_proc_informes_atencion ON public.solicitud_procedimientos_informes USING btree (id_solicitud, id_procedimiento);

-- Pasa a la tabla nueva los informes ya subidos.
--INSERT INTO public.solicitud_procedimientos_informes (id_solicitud, id_procedimiento, ruta, created_us, created_at)
--SELECT d.id_solicitud, d.id_procedimiento, d.ruta_informe, min(d.informe_us), coalesce(min(d.fecha_informe), now())
--FROM solicitud_procedimientos_detalle d
--WHERE d.ruta_informe IS NOT NULL
--GROUP BY d.id_solicitud, d.id_procedimiento, d.ruta_informe;

ALTER TABLE gastroenterologia ADD COLUMN informe_editado_us integer;
ALTER TABLE gastroenterologia ADD COLUMN informe_editado_at timestamp;
