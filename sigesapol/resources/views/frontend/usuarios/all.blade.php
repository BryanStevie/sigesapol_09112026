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
  
	$(document).ready(function () {
        $('#example').dataTable( {
            processing: true,
            serverSide: true,
            ajax: {
                url: '/usuarios/datatable',
                type: 'GET',
                data: function (d) {
                    d.permiso_modificar = $('#permiso_modificar').val();
                }
            },
            pageLength: 10,
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                paginate: {
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        } );
    });

</script>
@endsection
@section('content')
<div class="container" style="width:90%">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-1">
                <ul class="nav nav-pills nav-stacked">
                    <?php if ($permiso->lectura == 1): ?>
                        <li class="active"><a href="/all_usuario"><i class="fa fa-table fa-fw"></i>Usuarios</a></li>
                    <?php endif; ?>
                </ul>
                <br/>
            </div>
            <div class="col-md-10">
            </div>
            <div class="col-md-1">
                <ul class="nav nav-pills nav-stacked">
                    <?php if ($permiso->crear == 1): ?>
                        <li><a href="/create_usuario"><i class="fa fa-plus"></i> Nuevo</a></li>
                    <?php endif; ?>
                </ul>
                <br/>
            </div>

        </div>

        <div class="col-md-12 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Todos los Usuarios</h3>
                        <!--<div class="pull-right">
                            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Buscar</button>
                        </div>-->
                    </div>
                    <!--<div id="global">-->
                    <div class="table-responsive">
                        <table class="table" id="example">
                            <thead>
                                <tr class="filters">
                                    <th>N</th>
                                    <th><input type="text" class="form-control" placeholder="Apellidos y Nombres" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="DNI" disabled maxlength="8" onkeypress="return IsNumber(event)"></th>
                                    <th><input type="text" class="form-control" placeholder="E-mail" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Hospital" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Farmacia" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Servicio" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Tipo de Usuario" disabled onkeypress="return solo_letra(event)"></th>
                                    <th><input type="text" class="form-control" placeholder="Activo" disabled></th>
                                    <?php if ($permiso->modificar == 1): ?>
                                        <th>&nbsp;&nbsp;Editar&nbsp;&nbsp;</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="permiso_modificar" value="{{ $permiso->modificar }}">

@include('frontend.includes.footer')
@endsection