<script>
    $('#modalDetalleRequerimiento').on('hidden.bs.modal', function() {
        $("#tblRowEntregas-det").html("");
    });

    $('#btnEnviarRequerimiento').on('click', function() {
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
    });

    function cargarDetalleRequerimiento(data) {
        $("#btnEnviarRequerimiento").prop("disabled", true);
        $("#info_req-anulado").show();
        $("#nro_req-det").html(data.numero_requerimiento);
        $("#fecha_registro-det").html(data.created_at);
        $("#tipo_producto-det").html(data.tipo_producto?.nombre);
        $("#nro_pedido_siga-det").html(data.numero_pedido_siga);
        $("#denominacion_req-det").html(data.denominacion);
        $("#pfdm_nombre-det").val(`${data.producto.codigo} - ${data.producto.desc_trama ?? data.producto.nombre}`);
        $("#codigo_siga-producto-det").val(data.producto.codigo_siga);
        $("#codigo_sismed-producto-det").val(data.producto.codigo_sismed);
        $("#unidad_medida-producto-det").val(data.unidad_medida);
        $("#cantidad_total-det").val(data.cantidad_total);
        $("#req_id-det").val(data.id);
        const detalles = data.detalles;
        detalles.forEach((detalle) => {
            $("#tblRowEntregas-det").append(`<tr>
                    <td>
                        <div>
                            <span>Nro Entrega ${detalle.numero_entrega}</span>
                            <input type="hidden" id="numero_entrega1" name="numero_entrega[]" value="1">
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>Cantidad</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control" style="width:100%;" disabled value="${detalle.cantidad_parcial}">
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>N° Dias de Entrega</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control" style="width:100%;" disabled value="${detalle.numero_dias}">
                        </div>
                    </td>
                </tr>`);
        });
        if (data.estado_id == 1) {
            $("#container_estado-recepcionar").hide();
            $("#container_button-recepcionar").show();
            $("#btnEnviarRequerimiento").prop("disabled", false);
            $("#info_req-anulado").hide();
        } else if (data.estado_id == 8) {
            $("#container_estado-recepcionar").show();
            $("#container_button-recepcionar").hide();
            $("#descripcion_estado-req").html("ANULADO");
            $("#descripcion_estado-req").prop("style", "color: red;");
        } else if (data.estado_id == 2) {
            $("#container_estado-recepcionar").show();
            $("#container_button-recepcionar").hide();
            $("#descripcion_estado-req").html("ENVIADO");
            $("#descripcion_estado-req").prop("style", "color: green;");
        }
    }

    function cargarDetalleRequerimientoToEdit(data) {
        if (data.tipo_documento == "HT" || data.tipo_documento == "TH") {
            $("#td_th").trigger("click");
            $("#td_descripcion_th").val(data.numero_sgd_ht);
        } else if (data.tipo_documento == "SGD") {
            $("#td_sgd").trigger("click");
            $("#td_descripcion_sgd").val(data.numero_sgd_ht);
        }

        var option = new Option(`${data.producto.codigo} - ${data.producto.desc_trama ?? data.producto.nombre}`, data.producto.id, true, true);
        $("#buscadorPfDm").append(option).trigger("change");
        $("#buscadorPfDm").prop("disabled", true);
        $("#pfdm_id").val(data.producto.id);

        var option2 = new Option(`${data.id_responsable} - ${data.id_responsable}`, data.id_responsable, true, true);
        $("#destino_responsable").append(option2).trigger("change");
        $("#responsable_id").val(data.id_responsable);

        $("#nro_requerimiento").val(data.numero_requerimiento);
        $("#nro_oficio").val(data.numero_oficio);
        $("#fecha_oficio").val(data.fecha_oficio);
        $("#nro_informe").val(data.numero_informe);
        $("#fecha_informe").val(data.fecha_informe);
        $("#tipo_requerimiento").val(data.id_tipo_requerimiento).change();
        $("#obs_requerimiento").val(data.observacion_requerimiento);
        $("#obs_prioridad").val(data.observacion_prioridad);
        $("#nro_pedido_siga").val(data.numero_pedido_siga);
        $("#codigo_siga-producto").val(data.producto.codigo_siga);
        $("#codigo_sismed-producto").val(data.producto.codigo_sismed);
        $("#unidad_medida-producto").val(data.unidad_medida);
        $("#total_entregas").val(data.detalles.length);
        $("#cantidad_total").val(data.cantidad_total);
        $("#denominacion_requerimiento").val(data.denominacion);
        $("#tipo_prioridad").val(data.prioridad).change();
        $("#proc_seleccion").val(data.id_procedimiento_seleccion).change();
        $("#tipo_proceso").val(data.id_tipo_proceso).change();
        $("#servicio_producto").val(data.id_servicio).change();
        $("#tipo_producto").val(data.id_tipo_producto).change();
        $("#destino_unidad").val(1).change();
        $("#destino_etapa").val(1).change();
        /* $("#tipo_producto-det").html(data.tipo_producto?.nombre);
        $("#nro_pedido_siga-det").html(data.numero_pedido_siga);
        $("#denominacion_req-det").html(data.denominacion); */
        $("#req_id_upt").val(data.id);
        $("#tblRowEntregas").html("");
        $("#container-destino-requerimiento").hide("");
        const detalles = data.detalles;
        detalles.forEach((detalle, i) => {
            $("#tblRowEntregas").append(`<tr>
                    <td>
                        <div>
                            <span>Nro Entrega ${detalle.numero_entrega}</span>
                            <input type="hidden" id="numero_entrega${(i + 1)}" name="numero_entrega[]" value="${(i + 1)}">
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>Cantidad</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control" style="width:100%;"
                                id="cantidad_entrega${(i + 1)}" name="cantidad_entrega[]"
                                value="${detalle.cantidad_parcial}">
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>N° Dias de Entrega</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control" style="width:100%;" 
                                id="dias_entrega${(i + 1)}" name="dias_entrega[]"
                                value="${detalle.numero_dias}">
                        </div>
                    </td>
                </tr>`);
        });
        /* if (data.estado_id == 1) {
            $("#container_estado-recepcionar").hide();
            $("#container_button-recepcionar").show();
            $("#btnEnviarRequerimiento").prop("disabled", false);
            $("#info_req-anulado").hide();
        } else if (data.estado_id == 8) {
            $("#container_estado-recepcionar").show();
            $("#container_button-recepcionar").hide();
            $("#descripcion_estado-req").html("ANULADO");
            $("#descripcion_estado-req").prop("style", "color: red;");
        } else if (data.estado_id == 2) {
            $("#container_estado-recepcionar").show();
            $("#container_button-recepcionar").hide();
            $("#descripcion_estado-req").html("ENVIADO");
            $("#descripcion_estado-req").prop("style", "color: green;");
        } */
    }

    $(document).on('click', '.btn-editar-req', function() {
        $("#tituloModalRequerimiento").html("Editar Requerimiento");
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
                $("#modalRequerimiento").modal("show");
                const req_data = response.data ?? null;
                if (req_data) {
                    cargarDetalleRequerimientoToEdit(response.data);
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

    $(document).on('click', '.btn-detalle-req', function() {
        $("#tituloModalDetalleRequerimiento").html("Detalle Requerimiento");
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
                $("#modalDetalleRequerimiento").modal("show");
                const req_data = response.data ?? null;
                if (req_data) {
                    console.log(response.data.producto);
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
