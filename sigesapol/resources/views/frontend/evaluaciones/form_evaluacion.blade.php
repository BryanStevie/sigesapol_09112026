@extends('frontend.layouts.master')
@push('style')
@endpush
@section('renderjs')

<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('funciones/function.js') }}"></script>

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
  .modal-dialog {
    width: 90%;
  }
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

/*************Styles de sweetAlert2*************/

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

.encabezado {
  color: white !important;
}
.thead-resultados{
  background-color:#0b5a00 !important;
}
.thead-resultados-detalle{
  background-color:#54ac48 !important;
}
.tbody-resultados-detalle{
  background-color:#f1f1f1 !important;
}
.icon-visible-result {
    color: rgb(95, 129, 95);
}
.icon-visible-result:hover {
    color: green;
}
.icon-hidden-result {
    color: red;
}
.toggleExamenesResult {
    font-size: 2rem;
}
.hide-element-result {
    display: none;
}
.modal-header-examen {
  padding: 0.5rem;
}
.swal2-actions {
    margin: 0 !important;
    padding: 1rem;
    justify-content: space-evenly;
  }

  .swal2-actions button {
    margin: 0 !important;
  }

  .swal2-icon.swal2-warning {
    font-size: 15px;
  }
  .custom-swal-popup .swal2-header {
    background: none !important;
  }

  .table-borderless,
  .table-borderless th,
  .table-borderless td {
      border: none !important;
      padding: 2px 4px !important;
      vertical-align: top;
      font-size: 12px;
  }

</style>

<script>

const nivel = parseInt({{ json_encode($nivel) }});
const DIA_SEMANA_DOMINGO = 0;
const DIA_SEMANA_SABADO = 6;
const SERVICIO_NEUMOLOGIA = 63;

var fechaHoraCompleto = new Date('{{$datos_evaluacion->created_at}}');
var procedimientosSeleccionados =[];
//var listIdDiagnostico ={!! json_encode($arraydeprocedimientos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
//var listIdDiagnosticoObjetos ={!! json_encode($arraydeprocedimientos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
  var listIdDiagnostico =[];
  var listIdDiagnosticoObjetos =[];
  var listIdDiagnosticoEgreso = [];
  var listIdDiagnosticoEgresoObjetos = [];
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
  $(document).ready(function() {

    $('#datepicker2').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            compruebaIMCInicio();
    $('#fecha_atencion').datepicker();
    
    /*$(".fc-event-container").on("click", function (e) {
      alert("cssc");
    });*/
    
    $('input[name="parent"]').on("change", function (e) {
      getServicio();
    });
    
    $('#id_servicio,#id_establecimiento').on("change", function (e) {
      $('#calendar').fullCalendar('destroy');
      fullCalendar();
      modalTurnos();
      modalHistorial();
      modalHistorial1();
      modalHistorial2();
      modalHistorial3();
    });
    

    $('#btnPrestacion').on("click", function (e) {
      guardarPrestacion();
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
    ////////////////////////////////////////////////////////////////////////////// NUEVA MODIFICACION EXAMEN DE IMÁGENES 29-05-26
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
    ////////////////////////////////////////////////////////////////////////////// FIN MODIFICACION EXAMEN DE IMÁGENES 29-05-26
    
     $('#addProducto').on('click', function () {
     	AddProducto();
     });

     $('#addProductoEgreso').on('click', function () {
      AddProductoEgreso();
     });

    $('#addDerivarServicio').on('click', function () {
      var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
      $("#divServicio").hide();
      if(addDerivarServicio == "false"){
        $("#divServicio").show();
      }
    });


    $("#id_cama").select2({
        width: 'resolve'
    });

    $('#addRecetaVale').on('click', function () {
      var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
      $("#tiene_receta").val("");
      $("#fsRecetaVale").hide();
      if(addRecetaVale == "false"){
        //adicionado
        getProducto();
        getProductoEgreso();
        $("#tiene_receta").val("true");
        $("#fsRecetaVale").show();
        // Bloquea registrar alta
        $('#swRegistroAlta').prop('disabled', true).css({'opacity':'0.5','pointer-events':'none'});
      }
      else{
        $('#presiono_receta').val("");
        // Libera registrar alta
        $('#swRegistroAlta').prop('disabled', false).css({'opacity':'1','pointer-events':'auto'});
      }
    });
    if ($("#tiene_receta").val() == "true") $('#addRecetaVale').click(); 
    $('#addExamenLab').on('click', function () {
      var addExamenLab = $('#addExamenLab').attr("aria-pressed");
      $("#fsExamenLab").hide();
      if(addExamenLab == "false"){
        $("#fsExamenLab").show();
      }
      $("#laboratorio_activo").val(addExamenLab == "true" ? "false" : "true");
    });
    if ($("#laboratorio_activo").val() == "true") $('#addExamenLab').click(); 

    $('#addExamenRad').on('click', function () {
  
      var addExamenRad = $('#addExamenRad').attr("aria-pressed");
      $("#fsExamenRad").hide();
      if(addExamenRad == "false"){
        $("#fsExamenRad").show();
      }
      $("#imagenologia_activo").val(addExamenRad == "true" ? "false" : "true");
    });
    if ($("#imagenologia_activo").val() == "true") $('#addExamenRad').click(); 

    $('#addSignosVitales').on('click', function () {
      var addSignosVitales = $('#addSignosVitales').attr("aria-pressed");
      $("#signos_vitales_evaluacion_emergencia").val("");
      $("#fsSignosVitales").hide();
      if(addSignosVitales == "false"){
        $("#signos_vitales_evaluacion_emergencia").val("true");
        $("#fsSignosVitales").show();
      }
    });
    if ($("#signos_vitales_evaluacion_emergencia").val() == "true") $('#addSignosVitales').click();

    $('#addInterconsulta').on('click', function () {
  
      var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
      $("#fsInterconsulta").hide();
      if(addInterconsulta == "false"){
        $("#fsInterconsulta").show();
      }
      $("#interconsulta_activo").val(addInterconsulta == "true" ? "false" : "true");
    });
    if ($("#interconsulta_activo").val() == "true") $('#addInterconsulta').click(); 

    $('#addInterconsulta2').on('click',function(){
      AddInterconsulta2();
    });
    $('#addReferencia').on('click', function () {
      var addReferencia = $('#addReferencia').attr("aria-pressed");
      $("#fsReferencia").hide();
      if(addReferencia == "false"){
        $("#fsReferencia").show();
      }
    });

    $('#swRegistroAlta').on('click', function () {
      var swRegistroAlta = $('#swRegistroAlta').attr("aria-pressed");
      $("#fsRegistroAlta").hide();
      $("#flag_registro_alta").val("0");
      if(swRegistroAlta == "false"){
        $("#fsRegistroAlta").show();
        $("#flag_registro_alta").val("1");
          // bloquea prescribir receta
        $('#addRecetaVale').prop('disabled', true).css({'opacity':'0.5','pointer-events':'none'});
      }else{
        // libera prescribir receta
        $('#addRecetaVale').prop('disabled', false).css({'opacity':'1','pointer-events':'auto'});
      }
    });

    $('#addDiagnosticoEgreso').on('click', function() {
      AddDiagnosticoEgreso();
    });

    $('#addDiagnosticoQuirurgico').on('click', function() {
      AddDiagnosticoQuirurgico();
    });

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

    $('#tableProductosReceta tbody').on('click', 'button.deleteFila', function() {
      var fila = $(this).closest('tr');
      fila.remove();
      filasmedicamentos--;
    });

    $('#tableProductosAlta tbody').on('click', 'button.deleteFila', function() {
      var fila = $(this).closest('tr');
      fila.remove();
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
    $('#tblInterconsultaEv tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
    
    $('#tblExamenLab tbody').on('click', 'button.deleteFila', function () {
        var fila = $(this).closest('tr');
        var codActual = fila.find('.cod_lab').val();

        // Buscar si este código pertenece a un grupo
        var grupoEncontrado = grupos.find(g => g.includes(codActual));

        // Eliminar fila actual
        fila.remove();

        if (grupoEncontrado) {
            // Buscar el otro código del grupo (el que no es el actual)
            var otroCod = grupoEncontrado.find(c => c !== codActual);

            // Buscar y eliminar la fila con el otro código
            $('#tblExamenLab tbody tr').each(function () {
                var cod = $(this).find('.cod_lab').val();
                if (cod === otroCod) {
                    $(this).remove();
                }
            });
        }
    });
    $('#tblExamenRad tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });

    $('#tblDiagnosticoQuirurgico tbody').on('click', 'button.deleteFila', function() {
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

    $("#id_establecimiento_traslado").select2({
      width: 'resolve'
    });

    $("#especialida_destino").select2({
      width: 'resolve'
    });

    $("#condicion_traslado").select2({
      width: 'resolve'
    });

    $("#id_servicio_hospitalizacion").select2({
      width: 'resolve'
    });

    $("#temperatura").inputmask("decimal", {
      digits: 2,
      placeholder: "0.00",
      allowMinus: false,
      allowPlus: false,
      max: 150,
      digitsOptional: false
    });
    $("#talla").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 250,
      digitsOptional: false
    });
    $("#peso").inputmask("decimal", {
      digits: 2,
      placeholder: "0.00",
      allowMinus: false,
      allowPlus: false,
      max: 500,
      digitsOptional: false
    });
    $("#saturacion").inputmask("decimal", {
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
    });
    $("#oxigenacion").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100,
      digitsOptional: false
    });

    $("#oxigenaciontriaje").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100,
      digitsOptional: false
    });
    
    //AddDiagnostico();
    //AddProcedimiento();
    //AddExamenLab();
    //AddExamenRad();
    getProducto();
    getProductoEgreso();
    modalHistorial();
    modalHistorial1();
    modalHistorial2();
    modalHistorial3();
    modalLaboratorio();
    modalRadiologico();
    modalAntecedentes();
    modalDiagnostico();
    modalProcedimiento();
    //modalHistorialAtencion();
    modalHistorialEvaluaciones();
    modalHistorialAtencionEv();
    modalInterconsulta();
    //genera siteds
    const codigo_siteds = {!! json_encode($codigo_siteds) !!}
    console.log(codigo_siteds);
    if (codigo_siteds) {
      $("#codigo_siteds").val(codigo_siteds);
    } else {
      generarCodigoSitedsEvaluacion();
    }

    /**============== ESCALA DE GLASGOW INICIO ========================**/
    $("#adulto_respuesta_ocular").on("blur", function() { // respuesta_ocular rango del 1 al 4
      let value = $(this).val().trim();
      
      // Si el valor no es un número entre 1 y 4, se limpia el campo
      if (isNaN(value) || value < 1 || value > 4) {
          $(this).val("");
          $(".c-e-m-1").addClass("contenedor-error-mensaje");
          $(".error-mensaje-1").text("El valor debe estar entre 1 a 4");
      } else {
          $(".error-mensaje-1").text(""); // Limpiar el mensaje de error si el valor es válido
          $(".c-e-m-1").removeClass("contenedor-error-mensaje");
      }
    });
    $("#adulto_respuesta_verbal").on("blur", function() { // respuesta_verbal rango del 1 al 5
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 1 y 5, se limpia el campo
        if (isNaN(value) || value < 1 || value > 5) {
            $(this).val("");
            $(".c-e-m-2").addClass("contenedor-error-mensaje");
            $(".error-mensaje-2").text("El valor debe estar entre 1 a 5");
        } else {
            $(".error-mensaje-2").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-e-m-2").removeClass("contenedor-error-mensaje");
        }
    });
    $("#adulto_respuesta_motora").on("blur", function() { // respuesta_motora rango del 1 al 6
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 1 y 6, se limpia el campo
        if (isNaN(value) || value < 1 || value > 6) {
            $(this).val("");
            $(".c-e-m-3").addClass("contenedor-error-mensaje");
            $(".error-mensaje-3").text("El valor debe estar entre 1 a 6");
        } else {
            $(".error-mensaje-3").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-e-m-3").removeClass("contenedor-error-mensaje");
        }
    });
    $("#adulto_total_escala_glasgow").on("input", function() { // total_escala_glasgow rango del 3 al 15
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 3 y 15, se limpia el campo
        if (isNaN(value) || value < 3 || value > 15) {
            $(this).val("");
            console.log("Debe escribir el rango del 3 al 15");
        }
    });
    const calcularTotal = () => {
        let total = 0;
        $(".operacion-escala-glasgow").each(function() {
            let valor = parseInt($(this).val()) || 0; // Convertir a entero y manejar el caso de campos vacíos
            total += valor;
        });
        $("#adulto_total_escala_glasgow").val(total);
    }

    // Escuchar el evento change en los campos de entrada de respuestas
    $(".operacion-escala-glasgow").on("change", function() {
        calcularTotal();
    });
    /**============== ESCALA DE GLASGOW FIN ========================**/
    /**============== SIGNOS VITALES INICIO ========================**/
    $("#adulto_presion_arterial_x").on("blur", function() { // adulto_presion_arterial_x rango del 0 al 250
        let value = $(this).val();
        if (Validar.esVacio(value) == false ) {
          // Si el valor no es un número entre 0 y 250, se limpia el campo
          if (isNaN(value) || value < 0 || value > 250) {
              $(this).val("");
              $(".c-s-v-1").addClass("contenedor-error-mensaje");
              $(".error-mensaje-4").text("El valor debe estar entre 0 a 250");
          } else {
              $(".error-mensaje-4").text(""); // Limpiar el mensaje de error si el valor es válido
              $(".c-s-v-1").removeClass("contenedor-error-mensaje");
          }
        } else {
          $(".c-s-v-1").addClass("contenedor-error-mensaje");
          $(".error-mensaje-4").text("El valor debe estar entre 0 a 250");
        }
        
    });
    $("#adulto_presion_arterial_y").on("blur", function() { // adulto_presion_arterial_y rango del 0 al 140
        let value = $(this).val().trim();
        if (Validar.esVacio(value) == false ) {
          // Si el valor no es un número entre 0 y 140, se limpia el campo
          if (isNaN(value) || value < 0 || value > 140) {
              $(this).val("");
              $(".c-s-v-1").addClass("contenedor-error-mensaje");
              $(".error-mensaje-4").text("El valor debe estar entre 0 a 140");
          } else {
              $(".error-mensaje-4").text(""); // Limpiar el mensaje de error si el valor es válido
              $(".c-s-v-1").removeClass("contenedor-error-mensaje");
          }
        } else {
          $(".c-s-v-1").addClass("contenedor-error-mensaje");
          $(".error-mensaje-4").text("El valor debe estar entre 0 a 140");
        }
    });
    $("#adulto_frecuencia_cardiaca").on("blur", function() { // adulto_frecuencia_cardiaca rango del 50 al 150
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 50 y 150, se limpia el campo
        if (isNaN(value) || value < 50 || value > 150) {
            $(this).val("");
            $(".c-s-v-2").addClass("contenedor-error-mensaje");
            $(".error-mensaje-5").text("El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )");
        } else {
            $(".error-mensaje-5").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-s-v-2").removeClass("contenedor-error-mensaje");
        }
    });
    $("#adulto_frecuencia_respiratorio").on("blur", function() { // adulto_frecuencia_respiratorio rango del 10 al 35
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 10 y 35, se limpia el campo
        if (isNaN(value) || value < 10 || value > 35) {
            $(this).val("");
            $(".c-s-v-3").addClass("contenedor-error-mensaje");
            $(".error-mensaje-6").text("El valor de la Frec. Respiratoria debe estar entre 10 a 35 ( /min )");
        } else {
            $(".error-mensaje-6").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-s-v-3").removeClass("contenedor-error-mensaje");
        }
    });
    $("#adulto_temperatura").on("blur", function() { // adulto_temperatura rango del 35.00 a 45.00 ( °C )
        let value = $(this).val();

        // Si el valor no es un número entre 35.00 a 45.00 ( °C ), se limpia el campo
        if (Validar.esVacio(value) == false ) {
          if( !Validar.decimalEnRango(value, 35.0, 45.0)) {
            $(".c-s-v-4").addClass("contenedor-error-mensaje");
            $(".error-mensaje-7").text("La temperatura debe estar entre 35.00 a 45.00 ( °C )");
          } else {
            $(".error-mensaje-7").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-s-v-4").removeClass("contenedor-error-mensaje");
          }
        } else {
          $(".c-s-v-4").addClass("contenedor-error-mensaje");
          $(".error-mensaje-7").text("La temperatura debe estar entre 35.00 a 45.00 ( °C )");
        }
    });
    /**============== SIGNOS VITALES FIN ========================**/
    /**============== EXAMENES DE LABORATORIO INICIO ========================**/
    
    $("#adulto_fiO2").on("blur", function() { // adulto_fiO2 rango del 21 al 100
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 21 y 100, se limpia el campo
        if (isNaN(value) || value < 21 || value > 100) {
            $(this).val("");
            $(".c-e-l-5").addClass("contenedor-error-mensaje");
            $(".error-mensaje-12").text("El valor de la fIO2 debe estar entre 21 a 100 ( % )");
        } else {
            $(".error-mensaje-12").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-e-l-5").removeClass("contenedor-error-mensaje");
        }
    });
    /**============== EXAMENES DE LABORATORIO FIN ========================**/
    /**============== ANTECEDENTES PATOLOGICOS INICIO ========================**/
    $('#adulto_neoplasias').on('click', function () {
      console.log("Clink en Neoplasias");
      let adulto_neoplasias = $('#adulto_neoplasias').attr("aria-pressed");
      if (adulto_neoplasias == 'false') {
        $('#adulto_neoplasias_especifique').show();
      } else {
        $('#adulto_neoplasias_especifique').val('');
        $('#adulto_neoplasias_especifique').hide();
      }
    });
    $('#adulto_leucemias').on('click', function () {
      let adulto_leucemias = $('#adulto_leucemias').attr("aria-pressed");
      if (adulto_leucemias == 'false') {
        $('#adulto_leucemias_especifique').show();
      } else {
        $('#adulto_leucemias_especifique').val('');
        $('#adulto_leucemias_especifique').hide();
      }
    });
    $('#adulto_antecedentes_patologicos_otros_1').on('click', function () {
      let adulto_antecedentes_patologicos_otros_1 = $('#adulto_antecedentes_patologicos_otros_1').attr("aria-pressed");
      if (adulto_antecedentes_patologicos_otros_1 == 'false') {
        $('#adulto_antecedentes_patologicos_otros_1_especifique').show();
      } else {
        $('#adulto_antecedentes_patologicos_otros_1_especifique').val('');
        $('#adulto_antecedentes_patologicos_otros_1_especifique').hide();
      }
    });
    $('#adulto_antecedentes_patologicos_otros_2').on('click', function () {
      let adulto_antecedentes_patologicos_otros_2 = $('#adulto_antecedentes_patologicos_otros_2').attr("aria-pressed");
      if (adulto_antecedentes_patologicos_otros_2 == 'false') {
        $('#adulto_antecedentes_patologicos_otros_2_especifique').show();
      } else {
        $('#adulto_antecedentes_patologicos_otros_2_especifique').val('');
        $('#adulto_antecedentes_patologicos_otros_2_especifique').hide();
      }
    });
    /**============== ANTECEDENTES PATOLOGICOS FIN ========================**/
    /**============== SOLICITUD DE FRASCO DE HEMOCULTIVO INICIO ========================**/
    $('#adulto_frasco_1').on('click', function () {
      let adulto_frasco_1 = $('#adulto_frasco_1').attr("aria-pressed");
      if (adulto_frasco_1 == 'false') {
        $('#adulto_frasco_1_opcion_1').show();
        $('#adulto_frasco_1_opcion_2').show();
      } else {
        $('#adulto_frasco_1_opcion_1').val('0');
        $('#adulto_frasco_1_opcion_2').val('0');
        $('#adulto_frasco_1_opcion_1').hide();
        $('#adulto_frasco_1_opcion_2').hide();
      }
    });
    $('#adulto_frasco_2').on('click', function () {
      let adulto_frasco_2 = $('#adulto_frasco_2').attr("aria-pressed");
      if (adulto_frasco_2 == 'false') {
        $('#adulto_frasco_2_opcion_1').show();
        $('#adulto_frasco_2_opcion_2').show();
      } else {
        $('#adulto_frasco_2_opcion_1').val('0');
        $('#adulto_frasco_2_opcion_2').val('0');
        $('#adulto_frasco_2_opcion_1').hide();
        $('#adulto_frasco_2_opcion_2').hide();
      }
    });
    $('#adulto_frasco_3').on('click', function () {
      let adulto_frasco_3 = $('#adulto_frasco_3').attr("aria-pressed");
      if (adulto_frasco_3 == 'false') {
        $('#adulto_frasco_3_opcion_1').show();
        $('#adulto_frasco_3_opcion_2').show();
      } else {
        $('#adulto_frasco_3_opcion_1').val('0');
        $('#adulto_frasco_3_opcion_2').val('0');
        $('#adulto_frasco_3_opcion_1').hide();
        $('#adulto_frasco_3_opcion_2').hide();
      }
    });
    /**============== SOLICITUD DE FRASCO DE HEMOCULTIVO FIN ========================**/
    $('#adulto_hemocultivo_seguimiento_y_vigilancia').on('click', function () {
      let adulto_hemocultivo_seguimiento_y_vigilancia = $('#adulto_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed");
      if (adulto_hemocultivo_seguimiento_y_vigilancia == 'false') {
        $('#adulto_contenedor_fecha_primer_resultado_hemocultivo').show();
      } else {
        $('#adulto_fecha_primer_resultado_hemocultivo').val('');
        $('#adulto_contenedor_fecha_primer_resultado_hemocultivo').hide();
      }
    });
    /**============== SOLICITUD DE EXAMEN DE HEMOCULTIVO NEONATO Y PEDRIATRÍA INICIO ========================**/
    
    $('.grupo_checkbox_edad_gestional').on('change', function() {
        $('.grupo_checkbox_edad_gestional').not(this).prop('checked', false);
    });

    $("#pedriatria_respuesta_ocular").on("blur", function() { // pedriatria_respuesta_ocular rango del 1 al 4
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 1 y 4, se limpia el campo
        if (isNaN(value) || value < 1 || value > 4) {
            $(this).val("");
            $(".c-p-e-g-1").addClass("contenedor-error-mensaje");
            $(".error-mensaje-20").text("El valor debe estar entre 1 a 4");
      } else {
          $(".error-mensaje-20").text(""); // Limpiar el mensaje de error si el valor es válido
          $(".c-p-e-g-1").removeClass("contenedor-error-mensaje");
      }
    });
    $("#pedriatria_respuesta_verbal").on("blur", function() { // pedriatria_respuesta_verbal rango del 1 al 5
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 1 y 5, se limpia el campo
        if (isNaN(value) || value < 1 || value > 5) {
            $(this).val("");
            $(".c-p-e-g-2").addClass("contenedor-error-mensaje");
            $(".error-mensaje-21").text("El valor debe estar entre 1 a 5");
        } else {
            $(".error-mensaje-21").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-p-e-g-2").removeClass("contenedor-error-mensaje");
        }
    });
    $("#pedriatria_respuesta_motora").on("blur", function() { // pedriatria_respuesta_motora rango del 1 al 6
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 1 y 6, se limpia el campo
        if (isNaN(value) || value < 1 || value > 6) {
            $(this).val("");
            $(".c-p-e-g-3").addClass("contenedor-error-mensaje");
            $(".error-mensaje-22").text("El valor debe estar entre 1 a 6");
        } else {
            $(".error-mensaje-22").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-p-e-g-3").removeClass("contenedor-error-mensaje");
        }
    });
    $("#pedriatria_total_escala_glasgow").on("input", function() { // pedriatria_total_escala_glasgow rango del 3 al 15
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 3 y 15, se limpia el campo
        if (isNaN(value) || value < 3 || value > 15) {
            $(this).val("");
            console.log("Debe escribir el rango del 3 al 15");
        }
    });
    const calcularTotalPedriatriaEscalaGlasgow = () => {
        let total = 0;
        $(".operacion-pedriatria-escala-glasgow").each(function() {
            let valor = parseInt($(this).val()) || 0; // Convertir a entero y manejar el caso de campos vacíos
            total += valor;
        });
        $("#pedriatria_total_escala_glasgow").val(total);
    }

    $(".operacion-pedriatria-escala-glasgow").on("change", function() {
      calcularTotalPedriatriaEscalaGlasgow();
    });
    const calcularTotalPedriatriaApgar = () => {
        let total = 0;
        $(".operacion-apgar").each(function() {
            let valor = parseInt($(this).val()) || 0; // Convertir a entero y manejar el caso de campos vacíos
            total += valor;
        });
        $("#pedriatria_total_apgar").val(total);
    }

    $(".operacion-apgar").on("change", function() {
      calcularTotalPedriatriaApgar();
    });

    $("#pedriatria_presion_arterial_x").on("blur", function() { // pedriatria_presion_arterial_x rango del 0 al 250
        let value = $(this).val();
        if (Validar.esVacio(value) == false ) {
          // Si el valor no es un número entre 0 y 250, se limpia el campo
          if (isNaN(value) || value < 0 || value > 250) {
              $(this).val("");
              $(".c-p-s-v-1").addClass("contenedor-error-mensaje");
              $(".error-mensaje-23").text("El valor debe estar entre 0 a 250");
          } else {
              $(".error-mensaje-23").text(""); // Limpiar el mensaje de error si el valor es válido
              $(".c-p-s-v-1").removeClass("contenedor-error-mensaje");
          }
        } else {
          $(".c-p-s-v-1").addClass("contenedor-error-mensaje");
          $(".error-mensaje-23").text("El valor debe estar entre 0 a 250");
        }
        
    });
    $("#pedriatria_presion_arterial_y").on("blur", function() { // pedriatria_presion_arterial_y rango del 0 al 140
        let value = $(this).val().trim();
        if (Validar.esVacio(value) == false ) {
          // Si el valor no es un número entre 0 y 140, se limpia el campo
          if (isNaN(value) || value < 0 || value > 140) {
              $(this).val("");
              $(".c-p-s-v-1").addClass("contenedor-error-mensaje");
              $(".error-mensaje-23").text("El valor debe estar entre 0 a 140");
          } else {
              $(".error-mensaje-23").text(""); // Limpiar el mensaje de error si el valor es válido
              $(".c-p-s-v-1").removeClass("contenedor-error-mensaje");
          }
        } else {
          $(".c-p-s-v-1").addClass("contenedor-error-mensaje");
          $(".error-mensaje-23").text("El valor debe estar entre 0 a 140");
        }
    });
    $("#pedriatria_frecuencia_cardiaca").on("blur", function() { // pedriatria_frecuencia_cardiaca rango del 50 al 150
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 50 y 150, se limpia el campo
        if (isNaN(value) || value < 50 || value > 150) {
            $(this).val("");
            $(".c-p-s-v-2").addClass("contenedor-error-mensaje");
            $(".error-mensaje-24").text("El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )");
        } else {
            $(".error-mensaje-24").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-p-s-v-2").removeClass("contenedor-error-mensaje");
        }
    });
    $("#pedriatria_frecuencia_respiratorio").on("blur", function() { // pedriatria_frecuencia_respiratorio rango del 10 al 35
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 10 y 35, se limpia el campo
        if (isNaN(value) || value < 10 || value > 35) {
            $(this).val("");
            $(".c-p-s-v-3").addClass("contenedor-error-mensaje");
            $(".error-mensaje-25").text("El valor de la Frec. Respiratoria debe estar entre 10 a 35 ( /min )");
        } else {
            $(".error-mensaje-25").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-p-s-v-3").removeClass("contenedor-error-mensaje");
        }
    });
    $("#pedriatria_temperatura").on("blur", function() { // pedriatria_temperatura rango del 35.00 a 45.00 ( °C )
        let value = $(this).val();

        // Si el valor no es un número entre 35.00 a 45.00 ( °C ), se limpia el campo
        if (Validar.esVacio(value) == false ) {
          if( !Validar.decimalEnRango(value, 35.0, 45.0)) {
            $(".c-p-s-v-4").addClass("contenedor-error-mensaje");
            $(".error-mensaje-26").text("La temperatura debe estar entre 35.00 a 45.00 ( °C )");
          } else {
            $(".error-mensaje-26").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-p-s-v-4").removeClass("contenedor-error-mensaje");
          }
        } else {
          $(".c-p-s-v-4").addClass("contenedor-error-mensaje");
          $(".error-mensaje-26").text("La temperatura debe estar entre 35.00 a 45.00 ( °C )");
        }
    });
    $("#pedriatria_fiO2").on("blur", function() { // pedriatria_fiO2 rango del 21 al 100
        let value = $(this).val().trim();
        
        // Si el valor no es un número entre 21 y 100, se limpia el campo
        if (isNaN(value) || value < 21 || value > 100) {
            $(this).val("");
            $(".c-p-e-l-5").addClass("contenedor-error-mensaje");
            $(".error-mensaje-31").text("El valor de la fIO2 debe estar entre 21 a 100 ( % )");
        } else {
            $(".error-mensaje-31").text(""); // Limpiar el mensaje de error si el valor es válido
            $(".c-p-e-l-5").removeClass("contenedor-error-mensaje");
        }
    });
    $('#pedriatria_examen_laboratorio_otros').on('click', function () {
      let pedriatria_examen_laboratorio_otros = $('#pedriatria_examen_laboratorio_otros').attr("aria-pressed");
      if (pedriatria_examen_laboratorio_otros == 'false') {
        $('#pedriatria_examen_laboratorio_otros_especifique').show();
      } else {
        $('#pedriatria_examen_laboratorio_otros_especifique').val('');
        $('#pedriatria_examen_laboratorio_otros_especifique').hide();
      }
    });
    $('#pedriatria_frasco_1').on('click', function () {
      let pedriatria_frasco_1 = $('#pedriatria_frasco_1').attr("aria-pressed");
      if (pedriatria_frasco_1 == 'false') {
        $('#pedriatria_frasco_1_opcion_1').show();
        $('#pedriatria_frasco_1_opcion_2').show();
      } else {
        $('#pedriatria_frasco_1_opcion_1').val('0');
        $('#pedriatria_frasco_1_opcion_2').val('0');
        $('#pedriatria_frasco_1_opcion_1').hide();
        $('#pedriatria_frasco_1_opcion_2').hide();
      }
    });
    $('#pedriatria_frasco_2').on('click', function () {
      let pedriatria_frasco_2 = $('#pedriatria_frasco_2').attr("aria-pressed");
      if (pedriatria_frasco_2 == 'false') {
        $('#pedriatria_frasco_2_opcion_1').show();
        $('#pedriatria_frasco_2_opcion_2').show();
      } else {
        $('#pedriatria_frasco_2_opcion_1').val('0');
        $('#pedriatria_frasco_2_opcion_2').val('0');
        $('#pedriatria_frasco_2_opcion_1').hide();
        $('#pedriatria_frasco_2_opcion_2').hide();
      }
    });
    $('#pedriatria_frasco_3').on('click', function () {
      let pedriatria_frasco_3 = $('#pedriatria_frasco_3').attr("aria-pressed");
      if (pedriatria_frasco_3 == 'false') {
        $('#pedriatria_frasco_3_opcion_1').show();
        $('#pedriatria_frasco_3_opcion_2').show();
      } else {
        $('#pedriatria_frasco_3_opcion_1').val('0');
        $('#pedriatria_frasco_3_opcion_2').val('0');
        $('#pedriatria_frasco_3_opcion_1').hide();
        $('#pedriatria_frasco_3_opcion_2').hide();
      }
    });
    $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').on('click', function () {
      let pedriatria_hemocultivo_seguimiento_y_vigilancia = $('#pedriatria_hemocultivo_seguimiento_y_vigilancia').attr("aria-pressed");
      if (pedriatria_hemocultivo_seguimiento_y_vigilancia == 'false') {
        $('#pedriatria_contenedor_fecha_primer_resultado_hemocultivo').show();
      } else {
        $('#pedriatria_fecha_primer_resultado_hemocultivo').val('');
        $('#pedriatria_contenedor_fecha_primer_resultado_hemocultivo').hide();
      }
    });
    /**============== SOLICITUD DE EXAMEN DE HEMOCULTIVO NEONATO Y PEDRIATRÍA FIN ========================**/
    /**============== SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS INICIO ========================**/
    // $('.grupo_checkbox_citoquimico_liquidos_biologicos').on('change', function() {
    //     $('.grupo_checkbox_citoquimico_liquidos_biologicos').not(this).prop('checked', false);
    // });
    $('.grupo_checkbox_citoquimico_liquidos_biologicos').change(function() {
      // Si el checkbox "Otro" está marcado, mostrar el input de especifique
      if ($('#liquido_otro').is(':checked')) {
          $('#liquido_otro_especifique').show();
      } else {
          // Si el checkbox "Otro" no está marcado, ocultar el input de especifique
          $('#liquido_otro_especifique').val('');
          $('#liquido_otro_especifique').hide();
      }
    });
    /**============== SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS FIN ========================**/

  });

  const evitarLetras = (event) => {
    let charCode = event.which ? event.which : event.keyCode;
    let inputValue = String.fromCharCode(charCode);

    // Expresión regular para permitir solo números y un punto decimal
    let pattern = /^[\d.]+$/;

    if (!pattern.test(inputValue)) {
      event.preventDefault();
    }

    return true;
  }
  
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
            const idTopico = "{{old('id_topico')}}";
            if (idTopico.length > 0) {
              console.log(idTopico);
              $('#id_topico').val(idTopico).change();
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

function compruebaIMCInicio() {
  var peso = parseFloat($('#triaje_peso').val());
  var talla = parseFloat($('#triaje_talla').val());

  if (!isNaN(talla)) {
    // PESO POR ALTURA
    var sexo = $('input[name="nomsexo"]').val(); // MASCULINO, FEMENINO
    // Edad
    var edad_anos = parseInt($('input[name="edadpaciente"]').val(), 10); // 12/05/1992

    var pesoIdeal = 50 + ((talla - 150) / 4) * 3 + (edad_anos - 20) / 4;
    if (sexo === "M") {
      pesoIdeal = pesoIdeal * 0.9;
    }
    $('input[name="triaje_peso_ideal"]').val(pesoIdeal.toFixed(2));
  } else {
    $('input[name="triaje_peso_ideal"]').val("");
  }

  if (!isNaN(peso) && !isNaN(talla)) {
    var talla_en_metros = talla * 0.01;
    // IMC
    var imcx = (peso / (talla_en_metros * talla_en_metros)).toFixed(2);
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
    const codigo1 = element.codigo;
    if((element.codigo).trim() !== codigo2){

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
updateSelectElements2();   

}


function compruebaIMC() {
  var peso = parseFloat($('#peso').val());
  var talla = parseFloat($('#talla').val());

  if (!isNaN(talla)) {
    // PESO POR ALTURA
    var sexo = $('input[name="nomsexo"]').val(); // MASCULINO, FEMENINO
    // Edad
    var edad_anos = parseInt($('input[name="edadpaciente"]').val(), 10); // 12/05/1992

    var pesoIdeal = 50 + ((talla - 150) / 4) * 3 + (edad_anos - 20) / 4;
    if (sexo === "M") {
      pesoIdeal = pesoIdeal * 0.9;
    }
    $('input[name="peso_x_altura"]').val(pesoIdeal.toFixed(2));
  } else {
    $('input[name="peso_x_altura"]').val("");
  }

  if (!isNaN(peso) && !isNaN(talla)) {
    var talla_en_metros = talla * 0.01;
    // IMC
    var imcx = (peso / (talla_en_metros * talla_en_metros)).toFixed(2);
    $('input[name="imc"]').val(imcx);
  } else {
    $('input[name="imc"]').val('');
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


const personalesIds = [];
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
        console.log(personalesIds);
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          if (personalesIds[i] == oo.id) {
            newOption += "<option selected value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
          } else {
            newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
          }
        });                 
        $('#personal_salud'+i).html(newOption);
        //$("#personal_salud").select2({max_selected_options: 4});      
      }
    });        
}

    if (parseInt("{{count(old('id_servicio_interconsulta'))}}") > 0) {
  const ids = document.querySelectorAll('input[name="personal_salud_prev[]"]');
  for (let i = 0; i < parseInt("{{count(old('id_servicio_interconsulta'))}}"); i++) {
    console.log(i);
    getMedico($("#id_servicio_interconsulta"+i).val(), i);
    personalesIds.push(ids[i].value);
  }
}


function getProducto(){
	
	$('#tableProductosReceta tbody').html('');
	var id_ipress 		= $('#id_establecimiento').val();
	var id_consultorio 	= $('#id_servicio').val();
	var id_tiporeceta 	= $('#id_tipo_atencion').val();
	var id_farmacia     = $('#id_farmacia_receta').val();
	var nivel     = $('#id_nivel').val();
	var x  = $('#nro_interconsultas').val();
	var dni = $('#dni_beneficiario').val();
	var interconsulta = $('#interconsulta').val();
	if(id_tiporeceta == 2 && id_farmacia == 21){ //nutricional
  	if(id_tiporeceta==5 || id_tiporeceta == 2){
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
          newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				});                 
				$('##cmbMedicamentoTempReceta').html(newOption);
			}
		});
	}
  else{
      if(id_tiporeceta==2 && (id_farmacia == 19 || id_farmacia == 107 || id_farmacia ==22 || id_farmacia ==14)){
        id_consultorio=76;
      }
      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          });                 
          $('#cmbMedicamentoTempReceta').html(newOption);
        }
      });
  }
  if(id_farmacia == 132){
    $.ajax({
      url: '/obtener_producto_farmacotecnia/' + id_ipress,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
        });                 
        $('#cmbMedicamentoTempReceta').html(newOption);
      }
    });
  }
}

function getProductoEgreso(){
  $('#tableProductosAlta tbody').html('');
	var id_ipress 		= $('#id_establecimiento').val();
	var id_consultorio 	= $('#id_servicio').val();
	var id_tiporeceta 	= $('#id_tipo_atencion').val();
	var id_farmacia     = $('#id_farmacia_receta_egreso').val();
	var nivel     = $('#id_nivel').val();
	var x  = $('#nro_interconsultas').val();
	var dni = $('#dni_beneficiario').val();
	var interconsulta = $('#interconsulta').val();
	if(id_tiporeceta == 2 && id_farmacia == 21){ //nutricional
  	if(id_tiporeceta==5 || id_tiporeceta == 2){
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
					// newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
				});                 
				$('#cmbMedicamentoTempAlta').html(newOption);
			}
		});
	}
  else{
      if(id_tiporeceta==2 && (id_farmacia == 19 || id_farmacia == 107 || id_farmacia ==22 || id_farmacia ==14)){
        id_consultorio=76;
      }
      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            // newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          });                 
          $('#cmbMedicamentoTempAlta').html(newOption);
        }
      });
  }
  if(id_farmacia == 132){
    $.ajax({
      url: '/obtener_producto_farmacotecnia/' + id_ipress,
      success: function(result){                    
        var newOption = "";
        newOption = "<option value=''>--Seleccionar-</option>";
        $(result).each(function (ii, oo) {
          // newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' infecto='"+oo.infecto+"'>"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
        });                 
        $('#cmbMedicamentoTempAlta').html(newOption);
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
  $('#btnPrestacion').prop("disabled",true);

	var id_tipo_atencion = $('#id_tipo_atencion').val();
  var codigo_upss = $('#codigo_upss').val();
  var id_modalidad = $('#id_modalidad').val();
	var fecha_atencion = $('#fecha_atencion').val();
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
	var addExamenLab = $('#addExamenLab').attr("aria-pressed");
	var addExamenRad = $('#addExamenRad').attr("aria-pressed");
	var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
  var addReferencia = $('#addReferencia').attr("aria-pressed");
	var addCondicion = $('#addCondicion').attr("aria-pressed");
	var cantidadReceta = $('#cantidadReceta').val();
  // let tiempo_enfermedad = $('#tiempo_enfermedad').val();
  //   let motivo_visita = $('#motivo_visita').val();
  //   let anamnesis = $('#anamnesis').val();
  //   if(tiempo_enfermedad.trim()=="") msg+="Debe ingresar el <b>Tiempo de Enfermedad </b><br>"
  //   if(motivo_visita.trim()=="") msg+="Debe ingresar el <b>Motivo de Visita</b><br>"
  //   if(anamnesis.trim()=="") msg+="Debe ingresar un <b>Relato Cronológico</b><br>"

	$('#procedimientosSeleccionados1').val(JSON.stringify(procedimientosSeleccionados));
	let vardatos = $('#procedimientosSeleccionados1').val();
	if(dni_beneficiario=="" && beneficiario=="")msg+="Debe buscar un Asegurado<br>";

  var msg = "";
  <?php if($codigo_siteds=="" || $codigo_siteds==null){ ?>

    var cod_siteds= $('#codigo_siteds').val();  
    
    if ($('#frmPrestacion').find("#codigo_siteds").val() == "") {
      msg+="Debe ingresar un Codigo siteds manualmente, no se pudo hacer la conexion con SUSALUD <br>";
    }else{
      $("#codigo_siteds").val(completarConCeros(cod_siteds,12));  
    }

    //validar_dato_siteds_ambulatorio
    var codSiteds= $('#codigo_siteds').val();  
    $.ajax({
      url:'{{url('validar_dato_siteds_evaluacion/')}}/'+codSiteds,
      type:'GET',
      dataType:'JSON', 
        success: function(data){   
          if(data!=null){ 
            console.log('data[0].cantidad_siteds:'+ data.cantidad_siteds); 
            if(data.cantidad_siteds!=0){ 
              bootbox.alert("EL código SITEDS YA EXISTE, verifique que sea correcto y vuelva a intentarlo");
            } 
          }
        }
    });
  <?php } ?>

	var ind_diag = $('#tblDiagnostico tbody tr').length;
	if(ind_diag > 0){
		fila_dignosticos=$('#tblDiagnostico tbody').children('tr');
		for (i = 0; i < ind_diag; i++) {
			fila_dignostico=fila_dignosticos[i];
			var id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
			var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
			if(id_diagnostico == "")msg+="Debe ingresar el nombre de diagnostico<br>";
			if(id_tipo_diagnostico == "0")msg+="Debe ingresar el tipo de diagnostico<br>";
		}
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
			if(id_procedimiento == "")msg+="Debe ingresar el nombre del procedimiento<br>";
      if(cantidad == "")msg+="Debe ingresar la cantidad del procedimiento<br>";
      if(cantidad == 0)msg+="Debe ingresar la cantidad mayor a 0<br>";
      if(miSelect == "" || miSelect==null)msg+="Debe seleccionar el diagnostico para el procedimiento<br>";
		}
	}else{
		msg+="Debe ingresar minimo un procedimiento<br>";
	}

	  if(fecha_expedicion=="")msg+="Debe ingresar una Fecha de Expedicion<br>";
	
	if(addRecetaVale == "true"){
		var ind = $('#tableProductosReceta tbody tr').length;  
          
		if(ind > 0){
			var nind = ind-1;
		  
    	var cmbMedicamento = $("#cmbMedicamentoTempReceta"+nind).val();
			if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == ""){
				$('#example .deleteFila').eq(ind - 1).parent().parent().remove();
				ind = ind-1;
			}
        fila_productosmedicos=$('#tableProductosReceta tbody').children('tr');            
			
      for (i = 0; i < ind; i++) {
          
          fila_medicamento=fila_productosmedicos[i];
          var cmbMedicamento = $(fila_medicamento).find('.cmbMedicamentoTempReceta').val();
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

  var indAlta = $('#tableProductosAlta tbody tr').length;

  if (indAlta > 0) {

      var nindAlta = indAlta - 1;
      var cmbMedicamentoAlta = $("#cmbMedicamentoTempAlta" + nindAlta).val();

      if (cmbMedicamentoAlta == "ru='undefined'" || cmbMedicamentoAlta == "") {
          $('#tableProductosAlta .deleteFila').eq(indAlta - 1).parent().parent().remove();
          indAlta--;
      }

      var filaProductosAlta = $('#tableProductosAlta tbody').children('tr');

      for (var j = 0; j < indAlta; j++) {

        var filaMedicamentoAlta = filaProductosAlta[j];

        var cmbMedicamentoAlta      = $(filaMedicamentoAlta).find('.cmbMedicamentoTempAlta').val();
        var tiempo_periodo_alta     = $(filaMedicamentoAlta).find('.tiempo_periodo').val();
        var posologia_alta          = $(filaMedicamentoAlta).find('.posologia').val();
        var valmedentre_alta        = $(filaMedicamentoAlta).find('.nro_med_solictados').val();
        var via_administracion_alta = $(filaMedicamentoAlta).find('.id_via_administracion').val();
        var diag_prod_alta          = $(filaMedicamentoAlta).find('.select-diagnostico-alta').val();
        if (cmbMedicamentoAlta == "ru='undefined'" || cmbMedicamentoAlta == "undefined")msg += "Falta seleccionar un producto (dispositivo médico)<br>";
        if (tiempo_periodo_alta == "" || tiempo_periodo_alta == 0)msg += "Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0<br>";
        if (via_administracion_alta == 0 || via_administracion_alta == "undefined")msg += "Uno de los productos no cuenta con la vía de administración<br>";
        if (posologia_alta == "")msg += "Uno de los productos no cuenta con la posología<br>";
        if (valmedentre_alta == "" || valmedentre_alta == 0)msg += "Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0<br>";
      }

  }else{
      var selectElement = document.querySelector('select[name="id_producto_alta[]"]');
      if (selectElement) {
          selectElement.selectedIndex = -1;
      }
  }


	if(addExamenLab == "true"){
		let totalRowExamen = $('#tblExamenLab tbody tr').length;
    if (totalRowExamen > 0) {
      let examen_lab_tr = $('#tblExamenLab tbody').children('tr');

      for (i = 0; i < totalRowExamen; i++) {
        let fila_examen_lab = examen_lab_tr[i];

        let nombre_examen_lab = $(fila_examen_lab).find('input[name="nombre_examen_lab[]"]').val();
        let id_examen_lab = $(fila_examen_lab).find('input[name="id_examen_lab[]"]').val();
        let cant_examen_lab = $(fila_examen_lab).find('input[name="examen_lab_cantidad[]"]').val();
        let diagnostico_examen_lab = $(fila_examen_lab).find('select[name="miSelectLab[]"]').val();

        if (id_examen_lab == "") {
          msg += "Debe registrar un examen de laboratorio en la fila: " + (i+1) + "<br>";
        } else {
          if (cant_examen_lab == "") msg += "Debe registrar una cantidad en laboratorio: <b>" + nombre_examen_lab + "</b><br>";
          if (cant_examen_lab == "0") msg += "Debe registrar una cantidad mayor a 0 para el examen de laboratorio: <b>" + nombre_examen_lab + "</b><br>";
          if (diagnostico_examen_lab == "" || diagnostico_examen_lab == null) msg += "Debe seleccionar un diagnostico para el examen de laboratorio: <b>" + nombre_examen_lab + "</b><br>";
        }
      }
    }

    /*if (totalRowExamen > 1) {
      //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FINAL =================
      let fin_flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra' + (totalRowExamen - 1)).val();
      if (fin_flag_descripcion_tipo_muestra == 1) {
        let fin_descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + (totalRowExamen - 1)).val();
        if (fin_descripcion_tipo_muestra == "") {
          msg+='Debe indicar el tipo de muestra para microbilogía en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button><br>';
        }
      }
      
      //=== LABORATORIO VALIDA SOLICITUD HEMOCULTIVO ADULTO ESTE CARGADA FINAL =================
      let fin_flag_solicitud_examen_especializados_hemocultivo_adulto = $('#flag_solicitud_examen_especializados_hemocultivo_adulto' + (totalRowExamen - 1)).val()
      if (fin_flag_solicitud_examen_especializados_hemocultivo_adulto == 1) {
        let fin_des_adulto_respuesta_ocular = $('#des_adulto_respuesta_ocular' + (totalRowExamen - 1)).val();
        if (fin_des_adulto_respuesta_ocular == "") {
          msg+='Debe indicar la solicitud del examen especializado Hemocultivo adulto en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }
      
      //=== LABORATORIO VALIDA SOLICITUD HEMOCULTIVO PEDRIATRIA ESTE CARGADA FINAL =================
      let fin_flag_solicitud_examen_especializados_hemocultivo_pedriatria = $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria' + (totalRowExamen - 1)).val()
      if (fin_flag_solicitud_examen_especializados_hemocultivo_pedriatria == 1) {
        let fin_des_pedriatria_respuesta_ocular = $('#des_pedriatria_respuesta_ocular' + (totalRowExamen - 1)).val();
        if (fin_des_pedriatria_respuesta_ocular == "") {
          msg+='Debe indicar la solicitud del examen especializado Hemocultivo neonato y pedriatria en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }

      //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FINAL ===============================
      let fin_flag_solicitud_citoquimicos_liquidos_biologicos = $('#flag_solicitud_citoquimicos_liquidos_biologicos' + (totalRowExamen - 1)).val();
      if (fin_flag_solicitud_citoquimicos_liquidos_biologicos == 1) {
        let fin_des_num_frasco_muestra_opcion = $('#des_num_frasco_muestra_opcion' + (totalRowExamen - 1)).val();
        if (fin_des_num_frasco_muestra_opcion == "") {
          msg+='Debe registrar la solicitud para estudio citoquímico líquido en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }

    } else {
      //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
      let inicio_flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra0').val();
      if (inicio_flag_descripcion_tipo_muestra == 1) {
        let inicio_descripcion_tipo_muestra = $('#descripcion_tipo_muestra0').val();
        if (inicio_descripcion_tipo_muestra == "") {
          msg+='Debe indicar el tipo de muestra para microbilogía en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button><br>';
        }
      }

      //=== LABORATORIO VALIDA SOLICITUD HEMOCULTIVO ADULTO ESTE CARGADA INICIO =================
      let inicio_flag_solicitud_examen_especializados_hemocultivo_adulto = $('#flag_solicitud_examen_especializados_hemocultivo_adulto0').val()
      if (inicio_flag_solicitud_examen_especializados_hemocultivo_adulto == 1) {
        let inicio_des_adulto_respuesta_ocular = $('#des_adulto_respuesta_ocular0').val();
        if (inicio_des_adulto_respuesta_ocular == "") {
          msg+='Debe indicar la solicitud del examen especializado Hemocultivo adulto en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }

      //=== LABORATORIO VALIDA SOLICITUD HEMOCULTIVO PEDRIATRIA ESTE CARGADA INICIO =================
      let inicio_fin_flag_solicitud_examen_especializados_hemocultivo_pedriatria = $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria0').val()
      if (inicio_fin_flag_solicitud_examen_especializados_hemocultivo_pedriatria == 1) {
        let inicio_des_pedriatria_respuesta_ocular = $('#des_pedriatria_respuesta_ocular0').val();
        if (inicio_des_pedriatria_respuesta_ocular == "") {
          msg+='Debe indicar la solicitud del examen especializado Hemocultivo neonato y pedriatria en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }

      //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FINAL ===============================
      let inicio_flag_solicitud_citoquimicos_liquidos_biologicos = $('#flag_solicitud_citoquimicos_liquidos_biologicos0').val();
      if (inicio_flag_solicitud_citoquimicos_liquidos_biologicos == 1) {
        let inicio_des_num_frasco_muestra_opcion = $('#des_num_frasco_muestra_opcion0').val();
        if (inicio_des_num_frasco_muestra_opcion == "") {
          msg+='Debe registrar la solicitud para estudio citoquímico líquido en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }

    }*/
	}else{
    var inputElement = document.querySelector('input[name="id_examen_lab[]"]');
    if(inputElement){
      inputElement.value = '';
    }
  }
	if(addExamenRad == "true"){
		// VALIDACIÓN DINÁMICA: CAMPOS REQUERIDOS PARA IMÁGENES
    const isAddExamenRadActive = $('#addExamenRad').hasClass('active');
    const tiempo_enfermedad = $('#tiempo_enfermedad').val();
    const motivo_visita = $('#motivo_visita').val();
    const examen_auxiliar = $('#examen_auxiliar').val();

    if (isAddExamenRadActive) {
      if (motivo_visita.trim() === "") msg += "Debe ingresar el <b>Motivo de la Visita</b><br>";
      if (tiempo_enfermedad.trim() === "") msg += "Debe ingresar el <b>Tiempo de la Enfermedad Actual</b><br>";
      if (examen_auxiliar.trim() === "") msg += "Debe ingresar  <b>Examen Auxiliar</b><br>";
    }
    var id_examen_rad = $('input[name="id_examen_rad[]"]').map(function(){return $(this).val();}).get();
		var proce_rad = $('input[name="examen_rad_cantidad[]"]').map(function(){return $(this).val();}).get();
		if(id_examen_rad == "")msg+="Debe registrar un examen de imagenologia<br>";
		if(proce_rad == "")msg+="Debe registrar una cantidad en imagenologia<br>";
    if(proce_rad == 0)msg+="Debe registrar una cantidad mayor a 0 en imagenologia<br>";
	}else{
    var inputElement = document.querySelector('input[name="id_examen_rad[]"]');
    if(inputElement){
      inputElement.value = '';
    }
  }
  if (addInterconsulta == "true") {
    let tblInterconsulta_cantidad_tr = $('#tblInterconsultaEv tbody tr').length;
    if (tblInterconsulta_cantidad_tr > 0) {
      let interconsulta_tr = $('#tblInterconsultaEv tbody').children('tr');

      for (i = 0; i < tblInterconsulta_cantidad_tr; i++) {
        let fila_interconsulta = interconsulta_tr[i];

        let id_servicio_interconsulta = $(fila_interconsulta).find('[name="id_servicio_interconsulta[]"]').val();
        let motivo_interconsulta = $(fila_interconsulta).find('textarea[name="motivo_interconsulta[]"]').val();

        if (id_servicio_interconsulta == "") msg += "Debe seleccionar el Servicio de Interconsulta<br>";
        if (motivo_interconsulta == "") msg += "Debe ingresar el Motivo de la Interconsulta<br>";
      }
    }
  }
  

  var seleccionados = $("input[name='cod_lab[]']").map(function () {
    return $(this).val();
  }).get();

  msg += verificarFaltantes(seleccionados);

  // Datos del Alta
  var swRegistroAlta = $('#swRegistroAlta').attr("aria-pressed");

  if (swRegistroAlta == "true") {
    var ind_diag = $('#tblDiagnosticoEgreso tbody tr').length;
    if (ind_diag > 0) {
      fila_dignosticos = $('#tblDiagnosticoEgreso tbody').children('tr');
      for (i = 0; i < ind_diag; i++) {
        fila_dignostico = fila_dignosticos[i];
        var id_diagnostico = $(fila_dignostico).find('.id_diagnostico_egreso').val();
        var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico_egreso').val();
        if (id_diagnostico == "") msg += "Debe ingresar el nombre de diagnostico<br>";
        if (id_tipo_diagnostico == "0") msg += "Debe ingresar el tipo de diagnostico<br>";
      }
    } else {
      msg += "Debe ingresar minimo un diagnostico de Egreso.<br>";
    }

    var fecha_egreso = $('#fecha_egreso').val();
    if (fecha_egreso == "") {
      msg += "Debe ingresar la Fecha de Egreso Médico<br>";
    }

    var hora_egreso = $('#hora_egreso').val();
    if (hora_egreso == "") {
      msg += "Debe ingresar la Hora de Egreso Médico<br>";
    }

    var id_tipo_egreso = $('#id_tipo_egreso').val();

    if (id_tipo_egreso == "") {
      if (id_tipo_egreso == "") {
        msg += "Debe ingresar un tipo de egreso<br>";
      }
    } else {
      if (id_tipo_egreso == 2) {
        var id_servicio_hospitalizacion = $('#id_servicio_hospitalizacion').val();
        let id_edificio_ubicacion = $('#id_edificio_ubicacion').val();
        let id_pabellon_ubicacion = $('#id_pabellon_ubicacion').val();
        // let id_cama_ubicacion = $('#id_cama_ubicacion').val();
        // let id_clase_cama_ubicacion = $('#id_clase_cama_ubicacion').val();

        if (id_servicio_hospitalizacion == "") {
          msg += "Debe elegir un servicio de Hospitalizacion<br>";
        }

        if (id_edificio_ubicacion == "") {
          msg += "Debe seleccionar si la ubicacion es PIP o Histórico <b>(Ubicación del Paciente)</b><br>";
        }

        if (id_pabellon_ubicacion == "") {
          msg += "Debe seleccionar el Pabellón <b>(Ubicación del Paciente)</b><br>";
        }

        // if (id_upss_ubicacion == "") {
        //   msg += "Debe seleccionar el UPSS del Servicio <b>(Ubicación del Paciente)</b><br>";
        // }
        
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
  }

  if(msg!=""){
    bootbox.alert(msg);
    $('#btnPrestacion').prop("disabled",false);
    $('.loader').hide();
    return false;
  }
  else{
    /*const codigo_siteds = {!! json_encode($codigo_siteds) !!}
    if (codigo_siteds) {
      $("#frmPrestacion").submit(); 
    } else {
      const result = await generarCodigoSitedsEvaluacion();
      $("#codigo_siteds").remove();
      $("#frmPrestacion").append('<input type="hidden" name="codigo_siteds" id="codigo_siteds">');
      if (result && result.nuAutorizacion) {
        $("#codigo_siteds").val(result.nuAutorizacion);
      } else {
        $("#codigo_siteds").val(null);
      }*/
      // $("#frmPrestacion").submit();
    //}

    if (swRegistroAlta == "true") {
      $.ajax({
        url: "{{ route('buscar_dia_estancia_ajax') }}",
        type: "GET",
        dataType: "JSON",
        data: {
          id_asegurado: <?php echo $verificar_existe->id_asegurado; ?>,
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
                  $('#frmPrestacion').submit();
                }
              });
            } else {
              $('#frmPrestacion').submit();
            }
          }
        }
      });
    } else {
      $("#frmPrestacion").submit();
    }

  }
}


function obtenerEstablecimiento(parentesco,ubigeo){
	var newOption = "";
	newOption = "<option value=''>--Seleccionar--</option>";
	$('#id_establecimiento').html(newOption);
	
	$.ajax({
		url: '../obtener_establecimiento/'+parentesco+"/"+ubigeo,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value=''>--Seleccionar--</option>";
			$(result).each(function (ii, oo) {
				newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
			});                 
			$('#id_establecimiento').html(newOption);
			$("#id_establecimiento").select2({max_selected_options: 4});
		
		}
	});
}

function getServicio(){
	var newOption = "";
	newOption = "<option value=''>--Seleccionar--</option>";
	$('#id_servicio').html(newOption);
	//$("#id_servicio").val('').trigger("change");
	var parent = $('input[name="parent"]:checked').val();
	//var parent	= $('#parent').val();
	
	$.ajax({
		url: '../obtener_consultorio_parent/'+parent,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value=''>--Seleccionar--</option>";
			$(result).each(function (ii, oo) {
				newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
			});                 
			$('#id_servicio').html(newOption);
			$("#id_servicio").select2({max_selected_options: 4});
		
		}
	});
	
}
function fullCalendar(){
	//var SITEURL = "{{url('/')}}";
	var iIdMovimientoCompra = $('#hdIdMovimientoCompra').val();
	var iIdTipoCompra		= $('#hdIdTipoCompra').val();
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();
	$("#divHorario").html("");
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			/*right: 'month,agendaWeek,agendaDay'*/
		},
		editable: false,
		//events: "submit/cronogramaCompraData.php?iIdMovimientoCompra="+iIdMovimientoCompra,
		//events: "cronograma_cita",
		events: "cronograma_cita/"+id_ipress+"/"+id_servicio,
		//data: {id_ipress:id_ipress,id_servicio:id_servicio},
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {
			dateObj = new Date(start); /* Or empty, for today */
			dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
			dateObjNTZ = new Date(dateIntNTZ);
			start = dateObjNTZ.toISOString().slice(0, 10);
			start = $.trim(start);
			var sValidaciones = "";
				modalDelegar(start)
	
		}
	});	
		
}
function fn_RegistrarNuevoCronograma(start,iIdMovimientoCompra,iIdTipoCompra) {
	/*
    var oBotones = {
		"Pagar": function () {
            var btnGrabar = $("#ifrModal").contents().find("input[id='btnGrabar']");
            btnGrabar.click();
        },
        "Cerrar": function () {
            $(this).dialog("close");
            $(this).remove();
        }
    };
    var sRuta = "frmCronogramaCompraDetalle.php?param=" + iIdMovimientoCompra + "&param1=" + iIdTipoCompra + "&param2=" + start;
    fn_util_AbreModalNew("Agendar Pago", sRuta, '40', 150, null, oBotones);
	*/
	$('#showAtencionesMensual').modal();
}
function modalDelegar___(start,iIdMovimientoCompra,iIdTipoCompra){
	
	$(".modal-dialog").css("width","80%");
	$('#openOverlayOpc').modal('show');
	//$('#openOverlayOpc').modal();
	$('#openOverlayOpc .modal-body').css('height', 'auto');
	//alert(start);
	$.ajax({
			url: "../modal_cita_medico/"+start,
			type: "GET",
			success: function (result) {  
					$("#diveditpregOpc").html(result);
			}
	});
}
function modalDelegar(start){
	
	//$(".modal-dialog").css("width","80%");
	//$('#openOverlayOpc').modal('show');
	//$('#openOverlayOpc .modal-body').css('height', 'auto');
	
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();;
			
	$.ajax({
			url: "../modal_cita_medico/"+id_ipress+"/"+id_servicio+"/"+start,
			type: "GET",
			success: function (result) {  
					$("#divHorario").html(result);
			}
	});
}
function modalTurnos(){
	
	//$(".modal-dialog").css("width","80%");
	//$('#openOverlayOpc').modal('show');
	//$('#openOverlayOpc .modal-body').css('height', 'auto');
	
	var id_servicio	= $('#id_servicio').val();
	var id_ipress	= $('#id_establecimiento').val();;
	//var start = '2019-10-24';
	var start = "<?php echo $fechaServidor?>";
	//alert(start);
	$.ajax({
			url: "../modal_turnos/"+id_ipress+"/"+id_servicio+"/"+start,
			type: "GET",
			success: function (result) {  
					$("#divTurno").html(result);
			}
	});
}
function modalHistorial(){
  var id_principal = $("#id_principal").val();
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_evaluaciones_atenciones/"+id_principal+'/'+2,
			type: "GET",
			success: function (result) {  
					$("#divHistorial").html(result);
					$('#tablemodal1').DataTable({
						"sPaginationType": "full_numbers",
						
						"language": {"url": "/js/Spanish.json"},
					});
			}
	});
}

var texto_marca_agua_historial = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';

function generar_marca_agua_historial(overlayId) {
  var overlay = document.getElementById(overlayId);
  if (!overlay) return;

  overlay.innerHTML = '';

  var W = overlay.offsetWidth  || 800;
  var H = overlay.offsetHeight || 600;

  var cols = Math.ceil(W / 220) + 1;
  var rows = Math.ceil(H / 100) + 1;

  for (var r = 0; r < rows; r++) {
    for (var c = 0; c < cols; c++) {
      var span = document.createElement('span');
      span.textContent = texto_marca_agua_historial;
      span.style.left = (c * 220 - 40) + 'px';
      span.style.top  = (r * 100 - 20) + 'px';
      overlay.appendChild(span);
    }
  }
}

$(document).ready(function () {
  function registrarAuditoriaHistorial(idTipoAtencion) {
    $.ajax({
      url: '/auditoria-hc/registrar',
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        origen: 'Evaluación EMG(HA)',
        doc_buscado: $('#dni_beneficiario').val(),
        id_tipo_atencion: idTipoAtencion
      }
    });
  }

  $('a[href="#tab10default"]').on('shown.bs.tab', function () {
    generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
    registrarAuditoriaHistorial(1);
  });
  $('a[href="#tab11default"]').on('shown.bs.tab', function () {
    generar_marca_agua_historial('marca_agua_overlay_hist_emergencia');
    registrarAuditoriaHistorial(2);
  });
  $('a[href="#tab12default"]').on('shown.bs.tab', function () {
    generar_marca_agua_historial('marca_agua_overlay_hist_hospitalizacion');
    registrarAuditoriaHistorial(3);
  });

  var resizeMarcaAguaHistorialTimer = null;
  $(window).on('resize', function () {
    clearTimeout(resizeMarcaAguaHistorialTimer);
    resizeMarcaAguaHistorialTimer = setTimeout(function () {
      if ($('#tab10default').is(':visible')) generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
      if ($('#tab11default').is(':visible')) generar_marca_agua_historial('marca_agua_overlay_hist_emergencia');
      if ($('#tab12default').is(':visible')) generar_marca_agua_historial('marca_agua_overlay_hist_hospitalizacion');
    }, 250);
  });
});

function modalHistorial1(){
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_atenciones_1/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
					$("#divHistorial1").html(result);
					$('#tablemodalm1').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
						"sPaginationType": "full_numbers",
						
						"language": {"url": "/js/Spanish.json"},
					});
          generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
			}
	});
}

function modalHistorial2(){
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_atenciones_2/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
					$("#divHistorial2").html(result);
					$('#tablemodalm21').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
						"sPaginationType": "full_numbers",
						
						"language": {"url": "/js/Spanish.json"},
					});
          generar_marca_agua_historial('marca_agua_overlay_hist_emergencia');
			}
	});
}

function modalHistorial3(){
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_atenciones_3/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
					$("#divHistorial3").html(result);
					$('#tablemodalm3').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
						"sPaginationType": "full_numbers",
						
						"language": {"url": "/js/Spanish.json"},
					});
          generar_marca_agua_historial('marca_agua_overlay_hist_hospitalizacion');
			}
	});
}

function modalAntecedentes(){
	
	var dni_beneficiario = $("#dni_beneficiario").val();
	var id_tipo_atencion = $("#id_tipo_atencion").val();
	var id_cita = $("#id_cita").val();
	
	$.ajax({
			url: "../paciente_antecedentes/"+dni_beneficiario+'/'+id_tipo_atencion+'/'+id_cita,
			type: "GET",
			success: function (result) {  
					$("#divAntecedentes").html(result);
			}
	});
}



//Luis rojas 
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

//Luis rojas 
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
        if (item) {
            var claveUnica = JSON.stringify(item);
            if (!conjuntoUnico.has(claveUnica)) {
                conjuntoUnico.add(claveUnica);
                lista_diagnosticos.push(JSON.parse(claveUnica));
            }
        }
    });
    listIdDiagnosticoObjetos = [];
    listIdDiagnosticoObjetos = lista_diagnosticos;//LISTA DE DIAGNOSTICOS UNICOS
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

function addNewArrayEgreso(id_diagnostico) {
  if (listIdDiagnosticoEgresoObjetos.findIndex(e => e.id == id_diagnostico) == -1) {
    listIdDiagnosticoEgreso.push(id_diagnostico);
    if (listIdDiagnosticoEgreso.length > 0) {
      let codigo = JSON.stringify(id_diagnostico);
      $.ajax({
        dataType: 'json',
        type: 'POST',
        url: '/obtenerdatosDiagnosticos2',
        data: { codigo: codigo },
        success: function (result) {
          listIdDiagnosticoEgresoObjetos.push(result.objeto);
          updateSelectElementsEgreso();
        },
        error: function (msg, textStatus, errorThrown) {}
      });
    }
  }
}

function updateSelectElementsEgreso() {

  let selectElements = document.querySelectorAll('.select-diagnostico-alta');
  var conjuntoUnico = new Set();
  var lista = [];
  listIdDiagnosticoEgresoObjetos.forEach((item) => {
    if (item) {
      var clave = JSON.stringify(item);
      if (!conjuntoUnico.has(clave)) {
        conjuntoUnico.add(clave);
        lista.push(JSON.parse(clave));
      }
    }
  });
  listIdDiagnosticoEgresoObjetos = lista;

  selectElements.forEach((selectElement) => {
    let currentValue = selectElement.value;
    selectElement.innerHTML = "";
    listIdDiagnosticoEgresoObjetos.forEach((item) => {
      let option = document.createElement("option");
      option.value = item.id_diagnostico;
      option.textContent = item.nombre;
      selectElement.appendChild(option);
    });
    let optionExists = Array.from(selectElement.options).some(o => o.value === currentValue);
    selectElement.value = optionExists ? currentValue : "";
  });
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
	newRow +='<tr>';
	newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="44" required="" id="nombre_diagnostico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
	newRow +='<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico'+ind+'" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
	newRow +='<td><select tabindex="45" class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico'+ind+'" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
	newRow +='<td><button type="button" tabindex="46" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
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
				//url: '../obtener_diagnostico/' + request.term,
				url: '../obtener_diagnostico_genero/' + request.term + '/'+ genero,
				dataType: "json",
				success: function(data)  {
					let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
          response(filtro);
				}					
			})
	
		},select:  function(e, ui) {
			var id_diagnostico = ui.item.id;
			$("#id_diagnostico"+ind).val(id_diagnostico);
			$("#nombre_diagnostico"+ind).prop("readonly",true);
		}
	});
	
	/* $("#nombre_diagnostico"+ind).on('blur', function() {
		var id_diagnostico = $("#id_diagnostico"+ind).val();
		if(id_diagnostico == ""){
			bootbox.alert("Falta ingresar el diagnostico en esta fila");
			$("#nombre_diagnostico"+ind).val("");
			return false;
		}else{
      addNewArray(id_diagnostico);
    }
	}); */
  $("#nombre_diagnostico"+ind).on('blur', function() {
        const diagnostico = $("#nombre_diagnostico"+ind).val();
        const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
        var id_diagnostico = $("#id_diagnostico"+ind).val();
        if(id_diagnostico == ""){
          bootbox.alert("Falta ingresar el diagnostico en esta fila");
          $("#nombre_diagnostico"+ind).val("");
          return false;
        }else{
          console.log('object');
          if (firstLetterCode == "Z") {  //|| flag_saludpol == 0) {
            bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado por SALUDPOL.");
          }
          addNewArray(id_diagnostico);
        }
      });
	
}
function AddProcedimiento() {
  var newRow = "";
  var ind = $('#tblProcedimiento tbody tr').length;

  if (ind > 0) {
    $('.id_procedimiento').each(function() {
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

  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' + ind +
    '" name="id_procedimiento[]" value="" data-id='+ind+'></td>';
  newRow +=
    '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
    ind + '" name="procedimiento_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  $('#tblProcedimiento tbody').append(newRow);
  const selectElement = document.getElementById('miSelect' + ind);
  let defaultOption = document.createElement('option');
  //si entra la primera vez
  var i = 0;
  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    if (i==0){
      option.setAttribute("selected", "selected");  
      i++;
    }
    selectElement.appendChild(option);
  });

  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
	
  //tipo = 1
  $("#nombre_procedimiento" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '../obtener_procedimiento_convenio_emergencia/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
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
	
  $("#nombre_procedimiento" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    if (id_procedimiento == "") {
      bootbox.alert("Falta ingresar el procedimiento en esta fila");
      $("#nombre_procedimiento" + ind).val("");
      return false;
    }
  });
  $("#procedimiento_cantidad" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento" + ind).val();
    var cantidad = $("#procedimiento_cantidad" + ind).val();
    if (id_procedimiento == "") {
      bootbox.alert("Falta ingresar la cantidad en este procedimiento");
      $("#procedimiento_cantidad" + ind).val("");
      return false;
    }
    // Verifica si la cantidad es menor o igual a 0
    if (parseFloat(cantidad) <= 0) {
          bootbox.alert("Por favor, ingrese una cantidad mayor a 0");
          $("#procedimiento_cantidad" + ind).val("");
          return false;
      }
  });
	
}
function  AddInterconsulta2(){
  var newRow = "";
  var ind = $('#tblInterconsultaEv tbody tr').length;
  
  if(ind > 0){
    $('.id_servicio_interconsulta').each(function(){
      var ind_tmp = $(this).data("id");
      if(ind_tmp => ind){
        ind=Number(ind_tmp)+1;
      }
    });
          
    fila_medicamentos=$('#tblInterconsultaEv tbody').children('tr');
    rowIndex=$('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento=fila_medicamentos[idval];
    
  }

  let interconsulta_servicio_items = "";
  $('#cmbInterconsultaServiciosTemp option').each(function() {
    interconsulta_servicio_items += '<option value="' + $(this).val() + '" data-has_guardia="' + $(this).data('has_guardia') + '" >' + $(this).html() + '</option>';
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

  $('#tblInterconsultaEv tbody').append(newRow);
  $("#id_servicio_interconsulta" + ind).select2({max_selected_options: 4}).focus();

  /*$("#nombre_servicio_interconsulta"+ind).autocomplete({
    minLength: 1,
    delay : 400,
    source: function(request, response) {
      $.ajax({
        url: '{{ url("/") }}/obtener_consultorio_interconsulta/' + request.term +'/'+id_ipress,
        dataType: "json",
        success: function(data)  {
          let listaServicioSelecionado = $(".id_servicio_interconsulta").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaServicioSelecionado.includes(element.id));
          response(filtro);
        }         
      })
  
    },select:  function(e, ui) {
      var id_servicio_interconsulta = ui.item.id;
      let nombre_servicio_interconsulta = ui.item.value;
      let servicio_has_guardia = ui.item.has_guardia;
      let is_estable = $("#is_estable").val();
      $("#id_servicio_interconsulta"+ind).val(id_servicio_interconsulta);
      $("#nombre_servicio_interconsulta" + ind).val(nombre_servicio_interconsulta)
      $("#nombre_servicio_interconsulta"+ind).prop("readonly",true);
      // getMedico(id_servicio_interconsulta,ind);
      if (is_estable == 0) {
        bootbox.alert("<br>Favor de coordinar con el Jefe de guardia para responder la interconsulta.");
      } else {
        if (!servicio_has_guardia) {
          validarHorarioYFechasNoPermitidasInterconsulta(ind, id_servicio_interconsulta);
          return false;
        }
      }
    }
    
  });
  $("#nombre_servicio_interconsulta"+ind).on('blur', function() {
    if (!showValidacionCantInterconsulta) {
      var id_servicio_interconsulta = $("#id_servicio_interconsulta"+ind).val();
      if(id_servicio_interconsulta == ""){
        bootbox.alert("Falta ingresar el servicio en esta interconsulta");
        $("#nombre_servicio_interconsulta"+ind).val("");
        return false;
      }
    } else {
      showValidacionCantInterconsulta = false;
    }
  }); */

  $("#id_servicio_interconsulta" + ind).on("change", function(e) {
    let flagx = 0;
    // cmb = $(this);
    let interconsulta_servicio_id = $("#id_servicio_interconsulta" + ind).val();
    // console.log("interconsulta_servicio_id:"+ interconsulta_servicio_id);
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
        
        let mostrar_mensaje_coordinacion = false;
        let mostrar_textarea_motivo_interconsulta = false;

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
            horaLimiteInicio.setHours(7, 30, 0, 0);

            // Definir la hora límite fin (13:30)
            const horaLimiteFin = new Date();
            horaLimiteFin.setHours(13, 30, 0, 0);

            if (horaActual < horaLimiteInicio || horaActual > horaLimiteFin) {
              // Fuera del rango de horario
              let has_guardia = $(this).find('option:selected').data('has_guardia');

              if (has_guardia == 1) {
                // El servicio si hace guardia
                mostrar_textarea_motivo_interconsulta = true;

              } else if (has_guardia == 0) {
                // El servicio no hace guardia
                mostrar_mensaje_coordinacion = true;
              }

            } else {
              // Esta en el rango del horario
              mostrar_textarea_motivo_interconsulta = true;
            }

          } else {
            // Si es Domingo o es feriado
            mostrar_mensaje_coordinacion = true;

          }
        } catch (error) {
          bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error al validar el horario.<br><br>" + error);
        }

        if (mostrar_mensaje_coordinacion) {

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
              // $("#nombre_servicio_interconsulta" + ind).prop("readonly", true);
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

        if (mostrar_textarea_motivo_interconsulta) {
          $("#panel_interconsulta_resumen_" + ind).css('display', 'block');
          let $select = $("#id_servicio_interconsulta" + ind);

          // Bloquear visualmente Select2
          $select.next('.select2-container').css({
            'pointer-events': 'none',
            'background-color': '#eee',
            'opacity': 0.8
          });
        }

      }
    }

  });
}

/**
 * Validar horario de registro de interconsulta y no permitir registros en feriados
 * sabados y domingos
 */
async function validarHorarioYFechasNoPermitidasInterconsulta(ind, id_servicio) {

  let id_tipo_atencion = $("#id_tipo_atencion").val();console.log("entra porqueno tiene guardia");
  
  /*function limpiarCampos(ind) {
    $("#id_servicio_interconsulta" + ind).val('');
    $("#nombre_servicio_interconsulta" + ind).val('');
    $("#nombre_servicio_interconsulta" + ind).prop("readonly", false);
  }*/

  // if (id_servicio == SERVICIO_NEUMOLOGIA) {
  if (id_tipo_atencion == 2) { // Emergencia
    try {
      /*const is_feriado = await $.ajax({
        url: "{{ url('validar_fecha_actual_es_feriado') }}/",
        dataType: 'json',
      });

      if (is_feriado) {
        showValidacionCantInterconsulta = true;
        bootbox.alert("No se puede adicionar esta interconsulta porque es <b>feriado</b>.");
        limpiarCampos(ind);
        return;
      } else {*/
        let id_establecimiento = $('#id_establecimiento').val();
        const response = await $.ajax({
          url: "{{ url('interconsulta_obtener_cantidad') }}/" + id_establecimiento + "/" + id_servicio + "/2",
          dataType: 'json',
        });

        /*if (response.dia_semana_numero != DIA_SEMANA_SABADO
          || response.dia_semana_numero != DIA_SEMANA_DOMINGO) {*/
          // if (id_servicio == 27) {
            const horaActual = new Date();
            const [horas, minutos] = response.hora_actual.split(':').map(Number);
            horaActual.setHours(horas, minutos, 0, 0);

            // Definir la hora límite inicio (07:30)
            const horaLimiteInicio = new Date();
            horaLimiteInicio.setHours(07, 30, 0, 0);

            // Definir la hora límite fin (13:30)
            const horaLimiteFin = new Date();
            horaLimiteFin.setHours(13, 30, 0, 0);

            if (horaActual < horaLimiteInicio || horaActual > horaLimiteFin) {
              // showValidacionCantInterconsulta = true;
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
                  // $("#nombre_servicio_interconsulta" + ind).prop("readonly", true);

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
            } else {
              $("#panel_interconsulta_resumen_" + ind).css('display', 'block');
              // $("#nombre_servicio_interconsulta" + ind).prop("readonly", true);
              let $select = $("#id_servicio_interconsulta" + ind);

              // Bloquear visualmente Select2
              $select.next('.select2-container').css({
                'pointer-events': 'none',
                'background-color': '#eee',
                'opacity': 0.8
              });
            }
          // }
        /*} else {
          showValidacionCantInterconsulta = true;
          bootbox.alert("No se puede adicionar la interconsulta para este servicio, porque hoy <b>es Sábado o Domingo</b>.");
          limpiarCampos(ind);
          return;
        }*/
      //}
    } catch (error) {
      bootbox.alert("<span class='text-danger'><i class='fa fa-exclamation-triangle'></i></span> Ha ocurrido un error al validar el horario.");
    }
  }
}

function asignarValoresSelect(ui,ind){
    var id_examen_lab = ui.item.id;
      let codigo_procedimiento = ui.item.codigo_procedimiento;
      let id_categoria_examen_lab = ui.item.id_categoria;
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#id_categoria_examen_lab" + ind).val(id_categoria_examen_lab);
      $("#nombre_examen_lab" + ind).val(ui.item.value);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
      $("#cod_lab" + ind).val(ui.item.codigo_procedimiento);
      validaExamenSeleccionado($("#id_examen_lab" + ind).val());
      consultaWsResultExamenSeleccionado($("#id_examen_lab" + ind).val());
      if (id_categoria_examen_lab == "26") {
        // mostrarBotonParaTipoDeMuestraMicrobiologia(ind);
      }
      // SOLICITUD DE EXAMEN PARA HEMOCULTIVO
      if (codigo_procedimiento == '87040.01') {
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
          // mostrarBotonParaSolicitudHemocultivoPedriatria(ind);
        } else {
          // SOLICITUD DE EXAMEN PARA HEMOCULTIVO ADULTO
          // mostrarBotonParaSolicitudHemocultivoAdulto(ind);
        }
      }
      // SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS
      if (codigo_procedimiento == '87163') {
        // mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos(ind);
      }

       //valida las examenes depeendidntes
       var seleccionados = $("input[name='cod_lab[]']").map(function () {
          return $(this).val();
      }).get();

      if (verificarFaltantes(seleccionados) != "") {
          alert(verificarFaltantes(seleccionados));
      }

  }


function AddExamenLab() {
  var newRow = "";
  var ind = $('#tblExamenLab tbody tr').length;
	
  if (ind > 0) {
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
    console.log('ENTRO EN CONDICIÓN');
    console.log('INDICE : ' + ind);
    let flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra' + (ind - 1)).val();
    if (flag_descripcion_tipo_muestra == 1) {
      let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + (ind - 1)).val();
      if (descripcion_tipo_muestra == "") {
        Swal.fire({
            icon: 'error',
            title: 'Debe registrar el tipo de muestra para microbiología. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button>',
            showConfirmButton: false,
            timer: 3000
        });
        return;
      }
    }
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FIN =================
    //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO INICIO ============================
    let descripcion_examen = $('#descripcion_examen' + (ind - 1)).val();
    if (descripcion_examen == 'HEMOCULTIVO NEONATO Y PEDRIATRIA') {
      let flag_solicitud_examen_especializados_hemocultivo_pedriatria = $('#flag_solicitud_examen_especializados_hemocultivo_pedriatria' + (ind - 1)).val();
      if (flag_solicitud_examen_especializados_hemocultivo_pedriatria == 1) {
        let des_pedriatria_respuesta_ocular = $('#des_pedriatria_respuesta_ocular' + (ind - 1)).val();
        if (des_pedriatria_respuesta_ocular == "") {
          Swal.fire({
              icon: 'error',
              title: 'Debe registrar la solicitud del examen especializado Hemocultivo de Neonato y Pedriatría Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
              showConfirmButton: false,
              timer: 3000
          });
          return;
        }
      }
    }
    if (descripcion_examen == 'HEMOCULTIVO ADULTO') {
      let flag_solicitud_examen_especializados_hemocultivo_adulto = $('#flag_solicitud_examen_especializados_hemocultivo_adulto' + (ind - 1)).val();
      if (flag_solicitud_examen_especializados_hemocultivo_adulto == 1) {
        let des_adulto_respuesta_ocular = $('#des_adulto_respuesta_ocular' + (ind - 1)).val();
        if (des_adulto_respuesta_ocular == "") {
          Swal.fire({
              icon: 'error',
              title: 'Debe registrar la solicitud del examen especializado Hemocultivo adulto Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
              showConfirmButton: false,
              timer: 3000
          });
          return;
        }
      }
    }
    //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO FIN ===============================
    //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================
    if (descripcion_examen == 'CITOQUIMICO LIQUIDOS BIOLOGICOS') {
      let flag_solicitud_citoquimicos_liquidos_biologicos = $('#flag_solicitud_citoquimicos_liquidos_biologicos' + (ind - 1)).val();
      if (flag_solicitud_citoquimicos_liquidos_biologicos == 1) {
        let des_num_frasco_muestra_opcion = $('#des_num_frasco_muestra_opcion' + (ind - 1)).val();
        if (des_num_frasco_muestra_opcion == "") {
          Swal.fire({
              icon: 'error',
              title: 'Debe registrar la solicitud para estudio citoquímico líquido Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs><i class="fa fa-check-square"></i>Solicitud</button>',
              showConfirmButton: false,
              timer: 3000
          });
          return;
        }
      }
    }
    $('.id_laboratorio').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });
    fila_medicamentos = $('#tblExamenLab tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];
		
  }
  const id_nivel = $("#id_nivel").val();
  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query cod_lab" id="cod_lab' + ind +'" name="cod_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_categoria" id="id_categoria_examen_lab' + ind +'" name="id_categoria_examen_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" maxlength="2" placeholder="Cantidad" class="form-control examen_lab_cantidad typeahead tt-query" style="margin:0 5px;width:95%" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1" readonly></td>';
  if (id_nivel && parseInt(id_nivel) < 3) {
  newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  }
  newRow += '<td><select style="margin:0 10px;width:95%" class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
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
	// aqui abre el input para agregar el button agregar
  /*$("#examen_lab_cantidad" + ind).on('input', function() {
    var valor = $(this).val();
    if (valor === '0') {
      $(this).val(''); // Si ingresan 0, eliminarlo
    }
  });*/
	const selectElementLab = document.getElementById('miSelectLab' + ind);
  
  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
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
      let urlAjax = '../obtener_laboratorio_convenio_emergencia/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion;
      if (parseInt(nivel) === 1 || parseInt(nivel) === 2) {
        urlAjax = `/obtener_laboratorio_convenio/${request.term}/${nivel}/${genero}/2`;
      }
      $.ajax({
        url: urlAjax,
        // url: '../obtener_laboratorio_convenio_emergencia/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaLaboratorioSelecionado.includes(element.id));
          response(filtro);
        }
      })
	
    },
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
  /*$("#examen_lab_cantidad" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
      // $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });*/
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

function clearValuesFormPuerpera() {
  $('#fecha_parto').val('')
  $('#hora_parto').val('')
  $('#tipo_parto').val('')
  $('#complicacion_parto').val('')
  $('#descripcion_parto').val('')
}

function handleTipoDoc() {
  const selects = document.querySelectorAll(".tipo_doc");
  selects.forEach((select) => {
      select.addEventListener('change', () => {
          const inputTipoDoc = select.parentNode.nextElementSibling.childNodes[2].nextElementSibling;
          const tipoDoc = select.options[select.selectedIndex].text;
          if (tipoDoc == "DNI") {
              inputTipoDoc.maxLength = 8;
              inputTipoDoc.value = "";
          } else {
              inputTipoDoc.maxLength = 12;
              inputTipoDoc.value = "";
          }
      });
  });
}

function AddRowPurperio() {
  let newRow = "";
  let ind = $('#tblPuerperio tbody tr').length;
  if (ind > 0) {
    $('.id_puerperio').each(function() {
        let ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
        }
    });
    fila_medicamentos = $('#tblPuerperio tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    let idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];

    //Validar que el anterior registro este rellenado
    let estadoVidaPuerpera = $(`#estado_vida_puerpera${ind - 1}`).val();
    let tipoDocumentoPuerpera = $(`#tipo_documento_puerpera${ind - 1}`).val();
    let numeroDocumentoPuerpera = $(`#numero_documento_puerpera${ind - 1}`).val();
    let sexoPuerpera = $(`#sexo_puerpera${ind - 1}`).val();
    let tallaPuerpera = $(`#talla_puerpera${ind - 1}`).val();
    let pesoPuerpera = $(`#peso_puerpera${ind - 1}`).val();
    if (estadoVidaPuerpera == "" ||
        tipoDocumentoPuerpera == "" ||
        numeroDocumentoPuerpera == "" ||
        sexoPuerpera == "" ||
        tallaPuerpera == "" ||
        pesoPuerpera == ""
        ) {
        bootbox.alert("Ingrese los datos anterior del neonato para poder agregar uno nuevo. por favor");
        return false;
    }
  }
  const tipoDocsNati = {!! json_encode($tipoDocsNati) !!};

  let optionsTipodocs = '' 
  if (tipoDocsNati && tipoDocsNati.length > 0) {
      for (const tipo of tipoDocsNati) {
          optionsTipodocs += `<option value="${tipo.id}">${tipo.nombre}</option>`    
      }                
  }

  newRow += '<tr>';
  newRow +=
  `
    <td style="width: 13%; padding: 0px 0px 20px 0px">                                                                                          
        <label>ESTADO DE VIDA</label>
        <select class="form-control input-sm typeahead tt-query" name="estado_vida_puerpera[]" id="estado_vida_puerpera${ind}" style="width:100%">
            <option value=""> -Seleccionar-</option>
            <option value="VIVO">VIVO</option>
            <option value="MUERTO">MUERTO</option>
        </select>
    </td>
    <td style="width: 20%; padding: 0px 0px 20px 15px">                                                                                          
        <label>TIPO DE DOCUMENTOS</label>
        <select class="tipo_doc form-control input-sm typeahead tt-query" name="tipo_documento_puerpera[]" id="tipo_documento_puerpera${ind}" style="width:100%">
            <option value=""> -Seleccionar-</option>
            ${optionsTipodocs}
        </select>
    </td>
    <td style="width: 20%; padding: 0px 0px 20px 15px">
        <label>NUM. DOCUMENTOS</label>
        <input 
            type="text"
            onkeypress="return validacionDocSoloNumeros(event)" 
            placeholder="Num. documento" 
            name="numero_documento_puerpera[]" 
            id="numero_documento_puerpera${ind}" 
            class="form-control input-sm typeahead tt-query numero_documento_puerpera" 
            maxlength="10"
            pattern="[0-9]+"
            onkeypress="return /^[a-zA-Z0-9]+$/i.test(event.key)"
        >
    </td>
    <td style="width: 15%; padding: 0px 0px 20px 15px">                                                                                          
        <label>SEXO</label>
        <select class="form-control input-sm typeahead tt-query" name="sexo_puerpera[]" id="sexo_puerpera${ind}">
            <option value=""> -Seleccionar-</option>
            <option value="M">MASCULINO</option>
            <option value="F">FEMENINO</option>
        </select>
    </td>
    <td style="width: 7%; padding: 0px 0px 20px 15px">
        <label>TALLA(cm)</label>
        <input 
            type="text" 
            placeholder="0" 
            name="talla_puerpera[]" 
            id="talla_puerpera${ind}" 
            class="form-control input-sm typeahead tt-query" 
            maxlength="6"
            onkeypress="return /^[0-9.]+$/i.test(event.key)"
        >
    </td>
    <td style="width: 7%; padding: 0px 0px 20px 15px">
        <label>PESO(gr)</label>
        <input 
            type="text" 
            placeholder="0" 
            name="peso_puerpera[]" 
            id="peso_puerpera${ind}" 
            class="form-control input-sm typeahead tt-query" 
            maxlength="6"
            onkeypress="return /^[0-9.]+$/i.test(event.key)"
        >
    </td>
    <td style="width: 2%; padding: 0px 0px 20px 0px">
        <input 
            type="hidden" 
            class="form-control typeahead tt-query id_puerperio"
            id="id_parto_puerpera${ind}" 
            name="id_parto_puerpera[]" 
            data-id=${ind}
        >
    </td>
    <td style="width: 5%; padding: 0px 0px 10px 15px">
        <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left: 2%; margin-top: 20px;"><i class="fa fa-times"></i> Eliminar</button>
    </td>`;
  newRow += '<br /></tr>';
  $('#tblPuerperio tbody').append(newRow);
  handleTipoDoc();
}

$('#addRowPurperio').on('click', function() {
  AddRowPurperio();
  //LMP
  const rowsNati = document.getElementsByName('estado_vida_puerpera[]');
  rowsNati.forEach( row => {
    row.removeEventListener('change', changeTypeDoc);
    row.addEventListener('change', changeTypeDoc);
  });
});

$('#addPuerpera').on('click', function() {
    const addPuerpera = $('#addPuerpera').attr("aria-pressed");
        
    $("#formPurperio").hide();
    $("#fieldEventosEp").hide();
    if (parseInt("{{count(old('numero_documento_puerpera'))}}") == 0) {
      $('#tblPuerperio tbody').empty(); 
      clearValuesFormPuerpera();
    } else {
      const rowsNati = document.getElementsByName('estado_vida_puerpera[]');
        rowsNati.forEach( row => {
          row.removeEventListener('change', changeTypeDoc);
          row.addEventListener('change', changeTypeDoc);
        });
    }
    if (addPuerpera == "false") {
        $("#formPurperio").show();
        $("#fieldEventosEp").show();
    }; 
    $("#esta_en_puerperio").val(addPuerpera == "true" ? "false" : "true");
    if (("{{old('fecha_parto')}}").length > 0) {
      const fecha_parto = "{{old('fecha_parto')}}";
      $("#fecha_parto").val(fecha_parto);
    }
    if (("{{old('hora_parto')}}").length > 0) {
      const hora_parto = "{{old('hora_parto')}}";
      $("#hora_parto").val(hora_parto);
    }
    if (("{{old('descripcion_parto')}}").length > 0) {
      const descripcion_parto = "{{old('descripcion_parto')}}";
      $("#descripcion_parto").val(descripcion_parto);
    }
    if (("{{old('complicacion_parto')}}").length > 0) {
    const complicacion_parto = "{{old('complicacion_parto')}}";
    $("#complicacion_parto").val(complicacion_parto).change();
    }
    if (parseInt("{{old('tipo_parto')}}") > 0) {
      const tipo_parto = "{{old('tipo_parto')}}";
      $("#tipo_parto").val(tipo_parto).change();
  }
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

function modalHistorialEvaluaciones(){
  
  var id_principal = $("#id_principal").val();
  var estado = $("#estado").val();
  var id_tipo_atencion = $("#id_tipo_atencion").val();

  $.ajax({
      url: "../../modal_historial_evaluaciones/"+id_principal+"/"+estado+"/"+id_tipo_atencion,
      type: "GET",
      success: function (result) {  
          $("#divEvaluacione").html(result);

          $('#tablemodalm3').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}

function modalHistorialAtencionEv(){
  
  var id_principal = $("#id_principal").val();
  var estado = $("#estado").val();
  var id_tipo_atencion = $("#id_tipo_atencion").val();

  $.ajax({
      url: "../../modal_historial_evaluaciones_atenciones/"+id_principal+'/'+2,
      type: "GET",
      success: function (result) {  
          $("#divAtencionEvo").html(result);

          $('#tablemodalm').DataTable({
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}
/*
function modalHistorialAtencion(){
  
  var id_principal = $("#id_principal").val();
  var id_tipo_atencion = $("#id_tipo_atencion").val();
  var estado = $("#estado").val();
  if (id_tipo_atencion==2){
    var url = "../../modal_historial_atenciones_emergencia/"+id_principal+"/"+estado;
  }
  else
  {
    var url = "../../modal_historial_atenciones_hospitalizacion/"+id_principal+"/"+estado;
  }
  
  $.ajax({
      url,
      type: "GET",
      success: function (result) {  
          $("#divAtencion").html(result);

          $('#tablemodalm').DataTable({
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}*/
function modalDiagnostico(){
  
   var id_prestacion = $("#id_prestacion").val();
  $.ajax({
      url: "../../modal_diagnostico_paciente/"+id_prestacion,
      type: "GET",
      success: function (result) {  
          $("#divDiagnostico").html(result);
      }
  });

}

function modalProcedimiento(){
  
  var id_prestacion = $("#id_prestacion").val();
  $.ajax({
      url: "../../modal_procedimiento_paciente/"+id_prestacion,
      type: "GET",
      success: function (result) {  
          $("#divProcedimiento").html(result);
      }
  });

}



function modalLaboratorio(){
  const nivel = {!! json_encode($nivel) !!};
  var dni_beneficiario = $("#dni_beneficiario").val();
  if (nivel && parseInt(nivel) === 1 || nivel && parseInt(nivel) === 2) {
    $.ajax({
      url: `/modal_historial_laboratorio_nivel_1_2/${dni_beneficiario}`,
      type: "GET",
      success: function (result) {  
        $("#divLaboratorio").html(result);
        $('#tablemodalm1').DataTable({"sPaginationType": "full_numbers", "language": {"url": "/js/Spanish.json"}});
      }
    });
  } else {
    $.ajax({
        url: "../modal_historial_laboratorio/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divLaboratorio").html(result);
            $('#tablemodalLaboratorio').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
        }
    });
  }
}
function modalRadiologico(){
  
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../modal_historial_radiologia/"+dni_beneficiario,
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

function modalInterconsulta(){
  
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../modal_historial_interconsulta/"+dni_beneficiario,
      type: "GET",
      success: function (result) {  
          $("#divInterconsultaH").html(result);
          $('#tablemodalm4').DataTable({
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}

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
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 5px;width:100%" id="examen_rad_cantidad' +ind + '" name="examen_rad_cantidad[]" value="1"></td>';
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
  let id_tipo_atencion = $('#id_tipo_atencion').val();
  if (sexo == 'M')
    genero = 1;
  else
    genero = 2;
  //tipo=3
	const selectElementRad = document.getElementById('miSelectRad' + ind);
  
  listIdDiagnosticoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    selectElementRad.appendChild(option);
  });

  $("#nombre_examen_rad" + ind).autocomplete({
    minLength: 1,
    delay: 400,
    source: function(request, response) {
      $.ajax({
        url: '../obtener_radiologia_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + 76,
        //url: '../obtener_procedimiento/' + request.term,
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
      $("#examen_rad_cantidad" + ind).val("");
      return false;
    }
  });
  $("#examen_rad_cantidad" + ind).on('blur', function() {
    var id_examen_rad = $("#id_examen_rad" + ind).val();
    if (id_examen_rad == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen radiografia");
      $("#examen_rad_cantidad" + ind).val("");
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

let contadorFilas = 0;
var modalInfectoMostrado = false;
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
  // var ind = filasmedicamentos
  var ind = contadorFilas++;
  var tabindex = 101;
  var nuevalperiodo = "";
  if (ind > 0) {
    $('.cmbMedicamentoTempReceta').each(function() {
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
  $('#cmbMedicamentoTempReceta option').each(function() {
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
  newRow +='<td><select class="form-control cmbMedicamentoTempReceta" id="cmbMedicamentoTempReceta' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
	
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
  $('#tableProductosReceta tbody').append(newRow);
  // $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();
  let select = $("#cmbMedicamentoTempReceta" + ind);
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


  function validproductoseleccionado(id_farmacia, btn) {//Jose
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamentoTempReceta").val();
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

  listIdDiagnosticoEgresoObjetos.forEach((item) => {
    let option = document.createElement('option');
    option.value = item.id_diagnostico;
    option.textContent = item.nombre;
    selectElementProdAlta.appendChild(option);
  });
  $("#abrir-modal-" + ind).click(function(e) {
    e.stopPropagation();
  
    abrirModal(ind);
  });
	
  $("#nro_med_solictados" + ind).keypress(function(e) {
    if (e.which == 13) {
      <?php //if ($modo_registro == 1 && ($tipo_registro == 1 || $tipo_registro == 3)):?>
      AddProductoEgreso();
      <?php //else:?>
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      <?php //endif;?>
    }
  });

  $("#add_productoEgreso"+ind).click(function(e){
      AddProductoEgreso();
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      btnAgregarProductoEgreso = $(this);
      $(btnAgregarProductoEgreso).attr('disabled', 'disabled');
	});

  function validproductoseleccionado(id_farmacia, btn) {//Jose
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamentoTempAlta").val();
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
   filasmedicamentos++;
}

  function AddProductoEgreso() {
  var tipo_receta = $('#tipo_receta').val();
  var id_consultorio = $('#id_consultorio').val();
  var id_farmacia = $('#id_farmacia_receta_egreso').val();
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
  // var ind = filasmedicamentos
  var ind = contadorFilas++;
  var tabindex = 101;
  var nuevalperiodo = "";
  if (ind > 0) {
    $('.cmbMedicamentoTempAlta').each(function() {
      var ind_tmp = $(this).attr("ind");
      if (ind_tmp => ind) {
        //ind = Number(ind_tmp) + 1;
        tabindex += 3;
      }
    });

    fila_medicamentos = $('#cmbMedicamentoTempAlta tbody').children('tr');
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
  $('#cmbMedicamentoTempAlta option').each(function() {
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
  newRow +='<td><select class="form-control cmbMedicamentoTempAlta" id="cmbMedicamentoTempAlta' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto_alta[]" style="width: 450px">' + item_producto + '</select></td>';
	
  //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  //newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  newRow +='<td><input type="text" name="nro_stocks_alta[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
  newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo_alta[]" maxlength="3" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +'"/>';
  newRow +='<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion_alta[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';
  newRow += '<td><input type="text" name="posologia_alta[]" maxlength="150" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
    ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';
	
  <?php //endif;?>
  newRow +='<input type="hidden" name="lotes_lote_alta[]" id="lotes_lote" value=""/>';
  newRow +='<input type="hidden" name="lotes_registro_sanitario_alta[]" id="lotes_registro_sanitario" value=""/>';
  newRow +='<input type="hidden" name="lotes_fecha_vencimiento_alta[]" id="lotes_fecha_vencimiento" value=""/>';
  newRow +='<input type="hidden" name="lotes_precio_unitario_alta[]" id="lotes_precio_unitario" value=""/>';
  newRow +='<input type="hidden" name="lotes_cantidad_alta[]" id="lotes_cantidad" value=""/></td>';
	newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) +'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados_alta[]" required="" id="nro_med_solictados_alta' +ind +'" class="limpia_text nro_solicitado nro_med_solictados_alta input-sm" style="margin-left:4px; width:100px" /></td>';
  <?php //if ($modo_registro == 2 || ($modo_registro == 1 && $tipo_registro == 2)):?>
  //newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
  <?php //endif;?>
	
  // newRow += '<td><select class="form-control select-diagnostico-alta"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';  
  newRow += '<td><select class="form-control select-diagnostico-alta" id="miSelectProdAlta' + ind + '" name="miSelectProd_alta[]"></select></td>';
  // newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico-alta"  id="miSelectProdAlta' + ind + '" name="miSelectProd_alta[]"></select></td>';

  newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_productoEgreso'+ind+'"><i class="fa fa-plus"></i> </button></td>';
  newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
  newRow +='</tr>';
  $('#tableProductosAlta tbody').append(newRow);
  // $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();
  let select = $("#cmbMedicamentoTempAlta" + ind);
  select.select2({
      width: '100%',
      dropdownAutoWidth: true
  });


  const selectElementProd = document.getElementById('miSelectProdAlta' + ind);

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

  listIdDiagnosticoEgresoObjetos.forEach((item) => {
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
      AddProductoEgreso();
      <?php //else:?>
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      <?php //endif;?>
    }
  });

  $("#add_productoEgreso"+ind).click(function(e){
      AddProductoEgreso();
      validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      btnAgregarProductoEgreso = $(this);
      $(btnAgregarProductoEgreso).attr('disabled', 'disabled');
	});

  function validproductoseleccionado(id_farmacia, btn) {//Jose
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamentoTempAlta").val();
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
  filasmedicamentos++;
}

var _evitarChangeMedicamento = false;
$('#tableProductosReceta tbody').on('change', '.cmbMedicamentoTempReceta', function(e) {

  if (_evitarChangeMedicamento) return; 

  let cmb = $(this);
  let fila = cmb.closest('tr');
  let id_producto = cmb.val();
  let id_user = {{ Auth::user()->id }};
  let flagx = 0;

  let infecto = cmb.find('option:selected').attr('infecto');
  let inputTiempo = fila.find('.tiempo_periodo');
  let productoPrev = fila.attr('data-producto-prev');
  var nivel = $('#id_nivel').val();

  $('.cmbMedicamentoTempReceta').each(function() {
    if ($(this).val() == id_producto) flagx++;
  });

  if (flagx > 1) {
    bootbox.alert("El producto farmaceutico ya ha sido ingresado");
    _evitarChangeMedicamento = true;
    let fila = cmb.closest('tr');
    fila.find('input[type="text"], input[type="number"], textarea').val('');
    fila.find('select').each(function () {
      $(this).val(null).trigger('change');
    });
    fila.find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
    setTimeout(() => {
      _evitarChangeMedicamento = false;
    }, 50);
    return false;
  }

  // ================= PROA NIVEL 3 =================
  if (parseInt(nivel) === 3){

    if (infecto == 1) {
      // inputTiempo.val(3);
      // inputTiempo.prop('readonly', true);
      // inputTiempo.css('text-align', 'left');
      // inputTiempo.addClass('bloqueado-infecto');
      inputTiempo.val(3);
      inputTiempo.css('text-align', 'left');
      inputTiempo.on('input', function () {
        let valor = parseInt($(this).val()) || 0;
        if (valor > 3) {
          $(this).val(3);
        }
        if (valor < 0) {
          $(this).val(0);
        }
      });
    } else {
      inputTiempo.prop('readonly', false);
      inputTiempo.removeClass('bloqueado-infecto');
    }

    if (productoPrev && productoPrev != id_producto) {
      let infectoPrev = fila.find('.cmbMedicamentoTempReceta option[value="'+productoPrev+'"]').attr('infecto');
      let infectoActual = infecto;

      if (infectoPrev == 1 && infectoActual != 1) {
        resetInfectosDesdeFila(fila, 'total');
      }

      if (infectoPrev == 1 && infectoActual == 1) {
        fila.attr('data-auto', '0');
        fila.attr('data-interconsulta', '0');
        resetInfectosDesdeFila(fila, 'parcial');
        $('#tblInterconsultaEv tbody tr').each(function () {
          let $filaItc = $(this);
          let $btnEliminar = $filaItc.find('.deleteFila');

          let esAuto = $filaItc.attr('data-auto') === '1';
          let estaDeshabilitado = $btnEliminar.prop('disabled');

          if (esAuto || estaDeshabilitado) {
            $filaItc.remove();
          }
        });

        actualizarEstadoAddInterconsulta();

        $.ajax({
          url: '/interconsultaproa/cambiar-estado',
          type: 'POST',
          data: {
            id_emergencia: $('#id_principal').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
          }
        });

        if (esPrimerInfecto(fila)) {
          modalInfectoMostrado = true;

          let nombreMedicamento = cmb.find('option:selected').text();
          $('#nombreMedicamento').text(nombreMedicamento);

          $('#modalInfecto').data('fila', fila);
          $('#modalInfecto').modal('show');
        }
      }
    }

    if (infecto == 1 && !modalInfectoMostrado) {
      modalInfectoMostrado = true;
      let nombreMedicamento = cmb.find('option:selected').text();
      $('#nombreMedicamento').text(nombreMedicamento);
      $('#modalInfecto').data('fila', fila);
      $('#modalInfecto').modal('show');
    }
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
  // if (parseInt(nivel) === 3){
  //   if (infecto != 1) {
  //     verificarInterconsultaPROA();
  //   }
  // fila.attr('data-producto-prev', id_producto);
  // }
});

var _evitarChangeMedicamento2 = false;
$('#tableProductosAlta tbody').on('change', '.cmbMedicamentoTempAlta', function(e) {

  if (_evitarChangeMedicamento2) return; 

  let cmb = $(this);
  let fila = cmb.closest('tr');
  let id_producto = cmb.val();
  let id_user = {{ Auth::user()->id }};
  let flagx = 0;

  let infecto = cmb.find('option:selected').attr('infecto');
  let inputTiempo = fila.find('.tiempo_periodo');
  let productoPrev = fila.attr('data-producto-prev');
  var nivel = $('#id_nivel').val();

  $('.cmbMedicamentoTempAlta').each(function() {
    if ($(this).val() == id_producto) flagx++;
  });

  if (flagx > 1) {
    bootbox.alert("El producto farmaceutico ya ha sido ingresado");
    _evitarChangeMedicamento2 = true;
    let fila = cmb.closest('tr');
    fila.find('input[type="text"], input[type="number"], textarea').val('');
    fila.find('select').each(function () {
      $(this).val(null).trigger('change');
    });
    fila.find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
    setTimeout(() => {
      _evitarChangeMedicamento2 = false;
    }, 50);
    return false;
  }

  // ================= PROA NIVEL 3 =================
  if (parseInt(nivel) === 3){

    if (infecto == 1) {
      // inputTiempo.val(3);
      // inputTiempo.prop('readonly', true);
      // inputTiempo.css('text-align', 'left');
      // inputTiempo.addClass('bloqueado-infecto');
      inputTiempo.val(3);
      inputTiempo.css('text-align', 'left');
      inputTiempo.on('input', function () {
        let valor = parseInt($(this).val()) || 0;
        if (valor > 3) {
          $(this).val(3);
        }
        if (valor < 0) {
          $(this).val(0);
        }
      });
    } else {
      inputTiempo.prop('readonly', false);
      inputTiempo.removeClass('bloqueado-infecto');
    }

    if (productoPrev && productoPrev != id_producto) {
      let infectoPrev = fila.find('.cmbMedicamentoTempAlta option[value="'+productoPrev+'"]').attr('infecto');
      let infectoActual = infecto;

      if (infectoPrev == 1 && infectoActual != 1) {
        resetInfectosDesdeFila(fila, 'total');
      }

      if (infectoPrev == 1 && infectoActual == 1) {
        fila.attr('data-auto', '0');
        fila.attr('data-interconsulta', '0');
        resetInfectosDesdeFila(fila, 'parcial');
        $('#tblInterconsultaEv tbody tr').each(function () {
          let $filaItc = $(this);
          let $btnEliminar = $filaItc.find('.deleteFila');

          let esAuto = $filaItc.attr('data-auto') === '1';
          let estaDeshabilitado = $btnEliminar.prop('disabled');

          if (esAuto || estaDeshabilitado) {
            $filaItc.remove();
          }
        });

        actualizarEstadoAddInterconsulta();

        $.ajax({
          url: '/interconsultaproa/cambiar-estado',
          type: 'POST',
          data: {
            id_emergencia: $('#id_principal').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
          }
        });

        if (esPrimerInfecto(fila)) {
          modalInfectoMostrado = true;

          let nombreMedicamento = cmb.find('option:selected').text();
          $('#nombreMedicamento').text(nombreMedicamento);

          $('#modalInfecto').data('fila', fila);
          $('#modalInfecto').modal('show');
        }
      }
    }

    if (infecto == 1 && !modalInfectoMostrado) {
      modalInfectoMostrado = true;
      let nombreMedicamento = cmb.find('option:selected').text();
      $('#nombreMedicamento').text(nombreMedicamento);
      $('#modalInfecto').data('fila', fila);
      $('#modalInfecto').modal('show');
    }
  }
  // ================= AJAX =================
  let id_farmacia = $('#id_farmacia_receta_egreso').val();
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
  // if (parseInt(nivel) === 3){
  //   if (infecto != 1) {
  //    verificarInterconsultaPROA();
  //   }
  // fila.attr('data-producto-prev', id_producto);
  // }
});
  
  // $("#cmbMedicamento" + ind).on("change", function(e) {

  //   var flagx = 0;
  //   cmb = $(this);
  //   id_producto = $("#cmbMedicamento" + ind).val();
  //   id_user = {{ Auth::user()-> id }};

  //   $('.cmbMedicamento').each(function() {
  //     var ind_tmp = $(this).val();
  //     if ($(this).val() == id_producto) flagx++;
  //   });

  //   if (flagx > 1) {
  //     bootbox.alert("El producto farmaceutico ya ha sido ingresado");
  //     $("#cmbMedicamento" + ind).val("").trigger("change");
  //     return false;
  //   }
  //   id_farmacia_receta = $('#id_farmacia_receta').val();

  //   option = {
  //     url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
  //     type: 'GET',
  //     dataType: 'json',
  //     data: {}
  //   };

  //   $.ajax(option).done(function(data) {

  //     var cantidad = data.cantidad;
  //     var cantidadEstablecimiento = data.cantidadEstablecimiento;
  //     var cantidadAlmacen = data.cantidadAlmacen;
  //     $(cmb).closest("tr").find(".limpia_text").val("");
  //     //$(cmb).closest("tr").find("#nro_stocks").val(data);
  //     $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
  //     $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
  //     $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
  //     <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):
  //       ?>
  //     $(cmb).closest("tr").find(".tiempo_periodo").focus();
  //     <?php //endif;
  //       ?>
  //     $(cmb).closest("tr").find("#nro_med_solictados").val("");
  //     $(cmb).closest("tr").find("#nro_med_entregados").val("");
  //     $(cmb).closest("tr").find("#lotes_lote").val("");
  //     $(cmb).closest("tr").find("#lotes_cantidad").val("");
  //     $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
  //     $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
  //   });

  // });
  // filasmedicamentos++;

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
    url: '../obtener_antecedentes_by_id_beneficiario/'+ dni_beneficiario,
    dataType: "json",
    success: function(resultado){
      $("#antecedente").val().trim() == '' ? $("#antecedente").val(resultado.antecedente) : null;
      $("#antecedente_familiar").val().trim() == '' ? $("#antecedente_familiar").val(resultado.antecedente_familiar) : null;
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
  getProductoEgreso();
  //AddProducto();
}

getConsultorio();

const validaExamenSeleccionado = (id_examen_lab) => {
  let dni_beneficiario = $('#nrodoctitular').val();
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
  let dni_beneficiario = $('#nrodoctitular').val();
  let nombreCompleto = `${$('#paterno_beneficiario').val()} ${$('#materno_beneficiario').val()}, ${$('#nombre_beneficiario').val()}`;
  console.log({
    'dni_beneficiario' : dni_beneficiario,
    'id_examen_lab' : id_examen_lab,
  });
  $.ajax({
    url: '/consulta_ws_result_beneficiario_examen_rua/' + dni_beneficiario + '/' + id_examen_lab,
    success: function (result) {
      let res = result.ResultadosLabView;
      console.log(["result",res]);
      let numerosImpresos = new Set();
      let n = 0;
      let n2 = 0;
      let msg = "";
      if (res && Array.isArray(res) && res.length > 0) {
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
    };
    handlerErrorsController();
})
  // TAGSITEDS
    function generarCodigoSitedsEvaluacion() {
      const data = {!! json_encode($datos_evaluacion) !!}
      let nuAutorizacion = null;

      $("#codigo_siteds").prop("readonly", true); 
      Swal.fire ({
        title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
          Swal.showLoading ()
        },
      })
      
      const body = {
      "idRemitente": data.codigo_establecimiento,
      "nuRucRemitente": "", 
      "idCorrelativo": "0000001",
      "tiDoPaciente": data.id_tipo_doc_ident,
      "nuDoPaciente": data.nro_doc_ident,
      "noResponsableAut": "",
      "noPaResponsableAut": "",
      "noMaResponsableAut": "",
      "nuDoResponsableAut": "",
      "coAdmisionista": data.nro_doc_ident,
      "beMaxInicial": "0",
      "coTiMoneda": "1",
      "coPagoFijo": "0",
      "coCalServicio": "VS",
      "coPagoVariable": "100",
      "flagCaGarantia": "0",
      "deFlagCartaGarantia": "Sigesapol 1.0", 
      "tipoAtencionId": "20",
      "servicioId": data.id_servicio
    };

    


      $.ajax({
          url:'/ws-dirsapol-siteds',
          type:'POST',
          contentType: "application/json",
          dataType: "json",
          data: JSON.stringify(body),
          success: (result, textStatus, jqXHR) => {
            $("#codigo_siteds").val(result.nuAutorizacion)
            Swal.fire({
              icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
              title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
              showConfirmButton: false,
              timer: '1500'
            });
          },
          error: function(xhr, status, error){
            $("#codigo_siteds").val("");
            $("#codigo_siteds").prop("readonly", false); 
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. Por favor, inténtelo de nuevo más tarde o utilice SISTEDS a través del cliente o la web. Gracias.";
            Swal.fire({
              icon: 'error',
              title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
              html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
              confirmButtonText: 'ENTENDIDO',
            });
          }, 
          complete: function(xhr, status) {
            Swal.hideLoading();
          }
        })
    }
  
function completarConCeros(numero, longitud) {
  let numeroString = numero.toString();
  while (numeroString.length < longitud) {
    numeroString = '0' + numeroString;
  }
  return numeroString;
}

function esEntero(evt){     
  // code is the decimal ASCII representation of the pressed key.
  var code = (evt.which) ? evt.which : evt.keyCode;
  
  if(code==8) { // backspace.
    return true;
  } else if(code>=48 && code<=57) { // is a number.
    return true;
  } else{ // other keys.
    return false;
  }
}
if (parseInt("{{count(old('nombre_diagnostico'))}}") > 0) {
  const idDiagnosticos = [...document.querySelectorAll('input[name="id_diagnostico[]"]')].map((e) => {
    return e.value;
  });
  const nombreDiagnosticos = [...document.querySelectorAll('input[name="nombre_diagnostico[]"]')].map((e) => {
    return e.value;
  });
  const diagnosticosPrevios = idDiagnosticos.map((e, index) => {
    return {
      "id_diagnostico": parseInt(e),
      "codigo": nombreDiagnosticos[index].split("-")[0],
      "nombre": nombreDiagnosticos[index].split("-")[1],
      "created_at": null,
      "updated_at": null,
      "estado": null,
      "genero": null,
      "estado_fema": null
    }
  });
  diagnosticosPrevios.forEach((e) => {
    listIdDiagnosticoObjetos.push(e);
  });
  /* updateSelectElements();
  AddPreviewsDiagnosticosEnProcedimientosSelector();
  AddPreviewsDiagnosticosEnLaboratorioSelector();
  AddPreviewsDiagnosticosEnImagenSelector(); */
  selectPrevDiagnosticoEnProcedimiento();
  selectPrevDiagnosticoEnImagen();
  selectPrevDiagnosticoEnLaboratorio();
}

if ("{{old('id_cama')}}".length > 0) {
  console.log("{{old('id_cama')}}");
  $("#id_cama").val("{{old('id_cama')}}").change();
}

if ("{{old('id_prioridad')}}".length > 0) {
  $("#id_prioridad").val("{{old('id_prioridad')}}").change();
}

const apptInput = document.getElementById("appt");
if (apptInput.value == "") {
  var d = new Date();
  var n = d.toLocaleTimeString();
  apptInput.value = n;
}

if (parseInt("{{count(old('nombre_procedimiento'))}}") > 0) {
  const allInpuntsForProcedimientos = document.querySelectorAll('input[name="nombre_procedimiento[]"]');
  allInpuntsForProcedimientos.forEach((input, index) => {
    if (input.value.trim().length == 0 || $("#id_procedimiento"+index).val().trim().length == 0) {
      var nivel = $('#id_nivel').val();
      var sexo = $('#nomsexo').val();
      if (sexo == 'M')
        genero = 1;
      else
        genero = 2;
      $(`#${input.getAttribute('id')}`).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
          $.ajax({
            url: '../obtener_procedimiento_convenio_emergencia/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
            //url: '../obtener_procedimiento/' + request.term,
            dataType: "json",
            success: function(data) {
              let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
              response(filtro);
            }
          });
        },
        select: function(e, ui) {
          var id_procedimiento = ui.item.id;
          $("#id_procedimiento" + index).val(id_procedimiento);
          $("#nombre_procedimiento" + index).prop("readonly", true);
        }
      });
      $(`#${input.getAttribute('id')}`).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento" + index).val();
        if (id_procedimiento == "") {
          bootbox.alert("Falta ingresar el procedimiento en esta fila");
          $("#nombre_procedimiento" + index).val("");
          return false;
        }
      });
      $("#procedimiento_cantidad" + index).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento" + index).val();
        var cantidad = $("#procedimiento_cantidad" + index).val();
        if (id_procedimiento == "") {
          bootbox.alert("Falta ingresar la cantidad en este procedimiento");
          $("#procedimiento_cantidad" + index).val("");
          return false;
        }
        // Verifica si la cantidad es menor o igual a 0
        if (parseFloat(cantidad) <= 0) {
              bootbox.alert("Por favor, ingrese una cantidad mayor a 0");
              $("#procedimiento_cantidad" + index).val("");
              return false;
          }
      });
    }
  });
}

if (parseInt("{{count(old('nombre_diagnostico'))}}") > 0) {
  const allInpuntsForProcedimientos = document.querySelectorAll('input[name="nombre_diagnostico[]"]');
  allInpuntsForProcedimientos.forEach((input, index) => {
    if (input.value.trim().length == 0 || $("#id_diagnostico"+index).val().trim().length == 0) {
      var sexo	= $('#nomsexo').val();
      if(sexo=='M'){
        genero=1;
      }
      else{
        if(sexo=='F'){
          genero=2;
        }
      }
      $("#nombre_diagnostico"+index).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
          $.ajax({
            //url: '../obtener_diagnostico/' + request.term,
            url: '../obtener_diagnostico_genero/' + request.term + '/'+ genero,
            dataType: "json",
            success: function(data)  {
              let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
              response(filtro);
            }					
          })
      
        },select:  function(e, ui) {
          var id_diagnostico = ui.item.id;
          $("#id_diagnostico"+index).val(id_diagnostico);
          $("#nombre_diagnostico"+index).prop("readonly",true);
        }
      });
      
      $("#nombre_diagnostico"+ind).on('blur', function() {
        const diagnostico = $("#nombre_diagnostico"+ind).val();
        const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
        var id_diagnostico = $("#id_diagnostico"+ind).val();
        if(id_diagnostico == ""){
          bootbox.alert("Falta ingresar el diagnostico en esta fila");
          $("#nombre_diagnostico"+ind).val("");
          return false;
        }else{
          console.log('object');
          if (firstLetterCode == "Z") {  //|| flag_saludpol == 0) {
            bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado por SALUDPOL.");
          }
          addNewArray(id_diagnostico);
        }
      });
    }
  });
}

function selectPrevDiagnosticoEnProcedimiento() {
  const selects = document.querySelectorAll('select[name="miSelect[]"]');
  console.log(selects);
  selects?.forEach((select) => {
    const prevDiagnosticoId = select.getAttribute('prev-data');
    const selectId = select.getAttribute('id');
    $("#"+selectId).val(prevDiagnosticoId).change();
  });
}
function selectPrevDiagnosticoEnImagen() {
  const selects = document.querySelectorAll('select[name="miSelectRad[]"]');
  console.log({selects});
  selects?.forEach((select) => {
    const prevDiagnosticoId = select.getAttribute('prev-data');
    const selectId = select.getAttribute('id');
    $("#"+selectId).val(prevDiagnosticoId).change();
  });
}
function selectPrevDiagnosticoEnLaboratorio() {
  const selects = document.querySelectorAll('select[name="miSelectLab[]"]');
  console.log(selects);
  selects?.forEach((select) => {
    const prevDiagnosticoId = select.getAttribute('prev-data');
    const selectId = select.getAttribute('id');
    $("#"+selectId).val(prevDiagnosticoId).change();
  });
}

function AddPreviewsDiagnosticosEnProcedimientosSelector() {
  const cantidadDiagnosticosPrevios = parseInt("{{count(old('nombre_procedimiento'))}}");
  if (cantidadDiagnosticosPrevios > 0) {
    const selectsForDiagnosticos = document.querySelectorAll(".select-diagnostico");
    console.log(selectsForDiagnosticos);
    selectsForDiagnosticos.forEach((select) => {
      listIdDiagnosticoObjetos.forEach((item, index) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        select.appendChild(option);
      });
    });
  }
}

function AddPreviewsDiagnosticosEnLaboratorioSelector() {
  const cantidadExamenesPrevios = parseInt("{{count(old('nombre_examen_lab'))}}");
  if (cantidadExamenesPrevios > 0) {
    const selectsForDiagnosticos = document.querySelectorAll(".select-diagnostico");
    console.log(selectsForDiagnosticos);
    selectsForDiagnosticos.forEach((select) => {
      listIdDiagnosticoObjetos.forEach((item, index) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        select.appendChild(option);
      });
    });
  }
}

function AddPreviewsDiagnosticosEnImagenSelector() {
  const cantidadImagenesPrevios = parseInt("{{count(old('nombre_examen_rad'))}}");
  if (cantidadImagenesPrevios > 0) {
    const selectsForDiagnosticos = document.querySelectorAll(".select-diagnostico");
    console.log(selectsForDiagnosticos);
    selectsForDiagnosticos.forEach((select) => {
      listIdDiagnosticoObjetos.forEach((item, index) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        select.appendChild(option);
      });
    });
  }
}

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
        
        /*$.each(grupos, function (index, grupo) {
            var presentes = grupo.filter(codigo => seleccionados.includes(codigo));

            if (presentes.length === 1) { // Solo uno del grupo está presente
                var faltante = grupo.find(codigo => !seleccionados.includes(codigo));
                var nivel = $('#id_nivel').val();
                var sexo = $('#nomsexo').val();
                var id_tipo_atencion = $('#id_tipo_atencion').val();
                if (sexo == 'M')
                  genero = 1;
                else
                  genero = 2;
                $.ajax({
                        url: '../obtener_laboratorio_convenio_emergencia/' + faltante + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
                        //url: '../obtener_procedimiento/' + request.term,
                        dataType: "json",
                        success: function(data) {
                          console.log("ssss",data);
                          if(data.length == 0){

                          }else{
                            AddExamenLab();
                            var ind = $('#tblExamenLab tbody tr').length;
                            var da = {
                              item:data[0]
                            };
                            console.log(da);
                            asignarValoresSelect(da,(ind-1));
                          }
                          
                        }
                      })
            }
        });*/

        return mensajes.length > 0 ? mensajes.join("\n") : "";
    }

  $("#is_estable").on("mousedown", function () {
    // Guardamos el valor actual antes del cambio
    is_estable_auxiliar = $(this).val();
  });

  $("#is_estable").change(function() {

    // Inicio validar si hay interconsultas agregadas
    let cant_registros = $('#tblInterconsultaEv tbody tr').length;
    let has_interconsulta_agregada = false;

    if (cant_registros > 0) {
      
      let interconsulta_filas = $('#tblInterconsultaEv tbody').children('tr');
      
      for (let i = 0; i < cant_registros; i++) {
        $('.cmbInterconsultaServicios').each(function() {
          if ($(this).val() != "") {
            has_interconsulta_agregada = true;
            return false;
          }
        });
      }
    }
    // Fin validar si hay interconsultas agregadas

    if (has_interconsulta_agregada) {
      bootbox.confirm({
        message: "Al cambiar la opción de <b>Estable</b> se anulará los registros de solicitud de interconsulta ya adicionados.<br><br>¿Desea continuar?",
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
            const tbody_tblInterconsulta = document.querySelector("#tblInterconsultaEv tbody");
            tbody_tblInterconsulta.innerHTML = "";
          } else {
            $("#is_estable").val(is_estable_auxiliar);
          }
        }
      });
    }
  });

  function AddDiagnosticoEgreso() {
    var newRow = "";
    var ind = $('#tblDiagnosticoEgreso tbody tr').length;

    if (ind > 0) {
      $('.id_diagnostico_egreso').each(function() {
        var ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
          ind = Number(ind_tmp) + 1;
        }
      });

      fila_medicamentos = $('#tblDiagnosticoEgreso tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento = fila_medicamentos[idval];

    }

    var item_tipo_diagnostico = '';

    item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';

    newRow += '<tr>';
    newRow += '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_egreso[]" tabindex="8" required="" id="nombre_diagnostico_egreso' + ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control id_diagnostico_egreso typeahead tt-query" id="id_diagnostico_egreso' + ind + '" name="id_diagnostico_egreso[]" value="" data-id=' + ind + '></td>';
    newRow += '<input type="hidden" class="form-control typeahead tt-query" id="flag_saludpol_egreso' + ind + '" name="flag_saludpol_egreso[]" value="" data-id=' + ind + '></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico_egreso" id="cmbTipoDiagnosticoEgreso' + ind + '" name="id_tipo_diagnostico_egreso[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
    newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblDiagnosticoEgreso tbody').append(newRow);

    var sexo = $('#nomsexo').val();
    if (sexo == 'M') {
      genero = 1;
    } else {
      if (sexo == 'F') {
        genero = 2;
      }
    }

    $("#nombre_diagnostico_egreso" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          //url: '../obtener_diagnostico/' + request.term,
          url: '../../obtener_diagnostico_genero/' + request.term + '/' + genero,
          dataType: "json",
          success: function(data) {
            let listaDiagnosticoSeleccionado = $(".id_diagnostico_egreso").map(function() {
              return parseInt($(this).val());
            }).get();
            let filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
            response(filtro);
          }
        })
      },
      select: function(e, ui) {
        var id_diagnostico = ui.item.id;
        var flag_saludpol = ui.item.flag_saludpol;
        $("#id_diagnostico_egreso" + ind).val(id_diagnostico);
        $("#flag_saludpol_egreso" + ind).val(flag_saludpol);
        $("#nombre_diagnostico_egreso" + ind).prop("readonly", true);
      }
    });

    $("#nombre_diagnostico_egreso" + ind).on('blur', function() {
      const diagnostico = $("#nombre_diagnostico_egreso" + ind).val();
      const flag_saludpol = $("#flag_saludpol_egreso" + ind).val();
      const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
      const codigoDiagnostico = diagnostico.split("-")[0].trim();
      var id_diagnostico = $("#id_diagnostico_egreso" + ind).val();
      if (id_diagnostico == "") {
        bootbox.alert("Falta ingresar el diagnostico en esta fila");
        $("#nombre_diagnostico_egreso" + ind).val("");
        return false;
      } else {
        if (firstLetterCode == "Z" || flag_saludpol == 0) {
          bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado por SALUDPOL.");
        }
        if (codigoDiagnostico == "Y66.X") {
          bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado.");
        }
        addNewArrayEgreso(id_diagnostico);
      }
    });

  }

  // function cambiar_tipo_egreso() {
  $('#id_tipo_egreso').on('change', function() {
    // var id_tipo_egreso = $("#id_tipo_egreso").val();
    let id_tipo_egreso = $(this).val();
    let id_referencia = $('#id_referencia').val();

    if (id_tipo_egreso != "") {
      if (parseInt(id_tipo_egreso) != 3 && parseInt(id_referencia) != 0) {
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

      switch (parseInt(id_tipo_egreso)) {
        case 1: // ALTA
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

          break;

        case 2: // HOSPITALIZACIÓN  
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

          break;

        case 3: // TRASLADO
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

          break;

        case 4: // DEFUNCION  
          deshabilitarSolicitudesMedicas();

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

          break;

        case 6: // FUGA
          deshabilitarSolicitudesMedicas();

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

          break;

        case 7: // RETIRO VOLUNTARIO
          deshabilitarSolicitudesMedicas();

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
          
          break;
      }

    } else {
      $("#egreso_alta").hide();
      $("#egreso_hospitalizacion").hide();
      $("#egreso_defuncion").hide();
      $("#id_condicion_alta").val('');
      $("#egreso_traslado").hide();
      $("#alta_condicion").hide();
      $("#alta_condicion_tra").hide('');
      $("#alta_condicion_fuga").hide('');
      $("#alta_condicion_de").hide('');
      $("#alta_condicion_ret").hide('');
      $("#egreso_retiro").hide();
      $("#egreso_fuga").hide();
    }
  });

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

  $('#horas, #minutos').on('input', function() {
    const horas = parseInt($('#horas').val()) || 0;
    const minutos = parseInt($('#minutos').val()) || 0;
    const total = horas * 60 + minutos;
    $('#duracion_cirugia').val(total);
  });

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
  
  // FIN QUIRURGICO

  async function buscarDiaEstanciaAjax(fecha_egreso, hora_egreso) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "{{ route('buscar_dia_estancia_ajax') }}",
        type: "GET",
        dataType: "JSON",
        data: {
          id_asegurado: "{{$datos_evaluacion->id_asegurado}}",
          fecha_egreso: fecha_egreso,
          hora_egreso: hora_egreso
        },
        success: function(result) {
          resolve(result);
        },
        error: function(xhr, status, error) {
          reject(error);
        }
      });
    })
  }

  $('#tblDiagnosticoEgreso tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    var inputVal = obj.parent().siblings().find('input').val();
    let posicion = inputVal.indexOf("-");
    let codigo = inputVal.substring(0, posicion).trim();

    listIdDiagnosticoEgresoObjetos = listIdDiagnosticoEgresoObjetos.filter(
      (item) => item.codigo.trim() !== codigo
    );
    updateSelectElementsEgreso();
    obj.parent().parent().remove();
  });

  function deshabilitarSolicitudesMedicas() {
    let toggleButtonRecetaVale = document.getElementById('addRecetaVale');
    let toggleButtonOrdenLab = document.getElementById('addExamenLab');
    let toggleButtonOrdenRad = document.getElementById('addExamenRad');
    let toggleButtonInterconsulta = document.getElementById('addInterconsulta');

    let addRecetaVale = toggleButtonRecetaVale.getAttribute("aria-pressed");
    let addExamenLab = toggleButtonOrdenLab.getAttribute("aria-pressed");
    let addExamenRad = toggleButtonOrdenRad.getAttribute("aria-pressed");
    let addInterconsulta = toggleButtonInterconsulta.getAttribute("aria-pressed");

    let hasDocumento = false;

    hasDocumento = addRecetaVale == "true";

    if (!hasDocumento) {
      hasDocumento = addExamenLab == "true";
    }

    if (!hasDocumento) {
      hasDocumento = addExamenRad == "true";
    }

    if (!hasDocumento) {
      hasDocumento = addInterconsulta == "true";
    }

    if (hasDocumento) {
      
      bootbox.confirm({
        message: "Al seleccionar este Tipo de Egreso, se deshabilitará las Solicitudes de Orden de Laboratorio, Orden de Imágenes, Recetas e Interconsultas</br></br>" + "<b>¿Desea continuar?</b>",
        buttons: {
          confirm: {
            label: 'Si',
            className: 'btn-success'
          },
          cancel: {
            label: 'No',
            className: 'btn-danger'
          }
        },
        callback: function (result) {
          if (result==true) {

            if (addRecetaVale == "true") {
              toggleButtonRecetaVale.classList.remove("active");
              toggleButtonRecetaVale.setAttribute('aria-pressed', 'false');
              $("#fsRecetaVale").hide();
            }

            if (addExamenLab == "true") {
              toggleButtonOrdenLab.classList.remove("active");
              toggleButtonOrdenLab.setAttribute('aria-pressed', 'false');
              $("#fsExamenLab").hide();
            }
            
            if (addExamenRad == "true") {
              toggleButtonOrdenRad.classList.remove("active");
              toggleButtonOrdenRad.setAttribute('aria-pressed', 'false');
              $("#fsExamenRad").hide();
            }
            
            if (addInterconsulta == "true") {
              toggleButtonInterconsulta.classList.remove("active");
              toggleButtonInterconsulta.setAttribute('aria-pressed', 'false');
              $("#fsInterconsulta").hide();
            }

          } else {
            $('#id_tipo_egreso').val("");

            $("#egreso_alta").hide();
            $("#egreso_hospitalizacion").hide();
            $("#egreso_defuncion").hide();
            $("#id_condicion_alta").val('');
            $("#egreso_traslado").hide();
            $("#alta_condicion").hide();
            $("#alta_condicion_tra").hide('');
            $("#alta_condicion_fuga").hide('');
            $("#alta_condicion_de").hide('');
            $("#alta_condicion_ret").hide('');
            $("#egreso_retiro").hide();
            $("#egreso_fuga").hide();
          }
        }
      });

    }
  }


////////////////////////////////////////////////////////////////////////// INTERCONSULTA PROA NIVEL 3
if (nivel === 3){
  
  $(document).ready(function () {
    
    $('#fecha_probable_pq').datepicker({
      dateFormat: 'dd-mm-yy',
      autoclose: true,
      changeMonth: true,
      changeYear: true,
      minDate: 0
    });
  });
}
//////////////////////////////////////////////////////////////////////////////////////////////////
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

  /*===============Estilos de botón Cambiar estado de Orden INICIO======================*/
  .custom-header-cambiar-estado {
      background-color: #3c763d !important;
      border-color: #2e5c2f !important;
  }
  .custom-header-cambiar-estado-edit {
      background-color: #f0ad4e !important;
      border-color: #eea236 !important;
  }
  /*===============Estilos de botón Cambiar estado de Orden FIN======================*/
  input[type=checkbox]{
    /* Doble-tamaño Checkboxes */
    -ms-transform: scale(2); /* IE */
    -moz-transform: scale(2); /* FF */
    -webkit-transform: scale(2); /* Safari y Chrome */
    -o-transform: scale(2); /* Opera */
    padding: 10px;
  }
  .size-titulo {
      font-size: 1.2rem;
      font-weight: 700;
      text-align: center;
  }
  .formatearDiv1{
    padding: 0;
    margin-bottom: 0.5rem;
  }
  .formatearDiv1 label{
    padding: 0;
    margin: 0;
    height: 3rem;
    align-content: center;
  }
  .error-mensaje {
    font-size: 1.2rem;
    color: red;
  }
  .contenedor-error-mensaje {
    display: flex;
    justify-content: center;
    background: rgb(255, 231, 231);
    border: red;
  }
  .contenedor-colum {
    display: flex; 
    flex-direction: column; 
    gap: 1rem;
  }
  .contenedor-modal-dialog {
    width: 1400px !important;
  }

  .swal2-popup,
  .swal2-header {
    padding: 1.25em !important;
  }

@media (max-width: 1207px) {
    .panel-body {
        overflow-x: auto;
        display: block;
    }
}
/*===============TABLA IMAGENOLOGÍA RESPONSIVE FIN=====================================*/ 
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

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>
<div class="loader"></div>
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
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab220default" data-toggle="tab" style="font-size:12px">NUEVA ATENCION</a></li>
            <li><a href="#tab10default" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES - AMBULATORIO</a></li>
            <li><a href="#tab11default" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES - EMERGENCIA</a></li>
            <li><a href="#tab12default" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES - HOSPITALIZACION</a></li>
          </ul>
        </div>
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active" id="tab220default">
              <fieldset>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-12 col-md-12"> 
                      <div class="row">
                        <div class="panel panel-primary filterable">
                          <div class="panel-heading">
                            <h3 class="panel-title">MÓDULO DE EVALUACION MEDICAS</h3>
                          </div>
                          <div class="panel-body">
                            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->
                            <form class="form-horizontal" method="post" action="{{ route('registrar_evaluacion_emergencia')}}"
                              id="frmPrestacion" autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="tiene_receta" id="tiene_receta" @if(old('tiene_receta') == '1') value='true' @else value='' @endif>
                              <input type="hidden" name="id_nivel" id="id_nivel" value="{{$nivel}}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                              <input type="hidden" name="id_principal" id="id_principal" value="{{$id_principal}}">
                              <input type="hidden" name="estado" id="estado" value="{{$estado}}">
                              <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$datos_evaluacion->id_establecimiento}}">
                              <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="{{$id_tipo_atencion}}">
                              <input type="hidden" name="id_asegurado" id="id_asegurado"  value="{{$datos_evaluacion->id_asegurado}}">
                              <input type="hidden" name="id_servicio" id="id_servicio" value="{{$datos_evaluacion->id_servicio}}">
                              <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio" value="{{$datos_evaluacion->id_sub_consultorio}}">
                              <input type="hidden" name="id_medico" id="id_medico" value="{{$datos_evaluacion->id_medico}}">
                              <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$id_prestacion}}">
                              <input type="hidden" name="edadpaciente" id="edadpaciente" value="{{$datos_evaluacion->edadpaciente}}">
                              <input type="hidden" name="id_emergencia" id="id_emergencia" value="{{$id_emergencia}}">
                              <input type="hidden" name="turno" id="turno" value="{{$turno}}">
                              <input type="hidden" name="nivel_ipress" id="nivel_ipress" value="{{ $establecimiento[0]->nivel }}">
                              <input type="hidden" value="{{$datos_evaluacion->nro_doc_ident}}" name="dni_beneficiario" id="dni_beneficiario" class="form-control">
                              <input type="hidden" name="nro_historia" id="nro_historia" value="{{$datos_evaluacion->nro_historia}}">
                              <input type="hidden" name="codigo_upss" id="codigo_upss" value="{{$codigo_upss}}">
                              <input type="hidden" name="interconsulta_activo" id="interconsulta_activo" @if(old('interconsulta_activo') == '1') value='true' @else value='' @endif>
                              <input type="hidden" name="laboratorio_activo" id="laboratorio_activo" @if(old('laboratorio_activo') == '1') value='true' @else value='' @endif>
                              <input type="hidden" name="imagenologia_activo" id="imagenologia_activo" @if(old('imagenologia_activo') == '1') value='true' @else value='' @endif>
			                        <input type="hidden" value="{{$datos_evaluacion->id_prioridad}}" name="id_prioridad_actual" id="id_prioridad_actual">
                              <input type="hidden" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_atencion" id="fecha_atencion">
                              <input type="hidden" value="{{ date('H:i:s', strtotime($horaServidor)) }}" name="hora_atencion" id="hora_atencion">
                              <input type="hidden" name="signos_vitales_evaluacion_emergencia" id="signos_vitales_evaluacion_emergencia" value="{{old('signos_vitales_evaluacion_emergencia')}}">
                              <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
                              <input type="hidden" id="flag_registro_alta" name="flag_registro_alta" value="0" />
                              <input type="hidden" value="{{$datos_evaluacion->asegurado_paterno}}" name="paterno_beneficiario" id="paterno_beneficiario">
                              <input type="hidden" value="{{$datos_evaluacion->asegurado_materno}}" name="materno_beneficiario" id="materno_beneficiario">
                              <input type="hidden" value="{{$datos_evaluacion->asegurado_nombre}}" name="nombre_beneficiario" id="nombre_beneficiario">
                              <input type="hidden" value="{{$datos_evaluacion->parentesco}}" name="tipo_beneficiario" id="tipo_beneficiario">
                              <input type="hidden" value="{{$datos_evaluacion->fecha_nac}}" name="fecnacafiliado" id="fecnacafiliado">
                              <input type="hidden" id="id_rua_imagenes" name="id_rua_imagenes" value="">
                              <fieldset> 
                                <?php 
                                  switch ($id_tipo_atencione) {
                                    case '1': //AMBULATORIO
                                      $descripcion='SERVICIO'; $descripcion2='CONSULTORIO'; $atencion='AMBULATORIO';break;
                                    case '2': //EMERGENCIA
                                      $descripcion='PRIORIDAD'; $descripcion2='TOPICO';$atencion='EMERGENCIA';break;
                                    case '3': //HOSPITALIZACION
                                      $descripcion='SALA'; $descripcion2='SERVICIO';$atencion='HOSPITALIZACION';break;
                                  }
                                ?>
                                <div class="panel panel-info panel-bordered panel-sm panel-body">
                                  <div class="row">
                                    <!-- ===================== DATOS DEL PACIENTE ===================== -->
                                    <div class="col-md-6">
                                      <div class="panel panel-info" style="min-height:320px;">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-3">
                                            
                                          </div>
                                        </div>
                                          <div class="panel-body" style="padding-bottom: 34px;">
                                            <table class="table table-borderless" style="width:100%;margin-bottom: 0px!important;" >
                                              <?php 
                                                $doc   = null;
                                                $nom   = null;
                                                $fecha_nac = null;
                                                $sexo = null;
                                              
                                                if($datos_evaluacion->parentesco=='TITULAR'){
                                                  $doc   = $datos_evaluacion->tipo_doc_ident_titular.' - '.$datos_evaluacion->nro_doc_ident_titular;
                                                  $nom   = $datos_evaluacion->paterno_titular.' '.$datos_evaluacion->materno_titular.', '.$datos_evaluacion->nombre_titular;
                                                  $fecha_nac = $datos_evaluacion->fecha_nac_titular;
                                                  $sexo = $datos_evaluacion->sexo_titular;

                                                }else{
                                                  $doc   = $datos_evaluacion->tipo_doc_ident.' - '.$datos_evaluacion->nro_doc_ident;
                                                  $nom   = $datos_evaluacion->asegurado_paterno.' '.$datos_evaluacion->asegurado_materno.', '.$datos_evaluacion->asegurado_nombre;
                                                  $fecha_nac = $datos_evaluacion->fecha_nac;
                                                  $sexo = $datos_evaluacion->sexo;
                                                }
                                                // Calcular edad
                                                $fecha_formato = DateTime::createFromFormat('d/m/Y', $fecha_nac);
                                                $hoy = new DateTime();
                                                $edad_texto = '';

                                                if ($fecha_formato) {
                                                    $edad = $fecha_formato->diff($hoy);
                                                    $edad_texto = $edad->y.' AÑOS, '.$edad->m.' MESES, '.$edad->d.' DÍAS';
                                                }
                                              ?>  
                                                <tr>
                                                    <th colspan="3" style="padding-bottom:8px !important; color:#000" ><strong>DATOS DEL PACIENTE:</strong></th>
                                                </tr>

                                                <tr>
                                                    <th style="width:45%;">TIPO Y N° DE DOC</th>
                                                    <th style="width:5%;">:</th>
                                                    <td><?php echo $doc ?></td>
                                                </tr>

                                                <tr>
                                                    <th>APELLIDOS Y NOMBRES</th>
                                                    <th>:</th>
                                                    <td style="text-transform:uppercase;">
                                                        <?php  echo $nom; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>FECHA DE NACIMIENTO</th>
                                                    <th>:</th>
                                                    <td><?php echo $fecha_nac;?></td>
                                                </tr>

                                                <tr>
                                                    <th>EDAD / SEXO</th>
                                                    <th>:</th>
                                                    <td><?php echo $edad_texto;?>- <?php 
                                                        if($sexo === 'M'){
                                                          echo "MASCULINO";
                                                        }else{
                                                          echo "FEMENINO";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>TIPO BENEFICIARIO</th>
                                                    <th>:</th>
                                                    <td><?php echo $datos_evaluacion->parentesco;?></td>
                                                </tr>

                                                <tr>
                                                    <th>N° DE HISTORIA CLÍNICA</th>
                                                    <th>:</th>
                                                    <td><?php echo $datos_evaluacion->nro_historia;?></td>
                                                </tr>
                                                <?php 
                                                  $doc   = null;
                                                  $cip   = null;
                                                  $nom   = null;
                                                  $grado = null;
                                                
                                                  if($datos_evaluacion->parentesco=='TITULAR'){
                                                    $doc   = $datos_evaluacion->nro_doc_ident_titular;
                                                    $cip   = $datos_evaluacion->cip_titular;
                                                    $nom   = $datos_evaluacion->paterno_titular.' '.$datos_evaluacion->materno_titular.', '.$datos_evaluacion->nombre_titular;
                                                    $grado = $datos_evaluacion->grado_titular;
                                                  }elseif($datos_evaluacion->situacion_paciente!='CIVIL' && $flag_no_encontrado==0){
                                                    $doc   = $dato_titular[0]->nro_doc_ident;
                                                    $cip   = $dato_titular[0]->cip;
                                                    $nom   = $dato_titular[0]->paterno.' '.$dato_titular[0]->materno.', '.$dato_titular[0]->nombre;
                                                    $grado = $dato_titular[0]->grado;
                                                  }else{
                                                    $doc   = 'CIVIL';
                                                    $cip   = 'CIVIL';
                                                    $nom   = 'CIVIL';
                                                    $grado = 'CIVIL';
                                                  }
                                                ?> 
                                                <tr>
                                                    <th colspan="3" style="padding-bottom:8px !important; padding-top:8px !important; color:#000"><strong>DATOS DEL TITULAR:</strong></th>
                                                </tr>
                                                <tr>
                                                    <th>N° DE DOC</th>
                                                    <th>:</th>
                                                    <td><?php echo $datos_evaluacion->nro_doc_ident; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>N° DE CIP</th>
                                                    <th>:</th>
                                                    <td><?php echo $datos_evaluacion->cip;?></td>
                                                </tr>

                                                <tr>
                                                    <th>APELLIDOS Y NOMBRES</th>
                                                    <th>:</th>
                                                    <td><?php echo $datos_evaluacion->paterno_titular.' '.$datos_evaluacion->materno_titular.', '.$datos_evaluacion->nombre_titular; ?></td>
                                                </tr>

                                                <tr>
                                                    <th>GRADO</th>
                                                    <th>:</th>
                                                    <td><?php echo $datos_evaluacion->grado;?></td>
                                                </tr>
                                                <?php 
                                                  $parentesco   = null;
                                                  if($tipo_parentesco !='SOLO'){
                                                    $parentesco   = $cita->nombre_conducido."- (".$tipo_parentesco." )";
                                                  }else{
                                                    $parentesco   = 'VIENE SOLO';
                                                  }
                                                ?> 
                                                <tr>
                                                    <th style="color:#000"><strong>CONDUCIDO POR</strong></th>
                                                    <th style="color:#000">:</th>
                                                    <td><?php echo $parentesco; ?></td>
                                                </tr>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                      <!-- ===================== DATOS DE LA ATENCIÓN ===================== -->
                                      <div class="col-md-6">
                                          <div class="panel panel-info" style="min-height:320px;">
                                              <div class="panel-heading">
                                                  <h3 class="panel-title size-titulo">DATOS DE LA ATENCIÓN</h3>
                                              </div>
                                              <div class="panel-body">
                                                  <table class="table table-borderless" style="width:100%;margin-bottom: 0px;">
                                                      <tr>
                                                          <th>COD. SITEDS SUSALUD</th>
                                                          <th>:</th>
                                                          <td><div class="{{intval($datos_evaluacion->id_tipo_beneficiario) != 13 ? 'input-group' : ''}}" style="margin-top:-7px"><input type="text" class="form-control" id="codigo_siteds" name="codigo_siteds" onkeypress="return esEntero(event);" style="text-transform: uppercase;border: none !important;background-color: white; font-size:12px;color: #6b7381;padding: 0px 2px;" maxlength="12" pattern="[0-9]*" <?php if (intval($datos_evaluacion->id_tipo_beneficiario) !== 13) echo 'readonly' ?>/></div>
                                                          </td>
                                                      </tr>
                                                      <tr>
                                                          <th>FECHA DE ATENCIÓN</th>
                                                          <th>:</th>
                                                          <td><?php echo date("d/m/Y", strtotime($fechaServidor)); ?></td>
                                                      </tr>

                                                      <tr>
                                                          <th>PRIORIDAD</th>
                                                          <th>:</th>
                                                          <td><?php echo $datos_evaluacion->nom_prioridad; ?></td>
                                                      </tr>
                                                      <tr>
                                                          <th>AREA</th>
                                                          <th>:</th>
                                                          <td><?php echo $datos_evaluacion->consultorio?></td>
                                                      </tr>

                                                      <tr>
                                                          <th>CÓDIGO UPSS</th>
                                                          <th>:</th>
                                                          <td><?php echo $codigo_upss;?></td>
                                                      </tr>
                                                      <?php 
                                                        $pab= null;
                                                        $pis=null;
                                                        $hab=null;
                                                        $cm=null;
                                                        if($datos_evaluacion->ubica_prioridad<4){
                                                          $pab= $dato_cama->pabellon;
                                                          $pis=$dato_cama->piso;
                                                          $hab=$dato_cama->cuarto;
                                                          $cm=$dato_cama->cama;
                                                        }else{
                                                          $pab= " - ";
                                                          $pis=" - ";
                                                          $hab=" - ";
                                                          $cm=" - ";
                                                        }
                                                      ?>
                                                      <tr>
                                                          <th>PABELLON</th>
                                                          <th>:</th>
                                                          <td><?php echo $pab?></td>
                                                      </tr>

                                                      <tr>
                                                          <th>PISO</th>
                                                          <th>:</th>
                                                          <td><?php echo $pis?></td>
                                                      </tr>

                                                      <tr>
                                                          <th>HABITACIÓN</th>
                                                          <th>:</th>
                                                          <td><?php echo $hab?></td>
                                                      </tr>

                                                      <tr>
                                                          <th>CAMA/CAMILLA</th>
                                                          <th>:</th>
                                                          <td><?php echo $cm?></td>
                                                      </tr>

                                                      <tr>
                                                          <th colspan="3" style="padding-bottom:8px !important; padding-top:8px !important; color:#000"><strong>RESPONSABLE DE ATENCIÓN: </strong></th>
                                                      </tr>
                                                      <tr>
                                                          <th>TIPO Y N° DE DOC</th>
                                                          <th>:</th>
                                                          <td><?php echo $datos_evaluacion->tipo_doc_ident ?> - <?php echo $datos_evaluacion->nro_dni_medico ?></td>
                                                      </tr>

                                                      <tr>
                                                          <th>APELLIDOS Y NOMBRES</th>
                                                          <th>:</th>
                                                          <td><?php echo $datos_evaluacion->medico_paterno.' '.$datos_evaluacion->medico_materno.', '.$datos_evaluacion->medico_nombre;?></td>
                                                      </tr>

                                                      <tr>
                                                          <th>PROFESION/ESPECIALIDAD</th>
                                                          <th>:</th>
                                                          <td><?php echo $datos_evaluacion->profesion_medico." / ".$datos_evaluacion->especializacion_medico ?></td>
                                                      </tr>

                                                      <tr>
                                                          <th>N° DE COLEGIATURA/N° DE REG. DE ESPECIALIDAD</th>
                                                          <th>:</th>
                                                          <td><?php echo $datos_evaluacion->colegiatura_medico." / ".$datos_evaluacion->rne_medico; ?></td>
                                                      </tr>

                                                  </table>
                                                  <input type="hidden" class="form-control" value="{{$datos_evaluacion->tipo_doc_ident}}" name="tipodoc_beneficiario" id="tipodoc_beneficiario" style="text-align:left">
                                                  <input type="hidden" class="form-control" value="{{$datos_evaluacion->nro_doc_ident}}" name="nrodocafiliado" id="nrodocafiliado" readonly="" />
                                                  <input type="hidden" class="form-control" value="{{$datos_evaluacion->sexo}}" name="nomsexo" id="nomsexo" />
                                                  @if($datos_evaluacion->parentesco=='TITULAR')
                                                    <input type="hidden" class="form-control" value="{{$datos_evaluacion->tipo_doc_ident}}" name="tipodoc_titular"
                                                        id="tipodoc_titular" />
                                                    <input type="hidden" class="form-control" value="{{$datos_evaluacion->nro_doc_ident}}" name="nrodoctitular"
                                                        id="nrodoctitular" />
                                                    <input type="hidden" class="form-control" value="{{$datos_evaluacion->nombre_titular}}" name="nombre_titular"
                                                        id="nombre_titular" />
                                                      <input type="hidden" class="form-control" value="{{$datos_evaluacion->paterno_titular}}" name="paterno_titular"
                                                        id="paterno_titular" />
                                                      <input type="hidden" class="form-control" value="{{$datos_evaluacion->materno_titular}}" name="materno_titular"
                                                        id="materno_titular" />
                                                  @else
                                                    @if (!empty($dato_titular))
                                                        <input type="hidden" class="form-control" value="{{$dato_titular[0]->tipo_doc_ident}}" name="tipodoc_titular" id="tipodoc_titular" style="text-align:left">
                                                        <input type="hidden" class="form-control" value="{{$dato_titular[0]->nro_doc_ident}}" name="nrodoctitular" id="nrodoctitular" style="text-align:left">
                                                        <input type="hidden" class="form-control" value="{{$dato_titular[0]->nombre}}" name="nombre_titular" id="nombre_titular" style="text-align:left">
                                                        <input type="hidden" class="form-control" value="{{$dato_titular[0]->paterno}}" name="paterno_titular" id="paterno_titular" style="text-align:left">
                                                        <input type="hidden" class="form-control" value="{{$dato_titular[0]->materno}}" name="materno_titular" id="materno_titular" style="text-align:left">
                                                        <input type="hidden" class="form-control" value="{{$dato_titular[0]->nombre}}" name="nombre_titular" id="nombre_titular" style="text-align:left">


                                                        @endif
                                                  @endif
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                  <div> 
                              </div>

                              <?php if(isset($verificar_existe->id_referencia) && $verificar_existe->id_referencia != 0):?>     
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
                                                <th width="25%">Establecimiento Origen</th>
                                                <th width="20%">Servicio Origen</th>
                                                <th width="20%">Motivo de Referencia</th>
                                                <th width="15%">Fecha de Referencia</th>
                                                <th width="20%">Medico de Referencia</th>
                                              </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                              <tr>
                                                <td><?php echo $referencias->nombre_establecimiento?></td>
                                                <td><?php echo $referencias->nombre_servicio?></td>
                                                <td><?php echo $referencias->observacion_referencia?></td>
                                                <td><?php echo  date("Y-m-d", strtotime($referencias->fecha_atencion)) ?></td>
                                                <td><?php echo $nombre_medico_referencia?></td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>

                                        <div class="panel-body">
                                          <table class="displa" id="tblDiagnostico11" cellspacing="0" width="100%" style="margin-top:5px;">
                                            <thead>
                                              <tr>
                                                <th width="20%">Codigo del Diagnostico de Referencia</th>
                                                <th width="60%">Nombre del Diagnostico de Referencia</th>
                                                <th width="20%">Tipo del Diagnostico de Referencia</th>
                                              </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                              <?php foreach($diagnostico_prestacion_referencia as $row):
                                                $tipo_diagnostico = "";
                                                if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
                                                if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
                                                if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";
                                              ?>
                                              <tr>
                                                <td><?php echo $row->codigo?></td>
                                                <td><?php echo $row->nombre?></td>
                                                <td><?php echo $tipo_diagnostico?></td>
                                              </tr>
                                              <?php endforeach;?>
                                            </tbody>
                                          </table>
                                        </div>

                                      
                                        <div class="panel-body">
                                          <table class="display" id="tblProcedimiento1" cellspacing="0" width="100%" style="margin-top:5px;">
                                            <thead>
                                              <tr>
                                                <th width="20%">Codigo del procedimiento de Referencia</th>
                                                <th width="60%">Nombre del procedimiento de Referencia</th>
                                                <th width="20%">Observacion del procedimiento de Referencia</th>
                                              </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                              <?php foreach($procedimientos_refencia as $row):?>
                                              <tr>
                                                <td><?php echo $row->codigo?></td>
                                                <td><?php echo $row->descripcion?></td>
                                                <td><?php echo $row->observacion?></td>
                                              </tr>
                                              <?php endforeach;?>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </fieldset>
                                <?php endif; ?>

                              <div class="panel with-nav-tabs panel-default">
                                <div class="panel-heading">
                                  <ul class="nav nav-tabs">
                                    <li   class="active"><a href="#tab300default" data-toggle="tab" style="font-size:12px">EVALUACIONES MEDICAS</a></li>
                                    <li><a href="#tab305default" data-toggle="tab" style="font-size:12px">ATENCIONES MEDICAS</a></li>
                                    <li><a href="#tab303default" data-toggle="tab" style="font-size:12px">EXAMENES DE LABORATORIO</a></li>
                                    <li><a href="#tab304default" data-toggle="tab" style="font-size:12px">EXAMENES DE IMAGENOLOGIA</a></li>
                                    <li><a href="#tab1007default" data-toggle="tab" style="font-size:12px">HISTORIAL DE INTERCONSULTA</a></li>
                                  </ul>
                                </div>
                                <div class="panel-body">
                                  <div class="tab-content">
                                    <div class="tab-pane fade in active" id="tab300default">
                                      <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>HISTORIAL DE EVALUACION MEDICA</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <div id='divEvaluacione'></div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </fieldset>
                                    </div>
                                    <div class="tab-pane fade" id="tab305default">
                                      <fieldset>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>ATENCION MEDICA</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <div id='divHistorial'></div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </fieldset>
                                    </div>
                                    <div class="tab-pane fade" id="tab303default">
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
                                    <div class="tab-pane fade" id="tab304default">
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
                                    <div class="tab-pane fade" id="tab1007default">
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
                                  </div>
                                </div>
                              </div>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>ANTECEDENTES</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label>ANTECEDENTES</label>
                                      <textarea class="form-control" rows="7" id="antecedente" name="antecedente"
                                        placeholder="Antecedentes personales">{{old('antecedente')}}</textarea>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label>ANTECEDENTE FAMILIARES</label>
                                      <textarea class="form-control" rows="7" id="antecedente_familiar" name="antecedente_familiar"
                                        placeholder="Antecedente Familiar">{{old('antecedente_familiar')}}</textarea>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>ANAMNESIS</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label>MOTIVO DE VISITA</label>
                                      <textarea class="form-control" rows="7" id="motivo_visita" name="motivo_visita"
                                        placeholder="Relato m&eacute;dico del motivo de la visita" >{{old('motivo_visita')}}</textarea>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label>TIEMPO DE ENFERMEDAD / RELATO CRONOLOGICO</label>
                                      <textarea class="form-control" rows="7" id="tiempo_enfermedad" name="tiempo_enfermedad"
                                        placeholder="Relato m&eacute;dico del tiempo de enfermedad" >{{old('tiempo_enfermedad')}}</textarea>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label>EXAMENES AUXILIARES</label>
                                      <textarea class="form-control" rows="7" id="examen_auxiliar" name="examen_auxiliar" placeholder="Examenes auxiliares" >{{old('examen_auxiliar')}}</textarea>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>EXAMEN FISICO</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="VerSignosVitales">
                                    <div class="form-group">
                                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                        <label>SIGNOS VITALES</label>
                                      </div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                        <button tabindex="62" type="button" id="addSignosVitales" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                          <div class="handle"></div>
                                        </button>
                                      </div>
                                    </div>
                                    <div class="panel panel-info panel-bordered panel-sm" id="fsSignosVitales" style="display:none">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>INFORMACIÓN DE SIGNOS VITALES</b>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>TEMPERATURA (°C) &nbsp;</label>
                                            <input tabindex="43" placeholder="00.00" value="@if(old('temperatura') != null) {{old('temperatura')}} @elseif(isset($prestacion)) {{$prestacion->temperatura}} @endif" type="text" name="temperatura" id="temperatura" class="form-control" tabindex="23" onkeypress="return esNumero(event,this.id)" maxlength="5" />
                                          </div>
                                          <div class="col-md-2">
                                            <label>TALLA (cm) &nbsp;</label>
                                            <input tabindex="44" value="@if(old('talla') != null) {{old('talla')}} @elseif(isset($prestacion)) {{$prestacion->talla_cm}} @endif" type="text" name="talla" id="talla" class="form-control" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>PESO (Kg) &nbsp;</label>
                                            <input tabindex="45" placeholder="00.00" value="@if(old('peso') != null) {{old('peso')}} @elseif(isset($prestacion)) {{$prestacion->peso_kg}} @endif" type="text" name="peso" id="peso" class="form-control" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="6" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>IMC &nbsp;</label>
                                            <input value="@if(old('imc') != null) {{old('imc')}} @elseif(isset($prestacion))  @endif" type="text" name="imc" id="imc" class="form-control" readonly="" tabindex="46" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>PESO IDEAL &nbsp;</label>
                                            <input value="@if(old('peso_x_altura') != null) {{old('peso_x_altura')}} @elseif(isset($prestacion)) @endif" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control" readonly="" max="0" tabindex="47" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>Sa02 (%) &nbsp;</label>
                                            <input value="@if(old('saturacion') != null) {{old('saturacion')}} @elseif(isset($prestacion)) {{$prestacion->sa02_porcentaje}} @endif" type="text" name="saturacion" id="saturacion" class="form-control" tabindex="48" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                            <label>FREC. CARDIACA FETAL(/min)</label>
                                            <input tabindex="49" value="@if(isset($prestacion)){{$prestacion->frecuencia_cardiaca_fetal}}@endif" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>PRESIÓN ARTERIAL &nbsp;</label>
                                            <div class="row">
                                              <div class="col-xs-5">                          
                                                <input value="@if(old('presion_arterial_x') != null) {{old('presion_arterial_x')}} @elseif(isset($prestacion)) {{$prestacion->presion_arterial_x}} @endif" tabindex="50" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>  
                                                <div class="col-xs-2">
                                                  <label>/</label> 
                                                </div>
                                              <div class="col-xs-5">
                                                <input value="@if(old('presion_arterial_y') != null) {{old('presion_arterial_y')}} @elseif(isset($prestacion)) {{$prestacion->presion_arterial_y}} @endif" type="text" tabindex="51" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>  
                                            </div>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC.RESPIRATORIA(/min)</label>
                                            <input value="@if(old('frecuencia_respiratoria') != null) {{old('frecuencia_respiratoria')}} @elseif(isset($prestacion)) {{$prestacion->frecuencia_respiratoria}} @endif" type="text" name="frecuencia_respiratoria" tabindex="52" id="frecuencia_respiratoria" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. CARDIACA (/min) &nbsp;</label>
                                            <input value="@if(old('frec_cardiaca') != null) {{old('frec_cardiaca')}} @elseif(isset($prestacion)) {{$prestacion->frecuencia_cardiaca}} @endif" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="53" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>Fi02 (%) &nbsp;</label>
                                            <input value="@if(old('oxigenacion') != null) {{old('oxigenacion')}} @elseif(isset($prestacion)) {{$prestacion->fi02}} @endif" type="text" name="oxigenacion" id="oxigenacion" tabindex="54" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label>EXAMEN FISICO</label>
                                        <textarea class="form-control" rows="7" id="examen_fisico" name="examen_fisico"
                                          placeholder="Examen Físico">{{old('examen_fisico')}}</textarea>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label>EXAMEN CLINICO</label>
                                        <textarea class="form-control" rows="7" id="examen_clinico" name="examen_clinico"
                                          placeholder="Examen Clínico">{{old('examen_clinico')}}</textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>TRATAMIENTO Y/O TERAPEUTICA</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label>TRATAMIENTO AUXILIAR</label>
                                        <textarea class="form-control" rows="7" id="tratamiento_auxiliar" name="tratamiento_auxiliar"
                                          placeholder="Tratamiento auxiliar">{{old('tratamiento_auxiliar')}}</textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm" @if(empty($gestacion) && empty($puerperio)) style="display: none;" @endif>
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>SALUD MATERNA</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    @if (!empty($gestacion))
                                    <div class="form-group">
                                      <div class="panel-title text-center">
                                        <b>GESTANTES</b>
                                      </div>
                                      <div class="col-md-3">
                                        <label for="tipo">Última menstruación</label>
                                        <p>{{ $gestacion->fech_ult_regla }}</p>
                                      </div>
                                      <div class="col-md-3">
                                        <label>Fecha probable de parto</label>
                                        <p>{{ $gestacion->fech_prob_parto }}</p>
                                      </div>
                                      <div class="col-md-3">
                                        <label>Semanas de gestación</label>
                                        <p>{{ $gestacion->semanas_gestacion }}</p>
                                      </div>
                                      <div class="col-md-3">
                                        <label>N° de controles prenatales:</label>
                                        <p>{{ $gestacion->num_controles }}</p>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label>DUDA</label>
                                        <p>{{ $gestacion->duda ? 'Sí' : 'No' }}</p>
                                      </div>
                                    </div>
                                    @endif
                                    @if (!empty($puerperio))
                                    <div class="form-group">
                                      <div class="panel-title text-center">
                                        <b>PUERPERIAS</b>
                                      </div>
                                      <div class="col-md-3">
                                        <label>FECHA PARTO</label>
                                        <p>{{ $puerperio->fecha_parto }}</p>
                                      </div>
                                      <div class="col-md-3">
                                        <label>TIPO DE PARTO</label>
                                        <p>
                                          @if ($puerperio->id_tipo_parto == 1)
                                            Vaginal
                                          @elseif ($puerperio->id_tipo_parto == 2)
                                            Cesárea
                                          @else
                                            Otro
                                          @endif
                                        </p>
                                      </div>
                                      <div class="col-md-3">
                                        <label>COMPLICACION</label>
                                        <p>{{ $puerperio->complicaciones }}</p>
                                      </div>
                                    </div>
                                    @endif
                                    @if (!empty($puerperio->natis))
                                    <div class="panel-title text-center">
                                      <b>NEONATOS</b>
                                    </div>
                                      @foreach ($puerperio->natis as $neonato)
                                        <div class="form-group">
                                          <div class="col-md-3">
                                            <label>ESTADO DE VIDA</label>
                                            <p>{{ $neonato->estado }}</p>
                                          </div>
                                          <div class="col-md-3">
                                            <label>NUM. DOCUMENTOS</label>
                                            <p>{{ $neonato->num_doc }}</p>
                                          </div>
                                          <div class="col-md-3">
                                            <label>SEXO</label>
                                            <p>
                                              @if ($neonato->sexo == 'M')
                                                  MASCULINO
                                              @elseif ($neonato->sexo == 'F')
                                                  FEMENINO
                                              @endif
                                            </p>
                                          </div>
                                          <div class="col-md-1">
                                              <label>TALLA(cm)</label>
                                              <p>{{ $neonato->talla }}</p>
                                          </div>
                                          <div class="col-md-1">
                                              <label>PESO(gr)</label>
                                              <p>{{ $neonato->peso }}</p>
                                          </div>
                                        </div>
                                      @endforeach
                                    @endif
                                  </div>
                                </div>
                              </fieldset>
                              {{------------------ SECTION FOR PURPERIO --------------}}
                              @if ((intval($cita->id_servicio) === 77 || intval($cita->id_servicio) === 131) &&
                                strtoupper($cita->sexo) == "F" && 
                                intval($cita->edadpaciente) >= 5 && 
                                intval($cita->edadpaciente) <= 60
                                /* && !empty($gestacion) */ && empty($puerperio)
                              ) 
                              <div id="divPuerpera" class="panel panel-info panel-bordered panel-sm panel-body" >
                                <div class="form-group">
                                  <div class="col-sm-2"
                                      style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>PUERPERA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="90" type="button"
                                      id="addPuerpera"
                                      class="btn btn-lg btn-toggle"
                                      data-toggle="button"
                                      aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="formPurperio" style="display: none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>SALUD MATERNA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <input type="hidden" name="id_puerperio" id="id_puerperio">
                                      <div class="row">
                                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                              <label for="fecha_parto">FECHA PARTO</label>
                                              <input 
                                                  class="form-control input-sm datepicker" 
                                                  type="text" 
                                                  id="fecha_parto" 
                                                  name="fecha_parto" 
                                                  placeholder="01-01-2020" 
                                                  readonly>
                                          </div>
                                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                              <label for="hora_parto">HORA PARTO</label>
                                              <input 
                                                  class="form-control input-sm" 
                                                  type="time" 
                                                  id="hora_parto" 
                                                  name="hora_parto" 
                                                  placeholder="00:00:00"
                                                  >
                                          </div>
                                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                              <label for="tipo_parto">TIPO DE PARTO</label>
                                              <select tabindex="8" class="form-control input-sm" name="tipo_parto" id="tipo_parto" style="width:100%">
                                              <option value="0"> -Seleccionar-</option>
                                              @if (count($tipoPartos) > 0)
                                                  @foreach ($tipoPartos as $row)
                                                      <option value="{{$row->id}}">{{ $row->nombre }}</option>
                                                  @endforeach
                                              @endif
                                              </select>
                                          </div>
                                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                              <label for="complicacion_parto">COMPLICACIÓN</label>
                                              <select tabindex="8" class="form-control input-sm typeahead tt-query" name="complicacion_parto" id="complicacion_parto" style="width:100%">
                                                <option value="0"> -Seleccionar-</option>
                                                <option value="1">SIN COMPLICACIONES</option>
                                                <option value="2">COMPLICADO</option>
                                              </select>
                                          </div>
                                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top: 10px">
                                              <label for="descripcion_parto">DESCRIPCIÓN</label>
                                              <textarea class="form-control input-sm typeahead tt-query" name="descripcion_parto" id="descripcion_parto" cols="2" rows="2" maxlength="255"></textarea>
                                          </div>
                                      </div>
                                      <hr>
                                      <div class="row" style="">
                                        <div class="col-4 col-md-4">
                                          <span>NEONATOS</span>
                                          <button tabindex="92"
                                              type="button"
                                              id="addRowPurperio"
                                              class="btn btn-primary"
                                              data-target="#addClassModal">
                                              <i class="fa fa-plus"></i>
                                              AGREGAR</button>
                                        </div>
                                      </div>
                                      <br>
                                      <table id="tblPuerperio"  cellspacing="0" width="100%" style="margin-top:5px;">
                                        <tbody>
                                          @if (count(old('numero_documento_puerpera')) > 0)
                                            @for ($i = 0; $i < count(old('numero_documento_puerpera')); $i++)
                                            <tr>
                                              <td style="width: 13%; padding: 0px 0px 20px 0px">                                                                                          
                                                  <label>ESTADO DE VIDA</label>
                                                  <select class="form-control input-sm typeahead tt-query" name="estado_vida_puerpera[]" id="{{'estado_vida_puerpera'.$i}}" style="width:100%">
                                                      <option @if(old('estado_vida_puerpera.'.$i) == '') selected @endif value=""> -Seleccionar-</option>
                                                      <option @if(old('estado_vida_puerpera.'.$i) == 'VIVO') selected @endif value="VIVO">VIVO</option>
                                                      <option @if(old('estado_vida_puerpera.'.$i) == 'MUERTO') selected @endif value="MUERTO">MUERTO</option>
                                                  </select>
                                              </td>
                                              <td style="width: 20%; padding: 0px 0px 20px 15px">                                                                                          
                                                  <label>TIPO DE DOCUMENTOS</label>
                                                  <select class="tipo_doc form-control input-sm typeahead tt-query" name="tipo_documento_puerpera[]" id="{{'tipo_documento_puerpera'.$i}}" style="width:100%"><option value="">--Seleccionar--</option>
                                                    @if (old('estado_vida_puerpera.'.$i) == 'VIVO')
                                                      <option @if(old('tipo_documento_puerpera.'.$i) == '1') selected @endif value="1">DNI</option>
                                                      <option @if(old('tipo_documento_puerpera.'.$i) == '2') selected @endif value="2">CNV</option>
                                                      <option @if(old('tipo_documento_puerpera.'.$i) == '3') selected @endif value="3">AFILIACION DEL RN</option>
                                                    @elseif (old('estado_vida_puerpera.'.$i) == 'MUERTO') 
                                                      <option @if(old('tipo_documento_puerpera.'.$i) == '4') selected @endif value="4">CUI</option>
                                                    @endif
                                                  </select>
                                              </td>
                                              <td style="width: 20%; padding: 0px 0px 20px 15px">
                                                  <label>NUM. DOCUMENTOS</label>
                                                  <input type="text" onkeypress="return validacionDocSoloNumeros(event)" placeholder="Num. documento" name="numero_documento_puerpera[]" id="{{'numero_documento_puerpera'.$i}}" class="form-control input-sm typeahead tt-query numero_documento_puerpera" maxlength="12" pattern="[0-9]+" value="{{old('numero_documento_puerpera.'.$i)}}">
                                              </td>
                                              <td style="width: 15%; padding: 0px 0px 20px 15px">                                                                                          
                                                  <label>SEXO</label>
                                                  <select class="form-control input-sm typeahead tt-query" name="sexo_puerpera[]" id="{{'sexo_puerpera'.$i}}">
                                                      <option @if(old('sexo_puerpera.'.$i) == '') selected @endif value=""> -Seleccionar-</option>
                                                      <option @if(old('sexo_puerpera.'.$i) == 'M') selected @endif value="M">MASCULINO</option>
                                                      <option @if(old('sexo_puerpera.'.$i) == 'F') selected @endif value="F">FEMENINO</option>
                                                  </select>
                                              </td>
                                              <td style="width: 7%; padding: 0px 0px 20px 15px">
                                                  <label>TALLA(cm)</label>
                                                  <input type="text" placeholder="0" name="talla_puerpera[]" id="{{'talla_puerpera'.$i}}" value="{{old('talla_puerpera.'.$i)}}" class="form-control input-sm typeahead tt-query" maxlength="6" onkeypress="return /^[0-9.]+$/i.test(event.key)">
                                              </td>
                                              <td style="width: 7%; padding: 0px 0px 20px 15px">
                                                  <label>PESO(gr)</label>
                                                  <input type="text" placeholder="0" name="peso_puerpera[]" id="{{'peso_puerpera'.$i}}" value="{{old('peso_puerpera.'.$i)}}" class="form-control input-sm typeahead tt-query" maxlength="6" onkeypress="return /^[0-9.]+$/i.test(event.key)">
                                              </td>
                                              <td style="width: 2%; padding: 0px 0px 20px 0px">
                                                  <input type="hidden" class="form-control typeahead tt-query id_puerperio" id="{{'id_parto_puerpera'.$i}}" name="id_parto_puerpera[]" data-id="{{$i}}">
                                              </td>
                                              <td style="width: 5%; padding: 0px 0px 10px 15px">
                                                  <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left: 2%; margin-top: 20px;"><i class="fa fa-times"></i> Eliminar</button>
                                              </td>
                                            </tr>
                                            @endfor
                                          @endif
                                        </tbody>
                                      </table>
                                    </div>                                            
                                  </div>
                                </fieldset>
                              </div>
                              @endif
                              {{---------------- SECTION FOR PURPERIO END ------------}}
                              @if ($verificar_existe->id_puerperio)
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>EVENTOS EPIDEMIOLOGICOS</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <div class="">
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="11" {{ in_array(11, $eventosMarcados) ? 'checked disabled' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes que han recibido por lo menos un control prenatal en la IPRESS</span></label>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="12" {{ in_array(12, $eventosMarcados) ? 'checked disabled' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes que han recibido por lo menos 6 controles prenatales</span></label>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="13" {{ in_array(13, $eventosMarcados) ? 'checked disabled' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes que han recibido la primera consulta en el primer trimestre del embarazo</span></label>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="16" {{ in_array(16, $eventosMarcados) ? 'checked disabled' : '' }}>
                                                  <span style="margin-left: 6px;">Nacidos vivos con bajo peso al nacer (menos de 2500 gramos)</span></label>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="21" {{ in_array(21, $eventosMarcados) ? 'checked disabled' : '' }}>
                                                  <span style="margin-left: 6px;">Nacidos vivos con edad gestacional menor a 37 semanas de gestacion (segun fecha ultima regla)</span></label>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="15" {{ in_array(15, $eventosMarcados) ? 'checked disabled' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes con diagnostico confirmado de infeccion HIV</span></label>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="14" {{ in_array(14, $eventosMarcados) ? 'checked disabled' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes con diagnostico confirmado de Tuberculosis</span></label>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="8" {{ in_array(8, $eventosMarcados) ? 'checked disabled' : '' }}>
                                                  <span style="margin-left: 6px;">Fallecimiento del paciente</span></label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              @endif
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>DIAGNÓSTICOS</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <div id='divDiagnostico'></div>
                                  <br/>
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
                                  <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                                    style="margin-top:5px;">
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
                                                    <option value="0" @if(old('id_tipo_diagnostico.'.$i) == "0") selected @endif>--Seleccionar--</option>
                                                    <option value="1" @if(old('id_tipo_diagnostico.'.$i) == "1") selected @endif>PRESUNTIVO</option>
                                                    <option value="2" @if(old('id_tipo_diagnostico.'.$i) == "2") selected @endif>DEFINITIVO</option>
                                                    <option value="3" @if(old('id_tipo_diagnostico.'.$i) == "3") selected @endif>REITERATIVO</option>
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
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title text-center">
                                    <b>PROCEDIMIENTOS</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <div id='divProcedimiento'></div>
                                  <br/>
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
                                        <th width="45%"></th>
                                        <th width="5%"></th>
                                        <th width="45%"></th>
                                        <th width="5%"></th>
                                      </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                      @if (count(old('nombre_procedimiento')) !== 0)
                                          @for ($i = 0; $i < count(old('nombre_procedimiento')); $i++)
                                              <tr>
                                                <td>
                                                  <input type="text" data-toggle="tooltip" data-placement="top" title="" name="nombre_procedimiento[]" tabindex="44" required="" id="{{'nombre_procedimiento'.($i)}}" class="input-sm ui-autocomplete-input" style="margin-left:4px; width:100%; text-align:left" autocomplete="off" value="{{old('nombre_procedimiento.'.$i)}}" @if(trim(old('nombre_procedimiento.'.$i)) == '') @else readonly="" @endif>
                                                  <input type="hidden" class="form-control id_procedimiento typeahead tt-query" id="{{'id_procedimiento'.($i)}}" name="id_procedimiento[]" value="{{old('id_procedimiento.'.$i)}}" data-id="{{$i}}">
                                                </td>
                                                <td>
                                                  <input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="{{'procedimiento_cantidad'.($i)}}" name="procedimiento_cantidad[]" value="1">
                                                </td>
                                                <td>
                                                  <select class="form-control select-diagnostico" id="{{'miSelect'.($i)}}" name="miSelect[]" prev-data="{{old('miSelect.'.($i))}}">
                                                    @for ($ii = 0; $ii < count(old('id_diagnostico')); $ii++)
                                                    @if (strlen(old('nombre_diagnostico.'.($ii))) > 0)
                                                      <option value="{{old('id_diagnostico.'.($ii))}}">{{explode(" - ", old('nombre_diagnostico.'.($ii)))[1]}}</option>
                                                      @endif
                                                    @endfor
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
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>TRATAMIENTO Y/O TERAPEUTICA</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label>PLAN DE TRABAJO</label>
                                        <textarea class="form-control" rows="7" id="plan_trabajo" name="plan_trabajo"
                                          placeholder="Plan de Trabajo">{{old('plan_trabajo')}}</textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>UBICACIÓN/ ESTADO PACIENTE (PRIORIDAD)</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="form-group row">
                                              <div class="col-md-3">
                                                  <label>PRIORIDAD</label>
                                                  <select id="id_prioridad" name="id_prioridad"   onchange="getTopico();" class="form-control">
                                                    @foreach($opcionesprioridad as $e)
                                                      <option value="{{$e->id}}" <?php if($e->id == $datos_evaluacion->ubica_prioridad) echo 'selected' ?>>{{$e->descripcion}}</option>
                                                    @endforeach
                                                  </select>
                                              </div>
                                              <div class="col-md-2">
                                                  <label>AREA</label>
                                                  <select id="id_topico" name="id_topico"class="form-control" >
                                                    @foreach($topicosprioridad as $e)
                                                      <option value="{{$e->id}}" <?php if($e->id == $datos_evaluacion->ubica_topico) echo 'selected' ?>>{{$e->descripcion}}</option>
                                                    @endforeach
                                                  </select>
                                              </div>
                                              <div class="col-md-2">
                                                <label>FECHA INICIO</label>
                                                <input tabindex="2" type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_inicio" id="fecha_inicio" style="text-align:left" data-date-end-date="0d">
                                              </div>
                                              <div class="col-md-2">
                                                <label class="control-label">HORA</label>
                                                <input type="time" id="appt" name="appt" value="<?php echo $hora ?>" class="form-control" required>
                                              </div>
                                              <div class="col-md-3">
                                                  <label>CAMA</label>
                                                  <select id ="id_cama" name ="id_cama" class="form-control">
                                                    @foreach($totalcamas as $e)
                                                          <option value="{{$e->id}}"<?php if($e->id==$id_cama_actual) echo 'selected'; ?>>{{$e->nombre_cama}}-{{$e->nombre_cuarto}}</option>
                                                    @endforeach 
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </fieldset>
		                            <div class="panel panel-info panel-bordered panel-sm panel-body">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>INTERCONSULTA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="110" type="button" id="addInterconsulta" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="fsInterconsulta" style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                          <b>INTERCONSULTA MÉDICA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-md-4 col-lg-2">
                                                <button tabindex="111" type="button" id="addInterconsulta2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Interconsulta</button>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <select class="form-control" id="cmbInterconsultaServiciosTemp" style="display:none">
                                                <option value="">Seleccionar Servicio</option>
                                                @foreach($establecimiento_servicios as $row)
                                                  @if(intval($row->id) !== 137)
                                                    <option value="{{ $row->id }}" data-has_guardia="{{ $row->has_guardia }}">{{ $row->has_guardia }} - {{ $row->nombre }}</option>
                                                  @endif
                                                @endforeach
                                              </select>
                                            </div>
                                            <div class="form-group">
                                              <div class="col-md-4 col-lg-2">
                                                <label for="">CONDICIÓN DEL PACIENTE:</label>
                                              </div>
                                              <div class="col-md-4 col-lg-2">
                                                <select class="form-control" name="is_estable" id="is_estable" required="" >
                                                  <option value="1">ESTABLE</option>
                                                  <option value="0">INESTABLE</option>
                                                </select>
                                              </div>
                                            </div>
                                            <table class="display" id="tblInterconsultaEv" cellspacing="0" width="100%" style="margin-top:5px;">
                                              <thead>
                                                  <tr>
                                                   <th width="90%"></th>
                                                   <th width="10%"></th>
                                                </tr>
                                              </thead>
                                              <tfoot>
                                              </tfoot>
                                              <tbody>
                                                @if (count(old('nombre_servicio_interconsulta')) !== 0)
                                                    @for ($i = 0; $i < count(old('nombre_servicio_interconsulta')); $i++)
                                                        <tr>
                                                          <td>
                                                            <div class="panel panel-info panel-bordered panel-sm">
                                                              <div class="form-group"> 
                                                                <div class="panel-body"> 
                                                                  <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> 
                                                                    <label>SERVICIO</label>
                                                                  </div> 
                                                                  <div class="col-md-4" style="margin-left:0px;padding-left:0px"> 
                                                                    <input type="text" data-toggle="tooltip" data-placement="top" title="" name="nombre_servicio_interconsulta[]" tabindex="44" required="" id="{{'nombre_servicio_interconsulta'.$i}}" class="input-sm ui-autocomplete-input" style="margin-left:4px; width:100%; text-align:left" autocomplete="off" value="{{old('nombre_servicio_interconsulta.'.$i)}}" @if(trim(old('nombre_servicio_interconsulta.'.$i)) == '') @else readonly="" @endif>
                                                                  </div> 
                                                                  <!-- <div class="col-md-1"> 
                                                                    <label>Personal de Salud</label> 
                                                                  </div> 
                                                                  <div class="col-md-6">
                                                                    <select tabindex="72" class="form-control" name="personal_salud[]" id="{{'personal_salud'.($i)}}" style="width:100%"><option value="">-Seleccionar-</option></select>
                                                                  </div>  -->
                                                                  <div class="col-md-12">
                                                                    <br> 
                                                                    <label>RESUMEN</label> 
                                                                    <textarea class="form-control" cols="5" id="{{'motivo_interconsulta'.($i)}}" name="motivo_interconsulta[]" placeholder="Relato médico o caracteristicas de la interconsulta">{{old('motivo_interconsulta.'.$i)}}</textarea>
                                                                  </div>
                                                                </div>
                                                              </div>
                                                            </div>
                                                          </td>
                                                          <td>
                                                            <button tabindex="114" type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
                                                          </td>
                                                          <input type="hidden" class="" id="{{'personal_salud_prev'.($i)}}" name="personal_salud_prev[]" value="{{old('personal_salud.'.$i)}}">
                                                          <input type="hidden" class="form-control typeahead tt-query id_servicio_interconsulta" id="{{'id_servicio_interconsulta'.($i)}}" name="id_servicio_interconsulta[]" value="{{old('id_servicio_interconsulta.'.$i)}}" data-id="{{$i}}">
                                                        </tr>
                                                    @endfor
                                                  @endif
                                              </tbody>
                                            </table>
                                        </div>
                                      </div>
                                    </div>
                                 </div>
                                </fieldset>
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
                                      <b>PRODUCTOS MEDICOS</b>
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
                                      <select class="form-control" id="cmbMedicamentoTempReceta" tabindex="73"
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
                                    
                                    <table id="tableProductosReceta" class="display" cellspacing="0" width="100%">
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
                                  <div class="panel panel-info panel-bordered panel-sm panel-body" style="  <?php
                                      if(!$is_profesion_medico){
                                          echo "display:none;";
                                      }

                                    ?>">
                                 <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>EXAMEN DE LABORATORIO</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="90" type="button" id="addExamenLab" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
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
                                      <table>
                                        <tr>
                                          <td>
                                            <button tabindex="91" type="button" id="addExamenLab2" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <table class="display" id="tblExamenLab" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                              <th width="45%">Exámen</th>
                                              @if (intval($nivel) !== 3)
                                              <th width="18%">Observación</th>
                                              @endif
                                              <!-- <th width="10%">Cantidad</th> -->
                                              <th width="10%">Diagnóstico</th>
                                              <th></th>
                                              <th></th>
                                              <th width="10%"></th>
                                          </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                          @if (count(old('nombre_examen_lab')) !== 0)
                                            @for ($i = 0; $i < count(old('nombre_examen_lab')); $i++)
                                              <tr>
                                                <td>
                                                  <input type="text" data-toggle="tooltip" data-placement="top" title="" name="nombre_examen_lab[]" tabindex="8" required="" id="{{'nombre_examen_lab'.($i)}}" class="input-sm ui-autocomplete-input" style="margin-left:4px; width:100%; text-align:left" autocomplete="off" value="{{old('nombre_examen_lab.'.($i))}}" @if(trim(old('nombre_examen_lab.'.$i)) == '') @else readonly="" @endif>
                                                  <input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="{{'id_examen_lab'.($i)}}" name="id_examen_lab[]" value="{{old('id_examen_lab.'.($i))}}" data-id="{{$i}}">
                                                </td>
                                                <!-- <td>
                                                  <input type="hidden" maxlength="2" placeholder="Cantidad" class="form-control examen_lab_cantidad typeahead tt-query" readonly style="margin:0 5px;width:95%" id="{{'examen_lab_cantidad'.($i)}}" name="examen_lab_cantidad[]" value="@if(old('examen_lab_cantidad.'.($i)) != null){{old('examen_lab_cantidad.'.($i))}}@else{{'1'}}@endif">
                                                  <input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="{{'examen_lab_observacion'.($i)}}" name="examen_lab_observacion[]" value="{{old('examen_lab_observacion.'.($i))}}">
                                                </td> -->
                                                <td>
                                                  <select style="margin:0 10px;width:95%" class="form-control select-diagnostico" id="{{'miSelectLab'.($i)}}" name="miSelectLab[]" prev-data="{{old('miSelectLab.'.($i))}}">
                                                    @for ($ii = 0; $ii < count(old('id_diagnostico')); $ii++)
                                                      <option value="{{old('id_diagnostico.'.($ii))}}">{{explode(" - ", old('nombre_diagnostico.'.($ii)))[1]}}</option>
                                                    @endfor</select>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                  <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
                                                </td>
                                              </tr>
                                            @endfor
                                          @endif
                                        </tbody>
                                      </table>
                                      @if (intval($nivel) === 3)
                                        <br/>
                                        <div class="form-group">
                                          <div class="col-xs-12">
                                            <label for="">Observación</label>
                                            <input type="text" class="form-control" name="lab_orden_observacion" maxlength="255">
                                          </div>
                                        </div>
                                      @endif
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>EXAMEN IMAGENOLOGIA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="100" type="button" id="addExamenRad" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
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
                                          @if (count(old('nombre_examen_rad')) !== 0)
                                            @for ($i = 0; $i < count(old('nombre_examen_rad')); $i++)
                                              <tr>
                                                <td>
                                                  <input @if(trim(old('nombre_examen_rad.'.$i)) == '') @else readonly="" @endif type="text" data-toggle="tooltip" data-placement="top" title="" name="nombre_examen_rad[]" tabindex="8" required="" id="{{'nombre_examen_rad'.($i)}}" class="input-sm ui-autocomplete-input" style="margin-left:4px; width:100%; text-align:left" autocomplete="off" value="{{old('nombre_examen_rad.'.($i))}}">
                                                  <input type="hidden" class="form-control typeahead tt-query id_radiologia" id="{{'id_examen_rad'.($i)}}" name="id_examen_rad[]" value="{{old('id_examen_rad.'.($i))}}" data-id="{{$i}}">
                                                </td>
                                                <td>
                                                  <select class="form-control id_comparativo" id="{{'cmbComparativo'.($i)}}" name="id_comparativo[]" style="margin:0 20px;width:150px">
                                                    <option value="0">--Seleccionar--</option>
                                                    <option @if(old('id_comparativo.'.($i)) == '1') selected @endif value="1">SI</option>
                                                    <option @if(old('id_comparativo.'.($i)) == '2') selected @endif value="2">NO</option>
                                                  </select>
                                                </td>
                                                <td>
                                                  <select class="form-control id_lado" id="{{'cmbLado'.($i)}}" name="id_lado[]" style="margin:0 20px;width:150px">
                                                    <option value="0">--Seleccionar--</option>
                                                    <option @if(old('id_lado.'.($i)) == '1') selected @endif value="1">Izquierdo</option>
                                                    <option @if(old('id_lado.'.($i)) == '2') selected @endif value="2">Derecho</option>
                                                    <option @if(old('id_lado.'.($i)) == '3') selected @endif value="3">Ambos</option>
                                                  </select>
                                                </td>
                                                <td>
                                                  <input type="text" placeholder="Observacion" class="form-control examen_rad_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="{{'examen_rad_observacion'.($i)}}" name="examen_rad_observacion[]" value="{{old('examen_rad_observacion.'.($i))}}">
                                                </td>
                                                <td>
                                                  <input type="text" maxlength="2" placeholder="" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 5px;width:50%" id="{{'examen_rad_cantidad'.($i)}}" name="examen_rad_cantidad[]" value="@if(old('examen_rad_cantidad.'.($i)) != null){{old('examen_rad_cantidad.'.($i))}}@else{{'1'}}@endif">
                                                </td>
                                                <td>
                                                  <select class="form-control select-diagnostico" id="{{'miSelectRad'.($i)}}" name="miSelectRad[]" style="margin:0 10px;width:95%" prev-data="{{old('miSelectRad.'.($i))}}">
                                                    @for ($ii = 0; $ii < count(old('id_diagnostico')); $ii++)
                                                      <option value="{{old('id_diagnostico.'.($ii))}}">{{explode(" - ", old('nombre_diagnostico.'.($ii)))[1]}}</option>
                                                    @endfor
                                                  </select>
                                                </td>
                                                <td>
                                                  <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button>
                                                </td>
                                              </tr>
                                            @endfor
                                          @endif
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              <!--  -->

                              @if (intval($id_ipress) === 76)
                                <div class="panel panel-info panel-bordered panel-sm panel-body">
                                  <div class="form-group">
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>REGISTRAR ALTA</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="100" type="button" id="swRegistroAlta" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                  <fieldset id="fsRegistroAlta" style="display:none">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>ALTA MÉDICA</b>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <!--  -->
                                        <!-- <input type="hidden" value="<?php //echo $prestacion->id_medico ?>" name="id_medico_egreso" id="id_medico_egreso"> -->
                                        <input type="hidden" value="<?php echo $id_prestacion ?>" name="id_prestacionx" id="id_prestacionx">
                                        <!-- <input type="hidden" value="<?php //echo $id_emergencia ?>" name="id_emergencia" id="id_emergencia"> -->
                                        <!-- <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user"> -->
                                        <!-- <input type="hidden" name="codigo_siteds_backup" id="codigo_siteds_backup"> -->
                                        <!-- <input type="hidden" name="id_servicio_ubicacion" id="id_servicio_ubicacion" />
                                        <input type="hidden" name="id_clase_cama_ubicacion" id="id_clase_cama_ubicacion" /> -->

                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>DIAGNÓSTICOS DE EGRESO</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <!-- <div class="panel panel-info ">
                                              <div class="panel-heading">
                                                <h3 class="panel-title"><b>DIAGNÓSTICOS</b></h3>
                                              </div>
                                              <div class="panel-body"> -->
                                                <table>
                                                  <tr>
                                                    <td>
                                                      <button type="button" id="addDiagnosticoEgreso" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Diagnóstico</button>
                                                    </td>
                                                  </tr>
                                                </table>
                                                <br />
                                                <table class="display" id="tblDiagnosticoEgreso" cellspacing="0" width="100%" style="margin-top:5px;">
                                                  <thead>
                                                    <tr>
                                                      <th width="90%"></th>
                                                      <th width="10%"></th>
                                                    </tr>
                                                  </thead>
                                                  <tfoot>
                                                  </tfoot>
                                                  <tbody>
                                                  </tbody>
                                                </table>
                                              <!-- </div>
                                            </div> -->
                                          </div>
                                        </div>
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
                                                <select tabindex="72" class="form-control" name="id_farmacia_receta_egreso" id="id_farmacia_receta_egreso"
                                                  style="width:100%" onchange="getProductoEgreso()">
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
                                                      <button tabindex="75" type="button" id="addProductoEgreso" class="btn btn-primary"
                                                        data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                                    </td>
                                                  </tr>
                                                </table>
                                              </div>
                                            </div>
                                            <div style="display: none">
                                              <select class="form-control" id="cmbMedicamentoTempAlta" tabindex="73"
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

                                            <table id="tableProductosAlta" class="display" cellspacing="0" width="100%">
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

                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center">
                                              <b>ALTA DE PACIENTE</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <div style="clear:both"></div>
                                            <fieldset id="divAlta2">
                                              <div class="row">
                                                <!-- <br> -->
                                                <div class="col-md-1 col-sm-3">
                                                  <label for="fecha_egreso" class="control-label">FECHA EGRESO</label>
                                                </div>
                                                <div class="col-md-2 col-sm-3">
                                                  <input type="text" readonly data-date-start-date="-<?php echo ($dia_estancia + 1) ?>d" data-date-end-date="0d"
                                                  class="form-control datepicker input-date" value="<?php echo date('d-m-Y', strtotime($fechaServidor)); ?>"
                                                  name="fecha_egreso" id="fecha_egreso" style="text-align:left"/>
                                                </div>

                                                <div class="col-md-1 col-sm-3">
                                                  <label for="hora_egreso" class="control-label">HORA EGRESO</label>
                                                </div>
                                                <div class="col-md-2 col-sm-3">
                                                  <input type="text" class="form-control" value="<?php echo date("G:i:s", strtotime($horaServidor)); ?>" name="hora_egreso" id="hora_egreso" style="text-align:left" />
                                                </div>

                                                <div class="col-md-1 col-sm-3">
                                                  <label for="id_tipo_egreso" class="control-label">TIPO EGRESO</label>
                                                </div>
                                                <div class="col-md-2 col-sm-3">
                                                  <select class="form-control input-sm" id="id_tipo_egreso" name="id_tipo_egreso" style="width: 100%">
                                                    <option value="">Elegir</option>
                                                    <option value="1">DOMICILIARIA</option>
                                                    <option value="2">HOSPITALIZACIÓN</option>
                                                    <option value="3">TRANSFERIDO</option>
                                                    <option value="6">FUGA</option>
                                                    <option value="4">FALLECIDO</option>
                                                    <option value="7">RETIRO VOLUNTARIO</option>
                                                  </select>
                                                </div>

                                                <div id="alta_condicion" style="display:none">
                                                  <div class="col-md-1 col-sm-3">
                                                    <label for="id_condicion_alta" class="control-label">CONDICION ALTA</label>
                                                  </div>
                                                  <div class="col-md-2 col-sm-3">
                                                    <select class="form-control input-sm" id="id_condicion_alta" name="id_condicion_alta" style="width: 100%">
                                                      <option value="">Elegir</option>
                                                      <option value="1">MEJORADO</option>
                                                      <option value="2">CURADO</option>
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
                                                  <select class="form-control input-sm" id="derivar" name="derivar" style="width: 100%" onchange="cambiar_derivacion()">
                                                    <option value="NO">NO</option>
                                                    <option value="SI">SI</option>
                                                  </select>
                                                </div>

                                                <div class="col-md-1">
                                                  <label>SERVICIO</label>
                                                </div>
                                                <div class="col-md-3">
                                                  <select class="form-control input-sm" id="id_servicio_consulta_externa" name="id_servicio_consulta_externa" style="width: 100%">
                                                    <option value="">Elegir</option>
                                                    <?php
                                                      if ($consulta_externa != NULL) { // && $emergencia->derivar == "SI"
                                                        foreach ($consulta_externa as $row):
                                                    ?>
                                                          <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
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
                                                    <input type="text" class="form-control" value="" name="obs_retiro" id="obs_retiro" style="text-align:left">
                                                  </div>
                                                </div>
                                              </div>

                                              <div id="egreso_fuga" style="display:none">
                                                <div class="row">
                                                  <div class="col-md-2">
                                                    <label for="obs_fuga" class="control-label">DETALLE DE FUGA</label>
                                                  </div>
                                                  <div class="col-md-4">
                                                    <input type="text" class="form-control" value="" name="obs_fuga" id="obs_fuga" style="text-align:left">
                                                  </div>
                                                </div>
                                              </div>

                                              <div id="egreso_traslado" style="display:none">
                                                <div class="row">
                                                  <div class="col-md-2">
                                                    <label for="id_establecimiento_traslado" class="control-label">IPRESS DE TRASLADO</label>
                                                  </div>
                                                  <div class="col-md-4">
                                                    <select class="form-control input-sm" id="id_establecimiento_traslado" name="id_establecimiento_traslado" style="width: 100%">
                                                      <option value="">Elegir</option>
                                                      <?php 
                                                        if ($establecimientos_traslado != NULL) {
                                                          foreach ($establecimientos_traslado as $dato):
                                                      ?>
                                                            <option value="<?php echo $dato->id ?>" <?php if (isset($referencias->nombre_establecimiento)) { if ($dato->nombre == $referencias->nombre_establecimiento) { ?> selected <?php } } ?>><?php echo $dato->nombre ?></option>
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
                                                    <input type="text" class="form-control" value="" name="obs_traslado" id="obs_traslado" style="text-align:left">
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
                                                            <option value="<?php echo $dato->id ?>" <?php if (isset($referencias->nombre_servicio_origen)) { if ($dato->nombre == $referencias->nombre_servicio_origen) { ?> selected <?php } } ?>><?php echo $dato->nombre ?></option>
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
                                                    <input type="text" class="form-control" value="" name="especialidad_destino_otro" id="especialidad_destino_otro" style="text-align:left">
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
                                                    <select class="form-control input-sm" id="necropsia" name="necropsia" style="width: 100%">
                                                      <option value="SI">SI</option>
                                                      <option value="NO">NO</option>
                                                    </select>
                                                  </div>

                                                  <div class="col-md-2">
                                                    <label for="causas_muerte" class="control-label">CAUSAS DE MUERTE</label>
                                                  </div>
                                                  <div class="col-md-6">
                                                    <input type="text" class="form-control" value="" name="causas_muerte" id="causas_muerte" style="text-align:left">
                                                  </div>
                                                </div>
                                                <br />

                                                <div class="row">
                                                  <div class="col-md-2"> </div>
                                                  <div class="col-md-2">
                                                    <label for="fecha_defuncion" class="control-label">FECHA FALLECIMIENTO</label>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <input type="text" data-date-start-date="-<?php echo ($dia_estancia + 1) ?>d" data-date-end-date="0d" class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($fechaServidor)); ?>" name="fecha_defuncion" id="fecha_defuncion" style="text-align:left" />
                                                  </div>
                                                  <div class="col-md-1">
                                                    <label for="hora_defuncion" class="control-label">HORA FALLECIMIENTO</label>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <input type="text" class="form-control" value="<?php echo date("G:i:s", strtotime($horaServidor)); ?>" name="hora_defuncion" id="hora_defuncion" style="text-align:left" />
                                                  </div>
                                                </div>
                                              </div>

                                              <div id="egreso_hospitalizacion" style="display:none">
                                                <div class="row">
                                                  <div class="col-md-2">
                                                    <label for="id_servicio_hospitalizacion" class="control-label">ESPECIALIDAD A CARGO</label>
                                                  </div>
                                                  <div class="col-md-3 col-sm-6">
                                                    <select class="form-control input-sm" id="id_servicio_hospitalizacion" name="id_servicio_hospitalizacion" style="width: 100%">
                                                      <option value="">Elegir</option>
                                                    </select>
                                                  </div>
                                                </div>
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

                                                        @if ($verificar_existe->id_tipo_beneficiario == '12')
                                                          <div class="form-group" id="numeroboleta">
                                                            <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                              <label>NUMERO DE BOLETA</label>
                                                            </div>
                                                            <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                                              <input type="text" class="form-control" value="" name="numeroboleta" id="" style="text-align:left" <?php if ($verificar_existe->id_tipo_beneficiario != '12'): echo 'disabled'; endif; ?>>
                                                            </div>
                                                            <div class="col-md-2" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                              <label>NUMERO DE OFICIAL DE GUARDIA</label>
                                                            </div>
                                                            <div class="col-sm-4" style="margin-left:0px;padding-left:0px">
                                                              <input type="text" class="form-control" value="" name="numeroguar" id="" style="text-align:left" <?php if ($verificar_existe->id_tipo_beneficiario != '12'): echo 'disabled'; endif; ?>>
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
                                              <br />
                                            </fieldset>

                                            <fieldset>
                                              <div class="row form-group" id="codigo_siteds_container" style="display:none">
                                                <hr>
                                                <div class="col-md-2">
                                                  <label for="codigo_siteds_egreso">COD. SITEDS SUSALUD</label>
                                                  <input type="text" class="form-control" id="codigo_siteds_egreso" name="codigo_siteds_egreso" readonly>
                                                </div>
                                              </div>
                                            </fieldset>
                                          </div>
                                        </div>
                                        <!--  -->
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                              @endif

                              <!--  -->

                              <input tabindex="140" <?php echo $disabled_atencion?> class="btn btn-primary pull-right" value="GUARDAR" name="crea" type="button"
                                form="prestacionescrea" id="btnPrestacion" />&nbsp;&nbsp;
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade marca_agua_container" id="tab10default" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_ambulatorio"></div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE ATENCIONES MEDICAS - AMBULATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divHistorial1'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade marca_agua_container" id="tab11default" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_emergencia"></div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE ATENCIONES MEDICAS - EMERGENCIA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divHistorial2'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane fade marca_agua_container" id="tab12default" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_hospitalizacion"></div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE ATENCIONES MEDICAS - HOSPITALIZACION</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divHistorial3'></div>
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


  <div id="openOverlayOpc" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">

        <div class="modal-body">

          <div id="diveditpregOpc"></div>

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

  <div class="modal fade" id="showDetalleExamenPendiente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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

  <div class="modal fade" id="showDetalleExamenResult" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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