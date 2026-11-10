<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<style>
	.alert-warning{ padding: 4px 6px 4px 6px; margin: 0px; background: #3c8dbc!important;font-weight:normal!important;color:#FFFFFF}
    .table-sortable tbody tr {
        cursor: move;
    }

    #global {
        min-height: 650px !important;
        /*width: auto;*/
        width: 99%;
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
        width: 40% !important;
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

    /*if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fecha_fin').val(fechaHastaIni);
    return false;
    }*/
    });
    });
    $('#fecha_fin').trigger('change');
    $('#fecha_ini').trigger('change');
    var activeSystemClass = $('.list-group-item.active');
    
    });
	
	/*
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
    $("#iddispensador").select2({
    width: 'resolve'
    });
    $("#iddispensador").trigger('change');
    });
	*/
	
    function reporteCita(idUser){

		var fecha_ini = $('#fecha_ini').val();
		var fecha_fin = $('#fecha_fin').val();
		var dni = $('#dni_beneficiario').val();
		var paciente = $('#nombre_beneficiario').val();
		var estado = $('#estado').val();
		var id_servicio = $('#id_servicio').val();
		var id_consultorio = $('#id_consultorio').val();
		var id_medico = $('#id_medico').val();
        var id_establecimiento = $('#id_establecimiento').val();
		
		if (fecha_ini == "")fecha_ini = 0;
		if (fecha_fin == "")fecha_fin = 0;
		if (dni == "")dni = 0;
		if (paciente == "")paciente = 0;
		if (estado == "")estado = 0;
		if (id_servicio == "")id_servicio = 0;
		if (id_consultorio == "")id_consultorio = 0;
		if (id_medico == "")id_medico = 0;
		location.href = 'exportar_citas/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' + paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio + '/' + id_medico;
		
    }

    function reporteCitaPdf(idUser){

        var fecha_ini = $('#fecha_ini').val();
        var fecha_fin = $('#fecha_fin').val();
        var dni = $('#dni_beneficiario').val();
        var paciente = $('#nombre_beneficiario').val();
        var estado = $('#estado').val();
        var id_servicio = $('#id_servicio').val();
        var id_consultorio = $('#id_consultorio').val();
        var id_medico = $('#id_medico').val();
        var id_establecimiento = $('#id_establecimiento').val();
        
        if (fecha_ini == "")fecha_ini = 0;
        if (fecha_fin == "")fecha_fin = 0;
        if (dni == "")dni = 0;
        if (paciente == "")paciente = 0;
        if (estado == "")estado = 0;
        if (id_servicio == "")id_servicio = 0;
        if (id_consultorio == "")id_consultorio = 0;
        if (id_medico == "")id_medico = 0;
        location.href = 'imprimir_citas/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' + paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio + '/' + id_medico;
        
    }

    function ver_det_receta(idUser, nro_receta, id_receta){
		$('#nrorecmodal').text(nro_receta);
		$.ajax({
		url: '/ver_receta_atendida/' + idUser + '/' + nro_receta + '/' + id_receta,
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


function anularCita(id_cita){
	
	bootbox.confirm({ 
        size: "small",
        message: "&iquest;Deseas anular la cita?", 
        callback: function(result){
            if (result==true) {
                fn_anularCita(id_cita);
            }
        }
    });
	
}

function fn_anularCita(id_cita){
	
	$.ajax({
		url: '/anular_nro_cita/' + id_cita,
		dataType: "json",
		success: function(result){
			
			if(result.sw == true){
				location.href="all_citas_de_paciente";
			}
			
		}
		
	});
	
}

function modalHistoria(id){
	
	$(".modal-dialog").css("width","80%");
	$('#openOverlayOpc').modal('show');
	$('#openOverlayOpc .modal-body').css('height', 'auto');

	$.ajax({
			url: "modal_nro_historia/"+id,
			type: "GET",
			success: function (result) {  
					$("#diveditpregOpc").html(result);
			}
	});

}

function modalAsignarConsultorio(){
	var msg = "";
	var cantht = $('#Grd2 tbody tr td').length;
	var fecha_ini = $("#fecha_ini").val();
	var fecha_fin = $("#fecha_fin").val();
	
	if(cantht == 0){msg += "Debe seleccionar minimo una Cita <br>";}
	if(fecha_ini != fecha_fin){msg += "Para asignar consultorio la Fecha desde y la Fecha hasta debe ser iguales <br>";}
	if(msg!=""){
        bootbox.alert(msg); 
        return false;
    }
	
	$(".modal-dialog").css("width","80%");
	$('#openOverlayOpc').modal('show');
	$('#openOverlayOpc .modal-body').css('height', 'auto');

	$.ajax({
			url: "modal_asignar_consultorio",
			type: "GET",
			success: function (result) {  
					$("#diveditpregOpc").html(result);
			}
	});

}
	
$(document).ready(function () {
	
	$('#btnBuscar').click(function () {
		fn_ListarBusqueda();
	});
	
	$('#example-select-all').on('click', function(){
		var rows = $('#tblCita').DataTable().rows({ 'search': 'applied' }).nodes();
		$('input[type="checkbox"]', rows).prop('checked', this.checked);
		addMovimientoAll();
	});
		
	$("#id_servicio").select2({width: 'resolve'});
	$("#id_consultorio").select2({width: 'resolve'});
	datatablenew();
	
	<?php if($id_consultorio_actual > 0){?>
		$("#id_servicio").val("<?php echo $id_consultorio_actual?>").prop('disabled',true).select2();
		getSubConsultorio();
	<?php }?>
});

var m = 0;

function datatablenew(){
    var oTable1 = $('#tblCita').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/listar_citas_ajax",
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
			var _token = $('#_token').val();
            
            oSettings.jqXHR = $.ajax({
				"dataType": 'json',
                //"contentType": "application/json; charset=utf-8",
                "type": "POST",
                "url": sSource,
                "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
						fecha_ini:fecha_ini,fecha_fin:fecha_fin,dni_beneficiario:dni_beneficiario,nombre_beneficiario:nombre_beneficiario,estado:estado,
						id_servicio:id_servicio,id_consultorio:id_consultorio,id_medico:id_medico,id_establecimiento:id_establecimiento,_token:_token,
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
                    var dia = "";
					if(row.dia!= null)dia = row.dia;
					return dia;
                },
                "bSortable": false,
                "aTargets": [1]
                },
				{
                "mRender": function (data, type, row) {
                    var hora = "";
					if(row.hora!= null)hora = row.hora;
					return hora;
                },
                "bSortable": false,
                "aTargets": [2]
                },
                {
                "mRender": function (data, type, row) {
                    var tipo_doc_ident = "";
					if(row.tipo_doc_ident!= null)tipo_doc_ident = row.tipo_doc_ident;
					return tipo_doc_ident;
                },
                "bSortable": false,
                "aTargets": [3]
                },
				{
                "mRender": function (data, type, row) {
                    var nro_doc_ident = "";
					if(row.nro_doc_ident!= null)nro_doc_ident = row.nro_doc_ident;
					return nro_doc_ident;
                },
                "bSortable": false,
                "aTargets": [4],
				"className": 'control'
                },
				{
                "mRender": function (data, type, row) {
                    var nro_historia = "";
					var newRow = "";
					if(row.nro_historia!= null)newRow += row.nro_historia;
					<?php if($flagemergenciareferencia == 1):?>
						newRow += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">';
						newRow += '<a href="javascript:void(0)" onclick="modalHistoria('+row.id_asegurado_historia+')" class="btn btn-success btn-xs">';
						newRow += '<i class="fa fa-edit"></i></a></div>';
					<?php endif;?>
					return newRow;
                },
                "bSortable": false,
                "aTargets": [5]
                },
				{
                "mRender": function (data, type, row) {
                    var asegurado = "";
					if(row.asegurado_paterno!= null)asegurado += row.asegurado_paterno;
					if(row.asegurado_materno!= null)asegurado += " "+row.asegurado_materno;
					if(row.asegurado_nombre!= null)asegurado += " ,"+row.asegurado_nombre;
					return asegurado;
                },
                "bSortable": false,
                "aTargets": [6]
                },
				{
                "mRender": function (data, type, row) {
                    var servicio = "";
					if(row.servicio!= null)servicio = row.servicio;
					return servicio;
                },
                "bSortable": false,
                "aTargets": [7]
                },
				{
                "mRender": function (data, type, row) {
                    var consultorio = "";
					if(row.consultorio!= null)consultorio = row.consultorio;
					return consultorio;
                },
                "bSortable": false,
                "aTargets": [8]
                },
				{
                "mRender": function (data, type, row) {
                    var medico = "";
					if(row.medico_paterno!= null)medico += row.medico_paterno;
					if(row.medico_materno!= null)medico += " "+row.medico_materno;
					if(row.medico_nombre!= null)medico += " ,"+row.medico_nombre;
					return medico;
                },
                "bSortable": false,
                "aTargets": [9]
                },
				{
                "mRender": function (data, type, row) {
					var html = "";
					if(row.id_estado_cita == 1 || row.id_estado_cita == 8){
						html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Cita</b>">';
						html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularCita('+row.id+')"><i class="fa fa-remove"></i></a></div>';
					}else{
						html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
					}
					return html;
                },
                "bSortable": false,
                "aTargets": [10],
		//		"visible": <?php //echo ($flagemergenciareferencia == 1)?"false":"true"?>,
				"className": "text-center",
                },                
            ]


    });

}

function fn_ListarBusqueda() {
    datatablenew();
};

function addMovimiento(id,codigo,obj){
        
	var newRow = "";
	
	if ($(obj).is(':checked')){
		var pht = 0;
		$("input[name='mov']").each(function(){
			var rs = $(this).val().toString()
			if(rs == id){
				pht++;
			}
		});
		
		if(pht == 0){
			newRow +="<td style='padding-right:0px;float:left;border:0px'><input name='mov' type='hidden' value='"+id+"' /><span class='label alert-warning'>"+codigo+"</span></td>";
			$('#Grd2 tbody tr').append(newRow);
		}
		
		var cantht = $('#Grd2 tbody tr td').length;
		$('#spanContador').html(cantht);
		
	}else{
		var cantht = $('#Grd2 tbody tr td').length;
		if(cantht > 0){
			$("input[name='mov']").each(function(){
				var rs = $(this).val().toString()
				if(rs == id){
					$(this).parent().remove();
				}
			});
		}
		cantht = $('#Grd2 tbody tr td').length;
		$('#spanContador').html(cantht);
	}

}

function addMovimientoAll(){
        
	var newRow = "";
	
	if ($("input[name='select_all']").is(':checked')){
		var pht = 0;
		$("input[name='id_cita[]']").each(function(){
			var id = $(this).val().toString();
			var codigo = $(this).attr("codigo").toString();
			newRow ="<td style='padding-right:0px;float:left'><input name='mov' type='hidden' value='"+id+"' /><span class='label alert-warning'>"+codigo+"</span></td>";
			$('#Grd2 tbody tr').append(newRow);
		});
		
	}else{
		var cantht = $('#Grd2 tbody tr td').length;
		if(cantht > 0){
			$("input[name='id_cita[]']").each(function(){
				var id = $(this).val().toString();
				$("input[name='mov']").each(function(){
					var rs = $(this).val().toString()
					if(rs == id){
						$(this).parent().remove();   
					}
				});
			});
		}
	}
	
	var cantht = $('#Grd2 tbody tr td').length;
	$('#spanContador').html(cantht);
	
}

	
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


</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Lista Citas Medicas  - {{$farmacia->nombre}}</h3>
        </div>
        <div class="panel-body">
			
			<form class="form-horizontal" method="post" action="{{ route('listar_citas_de_paciente')}}" id="frmCita">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
            <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">
			<input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$id_establecimiento}}">
			<input type="hidden" name="id_medico" id="id_medico" value="<?php echo $medico?>">
			<!--<div style="clear:both;margin-bottom:10px"></div>-->
			
			<div class="row">
			
			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
				<label class="form-control-sm">Fecha Desde</label>
				<input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="<?php echo $fechaDesde?>" maxlength="10">
			</div>
			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
				<label class="form-control-sm">Fecha Hasta</label>
				<input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="<?php echo $fechaDesde?>" maxlength="10">
			</div>
			
			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
				<label class="form-control-sm">Nro. Documento</label>
				<input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
			</div>
			
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				<label class="form-control-sm">Nombres</label>
				<input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder="" class="form-control input-sm" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
			</div>
			
			</div>
			
			<div class="row">
			
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				<label class="form-control-sm">Servicio</label>
				<select class="form-control input-sm" name="id_servicio" id="id_servicio" onchange="getSubConsultorio()">
					<option value="0">-Seleccionar-</option>
					<?php foreach($consultorio as $row):?>
					<option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
					<?php endforeach;?>
				</select>
			</div>
			
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				<label class="form-control-sm">Consultorio</label>
				<select class="form-control input-sm" name="id_consultorio" id="id_consultorio" required="">
					<option value="0">-Seleccionar-</option>
				</select>
			</div>
			
			<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
				<label class="form-control-sm">Estado</label>
				<select class="form-control input-sm" id="estado" name="estado" tabindex="5" style="width: 100%">
					<option value="1" <?php if (isset($estado) && $estado == 1) echo "selected='selected'"; ?>>Programado</option>
					<option value="5" <?php if (isset($estado) && $estado == 5) echo "selected='selected'"; ?>>Anulado</option>
				</select>
			</div>
			
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding-top:20px">
				<div class="col-md-4">
					<input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
				</div>
				<div class="col-md-4">
					<a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteCita('{{ Auth::user()->id }}')" >
						<i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
					</a>
				</div>
                <div class="col-md-4">
                    <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteCitaPdf('{{ Auth::user()->id }}')" >
                        <i class="fa fa-file-pdf" aria-hidden="true"></i> PDF
                    </a>
                </div>
			</div>
			
			<?php if($flagemergenciareferencia == 1):?>
			
			<div class="pull-right" style="margin-right:10px;">
				<table id="Grd2" class="table" style="margin-bottom:0px">
					<tbody>
					<tr>
					</tr>
					</tbody>
				</table>
			</div>
			
			<?php endif;?>
			
			</form>
            <div class="col-md-12" id="global">
                <!--<table  class="table table-list-search">-->
                <!--<table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >-->
                <table id="tblCita" class="table table-bordered">
                    <thead>
                        <tr>
							<!--<th>Sel</th>-->
							<th>Nro Cita</th>
                            <th>Fecha</th>
							<th>Hora</th>
                            <th>Tipo Doc</th>
							<th>Numero Doc</th>
							<th>Numero Historia</th>
                            <th>Paciente</th>
							<th>Servicio</th>
							<th>Consultorio</th>
							<th>Medico</th>
							<!--<th>Ver Cita</th>-->
							<th>Anular</th>
							
                        </tr>
                    </thead>
                    <tbody>
						<!--
                        <?php //$n = 0; ?>
                        
                        <tr>
                            <td><?php
                                //$n++;
                                //echo $n;
                                ?></td>
                            <td>date("d/m/Y", strtotime($c->dia))</td>
							<td>$c->hora</td>
							<td>$c->tipo_doc_ident</td>
                            <td>$c->nro_doc_ident</td>
							<td>
							<div style="float:left">
							$c->nro_historia
							</div>
							<?php //if($flagemergenciareferencia == 1):?>
								<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">
									<a href="javascript:void(0)" onclick="modalHistoria(<?php //echo $c->id_asegurado_historia?>)" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
								</div>
							<?php //endif;?>
							</td>
                            <td>$c->asegurado_paterno $c->asegurado_materno, $c->asegurado_nombre </td>
							<td>$c->servicio</td>
							<td>$c->consultorio</td>
							<td>$c->medico_paterno $c->medico_materno, $c->medico_nombre </td>
							<?php //if($flagemergenciareferencia != 1):?>
                            <td class="text-center">
								<?php 
								//if ($c->id_estado_cita == 1 || $c->id_estado_cita == 8) {
								?>
									<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Atender Cita</b>">
										<a href="/formulario_atencion/$c->id" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
									</div>
								<?php 
								//} else {
								?>
									<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>
								<?php 
								//}
								?>
                            </td>
							
							<td class="text-center">
								<?php 
								//if ($c->id_estado_cita == 1 || $c->id_estado_cita == 8) {
								?>
									<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Cita</b>">
										<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularCita($c->id)"><i class="fa fa-remove"></i></a>
									</div>
								<?php
								//} else {
								?>
									<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>
								<?php 
								//}
								?>
							</td>
							<?php //endif;?>
                        </tr>
                        
						-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="openOverlayOpc" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">
		
		<!--<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title"><small style="font-size: 22px; color: #fff">Listado de medicos por dia</small></h4>
		</div>
		-->
      	<div class="modal-body">

      		<div id="diveditpregOpc"></div>

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
