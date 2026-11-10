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
    $(document).ready(function () {
        $('#example2').DataTable();
    });
    
    
    $(function () {
        Highcharts.chart('container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<span style="text-transform: uppercase; font-size:10px">{{$nombre_farmacia}}</span><br><span style="font-size: 14px; font-weight:bold">Reporte: Profesionales Prescriptores</span>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b> <br>{point.y} <br> {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                    name: 'Porcentaje',
                    colorByPoint: true,
                    data: [{
                            name: 'PROFESIONAL <br>DE LA INSTITUCION',
                            y: {{$institucion}}
                        },{
                            name: 'PROFESIONAL<br> CONTRATADO',
                            y: {{$contratado}}
                        }, {
                            name: 'SERUMISTA <br>CONTRATADO',
                            y: {{$serumista}}
                        }]
                }]
        });
    });
</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Profesionales de Salud Prescriptores </h3>
                    <div class="pull-right">                        
                        <a class="btb btn-default btn-xs" href="/allFarmacias_withStocks">
                            <i class="fa fa-arrow-left"></i> Nuevo reporte
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" />
                </div>
                @if(count($medicos)>0)
                <legend style="text-align: center">{{$nombre_farmacia}}</legend>
                <table id="example" class="table" cellspacing="0" width="100%" style="font-size: 10px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DNI</th>                          
                            <th>Nombre</th>
                            <th>Ape. Paterno</th>
                            <th>Ape. Materno</th>
                            <th>Colegiatura</th>
                            <th>Especialización</th>
                            <th>Contrato</th>
                            <!--<th>Ver</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; $i=0; $c=0; ?>
                        @foreach($medicos as $m)
                        <tr>
                            <td>
                                <?php
                                    $n++; 
                                    echo $n;                                    
                                ?>
                            </td>                                                         
                            <td>
                                {{$m->dni}}<br>
                                @role('Direoss')
                                <a href="/editar_medico/{{$m->id}}/{{$id_farmacia}}">Editar</a>
                                @endauth 
                            </td>                                                        
                            <td>{{$m->nombre}}</td>                            
                            <td>{{$m->paterno}}</td>                            
                            <td>{{$m->materno}}</td>  
                            <td>{{$m->colegiatura}}</td>
                            <td>{{$m->especializacion}}</td>
                            <td>
                                @if($m->tipo_contrato==1)
                                    Institucional
                                    <?php $i++; ?>
                                @else
                                    <?php $c++; ?>
                                    @if($m->tipo_contrato==2)
                                        Profesional Contratado
                                    @else
                                        Profesional Contratado (SERUMS)
                                    @endif
                                @endif
                                
                            </td>
                            <!--<td><div class="center"><button data-toggle="modal" data-target="#squarespaceModal" class="btn btn-primary center-block btn-xs">Ver</button></div></td>-->
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
        <div class="col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                <div class="panel-body"  style="display: block;"> 
                    @if(count($medicos)>0)
                    <div class="col-md-12" style="margin-bottom: 10px">
                        Total de Profesionales prescriptores: <?php echo (count($medicos)) ?></br>                        
                        Prescritores de la Institución: {{$institucion}}</br>
                        Prescritores Contratados: {{$contratado}}</br>
                        Prescritores Contratados (Serums): {{$serumista}}
                    </div>
                    @else                                         
                    <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                    </div>
                    @endif
                </div>
                <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 20px 20px 20px 20px"></div>
            </div>
        </div>
    </div>
</div>


@endsection
