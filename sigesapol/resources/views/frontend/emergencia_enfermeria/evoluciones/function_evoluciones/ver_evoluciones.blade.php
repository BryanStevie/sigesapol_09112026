<script>
    $('#modalVerEvoluciones').on('hidden.bs.modal', function() {
        $('#alergia_si_ver').hide();
        $('#alergia_no_ver').hide();
    });

     $(document).ready(function () {
        const nivel = $("#nivel_ipress").val();
        if (nivel != 2) {
        const index = 2;
        const tabla = document.getElementById("tbl_CPMS_ENFERMERIA_VER");
        for (let fila of tabla.rows) {
            if (fila.cells[index]) {
            fila.cells[index].style.display = "none";
            }
        }
        }
    });

    $('body').on('click', '#verEvoluciones', function() {
        Swal.fire({
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando informacion</span></div>",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
        cambiar_de_PestanaTab('tab0defaultEvoluciones-ver');
        var id_enfermeria = $('#id_enfermeria_ver').val(); //VIENE DEL FORM lISTAR
        var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado').val();
        var id_evoluciones = $(this).data('id_evoluciones');
        var id_asegurado = $(this).data('id_asegurado');
        $('#id_evolucion').val(id_evoluciones);
        const tabEvoluciones = document.getElementById('tabEvolucionesCPMS_ver');
        tabEvoluciones.setAttribute('data-toggle', 'none');
        tabEvoluciones.setAttribute('rel', 'tooltip');
        tabEvoluciones.setAttribute('title', 'Debe existir la atencion.');
        $.ajax({
            url: `/buscarIdPrestacionParaNotaDeEmergencia/${id_enfermeria}/${2}`,
            method: "GET",
            success: function(data) {
                id_prestacion_listado = data.length == 0 ? null : data[0].id;
                $('#id_prestacion_listado').val(id_prestacion_listado);
                var id_prestacion = $('#id_prestacion_listado').val();
                if (id_prestacion_listado) {
                    tabEvoluciones.setAttribute('data-toggle', 'tab');
                    tabEvoluciones.setAttribute('title', '');
                    $('[rel="tooltip"]').tooltip('destroy');
                }
                ver_evoluciones(id_evoluciones, id_enfermeria, id_prestacion, id_asegurado);
            },
            error: function(error) {
                console.error("Error al realizar la solicitud", error);
            }
        });

        $('#tituloFormularioEvoluciones-ver').html("Proceso de Cuidado Enfermero - PCE");
        $('#modalVerEvoluciones').modal('show');

        $("#tbl_DIAGNOSTICO_ENFERMERIA tbody").empty();
        $("#tbl_INTERVENCIONES_ENFERMERIA tbody").empty();
        $("#tbl_PLAN_CUIDADO_ENFERMERIA tbody").empty();
        $("#tbl_EVALUACIONES_ENFERMERIA tbody").empty();
        $("#tbl_CPMS_ENFERMERIA tbody").empty();

        $("#descripcion_turno").attr('disabled', 'disabled');

        $("#descripcion_subjetivo").attr('disabled', 'disabled');
        $("#descripcion_objetivo").attr('disabled', 'disabled');
        /* $("#descripcion_analisis").attr('disabled','disabled');
        $("#descripcion_plan").attr('disabled','disabled'); */
        $("#descripcion_intervenciones").attr('disabled', 'disabled');
        $("#descripcion_evaluaciones").attr('disabled', 'disabled');

        $("#add_DIAGNOSTICO_ENFERMERIA").hide();;
        $("#add_PLAN_CUIDADO_ENFERMERIA").hide();
        $("#add_INTERVENCIONES_ENFERMERIA").hide();
        $("#add_EVALUACIONES_ENFERMERIA").hide();
        $("#add_CPMS_ENFERMERIA").hide();

        var id_enfermeria = $('#id_enfermeria_ver').val(); //VIENE DEL FORM lISTAR
        var nombre_tipo_atencion = $('#nombre_tipo_atencion').val();
        $('#nombre_tipo_atencion_registro_ver').val("EMERGENCIA");
        var prioridad = $('#prioridad').val();
        $('#prioridad_registro_ver').val(prioridad);

        var id_atencion_evolucion_listado = $('#id_atencion_evolucion_listado').val();
        $('#id_atencion_evolucion_registro').val(id_atencion_evolucion_listado);
        // var id_atencion_evolucion_registro = $('#id_atencion_evolucion_registro').val();
        var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado').val();
        var id_evoluciones = $(this).data('id_evoluciones');
        $('#id_evolucion').val(id_evoluciones);

        var flag_firma = $(this).data('flag_firma');
        $('#flag_firma').val(flag_firma);
        if (flag_firma <= 0) {
            $("#btnFirmaEvolucion").show();
            $("#icon-doc-firmado-evolucion").hide();
        } else {
            $("#btnFirmaEvolucion").hide();
            if (flag_firma === 1) {
                $("#icon-doc-firmado-evolucion").show();
            } else {
                $("#icon-doc-firmado-evolucion").hide();
            }
        }
        //var id_nota_enfermeria = $('#id_nota_enfermeria_evolucion_listado').val();
        var id_prestacion = $('#id_prestacion_listado').val();
        var id_asegurado = $(this).data('id_asegurado');

        //cargarCabeceraModalEvoluciones_ver(id_asegurado);
        //diagnosticos_pacientes_prestacion_ver(id_prestacion_listado);
        //diagnosticos_pacientes_prestacion_ver2(id_prestacion_listado);
        //turnos();
        pintar_puntos_by_evolucion(id_enfermeria, "control_div_puntos_evolucion_ver",
            "control_div_puntos_evolucion_leyenda_ver", 'REGISTRO', id_tipo_atenciones_listado);
    });

    function ver_evoluciones(id_evoluciones, id_enfermeria, id_prestacion, id_asegurado) {
        var var_url = '{{ url('ver_nota_ingreso_evoluciones') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_evoluciones: id_evoluciones,
                id_enfermeria: id_enfermeria,
                id_prestacion: id_prestacion,
                id_asegurado: id_asegurado,
            },
            success: function(data) {
                const nivel = $("#nivel_ipress").val();
                Swal.fire({
                    icon: "success",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                    confirmButtonText: "ENTENDIDO",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: "ENTENDIDO",
                    timer: 1200,
                    timerProgressBar: true,
                });
                $('#num_documento_evolucion_ver').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_evolucion_ver').html(data.datos_paciente.paterno + " " + data
                    .datos_paciente.materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_evolucion_ver').html(data.datos_paciente.tipo_sangre);
                $('#edad_evolucion_ver').html(data.datos_paciente.edad_corta);
                $('#sexo_evolucion_ver').html(data.datos_paciente.sexo);
                $('#hc_evolucion_ver').html(data.datos_paciente.nro_historia);
                $('#cel_acomp_evolucion_ver').html(data.estado_acomp.celular_acomp != "" ? data.estado_acomp.celular_acomp : 'Sin registrar');
                $('#parentesgo_ver_1').html(data.datos_paciente.parentesgo);
                $('#tipo_sangre_ver_1').html(data.datos_paciente.tipo_sangre ?? 'Sin registrar');

                if (data.estado_alergia.flag_alergia) {
                    $('#alergia_si_ver').show();
                } else {
                    $('#alergia_no_ver').show();
                }
                /*********************************TABLA TURNOS************************************/
                var cuerpo_turnos = '';
                if (data.turnos && data.turnos.length > 0) {
                    $.each(data.turnos, function(key, value) {
                        cuerpo_turnos += '<option value="' + value.id + '">' + value.descripcion +
                            '</option>';
                    });
                }
                $("#turno_ver").html(cuerpo_turnos);
                $('#turno_ver').val(data.nota_enfermeria[0].id_turno).trigger('change');
                /**************************************************************************/
                $("#descripcion_subjetivo_ver").val(data.nota_enfermeria[0].descripcion_subjetivo);
                $("#descripcion_objetivo_ver").val(data.nota_enfermeria[0].descripcion_objetivo);
                $("#descripcion_analisis_ver").val(data.nota_enfermeria[0].descripcion_analisis);
                $("#descripcion_plan_ver").val(data.nota_enfermeria[0].descripcion_plan);
                $("#descripcion_intervenciones_ver").val(data.nota_enfermeria[0]
                    .descripcion_intervenciones);
                $("#descripcion_evaluaciones_ver").val(data.nota_enfermeria[0].descripcion_evaluaciones);

                $("#descripcion_evaluaciones_ver").val(data.nota_enfermeria[0].descripcion_evaluaciones);
                $("#fecha_registro_evoluciones_inicial_ver").val(data.nota_enfermeria[0].fecha_evol);

                /******************************HISTORIAL DE INVASIVOS POR ID ENFERMERIA*********/
                var cuerpo_INVASIVOS_ATENCION = '';
                $("#historial_invasivos_ver").empty();
                if (data.invasivos_por_atencion.items && data.invasivos_por_atencion.items.length > 0) {
                    $.each(data.invasivos_por_atencion.items, function(key, value) {
                        cuerpo_INVASIVOS_ATENCION = cuerpo_INVASIVOS_ATENCION +
                            '<tr>' +
                            '<td>' + (key + 1) + '</td>' +
                            '<td>' + value.fecha + '</td>' +
                            '<td>' + value.tipo + '</td>' +
                            '<td>' + value.lugar + '</td>' +
                            '<td>' + value.accion + '</td>' +
                            '</tr>';
                    });
                }
                $('#historial_invasivos_ver').append(cuerpo_INVASIVOS_ATENCION);
                if (data.invasivos_por_atencion.nextPageUrl) {
                    $('#load-more-container-ver').html(
                        '<button id="load-more-button-ver" class="btn enf_btn-celeste" data-next-page-url-ver="' +
                        (data.invasivos_por_atencion.current_page + 1) + '">Cargar más </button>');
                } else {
                    $('#load-more-container-ver').html('');
                }


                /********************************************************************************/




                //====================================================================================================================//
                var cuerpo_DIAGNOSTICO_ENFERMERIA = '';
                $("#cuerpo_DIAGNOSTICO_ENFERMERIA_VER").empty();
                if (data.nota_enfermeria_diagnostico && data.nota_enfermeria_diagnostico.length > 0) {
                    $.each(data.nota_enfermeria_diagnostico, function(key, value) {
                        cuerpo_DIAGNOSTICO_ENFERMERIA = cuerpo_DIAGNOSTICO_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" ' +
                            '   value="' + value.descrip_abrev + '"  disabled>' +
                            /* ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" ' +
                            '   value="' + value.valor_de_seleccion + '"  disabled>' + */
                            ' </td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_DIAGNOSTICO_ENFERMERIA_VER").html(cuerpo_DIAGNOSTICO_ENFERMERIA);
                //====================================================================================================================//

                var cuerpo_PLAN_CUIDADO_ENFERMERIA = '';
                $("#cuerpo_PLAN_CUIDADO_ENFERMERIA_VER").empty();
                if (data.nota_enfermeria_plan_cuidado && data.nota_enfermeria_plan_cuidado.length > 0) {
                    $.each(data.nota_enfermeria_plan_cuidado, function(key, value) {
                        cuerpo_PLAN_CUIDADO_ENFERMERIA = cuerpo_PLAN_CUIDADO_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" ' +
                            '   value="' + value.descrip_abrev + '"  disabled>' +
                            ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" ' +
                            '   value="' + value.valor_de_seleccion + '"  disabled>' +
                            ' </td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_PLAN_CUIDADO_ENFERMERIA_VER").html(cuerpo_PLAN_CUIDADO_ENFERMERIA);
                //====================================================================================================================//

                var cuerpo_INTERVENCIONES_ENFERMERIA = '';
                $("#cuerpo_INTERVENCIONES_ENFERMERIA_VER").empty();
                if (data.nota_enfermeria_intervenciones && data.nota_enfermeria_intervenciones.length > 0) {
                    $.each(data.nota_enfermeria_intervenciones, function(key, value) {
                        cuerpo_INTERVENCIONES_ENFERMERIA = cuerpo_INTERVENCIONES_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" ' +
                            '   value="' + value.descrip_abrev + '"  disabled>' +
                            /* ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" ' +
                            '   value="' + value.valor_de_seleccion + '"  disabled>' + */
                            ' </td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_INTERVENCIONES_ENFERMERIA_VER").html(cuerpo_INTERVENCIONES_ENFERMERIA);
                //====================================================================================================================//

                var cuerpo_EVALUACIONES_ENFERMERIA = '';
                $("#cuerpo_EVALUACIONES_ENFERMERIA_VER").empty();
                if (data.nota_enfermeria_evaluaciones && data.nota_enfermeria_evaluaciones.length > 0) {
                    $.each(data.nota_enfermeria_evaluaciones, function(key, value) {
                        cuerpo_EVALUACIONES_ENFERMERIA = cuerpo_EVALUACIONES_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" ' +
                            '   value="' + value.descrip_abrev + '"  disabled>' +
                            ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800; text-align: center;" ' +
                            '   value="' + value.valor_de_seleccion + '"  disabled>' +
                            ' </td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_EVALUACIONES_ENFERMERIA_VER").html(cuerpo_EVALUACIONES_ENFERMERIA);
                //====================================================================================================================//

                var cuerpo_CPMS_ENFERMERIA = '';
                $("#cuerpo_CPMS_ENFERMERIA_VER").empty();
                if (data.nota_enfermeria_cpms && data.nota_enfermeria_cpms.length > 0) {
                    $.each(data.nota_enfermeria_cpms, function(key, value) {

                        let lista_item_combo = '';
                        let valores = value;
                        if (data.diagnosticos_por_prestacion && data.diagnosticos_por_prestacion
                            .length > 0) {
                            $.each(data.diagnosticos_por_prestacion, function(key, value2) {
                                let selected = '';
                                if (value2.id_diagnostico == valores.id_diagnostico) {
                                    selected = 'selected';
                                }
                                lista_item_combo += '<option value="' + value2
                                    .id_diagnostico + '" ' + selected + '>' + value2.codigo + " - " + value2
                                    .nombre + '</option>';
                            });
                        }

                        cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 40%;"><input type="text" class="form-control input-sm" ' +
                            ' style="height: 28px !important;font-weight: 800;" name="procedimiento_descripcion[]" id="procedimiento_descripcion' +
                            key + '" value="' +  value.codigo+' - ' + value.nombre_procedimiento + '" disabled> ' +
                            ' </td>' +
                            '<td style="width: 10%;">' +
                            '<input name="procedimiento_cantidad[]" id="procedimiento_cantidad' +
                            key + '" value="' + value.cantidad +
                            '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;" disabled>' +
                            '</td>';
                            if (nivel == 2) {
                                cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA + '<td style="width: 10%;">' + '<input name="procedimiento_tiempo[]" id="procedimiento_tiempo' + key +
                                '" value="' + (value.procedimiento_tiempo ?? 'No registrado') +
                                '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;" disabled>' +
                                '</td>';
                            }
                            cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA + `<td style="width: ${nivel == 2 ? '40' : '50'}%;">` +
                            ' <select style="height: 28px !important;font-weight: 40;" class="form-control cpms_listado_ver" disabled>' +
                            lista_item_combo + '</select></td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_CPMS_ENFERMERIA_VER").html(cuerpo_CPMS_ENFERMERIA);

                $("#diagnosticos_ver").empty();
                var cuerpo_DIAGNOSTICOS_PRESTACION = '';
                //var cuerpo_DIAGNOSTICOS_PRESTACION2 = '' ;
                if (data.diagnosticos_por_prestacion && data.diagnosticos_por_prestacion.length > 0) {
                    $.each(data.diagnosticos_por_prestacion, function(key, value) {
                        cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION +
                            '<tr>' +
                            '<td>' + value.codigo + '</td>' +
                            '<td>' + value.nombre + '</td>' +
                            '<td>' + value.tipo + '</td>' +
                            '<td>' + value.clasificacion + '</td>' +
                            '</tr>';
                        // cuerpo_DIAGNOSTICOS_PRESTACION2 += '<option value="' + value.id_diagnostico + '">' + value.nombre + '</option>';
                    });
                }
                $("#diagnosticos_ver").html(cuerpo_DIAGNOSTICOS_PRESTACION);
                //$(".cpms_listado_ver").html(cuerpo_DIAGNOSTICOS_PRESTACION2);
                //====================================================================================================================//
                // Array de opciones para el select
                const lista_Array = [{
                        value: 0,
                        text: 'Seleccionar'
                    },
                    {
                        value: 1,
                        text: 'Colocación'
                    },
                    {
                        value: 2,
                        text: 'Cambio'
                    },
                    {
                        value: 3,
                        text: 'Retiro'
                    }
                ];

                // Generar las opciones del select como una cadena HTML
                let item_valor_combo = lista_Array.map(item =>
                    `<option value="${item.value}">${item.text}</option>`
                ).join('');

                // Inicializar la variable para almacenar el cuerpo de la tabla
                let cuerpo_invasivos = '';
                $("#lista_invasivos_ver").empty();
                // Recorrer el array de invasivos y generar las filas de la tabla
                data.invasivos_por_evolucion.forEach((value, key) => {
                    let options = lista_Array.map(item =>
                        `<option value="${item.value}" ${item.value == value.accion ? 'selected' : ''}>${item.text}</option>`
                    ).join('');
                    cuerpo_invasivos += `
              <tr>
                <td>
                  ${value.id}
                </td>
                <td>
                  ${value.tipo}
                </td>
                <td>
                  <input class="form-control" type="date" name="invasivo_fecha" value="${value.fecha ? value.fecha : ''}" disabled>
                </td>
                <td>
                  <input class="form-control" type="text" name="invasivo_lugar" value="${value.lugar == null ? '' : value.lugar}" disabled>
                </td>
                <td>
                  <select class="form-control" id="invasivo_accion${key}" name="invasivo_accion[]" style="height: 28px !important; cursor: pointer; font-size: 13px; padding: 1px 12px !important; font-weight: 800;" disabled>
                    ${options}
                  </select>
                </td>
              </tr>
            `;
                });

                // Añadir las filas generadas a la tabla
                $('#lista_invasivos_ver').append(cuerpo_invasivos);

            }
        })
    };

    // Maneja el clic en el botón "Cargar más"
    $(document).on('click', '#load-more-button-ver', function() {
        var id_enfermeria_ver = $('#id_enfermeria_ver').val();
        var nextPageUrlVer = $(this).data('next-page-url-ver');
        console.log(nextPageUrlVer);
        var $button = $(this);
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
        var var_url = '{{ url('cargar_mas_historiaL_invasivos') }}' + '?page=' + nextPageUrlVer;

        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_enfermeria: id_enfermeria_ver,
                page: nextPageUrlVer // Pasar el número de página actual
            },
            success: function(data) {
                $button.prop('disabled', false).html(
                    'Cargar más <i class="fa fa-chevron-down"></i>');
                procesarRespuesta_ver(data, false); // false indica que es una carga adicional
            }
        });
        return false;

    });

    function procesarRespuesta_ver(data, esCargaInicial) {
        var cuerpo_INVASIVOS_ATENCION_VER = '';
        if (data.invasivos_por_atencion.items && data.invasivos_por_atencion.items.length > 0) {
            $.each(data.invasivos_por_atencion.items, function(index, item) {
                cuerpo_INVASIVOS_ATENCION_VER +=
                    '<tr>' +
                    '<td>' + ((data.invasivos_por_atencion.current_page - 1) * 5 + index + 1) + '</td>' +
                    '<td>' + item.fecha + '</td>' +
                    '<td>' + item.tipo + '</td>' +
                    '<td>' + item.lugar + '</td>' +
                    '<td>' + item.accion + '</td>' +
                    '</tr>';
            });
        }

        if (esCargaInicial) {
            // Para la carga inicial, establecemos el contenido
            $('#historial_invasivos_ver').html(cuerpo_INVASIVOS_ATENCION_VER);
        } else {
            // Para cargas adicionales, agregamos al contenido existente
            $('#historial_invasivos_ver').append(cuerpo_INVASIVOS_ATENCION_VER);
        }

        // Manejo del botón "Cargar más"
        if (data.invasivos_por_atencion.nextPageUrl) {
            $('#load-more-container-ver').html(
                '<button id="load-more-button-ver" class="btn enf_btn-celeste" data-next-page-url-ver="' + (data
                    .invasivos_por_atencion.current_page + 1) + '">Cargar más </button>');
        } else {
            $('#load-more-container-ver').html('');
        }

    }
    /************************************************************/
    /****************FIRMAR PDF EVOLUCIONES**********************/
    // window.addEventListener('getArgumentsEvoluciones', async function(e) {
    //       await ObtieneArgumentosParaFirmaDesdeLaWebEvoluciones();
    //     });

    async function getArguments() {
        arg = document.getElementById("argumentos_evolucion").value;
        await dispatchEventClient('sendArguments', arg);
    }

    async function ObtieneArgumentosParaFirmaDesdeLaWebEvoluciones() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.post("{{ route('evolucion_post_arguments') }}", {
            _token: CSRF_TOKEN,
            type: "W",
            documentName: $('#doc_firmar_evolucion').val()
        }, function(data, status) {
            document.getElementById("argumentos_evolucion").value = data;
            getArguments();
        });
    }
    // function MiFuncionCancel() {
    //   alert("El proceso de firma digital fue cancelado.");
    // }
    /*************CON TOKEN****************/
    // $bh->flag_firma = 1;
    async function actualiza_datos_firma_con_token_evolucion() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        let id_resol = $('#id_evolucion').val();
        $.post("{{ route('actualiza_datos_firma_con_token_evolucion') }}", {
            _token: CSRF_TOKEN,
            id_resol: id_resol,
        }, function(data, status) {
            location.reload();
        });
    }

    $(document).ready(function() {
        /*************SIN TOKEN****************/
        $(document).on('click', '#btnFinalizarEvolucion', function(e) {
            e.preventDefault();
            firmarEvolucionSinTokens();
        })
        const firmarEvolucionSinTokens = () => {
            var var_url = '{{ url('actualiza_datos_firma_sin_token_evolucion') }}';
            var batchSignatureProcessPoller = setInterval(function() {
                $.ajax({
                    url: var_url,
                    type: "post",
                    cache: false,
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(completed) {
                        if (completed) {
                            //location.href = '/all_admisiones_atendidas_by_emergencia';
                            clearInterval(batchSignatureProcessPoller);
                        }
                    }
                });
            }, 3000);
        }

        $(document).on('click', '#btnFirmaEvolucion', function(e) {
            e.preventDefault();
            firmarEvolucion();
        })
        //balanceHidricoPDFfirmar
        const firmarEvolucion = () => {
            $("#flag_firma_enfermeria").val("4");
            const id_evolucion = $('#id_evolucion').val();
            $.ajax({
                    url: `/evolucion_firmar_por_turno/${id_evolucion}`,
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json'
                })
                .done(function(resultado) {
                    console.log(resultado);
                    if (resultado) {
                        if (resultado.sw) {
                            var csv = "<?php echo url(); ?>/tmp/csv/" + resultado.csv + ".csv";
                            $("#doc_firmar_evolucion").val(`${resultado.csv}.7z`);
                            initInvoker('W');
                        }
                    } else {
                        Swal.fire({
                            title: "Información de Servicio de Firma",
                            text: "No se puede procesar la información",
                            icon: "info",
                            timer: 4000,
                            showConfirmButton: true
                        });
                    }
                    $('#btnLiquidacionFirmarPdf').attr("disabled", false);
                })
                .fail(function(xhr, status, error) {
                    console.log('Error:', error);
                })
                .always(function() {
                    console.log('Esta función siempre se ejecuta, éxito o error');
                });
        }
    });
    /*************************FIN FIRMAR PDF EVOLUCIONES*********************/
</script>
