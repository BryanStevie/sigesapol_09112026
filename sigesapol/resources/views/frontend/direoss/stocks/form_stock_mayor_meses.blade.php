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

$validarFechaDesde = $txtfechaDesde_[1].'/'.$txtfechaDesde_[2];
$validarFechaHasta = $txtfechaHasta_[1].'/'.$txtfechaHasta_[2];

$arrayMeses=array();
$arrayMeses[]=$mes."/".$ano;


?>

<div class="container" style="width:100%">
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">Productos por Consumo Promedio Mensual</h3>
                    <div class="pull-right">                        
                        <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtar</button>
                        <a class="btn btn-default btn-xs" href="/otros_reportes_by_farma/10">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" />
                </div>
                @if(count($productos)>0)
                <table id="example" class="table" cellspacing="0" width="100%" style="font-size: 11px">
                    <thead>
                        <tr class="filters">
                            <th>#</th>
                            <th>Código</th>
                            <th><input type="text" class="form-control input-sm" placeholder="Productos Farmacéuticos y Dispositivos médicos" disabled></th>
                            <th>&nbsp;<?php echo $mes . "/" . $ano ?>&nbsp;</th>
                            <?php
                            $m = 0;
                            while ($nuevo != $limite && $validarFechaDesde != $validarFechaHasta):
                                $m++;
                                $nuevo = date("m/Y", mktime(0, 0, 0, $mes + $m, 1, $ano));
                                $arrayMeses[] = $nuevo;
                                ?>
                                <th>&nbsp;<?php echo $nuevo ?>&nbsp;</th>
                            <?php endwhile; ?>

                            <th>Acumulado</th>
                            <th>Promedio Mensual</th>
                            <!--<th>Stock al <?php echo ($nuevo != "") ? $nuevo : $mes . "/" . $ano; ?></th>--><th>Stock actual</th>
                            <th>Nro. de Meses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; 
                            
                        ?>


                        @foreach($productos as $f)
                        <?php
                        $acumulado = 0;
                        $nro_mes_acumulado = App\Models\Kardexe::ShowNroMesByProd($f->id_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
                        
                        ?>
                        <tr>
                            <td><?php $n++;
                        echo $n ?></td>
                            <td>{{$f->codigo}}</td>
                            <td>{{$f->nombre}}</td>

                            <?php 
                            $nromes = 0;
                            foreach ($arrayMeses as $rowMes): 
                                ?>
                                <td>
                                    <?php
                                    $nromes ++;
                                    $fechaMes = explode("/", $rowMes);
                                    $month = $fechaMes[1] . '-' . $fechaMes[0];
                                    $aux = date('Y-m-d', strtotime("{$month} + 1 month"));
                                    $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
                                    $fecha_inicio_ = '01-' . str_replace("/", "-", $rowMes);
                                    $fecha_fin_ = $last_day;

                                    $total = App\Models\Kardexe::ShowTotalByProd($f->id_producto, $nombre_farmacia, $fecha_inicio_, $fecha_fin_);
                                    $stock = (isset($total->total) && $total->total > 0) ? $total->total : 0;

                                    $egresos = App\Models\Kardexe::ShowEgresosByProd($f->id_producto, $nombre_farmacia, $fecha_inicio_, $fecha_fin_);
                                    $recetasDevueltas = App\Models\Kardexe::ShowRecetasDevueltas($f->id_producto, $nombre_farmacia, $fecha_inicio_, $fecha_fin_);
                                    $nro_mes = App\Models\Kardexe::ShowNroMesByProd($f->id_producto, $nombre_farmacia, $fecha_inicio_, $fecha_fin_);
                                    $g = 0;
                                    if ($nro_mes->nro_mes > 0):
                                        $g = ($egresos->total - $recetasDevueltas->recetas_devueltas) / $nro_mes->nro_mes;
                                        $acumulado += $g;
                                    endif;

                                    echo $g;
                                    ?>
                                </td>
                                <?php endforeach; ?>
                            <td><?php echo $acumulado ?></td>
                            <td>
                            <?php
                            $promedio = $acumulado / $nro_mes_acumulado->nro_mes;
                            echo number_format($promedio, 0, '', '');
                            ?>
                            </td>
                            <td><?php echo $stock; ?></td>
                            <td style="text-align: center;">
                                <?php
                                /*$provision = 0;
                                if ($promedio > 0):
                                    $provision = $stock / $promedio;
                                endif;

                                $provision = round($provision, 0);
                                echo $provision;*/
                                echo $nromes;
                                ?>
                            </td>
                        </tr>
                        @endforeach


                    </tbody>


                </table>
                @else                                         
                <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                </div>
                @endif


                <div style="margin: 10px 10px 10px 10px; border-top: 1px solid gray">
                    <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                <div class="panel-body"  style="display: block;">
                    @if(count($productos)>0)
                    <div class="col-md-12" style="margin-bottom: 10px">Productos con Stock con Promedio Mensual: <?php echo $n ?></div>                    
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a class="btn btn-success form-control" href="/exportar_reporte_productos_atendidos/{{$fecha_inicio}}/{{$fecha_fin}}/{{$nombre_farmacia}}">
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
