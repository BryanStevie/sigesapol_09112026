--Tablas maestras del modulo de movimiento de historia clinica

CREATE TABLE public.movimiento_hc_canal_ingreso (
	id serial4 NOT NULL,
	nombre varchar(100) NOT NULL,
	id_estado_reg int2 DEFAULT 1 NOT NULL,
	created_at timestamp NULL,
	created_us int4 NULL,
	updated_at timestamp NULL,
	updated_us int4 NULL,
	CONSTRAINT movimiento_hc_canal_ingreso_pkey PRIMARY KEY (id)
);

CREATE TABLE public.movimiento_hc_motivo (
	id serial4 NOT NULL,
	nombre varchar(100) NOT NULL,
	id_estado_reg int2 DEFAULT 1 NOT NULL,
	created_at timestamp NULL,
	created_us int4 NULL,
	updated_at timestamp NULL,
	updated_us int4 NULL,
	CONSTRAINT movimiento_hc_motivo_pkey PRIMARY KEY (id)
);

/*
Para los tipo de documento se estan separando por tipos (tipo):
S: Significa "SOLICITUD", sirve para el registro de solicitud
D: Significa "DEVOLUCION", sirve para el registro de devoluciones
*/
CREATE TABLE public.movimiento_hc_tipo_documento (
	id serial4 NOT NULL,
	nombre varchar(100) NOT NULL,
	tipo varchar(1) NOT NULL,
	id_estado_reg int2 DEFAULT 1 NOT NULL,
	created_at timestamp NULL,
	created_us int4 NULL,
	updated_at timestamp NULL,
	updated_us int4 NULL,
	CONSTRAINT movimiento_hc_tipo_documento_pkey PRIMARY KEY (id)
);

CREATE TABLE public.movimiento_hc_lugar_archivo (
	id serial4 NOT NULL,
	nombre varchar(100) NOT NULL,
	id_estado_reg int2 DEFAULT 1 NOT NULL,
	created_at timestamp NULL,
	created_us int4 NULL,
	updated_at timestamp NULL,
	updated_us int4 NULL,
	CONSTRAINT movimiento_hc_lugar_archivo_pkey PRIMARY KEY (id)
);

CREATE TABLE public.movimiento_hc_prioridad (
	id serial4 NOT NULL,
	nombre varchar(100) NOT NULL,
	id_estado_reg int2 DEFAULT 1 NOT NULL,
	created_at timestamp NULL,
	created_us int4 NULL,
	updated_at timestamp NULL,
	updated_us int4 NULL,
	CONSTRAINT movimiento_hc_prioridad_pkey PRIMARY KEY (id)
);

--Datos iniciales
INSERT INTO public.movimiento_hc_canal_ingreso (id, nombre) VALUES
	(1, 'CITADOS DEL DÍA'),
	(2, 'OFICIO DE JEFATURA'),
	(3, 'EXPEDIENTE DE MESA DE PARTES'),
	(4, 'PEDIDO DE PISO');

INSERT INTO public.movimiento_hc_motivo (id, nombre) VALUES
	(1, 'CONSULTA'),
	(2, 'INFORME MÉDICO'),
	(3, 'JUNTA MÉDICA'),
	(4, 'COPIA'),
	(5, 'DESCANSO MÉDICO'),
	(6, 'HOSPITALIZACIÓN');

INSERT INTO public.movimiento_hc_tipo_documento (id, nombre, tipo) VALUES
	(1, 'TICKET', 'S'),
	(2, 'OFICIO', 'S'),
	(3, 'EXPEDIENTE', 'S'),
	(4, 'CARGO', 'D'),
	(5, 'OFICIO', 'D');

INSERT INTO public.movimiento_hc_lugar_archivo (id, nombre) VALUES
	(1, 'ARCHIVO ACTIVO'),
	(2, 'ARCHIVO PASIVO'),
	(3, 'ARCHIVO DE FALLECIDOS'),
	(4, 'ARCHIVO DE REFERIDOS');

INSERT INTO public.movimiento_hc_prioridad (id, nombre) VALUES
	(1, 'NORMAL'),
	(2, 'URGENTE');

--Ajusta las secuencias porque se insertaron IDs explicitos
SELECT setval(pg_get_serial_sequence('public.movimiento_hc_canal_ingreso', 'id'), (SELECT MAX(id) FROM public.movimiento_hc_canal_ingreso));
SELECT setval(pg_get_serial_sequence('public.movimiento_hc_motivo', 'id'), (SELECT MAX(id) FROM public.movimiento_hc_motivo));
SELECT setval(pg_get_serial_sequence('public.movimiento_hc_tipo_documento', 'id'), (SELECT MAX(id) FROM public.movimiento_hc_tipo_documento));
SELECT setval(pg_get_serial_sequence('public.movimiento_hc_lugar_archivo', 'id'), (SELECT MAX(id) FROM public.movimiento_hc_lugar_archivo));
SELECT setval(pg_get_serial_sequence('public.movimiento_hc_prioridad', 'id'), (SELECT MAX(id) FROM public.movimiento_hc_prioridad));

--Opcional: llaves foraneas desde movimiento_hc_solicitud
-- ALTER TABLE public.movimiento_hc_solicitud ADD CONSTRAINT movimiento_hc_solicitud_canal_ingreso_fkey FOREIGN KEY (id_canal_ingreso) REFERENCES public.movimiento_hc_canal_ingreso(id);
-- ALTER TABLE public.movimiento_hc_solicitud ADD CONSTRAINT movimiento_hc_solicitud_motivo_fkey FOREIGN KEY (id_motivo) REFERENCES public.movimiento_hc_motivo(id);
-- ALTER TABLE public.movimiento_hc_solicitud ADD CONSTRAINT movimiento_hc_solicitud_tipo_documento_fkey FOREIGN KEY (id_documento) REFERENCES public.movimiento_hc_tipo_documento(id);
-- ALTER TABLE public.movimiento_hc_solicitud ADD CONSTRAINT movimiento_hc_solicitud_lugar_archivo_fkey FOREIGN KEY (id_lugar_archivo) REFERENCES public.movimiento_hc_lugar_archivo(id);
-- ALTER TABLE public.movimiento_hc_solicitud ADD CONSTRAINT movimiento_hc_solicitud_prioridad_fkey FOREIGN KEY (id_prioridad) REFERENCES public.movimiento_hc_prioridad(id);

--Opcional: llaves foraneas desde movimiento_hc_recepcion
-- ALTER TABLE public.movimiento_hc_recepcion ADD CONSTRAINT movimiento_hc_recepcion_tipo_documento_fkey FOREIGN KEY (id_documento_devolucion) REFERENCES public.movimiento_hc_tipo_documento(id);
