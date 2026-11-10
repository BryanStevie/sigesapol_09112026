@extends('frontend.layouts.master')
@section('renderjs')
    <style>
        .filterable {
            margin-top: 0px;
        }

        .filterable .panel-heading .pull-right {
            margin-top: -20px;
        }

        .filterable .filters input[disabled] {
            background-color: transparent;
            border: none;
            cursor: auto;
            box-shadow: none;
            padding: 0;
            height: auto;
        }

        .filterable .filters input[disabled]::-webkit-input-placeholder {
            color: #333;
        }

        .filterable .filters input[disabled]::-moz-placeholder {
            color: #333;
        }

        .filterable .filters input[disabled]:-ms-input-placeholder {
            color: #333;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('.filterable .btn-filter').click(function() {
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

            $('.filterable .filters input').keyup(function(e) {
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
                var $filteredRows = $rows.filter(function() {
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
                    $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' +
                        $table.find('.filters th').length +
                        '">No se encuentra el laboratorio</td></tr>'));
                }
            });
        });

        $(document).ready(function() {
            $('#example').dataTable({
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });


        function eliminar(id, estado) {
            if (estado == 2) {

                bootbox.confirm("¿Se activará la cama, desea continuar?", function(result) {
                    if (result == true) {
                        $.ajax({
                            url: '/eliminar_cama/' + id + '/' + estado,
                            success: function(result) {
                                location.reload();
                            }
                        });
                    }
                });
            } else {

                bootbox.confirm("¿Se desactivará la cama?", function(result) {
                    if (result == true) {
                        $.ajax({
                            url: '/eliminar_cama/' + id + '/' + estado,
                            success: function(result) {
                                //$(".alert-danger").html("sddsssfc").show();
                                location.reload();
                            }
                        });
                    }
                });
            }
        }

        function activar(id) {
            bootbox.confirm("¿Desea activar el Registro?", function(result) {
                if (result == true) {
                    $.ajax({
                        url: '/activar_cuarto/' + id,
                        success: function(result) {
                            //$(".alert-danger").html("sddsssfc").show();
                            location.reload();
                        }
                    });
                }
            });
        }
    </script>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-2">
                <div class="col-sm-12 pull-right">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="/all_pabellon/"><i class="fa fa-table fa-fw"></i> Pabell&oacute;n</a></li>
                        <li><a href="/all_piso"><i class="fa fa-table fa-fw"></i> Pisos</a></li>
                        <li><a href="/all_asignacion_servicio"><i class="fa fa-table fa-fw"></i> Servicio</a></li>
                        <li><a href="/all_cuarto/"><i class="fa fa-table fa-fw"></i> Cuarto</a></li>
                        <li><a href="/create_cama/"><i class="fa fa-table fa-fw"></i> Nueva Cama</a></li>
                        <li <?php if ($id_atencion == 1) {
                            echo 'class="active"';
                        } ?>><a href="/all_cama/1"><i class="fa fa-plus"></i> Cama Emergencia</a></li>
                        <li <?php if ($id_atencion == 2) {
                            echo 'class="active"';
                        } ?>><a href="/all_cama/2"><i class="fa fa-plus"></i> Cama Hospitalización</a>
                        </li>
                        <li><a href="/reporte_d1_cama/"><i class="fa fa-table fa-fw"></i> Edicion Camas Tramas</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-10">
                <div class="row">
                    <div class="panel panel-primary filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">Cama</h3>
                            <!--
        <div class="pull-right">
            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Buscar</button>
        </div>
                                -->
                        </div>
                        <div class="table-responsive">
                            <div class="col-md-12">
                                <br /><br />
                                <table class="table table-hover" id="example">
                                    <thead>
                                        <tr class="filters">
                                            <th>N</th>
                                            <th>Atenci&oacute;n</th>
                                            <th>Pabell&oacute;n</th>
                                            <th>Piso</th>
                                            <th>Servicio</th>
                                            <th>Cuarto</th>
                                            <th>Cama</th>
                                            <th>Upss</th>
                                            <th>Disponible</th>
                                            @if ($id_establecimiento == 76)
                                                <th>Tipo</th>
                                                <th>Situación</th>
                                                <th>Clase</th>
                                            @endif
                                            <th>Editar</th>
                                            <th>Desactivar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $n = 0;
                                        ?>
                                        @foreach ($camas as $far)
                                            <tr>
                                                <td><?php $n = $n + 1;
                                                echo $n;
                                                ?></td>
                                                <td>{{ $far->nombre_atencion }}</td>
                                                <td>{{ $far->pabellon }}</td>
                                                <td>{{ $far->piso }}</td>
                                                <td>{{ $far->consultorio }}</td>
                                                <td>{{ $far->cuarto }}</td>
                                                <td>{{ $far->nombre }}</td>
                                                <td>{{ $far->camas_upsses }}</td>
                                                @if ($far->estado == 0)
                                                    <td>SI</td>
                                                @else
                                                    @if ($far->estado == 1)
                                                        <td>NO</td>
                                                    @else
                                                        <td>INACTIVO</td>
                                                    @endif
                                                @endif

                                                @if ($id_establecimiento == 76)
                                                    <td>{{ $far->desc_tipo }}</td>
                                                    <td>{{ $far->desc_situacion }}</td>
                                                    <td>{{ $far->clase_cama }}</td>
                                                @endif
                                                <td class="text-center">
                                                    <?php if($far->estado != "2"){ ?>
                                                    <a class="btn  btn-sm btn-success"
                                                        href="/edit_cama/{{ $far->id }}"><i class="fa fa-edit"></i></a>
                                                    <?php } else {?>
                                                    <button type="button" class="btn  btn-sm btn-default"><i
                                                            class="fa fa-edit"></i></button>
                                                    <?php }?>
                                                </td>
                                                <td class="text-center">

                                                    <?php if($far->estado != "2"){ ?>
                                                    <?php if($far->estado == "1"){ ?>
                                                    <a class="btn btn-sm btn-warning" href="javascript:void(0)"
                                                        onclick="eliminar(<?php echo $far->id; ?>,<?php echo $far->estado; ?>)"><i
                                                            class="fa fa-thumbs-up"></i></a>
                                                    <?php } else {?>
                                                    <a class="btn  btn-sm btn-default"><i class="fa fa-thumbs-down"></i></a>
                                                    <?php }?>
                                                    <?php } else {?>
                                                    <a class="btn btn-sm btn-default" href="javascript:void(0)"
                                                        onclick="eliminar(<?php echo $far->id; ?>,<?php echo $far->estado; ?>)"><i
                                                            class="fa fa-thumbs-down"></i></a>
                                                    <?php }?>
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
    @include('frontend.includes.footer')
@endsection


<div id="openOverlayOpc" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">
            <!--
      <div class="modal-header" style="padding: 0px;margin: 0px">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Categorias</h4>
      </div>
      -->
            <div class="modal-body" style="padding: 0px;margin: 0px">

                <div id="diveditpregOpc"></div>

            </div>
            <div class="modal-footer">

            </div>
        </div>

    </div>
</div>
