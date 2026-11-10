<input type="hidden" id="id_establecimiento" value="{{ $establecimiento->id }}">
<input type="hidden" id="id_nivel" value="{{ $nivel }}">
<input type="hidden" id="id_tipo_atencion" value="{{ $solicitud->id_tipo_atencion }}">
<input type="hidden" id="tipo_receta" value="{{ $solicitud->id_tipo_atencion }}">
<input type="hidden" id="id_servicio" value="{{ $solicitud->id_servicio }}">
<input type="hidden" id="id_consultorio" value="{{ $solicitud->id_servicio }}">
<input type="hidden" id="id_asegurado" value="{{ $solicitud->id_asegurado }}">
<input type="hidden" id="dni_beneficiario" value="{{ $solicitud->nro_doc_ident }}">
<input type="hidden" id="nomsexo" value="{{ in_array(strtoupper(trim($solicitud->sexo)), ['M', '1']) ? 'M' : 'F' }}">
<input type="hidden" id="id_rua_imagenes" name="id_rua_imagenes" value="">
