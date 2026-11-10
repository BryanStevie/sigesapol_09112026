@extends('frontend.layouts.master')
@section('renderjs')

<style>
    .row{
        margin-top:10px;
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
    /*.panel-body{
        display: none;
    }*/
    .table{
        font-size: 12px
    }
    @media print{

      .oculto-impresion, .oculto-impresion *{

        display: none !important;

      }

    }
</style>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
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

            $panel.find('.open-sheard').slideToggle();
            if ($this.css('display') != 'none') {
                $panel.find('.open-sheard input').focus();
            }
        });
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
@endsection
@section('content')
<div class="container">      
    <h4>{{$farmacia}}</h4>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Lista de Requerimientos Recibidos
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="open-sheard" style="display: none;">
                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar" />
                    </div>
                    <div class="table-responsive">
                        <!--table class="table table-hover" id="dev-table"-->
                        <table id="example" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Destino</th>
                                    <th>Nro. Requerimiento</th>
                                    <th>Documento</th>
                                    <th>Enviado el</th> 
                                    <th>Descargar</th>
                                    <th>Ver detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 0; ?>
                                @foreach($lista_requerimientos as $r)
                                <tr>                            
                                    <td>
                                        <?php
                                        $n++;
                                        echo $n;
                                        ?>                              
                                    </td>
                                    <td><?php if($r->id_consultorio==0){ echo $r->nom_destino; }else{ echo $r->nombre_consultorio; } ?></td>
                                    <td class="text-center">{{$r->contador}}-{{$r->anio_reque}}</td>
                                    <td>{{$r->documento}}</td>
                                    <td>
                                        <?php
                                        if ($r->fecha != "") {
                                            $originalDate2 = $r->fecha;
                                            $newDate2 = date("d-m-Y", strtotime($originalDate2));
                                            echo $newDate2;
                                        } else {
                                            echo "Pendiente";
                                        }
                                        ?> 
                                    </td>
                                    <!-------------------------------------------------->                                     
                                    <td class="text-center">
                                        <a href="/imprimir_requerimiento_pdf/{{$r->id}}" class="btn btn-danger btn-xs" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>                            
                                    <td class="text-center">
                                        <a href="/ver_productos_del_pedido_enviado/{{$r->id}}/{{$r->tipo_producto_egestion}}/{{ Auth::user()->id_farmacia }}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br/>
                </div>
            </div>
        </div>
    </div>
@include('frontend.includes.footer')
@endsection
