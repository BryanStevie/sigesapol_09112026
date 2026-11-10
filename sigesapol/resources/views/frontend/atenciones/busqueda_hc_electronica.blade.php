
@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
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
        $('#tblPaciente').DataTable({
            "sPaginationType": "full_numbers",
            "language": {"url": "/js/Spanish.json"},
        });

    // $(document).ready(function () {
    //     $('#example').DataTable({
    //     /*"searching": false*/
    //     "language": {
    //     "emptyTable": "No se encontraron resultados"
    //     }
    // });
    // $("#system-search").keyup(function() {
    // var dataTable = $('#example').dataTable();
    // dataTable.fnFilter(this.value);
    // });
    // $("#iddispensador").select2({
    // width: 'resolve' // need to override the changed default
    // });
    // $("#iddispensador").trigger('change');
    });
    
	


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
	datatablenew();
	
	<?php //if($id_consultorio_actual > 0){?>
		//$("#id_servicio").val("<?php //echo $id_consultorio_actual?>").prop('disabled',true).select2();
		//getSubConsultorio();
	<?php //}?>
});

var m = 0;

function datatablenew(){
    var oTable1 = $('#tblPaciente').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/listar_paciente_ajax",
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
            
			var nro_documento = $('#nro_documento').val();
            var apellidos = $('#apellidos').val();
			var nombres = $('#nombres').val();
			
			var _token = $('#_token').val();
            oSettings.jqXHR = $.ajax({
				"dataType": 'json',
                //"contentType": "application/json; charset=utf-8",
                "type": "POST",
                "url": sSource,
                "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
                        nro_documento:nro_documento,apellidos:apellidos,nombres:nombres,
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
					var row_number = "";
					if(row.row_number!= null) row_number = row.row_number;
					return row_number;
				},
				"bSortable": false,
				// "className": "dt-center",
				"aTargets": [0]
				},
				{
                "mRender": function (data, type, row) {
                    var tipo_documento = "";
					if(row.tipo_documento!= null) tipo_documento = row.tipo_documento;
					return tipo_documento;
                },
				"bSortable": false,
                "aTargets": [1]
                },
				{
                "mRender": function (data, type, row) {
                    var nro_doc_ident = "";
					if(row.nro_doc_ident!= null) nro_doc_ident = row.nro_doc_ident;
					return nro_doc_ident;
                },
                "bSortable": false,
                "aTargets": [2]
                },
				{
                "mRender": function (data, type, row) {
                    var paterno = "";
					if(row.paterno!= null) paterno = row.paterno+" "+row.materno+" "+row.nombre;
					return paterno;
                },
                "bSortable": false,
                "aTargets": [3]
                },
				{
                "mRender": function (data, type, row) {
                    var sexo = "";
					if(row.sexo!= null) sexo = row.sexo;
					return sexo;
                },
                "bSortable": false,
                "aTargets": [4]
                },
                {
                "mRender": function (data, type, row) {
                    var fecha_nac = "";
					if(row.fecha_nac!= null) fecha_nac = row.fecha_nac;
					return fecha_nac;
                },
                "bSortable": false,
                "aTargets": [5]
                },
                {
                "mRender": function (data, type, row) {
                    var html = "";
                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Historia Clinica</b>">';
                    html += '<a href="javascript:void(0)" onclick="ver_historia_clinica('+row.id_asegurado+')" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
					return html;
                },
                "bSortable": false,
                "aTargets": [6]
                }
				
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

function ver_historia_clinica(id_asegurado){
	$.ajax({
		url: "/hc_electronica/"+id_asegurado,
		type: "GET",
		success: function () {
			window.location.href = "/hc_electronica/"+id_asegurado;
		},
		error: function (xhr) {
			const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Ocurrio un error, intentelo en unos minutos";
			Swal.fire({
				icon: 'error',
				title: "HISTORIA CLINICA",
				html: `<p style="font-size: 13px">${message}</p>`,
				showConfirmButton: false,
				timer: 3000,
			});
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
            <h3 class="panel-title">Busqueda de Historia Clinica Eletronica</h3>
        </div>
        <div class="panel-body">
			
			<!-- <form class="form-horizontal" method="post" action="{{ route('listar_citas_medico_atenciones')}}"> -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="row">
                    
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Nro. Documento</label>
                        <input id="nro_documento" name="nro_documento" type="text" placeholder="" class="form-control input-sm" maxlength="12" value="">
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Apellidos</label>
                        <input id="apellidos" name="apellidos" type="text" placeholder="" class="form-control input-sm" maxlength="60" value="">
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <label class="form-control-sm">Nombres</label>
                        <input id="nombres" name="nombres" type="text" placeholder="" class="form-control input-sm" maxlength="60" value="">
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-md-offset-1" style="padding-top:20px;">
                        <div class="col-md-6">
                            <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                        </div>
                        <!-- <div class="col-md-6" >
                            <a class="btn btn-success form-control input-sm" href="formulario_atencion_nuevo">Nuevo</a>
                        </div> -->
                    </div>
                </div>
            <!-- </form> -->
            <br>
            <div class="col-md-12" >
                <div class="table-responsive">
                    <table id="tblPaciente" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo Documento</th>
                                <th>N° Documento</th>
                                <th style="width:40%">Paciente</th>
                                <!-- <th>Numero Historia</th> -->
                                <th>Sexo</th>
                                <th>Fecha Nacimiento</th>
                                <th style="text-align:center">Ver</th>
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
