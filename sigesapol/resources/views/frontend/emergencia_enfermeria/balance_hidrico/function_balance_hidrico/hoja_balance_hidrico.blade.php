<script>
    /*=============================================
       AJAX PARA crear Listar
       =============================================*/
    $('body').on('click', '#listarHojaBalanceHidrico', function() {
        var codigo_fecha_24 = $(this).data('codigo_fecha_24');
        var id_balance = $(this).data('id_balance');
        var flag_firma = $(this).data('flag_firma');
        $('#flag_firma').val(flag_firma);
        if (flag_firma <= 0) {
            $("#btnFirmaBalance").show();
            $("#icon-doc-firmado").hide();
            console.log('icon-doc-firmado   hidden');
        } else {
            $("#btnFirmaBalance").hide();
            if (flag_firma === 1) {
                $("#icon-doc-firmado").show();
            } else {
                $("#icon-doc-firmado").hide();
            }
        }
        $('#FormularioListarHojaBalanceHidrico').trigger("reset");
        $('#tituloFormularioListarHojaBalanceHidrico').html("Hoja de Balance Hídrico de Enfermería");
        /* $('#modalListarHojaBalanceHidrico').modal('show'); */
        $('#id_balance_ver').val(id_balance);
        ver_hoja_balance(codigo_fecha_24, id_balance);
    });

    function ver_hoja_balance(codigo_fecha_24, id_balance) {
        var var_url = '{{ url('hoja_balance_hidrico') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                codigo_fecha_24: codigo_fecha_24,
                id_asegurado: $('#id_asegurado_bh').val(),
                id_balance: id_balance,
                id_tipo_atencion: 2
            },
            success: function(data) {
                $('#num_documento_bh_ver').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_bh_ver').html(data.datos_paciente.paterno + " " + data.datos_paciente
                    .materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_bh_ver').html(data.datos_paciente.tipo_sangre);
                $('#edad_bh_ver').html(data.datos_paciente.edad_corta);
                $('#sexo_bh_ver').html(data.datos_paciente.sexo);
                $('#hc_bh_ver').html(data.datos_paciente.nro_historia);
                $('#parentesgo_ver').html(data.datos_paciente.parentesgo);
                $('#tipo_sangre_ver').html(data.datos_paciente.tipo_sangre ?? 'Sin data');
                $('#alergia_bh_ver').html(
                    data.datos_paciente.tiene_alergias
                        ? `<i style="color: blue;">SI</i>`
                        : `<i style="color: red;">NO</i>`
                );

                var html_ingresos = '';
                var html_egresos = '';

                let ingreso_total_m = 0;
                let ingreso_total_t = 0;
                let ingreso_total_n = 0;
                let ingreso_total_md = 0;
                let ingreso_total_ingreso = 0;

                let egreso_total_m = 0;
                let egreso_total_t = 0;
                let egreso_total_n = 0;
                let egreso_total_md = 0;
                let egreso_total_ingreso = 0;

                $("#lista_ingresos").empty();
                $.each(data.ingresos, function(key, ingreso) {
                    if (ingreso.codigo_agrupacion == 460) {
                        egreso_total_m += parseFloat(ingreso.total_m) || 0;
                        egreso_total_t += parseFloat(ingreso.total_t) || 0;
                        egreso_total_n += parseFloat(ingreso.total_n) || 0;
                        egreso_total_md += parseFloat(ingreso.total_md) || 0;
                        egreso_total_ingreso += parseFloat(ingreso.total_caracter_fisico) || 0;

                        html_egresos += '<tr>';
                        html_egresos += '<td>' + ingreso.caracter_fisico + '</td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso.total_m +
                            '</b></td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso.total_t +
                            '</b></td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso.total_n +
                            '</b></td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso.total_md +
                            '</b></td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso
                            .total_caracter_fisico + '</b></td>';
                        html_egresos += '</tr>';
                    } else {
                      ingreso_total_m += parseFloat(ingreso.total_m) || 0;
                      ingreso_total_t += parseFloat(ingreso.total_t) || 0;
                      ingreso_total_n += parseFloat(ingreso.total_n) || 0;
                      ingreso_total_md += parseFloat(ingreso.total_md) || 0;
                      ingreso_total_ingreso += parseFloat(ingreso.total_caracter_fisico) || 0;
                      html_ingresos += '<tr>';
                      html_ingresos += '<td>' + ingreso.caracter_fisico + '</td>';
                      html_ingresos += '<td style="text-align: center;"><b>' + ingreso.total_m +
                          '</b></td>';
                      html_ingresos += '<td style="text-align: center;"><b>' + ingreso.total_t +
                          '</b></td>';
                      html_ingresos += '<td style="text-align: center;"><b>' + ingreso.total_n +
                          '</b></td>';
                      html_ingresos += '<td style="text-align: center;"><b>' + ingreso.total_md +
                          '</b></td>';
                      html_ingresos += '<td style="text-align: center;"><b>' + ingreso
                          .total_caracter_fisico + '</b></td>';
                      html_ingresos += '</tr>';
                    }
                });

                $("#lista_egresos").empty();
                $.each(data.egresos, function(key, egreso) {
                    egreso_total_m += parseFloat(egreso.total_m) || 0;
                    egreso_total_t += parseFloat(egreso.total_t) || 0;
                    egreso_total_n += parseFloat(egreso.total_n) || 0;
                    egreso_total_md += parseFloat(egreso.total_md) || 0;
                    egreso_total_ingreso += parseFloat(egreso.total_caracter_fisico) || 0;

                    html_egresos += '<tr>';
                    html_egresos += '<td>' + egreso.caracter_fisico + '</td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso.total_m +
                        '</b></td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso.total_t +
                        '</b></td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso.total_n +
                        '</b></td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso.total_md +
                        '</b></td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso
                        .total_caracter_fisico + '</b></td>';
                    html_egresos += '</tr>';
                });
                html_egresos += '<tr>';
                html_egresos += '<td><b>TOTAL</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_m + '</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_t + '</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_n + '</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_md + '</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_ingreso + '</b></tn>';
                html_egresos += '</tr>';
                html_egresos += '<tr>';
                html_egresos += '<td><b>BH 24 HORAS</b></td>';
                html_egresos += '<td style="text-align: center;"> - </td>';
                html_egresos += '<td style="text-align: center;"> - </td>';
                html_egresos += '<td style="text-align: center;"> - </td>';
                html_egresos += '<td style="text-align: center;"> - </td>';
                html_egresos += '<td style="text-align: center;"><b>' + (ingreso_total_ingreso -
                    egreso_total_ingreso) + '</b></tn>';
                html_egresos += '</tr>';
                $('#lista_egresos').append(html_egresos);
                html_ingresos += '<tr>';
                html_ingresos += '<td><b>TOTAL</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_m + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_t + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_n + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_md + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_ingreso +
                    '</b></tn>';
                html_ingresos += '</tr>';
                html_ingresos += '<tr>';
                html_ingresos += '<td><b>BALANCE</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + (ingreso_total_m -
                    egreso_total_m) + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + (ingreso_total_t -
                    egreso_total_t) + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + (ingreso_total_n -
                    egreso_total_n) + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + (ingreso_total_md -
                    egreso_total_md) + '</b></td>';
                html_ingresos += '<td style="text-align: center;"> - </tn>';
                html_ingresos += '</tr>';
                $('#lista_ingresos').append(html_ingresos);
                $('#modalListarHojaBalanceHidrico').modal('show');
            }
        })
    };

    /* window.addEventListener('getArguments', async function(e) {
      await ObtieneArgumentosParaFirmaDesdeLaWeb();
    }); */

    async function getArguments() {
        arg = document.getElementById("argumentos").value;
        await dispatchEventClient('sendArguments', arg);
    }

    // window.addEventListener('invokerOk', function(e) {
    //   actualiza_datos_firma_con_token();
    // });

    // window.addEventListener('invokerCancel', function(e) {
    //   MiFuncionCancel();
    // });

    async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.post("{{ route('bh_post_arguments') }}", {
            _token: CSRF_TOKEN,
            type: "W",
            documentName: $('#doc_firmar').val()
        }, function(data, status) {
            document.getElementById("argumentos").value = data;
            getArguments();
        });
    }

    function MiFuncionCancel() {
        alert("El proceso de firma digital fue cancelado.");
    }
    /*************CON TOKEN****************/
    // $bh->flag_firma = 1;
    async function actualiza_datos_firma_con_token() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        let id_resol = $('#id_balance_ver').val();
        $.post("{{ route('actualiza_datos_firma_con_token') }}", {
            _token: CSRF_TOKEN,
            id_resol: id_resol,
        }, function(data, status) {
            location.reload();
        });
    }

    $(document).ready(function() {
        /*************SIN TOKEN****************/
        // $bh->flag_firma = 2;firmarsintokens
        $(document).on('click', '#btnFinalizarBalance', function(e) {
            e.preventDefault();
            firmarBalanceSinTokens();
        })
        const firmarBalanceSinTokens = () => {
            var var_url = '{{ url('actualiza_datos_firma_sin_token') }}';
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

        $(document).on('click', '#btnFirmaBalance', function(e) {
            e.preventDefault();
            firmarBalance();
        })
        //balanceHidricoPDFfirmar
        const firmarBalance = () => {
            $("#flag_firma_enfermeria").val("3");
            const id_balance = $('#id_balance_ver').val();
            $.ajax({
                    url: `/bh_firmar_por_turno/${id_balance}`,
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json'
                })
                .done(function(resultado) {
                    console.log(resultado);
                    if (resultado) {
                        if (resultado.sw) {
                            var csv = "<?php echo url(); ?>/tmp/csv/" + resultado.csv + ".csv";
                            $("#doc_firmar").val(`${resultado.csv}.7z`);
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
