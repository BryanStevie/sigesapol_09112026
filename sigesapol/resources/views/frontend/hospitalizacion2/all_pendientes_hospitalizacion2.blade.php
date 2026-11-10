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

  #global {
    min-height: 650px !important;
    margin: 15px;
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
                        if($('#estado').val()==0){ 
                          let medico_egreso = row.medico_paterno + ' ' + row.medico_materno + ', ' + row.medico_nombre;
                          return '<b>Responsable:</b>'+medico_egreso+'<br><b>F.Derivación:</b> '+row.fecha_alta_medica+'<br><b>Hora Derivación:</b> '+row.hora_alta_medica;
                        }else if($('#estado').val()==1 || $('#estado').val()==-1){ 
                          let medico_egreso = row.medico_paterno + ' ' + row.medico_materno + ', ' + row.medico_nombre;
                          var fecha_registro =  '<b>Responsable: </b>'+medico_egreso+'<br>'+
                              '<b>F.Ingreso: </b>'+row.fecha_ingreso+'<br>'+
                              '<b>Hora Ingreso: </b>'+row.hora_ingreso;
                          return fecha_registro;
                        }
                    },
                    "bSortable": false,
                    "aTargets": [2]
                },
                {
                    "mRender": function(data, type, row) {   
                      if($('#estado').val()==0){ 
                        let paciente = row.paterno + ' ' + row.materno + ', ' + row.nombre;
                        return '<b>T.Beneficiario:</b> '+row.tipo_beneficiario+'<br><b>Documento:</b> '+row.dni_beneficiario+'<br><b>Paciente:</b> ' + paciente; 
                      }else if($('#estado').val()==1 || $('#estado').val()==-1){
                        return row.servicio_hospitalizado;
                      } 
                    },
                    "bSortable": false,
                    "aTargets": [3]
                }, 
                {
                    "mRender": function(data, type, row) { 
                      if($('#estado').val()==0){  
                        return row.sexo;
                      }else if($('#estado').val()==1 || $('#estado').val()==-1){
                        var paciente = '<b>T.Beneficiario: </b>'+row.tipo_beneficiario+'<br>'+
                        '<b>Documento: </b>'+row.dni_beneficiario +'<br>'+
                        '<b>Paciente: </b>'+ row.paterno + " " + row.materno + " " + row.nombre;
                        return paciente;
                      }
                    },
                    "bSortable": false,
                    "aTargets": [4]

                },
                {
                    "mRender": function(data, type, row) {
                      if($('#estado').val()==0){  
                        var edad = "";
                        if (row.edadpaciente != null) edad = calcularEdadPersonaReturn(row.fecha_nac); ;
                        return edad;
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
                        var consultorio_destino = "";
                        if (row.consultorio_destino != null) consultorio_destino = row.consultorio_destino;
                        return consultorio_destino;
                      }else if($('#estado').val()==1 || $('#estado').val()==-1){ 
                        return '<b>Cuarto:</b> '+row.nombre_cuarto+'<br><b>Cama:</b> '+row.nombre_cama ; 
                      }
                    },
                    "bSortable": false,
                    "aTargets": [6]

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
                    "aTargets": [7],
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
                    "aTargets": [8],
                    "visible": ($('#estado').val()==1) ? true : false
                },
                {
                    "mRender": function(data, type, row) {
                      var html = '';
                      if($('#estado').val()==0){  
                        html = '<div data-toggle="tooltip" data-placement="top" data-html="true" style="text-align:center" title="<b>Nueva Admisión</b>">';
                        html += '<a href="/hospitalizacion_admision/' + row.id + '" class="btn btn-success btn-xs"><i class="fa fa-pencil-square-o"></i></a></div>';
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
                    "aTargets": [9] 
                }, 
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
        $('#ipress_titulo').text('Ipress Origen');  
        $('#derivacio_titulo').text('Derivación');  
        $('#paciente_titulo').text('Paciente');  
        $('#sexo_titulo').text('Sexo');  
        $('#edad_titulo').text('Edad');  
        $('#destino_titulo').text('Servicio Destino');  
        $('#pdf_titulo').text('Pdf'); 
        $('#filiacion_titulo').text('Admisión');
      }else if ($('#estado').val() == 1 || $('#estado').val() == -1) { 
        $('#ipress_titulo').text('Establecimiento');
        $('#derivacio_titulo').text('Fecha Ingreso');  
        $('#paciente_titulo').text('Servicio');  
        $('#sexo_titulo').text('Beneficiario');  
        $('#edad_titulo').text('Sexo/Edad');  
        $('#destino_titulo').text('Ubicación');  
        $('#pdf_titulo').text('Pdf'); 
        $('#filiacion_titulo').text('Anulación'); 
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
                // let id_servicio_destino = $('#id_servicio_destino').val(); 
                let estado = $('#estado').val();
                let id_servicio_destino = $('#id_servicio_destino').val();
                // if (fecha_ini == "") fecha_ini = 0;
                // if (fecha_fin == "") fecha_fin = 0;
                // if (dni_beneficiario == "") dni_beneficiario = 0;
                // if (id_servicio_destino == "") id_servicio_destino = 0;
                // if (estado == "") estado = 0;
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "exportar_admision_hospitalizacion",
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
  
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Lista Pendientes de Admisión - Hospitalización</h3>
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
              value="<?php echo $fechaDesde ?>" maxlength="10"
              style="height: 35px !important; font-size: 14px !important;">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">Fecha Hasta</label>
            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
              value="<?php echo $fechaHasta ?>" maxlength="10"
              style="height: 35px !important; font-size: 14px !important;">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">N° Documento Paciente</label>
            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="12" style="height: 35px !important;">
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
            <select id="estado" name="estado" class="form-control" style="width: 100%">
              <option value="0">Pendientes</option>
              <option value="1">Atendidos</option>
              <option value="-1">Anulados</option>
            </select>
          </div>
          <div class="col-md-offset-4 col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label>&nbsp;</label>
            <input id="btnBuscar" class="form-control btn btn-info input-sm" value="BUSCAR" ype="button"
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
      <div id="global">
        <div class="table-responsive">
          <table id="tblEmergenciaTriaje" class="table table-condensed table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>N</th>
                <th id="ipress_titulo">Ipress Origen</th>
                <th id="derivacio_titulo">Derivación</th>
                <th id="paciente_titulo">Paciente</th>
                <th id="sexo_titulo">Sexo</th>
                <th id="edad_titulo">Edad</th>
                <th id="destino_titulo">Servicio Destino</th>
                <th style="text-align:center">Ver</th>
                <th id="Pdf_titulo">Pdf</th>
                <th id="filiacion_titulo" style="text-align:center">Admisión</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
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
