@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
<script src='/funciones/function.js'></script>
<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<style>
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
  /*.modal-dialog {
    width: 90%;
  }*/
  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }
  .table-row {
    cursor: pointer;
    transition: background-color 0.3s  !important;
  }

  .table-row:hover {
    background-color: lightgreen !important;
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
  .glyphicon {
      font-family: 'Glyphicons Halflings';
      font-style: normal;
      font-weight: normal;
      line-height: 1;
      position: relative;
      top: 1px;
      display: inline-block;
      font-size: inherit;
      text-rendering: auto;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
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
  /*===============TABLA IMAGENOLOGÍA RESPONSIVE INICIO=====================================*/
  #tblExamenRad {
      width: 100%;
      table-layout: auto; /* Permite que las columnas se ajusten al contenido */
      border-collapse: collapse; /* Une bordes para mejor presentación */
  }

#tblExamenRad th,
#tblExamenRad td {
    white-space: nowrap; /* Evita división en varias líneas */
    text-align: center; /* Centra el contenido */
    padding: 8px; /* Añade espacio*/
}

#tblExamenRad thead th {
    vertical-align: middle; /* los encabezados se alinean correctamente */
}
@media (max-width: 1207px) {
    .panel-body {
        overflow-x: auto;
        display: block;
    }
}
/*===============TABLA IMAGENOLOGÍA RESPONSIVE FIN=====================================*/
#RUALab2 {
  color: #FFFFFF;
  background-color: #6EBA4A;
  border-color: #6EBA4A;
  font-weight: bold;
  transition: background-color 0.3s, border-color 0.3s;
}

#RUALab2:hover {
  color: #FFFFFF;
  font-weight: bold;
  background-color: #4C941C;
  border-color: #4C941C;
}

.modal-col1 .modal-dialog {
    max-width: 450px !important;
}

.modal-col2 .modal-dialog {
    max-width: 950px !important;
}

.modal-col3 .modal-dialog {
    max-width: 1200px !important;
}

.modal-col4 .modal-dialog {
    max-width: 1400px !important;
}

#contenedorDX.col1 { grid-template-columns: 350px; }
#contenedorDX.col2 { grid-template-columns: repeat(2, 350px); }
#contenedorDX.col3 { grid-template-columns: repeat(3, 320px); }
#contenedorDX.col4 { grid-template-columns: repeat(4, 300px); }

.modal-col5 .modal-dialog {
    max-width: 1400px !important;
}

#contenedorDX {
    display: grid;
    justify-content: center;
    gap: 10px;

    max-height: calc((50px + 10px) * 10);
    overflow-y: auto;
    padding-right: 10px;
}

#contenedorDX label {
    display: flex;
    align-items: center;
}

#contenedorDX.col5 {
    grid-template-columns: repeat(4, 250px);
}

/* Global*/

    /* =========================
    PADDING UTILITIES
    ========================= */

    /* Padding general */
    .p-0 { padding: 0 !important; }
    .p-1 { padding: .25rem !important; }
    .p-2 { padding: .5rem !important; }
    .p-3 { padding: 1rem !important; }
    .p-4 { padding: 1.5rem !important; }
    .p-5 { padding: 3rem !important; }

    /* Padding top */
    .pt-0 { padding-top: 0 !important; }
    .pt-1 { padding-top: .25rem !important; }
    .pt-2 { padding-top: .5rem !important; }
    .pt-3 { padding-top: 1rem !important; }
    .pt-4 { padding-top: 1.5rem !important; }
    .pt-5 { padding-top: 3rem !important; }

    /* Padding bottom */
    .pb-0 { padding-bottom: 0 !important; }
    .pb-1 { padding-bottom: .25rem !important; }
    .pb-2 { padding-bottom: .5rem !important; }
    .pb-3 { padding-bottom: 1rem !important; }
    .pb-4 { padding-bottom: 1.5rem !important; }
    .pb-5 { padding-bottom: 3rem !important; }

    /* Padding left */
    .pl-0 { padding-left: 0 !important; }
    .pl-1 { padding-left: .25rem !important; }
    .pl-2 { padding-left: .5rem !important; }
    .pl-3 { padding-left: 1rem !important; }
    .pl-4 { padding-left: 1.5rem !important; }
    .pl-5 { padding-left: 3rem !important; }

    /* Padding right */
    .pr-0 { padding-right: 0 !important; }
    .pr-1 { padding-right: .25rem !important; }
    .pr-2 { padding-right: .5rem !important; }
    .pr-3 { padding-right: 1rem !important; }
    .pr-4 { padding-right: 1.5rem !important; }
    .pr-5 { padding-right: 3rem !important; }

    /* Padding horizontal (left + right) */
    .px-0 { padding-left: 0 !important; padding-right: 0 !important; }
    .px-1 { padding-left: .25rem !important; padding-right: .25rem !important; }
    .px-2 { padding-left: .5rem !important; padding-right: .5rem !important; }
    .px-3 { padding-left: 1rem !important; padding-right: 1rem !important; }
    .px-4 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
    .px-5 { padding-left: 3rem !important; padding-right: 3rem !important; }

    /* Padding vertical (top + bottom) */
    .py-0 { padding-top: 0 !important; padding-bottom: 0 !important; }
    .py-1 { padding-top: .25rem !important; padding-bottom: .25rem !important; }
    .py-2 { padding-top: .5rem !important; padding-bottom: .5rem !important; }
    .py-3 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
    .py-4 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
    .py-5 { padding-top: 3rem !important; padding-bottom: 3rem !important; }



    .m-0    { margin: 0!important;  }
    .fw-800    { font-weight: 800; }


    .w-25   { width: 25% !important; }
    .w-50   { width: 50% !important; }
    .w-75   { width: 75% !important; }
    .w-100  { width: 100% !important; }
    .w-auto { width: auto !important; }

    .h-25   { height: 25% !important; }
    .h-50   { height: 50% !important; }
    .h-75   { height: 75% !important; }
    .h-100  { height: 100% !important; }
    .h-auto { height: auto !important; }

    /* Display*/
    /* SM ≥576px */
    .d-none{display: none!important;}
</style>

<script>

const decimalRegex = /^\d+(\.\d+)?$/;
const DIA_SEMANA_DOMINGO = 0;

var fechaHoraCompleto = new Date('{{$cita->created_at}}');
var procedimientosSeleccionados =[];
  var listIdDiagnostico =[];
  var listIdDiagnosticoObjetos =[];

 var filasmedicamentos = 0;
let showValidacionCantInterconsulta = false;

  // "is_estable_auxiliar" se usa para guardar el valor actual antes de su cambio
  let is_estable_auxiliar = null;

function pad(num) {
    return num < 10 ? '0' + num : num;
}

// Extraer la fecha en formato "día-mes-año"
var dia = pad(fechaHoraCompleto.getDate());
var mes = pad(fechaHoraCompleto.getMonth() + 1); // Los meses en JavaScript empiezan desde 0, así que añadimos 1
var anio = fechaHoraCompleto.getFullYear();
var fecha = `${dia}-${mes}-${anio}`;

// Extraer la hora en formato "hora:minutos" (24 horas)
var horas = pad(fechaHoraCompleto.getHours());
var minutos = pad(fechaHoraCompleto.getMinutes());
var hora = `${horas}:${minutos}`;

// Mostrar la fecha y la hora en la consola (puedes eliminar estas líneas si no las necesitas)

// Mostrar la fecha y la hora en la consola (puedes eliminar estas líneas si no las necesitas)

	$("#div_uci_ventilador").hide();
	function getVentiladorUci(){
	    var uci = $('#id_uci').val();
	    if(uci=='1'){
	        $("#div_uci_ventilador").show();
	    }
	    else{
	        $("#div_uci_ventilador").hide();
	    }
	}
	function getAmbulancia(){
	    var ambulancia = $('#id_ambulancia').val();
	    if(ambulancia=='1'){
	        $("#div_ambulancia").show();
	    }
	    else{
	        $("#div_ambulancia").hide();
	    }
	}


  $(document).ready(function() {

    let minDate = new Date({{date('Y', strtotime($hospitalizacion->fecha_ingreso))}}, {{date('m', strtotime($hospitalizacion->fecha_ingreso))}} - 1, {{date('d', strtotime($hospitalizacion->fecha_ingreso))}});
    $("#fecha_atencion").datepicker({
      useCurrent: false,
      dateFormat: "dd-mm-yy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left",
      maxDate: 0,
      minDate: minDate
    });

    $('#datepicker2').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            compruebaIMCInicio();

    function calcularEdad(fechaNacimiento) {
        const [dia, mes, año] = fechaNacimiento.split('/').map(Number);
        const fechaJS = new Date(año, mes - 1, dia); // Meses en JS empiezan desde 0
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaJS.getFullYear();
        const mesActual = hoy.getMonth();
        const diaActual = hoy.getDate();
        if (mesActual < fechaJS.getMonth() || (mesActual === fechaJS.getMonth() && diaActual < fechaJS.getDate())) {
            edad--;
        }
        return edad;
    }

    $('#btnPrestacion').on("click", function (e) {
      Swal.fire({
        icon: 'info',
        title: 'IMPORTANTE',
        html: 'Tiene un plazo de <b>1 hora</b> para completar la historia clínica. Una vez vencido este plazo, el sistema bloqueará la continuidad de la atención hasta que la historia clínica sea completada.',
        confirmButtonText: 'ENTENDIDO',
        allowOutsideClick: false,
        allowEscapeKey: false
      }).then((result) => {
        if (result.isConfirmed) {
          guardarPrestacion();
        }
      });
    });

    $('#addDiagnostico').on('click',function(){
      AddDiagnostico();
    });

    $('#addProcedimiento').on('click',function(){
      AddProcedimiento();
    });
    $('#addExamenLab2').on('click',function(){
      AddExamenLab();
    });
    // $('#addExamenRad2').on('click',function(){
    //   AddExamenRad();
    // });
    ////////////////////////////////////////////////////////////////////////////// NUEVA MODIFICACION EXAMEN DE IMÁGENES 04-05-26 //ACTUALIZADO 29-05-26
    var nivel = {{ $nivel }};
    if(nivel === 3){
      let enviandoFormulario = false;
      var idImagenologiaActual = null;
      var imagenologiaModalMostrado = false;
      $('#addExamenRad2').off('click').on('click', function() {
        if (!imagenologiaModalMostrado) {
          $('#rad_anamnesis').val('');
          $('#rad_examen_fisico').val('');
          $('#rad_examen_auxiliar').val('');
          $('#detalleModal').modal('show');
        } else {
          AddExamenRad();
        }
      });

      $('#tblExamenRad tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        obj.parent().parent().remove();

        var totalFilas = $('#tblExamenRad tbody tr').length;
        if (totalFilas === 0) {
          imagenologiaModalMostrado = false;
          if (idImagenologiaActual) {
            $.ajax({
              url: '/actualizar_estado_imagenologia',
              type: 'POST',
              data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
              id: idImagenologiaActual
            }
          });
        }
        }
      });

      $('#btnConfirmarImagenologia').off('click').on('click', function() {
        var anamnesis         = $.trim($('#rad_anamnesis').val());
        var examen_fisico     = $.trim($('#rad_examen_fisico').val());
        var examen_auxiliar   = $.trim($('#rad_examen_auxiliar').val());
        var errores = [];

        function validarCampo(texto, nombreCampo) {
          if (texto === '') {
            errores.push('Debe ingresar ' + nombreCampo + '.');
            return;
          }

          if (texto.length < 20) {
            errores.push(nombreCampo + ' debe tener mínimo 20 caracteres.');
          }

          if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]/.test(texto)) {
            errores.push(nombreCampo + ' no puede contener solo símbolos.');
          }

          if (/^[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]+$/.test(texto)) {
            errores.push('Ingrese ' + nombreCampo + ' válido(a).');
          }

          if (/^([^\w\s])\1+$/.test(texto)) {
            errores.push(nombreCampo + ' contiene caracteres inválidos.');
          }
        }

        validarCampo(anamnesis, '<b>Anamnesis</b>');
        validarCampo(examen_fisico, '<b>Examen Físico</b>');
        validarCampo(examen_auxiliar, '<b>Examen Auxiliar</b>');

        if (errores.length > 0) {
          Swal.fire({
            icon: 'error',
            title: 'Campos requeridos',
            html: errores.join('<br>'),
            confirmButtonText: 'ENTENDIDO',
          });
          return false;
        }

        var id_emergencia      = $('#id_principal').val();
        var id_asegurado       = $('#id_asegurado').val();
        var id_establecimiento = $('#id_establecimiento').val();
        var id_tipo_atencion   = $('#id_tipo_atencion').val();

        $('#btnConfirmarImagenologia').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
          url: '/guardar_anamnesis_imagenologia',
          type: 'POST',
          data: {
              _token:             $('meta[name="csrf-token"]').attr('content'),
              id_emergencia:      id_emergencia,
              id_asegurado:       id_asegurado,
              id_establecimiento: id_establecimiento,
              anamnesis:          anamnesis,
              examen_fisico:      examen_fisico,
              examen_auxiliar:    examen_auxiliar,
              id_tipo_atencion:   id_tipo_atencion,
          },
          success: function(response) {
            $('#btnConfirmarImagenologia').prop('disabled', false).html('<i class="fa fa-save"></i> <strong>CONFIRMAR Y AGREGAR EXAMEN</strong>');
            if (response.success) {
              idImagenologiaActual = response.id;
              $('#id_rua_imagenes').val(idImagenologiaActual);
              $('#detalleModal').modal('hide');
              imagenologiaModalMostrado = true;
              Swal.fire({
                icon: 'success',
                title: 'Guardado correctamente',
                showConfirmButton: false,
                timer: 1500,
              }).then(function() {
                  AddExamenRad();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                html: response.message || 'Intente nuevamente.',
                confirmButtonText: 'ENTENDIDO',
              });
            }
          },
          error: function(xhr) {
            $('#btnConfirmarImagenologia').prop('disabled', false).html('<i class="fa fa-save"></i> <strong>CONFIRMAR Y AGREGAR EXAMEN</strong>');

            var msg = 'Ocurrió un error al guardar los datos.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error',
              title: 'Error inesperado',
              html: msg,
              confirmButtonText: 'ENTENDIDO',
            });
          }
        });
      });

      $('#detalleModal').on('hidden.bs.modal', function() {
          if (!imagenologiaModalMostrado) {
              imagenologiaModalMostrado = false;
          }
      });

      window.addEventListener('beforeunload', function (e) {
        if (!enviandoFormulario && idImagenologiaActual) {
          navigator.sendBeacon('/actualizar_estado_imagenologia', new URLSearchParams({
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: idImagenologiaActual
          }));
        }
      });
      $('#frmPrestacion').on('submit', function() {
          enviandoFormulario = true;
      });
    }else{
      $('#addExamenRad2').on('click',function(){
        AddExamenRad();
      });
    }
    ////////////////////////////////////////////////////////////////////////////// FIN MODIFICACION EXAMEN DE IMÁGENES 04-05-26

     $('#addProducto').on('click', function () {
	AddProducto();
     });
    $('#addDerivarServicio').on('click', function () {
      var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
      $("#divServicio").hide();
      if(addDerivarServicio == "false"){
        $("#divServicio").show();
      }
    });

    @if($regularizar == "1")
    $("#id_upss_encargado_regularizacion").select2({
        width: 'resolve'
    });

    $("#id_servicio_encargado_regularizacion").select2({
        width: 'resolve'
    });
    @endif

    $("#id_topico").select2({
        width: 'resolve'
    });

    $("#id_cama").select2({
        width: 'resolve'
    });

    $("#id_area_destino").select2({
        width: 'resolve'
    });

    $("#id_cama_destino").select2({
        width: 'resolve'
    });

	$('#addRecetaVale').on('click', function () {
		var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
		$("#fsRecetaVale").hide();
		if(addRecetaVale == "false"){
           //adicionado
           getProducto();
	   $("#fsRecetaVale").show();
	  }
	});

    $('#addExamenLab').on('click', function () {
      var addExamenLab = $('#addExamenLab').attr("aria-pressed");
      $("#fsExamenLab").hide();
      if(addExamenLab == "false"){
        $("#fsExamenLab").show();
      }
    });


    $('#addExamenRad').on('click', function () {
      var addExamenRad = $('#addExamenRad').attr("aria-pressed");
      $("#fsExamenRad").hide();
      if(addExamenRad == "false"){
        $("#fsExamenRad").show();
      }
    });


    // $('#addSignosVitales').on('click', function () {
    //   var addSignosVitales = $('#addSignosVitales').attr("aria-pressed");
    //   $("#fsSignosVitales").hide();
    //   if(addSignosVitales == "false"){
    //     $("#fsSignosVitales").show();
    //   }
    // });

    $('#addInterconsulta').on('click', function () {
      var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
      $("#fsInterconsulta").hide();
      if(addInterconsulta == "false"){
        $("#fsInterconsulta").show();
      }
    });

    $('#addInterconsulta2').on('click', async function() {
      await AddInterconsulta2();
    });


    $('#AddInterconsultadatosreg').on('click', function () {
      var addInterconsulta = $('#AddInterconsultadatosreg').attr("aria-pressed");
      $("#fsinterconsultadatos").hide();
      if(addInterconsulta == "false"){
        //adddatosServiciosxMedicos();
        $("#fsinterconsultadatos").show();
      }
      else{
        $('#personal_salud').val("");
        $('#personal_turno').val("");
        $('#motivo_interconsulta').val("");

      }
    });
    // $('#addReferencia').on('click', function () {
    //   var addReferencia = $('#addReferencia').attr("aria-pressed");
    //   $("#fsReferencia").hide();
    //   if(addReferencia == "false"){
    //     $("#fsReferencia").show();
    //   }
    // });

    // $('#addParesCraneales').on('click', function () {
    //   var addParesCraneales = $('#addParesCraneales').attr("aria-pressed");
    //   $("#fsParesCraneales").hide();
    //   $("#flag_pares_craneales").val("0");
    //   if(addParesCraneales == "false"){
    //     $("#fsParesCraneales").show();
    //     $("#flag_pares_craneales").val("1");
    //   }
    // });

    // $('#addAltaServicio').on('click', function () {
    //   var addAltaServicio = $('#addAltaServicio').attr("aria-pressed");
    //   $("#fsAltaServicio").hide();
    //   $("#flag_alta_servicio").val("0");
    //   if(addAltaServicio == "false"){
    //     $("#fsAltaServicio").show();
    //     $("#flag_alta_servicio").val("1");
    //   }
    // });

    $('#example tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);

      // Selecciona la fila completa
      var fila = obj.closest('tr');

      // Obtiene la posición de la fila
      var filaPosition = fila.index();

      // Imprime la posición del elemento 'input' en la consola
      console.log("La posición del input es: " + filaPosition);
      eliminarDiagnosticoXProducto(filaPosition);
      // Elimina la fila
      obj.parent().parent().remove();
      filasmedicamentos--;
    });

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

    $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
    $('#tblInterconsulta tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#tblExamenLab tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
    $('#tblExamenRad tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
    $("#id_servicio_derivar").select2({
        width: 'resolve'
    });
    $("#id_servicio_interconsulta").select2({
        width: 'resolve'
    });
    $("#id_establecimiento_referencia").select2({
        width: 'resolve'
    });
    $("#id_uci").select2({
        width: 'resolve'
    });
    $("#id_uci_cv").select2({
        width: 'resolve'
    });
    $("#id_ambulancia").select2({
        width: 'resolve'
    });
    $("#id_ambulancia_ref").select2({
        width: 'resolve'
    });

	  $("#id_tipo_ambulancia").select2({
        width: 'resolve'
    });
    $("#id_pasaje").select2({
        width: 'resolve'
    });
    $("#id_acompanante").select2({
        width: 'resolve'
    });

    $("#id_situacion").select2({
          width: 'resolve'
      });
    $("#id_farmacia_receta").select2({
          width: 'resolve'
      });
    $("#upss_bene").select2({
        width: 'resolve'
    });
    $("#ipress_bene").select2({
        width: 'resolve'
    });
    $("#cmbServicio0").select2({
        width: 'resolve'
    });
    $("#temperatura").inputmask("decimal", {
      integerDigits: 2,
      digits: 2,
      // placeholder: "0.00",
      allowMinus: false,
      allowPlus: false,
      // max: 150,
      digitsOptional: true
    });
    /*$("#talla").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 250,
      digitsOptional: false
    });*/
    $("#peso").inputmask("decimal", {
      integerDigits: 3,
      digits: 2,
      // placeholder: "0.00",
      allowMinus: false,
      allowPlus: false,
      // max: 500,
      digitsOptional: true
    });
    /*$("#saturacion").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100,
      digitsOptional: false
    });
    $("#tamanio_cintura").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 200,
      digitsOptional: false
    });
    $("#frecuencia_cardiaca_fetal").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 200,
      digitsOptional: false
    });
    $("#presion_arterial_x").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 200,
      digitsOptional: false
    });
    $("#presion_arterial_y").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 200,
      digitsOptional: false
    });
    $("#frecuencia_respiratoria").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100,
      digitsOptional: false
    });
    $("#frec_cardiaca").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 250,
      digitsOptional: false
    });*/
    $("#oxigenacion").inputmask("decimal", {
      integerDigits: 3,
      // placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100
      // digitsOptional: false
    });

    $("#oxigenaciontriaje").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100,
      digitsOptional: false
    });

    // **********************************************************************************
    $("#temperatura").on('blur', function (event) {
      var value = $(this).val();
      if (Validar.esVacio(value) == false ) {
        if (!decimalRegex.test(value)) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
            $('#texto-aceptado strong').addClass('borde-temperatura-valida');

        } else if( !Validar.decimalEnRango(value, 35.0, 40.0)) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
            $('#texto-aceptado strong').addClass('borde-temperatura-valida');

        } else if(parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
            $('#temperatura').addClass('borde-temperatura-hipotermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

        } else if(parseFloat(value) >= 36.0  && parseFloat(value) < 37.5) { //! VERDE
            $('#temperatura').addClass('borde-temperatura-normal');
            $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

        } else if(parseFloat(value) >= 37.5  && parseFloat(value) < 39.5) { // AMARILLO
            $('#temperatura').addClass('borde-temperatura-fiebre');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

        } else if(parseFloat(value) >= 39.5  && parseFloat(value) < 41.0) { // NARANJA
            $('#temperatura').addClass('borde-temperatura-fiebre-alta');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

        } else if(parseFloat(value) >= 41.0  && parseFloat(value) <= 45.0) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");
        }
      } else if (Validar.esVacio(value) ) {
          $('#temperatura').addClass('borde-temperatura-hipertermia');
          $('#texto-aceptado').html("<p><strong>" + 'Ingrese el valor de la temperatura ( °C )' + "</strong></p>");
          $('#texto-aceptado strong').addClass('borde-temperatura-valida');
        }
    });

    $("#temperatura").on('input',function() {
      $(this).removeClass('borde-temperatura borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
      $('#texto-aceptado').html('');
    });

    // **********************************************************************************

    $("#talla").click(function() {
      $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
      $('#imc-aceptado').html('');
    });

    $("#talla").on('blur',function(event) {
      var value = $(this).val();
      if (Validar.esVacio(value) == false) {
        if (!decimalRegex.test(value)) {
          $('#talla').addClass('borde-temperatura-hipertermia');
          $('#talla-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
          $('#talla-aceptado strong').addClass('borde-temperatura-valida');

        } else if (!Validar.enteroEnRango(value, 30, 200)) {
          $('#talla').addClass('borde-temperatura-hipertermia');
          $('#talla-aceptado').html("<p><strong>" + 'El valor de la talla debe ser entre 30 a 200 ( cm )' + "</strong></p>");
          $('#talla-aceptado strong').addClass('borde-temperatura-valida');
        }else {
          compruebaIMC();
        }
      }else if (Validar.esVacio(value)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'Ingrese el valor de la Talla ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');
      }
    });

    $("#talla").on('input',function() {
      $('#talla').removeClass('borde-temperatura-hipertermia');
      $('#talla-aceptado').html('');
    });

    // **********************************************************************************

    $("#peso").on('blur',function(event) {
      var value = $(this).val();
      if (Validar.esVacio(value) == false) {
        if (!decimalRegex.test(value)) {
          $('#peso').addClass('borde-temperatura-hipertermia');
          $('#peso-aceptado').html("<p><strong>Ingrese un valor válido</strong></p>");
          $('#peso-aceptado strong').addClass('borde-temperatura-valida');

        } else if (!Validar.decimalEnRango(value, 1.0, 180.0)) {
          $('#peso').addClass('borde-temperatura-hipertermia');
          $('#peso-aceptado').html("<p><strong>" + 'El valor del peso debe ser entre 1.0 a 180 ( Kg )' + "</strong></p>");
          $('#peso-aceptado strong').addClass('borde-temperatura-valida');

        } else {
          compruebaIMC();
        }
      }else if (Validar.esVacio(value)) {
        $('#peso').addClass('borde-temperatura-hipertermia');
        $('#peso-aceptado').html("<p><strong>" + 'Ingrese el valor del Peso ( Kg )' + "</strong></p>");
        $('#peso-aceptado strong').addClass('borde-temperatura-valida');
      }
    });

    $("#peso").on('input',function() {
      $('#peso').removeClass('borde-temperatura-hipertermia');
      $('#peso-aceptado').html('');
    });

    $("#peso").click(function() {
      $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
      $('#imc-aceptado').html('');
    });

    // **********************************************************************************

    $("#saturacion").on('blur', function (event) {
      var value = $(this).val();
      if (Validar.esVacio(value) == false ) {
        if (!Validar.enteroEnRango(value, 60, 100)) {
          $('#saturacion').addClass('borde-temperatura-hipertermia');
          $('#saturacion-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
          $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');

        } else if (value >= 60 && value <= 85){
          //HIPOXIA GRAVE  - ROJO
          $('#saturacion').addClass('borde-saturacion-hipoxia-grave');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
        } else if (value >= 86 && value <= 90){
          //HIPOXIA MODERADA - NARANJA
          $('#saturacion').addClass('borde-saturacion-hipoxia-moderada');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
        } else if (value >= 91 && value <= 94){
          // HIPOXIA LEVE - AMARILLO
          $('#saturacion').addClass('borde-saturacion-hipoxia-leve');
          $('#saturacion-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
        } else if (value >= 95 && value <= 100){
          //NORMAL - VERDE
          $('#saturacion').addClass('borde-saturacion-normal');
          $('#saturacion-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
        }
      } else if (Validar.esVacio(value) ) {
        $('#saturacion').addClass('borde-temperatura-hipertermia');
        $('#saturacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Saturación ( % )' + "</strong></p>");
        $('#saturacion-aceptado strong').addClass('borde-temperatura-valida');
      }

    });

    $("#saturacion").on('input',function() {
      $('#saturacion').removeClass('borde-saturacion-hipoxia-grave borde-saturacion-hipoxia-moderada borde-saturacion-hipoxia-leve borde-saturacion-normal borde-temperatura-hipertermia');
      $('#saturacion-aceptado').html('');
    });

    // **********************************************************************************

    $("#frecuencia_cardiaca_fetal").on('blur',function(event) {
      var value = $('#frecuencia_cardiaca_fetal').val();
      if (value.length == 0){
        $('#frecuencia_cardiaca_fetal').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_cardiaca_fetal-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 0 a 170 ( /min )' + "</strong></p>");
        $('#frecuencia_cardiaca_fetal-aceptado strong').addClass('borde-temperatura-valida');
      } else{
        if (Validar.esVacio(value) == false ) {
          if (!Validar.enteroEnRango(value, 0, 170)) {
            $('#frecuencia_cardiaca_fetal').addClass('borde-temperatura-hipertermia');
            $('#frecuencia_cardiaca_fetal-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 0 a 170 ( /min )' + "</strong></p>");
            $('#frecuencia_cardiaca_fetal-aceptado strong').addClass('borde-temperatura-valida');
          }
        }else {
          $('#frecuencia_cardiaca_fetal').removeClass('borde-temperatura-hipertermia');
          $('#frecuencia_cardiaca_fetal-aceptado').html("");
          $('#frecuencia_cardiaca_fetal-aceptado strong').removeClass('borde-temperatura-valida');
        }
      }
    });

    $("#frecuencia_cardiaca_fetal").on('input',function() {
      $('#frecuencia_cardiaca_fetal').removeClass('borde-temperatura-hipertermia');
      $('#frecuencia_cardiaca_fetal-aceptado').html("");
      $('#frecuencia_cardiaca_fetal-aceptado strong').removeClass('borde-temperatura-valida');
    });

    // **********************************************************************************

    $("#presion_arterial_x").on('blur',function(event) {
      var value = $(this).val();
      if (Validar.esVacio(value) == false ) {
        if (!Validar.enteroEnRango(value, 0, 250)) {
          $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
          $('#presion_arterial_x-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 250' + "</strong></p>");
          $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
        }
      } else if(Validar.esVacio(value)){
        $('#presion_arterial_x').addClass('borde-temperatura-hipertermia');
        $('#presion_arterial_x-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
        $('#presion_arterial_x-aceptado strong').addClass('borde-temperatura-valida');
      }
    });

    $("#presion_arterial_x").on('input',function() {
      $('#presion_arterial_x').removeClass('borde-temperatura-hipertermia');
      $('#presion_arterial_x-aceptado').html('');
    });

    // **********************************************************************************

    $("#presion_arterial_y").on('blur',function(event) {
      var value = $(this).val();
      if (Validar.esVacio(value) == false ) {
        if (!Validar.enteroEnRango(value, 0, 140)) {
          $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
          $('#presion_arterial_y-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 140' + "</strong></p>");
          $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
        }
      } else if(Validar.esVacio(value)){
        $('#presion_arterial_y').addClass('borde-temperatura-hipertermia');
        $('#presion_arterial_y-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
        $('#presion_arterial_y-aceptado strong').addClass('borde-temperatura-valida');
      }
    });

    $("#presion_arterial_y").on('input',function() {
      $('#presion_arterial_y').removeClass('borde-temperatura-hipertermia');
      $('#presion_arterial_y-aceptado').html('');
    });

    // **********************************************************************************

    $("#frecuencia_respiratoria").on('blur',function(event) {
      var value = $('#frecuencia_respiratoria').val();
      if (Validar.esVacio(value) == false ) {
        if (!Validar.enteroEnRango(value, 10, 35)) {
          $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
          $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria  debe estar entre 10 a 35 ( /min )' + "</strong></p>");
          $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
        }
      }else if (Validar.esVacio(value)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Respiratoria ( /min )' + "</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
      }
    });

    $("#frecuencia_respiratoria").on('input',function() {
      $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
      $('#frecuencia_respiratoria-aceptado').html('');
    });

    // **********************************************************************************

    $("#frec_cardiaca").on('blur',function(event) {
      var value = $('#frec_cardiaca').val();
      if (Validar.esVacio(value) == false ) {
        if (!Validar.enteroEnRango(value, 50, 150)) {
          $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
          $('#frec_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )' + "</strong></p>");
          $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
        }
      } else if (Validar.esVacio(value)) {
        $('#frec_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frec_cardiaca-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Cardiaca ( /min )' + "</strong></p>");
        $('#frec_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
      }
    });

    $("#frec_cardiaca").on('input',function() {
      $('#frec_cardiaca').removeClass('borde-temperatura-hipertermia');
      $('#frec_cardiaca-aceptado').html('');
    });

    // **********************************************************************************

    $("#oxigenacion").on('blur',function(event) {
      var value = $('#oxigenacion').val();
      if (Validar.esVacio(value) == false ){
        if (!Validar.enteroEnRango(value, 21, 100)) {
          $('#oxigenacion').addClass('borde-temperatura-hipertermia');
          $('#oxigenacion-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
          $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
        }
      }else if (Validar.esVacio(value)) {
        $('#oxigenacion').addClass('borde-temperatura-hipertermia');
        $('#oxigenacion-aceptado').html("<p><strong>" + 'Ingrese el valor de la Oxigenacion ( % )' + "</strong></p>");
        $('#oxigenacion-aceptado strong').addClass('borde-temperatura-valida');
      }
    });

    $("#oxigenacion").on('input',function() {
      $('#oxigenacion').removeClass('borde-temperatura-hipertermia');
      $('#oxigenacion-aceptado').html('');
    });

    // **********************************************************************************

    // Nombres de los input file para el manejo de sus eventos
    // let list_input_file = [
    //   "file_exam_gen_9"
    //   , "file_cabeza_cuello"
    //   , "file_mamas"
    //   , "file_exam_reg_10"
    //   , "file_exam_reg_11"
    //   , "file_exam_reg_12"
    //   , "file_exam_reg_13"
    //   , "file_neurologico"
    // ];

    //list_input_file.forEach(nombre => configurarManejoInputFile(nombre));

    AddDiagnostico();
    //AddProcedimiento();
    //AddExamenLab();
    //AddExamenRad();
    // getProducto();
    modalHistorial();
    modalLaboratorio();
    modalRadiologico();
    modalInterconsulta();
    //modalAntecedentes();


  });

function activarToggleSignosVitales() {
  var addSignosVitales = $('#addSignosVitales').attr("aria-pressed");
  $("#fsSignosVitales").hide();
  if(addSignosVitales == "false"){
    $("#fsSignosVitales").show();
  }
}

//agregarcamas

function getTopico (){
    var id_prioridad = $('#id_prioridad').val();
    var sexo = $('#sexo').val();
    var edad = $('#edadpaciente').val();

    if(id_prioridad!=''){
      /*
      if(id_prioridad=='4'){
        $('#id_topico').prop('disabled', true).trigger('change');
        //$('#topico_toggle').hide();
      }else{
        $('#id_topico').prop('disabled', false).trigger('change');
        //$('#topico_toggle').show();
      }*/
      //valicion de tipo de listado
      var url_consulta='/get_topico_por_id_prioridad/' + id_prioridad;
        ///validamos que sea de prioridad 2 y 3 para colocar por defecto gineco
        if(id_prioridad=='2'||id_prioridad=='3'){
            url_consulta='/get_topico_por_id_prioridad_sexo_edad/'+id_prioridad+'/'+sexo+'/'+edad;
        }

        $.ajax({
          url: url_consulta,
          success: function(result) {
            $('#id_topico').empty();
            $('#id_topico').append("<option value=''>--SELECCIONAR--</option>");
            $(result).each(function(ii, oo) {
              $('#id_topico').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>");
            });
            $('#id_topico').val("").change();
            if(id_prioridad=='1'){
                $('#id_topico').val("1").change();
                $('#observacion').focus();
            }else if(id_prioridad=='4'){
                $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>");
                $('#id_topico').val("19").change();
                $('#observacion').focus();
            }else{
                $('#id_topico').val("").change();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            if(id_prioridad!='4'){
              $('#id_topico').empty();
              $('#id_topico').append("<option value=''>--SELECCIONAR--</option>");
              $('#id_topico').val("").change();
              $('#observacion').focus();
            }else{
              $('#id_topico').empty();
              $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>");
              $('#id_topico').val("19").change();
              $('#observacion').focus();
            }
          }
        });
    }else{
      $('#id_topico').empty();
      $('#id_topico').append("<option value=''>--SELECCIONAR--</option>");
      $('#id_topico').val("").change();
    }
  }

  function getCamas(){
    var id_ipress = $('#id_establecimiento').val();
    var id_area_destino = $('#id_area_destino').val();

    if(id_area_destino!='0') {

      var url_consulta='/get_camas_por_id_cuarto/' + id_ipress + '/' + id_area_destino + '/0';
      $.ajax({
        url: url_consulta,
        success: function(result) {
          $('#id_cama_destino').empty();
          $('#id_cama_destino').append("<option value='0'>-Seleccione-</option>");
          $(result).each(function(ii, oo) {
            $('#id_cama_destino').append("<option value='" + oo.id + "'>" + oo.nombre + "</option>");
          });
          $('#id_cama_destino').val("0").change();
        },
      });
    }else{
      $('#id_cama_destino').empty();
      $('#id_cama_destino').append("<option value='0'>-Seleccione-</option>");
      $('#id_cama_destino').val("0").change();
    }
  }

function compruebaIMCInicio() {
  var peso = parseFloat($('#triaje_peso').val());
  var talla = parseFloat($('#triaje_talla').val());

  if (!isNaN(talla)) {
    if(talla===0){
      pesoIdeal = 0;
    }
    else{
      // PESO POR ALTURA
      var sexo = $('input[name="nomsexo"]').val(); // MASCULINO, FEMENINO
      // Edad
      var edad_anos = parseInt($('input[name="edadpaciente"]').val(), 10); // 12/05/1992

      var pesoIdeal = 50 + ((talla - 150) / 4) * 3 + (edad_anos - 20) / 4;
      if (sexo === "M") {
        pesoIdeal = pesoIdeal * 0.9;
      }
    }

    $('input[name="triaje_peso_ideal"]').val(pesoIdeal.toFixed(2));
  } else {
    $('input[name="triaje_peso_ideal"]').val("");
  }

  if (!isNaN(peso) && !isNaN(talla)) {
    if(peso===0){
      var imcx = 0;
    }
    else{
      if(talla===0){
        var imcx = 0;
      }
      else{
          var talla_en_metros = talla * 0.01;
          // IMC
          var imcx = (peso / (talla_en_metros * talla_en_metros)).toFixed(2);
      }
    }

    $('input[name="triaje_imc"]').val(imcx);
  } else {
    $('input[name="triaje_imc"]').val('');
  }
}

function eliminarDiagnosticosarr(codigon){
  let codigo2 =codigon.trim()

let listIdDiagnosticoObjetos2 =[];
  for (let index = 0; index < listIdDiagnosticoObjetos.length; index++) {
    const element = listIdDiagnosticoObjetos[index];
    if(element.codigo !== codigo2){
      listIdDiagnosticoObjetos2.push(element)
    }

  }
  listIdDiagnosticoObjetos=[];
  listIdDiagnosticoObjetos =listIdDiagnosticoObjetos.concat(listIdDiagnosticoObjetos2);



  procedimientosSeleccionados= procedimientosSeleccionados.map((item) => {
    if (item.codigo === codigo2) {
      return { ...item, id: "" };
    } else {
      return item;
    }
  });

  console.log("la lista con eliminacion de los diagnosticos x productos es : " + JSON.stringify(procedimientosSeleccionados))



console.log("la lista con eliminacion es " + JSON.stringify(listIdDiagnosticoObjetos));
updateSelectElements();

}


  function compruebaIMC() {

    var peso = document.frmPrestacion.peso.value;
    console.log(peso);
    if(peso==''){
      peso=0;
    }
    var talla = document.frmPrestacion.talla.value;
    if(talla==''){
      talla=0;
    }

    if (talla != "") {
      //PESO POR ALTURA
      var sexo = document.frmPrestacion.nomsexo.value; //MASCULINO , FEMENINO
      //edad
      // var tipo_registro = document.frmPrestacion.tipo_registro.value; // 0: Registro manual, 1: Registro automatico
      var fecha_nac = document.frmPrestacion.fecnacafiliado.value; //12/05/1992
      // var fecha_nac2 = document.frmPrestacion.fecha_nac2.value;
      //var edad2 = document.frmTriajeEmergencia.edad2.value;
      var fechax = gfecha(fecha_nac); // : gfecha(fecha_nac2);

      var datos_edad = calcularEdad(fechax);
      var edad_anos = 0;

      if (datos_edad != "") {
        var arregloedad = datos_edad.split("|");
        if (arregloedad.length > 0) {
          var edad_anos = arregloedad[0];
        }
      }
      var pesoIdeal = 50 + ((talla - 150) / 4) * 3 + (edad_anos - 20) / 4
      if (sexo == "M") {
        pesoIdeal = pesoIdeal * 0.9;
      }
      document.frmPrestacion.peso_x_altura.value = pesoIdeal.toFixed(2);
    } else {
      document.frmPrestacion.peso_x_altura.value = "";
    }


    if (peso != "" && talla != "") {
      var talla_en_metros = talla * 0.01;
      //IMC
      var imcx = (peso / (talla_en_metros * talla_en_metros)).toFixed(2);
      document.frmPrestacion.imc.value = imcx;

      if (imcx < 18.5) {
        $('#imc').addClass('borde-imc-bajopeso');
        $('#imc-aceptado').html("<strong>" + 'Paciente Bajo de Peso' + "</strong>");

      } else if (imcx >= 18.5 && imcx< 24.9) {
        $('#imc').addClass('borde-imc-normal');
        $('#imc-aceptado').html("<strong>" + 'Paciente Peso Normal' + "</strong>");
        //NORMAL
      } else if (imcx >= 25.0 && imcx< 29.9){
        $('#imc').addClass('borde-imc-sobrepeso');
        $('#imc-aceptado').html("<strong>" + 'Paciente Sobrepeso' + "</strong>");
        //SOBREPESO
      } else if (imcx >= 30.0 && imcx< 34.9){
        $('#imc').addClass('borde-imc-obesidad1');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad I' + "</strong>");
        //OBESIDAD I
      } else if (imcx >= 35.0 && imcx< 39.9){
        $('#imc').addClass('borde-imc-obesidad2');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad II' + "</strong>");
        // OBESIDAD II
      } else if (imcx >= 40.0){
        $('#imc').addClass('borde-imc-obesidad3');
        $('#imc-aceptado').html("<strong>" + 'Paciente Obesidad III' + "</strong>");
        // OBESIDAD III
      }

    } else {
      document.frmPrestacion.imc.value = '';
    }
  }
/*
function adddatosServiciosxMedicos(){
  $.ajax({
  dataType: 'json',
  type: 'POST',
  url: '/serviciosxMedicos', // Reemplaza con la URL de tu API
  success: function (result) {
    crearOpcionesServicio(result);
    console.log('La petición fue exitosa, y aquí están los datos recibidos:', result);


  },
  error: function (msg, textStatus, errorThrown) {

    console.error('Error durante la petición AJAX:', textStatus, errorThrown);
  }
});


}

function crearOpcionesServicio(servicios) {
  // Obtén el elemento <select> por su ID
  const selectServicio = $('#servicio');
  selectServicio.empty();
  selectServicio.append('<option value="">-Seleccionar-</option>');
  // Itera sobre cada servicio en el array de resultados
  servicios.forEach((servicio) => {
    const option = $('<option>');
    option.val(servicio.id);
    option.text(servicio.nombre);
    selectServicio.append(option);
  });
}

$('#servicio').on('change', function () {
    // Obtiene el valor seleccionado
    const valorSeleccionado = $(this).val();

    // Llama a la función 'opcionSeleccionada' con el valor seleccionado
    opcionSeleccionada(valorSeleccionado);
  });


  function opcionSeleccionada(valorSeleccionado) {
  console.log('El valor seleccionado es:', valorSeleccionado);

  $.ajax({
    dataType: 'json',
    type: 'POST',
    data: {
      valorSeleccionado: valorSeleccionado
    },
    url: '/medicosxServicio', // Reemplaza con la URL de tu API
    success: function (result) {
      console.log('La petición fue exitosa, y aquí están los datos recibidos:', result);
      $('#personal_salud').empty().append('<option value="">-Seleccionar-</option>');

      // Crear un objeto para llevar un registro de los id ya agregados
      var idRegistrados = {};

      $.each(result, function (index, medico) {
        // Verificar si el id no ha sido registrado previamente
        if (!idRegistrados[medico.id]) {
          var opcion = $('<option>');
          opcion.val(medico.id);
          opcion.text(medico.nombre_completo);
          $('#personal_salud').append(opcion);

          // Marcar el id como registrado
          idRegistrados[medico.id] = true;
        }
      });

    },
    error: function (msg, textStatus, errorThrown) {
      console.error('Error durante la petición AJAX:', textStatus, errorThrown);
    }
  });
}

$('#personal_salud').on('change', function () {
    // Obtiene el valor seleccionado
    const valorSeleccionado = $(this).val();

    // Llama a la función 'opcionSeleccionada' con el valor seleccionado
    opcionSeleccionada2(valorSeleccionado);
  });


  function opcionSeleccionada2(valorSeleccionado) {
  console.log('El valor seleccionado es:', valorSeleccionado);

  $.ajax({
    dataType: 'json',
    type: 'POST',
    data: {
      valorSeleccionado: valorSeleccionado
    },
    url: '/turnosmedico', // Reemplaza con la URL de tu API
    success: function (result) {
      console.log('La petición fue exitosa, y aquí están los datos recibidos:', result);
      $('#personal_turno').empty().append('<option value="">-Seleccionar-</option>');
      $.each(result, function (index, turno) {
        var opcion = $('<option>');
        opcion.val(turno.id);
        opcion.text(turno.descripcion_concatenada);
        $('#personal_turno').append(opcion);
      });


    },
    error: function (msg, textStatus, errorThrown) {
      console.error('Error durante la petición AJAX:', textStatus, errorThrown);
    }
  });

}

function getMedico(){
  var id_ipress     = $('#id_establecimiento').val();
  var id_consultorio  = $('#inter_servicio').val();

  $.ajax({
      url: '/obtener_medico_por_idipress_idconsultorio_solo_emergencia/' + id_ipress+ '/' + id_consultorio,
      success: function(result){
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
        newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
        });
        $('#personal_salud').html(newOption);
        $("#personal_salud").select2({max_selected_options: 4});
      }
      });

}
*/


function getMedico(id_consultorio,i){
    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#personal_salud').html(newOption);
    $("#personal_salud").val('').trigger("change");

    var turno     = $('#turno').val();
    var fecha_admision  = $('#fecha_atencion').val();
    var id_ipress     = $('#id_establecimiento').val();
    //var id_consultorio  = $('#id_servicio_interconsulta').val();
    //var id_consultorio  = $('#inter_servicio').val();




    $.ajax({
      url: '/obtener_medico_por_idipress_idconsultorio_turno_emergencia/' + id_ipress+ '/' + id_consultorio+'/'+turno + '/' + fecha_admision,
      success: function(result){
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
        });
        $('#personal_salud'+i).html(newOption);
        //$("#personal_salud").select2({max_selected_options: 4});
      }
    });

    }

  function getProducto(){
    $('#example tbody').html('');
    $('#cmbMedicamentoTemp').html('');
    var id_ipress     = $('#id_establecimiento').val();
    var id_consultorio  = $('#id_servicio').val();
    var id_tiporeceta   = $('#id_tipo_atencion').val();
    var id_farmacia     = $('#id_farmacia_receta').val();

    var nivel     = $('#id_nivel').val();
    var x  = $('#nro_interconsultas').val();
    var dni = $('#dni_beneficiario').val();
    if(id_tiporeceta == "3"){
      if(id_farmacia!=21){ //SIN NUTRICIONAL
        if(id_farmacia==10){
          if (id_consultorio == 44 || id_consultorio == 70) { //onco - hemato
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
              success: function(result){
                var newOption = "";
                newOption = "<option value=''>--Seleccionar--</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          }
        }
        else {
          if (id_consultorio==21){ //odonto
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
              success: function(result){
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          }
          else {
            if (id_consultorio==72){ //aneste
              $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 72,
                success: function(result){
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
                }
              });
            }
            else
            {
              if(id_farmacia != 132){
                $.ajax({
                  url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                  success: function(result){
                  var newOption = "";
                  newOption = "<option value=''>--Seleccionar-</option>";
                  $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                  });
                  $('#cmbMedicamentoTemp').html(newOption);
                  }
                });
              }
            }
          }
        }
      }
      else
      {
        if(id_farmacia == 21){ //nutricional
          if(id_tiporeceta==5 || id_tiporeceta == 3){
            id_consultorio = 110;
          }
          else{
            id_consultorio = 111;
          }
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia_nutricial/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
            success: function(result){
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });
              $('#cmbMedicamentoTemp').html(newOption);
            }
          });
        }
      }
    }
    if(id_farmacia == 132){
      $.ajax({
        url: '/obtener_producto_farmacotecnia/' + id_ipress,
        success: function(result){
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          });
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });
    }
  }


  function getSubConsultorio() {
    var id_establecimiento = $('#id_establecimiento').val();
    var id_consultorio = $('#id_servicio').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    $.ajax({
      //url: '/obtener_servicio_por_id_establecimiento_and_id_medico/' + id_establecimiento + '/' + id_medico + '/' + id_tipo_atencion,
        url: '/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' + id_consultorio,
        success: function (result) {
        var newOption = "";
        var reg = result.length;
        if(reg >= 2){
          newOption = "<option value='0'>--Seleccionar-</option>";
        }
        $(result).each(function (ii, oo) {
          newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
        });
        $('#id_sub_consultorio').html(newOption);
        $("#id_sub_consultorio").select2({max_selected_options: 4});
      }
    });
  }

  $("#id_farmacia_receta").select2({
    width: 'resolve'
  });

  async function guardarPrestacion(){

    var msg = "";
    var addExamenLab = $('#addExamenLab').attr("aria-pressed");

    if (addExamenLab == "true") {
      const tblExamenLab_cantidad_tr1 = $('#tblExamenLab tbody tr').length;
      if (tblExamenLab_cantidad_tr1 > 0) {
        const id_hora_toma_muestra = document.getElementById('id_hora_toma_muestra');
        const lab_orden_observacion = document.getElementById('lab_orden_observacion');
        const lab_orden_toma_muestra = document.getElementById("lab_orden_toma_muestra");

        if (!id_hora_toma_muestra.value) {

          const result = await Swal.fire({
            title: 'ALERTA',
            width: 400,
            confirmButtonText: 'GUARDAR',
            customClass: { confirmButton: 'btn btn-success' },
            buttonsStyling: false,
            html: `
                <div style="text-align: left">
                  <label for="">Debe seleccionar la hora de la toma de muestra</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" value="1">05:00 AM
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" value="2">09:00 AM
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" value="3">16:00 PM
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" value="4">22:00 PM
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="hora" id="radio_otro" value="0">OTRO
                    </label>
                  </div>
                  <textarea id="obs_otro" placeholder="Observación..." class="form-control" style="display:none; width:100%; margin-top:8px;" rows="2">${lab_orden_observacion.value}</textarea>
                </div>
            `,
            didOpen: () => {
              document.getElementById('radio_otro').addEventListener('change', function () {
                document.getElementById('obs_otro').style.display = this.checked ? 'block' : 'none';
              });
            },
            preConfirm: () => {
              const seleccionado = document.querySelector('input[name="hora"]:checked');
              if (!seleccionado) {
                  Swal.showValidationMessage('Seleccione una opción');
                  return false;
              }
              if (seleccionado.value === '0') {
                const obs = document.getElementById('obs_otro').value.trim();
                if (!obs) {
                  Swal.showValidationMessage('Ingrese una observación');
                  return false;
                }
                lab_orden_observacion.value = obs;
                return '0';
              }

              return seleccionado.value;
            }
          });

          if (!result.isConfirmed) return;

          id_hora_toma_muestra.value = result.value;

          const radioPrincipal = document.getElementById('hora_toma_muestra__' + result.value);
          if (radioPrincipal) {
              radioPrincipal.checked = true;
          }

          let descripcionHoraTomaMuestra = "";
          switch (result.value) {
            case "0": descripcionHoraTomaMuestra = "OTRO"; break;
            case "1": descripcionHoraTomaMuestra = "05:00 AM"; break;
            case "2": descripcionHoraTomaMuestra = "09:00 AM"; break;
            case "3": descripcionHoraTomaMuestra = "16:00 PM"; break;
            case "4": descripcionHoraTomaMuestra = "22:00 PM"; break;
          }

          lab_orden_toma_muestra.value = descripcionHoraTomaMuestra;
        } else if (id_hora_toma_muestra.value == "0" && lab_orden_observacion.value.trim() == "") {
          msg += "Al seleccionar la Hora de Toma de Muestra en <b>OTRO</b><br>debe ingresar el detalle en el campo de <b>Observación de la Orden de Laboratorio</b>.<br>";
        }
      }
    }

    Swal.fire({
      title: '<h5><b>REGISTRAR HISTORIA CLÍNICA</b></h5>',
      showLoaderOnConfirm: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      onOpen: () => {
        Swal.showLoading();
      }
    });

    // var msg = "";

    var id_tipo_atencion = $('#id_tipo_atencion').val();
    var codigo_upss = $('#codigo_upss').val();
    var id_modalidad = $('#id_modalidad').val();
    var fecha_atencion = $('#fecha_atencion').val();
    let hora_atencion = $('#hora_atencion').val();
    var id_servicio= $('#id_servicio').val();
    var id_sub_consultorio= $('#id_sub_consultorio').val();
    var dni_beneficiario= $('#dni_beneficiario').val();
    var beneficiario= $('#beneficiario').val();
    var id_diagnostico = $('input[name="id_diagnostico[]"]').map(function(){return $(this).val();}).get();
    var id_procedimiento = $('input[name="id_procedimiento[]"]').map(function(){return $(this).val();}).get();
    var procedimiento_cantidad = $('input[name="procedimiento_cantidad[]"]').map(function(){return $(this).val();}).get();
    var id_tipo_diagnostico = $('input[name="id_tipo_diagnostico[]"]').map(function(){return $(this).val();}).get();
    var fecha_expedicion = $('#fecha_expedicion').val();
    var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
    var id_servicio_derivar= $('#id_servicio_derivar').val();
    var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
    var addExamenRad = $('#addExamenRad').attr("aria-pressed");
    var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
    var addReferencia = $('#addReferencia').attr("aria-pressed");
    var addParesCraneales = $('#addParesCraneales').attr("aria-pressed");
    var cantidadReceta = $('#cantidadReceta').val();
    $('#procedimientosSeleccionados1').val(JSON.stringify(procedimientosSeleccionados));
    let vardatos = $('#procedimientosSeleccionados1').val();
    //Signos vitales
    // let temperatura = $('#temperatura').val();
    // let saturacion = $('#saturacion').val();
    // let presion_arterial_x = $('#presion_arterial_x').val();
    // let presion_arterial_y = $('#presion_arterial_y').val();
    // let frecuencia_respiratoria = $('#frecuencia_respiratoria').val();
    // let frec_cardiaca = $('#frec_cardiaca').val();

    //Enfermedad Actual
    // let tiempo_enfermedad = $('#tiempo_enfermedad').val();
    // let forma_inicio = $('#forma_inicio').val();
    // let curso = $('#curso').val();
    // let sintomas_signos_principal = $('#sintomas_signos_principal').val();
    // let relato = $('#relato').val();
    // let tratamiento_recibido_antes = $('#tratamiento_recibido_antes').val();
    // let fn_bio_1 = $('#fn_bio_1').val();
    // let fn_bio_2 = $('#fn_bio_2').val();
    // let fn_bio_3 = $('#fn_bio_3').val();
    // let fn_bio_4 = $('#fn_bio_4').val();
    // let fn_bio_5 = $('#fn_bio_5').val();
    // let fn_bio_6 = $('#fn_bio_6').val();

    //Antecedentes
    // let antc_1 = $('#antc_1').val();
    // let antc_2 = $('#antc_2').val();
    // let antc_3 = $('#antc_3').val();
    // let antc_4 = $('#antc_4').val();
    // let antc_5 = $('#antc_5').val();
    // let antc_6 = $('#antc_6').val();
    // let antc_7 = $('#antc_7').val();
    // let antc_8 = $('#antc_8').val();
    // let antc_9 = $('#antc_9').val();
    // let antc_10 = $('#antc_10').val();
    // let antc_11 = $('#antc_11').val();
    // let antc_12 = $('#antc_12').val();
    // let antc_13 = $('#antc_13').val();
    // let antc_14 = $('#antc_14').val();
    // let antc_15 = $('#antc_15').val();
    // let antc_17 = $('#antc_17').val();
    // let antc_18 = $('#antc_18').val();
    // let antc_20 = $('#antc_20').val();
    // let antc_21 = $('#antc_21').val();
    // let antc_22 = $('#antc_22').val();
    // let antc_23 = $('#antc_23').val();
    // let antc_24 = $('#antc_24').val();
    // let antc_25 = $('#antc_25').val();
    // let antc_26 = $('#antc_26').val();
    // let antc_27 = $('#antc_27').val();
    // let antc_28 = $('#antc_28').val();
    // let antc_29 = $('#antc_29').val();
    // let antc_30 = $('#antc_30').val();
    // let antc_31 = $('#antc_31').val();
    // let antc_32 = $('#antc_32').val();
    // let antc_33 = $('#antc_33').val();
    // let antecedente_familiar = $('#antecedente_familiar').val();

    // Examen clinico general
    // let exam_gen_1 = $('#exam_gen_1').val();
    // let exam_gen_2 = $('#exam_gen_2').val();
    // let exam_gen_3 = $('#exam_gen_3').val();
    // let exam_gen_4 = $('#exam_gen_4').val();
    // let exam_gen_5 = $('#exam_gen_5').val();
    // let exam_gen_6 = $('#exam_gen_6').val();
    // let exam_gen_7 = $('#exam_gen_7').val();
    // let exam_gen_8 = $('#exam_gen_8').val();
    // let exam_gen_9 = $('#exam_gen_9').val();

    // Examen clinico regional
    // let exam_reg_1 = $('#exam_reg_1').val();
    // let exam_reg_2 = $('#exam_reg_2').val();
    // let exam_reg_3 = $('#exam_reg_3').val();
    // let exam_reg_4 = $('#exam_reg_4').val();
    // let exam_reg_5 = $('#exam_reg_5').val();
    // let exam_reg_6 = $('#exam_reg_6').val();
    // let exam_reg_7 = $('#exam_reg_7').val();
    // let exam_reg_8 = $('#exam_reg_8').val();
    // let exam_reg_9 = $('#exam_reg_9').val();
    // let exam_reg_10 = $('#exam_reg_10').val();

    // let exam_reg_11 = $('#exam_reg_11').val();
    // let exam_reg_12 = $('#exam_reg_12').val();
    // let exam_reg_13 = $('#exam_reg_13').val();
    // let exam_reg_14 = $('#exam_reg_14').val();
    // let exam_reg_15 = $('#exam_reg_15').val();
    // let exam_reg_16 = $('#exam_reg_16').val();
    // let exam_reg_17 = $('#exam_reg_17').val();
    // let exam_reg_18 = $('#exam_reg_18').val();

    // // Pares craneales
    // let exam_reg_19 = $('#exam_reg_19').val();
    // let exam_reg_20 = $('#exam_reg_20').val();
    // let exam_reg_21 = $('#exam_reg_21').val();
    // let exam_reg_22 = $('#exam_reg_22').val();
    // let exam_reg_23 = $('#exam_reg_23').val();
    // let exam_reg_24 = $('#exam_reg_24').val();
    // let exam_reg_25 = $('#exam_reg_25').val();
    // let exam_reg_26 = $('#exam_reg_26').val();
    // let exam_reg_27 = $('#exam_reg_27').val();

    // let input_file_exam_gen_9 = document.getElementById('file_exam_gen_9');
    // let file_exam_gen_9 = input_file_exam_gen_9.files[0]; // Obtener el archivo seleccionado
    // let input_file_cabeza_cuello = document.getElementById('file_cabeza_cuello');
    // let file_cabeza_cuello = input_file_cabeza_cuello.files[0];
    // let input_file_mamas = document.getElementById('file_mamas');
    // let file_mamas = input_file_mamas.files[0];
    // let input_file_exam_reg_10 = document.getElementById('file_exam_reg_10');
    // let file_exam_reg_10 = input_file_exam_reg_10.files[0];
    // let input_file_exam_reg_11 = document.getElementById('file_exam_reg_11');
    // let file_exam_reg_11 = input_file_exam_reg_11.files[0];
    // let input_file_exam_reg_12 = document.getElementById('file_exam_reg_12');
    // let file_exam_reg_12 = input_file_exam_reg_12.files[0];
    // let input_file_exam_reg_13 = document.getElementById('file_exam_reg_13');
    // let file_exam_reg_13 = input_file_exam_reg_13.files[0];
    // let input_file_neurologico = document.getElementById('file_neurologico');
    // let file_neurologico = input_file_neurologico.files[0];

    // let indicacion_terapeutica = $('#indicacion_terapeutica').val();
    // let procedimiento_medico_quirurgico = $('#procedimiento_medico_quirurgico').val();
    // let select_cambiar_cama = $('#select_cambiar_cama').val();
    // let regularizar_datos = $('#regularizar_datos').val();

    // if(regularizar_datos == "1") {
    //   let id_servicio_encargado_regularizacion = $('#id_servicio_encargado_regularizacion').val();
    //   if (id_servicio_encargado_regularizacion == "0" || id_servicio_encargado_regularizacion == null) msg+="Debe seleccionar el <b>Servicio a Cargo</b><br>";
    // }
    // if(tiempo_enfermedad.trim()=="") msg+="Debe ingresar la <b>Enfermedad Actual</b><br>";
    // if(forma_inicio.trim()=="") msg+="Debe ingresar el campo <b>Forma de Inicio</b><br>";
    // if(curso.trim()=="") msg+="Debe ingresar el campo <b>Curso</b><br>";
    // if(sintomas_signos_principal.trim()=="") msg+="Debe ingresar el campo <b>Sintomas y signos principales</b><br>";
    // if(relato.trim()=="") msg+="Debe ingresar el campo <b>Relato</b><br>";
    // if(tratamiento_recibido_antes.trim()=="") msg+="Debe ingresar el campo <b>Tratamiento recibido antes</b><br>";
    // if(fn_bio_1.trim()=="") msg+="Debe ingresar el campo <b>Apetito</b><br>";
    // if(fn_bio_2.trim()=="") msg+="Debe ingresar el campo <b>Sed</b><br>";
    // if(fn_bio_3.trim()=="") msg+="Debe ingresar el campo <b>Peso</b><br>";
    // if(fn_bio_4.trim()=="") msg+="Debe ingresar el campo <b>Micción</b><br>";
    // if(fn_bio_5.trim()=="") msg+="Debe ingresar el campo <b>Deposiciones</b><br>";
    // if(fn_bio_6.trim()=="") msg+="Debe ingresar el campo <b>Sueño</b><br>";

    // if(antc_1.trim()=="") msg+="Debe ingresar el campo <b>Grupo sanguíneo y factor RH</b><br>";
    // if(antc_2.trim()=="") msg+="Debe ingresar el campo <b>Alergías-RAM</b><br>";
    // if(antc_3.trim()=="") msg+="Debe ingresar el campo <b>Drogas</b><br>";
    // if(antc_4.trim()=="") msg+="Debe ingresar el campo <b>Alcohol</b><br>";
    // if(antc_5.trim()=="") msg+="Debe ingresar el campo <b>Tabaco</b><br>";
    // if(antc_6.trim()=="") msg+="Debe ingresar el campo <b>Café</b><br>";
    // if(antc_7.trim()=="") msg+="Debe ingresar el campo <b>Transfusiones</b><br>";
    // if(antc_8.trim()=="") msg+="Debe ingresar el campo <b>Otros de importancia</b><br>";
    // if(antc_9.trim()=="") msg+="Debe ingresar el campo <b>Nacimiento</b><br>";
    // if(antc_10.trim()=="") msg+="Debe ingresar el campo <b>Desarrollo Psicomotor</b><br>";

    // if(antc_11.trim()=="") msg+="Debe ingresar el campo <b>Vacunas</b><br>";
    // if(antc_12.trim()=="") msg+="Debe ingresar el campo <b>Inicio de relaciones sexuales</b><br>";
    // if(antc_13.trim()=="") msg+="Debe ingresar el campo <b>Comportamiento Sexual</b><br>";
    // if(antc_14.trim()=="") msg+="Debe ingresar el campo <b>Menarquía</b><br>";
    // if(antc_15.trim()=="") msg+="Debe ingresar el campo <b>Régimen Catamenial</b><br>";
    // if(antc_17.trim()=="") msg+="Debe ingresar el campo <b>Método Anticonceptivo</b><br>";
    // if(antc_18.trim()=="") msg+="Debe ingresar el campo <b>Uso de hormonas</b><br>";
    // if(antc_20.trim()=="") msg+="Debe ingresar el campo <b>PARA</b><br>";

    // if(antc_21.trim()=="") msg+="Debe ingresar el campo <b>Enfermedades de la infancia</b><br>";
    // if(antc_22.trim()=="") msg+="Debe ingresar el campo <b>Diabetes</b><br>";
    // if(antc_23.trim()=="") msg+="Debe ingresar el campo <b>Hipertensión</b><br>";
    // if(antc_24.trim()=="") msg+="Debe ingresar el campo <b>TBC</b><br>";
    // if(antc_25.trim()=="") msg+="Debe ingresar el campo <b>Dislipidemias</b><br>";
    // if(antc_26.trim()=="") msg+="Debe ingresar el campo <b>Hepatitis</b><br>";
    // if(antc_27.trim()=="") msg+="Debe ingresar el campo <b>Convulsiones</b><br>";
    // if(antc_28.trim()=="") msg+="Debe ingresar el campo <b>Asma</b><br>";
    // if(antc_29.trim()=="") msg+="Debe ingresar el campo <b>Otros</b><br>";
    // if(antc_30.trim()=="") msg+="Debe ingresar el campo <b>Intervenciones Quirúrgicas</b><br>";

    // if(antc_31.trim()=="") msg+="Debe ingresar el campo <b>Traumatismo y Accidentes</b><br>";
    // if(antc_32.trim()=="") msg+="Debe ingresar el campo <b>Medicación de uso habitual</b><br>";
    // if(antc_33.trim()=="") msg+="Debe ingresar el campo <b>Hospitalizaciones previas</b><br>";

    // if(antecedente_familiar=="") msg+="Debe ingresar los <b>Antecedentes Familiares</b><br>";

    // // Examen Clinico General
    // if(exam_gen_1.trim()=="") msg+="Debe ingresar el campo <b>Ectoscopía</b><br>";
    // if(exam_gen_2.trim()=="") msg+="Debe ingresar el campo <b>Tipo morfológico</b><br>";
    // if(exam_gen_3.trim()=="") msg+="Debe ingresar el campo <b>Fascies</b><br>";
    // if(exam_gen_4.trim()=="") msg+="Debe ingresar el campo <b>Talla</b><br>";
    // if(exam_gen_5.trim()=="") msg+="Debe ingresar el campo <b>Peso</b><br>";
    // if(exam_gen_6.trim()=="") msg+="Debe ingresar el campo <b>Piel y faneras</b><br>";
    // if(exam_gen_7.trim()=="") msg+="Debe ingresar el campo <b>Tejido celular subcutáneo</b><br>";
    // if(exam_gen_8.trim()=="") msg+="Debe ingresar el campo <b>Sistema Linfático</b><br>";
    // if(exam_gen_9.trim()=="") msg+="Debe ingresar el campo <b>Sistema Locomotor</b><br>";
    // if (file_exam_gen_9) { // Verificar si hay archivo seleccionado
    //   if (!file_exam_gen_9.type.startsWith('image/')) { // Validar que sea una imagen
    //     msg += 'Por favor selecciona una imagen válida en el campo <b>Sistema Locomotor.</b><br>';
    //   }
    //   if (file_exam_gen_9.size > 5 * 1024 * 1024) { // Validar el tamaño del archivo (máximo 5MB)
    //     msg += 'El tamaño del archivo no debe exceder los 5MB en el campo <b>Sistema Locomotor.</b><br>';
    //   }
    // }

    // Examen Clinico Regional
    // if (file_cabeza_cuello) { // Verificar si hay archivo seleccionado
    //   if (!file_cabeza_cuello.type.startsWith('image/')) { // Validar que sea una imagen
    //     msg += 'Por favor selecciona una imagen válida en el campo <b>CABEZA Y CUELLO.</b><br>';
    //   }
    //   if (file_cabeza_cuello.size > 5 * 1024 * 1024) { // Validar el tamaño del archivo (máximo 5MB)
    //     msg += 'El tamaño del archivo no debe exceder los 5MB en el campo <b>CABEZA Y CUELLO.</b><br>';
    //   }
    // }
    // if(exam_reg_1.trim()=="") msg+="Debe ingresar el campo <b>CABEZA Y CUELLO - Cráneo</b><br>";
    // if(exam_reg_2.trim()=="") msg+="Debe ingresar el campo <b>CABEZA Y CUELLO - Cabello</b><br>";
    // if(exam_reg_3.trim()=="") msg+="Debe ingresar el campo <b>CABEZA Y CUELLO - Ojos</b><br>";
    // if(exam_reg_4.trim()=="") msg+="Debe ingresar el campo <b>CABEZA Y CUELLO - Nariz</b><br>";
    // if(exam_reg_5.trim()=="") msg+="Debe ingresar el campo <b>CABEZA Y CUELLO - Oídos</b><br>";
    // if(exam_reg_6.trim()=="") msg+="Debe ingresar el campo <b>CABEZA Y CUELLO - Boca</b><br>";
    // if(exam_reg_7.trim()=="") msg+="Debe ingresar el campo <b>CABEZA Y CUELLO - Cuello</b><br>";
    // if (file_mamas) { // Verificar si hay archivo seleccionado
    //   if (!file_mamas.type.startsWith('image/')) { // Validar que sea una imagen
    //     msg += 'Por favor selecciona una imagen válida en el campo <b>MAMAS.</b><br>';
    //   }
    //   if (file_mamas.size > 5 * 1024 * 1024) { // Validar el tamaño del archivo (máximo 5MB)
    //     msg += 'El tamaño del archivo no debe exceder los 5MB en el campo <b>MAMAS.</b><br>';
    //   }
    // }
    // if(exam_reg_8.trim()=="") msg+="Debe ingresar el campo <b>Mama Derecha</b><br>";
    // if(exam_reg_9.trim()=="") msg+="Debe ingresar el campo <b>Mama Izquierda</b><br>";
    // if (file_exam_reg_10) { // Verificar si hay archivo seleccionado
    //   if (!file_exam_reg_10.type.startsWith('image/')) { // Validar que sea una imagen
    //     msg += 'Por favor selecciona una imagen válida en el campo <b>TÓRAX Y PULMONES.</b><br>';
    //   }
    //   if (file_exam_reg_10.size > 5 * 1024 * 1024) { // Validar el tamaño del archivo (máximo 5MB)
    //     msg += 'El tamaño del archivo no debe exceder los 5MB en el campo <b>TÓRAX Y PULMONES.</b><br>';
    //   }
    // }
    // if(exam_reg_10.trim()=="") msg+="Debe ingresar el campo <b>TÓRAX Y PULMONES</b><br>";

    // if (file_exam_reg_11) { // Verificar si hay archivo seleccionado
    //   if (!file_exam_reg_11.type.startsWith('image/')) { // Validar que sea una imagen
    //     msg += 'Por favor selecciona una imagen válida en el campo <b>CARDIOVASCULAR.</b><br>';
    //   }
    //   if (file_exam_reg_11.size > 5 * 1024 * 1024) { // Validar el tamaño del archivo (máximo 5MB)
    //     msg += 'El tamaño del archivo no debe exceder los 5MB en el campo <b>CARDIOVASCULAR.</b><br>';
    //   }
    // }
    // if(exam_reg_11.trim()=="") msg+="Debe ingresar el campo <b>CARDIOVASCULAR</b><br>";

    // if (file_exam_reg_12) { // Verificar si hay archivo seleccionado
    //   if (!file_exam_reg_12.type.startsWith('image/')) { // Validar que sea una imagen
    //     msg += 'Por favor selecciona una imagen válida en el campo <b>ABDOMEN.</b><br>';
    //   }
    //   if (file_exam_reg_12.size > 5 * 1024 * 1024) { // Validar el tamaño del archivo (máximo 5MB)
    //     msg += 'El tamaño del archivo no debe exceder los 5MB en el campo <b>ABDOMEN.</b><br>';
    //   }
    // }
    // if(exam_reg_12.trim()=="") msg+="Debe ingresar el campo <b>ABDOMEN</b><br>";

    // if (file_exam_reg_13) { // Verificar si hay archivo seleccionado
    //   if (!file_exam_reg_13.type.startsWith('image/')) { // Validar que sea una imagen
    //     msg += 'Por favor selecciona una imagen válida en el campo <b>GENITO URINARIO.</b><br>';
    //   }
    //   if (file_exam_reg_13.size > 5 * 1024 * 1024) { // Validar el tamaño del archivo (máximo 5MB)
    //     msg += 'El tamaño del archivo no debe exceder los 5MB en el campo <b>GENITO URINARIO.</b><br>';
    //   }
    // }
    // if(exam_reg_13.trim()=="") msg+="Debe ingresar el campo <b>GENITO URINARIO</b><br>";
    // if(exam_reg_14.trim()=="") msg+="Debe ingresar el campo <b>TACTO RECTAL</b><br>";

    // if (file_neurologico) { // Verificar si hay archivo seleccionado
    //   if (!file_neurologico.type.startsWith('image/')) { // Validar que sea una imagen
    //     msg += 'Por favor selecciona una imagen válida en el campo <b>NEUROLÓGICO.</b><br>';
    //   }
    //   if (file_neurologico.size > 5 * 1024 * 1024) { // Validar el tamaño del archivo (máximo 5MB)
    //     msg += 'El tamaño del archivo no debe exceder los 5MB en el campo <b>NEUROLÓGICO.</b><br>';
    //   }
    // }
    // if(exam_reg_15.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Estado mental (conciencia-orientación)</b><br>";
    // if(exam_reg_16.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Funciones superiores (gnosis, praxis, lenguaje y memoria)</b><br>";
    // if(exam_reg_17.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Función motora (fuerza y tono muscular, movimientos involuntarios, coordinación y reflejos)</b><br>";
    // if(exam_reg_18.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Función sensitiva (superficie y profunda)</b><br>";

    // if(addParesCraneales == "true"){
    //   if(exam_reg_19.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - I Olfatorio</b><br>";
    //   if(exam_reg_20.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - II Óptico (agudeza visual, campos visuales, colore, fondo de ojo, presión intraocular)</b><br>";
    //   if(exam_reg_21.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - III IV VI Oculomotores (movimientos oculares, pupilas)</b><br>";
    //   if(exam_reg_22.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - V Trigésimo (reflejo corneal, sensibilidad facial, fuerza músculos masticadores)</b><br>";
    //   if(exam_reg_23.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - VII Facial (simetría facial, gusto)</b><br>";
    //   if(exam_reg_24.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - VIII Cocleovesticular (audición, equilibrio)</b><br>";
    //   if(exam_reg_25.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - IX y X Glosofaríngeo y Neumogástrico (voz, deglución, gusto)</b><br>";
    //   if(exam_reg_26.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - XI Espinal (esternocleidomastoideo)</b><br>";
    //   if(exam_reg_27.trim()=="") msg+="Debe ingresar el campo <b>NEUROLÓGICO - Pares craneales - XII Hipogloso (lengua)</b><br>";
    // }


    // if(temperatura=="") msg+="Debe ingresar la <b>Temperatura</b><br>";
    // if(saturacion=="") msg+="Debe ingresar la <b>Saturación</b><br>";
    // if(presion_arterial_x=="") msg+="Debe ingresar la <b>Presión Sistólica</b><br>";
    // if(presion_arterial_y=="") msg+="Debe ingresar la <b>Presión Diastólica</b><br>";
    // if(frecuencia_respiratoria=="") msg+="Debe ingresar la <b>Frecuencia Respiratoria</b><br>";
    // if(frec_cardiaca=="") msg+="Debe ingresar la <b>Frecuencia Cardiaca</b><br>";
    // // if(examen_clinico=="") msg+="Debe ingresar el <b>Examen Clínico General</b><br>";
    // // if(examen_clinico_regional=="") msg+="Debe ingresar el <b>Examen Clínico Regional</b><br>";
    // if(id_tipo_atencion=="")msg+="Debe ingresar un Tipo de Atencion<br>";
    // if(fecha_atencion=="")msg+="Debe ingresar una Fecha de Atencion<br>";
    // if(hora_atencion=="") msg += "Debe ingresar la Hora de Atención<br>";
    // // if(fecha_hora_atencion=="")msg+="Debe ingresar una Fecha de Atencion<br>";
    // if(id_servicio=="0")msg+="Debe ingresar un Servicio<br>";
    // if(id_sub_consultorio=="0")msg+="Debe ingresar un Consultorio<br>";
    // if(dni_beneficiario=="" && beneficiario=="")msg+="Debe buscar un Asegurado<br>";
    // //if(id_area_destino=="0")msg+="Debe ingresar un Area destino<br>";
    // if(id_cama=="0")msg+="Debe Seleccionar una cama<br>";
    var ind_diag = $('#tblDiagnostico tbody tr').length;
    let hayDiagnosticoPrincipal = false;
    if(ind_diag > 0){
      fila_dignosticos=$('#tblDiagnostico tbody').children('tr');
      for (i = 0; i < ind_diag; i++) {
        fila_dignostico=fila_dignosticos[i];
        var id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
        var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
        var id_categoria_diagnostico = $(fila_dignostico).find('.id_categoria_diagnostico').val();
        if(id_diagnostico == "")msg+="Debe ingresar el nombre de diagnostico<br>";
        if(id_tipo_diagnostico == "0")msg+="Debe ingresar el tipo de diagnostico<br>";
        if(id_categoria_diagnostico == "0")msg+="Debe ingresar si el diagnóstico es <b>Principal o Secundario</b><br>";
        if (!hayDiagnosticoPrincipal)
          if (id_categoria_diagnostico == "1") hayDiagnosticoPrincipal = true;
      }

      if (!hayDiagnosticoPrincipal) msg += "Debe ingresar mínimo un <b>Diagnóstico Principal</b><br>";
    }else{
      msg+="Debe ingresar minimo un diagnostico<br>";
      msg+="Debe ingresar el tipo de diagnostico<br>";
    }

    var ind_proced = $('#tblProcedimiento tbody tr').length;
    if(ind_proced > 0){
      fila_procedimientos=$('#tblProcedimiento tbody').children('tr');
      for (i = 0; i < ind_proced; i++) {
        fila_procedimiento=fila_procedimientos[i];
        var id_procedimiento = $(fila_procedimiento).find('.id_procedimiento').val();
        var cantidad = $('#procedimiento_cantidad'+i).val();
        var miSelect = $('#miSelect'+i).val();
        if(id_procedimiento == "") msg+="Debe ingresar el nombre del procedimiento<br>";
        if(cantidad == "")msg+="Debe ingresar la cantidad del procedimiento<br>";
        if(cantidad == 0)msg+="Debe ingresar la cantidad mayor a 0<br>";
        if(miSelect == "" || miSelect==null)msg+="Debe seleccionar el diagnostico para el procedimiento<br>";
      }
    }else{
      msg+="Debe ingresar minimo un procedimiento<br>";
    }

    // if(indicacion_terapeutica=="") msg+="Debe ingresar las <b>Indicaciones Terapeúticas</b><br>";
    // // if(examen_ayuda_diagnostica=="") msg+="Debe ingresar los <b>Exámenes de Ayuda Diagnóstica</b><br>";
    // if(procedimiento_medico_quirurgico=="") msg+="Debe ingresar los <b>Procedimientos Médico - Quirúrgicos</b><br>";
    // // if(interconsulta=="") msg+="Debe ingresar el campo <b>Interconsultas</b><br>";
    if(fecha_expedicion=="")msg+="Debe ingresar una Fecha de Expedicion<br>";
    //   if(addDerivarServicio == "true" && id_servicio_derivar=="0")msg+="Debe ingresar un Servicio para Derivar<br>";

    if(addRecetaVale == "true"){
      var ind = $('#example tbody tr').length;

      if(ind > 0){
        var nind = ind-1;

        var cmbMedicamento = $("#cmbMedicamento"+nind).val();
        if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == ""){
          $('#example .deleteFila').eq(ind - 1).parent().parent().remove();
          ind = ind-1;
        }
          fila_productosmedicos=$('#example tbody').children('tr');

        for (i = 0; i < ind; i++) {

            fila_medicamento=fila_productosmedicos[i];
            var cmbMedicamento = $(fila_medicamento).find('.cmbMedicamento').val();
            var tiempo_periodo = $(fila_medicamento).find('.tiempo_periodo').val();
            var posologia = $(fila_medicamento).find('.posologia').val();
            var valmedentre = $(fila_medicamento).find('.nro_med_solictados').val();
            var via_administracion =$(fila_medicamento).find('.id_via_administracion').val();
            var diag_prod =$(fila_medicamento).find('.miSelectProd').val();
            if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == "undefined")msg+="Falta seleccionar un producto (dispositivo médico)<br>";
            if(tiempo_periodo == "" || tiempo_periodo ==0)msg+="Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0<br>";
            if(via_administracion == 0 || via_administracion == "undefined" )msg+="Uno de los productos no cuenta con la via de administración<br>";
            if(posologia == "")msg+="Uno de los productos no cuenta con la posologia<br>";
            if(valmedentre == "" || valmedentre==0 )msg+="Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0<br>";
          }
      }else{
        msg+="Debe ingresar minimo un producto medico a la Receta Vale<br>";
      }
    }else{
      var selectElement  = document.querySelector('select[name="id_producto[]"]');
      if(selectElement ){
        selectElement.selectedIndex = -1;
      }

    }

    if (addExamenLab == "true") {
      const tblExamenLab_cantidad_tr = $('#tblExamenLab tbody tr').length;
      if (tblExamenLab_cantidad_tr > 0) {
        const ordenlab_tr = $('#tblExamenLab tbody').children('tr');

        let fila_ordenlab, id_examen_lab, cantidad_lab, id_diagnostico_lab, cpms_lab;

        for (let i = 0; i < tblExamenLab_cantidad_tr; i++) {
          fila_ordenlab = ordenlab_tr[i];

          id_examen_lab = $(fila_ordenlab).find('input[name="id_examen_lab[]"]').val();
          cantidad_lab = $(fila_ordenlab).find('input[name="examen_lab_cantidad[]"]').val();
          id_diagnostico_lab = $(fila_ordenlab).find('select[name="miSelectLab[]"]').val();
          cpms_lab = $(fila_ordenlab).find('input[name="cod_lab[]"]').val();

          if (id_examen_lab == "") {
            msg += "Debe registrar un examen de laboratorio<br>";
          } else {
            if (cantidad_lab == "") msg += `Debe registrar una cantidad para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
            if (cantidad_lab == 0) msg += `Debe registrar una cantidad mayor a 0 para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
            if (id_diagnostico_lab == "" || id_diagnostico_lab == null) msg += `Debe seleccionar el diagnóstico para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
          }
        }
      } else {
        msg += "Debe ingresar minimo un Examen de Laboratorio<br>";
      }
    } else {
      $('#tblExamenLab tbody').empty();
    }

    if (addExamenRad == "true") {
      const tblExamenRad_cantidad_tr = $('#tblExamenRad tbody tr').length;
      if (tblExamenRad_cantidad_tr > 0) {
        const orden_rad_tr = $('#tblExamenRad tbody').children('tr');

        let fila_orden_rad, id_examen_rad, cantidad_rad, id_diagnostico_rad;

        for (let i = 0; i < tblExamenRad_cantidad_tr; i++) {
          fila_orden_rad = orden_rad_tr[i];

          id_examen_rad = $(fila_orden_rad).find('input[name="id_examen_rad[]"]').val();
          cantidad_rad = $(fila_orden_rad).find('input[name="examen_rad_cantidad[]"]').val();
          id_diagnostico_rad = $(fila_orden_rad).find('select[name="miSelectRad[]"]').val();

          if (id_examen_rad == "") {
            msg += "Debe registrar un examen de imagenologia<br>";
          } else {
            if (cantidad_rad == "") msg += "Debe registrar una cantidad para el Examen de imagenología<br>";
            if (cantidad_rad == 0) msg += "Debe registrar una cantidad mayor a 0 para el Examen de imagenología<br>";
            if (id_diagnostico_rad == "" || id_diagnostico_rad == null) msg += "Debe seleccionar el diagnóstico para el Examen de imagenología<br>";
          }
        }
      } else {
        msg += "Debe ingresar minimo un Examen de Imagenología<br>";
      }
    } else {
      $('#tblExamenRad tbody').empty();
    }

    if (addInterconsulta == "true") {
      let tblInterconsulta_cantidad_tr = $('#tblInterconsulta tbody tr').length;
      if (tblInterconsulta_cantidad_tr > 0) {
        let interconsulta_tr = $('#tblInterconsulta tbody').children('tr');

        for (i = 0; i < tblInterconsulta_cantidad_tr; i++) {
          let fila_interconsulta = interconsulta_tr[i];

          let id_servicio_interconsulta = $(fila_interconsulta).find('[name="id_servicio_interconsulta[]"]').val();
          let motivo_interconsulta = $(fila_interconsulta).find('textarea[name="motivo_interconsulta[]"]').val();

          if (id_servicio_interconsulta == "") msg += "Debe seleccionar el Servicio de Interconsulta<br>";
          if (motivo_interconsulta == "") msg += "Debe ingresar el Motivo de la Interconsulta<br>";

          if (id_servicio_interconsulta == 27) {
            let id_establecimiento = $('#id_establecimiento').val();
            const cantidad = await obtenerCantidadInterconsulta({
              idEstablecimiento : id_establecimiento,
              idServicio : id_servicio_interconsulta,
              tipo: 3
            });

            if (cantidad >= 20) {
              msg += "No se puede adicionar la Interconsulta a <b>CARDIOLOGIA</b> porque supera la cantidad estimada por el Servicio.<br>";
            }
          }
        }
      }
    }

    var diagnosticosSeleccionados = $('#diagnosticosSeleccionados').val();

    if(addReferencia == "true"){
      var id_establecimiento_referencia = $('#id_establecimiento_referencia').val();
      var id_uci = $('#id_uci').val();
      if(id_uci==1)
        var id_uci_cv= $('#id_uci_cv').val();
      var id_ambulancia = $('#id_ambulancia').val();
      if(id_ambulancia==1){
        var id_ambulancia_ref = $('#id_ambulancia_ref').val();
        var id_tipo_ambulancia = $('#id_tipo_ambulancia').val();
      }
      var observacion_referencia = $('#observacion_referencia').val();
      var id_pasaje = $('#id_pasaje').val();
      var id_acompanante = $('#id_acompanante').val();
      var id_situacion = $('#id_situacion').val();
      var observacion_referencia = $('#observacion_referencia').val();
      if(id_establecimiento_referencia == "")msg+="Debe elegir un establecimiento<br>";
      if(id_uci == "")msg+="Debe especificar si quiere UCI<br>";
      if(id_uci==1)
        if(id_uci_cv == "")msg+="Debe especificar si quiere o no ventilador<br>";
      if(id_ambulancia == "")msg+="Debe especificar si quiere Ambulancia<br>";
      if(id_ambulancia==1){
        if(id_ambulancia_ref== "")msg+="Debe especificar si quiere de Ida o Vuelta<br>";
        if(id_tipo_ambulancia== "")msg+="Debe elegir el tipo de ambulancia<br>";
      }
      if(id_pasaje == "")msg+="Debe especificar si requiere pasaje<br>";
      if(id_acompanante == "")msg+="Debe especificar si quiere acompañante<br>";
      if(id_situacion == "")msg+="Debe elegir una situacion<br>";
      if(observacion_referencia == "")msg+="Debe escribir un relato o caracteristicas de la referencia<br>";

    }
    // VALIDACION DEL FORMULARIO GESTACION
    // const toogleGestacion = String($("#toogleGestacion").attr("aria-pressed")) === "true";
    // if (toogleGestacion) {
    //   const fecha_ultima_mestruacion = $("#fecha_ultima_mestruacion").val();
    //   const fecha_probable_parto = $("#fecha_probable_parto").val();
    //   const semanas_gestacion = $("#semanas_gestacion").val();
    //   const num_controles_prenatal = $("#num_controles_prenatal").val();

    //   if (!fecha_ultima_mestruacion.trim()) msg+="Debe ingresar la <b>Fecha de ultima Mestruación</b><br>";
    //   if (!fecha_probable_parto.trim()) msg+="Debe ingresar la <b>Fecha Probable de parto</b><br>";
    //   if (!semanas_gestacion.trim()) msg+="Debe ingresar las <b>Semanas de gestación</b><br>";
    //   if (!num_controles_prenatal.trim()) msg+="Debe ingresar los <b>Numeros de controles</b><br>";
    // }

    // // VALIDACION DEL FORMULARIO PUERPERA
    // const tooglePuerpera = String($("#tooglePuerpera").attr("aria-pressed")) === "true";
    // if (tooglePuerpera) {
    //   const fecha_parto = $("#fecha_parto").val();
    //   const hora_parto = $("#hora_parto").val();
    //   const tipo_parto = $("#tipo_parto").val();
    //   const complicacion_parto = $("#complicacion_parto").val();
    //   if (!fecha_parto.trim()) msg+="Debe ingresar la <b>Fecha de parto</b><br>";
    //   if (!hora_parto.trim()) msg+="Debe ingresar la <b>Hora de parto</b><br>";
    //   if (!tipo_parto.trim()) msg+="Debe seleccionar el <b>Tipo de parto</b><br>";
    //   if (!complicacion_parto.trim()) msg+="Debe ingresar la <b>complicación</b><br>";

    //   const countRowsTableNeonatos = $('#tableNeonatos tbody tr').length
    //   if (countRowsTableNeonatos <= 0) {
    //     msg+="Debe agregar como minimo el registro de un <b>Neonato</b><br>";
    //   } else {
    //     const validTableNeonatos = validateLastRowTableNeonatos();
    //     if (!validTableNeonatos) {
    //       msg+="Debe ingresar todo los campos del <b>Neonato</b><br>";
    //     }
    //   }
    // }

    // if (select_cambiar_cama != 0) {
    //   let fecha_inicio = $('#fecha_inicio').val();
    //   let appt = $('#appt').val();
    //   let area = $('#area').val();
    //   let id_cama = $('#id_cama').val();
    //   let id_cama_actual_paciente = $('#id_cama_actual_paciente').val();
    //   if (fecha_inicio == "") msg+="Debe ingresar la <b>Fecha de Rotación</b><br>";
    //   if (appt == "") msg+="Debe ingresar la <b>Hora de Rotación</b><br>";
    //   if (area == "0") msg+="Debe seleccionar el <b>Servicio de Destino</b><br>";
    //   if (id_cama == "0" || id_cama == null) msg+="Debe seleccionar la <b>Cama de Destino</b><br>";

    //   if (id_cama != "0" && id_cama != null) {
    //     if (id_cama == id_cama_actual_paciente) {
    //       msg += "<b>La cama seleccionada es la misma</b> donde se encuentra el paciente<br>";
    //     }
    //   }
    // }

    if(msg!=""){
      Swal.hideLoading();
      Swal.close();
      bootbox.alert({
        message: msg,
        size: 'large'
      });
      $('#btnPrestacion').prop("disabled",false);
      $('.loader').hide();
      return false;
    } else {
      Swal.close();

      return validateFrm();
    }
  }

function validateFrm() {
  let upss_cargo = $('#upss_cargo').val().trim();
  let servicio_medico = $('#servicio_medico').val().trim();
  let servicios = servicio_medico.split('#|#').map(item => item.trim());

  if (servicios.includes(upss_cargo)) {
      Swal.fire({
        title: '<h5><b>REGISTRAR HISTORIA CLÍNICA</b></h5>',
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onOpen: () => {
          Swal.showLoading();
        }
      });
      $("#frmPrestacion").submit();
  } else {
      bootbox.confirm({
          message: "¿Seguro de Guardar? EL UPSS A CARGO DEL PACIENTE NO COINCIDE CON SU SERVICIO. BAJO SU RESPONSABILIDAD.",
          buttons: {
              confirm: {
                  label: 'Sí',
                  className: 'btn-success'
              },
              cancel: {
                  label: 'No',
                  className: 'btn-danger'
              }
          },
          callback: function (result) {
              if (result === true) {
                    Swal.fire({
                    title: '<h5><b>REGISTRAR HISTORIA CLÍNICA</b></h5>',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onOpen: () => {
                      Swal.showLoading();
                    }
                  });
                  $("#frmPrestacion").submit();
              } else {
                  $('#btnPrestacion').prop('disabled', false);
              }
          }
      });
  }
  return false;
}

function fn_RegistrarNuevoCronograma(start,iIdMovimientoCompra,iIdTipoCompra) {
	$('#showAtencionesMensual').modal();
}
function modalDelegar___(start,iIdMovimientoCompra,iIdTipoCompra){

	$(".modal-dialog").css("width","80%");
	$('#openOverlayOpc').modal('show');
	$('#openOverlayOpc .modal-body').css('height', 'auto');
	$.ajax({
			url: "/modal_cita_medico/"+start,
			type: "GET",
			success: function (result) {
					$("#diveditpregOpc").html(result);
			}
	});
}
function modalDelegar(start){
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();;

	$.ajax({
			url: "/modal_cita_medico/"+id_ipress+"/"+id_servicio+"/"+start,
			type: "GET",
			success: function (result) {
					$("#divHorario").html(result);
			}
	});
}

var texto_marca_agua_historial_hspcu = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
function generar_marca_agua_historial_hspcu() {
    var overlay = document.getElementById('marca_agua_overlay_hist_hospitalizacion_cu');
    if (!overlay) return;
    overlay.innerHTML = '';
    var W = overlay.offsetWidth  || 800;
    var H = overlay.offsetHeight || 600;
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_historial_hspcu;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
}

$(document).ready(function () {
  var resizeMarcaAguaHistorialHspcuTimer = null;
  $(window).on('resize', function () {
    clearTimeout(resizeMarcaAguaHistorialHspcuTimer);
    resizeMarcaAguaHistorialHspcuTimer = setTimeout(function () {
      if ($('#tab4default').is(':visible')) generar_marca_agua_historial_hspcu();
    }, 250);
  });
});

function modalHistorial(){
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "/modal_historial_atenciones/"+dni_beneficiario,
			type: "GET",
			success: function (result) {
					$("#divHistorial").html(result);
					$('#tablemodalm').DataTable({
            "order": [],
						"sPaginationType": "full_numbers",
						"language": {"url": "/js/Spanish.json"},
					});
					generar_marca_agua_historial_hspcu();
			}
	});
}
function modalAntecedentes(){

	var dni_beneficiario = $("#dni_beneficiario").val();
	var id_tipo_atencion = $("#id_tipo_atencion").val();
	var id_cita = $("#id_cita").val();

	$.ajax({
			url: "/paciente_antecedentes/"+dni_beneficiario+'/'+id_tipo_atencion+'/'+id_cita,
			type: "GET",
			success: function (result) {
					$("#divAntecedentes").html(result);
			}
	});
}
function modalLaboratorio(){

	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "/modal_historial_laboratorio/"+dni_beneficiario,
			type: "GET",
			success: function (result) {
					$("#divLaboratorio").html(result);
					$('#tablemodalm1').DataTable({
						"sPaginationType": "full_numbers",

						"language": {"url": "/js/Spanish.json"},
					});
			}
	});
}
function modalRadiologico(){

	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "/modal_historial_radiologia/"+dni_beneficiario,
			type: "GET",
			success: function (result) {
					$("#divRadiologico").html(result);
					$('#tablemodalm2').DataTable({
						"sPaginationType": "full_numbers",

						"language": {"url": "/js/Spanish.json"},
					});
			}
	});
}
var texto_marca_agua_hist_interconsulta = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';

function generar_marca_agua_hist_interconsulta() {
  var overlay = document.getElementById('marca_agua_overlay_hist_interconsulta');
  if (!overlay) return;
  overlay.innerHTML = '';
  var W = overlay.offsetWidth  || 800;
  var H = overlay.offsetHeight || 600;
  var cols = Math.ceil(W / 220) + 1;
  var rows = Math.ceil(H / 100) + 1;
  for (var r = 0; r < rows; r++) {
    for (var c = 0; c < cols; c++) {
      var span = document.createElement('span');
      span.textContent = texto_marca_agua_hist_interconsulta;
      span.style.left = (c * 220 - 40) + 'px';
      span.style.top  = (r * 100 - 20) + 'px';
      overlay.appendChild(span);
    }
  }
}

$(document).ready(function () {
  $('a[href="#tab1007default"]').on('shown.bs.tab', function () {
    Swal.fire({
      icon: "warning",
      html: `<div style="padding: 0px 20px 20px 20px">
              <p style="font-size: 16px"><b>El historial de Interconsulta contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos y Ley.</b></p>
            </div>`,
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
      customClass: {
        container: 'swal-confidencialidad-interconsulta-hspcu-backdrop'
      }
    });

    generar_marca_agua_hist_interconsulta();

    $.ajax({
      url: '/auditoria-hc/registrar',
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        origen: 'Historia Clínica HSP(Hist. Interc.)-CI1',
        doc_buscado: $('#dni_beneficiario').val(),
        id_tipo_atencion: null
      }
    });
  });

  var resizeMarcaAguaHistInterconsultaTimer = null;
  $(window).on('resize', function () {
    clearTimeout(resizeMarcaAguaHistInterconsultaTimer);
    resizeMarcaAguaHistInterconsultaTimer = setTimeout(function () {
        if ($('#tab1007default').is(':visible')) generar_marca_agua_hist_interconsulta();
    }, 250);
  });
});

function modalInterconsulta(){

  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "/modal_historial_interconsulta/"+dni_beneficiario,
      type: "GET",
      success: function (result) {
          $("#divInterconsultaH").html(result);
          $('#tablemodalm5').DataTable({
            "sPaginationType": "full_numbers",

            "language": {"url": "/js/Spanish.json"},
          });
          generar_marca_agua_hist_interconsulta();
      }
  });
}

function updateSelectElements() {
  // Obtener todos los elementos <select> con la clase "select-diagnostico"
  let selectElements = document.querySelectorAll(".select-diagnostico");

  selectElements.forEach((selectElement) => {
    // Almacenar el valor actualmente seleccionado
    let currentValue = selectElement.value;

    // Eliminar las opciones actuales
    selectElement.innerHTML = "";


    // Agregar las opciones basadas en listIdDiagnosticoObjetos
    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement("option");
      option.value = item.id;
      option.textContent = item.nombre;
      selectElement.appendChild(option);
    });

    // Verificar si el valor seleccionado aún está disponible en las opciones
    let optionExists = Array.from(selectElement.options).some(
      (option) => option.value === currentValue
    );

    // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
    if (!optionExists) {
      selectElement.value = "";
    } else {
      // Si el valor seleccionado aún está disponible en las opciones, restablecer el valor seleccionado
      selectElement.value = currentValue;
      console.log()
    }
  });


}

function addNewArray(id_diagnostico) {
  if(  listIdDiagnosticoObjetos.findIndex( e  => e.id == id_diagnostico) ==-1 ){
  listIdDiagnostico.push(id_diagnostico);// solo es para validar si existen datos

    if (listIdDiagnostico.length > 0) {
      let codigo = JSON.stringify(id_diagnostico);
      // Realizar la petición AJAX usando el código id_diagnostico
      $.ajax({
          "dataType": 'json',
          "type": "POST",
          "url": "/obtenerdatosDiagnosticos",
          "data":{
            codigo : codigo
          },
          "success": function (result) {
            listIdDiagnosticoObjetos.push(result.objeto);
            console.log(JSON.stringify(listIdDiagnosticoObjetos));
            updateSelectElements();
          },
          "error": function (msg, textStatus, errorThrown) {
          }
      });
    }
  }
}

function AddDiagnostico(){
	var newRow = "";
	var ind = $('#tblDiagnostico tbody tr').length;

	if(ind > 0){
		$('.id_diagnostico').each(function(){
			var ind_tmp = $(this).data("id");
			if(ind_tmp => ind){
				ind=Number(ind_tmp)+1;
			}
		});

		fila_medicamentos=$('#tblDiagnostico tbody').children('tr');
		rowIndex=$('#hidden_rowIndex').val();
		var idval = ind - 1;
		fila_medicamento=fila_medicamentos[idval];
  }

	var item_tipo_diagnostico = '';
	item_tipo_diagnostico+='<option value="0">--Seleccionar--</option>';
	item_tipo_diagnostico+='<option value="1">PRESUNTIVO</option>';
	item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';
	item_tipo_diagnostico+='<option value="3">REITERATIVO</option>';
  let item_categoria_diagnostico = '';
	item_categoria_diagnostico+='<option value="0">--Seleccionar--</option>';
	item_categoria_diagnostico+='<option value="1">PRINCIPAL</option>';
	item_categoria_diagnostico+='<option value="2">SECUNDARIO</option>';
	newRow +='<tr>';
	newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="44" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
	newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_saludpol' + ind +'" name="flag_saludpol[]" value="" data-id='+ind+'></td>';
	newRow +='<td><select tabindex="45" class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico'+ind+'" name="id_tipo_diagnostico[]" style="margin:0 0 0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
  newRow +='<td><select tabindex="45" class="form-control id_categoria_diagnostico" id="cmbCategoriaDiagnostico'+ind+'" name="id_categoria_diagnostico[]" style="margin:0 0 0 20px;width:150px">'+item_categoria_diagnostico+'</select></td>';
	newRow +='<td><button type="button" tabindex="46" class="btn btn-danger deleteFila btn-xs" style="margin-left:20px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='</tr>';
	$('#tblDiagnostico tbody').append(newRow);
  var sexo	= $('#nomsexo').val();
	if(sexo=='M'){
		genero=1;
	}
	else{
		if(sexo=='F'){
			genero=2;
		}
	}

	$("#nombre_diagnostico"+ind).autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
			$.ajax({
				url: '/obtener_diagnostico_genero/' + request.term + '/'+ genero,
				dataType: "json",
				success: function(data)  {
					let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
          response(filtro);
				}
			})

		},select:  function(e, ui) {
			var id_diagnostico = ui.item.id;
      var flag_saludpol = ui.item.flag_saludpol;
      $("#id_diagnostico"+ind).val(id_diagnostico);
      $("#flag_saludpol"+ind).val(flag_saludpol);
      $("#nombre_diagnostico"+ind).prop("readonly",true);
		}
	});

	$("#nombre_diagnostico"+ind).on('blur', function() {
    const diagnostico = $("#nombre_diagnostico"+ind).val();
    const flag_saludpol = $("#flag_saludpol"+ind).val();
    const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
    const codigoDiagnostico = diagnostico.split("-")[0].trim();
		var id_diagnostico = $("#id_diagnostico"+ind).val();
		if(id_diagnostico == ""){
			bootbox.alert("Falta ingresar el diagnostico en esta fila");
			$("#nombre_diagnostico"+ind).val("");
			return false;
		}else{
      if (firstLetterCode == "Z" || flag_saludpol == 0) {
        bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado por SALUDPOL.");
      }
      if (codigoDiagnostico == "Y66.X") {
        bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado.");
      }
      addNewArray(id_diagnostico);
    }
	});

}
const procedimientosHospitalariosUnicosPorDiaIds = [11104, 11098, 11119, 11143, 11162, 11144];

function AddProcedimiento() {
  var newRow = "";
  var ind = $('#tblProcedimiento tbody tr').length;

  if (ind > 0) {
    $('.id_procedimiento').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp >= ind) {
        ind = Number(ind_tmp) + 1;
      }
    });

    fila_medicamentos = $('#tblProcedimiento tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];
  }

  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' + ind +
    '" name="id_procedimiento[]" value="" data-id=' + ind + '></td>';
  newRow +=
    '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
    ind + '" name="procedimiento_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';

  $('#tblProcedimiento tbody').append(newRow);

  // Poblar el select con opciones de diagnóstico
  const selectElement = document.getElementById('miSelect' + ind);
  let defaultOption = document.createElement('option');
 //si entra la primera vez
  var i = 0;
  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id;
    option.textContent = item.nombre;
    if (i == 0) {
      option.setAttribute("selected", "selected");
      i++;
    }
    selectElement.appendChild(option);
  });

  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  var genero = (sexo == 'M') ? 1 : 2;

  // Configuración del autocompletado
  $("#nombre_procedimiento" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '/obtener_procedimiento_convenio_tipo_atencion/' + request.term + '/' + nivel + '/' + genero + '/' + 3,
        dataType: "json",
        success: function(data) {
          let listaProcedimientoSelecionado = $(".id_procedimiento").map(function() {
            return parseInt($(this).val());
          }).get();

          let existeProcedimientoTipoHospitalarioUnico = procedimientosHospitalariosUnicosPorDiaIds.some(procedimientoId =>
            listaProcedimientoSelecionado.includes(procedimientoId));

          let filtro = data.filter((element) => !listaProcedimientoSelecionado.includes(element.id));
          if (existeProcedimientoTipoHospitalarioUnico) {
            filtro = filtro.filter((element) => !procedimientosHospitalariosUnicosPorDiaIds.includes(element.id));
          }
          response(filtro);
        }
      });
    },
    select: function(e, ui) {
      var id_procedimiento = ui.item.id;
      $("#id_procedimiento" + ind).val(id_procedimiento);
      $("#nombre_procedimiento" + ind).prop("readonly", true);
    }
  });

  // Validación cuando el input pierde el foco
  $("#nombre_procedimiento" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    if (!id_procedimiento) {
      bootbox.alert("Falta ingresar el procedimiento en esta fila");
      $(this).val(""); // Limpiar input si no se seleccionó nada
    }
  });

  // Validación de cantidad
  $("#procedimiento_cantidad" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    var cantidad = $("#procedimiento_cantidad" + ind).val();

    if (!id_procedimiento) {
      bootbox.alert("Falta ingresar la cantidad en este procedimiento");
      $(this).val("");
      return false;
    }

    if (parseFloat(cantidad) <= 0) {
      bootbox.alert("Por favor, ingrese una cantidad mayor a 0");
      $(this).val("");
      return false;
    }
  });
}


async function validarReglas(ind,id_procedimiento,genero,cod_upss,cod_cpms,id_tipo_atencion) {
    let valido = false; // Inicializamos como falso

    // Función para limpiar campos
    function limpiarCampos(ind) {
        $("#id_procedimiento" + ind).val('');
        $("#nombre_procedimiento" + ind).val('');
    }

    // Función para asignar procedimiento
    function asignarProcedimiento(ind, id_procedimiento) {
        $("#id_procedimiento" + ind).val(id_procedimiento);
        $("#nombre_procedimiento" + ind).prop("readonly", true);
    }

    try {
        // Regla 13
        if(cod_upss>0){
          const regla13 = await $.ajax({
              url: '/regla13/' + genero + '/' + cod_upss + '/' + cod_cpms + '/' + id_tipo_atencion,
              dataType: "json"
          });

          if (regla13.regla === 'INVALIDO') {
              limpiarCampos(ind);
              return; // Detener si Regla 13 es inválida
          } else {
              valido = true; // Regla 13 válida
              asignarProcedimiento(ind, id_procedimiento);
          }
        }

        // Si valido ya es true, no se ejecuta Regla 16
        if (!valido) {
            const regla16 = await $.ajax({
                url: '/regla16/' + cod_cpms + '/' + id_tipo_atencion,
                dataType: "json"
            });

            if (regla16.regla === 'INVALIDO') {
                limpiarCampos(ind);
                return; // Detener si Regla 16 es inválida
            } else {
                asignarProcedimiento(ind, id_procedimiento);
            }
        }
    } catch (error) {
        console.error('Error al validar reglas:', error);
    }
}

function buscarCodUpss() {
  var selectedOption = $('#id_upss_encargado_regularizacion').find('option:selected');
  var cod_upss = selectedOption.data('cod_upss');
  var codigo_upss = $('#codigo_upss').val();
  if (cod_upss !== undefined && cod_upss !== null && cod_upss !== "") {
    return cod_upss;
  } else if(codigo_upss !== undefined && codigo_upss !== null && codigo_upss !== ""){
    return codigo_upss;
  }else{
    Swal.fire({
        icon: 'error',
        title: 'Debe seleccionar un Codigo de UPSS',
        showConfirmButton: false,
        timer: 3000
    });
  }
}

  async function AddInterconsulta2() {
    let data_del_dia;
    let mostrar_servicio = false;
    let horario_sin_bloqueo = false;

    try {
      $.ajax({
        url: "{{ url('/') }}/get_datos_dia_actual",
        dataType: 'json',
        async: false,
        success: function(response) {
          data_del_dia = response;
        },
        error: function(xhr, status, error) {
          // console.error("Error en AJAX:", status, error);
          // console.log("Respuesta del servidor:", xhr.responseText);
          bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error obtener información del día.<br><br>" + xhr.responseText);
        }
      });

      if (data_del_dia.dia_semana_numero != DIA_SEMANA_DOMINGO && !data_del_dia.es_feriado) {

        const horaActual = new Date();
        const [horas, minutos] = data_del_dia.hora_actual.split(':').map(Number);
        horaActual.setHours(horas, minutos, 0, 0);

        // Definir la hora límite inicio (07:30)
        const horaLimiteInicio = new Date();
        horaLimiteInicio.setHours(07, 0, 0, 0);

        // Definir la hora límite fin (13:30)
        const horaLimiteFin = new Date();
        horaLimiteFin.setHours(12, 0, 0, 0);

        if (horaActual < horaLimiteInicio || horaActual > horaLimiteFin) {
            let is_estable = $("#is_estable").val();

            if (is_estable == 0) { // INESTABLE
              mostrar_servicio = true;

            } else { // ESTABLE
              // Ahi nomas se queda
              // No aparece Servicio ni Motivo
              bootbox.alert("<br><span class='text-warning'><i class='fa fa-exclamation-circle'></i></span> Esta Interconsulta debe realizarse de Lunes a Sábados <b>de 07:00 am</b> hasta <b>12:00pm</b><br>De lo contrario registrar en el Plan de Trabajo.");
            }

        } else {
          // La Interconsulta se registrar normal sin validaciones
          // Esta en el rango de 7 a 12 Lunes a sabado
          horario_sin_bloqueo = true;
          mostrar_servicio = true;
        }
      } else {
        // Si es Domingo
        let is_estable = $("#is_estable").val();
        if (String(is_estable) == "0") { // INESTABLE
          mostrar_servicio = true;

        } else { // ESTABLE
          // Ahi nomas se queda
          // No aparece Servicio ni Motivo
          bootbox.alert("<br><span class='text-warning'><i class='fa fa-exclamation-circle'></i></span> Esta Interconsulta debe realizarse de <b>Lunes a Sábados</b> de 07:00 am hasta 12:00pm.");
        }
      }
    } catch (error) {
      bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error al validar el horario.<br><br>" + error);
    }

    if (mostrar_servicio) {
      var newRow = "";
      var ind = $('#tblInterconsulta tbody tr').length;

      if(ind > 0){
        $('.id_servicio_interconsulta').each(function(){
          var ind_tmp = $(this).data("id");
          if(ind_tmp => ind){
            ind=Number(ind_tmp)+1;
          }
        });

        fila_medicamentos=$('#tblInterconsulta tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento=fila_medicamentos[idval];

      }

      let interconsulta_servicio_items = "";
      $('#cmbInterconsultaServiciosTemp option').each(function() {
        interconsulta_servicio_items += '<option value="' + $(this).val() + '" data-has_guardia="' + $(this).data('has_guardia') + '" >' + $(this).html() + '</option>';
      });

      // Inicializa la variable item_OTORRINO para los options
      var item_OTORRINO = '<option value="0">--Seleccionar--</option>';
      var item_DIAGNOSTICO = '<option value="0">--Seleccionar--</option>';

      // AJAX para OTORRINOLARINGOLOGÍA
      $.ajax({
          url: '/../cargar_upsses',
          method: 'GET',
          success: function (data) {
              $.each(data, function (index, item) {
                  item_OTORRINO += '<option value="' + item.codigo + '">' + item.descripcion_upss + '</option>';
              });
              $("#select_OTORRINO" + ind).html(item_OTORRINO);
          }
      });

      // AJAX para DIAGNOSTICO E IMAGENES
      $.ajax({
          url: '/../cargar_ups_diagnostico',
          method: 'GET',
          success: function (data) {
              $.each(data, function (index, item) {
                  item_DIAGNOSTICO += '<option value="' + item.codigo + '">' + item.descripcion_upss + '</option>';
              });
              $("#select_DIAGNOSTICO" + ind).html(item_DIAGNOSTICO);
          }
      });

      newRow +='<tr>';
      newRow +='  <td>';
      newRow +='    <div class="panel panel-info panel-bordered panel-sm">';
      newRow +='        <div class="panel-body">';
      newRow +='          <div class="form-group">';
      newRow +='            <div class="col-md-2">';
      newRow +='              <label>SERVICIO:</label>';
      newRow +='            </div>';
      newRow +='            <div class="col-md-4" >';
      newRow +='              <select class="form-control cmbInterconsultaServicios input-sm" id="id_servicio_interconsulta' + ind + '" ind="' + ind +'" name="id_servicio_interconsulta[]" style:"width:100%" data-guardia="">' + interconsulta_servicio_items + '</select>'
      newRow +='            </div>';
      newRow +='            <div class="col-md-4">'; // Inicio select Otorrino
      newRow +='              <select id="select_OTORRINO' + ind + '" class="form-control" style="display:none"></select>';
      newRow +='              <input type="hidden" id="hidden_select_OTORRINO' + ind + '" name="hidden_select_OTORRINO[]" value="">';
      newRow +='            </div>'; // Fin select Otorrino
      newRow +='            <div class="col-md-4">'; // Inicio select Diagnostico
      newRow +='              <select id="select_DIAGNOSTICO' + ind + '" class="form-control" style="display:none"></select>';
      newRow +='              <input type="hidden" id="hidden_select_DIAGNOSTICO' + ind + '" name="hidden_select_DIAGNOSTICO[]" value="">';
      newRow +='            </div>'; // Fin select Diagnostico
      newRow +='          </div>'; // .form-group
      newRow +='          <div class="form-group" id="panel_interconsulta_resumen_' + ind + '" style="display:none">';
      newRow +='            <div class="col-md-12">';
      newRow +='              <label>RESUMEN:</label>';
      newRow +='              <textarea class="form-control" cols="5" id="motivo_interconsulta'+ind+'" name="motivo_interconsulta[]" placeholder="Relato m&eacute;dico o caracteristicas de la interconsulta"></textarea>';
      newRow +='            </div>';
      newRow +='          </div>'; // .form-group
      newRow +='          <div class="form-group" id="panel_interconsulta_personal_coordinacion_' + ind + '" style="display:none">';
      newRow +='            <div class="col-md-2">';
      newRow +='              <label>PERSONAL EN COORDINACIÓN:</label>';
      newRow +='            </div>';
      newRow +='            <div class="col-md-10" >';
      newRow +='              <span id="span_interconsulta_personal_coodinacion_' + ind + '"></span>';
      newRow +='              <input type="hidden" name="interconsulta_personal_coodinacion[]" id="interconsulta_personal_coodinacion_' + ind + '" value="">';
      newRow +='            </div>';
      newRow +='          </div>'; // .form-group
      newRow +='        </div>'; // .panel-body
      newRow +='    </div>'; // .panel
      newRow +='  </td>';
      newRow +='  <td>';
      newRow +='    <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>';
      newRow +='  </td>';
      newRow +='</tr>';

      var id_ipress     = $('#id_establecimiento').val();

      $('#tblInterconsulta tbody').append(newRow);
      $("#id_servicio_interconsulta" + ind).select2({max_selected_options: 4}).focus();

        $("#nombre_servicio_interconsulta" + ind).on('keyup change', function () {
          checkSelectInterconsulta(ind);
      });

      function checkSelectInterconsulta(ind) {
          var inputValue = $("#nombre_servicio_interconsulta" + ind).val().toUpperCase().trim();

          if (inputValue === "OTORRINOLARINGOLOGÍA") {
              $("#select_OTORRINO" + ind).show();
              $("#select_OTORRINO" + ind).val('0');
          } else {
              $("#select_OTORRINO" + ind).hide();
              $("#hidden_select_OTORRINO" + ind).val('');
          }

          if (inputValue === "DIAGNOSTICO E IMAGENES") {
              $("#select_DIAGNOSTICO" + ind).show();
              $("#select_DIAGNOSTICO" + ind).val('0');
          } else {
              $("#select_DIAGNOSTICO" + ind).hide();
              $("#hidden_select_DIAGNOSTICO" + ind).val('');
          }
      }

      // Capturar cambios en select OTORRINO
      $("#select_OTORRINO" + ind).on("change", function () {
          var selectedValue = $(this).val();
          $("#hidden_select_OTORRINO" + ind).val(selectedValue);
      });

      // Capturar cambios en select DIAGNOSTICO
      $("#select_DIAGNOSTICO" + ind).on("change", function () {
          var selectedValue = $(this).val();
          $("#hidden_select_DIAGNOSTICO" + ind).val(selectedValue);
      });

      $("#id_servicio_interconsulta" + ind).on("change", async function(e) {
        let flagx = 0;
        cmb = $(this);
        let interconsulta_servicio_id = $("#id_servicio_interconsulta" + ind).val();

        if (interconsulta_servicio_id != "") {

          $('.cmbInterconsultaServicios').each(function() {
            var ind_tmp = $(this).val();
            if ($(this).val() == interconsulta_servicio_id) flagx++;
          });

          if (flagx > 1) {
            bootbox.alert("El Servicio ya ha sido ingresado.");
            $("#id_servicio_interconsulta" + ind).val("").trigger("change");
            return false;

          } else {

            if (interconsulta_servicio_id == 27) {
              const idEstablecimiento = $('#id_establecimiento').val();

              const cantidad = await obtenerCantidadInterconsulta({
                idEstablecimiento : idEstablecimiento,
                idServicio : interconsulta_servicio_id,
                tipo: 3
              });

              if (cantidad >= 20) {
                bootbox.alert('No se puede adicionar esta interconsulta porque supera la cantidad estimada por el Servicio.');
                $('#id_servicio_interconsulta' + ind).val('').trigger('change');
                return;
              }
            }

            if (horario_sin_bloqueo) { // Horario entre las 7 - 12 pm

              $("#panel_interconsulta_resumen_" + ind).css('display', 'block');
              let $select = $("#id_servicio_interconsulta" + ind);

              // Bloquear visualmente Select2
              $select.next('.select2-container').css({
                'pointer-events': 'none',
                'background-color': '#eee',
                'opacity': 0.8
              });

            } else {
            let has_guardia = $(this).find('option:selected').data('has_guardia');

            if (has_guardia == 1) { // El Servicio si hace guardia
              Swal.fire({
                title: 'MENSAJE',
                text: 'Recuerde comunicar a la especialidad solicitante del registro de la Interconsulta.',
                icon: 'info',
                confirmButtonText: 'Aceptar'
              });

              $("#panel_interconsulta_resumen_" + ind).css('display', 'block');

              let $select = $("#id_servicio_interconsulta" + ind);

              // Bloquear visualmente Select2
              $select.next('.select2-container').css({
                'pointer-events': 'none',
                'background-color': '#eee',
                'opacity': 0.8
              });

            } else if (has_guardia == 0) { // El Servicio no hace guardia
              Swal.fire({
                title: 'MENSAJE DE COORDINACIÓN',
                html: 'Para continuar con el registro de la interconsulta, deberá coordinar con el Coronel de Guardia, y registrar el nombre del personal médico que responderá la interconsulta',
                input: 'text',
                inputLabel: 'Datos del Médico',
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
                  $("#panel_interconsulta_resumen_" + ind).css('display', 'block');
                  $("#panel_interconsulta_personal_coordinacion_" + ind).css('display', 'block');
                  $("#span_interconsulta_personal_coodinacion_" + ind).text(datosPersonalCoordinacion);
                  $("#interconsulta_personal_coodinacion_" + ind).val(datosPersonalCoordinacion);

                  let $select = $("#id_servicio_interconsulta" + ind);

                  // Bloquear visualmente Select2
                  $select.next('.select2-container').css({
                    'pointer-events': 'none',
                    'background-color': '#eee',
                    'opacity': 0.8
                  });

                } else if (result.isDismissed) {
                  $("#id_servicio_interconsulta" + ind).val("").trigger("change");
                }
              });
            }
            } // fin condicion horario_sin_bloqueo
          }
        }

      });
    }
  }

  async function obtenerCantidadInterconsulta({ idEstablecimiento, idServicio, tipo = 3 }) {
    const url = `{{ url("/") }}/interconsulta_obtener_cantidad/${idEstablecimiento}/${idServicio}/${tipo}`;

    const resp = await fetch(url, {
      method: 'GET',
      headers: { 'Accept': 'application/json' }
    });

    if (!resp.ok) throw new Error('Error al consultar interconsulta_obtener_cantidad');
    const data = await resp.json();
    return Number(data?.cantidad ?? 0);
  }

async function validarCantidadInterconsulta(ind, id_servicio) {

  function limpiarCampos(ind) {
    $("#id_servicio_interconsulta" + ind).val('');
    $("#nombre_servicio_interconsulta" + ind).val('');
    $("#nombre_servicio_interconsulta" + ind).prop("readonly", false);
  }

  // if (id_servicio == 27) {
    let id_establecimiento = $('#id_establecimiento').val();
    try {
      const is_feriado = await $.ajax({
        url: "{{ url('validar_fecha_actual_es_feriado') }}/",
        dataType: 'json',
      });

      if (is_feriado) {
        showValidacionCantInterconsulta = true;
        bootbox.alert("No se puede adicionar interconsultas en feriados para <b>pacientes estables</b>.");
        limpiarCampos(ind);
        return;

      } else {
      const response = await $.ajax({
        url: "{{ url('interconsulta_obtener_cantidad') }}/" + id_establecimiento + "/" + id_servicio + "/3",
        dataType: 'json',
      });

      if (response.dia_semana_numero != DIA_SEMANA_DOMINGO) {
        // if (id_servicio == 27) {
          const horaActual = new Date();
          const [horas, minutos] = response.hora_actual.split(':').map(Number);
          horaActual.setHours(horas, minutos, 0, 0);

          // Definir la hora límite inicio (07:30)
          const horaLimiteInicio = new Date();
          horaLimiteInicio.setHours(07, 0, 0, 0);

          // Definir la hora límite fin (13:30)
          const horaLimiteFin = new Date();
          horaLimiteFin.setHours(12, 0, 0, 0);

          if (horaActual < horaLimiteInicio || horaActual > horaLimiteFin) {
            // if (parseInt(response.cantidad) >= 17) {
              showValidacionCantInterconsulta = true;
              bootbox.alert("No se puede adicionar esta interconsulta porque está fuera del rango de horario <b>(07:00 AM - 12:00 PM)</b> establecido para pacientes Estables.");
              limpiarCampos(ind);
              return;
            // }
          } /*else {
            showValidacionCantInterconsulta = true;
            bootbox.alert("No se puede adicionar esta interconsulta porque <b>esta fuera del horario (1 PM)</b> establecido por el servicio para solicitar Interconsultas.");
            limpiarCampos(ind);
            return;
          }*/
        // }
      } else {
        showValidacionCantInterconsulta = true;
        bootbox.alert("El registro de Interconsultas para pacientes estables es de Lunes a Sábado.");
        limpiarCampos(ind);
        return;
      }
      } // .is_feriado
    } catch (error) {
      bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error al validar el horario.");
    }
  // }
}


function asignarValoresSelect(ui,ind){
  var id_examen_lab = ui.item.id;
      let codigo_procedimiento = ui.item.codigo_procedimiento;
      let id_categoria_examen_lab = ui.item.id_categoria;
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#id_categoria_examen_lab" + ind).val(id_categoria_examen_lab);
      $("#cod_lab" + ind).val(ui.item.codigo_procedimiento);
      $("#nombre_examen_lab" + ind).val(ui.item.value);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
      validaExamenSeleccionado($("#id_examen_lab" + ind).val());
      consultaWsResultExamenSeleccionado($("#id_examen_lab" + ind).val());
      if (id_categoria_examen_lab == "9") {
        mostrarBotonParaTipoDeMuestraMicrobiologia(ind);
      }
      // SOLICITUD DE EXAMEN PARA HEMOCULTIVO
      if (codigo_procedimiento == '87040') {
        let fecnacafiliado = $('#fecnacafiliado').val();
        let partesFecha = fecnacafiliado.split('/');
        let fechaFormateada = `${partesFecha[2]}-${partesFecha[1]}-${partesFecha[0]}`;
        // Convertir la fecha de nacimiento a un objeto Date
        let fechaNacimiento = new Date(fechaFormateada);
        console.log(`Fecha nacimiento : ${fechaNacimiento}`);

        // Obtener la fecha actual
        let fechaActual = new Date();
        console.log(`Fecha actual : ${fechaActual}`);

        // Calcular la diferencia de años entre la fecha de nacimiento y la fecha actual
        let edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
        // Ajustar la edad si todavía no se ha alcanzado el cumpleaños en el año actual
        if (fechaActual.getMonth() < fechaNacimiento.getMonth() || (fechaActual.getMonth() === fechaNacimiento.getMonth() && fechaActual.getDate() < fechaNacimiento.getDate())) {
            edad--;
          }
          console.log(`Edad : ${edad}`);
        // Validar la edad y mostrar los botones correspondientes
        if (edad <= 14) {
          // SOLICITUD DE EXAMEN PARA HEMOCULTIVO NEONATO Y PEDIATRÍA
          mostrarBotonParaSolicitudHemocultivoPedriatria(ind);
        } else {
          // SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO
          mostrarBotonParaSolicitudHemocultivoAdulto(ind);
        }
      }
      // SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS
      if (codigo_procedimiento == '87163') {
        mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos(ind);
      }
}

  function obtenerIndiceTblExamenLab() {
    let indice = $('#tblExamenLab tbody tr').length;

    $('.id_laboratorio').each(function() {
      let ind_tmp = $(this).data("id");
      if (ind_tmp >= indice) {
        indice = Number(ind_tmp) + 1;
      }
    });

    return indice;
  }

function AddExamenLab() {
  var newRow = "";
  var ind = $('#tblExamenLab tbody tr').length;
	var tabindex = 71;
  if (ind > 0) {
    ind = obtenerIndiceTblExamenLab();

    fila_medicamentos = $('#tblExamenLab tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];

  }
  const id_nivel = $("#id_nivel").val();
  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control cod_lab" id="cod_lab' + ind +'" name="cod_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control id_categoria" id="id_categoria_examen_lab' + ind +'" name="id_categoria_examen_lab[]" value="" data-id='+ind+'></td>';
  if (id_nivel && parseInt(id_nivel) < 3) {
    newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  }
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico" id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
  // ====================== Tipo de Muestra Inicio ===============================================
  newRow +='<td>';
    newRow +='<button type="button" class="btn btn-success btn-xs tipoMuestraMicrobiologia" id="tipoMuestraMicrobiologia' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Tipo de muestra</button>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="descripcion_tipo_muestra' + ind +'" name="descripcion_tipo_muestra[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_descripcion_tipo_muestra' + ind +'" name="flag_descripcion_tipo_muestra[]" value="0" />';
    newRow +='<button type="button" class="btn btn-warning btn-xs editarTipoMuestraMicrobiologia" id="editarTipoMuestraMicrobiologia' + ind +'" data-id='+ind+' style="display:none; margin-left:4px"><i class="fa fa-edit">&nbsp;Tipo de muestra</button>';
    newRow +='</td>';
    // ====================== Tipo de Muestra Fin ===============================================
    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO ADULTO Inicio ===============================================
    newRow +='<td>';
    newRow +='<button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoAdulto" id="solicitudExamenEspecializadoHemocultivoAdulto' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="descripcion_examen' + ind +'" name="descripcion_examen[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fecha_tiempo_enfermedad' + ind +'" name="des_adulto_fecha_tiempo_enfermedad[]" value="" data-id='+ind+' />';
    /** ESCALA DE GLASGOW **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_ocular' + ind +'" name="des_adulto_respuesta_ocular[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_verbal' + ind +'" name="des_adulto_respuesta_verbal[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_respuesta_motora' + ind +'" name="des_adulto_respuesta_motora[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_total_escala_glasgow' + ind +'" name="des_adulto_total_escala_glasgow[]" value="" data-id='+ind+' />';
    /** SIGNOS VITALES **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_presion_arterial_x' + ind +'" name="des_adulto_presion_arterial_x[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_presion_arterial_y' + ind +'" name="des_adulto_presion_arterial_y[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frecuencia_cardiaca' + ind +'" name="des_adulto_frecuencia_cardiaca[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frecuencia_respiratorio' + ind +'" name="des_adulto_frecuencia_respiratorio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_temperatura' + ind +'" name="des_adulto_temperatura[]" value="" data-id='+ind+' />';
    /** EXÁMENES DE LABORATORIO **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_creatinina' + ind +'" name="des_adulto_creatinina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_diuresis' + ind +'" name="des_adulto_diuresis[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_procalcitonina' + ind +'" name="des_adulto_procalcitonina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_pcr' + ind +'" name="des_adulto_pcr[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fiO2' + ind +'" name="des_adulto_fiO2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucocitos' + ind +'" name="des_adulto_leucocitos[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_plaquetas' + ind +'" name="des_adulto_plaquetas[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_bilirrubina' + ind +'" name="des_adulto_bilirrubina[]" value="" data-id='+ind+' />';
    /** ANTECEDENTES PATOLÓGICOS **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_ima' + ind +'" name="des_adulto_ima[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_icc' + ind +'" name="des_adulto_icc[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_evp' + ind +'" name="des_adulto_evp[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_ecv' + ind +'" name="des_adulto_ecv[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_demencia' + ind +'" name="des_adulto_demencia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_dm' + ind +'" name="des_adulto_dm[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_diabetes_lesion_organica' + ind +'" name="des_adulto_diabetes_lesion_organica[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_hemiplejia' + ind +'" name="des_adulto_hemiplejia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_epoc' + ind +'" name="des_adulto_epoc[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_patologia_tejido_conectivo' + ind +'" name="des_adulto_patologia_tejido_conectivo[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_enfermedad_ulcerosa' + ind +'" name="des_adulto_enfermedad_ulcerosa[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_patologia_renal_moderada_o_grave' + ind +'" name="des_adulto_patologia_renal_moderada_o_grave[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_neoplasias' + ind +'" name="des_adulto_neoplasias[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_neoplasias_especifique' + ind +'" name="des_adulto_neoplasias_especifique[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucemias' + ind +'" name="des_adulto_leucemias[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_leucemias_especifique' + ind +'" name="des_adulto_leucemias_especifique[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_linfomas_malignos' + ind +'" name="des_adulto_linfomas_malignos[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_metastasis_colida' + ind +'" name="des_adulto_metastasis_colida[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_sida' + ind +'" name="des_adulto_sida[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_1' + ind +'" name="des_adulto_antecedentes_patologicos_otros_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_1_especifique' + ind +'" name="des_adulto_antecedentes_patologicos_otros_1_especifique[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_2' + ind +'" name="des_adulto_antecedentes_patologicos_otros_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_antecedentes_patologicos_otros_2_especifique' + ind +'" name="des_adulto_antecedentes_patologicos_otros_2_especifique[]" value="" data-id='+ind+' />';
    /** TERAPIA ANTIMICROBIANA **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_terapia_antimicrobiana' + ind +'" name="des_adulto_terapia_antimicrobiana[]" value="" data-id='+ind+' />';
    /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1' + ind +'" name="des_adulto_frasco_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1_opcion_1' + ind +'" name="des_adulto_frasco_1_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_1_opcion_2' + ind +'" name="des_adulto_frasco_1_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2' + ind +'" name="des_adulto_frasco_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2_opcion_1' + ind +'" name="des_adulto_frasco_2_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_2_opcion_2' + ind +'" name="des_adulto_frasco_2_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3' + ind +'" name="des_adulto_frasco_3[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3_opcion_1' + ind +'" name="des_adulto_frasco_3_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_frasco_3_opcion_2' + ind +'" name="des_adulto_frasco_3_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_hemocultivo_seguimiento_y_vigilancia' + ind +'" name="des_adulto_hemocultivo_seguimiento_y_vigilancia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_adulto_fecha_primer_resultado_hemocultivo' + ind +'" name="des_adulto_fecha_primer_resultado_hemocultivo[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_examen_especializados_hemocultivo_adulto' + ind +'"  name="flag_solicitud_examen_especializados_hemocultivo_adulto[]"  data-id='+ind+' value="0" />';
    newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoAdulto" id="editarSolicitudExamenEspecializadoHemocultivoAdulto' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';
    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO ADULTO Fin ===============================================

    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO NEONATO Y PEDRIATRÍA INICIO ===============================================
    newRow +='<button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoPedriatria" id="solicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fecha_tiempo_enfermedad' + ind +'" name="des_pedriatria_fecha_tiempo_enfermedad[]" value="" data-id='+ind+' />';
    /** EDAD GESTIONAL **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_postermino' + ind +'" name="des_pedriatria_postermino[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_termino' + ind +'" name="des_pedriatria_termino[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_leve' + ind +'" name="des_pedriatria_prematuro_leve[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_moderado' + ind +'" name="des_pedriatria_prematuro_moderado[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_prematuro_extremo' + ind +'" name="des_pedriatria_prematuro_extremo[]" value="" data-id='+ind+' />';
    /** APGAR **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_apariencia' + ind +'" name="des_pedriatria_apariencia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pulso' + ind +'" name="des_pedriatria_pulso[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_gesto' + ind +'" name="des_pedriatria_gesto[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_actividad_respiratoria' + ind +'" name="des_pedriatria_actividad_respiratoria[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tono_muscular' + ind +'" name="des_pedriatria_tono_muscular[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_total_apgar' + ind +'" name="des_pedriatria_total_apgar[]" value="" data-id='+ind+' />';
    /** ESCALA DE GLASGOW **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_ocular' + ind +'" name="des_pedriatria_respuesta_ocular[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_verbal' + ind +'" name="des_pedriatria_respuesta_verbal[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_respuesta_motora' + ind +'" name="des_pedriatria_respuesta_motora[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_total_escala_glasgow' + ind +'" name="des_pedriatria_total_escala_glasgow[]" value="" data-id='+ind+' />';
    /** SIGNOS VITALES **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_presion_arterial_x' + ind +'" name="des_pedriatria_presion_arterial_x[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_presion_arterial_y' + ind +'" name="des_pedriatria_presion_arterial_y[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frecuencia_cardiaca' + ind +'" name="des_pedriatria_frecuencia_cardiaca[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frecuencia_respiratorio' + ind +'" name="des_pedriatria_frecuencia_respiratorio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_temperatura' + ind +'" name="des_pedriatria_temperatura[]" value="" data-id='+ind+' />';
    /** EXÁMENES DE LABORATORIO **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_creatinina' + ind +'" name="des_pedriatria_creatinina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_diuresis' + ind +'" name="des_pedriatria_diuresis[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_procalcitonina' + ind +'" name="des_pedriatria_procalcitonina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pcr' + ind +'" name="des_pedriatria_pcr[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fiO2' + ind +'" name="des_pedriatria_fiO2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_leucocitos' + ind +'" name="des_pedriatria_leucocitos[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_plaquetas' + ind +'" name="des_pedriatria_plaquetas[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_bilirrubina' + ind +'" name="des_pedriatria_bilirrubina[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_glucosa' + ind +'" name="des_pedriatria_glucosa[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_alt' + ind +'" name="des_pedriatria_alt[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_vcg' + ind +'" name="des_pedriatria_vcg[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tp' + ind +'" name="des_pedriatria_tp[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_sodio' + ind +'" name="des_pedriatria_sodio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_potasio' + ind +'" name="des_pedriatria_potasio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_calcio' + ind +'" name="des_pedriatria_calcio[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_pco2' + ind +'" name="des_pedriatria_pco2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_tpta' + ind +'" name="des_pedriatria_tpta[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_examen_laboratorio_otros' + ind +'" name="des_pedriatria_examen_laboratorio_otros[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_examen_laboratorio_otros_especifique' + ind +'" name="des_pedriatria_examen_laboratorio_otros_especifique[]" value="" data-id='+ind+' />';
    /** TERAPIA ANTIMICROBIANA **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_terapia_antimicrobiana' + ind +'" name="des_pedriatria_terapia_antimicrobiana[]" value="" data-id='+ind+' />';
    /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1' + ind +'" name="des_pedriatria_frasco_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1_opcion_1' + ind +'" name="des_pedriatria_frasco_1_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_1_opcion_2' + ind +'" name="des_pedriatria_frasco_1_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2' + ind +'" name="des_pedriatria_frasco_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2_opcion_1' + ind +'" name="des_pedriatria_frasco_2_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_2_opcion_2' + ind +'" name="des_pedriatria_frasco_2_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3' + ind +'" name="des_pedriatria_frasco_3[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3_opcion_1' + ind +'" name="des_pedriatria_frasco_3_opcion_1[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_frasco_3_opcion_2' + ind +'" name="des_pedriatria_frasco_3_opcion_2[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_hemocultivo_seguimiento_y_vigilancia' + ind +'" name="des_pedriatria_hemocultivo_seguimiento_y_vigilancia[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_pedriatria_fecha_primer_resultado_hemocultivo' + ind +'" name="des_pedriatria_fecha_primer_resultado_hemocultivo[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_examen_especializados_hemocultivo_pedriatria' + ind +'"  name="flag_solicitud_examen_especializados_hemocultivo_pedriatria[]"  data-id='+ind+' value="0" />';
    newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoPedriatria" id="editarSolicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';
    // ====================== Solicitud de Examenes Especiales HEMOCULTIVO NEONATO Y PEDRIATRÍA FIN ===============================================
    // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================================
    newRow +='<button type="button" class="btn btn-success btn-xs solicitudCitoquimicosLiquidosBiologicos" id="solicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_antecedentes_patologicos_personales' + ind +'" name="des_antecedentes_patologicos_personales[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_antecedentes_patologicos_familiares' + ind +'" name="des_antecedentes_patologicos_familiares[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_cefaloraquideo' + ind +'" name="des_liquido_cefaloraquideo[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_pleural' + ind +'" name="des_liquido_pleural[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_ascitico' + ind +'" name="des_liquido_ascitico[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_sinovial' + ind +'" name="des_liquido_sinovial[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_otro' + ind +'" name="des_liquido_otro[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_liquido_otro_especifique' + ind +'" name="des_liquido_otro_especifique[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_num_frasco_muestra_opcion' + ind +'" name="des_num_frasco_muestra_opcion[]" value="" data-id='+ind+' />';
    newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_citoquimicos_liquidos_biologicos' + ind +'"  name="flag_solicitud_citoquimicos_liquidos_biologicos[]"  data-id='+ind+' value="0" />';
    newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudCitoquimicosLiquidosBiologicos" id="editarSolicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';

    // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FIN ===============================================
    newRow +='</td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';

  $('#tblExamenLab tbody').append(newRow);

	const selectElementLab = document.getElementById('miSelectLab' + ind);

  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id;
    option.textContent = item.nombre;
    selectElementLab.appendChild(option);
  });

  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
  //tipo=2
  $("#nombre_examen_lab" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '/obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
        //url: '/obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaLaboratorioSelecionado.includes(element.id));
          response(filtro);
        }
      })

    },
    // aqui cuando se despliega el autocomplete es cuando lo selecciona
    select: function(e, ui) {
      asignarValoresSelect(ui,ind);
    }
  });




  $("#nombre_examen_lab" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar el examen laboratorio en esta fila");
      // $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });
}

const mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos = (ind) => {
  $('#solicitudCitoquimicosLiquidosBiologicos' + ind).show();
  $('#flag_solicitud_citoquimicos_liquidos_biologicos' + ind).val('1');
  $('#descripcion_examen' + ind).val('CITOQUIMICO LIQUIDOS BIOLOGICOS');
}

// ========== REGISTRAR SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO INICIO =========
$(document).on('click','.solicitudCitoquimicosLiquidosBiologicos', function () {
  let i = $(this).data('id');
  $('#solicitud_citoquimicos_liquidos_biologicos_data_id').val(i);
  $('#antecedentes_patologicos_personales').val( $('#antecedente').val() )
  $('#antecedentes_patologicos_familiares').val( $('#antecedente_familiar').val() )
  $('#modalSolicitudCitoquimicosLiquidosBiologicos').modal();

});
$(document).on('click', '#btnGuardarRegistroSolicitudCitoquimicosLiquidosBiologicos', function(e) {
  e.preventDefault();
  validarRegistroSolicitudCitoquimicosLiquidosBiologicos();
});

const validarRegistroSolicitudCitoquimicosLiquidosBiologicos = () => {
  let msj = ''

  let antecedentes_patologicos_personales = $('#antecedentes_patologicos_personales').val()
  let antecedentes_patologicos_familiares = $('#antecedentes_patologicos_familiares').val()
  let liquido_cefaloraquideo = $('#liquido_cefaloraquideo').is(':checked');
  let liquido_pleural = $('#liquido_pleural').is(':checked');
  let liquido_ascitico = $('#liquido_ascitico').is(':checked');
  let liquido_sinovial = $('#liquido_sinovial').is(':checked');
  let liquido_otro = $('#liquido_otro').is(':checked');

  let liquido_otro_especifique = $('#liquido_otro_especifique').val()

  if ($('.grupo_checkbox_citoquimico_liquidos_biologicos:checked').length === 0) {
      msj += 'Debe seleccionar al menos un tipo de líquido biológico.<br>'
  }

  if ($('#liquido_otro').is(':checked')) {
      if ($('#liquido_otro_especifique').val().trim() === '') {
          msj += 'Debe especificar el líquido otro.<br>'
      }
  }
  let num_frasco_muestra_opcion = $('#num_frasco_muestra_opcion').val() //  NÚMERO DE FRASCO DE MUESTRA
  if (num_frasco_muestra_opcion == '0') {
    msj += 'Debe seleccionar el número de frasco.<br>'
  }

  if (msj !== '') {
    Swal.fire({
        icon: 'error',
        title: 'Alerta',
        html: msj,
        showConfirmButton: true,
    });
    return;
  }

  let i = $('#solicitud_citoquimicos_liquidos_biologicos_data_id').val();

  $('#des_antecedentes_patologicos_personales' + i).val(antecedentes_patologicos_personales)
  $('#des_antecedentes_patologicos_familiares' + i).val(antecedentes_patologicos_familiares)
  $('#des_liquido_cefaloraquideo' + i).val(liquido_cefaloraquideo)
  $('#des_liquido_pleural' + i).val(liquido_pleural)
  $('#des_liquido_ascitico' + i).val(liquido_ascitico)
  $('#des_liquido_sinovial' + i).val(liquido_sinovial)
  $('#des_liquido_otro' + i).val(liquido_otro)
  $('#des_liquido_otro_especifique' + i).val(liquido_otro_especifique)
  $('#des_num_frasco_muestra_opcion' + i).val(num_frasco_muestra_opcion)

  $('#modalSolicitudCitoquimicosLiquidosBiologicos').modal('hide');
  //========================== LIMPIAR CAMPOS ==========================
  formatearCamposModalSolicitudCitoquimicosLiquidosBiologicos()
  //=========================================

  Swal.fire({
      icon: 'success',
      title: 'Se guardo con exito!',
      showConfirmButton: false,
      timer: 2000,
      onClose: function() {
        //console.log(`Se guardo el tipo de muestra en el input ${i}`);
        $('#solicitudCitoquimicosLiquidosBiologicos' + i).hide();
        $('#editarSolicitudCitoquimicosLiquidosBiologicos' + i).show();
      }
  });
}

const formatearCamposModalSolicitudCitoquimicosLiquidosBiologicos = () => {

  $('#solicitud_citoquimicos_liquidos_biologicos_data_id').val('')
  $('#antecedentes_patologicos_personales').val('')
  $('#antecedentes_patologicos_familiares').val('')

  $('#liquido_cefaloraquideo').prop('checked', false)
  $('#liquido_pleural').prop('checked', false)
  $('#liquido_ascitico').prop('checked', false)
  $('#liquido_sinovial').prop('checked', false)
  $('#liquido_otro').prop('checked', false)
  $('#liquido_otro_especifique').val('')
  $('#liquido_otro_especifique').hide()
  $('#num_frasco_muestra_opcion').val('0')

}

$(document).ready(function() {
    $('#modalSolicitudCitoquimicosLiquidosBiologicos').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        formatearCamposModalSolicitudCitoquimicosLiquidosBiologicos()
    });
});
// ========== REGISTRAR SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO FIN =========
// ========== EDITAR SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO INICIO =========
$(document).on('click','.editarSolicitudCitoquimicosLiquidosBiologicos', function () {

  let i = $(this).data('id');
  $('#solicitud_citoquimicos_liquidos_biologicos_data_id').val(i);
  //------------------------------------------------
  $('#antecedentes_patologicos_personales').val( $('#des_antecedentes_patologicos_personales' + i).val() )
  $('#antecedentes_patologicos_familiares').val( $('#des_antecedentes_patologicos_familiares' + i).val() )

  if ($('#des_liquido_cefaloraquideo' + i).val() == 'true') {
    $('#liquido_cefaloraquideo').prop('checked', true)
  } else {
    $('#liquido_cefaloraquideo').prop('checked', false)
  }

  if ($('#des_liquido_pleural' + i).val() == 'true') {
    $('#liquido_pleural').prop('checked', true)
  } else {
    $('#liquido_pleural').prop('checked', false)
  }

  if ($('#des_liquido_ascitico' + i).val() == 'true') {
    $('#liquido_ascitico').prop('checked', true)
  } else {
    $('#liquido_ascitico').prop('checked', false)
  }

  if ($('#des_liquido_sinovial' + i).val() == 'true') {
    $('#liquido_sinovial').prop('checked', true)
  } else {
    $('#liquido_sinovial').prop('checked', false)
  }

  if ($('#des_liquido_otro' + i).val() == 'true') {
    $('#liquido_otro_especifique').val( $('#des_liquido_otro_especifique' + i).val() )
    $('#liquido_otro_especifique').show()
    $('#liquido_otro').prop('checked', true)
  } else {
    $('#liquido_otro_especifique').val('')
    $('#liquido_otro_especifique').hide()
    $('#liquido_otro').prop('checked', false)
  }

  $('#num_frasco_muestra_opcion').val( $('#des_num_frasco_muestra_opcion' + i).val() )

  //------------------------------------------------

  $("#modalSolicitudCitoquimicosLiquidosBiologicos").modal();

});
// ========== EDITAR SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO FIN =========


const mostrarBotonParaSolicitudHemocultivoPedriatria = (ind) => {
  $('#solicitudExamenEspecializadoHemocultivoPedriatria' + ind).show();
  $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria' + ind).val('1');
  $('#descripcion_examen' + ind).val('HEMOCULTIVO NEONATO Y PEDRIATRIA');
}
// ========== REGISTRAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO PEDRIATRIA INICIO =========
$(document).on('click','.solicitudExamenEspecializadoHemocultivoPedriatria', function () {

  let i = $(this).data('id');
  $('#solicitud_hemocultivo_pedriatria_data_id').val(i);
  let id_diagnostico = $('#miSelectLab' + i).val();
  if (id_diagnostico == 3806) { // cia10  I33.0
    console.log('Entro como cia10 i33.0');
    $('#pedriatria_contenedor_frasco_3').show();
  } else {
    $('#pedriatria_contenedor_frasco_3').hide();
  }
  // SIGNOS VITALES DE ATENCIÓN MÉDICA A SOLICITUD HEMOCULTIVO
  $('#pedriatria_presion_arterial_x').val( $('#presion_arterial_x').val() );
  $('#pedriatria_presion_arterial_y').val( $('#presion_arterial_y').val() );
  $('#pedriatria_frecuencia_cardiaca').val( $('#frec_cardiaca').val() );
  $('#pedriatria_frecuencia_respiratorio').val( $('#frecuencia_respiratoria').val() );
  $('#pedriatria_temperatura').val( $('#temperatura').val() );

  $('#btnGuardarRegistroSolicitudHemocultivoPedriatria').show();
  $("#modalSolicitudHemocultivoPedriatria").modal();

});
$(document).on('click', '#btnGuardarRegistroSolicitudHemocultivoPedriatria', function(e) {
  e.preventDefault();
  validarRegistroSolicitudHemocultivoPedriatria();
});
const validarRegistroSolicitudHemocultivoPedriatria = () => {
  let msj = ''
  let pedriatria_fecha_tiempo_enfermedad = $('#pedriatria_fecha_tiempo_enfermedad').val() //  TIEMPO DE ENFERMEDAD
  if (pedriatria_fecha_tiempo_enfermedad == '') {
    msj = 'Debe indicar el Tiempo de Enfermedad.<br>'
  }
  //  EDAD GESTIONAL
  let pedriatria_postermino = $('#pedriatria_postermino').prop('checked')
  let pedriatria_termino = $('#pedriatria_termino').prop('checked')
  let pedriatria_prematuro_leve = $('#pedriatria_prematuro_leve').prop('checked')
  let pedriatria_prematuro_moderado = $('#pedriatria_prematuro_moderado').prop('checked')
  let pedriatria_prematuro_extremo = $('#pedriatria_prematuro_extremo').prop('checked')
  if (pedriatria_postermino || pedriatria_termino || pedriatria_prematuro_leve || pedriatria_prematuro_moderado || pedriatria_prematuro_extremo) {
    console.log("Al menos uno de los checkboxes está seleccionado.");
  } else {
    msj += 'Debe marcar una opción en Edad Gestional.<br>'
  }
  //  APGAR
  let pedriatria_apariencia = $('#pedriatria_apariencia').val()
  if (pedriatria_apariencia == '') {
    msj += 'Debe indicar el campo apariencia.<br>'
  }
  let pedriatria_pulso = $('#pedriatria_pulso').val()
  if (pedriatria_pulso == '') {
    msj += 'Debe indicar el campo pulso.<br>'
  }
  let pedriatria_gesto = $('#pedriatria_gesto').val()
  if (pedriatria_gesto == '') {
    msj += 'Debe indicar el campo gesto.<br>'
  }
  let pedriatria_actividad_respiratoria = $('#pedriatria_actividad_respiratoria').val()
  if (pedriatria_actividad_respiratoria == '') {
    msj += 'Debe indicar el campo actividad respiratoria.<br>'
  }
  let pedriatria_tono_muscular = $('#pedriatria_tono_muscular').val()
  if (pedriatria_tono_muscular == '') {
    msj += 'Debe indicar el campo tono muscular.<br>'
  }
  let pedriatria_total_apgar = $('#pedriatria_total_apgar').val()
  if (pedriatria_total_apgar == '') {
    msj += 'Debe indicar el campo total apgar.<br>'
  }

  //  ESCALA DE GLASGOW
  let pedriatria_respuesta_ocular = $('#pedriatria_respuesta_ocular').val()
  if (pedriatria_respuesta_ocular == '') {
    msj += 'Debe indicar la Respuesta Ocular.<br>'
  }
  let pedriatria_respuesta_verbal = $('#pedriatria_respuesta_verbal').val()
  if (pedriatria_respuesta_verbal == '') {
    msj += 'Debe indicar la Respuesta Verbal.<br>'
  }
  let pedriatria_respuesta_motora = $('#pedriatria_respuesta_motora').val()
  if (pedriatria_respuesta_motora == '') {
    msj += 'Debe indicar la Respuesta Motora.<br>'
  }
  let pedriatria_total_escala_glasgow = $('#pedriatria_total_escala_glasgow').val()
  if (pedriatria_total_escala_glasgow == '') {
    msj += 'Debe indicar el Total.<br>'
  }
  // SIGNOS VITALES
  let pedriatria_presion_arterial_x = $('#pedriatria_presion_arterial_x').val()
  if (pedriatria_presion_arterial_x == '') {
    msj += 'Debe indicar la presión arterial x.<br>'
  }
  let pedriatria_presion_arterial_y = $('#pedriatria_presion_arterial_y').val()
  if (pedriatria_presion_arterial_y == '') {
    msj += 'Debe indicar la presión arterial y.<br>'
  }
  let pedriatria_frecuencia_cardiaca = $('#pedriatria_frecuencia_cardiaca').val()
  if (pedriatria_frecuencia_cardiaca == '') {
    msj += 'Debe indicar la frecuencia cardiaca.<br>'
  }
  let pedriatria_frecuencia_respiratorio = $('#pedriatria_frecuencia_respiratorio').val()
  if (pedriatria_frecuencia_respiratorio == '') {
    msj += 'Debe indicar la frecuencia respiratoria.<br>'
  }
  let pedriatria_temperatura = $('#pedriatria_temperatura').val()
  if (pedriatria_temperatura == '') {
    msj += 'Debe indicar la temperatura.<br>'
  }
  // EXÁMENES DE LABORATORIO
  let pedriatria_creatinina = $('#pedriatria_creatinina').val()
  let pedriatria_diuresis = $('#pedriatria_diuresis').val()
  let pedriatria_procalcitonina = $('#pedriatria_procalcitonina').val()
  let pedriatria_pcr = $('#pedriatria_pcr').val()
  let pedriatria_fiO2 = $('#pedriatria_fiO2').val()
  let pedriatria_leucocitos = $('#pedriatria_leucocitos').val()
  if (pedriatria_leucocitos == '') {
    msj += 'Debe indicar el campo leucocitos.<br>'
  }
  let pedriatria_plaquetas = $('#pedriatria_plaquetas').val()
  if (pedriatria_plaquetas == '') {
    msj += 'Debe indicar el campo plaquetas.<br>'
  }
  let pedriatria_bilirrubina = $('#pedriatria_bilirrubina').val()

  let pedriatria_glucosa = $('#pedriatria_glucosa').val()
  if (pedriatria_glucosa == '') {
    msj += 'Debe indicar el campo glucosa.<br>'
  }
  let pedriatria_alt = $('#pedriatria_alt').val()
  let pedriatria_vcg = $('#pedriatria_vcg').val()
  let pedriatria_tp = $('#pedriatria_tp').val()
  let pedriatria_sodio = $('#pedriatria_sodio').val()
  let pedriatria_potasio = $('#pedriatria_potasio').val()
  let pedriatria_calcio = $('#pedriatria_calcio').val()
  let pedriatria_pco2 = $('#pedriatria_pco2').val()

  let pedriatria_tpta = $('#pedriatria_tpta').val()
  let pedriatria_examen_laboratorio_otros = $('#pedriatria_examen_laboratorio_otros').attr("aria-pressed")
  let pedriatria_examen_laboratorio_otros_especifique = $('#pedriatria_examen_laboratorio_otros_especifique').val()
  // TERAPIA ANTIMICROBIANA
  let pedriatria_terapia_antimicrobiana = $('#pedriatria_terapia_antimicrobiana').attr("aria-pressed")
  if (pedriatria_terapia_antimicrobiana == 'false') {
    msj += 'Debe ser obligatorio terapia antimicrobiana.<br>'
  }
  // SOLICITUD DE FRASCO DE HEMOCULTIVO
  let pedriatria_frasco_1 = $('#pedriatria_frasco_1').attr("aria-pressed")
  if (pedriatria_frasco_1 == 'false') {
    msj += 'Debe ser obligatorio el frasco n° 1.<br>'
  } else {
    let pedriatria_frasco_1_opcion_1 = $('#pedriatria_frasco_1_opcion_1').val()
    if (pedriatria_frasco_1_opcion_1 == '0') {
      msj += 'Debe seleccionar la primera opcion del frasco n° 1.<br>'
    }
    let pedriatria_frasco_1_opcion_2 = $('#pedriatria_frasco_1_opcion_2').val()
    if (pedriatria_frasco_1_opcion_2 == '0') {
      msj += 'Debe seleccionar la segunda opcion del frasco n° 1.<br>'
    }
  }
  let pedriatria_frasco_2 = $('#pedriatria_frasco_2').attr("aria-pressed")
  if (pedriatria_frasco_2 == 'false') {
    msj += 'Debe ser obligatorio el frasco n° 2.<br>'
  } else {
    let pedriatria_frasco_2_opcion_1 = $('#pedriatria_frasco_2_opcion_1').val()
    if (pedriatria_frasco_2_opcion_1 == '0') {
      msj += 'Debe seleccionar la primera opcion del frasco n° 2.<br>'
    }
    let pedriatria_frasco_2_opcion_2 = $('#pedriatria_frasco_2_opcion_2').val()
    if (pedriatria_frasco_2_opcion_2 == '0') {
      msj += 'Debe seleccionar la segunda opcion del frasco n° 2.<br>'
    }
  }

  let y = $('#solicitud_hemocultivo_pedriatria_data_id').val();
  let id_diagnostico = $('#miSelectLab' + y).val();
  if (id_diagnostico == 3806) { // cia10  I33.0
    let pedriatria_frasco_3 = $('#pedriatria_frasco_3').attr("aria-pressed")
    if (pedriatria_frasco_3 == 'false') {
      msj += 'Debe ser obligatorio el frasco n° 3.<br>'
    } else {
      let pedriatria_frasco_3_opcion_1 = $('#pedriatria_frasco_3_opcion_1').val()
      if (pedriatria_frasco_3_opcion_1 == '0') {
        msj += 'Debe seleccionar la primera opcion del frasco n° 3.<br>'
      }
      let pedriatria_frasco_3_opcion_2 = $('#pedriatria_frasco_3_opcion_2').val()
      if (pedriatria_frasco_3_opcion_2 == '0') {
        msj += 'Debe seleccionar la segunda opcion del frasco n° 3.<br>'
      }
    }
  }
  let pedriatria_hemocultivo_seguimiento_y_vigilancia = $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed")
  if (pedriatria_hemocultivo_seguimiento_y_vigilancia == 'true') {
    let pedriatria_fecha_primer_resultado_hemocultivo = $('#pedriatria_fecha_primer_resultado_hemocultivo').val()
    if (pedriatria_fecha_primer_resultado_hemocultivo == '') {
      msj += 'Debe indicar la fecha del primer resultado de hemocultivo.<br>'
    }
  }
  if (msj !== '') {
    //bootbox.alert(msj)
    Swal.fire({
        icon: 'error',
        title: 'Alerta',
        html: msj,
        showConfirmButton: true,
    });
    return;
  }
  let i = $('#solicitud_hemocultivo_pedriatria_data_id').val();
  $('#des_pedriatria_fecha_tiempo_enfermedad' + i).val(pedriatria_fecha_tiempo_enfermedad);
  //  EDAD GESTIONAL
  $('#des_pedriatria_postermino' + i).val(pedriatria_postermino);
  $('#des_pedriatria_termino' + i).val(pedriatria_termino);
  $('#des_pedriatria_prematuro_leve' + i).val(pedriatria_prematuro_leve);
  $('#des_pedriatria_prematuro_moderado' + i).val(pedriatria_prematuro_moderado);
  $('#des_pedriatria_prematuro_extremo' + i).val(pedriatria_prematuro_extremo);
  //  APGAR
  $('#des_pedriatria_apariencia' + i).val(pedriatria_apariencia)
  $('#des_pedriatria_pulso' + i).val(pedriatria_pulso)
  $('#des_pedriatria_gesto' + i).val(pedriatria_gesto)
  $('#des_pedriatria_actividad_respiratoria' + i).val(pedriatria_actividad_respiratoria)
  $('#des_pedriatria_tono_muscular' + i).val(pedriatria_tono_muscular)
  $('#des_pedriatria_total_apgar' + i).val(pedriatria_total_apgar)
  /** ESCALA DE GLASGOW **/
  $('#des_pedriatria_respuesta_ocular' + i).val(pedriatria_respuesta_ocular);
  $('#des_pedriatria_respuesta_verbal' + i).val(pedriatria_respuesta_verbal);
  $('#des_pedriatria_respuesta_motora' + i).val(pedriatria_respuesta_motora);
  $('#des_pedriatria_total_escala_glasgow' + i).val(pedriatria_total_escala_glasgow);
  /** SIGNOS VITALES **/
  $('#des_pedriatria_presion_arterial_x' + i).val(pedriatria_presion_arterial_x);
  $('#des_pedriatria_presion_arterial_y' + i).val(pedriatria_presion_arterial_y);
  $('#des_pedriatria_frecuencia_cardiaca' + i).val(pedriatria_frecuencia_cardiaca);
  $('#des_pedriatria_frecuencia_respiratorio' + i).val(pedriatria_frecuencia_respiratorio);
  $('#des_pedriatria_temperatura' + i).val(pedriatria_temperatura);
  /** EXÁMENES DE LABORATORIO **/
  $('#des_pedriatria_creatinina' + i).val(pedriatria_creatinina);
  $('#des_pedriatria_diuresis' + i).val(pedriatria_diuresis);
  $('#des_pedriatria_procalcitonina' + i).val(pedriatria_procalcitonina);
  $('#des_pedriatria_pcr' + i).val(pedriatria_pcr);
  $('#des_pedriatria_fiO2' + i).val(pedriatria_fiO2);
  $('#des_pedriatria_leucocitos' + i).val(pedriatria_leucocitos);
  $('#des_pedriatria_plaquetas' + i).val(pedriatria_plaquetas);
  $('#des_pedriatria_bilirrubina' + i).val(pedriatria_bilirrubina);

  $('#des_pedriatria_glucosa' + i).val(pedriatria_glucosa)
  $('#des_pedriatria_alt' + i).val(pedriatria_alt)
  $('#des_pedriatria_vcg' + i).val(pedriatria_vcg)
  $('#des_pedriatria_tp' + i).val(pedriatria_tp)
  $('#des_pedriatria_sodio' + i).val(pedriatria_sodio)
  $('#des_pedriatria_potasio' + i).val(pedriatria_potasio)
  $('#des_pedriatria_calcio' + i).val(pedriatria_calcio)
  $('#des_pedriatria_pco2' + i).val(pedriatria_pco2)

  $('#des_pedriatria_tpta' + i).val(pedriatria_tpta)
  $('#des_pedriatria_examen_laboratorio_otros' + i).val(pedriatria_examen_laboratorio_otros)
  $('#des_pedriatria_examen_laboratorio_otros_especifique' + i).val(pedriatria_examen_laboratorio_otros_especifique)

  /** TERAPIA ANTIMICROBIANA **/
  $('#des_pedriatria_terapia_antimicrobiana' + i).val(pedriatria_terapia_antimicrobiana);
  /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
  $('#des_pedriatria_frasco_1' + i).val(pedriatria_frasco_1)
  $('#des_pedriatria_frasco_1_opcion_1' + i).val( $('#pedriatria_frasco_1_opcion_1').val() )
  $('#des_pedriatria_frasco_1_opcion_2' + i).val( $('#pedriatria_frasco_1_opcion_2').val() )
  $('#des_pedriatria_frasco_2' + i).val(pedriatria_frasco_2)
  $('#des_pedriatria_frasco_2_opcion_1' + i).val( $('#pedriatria_frasco_2_opcion_1').val() )
  $('#des_pedriatria_frasco_2_opcion_2' + i).val( $('#pedriatria_frasco_2_opcion_2').val() )
  $('#des_pedriatria_frasco_3' + i).val( $('#pedriatria_frasco_3').attr("aria-pressed") )
  $('#des_pedriatria_frasco_3_opcion_1' + i).val( $('#pedriatria_frasco_3_opcion_1').val() )
  $('#des_pedriatria_frasco_3_opcion_2' + i).val( $('#pedriatria_frasco_3_opcion_2').val() )
  $('#des_pedriatria_hemocultivo_seguimiento_y_vigilancia' + i).val(pedriatria_hemocultivo_seguimiento_y_vigilancia)
  $('#des_pedriatria_fecha_primer_resultado_hemocultivo' + i).val( $('#pedriatria_fecha_primer_resultado_hemocultivo').val() )

  $('#modalSolicitudHemocultivoPedriatria').modal('hide');
  //========================== LIMPIAR CAMPOS ==========================
  formatearCamposModalSolicitudHemocultivoPedriatria()
  //=========================================

  Swal.fire({
      icon: 'success',
      title: 'Se guardo con exito!',
      showConfirmButton: false,
      timer: 2000,
      onClose: function() {
        //console.log(`Se guardo el tipo de muestra en el input ${i}`);
        $('#solicitudExamenEspecializadoHemocultivoPedriatria' + i).hide();
        $('#editarSolicitudExamenEspecializadoHemocultivoPedriatria' + i).show();
      }
  });
}

const formatearCamposModalSolicitudHemocultivoPedriatria = () => {
  $('#solicitud_hemocultivo_pedriatria_data_id').val('');
  $('#pedriatria_fecha_tiempo_enfermedad').val('');
  //  EDAD GESTIONAL
  $('#pedriatria_postermino').prop('checked', false);
  $('#pedriatria_termino').prop('checked', false);
  $('#pedriatria_prematuro_leve').prop('checked', false);
  $('#pedriatria_prematuro_moderado').prop('checked', false);
  $('#pedriatria_prematuro_extremo').prop('checked', false);
  //  APGAR
  $('#pedriatria_apariencia').val('')
  $('#pedriatria_pulso').val('')
  $('#pedriatria_gesto').val('')
  $('#pedriatria_actividad_respiratoria').val('')
  $('#pedriatria_tono_muscular').val('')
  $('#pedriatria_total_apgar').val('')
  /** ESCALA DE GLASGOW **/
  $('#pedriatria_respuesta_ocular').val('');
  $('#pedriatria_respuesta_verbal').val('');
  $('#pedriatria_respuesta_motora').val('');
  $('#pedriatria_total_escala_glasgow').val('');
  /** SIGNOS VITALES **/
  $('#pedriatria_presion_arterial_x').val('')
  $('#pedriatria_presion_arterial_y').val('')
  $('#pedriatria_frecuencia_cardiaca').val('')
  $('#pedriatria_frecuencia_respiratorio').val('')
  $('#pedriatria_temperatura').val('')
  /** EXÁMENES DE LABORATORIO **/
  $('#pedriatria_creatinina').val('')
  $('#pedriatria_diuresis').val('')
  $('#pedriatria_procalcitonina').val('')
  $('#pedriatria_pcr').val('')
  $('#pedriatria_fiO2').val('')
  $('#pedriatria_leucocitos').val('')
  $('#pedriatria_plaquetas').val('')
  $('#pedriatria_bilirrubina').val('')

  $('#pedriatria_glucosa').val('')
  $('#pedriatria_alt').val('')
  $('#pedriatria_vcg').val('')
  $('#pedriatria_tp').val('')
  $('#pedriatria_sodio').val('')
  $('#pedriatria_potasio').val('')
  $('#pedriatria_calcio').val('')
  $('#pedriatria_pco2').val('')
  $('#pedriatria_tpta').val('')
  $('#pedriatria_examen_laboratorio_otros').removeClass('active')
  $('#pedriatria_examen_laboratorio_otros').attr("aria-pressed", "false")
  $('#pedriatria_examen_laboratorio_otros_especifique').val('')
  $('#pedriatria_examen_laboratorio_otros_especifique').hide()
  /** TERAPIA ANTIMICROBIANA **/
  $('#pedriatria_terapia_antimicrobiana').removeClass('active')
  $('#pedriatria_terapia_antimicrobiana').attr("aria-pressed", "false")
  /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
  $('#pedriatria_frasco_1').removeClass('active')
  $('#pedriatria_frasco_1').attr("aria-pressed", "false")
  $('#pedriatria_frasco_1_opcion_1').val('0')
  $('#pedriatria_frasco_1_opcion_2').val('0')
  $('#pedriatria_frasco_1_opcion_1').hide()
  $('#pedriatria_frasco_1_opcion_2').hide()
  $('#pedriatria_frasco_2').removeClass('active')
  $('#pedriatria_frasco_2').attr("aria-pressed", "false")
  $('#pedriatria_frasco_2_opcion_1').val('0')
  $('#pedriatria_frasco_2_opcion_2').val('0')
  $('#pedriatria_frasco_2_opcion_1').hide()
  $('#pedriatria_frasco_2_opcion_2').hide()
  $('#pedriatria_frasco_3').removeClass('active')
  $('#pedriatria_frasco_3').attr("aria-pressed", "false")
  $('#pedriatria_frasco_3_opcion_1').val('0')
  $('#pedriatria_frasco_3_opcion_2').val('0')
  $('#pedriatria_frasco_3_opcion_1').hide()
  $('#pedriatria_frasco_3_opcion_2').hide()
  $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').removeClass('active')
  $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed", "false")
  $('#pedriatria_fecha_primer_resultado_hemocultivo').val('')
  $('#pedriatria_contenedor_fecha_primer_resultado_hemocultivo').hide()
}

$(document).ready(function() {
    $('#modalSolicitudHemocultivoPedriatria').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        formatearCamposModalSolicitudHemocultivoPedriatria()
    });
});
// ========== REGISTRAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO PEDRIATRIA FIN =========
// ========== EDITAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO PEDRIATRIA INICIO =========
$(document).on('click','.editarSolicitudExamenEspecializadoHemocultivoPedriatria', function () {

  let i = $(this).data('id');
  $('#solicitud_hemocultivo_pedriatria_data_id').val(i);

  //------------------------------------------------
  //  TIEMPO DE ENFERMEDAD
  $('#pedriatria_fecha_tiempo_enfermedad').val( $('#des_pedriatria_fecha_tiempo_enfermedad' + i).val() )
  //  EDAD GESTIONAL
  if ($('#des_pedriatria_postermino' + i).val() == "true") {
    $('#pedriatria_postermino').prop('checked',true)
  }
  if ($('#des_pedriatria_termino' + i).val() == "true") {
    $('#pedriatria_termino').prop('checked',true)
  }
  if ($('#des_pedriatria_prematuro_leve' + i).val() == "true") {
    $('#pedriatria_prematuro_leve').prop('checked',true)
  }
  if ($('#des_pedriatria_prematuro_moderado' + i).val() == "true") {
    $('#pedriatria_prematuro_moderado').prop('checked',true)
  }
  if ($('#des_pedriatria_prematuro_extremo' + i).val() == "true") {
    $('#pedriatria_prematuro_extremo').prop('checked',true)
  }
  //  APGAR
  $('#pedriatria_apariencia').val( $('#des_pedriatria_apariencia' + i).val() )
  $('#pedriatria_pulso').val( $('#des_pedriatria_pulso' + i).val() )
  $('#pedriatria_gesto').val( $('#des_pedriatria_gesto' + i).val() )
  $('#pedriatria_actividad_respiratoria').val( $('#des_pedriatria_actividad_respiratoria' + i).val() )
  $('#pedriatria_tono_muscular').val( $('#des_pedriatria_tono_muscular' + i).val() )
  $('#pedriatria_total_apgar').val( $('#des_pedriatria_total_apgar' + i).val() )
  //  ESCALA DE GLASGOW
  $('#pedriatria_respuesta_ocular').val( $('#des_pedriatria_respuesta_ocular' + i).val() )
  $('#pedriatria_respuesta_verbal').val( $('#des_pedriatria_respuesta_verbal' + i).val() )
  $('#pedriatria_respuesta_motora').val( $('#des_pedriatria_respuesta_motora' + i).val() )
  $('#pedriatria_total_escala_glasgow').val( $('#des_pedriatria_total_escala_glasgow' + i).val() )
  // SIGNOS VITALES
  $('#pedriatria_presion_arterial_x').val( $('#des_pedriatria_presion_arterial_x' + i).val() )
  $('#pedriatria_presion_arterial_y').val( $('#des_pedriatria_presion_arterial_y' + i).val() )
  $('#pedriatria_frecuencia_cardiaca').val( $('#des_pedriatria_frecuencia_cardiaca' + i).val() )
  $('#pedriatria_frecuencia_respiratorio').val( $('#des_pedriatria_frecuencia_respiratorio' + i).val() )
  $('#pedriatria_temperatura').val( $('#des_pedriatria_temperatura' + i).val() )
  // EXÁMENES DE LABORATORIO
  $('#pedriatria_creatinina').val( $('#des_pedriatria_creatinina' + i).val() )
  $('#pedriatria_diuresis').val( $('#des_pedriatria_diuresis' + i).val() )
  $('#pedriatria_procalcitonina').val( $('#des_pedriatria_procalcitonina' + i).val() )
  $('#pedriatria_pcr').val( $('#des_pedriatria_pcr' + i).val() )
  $('#pedriatria_fiO2').val( $('#des_pedriatria_fiO2' + i).val() )
  $('#pedriatria_leucocitos').val( $('#des_pedriatria_leucocitos' + i).val() )
  $('#pedriatria_plaquetas').val( $('#des_pedriatria_plaquetas' + i).val() )
  $('#pedriatria_bilirrubina').val( $('#des_pedriatria_bilirrubina' + i).val() )

  $('#pedriatria_glucosa').val( $('#des_pedriatria_glucosa' + i).val() )
  $('#pedriatria_alt').val( $('#des_pedriatria_alt' + i).val() )
  $('#pedriatria_vcg').val( $('#des_pedriatria_vcg' + i).val() )
  $('#pedriatria_tp').val( $('#des_pedriatria_tp' + i).val() )
  $('#pedriatria_sodio').val( $('#des_pedriatria_sodio' + i).val() )
  $('#pedriatria_potasio').val( $('#des_pedriatria_potasio' + i).val() )
  $('#pedriatria_calcio').val( $('#des_pedriatria_calcio' + i).val() )
  $('#pedriatria_pco2').val( $('#des_pedriatria_pco2' + i).val() )
  $('#pedriatria_tpta').val( $('#des_pedriatria_tpta' + i).val() )
  if($('#des_pedriatria_examen_laboratorio_otros' + i).val() == 'true'){
    $('#pedriatria_examen_laboratorio_otros').attr("aria-pressed","true")
    $('#pedriatria_examen_laboratorio_otros').addClass("active")
    $('#pedriatria_examen_laboratorio_otros_especifique').val( $('#des_pedriatria_examen_laboratorio_otros_especifique' + i).val() )
    $('#pedriatria_examen_laboratorio_otros_especifique').show()
  }

  // TERAPIA ANTIMICROBIANA
  if($('#des_pedriatria_terapia_antimicrobiana' + i).val() == 'true'){
    $('#pedriatria_terapia_antimicrobiana').attr("aria-pressed","true")
    $('#pedriatria_terapia_antimicrobiana').addClass("active")
  }
  // SOLICITUD DE FRASCO DE HEMOCULTIVO
  if($('#des_pedriatria_frasco_1' + i).val() == 'true'){
    $('#pedriatria_frasco_1').attr("aria-pressed","true")
    $('#pedriatria_frasco_1').addClass("active")
    $('#pedriatria_frasco_1_opcion_1').val( $('#des_pedriatria_frasco_1_opcion_1' + i).val() )
    $('#pedriatria_frasco_1_opcion_2').val( $('#des_pedriatria_frasco_1_opcion_2' + i).val() )
    $('#pedriatria_frasco_1_opcion_1').show()
    $('#pedriatria_frasco_1_opcion_2').show()
  }
  if($('#des_pedriatria_frasco_2' + i).val() == 'true'){
    $('#pedriatria_frasco_2').attr("aria-pressed","true")
    $('#pedriatria_frasco_2').addClass("active")
    $('#pedriatria_frasco_2_opcion_1').val( $('#des_pedriatria_frasco_2_opcion_1' + i).val() )
    $('#pedriatria_frasco_2_opcion_2').val( $('#des_pedriatria_frasco_2_opcion_2' + i).val() )
    $('#pedriatria_frasco_2_opcion_1').show()
    $('#pedriatria_frasco_2_opcion_2').show()
  }
  if($('#des_pedriatria_frasco_3' + i).val() == 'true'){
    $('#pedriatria_frasco_3').attr("aria-pressed","true")
    $('#pedriatria_frasco_3').addClass("active")
    $('#pedriatria_frasco_3_opcion_1').val( $('#des_pedriatria_frasco_3_opcion_1' + i).val() )
    $('#pedriatria_frasco_3_opcion_2').val( $('#des_pedriatria_frasco_3_opcion_2' + i).val() )
    $('#pedriatria_frasco_3_opcion_1').show()
    $('#pedriatria_frasco_3_opcion_2').show()
  }
  if($('#des_pedriatria_hemocultivo_seguimiento_y_vigilancia' + i).val() == 'true'){
    $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed","true")
    $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').addClass("active")
    $('#pedriatria_fecha_primer_resultado_hemocultivo').val( $('#des_pedriatria_fecha_primer_resultado_hemocultivo' + i).val() )
    $('#pedriatria_contenedor_fecha_primer_resultado_hemocultivo').show()
  }
  //------------------------------------------------

  // let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + i).val();
  // $('#solicitud_edit_descripcion_tipo_muestra').val(descripcion_tipo_muestra);
  $("#modalSolicitudHemocultivoPedriatria").modal();

});
// ========== EDITAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO PEDRIATRIA FIN =========

const mostrarBotonParaSolicitudHemocultivoAdulto = (ind) => {
  $('#solicitudExamenEspecializadoHemocultivoAdulto' + ind).show();
  $('#flag_solicitud_examen_especializados_hemocultivo_adulto' + ind).val('1');
  $('#descripcion_examen' + ind).val('HEMOCULTIVO ADULTO');
}

// ========== REGISTRAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO INICIO =========
$(document).on('click','.solicitudExamenEspecializadoHemocultivoAdulto', function () {

  let i = $(this).data('id');
  $('#solicitud_hemocultivo_adulto_data_id').val(i);
  let id_diagnostico = $('#miSelectLab' + i).val();
  if (id_diagnostico == 3806) { // cia10  I33.0
    console.log('Entro como cia10 i33.0');
    $('#adulto_contenedor_frasco_3').show();
  } else {
    $('#adulto_contenedor_frasco_3').hide();
  }

  // SIGNOS VITALES DE ATENCIÓN MÉDICA A SOLICITUD HEMOCULTIVO
  $('#adulto_presion_arterial_x').val( $('#presion_arterial_x').val() );
  $('#adulto_presion_arterial_y').val( $('#presion_arterial_y').val() );
  $('#adulto_frecuencia_cardiaca').val( $('#frec_cardiaca').val() );
  $('#adulto_frecuencia_respiratorio').val( $('#frecuencia_respiratoria').val() );
  $('#adulto_temperatura').val( $('#temperatura').val() );

  $("#modalSolicitudHemocultivoAdulto").modal();
});

$(document).on('click', '#btnGuardarRegistroSolicitudHemocultivoAdulto', function(e) {
  e.preventDefault();
  validarRegistroSolicitudHemocultivoAdulto();
});

const validarRegistroSolicitudHemocultivoAdulto = () => {
  let msj = ''
  let adulto_fecha_tiempo_enfermedad = $('#adulto_fecha_tiempo_enfermedad').val() //  TIEMPO DE ENFERMEDAD
  if (adulto_fecha_tiempo_enfermedad == '') {
    msj = 'Debe indicar el Tiempo de Enfermedad.<br>'
  }
  //  ESCALA DE GLASGOW
  let adulto_respuesta_ocular = $('#adulto_respuesta_ocular').val()
  if (adulto_respuesta_ocular == '') {
    msj += 'Debe indicar la Respuesta Ocular.<br>'
  }
  let adulto_respuesta_verbal = $('#adulto_respuesta_verbal').val()
  if (adulto_respuesta_verbal == '') {
    msj += 'Debe indicar la Respuesta Verbal.<br>'
  }
  let adulto_respuesta_motora = $('#adulto_respuesta_motora').val()
  if (adulto_respuesta_motora == '') {
    msj += 'Debe indicar la Respuesta Motora.<br>'
  }
  let adulto_total_escala_glasgow = $('#adulto_total_escala_glasgow').val()
  if (adulto_total_escala_glasgow == '') {
    msj += 'Debe indicar el Total.<br>'
  }
  // SIGNOS VITALES
  let adulto_presion_arterial_x = $('#adulto_presion_arterial_x').val()
  if (adulto_presion_arterial_x == '') {
    msj += 'Debe indicar la presión arterial x.<br>'
  }
  let adulto_presion_arterial_y = $('#adulto_presion_arterial_y').val()
  if (adulto_presion_arterial_y == '') {
    msj += 'Debe indicar la presión arterial y.<br>'
  }
  let adulto_frecuencia_cardiaca = $('#adulto_frecuencia_cardiaca').val()
  if (adulto_frecuencia_cardiaca == '') {
    msj += 'Debe indicar la frecuencia cardiaca.<br>'
  }
  let adulto_frecuencia_respiratorio = $('#adulto_frecuencia_respiratorio').val()
  if (adulto_frecuencia_respiratorio == '') {
    msj += 'Debe indicar la frecuencia respiratoria.<br>'
  }
  let adulto_temperatura = $('#adulto_temperatura').val()
  if (adulto_temperatura == '') {
    msj += 'Debe indicar la temperatura.<br>'
  }
  // EXÁMENES DE LABORATORIO
  let adulto_creatinina = $('#adulto_creatinina').val()
  let adulto_diuresis = $('#adulto_diuresis').val()
  let adulto_procalcitonina = $('#adulto_procalcitonina').val()
  let adulto_pcr = $('#adulto_pcr').val()
  let adulto_fiO2 = $('#adulto_fiO2').val()
  let adulto_leucocitos = $('#adulto_leucocitos').val()
  if (adulto_leucocitos == '') {
    msj += 'Debe indicar leucocitos.<br>'
  }
  let adulto_plaquetas = $('#adulto_plaquetas').val()
  if (adulto_plaquetas == '') {
    msj += 'Debe indicar plaquetas.<br>'
  }
  let adulto_bilirrubina = $('#adulto_bilirrubina').val()
  //ANTECEDENTES PATOLÓGICOS
  let adulto_ima = $('#adulto_ima').attr("aria-pressed")
  let adulto_icc = $('#adulto_icc').attr("aria-pressed")
  let adulto_evp = $('#adulto_evp').attr("aria-pressed")
  let adulto_ecv = $('#adulto_ecv').attr("aria-pressed")
  let adulto_demencia = $('#adulto_demencia').attr("aria-pressed")
  let adulto_dm = $('#adulto_dm').attr("aria-pressed")
  let adulto_diabetes_lesion_organica = $('#adulto_diabetes_lesion_organica').attr("aria-pressed")
  let adulto_hemiplejia = $('#adulto_hemiplejia').attr("aria-pressed")
  let adulto_epoc = $('#adulto_epoc').attr("aria-pressed")
  let adulto_patologia_tejido_conectivo = $('#adulto_patologia_tejido_conectivo').attr("aria-pressed")
  let adulto_enfermedad_ulcerosa = $('#adulto_enfermedad_ulcerosa').attr("aria-pressed")
  let adulto_patologia_renal_moderada_o_grave = $('#adulto_patologia_renal_moderada_o_grave').attr("aria-pressed")
  let adulto_neoplasias = $('#adulto_neoplasias').attr("aria-pressed")
  let adulto_neoplasias_especifique = $('#adulto_neoplasias_especifique').val()
  let adulto_leucemias = $('#adulto_leucemias').attr("aria-pressed")
  let adulto_leucemias_especifique = $('#adulto_leucemias_especifique').val()
  let adulto_linfomas_malignos = $('#adulto_linfomas_malignos').attr("aria-pressed")
  let adulto_metastasis_colida = $('#adulto_metastasis_colida').attr("aria-pressed")
  let adulto_sida = $('#adulto_sida').attr("aria-pressed")
  let adulto_antecedentes_patologicos_otros_1 = $('#adulto_antecedentes_patologicos_otros_1').attr("aria-pressed")
  let adulto_antecedentes_patologicos_otros_1_especifique = $('#adulto_antecedentes_patologicos_otros_1_especifique').val()
  let adulto_antecedentes_patologicos_otros_2 = $('#adulto_antecedentes_patologicos_otros_2').attr("aria-pressed")
  let adulto_antecedentes_patologicos_otros_2_especifique = $('#adulto_antecedentes_patologicos_otros_2_especifique').val()
  // TERAPIA ANTIMICROBIANA
  let adulto_terapia_antimicrobiana = $('#adulto_terapia_antimicrobiana').attr("aria-pressed")
  if (adulto_terapia_antimicrobiana == 'false') {
    msj += 'Debe ser obligatorio terapia antimicrobiana.<br>'
  }
  // SOLICITUD DE FRASCO DE HEMOCULTIVO
  let adulto_frasco_1 = $('#adulto_frasco_1').attr("aria-pressed")
  if (adulto_frasco_1 == 'false') {
    msj += 'Debe ser obligatorio el frasco n° 1.<br>'
  } else {
    let adulto_frasco_1_opcion_1 = $('#adulto_frasco_1_opcion_1').val()
    console.log("adulto_frasco_1_opcion_1 :" +adulto_frasco_1_opcion_1);
    if (adulto_frasco_1_opcion_1 == '0') {
      msj += 'Debe seleccionar la primera opcion del frasco n° 1.<br>'
    }
    let adulto_frasco_1_opcion_2 = $('#adulto_frasco_1_opcion_2').val()
    if (adulto_frasco_1_opcion_2 == '0') {
      msj += 'Debe seleccionar la segunda opcion del frasco n° 1.<br>'
    }
  }
  let adulto_frasco_2 = $('#adulto_frasco_2').attr("aria-pressed")
  if (adulto_frasco_2 == 'false') {
    msj += 'Debe ser obligatorio el frasco n° 2.<br>'
  } else {
    let adulto_frasco_2_opcion_1 = $('#adulto_frasco_2_opcion_1').val()
    if (adulto_frasco_2_opcion_1 == '0') {
      msj += 'Debe seleccionar la primera opcion del frasco n° 2.<br>'
    }
    let adulto_frasco_2_opcion_2 = $('#adulto_frasco_2_opcion_2').val()
    if (adulto_frasco_2_opcion_2 == '0') {
      msj += 'Debe seleccionar la segunda opcion del frasco n° 2.<br>'
    }
  }

  let y = $('#solicitud_hemocultivo_adulto_data_id').val();
  let id_diagnostico = $('#miSelectLab' + y).val();
  if (id_diagnostico == 3806) { // cia10  I33.0
    let adulto_frasco_3 = $('#adulto_frasco_3').attr("aria-pressed")
    if (adulto_frasco_3 == 'false') {
      msj += 'Debe ser obligatorio el frasco n° 3.<br>'
    } else {
      let adulto_frasco_3_opcion_1 = $('#adulto_frasco_3_opcion_1').val()
      if (adulto_frasco_3_opcion_1 == '0') {
        msj += 'Debe seleccionar la primera opcion del frasco n° 3.<br>'
      }
      let adulto_frasco_3_opcion_2 = $('#adulto_frasco_3_opcion_2').val()
      if (adulto_frasco_3_opcion_2 == '0') {
        msj += 'Debe seleccionar la segunda opcion del frasco n° 3.<br>'
      }
    }
  }
  let adulto_hemocultivo_seguimiento_y_vigilancia = $('#adulto_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed")
  if (adulto_hemocultivo_seguimiento_y_vigilancia == 'true') {
    let adulto_fecha_primer_resultado_hemocultivo = $('#adulto_fecha_primer_resultado_hemocultivo').val()
    if (adulto_fecha_primer_resultado_hemocultivo == '') {
      msj += 'Debe indicar la fecha del primer resultado de hemocultivo.<br>'
    }
  }

  if (msj !== '') {
    //bootbox.alert(msj)
    Swal.fire({
        icon: 'error',
        title: 'Alerta',
        html: msj,
        showConfirmButton: true,
    });
    return;
  }
  let i = $('#solicitud_hemocultivo_adulto_data_id').val();
  $('#des_adulto_fecha_tiempo_enfermedad' + i).val(adulto_fecha_tiempo_enfermedad);
  /** ESCALA DE GLASGOW **/
  $('#des_adulto_respuesta_ocular' + i).val(adulto_respuesta_ocular);
  $('#des_adulto_respuesta_verbal' + i).val(adulto_respuesta_verbal);
  $('#des_adulto_respuesta_motora' + i).val(adulto_respuesta_motora);
  $('#des_adulto_total_escala_glasgow' + i).val(adulto_total_escala_glasgow);
  /** SIGNOS VITALES **/
  $('#des_adulto_presion_arterial_x' + i).val(adulto_presion_arterial_x);
  $('#des_adulto_presion_arterial_y' + i).val(adulto_presion_arterial_y);
  $('#des_adulto_frecuencia_cardiaca' + i).val(adulto_frecuencia_cardiaca);
  $('#des_adulto_frecuencia_respiratorio' + i).val(adulto_frecuencia_respiratorio);
  $('#des_adulto_temperatura' + i).val(adulto_temperatura);
  /** EXÁMENES DE LABORATORIO **/
  $('#des_adulto_creatinina' + i).val(adulto_creatinina);
  $('#des_adulto_diuresis' + i).val(adulto_diuresis);
  $('#des_adulto_procalcitonina' + i).val(adulto_procalcitonina);
  $('#des_adulto_pcr' + i).val(adulto_pcr);
  $('#des_adulto_fiO2' + i).val(adulto_fiO2);
  $('#des_adulto_leucocitos' + i).val(adulto_leucocitos);
  $('#des_adulto_plaquetas' + i).val(adulto_plaquetas);
  $('#des_adulto_bilirrubina' + i).val(adulto_bilirrubina);
  /** ANTECEDENTES PATOLÓGICOS **/
  $('#des_adulto_ima' + i).val(adulto_ima);
  $('#des_adulto_icc' + i).val(adulto_icc);
  $('#des_adulto_evp' + i).val(adulto_evp);
  $('#des_adulto_ecv' + i).val(adulto_ecv);
  $('#des_adulto_demencia' + i).val(adulto_demencia);
  $('#des_adulto_dm' + i).val(adulto_dm);
  $('#des_adulto_diabetes_lesion_organica' + i).val(adulto_diabetes_lesion_organica);
  $('#des_adulto_hemiplejia' + i).val(adulto_hemiplejia);
  $('#des_adulto_epoc' + i).val(adulto_epoc);
  $('#des_adulto_patologia_tejido_conectivo' + i).val(adulto_patologia_tejido_conectivo);
  $('#des_adulto_enfermedad_ulcerosa' + i).val(adulto_enfermedad_ulcerosa);
  $('#des_adulto_patologia_renal_moderada_o_grave' + i).val(adulto_patologia_renal_moderada_o_grave);
  $('#des_adulto_neoplasias' + i).val(adulto_neoplasias);
  $('#des_adulto_neoplasias_especifique' + i).val(adulto_neoplasias_especifique);
  $('#des_adulto_leucemias' + i).val(adulto_leucemias);
  $('#des_adulto_leucemias_especifique' + i).val(adulto_leucemias_especifique);
  $('#des_adulto_linfomas_malignos' + i).val(adulto_linfomas_malignos);
  $('#des_adulto_metastasis_colida' + i).val(adulto_metastasis_colida);
  $('#des_adulto_sida' + i).val(adulto_sida);
  $('#des_adulto_antecedentes_patologicos_otros_1' + i).val(adulto_antecedentes_patologicos_otros_1);
  $('#des_adulto_antecedentes_patologicos_otros_1_especifique' + i).val(adulto_antecedentes_patologicos_otros_1_especifique);
  $('#des_adulto_antecedentes_patologicos_otros_2' + i).val(adulto_antecedentes_patologicos_otros_2);
  $('#des_adulto_antecedentes_patologicos_otros_2_especifique' + i).val(adulto_antecedentes_patologicos_otros_2_especifique);
  /** TERAPIA ANTIMICROBIANA **/
  $('#des_adulto_terapia_antimicrobiana' + i).val(adulto_terapia_antimicrobiana);
  /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
  $('#des_adulto_frasco_1' + i).val(adulto_frasco_1)
  $('#des_adulto_frasco_1_opcion_1' + i).val( $('#adulto_frasco_1_opcion_1').val() )
  $('#des_adulto_frasco_1_opcion_2' + i).val( $('#adulto_frasco_1_opcion_2').val() )
  $('#des_adulto_frasco_2' + i).val(adulto_frasco_2)
  $('#des_adulto_frasco_2_opcion_1' + i).val( $('#adulto_frasco_2_opcion_1').val() )
  $('#des_adulto_frasco_2_opcion_2' + i).val( $('#adulto_frasco_2_opcion_2').val() )
  $('#des_adulto_frasco_3' + i).val( $('#adulto_frasco_3').attr("aria-pressed") )
  $('#des_adulto_frasco_3_opcion_1' + i).val( $('#adulto_frasco_3_opcion_1').val() )
  $('#des_adulto_frasco_3_opcion_2' + i).val( $('#adulto_frasco_3_opcion_2').val() )
  $('#des_adulto_hemocultivo_seguimiento_y_vigilancia' + i).val(adulto_hemocultivo_seguimiento_y_vigilancia)
  $('#des_adulto_fecha_primer_resultado_hemocultivo' + i).val( $('#adulto_fecha_primer_resultado_hemocultivo').val() )

  $('#modalSolicitudHemocultivoAdulto').modal('hide');
  //========================== LIMPIAR CAMPOS ==========================
  formatearCamposModalSolicitudHemocultivoAdulto()
  //=========================================

  Swal.fire({
      icon: 'success',
      title: 'Se guardo con exito!',
      showConfirmButton: false,
      timer: 2000,
      onClose: function() {
        //console.log(`Se guardo el tipo de muestra en el input ${i}`);
        $('#solicitudExamenEspecializadoHemocultivoAdulto' + i).hide();
        $('#editarSolicitudExamenEspecializadoHemocultivoAdulto' + i).show();
      }
  });
}

const formatearCamposModalSolicitudHemocultivoAdulto = () => {
  /** ESCALA DE GLASGOW **/
  $('#solicitud_hemocultivo_adulto_data_id').val('');
  $('#adulto_fecha_tiempo_enfermedad').val('');
  $('#adulto_respuesta_ocular').val('');
  $('#adulto_respuesta_verbal').val('');
  $('#adulto_respuesta_motora').val('');
  $('#adulto_total_escala_glasgow').val('');
  /** SIGNOS VITALES **/
  $('#adulto_presion_arterial_x').val('')
  $('#adulto_presion_arterial_y').val('')
  $('#adulto_frecuencia_cardiaca').val('')
  $('#adulto_frecuencia_respiratorio').val('')
  $('#adulto_temperatura').val('')
  /** EXÁMENES DE LABORATORIO **/
  $('#adulto_creatinina').val('')
  $('#adulto_diuresis').val('')
  $('#adulto_procalcitonina').val('')
  $('#adulto_pcr').val('')
  $('#adulto_fiO2').val('')
  $('#adulto_leucocitos').val('')
  $('#adulto_plaquetas').val('')
  $('#adulto_bilirrubina').val('')
  /** ANTECEDENTES PATOLÓGICOS **/
  $('#adulto_ima').removeClass('active')
  $('#adulto_ima').attr("aria-pressed", "false")
  $('#adulto_icc').removeClass('active')
  $('#adulto_icc').attr("aria-pressed", "false")
  $('#adulto_evp').removeClass('active')
  $('#adulto_evp').attr("aria-pressed", "false")
  $('#adulto_ecv').removeClass('active')
  $('#adulto_ecv').attr("aria-pressed", "false")
  $('#adulto_demencia').removeClass('active')
  $('#adulto_demencia').attr("aria-pressed", "false")
  $('#adulto_dm').removeClass('active')
  $('#adulto_dm').attr("aria-pressed", "false")
  $('#adulto_diabetes_lesion_organica').removeClass('active')
  $('#adulto_diabetes_lesion_organica').attr("aria-pressed", "false")
  $('#adulto_hemiplejia').removeClass('active')
  $('#adulto_hemiplejia').attr("aria-pressed", "false")
  $('#adulto_epoc').removeClass('active')
  $('#adulto_epoc').attr("aria-pressed", "false")
  $('#adulto_patologia_tejido_conectivo').removeClass('active')
  $('#adulto_patologia_tejido_conectivo').attr("aria-pressed", "false")
  $('#adulto_enfermedad_ulcerosa').removeClass('active')
  $('#adulto_enfermedad_ulcerosa').attr("aria-pressed", "false")
  $('#adulto_patologia_renal_moderada_o_grave').removeClass('active')
  $('#adulto_patologia_renal_moderada_o_grave').attr("aria-pressed", "false")
  $('#adulto_neoplasias').removeClass('active')
  $('#adulto_neoplasias').attr("aria-pressed", "false")
  $('#adulto_neoplasias_especifique').val('')
  $('#adulto_neoplasias_especifique').hide()
  $('#adulto_leucemias').removeClass('active')
  $('#adulto_leucemias').attr("aria-pressed", "false")
  $('#adulto_leucemias_especifique').val('')
  $('#adulto_leucemias_especifique').hide()
  $('#adulto_linfomas_malignos').removeClass('active')
  $('#adulto_linfomas_malignos').attr("aria-pressed", "false")
  $('#adulto_metastasis_colida').removeClass('active')
  $('#adulto_metastasis_colida').attr("aria-pressed", "false")
  $('#adulto_sida').removeClass('active')
  $('#adulto_sida').attr("aria-pressed", "false")
  $('#adulto_antecedentes_patologicos_otros_1').removeClass('active')
  $('#adulto_antecedentes_patologicos_otros_1').attr("aria-pressed", "false")
  $('#adulto_antecedentes_patologicos_otros_1_especifique').val('')
  $('#adulto_antecedentes_patologicos_otros_1_especifique').hide()
  $('#adulto_antecedentes_patologicos_otros_2').removeClass('active')
  $('#adulto_antecedentes_patologicos_otros_2').attr("aria-pressed", "false")
  $('#adulto_antecedentes_patologicos_otros_2_especifique').val('')
  $('#adulto_antecedentes_patologicos_otros_2_especifique').hide()
  /** TERAPIA ANTIMICROBIANA **/
  $('#adulto_terapia_antimicrobiana').removeClass('active')
  $('#adulto_terapia_antimicrobiana').attr("aria-pressed", "false")
  /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
  $('#adulto_frasco_1').removeClass('active')
  $('#adulto_frasco_1').attr("aria-pressed", "false")
  $('#adulto_frasco_1_opcion_1').val('0')
  $('#adulto_frasco_1_opcion_2').val('0')
  $('#adulto_frasco_1_opcion_1').hide()
  $('#adulto_frasco_1_opcion_2').hide()
  $('#adulto_frasco_2').removeClass('active')
  $('#adulto_frasco_2').attr("aria-pressed", "false")
  $('#adulto_frasco_2_opcion_1').val('0')
  $('#adulto_frasco_2_opcion_2').val('0')
  $('#adulto_frasco_2_opcion_1').hide()
  $('#adulto_frasco_2_opcion_2').hide()
  $('#adulto_frasco_3').removeClass('active')
  $('#adulto_frasco_3').attr("aria-pressed", "false")
  $('#adulto_frasco_3_opcion_1').val('0')
  $('#adulto_frasco_3_opcion_2').val('0')
  $('#adulto_frasco_3_opcion_1').hide()
  $('#adulto_frasco_3_opcion_2').hide()
  $('#adulto_hemocultivo_seguimiento_y_vigilancia').removeClass('active')
  $('#adulto_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed", "false")
  $('#adulto_fecha_primer_resultado_hemocultivo').val('')
  $('#adulto_contenedor_fecha_primer_resultado_hemocultivo').hide()
}

$(document).ready(function() {
    $('#modalSolicitudHemocultivoAdulto').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        formatearCamposModalSolicitudHemocultivoAdulto()
    });
});
// ========== REGISTRAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO FIN =========

// ========== EDITAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO INICIO =========
$(document).on('click','.editarSolicitudExamenEspecializadoHemocultivoAdulto', function () {

  let i = $(this).data('id');
  $('#solicitud_hemocultivo_adulto_data_id').val(i);

  //------------------------------------------------
  //  TIEMPO DE ENFERMEDAD
  $('#adulto_fecha_tiempo_enfermedad').val( $('#des_adulto_fecha_tiempo_enfermedad' + i).val() )

  //  ESCALA DE GLASGOW
  $('#adulto_respuesta_ocular').val( $('#des_adulto_respuesta_ocular' + i).val() )
  $('#adulto_respuesta_verbal').val( $('#des_adulto_respuesta_verbal' + i).val() )
  $('#adulto_respuesta_motora').val( $('#des_adulto_respuesta_motora' + i).val() )
  $('#adulto_total_escala_glasgow').val( $('#des_adulto_total_escala_glasgow' + i).val() )
  // SIGNOS VITALES
  $('#adulto_presion_arterial_x').val( $('#des_adulto_presion_arterial_x' + i).val() )
  $('#adulto_presion_arterial_y').val( $('#des_adulto_presion_arterial_y' + i).val() )
  $('#adulto_frecuencia_cardiaca').val( $('#des_adulto_frecuencia_cardiaca' + i).val() )
  $('#adulto_frecuencia_respiratorio').val( $('#des_adulto_frecuencia_respiratorio' + i).val() )
  $('#adulto_temperatura').val( $('#des_adulto_temperatura' + i).val() )
  // EXÁMENES DE LABORATORIO
  $('#adulto_creatinina').val( $('#des_adulto_creatinina' + i).val() )
  $('#adulto_diuresis').val( $('#des_adulto_diuresis' + i).val() )
  $('#adulto_procalcitonina').val( $('#des_adulto_procalcitonina' + i).val() )
  $('#adulto_pcr').val( $('#des_adulto_pcr' + i).val() )
  $('#adulto_fiO2').val( $('#des_adulto_fiO2' + i).val() )
  $('#adulto_leucocitos').val( $('#des_adulto_leucocitos' + i).val() )
  $('#adulto_plaquetas').val( $('#des_adulto_plaquetas' + i).val() )
  $('#adulto_bilirrubina').val( $('#des_adulto_bilirrubina' + i).val() )
  //ANTECEDENTES PATOLÓGICOS
  if ($('#des_adulto_ima' + i).val() == 'true') {
    $('#adulto_ima').attr("aria-pressed","true")
    $('#adulto_ima').addClass("active")
  }

  if($('#des_adulto_icc' + i).val() == 'true'){
    $('#adulto_icc').attr("aria-pressed","true")
    $('#adulto_icc').addClass("active")
  }
  if($('#des_adulto_evp' + i).val() == 'true'){
    $('#adulto_evp').attr("aria-pressed","true")
    $('#adulto_evp').addClass("active")
  }
  if($('#des_adulto_ecv' + i).val() == 'true'){
    $('#adulto_ecv').attr("aria-pressed","true")
    $('#adulto_ecv').addClass("active")
  }
  if($('#des_adulto_demencia' + i).val() == 'true'){
    $('#adulto_demencia').attr("aria-pressed","true")
    $('#adulto_demencia').addClass("active")
  }
  if($('#des_adulto_dm' + i).val() == 'true'){
    $('#adulto_dm').attr("aria-pressed","true")
    $('#adulto_dm').addClass("active")
  }
  if($('#des_adulto_diabetes_lesion_organica' + i).val() == 'true'){
    $('#adulto_diabetes_lesion_organica').attr("aria-pressed","true")
    $('#adulto_diabetes_lesion_organica').addClass("active")
  }
  if($('#des_adulto_hemiplejia' + i).val() == 'true'){
    $('#adulto_hemiplejia').attr("aria-pressed","true")
    $('#adulto_hemiplejia').addClass("active")
  }
  if($('#des_adulto_epoc' + i).val() == 'true'){
    $('#adulto_epoc').attr("aria-pressed","true")
    $('#adulto_epoc').addClass("active")
  }
  if($('#des_adulto_patologia_tejido_conectivo' + i).val() == 'true'){
    $('#adulto_patologia_tejido_conectivo').attr("aria-pressed","true")
    $('#adulto_patologia_tejido_conectivo').addClass("active")
  }
  if($('#des_adulto_enfermedad_ulcerosa' + i).val() == 'true'){
    $('#adulto_enfermedad_ulcerosa').attr("aria-pressed","true")
    $('#adulto_enfermedad_ulcerosa').addClass("active")
  }
  if($('#des_adulto_patologia_renal_moderada_o_grave' + i).val() == 'true'){
    $('#adulto_patologia_renal_moderada_o_grave').attr("aria-pressed","true")
    $('#adulto_patologia_renal_moderada_o_grave').addClass("active")
  }
  if($('#des_adulto_neoplasias' + i).val() == 'true'){
    $('#adulto_neoplasias').attr("aria-pressed","true")
    $('#adulto_neoplasias').addClass("active")
    $('#adulto_neoplasias_especifique').val( $('#des_adulto_neoplasias_especifique' + i).val() )
    $('#adulto_neoplasias_especifique').show()
  }
  if($('#des_adulto_leucemias' + i).val() == 'true'){
    $('#adulto_leucemias').attr("aria-pressed","true")
    $('#adulto_leucemias').addClass("active")
    $('#adulto_leucemias_especifique').val( $('#des_adulto_leucemias_especifique' + i).val() )
    $('#adulto_leucemias_especifique').show()
  }
  if($('#des_adulto_linfomas_malignos' + i).val() == 'true'){
    $('#adulto_linfomas_malignos').attr("aria-pressed","true")
    $('#adulto_linfomas_malignos').addClass("active")
  }
  if($('#des_adulto_metastasis_colida' + i).val() == 'true'){
    $('#adulto_metastasis_colida').attr("aria-pressed","true")
    $('#adulto_metastasis_colida').addClass("active")
  }
  if($('#des_adulto_sida' + i).val() == 'true'){
    $('#adulto_sida').attr("aria-pressed","true")
    $('#adulto_sida').addClass("active")
  }
  if($('#des_adulto_antecedentes_patologicos_otros_1' + i).val() == 'true'){
    $('#adulto_antecedentes_patologicos_otros_1').attr("aria-pressed","true")
    $('#adulto_antecedentes_patologicos_otros_1').addClass("active")
    $('#adulto_antecedentes_patologicos_otros_1_especifique').val( $('#des_adulto_antecedentes_patologicos_otros_1_especifique' + i).val() )
    $('#adulto_antecedentes_patologicos_otros_1_especifique').show()
  }
  if($('#des_adulto_antecedentes_patologicos_otros_2' + i).val() == 'true'){
    $('#adulto_antecedentes_patologicos_otros_2').attr("aria-pressed","true")
    $('#adulto_antecedentes_patologicos_otros_2').addClass("active")
    $('#adulto_antecedentes_patologicos_otros_2_especifique').val( $('#des_adulto_antecedentes_patologicos_otros_2_especifique' + i).val() )
    $('#adulto_antecedentes_patologicos_otros_2_especifique').show()
  }
  // TERAPIA ANTIMICROBIANA
  if($('#des_adulto_terapia_antimicrobiana' + i).val() == 'true'){
    $('#adulto_terapia_antimicrobiana').attr("aria-pressed","true")
    $('#adulto_terapia_antimicrobiana').addClass("active")
  }
  // SOLICITUD DE FRASCO DE HEMOCULTIVO
  if($('#des_adulto_frasco_1' + i).val() == 'true'){
    $('#adulto_frasco_1').attr("aria-pressed","true")
    $('#adulto_frasco_1').addClass("active")
    $('#adulto_frasco_1_opcion_1').val( $('#des_adulto_frasco_1_opcion_1' + i).val() )
    $('#adulto_frasco_1_opcion_2').val( $('#des_adulto_frasco_1_opcion_2' + i).val() )
    $('#adulto_frasco_1_opcion_1').show()
    $('#adulto_frasco_1_opcion_2').show()
  }
  if($('#des_adulto_frasco_2' + i).val() == 'true'){
    $('#adulto_frasco_2').attr("aria-pressed","true")
    $('#adulto_frasco_2').addClass("active")
    $('#adulto_frasco_2_opcion_1').val( $('#des_adulto_frasco_2_opcion_1' + i).val() )
    $('#adulto_frasco_2_opcion_2').val( $('#des_adulto_frasco_2_opcion_2' + i).val() )
    $('#adulto_frasco_2_opcion_1').show()
    $('#adulto_frasco_2_opcion_2').show()
  }
  if($('#des_adulto_frasco_3' + i).val() == 'true'){
    $('#adulto_frasco_3').attr("aria-pressed","true")
    $('#adulto_frasco_3').addClass("active")
    $('#adulto_frasco_3_opcion_1').val( $('#des_adulto_frasco_3_opcion_1' + i).val() )
    $('#adulto_frasco_3_opcion_2').val( $('#des_adulto_frasco_3_opcion_2' + i).val() )
    $('#adulto_frasco_3_opcion_1').show()
    $('#adulto_frasco_3_opcion_2').show()
  }
  if($('#des_adulto_hemocultivo_seguimiento_y_vigilancia' + i).val() == 'true'){
    $('#adulto_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed","true")
    $('#adulto_hemocultivo_seguimiento_y_vigilancia').addClass("active")
    $('#adulto_fecha_primer_resultado_hemocultivo').val( $('#des_adulto_fecha_primer_resultado_hemocultivo' + i).val() )
    $('#adulto_contenedor_fecha_primer_resultado_hemocultivo').show()
  }
  //------------------------------------------------

  // let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + i).val();
  // $('#solicitud_edit_descripcion_tipo_muestra').val(descripcion_tipo_muestra);
  $("#modalSolicitudHemocultivoAdulto").modal();

});
// ========== EDITAR SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO FIN =========

const mostrarBotonParaTipoDeMuestraMicrobiologia = (ind) => {
  $('#tipoMuestraMicrobiologia' + ind).show();
  $('#flag_descripcion_tipo_muestra' + ind).val('1');
}

// =========== REGISTRAR TIPO DE MUESTRA INICIO ==========================
$(document).on('click','.tipoMuestraMicrobiologia', function () {

  let i = $(this).data('id');
  $('#solicitud_data_id').val(i);
  $("#modalTipoMuestrabMicrobiologia").modal();

});

$(document).on('click', '#btnGuardarRegistroTipoMuestraMicrobiologia', function(e) {
  e.preventDefault();
  validarRegistroTipoMuestraMicrobiologia();
});

const validarRegistroTipoMuestraMicrobiologia = () => {
    let solicitud_descripcion_tipo_muestra = $('#solicitud_descripcion_tipo_muestra').val().trim();

    if(solicitud_descripcion_tipo_muestra === ""){
        Swal.fire({
            icon: 'error',
            title: 'El campo está vacío. Por favor, ingrese el tipo de muestra.',
            showConfirmButton: false,
            timer: 2000
        });
    return;
    }

    let i = $('#solicitud_data_id').val();
    $('#descripcion_tipo_muestra' + i).val(solicitud_descripcion_tipo_muestra);

    $('#modalTipoMuestrabMicrobiologia').modal('hide');
    //============Limpiamos los campos=========
    $('#solicitud_data_id').val('');
    $('#solicitud_descripcion_tipo_muestra').val('');
    //=========================================
    Swal.fire({
        icon: 'success',
        title: 'Se guardo con exito!',
        showConfirmButton: false,
        timer: 2000,
        onClose: function() {
          //console.log(`Se guardo el tipo de muestra en el input ${i}`);
          $('#tipoMuestraMicrobiologia' + i).hide();
          $('#editarTipoMuestraMicrobiologia' + i).show();
        }
    });
}

$(document).ready(function() {
    $('#modalTipoMuestrabMicrobiologia').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        $('#solicitud_data_id').val('');
        $('#solicitud_descripcion_tipo_muestra').val('');
    });
});
// =========== REGISTRAR TIPO DE MUESTRA FIN ==========================
// =========== EDITAR TIPO DE MUESTRA INICIO ==========================
$(document).on('click','.editarTipoMuestraMicrobiologia', function () {

  let i = $(this).data('id');
  let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + i).val();
  $('#solicitud_edit_data_id').val(i);
  $('#solicitud_edit_descripcion_tipo_muestra').val(descripcion_tipo_muestra);
  $("#modalEditTipoMuestrabMicrobiologia").modal();

});
$(document).on('click', '#btnGuardarEditTipoMuestraMicrobiologia', function(e) {
  e.preventDefault();
  validarEditTipoMuestraMicrobiologia();
});
const validarEditTipoMuestraMicrobiologia = () => {
    let solicitud_edit_descripcion_tipo_muestra = $('#solicitud_edit_descripcion_tipo_muestra').val().trim();

    if(solicitud_edit_descripcion_tipo_muestra === ""){
        Swal.fire({
            icon: 'error',
            title: 'El campo está vacío. Por favor, ingrese el tipo de muestra.',
            showConfirmButton: false,
            timer: 2000
        });
    return;
    }

    let i = $('#solicitud_edit_data_id').val();
    $('#descripcion_tipo_muestra' + i).val(solicitud_edit_descripcion_tipo_muestra);

    $('#modalEditTipoMuestrabMicrobiologia').modal('hide');
    //============Limpiamos los campos=========
    $('#solicitud_edit_data_id').val('');
    $('#solicitud_edit_descripcion_tipo_muestra').val('');
    //=========================================
    Swal.fire({
        icon: 'success',
        title: 'Se guardo con exito!',
        showConfirmButton: false,
        timer: 2000,
        onClose: function() {
          //console.log(`Se guardo el tipo de muestra en el input ${i}`);
          $('#editarTipoMuestraMicrobiologia' + i).prop('disabled', true);
        }
    });
}
// =========== EDITAR TIPO DE MUESTRA FIN ==========================

$(document).on('click','.addRadioPortatil', function () {
  let id = $(this).data("id");
  let addRadioPortatil = $(`#addRadioPortatil${id}`).attr("aria-pressed");
  console.log(addRadioPortatil,id);

  $(`#radio_portatil${id}`).val('NO');
  console.log(`#radio_portatil${id}`);
  if(addRadioPortatil == "true"){
    $(`#radio_portatil${id}`).val('SI');
  }
});

function AddExamenRad() {
  var newRow = "";
  var ind = $('#tblExamenRad tbody tr').length;

  if (ind > 0) {
    $('.id_radiologia').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });
    fila_medicamentos = $('#tblExamenRad tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];

  }

  var item_comparativo = '';
	item_comparativo+='<option value="0">--Seleccionar--</option>';
	item_comparativo+='<option value="1">SI</option>';
  item_comparativo+='<option value="2">NO</option>';

	var item_lado = '';
	item_lado+='<option value="0">--Seleccionar--</option>';
	item_lado+='<option value="1">Izquierdo</option>';
  item_lado+='<option value="2">Derecho</option>';
  item_lado+='<option value="3">Ambos</option>';

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de Radiografia" name="nombre_examen_rad[]" tabindex="8" required="" id="nombre_examen_rad' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind +'" name="id_examen_rad[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_codigo_rad" id="id_codigo_rad' + ind +'" name="id_codigo_rad[]" value="" data-id='+ind+'></td>';
  newRow +='<td><select class="form-control id_comparativo" id="cmbComparativo'+ind+'" name="id_comparativo[]" style="margin:0 20px;width:150px">'+item_comparativo+'</select></td>';
  newRow +='<td><select class="form-control id_lado" id="cmbLado'+ind+'" name="id_lado[]" style="margin:0 20px;width:150px">'+item_lado+'</select></td>';
  newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_rad_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_rad_observacion' +ind + '" name="examen_rad_observacion[]"></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 5px;width:50%" id="examen_rad_cantidad' +ind + '" name="examen_rad_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelectRad' + ind + '" name="miSelectRad[]" style="margin:0 10px;width:95%"></select></td>';
  newRow += `<td>
              <input type="hidden" id="radio_portatil${ind}" name="radio_portatil[]" value="NO" data-id="${ind}" />
              <button tabindex="100" type="button" id="addRadioPortatil${ind}" class="btn btn-lg btn-toggle addRadioPortatil" data-id="${ind}" data-toggle="button" aria-pressed="false" autocomplete="off">
                <div class="handle"></div>
              </button>
             </td>`;
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblExamenRad tbody').append(newRow);
  $("#examen_rad_cantidad" + ind).on('input', function() {
    var valor = $(this).val();
    if (valor === '0') {
      $(this).val(''); // Eliminar el valor si ingresan 0
    }
  });
  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  // const id_servicio = $('#id_servicio').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
  //tipo=3
	const selectElementRad = document.getElementById('miSelectRad' + ind);

  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id;
    option.textContent = item.nombre;
    selectElementRad.appendChild(option);
  });

  $("#nombre_examen_rad" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '/obtener_radiologia_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 3 + '/' + 105,
        //url: '/obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaRadiologiaSelecionado = $(".id_radiologia").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaRadiologiaSelecionado.includes(element.id));
          response(filtro);
        }
      })

    },
    select: function(e, ui) {
      var id_examen_rad = ui.item.id;
      var id_codigo_rad = ui.item.codigo_procedimiento;
      $("#id_examen_rad" + ind).val(id_examen_rad);
      $("#id_codigo_rad" + ind).val(id_codigo_rad);
      $("#nombre_examen_rad" + ind).prop("readonly", true);
      validaExamenRadiologiaSeleccionado($("#id_codigo_rad" + ind).val());
    }
  });

  $("#nombre_examen_rad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    if (id_examen_rad == "") {
      bootbox.alert("Falta ingresar el examen radiografia en esta fila");
      $("#examen_rad_cantidad" + ind).val("1");
      return false;
    }
  });
  $("#examen_rad_cantidad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    if (id_examen_rad == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen radiografia");
      $("#examen_rad_cantidad" + ind).val("1");
      return false;
    }
  });
}

function esNumero(event, id) {
  var charCode = (event.which) ? event.which : event.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}

function actualizararray(selectedId,selectedcodigo,ind) {
  if (procedimientosSeleccionados.length < 1) {
  let obj = {
    posicion: ind,
    id: selectedId,
    codigo:selectedcodigo
  }
  procedimientosSeleccionados.push(obj);
} else {
  let newArray = procedimientosSeleccionados.map((elemento) => {
    if (elemento.posicion === ind) {
      elemento.id = selectedId; // si el objeto existe, actualiza el id
      elemento.codigo =selectedcodigo;
    }
    return elemento;
  });
  if (!newArray.some((elemento) => elemento.posicion === ind)) {
    let obj = {
      posicion: ind,
      id: selectedId,
      codigo:selectedcodigo
    }
    newArray.push(obj); // si no existe, agrega un nuevo objeto
  }
  procedimientosSeleccionados = newArray; // actualiza el array original
}
console.log(procedimientosSeleccionados);

}
function abrirModal(ind) {
  $('#modal-content').empty();
  let arraydeprocedimientos =listIdDiagnosticoObjetos;
console.log(arraydeprocedimientos);

// Agregando los objetos del array a la tabla del modal
 let tableRows = arraydeprocedimientos.map(function (item) {
return '<tr class="table-row"><td>' + item.id + '</td><td>' + item.codigo + '</td><td>' + item.nombre + '</td></tr>';
}).join('');
$('#modalArrayList .modal-body #procedimientosTable tbody').html(tableRows);

// Agregar controlador de eventos al hacer clic en las filas de la tabla
$('#modalArrayList .modal-body #procedimientosTable tbody tr').on('click', function() {
  let selectedId = $(this).find('td:first').text();//aqui
  let selectedcodigo = $(this).find('td:nth-child(2)').text();
  let selectedName = $(this).find('td:nth-child(3)').text();
  console.log("posicion" + ind + "el id es" +selectedId );
  actualizararray(selectedId,selectedcodigo,ind);
  $('#modalArrayList').modal('hide');
});

$('#modalArrayList').modal('show');

}

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
  var newRow = "";
  //var ind = $('#example tbody tr').length;
  var ind = filasmedicamentos
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
    item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() +
      "</option>"
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
    newRow +='<input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>';
    newRow +='<input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>';
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

  newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';
  newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_producto'+ind+'"><i class="fa fa-plus"></i> </button></td>';
  newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
  newRow +='</tr>';
  $('#example tbody').append(newRow);
  $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();


  const selectElementProd = document.getElementById('miSelectProd' + ind);

  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id;
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

  function validproductoseleccionado(id_farmacia, btn) {//Jose
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


  $("#cmbMedicamento" + ind).on("change", function(e) {
    var flagx = 0;
    cmb = $(this);
    id_producto = $("#cmbMedicamento" + ind).val();
    id_user = {{ Auth::user()-> id }};

    $('.cmbMedicamento').each(function() {
      var ind_tmp = $(this).val();
      if ($(this).val() == id_producto) flagx++;
    });

    if (flagx > 1) {
      bootbox.alert("El producto farmaceutico ya ha sido ingresado");
      $("#cmbMedicamento" + ind).val("").trigger("change");
      return false;
    }
    id_farmacia_receta = $('#id_farmacia_receta').val();

    option = {
      url: '/cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
      type: 'GET',
      dataType: 'json',
      data: {}
    };

    $.ajax(option).done(function(data) {

      var cantidad = data.cantidad;
      var cantidadEstablecimiento = data.cantidadEstablecimiento;
      var cantidadAlmacen = data.cantidadAlmacen;
      $(cmb).closest("tr").find(".limpia_text").val("");
      //$(cmb).closest("tr").find("#nro_stocks").val(data);
      $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
      $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
      $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
      <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):
        ?>
      $(cmb).closest("tr").find(".tiempo_periodo").focus();
      <?php //endif;
        ?>
      $(cmb).closest("tr").find("#nro_med_solictados").val("");
      $(cmb).closest("tr").find("#nro_med_entregados").val("");
      $(cmb).closest("tr").find("#lotes_lote").val("");
      $(cmb).closest("tr").find("#lotes_cantidad").val("");
      $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
      $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
    });

  });
  filasmedicamentos++;
}
function soloNumerosMenosCero(e){
	var key = window.Event ? e.which : e.keyCode;
	//alert(key);
	return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}
function nro_interconsulta(){
	var dni_beneficiario = $('#dni_beneficiario').val();
	var id_farmacia = $('#id_farmacia').val();
	var x = 0;
	if(dni_beneficiario!=""){
        $.ajax({
                url: '/obtenerCantInterconsultas/' + dni_beneficiario+ '/' + id_farmacia,
                success: function (result) {
					if(result != null && result != ""){
		                for(var key in result) {
		                  x++;
		                }
					}
                }

        });

        return x;
    }
}
function getModalidad(){
  var id_tipo_atencion = $('#id_tipo_atencion').val();

  if (id_tipo_atencion == 1) {
    $("#divModalidad").show();
  } else {
    $("#divSubCons").hide();
    $("#divModalidad").hide();
  }
}
function getConsultorio(){

	var id_tipo_atencion 		= $('#id_tipo_atencion').val();
	var nivel     = $('#id_nivel').val();
	var valor;
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
    url: '/obtener_antecedentes_by_id_beneficiario/'+ dni_beneficiario,
    dataType: "json",
    success: function(resultado){
      $("#antecedente").val(resultado.antecedente);
      $("#antecedente_familiar").val(resultado.antecedente_familiar);
    }
  });

	//getProducto();
	/*
	if(id_tipo_atencion == 1){
		$("#divSubCons").show();
	}
	else{
		$("#divSubCons").hide();
	}
	*/
}
function llamarTv() {
  var id_cita = document.getElementById("id_cita").value;
  var cant_llamado_tv = document.getElementById("cant_llamado_tv").value;
  $.ajax({
    url: "/tv_llamar/" + id_cita,
    async: false,
    type: "GET",
    // dataType: "JSON",
    success: function(result) {

      if (result > 0) {
        // cant_llamado_tv += result + 1;
        document.getElementById("cant_llamado_tv").textContent = result;
        // document.getElementById("cant_llamado_tv").value = result;
        // console.log(result + " cant:" + cant_llamado_tv);
      } else {
        bootbox.alert("Actualmente se está llamando a otro paciente de este consultorio.");
      }
    },
    error: function(xhr, status) {
      console.log("error");
    },
    complete : function(xhr, status) {
      return false;
    }
  });
}
function posponerLlamadoTv() {
  var id_cita = document.getElementById("id_cita").value;
  $.ajax({
    url: "/tv_posponer_llamado/" + id_cita,
    async: false,
    type: "GET",
    success: function(result) {
      if (result=="2") {
        document.getElementById("cant_llamado_tv").textContent = "0";
        // console.log = result;
      } else {
        console.log = "error";
      }
    },
    complete : function(xhr, status) {
      return false;
    }
  });
}

function getProductoAndAddProducto() {
  getProducto();
  //AddProducto();
}

getConsultorio();

const validaExamenSeleccionado = (id_examen_lab) => {
  let dni_beneficiario = $('#dni_beneficiario').val();
  console.log({
    'dni_beneficiario' : dni_beneficiario,
    'id_examen_lab' : id_examen_lab,
  });
  $.ajax({
      url: '/validacion_beneficiario_dias_por_examen_rua/' + dni_beneficiario + '/' + id_examen_lab,
      success: function (result) {
        let msg = "";
        let n = 0;
        let confirmar_toma = "";
        console.log(["result",result]);
        if (result.examenes.length > 0) {
          console.log(["result > 0",result]);
          for (let key of result.examenes) {
            if (n == 0) {
                msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.materno_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de laboratorio pendientes del examen seleccionado en los últimos "+result.dias.option_value+" días, CANTIDAD : " + key.total_registros + "</h1>";
                msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                msg += "<thead>";
                msg += "<tr>";
                msg += "<th>Código</th>";
                msg += "<th>Descripción Corta</th>";
                msg += "<th>Tipo de Atención</th>";
                msg += "<th>Servicio</th>";
                msg += "<th>Especialista</th>";
                msg += "<th>Fecha de Orden</th>";
                msg += "<th>Estado</th>";
                msg += "<th>Dias Transcurridos</th>";
                msg += "</tr>";
                msg += "</thead>";
                msg += "<tbody>";
            }
            msg += "<tr><td>" + key.codigo_lab + "</td>";
            msg += "<td>" + key.descripcion_corta + "</td>";
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              if (key.id_evaluacion > 0) {
                msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
              } else {
                msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
              }
            }
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.nombre_servicio}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
            }
            msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
            msg += "<td>" + (key.fecha_expedicion) + "</td>";
            if (key.confirmar_toma == null || key.confirmar_toma == "") {
              confirmar_toma = "Pendiente";
            } else {
              switch (key.confirmar_toma) {
                case "0":
                  confirmar_toma = "Procesado";
                  break;
                case "1":
                  confirmar_toma = "Cancelado";
                  break;
                case "2":
                  confirmar_toma = "Procesado";
                  break;
                case "3":
                  confirmar_toma = "Reingresado";
                  break;
                case "4":
                  confirmar_toma = "No Enviado";
                  break;
              }
            }
            msg += "<td>" + confirmar_toma + "</td>";
            msg += "<td>" + (key.dias_transcurridos) + "</td></tr>";
            n++;
          }
          msg += "</tbody></table>";
          $("#contenido-table-pendiente").html(msg);
          // Swal.fire({
            //   title: "Alerta Laboratorio",
            //   html: msg,
            //   width: 1000
            // });
            $("#showDetalleExamenPendiente").modal();
          }
        }
      });
}

const validaExamenRadiologiaSeleccionado = (id_examen_rad) => {
  let dni_beneficiario = $('#dni_beneficiario').val();
  console.log({
    'dni_beneficiario' : dni_beneficiario,
    'id_examen_rad' : id_examen_rad,
  });
  $.ajax({
  url: '/validacion_beneficiario_dias_por_examen_rad/' + dni_beneficiario + '/' + id_examen_rad,
  success: function (result) {
    let msg = "";
    let n = 0;
    console.log(["result",result]);
    if (result.length > 0) {
      console.log(["result > 0",result]);
      for (let key of result) {
        if (n == 0) {
            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.materno_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de Imagenes pendientes del examen seleccionado en los últimos 3 meses, CANTIDAD : " + key.total_registros + "</h1>";
            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
            msg += "<thead>";
            msg += "<tr>";
            msg += "<th>Código</th>";
            msg += "<th>Descripción Corta</th>";
            msg += "<th>Tipo de Atención</th>";
            msg += "<th>Servicio</th>";
            msg += "<th>Especialista</th>";
            msg += "<th>Fecha de Orden</th>";
            msg += "<th>Estado</th>";
            msg += "<th>Dias Transcurridos</th>";
            msg += "</tr>";
            msg += "</thead>";
            msg += "<tbody>";
        }
        msg += "<tr><td>" + key.codigo_rad + "</td>";
        msg += "<td>" + key.descripcion_corta + "</td>";
        if (key.id_tipo_atencion == 1) {
          msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
        }
        if (key.id_tipo_atencion == 2) {
          if (key.id_evaluacion > 0) {
            msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
          } else {
            msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
          }
        }
        if (key.id_tipo_atencion == 1) {
          msg += "<td>" + `${key.nombre_servicio}` + "</td>";
        }
        if (key.id_tipo_atencion == 2) {
          msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
        }
        msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
        msg += "<td>" + (key.fecha_expedicion) + "</td>";
        if (key.flag_validated == '1') {
          msg += "<td style='font-weight : 700;'>ATENDIDO</td>";
        } else {
          if (key.flag_informe == '1') {
            msg += "<td style='font-weight : 700;'>PENDIENTE VALIDAR</td>";
          } else {
            if (key.flag_cita_imagenes == 1 ) {
              msg += "<td style='font-weight : 700;'>PENDIENTE CITADO</td>";
            } else if (key.confirmar_toma == null || key.confirmar_toma == '') {
              msg += "<td style='font-weight : 700;'>RECIENTE</td>";
            }
          }
        }

        msg += "<td>" + (key.dias_transcurridos) + "</td></tr>";
        n++;
      }
      msg += "</tbody></table>";
      $("#contenido-table-pendiente-radiologia").html(msg);
      // Swal.fire({
        //   title: "Alerta Laboratorio",
        //   html: msg,
        //   width: 1000
        // });
        $("#showDetalleExamenPendienteRadiologia").modal();
      }
    }
  });
}

$(document).on('click', '.toggleExamenesResult', function () {
  let registro = $(this).data('registro');
    $(".elementoOcultarResult" + registro).toggle();

    let icono = $(this).find('i');

    // icono.toggleClass('icon-visible');
    icono.toggleClass('icon-hidden-result');
});

const consultaWsResultExamenSeleccionado = (id_examen_lab) => {
  let dni_beneficiario = $('#dni_beneficiario').val();
  let nombreCompleto = `${$('#paterno_beneficiario').val()} ${$('#materno_beneficiario').val()}, ${$('#nombre_beneficiario').val()}`;
  console.log({
    'dni_beneficiario' : dni_beneficiario,
    'id_examen_lab' : id_examen_lab,
  });
  $.ajax({
    url: '/consulta_ws_result_beneficiario_examen_rua/' + dni_beneficiario + '/' + id_examen_lab,
    success: function (result) {
      let res = result;
      console.log("valor ws");
      console.log(res);

      let numerosImpresos = new Set();
      let n = 0;
      let n2 = 0;
      let msg = "";

      if (res == "vacio") {
        return;
      }
      if (res.length > 0) {
        console.log("Entro en la condición");
        if (n == 0) {
              // msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + nombreCompleto + " con DNI " + dni_beneficiario + " tiene resultados de la prueba de laboratorio seleccionado de los últimos 3 meses</h1>";
              msg += `<table class="table table-list-search" style="font-size: 11px !important;">`;
              msg += `<thead class="thead-resultados">`;
              msg += "<tr>";
              msg += `<th class="encabezado"></th>`;
              msg += `<th class="encabezado">Código de orden</th>`;
              msg += `<th class="encabezado">F.Toma de Muestra</th>`;
              msg += `<th class="encabezado">Tipo de Atención</th>`;
              msg += `<th class="encabezado">Establecimiento</th>`;
              msg += "</tr>";
              msg += "</thead>";
              msg += "<tbody>";
          }
        for (let key of res) {
          console.log( key.p_nombre );
          let numeroActual = key.o_numero;
          let elementoOcultar = "elementoOcultarResult" + n;
          console.log(numeroActual);
          // Verificar si el número ya se ha impreso
          if (!numerosImpresos.has(numeroActual)) {
            // Si no se ha impreso, imprimir y agregar al conjunto
            console.log('o_numero:', numeroActual);

            msg += `<tr><td><a class="toggleExamenesResult" data-registro="${n}"><i class="fa fa-plus-circle icon-visible" aria-hidden="true"></i></a></td>`;
            msg += `<td>${numeroActual}</td>`;
            msg += `<td>${key.o_fecha_toma}</td>`;
            msg += `<td>${key.s_nombre}</td>`;
            msg += `<td>${key.c_nombre}</td>`;
            msg += `</tr>`;
            msg += `<tr class="thead-resultados-detalle ${elementoOcultar} hide-element-result">`;
            msg += `<th class="encabezado">Examen</th>`;
            msg += `<th class="encabezado">Resultado</th>`;
            msg += `<th class="encabezado">Rango Referencial</th>`;
            msg += "</tr>";
            numerosImpresos.add(numeroActual);
            n2 = n;
            n++;
          }
          elementoOcultar = "elementoOcultarResult" + n2;
          msg += `<tr class="tbody-resultados-detalle ${elementoOcultar} hide-element-result">`
          msg += `<td>${key.p_nombre}</td>`;
          msg += `<td>${key.l_resultado}</td>`;
          msg += `<td>${key.l_ref_inf} - ${key.l_ref_sup}</td>`;
          msg += "</tr>";


        }
        //console.table(result);
          msg += "</tbody></table>";
          $("#contenido-table-result").html(msg);
          // Swal.fire({
          //   title: "Resultados Laboratorio",
          //   html: msg,
          //   width: 1000
          // });

          $("#showDetalleExamenResult").modal();
      }
    }
  });
}

  // TAGSITEDS
  async function generarCodigoSiteds() {
    return new Promise((resolve, reject) => {
      Swal.fire ({
        title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
        html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        },
      })
      const hospitalizacion = {!! json_encode($hospitalizacion) !!};
      const body = {
          "idCorrelativo": hospitalizacion.id,
          "aseguradoId": hospitalizacion.id_asegurado,
          "establecimientoId": hospitalizacion.id_establecimiento,
          "tipoAtencionId": "3",
          "servicioId": hospitalizacion.id_consultorio
      };
      $.ajax({
        url:'/v2/ws-dirsapol-siteds',
        type:'POST',
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(body),
        success: (result) => {
          resolve(result);
        },
        error: function(xhr, status, error){
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
            Swal.fire({
                icon: 'error',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
            reject(xhr.responseJSON);
        },
        complete: (xhr, status) => {
          Swal.hideLoading();
          Swal.close();
        }
      })
    });
  }

  // verificarRegla1();
  //TAGREGLA1
  function verificarRegla1() {
    const dniBeneficiario = $('#dni_beneficiario').val();
    $("#btnPrestacion").prop('disabled', false);
    Swal.fire ({
      title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Verificando asegurado</span></div>",
      html: "<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      onBeforeOpen: () => {
        Swal.showLoading ()
      },
    })
    $.ajax({
      url: `/regla1/${dniBeneficiario}`,
      type:'GET',
      contentType: "application/json",
      dataType: "json",
      success: (result, textStatus, jqXHR) => {
        console.log(result);
        Swal.fire({
          icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
          title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Consulta exitosa.</span></div>",
          showConfirmButton: false,
          timer: '1500'
        });
        $("#infoRegla1").prop('hidden', false);
        if(!result.success) {
          $("#btnPrestacion").prop('disabled', true);
          $("#infoRegla1").html('El beneficiario no cuenta con el seguro SALUDPOL activo, no se podra continuar con el registro de la atencion medica.');
        } else {
          $("#infoRegla1").html(`El beneficiario registrado se encuentra con seguro SALUDPOL activo, bajo el numero de documento ${dniBeneficiario}.`);
        }
      },
      error: function(xhr, status, error){
        console.log(error);
        const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
        Swal.fire({
          icon: 'error',
          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
          html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
          confirmButtonText: 'ENTENDIDO',
        });
      },
    });
  }

  function calcularFechaProbableParto() {
    var ultimaMestruacion = $("#fecha_ultima_mestruacion").datepicker("getDate");
    var fechaProbableParto = new Date(ultimaMestruacion.getTime() + (7 * 24 * 60 * 60 * 1000)); // Sumar 7 días
    fechaProbableParto.setMonth(fechaProbableParto.getMonth() - 3); // Restar 3 meses
    fechaProbableParto.setFullYear(fechaProbableParto.getFullYear() + 1); // Sumar 1 año
    var formattedDueDate = $.datepicker.formatDate('dd/mm/yy', fechaProbableParto);
    $("#fecha_probable_parto").val(formattedDueDate);
  }

  function calcularTrimestre() {
    var ultimaMestruacion = $("#fecha_ultima_mestruacion").datepicker("getDate");
    var fechaHoy = new Date();
    var diffMilisegundos = fechaHoy.getTime() - ultimaMestruacion.getTime();
    var diffSemanas = Math.floor(diffMilisegundos / (1000 * 60 * 60 * 24 * 7)); // Obtener la diferencia en semanas
    if (diffSemanas >= 45 ) {
      bootbox.alert("INGRESAR UNA FECHA CORRECTA MAXIMO DE 45 SEMANAS");
      $('#fecha_probable_parto').val('');
      $('#semanas_gestacion').val('');
    } else {
      const semanas = "SEMANA N° " + diffSemanas;
      $("#semanas_gestacion").val(semanas);
    }
  }

  $(document).ready(function () {
    $("#fecha_ultima_mestruacion").datepicker({
      useCurrent: false,
      dateFormat: "dd/mm/yy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left",
      /* minDate: 0 */
    });

    $("#fecha_ultima_mestruacion").on('change', function() {
      calcularFechaProbableParto();
      calcularTrimestre();
    })

    $("#fecha_probable_parto").datepicker({
      useCurrent: false,
      dateFormat: "dd/mm/yy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left",
      minDate: 0
    });

    $("#fecha_parto").datepicker({
      useCurrent: false,
      dateFormat: "dd/mm/yy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left",
      /* minDate: 0 */
    });

    $('#hora_parto').inputmask("99:99", {
      placeholder: "00:00",
      insertMode: false,
      clearIncomplete: true,
      oncomplete: function() {
        validateHoraParto($(this).val());
      }
    });

    handlerShowPanelSaludMaterna();
  });


  $("#toogleGestacion").on("click", function () {
    const toogleValue = String($(this).attr("aria-pressed")) === "false" ? true : false;
    $("#frmGestacionActive").val(String(toogleValue));
    handleSectionCollapse(toogleValue, "frmGestacion");
    if (toogleValue) {
      handleSectionCollapse(false, "frmPuerpera");
      $("#frmPuerperaActive").val('false');
      handleToogleBtn(false, 'tooglePuerpera');
    }
  });


  $("#tooglePuerpera").on("click", function () {
    const toogleValue = String($(this).attr("aria-pressed")) === "false" ? true : false;
    $("#frmPuerperaActive").val(String(toogleValue));
    handleSectionCollapse(toogleValue, "frmPuerpera");
    handleSectionCollapse(toogleValue, "fieldEventosEp");
    if (toogleValue) {
      handleSectionCollapse(false, "frmGestacion");
      $("#frmGestacionActive").val('false');
      handleToogleBtn(false, 'toogleGestacion');
    }
  });

  $("#hora_parto").on("input", function () {
    console.log($(this).val());
  });

  function handlerShowPanelSaludMaterna() {
    const cita = {!! json_encode($cita) !!};
    if (cita) {
      const edadArray = cita.edadpaciente.split(' ');
      const edad = edadArray[0].trim() || 1;
      if (String(cita.sexo) === "F" && (parseInt(cita.id_servicio) === 77 || parseInt(cita.id_servicio) === 131) && (parseInt(edad) >= 12) && (parseInt(edad) <= 55)) {
        $("#panelSaludMaterna").show();
        // $("#panelGestacion").show();
      }
    }
  }

  function validacionDocSoloNumeros(event) {
    var key = window.Event ? event.which : event.keyCode;
    if (key < 48 || key > 57) {
        event.preventDefault();
    }
  }

  function addRowTableNeonatos() {
    const isValidLastRow = validateLastRowTableNeonatos();
    if (!isValidLastRow) {
      Swal.fire({
        icon: "warning",
        title: '<p>HOSPITALIZACIÓN</p>',
        html: '<p>TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO NEONATO</p>',
        confirmButtonText: "ENTENDIDO"
      });
      return null;
    }

    const indexRow = $('#tableNeonatos tbody tr').length + 1;

    const tipoDocsNati = {!! json_encode($tipo_doc_nati) !!};
    let optionsTipodocs = ''
    if (tipoDocsNati && tipoDocsNati.length > 0) {
      tipoDocsNati.forEach(tipo => {
        optionsTipodocs += `<option value="${tipo.id}">${tipo.nombre}</option>`
      });
    }

    const newRow = `
      <tr>
        <td>
          <select class="form-control input-sm" name="estado_vida_puerpera[]" id="estado_vida_puerpera${indexRow}">
              <option value=""> -Seleccionar-</option>
              <option value="VIVO">VIVO</option>
              <option value="MUERTO">MUERTO</option>
          </select>
        </td>
        <td>
          <select class="tipo_doc form-control input-sm typeahead tt-query" name="tipo_documento_puerpera[]" id="tipo_documento_puerpera${indexRow}" style="width:100%">
            <option value=""> -Seleccionar-</option>
            ${optionsTipodocs}
          </select>
        </td>
        <td>
          <input
            type="text"
            onkeypress="return validacionDocSoloNumeros(event)"
            placeholder="Num. documento"
            name="numero_documento_puerpera[]"
            id="numero_documento_puerpera${indexRow}"
            class="form-control input-sm"
            maxlength="10"
            pattern="[0-9]+"
            onkeypress="return /^[a-zA-Z0-9]+$/i.test(event.key)">
        </td>
        <td>
          <select class="form-control input-sm typeahead tt-query" name="sexo_puerpera[]" id="sexo_puerpera${indexRow}">
              <option value=""> -Seleccionar-</option>
              <option value="M">MASCULINO</option>
              <option value="F">FEMENINO</option>
          </select>
        </td>
        <td>
          <input
              type="text"
              placeholder="0"
              name="talla_puerpera[]"
              id="talla_puerpera${indexRow}"
              class="form-control input-sm typeahead tt-query"
              maxlength="6"
              onkeypress="return /^[0-9.]+$/i.test(event.key)">
        </td>
        <td>
          <input
              type="text"
              placeholder="0"
              name="peso_puerpera[]"
              id="peso_puerpera${indexRow}"
              class="form-control input-sm"
              maxlength="6"
              onkeypress="return /^[0-9.]+$/i.test(event.key)">
        </td>
        <td style="text-align: center;">
          <button id="btnRemoveRowTableNeonatos" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
        </td>
        <td>
          <input
              type="hidden"
              class="form-control input-sm"
              id="id_parto_puerpera${indexRow}"
              name="id_parto_puerpera[]"
              data-id=${indexRow}>
        </td>
      </tr>`;
    $('#tableNeonatos tbody').append(newRow);

    $('#tableNeonatos').on('click', '#btnRemoveRowTableNeonatos', function(){
      const row = $(this).closest('tr');
      row.remove();
    });
  }

  function validateLastRowTableNeonatos() {
    const lastRow = $("#tableNeonatos tbody tr:last");
    let isValid = true;
    lastRow.find('input:not([type="hidden"]), select').each(function() {
      clearInputError($(this).attr("id"));
        if ($(this).val() === "" || $(this).val() == null) {
          isValid = false;
          markInputError($(this).attr('id'));
        }
    });
    return isValid;
  }

  function markInputError(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-group, td' );
    parentDiv.addClass("has-error");
    inputElement.after(`<span class="label-help" style="color: #dc3545; font-size: 11px">${messsageError || "campo requerido"}</span>`);
  }

  function clearInputError(nameInput) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest('.form-group, td');
    inputElement.siblings('.form-control-feedback, .label-help').remove();
    parentDiv.removeClass("has-error has-success");
  }

  function handleSectionCollapse(valueToogle, nameCollapse) {
    if (valueToogle) {
      $(`#${nameCollapse}`).slideDown("slow");
    } else {
      $(`#${nameCollapse}`).slideUp("slow");
    }
  }

  function handleToogleBtn(valueToogle, nameToogle) {
    if (valueToogle) {
      $(`#${nameToogle}`).addClass('active');
      $(`#${nameToogle}`).attr('aria-pressed', 'true');
    } else {
      $(`#${nameToogle}`).removeClass('active');
      $(`#${nameToogle}`).attr('aria-pressed', 'false');
    }
  }

  function validateHoraParto(input) {
    if (!input) {
        return;
    }
    var now = moment(); // Hora actual
    var inputTime = moment(input, "HH:mm");
    if (inputTime.isAfter(now, 'minute')) {
      Swal.fire({
        icon: "error",
        html: `<p>La hora de parto ingresada no puede ser mayor que la hora actual: ${now.format("HH:mm")}.</p>`,
        confirmButtonText: "ENTENDIDO"
      });
        $('#hora_parto').val('');
    }
  }

  function handleToogleBtn(valueToogle, nameToogle) {
    if (valueToogle) {
      $(`#${nameToogle}`).addClass('active');
      $(`#${nameToogle}`).attr('aria-pressed', 'true');
    } else {
      $(`#${nameToogle}`).removeClass('active');
      $(`#${nameToogle}`).attr('aria-pressed', 'false');
    }
  }

  $(document).ready(function () {
      $('#tabs a').on('shown.bs.tab', function (e) {
        const targetTab = $(e.target).attr("href"); // Obtener el id de la pestaña activa
        if (targetTab === "#tab4default") {
          Swal.fire({
            icon: "warning",
            html: `<div style="padding: 0px 20px 20px 20px">
                    <p style="font-size: 16px"><b>La historia clínica contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos</b></p><br>
                    <span style="font-size: 14px; color: #d9534f"><b>Ley N.° 26659</b></span><br>
                    <span style="font-size: 14px; color: #d9534f"><b>Ley de Protección de la Historia Clínica</b></span>
                  </div>`,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            customClass: {
              container: 'swal-confidencialidad-hspcu-backdrop'
            }
          });
          generar_marca_agua_historial_hspcu();

          $.ajax({
            url: '/auditoria-hc/registrar',
            type: 'POST',
            data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
              origen: 'Historia Clínica HSP(HA)-CI1',
              doc_buscado: $('#dni_beneficiario').val(),
              id_tipo_atencion: null
            }
          });
        }
      });
    });

  $("#id_area").change(function (e) {
    e.preventDefault();
    $('#id_cama').html('').change();
    let id_area = $(this).val();
    let id_establecimiento = $('#id_establecimiento').val();
    let nombre_tipo_atencion = 'HOSPITALIZACION';
    if (id_area != "0") {
      $.ajax({
        url: '/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto/' + id_establecimiento + '/' + nombre_tipo_atencion + '/' + id_area,
        success: function(result){
          let newOption = "<option value='0'>--SELECCIONAR--</option>";
          if (result.length === 1) {
            newOption += "<option value='"+result[0].id+"' selected>"+result[0].nombre+"</option>";
          } else {
            $(result).each(function (ii, oo) {
              newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
            });
          }
          $('#id_cama').html(newOption).change();
          $('#id_cama').focus();
        }
      });
    }
  });

  $("#select_cambiar_cama").change(function (e) {
    e.preventDefault();
    let valor_cambiar_cama = $(this).val();
    switch (valor_cambiar_cama) {
      case "0":
        $(".campo-rotacion").hide();
        break;
      case "1":
        $(".campo-rotacion").show();
        break;
    }
  });

  $("#id_cama").change(function (e) {
    e.preventDefault();
    let id_cama_seleccionada = $(this).val();
    let id_cama_actual_paciente = $("#id_cama_actual_paciente").val();

    if (id_cama_seleccionada != "0") {
      if (id_cama_seleccionada == id_cama_actual_paciente) {
        bootbox.alert("La cama seleccionada es la misma cama donde se encuentra el paciente");
      }
    }
  });

  $("#id_upss_encargado_regularizacion").change(function (e) {
    e.preventDefault();
    let id_upss = $(this).val();
    $('#id_servicio_encargado_regularizacion').html('');
    $.ajax({
      url: '/obtener_servicio_by_id_upss2/'+ id_upss,
      success: function(result){
        let newOption = "<option value='0'>--SELECCIONAR--</option>";
        if (result.length === 1) {
          newOption += "<option value='"+result[0].id+"' selected>"+result[0].servicio+"</option>";
        } else {
          $(result).each(function (ii, oo) {
              newOption += "<option value='"+oo.id+"'>"+oo.servicio+"</option>";
          });
        }
        $('#id_servicio_encargado_regularizacion').html(newOption).change();
        $('#id_servicio_encargado_regularizacion').focus();
      }
    });
  });

  // $("#btn_cambiar_o_mantener_cama").click(function(e) {
  //   e.preventDefault();
  //   let is_cambia_cama = $("#is_cambia_cama").val();
  //   if (is_cambia_cama == "0") {
  //     $("#btn_cambiar_o_mantener_cama").html("<i class='fa fa-check-square'></i> Matener Cama Actual");
  //     $("#is_cambia_cama").val("1");
  //     //Mostrar area destino y cama destino
  //     $("#div_campos_rotacion").show();
  //   } else {
  //     $("#btn_cambiar_o_mantener_cama").html("<i class='fa fa-check-square'></i> Cambiar Cama");
  //     $("#is_cambia_cama").val("0");
  //     //Ocultar area destino y cama destino
  //     $("#div_campos_rotacion").hide();
  //   }
  // });


  <?php if($nivel == 3): ?>
		var grupos = [
	        ["82803.01", "36600"],
	        ["82575", "82565"],
	        ["89190", "87999"],
	        ["87070", "87184"],
	        ["87163", "87184"],
	        ["87045", "87184"],
	        ["87040.01", "87184"],
	        ["87040.02", "87184"]
	    ];
	<?php else:?>
		var grupos = [];
	<?php endif;?>

    function verificarFaltantes(seleccionados) {
        var mensajes = [];

        $.each(grupos, function (index, grupo) {
            var presentes = grupo.filter(codigo => seleccionados.includes(codigo));

            if (presentes.length === 1) { // Solo uno del grupo está presente
                var faltante = grupo.find(codigo => !seleccionados.includes(codigo));
                mensajes.push("En Examenes de laboratorio: Falta la prueba " + faltante + " que está relacionada con " + presentes[0]);
            }
        });

        return mensajes.length > 0 ? mensajes.join("\n") : "";
    }

  $("#is_estable").on("mousedown", function () {
    // Guardamos el valor actual antes del cambio
    is_estable_auxiliar = $(this).val();
  });

  $("#is_estable").change(function() {

    // Inicio validar si hay interconsultas agregadas
    let cant_registros = $('#tblInterconsulta tbody tr').length;
    let has_interconsulta_agregada = false;

    if (cant_registros > 0) {

      let interconsulta_filas = $('#tblInterconsulta tbody').children('tr');

      for (let i = 0; i < cant_registros; i++) {
        $('.cmbInterconsultaServicios').each(function() {
          // if ($(this).val() != "") {
            has_interconsulta_agregada = true;
            return false;
          // }
        });
      }
    }
    // Fin validar si hay interconsultas agregadas

    if (has_interconsulta_agregada) {
      bootbox.confirm({
        message: "Al cambiar la <b>Condición del Paciente</b> se anulará los registros de solicitud de interconsulta ya adicionados.<br><br>¿Desea continuar?",
        buttons: {
          confirm: {
              label: 'Continuar',
              className: 'btn-success'
          },
          cancel: {
              label: 'Cancelar',
              className: 'btn-danger'
          }
        },
        callback: function (result) {
          if (result) {
            const tbody_tblInterconsulta = document.querySelector("#tblInterconsulta tbody");
            tbody_tblInterconsulta.innerHTML = "";
          } else {
            $("#is_estable").val(is_estable_auxiliar);
          }
        }
      });
    }

  });
//================================================================================================= Modal chklist laboratorio
  function cargarDiagnosticosTodos() {
    $("#contenedorDX").html("Cargando...");
    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    var genero = (sexo == 'M') ? 1 : 2;

    $.ajax({
        url: '/obtener_laboratorio_convenio/all/' + nivel + '/' + genero + '/' + id_tipo_atencion,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // console.log("ITEM EJEMPLO:", data[0]);
            let total = data.length;
            let clase = 'col1';
            let modalClase = 'modal-col1';

            if (total > 5 && total <= 10) { clase = 'col2'; modalClase = 'modal-col2'; }
            else if (total > 10 && total <= 20) { clase = 'col3'; modalClase = 'modal-col3'; }
            else if (total > 20) { clase = 'col4'; modalClase = 'modal-col4'; }

            $("#contenedorDX").removeClass("col1 col2 col3 col4").addClass(clase);
            $("#modalDX").removeClass("modal-col1 modal-col2 modal-col3 modal-col4").addClass(modalClase);

            let grupos = {};
            data.forEach(function(item) {
                let idCat = item.id_categoria || "0";
                let nombreCat = item.categoria_nombre || "SIN CATEGORÍA";
                if (!grupos[idCat]) {
                    grupos[idCat] = { nombre: nombreCat, items: [] };
                }
                grupos[idCat].items.push(item);
            });

            let html = "";
            Object.keys(grupos).sort().forEach(function(idCat) {
                html += `
                  <h4 style="margin: 6px 0 4px 0 !important;font-weight: 600;color: #3c763d;border-bottom: 1px solid white;padding-bottom: 2px;padding-top: 12px;font-size: 15px;text-transform: uppercase;">
                      ${grupos[idCat].nombre}
                  </h4>
                `;
                grupos[idCat].items.forEach(function(item) {
                    html += `
                        <label style="display:block; margin-left:10px;">
                            <input type="checkbox"
                                class="chk_rualab"
                                name="chk_rualab[]"
                                data-id="${item.id}"
                                data-codigo="${item.codigo_procedimiento || ''}"
                                data-categoria="${item.id_categoria || ''}"
                                data-label="${item.label || ''}"
                                style="transform: scale(1.4); margin-right:8px;">
                            ${item.label || ''}
                        </label>
                    `;
                });
            });

            $("#contenedorDX").html(html);

              const unionMap = {
                  // Bidireccionales
                  "82803": "36600",
                  "36600": "82803",
                  "82575": "82565",
                  "82565": "82575",
                  "82043.01": "84999.09",
                  "84999.09": "82043.01",
                  "89190": "87999",
                  "87999": "89190",
                  "87115": "84999.13",
                  "84999.13": "87115",
                  // Unidireccionales
                  "87070": "82020",
                  "87163": "87184",
                  "87040.01": "87184",
                  "87040.03": "87184",
                  "87045": "87184"
                  // De uno a muchos (ejemplo)
                  //"87184": ["87070", "87163", "87040.01", "87040.03", "87045"]
              };


              $(".chk_rualab").change(function() {
                  const codigo = $(this).data("codigo");
                  const union = unionMap[codigo];
                  if (union) {
                      if (Array.isArray(union)) {
                          union.forEach(u => {
                              const target = $(`.chk_rualab[data-codigo='${u}']`);
                              if (target.length) target.prop('checked', $(this).prop('checked'));
                          });
                      } else {
                          const target = $(`.chk_rualab[data-codigo='${union}']`);
                          if (target.length) target.prop('checked', $(this).prop('checked'));
                      }
                  }
              });
            }
    });
  }

  //================================================================================== Abrir modal y cargar datos
  $('#modalDX').on('show.bs.modal', function () {
      cargarDiagnosticosTodos();
  });

  //================================================================================== Función para asignar valores a la fila
  function asignarValoresDesdeModal(item, ind) {
      let id_examen_lab = item.id_examen_lab || item.id;
      let codigo_procedimiento = item.codigo_procedimiento;
      let id_categoria_examen_lab = item.id_categoria;
      let nombre_examen_lab = item.label || item.value;

      //============================================================================= Asignar a los campos de la fila
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#id_categoria_examen_lab" + ind).val(id_categoria_examen_lab);
      $("#cod_lab" + ind).val(codigo_procedimiento);
      $("#nombre_examen_lab" + ind).val(nombre_examen_lab).prop("readonly", true);

      if(id_examen_lab) {
          validaExamenSeleccionado(id_examen_lab);
          consultaWsResultExamenSeleccionado(id_examen_lab);
      }

      if (id_categoria_examen_lab == "9") mostrarBotonParaTipoDeMuestraMicrobiologia(ind);
      if (codigo_procedimiento == '87040') mostrarBotonParaSolicitudHemocultivoSegunEdad(ind);
      if (codigo_procedimiento == '87163') mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos(ind);

      /*let seleccionados = $("input[name='cod_lab[]']").map(function () {
          return $(this).val();
      }).get();

      let faltantes = verificarFaltantes(seleccionados);
      if(faltantes != "") alert(faltantes);*/
  }

  //================================================================================= Guardar seleccionados del modal e insertar en la tabla
  $(document).on('click', '#tblExamenLab .deleteFila', function () {
      $(this).closest('tr').remove();
  });

  function crearHiddenFields(nombres, ind) {
      return nombres.map(name =>
          `<input type="hidden" class="form-control" id="${name}${ind}" name="${name}[]" data-id="${ind}" value="">`
      ).join('\n');
  }

  const camposAdulto = [
      "descripcion_examen","des_adulto_fecha_tiempo_enfermedad",
      "des_adulto_respuesta_ocular","des_adulto_respuesta_verbal","des_adulto_respuesta_motora","des_adulto_total_escala_glasgow",
      "des_adulto_presion_arterial_x","des_adulto_presion_arterial_y","des_adulto_frecuencia_cardiaca","des_adulto_frecuencia_respiratorio","des_adulto_temperatura",
      "des_adulto_creatinina","des_adulto_diuresis","des_adulto_procalcitonina","des_adulto_pcr","des_adulto_fiO2","des_adulto_leucocitos","des_adulto_plaquetas","des_adulto_bilirrubina",
      "des_adulto_ima","des_adulto_icc","des_adulto_evp","des_adulto_ecv","des_adulto_demencia","des_adulto_dm","des_adulto_diabetes_lesion_organica","des_adulto_hemiplejia",
      "des_adulto_epoc","des_adulto_patologia_tejido_conectivo","des_adulto_enfermedad_ulcerosa","des_adulto_patologia_renal_moderada_o_grave",
      "des_adulto_neoplasias","des_adulto_neoplasias_especifique","des_adulto_leucemias","des_adulto_leucemias_especifique","des_adulto_linfomas_malignos",
      "des_adulto_metastasis_colida","des_adulto_sida","des_adulto_antecedentes_patologicos_otros_1","des_adulto_antecedentes_patologicos_otros_1_especifique",
      "des_adulto_antecedentes_patologicos_otros_2","des_adulto_antecedentes_patologicos_otros_2_especifique",
      "des_adulto_terapia_antimicrobiana",
      "des_adulto_frasco_1","des_adulto_frasco_1_opcion_1","des_adulto_frasco_1_opcion_2","des_adulto_frasco_2","des_adulto_frasco_2_opcion_1","des_adulto_frasco_2_opcion_2",
      "des_adulto_frasco_3","des_adulto_frasco_3_opcion_1","des_adulto_frasco_3_opcion_2",
      "des_adulto_hemocultivo_seguimiento_y_vigilancia","des_adulto_fecha_primer_resultado_hemocultivo",
      "flag_solicitud_examen_especializados_hemocultivo_adulto"
  ];

  const camposPed = [
      "des_pedriatria_fecha_tiempo_enfermedad","des_pedriatria_postermino","des_pedriatria_termino",
      "des_pedriatria_prematuro_leve","des_pedriatria_prematuro_moderado","des_pedriatria_prematuro_extremo",
      "des_pedriatria_apariencia","des_pedriatria_pulso","des_pedriatria_gesto","des_pedriatria_actividad_respiratoria",
      "des_pedriatria_tono_muscular","des_pedriatria_total_apgar",
      "des_pedriatria_respuesta_ocular","des_pedriatria_respuesta_verbal","des_pedriatria_respuesta_motora","des_pedriatria_total_escala_glasgow",
      "des_pedriatria_presion_arterial_x","des_pedriatria_presion_arterial_y","des_pedriatria_frecuencia_cardiaca","des_pedriatria_frecuencia_respiratorio","des_pedriatria_temperatura",
      "des_pedriatria_creatinina","des_pedriatria_diuresis","des_pedriatria_procalcitonina","des_pedriatria_pcr","des_pedriatria_fiO2","des_pedriatria_leucocitos",
      "des_pedriatria_plaquetas","des_pedriatria_bilirrubina","des_pedriatria_glucosa","des_pedriatria_alt","des_pedriatria_vcg","des_pedriatria_tp",
      "des_pedriatria_sodio","des_pedriatria_potasio","des_pedriatria_calcio","des_pedriatria_pco2","des_pedriatria_tpta","des_pedriatria_examen_laboratorio_otros",
      "des_pedriatria_examen_laboratorio_otros_especifique","des_pedriatria_terapia_antimicrobiana",
      "des_pedriatria_frasco_1","des_pedriatria_frasco_1_opcion_1","des_pedriatria_frasco_1_opcion_2","des_pedriatria_frasco_2","des_pedriatria_frasco_2_opcion_1",
      "des_pedriatria_frasco_2_opcion_2","des_pedriatria_frasco_3","des_pedriatria_frasco_3_opcion_1","des_pedriatria_frasco_3_opcion_2",
      "des_pedriatria_hemocultivo_seguimiento_y_vigilancia","des_pedriatria_fecha_primer_resultado_hemocultivo","flag_solicitud_examen_especializados_hemocultivo_pedriatria"
  ];

  const camposCito = [
      "des_antecedentes_patologicos_personales","des_antecedentes_patologicos_familiares","des_liquido_cefaloraquideo",
      "des_liquido_pleural","des_liquido_ascitico","des_liquido_sinovial","des_liquido_otro","des_liquido_otro_especifique",
      "des_num_frasco_muestra_opcion","flag_solicitud_citoquimicos_liquidos_biologicos"
  ];

  $("#guardarRUALab").on('click', function () {

    const implementacionSolicitudEspeciales = false; // Mientras no se implemente las solicitudes evitamos crear input de envio al request
    const id_nivel = $("#id_nivel").val();
    const checked = $("input[name='chk_rualab[]']:checked");
    if (checked.length === 0) {
      $("#modalDX").modal('hide');
      return;
    }

    const tblExamenLab_cantidad_tr = $('#tblExamenLab tbody tr').length;
    let examenExiste = false;

    checked.each(function () {
      const $this = $(this);
      const id_examen_lab = $this.data('id');
      const codigo = $this.data('codigo');
      const categoria = $this.data('categoria');
      const label = $this.data('label');

      examenExiste = false;

      if (tblExamenLab_cantidad_tr > 0) {
        const ordenlab_tr = $('#tblExamenLab tbody').children('tr');

        let fila_ordenlab, id_examen_lab_table;

        for (let i = 0; i < tblExamenLab_cantidad_tr; i++) {
          fila_ordenlab = ordenlab_tr[i];

          id_examen_lab_table = $(fila_ordenlab).find('input[name="id_examen_lab[]"]').val();

          if (parseInt(id_examen_lab) === parseInt(id_examen_lab_table)) {
            examenExiste = true;
            break;
          }

        }
      }

      if ($("#tblExamenLab tbody tr[data-id='" + id_examen_lab + "']").length > 0) {
          return true;
      }

      if (examenExiste) {
        return true;
      }

      const ind = obtenerIndiceTblExamenLab();

      let rowHtml = `
          <tr data-id="${id_examen_lab}">
            <td>
                <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required id="nombre_examen_lab${ind}" class="input-sm" style="width:100%; text-align:left" readonly value="${label}" />
                <input type="hidden" class="id_laboratorio" id="id_examen_lab${ind}" name="id_examen_lab[]" data-id="${ind}" value="${id_examen_lab}">
                <input type="hidden" class="cod_lab" id="cod_lab${ind}" name="cod_lab[]" data-id="${ind}" value="${codigo}">
                <input type="hidden" class="id_categoria" id="id_categoria_examen_lab${ind}" name="id_categoria_examen_lab[]" data-id="${ind}" value="${categoria}">
            </td>
      `;

      if (id_nivel && parseInt(id_nivel) < 3) {
        rowHtml += `
            <td>
              <input type="text" placeholder="Observacion" class="form-control examen_lab_observacion" id="examen_lab_observacion${ind}" name="examen_lab_observacion[]">
            </td>
        `;
      }
      rowHtml += `
            <td>
              <input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad" id="examen_lab_cantidad${ind}" name="examen_lab_cantidad[]" value="1">
            </td>
            <td>
              <select class="form-control select-diagnostico" id="miSelectLab${ind}" name="miSelectLab[]"></select>
            </td>
      `;

      if (implementacionSolicitudEspeciales) {
        rowHtml += `
            <td>
              <button type="button" class="btn btn-success btn-xs tipoMuestraMicrobiologia" id="tipoMuestraMicrobiologia${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Tipo de muestra</button>
              <input type="hidden" id="descripcion_tipo_muestra${ind}" name="descripcion_tipo_muestra[]" data-id="${ind}" value="">
              <input type="hidden" id="flag_descripcion_tipo_muestra${ind}" name="flag_descripcion_tipo_muestra[]" value="0">
              <button type="button" class="btn btn-warning btn-xs editarTipoMuestraMicrobiologia" id="editarTipoMuestraMicrobiologia${ind}" data-id="${ind}" style="display:none; margin-left:4px"><i class="fa fa-edit"></i>&nbsp;Tipo de muestra</button>
            </td>
            <td>
              <button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoAdulto" id="solicitudExamenEspecializadoHemocultivoAdulto${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>
              ${crearHiddenFields(camposAdulto, ind)}
              <button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoAdulto" id="editarSolicitudExamenEspecializadoHemocultivoAdulto${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>

              <button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoPedriatria" id="solicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>
              ${crearHiddenFields(camposPed, ind)}
              <button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoPedriatria" id="editarSolicitudExamenEspecializadoHemocultivoPedriatria' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>

              <button type="button" class="btn btn-success btn-xs solicitudCitoquimicosLiquidosBiologicos" id="solicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>
              ${crearHiddenFields(camposCito, ind)}
              <button type="button" class="btn btn-warning btn-xs editarSolicitudCitoquimicosLiquidosBiologicos" id="editarSolicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>
            </td>
        `;

      } else {
        rowHtml += `
            <td></td>
            <td></td>
        `;
      }
      rowHtml += `
            <td>
              <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px">
                <i class="fa fa-times"></i> Eliminar
              </button>
            </td>
          </tr>
      `;

      $("#tblExamenLab tbody").append(rowHtml);

      /*if (typeof asignarValoresDesdeModal === 'function') {
        asignarValoresDesdeModal({id_examen_lab: id_examen_lab, codigo_procedimiento: codigo, id_categoria: categoria, label: label}, ind);
      } else {
        $(`#id_examen_lab${ind}`).val(id_examen_lab);
        $(`#cod_lab${ind}`).val(codigo || '');
        $(`#id_categoria_examen_lab${ind}`).val(categoria || '');
        $(`#nombre_examen_lab${ind}`).val(label || '');
      }*/

      const select = document.getElementById('miSelectLab' + ind);
      if (Array.isArray(listIdDiagnosticoObjetos) && select) {
        listIdDiagnosticoObjetos.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.id;
          opt.textContent = item.nombre;
          select.appendChild(opt);
        });
      }
    });

    $("#modalDX").modal('hide');
    $("input[name='chk_rualab[]']").prop('checked', false);
  });

  document.querySelectorAll('input[name="hora_toma_muestra"]').forEach(radio => {
    radio.addEventListener('change', function() {

      let stringIdHoraTomaMuestraSeleccionada = String(this.value);
      let descripcionHoraTomaMuestra = "";
      switch (stringIdHoraTomaMuestraSeleccionada) {
        case "0": descripcionHoraTomaMuestra = "OTRO"; break;
        case "1": descripcionHoraTomaMuestra = "05:00 AM"; break;
        case "2": descripcionHoraTomaMuestra = "09:00 AM"; break;
        case "3": descripcionHoraTomaMuestra = "16:00 PM"; break;
        case "4": descripcionHoraTomaMuestra = "22:00 PM"; break;
      }

      document.getElementById("id_hora_toma_muestra").value = String(stringIdHoraTomaMuestraSeleccionada);
      document.getElementById("lab_orden_toma_muestra").value = descripcionHoraTomaMuestra;
    });
  });


//=================================================================================================
</script>
<style>
  .borde-saturacion-hipoxia-grave {
    border-color: red;
    border: 2px red solid;
  }

  .borde-saturacion-hipoxia-moderada {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-saturacion-hipoxia-leve {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-saturacion-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-imc-bajopeso {
    border-color: skyblue;
    border: 2px skyblue solid;
  }

  .borde-imc-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-imc-sobrepeso {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-imc-obesidad1 {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-imc-obesidad2 {
    border-color: orangered;
    border: 2px orangered solid;
  }

  .borde-imc-obesidad3 {
    border-color: red;
    border: 2px red solid;
  }

  .borde-temperatura-hipotermia {
    border-color: blue;
    border: 2px blue solid;
  }

  .borde-temperatura-normal {
    border-color: green;
    border: 2px green solid;
  }

  .borde-temperatura-fiebre {
    border-color: yellow;
    border: 2px yellow solid;
  }

  .borde-temperatura-fiebre-alta {
    border-color: orange;
    border: 2px orange solid;
  }

  .borde-temperatura-hipertermia {
    border-color: red;
    border: 2px red solid;
  }

  .borde-talla {
    border-color: green;
    border: 2px green solid;
  }

  .mensaje-temperatura {
    color: green;
    margin-left: 10px;
  }

  /*.modal-dialog {
    width: 1200px !important;
  }*/
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
  .swal2-icon.swal2-warning,
  .swal2-icon.swal2-info {
    font-size: 15px;
  }

  .swal2-popup,
  .swal2-header {
    padding: 1.25em !important;
  }

  /**************************** MARCA DE AGUA - HISTORIAL DE ATENCIONES ***********************/
  .marca_agua_container {
    position: relative;
  }
  .marca_agua_overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 999;
    overflow: hidden;
  }
  .marca_agua_overlay span {
    position: absolute;
    color: rgba(5, 98, 24, 0.12);
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
    transform: rotate(-35deg);
    user-select: none;
    letter-spacing: 1px;
  }
  @media print {
    .marca_agua_overlay span {
      display: none !important;
    }
    .marca_agua_overlay {
      z-index: 0 !important;
      bottom: 0 !important;
      height: auto !important;
      background-image: var(--marca-agua-print) !important;
      background-repeat: no-repeat !important;
      background-size: 100% 100% !important;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
    .marca_agua_container > :not(.marca_agua_overlay) {
      position: relative;
      z-index: 1;
    }
    .marca_agua_container .panel,
    .marca_agua_container .panel-heading,
    .marca_agua_container .panel-body,
    .marca_agua_container .modal-content,
    .marca_agua_container .modal-body,
    .marca_agua_container .well,
    .marca_agua_container .form-control,
    .marca_agua_container table,
    .marca_agua_container tr,
    .marca_agua_container td,
    .marca_agua_container th {
      background: transparent !important;
    }
    .container:has(.marca_agua_container) {
      width: 1200px !important;
      max-width: none !important;
      zoom: 0.6;
    }
    .marca_agua_container a[href]:after {
      content: none !important;
    }
    .marca_agua_container .table-responsive {
      overflow: visible !important;
    }
  }

  .swal-confidencialidad-hspcu-backdrop.swal2-backdrop-show {
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.4) !important;
  }

  .swal-confidencialidad-interconsulta-hspcu-backdrop.swal2-backdrop-show {
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.4) !important;
  }
</style>
<script>
  (function () {
    if (!window.matchMedia || window.marcaAguaImpresionRegistrada) return;
    window.marcaAguaImpresionRegistrada = true;
    var mq = window.matchMedia('print');
    var ctx = document.createElement('canvas').getContext('2d');
    ctx.font = '14px Arial, sans-serif';
    function svg_marca_agua(W, H, texto) {
      var txt = String(texto).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      var anchoTexto = ctx.measureText(texto).width + texto.length;
      var textos = '';
      for (var r = 0; r < Math.ceil(H / 100) + 2; r++) {
        for (var c = 0; c < Math.ceil(W / 220) + 2; c++) {
          var x = c * 220 - 40 + anchoTexto / 2;
          var y = r * 100 - 20 + 9;
          textos += '<text x="' + x + '" y="' + y + '" text-anchor="middle" dominant-baseline="middle" transform="rotate(-35 ' + x + ' ' + y + ')">' + txt + '</text>';
        }
      }
      var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '">' +
        '<g font-family="Arial, sans-serif" font-size="14" letter-spacing="1" fill="#e6e6e6">' + textos + '</g></svg>';
      return 'url("data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg) + '")';
    }
    function al_cambiar_impresion(e) {
      if (!e.matches) return;
      var overlays = document.querySelectorAll('.marca_agua_overlay');
      for (var i = 0; i < overlays.length; i++) {
        var span = overlays[i].querySelector('span');
        if (!span || !overlays[i].offsetWidth) continue;
        overlays[i].style.setProperty('--marca-agua-print', svg_marca_agua(overlays[i].offsetWidth, overlays[i].offsetHeight, span.textContent));
      }
    }
    if (mq.addEventListener) mq.addEventListener('change', al_cambiar_impresion);
    else if (mq.addListener) mq.addListener(al_cambiar_impresion);
  })();
</script>
@endsection
@section('content')

@if (session('mensaje_error'))
    <div style="background-color: #f8d7da;color: #a94442;border: 1px solid #ebccd1;border-radius: 4px;padding: 10px 15px;font-size: 14px;margin: 10px 0;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        {{ session('mensaje_error') }}
    </div>
@endif

<div class="loader"></div>
<div class="alert alert-info" hidden="true" id="infoRegla1"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
	<?php
            $disabled_atencion = "";
            if($flag==1){
              $disabled_atencion = "disabled='disabled'";
              echo '<div class="col-sm-12">
                  <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                    El paciente es civil o ya no esta asegurado y por consiguiente no tiene derecho de atención.
                  </div>
                </div>';
            }
          ?>
          <ul class="nav nav-tabs" id="tabs">
            <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">NUEVA ATENCION</a></li>
            <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES</a></li>
            <li><a href="#tab5default" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE LABORATORIO</a></li>
            <li><a href="#tab6default" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE IMAGENOLOGIA</a></li>
            <li><a href="#tab1007default" data-toggle="tab" style="font-size:12px">HISTORIAL DE INTERCONSULTA</a></li>
          </ul>
        </div>
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
                            <h3 class="panel-title">MÓDULO DE GESTIÓN DE ATENCIONES MEDICAS</h3>
                          </div>
                          <div class="panel-body">
                            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->
                            <form class="form-horizontal" method="post" action="{{ route('registrar_prestacion_hospitalizacion')}}" id="frmPrestacion" name="frmPrestacion" autocomplete="off" enctype="multipart/form-data">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $nivel?>">
                              <input type="hidden" name="turno" id="turno" value="<?php echo $turno?>">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                              <input type="hidden" name="id_cita" id="id_cita" value="<?php echo $id_cita?>">
                              <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="<?php echo $cita->id_establecimiento?>">
                              <input type="hidden" name="id_asegurado" id="id_asegurado" value="<?php echo $cita->id_asegurado?>">
                              <input type="hidden" name="id_servicio" id="id_servicio"value="<?php echo $cita->id_servicio?>">
                              <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio" value="<?php echo $cita->id_sub_consultorio?>">
                              <input type="hidden" name="id_medico" id="id_medico" value="<?php echo $cita->id_medico?>">
                              <input type="hidden" value="<?php //echo $cita->id_prioridad; ?>" name="id_prioridad_actual" id="id_prioridad_actual">
                              <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $id_tipo_atencione?>">
                              <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
                              <input type="hidden" id="frmPuerperaActive" name="frmPuerperaActive" value="false">
                              <input type="hidden" id="frmGestacionActive" name="frmGestacionActive" value="false">
                              <input type="hidden" id="regularizar_datos" name="regularizar_datos" value="{{$regularizar}}">
                              <input type="hidden" id="upss_cargo" name="upss_cargo" value="{{ $upss_cargo }}">
                              <input type="hidden" id="servicio_medico" name="servicio_medico" value="{{ $servicio_medico }}">
                              <input type="hidden" name="id_principal" id="id_principal" value="{{$id_principal}}">
                              <input type="hidden" name="id_hora_toma_muestra" id="id_hora_toma_muestra" />
                              <input type="hidden" id="id_rua_imagenes" name="id_rua_imagenes" value="">
                              <input type="hidden" id="fecha_hora_cu" name="fecha_hora_cu"  value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}">
                              <input type="hidden" id="flag_alta_servicio" name="flag_alta_servicio" value="0">
                              <input type="hidden" id="flag_exa_cu" name="flag_exa_cu" value="1">
                              <!-- Nueva Version-->
                              <div class="row m-0" style="display: flex;flex-wrap:wrap">
                                <fieldset class="col-lg-6 col-xs-12 pb-5">
                                  <div class="panel panel-info panel-bordered panel-sm h-100 m-0">
                                    <div class="panel-heading">
                                      <div class="panel-title text-left texto-12">
                                        <b>DATOS DEL PACIENTE</b>
                                      </div>
                                    </div>
                                    <input type="hidden" value="<?php echo $cita->tipo_doc_ident?>" name="tipodoc_beneficiario" id="tipodoc_beneficiario" style="text-align:left">
                                    <input type="hidden" value="<?php echo $cita->nro_doc_ident?>" name="nrodocafiliado" id="nrodocafiliado" readonly="" />
                                    <input type="hidden" value="<?php echo $cita->asegurado_paterno?>" name="paterno_beneficiario" id="paterno_beneficiario" readonly>
                                    <input type="hidden" value="<?php echo $cita->asegurado_materno?>" name="materno_beneficiario" id="materno_beneficiario" readonly>
                                    <input type="hidden" value="<?php echo $cita->asegurado_nombre?>" name="nombre_beneficiario" id="nombre_beneficiario" readonly>
                                    <input type="hidden"  value="<?php echo $cita->fecha_nac?>" name="fecnacafiliado" id="fecnacafiliado" readonly>
                                    <input type="hidden"  value="<?php echo $cita->sexo?>" name="nomsexo" id="nomsexo" readonly>
                                    <input type="hidden"  value="<?php echo $cita->parentesco?>"  name="tipo_beneficiario" id="tipo_beneficiario"  readonly>
                                    <input type="hidden" value="<?php echo $cita->nro_historia?>" name="nro_historia" id="nro_historia" readonly>
                                    <div class="pt-5">
                                      <strong class="panel-heading text-black texto-12" style="color: #333333 !important">DATOS DEL PACIENTE</strong>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-6">
                                          <span class="p-0 texto-12"><strong>TIPO Y N° DE DOC </strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-6 d-md-flex d-sm-none"><strong class="">:</strong></div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">
                                          <span class="texto-12"><?php echo $cita->tipo_doc_ident?> : <?php echo $cita->nro_doc_ident?></span>
                                        </div>
                                      </div>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-8">  <span class="p-0 texto-12"><strong> APELLIDOS Y NOMBRES </strong></span></div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none">   <strong class="">:</strong> </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">  <span class="texto-12">{{  $cita->asegurado_paterno.' '.$cita->asegurado_materno.' '.$cita->asegurado_nombre }}</span> </div>
                                      </div>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-8">  <span class="p-0 texto-12"><strong>FECHA DE NACIMIENTO</strong></span>  </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none">   <strong class="">:</strong> </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">  <span class="texto-12">{{ $cita->fecha_nac }}</span> </div>
                                      </div>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-6">  <span class="p-0 texto-12"><strong>EDAD / SEXO</strong></span>  </div>
                                        <div class="col-md-1 col-sm-2 col-xs-6 d-md-flex d-sm-none">   <strong class="">:</strong> </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">  <span class="texto-12">{{ $cita->edadpaciente }}</span> </div>
                                      </div>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-8">  <span class="p-0 texto-12"><strong>TIPO DE BENEFICIARIO</strong></span>  </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none">   <strong class="">:</strong> </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">  <span class="texto-12">{{ $cita->parentesco }}</span> </div>
                                      </div>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-8">  <span class="p-0 texto-12"><strong>N° DE HISTORIA CLÍNICA</strong></span>  </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none">   <strong class="">:</strong> </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">  <span class="texto-12">{{ $cita->nro_historia }} </span> </div>
                                      </div>
                                    </div>
                                    <div class="pt-2">
                                      <strong class="panel-heading text-black col-xs-12 texto-12" style="color: #333333 !important">DATOS DEL TITULAR </strong>
                                      <?php
                                        if($cita->parentesco=='TITULAR'){
                                          $titular_tipo_dni         =  $cita->tipo_doc_ident;
                                          $titular_dni              =  $cita->nro_doc_ident;
                                          $titular_nombre           = $cita->nombre_titular;
                                          $titular_paterno          =  $cita->paterno_titular;
                                          $titular_materno          = $cita->materno_titular;
                                          $titular_cip              = $cita->cip_titular;
                                          $titular_nombre_completo  = $cita->paterno_titular.' '.$cita->materno_titular.' '.$cita->nombre_titular;
                                          $titular_grado            =  $cita->grado_titular;
                                        }else{
                                          if($cita->situacion_paciente!='CIVIL' && $flag_no_encontrado==0){
                                            $titular_tipo_dni          = $dato_titular[0]->tipo_doc_ident;
                                            $titular_dni              =  $dato_titular[0]->nro_doc_ident;
                                            $titular_nombre           = $dato_titular[0]->nombre;
                                            $titular_paterno          = $dato_titular[0]->paterno;
                                            $titular_materno          = $dato_titular[0]->materno ;
                                            $titular_cip              = $dato_titular[0]->cip ;
                                            $titular_nombre_completo  = $dato_titular[0]->paterno.' '.$dato_titular[0]->materno.' '.$dato_titular[0]->nombre ;
                                            $titular_grado            =   $dato_titular[0]->grado;
                                          }else{
                                            $titular_tipo_dni          ='DNI';
                                            $titular_dni              =  $cita->nro_doc_ident;
                                            $titular_cip              = " - ";
                                            $titular_nombre_completo  = 'CIVIL';
                                            $titular_nombre           = 'CIVIL';
                                            $titular_paterno          = 'CIVIL';
                                            $titular_materno          = 'CIVIL';
                                            $titular_grado            = ' - ' ;
                                          }
                                        }
                                      ?>
                                      <input type="hidden" value="<?php echo $titular_tipo_dni ?>" name="tipodoc_titular" id="tipodoc_titular"  readonly/>
                                      <input type="hidden" value="{{$cita->nro_doc_ident}}" name="dni_beneficiario" id="dni_beneficiario" readonly/>
                                      <input type="hidden" value="<?php echo $titular_dni ?>" name="nrodoctitular" id="nrodoctitular"  readonly/>
                                      <input type="hidden" value="<?php echo $titular_nombre ?>" name="nombre_titular" id="nombre_titular"  readonly/>
                                      <input type="hidden" value="<?php echo $titular_paterno ?>" name="paterno_titular" id="paterno_titular"  readonly/>
                                      <input type="hidden" value="<?php echo $titular_materno ?>" name="materno_titular" id="materno_titular"  readonly/>
                                      <input type="hidden" value="<?php echo $titular_cip ?>" name="cip_titular" id="cip_titular"  readonly/>
                                      <input type="hidden" value="<?php echo $titular_grado ?>" name="grado" id="grado"  readonly/>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-6">
                                          <span class="p-0 texto-12"><strong>N° DE DOC</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-6 d-md-flex d-sm-none">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">
                                          <span class="texto-12">{{ $titular_dni }}</span>
                                        </div>
                                      </div>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-6">
                                          <span class="p-0 texto-12"><strong>N° DE CIP</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-6 d-md-flex d-sm-none">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">
                                          <span class="texto-12">{{ $titular_cip }}</span>
                                        </div>
                                      </div>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-8">
                                          <span class="p-0 texto-12"><strong>APELLIDOS Y NOMBRES</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">
                                          <span class="texto-12">
                                            {{ $titular_nombre_completo }}
                                          </span>
                                        </div>
                                      </div>
                                      <div class="row m-0">
                                        <div class="col-md-5 col-sm-4 col-xs-6">
                                          <span class="p-0 texto-12"><strong>GRADO</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-6 d-md-flex d-sm-none">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-6">
                                          <span class="texto-12">{{ $titular_grado }} </span>
                                        </div>
                                      </div>
                                    </div>
                                    <br>
                                  </div>
                                </fieldset>
                                <fieldset class="col-lg-6 col-xs-12 pb-5">
                                  <div class="panel panel-info panel-bordered panel-sm h-100 m-0">
                                    <div class="panel-heading">
                                      <div class="panel-title text-left texto-12 ">
                                        <b>DATOS DE LA ATENCIÓN</b>
                                      </div>
                                    </div>
                                    <br>
                                    <input type="hidden" value="<?php echo $titular_tipo_dni ?>" name="tipodoc_titular" id="tipodoc_titular"  readonly/>
                                    <div class="">
                                      <div class="row m-0" style="display:flex;align-items: self-end;">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                          <strong  class="text-black texto-12">COD. SITEDS SUSALUD:</strong>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                              <strong>:</strong>
                                          </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                          <span style="color:#fb000a!important;">{{ strtoupper($cita->cod_siteds_susalud) }}</span>
                                        </div>
                                      </div>
                                      <!-- TIPO DE ATENCIÓN -->
                                      <div class="row m-0">
                                          <div class="col-md-4 col-sm-4 col-xs-8">
                                              <span class="p-0 texto-12"><strong>TIPO DE ATENCIÓN</strong></span>
                                          </div>
                                          <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                              <strong>:</strong>
                                          </div>
                                          <div class="col-md-7 col-sm-6 col-xs-12">
                                              <span class="texto-12">HOSPITALIZACIÓN</span>
                                          </div>
                                      </div>

                                      <!-- FECHA Y HORA DE INGRESO -->
                                      <div class="row m-0">
                                          <div class="col-md-4 col-sm-4 col-xs-8">
                                              <span class="p-0 texto-12"><strong>FECHA Y HORA DE INGRESO</strong></span>
                                          </div>
                                          <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                              <strong>:</strong>
                                          </div>
                                          <div class="col-md-7 col-sm-6 col-xs-12">
                                              <span class="texto-12">{{ date('d/m/Y',strtotime($hospitalizacion->fecha_ingreso)) .' - '. date('H:m:s',strtotime($hospitalizacion->hora_ingreso)) }}</span>
                                          </div>
                                      </div>

                                      <!-- FECHA DE ATENCIÓN -->
                                      <div class="row m-0" style="    padding-top: 0.8rem;">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                            <span class="p-0 texto-12"><strong>FECHA DE ATENCIÓN</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                            <strong>:</strong>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                          <div class="w-100 input-group" style="display: flex; align-items: center;">
                                            <input type="text" style="border: 1px solid; border-radius: 5px;padding-left: 0.8rem;" class="col-xs-11 input-transparent" value="{{ date('d-m-Y', strtotime($fechaServidor)) }}" name="fecha_atencion" id="fecha_atencion" autocomplete="off" required>
                                          </div>
                                        </div>
                                      </div>

                                      <!-- HORA DE ATENCIÓN -->
                                      <div class="row m-0" style=" padding-top: 0.8rem;padding-bottom: 0.8rem;">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                          <span class="p-0 texto-12"><strong>HORA DE ATENCIÓN</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                          <div class="w-100 input-group" style="display: flex; align-items: center;">
                                            <input type="time" name="hora_atencion" id="hora_atencion" value="{{ date('H:i', strtotime($fechaServidor)) }}" style="border: 1px solid; border-radius: 5px;padding-left: 0.8rem;" class="col-xs-11 input-transparent" required>
                                          </div>
                                        </div>
                                      </div>

                                      <!-- UPS A CARGO -->
                                      <input  type="hidden"   value="{{$servicio_ubicacion->nombre}}" name="consultorio" id="consultorio"  >
                                      <div class="row m-0">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                          <span class=" p-0 texto-12"><strong>UPS A CARGO</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                          @if ($regularizar == "0")
                                            <span  class="texto-12" > {{$upss_encargado->codigo}} - {{$upss_encargado->descripcion_upss}} </span>
                                            <input type="hidden" name="codigo_upss" id="codigo_upss" value="{{$upss_encargado->codigo}}">
                                            <input type="hidden" name="id_upss_encargado" value="{{$hospitalizacion->id_upss_encargado}}">
                                            <input type="hidden" name="id_servicio_encargado" value="{{$hospitalizacion->id_servicio_encargado}}">
                                          @else
                                            <select name="id_upss_encargado_regularizacion" id="id_upss_encargado_regularizacion" style="width:100%">
                                              <option value="0">--SELECCIONAR--</option>
                                              @foreach($upsses as $r)
                                                <option value="{{$r->id_upss}}" data-cod_upss="{{$r->codigo_upss}}">{{$r->codigo_upss}} - {{$r->descripcion_upss}}</option>
                                              @endforeach
                                            </select>
                                            <input type="hidden" id="codigo_upss_regularizacion" name="codigo_upss_regularizacion">
                                          @endif
                                        </div>
                                      </div>
                                      @if ($regularizar != "0")
                                        <div class="row m-0">
                                          <div class="col-md-4 col-sm-4 col-xs-8">
                                            <span class=" p-0 texto-12"><strong>DEPARTAMENTO A CARGO</strong></span>
                                          </div>
                                          <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                            <strong>:</strong>
                                          </div>
                                          <div class="col-md-7 col-sm-6 col-xs-12">
                                            <select name="id_servicio_encargado_regularizacion" id="id_servicio_encargado_regularizacion" style="width:100%">
                                            </select>
                                          </div>
                                        </div>
                                      @endif

                                      <!-- UBICACIÓN DEL PACIENTE / CAMA -->
                                      <div class="row m-0">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                          <span class=" p-0 texto-12"><strong>UBICACIÓN DEL PACIENTE / CAMA</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12" style="display: flex;align-items: flex-start;">
                                          <span class="texto-12">{{$servicio_ubicacion->nombre.' / CAMA: '.$cama->cama}} </span>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="pt-2">
                                      <strong class="panel-heading text-black col-xs-12 texto-12" style="color: #333333 !important">RESPONSABLE DE LA ATENCIÓN</strong>
                                      <div class="row m-0">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                          <span class=" p-0 texto-12"><strong>TIPO Y N° DE DOC</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12" >
                                          <span class="texto-12"> DNI : {{ $cita->nro_dni_medico}} </span>
                                        </div>
                                      </div>

                                      <div class="row m-0">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                          <span class=" p-0 texto-12"><strong>APELLIDOS Y NOMBRES </strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12" style="font-size: 12px !important">
                                          <span>{{  $cita->medico_paterno.' '.$cita->medico_materno .' '.$cita->medico_nombre }}</span>
                                        </div>
                                      </div>

                                      <div class="row m-0">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                          <span class=" p-0 texto-12"><strong>PROFESIÓN / ESPECIALIDAD</strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                          <strong>:</strong>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                          <span class="texto-12">{{ $cita->profesion_medico.' / '.$cita->especializacion_medico }}</span>
                                        </div>
                                      </div>

                                      <div class="row m-0">
                                        <div class="col-md-4 col-sm-4 col-xs-8">
                                          <span class=" p-0 texto-12"><strong>N° DE CMP / RNE </strong></span>
                                        </div>
                                        <div class="col-md-1 col-sm-2 col-xs-4 d-md-flex d-sm-none p-0" style="justify-content:center">
                                          <strong>:</strong>
                                      </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <span class="texto-12">{{ $cita->colegiatura_medico.' / '. ($cita->rne_medico ? $cita->rne_medico : '-') }}</span>
                                        </div>
                                      </div>
                                      <br>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              <!-- DIAGNÓSTICOS -->
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>DIAGNÓSTICOS</b>
                                  </div>
                                </div>
                                <div class="panel-body">

                                  <div class="panel panel-default">
                                    <div class="table-responsive">
                                      <table class="table table-striped table-condensed">
                                        <tr>
                                          <th></th>
                                          <th>Diagnóstico(s)</th>
                                          <th>Tipo</th>
                                          <th>Prioridad</th>
                                          <th>Clasificación</th>
                                        </tr>
                                        <?php
                                          $tipo_diagnostico = "DEFINITIVO";
                                          $prioridad = "PRINCIPAL";
                                          $clasificacion = "ADMISION";
                                        ?>
                                        @if($dx_admision->codigo_cie_ingreso_1 != null)
                                        <tr>
                                          <td><i class="fa fa-arrow-right" aria-hidden="true"></i></td>
                                          <td>{{$dx_admision->codigo_cie_ingreso_1}} - {{$dx_admision->nombre_cie_ingreso_1}}</td>
                                          <td>{{$tipo_diagnostico}}</td>
                                          <td>{{$prioridad}}</td>
                                          <td>{{$clasificacion}}</td>
                                        </tr>
                                        @endif
                                        @if($dx_admision->codigo_cie_ingreso_2 != null)
                                        <tr>
                                          <td><i class="fa fa-arrow-right" aria-hidden="true"></i></td>
                                          <td>{{$dx_admision->codigo_cie_ingreso_2}} - {{$dx_admision->nombre_cie_ingreso_2}}</td>
                                          <td>{{$tipo_diagnostico}}</td>
                                          <td>{{$prioridad}}</td>
                                          <td>{{$clasificacion}}</td>
                                        </tr>
                                        @endif
                                        @if($dx_admision->codigo_cie_ingreso_3 != null)
                                        <tr>
                                          <td><i class="fa fa-arrow-right" aria-hidden="true"></i></td>
                                          <td>{{$dx_admision->codigo_cie_ingreso_3}} - {{$dx_admision->nombre_cie_ingreso_3}}</td>
                                          <td>{{$tipo_diagnostico}}</td>
                                          <td>{{$prioridad}}</td>
                                          <td>{{$clasificacion}}</td>
                                        </tr>
                                        @endif
                                        @if ($dx_traslado != null)
                                          @foreach($dx_traslado as $row)
                                          <?php
                                          $prioridad = ""; $clasificacion = "";
                                          switch ($row->id_prioridad) {
                                            case 1: $prioridad = "PRINCIPAL"; break;
                                            case 2: $prioridad = "SECUNDARIO"; break;
                                          }
                                          switch ($row->id_clasificacion_diagnosticos) {
                                            case 1: $clasificacion = "ATENCION"; break;
                                            case 8: $clasificacion = "EVOLUCION"; break;
                                            case 9: $clasificacion = "TRASLADO"; break;
                                          }
                                          ?>
                                          <tr>
                                            <td><i class="fa fa-random" aria-hidden="true"></i></td>
                                            <td>{{$row->codigo}} - {{$row->nombre}}</td>
                                            <td>{{$row->tipo_diagnostico}}</td>
                                            <td>{{$prioridad}}</td>
                                            <td>{{$clasificacion}}</td>
                                          </tr>
                                          @endforeach
                                        @endif
                                      </table>
                                    </div>
                                  </div>
                                  <table>
                                    <tr>
                                      <td>
                                        <button tabindex="58" type="button" id="addDiagnostico" class="btn btn-primary"
                                          data-toggle="modal" data-target="#addClassModal">Agregar
                                          Diagnostico</button>
                                      </td>
                                    </tr>
                                  </table>
                                  <br />
                                  <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                                    style="margin-top:5px;">
                                    <thead>
                                      <tr>
                                        <th width="90%">Código - Descripción</th>
                                        <th style="padding-left:20px;">Tipo</th>
                                        <th style="padding-left:20px;">Prioridad</th>
                                      </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
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
                                  <table class="display" id="tblProcedimiento" cellspacing="0" width="100%"
                                    style="margin-top:5px;">
                                    <thead>
                                      <tr>
                                          <th width="45%">Procedimiento</th>
                                          <th width="5%">Cantidad</th>
                                          <th width="45%">Diagnósticos</th>
                                        <th width="5%"></th>
                                      </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>PLAN DE TRABAJO</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <!-- LABORATORIO -->
                                    <div class="panel panel-info panel-bordered panel-sm panel-body" >
                                      <div class="form-group" style="<?php if(!$is_profesion_medico){ echo "display:none;";}?>">
                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label>EXAMEN DE LABORATORIO</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button tabindex="90" type="button" id="addExamenLab" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>
                                        </div>
                                      </div>
                                      <fieldset id="fsExamenLab" style="display:none">
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>EXAMEN LABORATORIO</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <div class="col-lg-10">
                                              <div class="form-group">
                                                <div class="col-md-6">
                                                  <button tabindex="91" type="button" id="addExamenLab2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                                </div>
                                                @if (Auth::user()->id_establecimiento == 76)
                                                  <div class="col-md-6" style="text-align: right;">
                                                    <button type="button" id="RUALab2" class="btn btn-primary" data-toggle="modal" data-target="#modalDX">RUA LABORATORIO</button>
                                                  </div>
                                                @endif
                                              </div>
                                              <br />
                                              <table class="display" id="tblExamenLab" cellspacing="0" width="100%" style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    @if (intval($nivel) == 3)
                                                      <th width="65%">Exámen</th>
                                                      <th width="10%">Cantidad</th>
                                                      <th width="20%">Diagnóstico</th>
                                                      <th></th>
                                                      <th></th>
                                                      <th width="5%">Eliminar</th>
                                                    @elseif (!empty($nivel) && (int)$nivel < 3)
                                                      <th width="45%">Exámen</th>
                                                      <th width="20%">Observación</th>
                                                      <th width="10%">Cantidad</th>
                                                      <th width="20%">Diagnóstico</th>
                                                      <th></th>
                                                      <th></th>
                                                      <th width="5%">Eliminar</th>
                                                    @endif
                                                  </tr>
                                                </thead>
                                                <tfoot>
                                                </tfoot>
                                                <tbody>
                                                </tbody>
                                              </table>
                                              @if (intval($nivel) === 3)
                                                <br/>
                                                <table style="width: 100%;">
                                                  <tr>
                                                    <th width="5%">Observación</th>
                                                    <th width="95%"></th>
                                                  </tr>
                                                  <tr>
                                                    <td><input type="text" class="form-control" id="lab_orden_toma_muestra" maxlength="8" readonly /></td>
                                                    <td><input type="text" class="form-control" id="lab_orden_observacion" name="lab_orden_observacion" maxlength="255" /></td>
                                                  </tr>
                                                </table>
                                              @endif
                                            </div> <!-- .col-md-10 -->
                                            <div class="col-lg-2">
                                              <div class="panel panel-default">
                                                <div class="panel-body">
                                                  <label for="">Hora de toma de muestra:</label>
                                                  <div class="radio">
                                                    <label>
                                                      <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__1" value="1">05:00 AM
                                                    </label>
                                                  </div>
                                                  <div class="radio">
                                                    <label>
                                                      <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__2" value="2">09:00 AM
                                                    </label>
                                                  </div>
                                                  <div class="radio">
                                                    <label>
                                                      <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__3" value="3">16:00 PM
                                                    </label>
                                                  </div>
                                                  <div class="radio">
                                                    <label>
                                                      <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__4" value="4">22:00 PM
                                                    </label>
                                                  </div>
                                                  <div class="radio">
                                                    <label>
                                                      <input type="radio" name="hora_toma_muestra" id="hora_toma_muestra__0" value="0">OTRO
                                                    </label>
                                                  </div>
                                                </div>
                                              </div>
                                            </div> <!-- .col-md-2 -->
                                          </div>
                                        </div>
                                      </fieldset>
                                    </div>

                                    <!-- IMAGENOLOGÍA -->
                                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                                      <div class="form-group">
                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label>EXAMEN IMAGENOLOGIA</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button tabindex="100" type="button" id="addExamenRad" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>
                                        </div>
                                      </div>
                                      <fieldset id="fsExamenRad" style="display:none">
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>EXAMEN IMAGENOLOGIA</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <table>
                                              <tr>
                                                <td>
                                                  <button tabindex="101" type="button" id="addExamenRad2" class="btn btn-primary"
                                                    data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                                </td>
                                              </tr>
                                            </table>
                                            <br />
                                            <div style="overflow-x:auto;">
                                              <table class="display" id="tblExamenRad" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    <th width="35%">Exámen</th>
                                                    <th width="3%">Comparativo</th>
                                                    <th width="3%">Lado</th>
                                                    <th width="20%">Observación</th>
                                                    <th width="5%">Cant</th>
                                                    <th width="20%">Diagnóstico</th>
                                                    <th width="14%">Radiología Portátil</th>
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
                                      </fieldset>
                                    </div>

                                    <!-- RECETA -->
                                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                                      <div class="form-group">
                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label>PRESCRIBIR RECETA</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button tabindex="70" type="button" id="addRecetaVale" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                        </div>
                                      </div>
                                      <fieldset id="fsRecetaVale" style="display:none">
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
                                                  @if($nivel<3)
                                                    <?php foreach($farmacias as $row):?>
                                                    <option value="<?php echo $row->id?>"
                                                      <?php if($row->id==Auth::user()->id_farmacia) echo "selected='selected'"?>>
                                                      <?php echo $row->nombre?></option>
                                                    <?php endforeach;?>
                                                  @else
                                                    <option value="128">Farmacia Dosis Unitaria PIP I del Hospital Nacional PNP Luis N. Saenz</option>
                                                    <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                                    <option value="10">Farmacia Oncologica del Hospital Nacional PNP Luis N. Saenz</option>
                                                    <option value="132">Farmacia de Preparados Farmacéutico No Estériles del Hospital Nacional PNP Luis N. Saenz</option>
                                                  @endif
                                                </select>
                                              </div>
                                            </div>
                                            <br/>
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
                                                <?php //foreach($producto as $row):?>
                                                  <!-- <option value="<?php //echo $row->id?>"><?php //echo $row->nombre?></option> -->
                                                <?php //endforeach;?>
                                              </select>
                                            </div>
                                            <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
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
                                          </div> <!-- .panel-body -->
                                        </div>
                                      </fieldset>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>

                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>UBICACIÓN/ ESTADO PACIENTE</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <input type="hidden" id="id_servicio_actual_paciente" name="id_servicio_actual_paciente" value="{{$id_cuarto_actual}}">
                                  <input type="hidden" id="id_cama_actual_paciente" name="id_cama_actual_paciente" value="{{$hospitalizacion->id_cama_estado}}">
                                  <div style="display:flex; flex-wrap:wrap; align-items:flex-end;">
                                    <!-- SERVICIO ACTUAL -->
                                    <div class="form-group" style="margin:0 20px 10px 0;">
                                      <label style="white-space:nowrap; display:block;">SERVICIO ACTUAL</label>
                                      <select id="id_area_actual" name="id_area_actual" class="form-control" disabled style="width:390px;">
                                        <option value="0">-Seleccione-</option>
                                        @foreach($cuartos as $e)
                                          <option value="{{$e->id_cuarto}}"
                                              @if($e->id_cuarto == $id_cuarto_actual) selected @endif>
                                              {{$e->servicio_sala}}
                                          </option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <!-- CAMA ACTUAL -->
                                    <div class="form-group" style="margin:0 20px 10px 0;">
                                      <label style="white-space:nowrap; display:block;">CAMA ACTUAL</label>
                                      <select id="id_cama_actual" name="id_cama_actual" class="form-control" disabled style="width:190px;">
                                        <option value="0">-Seleccione-</option>
                                        @foreach($camas as $e)
                                          <option value="{{$e->id}}"
                                            @if($e->id == $hospitalizacion->id_cama_estado) selected @endif>
                                            {{$e->nombre}}
                                          </option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <!-- CAMBIAR CAMA -->
                                    <div class="form-group" style="margin:0 20px 10px 0;">
                                      <label style="white-space:nowrap; display:block;">CAMBIAR CAMA</label>
                                      <select id="select_cambiar_cama" name="select_cambiar_cama" class="form-control" style="width:390px;">
                                        <option value="0">NO</option>
                                        <option value="1">ROTACIÓN</option>
                                      </select>
                                    </div>
                                    <!-- FECHA -->
                                    <div class="form-group campo-rotacion" style="margin:0 20px 10px 0; display:none;">
                                      <label style="white-space:nowrap; display:block;">FECHA ROTACIÓN</label>
                                      <input type="text" class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($fechaServidor)); ?>" name="fecha_inicio" id="fecha_inicio" data-date-end-date="0d" autocomplete="off" required style="width:200px;">
                                    </div>
                                    <!-- HORA -->
                                    <div class="form-group campo-rotacion" style="margin:0 20px 10px 0; display:none;">
                                      <label style="white-space:nowrap; display:block;">HORA ROTACIÓN</label>
                                      <input type="time" id="appt" name="appt" value="<?php echo $hora; ?>" class="form-control" required style="width:200px;">
                                    </div>
                                    <!-- ÁREA DESTINO -->
                                    <div class="form-group campo-rotacion" style="margin:0 20px 10px 0; display:none;">
                                      <label style="white-space:nowrap; display:block;">ÁREA DESTINO</label>
                                      <select id="id_area" name="id_area" class="form-control" style="width:390px;">
                                        <option value="0">-Seleccione-</option>
                                        @foreach($cuartos as $e)
                                          <option value="{{$e->id_cuarto}}"
                                            @if($e->id_cuarto == $id_cuarto_actual) selected @endif>
                                            {{$e->servicio_sala}}
                                          </option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <!-- CAMA DESTINO -->
                                    <div class="form-group campo-rotacion" style="margin:0 20px 10px 0; display:none;">
                                      <label style="white-space:nowrap; display:block;">CAMA DESTINO</label>
                                      <select id="id_cama" name="id_cama" class="form-control" style="width:390px;">
                                        <option value="0">-Seleccione-</option>
                                        @foreach($camas as $e)
                                          @if($e->id != $hospitalizacion->id_cama_estado)
                                            <option value="{{$e->id}}">
                                              {{$e->nombre}}
                                            </option>
                                          @endif
                                        @endforeach
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <input tabindex="140" <?php echo $disabled_atencion?> class="btn btn-primary pull-right" value="GUARDAR" name="crea" type="button" form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp; </form>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            <div class="tab-pane fade marca_agua_container" id="tab4default" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_hospitalizacion_cu"></div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE ATENCIONES MEDICAS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divHistorial'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="tab-pane fade" id="tab5default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divLaboratorio'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade" id="tab6default">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divRadiologico'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade marca_agua_container" id="tab1007default" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_interconsulta"></div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE INTERCONSULTA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divInterconsultaH'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="openOverlayOpc" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">

        <div class="modal-body">

          <div id="diveditpregOpc"></div>

        </div>

      </div>

    </div>
  </div>


  </div>


  <div class="modal fade" id="showDetalleExamenPendienteRadiologia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-examen" style="background-color: #ffffff">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #494949">&times;</span></button>
                <h4 class="modal-title" style="background-color: #ffffff"><small style="color: #494949; font-weight: 700">Examenes de Imagenes</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="contenido-table-pendiente-radiologia">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="showDetalleExamenPendiente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-examen" style="background-color: #ffffff">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #494949">&times;</span></button>
                <h4 class="modal-title" style="background-color: #ffffff"><small style="color: #494949; font-weight: 700">Pendientes Laboratorio</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="contenido-table-pendiente">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="showDetalleExamenResult" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-examen" style="background-color: #ffffff">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #494949">&times;</span></button>
                <h4 class="modal-title" style="background-color: #ffffff"><small style="color: #494949">Resultados Laboratorio</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="contenido-table-result">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
  </div>

  {{-- REGISTRO TIPO DE MUESTRA DE CATEGORIA DE MICROBIOLOGÍA PARA LABORATORIO --}}
<div class="modal fade" id="modalTipoMuestrabMicrobiologia" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">Escribe el tipo de muestra para los examenes de microbiología<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="solicitud_data_id" id="solicitud_data_id">
                <input type="text" name="solicitud_descripcion_tipo_muestra" id="solicitud_descripcion_tipo_muestra" class="form-control">
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarRegistroTipoMuestraMicrobiologia" class="btn btn-success">Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- EDITAR TIPO DE MUESTRA DE CATEGORIA DE MICROBIOLOGÍA PARA LABORATORIO --}}
<div class="modal fade" id="modalEditTipoMuestrabMicrobiologia" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado-edit">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">Edite el tipo de muestra para los examenes de microbiología<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="solicitud_edit_data_id" id="solicitud_edit_data_id">
                <input type="text" name="solicitud_edit_descripcion_tipo_muestra" id="solicitud_edit_descripcion_tipo_muestra" class="form-control">
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarEditTipoMuestraMicrobiologia" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

  {{-- REGISTRO TIPO DE MUESTRA DE CATEGORIA DE MICROBIOLOGÍA PARA LABORATORIO --}}
<div class="modal fade" id="modalTipoMuestrabMicrobiologia" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">Escribe el tipo de muestra para los examenes de microbiología<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="solicitud_data_id" id="solicitud_data_id">
                <input type="text" name="solicitud_descripcion_tipo_muestra" id="solicitud_descripcion_tipo_muestra" class="form-control">
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarRegistroTipoMuestraMicrobiologia" class="btn btn-success">Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- EDITAR TIPO DE MUESTRA DE CATEGORIA DE MICROBIOLOGÍA PARA LABORATORIO --}}
<div class="modal fade" id="modalEditTipoMuestrabMicrobiologia" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado-edit">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem">Edite el tipo de muestra para los examenes de microbiología<span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="solicitud_edit_data_id" id="solicitud_edit_data_id">
                <input type="text" name="solicitud_edit_descripcion_tipo_muestra" id="solicitud_edit_descripcion_tipo_muestra" class="form-control">
            </div>
            <div class="modal-footer">
                <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarEditTipoMuestraMicrobiologia" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal RUA LABORATORIO -->
<div class="modal fade" id="modalDX" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">RUA LABORATORIO</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div id="contenedorDX" class="contenedor-checks">Cargando...</div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="guardarRUALab">Guardar</button>
      </div>

    </div>
  </div>
</div>



{{-- Modal de Hemocultivo --}}
@include('frontend.laboratorios.modal_solicitud_hemocultivo')

{{-- MODAL SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS --}}
@include('frontend.laboratorios.modal_solicitud_citoquimicos_liquidos_biologicos')

{{-- MODAL IMAGENOLOGÍA --}}
<div class="modal fade" id="detalleModal" tabindex="-1">
  <div class="modal-dialog" style="width:95%; max-width:1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center" style="font-size:20px;"><strong>DATOS CLÍNICOS RELACIONADOS A LA SOLICITUD DEL EXAMEN DE IMAGENOLOGÍA</strong></h4>
      </div>

      <div class="modal-body" style="background:#f5f5f5; font-size:12px;">
        <div class="panel" style="border:1px solid #9fbf9f;">
          <div class="panel-heading text-center" style="background:#cfe3cf;">
            <strong>ANAMNESIS</strong>
          </div>
          <div class="panel-body">
            <div class="row" style="margin-top:10px;">
              <div class="col-md-12">
                <label><b>ANAMNESIS</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_anamnesis" name="rad_anamnesis"
                  class="form-control" rows="3"
                  placeholder="Ingrese anamnesis..."></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="panel" style="border:1px solid #9fbf9f; margin-top:10px;">
          <div class="panel-heading text-center" style="background:#cfe3cf;">
            <strong>EXAMENES AUXILIARES</strong>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <label><b>EXAMEN FÍSICO RELACIONADO A LA SOLICITUD DEL EXAMEN</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_examen_fisico" name="rad_examen_fisico"
                  class="form-control" rows="4"
                  placeholder="Ingrese el examen físico..."></textarea>
              </div>
              <div class="col-md-6">
                <label><b>EXAMENES AUXILIARES</b>&nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                <textarea id="rad_examen_auxiliar" name="rad_examen_auxiliar"
                  class="form-control" rows="4"
                  placeholder="Ingrese el examen auxiliar..."></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-times"></i> <strong>CERRAR</strong>
        </button>
        <button type="button" class="btn btn-success" id="btnConfirmarImagenologia">
          <i class="fa fa-save"></i> <strong>CONFIRMAR</strong>
        </button>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection