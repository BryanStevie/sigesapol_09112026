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
        margin:15px
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
        var oTable1 = $('#tblEmergenciaTriaje').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_atencion_emergencia_ajax",
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
                var id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
                var estado = $('#estado').val();
                var opcion = $('#opcion').val();
                var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
    				"dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,opcion:opcion,
    						fecha_ini:fecha_ini,fecha_fin:fecha_fin,dni_beneficiario:dni_beneficiario,nombre_beneficiario:nombre_beneficiario,id_establecimiento:id_establecimiento,estado:estado,
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
        					if(row.fecha_ingreso!= null)fecha_ingreso = row.fecha_ingreso;
        					return fecha_ingreso;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
    				{
                        "mRender": function (data, type, row) {
                            var hora_ingreso = "";
        					if(row.hora_ingreso!= null)hora_ingreso = row.hora_ingreso;
        					return hora_ingreso;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var prioridad = "";
                            if(row.prioridad!= null)prioridad = row.prioridad;
                            return prioridad;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var descripcion = "";
                            if(row.descripcion!= null)descripcion = row.descripcion;
                            return descripcion;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                        
                    },
    				{
                        "mRender": function (data, type, row) {
                            var dni_beneficiario = "";
        					if(row.dni_beneficiario!= null)dni_beneficiario = row.dni_beneficiario;
        					return dni_beneficiario;
                        },
                        "bSortable": false,
                        "aTargets": [5]
        				
                    },
    				{
                        "mRender": function (data, type, row) {
                            var asegurado = "";
        					if(row.paterno!= null)asegurado += row.paterno;
        					if(row.materno!= null)asegurado += " "+row.materno;
        					if(row.nombre!= null)asegurado += " ,"+row.nombre;
        					return asegurado;
                        },
                        "bSortable": false,
                        "aTargets": [6]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var tipo_beneficiario = "";
                            if(row.tipo_beneficiario!= null)tipo_beneficiario = row.tipo_beneficiario;
                            return tipo_beneficiario;
                        },
                        "bSortable": false,
                        "aTargets": [7]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var sexo = "";
                            if(row.sexo!= null)sexo = row.sexo;
                            return sexo;
                        },
                        "bSortable": false,
                        "aTargets": [8]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var edad = "";
                            if(row.edadpaciente!= null)edad = row.edadpaciente;
                            return edad;
                        },
                        "bSortable": false,
                        "aTargets": [9] 
                        
                    },
    				{
                        "mRender": function (data, type, row) {
    					   var html = "";

                           if(row.estado>=2 && row.estado<6){
    					        html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Alta Administrativa</b>">';
                                html += '<a href="/alta_medica_administrativa/<?php echo Auth::user()->id ?>/' + row.id +'" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';	
                            }else{
                                if(row.estado==6){soloNumeros
                                    html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b> Ver Alta Administrativa</b>">';
                                    html += '<a href="/alta_administrativa_ver/<?php echo Auth::user()->id ?>/' + row.id +'" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a></div>';    
                                }
                                else{
                                    html += '<button class="btn btn-warning btn-xs" disabled=""><i class="fa fa-eye"></i></button>';
                                }
                            }
    					   return html;
                        },
                        "bSortable": false,
                        "aTargets": [10]
        				
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

</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Lista Altas Médicas - Emergencia Alta Administrativa</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('listar_atencion_emergencia_ajax')}}">
    			<input type="hidden" name="_token" value="{{ csrf_token() }}">
    			<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
    			<input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
                <input type="hidden" name="opcion" id="opcion" value="{{$opcion}}">
    			<div class="row">
        			<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
        				<label class="form-control-sm">Fecha Desde</label>
        				<input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="<?php echo $fechaDesde?>" maxlength="10">
        			</div>
        			<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
        				<label class="form-control-sm">Fecha Hasta</label>
        				<input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="<?php echo $fechaHasta?>" maxlength="10">
        			</div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Nro. Documento</label>
                        <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
                          class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                      </div>

                      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Nombres</label>
                        <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                          class="form-control input-sm text-uppercase" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
                      </div>
                      <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Estado</label>
                        <select class="form-control" name="estado"  id="estado" tabindex="15">
                            <option value="5" <?php if(isset($estado) && $estado == 5)echo "selected='selected'";?>>Pendientes</option>
                            <option value="6" <?php if(isset($estado) && $estado == 6)echo "selected='selected'";?>>Atendidas</option>
                        </select>
                    </div>
        			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"><br/>
                        <input type="hidden" value="<?php echo $estado?>" name="estado" id="estado" class="form-control">
        				
                            <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
        				
        			</div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"><br/>
                            <a class="btn btn-primary form-control input-sm" href="javascript:void(0)" onclick="reporteEmergenciaTriaje('{{ Auth::user()->id }}')" >
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                            </a>
                    </div>
    			</div>
            </form>
            <div class="col-md-12"  id="global">
                <table id="tblEmergenciaTriaje" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N</th>
							<th>Fecha</th>
                            <th>Hora</th>
                            <th>Prioridad</th>
							<th>Topico</th>
                            <th>Numero Doc</th>
                            <th>Paciente</th>
                            <th>Parentesco</th>
                            <th>Sexo</th>
                            <th>Edad</th>
                            <th style="text-align:center">Alta Administrativa</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
