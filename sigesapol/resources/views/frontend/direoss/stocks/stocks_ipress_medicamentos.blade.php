@extends('frontend.layouts.master')
@section('renderjs')

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

        $('.container-fluid').on('click', '.panel-heading span.filter', function (e) {
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
        display: block;
    }
    .texto-vertical-2 {
        transform: rotate(180deg) !important;
        writing-mode: vertical-lr !important;

    }
    #global {
        height: 580px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;               
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Stock de Medicamentos de las IPRESS PNP</h3>
                    <div class="pull-right">
                    </div>
                </div>
                <div class="panel-body" style="">
                    <input type="text" class="" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" style="width:300px" />
                </div>
                <div id="global">
                <table class="table table-hover" id="dev-table" style="font-size:11px; table-layout: fixed;" border="1px">
                    <thead style="background-color: #CCCCCC; color:black; ">
                        <tr>
                            <th style="font-size:15px">Codigo</th>
                            <th style="font-size:15px">Producto</th>
                            <th style="font-size:15px">Unidad</th>
                            <th style="width:70px">Total</th>
                            @foreach($farmacias as $f)
                            <th class="texto-vertical-2" style="width:40px">{{$f->ipress}}</th>
                            @endforeach                            
                        </tr>
                    </thead>
                    <tbody >
                        @foreach($resultados as $r)
                        <tr>
                            <td>{{$r['codigo']}}</td>
                            <td>{{$r['nombre']}}</td>
                            <td>{{$r['unidad']}}</td>
                            <td style="font-weight: bold; font-size:13px;">{{$r['cantidad']}}</td>
                            @foreach($r['cantidades'] as $c)
                                @if($c['cant']>0)
                                    <td><span style="color:green">{{$c['cant']}}</span></td>
                                @else
                                    <td><span style="color:red">{{$c['cant']}}</span></td>
                                @endif                            
                            @endforeach                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
