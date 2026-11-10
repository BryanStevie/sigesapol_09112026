<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<style>
  .table-sortable tbody tr {
    cursor: move;
  }

  #global {
    min-height: 650px !important;
    /*width: auto;*/
    width: 100%;
    border: 1px solid #ddd;
    margin: 15px
      /* background: #f1f1f1;*/
      /*overflow-y: scroll !important;*/
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

  .modal-dialog {
    width: 70% !important;
  }
  td.dt-control {
    cursor: pointer;
    text-align: center;
  }
  .swal2-icon.swal2-warning {
    font-size: 15px;
  }
  #tableValidacionesTramas td,
  #tableValidacionesTramas th {
      vertical-align: middle !important;
  }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>

$(document).ready(function() {
    var establecimientosCargados = false; // Bandera para verificar si los establecimientos ya se cargaron
    let oTable1;

    // Función para cargar todos los establecimientos
    function cargarTodosEstablecimientos() {
        // Realizar una solicitud AJAX para obtener todos los establecimientos
        $.ajax({
            url: '{{ route("obtener_todos_establecimientos") }}',
            type: 'GET',
            success: function(response) {
                // Agregar todas las opciones de establecimiento
                $.each(response.establecimientos, function(key, value) {
                    $('#buscar_ipress_origen_establecimiento').append('<option value="' + value.id + '">' + value.nombre_estab + '</option>');
                });
                establecimientosCargados = true; // Establecer la bandera a verdadero después de cargar los establecimientos
            }
        });
    }
    // Cuando cambia la selección de nivel
    $('#buscar_ipress_origen_nivel').change(function() {
        var nivelSeleccionado = $(this).val();
        // Limpia las opciones de establecimiento y servicio
        $('#buscar_ipress_origen_establecimiento').empty().append('<option value="0">TODOS</option>');
        $('#buscar_ipress_origen_servicio').empty().append('<option value="0">TODOS</option>');
        
        // Si los establecimientos aún no se han cargado, cargarlos
        if (!establecimientosCargados) {
            cargarTodosEstablecimientos();
        }
        
        // Realiza una solicitud AJAX para obtener los establecimientos correspondientes al nivel seleccionado
        $.ajax({
            url: 'obtener_establecimientos_por_nivel',
            type: 'GET',
            data: { nivel: nivelSeleccionado },
            success: function(response) {
                // Agrega las opciones de establecimiento
                $.each(response.establecimientos, function(key, value) {
                    $('#buscar_ipress_origen_establecimiento').append('<option value="' + value.id + '">' + value.nombre_estab + '</option>');
                });
            }
        });
    });

    // Cuando cambia la selección de establecimiento
    $('#buscar_ipress_origen_establecimiento').change(function() {
        // Aquí puedes agregar código para manejar el cambio en la selección de establecimiento si es necesario
    });
});


        // Cuando cambia la selección de establecimiento
        $('#buscar_ipress_origen_establecimiento').change(function() {
            var establecimientoSeleccionado = $(this).val();
            // Limpia las opciones de servicio
            $('#buscar_ipress_origen_servicio').empty().append('<option value="0">TODOS</option>');
            // Realiza una solicitud AJAX para obtener los servicios correspondientes al establecimiento seleccionado
            $.ajax({
                url: 'obtener_servicios_por_establecimiento',
                type: 'GET',
                data: { establecimiento: establecimientoSeleccionado },
                success: function(response) {
                    // Agrega las opciones de servicio
                    $.each(response.servicios, function(key, value) {
                        $('#buscar_ipress_origen_servicio').append('<option value="' + value.id + '">' + value.nombre_servicio + '</option>');
                    });
                }
            });
        });
    

    
  //--------------------------------------------------------------------------
    $(document).ready(function () {
        $('#fecha_ini').appendDtpicker({
        "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
        });
    
        $('#fecha_fin').appendDtpicker({
        "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
        });
    
        $('#fecha_ini').click(function () {
            var fechaDesdeIni = $('#fecha_ini').val();
        
            $('#fecha_ini').change(function () {
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
                }
            });
        });

        $('#fecha_fin').click(function () {
            var fechaHastaIni = $('#fecha_fin').val();
            $('#fecha_fin').change(function () {
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
                }
    	
            });
        });

        $('#fecha_fin').trigger('change');
        
        $('#fecha_ini').trigger('change');
        
        var activeSystemClass = $('.list-group-item.active');
        
    });
        
    $(document).ready(function () {
        $('#example').DataTable({
            "language": {
            "emptyTable": "No se encontraron resultados"
            }
        });
    
        $("#system-search").keyup(function() {
            var dataTable = $('#example').dataTable();
            dataTable.fnFilter(this.value);
        });
    });
    
    function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }



    $(document).ready(function () {
    	
    	$('#btnBuscar').click(function () {
    		fn_ListarBusqueda();
    	});
    	
    	datatablenew();
    	 	
    });

    $('body').on('click', '#validarws', function () {
        var id_trama = $(this).data('id_trama');
        var id_estado = $(this).data('id_estado');
        $id_trama = id_trama;
        $id_estado = id_estado;
        $('#id_estado').html(id_estado);
        $('#id_trama').html(id_trama);
       if(id_trama == '0'){
        console.log(id_trama);
        console.log(id_estado);
        validar_x_ws(id_trama,id_estado)
       }
    });

    function validar_x_ws(id_trama,id_estado){
      Swal.fire({
            title: '<div class="titulo-padding"><p>Procesando el envio...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                Swal.showLoading();
                var postData = {
                  id_trama: id_trama,
                    id_estado: id_estado,
                  };
                $.ajax({
                    url: '/validar_ws_SaludPol',
                    type: 'POST',
                    data: postData,
                    success: function(response) {
                        console.log('Success:', response);
                        datatablenew();
                        Swal.hideLoading();
                        Swal.fire({
                          icon: 'success',
                          title: 'La información fue enviado correctamente!',
                          showConfirmButton: false,
                          showConfirmButton: false,
                          timer: 1500,
                          onClose: () => {
                              $('.blur-background').remove();
                          }
                      });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });               
            }
        });
    }

    function Edad(dateString) 
    {
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
        {
            age--;
        }
        return age;
    }

    var m = 0;
    function datatablenew(){
        oTable1 = $('#tblEmergenciaTriaje').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_auditoria_ajax",
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bSort": false,
            "info": true,
    		    "language": {"url": "/js/Spanish.json"},
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "lengthMenu": [[10, 50, 100, 200, 60000], [10, 50, 100, 200, "Todos"]],
            "aoColumns": [
                            {},
            ],
    		"dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnDrawCallback": function(json) {
                $('[data-toggle="tooltip"]').tooltip();
            },

            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                
                var sEcho           = aoData[0].value;
                var iNroPagina 	= parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar 	= aoData[4].value;
                var fecha_ini = $('#fecha_ini').val();
                var fecha_fin = $('#fecha_fin').val();
                var dni = $('#dni').val();
                var id_establecimiento = $('#buscar_ipress_origen_establecimiento').val();
                var buscar_ipress_origen_nivel = $('#buscar_ipress_origen_nivel').val();
                var buscar_ipress_origen_servicio = $('#buscar_ipress_origen_servicio').val();
                var buscar_ipress_origen_medico = $('#buscar_ipress_origen_medico').val();
                var nombre_beneficiario = $('#nombre_beneficiario').val();
                var estado = $('#estado').val();
                var editado_auditoria = $('#editado_auditoria').val();
    			var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				      "dataType": 'json',
                  "type": "POST",
                  "url": sSource,
                  "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,fecha_ini:fecha_ini,fecha_fin:fecha_fin,
                    dni:dni,id_establecimiento:id_establecimiento,buscar_ipress_origen_nivel:buscar_ipress_origen_nivel,buscar_ipress_origen_servicio:buscar_ipress_origen_servicio
                    ,buscar_ipress_origen_medico:buscar_ipress_origen_medico, nombre_beneficiario:nombre_beneficiario,estado:estado,editado_auditoria:editado_auditoria,
    						_token:_token
                           },
                    "success": function (result) {
                        fnCallback(result);
                    },
                    "error": function (msg, textStatus, errorThrown) {
                    }
                });
            },
            "aoColumnDefs":
                [	
                    {
                        "mRender": function (data, type, row) {
                            var correlativo = "";
        					if(row.correlativo!= null)correlativo = row.correlativo;
        					return correlativo;
                        },
        				"bSortable": false,
                        "aTargets": [0]
                        },
                        {
                        "mRender": function (data, type, row) {
                            var dni_paciente = "";
        					if(row.dni_paciente!= null)dni_paciente = row.dni_paciente;
        					return dni_paciente;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var paciente = "";
        					if(row.paciente!= null)paciente += row.paciente;
        					return paciente;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
    				{
                        "mRender": function (data, type, row) {
                            var dni_medico = "";
        					if(row.dni_medico!= null)dni_medico = row.dni_medico;
        					return dni_medico;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                    },                   
                    {
                        "mRender": function (data, type, row) {
                            var medico = "";
        					if(row.medico!= null)medico += row.medico;
        					return medico;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var nombre_establecimiento = "";
                            if(row.nombre_establecimiento!= null)nombre_establecimiento = row.nombre_establecimiento;
                            return nombre_establecimiento;
                        },
                        "bSortable": false,
                        "aTargets": [5]                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var nivel_establecimiento = "";
                            if(row.nivel_establecimiento!= null)nivel_establecimiento = row.nivel_establecimiento;
                            return nivel_establecimiento;
                        },
                        "bSortable": false,
                        "aTargets": [6]                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var nombre_servicio = "";
                            if(row.nombre_servicio!= null)nombre_servicio = row.nombre_servicio;
                            return nombre_servicio;
                        },
                        "bSortable": false,
                        "aTargets": [7] 
                    },
                    {
                                        "mRender": function (data, type, row) {
                                            var id_trama = "";
                                            if(row.id_trama!= null)id_trama = row.id_trama;
                                            return id_trama;
                                        },
                                        "bSortable": false,
                                        "aTargets": [8] 
                                        
                                    },
                                    {
                                      "mRender": function (data, type, row) {
                          var id = row[0]; // 

                          return '<a href="/editar_all_auditoria/'+row.id_trama+'"  class="btn btn-success btn-xs" title="Formulario"><i class="fa fa-eye"></i></a>';
                      },
                      "bSortable": false,
                      "aTargets": [9]

                      },

                      {
                                            "mRender": function (data, type, row) {
                          var id = row[0]; // 

                         // return '<a href="/editar_auditoria/'+row.id_cups+'"  class="btn btn-warning btn-xs" title="Formulario"><i class="fa fa-arrow-up"></i></a>';
                         return '<a href="/modificar_auditoria/'+row.id_trama+'"  class="btn btn-warning btn-xs" title="Formulario"><i class="fa fa-pencil-square-o"></i></a>';
                      },
                      "bSortable": false,
                      "aTargets": [10]

                      },
                      {
                      "mRender": function (data, type, row) {
                            var html = "";
                            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Formato Fups</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/pdf_formato_fups/'+row.id_prestacion+'" class="btn btn-primary btn-xs" style="background-color: #337AB7; color: #fff; border-color: #337AB7;"><i class="fa fa-pencil-square-o"></i></a></div>';
                            return html;
                      },
                      "bSortable": false,
                      "aTargets": [11]
                      },
                      {
                      "mRender": function (data, type, row) {
                        var editado = "";
                        if(row.actualizacon_trama != '2')editado = 'NO';
                        if(row.actualizacon_trama == '2')editado = 'SI';
                            return editado;
                        },
                      "bSortable": false,
                      "aTargets": [12]
                      },
                      {
                      "mRender": function (data, type, row) {
                          if(row.id_estado != '9'){
                              if(row.id_estado == '0'){
                                var html = "";
                              //  html += '<a href="javascript:void(0)" aria-hidden="true" data-id_trama="'+row.id_estado+'"  data-id_estado="'+row.id_trama+'"  id="validarws" data-toggle="tooltip" title="Validar Envio"';
                               // html += 'class="btn btn-success btn-xs "> <i class="fa fa-pencil-square-o"></i></a>';
                                html += '<a href="javascript:void(0)" aria-hidden="true" data-id_trama="'+row.id_estado+'"  data-id_estado="'+row.id_trama+'"  id="validarws" data-toggle="tooltip" title="Validar Envio"';
                                html += ' class="btn btn-xs" style="background-color: #337AB7; color: #fff; border-color: #337AB7;"> <i class="fa fa-pencil-square-o"></i></a>';

                                return html;
                              }else{
                                return '<a href="#" onclick="return false;"  class="btn btn-info btn-xs " disabled=""title="Envio Tram"><i class="fa fa-pencil-square-o"></i></a>';
                              }
                          }else{
                            return '<a href="#" onclick="return false;" class="btn btn-info btn-xs disabled" title="Envio Trama">  <i class="fa fa-pencil-square-o"></i></a>';
                          }
                          
                      },
                      "bSortable": false,
                      "aTargets": [13]
                      },
                      {
                      "mRender": function (data, type, row) {
                        var editado = "";
                        if (row.id_estado == '0') {
                            editado = 'NO ENVIADO';
                        } else if (row.id_estado == '1') {
                            editado = 'ENVIADO CONFORME ';

                        } else if (row.id_estado == '3') {
                            editado = 'ENVIADO OBSERVADO ' ;
                            
                        }
                            return editado;
                        },
                      "bSortable": false,
                      "aTargets": [14]
                      },
                      {
                      "mRender": function (data, type, row) {
                          if(row.id_estado != '9'){
                              if(row.id_estado >= '1'){
                                var html = "";
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Respuesta Saludpol</b>" style="float:left;padding-left:3px">';
                                    html += '<a href="/ver_datos_enviados/'+row.id_trama+'" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o"></i></a></div>';
                                return html;
                              }else{
                                return '<a href="#" onclick="return false;"  class="btn btn-success btn-xs " disabled=""title="Formulario"><i class="fa fa-pencil-square-o"></i></a>';
                              }
                          }else{
                              return '<a href="#" onclick="return false;" class="btn btn-info btn-xs disabled" title="Envio Trama">  <i class="fa fa-pencil-square-o"></i></a>';
                          }
                      },
                      "bSortable": false,
                      "aTargets": [15]
                      },
                      {
                        "mRender": function (data, type, row) {
                            return '<input type="checkbox" class="row-select">';
                        },
                        "bSortable": false,
                        "aTargets": [16]
                      }
                ]
        });

        const tableApi = oTable1.api();

        $('#selectAll').on('click', function(){
          const checkboxes = $('input.row-select', tableApi.rows({'search':'applied'}).nodes());
          checkboxes.prop('checked', false);
          checkboxes.slice(0, 10).prop('checked', this.checked);
        });

        $('#tblEmergenciaTriaje tbody').on('change', 'input.row-select', function(){
          var checkboxesChecked = $('input.row-select:checked', tableApi.rows({'search':'applied'}).nodes()).length;
          if(checkboxesChecked === 0){
              $('#selectAll').prop('checked', false).prop('indeterminate', false);
          } else if(checkboxesChecked > 0 && checkboxesChecked <= 10){
              $('#selectAll').prop('checked', true).prop('indeterminate', true);
          } else {
              $('#selectAll').prop('checked', true).prop('indeterminate', false);
          }
        });
    }

    function fn_ListarBusqueda() {
       datatablenew();
    }

    function anularAtencion(id_atencion){
    
        bootbox.confirm({ 
            size: "small",
            message: "&iquest;Deseas finalizar la atenci&oacute;n de triaje?", 
            callback: function(result){
                if (result==true) {
                    fn_anularAtencion(id_atencion);
                }
            }
        });
        
    }
    function reporteFema(id_opcion,idUser){

location.href = 'enviar_masivo_ws/'  + id_opcion;

}
    function fn_anularAtencion(id){
        
        $.ajax({
            url: '/cerrar_emergencia_triaje/' + id,
            dataType: "json",
            success: function(result){
                if(result.sw == true){
                    location.href="all_triajes_atendidas_by_emergencia";
                }
            }
        });
    }
    
    function obtenerFilasSeleccionadas() {
      const tableApi = oTable1.api();
      const seleccionadas = [];
      $('input.row-select:checked', tableApi.rows({'search':'applied'}).nodes()).each(function() {
        const fila = $(this).closest('tr');
        const data = tableApi.row(fila).data();
        seleccionadas.push(data);
      });
      return seleccionadas;
    }

    function onValidateMassive() {
      const filas = obtenerFilasSeleccionadas();
      if (!filas || filas.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: '¡Atención!',
          text: 'Debe seleccionar al menos un registro para continuar.',
        });
        return;
      }
      let arrayIds = filas.map(fila => fila.id_trama);
      // let arrayIds = ['307129', '312584', '10025', '229413'];
      $.ajax({
        url: '/api/auditoria/validacion-masiva',
        type: 'POST',
        dataType: 'json',
        data: { 
          ids: arrayIds, 
          _token: $('#_token').val() 
        },
        beforeSend: function() {
          Swal.fire({
            title: 'Validando registros...',
            html: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
          });
        }
      })
      .done(function(response) {
        Swal.close();
        if (response && response.data.length > 0) {
          $('#modalValidationTramas').modal({ backdrop: 'static', keyboard: false, show: true });
          builTableValidacion(response.data);
        }
      })
      .fail(function(xhr, status, error) {
        Swal.close();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'No se pudo procesar la solicitud: ' + error
        });
      });
    }

    function builTableValidacion(data) {
      const table = $('#tableValidacionesTramas').DataTable({
        searching: false,
        paging: false,
        info: false,
        ordering: false,
        lengthChange: false,
        destroy: true,
        data: data,
        columns: [
          {
            data: null,
            orderable: false,
            className: 'dt-control text-center',
            render: function (data, type, row) {
              return row.isValid ? '' : '<i class="fa fa-plus-circle text-warning expand-toggle"></i>';
            }
          },
          { data: 'trama_id' },
          {
            data: null,
            render: function (data, type, row) {
              return row.isValid ? '<span class="label label-success">Válido</span>' : '<span class="label label-danger">Inválido</span>';
            }
          }
        ]
      });
      $('#tableValidacionesTramas tbody').on('click', 'tr', function () {
        const table = $('#tableValidacionesTramas').DataTable();
        const row = table.row(this);
        if (row.data().isValid) return;
        toggleRow($(this));
      });
      // $('#tableValidacionesTramas tbody').on('click', '.expand-toggle', function (e) {
      //   e.stopPropagation();
      //   const tr = $(this).closest('tr');
      //   toggleRow(tr);
      // });
    }

    function formatDetalle(rowData) {
      let html = `
        <div class="child-content">
          <table class="table table-condensed table-bordered">
            <thead>
              <tr>
                <th styles='text-align: center;'>Regla</th>
                <th>Mensaje</th>
                <th>Detalles</th>
              </tr>
            </thead>
            <tbody>
      `;
      rowData.detail.forEach(d => {
        let dataDetalle = '';
        if (Array.isArray(d.data) && d.data.length > 0) {
          dataDetalle += '<ul class="list-unstyled" style="margin-bottom:0">';
          d.data.forEach((obj, index) => {
            Object.entries(obj).forEach(([key, value]) => {
              dataDetalle += `<div><strong>${key}:</strong> ${value}</div>`;
            });
            if (index < d.data.length - 1) {
              dataDetalle += `<div style="height:1px; background:#ccc; margin:6px 0;"></div>`;
            }
          });
          dataDetalle += '</ul>';
        } else {
          dataDetalle = `<em>Sin detalle</em>`;
        }
        html += `
          <tr>
            <td style='text-align: center;'><strong>${d.regla}</strong></td>
            <td>${d.message}</td>
            <td>${dataDetalle}</td>
          </tr>
        `;
      });
      html += `</tbody></table></div>`;
      return html;
    }

    function toggleRow(tr) {
      const table = $('#tableValidacionesTramas').DataTable();
      const row = table.row(tr);
      const icon = tr.find('.expand-toggle');
      if (row.child.isShown()) {
        $('div.child-content', row.child()).slideUp(200, function () {
          row.child.hide();
          tr.removeClass('shown');
          icon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
        });
      } else {
        row.child(formatDetalle(row.data())).show();
        tr.addClass('shown');
        icon.removeClass('fa-plus-circle').addClass('fa-minus-circle');
        $('div.child-content', row.child()).hide().slideDown(200);
      }
    }

</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
  <div class="panel-heading" style="padding: 0;">
  <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(255, 24, 24); color: rgb(255, 255, 255);">E</span> Lista de Auditoria Emergencia</h3>
    </div>
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_referencia_ajax')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
        <input type="hidden" name="id_establecimiento" id="id_establecimiento"

          value="{{Auth::user()->id_establecimiento}}">
        <div class="row">
          <div class="row">
                <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Fecha Desde</label>
                  <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
                    value="<?php echo $fechaDesde?>" maxlength="10">
                </div>
                <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Fecha Hasta</label>
                  <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
                    value="<?php echo $fechaHasta?>" maxlength="10">
                </div>
                <div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Nro. Documento</label>
                  <input id="dni" name="dni" type="text" placeholder=""
                    class="form-control input-sm" maxlength="12" onkeypress="return esEntero(event);" value="">
                </div>

                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Nombres</label>
                  <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                    class="form-control input-sm text-uppercase" maxlength="60" onkeypress="return noEspaciosInicioSoloLetras(event,'nombre_beneficiario');" value="<?php echo $nombre_beneficiario ?>">
                </div>

                <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
                
                  <div class="col-md-4">
                    <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                  </div>
                  <div class="col-md-8">
                                    
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Todos">
                                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('0','{{ Auth::user()->id }}')" >
                                           Envio Masivo Emergencia
                                        </a>
                                    </div>
                             
                            </div> 

                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                  <label class="form-control-sm">Estado</label>
                        <select class="form-control input-sm " id="estado" name="estado"  tabindex="3">
                          <option value="10">TODOS</option>
                          <option value="0">No enviado a SaludPol</option>
                          <option value="1">Enviados a SaludPol</option>
                          <option value="2">Procesando por SaludPol</option>
                          <option value="3">Observado en el Envio a SaludPol</option>
                          <option value="4">Aprobados por SaludPol</option>
                          <option value="5">Observado por las Reglas de SaludPol</option>
                            <!--option value="8">Modificado por Auditoria</option-->
                        </select>
                </div>

                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                  <label class="form-control-sm">NIVELES</label>
                  <select class="form-control input-sm" id="buscar_ipress_origen_nivel" name="buscar_ipress_origen_nivel" tabindex="3">
                      <option value="0">TODOS</option>
                      <?php foreach($niveles as $row): ?>
                          <option value="<?php echo $row->nivel ?>"><?php echo $row->nivel ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>


       <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
    <label class="form-control-sm">ESTABLECIMIENTO</label>
    <select class="form-control input-sm" id="buscar_ipress_origen_establecimiento" name="buscar_ipress_origen_establecimiento" tabindex="3">
      <?php if($id_establecimiento == '76'):  ?>
              <option value="0">TODOS</option>
              <?php  endif;?>
        <?php foreach($establecimientos as $row): ?>
            <option value="<?php echo $row->id ?>"><?php echo $row->nombre?></option>
        <?php endforeach; ?>        
    </select>
</div>



<div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
              <label class="form-control-sm">SERVICIO</label>
              <select class="form-control input-sm" id="buscar_ipress_origen_servicio" name="buscar_ipress_origen_servicio" tabindex="3">
                  <option value="0">TODOS</option>
                  <?php foreach($servicios as $row): ?>
                  <option value="<?php echo $row->id ?>"><?php echo $row->nombre?></option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
            <label class="form-control-sm">MEDICO</label>
            <select class="form-control input-sm" id="buscar_ipress_origen_medico" name="buscar_ipress_origen_medico" tabindex="3">
                <option value="0">TODOS</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
          <label class="form-control-sm">MODIFICADO POR AUDITORIA</label>
          <select class="form-control input-sm " id="editado_auditoria" name="editado_auditoria"  tabindex="3">
            <option value="0">TODOS</option>
            <option value="2">SI</option>
            <option value="1">NO</option>
          </select>
      </div>
      <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12"></div>
        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12"></div>
        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12"></div>
        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12"></div>
        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12"></div>
        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Todos" style="margin-top: 10px">
                <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="onValidateMassive()" >VALIDACIÓN MASIVA</a>
            </div>
        </div>
      </div>
      </div>
        </div>
      </form>
      <div class="col-md-12" id="global">
      <div class="table-responsive">
        <table id="tblEmergenciaTriaje" class="table table-bordered">
          <thead>
            <tr>
              <th>N</th>
              <th>Dni Paciente</th>
              <th>Nombres Paciente</th>
              <th>Dni Medico</th>
              <th>Medico de Alta</th>
              <th>Establecimiento</th>
              <th>Nivel</th>
              <th>Servicio</th>
              <th>Id Trama</th>
              <th>Ver Detalle</th>
              <th>Editar Trama</th>
              <th>Formato Fups</th>
              <th>Modificado por Auditor</th>
              <th>Envio Dirsapol </th>
              <th>Estado Trama </th>
              <th>Respuesta Saludpol</th>
              <th><input type="checkbox" id="selectAll"></th>
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

@include('frontend.auditoria.components.modal_resultados_validacion')

@include('frontend.includes.footer')
@endsection