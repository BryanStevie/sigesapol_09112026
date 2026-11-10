<script src='../../../funciones/function.js'></script>
<style>
    /* Estilos para el div con scroll */
    #scrollableDiv {
        width: 600px;
        /* Ancho del div */
        height: 600px;
        /* Alto del div */
        overflow-y: scroll;
        /* Habilita el scroll vertical */
        overflow-x: hidden;
        /* Oculta el scroll horizontal si existe */
        border: 1px solid #ccc;
        /* Borde del div */
        padding: 10px;
        /* Espaciado interno del div */
    }
</style>

<script>
    $('#fecha_cpt_inicial_listado').datepicker({
        useCurrent: false,
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        autoclose: true,
        orientation: "bottom left",
    }).on('changeDate', function(e) {
        const $p = [
            $('#fecha_cpt_inicial_listado').val(),
            $('#fecha_cpt_final_listado').val(),
            $('#id_turno_cpt').val(),
            $('#tipo_atenciones_listado_cpt').val(),
            $('#id_asegurado_cpt').val(),
        ];
        listar_cpt($p);
    });

    $('#fecha_cpt_final_listado').datepicker({
        useCurrent: false,
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        autoclose: true,
        orientation: "bottom left",
    }).on('changeDate', function(e) {
        const $p = [
            $('#fecha_cpt_inicial_listado').val(),
            $('#fecha_cpt_final_listado').val(),
            $('#id_turno_cpt').val(),
            $('#tipo_atenciones_listado_cpt').val(),
            $('#id_asegurado_cpt').val(),
        ];
        listar_cpt($p);
    });

    $('#id_turno_cpt').change(function() {
        const $p = [
            $('#fecha_cpt_inicial_listado').val(),
            $('#fecha_cpt_final_listado').val(),
            $('#id_turno_cpt').val(),
            $('#tipo_atenciones_listado_cpt').val(),
            $('#id_asegurado_cpt').val(),
        ];
        listar_cpt($p);
    });
    $('#tipo_atenciones_listado_cpt').change(function() {
        const $p = [
            $('#fecha_cpt_inicial_listado').val(),
            $('#fecha_cpt_final_listado').val(),
            $('#id_turno_cpt').val(),
            $('#tipo_atenciones_listado_cpt').val(),
            $('#id_asegurado_cpt').val(),
        ];
        listar_cpt($p);
    });
    /*=============================================
    AJAX PARA crear ListarEvolucionesS
    =============================================*/
    /* $('#modalListarEvoluciones').on('shown.bs.modal', function () {
       const fecha_ingreso = JSON.parse($("#listarEvoluciones").attr('data-principal')).fecha_ingreso;
       $('#fecha_evolucion_bus_inicial_listado').val(fecha_ingreso);
       $('#fecha_evolucion_bus_inicial_listado').attr("disabled", true);
     }); */
    $('body').on('click', '#listarCptHospitalizacion', function() {

        const id_asegurado = JSON.parse(this.getAttribute('data-principal')).id_asegurado;
        const nombre_tipo_atencion = JSON.parse(this.getAttribute('data-principal')).nombre_tipo_atencion;
        const id_enfermeria = JSON.parse(this.getAttribute('data-principal')).id_enfermeria; //id_enfermeria
        const id_ext = JSON.parse(this.getAttribute('data-principal')).id;
        const id_prestacion = JSON.parse(this.getAttribute('data-principal')).id_prestacion;
        const estado_hospitalizacion = JSON.parse(this.getAttribute('data-principal')).estado;
        var id_tipo_atenciones_listado = 3;

        const prioridad = JSON.parse(this.getAttribute('data-principal')).servicio_destino;
        var servicio = JSON.parse(this.getAttribute('data-principal')).nombre_consultorio;
        const area = JSON.parse(this.getAttribute('data-principal')).descripcion;
        const nombre_cama = JSON.parse(this.getAttribute('data-principal')).nombre_cama;
        const nombre_cuarto = JSON.parse(this.getAttribute('data-principal')).nombre_cuarto;

        $("#listaDatosCpt").empty();
        $('#FormularioListarCpt').trigger("reset");
        $('#id_asegurado_cpt').val(id_asegurado);
        $('#id_prestacion_listado_cpt').val(id_prestacion); //id de la pretacion de la atencion 
        $('#id_tipo_atenciones_listado_cpt').val(
            id_tipo_atenciones_listado); //tipo de atencion del listado que se tomara para el guardado
        $('#id_enfermeria_cpt').val(id_enfermeria);
        $('#id_ext_cpt').val(id_ext);
        $('#id_asegurado_cpt').val(id_asegurado);
        $('#id_enfermeria_ver_cpt').val(id_enfermeria);
        $('#nombre_tipo_atencion_cpt').val(nombre_tipo_atencion);
        $('#prioridad_cpt').val(prioridad);

        /* $("#crearCpt").prop("disabled", false);
        $("#crearCpt").parent().tooltip('destroy');
        if (estado_hospitalizacion >= 5) {
            $("#crearCpt").prop("disabled", true);
            $("#crearCpt").parent().tooltip();
        } */

        const $p = [
            $('#fecha_cpt_inicial_listado').val(),
            $('#fecha_cpt_final_listado').val(),
            $('#id_turno_cpt').val(),
            3,
            id_asegurado,
        ];

        listar_cpt($p);

        $('#prioridad_cpt').val(prioridad);
        $('#servicio_cpt').val(servicio);
        $('#area_cpt').val(area);
        $('#nombre_cama_cpt').val(nombre_cama);
        $('#nombre_cuarto_cpt').val(nombre_cuarto);
    });

    function listar_cpt($p) {
        var var_url = '{{ url('lista_nota_ingreso_cpt') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                fecha_evolucion_bus_inicial_listado: $p[0],
                fecha_evolucion_bus_final_listado: $p[1],
                id_turno: $p[2],
                id_tipo_atencion: 3,
                id_asegurado: $p[4],
                id_enfermeria: $("#id_enfermeria_cpt").val(),
                id_ext: $("#id_ext_cpt").val(),
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
                /***********DATOS DEL PACIENTE***********/
                $('#num_documento_cpt').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_cpt').html(data.datos_paciente.paterno + " " + data.datos_paciente
                    .materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_cpt').html(data.datos_paciente.tipo_sangre);
                $('#edad_cpt').html(data.datos_paciente.edad_corta);
                $('#sexo_cpt').html(data.datos_paciente.sexo);
                $('#hc_cpt').html(data.datos_paciente.nro_historia);
                if (data.estado_alergia.flag_alergia) {
                    $('#alergia_si_listado_cpt').show();
                } else {
                    $('#alergia_no_listado_cpt').show();
                }

                var cuerpo_turnos = '';

                $("#listaDatosCpt").empty();
                if (data.listado_cpt != null) {
                    var valor = '';
                    $.each(data.listado_cpt, function(key, value) {
                        valor = valor +
                            '<tr >' +
                            ' <td style="vertical-align: middle;"><b>FECHA:</b> ' + value
                            .fecha_cpt + '<br><b>HORA:</b> ' + value.hora_cpt +
                            '<br><b>TURNO:</b> ' + value.descripcion_turno + '</td>' +
                            ' <td style="vertical-align: middle;"> ' + '<br><b>Servicio:</b> ' + $(
                                '#prioridad_cpt').val() + '</td>' +
                            ' <td style="vertical-align: middle;"> <br>' + 'Cama' + ': ' + $(
                                '#nombre_cama_cpt').val() + '<br> Cuarto: ' + $(
                                '#nombre_cuarto_cpt')
                            .val() + '</td>' +
                            ' <td style="vertical-align: middle;">' + value.name + ' ' + value
                            .paterno + ' ' + value.materno + '</td>' +
                            ' <td style="vertical-align: middle; text-align: center;">' +
                            '   <a href="javascript:void(0)" data-flag_firma="' + value.flag_firma +
                            '" data-id_cpt="' + value.id_cpt +
                            '"  data-id_asegurado="' + value.id_asegurado +
                            '"id="verDetalleCpt" name="verDetalleCpt" ' +
                            '     data-toggle="tooltip" title="Visualizar" class="btn btn-success btn-xs verDetalleCpt"> <i class="fa fa-eye"></i>' +
                            '   </a>';
                        if (value.flag_firma != 1) {
                            valor += ' <a href="javascript:void(0)"  data-id_cpt="' + value
                                .id_cpt +
                                '"  data-id_asegurado="' + value.id_asegurado +
                                '" id="editarCpt" data-toggle="tooltip" title="Editar"' +
                                ' class="btn enf_btn-celeste btn-xs editarCpt"> <i class="fa fa-pencil-square-o"></i>' +
                                '</a>';
                            valor += ' <a href="/pdf_nota_ingreso_cpt/' + value.id +
                                '" target="_blank"  data-toggle="tooltip" title="PDF"' +
                                '     class="btn enf_btn-verde-oscuro btn-xs"> <i class="fa fa-download"></i>' +
                                '   </a>';
                        }
                        /* if (value.flag_firma === 1) {
                            valor += ' <a href="/' + value.ruta_firma +
                                '" target="_blank" data-toggle="tooltip" title="PDF Firmado por turno"';
                            valor +=
                                'class="btn enf_btn-verde-oscuro btn-xs"> <i class="fa fa-pencil" aria-hidden="true"></i> <i class="fa fa-download"></i>';
                            valor += '</a>';
                        } */
                        /* if (value.flag_firma === 0) {
                            valor +=
                                '  <a href="#" target="_blank"  data-toggle="tooltip" title="Falta firmar PDF por turno"';
                            valor +=
                                'class="btn enf_btn-verde-oscuro btn-xs disabled"> <i class="fa fa-pencil"></i><i class="fa fa-download"></i>';
                            valor += '</a>';
                        } */
                       if (value.flag_firma === 1) {
                            valor += ' <a href="/' + value.ruta_firma +
                                '" target="_blank" data-toggle="tooltip" title="PDF Firmado por turno"';
                            valor +=
                                'class="btn enf_btn-verde-oscuro btn-xs"> <i class="fa fa-pencil" aria-hidden="true"></i> <i class="fa fa-download"></i>';
                            valor += '</a>';
                        }
                        if (value.flag_firma === 0) {
                            valor +=
                                '  <a href="#" target="_blank"  data-toggle="tooltip" title="Falta firmar PDF por turno"';
                            valor +=
                                'class="btn enf_btn-verde-oscuro btn-xs disabled"> <i class="fa fa-pencil"></i><i class="fa fa-download"></i>';
                            valor += '</a>';
                        }
                        valor += ' <a href="javascript:void(0)"   data-id_cpt="' + value
                            .id_cpt +
                            '" id="anularCpt" data-toggle="tooltip" title="Anular"' +
                            '     class="btn btn-danger btn-xs anularCpt"><i class="fa fa-times"></i>' +
                            '   </a>';

                        valor += '</td>' +
                            '</tr>';
                    });
                    $("#listaDatosCpt").html(valor);

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
    /***************VALIDACION DE FECHAS**************/
    var fechaInicioInput = $("#fecha_cpt_inicial_listado");
    var fechaFinalInput = $("#fecha_cpt_final_listado");

    // Inicializar el datepicker
    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });

    // Validación de fecha inicial y fecha final
    fechaInicioInput.on("change", function() {
        var fechaInicio = fechaInicioInput.datepicker("getDate");
        var fechaFinal = fechaFinalInput.datepicker("getDate");

        // Si la fecha final está seleccionada
        if (fechaFinal && fechaInicio > fechaFinal) {
            alert("La fecha inicial no puede ser mayor que la fecha final.");
            fechaInicioInput.val("");
        }
    });

    fechaFinalInput.on("change", function() {
        var fechaInicio = fechaInicioInput.datepicker("getDate");
        var fechaFinal = fechaFinalInput.datepicker("getDate");

        // Si la fecha de inicio está seleccionada
        if (fechaInicio && fechaFinal < fechaInicio) {
            alert("La fecha final no puede ser menor que la fecha inicial.");
            fechaFinalInput.val("");
        }
    });
</script>
