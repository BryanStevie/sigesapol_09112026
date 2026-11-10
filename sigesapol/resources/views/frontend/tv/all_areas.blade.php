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
    $('#tbltv').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/tv_listar_area",
            "type": "POST",
            "dataType": "json",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columnDefs": [{
            "orderable": false,
            "targets": 0
        }],
        "pageLength": 10,
        "dom": 'Bfrtip',
        "filter": true,
        "columns": [{
                "data": "num",
                "name": "N"
            },
            {
                "data": "servicio",
                "name": "Servicio"
            },
            {
                "data": "area_descripcion",
                "name": "Área"
            },
            {
                "data": "consultorios",
                "name": "Consultorios"
            },
            {
                "data": "des_estado_area",
                "name": "Estado",
                "className": "text-center"
            },
            {
                "data": null,
                "name": "TV",
                "className": "text-center",
                "render": function(data, type, row, meta) {
                    if (row['id_estado_area'] == 1) {
                        return `<a class='btn btn-primary btn-xs' target='_blank' href='/tv/${row['id_tv']}/${row['id_tv_area']}'><i class='fa fa-tv'></i> TV</a>`;
                    } else {
                        return `<button type="button" class="btn btn-primary btn-xs">TV</button>`
                    }
                }
            },
            {
                "data": null,
                "name": "Editar",
                "className": "text-center",
                "render": function(data, type, row, meta) {
                    if (row['id_estado_area'] == 1) {
                        return `<a class='btn btn-success btn-xs' href='/tv_area_edit/${row['id_tv_area']}'>Editar</a>`;
                    } else {
                        return `<button type="button" class="btn btn-success btn-xs" disabled>Editar</button>`
                    }
                }
            }, {
                "data": null,
                "name": "Desactivar",
                "className": "text-center",
                "render": function(data, type, row, meta) {
                    if (row['id_estado_area'] == 1) {
                        return `<a class="btn btn-warning btn-xs" href="javascript:void(0)" onclick="actualizar_estado(${row['id_tv_area']},'0')">Deshabilitar</a>`;
                    } else {
                        return `<a class="btn btn-success btn-xs" href="javascript:void(0)" onclick="actualizar_estado(${row['id_tv_area']},'1')">Habilitar</a>`
                    }
                }
            }
        ]
    });
});

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
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table
                .find('.filters th').length + '">No se encuentra el Tipo de Proceso</td></tr>'));
        }
    });
});

function actualizar_estado(id, estado) {
    if (estado == "0") {
        bootbox.confirm("¿Desea desactivar el Área seleccionado?", function(result) {
            if (result == true) {
                // return false;
                $.ajax({
                    url: '/tv_area_actualizar_estado/' + id + '/' + estado,
                    success: function(result) {
                        location.reload();
                    }
                });
            }
        });
    } else if (estado == "1") {
        bootbox.confirm("¿Desea activar el Área seleccionado?", function(result) {
            if (result == true) {
                // return false;
                $.ajax({
                    url: '/tv_area_actualizar_estado/' + id + '/' + estado,
                    success: function(result) {
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
<div class="container">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title">Areas de los Televisores</h3>
            </div>
            <div class="panel-body">
                
                <a href="/tv_create_area" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a>
                
                <!-- <br /><br /> -->
                <div class="table-responsive">

                    <table class="table" id="tbltv">
                        <thead>
                            <tr class="filters">
                                <th>#</th>
                                <th>Servicio</th>
                                <th>Area</th>
                                <th>Consultorios</th>
                                <th>Estado</th>
                                <th>TV</th>
                                <th>Editar</th>
                                <th>Desactivar</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection