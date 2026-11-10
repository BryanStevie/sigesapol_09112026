<script>
    window.addEventListener('invokerOk', function(e) {
        const quienFirma = $("#flag_firma_enfermeria").val();
        if (quienFirma == "1") {
            MiFuncionOkWebMonitoreo();
        }
        if (parseInt(quienFirma) === 2) {
            MiFuncionOkWebKardex();
        }
        if (quienFirma == "3") {
          actualiza_datos_firma_con_token();
        }
        if (quienFirma == "4") {
          actualiza_datos_firma_con_token_evolucion();
        }
        if (quienFirma == "5") {
          actualiza_datos_firma_con_token_cpt();
        }
        // AGREGAR DEMAS METODOS
    });

    window.addEventListener('invokerCancel', function(e) {
        const flagModuleFirma = $("#flag_firma_enfermeria").val();
        if (parseInt(flagModuleFirma) === 2) {
            MiFuncionCancelKardex();
        } else  {
            MiFuncionCancel();
        }
    });

    window.addEventListener('getArguments', async function(e) {
        const quienFirma = $("#flag_firma_enfermeria").val();
        if (quienFirma == "1") {
            await ObtieneArgumentosParaFirmaDesdeLaWebMonitoreo();
        }
        if (parseInt(quienFirma) === 2) {
            await ObtieneArgumentosParaFirmaDesdeLaWebKardex();
        }
        if (quienFirma == "3") {
            await ObtieneArgumentosParaFirmaDesdeLaWeb();
        }
        if (quienFirma == "4") {
            await ObtieneArgumentosParaFirmaDesdeLaWebEvoluciones();
        }
        if (quienFirma == "5") {
            await ObtieneArgumentosParaFirmaDesdeLaWebCpt();
        }
    });

    function MiFuncionCancel() {
        alert("El proceso de firma digital fue cancelado.");
    }

    async function MiFuncionOkWebKardex() {
        const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        const id_kardex = $('#id_kardex').val();
        $.post("{{route('registrar_firma_kardex_enfermeria')}}", {
            _token: CSRF_TOKEN,
            id_kardex: id_kardex,
            }, function(data, status) {
            Swal.fire({
                icon: "success",
                title: "KARDEX ENFERMERIA",
                text: `SE FIRMO EXITOSAMENTE EL PDF`,
                confirmButtonText: 'ENTENDIDO',
            });
            $('#modalShowDetailKardex').modal('hide');
            searchListKardex();
        // location.reload();
        });
    }

    function MiFuncionCancelKardex() {
        Swal.fire({
        icon: "warning",
        title: "KARDEX ENFERMERIA",
        html: '<h5>PROCESO DE FIRMA CANCELADO</h5>',
        showLoaderOnConfirm: true,
        onOpen: () => {
            Swal.showLoading();
            Swal.close();
        }
        });
    }

</script>