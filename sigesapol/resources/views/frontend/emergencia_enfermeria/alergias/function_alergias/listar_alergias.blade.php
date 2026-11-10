<script>
    $('#fecha_incidencia_inicial').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        console.log('click sobre fecha_incidencia_inicial');
    });

    $('#fecha_incidencia_final').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom left"
    }).on('changeDate', function(e) {
        console.log('click sobre fecha_incidencia_final');
    });

    $(document).ready(function(e) {
        $('#modalListarAlergias').on('hidden.bs.modal', function(e) {
            const area_id = $('#id_area').val();
            const asegurado_id_bh = $('#id_asegurado_bh').val();
            const asegurado_id_moni = $('#id_asegurado_listado').val();
            const asegurado_id_evol = $('#id_asegurado_evol').val();
            if (asegurado_id_bh != '') {
                cargarDatosGlobales(asegurado_id_bh, area_id);
            } else if (asegurado_id_moni != '') {
                const id_enfermeria_moni = $("#id_enfermeria_moni").val();
                cargarDatosGlobales_Moni(id_enfermeria_moni);
            } else if (asegurado_id_evol != '') {
                cargarDatosGlobales_Evol(asegurado_id_evol);
            }
        });
    });

    /*=============================================
    AJAX PARA crear Listar Alergias
    =============================================*/
    $(document).on('click', '.listarAlergiasV2', function() {
        $('#tipoatenciones_alg').val('').trigger('change.select2');
        let id_asegurado_final = '';
        var id_asegurado_bh = $('#id_asegurado_bh').val();
        var id_asegurado_moni = $('#id_asegurado_listado').val();
        var id_asegurado_evol = $('#id_asegurado_evol').val();
        var id_servicio = $('#id_servicio').val();

        $('#FormularioListarAlergias').trigger("reset");
        $('#tituloFormularioListarAlergias').html("Listado de Alergias Médicas");
        $('#modalListarAlergias').modal('show');

        tipo_atenciones();
        if (id_asegurado_bh != '') {
            id_asegurado_final = id_asegurado_bh;
            cargarCabeceraModalA(id_asegurado_bh, id_servicio);
        } else if (id_asegurado_moni != '') {
            id_asegurado_final = id_asegurado_moni;
            cargarCabeceraModalA(id_asegurado_moni, id_servicio);
        } else if (id_asegurado_evol != '') {
            id_asegurado_final = id_asegurado_evol;
            cargarCabeceraModalA(id_asegurado_evol, id_servicio);
        }

        var tipoatenciones = $('#tipoatenciones_alg').val();
        var estado = $('#estado_alg').val();

        listar_medicamentos_alergias_V2(
            id_asegurado_final,
            tipoatenciones,
            estado
        );
    });

    function listar_medicamentos_alergias_V2(asegurado_id, tipoatenciones, estado) {
        var var_url = '{{ url('lista_alergias_medicas_filtro_V2 ') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                asegurado_id: asegurado_id,
                tipoatenciones: tipoatenciones,
                estado: estado,
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

                $("#listaAlergiasMedicas").empty();
                if (data != null && data.length > 0) {
                    var valor = '';
                    $.each(data, function(key, value) {
                        var obj = '';

                        var editar = '';

                        if (value.estado == true) {
                            editar =
                                '   <a href="javascript:void(0)" aria-hidden="true" ' +
                                'data-toggle="tooltip" data-id_alergia="' + value.id + '" ' +
                                'id="editarAlergias" title="Editar" ' +
                                'class="btn btn-warning btn-xs"> ' +
                                '<i class="fa fa-pencil-square-o"></i>' +
                                '   </a>';
                        }

                        if (value.estado == true) {
                            obj = obj +
                                '   <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" title="Validado"' +
                                '   class="btn btn-success btn-xs" id="validarAlergia" onclick="validar(' +
                                value.estado + ',' + value.id +
                                ')"><i class="fa fa-thumbs-o-up"></i>' +
                                '   </a>';
                        } else {
                            obj = obj +
                                '   <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" title="Validar"' +
                                '   class="btn btn-danger btn-xs" id="validarAlergia" onclick="validar(' +
                                value.estado + ',' + value.id +
                                ')"><i class="fa fa-thumbs-o-down"></i>' +
                                '   </a>';
                        }

                        valor = valor +
                            '<tr >' +
                            ' <td style="vertical-align: middle;">' + value.fecha + '</td>' +
                            ' <td style="vertical-align: middle;text-transform: uppercase;">' +
                            value.medicamento + '</td>' +
                            ' <td style="vertical-align: middle;text-transform: uppercase;">' +
                            value.nombre + ' ' + value.paterno + ' ' + value.materno + '</td>' +
                            ' <td style="vertical-align: middle;text-transform: uppercase;">' +
                            value.tipo_atencion + '</td>' +
                            ' <td style="vertical-align: middle;">' +
                            '   <a href="javascript:void(0)" aria-hidden="true" data-id_alergia="' +
                            value.id + '" id="verAlergias" name="verAlergias" ' +
                            '     data-toggle="tooltip" title="Visualizar" class="btn btn-success btn-xs"> <i class="fa fa-eye"></i>' +
                            '   </a>' + editar + obj +
                            ' </td>' +
                            '</tr>';
                    });
                    $("#listaAlergiasMedicas").html(valor);

                    swal({
                        buttons: false,
                        title: "Registros encontrados",
                        text: "Lista de registros cargados de forma correcta.",
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

    function calcularEdadAnios2(fechaNacimiento) {
        var partes = fechaNacimiento.split('/');

        var dia = parseInt(partes[0]);
        var mes = parseInt(partes[1]) - 1;
        var anio = parseInt(partes[2]);

        var hoy = new Date();

        var edad = hoy.getFullYear() - anio;

        if (
            hoy.getMonth() < mes ||
            (hoy.getMonth() === mes && hoy.getDate() < dia)
        ) {
            edad--;
        }

        return edad;
    }

    function cargarCabeceraModalA(id_asegurado, id_servicio) {
        $.ajax({
            url: '{{ url('getAseguradoby_id') }}/' + id_asegurado,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                console.log(data);
                $('#numerodoc_al').html(data.nro_doc_ident);
                $('#nombres_al').html(data.paterno + " " + data.materno + " " + data.nombre);
                $('#tiposangre_al').html(data.tipo_sangre ?? 'Sin data.');
                $('#edad_al').html(calcularEdadAnios2(data.bak_fecha_nac));
                $('#sexo_al').html(data.sexo);
                $('#hc_al').html(data.nro_historia);

                $('#id_asegurado_alergia').val(id_asegurado);
                $('#id_servicio_alergia').val(id_servicio);
            }
        });
    }

    function getListadoAlergias() {
        var tipoatenciones = document.getElementById('tipoatenciones_alg').value;
        var estado = document.getElementById('estado_alg').value;
        let id_asegurado_final = '';
        var id_asegurado_bh = $('#id_asegurado_bh').val();
        var id_asegurado_moni = $('#id_asegurado_listado').val();
        var id_asegurado_evol = $('#id_asegurado_evol').val();
        if (id_asegurado_bh != '') {
            id_asegurado_final = id_asegurado_bh;
        } else if (id_asegurado_moni != '') {
            id_asegurado_final = id_asegurado_moni;
        } else if (id_asegurado_evol != '') {
            id_asegurado_final = id_asegurado_evol;
        }

        listar_medicamentos_alergias_V2(id_asegurado_final, tipoatenciones, estado);
    }

    function tipo_atenciones() {
        $("#tipoatenciones_alg").empty();
        $("#tipoatenciones_alg").append("<option value=''>--SELECCIONAR--</option>");
        var var_url = '{{ url('tipo_atenciones ') }}';
        $.ajax({
            url: var_url,
            type: 'get',
            dataType: 'JSON',
            success: function(data) {
                $.each(data, function(key, value) {
                    $("#tipoatenciones_alg").append("<option value='" + value.id + "'>" + value
                        .nombre +
                        "</option>");
                });
            }
        })
    };
</script>
