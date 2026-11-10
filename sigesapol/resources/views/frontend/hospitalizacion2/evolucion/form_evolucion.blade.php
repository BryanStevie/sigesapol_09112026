@extends('frontend.layouts.master2')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
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
.swal2-icon.swal2-warning {
    font-size: 15px;
  }
</style>

<script>

const decimalRegex = /^\d+(\.\d+)?$/;
var fechaHoraCompleto = new Date('{{$datos_evaluacion->created_at}}');
var procedimientosSeleccionados =[];
//var listIdDiagnostico ={!! json_encode($arraydeprocedimientos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
//var listIdDiagnosticoObjetos ={!! json_encode($arraydeprocedimientos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
  var listIdDiagnostico =[];
  var listIdDiagnosticoObjetos =[];
 var filasmedicamentos = 0;

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
    $('#addExamenRad2').on('click',function(){
      AddExamenRad();
    });
    
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
    });
    $("#oxigenacion").inputmask("decimal", {
      digits: 0,
      placeholder: "0",
      allowMinus: false,
      allowPlus: false,
      max: 100,
      digitsOptional: false
    });*/

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
    getProducto();
    modalHistorial();
    modalLaboratorio();
    modalRadiologico();
    modalAntecedentes();
     modalDiagnostico();
    modalProcedimiento();
    //modalHistorialAtencion();
    modalHistorialEvaluaciones();
    modalHistorialAtencionEv();
    modalInterconsulta();
			
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


function getProducto(){
  
  $('#example tbody').html('');
  
  //if($('#id_servicio').val()=="") return false;
  
  //var id_ipress     = $('#id_ipress').val();
  var id_ipress     = $('#id_establecimiento').val();
  var id_consultorio  = $('#id_servicio').val();
  //var tipo_receta   = $('#tipo_receta').val();
  //var id_tiporeceta   = $('#id_tiporeceta').val();
  var id_tiporeceta   = $('#id_tipo_atencion').val();
  var id_farmacia     = $('#id_farmacia_receta').val();
  
  var nivel     = $('#id_nivel').val();
  var x  = $('#nro_interconsultas').val();
  var dni = $('#dni_beneficiario').val();
  //var interconsulta = $('#interconsulta').val();
  if(id_tiporeceta == "3"){ 
    
    /*if(interconsulta=='SI'){
      if (id_consultorio==104){
       id_consultorio2=104
      }
      else
      {
       id_consultorio2=105
      }
      $.ajax({
        //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
        url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +id_consultorio2,
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
    { */ 
      
      if(id_farmacia!=21){ //SIN NUTRICIONAL
        //if (id_consultorio==44 || id_consultorio==70){ //onco - hemato
        if(id_farmacia==10){
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 104,
            //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
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
        { if (id_consultorio==44 || id_consultorio==70){ //onco - hemato
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 104,
              //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
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
          { if (id_consultorio==21){ //odonto
              $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/'+ id_farmacia +'/'+ 21,
                //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
                success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
                });                 
                $('#cmbMedicamentoTemp').html(newOption);
                //$("#id_medico").select2({max_selected_options: 4});
                
                }
              });
            }
            else
            {
              if (id_consultorio==72){ //aneste
                $.ajax({
                  //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
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
                $.ajax({
                  url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                  //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
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
            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+'/'+id_farmacia +'/' + id_consultorio,
            success: function(result){                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
              });                 
              $('#cmbMedicamentoTemp').html(newOption);
              //$("#id_medico").select2({max_selected_options: 4});
            
            }
          });
      
        }
      }
      /*else
      {
        if (id_consultorio==104){
          $.ajax({
          url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
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
        {
          if (id_consultorio==21){
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 21 + '/' + dni,
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
          if (id_consultorio==72){
            $.ajax({
              //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ 72,
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
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 105+ '/' + dni,
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
      }*/          
    //}       
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
	// var fecha_atencion = $('#fecha_atencion').val();
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
	var addInterconsulta = $('#AddInterconsultadatosreg').attr("aria-pressed");
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

/*   if(cod_siteds_susalud.trim()=="") msg += "Debe ingresar el <b>Código SITEDS</b>.<br>";
  else $("#cod_siteds_susalud").val(completarConCeros(cod_siteds_susalud,12)); */

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

	  if(fecha_expedicion=="")msg+="Debe ingresar una Fecha de Expedicion<br>";
	
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
	if(addExamenLab == "true"){
		var id_examen_lab = $('input[name="id_examen_lab[]"]').map(function(){return $(this).val();}).get();
		var proce_lab = $('input[name="examen_lab_cantidad[]"]').map(function(){return $(this).val();}).get();
		if(id_examen_lab == "")msg+="Debe registrar un examen de laboratorio<br>";
		if(proce_lab == "")msg+="Debe registrar una cantidad en laboratorio<br>";
    if(proce_lab == 0)msg+="Debe registrar una cantidad mayor a 0 para el examen de laboratorio<br>";
	}else{
    var inputElement = document.querySelector('input[name="id_examen_lab[]"]');
    if(inputElement){
      inputElement.value = '';
    }
  }
	if(addExamenRad == "true"){
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
  if(addInterconsulta == "true"){
    var inter_servicio = $('input[name="nombre_servicio_interconsulta[]"]').map(function(){return $(this).val();}).get();
    var motivo_interconsulta = $('input[name="motivo_interconsulta[]"]').map(function(){return $(this).val();}).get();
    var personal_salud = $('input[name="personal_salud[]"]').map(function(){return $(this).val();}).get();
    if(inter_servicio == "")msg+="Debe registrar un Servicio<br>";
    if(personal_salud == "")msg+="Debe registrar un Personal de Salud<br>";
    if(motivo_interconsulta == "")msg+="Debe registrar un Motivo Interconsulta<br>";
    
  }
   	
    if(msg!=""){
      bootbox.alert({
        message: msg,
        size: 'large'
      });
      $('#btnPrestacion').prop("disabled",false);
      $('.loader').hide();
      return false;
    } else {
      const cod_siteds_susalud = $('#cod_siteds_susalud').val();
      if (!cod_siteds_susalud) {
        const result_siteds = await generarCodigoSitedsHospitalizacion();
        if (result_siteds && result_siteds.nuAutorizacion) {
          $("#cod_siteds_susalud").val(result_siteds.nuAutorizacion);
          $("#frmPrestacion").submit();
          return false;
        }
      }
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
			$("#id_diagnostico"+ind).val(id_diagnostico);
			$("#nombre_diagnostico"+ind).prop("readonly",true);
		}
	});
	
	$("#nombre_diagnostico"+ind).on('blur', function() {
		var id_diagnostico = $("#id_diagnostico"+ind).val();
		if(id_diagnostico == ""){
			bootbox.alert("Falta ingresar el diagnostico en esta fila");
			$("#nombre_diagnostico"+ind).val("");
			return false;
		}else{
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

  var tabindex = 112;
  
  newRow +='<tr>';
  newRow +='<td><div class="panel panel-info panel-bordered panel-sm"><div class="form-group"> <div class="panel-body"> <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>SERVICIO</label></div> <div class="col-md-4" style="margin-left:0px;padding-left:0px"> <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el servicio" name="nombre_servicio_interconsulta[]" tabindex="' + tabindex + '" required="" id="nombre_servicio_interconsulta'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" /> </div> <div class="col-md-1"> <label>Personal de Salud</label> </div> <div class="col-md-6"><select tabindex="72" class="form-control" name="personal_salud[]" id="personal_salud'+ind+'" style="width:100%"><option value="">-Seleccionar-</option></select> </div> <div class="col-md-12"><br/> <label>RESUMEN</label> <textarea class="form-control" cols="5" id="resumen_interconsulta'+ind+'" name="resumen_interconsulta[]" placeholder="Relato m&eacute;dico o caracteristicas de la interconsulta"></textarea></div></div></div></div></td>';
  newRow +='<td><button tabindex="114" type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_servicio_interconsulta" id="id_servicio_interconsulta'+ind+'" name="id_servicio_interconsulta[]" value="" data-id='+ind+'></td>';
  newRow +='</tr>';
  
  var id_ipress     = $('#id_establecimiento').val();

  $('#tblInterconsultaEv tbody').append(newRow);
  $("#nombre_servicio_interconsulta"+ind).autocomplete({
    minLength: 1,
    delay : 400,
    source: function(request, response) {
      $.ajax({
        url: '../obtener_consultorio_interconsulta/' + request.term +'/'+id_ipress,
        dataType: "json",
        success: function(data)  {
          let listaServicioSelecionado = $(".id_servicio_interconsulta").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaServicioSelecionado.includes(element.id));
          response(filtro);
        }         
      })
  
    },select:  function(e, ui) {
      var id_servicio_interconsulta = ui.item.id;
      $("#id_servicio_interconsulta"+ind).val(id_servicio_interconsulta);
      $("#nombre_servicio_interconsulta"+ind).prop("readonly",false);
      getMedico(id_servicio_interconsulta,ind);
    }
    
  });
  $("#nombre_servicio_interconsulta"+ind).on('blur', function() {
    var id_servicio_interconsulta = $("#id_servicio_interconsulta"+ind).val();
    if(id_servicio_interconsulta == ""){
      bootbox.alert("Falta ingresar el servicio en esta interconsulta");
      $("#nombre_servicio_interconsulta"+ind).val("");
      return false;
    }
  }); 
}

function AddExamenLab() {
  var newRow = "";
  var ind = $('#tblExamenLab tbody tr').length;
	
  if (ind > 0) {
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

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind +'" name="id_examen_lab[]" value="" data-id='+ind+'></td>';
  newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_lab_cantidad typeahead tt-query" style="margin:0 5px;width:95%" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  newRow += '<td><select style="margin:0 10px;width:95%" class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"></select></td>';
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
      $("#id_examen_lab" + ind).val(id_examen_lab);
      $("#nombre_examen_lab" + ind).prop("readonly", true);
      validaExamenSeleccionado($("#id_examen_lab" + ind).val());
      consultaWsResultExamenSeleccionado($("#id_examen_lab" + ind).val());
    }
  });
	
  $("#nombre_examen_lab" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar el examen laboratorio en esta fila");
      $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });
  $("#examen_lab_cantidad" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
      $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });
} 

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
  $.ajax({
      url: "../../modal_diagnostico_paciente_nuevo/"+id_prestacion,
      type: "GET",
      success: function (result) {  
          $("#divDiagnostico").html(result);
      }
  });

}

function modalProcedimiento(){
  
  var id_prestacion = $("#id_prestacion").val();
  $.ajax({
      url: "../../modal_procedimiento_paciente_nuevo/"+id_prestacion,
      type: "GET",
      success: function (result) {  
          $("#divProcedimiento").html(result);
      }
  });

}



function modalLaboratorio(){
  
  var dni_beneficiario = $("#dni_beneficiario").val();
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

  newRow +='<tr>';
  newRow +='<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de Radiografia" name="nombre_examen_rad[]" tabindex="8" required="" id="nombre_examen_rad' +ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind +'" name="id_examen_rad[]" value="" data-id='+ind+'></td>';
  newRow +='<td><select class="form-control id_comparativo" id="cmbComparativo'+ind+'" name="id_comparativo[]" style="margin:0 20px;width:150px">'+item_comparativo+'</select></td>';
  newRow +='<td><select class="form-control id_lado" id="cmbLado'+ind+'" name="id_lado[]" style="margin:0 20px;width:150px">'+item_lado+'</select></td>';
  newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_rad_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_rad_observacion' +ind + '" name="examen_rad_observacion[]"></td>';
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 5px;width:50%" id="examen_rad_cantidad' +ind + '" name="examen_rad_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelectRad' + ind + '" name="miSelectRad[]" style="margin:0 10px;width:95%"></select></td>';
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
        url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 3,
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
      $("#id_examen_rad" + ind).val(id_examen_rad);
      $("#nombre_examen_rad" + ind).prop("readonly", true);
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

  newRow +='<tr>';
  newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
	
  //newRow +='<td><input type="text" name="nro_stocks_almacen[]" required id="nro_stocks_almacen" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  //newRow +='<td><input type="text" name="nro_stocks_establecimiento[]" required id="nro_stocks_establecimiento" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
  <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
  newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind +'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo +'"/>';
	newRow +='<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion'+ind+'" name="id_via_administracion[]" style="margin:0 20px;width:150px">'+item_via_administracion+'</select></td>';

  newRow += '<td><input type="text" name="posologia[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
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
  
  function completarConCeros(numero, longitud) {
    let numeroString = numero.toString();
    while (numeroString.length < longitud) {
      numeroString = '0' + numeroString;
    }
    return numeroString;
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
          Swal.fire({
            icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
            html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${result.message || "Código SITEDS actualizado correctamente"}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
          });
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
            resolve(xhr.responseJSON);
        }, 
        complete: (xhr, status) => {
          Swal.hideLoading();
        }
      })
    });
  }

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
</style>
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
                              <input type="hidden" name="id_medico" id="id_medico" value="{{$datos_evaluacion->id_medico}}">
                              <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$id_prestacion}}">
                              <input type="hidden" name="edadpaciente" id="edadpaciente" value="{{$datos_evaluacion->edadpaciente}}">
                              <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" value="{{$id_hospitalizacion}}">
                              <input type="hidden" name="turno" id="turno" value="{{$turno}}">
                              <input type="hidden" value="{{$datos_evaluacion->nro_doc_ident}}" name="dni_beneficiario" id="dni_beneficiario" class="form-control">
                              <input type="hidden" name="nro_historia" id="nro_historia" value="{{$datos_evaluacion->nro_historia}}">
                              <input type="hidden" name="codigo_upss" id="codigo_upss" value="{{$codigo_upss}}">
                              
                              
                              <input type="hidden" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_atencion" id="fecha_atencion">
                              <input type="hidden" value="{{$fechaServidor}}" name="fecha_hora_atencion">
                              <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
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
                                          <label>FECHA DE ATENCION</label>
                                          <p><?php echo date("d-m-Y", strtotime($fechaServidor)); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                          <label>N° HISTORIA CLINICA</label>
                                          <p>{{$datos_evaluacion->nro_historia}}</p>
                                        </div>
                                        <div class="col-md-3">
                                          <label for="dni_beneficiario">{{$descripcion}}</label>
                                          <p>{{$datos_evaluacion->servicio}}</p>
                                        </div>
                                        {{--<div class="col-md-3">
                                          <label>{{$descripcion2}}</label>
                                          <p></p><!-- $datos_evaluacion->consultorio  -->
                                        </div>
                                        <div class="col-md-2">
                                          <label for="dni_beneficiario">CODIGO UPSS</label>
                                          <p>{{$codigo_upss}}</p>
                                        </div>--}}
                                        <?php 
                                          $is_valid_cod_siteds = true;
                                          if (!isset($codigo_siteds) || substr($codigo_siteds, -1) === 'T' || strlen($codigo_siteds) <= 10) {
                                            $is_valid_cod_siteds = false;
                                          }
                                        ?>
                                        <div class="col-md-offset-3 col-md-2">
                                          @if ($is_valid_cod_siteds)
                                            <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                                          @endif
                                          <input 
                                            type="{{$is_valid_cod_siteds ? 'text' : 'hidden'}}" 
                                            class="form-control" 
                                            id="cod_siteds_susalud" 
                                            name="cod_siteds_susalud"
                                            onkeypress="return esEntero(event);"
                                            style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"
                                            maxlength="12" 
                                            pattern="[0-9]*" 
                                            value="{{$codigo_siteds}}" 
                                            readonly
                                          >
                                          <input type="hidden" value="{{$codigo_siteds_valido}}" id="codigo_siteds_valido">
                                        </div>
                                      </div>
                                      
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
                                                                      if($row->lado_radio==1):
                                                                        echo "Izquierdo";
                                                                      else:
                                                                        echo "Derecho";
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
                                  <div class="panel-body" id="VerSignosVitales">
                                    <div class="form-group">
                                      <div class="col-sm-12" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                        <label>SIGNOS VITALES</label>
                                      </div>
                                    </div>
                                    <div class="panel panel-info panel-bordered panel-sm" id="fsSignosVitales">
                                      <!-- <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>INFORMACIÓN DE SIGNOS VITALES</b>
                                        </div>
                                      </div> -->
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>TEMPERATURA (°C) &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                            <input placeholder="00.00" value="" type="text" name="temperatura" id="temperatura" class="form-control" tabindex="23" onkeypress="return esNumero(event,this.id)" maxlength="5" />
                                            <span id="texto-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>TALLA (cm) &nbsp;</label>
                                            <input value="" type="text" name="talla" id="talla" class="form-control" tabindex="24" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="talla-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PESO (Kg) &nbsp;</label>
                                            <input placeholder="00.00" value="" type="text" name="peso" id="peso" class="form-control"  tabindex="25" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="6" >
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
                                            <input value="" type="text" name="saturacion" id="saturacion" class="form-control" tabindex="28" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="saturacion-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PRESIÓN ARTERIAL &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                            <div class="row">
                                              <div class="col-xs-5">                          
                                                <input value="" tabindex="29" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                                <span id="presion_arterial_x-aceptado" style="font-size:12px"></span>
                                              </div>  
                                                <div class="col-xs-2">
                                                   <label>/</label> 
                                                </div>
                                              <div class="col-xs-5">
                                                <input value="" type="text" tabindex="30" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                                <span id="presion_arterial_y-aceptado" style="font-size:12px"></span>
                                              </div>  
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>FREC. RESPIRATORIA (/min) <span style="font-weight: bold; color: red;">(*)</span></label>
                                            <input value="" type="text" name="frecuencia_respiratoria" tabindex="31" id="frecuencia_respiratoria" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="2" >
                                            <span id="frecuencia_respiratoria-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. CARDIACA (/min) &nbsp;<span style="font-weight: bold; color: red;">(*)</span></label>
                                            <input value="" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="32" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="frec_cardiaca-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Fi02 (%) &nbsp;</label>
                                            <input value="" type="text" name="oxigenacion" id="oxigenacion" tabindex="33" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="oxigenacion-aceptado" style="font-size:12px"></span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>DIURESIS</label>
                                            <input value="" type="text" name="diuresis" id="diuresis" tabindex="34" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>
                                          <div class="col-md-2">
                                            <label>BALANCE HÍDRICO</label>
                                            <input value="" type="text" name="balance_hidrico" id="balance_hidrico" tabindex="35" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                          </div>
                                          <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                            <label>FREC. CARDIACA FETAL(/min)</label>
                                            <input tabindex="36" value="" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                            <span id="frecuencia_cardiaca_fetal-aceptado" style="font-size:12px"></span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
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
                                          <textarea tabindex="36" class="form-control" rows="11" id="apreciacion_subjetiva" name="apreciacion_subjetiva"
                                            placeholder="Apreciación Subjetiva"></textarea>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-lg-12 col-md-12">
                                          <label>OBJETIVO <span style="font-weight: bold; color: red;">(*)</span></label>
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
                                          <textarea tabindex="36" class="form-control" rows="6" id="plan_trabajo" name="plan_trabajo"
                                            placeholder="Terapéutica y plan de trabajo."></textarea>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
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
                              <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>UBICACIÓN/ ESTADO PACIENTE</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="form-group row">
                                              <div class="col-md-2">
                                                <label>FECHA INICIO</label>
                                                <input tabindex="2" type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_inicio" id="fecha_inicio" style="text-align:left" data-date-end-date="0d">
                                              </div>
                                              <div class="col-md-2">
                                                <label>HORA</label>
                                                <input type="time" id="appt" name="appt" value="<?php echo $hora; ?>" class="form-control" required>
                                              </div>
                                              <div class="col-md-2">
                                                <label>AREA ACTUAL</label>
                                                <select id="id_area" name="id_area"class="form-control" style="width:100%" disabled>
                                                  <option value="0">-Seleccione-</option>
                                                  @foreach($cuartos as $e)
                                                    <option value="{{$e->id}}" @if($e->id == $dato_cama->id_cuarto) selected @endif>{{$e->pabellon}} - {{$e->nombre}}</option>
                                                  @endforeach
                                                </select>
                                              </div>
                                              <div class="col-md-2">
                                                <label>CAMA/CAMILLA</label>
                                                <select id ="id_cama" name="id_cama" class="form-control" style="width:100%">
                                                  <option value="0">-Seleccione-</option>
                                                  @foreach($camas as $e)
                                                    <option value="{{$e->id}}" @if($e->id == $dato_cama->id) selected @endif>{{$e->nombre}}</option>
                                                  @endforeach
                                                </select>
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
                                            <?php endforeach;?>
                                          @else
                                            <option value="128">Farmacia Dosis Unitaria PIP I del Hospital Nacional PNP Luis N. Saenz</option>
                                            <option value="20">Farmacia Dosis Unitaria del Hospital Nacional PNP Luis N. Saenz</option>
                                            <option value="21">Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                            <option value="10">Farmacia Oncologica del Hospital Nacional PNP Luis N. Saenz</option>
                                            
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
                                  <div class="panel panel-info panel-bordered panel-sm panel-body">
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
                                               <th width="35%">Observación</th>
                                              <th width="10%">Cantidad</th>
                                              <th width="10%">Diagnóstico</th>
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
                                              <th width="5%">Comparativo</th>
                                              <th width="5%">Lado</th>
                                              <th width="20%">Observación</th>
                                              <th width="5%">Cant</th>
                                              <th width="30%">Diagnóstico</th>
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
                                            <table>
                                              <tr>
                                                  <td>
                                                    <button tabindex="111" type="button" id="addInterconsulta2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Interconsulta</button>
                                                  </td>
                                              </tr>
                                            </table>
                                            <br />
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
                              </div>

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
@include('frontend.includes.footer')
@endsection