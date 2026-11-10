@extends('frontend.layouts.master2')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
	/*
    #global {        
        width: 95%;        
        margin: 15px 15px 15px 15px;     
        height: 380px !important;        
        border: 1px solid #ddd;
        overflow-y: scroll !important;
    }
	*/
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

</style>

<script>
    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
    //--------------------------------------------------------------------------
    $(document).ready(function () {
		
		/*
		$('#fechaDesde').appendDtpicker({
			"autodateOnStart": false,
			"minuteInterval": 5,
			"amPmInTimeList": true,
			"locale": "es",
			"dateFormat": "YYYY-MM-DD hh:mm",
		});
			
		$('#fechaHasta').appendDtpicker({
			"autodateOnStart": false,
			"minuteInterval": 5,
			"amPmInTimeList": true,
			"locale": "es",
			"dateFormat": "YYYY-MM-DD hh:mm",
		});
		*/
	
		
		$('#fechaDesde').appendDtpicker({
			"autodateOnStart": false,
			"minuteInterval": 5,
			"amPmInTimeList": true,
			"locale": "es",
			"dateFormat": "YYYY-MM-DD hh:mm",
		});
		
		
		$('#fechaHasta').appendDtpicker({
			"autodateOnStart": false,
			"minuteInterval": 5,
			"amPmInTimeList": true,
			"locale": "es",
			"dateFormat": "YYYY-MM-DD hh:mm",
		});
		
		
		$('#fechaDesde').click(function() {
			var fechaDesdeIni = $('#fechaDesde').val();
			$('#fechaDesde').change(function() {
				var fechaDesde = $('#fechaDesde').val();
				var fechaHasta = $('#fechaHasta').val();
				if(Date.parse(fechaDesde) > Date.parse(fechaHasta)){
					bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
					$('#fechaDesde').val(fechaDesdeIni);
					return false;
				}
				
			});
		
		});
		
		/*
		$('#fechaHasta').change(function() {
			//$('#fechaDesde').handleDtpicker('destroy');
			$('#fechaDesde').appendDtpicker({
				"futureOnly": true,
				"closeOnSelected": true,
				"dateFormat": "YYYY-MM-DD hh:mm",
				"minTime":"07:00",
				"maxTime":"23:00",
				"locale": "es",
				"amPmInTimeList": true,
				"autodateOnStart": false,
				"minuteInterval": 5,
				//minDate: $('#fechaHasta').val(),
				maxDate: '2017-08-05',
			});
			$('#fechaDesde').trigger('change');
		});
		*/
		/*
		$('#fechaHasta').change(function() {
			$('#fechaDesde').appendDtpicker({
				"futureOnly": true,
				"closeOnSelected": true,
				//"dateFormat": "DD/MM/YYYY hh:mm",
				"dateFormat": "YYYY-MM-DD hh:mm",
				"minTime":"07:00",
				"maxTime":"23:00",
				"locale": "es",
				"amPmInTimeList": true,
				"autodateOnStart": false,
				"minuteInterval": 5,
				maxDate: $('#fechaHasta').val() 
			});
		});
		
		$('#fechaDesde').change(function() {
			$('#fechaHasta').appendDtpicker({
				"futureOnly": true,
				"closeOnSelected": true,
				//"dateFormat": "DD/MM/YYYY hh:mm",
				"dateFormat": "YYYY-MM-DD hh:mm",
				"minTime":"07:00",
				"maxTime":"23:00",
				"locale": "es",
				"amPmInTimeList": true,
				"autodateOnStart": false,
				"minuteInterval": 5,
				minDate: $('#fechaDesde').val() 
			});
		});                     
		*/
		
		$('#fechaHasta').trigger('change');
		$('#fechaDesde').trigger('change');
		
		
	
	
	
        var activeSystemClass = $('.list-group-item.active');
		/*
        //something is entered in search form
        $('#system-search').keyup(function () {
            var that = this;
            // affect all table rows on in systems table
            var tableBody = $('.table-list-search tbody');
            var tableRowsClass = $('.table-list-search tbody tr');
            $('.search-sf').remove();
            tableRowsClass.each(function (i, val) {

                //Lower text for case insensitive
                var rowText = $(val).text().toLowerCase();
                var inputText = $(that).val().toLowerCase();
                if (inputText != '')
                {
                    $('.search-query-sf').remove();

                } else
                {
                    $('.search-query-sf').remove();
                }

                if (rowText.indexOf(inputText) == -1)
                {
                    //hide rows
                    tableRowsClass.eq(i).hide();

                } else
                {
                    $('.search-sf').remove();
                    tableRowsClass.eq(i).show();
                }
            });
            //all tr elements are hidden
            if (tableRowsClass.children(':visible').length == 0)
            {
                tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No existe la receta que esta buscando.</td></tr>');
            }
        });
		
		*/
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

    });
	
	
	function reporteAdmision(){
		
		var fechaDesde 			= $('#fechaDesde').val();
		var fechaHasta 			= $('#fechaHasta').val();
		var dni_beneficiario 	= $('#dni_beneficiario').val();
		var nro_admision 		= $('#nro_admision').val();
		
		if(fechaDesde == "")fechaDesde=0;
		if(fechaHasta == "")fechaHasta=0;
		if(dni_beneficiario == "")dni_beneficiario=0;
		if(nro_admision == "")nro_admision=0;
		location.href='exportar_reporte_admision/'+fechaDesde+'/'+fechaHasta+'/<?php echo $id_farmacia?>/'+dni_beneficiario+'/'+nro_admision;
	}
	
</script>
@endsection
@section('content')
<div class="container">

    <h4>

        <a href="{{ url('/stocks/create') }}" class="btn btn-primary pull-right btn-sm" style="display:none">
            <i class="fa fa-plus"></i> Agregar Stock
        </a>
    </h4>
    <div class="row col-md-12">
        <div class="panel panel-primary filterable" >
            <div class="panel-heading">
                <h3 class="panel-title">Buscar Pacientes 
                @if($id_user_consultorio!=17)
                    Hospitalizados
                @else
                    de Hemodialisis
                @endif
                </h3>
            </div>  
            <div class="panel-body">
                <legend style="text-align: center; text-transform: uppercase;">{{$nombre_ipress}}</legend>
				<!--recetas_atendidas_by_fecha_and_farmacia-->
				
                <div class="col-md-12">
                    <input class="form-control" id="system-search" name="q" placeholder="Buscar ...">                        
                </div>
				
				<?php //print_r($recetas);?>
                <div class="col-md-12" id="global">
                    <div class="table-responsive">
                        <!--<table  class="table table-list-search">-->
                        <table id="example" class="table table-list-search" style="font-size: 11px !important;" >
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Nro. Hospitalizaci&oacute;n</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Servicio</th>
                                    <th>DNI</th>
                                    <th>Beneficiario</th>
                                    <th>Tipo</th>
                                    <th>Registrador</th>
                                    @if($id_user_consultorio!=17)
                                    <th>Cuarto</th>
                                    <th>Cama</th>
                                    @endif
                                    <th>Alta</th>
                                    <th>Modificar</th>
                                    <th>Rotacion</th>
                                    <th>Imprimir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 0; ?>
                                @foreach($hospitalizado as $r)
                                <tr>
                                    <td><?php
                                        $n++;
                                        echo $n;
                                        ?></td>
                                    <td>{{$r->nro_hospitalizacion}}</td>
                                    <td>
                                        <?php
                                        $originalDate2 = $r->fecha_ingreso;
                                        $fecha = date("d-m-Y", strtotime($originalDate2));
                                        echo $fecha;
                                        ?> 
                                    </td>
                                    <td><?php echo $r->nombre_consultorio?></td>
                                    <td>{{$r->dni_beneficiario}}</td>
                                    <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>
                                    <td>{{$r->tipo_beneficiario}}</td>
                                    <td>{{$r->nombre_registrador}}</td>
                                    @if($id_user_consultorio!=17)
                                    <td>{{$r->nombre_cuarto}}</td>
                                    <td>{{$r->nombre_cama}}</td>
                                    @endif
                                    <td>

                                        @if($r->secuencia>1)
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Dar de Alta al Paciente</b>">
                                                <a href="/alta_medica_hospitalizado/{{$id_user}}/{{$r->id}}" class="btn btn-primary btn-xs"><i class="fa fa-thumbs-up"></i></a>
                                            </div>
                                        @else
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Dar de Alta al Paciente</b>">
                                                <a href="#" class="btn btn-primary btn-xs" disabled ><i class="fa fa-thumbs-up"></i></a>
                                            </div>
                                        @endif
                                    </td>
                                    <?php 
                                            /*$originalDate3 = $r->fecha_ingreso;
                                            
                                            $fecha = date("Y-m-d", strtotime($originalDate3));
                                            $fecha2 = strtotime("$fecha + 1 day");

                                            $fechaHoy = date('Y-m-d');
                                            $fechaHoy2 = strtotime("$fechaHoy");
                                            
                                            $diasDiferencia = $fechaHoy2 - $fecha2;
                                            if($diasDiferencia <= 0){*/
                                        ?>
                                    <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar Hospitalizado</b>">
                                                <a href="/modifica_admision_hospitalizacion/{{ $id_user }}/{{$r->id}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a> 
                                            </div>
                                        
                                    </td>
                                    <td>
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Rotacion de Cama</b>">
                                            <a href="/rotacion_cama_hospitalizado/{{ $id_farmacia }}/{{$r->id}}" class="btn btn-warning btn-xs"><i class="fa fa-fw fa-bed"></i></a>
                                        </div>
                                    </td>

                                    <?php // }else { ?> 
                                        <!--td>
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar Hospitalizado</b>">
                                            <button  disabled type="button" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>
                                        </div>
                                    </td-->

                                    <?php //} ?>

                                    <td>
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Imprimir Hospitalizado</b>">
                                            <a target="_blank" href="/imprimir_nuevo_hospitalizado/{{$r->id}}/{{ $id_farmacia }}/{{$id_user}}" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach                      
                            </tbody>
                        </table>
                    </div>
                </div>
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
