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


    div.panel:first-child {
        margin-top:20px;
    }

    div.treeview {
        min-width: 100px;
        min-height: 100px;

        max-height: 256px;
        overflow:auto;

        padding: 4px;

        margin-bottom: 20px;

        color: #369;

        border: solid 1px;
        border-radius: 4px;
    }
    div.treeview ul:first-child:before {
        display: none;
    }
    .treeview, .treeview ul {
        margin:0;
        padding:0;
        list-style:none;

        color: #369;
    }
    .treeview ul {
        margin-left:1em;
        position:relative
    }
    .treeview ul ul {
        margin-left:.5em
    }
    .treeview ul:before {
        content:"";
        display:block;
        width:0;
        position:absolute;
        top:0;
        left:0;
        border-left:1px solid;

        /* creates a more theme-ready standard for the bootstrap themes */
        bottom:15px;
    }
    .treeview li {
        margin:0;
        padding:0 1em;
        line-height:2em;
        font-weight:700;
        position:relative
    }
    .treeview ul li:before {
        content:"";
        display:block;
        width:10px;
        height:0;
        border-top:1px solid;
        margin-top:-1px;
        position:absolute;
        top:1em;
        left:0
    }
    .tree-indicator {
        margin-right:5px;

        cursor:pointer;
    }
    .treeview li a {
        text-decoration: none;
        color:inherit;

        cursor:pointer;
    }
    .treeview li button, .treeview li button:active, .treeview li button:focus {
        text-decoration: none;
        color:inherit;
        border:none;
        background:transparent;
        margin:0px 0px 0px 0px;
        padding:0px 0px 0px 0px;
        outline: 0;
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


    $.fn.extend({
        treeview: function () {
            return this.each(function () {
                // Initialize the top levels;
                var tree = $(this);

                tree.addClass('treeview-tree');
                tree.find('li').each(function () {
                    var stick = $(this);
                });
                tree.find('li').has("ul").each(function () {
                    var branch = $(this); //li with children ul

                    branch.prepend("<i class='tree-indicator glyphicon glyphicon-chevron-right'></i>");
                    branch.addClass('tree-branch');
                    branch.on('click', function (e) {
                        if (this == e.target) {
                            var icon = $(this).children('i:first');

                            icon.toggleClass("glyphicon-chevron-down glyphicon-chevron-right");
                            $(this).children().children().toggle();
                        }
                    })
                    branch.children().children().toggle();

                    /**
                     *	The following snippet of code enables the treeview to
                     *	function when a button, indicator or anchor is clicked.
                     *
                     *	It also prevents the default function of an anchor and
                     *	a button from firing.
                     */
                    branch.children('.tree-indicator, button, a').click(function (e) {
                        branch.click();

                        e.preventDefault();
                    });
                });
            });
        }
    });

    /**
     *	The following snippet of code automatically converst
     *	any '.treeview' DOM elements into a treeview component.
     */
    $(window).on('load', function () {
        $('.treeview').each(function () {
            var tree = $(this);
            tree.treeview();
        })
    })
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Reportes</h3>
                    <div class="pull-right">
                        <a  style="color: green; font-weight: bold" class="btn btn-default btn-xs" href="/tabular_stockgeneral_por_tipo/6">
                            <i class="fa fa-table" aria-hidden="true"></i> Material Fotográfico y Fonotécnico
                        </a>
                        <a  style="color: green; font-weight: bold" class="btn btn-default btn-xs" href="/tabular_stockgeneral_por_tipo/5">
                            <i class="fa fa-table" aria-hidden="true"></i> Material Dental
                        </a>
                        <a  style="color: green; font-weight: bold" class="btn btn-default btn-xs" href="/tabular_stockgeneral_por_tipo/2">
                            <i class="fa fa-table" aria-hidden="true"></i> Insumos de laboratorio
                        </a>
                        <a  style="color: green; font-weight: bold" class="btn btn-default btn-xs" href="/tabular_stockgeneral_por_tipo/4">
                            <i class="fa fa-table" aria-hidden="true"></i> Material de laboratorio
                        </a>
                        <a  style="color: green; font-weight: bold" class="btn btn-default btn-xs" href="/tabular_stockgeneral_por_tipo/1">
                            <i class="fa fa-table" aria-hidden="true"></i> Medicinas
                        </a>
                        <a  style="color: green; font-weight: bold" class="btn btn-default btn-xs" href="/tabular_stockgeneral_por_tipo/3">
                            <i class="fa fa-table" aria-hidden="true"></i> Biomédicos
                        </a>
                        <a  style="color: green; font-weight: bold" class="btn btn-default btn-xs" href="/tabular_stockgeneral_ipress">
                            <i class="fa fa-table" aria-hidden="true"></i> Reporte General
                        </a>
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body" style="font-size:12px">
                            <i class="glyphicon glyphicon-filter"></i> Buscar
                        </span>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers" />
                </div>
                <legend style="margin-left: 5px">Stock General - Farmacias</legend>
                <table class="table table-hover" id="dev-table" style="font-size: 12px">
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Producto Farmacéutico y Dispositivos Médicos</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 1; $m=1; ?>
                        @foreach($resultados as $r)
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td>

                                <ul class="treeview">
                                    <li><a href="#">{{$r['codigo']}} - {{$r['nombre']}} - {{$r['unidad']}}</a>
                                        <ul>
                                            @foreach($r['cantidades'] as $c) 
                                            <li>
                                                <?php echo $m++; ?>.
                                                {{$c['farma']}} - 
                                                @if($c['cant']<=0)
                                                <span style="color:red">({{$c['cant']}})</span>
                                                @else
                                                <span style="color:green">({{$c['cant']}})</span>
                                                @endif
                                            </li> 
                                            @endforeach 
                                            <?php $m=1; ?>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                            <td>{{$r['cantidad']}}</td>

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
