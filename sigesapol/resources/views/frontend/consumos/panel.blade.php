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
        height: 600px !important;
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
        $("#id_consultorio").select2({
            width: 'resolve' // need to override the changed default
        });
        $("#id_consultorio").trigger('change');
        
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
<style>
</style>
@endsection
@section('content')
<?php
    $pagRoute = "/famarcia_cpm/" . Auth::user()->id;
?>
<div class="container">
    <div class="row col-md-12">
        <div class="col-md-3">
            <div class="row">        
                <div class="panel panel-warning">
                    <div class="panel-heading">                
                        Almacen San Borja
                    </div>
                    <div class="panel-body">                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                    <i class="fa fa-flag" style="color:#FF0000"></i> Productos que no hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="stocksbyFamarcias/113/1" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                    <i class="fa fa-flag" style="color:#FFC000"></i> Productos criticos por agotarse
                                </div>
                                <div class="col-md-2">
                                    <a href="stocksbyFamarcias/113/4" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                    <i class="fa fa-flag" style="color:#ED7D31"></i> Productos en Sobrestock
                                </div>
                                <div class="col-md-2">
                                    <a href="stocksbyFamarcias/113/2" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                    <i class="fa fa-flag" style="color:#70AD47"></i> Productos que hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="stocksbyFamarcias/113/3" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                    <i class="fa fa-flag" style="color:#4472C4"></i> Productos que no rotan
                                </div>
                                <div class="col-md-2">
                                    <a href="stocksbyFamarcias/113/5" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                    </div>
                </div>
                <br/><br/>
            </div>
            <div class="row">        
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Productos Nivel III
                    </div>
                    <div class="panel-body">                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                    <i class="fa fa-flag" style="color:#FF0000"></i> Productos que no hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/1/3" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                    <i class="fa fa-flag" style="color:#FFC000"></i> Productos criticos por agotarse
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/4/3" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                    <i class="fa fa-flag" style="color:#ED7D31"></i> Productos en Sobrestock
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/2/3" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                    <i class="fa fa-flag" style="color:#70AD47"></i> Productos que hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/3/3" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                    <i class="fa fa-flag" style="color:#4472C4"></i> Productos que no rotan
                                </div>
                                <div class="col-md-2">
                                    <a href="<?php echo $pagRoute ?>/5/3" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"> 
        </div>
        <div class="col-md-3">        
            <div class="row">        
                <div class="panel panel-info">
                    <div class="panel-heading">                
                        Productos Nivel I
                    </div>
                    <div class="panel-body">                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                    <i class="fa fa-flag" style="color:#FF0000"></i> Productos que no hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/1/1" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                    <i class="fa fa-flag" style="color:#FFC000"></i> Productos criticos por agotarse
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/4/1" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                    <i class="fa fa-flag" style="color:#ED7D31"></i> Productos en Sobrestock
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/2/1" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                    <i class="fa fa-flag" style="color:#70AD47"></i> Productos que hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/3/1" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                    <i class="fa fa-flag" style="color:#4472C4"></i> Productos que no rotan
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/5/1" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                    </div>
                </div>
                <br/><br/>        
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Todos los productos
                    </div>
                    <div class="panel-body">                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                    <i class="fa fa-flag" style="color:#FF0000"></i> Productos que no hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/1/0" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                    <i class="fa fa-flag" style="color:#FFC000"></i> Productos criticos por agotarse
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/4/0" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                    <i class="fa fa-flag" style="color:#ED7D31"></i> Productos en Sobrestock
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/2/0" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                    <i class="fa fa-flag" style="color:#70AD47"></i> Productos que hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/3/0" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                    <i class="fa fa-flag" style="color:#4472C4"></i> Productos que no rotan
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/5/0" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"> 
        </div>
        <div class="col-md-3">        
            <div class="row">        
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        Productos Nivel II
                    </div>
                    <div class="panel-body">                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                    <i class="fa fa-flag" style="color:#FF0000"></i> Productos que no hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/1/2" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                    <i class="fa fa-flag" style="color:#FFC000"></i> Productos criticos por agotarse
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/4/2" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                    <i class="fa fa-flag" style="color:#ED7D31"></i> Productos en Sobrestock
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/2/2" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                    <i class="fa fa-flag" style="color:#70AD47"></i> Productos que hay stocks
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/3/2" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-10" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                    <i class="fa fa-flag" style="color:#4472C4"></i> Productos que no rotan
                                </div>
                                <div class="col-md-2">
                                    <a href="visualizar_productos_nivel/5/2" class="btn btn-default btn-xs">ver</a>
                                </div>
                            </div>
                        </div>                
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
