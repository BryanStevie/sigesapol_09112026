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
        /* width: 100%; */
        /* border: 1px solid #ddd; */
        margin-top:15px
            /* background: #f1f1f1;*/
            /*overflow-y: scroll !important;*/
    }

    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .row{
        margin-top:10px;
        padding: 0 10px;
    }
    .clickable{
        cursor: pointer;
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .panel-body{
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
	
	.modal-dialog{
        width: 70% !important;
    }
	
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
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
    
    
    function reporteEmergenciaTriaje(idUser){

    		var fecha_ini = $('#fecha_ini').val();
    		var fecha_fin = $('#fecha_fin').val();
    		
            if (fecha_ini == "")fecha_ini = 0;
    		if (fecha_fin == "")fecha_fin = 0;
    		
            location.href = 'exportar_reporte_emergencia_triaje/' + idUser + '/' + fecha_ini + '/' + fecha_fin ;
            
    		
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
        var oTable1 = $('#tblSuperUsuario').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_usuarios_modificados_ajax",
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
                var dni_beneficiario = $('#dni_beneficiario').val();
                var nombre_beneficiario = $('#nombre_beneficiario').val();
                var dni_administrador = $('#dni_administrador').val();
                var nombre_administrador = $('#nombre_administrador').val();
                var establecimiento = $('#establecimiento').val();
                var estado = $('#estado').val();
                var opcion = $('#opcion').val();
                var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				"dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,opcion:opcion,
    						fecha_ini:fecha_ini,fecha_fin:fecha_fin,dni_beneficiario:dni_beneficiario,nombre_beneficiario:nombre_beneficiario,estado:estado,establecimiento:establecimiento,dni_administrador:dni_administrador,nombre_administrador:nombre_administrador,
    						_token:_token
                           },
                    "success": function (result) {
                        fnCallback(result);
                    },
                    "error": function (msg, textStatus, errorThrown) {
                        //location.href="login";
                    }
                });
            },

            "aoColumnDefs":
                [	
                    {
                        "mRender": function (data, type, row) {
                            var id = "";
                            if(row.id!= null)id = row.id;
                            return id;
                        },
        				"bSortable": false,
                        "aTargets": [0]
                        },
    				{
                        "mRender": function (data, type, row) {
                            var fecha_ingreso = "";
                            if(row.fecha_ultimo_update!= null)fecha_ingreso = row.fecha_ultimo_update;
                            return fecha_ingreso;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var hora_atencion = "";
                            if(row.hora_ultimo_update!= null)hora_atencion = row.hora_ultimo_update;
                            return hora_atencion;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var nuevo = "";
                            if(row.estado == 1)nuevo = "Activo" ;else nuevo = "Inactivo";
                            return nuevo;
                        },
                        "bSortable": false,
                        "aTargets": [3] 
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var prioridad = "";
                            if(row.dni!= null)prioridad = row.dni;
                            return prioridad;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var descripcion = "";
                            if(row.paterno!= null)descripcion += row.paterno;
        					if(row.materno!= null)descripcion += " "+row.materno;
        					if(row.nombre!= null)descripcion += " ,"+row.nombre;
                            return descripcion;
                        },
                        "bSortable": false,
                        "aTargets": [5]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var detalle = "";
                            if(row.detalle == 1)detalle = "Contraseña";
                            else if(row.detalle == 2) detalle = "Permisos De Usuarios";
                            else if(row.detalle == 3) detalle = "Estado de  Usuario";
                            else if(row.detalle == 4) detalle = "Datos del Servicio";
                            else if(row.detalle == 5) detalle = "Flags  de Permisos";
                            else if(row.detalle == 6) detalle = "Estado y Datos";
                            else if(row.detalle == 7) detalle = "Estado y flags";
                            else if(row.detalle == 8) detalle = "Datos y flags";
                            else if(row.detalle == 9) detalle = "Estado, Datos y Flags";
                            else if(row.detalle == 10) detalle = "Creacion de Usuario";
                            else if(row.detalle == 11) detalle = "Otros";
                            else  detalle = row.detalle;
                            return detalle;
                        },
                        "bSortable": false,
                        "aTargets": [6]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var servi = "";
                            if(row.servicios!= null && row.servicios!= "")servi = row.servicios;else servi = "---";
                            return servi;
                        },
                        "bSortable": false,
                        "aTargets": [7]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var prioridad = "";
                            if(row.flags!= null && row.flags!= "")prioridad = row.flags;else prioridad = "---";
                            return prioridad;
                        },
                        "bSortable": false,
                        "aTargets": [8]
                        
                    },
    				{
                        "mRender": function (data, type, row) {
                            var dni_beneficiario = "";
        					if(row.establecimiento_editor!= null)dni_beneficiario = row.establecimiento_usu;
        					return dni_beneficiario;
                        },
                        "bSortable": false,
                        "aTargets": [9]
        				
                    },
                    {
                        "mRender": function (data, type, row) {
                            var sexo = "";
                            if(row.dni_edito!= null)sexo = row.dni_edito;
                            return sexo;
                        },
                        "bSortable": false,
                        "aTargets": [10]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var asegurado = "";
        					if(row.paterno!= null)asegurado += row.paterno_editor;
        					if(row.materno!= null)asegurado += " "+row.materno_editor;
        					if(row.nombre!= null)asegurado += " ,"+row.nombre_editor;
        					return asegurado;
                        },
                        "bSortable": false,
                        "aTargets": [11]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var edad = "";
                            if(row.establecimiento_editor!= null)edad = row.establecimiento_editor;
                            return edad;
                        },
                        "bSortable": false,
                        "aTargets": [12] 
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var edad = "";
                            edad = row.anos + " años " + row.meses + ", meses y " + row.dias + " días";
                            return edad;
                            
                            return $edad;
                        },
                        "bSortable": false,
                        "aTargets": [13] 
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var html = "";
                                html +=
                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Permisos</b>">';
                                html += '<a href="/ver_permisos/<?php echo Auth::user()->id ?>/' + row.dni +
                                '" class="btn btn-success btn-xs"><i class="fa fa-search"></i></a></div>';
                            
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [14] 
                        
                    },
    				{
                        "mRender": function (data, type, row) {
                            var html = "";
                                html +=
                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Bitacora de modificacion</b>">';
                                html += '<a href="/bitacora_modificacion/<?php echo Auth::user()->id ?>/' + row.dni +
                                '" class="btn btn-success btn-xs"><i class="fa fa-search"></i></a></div>';
                            
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [15] 
                        
                    },
                ]
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

</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Lista de los Usuarios Modificados </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="">
    			<input type="hidden" name="_token" value="{{ csrf_token() }}">
    			<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
    			<input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
                <input type="hidden" name="opcion" id="opcion" value="{{$opcion}}">
    			<div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-10">
                            <div class="col-sm-12">
                                <div class="col-sm-3">
                                    <label class="form-control-sm">Fecha Desde</label>
                                    <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="<?php echo $fechaDesde?>" maxlength="10">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-control-sm">Fecha Hasta</label>
                                    <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="<?php echo $fechaHasta?>" maxlength="10">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-control-sm">Nro. Documento</label>
                                    <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
                                    class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-control-sm">Nombres</label>
                                    <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                                    class="form-control input-sm text-uppercase" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                            <br>
                                <div class="col-sm-3">
                                    <label class="form-control-sm">Estado</label>
                                    <select class="form-control" name="estado"  id="estado" tabindex="15">
                                        <option value="2">Todos</option>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-control-sm">Establecimiento</label>
                                    <select class="form-control "  name="establecimiento" id="establecimiento"  style="width: 100%">
                                    <option value="0">Todos</option>
                                    <?php  
                                        foreach($establecimientos as $row): 
                                    ?>
                                    <option value="<?php echo $row->id?>"> <?php echo $row->nombre ?> </option>
                                    <?php endforeach; ?>
                                    
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-control-sm">Nro. Documento del Administrador</label>
                                    <input id="dni_administrador" name="dni_administrador" type="text" placeholder=""
                                    class="form-control input-sm" maxlength="12" value="<?php echo $dni_administrador ?>">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-control-sm">Nombres del Administrador</label>
                                    <input id="nombre_administrador" name="nombre_administrador" type="text" placeholder=""
                                    class="form-control input-sm text-uppercase" maxlength="60" value="<?php echo $nombre_administrador ?>">
                                </div>            
                            </div>           
                        </div>
                        <div class="col-sm-2">
                            <div class="col-sm-10">
                                <br>
                                <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">  
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="col-md-12"  id="global">
                        <div class="table-responsive">
                            <table id="tblSuperUsuario" class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Fecha Edit</th>
                                        <th>Hora Edit</th>
                                        <th>Estado</th>
                                        <th>DNI User</th>
                                        <th>Datos Usuario</th>
                                        <th>Se Actualizo</th>
                                        <th>Detalle Actualizacion Datos</th>
                                        <th>Detalle Actualizacion Flags</th>
                                        <th>Establecimiento Usuario</th>
                                        <th>DNI Editador</th>
                                        <th>Datos Editador</th>
                                        <th>Establecimiento Editador</th>
                                        <th>Antiguedad de Usuario</th>
                                        <th>Historial Permisos</th>
                                        <th>Bitacora de Modificaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
          
                </div>
                </form>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
