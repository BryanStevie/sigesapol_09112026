<title>Sistema de Farmacia</title>

<style>
    /*****************/
    .modal-dialog {
        width: 90%;
    }

    #tablemodal {
        border-spacing: 0;
        display: flex;
        /*Se ajuste dinamicamente al tamano del dispositivo**/
        max-height: 80vh;
        /*El alto que necesitemos**/
        overflow-y: auto;
        /**El scroll verticalmente cuando sea necesario*/
        overflow-x: hidden;
        /*Sin scroll horizontal*/
        table-layout: fixed;
        /**Forzamos a que las filas tenga el mismo ancho**/
        width: 98vw;
        /*El ancho que necesitemos*/
        border: 1px solid #c4c0c9;
    }

    #tablemodal thead {
        background-color: #e2e3e5;
        position: fixed !important;
    }


    #tablemodal th {
        border-bottom: 1px solid #c4c0c9;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal th {
        font-weight: normal;
        margin: 0;
        max-width: 9.5vw;
        min-width: 9.5vw;
        word-wrap: break-word;
        font-size: 10px;
        font-weight: bold;
        height: 3.5vh !important;
        line-height: 12px;
        vertical-align: middle;
        /*height:20px;*/
        padding: 4px;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal td {
        font-weight: normal;
        margin: 0;
        max-width: 9.5vw;
        min-width: 9.5vw;
        word-wrap: break-word;
        font-size: 11px;
        height: 3.5vh !important;
        padding: 4px;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal tbody tr:hover td,
    #tablemodal1 tbody tr:hover th {
        /*background-color: red!important;*/
        font-weight: bold;
        /*mix-blend-mode: difference;*/

    }

    #tablemodalm4 {}
</style>

<script type="text/javascript">
    $(document).on('click', '#btnShowDetailEvolEnf', async function() {
        const evolucion_id = $(this).data('id');
        const tipo_atencion_id = $(this).data('tipo_atencion_id');

        const res = await getEvolucionByIdAndTipoAtencionId(tipo_atencion_id, evolucion_id);
        if (res) {
            $('#modalVerEvoluciones').modal('show');
            $('#modalVerEvoluciones input').val('');
            setFormValues(res, tipo_atencion_id);
        }
    });

    $(document).ready(function() {});

    function getEvolucionByIdAndTipoAtencionId(tipoAtencionId, id_evolucion) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: `/getEvolucionDetail/${tipoAtencionId}/${id_evolucion}`,
                dataType: "json",
                beforeSend: function() {
                    Swal.fire({
                        html: '<h5>OBTENIENDO INFORMACIÓN..</h5>',
                        showLoaderOnConfirm: true,
                        onOpen: () => Swal.showLoading()
                    });
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(err) {
                    reject(err)
                },
                complete: function() {
                    Swal.hideLoading();
                    Swal.close();
                }
            });
        });
    }

    function setFormValues(data, tipoAtencionId) {
        const nivel_ipress = $("#nivel_ipress").val();
        if (parseInt(tipoAtencionId) === 2) {
            $("#container_emergencia").show();
            $("#container_hospitalizacion").hide();
            $("#priori_servicio").html("PRIORIDAD");
            $("#nombre_tipo_atencion_registro_ver").val("EMERGENCIA");
            $("#prioridad_registro_ver").val(data.emergencia.prioridad_nombre);
            $("#turno_ver_evol_enf").val(data.evolucion.turno_nombre);
            const fecha_evolucion = data.evolucion.created_at.split(" ")[0];
            const [year, month, day] = fecha_evolucion.split("-");
            const fecha_evolucion_format = `${day}/${month}/${year}`;
            $("#fecha_evol_enf").val(fecha_evolucion_format);
            $("#hora_evol_enf").val(data.evolucion.created_at.split(" ")[1]);
            $("#descripcion_subjetivo_ver").val(data.evolucion.descripcion_subjetivo);
            $("#descripcion_objetivo_ver").val(data.evolucion.descripcion_objetivo);
            $("#descripcion_analisis_ver").val(data.evolucion.descripcion_analisis);
            $("#descripcion_plan_ver").val(data.evolucion.descripcion_plan);
            $("#descripcion_intervenciones_ver").val(data.evolucion.descripcion_intervenciones);
            $("#descripcion_evaluaciones_ver").val(data.evolucion.descripcion_evaluaciones);
            $("#diagnosticos_ver").empty();
            var cuerpo_DIAGNOSTICOS_PRESTACION = '';
            if (data.diagnosticos_por_prestacion && data.diagnosticos_por_prestacion.length > 0) {
                $.each(data.diagnosticos_por_prestacion, function(key, value) {
                    cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION +
                        '<tr>' +
                        '<td>' + value.codigo + '</td>' +
                        '<td>' + value.nombre + '</td>' +
                        '<td>' + value.tipo + '</td>' +
                        '<td>' + value.clasificacion + '</td>' +
                        '</tr>';
                });
            }
            $("#diagnosticos_ver").html(cuerpo_DIAGNOSTICOS_PRESTACION);
            var cuerpo_CPMS_ENFERMERIA = '';
            if (data.nota_enfermeria_cpms && data.nota_enfermeria_cpms.length > 0) {
                $.each(data.nota_enfermeria_cpms, function(key, value) {
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
                                .codigo + " - " + value2
                                .nombre + '</option>';
                        });
                    }
                    cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                        '<tr >' +
                        ' <td style="width: 40%;"><input type="text" class="form-control input-sm" ' +
                        ' style="height: 28px !important;" name="procedimiento_descripcion[]" id="procedimiento_descripcion' +
                        key + '" value="' + value.codigo + ' - ' + value.nombre_procedimiento +
                        '" disabled> ' +
                        ' </td>' +
                        '<td style="width: 10%;">' +
                        '<input name="procedimiento_cantidad[]" id="procedimiento_cantidad' +
                        key + '" value="' + value.cantidad +
                        '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;" disabled>' +
                        '</td>';
                    if (nivel_ipress == 2) {
                        cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                            '<td style="width: 10%;">' +
                            '<input name="procedimiento_tiempo[]" id="procedimiento_tiempo' +
                            key +
                            '" value="' + (value.procedimiento_tiempo ?? 'No registrado') +
                            '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;" disabled>' +
                            '</td>';
                    }
                    cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                        `<td style="width: ${nivel_ipress == 2 ? '40' : '50'}%;">` +
                        ' <select style="height: 28px !important;" class="form-control cpms_listado_ver" disabled>' +
                        lista_item_combo + '</select></td>' +
                        '</tr>';
                });
            } else if (data.cpms && data.cpms.length > 0) {
                $.each(data.cpms, function(key, value) {
                    cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                        '<tr >' +
                        ' <td style="width: 40%;"><input type="text" class="form-control input-sm" ' +
                        ' style="height: 28px !important;" name="procedimiento_descripcion[]" id="procedimiento_descripcion' +
                        key + '" value="' + value.codigo_procedimiento + ' - ' + value
                        .nombre_procedimiento + '" disabled> ' +
                        ' </td>' +
                        '<td style="width: 10%;">' +
                        '<input name="procedimiento_cantidad[]" id="procedimiento_cantidad' +
                        key + '" value="' + value.cantidad +
                        '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;" disabled>' +
                        '</td>';
                    if (nivel_ipress == 2) {
                        cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                            '<td style="width: 10%;">' +
                            '<input name="procedimiento_tiempo[]" id="procedimiento_tiempo' +
                            key +
                            '" value="' + (value.procedimiento_tiempo ?? 'No registrado') +
                            '" type="text" class="form-control" style="height: 28px !important;font-weight: 40;" disabled>' +
                            '</td>';
                    }
                    cuerpo_CPMS_ENFERMERIA = cuerpo_CPMS_ENFERMERIA +
                        `<td style="width: ${nivel_ipress == 2 ? '40' : '50'}%;">` +
                        ' <input type="text" class="form-control" disabled style="height: 28px !important;" value="' +
                        value.codigo_diagnostico + " - " +
                        value.nombre_diagnostico + '"></td>' +
                        '</tr>';
                });
            }
            $("#cuerpo_CPMS_ENFERMERIA_VER").html(cuerpo_CPMS_ENFERMERIA);
            pintar_puntos_by_evolucion(data.evolucion.id_enfermeria, "control_div_puntos_evolucion_ver",
                "control_div_puntos_evolucion_leyenda_ver", 'REGISTRO', tipoAtencionId);
        }
        if (parseInt(tipoAtencionId) === 3) {
            $("#container_emergencia").hide();
            $("#container_hospitalizacion").show();
            $("#priori_servicio").html("SERVICIO");
            $("#nombre_tipo_atencion_registro_ver").val("HOSPITALIZACION");
            $("#prioridad_registro_ver").val(data.hospitalizacion.servicio_nombre);
            $("#turno_ver_evol_enf").val(data.evolucion.turno_nombre);
            const fecha_evolucion = data.evolucion.created_at.split(" ")[0];
            const [year, month, day] = fecha_evolucion.split("-");
            const fecha_evolucion_format = `${day}/${month}/${year}`;
            $("#fecha_evol_enf").val(fecha_evolucion_format);
            $("#hora_evol_enf").val(data.evolucion.created_at.split(" ")[1]);
            $("#descripcion_subjetivo_ver_h").val(data.evolucion.descripcion_subjetivo);
            $("#descripcion_objetivo_ver_h").val(data.evolucion.descripcion_objetivo);
            $("#descripcion_intervenciones_ver_h").val(data.evolucion.descripcion_intervenciones);
            $("#descripcion_evaluaciones_ver_h").val(data.evolucion.descripcion_evaluaciones);
            var cuerpo_INVASIVOS_ATENCION = '';
            $("#historial_invasivos_ver").empty();
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
            $('#historial_invasivos_ver').append(cuerpo_INVASIVOS_ATENCION);
            if (data.invasivos_por_atencion.nextPageUrl) {
                $('#load-more-container-ver').html(
                    '<button id="load-more-button-ver" class="btn enf_btn-celeste" data-next-page-url-ver="' + (data
                        .invasivos_por_atencion.current_page + 1) + '">Cargar más </button>');
            } else {
                $('#load-more-container-ver').html('');
            }
            //====================================================================================================================//
            var cuerpo_DIAGNOSTICO_ENFERMERIA = '';
            $("#cuerpo_DIAGNOSTICO_ENFERMERIA_VER").empty();
            if (data.nota_enfermeria_diagnostico && data.nota_enfermeria_diagnostico.length > 0) {
                $.each(data.nota_enfermeria_diagnostico, function(key, value) {
                    cuerpo_DIAGNOSTICO_ENFERMERIA = cuerpo_DIAGNOSTICO_ENFERMERIA +
                        '<tr >' +
                        ' <td style="width: auto;"><input type="text" class="form-control  input-sm" style="height: 28px !important;" ' +
                        '   value="' + value.descrip_abrev + '"  disabled>' +
                        ' </td>' +
                        '</tr>';
                });
            }
            $("#cuerpo_DIAGNOSTICO_ENFERMERIA_VER").html(cuerpo_DIAGNOSTICO_ENFERMERIA);
            //====================================================================================================================//

            var cuerpo_PLAN_CUIDADO_ENFERMERIA = '';
            $("#cuerpo_PLAN_CUIDADO_ENFERMERIA_VER").empty();
            if (data.nota_enfermeria_plan_cuidado && data.nota_enfermeria_plan_cuidado.length > 0) {
                $.each(data.nota_enfermeria_plan_cuidado, function(key, value) {
                    cuerpo_PLAN_CUIDADO_ENFERMERIA = cuerpo_PLAN_CUIDADO_ENFERMERIA +
                        '<tr >' +
                        ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;" ' +
                        '   value="' + value.descrip_abrev + '"  disabled>' +
                        ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important; text-align: center;" ' +
                        '   value="' + value.valor_de_seleccion + '"  disabled>' +
                        ' </td>' +
                        '</tr>';
                });
            }
            $("#cuerpo_PLAN_CUIDADO_ENFERMERIA_VER").html(cuerpo_PLAN_CUIDADO_ENFERMERIA);
            //====================================================================================================================//

            var cuerpo_INTERVENCIONES_ENFERMERIA = '';
            $("#cuerpo_INTERVENCIONES_ENFERMERIA_VER").empty();
            if (data.nota_enfermeria_intervenciones && data.nota_enfermeria_intervenciones.length > 0) {
                $.each(data.nota_enfermeria_intervenciones, function(key, value) {
                    cuerpo_INTERVENCIONES_ENFERMERIA = cuerpo_INTERVENCIONES_ENFERMERIA +
                        '<tr >' +
                        ' <td style="width: auto;"><input type="text" class="form-control  input-sm" style="height: 28px !important;" ' +
                        '   value="' + value.descrip_abrev + '"  disabled>' +
                        ' </td>' +
                        '</tr>';
                });
            }
            $("#cuerpo_INTERVENCIONES_ENFERMERIA_VER").html(cuerpo_INTERVENCIONES_ENFERMERIA);
            //====================================================================================================================//

            var cuerpo_EVALUACIONES_ENFERMERIA = '';
            $("#cuerpo_EVALUACIONES_ENFERMERIA_VER").empty();
            if (data.nota_enfermeria_evaluaciones && data.nota_enfermeria_evaluaciones.length > 0) {
                $.each(data.nota_enfermeria_evaluaciones, function(key, value) {
                    cuerpo_EVALUACIONES_ENFERMERIA = cuerpo_EVALUACIONES_ENFERMERIA +
                        '<tr >' +
                        ' <td style="width: 87%;"><input type="text" class="form-control  input-sm" style="height: 28px !important;" ' +
                        '   value="' + value.descrip_abrev + '"  disabled>' +
                        ' <td style="width: 13%;"><input type="text" class="form-control  input-sm" style="height: 28px !important; text-align: center;" ' +
                        '   value="' + value.valor_de_seleccion + '"  disabled>' +
                        ' </td>' +
                        '</tr>';
                });
            }
            $("#cuerpo_EVALUACIONES_ENFERMERIA_VER").html(cuerpo_EVALUACIONES_ENFERMERIA);
            //====================================================================================================================//
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

            // Generar las opciones del select como una cadena HTML
            let item_valor_combo = lista_Array.map(item =>
                `<option value="${item.value}">${item.text}</option>`
            ).join('');

            // Inicializar la variable para almacenar el cuerpo de la tabla
            let cuerpo_invasivos = '';
            $("#lista_invasivos_ver").empty();
            // Recorrer el array de invasivos y generar las filas de la tabla
            data.invasivos_por_evolucion.forEach((value, key) => {
                let options = lista_Array.map(item =>
                    `<option value="${item.value}" ${item.value == value.accion ? 'selected' : ''}>${item.text}</option>`
                ).join('');
                cuerpo_invasivos += `
              <tr>
                <td>
                  ${value.id}
                </td>
                <td>
                  ${value.tipo}
                </td>
                <td>
                  <input class="form-control" type="date" name="invasivo_fecha" value="${value.fecha ? value.fecha : ''}" disabled>
                </td>
                <td>
                  <input class="form-control" type="text" name="invasivo_lugar" value="${value.lugar == null ? '' : value.lugar}" disabled>
                </td>
                <td>
                  <select class="form-control" id="invasivo_accion${key}" name="invasivo_accion[]" style="height: 28px !important; cursor: pointer; font-size: 13px; padding: 1px 12px !important;" disabled>
                    ${options}
                  </select>
                </td>
              </tr>
            `;
            });

            // Añadir las filas generadas a la tabla
            $('#lista_invasivos_ver').append(cuerpo_invasivos);
            pintar_puntos_by_evolucion(data.evolucion.id_enfermeria, "control_div_puntos_evolucion_ver_h",
                "control_div_puntos_evolucion_leyenda_ver_h", 'REGISTRO', 3);
        }
    }

    // Maneja el clic en el botón "Cargar más"
    $(document).on('click', '#load-more-button-ver', function() {
        var id_enfermeria_ver = $('#id_enfermeria_ver').val();
        var nextPageUrlVer = $(this).data('next-page-url-ver');
        console.log(nextPageUrlVer);
        var $button = $(this);
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
        var var_url = '{{ url('cargar_mas_historiaL_invasivos') }}' + '?page=' + nextPageUrlVer;

        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_enfermeria: id_enfermeria_ver,
                page: nextPageUrlVer // Pasar el número de página actual
            },
            success: function(data) {
                $button.prop('disabled', false).html(
                    'Cargar más <i class="fa fa-chevron-down"></i>');
                procesarRespuesta_ver(data, false); // false indica que es una carga adicional
            }
        });
        return false;

    });

    function procesarRespuesta_ver(data, esCargaInicial) {
        var cuerpo_INVASIVOS_ATENCION_VER = '';
        if (data.invasivos_por_atencion.items && data.invasivos_por_atencion.items.length > 0) {
            $.each(data.invasivos_por_atencion.items, function(index, item) {
                cuerpo_INVASIVOS_ATENCION_VER +=
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
            $('#historial_invasivos_ver').html(cuerpo_INVASIVOS_ATENCION_VER);
        } else {
            // Para cargas adicionales, agregamos al contenido existente
            $('#historial_invasivos_ver').append(cuerpo_INVASIVOS_ATENCION_VER);
        }

        // Manejo del botón "Cargar más"
        if (data.invasivos_por_atencion.nextPageUrl) {
            $('#load-more-container-ver').html(
                '<button id="load-more-button-ver" class="btn enf_btn-celeste" data-next-page-url-ver="' + (data
                    .invasivos_por_atencion.current_page + 1) + '">Cargar más </button>');
        } else {
            $('#load-more-container-ver').html('');
        }

    }

    function pintar_puntos_by_evolucion_hospi($id_enfermeria, $control_div_puntos, $control_div_puntos_leyenda,
        $tipo_uso, $id_tipo_atenciones) {
        console.log('first')
        var arrayTextos = [];
        $.ajax({
            url: '{{ url('pintar_puntos_evolucion/') }}/' + $id_enfermeria + '/' + $id_tipo_atenciones,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#" + $control_div_puntos).empty();

                if ($control_div_puntos_leyenda != '') {
                    $("#" + $control_div_puntos_leyenda).empty();
                }
                let descripcion_proceso = '';
                $.each(data, function(key, valu) {
                    var created_at = new Date(valu.created_at);
                    var year = created_at.getFullYear();
                    var month = created_at.getMonth() +
                        1; // Sumamos 1 porque getMonth() devuelve un valor entre 0 y 11
                    var day = created_at.getDate();
                    var hours = created_at.getHours();
                    var minutes = created_at.getMinutes();
                    var seconds = created_at.getSeconds();
                    if (valu.tipo_registro == 'NOTA_ENFERMERIA') {
                        if (valu.descripcion_proceso.replace(/\s+/g, '') == 'OTROS') {
                            descripcion_proceso = valu.descripcion_otros;
                        } else {
                            descripcion_proceso = valu.descripcion_proceso;
                        }
                    } else {
                        descripcion_proceso = valu.descripcion_proceso;
                    }

                    var fecha = day + '/' + month + '/' + year;
                    var hora = hours + ':' + minutes + ':' + seconds;
                    var campoMostrar = 'Fecha: ' + fecha + ' - Hora: ' + hora + ' - Proceso: ' +
                        descripcion_proceso;

                    var valor = ' <a data-id="' + valu.id + '" data-id_enfermeria_delete="' +
                        $id_enfermeria + '" data-toggle="tooltip" data-placement="top" '
                    valor = valor + ' title="' + campoMostrar +
                        '" href="javascript:void(0)"  data-id_tipo_atenciones="' +
                        $id_tipo_atenciones + '" '
                    if ($tipo_uso == 'REGISTRO') {
                        valor = valor +
                            ' class="eliminarPuntoSeleccionado" data-control_div_puntos="' +
                            $control_div_puntos + '" '
                    } else {
                        valor = valor + ' data-control_div_puntos="' + $control_div_puntos + '" '
                    }
                    valor = valor + ' data-control_div_puntos_leyenda="' +
                        $control_div_puntos_leyenda + '" '
                    valor = valor + ' style="position:absolute;left:' + valu.x + 'px;top:' + valu
                        .y + 'px; '
                    valor = valor + ' width:' + valu.tama + 'px;height:' + valu.tama + 'px; '
                    valor = valor + ' background-color:' + valu.color + ';border-radius:50%; '
                    valor = valor + ' border: 1px solid #000000;"> '
                    if (valu.tama == 10) {
                        valor = valor +
                            ' <b style="color: #000;font-size:15px;padding:10px!important;">' +
                            descripcion_proceso + '</b></a > ';
                    } else if (valu.tama == 40) {
                        valor = valor +
                            ' <b style="color: #000;font-size:15px;padding-left:35px!important;">' +
                            descripcion_proceso + '</b></a > ';
                    } else if (valu.tama == 60) {
                        valor = valor +
                            ' <b style="color: #000;font-size:15px;padding-left:55px!important;">' +
                            descripcion_proceso + '</b></a > ';
                    }
                    $("#" + $control_div_puntos).append(valor);

                    var textos = valu.descripcion_proceso + '-' + valu.color;
                    var textosSeparados = textos.split(",");
                    textosSeparados.forEach(function(texto) {
                        if (arrayTextos.indexOf(texto) === -1) {
                            arrayTextos.push(texto);
                        }
                    });

                    arrayTextos.sort();
                });

                if ($control_div_puntos_leyenda != '') {
                    for (var i = 0; i < arrayTextos.length; i++) {
                        var texto = arrayTextos[i].split('-');
                        var valor_leyenda = '<li><span class="icono" style="background-color: ' + texto[1] +
                            ';"></span>' + texto[0] + '</li>';
                        $("#" + $control_div_puntos_leyenda).append(valor_leyenda);
                    }
                }

            }
        })
    };

    function pintar_puntos_by_evolucion($id_enfermeria, $control_div_puntos, $control_div_puntos_leyenda, $tipo_uso,
        $id_tipo_atenciones) {
        var arrayTextos = [];
        $.ajax({
            url: '{{ url('pintar_puntos_evolucion/') }}/' + $id_enfermeria + '/' + $id_tipo_atenciones,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#" + $control_div_puntos).empty();

                if ($control_div_puntos_leyenda != '') {
                    $("#" + $control_div_puntos_leyenda).empty();
                }

                $.each(data, function(key, valu) {
                    var created_at = new Date(valu.created_at);
                    var year = created_at.getFullYear();
                    var month = created_at.getMonth() +
                        1; // Sumamos 1 porque getMonth() devuelve un valor entre 0 y 11
                    var day = created_at.getDate();
                    var hours = created_at.getHours();
                    var minutes = created_at.getMinutes();
                    var seconds = created_at.getSeconds();

                    var fecha = day + '/' + month + '/' + year;
                    var hora = hours + ':' + minutes + ':' + seconds;
                    var campoMostrar = 'Fecha: ' + fecha + ' - Hora: ' + hora + ' - Proceso: ' +
                        valu.descripcion_proceso;

                    var valor = ' <a data-id="' + valu.id + '" data-id_enfermeria_delete="' +
                        $id_enfermeria + '" data-toggle="tooltip" data-placement="top" '
                    valor = valor + ' title="' + campoMostrar +
                        '" href="javascript:void(0)"  data-id_tipo_atenciones="' +
                        $id_tipo_atenciones + '" '
                    if ($tipo_uso == 'REGISTRO') {
                        valor = valor +
                            ' class="eliminarPuntoSeleccionado" data-control_div_puntos="' +
                            $control_div_puntos + '" '
                    } else {
                        valor = valor + ' data-control_div_puntos="' + $control_div_puntos + '" '
                    }
                    valor = valor + ' data-control_div_puntos_leyenda="' +
                        $control_div_puntos_leyenda + '" '
                    valor = valor + ' style="position:absolute;left:' + valu.x + 'px;top:' + valu
                        .y + 'px; '
                    valor = valor + ' width:' + valu.tama + 'px;height:' + valu.tama + 'px; '
                    valor = valor + ' background-color:' + valu.color + ';border-radius:50%; '
                    valor = valor + ' border: 1px solid #000000;"> '
                    if (valu.tama == 10) {
                        valor = valor +
                            ' <b style="color: #000;font-size:15px;padding:10px!important;">' + valu
                            .descripcion_proceso + '</b></a > ';
                    } else if (valu.tama == 40) {
                        valor = valor +
                            ' <b style="color: #000;font-size:15px;padding-left:35px!important;">' +
                            valu.descripcion_proceso + '</b></a > ';
                    } else if (valu.tama == 60) {
                        valor = valor +
                            ' <b style="color: #000;font-size:15px;padding-left:55px!important;">' +
                            valu.descripcion_proceso + '</b></a > ';
                    }
                    $("#" + $control_div_puntos).append(valor);

                    var textos = valu.descripcion_proceso + '-' + valu.color;
                    var textosSeparados = textos.split(",");
                    textosSeparados.forEach(function(texto) {
                        if (arrayTextos.indexOf(texto) === -1) {
                            arrayTextos.push(texto);
                        }
                    });

                    arrayTextos.sort();
                });

                if ($control_div_puntos_leyenda != '') {
                    for (var i = 0; i < arrayTextos.length; i++) {
                        var texto = arrayTextos[i].split('-');
                        var valor_leyenda = '<li><span class="icono" style="background-color: ' + texto[1] +
                            ';"></span>' + texto[0] + '</li>';
                        $("#" + $control_div_puntos_leyenda).append(valor_leyenda);
                    }
                }

            }
        })
    };

    function buildRowsTable(diagnosticos) {
        if ($.fn.DataTable.isDataTable('#table-diagnostic-history')) {
            $('#table-diagnostic-history').DataTable().destroy();
        }
        $('#table-diagnostic-history').DataTable({
            destroy: true,
            data: diagnosticos,
            searching: false,
            paging: false,
            info: false,
            ordering: false,
            columns: [{
                    data: "codigo_diagnostico"
                },
                {
                    data: "descripcion_diagnostico"
                },
                {
                    data: "descripcion_tipo_diagnosticos"
                },
                {
                    data: "id_evaluacion",
                    render: function(data) {
                        return parseInt(data) == 0 ? "ATENCIÓN" : "EVALUACIÓN"
                    }
                }
            ]
        });
    }
</script>


<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="col-md-12">
                <div class="box box-primary">
                    <br>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tablemodalm6"
                                style="font-size: 11px; margin: 0;">
                                <thead>
                                    <tr>
                                        <th>FECHA Y HORA</th>
                                        <th>RESPONSABLE</th>
                                        <th>DETALLE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($evoluciones) && count($evoluciones) > 0)
                                        @foreach ($evoluciones as $r)
                                            <tr>
                                                <td style="vertical-align: middle;"><b>FECHA:</b>
                                                    {{ $r->fecha_evol }}
                                                    <br><b>HORA:</b> {{ $r->hora_evol }}
                                                    <br><b>TURNO:</b> {{ $r->descripcion_turno }}
                                                </td>
                                                <td>{{ $r->name . ' ' . $r->paterno . ' ' . $r->materno }}</td>
                                                <td style="text-align: center;">
                                                    <button type="button" class="btn btn-success btn-xs"
                                                        data-toggle="tooltip" data-placement="top" title="Ver Detalle"
                                                        id="btnShowDetailEvolEnf" data-id="{{ $r->evolucion_id }}"
                                                        data-tipo_atencion_id="{{ $r->tipo_atencion_id }}"><i
                                                            class="fa fa-eye" aria-hidden="true"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" style="text-align: center;">No hay registros.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{-- Renderizar paginación --}}
                            <div class="mt-3">
                                {!! $evoluciones->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- <div class="modal fade" id="modalDetailEvoluciones" tabindex="-1" role="dialog"
        aria-labelledby="modalDetailEvolucionesLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    <h5 class="modal-title" id="exampleModalLabel">DETALLE EVOLUCIONES</h5>
                </div>
                <div class="modal-body" style="font-size: 11px">
                    <div class="container-fluid">
                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center">
                                    <b>DATOS DE LA ATENCION</b>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row form-group">
                                    <div class="col-md-2">
                                        <label for="tipo_atencion_h">TIPO DE LA ATENCION</label>
                                        <input class="form-control" id="tipo_atencion_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="fecha_atencion_h">FECHA DE LA ATENCION</label>
                                        <input class="form-control" id="fecha_atencion_h" readonly>
                                    </div>
                                    <div class="col-md-2" id="hora_atencion_h_div">
                                        <label for="hora_atencion_h">HORA DE LA ATENCION</label>
                                        <input class="form-control" id="hora_atencion_h" readonly>
                                    </div>
                                    <div class="col-md-3" id="prioridad_h_div">
                                        <label for="prioridad_h">PRIORIDAD</label>
                                        <input class="form-control" id="prioridad_h" readonly>
                                    </div>
                                    <div class="col-md-3" id="area_h_div">
                                        <label for="area_h">AREA</label>
                                        <input class="form-control" id="area_h" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-2" id="cama_h_div">
                                        <label for="medico_nombre">CAMA</label>
                                        <input class="form-control" id="cama_h" readonly>
                                    </div>
                                    <div class="col-md-2" id="nro_orden_h_div">
                                        <label for="medico_nombre">N° ORDEN</label>
                                        <input class="form-control" id="nro_orden_h" readonly>
                                    </div>
                                    <div class="col-md-2" id="nro_historia_clinica_h_div">
                                        <label for="medico_nombre">N° HISTORIA CLINICA</label>
                                        <input class="form-control" id="nro_historia_clinica_h" readonly>
                                    </div>
                                    <div class="col-md-2" id="codigo_ups_h_div">
                                        <label for="medico_nombre">CODIGO UPS</label>
                                        <input class="form-control" id="codigo_ups_h" readonly>
                                    </div>
                                    <div class="col-md-3" id="ipress_h_div">
                                        <label for="medico_nombre">NOMBRE DE LA IPRESS</label>
                                        <input class="form-control" id="ipress_h" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center">
                                    <b>INFORMACIÓN DEL PACIENTE</b>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row form-group">
                                    <div class="col-md-2">
                                        <label for="dni_beneficiario_h">DOCUMENTO DE IDENTIDAD</label>
                                        <input class="form-control" id="dni_beneficiario_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tipo_doc_medico_h">APELLIDO PATERNO</label>
                                        <input class="form-control" id="paterno_beneficiario_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_materno">APELLIDO MATERNO</label>
                                        <input class="form-control" id="materno_beneficiario_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">NOMBRES</label>
                                        <input class="form-control" id="nombre_beneficiario_h" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="medico_nombre">BENEFICIARIO</label>
                                        <input class="form-control" id="tipo_beneficiario_h" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-2">
                                        <label for="medico_nombre">F. DE NACIMIENTO</label>
                                        <input class="form-control" id="fecha_nac_paciente_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">CIP</label>
                                        <input class="form-control" id="cip_beneficiario_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">GRADO</label>
                                        <input class="form-control" id="grado_beneficiario_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">SITUACION</label>
                                        <input class="form-control" id="situacion_paciente_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">SEXO</label>
                                        <input class="form-control" id="sexo_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">T. SANGRE</label>
                                        <input class="form-control" id="sangre_beneficiario_h" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-2">
                                        <label for="medico_nombre">EDAD</label>
                                        <input class="form-control" id="edad_beneficiario_h" readonly>
                                    </div>
                                </div>
                                <div class="row form-group" id="div_acompaniea" style="display: none">
                                    <div class="col-md-12" style="margin-bottom: 10px;"><label
                                            for="tipo_doc_medico">DATOS DEL ACOMPAÑANTE</label></div>
                                    <div class="col-md-12">
                                        <hr style="border-top: 2px solid rgb(75, 68, 68); margin-top: 0;">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">CONDUCIDO POR</label>
                                        <input class="form-control" id="conducio_por" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                                        <input class="form-control" id="dni_conducido" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">TELEFONO</label>
                                        <input class="form-control" id="telf_conducido" readonly>
                                    </div>
                                </div>
                                <div class="row form-group" style="display: none">
                                    <div class="col-md-10">
                                        <label for="medico_nombre">DESCRIPCION</label>
                                        <input class="form-control" id="observaciones_benefi" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center">
                                    <b>INFORMACIÓN DEL RESPONSABLE DE ENFERMERIA</b>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row form-group">
                                    <div class="col-md-2">
                                        <label for="tipo_doc_medico">DOCUMENTO DE IDENTIDAD</label>
                                        <input class="form-control" id="profesional_dni_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                                        <input class="form-control" id="profesional_paterno_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_materno">APELLIDO MATERNO</label>
                                        <input class="form-control" id="profesional_materno_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medico_nombre">NOMBRES</label>
                                        <input class="form-control" id="profesional_nombre_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="especialidad_medico">ESPECIALIDAD</label>
                                        <input class="form-control" id="especialidad_medico_h" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-2">
                                        <label for="profesion_medico">PROFESION</label>
                                        <input class="form-control" id="profesion_medico_h" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="colegiatura">N° COLEGIATURA</label>
                                        <input class="form-control" id="colegiatura_h" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="modalVerEvoluciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" style="width: 1350px;" role="document">
            <div class="modal-content">

                <div class="modal-header" style="font-size: 20px !important;">
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                    <b id="tituloFormularioEvoluciones-ver">DETALLE DE EVOLUCION</b>
                </div>

                <div class="modal-body">
                    <form id="FormularioEvolucionesVer" name="FormularioEvolucionesVer" enctype="multipart/form-data"
                        method="POST">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="id_evolucion">
                        <input type="hidden" id="argumentos_evolucion" />
                        <input type="hidden" id="doc_firmar_evolucion" />
                        <input type="hidden" name="flag_firma" id="flag_firma_evolucion">
                        <div class="nav-tabs-custom">
                            <div class="tab-content">
                                <div class="tab-pane active" style="font-size: 11px;">
                                    <div class="panel panel-info panel-bordered panel-sm">
                                        <div class="panel-heading">
                                            <div class="panel-title text-center"><br>
                                                <b>DATOS DE LA ATENCION</b>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table" id="cpms_listado_ver" cellspacing="0" width="100%"
                                                style="margin-top:5px;">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">TIPO DE ATENCIÓN</th>
                                                        <th width="20%">FECHA DE ATENCIÓN</th>
                                                        <th width="15%">HORA DE ATENCIÓN</th>
                                                        <th width="30%" id="priori_servicio"></th>
                                                        <th width="25%">TURNO</th>
                                                    </tr>
                                                </thead>
                                                <tr>
                                                    <td width="20%">
                                                        <input type="text" class="form-control datepicker"
                                                            style="height: 28px !important;"
                                                            id="nombre_tipo_atencion_registro_ver" disabled>
                                                    </td>
                                                    <td width="20%">
                                                        <input type="text" class="form-control datepicker"
                                                            style="height: 28px !important;" autocomplete="off"
                                                            name="fecha_evol_enf" id="fecha_evol_enf" disabled>
                                                    </td>
                                                    <td width="15%">
                                                        <input type="text" class="form-control datepicker"
                                                            style="height: 28px !important;" autocomplete="off"
                                                            name="hora_evol_enf" id="hora_evol_enf" disabled>
                                                    </td>
                                                    <td width="30%">
                                                        <input type="text" class="form-control datepicker"
                                                            style="height: 28px !important;"
                                                            id="prioridad_registro_ver" disabled>
                                                    </td>
                                                    <td width="25%">
                                                        <input type="text" class="form-control datepicker"
                                                            style="height: 28px !important;" id="turno_ver_evol_enf"
                                                            disabled>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group" id="container_emergencia">
                                        <div class="panel with-nav-tabs panel-default"
                                            style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab0defaultEvoluciones-ver"
                                                            data-toggle="tab" style="font-size:12px"
                                                            aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o"></i>SOAPIE
                                                            </b></a></li>
                                                    <li><a href="#tab2defaultEvoluciones-ver"
                                                            id="tabEvolucionesCPMS_ver" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o"></i>
                                                                CPMS/CPT</b></a></li>
                                                    <li><a href="#tab3defaultEvoluciones-ver" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o"></i>
                                                                INVASIVOS</b></a></li>
                                                </ul>
                                            </div>

                                            <div class="panel-body">
                                                <div class="tab-content">

                                                    <div class="tab-pane fade active in"
                                                        id="tab0defaultEvoluciones-ver">
                                                        <div class="form-group"
                                                            style="margin-bottom: 0px !important;">
                                                            <div class="col-md-12">
                                                                <label for="tipo">S</label>
                                                                <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                    id="descripcion_subjetivo_ver" name="descripcion_subjetivo" disabled></textarea>
                                                            </div>

                                                            <div class="col-md-12"> <br>
                                                                <label for="tipo">O</label>
                                                                <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                    id="descripcion_objetivo_ver" name="descripcion_objetivo" disabled></textarea>
                                                            </div>

                                                            <div class="col-md-12"> <br>
                                                                <label for="tipo">A</label>
                                                                <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                    id="descripcion_analisis_ver" name="descripcion_analisis" disabled></textarea>
                                                            </div>

                                                            <div class="col-md-12"> <br>
                                                                <label for="tipo">P</label>
                                                                <textarea class="form-control" rows="2" required style="text-transform:uppercase" id="descripcion_plan_ver"
                                                                    name="descripcion_plan" disabled></textarea>
                                                            </div>

                                                            <div class="col-md-12"> <br>
                                                                <label for="tipo">I</label>
                                                                <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                    id="descripcion_intervenciones_ver" name="descripcion_intervenciones" disabled></textarea>
                                                            </div>

                                                            <div class="col-md-12"> <br>
                                                                <label for="tipo">E</label>
                                                                <textarea class="form-control" rows="2" required style="text-transform:uppercase"
                                                                    id="descripcion_evaluaciones_ver" name="descripcion_evaluaciones" disabled></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab2defaultEvoluciones-ver">
                                                        <fieldset>
                                                            <div class="panel panel-info panel-bordered panel-sm">
                                                                <div class="panel-heading">
                                                                    <div class="panel-title text-center"><br>
                                                                        <b>DIAGNOSTICO DE LA ATENCION</b>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <table class="table table-bordered"
                                                                        id="tblDiagnosticos" cellspacing="0"
                                                                        width="100%" style="margin-top:5px;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="15%">Codigo</th>
                                                                                <th width="50%">Nombre</th>
                                                                                <th width="15%">Tipo</th>
                                                                                <th width="15%">Clasificación</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tfoot>
                                                                        </tfoot>
                                                                        <tbody id="diagnosticos_ver">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <table id="tbl_CPMS_ENFERMERIA_VER"
                                                                                style="margin-bottom: 10px !important;width: 100%;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th width="40%">
                                                                                            Procedimiento
                                                                                        </th>
                                                                                        <th width="10%">Cantidad
                                                                                        </th>
                                                                                        <th width="10%"
                                                                                            id="tiempo_cpms_det">Tiempo
                                                                                            (min)
                                                                                        </th>
                                                                                        <th width="40%">Diagnosticos
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="cuerpo_CPMS_ENFERMERIA_VER"
                                                                                    name="cuerpo_CPMS_ENFERMERIA_VER"
                                                                                    class="cuerpo_CPMS_ENFERMERIA_VER">
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab3defaultEvoluciones-ver">
                                                        <fieldset>
                                                            <form id="FormularioPunto" name="FormularioPunto"
                                                                enctype="multipart/form-data" method="GET">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="col-md-12">
                                                                            <hr>
                                                                        </div>
                                                                        <input type="hidden" name="img_puntos_base"
                                                                            id="img_puntos_base">
                                                                        <div class="col-md-10"
                                                                            style="padding-left: 0px !important;  padding-right: 0px !important;cursor: pointer;">
                                                                            <div class="col-md-12"
                                                                                style="cursor: pointer;"
                                                                                id="celda1" name="celda1">
                                                                                <div class="form-group">
                                                                                    <div class="grid-block"
                                                                                        style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                                                                        <canvas id="plano_evolucion"
                                                                                            name="plano"
                                                                                            width="512"
                                                                                            height="512"></canvas>
                                                                                        <input type="hidden"
                                                                                            id="_token"
                                                                                            name="_token"
                                                                                            value="{{ csrf_token() }}">
                                                                                        <input type="hidden"
                                                                                            name="x"
                                                                                            id="x">
                                                                                        <input type="hidden"
                                                                                            name="y"
                                                                                            id="y">
                                                                                        <div id="control_div_puntos_evolucion_ver"
                                                                                            name="control_div_puntos_evolucion_ver">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <ul class="leyenda_grafico"
                                                                                id="control_div_puntos_evolucion_leyenda_ver"
                                                                                name="control_div_puntos_evolucion_leyenda_ver">
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </form>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="container_hospitalizacion">
                                        <div class="panel with-nav-tabs panel-default"
                                            style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab0defaultEvoluciones-ver_h"
                                                            data-toggle="tab" style="font-size:12px"
                                                            aria-expanded="true"><b><i class="fa fa-file-text-o"></i>
                                                                VALORACIÓN DE ENFERMERÍA </b></a></li>
                                                    <li><a href="#tab1defaultEvoluciones-ver" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o"></i>
                                                                ATENCIÓN DE ENFERMERÍA</b></a></li>
                                                    <li><a href="#tab3defaultEvoluciones-ver_h" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o"></i>
                                                                INVASIVOS</b></a></li>
                                                    <li><a href="#tab4defaultEvoluciones-ver" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o"></i>
                                                                HISTORIAL DE INVASIVOS</b></a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="panel-body">
                                                <div class="tab-content">

                                                    <div class="tab-pane fade active in"
                                                        id="tab0defaultEvoluciones-ver_h">
                                                        <div class="form-group"
                                                            style="margin-bottom: 0px !important;">
                                                            <div class="col-md-12">
                                                                <label for="tipo">SUBJETIVO</label>
                                                                <textarea class="form-control" rows="6" required style="text-transform:uppercase"
                                                                    id="descripcion_subjetivo_ver_h" name="descripcion_subjetivo" disabled></textarea>
                                                            </div>

                                                            <div class="col-md-12"> <br>
                                                                <label for="tipo">OBJETIVO</label>
                                                                <textarea class="form-control" rows="6" required style="text-transform:uppercase"
                                                                    id="descripcion_objetivo_ver_h" name="descripcion_objetivo" disabled></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane" id="tab1defaultEvoluciones-ver">
                                                        <fieldset>
                                                            <div class="row"> <br>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <table id="tbl_DIAGNOSTICO_ENFERMERIA_VER"
                                                                                style="width: 100%; margin-bottom: 10px !important;">
                                                                                <tbody
                                                                                    id="cuerpo_DIAGNOSTICO_ENFERMERIA_VER"
                                                                                    name="cuerpo_DIAGNOSTICO_ENFERMERIA_VER">
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <table id="tbl_PLAN_CUIDADO_ENFERMERIA_VER"
                                                                                style="margin-bottom: 10px !important;">
                                                                                <tbody
                                                                                    id="cuerpo_PLAN_CUIDADO_ENFERMERIA_VER"
                                                                                    name="cuerpo_PLAN_CUIDADO_ENFERMERIA_VER"
                                                                                    class="cuerpo_PLAN_CUIDADO_ENFERMERIA_VER">
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <table
                                                                                id="tbl_INTERVENCIONES_ENFERMERIA_VER"
                                                                                style="width: 100%; margin-bottom: 10px !important;">
                                                                                <tbody
                                                                                    id="cuerpo_INTERVENCIONES_ENFERMERIA_VER"
                                                                                    name="cuerpo_INTERVENCIONES_ENFERMERIA_VER"
                                                                                    class="cuerpo_INTERVENCIONES_ENFERMERIA_VER">
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label for="tipo">DETALLE DE
                                                                                INTERVENCIONES</label>
                                                                            <textarea class="form-control" rows="3" required style="text-transform:uppercase"
                                                                                id="descripcion_intervenciones_ver_h" name="descripcion_intervenciones" disabled></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12"
                                                                            style="margin-bottom: 10px !important;width: 100%">
                                                                            <table
                                                                                id="tbl_EVALUACIONES_ENFERMERIA_VER">
                                                                                <tbody
                                                                                    id="cuerpo_EVALUACIONES_ENFERMERIA_VER"
                                                                                    name="cuerpo_EVALUACIONES_ENFERMERIA_VER"
                                                                                    class="cuerpo_EVALUACIONES_ENFERMERIA_VER">
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-12" style="">
                                                                            <label for="tipo">DETALLE DE
                                                                                EVALUACIONES</label>
                                                                            <textarea class="form-control" rows="3" required style="text-transform:uppercase"
                                                                                id="descripcion_evaluaciones_ver_h" name="descripcion_evaluaciones" disabled></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab2defaultEvoluciones-ver">
                                                        <fieldset>
                                                            <div class="panel panel-info panel-bordered panel-sm">
                                                                <div class="panel-heading">
                                                                    <div class="panel-title text-center"><br>
                                                                        <b>DIAGNOSTICO DE LA ATENCION</b>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <table class="table table-bordered"
                                                                        id="tblDiagnosticos" cellspacing="0"
                                                                        width="100%" style="margin-top:5px;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="15%">Codigo</th>
                                                                                <th width="50%">Nombre</th>
                                                                                <th width="15%">Tipo</th>
                                                                                <th width="15%">Clasificación</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tfoot>
                                                                        </tfoot>
                                                                        <tbody id="diagnosticos_ver">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12"
                                                                            style="margin-bottom: 10px;">
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <table id="tbl_CPMS_ENFERMERIA_VER"
                                                                                style="margin-bottom: 10px !important;width: 100%;">
                                                                                <tbody id="cuerpo_CPMS_ENFERMERIA_VER"
                                                                                    name="cuerpo_CPMS_ENFERMERIA_VER"
                                                                                    class="cuerpo_CPMS_ENFERMERIA_VER">
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab3defaultEvoluciones-ver_h">
                                                        <fieldset>
                                                            <form id="FormularioPunto" name="FormularioPunto"
                                                                enctype="multipart/form-data" method="GET">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <table class="table table-hover table-sm">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th width="5%">#</th>
                                                                                    <th width="10%">Tipo</th>
                                                                                    <th width="15%">Fecha</th>
                                                                                    <th width="25%">Lugar</th>
                                                                                    <th width="25%">Acción</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tfoot>
                                                                            </tfoot>
                                                                            <tbody id="lista_invasivos_ver">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <hr>
                                                                        </div>
                                                                        <input type="hidden" name="img_puntos_base"
                                                                            id="img_puntos_base">
                                                                        <div class="col-md-10"
                                                                            style="padding-left: 0px !important;  padding-right: 0px !important;cursor: pointer;">
                                                                            <div class="col-md-12"
                                                                                style="cursor: pointer;"
                                                                                id="celda1" name="celda1">
                                                                                <div class="form-group">
                                                                                    <div class="grid-block"
                                                                                        style="background-image: url('/images/hospitalizacion_enfermeria/imagen2.jpg'); background-repeat: no-repeat;">
                                                                                        <canvas id="plano_evolucion"
                                                                                            name="plano"
                                                                                            width="512"
                                                                                            height="512"></canvas>
                                                                                        <input type="hidden"
                                                                                            id="_token"
                                                                                            name="_token"
                                                                                            value="{{ csrf_token() }}">
                                                                                        <input type="hidden"
                                                                                            name="x"
                                                                                            id="x">
                                                                                        <input type="hidden"
                                                                                            name="y"
                                                                                            id="y">
                                                                                        <div id="control_div_puntos_evolucion_ver_h"
                                                                                            name="control_div_puntos_evolucion_ver_h">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <ul class="leyenda_grafico"
                                                                                id="control_div_puntos_evolucion_leyenda_ver_h"
                                                                                name="control_div_puntos_evolucion_leyenda_ver_h">
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </form>
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab4defaultEvoluciones-ver">
                                                        <fieldset>
                                                            <div class="panel panel-info panel-bordered panel-sm">
                                                                <div class="panel-heading">
                                                                    <div class="panel-title text-center"><br>
                                                                        <b>HISTORIAL DE INVASIVOS</b>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <table class="table table-bordered"
                                                                        id="tblDiagnosticos" cellspacing="0"
                                                                        width="100%" style="margin-top:5px;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="15%">N°</th>
                                                                                <th width="15%">Fecha</th>
                                                                                <th width="50%">Tipo</th>
                                                                                <th width="15%">Lugar</th>
                                                                                <th width="15%">Acción</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tfoot>
                                                                        </tfoot>
                                                                        <tbody id="historial_invasivos_ver">

                                                                        </tbody>
                                                                    </table>
                                                                    <div id="load-more-container-ver">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </fieldset>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                </div>
            </div>
        </div>
    </div>
</body>
