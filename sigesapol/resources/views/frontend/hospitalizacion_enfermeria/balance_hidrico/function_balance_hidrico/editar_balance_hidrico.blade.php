<script>
    $(document).on('click', '[id^=add_tabla_editar_]', function() {
        var idString = $(this).attr('id').replace('add_tabla_editar_', '');
        var tabla_tipo = $(this).data('tabla_tipo');
        var secuencia = $(this).data('secuencia');
        var id = parseInt(idString, 10);
        var classList = $(this).attr('class').split(' ');
        // Obtener la última clase del array
        var tipo_balance = classList[classList.length - 1];

        let nombre_campo = '';
        let descripcion_caracter_fisico = '';
        let descripcion_caracter_fisico_id = '';
        let valor_tabla = '';
        let id_caracter_fisico = '';
        let tipo_tabla = 0;

        if (tipo_balance === 'ingreso_editar') {
            tipo_balance = 'ingreso_editar';
            nombre_campo = 'valor_ingreso';
            descripcion_caracter_fisico = 'descripcion_caracter_fisico_ingreso';
            descripcion_caracter_fisico_id = 'descripcion_caracter_fisico_id_ingreso';
            valor_tabla = 'valor_tabla_ingreso';
            id_caracter_fisico = 'id_caracter_fisico_ingreso';
        } else if (tipo_balance === 'egreso_editar') {
            tipo_balance = 'egreso_editar';
            nombre_campo = 'valor_egreso';
            descripcion_caracter_fisico = 'descripcion_caracter_fisico_egreso';
            descripcion_caracter_fisico_id = 'descripcion_caracter_fisico_id_egreso';
            valor_tabla = 'valor_tabla_egreso';
            id_caracter_fisico = 'id_caracter_fisico_egreso';
        }
        if (tabla_tipo) {
            tipo_tabla = tabla_tipo;
        }

        lista_table_balanceH(
            'tbl_tabla_' + id,
            descripcion_caracter_fisico,
            descripcion_caracter_fisico_id,
            nombre_campo,
            'id_relacionado_tabla_' + id,
            'boton_eliminar_item_tabla_' + id,
            tipo_balance,
            valor_tabla,
            id_caracter_fisico,
            id,
            tipo_tabla,
            secuencia
        );
        delete_table_tr_campo_balance('tbl_tabla_' + id, 'boton_eliminar_item_tabla_' + id, '');
    });
    /****************ELIMINAR TR DE REGISTROS QUE VIENEN DE LA TABLA*********************/
    $(document).on('click', '.boton_eliminar_item_tabla', function() {
        var $registro = $(this).closest('tr');
        $registro.css('text-decoration', 'line-through');
        $registro.css('color', 'red');
        $registro.find('input[name="estado[]"]').val('0');
        var id_bh = $registro.find('input#id_bh').val();
        var route = '{{ url('anular_item_balance_detalle') }}';
        Swal.fire({
            title: "¿Esta seguro de anular el registro?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, anular",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id_bh
                    },
                    success: function(data) {
                        if (data != null) {
                            if (data != 0) {
                                $registro.remove();
                                actualizarTotalIngreso2();
                                actualizarTotalEgreso2();
                                console.log('Se ha anulado el registro');
                            } else {
                                swal({
                                    buttons: false,
                                    title: "Error de Servicio de Registros",
                                    text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                                    icon: "error",
                                    timer: 2500,
                                });
                            }
                        } else {
                            swal({
                                buttons: false,
                                title: "Error de Servicio de Registros",
                                text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                                icon: "error",
                                timer: 2500,
                            });
                        }
                    },
                    error: function(xhr) {
                        alert('Error en la solicitud');
                    }
                });
            } else {
                Swal.fire({
                    title: "Operación Cancelada",
                    text: "Proceso de Registro Cancelado.",
                    icon: "error",
                    timer: 1500,
                    showConfirmButton: false
                });
                $registro.css('text-decoration', '');
                $registro.css('color', '');
            }
        });
    });
    /**************************************************/

    $('body').on('click', '#editarBalanceHidrico', function() {

        $('#FormularioBalanceHidricoEditar').trigger("reset");
        $('#tituloEditarFormularioBalanceHidrico').html("Editar Balance Hídrico");
        $('#modalBalanceHidricoEditar').modal('show');
        $("table[id^='tbl_tabla_'] tbody").empty();
        var id_turno = $(this).data('id_turno');

        var id_balance = $(this).data('id_balance');
        $('#id_balance2').val(id_balance);

        var id_asegurado = $('#id_asegurado_bh').val();
        var id_area = $('#id_area')
            .val(); //para validar si es UVI=1 o SHOCK TRAUMA=12 PARA QUE LE TRAIGA EN TURNOS MADRUGADA

        Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
        cargarDatosGlobalesEditar(id_balance, id_asegurado, id_area, id_turno);
        Swal.fire({
            icon: "success",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
            confirmButtonText: "ENTENDIDO",
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: "ENTENDIDO",
            timer: 2000,
            timerProgressBar: true,
        });
    });

    $(document).ready(function() {
        document.getElementById('peso_kg_editar').addEventListener('input', function(e) {
            const value = e.target.value;
            const regex = /^\d*\.?\d{0,2}$/;
            if (!regex.test(value)) {
                e.target.value = value.slice(0, -1);
            }
            calculo_horas_peso('peso_kg_editar', 'hora_registro_editar', 'INGRESO_AO_EDITAR',
                'INGRESO_PI_EDITAR');
            calculo_horas_peso('peso_kg_editar', 'hora_registro_editar', 'INGRESO_AO2_EDITAR',
                'INGRESO_PI2_EDITAR');
            actualizarTotalIngreso2();
            actualizarTotalEgreso2();
        });
        $('#hora_bh_editar').on('change', function() {
            var horaSeleccionada = $(this).val();
            var partes = horaSeleccionada.split(':');
            var horas = parseInt(partes[0]);
            var minutos = parseInt(partes[1]);
            var totalMinutos = horas * 60 + minutos; // Convertimos horas y minutos a minutos totales
            if (totalMinutos >= 450 && totalMinutos <= 809) { // 07:30 - 13:29
                $('#turno_bh_editar').val(2);
                $('#turnos_editar').val(2);
            } else if (totalMinutos >= 810 && totalMinutos <= 1169) { // 13:30 - 19:29
                $('#turno_bh_editar').val(3);
                $('#turnos_editar').val(3);
            } else if (totalMinutos >= 1170 && totalMinutos <= 1439) { // 19:30 - 23:59
                $('#turno_bh_editar').val(4);
                $('#turnos_editar').val(4);
            } else { // 00:00 - 07:29
                $('#turno_bh_editar').val(1);
                $('#turnos_editar').val(1);
            }
        });
        $('#actualizarBalanceHidrico').click(function(e) {
            let valid = true;
            // Validación de campos de ingreso_editar y egreso_editar

            $('#modalBalanceHidricoEditar').find(
                'input[name="valor_ingreso[]"], input[name="valor_egreso[]"]').each(function() {
                if ($(this).val() < 0 || $(this).val() === '') {
                    valid = false;
                    $(this).addClass('is-invalid');
                    console.log('Campo con valor inválido:', $(this).val(),
                        ' en el campo con id:', $(this).attr('id'));

                    Swal.fire({
                        title: "Información de Servicio de Registros",
                        text: "Todos los campos de cantidad de ingresos y egresos deben ser 0 ó mayores",
                        icon: "info",
                        timer: 4000,
                        showConfirmButton: true
                    });
                    return false; // salir del bucle each
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Validación del campo hora_registro
            if ($('#hora_registro_editar').val() <= 0 || $('#hora_registro_editar').val() === '') {
                valid = false;
                Swal.fire({
                    title: "Información de Servicio de Registros",
                    text: "El campo de hora debe ser mayor a 0",
                    icon: "info",
                    timer: 4000,
                    showConfirmButton: true
                });
            }


            if (valid) {
                e.preventDefault(); // prevenir el envío del formulario

                var token = $('#_token').val();
                var route = '{{ url('actualizar_balance_hidrico') }}';

                Swal.fire({
                    title: "¿Desea actualizar el Registro?",
                    text: "Recuerde verificar que todos los datos sean correctos.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, registrar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData($("#FormularioBalanceHidricoEditar")[0]);
                        $.ajax({
                            data: formData,
                            url: route,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': token
                            },
                            type: 'POST',
                            enctype: 'multipart/form-data',
                            dataType: 'JSON',
                            success: function(data) {
                                if (data != null) {
                                    if (data.existe_balance) {
                                        Swal.fire({
                                            title: "Información de Servicio de Registros",
                                            text: "Ya existe un registro con el mismo turno",
                                            icon: "info",
                                            timer: 3000,
                                            showConfirmButton: true
                                        });
                                    } else {
                                        if (data != 0) {
                                            const $p = [
                                                $('#fecha_desde').val(),
                                                $('#fecha_hasta').val(),
                                                $('#turno_bh_editar').val(),
                                                $('#id_asegurado_bh').val()
                                            ];
                                            $('#FormularioBalanceHidricoEditar')
                                                .trigger("reset");
                                            $('#modalBalanceHidricoEditar').modal(
                                                'hide');
                                            /* $('#total_ingreso').val('0');
                                            $('#total_egreso').val('0'); */
                                            Swal.fire({
                                                title: "Registro Exitoso",
                                                text: "Datos registrados Exitosamente.",
                                                icon: "success",
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                            listar_balance($p);
                                        } else {
                                            Swal.fire({
                                                title: "Error de Servicio de Registros",
                                                text: "Verifique que todos los datos ingresados sean los correctos, en caso que el problema persista, comuníquese con el área de informática.",
                                                icon: "error",
                                                timer: 2500,
                                                showConfirmButton: false
                                            });
                                        }
                                    }
                                } else {
                                    Swal.fire({
                                        title: "Error de Servicio de Registros",
                                        text: "Verifique que todos los datos ingresados sean los correctos, en caso que el problema persista, comuníquese con el área de informática.",
                                        icon: "error",
                                        timer: 2500,
                                        showConfirmButton: false
                                    });
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Operación Cancelada",
                            text: "Proceso de Registro Cancelado.",
                            icon: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            } else {
                e.preventDefault(); // prevenir el envío del formulario si no es válido
            }
        });
    });

    /* $('#modalBalanceHidricoEditar').on('hidden.bs.modal', function () {
      const inputIngresoRegistrar = document.getElementById('total_ingreso');
      inputIngresoRegistrar.value = 0;
      const inputEgresoRegistrar = document.getElementById('total_egreso');
      inputEgresoRegistrar.value = 0;
      console.log(inputIngresoRegistrar)
      console.log(inputEgresoRegistrar)
    }); */

    function cargarDatosGlobalesEditar(id_balance, id_asegurado, id_area, id_turno) {
        var var_url = '{{ url('datos_globales_balance') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_asegurado: id_asegurado,
                id_area: id_area,
                id_balance: id_balance
            },
            success: function(data) {
                /***********DATOS DEL PACIENTE***********/
                $('#num_documento_bh_editar').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_bh_editar').html(data.datos_paciente.paterno + " " + data.datos_paciente
                    .materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_bh_editar').html(data.datos_paciente.tipo_sangre);
                $('#edad_bh_editar').html(data.datos_paciente.edad_corta);
                $('#sexo_bh_editar').html(data.datos_paciente.sexo);
                $('#hc_bh_editar').html(data.datos_paciente.nro_historia);
                $('#parentesgo_bh_editar').html(data.datos_paciente.parentesgo);
                $('#tipo_sangre_bh_editar').html(data.datos_paciente.tipo_sangre);
                $('#alergia_bh_hospi_edit').html(
                    data.datos_paciente.tiene_alergias
                        ? `<i style="color: blue;">SI</i>`
                        : `<i style="color: red;">NO</i>`
                );
                /*****************TURNOS**********/
                ;
                $("#turnos_editar").empty(); // Vaciar antes de añadir nuevos turnos
                $.each(data.turnos, function(key, value) {
                    $("#turnos_editar").append("<option value='" + value.id + "'>" + value
                        .descripcion + "</option>");
                });
                $('#turnos_editar').val(id_turno).change();
                $('#hora_bh_editar').val(data.hora_registro[0].hora_registro_bh.split('-')[0]);
                $('#turno_bh_editar').val((data.turno_seleccionado[0].turno_seleccionado));

                var peso = $('#peso').val();
                $('#peso_kg_editar').val((data.bh_general[0].peso_al_registrar_bh || peso));

                /***********CARACTER FISICO*********/
                let html_caracter_fisico = '';
                $("#balance_hidrico_editar").empty();
                html_caracter_fisico += '<div class="col-md-6">';
                html_caracter_fisico += '<div class="table-responsive">';
                html_caracter_fisico += '<div class="col-md-12" style="text-align: center;">';
                html_caracter_fisico += '<b>INGRESOS</b>';
                html_caracter_fisico += '</div>';
                html_caracter_fisico +=
                    '<table id="example" class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
                html_caracter_fisico += ' <tbody id="balance_ingreso">';

                let camposHidden = '';
                let total_ingresos = 0;
                $.each(data.caracter_fisico, function(key, value) {
                    if ([1].includes(value.secuencia)) {
                        html_caracter_fisico += '<tr>';
                        html_caracter_fisico += '<td colspan="2">';
                        html_caracter_fisico += '<input type="hidden"  name="secuencia[]" value="' +
                            value.secuencia + '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="id_detalle_ingreso[]" value="' + value.id +
                            '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="descripcion_ingreso[]" value="' + value
                            .descripcion + '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="es_visible[]" value="0">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="id_caracter_fisico_ingreso[]" value="' +
                            value.id_cf + '">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">' +
                            value.descripcion;
                        html_caracter_fisico += '</td>';
                        html_caracter_fisico += '<td colspan="2">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="valor_tabla_ingreso[]">';
                        html_caracter_fisico +=
                            '<input type="number" step="1"   class="form-control" name="valor_ingreso[]" id="hora_registro_editar" value="' +
                            value.valor_caracter_fisico + '">';
                        html_caracter_fisico += '</td>';
                        html_caracter_fisico += '</tr>';
                    }
                    if ([2].includes(value.secuencia)) {
                        html_caracter_fisico += '<tr>';
                        html_caracter_fisico += '<td colspan="2">';
                        html_caracter_fisico += '<input type="hidden"  name="secuencia[]" value="' +
                            value.secuencia + '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="id_detalle_ingreso[]" value="' + value.id +
                            '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="descripcion_ingreso[]" value="' + value
                            .descripcion + '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="es_visible[]" value="0">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="id_caracter_fisico_ingreso[]" value="' +
                            value.id_cf + '">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">' +
                            value.descripcion;
                        html_caracter_fisico += '</td>';
                        html_caracter_fisico += '<td colspan="2">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="valor_tabla_ingreso[]">';
                        html_caracter_fisico +=
                            '<input type="number" step="1"   class="form-control ingreso_editar"  name="valor_ingreso[]" id="INGRESO_AO_EDITAR" value="' +
                            value.valor_caracter_fisico + '" disabled>';
                        html_caracter_fisico +=
                            '<input type="hidden" name="valor_ingreso[]" id="INGRESO_AO2_EDITAR" value="' +
                            value.valor_caracter_fisico + '">';
                        html_caracter_fisico += '</td>';
                        html_caracter_fisico += '</tr>';
                    }
                    if ([3].includes(value.secuencia)) {
                        html_caracter_fisico += '<tr>';
                        html_caracter_fisico += '<td colspan="2">';
                        html_caracter_fisico += '<input type="hidden"  name="secuencia[]" value="' +
                            value.secuencia + '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="id_detalle_ingreso[]" value="' + value.id +
                            '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="descripcion_ingreso[]" value="' + value
                            .descripcion + '">';
                        html_caracter_fisico +=
                            '<input type="hidden"  name="es_visible[]" value="0">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="id_caracter_fisico_ingreso[]" value="' +
                            value.id_cf + '">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">' +
                            value.descripcion;
                        html_caracter_fisico += '</td>';
                        html_caracter_fisico += '<td colspan="2">';
                        html_caracter_fisico +=
                            '<input type="hidden" name="valor_tabla_ingreso[]">';
                        html_caracter_fisico +=
                            '<input type="number" step="1"   class="form-control egreso_editar"  name="valor_ingreso[]" id="INGRESO_PI_EDITAR" value="' +
                            value.valor_caracter_fisico + '" disabled>';
                        html_caracter_fisico +=
                            '<input type="hidden" name="valor_ingreso[]" id="INGRESO_PI2_EDITAR" value="' +
                            value.valor_caracter_fisico + '">';
                        html_caracter_fisico += ' </td>';
                        html_caracter_fisico += '</tr>';
                    }
                    if (value.tipo_balance == 1) { //INGRESO
                        if (!([1, 2, 3].includes(value.secuencia))) {
                          if (value.multiple == 1 && value.tabla == null) {

                              html_caracter_fisico += '<tr>';
                              html_caracter_fisico += '<td colspan="4">';
                              html_caracter_fisico +=
                                  '<table id="example" class="table table-list-search"style="margin-bottom: 0px !important;">';
                              html_caracter_fisico += '<tbody><tr><td>';
                              html_caracter_fisico +=
                                  '<a href="javascript:void(0)"  data-secuencia="' + value
                                  .secuencia + '"id="add_tabla_editar_' + value.id_cf +
                                  '" name="add_tabla_' + value.id_cf +
                                  '" class="btn btn-success btn-sm add_tabla_editar_' + value
                                  .id_cf + ' ingreso_editar">';
                              html_caracter_fisico += '<i class="fa fa-plus" ></i>' + value
                                  .descripcion_cf;
                              html_caracter_fisico += '</a>';
                              html_caracter_fisico += '</td>';
                              html_caracter_fisico += '</tr>';
                              html_caracter_fisico += '</tbody>';
                              html_caracter_fisico += '</table>';
                              html_caracter_fisico += '<table id="tbl_tabla_' + value.id_cf +
                                  '" style="margin-bottom: 10px !important; margin-left: 8px;" class="tablas"> ';
                              html_caracter_fisico += '<tbody id="cuerpo_tabla_' + value.id_cf +
                                  '" name="cuerpo_tabla_' + value.id_cf +
                                  '" class="cuerpo_tabla_' + value.id_cf + '">';
                              if (value.datos && value.datos.length > 0) {
                                  $.each(value.datos, function(index, item) {

                                      html_caracter_fisico +=
                                          '<tr class="limpiar_al_iniciar">';
                                      html_caracter_fisico +=
                                          '<td colspan="2" width="60%">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" id="estado"  name="estado[]" value="1">';
                                      html_caracter_fisico +=
                                          '<input type="hidden"  name="secuencia[]" value="' +
                                          item.secuencia + '">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" name="id_detalle_ingreso[]" value="' +
                                          item.id + '">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" name="es_visible[]" value="1">';
                                      if (item.id_cf <= 9) {
                                          html_caracter_fisico +=
                                              '<input type="hidden" name="descripcion_caracter_fisico_id_ingreso" value="' +
                                              item.id_cf + '0' + index + '">';
                                      } else if (item.id_cf >= 10 && item.id_cf <= 99) {
                                          html_caracter_fisico +=
                                              '<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]" value="' +
                                              item.id_cf + '' + index + '">';
                                      }
                                      total_ingresos += item.valor_caracter_fisico;
                                      html_caracter_fisico +=
                                          '<input type="hidden" name="descripcion_ingreso[]">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" id="id_bh" value="' + item
                                          .id + '">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" name="id_caracter_fisico_ingreso[]" value="' +
                                          item.id_cf + '">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" class="form-control id_relacionado_tabla_' +
                                          item.id_cf +
                                          ' typeahead tt-query" id="id_relacionado_tabla_' +
                                          index + '" name="id_relacionado_tabla_' + item
                                          .id_cf + '[]" data-id=' + index +
                                          ' style="height: 28px !important;" required>';
                                      html_caracter_fisico +=
                                          '<input type="hidden" name="valor_tabla_ingreso[]">';
                                      html_caracter_fisico +=
                                          '<input type="text" required="" name="descripcion_caracter_fisico_ingreso[]" id="descripcion_caracter_fisico_ingreso' +
                                          item.id_cf + '' + index +
                                          '" class="form-control descripcion_caracter_fisico_ingreso' +
                                          item.id_cf + '" value="' + item.descripcion +
                                          '">';
                                      html_caracter_fisico += '</td>';
                                      html_caracter_fisico += '<td colspan="2">';
                                      html_caracter_fisico +=
                                          '<input name="valor_ingreso[]" id="valor_ingreso' +
                                          index +
                                          '" type="number" step="1" type="text" class="form-control ingreso_editar" value="' +
                                          item.valor_caracter_fisico + '" required>';
                                      html_caracter_fisico += '</td>';
                                      html_caracter_fisico += '<td>';
                                      html_caracter_fisico +=
                                          '<button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_tabla btn-xs"  style="margin-left:4px"><i class="fa fa-times"></i></button>';
                                      html_caracter_fisico += '</td>';
                                      html_caracter_fisico += '</tr>';
                                  });
                              }
                              html_caracter_fisico += '</tbody>';
                              html_caracter_fisico += '</table>';
                              html_caracter_fisico += '</td>';
                              html_caracter_fisico += '</tr>';

                          } else if (value.multiple == null && value.tabla == 1) {

                              let item_valor_combo = '';
                              $.each(data.intervencion_terapeuticas, function(key, t) {
                                  if (value.tabla_tipo == t.tipo) {
                                      if (t.id === value.id_valor) {
                                          item_valor_combo += '<option value=' + t.id +
                                              ' selected>' + t
                                              .descripcion_intervencion_t + '</option>';
                                      } else {
                                          item_valor_combo += '<option value=' + t.id +
                                              '>' + t.descripcion_intervencion_t +
                                              '</option>';
                                      }
                                  }
                              });
                              html_caracter_fisico += '<tr>';
                              html_caracter_fisico += '<td colspan="2">';
                              html_caracter_fisico +=
                                  '<input type="hidden"  name="secuencia[]" value="' + value
                                  .secuencia + '">';
                              html_caracter_fisico +=
                                  '<input type="hidden"  name="id_detalle_ingreso[]" value="' +
                                  value.id + '">';
                              html_caracter_fisico +=
                                  '<input type="hidden"  name="descripcion_ingreso[]">';
                              html_caracter_fisico +=
                                  '<input type="hidden"  name="es_visible[]" value="1">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="id_caracter_fisico_ingreso[]" value="' +
                                  value.id_cf + '">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">';
                              html_caracter_fisico +=
                                  '<select class="form-control" name="valor_tabla_ingreso[]">' +
                                  item_valor_combo;
                              html_caracter_fisico += '</select>';
                              html_caracter_fisico += '</td>';
                              html_caracter_fisico += '<td colspan="2">';
                              html_caracter_fisico +=
                                  '<input type="number" step="1"   name="valor_ingreso[]" class="form-control ingreso_editar" value="' +
                                  value.valor_caracter_fisico + '" >';
                              html_caracter_fisico += '</td>';
                              html_caracter_fisico += '</tr>';

                              total_ingresos += value.valor_caracter_fisico;

                          } else if (value.multiple == 1 && value.tabla == 1) {

                              html_caracter_fisico += '<tr>';
                              html_caracter_fisico += '<td colspan="4">';
                              html_caracter_fisico +=
                                  '<table id="example" class="table table-list-search"style="margin-bottom: 0px !important;">';
                              html_caracter_fisico += '<tbody><tr><td>';
                              html_caracter_fisico +=
                                  '<a href="javascript:void(0)" data-secuencia="' + value
                                  .secuencia + '" data-tabla_tipo="' + value.tabla_tipo +
                                  '" id="add_tabla_editar_' + value.id_cf + '" name="add_tabla_' +
                                  value.id_cf +
                                  '" class="btn btn-success btn-sm add_tabla_editar_' + value
                                  .id_cf + ' ingreso_editar">';
                              html_caracter_fisico += '<i class="fa fa-plus" ></i>' + value
                                  .descripcion_cf;
                              html_caracter_fisico += '</a>';
                              html_caracter_fisico += '</td>';
                              html_caracter_fisico += '</tr>';
                              html_caracter_fisico += '</tbody>';
                              html_caracter_fisico += '</table>';
                              html_caracter_fisico += '<table id="tbl_tabla_' + value.id_cf +
                                  '" style="margin-bottom: 10px !important; margin-left: 8px;" class="tablas"> ';
                              html_caracter_fisico += '<tbody id="cuerpo_tabla_' + value.id_cf +
                                  '" name="cuerpo_tabla_' + value.id_cf +
                                  '" class="cuerpo_tabla_' + value.id_cf + '">';
                              if (value.datos && value.datos.length > 0) {
                                  $.each(value.datos, function(index, item) {
                                      let item_valor_combo = '';
                                      $.each(data.intervencion_terapeuticas, function(key,
                                          t) {
                                          let selected = "";
                                          if (item.tabla_tipo == t.tipo) {
                                              if (t.id === item.id_valor) {
                                                  selected = "selected";
                                              }
                                              item_valor_combo +=
                                                  '<option value=' + t.id + ' ' +
                                                  selected + ' >' + t
                                                  .descripcion_intervencion_t +
                                                  '</option>';
                                          }
                                      });
                                      html_caracter_fisico += '<tr>';
                                      html_caracter_fisico += '<td colspan="2">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" id="id_bh" value="' + item
                                          .id + '">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" id="estado"  name="estado[]" value="1">';
                                      html_caracter_fisico +=
                                          '<input type="hidden"  name="secuencia[]" value="' +
                                          item.secuencia + '">';
                                      html_caracter_fisico +=
                                          '<input type="hidden"  name="id_detalle_ingreso[]" value="' +
                                          value.id + '">';
                                      html_caracter_fisico +=
                                          '<input type="hidden"  name="descripcion_ingreso[]">';
                                      html_caracter_fisico +=
                                          '<input type="hidden"  name="es_visible[]" value="1">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" name="id_caracter_fisico_ingreso[]" value="' +
                                          item.id_cf + '">';
                                      html_caracter_fisico +=
                                          '<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">';
                                      html_caracter_fisico +=
                                          '<select class="form-control" name="valor_tabla_ingreso[]">' +
                                          item_valor_combo + '</select>';
                                      html_caracter_fisico += '</td>';
                                      html_caracter_fisico += '<td colspan="2">';
                                      html_caracter_fisico +=
                                          '<input type="number" step="1"   name="valor_ingreso[]" class="form-control ingreso_editar" value="' +
                                          item.valor_caracter_fisico + '">';
                                      html_caracter_fisico += '</td>';
                                      html_caracter_fisico += '<td>';
                                      html_caracter_fisico +=
                                          '<button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_tabla btn-xs" style="margin-left:4px"><i class="fa fa-times"></i></button>';
                                      html_caracter_fisico += '</td>';
                                      html_caracter_fisico += '</tr>';
                                      total_ingresos += item.valor_caracter_fisico;
                                  });
                              }
                              html_caracter_fisico += '</tbody>';
                              html_caracter_fisico += '</table>';
                              html_caracter_fisico += '</td>';
                              html_caracter_fisico += '</tr>';

                          } else {
                              html_caracter_fisico += '<tr>';
                              html_caracter_fisico +=
                                  '<td colspan="2"><input type="hidden"  name="descripcion_ingreso[]" value="' +
                                  value.descripcion + '">' + value.descripcion + '</td>';
                              html_caracter_fisico += '<td colspan="2">';
                              html_caracter_fisico +=
                                  '<input type="hidden"  name="secuencia[]" value="' + value
                                  .secuencia + '">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="id_detalle_ingreso[]" value="' +
                                  value.id + '">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="es_visible[]" value="1">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="id_caracter_fisico_ingreso[]" value="' +
                                  value.id_cf + '">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="valor_tabla_ingreso[]">';
                              html_caracter_fisico +=
                                  '<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">';
                              html_caracter_fisico +=
                                  '<input type="number" step="1" name="valor_ingreso[]" class="form-control ingreso_editar" value="' +
                                  value.valor_caracter_fisico + '">';
                              html_caracter_fisico += '</td>';
                              html_caracter_fisico += '</tr>';
                              total_ingresos += value.valor_caracter_fisico;
                          }
                        }
                    }
                });
                html_caracter_fisico += '</tbody>';
                html_caracter_fisico += '</table>';
                html_caracter_fisico +=
                    '<table id="example" class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
                html_caracter_fisico += '<tbody>';
                html_caracter_fisico += '<tr>';
                html_caracter_fisico += '<td style="width: 70%;"><b>TOTAL DE INGRESOS </b></td>';
                html_caracter_fisico += '<td>';
                html_caracter_fisico +=
                    '<input  class="form-control" style="height: 28px !important;" id="total_ingreso2" disabled></td>';
                html_caracter_fisico += '</tr>';
                html_caracter_fisico += '</tbody>';
                html_caracter_fisico += '</table>';
                html_caracter_fisico += '<hr>';
                html_caracter_fisico += '</div>';
                html_caracter_fisico += '</div>';

                html_caracter_fisico += '<div class="col-md-6">';
                html_caracter_fisico += '<div class="table-responsive">';
                html_caracter_fisico +=
                    '<div class="col-md-12" style="text-align: center;"><b>EGRESOS</b></div>';
                html_caracter_fisico +=
                    '<table id="example" class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
                html_caracter_fisico += '<thead>';
                html_caracter_fisico += '<tr>';
                html_caracter_fisico += '<th style="text-align: center;">DESCRIPCIÓN</th>';
                html_caracter_fisico += '<th style="text-align: center;">VALOR</th>';
                html_caracter_fisico += '</tr>';
                html_caracter_fisico += '</thead>';
                html_caracter_fisico += '<tbody id="balance_egreso">';
                let contador_multiple_egreso = 0;
                $.each(data.caracter_fisico, function(key, value) {

                    if (value.tipo_balance == 2) { //EGRESO
                        if (value.multiple == 1 && value.tabla == null) {
                            html_caracter_fisico += '<tr>';
                            html_caracter_fisico += '<td colspan="4">';
                            html_caracter_fisico +=
                                '<table id="example" class="table table-list-search"style="margin-bottom: 0px !important;">';
                            html_caracter_fisico += '<tbody><tr><td>';
                            html_caracter_fisico +=
                                '<a href="javascript:void(0)" data-secuencia="' + value.secuencia +
                                '" id="add_tabla_editar_' + value.id_cf + '" name="add_tabla_' +
                                value.id_cf + '" class="btn btn-success btn-sm add_tabla_editar_' +
                                value.id_cf + ' egreso_editar">';
                            html_caracter_fisico += '<i class="fa fa-plus" ></i>' + value
                                .descripcion_cf;
                            html_caracter_fisico += '</a>';
                            html_caracter_fisico += '</td>';
                            html_caracter_fisico += '</tr>';
                            html_caracter_fisico += '</tbody>';
                            html_caracter_fisico += '</table>';
                            html_caracter_fisico += '<table id="tbl_tabla_' + value.id_cf +
                                '" style="margin-bottom: 10px !important; margin-left: 8px;" class="tablas"> ';
                            html_caracter_fisico += '<tbody id="cuerpo_tabla_' + value.id_cf +
                                '" name="cuerpo_tabla_' + value.i_cfd + '" class="cuerpo_tabla_' +
                                value.id_cf + '">';
                            if (value.datos && value.datos.length > 0) {
                                $.each(value.datos, function(index, item) {

                                    html_caracter_fisico +=
                                        '<tr class="limpiar_al_iniciar">';
                                    html_caracter_fisico += '<td colspan="2" width="60%">';
                                    html_caracter_fisico +=
                                        '<input type="hidden" id="id_bh" value="' + item
                                        .id + '">';
                                    html_caracter_fisico +=
                                        '<input type="hidden" id="estado"  name="estado[]" value="1">';
                                    html_caracter_fisico +=
                                        '<input type="hidden"  name="secuencia[]" value="' +
                                        item.secuencia + '">';
                                    html_caracter_fisico +=
                                        '<input type="hidden" name="id_detalle_egreso[]" value="' +
                                        item.id + '">';
                                    html_caracter_fisico +=
                                        '<input type="hidden" name="es_visible[]" value="1">';
                                    if (item.id_cf <= 9) {
                                        html_caracter_fisico +=
                                            '<input type="hidden" name="descripcion_caracter_fisico_id_egreso" value="' +
                                            item.id_cf + '0' + index + '">';
                                    } else if (item.id_cf >= 10 && item.id_cf <= 99) {
                                        html_caracter_fisico +=
                                            '<input type="hidden" name="descripcion_caracter_fisico_id_egreso[]" value="' +
                                            item.id_cf + '' + index + '">';
                                    }
                                    html_caracter_fisico +=
                                        '<input type="hidden" name="descripcion_egreso[]">';
                                    html_caracter_fisico +=
                                        '<input type="hidden" name="id_caracter_fisico_egreso[]" value="' +
                                        item.id_cf + '">';
                                    html_caracter_fisico +=
                                        '<input type="hidden" class="form-control id_relacionado_tabla_' +
                                        item.id_cf +
                                        ' typeahead tt-query" id="id_relacionado_tabla_' +
                                        index + '" name="id_relacionado_tabla_' + item
                                        .id_cf + '[]" data-id=' + index +
                                        ' style="height: 28px !important;" required>';

                                    html_caracter_fisico +=
                                        '<input type="hidden" name="valor_tabla_egreso[]">';
                                    html_caracter_fisico +=
                                        '<input type="text" required="" name="descripcion_caracter_fisico_egreso[]" id="descripcion_caracter_fisico_egreso' +
                                        item.id_cf + '' + index +
                                        '" class="form-control descripcion_caracter_fisico_egreso' +
                                        item.id_cf + '" value="' + item.descripcion + '">';
                                    html_caracter_fisico += '</td>';
                                    html_caracter_fisico += '<td colspan="2">';
                                    html_caracter_fisico +=
                                        '<input name="valor_egreso[]" id="valor_egreso' +
                                        index +
                                        '" type="number" step="1" type="text" class="form-control egreso_editar" value="' +
                                        item.valor_caracter_fisico + '" required>';
                                    html_caracter_fisico += '</td>';
                                    html_caracter_fisico += '<td>';
                                    html_caracter_fisico +=
                                        '<button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_tabla btn-xs" style="margin-left:4px"><i class="fa fa-times"></i></button>';
                                    html_caracter_fisico += '</td>';
                                    html_caracter_fisico += '</tr>';
                                });
                            }

                            html_caracter_fisico += '</table>';
                            html_caracter_fisico += '<table id="tbl_tabla_' + value.id +
                                '" style="margin-bottom: 10px !important; margin-left: 8px;"> ';
                            html_caracter_fisico += '<tbody id="cuerpo_tabla_' + value.id +
                                '" name="cuerpo_tabla_' + value.id + '" class="cuerpo_tabla_' +
                                value.id + '">';
                            html_caracter_fisico += '</tbody>';
                            html_caracter_fisico += '</table>';
                            html_caracter_fisico += '</td>';
                            html_caracter_fisico += '</tr>';

                        } else if (value.multiple == null && value.tabla == 1) {
                            //  1  DIETA_ORAL
                            //  2 INFUCION_CONTINUA
                            //  3 DIETA_ENTERAL
                            //  4 TRANSFUSIONES
                            let item_valor_combo = '';
                            $.each(data.intervencion_terapeuticas, function(key, t) {
                                if (value.tabla_tipo == t.tipo) {
                                    item_valor_combo += '<option value=' + t.id + ' >' + t
                                        .descripcion_intervencion_t + '</option>';
                                }
                            });
                            html_caracter_fisico += '<tr>';
                            html_caracter_fisico += '<td colspan="4">';
                            html_caracter_fisico +=
                                '<input type="hidden"  name="secuencia[]" value="' + value
                                .secuencia + '">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="id_detalle_egreso[]" value="' + value
                                .id + '">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="descripcion_egreso[]">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="descripcion_caracter_fisico_id_egreso[]">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="id_caracter_fisico_egreso[]" value="' +
                                value.id + '">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="descripcion_caracter_fisico_egreso[]">' +
                                value.descripcion + '</td>';
                            html_caracter_fisico += '</tr>';
                            html_caracter_fisico += '<tr>';
                            html_caracter_fisico +=
                                '<td colspan="2"><select class="form-control" name="valor_tabla_egreso[]">' +
                                item_valor_combo +
                                '</select></td> <td colspan="2"><input type="number" step="1"   name="valor_egreso[]" class="form-control egreso_editar" > </td>';
                            html_caracter_fisico += '</tr>';

                        } else {
                            html_caracter_fisico += '<tr>';
                            html_caracter_fisico += '<td colspan="2">';
                            html_caracter_fisico +=
                                '<input type="hidden"  name="secuencia[]" value="' + value
                                .secuencia + '">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="id_detalle_egreso[]" value="' + value
                                .id + '">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="es_visible[]" value="1">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="descripcion_egreso[]" value="' + value
                                .descripcion + '">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="descripcion_caracter_fisico_id_egreso[]">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="id_caracter_fisico_egreso[]" value="' +
                                value.id_cf + '">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="descripcion_caracter_fisico_egreso[]">';
                            html_caracter_fisico +=
                                '<input type="hidden" name="valor_tabla_egreso[]">' + value
                                .descripcion +
                                '</td> <td colspan="2"><input type="number" step="1"   name="valor_egreso[]" class="form-control egreso_editar" value="' +
                                value.valor_caracter_fisico + '"> </td>';
                            html_caracter_fisico += '</tr>';
                        }
                    }
                });
                html_caracter_fisico += '</tbody>';
                html_caracter_fisico += '</table>';

                html_caracter_fisico +=
                    '<table id="example" class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
                html_caracter_fisico += ' <tbody>';
                html_caracter_fisico += '<tr>';
                html_caracter_fisico += '<td style="width: 70%;"><b>TOTAL DE EGRESOS </b></td>';
                html_caracter_fisico += '<td>';
                html_caracter_fisico +=
                    '<input id="total_egreso2"  class="form-control" style="height: 28px !important;" required disabled>';
                html_caracter_fisico += '</td>';
                html_caracter_fisico += '</tr>';
                html_caracter_fisico += '</tbody>';
                html_caracter_fisico += '</table>';
                html_caracter_fisico += '<hr>';
                html_caracter_fisico += '</div>';
                html_caracter_fisico += '</div>';
                $('#balance_hidrico_editar').append(html_caracter_fisico);


                $('#peso, #hora_registro_editar, #INGRESO_AO_EDITAR, #INGRESO_PI_EDITAR').on('input',
                    function() {
                        calculo_horas_peso('peso', 'hora_registro_editar', 'INGRESO_AO_EDITAR',
                            'INGRESO_PI_EDITAR');
                        calculo_horas_peso('peso', 'hora_registro_editar', 'INGRESO_AO2_EDITAR',
                            'INGRESO_PI2_EDITAR');
                        actualizarTotalIngreso2();
                        actualizarTotalEgreso2();
                    });

                /**********************INGRESOS**********************/


                // Llamar a la función actualizarTotalIngreso cada vez que cambie el valor de cualquier input
                $(document).on('input', '.ingreso_editar', function() {
                    actualizarTotalIngreso2();
                });
                $(document).on('input', '.egreso_editar', function() {
                    actualizarTotalEgreso2();
                });
                // Llamar a la función al cargar la página para inicializar el valor
                $(document).ready(function() {
                    actualizarTotalIngreso2();
                    actualizarTotalEgreso2();
                });

            }
        });
    }
</script>
