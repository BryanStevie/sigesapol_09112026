<script>
    $(document).ready(function() {
        let formMedicamentos = [];
        let flag_action_form = 1;
        let flag_action_form_medicamento = 1;

        $('#modal_kardex_formulario_medicamento').on('shown.bs.modal', async function() {
            try {
                flag_action_form = $("#flag_action_form").val();
                flag_action_form_medicamento = $("#flag_action_form_medicamento").val();
                Swal.fire({
                    title: "MEDICAMENTOS",
                    html: '<h5>OBTENIENDO DATOS DE LOS MEDICAMENTOS...</h5>',
                    showLoaderOnConfirm: true,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                });

                clearFormMedicamentos();


                arrayViaAdministracion = await getViasAdministracion();

                formMedicamentos = [{
                    id: 1,
                    name: "kardex_fecha",
                    container_div: "kardex_fecha_div",
                    required: true,
                    tab: "mkr_tab_kardex"
                }];

                if ((parseInt(flag_action_form) === FORM_ACTION_CREATE_ID)) {
                    if (formMedicamentosData && JSON.stringify(formMedicamentosData) !== "{}") {
                        buildTableMedicamentosFormRegister(formMedicamentosData);
                    }
                } else {
                    const kardex_medicamentos_enfermerias =
                        await getKardexMedicamentosEnfermerias();
                    buildTableMedicamentosFormEdit(kardex_medicamentos_enfermerias);
                }

                $("#kardex_fecha").datepicker({
                    useCurrent: true,
                    format: 'dd/mm/yyyy',
                    todayHighlight: true,
                    autoclose: true,
                    placeholder: "dd/mm/yyyy",
                });

                $("#kardex_fecha").datepicker('setDate', new Date());

                $('input[name="radio_form_medicamento"]').change(function() {
                    const valueSelected = $('input[name="radio_form_medicamento"]:checked')
                        .val();
                    $('#tableHidratacion tbody').empty();
                    if (parseInt(valueSelected) === 1) {
                        $("#panel_hidratacion").fadeOut(); // MOSTRAR
                    } else {
                        $("#panel_hidratacion").fadeIn(); // OCULTAR
                    }
                });

            } catch (error) {
                Swal.fire({
                    title: "ERROR KARDEX ENFERMERIA",
                    html: `<h5>${error}</h5>`,
                    showLoaderOnConfirm: true,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                });
            } finally {
                Swal.hideLoading();
                Swal.close();
            }
        });

        $("#btnAddRowTableHidratacion").on("click", () => {
            onAddRowTableHidratacion();
        });

        $("#btnAddRowTableInfusion").on("click", () => {
            onAddRowTableInfusion();
        });

        $("#btnAddRowTableAntibioticos").on("click", () => {
            onAddRowTableAnbioticos();
        });

        $("#btnAddRowTableOtros").on("click", () => {
            onAddRowTableOtros();
        });

        $("#btnAddRowTableInhaladores").on("click", () => {
            onAddRowTableInhaladores();
        });

        $("#btnAddRowTableTranfusiones").on("click", () => {
            onAddRowTableTranfusiones();
        });

        $("#btnAddRowTableHemoglucotest").on("click", () => {
            onAddRowTableHemoglucotest();
        });

        $("#btnSaveFormMedicamento").on("click", () => {
            onClickSaveMedicamentos();
        });

        $("#btnCancelFormMedicamento").on("click", () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Si cancelas, perderás los cambios realizados en medicamentos",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, cancelar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearFormMedicamentos();
                    $("#modal_kardex_formulario_medicamento").modal('hide');
                }
            })
        })

        function onAddRowTableHidratacion(hidrationParam = null) {
            if (hidrationParam == null) {
                const resultValidateLastRow = validateLastRowTable("tableHidratacion");
                if (!resultValidateLastRow) {
                    Swal.fire({
                        icon: "warning",
                        title: '<p>HIDRATACIÓN</p>',
                        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UNA NUEVA HIDRATACIÓN</p>',
                    });
                    return null;
                }
            }

            const indexRow = $('#tableHidratacion tbody tr').length + 1;
            const newRow = `
            <tr id="tr_hidratacion_${indexRow}">
                <td id="kardex_hidratacion_medicamento_div${indexRow}"><input type="text" name="medicamento" id="kardex_hidratacion_medicamento${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_hidratacion_volumen_infusion_div${indexRow}"><input type="number" name="volumen_infusion" id="kardex_hidratacion_volumen_infusion${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_hidratacion_via_administracion_div${indexRow}">
                    <select class="form-control input-sm" name="id_via_administracion" id="kardex_hidratacion_via_administracion${indexRow}">${buildOptionsForSelect(arrayViaAdministracion, "id", "descripcion")}</select>
                </td>
                <td>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_hidratacion_frasco_I${indexRow}" name="I"  value="true">
                        <label class="form-check-label" for="kardex_hidratacion_frasco_I${indexRow}" style="margin-left: 20px">I</label>
                        </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_hidratacion_frasco_II${indexRow}" name="II" value="true">
                        <label class="form-check-label" for="kardex_hidratacion_frasco_II${indexRow}" style="margin-left: 20px">II</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_hidratacion_frasco_III${indexRow}" name="III" value="true">
                        <label class="form-check-label" for="kardex_hidratacion_frasco_III${indexRow}" style="margin-left: 20px">III</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_hidratacion_frasco_IV${indexRow}" name="IV" value="true">
                        <label class="form-check-label" for="kardex_hidratacion_frasco_IV${indexRow}" style="margin-left: 20px">IV</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_hidratacion_frasco_V${indexRow}" name="V" value="true">
                        <label class="form-check-label" for="kardex_hidratacion_frasco_V${indexRow}" style="margin-left: 20px">V</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_hidratacion_frasco_VI${indexRow}" name="VI" value="true">
                        <label class="form-check-label" for="kardex_hidratacion_frasco_VI${indexRow}" style="margin-left: 20px">VI</label>
                    </div>
                </td>
                <td id="kardex_hidratacion_hora_div${indexRow}"><input type="time" name="hora" id="kardex_hidratacion_hora${indexRow}" class="form-control input-sm"></td>
                ${hidrationParam == null || parseInt(flag_action_form) === 1 ? '<td><button id="btnRemoveRowHidratacion" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>' : ''}
                <td><input type="hidden" name="id" id="kardex_hidratacion_id${indexRow}" value="0"></td>
                <td><input type="hidden" name="flag_registro" id="flag_registro" value="0"></td>
            </tr>`;
            $('#tableHidratacion tbody').append(newRow);

            $('#tableHidratacion').on('click', '#btnRemoveRowHidratacion', function() {
                const row = $(this).closest('tr');
                const rowId = row.attr('id');
                formMedicamentos = formMedicamentos.filter(obj => String(obj.tr_id) !== String(rowId));
                row.remove();
            });

            if (hidrationParam) {
                $(`#kardex_hidratacion_medicamento${indexRow}`).val(hidrationParam.medicamento);
                $(`#kardex_hidratacion_volumen_infusion${indexRow}`).val(hidrationParam.volumen_infusion);
                $(`#kardex_hidratacion_via_administracion${indexRow}`).val(hidrationParam
                    .id_via_administracion);
                $(`#kardex_hidratacion_via_administracion${indexRow}`).prop('readonly', true);

                let frascoJson = {};
                if (typeof hidrationParam.frasco === 'string') {
                    frascoJson = JSON.parse(hidrationParam.frasco);
                } else {
                    frascoJson = hidrationParam.frasco;
                }
                Object.entries(frascoJson).forEach(([clave, valor]) => {
                    if (valor) $(`#kardex_hidratacion_frasco_${clave}${indexRow}`).prop('checked',
                        true);
                });
                $(`#kardex_hidratacion_hora${indexRow}`).val(hidrationParam.hora);
                if (parseInt(flag_action_form) === 2) {
                    $(`#kardex_hidratacion_hora${indexRow}`).prop('readonly', true);
                }

                $(`#kardex_hidratacion_id${indexRow}`).val(hidrationParam.id);
            }

            formMedicamentos.push({
                id: indexRow,
                name: `kardex_hidratacion_medicamento${indexRow}`,
                container_div: `kardex_hidratacion_medicamento_div${indexRow}`,
                required: true,
                tr_id: `tr_hidratacion_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_hidratacion_volumen_infusion${indexRow}`,
                container_div: `kardex_hidratacion_volumen_infusion_div${indexRow}`,
                required: true,
                tr_id: `tr_hidratacion_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_hidratacion_via_administracion${indexRow}`,
                container_div: `kardex_hidratacion_via_administracion_div${indexRow}`,
                required: true,
                tr_id: `tr_hidratacion_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_hidratacion_hora${indexRow}`,
                container_div: `kardex_hidratacion_hora_div${indexRow}`,
                required: true,
                tr_id: `tr_hidratacion_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
        }

        function onAddRowTableInfusion(infusionParam = null) {
            if (infusionParam === null) {
                const resultValidateLastRow = validateLastRowTable("tableInfusion");
                if (!resultValidateLastRow) {
                    Swal.fire({
                        icon: "warning",
                        title: '<p>INFUSIÓN</p>',
                        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UNA NUEVA INFUSIÓN</p>',
                    });
                    return null;
                }
            }

            const indexRow = $('#tableInfusion tbody tr').length + 1;
            const newRow = `
            <tr id="tr_infusion_${indexRow}">
                <td id="kardex_infusion_medicamento_div${indexRow}"><input type="text" name="infusiones_descripcion" id="kardex_infusion_medicamento${indexRow}" class="form-control input-sm"></td>
                <td>
                    <div class="radio">
                        <input type="radio" name="kardex_infusion_titulable${indexRow}" id="kardex_infusion_titulable_option_1${indexRow}" data-id_ref="titulable" value="0">
                        <label class="form-check-label" for="kardex_infusion_titulable_option_1${indexRow}" style="margin-left: 20px">TITULABLE</label>
                    </div>
                    <div class="radio">
                        <input type="radio" name="kardex_infusion_titulable${indexRow}" id="kardex_infusion_titulable_option_2${indexRow}" data-id_ref="titulable" value="1">
                        <label class="form-check-label" for="kardex_infusion_titulable_option_2${indexRow}" style="margin-left: 20px">VOLUMEN DE INFUSIÓN</label>
                    </div>
                </td>
                <td id="kardex_infusion_volumen_infusion_div${indexRow}"><input type="number" name="volumen_infusion" id="kardex_infusion_volumen_infusion${indexRow}" class="form-control input-sm" ></td>
                <td id="kardex_infusion_via_administracion_div${indexRow}">
                    <select class="form-control input-sm" name="id_via_administracion" id="kardex_infusion_via_administracion${indexRow}">${buildOptionsForSelect(arrayViaAdministracion, "id", "descripcion")}</select>
                </td>
                <td id="kardex_infusion_hora_div${indexRow}"><input type="time" name="hora" id="kardex_infusion_hora${indexRow}" class="form-control input-sm"></td>
                ${infusionParam === null || parseInt(flag_action_form) === 1 ? '<td><button id="btnRemoveRowInfusion" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>' : ''}
                <td><input type="hidden" name="id" id="kardex_infusion_id${indexRow}" value="0"></td>
                <td><input type="hidden" name="id_infusion" id="kardex_infusion_medicamento_id${indexRow}"></td>
                <td><input type="hidden" name="flag_registro" id="flag_registro" value="1"></td>
            </tr>`;
            $('#tableInfusion tbody').append(newRow);

            $(`#kardex_infusion_medicamento${indexRow}`).autocomplete({
                minLength: 1,
                delay: 300,
                source: function(request, response) {
                    $.ajax({
                        url: `/api/infusiones-enfermerias/buscar`,
                        type: "GET",
                        dataType: "json",
                        data: {
                            descripcion: request.term
                        },
                        success: function(data) {
                            response(data.map(function(item) {
                                return {
                                    id: item.id,
                                    label: item.descripcion
                                };
                            }));
                        },
                        error: function() {
                            response([]);
                        }
                    })
                },
                select: function(event, ui) {
                    $(this).val(ui.item.label);
                    $(`#kardex_infusion_medicamento_id${indexRow}`).val(ui.item.id);
                }
            });

            $(`#kardex_infusion_medicamento${indexRow}`).on('input', function() {
                $(`#kardex_infusion_medicamento_id${indexRow}`).val("");
            });

            $(`#kardex_infusion_medicamento${indexRow}`).blur(function() {
                const kardex_infusion_medicamento_id = $(`#kardex_infusion_medicamento_id${indexRow}`)
                    .val();
                clearInputError(`kardex_infusion_medicamento${indexRow}`,
                    `kardex_infusion_medicamento_div${indexRow}`)
                if (!kardex_infusion_medicamento_id) {
                    markInputError(`kardex_infusion_medicamento${indexRow}`,
                        `kardex_infusion_medicamento_div${indexRow}`,
                        "espere los resultados y seleccione uno");
                }
            });

            $('#tableInfusion').on('click', '#btnRemoveRowInfusion', function() {
                const row = $(this).closest('tr');
                const rowId = row.attr('id');
                formMedicamentos = formMedicamentos.filter(obj => String(obj.tr_id) !== String(rowId));
                row.remove();
            });

            if (infusionParam) {
                $(`#kardex_infusion_medicamento${indexRow}`).val(infusionParam.infusiones_descripcion);
                $(`#kardex_infusion_medicamento_id${indexRow}`).val(infusionParam.id_infusion);

                if (infusionParam.titulable && parseInt(infusionParam.titulable) === 1) {
                    $(`#kardex_infusion_titulable_option_1${indexRow}`).prop('checked', true);
                } else {
                    $(`#kardex_infusion_titulable_option_2${indexRow}`).prop('checked', true);
                }

                $(`#kardex_infusion_volumen_infusion${indexRow}`).val(infusionParam.volumen_infusion);
                $(`#kardex_infusion_via_administracion${indexRow}`).val(infusionParam.id_via_administracion);
                $(`#kardex_infusion_via_administracion${indexRow}`).prop('readonly', true);

                $(`#kardex_infusion_hora${indexRow}`).val(infusionParam.hora);
                if (parseInt(flag_action_form) === 2) {
                    $(`#kardex_infusion_hora${indexRow}`).prop('readonly', true);
                }

                $(`#kardex_infusion_id${indexRow}`).val(infusionParam.id);
            } else {
                $(`#kardex_infusion_titulable_option_1${indexRow}`).prop('checked', true);
            }

            formMedicamentos.push({
                id: indexRow,
                name: `kardex_infusion_medicamento${indexRow}`,
                container_div: `kardex_infusion_medicamento_div${indexRow}`,
                required: true,
                tr_id: `tr_infusion_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_infusion_volumen_infusion${indexRow}`,
                container_div: `kardex_infusion_volumen_infusion_div${indexRow}`,
                required: true,
                tr_id: `tr_infusion_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_infusion_via_administracion${indexRow}`,
                container_div: `kardex_infusion_via_administracion_div${indexRow}`,
                required: true,
                tr_id: `tr_infusion_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_infusion_hora${indexRow}`,
                container_div: `kardex_infusion_hora_div${indexRow}`,
                required: true,
                tr_id: `tr_infusion_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
        }

        function onAddRowTableAnbioticos(antibioticosParam = null) {
            if (antibioticosParam === null) {
                const resultValidateLastRow = validateLastRowTable("tableAntibioticos");
                if (!resultValidateLastRow) {
                    Swal.fire({
                        icon: "warning",
                        title: '<p>ANTIBIOTICOS</p>',
                        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO ANTIBIOTICOS</p>',
                    });
                    return null;
                }
            }

            const indexRow = $('#tableAntibioticos tbody tr').length + 1;
            const newRow = `
            <tr id="tr_antibioticos_${indexRow}">
                <td id="kardex_antibioticos_medicamento_div${indexRow}"><input type="text" name="medicamento" id="kardex_antibioticos_medicamento${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_antibioticos_via_administracion_div${indexRow}">
                <select class="form-control input-sm" name="id_via_administracion" id="kardex_antibioticos_via_administracion${indexRow}">${buildOptionsForSelect(arrayViaAdministracion, "id", "descripcion")}</select>
                </td>
                <td id="kardex_antibioticos_frecuencia_div${indexRow}"><input type="number" min="1" max="24" onkeyup=enforceMinMax(this) name="frecuencia" id="kardex_antibioticos_frecuencia${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_antibioticos_hora_div${indexRow}"><input type="time" name="hora" id="kardex_antibioticos_hora${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_antibioticos_dias_div${indexRow}"><input type="number" min="1" max="100" onkeyup=enforceMinMax(this) name="dias_administracion" id="kardex_antibioticos_dias${indexRow}" class="form-control input-sm"></td>
                ${antibioticosParam === null || parseInt(flag_action_form) === 1 ? '<td><button id="btnRemoveRowAnbioticos" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>' : ""}
                <td><input type="hidden" name="id_medicamento" id="kardex_antibioticos_medicamento_id${indexRow}"></td>
                <td><input type="hidden" name="id" id="kardex_antibioticos_id${indexRow}" value="0"></td>
                <td><input type="hidden" name="flag_registro" id="flag_registro" value="2"></td>
            </tr>`;
            $('#tableAntibioticos tbody').append(newRow);

            $(`#kardex_antibioticos_medicamento${indexRow}`).autocomplete({
                minLength: 1,
                delay: 300,
                source: function(request, response) {
                    $.ajax({
                        url: `/api/productos/buscar`,
                        type: "GET",
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function(data) {
                            response(data.map(function(item) {
                                return {
                                    id: item.id,
                                    label: `${item.codigo} - ${item.nombre}`
                                };
                            }));
                        },
                        error: function() {
                            response([]);
                        }
                    })
                },
                select: function(event, ui) {
                    $(this).val(ui.item.label);
                    $(`#kardex_antibioticos_medicamento_id${indexRow}`).val(ui.item.id);
                }
            });

            $(`#kardex_antibioticos_medicamento${indexRow}`).on('input', function() {
                $(`#kardex_antibioticos_medicamento_id${indexRow}`).val("");
            });

            $(`#kardex_antibioticos_medicamento${indexRow}`).blur(function() {
                const kardex_antibioticos_medicamento_id = $(
                    `#kardex_antibioticos_medicamento_id${indexRow}`).val();
                clearInputError(`kardex_antibioticos_medicamento${indexRow}`,
                    `kardex_antibioticos_medicamento_div${indexRow}`)
                if (!kardex_antibioticos_medicamento_id) {
                    markInputError(`kardex_antibioticos_medicamento${indexRow}`,
                        `kardex_antibioticos_medicamento_div${indexRow}`,
                        "espere los resultados y seleccione uno")
                }
            });

            $('#tableAntibioticos').on('click', '#btnRemoveRowAnbioticos', function() {
                const row = $(this).closest('tr');
                const rowId = row.attr('id');
                formMedicamentos = formMedicamentos.filter(obj => String(obj.tr_id) !== String(rowId));
                row.remove();
            });

            if (antibioticosParam) {
                $(`#kardex_antibioticos_medicamento${indexRow}`).val(antibioticosParam.medicamento);
                $(`#kardex_antibioticos_medicamento_id${indexRow}`).val(antibioticosParam.id_medicamento);

                $(`#kardex_antibioticos_via_administracion${indexRow}`).val(antibioticosParam
                    .id_via_administracion);
                $(`#kardex_antibioticos_frecuencia${indexRow}`).val(antibioticosParam.frecuencia);

                $(`#kardex_antibioticos_hora${indexRow}`).val(antibioticosParam.hora);

                $(`#kardex_antibioticos_dias${indexRow}`).val(antibioticosParam.dias_administracion);

                if (parseInt(flag_action_form) === 2) {
                    $(`#kardex_antibioticos_frecuencia${indexRow}`).prop('readonly', true);
                    $(`#kardex_antibioticos_hora${indexRow}`).prop('readonly', true);
                    $(`#kardex_antibioticos_dias${indexRow}`).prop('readonly', true);
                }

                $(`#kardex_antibioticos_id${indexRow}`).val(antibioticosParam.id);
            }

            formMedicamentos.push({
                id: indexRow,
                name: `kardex_antibioticos_medicamento${indexRow}`,
                container_div: `kardex_antibioticos_medicamento_div${indexRow}`,
                required: true,
                tr_id: `tr_antibioticos_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_antibioticos_via_administracion${indexRow}`,
                container_div: `kardex_antibioticos_via_administracion_div${indexRow}`,
                required: true,
                tr_id: `tr_antibioticos_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_antibioticos_frecuencia${indexRow}`,
                container_div: `kardex_antibioticos_frecuencia_div${indexRow}`,
                required: true,
                tr_id: `tr_antibioticos_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_antibioticos_hora${indexRow}`,
                container_div: `kardex_antibioticos_hora_div${indexRow}`,
                required: true,
                tr_id: `tr_antibioticos_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_antibioticos_dias${indexRow}`,
                container_div: `kardex_antibioticos_dias_div${indexRow}`,
                required: true,
                tr_id: `tr_antibioticos_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
        }

        function onAddRowTableOtros(otroParam = null) {
            if (otroParam === null) {
                const resultValidateLastRow = validateLastRowTable("tableOtros");
                if (!resultValidateLastRow) {
                    Swal.fire({
                        icon: "warning",
                        title: '<p>OTROS</p>',
                        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UNO NUEVO</p>',
                    });
                    return null;
                }
            }

            const indexRow = $('#tableOtros tbody tr').length + 1;
            const newRow = `
            <tr id="tr_otros_${indexRow}">
                <td id="kardex_otros_medicamento_div${indexRow}"><input type="text" name="medicamento" id="kardex_otros_medicamento${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_otros_via_administracion_div${indexRow}">
                    <select class="form-control input-sm" name="id_via_administracion" id="kardex_otros_via_administracion${indexRow}">${buildOptionsForSelect(arrayViaAdministracion, "id", "descripcion")}</select>
                </td>
                <td>
                <div class="form-check" style="margin-top: 5px">
                    <input type="checkbox" class="form-check-input" id="kardex_otros_condicional${indexRow}" name="condicional" value="true">
                    <label class="form-check-label" for="kardex_otros_condicional" style="margin-left: 20px">CONDICIONAL</label>
                </div>
                </td>
                <td id="kardex_otros_frecuencia_div${indexRow}"><input type="number" min="1" max="24" onkeyup=enforceMinMax(this) name="frecuencia"  id="kardex_otros_frecuencia${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_otros_hora_div${indexRow}"><input type="time" name="hora" id="kardex_otros_hora${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_otros_dias_div${indexRow}"><input type="number" min="1" max="100" onkeyup=enforceMinMax(this) name="dias_administracion" id="kardex_otros_dias${indexRow}" class="form-control input-sm"></td>
                <td>${otroParam === null || parseInt(flag_action_form) === 1 ? '<button id="btnRemoveRowOtros" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>' : ""}</td>
                <td><input type="hidden" name="id" id="kardex_otros_id${indexRow}" value="0"></td>
                <td><input type="hidden" name="flag_registro" id="flag_registro" value="3"></td>
            </tr>`;
            $('#tableOtros tbody').append(newRow);

            $(`#kardex_otros_condicional${indexRow}`).change(function() {
                $(`#kardex_otros_frecuencia${indexRow}`).val("");
                if (this.checked) {
                    $(`#kardex_otros_frecuencia${indexRow}`).attr("type", "text");
                } else {
                    $(`#kardex_otros_frecuencia${indexRow}`).attr("type", "number");
                }
            });

            $('#tableOtros').on('click', '#btnRemoveRowOtros', function() {
                const row = $(this).closest('tr');
                const rowId = row.attr('id');
                formMedicamentos = formMedicamentos.filter(obj => String(obj.tr_id) !== String(rowId));
                row.remove();
            });

            if (otroParam) {
                $(`#kardex_otros_medicamento${indexRow}`).val(otroParam.medicamento);
                $(`#kardex_otros_via_administracion${indexRow}`).val(otroParam.id_via_administracion);
                $(`#kardex_otros_condicional${indexRow}`).prop('checked', parseInt(otroParam.condicional) ===
                    1 ? true : false);

                $(`#kardex_otros_frecuencia${indexRow}`).val(otroParam.frecuencia);

                $(`#kardex_otros_hora${indexRow}`).val(otroParam.hora);

                if (parseInt(flag_action_form) === 2) {
                    $(`#kardex_otros_condicional${indexRow}`).prop('disabled', true);
                    $(`#kardex_otros_frecuencia${indexRow}`).prop('readonly', true);
                    $(`#kardex_otros_hora${indexRow}`).prop('readonly', true);
                }

                $(`#kardex_otros_dias${indexRow}`).val(otroParam.dias_administracion || 0);

                $(`#kardex_otros_id${indexRow}`).val(otroParam.id);
            }

            formMedicamentos.push({
                id: indexRow,
                name: `kardex_otros_medicamento${indexRow}`,
                container_div: `kardex_otros_medicamento_div${indexRow}`,
                required: true,
                tr_id: `tr_otros_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_otros_via_administracion${indexRow}`,
                container_div: `kardex_otros_via_administracion_div${indexRow}`,
                required: true,
                tr_id: `tr_otros_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_otros_frecuencia${indexRow}`,
                container_div: `kardex_otros_frecuencia_div${indexRow}`,
                required: true,
                tr_id: `tr_otros_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_otros_hora${indexRow}`,
                container_div: `kardex_otros_hora_div${indexRow}`,
                required: true,
                tr_id: `tr_otros_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_otros_dias${indexRow}`,
                container_div: `kardex_otros_dias_div${indexRow}`,
                required: true,
                tr_id: `tr_otros_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
        }

        function onAddRowTableInhaladores(inhaladoresParam = null) {
            if (inhaladoresParam === null) {
                const resultValidateLastRow = validateLastRowTable("tableInhaladores");
                if (!resultValidateLastRow) {
                    Swal.fire({
                        icon: "warning",
                        title: 'INHALADORES',
                        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO INHALADORES</p>',
                    });
                    return null;
                }
            }

            const indexRow = $('#tableInhaladores tbody tr').length + 1;
            const newRow = `
            <tr id="tr_inhaladores_${indexRow}">
                <td id="kardex_inhaladores_medicamento_div${indexRow}"><input type="text" name="medicamento" id="kardex_inhaladores_medicamento${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_inhaladores_via_administracion_div${indexRow}">
                    <select class="form-control input-sm" name="id_via_administracion" id="kardex_inhaladores_via_administracion${indexRow}">${buildOptionsForSelect(arrayViaAdministracion, "id", "descripcion")}</select>
                </td>
                <td>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_inhaladores_terapia_rescate_I${indexRow}" name="I" value="true">
                        <label class="form-check-label" for="kardex_inhaladores_terapia_rescate_I${indexRow}" style="margin-left: 20px">I</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_inhaladores_terapia_rescate_II${indexRow}" name="II" value="true">
                        <label class="form-check-label" for="kardex_inhaladores_terapia_rescate_II${indexRow}" style="margin-left: 20px">II</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_inhaladores_terapia_rescate_III${indexRow}" name="III" value="true">
                        <label class="form-check-label" for="kardex_inhaladores_terapia_rescate_III${indexRow}" style="margin-left: 20px">III</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_inhaladores_terapia_rescate_IV${indexRow}" name="IV" value="true">
                        <label class="form-check-label" for="kardex_inhaladores_terapia_rescate_IV${indexRow}" style="margin-left: 20px">IV</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_inhaladores_terapia_rescate_V${indexRow}" name="V" value="true">
                        <label class="form-check-label" for="kardex_inhaladores_terapia_rescate_V${indexRow}" style="margin-left: 20px">V</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_inhaladores_terapia_rescate_VI" name="VI" value="true">
                        <label class="form-check-label" for="kardex_inhaladores_terapia_rescate_VI${indexRow}" style="margin-left: 20px">VI</label>
                    </div>
                </td>
                <td id="kardex_inhaladores_frecuencia_div${indexRow}"><input type="number" min="1" max="24" onkeyup=enforceMinMax(this) name="frecuencia" id="kardex_inhaladores_frecuencia${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_inhaladores_hora_div${indexRow}"><input type="time" name="hora" id="kardex_inhaladores_hora${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_inhaladores_dias_div${indexRow}"><input type="number" min="1" max="100" onkeyup=enforceMinMax(this) name="dias_administracion" id="kardex_inhaladores_dias${indexRow}" class="form-control input-sm"></td>
                <td>${inhaladoresParam === "" || parseInt(flag_action_form) === 1 ? '<button id="btnRemoveRowInhaladores" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>' : ""}</td>
                <td><input type="hidden" name="id" id="kardex_inhaladores_id${indexRow}" value="0"></td>
                <td><input type="hidden" name="flag_registro" id="flag_registro" value="4"></td>
            </tr>`;
            $('#tableInhaladores tbody').append(newRow);

            $('#tableInhaladores').on('click', '#btnRemoveRowInhaladores', function() {
                const row = $(this).closest('tr');
                const rowId = row.attr('id');
                formMedicamentos = formMedicamentos.filter(obj => String(obj.tr_id) !== String(rowId));
                row.remove();
            });

            if (inhaladoresParam) {
                $(`#kardex_inhaladores_medicamento${indexRow}`).val(inhaladoresParam.medicamento);
                $(`#kardex_inhaladores_via_administracion${indexRow}`).val(inhaladoresParam
                    .id_via_administracion);

                let terapiaRescateJson = {};
                if (typeof inhaladoresParam.terapia_rescate === 'string') {
                    terapiaRescateJson = JSON.parse(inhaladoresParam.terapia_rescate);
                } else {
                    terapiaRescateJson = inhaladoresParam.terapia_rescate;
                }
                Object.entries(terapiaRescateJson).forEach(([clave, valor]) => {
                    if (valor) $(`#kardex_inhaladores_terapia_rescate_${clave}${indexRow}`).prop(
                        'checked', true);
                });

                $(`#kardex_inhaladores_frecuencia${indexRow}`).val(inhaladoresParam.frecuencia);

                $(`#kardex_inhaladores_hora${indexRow}`).val(inhaladoresParam.hora);

                $(`#kardex_inhaladores_dias${indexRow}`).val(inhaladoresParam.dias_administracion || 0);

                if (parseInt(flag_action_form) === 2) {
                    $(`#kardex_inhaladores_frecuencia${indexRow}`).prop('readonly', true);
                    $(`#kardex_inhaladores_hora${indexRow}`).prop('readonly', true);
                    $(`#kardex_inhaladores_dias${indexRow}`).prop('readonly', true);
                }

                $(`#kardex_inhaladores_id${indexRow}`).val(inhaladoresParam.id);
            }

            formMedicamentos.push({
                id: indexRow,
                name: `kardex_inhaladores_medicamento${indexRow}`,
                container_div: `kardex_inhaladores_medicamento_div${indexRow}`,
                required: true,
                tr_id: `tr_inhaladores_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_inhaladores_via_administracion${indexRow}`,
                container_div: `kardex_inhaladores_via_administracion_div${indexRow}`,
                tr_id: `tr_inhaladores_${indexRow}`,
                required: true,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_inhaladores_frecuencia${indexRow}`,
                container_div: `kardex_inhaladores_frecuencia_div${indexRow}`,
                required: true,
                tr_id: `tr_inhaladores_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_inhaladores_hora${indexRow}`,
                container_div: `kardex_inhaladores_hora_div${indexRow}`,
                required: true,
                tr_id: `tr_inhaladores_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_inhaladores_dias${indexRow}`,
                container_div: `kardex_inhaladores_dias_div${indexRow}`,
                required: true,
                tr_id: `tr_inhaladores_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
        }

        function onAddRowTableTranfusiones(tranfusionesParam = null) {
            if (tranfusionesParam === null) {
                const resultValidateLastRow = validateLastRowTable("tableTranfusiones");
                if (!resultValidateLastRow) {
                    Swal.fire({
                        icon: "warning",
                        title: 'TRANSFUSIONES',
                        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UNA NUEVA TRANSFUSIONES</p>',
                    });
                    return null;
                }
            }

            const indexRow = $('#tableTranfusiones tbody tr').length + 1;
            const newRow = `
                <tr id="tr_tranfusiones_${indexRow}">
                    <td id="kardex_tranfusiones_medicamento_div${indexRow}"><input type="text" name="medicamento" id="kardex_tranfusiones_medicamento${indexRow}" class="form-control input-sm"></td>
                    <td>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_tranfusiones_paquetes_I${indexRow}" name="I" value="true">
                        <label class="form-check-label" for="kardex_tranfusiones_paquetes_I${indexRow}" style="margin-left: 20px">I</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_tranfusiones_paquetes_II${indexRow}" name="II" value="true">
                        <label class="form-check-label" for="kardex_tranfusiones_paquetes_II${indexRow}" style="margin-left: 20px">II</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_tranfusiones_paquetes_III${indexRow}" name="III" value="true">
                        <label class="form-check-label" for="kardex_tranfusiones_paquetes_III${indexRow}" style="margin-left: 20px">III</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_tranfusiones_paquetes_IV${indexRow}" name="IV" value="true">
                        <label class="form-check-label" for="kardex_tranfusiones_paquetes_IV${indexRow}" style="margin-left: 20px">IV</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_tranfusiones_paquetes_V${indexRow}" name="V" value="true">
                        <label class="form-check-label" for="kardex_tranfusiones_paquetes_V${indexRow}" style="margin-left: 20px">V</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kardex_tranfusiones_paquetes_VI${indexRow}" name="VI" value="true">
                        <label class="form-check-label" for="kardex_tranfusiones_paquetes_VI${indexRow}" style="margin-left: 20px">VI</label>
                    </div>
                    </td>
                    <td id="kardex_tranfusiones_hora_div${indexRow}"><input type="time" name="hora" id="kardex_tranfusiones_hora${indexRow}" class="form-control input-sm"></td>
                    <td>${tranfusionesParam === null || parseInt(flag_action_form) === 1 ? '<button id="btnRemoveRowTranfusiones" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>' : ""}</td>
                    <td><input type="hidden" name="id" id="kardex_tranfusiones_id${indexRow}" value="0"></td>
                    <td><input type="hidden" name="flag_registro" id="flag_registro" value="5"></td>
                </tr>`;
            $('#tableTranfusiones tbody').append(newRow);

            $('#tableTranfusiones').on('click', '#btnRemoveRowTranfusiones', function() {
                const row = $(this).closest('tr');
                const rowId = row.attr('id');
                formMedicamentos = formMedicamentos.filter(obj => String(obj.tr_id) !== String(rowId));
                row.remove();
            });

            if (tranfusionesParam) {
                $(`#kardex_tranfusiones_medicamento${indexRow}`).val(tranfusionesParam.medicamento);

                let paquetesJson = {};
                if (typeof tranfusionesParam.paquetes === 'string') {
                    paquetesJson = JSON.parse(tranfusionesParam.paquetes);
                } else {
                    paquetesJson = tranfusionesParam.paquetes;
                }
                Object.entries(paquetesJson).forEach(([clave, valor]) => {
                    if (valor) $(`#kardex_tranfusiones_paquetes_${clave}${indexRow}`).prop('checked',
                        true);
                });

                $(`#kardex_tranfusiones_hora${indexRow}`).val(tranfusionesParam.hora);
                if (parseInt(flag_action_form) === 2) {
                    $(`#kardex_tranfusiones_hora${indexRow}`).prop('readonly', true);
                }

                $(`#kardex_tranfusiones_id${indexRow}`).val(tranfusionesParam.id);
            }

            formMedicamentos.push({
                id: indexRow,
                name: `kardex_tranfusiones_medicamento${indexRow}`,
                container_div: `kardex_tranfusiones_medicamento_div${indexRow}`,
                required: true,
                tr_id: `tr_tranfusiones_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_tranfusiones_hora${indexRow}`,
                container_div: `kardex_tranfusiones_hora_div${indexRow}`,
                required: true,
                tr_id: `tr_tranfusiones_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
        }

        function onAddRowTableHemoglucotest(hemoglucotesParam = null) {
            if (hemoglucotesParam === null) {
                const resultValidateLastRow = validateLastRowTable("tableHemoglucotest");
                if (!resultValidateLastRow) {
                    Swal.fire({
                        icon: "warning",
                        title: 'HEMOGLUCOTEST',
                        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO HEMOGLUCOTEST</p>',
                    });
                    return null;
                }
            }

            const indexRow = $('#tableHemoglucotest tbody tr').length + 1;
            const newRow = `
            <tr id="tr_hemoglucotest_${indexRow}">
                <td id="kardex_hemoglucotest_medicamento_div${indexRow}"><input type="text" name="medicamento" id="kardex_hemoglucotest_medicamento${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_hemoglucotest_frecuencia_div${indexRow}"><input type="number" min="1" max="24" onkeyup=enforceMinMax(this) name="frecuencia" id="kardex_hemoglucotest_frecuencia${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_hemoglucotest_hora_div${indexRow}"><input type="time" name="hora" id="kardex_hemoglucotest_hora${indexRow}" class="form-control input-sm"></td>
                <td id="kardex_hemoglucotest_dias_div${indexRow}"><input type="number" min="1" max="100" onkeyup=enforceMinMax(this) name="dias_administracion" id="kardex_hemoglucotest_dias${indexRow}" class="form-control input-sm"></td>
                <td>${hemoglucotesParam === null || parseInt(flag_action_form) === 1 ? '<button id="btnRemoveRowHemoglucotest" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>' : ""}</td>
                <td><input type="hidden" name="id" id="kardex_hemoglucotest_id${indexRow}" value="0"></td>
                <td><input type="hidden" name="flag_registro" id="flag_registro" value="6"></td>
            </tr>`;
            $('#tableHemoglucotest tbody').append(newRow);

            $('#tableHemoglucotest').on('click', '#btnRemoveRowHemoglucotest', function() {
                const row = $(this).closest('tr');
                const rowId = row.attr('id');
                formMedicamentos = formMedicamentos.filter(obj => String(obj.tr_id) !== String(rowId));
                row.remove();
            });

            if (hemoglucotesParam) {
                $(`#kardex_hemoglucotest_medicamento${indexRow}`).val(hemoglucotesParam.medicamento);
                $(`#kardex_hemoglucotest_frecuencia${indexRow}`).val(hemoglucotesParam.frecuencia);

                $(`#kardex_hemoglucotest_dias${indexRow}`).val(hemoglucotesParam.dias_administracion);
                $(`#kardex_hemoglucotest_dias${indexRow}`).prop('readonly', true);

                $(`#kardex_hemoglucotest_hora${indexRow}`).val(hemoglucotesParam.hora);

                if (parseInt(flag_action_form) === 2) {
                    $(`#kardex_hemoglucotest_frecuencia${indexRow}`).prop('readonly', true);
                    $(`#kardex_hemoglucotest_hora${indexRow}`).prop('readonly', true);
                }

                $(`#kardex_hemoglucotest_id${indexRow}`).val(hemoglucotesParam.id);
            }

            formMedicamentos.push({
                id: indexRow,
                name: `kardex_hemoglucotest_medicamento${indexRow}`,
                container_div: `kardex_hemoglucotest_medicamento_div${indexRow}`,
                required: true,
                tr_id: `tr_hemoglucotest_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_hemoglucotest_frecuencia${indexRow}`,
                container_div: `kardex_hemoglucotest_frecuencia_div${indexRow}`,
                required: true,
                tr_id: `tr_hemoglucotest_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_hemoglucotest_hora${indexRow}`,
                container_div: `kardex_hemoglucotest_hora_div${indexRow}`,
                required: true,
                tr_id: `tr_hemoglucotest_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
            formMedicamentos.push({
                id: indexRow,
                name: `kardex_hemoglucotest_dias${indexRow}`,
                container_div: `kardex_hemoglucotest_dias_div${indexRow}`,
                required: true,
                tr_id: `tr_hemoglucotest_${indexRow}`,
                tab: "mkr_tab_kardex"
            });
        }

        function validateLastRowTable(tableId) {
            let isValid = true;
            $(`#${tableId} tbody tr`).each(function() {
                $(this).find('input:not([type="checkbox"]):not([type="hidden"]), select').each(
                    function() {
                        if ($(this).is('input') && $(this).val().trim() === '') {
                            isValid = false;
                            return false;
                        }
                        if ($(this).is('select') && $(this).val() === '') {
                            isValid = false;
                            return false;
                        }
                    });
                if (!isValid) {
                    return false;
                }
            });
            return isValid;
        }

        function buildOptionsForSelect(data, id, label) {
            let htmlString = `<option value="" selected>--- seleccionar ---</option>`
            if (data && data.length > 0) {
                data.forEach(element => {
                    htmlString = htmlString +
                        `<option value="${element[id]}">${element[label]}</option>`
                });
            }
            return htmlString;
        }

        async function getViasAdministracion() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "GET",
                    url: "/api/via-administracion",
                    dataType: "json",
                    success: function(response) {
                        resolve(response)
                    },
                    error: function(xhr, status, error) {
                        reject(xhr.responseText);
                    }
                });
            });
        }

        async function onClickSaveMedicamentos() {
            try {
                $("#btnSaveFormMedicamento").prop('disabled', true);
                console.log(1);
                const controlsErros = validateFornMedicamento();
                console.log(2);
                if (controlsErros.length > 0) {
                    Swal.fire({
                        icon: "warning",
                        title: '<p>KARDEX MEDICAMENTOS</p>',
                        html: '<p class="descripcion-alert">EXISTE CAMPOS QUE SON NECESARIOS DE LLENAR</p>',
                    });
                    return null;
                }
                console.log(3);
                Swal.fire({
                    html: `<h5>${parseInt(flag_action_form_medicamento) === 1 ? "Guardando medicamentos" : "Actualizando medicamentos"} ...</h5>`,
                    showLoaderOnConfirm: true,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                });
                console.log(4);
                const data = {
                    id_kardex_enfermeria: $("#id").val(),
                    kardexFecha: $("#kardex_fecha").val(),
                    radioFormMedicamento: $('[name="radio_form_medicamento"]').val(),
                    arrayHidratacion: getValuesRowByTable("tableHidratacion"),
                    arrayInfusion: getValuesRowByTable("tableInfusion"),
                    arrayAntibioticos: getValuesRowByTable("tableAntibioticos"),
                    arrayOtros: getValuesRowByTable("tableOtros"),
                    arrayInhaladores: getValuesRowByTable("tableInhaladores"),
                    arrayTranfusiones: getValuesRowByTable("tableTranfusiones"),
                    arrayHemoglucotest: getValuesRowByTable("tableHemoglucotest"),
                };
                console.log(5);
                if (parseInt(flag_action_form) === 1) {
                    formMedicamentosData = data;
                    buildTableViewMedicamentosFormRegister(data);
                } else {
                    const responseUpdate = await updateMedicamento(data);
                    Swal.fire({
                        icon: "success",
                        title: "KARDEX ENFERMERIA",
                        text: `${responseUpdate.message || "OK"}`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                    await refreshTableMedicamentos();
                }
                console.log(6);

                clearFormMedicamentos();
                console.log(7);
                Swal.close();
                $("#modal_kardex_formulario_medicamento").modal('hide');

            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: '<p>KARDEX MEDICAMENTOS</p>',
                    html: `<p class="descripcion-alert">${error}</p>`,
                });
            } finally {
                $('#btnSaveFormMedicamento').prop('disabled', false);
                Swal.hideLoading();
            }

        }

        function updateMedicamento(data) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "PUT",
                    url: "/api/kardex-medicamentos-enfermerias/update-array",
                    contentType: 'application/json',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(data),
                    success: function(result) {
                        resolve(result);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }

        function buildTableViewMedicamentosFormRegister(data) {
            $("#btnShowFormMedicamento").html(
                `<i class="fa fa-pencil-square-o" aria-hidden="true"></i> EDITAR MEDICAMENTOS`);

            $('#tableMedicamentosKardex tbody').empty();
            const $tbody = $('#tableMedicamentosKardex tbody');
            let flag_registro = 0;
            let array = [];

            const session = user = {!! json_encode(Auth::user()) !!};
            const medico = `${session.name || ""} ${session.paterno || ""} ${session.materno || ""}`

            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    array = data[key];
                    if (Array.isArray(data[key]) && array.length > 0) {
                        flag_registro = data[key][0].flag_registro;
                        $tbody.append(
                            `<tr><td colspan="5" class="titulo-categoria" style="background-color: #d0d3d4"><strong>${getCategoryNamebyCategoryId(flag_registro)}</strong></td></tr>`
                        );
                        array.forEach((itemArray, index) => {
                            $tbody.append(returnHtmlString(itemArray, medico));
                        });
                    }
                }
            }
        }

        function validateFornMedicamento() {
            let $control = null;
            let valueControl = null;
            let controlsError = [];
            if (formMedicamentos && formMedicamentos && formMedicamentos.length > 0) {
                formMedicamentos.forEach((control, index) => {
                    $control = $(`#${control.name}`);

                    $(`#${control.container_div}`).removeClass("has-error  has-feedback");
                    $control.siblings('.form-control-feedback, .label-error').remove();
                    $control.nextAll('.label-error').remove();


                    if (control.required) {
                        valueControl = $control.val();
                        if (valueControl === null || valueControl === "" || valueControl ===
                            undefined) {
                            if ($control.is('select') && $control.hasClass('select2')) {
                                $control.next('.select2-container').addClass('input-error');
                                $control.next('.select2-container').after(
                                    `<span class="label-error" style="color: #dc3545; font-size: 11px; font-weight: bold">campo requerido</span>`
                                );
                            } else {
                                $control.after(
                                    `<span class="label-error" style="color: #dc3545; font-size: 11px; font-weight: bold">campo requerido</span>`
                                );
                                $(`#${control.container_div}`).addClass("has-error  has-feedback");
                            }
                            controlsError.push(control);
                        }
                    }
                });
            }
            if (controlsError.length > 0) {
                $(`#${controlsError[0].name}`).focus();
            }
            return controlsError;
        }

        function returnHtmlString(medicamentoParam, medico) {
            let timesString = "";
            if (medicamentoParam.horarios && medicamentoParam.horarios.length > 0) {
                medicamentoParam.horarios.forEach((horario, index) => timesString +=
                    `<b>${moment(horario.hora, "HH:mm:ss").format("HH:mm")}</b>${medicamentoParam.horarios.length > index + 1 ? "- " : ""}`
                );
            }
            let medicamentoName = "";
            switch (parseInt(medicamentoParam.flag_registro)) {
                case 0 || 2 || 3 || 4 || 5 || 6:
                    medicamentoName = medicamentoParam.medicamento
                    break;
                case 1:
                    medicamentoName = medicamentoParam.infusiones_descripcion
                    break;
                default:
                    medicamentoName = medicamentoParam.medicamento
                    break;
            }
            return `
            <tr>
                <td>
                    <p>${medicamentoName || "-"}</p>
                    <p>${timesString}</p>
                </td>
                <td>${medico}</td>
                <td>${medicamentoParam.id_via_administracion_label || "-"}</td>
                <td>${medicamentoParam.frecuencia ? "C/" + medicamentoParam.frecuencia : ""}</td>
                <td>${medicamentoParam.dias_administracion ? medicamentoParam.dias_administracion + "DIAS" : ""}</td>
            </tr>`;
        }

        function buildTableMedicamentosFormEdit(listaMedicamentos) {
            if (listaMedicamentos && listaMedicamentos.length > 0) {
                const firstMedicamento = listaMedicamentos[0];
                $("#kardex_fecha").val(moment(firstMedicamento.fecha, "YYYY-MM-DD").format("DD/MM/YYYY"));

                if (parseInt(firstMedicamento.hidratacion) === 1) {
                    $(`#radio_form_medicamento_option_1`).prop('checked', false);
                    $(`#radio_form_medicamento_option_2`).prop('checked', true);
                } else {
                    $(`#radio_form_medicamento_option_1`).prop('checked', true);
                    $(`#radio_form_medicamento_option_2`).prop('checked', false);
                }

                listaMedicamentos.forEach((medicamento, index) => {
                    if (parseInt(medicamento.flag_registro) === 0) { // hidrataciones
                        onAddRowTableHidratacion(medicamento)
                    }
                    if (parseInt(medicamento.flag_registro) === 1) { // infusiones
                        onAddRowTableInfusion(medicamento)
                    }
                    if (parseInt(medicamento.flag_registro) === 2) { // antibioticos
                        onAddRowTableAnbioticos(medicamento);
                    }
                    if (parseInt(medicamento.flag_registro) === 3) { // otros
                        onAddRowTableOtros(medicamento);
                    }
                    if (parseInt(medicamento.flag_registro) === 4) { // inhaladores
                        onAddRowTableInhaladores(medicamento);
                    }
                    if (parseInt(medicamento.flag_registro) === 5) { // transfuciones
                        onAddRowTableTranfusiones(medicamento);
                    }
                    if (parseInt(medicamento.flag_registro) === 6) { // hemoglutest
                        onAddRowTableHemoglucotest(medicamento); //
                    }
                });
            }
        }

        function buildTableMedicamentosFormRegister(dataFormMedicamento) {
            let itemObject = "";
            $("#kardex_fecha").val(dataFormMedicamento.kardexFecha);
            if (parseInt(dataFormMedicamento.kardexMedicamentoType) === 1) {
                $(`#radio_form_medicamento_option_1`).prop('checked', false);
                $(`#radio_form_medicamento_option_2`).prop('checked', true);
            } else {
                $(`#radio_form_medicamento_option_1`).prop('checked', true);
                $(`#radio_form_medicamento_option_2`).prop('checked', false);
            }
            for (const key in dataFormMedicamento) {
                if (dataFormMedicamento.hasOwnProperty(key)) {
                    itemObject = dataFormMedicamento[key];
                    if (Array.isArray(itemObject) && itemObject.length > 0) {
                        itemObject.forEach((itemArray, index) => {
                            if (parseInt(itemArray.flag_registro) === 0) { // hidrataciones
                                itemArray.frasco = {
                                    I: itemArray["I"] ?? false,
                                    II: itemArray["II"] ?? false,
                                    III: itemArray["III"] ?? false,
                                    IV: itemArray["IV"] ?? false,
                                    V: itemArray["V"] ?? false,
                                    VI: itemArray["VI"] ?? false,
                                };
                                onAddRowTableHidratacion(itemArray);
                            }
                            if (parseInt(itemArray.flag_registro) === 1) { // infusiones
                                onAddRowTableInfusion(itemArray);
                            }
                            if (parseInt(itemArray.flag_registro) === 2) { // antibioticos
                                onAddRowTableAnbioticos(itemArray);
                            }
                            if (parseInt(itemArray.flag_registro) === 3) { // otros
                                onAddRowTableOtros(itemArray);
                            }
                            if (parseInt(itemArray.flag_registro) === 4) { // inhaladores
                                itemArray.terapia_rescate = {
                                    I: itemArray["I"] ?? false,
                                    II: itemArray["II"] ?? false,
                                    III: itemArray["III"] ?? false,
                                    IV: itemArray["IV"] ?? false,
                                    V: itemArray["V"] ?? false,
                                    VI: itemArray["VI"] ?? false,
                                };
                                onAddRowTableInhaladores(itemArray);
                            }
                            if (parseInt(itemArray.flag_registro) === 5) { // transfuciones
                                itemArray.paquetes = {
                                    I: itemArray["I"] ?? false,
                                    II: itemArray["II"] ?? false,
                                    III: itemArray["III"] ?? false,
                                    IV: itemArray["IV"] ?? false,
                                    V: itemArray["V"] ?? false,
                                    VI: itemArray["VI"] ?? false,
                                };
                                onAddRowTableTranfusiones(itemArray);
                            }
                            if (parseInt(itemArray.flag_registro) === 6) { // hemoglutest
                                onAddRowTableHemoglucotest(itemArray); //
                            }
                        });
                    }
                }
            }
        }

        $(document).on("change", ".form-check-input", function(e) {
            const $checkbox = $(this);
            const isNotRequired = $checkbox.is(':checked');
            const $td = $checkbox.closest('td');

            const $td1 = $td.nextAll('td').eq(0);
            const $td2 = $td.nextAll('td').eq(1);

            const id1 = $td1.find('input').attr('id');
            const id2 = $td2.find('input').attr('id');

            const obj1 = formMedicamentos.find(item => item.name === id1);
            const obj2 = formMedicamentos.find(item => item.name === id2);

            if (isNotRequired) {
                if (obj1) {
                    obj1.required = false;
                }
                if (obj2) {
                    obj2.required = false;
                }
            } else {
                if (obj1) {
                    obj1.required = true;
                }
                if (obj2) {
                    obj2.required = true;
                }
            }
        });

    });
</script>
