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
    #global {
        height: 600px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;
    }
    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .panel{
        margin-bottom:0px
    }

</style>

<script>
    $(document).ready(function () {
        $("#id_consultorio").select2({
            width: 'resolve' // need to override the changed default
        });
        $("#id_consultorio").trigger('change');

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
    $(document).ready(function () {
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

    function exportarExcel() {

        var id_consultorio = $('#id_consultorio').val();
        if (id_consultorio == "")
            id_consultorio = 0;
        location.href = 'exportar_stocksbyFamarciaAndConsultorio/' + id_consultorio+'/'+{{ Auth::user()->id }}+'';
    }
</script>
<style>
</style>
@endsection
@section('content')
<div class="container">
    <div class="row col-md-12">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title" style="font-size: 13px !important">Stock actual por Servicios de Atención</h3>
            </div>
            <div class="panel-body">
                <div class="row col-sm-12">
                    <form class="form-horizontal" method="post" action="stocksbyFamarciaAndConsultorioPost">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-sm-5">
                            <label><small>Servicio</small></label>
                            <select class="form-control" name="id_consultorio" id="id_consultorio" style="width: 100%">
                                <option value="0">Todos</option>
                                <?php
                                foreach ($consultorio as $d) {
                                    echo '<option value="' . $d->id . '"';
                                    if (isset($idconsultorio)) {
                                        if ($idconsultorio == $d->id)
                                            echo " selected";
                                    }
                                    echo '>' . $d->nombre . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <br/>
                            <input class="btn btn-info form-control" value="Buscar" type="submit">
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <br/>
                            <a class="btn btn-success form-control" href="javascript:void(0)" onclick="exportarExcel()" >
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                            </a>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <br/>
                        <table id="example" class="table" style="font-size: 12px !important;" >
                            <thead>
                                <tr class="filters">
                                    <th>Código</th>
                                    <th>Producto Farmacéutico o Dispositivo Médico</th>
                                    <th>Unidad</th>
                                    <th>Situación</th>
                                    <th>Stock Actual</th>                            
                                </tr>
                            </thead>
                            <tbody style="font-size: 11px !important;">
                                <?php
                                if (isset($resultado)) {
                                    ?>
                                    @foreach($resultado as $r)
                                    <?php
                                    $cpm = "";
                                    $cpma = "";
                                    $meses_provicion = "";

                                    if ($r['cantidad'] > 0 && ($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                        $cpma = -1; //-1
                                    } else {
                                        if ($r['nro_mes'] == 0) {
                                            $cpma = -1;
                                        } else {
                                            if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                                $cpma = 0;
                                            } else {
                                                $cpma = ($r['egresos'] - $r['recetas_devueltas']) / $r['nro_mes'];
                                                //$cpma = round($g,0);
                                            }
                                        }
                                    }

                                    if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                        $meses_provicion = 0;
                                    } else {
                                        $provision = round(($r['cantidad'] / $cpma), 1);
                                        $meses_provicion = $provision;
                                    }

                                    /*                                     * *********************** */
                                    $color = "";
                                    $situacionTemp = "";
                                    if ($r['cantidad'] <= 0):
                                        $color = "#FF0000"; //rojo
                                        $situacionTemp = 1;
                                        $situac="Desabastecido";//rojo
                                    else:
                                        if ($cpma <= 0):
                                            $color = "#4472C4"; //Sin Rotacion Azul
                                            $situacionTemp = 5;
                                            $situac="Sin Rotacion";
                                        endif;
                                        if ($meses_provicion > 0 && $meses_provicion < 2):
                                            $color = "#FFC000"; //SubStock amarillo
                                            $situacionTemp = 4;
                                            $situac="SubStock";
                                        endif;
                                        if ($meses_provicion >= 2 && $meses_provicion <= 6):
                                            $color = "#70AD47"; //NormoStock verde
                                            $situacionTemp = 3;
                                            $situac="NormoStock";
                                        endif;
                                        if ($meses_provicion > 6):
                                            $color = "#ED7D31"; //SobreStock anaranjado
                                            $situacionTemp = 2;
                                            $situac="SobreStock";
                                        endif;

                                    endif;
                                    ?>
                                    <tr>
                                        <td>{{$r['codigo']}}</td>
                                        <td>{{$r['nombre']}} </td>
                                        <td>{{$r['unidad']}}</td>
                                        <td style="color: <?php echo $color?>"><?php echo $situac?></td>
                                        <td class="text-center">{{$r['cantidad']}}</td>
                                    </tr>
                                    @endforeach
                                    <?php
                                }
                                ?>
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
