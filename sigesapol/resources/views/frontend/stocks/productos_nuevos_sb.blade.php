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
        height: 610px !important;
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
    <div class="row col-md-12">

        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">PRODUCTOS INGRESADOS DESDE ALMACEN</h3>
                <div class="pull-right">
                    <a class="btn btn-default btn-xs" href="/stocksbyFamarcias/{{$id_farmacia}}">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                    </a>
                </div>
            </div>
            <div class="panel-body">         
                <div class="panel-body">
                    <div class="col-sm-12">
                        <form id="frm_transferencia" class="form-horizontal" method="post" action="{{ route('busca_nuevo_registro_sb')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div clas="row">       
                                <div class="row">                               
                                    <div class="col-md-2">                            
                                        <label class="help-block">Fecha Desde</label>
                                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                                        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">                                        
                                    </div>                        
                                    <div class="col-md-2">
                                        <label class="help-block">Fecha Hasta</label>
                                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                                    </div>
                                    <div class="col-md-2">
                                        <br/><br/>
                                        <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                                    </div>
                                </div>
                            </div>                    
                            <div class="row">                        
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <div class="pull-right">
                    @if($periodo==1)
                        <a class="btn btn-success btn-xs" href="#"><i class="fa fa-file-excel-o"></i> Exportar</a> 
                    @else
                        <a class="btn btn-success btn-xs" href="#"><i class="fa fa-file-excel-o"></i> Exportar</a> 
                    @endif
                </div>
            </div>            
            <div id="global">
                <br/>
                @if($stocks>0)
                    <table id="example" class="table " style="font-size: 11px !important;" >
                        <thead>
                            <tr class="filters">
                                <th>Nro</th>
                                <th>Código</th>
                                <th>Producto Farmacéutico o Dispositivo Médico</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 11px !important;">                            
                            <?php $x=1; ?>
                            @foreach($ingresos_amacen as $item)                            
                            <tr>
                                <td>{{ $x++ }}</td>
                                <td>{{ $item->codigo }}</a></td>
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->abreviatura }}</td>
                                <td>{{ $item->stock_cant }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                @endif
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
