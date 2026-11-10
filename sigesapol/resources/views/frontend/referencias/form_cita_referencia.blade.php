@extends('frontend.layouts.master')

@section('rendercss')

<link href='../js/fullcalendar-2.3.2/fullcalendar.css' rel='stylesheet' />
<link href='../js/fullcalendar-2.3.2/fullcalendar.print.css' rel='stylesheet' media='print' />

{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<style>
  .fc-sun {
    background-color: #DCE2E8;
  }

  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }

  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    /*margin:10px;*/
    text-align: left;
    /*margin-top:20px;*/
    /*background:#5cb85c*/
    background: transparent;
    color: #000000
  }

  .fc-day-grid-event :hover {
    color: #000000
  }

  .fc-content {
    padding: 0px !important;
  }

  .fc-title {
    text-align: left;
    font-size: 10px
  }

  .suggest-element {
    margin-left: 5px;
    margin-top: 5px;
    width: 350px;
    cursor: pointer;
  }

  #suggestions {
    width: 350px;
    height: 150px;
    overflow: auto;
  }

  .loader {
    width: 100%;
    height: 100%;
    /*height: 1500px;*/
    overflow: hidden;
    top: 0px;
    left: 0px;
    z-index: 10000;
    text-align: center;
    position: absolute;
    background-color: #000;
    opacity: 0.6;
    filter: alpha(opacity=40);
    display: none;
  }

  .dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 500px !important;
    font-size: 1.7em;
    border: 0px;
    margin-left: -17% !important;
    text-align: center;
    background: #3c8dbc;
    color: #FFFFFF;
  }
  .fc-unthemed .fc-today {
    background: #E1D097!important;
  }
</style>

@endsection

@section('renderjs')
<script src='../js/fullcalendar-2.3.2/lib/moment.min.js'></script>
<script src='../js/fullcalendar-2.3.2/fullcalendar.min.js'></script>
<script src='../js/fullcalendar-2.3.2/lang/es.js'></script>

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}

<script>
  $(document).ready(function() {

    $cmbcons_referencia = $("#frmCita").find("#consultorio_referencia");
    $cmbcons_referencia.select2();
    $cmbest_referencia = $("#frmCita").find("#establecimiento_referencia");
    $cmbest_referencia.select2();
    $cmbserv_referencia = $("#frmCita").find("#servicio_referencia");
    $cmbserv_referencia.select2();
    


    $('input[name="parent"]').on("change", function(e) {
      getServicio();
    });

    $('#id_servicio,#id_establecimiento').on("change", function(e) {
      fullCalendar();
    });


    $('#btnCita').on("click", function(e) {
      guardarCitaAutomatico();
    });

    //Cargar beneficiario al iniciar la carga de la vista
    obtenerBeneficiario();
    mostrar();
  });
  function mostrar() {
    let referido = $('#referido').val();
    if (referido == "SI") {
        $("#establereferencia").show();
        $("#consultorio_x_servicio").hide();
    }else{
      $("#establereferencia").hide();
      $("#consultorio_x_servicio").show();
    }
}

  $("#dni_beneficiario").on("keydown", function (e) {
        var code = e.keyCode || e.which;
        if (code == '9' || code == '13') {
          obtenerBeneficiario();
        }
    });
  function guardarCitaAutomatico() {

    var msg = "";
    var id_establecimiento = $('#id_establecimiento').val();
    var id_servicio = $('#id_servicio').val();
    var fecha_atencion = $('#fecha_atencion').val();
    var dni_beneficiario = $("#dni_beneficiario").val();
    let id_tipo_cita = $("#id_tipo_cita").val();

    if (dni_beneficiario == "") msg += "Debe ingresar el Numero de Documento <br>";
    if (id_establecimiento == "") msg += "Debe seleccionar un Establecimiento<br>";
    if (id_servicio == "") msg += "Debe ingresar un Servicio<br>";
    if (id_tipo_cita == "") msg += "Debe ingresar un tipo de cita<br>";
    if ($('input[name=horario]').is(':checked') == false) msg += "Debe seleccionar un Turno<br>";

    if ($('input[name=horario]').is(':checked') == true) {
      var horario = $('input[name=horario]:checked').val();
      var data = horario.split("#");
      var fecha_cita = data[0];
      var id_medico = data[1];
    }

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    } else {
      fn_save_cita_automatico(id_medico, fecha_cita);
    }
  }

  function fn_save_cita_automatico(id_medico, fecha_cita) {
    var correo = $('#correo').val();
    var msgLoader = "";
    if (correo == "") msgLoader = "Procesando, espere un momento por favor";
    else msgLoader = "Enviado correo, espere un momento por favor";

    $('#btnCita').prop("disabled", true);

    var heightBrowser = $(window).width() / 2;

    $('.loader').css("opacity", "0.8").css("height", heightBrowser).html(
      "<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>" +
      msgLoader + "</div></div>");
    $('.loader').show();

    var fecha_atencion_original = $('#fecha_atencion').val();
    var id_user = $('#id_user').val();
    $.ajax({
      url: "registrar_cita_referencia",
      type: "POST",
      data: $("#frmCita").serialize() + "&id_medico=" + id_medico + "&fecha_cita=" + fecha_cita,
      success: function(result) {
        $('#btnCita').prop("disabled", false);
        $('.loader').hide();
         location.href = "ver_cita/" + id_user + "/" + result;
      }
    });
  }

  function modalProximaCita(tipodoc_beneficiario,dni_beneficiario){
    $("#showProximasCitas .modal-dialog").css("width","45%");
    
    $.ajax({
        url: "../modal_proxima_cita/"+tipodoc_beneficiario+"/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $('#diveditpreg').html(result);
            $('#showProximasCitas').modal('show');
        }
    });
    
  }

  function getarea_servicio (){ 
    var ubicacion = $('#establecimiento_referencia').val();

    if(ubicacion!=''){
      
      var servicios_consultorios='/busca_ubicacion/' + ubicacion;  
      $.ajax({
        url: servicios_consultorios,
        success: function(result) { 
          $('#servicio_referencia').empty();  
          $('#servicio_referencia').append("<option value=''>--SELECCIONAR--</option>");  
          console.log(result);
          var new_id=0;
          $(result).each(function(id, oo) {   

              $('#servicio_referencia').append("<option value='" + oo.id + "'>" + oo.nombre + "</option>");  
          }); 

        },
          error: function(jqXHR, textStatus, errorThrown) {

          }
      });
    }else{ 
        $('#servicio_referencia').empty();  
        $('#servicio_referencia').append("<option value=''>--SELECCIONAR--</option>"); 
        $('#servicio_referencia').val("").change();
    }
  }


  function obtenerBeneficiario() {

    var id_tiporeceta = 1;
    var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_beneficiario = $("#dni_beneficiario").val();
    var msg = "";

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    }

    $.ajax({
      url: '/obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
      dataType: "json",
      success: function(result) {
        if (result.sw == false) {
          bootbox.alert(result.msg);
          $('#beneficiario').val("");
          $('#tipo_beneficiario').val("");
          $('#nombre_beneficiario').val("");
          $('#paterno_beneficiario').val("");
          $('#materno_beneficiario').val("");
          $('#tipodoc_beneficiario').val("");
          $('#cip_beneficiario').val("");
          $('#grado').val("");

          $('#fecnacafiliado').val("");
          $('#nomsexo').val("");
          $('#nro_historia').val("");
          $('#domicilio').val("");
          $('#telefono').val("");
          $('#telefono2').val("");
          $('#correo').val("");
          $('#cip_beneficiario').val("");
          return false;
        }

        var beneficiario = result.beneficiario.apepatafiliado + " " + result.beneficiario.apematafiliado + " " +
          result.beneficiario.apecasafiliado + ", " + result.beneficiario.nomafiliado;
        $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
        $('#beneficiario').val(beneficiario);
        $('#tipo_beneficiario').val(result.beneficiario.parentesco);
        $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
        $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
        $('#materno_beneficiario').val(result.beneficiario.apematafiliado + " " + result.beneficiario.apecasafiliado);
        $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
        $('#cip_beneficiario').val(result.beneficiario.cip);
        $('#grado').val(result.beneficiario.grado);
        $('#fecnacafiliado').val(result.beneficiario.fecnacafiliado);
        $('#nomsexo').val(result.beneficiario.nomsexo);

        if (result.numero_historia != null) {
          if (result.numero_historia != '')
            $('#nro_historia').val(result.numero_historia);
          else
            $('#nro_historia').val(result.beneficiario.nrodocafiliado);
        } else
          $('#nro_historia').val(result.beneficiario.nrodocafiliado);

        $('#domicilio').val(result.domicilio);
        $('#telefono').val(result.telefono);
        $('#telefono2').val(result.telefono2);
        $('#correo').val(result.correo);
        $('#cip_beneficiario').val(result.beneficiario.cip);

        $('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
        $('#nrodoctitular').val(result.beneficiario.nrodoctitular);
        $('#nombre_titular').val(result.beneficiario.nomtitular);
        $('#paterno_titular').val(result.beneficiario.apepattitular);
        $('#materno_titular').val(result.beneficiario.apemattitular + " " + result.beneficiario.apecastitular);

        var parentesco = result.beneficiario.parentesco;
        var ubigeo = result.beneficiario.ubigeo;
        var establecimiento = result.establecimiento;
        var cita = result.cita;
        var id_establecimiento = 0;

        if (establecimiento[0] != undefined) {
          id_establecimiento = establecimiento[0].id_establecimiento;
          nombre_establecimiento = establecimiento[0].establecimiento;
          $('#nombre_establecimiento').html(nombre_establecimiento);

        }

        if (cita[0] != undefined) {
          if (cita[0].id_estado_cita == 1) { // ESTADO PENDIENTE AUN NO HA SIDO ATENDIDO LA CITA
            //bootbox.alert("El beneficiario tiene una cita programada");
            //return false;
            var id_servicio = 8;
            //$("#parent1").prop("checked", true);
            //var id_servicio = cita[0].id_servicio;
            //$("#parent2").prop("checked", true);
          }
          if (cita[0].id_estado_cita == 3) { //ESTADO DERIVADO
            var id_servicio = cita[0].id_sub_consultorio;
            //$('#parent').val("2");
            //$('#nombre_parent').html("DERIVADO");
            //var parent = $('input[name="parent"]:checked').val();
            $("#parent2").prop("checked", true);
          }
        } else {
          var id_servicio = 8;
          //$('#parent').val("1");
          //$('#nombre_parent').html("GENERAL");
          $("#parent1").prop("checked", true);
        }

        id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;

        obtenerEstablecimiento(parentesco, ubigeo, id_establecimiento, id_servicio);

        if(result.cantidad_cita!=null && result.cantidad_cita>0)modalProximaCita(tipodoc_beneficiario,dni_beneficiario);

        modalHistorial();
        $("#divTurno").html("");

        $('#calendar').fullCalendar('destroy');
        fullCalendar();

      }

    });

  }

  function anularCita(id_cita) {

    bootbox.confirm({
      size: "small",
      message: "¿Deseas anular la cita?",
      callback: function(result) {
        if (result == true) {
          fn_anularCita(id_cita);
        }
      }
    });

  }

  function fn_anularCita(id_cita) {

    $.ajax({
      url: '/anular_cita/' + id_cita,
      dataType: "json",
      success: function(result) {

        if (result.sw == true) {
          obtenerBeneficiario();
          $('.nav-tabs a[href="#tab1default"]').tab('show');
        }

      }

    });

  }


  function obtenerEstablecimiento(parentesco, ubigeo, id_establecimiento, id_servicio) {
    var newOption = "";
    newOption = "<option value=''>--Seleccionar--</option>";
    $('#id_establecimiento').html(newOption);
    if (ubigeo == "") ubigeo = 0;
    $.ajax({
      url: '/obtener_establecimiento_cita/' + parentesco + "/" + ubigeo,
      success: function(result) {
        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";
        $(result).each(function(ii, oo) {
          var sel = "";
          if (id_establecimiento == oo.id) sel = "selected='selected'";
          newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre + "</option>";
        });
        $('#id_establecimiento').html(newOption);
        $("#id_establecimiento").select2({
          max_selected_options: 4
        });
        //Se comento solo para cargar los datos del referido
        //getServicio(id_servicio);
      }
    });
  }

  function limpiarBeneficiario() {

    var dni_beneficiario = $('#dni_beneficiario').val();
    var nrodocafiliado = $('#nrodocafiliado').val();
    if (dni_beneficiario != nrodocafiliado) {
      $('#beneficiario').val("");
      $('#tipo_beneficiario').val("");
      $('#nombre_beneficiario').val("");
      $('#paterno_beneficiario').val("");
      $('#materno_beneficiario').val("");
      $('#grado').val("");
      $('#nro_historia').val("");
      $('#tipodoc_beneficiario').val("");
      $('#cip_beneficiario').val("");
      $('#id_admision').val("");
      $('#grado').val("");
    }

  }

  function getServicio(id_servicio) {
    var newOption = "";
    newOption = "<option value=''>--Seleccionar--</option>";
    $('#id_servicio').html(newOption);
    var parent = 2; //$('input[name="parent"]:checked').val();
    var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_beneficiario = $("#dni_beneficiario").val();
    var id_establecimiento = $('#id_establecimiento').val();

    if (parent == 1) url = '/obtener_consultorio_cita_general/' + id_establecimiento + '/' + tipodoc_beneficiario + '/' +
      dni_beneficiario;
    if (parent == 2) url = '/obtener_consultorio_cita/' + id_establecimiento + '/' + tipodoc_beneficiario + '/' +
      dni_beneficiario;

    $.ajax({
      url: url,
      success: function(result) {
        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";
        $(result).each(function(ii, oo) {
          var sel = "";
          if (id_servicio == oo.id) sel = "selected='selected'";
          newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre + "</option>";
        });
        $('#id_servicio').html(newOption);
        $("#id_servicio").select2({
          max_selected_options: 4
        });
        fullCalendar();
      }
    });
  }

  function getcargaservicio() {
    var newOption = "";
    newOption = "<option value=''>--Seleccionar--</option>";
    $('#id_servicio').html(newOption);
    var parent = $('input[name="parent"]:checked').val();
    var id_establecimiento = $('#id_establecimiento').val();

    if (parent == 1) url = '/obtener_consultorio_cita_general/' + id_establecimiento + '/' + tipodoc_beneficiario + '/' +
      dni_beneficiario;
    if (parent == 2) url = '/obtener_consultorio_cita/' + id_establecimiento + '/' + tipodoc_beneficiario + '/' +
      dni_beneficiario;

    $.ajax({
      url: url,
      success: function(result) {
        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";
        $(result).each(function(ii, oo) {
          var sel = "";
          if (id_servicio == oo.id) sel = "selected='selected'";
          newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre + "</option>";
        });
        $('#id_servicio').html(newOption);
        $("#id_servicio").select2({
          max_selected_options: 4
        });
        fullCalendar();

      }
    });

  }

  async function fullCalendar() {
    var fecha = new Date(2022, 4, 10);
    var feriados = await getFeriados(moment().format("YYYY"));

    //var SITEURL = "{{url('/')}}";
    var iIdMovimientoCompra = $('#hdIdMovimientoCompra').val();
    var iIdTipoCompra = $('#hdIdTipoCompra').val();

    var id_servicio = $('#id_servicio').val();
    var id_ipress = $('#id_establecimiento').val();
    $("#divHorario").html("");
    $('#calendar').fullCalendar({
      header: {
        left: 'today',
        center: 'title',
        right: 'prev,next'
      },
      editable: false,
      annotations: [{
        start: new Date(2000, 1, 1),
        end: new Date(2022, 4, 13),
        title: 'Blocked Day',
        cls: 'open',
        color: '#777777',
        background: '#000'
      }],
      selectable: true,
      selectHelper: true,
      select: function(start, end, allDay) {
        if(start.format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')) {
          
          bootbox.alert("No se puede registrar citas con fechas pasadas");
          return;
        } else {
          dateObj = new Date(start);
          dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
          dateObjNTZ = new Date(dateIntNTZ);
          start = dateObjNTZ.toISOString().slice(0, 10);
          start = $.trim(start);
          var sValidaciones = "";
          modalDelegar(start);
        }
      },
      dayRender: function(date, cell) {
        var enabled = true;
        if(date < moment()) {
          cell.addClass("app-fullcalendar-day-disabled");
          enabled = false
        }
        feriados.forEach(element => {
          if (date.isSame(element.feriado)) {
            cell.addClass("app-fullcalendar-feriado");
            enabled = false
          }
        });
        if (enabled)
          cell.addClass("app-fullcalendar-day-enabled");
      }
    });
  }

  function fn_RegistrarNuevoCronograma(start, iIdMovimientoCompra, iIdTipoCompra) {
    $('#showAtencionesMensual').modal();
  }

  async function modalDelegar(start) {
    console.log(start);
    var tieneMensaje = false;
    let validacion = JSON.parse(await validateProgramacionCitas(start));


    var id_servicio = $('#id_servicio').val();
    var id_ipress = $('#id_establecimiento').val();
    var dni_beneficiario = $('#dni_beneficiario').val(); 

    if (id_servicio == '') {
      bootbox.alert('Seleccione el servicio');
    }

    if (validacion['validacion'] == '1') {
      $('#es_asegurado').val(1);
    } else if (validacion['validacion'] == '90') {
      $('#es_asegurado').val(0);
    } else {
      tieneMensaje = true;
      bootbox.alert(validacion['validacion']);
    }

    if (!tieneMensaje) {
      $.ajax({
        url: "../modal_cita_horario_referencia/" + id_ipress + "/" + id_servicio + "/" + start + "/" + dni_beneficiario,
        type: "GET",
        success: function(result) { 
          $("#divHorario").html(result);
        }
      });
    }
  }


  function modalTurnos() {
    var id_servicio = $('#id_servicio').val();
    var id_ipress = $('#id_establecimiento').val();
    var start = "<?php echo $fechaServidor ?>";
    $("#divTurno").html("");
    $.ajax({
      url: "modal_turnos/" + id_ipress + "/" + id_servicio + "/" + start,
      type: "GET",
      success: function(result) {
        $("#divTurno").html(result);
      }
    });

  }

  function modalHistorial() {
    var dni_beneficiario = $("#dni_beneficiario").val();
    $.ajax({
      url: "../modal_historial_cita/" + dni_beneficiario,
      type: "GET",
      success: function(result) {
        $("#divHistorial").html(result);
      }
    });

  }

  async function getFeriados(anio) {
    let feriados;
    try {
      feriados = await $.ajax({
        url: "../listar_feriados/" + anio,
        type: "GET"
      });
      return JSON.parse(feriados);
    } catch (error) {
      console.log(error);
    }
  }


  async function validateProgramacionCitas(fechaCita) {
    try {
      let validacion = await $.ajax({
        url: "../valida_programacion_cita",
        data: {
          _token: $("#_token").val(),
          nro_documento: $("#nrodocafiliado").val(),
          id_establecimiento: $("#id_establecimiento").val(),
          id_sub_consultorio: $("#id_servicio").val(),
          fecha: fechaCita,
        },
        type: "POST"
      });
      return validacion;
    } catch (error) {
    }
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
          <h3 class="panel-title">MÓDULO DE GESTIÓN DE CITAS - REFERENCIA</h3>
        </div>
        <div class="panel-body">
          <form class="app-form" method="post" action="{{ route('registrar_receta_rua')}}" id="frmCita" autocomplete="off">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="es_asegurado" name="es_afiliado" value="">
            <input type="hidden" name="id_nivel" id="id_nivel" value="<?php //echo $id_nivel 
                                                                      ?>">
            <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
            <div class="app-panel-title app-mt-4 app-mb-3"><strong>INFORMACIÓN DEL PACIENTE</strong></div>
            <div class="row">
              <div class="col-md-3 col-sm-4">
                <div class="form-group">
                  <label for="tipo">TIPO DOC.</label>
                  <select class="form-control input-sm" id="tipodoc_beneficiario_bus" name="tipodoc_beneficiario_bus" onchange="maxlength_doc_usu()" tabindex="3" disabled>
                    <option @if ($tipo_doc == 1) selected @endif value="1">DNI</option>
                    <option @if ($tipo_doc == 2) selected @endif value="2">CARNET DE EXTRANJERIA</option>
                    <option @if ($tipo_doc == 3) selected @endif value="3">PASAPORTE</option>
                    <option @if ($tipo_doc == 4) selected @endif value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                  </select>
                  <input type="hidden" value="" name="tipodoc_beneficiario" id="tipodoc_beneficiario" class="form-control">
                  <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular" class="form-control">
                </div>
              </div>
              <div class="col-md-3 col-sm-4">
                <label for="dni_beneficiario">NRO DOC.</label>
                <div class="input-group" style="display: flex;">
                  <input {{--onKeyPress="return soloNumeros(event)"--}}id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm text-uppercase" style="width: 100% !important; text-align: center; font-weight: bold; font-size: 1.5rem;" maxlength="12" autofocus="" {{--onblur="limpiarBeneficiario()"--}} tabindex="4" value="{{ $dni_referido }}" readonly>
                  {{--<span class="input-group-btn" style="height:28px!important;">
                    <button type="button" id="btnBuscar" onclick="obtenerBeneficiario()" class="btn btn-info" style="height:28px!important;line-height:10px">Buscar</button>
                  </span>--}}
                </div>
                <input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly="" />
                <input type="hidden" class="form-control" value="" name="nrodocafiliado" id="nrodocafiliado" readonly="" />
                <input type="hidden" class="form-control" value="" name="nrodoctitular" id="nrodoctitular" readonly="" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>APELLIDOS Y NOMBRES</label>
                  <input readonly="" type="text" class="form-control" value="" name="beneficiario" id="beneficiario" style="text-align:left">
                  <input type="hidden" class="form-control" value="" name="nombre_beneficiario" id="nombre_beneficiario" />
                  <input type="hidden" class="form-control" value="" name="paterno_beneficiario" id="paterno_beneficiario" />
                  <input type="hidden" class="form-control" value="" name="materno_beneficiario" id="materno_beneficiario" />
                  <input type="hidden" class="form-control" value="" name="nombre_titular" id="nombre_titular" />
                  <input type="hidden" class="form-control" value="" name="paterno_titular" id="paterno_titular" />
                  <input type="hidden" class="form-control" value="" name="materno_titular" id="materno_titular" />
                  <input type="hidden" class="form-control" value="" name="fecnacafiliado" id="fecnacafiliado" />
                  <input type="hidden" class="form-control" value="" name="nomsexo" id="nomsexo" />
                  <input type="hidden" class="form-control" value="{{ $id_referencia }}" name="id_referencia" id="id_referencia" />
                  {{-- <input type="hidden" class="form-control" value="" name="nro_historia" id="nro_historia"> --}}
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>PARENTESCO</label>
                  <input readonly value="" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>GRADO</label>
                  <input readonly value="" type="text" name="grado" id="grado" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label>HISTORIA CLINICA</label>
                  <input value="" type="text" name="nro_historia" id="nro_historia" class="form-control">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>TELÉFONO</label>
                  <input value="" type="text" name="telefono" id="telefono" class="form-control" maxlength="9">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>TELÉFONO 2</label>
                  <input value="" type="text" name="telefono2" id="telefono2" class="form-control" maxlength="9">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>DOMICILIO</label>
                  <input value="" type="text" name="domicilio" id="domicilio" class="form-control">

                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>CORREO</label>
                  <input value="" type="text" name="correo" id="correo" class="form-control">
                </div>
              </div>
            </div>
            <div class="app-panel-title app-mt-4 app-mb-3"><strong>INFORMACIÓN DE LA PRESCRIPCION Y SERVICIO</strong></div>
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="id_establecimiento">ABSCRIPCIÓN</label>
                      <select class="form-control" onchange="getcargaservicio()" name="id_establecimiento" id="id_establecimiento" tabindex="8" style="width: 100%" readonly="" selected>
                        <option value="">--Seleccionar--</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>
                        SERVICIO
                        {{--
                         &nbsp;
                        <label class="radio-inline">
                          <input class="app-radio-fix01" type="radio" name="parent" id="parent1" value="1"> GENERAL
                        </label>
                        <label class="radio-inline">
                          <input class="app-radio-fix01" type="radio" name="parent" id="parent2" value="2"> ESPECIALIDAD
                        </label>
                      --}}
                      </label>
                      <input type="hidden" name="parent" id="parent2" value="2">
                      <select class="form-control" name="id_servicio" id="id_servicio" tabindex="8" selected>
                        <option value="{{  $servicio_destino[0] }}" selected>{{ $servicio_destino[1] }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="tipo_cita">TIPO CITA</label>
                      <select class="form-control" name="tipo_cita" id="id_tipo_cita" tabindex="8">
                        <option value="">--Seleccionar--</option>
                        <option value="1" selected>Presencial</option>
                        <option value="2">Teleconsulta</option>
                      </select>
                    </div>
                  </div>
                  <!--referencia-->
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="tipo_cita">REFERIDO</label>
                      <select class="form-control" name="referido" id="referido" tabindex="10" onChange="mostrar();">
                        <option selected value="{{ $esReferido }}">{{ $esReferido }}</option>
                      </select>
                    </div>
                  </div>
                  <div id="establereferencia" style="display: none;">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="tipo_cita">ESTABLECIMIENTO DE LA REFERENCIA</label>
                        <select class="form-control" name="establecimiento_referencia" id="establecimiento_referencia" tabindex="10"  onchange="getarea_servicio();">
                          <option value="{{ $ipress_origen[0] }}" selected>{{ $ipress_origen[1] }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="tipo_cita">SERVICIO DE LA REFERENCIA</label>
                        <select class="form-control" name="servicio_referencia" id="servicio_referencia" tabindex="10">
                        <option value="{{ $servicio_origen[0] }}" selected="">{{ $servicio_origen[1] }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="tipo_cita"><b>DOCUMENTO DE LA REFERENCIA</b></label>
                      </div>
                    </div>
                      <div class="col-md-11">
                      <div class="col-md-6">
                        <label for="tipo_cita">TIPO DE DOCUMENTO</label>
                          <select class="form-control" name="tipo_doc" id="tipo_doc" tabindex="10">
                            <option value="0">--Seleccionar--</option>
                            <option value="1">OFICIO</option>
                            <option value="2">ACTA</option>
                          </select>
                      </div>
                      <div class="col-md-6">
                        <label for="tipo_cita"># DE DOCUMENTO</label>
                        <input value="" type="text" name="numero_doc" id="numero_doc" class="form-control" >
                      </div>
                      <div class="col-md-6">
                        <label for="tipo_cita">AÑO</label>
                        <input value="<?php echo $anio ?>" type="text" name="ano" id="ano" class="form-control" readonly="">
                      </div>
                      <div class="col-md-6">
                        <label for="origen_doc">DEPENDENCIA</label>
                          <select class="form-control" name="origen_doc" id="origen_doc" tabindex="10">
                            <option value="0">--Seleccionar--</option>
                            <option value="1" >/DIRSAPOL -</option>
                            <option value="2">/DIRSAPOL/SUBDIR -</option>
                            <option value="3">/DIRSAPOL/SECRETARIA - </option>
                          </select>
                      </div>
                      <div class="col-md-12">
                        <label for="tipo_cita">ESTABLECIMIENTO</label>
                          <select class="form-control" name="codigo_renipres" id="codigo_renipres" tabindex="10">
                          <option value="">--Seleccionar--</option>
                          <?php foreach($estableciemientoreferencia as $row):  ?>
                            <option value="<?php echo $row->codigo?>" ><?php echo $row->concatenado?></option>
                          <?php endforeach;?>
                          </select>
                      </div>
                    </div>
                  </div>
                  <div id="consultorio_x_servicio">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="tipo_cita">CONSULTORIO DE PROCEDENCIA</label>
                        <select class="form-control" name="consultorio_referencia" id="consultorio_referencia" tabindex="10"  onchange="getarea_servicio();">
                          <option value="">--Seleccionar--</option>
                          <?php foreach($servicio as $row):  ?>
                            <option value="<?php echo $row->nombre?>" ><?php echo $row->nombre?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>

                      
                  </div>
                  <div class="col-md-12">
                        <label for="tipo_cita">OBSERVACION</label>
                        <input value="" type="text" name="obs_registro_cita" id="obs__registro_cita" class="form-control" >
                      </div>  <?php if ($servicio_destino[0]  == 113 || $servicio_destino[0]  == 114): ?>
                       <div class="col-md-12" style="padding-top: 2rem;">
                   <div class="panel panel-info" id="listOrdenesLaboratorioCitaOrigen" >
                        <div class="panel-heading">
                           <?php if ($servicio_destino[0]  == 113): ?>
                                <h3 class="panel-title size-titulo">
                                    Citas con Orden de Laboratorio Inicial
                                </h3>

                            <?php elseif ($servicio_destino[0]  == 114): ?>
                                <h3 class="panel-title size-titulo">
                                    Citas con Orden de Diagnostico por Imagenes Inicial
                                </h3>
                            <?php endif; ?>

                        </div>
                          <?php if ($servicio_destino[0]  == 113 || $servicio_destino[0]  == 114): ?>
                        <div class="panel-body" style="overflow-y: scroll;">
                          <table id="tblOrdenesLaboratorioCitaOrigen" class="table" cellspacing="0" width="100%" style="">
                              <thead>
                                  <tr>
                                      <th style="text-align: left;">Nro. Orden</th>
                                      <th style="text-align: left;">Fecha. Orden</th>
                                      <th style="text-align: left;">Tipo Atención</th>
                                      <th style="text-align: left;">servicio</th>
                                      <th style="text-align: left;">Consultorio</th>
                                      <th style="text-align: left;">Médico</th>
                                      <th style="text-align: left;">CPMS</th>
                                      <th style="text-align: left;">Visualizar Orden</th>
                                      <th style="text-align: left;">&nbsp;</th>
                                  </tr>
                              </thead>
                              <tbody>
<?php foreach ($res as $orden): ?>
<tr>
    <td><?= $orden->id ?></td>
    <td><?= $orden->created_at ?></td>
    <td><?= $orden->nombre_tipo_atencion ?></td>
    <td><?= $orden->especialidad_prestacional ?></td>
    <td><?= $orden->consultorio ?></td>
    <td><?= $orden->medico_prestacional ?></td>

    <td class="tbl-centro">
    <span style="
        background-color:#d9534f;
        color:#ffffff;
        padding:4px 8px;
        border-radius:4px;
        font-size:12px;
        font-weight:bold;
        box-shadow:0 1px 3px rgba(0,0,0,0.3);
        display:inline-block;
    ">
        PENDIENTE
    </span>
</td>

    <td class="tbl-centro">
        <?php if (!empty($orden->cpms)): ?>
            <input
                type="checkbox"
                name="cpms_seleccionados[<?= $orden->id ?>][]"
                value="<?= $orden->cpms[0]->codigo_lab ?>"
                title="<?= $orden->cpms[0]->descripcion_corta ?>"
            >
        <?php else: ?>
            -
        <?php endif; ?>
    </td>

       <?php if ($servicio_destino[0]  == 113): ?>
          <td class="tbl-centro">              
              <a href="<?= url('laboratorio/rua/pdf/'.$orden->id) ?>"
                target="_blank"
                style="
                    background-color:#2e7d32;
                    color:#ffffff;
                    padding:6px 10px;
                    border-radius:4px;
                    text-decoration:none;
                    display:inline-block;
                    box-shadow:0 1px 3px rgba(0,0,0,0.3);
                ">
                  <i class="fa fa-search" style="color:#ffffff;"></i>
              </a>
          </td>
        <?php endif; ?>
               <?php if ($servicio_destino[0]  == 114): ?>
          <td class="tbl-centro">              
              <a href="<?= url('ver_radiologia_imprimir/'.$orden->id) ?>"
                target="_blank"
                style="
                    background-color:#2e7d32;
                    color:#ffffff;
                    padding:6px 10px;
                    border-radius:4px;
                    text-decoration:none;
                    display:inline-block;
                    box-shadow:0 1px 3px rgba(0,0,0,0.3);
                ">
                  <i class="fa fa-search" style="color:#ffffff;"></i>
              </a>
          </td>
        <?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody>
                          </table>
                        </div>  <?php endif; ?>
                    </div>  
                    </div>   <?php endif; ?> 
                </div>
              </div>
              <div class="col-md-6">
                <div id='calendar'></div>
              </div>
            </div>
            <ul class="nav nav-tabs app-mt-4" role="tablist">
              <li role="presentation" class="active"><a href="#tabcita" aria-controls="tabcita" role="tab" data-toggle="tab">CITAS DE EMERGENCIA Y REFERENCIA</a></li>
              <li role="presentation"><a href="#tabhistorial" aria-controls="tabhistorial" role="tab" data-toggle="tab">HISTORIAL DE CITA</a></li>
            </ul>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="tabcita">
                {{-- <div class="app-panel-title app-mt-4 app-mb-3"><strong>PROGRAMACIONES MEDICAS</strong></div>--}}
                <div id='divHorario'></div>
              </div>
              <div role="tabpanel" class="tab-pane" id="tabhistorial">
                {{-- <div class="app-panel-title"><strong>INFORMACIÓN DE LAS CITAS DEL ASEGURADO</strong></div> --}}
                <div class="form-group">
                  <div id='divHistorial'></div>
                </div>
              </div>
            </div>
            <hr style="clear: both;">
            <input class="btn btn-primary pull-right" value="CREAR" name="crea" type="button" form="prestacionescrea" id="btnCita" />
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
