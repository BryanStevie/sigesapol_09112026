@extends('frontend.layouts.master')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}

<style>

  .title-dialog {
    font-size: 18px;
    font-family:Arial, Helvetica, sans-serif;
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
    font-family:Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }

  .ui-autocomplete > li > div {
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
    color: #FFFFFF!important;
    background: #1d71b8!important;
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

  input[readonly]{
      background-color:#eeeeee;
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
  .uneditable-input:focus{   
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }
.table-borderless tbody > tr > td,.table-borderless tbody > tr > th{
  
  padding: 2px 8px !important;
  border: 0px solid;
  font-size: 10px;
  font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
select{
  font-size: 12px !important;
}

  input[type=checkbox] {
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
</style>
<script>
// Script completo que incluye comentario_examen_repetida
// Script completo con estructura de contenedor Bootstrap
/*var informacionExamenes = {!! json_encode($informacion) !!};
console.log(informacionExamenes);*/

regularizeCodeSiteds($("#id_cita").val(),$("#id_asegurado").val(),$("#id_establecimiento").val(),$("#id_servicio").val());
async function regularizeCodeSiteds(id, aseguradoId, establecimientoId, servicioId) {
    Swal.fire ({
        title:  "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
        html: "<div style='margin: 0px 0px 20px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento, por favor...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        },
    });
    const body = {
        "idCorrelativo": id,
        "aseguradoId": aseguradoId,
        "establecimientoId": establecimientoId,
        "tipoAtencionId": "21",
        "servicioId": servicioId
    };
    console.log(body);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url:'/v2/ws-dirsapol-siteds',
        type:'POST',
        contentType: "application/json",
        dataType: "json",
        headers: {
                'X-CSRF-TOKEN': csrfToken
            },
        data: JSON.stringify(body),
        success: (result, textStatus, jqXHR) => {
          /*if (result && result.nuAutorizacion && (parseInt(jqXHR.status) === 200 || parseInt(jqXHR.status) === 201)) {
            // updateSiteds(id, result.nuAutorizacion, result.message);
          } else {*/
            // cosnole.log(result.)
            $("#cod_siteds_susalud").val(result.nuAutorizacion);
            $(".cod_siteds_susalud").html(result.nuAutorizacion);
            const session = {!! json_encode(Auth::user()) !!}
            const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
            if(hasPermissionUpdateSitedsManual){
              $('#cod_siteds_susalud').removeAttr('readonly');
            }
            
            Swal.close();
          // }
        },
        error: function(xhr, status, error){
            const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
            Swal.fire({
                icon: 'error',
                title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                confirmButtonText: 'ENTENDIDO',
            });
        }, 
        complete: function(xhr, status) {
            Swal.hideLoading();
        }
    })
  }


$(document).ready(function() {
    // Cargar tabla con datos iniciales
    /*cargarTabla();
    
    // Evento para el botón de agregar fila
    $('#addRowBtn').click(function() {
        agregarNuevaFila();
    });
    
    // Evento para el botón de calcular totales
    $('#calculateTotalsBtn').click(function() {
        calcularTotales();
    });
    
    // Delegación de eventos para los botones y selects dinámicos
    $(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove();
    });
    
    $(document).on('change', '.upss-select', function() {
        var tr = $(this).closest('tr');
        var upssValue = $(this).val();
        var upssText = $(this).find('option:selected').text();
        
        // Actualizar el diagnóstico y comentario correspondiente a este UPSS
        actualizarDiagnosticoYComentario(tr, upssValue, upssText);
    });*/
});

/*function cargarTabla() {
    // Primero, agregar el contenedor con información del médico, servicio, diagnóstico
    actualizarInfoContenedor(informacionExamenes);
    $("#cantidad_diagnostico").text(informacionExamenes.length);
    // Limpiar tabla primero
    $('#tableBody').empty();
    
    // Primero, vamos a recopilar todos los valores únicos de UPSS para cada id_procedimiento
    var upssValoresPorProcedimiento = {};
    
    // También contaremos cuántas veces aparece cada id_procedimiento
    var contadorProcedimientos = {};
    
    informacionExamenes.forEach(function(examen) {
        // Contar apariciones de cada id_procedimiento
        if (!contadorProcedimientos[examen.id_procedimiento]) {
            contadorProcedimientos[examen.id_procedimiento] = 1;
        } else {
            contadorProcedimientos[examen.id_procedimiento]++;
        }
        
        // Recopilar UPSS, diagnósticos y comentarios únicos
        if (!upssValoresPorProcedimiento[examen.id_procedimiento]) {
            upssValoresPorProcedimiento[examen.id_procedimiento] = [];
        }
        
        // Verificar si esta combinación de UPSS no existe ya para este procedimiento
        // Importante: ahora comprobamos tanto el código como la descripción UPSS
        var existeUPSS = upssValoresPorProcedimiento[examen.id_procedimiento].some(function(item) {
            return item.codigo_upss === examen.codigo_upss && 
                   item.descripcion_upss === examen.descripcion_upss;
        });
        
        if (!existeUPSS) {
          console.log(1212,examen);
            upssValoresPorProcedimiento[examen.id_procedimiento].push({
                codigo_upss: examen.codigo_upss,
                descripcion_upss: examen.descripcion_upss,
                diagnostico_codigo: examen.diagnostico_codigo,
                diagnostico: examen.diagnostico,
                comentario_examen_repetida: examen.comentario_examen_repetida,
                servicio: examen.servicio,
                medico_paterno: examen.medico_paterno,
                medico_nombre: examen.medico_nombre,
                medico_amterno: examen.medico_amterno,
                id_prestacion_procedimiento : examen.id_prestacion_procedimiento,
                id_laboratorio:examen.id_laboratorio,
                id_evaluacion:examen.id_evaluacion,
                id_rua:examen.id_rua
            });
        }
    });
    
    // Crear un objeto para rastrear los procedimientos ya agregados
    var procedimientosAgregados = {};
    var cantidadExamenes = 0;

    // Ahora, cargar solamente un registro por cada id_procedimiento
    informacionExamenes.forEach(function(examen) {
        if (!procedimientosAgregados[examen.id_procedimiento]) {
          cantidadExamenes++;
            // Este procedimiento no se ha agregado, lo agregamos con su cantidad
            var cantidad = contadorProcedimientos[examen.id_procedimiento];
            agregarFilaConDatos(examen, upssValoresPorProcedimiento[examen.id_procedimiento], cantidad);
            procedimientosAgregados[examen.id_procedimiento] = true;
        }
    });
    $('#cantidad_analisis').text(cantidadExamenes);
}*/

function formatearFecha(fechaTimestamp) {
    if (!fechaTimestamp) return '';
    
    // Crear objeto Date
    var fecha = new Date(fechaTimestamp);
    
    // Obtener componentes de la fecha
    var dia = ('0' + fecha.getDate()).slice(-2);
    var mes = ('0' + (fecha.getMonth() + 1)).slice(-2);
    var año = fecha.getFullYear();
    
    // Obtener componentes de la hora
    var horas = fecha.getHours();
    var minutos = ('0' + fecha.getMinutes()).slice(-2);
    var segundos = ('0' + fecha.getSeconds()).slice(-2);
    
    // Convertir a formato 12 horas y determinar AM/PM
    var ampm = horas >= 12 ? 'PM' : 'AM';
    horas = horas % 12;
    horas = horas ? horas : 12; // La hora 0 debe ser 12
    horas = ('0' + horas).slice(-2);
    
    // Formatear el resultado
    return dia + '/' + mes + '/' + año + ' ' + horas + ':' + minutos + ':' + segundos + ' ' + ampm;
}
function actualizarInfoContenedor(datos) {



    var html ="";
    $.each(datos, function( index, value ) {
      // Formar el nombre completo del médico
    var nombreMedico = `${value.medico_paterno || ''} ${value.medico_nombre || ''} ${value.medico_amterno || ''}`.trim();
    nombreMedico = nombreMedico || 'NO ESPECIFICADO';
    
    // Formar el diagnóstico completo
    var diagnosticoCompleto = value.codigo_diagnostico +" "+ value.diagnostico || 'NO ESPECIFICADO';

    let fecha_atencion = value.fecha_atencion;

    if (fecha_atencion && fecha_atencion.trim() !== "") {
      // Quitar microsegundos
      const fechaLimpia = fecha_atencion.split('.')[0];

      const fecha = new Date(fechaLimpia);

      const opciones = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
      };

      fecha_atencion = formatearFecha(fecha_atencion);
    } else {
      fecha_atencion = "";
    }



    
    // Crear el HTML del contenedor
    html += `
    <tr style="border-bottom:1px solid #e0e9e0; padding-top: 4px;">
        <td>
            <span>${nombreMedico}</span>
            <input type="hidden" class="form-control medico" value="${nombreMedico}" readonly>
        </td>
        <td>
            <span>${value.servicio}</span>
            <input type="hidden" class="form-control servicio" value="${value.servicio || 'NO ESPECIFICADO'}" readonly>
        </td>
        <td>
            <span class="texto-con-puntos" title="${diagnosticoCompleto}"> ${value.diagnostico_codigo}</span>
            <input type="hidden" class="form-control diagnostico" value="${diagnosticoCompleto}" readonly>
             <span>(DEFINITIVO)</span>
            <input type="hidden" class="form-control" value="DEFINITIVO" readonly>
        </td>
        <td>
            <span>${fecha_atencion}</span>
            <input type="hidden" class="form-control fecha_atencion" value="${fecha_atencion}" readonly>
        </td>
        <td>
            <span>${(value.comentario_examen_repetida == null ? "":value.comentario_examen_repetida)}</span>
            <input type="hidden" class="form-control observacion_repetido" value="${(value.comentario_examen_repetida == null ? "":value.comentario_examen_repetida)}" readonly>
        </td>
    </tr>`;
    });

    // Insertar o actualizar el contenedor
        $('#infoContainer tbody').html(html);
   
}

function agregarFilaConDatos(datos, opcionesUPSS, cantidad = 1) {
    // Crear las opciones de UPSS para este procedimiento
    var upssOptions = '';
    var id_procedimientos_prestaciones = [];
    var id_laboratorios_array=[];
    var id_evaluacion_array =[];
    var id_rua_array =[];
    if (opcionesUPSS && opcionesUPSS.length > 0) {
        opcionesUPSS.forEach(function(upss) {
            console.log("upss",upss);
            var selected = (upss.codigo_upss === datos.codigo_upss) ? 'selected' : '';
            var comentario = upss.comentario_examen_repetida || '';
            var servicioValue = upss.servicio || '';
            var medicoValue = `${upss.medico_paterno || ''} ${upss.medico_nombre || ''} ${upss.medico_amterno || ''}`.trim();
            upssOptions += `<option value="${upss.codigo_upss}" 
                data-diagnostico="${upss.diagnostico_codigo}" 
                data-comentario="${comentario}" 
                data-servicio="${servicioValue}"
                data-medico="${medicoValue}"
                ${selected}>${servicioValue}</option>`;
                id_procedimientos_prestaciones.push(upss.id_prestacion_procedimiento);
                id_laboratorios_array.push(upss.id_laboratorio);
                id_evaluacion_array.push(upss.id_evaluacion);
                id_rua_array.push(upss.id_rua);
        });
    } else {
        var comentario = datos.comentario_examen_repetida || '';
        var servicioValue = datos.servicio || '';
        var medicoValue = `${datos.medico_paterno || ''} ${datos.medico_nombre || ''} ${datos.medico_amterno || ''}`.trim();

        upssOptions = `<option value="${datos.codigo_upss}" 
            data-diagnostico="${datos.diagnostico_codigo}" 
            data-comentario="${comentario}" 
            data-servicio="${servicioValue}"
            data-medico="${medicoValue}"
            selected>${servicioValue}</option>`;

            id_procedimientos_prestaciones.push(datos.id_prestacion_procedimiento);
            id_laboratorios_array.push(datos.id_laboratorio);
            id_evaluacion_array.push(datos.id_evaluacion);
            id_rua_array.push(upss.id_rua);
    }
    
    // Obtener el comentario para mostrar inicialmente
    var comentarioInicial = datos.comentario_examen_repetida || '-';
    
    var html = `
        <tr data-id-prestacion="${datos.id_prestacion}" data-id-procedimiento="${datos.id_procedimiento}" style="border-bottom: 1px solid;">
            <td>
              <input type="checkbox" class="exam-checkbox exam-checkbox" checked />
                <input type="hidden" name="value_cheack" value="0">
               <span class="status-indicator ml-2" style="color: green; font-weight: bold; font-size:18px;">R</span>
               <input type="text" name="id_prestacion" value="${datos.id_prestacion}"  />
               <input type="text" name="id_procedimiento" value="${datos.id_procedimiento}"  />
               <input type="text" name="id_larabotorio" value="${id_laboratorios_array.join(",")}"  />
               <input type="text" name="id_evaluacion" value="${id_evaluacion_array.join(",")}"  />
               <input type="text" name="id_procediminetos_prestaciones" value="${id_procedimientos_prestaciones.join(",")}">
               <input type="text" name="id_rua" value="${id_rua_array.join(",")}">
            </td>
            <td>${datos.examen_cod} - ${datos.examen_descripcion} (${cantidad})</td>
            <td style="text-align: center;">
                `+generarSelectCantidad(cantidad)+`
            </td>
            <td>
                <select class="form-control upss-select" style="
                    font-size: 11px !important;
                    font-weight: bold;
                    height: 30px;
                    margin: 3px;
                    max-width: 180px;">
                    ${upssOptions}
                </select>
            </td>
            <td style="">
                <select class="form-control pendiente" name="pendiente[]">
                                     <option value="NO" >NO</option>
                                        <option VALUE ="SI">SI</option>
                                  </select>
            </td>
            <td class="diagnostico-cell texto-con-puntos" style="text-align: center;" title="${datos.diagnostico}">${datos.diagnostico_codigo}</td>
            <td class="observacion-cell">${comentarioInicial}</td>
        </tr>
    `;
    
    $('#tableBody').append(html);
}

function generarSelectCantidad(cantidad, valorSeleccionado = 1) {

    return "<input type='hidden' class='cantidad-select' value'1'/><span>1</span>";
    let select = `<select class="form-control cantidad-select">`;
    for (let i = 1; i <= cantidad; i++) {
        let selected = (i == 1) ? 'selected' : '';
        select += `<option value="${i}" ${selected} >${i}</option>`;
    }
    select += `</select>`;
    return select;
}

function actualizarDiagnosticoYComentario(row, upssValue, upssText) {
    // Obtener el option seleccionado
    var opcionSeleccionada = row.find('.upss-select option:selected');
    
    // Buscar el diagnóstico correspondiente a este UPSS
    var diagnostico = opcionSeleccionada.data('diagnostico') || 'Sin diagnóstico disponible';
    
    // Buscar el comentario correspondiente a este UPSS
    var comentario = opcionSeleccionada.data('comentario') || '-';
    
    // Actualizar el diagnóstico en la fila
    row.find('.diagnostico-cell').text(diagnostico);
    
    // Actualizar el comentario/observación en la fila
    row.find('.observacion-cell').text(comentario);
    
      
}



function calcularTotales() {
    var totales = {};
    
    // Recolectar datos de todas las filas
    $('#tableBody tr').each(function() {
        var cpmsText = $(this).find('td:first').text();
        var cpmsCode = cpmsText.split(' - ')[0].trim();
        var quantity = parseInt($(this).find('.quantity-input').val()) || 1;
        
        if (!totales[cpmsCode]) {
            totales[cpmsCode] = {
                descripcion: cpmsText,
                cantidad: 0
            };
        }
        
        totales[cpmsCode].cantidad += quantity;
    });
    
    // Mostrar totales en formato más agradable
    var totalsHtml = '<div class="alert alert-info mt-3">';
    totalsHtml += '<h5>Totales por CPMS:</h5>';
    totalsHtml += '<ul>';
    
    for (var cpmsCode in totales) {
        totalsHtml += `<li><strong>${totales[cpmsCode].descripcion}:</strong> ${totales[cpmsCode].cantidad}</li>`;
    }
    
    totalsHtml += '</ul></div>';
    
    // Mostrar en la página en lugar de alerta
    $('#totalsDisplay').html(totalsHtml).show();
}



$('#btnGuardar').on('click', function () {
  let data = [];

  $('#tableBody tr').each(function () {
    const $row = $(this);

    // Solo tomamos los que estén checkeados
    if (true) {
      const id_prestacion = $row.data('id-prestacion');
      const id_procedimiento = $row.data('id-procedimiento');
      const id_procedimientos_prestaciones = $row.find('input[name="id_procediminetos_prestaciones"]').val();
      const id_laboratorios = $row.find('input[name="id_larabotorio"]').val();
      const id_evaluacion = $row.find('input[name="id_evaluacion"]').val();
      const upss = $row.find('.upss-select').val();
      const cantidad = $row.find('.cantidad-select').val();
      const pendiente = $row.find('.pendiente').val();
      const diagnostico = $row.find('.diagnostico-cell').text().trim();
      const observacion = $row.find('.observacion-cell').text().trim();
      const id_rua = $row.find('input[name="id_rua"]').val();
      const valor_cheack = $row.find('input[name="value_cheack"]').val();

      data.push({
        id_prestacion,
        id_procedimiento,
        id_procedimientos_prestaciones,
        pendiente,
        upss,
        cantidad,
        diagnostico,
        id_laboratorios,
        id_evaluacion,
        id_rua,
        valor_cheack,
        observacion
      });
    }
  });

 

  // Envío por AJAX
  $.ajax({
    url: '/laboratorio_emergencia_insert', // Cambia esto a tu ruta Laravel
    method: 'POST',
    data: {
      //_token: $('meta[name="csrf-token"]').attr('content'), // si usas Laravel y protección CSRF
      prestaciones: data,
      comentario:$("#observacion").val(),
      codSusalud:$("#cod_siteds").val()
    },
    beforeSend: function() {
        $('#loader').show();
    },
    success: function (response) {
      $('#loader').hide();
      if(response.cantidad != 0){
     
        // Sweet Alert notification with action on button click
        Swal.fire({
            icon: 'success',
            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN EXITOSA!</span></div>",
            html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'> Se registraron: ${response.cantidad}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
            // Add this event handler to perform an action when button is clicked
            preConfirm: () => {
                // You can put your custom action here
                console.log('Button clicked - performing action');
                // Example: redirect to another page
                // window.location.href = '/dashboard';
                
                // Example: show another message
                // return Swal.fire('Action completed!', 'Your next step was executed', 'success');
                
                // Example: refresh data
                // fetchData();
            }
        }).then((result) => {
            // This executes after the dialog is closed
            if (result.isConfirmed) {
                console.log('User clicked the confirmation button');
                // Additional actions can go here too
                // For example: resetForm();
                window.location.href = "/all_laboratorio_prescritos_by_ipress";
            }
        });
      }
    },
    error: function (error) {
      console.error('Error al guardar:', error);
    }
  });
});

  // ***
  function validarFormulario() {

    const cod_siteds_susalud = $('#cod_siteds_susalud').val();

    if (cod_siteds_susalud === "") {
      Swal.fire({
        icon: 'warning',
        // title: 'Campo vacío',
        html: 'Por favor, debe ingresar el <b>CODIGO SITEDS de SUSALUD</b>',
        confirmButtonText: 'ENTENDIDO'
      });
      return;
    }
    console.log("antes de las pruebas checkeadas");

    const pruebasCheckeadas = document.querySelectorAll('input[type=checkbox]:checked');
    if (pruebasCheckeadas.length == 0) {
      Swal.fire({
        // title: '¿Estás seguro?',
        html: "No esta seleccionando ningún Exámen.<br><br><b>¿Deseas continuar con el registro?<b>",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'SÍ, CONTINUAR',
        cancelButtonText: 'NO, CANCELAR',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          console.log("confirmó antes");
          registrarFormulario();

        } else if (result.dismiss === Swal.DismissReason.cancel) {
          console.log("no confirmó antes");
        }
      });

    } else {
      registrarFormulario();
    }
  }

  function registrarFormulario() {

    Swal.fire({
      title: '<h5><b>Registrando la Orden ...</b></h5>',
      showLoaderOnConfirm: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
          Swal.showLoading();
      }
    });

    const texto = $('#nro_doc_ident').val();

    // API moderna para copiar al portapapeles
    if (navigator.clipboard) {
      navigator.clipboard.writeText(texto).then(function () {
          $('#mensaje').fadeIn().delay(1500).fadeOut();
      }).catch(function (err) {
          alert('Error al copiar el texto: ' + err);
      });
    } else {
      // Fallback para navegadores antiguos
      const tempInput = $('<input>');
      $('body').append(tempInput);
      tempInput.val(texto).select();
      document.execCommand('copy');
      tempInput.remove();
      $('#mensaje').fadeIn().delay(1500).fadeOut();
    }

    $("#frmOrdenLaboratorio").submit();
      
  }

  function seleccionFilaRegistro(obj) {
    const fila = $(obj).closest('tr');
    const statusIndicator = fila.find('.status-indicator');
    const campos = fila.find('.campo-fila-envio');

    if ($(obj).is(':checked')) {
      campos.prop('disabled', false);
      statusIndicator.text('R').css('color', 'green');
    } else {
      campos.prop('disabled', true);
      statusIndicator.text('SR').css('color', 'red');
    }
  }

  $(document).ready(function() {
    $('#tableBody .exam-checkbox').each(function() {
      seleccionFilaRegistro(this);
    });
  });
</script>



<style>
   .readonly-select {
    background-color: #eee;
    color: #999;
    cursor: not-allowed;
}
  body {
      /* font-family: Arial, sans-serif; */
      /* padding: 20px; */
  }
  .module-header {
      background-color: #3C763D;
      color: white;
      padding: 10px;
      margin-bottom: 20px;
      font-weight: bold;
  }
  .section-header {
      background-color: #DFF0D8;
      padding: 8px;
      margin-bottom: 15px;
      color: #3C763D;
      font-weight: bold;
      text-align: center;
  }
  .form-group {
      margin-bottom: 10px;
  }
  .form-control {
      /* border-radius: 0; */
  }
  .btn-cancel {
      background-color: #B74848;
      color: white;
      border-radius: 0;
  }
  .btn-save {
      background-color: #5CB85C;
      color: white;
      border-radius: 0;
  }
  .alert-box {
      border: 1px solid #ddd;
      padding: 10px;
      margin-top: 10px;
      background-color: #f9f9f9;
      text-align: center;
  }
  .code-highlighted {
      border: 1px solid red;
      padding: 5px;
      color: red;
  }


  .loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    z-index: 9999;
  }

  .spinner {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
    margin-bottom: 10px;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
@endsection
@section('content')

<div id="loader" style="display: none;">
  <div class="loader-overlay">
    <div class="spinner"></div>
    <p>Cargando, por favor espere...</p>
  </div>
</div>
<div class="container">
  <div class="panel panel-default">
    <input type="hidden" id="id_asegurado" value="{{ $paciente[0]->id }}">
    <input id="id_servicio" name="id_servicio" type="hidden" value="1">
    <input id="id_establecimiento" name="id_establecimiento" type="hidden" value="{{$cita[0]->id_establecimiento}}">

    <div class="module-header">
        MÓDULO DE EXÁMENES DE LABORATORIO
    </div>      
    <div class="panel-body">

      <form name="frmOrdenLaboratorio" id="frmOrdenLaboratorio" class="form-horizontal" role="form" method="post"  action="{{ route('registrar_examen_laboratorio_lns_agrupado') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id_evaluacion" value="0" />
        <input type="hidden" name="id_tipo_atencion" value="1" />
        <input type="hidden" id="id_cita" name="id_cita" value="{{$idCita}}">

        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-info contenedor-datos-generales">
              <div class="panel-heading">
                <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
              </div>
              <div class="panel-body">
                <table class="table table-borderless" style="width: 100%; margin: 0px;">
                  <tr>
                    <th style="text-align: left; border: 0px solid;"><strong>N° DE DOCUMENTO</strong></th>
                    <th style="border: 0px solid;"><strong>:</strong></th>
                    <td style="border: 0px solid;">
                      <span id="nro_doc_ident" style="font-weight: bold;">{{ $paciente[0]->tipo_doc_ident }} - {{ $paciente[0]->nro_doc_ident }}</span>
                    </td>
                  </tr>
                  <tr>
                    <th style="text-align: left; border: 0px solid;width: 30%;"><strong>N° DE HISTORIA CLÍNICA</strong></th>
                    <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                    <td style="border: 0px solid;">
                      <span id="nro_historia" style="font-weight: bold;">{{ $paciente[0]->nro_doc_ident }}</span>
                    </td>
                  </tr>
                  <tr>
                    <th style="text-align: left; border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                    <th style="border: 0px solid;"><strong>:</strong></th>
                    <td style="border: 0px solid; text-transform: uppercase;">
                      <span id="nombre_completo" style="font-weight: bold;">{{ $paciente[0]->paterno }} {{ $paciente[0]->materno }}, {{ $paciente[0]->nombre }}</span>
                    </td>
                  </tr>
                    <tr>
                    <th style="text-align: left; border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                    <th style="border: 0px solid;"><strong>:</strong></th>
                    <td style="border: 0px solid;">
                      <span id="edad">
                        <?php  echo isset($paciente[0]->fecha_nac) ? $paciente[0]->fecha_nac : ''; ?>
                                  - <strong>EDAD (
                                                      <?php
                                        $fecha_nac = DateTime::createFromFormat('d/m/Y', $paciente[0]->fecha_nac);
                                        $hoy = new DateTime();

                                        if ($fecha_nac !== false) {
                                            $edad = $fecha_nac->diff($hoy);
                                            echo $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                                        } else {
                                            echo "Formato de fecha inválido";
                                        }
                                        ?>
                                    )</strong>
                      </span>
                    </td>
                  </tr>
                    <tr>
                    <th style="text-align: left; border: 0px solid;"><strong>TIPO BENEFICIARIO</strong></th>
                    <th style="border: 0px solid;"><strong>:</strong></th>
                    <td style="border: 0px solid; text-transform: uppercase;">
                      <span id="tipo_beneficiario" >{{ $paciente[0]->tipo_beneficioario }}</span>
                    </td>
                  </tr>
                
                  <tr>
                    <th style="text-align: left; border: 0px solid;"><strong>SEXO</strong></th>
                    <th style="border: 0px solid;"><strong>:</strong></th>
                    <td style="border: 0px solid; text-transform: uppercase;">
                      <span id="sexo">
                          <?php echo isset($paciente[0]->sexo) ? ($paciente[0]->sexo == "F" ? "Femenino":"Masculino") : ''; ?>
                      </span>
                    </td>
                  </tr>
              
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
              </div>
              <div class="panel-body">
                <table class="table table-borderless" style="width: 100%; margin: 0px;">
                    <tr>
                      <th style="text-align: left; border: 0px solid; "><strong>COD. SITEDS SUSALUD</strong></th>
                      <th style="border: 0px solid; "><strong>:</strong></th>
                      <td style="border: 0px solid;">
                        <span class="cod_siteds_susalud"></span>
                        <input type="hidden" class="form-control" id="cod_siteds_susalud" name="cod_siteds_susalud" readonly style="border: 2px solid red;">
                      </td>
                    </tr>
                    <tr>
                      <th style="text-align: left; border: 0px solid; "><strong>TIPO ATENCIÓN</strong></th>
                      <th style="border: 0px solid; "><strong>:</strong></th>
                      <td style="border: 0px solid; text-transform: uppercase;">
                        <span id="tipo_atencion">AMBULATORIO</span>
                      </td>
                    </tr>
                    <tr>
                      <th style="text-align: left; border: 0px solid; "><strong>PROCEDENCIA</strong></th>
                      <th style="border: 0px solid; "><strong>:</strong></th>
                      <td style="border: 0px solid; text-transform: uppercase;">
                        <span id="procedencia">HOSPITAL NACIONAL PNP LUIS N. SAENZ</span>
                      </td>
                    </tr>
                    <tr>
                      <th style="text-align: left; border: 0px solid; "><strong>SERVICIOS</strong></th>
                      <th style="border: 0px solid; "><strong>:</strong></th>
                      <td style="border: 0px solid; text-transform: uppercase;">
                        <span>{{ implode(', ', $servicios_agrupados) }}</span>
                      </td>
                    </tr>
                    
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-info contenedor-datos-generales" style="    font-size: 11px !important; display: none;">
          <div class="panel-heading">
            <h3 class="panel-title">DIAGNÓSTICOS</h3>
          </div>
          <div class="panel-body" style="margin: 0px 10px;">
            <div class="w-100" style="overflow-x: auto;">
              <div style="width: 1055px">
                <table class="table table-bordered" id="infoContainer" border="0">
                    <thead>
                        <tr>
                            <th style="width: 9%; padding: 5px">PERSONAL MÉDICO</th>
                            <th style="width: 8%;">SERVICIO</th>
                            <th style="width: 7%;">DIAGNOSTICO</th>
                            <th style="width: 7%;">FECHA DE ATENCIÓN</th>
                            <th style="width: 17%;">MOTIVO DE DUPLICIDAD DE EXÁMEN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí irán las filas de datos -->
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="display:none;">
          <div class="col-md-12">
            <h5>CANTIDAD DE DIAGNÓSTICO : <strong  id="cantidad_diagnostico"></strong> </h5>
          </div>
        </div>

        <div class="panel panel-info contenedor-datos-generales" style="    font-size: 11px !important;">
          <div class="panel-heading" style="position: relative; text-align: center;">
            <h3 class="panel-title size-titulo">LISTA DE EXÁMENES CLÍNICOS</h3>
            
            <div style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); white-space: nowrap;">
              <strong>Leyenda:</strong>
              <span style="margin-left: 10px; color: green; font-weight: bold;">R</span>
              <span style="margin-right: 15px;">Con reactivo</span>
              <span style="color: red; font-weight: bold;">SR</span>
              <span>Sin Reactivo</span>
            </div>
          </div>
          <div class="panel-body" style="margin: 0px 10px;">
            <div class="row">
              <div class="col-md-12">
                <table style="width: 100%;"  >
                  <thead>
                    <tr>
                      <!-- <th></th>
                      <th>NOMBRE DE EXAMEN</th>                                              
                      <th style="text-align: center;">CANTIDAD</th>
                      <th style="text-align: center;">SERVICIO</th>
                      <th style="text-align: center;">PENDIENTE</th>
                      <th style="text-align: center;">DIAGNÓSTICO</th>
                      <th>OBSERVACIÓN</th> -->

                      <th style="text-align: left; ">Nombre de Exámen</th>
                      <th style="text-align: center; ">Cantidad</th>
                      <th style="text-align: center; ">Servicio</th>
                      <th style="text-align: center; ">¿Pendiente?</th>                                                               
                      <th style="text-align: center; ">Diagnóstico</th>
                      <th style="text-align: left; ">Tipo de Muestra</th>
                    </tr>
                  </thead>
                  <tbody id="tableBody">
                    <?php
                      $i = 0;
                      foreach($filas as $cpms => $grupo):
                        $row = $grupo['item'];
                        $primeraOpcion = $grupo['opciones'][0];
                    ?>
                    <tr style="border-bottom: 1px solid #e0e9e0 ">
                      <td >
                          <input type="checkbox" class="exam-checkbox" name="prueba[]" checked value="<?php echo $row->id ?>" onchange="seleccionFilaRegistro(this)">
                          
                          <span class="status-indicator ml-2" style="margin-left: 15px; color: green; font-weight: bold; font-size: 20px;">R</span>
                          <span class="checkboxtexto texto-negrita-mayuscula" >&nbsp;&nbsp;&nbsp;<?php echo $row->cpms_personalizado." - ".$row->nombre?></span>
                      </td>
                      <td style="padding-left: 1rem;text-align: center;">
                          <span class=" ml-2" >{{ $row->cantidad }}</span>
                          <input type="hidden" class="form-control input-sm" style="text-align: center;" readonly="" value="{{ $row->cantidad }}" />
                      </td>
                      <td style="text-align: center;">
                        @if (count($grupo['opciones']) > 1)
                          <!-- <select class="form-control readonly-select" disabled title="Primera orden registrada (no modificable)"> -->
                            @foreach ($grupo['opciones'] as $index => $srv)
                              {{-- <option value="{{ $srv['id_lab_rad'] }}" @if($index === 0) selected @endif>{{ $srv['servicio'] }}</option> --}}
                              @if ($index === 0)
                                <span class="form-control-static" style="display:block; padding:6px 12px;">{{ $row->servicio }}</span>
                              @endif
                            @endforeach
                          <!-- </select> -->
                        @else
                          <span class="form-control-static" style="display:block; padding:6px 12px;">{{ $row->servicio }}</span>
                        @endif
                        <input type="hidden" class="campo-fila-envio" name="id_servicio[]" value="{{ $primeraOpcion['id_lab_rad'] }}">
                        <input type="hidden" class="campo-fila-envio" name="id_rua[]" value="{{ $primeraOpcion['id_rua'] }}">
                        <input type="hidden" class="campo-fila-envio" name="cpms[]" value="{{ $row->cpms_personalizado }}">
                      </td>
                      <td>
                        <select class="form-control pendiente campo-fila-envio" name="pendiente[]">
                          <option value="NO">NO</option>
                          <option value="SI">SI</option>
                        </select>
                      </td>
                      <td style="padding-left: 1rem;text-align:center;">
                          <span class=" ml-2 texto-con-puntos" title="{{ $row->relacion_diagnostico }}" >{{ $row->cod_diag }}</span>
                          <input type="hidden" class="form-control input-sm" style="text-align: left;" readonly="" value="{{ $row->relacion_diagnostico }}" />
                      </td>
                      <td style="padding-left: 1rem;">
                          <span class=" ml-2" >{{ $row->nombre_tipo_muestra }}</span>
                          <input type="hidden" class="form-control input-sm" style="text-align: left;" readonly="" value="{{ $row->nombre_tipo_muestra }}" />
                      </td>
                    </tr>
                    <?php
                      // if (!$repetido) $cpmsVistos[] = $row->cpms_personalizado;
                      $i++; 
                      endforeach;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label>CANTIDAD DE ANÁLISIS CLÍNICO: </label><span id="cantidad_analisis"> {{count($filas)}}</span>
          </div>
        </div>
        
        <div class="col-md-12">
            <div class="form-group">
              <label class="control-label" for="observacion">OBSERVACIÓN:</label>
              <input type="text" name="observacion" id="observacion" class="form-control input-sm text-uppercase" autocomplete="off" >
            </div>
        </div>
        <p>&nbsp;</p>
        <div class="row">
          <div class="col-md-6">
            <a href="/all_laboratorio_prescritos_by_ipress" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important">CANCELAR</a>
          </div>
          <div class="col-md-6">
            <!-- <button class="btn btn-save btn-block" id="btnGuardar">GUARDAR</button> -->
            <input onclick="validarFormulario();" type="button" value="GUARDAR" class="form-control btn btn-success" style="text-align:center!important">
          </div>
        </div>
      </form>

    </div>

  </div>
</div>
@include('frontend.laboratorios.modal_solicitud_procedimiento')
@include('frontend.includes.footer')
@endsection