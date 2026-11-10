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
	/*
    if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fecha_fin').val(fechaHastaIni);
    return false;
    }
	*/
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



function firmar(){

	//var rowindex = $('#Grd2 tbody tr td').length;
	//$('#btnLiquidacionFirmarPdf').attr("disabled",true);
	//if (rowindex > 0) {
	
		$.ajax({
			url: "genera_csv",
			type: "post",
			//data: p,
			data: $("#frmBloque").serialize(),
			//timeout: 30000,
			dataType: 'json'
		})
		.done(function (resultado) {
			//alert(resultado.sw);
			if (resultado.sw) {
				var csv = "<?php echo url()?>/tmp/csv/firmar.csv"; 
				location.href='bpsign:?batch_csv='+csv;
				//validarFirma();
			} else {
				//$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
				//dialogError(function () {});
			}
			$('#btnLiquidacionFirmarPdf').attr("disabled",false);

		})
		.fail(function () {
			//$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
			//dialogError(function () {});
		})
		.always(function () {
			//bootbox.hideAll();
		});
	
	//} else {

		//dialogWarning("Debe hacer check en minimo una solicitud");
	//}	
	

}

function validarFirma(){

	$('.loader').show();
	var batchSignatureProcessPoller = setInterval(function () {
		$.ajax({
			url: "../expedientes/validarFirma",
			type: "post",
			cache: false,
			dataType: 'json',
			data: $("#frmBloque").serialize(),
			success: function (completed) {
				if (completed) {
					$('.loader').hide();
					cargarSolicitudes();
					$('#Grd2 tbody tr').html("");
					$('#spanContador').html("");
					clearInterval(batchSignatureProcessPoller);
					
				}
			}
		});
	}, 3000);
	

}
	

$(document).ready(function () {
	
	$('#btnBuscar').click(function () {
		fn_ListarBusqueda();
	});
	/*
	$('#example-select-all').on('click', function(){
		var rows = $('#tblCita').DataTable().rows({ 'search': 'applied' }).nodes();
		$('input[type="checkbox"]', rows).prop('checked', this.checked);
		addMovimientoAll();
	});
	*/
	$("#id_servicio").select2({width: 'resolve'});
	$("#id_consultorio").select2({width: 'resolve'});
    $("#id_atencione").select2({width: 'resolve'});
	datatablenew();
	
	<?php //if($id_consultorio_actual > 0){?>
		//$("#id_servicio").val("<?php //echo $id_consultorio_actual?>").prop('disabled',true).select2();
		//getSubConsultorio();
	<?php //}?>
});

var m = 0;

function datatablenew(){
    var oTable1 = $('#tblPrestacion').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/listar_atenciones_ipress",
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
			var estado = $('#estado').val();
			var id_servicio = $('#id_servicio').val();
			var id_consultorio = $('#id_consultorio').val();
			var id_medico = $('#id_medico').val();
            //var id_establecimiento = $('#id_establecimiento').val();
            var id_establecimiento = 0;
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
                    var fecha_atencion = "";
					if(row.fecha_atencion!= null)fecha_atencion = row.fecha_atencion;
					return fecha_atencion;
                },
                "bSortable": false,
                "aTargets": [1]
                },
				{
                "mRender": function (data, type, row) {
                    var horario = "";
					if(row.horario!= null)horario = row.horario;
					return horario;
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
                    var atencion = "";
					if(row.atencion!= null)atencion = row.atencion;
					return atencion;
                },
                "bSortable": false,
                "aTargets": [7]
                },
                {
                "mRender": function (data, type, row) {
                    var establecimiento = "";
                    if(row.establecimiento!= null)establecimiento = row.establecimiento;
                    return establecimiento;
                },
                "bSortable": false,
                "aTargets": [8]
                },
                {
                "mRender": function (data, type, row) {
                    var servicio = "";
                    if(row.servicio!= null)servicio = row.servicio;
                    return servicio;
                },
                "bSortable": false,
                "aTargets": [9]
                },
                
				{
                "mRender": function (data, type, row) {
                    var consultorio = "";
					if(row.consultorio!= null)consultorio = row.consultorio;
					return consultorio;
                },
                "bSortable": false,
                "aTargets": [10]
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
                "aTargets": [11]
                },
				{
                "mRender": function (data, type, row) {
					var html = "";
					if(row.id_estado_reg==1){
						html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
						html += '<a href="/ver_prestacion_teleconsulta/<?php echo Auth::user()->id?>/'+row.id+'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
					}else{
						html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
					}
					
					return html;
                },
                "bSortable": false,
                "aTargets": [12],
				"visible": <?php echo ($flagemergenciareferencia == 1)?"false":"true"?>,
				"className": "text-center",
                },
				{
                "mRender": function (data, type, row) {
					var html = "";
					if(row.ruta_prestacion != null && row.id_estado_reg==1){
						html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';                        
						html += '<a href="/'+row.ruta_prestacion+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';                        
					}else{
						html += '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
					}
					return html;
                },
                "bSortable": false,
                "aTargets": [13],
				"className": "text-center",
                },
				{
                "mRender": function (data, type, row) {
					var html = "";
					if(/*row.ruta_receta != "{NULL}" && */row.ruta_receta!=null && row.id_estado_reg==1){
						var ruta_receta = row.ruta_receta.replace("{","").replace("}","");
						var ruta = ruta_receta.split(",");
						for(var r=0;r<ruta.length;r++){
							if(ruta[r]!="NULL"){
								html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">';
								html += '<a href="/'+ruta[r]+'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';                                
							}else{
								html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
							}
						}
					}	
					return html;
                },
                "bSortable": false,
                "aTargets": [14],
				"className": "text-center",
                },
				{
                "mRender": function (data, type, row) {
					var html = "";
					if(row.ruta_prestacion == null && row.id_estado_reg==1){
						html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atenci&oacute;n</b>">';
						html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularAtencion('+row.id+')"><i class="fa fa-remove"></i></a></div>';
					}else{
						html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
					}
					return html;
                },
                "bSortable": false,
                "aTargets": [15],
				"visible": <?php echo ($flagemergenciareferencia == 1)?"false":"true"?>,
				"className": "text-center",
                },
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
				location.href="all_atenciones_medico_ipress";
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
            <h3 class="panel-title">Lista Atenciones Medicas - {{$farmacia->nombre}}</h3>
        </div>
        <div class="panel-body">
			
			<form class="form-horizontal" method="post" action="{{ route('listar_citas_medico_atenciones')}}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
			<input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">
			<input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$farmacia->id_establecimiento}}">
			<input type="hidden" name="id_medico" id="id_medico" value="<?php echo $medico?>">
			
			
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
                    <option value="218">-CALL CENTER-</option>
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
                    <option value="1">-AMBULATORIO-</option>
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
					<a class="btn btn-success form-control input-sm" href="atencion_teleconsulta">Nuevo</a>
				</div>
			</div>
			 
            </form>
            <div class="col-md-12"  id="global">
                <table id="tblPrestacion" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N</th>
							<th>Fecha</th>
                            <th>Hora</th>
                            <th>Tipo Doc</th>
							<th>Numero Doc</th>
							<th>Numero Historia</th>
                            <th>Paciente</th>
                            <th>Atencion</th>
                            <th>Establecimiento</th>
							<th>Servicio</th>
							<th>Consultorio</th>
							<th>Medico</th>
							<th style="text-align:center">Ver Atencion</th>
							<th style="text-align:center">Pdf Atencion</th>
							<th style="text-align:center;min-width:106px">Pdf Receta</th>
							<th>Anular</th>
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
