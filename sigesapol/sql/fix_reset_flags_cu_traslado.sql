
CREATE OR REPLACE FUNCTION public.sp_insert_hospitalizacion_evolucion(p_id_hospitalizacion integer, p_id_prestacion integer, p_id_asegurado integer, p_id_tipo_atencion integer, p_fecha_hora_atencion character varying, p_id_medico integer, p_id_usuario integer, p_examen_fisico character varying, p_temperatura numeric, p_sa02_porcentaje smallint, p_presion_arterial_x smallint, p_presion_arterial_y smallint, p_talla_cm smallint, p_tamano_cintura_cm smallint, p_frecuencia_respiratoria smallint, p_peso_kg numeric, p_frecuencia_cardiaca_fetal numeric, p_frecuencia_cardiaca numeric, p_fi02 smallint, p_diuresis character varying, p_balance_hidrico character varying, p_motivo_visita character varying, p_tiempo_enfermedad character varying, p_examen_clinico character varying, p_examen_auxiliar character varying, p_tratamiento_auxiliar character varying, p_plan_trabajo character varying, p_antecedente character varying, p_antecedente_familiar character varying, p_prioridadesopciones integer, p_topicosprioridad integer, p_fecha_inicio_estado date, p_hora time without time zone, p_id_cama integer, p_diagnosticos character varying[], p_valor integer, p_cod_siteds_susalud character varying, p_apreciacion_subjetiva text, p_apreciacion_objetiva text, p_verificacion_tratamiento text, p_interpretacion_examenes text, p_procedimientos character varying[], p_flag_alta_servicio character varying, p_observacion_alta_servicio character varying, p_id_establecimiento integer, p_id_servicio_cama integer, p_tipo_cambio_cama character varying, p_id_upss_encargado integer, p_id_servicio_encargado integer, p_regularizar_datos_encargado character varying, p_id_upss_encargado_regularizar integer, p_id_servicio_encargado_regularizar integer, p_id_servicio_cama_regularizar integer)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$
/*
 * 49 parametros
 * */
declare

	p_id_evolucion integer;
	p_id_procedimiento integer;
	p_cantidad integer;
	p_id_diagnostico_proc integer;
	p_id_diagnostico integer;
	p_id_tipo_diagnostico integer;
	p_id_receta_diagnostico integer;
	p_id_categoria_diagnostico integer;
	p_id_clasificacion_diagnostico integer;
	p_id_cama_actual integer;
	p_fecha_atencion_timestamp timestamp;
	p_estado_ubicacion varchar;
	p_fecha_hora_inicio_cama timestamp;
	p_id_servicio_cama_actual integer;
	p_id_upss_encargado_actual integer;
	p_id_servicio_encargado_actual integer;
begin
	p_fecha_atencion_timestamp = cast(p_fecha_hora_atencion as timestamp);
	p_id_evolucion := nextval('evaluaciones_id_seq');

	Insert Into evaluaciones(id,id_emergencia,id_prestacion,id_asegurado, id_tipo_atencion , fecha_atencion, id_medico ,created_us, temperatura , sa02_porcentaje , presion_arterial_x ,presion_arterial_y, talla_cm, tamano_cintura_cm, frecuencia_respiratoria, peso_kg, frecuencia_cardiaca_fetal, frecuencia_cardiaca, fi02, diuresis, balance_hidrico, plan_trabajo, fecha_inicio_estado, hora_estado,cama_estado, id_estado_reg, created_at, cod_siteds_susalud,apreciacion_subjetiva,apreciacion_objetiva,verificacion_tratamiento,interpretacion_examenes, tratamiento_auxiliar)
	Values (p_id_evolucion,p_id_hospitalizacion,p_id_prestacion,p_id_asegurado, p_id_tipo_atencion, p_fecha_atencion_timestamp, p_id_medico, p_id_usuario, p_temperatura, p_sa02_porcentaje, p_presion_arterial_x, p_presion_arterial_y, p_talla_cm, p_tamano_cintura_cm, p_frecuencia_respiratoria, p_peso_kg, p_frecuencia_cardiaca_fetal, p_frecuencia_cardiaca, p_fi02, p_diuresis, p_balance_hidrico, p_plan_trabajo, p_fecha_inicio_estado,p_hora,p_id_cama, 1, now(),p_cod_siteds_susalud,p_apreciacion_subjetiva,p_apreciacion_objetiva,p_verificacion_tratamiento,p_interpretacion_examenes, p_tratamiento_auxiliar);

	if p_flag_alta_servicio = '1' then
		UPDATE prestaciones
		SET fecha_alta_servicio = p_fecha_inicio_estado,
		    hora_alta_servicio = p_hora,
		    id_cama_alta_servicio = p_id_cama,
		    observacion_alta_servicio = p_observacion_alta_servicio,
		    flag_alta_servicio = true
		WHERE id = p_id_prestacion;

		update hospitalizaciones set estado = 5 where id = p_id_hospitalizacion;

		/*if array_length(p_diagnosticos,1)>0 then
			for i in array_lower(p_diagnosticos, 1) .. array_upper( p_diagnosticos, 1)
			loop
				p_id_diagnostico := p_diagnosticos[i][1];
				p_id_tipo_diagnostico := p_diagnosticos[i][2];
				p_id_categoria_diagnostico := p_diagnosticos[i][3];
				p_id_clasificacion_diagnostico := 9; -- Alta de Servicio

				p_id_receta_diagnostico := nextval('receta_diagnosticos_id_seq');
				Insert Into receta_diagnosticos(id,id_receta_vale,id_diagnostico,id_tipo_diagnostico,estado,id_prestacion,created_at,created_us,id_evaluacion,id_categoria,id_clasificacion_diagnosticos)
				Values (p_id_receta_diagnostico,NULL,p_id_diagnostico,p_id_tipo_diagnostico,1,p_id_prestacion,now(),p_id_usuario,p_id_evolucion,p_id_categoria_diagnostico,p_id_clasificacion_diagnostico);

			end loop;
		End if;*/
	end if;

	if p_regularizar_datos_encargado = '1' then -- Regularizar datos del Servicio a cargo
		update hospitalizaciones set id_upss_encargado = p_id_upss_encargado_regularizar, id_servicio_encargado = p_id_servicio_encargado_regularizar, id_servicio_destino = p_id_servicio_cama_regularizar
		where id = p_id_hospitalizacion;
	end if;
	--select id_cama_estado into p_id_cama_actual from hospitalizaciones where id = p_id_hospitalizacion;

	select h.id_cama_estado, as2.id_consultorio, h.id_upss_encargado, h.id_servicio_encargado
	into p_id_cama_actual, p_id_servicio_cama_actual, p_id_upss_encargado_actual, p_id_servicio_encargado_actual
		from hospitalizaciones h inner join camas c on h.id_cama_estado = c.id
		inner join cuartos c2 on c.id_cuarto = c2.id
		inner join asignacion_servicios as2 on c2.id_asignacion_servicio = as2.id
		where h.id = p_id_hospitalizacion;

	if p_tipo_cambio_cama = '0' then -- No hay cambio de cama
		update hospitalizaciones set id_user_atencion = p_id_medico, fecha_atencion = p_fecha_atencion_timestamp, id_servicio_destino = p_id_servicio_cama, id_cama_estado = p_id_cama
		where id = p_id_hospitalizacion;

	elsif p_tipo_cambio_cama = '1' then --Rotacion
		update hospitalizaciones set id_user_atencion = p_id_medico, fecha_atencion = p_fecha_atencion_timestamp, id_servicio_destino = p_id_servicio_cama, id_cama_estado = p_id_cama, fecha_ini_cama_estado = p_fecha_inicio_estado, hora_ini_cama_estado = p_hora
		where id = p_id_hospitalizacion;

	elsif p_tipo_cambio_cama = '2' then --Traslado
		update hospitalizaciones set id_user_atencion = p_id_medico, fecha_atencion = p_fecha_atencion_timestamp, id_servicio_destino = p_id_servicio_cama, id_cama_estado = p_id_cama, fecha_ini_cama_estado = p_fecha_inicio_estado, hora_ini_cama_estado = p_hora, id_upss_encargado = p_id_upss_encargado, id_servicio_encargado = p_id_servicio_encargado
		where id = p_id_hospitalizacion;

		-- Si el traslado cambia el servicio encargado, reinicia las marcas del flujo de 2 vistas (HC CU)
		-- para que un nuevo ingreso al mismo u otro servicio no herede flags del ciclo anterior.
		if p_id_servicio_encargado_actual is distinct from p_id_servicio_encargado then
			update hospitalizaciones set
				flag_exa_cu = null,
				fecha_hora_cu = null,
				id_user_exa_cu = null,
				fecha_limite_hc_cu = null,
				flag_hc_cu = null,
				id_user_hc_cu = null,
				fecha_hora_hc_cu = null,
				hc_cu_fuera_plazo = null
			where id = p_id_hospitalizacion;
		end if;
	end if;

	-- Indica que permanece en la misma cama (Default)
	p_estado_ubicacion = '1';

	if p_flag_alta_servicio = '0' then
			-- Si la cama seleccionada es diferente a la cama registrada
			if p_id_cama_actual <> p_id_cama then

				if p_id_servicio_cama_actual <> p_id_servicio_cama then
					-- **Depositado
					p_estado_ubicacion = '4';

					if p_id_establecimiento = 76 then
						-- Poner cama actual como disponible
						update camas set estado = 0, situacion = '1' where id = p_id_cama_actual;
						-- Poner cama nueva como Ocupado
						update camas set estado = 1, situacion = '4' where id = p_id_cama;
					else
						-- Poner cama actual como disponible
						update camas set estado = 0 where id = p_id_cama_actual;
						-- Poner cama nueva como Ocupado
						update camas set estado = 1 where id = p_id_cama;
					end if;

					/*if p_flag_alta_servicio = '0' then
						p_estado_ubicacion = '2'; -- Indica cambio de cama en el mismo servicio
					elsif p_flag_alta_servicio = '1' then
						p_estado_ubicacion = '3'; -- Indica cambio de cama a otro servicio
					end if;*/
				else
					-- **Rotacion
					p_estado_ubicacion = '2';

					if p_id_establecimiento = 76 then
						-- Poner cama actual como disponible
						update camas set estado = 0, situacion = '1' where id = p_id_cama_actual;
						-- Poner cama nueva como Ocupado
						update camas set estado = 1, situacion = '4' where id = p_id_cama;
					else
						-- Poner cama actual como disponible
						update camas set estado = 0 where id = p_id_cama_actual;
						-- Poner cama nueva como Ocupado
						update camas set estado = 1 where id = p_id_cama;
					end if;

				end if;
			else
				-- ** Permanece en la misma cama
				p_estado_ubicacion = '1';

			end if;
	else
		--** Alta de servicio o Traslado
		p_estado_ubicacion = '3';

		if p_id_establecimiento = 76 then
			-- Poner cama actual como disponible
			update camas set estado = 0, situacion = '1' where id = p_id_cama_actual;
			-- Poner cama nueva como Ocupado
			update camas set estado = 1, situacion = '4' where id = p_id_cama;
		else
			-- Poner cama actual como disponible
			update camas set estado = 0 where id = p_id_cama_actual;
			-- Poner cama nueva como Ocupado
			update camas set estado = 1 where id = p_id_cama;
		end if;

	end if;

	if (p_tipo_cambio_cama = '0') then -- No hay cambio de cama
		p_fecha_hora_inicio_cama = null;
	elsif p_tipo_cambio_cama = '1' or p_tipo_cambio_cama = '2' then --Rotacion o Traslado
		p_fecha_hora_inicio_cama = cast(p_fecha_inicio_estado ||' '||p_hora as timestamp);
	end if;

	if p_tipo_cambio_cama = '0' or p_tipo_cambio_cama = '1' then
		insert into cambio_camas (id_cama_atencion, fecha_hora_inicio, id_cama, id_prestacion, id_evolucion, estado_ubicacion, estado, created_at, id_upss_encargado, id_servicio_encargado, id_tipo_atencion, id_ext, id_upss_encargado_atencion, id_servicio_encargado_atencion)
		values(p_id_cama_actual, p_fecha_hora_inicio_cama, p_id_cama, p_id_prestacion, p_id_evolucion, p_estado_ubicacion, '1', now(), p_id_upss_encargado, p_id_servicio_encargado, p_id_tipo_atencion, p_id_hospitalizacion, p_id_upss_encargado_actual, p_id_servicio_encargado_actual);
	end if;

	if array_length(p_procedimientos,1)>0 then

		for i in array_lower(p_procedimientos, 1) .. array_upper(p_procedimientos, 1)
			loop
			p_id_procedimiento := p_procedimientos[i][1];
			p_cantidad := p_procedimientos[i][2];
			p_id_diagnostico_proc := p_procedimientos[i][3];

			Insert Into prestacion_procedimientos(id_prestacion, id_procedimiento, id_estado_reg, created_at, created_us, cantidad, id_evaluacion,id_diagnostico)
			Values (p_id_prestacion, p_id_procedimiento, 1, now(), p_id_usuario, p_cantidad,p_id_evolucion,p_id_diagnostico_proc);

			end loop;
	End if;

	if(p_valor = 2) then
		if array_length(p_diagnosticos,1)>0 then
			for i in array_lower(p_diagnosticos, 1) .. array_upper( p_diagnosticos, 1)
			loop
				p_id_diagnostico := p_diagnosticos[i][1];
				p_id_tipo_diagnostico := p_diagnosticos[i][2];
				p_id_categoria_diagnostico := p_diagnosticos[i][3];
				p_id_clasificacion_diagnostico := p_diagnosticos[i][4];

				p_id_receta_diagnostico := nextval('receta_diagnosticos_id_seq');
				Insert Into receta_diagnosticos(id,id_receta_vale,id_diagnostico,id_tipo_diagnostico,estado,id_prestacion,created_at,created_us,id_evaluacion,id_categoria,id_clasificacion_diagnosticos)
				Values (p_id_receta_diagnostico,NULL,p_id_diagnostico,p_id_tipo_diagnostico,1,p_id_prestacion,now(),p_id_usuario, p_id_evolucion,p_id_categoria_diagnostico,p_id_clasificacion_diagnostico);

			end loop;
		End if;
	End if;

	Return p_id_evolucion;
end;
$function$
;
