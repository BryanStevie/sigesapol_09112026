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
<script src='../../funciones/function.js'></script> 
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
    @if($nombre_servicio!='')
        <h4>SERVICIO DE {{$nombre_servicio}}</h4>
    @else
        <h4>{{$farmacia}}</h4>
    @endif
            
    <div class="panel-body">
        <div class="pull-right" style="margin-bottom:10px"> 
        <?php if ($permiso->crear == 1): ?>
            <a data-toggle="modal" data-target="#squarespaceModal" class="btn btn-xs btn-default">
                <i class="fa fa-plus"></i> Generar Requerimiento
            </a>

        <?php endif; ?>
        
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Lista de Requerimientos
                    </h3>
                  <!--
                    <div class="pull-right" style="margin-bottom:10px">                        
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                            Filtrar <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    
                    </div>
                -->
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
                                    <th>Creado el</th>
                                    <th>Enviado el</th> 
                                    <th>Estado</th>
                                    <th>Descargar</th>
                                    <th>Ver detalle</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 0; ?>
                                @foreach($requerimientos as $r)
                                <tr>                            
                                    <td>
                                        <?php
                                        $n++;
                                        echo $n;
                                        ?>                              
                                    </td>
                                    <td>{{$r->nom_destino}}</td>
                                    <td class="text-center"><?php $array = explode("-", $r->numero);
                                                                    echo $array[2].'-'.$array[3]; ?></td>
                                    <td>{{$r->documento}}</td>
                                    <td>
                                        <?php
                                        $originalDate2 = $r->created_at;
                                        $newDate2 = date("d-m-Y", strtotime($originalDate2));
                                        echo $newDate2;
                                        ?> 
                                    </td>
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
                                    @if($r->estado=="1")
                                    <td>
                                        Agregando productos
                                        <i class="fa fa-folder-open-o" aria-hidden="true"></i>

                                    </td>
                                    @else
                                    <td>
                                        Enviado al almacen
                                        <i class="fa fa-check"></i>
                                    </td>
                                    @endif
                                    <!-------------------------------------------------->                                     
                                    <td class="text-center">
                                        <!--<a href="/imprimir_requerimientos/{{$usuario}}/{{$r->id}}" class="btn btn-success btn-xs"><i class="fa fa-file-excel-o "></i></a>-->
                                        <a href="/imprimir_requerimiento_pdf/{{$r->id}}" class="btn btn-danger btn-xs" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>                            
                                    <td class="text-center">
                                        <?php if ($r->estado == "1"): ?>
                                            <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                <a href="/ver_productos_del_pedido/{{$r->id}}/{{$r->tipo_producto_egestion}}/{{ Auth::user()->id_farmacia }}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="/ver_productos_del_pedido/{{$r->id}}/{{$r->tipo_producto_egestion}}/{{ Auth::user()->id_farmacia }}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>		
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($permiso->eliminar == 1): ?>
                                            @if($r->estado=="2")                                
                                            <button type="submit" disabled="" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                            @else
                                            <a href="/eliminar_pedido/{{$r->id}}/{{ Auth::user()->id }}" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
                                            @endif
                                        <?php endif; ?>
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


<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="lineModalLabel">Generar Requerimiento</h4>
            </div>
            <div class="modal-body">
                <!-- content goes here -->
                <form class="" method="post" action="{{ route('registrar_pedido')}}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group" style="margin-bottom: 8px">
                        <label>Lugar de Destino</label>
                        <select name="id_destino" id="id_destino" class="form-control" required="">
                            <option value="">-Seleccionar-</option>
                            @foreach($destino as $d)
                            <option value="{{$d->id_farmacia}}">{{$d->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 8px">
                        <label>Tipo de Producto</label>
                        <select name="tipo_producto_egestion" class="form-control" required="">
                            <option value="">-Seleccionar-</option>
                            @foreach($tipo_productos as $tp)
                            <option value="{{$tp->id_egestion}}">{{$tp->nombre}}</option>
                            @endforeach
                        </select>                        
                        <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{ Auth::user()->id_farmacia}}">
                        <input type="hidden" class="form-control" id="id_consultorio" name="id_consultorio" value="{{ Auth::user()->id_consultorio}}">
                        <input type="hidden" class="form-control" id="id_establecimiento" name="id_establecimiento" value="{{ Auth::user()->id_establecimiento}}">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id}}">
                    </div>   
                    <div class="form-group" style="margin-bottom: 8px">
                        <label>Documento</label>
                        <input type="text" class="form-control" id="documento" name="documento" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'documento');" value="" required="">                                                
                    </div> 
                    <div class="form-group" style="text-align:right; ">
                        <input id="btnInvalidar" class="btn btn-primary" type="submit" value="Continuar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
