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
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">Monitoreo de Centros de Atenci&oacuten</h3>
                    <div class="pull-right">
                        <a class="btn btn-default btn-xs" href="/all_usuarios"><i class="fa fa-users"></i> Personal Farmacéutico</a>
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                            <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    </div>
                </div>

                <?php
                $menu = App\Models\Menu::getMenuByUsuario(Auth::user()->id, 'otros_reportes_by_farma');
                //print_r($menu);
                ?>

                <div class="panel-body" style="display: block;">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar Farmacia" />
                </div>
                <table class="table table-hover" id="dev-table" style="font-size: 12px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Farmacia</th>
                            <th>Total</th>

                            <th align="center" style="padding:0px;margin:0px">
                                <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>No hay disponibilidad del medicamento (Afecta directamente a nuestros asegurados).</em><br><b>Saldo Final = 0</b>">
                                    <i class="fa fa-flag fa-2x" style="color:#FF0000"></i>
                                    <br />
                                    <div style="color:#333333;text-align:center;font-size:11px">Desabastecido</div>
                                </div>
                            </th>
                            <th align="center" style="padding:0px;margin:0px">
                                <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay una disponibilidad exagerada del medicamento (Riesgo alto de pérdida por vencimiento).</em><br><b>Meses de Provisión > 6</b>">
                                    <i class="fa fa-flag fa-2x" style="color:#ED7D31"></i>
                                    <br />
                                    <div style="color:#333333;text-align:center;font-size:11px">SobreStock</div>
                                </div>
                                </td>
                            <th align="center" style="padding:0px;margin:0px">
                                <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>La disponibilidad del medicamento es adecuada.</em><br><b>2 ≤ Meses de Provisión ≤ 6</b>">
                                    <i class="fa fa-flag fa-2x" style="color:#70AD47"></i>
                                    <br />
                                    <div style="color:#333333;text-align:center;font-size:11px">NormoStock</div>
                                </div>
                            </th>
                            <th align="center" style="padding:0px;margin:0px">
                                <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>Hay escaza disponibilidad del medicamento (Riesgo de desabastecimiento en caso de retraso del proveedor o picos de demanda).</em><br><b>Meses de Provisión < 2</b>">
                                    <i class="fa fa-flag fa-2x" style="color:#FFC000"></i>
                                    <br />
                                    <div style="color:#333333;text-align:center;font-size:11px">SubStock</div>
                                </div>
                            </th>
                            <th align="center" style="padding:0px;margin:0px;width:120px">
                                <div class="col-md-10" style="text-align:center" data-toggle="tooltip" data-placement="top" data-html="true" title="<em>El medicamento no se ha distribuido en los últimos 6 meses.</em><br><b>Recetas* = 0</b>">
                                    <i class="fa fa-flag fa-2x" style="color:#4472C4"></i>
                                    <br />
                                    <div style="color:#333333;text-align:center;font-size:11px">Sin Rotaci&oacute;n</div>
                                </div>
                            </th>


                            <th>Stock</th>
                            <th class="text-center">Reporte ICI</th>
                            <!--<th>Reportes</th>-->
                            <th>Prescriptores</th>                            
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

                            <td>{{$f['desabastecido']}}</td>
                            <td>{{$f['sobreStock']}}</td>
                            <td>{{$f['normoStock']}}</td>
                            <td>{{$f['subStock']}}</td>
                            <td>{{$f['sinRotacion']}}</td>

                            <td class="text-center">
                                <a href="/stocksbyFamarcias/{{$f['id_farmacia']}}" title="Ver Stock de la Farmacia">                                
                                    <i class="fa fa-medkit fa-2x" aria-hidden="true" style="color: red"></i>
                                </a> 
                            </td>
                            <td class="text-center">
                                <a href="/form_generar_rerporte_ici_by_farma/{{$f['id_farmacia']}}" title="Ver Reporte ICI de la Farmacia" class="btn btn-success btn-xs">
                                    <i class="fa fa-list-ol fa-2x"></i>
                                </a> 
                            </td>
                            <!--<td><a href="/generar_reporte_demanda_nosatisfecha/{{$f['id_farmacia']}}" class="btn btn-success btn-xs">Demanda No Satisfecha</a> </td>-->
                            <!--<td>
                                <div class="btn-group">
                                    <i class="fa fa-list-ol fa-2x btn btn-primary dropdown-toggle" data-toggle="dropdown" style="padding:2px 2px">
                                        <span class="caret"></span>
                                    </i>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php foreach ($menu as $rowMenu): ?>
                                            <?php if ($rowMenu->lectura == '1'): ?>
                                                <li><a href="<?php echo $rowMenu->url . "/" . $f['id_farmacia'] ?>"><?php echo $rowMenu->nombremenu ?></a></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                            </td>-->
                            <td class="text-center">
                                <a href="/prescriptoresByIpress/{{$f['id_farmacia']}}" title="Ver Prescriptores">
                                    <i class="fa fa-user-md fa-2x" aria-hidden="true"></i>
                                </a>
                            </td>
                            <!--<td><a href="/otros_reportes_by_farma/{{$f['id_farmacia']}}" title="Ver Reportes Generales Versus" class="btn btn-success btn-xs">Reportes Versus</a> </td>-->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
