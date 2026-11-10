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
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/gestion_medicos/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Personal de Salud</a></li>
                <li class=""><a href="/create_personal_institucional/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>
                <!--<li><a href="/create_medico/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>-->
                <!--<li class=""><a href="/formatoxls_personal_medico/{{Auth::user()->id}}"><i class="fa fa-download"></i> Descargar Formato</a></li>-->
                <!--<li class=""><a href="/import"><i class="fa fa-plus"></i> Importar</a></li>-->
                <!--<li class="active"><a href="/importaciones_medicos/{{Auth::user()->id}}"><i class="fa fa-info-circle"></i> Importarciones</a></li>-->
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Importar datos del Personal de Salud </h3>
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Buscar</button>
                        </div>
                    </div>
                    <div style="margin: 20px 20px 20px 20px;">
                        <form class="form-horizontal"  id="frmimportacion" method="post" action="{{route('subir_data_medicos')}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <!-- File Button --> 
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="filebutton">Lista de Medicos</label>
                                <div class="col-md-4">
                                    <input id="url_data" class="input-file" type="file" name="url_data">
                                    <input type="hidden" name="id_user" value="{{$usuarios}}">
                                    <input type="hidden" name="id_establecimiento" value="{{$establecimiento->id}}">                                    
                                </div>
                                <div class="col-md-4">
                                    <button id="singlebutton" name="singlebutton" class="btn btn-primary btn-xs" type="submit">Subir</button>
                                </div>                                
                            </div>
                        </form>
                    </div> 
                    @if(count($importaciones)>0)
                    <table class="table">
                        <thead>
                            <tr class="filters">
                                <th>N</th>
                                <!--<th><input type="text" class="form-control" placeholder="RUC" disabled></th>-->
                                <th><input type="text" class="form-control" placeholder="Archivo" disabled></th>
                                <th><input type="text" class="form-control" placeholder="Fecha Importación" disabled></th>
                                <th>Importar</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n=0; ?>
                            @foreach($importaciones as $i)
                            <tr>
                                <td><?php $n++; echo $n; ?></td>
                                <!--<td></td>-->
                                <td>{{$i->url_data}}</td>
                                 
                                @if($i->estado==0)
                                <td>Sin importar datos</td>
                                <td>
                                    <a class="btn btn-warning" href="/add_importacion_medicos/{{$i->id}}/{{$usuarios}}"><i class="fa fa-info"></i></a>                                   
                                </td>
                                @else
                                <td>{{$i->fecha_importacion}}</td>
                                <td>
                                    <i class="fa fa-check"></i>                                  
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection