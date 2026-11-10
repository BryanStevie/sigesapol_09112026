--En este commit se adiciono estos 2 campo
alter table movimiento_hc_solicitud add servicio_otro varchar(50);
alter table movimiento_hc_solicitud add id_user_responsable int4;

--Tablas principales del modulo del movimiento de historia clinica
CREATE TABLE public.movimiento_hc_solicitud (
	id serial4 NOT NULL,
	numero varchar(14) NULL,
	id_establecimiento int4 NULL,
	id_servicio int4 NULL,
	id_documento int4 NULL,
	responsable varchar(150) NULL,
	estado int2 DEFAULT 1 NOT NULL,
	observacion varchar(500) NULL,
	anio varchar(4) NULL,
	created_us int4 NULL,
	created_at timestamp(0) NULL,
	updated_us int4 NULL,
	updated_at timestamp(0) NULL,
	id_canal_ingreso int4 NULL,
	id_motivo int4 NULL,
	nro_documento varchar(50) NULL,
	fecha_documento date NULL,
	cantidad_hc int4 NULL,
	id_prioridad int4 NULL,
	id_lugar_archivo int4 NULL,
	observacion_archivo varchar(500) NULL,
	fecha_hora_registro timestamp NULL,
	fecha_hora_despacho timestamp NULL,
	documento_ruta varchar(255) NULL,
	fecha_requerida date NULL,
	fecha_retorno_prevista date NULL,
	servicio_otro varchar(50) NULL,
	id_user_responsable int4 NULL,
	CONSTRAINT movimiento_hc_solicitud_pkey PRIMARY KEY (id)
);

CREATE TABLE public.movimiento_hc_detalle (
	id serial4 NOT NULL,
	id_movimiento_hc_solicitud int4 NOT NULL,
	id_asegurado_historia int4 NOT NULL,
	estado int2 NOT NULL,
	observacion_archivo_recepcion varchar(100) NULL,
	id_estado_reg int2 DEFAULT 1 NOT NULL,
	created_at timestamp NULL,
	created_us int4 NULL,
	updated_at timestamp NULL,
	updated_us int4 NULL,
	deleted_at timestamp NULL,
	deleted_us int4 NULL,
	id_movimiento_hc_recepcion int4 NULL,
	CONSTRAINT movimiento_hc_detalle_pkey PRIMARY KEY (id),
	CONSTRAINT movimiento_hc_detalle_id_movimiento_hc_recepcion_fkey FOREIGN KEY (id_movimiento_hc_recepcion) REFERENCES public.movimiento_hc_recepcion(id)
);

CREATE TABLE public.movimiento_hc_recepcion (
	id serial4 NOT NULL,
	id_movimiento_hc_solicitud int4 NOT NULL,
	id_establecimiento int4 NOT NULL,
	anio_ingreso int2 NOT NULL,
	nro_ingreso int4 NOT NULL,
	recepcion_at timestamp NULL,
	recepcion_us int4 NULL,
	persona_devuelve varchar(150) NULL,
	id_documento_devolucion int4 NULL,
	nro_documento_devolucion varchar(50) NULL,
	observacion_recepcion varchar(200) NULL,
	id_estado_reg int2 DEFAULT 1 NOT NULL,
	created_at timestamp NULL,
	created_us int4 NULL,
	updated_at timestamp NULL,
	updated_us int4 NULL,
	CONSTRAINT movimiento_hc_recepcion_pkey PRIMARY KEY (id)
);