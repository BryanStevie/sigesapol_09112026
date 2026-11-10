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
    #squarespaceModal .modal-dialog{
        width: 1200px;
    }
    #global {
        height: 640px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;
    }
</style>
<script>
    $(document).ready(function () {
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            /* Useful DOM data and selectors */
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
            }
        });


    });

</script>

@endsection
@section('content')

<div class="container" style="width:100%">
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">Receta Dispensadas Por Producto - {{$nomproducto}}</h3>
                    <div class="pull-right">
                        <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtar</button>
                        <a class="btn btn-default btn-xs" href="/otros_reportes_by_farma/15">
                            <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar el Producto" />
                </div>
                <div id="global">
                <table id="example" class="table" cellspacing="0" width="100%" style="font-size: 11px">
                    <thead>
                        <tr class="filters">
                            <th>#</th>
                            <th>Fecha Dispensación</th>
                            <th>Nro. Receta</th>
                            <th>Tipo de documento</th>
                            <th>N° de documento</th>
                            <th>H.Clínica</th>
                            <th>Beneficiario</th>
                            <th><input type="text" class="form-control input-sm" placeholder="Médico" disabled></th>
                            <th><input type="text" class="form-control input-sm" placeholder="Servicio" disabled></th>
                            <th><input type="text" class="form-control input-sm" placeholder="Diagnosticos" disabled></th>
                            <th><input type="text" class="form-control input-sm" placeholder="Digitador" disabled></th>
                            <th>Cant. Dispensada</th>
                        </tr>
                    </thead>
                    @if(count($resultado)>0)
                    <tbody>
                        <?php $n = 0 ?>
                        @foreach($resultado as $f)
                        <tr>
                            <td><?php
                                $n++;
                                echo $n
                                ?></td>
                            <td>{{$f->_fecha}}</td>
                            <td>{{$f->_nroreceta}}</td>
                            <td>{{$f->_tipdocbene}}</td>
                            <td>{{$f->_nrodocbene}}</td>
                            <td>{{$f->_nrohcbene}}</td>
                            <td>{{$f->_beneficiario}}</td>
                            <td>{{$f->_medico}}</td>
                            <td>{{$f->_servicio}}</td>
                            <td>{{$f->_diagnostico}}</td>
                            <td>{{$f->_usuario}}</td>
                            <td class="text-center">
                                @if($f->_presentacion==1)
                                    {{$f->_cantdispensanda}} X {{$f->_presentacion_comercial}}
                                @else
                                  @if($id_producto==11045 || $id_producto==15938 || $id_producto==14607 || $id_producto==14608)
                                    <?php echo $f->_cantdispensanda/1000; ?> 
                                  @else
                                    {{$f->_cantdispensanda}}
                                  @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
                <div style="padding-bottom: 10px;"></div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                <div class="panel-body"  style="display: block;">
                    @if(count($resultado)>0)
                    <div class="col-md-12" style="margin-bottom: 10px">Recetas con Producto seleccionado: <?php echo $n ?></div>                    
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a class="btn btn-success form-control" href="/exportar_reporte_recetas_by_producto/{{$id_farmacia}}/{{$id_producto}}/{{$fecha_inicio}}/{{$fecha_fin}}">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>                        
                    </div>
                    <!--<div class="col-md-12" style="margin-bottom: 10px">
                          <a class="btn btn-default btn-xs" data-toggle="modal" data-target="#squarespaceModal"  href="">Ver historial de diagnosticos</a>
                    </div>-->
                    @else                                         
                    <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                    </div>
                    @endif
                </div>                
            </div>
        </div>
    </div>
</div>


@endsection