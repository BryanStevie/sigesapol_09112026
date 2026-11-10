@extends('frontend.layouts.master')
@section('renderjs')
<script src='../funciones/function.js'></script> 
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

  .swal2-icon.swal2-info {
    font-size: 20px !important;
  }
</style>

<script>

const decimalRegex = /^\d+(\.\d+)?$/;
var fechaHoraCompleto = new Date('{{$cita->created_at}}');
var procedimientosSeleccionados =[];
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

  $(document).ready(function() {

    
    
    $('#btnPrestacion').on("click", function (e) {
      guardarPrestacion();
    });
    
    $('#addDiagnostico').on('click',function(){
      AddDiagnostico();
    });
    
    $('#addProcedimiento').on('click',function(){
      AddProcedimiento();
    });

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
    
    AddDiagnostico();
    modalHistorial();
    modalLaboratorio();
    modalRadiologico();
    modalInterconsulta();

  });

  $("#codigo_upss").select2({
    width: 'resolve'
  });
  
  $("#especializacion_medico").select2({
    width: 'resolve'
  });

  $('#fecha_atencion').on('change', function () {
    let fechaAtencion = $(this).val();
    let hospFechaIngreso = $('#hosp_fecha_ingreso').val();
    let hosHoraIngreso = $('#hora_ingreso').val(); 
    let hosHoraEgreso = $('#hora_alta_medica').val();  
    
    if (!fechaAtencion || !hospFechaIngreso) return;

    const parseFecha = (fecha) => {
        const [dia, mes, anio] = fecha.split('-').map(Number);
        return new Date(anio, mes - 1, dia); 
    };

    const fechaAtencionDate = parseFecha(fechaAtencion);
    const hospFechaAtencionDate = parseFecha(hospFechaIngreso);
    const estado = Number({{ $cita->estado }});

    if (estado !== 6 && fechaAtencionDate < hospFechaAtencionDate) {
        Swal.fire({
            title: "<strong><u>ALERTA</u></strong>",
            icon: "warning",
            html: `La fecha de atención no puede ser anterior a la fecha de ingreso <b>(${hospFechaIngreso} ${hosHoraIngreso})</b>.`,
            confirmButtonText: 'OK',
        }).then(() => {
            $(this).val(hospFechaIngreso); 
        });
    }

    // if (estado === 6 && fechaAtencionDate > hospFechaAtencionDate) {
    //   Swal.fire({
    //     title: "<strong><u>ALERTA</u></strong>",
    //     icon: "warning",
    //     html: `La fecha de atención no puede ser mayor a la fecha de ingreso <b>(${hospFechaIngreso} ${hosHoraIngreso})</b> </br> porque su hora de egreso fue a las <b>(${hosHoraEgreso})</b>.`,
    //     confirmButtonText: 'OK',
    //   }).then(() => {
    //     $(this).val(hospFechaIngreso);
    //   });
    // }

    $("#hora_atencion").val(moment().format('HH:mm'));
  });

  $('#hora_atencion').on('change', function() {
    const timeAttention = $(this).val();
    const timeAttentionMoment = moment(timeAttention, 'HH:mm');
    const timeCurrent = moment();
    const dateAttention = $('#fecha_atencion').val();
    if (fecha_atencion) {
      const dateAttentionMoment = moment(dateAttention, 'DD-MM-YYYY');
      const dateCurrentMoment = moment();
      if (dateAttentionMoment.isSame(dateCurrentMoment, 'day')) {
        if (timeAttentionMoment.isAfter(timeCurrent)) {
          $(this).val(timeCurrent.format('HH:mm'));
          Swal.fire({
            title: "<strong><u>ALERTA</u></strong>",
            icon: "info",
            html: `La hora de atención no puede ser mayor a la hora actual (${timeCurrent.format('HH:mm')})`,
            confirmButtonText: 'OK',
          });
        }
      } 
    }
  });

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

 function guardarPrestacion() {
    let fechaAtencion = $('#fecha_atencion').val();
    let hospFechaIngreso = $('#hosp_fecha_ingreso').val();
    let hospFechaAltaMedica = $('#hosp_fecha_alta_medica').val();
    let horaAtencion = $('#hora_atencion').val(); 
    let hosHoraIngreso = $('#hora_ingreso').val(); 
    let hosHoraEgreso = $('#hora_alta_medica').val();  

    const estado = Number({{ $cita->estado }});

    function parseFechaYHora(fecha, hora) {
          if (!fecha || !hora) return null;
          const partes = fecha.split("-");
          if (partes.length !== 3) return null;
          const [dia, mes, anio] = partes;
          const fechaFormateada = `${anio}-${mes}-${dia} ${hora}`;
          return new Date(fechaFormateada);
      }
     

    if (fechaAtencion && hospFechaIngreso && hospFechaAltaMedica && hosHoraIngreso && hosHoraEgreso) {
        const fechaAtencionDate = parseFechaYHora(fechaAtencion, horaAtencion);
        const fechaIngresoDate = parseFechaYHora(hospFechaIngreso, hosHoraIngreso);
        const fechaEgresoDate = parseFechaYHora(hospFechaAltaMedica, hosHoraEgreso);

        if (estado === 6 && fechaAtencionDate < fechaIngresoDate) {
            Swal.fire({
                title: "<strong><u>ALERTA</u></strong>",
                icon: "warning",
                html: `La fecha de registro de la atención no puede ser menor a la fecha y hora de ingreso 
                       <b>(${hospFechaIngreso} / ${hosHoraIngreso})</b>.`,
                confirmButtonText: 'OK',
            }).then(() => {
                $('#fecha_atencion').val(hospFechaIngreso);
                $('#hora_atencion').val(hosHoraIngreso);
            });
            return;
        }

        if (estado === 6 && fechaAtencionDate > fechaEgresoDate) {
            Swal.fire({
                title: "<strong><u>ALERTA</u></strong>",
                icon: "warning",
                html: `La fecha de registro de la atencion no debe ser mayor a la fecha y hora de alta médica
                      <b>(${hospFechaAltaMedica} / ${hosHoraEgreso})</b>.`,
                confirmButtonText: 'OK',
            }).then(() => {
                $('#fecha_atencion').val(hospFechaAltaMedica);
                $('#hora_atencion').val(hosHoraEgreso);
            });
            return;
        }
    
    } 

    Swal.fire({
      title: '<h5><b>REGISTRANDO...</b></h5>',
      showLoaderOnConfirm: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      onOpen: () => {
        Swal.showLoading();
      }
    });
    
    var msg = "";
      
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    var codigo_upss = $('#codigo_upss').val();
    var id_modalidad = $('#id_modalidad').val();
    var fecha_atencion = $('#fecha_atencion').val();
    var hora_atencion = $('#hora_atencion').val();
    var id_servicio= $('#id_servicio').val();
    var id_sub_consultorio= $('#id_sub_consultorio').val();
    var dni_beneficiario= $('#dni_beneficiario').val();
    var beneficiario= $('#beneficiario').val();
    var especializacion_medico = $('#especializacion_medico').val();
    var codigo_upss_profesional = $('#codigo_upss_profesional').val();

    if(fecha_atencion=='')msg+="Debe Registrar la Fecha de Atención<br>";
    if(hora_atencion=='')msg+="Debe Registrar la Hora de Atención<br>";
    if(codigo_upss=='0')msg+="Debe seleccionar un Codigo de UPSS<br>";
    if(especializacion_medico=='0')msg+="Debe seleccionar la especialidad del profesional<br>";
    if(codigo_upss_profesional=='0')msg+="Debe seleccionar un Codigo de UPSS del Especialista<br>";
    
    let isRequiredCIE10 = true;

    const id_profesion = $('#id_profesion').val();

    const servicesExceptions = [10, 278, 279, 152, 65, 131, 162, 165, 182, 243, 244, 245, 259, 272];
    if (especializacion_medico && servicesExceptions.includes(parseInt(especializacion_medico))) {
      isRequiredCIE10 = false;
    }

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
        if (isRequiredCIE10) {
          msg+="Debe ingresar minimo un diagnostico<br>";
          msg+="Debe ingresar el tipo de diagnostico<br>";
        }
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
          if (isRequiredCIE10) {
            if(miSelect == "" || miSelect==null)msg+="Debe seleccionar el diagnostico para el procedimiento<br>";
          }
        }
      }else{
        if (isRequiredCIE10) {
          msg+="Debe ingresar minimo un procedimiento<br>";
        }
      }

      var observacion = $('#observacion').val();  
      if(observacion == "")msg+="Debe ingresar la descripción del procedimiento<br>";

    if(msg!=""){
        Swal.hideLoading();
        Swal.close();
        bootbox.alert({
          message: msg,
          size: 'large'
        });
        $('#frmConsejeria').prop("disabled",false);
        $('.loader').hide();
        return false;
    }else{
      // Alertar si existe una atencion con los paremetros (tipo atencion, fecha atención, servicio, profesional)

      $.ajax({
        "dataType": 'json',
        "type": "POST",
        "url": "{{ route('consejeria.verificacion') }}",
        "data":{
                  id_tipo_atencion : id_tipo_atencion,
                  fecha_atencion : fecha_atencion,
                  especializacion_medico : especializacion_medico
               },
        "success": function (result) {
          if (result > 0) {
            console.log(result);
            Swal.fire({
                title: "<strong><u>ALERTA</u></strong>",
                icon: "info",
                html: `Existe un registro con el mismo tipo de atención, fecha de atención, servicio y el profesional. ¿Desea continuar con el registro? `,
                showCancelButton: true, // Muestra el botón de "Cancelar"
                confirmButtonText: 'Continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lógica cuando el usuario hace clic en "Continuar"
                    console.log('El usuario eligió continuar.');
                    $("#frmConsejeria").submit();
                } else if (result.isDismissed) {
                    // Lógica cuando el usuario hace clic en "Cancelar"
                    console.log('El usuario canceló la acción.');
                    return;
                }
            });

          } else {
            $("#frmConsejeria").submit();
          }
        },
        "error": function (msg, textStatus, errorThrown) {
            //location.href="login";
        }
      });
    }
  }

  var texto_marca_agua_hist_consejeria_hsp = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';

  function generar_marca_agua_hist_consejeria_hsp() {
    var overlay = document.getElementById('marca_agua_overlay_hist_consejeria_hsp');
    if (!overlay) return;
    overlay.innerHTML = '';
    var W = overlay.offsetWidth  || 800;
    var H = overlay.offsetHeight || 600;
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_hist_consejeria_hsp;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }

  $(document).ready(function () {
    var resizeMarcaAguaHistConsejeriaHspTimer = null;
    $(window).on('resize', function () {
      clearTimeout(resizeMarcaAguaHistConsejeriaHspTimer);
      resizeMarcaAguaHistConsejeriaHspTimer = setTimeout(function () {
        if ($('#tab4default').is(':visible')) generar_marca_agua_hist_consejeria_hsp();
        if ($('#tab1007default').is(':visible')) generar_marca_agua_hist_interconsulta_hsp();
      }, 250);
    });
  });

  var texto_marca_agua_hist_interconsulta_hsp = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
  function generar_marca_agua_hist_interconsulta_hsp() {
    var overlay = document.getElementById('marca_agua_overlay_hist_interconsulta_hsp');
    if (!overlay) return;
    overlay.innerHTML = '';
    var W = overlay.offsetWidth  || 800;
    var H = overlay.offsetHeight || 600;
    var cols = Math.ceil(W / 220) + 1;
    var rows = Math.ceil(H / 100) + 1;
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var span = document.createElement('span');
        span.textContent = texto_marca_agua_hist_interconsulta_hsp;
        span.style.left = (c * 220 - 40) + 'px';
        span.style.top  = (r * 100 - 20) + 'px';
        overlay.appendChild(span);
      }
    }
  }

  function modalHistorial(){
    var dni_beneficiario = $("#nro_dni_paciente").val();
    $.ajax({
        url: "../modal_historial_atenciones/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divHistorial").html(result);
            $('#tablemodalm').DataTable({
              "order": [],
              "sPaginationType": "full_numbers",
              "language": {"url": "/js/Spanish.json"},
            });
            generar_marca_agua_hist_consejeria_hsp();
        }
    });
  }

  function modalLaboratorio(){
    var dni_beneficiario = $("#nro_dni_paciente").val();
    $.ajax({
        url: "../modal_historial_laboratorio/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divLaboratorio").html(result);
            $('#tablemodalm1').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
            generar_marca_agua_hist_interconsulta_hsp();
        }
    });
  }
  
  function modalRadiologico(){
    var dni_beneficiario = $("#nro_dni_paciente").val();
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
    var dni_beneficiario = $("#nro_dni_paciente").val();
    $.ajax({
        url: "../modal_historial_interconsulta/"+dni_beneficiario,
        type: "GET",
        success: function (result) {  
            $("#divInterconsultaH").html(result);
            $('#tablemodalm5').DataTable({
              "sPaginationType": "full_numbers",
              
              "language": {"url": "/js/Spanish.json"},
            });
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
                      //location.href="login";
                  }
              });
    }
    }
  }

  function AddDiagnostico(){
    const lastRowValid = validateLastRowTable("tblDiagnostico");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p>REGISTRAR CONSEJERIA</p>',
        html: '<p class="descripcion-alert">Por favor, complete todos los campos para agregar un nuevo diagnóstico.</p>', 
      });
      return null;
    }

    let newRow = "";
    let ind = $('#tblDiagnostico tbody tr').length;
    
    if (ind > 0) {
      $('.id_diagnostico').each(function () {
        const ind_tmp = $(this).data("id");
        if (ind_tmp >= ind) {
          ind = Number(ind_tmp) + 1;
        }
      });

    const fila_medicamentos = $('#tblDiagnostico tbody').children('tr');
    const idval = ind - 1;
    const fila_medicamento = fila_medicamentos[idval];
   }
  
   const item_tipo_diagnostico = `
      <option value="0">--Seleccionar--</option>
      <option value="1">PRESUNTIVO</option>
      <option value="2">DEFINITIVO</option>
      <option value="3">REITERATIVO</option>
    `;

    const item_categoria_diagnostico = `
      <option value="0">--Seleccionar--</option>
      <option value="1">PRINCIPAL</option>
      <option value="2">SECUNDARIO</option>
    `;

    newRow += `<tr>`;
    newRow += `<td>
        <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico"
          name="nombre_diagnostico[]" tabindex="44" required id="nombre_diagnostico${ind}"
          class="input-sm" style="margin-left:4px; width:100%; text-align:left" />
        <input type="hidden" class="form-control id_diagnostico typeahead tt-query"
          id="id_diagnostico${ind}" name="id_diagnostico[]" value="" data-id="${ind}">
      </td>`;
    newRow += `<td>
        <select tabindex="45" class="form-control id_tipo_diagnostico"
          id="cmbTipoDiagnostico${ind}" name="id_tipo_diagnostico[]"
          style="margin:0 0 0 20px;width:150px">
          ${item_tipo_diagnostico}
        </select>
      </td>`;
    newRow += `<td>
        <select tabindex="45" class="form-control id_categoria_diagnostico"
          id="cmbCategoriaDiagnostico${ind}" name="id_categoria_diagnostico[]"
          style="margin:0 0 0 20px;width:150px">
          ${item_categoria_diagnostico}
        </select>
      </td>`;
    newRow += `<td>
        <button type="button" tabindex="46" class="btn btn-danger deleteFila btn-xs"
          style="margin-left:20px"><i class="fa fa-times"></i> Eliminar</button>
      </td>`;
    newRow += `</tr>`;

    $('#tblDiagnostico tbody').append(newRow);

    // const sexo	= $('#nomsexo').val();
    // let genero = 0;

    // if (sexo === 'M') {
    //   genero = 1;
    // } else if (sexo === 'F') {
    //   genero = 2;
    // }
    
    $(`#nombre_diagnostico${ind}`).autocomplete({
      minLength: 1,
      delay : 400,
      source: function(request, response) {
        $.ajax({
          url: `../obtener_diagnostico/${request.term}/`,
          dataType: "json",
          success: function (data) {
            const listaDiagnosticoSeleccionado = $(".id_diagnostico")
              .map(function () { return parseInt($(this).val()); }).get();
            const filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
            response(filtro);
          }
        });
      },
      select:  function(e, ui) {
        const id_diagnostico = ui.item.id;
        $("#id_diagnostico"+ind).val(id_diagnostico);
        $("#nombre_diagnostico"+ind).prop("readonly",true);
      }
    });
    
    $(`#nombre_diagnostico${ind}`).on('blur', function () {
      const id_diagnostico = $(`#id_diagnostico${ind}`).val();
      if (id_diagnostico === "") {
        bootbox.alert("Falta ingresar el diagnóstico en esta fila");
        $(`#nombre_diagnostico${ind}`).val("");
      } else {
        addNewArray(id_diagnostico);
      }
    });
  }

  function AddProcedimiento() {
        const lastRowValid = validateLastRowTable("tblProcedimiento");
        if (!lastRowValid) {
        Swal.fire({
            icon: "warning",
            title: '<p>REGISTRAR CONSEJERIA</p>',
            html: '<p class="descripcion-alert">Por favor, complete todos los campos para agregar un nuevo procedimiento.</p>', 
        });
        return null;
        }
        var newRow = "";
        var ind = $('#tblProcedimiento tbody tr').length;
        const id_nivel = $("#id_nivel").val();
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
        if (id_nivel && parseInt(id_nivel) === 2) {
          newRow += '<td><input type="text" maxlength="3" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_tiempo' + ind + '" name="procedimiento_tiempo[]" value="1"></td>';
        }
        newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
        newRow +=
        '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow += '</tr>';
        $('#tblProcedimiento tbody').append(newRow);
        const selectElement = document.getElementById('miSelect' + ind);
        let defaultOption = document.createElement('option');

        var i = 0;
        listIdDiagnosticoObjetos.forEach((item) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        if (i==0){
            option.setAttribute("selected", "selected");  
            i++;
        }
        selectElement.appendChild(option);
        });

        // var nivel = $('#id_nivel').val();
        // var sexo = $('#nomsexo').val();
        // if (sexo == 'M')
        // genero = 1;
        // else
        // genero = 2;
        
        //tipo = 1
        $("#nombre_procedimiento" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            $.ajax({
            url: '../obtener_procedimiento/' + request.term,
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

  $(document).ready(function () {
    $('#tabs a').on('shown.bs.tab', function (e) {
      const targetTab = $(e.target).attr("href"); // Obtener el id de la pestaña activa
      console.log(targetTab);
      
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
            container: 'swal-confidencialidad-consejeria-hsp-backdrop'
          }
        });

        generar_marca_agua_hist_consejeria_hsp();

        $.ajax({
          url: '/auditoria-hc/registrar',
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            origen: 'Registro Procedimientos HSP(HA)',
            doc_buscado: $('#nro_dni_paciente').val(),
            id_tipo_atencion: null
          }
        });
      }

      if (targetTab === "#tab1007default") {
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
            container: 'swal-confidencialidad-interconsulta-hsp-backdrop'
          }
        });

        generar_marca_agua_hist_interconsulta_hsp();

        $.ajax({
          url: '/auditoria-hc/registrar',
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            origen: 'Registro Procedimientos HSP(Hist. Interc)',
            doc_buscado: $('#nro_dni_paciente').val(),
            id_tipo_atencion: null
          }
        });
      }
    });
  });
  
  function getUpssHosp() {
    var id_consultorio = $('#especializacion_medico').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    //var valor2=1;
    // if(id_tipo_atencion == 5 || id_tipo_atencion == 7 ){
    //   id_tipo_atencion = 1; 
    // }
    
    $.ajax({
      url: '/obtener_upss_hos/' + id_consultorio + '/' + id_tipo_atencion,
      success: function(result) {
        var newOption = "";
        var reg = result.length;
        if (reg >= 2) {
          newOption = "<option value='0'>--Seleccionar-</option>";
        }
        $(result).each(function(ii, oo) {
          newOption += "<option value='" + oo.id + "'>" + oo.codigo + ' - ' + oo.descripcion_upss + "</option>";
        });
        $('#codigo_upss_profesional').html(newOption);
        $("#codigo_upss_profesional").select2({
          max_selected_options: 4
        });
      }
    });
  }

  function validateLastRowTable(tableId) {
    let lastRow = $(`#${tableId} tbody tr:last`);
    let isValid = true;
    lastRow.find('input:visible, select:visible').each(function() {
      if (String($(this).val()).trim() === '' || $(this).val() === '0') {
        isValid = false;
      }
    });
    return isValid; 
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
  .swal2-icon.swal2-warning {
    font-size: 15px;
  }
  .texto-tamaño{
    font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 11px;
  }
  .size-titulo {
    font-size: 1.2rem;
    font-weight: 700;
    text-align: center;
  }
  .texto-tamaño{
      font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
      font-size: 12px;
  }
  .table-borderless tbody > tr > td,.table-borderless tbody > tr > th{
    padding: 2px 8px !important;
    border: 0px solid;
  }
  .table-borderless{
    margin-bottom: 0px !important;
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

  .swal-confidencialidad-consejeria-hsp-backdrop.swal2-backdrop-show {
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.4) !important;
  }

  .swal-confidencialidad-interconsulta-hsp-backdrop.swal2-backdrop-show {
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
                            <h3 class="panel-title">MÓDULO REGISTRO DE PROCEDIMIENTOS</h3>
                          </div>
                          <div class="panel-body">
                            <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->
                            <form class="form-horizontal" method="post" action="{{ route('consejeria.store')}}"
                              id="frmConsejeria" name="frmConsejeria" autocomplete="off" enctype="multipart/form-data">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $nivel?>">
                              <input type="hidden" name="turno" id="turno" value="<?php echo $turno?>">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia"
                                id="id_farmacia">
                              <input type="hidden" name="id_cita" id="id_cita" value="<?php echo $id_cita?>">
                              <input type="hidden" name="id_establecimiento" id="id_establecimiento"
                                value="<?php echo $cita->id_establecimiento?>">
                              <input type="hidden" name="id_asegurado" id="id_asegurado"
                                value="<?php echo $cita->id_asegurado?>">
                              <input type="hidden" name="id_servicio" id="id_servicio"
                                value="<?php echo $cita->id_servicio?>">
                              <input type="hidden" name="id_sub_consultorio" id="id_sub_consultorio"
                                value="<?php echo $cita->id_sub_consultorio?>">
                              <input type="hidden" name="id_medico" id="id_medico"
                                value="<?php echo $cita->id_medico?>">
                                <input type="hidden" value="<?php //echo $cita->id_prioridad; ?>" name="id_prioridad_actual" id="id_prioridad_actual">
                                <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $id_tipo_atencione?>">
                                <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
                              <input type="hidden" id="frmPuerperaActive" name="frmPuerperaActive" value="false">
                              <input type="hidden" name="id_servicio_cama" id="id_servicio_cama" value="{{isset($cama) && isset($cama->id_consultorio) ? $cama->id_consultorio : ""}}">
                              <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" value="{{$hospitalizacion->id}}">
                              <input type="hidden" name="hosp_fecha_atencion" id="hosp_fecha_atencion" value="{{isset($atencion) && isset($atencion->fecha_atencion) ?  $atencion->fecha_atencion : ""}}">
                              <input type="hidden" name="hosp_fecha_ingreso" id="hosp_fecha_ingreso" value="{{date('d-m-Y', strtotime($hospitalizacion->fecha_ingreso))}}">
                              <input type="hidden" name="hosp_fecha_alta_medica" id="hosp_fecha_alta_medica" value="{{date('d-m-Y', strtotime($hospitalizacion->fecha_alta_medica))}}">
                              <input type="hidden" name="hora_ingreso" id="hora_ingreso" value="{{ date('g:i A', strtotime($hospitalizacion->hora_ingreso)) }}">
                              <input type="hidden" name="hora_alta_medica" id="hora_alta_medica" value="{{ date('g:i A', strtotime($hospitalizacion->hora_alta_medica)) }}">
                              <input type="hidden" name="id_profesion" id="id_profesion" value="{{$cita->id_profesion}}">
                              <input type="hidden" id="id_servicio_encargado" name="id_servicio_encargado" value="{{$servicio->id}}" />
                            
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="panel panel-info" style="height: 83%;">
                                      <div class="panel-heading">
                                        <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                                      </div>
                                      <div class="panel-body">
                                        <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;width: 24%;"><strong>N° DOCUMENTO</strong></th>
                                              <th style="text-align: left; border: 0px solid;width: 12%;"><strong>:</strong></th>
                                              <td style="border: 0px solid;width: 40%;">
                                                  <input id="nro_dni_paciente" type="hidden" value="<?php echo $cita->nro_doc_ident?>" />
                                                  <span id="nro_doc_ident" style="font-weight: bold; margin-left: -53px;"><?php echo $cita->tipo_doc_ident?> - <?php echo $cita->nro_doc_ident?> </span>
                                              </td>
                                              <th style="text-align: left; border: 0px solid;width: 9%;"><strong>N° DE HC</strong></th>
                                              <th style="text-align: left; border: 0px solid;width: 2%;"><strong>:</strong></th>
                                              <td style="border: 0px solid; text-transform: uppercase;">
                                                  <span id="nro_historia" style="font-weight:bold;"><?php echo $cita->nro_historia?></span>
                                              </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid; text-align: left;" colspan="4">
                                                <span id="nombre_paciente" style="margin-left: -53px;">
                                                    <?php echo trim("{$cita->asegurado_paterno} {$cita->asegurado_materno} {$cita->asegurado_nombre}"); ?>
                                                </span>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid; text-align: left; padding-left: 0;" colspan="4">
                                                <span id="edad" style="margin-left: -53px">
                                                    <?php echo isset($cita->fecha_nac) ? $cita->fecha_nac : ''; ?>
                                                    - 
                                                        EDAD (
                                                        <?php
                                                            $fecha_nac = DateTime::createFromFormat('d/m/Y', $cita->fecha_nac);
                                                            $hoy = new DateTime();
                                                            if ($fecha_nac !== false) {
                                                                $edad = $fecha_nac->diff($hoy);
                                                                echo $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                                                            } else {
                                                                echo "Formato de fecha inválido";
                                                            }
                                                        ?>
                                                        )
                                                </span>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>SEXO</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0; position: relative; padding-left: 0;">
                                              <span id="sexo" style="position: relative; text-transform: uppercase; left: -52px;">
                                                <?php echo isset($cita->sexo) ? ($cita->sexo == "M" ? "MASCULINO" : "FEMENINO") : ''; ?>
                                              </span>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>TIPO DE BENEFICIARIO</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid; text-transform: uppercase;">
                                                <span id="tipo_beneficiario" style="position: relative; text-transform: uppercase; left: -52px;"><?php echo $cita->parentesco; ?></span>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>DATOS DEL TITULAR</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid; text-transform: uppercase;" colspan="4">
                                                <span id="dato_titular_name" style="margin-left: -53px;"><?php echo trim("{$dato_titular[0]->paterno} {$dato_titular[0]->materno} {$dato_titular[0]->nombre}");?></span>
                                            </td>
                                          </tr>
                                          <tr class="texto-tamaño">
                                            <th style="text-align: left; border: 0px solid;"><strong>CIP/DNI/GRADO</strong></th>
                                            <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                            <td style="border: 0px solid;" colspan="4">
                                                <span id="nrodoctitular" style="margin-left: -53px;"><?php echo $cita->cip_titular;?> / <?php echo $dato_titular[0]->nro_doc_ident;?> / <?php echo $cita->grado_titular;?></span>
                                            </td>
                                          </tr>
                                        </table>
                                      </div>
                                   </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="panel panel-info contenedor-datos-generales" style="height: 215px;">
                                    <div class="panel-heading">
                                      <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
                                    </div>
                                    <div class="panel-body" style="margin: 3px 3px;">
                                      <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                        <tr class="texto-tamaño">
                                          <th style="text-align: left; border: 0px solid;width: 26%;"><strong>COD.SITEDS SUSALUD</strong></th>
                                          <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                          <td style="border: 0px solid; text-transform: uppercase; font-weight:bold;">
                                              <span id="cod_siteds_susalud">{{$cita->cod_siteds_susalud }}</span>
                                          </td>
                                          <th style="text-align: left; border: 0;"><strong>HORA DE ATENCIÓN</strong></th>
                                          <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                          <td style="border: 0px solid;">
                                            <input type="time" name="hora_atencion" id="hora_atencion"
                                              value="<?php echo date('H:i', strtotime($fechaServidor)); ?>"
                                              class="form-control" style="width: 95px;margin-top:-11px;" required>
                                          </td>
                                        </tr>
                                        <tr class="texto-tamaño">
                                          <th style="text-align: left; border: 0px solid;"><strong>TIPO DE ATENCIÓN</strong></th>
                                          <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                          <td style="border: 0px solid;">
                                            <span id="tipo_atencion" style="font-weight:bold;">HOSPITALIZACION</span>
                                          </td>
                                        </tr>
                                        <tr class="texto-tamaño">
                                          <th style="text-align: left; border: 0px solid;"><strong>FECHA DE INGRESO</strong></th>
                                          <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                          <td style="border: 0px solid;">
                                            <span id="fecha_ingreso">
                                              {{
                                                \Carbon\Carbon::parse(
                                                  \Carbon\Carbon::parse($hospitalizacion->fecha_ingreso)->format('Y-m-d') . ' ' . $hospitalizacion->hora_ingreso
                                                )->format('d-m-Y g:i A')
                                              }}
                                            </span>
                                          </td>
                                        </tr>
                                        <tr class="texto-tamaño">
                                          <th style="text-align: left; border: 0px solid;"><strong>FECHA DE ATENCIÓN</strong></th>
                                          <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                          <td style="border: 0px solid;">
                                           <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                            name="fecha_atencion" id="fecha_atencion" autocomplete="off" data-date-end-date="0d" style="width:63%;"/>
                                          </td>
                                        </tr>
                                        <tr class="texto-tamaño">
                                          <th style="text-align: left; border: 0;"><strong>SERVICIO DE PROCEDENCIA</strong></th>
                                          <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                          <td style="border: 0;" colspan="4">
                                            <select class="form-control" name="codigo_upss" id="codigo_upss" tabindex="8" style="width: 74%;">
                                              @foreach ($upsses as $upss)
                                                <option value="{{ $upss->id }}">{{ $upss->codigo }} - {{ $upss->descripcion_upss }}</option>
                                              @endforeach
                                            </select>
                                          </td>
                                        </tr>
                                        @if (isset($cama) && isset($cama->cama))
                                        <tr class="texto-tamaño">
                                          <th style="text-align: left; border: 0;"><strong>UBICACIÓN DE PACIENTE</strong></th>
                                          <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                          <td style="border: 0;" colspan="4">
                                            <span id="consultorio" style="font-weight:bold;">{{ $cita->consultorio }}</span>
                                            <span style="margin: 0 5px;font-weight:bold;">/</span>
                                            <strong>CAMA</strong><strong>:</strong>
                                            <span id="cama" style="font-weight:bold;">{{ $cama->cama }}</span>
                                          </td>
                                        </tr>
                                        @endif
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title size-titulo">
                                    <h3 class="panel-title size-titulo">DATOS DEL REGISTRO DE PROCEDIMIENTO</h3>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <div class="panel-body">
                                    <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                      <tr class="texto-tamaño">
                                        <th style="text-align: left; border: 0px solid; width: 15%;"><strong>RESPONSABLE DE LA ATENCION</strong></th>
                                        <th style="text-align: left; border: 0px solid; width:2%;"><strong>:</strong></th>
                                        <td style="border: 0px solid;">
                                          <span id="tipo_doc_medico" type="hidden">
                                            <?php echo $cita->tipo_doc_ident ?> - <?php echo $cita->nro_dni_medico ?> / <?php echo trim("{$cita->medico_paterno} {$cita->medico_materno} {$cita->medico_nombre}"); ?>
                                          </span>
                                        </td>
                                      </tr>
                                      <tr class="texto-tamaño">
                                        <th style="text-align: left; border: 0px solid;"><strong>PROFESION/ESPECIALIDAD</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid;">
                                          <span id="profesion_medico" style="margin-left: -2px;">
                                            <?php echo $cita->profesion_medico?> / <?php echo $cita->especializacion_medico?>
                                          </span>
                                        </td>
                                      </tr>
                                      <tr class="texto-tamaño">
                                        <th style="text-align: left; border: 0px solid;"><strong>N° CMP / N° RNE</strong></th>
                                        <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                        <td style="border: 0px solid;" colspan="4">
                                            <span id="medico" style="margin-left: -2px;"><?php echo $cita->colegiatura_medico;?> / <?php echo $cita->rne_medico;?></span>
                                        </td>
                                      </tr>
                                      <tr class="texto-tamaño">
                                        <th style="text-align: left; border: 0;"><strong>DEPARTAMENTO</strong></th>
                                        <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                        <td style="border: 0;" colspan="4">
                                          <div style="width: 35%; margin-left:-4px;">
                                            <select class="form-control"
                                                    name="especializacion_medico"
                                                    id="especializacion_medico"
                                                    tabindex="8"
                                                    onchange="getUpssHosp();">
                                              <option value="0">--Seleccionar--</option>
                                              @foreach ($consultorios_medicos as $cm)
                                                <option value="{{ $cm->id_consultorio }}">{{ $cm->nombre_consultorio }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                        </td>
                                        <th style="text-align: right; border: 0 solid;width:23%;"><strong>CODIGO UPSS ESPECIALIDAD</strong></th>
                                        <th style="text-align: left; border: 0 solid;width:2%;"><strong>:</strong></th>
                                        <td style="border: 0; colspan="4">
                                            <select class="form-control texto-tamaño" name="codigo_upss_profesional" id="codigo_upss_profesional" style="width:80%;">
                                              <option value="0">--Seleccionar--</option>
                                            </select>
                                        </td>
                                      </tr>
                                    </table>
                                  </div>
                                </div> 
                              </div>

                              {{-- DIAGNOSTICOS --}}
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title size-titulo">
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
                                          <th>Clasificación</th>
                                        </tr>
                                        @if (isset($diagnostico))
                                          @foreach($diagnostico as $row)
                                            @if ($row->id_clasificacion_diagnosticos != 9)
                                              <?php
                                                $tipoDiagnostico = '';
                                                switch($row->id_tipo_diagnostico) {
                                                  case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                                                  case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                                                  case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                                                }
                                                $categoria = '';
                                                if ($row->id_categoria != null) {
                                                  $categoria = ($row->id_categoria == 1) ? 'PRINCIPAL' : 'SECUNDARIO';
                                                }
                                              ?>
                                              <tr class="row-diagnosticosfinal">           
                                                <td><input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->codigo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                                                <td><input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->nombre }}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                                                <td><input name="id_tipo_diagnosticos['{{ $row->id_diagnostico }}']" id="" value="{{ $tipoDiagnostico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                                                <td><input type="text" value="{{ $categoria }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly></td>
                                              </tr>
                                            @endif
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
                              {{-- PROCEDIMIENTOS --}}
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title size-titulo">
                                    <b>PROCEDIMIENTOS</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  
                                  <div class="panel panel-default">
                                    <div class="table-responsive">
                                      <table class="table table-striped table-condensed">
                                        <tr>
                                          <th>Código</th>
                                          <th>Nombre</th>
                                          <th>Cantidad</th>
                                          <th>Diagnóstico</th>
                                        </tr>
                                        @if (isset($procedimiento))
                                          @foreach($procedimiento as $row)
                                            <input type="hidden" value="{{ $row->cod }}" name="prestacion_procedimiento_id[]" style="width : 1px; height : 1px">
                                            <tr class="row2-{{ $row->cod }}">
                                              <td><input type="text" name="codigodeprod[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->codigo }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                              <td>
                                                {{$row->nombre}}
                                                <input name="id_procedimientos[{{ $row->cod }}]" id="idProcedimientos" class=" input-procedimiento form-control" value="{{$row->nombre}}" type="hidden" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                                              </td>
                                              <td><input type="text" name="cantidad_procedimientos[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->cantidad }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                              <td>
                                                <input name="iddiagnosticosxProcedimiento[{{ $row->id_diagnostico }}]" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly
                                                value="<?php
                                                $model_diagnosticos = new \App\Models\Producto_receta;
                                                $diagnostico_id = $row->id_diagnostico;
                                                $diagnostico_nombre = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id);
                                                echo $diagnostico_nombre;
                                                ?>">
                                              </td>
                                            </tr>
                                          @endforeach
                                        @endif
                                      </table>
                                    </div>
                                  </div>

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
                                          @if (isset($nivel) && intval($nivel) === 2)
                                            <th width="5%">Tiempo</th>
                                          @endif
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
                              
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-heading">
                                  <div class="panel-title size-titulo">
                                    <b>DESCRIPCIÓN</b>
                                  </div>
                                </div>
                                <div class="panel-body">
                                  <div class="form-group col-md-12">
                                    <label for="descripcion">DESCRIPCIÓN</label>
                                    <textarea class="form-control" id="observacion" name="observacion" rows="3"></textarea>
                                  </div>
                                </div>
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
            </div>
            <div class="tab-pane fade marca_agua_container" id="tab4default" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_consejeria_hsp"></div>
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
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_interconsulta_hsp"></div>
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
</div>

@include('frontend.includes.footer')
@endsection