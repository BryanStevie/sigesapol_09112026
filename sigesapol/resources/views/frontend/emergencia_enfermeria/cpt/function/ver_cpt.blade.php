<script>
    $('body').on('click', '#verDetalleCpt', function() {
        var id_enfermeria = $('#id_enfermeria_ver_cpt').val(); //VIENE DEL FORM lISTAR
        var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado_cpt').val();
        var id_cpt = $(this).data('id_cpt');
        var id_ext = $('#id_ext_cpt').val();
        var id_asegurado = $(this).data('id_asegurado');
        $("#diagnosticos_det_cpt").empty();
        $("#diagnosticos_det_cpt_prest").empty();
        $('#id_cpt_ver').val(id_cpt);
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
        ver_cpt(id_cpt, id_enfermeria, id_asegurado, id_ext);

        $('#modalVerCpt').modal('show');
        $("#tbl_CPMS_ENFERMERIA_det tbody").empty();
        $("#descripcion_turno").attr('disabled', 'disabled');
        var nombre_tipo_atencion = $('#nombre_tipo_atencion_cpt').val();
        $('#nombre_tipo_atencion_det_cpt').val('EMERGENCIA');
        var prioridad = $('#prioridad_cpt').val();
        $('#prioridad_det_cpt').val(prioridad);

        var flag_firma = $(this).data('flag_firma');
        $('#flag_firma_cpt').val(flag_firma);
        if (flag_firma <= 0) {
            $("#btnFirmaCpt").show();
            $("#icon-doc-firmado-cpt").hide();
        } else {
            $("#btnFirmaCpt").hide();
            if (flag_firma === 1) {
                $("#icon-doc-firmado-cpt").show();
            } else {
                $("#icon-doc-firmado-cpt").hide();
            }
        }
    });

    function ver_cpt(id_cpt, id_enfermeria, id_asegurado, id_ext) {
        var var_url = '{{ url('ver_nota_ingreso_cpt') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_cpt,
                id_enfermeria,
                id_asegurado,
                id_ext,
                id_tipo_atencion: 2
            },
            success: function(data) {
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
                $('#num_documento_cpt_ver').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_cpt_ver').html(data.datos_paciente.paterno + " " + data
                    .datos_paciente.materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_cpt_ver').html(data.datos_paciente.tipo_sangre);
                $('#edad_cpt_ver').html(data.datos_paciente.edad_corta);
                $('#sexo_cpt_ver').html(data.datos_paciente.sexo);
                $('#hc_cpt_ver').html(data.datos_paciente.nro_historia);
                $('#cel_acomp_cpt_ver').html(data.estado_acomp != 0 ? data.estado_acomp : 'Sin registrar');
                $('#parentesgo_cpt_ver').html(data.datos_paciente.parentesgo);
                $('#tipo_sangre_cpt_ver').html(data.datos_paciente.tipo_sangre);
                if (data.estado_alergia.flag_alergia) {
                    $('#alergia_si_cpt_det').show();
                } else {
                    $('#alergia_no_cpt_det').show();
                }
                var cuerpo_turnos = '';
                if (data.turnos && data.turnos.length > 0) {
                    $.each(data.turnos, function(key, value) {
                        cuerpo_turnos += '<option value="' + value.id + '">' + value.descripcion +
                            '</option>';
                    });
                }
                $("#turno_det_cpt").html(cuerpo_turnos);
                $('#turno_det_cpt').val(data.nota_cpt[0].id_turno).trigger('change');
                $("#hora_det_cpt").val(data.nota_cpt[0].updated_at.split(" ")[1]);
                $("#fecha_det_cpt").val(data.nota_cpt[0].updated_at.split(" ")[0]);

                var cuerpo_CPMS_ENFERMERIA = '';
                $("#cuerpo_CPMS_ENFERMERIA_det").empty();
                if (data.cpms && data.cpms.length > 0) {
                    $.each(data.cpms, function(key, value) {
                        cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                            '<tr>' +
                            ' <td><input type="text" class="form-control input-sm" ' +
                            ' style="height: 28px !important;font-weight: 800;" name="procedimiento_descripcion[]" id="' +
                            '" value="' + value.codigo_procedimiento + " - " + value
                            .nombre_procedimiento + '" disabled> ' +
                            ' </td>' +
                            '<td>' +
                            ' <input type="text" class="form-control" disabled style="height: 28px !important;" value="' +
                            value.codigo_diagnostico + " - " + value.nombre_diagnostico +
                            '"></td>' +
                            '<td>' +
                            '<input name="procedimiento_cantidad[]" id="procedimiento_cantidad' +
                            '" value="' +
                            value.cantidad +
                            '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;" disabled>' +
                            '</td>' +
                            '<td>' +
                            '<input name="procedimiento_tiempo[]" id="procedimiento_tiempo' +
                            '" value="' +
                            (value.tiempo ?? 'Sin asignar') +
                            '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;" disabled>' +
                            '</td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_CPMS_ENFERMERIA_det").html(cuerpo_CPMS_ENFERMERIA);

                var cuerpo_DIAGNOSTICOS_ADMISION = '';
                $("#diagnosticos_det_cpt").empty();
                $.each(data.diagnosticos.diagnosticos_admision, function(key, value) {
                    cuerpo_DIAGNOSTICOS_ADMISION = cuerpo_DIAGNOSTICOS_ADMISION +
                        '<tr>' +
                        '<td>' + value.codigo + '</td>' +
                        '<td>' + value.nombre + '</td>' +
                        '<td>' + (value.tipo ? value.tipo : 'DE ADMISION') + '</td>' +
                        '<td>' + (value.clasificacion ? value.clasificacion : 'DE ADMISION') +
                        '</td>' +
                        '</tr>';
                });
                $('#diagnosticos_det_cpt').append(cuerpo_DIAGNOSTICOS_ADMISION != '' ?
                    cuerpo_DIAGNOSTICOS_ADMISION : `<tr>
                        <td colspan="4" style="text-align:center;">No hay datos registrados.</td>
                        </tr>`);

                var cuerpo_DIAGNOSTICOS_PRESTACION = '';
                $("#diagnosticos_det_cpt_prest").empty();
                $.each(data.diagnosticos.diagnosticos_atencion, function(key, value) {
                    cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION +
                        '<tr>' +
                        '<td>' + value.codigo + '</td>' +
                        '<td>' + value.nombre + '</td>' +
                        '<td>' + (value.tipo ? value.tipo : 'DE ADMISION') + '</td>' +
                        '<td>' + (value.clasificacion ? value.clasificacion : 'DE ADMISION') +
                        '</td>' +
                        '</tr>';
                });
                $('#diagnosticos_det_cpt_prest').append(cuerpo_DIAGNOSTICOS_PRESTACION != '' ?
                    cuerpo_DIAGNOSTICOS_PRESTACION : `<tr>
                        <td colspan="4" style="text-align:center;">No hay datos registrados.</td>
                        </tr>`);
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
                    url: `/evolucion_firmar_por_turno/${id_evolucion}/3`,
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
</script>
