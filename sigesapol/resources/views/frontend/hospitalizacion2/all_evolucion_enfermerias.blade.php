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

    <div class="row col-md-12">
        <div class="panel panel-primary filterable" >
            <div class="panel-heading">
                <h3 class="panel-title">Relacion de Pacientes 
                @if($id_user_consultorio!=17)
                    Hospitalizados
                @else
                    de Hemodialisis
                @endif
                para Evoluciones Medicas
                </h3>
            </div>  
            <div class="panel-body">
                <legend style="text-align: center; text-transform: uppercase;">{{$establecimiento->nombre}}</legend>
				<div class="col-md-12">
                    <input class="form-control" id="system-search" name="q" placeholder="Buscar ..."><br/>                        
                </div>
				<div class="col-md-12">
                    <div class="table-responsive">
						<table id="example" class="table table-list-search" style="font-size: 11px !important;" >
	                        <thead>
	                            <tr>
	                                <th>N</th>
	                                <th>Nro. Hospitalizaci&oacute;n</th>
									<th>Fecha Ingreso</th>
									<th>Servicio</th>
	                                <th>DNI</th>
	                                <th>Beneficiario</th>
	                                <th>Parentesco</th>
	                                @if($id_user_consultorio!=17)
	                                <th>Cuarto</th>
	                                <th>Cama</th>
	                                @endif
	                                <th>Atender</th>
	                                <th>Ver</th>
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
	                                @if($id_user_consultorio!=17)
	                                <td>{{$r->nombre_cuarto}}</td>
	                                <td>{{$r->nombre_cama}}</td>
	                                @endif
	                                <td>
										<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Evolucion</b>">
	                    					<a href="/formulario_evolucion_hospitalizacion/{{$r->id}}" class="btn btn-warning btn-xs"><i class="fa fa-fw fa-bed"></i></a>
	                					</div>
	                                </td>
	                                <td>
										<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evoluciones</b>">
	                    					<a href="/ver_atenciones_evolucion/{{$r->id}}/{{$r->estado}}/3" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-eye"></i></a>
	                					</div>
	                                </td>
	                            </tr>
	                            @endforeach                      
	                        </tbody>
	                    </table>
	                    <br/><br/>
	                </div>   
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
