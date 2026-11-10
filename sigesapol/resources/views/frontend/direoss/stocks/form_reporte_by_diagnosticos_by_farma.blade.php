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
        width: 1000px;
    }
</style>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
    $(document).ready(function () {
        $('#example2').DataTable({            
            "info": false
        });
    });
    
    
    $(function () {
    // Create the chart
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: '<span style="text-transform: uppercase; font-size:13px">{{$nombre_farmacia}}</span><br>TOP 10 de los Diagnósticos más prescritos'
        },
        subtitle: {
            text: '<span style="font-size:15px">Del {{$fecha_inicio}} al {{$fecha_fin}}</span>'
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Número de veces prescrito'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
            }
        },

        tooltip: {           
            pointFormat: '{point.y}</b><br/>'
        },

        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [
                <?php $x=0; ?>
                @foreach($cies as $c)
                <?php $x++; ?>
                    @if($x<=10)
                    {   name: '<span style="font-size:8px">{{$c->codigo}}-{{$c->nombre}}</span>',
                        y: {{$c->contador}},
                        drilldown: '{{$c->codigo}}-{{$c->nombre}}'},
                    @endif
                @endforeach
            ]
        }],        
    });
});
</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">

        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Reporte de Diagnosticos según prescripción - Del {{$fecha_inicio}} al {{$fecha_fin}}</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/otros_reportes_by_farma/4">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" />
                </div>
                @if(count($cies)>0)
                <legend style="text-align: center">{{$nombre_farmacia}}</legend>
                <table id="example" class="display" cellspacing="0" width="100%" style="font-size: 11px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Codigo</th>
                            <th>Diagnostico</th>                            
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($cies as $c)
                        <tr>
                            <td><?php
                                $n++;
                                echo $n
                                ?></td>
                            <td>{{$c->codigo}}</td>
                            <td>{{$c->nombre}}</td>                            
                            <td>{{$c->contador}}</td>                            
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
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                <div class="panel-body"  style="display: block;">
                    @if(count($cies)>0)
                    <div class="col-md-12" style="margin-bottom: 10px">Diagnosticos según prescripción: <?php echo $n ?></div>                    
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a class="btn btn-success form-control" href="/exportar_reporte_por_diagnosticos/{{$fecha_inicio}}/{{$fecha_fin}}/{{$id_farmacia}}">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a class="btn btn-default btn-xs" data-toggle="modal" data-target="#squarespaceModal"  href="">Ver historial de diagnosticos</a>
                    </div>
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
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Historial de Diagnosticos Prescritos Del {{$fecha_inicio}} al {{$fecha_fin}}</h3>
            </div>
            <div class="modal-body">
                <table class="table" id="example2" style="font-size: 11px">
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>CODIGO</th>
                            <th>DIAGNOSTICO</th>
                            <?php
                            $valores = array();
                            foreach ($mes_anio as $m_a) {
                                $meses_anios = explode('_', $m_a);
                                $valores[$m_a] = 0;
                                ?>
                                <th> <?php echo $meses[$meses_anios[0]] . ' - ' . $meses_anios[1]; ?></th>
                                <?php
                            }
                            ?>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <?php
                    $productoGeneral = array();
                    foreach ($productoos as $producto) {
                        if (!array_key_exists($producto->codigo, $productoGeneral)) {
                            $productoGeneral[$producto->codigo] = array('producto' => $producto, 'valores' => $valores, $producto->codigo);
                        }
                        $productoGeneral[$producto->codigo]['valores'][$producto->fecha_anio] = $producto->total;
                    }
                    ?>
                    <tbody>
                        <?php
                        $fila = 1;
                        foreach ($productoGeneral as $productoItem) {
                            $total = 0;
                            ?>

                            <tr>                              
                                <td><?php echo $fila ?></td>
                                <td><?php echo $productoItem['producto']->codigo ?></td>
                                <td><?php echo $productoItem['producto']->nombre ?></td>
                                <?php
                                foreach ($productoItem['valores'] as $valores) {
                                    $total += floatval($valores);
                                    ?>
                                    <td><?php echo $valores ?></td>
                                    <?php
                                }
                                ?>
                                <td><?php echo $total ?></td>
                            </tr>
                            <?php
                            $fila += 1;
                        }
                        ?>
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
</div>
@endsection
