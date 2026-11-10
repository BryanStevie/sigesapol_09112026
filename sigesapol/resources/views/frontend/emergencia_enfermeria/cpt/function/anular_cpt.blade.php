<script>
    $('body').on('click', '#anularCpt', function() {
        var id_cpt = $(this).data('id_cpt');
        $('#anularFormularioCpt').trigger("reset");
        $('#modalAnularCpt').modal('show');
        $('#id_cpt_anul').val(id_cpt);
    });

    $(document).ready(function() {
        $('#AnularCpt').click(function(e) {
            e.preventDefault();
            var motivoAnulacion = $('#anulacion_motivo_cpt').val();
            if (motivoAnulacion == null || motivoAnulacion.length <= 5) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: "Proceso cancelado. Ingrese un motivo de la anulacion mayor a 5 caracteres para el campo motivo anulacion.",
                    icon: "error",
                    timer: 2500,
                });

                $('#anulacion_motivo_cpt').addClass('borde-alerta');
                setTimeout(function() {
                    $('#anulacion_motivo_cpt').focus();
                }, 1000);
                return;
            } else {
                $('#anulacion_motivo_cpt').removeClass('borde-alerta');
            }
            var token = $('#_token').val();
            var route = '{{ url('motivo_anulacion_cpt') }}';
            swal({
                buttons: true,
                title: "¿Desea Anular el registro?",
                text: "Recuerde verificar que todos los datos sean correctos.",
                icon: "warning",
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var formData = new FormData($("#anularFormularioCpt")[0]);
                    console.log(formData)
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
                                if (data != 0) {
                                    $('#anularFormularioCpt').trigger(
                                        "reset");
                                    $('#modalAnularCpt').modal('hide');
                                    swal({
                                        buttons: false,
                                        title: "Anulación Exitoso",
                                        text: "El registro fue anulado Exitosamente.",
                                        icon: "success",
                                        timer: 1500,
                                    });
                                    const $p = [
                                        $('#fecha_cpt_inicial_listado').val(),
                                        $('#fecha_cpt_final_listado').val(),
                                        $('#id_turno_cpt').val(),
                                        $('#tipo_atenciones_listado_cpt').val(),
                                        $('#id_asegurado_cpt').val(),
                                    ];
                                    listar_cpt($p);
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
                        }
                    });

                } else {

                    swal({
                        buttons: false,
                        title: "Operación Cancelada",
                        text: "Proceso de Registro Cancelado.",
                        icon: "error",
                        timer: 1500,
                    });

                }
            });


        });
    });
</script>
