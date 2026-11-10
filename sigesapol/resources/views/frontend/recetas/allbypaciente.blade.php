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
        height: 550px !important;
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
    .id{
        width: 40px;
    }
    .nro_historia{
        width: 150px;
    }
    .fecha{
        width: 190px;
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
<div class="container">
    <h2>Atender Receta
    </h2>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h4 class="panel-title">
                    Recetas del Paciente:  DNI ({{$receta[0]->dni}}) | {{$receta[0]->nombre}} {{$receta[0]->ape_paterno}} {{$receta[0]->ape_materno}}
                </h4>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Buscar</button>
                </div>
            </div>
            <div id="global">
                <table class="table " style="font-size: 14px !important;" >
                    <thead>
                        <tr class="filters">
                            <th>Nro.</th>
                            <th><input type="text" class="form-control" placeholder="Nro Historia" disabled maxlength="8"></th>
                            <th><input type="text" class="form-control" placeholder="Fec. emision Receta" disabled></th>                              
                            <th><input type="text" class="form-control" placeholder="Especialidad" disabled></th> 
                            <th>Atender</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px !important;">
                         {{-- */$x=0;/* --}}
                        @foreach($receta as $a)
                        {{-- */$x++;/* --}}
                        <tr>
                            <td class="id">{{ $x }}</td>
                            <td class="nro_historia"><b>{{$a->nro_historia}}<b></td>
                            <td class="fecha">{{$a->fecha_emision}}</td>
                            <td>{{$a->especialidad}}</td>                            
                            <td align="center" class="id">
                                    <a class="btn btn-warning"><em class="fa fa-pencil "></em></a>                                    
                            </td>
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