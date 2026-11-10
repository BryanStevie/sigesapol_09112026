@extends('frontend.layouts.master2')
@section('renderjs')
<script src='../funciones/function.js'></script>
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('consultaExterna/js/atencionAdicional.js') }}"></script>
<style>
  .swal2-popup.padding_sweet_alert {
    padding: 10px 60px !important;
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

  .modal-dialog {
    width: 90%;
  }

  .swal-confidencialidad-historial-new-atencion-backdrop.swal2-backdrop-show {
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.4) !important;
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
    text-align: center;
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
.modal-header {
  padding: 0.5rem 1rem 0 1rem;
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
  #miAlertaLaboratorio{
    width: 1000px !important;
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
.table-error {
  border: 1px solid #dc3545;
  background-color: #fee2e2;
}
.label-help {
  color: #dc3545; 
  font-size: 11px; 
  font-weight: bold
}

  .bootbox-custom-small .modal-dialog {
    max-width: 400px;
  }
/*===============TABLA IMAGENOLOGÍA RESPONSIVE FIN=====================================*/ 

  #modalHistorialProductos .modal-dialog {
      width: 95% !important;
      max-width: 1200px;
      margin: 20px auto;
  }
  #modalHistorialProductos .modal-content {
      border-radius: 6px;
      overflow: hidden;
  }

  #modalHistorialProductos .table {
      width: 100%;
      min-width: 700px; 
      font-size: 12px;
  }

  #modalHistorialProductos .table-bordered {
      border: 2px solid #000 !important;
  }

  #modalHistorialProductos .table-bordered > thead > tr > th,
  #modalHistorialProductos .table-bordered > tbody > tr > td {
      border: 1px solid #000 !important;
      color: #000;
      vertical-align: middle;
      padding: 6px;
  }

  #modalHistorialProductos thead th {
      background-color: #f2f2f2 !important;
      font-weight: bold;
      text-align: center;
  }
  .col-numero {
      width: 50px;
  }

  .col-codigo {
      width: 120px;
  }

  .col-descripcion {
    min-width: 250px;
    text-align: justify !important;
    white-space: normal !important; 
  }

  .col-fecha {
      width: 130px;
  }

  #modalHistorialProductos td,
  #modalHistorialProductos th {
      white-space: nowrap;
  }
  #modalHistorialProductos .table-responsive {
      overflow-x: auto;
  }
  #modalHistorialProductos .modal-body {
      overflow-x: auto;
      padding: 15px;
  }
  #modalHistorialProductos thead th.col-descripcion {
      text-align: center !important;
  }

  @media (max-width: 768px) {
      #modalHistorialProductos .modal-dialog {
          width: 98% !important;
          margin: 10px auto;
      }
      #modalHistorialProductos .modal-body {
          padding: 10px;
      }
      #modalHistorialProductos .table {
          min-width: 600px;
          font-size: 11px;
      }
  }

   #btnHistProd {
    width: auto !important;
    padding: 4px 12px;
    font-size: 12px;
    height: 30px;
    white-space: nowrap;
  }

  .swal-noafiliado,
  .swal-afiliado_a_vencer{
    border-radius: 12px;
  }

  .swal-noafiliado .swal2-title,
  .swal-afiliado_a_vencer .swal2-title{
    font-size: 23px !important;
    font-weight: 700;
    font-family: 'Roboto' !important;
  }

  .swal-noafiliado .swal2-html-container,
  .swal-afiliado_a_vencer .swal2-html-container{
    font-size: 17px !important;
    color: #444;
    font-family: 'Roboto' !important;
  }

  .swal-noafiliado .swal2-confirm,
  .swal-afiliado_a_vencer .swal2-confirm{
    font-size: 15px !important;
    padding: 10px 28px !important;
    color: #fff !important; 
    background-color: rgb(48, 133, 214) !important;
  }

  .swal-noafiliado .swal2-icon,
  .swal-afiliado_a_vencer .swal2-icon{
    transform: scale(0.5);
    margin: 0;
    font-size: 32px!important;
  }

  /* Diferencias */
  .swal-noafiliado{
    border: 3px solid orange;
  }

  .swal-afiliado_a_vencer{
    border: 3px solid skyblue;
  }
  .swal-noafiliadosp .swal2-icon {
      transform: scale(0.5);
      margin: 0;
      font-size: 32px!important;
    }
    .swal-noafiliadosp .swal2-icon.swal2-warning {
      border-color: #F2421F;
      color: #F2421F;
    }
    .swal-noafiliadosp .swal2-icon.swal2-warning .swal2-icon-content {
      color: #F2421F;
    }
    .swal-noafiliadosp{
      border-radius:12px;
      border: 3px solid #F2421F;
    }

</style>

<script>
  // let isValidSiteds = false;
  // let messageErroSiteds = "Es necesario generar el código SITEDS antes de continuar con el proceso.";

$(function() {
    $(".datepicker").datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: '0', 
        changeMonth: true,
        changeYear: true
    });
});

      function ocultarBotonGestante(nomsexo) {
          var dni_beneficiario = $('#dni_beneficiario').val();
          var sexo = $('#nomsexo').val(); // campo sexo'
      }
      $(document).ready(function() {
          ocultarBotonGestante();
          $('#dni_beneficiario').on('input', ocultarBotonGestante);
          $("#fsIntervencionQuirugica").hide();
      });

      var procedimientosSeleccionados =[];
      var listIdDiagnostico =[];
      var listIdDiagnosticoObjetos =[];

      var filasmedicamentos = 0;
      function pad(num) {
        return num < 10 ? '0' + num : num;
      }

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

      $(document).ready(function() {
        $("#VerConadis").hide();
        $('#esta_gestando').on('click', function() {
            var esta_gestando = $('#esta_gestando').attr("aria-pressed");
            $("#frsgetante").hide();
            if (esta_gestando == "false") {
                $("#frsgetante").show();
            }
        });
        

  //$('#fecha_atencion').datepicker();

  /*$(".fc-event-container").on("click", function (e) {
  	alert("cssc");
  });*/

  $('input[name="parent"]').on("change", function(e) {
    getServicio();
  });


  /*$('#id_servicio,#id_establecimiento').on("change", function (e) {
  	$('#calendar').fullCalendar('destroy'); 
  	fullCalendar();
  	modalTurnos();
  	modalHistorial();
  });*/

$(document).ready(function() {
  $('#servicio_referencia').select2({
    placeholder: "--SELECCIONAR--",
    allowClear: true,
    width: '100%' 
  });
});

$('#servicio_referencia').on('change', function() {
  const servicioId = $(this).val();

  $('#upss_referencia').empty().append('<option value="">--SELECCIONAR--</option>');

  if (servicioId) {
    $.ajax({
      url: '/obtener_upss_x_servicio/' + servicioId,
      method: 'GET',
      dataType: 'json', 
      success: function(response) {
        console.log("Respuesta de UPSS:", response);

        if (Array.isArray(response) && response.length > 0) {
          response.forEach(function(upss) {
            $('#upss_referencia').append(
              `<option value="${upss.id}">${upss.codigo} - ${upss.descripcion_upss}</option>`
            );
          });
        } else {
          console.warn('La respuesta no es un array válido o está vacía.');
        }
      },
      error: function(xhr, status, error) {
        console.error("Error AJAX:", status, error);
        alert('No se pudieron cargar las UPSS para el servicio seleccionado.');
      }
    });
  }
});

  $(document).ready(function() {

    $("#btnHistProd").click(function() {

        var dni = $("#dni_beneficiario").val();

        $("#modalHistorialProductos").modal("show");

        $("#tbodyhistorial").html(
            '<tr><td colspan="5">Cargando...</td></tr>'
        );

        $.get('/recetas/historial/' + dni, function(data) {

            var html = '';
            var i = 1;

            if (data.length > 0) {

                data.forEach(function(item) {

                   html += '<tr>' +
                      '<td class="col-numero">' + (i++) + '</td>' +
                      '<td class="col-codigo">' + item.cod_producto + '</td>' +
                      '<td class="col-descripcion">' + item.descripcion + '</td>' +
                      '<td class="col-fecha">' + formatearFecha(item.fecha_prescripcion) + '</td>' +
                      '<td class="col-fecha">' + formatearFecha(item.fecha_dispensacion) + '</td>' +
                  '</tr>';

                });

            } else {
                html = '<tr><td colspan="5">No hay registros</td></tr>';
            }

            $("#tbodyhistorial").html(html);

        }).fail(function() {
            $("#tbodyhistorial").html(
                '<tr><td colspan="5">Error al cargar datos</td></tr>'
            );
        });
    });
  });

  function formatearFecha(fecha) {
    if (!fecha) return '';
      var partes = fecha.split('-'); 
      return partes[2] + '/' + partes[1] + '/' + partes[0];
  }

  //TODO
            $(document).ready(function() {
              $('#modalContraReferencia').hide();
              $('#modalDatosReferencia').hide();
              $('#modalDiagnosticosReferencia').hide();
              $('#modalProcedimientosReferencia').hide();
                $(".datepicker").datepicker({
                    dateFormat: 'dd-mm-yy'
                });
                $(document).ready(function() {

                    $('#ultimaMestruacion').appendDtpicker({
                        "dateOnly": true,
                        "autodateOnStart": false,
                        "minuteInterval": 5,
                        "amPmInTimeList": false,
                        "locale": "es",
                        "closeOnSelected": true,
                        "dateFormat": "DD-MM-YYYY",
                        "maxDate": new Date() 
                    });

                    $('#parto_probable').appendDtpicker({
                        "dateOnly": true,
                        "autodateOnStart": false,
                        "minuteInterval": 5,
                        "amPmInTimeList": false,
                        "locale": "es",
                        "closeOnSelected": true,
                        "dateFormat": "DD-MM-YYYY",
                        "minDate": new Date() 
                    });
                });


                // Calcular la fecha probable de parto
                function calcularFechaProbableParto() {
                    var ultimaMestruacion = $("#ultimaMestruacion").datepicker("getDate");
                    var fechaProbableParto = new Date(ultimaMestruacion.getTime() + (7 * 24 * 60 * 60 *
                        1000)); // Sumar 7 dÃ­as
                    fechaProbableParto.setMonth(fechaProbableParto.getMonth() - 3); // Restar 3 meses
                    fechaProbableParto.setFullYear(fechaProbableParto.getFullYear() + 1); // Sumar 1 aÃ±o

                    // Formatear la fecha probable de parto y establecerla como valor del campo de entrada
                    var formattedDueDate = $.datepicker.formatDate('dd-mm-yy', fechaProbableParto);
                    $("#parto_probable").val(formattedDueDate);
                }

                // Llamar a la funcion de cÃ¡lculo cuando se cambie la fecha de la Ãºltima menstruacion
                $("#ultimaMestruacion").change(calcularFechaProbableParto);
            });
            $('#btnPrestacion').on("click", function(e) {
              consultarProcedimientosProgramadosPendientes();
            });

            /********************* Procedimientos programados *********************/
            window.consultarProcedimientosProgramadosPendientes = function() {
              let procedimientos = $('input[name="id_procedimiento_p[]"]')
                .filter(function() {
                  return this.value.trim() !== '';
                })
                .map(function() {
                  return this.value;
                }).get();
              if ($('#pp_activo').val() != '1' || procedimientos.length === 0) {
                guardarPrestacion();
                return;
              }
              $.ajax({
                url: '/consultarSolicitudProcedimientoPendiente',
                method: 'POST',
                data: {
                  '_token': $('input[name="_token"]').val(),
                  'nro_doc_asegurado': $("#dni_beneficiario").val(),
                  'procedimientos_id': procedimientos
                },
                success: function(res) {
                  if (res.length > 0) {
                    const procedimientos_data = res.map((procedimiento) => {
                      return `${procedimiento.codigo_procedimiento} - ${procedimiento.nombre_procedimiento} (Nro solicitud: ${procedimiento.solicitud.id})`;
                    });
                    Swal.fire({
                      title: '¿Deseas continuar?',
                      text: `Se encontraron los procedimientos ${procedimientos_data.join(", ")} pendientes ya solicitados. ¿Deseas proceder?`,
                      icon: 'warning',
                      showCancelButton: true,
                      width: '80%',
                      customClass: {
                        popup: 'padding_sweet_alert'
                      },
                      confirmButtonText: 'Sí',
                      cancelButtonText: 'No'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        guardarPrestacion();
                      }
                    });
                  } else {
                    guardarPrestacion();
                  }
                },
                error: function(err) {
                  console.error('Error:', err);
                  guardarPrestacion();
                },
              });
            };

            $('#addProcProgra').on('click', function() {
              if (parseInt($('#id_servicio').val() || 0) <= 0) {
                bootbox.alert("Seleccione el <b>Servicio</b> antes de agregar un procedimiento programado.");
                return false;
              }
              AddProcedimientoProgramado();
            });

            $(document).on('change', '#id_servicio', function() {
              if ($('#tblSolicitudPp .pp-item').length > 0) {
                $('#tblSolicitudPp').html('');
                bootbox.alert("Se cambió el servicio: vuelva a agregar los procedimientos programados.");
              }
            });

            $('#addProcedimientoProgramado').on('click', function() {
              var addProcedimientoProgramado = $('#addProcedimientoProgramado').attr("aria-pressed");
              $("#fsProcedimientosProgramados").hide();
              $("#pp_activo").val(0);
              if (addProcedimientoProgramado == "false") {
                $("#fsProcedimientosProgramados").show();
                $("#pp_activo").val(1);
              }
            });

            $('#tblSolicitudPp').on('click', 'button.deleteFila', function() {
              $(this).closest('.pp-item').remove();
            });

            window.modalInformesPp = function() {
              var dni = $("#dni_beneficiario").val();
              if (!dni) return;
              $.ajax({
                url: "/historial_atenciones_procprogramado/" + dni,
                type: "GET",
                dataType: "json",
                success: function(data) {
                  $('#tblHistorialInformes').off('draw.dt.marcaAgua').on('draw.dt.marcaAgua', function() {
                    if (typeof generar_marca_agua_hist_pp === 'function' && $('#tab_INFORMES_PP').is(':visible')) generar_marca_agua_hist_pp();
                  });
                  var ppHayInforme = (data || []).some(function(r) { return parseInt(r.tiene_informe) === 1 || r.gastro; });
                  $('#tblHistorialInformes').DataTable({
                    data: data || [],
                    destroy: true,
                    order: [[4, 'desc']],
                    language: {
                      emptyTable: "El paciente no tiene procedimientos programados atendidos",
                      lengthMenu: "Mostrar _MENU_ registros",
                      search: "Buscar:",
                      info: "Mostrando _START_ al _END_ de _TOTAL_ registros",
                      infoEmpty: "Mostrando 0 al 0 de 0 registros",
                      infoFiltered: "(filtrado de _MAX_ registros)",
                      zeroRecords: "No se encontraron resultados",
                      paginate: { previous: "Previo", next: "Siguiente" }
                    },
                    columns: [
                      { data: 'establecimiento' },
                      { data: 'nro_doc', className: 'text-center' },
                      { data: 'servicio_solicitante' },
                      { data: 'descripcion', render: function(data, type, row) { return row.codigo + ' - ' + data; } },
                      { data: 'fecha_atencion', className: 'text-center', render: function(data, type, row) {
                          return type === 'sort' ? row.fecha_orden : data;
                        }
                      },
                      { data: 'medico' },
                      { data: null, orderable: false, className: 'text-center', render: function(data, type, row) {
                          if (parseInt(row.puede_ver) !== 1) {
                            return `<button type="button" class="btn btn-success btn-sm" disabled title="Atención de otra IPRESS"><i class="fa fa-eye"></i></button>`;
                          }
                          return `<button type="button" class="btn btn-success btn-sm btnVerAtencionPPHist" title="${row.codigo} - ${row.descripcion}"
                            data-id-solicitud="${row.id_solicitud}" data-id-procedimiento="${row.id_procedimiento}"><i class="fa fa-eye"></i></button>`;
                        }
                      },
                      { data: null, orderable: false, className: 'text-center', visible: ppHayInforme, render: function(data, type, row) {
                          // Gastroenterología: botones de su informe en vez del informe subido
                          if (row.gastro) {
                            var htmlGastro = '';
                            [['baja', 'Endoscopia Baja'], ['alta', 'Endoscopia Alta']].forEach(function(p) {
                              if (parseInt(row.gastro[p[0]]) !== 1) return;
                              htmlGastro += parseInt(row.puede_ver) !== 1
                                ? `<button type="button" class="btn btn-primary btn-sm" style="margin:1px" disabled title="${p[1]} - Informe de otra IPRESS"><i class="fa fa-file-pdf-o"></i></button>`
                                : `<a href="/pp_informe_gastro_pdf/${row.gastro.id}/${p[0]}" target="_blank" class="btn btn-primary btn-sm" style="margin:1px" title="${p[1]}"><i class="fa fa-file-pdf-o"></i></a>`;
                            });
                            if (parseInt(row.tiene_informe) !== 1) return htmlGastro;
                          }
                          if (parseInt(row.tiene_informe) !== 1) return '';
                          if (parseInt(row.puede_ver) !== 1) {
                            return (htmlGastro || '') + `<button type="button" class="btn btn-primary btn-sm" disabled title="Informe de otra IPRESS"><i class="fa fa-file-pdf-o"></i></button>`;
                          }
                          return (htmlGastro || '') + String(row.ids_informes || '').split(',').filter(Boolean).map(id => `<a href="/pp_ver_informe/${row.id_solicitud}/${row.id_procedimiento}/${id}" target="_blank" class="btn btn-primary btn-sm" style="margin:1px" title="Ver informe"><i class="fa fa-file-pdf-o"></i></a>`).join('');
                        }
                      }
                    ]
                  });
                }
              });
            };

            $(document).on('click', '.btnVerAtencionPPHist', function() {
              $('#modalVerAtencionPPBody').html('<div class="text-center" style="padding:40px"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>');
              $('#modalVerAtencionPP').modal('show');
              $.ajax({
                url: '/modal_ver_atencion_procprogramado/' + $(this).data('id-solicitud') + '/' + $(this).data('id-procedimiento'),
                type: 'GET',
                success: function(result) { $('#modalVerAtencionPPBody').html(result); },
                error: function() { $('#modalVerAtencionPPBody').html('<div class="text-danger text-center" style="padding:40px">No se pudo cargar la atención.</div>'); }
              });
            });
            $(document).on('hidden.bs.modal', '#modalVerAtencionPP', function() {
              $('#modalVerAtencionPPBody').empty();
            });

            $('#addDiagnostico').on('click', function() {
              AddDiagnostico();
            });

            $('#addProcedimiento').on('click', function() {
              AddProcedimiento();
            });

            $('#addExamenLab2').on('click', function() {
              AddExamenLab();
            });

            $('#addRowPurperioNatiVivo').on('click', function() {
                AddRowPurperioVivo();
            });
            $('#addRowPurperioNatiMuerto').on('click', function() {
                AddRowPurperioMuerto();
            });

  // $('#addExamenRad2').on('click', function() {
  //   AddExamenRad();
  // });
  ////////////////////////////////////////////////////////////////////////////// NUEVA MODIFICACION EXAMEN DE IMÁGENES 15-05-26 //ACTUALIZADO 29-05-26
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

      var id_emergencia      = 0;
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
  ////////////////////////////////////////////////////////////////////////////// FIN MODIFICACION EXAMEN DE IMÁGENES 15-05-26

 $('#addProducto').on('click', function () {
    const botonesAddProducto = document.querySelectorAll('.btnAddProductoOfList');
    const ultimoBoton = botonesAddProducto.length > 0 ? botonesAddProducto[botonesAddProducto.length - 1] : null;
    var id_farmacia = $('#id_farmacia_receta').val();
    validproductoseleccionado(id_farmacia, ultimoBoton);
		AddProducto();
	});

  $('#addInterconsulta2').on('click',function(){
    AddInterconsulta2();
  });

  $('#addExamenInterPreQuirurgica').on('click',function(){
		AddExamenInterPreQuirurgica();
	});

  $('#addDerivarServicio').on('click', function() {
    var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
    $("#divDerivarServicio").hide();
    if (addDerivarServicio == "false") {
      $("#divDerivarServicio").show();
    }
  });

  $('#addreferencia').on('click', function() {
                var addreferencia = $('#addreferencia').attr("aria-pressed");
                console.log(addreferencia)
                $('#referencia_activo').val(addreferencia);
                $("#divreferencia").hide();
                if (addreferencia == "false") {
                    $('#referencia_activo').val(addreferencia);
                    $("#divreferencia").show();
                    $("#divcontrareferencia").hide();
                    $('#addcontrareferencia').prop('disabled', true);
                } else {
                    $('#addcontrareferencia').prop('disabled', false);
                }
            });

            $('#addcontrareferencia').on('click', function() {
    var addcontrareferencia = $('#addcontrareferencia').attr("aria-pressed");
    console.log(addcontrareferencia);
    $('#contrareferencia_activo').val(addcontrareferencia);
    $("#divcontrareferencia").hide();
    if (addcontrareferencia == "false") {
      $('#contrareferencia_activo').val(addcontrareferencia);
      $("#divcontrareferencia").show();
      $("#divreferencia").hide();
      $('#addreferencia').prop('disabled', true);
    }else{
      $('#addreferencia').prop('disabled', false);
    }
  });

  $('#addInterconsulta').on('click', function () {
    var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
    $("#fsInterconsulta").hide();
    if(addInterconsulta == "false"){
      $("#fsInterconsulta").show();
    }
  });

  $('#addRegistrarCita').on('click', function () {
      var addRegistrarCita = $('#addRegistrarCita').attr("aria-pressed");
      $('#registrar_cita').val(addRegistrarCita);
    });
  
    $('#addAlta').on('click', function() {
    var addAlta = $('#addAlta').attr("aria-pressed");
    $("#divAlta2").hide();
    if (addAlta == "false") {
      $("#divAlta2").show();
    }
  });

$('#addRecetaVale').on('click', function() {

    var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");

    $("#fsRecetaVale").hide();
    $("#divCopiarUltimaReceta").hide();

    if (addRecetaVale == "false") {

        $("#fsRecetaVale").show();

        var idEstablecimiento = parseInt($('#id_establecimiento').val());
        var idServicio = parseInt($('#id_servicio').val());

        if (idEstablecimiento === 75 && idServicio === 106) {
            $("#divCopiarUltimaReceta").show();
        }

    } else {

        $('#example tbody').empty();
        filasmedicamentos = 0;

    }
});

  $('#addExamenLab').on('click', function() {
    var addExamenLab = $('#addExamenLab').attr("aria-pressed");
    $("#fsExamenLab").hide();
    if (addExamenLab == "false") {
      $("#fsExamenLab").show();
    } else {
      $('#id_perfil_preoperatorio').val('0')
      $('#tblExamenLab tbody').empty();
    }
  });


  $('#addExamenRad').on('click', function() {
    var addExamenRad = $('#addExamenRad').attr("aria-pressed");
    $("#fsExamenRad").hide();
    if (addExamenRad == "false") {
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

  $('#addCondicion').on('click', function() {
    var addCondicion = $('#addCondicion').attr("aria-pressed");
    $("#divCondicion").hide();
    $("#condicion").val(0);
    if (addCondicion == "false") {
      $("#divCondicion").show();
      $("#condicion").val(1);
    }
  });
  $('#addOftalmologia').on('click', function () {
    var addOftalmologia = $('#addOftalmologia').attr("aria-pressed");
    $("#fsOftalmologia").hide();
    if(addOftalmologia == "false"){
      $("#fsOftalmologia").show();
    }
  });
  $('#addGastro').on('click', function () {
    var addGastro = $('#addGastro').attr("aria-pressed");
    $("#fsGastro").hide();
    if(addGastro == "false"){
      $("#fsGastro").show();
    }
  });
    $('#addInterconsulta').on('click', function () {
		var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
		$("#fsInterconsulta").hide();
		if(addInterconsulta == "false"){
			$("#fsInterconsulta").show();
		}
	});

	$('#addInterconsulta2').on('click',function(){
		AddInterconsulta2();
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

  $('#addReferencia').on('click', function () {
    var addReferencia = $('#addReferencia').attr("aria-pressed");
    $("#fsReferencia").hide();
    if(addReferencia == "false"){
      $("#fsReferencia").show();
    }
  });
  
  $("#paciente_discapacitado").hide();
  $("#paciente_discapacitado_seleccion").select2({ width: 'resolve'}); 
  $("input[name=discapacidad]").click(function () {      
      if($(this).val()=='SI'){
        $('#paciente_discapacitado').show(); 
      }else{
        $("#paciente_discapacitado").hide();
        $("#paciente_discapacitado_carnet").hide();
        const radio = document.getElementById("discapacidad_carnet_2");
        radio.checked = true;
          const radios = document.getElementsByName("discapacidad_carnet");
          for (let i = 0; i < radios.length; i++) {
            if (radios[i] !== radio) {
              radios[i].checked = false;
            }
          }
      }
  });

  $("#paciente_discapacitado_carnet").hide();
  $("#paciente_discapacitado_carnet_seleccion").select2({ width: 'resolve'}); 
  $("input[name=discapacidad_carnet]").click(function () {      
      if($(this).val()=='SI'){
        $('#paciente_discapacitado_carnet').show(); 
      }else{
        $("#paciente_discapacitado_carnet").hide();
      }
  });

  $("#producto_apoyo").hide();
  $("#producto_apoyo_seleccion").select2({ width: 'resolve'}); 
  $("input[name=presc_producto_apoyo]").click(function () {      
    if($(this).val()=='SI'){
      $('#producto_apoyo').show(); 
    }else{
      $('#producto_apoyo').hide(); 
      $('#tips_products').hide(); 
        const radio = document.getElementById("usa_product_apoyo_2");
        radio.checked = true;
        const radios = document.getElementsByName("usa_product_apoyo");
        for (let i = 0; i < radios.length; i++) {
          if (radios[i] !== radio) {
            radios[i].checked = false;
          }
        }
    }
  });

  $("#tips_products").hide();
  $("#tips_products_seleccion").select2({ width: 'resolve'}); 
  $("input[name=usa_product_apoyo]").click(function () {      
      if($(this).val()=='SI'){
        $('#tips_products').show(); 
      }else{
        $("#tips_products").hide();
      }
  });

  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
    
      // Obtiene la posicion de la fila
      var filaPosition = fila.index();
      
      // Imprime la posicion del elemento 'input' en la consola
      console.log("La posicion del input es: " + filaPosition);
      eliminarDiagnosticoXProducto(filaPosition);
      // Elimina la fila
      obj.parent().parent().remove();
      filasmedicamentos--;
    });
  
  function eliminarDiagnosticoXProducto(posicion) {
    // Se elimina el elemento en la posicion dada
    procedimientosSeleccionados.splice(posicion, 1);

    // Se actualiza la posicion de los elementos restantes
    procedimientosSeleccionados.forEach((item, index) => {
      item.posicion = index;
    });
    console.log(procedimientosSeleccionados);
  }


  $('#tblInterconsulta tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    obj.parent().parent().remove();
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

  $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });
	$('#tblInterconsulta tbody').on('click', 'button.deleteFila', function () {
		var obj = $(this);
		obj.parent().parent().remove();
	});

            $('#tblPuerpera tbody').on('click', 'button.deleteFila', function() {
                var obj = $(this);
                obj.parent().parent().remove();
            });
            $('#tblPuerperioNatiMuerto tbody').on('click', 'button.deleteFila', function() {
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

  $("#id_servicio_derivar").select2({
    width: 'resolve'
  });

    $("#id_servicio_interconsulta").select2({
        width: 'resolve'
    });
  $("#id_tipo_atencion").select2({
    width: 'resolve'
  });

  $("#id_modalidad").select2({
    width: 'resolve'
  });

  $("#id_servicio").select2({
    width: 'resolve'
  });

	$("#id_farmacia_receta").select2({
        width: 'resolve'
    });

  $("#id_receta").select2({
    width: 'resolve'
  });

  $("#upss_bene").select2({
    width: 'resolve'
  });

  $("#ipress_bene").select2({
    width: 'resolve'
  });

  $("#id_motivo_alta").select2({
    width: 'resolve'
  });

  $("#codigo_upss").select2({
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
  
  //AddDiagnostico();
  //AddProcedimiento();
  //AddExamenLab();
  //AddExamenRad();

  $("#registrar_cita_medico").hide();
});
function activarToggleGastro() {
  var addGastro = $('#addGastro').attr("aria-pressed");
  $("#fsGastro").hide();
  if(addGastro == "false"){
    $("#addGastro").show();
  }
}

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
        if (result.length > 0) {
          for (let key of result) {
            if (n == 0) {
                msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.nombre_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene anÃ¡lisis de laboratorio pendientes del examen seleccionado en los Ãºltimos 3 meses, CANTIDAD : " + key.total_registros + "</h1>";
                msg += "<table class='table table-list-search' style='font-size: 11px !important;' >"; 
                msg += "<thead>";
                msg += "<tr>";
                msg += "<th>Codigo</th>";
                msg += "<th>Descripcion Corta</th>";
                msg += "<th>Tipo de Atencion</th>";
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
            msg += "<th>Solicitud</th>";
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
        
        msg += "<td>" + (key.dias_transcurridos) + "</td>";
        msg += "<td>" + (parseInt(key.flag) == 0 ? "SIN FIRMAR" : "FIRMADO") + "</td></tr>";
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
      let res = result.ResultadosLabView;
      console.log(["result",res]);
      let numerosImpresos = new Set();
      let n = 0;
      let n2 = 0;
      let msg = "";
      if (res && Array.isArray(res) && res.length > 0) {
        if (n == 0) {
              // msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + nombreCompleto + " con DNI " + dni_beneficiario + " tiene resultados de la prueba de laboratorio seleccionado de los Ãºltimos 3 meses</h1>";
              msg += `<table class="table table-list-search" style="font-size: 11px !important;">`; 
              msg += `<thead class="thead-resultados">`;
              msg += "<tr>";
              msg += `<th class="encabezado"></th>`;
              msg += `<th class="encabezado">Codigo de orden</th>`;
              msg += `<th class="encabezado">F.Toma de Muestra</th>`;
              msg += `<th class="encabezado">Tipo de Atencion</th>`;
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
          // Verificar si el nÃºmero ya se ha impreso
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


function activarToggleSignosVitales() {
  var addSignosVitales = $('#addSignosVitales').attr("aria-pressed");
  $("#fsSignosVitales").hide();
  if(addSignosVitales == "false"){
    $("#fsSignosVitales").show();
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

updateSelectElements();   

}

function compruebaIMC(){ 
  
    peso = document.frmPrestacion.peso.value;
    talla = document.frmPrestacion.talla.value;

    if (talla != "") { 
        //PESO POR ALTURA
        var sexo = document.frmPrestacion.nomsexo.value; //MASCULINO , FEMENINO
        //edad
        var edad_anos = document.frmPrestacion.edadpaciente.value; //12/05/1992
        
        var pesoIdeal=50+((talla-150)/4)*3+(edad_anos-20)/4
        if(sexo=="M") {
              pesoIdeal=pesoIdeal*0.9;
        }
        document.frmPrestacion.peso_x_altura.value = pesoIdeal.toFixed(2);
    }else{
       document.frmPrestacion.peso_x_altura.value = "";
    }

    if (peso != "" && talla != "") { 
        var talla_en_metros =  talla*0.01;     
        //IMC
        var imcx=  ( peso / (talla_en_metros * talla_en_metros) ).toFixed(2);
        document.frmPrestacion.imc.value = imcx;

    } else {
        document.frmPrestacion.imc.value = '';
    }
  }

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

        function clearValuesFormGestante() {
            $('#ultimaMestruacion').val('')
            $('#parto_probable').val('')
            $('#semana_gesta').val('')
            $('#control_prenatal').val('')
            $('#duda').val('0')
        }

        function clearValuesFormPuerpera() {
            $('#fecha_parto').val('')
            $('#hora_parto').val('')
            $('#tipo_parto').val('')
            $('#complicacion_parto').val('')
            $('#descripcion_parto').val('')
        }
/************************/
function getTipoAtencion(id_tiporeceta){
		
		if(id_tiporeceta == undefined || id_tiporeceta == "undefined" || id_tiporeceta == "" || id_tiporeceta == "null" || id_tiporeceta == null)id_tiporeceta 	= $('#id_tiporeceta').val();
		else $('#id_tiporeceta').val(id_tiporeceta).trigger("change");
		
		var id_farmacia 	= $("#id_farmacia").val();
		var nivel     		= $('#id_nivel').val();
		$('#divTurno').show();
		$('#divMedico').attr("class","col-md-4").show();
		$('#divServicio').attr("class","col-md-6").show();
		$('#divCentroQuirurgico').hide();
		$('#divReceta').attr("class","col-md-3");
		$('#divAno').attr("class","col-md-2");
		$('#divGuia').hide();
		$('#divVia').hide();
		$('#divFinalVia').hide();
		$('#divInterconsulta').hide();
		$('#nro_historia').attr("readonly",true);
		
		if(id_tiporeceta==2){
			$('#divTurno').hide();
			$('#divMedico').attr("class","col-md-6");
		}
		/*if(id_tiporeceta==3 && nivel == 3){
			$('#divGuia').show();
			$('#divReceta').attr("class","col-md-2");
			$('#divAno').attr("class","col-md-1");
		}*/
		if(id_tiporeceta==4){
			$('#divCentroQuirurgico').show();
			$('#divTurno').hide();
			$('#divServicio').attr("class","col-md-4");
			getMedicoAnestesiologo();
		}
		if(id_tiporeceta==5 || id_farmacia==22){
			$('#divTurno').hide();
			$('#divMedico').attr("class","col-md-6");
			$('#nro_historia').attr("readonly",false);
			$('#nro_historia').attr("tabindex",4);
		}
		
		if(id_tiporeceta==3 && nivel == 2){
			//$('#divGuia').show();
			//$('#divReceta').attr("class","col-md-2");
			//$('#divAno').attr("class","col-md-1");
			
			//$('#divInterconsulta').show();
			$('#divTurno').hide();
			$('#divServicio').attr("class","col-md-4");
			$('#divMedico').attr("class","col-md-5");
		}
		
		if(id_tiporeceta==3 && nivel == 3){
			$('#divGuia').show();
			$('#divReceta').attr("class","col-md-2");
			$('#divAno').attr("class","col-md-1");
			
			$('#divInterconsulta').show();
			$('#divTurno').hide();
			//$('#divMedico').attr("class","col-md-6");
			$('#divServicio').attr("class","col-md-4");
			$('#divMedico').attr("class","col-md-5");
		}
		
		if(id_tiporeceta==6){
			$('#divVia').show();
			$('#divTurno').hide();
			$('#divServicio').attr("class","col-md-3");
			$('#divMedico').attr("class","col-md-3");
			$('#nro_historia').attr("readonly",false);
			$('#nro_historia').attr("tabindex",4);
		}
		
		/*
			var selectConsultorio = "";
			var item_consultorio = "";
			if(id_tiporeceta==2 && id_farmacia!=22){
				selectConsultorio += '<input type="text" readonly="" value="EMERGENCIA" name="consultorio" id="consultorio" class="form-control" style="text-align:left">';
				selectConsultorio += '<input type="hidden" name="id_consultorio" id="id_consultorio" value="76">';
				$('#divServicioCol').html(selectConsultorio);
				getProducto();
			}else if(id_tiporeceta==8){
				selectConsultorio += '<input type="text" readonly="" value="ONCOHEMA" name="consultorio" id="consultorio" class="form-control" style="text-align:left">';
				selectConsultorio += '<input type="hidden" name="id_consultorio" id="id_consultorio" value="104">';
				$('#divServicioCol').html(selectConsultorio);
				getProducto();
			}else{
				$('#id_consultorio_temp option').each(function(){
					item_consultorio += "<option value='"+$(this).val()+"'>"+$(this).html()+"</option>"	
				});
				
				var metodo = "";
				//if(id_tiporeceta == 1 || id_tiporeceta == 3 || id_tiporeceta == 5)
				metodo = "onchange='getProducto();'";
				
				selectConsultorio += '<select class="form-control" id="id_consultorio" name="id_consultorio" tabindex="6" '+metodo+' style="width:100%">'+item_consultorio+'</select>';
				$('#divServicioCol').html(selectConsultorio);
				$("#id_consultorio").select2({width: 'resolve'});
				
			}
			
			var selectMedico = "";
			
			<?php //if ($modo_registro == 1 && $tipo_registro == 1):?>
				selectMedico += '<input type="text" readonly="" value="<?php //echo (isset($turno->paterno))?$turno->paterno." ".$turno->materno." ".$turno->nombre:""?>" name="medico" id="medico" class="form-control" style="text-align:left">';
				selectMedico += '<input type="hidden" name="id_medico" value="<?php //echo (isset($turno->id_medico))?$turno->id_medico:""?>">';
				$('#divMedicoCol').html(selectMedico);
			<?php //else:?>
				var method = "";
				if(id_tiporeceta == 1)method = "valid_servicios_by_mes();";
				var item_medico = '<option value="">--Seleccionar--</option>';
				selectMedico += '<select class="form-control" id="id_medico" name="id_medico" tabindex="8" style="width:100%" onchange="'+method+'AddFilaMed();">'+item_medico+'</select>';
				$('#divMedicoCol').html(selectMedico);
				$("#id_medico").select2({width: 'resolve'});
			<?php //endif;?>
			
			var selectTurno = "";
			
			<?php //if ($modo_registro == 1 && $tipo_registro == 1):?>
				selectTurno += '<input type="text" readonly="" value="<?php //echo (isset($turno->nombre_turno))?$turno->nombre_turno:""?>" name="nombre_turno" id="nombre_turno" class="form-control" style="text-align:left">';
				selectTurno += '<input type="hidden" name="turno" value="<?php //echo (isset($turno->turno))?$turno->turno:""?>">';
				$('#divTurnoCol').html(selectTurno);
			<?php //else:?>
				var item_turno = '<option value="">--Seleccionar--</option>';
				item_turno += '<option value="M">MAÃ‘ANA</option>';
				item_turno += '<option value="T">TARDE</option>';
				item_turno += '<option value="N">NOCHE</option>';
				selectTurno += '<select class="form-control" id="turno" name="turno" tabindex="7" style="width:100%" onchange="getMedico()">'+item_turno+'</select>';
				$('#divTurnoCol').html(selectTurno);
				$("#turno").select2({width: 'resolve'});
			<?php //endif;?>
		*/	
		//}
		
		
	}

  function maxlength_doc_usu() {
    $('#dni_beneficiario').val('');
    if ($("#tipodoc_beneficiario_bus").val() == "1") {
      $("#dni_beneficiario").attr('maxlength', '8');
    } else {
      $("#dni_beneficiario").attr('maxlength', '12');
    }
    document.getElementById("dni_beneficiario").focus();
}

    // $('input[name="duda"]').change(function() {
    //   var seleccionado = $('input[name="duda"]:checked').val();

    //   if (seleccionado === "1") {
    //     $('#ultimaMestruacion').val('')
    //     $('#parto_probable').val('')
    //     $('#semana_gesta').val('')
    //     $('#control_prenatal').val('')
    //     $('#duda').val('')
    //   } else {
    //       bloquearUltimaMestruacion(false); // Desbloquear
    //   }
    // });
        // Funcion para seleccionar por defecto el radio button "No" cuando se active el toggle
        function seleccionarNoPorDefecto() {
            //no radiobutton
            var radioNo = $('input[name="duda"][value="0"]');

            // Seleccionar el radio button "No"
            radioNo.prop('checked', true);
        }

        // Llamar a la funcion para seleccionar "No" por defecto cuando se active el toggle
        // el toglefields es como un identificador para aperturar el togle
        function toggleFields() {
            // Llamar a la funcion para seleccionar "No" por defecto
            seleccionarNoPorDefecto();
            // Otras acciones acciones u logias para implementar el togle
        }

        // Llamar a la funcion toggle cuando se carga el documento hacer la funcion....
        $(document).ready(function() {
            toggleFields
        (); // Esto seleccionarÃ¡ "No" por defecto cuando se cargue la pÃ¡gina siempre y cuando el value este en 0 o 1
        });



        function calcularTrimestre() {

//obtencion de fechas    
var ultimaMestruacion = $("#ultimaMestruacion").datepicker("getDate");
var fechaHoy = new Date();

   //Ã¡lculo de la diferencia en semanas
var diffMilisegundos = fechaHoy.getTime() - ultimaMestruacion.getTime();
var diffSemanas = Math.floor(diffMilisegundos / (1000 * 60 * 60 * 24 * 7)); // Obtener la diferencia en semanas
// Determinar el trimestre
var trimestre;
var semanas;
if (diffSemanas >=45 ) {
               bootbox.alert("INGRESAR UNA FECHA CORRECTA MAXIMO DE 45 SEMANAS");

} else {
semanas = "SEMANA N ° " + diffSemanas;

}


// Mostrar el trimestre en el campo de entrada correspondiente
$("#semana_gesta").val(semanas);
}

        // Llamar a la funcion de cÃ¡lculo cuando se cambie la fecha de la Ãºltima menstruacion
        $("#ultimaMestruacion").change(calcularTrimestre);
/************************/
function valid_servicios_by_mes(){
        if($('#id_medico').val()=="") return false;
        var dni_beneficiario = $('#dni_beneficiario').val();
        var id_consultorio = $('#id_consultorio').val();
        var fecha_atencion = $('#fecha_atencion').val();
        var id_medico = $('#id_medico').val();
            $.ajax({
                url: '/obtenerCantAtencionesMesActualbyBeneficiario/' + dni_beneficiario+ '/' + id_consultorio+ '/' + fecha_atencion+ '/' + id_medico,
                success: function (result) {
                    result = result.split('|', 5);
                    maxnrousu = Number(result[0]);
                    maxnrores = Number(result[1]);
                    validaten = Number(result[2]);
                    totaten = Number(result[3]);
                    totmismaesp = Number(result[4]);
                    
                    var newOption = "";
                    newOption += "Existen <b><a href='#' onclick=valid_det_servicios_by_mes() style='text-decoration: none;'>" + totaten + "</a></b> atenciones en el mes de la fecha de expedicion seleccionada<br/>";
                    newOption += "Existen <b>" + totmismaesp + "</b> recetas con la misma especialidad en el mes de la fecha de expedicion seleccionada<br/>";
                    
                    $('#msj-atenciones').removeClass("alert alert-success").addClass("alert alert-danger");
                    $('#det-msj-atenciones').html(newOption);
                  
                    if (validaten >= maxnrousu){
                      if(id_consultorio!=104){
                            if (validaten == 3 && totmismaesp >= 1){
                                $("#addRow").prop("disabled", false);
                                $("#btnReg").prop("disabled", false);  
                            } else{
                                /*var flagresponsablefarmacia = "<?php //echo $flagresponsablefarmacia?>";
                                if(flagresponsablefarmacia != 1){
                                    $("#addRow").prop("disabled", true);
                                    $("#btnReg").prop("disabled", true);
                                } else {
                                    if (validaten < maxnrores){//maxnrores sacar del procedimiento almacenado
                                        $("#addRow").prop("disabled", false);
                                        $("#btnReg").prop("disabled", false);                                
                                    } else {  */
                                        $("#addRow").prop("disabled", true);
                                        $("#btnReg").prop("disabled", true);  
                                    //}
                               // }
                            }
                       }  
                      $('#msj-atenciones').show();
                    } else {
                        $('#msj-atenciones').removeClass("alert alert-danger").addClass("alert alert-success");
                        $('#det-msj-atenciones').html(newOption);
                        $("#addRow").prop("disabled", false);
                        $("#btnReg").prop("disabled", false);
                        $('#msj-atenciones').show();
                    }
                }
               
            });
        }
/************************/

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

async function guardarPrestacion(){
  // if (!isValidSiteds) {
  //   Swal.fire({
  //     icon: "warning",
  //     title: '<p style="font-size: 15px">ATENCIONES ADICIONALES MEDICAS</p>',
  //     html: `<p style="font-size: 13px">${messageErroSiteds}</p>`,
  //     confirmButtonText: "ENTENDIDO",
  //   });
  //   return;
  // }
	var msgLoader = "";
	msgLoader = "Procesando, espere un momento por favor";
	$('#btnPrestacion').prop("disabled",true);
	var heightBrowser = $(window).width()/2;
	$('.loader').css("opacity","0.8").css("height",heightBrowser).html("<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>"+msgLoader+"</div></div>");
    $('.loader').show();
	
    var msg = "";
      var cod_siteds= $('#codigo_siteds').val();  
      
      if ($('#frmPrestacion').find("#codigo_siteds").val() == "") {
        msg+="Debe ingresar un Codigo siteds<br>";
      }else{
        $("#codigo_siteds").val(completarConCeros(cod_siteds,12));  
      }

      const btnReferencia = document.getElementById("addreferencia")?.getAttribute('aria-pressed');
      if (btnReferencia === "true") {
        const tiempo_enfermedad = $("#tiempo_enfermedad").val();
        const anamnesis = $("#anamnesis").val();
        const examen_fisico = $("#examen_fisico").val();
        const tratamiento_auxiliar = $("#tratamiento_auxiliar").val();
        if (anamnesis == "") {
          msg += "Debe ingresar el relato cronologico (<b>Se activo la opcion de REFERENCIA</b>).<br>";
        }
        if (tiempo_enfermedad == "") {
          msg += "Debe ingresar el tiempo de enfermedad (<b>Se activo la opcion de REFERENCIA</b>).<br>";
        }
        if (examen_fisico == "") {
          msg += "Debe ingresar el examen fisico (<b>Se activo la opcion de REFERENCIA</b>).<br>";
        }
        if (tratamiento_auxiliar == "") {
          msg += "Debe ingresar el tratamiento auxiliar (<b>Se activo la opcion de REFERENCIA</b>).<br>";
        }
      }
              
      /*var codSiteds= $('#codigo_siteds').val();  
      $.ajax({
        url:'{{url('validar_dato_siteds_ambulatorio/')}}/'+codSiteds,
        type:'GET',
        dataType:'JSON', 
          success: function(data){   
            if(data!=null){ 
              if(data.cantidad_siteds!=0){ 
                bootbox.alert("EL codigo SITEDS YA EXISTE, verifique que sea correcto y vuelva a intentarlo");
              } 
            }
          }
      });
      */
	var id_tipo_atencion = $('#id_tipo_atencion').val();
	var fecha_atencion = $('#fecha_atencion').val();
	var id_servicio= $('#id_servicio').val();
	var id_sub_consultorio= $('#id_sub_consultorio').val();
	var dni_beneficiario= $('#dni_beneficiario').val();
	var beneficiario= $('#beneficiario').val();
	var codigo_upss= $('#codigo_upss').val();
	var id_diagnostico = $('input[name="id_diagnostico[]"]').map(function(){return $(this).val();}).get();
	var id_procedimiento = $('input[name="id_procedimiento[]"]').map(function(){return $(this).val();}).get();
	var procedimiento_cantidad = $('input[name="procedimiento_cantidad[]"]').map(function(){return $(this).val();}).get();
	var id_tipo_diagnostico = $('input[name="id_tipo_diagnostico[]"]').map(function(){return $(this).val();}).get();
  var fecha_expedicion = $('#fecha_expedicion').val();
  var addDerivarServicio = $('#addDerivarServicio').attr("aria-pressed");
  var addAlta = $('#addAlta').attr("aria-pressed");
	var id_servicio_derivar = $('#id_servicio_derivar').val();
	var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
	var addExamenLab = $('#addExamenLab').attr("aria-pressed");
  var addOftalmologia = $('#addOftalmologia').attr("aria-pressed");
  var addGastro = $('#addGastro').attr("aria-pressed");
	var addExamenRad = $('#addExamenRad').attr("aria-pressed");
	var addInterconsulta = $('#addInterconsulta').attr("aria-pressed");
  var addReferencia = $('#addReferencia').attr("aria-pressed");
	var addCondicion = $('#addCondicion').attr("aria-pressed");
	var cantidadReceta = $('#cantidadReceta').val();
  let addInterQuirurgica = $('#addInterQuirurgica').attr("aria-pressed");
  let addInterConsultaQuirurgica = $('#addInterPreQuirurgica').attr("aria-pressed");
	$('#procedimientosSeleccionados1').val(JSON.stringify(procedimientosSeleccionados));
  
  let tiempo_enfermedad = $('#tiempo_enfermedad').val();
    let motivo_visita = $('#motivo_visita').val();
    let anamnesis = $('#anamnesis').val();
    // if(tiempo_enfermedad.trim()=="") msg+="Debe ingresar el <b>Tiempo de Enfermedad </b><br>"
    // if(motivo_visita.trim()=="") msg+="Debe ingresar el <b>Motivo de Visita</b><br>"
    // if(anamnesis.trim()=="") msg+="Debe ingresar un <b>Relato Cronológico</b><br>"
  
	let vardatos = $('#procedimientosSeleccionados1').val();

  var esta_gestando = $('#esta_gestando').attr("aria-pressed");
  $("#ultimaMestruacion").removeAttr('disabled');
  $("#parto_probable").removeAttr('disabled');
  const id_gestacion = $("#id_gestacion").val();

  if (esta_gestando === "true") {
    if ($("#ultimaMestruacion").val() === "") {
      if (id_gestacion !== "" && id_gestacion !== null) {
        $("#ultimaMestruacion").attr('disabled', true); 
      }
      msg += "Debe ingresar la fecha de última menstruación.<br>";
    }

    if ($("#parto_probable").val() === "") {
      if (id_gestacion !== "" && id_gestacion !== null) {
        $("#parto_probable").attr('disabled', true); 
      }
      msg += "Debe ingresar la fecha de última menstruación.<br>";
    }

    if ($("#semana_gesta").val() === ""){
      msg += "Debe ingresar o calcular sus semanas de gestacion.<br>";
    }
    if ($('#control_prenatal').val() === "") {
      msg += "Debe ingresar un control prenatal<br>";
    }
  }

  $("#esta_gestando").remove();
  $("#frmPrestacion").append('<input type="hidden" name="esta_gestando" id="esta_gestando">');
  $("#esta_gestando").val(esta_gestando);
            
	
  if(id_tipo_atencion=="")msg+="Debe ingresar un Tipo de Atencion<br>";
	if(fecha_atencion=="")msg+="Debe ingresar una Fecha de Atencion<br>";
	if(id_servicio=="0")msg+="Debe ingresar un Servicio<br>";
	if(id_tipo_atencion==1){
    if(id_modalidad==1){
      if(id_sub_consultorio=="0")msg+="Debe ingresar un Consultorio<br>";
    }
  }
	if(codigo_upss=='0')msg+="Debe seleccionar un Codigo de UPSS<br>";
	if(dni_beneficiario=="" && beneficiario=="")msg+="Debe buscar un Asegurado<br>";
	//if(id_diagnostico=="")msg+="Debe ingresar minimo un diagnostico<br>";
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
  if(addDerivarServicio == "true" && id_servicio_derivar=="0")msg+="Debe ingresar un Servicio para Derivar<br>";
  if(addAlta == "true" && fecha_alta=="0")msg+="Debe ingresar la fecha de Alta<br>";
	if(addCondicion == "true" && cantidadReceta=="0")msg+="Debe ingresar la cantidad de dias de la Receta Vale<br>";
	
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
          if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == "undefined")msg+="Falta seleccionar un producto (dispositivo mÃ©dico)<br>"; 
          if(tiempo_periodo == "" || tiempo_periodo ==0)msg+="Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0<br>";
          if(via_administracion == 0 || via_administracion == "undefined" )msg+="Uno de los productos no cuenta con la via de administracion<br>";
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

  if (addInterQuirurgica == "true") {
    let checkbox1 = document.getElementById('rq_cardiologia');
    let checkbox2 = document.getElementById('rq_neumologia');
    let checkbox3 = document.getElementById('rq_anestesiologia');
    //if (!checkbox1.checked || !checkbox2.checked || !checkbox3.checked) {
    //SOLO SERA OBLIGATORIO ANESTESIOLOGIA
    // if (!checkbox3.checked) {
    //   msg += "El servicio de anestesiología de Riesgo Quirurgico deben estar marcado<br>";
    // }

    // let addInterPreQuirurgica = $('#addInterPreQuirurgica').attr("aria-pressed");
    // if (addInterPreQuirurgica == "true") {
    //   var ind = $('#tblExamenInterPreQuirurgica tbody tr').length;
    //   if (ind > 0){
    //     let fila_interconsulta_preqx = $('#tblExamenInterPreQuirurgica tbody').children('tr');            
    //     for (i = 0; i < ind; i++) {
    //       let row = fila_interconsulta_preqx[i];
    //       let servicio_interconsulta_preqx = $(row).find('.cmbMedicamento_preqx').val();
    //       let motivo_interconsulta_preqx = $(row).find('.motivo_interpreqx').val();
    //       if (servicio_interconsulta_preqx == "") msg += "Falta seleccionar el Servicio de Interconsulta Pre-quirúrgica<br>";
    //       if (motivo_interconsulta_preqx.trim() == "") msg += "Falta ingresar el Motivo de la Interconsulta Pre-quirúrgica<br>";
    //     }
    //   } else {
    //     msg += "Debe ingresar minimo una Interconsulta Pre-quirúrgica<br>";
    //   }
    // }

      
    const rowsTableProceduresIq= $('#tableProcedures_iq tbody tr');
    if (rowsTableProceduresIq.length > 0) {
      $('#tableProcedures_iq tbody tr').each(function () {
        let procedimientoNombreIq = $(this).find('input[name="procedimiento_nombre_iq[]"]');
        let procedimientoNombreIqValue = $.trim(procedimientoNombreIq.val());

        let idProcedimientoIq = $(this).find('input[name="id_procedimiento_iq[]"]');
        let idProcedimientoIqValue = $.trim(idProcedimientoIq.val());

        // 🚨 Si alguno está vacío o null → inválido
        if (procedimientoNombreIqValue === "" || 
            procedimientoNombreIqValue === null || 
            idProcedimientoIqValue === "" || 
            idProcedimientoIqValue === null) {
          msg+="Recuerde rellenar todo los campos de los procedimientos en INTERVENCIÓN QUIRÚRGICA<br>";    
        }
      });
    }
	}
  
  if (addInterConsultaQuirurgica == "true") {
    var ind = $('#tblExamenInterPreQuirurgica tbody tr').length;
    if (ind > 0){
      let fila_interconsulta_preqx = $('#tblExamenInterPreQuirurgica tbody').children('tr');            
      for (i = 0; i < ind; i++) {
        let row = fila_interconsulta_preqx[i];
        let servicio_interconsulta_preqx = $(row).find('.cmbMedicamento_preqx').val();
        let motivo_interconsulta_preqx = $(row).find('.motivo_interpreqx').val().trim();
        if (!servicio_interconsulta_preqx) {
            msg += "Falta seleccionar el Servicio de Interconsulta Pre-quirúrgica<br>";
        }
        if (motivo_interconsulta_preqx === "") {
            msg += "Falta ingresar el Motivo de la Interconsulta Pre-quirúrgica<br>";
        }
      }
    } else {
      msg += "Debe ingresar minimo una Interconsulta Pre-quirúrgica<br>";
    }
  }

	if(addExamenLab == "true"){
		// var id_examen_lab = $('input[name="id_examen_lab[]"]').map(function(){return $(this).val();}).get();
		// var proce_lab = $('input[name="examen_lab_cantidad[]"]').map(function(){return $(this).val();}).get();
		// if(id_examen_lab == "")msg+="Debe registrar un examen de laboratorio<br>";
		// if(proce_lab == "")msg+="Debe registrar una cantidad en laboratorio<br>";
    // if(proce_lab == 0)msg+="Debe registrar una cantidad mayor a 0 para el examen de laboratorio<br>";
    // let totalRowExamen = $('#tblExamenLab tbody tr').length;

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

    /*if (totalRowExamen > 1) {
      let fin_flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra' + (totalRowExamen - 1)).val();
      //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FINAL =================
      if (fin_flag_descripcion_tipo_muestra == 1) {
        let fin_descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + (totalRowExamen - 1)).val();
        if (fin_descripcion_tipo_muestra == "") {
          msg+='Debe indicar el tipo de muestra para microbilogía en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button><br>';
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

      //=== VALIDA SOLICITUD PARA TEST TOLERANCIA GLUCOSA INICIO ===============================
      let fin_flag_solicitud_test_tolerancia_glucosa_oral = $('#flag_solicitud_test_tolerancia_glucosa_oral' + (totalRowExamen - 1)).val();
      if (fin_flag_solicitud_test_tolerancia_glucosa_oral == 1) {
        let fin_flag_editado_solicitud_test_tolerancia_glucosa_oral = $('#flagEditadoSolicitudTestToleranciaGlucosaOral' + (totalRowExamen - 1)).val();
        if (fin_flag_editado_solicitud_test_tolerancia_glucosa_oral == '0') {
          msg+='Debe registrar la solicitud para test tolerancia glucosa en el último exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }

    } else {
      let inicio_flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra0').val();
      //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
      if (inicio_flag_descripcion_tipo_muestra == 1) {
        let inicio_descripcion_tipo_muestra = $('#descripcion_tipo_muestra0').val();
        if (inicio_descripcion_tipo_muestra == "") {
          msg+='Debe indicar el tipo de muestra para microbilogía en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button><br>';
        }
      }

      //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================
      let inicio_flag_solicitud_citoquimicos_liquidos_biologicos = $('#flag_solicitud_citoquimicos_liquidos_biologicos0').val();
      if (inicio_flag_solicitud_citoquimicos_liquidos_biologicos == 1) {
        let inicio_des_num_frasco_muestra_opcion = $('#des_num_frasco_muestra_opcion0').val();
        if (inicio_des_num_frasco_muestra_opcion == "") {
          msg+='Debe registrar la solicitud para estudio citoquímico líquido en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }

      //=== VALIDA SOLICITUD PARA TEST TOLERANCIA GLUCOSA INICIO ===============================
      let inicio_flag_solicitud_test_tolerancia_glucosa_oral = $('#flag_solicitud_test_tolerancia_glucosa_oral0').val();
      if (inicio_flag_solicitud_test_tolerancia_glucosa_oral == 1) {
        let inicio_flag_editado_solicitud_test_tolerancia_glucosa_oral = $('#flagEditadoSolicitudTestToleranciaGlucosaOral0').val();
        if (inicio_flag_editado_solicitud_test_tolerancia_glucosa_oral == '0') {
          msg+='Debe registrar la solicitud para test tolerancia glucosa en el primer exámen. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button><br>';
        }
      }
    }*/
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FIN =================
	}else{
    /*var inputElement = document.querySelector('input[name="id_examen_lab[]"]');
    if(inputElement){
      inputElement.value = '';
    }*/
    $('#tblExamenLab tbody').empty();
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
	/*if(addInterconsulta == "true"){
		var id_servicio_interconsulta = $('input[name="id_servicio_interconsulta[]"]').map(function(){return $(this).val();}).get();
    var motivo_interconsulta = $('input[name="motivo_interconsulta[]"]').map(function(){return $(this).val();}).get();
    //var resumen_interconsulta = $('input[name="resumen_interconsulta[]"]').map(function(){return $(this).val();}).get();
	  
	  if(id_servicio_interconsulta == "")msg+="Debe elegir un servicio para la interconsulta<br>";
		if(motivo_interconsulta == "")msg+="Debe registrar una motivo para la interconsulta<br>";
		//if(resumen_interconsulta == "")msg+="Debe registrar una resumen para la interconsulta<br>";
	}
  */
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
    if(id_acompanante == "")msg+="Debe especificar si quiere acompaÃ±ante<br>";
    if(id_situacion == "")msg+="Debe elegir una situacion<br>";
    if(observacion_referencia == "")msg+="Debe escribir un relato o caracteristicas de la referencia<br>";
     
  }

	if(procedimiento_cantidad>9)msg+="Esta seguro de ingresar la cantidad de procedimiento<br>";

if(addOftalmologia == "true"){
  //VALIDACION ======== SECCION DE OFTALMOLOGIA

	var od_pio= $('#od_pio').val();
    if(od_pio=='' || od_pio==null || od_pio.length ==0){
      msg+="Recuerde ingresar el campo PIO / OD<br>"; 
    }

    var od_pio= $('#oi_pio').val(); 
    if(oi_pio=='' || oi_pio==null || oi_pio.length ==0){
      msg+="Recuerde ingresar el campo OI PIO / OI.<br>";    
    }

    var od_lejos_sph= $('#od_lejos_sph').val();
    if(od_lejos_sph=='' || od_lejos_sph==null || od_lejos_sph.length ==0){ 
      msg+="Recuerde ingresar el campo OD LEJOS / SPH.<br>";    
    }

    var od_lejos_cil= $('#od_lejos_cil').val();
    if(od_lejos_cil=='' || od_lejos_cil==null || od_lejos_cil.length ==0){  
      msg+="Recuerde ingresar el campo OD LEJOS / CIL.<br>";    
    }

    var od_lejos_eje= $('#od_lejos_eje').val(); 
    if(od_lejos_eje=='' || od_lejos_eje==null || od_lejos_eje.length ==0){  
      msg+="Recuerde ingresar el campo OI LEJOS / EJE.<br>";   
    }

    var od_lejos_dip= $('#od_lejos_dip').val(); 
    if(od_lejos_dip=='' || od_lejos_dip==null || od_lejos_dip.length ==0){  
      msg+="Recuerde ingresar el campo OD LEJOS / DIP.<br>";  
    }

    var od_lejos_av= $('#od_lejos_av').val();
    if(od_lejos_av=='' || od_lejos_av==null || od_lejos_av.length ==0){
      msg+="Recuerde ingresar el campo OD LEJOS / AV.<br>";   
    }

    var oi_lejos_sph= $('#oi_lejos_sph').val();
    if(oi_lejos_sph=='' || oi_lejos_sph==null || oi_lejos_sph.length ==0){
      msg+="Recuerde ingresar el campo OI LEJOS / SPH.<br>";   
    }

    var oi_lejos_cil= $('#oi_lejos_cil').val();
    if(oi_lejos_cil=='' || oi_lejos_cil==null || oi_lejos_cil.length ==0){
      msg+="Recuerde ingresar el campo OI LEJOS / CIL.<br>";  
    }

    var oi_lejos_eje= $('#oi_lejos_eje').val();
    if(oi_lejos_eje=='' || oi_lejos_eje==null || oi_lejos_eje.length ==0){
      msg+="Recuerde ingresar el campo OI LEJOS / EJE.<br>";   
    }

    var oi_lejos_dip= $('#oi_lejos_dip').val();
    if(oi_lejos_dip=='' || oi_lejos_dip==null || oi_lejos_dip.length ==0){
      msg+="Recuerde ingresar el campo OI LEJOS / DIP.<br>";   
    }

    var oi_lejos_av= $('#oi_lejos_av').val();
    if(oi_lejos_av=='' || oi_lejos_av==null || oi_lejos_av.length ==0){
      msg+="Recuerde ingresar el campo OI LEJOS / AV.";
    }

    var od_cerca_sph= $('#od_cerca_sph').val();
    if(od_cerca_sph=='' || od_cerca_sph==null || od_cerca_sph.length ==0){
      msg+="Recuerde ingresar el campo OD CERCA / SPH.<br>";   
    }

    var od_cerca_cil= $('#od_cerca_cil').val();
    if(od_cerca_cil=='' || od_cerca_cil==null || od_cerca_cil.length ==0){
      msg+="Recuerde ingresar el campo OD CERCA / CIL.<br>";    
    }

    var od_cerca_eje= $('#od_cerca_eje').val();
    if(od_cerca_eje=='' || od_cerca_eje==null || od_cerca_eje.length ==0){
      msg+= "Recuerde ingresar el campo OD CERCA / EJE.<br>";  
    }

    var od_cerca_dip= $('#od_cerca_dip').val();
    if(od_cerca_dip=='' || od_cerca_dip==null || od_cerca_dip.length ==0){
      msg+="Recuerde ingresar el campo OD CERCA / DIP.<br>";   
    }

    var oi_cerca_sph= $('#oi_cerca_sph').val();
    if(oi_cerca_sph=='' || oi_cerca_sph==null || oi_cerca_sph.length ==0){
      msg+="Recuerde ingresar el campo OI CERCA / SPH.<br>";   
    }

    var oi_cerca_cil= $('#oi_cerca_cil').val();
    if(oi_cerca_cil=='' || oi_cerca_cil==null || oi_cerca_cil.length ==0){
      msg+="Recuerde ingresar el campo OI CERCA / CIL.<br>";   
    }

    var oi_cerca_eje= $('#oi_cerca_eje').val();
    if(oi_cerca_eje=='' || oi_cerca_eje==null || oi_cerca_eje.length ==0){
      msg+="Recuerde ingresar el campo OI CERCA / EJE.<br>";   
    }

    var oi_cerca_dip= $('#oi_cerca_dip').val();
    if(oi_cerca_dip=='' || oi_cerca_dip==null || oi_cerca_dip.length ==0){
      msg+="Recuerde ingresar el campo OI CERCA / DIP.<br>";  
    }

    var refraccion_add= $('#refraccion_add').val();
    if(refraccion_add=='' || refraccion_add==null || refraccion_add.length ==0){
      msg+="Recuerde ingresar el campo ADD.<br>";    
    }

//VALIDACION ======== SECCION DE OFTALMOLOGIA

    var od_motilidad_1= $('#od_motilidad_1').val();
    if(od_motilidad_1=='' || od_motilidad_1==null || od_motilidad_1.length ==0){
      msg+="Recuerde ingresar el campo OD MOTILIDAD OCULAR 1.<br>";    
      }

    var od_motilidad_2= $('#od_motilidad_2').val();
    if(od_motilidad_2=='' || od_motilidad_2==null || od_motilidad_2.length ==0){
      msg+="Recuerde ingresar el campo OD MOTILIDAD OCULAR 2.<br>";  
      }

    var od_motilidad_3= $('#od_motilidad_3').val();
    if(od_motilidad_3=='' || od_motilidad_3==null || od_motilidad_3.length ==0){
      msg+="Recuerde ingresar el campo OD MOTILIDAD OCULAR 3.<br>";   
      }

    var od_motilidad_4= $('#od_motilidad_4').val();
    if(od_motilidad_4=='' || od_motilidad_4==null || od_motilidad_4.length ==0){
      msg+="Recuerde ingresar el campo OD MOTILIDAD OCULAR 4.<br>";    
      }


      var od_motilidad_5= $('#od_motilidad_5').val();
    if(od_motilidad_5=='' || od_motilidad_5==null || od_motilidad_5.length ==0){
      msg+="Recuerde ingresar el campo OD MOTILIDAD OCULAR 5.<br>";    
      }

      var od_motilidad_6= $('#od_motilidad_6').val();
    if(od_motilidad_6=='' || od_motilidad_6==null || od_motilidad_6.length ==0){
      msg+="Recuerde ingresar el campo OD MOTILIDAD OCULAR 6.<br>";    
      }

//VALIDACION ======== SECCION DE OFTALMOLOGIA

      var oi_motilidad_1= $('#oi_motilidad_1').val();
    if(oi_motilidad_1=='' || oi_motilidad_1==null || oi_motilidad_1.length ==0){
      msg+="Recuerde ingresar el campo OI MOTILIDAD OCULAR 1.<br>";    
      }
 
      var oi_motilidad_2= $('#oi_motilidad_2').val();
    if(oi_motilidad_2=='' || oi_motilidad_2==null || oi_motilidad_2.length ==0){
      msg+="Recuerde ingresar el campo OI MOTILIDAD OCULAR 2.<br>";   
      }
 
      var oi_motilidad_3= $('#oi_motilidad_3').val();
    if(oi_motilidad_3=='' || oi_motilidad_3==null || oi_motilidad_3.length ==0){
      msg+="Recuerde ingresar el campo OI MOTILIDAD OCULAR 3.<br>";    
      }
 
      var oi_motilidad_4= $('#oi_motilidad_4').val();
    if(oi_motilidad_4=='' || oi_motilidad_4==null || oi_motilidad_4.length ==0){
      msg+="Recuerde ingresar el campo OI MOTILIDAD OCULAR 4.<br>";    
      }
 
      var oi_motilidad_5= $('#oi_motilidad_5').val();
    if(oi_motilidad_5=='' || oi_motilidad_5==null || oi_motilidad_5.length ==0){
      msg+="Recuerde ingresar el campo OI MOTILIDAD OCULAR 5.<br>";  
      }
 
      var oi_motilidad_6= $('#oi_motilidad_6').val();
    if(oi_motilidad_6=='' || oi_motilidad_6==null || oi_motilidad_6.length ==0){
      msg+="Recuerde ingresar el campo OI MOTILIDAD OCULAR 6.<br>";  
      }

//VALIDACION ======== SECCION DE OFTALMOLOGIA
 
      var motilidad_test= $('#motilidad_test').val();
    if(motilidad_test=='' || motilidad_test==null || motilidad_test.length ==0){
      msg+="Recuerde ingresar el campo MOTILIDAD TEST.<br>";   
      }

//VALIDACION ======== SECCION DE OFTALMOLO

      var od_parpado= $('#od_parpado').val();
    if(od_parpado=='' || od_parpado==null || od_parpado.length ==0){
      msg+="Recuerde ingresar el campo OD PARPADO.<br>";    
      } 

  var od_conjuntiva= $('#od_conjuntiva').val();
    if(od_conjuntiva=='' || od_conjuntiva==null || od_conjuntiva.length ==0){
      msg+="Recuerde ingresar el campo OD CONJUNTIVO.<br>";    
      } 

  var od_cornea= $('#od_cornea').val();
    if(od_cornea=='' || od_cornea==null || od_cornea.length ==0){
      msg+= "Recuerde ingresar el campo OD CORNEA.<br>";  
      } 

  var od_ca= $('#od_ca').val();
    if(od_ca=='' || od_ca==null || od_ca.length ==0){
      msg+="Recuerde ingresar el campo OD CA.<br>";    
      } 

  var od_pupila= $('#od_pupila').val();
    if(od_pupila=='' || od_pupila==null || od_pupila.length ==0){
      msg+="Recuerde ingresar el campo OD PUPILA.<br>";    
      } 

  var od_cristalino= $('#od_cristalino').val();
    if(od_cristalino=='' || od_cristalino==null || od_cristalino.length ==0){
      msg+="Recuerde ingresar el campo OD CRISTALINO.<br>";   
      } 

  //VALIDACION ======== SECCION DE OFTALMOLOGIA
  var oi_parpado= $('#oi_parpado').val();
    if(oi_parpado=='' || oi_parpado==null || oi_parpado.length ==0){
      msg+="Recuerde ingresar el campo OI PARPADO.<br>";    
      }

  var oi_conjuntiva= $('#oi_conjuntiva').val();
    if(oi_conjuntiva=='' || oi_conjuntiva==null || oi_conjuntiva.length ==0){
      msg+="Recuerde ingresar el campo OI CONJUNTIVO.<br>";    
      } 

  var oi_cornea= $('#oi_cornea').val();
    if(oi_cornea=='' || oi_cornea==null || oi_cornea.length ==0){
      msg+="Recuerde ingresar el campo OI CORREA.<br>";   
      }
 
  var oi_ca= $('#oi_ca').val();
    if(oi_ca=='' || oi_ca==null || oi_ca.length ==0){
      msg+="Recuerde ingresar el campo OI CA.<br>";    
      }
      
  var oi_pupila= $('#oi_pupila').val();
    if(oi_pupila=='' || oi_pupila==null || oi_pupila.length ==0){
      msg+="Recuerde ingresar el campo OI PUPILA.<br>";   
      }

  var oi_cristalino= $('#oi_cristalino').val();
    if(oi_cristalino=='' || oi_cristalino==null || oi_cristalino.length ==0){
      msg+="Recuerde ingresar el campo OI CRISTALINO.<br>";   
      }
      
      //VALIDACION ======== SECCION DE OFTALMOLOGIA

  var od_cristalino_fondo= $('#od_cristalino_fondo').val();
    if(od_cristalino_fondo=='' || od_cristalino_fondo==null || od_cristalino_fondo.length ==0){
      msg+="Recuerde ingresar el campo OI CRISTALINO FONDO.<br>";   
      } 

  var od_disco_optico= $('#od_disco_optico').val();
    if(od_disco_optico=='' || od_disco_optico==null || od_disco_optico.length ==0){
      msg+="Recuerde ingresar el campo OI DISCO OPTICO.<br>";    
      }
      

  var od_disco_macula= $('#od_disco_macula').val();
    if(od_disco_macula=='' || od_disco_macula==null || od_disco_macula.length ==0){
      msg+="Recuerde ingresar el campo OI DISCO MACULA.<br>";    
      }
      

  var od_retina= $('#od_retina').val();
    if(od_retina=='' || od_retina==null || od_retina.length ==0){
      msg+="Recuerde ingresar el campo OI RETINA.<br>";    
      }
      

  var od_vasos_retinianos= $('#od_vasos_retinianos').val();
    if(od_vasos_retinianos=='' || od_vasos_retinianos==null || od_vasos_retinianos.length ==0){
      msg+="Recuerde ingresar el campo OD VASOS RETINIANOS.<br>";   
      } 
  //VALIDACION ======== SECCION DE OFTALMOLOGIA

  var oi_cristalino_fondo= $('#oi_cristalino_fondo').val();
    if(oi_cristalino_fondo=='' || oi_cristalino_fondo==null || oi_cristalino_fondo.length ==0){
      msg+="Recuerde ingresar el campo OI CRISTALINO FONDO.<br>";    
      }
      

  var oi_disco_optico= $('#oi_disco_optico').val();
    if(oi_disco_optico=='' || oi_disco_optico==null || oi_disco_optico.length ==0){
      msg+="Recuerde ingresar el campo OI DISCO OPTICO.<br>";   
      }
      

  var oi_disco_macula= $('#oi_disco_macula').val();
    if(oi_disco_macula=='' || oi_disco_macula==null || oi_disco_macula.length ==0){
      msg+="Recuerde ingresar el campo OI DISCO OPTICO.<br>";    
      }


  var oi_retina= $('#oi_retina').val();
    if(oi_retina=='' || oi_retina==null || oi_retina.length ==0){
      msg+="Recuerde ingresar el campo OI RETINA.<br>";   
      } 

  var oi_vasos_retinianos= $('#oi_vasos_retinianos').val();
    if(oi_vasos_retinianos=='' || oi_vasos_retinianos==null || oi_vasos_retinianos.length ==0){
      msg+="Recuerde ingresar el campo OI VASOS RETINIANOS.<br>";   
      }
  //VALIDACION ======== SECCION DE OFTALMOLOGIA

  var imag_biometria= $('#imag_biometria').val();
    if(imag_biometria=='' || imag_biometria==null || imag_biometria.length ==0){
      msg+="Recuerde ingresar el campo BIOMETRIA.<br>";    
      } 

  var imag_retinografia= $('#imag_retinografia').val();
    if(imag_retinografia=='' || imag_retinografia==null || imag_retinografia.length ==0){
      msg+="Recuerde ingresar el campo RETINOGRAFIA.<br>";    
      } 

  var imag_campimetria= $('#imag_campimetria').val();
    if(imag_campimetria=='' || imag_campimetria==null || imag_campimetria.length ==0){
      msg+="Recuerde ingresar el campo CAMPIMETRIA.<br>";   
      } 

  var imag_ecografia_ocular= $('#imag_ecografia_ocular').val();
    if(imag_ecografia_ocular=='' || imag_ecografia_ocular==null || imag_ecografia_ocular.length ==0){
      msg+="Recuerde ingresar el campo ECOGRAFIA OCULAR.<br>";    
    }
  //VALIDACION ======== SECCION DE OFTALMOLOGIA
}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
if(addGastro == "true"){

}
	  
  var diagnosticosSeleccionados = $('#diagnosticosSeleccionados').val();

  /*var seleccionados = $("input[name='cod_lab[]']").map(function () {
            return $(this).val();
        }).get();

        msg += verificarFaltantes(seleccionados);*/

  const resultValidateFormProgramacionIQ = validateFormProgramacionQuirurgicaPq();
  console.log("[resultValidateFormProgramacionIQ] => ", resultValidateFormProgramacionIQ);
  if (resultValidateFormProgramacionIQ && resultValidateFormProgramacionIQ.length > 0) {
    msg += resultValidateFormProgramacionIQ.join('');
  }

   if (msg != "") {
      bootbox.alert(msg);
      $('#btnPrestacion').prop("disabled", false);
      $('.loader').hide();
      return false;
    } else {
      const programacionQuirurgica = $('#valueToogleProgramacionQuirurgica_pq').val();

      if (programacionQuirurgica == "1") {
          alertaFechaProgramacion().then(function(continuar) {
              if (continuar) {
                  // Usuario confirmó, enviamos formulario
                  $("#frmPrestacion").submit();
              } else {
                  // Usuario canceló, detenemos flujo
                  $('#btnPrestacion').prop("disabled", false);
                  $('.loader').hide();
                  return false;
              }
          });
      } else {
          // No es programación quirúrgica, enviamos formulario directamente
          $("#frmPrestacion").submit();
      }
    }
}


function fn_save_prestacion() {

  //var fecha_atencion_original = $('#fecha_atencion').val();
  $.ajax({
    url: "../registrar_prestacion",
    type: "POST",
			//data : $("#frmPrestacion").serialize()+"&id_medico="+id_medico+"&fecha_cita="+fecha_cita,
			data : $("#frmPrestacion").serialize(),
            success: function (result) {  
					//modalTurnos();
					//modalHistorial();
            }
    });
}

function modalProximaCitaServicio(tipodoc_beneficiario,dni_beneficiario,id_servicio,id_establecimiento,id_medico){
    $("#showProximasCitas .modal-dialog").css("width","45%");
    
    $.ajax({
        url: "modal_proxima_cita_servicio2/"+tipodoc_beneficiario+"/"+dni_beneficiario+"/"+id_servicio+"/"+id_establecimiento+"/"+id_medico,
        type: "GET",
        success: function (result) {
          if(result!=""){
            $('#diveditpreg').html(result);
            $('#showProximasCitas').modal('show');
          }
        }
    });
    
  }

  function servicio_imag_lab(){
      var id_servicio = $("#servicio_referencia").val();
    /*
      if(id_servicio == 113){
          $("#divreferencialab").show();
          $("#divreferenciaimag").hide();
      }else if(id_servicio == 114){
          $("#divreferencialab").hide();
          $("#divreferenciaimag").show();
      }else{
          $("#divreferencialab").hide();
         $("#divreferenciaimag").hide();
      }*/
  }

  function validarBotonUltimaReceta() {

      var idEstablecimiento = parseInt($('#id_establecimiento').val());
      var idServicio = parseInt($('#id_servicio').val());
      var recetaActiva = $('#addRecetaVale').attr('aria-pressed') === 'true';

      if (
          idEstablecimiento === 75 &&
          idServicio === 106 &&
          recetaActiva
      ) {
          $('#divCopiarUltimaReceta').show();
      } else {
          $('#divCopiarUltimaReceta').hide();
      }
  }

  $(document).ready(function() {
      validarBotonUltimaReceta();
  });

  

  $('#id_servicio').on('change', function() {
    let id_tipo_atencion = $("#id_tipo_atencion").val();
    const id_servicio_seleccionado = $("#id_servicio").val();

    validarBotonUltimaReceta();

    if(id_servicio_seleccionado != 10 ){
          $("#VerConadis").hide();
    }

    if (String(id_tipo_atencion) !== "") {

      // Vaciar listado de examenes de laboratorio en caso se haya registrado
      $("#tblExamenLab tbody").empty();

      /*var dni_beneficiario = $("#dni_beneficiario").val();
      if (dni_beneficiario == '') {
        return true;
      }*/
      const id_servicio_seleccionado = $("#id_servicio").val();
      if (id_servicio_seleccionado != 0) {

        const dni_beneficiario = $("#dni_beneficiario").val();
        if (dni_beneficiario != '') {
          $.ajax({
            url: `/consultarExistenciaContraReferenciaPendiente/${id_servicio_seleccionado}/${dni_beneficiario}`,
            dataType: "json",
            success: function (res) {
              if ($('#addcontrareferencia').hasClass('active')) {
                $('#addcontrareferencia').click();
              }
              if (res.existeContraReferenciaPendiente && res.id_establecimiento_destino_referencia == $('#id_establecimiento').val() && res.id_servicio_contraReferencia == id_servicio_seleccionado) {
                $('#modalReferencia').hide();
                $('#modalDatosReferencia').show();
                $('#modalDiagnosticosReferencia').show();
                $('#modalProcedimientosReferencia').show();
                $('#referenciaEstablecimientoOrigen').html(res.nombre_establecimiento);
                $('#referenciaServicioOrigen').html(res.referencia.nombre_servicio);
                $('#referenciaObservacion').html(res.referencia.observacion_referencia);
                $('#id_referencia_inicial').val(res.referencia.id);
                $('#modalContraReferencia').show();
                $('#servicio_contrareferencia').html('');
                $('#servicio_contrareferencia').append(
                  $('<option>', {
                      value: '',
                      text: '--SELECCIONAR--'
                  })
                );
                $('#servicio_contrareferencia').append(
                  $('<option>', {
                      value: res.id_establecimiento_origen_referencia,
                      text: res.nombre_establecimiento
                  })
                );
                res.diagnostico_prestacion_referencia.forEach(diagnostico => {
                  $('#tblDiagnosticosReferencia').append(`
                                                  <tr>
                                                    <td>${diagnostico.codigo}</td>
                                                    <td>${diagnostico.nombre}</td>
                                                    <td>${diagnostico.tipo_diagnostico}</td>
                                                  </tr>`);
                });
                res.procedimientos_refencia.forEach(procedimiento => {
                  $('#tblProcedimientosReferencia').append(`
                                                  <tr>
                                                    <td>${procedimiento.codigo}</td>
                                                    <td>${procedimiento.descripcion}</td>
                                                    <td>${procedimiento.observacion}</td>
                                                  </tr>`);
                });
              } else {
                if ($('#id_establecimiento').val() != 76) {
                  $('#modalReferencia').show();
                }
                $('#modalDatosReferencia').hide();
                $('#modalDiagnosticosReferencia').hide();
                $('#modalProcedimientosReferencia').hide();
                $('#referenciaEstablecimientoOrigen').html('');
                $('#referenciaServicioOrigen').html('');
                $('#referenciaObservacion').html('');
                $('#tblDiagnosticosReferencia').html('');
                $('#tblProcedimientosReferencia').html('');
                $('#id_referencia_inicial').val('');
                $('#modalContraReferencia').hide();
                $('#servicio_contrareferencia').html('');
                $('#servicio_contrareferencia').append(
                  $('<option>', {
                      value: '',
                      text: '--SELECCIONAR--'
                  })
                );
                $('#texto_contrareferencia').val('');
              }
            }
          });
        }
        getProducto();
        getUpss();
        getSubConsultorio();
        isServicioQuirurgico();
      }
    } else {
      bootbox.alert({
        message: 'No ha seleccionado el tipo de atención',
        className: 'bootbox-custom-small'
      });
      $(this).val("0"); //.trigger('change');
    }
  });
  $(document).on("change", "#id_sub_consultorio", function(){

      let id_sub_consultorio =  $("#id_servicio").val();

      if(id_sub_consultorio == 10 && $(this).val()!='0'){
          $("#VerConadis").show();
      }else{
          $("#VerConadis").hide();
      }

  });

  function getProductoUltimaReceta(id_farmacia) {

      getProducto(id_farmacia);

      return new Promise(function(resolve) {

          var intentos = 0;

          var verificarProductos = setInterval(function() {

              intentos++;

              if ($('#cmbMedicamentoTemp option').length > 1 || intentos >= 20) {

                  clearInterval(verificarProductos);
                  resolve();

              }

          }, 100);

      });
  }

async function getLastRecipeConsultaExterna() {

    console.log("1 - INICIO COPIAR RECETA");

    var dni_beneficiario = $('#dni_beneficiario').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();

    if (!dni_beneficiario || !id_tipo_atencion) {
        Swal.fire({
            title: 'Información',
            text: 'No se pudo identificar al asegurado o el tipo de atención.',
            icon: 'info'
        });
        return;
    }

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

        var response = await $.ajax({
            type: "GET",
            url: "/api/new-receta/dni/" + dni_beneficiario +
                 "/tipo-atencion/" + id_tipo_atencion,
            dataType: "json"
        });

        console.log("2 - RECETA OBTENIDA", response);

        if (
            response &&
            response.data &&
            response.data.productos &&
            response.data.productos.length > 0 &&
            response.data.id_farmacia
        ) {

            var id_farmacia_actual = $('#id_farmacia_receta').val();

            if (!id_farmacia_actual) {
                Swal.fire({
                    title: 'Información',
                    text: 'Debe seleccionar una farmacia antes de copiar la última receta.',
                    icon: 'info'
                });
                return;
            }

            console.log("3 - FARMACIA ACTUAL:", id_farmacia_actual);

            await getProductoUltimaReceta(id_farmacia_actual);

            console.log("4 - PRODUCTOS DE FARMACIA CARGADOS");
            console.log("5 - OPCIONES:", $('#cmbMedicamentoTemp option').length);
            for (const producto of response.data.productos) {

                var existeProducto =
                    $('#cmbMedicamentoTemp option[value="' + producto.id_producto + '"]').length > 0;

                if (!existeProducto) {

                    $('#cmbMedicamentoTemp').append(
                        $('<option>', {
                            value: producto.id_producto,
                            text: producto.codigo + ' - ' +
                                  producto.nombre + ', ' +
                                  producto.abreviatura
                        })
                    );
                }
            }
            $('#example tbody').empty();
            filasmedicamentos = 0;

            console.log("6 - TABLA LIMPIA");

            for (const producto of response.data.productos) {
                console.log("7 - AGREGANDO PRODUCTO:", producto.id_producto);
                AddProducto(producto);
            }

            console.log("8 - TODOS LOS PRODUCTOS AGREGADOS");

        } else {

            Swal.fire({
                title: 'Información',
                text: 'No se encontraron productos en la última receta.',
                icon: 'info'
            });

            return;
        }

        Swal.close();

    } catch (xhr) {

        var messageError =
            xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : "Ocurrió un problema al buscar la última receta.";

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

  function modalValidacionGeriatrico(beneficiario) {
            if (beneficiario.edadafiliado < 60) {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: 'El beneficiario es menor de 60 años, no se puede registrar una atencion.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                return false;
            }
            return true;
        }
function obtenerBeneficiario(){
  try {
    var id_tiporeceta = 1;
    var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_beneficiario = $("#dni_beneficiario").val();

    var msg = "";
	  //if(id_tiporeceta == "")msg += "Debe seleccionar el tipo de receta <br>";
	  //if(dni_beneficiario == "")msg += "Debe ingresar el numero de documento <br>";
    if (msg != "") {
      bootbox.alert(msg);
      return false;
    }

    $("#statusResponseWsSusalud").val(0);

    const id_servicio_seleccionado = $("#id_servicio").val();
    if (id_servicio_seleccionado != 0) {
      $.ajax({
        url: `/consultarExistenciaContraReferenciaPendiente/${id_servicio_seleccionado}/${dni_beneficiario}`,
        dataType: "json",
        success: function (res) {
          if ($('#addcontrareferencia').hasClass('active')) {
            $('#addcontrareferencia').click();
          }
          if (res.existeContraReferenciaPendiente && res.id_establecimiento_destino_referencia == $('#id_establecimiento').val() && res.id_servicio_contraReferencia == id_servicio_seleccionado) {
            $('#modalReferencia').hide();
            $('#modalDatosReferencia').show();
            $('#modalDiagnosticosReferencia').show();
            $('#modalProcedimientosReferencia').show();
            $('#referenciaEstablecimientoOrigen').html(res.nombre_establecimiento);
            $('#referenciaServicioOrigen').html(res.referencia.nombre_servicio);
            $('#referenciaObservacion').html(res.referencia.observacion_referencia);
            $('#id_referencia_inicial').val(res.referencia.id);
            $('#modalContraReferencia').show();
            $('#servicio_contrareferencia').html('');
            $('#servicio_contrareferencia').append(
              $('<option>', {
                  value: '',
                  text: '--SELECCIONAR--'
              })
            );
            res.servicios_ipress_origen.forEach(servicio => {
              $('#servicio_contrareferencia').append(
                $('<option>', {
                    value: servicio.id,
                    text: servicio.nombre
                })
              );
            });
            res.diagnostico_prestacion_referencia.forEach(diagnostico => {
              $('#tblDiagnosticosReferencia').append(`
                                              <tr>
                                                <td>${diagnostico.codigo}</td>
                                                <td>${diagnostico.nombre}</td>
                                                <td>${diagnostico.id_tipo_diagnostico == 1 ? 'PRESUNTIVO' : diagnostico.id_tipo_diagnostico == 2 ? 'DEFINITIVO' : 'REITERATIVO'}</td>
                                              </tr>`);
            });
            res.procedimientos_refencia.forEach(procedimiento => {
              $('#tblProcedimientosReferencia').append(`
                                              <tr>
                                                <td>${procedimiento.codigo}</td>
                                                <td>${procedimiento.descripcion}</td>
                                                <td>${procedimiento.observacion}</td>
                                              </tr>`);
            });
          } else {
            if ($('#id_establecimiento').val() != 76) {
              $('#modalReferencia').show();
            }
            $('#modalDatosReferencia').hide();
            $('#modalDiagnosticosReferencia').hide();
            $('#modalProcedimientosReferencia').hide();
            $('#id_referencia_inicial').val('');
            $('#referenciaEstablecimientoOrigen').html('');
            $('#referenciaServicioOrigen').html('');
            $('#referenciaObservacion').html('');
            $('#tblDiagnosticosReferencia').html('');
            $('#tblProcedimientosReferencia').html('');
            $('#modalContraReferencia').hide();
            $('#servicio_contrareferencia').html('');
            $('#servicio_contrareferencia').append(
              $('<option>', {
                  value: '',
                  text: '--SELECCIONAR--'
              })
            );
            $('#texto_contrareferencia').val('');
          }
        }
      });
    }
    let usarApi = "{{ $flag_api_sp ? 'api' : 'ws' }}";
    var origen = 'atencion_adicional';
    console.log(usarApi);
    $.ajax({
      url: '/obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario + '/' + usarApi +'/'+ origen,
      dataType: "json",
      success: function(result){
        var flag_api_sp = result.flag_api_sp;

        if (result.sw == false) {
          if ($.LoadingOverlay) {
            $.LoadingOverlay("hide");
          }

          var msgAlerta = result.msg || 'No se puede continuar con la atención.';
          var esNoAfiliado = msgAlerta.indexOf('No se encuentra afiliado') !== -1;

          Swal.fire({
            icon: 'warning',
            title: 'ALERTA',
            html: `
              <div style="font-size:16px; line-height:1.6; text-align:center;">
                <strong>${msgAlerta}</strong>
                ${esNoAfiliado ? `
                <br>
                Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación
                antes de continuar con la atención.
                ` : ''}
              </div>
            `,
            confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
            confirmButtonColor: '#3085d6',
            width: '38em',
            customClass: {
              popup: 'swal-noafiliado',
              title: 'swal-title',
              htmlContainer: 'swal-content'
            }
          }).then(() => {
          $('#beneficiario').val("");
          $('#tipo_beneficiario').val("");
          $('#nombre_beneficiario').val("");
          $('#paterno_beneficiario').val("");
          $('#materno_beneficiario').val("");
          $('#tipodoc_beneficiario').val("");
          $('#cip_beneficiario').val("");
          $('#grado').val("");
          $('#edadpaciente').val("");
          $("#result.beneficiario.nomsexo").val("");
          $('#fecnacafiliado').val("");
          $('#nomsexo').val("");
          $('#nro_historia').val("");
          $('#cip_pnp').val("");
          $('#domicilio').val("");
          $("#telefono_completo").val("");
          $('#telefono').val("");
          $('#telefono2').val("");
          $('#situacion_paciente').val("");
          $('#discapacidad').val("");
          $('#discapacidad_carnet').val("");
          $('#nro_carnet_conadis').val("");
          $('#color_carnet_conadis').val("0");
          $('#presc_producto_apoyo').val("");
          $('#usa_product_apoyo').val("");
          $('#tipo_producto').val("0");
          $('#nro_tipo_producto').val("");
          });
        return;
      }

      if (result.beneficiario && result.beneficiario.success === false) {
          if ($.LoadingOverlay) {
            $.LoadingOverlay("hide");
          }
          try {
            if (Swal.isLoading()) {
              Swal.close();
            }
          } catch (e) {
            Swal.close();
          }
          var status = result.beneficiario.status;
          console.log(status);
          if (status === 300) {
            Swal.fire({
                icon: 'warning',
                title: 'ALERTA',
                html: `
                    El paciente <strong>NO CUENTA CON COBERTURA DE SEGURO VIGENTE.</strong>
                    <br>
                    Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación
                    antes de continuar con la atención.
                `,
                confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                confirmButtonColor: '#3085d6',
                width: '38em',
                customClass: {
                  popup: 'swal-noafiliado',
                  title: 'swal-title',
                  htmlContainer: 'swal-content'
                }
            });
          } else if (status === 404) {
            Swal.fire({
              icon: 'warning',
              title: 'ALERTA',
              html: `
                  <div style="font-size:16px; line-height:1.6; text-align:center;">
                      <strong>NO SE ENCUENTRA REGISTRO</strong>
                      del paciente en <strong>SALUDPOL</strong>.<br>
                      Debe acercarse a las oficinas de SALUDPOL para regularizar
                      su registro.
                  </div>
              `,
              confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
              confirmButtonColor: '#3085d6',
              width: '38em',
              customClass: {
                popup: 'swal-noafiliadosp',
                title: 'swal-title',
                htmlContainer: 'swal-content'
              }
            });
          } else if (status === 401) {
            Swal.fire({
              icon: 'error',
              title: 'CREDENCIALES INVÁLIDAS',
              html: `
                <div style="font-size:16px; line-height:1.6; text-align:center;">
                  No se pudo autenticar con el servicio de <strong>SALUDPOL</strong>.
                  <br>
                  Comuníquese con el área de soporte técnico.
                </div>
              `,
              confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
              confirmButtonColor: '#3c763d',
              width: '38em',
              customClass: { popup: 'swal-afiliacion' }
            });
          } else if (status === 400) {
            Swal.fire({
              icon: 'error',
              title: 'DATOS INVÁLIDOS',
              text: result.beneficiario.message,
              confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
              confirmButtonColor: '#3c763d',
              customClass: { popup: 'swal-afiliacion' }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'ERROR AL CONSULTAR SALUDPOL',
              html: `
                <div style="font-size:16px; line-height:1.6; text-align:center;">
                  ${result.beneficiario.message}
                  <br>
                  Si el problema persiste, comuníquese con soporte técnico.
                </div>
              `,
              confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
              confirmButtonColor: '#3c763d',
              width: '38em',
              customClass: { popup: 'swal-afiliacion' }
            });
          }
          return;
        }

        if (parseInt($("#id_establecimiento").val()) == 75) {
          const resModalGeriatrico = modalValidacionGeriatrico(result.beneficiario);
          if (!resModalGeriatrico) {
              return;
          }
        }

        $("#statusResponseWsSusalud").val(1);
        
        $('#ocultar1').hide();
        
        let edadafiliado = "";
        if (result.beneficiario.fecha_nac) {
          const date_current = moment();
          const fecha_nac_format = moment(result.beneficiario.fecha_nac, 'DD/MM/YYYY').format("YYYY-MM-DD")
          edadafiliado = parseInt(date_current.diff(fecha_nac_format, 'years') || 0);
        } else {
           edadafiliado = parseInt(result.beneficiario.edadafiliado || 0);
        }

        if (result.beneficiario.nomsexo && 
            String(result.beneficiario.nomsexo).toUpperCase() ==='F' && 
            edadafiliado >= 12 && edadafiliado <= 55
        ) {
            obtenerUltimaGestacion(selectServicio.value);
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
        $('#cip_pnp').val(result.beneficiario.cip);
        $('#grado').val(result.beneficiario.grado);
        $('#fecnacafiliado').val(result.beneficiario.fecnacafiliado);
        $("#fecnacafiliado_completo").val(result.beneficiario.fecnacafiliado+" / "+result.beneficiario.nomsexo);
        $('#nomsexo').val(result.beneficiario.nomsexo);
        $('#edadpaciente').val(result.beneficiario.edadafiliado);
        $('#domicilio').val(result.domicilio);
        $('#telefono').val(result.telefono);
        $('#telefono2').val(result.telefono2);
        $('#situacion_paciente').val(result.beneficiario.situacion);
        $('#discapacidad').val(result.discapacidad);
        $('#discapacidad_carnet').val(result.discapacidad_carnet);
        $('#nro_carnet_conadis').val(result.nro_carnet_conadis);
        // $('#color_carnet_conadis').val(result.color_carnet_conadis);
        $('#color_carnet_conadis').val(result.color_carnet_conadis || "0");
        // $('#presc_producto_apoyo').val(result.presc_producto_apoyo);
        // $('#usa_product_apoyo').val(result.usa_product_apoyo);
        //  $('#tipo_producto').val(result.tipo_producto);
        //  $('#nro_tipo_producto').val(result.nro_tipo_producto);
        $('#presc_producto_apoyo').val(result.presc_producto_apoyo);
        $('#usa_product_apoyo').val(result.usa_product_apoyo);
        $('#tipo_producto').val(result.tipo_producto || "0");
        $('#nro_tipo_producto').val(result.nro_tipo_producto);

        let apellidosNombresBeneficiario =
          result.beneficiario.apepatafiliado + " " +
          result.beneficiario.apematafiliado + " " +
          result.beneficiario.apecasafiliado + " " +
          result.beneficiario.nomafiliado;

        $('#apellidos_nombres_beneficiario').val(
          apellidosNombresBeneficiario.trim()
        );

        if(id_servicio_seleccionado == 10){
          // if($("#codigo_upss").val()!=0 &&  $("#id_sub_consultorio").val()!=0){
            $("#VerConadis").show(); 
          /*}else{
            $("#VerConadis").hide();
          }*/
          if(result.discapacidad == 1){
            // Marcar el radio button encontrado
            const radio = document.getElementById("discapacidad1");
            radio.checked = true;
            const radios = document.getElementsByName("discapacidad");
            for (let i = 0; i < radios.length; i++) {
              if (radios[i] !== radio) {
                radios[i].checked = false;
              }
            }
            if(result.discapacidad_carnet == 1){
              $("#paciente_discapacitado_carnet").show();
              $("#paciente_discapacitado").show();
              const radio1 = document.getElementById("discapacidad_carnet_1");
              radio1.checked = true;
              const radios1 = document.getElementsByName("discapacidad_carnet");
              for (let i = 0; i < radios1.length; i++) {
                if (radios1[i] !== radio1) {
                  radios1[i].checked = false;
                }
              }
              const selectElement = document.getElementById("color_carnet_conadis");
              selectElement.selectedIndex = result.color_carnet_conadis;
            }
            else{
              $("#paciente_discapacitado").show();
              const radio1 = document.getElementById("discapacidad_carnet_2");
              radio1.checked = true;
              const radios1 = document.getElementsByName("discapacidad_carnet");
              for (let i = 0; i < radios1.length; i++) {
                if (radios1[i] !== radio1) {
                  radios1[i].checked = false;
                }
              }
            }           
          }

          if(result.presc_producto_apoyo == 1){
            const radio_product = document.getElementById("presc_producto_apoyo1");
            radio_product.checked = true;
            const radios_products = document.getElementsByName("presc_producto_apoyo");
            for (let i = 0; i < radios_products.length; i++) {
              if (radios_products[i] !== radio_product) {
                radios_products[i].checked = false;
              }
            }
            if(result.usa_product_apoyo == 1){
              $("#tips_products").show();
              $("#producto_apoyo").show();
              const radio_product1 = document.getElementById("usa_product_apoyo_1");
              radio_product1.checked = true;
              const radios_products1 = document.getElementsByName("usa_product_apoyo");
              for (let i = 0; i < radios_products1.length; i++){
                if(radios_products1[i] !== radio_product1){
                  radios_products1[i].checked = false;
                }
              }
              // const selectElement = document.getElementById("tipo_producto");
              // selectElement.selectedIndex = result.tipo_producto;
            }else{
              $("#producto_apoyo").show();
              const radio_product1 = document.getElementById("usa_product_apoyo_2");
              radio_product1.checked = true;
              const radio_products1 = document.getElementsByName("usa_product_apoyo");
              for (let i = 0; i < radio_products1.length; i++){
                if(radio_products1[i] !== radio_product1){
                  radio_products1[i].checked = false;
                }
              }
            }
          }
        }
        /*if(result.numero_historia != null && result.numero_historia!=""){
          $('#nro_historia').val(result.numero_historia);
        }else{
          $('#nro_historia').prop("readonly",false);
        }*/
        if(result.numero_historia!=null){
          if(result.numero_historia!='')
            $('#nro_historia').val(result.numero_historia);
          else
            $('#nro_historia').val(result.beneficiario.nrodocafiliado);	
        }
        else				
          $('#nro_historia').val(result.beneficiario.nrodocafiliado);
        
        $('#cip_beneficiario').val(result.beneficiario.cip);
        $('#cip_pnp').val(result.beneficiario.cip);
        
        $('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
        $('#nrodoctitular').val(result.beneficiario.nrodoctitular);
        titular_dato= result.beneficiario.nrodoctitular +" / "+(result.beneficiario.cip?? '-');
        $("#nrodoctitular_completo").val(titular_dato);
        //$('#tipo_beneficiario').val(result.beneficiario.parentesco);
        $('#nombre_titular').val(result.beneficiario.nomtitular);
        $('#paterno_titular').val(result.beneficiario.apepattitular);
        $('#materno_titular').val(result.beneficiario.apemattitular+" "+result.beneficiario.apecastitular);
        //$('#beneficiario').val(beneficiario);

        let apellidosNombresTitular =
          result.beneficiario.apepattitular + " " +
          result.beneficiario.apemattitular + " " +
          result.beneficiario.apecastitular + " " +
          result.beneficiario.nomtitular;

        $('#apellidos_nombres_titular').val(apellidosNombresTitular.trim());

        var fecha_nacimiento = result.beneficiario.fecnacafiliado;
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
            var id_servicio = 8;
            $("#parent1").prop("checked", true);
          }
          if(cita[0].id_estado_cita == 3){
            var id_servicio = cita[0].id_servicio;
            $("#parent2").prop("checked", true);
          }
        }else{
          var id_servicio = 8;
          $("#parent1").prop("checked", true);
        }
        
        var id_establecimiento = $("#id_establecimiento").val();
        var id_servicio = $("#id_servicio").val();
        var id_medico = $("#id_medico").val();

        //if(result.cantidad_cita!=null && result.cantidad_cita>0)
        modalProximaCitaServicio(tipodoc_beneficiario,dni_beneficiario,id_servicio,id_establecimiento,id_medico);
        
        $.ajax({
          url: '/condicion_paciente/' + dni_beneficiario + '/' + id_establecimiento + '/1/' + id_servicio,
          dataType: "json",
          success: function(resultado){
              $("#ipress_bene").val(resultado.ipress_bene);
              $("#ipress_bene").select2({max_selected_options: 4});
              $("#upss_bene").val(resultado.upss_bene);
              $("#upss_bene").select2({max_selected_options: 4});
            }
        });
        
        $.ajax({
          url: '/obtener_antecedentes_by_id_beneficiario/'+ dni_beneficiario,
          dataType: "json",
          success: function(resultado){
            $("#antecedente").val(resultado.antecedente);
            $("#antecedente_familiar").val(resultado.antecedente_familiar);
          }
        });

        $.ajax({
          url: '/obtener_servicios/' + dni_beneficiario,
          success: function(result) {
            $('#servicio_referencia').empty();   
            $('#servicio_referencia').append("<option value=''>--SELECCIONAR--</option>");   
            contador=0;
            id_contador=0;
            $(result).each(function(ii, oo) {
              contador++; 
              id_contador=oo.id;
              $('#servicio_referencia').append("<option value='" + oo.id + "'>" + oo.nombre + "</option>");    
            }); 
            if(contador==1){
              $('#servicio_referencia').val(id_contador).change();
            }else{
              $('#servicio_referencia').val("").change();
            }
          }
        });
    
        //alert(id_establecimiento);
        //obtenerEstablecimiento(parentesco,ubigeo,id_establecimiento);
        //obtenerEstablecimiento(parentesco,ubigeo,76,id_servicio);
        //getServicio(id_servicio);
        
        modalRC();
        // modalHistorial();
        modalLaboratorio();
        modalRadiologico();
        modalInterconsulta();
        if (typeof window.modalInformesPp === 'function') window.modalInformesPp();
        getProducto();

        modalHistorial();
        modalHistorial1();
        modalHistorial2();
        modalHistorialEvaluaciones();
        modalHistorial3();
        modalHistorialEvaluacionesHospitalizacion();
        
        $('#codigo_siteds').val("");
        const enabledGenerateSitedAutomatic = validateFechaAtencionForSitedsAutomatic();
        if (enabledGenerateSitedAutomatic) {
          $("#codigo_siteds").prop("readonly", true);
          generarCodigoSiteds();
        } else {
          $("#input-siteds").removeClass('input-group');
          $("#codigo_siteds").prop('readonly', false);
          Swal.fire({
            icon: "warning",
            title: `<div style='color: #060606; margin: 10px 0px -10px 0px'><p style='font-size: 20px; color: #E4A11B'><b>!Aviso¡</b></p><p style='font-size: 16px;'>CÓDIGO SITEDS</p></div>`,
            html: `<div style='padding: 10px 30px 0px 30px;><span style='color: #000000; font-size: 18px;'>Para completar el registro de la atención, deberá ingresar el código SITEDS manualmente. Este ingreso será bajo su responsabilidad.</span></div>`,
            confirmButtonText: 'ENTENDIDO',
          });
        }

        //AddDiagnostico();
        //AddProcedimiento(); 
        //AddExamenLab();
        //AddExamenRad();
        //alert(ubigeo);
        /*
        if(id_tiporeceta == 3){	
          ShowByServicioAndId_farmacia();
        }
        */
       if ($("#id_servicio").val() != '') {
        const res = atencionPermitida();
        analizarCondicionesCitaParaBloqueoDeFlujo(res, "PEDIATRIA", "La edad no corresponde a un paciente pediatrico.");
       }
       
       if (result && result.beneficiario && result.beneficiario.caducidad) {
        alertVencimientoSeguro(result.beneficiario.caducidad);
       }

       validarPacienteConIntervencionQuirurgica();
        
        //$("#btn_generate_siteds").prop("disabled", false);
      },
      error: function(response) {
        location.reload();
      }
    });
  } catch (error) {
    console.log("[error] =>",error);
  }
}

function obtenerUltimaGestacion(id_servicio = null) {
  $("#divDetailGestation").hide();
  $("#divDetailPuerpera").hide();
  $('#ocultar1').hide();
  $("#id_gestacion").val("");
  const dniBeneficiario = $("#dni_beneficiario").val();
  $.ajax({
    url: `/obtener_ultima_gestacion_dni_asegurado_ajax/${dniBeneficiario}`,
    dataType: "json",
    success: (result) => {
      if (result && result.length > 0) {
        const lastGestation = result[0];
        if (parseInt(id_servicio) === 77 || parseInt(id_servicio) === 131) {
          if (lastGestation.fech_prob_parto) {
            handlerValid28DaysPuerpera(lastGestation);
          }else {
            showFormGestationSet(lastGestation);
          }
        } else {
          if (lastGestation.fech_prob_parto) {
            handlerValid28DaysPuerpera(lastGestation);
          }else {
            setDetailGestation(lastGestation);
          }
        }
      } else if (parseInt(id_servicio) === 77 || parseInt(id_servicio) === 131) {
        showFormClearGestation();
      }      
    },
    error: (err) => {
      console.log("[err] =>", err);
    }
  })
}

function handlerValid28DaysPuerpera(lastGestation) {
  if (lastGestation && lastGestation !== null && JSON.stringify(lastGestation) !== "{}") {
    if (lastGestation.fech_prob_parto) {
      const currentDate = moment();
      const dateLimit = moment(lastGestation.fech_prob_parto).add(28, 'days');
      $("#divDetailPuerpera").hide();
      if (currentDate.isBefore(dateLimit)) {
        setDetailPuerpera(lastGestation);
      } else {
        showFormClearGestation();
      }
    }
  }
}

function setDetailGestation(lastGestationParam) {
  $("#establecimiento_nombre_detail").text(lastGestationParam.establecimiento_nombre || "");
  $("#fech_ult_regla_format_detail").text(lastGestationParam.fech_ult_regla_format || "");
  $("#fech_prob_parto_format_detail").text(lastGestationParam.fech_prob_parto_format || "");
  $("#semanas_gestacion_detail").text(lastGestationParam.semanas_gestacion || "");
  $("#num_controles_detail").text(lastGestationParam.num_controles || "");
  $("#duda_detail").text(lastGestationParam.duda ? "SI" : "NO" || "");
  $("#divDetailGestation").show();
  $('#esta_gestando').removeClass('active');
  $('#esta_gestando').attr('aria-pressed', 'false');
  $("#frsgetante").hide();
}

function showFormClearGestation() {
  $("#ultimaMestruacion").removeAttr('disabled');
  $("#parto_probable").removeAttr('disabled');
  // $("#control_prenatal").removeAttr('disabled');
  $("#alerta_control_prenatal").text("Esta paciente va a inciar el primer control de salud materna en este establecimiento.");
  $("#duda").removeAttr('disabled');
  clearFormGestation();
  $('#ocultar1').show();
  $('#esta_gestando').removeClass('active');
  $('#esta_gestando').attr('aria-pressed', 'false');
  $("#frsgetante").hide();
}

function showFormGestationSet(lastGestationParam) {
  setValuesFormGestation(lastGestationParam);
        //TODO: Encontrar forma de desactivar estos input
  //$('#ultimaMestruacion').prop("disabled", true);
  //$('#parto_probable').prop("disabled", true);
  // $('#control_prenatal').prop("disabled", true);
  $("#alerta_control_prenatal").text(`En la ultima consulta de salud materna, el nro de control prenatal fue: ${lastGestationParam.num_controles}`);
  $('#duda').prop("disabled", true);
  $('#ocultar1').show();
}

function setDetailPuerpera(puerperaParam) {
  const fecha_parto_format = moment(puerperaParam.fech_prob_parto, 'YYYY-MM-DD HH:mm:ss').format('DD-MM-YYYY HH:mm:ss');
  $("#fecha_parto_detail").text(fecha_parto_format || "");
  $("#divDetailPuerpera").show();
  $('#esta_gestando').removeClass('active');
  $('#esta_gestando').attr('aria-pressed', 'false');
  $("#frsgetante").hide();
}

function setValuesFormGestation(data) {
//TODO: IMPLEMENTAR VALIDACION DE CONTROL POR MES AQUI
  /* const newControl = parseInt(data.num_controles) + 1;
  $('#ultimaMestruacion').val(data.fech_ult_regla_format);
  $('#parto_probable').val(data.fech_prob_parto_format);
  const fechaDeHoy = moment();
  const fechaUltimaMenstruacion = moment(data.fech_ult_regla, 'YYYY-MM-DD');
  const diff = fechaDeHoy.diff(fechaUltimaMenstruacion, 'week');
  $('#semana_gesta').val(`SEMANA N° ${diff}`);
  $('#control_prenatal').val(String(newControl));
  $('#duda').val(data.duda ? '1' : '0');
  $('#esta_gestando').addClass('active');
  $('#esta_gestando').attr('aria-pressed', 'true');
  $("#frsgetante").show(); */
 
  const fechaDeHoy = moment();
  // const fechaAtencionUltimaGestacion = moment(data.fecha_atencion.split(" ")[0], "YYYY-MM-DD");
  // const mesesTranscurridoDelUltimoRegistroGestacion = fechaDeHoy.diff(fechaAtencionUltimaGestacion, "month");
  const fechaUltimaMenstruacion = moment(data.fech_ult_regla, 'YYYY-MM-DD');
  const diff = fechaDeHoy.diff(fechaUltimaMenstruacion, 'week');

  //const newControl = mesesTranscurridoDelUltimoRegistroGestacion < 7 ? mesesTranscurridoDelUltimoRegistroGestacion+1 : parseInt(data.num_controles) + 1;
  $("#id_gestacion").val(data.id_gestacion);
  $('#ultimaMestruacion').val(data.fech_ult_regla_format);
  $('#parto_probable').val(data.fech_prob_parto_format);
  $('#semana_gesta').val(`SEMANA N° ${diff}`);
  //$('#control_prenatal').val(String(newControl == 0 ? 1 : newControl));
  $('#duda').val(data.duda ? '1' : '0');
  $('#esta_gestando').addClass('active');
  $('#esta_gestando').attr('aria-pressed', 'true');
  $("#frsgetante").show();
}

function clearFormGestation() {
  $('#ultimaMestruacion').val('');
  $('#parto_probable').val('');
  $('#semana_gesta').val('');
  $('#control_prenatal').val('');
  $('#duda').val('0');
  $('#esta_gestando').removeClass('active');
  $('#esta_gestando').attr('aria-pressed', 'false');
  $("#frsgetante").hide();
}

function obtenerMedico(){
		
	var id_establecimiento = $("#id_establecimiento").val();
	var id_medico = $("#id_medico").val();
	
	$.ajax({
		url: '/obtener_medico_pnp/' + id_medico + '/' + id_establecimiento ,
		dataType: "json",
		success: function(result){
			if(result.sw == false){
				bootbox.alert(result.msg);
				$('#dni_medico').val("");
				$('#medico_paterno').val("");
				$('#medico_materno').val("");
				$('#medico_nombre').val("");
				$('#colegiatura').val("");
				$('#rne').val("");
				$('#profesion').val("");
				$('#especializacion').val("");
				
				return false;
			}
			
			$('#dni_medico').val(result.dni);
			$('#medico_paterno').val(result.paterno);
			$('#medico_materno').val(result.materno);
			$('#medico_nombre').val(result.nombre);
			$('#colegiatura').val(result.colegiatura);
			$('#rne').val(result.rne);
			$('#profesion').val(result.profesion);
			$('#especializacion').val(result.especializacion);
			
		}
		
	});
	
}
function obtenerEstablecimiento(parentesco,ubigeo){
	var newOption = "";
	newOption = "<option value=''>--Seleccionar--</option>";
	$('#id_establecimiento').html(newOption);
	
	$.ajax({
		url: '../obtener_establecimiento/' + parentesco + "/" + ubigeo,
		success: function(result) {
			var newOption = "";
			newOption = "<option value=''>--Seleccionar--</option>";
			$(result).each(function(ii, oo) {
				newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
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
    $('#cip_pnp').val("");
    $('#situacion_paciente').val("");
		$('#id_admision').val("");
		$('#grado').val("");
    $('#domicilio').val("");
    $('#telefono').val("");
    $('#telefono2').val("");
    $('#discapacidad').val("");
    $('#discapacidad_carnet').val("");
    $('#nro_carnet_conadis').val("");
    $('#color_carnet_conadis').val("");
    // $('#presc_producto_apoyo').val("");
    // $('#usa_product_apoyo').val("");
    // $('#tipo_producto').val("");
    // $('#nro_tipo_producto').val("");
	}
	
	//if(dni_beneficiario!="")obtenerBeneficiario();
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
  const id_servicio = $("#id_servicio").val();
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_atenciones/"+dni_beneficiario,
			type: "GET",
			success: function (result) {
        let contentElement = $(result);
        contentElement.find('button#btn_show_detail').each(function() {
            $(this).removeClass('btn btn-danger btn-success btn-xs');
            let dataIdServicio = $(this).data('id_servicio');
            if (parseInt(id_servicio) === parseInt(dataIdServicio)) {
              $(this).addClass('btn btn-success btn-xs');
            } else {
              $(this).addClass('btn btn-danger btn-xs');
            }
        });

				$("#divHistorial").html(contentElement);
				// $("#divHistorial").html(result);
			}
	});
}
function modalLaboratorio(){
  const nivel = {!! json_encode($nivel) !!};
	var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
    url: `/modal_historial_laboratorio_nivel_1_2/${dni_beneficiario}`,
    type: "GET",
    success: function (result) {  
      $("#divLaboratorioNivelI_II").html(result);
      $('#tablemodalLaboratorio').DataTable({"sPaginationType": "full_numbers", "language": {"url": "/js/Spanish.json"}});
    }
  });
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
  /* if (nivel && parseInt(nivel) === 1 || nivel && parseInt(nivel) === 2) {
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
  } */
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
        container: 'swal-confidencialidad-interconsulta-adic-backdrop'
      }
    });
    generar_marca_agua_hist_interconsulta();
    $.ajax({
        url: '/auditoria-hc/registrar',
        type: 'POST',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          origen: 'Atencion Adic. CE(Hist. Interconsulta)',
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

var texto_marca_agua_hist_pp = '{{ "DNI -" }} {{ auth()->user()->dni }}  {{ auth()->user()->name }}  {{ auth()->user()->paterno }}  {{ auth()->user()->materno }}';
function generar_marca_agua_hist_pp() {
  var overlay = document.getElementById('marca_agua_overlay_hist_pp');
  if (!overlay) return;
  overlay.innerHTML = '';
  var W = overlay.offsetWidth  || 800;
  var H = overlay.offsetHeight || 600;
  var cols = Math.ceil(W / 220) + 1;
  var rows = Math.ceil(H / 100) + 1;
  for (var r = 0; r < rows; r++) {
    for (var c = 0; c < cols; c++) {
      var span = document.createElement('span');
      span.textContent = texto_marca_agua_hist_pp;
      span.style.left = (c * 220 - 40) + 'px';
      span.style.top  = (r * 100 - 20) + 'px';
      overlay.appendChild(span);
    }
  }
}

$(document).ready(function () {
  $('a[href="#tab_INFORMES_PP"]').on('shown.bs.tab', function () {
    Swal.fire({
      icon: "warning",
      html: `<div style="padding: 0px 20px 20px 20px">
              <p style="font-size: 16px"><b>El historial de Procedimientos Programados contiene datos sensibles y que su protección es esencial para el bienestar del paciente, así como para el cumplimiento de la normativa legal de privacidad y protección de datos y Ley.</b></p>
            </div>`,
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
      customClass: {
        container: 'swal-confidencialidad-hist-pp-backdrop'
      }
    });
    generar_marca_agua_hist_pp();
    $.ajax({
        url: '/auditoria-hc/registrar',
        type: 'POST',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          origen: 'Hist. Proc. Prog. CE - ADICIONAL',
          doc_buscado: $('#dni_beneficiario').val(),
          id_tipo_atencion: 1
        }
    });
  });

  var resizeMarcaAguaHistPpTimer = null;
  $(window).on('resize', function () {
    clearTimeout(resizeMarcaAguaHistPpTimer);
    resizeMarcaAguaHistPpTimer = setTimeout(function () {
      if ($('#tab_INFORMES_PP').is(':visible')) generar_marca_agua_hist_pp();
    }, 250);
  });
});

function modalInterconsulta(){
  
  var dni_beneficiario = $("#dni_beneficiario").val();
  $.ajax({
      url: "../modal_historial_interconsulta/"+dni_beneficiario,
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
function updateSelectElements() {
  // Obtener todos los elementos <select> con la clase "select-diagnostico"
  let selectElements = document.querySelectorAll(".select-diagnostico");

  selectElements.forEach((selectElement) => {
    // Almacenar el valor actualmente seleccionado
    let currentValue = selectElement.value;

    // Eliminar las opciones actuales
    selectElement.innerHTML = "";

    let option = document.createElement("option");
    option.value = "";
    option.textContent = "---Seleccionar---";
    selectElement.appendChild(option);
    // Agregar las opciones basadas en listIdDiagnosticoObjetos
    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement("option");
      option.value = item.id;
      option.textContent = item.nombre;
      selectElement.appendChild(option);
    });

    // Verificar si el valor seleccionado aÃºn estÃ¡ disponible en las opciones
    let optionExists = Array.from(selectElement.options).some(
      (option) => option.value === currentValue
    );

    // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
    if (!optionExists) {
      selectElement.value = "";
    } else {
      // Si el valor seleccionado aÃºn estÃ¡ disponible en las opciones, restablecer el valor seleccionado
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
    // Realizar la peticion AJAX usando el codigo id_diagnostico
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
function modalRC(){
    var dni_beneficiario = $("#dni_beneficiario").val();
	  $.ajax({
			url: "../modal_historial_rc_dni/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
       
					$("#divRC1").html(result);
					
			}
	});
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
  item_tipo_diagnostico += '<option value="0">--Seleccionar--</option>';
  item_tipo_diagnostico += '<option value="1">PRESUNTIVO</option>';
  item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';
  item_tipo_diagnostico += '<option value="3">REITERATIVO</option>';
  newRow += '<tr>';
  newRow +=
    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico' +
    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
  newRow += '<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico' + ind +
    '" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
  newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind +
    '" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
  newRow +=
    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow += '</tr>';
  $('#tblDiagnostico tbody').append(newRow);


  let genero = 1;
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
        url: '../obtener_diagnostico_genero/' + request.term + '/' + genero,
        dataType: "json",
        success: function(data) {
          let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
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

function AddProcedimientoProgramado() {
  var newRow = "";
  var ind = $('#tblSolicitudPp .pp-item').length;
  if(ind > 0){
    $('.id_procedimiento_p').each(function(){
      var ind_tmp = $(this).data("id");
      if(ind_tmp >= ind){
        ind=Number(ind_tmp)+1;
      }
    });
  }
  newRow += '<div class="pp-item" style="margin-top:15px;">';
  newRow += '<div class="row">';
  newRow += '<div class="col-sm-7"><label>Procedimiento</label>';
  newRow += '<select name="nombre_procedimiento_p[]" id="nombre_procedimiento_p' + ind + '" class="form-control" style="width:100%"><option></option></select>';
  newRow += '<input type="hidden" class="id_procedimiento_p" id="id_procedimiento_p' + ind + '" name="id_procedimiento_p[]" value="" data-id='+ind+'></div>';
  newRow += '<div class="col-sm-1"><label>Cantidad</label>';
  newRow += '<input type="number" min="1" max="99" class="form-control procedimiento_cantidad_p" id="procedimiento_cantidad_p' + ind + '" name="procedimiento_cantidad_p[]" value="1"></div>';
  newRow += '<div class="col-sm-3"><label>Diagnostico</label>';
  newRow += '<select class="form-control select-diagnostico" id="miSelectPp' + ind + '" name="miSelectPp[]"></select></div>';
  newRow += '<div class="col-sm-1"><label>&nbsp;</label><br>';
  newRow += '<button type="button" class="btn btn-danger deleteFila">ELIMINAR</button></div>';
  newRow += '</div>';
  newRow += '<div class="row" style="margin-top:10px;"><div class="col-sm-12"><label>Indicación del Procedimiento</label>';
  newRow += '<textarea class="form-control procedimiento_indicacion_p" rows="4" id="procedimiento_indicacion_p' + ind + '" name="procedimiento_indicacion_p[]" placeholder="--- Registrar indicaciones del procedimiento programado---"></textarea>';
  newRow += '</div></div>';
  newRow += '</div>';
  $('#tblSolicitudPp').append(newRow);
  const selectElement = document.getElementById('miSelectPp' + ind);
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
  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  var genero = (sexo == 'M') ? 1 : 2;
  let proceduresExceptions = ['99199.01', '93000', 'D0160'];
  $("#nombre_procedimiento_p" + ind).select2({
    placeholder: '--- Seleccione procedimiento ---',
    allowClear: true,
    minimumInputLength: 0,
    width: '100%',
    language: {
      noResults: function() { return 'No se encontraron procedimientos'; },
      searching: function() { return 'Buscando...'; }
    },
    ajax: {
      url: '/pp_obtener_procedimiento_servicio',
      dataType: 'json',
      delay: 400,
      data: function(params) {
        return { buscar: params.term || '', nivel: nivel, genero: genero, id_servicio: $('#id_servicio').val() };
      },
      processResults: function(data) {
        let listaProcedimientoSelecionado = $(".id_procedimiento_p").not("#id_procedimiento_p" + ind).map(function(){return parseInt($(this).val());}).get();
        let filtro = data.filter((element)=>
          !listaProcedimientoSelecionado.includes(element.id) &&
          !proceduresExceptions.includes(String(element.codigo).toUpperCase())
        );
        return { results: filtro.map((element) => ({ id: element.id, text: element.label })) };
      }
    }
  }).on('select2:select', function(e) {
    $("#id_procedimiento_p" + ind).val(e.params.data.id);
  }).on('select2:unselect', function() {
    $("#id_procedimiento_p" + ind).val("");
  });
  $("#procedimiento_indicacion_p" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento_p" + ind).val();
    if(id_procedimiento == ""){
      bootbox.alert("Falta ingresar la indicacion en esta fila");
      $("#procedimiento_indicacion_p" + ind).val("");
      return false;
    }
  });
  $("#procedimiento_cantidad_p" + ind).on('blur', function() {
    var id_procedimiento = $("#id_procedimiento_p" + ind).val();
    if(id_procedimiento == ""){
      bootbox.alert("Falta ingresar la cantidad en este procedimiento");
      $("#procedimiento_cantidad_p"+ind).val("");
      return false;
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
    option.value = item.id;
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
        url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
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
    if (id_procedimiento == "") {
      bootbox.alert("Falta ingresar la cantidad en este procedimiento");
      $("#procedimiento_cantidad" + ind).val("");
      return false;
    }
  });
	
}

function  AddInterconsulta2(){
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

  
  var tabindex = 112;
  
  newRow +='<tr>';
  newRow +='<td><div class="panel panel-info panel-bordered panel-sm"><div class="form-group"> <div class="panel-body"> <div class="col-md-1" style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px"> <label>SERVICIO</label></div> <div class="col-md-4" style="margin-left:0px;padding-left:0px"> <input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el servicio" name="nombre_servicio_interconsulta[]" tabindex="' + tabindex + '" required="" id="nombre_servicio_interconsulta'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" /> </div> <div class="col-md-1"> <label>Personal de Salud</label> </div> <div class="col-md-6"><select tabindex="72" class="form-control" name="personal_salud[]" id="personal_salud'+ind+'" style="width:100%"><option value="">-Seleccionar-</option></select> </div> <div class="col-md-12"><br/> <label>RESUMEN</label> <textarea class="form-control" cols="5" id="motivo_interconsulta'+ind+'" name="motivo_interconsulta[]" placeholder="Relato m&eacute;dico o caracteristicas de la interconsulta"></textarea></div></div></div></div></td>';
  newRow +='<td><button tabindex="114" type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_servicio_interconsulta" id="id_servicio_interconsulta'+ind+'" name="id_servicio_interconsulta[]" value="" data-id='+ind+'></td>';
  newRow +='</tr>';
  
  var id_ipress     = $('#id_establecimiento').val();

  $('#tblInterconsulta tbody').append(newRow);
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

function AddExamenInterPreQuirurgica(){
	var newRow = "";
	var ind = $('#tblExamenInterPreQuirurgica tbody tr').length;
  var ind = filasmedicamentos;
	var tabindex = 71;
	var nuevalperiodo = "";

	if(ind > 0){
    $('.cmbMedicamento_preqx').each(function(){
      var ind_tmp = $(this).attr("ind");
      if(ind_tmp => ind){
        ind=Number(ind_tmp)+1;
        tabindex+=3;
      }
    });
	}
	
  var item_producto = "";
  $('#cmbMedicamentoTemp_preqx option').each(function() {
    item_producto += "<option value=" + $(this).val() + ">" + $(this).html() + "</option>"
  });
	
  newRow +='<tr>';
  newRow +='<td><select class="form-control cmbMedicamento_preqx" id="cmbMedicamento_preqx' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="id_servicio_interconsulta_preqx[]">' + item_producto + '</select></td>';
  newRow +='<td style="padding-left: 5px;"><input type="text" class="form-control motivo_interpreqx" id="motivo_interconsulta_preqx' + ind + '" ind="' + ind +'" tabindex="' + tabindex + '" name="motivo_interconsulta_preqx[]"></td>';
  newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
  newRow +='</tr>';

  $('#tblExamenInterPreQuirurgica tbody').append(newRow);
  $("#cmbMedicamento_preqx" + ind).select2({max_selected_options: 4}).focus();
	
	
	$("#cmbMedicamento_preqx"+ind).on("change", function (e) {
    var flagx = 0;
    cmb = $(this);
    id_producto = $("#cmbMedicamento_preqx"+ind).val();
    id_user={{Auth::user()->id}};
    
    $('.cmbMedicamento_preqx').each(function(){
      var ind_tmp = $(this).val();
      if($(this).val() == id_producto)flagx++;
    });
    
    if(flagx > 1){
      bootbox.alert("El Servicio ya ha sido ingresado");
      $("#cmbMedicamento_preqx"+ind).val("").trigger("change");
      return false;
    }
	});

  filasmedicamentos++;
}

$('#tblExamenInterPreQuirurgica tbody').on('click', 'button.deleteFila', function () {
  var obj = $(this);
  // Selecciona la fila completa
  var fila = obj.closest('tr');

  // Obtiene la posición de la fila
  var filaPosition = fila.index();
  
  // Elimina la fila
  obj.parent().parent().remove();
  // filasmedicamentos--;
});


        function handleTipoDoc() {
            const selects = document.querySelectorAll(".tipo_doc");
            selects.forEach((select) => {
                select.addEventListener('change', () => {
                    const inputTipoDoc = select.parentNode.nextElementSibling.firstElementChild;
                    const tipoDoc = select.options[select.selectedIndex].text;
                    inputTipoDoc.placeholder = tipoDoc;
                    if (tipoDoc == "DNI") {
                        inputTipoDoc.maxLength = 8;
                    } else {
                        inputTipoDoc.maxLength = 12;
                    }
                });
            });
        }

        // function AddRowPurperio() {
        //     let newRow = "";
        //     let ind = $('#tblPuerpera tbody tr').length;
        //     if (ind > 0) {
        //         $('.id_puerperio').each(function() {
        //             let ind_tmp = $(this).data("id");
        //             if (ind_tmp => ind) {
        //                 ind = Number(ind_tmp) + 1;
        //             }
        //         });
        //         fila_medicamentos = $('#tblPuerpera tbody').children('tr');
        //         rowIndex = $('#hidden_rowIndex').val();
        //         let idval = ind - 1;
        //         fila_medicamento = fila_medicamentos[idval];

        //         //Validar que el anterior registro este rellenado
        //         let estadoVidaPuerpera = $(`#estado_vida_puerpera${ind - 1}`).val();
        //         let tipoDocumentoPuerpera = $(`#tipo_documento_puerpera${ind - 1}`).val();
        //         let numeroDocumentoPuerpera = $(`#numero_documento_puerpera${ind - 1}`).val();
        //         let sexoPuerpera = $(`#sexo_puerpera${ind - 1}`).val();
        //         let tallaPuerpera = $(`#talla_puerpera${ind - 1}`).val();
        //         let pesoPuerpera = $(`#peso_puerpera${ind - 1}`).val();
        //         if (estadoVidaPuerpera == "" ||
        //             tipoDocumentoPuerpera == "" ||
        //             numeroDocumentoPuerpera == "" ||
        //             sexoPuerpera == "" ||
        //             tallaPuerpera == "" ||
        //             pesoPuerpera == ""
        //         ) {
        //             bootbox.alert("Ingrese los datos anterior del neonato para poder agregar uno nuevo. por favor");
        //             return false;
        //         }
        //     }

        //     newRow += '<tr>';
        //     newRow +=
        //         `
        //     <td style="width: 13%; padding: 0px 0px 20px 0px">                                                                                          
        //         <label>ESTADO DE VIDA</label>
        //         <select class="form-control input-sm typeahead tt-query" name="estado_vida_puerpera[]" id="estado_vida_puerpera${ind}" style="width:100%">
        //             <option value=""> -Seleccionar-</option>
        //             <option value="VIVO">VIVO</option>
        //             <option value="MUERTO">MUERTO</option>
        //         </select>
        //     </td>
        //     <td style="width: 20%; padding: 0px 0px 20px 15px">                                                                                          
        //         <label>TIPO DE DOCUMENTO</label>
        //         <select class="tipo_doc form-control input-sm typeahead tt-query" name="tipo_documento_puerpera[]" id="tipo_documento_puerpera${ind}" style="width:100%">
        //             <option value=""> -Seleccionar-</option>
        //             ${optionsTipodocs}
        //         </select>
        //     </td>
        //     <td style="width: 20%; padding: 0px 0px 20px 15px">
        //         <label>NUM. DOCUMENTO</label>
        //         <input 
        //             type="text" 
        //             placeholder="Num. documento" 
        //             name="numero_documento_puerpera[]" 
        //             id="numero_documento_puerpera${ind}" 
        //             class="form-control input-sm typeahead tt-query" 
        //             maxlength="10"
        //             onkeypress="return /^[a-zA-Z0-9]+$/i.test(event.key)"
        //         >
        //     </td>
        //     <td style="width: 15%; padding: 0px 0px 20px 15px">                                                                                          
        //         <label>SEXO</label>
        //         <select class="form-control input-sm typeahead tt-query" name="sexo_puerpera[]" id="sexo_puerpera${ind}">
        //             <option value=""> -Seleccionar-</option>
        //             <option value="M">MASCULINO</option>
        //             <option value="F">FEMENINO</option>
        //         </select>
        //     </td>
        //     <td style="width: 7%; padding: 0px 0px 20px 15px">
        //         <label>TALLA(cm)</label>
        //         <input 
        //             type="text" 
        //             placeholder="0" 
        //             name="talla_puerpera[]" 
        //             id="talla_puerpera${ind}" 
        //             class="form-control input-sm typeahead tt-query" 
        //             maxlength="6"
        //             onkeypress="return /^[0-9.]+$/i.test(event.key)"
        //         >
        //     </td>
        //     <td style="width: 7%; padding: 0px 0px 20px 15px">
        //         <label>PESO(kg)</label>
        //         <input 
        //             type="text" 
        //             placeholder="0" 
        //             name="peso_puerpera[]" 
        //             id="peso_puerpera${ind}" 
        //             class="form-control input-sm typeahead tt-query" 
        //             maxlength="6"
        //             onkeypress="return /^[0-9.]+$/i.test(event.key)"
        //         >
        //     </td>
        //     <td style="width: 2%; padding: 0px 0px 20px 0px">
        //         <input 
        //             type="hidden" 
        //             class="form-control typeahead tt-query id_puerperio"
        //             id="id_parto_puerpera${ind}" 
        //             name="id_parto_puerpera[]" 
        //             data-id=${ind}
        //         >
        //     </td>
        //     <td style="width: 5%; padding: 10px 0px 10px 15px">
        //         <button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left: 2%;"><i class="fa fa-times"></i> Eliminar</button>
        //     </td>`;
        //     newRow += '<br /></tr>';
        //     $('#tblPuerpera tbody').append(newRow);
        //     handleTipoDoc();
        // }

        function handleTipoDoc() {
            const selects = document.querySelectorAll(".tipo_doc");
            selects.forEach((select) => {
                select.addEventListener('change', () => {
                    const inputTipoDoc = select.parentNode.nextElementSibling.childNodes[2]
                        .nextElementSibling;
                    console.log(inputTipoDoc)
                    const tipoDoc = select.options[select.selectedIndex].text;
                    inputTipoDoc.placeholder = tipoDoc;
                    if (tipoDoc == "DNI") {
                        inputTipoDoc.maxLength = 8;
                    } else if (tipoDoc == "-Seleccionar-") {
                        inputTipoDoc.placeholder = "Num. documento";
                    } else {
                        inputTipoDoc.maxLength = 12;
                    }
                });
            });
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
      // validaExamenSeleccionado($("#id_examen_lab" + ind).val());
      // consultaWsResultExamenSeleccionado($("#id_examen_lab" + ind).val());
      /*if (id_categoria_examen_lab == "9") {
        mostrarBotonParaTipoDeMuestraMicrobiologia(ind);
      }*/
      // SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS
      /*if (codigo_procedimiento == '87163') {
        mostrarBotonParaSolicitudCitoquimicosLiquidosBiologicos(ind);
      }*/
      //let id_consultorio = $('#id_servicio').val();
      //         GINECOLOGÍA      ||    ENDOCRINOLOGÍA      ||       PEDIATRÍA
      /*if ((id_consultorio == '77' || id_consultorio == '15' || id_consultorio == '49' ) && codigo_procedimiento == '82951') { 
        mostrarBotonParaSolicitudTestToleranciaGlucosaOral(ind);
      }*/

      //valida las examenes depeendidntes
      /*var seleccionados = $("input[name='cod_lab[]']").map(function () {
      return $(this).val();
      }).get();

      if (verificarFaltantes(seleccionados) != "") {
          alert(verificarFaltantes(seleccionados));
      }*/
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
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA INICIO =================
    /*console.log('ENTRO EN CONDICIÓN');
    console.log('INDICE : ' + ind);
    let flag_descripcion_tipo_muestra = $('#flag_descripcion_tipo_muestra' + (ind - 1)).val();
    if (flag_descripcion_tipo_muestra == 1) {
      let descripcion_tipo_muestra = $('#descripcion_tipo_muestra' + (ind - 1)).val();
      if (descripcion_tipo_muestra == "") {
        Swal.fire({
            icon: 'error',
            title: 'Debe indicar el tipo de muestra para microbilogía. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Tipo de muestra</button>',
            showConfirmButton: false,
            timer: 3000
        });
        return;
      }
    }*/
    //=== LABORATORIO VALIDA QUE EL TIPO DE MUESTRA DE MICROBILOGÍA ESTE CARGADA FIN =================
    //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================
    /*let descripcion_examen = $('#descripcion_examen' + (ind - 1)).val();
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
    }*/
    //=== VALIDA SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FIN ===============================
    //=== VALIDA SOLICITUD PARA TEST TOLERANCIA GLUCOSA INICIO ===============================
    /*let flag_solicitud_test_tolerancia_glucosa_oral = $('#flag_solicitud_test_tolerancia_glucosa_oral' + (ind - 1)).val();
    if (flag_solicitud_test_tolerancia_glucosa_oral == 1) {
      let flag_editado_solicitud_test_tolerancia_glucosa_oral = $('#flagEditadoSolicitudTestToleranciaGlucosaOral' + (ind - 1)).val();
      if (flag_editado_solicitud_test_tolerancia_glucosa_oral == '0') {
        Swal.fire({
            icon: 'error',
            title: 'Debe registrar la solicitud para test tolerancia glucosa. Por favor, presiona el botón <button type="button" class="btn btn-success btn-xs"><i class="fa fa-check-square"></i>Solicitud</button>',
            showConfirmButton: false,
            timer: 3000
        });
        return;
      }
    }*/

    // $('.id_laboratorio').each(function() {
    //   var ind_tmp = $(this).data("id");
    //   if (ind_tmp => ind) {
    //     ind = Number(ind_tmp) + 1;
    //   }
    // });
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
  newRow +='<input type="hidden" class="form-control typeahead tt-query examenRepetida" id="examenRepetida' + ind +'" name="examenRepetida[]" value="" data-id='+ind+'>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query id_categoria" id="id_categoria_examen_lab' + ind +'" name="id_categoria_examen_lab[]" value="" data-id='+ind+'></td>';

  if (id_nivel && parseInt(id_nivel) < 3) {
    newRow +='<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" id="examen_lab_observacion' +ind + '" name="examen_lab_observacion[]"></td>';
  }
  newRow +='<td><input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad typeahead tt-query" id="examen_lab_cantidad' +ind + '" name="examen_lab_cantidad[]" value="1"></td>';
  newRow += '<td><select class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"><option value="">-- Seleccionar --</option></select></td>';
  // ====================== Campo Tiempo ===============================================
  // if (id_nivel && parseInt(id_nivel) < 3) {
  //   newRow +='<td><input type="text" maxlength="2" placeholder="Tiempo" class="form-control examen_lab_tiempo typeahead tt-query" style="margin:0 20px;width:150px" id="examen_lab_tiempo' + ind + '" name="examen_lab_tiempo[]" value="1"></td>';
  // }
  // ====================== Campo Tiempo - End ===============================================
  // ====================== Tipo de Muestra Inicio ===============================================
  newRow +='<td>';
  /*newRow +='<button type="button" class="btn btn-success btn-xs tipoMuestraMicrobiologia" id="tipoMuestraMicrobiologia' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Tipo de muestra</button>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="descripcion_tipo_muestra' + ind +'" name="descripcion_tipo_muestra[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_descripcion_tipo_muestra' + ind +'" value="0" />';
  newRow +='<button type="button" class="btn btn-warning btn-xs editarTipoMuestraMicrobiologia" id="editarTipoMuestraMicrobiologia' + ind +'" data-id='+ind+' style="display:none; margin-left:4px"><i class="fa fa-edit">&nbsp;Editar Tipo de muestra</button>';*/
  newRow +='</td>';
      // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS INICIO ===============================================
  newRow +='<td>';
  /*newRow +='<button type="button" class="btn btn-success btn-xs solicitudCitoquimicosLiquidosBiologicos" id="solicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="descripcion_examen' + ind +'" name="descripcion_examen[]" value="" data-id='+ind+' />';
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
  newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudCitoquimicosLiquidosBiologicos" id="editarSolicitudCitoquimicosLiquidosBiologicos' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';*/
  newRow +='</td>';
    // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS FIN ===============================================
      // ====================== SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL  INICIO ===============================================
  newRow +='<td>';
  /*newRow +='<button type="button" class="btn btn-success btn-xs solicitudTestToleranciaGlucosaOral" id="solicitudTestToleranciaGlucosaOral' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-check-square"></i>&nbsp;Solicitud</button>';
  
  //SOLICITUD DE TOLERANCIA PARA PACIENTE PEDIÁTRICO:
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_pediatrico_ttgo' + ind +'" name="des_tolerancia_paciente_pediatrico_ttgo[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_fecha_tolerancia_paciente_pediatrico_ttgo' + ind +'" name="des_fecha_tolerancia_paciente_pediatrico_ttgo[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_pediatrico_glucosa_anhidra' + ind +'" name="des_tolerancia_paciente_pediatrico_glucosa_anhidra[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra' + ind +'" name="des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[]" value="" data-id='+ind+' />';
  //SOLICITUD DE TOLERANCIA PARA PACIENTE ADULTO
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_adulto_ttgo' + ind +'" name="des_tolerancia_paciente_adulto_ttgo[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_fecha_tolerancia_paciente_adulto_ttgo' + ind +'" name="des_fecha_tolerancia_paciente_adulto_ttgo[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_adulto_glucosa_anhidra' + ind +'" name="des_tolerancia_paciente_adulto_glucosa_anhidra[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra' + ind +'" name="des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[]" value="" data-id='+ind+' />';
  //SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_g' + ind +'" name="des_tolerancia_paciente_gestante_g[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_p_1' + ind +'" name="des_tolerancia_paciente_gestante_p_1[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_p_2' + ind +'" name="des_tolerancia_paciente_gestante_p_2[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_p_3' + ind +'" name="des_tolerancia_paciente_gestante_p_3[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_p_4' + ind +'" name="des_tolerancia_paciente_gestante_p_4[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_edad_gestacional_meses' + ind +'" name="des_tolerancia_paciente_gestante_edad_gestacional_meses[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_edad_gestacional_semanas' + ind +'" name="des_tolerancia_paciente_gestante_edad_gestacional_semanas[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_edad_gestacional_dias' + ind +'" name="des_tolerancia_paciente_gestante_edad_gestacional_dias[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_ttgo' + ind +'" name="des_tolerancia_paciente_gestante_ttgo[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_fecha_tolerancia_paciente_gestante_ttgo' + ind +'" name="des_fecha_tolerancia_paciente_gestante_ttgo[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_glucosa_anhidra' + ind +'" name="des_tolerancia_paciente_gestante_glucosa_anhidra[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra' + ind +'" name="des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_tolerancia_paciente_gestante_factores_riesgo' + ind +'" name="des_tolerancia_paciente_gestante_factores_riesgo[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="des_especifique_tolerancia_paciente_gestante_factores_riesgo' + ind +'" name="des_especifique_tolerancia_paciente_gestante_factores_riesgo[]" value="" data-id='+ind+' />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flag_solicitud_test_tolerancia_glucosa_oral' + ind +'"  name="flag_solicitud_test_tolerancia_glucosa_oral[]"  data-id='+ind+' value="0" />';
  newRow +='<input type="hidden" class="form-control typeahead tt-query" id="flagEditadoSolicitudTestToleranciaGlucosaOral' + ind +'"  name="flagEditadoSolicitudTestToleranciaGlucosaOral[]"  data-id='+ind+' value="0" />';
  newRow +='<button type="button" class="btn btn-warning btn-xs editarSolicitudTestToleranciaGlucosaOral" id="editarSolicitudTestToleranciaGlucosaOral' + ind +'" data-id='+ind+' style="display:none; margin-left:4px;"><i class="fa fa-edit"></i>&nbsp;Solicitud</button>';*/
  // ====================== SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL FIN ===============================================
  newRow +='</td>';
  // ====================== Tipo de Muestra Fin ===============================================
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
    option.value = item.id;
    option.textContent = item.nombre;
    selectElementLab.appendChild(option);
  });

  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  let id_servicio = $("#id_servicio").val();
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
        url: '../obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio,
        //url: '../obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio,
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
  $("#examen_lab_cantidad" + ind).on('blur', function() {
    var id_examen_lab = $("#id_examen_lab" + ind).val();
    if (id_examen_lab == "") {
      bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
      // $("#examen_lab_cantidad" + ind).val("");
      return false;
    }
  });
} 

const mostrarBotonParaSolicitudTestToleranciaGlucosaOral = (ind) => {
  $('#solicitudTestToleranciaGlucosaOral' + ind).show();
  $('#flag_solicitud_test_tolerancia_glucosa_oral' + ind).val('1');
  $('#descripcion_examen' + ind).val('TEST DE TOLERANCIA A LA GLUCOSA ORAL');
}

// ========== REGISTRAR SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL INICIO =========
$(document).on('click','.solicitudTestToleranciaGlucosaOral', function () {
  let i = $(this).data('id');
  $('#solicitud_test_tolerancia_glucosa_oral_data_id').val(i);
  let id_consultorio = $('#id_servicio').val();
  // PEDIATRIA 49
  if (id_consultorio == '49') {
    $('#tolerancia_paciente_pediatrico').show()
    $('#tolerancia_paciente_adulto').hide()
    $('#tolerancia_paciente_gestante').hide()  
  }
  // ENDOCRINOLOGIA 15
  if (id_consultorio == '15') {
    $('#tolerancia_paciente_pediatrico').show()
    $('#tolerancia_paciente_adulto').show()
    $('#tolerancia_paciente_gestante').show()  
  }
  // GINECOLOGÍA 77
  if (id_consultorio == '77') {
    $('#tolerancia_paciente_gestante').show()
    $('#tolerancia_paciente_pediatrico').hide()
    $('#tolerancia_paciente_adulto').hide()
  }

  $('#modalSolicitudTestToleranciaGlucosaOral').modal();
});

$(document).on('click', '#btnGuardarRegistroSolicitudTestToleranciaGlucosaOral', function(e) {
  e.preventDefault();
  validarRegistroSolicitudTestToleranciaGlucosaOral();
});

const validarRegistroSolicitudTestToleranciaGlucosaOral = () => {
  let msj = ''
  let id_consultorio = $('#id_servicio').val();
  // PEDIATRIA 49
  if (id_consultorio == '49') {
    //SOLICITUD DE TOLERANCIA PARA PACIENTE PEDIÁTRICO:
    let tolerancia_paciente_pediatrico_ttgo = $('#tolerancia_paciente_pediatrico_ttgo').attr("aria-pressed")
    if ( tolerancia_paciente_pediatrico_ttgo == 'true') {
      let fecha_tolerancia_paciente_pediatrico_ttgo = $('#fecha_tolerancia_paciente_pediatrico_ttgo').val()
      if (fecha_tolerancia_paciente_pediatrico_ttgo == '') {
        msj += 'Debe indicar la fecha de tolerancia para paciente pediátrico ttgo.<br>'
      }
    }
    let tolerancia_paciente_pediatrico_glucosa_anhidra = $('#tolerancia_paciente_pediatrico_glucosa_anhidra').attr("aria-pressed") 
    if ( tolerancia_paciente_pediatrico_glucosa_anhidra == 'true') {
      let cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra = $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val()
      if (cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra == '') {
        msj += 'Debe indicar la cantidad de tolerancia para paciente pediátrico glucosa anhidra.<br>'
      }
    }
  }
  // ENDOCRINOLOGIA 15
  if (id_consultorio == '15') {
    //SOLICITUD DE TOLERANCIA PARA PACIENTE PEDIÁTRICO:
    let tolerancia_paciente_pediatrico_ttgo = $('#tolerancia_paciente_pediatrico_ttgo').attr("aria-pressed")
    if ( tolerancia_paciente_pediatrico_ttgo == 'true') {
      let fecha_tolerancia_paciente_pediatrico_ttgo = $('#fecha_tolerancia_paciente_pediatrico_ttgo').val()
      if (fecha_tolerancia_paciente_pediatrico_ttgo == '') {
        msj += 'Debe indicar la fecha de tolerancia para paciente pediátrico ttgo.<br>'
      }
    }
    let tolerancia_paciente_pediatrico_glucosa_anhidra = $('#tolerancia_paciente_pediatrico_glucosa_anhidra').attr("aria-pressed") 
    if ( tolerancia_paciente_pediatrico_glucosa_anhidra == 'true') {
      let cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra = $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val()
      if (cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra == '') {
        msj += 'Debe indicar la cantidad de tolerancia para paciente pediátrico glucosa anhidra.<br>'
      }
    }
    //SOLICITUD DE TOLERANCIA PARA PACIENTE ADULTO
    let tolerancia_paciente_adulto_ttgo = $('#tolerancia_paciente_adulto_ttgo').attr("aria-pressed")
    if ( tolerancia_paciente_adulto_ttgo == 'true') {
      let fecha_tolerancia_paciente_adulto_ttgo = $('#fecha_tolerancia_paciente_adulto_ttgo').val()
      if (fecha_tolerancia_paciente_adulto_ttgo == '') {
        msj += 'Debe indicar la fecha de tolerancia para paciente adulto ttgo.<br>'
      }
    }
    
    let tolerancia_paciente_adulto_glucosa_anhidra = $('#tolerancia_paciente_adulto_glucosa_anhidra').attr("aria-pressed")
    if ( tolerancia_paciente_adulto_glucosa_anhidra == 'true') {
      let cantidad_tolerancia_paciente_adulto_glucosa_anhidra = $('#cantidad_tolerancia_paciente_adulto_glucosa_anhidra').val()
      if (cantidad_tolerancia_paciente_adulto_glucosa_anhidra == '') {
        msj += 'Debe indicar la cantidad de tolerancia para paciente adulto glucosa anhidra.<br>'
      }
    }

    //SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE
    let tolerancia_paciente_gestante_g = $('#tolerancia_paciente_gestante_g').val()
    if ( tolerancia_paciente_gestante_g == '') {
      msj += 'Debe indicar en el campo gravidez.<br>'
    }
    
    let tolerancia_paciente_gestante_p_1 = $('#tolerancia_paciente_gestante_p_1').val()
    if ( tolerancia_paciente_gestante_p_1 == '') {
      msj += 'Debe indicar en el primer campo de paridad.<br>'
    }
    
    let tolerancia_paciente_gestante_p_2 = $('#tolerancia_paciente_gestante_p_2').val()
    if ( tolerancia_paciente_gestante_p_2 == '') {
      msj += 'Debe indicar en el segundo campo de paridad.<br>'
    }
    
    let tolerancia_paciente_gestante_p_3 = $('#tolerancia_paciente_gestante_p_3').val()
    if ( tolerancia_paciente_gestante_p_3 == '') {
      msj += 'Debe indicar en el tercer campo de paridad.<br>'
    }
    
    let tolerancia_paciente_gestante_p_4 = $('#tolerancia_paciente_gestante_p_4').val()
    if ( tolerancia_paciente_gestante_p_4 == '') {
      msj += 'Debe indicar en el cuarto campo de paridad.<br>'
    }

    let tolerancia_paciente_gestante_edad_gestacional_meses = $('#tolerancia_paciente_gestante_edad_gestacional_meses').val()
    if ( tolerancia_paciente_gestante_edad_gestacional_meses == '') {
      msj += 'Debe indicar el número de meses de edad gestacional.<br>'
    }
    
    let tolerancia_paciente_gestante_edad_gestacional_semanas = $('#tolerancia_paciente_gestante_edad_gestacional_semanas').val()
    if ( tolerancia_paciente_gestante_edad_gestacional_semanas == '') {
      msj += 'Debe indicar el número de semanas de edad gestacional.<br>'
    }
    
    let tolerancia_paciente_gestante_edad_gestacional_dias = $('#tolerancia_paciente_gestante_edad_gestacional_dias').val()
    if ( tolerancia_paciente_gestante_edad_gestacional_dias == '') {
      msj += 'Debe indicar el número de días de edad gestacional.<br>'
    }

    let tolerancia_paciente_gestante_ttgo = $('#tolerancia_paciente_gestante_ttgo').attr("aria-pressed")
    if ( tolerancia_paciente_gestante_ttgo == 'true') {
      let fecha_tolerancia_paciente_gestante_ttgo = $('#fecha_tolerancia_paciente_gestante_ttgo').val()
      if (fecha_tolerancia_paciente_gestante_ttgo == '') {
        msj += 'Debe indicar la fecha de tolerancia para paciente gestante ttgo.<br>'
      }
    }

    let tolerancia_paciente_gestante_glucosa_anhidra = $('#tolerancia_paciente_gestante_glucosa_anhidra').attr("aria-pressed")
    if ( tolerancia_paciente_gestante_glucosa_anhidra == 'true') {
      let cantidad_tolerancia_paciente_gestante_glucosa_anhidra = $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val()
      if (cantidad_tolerancia_paciente_gestante_glucosa_anhidra == '') {
        msj += 'Debe indicar la cantidad de tolerancia para paciente gestante glucosa anhidra.<br>'
      }
    }

    let tolerancia_paciente_gestante_factores_riesgo = $('#tolerancia_paciente_gestante_factores_riesgo').attr("aria-pressed")
    if ( tolerancia_paciente_gestante_factores_riesgo == 'true') {
      let especifique_tolerancia_paciente_gestante_factores_riesgo = $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val()
      if (especifique_tolerancia_paciente_gestante_factores_riesgo == '') {
        msj += 'Debe especificar en la tolerancia para paciente gestante factores riesgo.<br>'
      }
    }
  }
  // GINECOLOGÍA 77
  if (id_consultorio == '77') {
    //SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE
    let tolerancia_paciente_gestante_g = $('#tolerancia_paciente_gestante_g').val()
    if ( tolerancia_paciente_gestante_g == '') {
      msj += 'Debe indicar en el campo gravidez.<br>'
    }
    
    let tolerancia_paciente_gestante_p_1 = $('#tolerancia_paciente_gestante_p_1').val()
    if ( tolerancia_paciente_gestante_p_1 == '') {
      msj += 'Debe indicar en el primer campo de paridad.<br>'
    }
    
    let tolerancia_paciente_gestante_p_2 = $('#tolerancia_paciente_gestante_p_2').val()
    if ( tolerancia_paciente_gestante_p_2 == '') {
      msj += 'Debe indicar en el segundo campo de paridad.<br>'
    }
    
    let tolerancia_paciente_gestante_p_3 = $('#tolerancia_paciente_gestante_p_3').val()
    if ( tolerancia_paciente_gestante_p_3 == '') {
      msj += 'Debe indicar en el tercer campo de paridad.<br>'
    }
    
    let tolerancia_paciente_gestante_p_4 = $('#tolerancia_paciente_gestante_p_4').val()
    if ( tolerancia_paciente_gestante_p_4 == '') {
      msj += 'Debe indicar en el cuarto campo de paridad.<br>'
    }

    let tolerancia_paciente_gestante_edad_gestacional_meses = $('#tolerancia_paciente_gestante_edad_gestacional_meses').val()
    if ( tolerancia_paciente_gestante_edad_gestacional_meses == '') {
      msj += 'Debe indicar el número de meses de edad gestacional.<br>'
    }
    
    let tolerancia_paciente_gestante_edad_gestacional_semanas = $('#tolerancia_paciente_gestante_edad_gestacional_semanas').val()
    if ( tolerancia_paciente_gestante_edad_gestacional_semanas == '') {
      msj += 'Debe indicar el número de semanas de edad gestacional.<br>'
    }
    
    let tolerancia_paciente_gestante_edad_gestacional_dias = $('#tolerancia_paciente_gestante_edad_gestacional_dias').val()
    if ( tolerancia_paciente_gestante_edad_gestacional_dias == '') {
      msj += 'Debe indicar el número de días de edad gestacional.<br>'
    }

    let tolerancia_paciente_gestante_ttgo = $('#tolerancia_paciente_gestante_ttgo').attr("aria-pressed")
    if ( tolerancia_paciente_gestante_ttgo == 'true') {
      let fecha_tolerancia_paciente_gestante_ttgo = $('#fecha_tolerancia_paciente_gestante_ttgo').val()
      if (fecha_tolerancia_paciente_gestante_ttgo == '') {
        msj += 'Debe indicar la fecha de tolerancia para paciente gestante ttgo.<br>'
      }
    }

    let tolerancia_paciente_gestante_glucosa_anhidra = $('#tolerancia_paciente_gestante_glucosa_anhidra').attr("aria-pressed")
    if ( tolerancia_paciente_gestante_glucosa_anhidra == 'true') {
      let cantidad_tolerancia_paciente_gestante_glucosa_anhidra = $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val()
      if (cantidad_tolerancia_paciente_gestante_glucosa_anhidra == '') {
        msj += 'Debe indicar la cantidad de tolerancia para paciente gestante glucosa anhidra.<br>'
      }
    }

    let tolerancia_paciente_gestante_factores_riesgo = $('#tolerancia_paciente_gestante_factores_riesgo').attr("aria-pressed")
    if ( tolerancia_paciente_gestante_factores_riesgo == 'true') {
      let especifique_tolerancia_paciente_gestante_factores_riesgo = $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val()
      if (especifique_tolerancia_paciente_gestante_factores_riesgo == '') {
        msj += 'Debe especificar en la tolerancia para paciente gestante factores riesgo.<br>'
      }
    }
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

  let i = $('#solicitud_test_tolerancia_glucosa_oral_data_id').val();

  // PEDIATRIA 49
  if (id_consultorio == '49') {
    //SOLICITUD DE TOLERANCIA PARA PACIENTE PEDIÁTRICO:
    $('#des_tolerancia_paciente_pediatrico_ttgo' + i).val($('#tolerancia_paciente_pediatrico_ttgo').attr("aria-pressed"))
    $('#des_fecha_tolerancia_paciente_pediatrico_ttgo' + i).val( $('#fecha_tolerancia_paciente_pediatrico_ttgo').val() )

    $('#des_tolerancia_paciente_pediatrico_glucosa_anhidra' + i).val($('#tolerancia_paciente_pediatrico_glucosa_anhidra').attr("aria-pressed"))
    $('#des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra' + i).val( $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val() )

    // Formatear los otros campos
  }
  // ENDOCRINOLOGIA 15
  if (id_consultorio == '15') {

    //SOLICITUD DE TOLERANCIA PARA PACIENTE PEDIÁTRICO:
    $('#des_tolerancia_paciente_pediatrico_ttgo' + i).val( $('#tolerancia_paciente_pediatrico_ttgo').attr("aria-pressed") )
    $('#des_fecha_tolerancia_paciente_pediatrico_ttgo' + i).val( $('#fecha_tolerancia_paciente_pediatrico_ttgo').val() )

    $('#des_tolerancia_paciente_pediatrico_glucosa_anhidra' + i).val($('#tolerancia_paciente_pediatrico_glucosa_anhidra').attr("aria-pressed"))
    $('#des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra' + i).val( $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val() )

    //SOLICITUD DE TOLERANCIA PARA PACIENTE ADULTO
    $('#des_tolerancia_paciente_adulto_ttgo' + i).val( $('#tolerancia_paciente_adulto_ttgo').attr("aria-pressed") )
    $('#des_fecha_tolerancia_paciente_adulto_ttgo' + i).val( $('#fecha_tolerancia_paciente_adulto_ttgo').val() )

    $('#des_tolerancia_paciente_adulto_glucosa_anhidra' + i).val( $('#tolerancia_paciente_adulto_glucosa_anhidra').attr("aria-pressed") )
    $('#des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra' + i).val( $('#cantidad_tolerancia_paciente_adulto_glucosa_anhidra').val() )

    //SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE
    $('#des_tolerancia_paciente_gestante_g' + i).val( $('#tolerancia_paciente_gestante_g').val() )
    $('#des_tolerancia_paciente_gestante_p_1' + i).val( $('#tolerancia_paciente_gestante_p_1').val() )
    $('#des_tolerancia_paciente_gestante_p_2' + i).val( $('#tolerancia_paciente_gestante_p_2').val() )
    $('#des_tolerancia_paciente_gestante_p_3' + i).val( $('#tolerancia_paciente_gestante_p_3').val() )
    $('#des_tolerancia_paciente_gestante_p_4' + i).val( $('#tolerancia_paciente_gestante_p_4').val() )

    $('#des_tolerancia_paciente_gestante_edad_gestacional_meses' + i).val( $('#tolerancia_paciente_gestante_edad_gestacional_meses').val() )
    $('#des_tolerancia_paciente_gestante_edad_gestacional_semanas' + i).val( $('#tolerancia_paciente_gestante_edad_gestacional_semanas').val() )
    $('#des_tolerancia_paciente_gestante_edad_gestacional_dias' + i).val( $('#tolerancia_paciente_gestante_edad_gestacional_dias').val() )

    $('#des_tolerancia_paciente_gestante_ttgo' + i).val( $('#tolerancia_paciente_gestante_ttgo').attr("aria-pressed") )
    $('#des_fecha_tolerancia_paciente_gestante_ttgo' + i).val( $('#fecha_tolerancia_paciente_gestante_ttgo').val() )

    $('#des_tolerancia_paciente_gestante_glucosa_anhidra' + i).val( $('#tolerancia_paciente_gestante_glucosa_anhidra').attr("aria-pressed") )
    $('#des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra' + i).val( $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val() )

    $('#des_tolerancia_paciente_gestante_factores_riesgo' + i).val( $('#tolerancia_paciente_gestante_factores_riesgo').attr("aria-pressed") )
    $('#des_especifique_tolerancia_paciente_gestante_factores_riesgo' + i).val( $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val() )
  }
  // GINECOLOGÍA 77
  if (id_consultorio == '77') {
    //SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE
    $('#des_tolerancia_paciente_gestante_g' + i).val( $('#tolerancia_paciente_gestante_g').val() )
    $('#des_tolerancia_paciente_gestante_p_1' + i).val( $('#tolerancia_paciente_gestante_p_1').val() )
    $('#des_tolerancia_paciente_gestante_p_2' + i).val( $('#tolerancia_paciente_gestante_p_2').val() )
    $('#des_tolerancia_paciente_gestante_p_3' + i).val( $('#tolerancia_paciente_gestante_p_3').val() )
    $('#des_tolerancia_paciente_gestante_p_4' + i).val( $('#tolerancia_paciente_gestante_p_4').val() )

    $('#des_tolerancia_paciente_gestante_edad_gestacional_meses' + i).val( $('#tolerancia_paciente_gestante_edad_gestacional_meses').val() )
    $('#des_tolerancia_paciente_gestante_edad_gestacional_semanas' + i).val( $('#tolerancia_paciente_gestante_edad_gestacional_semanas').val() )
    $('#des_tolerancia_paciente_gestante_edad_gestacional_dias' + i).val( $('#tolerancia_paciente_gestante_edad_gestacional_dias').val() )

    $('#des_tolerancia_paciente_gestante_ttgo' + i).val( $('#tolerancia_paciente_gestante_ttgo').attr("aria-pressed") )
    $('#des_fecha_tolerancia_paciente_gestante_ttgo' + i).val( $('#fecha_tolerancia_paciente_gestante_ttgo').val() )

    $('#des_tolerancia_paciente_gestante_glucosa_anhidra' + i).val( $('#tolerancia_paciente_gestante_glucosa_anhidra').attr("aria-pressed") )
    $('#des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra' + i).val( $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val() )

    $('#des_tolerancia_paciente_gestante_factores_riesgo' + i).val( $('#tolerancia_paciente_gestante_factores_riesgo').attr("aria-pressed") )
    $('#des_especifique_tolerancia_paciente_gestante_factores_riesgo' + i).val( $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val() )
  }

  $('#modalSolicitudTestToleranciaGlucosaOral').modal('hide');
  //========================== LIMPIAR CAMPOS ==========================
  formatearCamposModalSolicitudTestToleranciaGlucosaOral()
  //=========================================

  Swal.fire({
      icon: 'success',
      title: 'Se guardo con exito!',
      showConfirmButton: false,
      timer: 2000,
      onClose: function() {
        //console.log(`Se guardo el tipo de muestra en el input ${i}`);
        $('#solicitudTestToleranciaGlucosaOral' + i).hide();
        $('#editarSolicitudTestToleranciaGlucosaOral' + i).show();
        $('#flagEditadoSolicitudTestToleranciaGlucosaOral' + i).val(1);
      }
  });
}
const formatearCamposModalSolicitudTestToleranciaGlucosaOral = () => {
  
  $('#solicitud_test_tolerancia_glucosa_oral_data_id').val('')
  
  //SOLICITUD DE TOLERANCIA PARA PACIENTE PEDIÁTRICO:
  $('#tolerancia_paciente_pediatrico_ttgo').removeClass('active')
  $('#tolerancia_paciente_pediatrico_ttgo').attr("aria-pressed", "false")
  $('#fecha_tolerancia_paciente_pediatrico_ttgo').val('')
  $('#contenedor_tolerancia_paciente_pediatrico_ttgo').hide()
  
  $('#tolerancia_paciente_pediatrico_glucosa_anhidra').removeClass('active')
  $('#tolerancia_paciente_pediatrico_glucosa_anhidra').attr("aria-pressed", "false")
  $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val('')
  $('#contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra').hide()

  //SOLICITUD DE TOLERANCIA PARA PACIENTE ADULTO
  $('#tolerancia_paciente_adulto_ttgo').removeClass('active')
  $('#tolerancia_paciente_adulto_ttgo').attr("aria-pressed", "false")
  $('#fecha_tolerancia_paciente_adulto_ttgo').val('')
  $('#contenedor_tolerancia_paciente_adulto_ttgo').hide()
  
  $('#tolerancia_paciente_adulto_glucosa_anhidra').removeClass('active')
  $('#tolerancia_paciente_adulto_glucosa_anhidra').attr("aria-pressed", "false")
  $('#cantidad_tolerancia_paciente_adulto_glucosa_anhidra').val('')
  $('#contenedor_tolerancia_paciente_adulto_glucosa_anhidra').hide()

  //SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE
  $('#tolerancia_paciente_gestante_g').val('')
  $('#tolerancia_paciente_gestante_p_1').val('')
  $('#tolerancia_paciente_gestante_p_2').val('')
  $('#tolerancia_paciente_gestante_p_3').val('')
  $('#tolerancia_paciente_gestante_p_4').val('')

  
  $('#tolerancia_paciente_gestante_edad_gestacional_meses').val('')
  $('#tolerancia_paciente_gestante_edad_gestacional_semanas').val('')
  $('#tolerancia_paciente_gestante_edad_gestacional_dias').val('')

  
  $('#tolerancia_paciente_gestante_ttgo').removeClass('active')
  $('#tolerancia_paciente_gestante_ttgo').attr("aria-pressed", "false")
  $('#fecha_tolerancia_paciente_gestante_ttgo').val('')
  $('#contenedor_tolerancia_paciente_gestante_ttgo').hide()
  
  $('#tolerancia_paciente_gestante_glucosa_anhidra').removeClass('active')
  $('#tolerancia_paciente_gestante_glucosa_anhidra').attr("aria-pressed", "false")
  $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val('')
  $('#contenedor_tolerancia_paciente_gestante_glucosa_anhidra').hide()
  
  $('#tolerancia_paciente_gestante_factores_riesgo').removeClass('active')
  $('#tolerancia_paciente_gestante_factores_riesgo').attr("aria-pressed", "false")
  $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val('')
  $('#contenedor_tolerancia_paciente_gestante_factores_riesgo').hide()

}

$(document).ready(function() {
    $('#modalSolicitudTestToleranciaGlucosaOral').on('hidden.bs.modal', function() {
        // Limpiamos los campos de entrada al ocultar el modal
        formatearCamposModalSolicitudTestToleranciaGlucosaOral()
    });
});

$('#tolerancia_paciente_pediatrico_ttgo').on('click', function () {
  let tolerancia_paciente_pediatrico_ttgo = $('#tolerancia_paciente_pediatrico_ttgo').attr("aria-pressed");
  if (tolerancia_paciente_pediatrico_ttgo == 'false') {
    $('#contenedor_tolerancia_paciente_pediatrico_ttgo').show()
  } else {
    $('#fecha_tolerancia_paciente_pediatrico_ttgo').val('')
    $('#contenedor_tolerancia_paciente_pediatrico_ttgo').hide()
  }
});

$('#tolerancia_paciente_pediatrico_glucosa_anhidra').on('click', function () {
  let tolerancia_paciente_pediatrico_glucosa_anhidra = $('#tolerancia_paciente_pediatrico_glucosa_anhidra').attr("aria-pressed");
  if (tolerancia_paciente_pediatrico_glucosa_anhidra == 'false') {
    $('#contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra').show()
  } else {
    $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val('')
    $('#contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra').hide()
  }
});

//SOLICITUD DE TOLERANCIA PARA PACIENTE ADULTO
$('#tolerancia_paciente_adulto_ttgo').on('click', function () {
  let tolerancia_paciente_adulto_ttgo = $('#tolerancia_paciente_adulto_ttgo').attr("aria-pressed");
  if (tolerancia_paciente_adulto_ttgo == 'false') {
    $('#contenedor_tolerancia_paciente_adulto_ttgo').show()
  } else {
    $('#fecha_tolerancia_paciente_adulto_ttgo').val('')
    $('#contenedor_tolerancia_paciente_adulto_ttgo').hide()
  }
});

$('#tolerancia_paciente_adulto_glucosa_anhidra').on('click', function () {
  let tolerancia_paciente_adulto_glucosa_anhidra = $('#tolerancia_paciente_adulto_glucosa_anhidra').attr("aria-pressed");
  if (tolerancia_paciente_adulto_glucosa_anhidra == 'false') {
    $('#contenedor_tolerancia_paciente_adulto_glucosa_anhidra').show()
  } else {
    $('#cantidad_tolerancia_paciente_adulto_glucosa_anhidra').val('')
    $('#contenedor_tolerancia_paciente_adulto_glucosa_anhidra').hide()
  }
});

//SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE
$('#tolerancia_paciente_gestante_ttgo').on('click', function () {
  let tolerancia_paciente_gestante_ttgo = $('#tolerancia_paciente_gestante_ttgo').attr("aria-pressed");
  if (tolerancia_paciente_gestante_ttgo == 'false') {
    $('#contenedor_tolerancia_paciente_gestante_ttgo').show()
  } else {
    $('#fecha_tolerancia_paciente_gestante_ttgo').val('')
    $('#contenedor_tolerancia_paciente_gestante_ttgo').hide()
  }
});

$('#tolerancia_paciente_gestante_glucosa_anhidra').on('click', function () {
  let tolerancia_paciente_gestante_glucosa_anhidra = $('#tolerancia_paciente_gestante_glucosa_anhidra').attr("aria-pressed");
  if (tolerancia_paciente_gestante_glucosa_anhidra == 'false') {
    $('#contenedor_tolerancia_paciente_gestante_glucosa_anhidra').show()
  } else {
    $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val('')
    $('#contenedor_tolerancia_paciente_gestante_glucosa_anhidra').hide()
  }
});

$('#tolerancia_paciente_gestante_factores_riesgo').on('click', function () {
  let tolerancia_paciente_gestante_factores_riesgo = $('#tolerancia_paciente_gestante_factores_riesgo').attr("aria-pressed");
  if (tolerancia_paciente_gestante_factores_riesgo == 'false') {
    $('#contenedor_tolerancia_paciente_gestante_factores_riesgo').show()
  } else {
    $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val('')
    $('#contenedor_tolerancia_paciente_gestante_factores_riesgo').hide()
  }
});
  
// ========== REGISTRAR SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL FIN =========
// ========== EDITAR SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL INICIO =========
$(document).on('click','.editarSolicitudTestToleranciaGlucosaOral', function () {
  
  let i = $(this).data('id');
  $('#solicitud_test_tolerancia_glucosa_oral_data_id').val(i);  
  //------------------------------------------------

  //SOLICITUD DE TOLERANCIA PARA PACIENTE PEDIÁTRICO:
  if($('#des_tolerancia_paciente_pediatrico_ttgo' + i).val() == 'true'){
    $('#tolerancia_paciente_pediatrico_ttgo').attr("aria-pressed","true")
    $('#tolerancia_paciente_pediatrico_ttgo').addClass("active")
    $('#fecha_tolerancia_paciente_pediatrico_ttgo').val( $('#des_fecha_tolerancia_paciente_pediatrico_ttgo' + i).val() ) 
    $('#contenedor_tolerancia_paciente_pediatrico_ttgo').show()
  }
  
  if($('#des_tolerancia_paciente_pediatrico_glucosa_anhidra' + i).val() == 'true'){
    $('#tolerancia_paciente_pediatrico_glucosa_anhidra').attr("aria-pressed","true")
    $('#tolerancia_paciente_pediatrico_glucosa_anhidra').addClass("active")
    $('#cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra').val( $('#des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra' + i).val() ) 
    $('#contenedor_tolerancia_paciente_pediatrico_glucosa_anhidra').show()
  }
  
  //SOLICITUD DE TOLERANCIA PARA PACIENTE ADULTO
  
  if($('#des_tolerancia_paciente_adulto_ttgo' + i).val() == 'true'){
    $('#tolerancia_paciente_adulto_ttgo').attr("aria-pressed","true")
    $('#tolerancia_paciente_adulto_ttgo').addClass("active")
    $('#fecha_tolerancia_paciente_adulto_ttgo').val( $('#des_fecha_tolerancia_paciente_adulto_ttgo' + i).val() ) 
    $('#contenedor_tolerancia_paciente_adulto_ttgo').show()
  }
  
  if($('#des_tolerancia_paciente_adulto_glucosa_anhidra' + i).val() == 'true'){
    $('#tolerancia_paciente_adulto_glucosa_anhidra').attr("aria-pressed","true")
    $('#tolerancia_paciente_adulto_glucosa_anhidra').addClass("active")
    $('#cantidad_tolerancia_paciente_adulto_glucosa_anhidra').val( $('#des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra' + i).val() ) 
    $('#contenedor_tolerancia_paciente_adulto_glucosa_anhidra').show()
  }
  
  //SOLICITUD DE TOLERANCIA PARA PACIENTE GESTANTE
  $('#tolerancia_paciente_gestante_g').val( $('#des_tolerancia_paciente_gestante_g' + i).val() ) 
  $('#tolerancia_paciente_gestante_p_1').val( $('#des_tolerancia_paciente_gestante_p_1' + i).val() ) 
  $('#tolerancia_paciente_gestante_p_2').val( $('#des_tolerancia_paciente_gestante_p_2' + i).val() ) 
  $('#tolerancia_paciente_gestante_p_3').val( $('#des_tolerancia_paciente_gestante_p_3' + i).val() ) 
  $('#tolerancia_paciente_gestante_p_4').val( $('#des_tolerancia_paciente_gestante_p_4' + i).val() ) 
  
  $('#tolerancia_paciente_gestante_edad_gestacional_meses').val( $('#des_tolerancia_paciente_gestante_edad_gestacional_meses' + i).val() ) 
  $('#tolerancia_paciente_gestante_edad_gestacional_semanas').val( $('#des_tolerancia_paciente_gestante_edad_gestacional_semanas' + i).val() ) 
  $('#tolerancia_paciente_gestante_edad_gestacional_dias').val( $('#des_tolerancia_paciente_gestante_edad_gestacional_dias' + i).val() ) 
  
  if($('#des_tolerancia_paciente_gestante_ttgo' + i).val() == 'true'){
    $('#tolerancia_paciente_gestante_ttgo').attr("aria-pressed","true")
    $('#tolerancia_paciente_gestante_ttgo').addClass("active")
    $('#fecha_tolerancia_paciente_gestante_ttgo').val( $('#des_fecha_tolerancia_paciente_gestante_ttgo' + i).val() ) 
    $('#contenedor_tolerancia_paciente_gestante_ttgo').show()
  }
  
  if($('#des_tolerancia_paciente_gestante_glucosa_anhidra' + i).val() == 'true'){
    $('#tolerancia_paciente_gestante_glucosa_anhidra').attr("aria-pressed","true")
    $('#tolerancia_paciente_gestante_glucosa_anhidra').addClass("active")
    $('#cantidad_tolerancia_paciente_gestante_glucosa_anhidra').val( $('#des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra' + i).val() ) 
    $('#contenedor_tolerancia_paciente_gestante_glucosa_anhidra').show()
  }
  
  if($('#des_tolerancia_paciente_gestante_factores_riesgo' + i).val() == 'true'){
    $('#tolerancia_paciente_gestante_factores_riesgo').attr("aria-pressed","true")
    $('#tolerancia_paciente_gestante_factores_riesgo').addClass("active")
    $('#especifique_tolerancia_paciente_gestante_factores_riesgo').val( $('#des_especifique_tolerancia_paciente_gestante_factores_riesgo' + i).val() ) 
    $('#contenedor_tolerancia_paciente_gestante_factores_riesgo').show()
  }
  //------------------------------------------------
  
  $("#modalSolicitudTestToleranciaGlucosaOral").modal();
  
});
// ========== EDITAR SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL FIN =========

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


const mostrarBotonParaTipoDeMuestraMicrobiologia = (ind) => {
  $('#tipoMuestraMicrobiologia' + ind).show();
  $('#flag_descripcion_tipo_muestra' + ind).val('1');
  $('#solicitudExamenEspecializado' + ind).show();
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

            function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
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
  var nivel = $('#id_nivel').val();
  var sexo = $('#nomsexo').val();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  let id_servicio = $("#id_servicio").val();
  let id_servicio_rad = $("#id_servicio").val();
  if(nivel!=3)
     id_servicio_rad = 8;
  
    
  
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
        //url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 3,
        url: `../obtener_radiologia_convenio/${request.term}/${nivel}/${genero}/${id_tipo_atencion}/${id_servicio_rad}`,
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
  const selects = document.querySelectorAll('select[name="id_producto[]"]');
  const ultimoSelect = selects[selects.length - 1]; // Obtiene el último select
  const ultimoValor = ultimoSelect ? ultimoSelect.value : null; // Obtiene su valor si existe
  if (ultimoValor == "ru='undefined'") {
		bootbox.alert("Ingrese un medicamento antes de agregar otro.");
    return false;
  }
  var tipo_receta = $('#tipo_receta').val();
  var id_consultorio = $('#id_consultorio').val();
  var id_farmacia = $('#id_farmacia_receta').val();
  if (tipo_receta == "") {
    bootbox.alert("Seleccione el tipo atencion");
    return false;
  }
  if (id_consultorio == "") {
    bootbox.alert("Seleccione un servicio");
    return false;
  }
  var newRow = "";
  //var ind = $('#example tbody tr').length;
  var ind = filasmedicamentos
  var tabindex = 11;
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
  item_via_administracion+='<option value="9">Oftalmologica</option>';
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
  newRow +='<td><button type="button" class="btn btn-primary btn-xs btnAddProductoOfList" id="add_producto'+ind+'"><i class="fa fa-plus"></i> </button></td>';
  newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
  newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';

  newRow +='</tr>';
  $('#example tbody').append(newRow);
  $("#cmbMedicamento" + ind).select2({max_selected_options: 4}).focus();
	
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
      var id_farmacia = $('#id_farmacia_receta').val();
      validproductoseleccionado(id_farmacia,this);
      <?php //endif;?>
    }
  });


  $("#add_producto"+ind).click(function(e){
      AddProducto();
      var id_farmacia = $('#id_farmacia_receta').val();
      validproductoseleccionado(id_farmacia,this);
      btnAgregarProducto = $(this);
      $(btnAgregarProducto).attr('disabled', 'disabled');
	});
  

  $("#cmbMedicamento" + ind).on("change", function(e) {
    var flagx = 0;
    var cmb = $(this);
    var id_producto = $("#cmbMedicamento" + ind).val();
    var id_user = {{ Auth::user()->id }};

    $('.cmbMedicamento').each(function() {
      var ind_tmp = $(this).val();
      if ($(this).val() == id_producto) flagx++;
    });

    if (flagx > 1) {
      bootbox.alert("El producto farmaceutico ya ha sido ingresado");
      $("#cmbMedicamento" + ind).val("").trigger("change");
      return false;
    }
    var id_farmacia_receta = $('#id_farmacia_receta').val();
    //flaghospitalizacion = {{Auth::user()->flaghospitalizacion}};

    //if( flaghospitalizacion == 1 &&  id_farmacia_receta!=3){
    var option = {
      url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
      type: 'GET',
      dataType: 'json',
      data: {}
    };
    // 	}
    //	else{
    //		option = {
    //			url: '../cargarstock_farmacia/' + id_producto+ '/' + id_user,
    //			type: 'GET',
    //			dataType: 'json',
    //			data: {}
    //		};
    //	}

    $.ajax(option).done(function(data) {

      var cantidad = data.cantidad;
      var cantidadEstablecimiento = data.cantidadEstablecimiento;
      var cantidadAlmacen = data.cantidadAlmacen;
      if (!productoParam) {
      $(cmb).closest("tr").find(".limpia_text").val("");
    
      }
      //$(cmb).closest("tr").find("#nro_stocks").val(data);
      $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
      $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
      $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
      <?php //if(isset($flag_receta_dias) && $flag_receta_dias == 1):
        ?>
      $(cmb).closest("tr").find(".tiempo_periodo").focus();
      <?php //endif;
        ?>
      if (!productoParam) {
      $(cmb).closest("tr").find("#nro_med_solictados").val("");
      }
      $(cmb).closest("tr").find("#nro_med_entregados").val("");
      $(cmb).closest("tr").find("#lotes_lote").val("");
      $(cmb).closest("tr").find("#lotes_cantidad").val("");
      $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
      $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
    });

  });
  filasmedicamentos++;

  if (productoParam) {

      var $medicamentoCopiado = $("#cmbMedicamento" + ind);

      $medicamentoCopiado.val(productoParam.id_producto);

      if ($medicamentoCopiado.val() == productoParam.id_producto) {
          $medicamentoCopiado.trigger('change');
      }

      $("#tiempo_periodo" + ind)
          .val(productoParam.tiempo_periodo);

      $("#cmbTipoViaAdministracion" + ind)
          .val(productoParam.id_via_administracion);

      $("#posologia" + ind)
          .val(productoParam.posologia);

      $("#nro_med_solictados" + ind)
          .val(productoParam.cantidad_prescrita);
  }
}

        const selectServicio = document.querySelector('#id_servicio');
        function validarServicioOrSexoPaciente () {
          if ((document.querySelector('#nomsexo').value == 'F') && (selectServicio.value == '77' || selectServicio.value == '131') && (parseInt(document.querySelector('#edadpaciente').value) >= 12) && (parseInt(document.querySelector('#edadpaciente').value) <= 55)) {
            // $('#divPuerperio').show();
            $('#ocultar1').show();
          } else {
            // $('#divPuerperio').hide();
            $('#ocultar1').hide();
          }
        }
        $('#id_servicio').on('select2:select', function (e) {
          const id_servicio_value = e.target.value
          validarServicioOrSexoPaciente();
          const dniBeneficiario = $("#dni_beneficiario").val();
          if (dniBeneficiario && dniBeneficiario !== "") {
            obtenerUltimaGestacion(id_servicio_value); 
          }
          if ($("#edadpaciente").val() != '') {
            const res = atencionPermitida();
            analizarCondicionesCitaParaBloqueoDeFlujo(res, "PEDIATRIA", "La edad no corresponde a un paciente pediatrico.");
          }
        });    


function soloNumerosMenosCero(e) {
  var key = window.Event ? e.which : e.keyCode;
  //alert(key);
  return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}
function getProducto(farmaciaIdLastRecipe = null) {
  $('#example tbody').html('');

  if ($('#id_servicio').val() == "") return false;
  var id_ipress = $('#id_establecimiento').val();
  var id_consultorio = $('#id_servicio').val();
  var id_tiporeceta = $('#id_tipo_atencion').val();
  var id_farmacia = farmaciaIdLastRecipe != null
    ? farmaciaIdLastRecipe
    : $('#id_farmacia_receta').val();

  var nivel = $('#id_nivel').val();
  var x = 0;
  var x = nro_interconsulta();
  var dni = $('#dni_beneficiario').val();
  var interconsulta = "NO";
  
  if(id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia!=18 && id_farmacia!=25){
    if(id_tiporeceta==2){
      id_consultorio=76;
    }
    $.ajax({
      url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress+ '/' +id_farmacia+'/'+ id_consultorio,
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

  if((id_tiporeceta == 1 || id_tiporeceta == 5 || id_tiporeceta == 6) && id_farmacia == 21){ //nutricional
    if(id_tiporeceta==5 || id_tiporeceta == 1){
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
      }
    });
  }

  if(id_farmacia==18 || id_farmacia==25 || id_tiporeceta == 4){ //centro quirurgico
    if(id_tiporeceta == 1){
      if(id_farmacia == 25){
        $.ajax({
          url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
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
        if(id_consultorio == 72){
          $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                '/' + 72,
              success: function(result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function(ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                    .nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
              }
            });
        }
        else{
          $.ajax({
            url: '/obtenerProductosFarmaciasByNivel/'+id_farmacia+'/' + id_tiporeceta+'/'+id_consultorio,
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
    else
    {
      if(id_tiporeceta == 4){
        if(id_consultorio == 72){
          $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                '/' + 72,
              success: function(result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function(ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                    .nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
              }
            });
        }
        else{
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress+ '/' + id_consultorio,
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

var controlPrenatalInput = document.getElementById("control_prenatal");
/* controlPrenatalInput.addEventListener("input", function(event) {
  console.log(this.value);
  console.log("[event] =>", event.target.value);
    var value = this.value.trim();
    if (value !== "" && !/^(1[0-9]?|[1-9])$/.test(value)) {
        this.value = value.charAt(0);
    }
}); */

$(document).ready(function() {
  $("#control_prenatal").on("input", function(event) {
    var value = $(this).val().trim();
        var number = parseInt(value, 10);
        if (isNaN(number) || number < 1 || number > 20) {
            $(this).val(''); // Limpiar el campo si el número es inválido
        }
  });
});

/*controlPrenatalInput.addEventListener("keypress", function(event) {
    var key = event.key;

    // Permitir solo nÃºmeros y la tecla de retroceso (backspace)
    if (!/^(1[0-5]?|[1-9])$/.test(key) && key !== "Backspace") {
        event.preventDefault(); // Prevenir la entrada de otros caracteres
    }
});*/
function nro_interconsulta() {
  var dni_beneficiario = $('#dni_beneficiario').val();
  var id_farmacia = $('#id_farmacia').val();
  var x = 0;
  if (dni_beneficiario != "") {
    $.ajax({
      url: '/obtenerCantInterconsultas/' + dni_beneficiario + '/' + id_farmacia,
      success: function(result) {
        if (result != null && result != "") {
          for (var key in result) {
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
    if (id_tipo_atencion == 7) {
      var newOption = "";
      newOption = "<option value='0'>--Seleccionar-</option>";
      newOption = "<option value='230500'> 230500 - URGENCIA </option>";
      
      $('#codigo_upss').html(newOption);
      $("#codigo_upss").select2({
        max_selected_options: 4
      });
      
    }

  }
}

function getConsultorio(){
	var id_tipo_atencion = $('#id_tipo_atencion').val();
	var id_modalidad = $('#id_modalidad').val();
  var id_servicio = $('#id_servicio').val();
	var nivel     = $('#id_nivel').val();
	/*var valor, valor2=1;
	if(nivel==3){
		switch(id_tipo_atencion){
			case '1': if(id_servicio=="")valor=3;  $("#divSubCons").show();$("#divModalidad").show();break; //ambulatorio
			case '2': valor=19; $("#divAlta").show();$("#divIngreso").show();break; //emergencia
			case '3': valor=20; $("#divAlta").show();$("#divIngreso").show();break;//hospitalizacion
			case '4': valor=18; break; //cq
			case '5': valor=21; break; //nut
			case '6': valor=21; break; //nut
			case '8': valor=10; break; //oncoh			
		}
	}
	*/
  if (id_tipo_atencion == 1) {
    if (id_modalidad == 1) {
      $("#divSubCons").show();
      $("#divModalidad").show();
      /*$("#id_farmacia_receta").val(3);
      $("#id_farmacia_receta").select2({
        max_selected_options: 4
      });*/
    }
    else{
      $("#divSubCons").hide();
    }
  } else {
    if (id_modalidad == 1) {
      $("#divSubCons").hide();
      $("#divModalidad").hide();
      if (id_tipo_atencion == 2 || id_tipo_atencion == 3) {
        $("#divIngreso").show();
        $("#divAlta").show();
        if (id_tipo_atencion == 2) {
          /*$("#id_farmacia_receta").val(19);
          $("#id_farmacia_receta").select2({
            max_selected_options: 4
          });*/
        } else {
          /*$("#id_farmacia_receta").val(20);
          $("#id_farmacia_receta").select2({
            max_selected_options: 4
          });*/
        }
      } else {
        /*$("#id_farmacia_receta").val(3);
        $("#id_farmacia_receta").select2({
          max_selected_options: 4
        });*/
        $("#divIngreso").hide();
        $("#divAlta").hide();
      }
    }
  }
}

function getMedico(){
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");
            
            if($('#turno').val()=="") return false;
        
		var id_ipress 		= $('#id_establecimiento').val();
		var id_consultorio 	= $('#id_servicio').val();
		var turno 		= $('#turno').val();
		var fecha_admision 	= $('#fecha_atencion').val();
		
		$.ajax({
			url: '/obtener_medico_por_idipress_idconsultorio_turno/' + id_ipress+ '/' + id_consultorio+'/'+turno + '/' + fecha_admision,
			success: function(result){                    
				var newOption = "";
				newOption = "<option value=''>--Seleccionar-</option>";
				$(result).each(function (ii, oo) {
					newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
				});
				$('#id_medico').html(newOption);
				$("#id_medico").select2({max_selected_options: 4});
			
			}
		});
        
    }



function getUpss() {
  var id_consultorio = $('#id_servicio').val();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  var valor2=1;
  if(id_tipo_atencion == 5 ){
    id_tipo_atencion = 1; 
  }
  
  if(id_tipo_atencion != 7 ){
   
    $.ajax({
      url: '/obtener_upss/' +
        id_consultorio + '/' + id_tipo_atencion,
      success: function(result) {
        var newOption = "";
        var reg = result.length;
        if (reg >= 2) {
          newOption = "<option value='0'>--Seleccionar-</option>";
        }
        $(result).each(function(ii, oo) {
          newOption += "<option value='" + oo.codigo + "'>" + oo.codigo + ' - ' + oo.descripcion_upss + "</option>";
        });
        $('#codigo_upss').html(newOption);
        $("#codigo_upss").select2({
          max_selected_options: 4
        });
        if (id_tipo_atencion != 1) {
          $("#codigo_upss[value='" + valor2 + "']").attr("selected", false);
          $("#codigo_upss[value='" + valor2 + "']").attr("selected", true);
        }
      }
    });
  }
}

function getSubConsultorio() {
  var id_establecimiento = $('#id_establecimiento').val();
  var id_consultorio = $('#id_servicio').val();
  var fecha_atencion = $('#fecha_atencion').val();
  var id_tipo_atencion = $('#id_tipo_atencion').val();
  var id_medico = <?php echo $medico->id;?> ;
  var valor2;

  /*
  if(id_establecimiento==75 &&  id_consultorio == 27){ //cardiologia
    $("#VerSignosVitales").show();
  }
  else{
    $("#VerSignosVitales").hide();
  }
*/
  if(id_establecimiento==75 &&  (id_consultorio == 28 || id_consultorio == 106 || id_consultorio == 107)){
    $("#VerAspectosGenerales").show();
  }
  else{
    $("#VerAspectosGenerales").hide();
  }

  if(id_establecimiento==76 &&  id_consultorio == 13){
    $("#VerOftalmologia").show();
  }
  else{
    $("#VerOftalmologia").hide();
  }
  if(id_establecimiento==76 &&  id_consultorio == 102){
    $("#VerGastroenterologia").hide();
  }
  else{
    $("#VerGastroenterologia").hide();
  }
  
  $.ajax({
    //url: '/obtener_servicio_por_id_establecimiento_and_id_medico/' + id_establecimiento + '/' + id_medico + '/' + id_tipo_atencion,
    //url: '/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' + id_consultorio + '/' + id_tipo_atencion,
      url: '/obtener_subconsultorios_servicio_establecimiento_medico/' + id_establecimiento + '/' +
      id_medico + '/' + fecha_atencion + '/' + id_consultorio,
    success: function(result) {
      var newOption = "";
      var reg = result.length;
      if (reg >= 2) {
        newOption = "<option value='0'>--Seleccionar-</option>";
      }
      $(result).each(function(ii, oo) {
        if (id_tipo_atencion != 1)
          valor2 = oo.id;
        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
      });
      $('#id_sub_consultorio').html(newOption);
      $("#id_sub_consultorio").select2({
        max_selected_options: 4
      });
      if (id_tipo_atencion != 1) {
        $("#id_sub_consultorio option[value='" + valor2 + "']").attr("selected", false);
        $("#id_sub_consultorio option[value='" + valor2 + "']").attr("selected", true);
      }
      if (id_establecimiento == 76 && id_consultorio == 27) {
        $("#registrar_cita_medico").show();
      } else{
        $("#registrar_cita_medico").hide();
      }
    }
  });

  if (id_consultorio == 104) { //onc
    $("#id_farmacia_receta").val(10);
    $("#id_farmacia_receta").select2({
      max_selected_options: 4
    });
  }
}

function getProductoAndAddProducto() {
  getProducto();
  //AddProducto();
}

// TAGSITEDS
function generarCodigoSiteds() {
    Swal.fire ({
      title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      onBeforeOpen: () => {
        Swal.showLoading ()
      },
    })

    const isValidate = validateGenerateSiteds();
    if (isValidate) {
      Swal.hideLoading();
      return;
    }
    const tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    const nrodocafiliado = $("#nrodocafiliado").val();
    const id_tipo_atencion = $('#id_tipo_atencion').val();
    const id_servicio = $('#id_servicio').val();

    $("#btn_generate_siteds").prop("disabled", true);
    $("#codigo_siteds").prop("readonly", true);

    $("#codigo_siteds").val("");

    const fecha_atencion = $("#fecha_atencion").val();
   
    const establecimiento = {!! json_encode($establecimiento) !!}
    const codigo_ipress = establecimiento && establecimiento.codigo ? establecimiento.codigo : '00013591';
    const body = {
      "idRemitente": codigo_ipress || "00013591",
      "nuRucRemitente": "",
      "idCorrelativo": '00000001',
      "tiDoPaciente": tipodoc_beneficiario,
      "nuDoPaciente": nrodocafiliado,
      "noResponsableAut": "",
      "noPaResponsableAut": "",
      "noMaResponsableAut": "",
      "nuDoResponsableAut": "",
      "coAdmisionista": nrodocafiliado,
      "beMaxInicial": "0",
      "coTiMoneda": "1",
      "coPagoFijo": "0",
      "coCalServicio": "VS",
      "coPagoVariable": "100",
      "flagCaGarantia": "0",
      "deFlagCartaGarantia": "Sigesapol 0.1",
      "tipoAtencionId": id_tipo_atencion,
      "servicioId": id_servicio,
      "forceTempCodeGeneration": parseInt($("#statusResponseWsSusalud").val()) === 1 ? true : false,
      "fechaAtencion": fecha_atencion ? moment(fecha_atencion, "DD-MM-YYYY").format("YYYY-MM-DD") : moment().format("YYYY-MM-DD")
    }
    $.ajax({
      url:'/ws-dirsapol-siteds',
      type:'POST',
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(body),
      success: (result, textStatus, jqXHR) => {
        $("#codigo_siteds").val(result.nuAutorizacion);
        if (result.nuAutorizacion.endsWith("T") || String(result.nuAutorizacion).length <= 6) {
          Swal.fire({
            icon: "warning",
            title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>ATENCIÓN ADICIONAL</span></div>",
            html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Puede generar su código válido en la web oficial de SITEDS y reemplazar el código temporal en el sistema.</span></div>`,
            confirmButtonText: 'ENTENDIDO',
          });
          $("#codigo_siteds").prop("readonly", false);
        } else {
          Swal.fire({
            icon: (parseInt(jqXHR.status) === 201 || parseInt(jqXHR.status) === 200) ? "success" : 'warning',
            title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
            showConfirmButton: false,
            timer: '1500'
          });
        }
      },
      error: function(xhr, status, error){
        //$("#btn_generate_siteds").prop("disabled", false);
        const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
        // messageErroSiteds = messageError;
        // const codeError = xhr.responseJSON && xhr.responseJSON.code ? xhr.responseJSON.code : "9000";
        // if (String(codeError) !== "A001") {
        //   $("#codigo_siteds").val("");
        //   $("#codigo_siteds").prop("readonly", false);
        //   isValidSiteds = true;
        // }
        $("#codigo_siteds").prop("readonly", false);
        Swal.fire({
            icon: 'error',
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
            html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
            customClass: 'swal-wide',
        });
      }, 
      complete: function(xhr, status) {
        Swal.hideLoading();
      }
    })
  }

  function validateGenerateSiteds() {
    const tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    const nrodocafiliado = $("#nrodocafiliado").val();
    const id_tipo_atencion = $('#id_tipo_atencion').val();
    const id_servicio = $('#id_servicio').val();

    let messageValidate = "";

    if (!tipodoc_beneficiario || parseInt(tipodoc_beneficiario) <= 0) {
      messageValidate += `<p> - Es necesario seleccionar el TIPO DE DOCUMENTO DE IDENTIDAD.</p>`;
    }
    if (!nrodocafiliado || nrodocafiliado.length < 8) {
      messageValidate += `<p> - Es necesario ingresar el N ° DE DOCUMENTO DE IDENTIDAD.</p>`;
    }
    if (!id_tipo_atencion || parseInt(id_tipo_atencion) === 0) {
      messageValidate += `<p> - Es necesario seleccionar el TIPO DE ATENCIÓN.</p>`;
    } 
    if (!id_servicio || parseInt(id_servicio) === 0) {
      messageValidate += `<p> - Es necesario seleccionar el SERVICIO.</p>`;
    }
    if (messageValidate !== null && messageValidate.length > 0) {
      Swal.fire({
        icon: "error",
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
        html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 14px; text-align: left'>${messageValidate}</span></div>`,
        confirmButtonText: 'ENTENDIDO',
        customClass: 'swal-wide',
      });
      return true;
    }
    return false;
  }

  // PARA VALIDAR SI LA ATENCION COMO VA AVANZANDO, ES VALIDA PARA PEDIATRIA -- VALIDACION SERVICIO
  function atencionPermitida() {
    const servicio = $("#id_servicio").val();
    if (servicio == 49) {
      const edadPaciente = $("#edadpaciente").val();
      if (parseInt(edadPaciente) > 16) {
        console.log(false);
        return false;
      }
    } 
    console.log(true);
    return true;
  }

  function analizarCondicionesCitaParaBloqueoDeFlujo(esAtencionPermitida, servicio, razon) {
    const btnGuardar = document.querySelector("#btnPrestacion");
    btnGuardar.disabled = false;
    if (esAtencionPermitida === false) {
      Swal.fire({
        icon: 'error',
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error</span></div>",
        html: `<div style='margin: 10px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>La atención del paciente no está permitida para el servicio de ${servicio}.</span><br><p style="font-size: 13px;">Razon: ${razon}</p></div>`,
        confirmButtonText: 'ENTENDIDO',
      });
      btnGuardar.disabled = true;
    }
  }

  //===============================CENTRO QUIRURGICO
  $('#addInterQuirurgica').on('click', function () {
		let addInterQuirurgica = $('#addInterQuirurgica').attr("aria-pressed");
		$("#fsInterQuirurgica").hide();
    $("#has_intervencion_quirurgica").val("0");
		if(addInterQuirurgica == "false"){
			$("#fsInterQuirurgica").show();
      $("#has_intervencion_quirurgica").val("1");
      $("#fsBloqueInterconsulta").show();

      $("#section_perfil_preoperatorio").show();
      $("#id_perfil_preoperatorio").val("0");
		}else{
      $("#fsBloqueInterconsulta").hide();
      
      $("#section_perfil_preoperatorio").hide();
      $("#id_perfil_preoperatorio").val("0");

      $("#tblExamenLab tbody").empty();
    }
	});

  $('#addInterPreQuirurgica').on('click', function () {
		let addInterPreQuirurgica = $('#addInterPreQuirurgica').attr("aria-pressed");
		$("#fsInterPreQuirurgica").hide();
		if(addInterPreQuirurgica == "false"){
			$("#fsInterPreQuirurgica").show();
		}
	});

  $("#fecha_atencion").datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function(dateText) {
      const dateSelected = moment(dateText, 'DD-MM-YYYY').startOf('day');
      const currentDate = moment().startOf('day');
      $("#codigo_siteds").val("");
      $("#btn_generate_siteds").prop("disabled", false);
      if (dateSelected.isAfter(currentDate) || dateSelected.isBefore(currentDate)) {
        $("#input-siteds").removeClass('input-group');
        $("#codigo_siteds").prop('readonly', false);
        // $("#btn-siteds").hide();
        Swal.fire({
          icon: "warning",
          title: `<div style='color: #060606; margin: 10px 0px -10px 0px'><p style='font-size: 20px; color: #E4A11B'><b>!Aviso¡</b></p><p style='font-size: 16px;'>CÓDIGO SITEDS</p></div>`,
          html: `
                <div style='padding: 10px 30px 0px 30px;>
                  <span style='color: #000000; font-size: 18px;'>No olvides que la acreditación automática solo se realiza el día de la atención. <br>Si decide hacerla manual, será bajo su responsabilidad. </span> <p>¡Gracias por tu comprensión!</p>
                </div>`,
          confirmButtonText: 'ENTENDIDO',
        });
      } else {
        $("#input-siteds").addClass('input-group');
        $("#codigo_siteds").prop('readonly', true);
        generarCodigoSiteds();
        // $("#btn-siteds").show();
      }
    }
  });

  function validateFechaAtencionForSitedsAutomatic() {
    const fecha_atencion = $("#fecha_atencion").val();
    const fecha_atencion_moment = moment(fecha_atencion, 'DD-MM-YYYY').startOf('day');
    const currentDate = moment().startOf('day');
    enabledSitedsAutomatic = true
    if (fecha_atencion_moment.isAfter(currentDate) || fecha_atencion_moment.isBefore(currentDate)) {
      enabledSitedsAutomatic = false;
    }
    return enabledSitedsAutomatic;
  }

  function isProfesionalMedico(isServicioQuirurgico) {
    let display_panel_laboratorio_orden = true;

    const nivel = $("#id_nivel").val();
    const is_profesion_medico = String($("#is_profesion_medico").val());
    console.log("ahi viene el boleano de is profesion medico:");
    console.log(is_profesion_medico);

    if (parseInt(nivel) == 3) {
      // if (isServicioQuirurgico == "SI") {
        if (is_profesion_medico == "false") {
          display_panel_laboratorio_orden = false;
        }
      // } else {
      //   display_panel_laboratorio_orden = false;
      // }
    } else {
      if (is_profesion_medico == "false") {
        display_panel_laboratorio_orden = false;
      }
    }

    if (display_panel_laboratorio_orden) {
      $("#panelLaboratorioOrden").show();
    } else {
      $("#panelLaboratorioOrden").hide();
    }
  }

  function isServicioQuirurgico() {
    $("#fsIntervencionQuirugica").hide();
    let id_consultorio = $('#id_servicio').val();
    //De manera dinamica buscar si el id_servicio tiene el flag de quirurgico
    $.ajax({
        url: '/servicio-pertenece-cqx/' + id_consultorio,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.quirurgico === 'SI') {
                $("#fsIntervencionQuirugica").show();
               // $("#fsBloqueInterconsulta").show();
            } else {
                // Si NO es quirúrgico, limpiamos y ocultamos todo
                let tbody = document.getElementById("tblExamenInterPreQuirurgica").getElementsByTagName("tbody")[0];
                tbody.innerHTML = "";

                let toggleButton1 = document.getElementById('addInterQuirurgica');
                // let toggleButton2 = document.getElementById('addInterPreQuirurgica');

                toggleButton1.classList.remove('active');   
                toggleButton1.setAttribute('aria-pressed', 'false'); 
                $("#fsInterQuirurgica").hide();

                // toggleButton2.classList.remove('active');   
                // toggleButton2.setAttribute('aria-pressed', 'false'); 
                $("#fsIntervencionQuirugica").hide();
                //$("#fsBloqueInterconsulta").hide();
            }

            isProfesionalMedico(response.quirurgico);
        },
        error: function(xhr, status, error) {
            console.error('Error al consultar si el servicio es quirúrgico:', error);
        }
    });
    //SE HARA CAMBIOS
    // switch (parseInt(id_consultorio)) { // Especialidades Quirurgicas
    //   case 74: case 14: case 72: case 61: case 75: case 25: case 281: 
    //   case 13: case 16: case 68: case 43: case 77: case 119: case 126: case 136:
    //     $("#fsIntervencionQuirugica").show();
    //     break;
    //   default:
    //     let tbody = document.getElementById("tblExamenInterPreQuirurgica").getElementsByTagName("tbody")[0];
    //     tbody.innerHTML = "";

    //     let toggleButton1 = document.getElementById('addInterQuirurgica');
    //     let toggleButton2 = document.getElementById('addInterPreQuirurgica');

    //     // $("#has_requiere_internamiento_preqx").val("0");
    //     toggleButton1.classList.remove('active');   // Eliminar la clase activa
    //     toggleButton1.setAttribute('aria-pressed', 'false'); // Cambiar el atributo aria
    //     $("#fsInterQuirurgica").hide();

    //     toggleButton2.classList.remove('active');   // Eliminar la clase activa
    //     toggleButton2.setAttribute('aria-pressed', 'false'); // Cambiar el atributo aria
    //     $("#fsInterPreQuirurgica").hide();
    //   break;
    // }
  }

  let isAuthentication = false;
  
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

  function registrarAuditoriaHistorialAtencionNuevoCe(idTipoAtencion) {
    $.ajax({
      url: '/auditoria-hc/registrar',
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        origen: 'Atencion Adicional CE(HA)',
        doc_buscado: $('#dni_beneficiario').val(),
        id_tipo_atencion: idTipoAtencion
      }
    });
  }

  $(document).ready(function () {
    $('#tabs a').on('shown.bs.tab', function (e) {
      const targetTab = $(e.target).attr("href");
      if (targetTab === "#tab3default") {
        generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
        if (!isAuthentication) {
          const nombre_beneficiario = $("#nombre_beneficiario").val();
          if (nombre_beneficiario) {
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
                container: 'swal-confidencialidad-historial-new-atencion-backdrop'
              }
            });
            isAuthentication = true;
            modalHistorial();
            /*SE PUSO AQUI ESTOS METODOS PARA QUE VUELVA A BUSCAR EL HISTORIAL CON EL NUEVO DNI,YA QUE CUANDO SE BUSCABA OTRO PACIENTE EN EL HISTORIAL MANTENIA EL ANTERIOR */
            modalHistorial1();
            modalHistorial2();
            modalHistorialEvaluaciones();
            modalHistorial3();
            modalHistorialEvaluacionesHospitalizacion();
            /*SE PUSO AQUI ESTOS METODOS PARA QUE VUELVA A BUSCAR EL HISTORIAL CON EL NUEVO DNI,YA QUE CUANDO SE BUSCABA OTRO PACIENTE EN EL HISTORIAL MANTENIA EL ANTERIOR */
            // AMBULATORIO por defecto al entrar
            registrarAuditoriaHistorialAtencionNuevoCe(1);
          } else {
            $('#tabs a[href="#tab2default"]').tab('show');
            Swal.fire({
              icon: "warning",
              title: "ATENCIÓN ADICIONAL",
              html: `<p style="font-size: 16px; padding: 0px 20px">Para consultar el historial de atenciones, por favor, ingrese los datos del asegurado y proceda con la búsqueda.</p>`,
            });
          }
        }
      }
    });
    
    $('#tab3default .nav-tabs a').on('shown.bs.tab', function (e) {
      const targetSubTab = $(e.target).attr("href");
      if (targetSubTab === "#tab10default") {
        generar_marca_agua_historial('marca_agua_overlay_hist_ambulatorio');
        if (isAuthentication) registrarAuditoriaHistorialAtencionNuevoCe(1);
      }
      if (targetSubTab === "#tab11default") {
        generar_marca_agua_historial('marca_agua_overlay_hist_emergencia');
        registrarAuditoriaHistorialAtencionNuevoCe(2);
      }
      if (targetSubTab === "#tab12default") {
        generar_marca_agua_historial('marca_agua_overlay_hist_hospitalizacion');
        registrarAuditoriaHistorialAtencionNuevoCe(3);
      }
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
                var nivel = $('#id_nivel').val();
                var sexo = $('#nomsexo').val();
                var id_tipo_atencion = $('#id_tipo_atencion').val();
                if (sexo == 'M')
                  genero = 1;
                else
                  genero = 2;
                $.ajax({
                        url: '../obtener_laboratorio_convenio/' + faltante + '/' + nivel + '/' + genero + '/' + id_tipo_atencion,
                        //url: '../obtener_procedimiento/' + request.term,
                        dataType: "json",
                        success: function(data) {
                          if(data.length == 0){

                          }else{
                            AddExamenLab();
                            // var ind = $('#tblExamenLab tbody tr').length;
                            var ind = obtenerIndiceTblExamenLab();
                            var da = {
                              item:data[0]
                            };
                            console.log(da);
                            asignarValoresSelect(da,(ind-1));
                          }
                          
                        }
                      })
            }
        });

        return mensajes.length > 0 ? mensajes.join("\n") : "";
    }

    function alertVencimientoSeguro(fechaVigencia) {
      if (fechaVigencia) {
        formato = "YYYYMMDD";
        if (moment(fechaVigencia, "DD/MM/YYYY", true).isValid()) {
            formato = "DD/MM/YYYY";
        } else if (moment(fechaVigencia, "YYYYMMDD", true).isValid()) {
            formato = "YYYYMMDD";
        }
        const fechaVigenciaMoment = moment(fechaVigencia, formato, true).startOf('day');
        if (fechaVigenciaMoment) {
          const fechaActual = moment().startOf('day');
          const diferenciaDias = fechaVigenciaMoment.diff(fechaActual, "days");
          if (diferenciaDias > -1 && diferenciaDias <= 30) {
            let htmlString = `<span><i class="fa fa-exclamation-triangle"></i> <b>El seguro del paciente vencerá en ${diferenciaDias} días. Es importante informarle.</b></span>`;
            Swal.fire({
              icon: 'info',
              title: 'AVISO',
              html: `
                <div style="font-size:16px; line-height:1.6; text-align:center;">
                  <b>El seguro del paciente vencerá en ${diferenciaDias} ${diferenciaDias === 1 ? 'día' : 'días'}.</b><br>
                  Se recomienda acercarse a <strong>SALUDPOL</strong> para gestionar la renovación de su afiliación y evitar inconvenientes en futuras atenciones.
                </div>
              `,
              confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
              confirmButtonColor: '#3085d6',
              width: '38em',
              customClass: {
                popup: 'swal-afiliado_a_vencer',
                title: 'swal-title',
                htmlContainer: 'swal-content'
              }
            });
            if (diferenciaDias === 0) {
              htmlString = `<span><i class="fa fa-exclamation-triangle"></i> <b>El seguro del paciente vence hoy. Es importante informarle.</b></span>`; 
              Swal.fire({
                icon: 'info',
                title: 'AVISO',
                html: `
                  <div style="font-size:16px; line-height:1.6; text-align:center;">
                    <b>El seguro del paciente vence hoy.</b><br>
                    Se recomienda acercarse a <strong>SALUDPOL</strong> para gestionar la renovación de su afiliación y evitar inconvenientes en futuras atenciones.
                  </div>
                `,
                confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                confirmButtonColor: '#3085d6',
                width: '38em',
                customClass: {
                  popup: 'swal-afiliado_a_vencer',
                  title: 'swal-title',
                  htmlContainer: 'swal-content'
                }
              });
            }
            $("#alertVigenciaSeguro").show();
            $("#alertVigenciaSeguro").html(htmlString);
          } else {
            $("#alertVigenciaSeguro").hide();
          }
        } else {
          $("#alertVigenciaSeguro").hide();
        }
      }
    }

    function abrirComentariosLaboratorioRepetidos(id_examen_lab){
      Swal.fire({
              title: "Confirmación",
              html: `
                <div style="display: flex; flex-direction: column; text-align: center;padding:10px;">
                  <div style="margin-bottom: 10px;">
                    <b>Ingrese el motivo por que se agrega la prueba ya solicitada, Debe ingresar un comentario para continuar.</b>
                  </div>
                  <textarea id="comentarioLabRepetido" class="swal2-input" placeholder="Ingrese su comentario..."></textarea>
                </div>
              `,
              width: 700,
              showCancelButton: true,
              confirmButtonText: "Guardar",
              cancelButtonText: "Cancelar",
              allowOutsideClick: false,
              preConfirm: () => {
                const comentario = document.getElementById("comentarioLabRepetido").value;
                if (!comentario.trim()) {
                  Swal.showValidationMessage("Debe ingresar un comentario.");
                }
                return comentario;
              }
            }).then((result) => {
              console.log(id_examen_lab);
              if (result.isConfirmed) {
                console.log("Comentario ingresado:", result.value);
                $('input.id_laboratorio').each(function () {
                    if ($(this).val() === id_examen_lab) {
                        // Buscar el input correspondiente a examenRepetida en la misma fila y cambiar su valor
                        $(this).closest('td').find('.examenRepetida').val(result.value);
                    }
                });
                Swal.fire("Guardado", "La prueba ha sido guardada con éxito.", "success");
                consultaWsResultExamenSeleccionado(id_examen_lab);
              }else{
                          $('input.id_laboratorio').each(function () {
                              if ($(this).val() === id_examen_lab) {
                                  // Subir hasta la fila <tr> y eliminarla
                                  $(this).closest('tr').remove();
                              }
                          });
              }
            });
    }

  function validarPacienteConIntervencionQuirurgica() {
    // debugger;
    $("#divAlert").hide();
    $("#divAlert").empty();
    $("#id_intervencion_quirurgica").val(0);
    $("#fsFechaProbableIntervencionQuirurgica").hide();
    $("#has_intervencion_quirurgica_2").val("0");

    let tipo_doc_ident  = $("#tipodoc_beneficiario").val();
    let nro_doc_ident  = $("#dni_beneficiario").val();
    let id_establecimiento = $("#id_establecimiento").val();
    let id_servicio = $("#id_servicio").val();

    if (id_asegurado && id_establecimiento && id_servicio) {
      $.ajax({
        url: `/api/centro-quirurgico/intervencion/validar?tipo_doc_ident=${tipo_doc_ident}&nro_doc_ident=${nro_doc_ident}&id_establecimiento=${id_establecimiento}&id_servicio=${id_servicio}`,
        method: "GET",
        dataType: "json"
      })
      .done(function (response) {
        console.log("[response] =>", response);
        // if (response.data && response.data.cqx_cita && response.data.cqx_intervencion && response.data.cqx_cita.id_cqx_cita && response.data.cqx_intervencion.id) {
        if (response.data && response.data.cqx_intervencion && response.data.cqx_intervencion.id) {

          const fecha_solicitud = moment(response.data.cqx_intervencion.fecha_solicitud, 'YYYY-MM-DD HH:mm:ss').format("DD-MM-YYYY");
          $("#id_cqx_intervencion_pq").val(response.data.cqx_intervencion.id);

          if(response.data.cqx_intervencion.id){
             $("#fsBloqueInterconsulta").show();
          }else{
            $("#fsBloqueInterconsulta").hide();
          }

          // $("#id_cqx_cita_pq").val(response.data.cqx_cita.id_cqx_cita);
          $("#id_cqx_prestacion").val(response.data.cqx_intervencion.id_prestacion);
          $("#divAlert").show();
          $("#divAlert").html(`<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <span>El paciente cuenta con una solicitud de Intervencion Quirúrgica del <b>${fecha_solicitud}</b> en el servicio seleccionado aún sin culminar.</span>`);
          bootbox.confirm({
            message: "El paciente cuenta con una solicitud de Intervencion Quirúrgica del <b>" + fecha_solicitud + "</b> en el servicio seleccionado aún sin culminar.<br><b>¿La cita a registrar pertenece a dicha solicitud?</b>",
            buttons: {
              confirm: { label: 'SI PERTENECE', className: 'btn-success' },
              cancel: { label: 'NO PERTENECE', className: 'btn-danger' }
            },
            callback: function(userResponse) {
              if (userResponse) {
                const id_servicio = parseInt($("#id_servicio").val());
                if (id_servicio !== 87 && id_servicio !== 27) { // Si no es Neuro y Cardio se oculta
                  $("#fsIntervencionQuirugica").hide();
                }
                $("#fsProgramacionQuirugica").show();
                $("#fsFechaProbableIntervencionQuirurgica").show();
                $("#fsBloqueInterconsulta").show();
                $("#section_perfil_preoperatorio").show();
                $("#has_intervencion_quirurgica_2").val("1");
              }
            }
          });
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        // console.log("[jqXHR] =>", jqXHR);
        // console.log("[textStatus] =>", textStatus);
        // console.log("[errorThrown] =>", errorThrown);
      });
    }
    // if (id_servicio != '') {
    //   $.ajax({
    //     url: `/validar_paciente_con_intervencion_quirurgica/${tipodoc_beneficiario}/${nrodocafiliado}/${id_establecimiento}/${id_servicio}`,
    //     type: "GET",
    //     dataType: "json",
    //     success: function(result) {
    //       if (result != null && result.id > 0) {
    //         $("#id_cqx_intervencion_pq").val(result.id);
    //         $("#id_cqx_prestacion").val(result.id_prestacion);
    //         $("#divAlert").show();
    //         $("#divAlert").html(`<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <span>El paciente cuenta con una solicitud de Intervencion Quirúrgica del <b>${result.fecha_solicitud}</b> en el servicio seleccionado aún sin culminar.</span>`);
    //         bootbox.confirm({
    //           message: "El paciente cuenta con una solicitud de Intervencion Quirúrgica del <b>" + result.fecha_solicitud + "</b> en el servicio seleccionado aún sin culminar.<br><b>¿La cita a registrar pertenece a dicha solicitud?</b>",
    //           buttons: {
    //             confirm: { label: 'SI PERTENECE', className: 'btn-success' },
    //             cancel: { label: 'NO PERTENECE', className: 'btn-danger' }
    //           },
    //           callback: function(userResponse) {
    //             if (userResponse) {
    //               const id_servicio = parseInt($("#id_servicio").val());
    //               if (id_servicio !== 87 && id_servicio !== 27) {
    //                 $("#fsIntervencionQuirugica").hide();
    //               }
    //               $("#fsProgramacionQuirugica").show();
    //             }
    //           }
    //         });
    //       }
    //     }
    //   });
    // }
  }

  $("#btnAddRowTableProcedure_aa_iq").on("click", function () {
    onClickAddRowTableProcedimientosAaIq();
  });

  function onClickAddRowTableProcedimientosAaIq() {
    const lastRowValid = validateLastRowTableAaIq("tableProcedures_iq");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: '<p style="font-size: 15px">PROCEDIMIENTOS DE INTERVENCIÓN</p>',
        html: '<p class="descripcion-alert" style="font-size: 12px">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO PROCEDIMIENTO</p>', 
      });
      return null;
    }
    const indexRow = $('#tableProcedures_iq tbody tr').length + 1;

    const newRow = `
      <tr id="tr_procedimientos_iq_${indexRow}">
          <td><input type="text" name="procedimiento_nombre_iq[]" id="procedimiento_nombre_iq${indexRow}" class="form-control input-sm"></td>
          <td><button id="btnRemoveRowProcedure_iq" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="id_procedimiento_iq[]" id="id_procedimiento_iq${indexRow}"></td>
      </tr>`;

    $('#tableProcedures_iq tbody').append(newRow);
    $("#tableProceduresHeader_iq").removeClass("table-error danger").nextAll('.label-help').remove();

    const sexo = String($("#sexo").val()).toUpperCase() === "M" ? 1 : 2;
    const id_establecimiento = $("#id_establecimiento").val() || "76";

    $(`#procedimiento_nombre_iq${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function(request, response) {
        $.ajax({
          url: `/obtener_procedimiento_convenio/${request.term}/${id_establecimiento}/${sexo}/${1}`,
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
        let idItem = ui.item.id;
        $(this).val(ui.item.label);
        $(`#id_procedimiento_iq${indexRow}`).val(idItem);
      }
    });

    $(`#procedimiento_nombre_iq${indexRow}`).on('input', function() {
      $(`#id_procedimiento_iq${indexRow}`).val("");
    });

    $(`#procedimiento_nombre_iq${indexRow}`).blur(function() {
      const id_procedimiento = $(`#id_procedimiento_iq${indexRow}`).val();
      clearInputError($(this).attr("id"))
      if (!id_procedimiento) {
          markInputError($(this).attr("id"), "espere los resultados y seleccione un procedimiento");
      }
    });

    $('#tableProcedures_iq').on('click', '#btnRemoveRowProcedure_iq', function(){
      const row = $(this).closest('tr');
      const rowId = row.attr('id');
      row.remove();
    });
  }

  function validateLastRowTableAaIq(tableId) {
    let lastRow = $(`#${tableId} tbody tr:last`);
    let isValid = true;
    lastRow.find('input:visible').each(function() {
      clearInputError($(this).attr("id"));
      if ($(this).val().trim() === '') {
        markInputError($(this).attr("id"));
        isValid = false;
      }
    });
    lastRow.find('select:visible').each(function() {
      clearInputError($(this).attr("id"));
      if ($(this).val() === '') {
          markInputError($(this).attr("id"));
          isValid = false;
      }
    });
    return isValid; 
  }

function modalHistorial1(){ // Historial Atenciones Medicas - Ambulatorio
	var dni_beneficiario = $("#dni_beneficiario").val();
	$.ajax({
			url: "../modal_historial_atenciones_1/"+dni_beneficiario,
			type: "GET",
			success: function (result) {  
					$("#divHistorial1").html(result);
					$('#tablemodalm1a').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
						"sPaginationType": "full_numbers",
						
						"language": {"url": "/js/Spanish.json"},
					});
			}
	});
}

function modalHistorial2(){ // Historial Atenciones Medicas - Emergencia
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
			}
	});
}

function modalHistorialEvaluaciones(){ // Historial Evaluaciones Medicas - Emergencia
  var dni_beneficiario = $("#dni_beneficiario").val();
  var id_tipo_atencion = $("#id_tipo_atencion").val();
  $.ajax({
      url: `/modal_historial_evaluaciones_emergencia_v2/${dni_beneficiario}/2`,
      type: "GET",
      success: function (result) {  
          $("#divEvaluacione").html(result);

          $('#tablemodalm1004').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}

function modalHistorial3(){ // Historial Atenciones Medicas - Hospitalizacion
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
			}
	});
}

function modalHistorialEvaluacionesHospitalizacion(){
  
  var dni_beneficiario = $("#dni_beneficiario").val();
  var id_tipo_atencion = $("#id_tipo_atencion").val();
  $.ajax({
      url: `/modal_historial_evaluaciones_emergencia_v2/${dni_beneficiario}/3`,
      type: "GET",
      success: function (result) {  
          $("#divEvaluacioneHospitalizacion").html(result);

          $('#tablemodalm4').DataTable({
            "paging": true,  // Activar la paginación
            "pageLength": 10,  // Número de registros por página
            "sPaginationType": "full_numbers",
            
            "language": {"url": "/js/Spanish.json"},
          });
      }
  });
}

  function crearHiddenFields(nombres, ind) {
    return nombres.map(name =>
      `<input type="hidden" class="form-control" id="${name}${ind}" name="${name}[]" data-id="${ind}" value="">`
    ).join('\n');
  }

  const camposTipoMuestraMicrobiologia = [
    "descripcion_tipo_muestra"
  ];

  const camposSolicitudEstudioCitoquimicoLiquido = [
    "des_antecedentes_patologicos_personales",
    "des_antecedentes_patologicos_familiares",
    "des_liquido_cefaloraquideo",
    "des_liquido_pleural",
    "des_liquido_ascitico",
    "des_liquido_sinovial",
    "des_liquido_otro",
    "des_liquido_otro_especifique",
    "des_num_frasco_muestra_opcion",
    "flag_solicitud_citoquimicos_liquidos_biologicos"
  ];

  const camposSolicitudTestToleranciaGlucosaOral = [
    "des_tolerancia_paciente_pediatrico_ttgo",
    "des_fecha_tolerancia_paciente_pediatrico_ttgo",
    "des_tolerancia_paciente_pediatrico_glucosa_anhidra",
    "des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra",
    "des_tolerancia_paciente_adulto_ttgo",
    "des_fecha_tolerancia_paciente_adulto_ttgo",
    "des_tolerancia_paciente_adulto_glucosa_anhidra",
    "des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra",
    "des_tolerancia_paciente_gestante_g",
    "des_tolerancia_paciente_gestante_p_1",
    "des_tolerancia_paciente_gestante_p_2",
    "des_tolerancia_paciente_gestante_p_3",
    "des_tolerancia_paciente_gestante_p_4",
    "des_tolerancia_paciente_gestante_edad_gestacional_meses",
    "des_tolerancia_paciente_gestante_edad_gestacional_semanas",
    "des_tolerancia_paciente_gestante_edad_gestacional_dias",
    "des_tolerancia_paciente_gestante_ttgo",
    "des_fecha_tolerancia_paciente_gestante_ttgo",
    "des_tolerancia_paciente_gestante_glucosa_anhidra",
    "des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra",
    "des_tolerancia_paciente_gestante_factores_riesgo",
    "des_especifique_tolerancia_paciente_gestante_factores_riesgo",
    "flag_solicitud_test_tolerancia_glucosa_oral"
  ];

  document.querySelector('#id_perfil_preoperatorio').addEventListener('change', function() {
    // console.log(this.value);        // valor seleccionado
    // console.log(this.selectedIndex); // índice del item
    // console.log(this.options[this.selectedIndex].text); // texto visible
    
    $("#tblExamenLab tbody").empty();

    let id_perfil_preoperatorio = this.value;

    if (parseInt(id_perfil_preoperatorio) > 0) {
      let nivel = $('#id_nivel').val();
      let sexo = $('#nomsexo').val();
      let id_tipo_atencion = $('#id_tipo_atencion').val();
      let id_servicio = 26; // CENTRO QUIRURGICO

      let genero = 0;
      if (sexo == 'M') {
        genero = 1;
      } else if (sexo == 'F') {
        genero = 2;
      }

      $.ajax({
        url: '../obtener_laboratorio_convenio/all/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio + '/' + id_perfil_preoperatorio,
        dataType: "json",
        success: function(data) {
          console.log(data);
          // let listaLaboratorioSelecionado = $(".id_laboratorio").map(function(){
          //   return parseInt($(this).val());
          // }).get();
          // let listaExamenLabFiltrada = data.filter((element) => !listaLaboratorioSelecionado.includes(element.id));

          if (data && Array.isArray(data)) {
            data.forEach((item, index) => {
              // const ind = $('#tblExamenLab tbody tr').length + 1;
              const ind = obtenerIndiceTblExamenLab();

              // Crear una nueva fila para cada examen
              const newRow = `
                <tr>
                  <td>
                    <input type="text" name="nombre_examen_lab[]" id="nombre_examen_lab${ind}" class="input-sm" style="text-align: left; width: 100%;" value="${item.label || ''}" readonly />
                    <input type="hidden" name="id_examen_lab[]" id="id_examen_lab${ind}" value="${item.id}" class="id_laboratorio" data-id="${ind}" />
                    <input type="hidden" name="cod_lab[]" id="cod_lab${ind}" value="${item.codigo_procedimiento || ''}" />
                    <input type="hidden" class="examenRepetida" id="examenRepetida${ind}" name="examenRepetida[]" value="" data-id="${ind}" />
                    <input type="hidden" name="id_categoria_examen_lab[]" id="id_categoria_examen_lab${ind}" value="${item.id_categoria || ''}" />
                  </td>
                  <td>
                    <input type="number" name="examen_lab_cantidad[]" id="examen_lab_cantidad${ind}" class="form-control" value="1" readonly />
                  </td>
                  <td>
                    <select name="miSelectLab[]" id="miSelectLab${ind}" class="form-control select-diagnostico">
                      <option value="">-- Seleccionar --</option>
                    </select>
                  </td>

                  <td>
                    <button type="button" class="btn btn-success btn-xs tipoMuestraMicrobiologia" id="tipoMuestraMicrobiologia${ind}" data-id="${ind}" style="display:none; margin-left:4px;">
                      <i class="fa fa-check-square"></i> Tipo de muestra
                    </button>
                    ${crearHiddenFields(camposTipoMuestraMicrobiologia, ind)}
                    <input type="hidden" class="form-control" id="flag_descripcion_tipo_muestra${ind}" value="0" />
                    <button type="button" class="btn btn-warning btn-xs editarTipoMuestraMicrobiologia" id="editarTipoMuestraMicrobiologia${ind}" data-id="${ind}" style="display:none; margin-left:4px">
                      <i class="fa fa-edit"></i> Editar Tipo de muestra
                    </button>
                  </td>
                  <td>
                    <button type="button" class="btn btn-success btn-xs solicitudCitoquimicosLiquidosBiologicos" id="solicitudCitoquimicosLiquidosBiologicos${ind}" data-id="${ind}" style="display:none; margin-left:4px;">
                      <i class="fa fa-check-square"></i> Solicitud
                    </button>
                    ${crearHiddenFields(camposSolicitudEstudioCitoquimicoLiquido, ind)}
                    <button type="button" class="btn btn-warning btn-xs editarSolicitudCitoquimicosLiquidosBiologicos" id="editarSolicitudCitoquimicosLiquidosBiologicos${ind}" data-id="${ind}" style="display:none; margin-left:4px;">
                      <i class="fa fa-edit"></i> Solicitud
                    </button>
                  </td>
                  <td>
                    <button type="button" class="btn btn-success btn-xs solicitudTestToleranciaGlucosaOral" id="solicitudTestToleranciaGlucosaOral${ind}" data-id="${ind}" style="display:none; margin-left:4px;">
                      <i class="fa fa-check-square"></i> Solicitud
                    </button>
                    ${crearHiddenFields(camposSolicitudTestToleranciaGlucosaOral, ind)}
                    <button type="button" class="btn btn-warning btn-xs editarSolicitudTestToleranciaGlucosaOral" id="editarSolicitudTestToleranciaGlucosaOral${ind}" data-id="${ind}" style="display:none; margin-left:4px;">
                      <i class="fa fa-edit"></i> Solicitud
                    </button>
                  </td>

                  <td>
                    <button type="button" class="btn btn-danger btn-xs deleteFila" style="margin-left: 4px">
                      <i class="fa fa-times"></i> Eliminar
                    </button>
                  </td>
                </tr>
              `;

              // Agregar la nueva fila a la tabla
              $('#tblExamenLab tbody').append(newRow);

              // Rellenar el select con los diagnósticos disponibles
              if (Array.isArray(listIdDiagnosticoObjetos)) {
                const select = document.getElementById(`miSelectLab${ind}`);
                listIdDiagnosticoObjetos.forEach(diagnostico => {
                  const option = document.createElement('option');
                  option.value = diagnostico.id;
                  option.textContent = diagnostico.nombre;
                  select.appendChild(option);
                });
              }

              if (item.id) {
                // validaExamenSeleccionado(item.id);
                // consultaWsResultExamenSeleccionado(item.id);
              }
            });
          } else {
              console.error('No se recibieron datos válidos de la ruta obtener_laboratorio_convenio.');
          }
        },
        error: function (xhr, status, error) {
          console.error('Error al obtener los exámenes:', error);
        }

      });
    }
  });

  // RUA Laboratorio Checklist
  function cargarDiagnosticosTodos() {
    $("#contenedorDX").html("Cargando...");
    const nivel = $('#id_nivel').val();
    const sexo = $('#nomsexo').val();
    const id_tipo_atencion = $('#id_tipo_atencion').val();
    const id_servicio = $('#id_servicio').val();
    const genero = (sexo == 'M') ? 1 : 2;

    $.ajax({
      url: '/obtener_laboratorio_convenio/all/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio,
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
          /*"82803": "36600",
          "36600": "82803",
          "82575": "82565",
          "82565": "82575",
          "82043.01": "84999.09",
          "84999.09": "82043.01",
          "89190": "87999",
          "87999": "89190",
          "87070": "87184",
          "87184": "87070",
          "87163": "87184",
          "87184": "87163",
          "87040.01": "87184",
          "87184": "87040.01",
          "87040.03": "87184",
          "87184": "87040.03",
          "87045": "87184",
          "87184": "87045",
          "87115": "84999.13",
          "84999.13": "87115"*/
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

  $('#modalDX').on('show.bs.modal', function () {
    cargarDiagnosticosTodos();
  });

  $("#guardarRUALab").on('click', function () {

    const checked = $("input[name='chk_rualab[]']:checked");
    if (checked.length === 0) {
      $("#modalDX").modal('hide');
      return;
    }

    checked.each(function () {
      const $this = $(this);
      const label = $this.data('label');
      const id_examen_lab = $this.data('id');
      const codigo = $this.data('codigo');
      const categoria = $this.data('categoria');

      if ($("#tblExamenLab tbody tr[data-id='" + id_examen_lab + "']").length > 0) {
        return true; 
      }

      const ind = obtenerIndiceTblExamenLab();

      const newRow = `
        <tr>
          <td>
            <input type="text" name="nombre_examen_lab[]" id="nombre_examen_lab${ind}" class="input-sm" style="text-align: left; width: 100%;" value="${label}" readonly />
            <input type="hidden" name="id_examen_lab[]" id="id_examen_lab${ind}" value="${id_examen_lab}" class="id_laboratorio" data-id="${ind}" />
            <input type="hidden" name="cod_lab[]" id="cod_lab${ind}" value="${codigo}" />
            <input type="hidden" class="examenRepetida" id="examenRepetida${ind}" name="examenRepetida[]" value="" data-id="${ind}" />
            <input type="hidden" name="id_categoria_examen_lab[]" id="id_categoria_examen_lab${ind}" value="${categoria}" />
          </td>
          <td>
            <input type="number" name="examen_lab_cantidad[]" id="examen_lab_cantidad${ind}" class="form-control" value="1" readonly />
          </td>
          <td>
            <select name="miSelectLab[]" id="miSelectLab${ind}" class="form-control select-diagnostico">
              <option value="">-- Seleccionar --</option>
            </select>
          </td>

          <td>
          </td>
          <td>
          </td>
          <td>
          </td>

          <td>
            <button type="button" class="btn btn-danger btn-xs deleteFila" style="margin-left: 4px">
              <i class="fa fa-times"></i> Eliminar
            </button>
          </td>
        </tr>
      `;

      // Agregar la nueva fila a la tabla
      $("#tblExamenLab tbody").append(newRow);

      const select = document.getElementById(`miSelectLab${ind}`);
      if (Array.isArray(listIdDiagnosticoObjetos) && select) {
        listIdDiagnosticoObjetos.forEach(diagnostico => {
          const option = document.createElement('option');
          option.value = diagnostico.id;
          option.textContent = diagnostico.nombre;
          select.appendChild(option);
        });
      }

      if (id_examen_lab) {
        // validaExamenSeleccionado(id_examen_lab);
        // consultaWsResultExamenSeleccionado(id_examen_lab);
      }

    });

    $("#modalDX").modal('hide');
    $("input[name='chk_rualab[]']").prop('checked', false);
  });

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
  .swal-wide{
    width:500px !important;
  }

  .swal2-icon.swal2-warning {
    font-size: 15px;
  }

  input[type=checkbox]
  {
    /* Doble-tamaño Checkboxes */
    -ms-transform: scale(2); /* IE */
    -moz-transform: scale(2); /* FF */
    -webkit-transform: scale(2); /* Safari y Chrome */
    -o-transform: scale(2); /* Opera */
    padding: 10px;
  }

  .label-error {
    color: #d9534f; 
    font-size: 11px; 
    font-weight: bold
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

  .swal-confidencialidad-interconsulta-adic-backdrop.swal2-backdrop-show {
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      background: rgba(0, 0, 0, 0.4) !important;
  }

  .swal-confidencialidad-hist-pp-backdrop.swal2-backdrop-show {
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
@include('frontend.atenciones.modal_atencion_escala')
  @include('frontend.atenciones.ficha_psicologia')
<?php $grados = array("NINGUNO", "ALUMNO", "CADETE", "ALFÉREZ DE SERVICIO", "ALFÉREZ MAESTRO ARMERO", "CAPITÁN", "CAPITÁN DE SERVICIO", "CAPITÁN MAESTRO ARMERO", "COMANDANTE", "COMANDANTE DE SERVICIO", "COMANDANTE MAESTRO ARMERO", "CORONEL", "CORONEL DE SERVICIO", "ESPECIALISTA BRIGADIER", "ESPECIALISTA DE PRIMERA", "ESPECIALISTA DE SEGUNDA", "ESPECIALISTA DE TERCERA", "ESPECIALISTA SUPERIOR", "ESPECIALISTA TÃ‰CNICO DE 1RA", "ESPECIALISTA TÃ‰CNICO DE 2DA", "ESPECIALISTA TÃ‰CNICO DE 3RA", "GENERAL", "GENERAL DE SERVICIO", "MAYOR", "MAYOR DE SERVICIO", "MAYOR MAESTRO ARMERO", "SUBOFICIAL BRIGADIER", "SUBOFICIAL BRIGADIER DE SERVICIO", "SUBOFICIAL DE PRIMERA", "SUBOFICIAL DE PRIMERA DE SERVICIO", "SUBOFICIAL DE SEGUNDA", "SUBOFICIAL DE SEGUNDA DE SERVICIO", "SUBOFICIAL DE TERCERA", "SUBOFICIAL DE TERCERA DE SERVICIO", "SUBOFICIAL SUPERIOR", "SUBOFICIAL SUPERIOR DE SERVICIO", "SUBOFICIAL TECNICO DE 1RA", "SUBOFICIAL TECNICO DE 1RA DE SERVICIO", "SUBOFICIAL TECNICO DE 2DA", "SUBOFICIAL TECNICO DE 2DA DE SERVICIO", "SUBOFICIAL TECNICO DE 3RA", "SUBOFICIAL TECNICO DE 3RA DE SERVICIO", "TENIENTE", "TENIENTE DE SERVICIO", "TENIENTE GENERAL", "TENIENTE MAESTRO ARMERO");
    $complicaciones = ['SIN COMPLICACIONES', 'COMPLICADO'];
?>
<div class="loader"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
          <ul class="nav nav-tabs" id="tabs">
            <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">NUEVA ATENCION</a></li>
            <li><a href="#tab3default" data-toggle="tab" style="font-size:12px">HIST. ATENCIONES</a></li>
            <li><a href="#tab4default" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE LABORATORIO</a></li>
            <li><a href="#tab5default" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE RADIOLOGIA</a></li>
            <li><a href="#tab1007default" data-toggle="tab" style="font-size:12px">HIST. INTERCONSULTA</a></li>
            <li><a href="#tab_RC" data-toggle="tab" style="font-size:12px">HIST. REFERENCIAS / CONTRAREFERENCIAS</a></li>
            <li><a href="#tab_INFORMES_PP" data-toggle="tab" style="font-size:12px">HIST. DE ATENCIONES P. PROGRAMADO</a></li>
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
                            <h3 class="panel-title">ATENCIONES ADICIONALES MEDICAS</h3>
                          </div>
                          <div class="panel-body">
                            <form class="form-horizontal" method="post" action="{{ route('registrar_prestacion')}}"
                              id="frmPrestacion" name="frmPrestacion" autocomplete="off" enctype="multipart/form-data">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="id_nivel" id="id_nivel" value="{{ $nivel}}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                              <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia"
                                id="id_farmacia">
                              <input type="hidden" name="id_establecimiento" id="id_establecimiento"
                                value="<?php echo $establecimiento->id?>">
                              <input type="hidden" name="id_asegurado" id="id_asegurado" value="0">
                              <input type="hidden" name="pp_activo" id="pp_activo" value="0">
                              <input type="hidden" name="referencia_activo" id="referencia_activo" value="">
                                      <input type="hidden" name="contrareferencia_activo" id="contrareferencia_activo" value="">
                              <input type="hidden" name="id_medico" id="id_medico" value="<?php echo $medico->id ?>">
                              <input type="hidden" name="id_cita" id="id_cita" value="0">
                              <input type="hidden" name="edadpaciente" id="edadpaciente">
                              <input type="hidden" value="0" name="id_prestacion" id="id_prestacion">
                              <input id="procedimientosSeleccionados1" type="hidden" name="diagnosticosSeleccionados[]">
                              <input type="hidden" name="statusResponseWsSusalud" id="statusResponseWsSusalud" value="0">
                              <input type="hidden" name="id_cq_cita" id="id_cq_cita">
                              <input type="hidden" name="id_cqx_cita_pq" id="id_cqx_cita_pq" value="0">
                              <input type="hidden" name="id_cqx_intervencion_pq" id="id_cqx_intervencion_pq">
                              <input type="hidden" name="id_cqx_prestacion" id="id_cqx_prestacion">
                              <input type="hidden" id="is_profesion_medico" value="{{ ($is_profesion_medico ? 'true' : 'false') }}">
                              <input type="hidden" name="has_intervencion_quirurgica_2" id="has_intervencion_quirurgica_2" value="0">
                              <input type="hidden" id="id_rua_imagenes" name="id_rua_imagenes" value="">
                              <div class="alert alert-warning" role="alert" id="alertVigenciaSeguro" style="padding-left: 12px" hidden></div>
                              <div class="alert alert-warning" role="alert" style="padding-left: 10px" id="divAlert" hidden></div>
                              @if(Auth::user()->tipo_registro!=4)
                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                <fieldset>
                                  <legend>
                                    <b>DATOS DE LA ATENCI&Oacute;N ADICIONAL</b>
                                  </legend>
                                  <div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label>TIPO DE ATENCI&Oacute;N</label>
                                        <select class="form-control" name="id_tipo_atencion" id="id_tipo_atencion"
                                          tabindex="-1" style="width: 100%" onchange="getModalidad()">
                                          <option value="">-Seleccionar-</option>
                                          <?php foreach($tipo_atencion as $row):?>
                                            @if($nivel<3)
                                              @if($row->id_tipo_atencion!=2 and $row->id_tipo_atencion!=3 and $row->id_tipo_atencion!=4)
                                                <option value="<?php echo $row->id_tipo_atencion?>">
                                                <?php echo $row->nombre_tipo_atencion?></option>
                                              @endif
                                            @else
                                              @if($row->id_tipo_atencion!=7 and $row->id_tipo_atencion!=2 and $row->id_tipo_atencion!=3 and $row->id_tipo_atencion!=4)
                                                <option value="<?php echo $row->id_tipo_atencion?>">
                                                <?php echo $row->nombre_tipo_atencion?></option>
                                              @endif
                                            @endif
                                          <?php endforeach;?>
                                        </select>
                                      </div>
                                      <div class="col-md-3" id="divModalidad" style="display:none">
                                        <label for="modalidad_beneficiario">MODALIDAD</label>
                                        <select class="form-control" name="id_modalidad" id="id_modalidad" onchange="getConsultorio()"
                                          tabindex="8" style="width: 100%">
                                          <option value="">--Seleccionar--</option>
                                          <option value="1">Presencial</option>
                                          <option value="2">Virtual</option>
                                          <option value="3">Extramural</option>
                                        </select>
                                      </div>
                                      <div class="col-md-3">
                                        <label for="dni_beneficiario">SERVICIO</label>
                                        <select class="form-control" name="id_servicio" id="id_servicio" tabindex="8"
                                          style="width: 100%">
                                          <option value="0">--Seleccionar--</option>
                                          <?php foreach($consultorio_medico as $row):?>
                                          @if (intval($row->id_consultorio) !== 76 && intval($row->id_consultorio) !== 105)
                                            <option value="<?php echo $row->id_consultorio?>"><?php echo $row->nom_consultorio?></option>
                                          @endif
                                          <?php endforeach;?>
                                        </select>
                                      </div>
                                      <div class="col-md-3" id="divSubCons" style="display:none">
                                        <label for="dni_beneficiario">CONSULTORIO</label>
                                        <select class="form-control" name="id_sub_consultorio" id="id_sub_consultorio"
                                          tabindex="8" style="width: 100%">
                                          <option value="0">--Seleccionar--</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                    <div class="col-md-3">
                                        <label>FECHA DE ATENCION</label>
      
                                          <input type="text" data-date-start-date="-d" data-date-end-date="0d" 
                                          class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($fechaServidor)); ?>" name="fecha_atencion" 
                                          id="fecha_atencion" style="text-align:left" />
          		
                                      </div>
                                      <div class="col-md-3">
                                        <label for="dni_beneficiario">CODIGO UPSS</label>
                                        <select class="form-control" name="codigo_upss" id="codigo_upss"
                                          tabindex="8" style="width: 100%">
                                          <option value="0">--Seleccionar--</option>
                                        </select>
                                      </div>
                                      <!--div class="col-md-3">
                                        <label> CODIGO UPSS</label>
                                        <input readonly="" type="text" class="form-control" value="" name="codigo_upss"
                                          id="codigo_upss" />
                                      </div-->
                                      <div class="col-md-6">
                                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                                        <input readonly="" type="text" class="form-control"
                                          value="<?php echo $establecimiento->nombre?>" name="establecimiento"
                                          id="establecimiento" style="text-align:left">
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              @else
                              <div class="panel panel-info panel-bordered panel-sm panel-body">
                                <fieldset>
                                  <legend>
                                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                                  </legend>
                                  <div>
                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label>TIPO DE ATENCI&Oacute;N</label>
                                        <select class="form-control" name="id_tipo_atencion" id="id_tipo_atencion"
                                          tabindex="-1" style="width: 100%"
                                          onchange="getTipoAtencion();getConsultorio();">
                                          <option value="">-Seleccionar-</option>
                                          <?php foreach($tipo_atencion as $row):?>
                                            @if($row->id_tipo_atencion!=7 and $row->id_tipo_atencion!=2 and $row->id_tipo_atencion!=3)
                                            <option value="<?php echo $row->id_tipo_atencion?>">
                                              <?php echo $row->nombre_tipo_atencion?></option>
                                            @endif
                                          <?php endforeach;?>
                                          
                                        </select>
                                      </div>
                                      <div class="col-md-2">
                                        <label>FECHA DE ATENCION</label>
                                        <input type="text" class="form-control datepicker"
                                          value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                          name="fecha_atencion" id="fecha_atencion" style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label>N ° HISTORIA CLINICA</label>
                                        <input readonly="" type="text" class="form-control" value="" name="nro_historia"
                                          id="nro_historia" />
                                      </div>
                                      <div class="col-md-4">
                                        <label for="dni_beneficiario">SERVICIO</label>
                                        <select class="form-control" id="id_servicio" name="id_servicio" tabindex="6"
                                          onchange="getProducto(); getSubConsultorio();" style="width: 100%">
                                          <option value="0">--Seleccionar--</option>
                                          @foreach($consultorio as $c)
                                          <option value="{{$c->id}}">{{$c->nombre}}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                                        <input readonly="" type="text" class="form-control"
                                          value="<?php echo $establecimiento->nombre?>" name="establecimiento"
                                          id="establecimiento" style="text-align:left">
                                      </div>
                                      <div class="col-md-2" id="divSubCons" style="display:none">
                                        <label for="dni_beneficiario">CONSULTORIO</label>
                                        <select class="form-control" name="id_sub_consultorio" id="id_sub_consultorio"
                                          tabindex="8" style="width: 100%">
                                          <option value="0">--Seleccionar--</option>
                                        </select>
                                      </div>
                                      <div class="col-md-2" id="divTurno">
                                        <label>TURNO</label>
                                        <div id="divTurnoCol">
                                          <select class="form-control" name="turno" id="turno" tabindex="7"
                                            style="width: 100%" onchange="getMedico()">
                                            <option value="">-Seleccionar-</option>
                                            <option value="M">MAÃ‘ANA</option>
                                            <option value="T">TARDE</option>
                                            <option value="N">NOCHE</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="col-md-4" id="divMedico">
                                        <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                                        <div id="divMedicoCol">
                                          <select class="form-control" name="id_medico" id="id_medico" tabindex="8"
                                            style="width: 100%" onchange="valid_servicios_by_mes();obtenerMedico();">
                                            <option value="">--Seleccionar--</option>
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              @endif
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
                                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                        <select class="form-control input-sm" id="tipodoc_beneficiario_bus"
                                          name="tipodoc_beneficiario_bus" onchange="maxlength_doc_usu()" tabindex="3">
                                          <option value="1" selected="">DNI</option>
                                          <option value="2">CARNET DE EXTRANJERIA</option>
                                          <option value="3">PASAPORTE</option>
                                          <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                        </select>
                                        <input type="hidden" value="" name="tipodoc_beneficiario"
                                          id="tipodoc_beneficiario" class="form-control">
                                        <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular"
                                          class="form-control">
                                      </div>
                                      <div class="col-md-3">
                                        <label for="dni_beneficiario">N ° DE DOCUMENTO DE IDENTIDAD</label>
                                        <div class="input-group">
                                          <input onKeyPress="return soloNumeros(event)" id="dni_beneficiario"
                                            name="dni_beneficiario" type="text" placeholder=""
                                            class="form-control input-sm text-uppercase" maxlength="8" 
                                            onblur="limpiarBeneficiario()" tabindex="4">
                                          <span class="input-group-btn" style="height:28px!important;">
                                            <button type="button" id="btnBuscar" onclick="obtenerBeneficiario()"
                                              class="btn btn-info"
                                              style="height:28px!important;line-height:10px">Buscar</button>
                                          </span>
                                        </div>
                                        <input type="hidden" class="form-control" value="" name="beneficiario"
                                          id="beneficiario" style="text-align:left">
                                        <input type="hidden" class="form-control" value="" name="cip_beneficiario"
                                          id="cip_beneficiario" readonly="" />
                                        <input type="hidden" class="form-control" value="" name="nrodocafiliado"
                                          id="nrodocafiliado" readonly="" />
                                        <input type="hidden" class="form-control" value="" name="nrodoctitular"
                                          id="nrodoctitular" readonly="" />
                                      </div>
                                      <div class="col-md-2">
                                        <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                                        <div id="input-siteds" class="input-group">
                                          <input type="text" class="form-control"  id="codigo_siteds" name="codigo_siteds"  onkeypress="return esEntero(event);"
                                          style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"   maxlength="12"  pattern="[0-9]*" readonly />
                                          <!--span id="btn-siteds" class="input-group-btn">
                                            <button type="button" id="btn_generate_siteds" onclick="generarCodigoSiteds();"
                                              class="btn btn-sm btn-info" style="margin-top: -1px">Generar</button>
                                          </span-->
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <label>N ° HISTORIA CLINICA</label>
                                        <input readonly="" type="text" class="form-control" value="" name="nro_historia"
                                          id="nro_historia" />
                                      </div>
                                      <div class="col-md-2">
                                        <label for="dni_beneficiario">SEXO</label>
                                        <input readonly="" type="text" class="form-control" value="" name="nomsexo"
                                          id="nomsexo" />
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label>APELLIDO PATERNO</label>
                                        <input type="text" readonly="" class="form-control" value=""
                                          name="paterno_beneficiario" id="paterno_beneficiario" />
                                        <input type="hidden" class="form-control" value="" name="nombre_titular"
                                          id="nombre_titular" />
                                        <input type="hidden" class="form-control" value="" name="paterno_titular"
                                          id="paterno_titular" />
                                        <input type="hidden" class="form-control" value="" name="materno_titular"
                                          id="materno_titular" />
                                      </div>
                                      <div class="col-md-3">
                                        <label>APELLIDO MATERNO</label>
                                        <input readonly="" type="text" class="form-control" value=""
                                          name="materno_beneficiario" id="materno_beneficiario" />
                                      </div>
                                      <div class="col-md-3">
                                        <label>NOMBRES</label>
                                        <input readonly="" type="text" class="form-control" value=""
                                          name="nombre_beneficiario" id="nombre_beneficiario" />
                                      </div>
                                      <div class="col-md-3">
                                        <label>FECHA DE NACIMIENTO</label>
                                        <input readonly="" type="text" class="form-control" value=""
                                          name="fecnacafiliado" id="fecnacafiliado" />
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      
                                      <div class="col-md-3">
                                        <label>PARENTESCO &nbsp;</label>
                                        <input readonly="" value="" type="text" name="tipo_beneficiario"
                                          id="tipo_beneficiario" class="form-control">
                                      </div>
                                      <div class="col-md-3">
                                        <label>GRADO &nbsp;</label>
                                        <input readonly="" value="" type="text" name="grado" id="grado"
                                          class="form-control">
                                      </div>
                                      <div class="col-md-3">
                                        <label>CIP &nbsp;</label>
                                        <input readonly="" value="" type="text" name="cip_pnp" id="cip_pnp"
                                          class="form-control">
                                      </div>
                                      <div class="col-md-3">
                                        <label>SITUACION &nbsp;</label>
                                        <input readonly="" value="" type="text" name="situacion_paciente" id="situacion_paciente"
                                          class="form-control">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                        <label>TELEFONO &nbsp;</label>
                                        <input readonly="" value="" type="text" name="telefono" id="telefono"
                                          class="form-control">
                                      </div>
                                      <div class="col-md-3">
                                        <label>TELEFONO 2 &nbsp;</label>
                                        <input readonly="" value="" type="text" name="telefono2" id="telefono2"
                                          class="form-control">
                                      </div>
                                      <div class="col-md-6">
                                        <label>DOMICILIO&nbsp;</label>
                                        <input readonly="" value="" type="text" name="domicilio" id="domicilio"
                                          class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>

                              <!-- <div class=" col-xs-12" style="padding-bottom: 0.8rem;" id="VerConadis"> -->
                                <fieldset style="" id="VerConadis">
                                    <div class="panel panel-info h-100 panel-bordered panel-sm" >
                                        <!-- <div class="panel-heading">
                                          <h3 class="panel-title size-titulo">INFORMACIÓN DEL PACIENTE</h3>
                                        </div> -->
                                        <div class="panel-heading">
                                          <div class="panel-title text-center">
                                            <b>INFORMACIÓN DEL PACIENTE</b>
                                          </div>
                                        </div>
                                        <div class="panel-body" style="padding-bottom: 0;" >
                                              <div class="form-group col-xs-12">
                                                  <div class="col-md-3 size-titulo">
                                                    <label style="margin: 0;">PACIENTE CON ALGUNA DISCAPACIDAD</label><br>
                                                    <input type="radio" checked="" class="regular-radio susalud-content" name="discapacidad"
                                                      style="cursor: pointer;" id="discapacidad1" value="SI">
                                                    <b>SI</b>
                                                    <input type="radio" checked="" class="regular-radio susalud-content" name="discapacidad"
                                                      id="discapacidad2" value="NO" style="margin-left: 10px;cursor: pointer;">
                                                    <b>NO</b>
                                                  </div>
                                                  <div id="paciente_discapacitado" name="paciente_discapacitado">
                                                    <div class="col-md-2 size-titulo">
                                                      <label for="tipo">PACIENTE CON CARNET CONADIS</label><br>
                                                      <input type="radio" checked="" class="regular-radio susalud-content" name="discapacidad_carnet"
                                                        style="cursor: pointer;" id="discapacidad_carnet_1" value="SI">
                                                      <b>SI</b>
                                                      <input type="radio" checked="" class="regular-radio susalud-content" name="discapacidad_carnet"
                                                        id="discapacidad_carnet_2" value="NO" style="margin-left: 10px;cursor: pointer;">
                                                      <b>NO</b>
                                                    </div>
                                                  </div>
                                                  <div id="paciente_discapacitado_carnet" name="paciente_discapacitado_carnet"> 
                                                      <div class="col-md-2 size-contenido">
                                                        <label>COLOR CARNET</label>
                                                        <select class="form-control input-sm susalud-content" id="color_carnet_conadis"
                                                          name="color_carnet_conadis" style="width: 100%;">
                                                            <option value="0">Seleccionar</option>
                                                            <option value="1">AMARILLO</option>
                                                            <option value="2">CELESTE</option>
                                                        </select>
                                                      </div>
                                                      <div class="col-md-3 size-contenido">
                                                        <label>N CARNET &nbsp;</label>
                                                        <input value="" type="text" name="nro_carnet_conadis" id="nro_carnet_conadis" maxlength="12" 
                                                        class="form-control susalud-content">
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="form-group col-xs-12">
                                                  <div class="col-md-3 size-titulo">
                                                    <label for="tipo">PRESC. PRODUCTO DE APOYO</label><br>
                                                    <input type="radio" checked="" class="regular-radio susalud-content" name="presc_producto_apoyo"
                                                      style="cursor: pointer;" id="presc_producto_apoyo1" value="SI">
                                                    <b>SI</b>
                                                    <input type="radio" checked="" class="regular-radio susalud-content" name="presc_producto_apoyo"
                                                      id="presc_producto_apoyo2" value="NO" style="margin-left: 10px;cursor: pointer;">
                                                    <b>NO</b>
                                                  </div>
                                                  <div id="producto_apoyo" name="producto_apoyo">
                                                    <div class="col-md-2 size-titulo">
                                                      <label for="tipo">USA PRODUCTO DE APOYO</label><br>
                                                      <input type="radio" checked="" class="regular-radio susalud-content" name="usa_product_apoyo"
                                                        style="cursor: pointer;" id="usa_product_apoyo_1" value="SI">
                                                      <b>SI</b>
                                                      <input type="radio" checked="" class="regular-radio susalud-content" name="usa_product_apoyo"
                                                        id="usa_product_apoyo_2" value="NO" style="margin-left: 10px;cursor: pointer;">
                                                      <b>NO</b>
                                                    </div>
                                                  </div>
                                                  <div id="tips_products" name="tips_products"> 
                                                      <div class="col-md-2 size-contenido">
                                                        <label>TIPO DE PRODUCTO</label>
                                                        <select class="form-control input-sm susalud-content" id="tipo_producto"
                                                          name="tipo_producto" style="width: 100%;">
                                                            <option value="0">Seleccionar</option>
                                                            <option value="1">SILLA DE RUEDAS</option>
                                                            <option value="2">PRÓTESIS</option>
                                                            <option value="3">ÓRTESIS</option>
                                                            <option value="4">AUDÍFONOS</option>
                                                            <option value="5">BASTONES</option>
                                                            <option value="6">OTRO</option>  
                                                        </select>
                                                      </div>
                                                      <div class="col-md-3 size-titulo">
                                                        <label>&nbsp;</label>
                                                        <input value="" type="text" name="nro_tipo_producto" id="nro_tipo_producto" maxlength="12" 
                                                        class="form-control susalud-content">
                                                      </div><br>
                                                      <div class="col-md-2 size-titulo">
                                                        <button id="btnHistProd" class="form-control btn btn-success" type="button">HISTORIAL DE PRODUCTOS</button>
                                                      </div>
                                                  </div>
                                              </div>
                                        </div>
                                    </div>
                                </fieldset>
                              <!-- </div> -->

                              @if(Auth::user()->tipo_registro!=4)
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                        <input readonly="" type="text" class="form-control" value="DNI"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-4">
                                        <label for="dni_beneficiario">N ° DE DOCUMENTO DE IDENTIDAD</label>
                                        <input readonly="" type="text" class="form-control"
                                          value="<?php echo $medico->dni ?>" name="cc" id="cc" style="text-align:left">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label>APELLIDO PATERNO</label>
                                        <input readonly="" type="text" class="form-control"
                                          value="<?php echo $medico->paterno ?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-4">
                                        <label>APELLIDO MATERNO</label>
                                        <input readonly="" type="text" class="form-control"
                                          value="<?php echo $medico->materno ?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-4">
                                        <label>NOMBRES</label>
                                        <input readonly="" type="text" class="form-control"
                                          value="<?php echo $medico->nombre ?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-2">
                                        <label>N ° COLEGIATURA</label>
                                        <input readonly="" type="text" class="form-control"
                                          value="<?php echo $medico->colegiatura ?>" name="cc" id="cc"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label>N ° RNE</label>
                                        <input readonly="" value="<?php echo $medico->rne ?>" type="text" name="rne"
                                          id="rne" class="form-control">
                                      </div>
                                      <div class="col-md-4">
                                        <label>PROFESION</label>
                                        <input readonly="" value="<?php echo $medico->profesion ?>" type="text"
                                          name="profesion" id="profesion" class="form-control">
                                      </div>
                                      <div class="col-md-4">
                                        <label>ESPECIALIDAD</label>
                                        <input readonly="" value="<?php echo $medico->especializacion ?>" type="text"
                                          name="especializacion" id="especializacion" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              @else
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                        <input readonly="" type="text" class="form-control" value="DNI"
                                          style="text-align:left">
                                      </div>
                                      <div class="col-md-4">
                                        <label for="dni_beneficiario">N ° DE DOCUMENTO DE IDENTIDAD</label>
                                        <input readonly="" type="text" class="form-control" value="" name="dni_medico"
                                          id="dni_medico" style="text-align:left">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-4">
                                        <label>APELLIDO PATERNO</label>
                                        <input readonly="" type="text" class="form-control" value=""
                                          name="medico_paterno" id="medico_paterno" style="text-align:left">
                                      </div>
                                      <div class="col-md-4">
                                        <label>APELLIDO MATERNO</label>
                                        <input readonly="" type="text" class="form-control" value=""
                                          name="medico_materno" id="medico_materno" style="text-align:left">
                                      </div>
                                      <div class="col-md-4">
                                        <label>NOMBRES</label>
                                        <input readonly="" type="text" class="form-control" value=""
                                          name="medico_nombre" id="medico_nombre" style="text-align:left">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-2">
                                        <label>N ° COLEGIATURA</label>
                                        <input readonly="" type="text" class="form-control" value="" name="colegiatura"
                                          id="colegiatura" style="text-align:left">
                                      </div>
                                      <div class="col-md-2">
                                        <label>N ° RNE</label>
                                        <input readonly="" value="" type="text" name="rne" id="rne"
                                          class="form-control">
                                      </div>
                                      <div class="col-md-4">
                                        <label>PROFESION</label>
                                        <input readonly="" value="" type="text" name="profesion" id="profesion"
                                          class="form-control">
                                      </div>
                                      <div class="col-md-4">
                                        <label>ESPECIALIDAD</label>
                                        <input readonly="" value="" type="text" name="especializacion"
                                          id="especializacion" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>

                              @endif
                              <fieldset id="modalDatosReferencia">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <input type="hidden" name="id_referencia_inicial" id="id_referencia_inicial">
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
                                          <td id="referenciaEstablecimientoOrigen"></td>
                                          <td id="referenciaServicioOrigen"></td>
                                          <td id="referenciaObservacion"></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </fieldset>  

                              <fieldset id="modalDiagnosticosReferencia">
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
                                      <tbody id="tblDiagnosticosReferencia">
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </fieldset>

                              <fieldset id="modalProcedimientosReferencia">
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
                                      <tbody id="tblProcedimientosReferencia">
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>CONDICION</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <div class="col-md-4">
                                        <label for="tipo">ESTABLECIMIENTO</label>
                                        <select class="form-control input-sm" id="ipress_bene" name="ipress_bene"
                                          tabindex="3">
                                          <option value="NUEVO">NUEVO</option>
                                          <option value="CONTINUADOR">CONTINUADOR</option>
                                          <option value="REINGRESO">REINGRESO</option>
                                        </select>
                                      </div>
                                      <div class="col-md-4">
                                        <label for="tipo">SERVICIO</label>
                                        <select class="form-control input-sm" id="upss_bene" name="upss_bene"
                                          tabindex="3">
                                          <option value="NUEVO">NUEVO</option>
                                          <option value="CONTINUADOR">CONTINUADOR</option>
                                          <option value="REINGRESO">REINGRESO</option>
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
                                      <b>ANTECEDENTES</b>
                                    </div>
                                  </div>
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>ANTECEDENTE</label>
                                          <textarea class="form-control" cols="5" id="antecedente" name="antecedente"
                                            placeholder="Antecedente Personal"></textarea>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>ANTECEDENTE FAMILIAR</label>
                                          <textarea class="form-control" cols="5" id="antecedente_familiar" name="antecedente_familiar"
                                            placeholder="Antecedente Familiar"></textarea>
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
                                          <textarea class="form-control" cols="5" id="motivo_visita" name="motivo_visita"
                                            placeholder="Relato m&eacute;dico del motivo de la visita" ></textarea>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>TIEMPO DE ENFERMEDAD</label>
                                          <textarea class="form-control" cols="5" id="tiempo_enfermedad" name="tiempo_enfermedad"
                                            placeholder="Relato m&eacute;dico del tiempo de enfermedad" ></textarea>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="col-md-12">
                                          <label>RELATO CRONOLOGICO</label>
                                          <textarea class="form-control" cols="5" id="anamnesis" name="anamnesis"
                                            placeholder="Relato m&eacute;dico del relato cronologico" ></textarea>
                                        </div>
                                      </div>
                                    </div>
                                    <!--div class="tab-pane" id="tab8default"-->
				<div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>EXAMEN FISICO</b>
                                      </div>
                                    </div>
                                        <div class="panel-body" id="VerAspectosGenerales">
                                          <div class="form-group">
                                            <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                              <label>ASPECTOS GENERALES</label>
                                            </div>
                                            <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                              <button tabindex="62" type="button" id="addAspectosGenerales" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                <div class="handle"></div>
                                              </button>
                                            </div>
                                          </div>
                                          <div class="panel panel-info panel-bordered panel-sm" id="blockAspectosGenerales" style="display: none;">
                                            <div class="panel-heading">
                                              <div class="panel-title text-center">
                                                <b>ASPECTOS GENERALES</b>
                                              </div>
                                            </div>
                                            <div class="panel-body">
                                              <table>
                                                <tr>
                                                  <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEscala">Escalas de Valor</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFichaPsicologia">Ficha Psicologica</button>
                                                  </td>
                                                </tr>
                                              </table>
                                              <br />
                                              <div class="form-group" id="bloqueEscalas">
                                                <div class="col-md-12">
                                                  <textarea tabindex="61" class="form-control app-textarea" cols="5" rows="5" id="aspectos_generales" name="aspectos_generales"placeholder="Aspectos Generales" readonly></textarea>
                                                </div>
                                              </div>
                                            </div>
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
                                                <label>TEMPERATURA ( °C) &nbsp;</label>
                                                <input placeholder="0.00" value="" type="text" name="temperatura" id="temperatura" class="form-control" tabindex="23" onkeypress="return esNumero(event,this.id)" maxlength="6" />
                                              </div>
                                              <div class="col-md-2">
                                                <label>TALLA (cm) &nbsp;</label>
                                                <input placeholder="0" value="" type="text" name="talla" id="talla" class="form-control" tabindex="24" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>PESO (Kg) &nbsp;</label>
                                                <input placeholder="0.00" value="" type="text" name="peso" id="peso" class="form-control"  tabindex="25" onkeyup="compruebaIMC()" onkeypress="return esNumero(event,this.id)"  maxlength="6" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>IMC &nbsp;</label>
                                                <input value="" type="text" name="imc" id="imc" class="form-control" readonly="" tabindex="26" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>PESO IDEAL &nbsp;</label>
                                                <input value="" type="text" name="peso_x_altura" id="peso_x_altura" class="form-control" readonly="" max="0" tabindex="27" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>Sa02 (%) &nbsp;</label>
                                               
                                                <input placeholder="0" value="" type="text" name="saturacion" id="saturacion" class="form-control" tabindex="28" onkeypress="return esNumero(event,this.id)" maxlength="3" />

                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <div class="col-md-2">
                                                <label>TAMAÑO DE CINTURA (cm)</label>

                                                <input placeholder="0" value="" type="text" name="tamanio_cintura" id="tamanio_cintura" class="form-control" tabindex="29" onkeypress="return esNumero(event,this.id)" maxlength="3" />

                                              </div>
                                              <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                                <label>FREC. CARDIACA FETAL(/min)</label>

                                                <input placeholder="0" value="" type="text" name="frecuencia_cardiaca_fetal" id="frecuencia_cardiaca_fetal" class="form-control" tabindex="30" onkeypress="return esNumero(event,this.id)" maxlength="3" />

                                              </div>
                                              <div class="col-md-2">
                                                <label>PRESIÓN ARTERIAL &nbsp;</label>
                                                <div class="row">
                                                  <div class="col-xs-5">  

                                                    <input placeholder="0" value="" tabindex="31" type="text" name="presion_arterial_x" id="presion_arterial_x" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >

                                                  </div>  
                                                    <div class="col-xs-2">
                                                       <label>/</label> 
                                                    </div>
                                                  <div class="col-xs-5">
                                                    <input placeholder="0" value="" type="text" tabindex="32" name="presion_arterial_y" id="presion_arterial_y" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                                  </div>  
                                                </div>
                                            </div>
                                              <div class="col-md-2">
                                                <label>FREC.RESPIRATORIA(/min)</label>
                                                <input placeholder="0" value="" type="text" name="frecuencia_respiratoria" tabindex="33" id="frecuencia_respiratoria" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>FREC. CARDIACA (/min) &nbsp;</label>
                                                <input placeholder="0" value="" type="text" name="frec_cardiaca" id="frec_cardiaca" tabindex="34" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                              <div class="col-md-2">
                                                <label>Fi02 (%) &nbsp;</label>
                                                <input placeholder="0" value="" type="text" name="oxigenacion" id="oxigenacion" tabindex="35" class="form-control" onkeypress="return esNumero(event,this.id)" maxlength="3" >
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="panel-body" id="VerOftalmologia">
                                        <div class="form-group">
                                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                            <label>OFTALMOLOGIA</label>
                                          </div>
                                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                            <button tabindex="62" type="button" id="addOftalmologia" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </div>
                                        </div>
                                        <div id="fsOftalmologia" style="display:none">
                                          <div class="form-group">
                                            <div class="col-md-12">
                                              <div class="panel panel-info panel-bordered panel-sm"
                                                style="padding-bottom: 0px">
                                                <div class="form-group">
                                                  <div class="panel-body">
                                                    <div class="col-md-12">
                                                      <label for=""><b style="font-size: 16px;">1. AV</b></label>
                                                    </div>
                                                    <div class="col-md-12">
                                                      <table class="table table-striped table-list-search">
                                                        <thead>
                                                          <tr>
                                                            <th style="text-align: center;"> </th>
                                                            <th style="text-align: center;">OD</th>
                                                            <th style="text-align: center;">OI</th>
                                                          </tr>
                                                        </thead>
                                                        <tr>
                                                          <td style="width: 30%;">
                                                            <label style="font-size: 12px; padding-right: 15px;">AV
                                                              SC</label>
                                                          </td>
                                                          <td>
                                                            <select class="form-control" name="od_av_sc" id="od_av_sc">
                                                              <option value="20/20">20/20</option>
                                                              <option value="20/25">20/25</option>
                                                              <option value="20/30">20/30</option>
                                                              <option value="20/40">20/40</option>
                                                            </select>
                                                          </td>
                                                          <td>
                                                            <select class="form-control" name="oi_av_sc" id="oi_av_sc">
                                                              <option value="20/20">20/20</option>
                                                              <option value="20/25">20/25</option>
                                                              <option value="20/30">20/30</option>
                                                              <option value="20/40">20/40</option>
                                                            </select>
                                                          </td>
                                                        </tr>
                                                        <tr>
                                                          <td style="width: 30%;">
                                                            <label style="font-size: 12px;padding-right: 15px;">AV
                                                              CC</label>
                                                          </td>
                                                          <td>
                                                            <select class="form-control" name="od_av_cc" id="od_av_cc">
                                                              <option value="20/20">20/20</option>
                                                              <option value="20/25">20/25</option>
                                                              <option value="20/30">20/30</option>
                                                              <option value="20/40">20/40</option>
                                                            </select>
                                                          </td>
                                                          <td>
                                                            <select class="form-control" name="oi_av_cc" id="oi_av_cc">
                                                              <option value="20/20">20/20</option>
                                                              <option value="20/25">20/25</option>
                                                              <option value="20/30">20/30</option>
                                                              <option value="20/40">20/40</option>
                                                            </select>
                                                          </td>
                                                        </tr>
                                                        <tr>
                                                          <td style="width: 30%;">
                                                            <label style="font-size: 12px;padding-right: 15px;">AV
                                                              CAE</label>
                                                          </td>
                                                          <td>
                                                            <select class="form-control" name="od_av_cae" id="od_av_cae">
                                                              <option value="20/20">20/20</option>
                                                              <option value="20/25">20/25</option>
                                                              <option value="20/30">20/30</option>
                                                              <option value="20/40">20/40</option>
                                                            </select>
                                                          </td>
                                                          <td>
                                                            <select class="form-control" name="oi_av_cae" id="oi_av_cae">
                                                              <option value="20/20">20/20</option>
                                                              <option value="20/25">20/25</option>
                                                              <option value="20/30">20/30</option>
                                                              <option value="20/40">20/40</option>
                                                            </select>
                                                          </td>
                                                        </tr>
                                                        <tr>
                                                          <td style="width: 30%;">
                                                            <label
                                                              style="font-size: 12px;padding-right: 15px;">PIO</label>
                                                          </td>
                                                          <td>
                                                            <input type="text" class="form-control" name="od_pio"
                                                              id="od_pio">
                                                          </td>
                                                          <td>
                                                            <input type="text" class="form-control" name="oi_pio"
                                                              id="oi_pio">
                                                          </td>
                                                        </tr>
                                                      </table>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>

                                              <div class="panel panel-info panel-bordered panel-sm">
                                                <div class="form-group">
                                                  <div class="panel-body">
                                                    <div class="col-md-12">
                                                      <label for=""><b style="font-size: 16px;">2. REFRACCION</b></label>
                                                    </div>
                                                    <div class="col-md-12">
                                                      <table class="table table-striped table-list-search" id="tblOftalmo" cellspacing="0" width="100%"
                                                        style="margin-top:5px;">
                                                        <thead>
                                                          <tr>
                                                              <th width="20%"> </th>
                                                              <th width="16%"><p align="center"> SPH </p></th>
                                                              <th width="16%"><p align="center"> CIL </p></th>
                                                              <th width="16%"><p align="center"> EJE </p></th>
                                                              <th width="16%"><p align="center"> DIP </p></th>
                                                              <th width="16%"><p align="center"> AV </p></th>
                                                          </tr>
                                                        </thead>
                                                        <tfoot>
                                                        </tfoot>
                                                        <tbody>
                                                          <tr>
                                                              <th width="20%">OD LEJOS</th>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3" style="margin:0 20px;width:90%" type="text" class="form-control" name="od_lejos_sph" id="od_lejos_sph">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3" style="margin:0 20px;width:90%" type="text" class="form-control" name="od_lejos_cil" id="od_lejos_cil">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3" style="margin:0 20px;width:90%" type="text" class="form-control" name="od_lejos_eje" id="od_lejos_eje">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="od_lejos_dip" id="od_lejos_dip">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="od_lejos_av" id="od_lejos_av">
                                                              </td>
                                                          </tr>
                                                          <tr>
                                                              <th width="20%">OI LEJOS</th>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_lejos_sph" id="oi_lejos_sph">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_lejos_cil" id="oi_lejos_cil">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_lejos_eje" id="oi_lejos_eje">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_lejos_dip" id="oi_lejos_dip">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_lejos_av" id="oi_lejos_av">
                                                              </td>
                                                          </tr>
                                                          <tr>
                                                              <th width="20%">OD CERCA</th>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="od_cerca_sph" id="od_cerca_sph">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="od_cerca_cil" id="od_cerca_cil">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="od_cerca_eje" id="od_cerca_eje">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="od_cerca_dip" id="od_cerca_dip">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="od_cerca_av" id="od_cerca_av">
                                                              </td>
                                                          </tr>
                                                          <tr>
                                                              <th width="20%">OI CERCA</th>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_cerca_sph" id="oi_cerca_sph">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_cerca_cil" id="oi_cerca_cil">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_cerca_eje" id="oi_cerca_eje">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_cerca_dip" id="oi_cerca_dip">
                                                              </td>
                                                              <td width="16%">
                                                                <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="oi_cerca_av" id="oi_cerca_av">
                                                              </td>
                                                          </tr>
                                                          <tr>
                                                            <th width="20%">ADD</th>
                                                            <td width="16%">
                                                              <input onkeypress="return esEntero(event);" maxlength="3"  style="margin:0 20px;width:90%" type="text" class="form-control" name="refraccion_add"
                                                              id="refraccion_add">
                                                              </td>
                                                            <td width="16%"></td>
                                                            <td width="16%"></td>
                                                            <td width="16%"></td>
                                                            <td width="16%"></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>

                                              <div class="panel panel-info panel-bordered panel-sm">
                                                <div class="form-group">
                                                  <div class="panel-body">
                                                    <div class="col-md-12">
                                                      <label for=""><b style="font-size: 16px;">3. MOTILIDAD
                                                          OCULAR</b></label>
                                                    </div>
                                                    <div class="col-md-1" style="padding-right:0px;">
                                                      <br>
                                                      <input width="50%" onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="od_motilidad_1" id="od_motilidad_1">
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="od_motilidad_2" id="od_motilidad_2">
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="od_motilidad_3" id="od_motilidad_3">
                                                    </div>
                                                    <div class="col-md-1" style="padding-right:0px;">
                                                      <br><img src="../../images/oftalmologia/motalidad.png" width="105"
                                                        height="auto">
                                                    </div>
                                                    <div class="col-md-1" style="padding-right:0px;">
                                                      <br>
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="od_motilidad_4" id="od_motilidad_4">
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="od_motilidad_5" id="od_motilidad_5">
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="od_motilidad_6" id="od_motilidad_6">
                                                    </div>
                                                    <div class="col-md-1" style="padding-right:0px;">
                                                      <br>
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="oi_motilidad_1" id="oi_motilidad_1">
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="oi_motilidad_2" id="oi_motilidad_2">
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="oi_motilidad_3" id="oi_motilidad_3">
                                                    </div>
                                                    <div class="col-md-1" style="padding-right:0px;">
                                                      <br>
                                                      <img src="../../images/oftalmologia/motalidad2.png" width="105"
                                                        height="auto">
                                                    </div>
                                                    <div class="col-md-1" style="padding-right:0px;">
                                                      <br>
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="oi_motilidad_4" id="oi_motilidad_4">
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="oi_motilidad_5" id="oi_motilidad_5">
                                                      <input onkeypress="return esEntero(event);" maxlength="3" type="text" class="form-control" name="oi_motilidad_6" id="oi_motilidad_6">
                                                    </div>
                                                    <div class="col-md-6">
                                                      <label style="font-size: 12px; margin-bottom: 0px !important;">TEST
                                                        HIRSCHBERG-COVER TEST-OTROS</label><br>
                                                      <textarea class="form-control" name="motilidad_test"
                                                        id="motilidad_test" cols="30" rows="4"></textarea>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="panel panel-info panel-bordered panel-sm">
                                                <div class="form-group">
                                                  <div class="panel-body">
                                                    <div class="col-md-12">
                                                      <label for=""><b style="font-size: 16px;">4.
                                                          BIOMICROSCOPIA</b></label>
                                                    </div>
                                                    <div class="col-md-12">
                                                      <table class="table table-striped table-list-search" id="tblOftalmoBio" cellspacing="0" width="100%"
                                                          style="margin-top:5px;">
                                                          <thead>
                                                            <tr>
                                                                <th width="4%"> </th>
                                                                <th width="16%"><p align="center">  PARPADO </p></th>
                                                                <th width="16%"><p align="center"> CONJUNTIVA </p></th>
                                                                <th width="16%"><p align="center">  CORNEA </p></th>
                                                                <th width="16%"><p align="center"> CA </p></th>
                                                                <th width="16%"><p align="center">  PUPILA </p></th>
                                                                <th width="16%"><p align="center"> CRISTALINO </p></th>
                                                            </tr>
                                                          </thead>
                                                          <tfoot>
                                                          </tfoot>
                                                          <tbody>
                                                            <tr>
                                                                <th width="4%">OD</th>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_parpado" id="od_parpado" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_conjuntiva" id="od_conjuntiva" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_cornea" id="od_cornea" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_ca" id="od_ca" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_pupila" id="od_pupila" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_cristalino" id="od_cristalino" cols="30" rows="8"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th width="4%">OI</th>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_parpado" id="oi_parpado" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_conjuntiva" id="oi_conjuntiva" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_cornea" id="oi_cornea" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_ca" id="oi_ca" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_pupila" id="oi_pupila" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="16%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_cristalino" id="oi_cristalino" cols="30" rows="8"></textarea>
                                                                </td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="panel panel-info panel-bordered panel-sm">
                                                <div class="form-group">
                                                  <div class="panel-body">
                                                    <div class="col-md-12">
                                                      <label for=""><b style="font-size: 16px;">5. FONDO DE
                                                          OJO</b></label>
                                                    </div>
                                                    <div class="col-md-12">
                                                      <table class="table table-striped table-list-search" id="tblOftalmoBio" cellspacing="0" width="100%"
                                                          style="margin-top:5px;">
                                                          <thead>
                                                            <tr>
                                                                <th width="5%"> </th>
                                                                <th width="19%"><p align="center">  CRISTALINO </p></th>
                                                                <th width="19%"><p align="center"> DISCO OPTICO </p></th>
                                                                <th width="19%"><p align="center">  DISCO MACULA </p></th>
                                                                <th width="19%"><p align="center"> RETINA </p></th>
                                                                <th width="19%"><p align="center">  VASOS RETINIANOS </p></th>
                                                            </tr>
                                                          </thead>
                                                          <tfoot>
                                                          </tfoot>
                                                          <tbody>
                                                            <tr>
                                                                <th width="5%">OD</th>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_cristalino_fondo" id="od_cristalino_fondo" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_disco_optico" id="od_disco_optico" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_disco_macula" id="od_disco_macula" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_retina" id="od_retina" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="od_vasos_retinianos" id="od_vasos_retinianos" cols="30" rows="8"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th width="5%">OI</th>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_cristalino_fondo" id="oi_cristalino_fondo" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_disco_optico" id="oi_disco_optico" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_disco_macula" id="oi_disco_macula" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_retina" id="oi_retina" cols="30" rows="8"></textarea>
                                                                </td>
                                                                <td width="19%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="oi_vasos_retinianos" id="oi_vasos_retinianos" cols="30" rows="8"></textarea>
                                                                </td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info panel-bordered panel-sm"
                                              style="padding-left: 14px;padding-right: 14px; padding-bottom: 0px important;">
                                              <div class="form-group">
                                                <div class="panel-body">
                                                  <div class="col-md-12">
                                                    <label for=""><b style="font-size: 16px;">6. IMAGENES
                                                        AUXILIARES</b></label>
                                                  </div>
                                                  <div class="col-md-4 panel panel-info panel-bordered panel-sm">
                                                    <br><label style="font-size: 14px;">BIOMETRIA</label>
                                                    <input type="file" name="imag_biometria" id="imag_biometria"
                                                      accept=".pdf" class="input-sm" style="padding: 0px" required>
                                                  </div>
                                                  <div class="col-md-4 panel panel-info panel-bordered panel-sm">
                                                    <br><label style="font-size: 14px;">RETINOGRAFIA</label>
                                                    <input type="file" name="imag_retinografia" id="imag_retinografia"
                                                      accept=".pdf" class="input-sm" style="padding: 0px" required>
                                                  </div>
                                                  <div class="col-md-4 panel panel-info panel-bordered panel-sm">
                                                    <br><label style="font-size: 14px;">CAMPIMETRIA</label>
                                                    <input type="file" name="imag_campimetria" id="imag_campimetria"
                                                      accept=".pdf" class="input-sm" style="padding: 0px" required>
                                                  </div>
                                                  <div class="col-md-4 panel panel-info panel-bordered panel-sm">
                                                    <br><label style="font-size: 14px;">ECOGRAFÍA OCULAR</label>
                                                    <input type="file" name="imag_ecografia_ocular"
                                                      id="imag_ecografia_ocular" accept=".pdf" class="input-sm"
                                                      style="padding: 0px" required>
                                                  </div>
                                                  <!--div class="col-md-4 panel panel-info panel-bordered panel-sm">
                                                    <br><label style="font-size: 14px;">NEW</label>
                                                    <input type="file" name="imag_new"
                                                      id="imag_new" accept=".pdf" class="input-sm"
                                                      style="padding: 0px" required>
                                                  </div-->
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div> 
                                      </div>                                   
                                      <div class="panel-body" id="VerGastroenterologia">
                                        <div class="form-group">
                                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                            <label>GASTROENTEROLOGIA</label>
                                          </div>
                                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                            <button tabindex="62" type="button" id="addGastro" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </div>
                                        </div>
                                        <div id="fsGastro" style="display:none">
                                          <div class="panel with-nav-tabs panel-default">
                                            <div class="panel-heading">
                                              <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab_baja" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA BAJA</a></li>
                                                <li><a href="#tab_alta" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA ALTA</a></li>
                                              </ul>
                                            </div>
                                            <div class="panel-body">
                                              <div class="tab-content">
                                                <div class="tab-pane fade in active" id="tab_baja">
                                                  <fieldset>
                                                    <div class="panel-body">
                                                      <div class="table-responsive">
                                                        <table class="table table-striped table-list-search" id="tblGastroBaja" cellspacing="0" width="100%" style="margin-top:5px;">
                                                          <tfoot>
                                                          </tfoot>
                                                          <tbody>
                                                            <tr>
                                                                <td width="15%"><b>INDICACIONES:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_indicaciones" id="baja_indicaciones" cols="30" rows="1"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>MEDICACION:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_medicacion" id="baja_medicacion" cols="30" rows="1"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>INSPECCION:</b></td>
                                                                <td width="85%" colspan="8">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"></td>
                                                                <td width="10%"><b>Estatica:</b></td>
                                                                <td width="65%" colspan="6">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_estatica" id="baja_estatica" cols="30" rows="2"></textarea>
                                                                </td>
                                                                <td width="10%"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"></td>
                                                                <td width="10%"><b>Dinamica:</b></td>
                                                                <td width="65%" colspan="6">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_dinamica" id="baja_dinamica" cols="30" rows="2"></textarea>
                                                                </td>
                                                                <td width="10%"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"></td>
                                                                <td width="10%"><b>Tacto Rectal:</b></td>
                                                                <td width="65%" colspan="6">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_tacto_rectal" id="baja_tacto_rectal" cols="30" rows="2"></textarea>
                                                                </td>
                                                                <td width="10%"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>PREPARACION:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_preparacion" id="baja_preparacion" cols="30" rows="1"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="20%"><b>ESCALA DE BOSTON:</b></td>
                                                                <td width="10%">CD</td>
                                                                <td width="10%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_escala_cd" id="baja_escala_cd" cols="30" rows="1"></textarea>
                                                                </td>
                                                                <td width="10%">CT</td>
                                                                <td width="10%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_escala_ct" id="baja_escala_ct" rows="1"></textarea>
                                                                </td>
                                                                <td width="10%">CI</td>
                                                                <td width="10%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_escala_ci" id="baja_escala_ci" rows="1"></textarea>
                                                                </td>
                                                                <td width="10%">TOTAL</td>
                                                                <td width="10%">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_escala_total" id="baja_escala_total" rows="1"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>INSERCION:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_insercion" id="baja_insercion" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>HALLAZGOS:</b></td>
                                                                <td width="85%" colspan="8">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>CANAL ANAL:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_canal_anal" id="baja_canal_anal" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>RECTO:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_recto" id="baja_recto" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>SIGMOIDES:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_sigmoide" id="baja_sigmoide" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>DESCENDENTE:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_descendente" id="baja_descendente" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Transverso:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_transverso" id="baja_transverso" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Ascendente:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_ascendente" id="baja_ascendente" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Ciego:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_ciego" id="baja_ciego" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>COMPLICACIONES:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_complicaciones" id="baja_complicaciones" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>BIOPSIA:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_biopsia" id="baja_biopsia" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>OBSERVACIONES:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_observaciones" id="baja_observaciones" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%" colspan="4"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                                                                <td width="50%" colspan="5">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="100%" colspan="9">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="baja_diag_endoscopico" id="baja_diag_endoscopico" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                    </div>
                                                  </fieldset>
                                                </div>
                                                <div class="tab-pane fade" id="tab_alta">
                                                  <fieldset>
                                                    <div class="panel-body">
                                                      <div class="row">
                                                        <table class="table table-striped table-list-search" id="tblGastroAlta" cellspacing="0" width="100%" style="margin-top:5px;">
                                                          <tfoot>
                                                          </tfoot>
                                                          <tbody>
                                                            <tr>
                                                                <td width="15%"><b>INDICACIONES:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_indicaciones" id="alta_indicaciones" cols="30" rows="1"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>MEDICACION:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_medicacion" id="alta_medicacion" cols="30" rows="1"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>HALLAZGOS:</b></td>
                                                                <td width="85%" colspan="8">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>ESOFAGO:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_esofago" id="alta_esofago" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>ESTOMAGO:</b></td>
                                                                <td width="85%" colspan="8">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Cardias:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_cardias" id="alta_cardias" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Fondo:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_fondo" id="alta_fondo" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Cuerpo:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_cuerpo" id="alta_cuerpo" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Antro:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_antro" id="alta_antro" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>DUODENO:</b></td>
                                                                <td width="85%" colspan="8">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Bulbo:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_bulbo" id="alta_bulbo" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>Segunda porción:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_seg_porcion" id="alta_seg_porcion" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>BIOPSIA:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_biopsia" id="alta_biopsia" cols="30" rows="1"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>COMPLICACIONES</b></td>
                                                                <td width="85%" colspan="4">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_complicaciones" id="alta_complicaciones" cols="30" rows="1"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%"><b>OBSERVACIONES:</b></td>
                                                                <td width="85%" colspan="8">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_observaciones" id="alta_observaciones" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%" colspan="3"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                                                                <td width="50%" colspan="6">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="100%" colspan="9">
                                                                  <textarea style="margin:10 20px;width:95%" class="form-control" name="alta_diag_endoscopico" id="alta_diag_endoscopico" cols="30" rows="2"></textarea>
                                                                </td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                    </div>
                                                  </fieldset>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>                                                                         
                                      <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                              <label>EXAMEN FISICO</label>
                                              <textarea class="form-control" cols="5" id="examen_fisico" name="examen_fisico"
                                                placeholder="Examen Físico"></textarea>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <label>EXAMEN CLINICO</label>
                                            <textarea class="form-control" cols="5" id="examen_clinico" name="examen_clinico"
                                              placeholder="Examen Clínico"></textarea>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <label>EXAMENES AUXILIARES</label>
                                            <textarea class="form-control" cols="5" id="examen_auxiliar" name="examen_auxiliar"
                                              placeholder="Examenes auxiliares"></textarea>
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
                                          <textarea class="form-control" cols="5" id="tratamiento_auxiliar" name="tratamiento_auxiliar"
                                            placeholder="Tratamiento auxiliar"></textarea>
                                        </div>
                                      </div>
                                    </div>
			                           </div>
                                </fieldset>
                                <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>DIAGNÓSTICOS</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
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
                                            <th width="90%">Diagnostico</th>
                                            <th width="10%">Tipo</th>
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
                              <fieldset>
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
                                            <th width="45%">Diagnosticos</th>
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
                              </fieldset>
                              <?php if($nivel != 3): ?>
                              <fieldset id="modalReferencia">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REFERENCIA</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                                      <div class="form-group">
                                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                            <label>REFERIR CONSULTA</label>
                                          </div>
                                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                            <button type="button" id="addreferencia" class="btn btn-lg btn-toggle"
                                              data-toggle="button" aria-pressed="false" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </div>
                                         <div class="col-md-8" style="padding-top:5px;">
  <div id="divreferencia" style="display:none">
  
    <div class="form-group row" style="margin-bottom: 10px;">
      <label class="col-md-3 control-label">SERVICIO DE REFERENCIA</label>
      <div class="col-md-9">
        <select class="form-control input-sm" onclick="servicio_imag_lab();" id="servicio_referencia" name="servicio_referencia">
          <option value="" selected>--SELECCIONAR--</option>
        </select>
      </div>
    </div>

    <div class="form-group row" style="margin-bottom: 10px;">
      <label class="col-md-3 control-label">COMENTARIO DE LA REFERENCIA</label>
      <div class="col-md-9">
        <textarea class="form-control input-sm" id="texto_referencia" name="texto_referencia" placeholder="Texto de referencia"></textarea>
      </div>
    </div>

    <div class="form-group row" style="margin-bottom: 10px;">
      <label class="col-md-3 control-label">UPSS</label>
      <div class="col-md-9">
        <select class="form-control input-sm" id="upss_referencia" name="upss_referencia">
          <option value="">--SELECCIONAR--</option>
        </select>
      </div>
    </div>

  </div>
</div>
                                         
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <?php endif; ?>
                              <fieldset id="modalContraReferencia">
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>CONTRA REFERENCIA</b>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                                        <div class="form-group">
                                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                            <label>CONTRA REFERIR CONSULTA</label>
                                          </div>
                                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                            <button type="button" id="addcontrareferencia" class="btn btn-lg btn-toggle"
                                              data-toggle="button" aria-pressed="false" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </div>
                                          <div id="divcontrareferencia" style="display:none">
                                            <div class="col-md-8">
                                            <div class="col-md-5"
                                              style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                              <label>SERVICIO DE LA IPRESS PARA LA CONTRAREFERENCIA</label>
                                            </div>
                                            <div class="col-md-7" style="margin-left:0px;padding-left:0px">
                                              <select class="form-control input-sm" id="servicio_contrareferencia" name="servicio_contrareferencia">
                                                <option value="" selected="">--SELECCIONAR--</option>
                                              </select>
                                            </div>
                                            </div>

                                            <div class="col-md-4"></div>
                                            <div class="col-md-8">
                                            <br>
                                            <div class="col-md-5"
                                              style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                              <label>COMENTARIO DE LA CONTRA REFERENCIA</label>
                                            </div>
                                            <div class="col-md-7" style="margin-left:0px;padding-left:0px">
                                                <textarea class="form-control" cols="5" id="texto_contrareferencia" name="texto_contrareferencia"
                                                    placeholder="Detallar si necesita realizarce algun Procedimiento"></textarea>
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
                                        <b>PLAN DE TRABAJO</b>
                                      </div>
                                    </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-12">
                                            <label>PLAN DE TRABAJO</label>
                                            <textarea class="form-control" cols="5" id="plan_trabajo" name="plan_trabajo"
                                              placeholder="Plan de Trabajo"></textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </fieldset>
                                {{-- BUG --}}
                                <div id="divDetailPuerpera" style="display: none;" class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>PUERPERA</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <label>FECHA DE PARTO:</label>
                                          <p id="fecha_parto_detail"></p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div id="divDetailGestation" style="display: none;" class="panel panel-info panel-bordered panel-sm" >
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>GESTACIÓN</b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <label>ESTABLECIMIENTO:</label>
                                          <p id="establecimiento_nombre_detail"></p>
                                      </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                          <label>ULTIMA MESTRUACIÓN:</label>
                                          <p id="fech_ult_regla_format_detail"></p>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                          <label>FECHA PROBABLE DE PARTO:</label>
                                          <p id="fech_prob_parto_format_detail"></p>
                                      </div>  
                                      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                          <label>SEMANAS DE GESTACIÓN:</label>
                                          <p id="semanas_gestacion_detail"></p>
                                      </div>  
                                      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                          <label>N° DE CONTROLES PRENATALES:</label>
                                          <p id="num_controles_detail"></p>
                                      </div> 
                                      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                          <label>DUDA:</label>
                                          <p id="duda_detail"></p>
                                      </div>  
                                    </div>
                                  </div>
                                </div>

                                {{-- Procedimientos programados: solo IPRESS de nivel 3 o establecimiento 76 --}}
                                @if (\App\Models\Establecimiento::ppHabilitado(Auth::user()->id_establecimiento))
                                <fieldset>
                                  <fieldset>
                                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                                      <div class="form-group">
                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label>PROCEDIMIENTOS PROGRAMADOS</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button type="button" id="addProcedimientoProgramado" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                  <fieldset id="fsProcedimientosProgramados" style="display: none;">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>PROCEDIMIENTO PROGRAMADO</b>
                                        </div>
                                      </div>
                                      <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-sm-12">
                                            <button type="button" id="addProcProgra" class="btn btn-primary">Agregar Procedimiento</button>
                                          </div>
                                        </div>
                                        <div id="tblSolicitudPp"></div>
                                      </div>
                                    </div>
                                  </fieldset>
                                </fieldset>
                                @endif

                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label>DERIVAR CONSULTA</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button type="button" id="addDerivarServicio" class="btn btn-lg btn-toggle"
                                            data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>
                                        </div>
                                        <div id="divDerivarServicio" style="display:none">
                                          <div class="col-md-1"
                                            style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                            <label>SERVICIO</label>
                                          </div>
                                          <div class="col-md-4" style="margin-left:0px;padding-left:0px">
                                            <select class="form-control" name="id_servicio_derivar" id="id_servicio_derivar"
                                              tabindex="8" style="width: 100%">
                                              <option value="0">--Seleccionar--</option>
                                              <?php foreach ($consultorio as $row) : ?>
                                              <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
                                              <?php endforeach; ?>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                                <fieldset>
                                  <div id="registrar_cita_medico">
                                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                                      <div class="form-group" id="registrar_cita_medico">
                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                          <label>REGISTRAR CITA</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button tabindex="69" type="button" id="addRegistrarCita" class="btn btn-lg btn-toggle"
                                            data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>
                                          <input type="hidden" name="registrar_cita" id="registrar_cita" value="true">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>

                              {{-- -------------- SECCION IRWIN GESTANTE--------- --}}
                              <fieldset>
                                <div style="display: none;" id="ocultar1" class="panel panel-info panel-bordered panel-sm panel-body">
                                  <div class="form-group">
                                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                      <label>GESTANTE</label>
                                    </div>
                                    <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                      <button tabindex="90" 
                                        type="button" 
                                        id="esta_gestando" 
                                        class="btn btn-lg btn-toggle" 
                                        data-toggle="button" 
                                        aria-pressed="false" 
                                        autocomplete="off" 
                                        onclick="toggleFields()"
                                      >
                                        <div class="handle"></div>
                                      </button>
                                    </div>
                                  </div>
                                <fieldset id="frsgetante" style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>SALUD MATERNA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <input type="hidden" id="id_gestacion" name="id_gestacion">
                                      <div class="row">
                                        <div class="col-sm-2" style="margin-left: 1%;">
                                          <div class="form-group">
                                            <label for="ultimaMestruacion">Última menstruacion:</label>
                                            <input readonly type="text" id="ultimaMestruacion" name="ultimaMestruacion" class="form-control input-sm datepicker" style="width: 90%;">
                                          </div>
                                        </div>

                                        <div class="col-sm-3">
                                          <div class="form-group">
                                            <label for="parto_probable">Fecha probable de parto:</label>
                                            <input readonly type="text" id="parto_probable" name="parto_probable" class="form-control input-sm datepicker" style="width: 90%;">
                                          </div>
                                        </div>
                                    
                                        <div class="col-sm-3">
                                          <div class="form-group">
                                            <label for="semana_gesta">Semanas de gestacion</label>
                                            <input readonly type="text"
                                            id="semana_gesta"
                                            name="semana_gesta"
                                            class="form-control input-sm"
                                            style="width: 90%;">
                                          </div>
                                        </div>
                                        

                                        <div class="col-sm-3">
                                          <div class="form-group">
                                            <label for="control_prenatal">N ° DE CONTROLES PRENATALES</label>
                                            <input type="text" 
                                              id="control_prenatal" 
                                              name="control_prenatal"
                                              class="form-control input-sm" 
                                              style="width: 90%;" 
                                              placeholder="Control prenatal"  
                                              title="" 
                                              required
                                              maxlength="2"
                                              >
                                              <small id="alerta_control_prenatal"></small>
                                          </div>
                                        </div>
                                    
                                        <div class="col-sm-3"style="margin-left: 1%;">
                                          <div class="form-group">
                                            <label>Duda:</label><br>
                                            <label class="radio-inline"><input type="radio" name="duda" value="1"> Si</label>
                                            <label class="radio-inline"><input type="radio" name="duda" value="0"> No</label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                                </div>
                              </fieldset>
                              
                              {{-- -------------- SECCION IRWIN GESTANTE END--------- --}}
                              @if(Auth::user()->tipo_registro!=4)
                                <!--fieldset>
  				                        <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
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
                                                <table class="display" id="tblInterconsulta" cellspacing="0" width="100%" style="margin-top:5px;">
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
                                  </div>
                                </fieldset-->
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                      <div class="form-group">
	                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
	                                    <label>PRESCRIBIR RECETA</label>
	                                  </div>
	                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
	                                    <button type="button" id="addRecetaVale" class="btn btn-lg btn-toggle"
	                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
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
                                          <input type="text" class="form-control datepicker"
                                            value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
                                            name="fecha_expedicion" id="fecha_expedicion" tabindex="2"
                                            data-date-end-date="0d" autocomplete="off" />
                                        </div>
                                        <div class="col-md-5">
                                          <label>FARMACIA</label>
                                          <select class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                                            style="width:100%" onchange="getProducto()">
                                            <option value="">-Seleccionar-</option>
                                            <?php if(Auth::user()->id_establecimiento != 76):?>
                                                <?php foreach($farmacias as $row):?>
                                                <option value="<?php echo $row->id?>"
                                                  <?php if($row->id==Auth::user()->id_farmacia) echo "selected='selected'"?>>
                                                  <?php echo $row->nombre?></option>
                                                <?php endforeach;?>
                                              <?php else:?>
                                                <option value="121" <?php if(Auth::user()->id_farmacia==121) echo "selected"; ?>>Farmacia PIP I del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="3" <?php if(Auth::user()->id_farmacia==3) echo "selected"; ?>>Farmacia Central del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="10" <?php if(Auth::user()->id_farmacia==10) echo "selected"; ?>>Farmacia Oncologica del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="21" <?php if(Auth::user()->id_farmacia==21) echo "selected"; ?>>Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="17" <?php if(Auth::user()->id_farmacia==17) echo "selected"; ?>>Farmacia TARGA del Hospital Nacional PNP Luis N. Saenz</option>
                                                <option value="109" <?php if(Auth::user()->id_farmacia==109) echo "selected"; ?>>Almacén Rehabilitacion del Hospital Nacional PNP Luis N. Saenz</option>
                                  				<option value="132" <?php if(Auth::user()->id_farmacia==132) echo "selected"; ?>>Farmacia de Preparados Farmacéutico No Estériles del Hospital Nacional PNP Luis N. Saenz</option>
                                              <?php endif;?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-sm-2"
                                          style="padding-top:5px;margin-right:0px;padding-right:0px;font-size:13px">
                                          <label>PACIENTE CONTINUADOR</label>
                                        </div>
                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                          <button type="button" id="addCondicion" class="btn btn-lg btn-toggle"
                                            data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>
                                        </div>
                                        <input type="hidden" name="condicion" id="condicion" value="0">
                                        <div id="divCondicion" style="display:none">
                                          <div class="col-md-2"
                                            style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px;font-size:13px">
                                            <label>DIAS DE RECETAS</label>
                                          </div>
                                          <div class="col-md-1" style="margin-left:0px;padding-left:0px">
                                            <select class="form-control" name="cantidadReceta" id="cantidadReceta"
                                              tabindex="8" style="width: 100%">
                                              <option value="0"></option>
                                              <option value="1">30</option>
                                              <option value="2">60</option>
                                              <option value="3">90</option>
                                              <option value="4">120</option>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                        <div class="form-group">
                                          <div class="col-sm-12">
                                      <table>
                                        <tr>
                                          <td>
                                            <button type="button" id="addProducto" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                          </td>
                                          <td style="padding-left: 10px;">
                                            <div id="divCopiarUltimaReceta" style="display:none;">
                                                <button type="button"
                                                    id="btnCopiarUltimaReceta"
                                                    class="btn btn-info"
                                                    onclick="getLastRecipeConsultaExterna()"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Copiar Última Receta">
                                                    <i class="fa fa-files-o"></i>
                                                </button>
                                            </div>
                                        </td>
                                        </tr>

                                      </table>
                                          </div>
                                        </div>
                                      <br />
                                      <div style="display: none">
                                        <select class="form-control" id="cmbMedicamentoTemp" tabindex="16"
                                          style="width: 500px">
                                          <option value="">Seleccionar Producto</option>
                                        </select>
                                      </div>
                                      <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                      <input type="hidden" value="1" name="receta_prescrita" id="receta_prescrita">
                                        <div class="form-group">                                    
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
                                </div>
                              </fieldset>
                              </fieldset>

                              {{--CENTRO QUIRURGICO INICIO--}}
                              <div class="panel panel-info panel-bordered panel-sm panel-body" id="fsIntervencionQuirugica" sytle="display:none;">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>INTERVENCIÓN QUIRÚRGICA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="90" type="button" id="addInterQuirurgica" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                    <input type="hidden" id="has_intervencion_quirurgica" name="has_intervencion_quirurgica" value="0">
                                  </div>
                                </div>
                                <fieldset id="fsInterQuirurgica" style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>RIESGO QUIRÚRGICO </b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div style="display: flex; flex-direction: column; gap: 1rem; padding-bottom: 3rem;">
                                        <div style="display: flex; gap: 0.5rem;">
                                          <div class="col-sm-1">
                                            <input type="checkbox" name="rq_cardiologia" id="rq_cardiologia" value="1" class="" checked/>
                                          </div>
                                          <div class="col-sm-10">
                                            <label>CARDIOLOGÍA</label>
                                          </div>
                                        </div>
                                        <div style="display: flex; gap: 0.5rem;">
                                          <div class="col-sm-1">
                                            <input type="checkbox" name="rq_neumologia" id="rq_neumologia" value="1" class="" checked/>
                                          </div>
                                          <div class="col-sm-10">
                                            <label>NEUMOLOGÍA</label>
                                          </div>
                                        </div>
                                        <div style="display: flex; gap: 0.5rem;">
                                          <div class="col-sm-1">
                                            <input type="checkbox" name="rq_anestesiologia" id="rq_anestesiologia" value="1" class="" checked/>
                                          </div>
                                          <div class="col-sm-10">
                                            <label>ANESTESIOLOGÍA</label>
                                          </div>
                                        </div>
                                      </div>

                                      <!-- <div class="panel panel-info panel-bordered panel-sm panel-body">
                                        <div class="form-group">
                                          <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                            <label>INTERCONSULTA PRE-QUIRÚRGICA</label>
                                          </div>
                                          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                            <button tabindex="90" type="button" id="addInterPreQuirurgica" class="btn btn-lg btn-toggle"
                                              data-toggle="button" aria-pressed="false" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </div>
                                        </div>
                                        <fieldset id="fsInterPreQuirurgica" style="display:none">
                                          <div class="panel panel-info panel-bordered panel-sm">
                                            <div class="panel-heading">
                                              <div class="panel-title text-center">
                                                <b>INTERCONSULTA</b>
                                              </div>
                                            </div>
                                            <div class="panel-body">
                                              
                                              <table>
                                                <tr>
                                                  <td>
                                                    <button tabindex="" type="button" id="addExamenInterPreQuirurgica" class="btn btn-primary"
                                                      data-toggle="modal" data-target="#addClassModal">Agregar</button>
                                                  </td>
                                                </tr>
                                              </table>
                                              <br />
                                              <div class="form-group" style="display:none">
                                                <select class="form-control" id="cmbMedicamentoTemp_preqx" tabindex="" style="width: 500px">
                                                  <option value="">Seleccionar Servicio</option>
                                                  <?php foreach ($consultorio as $row) : ?>
                                                  <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
                                                  <?php endforeach; ?>
                                                </select>
                                              </div>

                                              <table class="display" id="tblExamenInterPreQuirurgica" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <thead>
                                                  <tr>
                                                    <th width="30%">SERVICIO</th>
                                                    <th width="70%">MOTIVO DE INTERCONSULTA</th>
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
                                      </div> -->

                                      <div class="panel panel-info panel-bordered">
                                        <div class="panel-heading" style="text-align: center">
                                          <p class="panel-title" style="font-size: 14px"><b>PROCEDIMIENTOS DE INTERVENCIÓN</b></p>
                                        </div>
                                        <div class="panel-body" style="font-size: 12px">
                                          <button type="button" class="btn btn-success btn-sm" id="btnAddRowTableProcedure_aa_iq" style="margin-bottom: 10px"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Procedimiento</button>
                                          <table class="table table-striped" id="tableProcedures_iq">
                                            <thead id="tableProceduresHeader_iq">
                                              <tr>
                                                <th style="width: 95%">Código y Descripción</th>
                                                <th style="width: 5%">Eliminar</th>
                                              </tr>
                                            </thead>
                                            <tbody></tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>
                              </div>
                              {{----------------------------}}

                              <fieldset id="fsProgramacionQuirugica" style="display: none;">
                                @include('frontend.atenciones.atencion_adicional.components.panel_programacion_quirurgica')
                              </fieldset>
                              <div class="panel panel-info panel-bordered panel-sm panel-body" id="fsBloqueInterconsulta" style="display: none;">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>INTERCONSULTA PRE-QUIRÚRGICA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button tabindex="90" type="button" id="addInterPreQuirurgica" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="fsInterPreQuirurgica" style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>INTERCONSULTA</b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      
                                      <table>
                                        <tr>
                                          <td>
                                            <button tabindex="" type="button" id="addExamenInterPreQuirurgica" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <div class="form-group" style="display:none">
                                        <select class="form-control" id="cmbMedicamentoTemp_preqx" tabindex="" style="width: 500px">
                                          <option value="">Seleccionar Servicio</option>
                                          <?php foreach ($consultorio as $row) : ?>
                                          <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
                                          <?php endforeach; ?>
                                        </select>
                                      </div>

                                      <table class="display" id="tblExamenInterPreQuirurgica" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                            <th width="30%">SERVICIO</th>
                                            <th width="70%">MOTIVO DE INTERCONSULTA</th>
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

                              <div class="panel panel-info panel-bordered panel-sm panel-body" id="panelLaboratorioOrden">
                                <div class="form-group">
                                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                    <label>EXAMEN DE LABORATORIO</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button type="button" id="addExamenLab" class="btn btn-lg btn-toggle"
                                      data-toggle="button" aria-pressed="false" autocomplete="off">
                                      <div class="handle"></div>
                                    </button>
                                  </div>
                                </div>
                                <fieldset id="fsExamenLab" style="display:none">
                                  <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>EXAMEN LABORATORIO </b>
                                      </div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-md-4">
                                          <button type="button" id="addExamenLab2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                        </div>
                                        <div class="col-md-4">
                                          <?php $display = "none"; ?>
                                          <div id="section_perfil_preoperatorio" style="display: {{ $display }};">
                                            <div class="col-md-6">
                                              <label for="id_perfil_preoperatorio">Perfil Preoperatorio:</label>
                                            </div>
                                            <div class="col-md-6">
                                              <select name="id_perfil_preoperatorio" id="id_perfil_preoperatorio" class="form-control">
                                                <option value="0">-Seleccionar-</option>
                                                <option value="1">CIRUGÍA MAYOR</option>
                                                <option value="2">CIRUGÍA MENOR</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                        @if (intval($establecimiento->id) === 76)
                                          <div class="col-md-4" style="text-align: right;">
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
                                        <!--<table style="width: 100%;">
                                          <tr>
                                            <th width="5%">Observación</th>
                                            <th width="95%"></th>
                                          </tr>
                                          <tr>
                                            <td><input type="text" class="form-control" id="lab_orden_toma_muestra" maxlength="8" readonly /></td>
                                            <td><input type="text" class="form-control" name="lab_orden_observacion" maxlength="255" /></td>
                                          </tr>
                                        </table> -->
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
                                    <label>EXAMEN DE IMAGENOLOGIA</label>
                                  </div>
                                  <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                    <button type="button" id="addExamenRad" class="btn btn-lg btn-toggle"
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
                                            <button type="button" id="addExamenRad2" class="btn btn-primary"
                                              data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
                                          </td>
                                        </tr>
                                      </table>
                                      <br />
                                      <table class="display" id="tblExamenRad" cellspacing="0" width="100%"
                                        style="margin-top:5px;">
                                        <thead>
                                          <tr>
                                              <th width="35%">Examen</th>
                                              <th width="3%">Comparativo</th>
                                              <th width="3%">Lado</th>
                                              <th width="20%">Observacion</th>
                                              <th width="5%">Cant</th>
                                              <th width="20%">Diagnostico</th>
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
                              @endif

                              <fieldset class="row" id="fsFechaProbableIntervencionQuirurgica" hidden>
                                <div class="col-md-offset-3 col-md-6" >
                                  <div class="panel panel-info panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center"><b>FECHA PROBABLE DE INTERVENCIÓN QUIRÚRGICA</b></div>
                                    </div>
                                    <div class="panel-body">
                                      <div class="form-group">
                                        <div class="col-md-offset-4 col-md-4">
                                          <input type="text" id="fecha_probable_pq" name="fecha_probable_pq" class="form-control" readonly style="background-color: #ffffff">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>

                              <input class="btn btn-primary pull-right" value="GUARDAR" name="crea" type="button" form="prestacionescrea" id="btnPrestacion" />
                              
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab3default">
              <fieldset>
              <ul class="nav nav-tabs" id="tabs">
                <li class="active"><a href="#tab10default" data-toggle="tab" style="font-size:12px">AMBULATORIO</a></li>
                <li><a href="#tab11default" data-toggle="tab" style="font-size:12px">EMERGENCIA</a></li>
                <li><a href="#tab12default" data-toggle="tab" style="font-size:12px">HOSPITALIZACION</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane fade in active marca_agua_container" id="tab10default" style="position:relative;">
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
                    <div class="panel panel-info panel-bordered panel-sm">
                      <div class="panel-heading">
                        <div class="panel-title text-center">
                          <b>HISTORIAL DE EVOLUCIONES MEDICAS</b>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-md-12">
                            <div id='divEvaluacioneHospitalizacion'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
              {{-- <fieldset>
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
              </fieldset> --}}
            </div>
            <div class="tab-pane fade" id="tab4default">
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
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE EXAMENES DE LABORATORIO DE NIVEL II - I</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divLaboratorioNivelI_II'></div>
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
            <div class="tab-pane fade" id="tab_RC">
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE REFERENCIAS / CONTRAREFERENCIAS</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divRC1'></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            {{-- Procedimientos programados --}}
            <div class="tab-pane fade marca_agua_container" id="tab_INFORMES_PP" style="position:relative;">
              <div class="marca_agua_overlay" id="marca_agua_overlay_hist_pp"></div>
              <fieldset>
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>HISTORIAL DE INFORMES DE PROCEDIMIENTO PROGRAMADO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <div id='divINFORMES_PP'>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="box box-primary">
                              <br>
                              <div class="box-body">
                                <div class="table-responsive">
                                  <table class="table table-bordered table-sm" id="tblHistorialInformes" style="width: 100% !important;">
                                    <thead>
                                      <tr>
                                        <th>Establecimiento</th>
                                        <th class="text-center">Nº de Doc (Paciente)</th>
                                        <th>Servicio solicitante</th>
                                        <th>Procedimiento solicitado</th>
                                        <th class="text-center">Fecha de atención</th>
                                        <th>Médico</th>
                                        <th class="text-center">Ver atención</th>
                                        <th class="text-center">Ver informe</th>
                                      </tr>
                                    </thead>
                                    <tbody id="tblBodyInformes" style="padding-top:32px">
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

<div class="modal fade" id="modalHistorialProductos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="panel panel-info h-100 panel-bordered panel-sm" style="margin-bottom: 0; border-radius: 4px 4px 0 0;">
        <div class="panel-heading">
          <div class="panel-title text-center">
            <b>HISTORIAL DE PRODUCTOS ENTREGADOS</b>
          </div>
        </div>
      </div>
      
      <div class="modal-body">
        <div class="table-responsive">
         <table class="table table-bordered table-striped text-center" style="font-size: 12px;">
          <thead>
              <tr>
                  <th class="col-numero">N°</th>
                  <th class="col-codigo">COD DE PRODUCTO</th>
                  <th class="col-descripcion">DESCRIPCIÓN</th>
                  <th class="col-fecha">FECHA DE PRESCRIPCIÓN</th>
                  <th class="col-fecha">FECHA DE DISPENSACIÓN</th>
              </tr>
          </thead>
          <tbody id="tbodyhistorial">
          </tbody>
        </table>
        </div>
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn btn-success" data-dismiss="modal" style="background-color: #008000; border-color: #008000; padding: 6px 30px; font-weight: bold;">
          CERRAR
        </button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header" id="modal-header">
                    <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">Ã—</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
                    <div id="alerta" class="alert alert-danger"></div>
                </div>
                <div class="modal-body">
                    <table border="0px" id="tabla_lote">
                        <thead>
                            <tr>
                                <th style="width:10%">Código</th>   
                                <th style="width:40%">Nombre</th> 
                                <th style="width:10%">Lote</th> 
                                <th style="width:10%">Reg. San.</th> 
                                <th style="width:10%">Precio</th>
                                <th style="width:10%">Fec. ven.</th>
                                <th style="width:10%">Cant. Actual.</th> 
                                <th style="width:10%">Cant. Entregar</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>                        
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">                        
                        <div class="btn-group" role="group">
                            <div class="col-md-6 text-center">
                                <button type="button" onclick="sumarCantidadLotes(0)" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar</button>
                            </div>
                            <div class="col-md-6 text-center">
                                <button type="button" onclick="sumarCantidadLotes(1)" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar y Agregar Nueva Fila</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
  <div class="modal fade" id="modalTipoMuestrabMicrobiologia" role="dialog">
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
  <div class="modal fade" id="modalEditTipoMuestrabMicrobiologia" role="dialog">
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
              </form>
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

{{-- MODAL SOLICITUD PARA ESTUDIO CITOQUÍMICO  LÍQUIDOS BIOLÓGICOS --}}
@include('frontend.laboratorios.modal_solicitud_citoquimicos_liquidos_biologicos')
{{-- MODAL SOLICITUD PARA TEST DE TOLERANCIA A LA GLUCOSA ORAL  --}}
@include('frontend.laboratorios.modal_solicitud_test_tolerancia_glucosa_oral')

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

{{-- Procedimientos programados: ver atención desde el historial (mismo modal que all_procprogramados) --}}
<div class="modal fade" id="modalVerAtencionPP" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="width: 1200px; max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">VER ATENCIÓN DEL PROCEDIMIENTO PROGRAMADO</h5>
      </div>
      <div class="modal-body" style="overflow-y: auto; max-height: 80vh;">
        <div id="modalVerAtencionPPBody"></div>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection


