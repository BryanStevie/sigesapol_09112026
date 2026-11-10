@include('frontend.emergencia_enfermeria.evoluciones.function_evoluciones.puntos_grafico.control_de_puntos_evoluciones')
<script src='../../../funciones/function.js'></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>  --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> --}}
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
    //PARA EL FORM EDITAR
    $('body').on('click', '#plano_evolucion2', function() {
        var rect = document.getElementById('plano_evolucion2').getBoundingClientRect();
        var x = event.clientX - rect.left;
        var y = event.clientY - rect.top;
        $('#x').val(x)
        $('#y').val(y)
        var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado').val();
        var id_enfermeria = $('#id_enfermeria2').val();

        $("#tipo_proceso_editar").select2({
            width: 'resolve'
        });
        $("#tamanio_proceso_editar").select2({
            width: 'resolve'
        });
        lista_parametros('TIPO_PROCESO_PUNTO', 'tipo_proceso_editar', 'combo', 'TABLA_PARAMETROS', 0);
        lista_parametros('TAMANIO_PUNTOS', 'tamanio_proceso_editar', 'combo', 'TABLA_PARAMETROS', 0);
        actualizarPuntoEvolucion('control_div_puntos_evolucion_editar',
            'control_div_puntos_evolucion_leyenda_editar');
        pintar_puntos_by_evolucion(id_enfermeria, 'control_div_puntos_evolucion_editar',
            'control_div_puntos_evolucion_leyenda_editar', 'REGISTRO', id_tipo_atenciones_listado);
    });

    $(document).ready(function() {
        $('.numero').on('input', function() {
            let valor = $(this).val();
            // Remueve todo lo que no sean dígitos
            let nuevoValor = valor.replace(/[^0-9]/g, '');
            // Actualiza el valor del campo de entrada
            $(this).val(nuevoValor);
        });
    });
    $('#modalListarEvoluciones').on('hidden.bs.modal', function() {
        $('#alergia_si_listado').hide();
        $('#alergia_no_listado').hide();
    });
    $("#fechaIni").datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function(e) {
        const $p = [
            $('#fechaIni').val(),
            $('#fechaFin').val(),
            $('#id_turno').val(),
            $('#tipo_atenciones_listado').val(),
            $('#id_asegurado').val(),
        ];
        listar_evoluciones($p);
    });
    $("#fechaFin").datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function(e) {
        const $p = [
            $('#fechaIni').val(),
            $('#fechaFin').val(),
            $('#id_turno').val(),
            $('#tipo_atenciones_listado').val(),
            $('#id_asegurado').val(),
        ];
        listar_evoluciones($p);
    });

    $('#id_turno').change(function() {
        const $p = [
            $('#fechaIni').val(),
            $('#fechaFin').val(),
            $('#id_turno').val(),
            $('#tipo_atenciones_listado').val(),
            $('#id_asegurado').val(),
        ];
        listar_evoluciones($p);
    });
    $('#tipo_atenciones_listado').change(function() {
        const $p = [
            $('#fechaIni').val(),
            $('#fechaFin').val(),
            $('#id_turno').val(),
            $('#tipo_atenciones_listado').val(),
            $('#id_asegurado').val(),
        ];
        listar_evoluciones($p);
    });
    /*=============================================
    AJAX PARA crear ListarEvolucionesS
    =============================================*/
    /* $('#modalListarEvoluciones').on('shown.bs.modal', function () {
       const fecha_ingreso = JSON.parse($("#listarEvoluciones").attr('data-principal')).fecha_ingreso;
       $('#fecha_evolucion_bus_inicial_listado').val(fecha_ingreso);
       $('#fecha_evolucion_bus_inicial_listado').attr("disabled", true);
     }); */
    $('body').on('click', '#listarEvoluciones', function() {
        /*******************DATOS DE LA PAGINA PRINCIPAL AL TRAER LA DATA CON AJAX*******************/
        /* const fecha_ingreso = JSON.parse($(this).attr('data-principal')).fecha_ingreso;
        $('#fecha_evolucion_bus_inicial_listado').val(fecha_ingreso); */
        /* $('#fecha_evolucion_bus_inicial_listado').attr("disabled", true); */

        const id_asegurado = JSON.parse(this.getAttribute('data-principal')).id_asegurado;
        const nombre_tipo_atencion = JSON.parse(this.getAttribute('data-principal')).nombre_tipo_atencion;
        const id_enfermeria = JSON.parse(this.getAttribute('data-principal')).id_enfermeria; //id_enfermeria
        const id_prestacion = JSON.parse(this.getAttribute('data-principal')).id_prestacion; //id_enfermeria
        const id_ext_list = JSON.parse(this.getAttribute('data-principal')).id; //id_enfermeria
        var id_tipo_atenciones_listado = 3;

        const prioridad = JSON.parse(this.getAttribute('data-principal')).servicio_hospitalizado;
        var servicio = JSON.parse(this.getAttribute('data-principal')).nombre_consultorio;
        const area = JSON.parse(this.getAttribute('data-principal')).descripcion;
        const nombre_cama = JSON.parse(this.getAttribute('data-principal')).nombre_cama;
        const nombre_cuarto = JSON.parse(this.getAttribute('data-principal')).nombre_cuarto;

        $("#listaDatosEvoluciones").empty();
        $('#FormularioListarEvoluciones').trigger("reset");
        $('#tituloFormularioListarEvoluciones').html("Paciente de Cuidado Enfermero - PCE");
        $('#modalListarEvoluciones').modal('show');
        $('#id_asegurado').val(id_asegurado);
        // $('#id_atencion_evolucion_listado').val(id_atencion); //id de la atencion (id_emergencia,id_hospitalizacion,etc)
        $('#id_prestacion_listado').val(id_prestacion); //id de la pretacion de la atencion 
        $('#id_tipo_atenciones_listado').val(
            id_tipo_atenciones_listado); //tipo de atencion del listado que se tomara para el guardado
        $('#id_enfermeria_evolucion').val(id_enfermeria);
        $('#id_ext_list').val(id_ext_list);
        $('#id_enfermeria_ver').val(id_enfermeria);

        $('#nombre_tipo_atencion').val(nombre_tipo_atencion);
        $('#prioridad').val(prioridad);
        ///LISTADO DE REGISTROS  
        const $p = [
            $('#fechaIni').val(),
            $('#fechaFin').val(),
            $('#id_turno').val(),
            3,
            id_asegurado,
        ];
        listar_evoluciones($p);
        $('#prioridad').val(prioridad);
        $('#servicio').val(servicio);
        $('#area').val(area);
        $('#nombre_cama').val(nombre_cama);
        $('#nombre_cuarto').val(nombre_cuarto);
        /* $("#tipo_atenciones_listado").append("<option value=''>--SELECCIONAR--</option>");  */
    });

    function listar_evoluciones($p) {
        var var_url = '{{ url('lista_nota_ingreso_evoluciones') }}';
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
                id_enfermeria: $("#id_enfermeria_evolucion").val(),
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
                $('#num_documento_evolucion').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_evolucion').html(data.datos_paciente.paterno + " " + data.datos_paciente
                    .materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_evolucion').html(data.datos_paciente.tipo_sangre);
                $('#edad_evolucion').html(data.datos_paciente.edad_corta);
                $('#sexo_evolucion').html(data.datos_paciente.sexo);
                $('#hc_evolucion').html(data.datos_paciente.nro_historia);
                /* if (data.estado_alergia.flag_alergia) {
                    $('#alergia_si_listado').show();
                } else {
                    $('#alergia_no_listado').show();
                } */
                $('#alergia_evol').html(
                    data.tiene_alergias
                        ? `<i style="color: blue;">SI</i>`
                        : `<i style="color: red;">NO</i>`
                );
                /***TIPO DE ATENCIONES*****/
                /* $("#tipo_atenciones_listado").empty();
                $("#tipo_atenciones_listado").append("<option value='" + 0 + "'>" + '---Seleccionar---' + "</option>");
                $("#tipo_atenciones_listado").append("<option value='" + 3 + "'>" + 'HOSPITALIZACION' + "</option>"); */
                /* $.each(data.tipo_atenciones, function (key, value) {
                });
                $("#tipo_atenciones_listado").val($p[3]); */
                /************TURNOS**********/
                ;
                /* $("#id_turno").empty();
                $.each(data.turnos, function (key, value) { 
                  $("#id_turno").append("<option value='" + value.id + "'>" +value.descripcion + "</option>");
                });
                $("#id_turno").val($p[2]); */

                var cuerpo_turnos = '';

                $("#listaDatosEvoluciones").empty();
                if (data.evoluciones != null) {
                    var valor = '';
                    $.each(data.evoluciones, function(key, value) {

                        // var descripcion_turno='';
                        // if(value.descripcion_turno == 'M'){
                        //   descripcion_turno='MAÑANA';
                        // }else if(value.descripcion_turno == 'T'){
                        //   descripcion_turno='TARDE';
                        // }else if(value.descripcion_turno == 'N'){
                        //   descripcion_turno='NOCHE';
                        // }
                        valor = valor +
                            '<tr >' +
                            ' <td style="vertical-align: middle;"><b>FECHA:</b> ' + value
                            .fecha_evol + '<br><b>HORA:</b> ' + value.hora_evol +
                            '<br><b>TURNO:</b> ' + value.descripcion_turno + '</td>' +
                            ' <td style="vertical-align: middle;"> ' + '<br><b>Servicio:</b> ' + $(
                                '#prioridad').val() + '</td>' +
                            ' <td style="vertical-align: middle;"> <br>' + 'Cama' + ': ' + $(
                                '#nombre_cama').val() + '<br> Cuarto: ' + $('#nombre_cuarto')
                            .val() + '</td>' +
                            ' <td style="vertical-align: middle;">' + value.name + ' ' + value
                            .paterno + ' ' + value.materno + '</td>' +
                            ' <td style="vertical-align: middle;text-transform: uppercase;"><b style="font-size: 12px !important">SUBJETIVO:</b> ' +
                            value.descripcion_subjetivo +
                            '<br><b style="font-size: 12px !important">OBJETIVO:</b> ' + value
                            .descripcion_objetivo + '' +
                            ' </td>' +
                            ' <td style="vertical-align: middle; text-align: center;">' +
                            '   <a href="javascript:void(0)" data-flag_firma="' + value.flag_firma +
                            '" data-id_evoluciones="' + value.id_evoluciones +
                            '"  data-id_asegurado="' + value.id_asegurado +
                            '"id="verEvoluciones" name="verEvoluciones" ' +
                            '     data-toggle="tooltip" title="Visualizar" class="btn btn-success btn-xs verEvoluciones"> <i class="fa fa-eye"></i>' +
                            '   </a>';
                        if (value.flag_firma != 1) {
                            if (value.id_usuario_registro == $("#id_user").val()) {
                                valor += ' <a href="javascript:void(0)"  data-id_evoluciones="' +
                                    value
                                    .id_evoluciones +
                                    '" id="editarEvoluciones" data-toggle="tooltip" title="Editar"' +
                                    ' class="btn enf_btn-celeste btn-xs editarEvoluciones"> <i class="fa fa-pencil-square-o"></i>' +
                                    '</a>';
                            } else {}
                            valor += ' <a href="/pdf_nota_ingreso_evoluciones/' + value.id +
                                '" target="_blank"  data-toggle="tooltip" title="PDF"' +
                                '     class="btn enf_btn-verde-oscuro btn-xs"> <i class="fa fa-download"></i>' +
                                '   </a>';
                        }
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
                        if (!value.flag_firma) {
                            if (value.id_usuario_registro == $("#id_user").val()) {
                                valor += ' <a href="javascript:void(0)"   data-id_evoluciones="' +
                                    value
                                    .id_evoluciones +
                                    '" id="anularEvoluciones" data-toggle="tooltip" title="Anular"' +
                                    '     class="btn btn-danger btn-xs anularEvoluciones"><i class="fa fa-times"></i>' +
                                    '   </a>';
                            } else {}
                        }

                        valor += '</td>' +
                            '</tr>';
                    });
                    $("#listaDatosEvoluciones").html(valor);

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
    var fechaInicioInput = $("#fechaIni");
    var fechaFinalInput = $("#fechaFin");

    fechaInicioInput.on("change", function(e) {
        var fechaInicio = fechaInicioInput.datepicker("getDate");
        var fechaFinal = fechaFinalInput.datepicker("getDate");
        if (fechaFinal && fechaInicio > fechaFinal) {
            alert("La fecha inicial no puede ser mayor que la fecha final.");
            fechaInicioInput.val("");
        }
    });

    fechaFinalInput.on("change", function(e) {
        var fechaInicio = fechaInicioInput.datepicker("getDate");
        var fechaFinal = fechaFinalInput.datepicker("getDate");
        if (fechaInicio && fechaFinal < fechaInicio) {
            alert("La fecha final no puede ser menor que la fecha inicial.");
            fechaFinalInput.val("");
        }
    });
</script>
