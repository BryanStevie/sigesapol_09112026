<script>
    const FORM_ACTION_CREATE_ID = 1;
    const FORM_ACTION_EDIT_ID = 2;
    let arrayViaAdministracion = [];
    let arrayMedicamentosKardex = [];
    let formMedicamentosData = {};
    let dataCabecera = null;

    function buildFilters() {
        /* const fecha_inicio_moment = $("#kardex_filter_fecha_inicio").val() ? moment($("#kardex_filter_fecha_inicio").val(), "DD/MM/YYYY") : "";
        const fecha_fin_moment = $("#kardex_filter_fecha_fin").val() ? moment($("#kardex_filter_fecha_fin").val(), "DD/MM/YYYY") : "";
        const filter = {
            emergencia_id:  String($('#id_hospitalizacion_kardex').val()),
            fecha_inicio: fecha_inicio_moment ? fecha_inicio_moment.format("YYYY-MM-DD") : "",
            fecha_fin: fecha_fin_moment ? fecha_fin_moment.format("YYYY-MM-DD") : "",
            tipo_id: String($("#kardex_filter_tipo").val()),
            turno_id: String($("#kardex_filter_turno").val()),
        } */
        const filter = {
            hospitalizacion_id: String($('#id_hospitalizacion_kardex').val()),
            fecha_inicio: "",
            fecha_fin: "",
            tipo_id: "",
            turno_id: "",
        }
        return filter;
    }

    function searchListKardex() {
        $("#btnCreateKardexContainer").hide();
        $("#tableListKardexContainer").hide();
        const renderTableHistory = $('#listKardexsTable').DataTable();
        if ($.fn.DataTable.isDataTable('#listKardexsTable')) {
            $('#listKardexsTable').DataTable().destroy();
            $('#listKardexsTable tbody').empty();
        }
        const queryParams = buildFilters();
        $('#listKardexsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            ordering: false,
            retrieve: false,
            destroy: true,
            resetPaging: false,
            language: {
                "processing": "Buscando resultados..."
            },
            ajax: {
                url: "/api/kardex-enfermerias/search-hospi",
                type: 'GET',
                data: function(d) {
                    d.hospitalizacion_id = queryParams.hospitalizacion_id;
                    d.fecha_inicio = queryParams.fecha_inicio;
                    d.fecha_fin = queryParams.fecha_fin;
                    d.tipo_id = queryParams.tipo_id;
                    d.turno_id = queryParams.turno_id;
                },
                dataSrc: function(json) {
                    if (json.length > 0) {
                        $("#tableListKardexContainer").show();
                    } else {
                        $("#btnCreateKardexContainer").show();
                    }
                    return json;
                }
            },
            columns: [{
                    data: 'kardex_enfermerias_id',
                    width: '5%',
                    className: 'text-center dt-center',
                },
                {
                    data: null,
                    width: '15%',
                    className: 'dt-center',
                    render: function(data, type, row) {
                        return `
                        <div>
                        <p><b>Fecha:</b> ${moment(row.fecha, "YYYY-MM-DD").format("DD/MM/YYYY") || " - "}</p>  
                        <p><b>Hora:</b> ${row.hora || " - "}</p>  
                        <p><b>Turno:</b> ${row.turno || " - "}</p>  
                        </div>
                    `;
                    },
                },
                {
                    data: null,
                    width: '20%',
                    className: 'dt-center',
                    render: function(data, type, row) {
                        return `
                        <div>
                        <p><b>Servicio:</b> ${row.servicio || " - "}</p>  
                        </div>
                    `;
                    },
                },
                {
                    data: null,
                    width: '20%',
                    className: 'dt-center',
                    render: function(data, type, row) {
                        return `
                        <div>
                            <p><b>Cama: </b> ${row.cama}</p>
                            <p><b>Cuarto: </b> ${row.cuarto}</p>  
                        </div>
                    `;
                    },
                },
                {
                    data: null,
                    width: '20%',
                    className: 'dt-center',
                    render: function(data, type, row) {
                        return `${row.medico_nombre || ""} ${row.medico_paterno || ""} ${row.medico_materno || ""}`;
                    },
                },
                {
                    data: null,
                    width: '10%',
                    className: 'text-center dt-center',
                    render: function(data, type, row) {
                        if (parseInt(row.kardex_enfermerias_estado) === 1) {
                            if (parseInt(row.kardex_enfermerias_flag_firma) === 1) {
                                return `
                                    <div>
                                        <p>
                                            <button id="show_detail_kardex-${row.kardex_enfermerias_id}" type="button" class="btn btn-success btn-xs" data-id="${row.kardex_enfermerias_id}" data-toggle="tooltip" data-placement="top" title="Ver detalle">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </button>
                                        </p>
                                    </div>
                                    <div> 
                                        <p>
                                            <a href="/${moment().format("YYYY/MM/DD")}/${row.kardex_enfermerias_ruta_firma}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="PDF FIRMADO" target="_blank">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                            </a>
                                        </p>
                                    </div>`;
                            }
                            return `
                                <div>
                                    <p>
                                        <button id="show_detail_kardex-${row.kardex_enfermerias_id}" type="button" class="btn btn-success btn-xs" data-id="${row.kardex_enfermerias_id}" data-toggle="tooltip" data-placement="top" title="Ver detalle">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </p>  
                                    <p>
                                        <button id="edit-${row.kardex_enfermerias_id}" type="button" class="btn btn-info btn-xs" data-id="${row.kardex_enfermerias_id}" data-toggle="tooltip" data-placement="top" title="Editar">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>
                                    </p>  
                                    <p>
                                        <a href="/api/kardex-enfermerias/pdf/hospi/${row.kardex_enfermerias_id}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="PDF" target="_blank">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                        </a>
                                    </p>
                                </div>
                            `;
                        }
                        return `
                            <div>
                                <p>
                                    <button id="show_detail_kardex-${row.kardex_enfermerias_id}" type="button" class="btn btn-success btn-xs" data-id="${row.kardex_enfermerias_id}" data-toggle="tooltip" data-placement="top" title="Ver detalle">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </p>
                            </div>`;
                    },
                },
                {
                    data: null,
                    width: '5%',
                    className: 'text-center dt-center',
                    render: function(data, type, row) {
                        if (parseInt(row.kardex_enfermerias_estado) === 1) {
                            if (parseInt(row.kardex_enfermerias_flag_firma) === 1) {
                                return `<span class="label label-success">KARDEX FIRMADO</span>`;
                            }
                            return `<button id="anular-${row.kardex_enfermerias_id}" type="button" class="btn btn-danger btn-xs"  data-id="${row.kardex_enfermerias_id}" data-toggle="tooltip" data-placement="top" title="anular"><i class="fa fa-times-circle" aria-hidden="true"></i></button>`;
                        }
                        return `<span class="label label-danger">KARDEX ANULADO</span>`;
                    },
                },
            ]
        });
    }

    function getKardexMedicamentosEnfermerias() {
        return new Promise((resolve, reject) => {
            const id_kardex_enfermeria = $("#id").val();
            $.ajax({
                type: "GET",
                url: `/api/kardex-medicamentos-enfermerias/kardex-enfermerias/${id_kardex_enfermeria}`,
                dataType: "json",
                success: function(result) {
                    resolve(result);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        })
    }

    const agruparMedicamentosPorFlagRegistro = (data) => {
        return data.reduce((acc, medicamento) => {
            (acc[medicamento.flag_registro] = acc[medicamento.flag_registro] || []).push(medicamento);
            return acc;
        }, {});
    };

    function buildTableViewMedicamentos(data) {
        $('#tableMedicamentosKardex tbody').empty();
        let rowString = "";
        if (data && data.length > 0) {
            const medicamentosPorFlagRegistro = agruparMedicamentosPorFlagRegistro(data);
            const $tbody = $('#tableMedicamentosKardex tbody');
            $.each(medicamentosPorFlagRegistro, function(flag_registro, medicamentos) {
                $tbody.append(
                    `<tr><td colspan="7" class="titulo-categoria" style="background-color: #d0d3d4"><strong>${getCategoryNamebyCategoryId(flag_registro)}</strong></td></tr>`
                    );

                $.each(medicamentos, function(index, medicamento) {
                    $tbody.append(buildRowTableDetailMedicamento(medicamento));
                });
            });
        }
    }

    function buildRowTableDetailMedicamento(data) {
        let timesString = "";
        if (data.horarios && data.horarios.length > 0) {
            data.horarios.forEach((horario, index) => {
                if (horario.aplicacion) {
                    timesString +=
                        `<b class='strikethrough'>${moment(horario.hora, "HH:mm:ss").format("HH:mm")}</b>${data.horarios.length > index + 1 ? " - " : ""}`;
                } else {
                    timesString +=
                        `<b>${moment(horario.hora, "HH:mm:ss").format("HH:mm")}</b>${data.horarios.length > index + 1 ? " - " : ""}`;
                }
            });
        }
        const medicamentoName = [0, 2, 3, 4, 5, 6].includes(parseInt(data.flag_registro)) ? data.medicamento : data
            .infusiones_descripcion;
        return ` 
        <tr>
            <td>
                <p>${medicamentoName || "-"}</p>
                <p>${timesString}</p>
            </td>
            <td>${data.usuario_nombre || ""} ${data.usuario_paterno || ""} ${data.usuario_materno || ""}</td>
            <td>${data.descripcion || "-"}</td>
            <td>C/${data.frecuencia || "0"}</td>
            <td>${data.dias_administracion || "0"} DÍAS</td>
            <td>
                ${parseInt(data.estado) === 1 ? `<button type="button" id="show_modal_horario-${data.id}" class="btn btn-success btn-xs" data-id="${data.id}" data-medicamento="${data.medicamento}" data-flag_registro="${data.flag_registro}" data-toggle="tooltip" data-placement="top" title="medicación"><i class="fa fa-clock-o" aria-hidden="true"></i></button>` : ''}
            </td>
            <td>
                ${parseInt(data.estado) === 1 ? 
                `<button type="button" id="suspend_medicamento-${data.id}" class="btn btn-danger btn-xs" data-id="${data.id}" data-estado_id="0" data-toggle="tooltip" data-placement="top" title="suspender">
                    <i class="fa fa-pause" aria-hidden="true"></i>
                </button>` : 
                `<button type="button" id="suspend_medicamento-${data.id}" class="btn btn-success btn-xs" data-id="${data.id}" data-estado_id="1" data-toggle="tooltip" data-placement="top" title="reanudar">
                    <i class="fa fa-play-circle" aria-hidden="true"></i>
                </button>`}
            </td>
        </tr>`;
    }

    function getCategoryNamebyCategoryId(categoryId) {
        const category = {
            0: "HIDRATACIONES",
            1: "INFUSIONES",
            2: "ANTIBIOTICOS",
            3: "OTROS",
            4: "INHALADORES",
            5: "TRASFUSIONES",
            6: "HEMOGLUCOTESTES",
        }
        return category[parseInt(categoryId)] || "OTROS";
    }

    function markInputError(nameInput, nameDiv, messsageError = null) {
        $(`#${nameInput}`).after(
            `<span class="label-error" style="color: #dc3545; font-size: 11px; font-weight: bold">${messsageError || "campo requerido"}</span>`
            );
        $(`#${nameDiv}`).addClass("has-error  has-feedback");
    }

    function clearInputError(nameInput, nameDiv) {
        $(`#${nameInput}`).siblings('.form-control-feedback, .label-error').remove();
        $(`#${nameDiv}`).removeClass("has-error  has-feedback");
    }

    function getValuesRowByTable(tableName) {
        const rows = $(`#${tableName} tbody tr`);
        const resultados = [];

        if (rows && rows.length > 0) {
            rows.each(function(index, fila) {
                let valores = {};
                $(fila).find('input, select').each(function() {
                    var input = $(this);
                    var nombre = input.attr('name');
                    var valor;
                    let label;

                    if (input.is(':checkbox')) {
                        valor = input.is(':checked');
                        valores[input.attr('name')] = valor;
                    } else if (input.is('select')) {
                        valor = input.find('option:selected').val();
                        label = input.find('option:selected').text();
                        valores[input.attr('name')] = valor;
                        valores[`${input.attr('name')}_label`] = label;
                    } else if (input.is(':radio')) {
                        if (input.is(':checked')) {
                            let dataIdRef = input.attr('data-id_ref');
                            valores[dataIdRef] = input.val();
                        }
                    } else {
                        valor = input.val();
                        valores[input.attr('name')] = valor;
                    }
                });
                // BUG ACA:
                valores.horarios = [];
                if ((tableName === "tableAntibioticos" || tableName === "tableOtros" || tableName ===
                        "tableInhaladores" || tableName === "tableHemoglucotest") && parseInt(valores.id) ===
                    0) {
                    const frecuenciaHoras = parseInt(valores.frecuencia) ?? 0;
                    const cantidadDias = parseInt(valores.dias_administracion) ?? 0;
                    const horaInicio = valores.hora || "00:00";
                    /* valores.horarios = generarHorarios(frecuenciaHoras, cantidadDias, horaInicio); */
                }
                resultados.push(valores);
            });
        }
        return resultados;
    }

    function generarHorarios(frecuenciaHoras, cantidadDias, horaInicio) {
        const horarios = [];
        const formatoFechaHora = 'YYYY-MM-DD HH:mm:ss';
        const intervalo = frecuenciaHoras * 60; // Convertir horas a minutos
        const fechaActual = moment().format('YYYY-MM-DD'); // Obtener la fecha actual
        const primeraToma = `${fechaActual} ${horaInicio}`; // Concatenar fecha actual con la hora proporcionada
        const fechaPrimeraToma = moment(primeraToma, formatoFechaHora);

        for (let i = 0; i < cantidadDias; i++) {
            /* let horaActual = fechaPrimeraToma.clone().add(i, 'days'); */
            let inicioDia = fechaPrimeraToma.clone().add(i, 'days');
            let finDia = fechaPrimeraToma.clone().add(i + 1, 'days');

            // ✅ Si intervalo es 0 → solo una toma
            if (intervalo === 0) {
                horarios.push({
                    fecha: inicioDia.format('YYYY-MM-DD'),
                    hora: inicioDia.format('HH:mm:ss')
                });
                continue;
            }
            while (inicioDia.isBefore(moment(fechaPrimeraToma).add(i + 1, 'days'))) {
                horarios.push({
                    fecha: inicioDia.format('YYYY-MM-DD'),
                    hora: inicioDia.format('HH:mm:ss')
                });
                inicioDia.add(intervalo, 'minutes');
            }
        }
        return horarios;
    }

    async function refreshTableMedicamentos() {
        const kardex_medicamentos_enfermerias = await getKardexMedicamentosEnfermerias();
        buildTableViewMedicamentos(kardex_medicamentos_enfermerias);
    }

    function clearFormKardex() {
        $("#tableExamAuxLab tbody").empty();
        $("#tableExamAuxImag tbody").empty();
        $("#tableInterconsulta tbody").empty();

        $("#divFormMedicamento").hide();
        $("#listMedicamentosContainer").show();

        $("#control_funciones_vitales").removeAttr('checked');
        $("#barrera_hematoencefalica").removeAttr('checked');
        $("#cabecera").val("");
        $("#id_oxigeno_terapia").val("");
        $("#movilización").removeAttr('checked');
        $("#hemoglucotest_cuidado_enfermero_kardex").val("");
        $("#oxigenoterapia_cantidad").val("");
        /* $('#oxigenoterapia_cantidad').prop('disabled', true); */

        $(`#tabs_kardex_register a[href="#mkr_tab_kardex"]`).tab('show');

        $("#btnSave").prop("disabled", false);
    }

    function clearFormMedicamentos() {
        $("#kardex_fecha").val("");
        $('input[name="radio_form_medicamento"][value="0"]').prop('checked', true);

        $("#tableHidratacion tbody").empty();
        $("#tableInfusion tbody").empty();
        $("#tableAntibioticos tbody").empty();
        $("#tableOtros tbody").empty();
        $("#tableInhaladores tbody").empty();
        $("#tableTranfusiones tbody").empty();
        $("#tableHemoglucotest tbody").empty();

        $('input[name="radio_form_medicamento"]').off('change');

        $("#btnSaveFormMedicamento").prop("disabled", false);
    }

    function clearTableViewMedicamentos() {
        $("#tableMedicamentosKardex tbody").empty();
    }

    function enforceMinMax(el) {
        if (el.value != "") {
            if (parseInt(el.value) < parseInt(el.min)) {
                el.value = el.min;
            }
            if (parseInt(el.value) > parseInt(el.max)) {
                el.value = el.max;
            }
        }
    }
</script>

<style>
    .dt-center {
        vertical-align: middle !important;
    }

    .titulo-categoria {
        text-align: center;
        font-weight: bold;
        background-color: #f2f2f2;
        /* Color de fondo para resaltar */
        font-size: 16px;
        /* Tamaño de fuente más grande */
    }
</style>
