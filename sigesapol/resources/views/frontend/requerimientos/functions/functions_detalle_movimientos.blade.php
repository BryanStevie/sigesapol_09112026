<script>
    $('#modalDetalleMovimientos').on('hidden.bs.modal', function() {});

    const tipos_requerimiento = {
        1: 'ANUAL',
        2: 'EXTRAORDINARIO',
        3: 'EMERGENCIA',
        4: 'OTRO',
    };
    const prioridad = {
        1: 'ALTA',
        2: 'MEDIA',
        3: 'BAJA',
    };
    const tipos_seleccion = {
        1: 'MENOR A 8 UIT',
        2: 'MAYOR A 8 UIT',
    };
    const tipos_proceso = {
        1: 'SUBASTA INVERSA ELECTRONICA',
        2: 'LICITACION PUBLICA',
        4: 'LICITACION PUBLICA ABREVIADA',
        3: 'COMPRA DIRECTA',
    };

    /* $('#btnEnviarRequerimiento').on('click', function() {
        const tablaReqDet = $("#tableRequerimientos").DataTable();

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'No, cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/requerimientos_pfdm/actualizar',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        req_id: $('#req_id-det').val(),
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Actualizando...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado',
                            text: 'Los cambios se guardaron correctamente'
                        });
                        $('#modalDetalleRequerimiento').modal('hide');
                        tablaReqDet.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un problema al actualizar'
                        });
                    }
                });
            } else if (result.isDismissed) {
                console.log('Cancelado');
            }
        });
    }); */

    function cargarDetalleRequerimiento(data) {
        $("#btnEnviarRequerimiento").prop("disabled", true);
        $("#info_req-anulado").show();
        $("#nro_req-det").html(data.numero_requerimiento);
        $("#fecha_registro-det").html(data.created_at);
        $("#nro_pedido_siga-det").html(data.numero_pedido_siga);
        $("#nro_ht_sgd-det").html(data.numero_sgd_ht);
        $("#nro_informe-det").html(data.numero_informe);
        $("#nro_oficio-det").html(data.numero_oficio);
        $("#tipo_requerimiento-det").html(tipos_requerimiento[data.id_tipo_requerimiento]);
        $("#tipo_prioridad-det").html(prioridad[data.prioridad]);
        $("#tipo_seleccion-det").html(tipos_seleccion[data.id_procedimiento_seleccion]);
        $("#tipo_proceso-det").html(tipos_proceso[data.id_tipo_proceso] ?? "Sin data.");
        $("#tipo_producto-det").html(data.tipo_producto?.nombre);
        $("#denominacion_req-det").html(data.denominacion);
        $("#req_id-det").val(data.id);
        $("#container-ver_movimientos").html(
            `<a href="/requerimiento/ver-movimientos/${data.id}" class="btn btn-success" target="_blank">
                <i class="fa fa-eye" aria-hidden="true"></i>
                </a>`);
    }

    function obtenerIcono(etapa) {

        if (etapa == 1) {
            return 'glyphicon glyphicon-pencil';
        }

        if (etapa > 1 && etapa < 5) {
            return 'glyphicon glyphicon-list-alt';
        }

        if (etapa > 5) {
            return 'glyphicon glyphicon-user';
        }

        return 'glyphicon glyphicon-ok-circle';
    }

    function obtenerEstado(id) {

        const estados = {
            1: 'REGISTRADO',
            2: 'REMITIDO',
            3: 'RECIBIDO',
            4: 'ARCHIVADO',
            5: 'ENVIADO',
            6: 'OBSERVADO',
            7: 'DEVUELTO',
            8: 'ANULADO',
            9: 'EN PROCESO'
        };

        return estados[id] || 'DESCONOCIDO';
    }

    function renderTimeline(movimientos) {

        let html = '';

        let col = Math.floor(12 / movimientos.length);

        if (col <= 0) {
            col = 3;
        }

        movimientos.forEach(function(movimiento) {

            html += `
            <div class="col-xs-${col} paso">

                <div class="icono">
                    <i class="${obtenerIcono(movimiento.estado_id)}"></i>
                </div>

                <p>${obtenerEstado(movimiento.estado_id)}</p>

                <small>${movimiento.observacion}</small>

            </div>
        `;
        });

        $('#timeline-ap').html(html);
    }

    $(document).on('click', '.btn-detalle-req', function() {
        $("#tituloModalDetalleRequerimiento").html("Detalle Movimientos");
        const req_id = $(this).data("id");

        $.ajax({
            url: `/ver-requerimiento_pfdm/${req_id}`,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Cargando informacion...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.message
                });
                $("#modalDetalleMovimientos").modal("show");
                const req_data = response.data ?? null;
                if (req_data) {
                    renderTimeline(req_data.movimientos);
                    cargarDetalleRequerimiento(response.data);
                } else {
                    alert("No existe data, reportar error!!!");
                }
            },
            error: function() {
                let errors = xhr.responseJSON.errors;
                let mensaje = 'Ocurrió un error';

                if (errors) {
                    mensaje = Object.values(errors).map(e => e[0]).join('<br>');
                } else if (xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: mensaje
                });
            },
        });
    });
</script>
