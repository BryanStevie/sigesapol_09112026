@extends('frontend.layouts.master2')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
<script src='../funciones/function.js'></script> 
<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
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
  /* .modal-dialog {
    width: 90%;
  } */
   .leyenda_grafico {
    list-style-type: none;
    margin: 0;
    padding: 0;
  }
    .leyenda_grafico li {
    margin-bottom: 10px;
    padding-left: 30px;
    position: relative;
  }

  .leyenda_grafico li .icono {
    position: absolute;
    left: 0;
    top: 0;
    height: 20px;
    width: 20px;
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
.swal2-styled.swal2-confirm {
  background-color:#3c763d !important; 
}
.swal2-modal .swal2-content td{
  text-align: left !important;
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
.swal2-icon.swal2-warning,
.swal2-icon.swal2-info {
    font-size: 15px;
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
  .swal2-image {
      width: 50%;
      height: 40%;
  }

  .swal2-icon.swal2-warning,
  .swal2-icon.swal2-info {
    font-size: 15px;
  }

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
  .swal-title-blanco {
    color: white !important;
  }

  .swal-ruaimg .swal2-header {
    background: none;
  }
</style>

<script>

const decimalRegex = /^\d+(\.\d+)?$/;
const DIA_SEMANA_DOMINGO = 0;

var fechaHoraCompleto = new Date('{{$datos_evaluacion->created_at}}');
var procedimientosSeleccionados =[];
//var listIdDiagnostico ={!! json_encode($arraydeprocedimientos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
//var listIdDiagnosticoObjetos ={!! json_encode($arraydeprocedimientos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
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
  $(document).ready(function() {

    $('#datepicker2').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            compruebaIMCInicio();
    // $('#fecha_atencion').datepicker();
    
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
            customClass: {
              popup: 'swal-ruaimg'
            }
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
                customClass: {
                  popup: 'swal-ruaimg'
                }
              }).then(function() {
                  AddExamenRad();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                html: response.message || 'Intente nuevamente.',
                confirmButtonText: 'ENTENDIDO',
                customClass: {
                  popup: 'swal-ruaimg'
                }
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
              customClass: {
                popup: 'swal-ruaimg'
              }
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

    $("#id_cama").select2({
        width: '100%'
    });

    $("#traslado_id_upss_encargado").select2({ width: '100%' });

    $("#id_area_destino").select2({ width: '100%' });

    $("#id_cama_destino").select2({width: '100%' });

       $('#addRecetaVale').on('click', function () {
      var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
      $("#fsRecetaVale").hide();
      if(addRecetaVale == "false"){
        //adicionado
        getProducto();
        $("#fsRecetaVale").show();
      }
      else{
        $('#presiono_receta').val("");
  
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

    $('#addSignosVitales').on('click', function () {
      var addSignosVitales = $('#addSignosVitales').attr("aria-pressed");
      $("#fsSignosVitales").hide();
      if(addSignosVitales == "false"){
        $("#fsSignosVitales").show();
      }
    });
    $('#addInterconsulta').on('click', function () {
    var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
    $("#fsInterconsulta").hide();
    if(addInterconsulta == "false"){
      $("#fsInterconsulta").show();
    }
  });
    $('#addAltaServicio').on('click', function () {
      var addAltaServicio = $('#addAltaServicio').attr("aria-pressed");
      $("#fsAltaServicio").hide();
      $("#flag_alta_servicio").val("0");
      if(addAltaServicio == "false"){
        $("#fsAltaServicio").show();
        $("#flag_alta_servicio").val("1");
      }
    });
    $('#addInterconsulta2').on('click', async function () {
      await AddInterconsulta2();
    });
    $('#addReferencia').on('click', function () {
      var addReferencia = $('#addReferencia').attr("aria-pressed");
      $("#fsReferencia").hide();
      if(addReferencia == "false"){
        $("#fsReferencia").show();
      }
    });
    $('#addRequiereIntervencionQuirurgica').on('click', function () {
      var addCQX = $('#addRequiereIntervencionQuirurgica').attr("aria-pressed");
      $("#evolucion_requiere_intervencion_quirurgica").val("0");
      $("#fsRequiereIntervencionQuirurgica").hide();
      if(addCQX == "false"){
        $("#fsRequiereIntervencionQuirurgica").show();
        $("#evolucion_requiere_intervencion_quirurgica").val("1");
      }
      $.ajax({
        url: '/get_procedimientos',
        type: "GET",
        dataType: "json",
        success: function(result){
          let newOption = "";
          newOption = "<option value=''>--Seleccionar--</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' >"+oo.codigo+" - "+oo.descripcion+"</option>";
          });                 
          $('#cmbProcedimientoIntervencionTemp').html(newOption);
        }
      });
      //medicos cirujanos
      // LLamar a medicos cirujanos
      let id_establecimiento = $("#id_establecimiento").val();
      let id_servicio = $("#id_servicio_encargado").val();
      $.ajax({
        url: '/get_profesionales_x_especialidad/'+ id_establecimiento + '/' + id_servicio,
        type: "GET",
        dataType: "json",
        success: function(result){
          let newOption = "";
          newOption = "<option value=''>--Seleccionar--</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' >"+oo.paterno+" "+oo.materno+", "+oo.nombre+"</option>";
          });                 
          $('#cmbProfesionalesTemp').html(newOption);
        }
      });
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
      var obj = $(this);
      obj.parent().parent().remove();
    });
    $('#tblExamenRad tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
    $("#id_servicio_derivar").select2({
        width: '100%'
    });
    $("#id_servicio_interconsulta").select2({
        width: '100%'
    });
    $("#id_establecimiento_referencia").select2({
        width: '100%'
    });
    $("#id_uci").select2({
        width: '100%'
    });
    $("#id_uci_cv").select2({
        width: '100%'
    });
    $("#id_ambulancia").select2({
        width: '100%'
    });
    $("#id_ambulancia_ref").select2({
        width: '100%'
    });
	
	  $("#id_tipo_ambulancia").select2({
        width: '100%'
    });
    $("#id_pasaje").select2({
        width: '100%'
    });
    $("#id_acompanante").select2({
        width: '100%'
    });
	
    $("#id_situacion").select2({
          width: '100%'
      });
    $("#id_farmacia_receta").select2({
          width: '100%'
      });
    $("#upss_bene").select2({
        width: '100%'
    });
    $("#ipress_bene").select2({
        width: '100%'
    });
    $("#cmbServicio0").select2({
        width: '100%'
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
    $("#talla").inputmask("decimal", {
      integerDigits: 3,
      // placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      // max: 250,
      // digitsOptional: false
    });
    $("#peso").inputmask("decimal", {
      integerDigits: 3,
      digits: 2,
      // placeholder: "0.00",
      allowMinus: false,
      allowPlus: false,
      // max: 500,
      digitsOptional: true
    });
    $("#saturacion").inputmask("decimal", {
      integerDigits: 3,
      //placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100,
      //digitsOptional: false
    });
    /*$("#tamanio_cintura").inputmask("decimal", {
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
    });*/
    $("#presion_arterial_x").inputmask("decimal", {
      integerDigits: 3,
      //placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      // max: 250,
      //digitsOptional: false
    });
    $("#presion_arterial_y").inputmask("decimal", {
      integerDigits: 3,
      //placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      // max: 140,
      //digitsOptional: false
    });
    $("#frecuencia_respiratoria").inputmask("decimal", {
      integerDigits: 2,
      //placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      // max: 35,
      // digitsOptional: false
    });
    $("#frec_cardiaca").inputmask("decimal", {
      integerDigits: 3,
      // placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      // max: 150,
      // digitsOptional: false
    });
    $("#oxigenacion").inputmask("decimal", {
      integerDigits: 3,
      // placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100,
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
    
    AddDiagnostico();
    //AddProcedimiento();
    //AddExamenLab();
    //AddExamenRad();
    // getProducto();
    modalHistorial();
    modalLaboratorio();
    modalRadiologico();
    // modalAntecedentes();
     modalDiagnostico();
    modalProcedimiento();
    //modalHistorialAtencion();
    modalHistorialEvaluaciones();
    modalHistorialAtencionEv();
    modalInterconsulta();
    generarCodigoSitedsHospitalizacion();
    
  });
  // FIN del READY
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
updateSelectElements2();   

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


function getProducto(farmaciaIdLastRecipe = null){
  $('#example tbody').html('');
  // $('#cmbMedicamentoTemp').html('');
  
  var id_ipress     = $('#id_establecimiento').val();
  var id_consultorio  = $('#id_servicio_encargado').val();
  var id_tiporeceta   = $('#id_tipo_atencion').val();
  var id_farmacia     = $('#id_farmacia_receta').val();
  if (farmaciaIdLastRecipe) {
    id_farmacia = farmaciaIdLastRecipe;
  }
  
  var nivel     = $('#id_nivel').val();
  var x  = $('#nro_interconsultas').val();
  var dni = $('#dni_beneficiario').val();

  if(id_tiporeceta == "3"){ 
    if(id_farmacia!=21){ //SIN NUTRICIONAL
      if(id_farmacia==10){
        if (id_consultorio == 44 || id_consultorio == 70 || id_consultorio == 55) { //onco - hemato
          return $.ajax({
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
      else
      { 
        if(id_farmacia == 132){
          return $.ajax({
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
        else
        {
          
          if(id_farmacia == 25){ //nutricional
            
            return $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
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
          else{


        /*if (id_consultorio==44 || id_consultorio==70){ //onco - hemato
            return $.ajax({
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
          else
          {*/ if (id_consultorio==21){ //odonto
              return $.ajax({
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
            else
            {
              if (id_consultorio==72){ //aneste
                  return $.ajax({
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
                return $.ajax({
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
        return $.ajax({
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
        width: '100%'
    });
async function guardarPrestacion(){

  var msg = "";
  var addExamenLab = $('#addExamenLab').attr("aria-pressed");
  let id_establecimiento = $("#id_establecimiento").val();

  if (addExamenLab == "true") {
    
    const tblExamenLab_cantidad_tr1 = $('#tblExamenLab tbody tr').length;
    if (tblExamenLab_cantidad_tr1 > 0) {
      if (id_establecimiento == 76) {
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
  }

  Swal.fire({
    title: '<h5><b>REGISTRAR EVOLUCIÓN<b></h5>',
    showLoaderOnConfirm: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    onOpen: () => {
      Swal.showLoading();
    }
  });

  // var msg = "";
  $('#btnPrestacion').prop("disabled",true);

	var id_tipo_atencion = $('#id_tipo_atencion').val();
        var codigo_upss = $('#codigo_upss').val();
        var id_modalidad = $('#id_modalidad').val();
	let fecha_atencion = $('#fecha_atencion').val();
  let hora_atencion = $('hora_atencion').val();
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
	// var addExamenLab = $('#addExamenLab').attr("aria-pressed");
	var addExamenRad = $('#addExamenRad').attr("aria-pressed");
	var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
  var addReferencia = $('#addReferencia').attr("aria-pressed");
	var addCondicion = $('#addCondicion').attr("aria-pressed");
	var cantidadReceta = $('#cantidadReceta').val();
	$('#procedimientosSeleccionados1').val(JSON.stringify(procedimientosSeleccionados));
	let vardatos = $('#procedimientosSeleccionados1').val();
  let temperatura = $('#temperatura').val();
  let saturacion = $('#saturacion').val();
  let presion_arterial_x = $('#presion_arterial_x').val();
  let presion_arterial_y = $('#presion_arterial_y').val();
  let frecuencia_respiratoria = $('#frecuencia_respiratoria').val();
  let frec_cardiaca = $('#frec_cardiaca').val();
  let cod_siteds_susalud = $('#cod_siteds_susalud').val();
  let apreciacion_subjetiva = $('#apreciacion_subjetiva').val();
  let apreciacion_objetiva = $('#apreciacion_objetiva').val();
  let verificacion_tratamiento = $('#verificacion_tratamiento').val();
  let interpretacion_examenes = $('#interpretacion_examenes').val();
  let plan_trabajo = $('#plan_trabajo').val();
  let regularizar_datos = $('#regularizar_datos').val();

/*   if(cod_siteds_susalud.trim()=="") msg += "Debe ingresar el <b>Código SITEDS</b>.<br>";
  else $("#cod_siteds_susalud").val(completarConCeros(cod_siteds_susalud,12)); */
  if(fecha_atencion == "") msg += "Debe ingresar la Fecha de Atención<br>";
  if(hora_atencion == "") msg += "Debe ingresar la Hora de Atencion<br>";
  if(regularizar_datos == "1") {
    let id_servicio_encargado_regularizacion = $('#id_servicio_encargado_regularizacion').val();
    if (id_servicio_encargado_regularizacion == "0" || id_servicio_encargado_regularizacion == null) msg+="Debe seleccionar el <b>Servicio a Cargo</b><br>";
  }
	if(dni_beneficiario=="" && beneficiario=="")msg+="Debe buscar un Asegurado<br>";
  if(temperatura=="") msg+="Debe ingresar la <b>Temperatura</b><br>";
  if(saturacion=="") msg+="Debe ingresar la <b>Saturación</b><br>";
  if(presion_arterial_x=="") msg+="Debe ingresar la <b>Presión Sistólica</b><br>";
  if(presion_arterial_y=="") msg+="Debe ingresar la <b>Presión Diastólica</b><br>";
  if(frecuencia_respiratoria=="") msg+="Debe ingresar la <b>Frecuencia Respiratoria</b><br>";
  if(frec_cardiaca=="") msg+="Debe ingresar la <b>Frecuencia Cardiaca</b><br>";

  if(apreciacion_subjetiva.trim()=="") msg += "Debe ingresar la <b>Apreciación Subjetiva</b>.<br>";
  if(apreciacion_objetiva.trim()=="") msg += "Debe ingresar la <b>Apreciación Objetiva</b>.<br>";
  if(verificacion_tratamiento.trim()=="") msg += "Debe ingresar la <b>Verificación de Tratamiento y Dieta</b>.<br>";
  if(plan_trabajo.trim()=="") msg += "Debe ingresar el campo de <b>Terapéutica y Plan de Trabajo</b>.<br>";

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
			if(id_procedimiento == "") {
        msg+="Debe ingresar el nombre del <b>procedimiento CPMS</b> y los datos que se solicitan, de lo contrario elimine el campo<br>";
      } else if (id_procedimiento != "") {
        if (cantidad == "") {
          msg+="Debe ingresar la cantidad del <b>procedimiento CPMS</b> ingresado<br>";
        } else if (cantidad == 0) {
          msg+="Debe ingresar la cantidad del <b>procedimiento CPMS</b> y debe ser mayor a 0<br>";
        }
        if(miSelect == "" || miSelect==null)msg+="Debe seleccionar el diagnóstico para el <b>procedimiento CPMS</b><br>";
      }
		}
	}/*else{
		msg+="Debe ingresar minimo un procedimiento<br>";
	}*/

	  //if(fecha_expedicion=="")msg+="Debe ingresar una Fecha de Expedicion<br>";
	
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
          if (diag_prod == "" || diag_prod == null) msg += "Debe seleccionar el diagnóstico para el <b>Producto Médico</b><br>";
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

  // ************ VALIDAR UBICACIÓN/ ESTADO PACIENTE *****************
  const id_area_destino = $("#id_area_destino").val();
  console.log("[id_area_destino] => ", id_area_destino);
  if (id_area_destino) {
    const fecha_inicio = $("#fecha_inicio").val();
    const appt = $("#appt").val();
    const id_cama_destino = $("#id_cama_destino").val();
    if (!fecha_inicio.trim()) msg+="Debe ingresar la <b>FECHA INICIO ROTACIÓN</b><br>";
    if (!appt.trim()) msg+="Debe ingresar la <b>HORA ROTACIÓN</b><br>";
    if (!id_cama_destino) msg+="Debe ingresar la <b>CAMA/CAMILLA DESTINO</b><br>";
  }
  // ********** VALIDAR UBICACIÓN/ ESTADO PACIENTE - END *************

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

        if(id_examen_lab == "") {
          msg += "Debe registrar un examen de laboratorio<br>";
        } else {
          if (cantidad_lab == "") msg += `Debe registrar una cantidad para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
          if (cantidad_lab == 0) msg += `Debe registrar una cantidad mayor a 0 para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
          if (id_diagnostico_lab == "" || id_diagnostico_lab == null) msg += `Debe seleccionar el diagnóstico para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
        }
      }

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
    }
	} else {
    $('#tblExamenRad tbody').empty();
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

  // VALIDACION DEL FORMULARIO PUERPERA
  const tooglePuerpera = String($("#tooglePuerpera").attr("aria-pressed")) === "true";
  if (tooglePuerpera) {
    const fecha_parto = $("#fecha_parto").val();
    const hora_parto = $("#hora_parto").val();
    const tipo_parto = $("#tipo_parto").val();
    const complicacion_parto = $("#complicacion_parto").val();
    if (!fecha_parto.trim()) msg+="Debe ingresar la <b>Fecha de parto</b><br>";
    if (!hora_parto.trim()) msg+="Debe ingresar la <b>Hora de parto</b><br>";
    if (!tipo_parto.trim()) msg+="Debe seleccionar el <b>Tipo de parto</b><br>";
    if (!complicacion_parto.trim()) msg+="Debe ingresar la <b>complicación</b><br>";
    
    const countRowsTableNeonatos = $('#tableNeonatos tbody tr').length
    if (countRowsTableNeonatos <= 0) {
      msg+="Debe agregar como minimo el registro de un <b>Neonato</b><br>";
    } else {
      const validTableNeonatos = validateLastRowTableNeonatos();
      if (!validTableNeonatos) {
        msg+="Debe ingresar todo los campos del <b>Neonato</b><br>";
      }
    }
  }

  // VALIDACION DEL FORMULARIO TRASLADO
  // const toogleTraslado = String($("#toogleTraslado").attr("aria-pressed")) === "true";
  //let select_cambiar_cama = $("#select_cambiar_cama").val();
  //if (select_cambiar_cama == "2") {
  // const toogleTraslado = String($("#toogleTraslado").attr("aria-pressed")) === "true";
  ///const select_cambiar_cama = $('#select_cambiar_cama').val();  
  let select_cambiar_cama = $("#select_cambiar_cama").val();
  if (select_cambiar_cama == "2") {
    const traslado_tipo = $("#traslado_tipo").val();
    if (!traslado_tipo.trim()) msg+="Debe seleccionar el <b>Tipo de transferencia</b><br>";

    if (traslado_tipo && parseInt(traslado_tipo) === 2) {
      let traslado_id_upss_encargado = $("#traslado_id_upss_encargado").val();
      let traslado_id_servicio_encargado = $("#traslado_id_servicio_encargado").val();
      const traslado_upss_destino = $("#traslado_upss_destino").val();
      const traslado_cama = $("#traslado_cama").val();
      const traslado_fecha = $("#traslado_fecha").val();
      const traslado_hora = $("#traslado_hora").val();

      if (traslado_id_upss_encargado == "") msg+= "Debe seleccionar el <b>UPSS a cargo</b> en el traslado<br>"
      if (traslado_id_servicio_encargado == "0" || traslado_id_servicio_encargado == null) msg+= "Debe seleccionar el <b>Departamento a cargo</b> en el traslado<br>"
      if (!traslado_upss_destino.trim()) msg+="Debe seleccionar el <b>upss de destino</b> en traslado<br>";
      if (!traslado_cama.trim()) msg+="Debe seleccionar la <b>cama de traslado</b><br>";
      if (!traslado_fecha.trim()) msg+="Debe ingresar la <b>Fecha de traslado</b><br>";
      if (!traslado_hora.trim()) msg+="Debe ingresar la <b>Hora de traslado</b><br>";
    }

    const countRowsTableTraslado = $('#tableDiagTraslado tbody tr').length
    if (countRowsTableTraslado <= 0) {
      msg+="Debe agregar como minimo un <b>DIAGNÓSTICO DE TRASLADO</b><br>";
    } else {
      const validTableTraslado = validateLastRowTableDiagTraslado();
      if (!validTableTraslado) {
        msg+="Debe ingresar todo los campos del <b>DIAGNÓSTICO DE TRASLADO</b><br>";
      }
    }
  } 

    //PARA CENTRO QUIRURGICO
    var addCQX = $('#addRequiereIntervencionQuirurgica').attr("aria-pressed");
    if(addCQX == "true"){
      $('#btnPrestacion').prop("disabled", true);

      try {
       
          var id_hospitalizacion = $("#id_hospitalizacion").val();
          if (id_hospitalizacion) {
              const resp = await $.post("{{ url('ajax_validar_evolucion_qx') }}", {
                  _token: "{{ csrf_token() }}",
                  id_hospitalizacion: id_hospitalizacion
              });

              if (resp.existe) {
                  const result = await Swal.fire({
                      title: 'Atención: Evolución Quirúrgica existente',
                      html: `
                          Este paciente ya tiene registrada una <b>Evolución Quirúrgica previa</b> durante su hospitalización.<br><br>
                          Si realiza otra evolución y la envía al Centro Quirúrgico, se registrará una nueva intervención.<br><br>
                          ¿Desea continuar con el envío al Centro Quirúrgico de todas formas?
                      `,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonText: 'Continuar',
                      cancelButtonText: 'Cancelar',
                      customClass: {
                          title: 'swal-title-blanco'
                      }
                  });

                  if (!result.isConfirmed) {
                      $('#btnPrestacion').prop("disabled", false);
                      return;
                  }
              }
          }

      } catch (e) {
          console.error(e);
          $('#btnPrestacion').prop("disabled", false);
      }
      var id_tipo_cirugia = $('#id_tipo_cirugia').val();
      if(id_tipo_cirugia=='1'){
        var hora_inicio_intervencion = $('#hora_inicio_intervencion').val();
        var hora_fin_intervencion = $('#hora_fin_intervencion').val();
        if (!hora_inicio_intervencion) {
          msg += "Debe ingresar la hora de inicio (Centro quirurgico)<br>";
        }
        if (!hora_fin_intervencion) {
          msg += "Debe ingresar la hora fin (Centro de quirurgico)<br>";
        }
      }
      if(id_tipo_cirugia=='2' || id_tipo_cirugia=='3'){
        var horas = parseInt($('#horas').val()) || 0;
        //var minutos = parseInt($('#minutos').val()) || 0;
        if (horas <= 0) {
          msg += "Ingrese una <b>hora</b> mayor a 0 (Centro quirúrgico)<br>";
        }
      }
      var ind_diag = $('#tblDiagnosticoQuirurgico tbody tr').length;
      var ind_proced = $('#table_procedimiento_intervencion tbody tr').length;
      var ind_cirujano = $('#table_medico_cirujano tbody tr').length;

      // Validación de diagnósticos
      if (ind_diag > 0) {
        var errorEnDiagnosticos = false;
        $('#tblDiagnosticoQuirurgico tbody tr').each(function(){
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
      }else{
        msg += "Debe ingresar mínimo <b>un diagnóstico (Centro quirurgico)</b><br>";
      }
      // Validación de procedimientos
      if (ind_proced > 0) {
        var errorEnProcedimientos = false;
        $('#table_procedimiento_intervencion tbody tr').each(function (index) {
          var id_procedimiento = $(this).find('.cmbProcedimientoIntervencion').val();
          if (id_procedimiento === "") {
            msg += "Debe ingresar el <b>nombre del procedimiento (Centro quirurgico)</b><br>";
            errorEnProcedimientos = true;
          }
          // Si no hay errores, limpiamos el mensaje de procedimientos
          if (!errorEnProcedimientos){
            msg = msg.replace("Debe ingresar el <b>nombre del procedimiento (Centro quirurgico)</b><br>", "");
          }        
        });
      }else{
          msg += "Debe ingresar mínimo <b>un procedimiento (Centro quirurgico)</b><br>";
      }
      // Validación de cirujanos
      if (ind_cirujano > 0) {
        var errorEnCirujanos = false;
        $('#table_medico_cirujano tbody tr').each(function (index) {
          var id_cirujano = $(this).find('.cmbMedicoCirujano ').val();
          var id_cargo_medico = $(this).find('.cargoMedicoCirujano').val();
          
          if (id_cirujano === "") {
            msg += "Debe ingresar el nombre del <b>médico cirujano (Centro quirurgico)</b><br>";
            errorEnCirujanos = true;
          }
          if (id_cargo_medico === "" || id_cargo_medico === "0") {
            msg += "Debe de seleccionar el <b>cargo del médico cirujano (Centro quirurgico)</b><br>";
            errorEnCirujanos = true;
          }
          // Si no hay errores, limpiamos el mensaje de procedimientos
          if (!errorEnCirujanos){
            msg = msg.replace("Debe ingresar el <b>nombre del médico cirujano (Centro quirurgico)</b><br>", "");
          }         
        });
      }else{
        msg += "Debe ingresar mínimo <b>un médico cirujano (Centro quirurgico)</b><br>";
      }
    }
    ////FIN PARA CENTRO QUIRURGICO
   	
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
      //const cod_siteds_susalud = $('#cod_siteds_susalud').val();
      //if (!cod_siteds_susalud) {
      //  const result_siteds = await generarCodigoSitedsHospitalizacion();
      //  if (result_siteds && result_siteds.nuAutorizacion) {
      //    $("#cod_siteds_susalud").val(result_siteds.nuAutorizacion);
      $("#frmPrestacion").submit();
      //    return false;
      //  }
      //}
      /* let codigo_siteds_valido = $('#codigo_siteds_valido').val();
      if (codigo_siteds_valido == "0") {
        var codSiteds= $('#cod_siteds_susalud').val();  
        $.ajax({
          url:'{{url('validar_codigo_siteds/')}}/'+codSiteds,
          type:'GET',
          dataType:'JSON', 
            success: function(data){   
              if(data!=null){ 
                if(!data) {
                  bootbox.alert({
                    message: 'El <b>CÓDIGO SITEDS</b> ingresado ya fue asignado a una admisión diferente, por favor ingrese uno diferente.',
                    size: 'small'
                  });

                  setTimeout(function (){ 
                      $('#cod_siteds_susalud').val('');
                      $('#cod_siteds_susalud').focus();
                  }, 500);  

                } else { 
                  $("#frmPrestacion").submit();
                  return false;
                } 
              }
            },
            error: function( XMLHttpRequest, textStatus, errorThrown ) {
              bootbox.alert("Ha ocurrido un error al validar el Código SITEDS");
              return false;
            }
        });
      } else {
        $("#frmPrestacion").submit();
        return false;
      } */
    }
    $('#btnPrestacion').prop("disabled",false);
}

function validateFrm() {
  let upss_cargo = $('#upss_cargo').val().trim();
  let servicio_medico = $('#servicio_medico').val().trim();
  let servicios = servicio_medico.split('#|#').map(item => item.trim());

  if (servicios.includes(upss_cargo)) {
      Swal.fire({
        title: '<h5><b>REGISTRAR EVOLUCIÓN</b></h5>',
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
                    title: '<h5><b>REGISTRAR EVOLUCIÓN</b></h5>',
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

function obtenerBeneficiario(){

	var id_tipo_atencion = 1;
	var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
	var dni_beneficiario = $("#dni_beneficiario").val();
	var msg = "";
	
	//if(id_tipo_atencion == "")msg += "Debe seleccionar el tipo de receta <br>";
	//if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
	
	if (msg != "") {
		bootbox.alert(msg);
		return false;
	}
	
	$.ajax({
		url: '../obtener_beneficiario/' + id_tipo_atencion + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
		dataType: "json",
		success: function(result){
			if(result.sw == false){
				bootbox.alert(result.msg);
				$('#beneficiario').val("");
				$('#tipo_beneficiario').val("");
				$('#nombre_beneficiario').val("");
				$('#paterno_beneficiario').val("");
				$('#materno_beneficiario').val("");
				$('#tipodoc_beneficiario').val("");
				$('#cip_beneficiario').val("");
				$('#grado').val("");
				return false;
			}
			
			var beneficiario = result.beneficiario.apepatafiliado+" "+result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado+", "+result.beneficiario.nomafiliado;
			$('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
			$('#beneficiario').val(beneficiario);
			$('#tipo_beneficiario').val(result.beneficiario.parentesco);
			$('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
			$('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
			$('#materno_beneficiario').val(result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado);
			$('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
			$('#cip_beneficiario').val(result.beneficiario.cip);
			$('#grado').val(result.beneficiario.grado);
			
			var parentesco 	= result.beneficiario.parentesco;
			var ubigeo 		= result.beneficiario.ubigeo;
			
			obtenerEstablecimiento(parentesco,ubigeo);
			modalHistorial();
			modalLaboratorio();
			modalRadiologico();
      modalInterconsulta();
			getProducto();
			AddDiagnostico();
			AddProcedimiento();
			AddExamenLab();
			AddExamenRad();
			/*
			if(id_tipo_atencion == 3){	
				ShowByServicioAndId_farmacia();
			}
			*/
		},
		error: function(response) {
			location.reload();
		}
		
	});
	
}
function obtenerBeneficiario_dirrehum(){
	var id_tiporeceta = 1;
	var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
	var dni_beneficiario = $("#dni_beneficiario").val();
	var msg = "";
	
	if (msg != "") {
		bootbox.alert(msg);
		return false;
	}
	
	$.ajax({
		url: '/obtener_beneficiario_dirrehum/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
		dataType: "json",
		success: function(result){
			if(result.sw == false){
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
				$('#cip_beneficiario').val("");
				return false;
			}
			
			var beneficiario = result.beneficiario.apepatafiliado+" "+result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado+", "+result.beneficiario.nomafiliado;
			$('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
			$('#beneficiario').val(beneficiario);
			$('#tipo_beneficiario').val(result.beneficiario.parentesco);
			$('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
			$('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
			$('#materno_beneficiario').val(result.beneficiario.apematafiliado+" "+result.beneficiario.apecasafiliado);
			$('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
			$('#cip_beneficiario').val(result.beneficiario.cip);
			$('#grado').val(result.beneficiario.grado);
			$('#fecnacafiliado').val(result.beneficiario.fecnacafiliado);
			$('#nomsexo').val(result.beneficiario.nomsexo);
			
			if(result.numero_historia!=null){
				if(result.numero_historia!='')
					$('#nro_historia').val(result.numero_historia);
				else
					$('#nro_historia').val(result.beneficiario.nrodocafiliado);	
			}
			else				
				$('#nro_historia').val(result.beneficiario.nrodocafiliado);
			
			$('#cip_beneficiario').val(result.beneficiario.cip);
			
			$('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
			$('#nrodoctitular').val(result.beneficiario.nrodoctitular);
			//$('#tipo_beneficiario').val(result.beneficiario.parentesco);
			$('#nombre_titular').val(result.beneficiario.nomtitular);
			$('#paterno_titular').val(result.beneficiario.apepattitular);
			$('#materno_titular').val(result.beneficiario.apemattitular+" "+result.beneficiario.apecastitular);
			//$('#beneficiario').val(beneficiario);
			
			var parentesco 			= result.beneficiario.parentesco;
			var ubigeo 				= result.beneficiario.ubigeo;
			var establecimiento 	= result.establecimiento;
			var cita 				= result.cita;
			var id_establecimiento 	= 0;
			
			if(establecimiento[0]!=undefined){
				id_establecimiento = establecimiento[0].id_establecimiento;
				nombre_establecimiento = establecimiento[0].establecimiento;
				$('#nombre_establecimiento').html(nombre_establecimiento);
			}
			
			if(cita[0]!=undefined){
				if(cita[0].id_estado_cita == 1){
					//bootbox.alert("El beneficiario tiene una cita programada");
					//return false;
					//var id_servicio = cita[0].id_servicio;
					var id_servicio = 8;
					$("#parent1").prop("checked", true);
				}
				if(cita[0].id_estado_cita == 3){
					var id_servicio = cita[0].id_servicio;
					//$('#parent').val("2");
					//$('#nombre_parent').html("DERIVADO");
					//var parent = $('input[name="parent"]:checked').val();
					$("#parent2").prop("checked", true);
				}
			}else{
				var id_servicio = 8;
				$("#parent1").prop("checked", true);
			}
			
			//obtenerEstablecimiento(parentesco,ubigeo,76,id_servicio);
			modalHistorial();
			modalLaboratorio();
			modalRadiologico();
                        modalInterconsulta();
			getProducto();
			AddDiagnostico();
			AddProcedimiento();
			AddExamenLab();
			AddExamenRad();
			$("#divTurno").html("");
			//getProducto();
			
		},
		error: function(response) {
			location.reload();
		}
	});
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
function limpiarBeneficiario(){
		
	var dni_beneficiario = $('#dni_beneficiario').val();
	var nrodocafiliado = $('#nrodocafiliado').val();
	if(dni_beneficiario!=nrodocafiliado){
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
	
	if(dni_beneficiario!="")obtenerBeneficiario();
	//if(dni_beneficiario!="")obtenerBeneficiario_dirrehum();
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
  var id_hospitalizacion = $("#id_hospitalizacion").val();
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_evaluaciones_atenciones/"+id_hospitalizacion+'/'+3,
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
/*function modalAntecedentes(){
	
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
}*/



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
        url: '../obtener_procedimiento_convenio_tipo_atencion/' + request.term + '/' + nivel + '/' + genero + '/' + 3,
        //url: '../obtener_procedimiento/' + request.term,
        dataType: "json",
        success: function(data) {
          let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
          let existeProcedimientoTipoHospitalarioUnico = false;
          for (const procedimientoId of procedimientosHospitalariosUnicosPorDiaIds) {
            if(listaProcedimientoSelecionado.includes(procedimientoId)) {
              existeProcedimientoTipoHospitalarioUnico = true;
              break;
            }
          }
          if(existeProcedimientoTipoHospitalarioUnico) {
            let procedimientosPrev = data.filter((element)=>!procedimientosHospitalariosUnicosPorDiaIds.includes(element.id));
            let filtro = procedimientosPrev.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
            response(filtro);
          } else {
            let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
            response(filtro);
          }
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
  async function  AddInterconsulta2(){
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

      // Inicializa la variable item_OTORRINO para los options
      var item_OTORRINO = '<option value="0">--Seleccionar--</option>';
      var item_DIAGNOSTICO = '<option value="0">--Seleccionar--</option>';

      // AJAX para OTORRINOLARINGOLOGÍA
      $.ajax({
          url: '../cargar_upsses',
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
          url: '../cargar_ups_diagnostico',
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

      var id_ipress = $('#id_establecimiento').val();

      $('#tblInterconsultaEv tbody').append(newRow);
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

      $("#id_servicio_interconsulta" + ind).on("change", async function (e) {
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
	
  if (ind > 0) {
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
    /*console.log('ENTRO EN CONDICIÓN');
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
    }*/
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FIN =================
    //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO INICIO ============================
    /*let descripcion_examen = $('#descripcion_examen' + (ind - 1)).val();
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
    }*/
    //=== VALIDA SOLICITUD EXAMEN ESPECIALIZADO HEMOCULTIVO FIN ===============================
    //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================
    /*if (descripcion_examen == 'CITOQUIMICO LIQUIDOS BIOLOGICOS') {
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
    }*/
    /*$('.id_laboratorio').each(function() {
      var ind_tmp = $(this).data("id");
      if (ind_tmp => ind) {
        ind = Number(ind_tmp) + 1;
      }
    });*/
    ind = obtenerIndiceTblExamenLab();

    fila_medicamentos = $('#tblExamenLab tbody').children('tr');
    rowIndex = $('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento = fila_medicamentos[idval];
		
  }
  const id_nivel = $("#id_nivel").val();
  newRow +='<tr>';
    newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id="'+ind+'">';
  newRow +='<input type="hidden" class="form-control typeahead tt-query cod_lab" id="cod_lab' + ind +'" name="cod_lab[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_categoria" id="id_categoria_examen_lab' + ind +'" name="id_categoria_examen_lab[]" value="" data-id='+ind+'></td>';
  if (id_nivel && parseInt(id_nivel) < 3) {
    newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  }
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad typeahead tt-query" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"><option value="">-- Seleccionar --</option></select></td>';
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
  $("#examen_lab_cantidad" + ind).on('input', function() {
    var valor = $(this).val();
    if (valor === '0') {
      $(this).val(''); // Si ingresan 0, eliminarlo
    }
  });
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
      $.ajax({
        url: '../obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
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
      var id_examen_lab = ui.item.id;
      let codigo_procedimiento = ui.item.codigo_procedimiento;
      let id_categoria_examen_lab = ui.item.id_categoria;
      $("#cod_lab" + ind).val(ui.item.codigo_procedimiento);
      $("#id_categoria_examen_lab" + ind).val(id_categoria_examen_lab);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
      $("#id_examen_lab" + ind).val(ui.item.id);
      /*validaExamenSeleccionado($("#id_examen_lab" + ind).val());
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
      }*/

        //valida las examenes depeendidntes
        /*var seleccionados = $("input[name='cod_lab[]']").map(function () {
          return $(this).val();
      }).get();

      if (verificarFaltantes(seleccionados) != "") {
          alert(verificarFaltantes(seleccionados));
      }*/
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
  $("#examen_lab_cantidad" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
      //$("#examen_lab_cantidad" + ind).val("");
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

function modalHistorialEvaluaciones(){
  
  var id_hospitalizacion = $("#id_hospitalizacion").val();
  var estado = $("#estado").val();
  var id_tipo_atencion = $("#id_tipo_atencion").val();

  $.ajax({
      url: "../../modal_historial_evaluaciones/"+id_hospitalizacion+"/"+estado+"/"+id_tipo_atencion,
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
  
  var id_principal = $("#id_hospitalizacion").val();
  var estado = $("#estado").val();
  var id_tipo_atencion = $("#id_tipo_atencion").val();

  $.ajax({
      url: "../../modal_historial_evaluaciones_atenciones/"+id_principal+'/'+3,
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
   var id_hospitalizacion = $("#id_hospitalizacion").val();
  $.ajax({
      url: "../../modal_diagnostico_paciente_nuevo_ho/"+id_prestacion,
      type: "GET",
      success: function (result) {  
          $("#divDiagnostico").html(result);
      }
  });

}

function modalProcedimiento(){
  
  var id_prestacion = $("#id_prestacion").val();
  $.ajax({
      url: "../../modal_procedimiento_paciente_nuevo_ho/"+id_prestacion,
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
            $('#tablemodalm1').DataTable({
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
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind +'" name="id_examen_rad[]" value="" data-id='+ind+'></td>';
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
  //const id_servicio = $('#id_servicio').val();
  const id_servicio_encargado = $('#id_servicio_encargado').val();
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
        url: '../obtener_radiologia_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 3 + '/' + 105,
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

function AddProducto(productoParam = null) {
  var tipo_receta = $('#tipo_receta').val();
  // var id_consultorio = $('#id_consultorio').val();
  var id_farmacia = $('#id_farmacia_receta').val();
  if (tipo_receta == "") {
    bootbox.alert("Seleccione el tipo atención");
    return false;
  }
  // if (id_consultorio == "") {
  //   bootbox.alert("Seleccione un servicio");
  //   return false;
  // }
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
	
  newRow += '<td><select class="form-control miSelectProd"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';  
  newRow +='<td><button type="button" class="btn btn-primary btn-xs" id="add_producto'+ind+'"><i class="fa fa-plus"></i> </button></td>';
  newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
  newRow +='</tr>';
  $('#example tbody').append(newRow);
  $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();


  const selectElementProd = document.getElementById('miSelectProd' + ind);

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
      url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
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
      // $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
      // $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
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

      if (productoParam) {
        $(`#nro_med_solictados${ind}`).val(productoParam.cantidad_prescrita);
      }
    });

  });
  filasmedicamentos++;

  if (productoParam){
    $(`#cmbMedicamento${ind}`).val(productoParam.id_producto).trigger('change');
    $(`#tiempo_periodo${ind}`).val(productoParam.tiempo_periodo); 
    $(`#cmbTipoViaAdministracion${ind}`).val(productoParam.id_via_administracion);
    $(`#posologia${ind}`).val(productoParam.posologia);
  }
}
// Fin de AddProducto
function actualizarEstado() {
  const valoresSeleccionadosMedicamento = [];
  const valoresSeleccionadosProductos = [];
  // Obtener los valores seleccionados de todos los combos ".cmbMedicamento"
  $(".cmbMedicamento").each(function () {
    let seleccionados = $(this).val(); // Obtener el valor seleccionado
    if (!Array.isArray(seleccionados)) {
      seleccionados = seleccionados ? [seleccionados] : []; // Asegurarse de que sea un array
    }
    seleccionados.forEach((value) => {
      //valoresSeleccionadosMedicamento.push(value); // Guardar cada valor seleccionado
      if (value && value !== "ru='undefined'" && value !== "undefined" && value !== "") { 
        valoresSeleccionadosMedicamento.push(value);
      }
    });
  });

  // Almacenar todos los valores seleccionados de los combos ".productos_combinados"
  
  $(".productos_combinados").each(function () {
    let seleccionados = $(this).val(); // Obtener el valor seleccionado
    if (!Array.isArray(seleccionados)) {
      seleccionados = seleccionados ? [seleccionados] : []; // Asegurarse de que sea un array
    }
    
    seleccionados.forEach((value) => {
      valoresSeleccionadosProductos.push(value);
    });
  });

  // Obtener el último combo de "productos_combinados"
  const ultimoCombo = $(".productos_combinados").last();
  ultimoCombo.empty(); // Limpiar todas las opciones previas

  // Añadir las opciones seleccionadas en ".cmbMedicamento" que no estén en "productos_combinados"
  valoresSeleccionadosMedicamento.forEach((value) => {
    if (!valoresSeleccionadosProductos.includes(value)) {
      //const texto = $(`.cmbMedicamento option[value="${value}"]`).text(); // Obtener el texto correspondiente
      const texto = $(`.cmbMedicamento option[value="${value}"]:first`).text().trim();
      ultimoCombo.append($("<option>", { value: value, text: texto })); // Agregar al combo
      if (!productosSeleccionadosCombinacion.includes(value)) {
        productosSeleccionadosCombinacion.push(value);
      }
    }
  });

  // Aplicar Select2 al último combo
  ultimoCombo.select2({
    width: "resolve",
    max_selected_options: 4,
  });

  // Habilitar o deshabilitar el botón dependiendo de si hay valores seleccionados
  const boton = $("#addRecetaValeIndicacion");
  boton.prop("disabled", valoresSeleccionadosMedicamento.length === 0);
}
// Fin de AddProducto

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
  let dni_beneficiario = $('#nrodocafiliado').val();
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
        if (result.length > 0) {
          console.log(["result > 0",result]);
          for (let key of result) {
            if (n == 0) {
                msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.nombre_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de laboratorio pendientes del examen seleccionado en los últimos 3 meses, CANTIDAD : " + key.total_registros + "</h1>";
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
  let dni_beneficiario = $('#nrodocafiliado').val();
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
  
  function completarConCeros(numero, longitud) {
    let numeroString = numero.toString();
    while (numeroString.length < longitud) {
      numeroString = '0' + numeroString;
    }
    return numeroString;
  }
/*
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
*/
  function getCamas () { 
    const id_ipress = $('#id_establecimiento').val();
    const tipo_atencion = "HOSPITALIZACION";
    const id_area_destino = $('#id_area_destino').val();
    return new Promise((resolve, reject) => { 
      $.ajax({
        url: `/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto/${id_ipress}/${tipo_atencion}/${id_area_destino}`,
        //url: `/get_camas_por_id_cuarto/${id_ipress}/${id_area_destino}/2`,
        success: function(result) {
          resolve(result);
        }
      });
    })      
  }

  function buildOptionsSelect(arrayOptions, nameSelect) {
    $(`#${nameSelect}`).empty();  
    $(`#${nameSelect}`).append("<option value=''>-Seleccione-</option>");
    if (arrayOptions && arrayOptions.length > 0) {
      arrayOptions.forEach((option, index) => {
        $(`#${nameSelect}`).append(`<option value='${option.id}'>${option.nombre}</option>`);
      });
    }
    $(`#${nameSelect}`).val("").change();
  }

  async function generarCodigoSitedsHospitalizacion() {
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
    const datos_evaluacion = {!! json_encode($datos_evaluacion) !!};
    const body = {
        "idCorrelativo": datos_evaluacion.id,
        "aseguradoId": datos_evaluacion.id_asegurado,
        "establecimientoId": datos_evaluacion.id_establecimiento,
        "tipoAtencionId": "3", // Hospitalizacion
        "servicioId": datos_evaluacion.id_consultorio
    };
    return new Promise((resolve, reject) => {
      $.ajax({
        url:'/v2/ws-dirsapol-siteds',
        type:'POST',
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(body),
        success: (result, textStatus, jqXHR) => {
          $("#cod_siteds_susalud").val(result.nuAutorizacion)
          Swal.fire({
            icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
            //html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${result.message || "Código SITEDS actualizado correctamente"}</span></div>`,
            //confirmButtonText: 'ENTENDIDO',
            showConfirmButton: false,
            timer: '1500'
          });
          resolve(result);
        },
        error: function(xhr, status, error){
          $("#cod_siteds_susalud").val("");
          $("#cod_siteds_susalud").prop("readonly", false);
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
            Swal.fire({
                icon: 'error',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
            resolve(xhr.responseJSON);
        }, 
        complete: (xhr, status) => {
          Swal.hideLoading();
        }
      })
    });
  }

  $(document).ready(function () {
    let minDate = new Date({{date('Y', strtotime($hospitalizacione->fecha_ingreso))}}, {{date('m', strtotime($hospitalizacione->fecha_ingreso))}} - 1, {{date('d', strtotime($hospitalizacione->fecha_ingreso))}});
    $("#fecha_atencion").datepicker({
      useCurrent: false,
      dateFormat: "dd-mm-yy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left",
      maxDate: 0,
      minDate: minDate
    });

    $("#fecha_parto").datepicker({
      useCurrent: false,
      dateFormat: "dd/mm/yy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left",
      maxDate: 0
    });

    $('#hora_parto').inputmask("99:99", {
      placeholder: "00:00", 
      insertMode: false, 
      clearIncomplete: true,
      oncomplete: function() {
        validateHoraParto($(this).val());
      }
    });

    $("#fecha_inicio").datepicker({
      useCurrent: false,
      dateFormat: "dd/mm/yy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left"
    });

    $("#traslado_fecha").datepicker({
      useCurrent: false,
      dateFormat: "dd/mm/yy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left"
    });

    $('#traslado_hora').inputmask("99:99", {
      placeholder: "00:00", 
      insertMode: false, 
      clearIncomplete: true
    });

    handlerShowPanelSaludMaterna();

    $("#traslado_tipo").change(function () {
      handlerOnSelectTipoTransferencia($(this).val());    
    });
  });


  $("#tooglePuerpera").on("click", function () {
    const toogleValue = String($(this).attr("aria-pressed")) === "false" ? true : false;
    $("#frmPuerperaActive").val(String(toogleValue));
    handleSectionCollapse(toogleValue, "frmPuerpera");
    handleSectionCollapse(toogleValue, "fieldEventosEp");
  });

  $("#toogleTraslado").on("click", async function () {
    const toogleValue = String($(this).attr("aria-pressed")) === "false";
    handleSectionCollapse(toogleValue, "frmTraslado");
    $("#frmTrasladoActive").val(String(toogleValue));

    if (toogleValue) {
      const dato_cama = {!! json_encode($dato_cama) !!}

      $("#fecha_inicio").datepicker("setDate", moment().format("DD/MM/YYYY"));
      $("#appt").val(moment().format("HH:mm"));

      $('#id_area_destino').val(parseInt(dato_cama.id_consultorio)).trigger('change');

      const camas = await getCamas();
      buildOptionsSelect(camas, 'id_cama_destino');
      $('#id_cama_destino').val(parseInt(dato_cama.id)).trigger('change');
    }
  });

  $("#hora_parto").on("input", function () {
    console.log($(this).val());
  });

  function handlerShowPanelSaludMaterna() {
    const hospitalizacion = {!! json_encode($datos_evaluacion) !!};
    if (hospitalizacion) {
      if (String(hospitalizacion.sexo) === "F" && (parseInt(hospitalizacion.id_consultorio) === 77 || parseInt(hospitalizacion.id_consultorio) === 131) && (parseInt(hospitalizacion.edadpaciente) >= 12) && (parseInt(hospitalizacion.edadpaciente) <= 55)) {
        $("#panelPuerpera").show();
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

  function handlerOnSelectTipoTransferencia(tipoId) {
    let contentHtmlUpssDestino = "";
    clearFormTransferencia();

    const listUpss = {!! json_encode($listUpss) !!};
    let optionsSelectUpssDestino = "";
    if (listUpss && listUpss.length > 0) {
      listUpss.forEach((upss) => {
        optionsSelectUpssDestino += `<option value="${upss.id_upss}">${upss.nombre}</option>`
      });
    }

    if (parseInt(tipoId) === 1) {
      $('#operacion_propuesta1').show();
      $('#diagnostico_traslado').hide();
      
      contentHtmlUpssDestino = `<label for="traslado_upss_destino">SERVICIO UBICACION</label>
                                  <select name="traslado_upss_destino" id="traslado_upss_destino" class="form-control">
                                    <option value="26" selected>CENTRO QUIRURGICO</option>
                                  </select>`;
      $('#formGroupTrasladoUppsDestino').html(contentHtmlUpssDestino).show();

    }
    if (parseInt(tipoId) === 2) {
      $('#operacion_propuesta1').hide();
      $('#diagnostico_traslado').show()
      contentHtmlUpssDestino = `<label for="traslado_upss_destino">SERVICIO UBICACION</label>
                                <select name="traslado_upss_destino" id="traslado_upss_destino" class="form-control">
                                  <option value="">-- SELECCIONAR DESTINO --</option>
                                  ${optionsSelectUpssDestino}
                                </select>`;
      // contentHtmlUpssDestino = `<label for="traslado_upss_destino">SERVICIO DESTINO</label>
      //                           <select name="traslado_upss_destino" id="traslado_upss_destino" class="form-control">
      //                           </select>`;

      $('#formGroupTrasladoUppsEncargado').show();
      $('#formGroupTrasladoServicioEncargado').show();
      $('#formGroupTrasladoUppsDestino').html(contentHtmlUpssDestino).show();
      $("#formGroupCama").show();
      $("#formGroupFechaTranslado").show();
      $("#formGroupHoraTranslado").show();

      $("#traslado_upss_destino").select2({ width: '100%' }).on('change', async function(e) {
        const selectedValue = $(this).val();
        if (selectedValue) {
          $('#traslado_cama').empty();
          $("#traslado_cama").append('<option value="">-- SELECCIONAR CAMA --</option>');

          const camas = await getCamasByIdConsultorio(selectedValue);
          if($("#select_cambiar_cama").val() == 2){
            if($("#id_area").val() == selectedValue){
              var valor = $("#id_cama").val();
              var texto = $("#id_cama option:selected").text();
              $("#traslado_cama").append('<option value="' + valor + '">' + texto + '</option>');
            }
          }
          if (camas && camas.length > 0) {
            $.each(camas, function(index, cama) {
              $('#traslado_cama').append(`<option value="${cama.id}">${cama.nombre_cama}</option>`);
            });
          }
        }
      });
    }
  }

  function clearFormTransferencia() {
    $('#formGroupTrasladoUppsDestino').empty();
    $("#traslado_cama").val("");
    $("#traslado_fecha").val("");
    $("#traslado_hora").val("");

    $("#formGroupTrasladoUppsEncargado").hide();
    $("#formGroupTrasladoServicioEncargado").hide();
    $("#formGroupTrasladoUppsDestino").hide();
    $("#formGroupCama").hide();
    $("#formGroupFechaTranslado").hide();
    $("#formGroupHoraTranslado").hide();

    $('#traslado_upss_destino').select2('destroy');

    $('#traslado_cama').prop('disabled', true);
  }

  function onAddRowTableDiagTraslado() {
    const isValidLastRow = validateLastRowTableDiagTraslado();
    if (!isValidLastRow) {
      Swal.fire({
        icon: "warning",
        title: '<p>EVOLUCIONES MEDICAS</p>',
        html: '<p>TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO DIAGNOSTICO</p>',
        confirmButtonText: "ENTENDIDO"
      });
      return null;
    }

    const indexRow = $('#tableDiagTraslado tbody tr').length + 1;
    const newRow = `
                    <tr>
                      <td>
                        <input type="text" name="traslado_diagnostico[]" id="traslado_diagnostico${indexRow}" class="form-control">
                      </td>
                      <td>
                        <select name="traslado_diagnostico_tipo[]" id="traslado_diagnostico_tipo${indexRow}" class="form-control">
                          <option value="">-- Seleccionar --</option>
                          <option value="1">PRESUNTIVO</option>
                          <option value="2">DEFINITIVO</option>
                          <option value="3">REITERATIVO</option>
                        </select>
                      </td>
                      <td style="text-align: center"><button id="btnRemoveRowDiagTraslado" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>
                      <td><input type="hidden" name="id_traslado_diagnostico[]" id="id_traslado_diagnostico${indexRow}"></td>
                    </tr>`;
    $('#tableDiagTraslado tbody').append(newRow);

    $(`#traslado_diagnostico${indexRow}`).autocomplete({
      minLength: 1,
      source: function(request, response) {
        $.ajax({
          url: `/obtener_diagnostico_genero/${request.term}/${$('#nomsexo').val() === "M" ? 1 : 2}`,
          type: "GET",
          dataType: "json",
          success: function(data)  {
            response(data.map(function(item) {
              return { id: item.id, label: item.label};
            }));
          },
          error: function() {
            response([]);
          }			
        })
      },
      select: function(event, ui) {
        $(this).val(ui.item.label);
        $(`#id_traslado_diagnostico${indexRow}`).val(ui.item.id);
      }
    });

    $(`#traslado_diagnostico${indexRow}`).on('input', function() {
        $(`#id_traslado_diagnostico${indexRow}`).val("");
    });

    $(`#traslado_diagnostico${indexRow}`).blur(function() {
      const id_traslado_diagnostico = $(`#id_traslado_diagnostico${indexRow}`).val();
      clearInputError(`traslado_diagnostico${indexRow}`)
      if (!id_traslado_diagnostico) {
        markInputError(`traslado_diagnostico${indexRow}`, "espere los resultados y seleccione uno")
      }
    });

    $(`#traslado_diagnostico_tipo${indexRow}`).blur(function() {
      const traslado_diagnostico_tipo = $(`#traslado_diagnostico_tipo${indexRow}`).val();
      if (traslado_diagnostico_tipo) {
        clearInputError(`traslado_diagnostico_tipo${indexRow}`);
      } else {
        markInputError(`traslado_diagnostico_tipo${indexRow}`);
      }
    });

    $('#tableDiagTraslado').on('click', '#btnRemoveRowDiagTraslado', function(){
      const row = $(this).closest('tr');
      row.remove();
    });
  }

  function validateLastRowTableDiagTraslado() {
    const lastRow = $("#tableDiagTraslado tbody tr:last");
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

  function getCamasByIdConsultorio(idConsultorio) {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: `/obtener_cama_por_idcuarto/${idConsultorio}`,
        dataType: "json",
        beforeSend: function() {
          $('#traslado_cama').prop('disabled', true);
          markInputError("traslado_cama", "cargando resultados...")
        },
        success: function (response) {
          resolve(response)
        },
        error: function (error) {
          reject(error)
        },
        complete: function() {
          $('#traslado_cama').prop('disabled', false);
          clearInputError("traslado_cama");
        }
      });
    });
  }

  $("#fecha_inicio").on("change", function() {
    const value_selected = $(this).val();
    if (String(value_selected) !== moment().format("DD/MM/YYYY")) {
      handlerDisabledTraslado();
    }
  });

  $("#appt").blur(function() {
    const valor = $(this).val();
    if (String(valor) !== moment().format("HH:mm") ) {
      handlerDisabledTraslado();
    }
  });

  $("#id_area_destino").on('select2:select', async function(e) {
    const value_selected = $(this).val();

    if (value_selected && String(value_selected) !== "" && String(value_selected) !== "0") {
      const dato_cama = {!! json_encode($dato_cama) !!}

      if (parseInt(dato_cama.id_cuarto) !== parseInt(value_selected)) {
        handlerDisabledTraslado();
      }

      const camas = await getCamas();
      buildOptionsSelect(camas, 'id_cama_destino');
    }
  });

  $("#id_cama_destino").on('select2:select', async function(e) {
    const dato_cama = {!! json_encode($dato_cama) !!}
    const value_selected = $(this).val();
    if (parseInt(dato_cama.id) !== parseInt(value_selected)) {
      handlerDisabledTraslado();
    }
  });

  function handleToogleBtn(valueToogle, nameToogle) {
    if (valueToogle) {
      $(`#${nameToogle}`).addClass('active');
      $(`#${nameToogle}`).attr('aria-pressed', 'true');
    } else {
      $(`#${nameToogle}`).removeClass('active');
      $(`#${nameToogle}`).attr('aria-pressed', 'false');
    }
  }

  function handlerDisabledTraslado() {
    handleToogleBtn(false, "toogleTraslado");
    handleSectionCollapse(false, "frmTraslado");
    $("#frmTrasladoActive").val("false");
  }

  $("#select_cambiar_cama").change(function (e) {
    e.preventDefault();
    let valor_cambiar_cama = $(this).val();
    switch (valor_cambiar_cama) {
      case "0":
        $("#div_campos_rotacion").hide();
        $("#frmTraslado").hide();
        //handleSectionCollapse(false, "panelTraslado");
        break;
      case "1":
        $("#frmTraslado").hide();
        //handleSectionCollapse(false, "panelTraslado");
        $("#div_campos_rotacion").show();
        break;
      case "2":
        $("#div_campos_rotacion").hide();
        $("#panelTraslado").show();
        $("#frmTraslado").show();
        //handleSectionCollapse(true, "panelTraslado");
        break;
    }
  });

  $("#traslado_id_upss_encargado").change(function (e) {
    e.preventDefault();
    let id_upss = $(this).val();
    $('#traslado_id_servicio_encargado').html('');
    // $('#traslado_upss_destino').html('');
    $.ajax({
      url: '/obtener_servicio_by_id_upss2/'+ id_upss,
      success: function(result){                    
        let newOption = "<option value='0'>--SELECCIONAR--</option>"; 
        if (result.length === 1) {
          newOption += "<option value='"+result[0].id+"' selected>"+result[0].servicio+"</option>";
          $('#traslado_upss_destino').val(result[0].id).change();
        } else {
          $(result).each(function (ii, oo) {
              newOption += "<option value='"+oo.id+"'>"+oo.servicio+"</option>";
          });
        }
        $('#traslado_id_servicio_encargado').html(newOption).change();

        // let newOption2 = "<option value=''>--SELECCIONAR--</option>"; 
        // if (result.length === 1) {
        //   newOption2 += "<option value='"+result[0].id+"' selected>"+result[0].servicio+"</option>";
        // } else {
        //   $(result).each(function (ii, oo) {
        //       newOption2 += "<option value='"+oo.id+"'>"+oo.servicio+"</option>";
        //   });
        // }
        // $('#traslado_upss_destino').html(newOption).change();
        $('#traslado_upss_destino').focus();
      }
    }); 
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

  <?php if($nivel == 3): ?>
  		var grupos = [
	        ["82803.01", "36600"],
	        // ["82575", "82565"],
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






  /*  function getCamasDestinoByCuarto(id_cuarto) {
    const id_establecimiento = $("#id_establecimiento").val();
    $.ajax({
      type: "GET",
      url: `/get_camas_por_id_cuarto/${id_establecimiento}/${id_cuarto}/2`,
      dataType: "json",
      success: function (response) {
        buildOptionsSelectCamaDestino(response); 
      },
      error: function (error) {
        console.log(error);
      }
    });
  }

  function buildOptionsSelectCamaDestino(listCama) {
    $('#id_cama_destino').empty();
    if (listCama && listCama.length >  0) {
      $("#id_cama_destino").append('<option value="">-Seleccione-</option>');
      listCama.forEach((cama) => {
        $("#id_cama_destino").append(`<option value="${cama.id}">${cama.nombre}</option>`);
      });
      $('#id_cama_destino').val("").change(); 
    } else {
      $('#id_cama_destino').append("<option value=''>-Seleccione-</option>"); 
      $('#id_cama_destino').val("").change(); 
    }
  }

*/
  async function getLastRecipeOnHospi() {
    const id_asegurado = $("#id_asegurado").val();
    Swal.fire({
      title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Buscando Receta</span></div>",
      html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 16px'>Espere un momento por favor ...</span></div>",
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => Swal.showLoading()
    });
    try {
      const id_prestacion = $("#id_prestacion").val();
      const response = await $.ajax({
        type: "GET",
        url: `/api/ultima-receta/prestacion/${id_prestacion}`,
        dataType: "json"
      });
      if (response && response.data && response.data.productos.length > 0 && response.data.id_farmacia) {
        await getProducto(response.data.id_farmacia); // Se espera correctamente
        $('#example tbody').empty();
        $('#id_farmacia_receta').val(response.data.id_farmacia).trigger('change');
        for (const producto of response.data.productos) {
          AddProducto(producto);
        }
      }
      Swal.close();
    } catch (xhr) {
      const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Ocurrió un problema al anular la interconsulta.";
      Swal.fire({
        title: 'Error',
        text: messageError,
        icon: 'error',
        timer: 4000,
        timerProgressBar: true
      });
    } finally {
      Swal.hideLoading();
    }
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
            const tbody_tblInterconsulta = document.querySelector("#tblInterconsultaEv tbody");
            tbody_tblInterconsulta.innerHTML = "";
          } else {
            $("#is_estable").val(is_estable_auxiliar);
          }
        }
      });
    }

  });

  /***********OTROS DATOS PARA CENTRO QUIRURGICO**************/

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
    if(electiva=='1'){
      $('#electiva').show();
      $('#duracion_cirugia_calculada').show();
      $('#duracion_cirugia_calculada').prop('disabled', true); 
      $('#hora_minuto').hide();
      $('#duracion_cirugia').val(0);
      $('#horas').val(0);
    }else{
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
  //OTROS DATOS PARA CENTRO QUIRURGICO

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
        console.log("ITEM EJEMPLO:", data[0]); 
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
          // "82575": "82565",
          // "82565": "82575",
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
    if (faltantes != "") alert(faltantes);*/
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

      const nivel = $("#id_nivel").val();
      const checked = $("input[name='chk_rualab[]']:checked");
      if (checked.length === 0) {
          $("#modalDX").modal('hide');
          return;
      }

      checked.each(function () {
          const $this = $(this);
          const id_examen_lab = $this.data('id');
          const codigo = $this.data('codigo');
          const categoria = $this.data('categoria');
          const label = $this.data('label');

          if ($("#tblExamenLab tbody tr[data-id='" + id_examen_lab + "']").length > 0) {
              return true; 
          }

          const ind = obtenerIndiceTblExamenLab();

          let rowHtml = `
              <tr data-id="${id_examen_lab}">
                <td>
                    <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required id="nombre_examen_lab${ind}" class="input-sm" style="width:100%; text-align:left" value="${label}" readonly />
                    <input type="hidden" class="id_laboratorio" id="id_examen_lab${ind}" name="id_examen_lab[]" data-id="${ind}" value="${id_examen_lab}">
                    <input type="hidden" class="cod_lab" id="cod_lab${ind}" name="cod_lab[]" data-id="${ind}" value="${codigo}">
                    <input type="hidden" class="id_categoria" id="id_categoria_examen_lab${ind}" name="id_categoria_examen_lab[]" data-id="${ind}" value="${categoria}">
                </td>`;
          if (nivel && parseInt(nivel) < 3) {
            rowHtml += `
                <td>
                  <input type="text" placeholder="Observacion" class="form-control examen_lab_observacion" id="examen_lab_observacion${ind}" name="examen_lab_observacion[]">
                </td>`;
          }
          rowHtml += `
                <td>
                  <input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad" id="examen_lab_cantidad${ind}" name="examen_lab_cantidad[]" value="1">
                </td>
                <td>
                  <select class="form-control select-diagnostico" id="miSelectLab${ind}" name="miSelectLab[]">
                    <option value="">-- Seleccionar --</option>
                  </select>
                </td>
                <td>
                    <button type="button" class="btn btn-success btn-xs tipoMuestraMicrobiologia" id="tipoMuestraMicrobiologia${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Tipo de muestra</button>
                    <input type="hidden" id="descripcion_tipo_muestra${ind}" name="descripcion_tipo_muestra[]" data-id="${ind}" value="">
                    <input type="hidden" id="flag_descripcion_tipo_muestra${ind}" name="flag_descripcion_tipo_muestra[]" value="0">
                    <button type="button" class="btn btn-warning btn-xs editarTipoMuestraMicrobiologia" id="editarTipoMuestraMicrobiologia${ind}" data-id="${ind}" style="display:none; margin-left:4px"><i class="fa fa-edit"></i>&nbsp;Tipo de muestra</button>
                </td>
                <td>
                    <button type="button" class="btn btn-success btn-xs solicitudExamenEspecializadoHemocultivoAdulto" id="solicitudExamenEspecializadoHemocultivoAdulto${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>
                    ${crearHiddenFields(camposAdulto, ind)}
                    ${crearHiddenFields(camposPed, ind)}
                    ${crearHiddenFields(camposCito, ind)}
                    <button type="button" class="btn btn-warning btn-xs editarSolicitudExamenEspecializadoHemocultivoAdulto" id="editarSolicitudExamenEspecializadoHemocultivoAdulto${ind}" data-id="${ind}" style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>
                </td>
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
                  opt.value = item.id_diagnostico;
                  opt.textContent = item.nombre;
                  select.appendChild(opt);
              });
          }
      });

      $("#modalDX").modal('hide');
      $("input[name='chk_rualab[]']").prop('checked', false);
  });

</script>
<script type="text/javascript">
/***********OTROS DATOS PARA CENTRO QUIRURGICO**************/
 $('#addDiagnosticoQuirurgico').on('click', function() {
    AddDiagnosticoQuirurgico();
  });

function AddDiagnosticoQuirurgico(){
  var newRow = "";
  var ind = $('#tblDiagnosticoQuirurgico tbody tr').length;
  
  if(ind > 0){
    $('.id_diagnostico_quirurgico').each(function(){
      var ind_tmp = $(this).data("id");
      if(ind_tmp => ind){
        ind=Number(ind_tmp)+1;
      }
    });
          
    fila_medicamentos=$('#tblDiagnosticoQuirurgico tbody').children('tr');
    rowIndex=$('#hidden_rowIndex').val();
    var idval = ind - 1;
    fila_medicamento=fila_medicamentos[idval];
    
  }
  
  var item_tipo_diagnostico = '';

  item_tipo_diagnostico+='<option value="2">DEFINITIVO</option>';

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_quirurgico[]" tabindex="8" required="" id="nombre_diagnostico_quirurgico'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control id_diagnostico_quirurgico typeahead tt-query" id="id_diagnostico_quirurgico'+ind+'" name="id_diagnostico_quirurgico[]" value="" data-id='+ind+'></td>';

  newRow +='<td><select class="form-control id_tipo_diagnostico_quirurgico" id="cmbTipoDiagnosticoQuirurgico'+ind+'" name="id_tipo_diagnostico_quirurgico[]" style="margin:0 20px;width:150px">'+item_tipo_diagnostico+'</select></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='</tr>';
  $('#tblDiagnosticoQuirurgico tbody').append(newRow);

  var sexo  = $('#nomsexo').val();
  if(sexo=='M'){
    genero=1;
  }
  else{
    if(sexo=='F'){
      genero=2;
    }
  }
  
  $("#nombre_diagnostico_quirurgico"+ind).autocomplete({
    minLength: 1,
    delay : 400,
    source: function(request, response) {
      $.ajax({
        //url: '../obtener_diagnostico/' + request.term,
        url: '../../obtener_diagnostico_genero/' + request.term + '/'+ genero,
        dataType: "json",
        success: function(data)  {
          let listaDiagnosticoSeleccionadoCQX = $(".id_diagnostico_quirurgico").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaDiagnosticoSeleccionadoCQX.includes(element.id));
          response(filtro);
        }         
      })
  
    },select:  function(e, ui) {
      var id_diagnostico = ui.item.id;
      $("#id_diagnostico_quirurgico"+ind).val(id_diagnostico);
      $("#nombre_diagnostico_quirurgico"+ind).prop("readonly",true);
    }
  });
  
  $("#nombre_diagnostico_quirurgico"+ind).on('blur', function() {
    const diagnostico = $("#nombre_diagnostico_quirurgico"+ind).val();
  
    const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
    const codigoDiagnostico = diagnostico.split("-")[0].trim();
    var id_diagnostico = $("#id_diagnostico_quirurgico"+ind).val();
    if(id_diagnostico == ""){
      bootbox.alert("Falta ingresar el diagnostico en esta fila");
      $("#nombre_diagnostico_quirurgico"+ind).val("");
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

$('#addProcedimientoIntervencion').on('click', function () {
		AddProcedimientoIntervencion();
	});


function AddProcedimientoIntervencion() {
    let newRow = "";
    let ind = $('#table_procedimiento_intervencion tbody tr').length;
    
    let item_procedimiento = "";
    $('#cmbProcedimientoIntervencionTemp option').each(function() {
      item_procedimiento += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
    });

    newRow +='<tr>';
    newRow +='<td><select class="form-control cmbProcedimientoIntervencion" id="cmbProcedimientoIntervencion' + ind + '" ind="' + ind + '" name="id_procedimiento_intervencion[]" >' + item_procedimiento + '</select></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:10px;"><i class="fa fa-times"></i> </button></td>';

    newRow +='</tr>';
    $('#table_procedimiento_intervencion tbody').append(newRow);
    $("#cmbProcedimientoIntervencion" + ind).select2({max_selected_options: 4}).focus();
    
    $("#cmbProcedimientoIntervencion"+ind).on("change", function (e) {
      var flagx = 0;
      cmb = $(this);
      id_producto = $("#cmbProcedimientoIntervencion"+ind).val();
      id_user=<?php echo Auth::user()->id;?>;
      
      $('.cmbProcedimientoIntervencion').each(function(){
        var ind_tmp = $(this).val();
        if($(this).val() == id_producto)flagx++;
      });
      
      if(flagx > 1){
        bootbox.alert("El Procedimiento ya ha sido ingresado");
        $("#cmbProcedimientoIntervencion"+ind).val("").trigger("change");
        return false;
      }
    
    });
    item_procedimiento++;
  }

$('#addMedicoCirujano').on('click', function () {
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
    
    newRow +='<tr>';
    newRow +='<td><select class="form-control cmbMedicoCirujano" id="cmbMedicoCirujano' + ind + '" ind="' + ind + '" name="id_medico_cirujano[]" >' + item_producto + '</select></td>';
    newRow +='<td><select class="form-control cargoMedicoCirujano" id="cmbMedicoCirujanoCargo'+ind+'" name="id_medico_cirujano_cargo[]" style="margin-left:20px;">'+item_cargo+'</select></td>';
    
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:30px;"><i class="fa fa-times"></i> </button></td>';

    newRow +='</tr>';
    $('#table_medico_cirujano tbody').append(newRow);
    $("#cmbMedicoCirujano" + ind).select2({max_selected_options: 4}).focus();
    
    $("#cmbMedicoCirujano"+ind).on("change", function (e) {
      var flagx = 0;
      cmb = $(this);
      id_producto = $("#cmbMedicoCirujano"+ind).val();
      id_user={{Auth::user()->id}};
      
      $('.cmbMedicoCirujano').each(function(){
        var ind_tmp = $(this).val();
        if($(this).val() == id_producto)flagx++;
      });
      
      if(flagx > 1){
        bootbox.alert("El Médico ya ha sido ingresado");
        $("#cmbMedicoCirujano"+ind).val("").trigger("change");
        return false;
      }
    
    });
    filasmedicamentos++;
  }

   $('#tblDiagnosticoQuirurgico tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
   });

   $('#table_procedimiento_intervencion tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
    var fila = obj.closest('tr');
    var filaPosition = fila.index();
    obj.parent().parent().remove();
  });

  $('#table_medico_cirujano tbody').on('click', 'button.deleteFila', function () {
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
  //OTROS DATOS PARA CENTRO QUIRURGICO
  $(function () {
    $('[data-toggle="popover"]').popover();
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
      // let inputIdHoraTomaMuestraAux = document.getElementById("id_hora_toma_muestra").value;
      // let inputLabOrdenObservacionAux = document.querySelector('input[name="lab_orden_observacion"]').value;
      
      document.getElementById("id_hora_toma_muestra").value = String(stringIdHoraTomaMuestraSeleccionada);

      /*if (String(inputIdHoraTomaMuestraAux) == "") {
        document.querySelector('input[name="lab_orden_observacion"]').value = descripcionHoraTomaMuestra + " - " + inputLabOrdenObservacionAux;
      } else {
        // const resultado = labOrdenObservacionAux.slice(0, labOrdenObservacionAux.indexOf("-"));
        const soloLabOrdenObservacion = inputLabOrdenObservacionAux.slice(inputLabOrdenObservacionAux.indexOf("-") + 2);
        console.log(soloLabOrdenObservacion);
        document.querySelector('input[name="lab_orden_observacion"]').value = descripcionHoraTomaMuestra + " - " + soloLabOrdenObservacion;
      }*/

      document.getElementById("lab_orden_toma_muestra").value = descripcionHoraTomaMuestra;
    });
  });
</script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
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
    font-size: 11px !important;
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

  /* .modal-dialog {
    width: 1200px !important;
  } */
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

  div#fsSignosVitales label,
  div#fsSignosVitales label span {
    font-size: 12px;
  }

  .ui-autocomplete-loading {
        background: white url('{{ asset('images/loader.gif') }}') no-repeat right center !important; 
    }

    .disabled-select {
  background-color: #d5d5d5;
  opacity: 0.5;
  border-radius: 3px;
  cursor: not-allowed;
  position: absolute;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
}

select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
  background: #eee;
  box-shadow: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow,
select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
  display: none;
}
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

</style>
@endsection
@section('content')

@if (session('mensaje_error'))
    <div style="background-color: #f8d7da;color: #a94442;border: 1px solid #ebccd1;border-radius: 4px;padding: 10px 15px;font-size: 14px;margin: 10px 0;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        {{ session('mensaje_error') }}
    </div>
@endif

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
                            <h3 class="panel-title">MÓDULO DE EVOLUCIONES MEDICAS</h3>
                          </div>
                          <div class="panel-body">
                            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->
                            <form class="form-horizontal" method="post" action="{{ route('registrar_evolucion_hospitalizacion')}}"
                              id="frmPrestacion" name="frmPrestacion" autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="id_nivel" id="id_nivel" value="{{$nivel}}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                              <input type="hidden" name="estado" id="estado" value="{{$estado}}">
                              <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$datos_evaluacion->id_establecimiento}}">
                              <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="{{$id_tipo_atencion}}">
                              <input type="hidden" name="id_asegurado" id="id_asegurado"  value="{{$datos_evaluacion->id_asegurado}}">
                              <input type="hidden" name="id_servicio" id="id_servicio" value="{{$id_servicio}}">

                              <input type="hidden" name="id_servicio_encargado" id="id_servicio_encargado" value="{{$hospitalizacione->id_servicio_encargado}}">

                              <input type="hidden" name="id_medico" id="id_medico" value="{{$datos_evaluacion->id_medico}}">
                              <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$id_prestacion}}">
                              <input type="hidden" name="edadpaciente" id="edadpaciente" value="{{$datos_evaluacion->edadpaciente}}">
                              <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" value="{{$id_hospitalizacion}}">
                              <input type="hidden" name="turno" id="turno" value="{{$turno}}">
                              <input type="hidden" value="{{$datos_evaluacion->nro_doc_ident}}" name="dni_beneficiario" id="dni_beneficiario" class="form-control">
                              <input type="hidden" name="nro_historia" id="nro_historia" value="{{$datos_evaluacion->nro_historia}}">
                              <input type="hidden" name="codigo_upss" id="codigo_upss" value="{{$upss_encargado->codigo}}">
                              <input type="hidden" value="{{$dato_cama->id}}" name="id_cama_actual" id="id_cama_actual">
                              
                              <!-- <input type="text" value="<?php //echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_atencion" id="fecha_atencion"> -->
                              <!-- <input type="text" value="{{$fechaServidor}}" name="fecha_hora_atencion"> -->
                              <input type="hidden" id="frmPuerperaActive" name="frmPuerperaActive" value="false">
                              <input type="hidden" id="frmTrasladoActive" name="frmTrasladoActive" value="false">
                              <input type="hidden" name="entidad_firma" id="entidad_firma" value="{{Auth::user()->dni}}">
                              <input type="hidden" name="id_evaluacion" id="id_evaluacion">
                              <input type="hidden" name="argumentos_orden_traslado" id="argumentos_orden_traslado" value="" />
                              <input type="hidden" name="doc_orden_traslado" id="doc_orden_traslado">
                              <input type="hidden" name="opt_orden_traslado" id="opt_orden_traslado">
                              <input type="hidden" id="user_orden_traslado" value="{{ Auth::user()->dni }}" />
                              <input type="hidden" name="flag_orden_traslado" id="flag_orden_traslado" value="1">
                              <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
                              <input type="hidden" id="regularizar_datos" name="regularizar_datos" value="{{$regularizar}}">
                              <input type="hidden" id="upss_cargo" name="upss_cargo" value="{{ $upss_cargo }}">
                              <input type="hidden" id="servicio_medico" name="servicio_medico" value="{{ $servicio_medico }}">
                              <input type="hidden" name="id_principal" id="id_principal" value="{{$id_principal}}">
                              <input type="hidden" name="id_hora_toma_muestra" id="id_hora_toma_muestra" />
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
                                <div class="panel-body" id="collapseprestacionespaciente">
                                  <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="col-md-2">
                                          <label>FECHA DE INGRESO</label>
                                          <p><?php echo date("d-m-Y", strtotime($hospitalizacione->fecha_ingreso)); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                          <label>FECHA DE ATENCION</label>
                                          <!-- <p><?php //echo date("d-m-Y", strtotime($fechaServidor)); ?></p> -->
                                          <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($fechaServidor)) }}" name="fecha_atencion" id="fecha_atencion" readonly style="background-color: #ffffff !important">
                                        </div>
                                        <div class="col-md-2">
                                          <label>HORA DE ATENCIÓN</label>
                                          <input type="time" name="hora_atencion" id="hora_atencion" value="{{ date('H:i', strtotime($fechaServidor)) }}" class="form-control" required>
                                        </div>
                                        <div class="col-md-2">
                                          <label>N° HISTORIA CLINICA</label>
                                          <p>{{$datos_evaluacion->nro_historia}}</p>
                                        </div>
                                        {{--<div class="col-md-3">
                                          <label for="dni_beneficiario">{{$descripcion}}</label>
                                          <p>{{$dato_cama->consultorio}}</p>
                                        </div>
                                        <div class="col-md-3">
                                          <label>{{$descripcion2}}</label>
                                          <p></p><!-- $datos_evaluacion->consultorio  -->
                                        </div>
                                        <div class="col-md-2">
                                          <label for="dni_beneficiario">CODIGO UPSS</label>
                                          <p>{{$codigo_upss}}</p>
                                        </div>--}}
                                        <?php 
                                          /*$is_valid_cod_siteds = true;
                                          if (!isset($codigo_siteds) || substr($codigo_siteds, -1) === 'T' || strlen($codigo_siteds) <= 10) {
                                            $is_valid_cod_siteds = false;
                                          }*/
                                        ?>
                                        <div class="col-md-offset-1 col-md-2">
                                          
                                            <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                                          <input 
                                            type="text" 
                                            class="form-control" 
                                            id="cod_siteds_susalud" 
                                            name="cod_siteds_susalud"
                                            onkeypress="return esEntero(event);"
                                            style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"
                                            maxlength="12" 
                                            pattern="[0-9]*" 
                                            
                                            readonly
                                          >
                                          <input type="hidden" value="{{$codigo_siteds_valido}}" id="codigo_siteds_valido">
                                        </div>
                                      </div>
                                      {{--
                                      <div class="col-md-12">
                                        <div class="col-md-2">
                                          <label for="tipo">PABELLON</label>
                                          <p>{{$dato_cama->pabellon}}</p>
                                        </div>
                                        <div class="col-md-2">
                                          <label for="dni_beneficiario">PISO</label>
                                          <p>{{$dato_cama->piso}}</p>
                                        </div>
                                        <div class="col-md-3">
                                          <label>HABITACION</label>
                                          <p>{{$dato_cama->cuarto}}</p>
                                        </div>
                                        <div class="col-md-3">
                                          <label>CAMA</label>
                                          <p>{{$dato_cama->cama}}</p>
                                        </div>
                                      </div>
                                      --}}
                                      <div class="col-md-12">
                                        @if ($regularizar == "0")
                                        <div class="col-md-4">
                                          <label>UPSS A CARGO</label>
                                          <p>{{$upss_encargado->codigo}} - {{$upss_encargado->descripcion_upss}}</p>
                                          <input type="hidden" name="id_upss_encargado" value="{{$hospitalizacione->id_upss_encargado}}">
                                          <input type="hidden" name="id_servicio_encargado" value="{{$hospitalizacione->id_servicio_encargado}}">
                                        </div>
                                        @else
                                        <div class="col-md-4">
                                          <label for="dni_beneficiario">UPSS A CARGO</label>
                                          <select name="id_upss_encargado_regularizacion" id="id_upss_encargado_regularizacion" style="width:100%">
                                            <option value="0">--SELECCIONAR--</option>
                                            @foreach($upsses as $r)
                                              <option value="{{$r->id_upss}}">{{$r->codigo_upss}} - {{$r->descripcion_upss}}</option>
                                            @endforeach
                                          </select>
                                          <input type="hidden" id="codigo_upss_regularizacion" name="codigo_upss_regularizacion">
                                        </div>
                                        <div class="col-md-2">
                                          <label>DEPARTAMENTO A CARGO</label>
                                          <select name="id_servicio_encargado_regularizacion" id="id_servicio_encargado_regularizacion" style="width:100%">
                                          </select>
                                        </div>
                                        @endif
                                        <div class="col-md-4">
                                          <label>SERVICIO ACTUAL</label>
                                          <p>{{$servicio_ubicacion->nombre}}</p>
                                        </div>
                                        <div class="col-md-2">
                                          <label>CAMA</label>
                                          <p>{{$dato_cama->cama}}</p>
                                        </div>
                                      </div>
                                      
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>INFORMACIÓN DEL PACIENTE</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label for="tipo">DOCUMENTO DE IDENTIDAD</label>
                                        <input tabindex="1" readonly="" type="text" class="form-control"
                                            value="{{$datos_evaluacion->tipo_doc_ident}}: {{$datos_evaluacion->nro_doc_ident}}" name="tipodoc_beneficiario_bus" id="tipodoc_beneficiario_bus" style="text-align:left">
                                        <input type="hidden" class="form-control" value="{{$datos_evaluacion->tipo_doc_ident}}" name="tipodoc_beneficiario" id="tipodoc_beneficiario" style="text-align:left">
                                        <input type="hidden" class="form-control" value="{{$datos_evaluacion->nro_doc_ident}}" name="nrodocafiliado" id="nrodocafiliado" readonly="" />
                                      </div>
                                      <div class="col-md-3">
                                        <label>APELLIDO PATERNO</label>
                                        <input tabindex="4" readonly="" type="text" class="form-control" value="{{$datos_evaluacion->asegurado_paterno}}" name="paterno_beneficiario" id="paterno_beneficiario" style="text-align:left">
                                      </div>
                                      <div class="col-md-3">
                                        <label>APELLIDO MATERNO</label>
                                        <input tabindex="5" readonly="" type="text" class="form-control" value="{{$datos_evaluacion->asegurado_materno}}" name="materno_beneficiario" id="materno_beneficiario" style="text-align:left">
                                      </div>
                                      <div class="col-md-3">
                                        <label>NOMBRES</label>
                                        <input tabindex="6" readonly="" type="text" class="form-control" value="{{$datos_evaluacion->asegurado_nombre}}" name="nombre_beneficiario" id="nombre_beneficiario" style="text-align:left">
                                      </div>
                                    </div>
                                    <div class="form-group">
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
                                      <div class="col-md-2">
                                        <label>FECHA DE NACIMIENTO</label>
                                        <input tabindex="13" readonly="" type="text" class="form-control" value="{{$datos_evaluacion->fecha_nac}}" name="fecnacafiliado" id="fecnacafiliado" style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label>PARENTESCO &nbsp;</label>
                                        <input tabindex="14" readonly="" value="{{$datos_evaluacion->parentesco}}" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
                                      </div>
                                      <div class="col-md-2">
                                        <label for="cip">CIP</label>
                                        <input tabindex="15" readonly="" type="text" class="form-control" value="{{$datos_evaluacion->cip}}" name="cip_beneficiario" id="cip_beneficiario" style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label for="grado">GRADO</label>
                                        <input tabindex="16" readonly="" type="text" class="form-control" value="{{$datos_evaluacion->grado}}" name="grado" id="grado" style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label for="nomsexo">SEXO</label>
                                        <input tabindex="17" readonly="" type="text" class="form-control" value="{{$datos_evaluacion->sexo}}" name="nomsexo" id="nomsexo" style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label for="edadpaciente">EDAD</label>
                                        <input tabindex="18" readonly="" type="text" class="form-control"
                                          value="{{$datos_evaluacion->edadpaciente}}" name="edadpaciente" id="edadpaciente"
                                          style="text-align:left">
                                      </div>
                                      @else
                                      <div class="col-md-3">
                                        <label>FECHA DE NACIMIENTO</label>
                                        <input tabindex="13" readonly="" type="text" class="form-control"
                                          value="{{$datos_evaluacion->fecha_nac}}" name="fecnacafiliado"
                                          id="fecnacafiliado" style="text-align:left">
                                      </div>
                                      <div class="col-md-3">
                                        <label>PARENTESCO &nbsp;</label>
                                        <input tabindex="14" readonly="" value="{{$datos_evaluacion->parentesco}}" type="text"
                                          name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
                                      </div>
                                      <div class="col-md-3">
                                        <label for="nomsexo">SEXO</label>
                                        <input tabindex="15" readonly="" type="text" class="form-control"
                                          value="{{$datos_evaluacion->sexo}}" name="nomsexo" id="nomsexo"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-3">
                                        <label for="edadpaciente">EDAD</label>
                                        <input tabindex="16" readonly="" type="text" class="form-control"
                                          value="{{$datos_evaluacion->edadpaciente}}" name="edadpaciente" id="edadpaciente"
                                          style="text-align:left">
                                      </div>
                                      @if ($flag_no_encontrado == 0)
                                      <div class="col-md-3"><br/>
                                        <label>DOCUMENTO TITULAR</label>
                                        <input tabindex="17" readonly="" type="text" class="form-control"
                                          value="{{$dato_titular[0]->tipo_doc_ident}}:{{$dato_titular[0]->nro_doc_ident}}" name="documento_titular"
                                          id="documento_titular" style="text-align:left">
                                        <input type="hidden" class="form-control" value="{{$dato_titular[0]->tipo_doc_ident}}" name="tipodoc_titular" id="tipodoc_titular" style="text-align:left">
                                        <input type="hidden" class="form-control" value="{{$dato_titular[0]->nro_doc_ident}}" name="nrodoctitular" id="nrodoctitular" style="text-align:left">
                                      </div>
                                      <div class="col-md-4"><br/>
                                        <label>DATO DEL TITULAR &nbsp;</label>
                                        <input tabindex="18" readonly="" value="{{$dato_titular[0]->nombre}}, {{$dato_titular[0]->paterno}} {{$dato_titular[0]->materno}}" type="text" name="dato_titular_name" id="dato_titular_name" class="form-control">
                                          <input type="hidden" class="form-control" value="{{$dato_titular[0]->nombre}}" name="nombre_titular" id="nombre_titular" style="text-align:left">
                                          <input type="hidden" class="form-control" value="{{$dato_titular[0]->paterno}}" name="paterno_titular" id="paterno_titular" style="text-align:left">
                                          <input type="hidden" class="form-control" value="{{$dato_titular[0]->materno}}" name="materno_titular" id="materno_titular" style="text-align:left">
                                      </div>
                                      <div class="col-md-2"><br/>
                                        <label for="cip">CIP</label>
                                        <input tabindex="18" readonly="" type="text" class="form-control" value="{{$dato_titular[0]->cip}}" name="cip_titular" id="cip_titular" style="text-align:left">
                                      </div>
                                      <div class="col-md-3"><br/>
                                        <label for="grado">GRADO</label>
                                        <input tabindex="18" readonly="" type="text" class="form-control" value="{{$dato_titular[0]->grado}}" name="grado" id="grado" style="text-align:left">
                                      </div>
                                      @endif
                                      @endif
                                    </div>
                                  </div>      
                                </div>
                              </fieldset>
                              <div class="panel with-nav-tabs panel-default">
                                <div class="panel-heading">
                                  <ul class="nav nav-tabs">
                                    <li   class="active"><a href="#tab300default" data-toggle="tab" style="font-size:12px">EVOLUCIONES MEDICAS</a></li>
                                    <li><a href="#tab305default" data-toggle="tab" style="font-size:12px">HISTORIA CLINICA</a></li>
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
                                              <b>HISTORIAL DE EVOLUCIONES MEDICAS</b>
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
                              <!-- <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>ANTECEDENTES</b>
                                    </div>
                                  </div>
                                </div>
                              </fieldset> -->
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>PLAN DE TRABAJO</b>
                                    </div>
                                  </div>
                                  <!-- <div class="panel-body" style="display: none;">
                                    <div class="col-md-12">
                                      <label>MOTIVO DE VISITA</label>
                                      <textarea class="form-control" rows="7" id="motivo_visita" name="motivo_visita"
                                        placeholder="Relato m&eacute;dico del motivo de la visita"></textarea>
                                    </div>
                                  </div> -->
                                  <div class="panel-body">
                                    @if ($ultima_atencion->tipo_atencion_ultima == "prestacion")
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12">
                                        <label>EXÁMENES DE AYUDA DIAGNÓSTICA</label>
                                        <!-- PLAN DE TRABAJO - LABORATORIO -->
                                        <div class="panel panel-bordered panel-default">
                                          <div class="panel-heading">
                                            <div class="panel-title">
                                              <b style="font-size:11px;">EXAMEN(ES) DE LABORATORIO</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            @if ($ordenes_laboratorio != null)
                                              @foreach ($ordenes_laboratorio as $rowLaboratorio)
                                                <?php
                                                if ($rowLaboratorio->fecha_firma == "") {
                                                  $flag_firmar_lab = 1;
                                                }
                                                $model_prestacion = new \App\Models\Prestaciones;
                                                $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio2($id_prestacion, $rowLaboratorio->id);
                                                ?>
                                                <div class="form-group">
                                                  <div class="col-md-12">
                                                    <label class="col-md-2">N° ORDEN: <span style="font-weight:normal"><?php echo $id_prestacion.' - '. $rowLaboratorio->id; ?></span></label>
                                                    <label class="col-md-2">FEC. ORDEN: <span style="font-weight:normal"><?php echo $rowLaboratorio->fecha_firma; ?></span></label>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <div class="col-md-12">
                                                    <!-- <fieldset> -->
                                                      <div class="panel panel-info panel-bordered panel-sm">
                                                        <div class="panel-body">
                                                          <div class="form-group">
                                                            <div class="col-md-12">
                                                              <table class="display" cellspacing="0" width="100%">
                                                                <thead>
                                                                  <tr>
                                                                    <th width="5%">Codigo</th>
                                                                    <th width="45%">Nombre</th>
                                                                    <th width="30%">Observacion</th>
                                                                    <th width="10%">Cantidad</th>
                                                                    <th width="10%">Diagnóstico</th>
                                                                  </tr>
                                                                </thead>
                                                                <tbody>
                                                                  @foreach($proclaboratorio as $row)
                                                                  <tr>
                                                                    <td><?php echo $row->codigo?></td>
                                                                    <td><?php echo $row->nombre?></td>
                                                                    <td><?php echo $row->observacion?></td>
                                                                    <td><?php echo $row->cantidad?></td>
                                                                    <td><?php echo $row->codigo_diagnostico?></td>
                                                                  </tr>
                                                                  @endforeach
                                                                </tbody>
                                                              </table>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    <!-- </fieldset> -->
                                                  </div>
                                                </div>
                                              @endforeach
                                            @else
                                              <p>No hay Órdenes de Laboratorio registradas.</p>
                                            @endif
                                          </div> <!-- .panel-body -->
                                        </div>

                                        <!-- PLAN DE TRABAJO - IMAGENOLOGIA -->
                                        <div class="panel panel-bordered panel-default">
                                          <div class="panel-heading">
                                            <div class="panel-title">
                                              <b style="font-size:11px;">EXAMEN(ES) DE IMAGENOLOGIA</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            @if ($ordenes_imagenologia != null)
                                              @foreach($ordenes_imagenologia as $rowRadiologia)
                                                <?php
                                                if ($rowRadiologia->fecha_firma == "") {
                                                    $flag_firmar_lab = 1;
                                                }
                                                $model_prestacion = new \App\Models\Prestaciones;
                                                $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLaboratorio($id_prestacion, $rowRadiologia->id,3);
                                                ?>
                                                <div class="form-group">
                                                  <div class="col-md-12">
                                                    <label class="col-md-2">N° ORDEN: <span style="font-weight:normal;">{{$id_prestacion.' - '. $rowRadiologia->id}}</span></label>
                                                    <label class="col-md-2">FEC. ORDEN: <span style="font-weight:normal;">{{$rowRadiologia->fecha_firma}}</span></label>
                                                  </div>
                                                </div>                  
                                                <fieldset>
                                                  <div class="panel panel-info panel-bordered panel-sm">
                                                    <div class="panel-body">
                                                      <div class="form-group">
                                                        <div class="col-md-12">
                                                          <table class="display" cellspacing="0" width="100%">
                                                            <thead>
                                                              <tr>
                                                                <th width="5%">Codigo</th>
                                                                <th width="45%">Nombre</th>
                                                                <th width="10%">Comparativo</th>
                                                                <th width="10%">Lado</th>
                                                                <th width="15%">Observacion</th>
                                                                <th width="5%">Cantidad</th>
                                                                <th width="10%">Diagnóstico</th>
                                                                <th width="5%"></th>
                                                              </tr>
                                                              </tr>
                                                            </thead>
                                                            <tfoot>
                                                            </tfoot>
                                                            <tbody>
                                                              @foreach($procradiologia as $row)
                                                                <tr>
                                                                  <td>{{$row->codigo}}</td>
                                                                  <td>{{$row->nombre}}</td>
                                                                  <td>
                                                                    @if($row->comparativo_radio!=null)
                                                                      <?php
                                                                      if($row->comparativo_radio==1):
                                                                        echo "SI";
                                                                      else:
                                                                        echo "NO";
                                                                      endif;
                                                                      ?>
                                                                    @endif
                                                                  </td>
                                                                  <td>
                                                                    @if($row->lado_radio!=null)
                                                                      <?php
                                                                      if($row->lado_radio!=null):
                                                                        if($row->lado_radio==1):
                                                                          echo "Izquierdo";
                                                                        elseif($row->lado_radio==2):
                                                                          echo "Derecho";
                                                                        elseif($row->lado_radio==3):
                                                                          echo "Ambos";
                                                                        endif;
                                                                      endif;
                                                                      ?>
                                                                    @endif
                                                                  </td>
                                                                  <td>
                                                                    @if($row->observacion!='0')
                                                                      {{$row->observacion}}
                                                                    @endif
                                                                  </td>
                                                                  <td>{{$row->cantidad}}</td>
                                                                  <td>{{$row->cod_diag}}</td>
                                                                </tr>
                                                              @endforeach
                                                            </tbody>
                                                          </table>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </fieldset>
                                              @endforeach
                                            @else
                                              <p>No hay Órdenes de Imagenología registradas.</p>
                                            @endif
                                          </div> <!-- .panel-body -->
                                        </div>
                                      </div>
                                      <div class="col-lg-12 col-md-12">
                                        <label>PROCEDIMIENTOS MÉDICO - QUIRÚRGICOS</label>
                                        <textarea class="form-control" rows="5" id="" name="" readonly
                                          placeholder="">{{$ultima_atencion->procedimiento_medico_quirurgico}}</textarea>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12">
                                        <label>INTERCONSULTAS</label>
                                        <!-- PLAN DE TRABAJO - INTERCONSULTA -->
                                        <div class="panel panel-bordered panel-default">
                                          <div class="panel-heading">
                                            <div class="panel-title">
                                              <b style="font-size:11px;">INTERCONSULTAS</b>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <fieldset>
                                              <div class="col-md-12">
                                                @if ($interconsultas != null)
                                                  @foreach($interconsultas as $rowi)
                                                  <div class="panel panel-info panel-bordered panel-sm">
                                                    <div class="form-group"> 
                                                      <div class="panel-body"> 
                                                        <div class="col-md-12">
                                                          <label>SERVICIO: <span style="font-weight:normal">{{$rowi->codigo_interconsulta}} - {{$rowi->servicio}}</span></label>
                                                        </div>
                                                        <div class="col-md-12">
                                                          <label>MOTIVO: <span style="font-weight:normal">{{$rowi->motivo_interconsulta}}</span></label> 
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  @endforeach
                                                @else
                                                  <p>No hay Interconsultas registradas.</p>
                                                @endif
                                              </div>
                                            </fieldset>
                                          </div> <!-- .panel-body -->
                                        </div>

                                      </div>
                                    </div>
                                    @elseif ($ultima_atencion->tipo_atencion_ultima == "evolucion")
                                    <div class="form-group">
                                      <div class="col-lg-12 col-md-12">
                                        <label>PLAN</label>
                                        <textarea class="form-control" rows="6" id="" name="" readonly placeholder="">{{$ultima_atencion->plan_trabajo}}</textarea>
                                      </div>
                                    </div>
                                    @endif
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>EVOLUCIÓN</b>
                                    </div>
                                  </div>
                                  <?php
                                    $signos_vitales = null;
                                    $readonly_signos_vitales = "";
                                    $temperatura = ""; $talla = ""; $peso = ""; $saturacion = ""; 
                                    $presion_arterial_x = ""; $presion_arterial_y = ""; $frecuencia_respiratoria = ""; 
                                    $frecuencia_cardiaca = ""; $oxigenacion = ""; $diuresis = ""; 
                                    $balance_hidrico = ""; $frecuencia_cardiaca_fetal = "";

                                    if ($signos_vitales != null) {
                                      $readonly_signos_vitales = "readonly";

                                      foreach ($signos_vitales as $sv) {
                                        switch ($sv->id_caracter_fisico) {
                                          case 1: $presion_arterial_x = $sv->valor; break;
                                          case 2: $presion_arterial_y = $sv->valor; break;
                                          case 4: $frecuencia_cardiaca = $sv->valor; break;
                                          case 6: $frecuencia_respiratoria = $sv->valor; break;
                                          case 8: $saturacion = $sv->valor; break;
                                          case 9: $temperatura = $sv->valor; break;
                                          case 78: $oxigenacion = $sv->valor; break;
                                        }
                                      }
                                    }
                                  ?>
                                  {{-- @if ($signos_vitales != null) --}}
                                  <div class="panel-body" id="VerSignosVitales">
                                    <div class="form-group">
                                      <div class="col-sm-12" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                        @if ($signos_vitales != null)
                                        <label>SIGNOS VITALES &nbsp;(Fecha Registro: <span style="font-weight:normal">{{date("d-m-Y H:i:s", strtotime($signo_vital->created_at))}}</span>)</label>
                                        @else
                                        <label>SIGNOS VITALES</label>
                                        @endif
                                      </div>
                                    </div>
                                    <div class="panel panel-info panel-bordered panel-sm" id="fsSignosVitales">
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>TEMPERATURA (°C) &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                            <input placeholder="00.00" value="{{$temperatura}}" type="text" name="temperatura" id="temperatura" class="form-control" tabindex="23" onkeypress="return esNumero(event,this.id)" maxlength="5" />
                                            <span id="texto-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>TALLA (cm) &nbsp;</label>
                                            <input value="{{$talla}}" type="text" name="talla" id="talla" class="form-control" tabindex="24" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="talla-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PESO (Kg) &nbsp;</label>
                                            <input placeholder="00.00" value="{{$peso}}" type="text" name="peso" id="peso" class="form-control"  tabindex="25" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="6" >
                                            <span id="peso-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-1">
                                            <label>IMC &nbsp;</label>
                                            <input value="" type="text" name="imc" id="imc" class="form-control" readonly="" tabindex="26" >
                                            <span id="imc-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-1">
                                            <label>PESO IDEAL &nbsp;</label>
                                            <input value="" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control" readonly="" max="0" tabindex="27" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>Sa02 (%) &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                            <input value="{{$saturacion}}" type="text" name="saturacion" id="saturacion" class="form-control" tabindex="28" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="saturacion-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PRESIÓN ARTERIAL &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                            {{-- @if ($signos_vitales != null)
                                              <input type="text" tabindex="29" value="{{$presion_arterial_x}} / {{$presion_arterial_y}}" class="form-control" readonly>
                                              <input value="{{$presion_arterial_x}}" type="hidden" name="presion_arterial_x" id="presion_arterial_x" class="form-control">
                                              <input value="{{$presion_arterial_y}}" type="hidden" name="presion_arterial_y" id="presion_arterial_y" class="form-control">
                                            @else --}}
                                            <div class="row">
                                              <div class="col-xs-5">                          
                                                <input value="{{$presion_arterial_x}}" tabindex="29" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                                <span id="presion_arterial_x-aceptado" style="font-size:12px"></span>
                                              </div>  
                                                <div class="col-xs-2">
                                                   <label>/</label> 
                                                </div>
                                              <div class="col-xs-5">
                                                <input value="{{$presion_arterial_y}}" type="text" tabindex="30" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                                <span id="presion_arterial_y-aceptado" style="font-size:12px"></span>
                                              </div>  
                                            </div>
                                            {{-- @endif --}}
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>FREC. RESPIRATORIA (/min) <span style="font-weight: bold; color: red;">(*)</span></label>
                                            <input value="{{$frecuencia_respiratoria}}" type="text" name="frecuencia_respiratoria" tabindex="31" id="frecuencia_respiratoria" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="2" >
                                            <span id="frecuencia_respiratoria-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. CARDIACA (/min) &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                            <input value="{{$frecuencia_cardiaca}}" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="32" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="frec_cardiaca-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Fi02 (%) &nbsp;</label>
                                            <input value="{{$oxigenacion}}" type="text" name="oxigenacion" id="oxigenacion" tabindex="33" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="oxigenacion-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>DIURESIS</label>
                                            <input value="{{$diuresis}}" type="text" name="diuresis" id="diuresis" tabindex="34" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="5" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>BALANCE HÍDRICO</label>
                                            <input value="{{$balance_hidrico}}" type="text" name="balance_hidrico" id="balance_hidrico" tabindex="35" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="5" >
                                          </div>
                                          <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                            <label>FREC. CARDIACA FETAL(/min)</label>
                                            <input tabindex="36" value="{{$frecuencia_cardiaca_fetal}}" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="frecuencia_cardiaca_fetal-aceptado" style="font-size:12px"></span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  {{-- @endif --}}
                                  <!-- <div class="panel-body" style="display: none;">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label>EXAMEN FISICO</label>
                                        <textarea class="form-control" rows="7" id="examen_fisico" name="examen_fisico"
                                          placeholder="Examen Físico"></textarea>
                                      </div>
                                    </div>
                                  </div> -->
                                  <div class="panel-body">
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                        <div class="col-lg-12 col-md-12">
                                          <label>SUBJETIVO <span style="font-weight: bold; color: red;">(*)</span></label>
                                          <button type="button" class="btn btn-default btn-xs btn-dictado" data-target-id="apreciacion_subjetiva" title="Dictar por voz" aria-label="Dictar por voz" style="margin-left:6px; vertical-align:middle;"><i class="fa fa-microphone"></i></button>
                                          <textarea tabindex="36" class="form-control" rows="11" id="apreciacion_subjetiva" name="apreciacion_subjetiva"
                                            placeholder="Apreciación Subjetiva"></textarea>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-lg-12 col-md-12">
                                          <label>OBJETIVO <span style="font-weight: bold; color: red;">(*)</span></label>
                                          <button type="button" class="btn btn-default btn-xs btn-dictado" data-target-id="apreciacion_objetiva" title="Dictar por voz" aria-label="Dictar por voz" style="margin-left:6px; vertical-align:middle;"><i class="fa fa-microphone"></i></button>
                                          <textarea tabindex="36" class="form-control" rows="11" id="apreciacion_objetiva" name="apreciacion_objetiva"
                                            placeholder="Apreciación Objetiva"></textarea>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                        <label for="">APRECIACIÓN <span style="font-weight: bold; color: red;">(*)</span></label>
                                        <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-lg-12 col-md-12">
                                                <label>VERIFICACIÓN DEL TRATAMIENTO Y DIETA</label>
                                                <textarea tabindex="36" class="form-control" rows="5" id="verificacion_tratamiento" name="verificacion_tratamiento"
                                                  placeholder="Verificación del tratamiento y dieta."></textarea>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <div class="col-lg-12 col-md-12">
                                                <label>INTERPRETACIÓN DE EXÁMENES Y COMENTARIO</label>
                                                <button type="button" class="btn btn-default btn-xs btn-dictado" data-target-id="interpretacion_examenes" title="Dictar por voz" aria-label="Dictar por voz" style="margin-left:6px; vertical-align:middle;"><i class="fa fa-microphone"></i></button>
                                                <textarea tabindex="36" class="form-control" rows="5" id="interpretacion_examenes" name="interpretacion_examenes"
                                                  placeholder="Interpretación de exámenes y comentario."></textarea>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-lg-12 col-md-12">
                                          <label>PLAN <span style="font-weight: bold; color: red;">(*)</span></label>
                                          <button type="button" class="btn btn-default btn-xs btn-dictado" data-target-id="plan_trabajo" title="Dictar por voz" aria-label="Dictar por voz" style="margin-left:6px; vertical-align:middle;"><i class="fa fa-microphone"></i></button>
                                          <textarea tabindex="36" class="form-control" rows="6" id="plan_trabajo" name="plan_trabajo"
                                            placeholder="Terapéutica y plan de trabajo."></textarea>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>

                              <script src="{{ asset('js/dictado-voz.js') }}"></script>

                              <!-- <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>TRATAMIENTO Y/O TERAPEUTICA</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label>TRATAMIENTO HABITUAL</label>
                                        <textarea class="form-control" rows="7" id="tratamiento_auxiliar" name="tratamiento_auxiliar"
                                          placeholder="Tratamiento auxiliar"></textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset> -->
                              @if ($servicio_ubicacion->id == 131)
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
                                                  <input type="checkbox" name="evento_ep[]" value="11" {{ in_array(11, $eventosMarcados) ? 'checked' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes que han recibido por lo menos un control prenatal en la IPRESS</span></label>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="12" {{ in_array(12, $eventosMarcados) ? 'checked' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes que han recibido por lo menos 6 controles prenatales</span></label>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="13" {{ in_array(13, $eventosMarcados) ? 'checked' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes que han recibido la primera consulta en el primer trimestre del embarazo</span></label>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="16" {{ in_array(16, $eventosMarcados) ? 'checked' : '' }}>
                                                  <span style="margin-left: 6px;">Nacidos vivos con bajo peso al nacer (menos de 2500 gramos)</span></label>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="21" {{ in_array(21, $eventosMarcados) ? 'checked' : '' }}>
                                                  <span style="margin-left: 6px;">Nacidos vivos con edad gestacional menor a 37 semanas de gestacion (segun fecha ultima regla)</span></label>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="15" {{ in_array(15, $eventosMarcados) ? 'checked' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes con diagnostico confirmado de infeccion HIV</span></label>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="14" {{ in_array(14, $eventosMarcados) ? 'checked' : '' }}>
                                                  <span style="margin-left: 6px;">Gestantes con diagnostico confirmado de Tuberculosis</span></label>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="evento_ep[]" value="8" {{ in_array(8, $eventosMarcados) ? 'checked' : '' }}>
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
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <!-- <fieldset>
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
                                          placeholder="Plan de Trabajo"></textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset> -->
                              
                              {{-- UBICACIÓN/ ESTADO PACIENTE --}}
                              <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>UBICACIÓN/ ESTADO PACIENTE</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <input type="hidden" id="id_servicio_actual_paciente" name="id_servicio_actual_paciente" value="{{$id_servicio_actual}}">
                                          <div class="form-group row">
                                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                                <label>AREA ACTUAL</label>
                                                <select id="id_area" name="id_area"class="form-control" style="width:100%" disabled>
                                                  <option value="0">-Seleccione-</option>
                                                  @foreach($cuartos as $e)
                                                    <option value="{{$e->id_cuarto}}" @if($e->id_cuarto == $dato_cama->id_consultorio) selected @endif>{{$e->servicio_sala}}</option>
                                                  @endforeach
                                                </select>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                                <label>CAMA/CAMILLA ACTUAL</label>
                                                <select id="id_cama" name="id_cama" class="form-control" style="width:100%" disabled>
                                                  <option value="0">-Seleccione-</option>
                                                  @foreach($camas as $e)
                                                    <option value="{{$e->id}}" @if($e->id == $dato_cama->id) selected @endif>{{$e->nombre}}</option>
                                                  @endforeach
                                                </select>
                                              </div>
                                              <div class="col-md-2">
                                                <label>CAMBIAR CAMA</label>
                                                <select id="select_cambiar_cama" name="select_cambiar_cama" class="form-control" 
                                                @if(!$existe_servicio_atencion) readonly style="pointer-events: none;" @endif>
                                                  <option value="0">NO</option>
                                                  <option value="1">ROTACIÓN</option>
                                                  <option value="2">TRASLADO</option>
                                                </select>
                                                @if(!$existe_servicio_atencion)
                                                <span style="color:red"><b>*Su especialidad no coincide con el servicio de atención</b></span>
                                                @endif
                                              </div>
                                          </div> <!-- form-group row -->
                                          <div id="div_campos_rotacion" class="panel-sm panel-body" style="display:none">
                                             <fieldset class="panel panel-info panel-bordered panel-sm">
                                              <div class="panel-heading text-center"><h3 class="panel-title">ROTACIÓN</h3></div>
                                                <div class="panel-body">
                                                  <div class="form-group row">
                                                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                                      <label>FECHA INICIO ROTACIÓN</label>
                                                      <input tabindex="2" type="text" class="form-control" value="<?php echo date("d/m/Y", strtotime($fechaServidor)); ?>" name="fecha_inicio" id="fecha_inicio" readonly style="background-color: #ffffff !important" data-date-end-date="0d">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                                      <label>HORA ROTACIÓN</label>
                                                      <input type="time" id="appt" name="appt" value="<?php echo $hora; ?>" class="form-control" required>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                                      <label>AREA DESTINO</label>
                                                      <select id="id_area_destino" name="id_area_destino"class="form-control" style="width:100%">
                                                        <option value="">-Seleccione-</option>
                                                        @foreach($cuartos as $e)
                                                          <option value="{{$e->id_cuarto}}" @if($e->id_cuarto == $dato_cama->id_consultorio) selected @endif>{{$e->servicio_sala}}</option>
                                                        @endforeach
                                                      </select>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                      <label for="id_cama_destino" >CAMA/CAMILLA DESTINO</label>
                                                      <select id="id_cama_destino" name="id_cama_destino" class="form-control">
                                                        <option>-Seleccione-</option>
                                                        @foreach($camas as $e)
                                                          @if($e->id != $dato_cama->id)
                                                          <option value="{{$e->id}}">{{$e->nombre}}</option>
                                                          @endif
                                                        @endforeach
                                                      </select>
                                                    </div>
                                                  </div>
                                                </div>
                                             </fieldset>
                                          </div>
                                      </div>
                                  </div>
                              </fieldset>
                              {{-- UBICACIÓN/ ESTADO PACIENTE - END --}}

                              {{------------------- TRANFERENCIA -------------------}}
                              <div id="panelTraslado" class="panel-sm panel-body" style="display: none">
                                {{--<div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>TRANSFERENCIA DE SERVICIO</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="110" type="button" id="toogleTraslado" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>
                                  </div>
                                </div>--}}
                                <div id="frmTraslado">
                                  <fieldset class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading text-center"><h3 class="panel-title">TRASLADO</h3></div>
                                    <div class="panel-body">
                                      <div class="row">
                                        <!-- <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                          <label for="upss_origen">UPSS ORIGEN</label>
                                          <select name="upss_origen" id="upss_origen" class="form-control" disabled>
                                            <option value="{{$datos_evaluacion->id_consultorio}}" selected>{{$datos_evaluacion->servicio}}</option>
                                          </select>
                                        </div> -->
                                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                          <label for="traslado_tipo">TIPO DE TRASLADO / TRANSFERENCIA</label>
                                          <select name="traslado_tipo" id="traslado_tipo" class="form-control">
                                            <option value="">-- Seleccionar tipo --</option>
                                            <option value="1">A PROCEDIMIENTO QX</option>
                                            <option value="2">DE SERVICIO</option>
                                          </select>
                                        </div> 
                                        <!--  -->
                                        <div class="col-sm-6 col-md-3" id="formGroupTrasladoUppsEncargado" style="display: none">
                                          <label>UPSS DESTINO</label>
                                          <select name="traslado_id_upss_encargado" id="traslado_id_upss_encargado" style="width:100%" class="form-control">
                                            <option value="">--SELECCIONAR--</option>
                                            @foreach($upsses as $r)
                                              @if($upss_encargado->codigo!=$r->codigo_upss)
                                              <option value="{{$r->id_upss}}">{{$r->codigo_upss}} - {{$r->descripcion_upss}}</option>
                                              @endif
                                            @endforeach
                                          </select>
                                        </div>
                                        <div class="col-sm-6 col-md-2" id="formGroupTrasladoServicioEncargado" style="display: none">
                                          <label>DEPARTAMENTO A CARGO DESTINO</label>
                                          <select name="traslado_id_servicio_encargado" id="traslado_id_servicio_encargado" style="width:100%" class="form-control">
                                          </select>
                                        </div>
                                        <!--  -->
                                        <div class="col-xs-12 col-sm-6 col-md-3" id="formGroupTrasladoUppsDestino" style="display: none">
                                          <label for="traslado_upss_destino">SERVICIO UBICACIÓN</label>
                                        </div> 
                                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2" id="formGroupCama" style="display: none">
                                          <label for="traslado_cama">CAMA UBICACIÓN</label>
                                          <select name="traslado_cama" id="traslado_cama" class="form-control" disabled>
                                            <option value="">-- SELECCIONAR CAMA --</option>
                                          </select>
                                        </div>
                                      </div>
                                      <br>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2" id="formGroupFechaTranslado" style="display: none">
                                          <label for="traslado_fecha">FECHA TRASLADO</label>
                                          <input type="text" class="form-control" id="traslado_fecha" name="traslado_fecha" placeholder="01/01/2024" readonly style="background-color: #ffffff !important">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2" id="formGroupHoraTranslado" style="display: none">
                                          <label for="traslado_hora">HORA TRASLADO</label>
                                          <input type="time" class="form-control" id="traslado_hora" name="traslado_hora" placeholder="00:00">
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                  
                                  <fieldset class="panel panel-info panel-bordered panel-sm" id="diagnostico_traslado">
                                    <div class="panel-heading text-center"><h3 class="panel-title">DIAGNÓSTICOS DEL TRASLADO</h3></div>
                                    <div class="panel-body">
                                      <button type="button" class="btn btn-sm btn-success" onclick="onAddRowTableDiagTraslado()"><i class="fa fa-plus"></i>AGREGAR DIAGNÓSTICO</button>
                                      <br><br>
                                      <table class="table table-striped" style="margin: 0px -8px; font-size: 11px; width: 100%" id="tableDiagTraslado">
                                        <thead>
                                          <tr>
                                            <th width="70%">DIAGNÓSTICO</th>
                                            <th width="20%">TIPO</th>
                                            <th width="10%" style="text-align: center">ELIMINAR</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                      </table>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                              {{---------------- TRANFERENCIA - END ----------------}}

                              {{-- SALUD_MATERNA - GESTACION --}}
                              @if (isset($lastGestacion) && strval($datos_evaluacion->sexo) === "F" && (intval($datos_evaluacion->id_consultorio) === 77 || intval($datos_evaluacion->id_consultorio) === 131) && (intval($datos_evaluacion->edadpaciente) >= 12) && (intval($datos_evaluacion->edadpaciente) <= 55))
                              <fieldset id="frmGestacion">
                                <div class="panel panel-info panel-bordered panel-sm"  id="panelGestacion">
                                  <div class="panel-heading"><div class="panel-title text-center"><b>SALUD MATERNA</b></div></div>
                                  <div class="panel-body">
                                    <div class="row">
                                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px"><label>GESTANTE</label></div>
                                    </div>
                                    <br>
                                    <div class="row" style="font-size: 11px; font-weight: lighter;">
                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <label for="fecha_ultima_mestruacion">FECHA DE ULTIMA REGLA</label>
                                        <input class="form-control" id="fecha_ultima_mestruacion" name="fecha_ultima_mestruacion" placeholder="DD/MM/AAAA" readonly value="{{$lastGestacion->fech_ult_regla_format}}">
                                      </div>
                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <label for="fecha_probable_parto">FECHA PROBABLE DE PARTO</label>
                                        <input class="form-control" id="fecha_probable_parto" name="fecha_probable_parto" placeholder="DD/MM/AAAA" readonly value="{{$lastGestacion->fech_prob_parto_format}}">
                                      </div>
                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <label for="semanas_gestacion">SEMANAS DE GESTACION</label>
                                        <input class="form-control" id="semanas_gestacion" name="semanas_gestacion" readonly value="{{$lastGestacion->semanas_gestacion}}">
                                      </div>
                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <label for="num_controles_prenatal">NUM. DE CONTROLES PRENATALES</label>
                                        <input class="form-control" id="num_controles_prenatal" name="num_controles_prenatal" readonly value="{{$lastGestacion->num_controles}}">
                                      </div>
                                    </div>
                                    <br>
                                    <div class="row" style="font-size: 11px; font-weight: lighter;">
                                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-top: 7px">
                                        <label>DUDA</label>
                                      </div>
                                      <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
                                        <label class="radio-inline"><input type="radio" name="options_duda" id="duda_si" value="1" {{$lastGestacion->duda ? "checked": ""}} disabled> SI</label>
                                        <label class="radio-inline"><input type="radio" name="options_duda" id="duda_no" value="0" {{!$lastGestacion->duda ? "checked": ""}} disabled> NO</label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              @endif 
                              {{-- END_SALUD_MATERNA - GESTACION --}}

                              {{-- SALUD_MATERNA - PUERPERA --}}
                              <fieldset id="panelPuerpera" style="display:none">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading"><div class="panel-title text-center"><b>SALUD MATERNA</b></div></div>
                                  <div class="panel-body">
                                    <div class="row">
                                      <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px"><label>PUERPERA</label></div>
                                      <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button type="button" id="tooglePuerpera" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>
                                      </div>
                                    </div>
                                    <br>
                                    <div id="frmPuerpera" style="display:none">
                                      <div class="row" style="font-size: 11px">
                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <label for="fecha_parto">FECHA PARTO</label>
                                        <input class="form-control input-date" id="fecha_parto" name="fecha_parto" placeholder="DD/MM/AAAA" readonly>
                                      </div>
                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <label for="hora_parto">HORA PARTO</label>
                                        <input type="text" class="form-control" id="hora_parto" name="hora_parto" placeholder="00:00:00">
                                      </div>
                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <label for="tipo_parto">TIPO DE PARTO</label>
                                        <select tabindex="8" class="form-control input-sm" name="tipo_parto" id="tipo_parto">
                                          <option value=""> -Seleccionar-</option>
                                          @if (count($tipo_partos) > 0)
                                              @foreach ($tipo_partos as $tipo_parto)
                                                  <option value="{{$tipo_parto->id}}">{{ $tipo_parto->nombre }}</option>
                                              @endforeach
                                          @endif
                                        </select>
                                      </div>
                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <label for="complicacion_parto">COMPLICACIÓN</label>
                                        <select tabindex="8" class="form-control input-sm" name="complicacion_parto" id="complicacion_parto" style="width:100%">
                                        <option value=""> -Seleccionar-</option>
                                        <option value="1">SIN COMPLICACIONES</option>
                                        <option value="2">COMPLICADO</option>
                                        </select>
                                      </div>
                                    </div>
                                    <br>
                                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                                      <div class="row">
                                        <div class="col-md-1">
                                          <label>NEONATOS</label>
                                        </div>
                                        <div class="col-md-11">
                                          <button type="button" class="btn btn-success btn-xs" onclick="addRowTableNeonatos()"><i class="fa fa-plus"></i>AGREGAR</button>
                                        </div>
                                      </div>
                                      <br>
                                      <table class="table table-striped" style="margin: 0px -8px; font-size: 11px" id="tableNeonatos">
                                        <thead>
                                          <tr>
                                            <th width="20%">ESTADO DE VIDA</th>
                                            <th width="20%">TIPO DOCUMENTO</th>
                                            <th width="20%">NUM. DOCUMENTO</th>
                                            <th width="15%">SEXO</th>
                                            <th width="10%">TALLA(cm)</th>
                                            <th width="10%">PESO(gr)</th>
                                            <th width="5%">ELIMINAR</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                      </table>                                        
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              {{-- END_SALUD_MATERNA - PUERPERA --}}

                              <fieldset id="fieldEventosEp" style="display: {{ $obj_hospitalizacion_atencion->id_puerperio ? 'block' : 'none' }};">
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
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>TRATAMIENTO</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label>INDICACIONES TERAPÉUTICAS</label>
                                        <textarea class="form-control" tabindex="" rows="5" id="indicacion_terapeutica" name="indicacion_terapeutica" placeholder="Dieta, cuidados de enfermería y de otros profesionales que sean considerados necesarios, medicamentos (consignando presentación, dosis, frecuencia y vía de administración)."></textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
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
                                        <select tabindex="72" class="form-control" name="id_farmacia_receta" id="id_farmacia_receta" style="width:100%" onchange="getProducto();">
                                          <option value="">-Seleccionar-</option>
                                          @if($nivel<3)
                                            <?php foreach($farmacias as $row):?>
                                            <option value="<?php echo $row->id?>"
                                              <?php if($row->id==Auth::user()->id_farmacia) echo "selected='selected'"?>>
                                              <?php echo $row->nombre?></option>
                                              @if(Auth::user()->id_establecimiento == 77)
                                                <option value="25">Farmacia Centro Quirúrgico del Hospital PNP Augusto B. Leguia</option>
                                              @endif
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
                                    <br />
				                                            <div class="form-group">
                                          <div class="col-sm-12">
                                            <table>
                                              <tr>
                                                <td>
                                                  <button tabindex="75" type="button" id="addProducto" class="btn btn-primary"
                                                    data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                                </td>

                                                <td>
                                                  <button 
                                                    type="button" 
                                                    class="btn btn-info" 
                                                    style="margin-left: 20px" 
                                                    onclick="getLastRecipeOnHospi()"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Copiar Última Receta"
                                                  ><i class="fa fa-files-o"></button>
                                                </td>

                                              </tr>
                                            </table>
                                          </div>
                                        </div>

                                    <div style="display: none">
                                      <select class="form-control" id="cmbMedicamentoTemp" tabindex="73"
                                        style="width: 500px">
                                        <option value="">Seleccionar Producto</option>
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
                                  <?php $displayPanelLaboratorioOrden = !$is_profesion_medico ? "display:none" : ""; ?>
                              <div class="panel panel-info panel-bordered panel-sm panel-body" style="{{$displayPanelLaboratorioOrden}}">
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
                                      <?php
                                        $ancho_columna_segun_nivel_establecimiento = intval($nivel) === 3 ? "10" : "12";
                                      ?>
                                      <div class="col-md-{{ $ancho_columna_segun_nivel_establecimiento }}">
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
                                        <table class="display" id="tblExamenLab" cellspacing="0" width="100%"
                                          style="margin-top:5px;">
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
                                                {{--@if (!empty($nivel) && (int)$nivel < 3)
                                                  <th width="5%">Tiempo</th>
                                                @endif--}}
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
                                          <!-- <div class="form-group">
                                            <div class="col-xs-12">
                                              <label for="">Observación</label>
                                              <input type="text" class="form-control" name="lab_orden_observacion" maxlength="255">
                                            </div>
                                          </div> -->
                                        @endif
                                      </div>
                                      @if (intval($nivel) === 3)
                                        <div class="col-md-2">
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
                                              <th width="30%">Diagnóstico</th>
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
                                </fieldset>
                              </div>
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
                                  <div class="col-sm-8">
                                    <b><span id="labelInfoInterconsulta" style="color:red"></span></b>
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
                                                  @if(intval($row->id) !== 137 || intval($hospitalizacione->id_servicio_encargado) === 8)
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
                                      <label>REQUIERE INTERVENCIÓN QUIRÚRGICA</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      @if($tiene_flag_quirurgico)
                                        <button tabindex="100" type="button" id="addRequiereIntervencionQuirurgica" class="btn btn-lg btn-toggle"
                                          data-toggle="button" aria-pressed="false" autocomplete="off" >
                                          <div class="handle"></div>
                                        </button>
                                      @else
                                        <span data-toggle="popover"
                                            data-trigger="hover"
                                            data-placement="top"
                                            data-html="true"
                                            title="<strong>Servicio Inactivo</strong>"
                                            data-content="El servicio no está activo en la lista de servicios quirúrgicos">
                                          <button id="btn-servicio-inactivo"
                                                  type="button"
                                                  class="btn btn-lg btn-toggle"
                                                  aria-pressed="false"
                                                  autocomplete="off"
                                                  disabled>
                                              <div class="handle"></div>
                                          </button>
                                      </span>
                                      @endif

                                    </div>
                                  </div>
                                  <fieldset id="fsRequiereIntervencionQuirurgica" style="display:none">
                                    <input type="hidden" name="evolucion_requiere_intervencion_quirurgica" id="evolucion_requiere_intervencion_quirurgica" value="0">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                          <div class="panel-heading">
                                            <div class="panel-title text-center"><b>REGISTRAR INFORMACIÓN QUIRÚRGICA</b></div>
                                          </div>
                                          <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label>SELECCIONAR</label>
                                                    <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control">
                                                        @foreach ($tipo_cirugias as $row)
                                                        <option value="{{ $row->id }}" @if($row->id==3) selected  @endif>{{ $row->descripcion }}</option>
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
                                                    <button type="button" id="addDiagnosticoQuirurgico" class="btn btn-primary"
                                                      data-toggle="modal" data-target="#addClassModal">Agregar
                                                      Diagnostico</button>
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
                                  </fieldset>
                              </div>

                              {{--<div class="panel panel-info panel-bordered panel-sm panel-body">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>ALTA DE SERVICIO</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="110" type="button" id="addAltaServicio" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="fsAltaServicio" style="display:none">
                                  <div class="panel panel-success panel-bordered panel-sm">
                                    <div class="panel-heading">
                                    <div class="panel-title text-center"><b>ALTA DE SERVICIO</b></div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-md-2">
                                          <label for="">FECHA DE ALTA</label>
                                          <input type="text" data-date-end-date="0d" class="form-control datepicker" value="{{ date('d-m-Y', strtotime($fechaServidor)) }}" name="fecha_egreso" id="fecha_egreso"/>
                                          <input type="hidden" id="flag_alta_servicio" name="flag_alta_servicio" value="0">
                                        </div>

                                        <div class="col-md-2">
                                          <label for="">HORA DE ALTA</label>
                                          <input type="time" name="hora_egreso" id="hora_egreso" value="{{ date('H:i', strtotime($hora)) }}" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                          <label for="">SERVICIO</label>
                                          <select id="id_area_destino" name="id_area_destino"class="form-control" onchange="getCamas();" style="width:100%">
                                            <option value="0">-Seleccione-</option>
                                            @foreach($cuartos as $e)
                                              @if($e->id != $dato_cama->id_cuarto)
                                                <option value="{{$e->id}}">{{$e->pabellon}} - {{$e->nombre}}</option>
                                              @endif
                                            @endforeach
                                          </select>
                                        </div>

                                        <div class="col-md-2">
                                          <label for="">CAMA/CAMILLA</label>
                                          <select id ="id_cama_destino" name="id_cama_destino" class="form-control" style="width:100%">
                                            <option value="0">-Seleccione-</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-md-8">
                                          <label for="">OBSERVACION</label>
                                          <textarea rows="2" class="form-control" id="observacion_alta_servicio" name="observacion_alta_servicio"></textarea>
                                        </div>
                                      </div>
                                      <hr>
                                      <label for=""><b>DIAGNOSTICOS</b></label>
                                      <p>Los Diagnósticos de Alta de Servicio se tomarán de los Diagnósticos ya ingresados.</p>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>--}}

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

{{-- MODAL SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS --}}
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

<div id="addComponent"></div>
@include('frontend.includes.footer')
@endsection