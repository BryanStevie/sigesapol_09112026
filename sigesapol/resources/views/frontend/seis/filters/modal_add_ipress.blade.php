<style>
    #tblToAddIpress .btn:focus,
    #tblToAddIpress .btn:active,
    #tblToQuitIpress .btn:focus,
    #tblToQuitIpress .btn:active {
        outline: none !important;
        box-shadow: none !important;
        border-color: transparent !important;
    }
</style>
<script>
    $(document).ready(function() {
        var tblToAddIpress = $("#tblToAddIpress").DataTable({
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
                    title: "Nombre",
                    data: "nombre_ipress"
                },
                {
                    title: "Categoria",
                    data: "nivel",
                    render: function(data, type, row) {
                        if (data == 1) {
                            return `<span>I</span>`;
                        } else if (data == 2) {
                            return `<span>II</span>`;
                        } else if (data == 3) {
                            return `<span>III</span>`;
                        } else {
                            return `<span class="badge bg-danger">No disponible</span>`;
                        }
                    },
                    className: "text-center"
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
                            <button class="btn btn-primary btn-add-ipress" 
                                    data-id="${row.id}" 
                                    style="width: 32px; height: 32px; border-radius: 8px; padding: 0; font-size: 17px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
                            </button>`;
                    },
                    className: "text-center"
                }
            ]
        });
        var tblToQuitIpress = $("#tblToQuitIpress").DataTable({
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
                    title: "Nombre",
                    data: "nombre_ipress"
                },
                {
                    title: "Acción",
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-danger btn-remove-ipress" 
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

        $('#tblToAddIpress').on('click', '.btn-add-ipress', function() {
            var row = tblToAddIpress.row($(this).closest('tr'));
            var data = row.data();

            var exists = false;
            tblToQuitIpress.rows().every(function() {
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

            tblToQuitIpress.row.add(data).draw();
        });

        $('#tblToQuitIpress').on('click', '.btn-remove-ipress', function() {
            var row = tblToQuitIpress.row($(this).closest('tr'));
            row.remove().draw();
        });

        $("#btnBuscarIpress").on("click", function() {
            var descripcion = $('#descripcion_ipress').val().trim();
            var estado = $('#estado_ipress').val();
            var categoria_ipress = $('#categoria_ipress').val();
            var region_ipress = $('#grupo-region_ipress').val();

            if (descripcion === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos requeridos',
                    text: 'Debes ingresar al menos la descripción para buscar.',
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

            $.post('/buscarIpress', {
                    descripcion_ipress: $('#descripcion_ipress').val(),
                    estado: $('#estado_ipress').val(),
                    categoria_ipress: $('#categoria_ipress').val(),
                    region_ipress: $('#grupo-region_ipress').val(),
                    _token: '{{ csrf_token() }}'
                })
                .done(function(data) {
                    Swal.close();

                    if (data.length > 0) {
                        tblToAddIpress.clear();
                        tblToAddIpress.rows.add(data);
                        tblToAddIpress.draw();

                        Swal.fire({
                            icon: 'success',
                            title: 'Búsqueda completada',
                            text: `Se encontraron ${data.length} resultados.`,
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        tblToAddIpress.clear().draw();
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

        $("#btnAddIpressToFilter").on("click", function() {
            var tblToAddIpress = $("#tblToAddIpress").DataTable();
            var tblToQuitIpress = $("#tblToQuitIpress").DataTable();

            var items = tblToQuitIpress.rows().data().toArray();

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
                    var tblFinalIpressFilter = $("#tblFinalIpressFilter").DataTable();

                    var existingData = tblFinalIpressFilter.rows().data().toArray();

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

                    tblFinalIpressFilter.rows.add(newItems).draw();

                    $('#descripcion_ipress').val('');
                    $('#categoria_ipress').val('');
                    $('#grupo-region_ipress').val('');
                    $('#estado_ipress').val('');

                    tblToAddIpress.clear().draw();
                    tblToQuitIpress.clear().draw();

                    $('#seleccionarIPRESSModal').modal('hide');

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

        $('#seleccionarIPRESSModal').on('hidden.bs.modal', function() {
            $('#descripcion_ipress').val('');
            $('#categoria_ipress').val('');
            $('#grupo-region_ipress').val('');
            $('#estado_ipress').val('');

            var tblToAddIpress = $("#tblToAddIpress").DataTable();
            var tblToQuitIpress = $("#tblToQuitIpress").DataTable();

            tblToAddIpress.clear().draw();
            tblToQuitIpress.clear().draw();
        });
    });
</script>
<div class="modal fade" id="seleccionarIPRESSModal" tabindex="-1" role="dialog" aria-labelledby="seleccionarIPRESSLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header"
                style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="seleccionarIPRESSLabel">Seleccionar IPRESS</h4>
            </div>

            <div class="modal-body">

                <div class="row" style="padding-bottom: 15px; border: 1px solid #ccc; margin: 0; padding-top: 15px;">

                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="descripcion_ipress" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Descripción</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <input type="text" class="form-control" id="descripcion_ipress">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="grupo-region_ipress" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Grupo / Región</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <select class="form-control" id="grupo-region_ipress">
                                    <option value="">- Seleccionar -</option>
                                    @foreach ($regiones_ipress as $region)
                                        <option value="{{ $region->red_region }}">{{ $region->red_region }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="categoria_ipress" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Categoría</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <select class="form-control" id="categoria_ipress">
                                    <option value="">- Seleccionar -</option>
                                    <option value="1">I</option>
                                    <option value="2">II</option>
                                    <option value="3">III</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="estado_ipress" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Estado</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <select class="form-control" id="estado_ipress">
                                    <option value="">- Seleccionar -</option>
                                    <option value="1">Activos</option>
                                    <option value="0">Inactivos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"
                    style="margin-top: 15px; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                    <div class="col-xs-6">
                        <button type="button" class="btn btn-default" id="btnBuscarIpress">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                        </button>
                        <button type="button" class="btn btn-success" id="btnAddIpressToFilter">
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
                <div class="row">

                    <div class="col-sm-6">
                        <div class="table-responsive" style="border: 1px solid #ccc;">
                            <table id="tblToAddIpress" class="table table-condensed toAdd" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Descripción</th>
                                        <th>Categoría</th>
                                        <th>Estado</th>
                                        <th style="width: 10%; text-align: center;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="table-responsive" style="border: 1px solid #ccc;">
                            <table id="tblToQuitIpress" class="table table-condensed toQuit" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Nombre</th>
                                        <th style="width: 10%; text-align: center;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
