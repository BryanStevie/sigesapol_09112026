@extends('frontend.layouts.master3')
@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')
<style>
  /* .table-sortable tbody tr {
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
  } */
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
  $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
    //--------------------------------------------------------------------------
  


    $(document).ready(function() {
       
      $("#id_servicio_destino").select2({
        width: 'resolve'
      });

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
                fn_ListarBusqueda();
        }
			});
		});

		$('#fecha_fin').keypress(function(event) {
			if (event.which === 13) {
				fn_ListarBusqueda();
			}
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
                fn_ListarBusqueda();
        }

			});
		}); 
  
        var activeSystemClass = $('.list-group-item.active');

        fn_ListarBusqueda();
         
    $("#busqueda_datos").keypress(function(e) {
            if (e.which == 13) {
                fn_ListarBusqueda();
            }
        }); 
    
        $('#id_servicio_destino').on('change', function() { 
            fn_ListarBusqueda();
        });

        $('#btnBuscar').click(function() {
            fn_ListarBusqueda();
        });
    

        function fn_ListarBusqueda() {
            datatablenew();
        }
    });

    $('#estado').change(function(){ 
        datatablenew();
        
      if ($('#estado').val() == 2) { // No se usa
        $('#columna_2').text('Hospitalización');
        $('#columna_3').text('Fecha de Admisión');
      }else if ($('#estado').val() == 3 || $('#estado').val() == 0) { // 3: Atendidos, 0: Anulados
        $('#columna_2').text('Admisión');
        $('#columna_3').text('Última Atención');
      }
    });

    

    function datatablenew() {
        let estado = $('#estado').val();
        var oTable1 = $('#tblEmergenciaTriaje').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/all_hospitalizado_by_establecimiento_ajax",
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
            "aoColumns": [{},],
            "dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnDrawCallback": function (json) {
                $('[data-toggle="tooltip"]').tooltip();
            },

            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var sEcho = aoData[0].value;
                var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar = aoData[4].value;
                var fecha_ini = $('#fecha_ini').val();
                var fecha_fin = $('#fecha_fin').val();
                var ndocumento = $('#ndocumento').val();
                var busqueda_datos = $('#busqueda_datos').val();
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
                        ndocumento: ndocumento,
                        busqueda_datos: busqueda_datos,
                        id_servicio_destino: id_servicio_destino,
                        estado: estado,
                        _token: _token
                    },
                    "success": function (result) {
                        fnCallback(result);
                    },
                    "error": function (msg, textStatus, errorThrown) {
                        //location.href="login";
                    }
                });
            },

            "aoColumnDefs": [{
                "mRender": function (data, type, row) {
                    var id = "";
                    if (row.id != null) id = row.id;
                    return id;
                },
                "bSortable": false,
                "aTargets": [0]
            },
            {
                "mRender": function (data, type, row) {
                    if (row.estado === 1 || row.estado === 5) { 
                      return '<b>N° Hopitalización: </b><br>'+row.nro_hospitalizacion;
                    }else if (row.estado === 2 || row.estado === 4 || row.estado === 6 || row.estado === 7) { 
                      var fecha_registro =  '<b>N° Hopitalización: </b>'+row.nro_hospitalizacion+'<br>'+
                      '<b>Responsable: </b><br>'+row.nombre_registrador+'<br>'+
                      '<b>F.Ingreso: </b>'+row.fecha_ingreso+'<br>'+
                      '<b>Hora Ingreso: </b>'+row.hora_ingreso;
                      return fecha_registro; 
                    }
                },
                "bSortable": false,
                "aTargets": [1]
            },
            {
                "mRender": function (data, type, row) {
                    if (row.estado === 1 || row.estado === 5) {
                      var fecha_registro = '<b>Responsable: </b>'+row.nombre_registrador+'<br>'+
                        '<b>F.Ingreso: </b>'+row.fecha_ingreso+'<br>'+
                        '<b>Hora Ingreso: </b>'+row.hora_ingreso;
                      return fecha_registro;
                    }else if (row.estado === 2 || row.estado === 4 || row.estado === 6 || row.estado === 7) {
                      var fecha_atencion = '<b>Responsable: </b><br>'+row.medico_paterno+' '+row.medico_materno+' '+row.medico_nombre+'<br>'+
                        '<b>F.Atención: </b>'+row.fecha_atencion+'<br>'+
                        '<b>Hora Atención: </b>'+row.horario;
                      return fecha_atencion;
                    }
                },
                "bSortable": false,
                "aTargets": [2]
            },
            {
                "mRender": function (data, type, row) {
                    var nombre_consultorio = "";
                    if (row.servicio != null) nombre_consultorio = row.servicio;
                    return nombre_consultorio;
                },
                "bSortable": false,
                "aTargets": [3]
            }, 
            {
                "mRender": function (data, type, row) {
                    var paciente = '<b>T.Beneficiario: </b>'+row.tipo_beneficiario+'<br>'+
                    '<b>Documento: </b>'+row.dni_beneficiario +'<br>'+
                    '<b>Paciente: </b>'+ row.paterno_beneficiario + " " + row.materno_beneficiario + " " + row.nombre_beneficiario;
                    return paciente;
                },
                "bSortable": false,
                "aTargets": [4]

            }, 
                {
                    "mRender": function(data, type, row) {
                        var sexo = "";
                        if (row.sexo != null) sexo = row.sexo;
                        return sexo;
                    },
                    "bSortable": false,
                    "aTargets": [5]

                },
                {
                    "mRender": function(data, type, row) {
                        var edad = "";
                        if (row.edadpaciente != null) edad = calcularEdadPersonaReturn(row.fecha_nac); ;
                        return edad;
                    },
                    "bSortable": false,
                    "aTargets": [6]

                }, 
            {
                "mRender": function (data, type, row) {
                    var nombre_cuarto = '<b>Cuarto: </b> '+row.nombre_cuarto+'<br>'+ '<b>Cama: </b> '+row.nombre_cama ;
                    return nombre_cuarto;
                },
                "bSortable": false,
                "aTargets": [7]

            }, 
            {
                "mRender": function (data, type, row) {
                    var html = "";
                    if (estado == 1) {
                      if (row.estado == 1 || row.estado == 5) {
                        html += '<div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true"';
                        html += ' title="<b>Atender</b>">';
                        html += ' <a href="/form_atenciones_hospitalizacion/' + row.id + '" class="btn btn-success btn-xs">';
                        html += ' <i class="fa fa-fw fa-edit"></i></a>';
                        html += '</div>';
                      }
                    }
                    if (estado == 4) {
                      if (row.estado === 2 || row.estado === 4 || row.estado === 6 || row.estado === 7) {
                        html += '<div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true"';
                        html += ' title="<b>Ver Atenciónes</b>">';
                         //html += ' <a href="/ver_prestacion_hospitalizacion/' + row.id_user + '/'+ row.id_prestacion+'" class="btn btn-success btn-xs"> '; 
                        html += ' <a href="/ver_prestaciones_hospitalizacion/' + row.id + '" class="btn btn-success btn-xs"> '; 
                        html += ' <i class="fa fa-eye"></i></a>';
                        html += '</div>';
                      }
                    }
                    return html;
                },
                "bSortable": false,
                "aTargets": [8]

            },
            // {
            //     "mRender": function (data, type, row) {
            //         var html = "";
            //         if (row.ruta_prestacion != null && row.id_estado_reg == 1) {
            //           html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
            //           //html += '<a href="/' + row.flag + '/' + row.ruta_prestacion + '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
            //           html += '<a href="/print_hospitalizacion_atencion/' + row.id + '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
            //         } else {
            //           if (row.estado >= 3) {
            //             html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-download"></i></a></button>';
            //           }
            //         }
            //         return html;
            //     },
            //     "bSortable": false,
            //     "aTargets": [9],
            //     "className": "text-center"

            // },
            // {
            //     "mRender": function (data, type, row) {
            //         var html = "";
            //         if (row.ruta_receta != null && row.id_estado_reg == 1) {
            //           var ruta_receta = row.ruta_receta.replace("{", "").replace("}", "");
            //           var ruta = ruta_receta.split(",");
            //           let flag_receta = row.flag_receta.replace("{", "").replace("}", "");
            //           let flag_firma = flag_receta.split(",");

            //           for (var r = 0; r < ruta.length; r++) {
            //             if (ruta[r] != "NULL") {
            //               html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
            //               html += '<a href="/' + flag_firma[0] + '/' + ruta[r] + '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
            //             } else {
            //               html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
            //             }
            //           }
            //         }
            //         return html;
            //     },
            //     "bSortable": false,
            //     "aTargets": [10],
            //     "className": "text-center"

            // },
            // {
            //     "mRender": function (data, type, row) {
            //         var html = "";
            //         if (row.ruta_laboratorio != null && row.id_estado_reg == 1) {
            //           var ruta_laboratorio = row.ruta_laboratorio.replace("{", "").replace("}", "");
            //           var ruta = ruta_laboratorio.split(",");
            //           let flag_laboratorio = row.flag_laboratorio.replace("{", "").replace("}", "");
            //           let flag_firma = flag_laboratorio.split(",");

            //           for (var r = 0; r < ruta.length; r++) {
            //             if (ruta[r] != "NULL") {
            //               html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">';
            //               html += '<a href="/' + flag_firma[0] + '/' + ruta[r] +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
            //             } else {
            //               html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
            //             }
            //           }
            //         }
            //         return html;
            //     },
            //     "bSortable": false,
            //     "aTargets": [11],
            //     "className": "text-center"
            // },
            // {
            //     "mRender": function (data, type, row) { 
            //       var html = "";
            //       if (row.ruta_radiologia != null) {
            //         var ruta_radiologia = row.ruta_radiologia.replace("{", "").replace("}", "");
            //         var ruta = ruta_radiologia.split(",");
            //         let flag_radiologia = row.flag_radiologia.replace("{", "").replace("}", "");
            //         let flag_firma = flag_radiologia.split(",");

            //         for (var r = 0; r < ruta.length; r++) {
            //           if (ruta[r] != "NULL") {
            //             html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">';
            //             html += '<a href="/' + flag_firma[0] + '/' + ruta[r] + '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
            //           } else {
            //             html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
            //           }
            //         }
            //       }
            //       return html;
            //     },
            //     "bSortable": false,
            //     "aTargets": [12],
            //     "className": "text-center"
            // },
            // {
            //   "mRender": function (data, type, row) { 
            //     var html = "";
            //     if (row.estado == 0 || row.estado === 6) {
            //       html += '<b>Fecha:</b><br> '+row.fecha_anulacion_atencion +' - '+row.hora_anulacion_atencion+'<br>'+ 
            //                   '<b>Responsable de Anulación:</b><br>'+row.usuario_anula_atencion+'<br>'+
            //                   '<b>Motivo de Anulación:</b><br> '+row.motivo_anulacion_atencion ; 
            //     } else if (row.estado == 3) {
            //       html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atención</b>" style="float:left;padding-left:3px"><button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button></div>';
            //     }
            //     return html;
            //   },
            //   "bSortable": false,
            //   "aTargets": [13],
            //   "className": "text-center"
            // },
            /*{
              "mRender": function (data, type, row) { 
                let htmlString = '<div style="text-align: center">';
                if (row.cod_siteds_susalud) {
                    let isSitedsTemp = false;
                    if (row.cod_siteds_susalud && (row.cod_siteds_susalud[row.cod_siteds_susalud.length - 1] === 'T' || row.cod_siteds_susalud.length === 4)) {
                        isSitedsTemp = true;
                    }
                    htmlString += `<p><span class="label ${ isSitedsTemp ? 'label-warning' : 'label-success'}">${row.cod_siteds_susalud}</span></p>`;
                    if (isSitedsTemp) {
                        htmlString += `<button class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Regularizar" onclick="regularizeCodeSiteds(${row.id}, ${row.id_asegurado}, ${row.id_establecimiento}, ${row.id_consultorio})"><i class="fa fa-check-square"></i></button>`
                    }
                }
                return htmlString += "</div>";
              },
              "bSortable": false,
              "aTargets": [9],
              "className": "text-center"
            },*/
            ]
        });
      var oTable2= $('#tblEmergenciaTriaje').DataTable();   
      if ($('#estado').val() == '0') {
          oTable2.column(8).visible(false);
          oTable2.column(9).visible(false);
          oTable2.column(10).visible(false);
          oTable2.column(11).visible(false); 
      }else if ($('#estado').val() == '1' ) {
          // oTable2.column(9).visible(false);
          // oTable2.column(10).visible(false);
          // oTable2.column(11).visible(false);
          // oTable2.column(12).visible(false);
          // oTable2.column(13).visible(false);
      } 
      
    }

    function reporteAtencionHopitalizacion(idUser) {
      Swal.fire({
        title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
          header : 'swal2-header-generar'
        },
        willOpen: () => {
          $('#blur-container').append('<div class="blur-background"></div>');
          let fecha_ini = $('#fecha_ini').val();
          let fecha_fin = $('#fecha_fin').val();
          let ndocumento = $('#ndocumento').val();
          let busqueda_datos = $('#busqueda_datos').val();
          let id_servicio_destino = $('#id_servicio_destino').val();
          let estado = $('#estado').val();
          
          Swal.showLoading();
          $.ajax({
            type: "POST",
            url: "exportar_atencion_hospitalizacion",
            data: {
              "fecha_ini" : fecha_ini,
              "fecha_fin" : fecha_fin,
              "ndocumento" : ndocumento,
              "busqueda_datos" : busqueda_datos,
              "id_servicio_destino" : id_servicio_destino,
              "estado" : estado
            },
            success: function (response) {
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
                // divLinkReporte.removeChild(link);
                divLinkReporte.remove();
                Swal.hideLoading();
                Swal.fire({
                  icon: 'success',
                  title: 'El reporte se ha generado correctamente!',
                  showConfirmButton: false,
                  // showConfirmButton: false,
                  timer: 1500,
                  willClose: () => {
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
                willClose: () => {
                  $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                }
              });
            }
          });
        }
      });
    }
 
</script>
<!-- alergias -->
{{-- @include('frontend.hospitalizacion.alergias.function_alergias.listar_alergias') --}}
{{-- @include('frontend.hospitalizacion.alergias.function_alergias.registrar_alergias') --}}
<!-- interconsultas -->
{{-- @include('frontend.hospitalizacion.medicina.interconsultas.function_interconsultas.listar_interconsultas') --}}
{{-- @include('frontend.hospitalizacion.medicina.interconsultas.function_interconsultas.registrar_interconsultas') --}}
<!-- evoluciones medicas -->
{{-- @include('frontend.hospitalizacion.medicina.evoluciones.function_evoluciones.listar_evoluciones') --}}
{{-- @include('frontend.hospitalizacion.medicina.evoluciones.function_evoluciones.registrar_evoluciones') --}}
{{-- @include('frontend.hospitalizacion.medicina.evoluciones.function_evoluciones.listar_historiales') --}}
@endsection
@section('content')
<div class="container-fluid">

  <div class="row col-md-12">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title">Buscar Pacientes Hospitalizados
        </h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal" method="post" action="{{ route('all_hospitalizado_by_establecimiento_ajax')}}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
          <input type="hidden" name="id_establecimiento" id="id_establecimiento"
            value="{{Auth::user()->id_establecimiento}}">
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
              <label class="form-control-sm">N° Documento</label>
              <input class="form-control input-sm" type="text" name="ndocumento" id="ndocumento" value="" maxlength="15" >
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <label class="form-control-sm">Apellidos y Nombres</label>
              <input class="form-control input-sm" id="busqueda_datos" name="busqueda_datos" >
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
                <option value="1">Pendientes</option>
                <option value="4">Atendidos</option>
                <option value="0">Anulados</option> 
              </select>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
              <label for="">&nbsp;</label>
              <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" id="opcion_exportar" name="opcion_exportar">
              <label for="">&nbsp;</label>
              <!-- <button id="btn_exportar_repo" name="btn_exportar_repo" class="form-control btn btn-success input-sm" onclick="reporteAtencionHopitalizacion('{{ Auth::user()->id }}')"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar</button> -->
              <a href="javascript:void(0)" class="form-control btn btn-success input-sm" id="btn_exportar_repo"
                name="btn_exportar_repo" onclick="reporteAtencionHopitalizacion('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar</a>
            </div>
          </div>
        </form>
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="tblEmergenciaTriaje" class="table table-condensed table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>N</th>
                  <th id="columna_2">Hospitalización</th>
                  <th id="columna_3">Admisión</th>
                  <th>Servicio</th>
                  <th>Beneficiario</th>
                  <th>Sexo</th>
                  <th>Edad</th>
                  <th>Ubicación</th> 
                  <th>Atención</th>
                  <!-- <th>Pdf<br>Atención</th>
                  <th>Receta</th>
                  <th>Orden<br>Laboratorio</th>
                  <th>Orden<br>Imagenología</th>
                  <th>Anulación</th>
                  <th>Siteds</th> -->
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
</div>
<script>
  function AnularAtencion(id_hopitalizacion, motivo_anulacion_atencion){
    bootbox.confirm({ 
      size: "small", 
      message: "&iquest;Desea anular la Atención registrada?", 
      callback: function(result){
        if (result==true) {
          fn_AnularAtencion(id_hopitalizacion, motivo_anulacion_atencion.replace(/\//g, "-"));
        }
      }
    }); 
  }
  

  function fn_AnularAtencion(id_hopitalizacion, motivo_anulacion_atencion) {
    var data = {
      id: id_hopitalizacion,
      motivo_anulacion: motivo_anulacion_atencion
    };

    $.ajax({
      url: '/anular_atencion_hospitalizacion',
      method: 'POST',
      data: JSON.stringify(data),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
        if (result.sw == true) {
          $('#modalAnularAtencion').modal('hide');
          var oTable = $('#tblEmergenciaTriaje').dataTable();
          oTable.fnDraw(false);

          Swal.fire({
            title: '<div class="titulo-padding"><p>Mensaje de Atención</p></div>',
            html: '<p class="descripcion-alert"><span><b>LA ATENCIÓN SELECCIONADA FUE ANULADA DE FORMA CORRECTA.</b><br></span></p>',
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
  
  $('body').on('click', '#anular_atencion', function () {  
    var oTable = $('#tblEmergenciaTriaje').dataTable();
    oTable.fnDraw(false);

    $('#FormularioAnularAtencion').trigger("reset");
    $('#modalAnularAtencion').modal({backdrop: 'static', keyboard: false, show: true})
    $('#modalAnularAtencion').modal('show');

    $('#id_hopitalizacion_').val($(this).data('id_hopitalizacion'));

    var oTable = $('#tblEmergenciaTriaje').dataTable();
    oTable.fnDraw(false); 
  });

  
$('body').on('click', '#registrar_anular_atencion', function () {  
  var id_hopitalizacion_=$('#id_hopitalizacion_').val();
  var motivo_anulacion_atencion=$('#motivo_anulacion_atencion').val();
  if(motivo_anulacion_atencion == '' || motivo_anulacion_atencion==null){
    Swal.fire({
      title: '<div class="titulo-padding"><p>Mensaje de Atención</p></div>',
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
    
    var motivo_anulacion_atencion=motivo_anulacion_atencion.toUpperCase();
    if(motivo_anulacion_atencion.length > 10){
      AnularAtencion(id_hopitalizacion_ , motivo_anulacion_atencion); 
      var oTable = $('#tblEmergenciaTriaje').dataTable();
      oTable.fnDraw(false);
    }else{
      Swal.fire({
        title: '<div class="titulo-padding"><p>Mensaje de Atención</p></div>',
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

<div class="modal fade" id="modalAnularAtencion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header" style="font-size: 20px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <b>DETALLE DE ANULACIÓN</b>
      </div>
      <div class="modal-body">
        <form id="FormularioAnularAtencion" name="FormularioAnularAtencion" enctype="multipart/form-data" method="POST">
          <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="id_hopitalizacion_" name="id_hopitalizacion_">
          <div class="nav-tabs-custom">
            <div class="tab-content">
              <div class="tab-pane active">
                <fieldset>
                  <div class="form-group" style="text-align: center">
                    <textarea name="motivo_anulacion_atencion"
                      id="motivo_anulacion_atencion" rows="10" style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group" style="text-align: center">
                    <a href="javascript:void(0)" class="btn btn-danger btn-md btn-block" id="registrar_anular_atencion"
                      name="registrar_anular_atencion">
                      ANULAR ATENCIÓN
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


<!-- interconsultas -->
{{-- @include('frontend.hospitalizacion.medicina.interconsultas.modal_interconsultas.lista_de_interconsultas') --}}
{{-- @include('frontend.hospitalizacion.medicina.interconsultas.modal_interconsultas.registrar_interconsultas') --}}

<!-- evoluciones médicas -->
{{-- @include('frontend.hospitalizacion.medicina.evoluciones.modal_evoluciones.lista_de_evoluciones') --}}
{{-- @include('frontend.hospitalizacion.medicina.evoluciones.modal_evoluciones.lista_historiales') --}}
{{-- @include('frontend.hospitalizacion.medicina.evoluciones.modal_evoluciones.registrar_evoluciones') --}}

<!-- alergias -->
{{-- @include('frontend.hospitalizacion.alergias.modal_alergias.lista_de_alergias') --}}
{{-- @include('frontend.hospitalizacion.alergias.modal_alergias.registrar_alergias') --}}

<div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection