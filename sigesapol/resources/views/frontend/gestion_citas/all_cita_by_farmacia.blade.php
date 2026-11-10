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
                <h3 class="panel-title">Buscar Citas Registradas</h3>
            </div>  
            <div class="panel-body">
                <legend style="text-align: center; text-transform: uppercase;">{{$farmacia->nombre}}</legend>
				<!--recetas_atendidas_by_fecha_and_farmacia-->
				<form class="form-horizontal" method="post" action="{{ route('listar_cita')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
					
					
					<!--
                    <div class="col-md-12">Fecha</div>
                    <div class="col-md-3">
                        <input type="text" class="form-control datepicker" name="fecha" required="">
                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                    </div>
					-->
					
					<label class="col-md-2 control-label">Fecha Desde</label>
					<div class="col-md-3">
						<input class="form-control datepicker" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde?>">
						<input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
						<input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
					</div>
					<label class="col-md-2 control-label">Fecha Hasta</label>
					<div class="col-md-3">
						<input class="form-control" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta?>">
					</div>
					
					<div style="clear:both;margin-bottom:10px"></div>
					<label class="col-md-2 control-label" for="textinput">DNI</label>  
					<div class="col-md-3">
						<input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-md" maxlength="8" onkeypress="return IsNumber(event)" >
					</div>
					<label class="col-md-2 control-label" for="textinput">Nro. Cita</label>  
					<div class="col-md-3">
						<input id="nro_cita" name="nro_cita" type="text" placeholder="" class="form-control input-md" >
					</div>
					
					<!--
					<div style="clear:both;margin-bottom:10px"></div>
					
					<label class="col-md-2 control-label">Estado</label>
					<div class="col-md-3">
						<select class="form-control" name="estado"  id="estado" tabindex="15">
							<option value="1" <?php if(isset($estado) && $estado == 1)echo "selected='selected'";?>>Atendidas</option>
							<option value="2" <?php if(isset($estado) && $estado == 2)echo "selected='selected'";?>>Invalidadas</option>
						</select>
					</div>  
					-->			
					<div class="col-md-2">
						<input class="form-control btn btn-info" value="Buscar" type="submit">
					</div>
					
					<!--
                    <div class="col-md-6">
                        <button class="btn btn-info" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
					-->
					<!--
                    <div class="col-md-3">
                        <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}" class="btn btn-info" type="submit">
                            <i class="fa fa-list"></i> Lista de Recetas atendidas
                        </a>
                    </div>
					-->
                </form>
                <div class="col-md-12" style="margin-top: 10px">
                    <legend>
                        Citas Registradas
                        <div class="pull-right">Total: {{$cantidad}} citas registradas</div>
                    </legend>
                </div>                
                <div class="col-md-12">
                    <input class="form-control" id="system-search" name="q" placeholder="Buscar cita">                        
                </div>
				
				<?php //print_r($recetas);?>
                <div class="col-md-12" id="global">
                    <!--<table  class="table table-list-search">-->
					<table id="example" class="table table-list-search" style="font-size: 11px !important;" >
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Nro. Cita</th>
                                <th>Fecha</th>                                                        
                                <th>DNI</th>
                                <th>Beneficiario</th>
                                <th>Tipo</th>
                                <th>Registrador</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 0; ?>
                            @foreach($recetas as $r)
                            <tr>
                                <td><?php
                                    $n++;
                                    echo $n;
                                    ?></td>
                                <td>{{$r->nro_cita}}</td>
                                <td>
                                    <?php
                                    $originalDate2 = $r->fecha_cita;
                                    $fecha = date("d-m-Y", strtotime($originalDate2));
                                    echo $fecha;
                                    ?> 
                                </td>
                                <td>{{$r->dni_beneficiario}}</td> 
                                <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   
                                <td>{{$r->tipo_beneficiario}}</td> 
                                <td>{{$r->name}} ({{$r->email}}) 
                                <td>
									<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Visualizar Receta</b>">
                    					<a href="/ver_cita/{{ Auth::user()->id }}/{{$r->nro_cita}}" class="btn btn-success btn-xs"><i class="fa fa-search"></i></a>
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
@include('frontend.includes.footer')
@endsection
