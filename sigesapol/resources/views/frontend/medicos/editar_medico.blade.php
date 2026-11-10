@extends('frontend.layouts.master')
@section('renderjs')

<style>
  #programacionAsistencial {
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 4px 4px;
  }

  /* Estilos para las pestañas */
  .tab-content {
    padding: 20px 0;
  }
  .nav-tabs {
    border-bottom: 2px solid #ddd;
  }
  .nav-tabs > li > a {
    margin-right: 2px;
    line-height: 1.42857143;
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
    color: #555;
  }
  .nav-tabs > li > a:hover {
    border-color: #eee #eee #ddd;
  }
  .nav-tabs > li.active > a,
  .nav-tabs > li.active > a:hover,
  .nav-tabs > li.active > a:focus {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
    font-weight: bold;
  }
</style>

<script>

$('#btnBuscar').on('click', function () {

    let periodo = $('#periodo').val();
    let id_personal = $('#id_personal').val();
    $.ajax({
        url: '/buscar_programacion_asistencial_1',
        type: 'GET',
        data: {
            periodo: periodo,
            id_personal: id_personal
        },
        success: function (response) {

            let html = '';

            if (response.length > 0) {

                response.forEach((item) => {
                    html += `
                        <tr>
                            <td>${item.periodo}</td>
                            <td>${item.id_establecimiento}</td>
                            <td>${item.horas_programadas}</td>
                            <td>${item.horas_efectivas}</td>
                            <td><button class="btn btn-info btn-sm">Ver</button></td>
                        </tr>
                    `;
                });

            } else {

                html = `
                    <tr>
                        <td colspan="6" style="text-align:center; font-weight:bold;">
                            SIN REGISTROS
                        </td>
                    </tr>
                `;
            }

            $('#tablaResumen tbody').html(html);
        }
    });
});

$('#btnNuevo').on('click', function () {

    var id_personal = $('#id_personal').val();
    var periodo = $('#periodo').val();
    var ipress = 0;

    if (!periodo) {
        alert('Ingrese el periodo');
        return;
    }
    periodo = periodo.replace(/\D/g, '');
    if (periodo.length !== 6) {
        alert('El periodo debe tener formato MMYYYY (ej: 042026)');
        return;
    }
    var mes = parseInt(periodo.substring(0, 2), 10);
    var anio = parseInt(periodo.substring(2, 6), 10);
    if (mes < 1 || mes > 12) {
        alert('El mes debe estar entre 01 y 12');
        return;
    }
    if (anio < 2000 || anio > 2100) {
        alert('El año no es válido');
        return;
    }
    var periodo_encoded = encodeURIComponent(periodo);
    window.location.href = '/editar_programacion_asistencial_1/' 
        + id_personal + '/' + periodo_encoded + '/' + ipress;
});

  function jsDecimals(e) {
    var evt = (e) ? e : window.event;
    var key = (evt.keyCode) ? evt.keyCode : evt.which;
    if (key != null) {
        key = parseInt(key, 10);
        if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
            if (!jsIsUserFriendlyChar(key, "Decimals")) {
                return false;
            }
        } else {
            if (evt.shiftKey) {
                return false;
            }
        }
    }
    return true;
  }
    
  $(document).ready(function () {
    $(".js-example-basic-multiple").select2({
        width: 'resolve',
        max_selected_options: 4
    });
        
    var selectedValues = new Array();
    <?php 
        $n = 0;
         foreach ($consultoriomedico as $rowConsul):
             ?>
                selectedValues[<?php echo $n?>] = "<?php echo $rowConsul->id_consultorio?>";
             <?php
             $n++;
         endforeach;
    ?>
    $('.js-example-basic-multiple').val(selectedValues).trigger('change')
    
    $("#btnReg").on("click", function () {		
        var id_establecimiento = $('#id_establecimiento').val();
        var id_colegio_profesional = $('#id_colegio_profesional').val();

        if (id_colegio_profesional == "") {
            bootbox.alert("Debe seleccionar un colegio profesional.");
            return false;
        }
        
        if (id_establecimiento != "") {		
            bootbox.confirm("Aviso: Revise los datos antes de actualizar.", function(result) {
                if (result==true) {
                    $("#frmlotizar").submit();
                }
            });
            return false;
        }
    });	
  });

  $('#addRow').on('click', function () {
    AddFila();
  });

  $('#example tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    bootbox.confirm("Desea eliminar la disponibilidad?", function(result) {
        if (result==true) {
            var id_del = obj.parent().parent().find('.id').val();
            var newDelete = '<input type="hidden" name="ids_delete[]" value="'+id_del+'" />';
            $('#example tbody').append(newDelete);
            obj.parent().parent().remove();
        }
    });
  });
		
  function AddFila(){
    var newRow = "";
    var ind = $('#example tbody tr').length;
    
    if(ind > 0){
        $('.cmbDia').each(function(){
            var ind_tmp = $(this).attr("ind");
            if(ind_tmp => ind)ind=Number(ind_tmp)+1;
        });
    }
    
    var item_dia = "";
    $('#diaTemp option').each(function(){
        item_dia += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
    });
    
    var item_turno = "";
    $('#turnoTemp option').each(function(){
        item_turno += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
    });
    
    newRow +='<tr>';
    newRow +='<td><select class="form-control cmbDia" id="dia'+ind+'" ind="'+ind+'" tabindex="12" name="dia[]" style="width:200px" >'+item_dia+'</select><input type="hidden" name="id[]" class="id" value=""/></td>';
    newRow +='<td><select class="form-control cmbTurno" id="turno'+ind+'" ind="'+ind+'" tabindex="12" name="turno[]" style="width:200px">'+item_turno+'</select></td>';
    newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="numero_atencion[]" required="" id="numero_atencion" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" value=""/>';
    newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    
    $('#example tbody').append(newRow);
  }

  $est = $("#frmlotizar").find("#id_establecimiento");
  $est.select2({
      width: 'resolve' // need to override the changed default
  });
</script>
@endsection
@if(session('error'))
<script>
    alert("{{ session('error') }}");
</script>
@endif
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Editar Personal de Salud</h3> 
        </div>
        <div class="panel-body">
          
          <!-- Pestañas -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#editarPersonal" aria-controls="editarPersonal" role="tab" data-toggle="tab">EDITAR PERSONAL</a>
            </li>
            <li role="presentation">
              <a href="#programacionAsistencial" aria-controls="programacionAsistencial" role="tab" data-toggle="tab">PROGRAMACIÓN ASISTENCIAL</a>
            </li>
          </ul>
          
          <!-- Contenido de las pestañas -->
          <div class="tab-content">
            <!-- Pestaña EDITAR PERSONAL -->
            <div role="tabpanel" class="tab-pane active" id="editarPersonal">
              <form method="post" action="{{ route('update_medico')}}" id="frmlotizar" name="frmlotizar" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input id="id_medico" name="id_medico" value="{{$medico->id}}" type="hidden">
                <input id="id_farmacia" name="id_farmacia" value="{{$id_farmacia}}" type="hidden">
                <div class="col-md-3">
                  <label>Tipo Documento</label>
                  <input value="{{$tipo_documento_text}}" class="form-control input-sm" readonly=""
                    onkeypress="return IsNumber(event)">
                  <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{$tipo_documento_id}}">
                </div>
                <div class="col-md-3">
                  <label>Documento</label>
                  <input value="{{$medico->dni}}" class="form-control input-sm" readonly=""
                    onkeypress="return IsNumber(event)">
                </div>
                <div class="col-md-3">
                  <label>Nombre</label>
                  <input value="{{$medico->nombre}}" class="form-control input-sm" readonly="" name="nombre"
                    onkeypress="return solo_letra(event)">
                </div>
                <div class="col-md-3">
                  <label>Ape. Paterno</label>
                  <input value="{{$medico->paterno}}" class="form-control input-sm" readonly="" name="paterno"
                    onkeypress="return solo_letra(event)">
                </div>
                <div class="col-md-3" style="margin-top:10px">
                  <label>Ape. Materno</label>
                  <input value="{{$medico->materno}}" class="form-control input-sm" readonly="" name="materno"
                    onkeypress="return solo_letra(event)">
                </div>
                <div class="col-md-6" style="margin-top:10px">
                  <label>Colegio</label>
                  <select id="id_colegio_profesional" name="id_colegio_profesional" class="form-control input-sm" required="">
                    <option value="">--Seleccionar--</option>
                    @foreach($colegio_profesional as $col)
                    <option value="{{$col->id}}"
                      <?php if ($col->id == $medico->id_colegio_profesional) echo "selected='selected'"; ?>>{{$col->descripcion}}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3" style="margin-top:10px">
                </div>
                <div class="col-md-6" style="margin-top:10px">
                  <label>Especialidad del personal de salud</label>
                  <select name="id_especializacion" class="form-control input-sm" required="">
                    <option value="">--Seleccionar--</option>
                    @foreach($especializaciones as $esp)
                    <option value="{{$esp->id}}"
                      <?php if ($esp->id == $medico->id_especializacion) echo "selected='selected'"; ?>>{{$esp->nombre}}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:10px">
                  <label>Profesi&oacute;n</label>
                  <select name="id_profesion" class="form-control input-sm" required="">
                    <option value="">Seleccionar</option>
                    @foreach($profesion as $e)
                    <option value="{{$e->id}}" <?php if ($e->id == $medico->id_profesion) echo "selected='selected'"; ?>>
                      {{$e->nombre}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3" style="margin-top:10px">
                  <label>Colegiatura</label>
                  <input name="colegiatura" value="{{$medico->colegiatura}}" class="form-control input-sm" maxlength="6">
                </div>
                <div class="col-md-3" style="margin-top:10px">
                  <label>Nro. RNE</label>
                  <input id="rne" name="rne" type="text" value="{{$medico->rne}}" placeholder=""
                    class="form-control input-sm" onkeydown="return jsDecimals(event);">
                </div>
                <div class="col-md-6" style="margin-top:10px">
                  <label>E-mail</label>
                  <input name="email" type="email" value="{{$medico->email}}" class="form-control input-sm">
                </div>
                
                <div class="clearfix"></div>
                <div class="col-md-6" style="margin-top:10px">
                  <label>Contrato</label>
                  <select name="tipo_contrato" class="form-control input-sm" required="">
                    <option value="">Seleccionar</option>
                    <?php foreach ($tipo as $rowTipo): ?>
                    <option value="<?php echo $rowTipo->id ?>"
                      <?php if ($rowTipo->id == $medico->tipo_contrato) echo "selected='selected'"; ?>>
                      <?php echo $rowTipo->nombre ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-md-6" style="margin-top:10px">
                  <label>Asignar nueva IPRESS PNP</label>
                  <select name="id_establecimiento" id="id_establecimiento" class="form-control input-sm">
                    <option value="">Seleccionar</option>
                    @foreach($establecimientos as $e)
                    <option value="{{$e->id}}" @if ($e->id == $medico->id_establecimiento) selected="selected" @endif >
                      {{$e->nombre}}
                    </option>
                    @endforeach
                  </select>
                </div>
                
                <div class="col-md-6" style="margin-top:10px">
                  <label>Foto Firma</label>
                  <input type="file" name="archivo_firma" id="archivo_firma" class="form-control input-sm">
                  @if($medico->nombre_firma != null)
                  <div style="text-align:center">
                    <img src="/images/profesionales/{{$medico->nombre_firma}}" alt="" width="220px" class="img-thumbnail">
                  </div>
                  @endif
                </div>

                <div class="col-md-12" style="margin-top:10px">
                  <label>Servicio de Atenci&oacute;n</label>
                  <select class="form-control js-example-basic-multiple" name="id_consultorio[]" required=""
                    style="width: 100%" multiple="multiple">
                    @foreach($consultorio as $e)
                    <option value="{{$e->id}}" <?php if ($e->id == $medico->id_consultorio) echo "selected='selected'"; ?>>
                      {{$e->nombre}}</optio>
                      @endforeach
                  </select>
                  <p class="text-danger"><b>(*)</b> Si elimina un Servicio, se desactivará y se cancelará la programación de
                    sus atenciones a partir de mañana.</p>
                </div>
                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                  <a href="/gestion_medicos" class="form-control btn btn-danger">Cancelar</a>
                </div>
                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                  <input id="btnReg" class="btn btn-success form-control" value="Actualizar" type="submit">
                </div>
              </form>
            </div>
            
            <!-- Pestaña PROGRAMACIÓN ASISTENCIAL -->
          <div role="tabpanel" class="tab-pane" id="programacionAsistencial">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Programación Asistencial</h3>
    </div>
    <div class="panel-body" style="padding:0; overflow-x: auto;">
      <!----****----->
      <div class="container" style="margin-top: 20px;">
          <div class="panel panel-success">
              <div class="panel-heading">
                  <strong>Búsqueda de Programación</strong>
              </div>
                <div class="panel-body">
                    <form class="form-inline" id="formBusqueda">
                        <div class="form-group col-md-3">
                            <label for="periodo">Periodo</label>                            
                            <input type="hidden" id="id_personal" name="id_personal" class="form-control" value="{{$medico->id}}" maxlength="7" required>
                            <input type="text" id="periodo" name="periodo" class="form-control" placeholder="mm/aaaa" maxlength="7" required>
                        </div>
                        <button type="button" class="btn btn-primary" id="btnBuscar">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                        <button type="button" class="btn btn-success" id="btnNuevo">
                            <i class="fa fa-plus"></i> Nuevo
                        </button>
                    </form>
                  </div>
              </div>
              <div class="panel panel-default">
                  <div class="panel-heading bg-lightgreen">
                      <strong>Resumen de Programación</strong>
                  </div>
                  <div class="panel-body" style="padding: 0">
                    <table class="table table-bordered table-striped" id="tablaResumen" style="font-size: 13px; margin: 0">
                        <thead>
                            <tr>
                                <th style="width: 5%">PERIODO</th>
                                <th style="width: 20%">IPRESS</th>
                                <th style="width: 6%">T. Hrs. Prog.</th>
                                <th style="width: 6%">T. Hrs. Efec.</th>
                                <th style="width: 2%">DETALLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($programacion_asistencial as $a)
                            <tr>
                                <td>{{ $a->periodo }}</td>
                                <td>{{ $a->id_establecimiento }}</td>
                                <td>{{ $a->horas_programadas }}</td>
                                <td>{{ $a->horas_efectivas }}</td>
                                <td><a href="/editar_programacion_asistencial_2/{{ $medico->id }}/{{ $a->periodo }}/{{ $a->id_establecimiento }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; font-weight:bold;">
                                    SIN REGISTROS
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                  </div>
              </div>
          </div>
      <!----****----->
    </div>
  </div>
</div>
          </div>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection