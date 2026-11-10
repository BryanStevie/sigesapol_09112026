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
</style>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
    
    function ver_det_servicio(idUser, id_consultorio,fecha_inicio,fecha_fin){
        $.ajax({
        url: '/ver_det_servicio/2/' + idUser + '/' + id_consultorio + '/' + fecha_inicio + '/' + fecha_fin,
                async: false,
                success: function(result){
                $("#muestra-det-servicio").html(result);
                },
                complete: function () {
                $("#myModalDetRV").modal();
                }
        });
    }


$(function () {
    // Create the chart
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: '<span style="text-transform: uppercase; font-size:13px">{{$nombre_farmacia}}</span><br>Número de recetas prescritas por Servicios'
        },
        subtitle: {
            text: '<span style="font-size:15px">Del {{$fecha_inicio}} al {{$fecha_fin}}</span>'
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Número de recetas prescritas'
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
            pointFormat: '{point.y}</b> recetas prescritas<br/>'
        },

        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [
                @foreach($consultorios as $c)
                {   name: '{{$c->nombre}}',
                    y: {{$c->contador}},
                    drilldown: '{{$c->nombre}}'},
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
                    <h3 class="panel-title">Recetas Dispensadas por Servicio</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/otros_reportes_by_farma/6">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" />
                </div>
                @if(count($consultorios)>0)
                <legend style="text-align: center">{{$nombre_farmacia}}</legend>
                <p class="text-center">Del {{$fecha_inicio}} al {{$fecha_fin}}</p>
                <table id="example" class="display" cellspacing="0" width="100%" style="font-size: 11px">
                    <thead>
                        <tr>
                            <th>#</th>                            
                            <th>Servicio</th>                            
                            <th>Número de recetas prescritas</th>
                            <th>Productos Dispensados</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($consultorios as $c)
                        <tr>
                            <td><?php
                                $n++;
                                echo $n
                                ?></td>                            
                            <td>{{$c->nombre}}</td>                            
                            <td>{{$c->contador}}</td>
                            <td>
                                    <button type="button" class="btn btn-primary btn-xs" onclick="ver_det_servicio('{{ Auth::user()->id }}','{{$c->id}}','{{$fecha_inicio}}','{{$fecha_fin}}')"><i class="fa fa-search"></i></button>
                                    <a target="_blank" class="btn btn-success btn-xs" href="/exportar_reporte_productos_por_servicios/{{$c->id}}/{{$fecha_inicio}}/{{$fecha_fin}}/{{$id_farmacia}}">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    </a>                        
                                    <a class="btn btn-danger btn-xs" target="_blank" href="/imprimir_reporte_productos_por_servicios/{{$c->id}}/{{$fecha_inicio}}/{{$fecha_fin}}/{{$id_farmacia}}">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>                        
                                
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
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                <div class="panel-body"  style="display: block;">
                    @if(count($consultorios)>0)
                    <div class="col-md-12" style="margin-bottom: 10px">Total de Servicios: <?php echo $n ?></div>                    
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a class="btn btn-success form-control" href="/exportar_reporte_por_consultorios/{{$fecha_inicio}}/{{$fecha_fin}}/{{$id_farmacia}}">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>                        
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
<div class="modal fade" id="myModalDetRV" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">PRODUCTOS POR SERVICIO</h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-servicio"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection
