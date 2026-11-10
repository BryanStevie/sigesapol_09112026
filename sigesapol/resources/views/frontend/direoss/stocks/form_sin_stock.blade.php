@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .row{
        margin-top:40px;
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
        display: none;
    }
    #squarespaceModal .modal-dialog{
        width: 1200px;
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
</script>
@endsection
@section('content')

<?php

$txtfechaDesde_=explode("-",$fecha_inicio);
$txtfechaHasta_=explode("-",$fecha_fin);

$fechaIni=$txtfechaDesde_[2]."/".$txtfechaDesde_[1]."/01"; 
$fechaFin=$txtfechaHasta_[2]."/".$txtfechaHasta_[1]."/01";

$mes = date("m", strtotime($fechaIni));
$ano=  date("Y", strtotime($fechaIni));

$limite=$txtfechaHasta_[1].'/'.$txtfechaHasta_[2];
$nuevo=0;

$arrayMeses=array();
$arrayMeses[]=$mes."/".$ano;


?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">Número de Productos sin Stock</h3>
                    <div class="pull-right">
                        <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtar</button>
                        <a class="btn btn-default btn-xs" href="/otros_reportes_by_farma/12">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" />
                </div>
                @if(count($stocks)>0)
                <table id="example" class="table" cellspacing="0" width="100%" style="font-size: 11px">
                    <thead>
                        <tr class="filters">
                            <th>#</th>
                            <th>Código</th>
                            <th><input type="text" class="form-control input-sm" placeholder="Productos Farmacéuticos y Disp. Médicos" disabled></th>
                            <th>Unidad</th>
                            <th>Stock Actual</th>
                        </tr>
                    </thead>

                    <tbody>


                        <?php $n = 0 ?>
                        @foreach($stocks as $f)
                        <tr>
                            <td><?php $n++;
                        echo $n ?></td>
                            <td>{{$f->codigo_producto}}</td>
                            <td>{{$f->nombre_producto}}</td>
                            <td>{{$f->unidad}}</td>
                            <td>{{$f->total}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else                                         
                <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                </div>
                @endif
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                <div class="panel-body"  style="display: block;">
                    @if(count($stocks)>0)
                    <div class="col-md-12" style="margin-bottom: 10px">Productos sin Stock: <?php echo $n ?></div>                    
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a class="btn btn-success form-control" href="/exportar_reporte_productos_sin_stock/{{$fecha_inicio}}/{{$fecha_fin}}/{{$id_farmacia}}">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>                        
                    </div>
                    <!--<div class="col-md-12" style="margin-bottom: 10px">
                          <a class="btn btn-default btn-xs" data-toggle="modal" data-target="#squarespaceModal"  href="">Ver historial de diagnosticos</a>
                    </div>-->
                    @else                                         
                    <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                    </div>
                    @endif
                </div>                
            </div>
        </div>
    </div>
</div>    
     
@endsection
