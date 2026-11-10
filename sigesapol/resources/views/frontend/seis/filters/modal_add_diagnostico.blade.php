<style>
    #tblToAddDiagnostico .btn:focus,
    #tblToAddDiagnostico .btn:active,
    #tblToQuitDiagnostico .btn:focus,
    #tblToQuitDiagnostico .btn:active {
        outline: none !important;
        box-shadow: none !important;
        border-color: transparent !important;
    }
</style>
<script>
    $(document).ready(function() {
        var tblToAddDiagnostico = $("#tblToAddDiagnostico").DataTable({
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
                    data: "nombre_diagnostico"
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
                            <button class="btn btn-primary btn-add-diag" 
                                    data-id="${row.id}" 
                                    style="width: 32px; height: 32px; border-radius: 8px; padding: 0; font-size: 17px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
                            </button>`;
                    },
                    className: "text-center"
                }
            ]
        });
        var tblToQuitDiagnostico = $("#tblToQuitDiagnostico").DataTable({
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
                    data: "nombre_diagnostico"
                },
                {
                    title: "Acción",
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-danger btn-remove-diag" 
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

        $('#tblToAddDiagnostico').on('click', '.btn-add-diag', function() {
            var row = tblToAddDiagnostico.row($(this).closest('tr'));
            var data = row.data();

            var exists = false;
            tblToQuitDiagnostico.rows().every(function() {
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

            tblToQuitDiagnostico.row.add(data).draw();
        });

        $('#tblToQuitDiagnostico').on('click', '.btn-remove-diag', function() {
            var row = tblToQuitDiagnostico.row($(this).closest('tr'));
            row.remove().draw();
        });

        $("#btnBuscarDiagnostico").on("click", function() {
            var descripcion = $('#descripcion_diagnostico').val().trim();
            var codigo = $('#codigo_diagnostico').val().trim();
            var sexo = $('#sexo_diagnostico').val().trim();
            var estado = $('#estado_diagnostico').val();

            if (descripcion === '' && codigo === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos requeridos',
                    text: 'Debes ingresar al menos la descripción o codigo para buscar.',
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

            $.post('/buscarDiagnostico', {
                    descripcion_diagnostico: $('#descripcion_diagnostico').val(),
                    estado: $('#estado_diagnostico').val(),
                    sexo_diagnostico: $('#sexo_diagnostico').val(),
                    codigo_diagnostico: $('#codigo_diagnostico').val(),
                    _token: '{{ csrf_token() }}'
                })
                .done(function(data) {
                    Swal.close();

                    if (data.length > 0) {
                        tblToAddDiagnostico.clear();
                        tblToAddDiagnostico.rows.add(data);
                        tblToAddDiagnostico.draw();

                        Swal.fire({
                            icon: 'success',
                            title: 'Búsqueda completada',
                            text: `Se encontraron ${data.length} resultados.`,
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        tblToAddDiagnostico.clear().draw();
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

        $("#btnAddDiagToFilter").on("click", function() {
            var tblToAddDiagnostico = $("#tblToAddDiagnostico").DataTable();
            var tblToQuitDiagnostico = $("#tblToQuitDiagnostico").DataTable();

            var items = tblToQuitDiagnostico.rows().data().toArray();

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
                    var tblFinalDiagFilter = $("#tblFinalDiagFilter").DataTable();

                    var existingData = tblFinalDiagFilter.rows().data().toArray();

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

                    tblFinalDiagFilter.rows.add(newItems).draw();

                    $('#descripcion_diagnostico').val('');
                    $('#codigo_diagnostico').val('');
                    $('#sexo_diagnostico').val('');
                    $('#estado_diagnostico').val('');

                    tblToAddDiagnostico.clear().draw();
                    tblToQuitDiagnostico.clear().draw();

                    $('#seleccionarDiagModal').modal('hide');

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

        $('#seleccionarDiagModal').on('hidden.bs.modal', function() {
            $('#descripcion_diagnostico').val('');
            $('#codigo_diagnostico').val('');
            $('#sexo_diagnostico').val('');
            $('#estado_diagnostico').val('');

            var tblToAddDiagnostico = $("#tblToAddDiagnostico").DataTable();
            var tblToQuitDiagnostico = $("#tblToQuitDiagnostico").DataTable();

            tblToAddDiagnostico.clear().draw();
            tblToQuitDiagnostico.clear().draw();
        });
    });
</script>
<div class="modal fade" id="seleccionarDiagModal" tabindex="-1" role="dialog" aria-labelledby="seleccionarIPRESSLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header"
                style="background-color: #3c763d; border-color: #4F783B; color: white; border-bottom: 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="seleccionarIPRESSLabel">Seleccionar Diagnostico</h4>
            </div>

            <div class="modal-body">

                <div class="row" style="padding-bottom: 15px; border: 1px solid #ccc; margin: 0; padding-top: 15px;">

                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="sexo_diagnostico" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Sexo</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <select class="form-control" id="sexo_diagnostico">
                                    <option value="">- Seleccionar -</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                    <option value="3">Ambos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="codigo_diagnostico" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Codigo</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <input type="text" class="form-control" id="codigo_diagnostico">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="descripcion_diagnostico" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Descripción</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <input type="text" class="form-control" id="descripcion_diagnostico">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="padding: 5px 15px;">
                        <div class="form-group">
                            <label for="estado_diagnostico" class="control-label col-sm-4"
                                style="text-align: left; padding-left: 0;">Estado</label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <select class="form-control" id="estado_diagnostico">
                                    <option value="">- Seleccionar -</option>
                                    <option value="1">Activo</option>
                                    <option value="0">No activo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"
                    style="margin-top: 15px; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                    <div class="col-xs-6">
                        <button id="btnBuscarDiagnostico" type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                        </button>
                        <button id="btnAddDiagToFilter" type="button" class="btn btn-success">
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
                            <table id="tblToAddDiagnostico" class="table table-condensed toAdd" style="margin-bottom: 0;">
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
                            <table id="tblToQuitDiagnostico" class="table table-condensed toQuit" style="margin-bottom: 0;">
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
