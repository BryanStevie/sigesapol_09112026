<style>
    #tblToAddUpss .btn:focus,
    #tblToAddUpss .btn:active,
    #tblToQuitUpss .btn:focus,
    #tblToQuitUpss .btn:active {
        outline: none !important;
        box-shadow: none !important;
        border-color: transparent !important;
    }
</style>
<script>
    $(document).ready(function() {
        var tblToAddUpss = $("#tblToAddUpss").DataTable({
            searching: false,
            lengthChange: false,
            paging: false,
            info: false,
            ordering: false,
            columns: [{
                    title: "Código",
                    data: "codigo"
                },
                {
                    title: "Descripción",
                    data: "descripcion_upss"
                },
                {
                    title: "Estado",
                    data: "estado",
                    render: function(data, type, row) {
                        if (data == 1) {
                            return `<span style="background-color: green;" class="badge bg-success">Activo</span>`;
                        } else {
                            return `<span class="badge bg-danger">No activo</span>`;
                        }
                    },
                    className: "text-center"
                },
                {
                    title: "Acción",
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-primary btn-add-upss" 
                                    data-id="${row.id}" 
                                    style="width: 32px; height: 32px; border-radius: 8px; padding: 0; font-size: 17px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
                            </button>`;
                    },
                    className: "text-center"
                }
            ]
        });
        var tblToQuitUpss = $("#tblToQuitUpss").DataTable({
            searching: false,
            lengthChange: false,
            paging: false,
            info: false,
            ordering: false,
            columns: [{
                    title: "Código",
                    data: "codigo"
                },
                {
                    title: "Descripción",
                    data: "descripcion_upss"
                },
                {
                    title: "Acción",
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-danger btn-remove-upss" 
                                    data-id="${row.id}" 
                                    style="width: 32px; height: 32px; border-radius: 8px; padding: 0; font-size: 17px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                            </button>
                        `;
                    },
                    className: "text-center"
                }
            ]
        });

        $('#tblToAddUpss').on('click', '.btn-add-upss', function() {
            var row = tblToAddUpss.row($(this).closest('tr'));
            var data = row.data();

            // Verificar si ya existe en la tabla "tblToQuitUpss"
            var exists = false;
            tblToQuitUpss.rows().every(function() {
                var row = this.data();
                console.log(data.id);
                if (row.id === data.id) { // Compara por el campo único, por ejemplo "id"
                    exists = true;
                    return false; // salir del bucle
                }
            });

            if (exists) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Este registro ya fue agregado.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            tblToQuitUpss.row.add(data).draw();
        });

        $('#tblToQuitUpss').on('click', '.btn-remove-upss', function() {
            var row = tblToQuitUpss.row($(this).closest('tr'));
            row.remove().draw();
        });

        $("#btnBuscarUps").on("click", function() {
            var codigo = $('#codigo_upss').val().trim();
            var descripcion = $('#descripcion_upss').val().trim();
            var estado = $('#estado_upss').val();

            if (codigo === '' && descripcion === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos requeridos',
                    text: 'Debes ingresar al menos el código o la descripción para buscar.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor, espera mientras se realiza la búsqueda.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.post('/buscarUps', {
                    codigo: $('#codigo_upss').val(),
                    descripcion_upss: $('#descripcion_upss').val(),
                    estado: $('#estado_upss').val(),
                    tipo_consulta_medica: $('#tipoCM').val(),
                    tipo_servicio: $('#tipoServUps').val(),
                    _token: '{{ csrf_token() }}'
                })
                .done(function(data) {
                    Swal.close();

                    if (data.length > 0) {
                        tblToAddUpss.clear();
                        tblToAddUpss.rows.add(data);
                        tblToAddUpss.draw();

                        Swal.fire({
                            icon: 'success',
                            title: 'Búsqueda completada',
                            text: `Se encontraron ${data.length} resultados.`,
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        tblToAddUpss.clear().draw();
                        Swal.fire({
                            icon: 'info',
                            title: 'Sin resultados',
                            text: 'No se encontraron coincidencias.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .fail(function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un problema al realizar la búsqueda. Intenta nuevamente.'
                    });
                });
        });

        $("#btnAddUpssToFilter").on("click", function() {
            var tblToAddUpss = $("#tblToAddUpss").DataTable();
            var tblToQuitUpss = $("#tblToQuitUpss").DataTable();

            var items = tblToQuitUpss.rows().data().toArray();

            if (items.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'No hay registros seleccionados para agregar.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            Swal.fire({
                title: '¿Deseas agregar los registros seleccionados?',
                text: "Se enviarán los registros y se reiniciarán las tablas.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, agregar',
                cancelButtonText: 'No, cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var tblFinalUpsFilter = $("#tblFinalUpsFilter").DataTable();

                    var existingData = tblFinalUpsFilter.rows().data().toArray();

                    var newItems = items.filter(function(item) {
                        return !existingData.some(function(existing) {
                            return existing.id === item.id;
                        });
                    });

                    if (newItems.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Todos los registros seleccionados ya existen en la tabla final.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        return;
                    }

                    tblFinalUpsFilter.rows.add(newItems).draw();

                    $('#codigo_upss').val('');
                    $('#descripcion_upss').val('');
                    $('#estado_upss').val('');

                    tblToAddUpss.clear().draw();
                    tblToQuitUpss.clear().draw();

                    $('#modalAddUpssToFilters').modal('hide');

                    console.log("Items seleccionados:", items);

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Cancelado',
                        text: 'No se realizaron cambios.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

        $('#modalAddUpssToFilters').on('hidden.bs.modal', function() {
            $('#codigo_upss').val('');
            $('#descripcion_upss').val('');
            $('#estado_upss').val('');

            var tblToAddUpss = $("#tblToAddUpss").DataTable();
            var tblToQuitUpss = $("#tblToQuitUpss").DataTable();

            tblToAddUpss.clear().draw();
            tblToQuitUpss.clear().draw();
        });
    });
</script>
<div class="modal fade" id="modalAddUpssToFilters" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Seleccionar UPS</h5>
            </div>
            <div class="modal-body">
                <!-- Panel de filtros -->
                <div class="panel panel-default">
                    <div class="panel-body" style="">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Código</label>
                                <input type="text" id="codigo_upss" class="form-control input-sm"
                                    placeholder="Código">
                            </div>
                            <div class="col-sm-4">
                                <label>Nombre UPS</label>
                                <input type="text" id="descripcion_upss" class="form-control input-sm"
                                    placeholder="Nombre UPS">
                            </div>
                            <div class="col-sm-4">
                                <label>Estado</label>
                                <select class="form-control input-sm" id="estado_upss">
                                    <option value="">- Seleccionar -</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" style="margin-top:8px;">
                            <div class="col-sm-4">
                                <label>Tipo Consulta Médica</label>
                                <select id="tipoCM" class="form-control input-sm">
                                    <option value="">- Todos -</option>
                                    <option value="1">CCEE</option>
                                    <option value="2">EMER</option>
                                    <option value="3">HOS</option>
                                    <option value="4">ESTRA</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Tipo Servicio</label>
                                <select id="tipoServUps" class="form-control input-sm">
                                    <option value="">- Todos -</option>
                                    @foreach ($serviciosParaUps as $servicio)
                                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                {{-- <div class="row" style="margin-bottom:8px;">
                    <div class="col-sm-4" style="">
                        <button id="btnBuscarUps" class="btn btn-default btn-sm">
                            <i class="glyphicon glyphicon-search"></i> Buscar
                        </button>
                        <button id="btnAddUpssToFilter" class="btn btn-success btn-sm">
                            <i class="glyphicon glyphicon-ok"></i> Aceptar
                        </button>
                    </div>
                    <div class="col-sm-8 text-right">
                        <button class="btn btn-info btn-sm">
                            <i class="glyphicon glyphicon-check"></i> Seleccionar todo
                        </button>
                        <button class="btn btn-default btn-sm">
                            <i class="glyphicon glyphicon-refresh"></i> Limpiar Todo
                        </button>
                    </div>
                </div> --}}
                <div class="row"
                    style="margin-top: 15px; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                    <div class="col-xs-6">
                        <button id="btnBuscarUps" type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                        </button>
                        <button id="btnAddUpssToFilter" type="button" class="btn btn-success">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Aceptar
                        </button>
                    </div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="select_all btn btn-default">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Seleccionar todo
                        </button>
                        <button type="button" class="remove_all btn btn-default">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Limpiar Todo
                        </button>
                    </div>
                </div>

                <!-- Tablas -->
                <div class="row">
                    <div class="col-sm-6" style="padding-right:5px;">
                        <div class="table-responsive">
                            <table id="tblToAddUpss" class="table table-bordered table-condensed toAdd">
                                <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-6" style="padding-left:5px;">
                        <div class="table-responsive">
                            <table id="tblToQuitUpss" class="table table-bordered table-condensed toQuit">
                                <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Nombre</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
