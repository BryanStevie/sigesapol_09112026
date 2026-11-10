@extends('frontend.layouts.master')
@section('renderjs')

<style>
    .filterable {
        margin-top: 15px;
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
    /*
     Please consider that the JS part isn't production ready at all, I just code it to show the concept of merging filters and titles together !
     */
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
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No result found</td></tr>'));
            }
        });
    });
</script>
@endsection
@section('content')
<div class="container">
    <h4>Stock - {{$farma->nombre}}</h4>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">GENERAR EL REQUERIMIENTO DE PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MEDICOS</h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> BUSCAR</button>
                </div>
            </div>
            <form method="post" action="{{ route('generar_requerimiento')}}" id='frmReporteIcis'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input value="{{$farma->id}}" name="id_farmacia" type="hidden">  
                <table class="table">
                    <thead>
                        <tr class="filters">
                            <th>N</th>
                            <th>Código</th>
                            <th><input type="text" class="form-control" placeholder="Decripción" disabled></th>
                            <th>Unidad</th>
                            <th>Stock Actual</th>
                            <th><button type="submit">Requerir</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($stocks as $s)
                        <tr>
                            <td><?php $n++; echo $n; ?></td>
                            <td>
                                {{$s->codigo_producto}}
                                <input name="codigo_producto[]" value="{{$s->codigo_producto}}" type="hidden">
                            </td>
                            <td>{{$s->nombre_producto}}</td>
                            <td>{{$s->abreviatura}}</td>
                            <td>{{$s->cantidad}}</td>
                            <td>
                                <input value="" type="number" style="width: 60px" id="cantidad_solictada" name="cantidad_solicitada[]"> 
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
             </from>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
