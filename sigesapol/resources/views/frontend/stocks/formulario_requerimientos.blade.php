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
    $("#btnReg").on("click", function () {
        if (confirm("¿Desea Grabar?") == true) {
            $("#frmRequerimiento").submit();
        }
    });
    //--------------------------------------------------------------------------
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
                                var no_results = $('<tr class="filterTable_no_results"><td colspan="' + col_count + '">No hay resultados</td></tr>')
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
    @if($aviso==1)
    <form method="post" action="{{ route('registrar_requerimiento')}}" id='frmRequerimiento'>
        <h3>{{$nombre_farmacia}}</h3>
        <div class="col-md-12" style="margin-bottom:5px">
            ¿Estas seguro de registrar este requerimiento?
        </div>
        <div class="col-md-2" style="margin-bottom:15px">
            <button class="btn btn-success form-control" id='btnReg'> Si </button>                
        </div>
        <div class="col-md-2" style="margin-bottom:15px">
            <a href="/form_generar_rerporte_ici/{{ Auth::user()->id }}" class="btn btn-danger form-control"> No </a>                
        </div>

        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php $n = $contador + 1; ?>
                            Nro. Requerimiento: REQ-{{$establecimiento->codigo}}-<?php echo $n ?>
                            <input value="REQ-{{$establecimiento->codigo}}-<?php echo $n ?>" name="numero" id='numero' type="hidden">
                            <input value="{{$nombre_farmacia}}" name="nombre_farmacia" id='nombre_farmacia' type="hidden">                            
                            <input value="<?php echo $n ?>" name="contador" id='contador' type="hidden">
                            <input value="{{$id_farmacia}}" name="id_farmacia" id='id_farmacia' type="hidden">
                            <input value="{{ Auth::user()->id }}" name="id_user" id='id_user' type="hidden">
                        </h3>
                        <div class="pull-right">
                            Fecha: {{$fecha}}
                            <input value="{{$fecha}}" name="fecha" id='fecha' type="hidden">
                            <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                                <i class="glyphicon glyphicon-filter"></i>
                            </span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar" />
                    </div>
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Código</th>
                                <th>Medicamento</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 0; ?>
                            @foreach($listado as $l)
                            <tr>
                                <td><?php
                                    $n++;
                                    echo $n;
                                    ?></td>
                                <td>{{$l['codigo']}}</td>
                                <td>{{$l['nombre']}}</td>
                                <td>{{$l['unidad']}}</td>
                                <td>
                                    <input value="{{$l['cantidad']}}" name="cantidad_solicitada[]" id="cantidad_solicitada" style="width: 65px" type="text" autofocus="">
                                    <input value="{{$l['codigo']}}" name="codigo_producto[]" id="codigo_producto" type="hidden">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>        
    </form>
    @else
    <a class="btn btn-default btn-xs" href="/formulario_solicitar_requerimiento/{{ Auth::user()->id }}"><i class="fa fa-list"></i> Generar requerimientos</a>
    <a class="btn btn-default btn-xs" href="/stocksbyFamarcia/{{ Auth::user()->id }}"><i class="fa fa-list"></i> Stock Actual</a>
    <a class="btn btn-default btn-xs" href="/form_generar_rerporte_ici/{{ Auth::user()->id }}"><i class="fa fa-search"></i> Reporte ICI</a>     
    @endif
</div>
@include('frontend.includes.footer')
@endsection
