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
    
    $(document).ready(function () {
        //$('#example').DataTable();
        $('#example').dataTable( {
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                },
                "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        } );
    });

	function deshabilitar(id){
		bootbox.confirm("¿Desea deshabilitar el Registro seleccionado?", function(result) {
			if (result==true) {				
				$.ajax({
					url: '/deshabilitar_triaje_topico/'+id,
					success: function(result){
						//$(".alert-danger").html("sddsssfc").show();
						location.reload();
					}
				});
            }
		});
	}

    function habilitar(id){
		bootbox.confirm("¿Desea habilitar el Registro seleccionado?", function(result) {
			if (result==true) {				
				$.ajax({
					url: '/habilitar_triaje_topico/'+id,
					success: function(result){
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
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <?php //if ($permiso->lectura == 1): ?>
                    <li class="active"><a href="/all_triaje_topico"><i class="fa fa-table fa-fw"></i> Tópicos</a></li>
                <?php //endif; ?>
                <?php //if ($permiso->crear == 1): ?>
                    <li><a href="/create_prioridad_triaje"><i class="fa fa-plus"></i> Nuevo </a></li>
                <?php //endif; ?>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Listado de Tópicos - Emergencia</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover"  id="example">
                            <thead>
                                <tr class="filters">
                                    <th>N</th>
                                    <th><input type="text" class="form-control" placeholder="Descripción" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Prioridad" disabled></th>
                                    <?php if ($permiso->modificar == 1): ?>
                                        <th><input type="text" class="form-control" placeholder="Editar" disabled></th>
                                    <?php endif; ?>
                                    <?php if ($permiso->eliminar == 1 and $permiso->modificar == 1) : ?>
                                        <th><input type="text" class="form-control" placeholder="Habilitar/Deshabilitar" disabled></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $n = 0;
                                ?>
                                @foreach($triajes as $fing)
                                <tr>
                                    <td><?php
                                        $n = $n + 1;
                                        echo $n;
                                        ?></td>
                                    <!--<td></td>-->
                                    <td>{{$fing->descripcion}}</td>
                                    <td>{{$fing->prioridad}}</td>
                                    <?php if ($permiso->modificar == 1): ?>
                                        <td class="text-center">
                                            <a class="btn btn-success" href="/edit_triaje_topico/{{ $fing->id }}"><i class="fa fa-edit"></i></a>                                    
                                        </td>
                                    <?php endif; ?>
                                    
                                    <td class="text-center">
                                        <?php //if ($permiso->eliminar == 1): ?>
                                        <?php if ($fing->estado == 'N'): ?>
                                        <a class="btn btn-success" href="javascript:void(0)" onclick="deshabilitar(<?php echo $fing->id ?>)"><i class="fa fa-thumbs-up"></i></a>
                                        <?php else: ?>
                                        <a class="btn btn-danger" href="javascript:void(0)" onclick="habilitar(<?php echo $fing->id ?>)"><i class="fa fa-thumbs-down"></i></a>
                                        <?php endif; ?>
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
@include('frontend.includes.footer')
@endsection


<div id="openOverlayOpc" class="modal fade" role="dialog">
  <div class="modal-dialog" >

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
