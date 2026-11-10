create table atenciones_bloqueo_historial(
    id serial4 not null,
    id_asegurado int not null, 
    accion int2 not null, -- 1 bloqueo, 2 desbloqueo
    motivo varchar(100) not null,
    created_at timestamp without time zone not null,
    created_us int not null,
    CONSTRAINT atenciones_bloqueo_historial_pkey PRIMARY KEY (id)
);