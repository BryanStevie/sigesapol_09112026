@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<style>
  .modal-dialog {
    width: 90%;
  }
  
  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }

  .ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 7px;
    margin: 2px 0 0;
    list-style: none;
    font-size: 15px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }

  .ui-autocomplete>li>div {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333333;
    white-space: nowrap;
  }

  .ui-state-hover,
  .ui-state-active,
  .ui-state-focus {
    text-decoration: none;
    color: #FFFFFF !important;
    background: #1d71b8 !important;
    cursor: pointer;
  }

  .ui-helper-hidden-accessible {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  input[readonly] {
    background-color: #eeeeee;
    text-align: center
  }

  span:focus,
  textarea:focus,
  input[type="text"]:focus,
  input[type="password"]:focus,
  input[type="datetime"]:focus,
  input[type="datetime-local"]:focus,
  input[type="date"]:focus,
  input[type="month"]:focus,
  input[type="time"]:focus,
  input[type="week"]:focus,
  input[type="number"]:focus,
  input[type="email"]:focus,
  input[type="url"]:focus,
  input[type="search"]:focus,
  input[type="tel"]:focus,
  input[type="color"]:focus,
  .uneditable-input:focus {
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }

  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    margin: 10px;
    text-align: center;
    margin-top: 40px;
    /*background:#5cb85c*/
    background: transparent;
    color: #009966
  }

  .fc-day-grid-event :hover {
    color: #009966
  }

  .fc-content {
    padding: 5px !important;
  }

  .fc-title {
    text-align: center;
    font-size: 12px
  }

  /*
  .select2-container:focus {
    border-color: rgba(126, 239, 104, 0.8);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
    outline: 0 none;
  }
  */
  /*
  .select2-container-active .select2-choice {
    border: 1px solid #999;
    box-shadow: inset 0 2px 3px 0 rgba(0,0,0,.06);
    border-radius: 4px;
  } 
  
  .select2-container-active.select2-dropdown-open .select2-choice {
    border-bottom: 2px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    border-top: 1px solid #ccc;
    box-shadow: none;
  }
  */

  .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal {
    margin: 15rem auto auto auto !important;
  }

  .swal2-popup {
    width: 50em !important;
  }

  .swal2-popup,
  .swal2-header {
    padding: 0 !important;
  }

  .swal2-header {
    background: #3c763d;
  }

  .swal2-html-container {
    margin: 0 !important;
    padding: 1rem 0 !important;
  }

  .titulo-padding {
    padding: 1rem 0 0 0;
  }

  .titulo-padding p {
    color: white;
    margin: 0;
    font-size: 2.5rem;
  }

  .descripcion-alert {
    font-size: 1.5rem;
    padding: 0 1rem;
  }

  .descripcion-alert {
    margin: 0;
  }

  .descripcion-alert span {
    font-weight: 700;
  }

  .swal2-actions {
    margin: 0 !important;
    padding: 1rem;
    justify-content: space-evenly;
  }

  .swal2-actions button {
    margin: 0 !important;
  }

  .borde-alerta {
    border-color: red !important;
    border: 2px red solid !important;
    outline: 0;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
  }

  .swal2-header-ref {
    background: #ec942c;
  }
  .swal2-content {
    padding: 0 1.6em !important;
  }
  .swal2-title {
    color: white !important;
  }

</style>

<script>
  const ID_SERVICIO_CENTRO_QUIRURGICO = 26;
  //<![CDATA[
  var documentName_ = null;
  //
  var procedimientosSeleccionados =[];
  var listIdDiagnostico =[];
  var listIdDiagnosticoObjetos =[];
  var filasmedicamentos = 0;

  window.addEventListener('getArguments', async function(e) {
    await ObtieneArgumentosParaFirmaDesdeLaWeb();
  });

  async function getArguments() {
    arg = document.getElementById("argumentos").value;
    await dispatchEventClient('sendArguments', arg);
  }

  window.addEventListener('invokerOk', function(e) {
    MiFuncionOkWeb();
  });

  window.addEventListener('invokerCancel', function(e) {
    MiFuncionCancel();
  });

  async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');

    $.post("{{route('postArguments')}}", {
      _token: CSRF_TOKEN,
      type: "W",
      documentName: $('#doc_firmar').val(),
      entidad: <?= Auth::user()->dni ?>
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
    });
  }

  async function MiFuncionOkWeb() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    var id_resol = $('#id_prestacion').val();
    $.post("{{route('actualiza_prestacion')}}", {
      _token: CSRF_TOKEN,
      id_tipo: "prestacion",
      id_resol: id_resol,
    }, function(data, status) {
      $.post("{{route('actualiza_prestacion')}}", {
        _token: CSRF_TOKEN,
        id_tipo: "receta",
        id_resol: id_resol,
      }, function(data, status) {
        $.post("{{route('actualiza_prestacion')}}", {
          _token: CSRF_TOKEN,
          id_tipo: "laboratorio",
          id_resol: id_resol,
        }, function(data, status) {
          $.post("{{route('actualiza_prestacion')}}", {
            _token: CSRF_TOKEN,
            id_tipo: "radiologia",
            id_resol: id_resol,
          }, function(data, status) {
            location.reload();
          });
        });
      });
    });
  }

  function MiFuncionCancel() {
    alert("El proceso de firma digital fue cancelado.");
  }

  $(document).ready(function() {

    if ($("#flag_firmar").val() == 0) {
      $("#btnfirmar").hide();
    } else {
      $("#btnfirmar").show();
    }
    getProducto();

    $(document).on('click', '#btnfirmar', function(e) {
      e.preventDefault();
      firmar();
    })

    $('#fecha_atencion').datepicker();

    $('#btnPrestacion').on("click", function(e) {
      guardarAltaMedica();
    });

    $('#addDiagnostico').on('click', function() {
      AddDiagnostico();
    });

    $('#addDiagnosticoQuirurgico').on('click', function() {
      AddDiagnosticoQuirurgico();
    });

    $('#addDerivarServicio').on('click', function() {
      var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
      $("#divServicio").hide();
      if (addDerivarServicio == "false") {
        $("#divServicio").show();
      }
    });

    $('#addRecetaVale').on('click', function() {
      var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
      $("#tiene_receta").val("");
      $("#fsRecetaVale").hide();
      if (addRecetaVale == "false") {
      getProducto();
      $("#tiene_receta").val("true");
        $("#fsRecetaVale").show();
      }else{
        $('#presiono_receta').val("");
      }
    });
    if ($("#tiene_receta").val() == "true") $('#addRecetaVale').click(); 

    $('#addExamenLab').on('click', function() {
      var addExamenLab = $('#addExamenLab').attr("aria-pressed");
      $("#fsExamenLab").hide();
      if (addExamenLab == "false") {
        $("#fsExamenLab").show();
      }
    });

    $('#addExamenRad').on('click', function() {
      var addExamenRad = $('#addExamenRad').attr("aria-pressed");
      $("#fsExamenRad").hide();
      if (addExamenRad == "false") {
        $("#fsExamenRad").show();
      }
    });

    $('#addAlta').on('click', function() {
      var addAlta = $('#addAlta').attr("aria-pressed");
      $("#divAlta2").hide();
      if (addAlta == "false") {
        $("#divAlta2").show();
      }
    });

    $('#example tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#tblDiagnosticoQuirurgico tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#tblExamenLab tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#tblExamenRad tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $("#id_establecimiento_traslado").select2({
      width: 'resolve'
    });

    $("#id_servicio_hospitalizacion").select2({
      width: 'resolve'
    });

    $("#condicion_traslado").select2({
      width: 'resolve'
    });

    $("#especialida_destino").select2({
      width: 'resolve'
    });

    $("#id_tipo_egreso").select2({
      width: 'resolve'
    });

    $("#id_servicio_encargado").select2({
      width: 'resolve'
    });

    $("#id_pabellon_destino").select2({
      width: 'resolve'
    });

    $("#id_servicio_destino").select2({
      width: 'resolve'
    });

    $("#id_cama").select2({
      width: 'resolve'
    });

    $("#id_farmacia_receta").select2({
      width: 'resolve'
    });

    $('#id_tipo_egreso').on('change', function() {
      var id_tipo_egreso = $(this).val();
      var id_referencia = $('#id_referencia').val();
      if (id_tipo_egreso != 3 && id_referencia != 0) {
        Swal.fire({
          title: '<div class="titulo-padding"><p>Observación</p></div>',
          html: '<p class="descripcion-alert"><span><b>El paciente fue atendido en emergencia por referencia de preferencia dar alta como transferido.</b><br></span></p>',
          allowOutsideClick: false,
          confirmButtonColor: '#ec942c',
          customClass: {
            header: 'swal2-header-ref',
          },
        });
      }
    });

  });

  function getProducto(){
    $('#example tbody').html('');
    var id_ipress 	= $('#id_establecimiento').val();
    var id_consultorio 	= $('#id_servicio').val();
    var id_tiporeceta 	= $('#id_tipo_atencion').val();
    var id_farmacia     = $('#id_farmacia_receta').val();
    var nivel     = $('#id_nivel').val();

    if(id_tiporeceta == 2 && id_farmacia == 21){ //nutricional
      if(id_tiporeceta==5 || id_tiporeceta == 2){
         id_consultorio = 110;
      }else{
       id_consultorio = 111;
      }
      $.ajax({
         url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia_nutricial/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
         success: function(result){                    
           var newOption = "<option value=''>--Seleccionar-</option>";
           $(result).each(function (ii, oo) {
             newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
        });                 
           $('#cmbMedicamentoTemp').html(newOption);
        }
      });
    }
    else if(id_farmacia == 132){   
      $.ajax({
        url: '/obtener_producto_farmacotecnia/' + id_ipress,
        success: function(result){                    
          var newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          });                 
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });
    }
    else{
      if(id_tiporeceta==2 && (id_farmacia == 19 || id_farmacia == 107 || id_farmacia ==22 || id_farmacia ==14)){
        id_consultorio=76;
      }
      $.ajax({
          url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia+'/'+ id_consultorio,
          success: function(result){                    
          var newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
            });                 
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });
    }
  }

  $(document).ready(function() {
    $('#fecha_egreso').change(function() {
      var fechaEgreso = $(this).val();
      var fechaCalculo = $('input[name="fecha_calculo"]').val();

      console.log('Fecha de Egreso:', fechaEgreso);
      console.log('Fecha de Cálculo:', fechaCalculo);

      var partesFechaEgreso = fechaEgreso.split('-');
      var dateEgreso = new Date(partesFechaEgreso[2], partesFechaEgreso[1] - 1, partesFechaEgreso[0]);

      var partesFechaCalculo = fechaCalculo.split(' ')[0].split('-');
      var dateCalculo = new Date(partesFechaCalculo[0], partesFechaCalculo[1] - 1, partesFechaCalculo[2]);
      var diferenciaMilisegundos = dateCalculo - dateEgreso;
      var diferenciaMilisegundosAbs = Math.abs(diferenciaMilisegundos);
      var diferenciaDias = Math.floor(diferenciaMilisegundosAbs / (1000 * 60 * 60 * 24)) + 1;
      console.log('Diferencia en días sumando uno:', diferenciaDias);
      document.getElementById('calculo_dias').value = diferenciaDias;

      const id_tipo_egreso = $("#id_tipo_egreso").val();
      handlerSiteds(fechaEgreso, id_tipo_egreso);
    });
  });

  async function guardarAltaMedica() {
    var msg = "";
     if($('#tiene_receta').val() === 'true') {
        var fecha_expedicion = $('#fecha_expedicion').val();
        var fecha_calculo = $('input[name="fecha_calculo"]').val();
        var fecha_egreso = $('#fecha_egreso').val();

        if(fecha_expedicion && fecha_calculo){
          var momExpedicion = moment(fecha_expedicion, 'DD-MM-YYYY');
          var momIngreso = moment(fecha_calculo.split(' ')[0], 'YYYY-MM-DD');

          if(momExpedicion.isBefore(momIngreso, 'day')){
              bootbox.alert("La fecha de expedición no puede ser menor a la fecha de ingreso");
              return false;
          }

          if(fecha_egreso){
            var momEgreso = moment(fecha_egreso, 'DD-MM-YYYY');
            if (momExpedicion.isAfter(momEgreso, 'day')) {
                bootbox.alert("La fecha de expedición no puede ser mayor a la fecha de egreso");
                return false;
            }
          }
        }
      }
    
    if ($('#tiene_receta').val() === 'true') {
      
      let totalRowProducto = $('#example tbody tr').length;

      if (totalRowProducto > 0) {
        let producto_tr = $('#example tbody').children('tr');
        let erroresProducto = new Set();

        for (let i = 0; i < totalRowProducto; i++) {
          let fila = producto_tr[i];

          let id_producto     = $(fila).find('select[name="id_producto[]"]').val();
          let tiempo_producto = $(fila).find('input[name="tiempo_periodo[]"]').val();
          let via_producto    = $(fila).find('select[name="id_via_administracion[]"]').val();
          let posol_producto  = $(fila).find('input[name="posologia[]"]').val();
          let cant_producto   = $(fila).find('input[name="nro_med_solictados[]"]').val();
          let diag_producto   = $(fila).find('select[name="miSelectProd[]"]').val();

          if (tiempo_producto == "" || tiempo_producto == null || isNaN(tiempo_producto) || parseInt(tiempo_producto) <= 0) {
            erroresProducto.add("Uno de los productos no cuenta con el tiempo (días), tiene que ser mayor a 0.");
          }
          if (via_producto == "" || via_producto == null || via_producto == "0") {
            erroresProducto.add("Uno de los productos no cuenta con la vía de administración.");
          }
          if (posol_producto == "" || posol_producto == null || posol_producto.trim() === "") {
            erroresProducto.add("Uno de los productos no cuenta con la posología.");
          }
          if (cant_producto == "" || cant_producto == null || cant_producto === "NaN" || isNaN(cant_producto) || parseInt(cant_producto) <= 0) {
            erroresProducto.add("Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0.");
          }
        }

        if (erroresProducto.size > 0) {
          msg += Array.from(erroresProducto).join("<br>") + "<br>";
        }
      } else {
        msg += "Debe ingresar minimo un producto medico a la Receta Vale<br>";
      }
    }

    var ind_diag = $('#tblDiagnostico tbody tr').length;
    if (ind_diag > 0) {
      fila_dignosticos = $('#tblDiagnostico tbody').children('tr');
      for (i = 0; i < ind_diag; i++) {
        fila_dignostico = fila_dignosticos[i];
        var id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
        var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
        if (id_diagnostico == "") msg += "Debe ingresar el nombre de diagnostico<br>";
        if (id_tipo_diagnostico == "0") msg += "Debe ingresar el tipo de diagnostico<br>";
      }
    } else {
      msg += "Debe ingresar minimo un diagnostico<br>";
    }

    var fecha_egreso = $('#fecha_egreso').val();
    if (fecha_egreso == "") {
      msg += "Debe ingresar la Fecha de egreso medico<br>";
    }

    var hora_egreso = $('#hora_egreso').val();
    if (hora_egreso == "") {
      msg += "Debe ingresar la Hora de egreso medico<br>";
    }

    var id_tipo_egreso = $('#id_tipo_egreso').val();

    if (id_tipo_egreso == "") {
      if (id_tipo_egreso == "") {
        msg += "Debe ingresar un tipo de egreso<br>";
      }
    } else {
      if (id_tipo_egreso == 2) {
        // let id_establecimiento = $('#id_establecimiento').val();
        var id_servicio_hospitalizacion = $('#id_servicio_hospitalizacion').val();
        @if (intval($id_establecimiento) === 76)
        let id_edificio_ubicacion = $('#id_edificio_ubicacion').val();
        let id_pabellon_ubicacion = $('#id_pabellon_ubicacion').val();
        @endif
        // let id_cama_ubicacion = $('#id_cama_ubicacion').val();
        // let id_clase_cama_ubicacion = $('#id_clase_cama_ubicacion').val();

        if (id_servicio_hospitalizacion == "") {
          msg += "Debe elegir un servicio de Hospitalizacion<br>";
        }

        @if (intval($id_establecimiento) === 76)
        if (id_edificio_ubicacion == "") {
          msg += "Debe seleccionar si la ubicacion es PIP o Histórico <b>(Ubicación del Paciente)</b><br>";
        }

        if (id_pabellon_ubicacion == "") {
          msg += "Debe seleccionar el Pabellón <b>(Ubicación del Paciente)</b><br>";
        }
        @endif
        
        // if (id_cama_ubicacion == "") {
        //   msg += "Debe seleccionar la Cama <b>(Ubicación del Paciente)</b><br>";
        // }

        // switch (id_clase_cama_ubicacion) {
        //   case 1:
        //   case 6:
        //   case 7:
        //     let personal_coordinacion = $('#personal_coordinacion').val();
        //     if (personal_coordinacion.trim() == "") {
        //       msg += "Debe ingresar el Personal de Coordinación <b>(Ubicación del Paciente)</b><br>";
        //     }
        //     break;
        // }
      }

      if (id_tipo_egreso == 1) {
        var id_condicion_alta = $('#id_condicion_alta').val();
        if (id_condicion_alta == "") {
          msg += "Debe elegir una condición de alta<br>";
        }
      }

      if (id_tipo_egreso == 3) {
        var id_establecimiento_traslado = $('#id_establecimiento_traslado').val();
        var especialida_destino = $('#especialida_destino').val();
        var condicion_traslado = $('#condicion_traslado').val();
        if (id_establecimiento_traslado == "") {
          msg += "Debe elegir un establecimiento de traslado<br>";
        }
        if (especialida_destino == "") {
          msg += "Debe elegir una especialidad de destino<br>";
        }
        if (condicion_traslado == "") {
          msg += "Debe elegir una condicion de traslado<br>";
        }
      }
    }

    let addRequiereInternamientoQx = $('#addRequiereInternamientoQx').attr("aria-pressed");
    let pendiente_alta_emergencia_interconsulta_qx = parseInt($('#pendiente_alta_emergencia_interconsulta_qx').val(), 10);
    
    if (addRequiereInternamientoQx == "true" && pendiente_alta_emergencia_interconsulta_qx===0) {
      var ind_diag = $('#tblDiagnosticoQuirurgico tbody tr').length;
      var ind_proced_post = $('#table_procedimiento_intervencion tbody tr').length;
      var ind_cirujano = $('#table_medico_cirujano tbody tr').length;

      // const duracion_cirugia = $('#duracion_cirugia').val();
      // if(duracion_cirugia==""){
      //   msg+="Debe ingresar el <b>tiempo de duración (Centro quirurgico)</b></b><br>";
      // }
      var id_tipo_cirugia = $('#id_tipo_cirugia').val();
      if (id_tipo_cirugia == '1') {
        var hora_inicio_intervencion = $('#hora_inicio_intervencion').val();
        var hora_fin_intervencion = $('#hora_fin_intervencion').val();
        if (!hora_inicio_intervencion) {
          msg += "Debe ingresar la hora de inicio (Centro quirurgico)<br>";
        }
        if (!hora_fin_intervencion) {
          msg += "Debe ingresar la hora fin (Centro quirurgico)<br>";
        }
      }

      if (id_tipo_cirugia == '2' || id_tipo_cirugia == '3') {
        var horas = $('#horas').val();
        var minutos = $('#minutos').val();
        if (!horas) {
          msg += "Por lo menos ingrese la hora (Centro quirurgico)<br>";
        }
      }

      if (ind_diag > 0) {
        var errorEnDiagnosticos = false;
        $('#tblDiagnosticoQuirurgico tbody tr').each(function() {
          var id_diagnostico = $(this).find('.id_diagnostico_quirurgico').val();
          var id_tipo_diagnostico = $(this).find('.id_tipo_diagnostico_quirurgico').val();

          if (id_diagnostico === "") {
            msg += "Debe ingresar el <b>nombre de diagnóstico (Centro quirurgico)</b><br>";
            errorEnDiagnosticos = true;
          }
          if (id_tipo_diagnostico === "0") {
            msg += "Debe ingresar el <b>tipo de diagnóstico (Centro quirurgico)</b><br>";
            errorEnDiagnosticos = true;
          }
        });
        // Si no hay errores, limpiamos el mensaje de diagnóstico
        if (!errorEnDiagnosticos) {
          msg = msg.replace("Debe ingresar el <b>nombre de diagnóstico (Centro quirurgico)</b><br>", "");
          msg = msg.replace("Debe ingresar el <b>tipo de diagnóstico (Centro quirurgico)</b><br>", "");
        }
      } else {
        msg += "Debe ingresar mínimo <b>un diagnóstico (Centro quirurgico)</b><br>";
      }

      // Validación de procedimientos
      if (ind_proced_post > 0) {
        $('#table_procedimiento_intervencion tbody tr').each(function(index) {
          var id_procedimiento = $(this).find('.cmbProcedimientoIntervencion').val();
          if (id_procedimiento === "") {
            msg += "No debe de haber ningún <b>procedimiento de intervención sin ser seleccionado (Centro quirurgico)</b><br>";
          }
        });
      } else {
        msg += "Debe ingresar mínimo un <b>procedimiento de intervención (Centro quirurgico)</b><br>";
      }

      // Validación de cirujanos
      if (ind_cirujano > 0) {
        var errorEnCirujanos = false;
        $('#table_medico_cirujano tbody tr').each(function(index) {
          var id_cirujano = $(this).find('.cmbMedicoCirujano ').val();
          var id_cargo_medico = $(this).find('.cargoMedicoCirujano').val();

          if (id_cirujano === "") {
            msg += "Debe ingresar el nombre del <b>médico cirujano (Centro quirurgico)</b><br>";
            errorEnCirujanos = true;
          }
          if (id_cargo_medico === "") {
            msg += "Debe de seleccionar el <b>cargo del médico cirujano (Centro quirurgico)</b><br>";
            errorEnCirujanos = true;
          }
          // Si no hay errores, limpiamos el mensaje de procedimientos
          if (!errorEnCirujanos) {
            msg = msg.replace("Debe ingresar el <b>nombre del médico cirujano (Centro quirurgico)</b><br>", "");
          }
        });
      } else {
        msg += "Debe ingresar mínimo <b>un médico cirujano (Centro quirurgico)</b><br>";
      }
    }

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    } else {
      $.ajax({
        url: "{{ route('buscar_dia_estancia_ajax') }}",
        type: "GET",
        dataType: "JSON",
        data: {
          id_asegurado: <?php echo $prestacion->id_asegurado; ?>,
          fecha_egreso: fecha_egreso,
          hora_egreso: hora_egreso
        },
        success: function(result) {
          result.dia_estancia ++;
          if (result!=null) {
            if (result.dia_estancia>1) {
              Swal.fire({
              title: '<div class="titulo-padding"><p>Alerta de Estancia</p></div>',
              html: '<p class="descripcion-alert">EL PACIENTE TIENE REGISTRO DESDE:<span>El '+ result.fecha_ingreso +'</span> A LAS <span>'+ result.hora_ingreso +'</span> EN EL TÓPICO <span>'+ result.consultorio +'</span> CON UN TOTAL DE <span>'+ result.dia_estancia +' </span> DIA(S) DE ESTANCIA.<br><br>¿DESEA CONTINUAR?</p>',
              allowOutsideClick: false, 
              showCancelButton: true,
              confirmButtonText: '<b>CONTINUAR EL REGISTRO</b>',
              confirmButtonColor: '#3085d6',
              cancelButtonText: '<b>CANCELAR EL REGISTRO</b>',
              cancelButtonColor: 'red',
              }).then((result) => {
                if (result.isConfirmed) {
                  $('#frmAltaMedica').submit();
                }
              });
            } else {
              $('#frmAltaMedica').submit();
            }
          }
        }
      });
    }
  }

  function eliminarDiagnosticoXProducto(posicion) {
    // Se elimina el elemento en la posición dada
    procedimientosSeleccionados.splice(posicion, 1);

    // Se actualiza la posición de los elementos restantes
    procedimientosSeleccionados.forEach((item, index) => {
      item.posicion = index;
    });
    console.log(procedimientosSeleccionados);
  }

  $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    var inputVal = obj.parent().siblings().find('input').val();
    let posicion =inputVal.indexOf("-");
    let codigo = inputVal.substring(0,posicion);
    eliminarDiagnosticosarr(codigo);
    console.log("el codigo es" + codigo);
    obj.parent().parent().remove();
  });

  function eliminarDiagnosticosarr(codigon){
    let codigo2 = codigon.trim();
    let listIdDiagnosticoObjetos2 = [];

    for (let index = 0; index < listIdDiagnosticoObjetos.length; index++) {
      const element = listIdDiagnosticoObjetos[index];
      const codigo1 = element.codigo;

      if ((element.codigo).trim() !== codigo2) {
        listIdDiagnosticoObjetos2.push(element);
      }
    }
    listIdDiagnosticoObjetos = [];
    listIdDiagnosticoObjetos = listIdDiagnosticoObjetos.concat(listIdDiagnosticoObjetos2);

    procedimientosSeleccionados = procedimientosSeleccionados.map((item) => {
      if (item.codigo === codigo2) {
        return { ...item, id: "" };
      } else {
        return item;
      }
    });

    updateSelectElements2();
  }

  function updateSelectElements2() {
    // Obtener todos los elementos <select> con la clase "select-diagnostico"
    let selectElements = document.querySelectorAll(".select-diagnostico");

    selectElements.forEach((selectElement) => {
      // Almacenar el valor actualmente seleccionado
      let currentValue = selectElement.value;

      // Eliminar las opciones actuales
      selectElement.innerHTML = "";

      // Agregar las opciones basadas en listIdDiagnosticoObjetos

      //Ultimo registro del diagnostico
      var ultimoRegistro = listIdDiagnosticoObjetos[listIdDiagnosticoObjetos.length - 1];
      var conjuntoUnico = new Set();
      var lista_diagnosticos = [];
      // ESTO SIRVE PARA QUE NO HAYGA DUPLICACION DE DIAGNOSTICOS
      listIdDiagnosticoObjetos.forEach((item) => {
          if (item && item.id_diagnostico != null) {
              var claveUnica = String(item.id_diagnostico).trim();
              if (!conjuntoUnico.has(claveUnica)) {
                  conjuntoUnico.add(claveUnica);
                  lista_diagnosticos.push(item);
              }
          }
      });
      listIdDiagnosticoObjetos = [];
      listIdDiagnosticoObjetos = lista_diagnosticos;//LISTA DE DIAGNOSTICOS UNICOS

      selectElements.forEach((selectElement) => {
        let currentValue = selectElement.value;
        selectElement.innerHTML = "";

        listIdDiagnosticoObjetos.forEach((item) => {
          let option = document.createElement("option");
          option.value = item.id_diagnostico;
          option.textContent = item.nombre;
          selectElement.appendChild(option);
        });

      // Verificar si el valor seleccionado aún está disponible en las opciones
      let optionExists = Array.from(selectElement.options).some(
        (option) => option.value === currentValue
      );
       selectElement.value = optionExists ? currentValue : "";
      });      
    });
  }

  function addNewArray(id_diagnostico) {
    if( listIdDiagnosticoObjetos.findIndex(e  => e.id_diagnostico == id_diagnostico) ==-1 ){
       listIdDiagnostico.push(id_diagnostico);// solo es para validar si existen datos

      if (listIdDiagnostico.length > 0) {
        let codigo = JSON.stringify(id_diagnostico);

        $.ajax({
            "dataType": 'json',
            "type": "POST",
            "url": "/obtenerdatosDiagnosticos2",
            "data":{
              codigo : codigo
                  },
            "success": function (result) {
              listIdDiagnosticoObjetos.push(result.objeto);   
                  console.log(JSON.stringify(listIdDiagnosticoObjetos));
                  updateSelectElements2();
            },
            "error": function (msg, textStatus, errorThrown) {
                //location.href="login";
            }
        });
      }
    }
  }

  function AddDiagnostico() {
    var newRow = "";
    var ind = $('#tblDiagnostico tbody tr').length;

    if (ind > 0) {
      $('.id_diagnostico').each(function() {
        var ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
          ind = Number(ind_tmp) + 1;
        }
      });

      fila_medicamentos = $('#tblDiagnostico tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento = fila_medicamentos[idval];

    }

    var item_tipo_diagnostico = '';

    item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';

    newRow += '<tr>';
    newRow += '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico' + ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico' + ind + '" name="id_diagnostico[]" value="" data-id=' + ind + '></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind + '" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
    newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblDiagnostico tbody').append(newRow);

    var sexo = $('#nomsexo').val();
    if (sexo == 'M') {
      genero = 1;
    } else {
      if (sexo == 'F') {
        genero = 2;
      }
    }

    $("#nombre_diagnostico" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          //url: '../obtener_diagnostico/' + request.term,
          url: '../../obtener_diagnostico_genero/' + request.term + '/' + genero,
          dataType: "json",
          success: function(data) {
            let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function() {
              return parseInt($(this).val());
            }).get();
            let filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
            response(filtro);
          }
        })
      },
      select: function(e, ui) {
        var id_diagnostico = ui.item.id;
        $("#id_diagnostico" + ind).val(id_diagnostico);
        $("#nombre_diagnostico" + ind).prop("readonly", true);
      }
    });

    $("#nombre_diagnostico" + ind).on('blur', function() {
      var id_diagnostico = $("#id_diagnostico" + ind).val();
      if (id_diagnostico == "") {
        bootbox.alert("Falta ingresar el diagnostico en esta fila");
        $("#nombre_diagnostico" + ind).val("");
        return false;
      }else{
        addNewArray(id_diagnostico);  
      }
    });
  }

  function AddDiagnosticoQuirurgico() {
    var newRow = "";
    var ind = $('#tblDiagnosticoQuirurgico tbody tr').length;

    if (ind > 0) {
      $('.id_diagnostico_quirurgico').each(function() {
        var ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
          ind = Number(ind_tmp) + 1;
        }
      });

      fila_medicamentos = $('#tblDiagnosticoQuirurgico tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento = fila_medicamentos[idval];
    }

    var item_tipo_diagnostico = '';

    item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';

    newRow += '<tr>';
    newRow += '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_quirurgico[]" tabindex="8" required="" id="nombre_diagnostico_quirurgico' + ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control id_diagnostico_quirurgico typeahead tt-query" id="id_diagnostico_quirurgico' + ind + '" name="id_diagnostico_quirurgico[]" value="" data-id=' + ind + '></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico_quirurgico" id="cmbTipoDiagnosticoQuirurgico' + ind + '" name="id_tipo_diagnostico_quirurgico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
    newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblDiagnosticoQuirurgico tbody').append(newRow);

    var sexo = $('#nomsexo').val();
    if (sexo == 'M') {
      genero = 1;
    } else {
      if (sexo == 'F') {
        genero = 2;
      }
    }

    $("#nombre_diagnostico_quirurgico" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          //url: '../obtener_diagnostico/' + request.term,
          url: '../../obtener_diagnostico_genero/' + request.term + '/' + genero,
          dataType: "json",
          success: function(data) {
            let listaDiagnosticoSeleccionadoCQX = $(".id_diagnostico_quirurgico").map(function() {
              return parseInt($(this).val());
            }).get();
            let filtro = data.filter((element) => !listaDiagnosticoSeleccionadoCQX.includes(element.id));
            response(filtro);
          }
        })
      },
      select: function(e, ui) {
        var id_diagnostico = ui.item.id;
        $("#id_diagnostico_quirurgico" + ind).val(id_diagnostico);
        $("#nombre_diagnostico_quirurgico" + ind).prop("readonly", true);
      }
    });

    $("#nombre_diagnostico_quirurgico" + ind).on('blur', function() {
      const diagnostico = $("#nombre_diagnostico_quirurgico" + ind).val();
      const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
      const codigoDiagnostico = diagnostico.split("-")[0].trim();
      var id_diagnostico = $("#id_diagnostico_quirurgico" + ind).val();
      if (id_diagnostico == "") {
        bootbox.alert("Falta ingresar el diagnostico en esta fila");
        $("#nombre_diagnostico_quirurgico" + ind).val("");
        return false;
      } else {
        if (firstLetterCode == "Z" || flag_saludpol == 0) {
          bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado por SALUDPOL.");
        }
        if (codigoDiagnostico == "Y66.X") {
          bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado.");
        }
      }
    });

  }


  function soloNumerosMenosCero(e) {

    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
  }

  function firmar() {

    //var rowindex = $('#Grd2 tbody tr td').length;
    //$('#btnLiquidacionFirmarPdf').attr("disabled",true);
    //if (rowindex > 0) {

    $.ajax({
      url: "<?php echo url() ?>/genera_csv",
      type: "post",
      //data: p,
      data: $("#frmBloque").serialize(),
      //timeout: 30000,
      dataType: 'json'
    })
    .done(function(resultado) {
      console.log(resultado);
      if (resultado.sw) {
        var csv = "<?php echo url() ?>/tmp/csv/" + resultado.csv + ".csv";
        $("#doc_firmar").val(`${resultado.csv}.7z`);
        initInvoker('W');
      } else {
        //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
        //dialogError(function () {});
      }
      $('#btnLiquidacionFirmarPdf').attr("disabled", false);

    })
    .fail(function(jqXHR, textStatus) {
      //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
      //dialogError(function () {});
      console.log("Request failed: " + textStatus)
    })
    .always(function() {
      //bootbox.hideAll();
    });

    //} else {

    //dialogWarning("Debe hacer check en minimo una solicitud");
    //}	
  }

  function firmarsintokens() {
    var batchSignatureProcessPoller = setInterval(function() {
      $.ajax({
        url: "<?php echo url() ?>/sinTokens",
        type: "post",
        cache: false,
        dataType: 'json',
        data: $("#frmBloque").serialize(),
        success: function(completed) {
          if (completed) {
            location.href = '/all_atenciones_by_emergencia';
            clearInterval(batchSignatureProcessPoller);
          }
        }
      });
    }, 3000);
  }

  function cambiar_tipo_egreso() {
    var id_tipo_egreso = $("#id_tipo_egreso").val();

    if (id_tipo_egreso == '1') { //ALTA
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").hide();
      $("#egreso_defuncion").hide();
      $("#id_condicion_alta").val('');
      $("#egreso_traslado").hide();
      $("#alta_condicion").show('');
      $("#alta_condicion_tra").hide('');
      $("#alta_condicion_fuga").hide('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_ret").hide('');
      $("#egreso_retiro").hide();
      $("#egreso_fuga").hide();
    } else if (id_tipo_egreso == '2') { //HOSPITALIZACIÓN  
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").show();
      $("#egreso_defuncion").hide();
      $("#id_condicion_alta").val('');
      $("#egreso_traslado").hide();
      $("#alta_condicion").hide('');
      $("#alta_condicion_tra").show('');
      $("#alta_condicion_fuga").hide('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_ret").hide('');
      $("#egreso_retiro").hide();
      $("#egreso_fuga").hide();
      $.getJSON("/listar_consultorios_ajax/3", function(json) {
        document.getElementById("id_servicio_hospitalizacion").innerHTML = "";
        $('#id_servicio_hospitalizacion').append($('<option>').text("Seleccione").attr('value', ""));

        $.each(json, function(i, obj) {
          var len = obj.length;
          for (j = 0; j < len; j++) {
            if (obj[j].descripcion_corta_upss != null) {
              var nombre = obj[j].descripcion_corta_upss.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
              if (!nombre.includes("centro quirurgico")) {
                $('#id_servicio_hospitalizacion').append(
                  $('<option>')
                  .text(obj[j].descripcion_corta_upss)
                  .attr('value', obj[j].id_upss)
                  .attr('data-flag-quirurgico', obj[j].flag_quirurgico)
                );
              }
            }
          }
        });
      });

    } else if (id_tipo_egreso == '4') { //DEFUNCION  
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").hide();
      $("#egreso_traslado").hide();
      $("#egreso_defuncion").show();
      $("#alta_condicion").hide('');
      $("#alta_condicion_de").show('');
      $("#alta_condicion_fuga").hide('');
      $("#alta_condicion_tra").hide('');
      $("#alta_condicion_ret").hide('');
      $("#egreso_retiro").hide();
      $("#egreso_fuga").hide();
    } else if (id_tipo_egreso == '3') { //TRASLADO
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").hide();
      $("#egreso_defuncion").hide();
      $("#egreso_traslado").show();
      $("#id_condicion_alta").val('');
      $("#alta_condicion_fuga").hide('');
      $("#alta_condicion").hide('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_tra").show('')
      $("#alta_condicion_ret").hide('');
      $("#egreso_retiro").hide();
      $("#egreso_fuga").hide();
    } else if (id_tipo_egreso == '7') { // RETIRO VOLUNTARIO
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").hide();
      $("#egreso_defuncion").hide();
      $("#egreso_traslado").hide();
      $("#id_condicion_alta").val('');
      $("#alta_condicion_fuga").hide('');
      $("#alta_condicion").hide('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_tra").hide('');
      $("#alta_condicion_ret").show('');
      $("#egreso_retiro").show();
      $("#egreso_fuga").hide();
    } else if (id_tipo_egreso == '6') { // FUGA
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").hide();
      $("#egreso_defuncion").hide();
      $("#egreso_traslado").hide();
      $("#id_condicion_alta").val('');
      $("#alta_condicion_fuga").show('');
      $("#alta_condicion").hide('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_tra").hide('');
      $("#alta_condicion_ret").hide('');
      $("#egreso_fuga").show();
      $("#egreso_retiro").hide();
    } else if (id_tipo_egreso == '2') { //HOSPITALIZACIÓN  
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").show();
      $("#egreso_defuncion").hide();
      $("#alta_condicion_fuga").hide('');
      $("#id_condicion_alta").val('1');
      $("#egreso_traslado").hide();
      $("#alta_condicion_tra").show('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_ret").hide('');
      $("#egreso_retiro").hide();
      $("#egreso_fuga").hide();
    }

    if (parseInt("{{old('id_condicion_alta')}}") > 0) {
      const getLastIdCondicionAlta = "{{old('id_condicion_alta')}}";
      $("#id_condicion_alta").val(getLastIdCondicionAlta).change();
    }

    if (parseInt("{{old('id_servicio_hospitalizacion')}}") > 0) {
      const lastIdServicioHospitalizacion = "{{old('id_servicio_hospitalizacion')}}";
      $("#id_servicio_hospitalizacion").val(lastIdServicioHospitalizacion).change();
    }

    if (parseInt("{{old('id_establecimiento_traslado')}}") > 0) {
      const id_establecimiento_traslado = "{{old('id_establecimiento_traslado')}}";
      $("#id_establecimiento_traslado").val(id_establecimiento_traslado).change();
    }

    if (("{{old('especialida_destino')}}").length > 0) {
      const especialida_destino = "{{old('especialida_destino')}}";
      $("#especialida_destino").val(especialida_destino).change();
    }

    if (("{{old('condicion_traslado')}}").length > 0) {
      const condicion_traslado = "{{old('condicion_traslado')}}";
      $("#condicion_traslado").val(condicion_traslado).change();
    }

    if (("{{old('necropsia')}}").length > 0) {
      const necropsia = "{{old('necropsia')}}";
      $("#necropsia").val(necropsia).change();
    }

    if (("{{old('obs_traslado')}}").length > 0) {
      const obs_traslado = "{{old('obs_traslado')}}";
      $("#obs_traslado").val(obs_traslado);
    }

    if (("{{old('causas_muerte')}}").length > 0) {
      const causas_muerte = "{{old('causas_muerte')}}";
      $("#causas_muerte").val(causas_muerte);
    }

    if (("{{old('fecha_defuncion')}}").length > 0) {
      const fecha_defuncion = "{{old('fecha_defuncion')}}";
      $("#fecha_defuncion").val(fecha_defuncion);
    }

    if (("{{old('hora_defuncion')}}").length > 0) {
      const hora_defuncion = "{{old('hora_defuncion')}}";
      $("#hora_defuncion").val(hora_defuncion);
    }

    if (("{{old('especialidad_destino_otro')}}").length > 0) {
      const especialidad_destino_otro = "{{old('especialidad_destino_otro')}}";
      $("#especialidad_destino_otro").val(especialidad_destino_otro);
    }

    if (("{{old('obs_retiro')}}").length > 0) {
      const obs_retiro = "{{old('obs_retiro')}}";
      $("#obs_retiro").val(obs_retiro);
    }
    
    if (("{{old('obs_fuga')}}").length > 0) {
      const obs_fuga = "{{old('obs_fuga')}}";
      $("#obs_fuga").val(obs_fuga);
    }
  }

  if (parseInt("{{count(old('id_tipo_egreso'))}}") > 0) {
    cambiar_tipo_egreso();
  }

  function cambiar_derivacion() {
    var derivar = $("#derivar").val();
    if (derivar == 'SI') { //SI
      $.getJSON("/listar_consultorios_ajax/1", function(json) {
        document.getElementById("id_servicio_consulta_externa").innerHTML = "";
        $('#id_servicio_consulta_externa').append($('<option>').text("Seleccione").attr('value', ""));
        $('#id_servicio_consulta_externa').append($('<option>').text("OTRO").attr('value', "OTRO"));

        $.each(json, function(i, obj) {
          //alert(obj[0].nombre);      alert(obj.length);   
          var len = obj.length;
          for (j = 0; j < len; j++) {
            $('#id_servicio_consulta_externa').append($('<option>').text(obj[j].nombre).attr('value', obj[j].id));
          }
        });
      });
    } else {
      document.getElementById("id_servicio_consulta_externa").innerHTML = "";
    }
  }

  function getSexoGenero() {
    return ($('#nomsexo').val() == 'M') ? 1 : 2;
  }

  // HORA Y FECHA DE EGRESO
  if (("{{old('fecha_egreso')}}").length) {
    $("#fecha_egreso").val("{{old('fecha_egreso')}}");
  }

  if (("{{old('hora_egreso')}}").length) {
    $("#hora_egreso").val("{{old('hora_egreso')}}");
  }

  $(document).ready(function() {
    function handlerErrorsController() {
      const inputsConError = [];
      let message = "";
      @foreach ($errors->keys() as $input)
        inputsConError.push('{{ $input }}');
      @endforeach

      if (inputsConError && inputsConError.length > 0) {
        inputsConError.forEach(function(inputName) {
          $(`#${inputName}`).addClass("borde-alerta");
          $(`#${inputName}`).next('.select2-container').addClass('borde-alerta');
          if (inputName == 'edad_pac') {
            $(`#edad_paciente2`).addClass("borde-alerta");
          }
        });

        @if ($errors->any())
          let message = "";
          @foreach ($errors->all() as $error)
            message += `<span style="color: red; font-size: 1.5rem;font-weight: 700px;">- {{ $error }}</span> <br/>`;
          @endforeach
          Swal.fire({
            title: '<div class="titulo-padding"><p>Campos incorrectos</p></div>',
            html: `<div style="text-align: left; margin: 0px 20px 0px 20px;">${message}</div>`,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'ENTENDIDO',
            allowOutsideClick: false,
          });
        @endif
      }
    }
    handlerErrorsController();
  });

  $("#id_servicio_hospitalizacion").change(function() {
    let flagQuirurgico = $("#id_servicio_hospitalizacion option:selected").data("flag-quirurgico");
    if (flagQuirurgico == 1) {
      $("#centro_quirurgico").show(); // Mostrar el div
    } else {
      $("#centro_quirurgico").hide(); // Ocultar el div
      $("#requiere_intervencion_quirurgica").val("0");
      $('#addRequiereInternamientoQx').removeClass('active').attr('aria-pressed', 'false');
      $("#operacion_propuesta1").hide();
      $("#operacion_propuesta2").hide();
      $("#operacion_propuesta3").hide();
      $("#operacion_propuesta4").hide();
    }
  });

  $('#addRequiereInternamientoQx').on('click', function() {
    let addRequiereInternamientoQx = $('#addRequiereInternamientoQx').attr("aria-pressed");
    $("#requiere_intervencion_quirurgica").val("0");
    $('#pendiente_alta_emergencia_interconsulta_qx').val("0")
    $('#id_intervencion').val("0");
    $("#operacion_propuesta1").hide();
    $("#operacion_propuesta2").hide();
    $("#operacion_propuesta3").hide();
    $("#operacion_propuesta4").hide();
    //$("#fsProgramacionQx").hide();
    if (addRequiereInternamientoQx == "false") {
      var id_emergencia   = $('#id_emergencia').val();
      var id_prestacionx = $('#id_prestacionx').val();
      $.ajax({
          url: "{{ route('validar.pendiente.alta.emergencia.qx') }}",
          type: "POST",
          dataType: "json",
          data: {
              _token: "{{ csrf_token() }}",
              id_emergencia: id_emergencia,
              id_prestacion: id_prestacionx
          },
          beforeSend: function () {
              Swal.fire({
                  title: 'Verificando...',
                  text: 'Consultando si existe alta pendiente desde interconsulta a quirúrgico.',
                  icon: 'info',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  willOpen: () => {
                      Swal.showLoading()
                  }
              });
          },
          success: function (response) {
              Swal.close();
              $("#requiere_intervencion_quirurgica").val("1");
              if (response.existe) {
                  if (response.pendiente_alta_emergencia) {
                      Swal.fire({
                          title: 'ALERTA',
                          text: '⚠ Existe una intervención quirúrgica con ALTA PENDIENTE.',
                          icon: 'warning',
                          confirmButtonText: 'Aceptar'
                      });
                    $('#id_intervencion').val(response.id_intervencion);
                    $('#pendiente_alta_emergencia_interconsulta_qx').val(1);
                   // Limpiar tablas antes de agregar datos
                    $('#table_procedimiento_intervencion_alta').find('tbody').empty();
                    $('#table_diagnostico_intervencion_alta').find('tbody').empty();
                    $('#table_cirujanos_intervencion_alta').find('tbody').empty();

                    $('#centro_quirurgico_pendiente_alta_qx').show();

                    $('#turno').val(response.turno);
                    $('#duracion').val(response.duracion);
                    $('#tipo_dirugia').val(response.tipo_cirugia);
                    $('#clasificacion_cirugia').val(response.clasificacion_cirugia);

                    //AQUI TRAER LOS PROCEDIMIENTOS,DIAGNOSTICOS, CIRUJANOS
                    // Diagnósticos
                    response.diagnosticos_cqx.forEach(function(item) {
                        let fila = `<tr>
                            <td>${item.codigo_diagnostico} - ${item.descripcion_diagnostico}</td>
                            <td>${item.descripcion_tipo_diagnosticos}</td>
                        </tr>`;
                        $('#table_diagnostico_intervencion_alta tbody').append(fila);
                    });

                    // Procedimientos
                    response.procedimientos_cqx_emergencia.forEach(function(item) {
                        var fila = `<tr>
                            <td>${item.codigo_cpms} - ${item.descripcion_cpms}</td>
                        </tr>`;
                        $('#table_procedimiento_intervencion_alta').append(fila);
                    });
                    // Cirujanos
                    response.profesionales_quirugicos.forEach(function(item) {
                        var fila = `<tr>
                            <td>${item.paterno} ${item.paterno}, ${item.nombre}</td>
                            <td>${item.tipo_profesional}</td>
                        </tr>`;
                        $('#table_cirujanos_intervencion_alta').append(fila);
                    });
                  } else {
                      Swal.fire({
                          title: 'OK',
                          text: '✔ No existe alta pendiente. Puede continuar.',
                          icon: 'success',
                          timer: 2000,
                          showConfirmButton: false
                      });
                      $('#btn_guardar').prop('disabled', false);
                  }
              } else {
                  $('#centro_quirurgico_pendiente_alta_qx').hide();
                  $('#centro_quirurgico_no_pendiente_alta').show();
                  //$("#requiere_intervencion_quirurgica").val("1");
                  $("#operacion_propuesta1").show();
                  $("#operacion_propuesta2").show();
                  $("#operacion_propuesta3").show();
                  $("#operacion_propuesta4").show();
                  $.ajax({
                    url: '/get_procedimientos',
                    type: "GET",
                    dataType: "json",
                    success: function(result) {
                      let newOption = "";
                      newOption = "<option value=''>--Seleccionar--</option>";
                      $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "' >" + oo.codigo + " - " + oo.descripcion + "</option>";
                      });
                      $('#cmbProcedimientoIntervencionTemp').html(newOption);
                    }
                  });
                  //medicos cirujanos
                  // LLamar a medicos cirujanos
                  let id_establecimiento = $("#id_establecimiento").val();
                  let id_servicio = $("#id_servicio").val();
                  $.ajax({
                    url: '/get_profesionales_x_especialidad/' + id_establecimiento + '/' + id_servicio,
                    type: "GET",
                    dataType: "json",
                    success: function(result) {
                      let newOption = "";
                      newOption = "<option value=''>--Seleccionar--</option>";
                      $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "' >" + oo.paterno + " " + oo.materno + ", " + oo.nombre + "</option>";
                      });
                      $('#cmbProfesionalesTemp').html(newOption);
                    }
                  });                  
              }
          },
          error: function (xhr) {
              Swal.close();
              Swal.fire({
                  title: 'Error',
                  text: '❌ Error al validar la intervención. Reintente.',
                  icon: 'error',
                  confirmButtonText: 'Aceptar'
              });
              console.error(xhr.responseText);
          }
      });
    }else{
      $("#centro_quirurgico_no_pendiente_alta").hide();
      $("#centro_quirurgico_pendiente_alta_qx").hide();
    }
  });

  $('#addProcedimientoIntervencion').on('click', function() {
    AddProcedimientoIntervencion();
  });

  function AddProcedimientoIntervencion() {
    let newRow = "";
    let ind = $('#table_procedimiento_intervencion tbody tr').length;

    let item_procedimiento = "";
    $('#cmbProcedimientoIntervencionTemp option').each(function() {
      item_procedimiento += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
    });

    newRow += '<tr>';
    newRow += '<td><select class="form-control cmbProcedimientoIntervencion" id="cmbProcedimientoIntervencion' + ind + '" ind="' + ind + '" name="id_procedimiento_intervencion[]" >' + item_procedimiento + '</select></td>';
    newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:10px;"><i class="fa fa-times"></i> </button></td>';
    newRow += '</tr>';

    $('#table_procedimiento_intervencion tbody').append(newRow);
    $("#cmbProcedimientoIntervencion" + ind).select2({
      max_selected_options: 4
    }).focus();

    $("#cmbProcedimientoIntervencion" + ind).on("change", function(e) {
      var flagx = 0;
      cmb = $(this);
      id_producto = $("#cmbProcedimientoIntervencion" + ind).val();
      id_user = <?php echo Auth::user()->id; ?>;

      $('.cmbProcedimientoIntervencion').each(function() {
        var ind_tmp = $(this).val();
        if ($(this).val() == id_producto) flagx++;
      });

      if (flagx > 1) {
        bootbox.alert("El Procedimiento ya ha sido ingresado");
        $("#cmbProcedimientoIntervencion" + ind).val("").trigger("change");
        return false;
      }

    });
    item_procedimiento++;
  }

  $('#table_procedimiento_intervencion tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    // Selecciona la fila completa
    var fila = obj.closest('tr');

    // Obtiene la posición de la fila
    var filaPosition = fila.index();

    // Imprime la posición del elemento 'input' en la consola
    console.log("La posición del input es: " + filaPosition);

    // Elimina la fila
    obj.parent().parent().remove();
    // filasmedicamentos--;
  });

  $('#addMedicoCirujano').on('click', function() {
    AddMedicoCirujano();
  });

  $('#addProducto').on('click', function() {
    AddProducto();
  });

  function AddMedicoCirujano() {
    var newRow = "";
    var ind = $('#table_medico_cirujano tbody tr').length;

    var item_producto = "";
    $('#cmbProfesionalesTemp option').each(function() {
      item_producto += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
    });

    let item_cargo = "";
    $('#cmbTipoProfesionalTemp option').each(function() {
      item_cargo += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
    });

    newRow += '<tr>';
    newRow += '<td><select class="form-control cmbMedicoCirujano" id="cmbMedicoCirujano' + ind + '" ind="' + ind + '" name="id_medico_cirujano[]" >' + item_producto + '</select></td>';
    newRow += '<td><select class="form-control cargoMedicoCirujano" id="cmbMedicoCirujanoCargo' + ind + '" name="id_medico_cirujano_cargo[]" style="margin-left:20px;">' + item_cargo + '</select></td>';
    newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:30px;"><i class="fa fa-times"></i> </button></td>';
    newRow += '</tr>';

    $('#table_medico_cirujano tbody').append(newRow);
    $("#cmbMedicoCirujano" + ind).select2({
      max_selected_options: 4
    }).focus();

    $("#cmbMedicoCirujano" + ind).on("change", function(e) {
      var flagx = 0;
      cmb = $(this);
      id_producto = $("#cmbMedicoCirujano" + ind).val();
      id_user = {{ Auth::user()->id }};

      $('.cmbMedicoCirujano').each(function() {
        var ind_tmp = $(this).val();
        if ($(this).val() == id_producto) flagx++;
      });

      if (flagx > 1) {
        bootbox.alert("El Médico ya ha sido ingresado");
        $("#cmbMedicoCirujano" + ind).val("").trigger("change");
        return false;
      }

    });
    filasmedicamentos++;
  }

  $('#table_medico_cirujano tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    var fila = obj.closest('tr');
    var filaPosition = fila.index();
    obj.parent().parent().remove();
  });

  function filtrarNumeroDecimal(input) {
    input.value = input.value
      .replace(/[^0-9.]/g, '')
      .replace(/(\..*?)\..*/g, '$1');
  }

  document.getElementById('hora_inicio_intervencion').addEventListener('change', calcularDuracion);
  document.getElementById('hora_fin_intervencion').addEventListener('change', calcularDuracion);

  function calcularDuracion() {
    const inicio = document.getElementById('hora_inicio_intervencion').value;
    const termino = document.getElementById('hora_fin_intervencion').value;

    if (inicio && termino) {
      const inicioMilis = convertirATiempoEnMilisegundos(inicio);
      let terminoMilis = convertirATiempoEnMilisegundos(termino);

      // Si la hora de fin es menor que la de inicio, asumimos que pasó la medianoche
      if (terminoMilis < inicioMilis) {
        terminoMilis += 24 * 60 * 60 * 1000; // sumamos 24 horas en milisegundos
      }

      const diferenciaMilis = terminoMilis - inicioMilis;

      // Calcular total minutos
      const totalMinutos = Math.floor(diferenciaMilis / (1000 * 60));
      document.getElementById('duracion_cirugia').value = totalMinutos;

      // Calcular en formato HH:MM
      const duracionHHMM = convertirMilisegundosATiempo(diferenciaMilis);
      document.getElementById('duracion_cirugia_calculada').value = duracionHHMM;
    }
  }

  // Convierte HH:MM a milisegundos
  function convertirATiempoEnMilisegundos(hora) {
    const [h, m] = hora.split(':').map(Number);
    return (h * 60 * 60 * 1000) + (m * 60 * 1000);
  }

  // Convierte milisegundos a formato HH:MM
  function convertirMilisegundosATiempo(ms) {
    const totalMin = Math.floor(ms / (1000 * 60));
    const horas = Math.floor(totalMin / 60);
    const minutos = totalMin % 60;
    return `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}`;
  }

  $('#id_tipo_cirugia').change(function() {
    var electiva = $('#id_tipo_cirugia').val();
    if (electiva == '1') {
      $('#electiva').show();
      $('#duracion_cirugia_calculada').show();
      $('#duracion_cirugia_calculada').prop('disabled', true);
      $('#hora_minuto').hide();
      $('#duracion_cirugia').val(0);
      $('#horas').val(0);
    } else {
      $('#electiva').hide();
      $('#duracion_cirugia_calculada').hide();
      $('#hora_minuto').show();
      $('#duracion_cirugia').val(0);
      $('#minutos').val(0);
    }
  });

  $('#horas, #minutos').on('input', function() {
    const horas = parseInt($('#horas').val()) || 0;
    const minutos = parseInt($('#minutos').val()) || 0;
    const total = horas * 60 + minutos;
    $('#duracion_cirugia').val(total);
  });

  /*function getCamasUbicacion(id_servicio_ubicacion) { 
    const id_ipress = $('#id_establecimiento').val();
    const tipo_atencion = "HOSPITALIZACION";

    // consultar mediante una ruta hacia la base datos pasando el id_upss_ubicacion y obtener el  
    return new Promise((resolve, reject) => { 
      $.ajax({
        url: `/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto_activos/${id_ipress}/${tipo_atencion}/${id_servicio_ubicacion}`,
        success: function(result) {
          resolve(result);
        }
      });
    });
  }*/

  /*function buildOptionsSelect(arrayOptions, nameSelect) {
    $(`#${nameSelect}`).empty();  
    $(`#${nameSelect}`).append("<option value=''>-Seleccione-</option>");
    if (arrayOptions && arrayOptions.length > 0) {
      arrayOptions.forEach((option, index) => {
        $(`#${nameSelect}`).append(`<option value='${option.id}' data-id_clase_cama='${option.id_clase_cama}'>${option.nombre} - ${option.clase_cama}</option>`);
      });
    }
    $(`#${nameSelect}`).val("").change();
  }*/

  /*$('#id_upss_ubicacion').change(async function() { // ahora es id_pabellon_ubicacion
    let id_upss_ubicacion_selected = $('#id_upss_ubicacion').val();
    if (parseInt(id_upss_ubicacion_selected) > 0) {
      const id_servicio_ubicacion = $(this).find(':selected').data('id_servicio'); console.log(id_servicio_ubicacion);
      $("#id_servicio_ubicacion").val(id_servicio_ubicacion);
      const camas_ubicacion = await getCamasUbicacion(id_servicio_ubicacion);
      buildOptionsSelect(camas_ubicacion, 'id_cama_ubicacion');
      // $('#id_cama_ubicacion').val(parseInt(dato_cama.id)).trigger('change');
    } else {
      $("#id_servicio_ubicacion").val("");
      $("#id_cama_ubicacion").empty();
    }
  });*/

  /*$('#id_cama_ubicacion').change(async function() {
    $('#id_cama_ubicacion').removeClass('select-rojo select-naranja select-azul'); // limpia clases
    let id_cama_ubicacion_selected = $('#id_cama_ubicacion').val();

    if (parseInt(id_cama_ubicacion_selected) > 0) {
      const id_clase_cama_ubicacion = $(this).find(':selected').data('id_clase_cama'); console.log(id_clase_cama_ubicacion);
      switch (id_clase_cama_ubicacion) {
        case 1:
        case 6:
        case 7:
          Swal.fire({
            title: 'Mensaje',
            html: 'Para la asignación de esta cama debe realizarse la coordinación obligatoria con el área médica de la ubicación del paciente.',
            input: 'text',
            inputLabel: 'Nombre del médico/profesional con quien se coordinó:',
            // inputPlaceholder: 'Escriba su nombre aquí',
            showCancelButton: true,
            confirmButtonText: 'CONTINUAR',
            cancelButtonText: 'CANCELAR',
            inputValidator: (value) => {
              if (!value) {
                return 'Porfavor completar los campos requeridos para continuar.';
              }
            }
          }).then((result) => {
            if (result.isConfirmed) {
              const datosPersonalCoordinacion = result.value;
              $("#personal_coordinacion").val(datosPersonalCoordinacion);

              switch (parseInt(id_clase_cama_ubicacion)) {
                case 1:
                  $('#id_cama_ubicacion').addClass('select-rojo');console.log("entro en rojo");
                  break;
                case 6:
                  $('#id_cama_ubicacion').addClass('select-naranja');console.log("entro en naranja");
                  break;
                case 7:
                  $('#id_cama_ubicacion').addClass('select-azul');console.log("entro en azul");
                  break;
              }

            } else if (result.isDismissed) {
              // $("#id_cama_ubicacion").val("").trigger("change");.change();
              $("#id_cama_ubicacion").val("").change();
            }
          });
          break;
      }
    }
  });*/

  $('#btnCargarUltimoDiagnostico').on('click', function () {
    var id_emergencia = $('#id_emergencia').val();
    var $btn = $(this);

    $btn.prop('disabled', true);

    $.ajax({
      url: '/obtener_ultimo_diagnostico_emergencia/' + id_emergencia,
      dataType: 'json',
      success: function (data) {
        if (!data || data.length === 0) {
          bootbox.alert('No se encontraron diagnósticos registrados para esta emergencia.');
          return;
        }
        $('#tblDiagnostico tbody').empty();
        listIdDiagnostico = [];
        listIdDiagnosticoObjetos = [];  
        $.each(data, function (i, diag) {
          AddDiagnostico();
          var lastInd = $('.id_diagnostico').last().data('id');
          var nombre = diag.nombre ? (diag.codigo + ' - ' + diag.nombre) : diag.codigo;
          $('#id_diagnostico' + lastInd).val(diag.id_diagnostico);
          $('#nombre_diagnostico' + lastInd).val(nombre).prop('readonly', true);

           if (listIdDiagnosticoObjetos.findIndex(e => e.id_diagnostico == diag.id_diagnostico) === -1) {
            listIdDiagnosticoObjetos.push({
              id: diag.id_diagnostico,          
              id_diagnostico: diag.id_diagnostico,
              codigo: diag.codigo,
              nombre: diag.nombre
            });
            listIdDiagnostico.push(diag.id_diagnostico); 
          }
          updateSelectElements2();
        });
      },
      error: function () {
        bootbox.alert('Error al obtener los diagnósticos.');
      },
      complete: function () {
        $btn.prop('disabled', false);
      }
    });
  });

  let contadorFilas = 0;

  function AddProducto() {
    var tipo_receta = $('#tipo_receta').val();
    var id_consultorio = $('#id_consultorio').val();
    var id_farmacia = $('#id_farmacia_receta').val();
    if (tipo_receta == "") {
      bootbox.alert("Seleccione el tipo atención");
      return false;
    }
    if (id_consultorio == "") {
      bootbox.alert("Seleccione un servicio");
      return false;
    }
    if (id_farmacia == '') {
      bootbox.alert("Primero debe seleccionar una farmacia.");
      return false;
    }
    var newRow = "";
    //var ind = $('#example tbody tr').length;
    // var ind = filasmedicamentos
    var ind = contadorFilas++;
    var tabindex = 101;
    var nuevalperiodo = "";
    if (ind > 0) {
      $('.cmbMedicamento').each(function() {
        var ind_tmp = $(this).attr("ind");
        if (ind_tmp => ind) {
          //ind = Number(ind_tmp) + 1;
          tabindex += 3;
        }
      });

      fila_medicamentos = $('#example tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):  ?>
      fila_medicamento = fila_medicamentos[idval];
      var valante = $(fila_medicamento).find('#nro_med_entregados').val();
      if (valante == "") {
        bootbox.alert("Ingrese cantidad entregada en el producto anterior");
        return false;
      }
      <?php //endif; ?>

      //nuevalperiodo = document.getElementById('tiempo_periodo' + idval).value;
    }

    var item_producto = "";
    $('#cmbMedicamentoTemp option').each(function() {
      // item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
      //   "</option>"
      item_producto += "<option value='" + $(this).val() + "' ru='" + $(this).attr("ru") + "' infecto='" + $(this).attr("infecto") + "'>" + $(this).html() + "</option>";
    });

    var item_via_administracion = '';
    item_via_administracion+='<option value="0">--Seleccionar--</option>';
    item_via_administracion+='<option value="1">Oral</option>';
    item_via_administracion+='<option value="2">Sublingual</option>';
    item_via_administracion+='<option value="3">Rectal</option>';
    item_via_administracion+='<option value="4">Intravenosa</option>';
    item_via_administracion+='<option value="5">Intramuscular</option>';
    item_via_administracion+='<option value="6">Subcutánea</option>';
    item_via_administracion+='<option value="7">Dérmica</option>';
    item_via_administracion+='<option value="8">Nasal</option>';
    item_via_administracion+='<option value="9">Oftalmológica</option>';
    item_via_administracion+='<option value="10">Inhalatoria</option>';
    item_via_administracion+='<option value="11">Epidural</option>';
    item_via_administracion+='<option value="12">Intratecal</option>';
    item_via_administracion+='<option value="13">Vaginal</option>';
    item_via_administracion+='<option value="14">Intraarticular</option>';
    item_via_administracion+='<option value="17">Parenteral</option>';
    item_via_administracion+='<option value="15">No Aplica</option>';
    item_via_administracion+='<option value="16">Otros</option>';

    newRow +='<tr>';
    newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
    
    //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    //newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
    newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" maxlength="3" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +'"/>';
    newRow +='<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';

    newRow += '<td><input type="text" name="posologia[]" maxlength="150" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
      ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';
    
    <?php //endif;?>
    newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
    newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
    newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
    newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados' +ind +'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
    
    <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
    //newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
    <?php //endif;?>
    
    newRow += '<td><select class="form-control select-diagnostico"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';  
    newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_producto'+ind+'"><i class="fa fa-plus"></i> </button></td>';
    newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
    newRow +='</tr>';
    $('#example tbody').append(newRow);
    // $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();
    let select = $("#cmbMedicamento" + ind);
    select.select2({
        width: '100%',
        dropdownAutoWidth: true
    });

    const selectElementProd = document.getElementById('miSelectProd' + ind);

    $('.tiempo_periodo').on('input', function () {
      const valor = parseInt($(this).val(), 10);

      if (!isNaN(valor) && valor > 180) {
        Swal.fire({
          icon: 'warning',
          title: '¡Atención!',
          text: 'El tiempo de periodo excede los 180 días.',
          confirmButtonText: 'Cerrar'
        });

      }
    });

    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement('option');
      option.value = item.id_diagnostico;
      option.textContent = item.nombre;
      selectElementProd.appendChild(option);
    });
    $("#abrir-modal-" + ind).click(function(e) {
      e.stopPropagation();
    
      abrirModal(ind);
    });
	
    $("#nro_med_solictados" + ind).keypress(function(e) {
      if (e.which == 13) {
        <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
        AddProducto();
        <?php //else:?>
        validproductoseleccionado({{Auth::user()->id_farmacia}},this);
        <?php //endif;?>
      }
    });

    $("#add_producto"+ind).click(function(e){
        AddProducto();
        validproductoseleccionado({{Auth::user()->id_farmacia}},this);
        btnAgregarProducto = $(this);
        $(btnAgregarProducto).attr('disabled', 'disabled');
    });

    $("#cmbMedicamento"+ind).on("change", function (e) {
      var flagx = 0;
      cmb = $(this);
      id_producto = $("#cmbMedicamento"+ind).val();
      id_user={{Auth::user()->id}};
      var fila = cmb.closest("tr");
      
      $('.cmbMedicamento').each(function(){
        var ind_tmp = $(this).val();
        if($(this).val() == id_producto)flagx++;
      });
      
      if(flagx > 1){
        bootbox.alert("El producto farmaceutico ya ha sido ingresado");
        $("#cmbMedicamento"+ind).val("").trigger("change");
        return false;
      }

      // ================= AJAX =================
      let id_farmacia = $('#id_farmacia_receta').val();
      $.ajax({
        url: '/cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia,
        type: 'GET',
        dataType: 'json'
        }).done(function(data) {
          fila.find(".limpia_text").val("");
          fila.find("#nro_stocks").val(data.cantidad);
          fila.find("#nro_stocks_establecimiento").val(data.cantidadEstablecimiento);
          fila.find("#nro_stocks_almacen").val(data.cantidadAlmacen);
          fila.find(".tiempo_periodo").focus();
          fila.find("#nro_med_solictados").val("");
          fila.find("#nro_med_entregados").val("");
          fila.find("#lotes_lote").val("");
          fila.find("#lotes_cantidad").val("");
          fila.find("#lotes_registro_sanitario").val("");
          fila.find("#lotes_fecha_vencimiento").val("");
        });
      });

    filasmedicamentos++;
    }
    
    function validproductoseleccionado(id_farmacia, btn) {
      var dni_beneficiario = $('#dni_beneficiario').val();
      var nro_dias = 30;
      var objTr = $(btn).closest("tr");
      var id_producto = objTr.find(".cmbMedicamento").val();
      $.ajax({
        url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
        success: function (result) {
        var msg   = "";
        var n     = 0;
        var flag  = true;
        for(var key in result) {
          if(n == 0){
            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";              
            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                        msg += "<thead>";
                          msg += "<tr>";
            msg += "<th>Farmacia</th>";
            msg += "<th>Nro. Receta</th>";
            msg += "<th>Fecha Expedicion</th>";
            msg += "<th>Producto</th>";
            msg += "<th>Tiempo(Dias)</th>";
            msg += "<th>Dias Transcurridos</th>";
            msg += "<th>Dias Pendientes</th>";
            msg += "<th>Cantidad Prescrita</th>";
                          msg += "</tr>";
                        msg += "</thead>";
            msg += "<tbody>";
          }
              
          msg += "<tr><td>"+(result[key].farmacia)+"</td>";
          msg += "<td>"+(result[key].nro_receta)+"</td>";
          msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
          msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
          msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
          msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
          msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
          msg += "<td>"+(result[key].cantidad_prescrita)+"</td></tr>";
          n++;
        }
          if(msg != "")/*{
              verlotes(id_farmacia, btn);      
          } 
          else */
          {
            bootbox.confirm({
                title: "Alerta",
                message: msg,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancelar'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Continuar'
                    }
                },
                callback: function (result) {
                  if(result==true){
                    verlotes(id_farmacia, btn);
                  }
                }
            });
          }
        }
      });
    }

</script>
<style>
  .modal-dialog {
    width: 1200px !important;
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

  .alert {
    padding: 10px;
    background-position: 2% 7px;
    background-repeat: no-repeat;
    background-size: auto 35px;
    /*background-color: rgba(0, 0, 0, 0);*/
    border: 0;
    min-width: auto !important;
    text-align: left;
    padding-left: 68px;
    font-size: 14px;
    margin-bottom: 10px;
  }

  .alert.alert-danger {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAARVBMVEX////7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0NAQECdhUHYsUKGdEG1l0JLSEBuYkHkukPKU8NPAAAADnRSTlMA4EDAoCAQ8IBgkDDQUFItelQAAAC0SURBVHherZBLDsMwCAVD7Nj5gvPr/Y/a0FpGLTibdnbozQJN829agPZu7/CiuxE8C76+T/hiqgruLbjqh5ip/TljZrb3AQuDKYAIYO09L3TBRq/3EHk4shCDEkZkTqITmdGMLIIO7r8FbzfaiDappSNjIkpZcCqyCDo4WAJYkR8prTp4ADSBUCIX9uPY5epL5AJdyBWDRDYFHCWyCiXBObKwrh+nl4g2TiLVhSXW97g0v/MEHIQbCYeFmYAAAAAASUVORK5CYII=);
    border-top: 1px solid rgba(140, 0, 0, 0.4);
    border-bottom: 1px solid rgba(140, 0, 0, 0.4);
  }

  .alert.alert-info {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAADBklEQVR42u3YyWsTURwHcP8aQUWtWlu7JEbtpngQvIqXevDWm1fXehO12BTErdQiVMFQ8ODBusWkmSa1lVrTxdrG0FA9WJtkljfL19/LTDKMibl0EorkwReSCQ8+fN+bl2G2AdhSqYH+P1CpAS0DfXUI+ufzUCfaoQptYMIxsMhRsE/d0L4/BNR0qcnug4y159CmTkOLdkKNdnBQAaNEjkAZ90EOH4YcOQ51dQQVBRnLN6DHuqDFOqGZGEpbaVDICynkAVvoRUVAxuqQE8PjXC7C/AX64KG0gq3ch6sgQ1zkEBtTph2FY8JeApkYKdgCMdgMPTMP10D64kUOsTDtDsw/27FAIge9b4Y8e8EdELS0jTAh5TF2Ow5Q9l0TDDWNTYOMjSkLQREcEAdGCRfvHdFcLgI1EegQtPXY5kFactAE2JDyGHvvONrhILZ81w3QIxNQCEfw2JvYxpRYKrMdSqNLoNSzPMBGTJwEmz4HuSSGMnkWYrgjv1Rm3jZATQ5vHqT//sgRVhtmI8p0NwADbOF6EUaOX4GiZCHGzhAkD2ogUD20XxNw5baXhRN2G2EvR+QwhqFDme91YJgiQfpyCTamkTAHaQm97p1DauIBR1jxWI14CHMNuqZCmbsKmcKYBLkYQzkA9m3APRA/P6TxrvztbCfYkoOoqgKVydTQ5ZIYMdgKQ91wD8SHvj7JAYXb2T5jmqEkhiEnHnNIESb7po72joCK/Ntray8sRD7UBkVeGoBEIQilwYFRUwFU9HmIrdzjCHtpKNJXP48FqSfIfspe2jf+Sj4P2UOMnOKQfCOE6Ye0eMdqZR+yr/fwc8icXA2QmnzCD7pCI+JiH8SFPt4KYXYjO7YTLDFYPZCensu3QakjzC2I8zcJsguZsR3IvNoOfWO2eiA+tJ9jfI9Q+qEkn1JGoCz1UW5D+/HSnFhNUCqVQjwezyUajSIUChW+02/VB42OjqJMaqCtB5qZmUEgEIDf73eEXxMEofogPtLpNHp6euDz+Xhyn+lauUm1tx81UMXzBw5wT9JYWi53AAAAAElFTkSuQmCC);
    border-top: 1px solid rgba(255, 165, 0, 0.4);
    border-bottom: 1px solid rgba(255, 165, 0, 0.4);
    color: rgb(225, 75, 0);
  }

  .alert.alert-success {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
    border-top: 1px solid limegreen;
    border-bottom: 1px solid limegreen;
  }

  .form-control {
    height: 28px;
    text-align: left !important;
  }

  .form-group {
    margin-bottom: 10px;
  }

  #divHorariox {
    /*height: 200px!important;;*/
    /*background-color: #fed9ff; */
    /*width: 600px; */
    width: 100%;
    height: 550px;
    overflow-x: hidden;
    overflow-y: auto;
    text-align: center;
    padding: 0px !important;
  }

  /********************/

  body {
    font-family: 'Montserrat', 'Lato', 'Open Sans', 'Helvetica Neue', Helvetica, Calibri, Arial, sans-serif;
    color: #6b7381;
    background: #f2f2f2;
  }

  .jumbotron {
    background: #6b7381;
    color: #bdc1c8;
  }

  .jumbotron h1 {
    color: #fff;
  }

  .example {
    margin: 4rem auto;
  }

  .example>.row {
    margin-top: 2rem;
    height: 5rem;
    vertical-align: middle;
    text-align: center;
    border: 1px solid rgba(189, 193, 200, 0.5);
  }

  .example>.row:first-of-type {
    border: none;
    height: auto;
    text-align: left;
  }

  .example h3 {
    font-weight: 400;
  }

  .example h3>small {
    font-weight: 200;
    font-size: 0.75em;
    color: #939aa5;
  }

  .example h6 {
    font-weight: 700;
    font-size: 0.65rem;
    letter-spacing: 3.32px;
    text-transform: uppercase;
    color: #bdc1c8;
    margin: 0;
    line-height: 5rem;
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

  .btn-toggle.btn-lg.btn-sm:before {
    text-align: right;
  }

  .btn-toggle.btn-lg.btn-sm:after {
    text-align: left;
    opacity: 0;
  }

  .btn-toggle.btn-lg.btn-sm.active:before {
    opacity: 0;
  }

  .btn-toggle.btn-lg.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-lg.btn-xs:before,
  .btn-toggle.btn-lg.btn-xs:after {
    display: none;
  }

  .btn-toggle.btn-sm {
    margin: 0 0.5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
  }

  .btn-toggle.btn-sm:focus,
  .btn-toggle.btn-sm.focus,
  .btn-toggle.btn-sm:focus.active,
  .btn-toggle.btn-sm.focus.active {
    outline: none;
  }

  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: 1.5rem;
    width: 0.5rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }

  .btn-toggle.btn-sm:before {
    content: 'NO';
    left: -0.5rem;
  }

  .btn-toggle.btn-sm:after {
    content: 'SI';
    right: -0.5rem;
    opacity: 0.5;
  }

  .btn-toggle.btn-sm>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }

  .btn-toggle.btn-sm.active {
    transition: background-color 0.25s;
  }

  .btn-toggle.btn-sm.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }

  .btn-toggle.btn-sm.active:before {
    opacity: 0.5;
  }

  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-sm.btn-sm:before,
  .btn-toggle.btn-sm.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }

  .btn-toggle.btn-sm.btn-sm:before {
    text-align: right;
  }

  .btn-toggle.btn-sm.btn-sm:after {
    text-align: left;
    opacity: 0;
  }

  .btn-toggle.btn-sm.btn-sm.active:before {
    opacity: 0;
  }

  .btn-toggle.btn-sm.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-sm.btn-xs:before,
  .btn-toggle.btn-sm.btn-xs:after {
    display: none;
  }

  .btn-toggle.btn-xs {
    margin: 0 0;
    padding: 0;
    position: relative;
    border: none;
    height: 1rem;
    width: 2rem;
    border-radius: 1rem;
  }

  .btn-toggle.btn-xs:focus,
  .btn-toggle.btn-xs.focus,
  .btn-toggle.btn-xs:focus.active,
  .btn-toggle.btn-xs.focus.active {
    outline: none;
  }

  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    line-height: 1rem;
    width: 0;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }

  .btn-toggle.btn-xs:before {
    content: 'NO';
    left: 0;
  }

  .btn-toggle.btn-xs:after {
    content: 'SI';
    right: 0;
    opacity: 0.5;
  }

  .btn-toggle.btn-xs>.handle {
    position: absolute;
    top: 0.125rem;
    left: 0.125rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 0.75rem;
    background: #fff;
    transition: left 0.25s;
  }

  .btn-toggle.btn-xs.active {
    transition: background-color 0.25s;
  }

  .btn-toggle.btn-xs.active>.handle {
    left: 1.125rem;
    transition: left 0.25s;
  }

  .btn-toggle.btn-xs.active:before {
    opacity: 0.5;
  }

  .btn-toggle.btn-xs.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-xs.btn-sm:before,
  .btn-toggle.btn-xs.btn-sm:after {
    line-height: -1rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.275rem;
    width: 1.55rem;
  }

  .btn-toggle.btn-xs.btn-sm:before {
    text-align: right;
  }

  .btn-toggle.btn-xs.btn-sm:after {
    text-align: left;
    opacity: 0;
  }

  .btn-toggle.btn-xs.btn-sm.active:before {
    opacity: 0;
  }

  .btn-toggle.btn-xs.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-xs.btn-xs:before,
  .btn-toggle.btn-xs.btn-xs:after {
    display: none;
  }

  .btn-toggle.btn-secondary {
    color: #6b7381;
    background: #bdc1c8;
  }

  .btn-toggle.btn-secondary:before,
  .btn-toggle.btn-secondary:after {
    color: #6b7381;
  }

  .btn-toggle.btn-secondary.active {
    background-color: #ff8300;
  }

  .input-date {
    background-color: #ffffff !important;
  }
</style>
@endsection
@section('content')

<?php $grados = array("NINGUNO", "ALUMNO", "CADETE", "ALFÉREZ DE SERVICIO", "ALFÉREZ MAESTRO ARMERO", "CAPITÁN", "CAPITÁN DE SERVICIO", "CAPITÁN MAESTRO ARMERO", "COMANDANTE", "COMANDANTE DE SERVICIO", "COMANDANTE MAESTRO ARMERO", "CORONEL", "CORONEL DE SERVICIO", "ESPECIALISTA BRIGADIER", "ESPECIALISTA DE PRIMERA", "ESPECIALISTA DE SEGUNDA", "ESPECIALISTA DE TERCERA", "ESPECIALISTA SUPERIOR", "ESPECIALISTA TÉCNICO DE 1RA", "ESPECIALISTA TÉCNICO DE 2DA", "ESPECIALISTA TÉCNICO DE 3RA", "GENERAL", "GENERAL DE SERVICIO", "MAYOR", "MAYOR DE SERVICIO", "MAYOR MAESTRO ARMERO", "SUBOFICIAL BRIGADIER", "SUBOFICIAL BRIGADIER DE SERVICIO", "SUBOFICIAL DE PRIMERA", "SUBOFICIAL DE PRIMERA DE SERVICIO", "SUBOFICIAL DE SEGUNDA", "SUBOFICIAL DE SEGUNDA DE SERVICIO", "SUBOFICIAL DE TERCERA", "SUBOFICIAL DE TERCERA DE SERVICIO", "SUBOFICIAL SUPERIOR", "SUBOFICIAL SUPERIOR DE SERVICIO", "SUBOFICIAL TECNICO DE 1RA", "SUBOFICIAL TECNICO DE 1RA DE SERVICIO", "SUBOFICIAL TECNICO DE 2DA", "SUBOFICIAL TECNICO DE 2DA DE SERVICIO", "SUBOFICIAL TECNICO DE 3RA", "SUBOFICIAL TECNICO DE 3RA DE SERVICIO", "TENIENTE", "TENIENTE DE SERVICIO", "TENIENTE GENERAL", "TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE GESTIÓN DE ALTAS MEDICAS - <?php echo $prestacion->establecimiento ?> </h3>
          </div>

          <div class="panel-body">
            <input type="hidden" name="id_referencia" id="id_referencia" value="<?php echo $emergencia->id_referencia; ?>">
            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->

            <form class="form-horizontal" method="post" id="frmBloque" action="{{ route('registrar_receta_prestacion')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
              <input type="hidden" value="<?php echo $id_prestacion ?>" name="id_prestacion" id="id_prestacion">
              <input type="hidden" value="<?php echo $prestacion->id_cita ?>" name="id_cita" id="id_cita">
              <input type="hidden" value="<?php echo $prestacion->id_asegurado ?>" name="id_asegurado" id="id_asegurado">
              <input type="hidden" value="<?php echo $prestacion->id_establecimiento ?>" name="id_establecimiento" id="id_establecimiento">
              <input type="hidden" value="<?php echo $prestacion->id_sub_consultorio ?>" name="id_sub_consultorio" id="id_sub_consultorio">
              <input type="hidden" value="<?php echo $prestacion->id_medico ?>" name="id_medico" id="id_medico">
              <input type="hidden" value="<?php echo $id_tipo_atencion ?>" name="id_tipo_atencion" id="id_tipo_atencion">
              <input type="hidden" value="<?php echo $prestacion->id_consultorio ?>" name="id_servicio" id="id_servicio">
              <input type="hidden" value="<?php echo $prestacion->sexo ?>" name="nomsexo" id="nomsexo">
              <input type="hidden" value="<?php echo $nivel ?>" name="id_nivel" id="id_nivel">
              <input type="hidden" value="<?php echo $fecha_calculo ?>" name="fecha_calculo">
              <?php
                $flag_firmar = 0;
                if ($flag_pendinte_firma == 1) {
                  $flag_firmar = 1;
                }
              ?>
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>
                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                  </legend>
                  <div>
                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p><?php echo $prestacion->tipo_atencion ?></p>
                      </div>
                      <div class="col-md-2">
                        <label for="dni_beneficiario">FECHA DE ATENCION</label>
                        <p><?php echo $prestacion->fecha_atencion ?></p>
                      </div>
                      <div class="col-md-2">
                        <label>N° HISTORIA CLINICA</label>
                        <p>{{$prestacion->nro_historia}}</p>
                      </div>
                      <div class="col-md-3">
                        <label for="nombre_ipres">PRIORIDAD</label>
                        <p>{{$prestacion->servicio}}</p>
                      </div>
                      <div class="col-md-3">
                        <label for="nombre_ipres">AREA</label>
                        <p>{{$prestacion->consultorio}}</p>
                      </div>
                    </div>
                    @if ($sw == 1)
                      <div class="form-group">
                        <div class="col-md-2">
                          <label>CODIGO UPSS</label>
                          <p>{{$prestacion->codigo_upss}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="tipo">PABELLON</label>
                          <p>{{$datoadicional->pabellon}}</p>
                        </div>
                        <div class="col-md-2">
                          <label for="dni_beneficiario">PISO</label>
                          <p>{{$datoadicional->piso}}</p>
                        </div>
                        <div class="col-md-3">
                          <label>HABITACION</label>
                          <p>{{$datoadicional->cuarto}}</p>
                        </div>
                        <div class="col-md-2">
                          <label>CAMA/CAMILLA</label>
                          <p>{{$datoadicional->cama}}</p>
                        </div>
                      </div>
                    @endif
                  </div>
                </fieldset>
              </div>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->tipo_doc_ident ?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->nro_doc_ident ?></p>
                      </div>
                      <div class="col-md-4">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $prestacion->sexo ?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->asegurado_paterno ?></p>
                      </div>
                      <div class="col-md-4">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->asegurado_materno ?></p>
                      </div>
                      <div class="col-md-4">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->asegurado_nombre ?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $prestacion->fecha_nac ?></p>
                      </div>
                      <div class="col-md-4">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $prestacion->parentesco ?></p>
                      </div>
                      <div class="col-md-4">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $prestacion->grado ?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <?php if ($emergencia->id_referencia != 0): ?>
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>DATOS DE LA REFERENCIA</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="displa" id="tblDiagnostico111" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="10%"></th>
                            <th width="30%">Establecimiento Origen</th>
                            <th width="30%">Servicio Origen</th>
                            <th width="30%">Motivo de Referencia</th>
                          </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                          <tr>
                            <td></td>
                            <td><?php echo $referencias->nombre_establecimiento ?></td>
                            <td><?php echo $referencias->nombre_servicio_origen ?></td>
                            <td><?php echo $referencias->observacion_referencia ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>

                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>DIAGNÓSTICOS DE REFERENCIA</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="displa" id="tblDiagnostico11" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="20%">Codigo</th>
                            <th width="60%">Nombre</th>
                            <th width="20%">Tipo</th>
                          </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                          <?php foreach ($diagnostico_prestacion_referencia as $row):
                            $tipo_diagnostico = "";
                            if ($row->id_tipo_diagnostico == 1) $tipo_diagnostico = "PRESUNTIVO";
                            if ($row->id_tipo_diagnostico == 2) $tipo_diagnostico = "DEFINITIVO";
                            if ($row->id_tipo_diagnostico == 3) $tipo_diagnostico = "REITERATIVO";
                          ?>
                            <tr>
                              <td><?php echo $row->codigo ?></td>
                              <td><?php echo $row->nombre ?></td>
                              <td><?php echo $tipo_diagnostico ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>

                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>PROCEDIMIENTOS DE REFERENCIA</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="display" id="tblProcedimiento1" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="20%">Codigo</th>
                            <th width="60%">Nombre</th>
                            <th width="20%">Observacion</th>
                          </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                          <?php foreach ($procedimientos_refencia as $row): ?>
                            <tr>
                              <td><?php echo $row->codigo ?></td>
                              <td><?php echo $row->descripcion ?></td>
                              <td><?php echo $row->observacion ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>
              <?php endif; ?>

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL RESPONSABLE DEL ALTA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="col-md-3">
                        <label for="tipo">DOCUMENTO DE IDENTIDAD</label>
                        <p>DNI: {{$medico[0]->dni}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p>{{$medico[0]->paterno}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p>{{$medico[0]->materno}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p>{{$medico[0]->nombre}}</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-3">
                        <label>ESPECIALIDAD</label>
                        <p>{{$medico[0]->especializacion}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>PROFESION</label>
                        <p>{{$medico[0]->profesion}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>N° COLEGIATURA</label>
                        <p>{{$medico[0]->colegiatura}}</p>
                      </div>
                      <div class="col-md-3">
                        <label>N° RNE</label>
                        <p>{{$medico[0]->rne}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <!--
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm panel-body">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>CONDICION</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-md-12">
                      <div class="col-md-4">
                        <label for="tipo">IPRESS</label>
                        <div class="col-md-10">
                          <p><?php echo $prestacion->ipress ?></p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label for="tipo">UPSS</label>
                        <div class="col-md-10">
                          <p><?php echo $prestacion->upss ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      @if($prestacion->id_tipo_atencion>1)
                      <div class="col-md-4">
                        <br /><br />
                        <label for="tipo">FECHA INGRESO</label>
                        <div class="col-md-10">
                          <p><?php //echo $prestacion->fecha_ingresos ?></p>
                        </div>
                      </div>
                      @if($prestacion->id_tipo_atencion<4) @if($prestacion->fecha_alta!="")
                        <div class="col-md-4">
                          <br /><br />
                          <label for="tipo">FECHA ALTA</label>
                          <div class="col-md-10">
                            <p><?php echo $prestacion->fecha_alta ?></p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <br /><br />
                          <label for="tipo">MOTIVO ALTA</label>
                          <div class="col-md-10">
                            <p>
                              <?php
                              switch ($prestacion->id_motivo_alta) {
                                case '1':
                                  echo "Mejorado";
                                  break;
                                case '2':
                                  echo "Curado";
                                  break;
                                case '3':
                                  echo "Transferido";
                                  break;
                                case '4':
                                  echo "Voluntario";
                                  break;
                                default:
                                  echo "";
                                  break;
                              }
                              ?>
                            </p>
                          </div>
                        </div>
                        @endif
                        @endif
                        @endif
                    </div>
                  </div>
                </div>
              </fieldset>
			        -->

              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICOS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table class="display" id="tblDiagnostico_anteriores" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="15%">Codigo</th>
                          <th width="50%">Nombre</th>
                          <th width="15%">Tipo</th>
                          <th width="15%">Clasificación</th>
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody>
                        <?php foreach ($diagnostico as $row):
                          $tipo_diagnostico = "";
                          if ($row->id_tipo_diagnostico == 1) $tipo_diagnostico = "PRESUNTIVO";
                          if ($row->id_tipo_diagnostico == 2) $tipo_diagnostico = "DEFINITIVO";
                          if ($row->id_tipo_diagnostico == 3) $tipo_diagnostico = "REITERATIVO";

                          $clase_diagnostico = "";
                          if ($row->id_clasificacion_diagnosticos == 1) {
                            $clase_diagnostico = "Atencion";
                          } else if ($row->id_clasificacion_diagnosticos == 2) {
                            $clase_diagnostico = "Ingreso";
                          } else if ($row->id_clasificacion_diagnosticos == 3) {
                            $clase_diagnostico = "Egreso";
                          } else if ($row->id_clasificacion_diagnosticos == 4) {
                            $clase_diagnostico = "Mortalidad";
                          } else {
                            $clase_diagnostico = "Atencion";
                          }

                        ?>
                          <tr>
                            <td><?php echo $row->codigo ?></td>
                            <td><?php echo $row->nombre ?></td>
                            <td><?php echo $tipo_diagnostico ?></td>
                            <td><?php echo $clase_diagnostico ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </fieldset>
            </form>

            <form class="form-horizontal" method="post" id="frmAltaMedica" action="{{ route('registrar_alta_medica_emer')}}">
              <!-- ALTA MEDICA -->
              <fieldset id="fsAlta">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="tiene_receta" id="tiene_receta" @if(old('tiene_receta') == '1') value='true' @else value='' @endif>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>DIAGNOSTICOS DE EGRESO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="panel panel-info ">
                      <div class="panel-heading">
                        <h3 class="panel-title"><b>DIAGNOSTICOS </b></h3>
                      </div>
                      <div class="panel-body">
                        <table>
                          <tr>
                            <td>
                              <button type="button" id="addDiagnostico" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Diagnostico</button>
                              <button type="button" id="btnCargarUltimoDiagnostico" class="btn btn-info"><i class="fa fa-copy"></i></button>
                            </td>
                          </tr>
                        </table>
                        <br />
                        <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                              <th width="90%"></th>
                              <th width="10%"></th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                            @if (count(old('nombre_diagnostico')) !== 0)
                              @for ($i = 0; $i < count(old('nombre_diagnostico')); $i++)
                                <tr>
                                  <td>
                                    <input type="text" data-toggle="tooltip" data-placement="top" title="" name="nombre_diagnostico[]" tabindex="44" required="" id="{{'nombre_diagnostico'.($i)}}" class="input-sm ui-autocomplete-input" style="margin-left:4px; width:100%; text-align:left" autocomplete="off" value="{{old('nombre_diagnostico.'.$i)}}" @if(trim(old('nombre_diagnostico.'.$i)) == '') @else readonly="" @endif>
                                    <input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="{{'id_diagnostico'.($i)}}" name="id_diagnostico[]" value="{{old('id_diagnostico.'.$i)}}" data-id="{{$i}}">
                                  </td>
                                  <td>
                                    <select tabindex="45" class="form-control id_tipo_diagnostico" id="{{'cmbTipoDiagnostico'.($i+1)}}" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">
                                      <option value="2" selected>DEFINITIVO</option>
                                    </select>
                                  </td>
                                  <td>
                                    <button type="button" tabindex="46" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
                                  </td>
                                </tr>
                                @endfor
                              @endif
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="panel panel-info panel-bordered panel-sm panel-body">
                  <div class="form-group">
                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                      <label>PRESCRIBIR RECETA</label>
                    </div>
                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                      <button tabindex="70" type="button" id="addRecetaVale" class="btn btn-lg btn-toggle"
                        data-toggle="button" aria-pressed="false" autocomplete="off">
                        <div class="handle"></div>
                      </button>
                    </div>
                  </div>
                </div>
                <fieldset id="fsRecetaVale" style="display:none">
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>PRODUCTOS MEDICOS DE EGRESO</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-md-2">
                          <label>FEC. EXPEDICIÓN (A)</label>
                          <input tabindex="71" type="text" class="form-control datepicker"
                            value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                            name="fecha_expedicion" id="fecha_expedicion" tabindex="2"
                            data-date-end-date="0d" autocomplete="off" />
                        </div>
                        <div class="col-md-5">
                          <label>FARMACIA</label>
                          <select tabindex="72" class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                            style="width:100%" onchange="getProducto()">
                            <option value="">-Seleccionar-</option>
                            <?php foreach($farmacias as $row):?>
                            <option value="<?php echo $row->id?>"
                              <?php if($row->id==Auth::user()->id_farmacia) echo "selected='selected'"?>>
                              <?php echo $row->nombre?></option>
                              @if($nivel==3)
                              <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                              <option value="132">Farmacia de Preparados Farmacéutico No Estériles del Hospital Nacional PNP Luis N. Saenz</option>
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
                            <option value="<?php echo $row->id?>">
                              <?php echo $row->nombre?></option>
                            <?php endforeach;?>
                        </select>
                      </div>
                      <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                      <input type="hidden" value="0" name="presiono_receta" id="presiono_receta">

                      <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th>Producto farmacéutico o dispositivo médico</th>
                            <!--th style="width:10%">Stock UE-020</th>
                            <th style="width:10%">Stock Establecimiento</th-->
                            <th style="width:10%">Stock Farmacia</th>
                            <th>Tiempo(Dias)</th>
                            <th> Via Adm. </th>
                            <th>Posologia</th>
                            <th> Cant. Pres.</th>
                            <th> DiagnosticoxProducto </th>
                            <th>  </th>
                            <th>  </th>
                          </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                        </tbody>
                      </table>
                    </div> 
                  </div>
                </fieldset>

                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>ALTA DE PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <input type="hidden" value="<?php echo $prestacion->id_medico ?>" name="id_medico_egreso" id="id_medico_egreso">
                    <input type="hidden" value="<?php echo $id_prestacion ?>" name="id_prestacionx" id="id_prestacionx">
                    <input type="hidden" value="<?php echo $prestacion->id_ext; ?>" name="id_emergencia" id="id_emergencia">
                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                    <input type="hidden" name="codigo_siteds_backup" id="codigo_siteds_backup">
                    <div style="clear:both"></div>
                    <fieldset id="divAlta2">
                      <div class="row">
                        <div class="col-md-2 col-sm-6">
                          <label for="" class="control-label">FECHA ULTIMA ATENCIÓN</label>
                        </div>
                        <div class="col-md-2 col-sm-6">
                          <input type="text" class="form-control" value="{{ $fecha_ultima_atencion }}" readonly>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <!-- <br> -->
                        <div class="col-md-2 col-sm-3">
                          <label for="fecha_egreso" class="control-label">FECHA EGRESO</label>
                        </div>
                        <div class="col-md-2 col-sm-3">
                          <input type="text"  data-date-start-date="-<?php echo ($dia_estancia + 1) ?>d" data-date-end-date="0d"
                          class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($fechaEgreso)); ?>"
                          name="fecha_egreso" id="fecha_egreso" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?> />
                        </div>

                        <div class="col-md-1 col-sm-3">
                          <label for="hora_egreso" class="control-label">HORA EGRESO</label>
                        </div>
                        <div class="col-md-1 col-sm-3">
                          <input type="text" class="form-control" value="<?php echo date("G:i:s", strtotime($horaEgreso)); ?>" name="hora_egreso" id="hora_egreso" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?> />
                        </div>

                        <div class="col-md-1 col-sm-3">
                          <label for="id_tipo_egreso" class="control-label">TIPO EGRESO</label>
                        </div>
                        <div class="col-md-2 col-sm-3">
                          <select class="form-control input-sm" id="id_tipo_egreso" name="id_tipo_egreso" style="width: 100%" onchange="cambiar_tipo_egreso()" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                            <option value="">Elegir</option>
                            <option @if(old('id_tipo_egreso') == '1') selected @endif value="1" <?php if ($emergencia->id_tipo_egreso == "1") { ?>selected <?php } ?>>DOMICILIARIA</option>
                            <option @if(old('id_tipo_egreso') == '2') selected @endif value="2" <?php if ($emergencia->id_tipo_egreso == "2") { ?>selected <?php } ?>>HOSPITALIZACIÓN</option>
                            <option @if(old('id_tipo_egreso') == '3') selected @endif value="3" <?php if ($emergencia->id_tipo_egreso == "3") { ?>selected <?php } ?>>TRANSFERIDO</option>
                            <option @if(old('id_tipo_egreso') == '6') selected @endif value="6" <?php if ($emergencia->id_tipo_egreso == "6") { ?>selected <?php } ?>>FUGA</option>
                            <option @if(old('id_tipo_egreso') == '4') selected @endif value="4" <?php if ($emergencia->id_tipo_egreso == "4") { ?>selected <?php } ?>>FALLECIDO</option>
                            <option @if(old('id_tipo_egreso') == '7') selected @endif value="7" <?php if ($emergencia->id_tipo_egreso == "7") { ?>selected <?php } ?>>RETIRO VOLUNTARIO</option>
                          </select>
                        </div>

                        <div id="alta_condicion" style="display:none">
                          <div class="col-md-1 col-sm-3">
                            <label for="id_condicion_alta" class="control-label">CONDICION ALTA</label>
                          </div>
                          <div class="col-md-2 col-sm-3">
                            <select class="form-control input-sm" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                              <option value="">Elegir</option>
                              <option value="1" <?php if ($emergencia->id_condicion_alta == "1") { ?>selected <?php } ?>>MEJORADO</option>
                              <option value="2" <?php if ($emergencia->id_condicion_alta == "2") { ?>selected <?php } ?>>CURADO</option>
                            </select>
                          </div>
                        </div>

                        <div id="alta_condicion_de" style="display:none">
                          <div class="col-md-1 col-sm-3">
                            <label class="control-label">CONDICION ALTA</label>
                          </div>
                          <div class="col-md-2 col-sm-3">
                            <select class="form-control input-sm" id="" name="" style="width: 100%" disabled>
                              <option>FALLECIDO</option>
                            </select>
                          </div>
                        </div>

                        <div id="alta_condicion_ret" style="display:none">
                          <div class="col-md-1 col-sm-3">
                            <label class="control-label">CONDICION ALTA</label>
                          </div>
                          <div class="col-md-2 col-sm-3">
                            <select class="form-control input-sm" id="" name="" style="width: 100%" disabled>
                              <option>RETIRO VOLUNTARIO</option>
                            </select>
                          </div>
                        </div>

                        <div id="alta_condicion_fuga" style="display:none">
                          <div class="col-md-1 col-sm-3">
                            <label class="control-label">CONDICION ALTA</label>
                          </div>
                          <div class="col-md-2 col-sm-3">
                            <select class="form-control input-sm" id="" name="" style="width: 100%" disabled>
                              <option>FUGA</option>
                            </select>
                          </div>
                        </div>

                        <div id="alta_condicion_tra" style="display:none">
                          <div class="col-md-1 col-sm-3">
                            <label class="control-label">CONDICION ALTA</label>
                          </div>
                          <div class="col-md-2 col-sm-3">
                            <select class="form-control input-sm" id="" name="" style="width: 100%" disabled>
                              <option>TRASLADO</option>
                            </select>
                          </div>
                        </div>
                      </div> <!-- .row -->
                      <br />

                      <!-- El DIV (egreso_alta) no se muestra en ninguna condicional, solo cuando el tipo de egreso ya esta registrado como si fuera la opcion de VER -->
                      <div id="egreso_alta" style="display:none">
                        <div class="col-md-1">
                          <label class="control-label">DERIVAR CONSULTA</label>
                        </div>
                        <div class="col-md-1">
                          <select class="form-control input-sm" id="derivar" name="derivar" style="width: 100%" onchange="cambiar_derivacion()" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                            <option value="NO" <?php if ($emergencia->derivar == "NO") { ?>selected <?php } ?>>NO</option>
                            <option value="SI" <?php if ($emergencia->derivar == "SI") { ?>selected <?php } ?>>SI</option>
                          </select>
                        </div>

                        <div class="col-md-1">
                          <label>SERVICIO</label>
                        </div>
                        <div class="col-md-3">
                          <select class="form-control input-sm" id="id_servicio_consulta_externa" name="id_servicio_consulta_externa" style="width: 100%" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                            <option value="">Elegir</option>
                            <?php 
                              if ($consulta_externa != NULL && $emergencia->derivar == "SI") {
                                foreach ($consulta_externa as $row):
                            ?>
                                  <option value="<?php echo $row->id ?>" <?php if ($row->id == $emergencia->id_servicio_destino) { ?>selected <?php } ?>><?php echo $row->nombre ?></option>
                            <?php 
                                endforeach;
                              } 
                            ?>
                          </select>
                        </div>
                      </div>

                      <div id="egreso_retiro" style="display:none">
                        <div class="row">
                          <div class="col-md-2">
                            <label for="obs_retiro" class="control-label">OBSERVACION DE RETIRO VOLUNTARIO</label>
                          </div>
                          <div class="col-md-4">
                            <input type="text" class="form-control" value="{{ $emergencia->obs_retiro}}" name="obs_retiro" id="obs_retiro" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                          </div>
                        </div>
                      </div>

                      <div id="egreso_fuga" style="display:none">
                        <div class="row">
                          <div class="col-md-2">
                            <label for="obs_fuga" class="control-label">DETALLE DE FUGA</label>
                          </div>
                          <div class="col-md-4">
                            <input type="text" class="form-control" value="{{ $emergencia->fuga}}" name="obs_fuga" id="obs_fuga" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                          </div>
                        </div>
                      </div>

                      <div id="egreso_traslado" style="display:none">
                        <div class="row">
                          <div class="col-md-2">
                            <label for="id_establecimiento_traslado" class="control-label">IPRESS DE TRASLADO</label>
                          </div>
                          <div class="col-md-4">
                            <select class="form-control input-sm" id="id_establecimiento_traslado" name="id_establecimiento_traslado" style="width: 100%" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                              <option value="">Elegir</option>
                              <?php 
                                if ($estable != NULL) {
                                  foreach ($estable as $dato):
                              ?>
                                    <option value="<?php echo $dato->id ?>" <?php if (isset($referencias->nombre_establecimiento)) { if ($dato->nombre == $referencias->nombre_establecimiento) { ?> selected <?php } } else { } ?>><?php echo $dato->nombre ?></option>
                              <?php 
                                  endforeach;
                                } 
                              ?>
                            </select>
                          </div>
                          <div class="col-md-2">
                            <label for="obs_traslado" class="control-label">COMENTARIOS DE TRASLADO</label>
                          </div>
                          <div class="col-md-4">
                            <input type="text" class="form-control" value="{{ $emergencia->obs_traslado}}" name="obs_traslado" id="obs_traslado" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                          </div>
                        </div>
                        <br />

                        <div class="row">
                          <div class="col-md-2">
                            <label for="especialida_destino" class="control-label">ESPECIALIDAD DE DESTINO</label>
                          </div>
                          <div class="col-md-3">

                            <?php if (isset($referencias->nombre_servicio_origen)) : ?>
                              <select class="form-control input-sm" id="especialida_destino" name="especialida_destino" style="width: 100%">
                                <option value="">Elegir</option>
                                <?php 
                                  if ($servicio_referen != NULL) {
                                    foreach ($servicio_referen as $dato):
                                ?>
                                    <option value="<?php echo $dato->id ?>" <?php if (isset($referencias->nombre_servicio_origen)) { if ($dato->nombre == $referencias->nombre_servicio_origen) { ?> selected <?php } } else {} ?>><?php echo $dato->nombre ?></option>
                                <?php 
                                    endforeach;
                                  }
                                ?>
                              </select>
                            <?php else: ?>
                              <select class="form-control input-sm" id="especialida_destino" name="especialida_destino" style="width: 100%">
                                <option value="">Elegir</option>
                                <option value="PEDIATRIA">PEDIATRIA</option>
                                <option value="MEDICINA">MEDICINA</option>
                                <option value="CIRUJIA">CIRUJIA</option>
                                <option value="GINECO-OBST">GINECO-OBST</option>
                                <option value="LABORATORIO">LABORATORIO</option>
                                <option value="Dx-IMAG">Dx IMAG.</option>
                                <option value="OTROS">OTROS</option>
                              </select>
                            <?php endif; ?>

                          </div>
                          <div class="col-md-1">
                            <label for="especialidad_destino_otro" class="control-label">DETALLAR</label>
                          </div>
                          <div class="col-md-4">
                            <input type="text" class="form-control" value="" name="especialidad_destino_otro" id="especialidad_destino_otro" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                          </div>
                        </div>
                        <br />

                        <div class="row">
                          <div class="col-md-3">
                            <label for="condicion_traslado" class="control-label">CONDICION DEL PACIENTE A LA HORA DEL TRASLADO</label>
                          </div>
                          <div class="col-md-2">
                            <select class="form-control input-sm" id="condicion_traslado" name="condicion_traslado" style="width: 100%">
                              <option value="">Elegir</option>
                              <option value="ESTABLE">ESTABLE</option>
                              <option value="MAL ESTADO">MAL ESTADO</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div id="egreso_defuncion" style="display:none">
                        <div class="row">
                          <div class="col-md-1">
                            <label for="necropsia" class="control-label">NECROPSIA</label>
                          </div>
                          <div class="col-md-1">
                            <select class="form-control input-sm" id="necropsia" name="necropsia" style="width: 100%" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>

                          <div class="col-md-2">
                            <label for="causas_muerte" class="control-label">CAUSAS DE MUERTE</label>
                          </div>
                          <div class="col-md-6">
                            <input type="text" class="form-control" value="{{ $emergencia->causas_muerte }}" name="causas_muerte" id="causas_muerte" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                          </div>
                        </div>
                        <br />

                        <div class="row">
                          <div class="col-md-2"> </div>
                          <div class="col-md-2">
                            <label for="fecha_defuncion" class="control-label">FECHA FALLECIMIENTO</label>
                          </div>
                          <div class="col-md-2">
                            <input type="text" data-date-start-date="-<?php echo ($dia_estancia + 1) ?>d" data-date-end-date="0d" class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($fechaEgreso)); ?>" name="fecha_defuncion" id="fecha_defuncion" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?> />
                          </div>
                          <div class="col-md-1">
                            <label for="hora_defuncion" class="control-label">HORA FALLECIMIENTO</label>
                          </div>
                          <div class="col-md-2">
                            <input type="text" class="form-control" value="<?php echo date("G:i:s", strtotime($horaEgreso)); ?>" name="hora_defuncion" id="hora_defuncion" style="text-align:left" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?> />
                          </div>
                        </div>
                      </div>

                      <div id="egreso_hospitalizacion" style="display:none">
                        <div class="row">
                          <div class="col-md-2 col-sm-6">
                            <label for="id_servicio_hospitalizacion" class="control-label">ESPECIALIDAD A CARGO</label>
                          </div>
                          <div class="col-md-5 col-sm-6">
                            <select class="form-control input-sm" id="id_servicio_hospitalizacion" name="id_servicio_hospitalizacion" style="width: 100%" <?php if ($emergencia->fecha_alta_medica != ''): echo 'disabled'; endif; ?>>
                              <option value="">Elegir</option>
                              
                            </select>
                          </div>
                        </div>

                        @if (intval($id_establecimiento) === 76)
                          <br />
                          <div class="row">
                            <div class="col-md-2">
                              <label class="control-label">UBICACION DEL PACIENTE</label>
                            </div>
                            <div class="col-md-1">
                              <label for="id_edificio_ubicacion" class="control-label">PABELLON</label>
                            </div>
                            <div class="col-md-1">
                              <select name="id_edificio_ubicacion" id="id_edificio_ubicacion" class="form-control" style="width:100%">
                                <option value="">-Seleccione-</option>
                                <option value="1">HISTORICO</option>
                                <option value="2">PIP</option>
                              </select>
                            </div>
                            <div class="col-md-3">
                              {{-- <select name="id_upss_ubicacion" id="id_upss_ubicacion" class="form-control" style="width:100%">
                              <option value="">-Seleccione-</option>
                                @foreach ($upss as $row)
                                  <option value="{{ $row->id_upss }}" data-id_servicio="{{ $row->id_consultorio }}">{{ $row->nombre }}</option>
                                @endforeach
                              </select> --}}
                              <select name="id_pabellon_ubicacion" id="id_pabellon_ubicacion" class="form-control" style="width:100%">
                                <option value="">-Seleccione-</option>
                                @foreach ($pabellones as $pa)
                                  <option value="{{ $pa->id }}">{{ $pa->pabellon }}</option>
                                @endforeach
                              </select>
                            </div>
                            {{-- <div class="col-md-1">
                              <label for="id_cama_ubicacion" class="control-label" >CAMA</label>
                            </div>
                            <div class="col-md-2"> <!-- Aislado, Complejo, Quirúrgica -->
                              <select name="id_cama_ubicacion" id="id_cama_ubicacion" class="form-control"></select>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label for="personal_coordinacion" class="control-label">COORDINACIÓN</label>
                                <input type="text" class="form-control" name="personal_coordinacion" id="personal_coordinacion" readonly />
                              </div>
                            </div> --}}
                          </div>
                        @endif

                        <br />
                        <div class="col-md-12">
                          <div id="centro_quirurgico" style="display:none">
                            <div class="form-group">
                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                <label>REQUIERE INTERNAMIENTO QX </label>
                              </div>
                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                <button tabindex="100" type="button" id="addRequiereInternamientoQx" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                  <div class="handle"></div>
                                </button>
                                <input type="hidden" id="requiere_intervencion_quirurgica" name="requiere_intervencion_quirurgica" value="0">
                                <input type="hidden" id="pendiente_alta_emergencia_interconsulta_qx" name="pendiente_alta_emergencia_interconsulta_qx" value="0">
                                <input type="hidden" id="id_intervencion" name="id_intervencion" value="0">
                                
                              </div>
                              <br>
                            </div>
                            <div class="form-group" id="centro_quirurgico_no_pendiente_alta" style="display:none">
                              <div class="col-md-12">
                                <div class="panel panel-info panel-sm" id="operacion_propuesta1" style="display:none">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center"><b>REGISTRAR INFORMACIÓN QUIRÚRGICA</b></div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-2">
                                        <label>TIPO DE CIRUGIA</label>
                                        <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control">
                                          <option value="0">Seleccione</option>
                                          @foreach ($tipo_cirugias as $row)
                                            <option value="{{ $row->id }}" @if($row->id==3) selected @endif>{{ $row->descripcion }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div id="electiva" style="display:none;">
                                        <div class="col-md-2">
                                          <label for="">HORA DE INICIO:</label>
                                          <input type="time" class="form-control input-sm" name="hora_inicio_intervencion" id="hora_inicio_intervencion">
                                        </div>
                                        <div class="col-md-2">
                                          <label for="">HORA DE FIN:</label>
                                          <input type="time" class="form-control input-sm" name="hora_fin_intervencion" id="hora_fin_intervencion">
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <label>TIEMPO DE DURACIÓN</label>
                                        <input type="hidden" id="duracion_cirugia" name="duracion_cirugia" class="form-control">
                                        <input type="time" id="duracion_cirugia_calculada" class="form-control" style="width: 105px; display: none;">
                                        <div id="hora_minuto">
                                          <div class="row" style="align-items: center;">
                                            <div class="col-md-3 d-flex align-items-center" style="display: flex; align-items: center;">
                                              <label for="horas" style="margin-right: 5px; white-space: nowrap;">Hora:</label>
                                              <input type="number" id="horas" class="form-control" min="0" max="24" placeholder="0" style="width:80px;">
                                            </div>
                                            <div class="col-md-3 d-flex align-items-center" style="display: flex; align-items: center;">
                                              <label for="minutos" style="margin-right: 5px; white-space: nowrap;">Minuto:</label>
                                              <input type="number" id="minutos" class="form-control" min="0" max="59" placeholder="0" style="width:80px;">
                                            </div>
                                          </div>
                                        </div>
                                        <!-- <input type="text" class="form-control" name="duracion_cirugia" id="duracion_cirugia" class="form-control" placeholder="" style="width: 105px;" oninput="filtrarNumeroDecimal(this)">                                     -->
                                      </div>
                                    </div>
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>DIAGNOSTICO QUIRURGICO</b>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <table>
                                          <tr>
                                            <td>
                                              <button type="button" id="addDiagnosticoQuirurgico" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Diagnostico</button>
                                            </td>
                                          </tr>
                                        </table>
                                        <br />
                                        <table class="display" id="tblDiagnosticoQuirurgico" cellspacing="0" width="100%" style="margin-top:5px;">
                                          <thead>
                                            <tr>
                                              <th width="50%">Nombre</th>
                                              <th width="15%">Tipo</th>
                                            </tr>
                                          </thead>
                                          <tfoot>
                                          </tfoot>
                                          <tbody>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>

                                    <div class="panel panel-info panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center"><b>OPERACION , PROCEDIMIENTO A REALIZAR</b></div>
                                      </div>
                                      <div class="panel-body">
                                        <!--  -->
                                        <div class="form-group">
                                          <div class="col-sm-2">
                                            <button tabindex="75" type="button" id="addProcedimientoIntervencion" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Procedimiento</button>
                                          </div>
                                        </div>
                                        <div class="form-group" style="display:none">
                                          <select id="cmbProcedimientoIntervencionTemp"></select>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-sm-12">
                                            <table id="table_procedimiento_intervencion" class="display" cellspacing="0" width="100%">
                                              <thead>
                                                <tr>
                                                  <th>Código y Descripción</th>
                                                  <th style="width:10%;padding-left:30px;">Eliminar</th>
                                                </tr>
                                              </thead>
                                              <tfoot>
                                              </tfoot>
                                              <tbody>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                        <!--  -->
                                      </div>
                                    </div>

                                    <div class="panel panel-info panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center"><b>CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</b></div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-sm-2">
                                            <button tabindex="75" type="button" id="addMedicoCirujano" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Cirujano que intervienen en la operación</button>
                                          </div>
                                        </div>
                                        <div class="form-group" style="display:none">
                                          <select id="cmbProfesionalesTemp"></select>
                                          <select id="cmbTipoProfesionalTemp">
                                            <option value="0">--Seleccionar--</option>
                                            @foreach ($cqx_tipo_profesionales as $tp)
                                              @if ($tp->flag_profesional == 1)
                                                <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                              @endif
                                            @endforeach
                                          </select>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-sm-12">
                                            <table id="table_medico_cirujano" class="display" cellspacing="0" width="100%">
                                              <thead>
                                                <tr>
                                                  <th>Apellidos y Nombres</th>
                                                  <th style="width:30%;padding-left:20px;">Cargo</th>
                                                  <th style="width:10%;padding-left:30px;">Eliminar</th>
                                                </tr>
                                              </thead>
                                              <tfoot>
                                              </tfoot>
                                              <tbody>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                @if ($emergencia->id_tipo_beneficiario == '12')
                                  <div class="form-group" id="numeroboleta">
                                    <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                      <label>NUMERO DE BOLETA</label>
                                    </div>
                                    <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                      <input type="text" class="form-control" value="" name="numeroboleta" id="" style="text-align:left" <?php if ($emergencia->id_tipo_beneficiario != '12'): echo 'disabled'; endif; ?>>
                                    </div>
                                    <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                      <label>NUMERO DE OFICIAL DE GUARDIA</label>
                                    </div>
                                    <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                      <input type="text" class="form-control" value="" name="numeroguar" id="" style="text-align:left" <?php if ($emergencia->id_tipo_beneficiario != '12'): echo 'disabled'; endif; ?>>
                                    </div>
                                  </div>
                                @endif
                              </div>
                            </div>
                            <div class="form-group" id="centro_quirurgico_pendiente_alta_qx" style="display:none">
                                <div class="col-md-12" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                  <div class="alert alert-warning" role="alert" style="font-weight: bold;">
                                      <i class="fa fa-exclamation-triangle"></i>
                                      <strong>ATENCIÓN:</strong>  
                                      Debe <u>finalizar la alta de emergencia</u> para que se habilite la opción
                                      <span class="text-danger">“Atender Registro de Atención Quirúrgica”</span>.
                                  </div>
                                </div>
                                <div class="col-md-12">
                                  <div class="panel panel-info panel-sm" >
                                    <div class="panel-heading">
                                      <div class="panel-title text-center"><b>INFORMACIÓN QUIRÚRGICA</b></div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="panel-body">

                                        <div class="form-group">
                                          <div class="col-sm-2">
                                            <label for="">TURNOS QUIRURGICOS</label>
                                          </div>
                                          <div class="col-sm-1">
                                            <input type="number" id="turno" class="form-control" style="width: 105px;"  disabled>
                                          </div>

                                          <div class="col-md-2">
                                              <label for="">CLASIFICACION DECIRUGIA</label>
                                          </div>
                                          <div class="col-md-2">
                                              <input type="text" id="clasificacion_cirugia" class="form-control" style="width: 350px;"  readonly>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <label>TIPO DE CIRUGÍA</label>
                                                <input type="text" id="tipo_dirugia" class="form-control" style="width: 250px;"  readonly>                                     
                                            </div>
                                            <div id="electiva" style="display:none;">
                                              <div id="fecha_programacion" style="display:none">
                                                <div class="col-md-2">
                                                  <label for="">FECHA DE PROGRAMACIÓN:</label>
                                                  <input type="text" id="fecha_probable_pq" name="fecha_probable_pq" class="form-control"  readonly style="background-color: #ffffff" disabled>
                                                </div>
                                              </div>

                                              <div class="col-md-2">
                                                <label for="">HORA DE INICIO:</label>
                                                <input type="time" class="form-control input-sm" name="hora_inicio_intervencion" id="hora_inicio_intervencion" disabled>
                                              </div>
                                              <div class="col-md-2">
                                                <label for="">HORA DE FIN:</label>
                                                <input type="time" class="form-control input-sm" name="hora_fin_intervencion" id="hora_fin_intervencion" disabled>
                                              </div>
                                            </div>       
                                            <div class="col-md-4">
                                                <label>TIEMPO DE DURACIÓN</label>
                                                
                                                <div id="hora_minuto">
                                                    <div class="row" style="align-items: center;">
                                                      <div class="col-md-3 d-flex align-items-center" style="display: flex; align-items: center;">
                                                        <input type="text" id="duracion" class="form-control" min="0" max="24" placeholder="0" style="width:80px;" readonly>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="panel panel-info panel-sm">
                                            <div class="panel-heading">
                                                <div class="panel-title text-center"><b>DIAGNOSTICOS</b></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <table id="table_diagnostico_intervencion_alta" cellspacing="0" width="100%" style="width: 100%;">
                                                          <thead>
                                                              <tr>
                                                                  <th width="50%">NOMBRE</th>
                                                                  <th width="15%">TIPO</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                                              
                                                          </tbody>
                                                      </table>
                                                    </div>
                                                </div>
                                            </div>
                                      </div>

                                      <div class="panel panel-info panel-sm">
                                            <div class="panel-heading">
                                                <div class="panel-title text-center"><b>PROCEDIMIENTOS DE INTERVENCIÓN</b></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <table id="table_procedimiento_intervencion_alta"  cellspacing="0" width="100%">
                                                          <thead>
                                                              <tr>
                                                              <th width="70%">DESCRIPCION</th>
                                                          </thead>
                                                          <tfoot>
                                                          </tfoot>
                                                          <tbody>
                                                            
                                                          </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                      </div>

                                       <div class="panel panel-info panel-sm">
                                            <div class="panel-heading">
                                                <div class="panel-title text-center"><b>PROFESIONALES QUE INTERVIENEN</b></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <table id="table_cirujanos_intervencion_alta"  cellspacing="0" width="100%">
                                                          <thead>
                                                              <tr>
                                                              <th width="50%">APELLIDOS Y NOMBRES</th>
                                                              <th width="15%">CARGO</th>
                                                          </thead>
                                                          <tfoot>
                                                          </tfoot>
                                                          <tbody>
                                                            
                                                          </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                      </div>

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

              </fieldset>

              @if($emergencia->fecha_alta_medica=='' or $emergencia->fecha_alta_medica=='null')
                <div id="divFirmar">
                  <input type="submit" class="btn btn-info input-sm pull-right" value="GUARDAR" name="crea" form="prestacionescrea" id="btnPrestacion" style="margin-left:20px" />
                </div>
              @else
                <a class="btn btn-primary btn-sm hidden-print pull-right" href="/all_atenciones_by_emergencia"> <span class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
              @endif
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- </div>
</div> -->

<input type="hidden" id="argumentos" value="" />
<input type="hidden" id="doc_firmar" value="" />
<input type="hidden" value="<?php echo $flag_firmar ?>" name="flag_firmar" id="flag_firmar">
<div id="addComponent"></div>

@include('frontend.includes.footer')
@endsection