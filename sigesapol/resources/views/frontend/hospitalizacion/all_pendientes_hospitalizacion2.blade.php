<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master3')
@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')
<style>
  .table-sortable tbody tr {
    cursor: move;
  }

  .margin {

    margin-bottom: 20px;
  }

  .margin-buscar {
    margin-bottom: 5px;
    margin-top: 5px;
  }

  .row {
    margin-top: 10px;
    padding: 0 10px;
  }

  .clickable {
    cursor: pointer;
  }

  .panel-heading div {
    margin-top: -18px;
    font-size: 15px;
  }

  .panel-heading div span {
    margin-left: 5px;
  }

  .panel-body {
    display: block;
  }

  .dataTables_filter {
    display: none;
  }

  .help-block {
    display: block;
    margin-top: 1px;
    margin-bottom: 10px;
    color: #737373;
  }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
  //--------------------------------------------------------------------------
  
    $(document).ready(function() {  
	 

		$('#fecha_ini').datepicker({
			useCurrent: false,
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: "bottom left"
		}).on('changeDate', function(e) {
			console.log('click sobre fecha_ini');
			var fechaDesdeIni = $('#fecha_ini').val();

			$('#fecha_ini').change(function() {
				var fechaDesde = $('#fecha_ini').val();
				var fechaHasta = $('#fecha_fin').val();
				f1 = fechaDesde.split("-");
				f2 = fechaHasta.split("-");
				f3 = new Date();
				var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
				var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

				if (f1 > f2) {
					bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
					$('#fecha_ini').val(fechaDesdeIni);
					return false;
				}else{
                // fn_ListarBusqueda();
        }
			});
		}); 
    
		$('#fecha_fin').datepicker({
			useCurrent: false,
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: "bottom left"
		}).on('changeDate', function(e) {
			console.log('click sobre fecha_fin');
			var fechaHastaIni = $('#fecha_fin').val();
			$('#fecha_fin').change(function() {
				var fechaDesde = $('#fecha_ini').val();
				var fechaHasta = $('#fecha_fin').val();
				f1 = fechaDesde.split("-");
				f2 = fechaHasta.split("-");
				f3 = new Date();
				var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
				var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

				if (f2 < f1) {
					bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
					$('#fecha_fin').val(fechaHastaIni);
					return false;
				}else{
                // fn_ListarBusqueda();
        }

			});
		}); 

        $("#dni_beneficiario").keypress(function(e) {
            if (e.which == 13) {
                // fn_ListarBusqueda();
            }
        });
        $('#id_servicio_destino').on('change', function() { 
            // fn_ListarBusqueda();
        });

        $("#id_servicio_destino").select2({
          width: 'resolve'
        });
    });
 
    function soloNumeros(e) {
        var key = window.Event ? e.which : e.keyCode;
        //alert(key);
        return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }



    $('#btnBuscar').click(function() {
        fn_ListarBusqueda();
    });

    $(document).ready(function() {
        fn_ListarBusqueda();
    });
 
    var m = 0;

    function datatablenew() {
      let id_establecimiento = $("#id_establecimiento").val();
      let id_farmacia = $("#id_farmacia").val();
      let id_user = $("#id_user").val();
      let estado = $('#estado').val();

        var oTable1 = $('#tblEmergenciaTriaje').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_emergencia_a_hospitalizar_ajax",
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bSort": false,
            "info": true,
            "language": {
                "url": "/js/Spanish.json"
            },
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "lengthMenu": [
                [10, 50, 100, 200, 60000],
                [10, 50, 100, 200, "Todos"]
            ],
            "aoColumns": [{}, ],
            "dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnDrawCallback": function(json) {
                $('[data-toggle="tooltip"]').tooltip();
            },

            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                var sEcho = aoData[0].value;
                var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar = aoData[4].value;
                var fecha_ini = $('#fecha_ini').val();
                var fecha_fin = $('#fecha_fin').val();
                var dni_beneficiario = $('#dni_beneficiario').val();
                let nombre_beneficiario = $('#nombre_beneficiario').val();
                var id_servicio_destino = $('#id_servicio_destino').val();
                var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": {
                        NumeroPagina: iNroPagina,
                        NumeroRegistros: iCantMostrar,
                        fecha_ini: fecha_ini,
                        fecha_fin: fecha_fin,
                        dni_beneficiario: dni_beneficiario,
                        nombre_beneficiario: nombre_beneficiario,
                        id_servicio_destino: id_servicio_destino,
                        id_establecimiento: id_establecimiento, 
                        estado: estado,
                        _token: _token
                    },
                    "success": function(result) {
                        fnCallback(result);
                    },
                    "error": function(msg, textStatus, errorThrown) {
                        //location.href="login";
                    }
                });
            },

            "aoColumnDefs": [{
                    "mRender": function(data, type, row) {
                        var id = "";
                        if (row.id != null) id = row.id;
                        return id;
                    },
                    "bSortable": false,
                    "aTargets": [0]
                },
                {
                    "mRender": function(data, type, row) {
                        var establecimiento_origen = "";
                        if($('#estado').val()==0){ 
                          establecimiento_origen = row.establecimiento_origen;
                        }else if($('#estado').val()==1 || $('#estado').val()==-1){
                          establecimiento_origen = row.establecimiento;
                        }
                        return establecimiento_origen;
                    },
                    "bSortable": false,
                    "aTargets": [1]
                },
                {
                    "mRender": function(data, type, row) {
                        let tipo_atencion = "";
                        if($('#estado').val() == 0) { 
                          tipo_atencion = row.tipo_atencion;
                        } else if($('#estado').val() == 1 || $('#estado').val() == -1) {
                          tipo_atencion = row.tipo_origen;
                        }
                        return tipo_atencion;
                    },
                    "bSortable": false,
                    "aTargets": [2]
                },
                {
                    "mRender": function(data, type, row) { 
                        if($('#estado').val()==0){ 
                          let medico_egreso = row.medico_paterno + ' ' + row.medico_materno + ', ' + row.medico_nombre;
                          return  '<b>Responsable:</b> '+medico_egreso+
                                  '<br><b>Fecha/Hora:</b> '+row.fecha_alta_medica + ' &nbsp;' + row.hora_alta_medica;
                        }else if($('#estado').val()==1 || $('#estado').val()==-1){ 
                          let resposable_admision = row.usuario_admision_paterno + ' ' + row.usuario_admision_materno + ', ' + row.usuario_admision_nombre;
                          var fecha_registro = '<b>Responsable: </b>'+resposable_admision+'<br>'+
                              '<b>Fecha/Hora: </b>'+row.fecha_ingreso + ' &nbsp;' + row.hora_ingreso;
                          return fecha_registro;
                        }
                    },
                    "bSortable": false,
                    "aTargets": [3]
                },
                {
                    "mRender": function(data, type, row) {   
                      let paciente = row.paterno + ' ' + row.materno + ', ' + row.nombre;
                      if($('#estado').val()==0){ 
                        return '<b>Parentesco:</b> '+row.tipo_beneficiario
                          +'<br><b>Documento:</b> '+row.dni_beneficiario
                          +'<br><b>Nombres:</b> ' + paciente; 
                      }else if($('#estado').val()==1 || $('#estado').val()==-1){
                        return '<b>Parentesco: </b>'+row.tipo_beneficiario+'<br>'+
                        '<b>Documento: </b>'+row.dni_beneficiario +'<br>'+
                        '<b>Nombres: </b>'+ paciente;
                      } 
                    },
                    "bSortable": false,
                    "aTargets": [4]
                }, 
                {
                    "mRender": function(data, type, row) { 
                      if($('#estado').val()==0){  
                        return row.sexo;
                      }else if($('#estado').val()==1 || $('#estado').val()==-1){
                        var sexo = "";
                        if (row.sexo != null) sexo = '<b>Sexo:</b> '+row.sexo+'<br><b>Edad:</b> '+calcularEdadPersonaReturn(row.fecha_nac); ;
                        return sexo;
                      }
                    },
                    "bSortable": false,
                    "aTargets": [5]

                },
                {
                    "mRender": function(data, type, row) {
                      if($('#estado').val()==0){  
                        var edad = "";
                        if (row.edadpaciente != null) edad = calcularEdadPersonaReturn(row.fecha_nac); ;
                        return edad;
                      }else if($('#estado').val()==1 || $('#estado').val()==-1){
                        // var sexo = "";
                        // if (row.sexo != null) sexo = '<b>Sexo:</b> '+row.sexo+'<br><b>Edad:</b> '+calcularEdadPersonaReturn(row.fecha_nac); ;
                        // return sexo;
                        return row.servicio_hospitalizado;
                      }
                    },
                    "bSortable": false,
                    "aTargets": [6]

                },
                {
                    "mRender": function(data, type, row) {
                      if($('#estado').val()==0){  
                        //consultorio_especial es cuando es reproduccion humana viualizara de ginecologia
                        var consultorio_destino = "";
                        if (row.consultorio_destino != null && row.consultorio_especial == null) {
                          consultorio_destino = row.consultorio_destino;
                        }else if (row.consultorio_destino != null && row.consultorio_especial != null) {
                          consultorio_destino = row.consultorio_especial;
                        }
                        return consultorio_destino;
                      }else if($('#estado').val()==1 || $('#estado').val()==-1){ 
                        let ubicacion_fisico = '';
                        if (row.flag_clinica_dia == "0") {
                          ubicacion_fisico += '<b>Cuarto:</b> '+row.nombre_cuarto+'<br><b>Cama:</b> '+row.nombre_cama ; 
                        } else if (row.flag_clinica_dia == "1") {
                          ubicacion_fisico += 'CLINICA DE DIA';
                        }
                        return ubicacion_fisico;
                      }
                    },
                    "bSortable": false,
                    "aTargets": [7]

                }, 
                {
                    "mRender": function(data, type, row) {
                      var html = ''
                      if($('#estado').val()==1){  
                        html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Ver</b>">';
                        html += '<a href="/ver_hospitalizacion/'+<?php echo Auth::user()->id; ?>+'/' + row.id + '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
                      }
                      return html;
                    },
                    "bSortable": false,
                    "aTargets": [8],
                    "visible": ($('#estado').val()==1) ? true : false
                },
                {
                    "mRender": function(data, type, row) {
                      var html = '';
                      if (estado == "1") {
                        if (id_establecimiento != "75") {
                          html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Ver Atención</b>">';
                          html += '<a href="PdfHospitalizacionAdmision/'+row.id+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                        } else {
                          html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Ver Atención</b>">';
                          html += '<a href="imprimir_nuevo_hospitalizado/' + row.id + '/'+ id_farmacia + '/' + id_user + '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                        }
                      }
                      return html;
                    },
                    "bSortable": false,
                    "aTargets": [9],
                    "visible": ($('#estado').val()==1) ? true : false
                },
                {
                    "mRender": function(data, type, row) {
                      var html = '';
                      if($('#estado').val()==0){
                        if (row.id_tipo_atencion == 2) { // Emergencia
                          if(row.flag_firma != 0){
                            html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Nueva Admisión</b>">';
                            html += '<a href="/hospitalizacion_admision/' + row.id + '" class="btn btn-success btn-xs validar-hospitalizacion" data-id="' + row.id + '"><i class="fa fa-pencil-square-o"></i></a></div>';
                          }else{
                            html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>El alta debe estar firmada</b>">';
                            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-pencil-square-o"></i></button></div>';
                          }  
                        } else {
                          html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Nueva Admisión</b>">';
                          html += '<a href="/hospitalizacion_admision/' + row.id + '" class="btn btn-success btn-xs validar-hospitalizacion" data-id="' + row.id + '"><i class="fa fa-pencil-square-o"></i></a></div>';
                        }
                      }else if($('#estado').val()==1){ 
                        if (row.estado == 1) {
                          html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Anular Admisión</b>">';
                          html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" data-id_hopitalizacion="'+row.id+'" ';
                          html += 'id="anular_admision" name="anular_admision"><i class="fa fa-remove"></i></a></div>';
                        } else {
                          html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Anular Admisión</b>">';
                          html += '<button disabled class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button></div>';
                        }
                      }else if($('#estado').val()==-1){
                        html = '<b>Fecha:</b><br> '+row.fecha_anulacion_admision +' - '+row.hora_anulacion_admision+'<br>'+ 
                                  '<b>Responsable de Anulación:</b><br>'+row.usuario_anula_admision+'<br>'+
                                  '<b>Motivo de Anulación:</b><br> '+row.motivo_anulacion ; 
                      }
                      return html; 
                    },
                    "bSortable": false,
                    "aTargets": [10] 
                },
                {
                  "mRender": function(data, type, row) {
                    let html = '';
                    // console.log(estado);
                    if (row.flag_firma==1) {
                      html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Orden</b>">';
                      html += '<a href="/'+row.ruta_orden+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                    }
                    else{
                      //if(row.id_tipo_atencion == 2){
                        if(!row.id_intervencion){
                           html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Orden</b>">';
                          //html += '<a href="/hospitalizacion_orden/'+estado+'/'+row.id+'" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-download"></i></a></div>';
                          html += '<a href="/pdf_indicaciones/'+{{Auth::user()->id}}+'/'+row.id_ext+'" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-download"></i></a></div>';
                        }  
                      //}
                      /*if(row.id_tipo_origen == 2){
                        html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Orden</b>">';
                          html += '<a href="/pdf_indicaciones/'+{{Auth::user()->id}}+'/'+row.id_ext+'" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-download"></i></a></div>';
                      }*/
                    }
                    
                    return html; 
                  },
                  "bSortable": false,
                  "aTargets": [11] 
                },
                {
                  "mRender": function(data, type, row) {
                    let htmlString = "";
                    if (row.cod_siteds_susalud) {
                      if (row.cod_siteds_susalud[row.cod_siteds_susalud.length - 1] === "T" || String(row.cod_siteds_susalud).length <= 6) {
                        htmlString = `
                                <p data-toggle="tooltip" data-placement="top" title="Código Siteds Temporal">
                                    <span class="label label-warning" style='font-weight: lighter; font-size: 10px'>${(row.cod_siteds_susalud).toUpperCase()}</span>
                                </p>
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Regularizar</b>" class="contenido-btn-div">
                                    <button class="btn btn-primary btn-xs" onclick="regularizeCodeSiteds(${row.id}, '${row.id_establecimiento}', ${row.servicio_hospitalizado_id}, '${row.fecha_ingreso}')">
                                        <i class="fa fa-check-circle-o"></i> 
                                    </button>
                                </div>`;
                      } else {
                        htmlString = `<span class="label label-success" style='font-weight: lighter; font-size: 10px'>${(row.cod_siteds_susalud)}</span>`
                      }
                    }
                    return htmlString;
                  },
                  "bSortable": false,
                  "aTargets": [12],
                  "visible": ($('#estado').val()==1) ? true : false
                }
            ]
        });
    }

    function fn_ListarBusqueda() {
        datatablenew();
    }

    function anularAtencion(id_atencion) {

        bootbox.confirm({
            size: "small",
            message: "&iquest;Deseas finalizar la atenci&oacute;n de triaje?",
            callback: function(result) {
                if (result == true) {
                    fn_anularAtencion(id_atencion);
                }
            }
        });

    }

    $('#estado').change(function(){    
      // datatablenew();   
      if ($('#estado').val() == 0) { 
        // $('#columna0').text('');
        $('#columna1').text('Ipress Origen');  
        $('#columna2').text('Tipo de Atención');
        $('#columna3').text('Datos de Derivación');  
        $('#columna4').text('Datos del Paciente');  
        $('#columna5').text('Sexo');  
        $('#columna6').text('Edad');  
        $('#columna7').text('Servicio Destino');
        // $('#columna8').text('');
        $('#columna9').text('Pdf'); 
        $('#columna10').text('Admisión');
        // $('#columna11').text('');
      }else if ($('#estado').val() == 1 || $('#estado').val() == -1) { 
        // $('#columna0').text('');
        $('#columna1').text('Establecimiento');
        $('#columna2').text('Tipo de Atención');
        $('#columna3').text('Datos de Admisión');  
        $('#columna4').text('Datos del Paciente');  
        $('#columna5').text('Sexo/Edad');  
        $('#columna6').text('Servicio');  
        $('#columna7').text('Ubicación');  
        // $('#columna8').text('');
        $('#columna9').text('Pdf'); 
        $('#columna10').text('Anulación');
        // $('#columna11').text('');
      } 
    });
 
    function reporteAdmisionHopitalizacion(idUser) {
        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                let id_establecimiento = $('#id_establecimiento').val();
                let fecha_ini = $('#fecha_ini').val();
                let fecha_fin = $('#fecha_fin').val();
                let dni_beneficiario = $('#dni_beneficiario').val();
                let nombre_beneficiario = $('#nombre_beneficiario').val();
                let estado = $('#estado').val();
                let id_servicio_destino = $('#id_servicio_destino').val();
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "export_admin_hospitalizacion",
                    data: {
                        "id_establecimiento" : id_establecimiento,
                        "fecha_ini" : fecha_ini,
                        "fecha_fin" : fecha_fin,
                        "dni_beneficiario" : dni_beneficiario,
                        "nombre_beneficiario" : nombre_beneficiario,
                        // "id_servicio_destino" : id_servicio_destino,
                        "estado" : estado,
                        "id_servicio_destino" : id_servicio_destino,
                    },
                    success: function (response) {
                        console.log(response);
                        console.log(response.excelNameResponse);
                        if (response.excelNameResponse) {
                            // Obtener la URL del archivo del response
                            let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                            // Crear un enlace de descarga
                            let link = document.createElement('a');
                            link.href = fileUrl;
                            link.download = 'Exportar_Reportes_de_Evaluacion_.xlsx';
                            link.innerText = 'Descargar archivo';
                            // Simular un clic en el enlace para iniciar la descarga
                            let divLinkReporte = document.getElementById('link-reporte');
                            divLinkReporte.appendChild(link);
                            link.click();
                            divLinkReporte.removeChild(link);
                            Swal.hideLoading();
                            Swal.fire({
                                icon: 'success',
                                title: 'El reporte se ha generado correctamente!',
                                showConfirmButton: false,
                                showConfirmButton: false,
                                timer: 1500,
                                onClose: () => {
                                    $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al generar el reporte',
                            text: 'Por favor, inténtelo de nuevo más tarde',
                            confirmButtonText: 'Cerrar',
                            customClass: {
                                confirmButton : 'custom-swal-button-cerrar'
                            },
                            onClose: () => {
                                $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                            }
                        });
                    }
                });
            }
        });
    }

    
    $(document).on("click contextmenu", ".validar-hospitalizacion", function (event) {
    event.preventDefault(); // Evita la navegación inmediata

    const idOrdenHospitalizacion = $(this).data("id"); // Obtener ID del paciente
    const url = $(this).attr("href"); // Obtener la URL

    // Detectar si es clic derecho o apertura en nueva pestaña (Ctrl/Cmd + Click)
    const esNuevaPestana = event.type === "contextmenu" || event.ctrlKey || event.metaKey || event.shiftKey;

    // Llamar a la validación antes de redirigir
    validarAdmicion(idOrdenHospitalizacion, url, esNuevaPestana);
});

// Función para validar antes de redirigir
function validarAdmicion(idOrdenHospitalizacion, url, esNuevaPestana) {
    $.ajax({
        type: "POST",
        url: "/hospitalazacionValidarAtendida",
        data: {
            "idOrdenHospitalizacion": idOrdenHospitalizacion,
            _token: $('input[name="_token"]').val()
        },
        success: function (response) {
            console.log(response);
            if (response.respuesta) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Paciente Atendido',
                    text: '¿Está seguro de que desea atender nuevamente a este paciente?',
                    confirmButtonText: 'Sí',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    customClass: {
                        confirmButton: 'custom-swal-button-cerrar'
                    },
                    onClose: () => {
                        $('.blur-background').remove();
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        abrirPagina(url, esNuevaPestana);
                    }
                });
            } else {
                abrirPagina(url, esNuevaPestana);
            }
        },
        error: function () {
            Swal.hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error al validar la hospitalización',
                text: 'Por favor, inténtelo de nuevo más tarde.',
                confirmButtonText: 'Cerrar',
                customClass: {
                    confirmButton: 'custom-swal-button-cerrar'
                },
                onClose: () => {
                    $('.blur-background').remove();
                }
            });
        }
    });
}

// Función para abrir la página según el método de apertura
function abrirPagina(url, esNuevaPestana) {
    if (esNuevaPestana) {
        window.open(url, '_blank'); // Abrir en nueva pestaña
    } else {
        window.location.href = url; // Redirigir en la misma pestaña
    }
}

  function regularizeCodeSiteds(id, establecimientoId, servicioId, fechaIngreso) {
    const dateAttention = moment(fechaIngreso, 'DD/MM/YYYY').format("YYYY-MM-DD");
    const body = {
      "recordId": id,
      "moduleId": 6,
      "establecimientoId": establecimientoId,
      "tipoAtencionId": 3,
      "servicioId": servicioId,
      "dateAttention": dateAttention
    };
    $.ajax({
      url: "/regularize-multiple-siteds-temporary",
      method: 'POST',
      data: body,
      dataType: 'json',
      beforeSend: function() {
        Swal.fire({
          title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
          html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Por favor, espera...</span></div>`,
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: () => Swal.showLoading()
        });
      }
    }).done(function(response) {
      Swal.fire({
        icon:'success',
        title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
        html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${response.message || "Código SITEDS actualizado correctamente"}</span></div>`,
        confirmButtonText: 'ENTENDIDO',
      });
      fn_ListarBusqueda();
    }).fail(function(jqXHR, textStatus, errorThrown) {
      const errorCode = jqXHR.responseJSON && jqXHR.responseJSON.code ? jqXHR.responseJSON.code : "";
      let messageError = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";

      const session = {!! json_encode(Auth::user()) !!}
      let hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;

      if ((errorCode === "406" || errorCode === "501") && hasPermissionUpdateSitedsManual) {
          messageError += "<br><br><span style='font-size: 15px; color: #d9534f'>Sin embargo, puedes ingresar manualmente al SITEDS, pero recuerda que cualquier acción será bajo tu responsabilidad.</span>";
      } else {
        hasPermissionUpdateSitedsManual = 0;
      }

      Swal.fire({
        icon: 'warning',
        title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
        html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
        showCancelButton: true,
        showConfirmButton:  hasPermissionUpdateSitedsManual,
        cancelButtonText: hasPermissionUpdateSitedsManual ? "CANCELAR" : "ENTENDIDO",
        confirmButtonColor: "#5cb85c",
        cancelButtonColor: "#d9534f",
        confirmButtonText: "INGRESAR MANUALMENTE",
        allowOutsideClick: false,
        allowEscapeKey: false 
      }).then((result) => {
        if (result.isConfirmed) {
          const session = {!! json_encode(Auth::user()) !!}
          const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
          showModalSitedsManual().then((resultModal) => {
            if (resultModal && resultModal.isConfirmed && resultModal.value) {
              updateManualCodeSiteds(id, resultModal.value);
            }
          })
        }
      });
    }).always(function() {
      Swal.hideLoading();
    });
  }

  function showModalSitedsManual(id) {
    return new Promise((resolve, reject) => {
      Swal.fire({
        title: "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>Regularización Manual del SITEDS</span></div>",
        html: "<div style='padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Le informamos que, para regularizar su código SITEDS, debe generarlo en la página oficial de SITEDS. Una vez generado, ingréselo en este formulario para continuar con el proceso.</span></div>",
        input: 'text',
        inputLabel: 'Código SITEDS',
        inputPlaceholder: "000000000001",
        showCancelButton: true,
        cancelButtonText: 'CANCELAR',
        confirmButtonText: 'GUARDAR',
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
            popup: 'swal-wide-form',
        },
        inputAttributes: {
            maxlength: 12,
            pattern: '[A-Za-z0-9 ]*'
        },
        buttonsStyling: false,
        inputValidator: (value) => {
            if (!value) {
                return 'Ingresa el código SITED para continuar';
            } else if (value.length < 12) {
                return 'El código SITEDS debe tener 12 caracteres';
            }
        }
      }).then((result) => {
        if (result.isConfirmed) {
            resolve(result);
        } else {
            reject(result);
        }
      }).catch((error) => {
        reject(error);
      });
    });
  }

  function updateManualCodeSiteds(hospitalizacionId, newCodeSiteds) {
    $.ajax({
      url: `/hospitalizacion/${hospitalizacionId}/update-code-siteds`,
      method: 'PUT',
      data: {
        cod_siteds_susalud: newCodeSiteds
      },
      dataType: 'json',
      beforeSend: function() {
        Swal.fire({
          title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
          html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Actualizando Codigo Siteds...</span></div>`,
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: () => Swal.showLoading()
        });
      }
    }).done(function(response) {
      const message = response.message || "Código SITEDS actualizado correctamente"; 
      Swal.fire({
        icon:'success',
        title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
        html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${message}</span></div>`,
        confirmButtonText: 'ENTENDIDO',
      });
      fn_ListarBusqueda();
    }).fail(function(jqXHR, textStatus, errorThrown) {
    }).always(function(xhr, status) {
      Swal.hideLoading();
      Swal.close();
    });
  }
  
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading" style="padding: 0;">
      <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(236, 236, 117); color: green;">H</span> Lista de Admisión - Hospitalización</h3>
    </div>
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_emergencia_a_hospitalizar_ajax')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{$id_user}}" name="id_user" id="id_user" type="hidden">
        <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$id_establecimiento}}">
        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$id_farmacia}}">
        <div class="row">
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">Fecha Desde</label>
            <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
              value="<?php echo $fechaDesde ?>" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">Fecha Hasta</label>
            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
              value="<?php echo $fechaHasta ?>" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">N° Documento Paciente</label>
            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="12">
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <label class="form-control-sm">Apellidos y Nombres</label>
            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="100" style="">
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <label>Servicio Destino</label>
            <select id="id_servicio_destino" name="id_servicio_destino" class="form-control" style="width: 100%">
              <option value="0">--Seleccionar--</option>
              <?php  
                foreach($upss as $row): 
              ?>
              <option value="<?php echo $row->id_upss?>">
                <?php echo $row->nombre ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label>Estado</label>
            <select id="estado" name="estado" class="form-control input-sm" style="width: 100%">
              <option value="0">Pendientes</option>
              <option value="1">Atendidos</option>
              <option value="-1">Anulados</option>
            </select>
          </div>
          <div class="col-md-offset-4 col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label>&nbsp;</label>
            <input id="btnBuscar" class="form-control btn btn-info input-sm" value="BUSCAR" type="button"
              style="height: 35px !important;">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" id="opcion_exportar" name="opcion_exportar">
            <label>&nbsp;</label>
            <a href="javascript:void(0)" class="btn btn-success btn-block" id="btn_exportar_repo"
              name="btn_exportar_repo" onclick="reporteAdmisionHopitalizacion('{{ Auth::user()->id }}')">
              <i class="fa fa-file-excel-o" aria-hidden="true"></i> EXPORTAR</a>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label>&nbsp;</label>
            <a href="/hospitalizacion_admision" class="btn btn-success btn-block"
              style="height: 35px !important;">NUEVO</a>
          </div>
        </div>
      </form>
      <br>
      <div class="table-responsive">
        <table id="tblEmergenciaTriaje" class="table table-condensed table-bordered table-hover" style="width:100%">
          <thead>
            <tr>
              <th id="columna0">N</th>
              <th id="columna1">Ipress Origen</th>
              <th id="columna2">Tipo Atención</th>
              <th id="columna3">Datos de Derivación</th>
              <th id="columna4">Paciente</th>
              <th id="columna5">Sexo</th>
              <th id="columna6">Edad</th>
              <th id="columna7">Servicio Destino</th>
              <th id="columna8" style="text-align:center">Ver</th>
              <th id="columna9">Pdf</th>
              <th id="columna10" style="text-align:center">Admisión</th>
              <th id="columna11">Pdf de Hospitalizacion</th>
              <th id="columna12">Siteds</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<script>
  function anularAdmision(id_hopitalizacion, motivo_anulacion_hopitalizacion){
    bootbox.confirm({ 
      size: "small", 
      message: "&iquest;Desea anular la Admisión registrada?", 
      callback: function(result){
        if (result==true) {
          fn_anularAdmision(id_hopitalizacion, motivo_anulacion_hopitalizacion.replace(/\//g, "-"));
        }
      }
    }); 
  }
  

  function fn_anularAdmision(id_hopitalizacion, motivo_anulacion_hopitalizacion) {
    var data = {
      id: id_hopitalizacion,
      motivo_anulacion: motivo_anulacion_hopitalizacion
    };

    $.ajax({
      url: '/anular_admision_hospitalizacion',
      method: 'POST',
      data: JSON.stringify(data),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
        if (result.sw == true) {
          $('#modalAnularAdmision').modal('hide');
          var oTable = $('#tblEmergenciaTriaje').dataTable();
          oTable.fnDraw(false);

          Swal.fire({
            title: '<div class="titulo-padding"><p>Mensaje de Admisión</p></div>',
            html: '<p class="descripcion-alert"><span><b>LA ADMISIÓN SELECCIONADA FUE ANULADA DE FORMA CORRECTA.</b><br></span></p>',
            allowOutsideClick: false,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
          });
        }
      }
    });
  }
  
  $('body').on('click', '#anular_admision', function () {  
    var oTable = $('#tblEmergenciaTriaje').dataTable();
    oTable.fnDraw(false);

    $('#FormularioAnularAdmision').trigger("reset");
    $('#modalAnularAdmision').modal({backdrop: 'static', keyboard: false, show: true})
    $('#modalAnularAdmision').modal('show');

    $('#id_hopitalizacion_').val($(this).data('id_hopitalizacion'));

    var oTable = $('#tblEmergenciaTriaje').dataTable();
    oTable.fnDraw(false); 
  });

  
$('body').on('click', '#registrar_anular_admision', function () {  
  var id_hopitalizacion_=$('#id_hopitalizacion_').val();
  var motivo_anulacion_hopitalizacion_hopitalizacion=$('#motivo_anulacion_hopitalizacion_hopitalizacion').val();
  if(motivo_anulacion_hopitalizacion_hopitalizacion == '' || motivo_anulacion_hopitalizacion_hopitalizacion==null){
    Swal.fire({
      title: '<div class="titulo-padding"><p>Mensaje de Admisión</p></div>',
      html: '<p class="descripcion-alert"><span><b style="color: red;">RECUERDE QUE TIENE QUE TENER UNA CANTIDAD MÍMINA DE 10 CARACTERES.</b><br></span></p>', 
      allowOutsideClick: false,
      timer: 1800,
      timerProgressBar: true,
      showConfirmButton: false,
      allowOutsideClick: false,
    });
            
    var oTable = $('#tblEmergenciaTriaje').dataTable();
    oTable.fnDraw(false);
  }else{ 
    
    var motivo_anulacion_hopitalizacion_hopitalizacion=motivo_anulacion_hopitalizacion_hopitalizacion.toUpperCase();
    if(motivo_anulacion_hopitalizacion_hopitalizacion.length > 10){
      anularAdmision(id_hopitalizacion_ , motivo_anulacion_hopitalizacion_hopitalizacion); 
      var oTable = $('#tblEmergenciaTriaje').dataTable();
      oTable.fnDraw(false);
    }else{
      Swal.fire({
        title: '<div class="titulo-padding"><p>Mensaje de Admisión</p></div>',
        html: '<p class="descripcion-alert"><span><b style="color: red;">RECUERDE QUE TIENE QUE TENER UNA CANTIDAD MÍMINA DE 10 CARACTERES.</b><br></span></p>', 
        allowOutsideClick: false,
        timer: 1800,
        timerProgressBar: true,
        showConfirmButton: false,
        allowOutsideClick: false,
      });
    }

  } 
});
</script>

<div class="modal fade" id="modalAnularAdmision" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header" style="font-size: 20px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <b>DETALLE DE ANULACIÓN</b>
      </div>
      <div class="modal-body">
        <form id="FormularioAnularAdmision" name="FormularioAnularAdmision" enctype="multipart/form-data" method="POST">
          <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="id_hopitalizacion_" name="id_hopitalizacion_">
          <div class="nav-tabs-custom">
            <div class="tab-content">
              <div class="tab-pane active">
                <fieldset>
                  <div class="form-group" style="text-align: center">
                    <textarea name="motivo_anulacion_hopitalizacion_hopitalizacion"
                      id="motivo_anulacion_hopitalizacion_hopitalizacion" rows="10" style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group" style="text-align: center">
                    <a href="javascript:void(0)" class="btn btn-danger btn-md btn-block" id="registrar_anular_admision"
                      name="registrar_anular_admision">
                      ANULAR ADMISIÓN
                    </a>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection
