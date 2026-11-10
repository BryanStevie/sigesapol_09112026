<script>
    $('#fecha_monitoreo_inicial').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        console.log('click sobre fecha_monitoreo_inicial');
    });

    $('#fecha_monitoreo_final').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        console.log('click sobre fecha_monitoreo_final');
    });

    function convertirFecha(fecha) {
        if (!fecha) return null;
        let partes = fecha.split('/');
        if (partes.length === 3) {
            let dia = partes[0];
            let mes = partes[1];
            let año = partes[2];
            return `${año.length === 2 ? '20' + año : año}-${mes.padStart(2, '0')}-${dia.padStart(2, '0')}`;
        }
        return null;
    }

    $('#fecha_monitoreo_inicial').change(function() {
        let fechaInicial = $('#fecha_monitoreo_inicial').val();
        let fechaFinal = $('#fecha_monitoreo_final').val();
        if (fechaInicial && fechaFinal) {
            let inicioFormateado = convertirFecha(fechaInicial);
            let finalFormateado = convertirFecha(fechaFinal);
            let fechaInicioDate = new Date(inicioFormateado);
            let fechaFinDate = new Date(finalFormateado);
            if (fechaInicioDate > fechaFinDate) {
                bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                return false;
            }
        }
    });

    $('#modalListarMonitoreo').on('hidden.bs.modal', function (e) {
        $('#tblMonitoreos').html('');
        $('#listado_turno_moni_handler').val(2).trigger('change');
    });
    
    $('#listarMonitoreoDetalles').on('click', function(e) {
        let fechaInicial = $('#fecha_monitoreo_inicial').val();
        let fechaFinal = $('#fecha_monitoreo_final').val();
        if (fechaInicial && fechaFinal) {
            let inicioFormateado = convertirFecha(fechaInicial);
            let finalFormateado = convertirFecha(fechaFinal);
            let fechaInicioDate = new Date(inicioFormateado);
            let fechaFinDate = new Date(finalFormateado);
            if (fechaInicioDate > fechaFinDate) {
                bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                return false;
            }
        }
        const formData = $('#FormularioListarMonitoreo').serializeArray();
        const data = $('#FormularioListarMonitoreo').serialize();
        console.log(formData);
        Swal.fire({
            title:
                "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
        $.ajax({
            url: 'api/monitoreo/listarMonitoreoDetalles_hospi',
            type: 'GET',
            data: data,
            dataType: 'json',
            success: function(res) {
                const rows = renderListadoMonitoreo(res);
                $("#tblMonitoreos").empty();
                rows.forEach((row) => {
                    $("#tblMonitoreos").append(row);
                });
                $('.editarMonitoreo').each(function(i, button) {
                    $(button).on('click', function(e) {
                        const fecha_hora = this.getAttribute('data-monitoreo-date');
                        const id_monitoreo = this.getAttribute('data-monitoreo-id');
                        renderEditModal(fecha_hora, id_monitoreo);
                    });
                });
                $('.detalleMonitoreo').each(function(i, button) {
                    $(button).on('click', function(e) {
                        const fecha_hora = this.getAttribute('data-monitoreo-date');
                        const id_monitoreo = this.getAttribute('data-monitoreo-id');
                        renderDetalleModal(fecha_hora, id_monitoreo);
                    });
                });
                $('.exportarMonitoreo').each(function(i, button) {
                    $(button).on('click', function(e) {
                        const fecha_hora = this.getAttribute('data-monitoreo-date');
                        const id_monitoreo = this.getAttribute('data-monitoreo-id');
                        console.log({fecha_hora, id_monitoreo});
                    });
                });
                /* $('.firmarMonitoreo').each(function(i, button) {
                    $(button).on('click', function(e) {
                        const fecha_hora = this.getAttribute('data-monitoreo-date');
                        const id_monitoreo = this.getAttribute('data-monitoreo-id');
                        console.log('llega antes de llamar al metodo firmar 1');
                        firmarMonitoreo(id_monitoreo, fecha_hora);
                    });
                }); */
                $('.firmarMonitoreo').each(function(i, button) {
                    $(button).off('click').on('click', function(e) {
                        const fecha_hora = this.getAttribute('data-monitoreo-date');
                        const id_monitoreo = this.getAttribute('data-monitoreo-id');
                        console.log('Llega antes de llamar al método firmar');
                        firmarMonitoreo(id_monitoreo, fecha_hora);
                        e.preventDefault();
                    });
                });
                Swal.fire({
                    icon: "success",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                    confirmButtonText: "ENTENDIDO",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: "ENTENDIDO",
                });
            },
            error: function(res) {
                Swal.fire({
                    icon: "error",
                    title: "ERROR ",
                    text: "Servicio no disponible. intentelo en un minutos.",
                });
            }
        })
    });

    $('#exportarMonitoreoPorDia').on('click', function(e) {
        const formulario = document.getElementById('FormularioListarMonitoreo');
        const fechaFin = formulario.querySelector('#fecha_monitoreo_final').value;
        const partesFecha = fechaFin.split('/');
        const dia = partesFecha[0];
        const mes = partesFecha[1];
        const año = partesFecha[2];
        const fechaConvertida = `${año}-${mes}-${dia}`;
        const id_enfermeria_moni = formulario.querySelector('#id_enfermeria_moni').value;
        this.href = `api/monitoreo/exportarMonitoreoPorDia/${id_enfermeria_moni}/${fechaConvertida}/3`;
        this.setAttribute('target', '_blank');
        this.click();
    });

    function firmarMonitoreo(id, fecha_hora) {
        const [fecha, hora] = fecha_hora.split(' ');
        // MONITOREO => 1
        $("#flag_firma_enfermeria").val("1");
        $.ajax({
            url: `/api/monitoreo/exportarMonitoreoParaFirmar/${id}/${fecha} ${hora}/3`,
            type: "post",
            data: $("#FormularioListarMonitoreo").serialize(),
            dataType: 'json'
        })
        .done(function(resultado) {
        console.log(resultado);
        if (resultado.sw) {
            var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
            $("#doc_firmar_monitoreo").val(`${resultado.csv}.7z`);
            $("#opt_firma_monitoreo").val(`${resultado.opt}`);
            $("#id_monitoreo_firma").val(id);
            $("#fecha_hora_firma").val(fecha_hora);
            initInvoker('W');
        } else {
        }
        $('#btnLiquidacionFirmarPdf').attr("disabled", false);
        })
        .fail(function(jqXHR, textStatus) {
            console.log("Request failed: " + textStatus)
        })
        .always(function() {
        });
    }

    /* window.addEventListener('getArgumentsMonitoreo', async function(e) {
        await ObtieneArgumentosParaFirmaDesdeLaWebMonitoreo();
    }); */

    async function getArgumentsMonitoreo() {
        arg = document.getElementById("argumentos_monitoreo").value;
        await dispatchEventClient('sendArguments', arg);
    }

    /* window.addEventListener('invokerOk', function(e) {
        MiFuncionOkWebMonitoreo();
    }); */

    /* window.addEventListener('invokerCancel', function(e) {
        MiFuncionCancel();
    }); */

    async function ObtieneArgumentosParaFirmaDesdeLaWebMonitoreo() {
        console.log('ESTA OK');
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.post("{{route('postArguments')}}", 
        {
            _token: CSRF_TOKEN,
            type: "W",
            documentName: $('#doc_firmar_monitoreo').val(),
            opt_firma: $('#opt_firma_monitoreo').val(),    
            entidad: <?=Auth::user()->dni?>
        }, 
        function(data, status) {
            document.getElementById("argumentos_monitoreo").value = data;
            getArgumentsMonitoreo();
        });
    }

    async function MiFuncionOkWebMonitoreo() {
        console.log('ESTA OKK 2');
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.post("{{route('actualiza_monitoreo')}}", 
        {
        _token: CSRF_TOKEN,
        id_monitoreo: $("#id_monitoreo_firma").val(),
        fecha_hora: $("#fecha_hora_firma").val(),
        }, function(data, status) {
            console.log(data);
            $('#closeMonitoreoDetalle').click();
            $('#listarMonitoreoDetalles').click();
        }).always(function () {
            $("#id_monitoreo_firma").val("");
            $("#fecha_hora_firma").val("");
        });
    }

    /* function MiFuncionCancel() {
        alert("El proceso de firma digital fue cancelado.");
    } */

    function renderListadoMonitoreo(monitoreos) {
        const id_user = $('#id_user').val();
        const rows = monitoreos.map(({id, fecha, hora, responsable, ubicacion, perdida_insensible, agua_oxigenacion, id_responsable, firma, ruta_firma}, i) => {
            const [cuarto, cama] = ubicacion.split("-");
            ++i;
            return `
                <tr role="row" class="odd row-monitoreo">
                    <td>${i}</td>
                    <td><b>FECHA: </b>${fecha} <b>HORA: </b>${hora}</td>
                    <td>HOSPITALIZACION<br><b>Cuarto:</b> ${cuarto} <br><b>Cama:</b> ${cama}</td>
                    <td>${responsable}</td>
                    <td>
                        <span><b>P. I: </b></span>
                        <span>${perdida_insensible ?? "Sin data"}</span>
                        <br>
                        <span><b>A. O: </b></span>
                        <span>${agua_oxigenacion ?? "Sin data"}</span>
                    </td>
                    <td class="funciones">
                        <a href="javascript:void(0)" aria-hidden="true"
                        data-toggle="tooltip" title=""
                        data-monitoreo-id="${id}"
                        data-monitoreo-date="${fecha} ${hora}"
                        class="btn btn-success btn-xs detalleMonitoreo"> <i
                        class="fa fa-eye"></i>
                        </a>
                        ${parseInt(firma) ? `
                            <a href="${ruta_firma}" aria-hidden="true"
                                target="_blank"
                                data-toggle="tooltip" title=""
                                class="btn btn-success btn-xs"> <i
                                class="fa fa-fw fa-download"></i>
                            </a>
                            ` : `
                            <a href="/api/monitoreo/exportarMonitoreo/${id}/${fecha} ${hora}/3" aria-hidden="true"
                                target="_blank"
                                data-toggle="tooltip" title=""
                                data-monitoreo-id="${id}"
                                data-monitoreo-date="${fecha} ${hora}"
                                class="btn btn-success btn-xs exportarMonitoreo"> <i
                                class="fa fa-fw fa-download"></i>
                            </a>
                            `
                        }
                        ${id_user == id_responsable ? `
                            ${!parseInt(firma) ? `
                                <a href="javascript:void(0)" aria-hidden="true"
                                    data-toggle="tooltip" title=""
                                    data-monitoreo-id="${id}"
                                    data-monitoreo-date="${fecha} ${hora}"
                                    class="btn btn-success btn-xs editarMonitoreo"> 
                                    <i class="fa fa-edit"></i>
                                </a>
                                ` : ``
                            }
                            ` : ``
                            }
                    </td>
                </tr>
            `;
        });
        return rows;
    }

    $('#modalListarMonitoreo').on('hidden.bs.modal', function () {
        $("#tblMonitoreos").empty();
    });


    /*=============================================
    AJAX PARA crear Listar
    =============================================*/
    $('body').on('click', '#listarMonitoreos', function() {
        $('#FormularioListarMonitoreo').trigger("reset");
        $('#tituloFormularioListarMonitoreo').html("Listado de Monitoreo");
        const idAsegurado = JSON.parse(this.getAttribute('data-asegurado-m')).id_asegurado;
        /* const idPrestacion = JSON.parse(this.getAttribute('data-asegurado-m')).id_prestacion; */
        const idNota = JSON.parse(this.getAttribute('data-asegurado-m')).id_enfermeria;
        const edadPaciente = JSON.parse(this.getAttribute('data-asegurado-m')).edad;
        $("#edadPaciente").val(edadPaciente);
        $('#id_asegurado_listado').val(idAsegurado);
        $('#id_enfermeria_moni').val(idNota);
        $.ajax({
            url: '{{ url('getDatosPacienteForEnfermeria/') }}/' + idAsegurado,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {  
                console.log(data);
                renderUserData(data);
                $('#modalListarMonitoreo').modal('show');
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
                $.ajax({
                    url: `/buscar_ptje_glasgow_nota/${idNota}`,
                    success: function(result) {
                        $("#crearMonitoreo").attr('data-user-glasgow', result);
                        $("#crearMonitoreo").attr('data-user-id', idAsegurado);
                        $("#crearMonitoreo").attr('data-enfermeria-id', idNota);
                        $("#crearMonitoreo").attr('data-asegurado-edad', edadPaciente);
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
                        /* $.ajax({
                            url: `/buscar_datos_prestacion_enfermeria/${idPrestacion}`,
                            success: function(result) {
                                const data = JSON.stringify(result);
                                $("#diagnosticosPrestacionPrevia").html(data);
                            },
                            error: function(err) {
                                Swal.fire({
                                    icon: "error",
                                    title: "ERROR",
                                    text: "Ocurrio un error inesperado al consultar los diagnosticos de la prestacion enfermeria.",
                                });
                            }
                        }); */
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: "error",
                            title: "ERROR",
                            text: "Ocurrio un error inesperado al consultar la nota de enfermeria.",
                        });
                    }
                });
            },
            error: function(err) {
                Swal.fire({
                    icon: "error",
                    title: "ERROR",
                    text: "Ocurrio un error inesperado al consultar datos del asegurado.",
                });
            }
        });
    });
</script>