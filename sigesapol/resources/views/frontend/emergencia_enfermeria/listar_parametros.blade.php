<script>
    function lista_parametros($descrip_tipo_parametro, $combo_rellenar, $elemento_de_carga, $tipo_de_ruta_a_buscar,
        $seleccionar_id_precargado) {
        var ruta_url = '';
        if ($tipo_de_ruta_a_buscar == 'TABLA_PARAMETROS') {
            ruta_url = '{{ url('parametro/listar_tipo_parametro/') }}/' + $descrip_tipo_parametro;
        } else if ($tipo_de_ruta_a_buscar == 'TABLA_ESPECIALIDADES') {
            ruta_url = '{{ url('parametro/especialidades/') }}';
        } else if ($tipo_de_ruta_a_buscar == 'TABLA_TIPO_DE_SANGRE') {
            ruta_url = '{{ url('parametro/listar_tipo_sangre/') }}';
        } else if ($tipo_de_ruta_a_buscar == 'TABLA_DEPARTAMENTO') {
            ruta_url = '{{ url('parametro/listar_departamentos/') }}';
        } else if ($tipo_de_ruta_a_buscar == 'TABLA_CARACTERISTICA_PACIENTE') {
            ruta_url = '{{ url('parametro/listar_') }}' + $descrip_tipo_parametro;
        } else if ($tipo_de_ruta_a_buscar == 'TIPO_SEGUROS') {
            ruta_url = '{{ url('parametro/listar_tipo_seguros/') }}';
        }
        $.ajax({
            url: ruta_url,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#" + $combo_rellenar).empty();
                if ($elemento_de_carga == 'combo') {
                    if ($combo_rellenar != 'tamanio_proceso') {
                        $("#" + $combo_rellenar).append("<option value='*'>--SELECCIONAR--</option>");
                    }
                }
                $.each(data, function(key, value) {
                    if ($elemento_de_carga == 'combo' || $elemento_de_carga == 'combo_multiple') {
                        if ($tipo_de_ruta_a_buscar == 'TABLA_PARAMETROS') {
                            if (value.caracteristica != '_' && value.caracteristica != '' && value
                                .caracteristica != null) {
                                $("#" + $combo_rellenar).append("<option value='" + value
                                    .caracteristica + "'>" + value.descrip_corta + "</option>");
                            } else {
                                if ($combo_rellenar == 'ingresa_por') {
                                    if (value.descrip_corta == $seleccionar_id_precargado) {
                                        $("#" + $combo_rellenar).append("<option value='" +
                                            $seleccionar_id_precargado + "' selected>" + value
                                            .descrip_corta + "</option>");
                                    } else {
                                        $("#" + $combo_rellenar).append("<option value='" + value
                                            .id + "'>" + value.descrip_corta + "</option>");
                                    }
                                } else {
                                    if (value.id == $seleccionar_id_precargado) {
                                        $("#" + $combo_rellenar).append("<option value='" +
                                            $seleccionar_id_precargado + "' selected>" + value
                                            .descrip_corta + "</option>");
                                    } else {
                                        $("#" + $combo_rellenar).append("<option value='" + value
                                            .id + "'>" + value.descrip_corta + "</option>");
                                    }
                                }
                            }
                        } else if ($tipo_de_ruta_a_buscar == 'TABLA_ESPECIALIDADES') {
                            if (value.id == $seleccionar_id_precargado) {
                                $("#" + $combo_rellenar).append("<option value='" +
                                    $seleccionar_id_precargado + "' selected>" + value.nombre +
                                    "</option>");
                            } else {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + value.nombre + "</option>");
                            }
                        } else if ($tipo_de_ruta_a_buscar == 'TABLA_TIPO_DE_SANGRE') {
                            if (value.id == $seleccionar_id_precargado) {
                                $("#" + $combo_rellenar).append("<option value='" +
                                    $seleccionar_id_precargado + "' selected>" + value
                                    .descripcion_corta + "</option>");
                            } else {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + value.descripcion_corta + "</option>");
                            }
                        } else if ($tipo_de_ruta_a_buscar == 'TABLA_DEPARTAMENTO') {
                            if (value.id == $seleccionar_id_precargado) {
                                $("#" + $combo_rellenar).append("<option value='" +
                                    $seleccionar_id_precargado + "' selected>" + value.codigo +
                                    '-' + value.descripcion_corta + "</option>");
                            } else {
                                $("#" + $combo_rellenar).append("<option value='" + value
                                    .id_departamento + "'>" + value.codigo + '-' + value
                                    .descripcion + "</option>");
                            }
                        } else if ($tipo_de_ruta_a_buscar == 'TABLA_CARACTERISTICA_PACIENTE') {
                            if ($descrip_tipo_parametro == 'etnias') {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + (value.descripcion + '(' + value.etnias + ')') +
                                    "</option>");
                            } else if ($descrip_tipo_parametro == 'idiomas') {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + (value.descripcion + '(' + value.codigo + ')') +
                                    "</option>");
                            } else if ($descrip_tipo_parametro == 'estado_civil') {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + (value.descripcion) + "</option>");
                            } else if ($descrip_tipo_parametro == 'religiones') {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + (value.descripcion) + "</option>");
                            } else if ($descrip_tipo_parametro == 'grados_instruccion') {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + (value.descripcion) + "</option>");
                            } else if ($descrip_tipo_parametro == 'ocupaciones') {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + (value.descripcion) + "</option>");
                            } else if ($descrip_tipo_parametro == 'parentescos') {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + (value.descripcion) + "</option>");
                            }
                        } else if ($tipo_de_ruta_a_buscar == 'TIPO_SEGUROS') {
                            if (value.id == $seleccionar_id_precargado) {
                                $("#" + $combo_rellenar).append("<option value='" +
                                    $seleccionar_id_precargado + "' selected>" + value
                                    .descripcion + "</option>");
                            } else {
                                $("#" + $combo_rellenar).append("<option value='" + value.id +
                                    "'>" + value.descripcion + "</option>");
                            }
                        }
                    }
                });
                if ($elemento_de_carga == 'combo') {
                    if ($combo_rellenar != 'tamanio_proceso' && $seleccionar_id_precargado == 0) {
                        $("#" + $combo_rellenar).val("*").change();
                    } else if ($combo_rellenar != 'tamanio_proceso' && $seleccionar_id_precargado != 0) {
                        $("#" + $combo_rellenar).val($seleccionar_id_precargado).change();
                    } else if ($combo_rellenar == 'tamanio_proceso') {
                        $("#" + $combo_rellenar).val("10").change();
                    }
                }
            }
        })
    }

    function lista_parametros_by_id($id_parametro, $combo_rellenar, $elemento_de_carga, $tipo_de_busqueda, $id_ipress) {
        var ruta_url = '';
        if ($tipo_de_busqueda == 'TABLA_PARAMETROS') {
            ruta_url = '{{ url('parametro/listar_tipo_parametro_detalle/') }}/' + $id_parametro;
        } else if ($tipo_de_busqueda == 'CONSULTORIO_BY_TIPO_ATENCION') {
            ruta_url = '{{ url('listar_consultorios_by_tipo_atencion_ajax/') }}/' + $id_ipress + '/' + $id_parametro;
        } else if ($tipo_de_busqueda == 'TABLA_PROVINCIAS') {
            ruta_url = '{{ url('parametro/listar_provincias/') }}/' + $id_parametro;
        } else if ($tipo_de_busqueda == 'TABLA_DISTRITOS') {
            ruta_url = '{{ url('parametro/listar_distritos/') }}/' + $id_parametro;
        }
        $.ajax({
            url: ruta_url,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#" + $combo_rellenar).empty();
                if ($elemento_de_carga == 'combo') {
                    $("#" + $combo_rellenar).append("<option value='*'>--SELECCIONAR--</option>");
                }
                $.each(data, function(key, value) {
                    if ($tipo_de_busqueda == 'TABLA_PARAMETROS') {
                        if ($elemento_de_carga == 'combo') {
                            $("#" + $combo_rellenar).append("<option value='" + value.id + "'>" +
                                value.descrip_larga + "</option>");
                        }
                    } else if ($tipo_de_busqueda == 'CONSULTORIO_BY_TIPO_ATENCION') {
                        ////listar topicos mediante la seleccion de tipo de origen
                        if ($elemento_de_carga == 'combo') {
                            $("#" + $combo_rellenar).append("<option value='" + value.id + "'>" +
                                value.nombre + "</option>");
                        }

                    } else if ($tipo_de_busqueda == 'TABLA_PROVINCIAS') {
                        if ($elemento_de_carga == 'combo') {
                            $("#" + $combo_rellenar).append("<option value='" + value.id_provincia +
                                "'>" + value.codigo + '-' + value.descripcion + "</option>");
                        }
                    } else if ($tipo_de_busqueda == 'TABLA_DISTRITOS') {
                        if ($elemento_de_carga == 'combo') {
                            $("#" + $combo_rellenar).append("<option value='" + value.id_distrito +
                                "'>" + value.codigo + '-' + value.descripcion + "</option>");
                        }
                    }
                });
                if ($elemento_de_carga == 'combo') {
                    if ($id_ipress == 0 || $id_ipress == '*' || $id_ipress == '') {
                        $id_ipress = '*';
                    }
                    if ($tipo_de_busqueda != 'CONSULTORIO_BY_TIPO_ATENCION') {
                        $("#" + $combo_rellenar).val($id_ipress).change();
                    }
                }
            }
        })
    }


    function agregar_formato_fecha_campo_input_descripcion_detalle($input_descripcion_detalle) {
        $('#' + $input_descripcion_detalle).datepicker({
            useCurrent: false,
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            orientation: "bottom left"
        }).on('changeDate', function(e) {
            console.log('click sobre fecha_kardex_final');
        });
    }

    function listar_datos_by_autocomplete_input($tipo_de_elemento, $elemento_a_utilizar, $input_relacionados,
        $tipo_de_busqueda,
        $longitud_permitida_para_busqueda) {

        if ($tipo_de_elemento == 'input') {
            $("#" + $elemento_a_utilizar).autocomplete({
                minLength: $longitud_permitida_para_busqueda,
                delay: 500,
                source: function(request, response) {
                    var url_consulta = '';
                    /////CUANDO ES UN PERSONAL MEDICO QUE BUSCA POR DNI
                    if ($tipo_de_busqueda == 'PERSONAL_PROFESIONAL') {
                        url_consulta = '{{ url('parametro/profesional/') }}/' + request.term;
                    } else if ($tipo_de_busqueda == 'UBIGEO_DITRITO') {
                        url_consulta = '{{ url('parametro/ubigeo_distrito/') }}/' + request.term;
                    } else if ($tipo_de_busqueda == 'ORIGEN_REFERENCIA') {
                        var institucion = '';
                        console.log('#' + $input_relacionados[2]);
                        if ($('#' + $input_relacionados[2]).prop('checked')) {
                            institucion = '-';
                        } else {
                            console.log('El checkbox está marcado');
                            institucion = 'x_institucion';
                        }
                        url_consulta = '{{ url('parametro/ipress_origen/') }}/' + request.term + '/' +
                            institucion;
                    }
                    $.ajax({
                        url: url_consulta,
                        dataType: "json",
                        success: function(data) {
                            // Limitar la cantidad de resultados a mostrar
                            var limitedData = data.slice(0, 20);
                            response(limitedData);
                        }
                    })
                },
                select: function(e, ui) {

                    /////CUANDO ES UN PERSONAL MEDICO QUE BUSCA POR DNI
                    if ($tipo_de_busqueda == 'PERSONAL_PROFESIONAL') {
                        $("#" + $input_relacionados[0]).val(ui.item.id);
                        $("#" + $input_relacionados[1]).val(ui.item.tipo_documento + ' ' + ui.item.dni);
                        $("#" + $input_relacionados[2]).val(ui.item.profesional);
                        $("#" + $input_relacionados[3]).val(ui.item.especializacion);
                        $("#" + $input_relacionados[4]).val(ui.item.colegiatura);
                        $("#" + $input_relacionados[5]).val(ui.item.rne);
                        $("#" + $input_relacionados[6]).val(ui.item.dni);

                        $("#" + $elemento_a_utilizar).removeClass('borde-alerta');
                    } else if ($tipo_de_busqueda == 'UBIGEO_DITRITO') {
                        $("#" + $input_relacionados[0]).val(ui.item.id_departamento);
                        $("#" + $input_relacionados[1]).val(ui.item.id_provincia);
                        $("#" + $input_relacionados[2]).val(ui.item.id_distrito);

                        $("#" + $elemento_a_utilizar).removeClass('borde-alerta');
                    } else if ($tipo_de_busqueda == 'ORIGEN_REFERENCIA') {
                        $("#" + $input_relacionados[0]).val(ui.item.id);
                        $("#" + $input_relacionados[1]).val(ui.item.establecimiento);

                        $("#" + $elemento_a_utilizar).removeClass('borde-alerta');
                    }

                }
            });

        } else if ($tipo_de_elemento == 'combo') {

        }

    }

    function lista_table_tr_campo_detalle(
        $tabla_relacionada,
        $input_descripcion,
        $input_descripcion_detalle,
        $input_id_relacionado,
        $boton_eliminar_item,
        $descrip_tipo_parametro,
        $tipo_de_elemento,
        $lista_de_value_en_combo,
        $lista_de_datos_en_combo,
        $habilita_busqueda,
        $cpms,
        $procedimiento_cantidad,
        $procedimiento_tiempo,
        diagnosticosEvolucionesCurr,
        nivel
    ) {

        var ind = $('#' + $tabla_relacionada + ' tbody tr').length;
        if (ind > 0) {
            $("#" + $input_id_relacionado).each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
            fila_medicamentos = $('#' + $tabla_relacionada + ' tbody').children('tr');
            rowIndex = $('#hidden_rowIndex').val();
            var idval = ind - 1;
            fila_medicamento = fila_medicamentos[idval];
        }

        var porcentaje = '';
        if ($tipo_de_elemento == 'input') {
            porcentaje = '80%';
        } else if ($cpms == 'SI') {
            porcentaje = '80%';
        } else if ($tipo_de_elemento == 'combo') {
            porcentaje = '77%';
        } else if ($tipo_de_elemento == 'checkbox') {
            porcentaje = '82%';
        } else if ($tipo_de_elemento == 'fecha') {
            porcentaje = '76%';
        } else {
            porcentaje = '100%';
        }

        if ($tipo_de_elemento == 'combo') {
            if ($lista_de_value_en_combo != '' && $lista_de_datos_en_combo != '') {
                var item_valor_combo = '';
                const lista_de_value_en_combo_Array = $lista_de_value_en_combo.split("||");
                const lista_de_datos_en_combo_Array = $lista_de_datos_en_combo.split("||");
                for (var i = 0; i < lista_de_datos_en_combo_Array.length; i++) {
                    item_valor_combo += '<option value=' + lista_de_value_en_combo_Array[i] + '>' +
                        lista_de_datos_en_combo_Array[i] + '</option>';
                }
            }
        }

        var newRow = '<tr>'
        newRow += '<td style="width: ' + porcentaje + ';"><input type="text"  required="" name="' + $input_descripcion +
            '[]" id="' + $input_descripcion + '' + ind +
            '" class="form-control input-sm descripcion_CPMS_editar" value="" style="height: 28px !important;"/>';
        newRow += '<input type="hidden" class="form-control ' + $input_id_relacionado + '  typeahead tt-query" id="' +
            $input_id_relacionado + '' + ind + '" name="' + $input_id_relacionado + '[]" data-id=' + ind +
            ' style="height: 28px !important;" required></td>';

        if ($tipo_de_elemento == 'input') {
            newRow += '<td>';
            newRow += '<input name="' + $input_descripcion_detalle + '[]" id="' + $input_descripcion_detalle + '' +
                ind +
                '" type="number" step="1" onKeyPress="return soloNumeros(event)"   type="text" class="form-control" style="height: 28px !important;font-weight: 800;" required>';
            newRow += '</td>'
            newRow += '<td>';
        } else if ($tipo_de_elemento == 'combo') {
            newRow += '<td style="width: 20%;">';
            newRow += '<select class="form-control ' + $input_descripcion_detalle + '" id="' +
                $input_descripcion_detalle + '' + ind + '" name="' + $input_descripcion_detalle +
                '[]"  style="height: 28px !important;cursor:pointer; font-size:13px;padding: 1px 12px !important;font-weight: 800;">' +
                item_valor_combo + '</select>';
            newRow += '</td>'
        } else if ($tipo_de_elemento == 'checkbox') {
            newRow += '<td>';
            newRow += '<input type="checkbox" id="' + $input_descripcion_detalle + '' + ind + '" name="' +
                $input_descripcion_detalle + '' + ind + '" style="margin-left: 10px;cursor: pointer;">';
            newRow += '<label for="' + $input_descripcion_detalle + '' + ind +
                '" style="cursor: pointer;"> IMPLEMENTADO </label> ';
            newRow += '</td>';
        } else if ($tipo_de_elemento == 'fecha') {
            newRow += '<td>';
            newRow +=
                '<input type="text" class="form-control datepicker" style="height: 28px !important;" autocomplete="off"';
            newRow += 'name="' + $input_descripcion_detalle + '[]" id="' + $input_descripcion_detalle + '' + ind +
                '" value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">';
            newRow += '</td>'
        }
        if ($cpms == 'SI') {
            newRow += '<td style="width: 10%;">';
            newRow += '<input name="' + $procedimiento_cantidad + '[]" id="procedimiento_cantidad' + ind +
                '" type="number" step="1" value="1" min="1" max="99" class="form-control cantidad_CPMS_editar numero" style="height: 28px !important;font-weight: 40;" required>';
            newRow += '</td>'
            if (nivel == 2) {
                newRow += '<td style="width: 10%;">';
                newRow += '<input name="' + $procedimiento_tiempo + '[]" id="procedimiento_tiempo' + ind +
                    '" type="number" step="1" value="1" min="1" max="999" class="form-control tiempo_CPMS_editar numero" style="height: 28px !important;font-weight: 40;" required>';
                newRow += '</td>'
            }
            /* if (tipoAtencion == 2) {
                newRow += '<td style="width: 10%;">';
                newRow += '<input name="' + $procedimiento_tiempo + '[]" id="procedimiento_tiempo' + ind +
                    '" type="number" step="1" value="1" min="1" max="999" class="form-control tiempo_CPMS_editar numero" style="height: 28px !important;font-weight: 40;" required>';
                newRow += '</td>'
            } */
            newRow += '<td style="width: 40%;">';
            newRow += ' <select class="form-control cpms_listado" id="procedimiento_diagnosticos' + ind +
                '" name="procedimiento_diagnosticos[]" style="height: 28px !important;font-weight: 40; width: 200px;">';
            diagnosticosEvolucionesCurr.forEach(diagnostico => {
                newRow +=
                    `<option value="${diagnostico.id_diagnostico}">${diagnostico.codigo ? (diagnostico.codigo).concat(" - ") : ''} ${diagnostico.nombre}</option>`;
            });
            newRow += '</select>';
            newRow += '</td>'
        }

        newRow += '<td> <button type="button" tabindex="46" class="btn btn-danger ' + $boton_eliminar_item +
            ' btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button>'
        newRow += '</tr>'


        ////valicacion de dato obligatorio
        var filas = $('#' + $tabla_relacionada + ' tbody tr');
        var ultimaFila = filas.last();
        var primeraFila = filas.first();
        if ($habilita_busqueda == 'SI') {
            if (primeraFila.find('input[name="' + $input_descripcion + '"]').val() == "") {
                // Mostrar un mensaje de advertencia al usuario
                alert("POR FAVOR, RECUERDE COMPLETAR TODOS LOS CAMPOS ANTES DE AGREGAR UN NUEVO ITEM.");
                return; // Detener la ejecución de la función
            } else if (primeraFila.find('input,select').filter(function() {
                    return $(this).val() == "";
                }).length > 0) {
                alert("POR FAVOR, RECUERDE COMPLETAR TODOS LOS CAMPOS ANTES DE AGREGAR UN NUEVO ITEM.");
                return; // Detener la ejecución de la función
            } else if (ultimaFila.find('input,select').filter(function() {
                    return $(this).val() == "";
                }).length > 0) {
                alert("POR FAVOR, RECUERDE COMPLETAR TODOS LOS CAMPOS ANTES DE AGREGAR UN NUEVO ITEM.");
                return; // Detener la ejecución de la función
            }
        }


        $('#' + $tabla_relacionada + ' tbody').append(newRow);
        $('#' + $input_descripcion + '' + ind).focus();

        ///SI ES FECHA GENERAMOS FORMATO PARA FECHAS
        if ($tipo_de_elemento == 'fecha') {
            agregar_formato_fecha_campo_input_descripcion_detalle($input_descripcion_detalle + '' + ind);
        }

        if ($habilita_busqueda == 'SI') {
            $("#" + $input_descripcion + '' + ind).autocomplete({
                minLength: 1,
                delay: 500,
                source: function(request, response) {
                    var url_consulta = '';
                    //buscamos cuando es relacionado a CPMS
                    if ($input_descripcion_detalle == 'descripcion_detalle_CPMS_ENFERMERIA') {
                        url_consulta = '{{ url('obtener_procedimiento_convenio_enfermeria/') }}/' + request
                            .term + '/1/3/4';
                        //buscamos cuando es relacionado a personal convocado en incidencias
                    } else if ($input_descripcion_detalle == 'descripcion_detalle_PERS_CONV_EVEAD') {
                        url_consulta = '{{ url('parametro/personal_convocado/') }}/' + request.term;
                    } else {
                        //busqueda general de la tabla de parametros
                        url_consulta = '{{ url('parametro/listar_tipo_parametro/') }}/' + request.term +
                            '/' + $descrip_tipo_parametro;
                    }


                    $.ajax({
                        url: url_consulta,
                        dataType: "json",
                        success: function(data) {
                            let listaDGSeleccionado = $("." + $input_id_relacionado).map(
                                function() {
                                    return parseInt($(this).val());
                                }).get();
                            let filtro = data.filter((element) => !listaDGSeleccionado.includes(
                                element.id));
                            var limitedData = filtro.slice(0, 10);
                            response(limitedData);
                        }
                    })
                },
                select: function(e, ui) {
                    var id_relacionado = ui.item.id;
                    var descripcion = ui.item.value;
                    $("#" + $input_id_relacionado + '' + ind).val(id_relacionado);
                    $("#" + $input_descripcion + '' + ind).attr('disabled', 'disabled');

                    $('#' + $input_descripcion_detalle + '' + ind).focus();

                    if ($tabla_relacionada == 'tbl_PLAN_CUIDADO_ENFERMERIA') {
                        $("#tbl_EVALUACIONES_ENFERMERIA tbody").empty();
                    }
                }
            });

            $("#" + $input_descripcion + '' + ind).on('blur', function() {
                var id_relacionado = $("#" + $input_id_relacionado + '' + ind).val();
                if (id_relacionado == "") {
                    $("#" + $input_descripcion + '' + ind).val("");
                    return false;
                }
            });
        }

    }

    function delete_table_tr_campo($tabla_relacionada, $boton_eliminar_item, $copiar_tabla_datos) {
        $('#' + $tabla_relacionada + ' tbody').on('click', 'button.' + $boton_eliminar_item + '', function() {
            var obj = $(this);
            obj.parent().parent().remove();
            if ($copiar_tabla_datos == 'copiar_tabla_datos') {
                copiar_datos_otra_table();
            }
        });
    }

    function delete_table_tr_campo_balance($tabla_relacionada, $boton_eliminar_item, $copiar_tabla_datos) {
        $('#' + $tabla_relacionada + ' tbody').on('click', 'button.' + $boton_eliminar_item + '', function() {
            var obj = $(this);
            obj.parent().parent().remove();
            if ($copiar_tabla_datos == 'copiar_tabla_datos') {
                copiar_datos_otra_table();
            }
            actualizarTotalIngreso();
            actualizarTotalEgreso();
            actualizarTotalIngreso2();
            actualizarTotalEgreso2();
        });
    }

    function copiar_datos_otra_table() {
        $("#tbl_EVALUACIONES_ENFERMERIA tbody").empty();
        var item_valor_combo = '';
        var lista_de_value_en_combo = '1||2||3||4||5';
        var lista_de_datos_en_combo = '1||2||3||4||5';
        const lista_de_value_en_combo_Array = lista_de_value_en_combo.split("||");
        const lista_de_datos_en_combo_Array = lista_de_datos_en_combo.split("||");
        for (var i = 0; i < lista_de_datos_en_combo_Array.length; i++) {
            item_valor_combo +=
                '<option  style="height: 28px !important;cursor:pointer; font-size:13px;padding: 1px 12px !important;font-weight: 800;" value=' +
                lista_de_value_en_combo_Array[i] + '>' + lista_de_datos_en_combo_Array[i] + '</option>';
        }

        var filas = $('#tbl_PLAN_CUIDADO_ENFERMERIA tbody tr')
        for (var i = 0; i < filas.length; i++) {
            var inputs = filas[i].querySelectorAll("input[type='text']");
            var selects = filas[i].querySelectorAll("select.cantidad_detalle_PLAN_CUIDADO_ENFERMERIA");

            for (var j = 0; j < inputs.length; j++) {
                var selectedValue = selects[j].value; // Obtén el valor seleccionado del select

                var newRow = '<tr>';
                newRow +=
                    '<td style="width: 85%;"><input type="text" disabled name="descripcion_EVALUACIONES_ENFERMERIA[]" id="descripcion_EVALUACIONES_ENFERMERIA' +
                    j + '" class="form-control input-sm" value="' + inputs[j].value +
                    '" style="height: 28px !important;"/></td>';
                newRow +=
                    '<td><select class="form-control cantidad_detalle_EVALUACIONES_ENFERMERIA" id="cantidad_detalle_EVALUACIONES_ENFERMERIA' +
                    j +
                    '" name="cantidad_detalle_EVALUACIONES_ENFERMERIA[]" style="height: 28px !important; cursor:pointer; padding: 1px 12px !important; font-weight: 800;">';

                // Crear las opciones del select con el valor seleccionado
                for (var k = 0; k < lista_de_value_en_combo_Array.length; k++) {
                    var selected = (lista_de_value_en_combo_Array[k] == selectedValue) ? 'selected' : '';
                    newRow += '<option value="' + lista_de_value_en_combo_Array[k] + '" ' + selected + '>' +
                        lista_de_datos_en_combo_Array[k] + '</option>';
                }

                newRow += '</select></td>';
                newRow +=
                    '<td> <button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_EVALUACIONES_ENFERMERIA btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button>'
                newRow += '</tr>';
                $('#tbl_EVALUACIONES_ENFERMERIA tbody').append(newRow);
            }
        }

    }

    function copiar_datos_otra_table_editar() {
        var item_valor_combo = '';
        var lista_de_value_en_combo = '1||2||3||4||5';
        var lista_de_datos_en_combo = '1||2||3||4||5';
        const lista_de_value_en_combo_Array = lista_de_value_en_combo.split("||");
        const lista_de_datos_en_combo_Array = lista_de_datos_en_combo.split("||");

        // Construir las opciones del combo
        for (var i = 0; i < lista_de_datos_en_combo_Array.length; i++) {
            item_valor_combo +=
                '<option style="height: 28px !important;cursor:pointer; font-size:13px;padding: 1px 12px !important;font-weight: 800;" value="' +
                lista_de_value_en_combo_Array[i] + '">' + lista_de_datos_en_combo_Array[i] + '</option>';
        }

        var filas = $('#tbl_PLAN_CUIDADO_ENFERMERIA_EDITAR tbody tr');

        for (var i = 0; i < filas.length; i++) {
            var inputs = filas[i].querySelectorAll("input[type='text']");
            var selects = filas[i].querySelectorAll("select.cantidad_detalle_PLAN_CUIDADO_ENFERMERIA");
            let inputs_id_detalles = filas[i].querySelectorAll("input[type='hidden'][name='id_detalle_p[]']");
            let ids = filas[i].querySelectorAll("input[type='hidden'][name='id[]']"); // Cambiado a plural

            for (var j = 0; j < inputs.length; j++) {
                var selectedValue = selects[j].value; // Obtén el valor seleccionado del select

                let id_detalle = inputs_id_detalles[j] ? inputs_id_detalles[j].value :
                    ''; // Obtén el valor del input hidden
                let id = ids[j] ? ids[j].value : ''; // Obtén el valor del input hidden

                // Verifica si la fila con el mismo id ya está en la tabla
                let rowExists = false;
                $('#tbl_EVALUACIONES_ENFERMERIA_EDITAR tbody tr').each(function() {
                    let existingId = $(this).find("input[name='id[]']").val();
                    if (existingId === id) {
                        rowExists = true;
                        return false; // Salir del bucle each
                    }
                });

                // Solo agrega la nueva fila si no existe una fila con el mismo id_detalle
                if (!rowExists) {
                    var newRow = '<tr>';
                    newRow += '<td style="width: 85%;">';
                    newRow += '<input type="hidden" id="id_detalle" name="id_detalle_e[]" value="' + id_detalle + '">';
                    newRow +=
                        '<input type="text" disabled name="descripcion_EVALUACIONES_ENFERMERIA[]" id="descripcion_EVALUACIONES_ENFERMERIA' +
                        j + '" class="form-control input-sm" value="' + inputs[j].value +
                        '" style="height: 28px !important;"/></td>';
                    newRow +=
                        '<td><select class="form-control cantidad_detalle_EVALUACIONES_ENFERMERIA_EDITAR" id="cantidad_detalle_EVALUACIONES_ENFERMERIA' +
                        j +
                        '" name="cantidad_detalle_EVALUACIONES_ENFERMERIA[]" style="height: 28px !important; cursor:pointer; padding: 1px 12px !important; font-weight: 800;">';

                    // Crear las opciones del select con el valor seleccionado
                    for (var k = 0; k < lista_de_value_en_combo_Array.length; k++) {
                        var selected = (lista_de_value_en_combo_Array[k] == selectedValue) ? 'selected' : '';
                        newRow += '<option value="' + lista_de_value_en_combo_Array[k] + '" ' + selected + '>' +
                            lista_de_datos_en_combo_Array[k] + '</option>';
                    }

                    newRow += '</select></td>';
                    newRow +=
                        '<td><button type="button" tabindex="46" class="btn btn-danger boton_eliminar_item_EVALUACIONES_ENFERMERIA btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
                    newRow += '</tr>';

                    $('#tbl_EVALUACIONES_ENFERMERIA_EDITAR tbody').append(newRow);
                }
            }
        }
    }

    function calculo_horas_peso(peso, hora, ao, pi) {
        var peso = parseFloat($('#' + peso).val());
        var hora = parseFloat($('#' + hora).val());
        let resultado = 0;
        if (!isNaN(peso) && !isNaN(hora)) {
            let peso_g = peso * 1000;
            let ml_kg;
            if (peso_g > 2500) {
                // Fórmula para > 2500 gramos
                ml_kg = 0.5;
            } else if(peso_g >= 2000 && peso_g <= 2500) {
                // Fórmula para 2000 - 2500 gramos
                ml_kg = 0.75;
            } else if(peso_g >= 1500 && peso_g < 2000) {
                // Fórmula para 1500 - 2000 gramos
                ml_kg = 1;
            } else if(peso_g >= 1000 && peso_g < 1500) {
                // Fórmula para 1000 - 1500 gramos
                ml_kg = 1.5;
            } else if(peso_g >= 750 && peso_g < 1000) {
                // Fórmula para 750 - 1000 gramos
                ml_kg = 2;
            } else {
                // Fórmula para < 750 gramos
                ml_kg = 2.5;
            }
            resultado = peso * /* 0.5 */ml_kg * hora;
            $('#' + pi).val(Math.round(resultado)); // Mostrar resultado redondeado en #pi
            $('#' + ao).val(Math.round((resultado / 3))); // Mostrar resultado / 3 redondeado en #ao
        } else {
            $('#' + pi).val('');
            $('#' + ao).val('');
        }
    }

    function calculo_horas_peso2(peso, hora, ao, pi) {
        var peso = parseFloat($('#' + peso).val());
        var hora = parseFloat($('#' + hora).val());
        let resultado = 0;
        if (!isNaN(peso) && !isNaN(hora)) {
            resultado = peso * 0.5 * hora;
            $('#' + pi).val(Math.round(resultado)); // Mostrar resultado redondeado en #pi
            $('#' + ao).val(Math.round((resultado / 3))); // Mostrar resultado / 3 redondeado en #ao
        } else {
            $('#' + pi).val('');
            $('#' + ao).val('');
        }
    }

    function lista_table_balanceH(
        $tabla_relacionada,
        $input_descripcion,
        $descripcion_caracter_fisico_id,
        $input_descripcion_detalle,
        $input_id_relacionado,
        $boton_eliminar_item,
        $tipo_balance,
        $valor_tabla,
        $id_caracter_fisico,
        id,
        tabla_tipo,
        secuencia
    ) {
        var ind = $('#' + $tabla_relacionada + ' tbody tr').length;
        if (ind > 0) {
            $("#" + $input_id_relacionado).each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp >= ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
        }

        let camposHidden = '';
        camposHidden += '<input type="hidden" name="es_visible[]" value="1">';
        camposHidden += '<input type="hidden" name="secuencia[]" value="' + secuencia + '">';

        if (id <= 9) {
            camposHidden += '<input type="hidden" name="' + $descripcion_caracter_fisico_id + '[]" value="' + id + '0' +
                ind + '">';
        } else if (id >= 10 && id <= 99) {
            camposHidden += '<input type="hidden" name="' + $descripcion_caracter_fisico_id + '[]" value="' + id + '' +
                ind + '">';
        } else {
            camposHidden += '<input type="hidden" name="' + $descripcion_caracter_fisico_id + '[]" >';
        }

        if ($tipo_balance == 'ingreso') {
            camposHidden += '<input type="hidden" name="id_detalle_ingreso[]" value="0">';
            camposHidden += '<input type="hidden" name="descripcion_ingreso[]">';
        } else {
            camposHidden += '<input type="hidden" name="id_detalle_egreso[]" value="0">';
            camposHidden += '<input type="hidden" name="descripcion_egreso[]">';
        }
        camposHidden += '<input type="hidden" name="' + $id_caracter_fisico + '[]" value="' + id + '">';

        camposHidden += '<input type="hidden" class="form-control ' + $input_id_relacionado +
            ' typeahead tt-query" id="' + $input_id_relacionado + '' + ind + '" name="' + $input_id_relacionado +
            '[]" data-id=' + ind + ' style="height: 28px !important;" required>';

        let newRow = '<tr class="limpiar_al_iniciar">';
        newRow += '<td colspan="2" width="60%">';
        newRow += camposHidden;

        if (tabla_tipo > 0) {
            newRow += '<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">';
            $.ajax({
                url: 'intervencion_terapeuticas_ajax',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let item_valor_combo = '';
                    response.forEach(function(item) {
                        if (tabla_tipo == item.tipo) {
                            item_valor_combo += '<option value=' + item.id + '>' + item
                                .descripcion_intervencion_t + '</option>';
                        }
                    });
                    newRow += '<select class="form-control" name="valor_tabla_ingreso[]">' +
                        item_valor_combo + '</select>';

                    newRow += '</td>';
                    newRow += '<td colspan="2">';
                    newRow += '<input name="' + $input_descripcion_detalle + '[]" id="' +
                        $input_descripcion_detalle + '' + ind +
                        '" type="number" step="1" type="text" class="form-control ' + $tipo_balance +
                        '" required>';
                    newRow += '</td>';
                    newRow += '<td>';
                    newRow += '<button type="button" tabindex="46" class="btn btn-danger ' +
                        $boton_eliminar_item +
                        ' btn-xs" style="margin-left:4px"><i class="fa fa-times"></i></button>';
                    newRow += '</td>';
                    newRow += '</tr>';

                    $('#' + $tabla_relacionada + ' tbody').append(newRow);
                    $('#' + $input_descripcion + id + '' + ind).focus();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        } else {
            newRow += '<input type="hidden" name="' + $valor_tabla + '[]">';
            newRow += '<input type="text" required="" name="' + $input_descripcion + '[]" id="' + $input_descripcion +
                id + '' + ind + '" class="form-control ' + $input_descripcion + id + '">';
            newRow += '</td>';
            newRow += '<td colspan="2">';
            newRow += '<input name="' + $input_descripcion_detalle + '[]" id="' + $input_descripcion_detalle + '' +
                ind + '" type="number" step="1" type="text" class="form-control ' + $tipo_balance + '" required>';
            newRow += '</td>';
            newRow += '<td>';
            newRow += '<button type="button" tabindex="46" class="btn btn-danger ' + $boton_eliminar_item +
                ' btn-xs" style="margin-left:4px"><i class="fa fa-times"></i></button>';
            newRow += '</td>';
            newRow += '</tr>';

            $('#' + $tabla_relacionada + ' tbody').append(newRow);
            $('#' + $input_descripcion + id + '' + ind).focus();
        }
    }

    function actualizarTotalIngreso() {
        let total = 0;
        $('.ingreso').each(function() {
            total += parseFloat($(this).val()) || 0; // Sumar el valor del input, o 0 si está vacío
        });
        $('#total_ingreso').val(total); // Establecer el valor total en el input de total_ingreso
    }
    /**********************EGRESOS**********************/
    function actualizarTotalEgreso() {
        let total = 0;
        $('.egreso').each(function() {
            total += parseFloat($(this).val()) || 0; // Sumar el valor del input, o 0 si está vacío
        });
        $('#total_egreso').val(total); // Establecer el valor total en el input de total_ingreso
    }

    function actualizarTotalIngreso2() {
        let total = 0;
        $('.ingreso').each(function() {
            total += parseFloat($(this).val()) || 0; // Sumar el valor del input, o 0 si está vacío
        });
        $('#total_ingreso2').val(total); // Establecer el valor total en el input de total_ingreso
    }
    /**********************EGRESOS**********************/
    function actualizarTotalEgreso2() {
        let total = 0;
        $('.egreso').each(function() {
            total += parseFloat($(this).val()) || 0; // Sumar el valor del input, o 0 si está vacío
        });
        $('#total_egreso2').val(total); // Establecer el valor total en el input de total_ingreso
    }
</script>
