<script>
    $(document).ready(function() {
        const nivel = $("#nivel_ipress").val();
        if (nivel != 2) {
            const index = 2;
            const tabla = document.getElementById("tbl_CPMS_ENFERMERIA_EVOL");
            for (let fila of tabla.rows) {
                if (fila.cells[index]) {
                    fila.cells[index].style.display = "none";
                }
            }
        }
    });

    $('#modalRegistrarEvoluciones').on('hidden.bs.modal', function() {
        $('#num_documento_evolucion_n').html("");
        $('#nom_paciente_evolucion_n').html("");
        $('#tipo_sangre_evolucion_n').html("");
        $('#edad_evolucion_n').html("");
        $('#sexo_evolucion_n').html("");
        $('#hc_evolucion_n').html("");
        $('#parentesgo_n').html("");
        $('#tipo_sangre_n').html("");
        $('#cel_acomp_evolucion_n').html("");
        $('#alergia_si').hide();
        $('#alergia_no').hide();
    });

    function isTimeInRangeEvol(time, startTime, endTime) {
        const timeToCheck = new Date(`1970-01-01T${time}:00`);
        const start = new Date(`1970-01-01T${startTime}:00`);
        const end = new Date(`1970-01-01T${endTime}:00`);

        // Manejar el caso en que el rango de tiempo cruza la medianoche
        if (end < start) {
            return (timeToCheck >= start && timeToCheck < new Date('1970-01-02T00:00:00')) ||
                (timeToCheck >= new Date('1970-01-01T00:00:00') && timeToCheck <= end);
        } else {
            return timeToCheck >= start && timeToCheck <= end;
        }
    }

    let diagnosticosEvolucionesCurr = null;

    $('body').on('click', '#crearEvoluciones', function() {
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
        $('#diagnosticos_nuevo').html('');
        cambiar_de_PestanaTab('tab0defaultEvoluciones');
        var id_enfermeria = $('#id_enfermeria_evolucion').val();
        var id_prestacion_listado = null;
        const tabEvoluciones = document.getElementById('tabEvolucionesCPMS');
        tabEvoluciones.setAttribute('data-toggle', 'none');
        tabEvoluciones.setAttribute('rel', 'tooltip');
        tabEvoluciones.setAttribute('title', 'Debe existir la atencion.');
        $('[rel="tooltip"]').tooltip();
        $.ajax({
            url: `/buscarIdPrestacionParaNotaDeEmergencia/${id_enfermeria}/${2}`,
            method: "GET",
            success: function(data) {
                id_prestacion_listado = data.length == 0 ? null : data[0].id;
                if (id_prestacion_listado) {
                    tabEvoluciones.setAttribute('data-toggle', 'tab');
                    tabEvoluciones.setAttribute('title', '');
                    $('[rel="tooltip"]').tooltip('destroy');
                }
                /* console.log({id_prestacion_listado}) */
                $('#id_prestacion_listado').val(id_prestacion_listado);
                $('#id_prestacion_registro').val(id_prestacion_listado);
                cargarDatosGlobalesEvoluciones(id_enfermeria, id_prestacion_listado, id_asegurado);
            },
            error: function(error) {
                console.error("Error al realizar la solicitud", error);
            }
        });
        $("#registrarEvoluciones").attr("id", "registrarEvoluciones");
        $("#registrarEvoluciones").attr("disabled", false);
        $('#FormularioEvoluciones').trigger("reset");
        $('#tituloFormularioEvoluciones').html("Paciente de Cuidado Enfermero - PCE");
        $('#modalRegistrarEvoluciones').modal('show');

        $("#descripcion_subjetivo").removeAttr('disabled');
        $("#descripcion_objetivo").removeAttr('disabled');
        $("#descripcion_intervenciones").removeAttr('disabled');
        $("#descripcion_evaluaciones").removeAttr('disabled');

        $("#add_DIAGNOSTICO_ENFERMERIA").show();
        $("#add_PLAN_CUIDADO_ENFERMERIA").show();
        $("#add_INTERVENCIONES_ENFERMERIA").show();
        $("#add_EVALUACIONES_ENFERMERIA").show();
        $("#add_CPMS_ENFERMERIA").show();

        /* var id_enfermeria = $('#id_enfermeria_evolucion').val();
        var id_prestacion_listado = $('#id_prestacion_listado').val(); */
        var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado').val();
        var nombre_tipo_atencion = $('#nombre_tipo_atencion').val();
        var prioridad = $('#prioridad').val();
        var id_asegurado = $('#id_asegurado').val();
        var id_ext_reg = $('#id_ext_list').val();

        $('#nombre_tipo_atencion_registro_n').val('EMERGENCIA');
        $('#prioridad_registro_n').val(prioridad);
        /* $('#id_prestacion_registro').val(id_prestacion_listado); */
        $('#id_tipo_atenciones_registro').val(2);
        $('#id_ext_evolucion').val(id_ext_reg);
        $("#fecha_registro_evoluciones_inicial").val('{{ \Carbon\Carbon::now()->format('d/m/Y') }}');

        $("#tbl_DIAGNOSTICO_ENFERMERIA tbody").empty();
        $("#tbl_INTERVENCIONES_ENFERMERIA tbody").empty();
        $("#tbl_PLAN_CUIDADO_ENFERMERIA tbody").empty();
        $("#tbl_EVALUACIONES_ENFERMERIA tbody").empty();
        $("#tbl_CPMS_ENFERMERIA_EVOL tbody").empty();

        $('#id_asegurado2').val(id_asegurado);

        $("#tipo_proceso_nuevo").select2({
            width: 'resolve'
        });
        $("#tamanio_proceso_nuevo").select2({
            width: 'resolve'
        });

        lista_parametros('TIPO_PROCESO_PUNTO', 'tipo_proceso_nuevo', 'combo', 'TABLA_PARAMETROS', 0);
        lista_parametros('TAMANIO_PUNTOS', 'tamanio_proceso_nuevo', 'combo', 'TABLA_PARAMETROS', 0);
        pintar_puntos_by_evolucion(id_enfermeria, "control_div_puntos_evolucion",
            "control_div_puntos_evolucion_leyenda", 'REGISTRO', id_tipo_atenciones_listado);
        eliminar_puntosNuevoEdit('control_div_puntos_evolucion', 'control_div_puntos_evolucion_leyenda');
        plano_evolucion1();
        /* cargarDatosGlobalesEvoluciones(id_enfermeria,id_prestacion_listado,id_asegurado) */
    });


    function plano_evolucion1() {
        document.getElementById('plano_evolucion').addEventListener('click', function(event) {
            var rect = document.getElementById('plano_evolucion').getBoundingClientRect();
            var x = event.clientX - rect.left;
            var y = event.clientY - rect.top;
            $('#x').val(x)
            $('#y').val(y)
            var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado').val();
            var id_enfermeria = $('#id_enfermeria_evolucion').val();

            $("#tipo_proceso_nuevo").select2({
                width: 'resolve'
            });
            $("#tamanio_proceso_nuevo").select2({
                width: 'resolve'
            });
            // $('#id_hospitalizacion').val(id_hospitalizacion_registro_monitoreo);
            lista_parametros('TIPO_PROCESO_PUNTO', 'tipo_proceso_nuevo', 'combo', 'TABLA_PARAMETROS', 0);
            lista_parametros('TAMANIO_PUNTOS', 'tamanio_proceso_nuevo', 'combo', 'TABLA_PARAMETROS', 0);
            guardarPuntoEvolucion('control_div_puntos_evolucion', 'control_div_puntos_evolucion_leyenda');
            pintar_puntos_by_evolucion(id_enfermeria, "control_div_puntos_evolucion",
                "control_div_puntos_evolucion_leyenda", 'REGISTRO', id_tipo_atenciones_listado);
        });
    }

    $('#add_DIAGNOSTICO_ENFERMERIA').on('click', function() {

        lista_table_tr_campo_detalle(
            'tbl_DIAGNOSTICO_ENFERMERIA',
            'descripcion_DIAGNOSTICO_ENFERMERIA',
            /* 'cantidad_detalle_DIAGNOSTICO_ENFERMERIA' */null,
            'id_relacionado_DIAGNOSTICO_ENFERMERIA',
            'boton_eliminar_item_DIAGNOSTICO_ENFERMERIA',
            'NANDA',
            /* 'combo' */null,
            '1||2||3||4||5',
            '1||2||3||4||5',
            'SI',
            'NO'
        );
        delete_table_tr_campo('tbl_DIAGNOSTICO_ENFERMERIA', 'boton_eliminar_item_DIAGNOSTICO_ENFERMERIA', '');

    });

    $('#add_INTERVENCIONES_ENFERMERIA').on('click', function() {

        lista_table_tr_campo_detalle(
            'tbl_INTERVENCIONES_ENFERMERIA',
            'descripcion_INTERVENCIONES_ENFERMERIA',
            /* 'cantidad_detalle_INTERVENCIONES_ENFERMERIA' */null,
            'id_relacionado_INTERVENCIONES_ENFERMERIA',
            'boton_eliminar_item_INTERVENCIONES_ENFERMERIA',
            'NIC',
            /* 'combo' */null,
            '1||2||3||4||5',
            '1||2||3||4||5',
            'SI',
            'NO'
        );
        delete_table_tr_campo('tbl_INTERVENCIONES_ENFERMERIA', 'boton_eliminar_item_INTERVENCIONES_ENFERMERIA',
            '');

    });

    $('#add_PLAN_CUIDADO_ENFERMERIA').on('click', function() {

        lista_table_tr_campo_detalle(
            'tbl_PLAN_CUIDADO_ENFERMERIA',
            'descripcion_PLAN_CUIDADO_ENFERMERIA',
            'cantidad_detalle_PLAN_CUIDADO_ENFERMERIA',
            'id_relacionado_PLAN_CUIDADO_ENFERMERIA',
            'boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA',
            'NOC',
            'combo',
            '1||2||3||4||5',
            '1||2||3||4||5',
            'SI',
            'NO'
        );
        delete_table_tr_campo('tbl_PLAN_CUIDADO_ENFERMERIA', 'boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA',
            'copiar_tabla_datos');

    });

    $('#add_CPMS_ENFERMERIA_EVOL').on('click', function() {

        lista_table_tr_campo_detalle(
            'tbl_CPMS_ENFERMERIA_EVOL',
            'descripcion_CPMS_ENFERMERIA_NUEVO_EVOL',
            'descripcion_detalle_CPMS_ENFERMERIA',
            'id_relacionado_CPMS_ENFERMERIA',
            'boton_eliminar_item_CPMS_ENFERMERIA',
            'null',
            'sin_elemento_adicional',
            'null',
            'null',
            'SI',
            'SI',
            'procedimiento_cantidad',
            'procedimiento_tiempo',
            diagnosticosEvolucionesCurr,
            $("#nivel_ipress").val()
        );
        var id_prestacion_listado = $('#id_prestacion_listado').val();
    });
    delete_table_tr_campo('tbl_CPMS_ENFERMERIA_EVOL', 'boton_eliminar_item_CPMS_ENFERMERIA', '');
    $('#add_EVALUACIONES_ENFERMERIA').on('click', function() {
        copiar_datos_otra_table();
        delete_table_tr_campo('tbl_DIAGNOSTICO_ENFERMERIA', 'boton_eliminar_item_DIAGNOSTICO_ENFERMERIA', '');
        delete_table_tr_campo('tbl_INTERVENCIONES_ENFERMERIA', 'boton_eliminar_item_INTERVENCIONES_ENFERMERIA',
            '');
        delete_table_tr_campo('tbl_PLAN_CUIDADO_ENFERMERIA', 'boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA',
            'copiar_tabla_datos');
        delete_table_tr_campo('tbl_EVALUACIONES_ENFERMERIA', 'boton_eliminar_item_EVALUACIONES_ENFERMERIA', '');
        delete_table_tr_campo('tbl_CPMS_ENFERMERIA_EVOL', 'boton_eliminar_item_CPMS_ENFERMERIA', '');
    });

    function capturarImagenNuevo(celdaID) {
        const celda = document.getElementById(celdaID);
        swal({
            buttons: true,
            title: "¿Desea Realizar el Registro?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                // Capturar la imagen solo cuando el usuario confirma
                html2canvas(celda).then(function(canvas) {
                    const imgData = canvas.toDataURL('image/png');
                    $('#img_puntos_base').val(imgData.replace(/^data:image\/[a-z]+;base64,/, ""));
                    const imagenCapturada = document.getElementById('imagenCapturada');
                    imagenCapturada.src = imgData;
                    imagenCapturada.style.display = 'block';
                    celda.innerHTML = '';
                    celda.appendChild(imagenCapturada);
                    var token = $('#_token').val();
                    var route = '{{ url('registrar_nota_ingreso_evoluciones') }}';
                    var formData = new FormData($("#FormularioEvoluciones")[0]);
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
                                if (data == 3) {
                                    swal({
                                        buttons: false,
                                        title: "REGISTRO EXISTENTE.",
                                        text: "Ya existe una evolucion registrada en el turno seleccionado.",
                                        icon: "error",
                                        timer: 2500,
                                    });
                                } else if (data != 0) {
                                    // Limpiar formulario y cerrar modal
                                    $('#FormularioEvoluciones').trigger("reset");
                                    $('#modalRegistrarEvoluciones').modal('hide');
                                    swal({
                                        buttons: false,
                                        title: "Registro Exitoso",
                                        text: "Datos registrados Exitosamente.",
                                        icon: "success",
                                        timer: 1500,
                                    });
                                    // Actualizar listado de evoluciones
                                    const $p = [
                                        $('#fechaIni').val(),
                                        $('#fechaFin').val(),
                                        $('#id_turno').val(),
                                        2,
                                        $('#id_asegurado').val(),
                                    ];
                                    listar_evoluciones($p);
                                    //VOLVER A SU ESTADO INICIAL
                                    $('#img_puntos_base').val('');
                                    $('#celda0').html();
                                    $('#imagen_captura').html(`
                                   <img id="imagenCapturada" style="display: none;" />
                                `);
                                    $('#celda0').html(`
                                    <div class="form-group">
                                        <div class="grid-block" style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                            <canvas id="plano_evolucion" name="plano" width="512" height="512"></canvas>
                                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="x" id="x">
                                            <input type="hidden" name="y" id="y">
                                            <div id="control_div_puntos_evolucion" name="control_div_puntos_evolucion">
                                            </div>
                                        </div>
                                    </div>
                                `);
                                    $('#control_div_puntos_evolucion_leyenda').empty();
                                    //FIN VOLVER A SU ESTADO INICIAL
                                    pintar_puntos_by_evolucion($('#id_enfermeria_evolucion')
                                        .val(), "control_div_puntos_evolucion",
                                        "control_div_puntos_evolucion_leyenda",
                                        'REGISTRO', $('#id_tipo_atenciones_listado')
                                        .val());
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
                        },
                        error: function(xhr, status, error) {
                            swal({
                                buttons: false,
                                title: "Error de Servicio de Registros",
                                text: "Hubo un problema al intentar registrar los datos. Por favor intente nuevamente.",
                                icon: "error",
                                timer: 2500,
                            });
                        }
                    });
                }).catch(function(error) {
                    console.error('Error al capturar la imagen:', error);
                });
            } else {
                console.log("El usuario ha cancelado la acción.");
            }
        });
    }
    // async function capturarImagenNuevo(celdaID) {
    //   const celda = document.getElementById(celdaID);
    //   html2canvas(celda).then(function (canvas) {
    //     const imgData = canvas.toDataURL('image/png');
    //     const imagenCapturada = document.getElementById('imagenCapturada');
    //     $('#img_puntos_base').val(imgData.replace(/^data:image\/[a-z]+;base64,/, ""));  
    //     imagenCapturada.src = imgData;
    //     imagenCapturada.style.display = 'block';
    //     celda.innerHTML = '';
    //     celda.appendChild(imagenCapturada); 
    //   });
    // }

    function cambiar_de_PestanaTab(descripcion_de_tab) {
        var tab_seleccionado = document.querySelector('a[href="#' + descripcion_de_tab + '"]');
        tab_seleccionado.click();
    }

    $(document).ready(function() {
        $('#registrarEvoluciones').click(function(e) {
            e.preventDefault();
            //setTimeout(function() {
            // capturarContenidoCelda('celda1'); 
            //}, 1500); 
            var descripcion_turno = $('#turnos_nuevo').val();
            var descripcion_subjetivo = $('#descripcion_subjetivo').val();
            var descripcion_objetivo = $('#descripcion_objetivo').val();
            var descripcion_intervenciones = $('#descripcion_intervenciones').val();
            var descripcion_evaluaciones = $('#descripcion_evaluaciones').val();
            let tbl_DIAGNOSTICO_ENFERMERIA = $('#tbl_DIAGNOSTICO_ENFERMERIA tbody tr').length;
            let tbl_INTERVENCIONES_ENFERMERIA = $('#tbl_INTERVENCIONES_ENFERMERIA tbody tr').length;
            let tbl_PLAN_CUIDADO_ENFERMERIA = $('#tbl_PLAN_CUIDADO_ENFERMERIA tbody tr').length;
            let tbl_EVALUACIONES_ENFERMERIA = $('#tbl_EVALUACIONES_ENFERMERIA tbody tr').length;
            let tbl_CPMS_ENFERMERIA = $('#tbl_CPMS_ENFERMERIA_EVOL tbody tr').length;
            
            let fechaReg = $("#fecha_registro_evolucion").val();
            let horaReg = $("#hora_registro_evolucion").val();
            let fechaIng = $("#fecha_hora_ing_reg").val();
            let fechaEg = $("#fecha_hora_eg_reg").val() != "" ? $("#fecha_hora_eg_reg").val() : null;

            if (!horaReg.match(/^\d{2}:\d{2}:\d{2}$/)) {
                horaReg += ':00';
            }

            let fechaHoraReg = `${fechaReg} ${horaReg}`;
            let fechaHoraRegDate = new Date(fechaHoraReg.replace(' ', 'T'));
            let fechaIngDate = new Date(fechaIng.replace(' ', 'T'));
            let fechaEgDate = fechaEg ? new Date(fechaEg.replace(' ', 'T')) : null;

            if (fechaHoraRegDate < fechaIngDate) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: `Proceso cancelado. No se puede registrar con una fecha y hora anterior al ingreso del paciente: ${fechaIng}.`,
                    icon: "error",
                    timer: 3500,
                });
                return;
            }

            if (fechaEgDate && (fechaHoraRegDate > fechaEgDate)) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: `Proceso cancelado. No se puede registrar con una fecha y hora posterior al egreso del paciente: ${fechaEg}.`,
                    icon: "error",
                    timer: 3500,
                });
                return;
            }

            var color = $('#tipo_proceso').val();
            var tipo_registro = $('#tipo_rgs').val();
            var id_prestacion = parseInt($('#id_prestacion_registro').val());
            console.log('ID PRESTACION SETEADO => ' + id_prestacion)
            //var img_puntos_base = $('#img_puntos_base').val();
            if (id_prestacion) {
                if (tbl_CPMS_ENFERMERIA <= 0) {
                    swal({
                        buttons: false,
                        title: "Verifique los campos ingresados",
                        text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de CPMS.",
                        icon: "error",
                        timer: 2500,
                    });
                    cambiar_de_PestanaTab("tab2defaultEvoluciones");
                    setTimeout(function() {
                        $('#add_CPMS_ENFERMERIA').focus();
                    }, 1000);
                    return;
                }
                let isValidName = false;
                $('input[name="id_relacionado_CPMS_ENFERMERIA[]"]').each(function(index) {
                    let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número
                    // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                    //let descripcionInput = $('input[name="descripcion_CPMS_ENFERMERIA[]"]').eq(index);
                    // Verifica si el valor es un número válido y mayor a 0
                    if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                        isValidName = true; // Marca como inválido
                        //descripcionInput.addClass('is-invalid'); // Agrega clase de error al input de descripción
                        $('#descripcion_CPMS_ENFERMERIA_NUEVO' + index).css('border',
                            '1px solid red');
                    } else {
                        // descripcionInput.removeClass('is-invalid'); // Remueve clase de error si es válido
                        $('#descripcion_CPMS_ENFERMERIA_NUEVO' + index).css('border', '');
                    }
                });
                if (isValidName) {
                    swal({
                        buttons: false,
                        title: "Verifique los campos de procedimiento",
                        text: "Proceso cancelado. Ingrese un valor válido.",
                        icon: "error",
                        timer: 2500,
                    });
                    cambiar_de_PestanaTab("tab2defaultEvoluciones");
                    return false; // Previene el envío del formulario
                }
                let isValid = false;
                $('input[name="procedimiento_cantidad[]"]').each(function(index) {
                    let cantidad = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(cantidad); // Convierte a número
                    // Verifica si el valor es un número válido y mayor a 1
                    if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                        isValid = true;
                        $('#procedimiento_cantidad' + index).css('border', '1px solid red');
                        // Muestra un mensaje de error
                    } else {
                        $('#procedimiento_cantidad' + index).css('border', '');
                    }
                });
                if (isValid) {
                    swal({
                        buttons: false,
                        title: "Verifique los campos de cantidad",
                        text: "Proceso cancelado. Ingrese un valor en la cantidad.",
                        icon: "error",
                        timer: 2500,
                    });
                    cambiar_de_PestanaTab("tab2defaultEvoluciones");
                    return false;
                }
            }

            if (descripcion_turno == null || descripcion_turno == '' || descripcion_turno.length < 1) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: "Proceso cancelado. Seleccione un TURNO VÁLIDO para el registro correspondiente.",
                    icon: "error",
                    timer: 2500,
                });
                cambiar_de_PestanaTab("tab0defaultEvoluciones");
                $('#turnos_nuevo').next('.select2-container').addClass('borde-alerta');
                setTimeout(function() {
                    $('#turnos_nuevo').focus();
                }, 1000);
                return;
            } else {
                $('#turnos_nuevo').next('.select2-container').removeClass('borde-alerta');
            }

            // if(descripcion_subjetivo == null || descripcion_subjetivo.length <=5){ 
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo SUBJETIVO.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab0defaultEvoluciones");
            //   $('#descripcion_subjetivo').addClass('borde-alerta');
            //   setTimeout(function() {
            //     $('#descripcion_subjetivo').focus();  
            //   }, 1000);
            //   return;
            // }else{
            //   $('#descripcion_subjetivo').removeClass('borde-alerta');
            // }

            // if(descripcion_objetivo == null || descripcion_objetivo.length <=5){ 
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo OBJETIVO.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab0defaultEvoluciones");
            //   $('#descripcion_objetivo').addClass('borde-alerta');
            //   setTimeout(function() {
            //     $('#descripcion_objetivo').focus();  
            //   }, 1000);
            //   return;
            // }else{
            //   $('#descripcion_objetivo').removeClass('borde-alerta');
            // }

            // if(tbl_DIAGNOSTICO_ENFERMERIA <= 0){
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de DIAGNÓSTICOS.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones");
            //   setTimeout(function() {
            //     $('#add_DIAGNOSTICO_ENFERMERIA').focus();  
            //   }, 1000);
            //   return;
            // }
            if (tbl_DIAGNOSTICO_ENFERMERIA > 0) {
                let isValidNameDiagnostico = false;
                $('input[name="descripcion_DIAGNOSTICO_ENFERMERIA[]"]').each(function(index) {
                    let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número

                    // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                    let descripcionInput = $(
                        'input[name="descripcion_DIAGNOSTICO_ENFERMERIA[]"]').eq(index);
                    // Verifica si el valor es un número válido y mayor a 0
                    if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                        isValidNameDiagnostico = true; // Marca como inválido
                        descripcionInput.addClass('is-invalid');
                    } else {
                        descripcionInput.removeClass('is-invalid');
                    }
                });

                if (isValidNameDiagnostico) {
                    swal({
                        buttons: false,
                        title: "Verifique los campos de diagnostico",
                        text: "Proceso cancelado. Ingrese un valor válido.",
                        icon: "error",
                        timer: 2500,
                    });
                    cambiar_de_PestanaTab("tab1defaultEvoluciones");
                    return false; // Previene el envío del formulario
                }
            }

            // if(tbl_INTERVENCIONES_ENFERMERIA <= 0){
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de EJECUCIÓN.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones"); 
            //   setTimeout(function() {
            //     $('#add_INTERVENCIONES_ENFERMERIA').focus();  
            //   }, 1000);
            //   return;
            // }
            if (tbl_INTERVENCIONES_ENFERMERIA > 0) {
                let isValidNameIntervenciones = false;
                $('input[name="descripcion_INTERVENCIONES_ENFERMERIA[]"]').each(function(index) {
                    let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número

                    // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                    let descripcionInput = $(
                        'input[name="descripcion_INTERVENCIONES_ENFERMERIA[]"]').eq(index);
                    // Verifica si el valor es un número válido y mayor a 0
                    if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                        isValidNameIntervenciones = true; // Marca como inválido
                        descripcionInput.addClass('is-invalid');
                    } else {
                        descripcionInput.removeClass('is-invalid');
                    }
                });

                if (isValidNameIntervenciones) {
                    swal({
                        buttons: false,
                        title: "Verifique los campos de NIC",
                        text: "Proceso cancelado. Ingrese un valor válido.",
                        icon: "error",
                        timer: 2500,
                    });
                    cambiar_de_PestanaTab("tab1defaultEvoluciones");
                    return false; // Previene el envío del formulario
                }
            }

            // if(tbl_PLAN_CUIDADO_ENFERMERIA <= 0){
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de PLANEACIÓN.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones");
            //   setTimeout(function() {
            //     $('#add_PLAN_CUIDADO_ENFERMERIA').focus();  
            //   }, 1000);
            //   return;
            // }
            if (tbl_PLAN_CUIDADO_ENFERMERIA > 0) {
                let isValidNamePlan = false;
                $('input[name="descripcion_PLAN_CUIDADO_ENFERMERIA[]"]').each(function(index) {
                    let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número

                    // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                    let descripcionInput = $(
                        'input[name="descripcion_PLAN_CUIDADO_ENFERMERIA[]"]').eq(index);
                    // Verifica si el valor es un número válido y mayor a 0
                    if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                        isValidNamePlan = true; // Marca como inválido
                        descripcionInput.addClass('is-invalid');
                    } else {
                        descripcionInput.removeClass('is-invalid');
                    }
                });

                if (isValidNamePlan) {
                    swal({
                        buttons: false,
                        title: "Verifique los campos de NOC",
                        text: "Proceso cancelado. Ingrese un valor válido.",
                        icon: "error",
                        timer: 2500,
                    });
                    cambiar_de_PestanaTab("tab1defaultEvoluciones");
                    return false; // Previene el envío del formulario
                }
            }

            // if(tbl_EVALUACIONES_ENFERMERIA <= 0){
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de EVALUACIÓN.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones");
            //   setTimeout(function() {
            //     $('#add_EVALUACIONES_ENFERMERIA').focus();  
            //   }, 1000);
            //   return;
            // }

            // if(descripcion_intervenciones == null || descripcion_intervenciones.length <=5){ 
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo DETALLE DE INTERVENCIONES.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones");
            //   $('#descripcion_intervenciones').addClass('borde-alerta');
            //   setTimeout(function() {
            //     $('#descripcion_intervenciones').focus();  
            //   }, 1000);
            //   return;
            // }else{
            //   $('#descripcion_intervenciones').removeClass('borde-alerta');
            // }

            // if(descripcion_evaluaciones == null || descripcion_evaluaciones.length <=5){ 
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo DETALLE DE EVALUACIONES.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones");
            //   $('#descripcion_evaluaciones').addClass('borde-alerta');
            //   setTimeout(function() {
            //     $('#descripcion_evaluaciones').focus();  
            //   }, 1000);
            //   return;
            // }else{
            //   $('#descripcion_evaluaciones').removeClass('borde-alerta');
            // }

            var token = $('#_token').val();
            var route = '{{ url('registrar_nota_ingreso_evoluciones') }}';
            capturarImagenNuevo('celda0');
            // swal({
            //       buttons: true,
            //       title: "¿Desea Realizar el Registro?",
            //       text: "Recuerde verificar que todos los datos sean correctos.",
            //       icon: "warning",
            //       dangerMode: true,
            //     }).then((willDelete) => {
            //       if (willDelete) { 
            //         var formData = new FormData($("#FormularioEvoluciones") [0]);
            //         $.ajax({
            //           data: formData,
            //           url:route,
            //           contentType: false,
            //           processData: false,
            //           headers: {'X-CSRF-TOKEN':token},
            //           type:'POST',
            //           enctype: 'multipart/form-data',
            //           dataType:'JSON',
            //           success: function(data){
            //             if(data!=null){
            //                 if(data!=0){

            //                     $('#FormularioEvoluciones').trigger("reset");
            //                     $('#modalRegistrarEvoluciones').modal('hide');

            //                     swal({
            //                       buttons: false,
            //                       title: "Registro Exitoso",
            //                       text: "Datos registrados Exitosamente.",
            //                       icon: "success",
            //                       timer: 1500,
            //                     });

            //                     //window.open(('/pdf_nota_ingreso_evoluciones/'+data), '_blank');
            //                     ///LISTADO DE REGISTROS  
            //                     const $p = [
            //                       $('#fecha_registro_hoja_grafica_inicial').val(), 
            //                       $('#fecha_registro_hoja_grafica_inicial').val(),
            //                       $('#descripcion_turno_bus_listado').val(),
            //                       $('#id_atencion_evolucion_registro').val(),
            //                       $('#tipo_atenciones').val(),
            //                       $('#id_asegurado2').val(),
            //                     ];
            //                     listar_evoluciones($p);
            //                     //Actualizar Datos de la Tabla
            //                     //var oTable = $('#listaCategorias').dataTable();
            //                     //oTable.fnDraw(false);
            //                 }else{

            //                   swal({
            //                     buttons: false,
            //                     title: "Error de Servicio de Registros",
            //                     text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
            //                     icon: "error",
            //                     timer: 2500,
            //                   });

            //                 }
            //           }else{

            //             swal({
            //                 buttons: false,
            //                 title: "Error de Servicio de Registros",
            //                 text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
            //                 icon: "error",
            //                 timer: 2500,
            //               });

            //           }
            //         }
            //       });

            //     } else {

            //         swal({
            //           buttons: false,
            //           title: "Operación Cancelada",
            //           text: "Proceso de Registro Cancelado.",
            //           icon: "error",
            //           timer: 1500,
            //         });

            //       }
            //     });


        });
    });

    //DIAGNOSTICOS PARA EL COMBO
    function diagnosticos_pacientes_prestacion2(diagnosticos) {
        console.log(diagnosticos)
        /* $.ajax({
          url: '{{ url('diagnosticos_pacientes_prestacion_combo/') }}/' + id_prestacion,
          type: 'GET',
          dataType: 'JSON',
          success: function(data) {  
            var cuerpo_DIAGNOSTICOS_PRESTACION2 = '' ;
                //$("#cuerpo_DIAGNOSTICO_ENFERMERIA").empty();
                $.each(data, function (key, value) { 
                      cuerpo_DIAGNOSTICOS_PRESTACION2 += '<option value="' + value.id_diagnostico + '">' + value.nombre + '</option>';
                }); 
                $(".cpms_listado").html(cuerpo_DIAGNOSTICOS_PRESTACION2);
          }
        })  */
    }

    function cargarDatosGlobalesEvoluciones(id_enfermeria, id_prestacion, id_asegurado) {
        var var_url = '{{ url('datos_globales_evoluciones') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_asegurado: id_asegurado,
                id_prestacion: id_prestacion,
                id_enfermeria: id_enfermeria,
                id_tipo_atencion: $("#id_tipo_atenciones_registro").val(),
                id_ext: $("#id_ext_evolucion").val()
            },
            success: function(data) {
                diagnosticosEvolucionesCurr = data.diagnosticos.diagnosticos_atencion;
                console.log(diagnosticosEvolucionesCurr)
                $("#fecha_hora_ing_reg").val(data.fechas_ingreso_egreso.fecha_hora_ingreso);
                $("#fecha_hora_eg_reg").val(data.fechas_ingreso_egreso.fecha_hora_egreso);
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
                /***********DATOS DEL PACIENTE***********/
                $('#num_documento_evolucion_n').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_evolucion_n').html(data.datos_paciente.paterno + " " + data.datos_paciente
                    .materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_evolucion_n').html(data.datos_paciente.tipo_sangre);
                $('#edad_evolucion_n').html(data.datos_paciente.edad_corta);
                $('#sexo_evolucion_n').html(data.datos_paciente.sexo);
                $('#hc_evolucion_n').html(data.datos_paciente.nro_historia);
                $('#cel_acomp_evolucion_n').html(data.estado_acomp.celular_acomp ? data.estado_acomp
                    .celular_acomp : 'Sin registrar');
                $('#parentesgo_n').html(data.datos_paciente.parentesgo);
                $('#tipo_sangre_n').html(data.datos_paciente.tipo_sangre ?? "Sin registrar");
                if (data.estado_alergia.flag_alergia) {
                    $('#alergia_si').show();
                } else {
                    $('#alergia_no').show();
                }
                /*****************TURNOS**********/
                ;
                $("#turnos_nuevo").empty(); // 
                $.each(data.turnos, function(key, value) {
                    $("#turnos_nuevo").append("<option value='" + value.id + "'>" + value
                        .descripcion + "</option>");
                });

                const inputFechaConHora = document.getElementById('hora_registro_evolucion');
                const hora = inputFechaConHora.value;
                $("#turnos_nuevo").val(isTimeInRangeEvol(hora, "07:30", "13:29") ? "2" : isTimeInRangeEvol(
                        hora, "13:30", "19:29") ? "3" : isTimeInRangeEvol(hora, "19:30", "01:29") ?
                    "4" : isTimeInRangeEvol(hora, "01:30", "07:29") ? "1" : "").change();
                inputFechaConHora.addEventListener('change', function(e) {
                    const hora = e.target.value;
                    $("#turnos_nuevo").val(isTimeInRangeEvol(hora, "07:30", "13:29") ? "2" :
                        isTimeInRangeEvol(hora, "13:30", "19:29") ? "3" : isTimeInRangeEvol(
                            hora, "19:30", "01:29") ? "4" : isTimeInRangeEvol(hora, "01:30",
                            "07:29") ? "1" : "").change();
                });

                $("#turnos_nuevo").on('change', function(e) {
                    const hora = $("#hora_registro_evolucion").val();
                    if (hora) {
                        if ($("#turnos_nuevo").val() == '2') {
                            if (!isTimeInRangeEvol(hora, "07:30", "13:29")) {
                                Swal.fire({
                                    icon: "error",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                                e.preventDefault();
                                $("#turnos_nuevo").val(isTimeInRangeEvol(hora, "07:30", "13:29") ?
                                        "2" : isTimeInRangeEvol(hora, "13:30", "19:29") ? "3" :
                                        isTimeInRangeEvol(hora, "19:30", "01:29") ? "4" :
                                        isTimeInRangeEvol(hora, "01:30", "07:29") ? "1" : "")
                                    .change();
                            }
                        }
                        if ($("#turnos_nuevo").val() == '3') {
                            if (!isTimeInRangeEvol(hora, "13:30", "19:29")) {
                                Swal.fire({
                                    icon: "error",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                                e.preventDefault();
                                $("#turnos_nuevo").val(isTimeInRangeEvol(hora, "07:30", "13:29") ?
                                        "2" : isTimeInRangeEvol(hora, "13:30", "19:29") ? "3" :
                                        isTimeInRangeEvol(hora, "19:30", "01:29") ? "4" :
                                        isTimeInRangeEvol(hora, "01:30", "07:29") ? "1" : "")
                                    .change();
                            }
                        }
                        if ($("#turnos_nuevo").val() == '4') {
                            if (!isTimeInRangeEvol(hora, "19:30", "01:29")) {
                                Swal.fire({
                                    icon: "error",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                                e.preventDefault();
                                $("#turnos_nuevo").val(isTimeInRangeEvol(hora, "07:30", "13:29") ?
                                        "2" : isTimeInRangeEvol(hora, "13:30", "19:29") ? "3" :
                                        isTimeInRangeEvol(hora, "19:30", "01:29") ? "4" :
                                        isTimeInRangeEvol(hora, "01:30", "07:29") ? "1" : "")
                                    .change();
                            }
                        }
                        if ($("#turnos_nuevo").val() == '1') {
                            if (!isTimeInRangeEvol(hora, "01:30", "07:29")) {
                                Swal.fire({
                                    icon: "error",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Debe seleccionar el turno correspondiente a la HORA.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                                e.preventDefault();
                                $("#turnos_nuevo").val(isTimeInRangeEvol(hora, "07:30", "13:29") ?
                                        "2" : isTimeInRangeEvol(hora, "13:30", "19:29") ? "3" :
                                        isTimeInRangeEvol(hora, "19:30", "01:29") ? "4" :
                                        isTimeInRangeEvol(hora, "01:30", "07:29") ? "1" : "")
                                    .change();
                            }
                        }
                    }
                });

                /*****************LISTA DE INVASIVOS*********************/
                var item_valor_combo = '';
                const lista_Array = [{
                        value: 0,
                        text: 'Seleccionar'
                    },
                    {
                        value: 1,
                        text: 'Colocación'
                    },
                    {
                        value: 2,
                        text: 'Cambio'
                    },
                    {
                        value: 3,
                        text: 'Retiro'
                    }
                ];
                for (var i = 0; i < lista_Array.length; i++) {
                    item_valor_combo +=
                        `<option value="${lista_Array[i].value}">${lista_Array[i].text}</option>`;
                }
                var cuerpo_invasivos = '';
                $("#lista_invasivos").empty();
                $.each(data.invasivos, function(key, value) {
                    cuerpo_invasivos = cuerpo_invasivos +
                        '<tr>' +
                        '<td>' + value.id +
                        '<input type="hidden" id="" name="invasivos[]" value="' + value.id +
                        '"></td>' +
                        '<td>' + value.descripcion_corta +
                        '<input type="hidden" id="descripcion_corta' + key +
                        '" name="descripcion_corta[' + key + ']" value="' + value
                        .descripcion_corta + '"></td>' +
                        '<td><input class="form-control fecha" type="date" id="invasivo_fecha' +
                        key + '" name="invasivo_fecha[' + key + ']"></td>' +
                        '<td><input class="form-control" type="text" id="invasivo_lugar' + key +
                        '" name="invasivo_lugar[' + key + ']"></td>' +
                        '<td><select class="form-control" id="invasivo_accion' + key +
                        '" name="invasivo_accion[' + key +
                        ']"  style="height: 28px !important;cursor:pointer; font-size:13px;padding: 1px 12px !important;font-weight: 800;">' +
                        item_valor_combo + '</select></td>' +
                        '</tr>';
                });
                $('#lista_invasivos').append(cuerpo_invasivos);
                /* validacionFechasInvasivosRegitro(data.fecha_registro_admision.fecha); */

                /**************HISTORIAL INVASIVOS*******************/
                var cuerpo_INVASIVOS_ATENCION = '';
                $("#historial_invasivos").empty();
                if (data.invasivos_por_atencion.items && data.invasivos_por_atencion.items.length > 0) {
                    $.each(data.invasivos_por_atencion.items, function(key, value) {
                        cuerpo_INVASIVOS_ATENCION = cuerpo_INVASIVOS_ATENCION +
                            '<tr>' +
                            '<td>' + (key + 1) + '</td>' +
                            '<td>' + value.fecha + '</td>' +
                            '<td>' + value.tipo + '</td>' +
                            '<td>' + (value.lugar || "") + '</td>' +
                            '<td>' + value.accion + '</td>' +
                            '</tr>';
                    });
                }
                $('#historial_invasivos').append(cuerpo_INVASIVOS_ATENCION);

                // Manejo del botón "Cargar más"
                if (data.invasivos_por_atencion.nextPageUrl) {
                    $('#load-more-container-registrar').html(
                        '<button id="load-more-button-registrar" class="btn enf_btn-celeste" data-next-page-url-registrar="' +
                        (data.invasivos_por_atencion.current_page + 1) + '">Cargar más </button>');
                } else {
                    $('#load-more-container-registrar').html('');
                }

                /***********************DIAGNOSTICOS PACIENTES PRESTACION************************/
                var cuerpo_DIAGNOSTICOS_PRESTACION = '';
                /***************DATOS PARA LA TABLA***********/
                $("#diagnosticos_nuevo").empty();
                $.each(data.diagnosticos.diagnosticos_atencion, function(key, value) {
                    cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION +
                        '<tr>' +
                        '<td>' + value.codigo + '</td>' +
                        '<td>' + value.nombre + '</td>' +
                        '<td>' + value.tipo + '</td>' +
                        '<td>' + value.clasificacion + '</td>' +
                        '</tr>';
                });
                $('#diagnosticos_nuevo').append(cuerpo_DIAGNOSTICOS_PRESTACION);




            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Error al realizar la solicitud.</span></div>",
                    confirmButtonText: "ENTENDIDO",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: "ENTENDIDO",
                    timer: 1200,
                    timerProgressBar: true,
                });
            }
        });
    }

    // Maneja el clic en el botón "Cargar más"
    $(document).on('click', '#load-more-button-registrar', function() {
        var id_enfermeria_registrar = $('#id_enfermeria_evolucion').val();
        var nextPageUrlRegistrar = $(this).data('next-page-url-registrar');

        var $button = $(this);
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
        var var_url = '{{ url('cargar_mas_historiaL_invasivos') }}' + '?page=' + nextPageUrlRegistrar;

        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_enfermeria: id_enfermeria_registrar,
                page: nextPageUrlRegistrar // Pasar el número de página actual
            },
            success: function(data) {
                $button.prop('disabled', false).html(
                    'Cargar más <i class="fa fa-chevron-down"></i>');
                procesarRespuesta_registrar(data, false); // false indica que es una carga adicional
            }
        });
        return false;

    });

    function procesarRespuesta_registrar(data, esCargaInicial) {
        var cuerpo_INVASIVOS_ATENCION_REGISTRAR = '';
        if (data.invasivos_por_atencion.items && data.invasivos_por_atencion.items.length > 0) {
            $.each(data.invasivos_por_atencion.items, function(index, item) {
                cuerpo_INVASIVOS_ATENCION_REGISTRAR +=
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
            $('#historial_invasivos').html(cuerpo_INVASIVOS_ATENCION_REGISTRAR);
        } else {
            // Para cargas adicionales, agregamos al contenido existente
            $('#historial_invasivos').append(cuerpo_INVASIVOS_ATENCION_REGISTRAR);
        }

        // Manejo del botón "Cargar más"
        if (data.invasivos_por_atencion.nextPageUrl) {
            $('#load-more-container-registrar').html(
                '<button id="load-more-button-registrar" class="btn enf_btn-celeste" data-next-page-url-registrar="' +
                (data.invasivos_por_atencion.current_page + 1) + '">Cargar más </button>');
        } else {
            $('#load-more-container-registrar').html('');
        }

    }

    /* function validacionFechasInvasivosRegitro(fecha){
      // Fecha actual
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); 
      var yyyy = today.getFullYear();
      today = yyyy + '-' + mm + '-' + dd; 
      var fechaRegistrada = fecha; //fecha del registro del paciente
      
      $('.fecha').each(function() {
          $(this).attr('min', fechaRegistrada); 
          $(this).attr('max', today);
          // Establece la fecha actual como valor predeterminado si está dentro del rango permitido
          var valorActual = $(this).val();
          if (!valorActual || valorActual < fechaRegistrada) {
              $(this).val(fechaRegistrada);
          } else if (valorActual > today) {
              $(this).val(today);
          }
          // Desactiva la entrada manual y permite solo la selección con el selector
          $(this).on('input', function() {
              var valorActual = $(this).val();
              // Restringe la entrada a las fechas dentro del rango permitido
              if (valorActual < fechaRegistrada || valorActual > today) {
                  $(this).val('');
              }
          });
          // Impide la entrada manual en el campo de fecha
          $(this).on('keydown', function(e) {
              e.preventDefault(); // Previene la entrada manual desde el teclado
          });

          // Asegúrate de que el campo solo permita fechas dentro del rango permitido
          $(this).on('change', function() {
              var valorActual = $(this).val();
              if (valorActual < fechaRegistrada || valorActual > today) {
                  $(this).val('');
              }
          });
      });
    } */
</script>
