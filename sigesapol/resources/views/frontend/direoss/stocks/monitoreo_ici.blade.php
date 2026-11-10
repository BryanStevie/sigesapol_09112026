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
    (function () {
        'use strict';
        var $ = jQuery;
        $.fn.extend({
            filterTable: function () {
                return this.each(function () {
                    $(this).on('keyup', function (e) {
                        $('.filterTable_no_results').remove();
                        var $this = $(this),
                                search = $this.val().toLowerCase(),
                                target = $this.attr('data-filters'),
                                $target = $(target),
                                $rows = $target.find('tbody tr');

                        if (search == '') {
                            $rows.show();
                        } else {
                            $rows.each(function () {
                                var $this = $(this);
                                $this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
                            })
                            if ($target.find('tbody tr:visible').size() === 0) {
                                var col_count = $target.find('tr').first().find('td').size();
                                var no_results = $('<tr class="filterTable_no_results"><td colspan="' + col_count + '">No results found</td></tr>')
                                $target.find('tbody').append(no_results);
                            }
                        }
                    });
                });
            }
        });
        $('[data-action="filter"]').filterTable();
    })(jQuery);

    $(function () {
        // attach table filter plugin to inputs
        $('[data-action="filter"]').filterTable();

        $('.container').on('click', '.panel-heading span.filter', function (e) {
            var $this = $(this),
                    $panel = $this.parents('.panel');

            $panel.find('.panel-body').slideToggle();
            if ($this.css('display') != 'none') {
                $panel.find('.panel-body input').focus();
            }
        });
        $('[data-toggle="tooltip"]').tooltip();
    })

    function reporteProductos(id_establecimiento,tipo){
        location.href = 'exportar_reporte_ici_establecimiento/' + '<?php echo $fecha_inicio ?>' + '/' + '<?php echo $fecha_fin ?>' + '/' + id_establecimiento + '/' + tipo;
    }
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary filterable">                
                <div class="panel-heading">
                    <h3 class="panel-title">Establecimientos a nivel nacional</h3>                    
                    <div class="pull-right">                        
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                            <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    </div>
                </div>
                </br>
                <form class="" method="post" action="{{ route('allEstablecimientos_withStocks')}}" id="frmIci">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">                    
                    <div class="col-sm-4">
                        <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_inicio}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"> 
                        <span class="help-block">Fecha Desde</span>  
                    </div>
                    <div class="col-sm-4">
                        <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_fin}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"> 
                        <span class="help-block">Fecha Hasta</span>  
                    </div>
                    <div class="col-sm-3">                    
                        <button class="form-control btn btn-info" type="submit">Buscar</button>
                    </div>
                </form>
                <?php
                $menu = App\Models\Menu::getMenuByUsuario(Auth::user()->id, 'otros_reportes_by_farma');
                //print_r($menu);
                ?>            
                <br>
                <br>
                <br>
                <br>
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#home">Total</a></li>
                  <li><a data-toggle="tab" href="#menu1">Medicamentos</a></li>
                  <li><a data-toggle="tab" href="#menu2">Dispositivos</a></li>
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">                
                        <!--- Aqui agregue tab --->
                        <div class="panel-body" style="display: block;">
                            <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar Farmacia" />
                        </div>
                        <div class="panel-body" style="display: block;">
                            <table class="table table-hover" id="dev-table" style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Farmacia</th>
                                        <th rowspan="2">Total</th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#70AD47"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">NormoStock</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#FFC000"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">SubStock</div>
                                            </div>
                                        </th>                                        
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#ED7D31"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">SobreStock</div>
                                            </div>
                                        </th>                                        
                                        <th colspan="3" align="center" style="padding:0px;margin:0px;width:120px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos dias.</em><br><b>Recetas* = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#4472C4"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Sin Rotaci&oacute;n</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#FF0000"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Desabastecido</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#d81b60"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Existencia</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#00c0ef"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Disponibilidad</div>
                                            </div>
                                        </th>
                                        <th rowspan="2" class="text-center">Reporte ICI</th>
                                    </tr>
                                    <tr>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n = 0 ?>
                                    @foreach($listado as $f)
                                    <tr>
                                        <td><?php $n++;
                                    echo $n ?></td>
                                        <td>{{$f['nombre']}}</td>
                                        <td>{{$f['total']}}</td>
                                        @if($f['por_normoStock'] > 80)                         
                                            <td style="text-align:center;">{{$f['normoStock']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_normoStock'], 2, '.', ',')!!}</td>
                                            <td style="text-align:center;">{!! number_format($f['punt_normoStock'], 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_normoStock'] >= 60 && $f['por_normoStock'] <= 80)
                                                <td style="text-align:center;">{{$f['normoStock']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_normoStock'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_normoStock'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_normoStock'] < 60)
                                                    <td style="text-align:center;">{{$f['normoStock']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_normoStock'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_normoStock'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_subStock'] < 10)                            
                                            <td style="text-align:center;">{{$f['subStock']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_subStock'], 2, '.', ',') !!}</td>
                                            @if($f['por_subStock'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_subStock'], 2, '.', ',') !!}</td>
                                            @endif
                                        @else
                                            @if($f['por_subStock'] >= 10 && $f['por_subStock'] <= 20)
                                                <td style="text-align:center;">{{$f['subStock']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_subStock'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_subStock'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_subStock'] > 20)
                                                    <td style="text-align:center;">{{$f['subStock']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_subStock'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_subStock'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_sobreStock'] < 10)                            
                                            <td style="text-align:center;">{{$f['sobreStock']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sobreStock'], 2, '.', ',')!!}</td>
                                            @if($f['por_sobreStock'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_sobreStock'], 2, '.', ',') !!}</td>
                                            @endif
                                        @else
                                            @if($f['por_sobreStock'] >= 10 && $f['por_sobreStock'] <= 20)
                                                <td style="text-align:center;">{{$f['sobreStock']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_sobreStock'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_sobreStock'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_sobreStock'] > 20)
                                                    <td style="text-align:center;">{{$f['sobreStock']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_sobreStock'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sobreStock'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif    
                                    
                                        @if($f['por_sinRotacion'] == 0)                            
                                            <td style="text-align:center;">{{$f['sinRotacion']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sinRotacion'], 2, '.', ',')!!}</td>
                                            <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_sinRotacion'] > 0 && $f['por_sinRotacion'] < 10)
                                                @if($f['por_sinRotacion'] < 1)
                                                    <td style="text-align:center;">{{$f['sinRotacion']}}</td>
                                                    <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sinRotacion'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion'], 2, '.', ',') !!}</td>
                                                @else 
                                                    <td style="text-align:center;">{{$f['sinRotacion']}}</td>
                                                    <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_sinRotacion'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion'], 2, '.', ',') !!}</td>
                                                @endif
                                            @else
                                                @if($f['por_sinRotacion'] >= 10)
                                                    <td style="text-align:center;">{{$f['sinRotacion']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_sinRotacion'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif
                                        @if($f['por_desabastecido'] <= 10)                            
                                            <td style="text-align:center;">{{$f['desabastecido']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_desabastecido'], 2, '.', ',')!!}</td>
                                            @if($f['por_desabastecido'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(2, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_desabastecido'], 2, '.', ',') !!}</td>
                                            @endif    
                                        @else
                                            @if($f['por_desabastecido'] > 10 && $f['por_desabastecido'] <= 30)
                                                <td style="text-align:center;">{{$f['desabastecido']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_desabastecido'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_desabastecido'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_desabastecido'] > 30)
                                                    <td style="text-align:center;">{{$f['desabastecido']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_desabastecido'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_desabastecido'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_existencia'] > 90)
                                            <td style="text-align:center;">{!! $f['existencia'] !!}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_existencia'], 2, '.', ',') !!}</td>
                                            <td style="text-align:center;">{!! number_format($f['punt_existencia'], 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_existencia'] >= 70 && $f['por_existencia'] <= 90)
                                                <td style="text-align:center;">{!! $f['existencia'] !!}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_existencia'], 2, '.', ',') !!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_existencia'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_existencia'] < 70)
                                                    <td style="text-align:center;">{!! $f['existencia'] !!}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_existencia'], 2, '.', ',') !!}</td>
                                                    @if($f['por_existencia'] == 0)
                                                        <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                                    @else
                                                        <td style="text-align:center;">{!! number_format($f['punt_existencia'], 2, '.', ',') !!}</td>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                        @if($f['por_disponibilidad'] > 90)                            
                                            <td style="text-align:center;">{!! $f['disponibilidad'] !!}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_disponibilidad'], 2, '.', ',')!!}</td>
                                            @if($f['por_disponibilidad'] == 100)
                                                <td style="text-align:center;">{!! number_format(2, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_disponibilidad'], 2, '.', ',') !!}</td>
                                            @endif                            
                                        @else
                                            @if($f['por_disponibilidad'] >= 70 && $f['por_disponibilidad'] <= 90)
                                                <td style="text-align:center;">{!! $f['disponibilidad'] !!}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_disponibilidad'], 2, '.', ',') !!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_disponibilidad'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_disponibilidad'] < 70)
                                                    <td style="text-align:center;">{!! $f['disponibilidad'] !!}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_disponibilidad'], 2, '.', ',') !!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_disponibilidad'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif
                                        
                                        <td class="text-center">
                                            <a class="btn btn-success btn-xs" title="Ver Reporte ICI de la Farmacia" href="javascript:void(0)" onclick="reporteProductos(<?php echo $f['id_establecimiento']; ?> ,3)" >
                                                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                    
                    <!--fin tab 01 -->
                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <!--- Aqui agregue tab --->
                        <div class="panel-body" style="display: block;">
                            <input type="text" class="form-control" id="dev-table-filter_2" data-action="filter" data-filters="#dev-table_2" placeholder="Buscar Farmacia" />
                        </div>
                        <div class="panel-body" style="display: block;">
                            <table class="table table-hover" id="dev-table_2" style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Farmacia</th>
                                        <th rowspan="2">Total</th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#70AD47"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">NormoStock</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#FFC000"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">SubStock</div>
                                            </div>
                                        </th>                                        
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#ED7D31"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">SobreStock</div>
                                            </div>
                                        </th>                                        
                                        <th colspan="3" align="center" style="padding:0px;margin:0px;width:120px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#4472C4"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Sin Rotaci&oacute;n</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#FF0000"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Desabastecido</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#d81b60"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Existencia</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#00c0ef"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Disponibilidad</div>
                                            </div>
                                        </th>
                                        <th rowspan="2" class="text-center">Reporte ICI</th>
                                    </tr>
                                    <tr>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n = 0 ?>
                                    @foreach($listado as $f)
                                    <tr>
                                        <td><?php $n++;
                                    echo $n ?></td>
                                        <td>{{$f['nombre']}}</td>
                                        <td>{{$f['total_medicamentos']}}</td>
                                        
                                        @if($f['por_normoStock_medicamentos'] > 80)                         
                                            <td style="text-align:center;">{{$f['normoStock_medicamentos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_normoStock_medicamentos'], 2, '.', ',')!!}</td>
                                            <td style="text-align:center;">{!! number_format($f['punt_normoStock_medicamentos'], 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_normoStock_medicamentos'] >= 60 && $f['por_normoStock_medicamentos'] <= 80)
                                                <td style="text-align:center;">{{$f['normoStock_medicamentos']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_normoStock_medicamentos'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_normoStock_medicamentos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_normoStock_medicamentos'] < 60)
                                                    <td style="text-align:center;">{{$f['normoStock_medicamentos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_normoStock_medicamentos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_normoStock_medicamentos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_subStock_medicamentos'] < 10)                            
                                            <td style="text-align:center;">{{$f['subStock_medicamentos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_subStock_medicamentos'], 2, '.', ',') !!}</td>
                                            @if($f['por_subStock_medicamentos'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_subStock_medicamentos'], 2, '.', ',') !!}</td>
                                            @endif
                                        @else
                                            @if($f['por_subStock_medicamentos'] >= 10 && $f['por_subStock_medicamentos'] <= 20)
                                                <td style="text-align:center;">{{$f['subStock_medicamentos']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_subStock_medicamentos'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_subStock_medicamentos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_subStock_medicamentos'] > 20)
                                                    <td style="text-align:center;">{{$f['subStock_medicamentos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_subStock_medicamentos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_subStock_medicamentos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif
                                    
                                        @if($f['por_sobreStock_medicamentos'] < 10)                            
                                            <td style="text-align:center;">{{$f['sobreStock_medicamentos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sobreStock_medicamentos'], 2, '.', ',')!!}</td>
                                            @if($f['por_sobreStock_medicamentos'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_sobreStock_medicamentos'], 2, '.', ',') !!}</td>
                                            @endif
                                        @else
                                            @if($f['por_sobreStock_medicamentos'] >= 10 && $f['por_sobreStock_medicamentos'] <= 20)
                                                <td style="text-align:center;">{{$f['sobreStock_medicamentos']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_sobreStock_medicamentos'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_sobreStock_medicamentos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_sobreStock_medicamentos'] > 20)
                                                    <td style="text-align:center;">{{$f['sobreStock_medicamentos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_sobreStock_medicamentos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sobreStock_medicamentos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif    

                                        @if($f['por_sinRotacion_medicamentos'] == 0)                            
                                            <td style="text-align:center;">{{$f['sinRotacion_medicamentos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sinRotacion_medicamentos'], 2, '.', ',')!!}</td>
                                            <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_sinRotacion_medicamentos'] > 0 && $f['por_sinRotacion_medicamentos'] < 10)
                                                @if($f['por_sinRotacion_medicamentos'] < 1)
                                                    <td style="text-align:center;">{{$f['sinRotacion_medicamentos']}}</td>
                                                    <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sinRotacion_medicamentos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion_medicamentos'], 2, '.', ',') !!}</td>
                                                @else 
                                                    <td style="text-align:center;">{{$f['sinRotacion_medicamentos']}}</td>
                                                    <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_sinRotacion_medicamentos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion_medicamentos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @else
                                                @if($f['por_sinRotacion_medicamentos'] >= 10)
                                                    <td style="text-align:center;">{{$f['sinRotacion_medicamentos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_sinRotacion_medicamentos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion_medicamentos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_desabastecido_medicamentos'] <= 10)                            
                                            <td style="text-align:center;">{{$f['desabastecido_medicamentos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_desabastecido_medicamentos'], 2, '.', ',')!!}</td>
                                            @if($f['por_desabastecido_medicamentos'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(2, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_desabastecido_medicamentos'], 2, '.', ',') !!}</td>
                                            @endif    
                                        @else
                                            @if($f['por_desabastecido_medicamentos'] > 10 && $f['por_desabastecido_medicamentos'] <= 30)
                                                <td style="text-align:center;">{{$f['desabastecido_medicamentos']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_desabastecido_medicamentos'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_desabastecido_medicamentos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_desabastecido_medicamentos'] > 30)
                                                    <td style="text-align:center;">{{$f['desabastecido_medicamentos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_desabastecido_medicamentos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_desabastecido_medicamentos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_existencia_medicamentos'] > 90)
                                            <td style="text-align:center;">{!! $f['existencia_medicamentos'] !!}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_existencia_medicamentos'], 2, '.', ',') !!}</td>
                                            <td style="text-align:center;">{!! number_format($f['punt_existencia_medicamentos'], 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_existencia_medicamentos'] >= 70 && $f['por_existencia_medicamentos'] <= 90)
                                                <td style="text-align:center;">{!! $f['existencia_medicamentos'] !!}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_existencia_medicamentos'], 2, '.', ',') !!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_existencia_medicamentos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_existencia_medicamentos'] < 70)
                                                    <td style="text-align:center;">{!! $f['existencia_medicamentos'] !!}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_existencia_medicamentos'], 2, '.', ',') !!}</td>
                                                    @if($f['por_existencia_medicamentos'] == 0)
                                                        <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                                    @else
                                                        <td style="text-align:center;">{!! number_format($f['punt_existencia_medicamentos'], 2, '.', ',') !!}</td>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                        @if($f['por_disponibilidad_medicamentos'] > 90)                            
                                            <td style="text-align:center;">{!! $f['disponibilidad_medicamentos'] !!}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_disponibilidad_medicamentos'], 2, '.', ',')!!}</td>
                                            @if($f['por_disponibilidad_medicamentos'] == 100)
                                                <td style="text-align:center;">{!! number_format(2, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_disponibilidad_medicamentos'], 2, '.', ',') !!}</td>
                                            @endif                            
                                        @else
                                            @if($f['por_disponibilidad_medicamentos'] >= 70 && $f['por_disponibilidad_medicamentos'] <= 90)
                                                <td style="text-align:center;">{!! $f['disponibilidad_medicamentos'] !!}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_disponibilidad_medicamentos'], 2, '.', ',') !!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_disponibilidad_medicamentos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_disponibilidad_medicamentos'] < 70)
                                                    <td style="text-align:center;">{!! $f['disponibilidad_medicamentos'] !!}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_disponibilidad_medicamentos'], 2, '.', ',') !!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_disponibilidad_medicamentos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif
                                        <td class="text-center">
                                            <a class="btn btn-success btn-xs" title="Ver Reporte ICI de la Farmacia" href="javascript:void(0)" onclick="reporteProductos(<?php echo $f['id_establecimiento']; ?> ,1)" >
                                                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <!--- Aqui agregue tab --->
                        <div class="panel-body" style="display: block;">
                            <input type="text" class="form-control" id="dev-table-filter_3" data-action="filter" data-filters="#dev-table_3" placeholder="Buscar Farmacia" />
                        </div>
                        <div class="panel-body" style="display: block;">
                            <table class="table table-hover" id="dev-table_3" style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Farmacia</th>
                                        <th rowspan="2">Total</th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#70AD47"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">NormoStock</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#FFC000"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">SubStock</div>
                                            </div>
                                        </th>                                        
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#ED7D31"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">SobreStock</div>
                                            </div>
                                        </th>                                        
                                        <th colspan="3" align="center" style="padding:0px;margin:0px;width:120px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#4472C4"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Sin Rotaci&oacute;n</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#FF0000"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Desabastecido</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#d81b60"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Existencia</div>
                                            </div>
                                        </th>
                                        <th colspan="3" align="center" style="padding:0px;margin:0px">
                                            <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                                <i class="fa fa-flag fa-2x" style="color:#00c0ef"></i>
                                                <br />
                                                <div style="color:#333333;text-align:center;font-size:11px">Disponibilidad</div>
                                            </div>
                                        </th>
                                        <th rowspan="2" class="text-center">Reporte ICI</th>
                                    </tr>
                                    <tr>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                        <th>Cant.</th>
                                        <th> % </th>
                                        <th>Punt.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n = 0 ?>
                                    @foreach($listado as $f)
                                    <tr>
                                        <td><?php $n++;
                                    echo $n ?></td>
                                        <td>{{$f['nombre']}}</td>
                                        <td>{{$f['total_dispositivos']}}</td>
                                        
                                        @if($f['por_normoStock_dispositivos'] > 80)     
                                            <td style="text-align:center;">{{$f['normoStock_dispositivos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_normoStock_dispositivos'], 2, '.', ',')!!}</td>
                                            <td style="text-align:center;">{!! number_format($f['punt_normoStock_dispositivos'], 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_normoStock_dispositivos'] >= 60 && $f['por_normoStock_dispositivos'] <= 80)
                                                <td style="text-align:center;">{{$f['normoStock_dispositivos']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_normoStock_dispositivos'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_normoStock_dispositivos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_normoStock_dispositivos'] < 60)
                                                    <td style="text-align:center;">{{$f['normoStock_dispositivos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_normoStock_dispositivos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_normoStock_dispositivos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_subStock_dispositivos'] < 10)                            
                                            <td style="text-align:center;">{{$f['subStock_dispositivos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_subStock_dispositivos'], 2, '.', ',') !!}</td>
                                            @if($f['por_subStock_dispositivos'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_subStock_dispositivos'], 2, '.', ',') !!}</td>
                                            @endif
                                        @else
                                            @if($f['por_subStock_dispositivos'] >= 10 && $f['por_subStock_dispositivos'] <= 20)
                                                <td style="text-align:center;">{{$f['subStock_dispositivos']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_subStock_dispositivos'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_subStock_dispositivos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_subStock_dispositivos'] > 20)
                                                    <td style="text-align:center;">{{$f['subStock_dispositivos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_subStock_dispositivos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_subStock_dispositivos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_sobreStock_dispositivos'] < 10)                            
                                            <td style="text-align:center;">{{$f['sobreStock_dispositivos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sobreStock_dispositivos'], 2, '.', ',')!!}</td>
                                            @if($f['por_sobreStock_dispositivos'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_sobreStock_dispositivos'], 2, '.', ',') !!}</td>
                                            @endif
                                        @else
                                            @if($f['por_sobreStock_dispositivos'] >= 10 && $f['por_sobreStock_dispositivos'] <= 20)
                                                <td style="text-align:center;">{{$f['sobreStock_dispositivos']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_sobreStock_dispositivos'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_sobreStock_dispositivos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_sobreStock_dispositivos'] > 20)
                                                    <td style="text-align:center;">{{$f['sobreStock_dispositivos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_sobreStock_dispositivos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sobreStock_dispositivos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif    
                                    
                                        @if($f['por_sinRotacion_dispositivos'] == 0)                            
                                            <td style="text-align:center;">{{$f['sinRotacion_dispositivos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sinRotacion_dispositivos'], 2, '.', ',')!!}</td>
                                            <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_sinRotacion_dispositivos'] > 0 && $f['por_sinRotacion_dispositivos'] < 10)
                                                @if($f['por_sinRotacion_dispositivos'] < 1)
                                                    <td style="text-align:center;">{{$f['sinRotacion_dispositivos']}}</td>
                                                    <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_sinRotacion_dispositivos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion_dispositivos'], 2, '.', ',') !!}</td>
                                                @else 
                                                    <td style="text-align:center;">{{$f['sinRotacion_dispositivos']}}</td>
                                                    <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_sinRotacion_dispositivos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion_dispositivos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @else
                                                @if($f['por_sinRotacion_dispositivos'] >= 10)
                                                    <td style="text-align:center;">{{$f['sinRotacion_dispositivos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_sinRotacion_dispositivos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_sinRotacion_dispositivos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_desabastecido_dispositivos'] <= 10)                            
                                            <td style="text-align:center;">{{$f['desabastecido_dispositivos']}}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_desabastecido_dispositivos'], 2, '.', ',')!!}</td>
                                            @if($f['por_desabastecido_dispositivos'] == 0)                            
                                                <td style="text-align:center;">{!! number_format(2, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_desabastecido_dispositivos'], 2, '.', ',') !!}</td>
                                            @endif    
                                        @else
                                            @if($f['por_desabastecido_dispositivos'] > 10 && $f['por_desabastecido_dispositivos'] <= 30)
                                                <td style="text-align:center;">{{$f['desabastecido_dispositivos']}}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_desabastecido_dispositivos'], 2, '.', ',')!!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_desabastecido_dispositivos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_desabastecido_dispositivos'] > 30)
                                                    <td style="text-align:center;">{{$f['desabastecido_dispositivos']}}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_desabastecido_dispositivos'], 2, '.', ',')!!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_desabastecido_dispositivos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif

                                        @if($f['por_existencia_dispositivos'] > 90)
                                            <td style="text-align:center;">{!! $f['existencia_dispositivos'] !!}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_existencia_dispositivos'], 2, '.', ',') !!}</td>
                                            <td style="text-align:center;">{!! number_format($f['punt_existencia_dispositivos'], 2, '.', ',') !!}</td>
                                        @else
                                            @if($f['por_existencia_dispositivos'] >= 70 && $f['por_existencia_dispositivos'] <= 90)
                                                <td style="text-align:center;">{!! $f['existencia_dispositivos'] !!}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_existencia_dispositivos'], 2, '.', ',') !!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_existencia_dispositivos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_existencia_dispositivos'] < 70)
                                                    <td style="text-align:center;">{!! $f['existencia_dispositivos'] !!}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_existencia_dispositivos'], 2, '.', ',') !!}</td>
                                                    @if($f['por_existencia_dispositivos'] == 0)
                                                        <td style="text-align:center;">{!! number_format(1, 2, '.', ',') !!}</td>
                                                    @else
                                                        <td style="text-align:center;">{!! number_format($f['punt_existencia_dispositivos'], 2, '.', ',') !!}</td>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                        @if($f['por_disponibilidad_dispositivos'] > 90)                            
                                            <td style="text-align:center;">{!! $f['disponibilidad_dispositivos'] !!}</td>
                                            <td bgcolor="#4B8A08" style="text-align:center;">{!! number_format($f['por_disponibilidad_dispositivos'], 2, '.', ',')!!}</td>
                                            @if($f['por_disponibilidad_dispositivos'] == 100)
                                                <td style="text-align:center;">{!! number_format(2, 2, '.', ',') !!}</td>
                                            @else
                                                <td style="text-align:center;">{!! number_format($f['punt_disponibilidad_dispositivos'], 2, '.', ',') !!}</td>
                                            @endif                            
                                        @else
                                            @if($f['por_disponibilidad_dispositivos'] >= 70 && $f['por_disponibilidad_dispositivos'] <= 90)
                                                <td style="text-align:center;">{!! $f['disponibilidad_dispositivos'] !!}</td>
                                                <td bgcolor="#FFFF00" style="text-align:center;">{!! number_format($f['por_disponibilidad_dispositivos'], 2, '.', ',') !!}</td>
                                                <td style="text-align:center;">{!! number_format($f['punt_disponibilidad_dispositivos'], 2, '.', ',') !!}</td>
                                            @else
                                                @if($f['por_disponibilidad_dispositivos'] < 70)
                                                    <td style="text-align:center;">{!! $f['disponibilidad_dispositivos'] !!}</td>
                                                    <td bgcolor="#FE2E2E" style="text-align:center;">{!! number_format($f['por_disponibilidad_dispositivos'], 2, '.', ',') !!}</td>
                                                    <td style="text-align:center;">{!! number_format($f['punt_disponibilidad_dispositivos'], 2, '.', ',') !!}</td>
                                                @endif
                                            @endif
                                        @endif
                                        
                                        <td class="text-center">
                                            <a class="btn btn-success btn-xs" title="Ver Reporte ICI de la Farmacia" href="javascript:void(0)" onclick="reporteProductos(<?php echo $f['id_establecimiento']; ?> ,1)" >
                                                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
