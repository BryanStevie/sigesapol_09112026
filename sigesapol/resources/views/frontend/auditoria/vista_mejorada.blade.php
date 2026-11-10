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
  width: 100%;
  min-height: 650px !important;
  max-width: 100%;
  border: 1px solid #ddd;
  margin: 15px auto;
  padding: 15px;
  box-sizing: border-box; /* Asegura que el padding no afecte el tamaño total del contenedor */
  overflow-x: auto; /* Permite desplazamiento horizontal si es necesario */
  overflow-y: hidden; /* No permite el desplazamiento vertical */
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
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>

$(document).ready(function() {
    var establecimientosCargados = false; // Bandera para verificar si los establecimientos ya se cargaron

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
    function reporteFema(id_opcion,idUser){

location.href = 'enviar_masivo_ws/'  + id_opcion;

}
    
    function reporteEmergenciaTriaje(idUser){
    		var fecha_ini = $('#fecha_ini').val();
    		var fecha_fin = $('#fecha_fin').val();
    		
        if (fecha_ini == "")fecha_ini = 0;
    		if (fecha_fin == "")fecha_fin = 0;
    		
        location.href = '{{ url('exportar_reporte_emergencia_triaje') }}' + '/' + idUser + '/' + fecha_ini + '/' + fecha_fin ;
    }
	
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
        var oTable1 = $('#tblEmergenciaTriaje').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_auditoria_cups_ajax",
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
                var dni = $('#dni').val();
                var ipress = $('#ipress').val();
                var anio = $('#anio').val();
                var tipo_atencion = $('#tipo_atencion').val();
                var referido = $('#referido').val();
                var tipo_atencion_detalle = $('#tipo_atencion_detalle').val();
                var correlativo = $('#correlativo').val();
    			var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				      "dataType": 'json',
                  "type": "POST",
                  "url": sSource,
                  "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
                    dni:dni, ipress:ipress,
                    tipo_atencion:tipo_atencion, referido:referido,
                    tipo_atencion_detalle:tipo_atencion_detalle, correlativo:correlativo,anio:anio,
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
                            var dni_paciente = "";
                            if (row.codigo_ipress != null) dni_paciente += row.codigo_ipress + ' - ';
                            if (row.año != null) dni_paciente += row.año + ' - ';
                            if (row.intramural_extramural != null) dni_paciente += row.intramural_extramural + ' - ';
                            if (row.no_referidos_referidos != null) dni_paciente += row.no_referidos_referidos + ' - ';
                            if (row.tipo_atencion != null) dni_paciente += row.tipo_atencion + ' - ';
                            if (row.id_ext != null) dni_paciente += row.id_ext;
        					return dni_paciente;
                        },
                        "bSortable": false,
                        "aTargets": [0]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var correlativo = "";
        					if(row.fecha_atencion_fups!= null)correlativo = row.fecha_atencion_fups;
        					return correlativo;
                        },
        				"bSortable": false,
                        "aTargets": [1]
                        },
                        {
                        "mRender": function (data, type, row) {
                            var dni_paciente = "";
        					if(row.paciente_nro_doc_identidad!= null)dni_paciente = row.paciente_nro_doc_identidad;
        					return dni_paciente;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var paciente = "";
                            if (row.paciente_apellido_paterno != null) paciente += row.paciente_apellido_paterno + ' ';
                            if (row.paciente_apellido_materno != null) paciente += row.paciente_apellido_materno + ', ';
                            if (row.paciente_nombres != null) paciente += row.paciente_nombres;
        					return paciente;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                    },                  

                    {
                                        "mRender": function (data, type, row) {
                                            var id_trama = "";
                                            if(row.id_estado == '0' )id_trama = 'NO ENVIADO';
                                            if(row.id_estado == '1' )id_trama = 'ENVIADO';
                                            return id_trama;
                                        },
                                        "bSortable": false,
                                        "aTargets": [4] 
                                        
                                    },
                                    {
                                        "mRender": function (data, type, row) {
                                            var paciente = "";
                                            if (row.doc_ape_paterno != null) paciente += row.doc_ape_paterno + ' ';
                                            if (row.doc_ape_materno != null) paciente += row.doc_ape_materno + ', ';
                                            if (row.doc_nombres != null) paciente += row.doc_nombres;
                                            return paciente;
                                        },
                                        "bSortable": false,
                                        "aTargets": [5] 
                                        
                                    },
                                    {
                                      "mRender": function (data, type, row) {
                          var id = row[0]; // 

                          return '<a href="/editar_all_auditoria/'+row.id+'"  class="btn btn-success btn-xs" title="Formulario"><i class="fa fa-eye"></i></a>';
                      },
                      "bSortable": false,
                      "aTargets": [6]

                      },

                      {
                      "mRender": function (data, type, row) {
                            var html = "";
                            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Formato Fups</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/pdf_formato_fups/'+row.id_prestacion+'" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o"></i></a></div>';
                            return html;
                      },
                      "bSortable": false,
                      "aTargets": [7]
                      },

                      {
                      "mRender": function (data, type, row) {
                            var html = "";
                            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Formato Fups</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/reglas_aplicadas/'+row.id_cups+'" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o"></i></a></div>';
                            return html;
                      },
                      "bSortable": false,
                      "aTargets": [8]
                      },
                ]
        });
    }

    function fn_ListarBusqueda() {
       datatablenew();
    }

 
    
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading" style="padding: 0;">
      <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title">
        <span style="display: inline-block; padding: 10px 15px; background-color: rgb(51, 61, 183); color: white;">A</span> Lista de Auditoria
      </h3>
    </div>
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_referencia_ajax') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border: 2px solid #ddd; padding: 10px; margin-bottom: 20px;">
          
                <div class="row">
                    <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Nro. Documento</label>
                        <input id="dni" name="dni" type="text" placeholder=""
                        class="form-control input-sm" maxlength="12" onkeypress="return esEntero(event);" value="">
                    </div>
                </div>

                <div class="container-fluid">
    <!-- Fila con el Título CUPS centrado dentro de la misma fila -->
    <div class="row">
        <div class="col-12 text-center">
            <h3>CUPS</h3>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-4 col-md-2 col-sm-2 col-xs-2">
            <label class="form-control-sm">Codigo IPRESS</label>
            <select class="form-control input-sm" id="ipress" name="ipress" tabindex="3">
                  <?php foreach($establecimientos as $row): ?>
                  <option value="<?php echo $row->codigo ?>"><?php echo $row->nombre .' - '.$row->codigo?></option>
                  <?php endforeach; ?>
              </select>
    
        </div>

        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
            <label class="form-control-sm">Año</label>
            <input id="anio" name="anio" type="text" class="form-control input-sm" maxlength="2" placeholder="AA" onkeypress="return esEntero(event)" value="24">
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <label class="form-control-sm">Tipo de Atención</label>
            <select id="tipo_atencion" name="tipo_atencion" class="form-control input-sm">
                <option value="I">Intramural</option>
                <option value="E">Extramural</option>
            </select>
        </div>

        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
            <label class="form-control-sm">Referido</label>
            <select id="referido" name="referido" class="form-control input-sm">
                <option value="1">Referido</option>
                <option value="0">No Referido</option>
            </select>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <label class="form-control-sm">Tipo Atención</label>
            <select id="tipo_atencion_detalle" name="tipo_atencion_detalle" class="form-control input-sm">
                <option value="1">Consulta Externa - 1</option>
                <option value="2">Emergencia - 2</option>
                <option value="3">Hospitalización - 3</option>
            </select>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <label class="form-control-sm">Correlativo</label>
            <input id="correlativo" name="correlativo" type="text" class="form-control input-sm" maxlength="11" placeholder="####" onkeypress="return esEntero(event)">
        </div>
    </div>
</div>


            <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12" style="padding-top: 20px; padding-right: 0px;">
              <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
            </div>

        </div>

      </form>

      <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" id="global">
        
        <div class="table-responsive">
          <table id="tblEmergenciaTriaje" class="table table-bordered">
            <thead>
              <tr>
                <th>Codigo CUPS PNP</th>
                <th>Fecha Atencion</th>
                <th>Dni Paciente</th>
                <th>Nombres Paciente</th>
                <th>Estado Trama</th>
                <th>Medico Responsable</th>
                <th>Detalle Trama</th>
                <th>FUPS Trama</th>
                <th>Validar Reglas</th>
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


@include('frontend.includes.footer')
@endsection