@extends('frontend.layouts.master4')

@section('content')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
        
    const datas = [
        { name: 'Enviados', y: <?php echo $data1['total_enviados'];?> },
        { name: 'No Enviados', y: <?php echo $data1['total_no_enviados'];?> },
    ];

    const total1 = datas.reduce((sum, point) => sum + point.y, 0);
    Highcharts.chart('estado_enviados_y_enviados', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Prestaciones Enviados y no Enviados'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y})',
        valueSuffix: ''
    },
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -40,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                }
            }]
        }
    },
    series: [{
            name: 'Porcentaje',
            colorByPoint: true,
            data: datas
        }],
       
    });



    const data = [
        { name: 'Observados', y: <?php echo $data2['total_observados'];?> },
        { name: 'Modificados', y: <?php echo $data2['total_modificados'];?> },
        { name: 'Rennviados', y: <?php echo $data2['total_reenviados'];?> }
    ];

    const total = data.reduce((sum, point) => sum + point.y, 0);

    Highcharts.chart('estado_enviados', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Enviados'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y})',
            valueSuffix: ''
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y} ({point.percentage:.1f}%)', 
                    style: {
                        fontSize: '1em',
                        textOutline: 'none',
                        opacity: 0.7
                    }
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Porcentaje',
            colorByPoint: true,
            data: data
        }],
   
        center: [{
            text: 'Total: ' + <?php echo $data2['total_enviados'];?>,
            align: 'center',
            verticalAlign: 'middle',
            style: {
                fontSize: '2em',
                color: 'black'
            }
        }]
    });
  });
  $(document).ready(function () {

    $('#fecha_inicio').datepicker().on('hide', function (ev) {
        var fechaFinIni = $('#fecha_fin').val();
        var fechaIni = $('#fecha_inicio').val();
        f1 = fechaIni.split("-");
        var fechaFin = $('#fecha_fin').val();
        f2 = fechaFin.split("-");
        f3 = new Date();
        var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
        var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
        if (f1 > f2) {
            $('#fecha_inicio').val(fechaFinIni);
            $('#fecha_inicio').change();
            bootbox.alert("La Fecha de Inicio no debe ser mayor a la Fecha Fin");
            return false;
        }
    });
    $('#fecha_fin').datepicker().on('hide', function (ev) {
        var fechaFinIni = $('#fecha_inicio').val();
        var fechaIni = $('#fecha_inicio').val();
        f1 = fechaIni.split("-");
        var fechaFin = $('#fecha_fin').val();
        f2 = fechaFin.split("-");
        f3 = new Date();
        var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
        var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
        if (f1 > f2) {
            $('#fecha_fin').val(fechaFinIni);
            $('#fecha_fin').change();
            bootbox.alert("La Fecha Fin no debe ser menor a la Fecha de Inicio");
            return false;
        }
    });

  });
  function buscar() {
    Swal.fire({
        title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
            header : 'swal2-header-generar'
        },
        onBeforeOpen: () => {
            $('#blur-container').append('<div class="blur-background"></div>');
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_fin = $('#fecha_fin').val();

            if (fecha_inicio == "")fecha_inicio = 0;
            if (fecha_fin == "")fecha_fin = 0;

            Swal.showLoading();
            $("#form_estados_atrama").submit();
          }
    });
  }
</script>
<style>
  .highcharts-figure,
  .highcharts-data-table table {
      min-width: 320px;
      max-width: 800px;
      margin: 1em auto;
  }

  .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #ebebeb;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
  }

  .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
  }

  .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
  }

  .highcharts-data-table td,
  .highcharts-data-table th,
  .highcharts-data-table caption {
      padding: 0.5em;
  }

  .highcharts-data-table thead tr,
  .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
  }

  .highcharts-data-table tr:hover {
      background: #f1f7ff;
  }

  input[type="number"] {
      min-width: 50px;
  }
</style>

<div class="loader"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="container col-md-6 col-md-offset-3">
      <div class="panel panel-primary">
          <div class="panel-heading">
              <div class="panel-title">
               Busqueda de Estados de las tramas 
              </div>
          </div>
          <div class="panel-body">
            <form method="post" action="{{ route('estados_trama_prestacion_grafico') }}" id="form_estados_atrama">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="consulta" value="1">
                  <div class="col-md-4">
                      <label class="help-block">Fecha de Inicio <i class="fa fa-calendar"></i></label>
                      <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required="" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10" value="<?php echo $fecha_inicio;?>" />
                  </div>
                  <div class="col-md-4">
                      <label class="help-block">Fecha Fin <i class="fa fa-calendar"></i></label>
                      <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="<?php echo $fecha_fin_actual;?>" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10" >
                  </div>
            
              <div class="col-md-4 pull-right" style="margin-top: 10px"><br>
                  <button class="form-control btn btn-info" type="submit" onclick="buscar()">Buscar</button>
              </div>
            </form>
          </div>
      </div>
    </div>
  </div>




  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active" id="tab2default">
              <fieldset>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="row">
                        <div class="panel panel-primary filterable">
                          <div class="panel-heading">
                            <h3 class="panel-title">Resumen de estados de la Trama Prestacional enviados y no enviado a SALUDPOL</h3>
                          </div>
                          <div class="panel-body">

                            <div class="row">
                              <div class="col-md-6">
                                <div class="thumbnail">
                                  <div class="caption">
                                      <div id="estado_enviados_y_enviados" style="width: 100%; height: 400px;"></div> 
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="thumbnail">
                                  <div class="caption">
                                    <div id="estado_enviados" style="width: 100%; height: 400px;"></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('frontend.includes.footer')
  @endsection