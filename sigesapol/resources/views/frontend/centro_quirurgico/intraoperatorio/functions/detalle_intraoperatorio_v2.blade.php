<script>
    /****************FUNCIONES PARA LA FIRMA***************************/
    async function getArguments() {
        arg = document.getElementById("argumentos").value;
        await dispatchEventClient('sendArguments', arg);
    }

    async function ObtieneArgumentosParaFirmaDesdeLaWebIntraoperatorio() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.post("{{ route('intraoperatorio_post_arguments') }}", {
            _token: CSRF_TOKEN,
            type: "W",
            documentName: $('#doc_firmar').val()
        }, function(data, status) {
            document.getElementById("argumentos").value = data;
            getArguments();
        });
    }

    async function actualiza_datos_firma_con_token_io() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        let id_resol = $('#id_intraoperatorio').val();
        console.log(id_resol);
        $.post("{{ route('actualiza_datos_firma_con_token_io') }}", {
            _token: CSRF_TOKEN,
            id_resol: id_resol,
        }, function(data, status) {
            location.reload();
        });
    }

    const ActualizaIntraoperatorioSinTokens = () => {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        let id_resol = $('#id_intraoperatorio').val();
        console.log(id_resol);
        $.post("{{ route('actualiza_datos_firma_sin_token_io') }}", {
            _token: CSRF_TOKEN,
            id_resol: id_resol,
        }, function(data, status) {
            location.reload();
        });
    }


    $(document).ready(function() {
        $(document).on('click', '#btnFinalizarIntraoperatorio', function(e) {
            e.preventDefault();
            ActualizaIntraoperatorioSinTokens();
        });
        $(document).on('click', '#btnFirmaIntraoperatorio', function(e) {
            e.preventDefault();
            firmarIntraoperatorio();
        });
        $(document).on('click', '#btnAnularIntraoperatorio', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            anularIntraoperatorio(id);
        });

        function anularIntraoperatorio(intraoperatorio_id) {
            Swal.fire({
                title: 'Anulacion de registro',
                text: '¿Estás seguro de anular el registro intraoperario?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/anular_intraoperatorio`,
                        type: 'POST',
                        data: {
                            intraoperatorio_id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: '¡Listo!',
                                text: 'La acción se realizó correctamente.',
                                icon: 'success'
                            });
                            $('#modal-detalle-intraoperatorio').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al realizar la acción.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }

        const firmarIntraoperatorio = () => {
            const id_intraoperatorio = $('#id_intraoperatorio-det').val();
            $.ajax({
                    url: `/intraoperatorio_firmar/${id_intraoperatorio}`,
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