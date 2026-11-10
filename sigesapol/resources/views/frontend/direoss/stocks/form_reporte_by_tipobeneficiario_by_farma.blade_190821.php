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

    $(function () {
        Highcharts.chart('container2', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<span style="text-transform: uppercase; font-size:13px">{{$nombre_farmacia}} - AMBULATORIO </span><br><span style="font-size: 17px; font-weight:bold">Recetas Atendidas por Beneficiario</span><br><span style="font-size:15px">Del {{$fecha_inicio}} al {{$fecha_fin}}</span>'
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
                        format: '<b>{point.name}</b> <br>Recetas:{point.y} <br> {point.percentage:.1f} %',
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
                        name: 'TITULARES',
                        y: {{$titular[0]->contador}}
                    },{
                        name: 'PADRES',
                        y: {{$padres[0]->contador}}
                    }, {
                        name: 'CONYUGUES',
                        y: {{$conyuges[0]->contador}}
                    }, {
                        name: 'HIJOS',
                        y: {{$hijo_incapacitado[0]->contador}} + {{$hijo_no_incapacitado[0]->contador}}
                    }]
            }]
        });        
        Highcharts.chart('container3', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<span style="text-transform: uppercase; font-size:13px">{{$nombre_farmacia}} - HOSPITALIZACION</span><br><span style="font-size: 17px; font-weight:bold">Recetas Atendidas por Beneficiario</span><br><span style="font-size:15px">Del {{$fecha_inicio}} al {{$fecha_fin}}</span>'
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
                        format: '<b>{point.name}</b> <br>Recetas:{point.y} <br> {point.percentage:.1f} %',
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
                        name: 'TITULARES',
                        y: {{$titular_2[0]->contador}}
                    },{
                        name: 'PADRES',
                        y: {{$padres_2[0]->contador}}
                    }, {
                        name: 'CONYUGUES',
                        y: {{$conyuges_2[0]->contador}}
                    }, {
                        name: 'HIJOS',
                        y: {{$hijo_incapacitado_2[0]->contador}} + {{$hijo_no_incapacitado_2[0]->contador}}
                    }]
            }]
        });        
        Highcharts.chart('container4', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<span style="text-transform: uppercase; font-size:13px">{{$nombre_farmacia}} - EMERGENCIA </span><br><span style="font-size: 17px; font-weight:bold">Recetas Atendidas por Beneficiario</span><br><span style="font-size:15px">Del {{$fecha_inicio}} al {{$fecha_fin}}</span>'
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
                        format: '<b>{point.name}</b> <br>Recetas:{point.y} <br> {point.percentage:.1f} %',
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
                        name: 'TITULARES',
                        y: {{$titular_1[0]->contador}}
                    },{
                        name: 'PADRES',
                        y: {{$padres_1[0]->contador}}
                    }, {
                        name: 'CONYUGUES',
                        y: {{$conyuges_1[0]->contador}}
                    }, {
                        name: 'HIJOS',
                        y: {{$hijo_incapacitado_1[0]->contador}} + {{$hijo_no_incapacitado_1[0]->contador}}
                    }]
            }]
        });        
        Highcharts.chart('container5', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<span style="text-transform: uppercase; font-size:13px">{{$nombre_farmacia}}</span><br><span style="font-size: 17px; font-weight:bold">Recetas Atendidas por Beneficiario</span><br><span style="font-size:15px">Del {{$fecha_inicio}} al {{$fecha_fin}}</span>'
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
                        format: '<b>{point.name}</b> <br>Recetas:{point.y} <br> {point.percentage:.1f} %',
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
                        name: 'TITULARES',
                        y: {{$titular[0]->contador+$titular_1[0]->contador+$titular_2[0]->contador}}
                    },{
                        name: 'PADRES',
                        y: {{$padres[0]->contador+$padres_1[0]->contador+$padres_2[0]->contador}}
                    }, {
                        name: 'CONYUGUES',
                        y: {{$conyuges[0]->contador+$conyuges_1[0]->contador+$conyuges_2[0]->contador}}
                    }, {
                        name: 'HIJOS',
                        y: {{$hijo_incapacitado[0]->contador}} + {{$hijo_no_incapacitado[0]->contador}} +{{$hijo_incapacitado_1[0]->contador}} + {{$hijo_no_incapacitado_1[0]->contador}} +{{$hijo_incapacitado_2[0]->contador}} + {{$hijo_no_incapacitado_2[0]->contador}}
                    }]
            }]
        });        
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
                text: '<span style="text-transform: uppercase; font-size:13px">{{$nombre_farmacia}}</span><br><span style="font-size: 17px; font-weight:bold">Recetas Atendidas por Beneficiario</span><br><span style="font-size:15px">Del {{$fecha_inicio}} al {{$fecha_fin}}</span>'
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
                        format: '<b>{point.name}</b> <br>Recetas:{point.y} <br> {point.percentage:.1f} %',
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
                        name: 'TITULARES',
                        y: {{$titular[0]->contador}}
                    },{
                        name: 'PADRES',
                        y: {{$padres[0]->contador}}
                    }, {
                        name: 'CONYUGUES',
                        y: {{$conyuges[0]->contador}}
                    }, {
                        name: 'HIJOS',
                        y: {{$hijo_incapacitado[0]->contador}} + {{$hijo_no_incapacitado[0]->contador}}
                    }]
            }]
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
                    <h3 class="panel-title">Recetas Dispensadas por Tipo de Beneficiario</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/otros_reportes_by_farma/7">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" />
                </div>
                <legend style="text-align: center">{{$nombre_farmacia}}</legend>
                <p class="text-center">Del {{$fecha_inicio}} al {{$fecha_fin}}</p>
                @if($id_farmacia==22 || $id_farmacia==14)
                <table cellspacing="0" width="50%"  border="1px" class="table">
                    <thead>
                        <tr>
                            <th>#</th>                            
                            <th>Tipo Beneficiario</th>                            
                            <th>AMBULATORIO</th>                            
                            <th>HOSPITALIZACION</th>
                            <th>EMERGENCIA</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>                            
                            <td>TITULAR</td>                            
                            <td>
                                {{$titular[0]->contador}}
                            </td>
                            <td>{{$titular_2[0]->contador}}
                            </td>
                            <td>{{$titular_1[0]->contador}}
                            </td>
                            <td>{{$titular[0]->contador+$titular_1[0]->contador+$titular_2[0]->contador}}
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>                            
                            <td>PADRES</td>                            
                            <td>
                                {{$padres[0]->contador}}                                
                            </td>
                            <td>{{$padres_2[0]->contador}}
                            </td>
                            <td>{{$padres_1[0]->contador}}
                            </td>
                            <td>{{$padres[0]->contador+$padres_1[0]->contador+$padres_2[0]->contador}}
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>                            
                            <td>CONYUGES</td>                            
                            <td>
                                {{$conyuges[0]->contador}}                                
                            </td>
                            <td>{{$conyuges_2[0]->contador}}
                            </td>
                            <td>{{$conyuges_1[0]->contador}}
                            </td>
                            <td>{{$conyuges[0]->contador+$conyuges_1[0]->contador+$conyuges_2[0]->contador}}
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>                            
                            <td>HIJOS</td>                            
                            <td>
                                <?php echo $hijos = $hijo_incapacitado[0]->contador + $hijo_no_incapacitado[0]->contador; ?>
                            </td>
                            <td><?php echo $hijos_2 = $hijo_incapacitado_2[0]->contador + $hijo_no_incapacitado_2[0]->contador; ?>
                            </td>
                            <td>
                                <?php echo $hijos_1 = $hijo_incapacitado_1[0]->contador + $hijo_no_incapacitado_1[0]->contador; ?>
                            </td>
                            <td>{{$hijos+$hijos_1+$hijos_2}}
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>                            
                            <td>TOTAL</td>                            
                            <td>
                                {{$titular[0]->contador+$conyuges[0]->contador+$padres[0]->contador+$hijos}}                                
                            </td>
                            <td>{{$titular_2[0]->contador+$conyuges_2[0]->contador+$padres_2[0]->contador+$hijos_2}} 
                            </td>
                            <td>{{$titular_1[0]->contador+$conyuges_1[0]->contador+$padres_1[0]->contador+$hijos_1}} 
                            </td>
                            <td>{{$titular[0]->contador+$titular_1[0]->contador+$titular_2[0]->contador+$padres[0]->contador+$padres_1[0]->contador+$padres_2[0]->contador+$conyuges[0]->contador+$conyuges_1[0]->contador+$conyuges_2[0]->contador+$hijos+$hijos_1+$hijos_2}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="container2" style="min-width: 310px; height: 400px; max-width: 600px; margin: 20px 20px 20px 20px"></div>
                <div id="container3" style="min-width: 310px; height: 400px; max-width: 600px; margin: 20px 20px 20px 20px"></div>
                <div id="container4" style="min-width: 310px; height: 400px; max-width: 600px; margin: 20px 20px 20px 20px"></div>
                <div id="container5" style="min-width: 310px; height: 400px; max-width: 600px; margin: 20px 20px 20px 20px"></div>
                @else
                <table cellspacing="0" width="50%"  border="1px" class="table">
                    <thead>
                        <tr>
                            <th>#</th>                            
                            <th>Tipo Beneficiario</th>                            
                            <th>Número de Recetas Prescritas y atendidas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>                            
                            <td>TITULAR</td>                            
                            <td>{{$titular[0]->contador}}</td>
                        </tr>
                        <tr>
                            <td>2</td>                            
                            <td>PADRES</td>                            
                            <td>{{$padres[0]->contador}}</td>
                        </tr>
                        <tr>
                            <td>3</td>                            
                            <td>CONYUGES</td>                            
                            <td>{{$conyuges[0]->contador}}</td>
                        </tr>
                        <tr>
                            <td>4</td>                            
                            <td>HIJOS</td>                            
                            <td><?php $hijos = $hijo_incapacitado[0]->contador + $hijo_no_incapacitado[0]->contador; echo $hijos; ?></td>
                        </tr>
                    </tbody>
                </table>
                <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 20px 20px 20px 20px"></div>
                
                @endif
                
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                @if($id_farmacia==22 || $id_farmacia==14)
                    <div class="panel-body"  style="display: block;">                    
                        <div class="col-md-12" style="margin-bottom: 10px">Titular(es): 
    <?php echo $titular[0]->contador+$titular_1[0]->contador+$titular_2[0]->contador; ?>
                        </div>  
                        <div class="col-md-12" style="margin-bottom: 10px">Derechohabiente(s): 
    <?php  echo $padres[0]->contador+$padres_1[0]->contador+$padres_2[0]->contador+$conyuges[0]->contador+$conyuges_1[0]->contador+$conyuges_2[0]->contador+$hijos+$hijos_1+$hijos_2; ?>
                        </div>  
                        <div class="col-md-12" style="margin-bottom: 10px">
                            <a class="btn btn-success form-control" href="/exportar_reporte_por_beneficiario2/{{$fecha_inicio}}/{{$fecha_fin}}/{{$id_farmacia}}">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                            </a>                        
                        </div>
                    </div>
                @else
                    <div class="panel-body"  style="display: block;">                    
                        <div class="col-md-12" style="margin-bottom: 10px">Titular(es): 
    <?php echo $titular[0]->contador; ?>
                        </div>  
                        <div class="col-md-12" style="margin-bottom: 10px">Derechohabiente(s): 
    <?php  echo $hijos + $padres[0]->contador + $conyuges[0]->contador; ?>
                        </div>  
                        <div class="col-md-12" style="margin-bottom: 10px">
                            <a class="btn btn-success form-control" href="/exportar_reporte_por_beneficiario/{{$fecha_inicio}}/{{$fecha_fin}}/{{$id_farmacia}}">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                            </a>                        
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
