@extends('frontend.layouts.master5')
@section('renderjs')
<div id="blur-container">
<!-- Uso de Blade para importar scripts -->
<script src="{{ asset('funciones/function.js') }}"></script>
<script src="{{ asset('plugins/jQuery/jquery-ui.js') }}"></script>

<script src="{{ asset('auditoria/js/auditoria.js') }}"></script>
<script src="{{ asset('js/jquery.inputmask.bundle.min.js') }}"></script>
<!-- Incluir archivos CSS -->
<link rel="stylesheet" href="{{ asset('plugins/jQuery/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('auditoria/css/auditoria.css') }}">
<script>
  ///////////////////////////////////////SOLO PARA PRODUCTOS MEDICOS//////////////////////////////
  // CUANDO EL VALIDADOR ES TRUE RETORNA A LA PANTALLA LOS DATOS ANTERIORES PARA PRODUCTOS MEDICOS
  const oldProductos = {!! json_encode(old('id_producto', [])) !!};
  const oldVia_administracion = {!! json_encode(old('id_via_administracion', [])) !!};
  
  
  $('#example tbody tr').each(function(p_index) {
    if ($('#id_producto_miselect' + p_index).val() == '1') {
      //////////////////////////////////PRODUCTOS/////////////////////////////////
        const selectedProducto = oldProductos[p_index] || '';
        const cmbMedicamento = document.getElementById('cmbMedicamento' + p_index);
        $('#cmbMedicamentoTemp option').each(function() {
            var i = 0;
            let option = document.createElement('option');
            option.value = $(this).val();
            option.setAttribute('ru', $(this).attr('ru'));
            option.textContent = $(this).html();

            // Check if this option should be selected
            if (selectedProducto === $(this).val()) {
                option.selected = true;
            }
            cmbMedicamento.appendChild(option);
        });
        ////////////////////////VIA ADMINISTRACION////////////////////////////
        const selectedVia_administracion = oldVia_administracion[p_index] || '';

        var item_via_administracion = '';
        item_via_administracion += '<option value="0">--Seleccionar--</option>';
        item_via_administracion += '<option value="1">Oral</option>';
        item_via_administracion += '<option value="2">Sublingual</option>';
        item_via_administracion += '<option value="3">Rectal</option>';
        item_via_administracion += '<option value="4">Intravenosa</option>';
        item_via_administracion += '<option value="5">Intramuscular</option>';
        item_via_administracion += '<option value="6">Subcutánea</option>';
        item_via_administracion += '<option value="7">Dérmica</option>';
        item_via_administracion += '<option value="8">Nasal</option>';
        item_via_administracion += '<option value="9">Oftalmológica</option>';
        item_via_administracion += '<option value="10">Inhalatoria</option>';
        item_via_administracion += '<option value="11">Epidural</option>';
        item_via_administracion += '<option value="12">Intratecal</option>';

        const cmbTipoViaAdministracion = document.getElementById('cmbTipoViaAdministracion' + p_index);
        cmbTipoViaAdministracion.innerHTML = item_via_administracion;

        // Selecciona el valor correspondiente
        if (selectedVia_administracion) {
            cmbTipoViaAdministracion.value = selectedVia_administracion;
        }

        ////////////////////////DIAGNOSTICOS POR PRODUCTO////////////////////////////////////
        const diagnosticosProdInputs = $("input[name='nombre_diagnostico2[]']").map(function() {
            return $(this).val();
        }).get();

        diagnosticosProdInputs.forEach((nombre, d_index) => {
            const selectElementPD = document.getElementById('miSelectProd' + p_index);
            const valor = $('#id_diagnostico2' + d_index);
            var i = 0;
            let option = document.createElement('option');
            option.value = valor.val();
            option.textContent = nombre;
            if (i == 0) {
                option.setAttribute("selected", "selected");
                i++;
            }
            selectElementPD.appendChild(option);
        });
    }
  });

////////////////////////////////////FIN SOLO PARA PRODUCTOS MEDICOS//////////////////////////////

  @if (session('error'))
    bootbox.alert({
          message: "{{ session('error') }}",
          buttons: {
              ok: {
                  label: 'OK',
                  className: 'btn btn-warning' // Aplicar clase de advertencia al botón "OK"
              }
          },
          className: 'bootbox-warning'
      });

  @endif
</script>
	
@endsection
@section('content')
    <div class="row">
      <div class="col-md-12">
        <div class="panel with-nav-tabs panel-default">
          <div class="panel-body">
            <div class="tab-content">
              <div class="tab-pane fade in active" id="tab2default">
                <fieldset>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">
                        <div class="row">
                          <div class="panel panel-primary filterable">
                            <div class="panel-heading">
                              <h3 class="panel-title">Modulo Auditor - Edicion de la Trama</h3>
                            </div>
                            <div class="panel-body">

                              
                                <form id="frmAuditoriaEditar" action="{{ route('actualizar_auditoria', ['id' =>$id_prestacion]) }}" class="form-horizontal" method="post" autocomplete="off">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                                <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                <input type="hidden"  name="id_prestacion" id="id_prestacion" value="<?php echo $id_prestacion;?>">
                                <input type="hidden" name="med_id_tipo_profes" value="{{$data['med_id_tipo_profes']}}">
                                <input type="hidden" name="id_servicio" value="{{$data['id_servicio']}}">
                                <input type="hidden" name="nombre_servicio" value="{{$data['nombre_servicio']}}">
                                <input type="hidden" name="id_trama_prestacion" value="{{$data['id_trama_prestacion']}}">
                                <input type="hidden" name="id_user_auditor" value="{{$medico_auditor['id_auditor']}}">
                                <input type="hidden" name="id_nivel" id="id_nivel" value="{{$nivel}}">

                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>Datos del Medico Auditor</b>
                                      </div>
                                    </div>
                                    <div class="panel-body" >
                                      <div class="form-group">
                                        <div class="col-md-4">
                                            <label > Tipo de Documento de Identidad</label>
                                            <select class="form-control input-sm" disabled>
                                              <option value="1" {{ old('tipo_documento') == $medico_auditor['tipo_documento'] ? 'selected' : '' }} >DNI</option>
                                              <option value="2" {{ old('tipo_documento') == $medico_auditor['tipo_documento'] ? 'selected' : '' }}>CARNET DE EXTRANJERIA</option>
                                              <option value="3" {{ old('tipo_documento') == $medico_auditor['tipo_documento'] ? 'selected' : '' }}>PASAPORTE</option>
                                              <option value="4" {{ old('tipo_documento') == $medico_auditor['tipo_documento'] ? 'selected' : '' }}>DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                          <label>N° de Documento de Identidad</label>
                                          <input  type="text"  class="form-control input-sm text-uppercase" value="{{$medico_auditor['dni']}}" disabled/>
                                             
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-4">
                                          <label>Apellido Paterno</label>
                                          <input type="text" class="form-control input-sm text-uppercase" value="{{$medico_auditor['paterno']}}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                          <label> Apellido Materno</label>
                                          <input type="text" class="form-control input-sm text-uppercase" value="{{$medico_auditor['materno']}}" disabled>
                                          
                                        </div>
                                        <div class="col-md-4">
                                          <label>Nombres</label>
                                          <input type="text" class="form-control input-sm text-uppercase" value="{{$medico_auditor['nombre']}}" disabled>
                                         
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <div class="col-md-3">
                                          <label>N° Colegiatura</label>
                                          <input type="text" class="form-control" value="{{$medico_auditor['colegiatura']}}"   disabled>
                                        </div>
                                        <div class="col-md-3">
                                          <label> N° RNE &nbsp;</label>
                                          <input  type="text" class="form-control" value="{{$medico_auditor['rne']}}" disabled>
                                        </div>
                                        <div class="col-md-3">
                                          <label>Profesi&oacute;n</label>
                                          <select  class="form-control input-sm" disabled>
                                            <option >Seleccionar</option>
                                            @foreach($profesiones as $e)
                                            <option value="{{$e->id}}" <?php if($medico_auditor['id_profesion']==$e->id) echo 'selected';?> >{{$e->nombre}}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                        <div class="col-md-3">
                                          <label for="grado">Especialidad </label>
                                          <select class="form-control input-sm" disabled>
                                            <option >--Seleccionar--</option>
                                            @foreach($especializaciones as $esp)
                                            <option value="{{$esp->id}}" <?php if($medico_auditor['id_especializacion']==$esp->id) echo 'selected';?>>{{$esp->nombre}}
                                            </option>
                                            @endforeach
                                          </select>
                                        </div>
                                      </div>
                                </fieldset>

                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b> Datos Generales de la Auditoria</b>
                                      </div>
                                    </div>
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="col-md-4">
                                          <label for="tipo">Fecha de Auditoria </label>
                                          <input  value="<?php echo $data['fecha_auditoria'];?>" class="form-control input-sm" type="date" name="fecha_auditoria" id="fecha_auditoria"   maxlength="10" disabled>
                                        </div>
                                        <div class="col-md-4">
                                          <label for="paterno_beneficiario"> Servicio Auditado </label>
                                          
                                          <select class="form-control input-sm" onchange="getSubConsultorio()" disabled>
                                            <option value="0">-Seleccionar-</option>
                                            <?php foreach($consultorios as $row):?>
                                            <option value="<?php echo $row->id;?>" <?php if($data['id_servicio']==$row->id){echo 'selected';}?> ><?php echo $row->nombre; ?></option>
                                            <?php endforeach;?>
                                        </select>
                                        </div>
                                        <div class="col-md-4">
                                          <label> Asunto Audicion <span class="asterisco"></span></label>
                                          <select class="form-control input-sm" name="asunto_audicion">
                                            <option value="1" {{ old('asunto_audicion') == $medico_auditor['tipo_documento'] ? 'selected' : '' }}>AUDICIÓN POR OBSERVACIÓN DE SALUDPOL</option>
                                            <option value="2" {{ old('asunto_audicion') == $medico_auditor['tipo_documento'] ? 'selected' : '' }}>REVISIÓN DEL MEDICO AUDITOR</option>
                                          </select>
                                          @if ($errors->has('asunto_audicion'))
                                            <span style="color: red;">{{ $errors->first('asunto_audicion') }}</span>
                                            @endif
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <label for="tipo">Observaciones de la Audicion </label>
                                          <textarea onkeydown="verificarEspacios(event)" name="observaciones_audicion" id="observaciones_audicion" class="form-control" style="{{ $errors->has('observaciones_audicion') ? 'border: 1px solid red;' : '' }}">{{ old('observaciones_audicion') }}</textarea>
                                          @if ($errors->has('observaciones_audicion'))
                                          <span style="color: red;">{{ $errors->first('observaciones_audicion') }}</span>
                                          @endif
                                        </div>
                                      </div>
                                    </div>
                                </fieldset>

                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>Afiliación</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-md-4">
                                          <label >N° Historia Clinica <span class="asterisco"></span></label>
                                          <input tabindex="19" type="text" class="form-control"  onkeypress="return soloNumerosMenosCero(event)" name="n_historia_clinica" id="n_historia_clinica" value="{{ old('n_historia_clinica', $data['n_historia_clinica']) }}" style="{{ $errors->has('n_historia_clinica') ? 'border: 1px solid red;' : '' }}">
                                          @if ($errors->has('n_historia_clinica'))
                                          <span style="color: red;">{{ $errors->first('n_historia_clinica') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-4">
                                          <label>Tipo de Documento <span class="asterisco"></span></label>
                                          <select class="form-control input-sm" id="tipo_documento" name="tipo_documento" onchange="maxlength_doc_usu('tipo_documento','n_documento_identidad')" tabindex="1">
                                            <option value="1" {{ old('tipo_documento') == '1' ? 'selected' : '' }}>DNI</option>
                                            <option value="2" {{ old('tipo_documento') == '2' ? 'selected' : '' }}>CARNET DE EXTRANJERIA</option>
                                            <option value="3" {{ old('tipo_documento') == '3' ? 'selected' : '' }}>PASAPORTE</option>
                                            <option value="4" {{ old('tipo_documento') == '4' ? 'selected' : '' }}>DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                          </select>
                                        </div>
                                        <div class="col-md-4">
                                          <label>N° de Documento de Identidad <span class="asterisco"></span></label>
                                          <input id="n_documento_identidad" name="n_documento_identidad" type="text"  class="form-control input-sm text-uppercase"  pattern="[0-9]*"
                                              onkeypress="return esEntero(event);" tabindex="2" value="{{ old('n_documento_identidad', $data['n_documento_identidad']) }}" style="{{ $errors->has('n_documento_identidad') ? 'border: 1px solid red;' : '' }}"/>
                                          @if ($errors->has('n_documento_identidad'))
                                          <span style="color: red;">{{ $errors->first('n_documento_identidad') }}</span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <div class="col-md-4">
                                          <label >Tipo de Seguro <span class="asterisco"></span></label>
                                          <select class="form-control input-sm" id="tipo_seguro" name="tipo_seguro"  tabindex="1">
                                            <?php foreach ($tipo_seguros as $key => $value):?>
                                            <option value="<?php echo $value->id;?>" <?php if($data['tipo_seguro']==$value->id){echo 'selected';}?>><?php echo $value->descripcion;?></option>
                                            <?php endforeach;?>
                                          </select>
                                        </div>
                                        <div class="col-md-4">
                                          <label>Fecha de Nacimiento <span class="asterisco"></span></label>
                                          <input class="form-control input-sm" type="date" name="fecha_nacimiento" id="fecha_nacimiento"  value="{{ old('fecha_nacimiento', $data['fecha_nacimiento']) }}" style="{{ $errors->has('fecha_nacimiento') ? 'border: 1px solid red;' : '' }}">
                                          @if ($errors->has('fecha_nacimiento'))
                                          <span style="color: red;">{{ $errors->first('fecha_nacimiento') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-4">
                                          <label > Edad <span class="asterisco"></span></label>
                                          <input tabindex="22" type="text" class="form-control"  onkeypress="return soloNumerosMenosCero(event)" name="edad" id="edad" value="{{ old('edad', $data['edad']) }}" style="{{ $errors->has('edad') ? 'border: 1px solid red;' : '' }}">
                                          @if ($errors->has('edad'))
                                          <span style="color: red;">{{ $errors->first('edad') }}</span>
                                          @endif
                                        </div>
                                      </div>

                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label >Sexo <span class="asterisco"></span></label>
                                            <select class="form-control input-sm" id="sexo" name="sexo"  tabindex="1">
                                              <option value="M" <?php if($data['sexo']=='M'){echo 'selected';}?>>Masculino</option>
                                              <option value="F" <?php if($data['sexo']=='F'){echo 'selected';}?>>Femenino</option>
                                            </select>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Departamento <span class="asterisco"></span></label>
                                            <select id="id_departamento" name="id_departamento"
                                                class="form-control input-sm {{ $errors->has('id_departamento') ? 'is-invalid' : '' }}" required="" onchange="getProvincia();">
                                                <option value="">SELECCIONE</option>
                                                @foreach($departamento as $e)
                                                <option value="{{$e->id_departamento}}" @if ($e->id_departamento ==
                                                    $data['id_departamento']) selected @endif>
                                                    {{$e->descripcion}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('id_departamento'))
                                            <span style="color: red;">{{ $errors->first('id_departamento') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <label>Provincia <span class="asterisco"></span></label>
                                            <select id="id_provincia" name="id_provincia"
                                                class="form-control input-sm {{ $errors->has('id_provincia') ? 'is-invalid' : '' }}" required="" onchange="getDistrito();">
                                                @if($provincia != "")
                                                @foreach($provincia as $e)
                                                <option value="{{$e->id_provincia}}" @if ($e->id_provincia ==
                                                     $data['id_provincia']) selected @endif>
                                                    {{$e->descripcion}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('id_provincia'))
                                            <span style="color: red;">{{ $errors->first('id_provincia') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <label>Distrito <span class="asterisco"></span></label>
                                            <select id="id_distrito" name="id_distrito"
                                                class="form-control input-sm {{ $errors->has('id_distrito') ? 'is-invalid' : '' }}" required="">
                                                @if($distrito != "")
                                                @foreach($distrito as $e)
                                                <option value="{{$e->id_distrito}}" @if ($e->id_distrito ==
                                                    $data['id_distrito']) selected @endif>
                                                    {{$e->descripcion}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('id_distrito'))
                                            <span style="color: red;">{{ $errors->first('id_distrito') }}</span>
                                            @endif
                                        </div>
                                          <div class="col-md-4">
                                            <label >  Domicilio Actual </label>
                                            <textarea onkeydown="verificarEspacios(event)" name="domicilio_actual" id="domicilio_actual" class="form-control" style="{{ $errors->has('domicilio_actual') ? 'border: 1px solid red;' : '' }}">{{ old('domicilio_actual', $data['domicilio_actual']) }}</textarea>

                                            @if ($errors->has('domicilio_actual'))
                                            <span style="color: red;">{{ $errors->first('domicilio_actual') }}</span>
                                            @endif
                                          </div>

                                        </div>

                                        <div class="form-group">
                                          <div class="col-md-4">
                                            <label >Apellido Paterno <span class="asterisco"></span></label>
                                            <input onkeydown="verificarEspacios(event)" tabindex="22" type="text" class="form-control"  name="apellido_paterno" id="apellido_paterno" value="{{ old('apellido_paterno', $data['apellido_paterno']) }}" style="{{ $errors->has('apellido_paterno') ? 'border: 1px solid red;' : '' }}" >
                                            @if ($errors->has('apellido_paterno'))
                                            <span style="color: red;">{{ $errors->first('apellido_paterno') }}</span>
                                            @endif
                                          </div>
                                          <div class="col-md-4">
                                            <label > Apellido Materno <span class="asterisco"></span></label>
                                            <input onkeydown="verificarEspacios(event)" tabindex="22" type="text" class="form-control"  name="apellido_materno" id="apellido_materno" value="{{ old('apellido_materno', $data['apellido_materno']) }}" style="{{ $errors->has('apellido_materno') ? 'border: 1px solid red;' : '' }}" >
                                            @if ($errors->has('apellido_materno'))
                                            <span style="color: red;">{{ $errors->first('apellido_materno') }}</span>
                                            @endif
                                          </div>
                                          <div class="col-md-4">
                                            <label > Nombres <span class="asterisco"></span></label>
                                            <input onkeydown="verificarEspacios(event)" tabindex="22" type="text" class="form-control"  name="nombres" id="nombres" value="{{ old('nombres', $data['nombres']) }}" style="{{ $errors->has('nombres') ? 'border: 1px solid red;' : '' }}">
                                            @if ($errors->has('nombres'))
                                            <span style="color: red;">{{ $errors->first('nombres') }}</span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <div class="col-md-4">
                                            <label>Acompañante <span class="asterisco"></span></label>
                                            
                                            <select id="acompanante" name="acompanante"  class="form-control input-sm {{ $errors->has('med_id_especialidad_prof') ? 'is-invalid' : '' }}">
                                                @if($tipo_parentesco != "")
                                                  @foreach($tipo_parentesco as $e)
                                                  <option value="{{$e->id}}" @if ($e->id == $data['acompanante']) selected @endif>
                                                      {{$e->descripcion}}</option>
                                                  @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('acompanante'))
                                            <span style="color: red;">{{ $errors->first('acompanante') }}</span>
                                            @endif
                                          </div>
                                          <div class="col-md-4">
                                            <label > Telefono de acompañante </label>
                                            <input tabindex="22" type="text" class="form-control"  onkeypress="return soloNumerosMenosCero(event)" name="numero_acompanante" id="numero_acompanante"   value="{{ old('numero_acompanante', $data['numero_acompanante']) }}" style="{{ $errors->has('numero_acompanante') ? 'border: 1px solid red;' : '' }}">
                                            @if ($errors->has('numero_acompanante'))
                                            <span style="color: red;">{{ $errors->first('numero_acompanante') }}</span>
                                            @endif                                          
                                          </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>Medico de la prestación</b>
                                      </div>
                                    </div>
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="col-md-4">
                                            <label> Tipo de Documento de Identidad <span class="asterisco"></span></label>
                                            <select class="form-control input-sm" id="med_tipo_documento" name="med_tipo_documento" onchange="maxlength_doc_usu('med_tipo_documento','med_n_documento_identidad')" tabindex="1">
                                              <option value="1" {{ old('med_tipo_documento') == $data['med_tipo_documento'] ? 'selected' : '' }} >DNI</option>
                                              <option value="2" {{ old('med_tipo_documento') == $data['med_tipo_documento'] ? 'selected' : '' }}>CARNET DE EXTRANJERIA</option>
                                              <option value="3" {{ old('med_tipo_documento') == $data['med_tipo_documento'] ? 'selected' : '' }}>PASAPORTE</option>
                                              <option value="4" {{ old('med_tipo_documento') == $data['med_tipo_documento'] ? 'selected' : '' }}>DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                          <label>N° de Documento de Identidad <span class="asterisco"></span></label>
                                          <input id="med_n_documento_identidad" name="med_n_documento_identidad" type="text"  class="form-control input-sm text-uppercase"  pattern="[0-9]*"
                                              onkeypress="return esEntero(event);" tabindex="2" value="{{ old('med_n_documento_identidad', $data['med_n_documento_identidad']) }}" style="{{ $errors->has('med_n_documento_identidad') ? 'border: 1px solid red;' : '' }}" />
                                              @if ($errors->has('med_n_documento_identidad'))
                                              <span style="color: red;">{{ $errors->first('med_n_documento_identidad') }}</span>
                                              @endif
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-4"> 
                                          <label>Apellido Paterno <span class="asterisco"></span></label>
                                          <input  onkeydown="verificarEspacios(event)" type="text" class="form-control input-sm text-uppercase"  name="med_ape_paterno" id="med_ape_paterno" value="{{ old('med_ape_paterno', $data['med_ape_paterno']) }}" style="{{ $errors->has('med_ape_paterno') ? 'border: 1px solid red;' : '' }}">
                                          @if ($errors->has('med_ape_paterno'))
                                          <span style="color: red;">{{ $errors->first('med_ape_paterno') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-4">
                                          <label> Apellido Materno <span class="asterisco"></span></label>
                                          <input  onkeydown="verificarEspacios(event)" tabindex="12" type="text" class="form-control input-sm text-uppercase"  name="med_ape_materno" id="med_ape_materno"  value="{{ old('med_ape_materno', $data['med_ape_materno']) }}" style="{{ $errors->has('med_ape_materno') ? 'border: 1px solid red;' : '' }}">
                                          @if ($errors->has('med_ape_materno'))
                                          <span style="color: red;">{{ $errors->first('med_ape_materno') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-4">
                                          <label>Nombres <span class="asterisco"></span></label>
                                          <input  onkeydown="verificarEspacios(event)" tabindex="12"  type="text" class="form-control input-sm text-uppercase"  name="med_nombres" id="med_nombres" value="{{ old('med_nombres', $data['med_nombres']) }}" style="{{ $errors->has('med_nombres') ? 'border: 1px solid red;' : '' }}">
                                          @if ($errors->has('med_nombres'))
                                          <span style="color: red;">{{ $errors->first('med_nombres') }}</span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <div class="col-md-3">
                                          <label>N° Colegiatura <span class="asterisco"></span></label>
                                          <input tabindex="13"  type="text" class="form-control" onkeypress="return soloNumerosMenosCero(event)"  name="med_num_colegiatura" id="med_num_colegiatura" value="{{ old('med_num_colegiatura', $data['med_num_colegiatura']) }}" style="{{ $errors->has('med_num_colegiatura') ? 'border: 1px solid red;' : '' }}">
                                          @if ($errors->has('med_num_colegiatura'))
                                          <span style="color: red;">{{ $errors->first('med_num_colegiatura') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-3">
                                          <label> N° RNE &nbsp; <span class="asterisco"></span></label> 
                                          <input tabindex="14"   type="text" onkeypress="return soloNumerosMenosCero(event)" name="med_numero_rne" id="med_numero_rne" class="form-control" value="{{ old('med_numero_rne', $data['med_numero_rne']) }}" style="{{ $errors->has('med_numero_rne') ? 'border: 1px solid red;' : '' }}">
                                          @if ($errors->has('med_numero_rne'))
                                              <span style="color: red;">{{ $errors->first('med_numero_rne') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-3">
                                          <label>Profesi&oacute;n <span class="asterisco"></span></label>
                                          <select name="med_id_tipo_profes" id="med_id_tipo_profes" class="form-control input-sm"  disabled>
                                            <option >Seleccionar</option>
                                            @foreach($profesiones as $e)
                                            <option value="{{$e->id}}" {{ old('med_id_tipo_profes',$data['med_id_tipo_profes']) == $e->id ? 'selected' : '' }}>{{$e->nombre}}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                        <div class="col-md-3">
                                          <label for="grado">Especialidad <span class="asterisco"></span></label>
                                          <select name="med_id_especialidad_prof" id="med_id_especialidad_prof" class="form-control input-sm" >
                                            <option value="" >--Seleccionar--</option>
                                            @foreach($especializaciones as $esp)
                                            <option value="{{$esp->id}}" {{ old('med_id_especialidad_prof',$data['med_id_especialidad_prof']) == $esp->id ? 'selected' : '' }}>{{$esp->nombre}}
                                            </option>
                                            @endforeach
                                          </select>
                                        </div>
                                      </div>
                                </fieldset>
                                              <!---- INFORMACION DE INGRESO Y ADICCIONAL ------>
                                 @if($tipo_atencion=='2')             
                                <fieldset>
                                  <div class="panel with-nav-tabs panel-default" style="font-size: 11px;padding-bottom: 0px !important; ">
                                    <div class="panel-heading">
                                      <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                                            aria-expanded="true"><b><i class="fa fa-book" aria-hidden="true"></i>
                                              INFORMACIÓN DEL INGRESO</b></a></li>
                                      </ul>
                                    </div>
                                    <div class="panel-body" style="padding-bottom: 0px !important;">
                                      <div class="tab-content">
                                        <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">

                                          <fieldset style="padding-bottom: 10px !important;">
                                            <div class="row">
                                              <div class="col-md-3">
                                                <label>FORMA DE INGRESO</label>
                                                <select class="form-control input-sm" id="id_forma_ingreso" name="id_forma_ingreso">
                                                  <option value="">--SELECCIONAR--</option>
                                                  <option value="2" <?php echo ($datos_extra->id_forma_ingreso == '2') ? 'selected' : ''; ?>>CAMILLA</option>
                                                  <option value="1" <?php echo ($datos_extra->id_forma_ingreso == '1') ? 'selected' : ''; ?>>CAMINANDO</option>
                                                  <option value="4" <?php echo ($datos_extra->id_forma_ingreso == '4') ? 'selected' : ''; ?>>FALLECIDO</option>
                                                  <option value="7" <?php echo ($datos_extra->id_forma_ingreso == '7') ? 'selected' : ''; ?>>GATEANDO</option>
                                                  <option value="5" <?php echo ($datos_extra->id_forma_ingreso == '5') ? 'selected' : ''; ?>>NO INDICA</option>
                                                  <option value="3" <?php echo ($datos_extra->id_forma_ingreso == '3') ? 'selected' : ''; ?>>SILLA DE RUEDA</option>
                                              </select>
                                              </div>
                                              <div class="col-md-3">
                                                <label>ESTADO DE INGRESO</label>
                                                <select class="form-control input-sm" id="id_estado_ingreso" name="id_estado_ingreso">
                                                  <option value="" >--SELECCIONAR--</option>
                                                  <option value="4" <?php echo ($datos_extra->id_estado_ingreso == '4' || $datos_extra->id_estado_ingreso == '7' || $datos_extra->id_estado_ingreso == '8') ? 'selected' : ''; ?>>  CADAVER  </option>
                                                  <option value="1" <?php echo ($datos_extra->id_estado_ingreso == '1' || $datos_extra->id_estado_ingreso == '2' || $datos_extra->id_estado_ingreso == '5') ? 'selected' : ''; ?>>CONSCIENTE</option>
                                                  <option value="3" <?php echo ($datos_extra->id_estado_ingreso == '3' || $datos_extra->id_estado_ingreso == '6') ? 'selected' : ''; ?>>  INCONSCIENTE  </option>
                                                  <option value="9" <?php echo ($datos_extra->id_estado_ingreso == '9') ? 'selected' : ''; ?>>  NO INDICA  </option>
                                                </select>
                                              </div>
                                              <div class="col-md-3">
                                                <label>MOTIVO DE INGRESO</label>
                                                <select class="form-control input-sm" id="id_motivo_ingreso" name="id_motivo_ingreso">
                                                  <option value="" >--SELECCIONAR--</option>
                                                  <option value="2" <?php echo ($datos_extra->id_motivo_ingreso == '2') ? 'selected' : ''; ?>>  ENFERMEDAD COMUN  </option>
                                                  <option value="4" <?php echo ($datos_extra->id_motivo_ingreso == '4') ? 'selected' : ''; ?>>  EVACUADO  </option>
                                                  <option value="1" <?php echo ($datos_extra->id_motivo_ingreso == '1') ? 'selected' : ''; ?>>  POR COVID 19  </option>
                                                  <option value="3" <?php echo ($datos_extra->id_motivo_ingreso == '3') ? 'selected' : ''; ?>>  VIOLENCIA  </option>
                                                </select>
                                              </div>
                                            </div>
                                          </fieldset>

                                          <br>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                                <fieldset>
                                  <div class="panel with-nav-tabs panel-default"
                                    style="font-size: 11px;padding-bottom: 0px !important; visibility:">
                                    <div class="panel-heading">
                                      <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab0defaultInterconsultaKardex" data-toggle="tab" style="font-size:12px"
                                            aria-expanded="true"><b><i class="fa fa-file-text" aria-hidden="true"></i>
                                              INFORMACIÓN ADICIONAL</b></a></li>
                                      </ul>
                                    </div>
                                    <div class="panel-body" style="padding-bottom: 0px !important;">
                                      <div class="tab-content">

                                        <div class="tab-pane fade active in" id="tab0defaultInterconsultaKardex">
                                          <fieldset>
                                            <div class="row">
                                              <div class="col-md-3">
                                                <label>PRIORIDAD</label>
                                                <select class="form-control input-sm" id="id_prioridad" name="id_prioridad">
                                                  <option value="" selected="">--SELECCIONAR--</option>
                                                  <option value="1" <?php echo ($datos_extra->id_prioridad == '1') ? 'selected' : ''; ?>>  I - GRAVEDAD SÚBITA EXTREMA  </option>
                                                  <option value="2" <?php echo ($datos_extra->id_prioridad == '2') ? 'selected' : ''; ?>>  II - URGENCIA MAYOR  </option>
                                                  <option value="3" <?php echo ($datos_extra->id_prioridad == '3') ? 'selected' : ''; ?>>  III - URGENCIA MENOR  </option>
                                                  <option value="4" <?php echo ($datos_extra->id_prioridad == '4') ? 'selected' : ''; ?>>  IV - PATOLOGÍA AGUDA COMÚN  </option>
                                                </select>
                                              </div>

                                              <div id="" class="col-md-3">
                                                <label for="topico">AREA/SERVICIO</label>
                                                <select class="form-control input-sm" id="id_topico" name="id_topico">
                                                  <option value="" selected="">--SELECCIONAR--</option>
                                                  <option value="1" <?php echo ($datos_extra->id_triaje == '1') ? 'selected' : ''; ?>>  SHOCK TRAUMA  </option>
                                                  <option value="2" <?php echo ($datos_extra->id_triaje == '2' || $datos_extra->id_triaje == '3'|| $datos_extra->id_triaje == '2') ? 'selected' : ''; ?>>  TRAUMATOLOGIA  </option>
                                                  <option value="4" <?php echo ($datos_extra->id_triaje == '4' || $datos_extra->id_triaje == '5') ? 'selected' : ''; ?>>  PEDIATRIA  </option>
                                                  <option value="6" <?php echo ($datos_extra->id_triaje == '6' || $datos_extra->id_triaje == '7') ? 'selected' : ''; ?>>  GINECOLOGÍA Y OBSTETRICIA  </option>
                                                  <option value="8" <?php echo ($datos_extra->id_triaje == '8' || $datos_extra->id_triaje == '9') ? 'selected' : ''; ?>>  MEDICINA  </option>
                                                  <option value="10" <?php echo ($datos_extra->id_triaje == '10' || $datos_extra->id_triaje == '11') ? 'selected' : ''; ?>>  CIRUGIA  </option>
                                                  <option value="12" <?php echo ($datos_extra->id_triaje == '12') ? 'selected' : ''; ?>>  UNIDAD VIGILANCIA INTENSIVA  </option>
                                                  <option value="19" <?php echo ($datos_extra->id_triaje == '19') ? 'selected' : ''; ?>>  CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)  </option>
                                                  <option value="20" <?php echo ($datos_extra->id_triaje == '20') ? 'selected' : ''; ?>>  NO AMERITA  </option>
                                                </select>
                                              </div>

                                              <div class="col-md-3">
                                                <label>MOTIVO DE LA CONSULTA</label>
                                                <!--input value="{{$datos_extra->observacion_triaje}}" type="text" name="observacion" id="observacion" class="form-control"
                                                  style="text-transform: uppercase;" -->
                                                  <textarea name="observacion" id="observacion" class="form-control" style="text-transform: uppercase;"><?php echo htmlspecialchars($datos_extra->observacion_triaje); ?></textarea>
                                              </div>
                                            </div>
                                          </fieldset>
                                        </div>
                                        <br>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                                @endif
                                {{--
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>Anamnesis</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-md-4">
                                          <label >Fecha Atención <span class="asterisco"></span></label>
                                          <input class="form-control input-sm" type="date" name="fecha_atencion" id="fecha_atencion"   maxlength="10"  value="{{ old('fecha_atencion', $data['fecha_atencion']) }}" style="{{ $errors->has('fecha_atencion') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('fecha_atencion'))
                                          <span style="color: red;">{{ $errors->first('fecha_atencion') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-4">
                                          <label >Hora atención <span class="asterisco"></span></label>
                                          <input  class="form-control input-sm"  type="time"  id="hora_atencion"  name="hora_atencion" placeholder="00:00:00" value="{{ old('hora_atencion', $data['hora_atencion']) }}" style="{{ $errors->has('hora_atencion') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('hora_atencion'))
                                          <span style="color: red;">{{ $errors->first('hora_atencion') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-4">
                                          <label>Tiempo de Enfermedad </label>
                                          <textarea onkeydown="verificarEspacios(event)" name="tiempo_enfermedad" id="tiempo_enfermedad" class="form-control" style="{{ $errors->has('tiempo_enfermedad') ? 'border: 1px solid red;' : '' }}">{{ old('tiempo_enfermedad') }}</textarea>
                                         
                                          @if($errors->has('tiempo_enfermedad'))
                                              <span style="color: red;">{{ $errors->first('tiempo_enfermedad') }}</span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <label >Desarrollo Cronologico de Enfermedad </label>
                                          <input  onkeydown="verificarEspacios(event)" tabindex="22" type="text" class="form-control"  name="desarrollo_cronologico_enf" id="desarrollo_cronologico_enf" value="{{ old('desarrollo_cronologico_enf', $data['desarrollo_cronologico_enf']) }}" style="{{ $errors->has('desarrollo_cronologico_enf') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('desarrollo_cronologico_enf'))
                                          <span style="color: red;">{{ $errors->first('desarrollo_cronologico_enf') }}</span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <label >Motivo de Visita</label>
                                          <textarea onkeydown="verificarEspacios(event)" name="motivo_visita" id="motivo_visita" class="form-control" style="{{ $errors->has('motivo_visita') ? 'border: 1px solid red;' : '' }}">{{ old('motivo_visita') }}</textarea>
                                          @if($errors->has('motivo_visita'))
                                          <span style="color: red;">{{ $errors->first('motivo_visita') }}</span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <label > Antecedentes </label>
                                          <textarea onkeydown="verificarEspacios(event)" name="antecedentes" id="antecedentes" class="form-control" style="{{ $errors->has('antecedentes') ? 'border: 1px solid red;' : '' }}">{{ old('observaciones_audicion') }}</textarea>
                                        
                                          @if($errors->has('antecedentes'))
                                          <span style="color: red;">{{ $errors->first('antecedentes') }}</span>
                                          @endif
                                        </div>
                                      </div>
                                      <!-- 
                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <label > Signos y sintomas </label>
                                          <input tabindex="22" type="text" class="form-control"  name="signos_vitales" id="signos_vitales" value="" value="{{ old('signos_vitales', $data['signos_vitales']) }}" style="{{ $errors->has('antecedentes') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('signos_vitales'))
                                          <span style="color: red;">{{ $errors->first('signos_vitales') }}</span>
                                          @endif
                                        </div>
                                      </div>
                                       -->
                                    </div>
                                </fieldset>
                                
                                
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>Examen Clinico</b> - Funciones Vitales
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-md-2">
                                          <label > Temperatura (T) </label>
                                        
                                          <input type="text" name="temperatura" id="temperatura" class="form-control" maxlength="5" onkeypress="return esEntero(event)"  value="{{ old('temperatura', $data['temperatura']) }}" style="{{ $errors->has('temperatura') ? 'border: 1px solid red;' : '' }}"/>
                                          @if($errors->has('temperatura'))
                                          <span style="color: red;">{{ $errors->first('temperatura') }}</span><br>
                                          @endif
                                        <span id="texto-aceptado"></span>
                                        </div>
                                        <div class="col-md-2">
                                          <label for="medico_paterno"> Frecuencia Respiratoria</label>
                                          <input  type="text" tabindex="11" name="frecuencia_respiratoria" id="frecuencia_respiratoria" class="form-control" onkeypress="return esEntero(event);" maxlength="2" value="{{ old('frecuencia_respiratoria', $data['frecuencia_respiratoria']) }}" style="{{ $errors->has('frecuencia_respiratoria') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('frecuencia_respiratoria'))
                                          <span style="color: red;">{{ $errors->first('frecuencia_respiratoria') }}</span><br>
                                          @endif
                                          <span id="frecuencia_respiratoria-aceptado"></span>
                                        </div>
                                        <div class="col-md-2">
                                          <label for="medico_materno"> Frecuenca Cardiaca </label>
                                          <input  type="text" tabindex="10" name="frecuencia_cardiaca" id="frecuencia_cardiaca" class="form-control" onkeypress="return esEntero(event);" maxlength="3" value="{{ old('frecuencia_cardiaca', $data['frecuencia_cardiaca']) }}" style="{{ $errors->has('frecuencia_cardiaca') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('frecuencia_cardiaca'))
                                          <span style="color: red;">{{ $errors->first('frecuencia_cardiaca') }}</span><br>
                                          @endif
                                          <span id="frecuencia_cardiaca-aceptado"></span>
                                        </div>
                                        <div class="col-md-2">
                                          <label>PRESIÓN ARTERIAL </label>
                                            <div class="row">
                                              <div class="col-xs-5">
                                                <input  tabindex="8" type="text" name="presion_arterial_1" id="presion_arterial_1" class="form-control" onkeypress="return esEntero(event);" maxlength="3" value="{{ old('presion_arterial_1', $data['presion_arterial_1']) }}" style="{{ $errors->has('presion_arterial_1') ? 'border: 1px solid red;' : '' }}">
                                                @if($errors->has('presion_arterial_1'))
                                                <span style="color: red;">{{ $errors->first('presion_arterial_1') }}</span><br>
                                                @endif
                                                <span id="presion_arterial_1-aceptado"></span>
                                              </div>
                                              <div class="col-xs-2 text-center">
                                                <label>/</label>
                                              </div>
                                              <div class="col-xs-5">
                                                <input  type="text" tabindex="9" name="presion_arterial_2" id="presion_arterial_2" class="form-control" onkeypress="return esEntero(event);" maxlength="3" value="{{ old('presion_arterial_2', $data['presion_arterial_2']) }}" style="{{ $errors->has('presion_arterial_2') ? 'border: 1px solid red;' : '' }}">
                                                @if($errors->has('presion_arterial_2'))
                                                <span style="color: red;">{{ $errors->first('presion_arterial_2') }}</span><br>
                                                @endif
                                                <span id="presion_arterial_2-aceptado"></span>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                          <label for="medico_materno"> Saturacion de Oxigeno</label>
                                          <input  type="text" tabindex="13" name="saturacion_oxigeno" id="saturacion_oxigeno" class="form-control" onkeypress="return esEntero(event);" maxlength="3" value="{{ old('saturacion_oxigeno', $data['saturacion_oxigeno']) }}" style="{{ $errors->has('saturacion_oxigeno') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('saturacion_oxigeno'))
                                          <span style="color: red;">{{ $errors->first('saturacion_oxigeno') }}</span><br>
                                          @endif
                                          <span id="saturacion_oxigeno-aceptado"></span>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-3">
                                          <label >Peso </label>
                                          <input placeholder="00.00"  tabindex="16" type="text" name="peso" id="peso" class="form-control"  onkeypress="return esEntero(event)"  maxlength="6" value="{{ old('peso', $data['peso']) }}" style="{{ $errors->has('peso') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('peso'))
                                          <span style="color: red;">{{ $errors->first('peso') }}</span><br>
                                          @endif
                                          <span id="peso-aceptado"></span>

                                        </div>
                                        <div class="col-md-3">
                                          <label for="medico_paterno"> TALLA (cm) </label>
                                          <input  type="text" tabindex="15" name="talla" id="talla" class="form-control"  maxlength="5" onkeypress="return esEntero(event)"  value="{{ old('talla', $data['talla']) }}" style="{{ $errors->has('talla') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('talla'))
                                          <span style="color: red;">{{ $errors->first('talla') }}</span><br>
                                          @endif
                                          <span id="talla-aceptado"></span>
                                        </div>
                                        <div class="col-md-2">
                                          <label>Tamaño de la cintura (cm)</label>
                                          <input placeholder="0"  type="text" name="tamano_cintura" id="tamano_cintura" class="form-control" tabindex="29" onkeypress="return esNumero(event,this.id)" maxlength="3" value="{{ old('tamano_cintura', $data['tamano_cintura']) }}" style="{{ $errors->has('tamano_cintura') ? 'border: 1px solid red;' : '' }}" />
                                          @if($errors->has('tamano_cintura'))
                                          <span style="color: red;">{{ $errors->first('tamano_cintura') }}</span><br>
                                          @endif
                                        </div>
                                        <div class="col-md-2">
                                          <label>Fi02 (%) </label>
                                          <input placeholder="0" type="text" tabindex="12" name="fi02" id="fi02" tabindex="19" class="form-control" onkeypress="return esEntero(event);" maxlength="3" value="{{ old('fi02', $data['fi02']) }}" style="{{ $errors->has('fi02') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('fi02'))
                                          <span style="color: red;">{{ $errors->first('fi02') }}</span><br>
                                          @endif
                                          <span id="fi02-aceptado"></span>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <label >Examenes auxiliares </label>
                                          <textarea onkeydown="verificarEspacios(event)" name="examenes_auxiliares" id="examenes_auxiliares" class="form-control" style="{{ $errors->has('examenes_auxiliares') ? 'border: 1px solid red;' : '' }}">{{ old('examenes_auxiliares') }}</textarea>
                                        
                                          @if($errors->has('examenes_auxiliares'))
                                          <span style="color: red;">{{ $errors->first('examenes_auxiliares') }}</span><br>
                                          @endif
                                        </div>
                                      </div>
                                    </div>
                                </fieldset>
                                --}}
                                <fieldset style="display: none;">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>Diagnósticos</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <table>
                                        <tr>
                                          <td>
                                            <button type="button" id="addDiagnostico" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar
                                              Diagnostico</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <table class="table table-bordered"  id="tblDiagnostico" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                            <th width="90%" style="text-align: center;">NOMBRE</th>
                                            <th width="10%" style="text-align: center;">TIPO</th>
                                            <th width="10%" style="text-align: center;">CLASIFICACIÓN</th>
                                            <th width="10%" style="text-align: center;">OPCIÓN</th>
                                          </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                         
                                          <?php 
                                          if(!empty($data['diagnosticos_ingreso'])):
                                            foreach($data['diagnosticos_ingreso'] as $key => $row): 
                                          ?>
                                          <tr>
                                              <td>
                                                <input type="hidden" id="id_diagnostico_ingreso{{$key}}" name="id_diagnostico_ingreso[]"  value="{{$row['id_diagnostico']}}">
                                                <input type="hidden" id="id_diagnostico_ingreso_anulado{{$key}}" name="id_diagnostico_ingreso_anulado[]" >
                                                <input type="hidden" id="codigo_diagnostico_ingreso{{$key}}" value="{{$row['id_cie10']}}">
                                                <input type="hidden" id="id_tipo_diag_diagnostico_ingreso{{$key}}" value="<?php if($row['id_tipo_diag']==1) echo 'PRESUNTIVO';?>
                                                <?php if($row['id_tipo_diag']==2) echo 'DEFINITIVO';?>
                                                <?php if($row['id_tipo_diag']==3) echo 'REITERATIVO';?>">
                                                <input type="hidden" id="tipo_diag_diagnostico_ingreso{{$key}}" value="<?php if($row['id_tipo_diag']==1) echo 'PRESUNTIVO';?>
                                                <?php if($row['id_tipo_diag']==2) echo 'DEFINITIVO';?>
                                                <?php if($row['id_tipo_diag']==3) echo 'REITERATIVO';?>">
                                                <input type="hidden" id="renipress_diagnostico_ingreso{{$key}}" value="{{$row['renipress']}}">
                                                {{$row['id_cie10']}} - {{$row['descripcion']}}
                                            <td>
                                                <?php if($row['id_tipo_diag']==1) echo 'PRESUNTIVO';?>
                                                <?php if($row['id_tipo_diag']==2) echo 'DEFINITIVO';?>
                                                <?php if($row['id_tipo_diag']==3) echo 'REITERATIVO';?>
                                              </td>
                                              <td>
                                                <?php if($row['tipo_diag']==0) echo 'ATENCIÓN';?>
                                                <?php if($row['tipo_diag']==1) echo 'EVALUACION';?>
                                                <?php if($row['tipo_diag']==2) echo 'DIAGNOSTICO DE CABECERA';?>
                                              </td>
                                                <td><button type="button" class="btn btn-danger deleteFilaDiagnosticoIngreso btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>
                                            </tr>
                                          <?php endforeach;
                                          endif;
                                          ?>

                                          @if(old('nombre_diagnostico'))
                                            @foreach(old('nombre_diagnostico') as $index => $nombre_diagnostico)

                                                <tr>
                                                    <td>
                                                        <input type="hidden" id="datosretorno_diagnostico_ingreso{{ $index }}"  value="1">
                                                        <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" id="nombre_diagnostico{{ $index }}" name="nombre_diagnostico[]" value="{{ $nombre_diagnostico }}" class="input-sm" style="margin-left:4px; width:100%; text-align:left">
                                                        <input type="hidden" class="form-control id_diagnostico" id="id_diagnostico{{ $index }}" name="id_diagnostico_ingreso_nuevos[]" data-id="{{ $index }}" value="{{ old('id_diagnostico_ingreso_nuevos')[$index] }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-control id_tipo_diagnostico" name="id_diagnostico_ingreso_nuevos_tipo_diagnostico[]"  style="margin:0 20px;width:150px">
                                                            <option value="0">--Seleccionar--</option>
                                                            <option value="1" {{ old('id_diagnostico_ingreso_nuevos_tipo_diagnostico')[$index] == 1 ? 'selected' : '' }}>PRESUNTIVO</option>
                                                            <option value="2" {{ old('id_diagnostico_ingreso_nuevos_tipo_diagnostico')[$index] == 2 ? 'selected' : '' }}>DEFINITIVO</option>
                                                            <option value="3" {{ old('id_diagnostico_ingreso_nuevos_tipo_diagnostico')[$index] == 3 ? 'selected' : '' }}>REITERATIVO</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control id_clasificacion_diagnostico" name="id_clasificacion[]"  style="margin:0 20px;width:150px">
                                                            <option value="0">--Seleccionar--</option>
                                                            <option value="1" {{ old('id_clasificacion')[$index] == 1 ? 'selected' : '' }}>ATENCIÓN</option>
                                                            <option value="2" {{ old('id_clasificacion')[$index] == 2 ? 'selected' : '' }}>EVALUACIÓN</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                          @endif
                                          
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </fieldset>

                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b> Plan de Trabajo</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      
                                      {{-- <div class="form-group">
                                        <div class="col-md-12">
                                          <label > Exámenes de Patología (Examenes de Laboratorio) </label>
                                          <table class="table table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                              <tr>
                                                <th width="5%" style="text-align: center;">CODIGO</th>
                                                <th width="45%" style="text-align: center;">NOMBRE</th>
                                                <th width="30%" style="text-align: center;">OBSERVACIÓN</th>
                                                <th width="10%" style="text-align: center;">CANTIDAD</th>
                                                <th width="10%" style="text-align: center;">DIAGNOSTICOS</th>
                                                <th width="10%" style="text-align: center;">OPCIÓN</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              @if(!empty($data['examen_patologia']))
                                              @foreach($data['examen_patologia'] as $key => $row)
                                                @php
                                                  $isEliminadoPatologia = !empty(old('id_examen_patologia_anulado.'.$key));
                                                @endphp
                                                <tr class="{{ $isEliminadoPatologia ? 'eliminado' : '' }}">
                                                  <td>
                                                    <input type="hidden" id="codigo{{$key}}" value="{{$row['codigo']}}">
                                                    <input type="hidden" id="nombre{{$key}}" value="{{$row['nombre']}}">
                                                    <input type="hidden" id="cantidad{{$key}}" value="{{$row['cantidad']}}">
                                                    <input type="hidden" id="nombre_completo{{$key}}" value="{{$row['nombre_completo']}}">
                                                    <input type="hidden" id="observacion{{$key}}" value="{{$row['observacion']}}">
                                                    <input type="hidden" id="diagnostico{{$key}}" value="{{$row['diagnostico']}}">
                                                    <input type="hidden" id="id_examen_patologia{{$key}}" name="id_examen_patologia[]" value="{{$row['id']}}">
                                                    <input type="hidden" id="id_examen_patologia_anulado{{$key}}" name="id_examen_patologia_anulado[]" value="{{ old('id_examen_patologia_anulado.'.$key, '') }}">
                                                    {{$row['codigo']}}
                                                  </td>
                                                  <td>{{ $row['nombre'] }}</td>
                                                  <td>{{ $row['observacion'] }}</td>
                                                  <td>{{ $row['cantidad'] }}</td>
                                                  <td>{{ $row['cod_diag'] }}</td>
                                                  <td><button type="button" class="btn btn-danger deleteFilaExamenPatologia btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>
                                                </tr>
                                              @endforeach;
                                            @endif;
                                            </tbody>
                                          </table>
                                        </div>
                                      </div> --}}
                                    
                                      {{-- <div class="form-group">
                                        <div class="col-md-12">
                                          <label >Exámenes de diagnosticos por imagenes </label>
                                          <table class="table table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                              <tr>
                                            <th width="5%" style="text-align: center;">CODIGO</th>
                                            <th width="45%" style="text-align: center;">NOMBRE</th>
                                            <th width="10%" style="text-align: center;">COMPARATIVO</th>
                                            <th width="10%" style="text-align: center;">LADO</th>
                                            <th width="15%" style="text-align: center;">OBSERVACIÓN</th>
                                            <th width="5%" style="text-align: center;">CANTIDAD</th>
                                            <th width="10%" style="text-align: center;">DIAGNOSTICO</th>
                                            <th width="5%" style="text-align: center;">OPCIÓN</th>
                                              </tr>
                                              </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                              @if(!empty($data['examenes_imagenologias']))
                                                @foreach($data['examenes_imagenologias'] as $key => $row)
                                                  @php
                                                    $isEliminadoImagenologia = !empty(old('id_examen_imagenologia_anulado.' . $key));
                                                  @endphp
                                                  <tr class="{{ $isEliminadoImagenologia ? 'eliminado' : '' }}">
                                                    <td>
                                                      <input type="hidden" id="codigo{{$key}}" value="{{$row['codigo']}}">
                                                      <input type="hidden" id="nombre{{$key}}" value="{{$row['nombre']}}">
                                                      <input type="hidden" id="cantidad{{$key}}" value="{{$row['cantidad']}}">
                                                      <input type="hidden" id="observacion{{$key}}" value="{{$row['observacion']}}">
                                                      <input type="hidden" id="diagnostico{{$key}}" value="{{$row['diagnostico']}}">
                                                      <input type="hidden" id="cod_diag{{$key}}" value="{{$row['cod_diag']}}">
                                                      <input type="hidden" id="comparativo_radio{{$key}}" value="{{$row['comparativo_radio']}}">
                                                      <input type="hidden" id="lado_radio{{$key}}" value="{{$row['lado_radio']}}">
                                                      <input type="hidden" id="id_examen_imagenologia{{$key}}" name="id_examen_imagenologia[]" value="{{$row['id']}}">
                                                      <input type="hidden" id="id_examen_imagenologia2{{$key}}" name="id_examen_imagenologia2[]">
                                                      <input type="hidden" id="id_examen_imagenologia_anulado{{$key}}" name="id_examen_imagenologia_anulado[]" value="{{ old('id_examen_imagenologia_anulado.' . $key, '') }}">
                                                      {{$row['codigo']}}
                                                    </td>
                                                    <td>{{$row['nombre']}}</td>
                                                    <td>
                                                      @if($row['comparativo_radio'] != null)
                                                        {{ $row['comparativo_radio'] == 1 ? 'SI' : 'NO' }}
                                                      @endif
                                                    </td>
                                                    <td>
                                                      @if($row['lado_radio'] != null)
                                                        {{ $row['lado_radio'] == 1 ? 'Izquierdo' : 'Derecho' }}
                                                      @endif
                                                    </td>
                                                    <td>
                                                      @if($row['observacion'] != '0')
                                                        {{ $row['observacion'] }}
                                                      @endif
                                                    </td>
                                                    <td>{{$row['cantidad']}}</td>
                                                    <td>{{$row['cod_diag']}}</td>
                                                    <td>
                                                      <button type="button" class="btn btn-danger deleteFilaExamenImagenologia btn-xs" style="margin-left:4px">
                                                        <i class="fa fa-times"></i> Eliminar
                                                      </button>
                                                    </td>
                                                  </tr>
                                                @endforeach
                                              @endif
                                            </tbody>
                                          </table>
                                        </div>
                                      </div> --}}

                                      {{-- <div class="form-group">
                                        <div class="col-md-12">
                                          <label>Interconsultas</label>
                                          <table class="table table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                              <tr>
                                                <th style="text-align: center;">SERVICIO</th>
                                                <th style="text-align: center;">MOTIVO</th>
                                                <th style="text-align: center;">MÉDICO DE INTERCONSULTA</th>
                                              </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                              @if(!empty($data['interconsultas']))
                                                @foreach($data['interconsultas'] as $row)
                                                  <tr>
                                                    <td>{{ $row['codigo_interconsulta'] }} - {{ $row['servicio'] }}</td>
                                                    <td>{{ $row['motivo_interconsulta'] }}</td>
                                                    <td>{{ $row['medico_nombre_tr'] }} {{ $row['medico_paterno_tr'] }} {{ $row['medico_materno_tr'] }}</td>
                                                  </tr>
                                                @endforeach
                                              @endif
                                            </tbody>
                                          </table>
                                        </div>
                                      </div> --}}
                                      
                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                          <div class="panel-title text-center">
                                            <b>DIAGNÓSTICOS</b>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          <table>
                                            <tr>
                                              <td>
                                                <button type="button" id="addDiagnostico2" class="btn btn-primary"
                                                  data-toggle="modal" data-target="#addClassModal">Agregar
                                                  Diagnostico</button>
                                              </td>
                                            </tr>
                                          </table>
                                          <br />
                                          <table class="table table-bordered" cellspacing="0" id="tblDiagnostico2" cellspacing="0" width="100%"
                                            style="margin-top:5px;">
                                            <thead>
                                              <tr>
                                                <th width="90%" style="text-align: center;">NOMBRE</th>
                                                <th width="10%" style="text-align: center;">TIPO</th>
                                                <th width="10%" style="text-align: center;">CLASIFICACIÓN</th>
                                                <th width="10%" style="text-align: center;">INGRESO/EGRESO</th>
                                                <th width="10%" style="text-align: center;">OPCIÓN</th>
                                              </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                              <?php 
                                              if(!empty($data['diagnosticos_externo'])):
                                              
                                                foreach($data['diagnosticos_externo'] as $key => $row): 

                                                 $isEliminado = !empty(old('id_diagnosticos_externo_anulado.'.$key));
                                                 ?>
                                                <tr class="<?php echo $isEliminado ? 'eliminado' : ''; ?>">
                                                  <td>
                                                  <input type="hidden" id="renipress{{$key}}" value="{{$row['renipress']}}">
                                                  <input type="hidden" id="numero_lote_fups{{$key}}" value="{{$row['numero_lote_fups']}}">
                                                  <input type="hidden" id="numero_fups{{$key}}" value="{{$row['numero_fups']}}">
                                                  <input type="hidden" class="nuevo_codigo_diagnostico" id="id_cie10{{$key}}" value="{{$row['id_cie10']}}">
                                                  <input type="hidden" id="id_identificador_diag{{$key}}" value="{{$row['id_identificador_diag']}}">
                                                  <input type="hidden" id="id_tipo_diag{{$key}}" value="{{$row['id_tipo_diag']}}">
                                                  
                                                  <input type="hidden" class="nuevo_id_diagnostico" id="id_diagnostico{{$key}}" value="{{$row['id_diagnostico']}}">
                                                  <input type="hidden" id="tipo_diag{{$key}}" value="{{$row['tipo_diag']}}">
                                                  <input type="hidden" class="nuevo_nombre_diagnostico" id="descripcion{{$key}}" value="{{$row['descripcion']}}">
                                                  
                                                  <input type="hidden" id="id_diagnosticos_externo{{$key}}" name="id_diagnosticos_externo[]"  value="{{$row['id_diagnostico']}}">
                                                  <input type="hidden" id="id_diagnosticos_externo2{{$key}}" name="id_diagnosticos_externo2[]">
                                                  <input type="hidden" id="id_diagnosticos_externo_anulado{{$key}}" name="id_diagnosticos_externo_anulado[]" value="{{ old('id_diagnosticos_externo_anulado.'.$key, '') }}">

                                                    {{$row['id_cie10']}}-{{$row['descripcion']}}
                                                  </td>
                                                  <td>
                                                    <?php if($row['id_tipo_diag']==1) echo 'PRESUNTIVO';?>
                                                    <?php if($row['id_tipo_diag']==2) echo 'DEFINITIVO';?>
                                                    <?php if($row['id_tipo_diag']==3) echo 'REITERATIVO';?>
                                                    </td>
                                                    <td>
                                                      <?php if($row['tipo_diag']==0) echo 'ATENCIÓN';?>
                                                      <?php if($row['tipo_diag']!=0) echo 'EVALUACIÓN';?>
                                                      </td>
                                                      <td>
                                                        @if($row['id_identificador_diag'] == 'E')
                                                            EGRESO
                                                        @elseif($row['id_identificador_diag'] == 'I')
                                                            INGRESO
                                                        @else
                                                        @endif
                                                      </td>
                                                      <td><button type="button" class="btn btn-danger deleteFilaDiagnosticoExterno btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>
                                                </tr>
                                                <?php 
                                                endforeach;
                                              endif;
                                              ?>
                                            @if(old('nombre_diagnostico2'))
                                            @foreach(old('nombre_diagnostico2') as $index => $nombre_diagnostico2)
                                                <tr>
                                                    <td>
                                                      <input type="hidden" id="datosretorno_diagnostico_externo{{ $index }}"  value="1">
                                                        <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico2[]" tabindex="44" required="" id="nombre_diagnostico2{{$index}}" value="{{ $nombre_diagnostico2 }}" class="input-sm ui-autocomplete-input id_diagnostico2" style="margin-left:4px; width:100%; text-align:left" />
                                                        <input type="hidden" id="id_diagnostico2{{ $index }}" name="id_diagnostico_externo_nuevos[]" data-id="{{ $index }}" value="{{ old('id_diagnostico_externo_nuevos.'.$index) }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-control id_tipo_diagnostico2" id="cmbTipoDiagnostico2{{ $index }}" name="id_diagnostico_externo_nuevos_tipo_diagnostico[]" style="margin:0 20px;width:150px">
                                                            <option value="0">--Seleccionar--</option>
                                                            <option value="1" {{ old('id_diagnostico_externo_nuevos_tipo_diagnostico')[$index] == 1 ? 'selected' : '' }}>PRESUNTIVO</option>
                                                            <option value="2" {{ old('id_diagnostico_externo_nuevos_tipo_diagnostico')[$index] == 2 ? 'selected' : '' }}>DEFINITIVO</option>
                                                            <option value="3" {{ old('id_diagnostico_externo_nuevos_tipo_diagnostico')[$index] == 3 ? 'selected' : '' }}>REITERATIVO</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control id_clasificacion_diagnostico2" id="cmbClasificacion2{{ $index }}" name="id_clasificacion2[]" style="margin:0 20px;width:150px">
                                                            <option value="N">--Seleccionar--</option>
                                                            @if (old('ingreso_egreso')[$index] == 'I')
                                                            <option value="0" {{ old('id_clasificacion2')[$index] == 0 ? 'selected' : '' }}>ATENCIÓN</option>
                                                            <option value="1" {{ old('id_clasificacion2')[$index] == 1 ? 'selected' : '' }}>EVALUACIÓN</option>
                                                            @elseif (old('ingreso_egreso')[$index] == 'E')
                                                            <option value="0" {{ old('id_clasificacion2')[$index] == 0 ? 'selected' : '' }}>SECUNDARIO</option>
                                                            <option value="2" {{ old('id_clasificacion2')[$index] == 2 ? 'selected' : '' }}>DIAGNOSTICO DE CABECERA</option>
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                      <select class="form-control " id="cmb_ingreso_egreso{{$index}}" name="ingreso_egreso[]" style="margin:0 20px;width:150px">
                                                        <option value="I" {{ old('ingreso_egreso')[$index] == 'I' ? 'selected' : '' }}>INGRESO</option>
                                                        <option value="E" {{ old('ingreso_egreso')[$index] == 'E' ? 'selected' : '' }}>EGRESO</option>
                                                    </select>
                                                  </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger deleteFila2 btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>

                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                          <div class="panel-title text-center">
                                            <b>PROCEDIMIENTOS</b>
                                          </div>
                                        </div>
                                        <div class="panel-body">
                                          <table>
                                            <tr>
                                              <td>
                                                <button type="button" id="addProcedimiento" class="btn btn-primary"
                                                  data-toggle="modal" data-target="#addClassModal">Agregar
                                                  Procedimiento</button>
                                              </td>
                                            </tr>
                                          </table>
                                          <br />
                                          <table class="table table-bordered" cellspacing="0"  id="tblProcedimiento" width="100%"
                                            style="margin-top:5px;">
                                            <thead>
                                              <tr>
                                                  <th width="45%" style="text-align: center;">PROCEDIMIENTO</th>
                                                  <th width="5%" style="text-align: center;">CANTIDAD</th>
                                                  <th width="45%" style="text-align: center;">DIAGNOSTICOS</th>
                                                  <th width="5%">OPCIÓN</th>
                                              </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                              <?php 
                                              if(!empty($data['trama_procedimientos_externo'])):
                                              
                                                foreach($data['trama_procedimientos_externo'] as $key => $row): 

                                                $isEliminadoProcedimiento = !empty(old('id_procedimiento_externo_anulado.'.$key));
                                                ?>
                                              <tr class="<?php echo $isEliminadoProcedimiento ? 'eliminado' : ''; ?>">
                                                <td>
                                                  <input type="hidden" id="renipress{{$key}}"  value="{{$row['renipress']}}">
                                                  <input type="hidden" id="numero_lote_fups{{$key}}"  value="{{$row['numero_lote_fups']}}">
                                                  <input type="hidden" id="numero_fups{{$key}}"  value="{{$row['numero_fups']}}">
                                                  <input type="hidden" id="id_cie10{{$key}}"  value="{{$row['id_cie10']}}">
                                                  <input type="hidden" id="id_proced{{$key}}"  value="{{$row['id_proced']}}">
                                                  <input type="hidden" id="cantidad_indicada{{$key}}"  value="{{$row['cantidad_indicada']}}">
                                                  <input type="hidden" id="cantidad_ejecutada{{$key}}"  value="{{$row['cantidad_ejecutada']}}">
                                                  <input type="hidden" id="precio{{$key}}"  value="{{$row['precio']}}">
                                                  <input type="hidden" id="valorizacion{{$key}}"  value="{{$row['valorizacion']}}">
                                                  <input type="hidden" id="correlativo{{$key}}"  value="{{$row['correlativo']}}">
                                                  <input type="hidden" id="fecha_procedimiento{{$key}}"  value="{{$row['fecha_procedimiento']}}">
                                                  <input type="hidden" id="resultado_procedimiento{{$key}}"  value="{{$row['resultado_procedimiento']}}">
                                                  <input type="hidden" id="id_diagnostico{{$key}}"  value="{{$row['id_diagnostico']}}">
                                                  <input type="hidden" id="nombre_diagnostico{{$key}}"  value="{{$row['nombre_diagnostico']}}">
                                                  <input type="hidden" id="id_procedimiento{{$key}}"  value="{{$row['id_procedimiento']}}">
                                                  <input type="hidden" id="codigo_procedimiento{{$key}}"  value="{{$row['codigo_procedimiento']}}">
                                                  <input type="hidden" id="nombre_procedimiento{{$key}}"  value="{{$row['nombre_procedimiento']}}">

                                                  <input type="hidden" id="id_procedimiento_externo{{$key}}" name="id_procedimiento_externo[]"  value="{{$row['id_procedimiento']}}">
                                                  <input type="hidden" id="id_procedimiento_externo2{{$key}}" name="id_procedimiento_externo2[]">

                                                  <input type="hidden" id="id_procedimiento_externo_anulado{{$key}}" name="id_procedimiento_externo_anulado[]" value="{{ old('id_procedimiento_externo_anulado.'.$key, '') }}">
                                                  {{$row['codigo_procedimiento']}}-{{$row['nombre_procedimiento']}}
                                                </td>
                                                <td>
                                                  {{$row['cantidad_indicada']}}
                                                </td>
                                                <td>
                                                  <?php echo $row['nombre_diagnostico'];?>
                                                </td>
                                                    <td><button type="button" class="btn btn-danger deleteFilaProcedimientoExterno btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>
                                                </tr>
                                              <?php 
                                                endforeach;
                                              endif;
                                              ?>
                                            @if(old('nombre_procedimiento'))
                                            @foreach(old('nombre_procedimiento') as $index => $nombre_procedimiento)
                                                <tr>
                                                    <td>
                                                      
                                                      <input type="hidden" id="existe_datos_procedimiento{{ $index }}"  value="1">
                                                      <input type="hidden" id="datosretorno_procedimiento{{ $index }}"  value="1">
                                                      <input type="hidden" id="id_procedimiento_miselect{{ $index }}" value="1">
                                                      <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_procedimiento[]" tabindex="44" required="" id="nombre_procedimiento_ext{{$index}}" value="{{ $nombre_procedimiento }}" class="input-sm ui-autocomplete-input id_procedimiento" style="margin-left:4px; width:100%; text-align:left" />
                                                      <input type="hidden" id="id_procedimiento{{ $index }}" name="id_procedimiento[]" data-id="{{ $index }}" value="{{ old('id_procedimiento')[$index] }}">
                                                    </td>
                                                    <td>
                                                      <input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad{{ $index }}" name="procedimiento_cantidad[]" value="{{ old('procedimiento_cantidad')[$index] }}">
                                                    </td>
                                                    <td>
                                                      <select class="form-control select-diagnostico" id="miSelect{{ $index }}" name="miSelect[]"></select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif


                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                     
                                      
                                      <div class="panel panel-info panel-bordered panel-sm">
                                         <div class="panel-heading">
                                         <div class="panel-title text-center">
                                           <b>PRODUCTOS MEDICOS</b>
                                         </div>
                                         </div>
                                         <div class="panel-body">
                                         <div class="form-group">
                                           <div class="col-md-2">
                                             <label>FEC. EXPEDICIÓN (A)</label>
                                             <input tabindex="71" type="text" class="form-control datepicker"
                                               value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                               name="fecha_expedicion" id="fecha_expedicion" 
                                               data-date-end-date="0d" autocomplete="off" />
                                           </div>
                                           <div class="col-md-5">
                                             <label>FARMACIA</label>
                                             <select tabindex="72" class="form-control" name="id_farmacia_receta" id="id_farmacia_receta" style="width:100%" onchange="getProducto();">
                                               <option value="">-Seleccionar-</option>
                                               <?php foreach($farmacias as $row):?>
                                               <option value="<?php echo $row->id?>"
                                                 <?php if($row->id==Auth::user()->id_farmacia) echo "selected='selected'"?>>
                                                 <?php echo $row->nombre?></option>
                                                 @if($nivel==3)
                                                   <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                                 @endif
                                               <?php endforeach;?>
                                             </select>
                                           </div>
                                         </div>
                                         <br />
                                        <div class="form-group">
                                          <div class="col-sm-12">
                                            <table>
                                              <tr>
                                                <td>
                                                <button tabindex="75" type="button" id="addProducto" class="btn btn-primary"
                                                data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                                </td>
                                              </tr>
                                            </table>
                                          </div>
                                        </div>
      
                                         <div style="display: none">
                                           <select class="form-control" id="cmbMedicamentoTemp" tabindex="73"
                                             style="width: 500px">
                                             <option value="">Seleccionar Producto</option>
                                             <?php foreach($producto as $row):?>
                                               <option value="<?php echo $row->id?>" data-codigo="<?php echo $row->codigo?>">
                                                <?php echo $row->codigo?> - <?php echo $row->nombre?></option>
                                               <?php endforeach;?>
                                           </select>
                                         </div>
                                            <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                             <table id="example" class="table table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                      <tr>
                                                        <th>Códido</th>
                                                        <th>Producto farmacéutico o dispositivo médico</th>
                                                        <th style="width:10%">Stock Farmacia</th>
                                                        <th>Tiempo(Dias)</th>
                                                        <th> Via Adm. </th>
                                                        <th>Posologia</th>
                                                        <th> Cant. Pres.</th>
                                                        <th> Cant. Disp.</th>
                                                        <th> DiagnosticoxProducto </th>
                                                        <th style="width:8%">Opción</th>
                                                      </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                  <?php 
                                                  //var_dump($data['trama_productos']);
                                                  if(!empty($data['trama_productos'])):
                                                    foreach($data['trama_productos'] as $key => $row):

                                                    $isEliminadoProducto = !empty(old('id_producto_anulado.'.$key));
                                                    ?>
                                                  <tr class="<?php echo $isEliminadoProducto ? 'eliminado' : ''; ?>">
                                                    <td>{{ $row['id_prod_med'] }}</td>
                                                    <td>
                                                      <input type="hidden" id="renipress{{$key}}" name="renipress[]"  value="{{$row['renipress']}}">
                                                      <input type="hidden" id="numero_lote_fups{{$key}}" name="numero_lote_fups[]"  value="{{$row['numero_lote_fups']}}">
                                                      <input type="hidden" id="numero_fups{{$key}}" name="numero_fups[]"  value="{{$row['numero_fups']}}">
                                                      <input type="hidden" id="id_cie10{{$key}}" name="id_cie10[]"  value="{{$row['id_cie10']}}">
                                                      <input type="hidden" id="id_prod_med{{$key}}" name="id_prod_med[]"  value="{{$row['id_prod_med']}}">
                                                      <input type="hidden" id="cantidad_prescrita{{$key}}" name="cantidad_prescrita[]"  value="{{$row['cantidad_prescrita']}}">
                                                      <input type="hidden" id="precio{{$key}}" name="precio[]"  value="{{$row['precio']}}">
                                                      <input type="hidden" id="cantidad_entregada{{$key}}" name="cantidad_entregada[]"  value="{{$row['cantidad_entregada']}}">
                                                      <input type="hidden" id="valorizacion{{$key}}" name="valorizacion[]"  value="{{$row['valorizacion']}}">
                                                      <input type="hidden" id="correlativo{{$key}}" name="correlativo[]"  value="{{$row['correlativo']}}">

                                                      <input type="hidden" id="id_producto1{{$key}}" name="id_producto1[]"  value="{{$row['id_producto']}}">
                                                      <input type="hidden" id="id_producto2{{$key}}" name="id_producto2[]">
                                                      <input type="hidden" id="id_producto_anulado{{$key}}" name="id_producto_anulado[]" value="{{ old('id_producto_anulado.'.$key, '') }}">

                                                      <input type="hidden" class="select-diagnostico" value="1">
                                                      
                                                      <?php echo $row['nombre_producto']?>
                                                    </td>
                                                    <td>
                                                      <?php echo $row['stock_farmacia']?>
                                                    </td>
                                                    <td>
                                                      <?php echo $row['tiempo_periodo']?>
                                                    </td>
                                                    <td>
                                                      <?php echo $row['via_administracion']?>
                                                    </td>
                                                    <td>
                                                      <?php echo $row['posologia']?>
                                                    </td>
                                                    <td>
                                                      <?php echo $row['cantidad_prescrita']?>
                                                    </td>
                                                    <td>
                                                      <?php echo $row['cantidad_entregada']?>
                                                    </td>
                                                    <td>
                                                      <?php echo $row['nombre_diagnostico']?>
                                                    </td>
                                                    <td>
                                                    <button type="button"  class="btn btn-danger deleteFilaProductoAnulado btn-xs" style="margin-left:4px">
                                                      <i class="fa fa-times"></i> 
                                                    </button>
                                                    </td>
                                                  </tr>
                                                  <?php endforeach;
                                                  endif;
                                                   ?>
                                                  @if(old('id_producto'))
                                                    @foreach(old('id_producto') as $index => $id_producto)
                                                    <div style="display: none">
                                                      <select class="form-control" id="cmbMedicamentoTemp" tabindex="73"
                                                        style="width: 500px">
                                                        <option value="">Seleccionar Producto</option>
                                                        <?php foreach($producto as $row):?>
                                                          <option value="<?php echo $row->id?>">
                                                            <?php echo $row->nombre?></option>
                                                          <?php endforeach;?>
                                                      </select>
                                                    </div>
                                                    <tr>
                                                      <td></td>
                                                      <td>
                                                      
                                                      <input type="hidden" id="id_producto_miselect{{ $index }}" value="1">
                                                      <select class="form-control cmbMedicamento" id="cmbMedicamento{{$index}}"   name="id_producto[]" style="width: 450px"></select>
                                                      </td>
                                                      <input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>
                                                      <input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>
                                                      <td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:70px" readonly="" value="{{ old('nro_stocks')[$index] }}"/>
                                                  
                                                      <td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="{{$index+1}}" required="" id="tiempo_periodo{{$index}}" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:70px" value="{{ old('tiempo_periodo')[$index] }}"/>
                                                      <td>
                                                      <select tabindex="{{$index+1}}" class="form-control id_via_administracion" id="cmbTipoViaAdministracion{{$index}}" name="id_via_administracion[]" style="margin:0 20px;width:130px">{{ old('id_via_administracion')[$index] }}</select></td>
                                                      <td><input type="text" name="posologia[]" tabindex="{{$index+2}}" required="" id="posologia{{$index}}" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:180px" value="{{ old('posologia')[$index] }}"/>
                                                      <input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>
                                                      <input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>
                                                      <input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>
                                                      <input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>
                                                      <input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>
                                                      <td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="{{$index+3}}" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados{{$index}}" value="{{ old('nro_med_solictados')[$index] }}" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:50px" /></td>
                                                      <td></td>
                                                      <td>
                                                        <input type="hidden" name="id_cie10_producto[]" id="id_cie10_producto{{$index}}" value="{{ old('id_cie10_producto')[$index] }}">
                                                        <input type="hidden" name="codigo_producto[]" id="codigo_producto{{$index}}" value="{{ old('codigo_producto')[$index] }}">
                                                        <select tabindex="{{$index+4}}" class="form-control select-diagnostico"  id="miSelectProd{{$index}}" name="miSelectProd[]"></select>
                                                      </td>
                                                      <td>
                                                        <button type="button" class="btn btn-primary btn-xs" id="add_producto{{$index}} "><i class="fa fa-plus"></i> </button> 
                                                        <button type="button"  class="btn btn-danger deleteFilaProducto btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>
                                                      </tr>
                                                    @endforeach
                                                  @endif
                                                </tbody>
                                             </table>
                                          </div>
                                        </div>
                                    </div>
                                </fieldset>

                                {{--
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>Diagnósticos de Alta</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <table>
                                        <tr>
                                          <td>
                                            <button type="button" id="addDiagnosticoAlta" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar
                                              Diagnostico</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <table class="table table-bordered"   id="tblDiagnosticoAlta" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                            <th width="90%" style="text-align: center;">NOMBRE</th>
                                            <th width="10%" style="text-align: center;">TIPO</th>
                                            <th width="10%" style="text-align: center;">CLASIFICACIÓN</th>
                                            <th width="10%" style="text-align: center;">OPCIÓN</th>
                                          </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                          @if (!empty($data['diagnosticos_alta']))
                                            @foreach ($data['diagnosticos_alta'] as $key => $row)
                                                @php
                                                    $isEliminadoDiagnosticosAlta = !empty(old('id_diagnostico_alta_anulado.' . $key));
                                                @endphp
                                            <tr class="{{ $isEliminadoDiagnosticosAlta ? 'eliminado' : '' }}">
                                            <td>
                                              <input type="hidden" id="renipress_alta{{$key}}" value="{{$row['renipress']}}">
                                              <input type="hidden" id="numero_lote_fups_alta{{$key}}" value="{{$row['numero_lote_fups']}}">
                                              <input type="hidden" id="numero_fups_alta{{$key}}" value="{{$row['numero_fups']}}">
                                              <input type="hidden" id="id_cie10_alta{{$key}}" value="{{$row['id_cie10']}}">
                                              <input type="hidden" id="id_identificador_diag_alta{{$key}}" value="{{$row['id_identificador_diag']}}">
                                              <input type="hidden" id="id_tipo_diag_alta{{$key}}" value="{{$row['id_tipo_diag']}}">
                                        
                                              <input type="hidden" id="id_diagnostico_alta{{$key}}" value="{{$row['id_diagnostico']}}">
                                              <input type="hidden" id="tipo_diag_alta{{$key}}" value="{{$row['tipo_diag']}}">
                                              <input type="hidden" id="descripcion_alta{{$key}}" value="{{$row['descripcion']}}">

                                              <input type="hidden" id="id_diagnostico_alta{{$key}}" name="id_diagnostico_alta[]"  value="{{$row['id_diagnostico']}}">
                                              <input type="hidden"  id="id_diagnostico_alta2{{$key}}" name="id_diagnostico_alta2[]" >
                                              <input type="hidden" class="form-control id_diagnostico_alta typeahead tt-query" id="id_diagnostico_alta_anulado{{$key}}" name="id_diagnostico_alta_anulado[]" value="{{ old('id_diagnostico_alta_anulado.'.$key, '') }}">

                                              {{$row['id_cie10']}}-{{$row['descripcion']}}
                                            </td>
                                            <td>
                                               @if($row['id_tipo_diag'] == 2)
                                                    DEFINITIVO
                                                @endif
                                            </td>
                                            <td>
                                             @if($row['tipo_diag'] == 0)
                                                  SECUNDARIO
                                              @elseif($row['tipo_diag'] == 2)
                                                  DIAGNOSTICO DE CABECERA
                                              @endif
                                            </td>
                                            <td><button type="button" class="btn btn-danger deleteFilaDiagnosticoAlta btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>
                                            </tr>
                                             @endforeach
                                          @endif
                                          

                                          @if(old('nombre_diagnostico_alta'))
                                          @foreach(old('nombre_diagnostico_alta') as $index => $nombre_diagnostico_alta)

                                              <tr>
                                                  <td>
                                                      <input type="hidden" id="datosretorno_diagnostico_alta{{ $index }}"  value="1">
                                                      <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" id="nombre_diagnostico_alta{{ $index }}" name="nombre_diagnostico_alta[]" value="{{ $nombre_diagnostico_alta }}" class="input-sm" style="margin-left:4px; width:100%; text-align:left">
                                                      <input type="hidden" class="form-control id_diagnostico_alta" id="id_diagnostico_alta{{ $index }}" name="id_diagnostico_alta_nuevos[]" data-id="{{ $index }}" value="{{ old('id_diagnostico_alta_nuevos')[$index] }}">
                                                  </td>
                                                  <td>
                                                    <input type="hidden" id="tipo_diagnostico_alta{{ $index }}" name="tipo_diagnostico_alta[]"  value="2">
                                                    DEFINITIVO
                                                  </td>
                                                  <td>
                                                      <select class="form-control id_clasificacion_alta" name="id_clasificacion_alta[]"  style="margin:0 20px;width:150px">
                                                          <option value="N">--Seleccionar--</option>
                                                          <option value="0" {{ old('id_clasificacion_alta')[$index] == 0 ? 'selected' : '' }}>SECUNDARIO</option>
                                                          <option value="2" {{ old('id_clasificacion_alta')[$index] == 2 ? 'selected' : '' }}>DIAGNOSTICO DE CABECERA</option>
                                                      </select>
                                                  </td>
                                                  <td>
                                                      <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
                                                  </td>
                                              </tr>
                                          @endforeach
                                          @endif
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </fieldset>
                                --}}
                                @if($tipo_atencion!='1')
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b> Especifica Indicaciones de Alta</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">

                                      <div class="form-group">
                                        <div class="col-md-3">
                                          <label >Tipo de Egreso <span class="asterisco"></span></label>
                                          <select class="form-control input-sm" name="tipo_egreso">
                                            <option value="1" {{ old('tipo_egreso', $data['tipo_egreso']) == 1 ? 'selected' : '' }}>Alta</option>
                                            <option value="2" {{ old('tipo_egreso', $data['tipo_egreso']) == 2 ? 'selected' : '' }}>Hospitalización</option>
                                            <option value="3" {{ old('tipo_egreso', $data['tipo_egreso']) == 3 ? 'selected' : '' }}>Traslado</option>
                                            <option value="4" {{ old('tipo_egreso', $data['tipo_egreso']) == 4 ? 'selected' : '' }}>Fallecido</option>
                                            <option value="6" {{ old('tipo_egreso', $data['tipo_egreso']) == 6 ? 'selected' : '' }}>Fuga</option>
                                            <option value="7" {{ old('tipo_egreso', $data['tipo_egreso']) == 7 ? 'selected' : '' }}>Retiro Voluntario</option>
                                          </select>
                                          @if ($errors->has('tipo_egreso'))
                                            <span style="color: red;">{{ $errors->first('tipo_egreso') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-3">
                                          <label >Condición de egreso <span class="asterisco"></span></label>
                                          <select class="form-control input-sm" name="condicion_egreso">
                                            <option value="1" {{ old('condicion_egreso', $data['condicion_egreso']) == 1 ? 'selected' : '' }}>Mejorado</option>
                                            <option value="2" {{ old('condicion_egreso', $data['condicion_egreso']) == 2 ? 'selected' : '' }}>Curado</option>
                                            <option value="3" {{ old('condicion_egreso', $data['condicion_egreso']) == 3 ? 'selected' : '' }}>Transferido</option>
                                            <option value="4" {{ old('condicion_egreso', $data['condicion_egreso']) == 4 ? 'selected' : '' }}>Retiro Voluntario</option>
                                            <option value="5" {{ old('condicion_egreso', $data['condicion_egreso']) == 5 ? 'selected' : '' }}>Fugado</option>
                                            <option value="6" {{ old('condicion_egreso', $data['condicion_egreso']) == 6 ? 'selected' : '' }}>Fallecido</option>
                                          </select>
                                          @if ($errors->has('condicion_egreso'))
                                            <span style="color: red;">{{ $errors->first('condicion_egreso') }}</span>
                                          @endif
                                        </div>
                                        <div class="col-md-3">
                                          <label > Fecha Egreso<span class="asterisco"></span></label>
                                          <?php 
                                          $fecha_egreso = date('Y-m-d',strtotime($data['fecha_egreso']));
                                          ?>
                                          <input tabindex="19" type="date" class="form-control"   name="fecha_egreso" id="fecha_egreso"  value="{{ old('fecha_egreso', $fecha_egreso) }}" style="{{ $errors->has('fecha_egreso') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('fecha_egreso'))
                                          <span style="color: red;">{{ $errors->first('fecha_egreso') }}</span><br>
                                          @endif
                                        </div>
                                        <div class="col-md-3">
                                          <label >Hora Egreso <span class="asterisco"></span></label>
                                          <?php 
                                          $hora_egreso = date('H:m:s',strtotime($data['hora_egreso']));
                                          ?>
                                          <input tabindex="19" type="time" class="form-control"   name="hora_egreso" id="hora_egreso"value="{{ old('hora_egreso', $hora_egreso) }}" style="{{ $errors->has('hora_egreso') ? 'border: 1px solid red;' : '' }}">
                                          @if($errors->has('hora_egreso'))
                                          <span style="color: red;">{{ $errors->first('hora_egreso') }}</span><br>
                                          @endif
                                        </div>
                                      </div>

                                    </div>
                                </fieldset>
                                @endif
                                <input tabindex="140" class="btn btn-primary pull-right" value="GUARDAR" name="crea"
                                  type="button" form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp;
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              </fieldset>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('frontend.includes.footer')
  @endsection