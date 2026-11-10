<script>
    let formEditMedicamento = [];
     $('#modal_kardex_editar_medicamento').on('shown.bs.modal', async function () {
        try {
            Swal.fire({
                title: "MEDICAMENTOS",
                html: '<h5>OBTENIENDO DATOS DEL MEDICAMENTO...</h5>',
                showLoaderOnConfirm: true,
                onOpen: () => {
                    Swal.showLoading();
                }
            });
            formEditMedicamento = [];
            $("#tableHorarioMedicamento tbody").empty();
            await buildTableHorarios();
        } catch (error) {
            Swal.fire({
                title: "ERROR KARDEX ENFERMERIA",
                html: `<h5>${error}</h5>`,
                showLoaderOnConfirm: true,
            });
        } finally {
            Swal.hideLoading();
            Swal.close();
        }
    });

    $(document).ready(function () {
        $("#btnAddRowTableHorarioMedicamento").on("click", function () {
            onAddRowTableHorarioMedicamento();
        });

        $("#modal_kardex_editar_medicamento_cancel").on("click", function () {
            onCancel();
        });

        $("#modal_kardex_editar_medicamento_ok").on("click", async function () {
            const resultValidate = validateFormEditHorarioMedi();
            if (!resultValidate) {
                Swal.fire({
                    icon: "warning",
                    title: '<p>HORARIO POR MEDICAMENTO</p>',
                    html: '<p class="descripcion-alert">EXISTE CAMPOS QUE SON NECESARIOS DE LLENAR</p>', 
                });
                return false;
            }
            console.log("[resultValidate] =", resultValidate);
            const result = await onSubmit();
            if (result) {
                clearFormHorariosMedicamento();
                $('#modal_kardex_editar_medicamento').modal('hide');
                Swal.fire({
                    icon: "success",
                    title: "HORARIO POR MEDICAMENTO",
                    text: `${result.message || "OK"}`,
                    confirmButtonText: 'ENTENDIDO',
                });
                await refreshTableMedicamentos();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "HORARIO POR MEDICAMENTO",
                    text: `Ocurrio un error, intentelo en unos minutos`,
                    confirmButtonText: 'ENTENDIDO',
                });
            }
            
        });
    });

    function onAddRowTableHorarioMedicamento(horario = null) {
        if (!horario) {
            const resultValidateLastRow = validateLastRowTableHorarios();
            if (!resultValidateLastRow) {
                Swal.fire({
                    icon: "warning",
                    title: '<p>HORARIO POR MEDICAMENTO</p>',
                    html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO HORARIO</p>', 
                });
                return null;
            }
        }
        const indexRow = $('#tableHorarioMedicamento tbody tr').length + 1;
        const newRow = `
        <tr id="tr_edit_medicamento_${indexRow}">
            <td id="editar_medicamento_fecha_div${indexRow}"><input type="text" name="editar_medicamento_fecha[]" id="editar_medicamento_fecha${indexRow}" class="form-control input-sm"></td>
            <td id="editar_medicamento_hora_div${indexRow}"><input type="time" name="editar_medicamento_hora[]" id="editar_medicamento_hora${indexRow}" class="form-control input-sm"></td>
            <td>
                <div class="radio" style="margin-top: -2px">
                    <input type="radio" name="editar_medicamento_aplicable${indexRow}" id="editar_medicamento_aplicable_option_1${indexRow}" data-id_ref="editar_medicamento_aplicable" value="1">
                    <label class="form-check-label" for="editar_medicamento_aplicable_option_1${indexRow}" style="margin-left: 20px">SI</label>
                </div>
                <div class="radio">
                    <input type="radio" name="editar_medicamento_aplicable${indexRow}" id="editar_medicamento_aplicable_option_2${indexRow}" data-id_ref="editar_medicamento_aplicable" value="0">
                    <label class="form-check-label" for="editar_medicamento_aplicable_option_2${indexRow}" style="margin-left: 20px">NO</label>
                </div>
            </td>
            <td><div class="form-check"><input type="checkbox" class="form-check-input" name="editar_medicamento_ram[]" id="editar_medicamento_ram${indexRow}" value="true"></div></td>
            <td>
                <div id="horario_medicamentos_td_frasco${indexRow}">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editar_medicamento_frasco_I${indexRow}" name="editar_medicamento_frasco_I" value="true">
                        <label class="form-check-label" for="editar_medicamento_frasco_I${indexRow}" style="margin-left: 20px">I</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editar_medicamento_frasco_II${indexRow}" name="editar_medicamento_frasco_II" value="true">
                        <label class="form-check-label" for="editar_medicamento_frasco_II${indexRow}" style="margin-left: 20px">II</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editar_medicamento_frasco_III${indexRow}" name="editar_medicamento_frasco_III" value="true">
                        <label class="form-check-label" for="editar_medicamento_frasco_III${indexRow}" style="margin-left: 20px">III</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editar_medicamento_frasco_IV${indexRow}" name="editar_medicamento_frasco_IV" value="true">
                        <label class="form-check-label" for="editar_medicamento_frasco_IV${indexRow}" style="margin-left: 20px">IV</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editar_medicamento_frasco_V${indexRow}" name="editar_medicamento_frasco_V" value="true">
                        <label class="form-check-label" for="editar_medicamento_frasco_V${indexRow}" style="margin-left: 20px">V</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editar_medicamento_frasco_VI${indexRow}" name="editar_medicamento_frasco_VI" value="true">
                        <label class="form-check-label" for="editar_medicamento_frasco_VI${indexRow}" style="margin-left: 20px">VI</label>
                    </div>
                </div>
            </td>
            <td><input type="text" name="editar_medicamento_observacion[]" id="editar_medicamento_observacion${indexRow}" class="form-control input-sm"></td>
            <td>${!horario ? '<button id="btnRemoveRowHorarioMedicamento" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>': ""}</td>
            <td><input type="hidden" name="editar_medicamento_index[]" id="editar_medicamento_index${indexRow}" value="${indexRow}" data-id=${indexRow}></td>
            <td><input type="hidden" name="editar_medicamento_id[]" id="editar_medicamento_id${indexRow}"></td>
        </tr>`;

        $('#tableHorarioMedicamento tbody').append(newRow);

        const flag_registro = $("#flag_registro").val();
        console.log("object");
        if (parseInt(flag_registro) === 0) {
            $(`#horario_medicamentos_td_frasco${indexRow}`).show();
        } else {
            $(`#horario_medicamentos_td_frasco${indexRow}`).hide();
        }

        $(`#editar_medicamento_fecha${indexRow}`).datepicker({
            useCurrent: true,
            format: 'dd/mm/yyyy', 
            todayHighlight: true,
            autoclose: true,  
            placeholder: "dd/mm/yyyy",
        });

        $('#tableHorarioMedicamento').on('click', '#btnRemoveRowHorarioMedicamento', function(){
            const row = $(this).closest('tr');
            const rowId = row.attr('id');
            formEditMedicamento = formEditMedicamento.filter(obj => String(obj.tr_id) !== String(rowId));
            row.remove();
        });

        if (horario) {
            $(`#editar_medicamento_fecha${indexRow}`).datepicker("setDate", moment(horario.fecha, "YYYY-MM-DD").format("DD/MM/YYYY"));
            $(`#editar_medicamento_hora${indexRow}`).val(horario.hora)
            // $(`#editar_medicamento_hora${indexRow}`).val(horario.hora).attr("readonly", true);

            if (horario.aplicacion && parseInt(horario.aplicacion) === 1) {
                $(`#editar_medicamento_aplicable_option_1${indexRow}`).prop('checked', true);
            } else {
                $(`#editar_medicamento_aplicable_option_2${indexRow}`).prop('checked', true);
            }

            if (horario.ram && parseInt(horario.ram) === 1) {
                $(`#editar_medicamento_ram${indexRow}`).prop('checked', true);
            }

            $(`#editar_medicamento_observacion${indexRow}`).val(horario.observacion ?? "");

            if (horario.frasco) {
                const frascoHorarioJson = JSON.parse(horario.frasco);
                Object.entries(frascoHorarioJson).forEach(([clave, valor]) => {
                    if (valor) $(`#editar_medicamento_frasco_${clave}${indexRow}`).prop('checked', true);
                });
            }

            $(`#editar_medicamento_id${indexRow}`).val(horario.id);
        } else {
            $(`#editar_medicamento_aplicable_option_2${indexRow}`).prop('checked', true);
        }

        formEditMedicamento.push({id: indexRow, name: `editar_medicamento_fecha${indexRow}`,container_div: `editar_medicamento_fecha_div${indexRow}`, required: true, tr_id: `tr_edit_medicamento_${indexRow}`});
        formEditMedicamento.push({id: indexRow, name: `editar_medicamento_hora${indexRow}`,container_div: `editar_medicamento_hora_div${indexRow}`, required: true, tr_id: `tr_edit_medicamento_${indexRow}`});
    }

    function onSubmit() {
        return new Promise((resolve, reject) => {
            const data = buildRequestSaveAndUpdate();
            console.log("[data] => => ", data);
            $.ajax({
                type: "PUT",
                url: "/api/horarios-kardex-medicamentos/update-array",
                contentType: 'application/json',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify(data),
                beforeSend: function() {
                    $("#modal_kardex_editar_medicamento_ok").prop('disabled', true);
                    Swal.fire({
                        html: '<h5>Actualizando registro...</h5>',
                        showLoaderOnConfirm: true,
                        onOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                },
                complete: function() {
                    $('#modal_kardex_editar_medicamento_ok').prop('disabled', false);
                    Swal.hideLoading();
                }
            });
        });
    }

    function onCancel() {
        clearFormHorariosMedicamento();
    }

    function clearFormHorariosMedicamento() {
        $("#tableHorarioMedicamento tbody").empty();
    }

    function getHorariosByMedicamentoId(id_medicamento) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: `/api/horarios-kardex-medicamentos/kardex_medicamentos_enfermerias/${id_medicamento}`,
                dataType: "json",
                success: function (response) {
                    resolve(response);
                },
                error: function (xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    async function buildTableHorarios() {
        const kardex_enfermerias_id = $("#id").val();
        const id_medicamento = $("#id_medicamento").val();
        const horarios = await getHorariosByMedicamentoId(id_medicamento);
        if (horarios && horarios.length > 0) {
            horarios.forEach((horario, index) => {
                onAddRowTableHorarioMedicamento(horario);
            });
        }
    }

    function getValuesRowByTable(tableName) {
        const tableHidratacionRows = $(`#${tableName} tbody tr`);
        const resultados  = [];
        if (tableHidratacionRows && tableHidratacionRows.length > 0) {
        tableHidratacionRows.each(function(index, fila) {
            let valores = {};
            $(fila).find('input, select').each(function() {
                let input = $(this);
                let nombre = input.attr('name');
                let valor;

                if (input.is(':checkbox')) {
                    valor = input.is(':checked');
                    valores[input.attr('name')] = valor;
                } else if (input.is('select')) {
                    valor = input.find('option:selected').val();
                    valores[input.attr('name')] = valor;
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
            resultados.push(valores);
        });
        }
        return resultados ;
    }

    function buildRequestSaveAndUpdate() {
        let obj = {
            arrayHorario: getValuesRowByTable("tableHorarioMedicamento"),
            id_kardex: $("#id_kardex").val(),
            id_medicamento: $("#id_medicamento").val(),
        }
        return obj
    }

    function validateFormEditHorarioMedi() {
        let $control = null;
        let valueControl = null;
        let controlsError = [];
        formEditMedicamento.forEach((control, index) => {
            $control = $(`#${control.name}`);

            $(`#${control.container_div}`).removeClass("has-error  has-feedback");
            $control.siblings('.form-control-feedback, .label-error').remove();
            $control.nextAll('.label-error').remove();
        
            if (control.required) {
                valueControl = $control.val();
                if (valueControl === null || valueControl === "" || valueControl === undefined) {
                    $control.after(`<span class="label-error" style="color: #dc3545; font-size: 11px; font-weight: bold">campo requerido</span>`);
                    $(`#${control.container_div}`).addClass("has-error  has-feedback");
                    controlsError.push(control);
                }
            }

        });
        
        if (controlsError.length > 0) {
            $(`#${controlsError[0].name}`).focus();
            return false;
        }
        return true;
    }

    function validateLastRowTableHorarios() {
        var rowCount = $('#tableHorarioMedicamento tbody').find('tr').length;
        const lastTr = $('#tableHorarioMedicamento tbody tr').last();
        if (rowCount > 0 && lastTr) {
            const fecha = lastTr.find('input[name="editar_medicamento_fecha[]"]').val();
            const hora = lastTr.find('input[name="editar_medicamento_hora[]"]').val();
            if (!fecha || !hora) return false;
            return true;
        }
        return true;
    }
</script>