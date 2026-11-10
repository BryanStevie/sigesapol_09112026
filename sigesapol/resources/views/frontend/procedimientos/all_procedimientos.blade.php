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

    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
   
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
            //"dateFormat": "YYYY-MM-DD hh:mm",
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
    /*"searching": false*/
    "language": {
    "emptyTable": "No se encontraron resultados"
    }
    });
    $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable();
    dataTable.fnFilter(this.value);
    });
    $("#iddispensador").select2({
    width: 'resolve' // need to override the changed default
    });
    $("#iddispensador").trigger('change');
    });
    
	function reporteAtencion(idUser){

		var fecha_ini = $('#fecha_ini').val();
		var fecha_fin = $('#fecha_fin').val();
		var dni = $('#dni_beneficiario').val();
		var paciente = $('#nombre_beneficiario').val();
		var estado = $('#estado').val();
		var id_servicio = $('#id_servicio').val();
		var id_consultorio = $('#id_consultorio').val();
		var id_medico = $('#id_medico').val();
        var id_atencione = $('#id_atencione').val();
		
		if (fecha_ini == "")fecha_ini = 0;
		if (fecha_fin == "")fecha_fin = 0;
		if (dni == "")dni = 0;
		if (paciente == "")paciente = 0;
		if (estado == "")estado = 0;
		if (id_servicio == "")id_servicio = 0;
		if (id_consultorio == "")id_consultorio = 0;
		if (id_medico == "")id_medico = 0;
        if (id_atencione == "")id_atencione = 0;
		location.href = 'exportar_reporte_atencion/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' + paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio + '/' + id_medico + '/' + id_atencione;
        
		
    }
	
	function reporteAtencionDetallado(idUser){

		var fecha_ini = $('#fecha_ini').val();
		var fecha_fin = $('#fecha_fin').val();
		var dni = $('#dni_beneficiario').val();
		var paciente = $('#nombre_beneficiario').val();
		var estado = $('#estado').val();
		var id_servicio = $('#id_servicio').val();
		var id_consultorio = $('#id_consultorio').val();
		var id_medico = $('#id_medico').val();
        var id_atencione = $('#id_atencione').val();
		
		if (fecha_ini == "")fecha_ini = 0;
		if (fecha_fin == "")fecha_fin = 0;
		if (dni == "")dni = 0;
		if (paciente == "")paciente = 0;
		if (estado == "")estado = 0;
		if (id_servicio == "")id_servicio = 0;
		if (id_consultorio == "")id_consultorio = 0;
		if (id_medico == "")id_medico = 0;
        if (id_atencione == "")id_atencione = 0;
		location.href = 'exportar_reporte_atencion_detallado/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' + paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio + '/' + id_medico + '/' + id_atencione;
		
    }

    function ver_det_receta(idUser, nro_receta, id_receta){
    $('#nrorecmodal').text(nro_receta);
    $.ajax({
    url: '/ver_receta_atendida/2/' + idUser + '/' + nro_receta + '/' + id_receta,
            async: false,
            success: function(result){
            $("#muestra-det-receta").html(result);
            },
            complete: function () {
            $("#myModalDetRV").modal();
            }
    });
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
	$("#id_servicio").select2({width: 'resolve'});
	$("#id_consultorio").select2({width: 'resolve'});
    $("#id_atencione").select2({width: 'resolve'});
	datatablenew();
	
});

var m = 0;

function datatablenew(){
    var oTable1 = $('#tblPrestacion').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/listar_procedimientos_ajax",
        "bProcessing": true,
        "sPaginationType": "full_numbers",
        //"paging":false,
        "bFilter": false,
        "bSort": false,
        "info": true,
		//"responsive": true,
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
			var estado = $('#estado').val();
			var id_servicio = $('#id_servicio').val();
			var id_consultorio = $('#id_consultorio').val();
			var id_medico = $('#id_medico').val();
            var id_establecimiento = $('#id_establecimiento').val();
            var id_atencione = $('#id_atencione').val();
			var _token = $('#_token').val();
            oSettings.jqXHR = $.ajax({
				"dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
						fecha_ini:fecha_ini,fecha_fin:fecha_fin,dni_beneficiario:dni_beneficiario,nombre_beneficiario:nombre_beneficiario,estado:estado,id_establecimiento:id_establecimiento,id_servicio:id_servicio,id_consultorio:id_consultorio,id_medico:id_medico,id_atencione:id_atencione,
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
            [	{
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
                    var seccion = "";
					if(row.seccion!= null)seccion = row.seccion;
					return seccion;
                },
                "bSortable": false,
                "aTargets": [1]
                },
				,
                {
                "mRender": function (data, type, row) {
                    var subseccion = "";
                    if(row.subseccion!= null)subseccion = row.subseccion;
                    return subseccion;
                },
                "bSortable": false,
                "aTargets": [2]
                },
                {
                "mRender": function (data, type, row) {
                    var codigo = "";
                    if(row.codigo!= null)codigo = row.codigo;
                    return codigo;
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
                    var genero = "";
                    if(row.genero!= null){
                        switch (row.genero) {
                                        case '1': genero = "Masculino"; break;
                                        case '2': genero = "Femenino"; break;
                                        case '3': genero = "Ambos"; break;
                        }
                    }
                    return genero;
                },
                "bSortable": false,
                "aTargets": [5]
                },
                {
                "mRender": function (data, type, row) {
                    var t_nivel1 = "";
                    if(row.t_nivel1!= null)t_nivel1 = row.t_nivel1;
                    return t_nivel1;
                },
                "bSortable": false,
                "aTargets": [6]
                },
                {
                "mRender": function (data, type, row) {
                    var t_nivel2 = "";
                    if(row.t_nivel2!= null)t_nivel2 = row.t_nivel2;
                    return t_nivel2;
                },
                "bSortable": false,
                "aTargets": [7]
                },
                {
                "mRender": function (data, type, row) {
                    var t_nivel3 = "";
                    if(row.t_nivel3!= null)t_nivel3 = row.t_nivel3;
                    return t_nivel3;
                },
                "bSortable": false,
                "aTargets": [8]
                },
                {
                "mRender": function (data, type, row) {
                    var convenio = "";
                    if(row.convenio!= null){
                        switch (row.convenio) {
                            case '1': convenio = "SI"; break;
                            case '2': convenio = "NO"; break;
                        }
                    }
                    return convenio;
                },
                "bSortable": false,
                "aTargets": [9]
                },
                {
                "mRender": function (data, type, row) {
                    var estado = "";
                    if(row.estado!= null){
                        switch (row.estado) {
                            case '1': estado = "ACTIVO"; break;
                            case '2': estado = "INACTIVO"; break;
                        }
                    }
                    return estado;
                },
                "bSortable": false,
                "aTargets": [10]
                },
                /*{
                "mRender": function (data, type, row) {
                    var estado1 = "";
                    if(row.estado!= null){
                        switch (row.estado) {
                            case '1': estado1 = "ACTIVO"; break;
                            case '2': estado1 = "INACTIVO"; break;
                        }
                    }
                    return estado1;
                },
                "bSortable": false,
                "aTargets": [11]
                },
                {
                "mRender": function (data, type, row) {
                    var estado2 = "";
                    if(row.estado!= null){
                        switch (row.estado) {
                            case '1': estado2 = "ACTIVO"; break;
                            case '2': estado2 = "INACTIVO"; break;
                        }
                    }
                    return estado2;
                },
                "bSortable": false,
                "aTargets": [12]
                },*/

            ]
    });
}

function fn_ListarBusqueda() {
    datatablenew();
};

function getSubConsultorio() {

	var id_establecimiento = $('#id_establecimiento').val();
	var id_servicio = $('#id_servicio').val();
	//var id_tipo_atencion = $('#id_tipo_atencion').val();
	$.ajax({
			url: '/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' + id_servicio,
			success: function (result) {
			var newOption = "";
			var reg = result.length;
			if(reg >= 2){
				newOption = "<option value='0'>--Seleccionar-</option>";
			}
			$(result).each(function (ii, oo) {
				newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
			});
			$('#id_consultorio').html(newOption);
			$("#id_consultorio").select2({max_selected_options: 4});
		}
	});
}

function anularAtencion(id_atencion){
	
	bootbox.confirm({ 
        size: "small",
        message: "&iquest;Deseas anular la atenci&oacute;n medica?", 
        callback: function(result){
            if (result==true) {
                fn_anularAtencion(id_atencion);
            }
        }
    });	
}

function fn_anularAtencion(id_prestacion){
	
	$.ajax({
		url: '/anular_prestacion/' + id_prestacion,
		dataType: "json",
		success: function(result){
			
			if(result.sw == true){
				location.href="all_citas_medico_atenciones";
			}
		}
	});
}

function modalHistoria(id){
	
	$(".modal-dialog").css("width","80%");
	$('#openOverlayOpc').modal('show');
	$('#openOverlayOpc .modal-body').css('height', 'auto');

	$.ajax({
			url: "modal_historia/"+id,
			type: "GET",
			success: function (result) {  
					$("#diveditpregOpc").html(result);
			}
	});

}

	
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Lista de Procedimientos Medicos</h3>
        </div>
        <div class="panel-body">
			
			<form class="form-horizontal" method="post" action="{{ route('listar_citas_medico_atenciones')}}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
			<!--input type="hidden" name="id_farmacia" id="id_farmacia" value=""-->
			<!--input type="hidden" name="id_establecimiento" id="id_establecimiento" value=""-->
			<input type="hidden" name="id_medico" id="id_medico" value="<?php //echo $medico?>">
			
			
			<div class="row">
    			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
    				<label class="form-control-sm">Fecha Desde</label>
    				<input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="<?php //echo $fechaDesde?>" maxlength="10">
    			</div>
    			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
    				<label class="form-control-sm">Fecha Hasta</label>
    				<input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="<?php //echo $fechaDesde?>" maxlength="10">
    			</div>
    			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
    				<label class="form-control-sm">Nro. Documento</label>
    				<input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php //echo $dni_beneficiario ?>">
    			</div>
    			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
    				<label class="form-control-sm">Nombres</label>
    				<input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="60" value="<?php //echo $nombre_beneficiario ?>">
    			</div>
    			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
    				<div class="col-md-6">
    					<a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteAtencion('{{ Auth::user()->id }}')" >
    						<i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
    					</a>
    				</div>
    				<div class="col-md-6" style="padding-right:0px;padding-left:0px">
    					<a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteAtencionDetallado('{{ Auth::user()->id }}')" >
    						<i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Detallado
    					</a>
    				</div>
    			</div>
			</div>
			<div class="row">
    			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
    				<label class="form-control-sm">Servicio</label>
    				<select class="form-control input-sm" name="id_servicio" id="id_servicio" onchange="getSubConsultorio()">
    					<option value="0">-Seleccionar-</option>
    					<?php // foreach($consultorio as $row):?>
    					<!--option value="<?php // echo $row->id?>"><?php // echo $row->nombre?></option-->
    					<?php //endforeach;?>
    				</select>
    			</div>
    			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
    				<label class="form-control-sm">Consultorio</label>
    				<select class="form-control input-sm" name="id_consultorio" id="id_consultorio" required="">
    					<option value="0">-Seleccionar-</option>
    				</select>
    			</div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Atenciones</label>
                    <select class="form-control input-sm" name="id_atencione" id="id_atencione">
                        <option value="0">-Seleccionar-</option>
                        <?php  //foreach($atencione as $row):?>
                        <option value="<?php //echo $row->id?>"><?php //echo $row->nombre?></option>
                        <?php //endforeach;?>
                    </select>
                </div>
    			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
    				<label class="form-control-sm">Estado</label>
    				<select class="form-control input-sm" id="estado" name="estado" tabindex="5" style="width: 100%">
    					<option value="1" selected="selected">Atendido</option>
    					<option value="0">Anulado</option>
    				</select>
    			</div>
    			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-md-offset-1" style="padding-top:20px;padding-right:0px">
    				<div class="col-md-6">
    					<input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
    				</div>
    				<div class="col-md-6" style="padding-right:0px;padding-left:0px">
    					<a class="btn btn-success form-control input-sm" href="formulario_atencion_nuevo">Nuevo</a>
    				</div>
    			</div>
            </div>
            </form>
            <div class="col-md-12"  id="global">
                <table id="tblPrestacion" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N</th>
							<th>Seccion</th>
                            <th>Subseccion</th>
                            <th>Codigo</th>
							<th>Descripcion</th>
							<th>Genero</th>
                            <th>Tarifario N1</th>
                            <th>Tarifario N2</th>
                            <th>Tarifario N3</th>
							<th>Convenio</th>
							<th>Estado</th>
							<!--th style="text-align:center">Editar</th>
                            <th style="text-align:center">Desactivar</th-->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalDetRV" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE RECETA VALE NRO. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-receta"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
