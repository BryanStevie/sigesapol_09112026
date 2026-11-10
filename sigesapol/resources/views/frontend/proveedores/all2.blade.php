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
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el Personal de Salud</td></tr>'));
            }
        });
    });
	
	function eliminar(id, estado){
            if(estado == 3){
                    bootbox.confirm("¿Se activará al proveedor de salud, desea continuar?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_proveedor/'+id+'/'+estado,
                            success: function(result){
                                //$(".alert-danger").html("sddsssfc").show();
                                location.reload();
                            }
                         });
                    }
                });
            } else {
                    bootbox.confirm("¿Se desactivará al proveedor de salud?", function(result) {
                    if (result==true) {				
                        $.ajax({
                            url: '/desactivar_proveedor/'+id+'/'+estado,
                            success: function(result){
                                //$(".alert-danger").html("sddsssfc").show();
                                location.reload();
                            }
                         });
                    }
                });
            }
	}
	
</script>
@endsection
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Proveedores de Salud </h3>
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtrar</button>
                        </div>
                    </div>
                    <div class="panel-body">
                    <?php if ($permiso->crear == 1): ?>
                        <a href="/formulario_proveedor" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a>
                    <?php endif; ?>
                        <br/><br/>
                    <div class="table-responsive">
                        <table class="table table-hover" style="font-size: 11px !important">
                            <thead>
                                <tr class="filters">
                                    <th>N</th>
                                    <th><input type="text" placeholder="RUC" class="form-control" disabled  maxlength="11" onkeypress="return IsNumber(event)" style="font-size: 11px !important; padding:3px 3px"></th>                                
                                    <th><input type="text" class="form-control" placeholder="Descripcion" disabled onkeypress="return solo_letra(event)" style="font-size: 11px !important; padding:3px 3px"></th>
                                    <th><input type="text" class="form-control" placeholder="Direccion" disabled onkeypress="return  style="font-size: 11px !important; padding:3px 3px"></th>
                                    <th><input type="text" class="form-control" placeholder="Fax" disabled onkeypress="return IsNumber(event)" maxlength="6" style="font-size: 11px !important; padding:3px 3px"></th>
                                    <th><input type="text" class="form-control" placeholder="Telefono" disabled onkeypress="return IsNumber(event)" style="font-size: 11px !important; padding:3px 3px"></th>
                                    <th><input type="text" class="form-control" placeholder="Estado" disabled onkeypress="return solo_letra(event)" style="font-size: 11px !important; padding:3px 3px"></th>
                                    <!--<th>Servicio <br/>Atenci&oacute;n</th>-->
                                    <?php if ($permiso->modificar == 1): ?>
                                        <th style="font-size: 13px !important">Editar</th>
                                    <?php endif; ?>
                                    <?php if ($permiso->eliminar == 1): ?>
                                        <th>Desactivar</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $n = 0;
                                ?>
                                @foreach($proveedores as $prov)
                                <tr>
                                    <td><?php
                                        $n = $n + 1;
                                        echo $n;
                                        ?></td>
                                    <td>{{$prov->ruc_proveedor}}<br>
                                    </td>                                
                                    <td>{{$prov->nombre_proveedor}}</td>
                                    <td>{{$prov->direccion_proveedor}}</td>
                                    <td>{{$prov->telefono_proveedor}}</td>
                                    <td>{{$prov->fax_proveedor}}</td>
                                    <td>
                                        <?php echo $prov->estado == "1"? "ACTIVO":"INACTIVO"; ?>
                                    </td>
                                    <?php if ($permiso->modificar == 1): ?>
                                        <td class="text-center">
                                            <?php if($prov->estado == "1"){ ?>
                                            <a class="btn btn-success btn-xs" href="/editar_proveedor/{{$prov->id}}"><i class="fa fa-edit"></i></a>
                                            <?php } else {?>
                                            <button type="button" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button>
                                            <?php }?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if ($permiso->eliminar == 1): ?>
                                        <td class="text-center">
                                            <?php if($prov->estado == "1"){ ?>
                                            <a class="btn btn-warning btn-xs" href="javascript:void(0)" onclick="eliminar(<?php echo $prov->id ?>,<?php echo $prov->estado ?>)"><i class="fa fa-thumbs-up"></i></a>                                    
                                            <?php } else {?>
                                            <a class="btn btn-success btn-xs" href="javascript:void(0)" onclick="eliminar(<?php echo $prov->id ?>,<?php echo $prov->estado ?>)"><i class="fa fa-thumbs-up"></i></a>
                                            <?php }?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('frontend.includes.footer')
@endsection