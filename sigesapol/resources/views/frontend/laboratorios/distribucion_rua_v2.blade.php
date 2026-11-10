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
                    "sSearch": "Filtrar:",
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
    </script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Distribución RUA</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr class="filters">
                                      <th rowspan="2">N</th>
                                      <th rowspan="2">
                                        <input style="font-size:16px!important" type="text" class="form-control" placeholder="NIVEL" disabled>
                                      </th>
                                      <th colspan="2">AMBULATORIO</th>
                                      <th colspan="2">EMERGENCIA</th>
                                      <th colspan="2">HOSPITALIZACION</th>
                                    </tr>
                                    <tr>
                                      <th>LAB.</th>
                                      <th>IMAG.</th>
                                      <th>LAB.</th>
                                      <th>IMAG.</th>
                                      <th>LAB.</th>
                                      <th>IMAG.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($nivel == 1 || Auth::user()->flagestablecimientos == 1)
                                      <tr>
                                        <td>1.-</td>
                                        <td>NIVEL I</td>
                                        <td>
                                          <a class="btn btn-sm btn-success" href="/add_categoria_rua/1/1"><i class="fa fa-hospital-o"></i></a>
                                        </td>
                                        <td>
                                          <a href="/add_categoria_radiologias_nivel/8/1" class="btn btn-sm btn-warning btn-md"><i class="fa fa-h-square"></i></a>
                                        </td>
                                        <td>
                                          <a class="btn btn-sm btn-success" href="/add_categoria_rua/2/1"><i class="fa fa-hospital-o"></i></a>
                                        </td>
                                        <td>
                                          <a href="/add_categoria_radiologias_nivel/76/1" class="btn btn-sm btn-warning btn-md"><i class="fa fa-h-square"></i></a>
                                        </td>
                                        <td>
                                          <a class="btn btn-sm btn-success" href="/add_categoria_rua/3/1"><i class="fa fa-hospital-o"></i></a>
                                        </td>
                                        <td>
                                          <a href="/add_categoria_radiologias_nivel/105/1" class="btn btn-sm btn-warning btn-md"><i class="fa fa-h-square"></i></a>
                                        </td>
                                      </tr>
                                    @endif
                                    @if ($nivel == 2 || Auth::user()->flagestablecimientos == 1)
                                      <tr>
                                        <td>2.-</td>
                                        <td>NIVEL II</td>
                                        <td>
                                          <a class="btn btn-sm btn-success" href="/add_categoria_rua/2/1"><i class="fa fa-hospital-o"></i></a>
                                        </td>
                                        <td>
                                          <a href="/add_categoria_radiologias_nivel/8/2" class="btn btn-sm btn-warning btn-md"><i class="fa fa-h-square"></i></a>
                                        </td>
                                        <td>
                                          <a class="btn btn-sm btn-success" href="/add_categoria_rua/2/2"><i class="fa fa-hospital-o"></i></a>
                                        </td>
                                        <td>
                                          <a href="/add_categoria_radiologias_nivel/76/2" class="btn btn-sm btn-warning btn-md"><i class="fa fa-h-square"></i></a>
                                        </td>
                                        <td>
                                          <a class="btn btn-sm btn-success" href="/add_categoria_rua/2/3"><i class="fa fa-hospital-o"></i></a></i></a>
                                        </td>
                                        <td>
                                          <a  href="/add_categoria_radiologias_nivel/105/2" class="btn btn-sm btn-warning btn-md"><i class="fa fa-h-square"></i></a>
                                        </td>
                                      </tr>
                                    @endif
                                </tbody>
                            </table>
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
