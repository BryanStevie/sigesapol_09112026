@extends('frontend.layouts.master')
@section('rendercss')

<style>
  fieldset, legend {
   all: revert;
  }
  .example .btn-toggle {
    top: 50%;
    transform: translateY(-50%);
  }
  .btn-toggle {
    margin: 0 4rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle:focus,
  .btn-toggle.focus,
  .btn-toggle:focus.active,
  .btn-toggle.focus.active {
    outline: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    line-height: 1.5rem;
    width: 4rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle:before {
    content: 'NO';
    left: -4rem;
  }
  .btn-toggle:after {
    content: 'SI';
    right: -4rem;
    opacity: 0.5;
  }
  .btn-toggle>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.active:before {
    opacity: 0.5;
  }
  .btn-toggle.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    display: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    color: #6b7381;
  }
  .btn-toggle.active {
    background-color: #29b5a8;
  }
  .btn-toggle.btn-lg {
    margin: 0 5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 2.5rem;
    width: 5rem;
    border-radius: 2.5rem;
  }
  .btn-toggle.btn-lg:focus,
  .btn-toggle.btn-lg.focus,
  .btn-toggle.btn-lg:focus.active,
  .btn-toggle.btn-lg.focus.active {
    outline: none;
  }
  .btn-toggle.btn-lg:before,
  .btn-toggle.btn-lg:after {
    line-height: 2.5rem;
    width: 5rem;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-lg:before {
    content: 'NO';
    left: -5rem;
  }
  .btn-toggle.btn-lg:after {
    content: 'SI';
    right: -5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-lg>.handle {
    position: absolute;
    top: 0.3125rem;
    left: 0.3125rem;
    width: 1.875rem;
    height: 1.875rem;
    border-radius: 1.875rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-lg.active>.handle {
    left: 2.8125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-lg.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-sm:before,
  .btn-toggle.btn-lg.btn-sm:after {
    line-height: 0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.6875rem;
    width: 3.875rem;
  }
  .select2-container {
    max-width: 1100px; 
  }
  
</style>

@endsection

@section('renderjs')

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}

<script>
document.addEventListener("DOMContentLoaded", function(){

    var pnp_nopnp = 1;
    var boton = document.getElementById("tipo_ipress");

    boton.addEventListener("click", function(e){

        e.preventDefault();
        e.stopPropagation(); // evita que otros scripts del sistema lo usen

        if(pnp_nopnp == 1){

            this.innerHTML = "IPRESS NO PNP";
            this.style.backgroundColor = "#2E86C1";
            document.getElementById("div_pnp").style.display = "none";
            document.getElementById("div_no_pnp").style.display = "block";

            pnp_nopnp = 0;

        }else{

            this.innerHTML = "IPRESS PNP";
            this.style.backgroundColor = "#27AE60";
            document.getElementById("div_pnp").style.display = "block";
            document.getElementById("div_no_pnp").style.display = "none";

            pnp_nopnp = 1;

        }

    });

});

  function getDatosIpress(id_establecimiento){
    $.ajax({
        url: "/get_ipress/"+id_establecimiento,
        type: "GET",
        success: function (result) {  
          var data = JSON.parse(result);
          $('#ipress_origen_nombre').val(data.nombre);
          $('#ipress_ruc').val(data.ruc);
          $('#ipress_red_regional').val(data.red_region);
         
        }
    });
  }

  $('#btnSolicitud').on("click", function(e) {
    guardareferencia();
  });
$(document).ready(function(){

    $('#establecimiento_destino_pnp').select2({
        placeholder: "Buscar IPRESS por código o nombre",
        allowClear: true,
        width: '100%'
    });

});
  function guardareferencia(){
    var msg = "";
    var msg1 = "";
    var addcoordinacion = $('#addcoordinacion').attr("aria-pressed");
    var establecimiento_destino = $('#establecimiento_destino').val();
    var establecimiento_destino_pnp = $('#establecimiento_destino_pnp').val();
    if(addcoordinacion == "false" && establecimiento_destino_pnp == '0'){
      var msg1 = "Debe Coordinar Con un Responsable de la Ipress Destino "+msg1;
    }
    if(establecimiento_destino == '0' && establecimiento_destino_pnp == '0'){
      var msg = "Debe Seleccionar una IPRESS destino "+msg;
    }

    if(msg!="" || msg1 != ""){
            bootbox.alert(msg1);
            bootbox.alert(msg);
            $('#btnSolicitud').prop("disabled",false);
            $('.loader').hide(); 
            return false;
        }
        else{
          $("#frmSolicitud").submit();
        }
  }

  $('#addIpressNoPNP').on('click', function () {
    var addIpressNoPNP = $('#addIpressNoPNP').attr("aria-pressed");
    if (addIpressNoPNP == "false") {
      $("#form_ipress_no_pnp").show();
      $('#ipress_no_pnp_envio').val("true");
    } else {
      $("#form_ipress_no_pnp").hide();
      $('#nivel_categoria').val('');
      $('#id_uci_referencia').val('');
      $('#id_uci').attr('aria-pressed', 'false');
      $('#id_uci').removeClass('active');
      $('#ventilador_mecanico').val('');
      $('#ventilador_mec').attr('aria-pressed', 'false');
      $('#ventilador_mec').removeClass('active');
      $('#ipress_no_pnp_envio').val("false");
    }
  });

  $('#addReqAmbulancia').on('click', function () {
    var addReqAmbulancia = $('#addReqAmbulancia').attr("aria-pressed");
    if (addReqAmbulancia == "false") {
      $("#form_requiere_ambulancia").show();
      $('#requiere_amb_flag').val("true");
    } else {
      $("#form_requiere_ambulancia").hide();
      $('#tipo_ambulancia').val('');
      $('#tipo_ruta').val('');
      $('#requiere_amb_flag').val("false");
    }
  });

  $('#id_uci').on('click', function () {
		var id_uci = $('#id_uci').attr("aria-pressed");
    $('#id_uci_referencia').val(id_uci);
  });

  $('#ventilador_mec').on('click', function () {
		var ventilador_mec = $('#ventilador_mec').attr("aria-pressed");
    $('#ventilador_mecanico').val(ventilador_mec);
  });
$('#establecimiento_destino').select2();

  $(document).on('click', '.btn-ver-atencion', function () {
    var url = $(this).data('url');
    $('#iframeVerAtencion').attr('src', url);
    $('#modalVerAtencion').modal('show');
  });


  $('#modalVerAtencion').on('hidden.bs.modal', function () {
    $('#iframeVerAtencion').attr('src', '');
  });

</script>
<script>
  $('#addProcedimiento').on('click', function() {
      addProcedimiento();
  });
  $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    var trId = obj.closest('tr').attr('id');
    $('#tblProcedimiento tbody tr#'+trId).remove();
  });

  function addProcedimiento() {
      var newRow = "";
      var ind = $('#tblProcedimiento tbody tr').length;

      if (ind > 0) {
          $('.id_servicio_Procedimiento').each(function() {
              var ind_tmp = $(this).data("id");
              if (ind_tmp => ind) {
                  ind = Number(ind_tmp) + 1;
              }
          });

          fila_medicamentos = $('#tblProcedimiento tbody').children('tr');
          rowIndex = $('#hidden_rowIndex').val();
          var idval = ind - 1;
          fila_medicamento = fila_medicamentos[idval];

      }
      newRow += '<tr id="'+ind+'">';
        newRow += '<td >'+
                    '<label>TIPO DE PROCEDIMIENTO SOLICITADO</label>'+
                    '<select class="form-control input-sm " id="procedimientos'+ind+'" name="procedimientos[]" onchange="extraerDatosProcedimientos(this,'+ind+')"></select>'+
                  '</td>';
        newRow +='<td class="col-md-2">'+
                  '<label>CODIGO PROCEDIMIENTO</label>'+
                  '<input value="" type="text" name="procedimiento_codigo" id="procedimiento_codigo'+ind+'" class="form-control input-sm">'+
                  '</td>';
      newRow += '</tr>';

      newRow += '<tr id="'+ind+'">';
        newRow +='<td>'+
                    '<label>DESCRIPCION DE PROCEDIMIENTO SOLICITADO</label>'+
                    '<textarea class="form-control input-sm" name="procedimiento_descripcion[]" id="procedimiento_descripcion'+ind+'" rows="4" style="width: 100%;"></textarea>'+
                  '</td>';
          newRow +='<td  class="col-md-4">'+
                  '<label>MOTIVO</label>'+
                  '<input value="" type="text" name="procedimiento_motivo[]" id="procedimiento_motivo'+ind+'" class="form-control input-sm">'+
                '</td>';
        newRow += '<td class="col-md-2">'+
                  '<button  type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>'+
                '</td>';
        newRow +=
            '<input type="hidden" class="form-control typeahead tt-query id_servicio_Procedimiento" id="id_servicio_Procedimiento' +ind + '" name="id_servicio_Procedimiento[]" value="" data-id=' + ind + '></td>';
      newRow += '</tr> ';

      newRow += '<tr id="'+ind+'">';
        newRow += '<td colspan="3">'+
                  '<hr>'+
                '</td>';
      newRow += '</tr> ';
      $('#tblProcedimiento tbody').append(newRow);
      getProcedimientos(ind);//Trae todos los procedimientos con estado 1 para cada combo
      $("#procedimientos"+ind).select2({
        width: 'resolve'
      });  
      
  }

  function extraerDatosProcedimientos(selectElement,id) {
    var codigo = selectElement.options[selectElement.selectedIndex].getAttribute('data-codigo');
    var descripcion = selectElement.options[selectElement.selectedIndex].getAttribute('data-descripcion');
    $('#procedimiento_codigo'+id).val(codigo);
    $('#procedimiento_descripcion'+id).val(descripcion);
    $('#procedimiento_codigo'+id).prop('disabled',true);
    $('#procedimiento_descripcion'+id).prop('disabled',true);
  }
  
  function getProcedimientos(idselector){
    $.ajax({
        url: "/get_procedimientos",
        type: "GET",
        success: function (result) {  
          var data = JSON.parse(result);
          $('#procedimientos'+idselector).empty();  
          $('#procedimientos'+idselector).append("<option value=''>--SELECCIONAR--</option>");  
          $(data).each(function(id, dato) { 
              $('#procedimientos'+idselector).append("<option value='" + dato.id + "' data-codigo='"+dato.codigo+"' data-descripcion='"+dato.descripcion+"'>" + dato.descripcion + "</option>");  
          });
        }
    });
      
  }

</script>
@endsection
@section('content')

<div class="loader"></div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">MÓDULO DE GESTIÓN DE NUEVA SOLICITUD</h3>
        </div>
        <div class="panel-body">
          <form class="app-form" method="post" action="{{ route('registrar_solicitud_formulario')}}" id="frmSolicitud" name="frmSolicitud" autocomplete="off">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
            <input type="hidden" value="" name="ipress_no_pnp_envio" id="ipress_no_pnp_envio">
            <input type="hidden" value="" name="ventilador_mecanico" id="ventilador_mecanico">
            <input type="hidden" value="" name="requiere_amb_flag" id="requiere_amb_flag">
            <input type="hidden" value="" name="id_uci_referencia" id="id_uci_referencia">
            <input type="hidden" value="{{ $id_referencia }}" name="id_referencia" id="id_user">
            <div class="app-panel-title app-mt-4 app-mb-3"><strong>DATOS IPRESS ORIGEN</strong></div>
           
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>IPRESS ORIGEN</label>
                  <input value="" type="text" name="ipress_origen_nombre" id="ipress_origen_nombre" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>RUC IPRESS ORIGEN</label>
                  <input value="<?php echo 20504380077 ?>" type="text" name="" id="" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>RED REGIONAL IPRESS</label>
                  <input value="" type="text" name="ipress_red_regional" id="ipress_red_regional" class="form-control input-sm" disabled>
                </div>
              </div>
              
            </div>

            <div class="app-panel-title app-mt-4 app-mb-3"><strong>DATOS DEL PACIENTE</strong></div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>TIPO DE DOCUMENTO</label>
                  <input value="<?php echo $asegurado->tipo_doc_ident ?>" type="text" name="tipo_documento" id="tipo_documento" class="form-control input-sm"  disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>NUMERO DE DOCUMENTO</label>
                  <input value="<?php echo $asegurado->nro_doc_ident ?>" type="text" name="numero_documento" id="numero_documento" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>PARENTESCO</label>
                  <input value="<?php echo $asegurado->parentesco ?>" type="text" name="parentesco" id="parentesco" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>GRADO</label>
                  <input value="<?php echo $asegurado->grado ?>" type="text" name="grado" id="grado" class="form-control input-sm" disabled>

                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>APELLIDO PATERNO</label>
                  <input value="<?php echo $asegurado->paterno ?>" type="text" name="apellido_paterno" id="apellido_paterno" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>APELLIDO MATERNO</label>
                  <input value="<?php echo $asegurado->materno ?>" type="text" name="apellido_materno" id="apellido_materno" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>NOMBRES</label>
                  <input value="<?php echo $asegurado->nombre ?>" type="text" name="nombres" id="nombres" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>CIP</label>
                  <input value="<?php echo $asegurado->cip ?>" type="text" name="cip" id="cip" class="form-control input-sm" disabled>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>FECHA NACIMIENTO</label>
                  <input value="<?php echo $asegurado->fecha_nac ?>" type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>SEXO</label>
                  <input value="<?php echo $asegurado->sexo ?>" type="text" name="sexo" id="sexo" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>TIPO DE SANGRE</label>
                  <input value="<?php echo $asegurado->tipo_sangre ?>" type="text" name="tipo_sangre" id="tipo_sangre" class="form-control input-sm" >
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>HISTORIA CLINICA</label>
                  <input value="<?php echo $asegurado->nro_historia ?>" type="text" name="hc" id="hc" class="form-control input-sm" disabled>

                </div>
              </div>
            
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>TELEFONO</label>
                  <input value="<?php echo $asegurado->telefono ?>" type="text" name="telefono" id="telefono" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>CORREO</label>
                  <input value="<?php echo $asegurado->correo ?>" type="text" name="correo" id="correo" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>DEPARTAMENTO</label>
                  <input value="<?php echo $asegurado->departamento ?>" type="text" name="departamento" id="departamento" class="form-control input-sm" disabled>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>CIUDAD</label>
                  <input value="<?php echo $asegurado->distrito ?>" type="text" name="ciudad" id="ciudad" class="form-control input-sm" disabled>

                </div>
              </div>
            </div>

                                      <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                          <div class="panel-title text-center">
                                            <b>DATOS DE LA REFERENCIA</b>
                                          </div>
                                        </div>

                                        <div class="panel-body">
                                          <table class="displa"
                                            id="tblDiagnostico111"
                                            cellspacing="0"
                                            width="100%"
                                            style="margin-top:5px;">

                                            <thead>
                                              <tr>
                                                <th width="23%">Establecimiento Origen</th>
                                                <th width="22%">Servicio Origen</th>
                                                <th width="22%">Servicio Destino</th>
                                                <th width="28%">Motivo de Referencia</th>
                                                <th width="5%" class="text-center">Atención</th>
                                              </tr>
                                            </thead>

                                            <tbody>
                                              <tr>
                                                <td>
                                                  <?php echo $referencia->nombre_establecimiento ?>
                                                </td>

                                                <td>
                                                  <?php echo $referencia->nombre_servicio_origen ?>
                                                </td>

                                                <td>
                                                  <?php echo $referencia->nombre_servicio ?>
                                                </td>

                                                <td>
                                                  <?php echo $referencia->observacion_referencia ?>
                                                </td>

                                                <td class="text-center">

                                                  @if(!empty($referencia->id_prestacion))
                                                    <button type="button"
                                                      class="btn btn-warning btn-xs btn-ver-atencion"
                                                      title="Ver Atención"
                                                      data-url="{{ url('/ver_prestacion/'.Auth::user()->id.'/'.$referencia->id_prestacion) }}?solo_lectura=1">
                                                      <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                  @endif

                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                      <div class="modal fade"
                                        id="modalVerAtencion"
                                        tabindex="-1"
                                        role="dialog">

                                        <div class="modal-dialog"
                                          role="document"
                                          style="width:95%; height:90%;">

                                          <div class="modal-content"
                                              style="height:90vh;">

                                            <div class="modal-header">
                                              <button type="button"
                                                      class="close"
                                                      data-dismiss="modal">
                                                  &times;
                                              </button>
                                              <h4 class="modal-title">
                                                  Atención del Paciente
                                              </h4>
                                            </div>

                                            <div class="modal-body"
                                              style="padding:0; height:calc(90vh - 60px);">

                                              <iframe id="iframeVerAtencion"
                                                      src=""
                                                      frameborder="0"
                                                      style="width:100%; height:100%;">
                                              </iframe>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                              
           
         
<div class="app-panel-title app-mt-4 app-mb-3">
<strong>SELECCIONAR IPRESS DESTINO</strong>

<br><br>

<button tabindex="42"
        type="button"
        id="tipo_ipress"
        style="padding:10px 25px;
               font-size:16px;
               border:none;
               border-radius:20px;
               color:white;
               background-color:#27AE60;
               cursor:pointer;">

IPRESS PNP

</button>

</div>

<div class="col-md-12">

<div id="div_pnp" style="display:show; background:#dff5e1; padding:15px; border-radius:6px; border:1px solid #a6d8a8;">

<div class="form-group">
<label style="color:#1b5e20;"><strong>ESTABLECIMIENTO DE LA REFERENCIA PNP</strong></label>

<select class="form-control input-sm"
        id="establecimiento_destino"
        name="establecimiento_destino">

<option value="0">SELECCIONAR</option>

<?php foreach ($ipress as $row): ?>
<option value="<?= $row->id ?>">
<?= $row->nombre_ipress ?>
</option>
<?php endforeach; ?>

</select>
</div>

</div>
</div>



<div class="col-md-12">

<div id="div_no_pnp" style="display:none; background:#e3f2fd; padding:15px; border-radius:6px; border:1px solid #90caf9;">

<div class="form-group">
<label style="color:#0d47a1;"><strong>ESTABLECIMIENTO DE LA REFERENCIA NO PNP</strong></label>

<select class="form-control input-sm"
        id="establecimiento_destino_pnp"
        name="establecimiento_destino_pnp">

<option value="0">SELECCIONAR</option>

<?php foreach ($ipress_no_pnp as $row): ?>
<option value="<?= $row->id ?>">
<?= $row->id." - ".$row->nombre_ipress ?>
</option>
<?php endforeach; ?>

</select>

</div>

</div>

</div>
<div class="row">
<div class="col-md-12"><br>
 <div class="col-md-1"><br>
                        </div>
                          <div class="col-md-5">
                            <div class="form-group">
                              <label>SE COORDINO CON PERSONAL DE LA IPRESS</label>
                              <button tabindex="42" type="button" id="addcoordinacion" name="addcoordinacion" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                              </button>
                            </div>
                          </div>
                          
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>DATOS DEL PERSONAL DE COORDINACION</label> 
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <input value="" type="text" name="personal_coordinacion" id="personal_coordinacion" class="form-control input-sm"> 
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-1"><br>
                        </div>
                        <div class="col-md-1"><br>
                          <div class="form-group">
                            <label>DATOS DEL PERSONA ACOMPAÑANTE</label> 
                          </div>
                        </div>
                        <div class="col-md-4"><br>
                          <div class="form-group">
                            <input value="" type="text" name="nombre_personal_acoma" id="nombre_personal_acoma" class="form-control input-sm"> 
                          </div>
                        </div>
                        <div class="col-md-2"><br>
                          <div class="form-group">
                            <label>CELULAR DEL PERSONA ACOMPAÑANTE</label> 
                          </div>
                        </div>
                        <div class="col-md-2"><br>
                          <div class="form-group">
                            <input value="" type="text" name="celular_personal_acoma" id="celular_personal_acoma" class="form-control input-sm"> 
                          </div>
                        </div>
                        
                        </div>
                        </div>
                </div>

            <div class="app-panel-title app-mt-4 app-mb-3"><strong>VIA DE TRANSPORTE <button tabindex="42" type="button" name="addIpressNoPNP" id="addIpressNoPNP" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
              <div class="handle"></div>
            </button> </strong></div>

            <div id="form_ipress_no_pnp" style="display:none">
            <div class="row">
              <div class="col-md-1"><br>
               </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>VIA DE TRANSPORTE</label>
                  <select class="form-control input-sm " id="nivel_categoria" name="nivel_categoria"  tabindex="3">
                    <option value="">SELECCIONAR</option>
                    <option value="1">TERRESTE</option>
                    <option value="2">AEREO</option>
                    <option value="3">NAVAL</option>
                    <option value="4">TERRESTE ASISTIDO</option>
                    <option value="5">AEREO ASISTIDO</option>
                    <option value="6">NAVAL ASISTIDO</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3"><br>
                <div class="form-group">
                  <label>REQUIERE UCI</label>
                  <button tabindex="42" type="button" id="id_uci" name="id_uci" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                    <div class="handle"></div>
                  </button>
                </div>
              </div>
              <div class="col-md-3"><br>
                <div class="form-group">
                  <label>VENTILADOR MECANICO</label>
                  <button tabindex="42" type="button" id="ventilador_mec" name="ventilador_mec" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                    <div class="handle"></div>
                  </button>
                </div>
              </div>
            </div>
            </div>
                        
            <div class="app-panel-title app-mt-4 app-mb-3"><strong>TRASLADO EN AMBULANCIA<button tabindex="42" type="button" name="addReqAmbulancia" id="addReqAmbulancia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
              <div class="handle"></div>
            </button> </strong></div>

            <div id="form_requiere_ambulancia" style="display:none">
                <div class="row">
                  <div class="col-md-1"><br>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>TIPO DE AMBULANCIA</label>
                      <select class="form-control input-sm" id="tipo_ambulancia" name="tipo_ambulancia"  tabindex="3">
                        <option value="">SELECCIONAR</option>
                        <option value="1">AMBULANCIA TIPO I</option>
                        <option value="2">AMBULANCIA TIPO II</option>
                        <option value="3">AMBULANCIA TIPO III</option>
                        <option value="4">AMBULANCIA TIPO IV</option>
                        <option value="5">AMBULANCIA TIPO V</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>TIPO DE RUTA</label>
                      <select class="form-control input-sm" id="tipo_ruta" name="tipo_ruta"  tabindex="3">
                        <option value="">SELECCIONAR</option>
                        <option value="1">IDA</option>
                        <option value="2">IDA Y VUELTA</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>          
                
            <input class="btn btn-primary pull-right" value="Guardar datos" name="crea" type="button" id="btnSolicitud" />
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="showProximasCitas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg"></div>
      </div>
    
  </div>
</div>

@include('frontend.includes.footer')
@endsection