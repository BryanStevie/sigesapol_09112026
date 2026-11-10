<script>
    $(document).ready(function() {

        $("#obs_requerimiento").hide();
        $('label[for="obs_requerimiento"]').hide();

        $("#tipo_requerimiento").on("change", function(e) {
            const requiereObs = ($(this).val() == "1" || $(this).val() == "") ? false : true;
            if (requiereObs) {
                $("#obs_requerimiento").show();
                $('label[for="obs_requerimiento"]').show();
            } else {
                $("#obs_requerimiento").hide();
                $('label[for="obs_requerimiento"]').hide();
            }
        });

        $('#fecha_oficio').on('change', function() {
            let fechaIngresada = $(this).val();

            if (fechaIngresada) {
                let hoy = new Date().toISOString().split('T')[0];

                if (fechaIngresada > hoy) {
                    $(this).val('');

                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'Solo puedes ingresar una fecha menor o igual a la fecha actual'
                    });
                }
            }
        });

        $('#fecha_informe').on('change', function() {
            let fechaIngresada = $(this).val();

            if (fechaIngresada) {
                let hoy = new Date().toISOString().split('T')[0];

                if (fechaIngresada > hoy) {
                    $(this).val('');

                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'Solo puedes ingresar una fecha menor o igual a la fecha actual'
                    });
                }
            }
        });

        $('#modalRequerimiento').on('hidden.bs.modal', function() {
            $("#tituloModalRequerimiento").html("");
            $("#td_th").trigger("click");
            $('#frmSubmitReq')[0].reset();
            $('#pfdm_id').val("");
            $('#responsable_id').val("");
            $('#req_id_upt').val("");
            $('#buscadorPfDm').val(null).trigger('change');
            $("#buscadorPfDm").prop("disabled", false);
            $('#destino_responsable').val(null).trigger('change');
            $('#servicio_producto').val(null).trigger('change');
            $("#fechaRequerimiento").prop("disabled", false);
            $("#th_descripcion").prop("disabled", false);
            $("#tipo_proceso").prop("disabled", true);
            $("#tipo_proceso").removeClass("required");
            $("#col-tipo_proceso").hide();
            $("#servicio_producto").prop("disabled", false);
            $("#servicio_producto").addClass("required");
            $("#col-servicio_producto").show();
            $(this).find('.required').each(function() {
                $(this).siblings('.error-text').hide();
                $(this).removeClass('input-error');
            });
            $("#fieldsetTableEntregas").removeClass("input-error");
            $("#errorEntregas").hide();
            $("#container-destino-requerimiento").show("");
            $("#tblRowEntregas").html("");
            /* $("#tblRowEntregas").html(`<tr>
                    <td>
                        <div>
                            <span>Nro Entrega 1</span>
                            <input type="hidden" id="numero_entrega1" name="numero_entrega[]" value="1">
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>Cantidad</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control required" style="width:100%;"
                                id="cantidad_entrega1" name="cantidad_entrega[]">
                            <span class="error-text">Debe ingresar el campo</span>

                        </div>
                    </td>
                    <td>
                        <div>
                            <span>N° Dias de Entrega</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control required" style="width:100%;"
                                id="dias_entrega1" name="dias_entrega[]">
                            <span class="error-text">Debe ingresar el campo</span>
                        </div>
                    </td>
                </tr>`); */
            $(".required_pfdm").next().hide();
        });

        $("#td_sgd").on('change', function() {
            $("#td_descripcion_sgd").removeClass("input-error");
            $("#td_descripcion_sgd").siblings(".error-text").hide();
            if ($(this).is(':checked')) {
                $("#td_descripcion_th").prop("disabled", true);
                $("#td_descripcion_sgd").prop("disabled", false);
                $("#td_descripcion_sgd").addClass("required");
                $("#td_descripcion_th").removeClass("required");
                $("#container-desc_th").hide();
                $("#container-desc_sgd").show();
                $("#td_descripcion_th").val("");
                $("#td_descripcion_sgd").val("");
            }
        });

        $("#td_th").on('change', function() {
            $("#td_descripcion_th").removeClass("input-error");
            $("#td_descripcion_th").siblings(".error-text").hide();
            if ($(this).is(':checked')) {
                $("#td_descripcion_th").prop("disabled", false);
                $("#td_descripcion_sgd").prop("disabled", true);
                $("#td_descripcion_th").addClass("required");
                $("#td_descripcion_sgd").removeClass("required");
                $("#container-desc_th").show();
                $("#container-desc_sgd").hide();
                $("#td_descripcion_th").val("");
                $("#td_descripcion_sgd").val("");
            }
        });

        $("#proc_seleccion").on("change", function() {
            const proc_seleccion = parseInt($(this).val());
            $("#tipo_proceso").removeClass("input-error");
            $("#tipo_proceso").siblings(".error-text").hide();
            if (proc_seleccion === 2) {
                $("#tipo_proceso").prop("disabled", false);
                $("#tipo_proceso").addClass("required");
                $("#col-tipo_proceso").show();
            } else {
                $("#tipo_proceso").prop("disabled", true);
                $("#tipo_proceso").removeClass("required");
                $("#col-tipo_proceso").hide();
            }
        });

        $("#tipo_producto").on("change", function() {
            const tipo_producto = parseInt($(this).val());
            $("#servicio_producto").removeClass("input-error");
            $("#servicio_producto").siblings(".error-text").hide();
            if (tipo_producto === 1) {
                /* $("#servicio_producto").prop("disabled", true);
                $("#servicio_producto").removeClass("required");
                $("#col-servicio_producto").hide(); */
            } else {
                $("#servicio_producto").prop("disabled", false);
                $("#servicio_producto").addClass("required");
                $("#col-servicio_producto").show();
            }
        });

        $("#frmSubmitReq").on("submit", function(e) {
            e.preventDefault();

            const tablaReqSub = $("#tableRequerimientos").DataTable();
            let isValid = true;

            $(".required").removeClass("input-error");
            $(".error-text").hide();

            $(".required").each(function() {
                let value = $(this).val().trim();
                if (value === "") {
                    $(this).addClass("input-error");
                    $(this).siblings(".error-text").show();
                    isValid = false;
                }
            });

            $(".required_pfdm").each(function() {
                let value = $(this).val().trim();
                if (value === "") {
                    $(this).siblings(".error-text").show();
                    isValid = false;
                }
            });

            if ($("#tblRowEntregas tr").length == 0) {
                $("#fieldsetTableEntregas").addClass("input-error");
                $("#errorEntregas").show();
                isValid = false;
            }


            if (!isValid) {
                return;
            }

            let formD = $(this)[0];
            let form = $(this);
            let formData = new FormData(formD);
            let obj = Object.fromEntries(formData.entries());

            $.ajax({
                url: obj.req_id_upt == "" ? "/guardar-requerimiento_pfdm" : "/actualizar-requerimiento_pfdm",
                type: "POST",
                data: form.serialize(),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Guardando...',
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
                    form.trigger("reset");
                    $("#modalRequerimiento").modal("hide");
                    tablaReqSub.ajax.reload();
                },
                /* error: function() {
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
                }, */
                error: function(xhr) { // IMPORTANTE: Agregamos 'xhr' como parámetro
                    let mensaje = 'Ocurrió un error inesperado';

                    // Si el servidor responde con errores de validación (Estado 422)
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;

                        // Convertimos el objeto de errores en una lista legible
                        // Object.values obtiene los arrays de mensajes, tomamos el primero de cada uno
                        mensaje = Object.keys(errors).map(function(key) {
                            return '<li>' + errors[key][0] + '</li>';
                        }).join('');

                        mensaje = '<ul style="text-align:left;">' + mensaje + '</ul>';
                    }
                    // Si el servidor responde con un mensaje de error genérico (Estado 500 u otros)
                    else if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: '¡Atención!',
                        html: mensaje, // Usamos 'html' para que las etiquetas <ul> y <li> se vean bien
                        confirmButtonText: 'Entendido'
                    });
                },
            });
        });

        $('#modalRequerimiento').on('shown.bs.modal', function() {
            $(this)
                .find('input:not([type="hidden"]), select, textarea')
                .filter(':visible:enabled:first')
                .focus();
            $('#buscadorPfDm').select2({
                dropdownParent: $('#modalRequerimiento'),
                width: '100%',
                placeholder: 'Busque por codigo o nombre del producto...',
                minimumInputLength: 2,
                ajax: {
                    url: '/buscar-pfdm',
                    type: 'GET',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: `${item.codigo} - ${item.nombre}`,
                                codigo_sismed: item.codigo_sismed,
                                codigo_siga: item.codigo_siga,
                                unidad_medida: item.unidad_medida,
                            }))
                        };
                    },
                    cache: true
                }
            });

            $('#buscadorPfDm').on('select2:select', function(e) {
                let data = e.params.data;

                $('#pfdm_id').val(data.id || '');
                $('#codigo_siga-producto').val(data.codigo_siga || '');
                $('#codigo_sismed-producto').val(data.codigo_sismed || '');
                $('#unidad_medida-producto').val(data.unidad_medida || '');
                $('#unidad_medida').val(data.unidad_medida || '');

                $(".required_pfdm").next().hide();
            });

            $('#destino_responsable').select2({
                dropdownParent: $('#modalRequerimiento'),
                width: '100%',
                placeholder: 'Busque por nombre o dni del responsable...',
                minimumInputLength: 2,
                ajax: {
                    url: '/buscar-responsable',
                    type: 'GET',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: `${item.dni} - ${item.name}, ${item.paterno} ${item.materno}`,
                            }))
                        };
                    },
                    cache: true
                }
            });

            $('#destino_responsable').on('select2:select', function(e) {
                let data = e.params.data;

                $('#responsable_id').val(data.id || '');
                $(".required_pfdm").next().hide();
            });
        });

        $("#total_entregas").on("change", function() {
            $('input[name="cantidad_entrega[]"]').val('');
            let nro_entregas = parseInt($(this).val()) || 1;
            let tbody = $("#tableEntregas tbody");
            let filasActuales = tbody.find("tr").length;

            if (nro_entregas < 1) nro_entregas = 1;

            if (nro_entregas > filasActuales) {
                for (let i = filasActuales + 1; i <= nro_entregas; i++) {
                    tbody.append(`
                <tr>
                    <td>
                        <div>
                            <span>Nro Entrega ${i}</span>
                            <input type="hidden" id="numero_entrega${i}" name="numero_entrega[]" value="${i}">
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>Cantidad</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control required" style="width:100%;"
                                id="cantidad_entrega${(i)}" name="cantidad_entrega[]">
                            <span class="error-text">Debe ingresar el campo</span>

                        </div>
                    </td>
                    <td>
                        <div>
                            <span>N° Dias de Entrega</span>
                        </div>
                        <div>
                            <input type="text" data-placement="top"
                                class="input-sm form-control required" style="width:100%;"
                                id="dias_entrega${(i)}" name="dias_entrega[]">
                            <span class="error-text">Debe ingresar el campo</span>
                        </div>
                    </td>
                </tr>
            `);
                }
            }

            if (nro_entregas < filasActuales) {
                for (let i = filasActuales; i > nro_entregas; i--) {
                    tbody.find("tr").last().remove();
                }
            }
        });

        $('#total_entregas').on('keydown', function(e) {
            const allowedKeys = ['ArrowUp', 'ArrowDown', 'Tab'];

            if (!allowedKeys.includes(e.key)) {
                e.preventDefault();
            }
        });

        $('#servicio_producto').select2({
            dropdownParent: $('#modalRequerimiento'),
            placeholder: 'Buscar servicio...',
            allowClear: true,
            width: '100%'
        });
    });
</script>
