<script>
    // document.getElementById('plano_evolucion2').addEventListener('click', function(event) {
    //       var rect = document.getElementById('plano_evolucion2').getBoundingClientRect();
    //       var x = event.clientX - rect.left;
    //       var y = event.clientY - rect.top;
    //       $('#x').val(x)
    //       $('#y').val(y)
    //       var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado').val();
    //       var id_enfermeria=$('#id_enfermeria2').val();

    //       $("#tipo_proceso_editar").select2({ width: 'resolve'}); 
    //       $("#tamanio_proceso_editar").select2({ width: 'resolve'}); 
    //       lista_parametros('TIPO_PROCESO_PUNTO','tipo_proceso_editar','combo','TABLA_PARAMETROS',0);
    //       lista_parametros('TAMANIO_PUNTOS','tamanio_proceso_editar','combo','TABLA_PARAMETROS',0); 
    //       actualizarPuntoEvolucion('control_div_puntos_evolucion_editar', 'control_div_puntos_evolucion_leyenda_editar');
    //       pintar_puntos_by_evolucion(id_enfermeria,'control_div_puntos_evolucion_editar', 'control_div_puntos_evolucion_leyenda_editar','REGISTRO',id_tipo_atenciones_listado); 
    // });
    $('#modalEditarEvoluciones').on('hidden.bs.modal', function() {
        $('#alergia_si_upt').hide();
        $('#alergia_no_upt').hide();
    });

    $(document).ready(function() {
        const nivel = $("#nivel_ipress").val();
        if (nivel != 2) {
            const index = 2;
            const tabla = document.getElementById("tbl_CPMS_ENFERMERIA_EDITAR");
            for (let fila of tabla.rows) {
                if (fila.cells[index]) {
                    fila.cells[index].style.display = "none";
                }
            }
        }
    });

    async function capturarImagen2(celdaID) {
        const celda2 = document.getElementById(celdaID);
        try {
            // Mostrar la alerta para confirmar la acción
            const willUpdate = await swal({
                buttons: true,
                title: "¿Desea Realizar la Actualización?",
                text: "Recuerde verificar que todos los datos sean correctos.",
                icon: "warning",
                dangerMode: true,
            });
            if (willUpdate) {
                // Capturar la imagen del elemento usando html2canvas
                const canvas = await html2canvas(celda2);
                const imgData2 = canvas.toDataURL('image/png');
                $('#img_puntos_base_update').val(imgData2.replace(/^data:image\/[a-z]+;base64,/,
                "")); // Guardar imagen sin el prefijo
                const imagenCapturada2 = document.getElementById('imagenCapturada2');
                imagenCapturada2.src = imgData2;
                imagenCapturada2.style.display = 'block';
                // Limpiar el contenido de la celda y agregar la imagen capturada
                celda2.innerHTML = '';
                celda2.appendChild(imagenCapturada2);
                // Realizar la petición AJAX para actualizar los datos
                const token = $('#_token').val();
                const route = '{{ url('actualizar_nota_ingreso_evoluciones') }}';
                const formData = new FormData($("#EditarFormularioEvoluciones")[0]);
                const response = await $.ajax({
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
                });
                if (response != null) {
                    if (response == 3) {
                        swal({
                            buttons: false,
                            title: "REGISTRO EXISTENTE.",
                            text: "Ya existe una evolucion registrada en el turno seleccionado.",
                            icon: "error",
                            timer: 2500,
                        });
                    } else if (response != 0) {
                        // Limpiar el formulario y cerrar el modal
                        $('#EditarFormularioEvoluciones').trigger("reset");
                        $('#img_puntos_base_update').val('');
                        $('#imagenCapturada2').attr('src', '');
                        // Mostrar mensaje de éxito
                        $('#modalEditarEvoluciones').modal('hide');
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
                            $('#tipo_atenciones_listado').val(),
                            $('#id_asegurado_registro2').val(),
                        ];
                        $('#listaDatosEvoluciones').empty();
                        listar_evoluciones($p);
                        //VOLVER A SU ESTADO INICIAL
                        $('#img_puntos_base_update').val('');
                        $('#celda2').html();
                        $('#imagen_captura2').html(`
                        <img id="imagenCapturada2" style="display: none;" />
                    `);
                        console.log('Se borro imagenCapturada2');
                        $('#celda2').html(`
                        <div class="form-group">
                            <div class="grid-block" style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                              <canvas id="plano_evolucion2" name="plano" width="512" height="512"></canvas>
                              <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="x" id="x">
                              <input type="hidden" name="y" id="y"> 
                              <div id="control_div_puntos_evolucion_editar" name="control_div_puntos_evolucion"> 
                              </div>
                            </div>
                        </div>
                    `);
                        $('#control_div_puntos_evolucion_leyenda_editar').empty();
                        pintar_puntos_by_evolucion($('#id_enfermeria_ver').val(),
                            "control_div_puntos_evolucion_editar",
                            "control_div_puntos_evolucion_leyenda_editar", 'REGISTRO', $(
                                '#id_tipo_atenciones_listado').val());
                        planoEvolucion2();
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
            } else {
                console.log("El usuario ha cancelado la actualización.");
            }
        } catch (error) {
            console.error('Error al capturar la imagen o en la solicitud AJAX:', error);
            swal({
                buttons: false,
                title: "Error de Servicio de Registros",
                text: "Hubo un error al procesar la solicitud. Intente nuevamente.",
                icon: "error",
                timer: 2500,
            });
        }
    }
    // async function capturarImagen2(celdaID) {
    //   const celda2 = document.getElementById(celdaID);
    //   html2canvas(celda2).then(function (canvas) {
    //       const imgData2 = canvas.toDataURL('image/png');
    //       const imagenCapturada2 = document.getElementById('imagenCapturada2');
    //       $('#img_puntos_base_update').val(imgData2.replace(/^data:image\/[a-z]+;base64,/, ""));  
    //       imagenCapturada2.src = imgData2;
    //       imagenCapturada2.style.display = 'block';
    //       celda.innerHTML = '';
    //       celda.appendChild(imagenCapturada2); 
    //     });
    // }

    $('#modalEditarEvoluciones').on('shown.bs.modal', function() {
        $.ajax({
            url: `/buscarEvolucion/${$("#evolucion_id").val()}`,
            method: "GET",
            success: function(data) {
                const evolucion = JSON.parse(data);
                $("#fecha_registro_evolucion_2").val((evolucion.created_at).split(" ")[0]);
                $("#hora_registro_evolucion_2").val((evolucion.created_at).split(" ")[1]);
                $("#turno2").val(evolucion.id_turno);
                const inputFechaConHoraEv = document.getElementById('hora_registro_evolucion_2');
                const horaEv = inputFechaConHoraEv.value;
                inputFechaConHoraEv.addEventListener('change', function(e) {
                    const horaEv = e.target.value;
                    $("#turno2").val(isTimeInRangeEvol(horaEv, "07:30", "13:29") ? "2" :
                        isTimeInRangeEvol(horaEv, "13:30", "19:29") ? "3" :
                        isTimeInRangeEvol(horaEv, "19:30", "01:29") ? "4" :
                        isTimeInRangeEvol(horaEv, "01:30", "07:29") ? "1" : "").change();
                });
                $("#turno2").on('change', function(e) {
                    const hora = $("#hora_registro_evolucion_2").val();
                    if (hora) {
                        if ($("#turno2").val() == '2') {
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
                                $("#turno2").val(isTimeInRangeEvol(hora, "07:30", "13:29") ?
                                    "2" : isTimeInRangeEvol(hora, "13:30", "19:29") ?
                                    "3" : isTimeInRangeEvol(hora, "19:30", "01:29") ?
                                    "4" : isTimeInRangeEvol(hora, "01:30", "07:29") ?
                                    "1" : "").trigger("change");
                            }
                        }
                        if ($("#turno2").val() == '3') {
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
                                $("#turno2").val(isTimeInRangeEvol(hora, "07:30", "13:29") ?
                                    "2" : isTimeInRangeEvol(hora, "13:30", "19:29") ?
                                    "3" : isTimeInRangeEvol(hora, "19:30", "01:29") ?
                                    "4" : isTimeInRangeEvol(hora, "01:30", "07:29") ?
                                    "1" : "").trigger("change");
                            }
                        }
                        if ($("#turno2").val() == '4') {
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
                                $("#turno2").val(isTimeInRangeEvol(hora, "07:30", "13:29") ?
                                    "2" : isTimeInRangeEvol(hora, "13:30", "19:29") ?
                                    "3" : isTimeInRangeEvol(hora, "19:30", "01:29") ?
                                    "4" : isTimeInRangeEvol(hora, "01:30", "07:29") ?
                                    "1" : "").trigger("change");
                            }
                        }
                        if ($("#turno2").val() == '1') {
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
                                $("#turno2").val(isTimeInRangeEvol(hora, "07:30", "13:29") ?
                                    "2" : isTimeInRangeEvol(hora, "13:30", "19:29") ?
                                    "3" : isTimeInRangeEvol(hora, "19:30", "01:29") ?
                                    "4" : isTimeInRangeEvol(hora, "01:30", "07:29") ?
                                    "1" : "").trigger("change");
                            }
                        }
                    }
                });
            },
            error: function(error) {}
        });
    });

    let diagnosticosEvolucionesCurrEvo = null;

    $('body').on('click', '#editarEvoluciones', function() {
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
        const evolucion_id = this.getAttribute('data-id_evoluciones');
        $("#evolucion_id").val(evolucion_id);
        planoEvolucion2();
        //$('#FormularioEvoluciones').trigger("reset");
        $('#EditarFormularioEvoluciones').trigger("reset");
        eliminar_puntosNuevoEdit('control_div_puntos_evolucion_editar',
            'control_div_puntos_evolucion_leyenda_editar');
        $('#tituloFormularioEvoluciones-editar').html("Paciente de Cuidado Enfermero - PCE");
        $('#modalEditarEvoluciones').modal('show');

        var id_evoluciones = $(this).data('id_evoluciones'); //VIENE DEL LISTADO DE EVOLUCIONES
        /*******************************DATOS DEL FORMULARIO NUEVO EVOLUCIONES*********************************/
        var nombre_tipo_atencion = $('#nombre_tipo_atencion').val(); //VIENE DEL FORM NUEVO EVOLUCION
        var prioridad = $('#prioridad').val(); //VIENE DEL FORM NUEVO EVOLUCION
        var id_enfermeria = $('#id_enfermeria_ver').val(); //VIENE DEL FORM NUEVO EVOLUCION
        var id_tipo_atenciones_listado = $('#id_tipo_atenciones_listado')
    .val(); //VIENE DEL FORM NUEVO EVOLUCION
        //var id_nota_enfermeria_evolucion_listado = $('#id_nota_enfermeria_evolucion_listado').val();//VIENE DEL FORM NUEVO EVOLUCION
        var id_asegurado = $('#id_asegurado').val(); //VIENE DEL FORM NUEVO EVOLUCION
        var id_ext_upt = $('#id_ext_list').val(); //VIENE DEL FORM NUEVO EVOLUCION

        /**********DATOS DEL FORMULARIO NUEVO EVOLUCIONES PARA EL FORMULARIO EDITAR EVOLUCIONES*****************/
        $('#id_enfermeria2').val(id_enfermeria); //COLOCO EL VALOR ID_ENFERMERIA EN EL FORM EDITAR
        $('#nombre_tipo_atencion_registro2').val('EMERGENCIA');
        $('#prioridad_registro2').val(prioridad);
        $('#id_evoluciones_registro2').val(id_evoluciones);
        $('#id_tipo_atenciones_evolucion_registro2').val(2);
        $('#id_ext_evolucion_upt').val(id_ext_upt);
        console.log(id_ext_upt)
        console.log($("#id_tipo_atenciones_evolucion_registro2").val())

        //$('#id_nota_enfermeria_evolucion_registro2').val(id_nota_enfermeria_evolucion_listado);
        $('#id_asegurado_registro2').val(id_asegurado);
        $('#cuerpo_diagnosticos_ENFERMERIA_EDITAR').html('');
        cambiar_de_PestanaTab('tab0defaultEvoluciones-editar');
        var id_enfermeria = $('#id_enfermeria2').val();
        var id_prestacion_listado = null;
        const tabEvoluciones_editar = document.getElementById('tabEvolucionesCPMS_editar');
        tabEvoluciones_editar.setAttribute('data-toggle', 'none');
        tabEvoluciones_editar.setAttribute('rel', 'tooltip');
        tabEvoluciones_editar.setAttribute('title', 'Debe existir la atencion.');
        $('[rel="tooltip"]').tooltip();
        $.ajax({
            url: `/buscarIdPrestacionParaNotaDeEmergencia/${id_enfermeria}/${2}`,
            method: "GET",
            success: function(data) {
                id_prestacion_listado = data.length == 0 ? null : data[0].id;
                if (id_prestacion_listado) {
                    tabEvoluciones_editar.setAttribute('data-toggle', 'tab');
                    tabEvoluciones_editar.setAttribute('title', '');
                    $('[rel="tooltip"]').tooltip('destroy');
                }
                $('#id_prestacion_listado').val(id_prestacion_listado);
                $('#id_prestacion_registro2').val(id_prestacion_listado);
                editar_evoluciones(id_evoluciones, id_enfermeria, id_prestacion_listado,
                    id_asegurado);
            },
            error: function(error) {
                console.error("Error al realizar la solicitud", error);
            }
        });
        lista_parametros('TIPO_PROCESO_PUNTO', 'tipo_proceso_editar', 'combo', 'TABLA_PARAMETROS', 0);
        lista_parametros('TAMANIO_PUNTOS', 'tamanio_proceso_editar', 'combo', 'TABLA_PARAMETROS', 0);
        pintar_puntos_by_evolucion(id_enfermeria, "control_div_puntos_evolucion_editar",
            "control_div_puntos_evolucion_leyenda_editar", 'REGISTRO', id_tipo_atenciones_listado);
    });

    $(document).ready(function() {
        $('#ActualizarEvoluciones').click(function(e) {
            e.preventDefault();
            // capturarContenidoCelda2('celda2');
            var id_prestacion = parseInt($('#id_prestacion_registro2').val());
            console.log('ID PRESTACION SETEADO => ' + id_prestacion)
            var turno = $('#turno2').val();
            var descripcion_subjetivo = $('#descripcion_subjetivo2').val();
            var descripcion_objetivo = $('#descripcion_objetivo2').val();
            var descripcion_intervenciones = $('#descripcion_intervenciones2').val();
            var descripcion_evaluaciones = $('#descripcion_evaluaciones2').val();
            let tbl_DIAGNOSTICO_ENFERMERIA_EDITAR = $('#tbl_DIAGNOSTICO_ENFERMERIA_EDITAR tbody tr')
                .length;
            let tbl_INTERVENCIONES_ENFERMERIA_EDITAR = $(
                '#tbl_INTERVENCIONES_ENFERMERIA_EDITAR tbody tr').length;
            let tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR = $('#tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR tbody tr')
                .length;
            let tbl_EVALUACIONES_ENFERMERIA = $('#tbl_EVALUACIONES_ENFERMERIA_EDITAR tbody tr').length;
            let tbl_CPMS_ENFERMERIA_EDITAR = $('#tbl_CPMS_ENFERMERIA_EDITAR tbody tr').length;

            let fechaReg = $("#fecha_registro_evolucion_2").val();
            let horaReg = $("#hora_registro_evolucion_2").val();
            let fechaIng = $("#fecha_hora_ing_upt").val();
            let fechaEg = $("#fecha_hora_eg_upt").val() != "" ? $("#fecha_hora_eg_upt").val() : null;

            if (!horaReg.match(/^\d{2}:\d{2}:\d{2}$/)) {
                horaReg += ':00';
            }

            let fechaHoraReg = `${fechaReg} ${horaReg}`;
            let fechaHoraUptDate = new Date(fechaHoraReg.replace(' ', 'T'));
            let fechaIngUptDate = new Date(fechaIng.replace(' ', 'T'));
            let fechaEgUptDate = fechaEg ? new Date(fechaEg.replace(' ', 'T')) : null;

            if (fechaHoraUptDate < fechaIngUptDate) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: `Proceso cancelado. No se puede registrar con una fecha y hora anterior al ingreso del paciente: ${fechaIng}.`,
                    icon: "error",
                    timer: 3500,
                });
                return;
            }

            if (fechaEgUptDate && (fechaHoraUptDate > fechaEgUptDate)) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: `Proceso cancelado. No se puede registrar con una fecha y hora posterior al egreso del paciente: ${fechaEg}.`,
                    icon: "error",
                    timer: 3500,
                });
                return;
            }

            if (id_prestacion) {
                /* if(tbl_CPMS_ENFERMERIA_EDITAR <= 0){
                  swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de CPMS.",
                    icon: "error",
                    timer: 2500,
                  });
                  cambiar_de_PestanaTab("tab2defaultEvoluciones-editar");
                  setTimeout(function() {
                    $('#add_CPMS_ENFERMERIA_EDITAR').focus();  
                  }, 1000);
                  return;
                } */
                /* let isValidName = false;
                $('input[name="descripcion_CPMS_ENFERMERIA_EDITAR[]"]').each(function(index) {
                    let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número
                    // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                    let descripcionInput = $('input[name="descripcion_CPMS_ENFERMERIA_EDITAR[]"]').eq(index);
                    // Verifica si el valor es un número válido y mayor a 0
                    if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                        isValidName = true; // Marca como inválido
                        descripcionInput.addClass('is-invalid');
                    } else {
                      descripcionInput.removeClass('is-invalid');
                    }
                }); */
                /* if (isValidName) {
                    swal({
                        buttons: false,
                        title: "Verifique los campos de procedimiento",
                        text: "Proceso cancelado. Ingrese un valor válido.",
                        icon: "error",
                        timer: 2500,
                    });
                    cambiar_de_PestanaTab("tab2defaultEvoluciones-editar");
                    return false; // Previene el envío del formulario
                }
                let isValid = false;
                $('input[name="procedimiento_cantidad_editar[]"]').each(function(index) {
                    let cantidad = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(cantidad); // Convierte a número
                    let descripcionInput = $('input[name="procedimiento_cantidad_editar[]"]').eq(index);
                    // Verifica si el valor es un número válido y mayor a 1
                    if (isNaN(cantidadNumerica) || cantidadNumerica <= 0) {
                        isValid = true;
                        descripcionInput.addClass('is-invalid');
                        // Muestra un mensaje de error
                    } else {
                      descripcionInput.removeClass('is-invalid');
                    }
                });
                if(isValid){
                  swal({
                      buttons: false,
                      title: "Verifique los campos de cantidad",
                      text: "Proceso cancelado. Ingrese un valor en la cantidad.",
                      icon: "error",
                      timer: 2500,
                  });
                  cambiar_de_PestanaTab("tab2defaultEvoluciones-editar");
                  return false; 
                } */
            }

            if (turno == null || turno == '' || turno.length < 1) {
                swal({
                    buttons: false,
                    title: "Verifique los campos ingresados",
                    text: "Proceso cancelado. Seleccione un TURNO VÁLIDO para el registro correspondiente.",
                    icon: "error",
                    timer: 2500,
                });
                cambiar_de_PestanaTab("tab0defaultEvoluciones-editar");
                /* $('#turno2').next('.select2-container').addClass('borde-alerta');  
                setTimeout(function() {
                  $('#turno2').focus();  
                }, 1000); */
                return;
            } else {
                /* $('#turno2').next('.select2-container').removeClass('borde-alerta');  */
            }

            // if(descripcion_subjetivo == null || descripcion_subjetivo.length <=5){ 
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo SUBJETIVO.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab0defaultEvoluciones-editar");
            //   $('#descripcion_subjetivo2').addClass('borde-alerta');
            //   setTimeout(function() {
            //     $('#descripcion_subjetivo2').focus();  
            //   }, 1000);
            //   return;
            // }else{
            //   $('#descripcion_subjetivo2').removeClass('borde-alerta');
            // }

            // if(descripcion_objetivo == null || descripcion_objetivo.length <=5){ 
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo OBJETIVO.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab0defaultEvoluciones-editar");
            //   $('#descripcion_objetivo2').addClass('borde-alerta');
            //   setTimeout(function() {
            //     $('#descripcion_objetivo2').focus();  
            //   }, 1000);
            //   return;
            // }else{
            //   $('#descripcion_objetivo2').removeClass('borde-alerta');
            // }

            // if(tbl_DIAGNOSTICO_ENFERMERIA_EDITAR <= 0){
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de DIAGNÓSTICOS.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones-editar");
            //   setTimeout(function() {
            //     $('#add_DIAGNOSTICO_ENFERMERIA_EDITAR').focus();  
            //   }, 1000);
            //   return;
            // }
            if (tbl_DIAGNOSTICO_ENFERMERIA_EDITAR > 0) {
                let isValidNameDiagnostico = false;
                $('input[name="descripcion_DIAGNOSTICO_ENFERMERIA_EDITAR[]"]').each(function(index) {
                    let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número

                    // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                    let descripcionInput = $(
                        'input[name="descripcion_DIAGNOSTICO_ENFERMERIA_EDITAR[]"]').eq(
                        index);
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
                    cambiar_de_PestanaTab("tab1defaultEvoluciones-editar");
                    return false; // Previene el envío del formulario
                }
            }

            // if(tbl_INTERVENCIONES_ENFERMERIA_EDITAR <= 0){
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de EJECUCIÓN.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones-editar"); 
            //   setTimeout(function() {
            //     $('#add_INTERVENCIONES_ENFERMERIA_EDITAR').focus();  
            //   }, 1000);
            //   return;
            // }
            if (tbl_INTERVENCIONES_ENFERMERIA_EDITAR > 0) {
                let isValidNameIntervenciones = false;
                $('input[name="descripcion_INTERVENCIONES_ENFERMERIA_EDITAR[]"]').each(function(index) {
                    let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número

                    // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                    let descripcionInput = $(
                        'input[name="descripcion_INTERVENCIONES_ENFERMERIA_EDITAR[]"]').eq(
                        index);
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
                    cambiar_de_PestanaTab("tab1defaultEvoluciones-editar");
                    return false; // Previene el envío del formulario
                }
            }

            // if(tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR <= 0){
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese como mínimo un ITEM dentro de la selección de PLANEACIÓN.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones-editar");
            //   setTimeout(function() {
            //     $('#add_PLAN_CUIDADO_ENFERMERIA_EDITAR').focus();  
            //   }, 1000);
            //   return;
            // }
            if (tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR > 0) {
                let isValidNamePlan = false;
                $('input[name="descripcion_PLAN_CUIDADO_ENFERMERIA_EDITAR[]"]').each(function(index) {
                    let id_relacionado = $(this).val().trim(); // Elimina espacios en blanco
                    let cantidadNumerica = parseFloat(id_relacionado); // Convierte a número

                    // Selecciona el input correspondiente de descripcion_CPMS_ENFERMERIA[] usando el índice
                    let descripcionInput = $(
                        'input[name="descripcion_PLAN_CUIDADO_ENFERMERIA_EDITAR[]"]').eq(
                        index);
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
                    cambiar_de_PestanaTab("tab1defaultEvoluciones-editar");
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
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones-editar");
            //   setTimeout(function() {
            //     $('#add_EVALUACIONES_ENFERMERIA_EDITAR').focus();  
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
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones-editar");
            //   $('#descripcion_intervenciones2').addClass('borde-alerta');
            //   setTimeout(function() {
            //     $('#descripcion_intervenciones2').focus();  
            //   }, 1000);
            //   return;
            // }else{
            //   $('#descripcion_intervenciones2').removeClass('borde-alerta');
            // }

            // if(descripcion_evaluaciones == null || descripcion_evaluaciones.length <=5){ 
            //   swal({
            //     buttons: false,
            //     title: "Verifique los campos ingresados",
            //     text: "Proceso cancelado. Ingrese un detalle mayor a 5 caracteres para el campo DETALLE DE EVALUACIONES.",
            //     icon: "error",
            //     timer: 2500,
            //   });
            //   cambiar_de_PestanaTab("tab1defaultEvoluciones-editar");
            //   $('#descripcion_evaluaciones2').addClass('borde-alerta');
            //   setTimeout(function() {
            //     $('#descripcion_evaluaciones2').focus();  
            //   }, 1000);
            //   return;
            // }else{
            //   $('#descripcion_evaluaciones2').removeClass('borde-alerta');
            // }

            var token = $('#_token').val();
            var route = '{{ url('actualizar_nota_ingreso_evoluciones') }}';
            capturarImagen2('celda2');
            // swal({
            //       buttons: true,
            //       title: "¿Desea Realizar el Registro?",
            //       text: "Recuerde verificar que todos los datos sean correctos.",
            //       icon: "warning",
            //       dangerMode: true,
            //     }).then((willDelete) => {
            //       if (willDelete) { 
            //         setTimeout(function() {

            //           var formData = new FormData($("#EditarFormularioEvoluciones") [0]);
            //           $.ajax({
            //             data: formData,
            //             url:route,
            //             contentType: false,
            //             processData: false,
            //             headers: {'X-CSRF-TOKEN':token},
            //             type:'POST',
            //             enctype: 'multipart/form-data',
            //             dataType:'JSON',
            //             success: function(data){
            //               if(data!=null){
            //                   if(data!=0){
            //                       $('#EditarFormularioEvoluciones').trigger("reset");
            //                       $('#img_puntos_base_update').val('');
            //                       $('#imagenCapturada2').attr('src', '');
            //                       //capturarContenidoCelda2('celda2',1);
            //                       //actualizarPuntoEvolucion('control_div_puntos_evolucion_editar', 'control_div_puntos_evolucion_leyenda_editar');
            //                       //pintar_puntos_by_evolucion($('#id_enfermeria2').val(),'control_div_puntos_evolucion_editar', 'control_div_puntos_evolucion_leyenda_editar','REGISTRO',$('#id_tipo_atenciones_evolucion_registro2').val()); 
            //                       $('#modalEditarEvoluciones').modal('hide');
            //                       swal({
            //                         buttons: false,
            //                         title: "Registro Exitoso",
            //                         text: "Datos registrados Exitosamente.",
            //                         icon: "success",
            //                         timer: 1500,
            //                       });
            //                       const $p = [
            //                         $('#fecha_registro_hoja_grafica_inicial').val(), 
            //                         $('#fecha_registro_hoja_grafica_inicial').val(),
            //                         $('#descripcion_turno_bus_listado').val(),
            //                         $('#id_tipo_atenciones_evolucion_registro2').val(),
            //                         $('#id_asegurado_registro2').val(),
            //                       ];
            //                       listar_evoluciones($p);
            //                   }else{

            //                     swal({
            //                       buttons: false,
            //                       title: "Error de Servicio de Registros",
            //                       text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
            //                       icon: "error",
            //                       timer: 2500,
            //                     });

            //                   }
            //             }else{

            //               swal({
            //                   buttons: false,
            //                   title: "Error de Servicio de Registros",
            //                   text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
            //                   icon: "error",
            //                   timer: 2500,
            //                 });

            //             }
            //           }
            //         }, 50000);

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

    $('#add_DIAGNOSTICO_ENFERMERIA_EDITAR').on('click', function() {
        lista_table_tr_campo_detalle(
            'tbl_DIAGNOSTICO_ENFERMERIA_EDITAR',
            'descripcion_DIAGNOSTICO_ENFERMERIA_EDITAR',
            'cantidad_detalle_DIAGNOSTICO_ENFERMERIA',
            'id_relacionado_DIAGNOSTICO_ENFERMERIA_EDITAR',
            'boton_eliminar_item_DIAGNOSTICO_ENFERMERIA_EDITAR',
            'NANDA',
            'combo',
            '1||2||3||4||5',
            '1||2||3||4||5',
            'SI'
        );

    });
    $('#add_INTERVENCIONES_ENFERMERIA_EDITAR').on('click', function() {

        lista_table_tr_campo_detalle(
            'tbl_INTERVENCIONES_ENFERMERIA_EDITAR',
            'descripcion_INTERVENCIONES_ENFERMERIA_EDITAR',
            'cantidad_detalle_INTERVENCIONES_ENFERMERIA',
            'id_relacionado_INTERVENCIONES_ENFERMERIA_EDITAR',
            'boton_eliminar_item_INTERVENCIONES_ENFERMERIA_EDITAR',
            'NIC',
            'combo',
            '1||2||3||4||5',
            '1||2||3||4||5',
            'SI',
            'NO'
        );

    });
    $('#add_PLAN_CUIDADO_ENFERMERIA_EDITAR').on('click', function() {

        lista_table_tr_campo_detalle(
            'tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR',
            'descripcion_PLAN_CUIDADO_ENFERMERIA_EDITAR',
            'cantidad_detalle_PLAN_CUIDADO_ENFERMERIA',
            'id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR',
            'boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA_EDITAR',
            'NOC',
            'combo',
            '1||2||3||4||5',
            '1||2||3||4||5',
            'SI',
            'NO'
        );

    });
    $('#add_CPMS_ENFERMERIA_EDITAR').on('click', function() {
        lista_table_tr_campo_detalle(
            'tbl_CPMS_ENFERMERIA_EDITAR',
            'descripcion_CPMS_ENFERMERIA_EDITAR',
            'descripcion_detalle_CPMS_ENFERMERIA',
            'id_relacionado_CPMS_ENFERMERIA',
            'boton_eliminar_item_CPMS_ENFERMERIA_EDITAR',
            'null',
            'sin_elemento_adicional',
            'null',
            'null',
            'SI',
            'SI',
            'procedimiento_cantidad_editar',
            'procedimiento_tiempo',
            diagnosticosEvolucionesCurrEvo,
            $("#nivel_ipress").val()
        );
        var id_prestacion_listado = $('#id_prestacion_listado').val();
        /* diagnosticos_pacientes_prestacion(id_prestacion_listado); */

    });
    delete_table_tr_campo('tbl_DIAGNOSTICO_ENFERMERIA_EDITAR', 'boton_eliminar_item_DIAGNOSTICO_ENFERMERIA_EDITAR', '');
    delete_table_tr_campo('tbl_INTERVENCIONES_ENFERMERIA_EDITAR',
        'boton_eliminar_item_INTERVENCIONES_ENFERMERIA_EDITAR', '');
    delete_table_tr_campo('tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR', 'boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA_EDITAR',
        '');
    delete_table_tr_campo('tbl_CPMS_ENFERMERIA_EDITAR', 'boton_eliminar_item_CPMS_ENFERMERIA_EDITAR', '');
    delete_table_tr_campo('tbl_EVALUACIONES_ENFERMERIA_EDITAR', 'boton_eliminar_item_EVALUACIONES_ENFERMERIA_EDITAR',
        '');
    $('#add_EVALUACIONES_ENFERMERIA_EDITAR').on('click', function() {
        copiar_datos_otra_table_editar();
        delete_table_tr_campo('tbl_EVALUACIONES_ENFERMERIA_EDITAR',
            'boton_eliminar_item_EVALUACIONES_ENFERMERIA_EDITAR', '');
        delete_table_tr_campo('tbl_DIAGNOSTICO_ENFERMERIA_EDITAR',
            'boton_eliminar_item_DIAGNOSTICO_ENFERMERIA_EDITAR', '');
        delete_table_tr_campo('tbl_INTERVENCIONES_ENFERMERIA_EDITAR',
            'boton_eliminar_item_INTERVENCIONES_ENFERMERIA_EDITAR', '');
        delete_table_tr_campo('tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR',
            'boton_eliminar_item_PLAN_CUIDADO_ENFERMERIA_EDITAR', 'copiar_tabla_datos');
        delete_table_tr_campo('tbl_CPMS_ENFERMERIA_EDITAR', 'boton_eliminar_item_CPMS_ENFERMERIA_EDITAR', '');
    });

    $(document).on('click', '.boton_eliminar_item_cpms', function() {
        var $registro = $(this).closest('tr');
        $registro.css('text-decoration', 'line-through');
        $registro.css('color', 'red');
        var id_procedimiento = $registro.find('input.id_relacionado_CPMS_ENFERMERIA').val();
        var id_evoluciones = $registro.find('input#id_evoluciones').val();

        var route = '{{ url('anular_item_procedimiento_enfermeria') }}';
        Swal.fire({
            title: "¿Esta seguro de anular el registro?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, anular",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_procedimiento: id_procedimiento,
                        id_evoluciones: id_evoluciones
                    },
                    success: function(data) {
                        if (data != null) {
                            if (data != 0) {
                                $registro.remove();
                                console.log('Se ha anulado el registro');
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
                    error: function(xhr) {
                        alert('Error en la solicitud');
                    }
                });
            } else {
                Swal.fire({
                    title: "Operación Cancelada",
                    text: "Proceso de Registro Cancelado.",
                    icon: "error",
                    timer: 1500,
                    showConfirmButton: false
                });
                $registro.css('text-decoration', '');
                $registro.css('color', '');
            }
        });
    });
    $(document).on('click', '.boton_eliminar_atencion_enfermeria', function() {
        var $registro_evolucion = $(this).closest('tr');
        $registro_evolucion.css('text-decoration', 'line-through');
        $registro_evolucion.css('color', 'red');
        var id_evolucion_detalle = $registro_evolucion.find('input#id_detalle').val();
        atencion_enfermeria(id_evolucion_detalle, $registro_evolucion);
    });

    function atencion_enfermeria(id_evolucion_detalle, $registro_evolucion) {
        var route = '{{ url('anular_item_evoluciones_detalle') }}';
        Swal.fire({
            title: "¿Esta seguro de anular el registro?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, anular",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_evolucion_detalle: id_evolucion_detalle
                    },
                    success: function(data) {
                        if (data != null) {
                            if (data != 0) {
                                $registro_evolucion.remove();
                                console.log('Se ha anulado el registro');
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
                    error: function(xhr) {
                        alert('Error en la solicitud');
                    }
                });
            } else {
                Swal.fire({
                    title: "Operación Cancelada",
                    text: "Proceso de Registro Cancelado.",
                    icon: "error",
                    timer: 1500,
                    showConfirmButton: false
                });
                $registro_evolucion.css('text-decoration', '');
                $registro_evolucion.css('color', '');
            }
        });
    }

    function cambiar_de_PestanaTab(descripcion_de_tab) {
        var tab_seleccionado = document.querySelector('a[href="#' + descripcion_de_tab + '"]');
        tab_seleccionado.click();
    }

    function editar_evoluciones(id_evoluciones, id_nota_enfermeria_evolucion_listado, id_prestacion_listado,
        id_asegurado, nextPage = 1) {
        //var var_url='{{ url('ver_nota_ingreso_evoluciones') }}';
        var var_url = '{{ url('ver_nota_ingreso_evoluciones') }}' + '?page=' + nextPage;
        console.log(var_url);
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_evoluciones: id_evoluciones,
                id_enfermeria: id_nota_enfermeria_evolucion_listado,
                id_prestacion: id_prestacion_listado,
                id_asegurado: id_asegurado,
                id_tipo_atencion: $("#id_tipo_atenciones_evolucion_registro2").val(),
                id_ext: $("#id_ext_evolucion_upt").val(),
                page: nextPage // Pasar el número de página actual
            },
            success: function(data) {
                diagnosticosEvolucionesCurrEvo = data.diagnosticos_por_prestacion;
                const nivel = $("#nivel_ipress").val();
                $("#fecha_hora_ing_upt").val(data.fechas_ingreso_egreso.fecha_hora_ingreso);
                $("#fecha_hora_eg_upt").val(data.fechas_ingreso_egreso.fecha_hora_egreso);
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
                /*********************DATOS CABECERA DEL PACIENTE*************************/
                $('#num_documento_evolucion_editar').html(data.datos_paciente.nro_doc_ident);
                $('#nom_paciente_evolucion_editar').html(data.datos_paciente.paterno + " " + data
                    .datos_paciente.materno + " " + data.datos_paciente.nombre);
                $('#tipo_sangre_evolucion_editar').html(data.datos_paciente.tipo_sangre);
                $('#edad_evolucion_editar').html(data.datos_paciente.edad_corta);
                $('#sexo_evolucion_editar').html(data.datos_paciente.sexo);
                $('#hc_evolucion_editar').html(data.datos_paciente.nro_historia);
                $('#cel_acomp_evolucion_editar').html(data.estado_acomp.celular_acomp ? data
                    .estado_acomp.celular_acomp : 'Sin registrar');
                $('#parentesgo_editar').html(data.datos_paciente.parentesgo);
                $('#tipo_sangre_editar').html(data.datos_paciente.tipo_sangre ?? 'Sin registrar');
                /*********************DATOS DEL PACIENTE*************************/
                console.log($("#descripcion_intervenciones2"))
                $('#descripcion_turno2').val(data.nota_enfermeria[0].descripcion_turno).change();
                $("#descripcion_subjetivo2").val(data.nota_enfermeria[0].descripcion_subjetivo);
                $("#descripcion_objetivo2").val(data.nota_enfermeria[0].descripcion_objetivo);
                $("#descripcion_intervenciones2_2").val(data.nota_enfermeria[0].descripcion_intervenciones);
                $("#descripcion_evaluaciones2_2").val(data.nota_enfermeria[0].descripcion_evaluaciones);
                $("#descripcion_analisis2").val(data.nota_enfermeria[0].descripcion_analisis);
                $("#descripcion_plan2").val(data.nota_enfermeria[0].descripcion_plan);
                $("#fecha_registro_evoluciones_inicial2").val(data.nota_enfermeria[0].fecha_evol);

                if (data.estado_alergia.flag_alergia) {
                    $('#alergia_si_upt').show();
                } else {
                    $('#alergia_no_upt').show();
                }

                /*********************************TABLA TURNOS************************************/
                var cuerpo_turnos = '';
                /* if(data.turnos && data.turnos.length > 0){
                  $.each(data.turnos, function (key, value) { 
                    cuerpo_turnos += '<option value="' + value.id + '">' + value.descripcion + '</option>';
                  }); 
                } */
                /* $("#turno2").html(cuerpo_turnos);
                $('#turno2').val(data.nota_enfermeria[0].id_turno).trigger('change'); */
                /**************************************************************************/

                var cuerpo_DIAGNOSTICO_ENFERMERIA = '';
                $("#cuerpo_DIAGNOSTICO_ENFERMERIA_EDITAR").empty();
                if (data.nota_enfermeria_diagnostico && data.nota_enfermeria_diagnostico.length > 0) {
                    $.each(data.nota_enfermeria_diagnostico, function(key, value) {
                        let item_valor_combo = '';
                        for (let i = 1; i <= 5; i++) {
                            let = selected = '';
                            if (parseInt(value.valor_de_seleccion, 10) === i) {
                                selected = 'selected';
                            }
                            item_valor_combo += '<option value=' + i + ' ' + selected + '>' + i +
                                '</option>';
                        }
                        cuerpo_DIAGNOSTICO_ENFERMERIA = cuerpo_DIAGNOSTICO_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 80%;">' +
                            '<input type="hidden" id="id_detalle"name="id_detalle_d[]" value="' +
                            value.id_detalle + '">' +
                            '<input type="hidden" class="form-control id_relacionado_DIAGNOSTICO_ENFERMERIA_EDITAR  typeahead tt-query" id="id_relacionado_DIAGNOSTICO_ENFERMERIA_EDITAR' +
                            key +
                            '" name="id_relacionado_DIAGNOSTICO_ENFERMERIA_EDITAR[]" data-id="' +
                            key + '" value="' + value.id +
                            '"><input type="text" name="descripcion_DIAGNOSTICO_ENFERMERIA_EDITAR[]" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" ' +
                            '   value="' + value.descrip_abrev + '" disabled>' +
                            /* ' <td style="width: 20%;"><select  style="height: 28px !important;font-weight: 800;" class="form-control  cantidad_detalle_DIAGNOSTICO_ENFERMERIA" id="cantidad_detalle_DIAGNOSTICO_ENFERMERIA' +
                            key + '" name="cantidad_detalle_DIAGNOSTICO_ENFERMERIA[]" >' +
                            item_valor_combo + '</select>' + */
                            ' </td>' +
                            '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_atencion_enfermeria btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_DIAGNOSTICO_ENFERMERIA_EDITAR").html(cuerpo_DIAGNOSTICO_ENFERMERIA);
                //====================================================================================================================//

                /********************DIAGNOSTICOS POR PRESTACION PARA TABLA Y COMBO*****************/
                var cuerpo_DIAGNOSTICOS_PRESTACION = '';
                //var cuerpo_DIAGNOSTICOS_PRESTACION2 = '' ;
                $("#cuerpo_diagnosticos_ENFERMERIA_EDITAR").empty();
                if (data.diagnosticos_por_prestacion && data.diagnosticos_por_prestacion.length > 0) {
                    $.each(data.diagnosticos_por_prestacion, function(key, value) {
                        cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION +
                            '<tr>' +
                            '<td>' + value.codigo + '</td>' +
                            '<td>' + value.nombre + '</td>' +
                            '<td>' + value.tipo + '</td>' +
                            '<td>' + value.clasificacion + '</td>' +
                            '</tr>';
                        // cuerpo_DIAGNOSTICOS_PRESTACION2 += '<option value="' + value.id_diagnostico + '">' + value.nombre + '</option>';
                    });
                }

                $("#cuerpo_diagnosticos_ENFERMERIA_EDITAR").html(cuerpo_DIAGNOSTICOS_PRESTACION);
                // $(".cpms_listado").html(cuerpo_DIAGNOSTICOS_PRESTACION2);
                /*******************************************************************************/

                var cuerpo_PLAN_CUIDADO_ENFERMERIA = '';
                $("#cuerpo_PLAN_CUIDADO_ENFERMERIA_EDITAR").empty();
                if (data.nota_enfermeria_plan_cuidado && data.nota_enfermeria_plan_cuidado.length > 0) {
                    $.each(data.nota_enfermeria_plan_cuidado, function(key, value) {
                        let item_valor_combo2 = '';
                        for (let i = 1; i <= 5; i++) {
                            let = selected = '';
                            if (parseInt(value.valor_de_seleccion, 10) === i) {
                                selected = 'selected';
                            }
                            item_valor_combo2 += '<option value=' + i + ' ' + selected + '>' + i +
                                '</option>';
                        }
                        cuerpo_PLAN_CUIDADO_ENFERMERIA = cuerpo_PLAN_CUIDADO_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 80%;">' +
                            '<input type="hidden" id="id_detalle" name="id_detalle_p[]" value="' +
                            value.id_detalle + '">' +
                            '<input type="hidden" id="id" name="id[]" value="' + value.id + '">' +
                            '<input type="hidden" id="id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR' +
                            key +
                            '" name="id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR[]" data-id="' +
                            key + '" value="' + value.id +
                            '"><input type="text" name="descripcion_PLAN_CUIDADO_ENFERMERIA[]" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" ' +
                            '   value="' + value.descrip_abrev + '" disabled="disabled">' +
                            ' <td style="width: 20%;"><select  style="height: 28px !important;font-weight: 800;" class="form-control  cantidad_detalle_PLAN_CUIDADO_ENFERMERIA" id="cantidad_detalle_PLAN_CUIDADO_ENFERMERIA' +
                            key + '" name="cantidad_detalle_PLAN_CUIDADO_ENFERMERIA[]" >' +
                            item_valor_combo2 + '</select>' +
                            ' </td>' +
                            '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_atencion_enfermeria btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_PLAN_CUIDADO_ENFERMERIA_EDITAR").html(cuerpo_PLAN_CUIDADO_ENFERMERIA);
                //====================================================================================================================//

                var cuerpo_INTERVENCIONES_ENFERMERIA = '';
                $("#cuerpo_INTERVENCIONES_ENFERMERIA_EDITAR").empty();
                if (data.nota_enfermeria_intervenciones && data.nota_enfermeria_intervenciones.length > 0) {
                    $.each(data.nota_enfermeria_intervenciones, function(key, value) {
                        let item_valor_combo3 = '';
                        for (let i = 1; i <= 5; i++) {
                            let = selected = '';
                            if (parseInt(value.valor_de_seleccion, 10) === i) {
                                selected = 'selected';
                            }
                            item_valor_combo3 += '<option value=' + i + ' ' + selected + '>' + i +
                                '</option>';
                        }
                        cuerpo_INTERVENCIONES_ENFERMERIA = cuerpo_INTERVENCIONES_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 80%;">' +
                            '<input type="hidden" id="id_detalle" name="id_detalle_i[]" value="' +
                            value.id_detalle + '">' +
                            '<input type="hidden"  id="id_relacionado_INTERVENCIONES_ENFERMERIA_EDITAR' +
                            key +
                            '" name="id_relacionado_INTERVENCIONES_ENFERMERIA_EDITAR[]" data-id="' +
                            key + '" value="' + value.id +
                            '"><input type="text" name="descripcion_INTERVENCIONES_ENFERMERIA[]" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" ' +
                            '   value="' + value.descrip_abrev + '" disabled>' +
                            /* ' <td style="width: 20%;"><select  style="height: 28px !important;font-weight: 800;" class="form-control  cantidad_detalle_INTERVENCIONES_ENFERMERIA" id="cantidad_detalle_INTERVENCIONES_ENFERMERIA' +
                            key + '" name="cantidad_detalle_INTERVENCIONES_ENFERMERIA[]" >' +
                            item_valor_combo3 + '</select>' + */
                            ' </td>' +
                            '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_atencion_enfermeria btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_INTERVENCIONES_ENFERMERIA_EDITAR").html(cuerpo_INTERVENCIONES_ENFERMERIA);

                //====================================================================================================================//

                var cuerpo_EVALUACIONES_ENFERMERIA = '';
                $("#cuerpo_EVALUACIONES_ENFERMERIA_EDITAR").empty();
                if (data.nota_enfermeria_evaluaciones && data.nota_enfermeria_evaluaciones.length > 0) {
                    $.each(data.nota_enfermeria_evaluaciones, function(key, value) {
                        let item_valor_combo4 = '';
                        for (let i = 1; i <= 5; i++) {
                            let = selected = '';
                            if (parseInt(value.valor_de_seleccion, 10) === i) {
                                selected = 'selected';
                            }
                            item_valor_combo4 += '<option value=' + i + ' ' + selected + '>' + i +
                                '</option>';
                        }
                        cuerpo_EVALUACIONES_ENFERMERIA = cuerpo_EVALUACIONES_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 80%;">' +
                            '<input type="hidden" id="id_detalle" name="id_detalle_e[]" value="' +
                            value.id_detalle + '">' +
                            '<input type="hidden" id="id" name="id[]" value="' + value.id + '">' +
                            '<input type="text" class="form-control  input-sm" style="height: 28px !important;font-weight: 800;" ' +
                            '   value="' + value.descrip_abrev + '" disabled>' +
                            ' <td style="width: 20%;"><select  style="height: 28px !important;font-weight: 800;" class="form-control  cantidad_detalle_EVALUACIONES_ENFERMERIA" id="cantidad_detalle_EVALUACIONES_ENFERMERIA' +
                            key + '" name="cantidad_detalle_EVALUACIONES_ENFERMERIA[]" >' +
                            item_valor_combo4 + '</select>' +
                            ' </td>' +
                            '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_atencion_enfermeria btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_EVALUACIONES_ENFERMERIA_EDITAR").html(cuerpo_EVALUACIONES_ENFERMERIA);
                //====================================================================================================================//
                //var cuerpo_DIAGNOSTICOS_PRESTACION2 = '';
                var cuerpo_CPMS_ENFERMERIA = '';
                $("#cuerpo_CPMS_ENFERMERIA_EDITAR").empty();
                if (data.cpms && data.cpms.length > 0) {
                    $.each(data.cpms, function(key, value) {
                        console.log(value);
                        let lista_item_combo = '';
                        let valores = value;
                        if (data.diagnosticos_por_prestacion && data.diagnosticos_por_prestacion
                            .length > 0) {
                            $.each(data.diagnosticos_por_prestacion, function(key, value2) {
                                let selected = '';
                                if (value2.id_diagnostico == valores.id_diagnostico) {
                                    selected = 'selected';
                                }
                                lista_item_combo += '<option value="' + value2
                                    .id_diagnostico + '" ' + selected + '>' + value2
                                    .codigo + " - " + value2.nombre + '</option>';
                            });
                        }

                        cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                            '<tr >' +
                            ' <td style="width: 40%;">' +
                            '<input type="hidden" id="id_evoluciones" value="' + value
                            .id_evolucion_enfermeria + '">' +
                            '<input type="hidden" class="form-control id_relacionado_CPMS_ENFERMERIA  typeahead tt-query" name="id_relacionado_CPMS_ENFERMERIA[]" data-id=' +
                            key + ' style="height: 28px !important;" value="' + valores
                            .id_procedimiento + '">' +
                            '<input type="text" class="form-control input-sm" ' +
                            ' style="height: 28px !important;" name="procedimiento_descripcion[]" id="procedimiento_descripcion' +
                            key + '" value="' + value.codigo_procedimiento + ' - ' + value.nombre_procedimiento +
                            '" disabled> ' +
                            ' </td>' +
                            '<td style="width: 10%;">' +
                            '<input name="procedimiento_cantidad_editar[]" id="procedimiento_cantidad' +
                            key + '" value="' + value.cantidad +
                            '" type="number" step="1" type="text" class="form-control numero" style="height: 28px !important;font-weight: 40;" required>' +
                            '</td>';
                        if (nivel == 2) {
                            cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                                '<td style="width: 10%;">' +
                                '<input name="procedimiento_tiempo[]" id="procedimiento_tiempo' +
                                key + '" value="' + value.procedimiento_tiempo +
                                '" type="number" step="1" type="text" class="form-control numero" style="height: 28px !important;font-weight: 40;" required>' +
                                '</td>';
                        }
                        cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                            `<td style="width: ${nivel == 2 ? '40' : '50'}%;">` +
                            ' <select class="form-control cpms_listado" style="height:28px !important; font-weight: 100 !important;" id="procedimiento_diagnostico' +
                            key + '" name="procedimiento_diagnosticos[]">"' + lista_item_combo +
                            '"</select></td>' +
                            '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_cpms  btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>' +
                            '</tr>';
                    });
                }
                $("#cuerpo_CPMS_ENFERMERIA_EDITAR").html(cuerpo_CPMS_ENFERMERIA);
                // Función para seleccionar la opción correcta en el select

                //====================================================================================================================//
                // Array de opciones para el select
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

                let cuerpo_invasivos = '';
                $("#lista_invasivos_editar").empty();
                /* data.invasivos_por_evolucion.forEach((value, key) => {
                    let options = lista_Array.map(item => 
                      `<option value="${item.value}" ${item.value == value.accion ? 'selected' : ''}>${item.text}</option>`
                    ).join('');
                    cuerpo_invasivos += `
                      <tr>
                        <td>
                          ${value.id}<input type="hidden" id="" name="invasivos[]" value="${value.id}">
                          <input type="hidden" id="" name="id_evolucion_invasivo[]" value="${value.id_evolucion_invasivo}">
                        </td>
                        <td>
                          ${value.tipo}
                          <input class="form-control" type="hidden" name="descripcion_corta[${key}]" value="${value.fedescripcion_cortacha}">
                        </td>
                        <td>
                          <input class="form-control fecha_editar" type="date" name="invasivo_fecha[${key}]" value="${value.fecha ? value.fecha : ''}">
                        </td>
                        <td>
                          <input class="form-control" type="text" name="invasivo_lugar[${key}]" value="${value.lugar?value.lugar:''}">
                        </td>
                        <td>
                          <select class="form-control" id="invasivo_accion[${key}]" name="invasivo_accion[]" style="height: 28px !important; cursor: pointer; font-size: 13px; padding: 1px 12px !important; font-weight: 800;">
                            ${options}
                          </select>
                        </td>
                      </tr>
                    `;
                }); */
                $('#lista_invasivos_editar').append(cuerpo_invasivos);
                validacionFechasInvasivosEditar(data.fecha_registro_admision.fecha);
                /******************************HISTORIAL DE INVASIVOS POR ID ENFERMERIA*********/
                var cuerpo_INVASIVOS_ATENCION = '';
                var load_more_datos = '';
                $("#historial_invasivos_editar").empty();
                $("load-more").empty();
                if (data.invasivos_por_atencion.items && data.invasivos_por_atencion.items.length > 0) {

                    $.each(data.invasivos_por_atencion.items, function(key, value) {
                        cuerpo_INVASIVOS_ATENCION = cuerpo_INVASIVOS_ATENCION +
                            '<tr>' +
                            '<td>' + (key + 1) + '</td>' +
                            '<td>' + value.fecha + '</td>' +
                            '<td>' + value.tipo + '</td>' +
                            '<td>' + (value.lugar || '') + '</td>' +
                            '<td>' + value.accion + '</td>' +
                            '</tr>';
                    });
                }
                $('#historial_invasivos_editar').append(cuerpo_INVASIVOS_ATENCION);
                // Manejo del botón "Cargar más"
                if (data.invasivos_por_atencion.nextPageUrl) {
                    $('#load-more-container').html(
                        '<button id="load-more-button" class="btn enf_btn-celeste" data-next-page-url="' +
                        (data.invasivos_por_atencion.current_page + 1) + '">Cargar más </button>');
                } else {
                    $('#load-more-container').html('');
                }

                /********************************************************************************/
            }
        })
    }

    // Maneja el clic en el botón "Cargar más"
    $(document).on('click', '#load-more-button', function() {
        var id_nota_enfermeria_evolucion_listado = $('#id_enfermeria2').val();
        var nextPageUrl = $(this).data('next-page-url');
        var $button = $(this);
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
        var var_url = '{{ url('cargar_mas_historiaL_invasivos') }}' + '?page=' + nextPageUrl;

        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_enfermeria: id_nota_enfermeria_evolucion_listado,
                page: nextPageUrl // Pasar el número de página actual
            },
            success: function(data) {
                $button.prop('disabled', false).html(
                    'Cargar más <i class="fa fa-chevron-down"></i>');
                procesarRespuesta(data, false); // false indica que es una carga adicional
            }
        });
        return false;

    });

    function procesarRespuesta(data, esCargaInicial) {
        var cuerpo_INVASIVOS_ATENCION = '';

        $.each(data.invasivos_por_atencion.items, function(index, item) {
            cuerpo_INVASIVOS_ATENCION +=
                '<tr>' +
                '<td>' + ((data.invasivos_por_atencion.current_page - 1) * 5 + index + 1) + '</td>' +
                '<td>' + item.fecha + '</td>' +
                '<td>' + item.tipo + '</td>' +
                '<td>' + item.lugar + '</td>' +
                '<td>' + item.accion + '</td>' +
                '</tr>';
        });

        if (esCargaInicial) {
            // Para la carga inicial, establecemos el contenido
            $('#historial_invasivos_editar').html(cuerpo_INVASIVOS_ATENCION);
        } else {
            // Para cargas adicionales, agregamos al contenido existente
            $('#historial_invasivos_editar').append(cuerpo_INVASIVOS_ATENCION);
        }

        // Manejo del botón "Cargar más"
        if (data.invasivos_por_atencion.nextPageUrl) {
            $('#load-more-container').html(
                '<button id="load-more-button" class="btn enf_btn-celeste" data-next-page-url="' + (data
                    .invasivos_por_atencion.current_page + 1) + '">Cargar más </button>');
        } else {
            $('#load-more-container').html('');
        }
    }


    function diagnosticos_pacientes_prestacion(id_prestacion) {
        $.ajax({
            url: '{{ url('diagnosticos_pacientes_prestacion_combo/') }}/' + id_prestacion,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                var cuerpo_DIAGNOSTICOS_PRESTACION2 = '';
                if (data && data.length > 0) {
                    $.each(data, function(key, value) {
                        cuerpo_DIAGNOSTICOS_PRESTACION2 += '<option value="' + value
                            .id_diagnostico + '">' + value.nombre + '</option>';
                    });
                }
                $(".cpms_listado").html(cuerpo_DIAGNOSTICOS_PRESTACION2);
            }
        })
    }

    function validacionFechasInvasivosEditar(fecha) {
        // Fecha actual
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        var fechaRegistrada = fecha; //fecha del registro del paciente

        $('.fecha_editar').each(function() {
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
    }

    function planoEvolucion2() {
        document.getElementById('plano_evolucion2').addEventListener('click', function(event) {
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
    }
</script>
