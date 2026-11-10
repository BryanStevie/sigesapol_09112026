@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .filterable {
        margin-top: 0px;
    }
    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }
    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }
    #global {
        height: 640px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;
    }
    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }
	.panel{
		margin-bottom:0px
	}

</style>

<script>
    $(document).ready(function () {
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            /* Useful DOM data and selectors */
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
            }
        });
    });
    $(document).ready(function () {
        $('#example').dataTable( {
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Filtrar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                },
                "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        } );
    });
</script>
@endsection
@section('content')
<div class="container">
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                {{$farma->nombre}}  
            </div>
			
			<?php
			
			$pagRoute = "/stocksbyFamarcias/".$id_farmacia;
			/*
			if($farmaciaActual == 1):
				$pagRoute = "/stocksbyFamarcia/".Auth::user()->id;
			endif;
			
			if($farmaciaActual == 2):
				$pagRoute = "/stocksbyFamarcias/".$id_farmacia;
			endif;
			*/
			?>
			
            <div class="panel-body">   
                <div class="col-md-12">
                    <b>Leyenda:</b>
                </div>
				
				<div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Stock Local del Centro de Atenci&oacute;n</em><br>">
                    <i class="fa fa-medkit" style="color:#000000"></i> Stock en General
                </div>
				<div class="col-md-2" style="padding-left:18px">
                    <a href="<?php echo $pagRoute?>" class="btn btn-default btn-xs">ver</a>
                </div>
				
				
				<div class="row">
				
				<div class="col-md-11 col-md-offset-1">
				
				<div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                    <i class="fa fa-flag" style="color:#FF0000"></i> Desabastecido
                </div>
                <div class="col-md-2">
                    <a href="<?php echo $pagRoute?>/1" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                    <i class="fa fa-flag" style="color:#ED7D31"></i> SobreStock
                </div>
                <div class="col-md-2">
                     <a href="<?php echo $pagRoute?>/2" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                    <i class="fa fa-flag" style="color:#70AD47"></i> NormoStock
                </div>
                <div class="col-md-2">
                    <a href="<?php echo $pagRoute?>/3" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                    <i class="fa fa-flag" style="color:#FFC000"></i> SubStock
                </div>
                <div class="col-md-2">
                    <a href="<?php echo $pagRoute?>/4" class="btn btn-default btn-xs">ver</a>
                </div>
				<div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                    <i class="fa fa-flag" style="color:#4472C4"></i> Sin Rotaci&oacute;n
                </div>
                <div class="col-md-2">
                    <a href="<?php echo $pagRoute?>/5" class="btn btn-default btn-xs">ver</a>
                </div>
				
				</div>
				
				</div>
				
				
				
                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Stock por lotes</em>">
                    <i class="fa fa-medkit" style="color: orange"></i> Stock por Lotes
                </div>
                <div class="col-md-2" style="padding-left:18px">
                    <a href="/productos_porlotes/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>
				
            </div>
			
			
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                Clasificación de Stock por Tipo de Producto 
            </div>            
            <div class="panel-body" style="font-size:11px">   
                <div class="col-md-12">
                    <b>Leyenda:</b>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Medicamentos ({{$countMedicamento}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/1" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Biomédicos ({{$countbiomedico}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/3" class="btn btn-default btn-xs">ver</a>
                </div>                
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material Dental ({{$countMaterialDental}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/5" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Material de Laboratorio ({{$countMaterialLaboratorio}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/4" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Insumos de Laboratorio ({{$countinsumolaboratorio}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/2" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Mat. Fotográfico y Fonot. ({{$countMatFotograficoAndFonotecnico}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_por_tipo/{{ $id_farmacia }}/6" class="btn btn-default btn-xs">ver</a>
                </div>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                Alertas de Vencimiento 
            </div>            
            <div class="panel-body" style="font-size:11px">   
                <div class="col-md-12">
                    <b>Leyenda:</b>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Prod. próximos a vencer ({{$count_proximos_vencer}})
                    <br> <span style="font-size:9px">(En los Proximos 06 meses)</span>
                </div>
                <div class="col-md-2">
                    <a href="/productos_proximos_vencer/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>
                <div class="col-md-10">
                    <i class="fa fa-medkit" aria-hidden="true" style="color:red"></i> Productos vencidos ({{$count_productos_vencidos}})
                </div>
                <div class="col-md-2">
                    <a href="/productos_vencidos/{{ $id_farmacia }}" class="btn btn-default btn-xs">ver</a>
                </div>                
            </div>
        </div>
    </div>
    <!--<h4>
        Stocks - {{$farma->nombre}}         
        <span class="pull-right">Hoy: {{$fecha}}</span>
    </h4>-->
    <div class="row col-md-9">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">STOCK LOCAL DE CENTRO DE ATENCIÓN - STOCK POR LOTES</h3>
                <div class="pull-right">
                    <a class="btn btn-success btn-xs" href="/imprimir_productos_porlotes/{{ $id_farmacia }}"><i class="fa fa-file-excel-o"></i> Exportar</a> 
                    <a class="btn btn-default btn-xs" href="/stocksbyFamarcias/{{$id_farmacia}}">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                    </a>
                </div>
            </div>            
            <div id="global">
                <br/>
                @if(count($stocks)>0)
                <table id="example" class="table " style="font-size: 11px !important;" >
                    <thead>
                        <tr class="filters">
                            <th>Nro</th>
                            <th>Código</th>
                            <th>Producto Farmacéutico o Dispositivo Médico</th>                            
                            <th>Unidad</th>
                            <th>Tipo</th>
                            <th>Lote</th>
                            <th>R.S.</th>
                            <th>Fec. Vencimiento <br> (mm-aaaa)</th>
                            <th>Stock Actual</th>
                            <th>Precio</th>
                            <th>Total</th>
                            
                        </tr>
                    </thead>
                    <tbody style="font-size: 11px !important;">
                        {{-- */$x=0;/* --}}
                        @foreach($stocks as $item)
                        {{-- */$x++;/* --}}
                        <tr>
                            <td>{{ $x }}</td>
                            <td>{{ $item->codigo_producto }}</a></td>
                            <td>{{ $item->prod_farmaceutico }}</td>                            
                            <td>{{ $item->abreviatura }}</td>
                            <td>{{ $item->nombre_tipo }}</td>
                            <td>{{ $item->lote }}</td>
                            <td>{{ $item->registro_sanitario }}</td>
                            
                            <td>
                                <?php
                                /*
                                $originalDate3 = $item->fecha_vencimiento;
                                $fecha_ven = date("m-Y", strtotime($originalDate3));
                                echo $fecha_ven;
                                */
                                echo $item->fecha_vencimiento;;
                                ?>
                            </td>
                            <td>{{ $item->cantidad }}</td>
                            <td>{{ $item->precio_unitario }}</td>
                            <td>{{ $item->precio_unitario*$item->cantidad }}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else               
                <div class="col-sm-12 col-md-12" style="margin-top:15px">
                    <div class="alert alert-warning">
                        <span class="glyphicon glyphicon-info-sign"></span> <strong>Aviso</strong>
                        <hr class="message-inner-separator">
                        <p>No existe registros de productos proximos a vencer...</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
