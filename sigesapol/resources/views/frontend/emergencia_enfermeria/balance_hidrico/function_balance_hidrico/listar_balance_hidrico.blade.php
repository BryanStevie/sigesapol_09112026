<script>
    document.querySelector('a.disabled').addEventListener('click', function(event) {
        event.preventDefault(); // Evita la redirección
    });
    $('#fecha_desde').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        const $p = [
            $('#fecha_desde').val(),
            $('#fecha_hasta').val(),
            $('#turnos_inicio').val(),
            $('#id_asegurado_bh').val()
        ];
        listar_balance($p);
    });

    $('#fecha_hasta').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        const $p = [
            $('#fecha_desde').val(),
            $('#fecha_hasta').val(),
            $('#turnos_inicio').val(),
            $('#id_asegurado_bh').val()
        ];
        listar_balance($p);
    });
    $('#turnos_inicio').change(function() {
        const $p = [
            $('#fecha_desde').val(),
            $('#fecha_hasta').val(),
            $('#turnos_inicio').val(),
            $('#id_asegurado_bh').val()
        ];
        listar_balance($p);
    });
    /*=============================================
    AJAX PARA crear Listar
    =============================================*/
    $('body').on('click', '#listarBalanceHidrico', function() {
        const id_enfermeria = JSON.parse(this.getAttribute('data-asegurado')).id_enfermeria;
        const idAsegurado = JSON.parse(this.getAttribute('data-asegurado')).id_asegurado;
        const prioridad = JSON.parse(this.getAttribute('data-asegurado')).prioridad;
        const nombre_tipo_atencion = JSON.parse(this.getAttribute('data-asegurado')).nombre_tipo_atencion;
        const peso_kg = JSON.parse(this.getAttribute('data-asegurado')).peso_kg;
        const nombre_cama = JSON.parse(this.getAttribute('data-asegurado')).nombre_cama;
        const nombre_cuarto = JSON.parse(this.getAttribute('data-asegurado')).nombre_cuarto;
        const id_area = JSON.parse(this.getAttribute('data-asegurado')).id_area;

        $("#listaBalanceHidrico").empty();
        $('#FormularioBalanceHidricoNuevo').trigger("reset");
        $('#tituloFormularioListarBalanceHidrico').html("BALANCE HÍDRICO DE ENFERMERÍA");
        $('#modalListarBalanceHidrico').modal('show');
        $('#id_asegurado_bh').val(idAsegurado);
        $('#id_enfermeria_bh').val(id_enfermeria);
        $('#id_asegurado_bh_editar').val(idAsegurado);
        $('#id_enfermeria_bh_editar').val(id_enfermeria);
        var now = new Date();
        var options = {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        };
        var timeOptions = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        var fecha = now.toLocaleDateString('es-ES', options);
        var hora = now.toLocaleTimeString('es-ES', timeOptions);

        $('#tipo_atencion_disabled').html(nombre_tipo_atencion);
        $('#fecha_atencion_disabled').html(fecha);
        $('#hora_atencion_disabled').html(hora);
        $('#prioridad_disabled').html(prioridad);

        $('#tipo_atencion_disabled_editar').html(nombre_tipo_atencion);
        $('#fecha_atencion_disabled_editar').html(fecha);
        $('#hora_atencion_disabled_editar').html(hora);
        $('#prioridad_disabled_editar').html(prioridad);

        $('#peso').val(peso_kg);
        $('#peso_listado').val(peso_kg);
        $('#peso_hoja_bh').html(peso_kg);
        $('#peso_kg_editar').html(peso_kg);


        $('#ubicacion').val(nombre_cuarto + ' ' + nombre_cama); //UBICACION DEL PACIENTE
        $('#id_area').val(id_area);
        $('#ubicacion_editar').val(nombre_cuarto + ' ' + nombre_cama); //UBICACION DEL PACIENTE
        $('#id_area_editar').val(id_area);
        $("#turnos_inicio").empty();

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
        cargarDatosGlobales(idAsegurado, id_area);
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

    $('body').on('click', '#crearBalanceHidrico', function() {
        var peso = $('#peso_listado').val();
        if (peso <= 0) {
            Swal.fire({
                title: "Información de Servicio de Registros",
                text: "No existe el peso del paciente, los valores A.O y P.I iran con 0",
                icon: "warning",
                timer: 5000,
                showConfirmButton: true
            });
        }
    });

    $('#modalListarBalanceHidrico').on('hidden.bs.modal', function(e) {
        $('#id_asegurado_bh').val("");
        $('#id_asegurado_listado').val("");
    });

    function cargarDatosGlobales(id_asegurado, id_area) {

        var var_url = '{{ url('datos_globales_balance') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_asegurado: id_asegurado,
                id_area: id_area,
            },
            success: function(data) {
                /***********DATOS DEL PACIENTE***********/
                $('#num_documento_bh').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_bh').html(data.datos_paciente.paterno + " " + data.datos_paciente.materno +
                    " " + data.datos_paciente.nombre);
                $('#tipo_sangre_bh').html(data.datos_paciente.tipo_sangre);
                $('#edad_bh').html(data.datos_paciente.edad_corta);
                $('#sexo_bh').html(data.datos_paciente.sexo);
                $('#hc_bh').html(data.datos_paciente.nro_historia);
                $('#parentesgo_bh').html(data.datos_paciente.parentesgo);
                $('#tipo_sangre_bh').html(data.datos_paciente.tipo_sangre ?? 'Sin data');
                $('#alergia_bh').html(
                    data.datos_paciente.tiene_alergias
                        ? `<i style="color: blue;">SI</i>`
                        : `<i style="color: red;">NO</i>`
                );
                /***TIPO DE ATENCIONES*****/
                $.each(data.tipo_atenciones, function(key, value) {
                    $("#tipo_atenciones").append("<option value='" + value.id + "'>" + value
                        .nombre + "</option>");
                });
                /************TURNOS**********/
                ;
                $.each(data.turnos, function(key, value) {
                    $("#turnos_inicio").append("<option value='" + value.id + "'>" + value
                        .descripcion + "</option>");
                });

            }
        });
    }

    function listar_balance($p) {
        var var_url = '{{ url('listar_balance') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                fecha_desde: $p[0],
                fecha_hasta: $p[1],
                turno: $p[2],
                id_asegurado: $p[3],
                id_enfermeria_bh: $('#id_enfermeria_bh').val()
            },
            beforeSend: function() {
                swal({
                    buttons: false,
                    title: "Realizando Consulta ...",
                    text: "Espere un momento mientras se genera la solicitud.",
                    icon: "warning",
                });
            },
            success: function(data) {
                $("#listaBalanceHidrico").empty();
                if (data != null) {
                    var valor = '';
                    $.each(data, function(key, value) {

                        valor = valor +
                            '<tr >' +
                            ' <td style="vertical-align: middle;">' + (key + 1) + '</td>' +
                            ' <td style="vertical-align: middle;">' + value.fecha_registro + ' - ' +
                            value.hora_registro + '</td>' +
                            ' <td style="vertical-align: middle;">' + value.nombre + ' ' + value
                            .paterno + ' ' + value.materno + '</td>' +
                            ' <td style="vertical-align: middle;">' + $('#ubicacion').val() +
                            '</td>' +
                            ' <td style="vertical-align: middle;">' + $('#peso_listado').val() +
                            '</td>' +
                            ' <td style="vertical-align: middle;">' + value.turno + '</td>' +
                            ' <td style="vertical-align: middle;text-transform: uppercase;">' +
                            value.diferencia + ' </td>' +
                            ' <td style="vertical-align: middle;text-transform: uppercase;">' +
                            value.bh24 + '</td>' +
                            ' <td style="vertical-align: middle; ">' +
                            '   <a href="javascript:void(0)" data-flag_firma="' + value.flag_firma +
                            '"  title="Hoja de balance de todos los turnos" data-id_balance="' +
                            value.id + '" data-codigo_fecha_24="' + value.codigo_fecha_24 +
                            '" id="listarHojaBalanceHidrico" name="verEvoluciones" ' +
                            '     data-toggle="tooltip" title="Visualizar" class="btn btn-success btn-xs verEvoluciones"> <i class="fa fa-eye"></i>' +
                            '   </a> ';
                        if (value.flag_firma != 1) {
                            valor += '<a href="javascript:void(0)"  data-id_turno="' + value
                                .id_turno + '" data-id_balance="' + value.id +
                                '" id="editarBalanceHidrico" data-toggle="tooltip" title="Editar"';
                            valor +=
                                'class="btn enf_btn-celeste btn-xs editarEvoluciones"> <i class="fa fa-pencil-square-o"></i>';
                            valor += '</a>';
                        }
                        valor += '  <a href="/pdf_balance_hidrico/' + value.id +
                            '" target="_blank"  data-toggle="tooltip" title="PDF de todos los turnos"';
                        valor +=
                            'class="btn enf_btn-verde-oscuro btn-xs"> <i class="fa fa-download"></i>';
                        valor += '</a>';
                        if (value.flag_firma === 1) {
                            valor += ' <a href="/' + value.ruta_firma +
                                '" target="_blank" data-toggle="tooltip" title="PDF Firmado por turno"';
                            valor +=
                                'class="btn enf_btn-verde-oscuro btn-xs"> <i class="fa fa-pencil" aria-hidden="true"></i> <i class="fa fa-download"></i>';
                            valor += '</a>';
                        }
                        if (value.flag_firma === 2) {
                            valor += '  <a href="/pdf_balance_hidrico_turno/' + value.id +
                                '" target="_blank"  data-toggle="tooltip" title="PDF sin Firma por turno"';
                            valor +=
                                'class="btn enf_btn-verde-oscuro btn-xs"> <i class="fa fa-download"></i>';
                            valor += '</a>';
                        } else if (value.flag_firma === 0) {
                            valor +=
                                '  <a href="#" target="_blank"  data-toggle="tooltip" title="Falta firmar PDF por turno"';
                            valor +=
                                'class="btn enf_btn-verde-oscuro btn-xs disabled"> <i class="fa fa-pencil"></i><i class="fa fa-download"></i>';
                            valor += '</a>';
                        }
                        valor += '</td>';
                        valor += '</tr>';
                    });
                    $("#listaBalanceHidrico").html(valor);

                    swal({
                        buttons: false,
                        title: "Registros encontrados",
                        text: "List de registros cargados de forma correcta.",
                        icon: "warning",
                        timer: 1500,
                    });

                } else {

                    swal({
                        buttons: false,
                        title: "Sin Historial",
                        text: "No se han detectado registros durante la búsqueda realizada.",
                        icon: "warning",
                        timer: 1500,
                    });

                }
            }
        })
    };
</script>
