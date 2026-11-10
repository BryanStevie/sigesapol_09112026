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
        height: 480px !important;
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
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentró ningún producto farmacéutico o dispositivo médico</td></tr>'));
            }
        });
    });
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
		
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
				<?php if($permiso->lectura == 1):?>
                <li class="active"><a href="/all_productos"><i class="fa fa-table fa-fw"></i> Productos Farmac&eacuteuticos y Dispositivos M&eacutedicos </a></li>
				<?php endif;?>
                <?php if($permiso->crear == 1):?>
    				<li><a href="/create_producto"><i class="fa fa-plus"></i> Nuevo</a></li>
                <?php endif;?>
            </ul>
        </div>
		
        <div class="col-md-10 well">
            <div class="row margin" style=" font-size: 13px">
                <div class="form-group">                     
                    <!--<div class="col-md-3 margin-buscar">
                        <input id="textinput" name="textinput" type="text" placeholder="Código" class="form-control input-md">
                    </div>
                    <div class="col-md-1 margin-buscar">
                        <input id="" name="" type="submit" value="Buscar" class="btn btn-success">                        
                    </div>-->

                    <form id="frmtipo_prod" method="post" action="{{ route('search_tipo_producto') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="ruta" value="<?php echo $ruta?>">
                        <div class="col-md-5 margin-buscar">
                            <select class="form-control" required="" name="id_tipo" id="id_tipo">
                                <option value="">--Seleccionar por Tipos de Productos--</option>
                                @foreach($tipos as $t)
                                <option value="{{$t->id}}">{{$t->nombre}}</option>
                                @endforeach
                            </select>                        
                        </div>
                        <div class="col-md-1 margin-buscar">
                            <input id="" name="" type="submit" value="Buscar" class="btn btn-success">                        
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Productos Farmac&eacuteuticos y Dispositivos M&eacutedicos</h3>
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtrar</button>
                        </div>
                    </div>
                    <div id="global">
                        <table class="table " style="font-size: 11px !important;" >
                            <thead>
                                <tr class="filters">
                                    <th>ID</th>
                                    <!--<th><input type="text" class="form-control" placeholder="RUC" disabled></th>-->
                                    <th><input type="text" class="form-control" placeholder="Codigo" disabled maxlength="8"></th>
                                    <th><input type="text" class="form-control" placeholder="Nombre" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Unidad" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Tipo Prod" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Nivel" disabled></th>
                                    <?php if($permiso->crear == 1):?>
    									<th>Editar</th>
                                    <?php endif;?>
                                </tr>
                            </thead>
                            <tbody style="font-size: 11px !important;">

                                @foreach($productos as $pro)
                                <tr>
                                    <td>{{$pro->id}}</td>
                                    <!--<td></td>-->
                                    <td>{{$pro->codigo}}</td>
                                    <td>{{$pro->nombre}}</td>
									<td>{{$pro->abreviatura}}</td>
									<td>{{$pro->tipo_producto}}</td>
                                    <td>{{$pro->nivel}}</td>
                                    <?php if($permiso->crear == 1):?>
                                        <td>
                                            <a class="btn btn-success" href="/edit_producto/{{ $pro->id }}"><i class="fa fa-edit"></i></a>
                                        </td>
                                    <?php endif;?>
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
@include('frontend.includes.footer')
@endsection