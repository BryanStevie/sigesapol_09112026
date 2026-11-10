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
        var tblToAddServ = $("#tblToAddServ").DataTable({
            searching: false,
            lengthChange: false,
            paging: false,
            info: false,
            ordering: false,
            columns: [{
                    title: "Código",
                    data: "id"
                },
                {
                    title: "Nombre",
                    data: "nombre_servicio"
                },
                {
                    title: "Estado",
                    data: "estado",
                    render: function(data, type, row) {
                        if (data == 4) {
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
                            <button class="btn btn-primary btn-add-serv" 
                                    data-id="${row.id}" 
                                    style="width: 32px; height: 32px; border-radius: 8px; padding: 0; font-size: 17px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
                            </button>`;
                    },
                    className: "text-center"
                }
            ]
        });
        var tblToQuitServ = $("#tblToQuitServ").DataTable({
            searching: false,
            lengthChange: false,
            paging: false,
            info: false,
            ordering: false,
            columns: [{
                    title: "Código",
                    data: "id"
                },
                {
                    title: "Nombre",
                    data: "nombre_servicio"
                },
                {
                    title: "Acción",
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-danger btn-remove-serv" 
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

        $('#tblToAddServ').on('click', '.btn-add-serv', function() {
            var row = tblToAddServ.row($(this).closest('tr'));
            var data = row.data();

            var exists = false;
            tblToQuitServ.rows().every(function() {
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

            tblToQuitServ.row.add(data).draw();
        });

        $('#tblToQuitServ').on('click', '.btn-remove-serv', function() {
            var row = tblToQuitServ.row($(this).closest('tr'));
            row.remove().draw();
        });

        $("#btnBuscarServ").on("click", function() {
            var inputDescripcion = $('#inputDescripcion').val().trim();
            var selectCodigo = $('#selectCodigo').val();
            var selectEstado = $('#selectEstado').val();

            if (inputDescripcion === '') {
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

            $.post('/buscarServ', {
                    descripcion_serv: $('#inputDescripcion').val(),
                    codigo_serv: $('#selectCodigo').val(),
                    estado: $('#selectEstado').val(),
                    _token: '{{ csrf_token() }}'
                })
                .done(function(data) {
                    Swal.close();

                    if (data.length > 0) {
                        tblToAddServ.clear();
                        tblToAddServ.rows.add(data);
                        tblToAddServ.draw();

                        Swal.fire({
                            icon: 'success',
                            title: 'Búsqueda completada',
                            text: `Se encontraron ${data.length} resultados.`,
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        tblToAddServ.clear().draw();
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

        $("#btnAddServToFilter").on("click", function() {
            var tblToAddServ = $("#tblToAddServ").DataTable();
            var tblToQuitServ = $("#tblToQuitServ").DataTable();

            var items = tblToQuitServ.rows().data().toArray();

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
                    var tblFinalServFilter = $("#tblFinalServFilter").DataTable();

                    var existingData = tblFinalServFilter.rows().data().toArray();

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

                    tblFinalServFilter.rows.add(newItems).draw();

                    $('#inputDescripcion').val('');
                    $('#selectCodigo').val('');
                    $('#selectEstado').val('');

                    tblToAddServ.clear().draw();
                    tblToQuitServ.clear().draw();

                    $('#seleccionarServModal').modal('hide');

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

        $('#seleccionarServModal').on('hidden.bs.modal', function() {
            $('#inputDescripcion').val('');
            $('#selectCodigo').val('');
            $('#selectEstado').val('');

            var tblToAddServ = $("#tblToAddServ").DataTable();
            var tblToQuitServ = $("#tblToQuitServ").DataTable();

            tblToAddServ.clear().draw();
            tblToQuitServ.clear().draw();
        });
    });
</script>
<div class="modal fade" id="seleccionarServModal" tabindex="-1" role="dialog" aria-labelledby="seleccionarIPRESSLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header"
                style="background-color: ##3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="seleccionarIPRESSLabel">Seleccionar Servicio</h4>
            </div>

            <div class="modal-body">

                <div class="row" style="padding-bottom: 15px; border: 1px solid #ccc; margin: 0; padding-top: 15px;">
                    {{-- <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="selectCodigo" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Codigo</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <select class="form-control" id="selectCodigo">
                                    <option value="">- Todos -</option>
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="inputDescripcion" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Nombre</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <input type="text" class="form-control" id="inputDescripcion">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="selectEstado" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Estado</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <select class="form-control" id="selectEstado">
                                    <option value="">- Seleccionar -</option>
                                    <option value="4">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"
                    style="margin-top: 15px; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                    <div class="col-xs-6">
                        <button id="btnBuscarServ" type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                        </button>
                        <button id="btnAddServToFilter" type="button" class="btn btn-success">
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
                            <table id="tblToAddServ" class="table table-condensed toAdd" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Descripción</th>
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
                            <table id="tblToQuitServ" class="table table-condensed toQuit" style="margin-bottom: 0;">
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
