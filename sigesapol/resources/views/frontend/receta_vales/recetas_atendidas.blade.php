@extends('frontend.layouts.master2')
@section('renderjs')

<script>
    $cmbUni = $("#frmcrear_prod").find("#id_unidad");
    $cmbUni.select2();
</script>

<script type="text/javascript">

	$('#fecha').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true/*"timeOnly": true*/});
	$('#fechaHasta').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true});
	
	/*
	$('#fechaHasta').change(function() {
		$('#fecha').appendDtpicker({
			maxDate: $('#fechaHasta').val()
		});
	});

	$('#fecha').change(function() {
		$('#fechaHasta').appendDtpicker({
			minDate: $('#fecha').val()
		});
	});
	
	$('#fechaHasta').trigger('change');
	$('#fecha').trigger('change');
	*/

</script>

@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <div class="panel-group" id="accordion">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                                </span>Atención de Recetas</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">                             
                            <table class="table">
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-plus text-primary"></span>
                                        <a href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia }}"> Nueva Receta</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/buscar_receta/{{ Auth::user()->id }}"> Buscar Receta</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list text-primary"></span>
                                        <a href="/recetas_atendidas/{{ Auth::user()->id }}"> Receta atendidas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list text-primary"></span>
                                        <a href="/recetas_invalidadas/{{ Auth::user()->id_farmacia }}/{{ Auth::user()->id }}"> Recetas Invalidadas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-tasks text-primary"></span>
                                        <a href="/resumen_diario/{{ Auth::user()->id }}"> Resumen Diario</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-arrow-left text-primary"></span>
                                        <a href="/stocksbyFamarcia/{{ Auth::user()->id }}"> Ir al Stock</a>
                                    </td>
                                </tr>
								
								@role('Almacenero_recetas')
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}">Recetas atendidas</a>
                                    </td>
                                </tr>
                                @endauth
								
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-9">
            <div class="row">
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">Recetas Atendidas</h3>                
                    </div> 
                    <div class="panel-body">
                        <legend style="text-align: center">{{$farmacia->nombre}}</legend>
                        <form class="form-horizontal" method="post" action="{{ route('listar_recetas')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-md-2">Fecha Desde</div>
                            <div class="col-md-3">
                                <input class="form-control datepicker" type="text" name="fecha" id="fecha" required="" placeholder="Fecha Desde">
                                <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                            </div>
							<div class="col-md-2">Fecha Hasta</div>
                            <div class="col-md-3">
                                <input class="form-control" type="text" name="fechaHasta" id="fechaHasta" required="" placeholder="Fecha Hasta">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control btn btn-info" value="Buscar" type="submit">
                            </div>
                        </form>
                        @if(count($recetas)>0)
                        <div class="col-md-12" style="margin-top: 15px">
                            <legend>Recetas Atendidas del {{$fecha}} <?php if(isset($fechaHasta))echo " al ".$fechaHasta?>
							<?php if(!isset($fechaHasta))$fechaHasta="0";?>
                            <a href="/imprimir_lista_recetas_atendidas/{{ Auth::user()->id }}/{{$fecha}}/{{$fechaHasta}}" class="btn btn-xs btn-success pull-right"><i class="fa fa-file-excel-o"></i> Exportar Reporte de Recetas atendidas</a></legend>
                        </div>   
                        <div class="col-md-3" style="margin-bottom: 10px">Total de Recetas atendidas:</div>
                        <div class="col-md-5">
                            <input value="{{$nro_atendidas}}" type="text" style="width: 70px">                            
                        </div>

                        <table class="table " style="font-size: 13px !important;" >
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Nro. Receta</th>
                                    <th>DNI</th>
                                    <th>Beneficiario</th>
                                    <th>Tipo Beneficiario</th>   
                                    @if(Auth::user()->id_farmacia==28)
                                        <th>Importe</th>
                                    @endif
                                    <th>Dispensador</th>
                                    <th style="font-size: 11px">Fecha de atención (Receta)</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 1; ?>
                                @foreach($recetas as $r) 
                                <tr>
                                    <td><?php echo $n++; ?></td>
                                    <td>{{$r->nro_receta}}</td>
                                    <td>{{$r->dni_beneficiario}}</td>
                                    <td>{{$r->nombre_beneficiario}} {{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}</td>
                                    <td>{{$r->tipo_beneficiario}}</td>
                                    @if(Auth::user()->id_farmacia==28)
                                        <td>{{$r->total}}</td>
                                    @endif
                                    <td>{{$r->name}} {{$r->paterno_usuario}} {{$r->materno_usuario}}</td>
                                    <?php
                                        $originalDates = $r->fecha_registro;
                                        $fecha_obtenida = date("d-m-Y", strtotime($originalDates));                                          
                                    ?>
                                    <td><?php echo $fecha_obtenida?></td>
                                    @if(Auth::user()->id_farmacia!=28)                                    
                                    <td>
                                        <a href="/ver_receta_atendida/{{ Auth::user()->id }}/{{$r->nro_receta}}" class="btn btn-success btn-xs"><i class="fa fa-search"></i></a>
                                    </td>
                                    @else
                                    <td>
                                        <a href="/ver_receta_atendida_cirugia/{{ Auth::user()->id }}/{{$r->nro_receta}}" class="btn btn-success btn-xs"><i class="fa fa-search"></i></a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="col-md-12" style="margin-top: 20px">
                            "No hubo recetas atendidas el {{$fecha}}"
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection