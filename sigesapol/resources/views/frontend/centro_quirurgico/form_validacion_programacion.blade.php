<style>
    /* .intro-contenedor {
        padding-top: 1rem;
    } */
</style>
<script>
    $(document).ready(function() {
        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
            let requiereInternamientoPreQx = {{ $intervencion->is_requiere_internamiento_preqx == 1 ?: 0 }};

            if (requiereInternamientoPreQx) {
                $('#addRequiereInternamientoPreQx').attr("aria-pressed", "true");
                $('#addRequiereInternamientoPreQx').addClass("active");

                $("#has_requiere_internamiento_preqx").val("1");
                $("#fsRequiereInternamientoPreQx").show();
            } else {
                $("#has_requiere_internamiento_preqx").val("0");
                $("#fsRequiereInternamientoPreQx").hide();
            }

            let diasHospitalizadoPostQx = {{ $intervencion->is_hospitalizacion_postqx == 1 ?: 0 }};
            let toggleButton2 = document.getElementById('addDiasHospitalizadoPostQx');

            if (diasHospitalizadoPostQx) {
                $("#is_requiere_hospitalizacion_postqx").val("1");
                $('#addDiasHospitalizadoPostQx').attr("aria-pressed", "true");
                $('#addDiasHospitalizadoPostQx').addClass("active");
                $("#fsDiasHospitalizadoPostQx").show();
            } else {
                $("#is_requiere_hospitalizacion_postqx").val("0");
                $("#fsDiasHospitalizadoPostQx").hide();
            }

            let id_prioridad_programacion =
                {{ !empty($intervencion->id_prioridad_programacion) && $intervencion->id_prioridad_programacion > 0 ? $intervencion->id_prioridad_programacion : 0 }};
            if (id_prioridad_programacion > 0) {
                $('#prioridadProgramacionsemanas').attr("aria-pressed", "true");
                $('#prioridadProgramacionsemanas').addClass("active");
                $("#has_prioridad_programacion_semanas").val("1");
                $("#fsPrioridadProgramacionSemanas").show();
            } else {
                $("#has_prioridad_programacion_semanas").val("0");
                $("#fsPrioridadProgramacionSemanas").hide();
            }
        @endif

        // $("#fecha_intervencion").datepicker({
        //     dateFormat: 'dd-mm-yy',
        //     minDate: 0,
        //     autoclose: true,
        //     changeMonth: true,
        //     changeYear: true
        // });

        $('#addRequiereInternamientoPreQx').on('click', function() {
            let addRequiereInternamientoPreQx = $('#addRequiereInternamientoPreQx').attr(
            "aria-pressed");
            $("#has_requiere_internamiento_preqx").val("0");
            $("#fsRequiereInternamientoPreQx").hide();
            if (addRequiereInternamientoPreQx == "false") {
                $("#has_requiere_internamiento_preqx").val("1");
                $("#fsRequiereInternamientoPreQx").show();
            }
        });

        $('#addDiasHospitalizadoPostQx').on('click', function() {
            let addDiasHospitalizadoPostQx = $('#addDiasHospitalizadoPostQx').attr("aria-pressed");
            $("#is_requiere_hospitalizacion_postqx").val("0");
            $("#fsDiasHospitalizadoPostQx").hide();
            if (addDiasHospitalizadoPostQx == "false") {
                $("#is_requiere_hospitalizacion_postqx").val("1");
                $("#fsDiasHospitalizadoPostQx").show();
            }
        });

        $('#addSugerenciaMedica').on('click', function() {
            let addSugerenciaMedica = $('#addSugerenciaMedica').attr("aria-pressed");
            $("#is_sugerencia_medica").val("0");
            $("#fsSugerenciaMedica").hide();
            if (addSugerenciaMedica == "false") {
                $("#is_sugerencia_medica").val("1");
                $("#fsSugerenciaMedica").show();
            }
        });
        $('#addObservado').on('click', function() {
            let addObservado = $('#addObservado').attr("aria-pressed");
            $("#is_observado").val("0");
            $("#fsObservado").hide();
            $('#programacion_validacion').find('input, select, textarea, button').prop('disabled',
                false);
            if (addObservado == "false") {
                $("#is_observado").val("1");
                $("#fsObservado").show();
                //DISABLED LOS DEMAS CAMPOS (REGISTRAR PROGRAMACIÓN QUIRÚRGICA)
                $('#programacion_validacion').find('input, select, textarea, button').prop('disabled',
                    true);

            }
        });

    });

    @if ($is_origen_emergencia)
        //DIAGNOSTICOS CQX
        $('#addDiagnosticoQuirurgico').on('click', function() {
            AddDiagnosticoQuirurgico();
        });
        let tomSelectInstancesDiagnostico = {};

        function AddDiagnosticoQuirurgico() {
            var newRow = "";
            var ind = $('#tblDiagnosticoQuirurgico tbody tr').length;

            if (ind > 0) {
                $('.id_diagnostico_quirurgico').each(function() {
                    var ind_tmp = $(this).data("id");
                    if (ind_tmp => ind) {
                        ind = Number(ind_tmp) + 1;
                    }
                });

                fila_medicamentos = $('#tblDiagnosticoQuirurgico tbody').children('tr');
                rowIndex = $('#hidden_rowIndex').val();
                var idval = ind - 1;
                fila_medicamento = fila_medicamentos[idval];

            }

            var item_tipo_diagnostico = '';

            item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';

            newRow += '<tr>';
            newRow +=
                '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_quirurgico[]" tabindex="8" required="" id="nombre_diagnostico_quirurgico' +
                ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
            newRow +=
                '<input type="hidden" class="form-control id_diagnostico_quirurgico typeahead tt-query" id="id_diagnostico_quirurgico' +
                ind + '" name="id_diagnostico_quirurgico[]" value="" data-id=' + ind + '></td>';

            newRow +=
                '<td><select class="form-control id_tipo_diagnostico_quirurgico" id="cmbTipoDiagnosticoQuirurgico' +
                ind + '" name="id_tipo_diagnostico_quirurgico[]" style="margin:0 20px;width:150px">' +
                item_tipo_diagnostico + '</select></td>';
            newRow +=
                '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
            newRow += '</tr>';
            $('#tblDiagnosticoQuirurgico tbody').append(newRow);

            var sexo = $('#sexo').val();
            if (sexo == 'M') {
                genero = 1;
            } else {
                if (sexo == 'F') {
                    genero = 2;
                }
            }

            $("#nombre_diagnostico_quirurgico" + ind).autocomplete({
                minLength: 1,
                delay: 400,
                source: function(request, response) {
                    $.ajax({
                        //url: '../obtener_diagnostico/' + request.term,
                        url: '../../obtener_diagnostico_genero/' + request.term + '/' + genero,
                        dataType: "json",
                        success: function(data) {
                            let listaDiagnosticoSeleccionadoCQX = $(
                                ".id_diagnostico_quirurgico").map(function() {
                                return parseInt($(this).val());
                            }).get();
                            let filtro = data.filter((element) => !
                                listaDiagnosticoSeleccionadoCQX.includes(element.id));
                            response(filtro);
                        }
                    })

                },
                select: function(e, ui) {
                    var id_diagnostico = ui.item.id;
                    $("#id_diagnostico_quirurgico" + ind).val(id_diagnostico);
                    $("#nombre_diagnostico_quirurgico" + ind).prop("readonly", true);
                }
            });

            $("#nombre_diagnostico_quirurgico" + ind).on('blur', function() {
                const diagnostico = $("#nombre_diagnostico_quirurgico" + ind).val();

                const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
                const codigoDiagnostico = diagnostico.split("-")[0].trim();
                var id_diagnostico = $("#id_diagnostico_quirurgico" + ind).val();
                if (id_diagnostico == "") {
                    //bootbox.alert("Falta ingresar el diagnostico en esta fila");
                    let currentTS = tomSelectInstancesDiagnostico["id_diagnostico_quirurgico" + ind];
                    let modal = $(this).closest('.modal');
                    $('body').addClass('modal-open');
                    bootbox.alert({
                        message: "Falta ingresar el diagnostico en esta fila",
                        callback: function() {
                            modal.modal('show');
                            $('body').addClass('modal-open');
                            if (currentTS) {
                                currentTS.clear();
                                currentTS.setTextboxValue('');
                                currentTS.blur();
                            }
                        }
                    }).on('hidden.bs.modal', function() {
                        modal.modal('show');
                        $('body').addClass('modal-open');
                    });

                    $("#nombre_diagnostico_quirurgico" + ind).val("");
                    return false;
                } else {
                    // if (firstLetterCode == "Z" || flag_saludpol == 0) {
                    //     bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado por SALUDPOL.");
                    // }
                    if (codigoDiagnostico == "Y66.X") {
                        //bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado.");
                        let currentTS = tomSelectInstancesDiagnostico["id_diagnostico_quirurgico" + ind];
                        let modal = $(this).closest('.modal');
                        $('body').addClass('modal-open');
                        bootbox.alert({
                            message: "No se recomienda seleccionar ese diagnostico, ya que puede ser observado",
                            callback: function() {
                                modal.modal('show');
                                $('body').addClass('modal-open');
                                if (currentTS) {
                                    currentTS.clear();
                                    currentTS.setTextboxValue('');
                                    currentTS.blur();
                                }
                            }
                        }).on('hidden.bs.modal', function() {
                            modal.modal('show');
                            $('body').addClass('modal-open');
                        });

                        $("#nombre_diagnostico_quirurgico" + ind).val("");
                        return false;
                    }
                }
            });
        }
        $('#tblDiagnosticoQuirurgico tbody').on('click', 'button.deleteFila', function() {
            var obj = $(this);
            obj.parent().parent().remove();
        });
        //PROCEDIMIENTOS CQX
        $('#addProcedimientoIntervencionEmergencia').on('click', function() {
            AddProcedimientoIntervencionEmergencia();
        });
        let tomSelectInstancesProcedimiento = {};

        function AddProcedimientoIntervencionEmergencia() {
            let newRow = "";
            let ind = $('#table_procedimiento_intervencion_emergencia tbody tr').length;

            let item_procedimiento = "";
            $('#cmbProcedimientoIntervencionTempEmergencia option').each(function() {
                item_procedimiento += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
            });

            newRow += '<tr>';
            newRow += '<td><select class="select-tom cmbProcedimientoIntervencion" id="cmbProcedimientoIntervencion' +
                ind + '" ind="' + ind + '" name="id_procedimiento_intervencion[]" >' + item_procedimiento +
                '</select></td>';
            newRow +=
                '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:10px;"><i class="fa fa-times"></i> Eliminar </button></td>';

            newRow += '</tr>';
            $('#table_procedimiento_intervencion_emergencia tbody').append(newRow);
            const select_procedimiento = new TomSelect("#cmbProcedimientoIntervencion" + ind, {
                maxItems: 1,
                plugins: ['clear_button']
            });
            tomSelectInstancesProcedimiento["cmbProcedimientoIntervencion" + ind] = select_procedimiento;
            select_procedimiento.focus();


            $("#cmbProcedimientoIntervencion" + ind).on("change", function(e) {

                let id_procedimiento = $(this).val();
                let flagx = 0;
                let currentTS = tomSelectInstancesProcedimiento["cmbProcedimientoIntervencion" + ind];

                $('.cmbProcedimientoIntervencion').each(function() {
                    if ($(this).val() === id_procedimiento) flagx++;
                });

                if (id_procedimiento === null || id_procedimiento === "") {
                    if (currentTS) {
                        currentTS.setTextboxValue('');
                        currentTS.blur();
                    }
                    return;
                }

                if (flagx > 1) {
                    let modal = $(this).closest('.modal');
                    $('body').addClass('modal-open');
                    bootbox.alert({
                        message: "El Procedimiento ya ha sido ingresado",
                        callback: function() {
                            modal.modal('show');
                            $('body').addClass('modal-open');
                            if (currentTS) {
                                currentTS.clear();
                                currentTS.setTextboxValue('');
                                currentTS.blur();
                            }
                        }
                    }).on('hidden.bs.modal', function() {
                        modal.modal('show');
                        $('body').addClass('modal-open');
                    });
                }

            });
            item_procedimiento++;
        }
        $('#table_procedimiento_intervencion_emergencia tbody').on('click', 'button.deleteFila', function() {
            var obj = $(this);
            var fila = obj.closest('tr');
            var filaPosition = fila.index();
            obj.parent().parent().remove();
        });

        $('#addMedicoCirujanoEmergencia').on('click', function() {
            AddMedicoCirujanoEmergencia();
        });

        let tomSelectInstancesCirujano = {};

        function AddMedicoCirujanoEmergencia() {
            var newRow = "";
            var ind = $('#table_medico_cirujano_emergencia tbody tr').length;

            let item_profesional = "";
            $('#cmbProfesionalesCirujanosTodosTempEmergencia option').each(function() {
                item_profesional += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
            });

            let item_tipo_profesional = "";
            $('#cmbTipoProfesionalCirujanoTempEmergencia option').each(function() {
                item_tipo_profesional += "<option value='" + $(this).val() + "'>" + $(this).html() +
                "</option>";
            });

            newRow += '<tr>';
            newRow += '<td><select class="select-tom cmbMedicoCirujanoTodos" id="id_medico_cirujano' + ind + '" ind="' +
                ind + '" name="id_medico_cirujano[]" >' + item_profesional + '</select></td>';
            newRow +=
                '<td><select class="form-control cmbCargoMedicoCirujanoTodos input-sm" id="id_medico_cirujano_cargo' +
                ind + '" name="id_medico_cirujano_cargo[]" style="margin:0 20px;width:150px">' + item_tipo_profesional +
                '</select></td>';

            newRow +=
                '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar </button></td>';

            newRow += '</tr>';
            $('#table_medico_cirujano_emergencia tbody').append(newRow);

            const select_medico_cirujano = new TomSelect("#id_medico_cirujano" + ind, {
                maxItems: 1,
                plugins: ['clear_button']
            });
            tomSelectInstancesCirujano["id_medico_cirujano" + ind] = select_medico_cirujano;
            select_medico_cirujano.focus();

            $("#id_medico_cirujano" + ind).on("change", function(e) {

                let id_medico_cirujano = $(this).val();
                let flagx = 0;
                let currentTS = tomSelectInstancesCirujano["id_medico_cirujano" + ind];

                $('.cmbMedicoCirujanoTodos').each(function() {
                    if ($(this).val() === id_medico_cirujano) flagx++;
                });
                if (id_medico_cirujano === null || id_medico_cirujano === "") {
                    if (currentTS) {
                        currentTS.setTextboxValue('');
                        currentTS.blur();
                    }
                    return;
                }

                if (flagx > 1) {
                    let modal = $(this).closest('.modal');
                    $('body').addClass('modal-open');

                    bootbox.alert({
                        message: "El Médico ya ha sido ingresado",
                        callback: function() {
                            modal.modal('show');
                            $('body').addClass('modal-open');
                            if (currentTS) {
                                currentTS.clear();
                                currentTS.setTextboxValue('');
                                currentTS.blur();
                            }
                        }
                    }).on('hidden.bs.modal', function() {
                        modal.modal('show');
                        $('body').addClass('modal-open');
                    });

                    // let modal = $(this).closest('.modal');

                    // $('body').addClass('modal-open');

                    // bootbox.alert({
                    //     message: "El Médico ya ha sido ingresado",
                    //     callback: function() {
                    //         modal.modal('show');
                    //         $('body').addClass('modal-open');
                    //     }
                    // }).on('hidden.bs.modal', function() {
                    //     modal.modal('show');
                    //     $('body').addClass('modal-open');
                    // });

                    // $("#id_medico_cirujano" + ind).val("").trigger("change");
                }
            });
            // filasmedicamentos++;
        }

        $('#table_medico_cirujano_emergencia tbody').on('click', 'button.deleteFila', function() {
            var obj = $(this);
            var fila = obj.closest('tr');
            var filaPosition = fila.index();
            obj.parent().parent().remove();
        });
    @else
        $('#addProcedimientoIntervencion').on('click', function() {
            AddProcedimientoIntervencion();
        });

        $('#addMedicoCirujano').on('click', function() {
            AddMedicoCirujano();
        });

        function AddProcedimientoIntervencion() {
            let newRow = "";
            let ind = $('#table_procedimiento_intervencion tbody tr').length;

            let item_procedimiento = "";
            $('#cmbProcedimientoIntervencionTemp option').each(function() {
                item_procedimiento += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
            });

            newRow += '<tr>';
            newRow += '<td><select class="form-control cmbProcedimientoIntervencion" id="cmbProcedimientoIntervencion' +
                ind + '" ind="' + ind + '" name="id_procedimiento_intervencion[]" style="width:100%">' +
                item_procedimiento + '</select></td>';
            newRow +=
                '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:10px;"><i class="fa fa-times"></i> </button></td>';
            newRow += '</tr>';

            $('#table_procedimiento_intervencion tbody').append(newRow);
            $("#cmbProcedimientoIntervencion" + ind).select2({
                max_selected_options: 4
            }).focus();

            $("#cmbProcedimientoIntervencion" + ind).on("change", function(e) {
                let flagx = 0;
                let cmb = $(this);
                let id_producto = $("#cmbProcedimientoIntervencion" + ind).val();

                $('.cmbProcedimientoIntervencion').each(function() {
                    var ind_tmp = $(this).val();
                    if ($(this).val() == id_producto) flagx++;
                });

                if (flagx > 1) {
                    let modal = $(this).closest('.modal');

                    $('body').addClass('modal-open');

                    bootbox.alert({
                        message: "El Procedimiento ya ha sido ingresado",
                        callback: function() {
                            modal.modal('show');
                            $('body').addClass('modal-open');
                        }
                    }).on('hidden.bs.modal', function() {
                        modal.modal('show');
                        $('body').addClass('modal-open');
                    });

                    $("#cmbProcedimientoIntervencion" + ind).val("0").trigger("change");
                }
            });
        }

        $('#table_procedimiento_intervencion tbody').on('click', 'button.deleteFila', function() {
            var obj = $(this);
            // Selecciona la fila completa
            var fila = obj.closest('tr');

            // Obtiene la posición de la fila
            var filaPosition = fila.index();

            // Elimina la fila
            obj.parent().parent().remove();
        });

        function AddMedicoCirujano() {
            var newRow = "";
            var ind = $('#table_medico_cirujano tbody tr').length;

            let item_profesional = "";
            $('#cmbProfesionalesCirujanosTodosTemp option').each(function() {
                item_profesional += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
            });

            let item_tipo_profesional = "";
            $('#cmbTipoProfesionalCirujanoTemp option').each(function() {
                item_tipo_profesional += "<option value='" + $(this).val() + "'>" + $(this).html() +
                "</option>";
            });

            newRow += '<tr>';
            newRow += '<td><select class="form-control cmbMedicoCirujanoTodos" id="id_medico_cirujano' + ind +
                '" ind="' + ind + '" name="id_medico_cirujano[]" >' + item_profesional + '</select></td>';
            newRow +=
                '<td><select class="form-control cmbCargoMedicoCirujanoTodos input-sm" id="id_medico_cirujano_cargo' +
                ind + '" name="id_medico_cirujano_cargo[]" style="margin:0 20px;width:150px">' + item_tipo_profesional +
                '</select></td>';

            newRow +=
                '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';

            newRow += '</tr>';
            $('#table_medico_cirujano tbody').append(newRow);
            $("#id_medico_cirujano" + ind).select2({
                max_selected_options: 4
            }).focus();

            $("#id_medico_cirujano" + ind).on("change", function(e) {
                var flagx = 0;
                cmb = $(this);
                id_producto = $("#id_medico_cirujano" + ind).val();

                $('.cmbMedicoCirujanoTodos').each(function() {
                    var ind_tmp = $(this).val();
                    if ($(this).val() == id_producto) flagx++;
                });

                if (flagx > 1) {
                    let modal = $(this).closest('.modal');

                    $('body').addClass('modal-open');

                    bootbox.alert({
                        message: "El Médico ya ha sido ingresado",
                        callback: function() {
                            modal.modal('show');
                            $('body').addClass('modal-open');
                        }
                    }).on('hidden.bs.modal', function() {
                        modal.modal('show');
                        $('body').addClass('modal-open');
                    });

                    $("#id_medico_cirujano" + ind).val("").trigger("change");
                }
            });
            // filasmedicamentos++;
        }

        $('#table_medico_cirujano tbody').on('click', 'button.deleteFila', function() {
            var obj = $(this);
            // Selecciona la fila completa
            var fila = obj.closest('tr');

            // Obtiene la posición de la fila
            var filaPosition = fila.index();

            // Elimina la fila
            obj.parent().parent().remove();
            // filasmedicamentos--;
        });

        $('#prioridadProgramacionsemanas').on('click', function() {
            let prioridadProgramacionsemanas = $('#prioridadProgramacionsemanas').attr("aria-pressed");
            $("#has_prioridad_programacion_semanas").val("0");
            $("#fsPrioridadProgramacionSemanas").hide();
            if (prioridadProgramacionsemanas == "false") {
                $("#has_prioridad_programacion_semanas").val("1");
                $("#fsPrioridadProgramacionSemanas").show();
            }
        });

        @if ($intervencion->es_nuevo_admision_hospitalizacion)
            $('#addDiagnosticoQuirurgico').on('click', function() {
                AddDiagnosticoQuirurgico();
            });
            let tomSelectInstancesDiagnostico = {};

            function AddDiagnosticoQuirurgico() {
                var newRow = "";
                var ind = $('#tblDiagnosticoQuirurgico tbody tr').length;

                if (ind > 0) {
                    $('.id_diagnostico_quirurgico').each(function() {
                        var ind_tmp = $(this).data("id");
                        if (ind_tmp => ind) {
                            ind = Number(ind_tmp) + 1;
                        }
                    });

                    fila_medicamentos = $('#tblDiagnosticoQuirurgico tbody').children('tr');
                    rowIndex = $('#hidden_rowIndex').val();
                    var idval = ind - 1;
                    fila_medicamento = fila_medicamentos[idval];

                }

                var item_tipo_diagnostico = '';

                item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';

                newRow += '<tr>';
                newRow +=
                    '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_quirurgico[]" tabindex="8" required="" id="nombre_diagnostico_quirurgico' +
                    ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
                newRow +=
                    '<input type="hidden" class="form-control id_diagnostico_quirurgico typeahead tt-query" id="id_diagnostico_quirurgico' +
                    ind + '" name="id_diagnostico_quirurgico[]" value="" data-id=' + ind + '></td>';

                newRow +=
                    '<td><select class="form-control id_tipo_diagnostico_quirurgico" id="cmbTipoDiagnosticoQuirurgico' +
                    ind + '" name="id_tipo_diagnostico_quirurgico[]" style="margin:0 20px;width:150px">' +
                    item_tipo_diagnostico + '</select></td>';
                newRow +=
                    '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
                newRow += '</tr>';
                $('#tblDiagnosticoQuirurgico tbody').append(newRow);

                var sexo = $('#sexo').val();
                if (sexo == 'M') {
                    genero = 1;
                } else {
                    if (sexo == 'F') {
                        genero = 2;
                    }
                }

                $("#nombre_diagnostico_quirurgico" + ind).autocomplete({
                    minLength: 1,
                    delay: 400,
                    source: function(request, response) {
                        $.ajax({
                            //url: '../obtener_diagnostico/' + request.term,
                            url: '../../obtener_diagnostico_genero/' + request.term + '/' + genero,
                            dataType: "json",
                            success: function(data) {
                                let listaDiagnosticoSeleccionadoCQX = $(
                                    ".id_diagnostico_quirurgico").map(function() {
                                    return parseInt($(this).val());
                                }).get();
                                let filtro = data.filter((element) => !
                                    listaDiagnosticoSeleccionadoCQX.includes(element.id));
                                response(filtro);
                            }
                        })

                    },
                    select: function(e, ui) {
                        var id_diagnostico = ui.item.id;
                        $("#id_diagnostico_quirurgico" + ind).val(id_diagnostico);
                        $("#nombre_diagnostico_quirurgico" + ind).prop("readonly", true);
                    }
                });

                $("#nombre_diagnostico_quirurgico" + ind).on('blur', function() {
                    const diagnostico = $("#nombre_diagnostico_quirurgico" + ind).val();

                    const firstLetterCode = diagnostico.split("-")[0].trim().split("")[0];
                    const codigoDiagnostico = diagnostico.split("-")[0].trim();
                    var id_diagnostico = $("#id_diagnostico_quirurgico" + ind).val();
                    if (id_diagnostico == "") {
                        //bootbox.alert("Falta ingresar el diagnostico en esta fila");
                        let currentTS = tomSelectInstancesDiagnostico["id_diagnostico_quirurgico" + ind];
                        let modal = $(this).closest('.modal');
                        $('body').addClass('modal-open');
                        bootbox.alert({
                            message: "Falta ingresar el diagnostico en esta fila",
                            callback: function() {
                                modal.modal('show');
                                $('body').addClass('modal-open');
                                if (currentTS) {
                                    currentTS.clear();
                                    currentTS.setTextboxValue('');
                                    currentTS.blur();
                                }
                            }
                        }).on('hidden.bs.modal', function() {
                            modal.modal('show');
                            $('body').addClass('modal-open');
                        });

                        $("#nombre_diagnostico_quirurgico" + ind).val("");
                        return false;
                    } else {
                        // if (firstLetterCode == "Z" || flag_saludpol == 0) {
                        //     bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado por SALUDPOL.");
                        // }
                        if (codigoDiagnostico == "Y66.X") {
                            //bootbox.alert("No se recomienda seleccionar ese diagnostico, ya que puede ser observado.");
                            let currentTS = tomSelectInstancesDiagnostico["id_diagnostico_quirurgico" + ind];
                            let modal = $(this).closest('.modal');
                            $('body').addClass('modal-open');
                            bootbox.alert({
                                message: "No se recomienda seleccionar ese diagnostico, ya que puede ser observado",
                                callback: function() {
                                    modal.modal('show');
                                    $('body').addClass('modal-open');
                                    if (currentTS) {
                                        currentTS.clear();
                                        currentTS.setTextboxValue('');
                                        currentTS.blur();
                                    }
                                }
                            }).on('hidden.bs.modal', function() {
                                modal.modal('show');
                                $('body').addClass('modal-open');
                            });

                            $("#nombre_diagnostico_quirurgico" + ind).val("");
                            return false;
                        }
                    }
                });
            }
            $('#tblDiagnosticoQuirurgico tbody').on('click', 'button.deleteFila', function() {
                var obj = $(this);
                obj.parent().parent().remove();
            });
        @endif
    @endif

    $('#addMedicoAnestesiologo').on('click', function() {
        AddMedicoAnestesiologo();
    });
    let tomSelectInstancesAnestesio = {};

    function AddMedicoAnestesiologo() {
        var newRow = "";
        var ind = $('#table_medico_anestesiologo tbody tr').length;

        let item_profesional = "";
        $('#cmbProfesionalesAntesiologiaTemp option').each(function() {
            item_profesional += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
        });

        let item_tipo_profesional = "";
        $('#cmbTipoProfesionalAnestesiologoTemp option').each(function() {
            item_tipo_profesional += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
        });

        newRow += '<tr>';
        newRow += '<td><select class="select-tom cmbMedicoAnestesiologia" id="id_medico_anestesiologia' + ind +
            '" ind="' + ind + '" name="id_medico_anestesiologia[]" >' + item_profesional + '</select></td>';
        newRow +=
            '<td><select class="form-control cmbCargoMedicoAnestesiologia input-sm" id="id_medico_anestesiologia_cargo' +
            ind + '" name="id_medico_anestesiologia_cargo[]" style="margin:0 20px;width:150px">' +
            item_tipo_profesional + '</select></td>';

        newRow +=
            '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar </button></td>';

        newRow += '</tr>';
        $('#table_medico_anestesiologo tbody').append(newRow);
        // $("#id_medico_anestesiologia" + ind).select2({max_selected_options: 4}).focus();
        const select_medico = new TomSelect("#id_medico_anestesiologia" + ind, {
            maxItems: 1,
            plugins: ['clear_button']
        });
        tomSelectInstancesAnestesio["id_medico_anestesiologia" + ind] = select_medico;
        select_medico.focus();


        $("#id_medico_anestesiologia" + ind).on("change", function(e) {
            let id_medico = $(this).val();
            let flagx = 0;
            let currentTS = tomSelectInstancesAnestesio["id_medico_anestesiologia" + ind];

            // Verifica duplicados solo una vez
            $('.cmbMedicoAnestesiologia').each(function() {
                if ($(this).val() === id_medico) flagx++;
            });

            // Si el valor fue borrado (clear)
            if (id_medico === null || id_medico === "") {
                if (currentTS) {
                    // Evitar trigger redundante
                    currentTS.setTextboxValue('');
                    currentTS.blur();
                }
                return; // Termina ejecución para evitar que siga
            }

            // Si hay duplicado
            if (flagx > 1) {
                let modal = $(this).closest('.modal');

                $('body').addClass('modal-open');

                bootbox.alert({
                    message: "El Médico ya ha sido ingresado",
                    callback: function() {
                        modal.modal('show');
                        $('body').addClass('modal-open');
                        if (currentTS) {
                            currentTS.clear();
                            currentTS.setTextboxValue('');
                            currentTS.blur();
                        }
                    }
                }).on('hidden.bs.modal', function() {
                    modal.modal('show');
                    $('body').addClass('modal-open');
                });
            }
        });
        // filasmedicamentos++;
    }

    $('#table_medico_anestesiologo tbody').on('click', 'button.deleteFila', function() {
        var obj = $(this);
        // Selecciona la fila completa
        var fila = obj.closest('tr');

        // Obtiene la posición de la fila
        var filaPosition = fila.index();

        // Elimina la fila
        obj.parent().parent().remove();
        // filasmedicamentos--;
    });

    $('#btnValidar').on("click", function(e) {
        e.preventDefault(); // Evitar el comportamiento por defecto del botón
        let btn = $(this);
        validarAsegurado().then(function(resultado) {
            if (!resultado) return; // si no pasa, salir
            btn.text('Guardar');


            let ind = $('#table_medico_anestesiologo tbody tr').length;
            let msg = "";
            let is_observado = $('#is_observado').val();

            //Para validacion
            if (is_observado == "1") {
                let motivo_observacion = $('#motivo_observacion').val();
                let fecha_observacion = $('#fecha_registro_observacion').val();
                let hora_observacion = $('#hora_registro_observacion').val();

                if (motivo_observacion.trim() === "") msg +=
                    "Debe ingresar el motivo de la observación<br>";
                if (fecha_observacion.trim() === "") msg += "Debe ingresar la fecha de observación<br>";
                if (hora_observacion.trim() === "") msg += "Debe ingresar la hora de observación<br>";

                if (msg != "") {
                    let modal = $(this).closest('.modal');
                    $('body').addClass('modal-open');
                    bootbox.alert({
                        message: msg,
                        callback: function() {
                            modal.modal('show');
                            $('body').addClass('modal-open');
                        }
                    }).on('hidden.bs.modal', function() {
                        modal.modal('show');
                        $('body').addClass('modal-open');
                    });
                    return;
                }

                // Si pasa validación, envía formulario
                Swal.fire({
                    title: 'Confirmar',
                    text: "¿Está seguro de registrar la observación?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendAjaxForm('observacion');
                    }
                });

                return;
            }
            //Fin Para validacion

            let duracion_cirugia = $("#duracion_cirugia").val();
            if (duracion_cirugia === "" || duracion_cirugia === "0") msg +=
                "Debe ingresar el tiempo de duración<br>";

            let id_tipo_cirugia = $("#id_tipo_cirugia").val();
            let hora_inicio = $('#hora_inicio_intervencion').val();
            let hora_fin = $('#hora_fin_intervencion').val();
            //if (id_tipo_cirugia == "0") msg += "Debe seleccionar el tipo de cirugía<br>";
            if ([1, 4].includes(Number(id_tipo_cirugia))) {
                if (!hora_inicio) {
                    msg += "Debe ingresar la hora de inicio (Centro quirúrgico)<br>";
                }
                if (!hora_fin) {
                    msg += "Debe ingresar la hora fin (Centro quirúrgico)<br>";
                }
            }

            @if ($is_origen_emergencia)

                // var id_tipo_cirugia = $("#id_tipo_cirugia").val();
                // if (id_tipo_cirugia == "0") msg += "Debe seleccionar el tipo de cirugía<br>";

                // let tiempo_duracion = $("#tiempo_duracion").val();
                // if (tiempo_duracion == "0") msg += "Debe ingresar el tiempo de duración<br>";

                var ind_1 = $('#tblDiagnosticoQuirurgico tbody tr').length;
                if (ind_1 > 0) {
                    let fila_diagnostico = $('#tblDiagnosticoQuirurgico tbody').children('tr');
                    for (i = 0; i < ind_1; i++) {
                        let row = fila_diagnostico[i];
                        let diagnostico = $(row).find('.id_diagnostico_quirurgico').val();

                        if (diagnostico == "") msg += "Falta seleccionar un diagnostico<br>";
                    }
                } else {
                    msg += "Debe ingresar minimo un diagnostico<br>";
                }

                let ind_2 = $('#table_procedimiento_intervencion_emergencia tbody tr').length;
                if (ind_2 > 0) {
                    let fila_medico_cirujano = $('#table_procedimiento_intervencion_emergencia tbody')
                        .children('tr');
                    for (i = 0; i < ind_2; i++) {
                        let row = fila_medico_cirujano[i];
                        let procedimiento = $(row).find('.cmbProcedimientoIntervencion').val();

                        if (procedimiento == "") msg += "Falta seleccionar un procedimiento<br>";
                    }
                } else {
                    msg += "Debe ingresar minimo un procedimiento<br>";
                }

                let ind_3 = $('#table_medico_cirujano_emergencia tbody tr').length;
                if (ind_3 > 0) {
                    let fila_medico_cirujano = $('#table_medico_cirujano_emergencia tbody').children(
                        'tr');
                    for (i = 0; i < ind_3; i++) {
                        let row = fila_medico_cirujano[i];
                        let medico = $(row).find('.cmbMedicoCirujanoTodos').val();
                        let cargo = $(row).find('.cmbCargoMedicoCirujanoTodos').val();
                        if (medico == "") msg += "Falta seleccionar un Medico Cirujano<br>";
                        if (cargo == "0") msg += "Falta seleccionar el Cargo del Medico Cirujano<br>";
                    }
                } else {
                    msg += "Debe ingresar minimo un Médico Cirujano<br>";
                }

                let idsala_intervencion = $("#id_sala_intervencion").val();
                msg += (idsala_intervencion == "0") ? "Falta seleccionar la Sala de Intervención<br>" :
                    "";
            @else
                // @if ($is_origen_emergencia)
                //     let id_lugar_operacion = $("#id_lugar_operacion").val();
                //     if (id_lugar_operacion == "0") msg += "Debe seleccionar el Lugar de Operación<br>";
                // @endif

                // let id_prioridad_programacion = $("#id_prioridad_programacion").val();
                // if (id_prioridad_programacion == "0") msg += "Debe seleccionar la Prioridad de Programación en Semanas<br>";

                let addRequiereInternamientoPreQx = $('#addRequiereInternamientoPreQx').attr(
                    "aria-pressed");
                if (addRequiereInternamientoPreQx == "true") {
                    let id_tiempo_internamiento_preqx = $("#id_tiempo_internamiento_preqx").val();
                    if (id_tiempo_internamiento_preqx == "0") msg +=
                        "Debe seleccionar el Tiempo que se requiere de Internamiento<br>";
                }

                let addDiasHospitalizadoPostQx = $('#addDiasHospitalizadoPostQx').attr("aria-pressed");
                if (addDiasHospitalizadoPostQx == "true") {
                    let id_tiempo_hospitalizacion_postqx = $("#id_tiempo_hospitalizacion_postqx").val();
                    if (id_tiempo_hospitalizacion_postqx == "0") msg +=
                        "Debe seleccionar el Tiempo que se requiere de Hospitalización<br>";
                }

                /*let prioridadProgramacionsemanas = $('#prioridadProgramacionsemanas').attr("aria-pressed");
                if (prioridadProgramacionsemanas == "true") {*/
                let id_prioridad_programacion = $("#id_prioridad_programacion").val();
                if (id_prioridad_programacion == "0" || id_prioridad_programacion == '') msg +=
                    "Debe ingresar el turno<br>";
                /*}*/
                // var id_tipo_cirugia2 = $("#id_tipo_cirugia").val();
                // if (id_tipo_cirugia2 == "0") msg += "Debe seleccionar el tipo de cirugía<br>";


                let fecha_intervencion = $("#fecha_intervencion").val();
                // let hora_inicio_intervencion = $("#hora_inicio_intervencion").val();
                // let hora_fin_intervencion = $("#hora_fin_intervencion").val();
                let id_sala_intervencion = $("#id_sala_intervencion").val();
                let id_tipo_anestesia = $("#id_tipo_anestesia").val();

                msg += (fecha_intervencion == "") ? "Falta ingresar la Fecha de Intervención<br>" : "";
                // msg += (hora_inicio_intervencion == "" || hora_inicio_intervencion.trim() == ":") ? "Falta ingresar la Hora de Inicio de la Intervención<br>" : "";
                // msg += (hora_fin_intervencion == "" || hora_fin_intervencion.trim() == ":") ? "Falta ingresar la Hora de Fin de la Intervención<br>" : "";
                msg += (id_sala_intervencion == "0") ? "Falta seleccionar la Sala de Intervención<br>" :
                    "";
                msg += (id_tipo_anestesia == "0") ? "Falta seleccionar el Tipo de Anestesia<br>" : "";

                // @if ($is_origen_emergencia)
                //     let ind_1 = $('#table_medico_cirujano tbody tr').length;
                //     if (ind_1 > 0){
                //         let fila_medico_cirujano = $('#table_medico_cirujano tbody').children('tr');            
                //         for (i = 0; i < ind_1; i++) {
                //             let row = fila_medico_cirujano[i];
                //             let medico = $(row).find('.cmbMedicoCirujanoTodos').val();
                //             let cargo = $(row).find('.cmbCargoMedicoCirujanoTodos').val();
                //             if (medico == "") msg += "Falta seleccionar un Medico Cirujano<br>";
                //             if (cargo == "0") msg += "Falta seleccionar el Cargo del Medico Cirujano<br>";
                //         }
                //     } else {
                //         msg += "Debe ingresar minimo un Médico Cirujano<br>";
                //     }
                // @endif
            @endif

            if (ind > 0) {
                let fila_medico_cirujano = $('#table_medico_anestesiologo tbody').children('tr');
                for (i = 0; i < ind; i++) {
                    let row = fila_medico_cirujano[i];
                    let medico = $(row).find('.cmbMedicoAnestesiologia').val();
                    let cargo = $(row).find('.cmbCargoMedicoAnestesiologia').val();
                    if (medico == "") msg += "Falta seleccionar un Medico Anestesiólogo<br>";
                    if (cargo == "0") msg += "Falta seleccionar el Cargo del Medico Anestesiólogo<br>";
                }
            } else {
                var idtipo_anestesia = $('#id_tipo_anestesia').val();
                if (idtipo_anestesia != '1') {
                    msg += "Debe ingresar minimo un Médico Anestesiólogo<br>";
                }
            }

            if (msg != "") {
                let modal = $(this).closest('.modal');

                $('body').addClass('modal-open');

                bootbox.alert({
                    message: msg,
                    callback: function() {
                        modal.modal('show');
                        $('body').addClass('modal-open');
                    }
                }).on('hidden.bs.modal', function() {
                    modal.modal('show');
                    $('body').addClass('modal-open');
                });
            } else {
                // // Guardar referencia al modal principal
                // let modal = $(this).closest('.modal');

                // // Bloquear el scroll del body al abrir el bootbox
                // $('body').addClass('modal-open');

                // bootbox.confirm({
                //     message: "¿Está seguro de Validar?",
                //     callback: function(result) {
                //         if (result) {
                //             // Acción cuando se confirma
                //             sendAjaxForm('validacion');
                //         } else {
                //             // Restaurar el estado del modal
                //             modal.modal('show');  // Volver a mostrar el modal principal
                //             $('body').addClass('modal-open');  // Restaurar el scroll del modal
                //         }
                //     }
                // }).on('hidden.bs.modal', function () {
                //     // Si el bootbox se cierra, restaurar el scroll
                //     $('body').addClass('modal-open');
                // });
                document.getElementById('modal-validacion-programacion').inert = true;

                Swal.fire({
                    title: 'Confirmar',
                    text: "¿Está seguro de Validar?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, validar',
                    cancelButtonText: 'Cancelar',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendAjaxForm('validacion');
                    }

                    // Quitar inert después
                    document.getElementById('modal-validacion-programacion').inert = false;
                });

                // Swal.fire({
                //     title: 'Confirmar',
                //     text: "¿Está seguro de Validar?",
                //     icon: 'question',
                //     showCancelButton: true,
                //     confirmButtonText: 'Sí, validar',
                //     cancelButtonText: 'Cancelar',
                //     allowOutsideClick: false
                // }).then((result) => {
                //     if (result.isConfirmed) {
                //         sendAjaxForm('validacion');
                //     }
                // });
            }


        });
    });

    function filtrarNumeroDecimal(input) {
        input.value = input.value
            .replace(/[^0-9.]/g, '')
            .replace(/(\..*?)\..*/g, '$1');
    }

    $(document).ready(function() {
        function actualizarFechaHora() {
            var ahora = new Date();

            // Fecha en formato YYYY-MM-DD
            var year = ahora.getFullYear();
            var month = String(ahora.getMonth() + 1).padStart(2, '0');
            var day = String(ahora.getDate()).padStart(2, '0');
            $('#fecha_registro').val(year + '-' + month + '-' + day);
            $('#fecha_registro_observacion').val(year + '-' + month + '-' + day);

            // Hora en formato HH:MM
            var hours = String(ahora.getHours()).padStart(2, '0');
            var minutes = String(ahora.getMinutes()).padStart(2, '0');
            $('#hora_registro').val(hours + ':' + minutes);
            $('#hora_registro_observacion').val(hours + ':' + minutes);
        }

        // Ejecutar la función al cargar la página
        actualizarFechaHora();

        // Si quieres que la hora se actualice automáticamente cada minuto:
        setInterval(actualizarFechaHora, 60000);

        const minutosRaw = parseInt(
            {{ isset($intervencion->emergencia_duracion_cirugia) ? $intervencion->emergencia_duracion_cirugia : 0 }},
            10);

        if (!isNaN(minutosRaw) && minutosRaw > 0) {
            const minutos = minutosRaw < 10 ? minutosRaw * 60 : minutosRaw;
            const horas = Math.floor(minutos / 60);
            const min = minutos % 60;
            const hhmm = (String(horas).padStart(2, '0')) + ':' + (String(min).padStart(2, '0'));
            document.getElementById("duracion_cirugia_calculada").value = hhmm;
        }
        $('#modal-validacion-programacion').on('shown.bs.modal', function() {

            // document.getElementById('hora_inicio_intervencion').addEventListener('change', calcularDuracion);
            // document.getElementById('hora_fin_intervencion').addEventListener('change', calcularDuracion);
            let inicio = document.getElementById('hora_inicio_intervencion');
            let fin = document.getElementById('hora_fin_intervencion');

            if (inicio) inicio.addEventListener('change', calcularDuracion);
            if (fin) fin.addEventListener('change', calcularDuracion);

            function calcularDuracion() {
                const inicio = document.getElementById('hora_inicio_intervencion').value;
                const termino = document.getElementById('hora_fin_intervencion').value;

                if (inicio && termino) {
                    const inicioMilis = convertirATiempoEnMilisegundos(inicio);
                    let terminoMilis = convertirATiempoEnMilisegundos(termino);

                    if (terminoMilis < inicioMilis) {
                        terminoMilis += 24 * 60 * 60 * 1000; // sumamos 24 horas
                    }

                    const diferenciaMilis = terminoMilis - inicioMilis;

                    // Calcular total minutos
                    const totalMinutos = Math.floor(diferenciaMilis / (1000 * 60));
                    document.getElementById('duracion_cirugia').value = totalMinutos;

                    // Calcular en formato HH:MM
                    const duracionHHMM = convertirMilisegundosATiempo(diferenciaMilis);
                    document.getElementById('duracion_cirugia_calculada').value = duracionHHMM;
                }
            }

            function convertirATiempoEnMilisegundos(hora) {
                const partes = hora.split(':');
                const horas = parseInt(partes[0], 10);
                const minutos = parseInt(partes[1], 10);
                return (horas * 60 * 60 * 1000) + (minutos * 60 * 1000);
            }

            function convertirMilisegundosATiempo(milis) {
                const horas = Math.floor(milis / (1000 * 60 * 60));
                const minutos = Math.floor((milis % (1000 * 60 * 60)) / (1000 * 60));
                return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}`;
            }
        });

        var tipo_cirugia =
            {{ isset($intervencion->emergencia_tipo_cirugia) ? $intervencion->emergencia_tipo_cirugia : 0 }};
        if ([1, 4].includes(Number(tipo_cirugia))) {
            $('#electiva_div').show();
        } else {
            $('#electiva_div').hide();
        }
        $('#id_tipo_cirugia').change(function() {
            var tipo_cirugia = $('#id_tipo_cirugia').val();
            if ([1, 4].includes(Number(tipo_cirugia))) {
                $('#electiva_div').show();
            } else {
                $('#electiva_div').hide();
            }
        });



    });

    function filtrarNumero(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function sendAjaxForm(tipo) {
        let formData = $("#frmValidacionProgramacion").serialize();

        $.ajax({
            url: $("#frmValidacionProgramacion").attr('action'),
            type: 'POST',
            data: formData + '&tipo=' + tipo,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#btnValidar').prop('disabled', true);
            },
            success: function(response) {
                $('#btnValidar').prop('disabled', false);

                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        html: response.message || 'Operación realizada correctamente'
                    });

                    let tbody = $('#tblObservaciones tbody');
                    tbody.empty();

                    let idIntervencion = $('#modal-validacion-programacion').data('id');
                    let contenedor = $('div[data-id="' + idIntervencion + '"]');
                    let fila = $('#tblIntervenciones tr').filter(function() {
                        return $(this).data('id') == idIntervencion;
                    });

                    /* ============================================================
                    =====================  OBSERVACIONES  =======================
                    ============================================================ */
                    if (response.tipo && response.tipo == 'observacion' && response.observaciones &&
                        response.observaciones.length > 0) {

                        // Limpiar tabla antes de agregar
                        tbody.empty();

                        // 1Construir contenido completo para el botón general
                        let contenidoObs = '<div style="max-width:250px;">';
                        response.observaciones.forEach(function(obs) {
                            contenidoObs +=
                                '<div style="margin-bottom:6px; border-bottom:1px solid #ddd; padding-bottom:4px;">' +
                                '<strong>📅</strong> ' + (obs.fecha_registro || '') + ' ' + (obs
                                    .hora_registro || '') + '<br>' +
                                '<strong>👤</strong> ' + (obs.medico ? obs.medico.nombre + ' ' + obs
                                    .medico.paterno + ' ' + obs.medico.materno : '') + '<br>' +
                                '<strong>💬</strong> ' + (obs.motivo ? obs.motivo.replace(/"/g,
                                    '&quot;').replace(/\n/g, '<br>') : 'Sin motivo') +
                                '</div>';
                        });
                        contenidoObs += '</div>';

                        // Agregar filas al tbody con icono individual
                        $.each(response.observaciones, function(index, obs) {
                            let motivoHtml =
                                '<i class="fa fa-eye text-danger" style="cursor:pointer"></i>';

                            tbody.append(
                                '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + (obs.fecha_registro || '') + ' ' + (obs
                                    .hora_registro || '') + '</td>' +
                                '<td>' + (obs.medico ? obs.medico.nombre + ' ' + obs.medico
                                    .paterno + ' ' + obs.medico.materno : '') + '</td>' +
                                '<td class="text-center">' + motivoHtml + '</td>' +
                                '</tr>'
                            );
                        });
                        $("#motivo_observacion").val("");
                        // asegurar obtener la fila REAL antes de modificar
                        if (fila.length === 0) {
                            let boton = $('a[href="javascript:void(0)"][onclick*="(' + idIntervencion +
                                ',"]');
                            fila = boton.closest('tr');
                        }

                        if (fila.length) {
                            fila.find('td').each(function() {
                                let txt = $(this).text().trim();
                                if (txt === 'Programado') {
                                    $(this).text('Pendiente');
                                }
                            });
                        }
                        actualizarBotonObservaciones(contenedor, contenidoObs);
                    } else {
                        tbody.empty().append(
                            '<tr><td colspan="5" class="text-center">Sin observaciones</td></tr>');
                    }

                    /* ============================================================
                    ======================= VALIDACIÓN ============================
                    ============================================================ */
                    if (response.tipo && response.tipo == 'validacion') {

                        // Ocultar temporalmente el modal
                        $('#modal-validacion-programacion').modal('hide');

                        // Obtener fila si no existe
                        if (fila.length === 0) {
                            let boton = $('a[href="javascript:void(0)"][onclick*="(' + idIntervencion +
                                ',"]');
                            fila = boton.closest('tr');
                        }

                        if (fila.length) {
                            // Actualizar estado
                            fila.find('td').each(function() {
                                let txt = $(this).text().trim();
                                if (txt === 'Pendiente' || txt === 'Programado') {
                                    $(this).text('Validado');
                                }
                            });

                            // Cambiar estilo de botones
                            fila.find('a, button').each(function() {
                                if ($(this).hasClass('btn') && $(this).find('.fa-check').length) {
                                    $(this).removeClass('btn-default btn-secondary').addClass(
                                        'btn-success');
                                }
                            });
                        }

                        // ============================================================
                        //Swal de carga mientras recargamos el modal
                        // ============================================================
                        Swal.fire({
                            title: 'Cargando información actualizada...',
                            text: 'Por favor espere un momento',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();

                                $.ajax({
                                    type: "GET",
                                    url: `/form_validacion_programacion_qx/{{ $intervencion->id }}/{{ $id_tipo_atencion_origen }}`,
                                    success: function(result) {

                                        // ---------------------------
                                        // FIX LINUX: SweetAlert basura
                                        // ---------------------------
                                        Swal.close();
                                        $('.swal2-container').remove();
                                        $('.swal2-backdrop, .swal2-backdrop-show')
                                            .remove();

                                        // ---------------------------
                                        // FIX LINUX: Bootstrap modal
                                        // ---------------------------
                                        $('.modal-backdrop').remove();
                                        $('body')
                                            .removeClass('modal-open')
                                            .css({
                                                'overflow': 'auto',
                                                'padding-right': '0px'
                                            });

                                        // ---------------------------
                                        // Cargar contenido del modal
                                        // ---------------------------
                                        $("#div-modal-validacion-programacion")
                                            .html(result);

                                        let modal = $(
                                            '#modal-validacion-programacion');
                                        modal.modal('show');

                                        // ---------------------------
                                        // FIX LINUX adicional:
                                        // eliminar backdrop duplicado
                                        // ---------------------------
                                        setTimeout(() => {
                                            $('.modal-backdrop').remove();
                                            $('body').addClass('modal-open')
                                                .css('overflow', 'auto');
                                        }, 300);

                                        // ---------------------------
                                        // Reactivar popovers y tooltips
                                        // ---------------------------
                                        $('[data-toggle="popover"]').popover();
                                        $('[data-toggle="tooltip"]').tooltip();

                                        // ---------------------------
                                        // Rehabilitar boton "Atender"
                                        // ---------------------------
                                        fila.find('.btnAtender')
                                            .prop('disabled', false)
                                            .removeAttr('disabled');

                                        // ---------------------------
                                        // LLAMADO: actualizar sugerencia
                                        // AQUÍ DEBE IR, NO EN OTRO LADO
                                        // ---------------------------
                                        if (typeof actualizarBotonSugerencia ===
                                            'function') {
                                            actualizarBotonSugerencia(fila,
                                                contenedor, response);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.close();
                                        $('.swal2-container').remove();

                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error al cargar datos',
                                            text: "Ocurrió un error al obtener los datos: " +
                                                error
                                        });
                                    }
                                });
                            }
                        });

                    }

                    $('[data-toggle="tooltip"]').tooltip();

                    if (response.no_medico && response.no_medico.length > 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Información',
                            text: response.message
                        });
                    }

                } else {
                    if (response.status === false) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atención',
                            text: (response.message ? `${response.message}` : ''),
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Ocurrió un error al procesar la información'
                        });
                    }
                }

            },
            error: function(xhr, status, error) {
                $('#btnValidar').prop('disabled', false);
                let mensaje = "Error desconocido";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                } else {
                    mensaje = "Código: " + xhr.status + " (" + error + ")";
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error detectado',
                    text: mensaje
                });
            }
        });
    }

    $(function() {
        $('[data-toggle="popover"]').popover();
    });

    function actualizarBotonObservaciones(contenedor, contenidoObs) {
        try {

            let btnObs = contenedor.find('.btn-observacion');

            // Si existía popover/estado antiguo, destruirlo para liberar memoria
            if (btnObs.length) {
                try {
                    btnObs.popover('destroy');
                } catch (e) {
                    /* ignore */ }
            }

            // Crear un nuevo botón jQuery (no usar data-toggle para evitar auto-init)
            let nuevoBtn = $(
                '<button type="button" class="btn-observacion btn btn-danger btn-xs" aria-label="Observaciones">' +
                '<i class="fa fa-eye"></i>' +
                '</button>'
            );

            // Habilitarlo (si venía disabled)
            nuevoBtn.prop('disabled', false);

            // Guardar contenido en attribute para referencia si hace falta
            nuevoBtn.attr('data-content', contenidoObs);

            // Reemplazar (si no existía, append)
            if (btnObs.length) {
                btnObs.replaceWith(nuevoBtn);
            } else {
                contenedor.append(nuevoBtn);
            }

            // Inicializar popover en el nuevo elemento (Bootstrap JS)
            nuevoBtn.popover({
                html: true,
                trigger: 'hover',
                placement: 'top',
                container: 'body',
                title: '<strong>Observaciones</strong>',
                content: contenidoObs
            });
            $('#modal-validacion-programacion').modal('hide');
        } catch (err) {
            console.error('[actualizarBotonObservacion] error:', err);
        }
    }

    function actualizarBotonSugerencia(fila, contenedor, response) {
        try {
            // 1. Reubicar contenedor si viene vacío
            if (!contenedor || !contenedor.length) {
                let id = fila && fila.length ? fila.data('id') : null;
                if (id) contenedor = $('div[data-id="' + id + '"]');
            }

            let btnSugerencia = contenedor.find('.btn-sugerencia');
            if (!btnSugerencia.length) {
                console.warn('[actualizarBotonSugerencia] no existe btn-sugerencia');
                return;
            }

            // 2. Habilitar botón sin bloquear nada
            btnSugerencia.prop('disabled', false)
                .removeClass('disabled')
                .attr('aria-disabled', 'false');

            // 3. Eliminar popovers previos (forma segura Linux)
            try {
                btnSugerencia.popover('dispose');
            } catch (_) {}
            try {
                btnSugerencia.popover('destroy');
            } catch (_) {}
            btnSugerencia.removeData('bs.popover')
                .removeAttr('data-original-title')
                .removeAttr('title')
                .removeAttr('aria-describedby');
            $('.popover').remove();

            // 4. Definir contenido
            let contenido = '';
            if (response?.sugerencia?.trim()) contenido = response.sugerencia;
            else if (response?.sugerencia_html?.trim()) contenido = response.sugerencia_html;
            else contenido = 'No tiene sugerencia médica registrada.';

            btnSugerencia.attr('data-content', contenido);

            // 5. Inicializar popover normal
            let modalPadre = btnSugerencia.closest('.modal');
            let contenedorPopover = modalPadre.length ? modalPadre : 'body';

            btnSugerencia.popover({
                html: true,
                trigger: 'hover',
                placement: 'top',
                container: contenedorPopover,
                title: '<strong>Sugerencia médica</strong>',
                content: contenido
            });

            // 6. Verificar si Bootstrap creo instancia (Linux falla a veces)
            setTimeout(function() {
                let instancia = btnSugerencia.data('bs.popover');

                if (!instancia) {
                    // Reconstruir botón en modo seguro
                    let nuevoBtn = $('<button type="button" class="btn-sugerencia btn btn-danger btn-xs">' +
                        '<i class="fa fa-eye"></i>' +
                        '</button>');

                    nuevoBtn.attr('data-content', contenido);

                    btnSugerencia.replaceWith(nuevoBtn);

                    nuevoBtn.popover({
                        html: true,
                        trigger: 'manual',
                        placement: 'top',
                        container: contenedorPopover,
                        title: '<strong>Sugerencia médica</strong>',
                        content: contenido
                    });

                    // Hover manual (fix Linux)
                    nuevoBtn.on('mouseenter', function() {
                        $(this).popover('show');
                    }).on('mouseleave', function() {
                        $(this).popover('hide');
                    });

                    console.log('POPOVER forzado OK (Linux)');
                } else {
                    console.log('Popover cargado normalmente');
                }
            }, 150);

        } catch (err) {
            console.error('[actualizarBotonSugerencia] error:', err);
        }
    }

    function validarAsegurado() {

        var tipodoc_beneficiario = {!! json_encode(isset($datos_asegurado->id_tipo_doc_ident) ? trim($datos_asegurado->id_tipo_doc_ident) : '') !!};
        var dni_beneficiario = {!! json_encode(isset($datos_asegurado->nro_doc_ident) ? trim($datos_asegurado->nro_doc_ident) : '') !!};
        ipodoc_beneficiario = tipodoc_beneficiario.trim();
        dni_beneficiario = dni_beneficiario.trim();
        var deferred = $.Deferred();

        if (tipodoc_beneficiario && dni_beneficiario) {

            var id_tiporeceta = "1";

            $.ajax({
                    url: '/obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' +
                        dni_beneficiario,
                    dataType: "json"
                })
                .done(function(result) {

                    if (result.sw == false) {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            html: result.msg || 'El paciente no se encuentra afiliado',
                            confirmButtonText: 'Cerrar',
                            allowOutsideClick: false
                        });

                        deferred.resolve(false);
                        return;
                    }

                    deferred.resolve(true);

                })
                .fail(function() {

                    Swal.fire({
                        icon: 'error',
                        text: 'Error al validar el asegurado'
                    });

                    deferred.resolve(false);
                });

        } else {

            // si no hay datos no bloquea el flujo
            deferred.resolve(true);

        }

        return deferred.promise();
    }
</script>

<div class="panel-body">
    <form name="frmValidacionProgramacion" id="frmValidacionProgramacion" method="post"
        action="{{ route('validar_programacion_quirurgica') }}" class="form-horizontal" role="form" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_tipo_atencion_origen" value="{{ $id_tipo_atencion_origen }}">
        <input type="hidden" name="id_intervencion_quirurgica" value="{{ $intervencion->id }}">
        <input type="hidden" name="id_medico" value="{{ $id_medico }}">
        <input type="hidden" name="is_origen_emergencia" value="{{ $is_origen_emergencia }}">
        <input type="hidden" id="sexo" value="{{ !empty($intervencion->sexo) ? $intervencion->sexo : '' }}">
        <input type="hidden" id="duracion_cirugia" name="duracion_cirugia"
            value="{{ $intervencion->emergencia_duracion_cirugia }}">
        <input type="hidden" id="view_sugerencia_medica" value="{{ $intervencion->sugerencia_medica }}">
        @if (!$is_origen_emergencia)
            <input type="hidden" name="id_lugar_operacion" value="{{ $intervencion->id_lugar_operacion }}">
        @endif

        <div class="panel panel-info panel-sm">
            <div class="panel-heading">
                <div class="panel-title text-center"><b>OBSERVACIÓN</b></div>
            </div>
            <div class="panel-body">
                <p><strong> LISTA DE OBSERVACIONES</strong></p>
                <table class="table table-bordered table-condensed" id="tblObservaciones">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha Hora</th>
                            <th>Medico</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($observaciones->count() > 0)
                            @foreach ($observaciones as $index => $obs)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $obs->fecha_registro ? \Carbon\Carbon::parse($obs->fecha_registro)->format('d/m/Y') : '' }}
                                        {{ $obs->hora_registro ? $obs->hora_registro : '' }}</td>
                                    <td>{{ $obs->medico ? $obs->medico->nombre . ' ' . $obs->medico->paterno . ' ' . $obs->medico->materno : '' }}
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-eye text-danger" data-toggle="popover" data-trigger="hover"
                                            data-placement="top" data-content="{{ $obs->motivo }}"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Sin observaciones</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="form-group">
                    <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                        <label>Observado</label>
                    </div>
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                            <input type="hidden" id="is_observado" name="is_observado" value="0">
                            <button tabindex="110" type="button" id="addObservado" class="btn btn-lg btn-toggle"
                                data-toggle="button" aria-pressed="false" autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                    @else
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                            <button tabindex="110" type="button" class="btn btn-lg btn-toggle" data-toggle="button"
                                aria-pressed="false" autocomplete="off" disabled>
                                <div class="handle"></div>
                            </button>
                        </div>
                    @endif

                </div>
                <div id="fsObservado" style="display: none">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label for="">FECHA DE REGISTRO</label>
                            <input tabindex="6" type="date" class="form-control" name="fecha_registro_observacion"
                                id="fecha_registro_observacion" style="text-align:left;">
                        </div>
                        <div class="col-md-3">
                            <label for="">HORA DE REGISTRO</label>
                            <input tabindex="7" type="time" class="form-control" id="hora_registro_observacion"
                                name="hora_registro_observacion" style="text-align:left">
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">INFORMACIÓN DEL RESPONSABLE DE LA ATENCION</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                    <input tabindex="11" readonly="" type="text" class="form-control"
                                        value="DNI" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                    <label for="">N° DE DOCUMENTO DE IDENTIDAD</label>
                                    <input tabindex="12" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->dni) ? $data_medico->dni : '' }}"
                                        style="text-align:left">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="">APELLIDO PATERNO</label>
                                    <input tabindex="15" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->paterno) ? $data_medico->paterno : '' }}"
                                        style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                    <label for="">APELLIDO MATERNO</label>
                                    <input tabindex="16" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->materno) ? $data_medico->materno : '' }}"
                                        style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                    <label for="">NOMBRES</label>
                                    <input tabindex="17" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->nombre) ? $data_medico->nombre : '' }}"
                                        style="text-align:left">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="">PROFESIÓN</label>
                                    <input tabindex="27" readonly=""
                                        value="{{ !empty($data_medico->profesion) ? $data_medico->profesion : '' }}"
                                        type="text" class="form-control" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                    <label for="">N° COLEGIATURA</label>
                                    <input tabindex="25" readonly="" type="text" class="form-control"
                                        value="{{ !empty($data_medico->colegiatura) ? $data_medico->colegiatura : '' }}"
                                        style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                    <label for="">N° RNE</label>
                                    <input tabindex="26" readonly=""
                                        value="{{ !empty($data_medico->rne) ? $data_medico->rne : '' }}" type="text"
                                        class="form-control" style="text-align:left">
                                </div>
                                <div class="col-md-3">
                                    <label for="">ESPECIALIDAD</label>
                                    <input tabindex="28" readonly=""
                                        value="{{ !empty($data_medico->especialidad) ? $data_medico->especialidad : '' }}"
                                        type="text" class="form-control" style="text-align:left">
                                </div>
                            </div>
                        </div>
                    </div>

                    <fieldset class="panel panel-info panel-bordered panel-sm">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="">MOTIVO</label>
                                <textarea class="form-control" id="motivo_observacion" name="motivo_observacion" rows="4" cols="50"></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="panel panel-info panel-bordered panel-sm panel-body" id="programacion_validacion">
            <div class="panel-heading">
                <div class="panel-title text-center"><b>REGISTRAR PROGRAMACIÓN QUIRÚRGICA</b></div>
            </div>
            <div class="panel-body">

                @if ($is_origen_emergencia || $es_traslado_hospitalizacion_acqx)
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="col-md-2">
                            <label for="">FECHA DE REGISTRO</label>
                            <input tabindex="6" type="date" class="form-control" name="fecha_registro"
                                id="fecha_registro" style="text-align:left;">
                        </div>
                        <div class="col-md-2">
                            <label for="">HORA DE REGISTRO</label>
                            <input tabindex="7" type="time" class="form-control" id="hora_registro"
                                name="hora_registro" style="text-align:left">
                        </div>
                    @else
                        <div class="col-md-2">
                            <label for="">FECHA DE REGISTRO</label>
                            <p>{{ $intervencion->fecha_registro ? \Carbon\Carbon::parse($intervencion->fecha_registro)->format('Y-m-d') : '' }}
                            </p>
                        </div>
                        <div class="col-md-2">
                            <label for="">HORA DE REGISTRO</label>
                            <p>{{ $intervencion->hora_registro ? \Carbon\Carbon::parse($intervencion->hora_registro)->format('H:i') : '' }}
                            </p>
                        </div>
                    @endif

                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="col-sm-12 col-md-2">
                            <label
                                for="">{{ $es_traslado_hospitalizacion_acqx ? 'SELECCIONAR' : 'TIPO DE CIRUGIA' }}:</label>
                            <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control">
                                <option value="0">Seleccione</option>
                                @foreach ($tipo_cirugias as $row)
                                    <option value="{{ $row->id }}"
                                        @if ($intervencion->emergencia_tipo_cirugia == $row->id) selected @endif>{{ $row->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="col-sm-12 col-md-2">
                            <label for="">TIPO DE CIRUGIA:</label>
                            <p>{{ $intervencion->tipo_cirugia }}</p>
                        </div>
                    @endif

                    <div id="electiva_div" style="display: none;">
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2">
                                <label for="">HORA DE INICIO:</label>
                                <input type="time" class="form-control input-sm" name="hora_inicio_intervencion"
                                    id="hora_inicio_intervencion" value="{{ $intervencion->hora_inicio_prog_qx }}">
                            </div>
                        @else
                            <div class="col-md-2">
                                <label for="">HORA DE INICIO:</label>
                                <p>{{ date('H:i', strtotime($intervencion->fecha_inicio_intervencion)) }}</p>
                            </div>
                        @endif

                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <div class="col-md-2">
                                <label for="">HORA DE FIN:</label>
                                <input type="time" class="form-control input-sm" name="hora_fin_intervencion"
                                    id="hora_fin_intervencion" value="{{ $intervencion->hora_fin_prog_qx }}">
                            </div>
                        @else
                            <div class="col-md-2">
                                <label for="">HORA DE FIN:</label>
                                <p>{{ date('H:i', strtotime($intervencion->fecha_fin_intervencion)) }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-12 col-md-3">
                        <label for="">DURACIÓN DE LA CIRUGIA:</label>
                        <input type="text" id="duracion_cirugia_calculada" class="form-control"
                            style="width: 105px;" disabled>
                    </div>

            </div>
            <div class="form-group">
                <div class="col-md-2" style="text-align:right;">
                    <label for="">SALA DE INTERVENCIÓN:</label>
                </div>
                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                    <div class="col-md-2">
                        <select name="id_sala_intervencion" id="id_sala_intervencion" class="form-control input-sm">
                            <option value="0">Seleccione</option>
                            @foreach ($cq_salas as $row)
                                <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-md-2">
                        <p>{{ $intervencion->sala_intervencion }}</p>
                    </div>
                @endif
                <div class="col-md-2" style="text-align:right;">
                    <label for="">TIPO DE ANESTESIA:</label>
                </div>
                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                    <div class="col-md-6">
                        <select name="id_tipo_anestesia" id="id_tipo_anestesia" class="form-control input-sm">
                            <option value="0">Seleccione</option>
                            @foreach ($tipos_anestesia as $row)
                                <option value="{{ $row->id }}">{{ $row->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-md-6">
                        <p>{{ $intervencion->tipo_anestesia }}</p>
                    </div>
                @endif
            </div>
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <b>DIAGNOSTICO DE INTERVENCIÓN</b>
                    </div>
                </div>
                <div class="panel-body">
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <table>
                            <tr>
                                <td>
                                    {{-- <button type="button" id="addDiagnosticoQuirurgico" class="btn btn-primary"
                                                data-toggle="modal" data-target="#addClassModal">Agregar
                                                Diagnostico</button>
                                            </td> --}}
                            </tr>
                        </table>
                    @endif
                    <br />
                    <table id="tblDiagnosticoQuirurgico" cellspacing="0" width="100%" style="width: 100%;">
                        <thead>
                            <tr>
                                <th width="50%">NOMBRE</th>
                                <th width="15%">TIPO</th>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($diagnosticos_cqx as $key => $row)
                                <tr>
                                    <?php $tipo = '';
                                    switch ($row->id_tipo_diagnostico):
                                        case 1:
                                            $tipo = 'PRESUNTIVO';
                                            break;
                                        case 2:
                                            $tipo = 'DEFINITIVO';
                                            break;
                                        case 3:
                                            $tipo = 'REITERATIVO';
                                            break;
                                    endswitch;
                                    ?>
                                    <td style="width:60%; 1px solid gray;">
                                        <p>{{ $row->codigo_diagnostico }} - {{ $row->descripcion_diagnostico }}</p>
                                    </td>
                                    <td style="width:10%; 1px solid gray;">{{ $tipo }}</td>
                                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                    @endif
                                    <?php $i++; ?>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <b>PROCEDIMIENTOS DE INTERVENCIÓN</b>
                    </div>
                </div>
                <div class="panel-body">
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <table>
                            <tr>
                                <td>
                                    {{-- <button tabindex="75" type="button" id="addProcedimientoIntervencionEmergencia" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Procedimiento</button> --}}
                                </td>
                            </tr>
                        </table>
                        <div class="form-group" style="display:none">
                            <select id="cmbProcedimientoIntervencionTempEmergencia">
                                <option value="0">--Seleccionar--</option>
                                @foreach ($procedimientos as $p)
                                    <option value="{{ $p->id }}">{{ $p->codigo }} - {{ $p->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <table id="table_procedimiento_intervencion_emergencia" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="70%">DESCRIPCION</th>

                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                            @foreach ($procedimientos_cqx_emergencia as $pintervencion)
                                <tr>
                                    <td>{{ $pintervencion->codigo_cpms }} - {{ $pintervencion->descripcion_cpms }}
                                    </td>
                                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                        <td></td>
                                </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Incio PROFESIONALES QUE INTERVIENEN -->
            <div class="panel panel-info panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center"><b>PROFESIONALES QUE INTERVIENEN</b></div>
                </div>
                <div class="panel-body">
                    <!-- INICIO CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN -->
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="form-group">
                            <div class="col-sm-2">
                                {{-- <button tabindex="75" type="button" id="addMedicoCirujanoEmergencia" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Cirujano que intervienen en la operación</button> --}}
                            </div>
                        </div>
                        <div class="form-group" style="display:none">
                            <select id="cmbProfesionalesCirujanosTodosTempEmergencia">
                                <option value="">--Seleccionar--</option>
                                @foreach ($lista_profesionales_cirujanos as $pa)
                                    <option value="{{ $pa->id }}">{{ $pa->paterno }} {{ $pa->materno }},
                                        {{ $pa->nombre }}</option>
                                @endforeach
                            </select>
                            <select id="cmbTipoProfesionalCirujanoTempEmergencia">
                                <option value="0">--Seleccionar--</option>
                                @foreach ($cqx_tipo_profesionales as $tp)
                                    @if ($tp->flag_profesional == 1)
                                        <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="col-sm-12">
                            <table id="table_medico_cirujano_emergencia" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Apellidos y Nombres</th>
                                        <th style="width:30%;padding-left:20px;">Cargo</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    @foreach ($profesionales_cqx_emergencia as $pq)
                                        <tr>
                                            <td>{{ $pq->paterno }} {{ $pq->materno }}, {{ $pq->nombre }}</td>
                                            <td>{{ $pq->tipo_profesional }}</td>
                                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- FIN CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN -->

                    <!-- INICIO ANESTESIOLOGOS QUE INTERVIENEN EN LA OPERACIÓN -->
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <div class="form-group">
                            <div class="col-sm-2">
                                <button tabindex="75" type="button" id="addMedicoAnestesiologo"
                                    class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#addClassModal">Agregar Anestesiólogo que intervienen en la
                                    operación</button>
                            </div>
                        </div>
                        <div class="form-group" style="display:none">
                            <select id="cmbProfesionalesAntesiologiaTemp">
                                <option value="">--Seleccionar--</option>
                                @foreach ($lista_profesionales_anestesiologos as $pa)
                                    <option value="{{ $pa->id }}">{{ $pa->paterno }} {{ $pa->materno }},
                                        {{ $pa->nombre }}</option>
                                @endforeach
                            </select>
                            <select id="cmbTipoProfesionalAnestesiologoTemp">
                                <option value="0">--Seleccionar--</option>
                                @foreach ($cqx_tipo_profesionales as $tp)
                                    @if ($tp->flag_profesional == 2)
                                        <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <table id="table_medico_anestesiologo" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Apellidos y Nombres</th>
                                            <th style="width:30%;padding-left:20px;">Cargo</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <div class="col-sm-12">
                                <table class="table table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Apellidos y Nombres</th>
                                            <th style="width:30%">Cargo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($profesionales_anestesiologos as $pq)
                                            <tr>
                                                <td>{{ $pq->paterno }} {{ $pq->materno }}, {{ $pq->nombre }}</td>
                                                <td>{{ $pq->tipo_profesional }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <!-- FIN ANESTESIOLOGOS QUE INTERVIENEN EN LA OPERACIÓN -->
                </div>
            </div>
            <!-- Fin PROFESIONALES QUE INTERVIENEN -->
        @else
            <?php
            $lugar_operacion = '';
            switch ($intervencion->id_lugar_operacion) {
                case 1:
                    $lugar_operacion = 'HOSPITALIZADO';
                    break;
                case 2:
                    $lugar_operacion = 'CLÍNICA DE DÍA';
                    break;
            }
            ?>

            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">FECHA DE REGISTRO</label>
                        <input tabindex="6" type="date" class="form-control" name="fecha_registro"
                            id="fecha_registro" style="text-align:left;">
                    </div>
                    <div class="col-md-3">
                        <label for="">HORA DE REGISTRO</label>
                        <input tabindex="7" type="time" class="form-control" id="hora_registro"
                            name="hora_registro" style="text-align:left">
                    </div>
                    <div class="col-md-3">
                        <label for="">A SER OPERADO EN:</label>
                        <select name="id_lugar_operacion" id="id_lugar_operacion" class="form-control input-sm">
                            <option value="0">Seleccione</option>
                            <option value="1"
                                {{ !empty($datos_interconsulta) && $datos_interconsulta->id_tipo_atencion == 3 ? 'selected' : ($intervencion->id_lugar_operacion == 1 ? 'selected' : '') }}>
                                HOSPITALIZADO</option>
                            <option value="2" @if (
                                ($id_tipo_origen == 1 && $intervencion->es_nuevo_admision_hospitalizacion) ||
                                    $intervencion->id_lugar_operacion == 2) selected @endif>CLÍNICA DE DÍA
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">FECHA PROBABLE DE INTERVENCIÓN:</label>
                        @if ($intervencion->es_nuevo_admision_hospitalizacion)
                            <input tabindex="6" type="date" class="form-control"
                                name="fecha_probable_intervencion" id="fecha_probable_intervencion"
                                value="{{ \Carbon\Carbon::parse($fecha_registro_admision)->format('Y-m-d') }}"
                                style="text-align:left;">
                        @else
                            <input tabindex="6" type="date" class="form-control"
                                name="fecha_probable_intervencion" id="fecha_probable_intervencion"
                                value="{{ \Carbon\Carbon::parse($intervencion->fecha_probable)->format('Y-m-d') }}"
                                style="text-align:left;">
                        @endif
                    </div>
                </div>
            @else
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">FECHA DE REGISTRO: </label>
                        <p>{{ $intervencion->fecha_registro ? \Carbon\Carbon::parse($intervencion->fecha_registro)->format('Y-m-d') : '' }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label for="">HORA DE REGISTRO</label>
                        <p>{{ $intervencion->hora_registro ? \Carbon\Carbon::parse($intervencion->hora_registro)->format('H:i') : '' }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label for="">A SER OPERADO EN:</label>
                        <p> {{ $lugar_operacion }}</p>
                    </div>
                    <div class="col-md-3">
                        <label for="">FECHA PROBABLE DE INTERVENCIÓN: </label>
                        <p>{{ !empty($intervencion->fecha_probable) ? date('d-m-Y', strtotime($intervencion->fecha_probable)) : '' }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="form-group">
                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                    <div class="col-md-4">
                        <label for="">TURNOS QUIRURGICOS</label>
                        <input type="text" name="id_prioridad_programacion" id="id_prioridad_programacion"
                            class="form-control" oninput="filtrarNumero(this)"
                            value="{{ !empty($intervencion->id_prioridad_programacion) ? $intervencion->id_prioridad_programacion : 1 }}">
                    </div>
                    <div class="col-md-4">
                        <label for="">TIPO DE CIRUGIA</label>
                        <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control">
                            <option value="0">Seleccione</option>
                            @foreach ($tipo_cirugias as $row)
                                <option value="{{ $row->id }}"
                                    @if ($row->id == $intervencion->emergencia_tipo_cirugia) selected @endif>{{ $row->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-md-4">
                        <label for="">TURNOS QUIRURGICOS</label>
                        <p>{{ !empty($intervencion->id_prioridad_programacion) ? $intervencion->id_prioridad_programacion : '0' }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label for="">TIPO DE CIRUGIA</label>
                        <p>{{ $intervencion->tipo_cirugia }}</p>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <div class="col-md-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <label>REQUIERE INTERNAMIENTO PRE QX</label>
                </div>
                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                    <div class="col-md-2" style="margin-left:0px;padding-left:0px">
                        <button tabindex="100" type="button" id="addRequiereInternamientoPreQx"
                            class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                            {{ !empty($datos_interconsulta) && $datos_interconsulta->id_tipo_atencion == 3 ? 'disabled' : '' }}
                            autocomplete="off">
                            <div class="handle"></div>
                        </button>
                        <input type="hidden" id="has_requiere_internamiento_preqx"
                            name="has_requiere_internamiento_preqx" value="0">
                    </div>
                    <div class="col-md-2">
                        <fieldset id="fsRequiereInternamientoPreQx">
                            <select name="id_tiempo_internamiento_preqx" id="id_tiempo_internamiento_preqx"
                                class="form-control input-sm">
                                <option value="0">Seleccione</option>
                                @foreach ($cqx_dias as $row)
                                    @if ($row->flag == 2)
                                        <option value="{{ $row->id }}"
                                            @if ($intervencion->is_requiere_internamiento_preqx) @if ($intervencion->id_tiempo_internamiento_preqx == $row->id) selected @endif
                                            @endif>{{ $row->descripcion }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                @else
                    @if ($intervencion->is_requiere_internamiento_preqx)
                        <div class="col-md-2">
                            <p>SI</p>
                        </div>
                        <div class="col-md-2">
                            <p>{{ $intervencion->tiempo_internamiento_preqx }}</p>
                        </div>
                    @else
                        <div class="col-md-2">NO</div>
                    @endif
                @endif
            </div>
            <div class="form-group">
                <div class="col-md-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <label>DIAS DE HOSPITALIZADO POST QX</label>
                </div>
                @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                    <div class="col-md-2" style="margin-left:0px;padding-left:0px">
                        <button tabindex="100" type="button" id="addDiasHospitalizadoPostQx"
                            class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                            {{ !empty($datos_interconsulta) && $datos_interconsulta->id_tipo_atencion == 3 ? 'disabled' : '' }}
                            autocomplete="off">
                            <div class="handle"></div>
                        </button>
                        <input type="hidden" id="is_requiere_hospitalizacion_postqx"
                            name="is_requiere_hospitalizacion_postqx" value="0">
                    </div>
                    <div class="col-md-2">
                        <fieldset id="fsDiasHospitalizadoPostQx">
                            <select name="id_tiempo_hospitalizacion_postqx" id="id_tiempo_hospitalizacion_postqx"
                                class="form-control input-sm">
                                <option value="0">Seleccione</option>
                                @foreach ($cqx_dias as $row)
                                    @if ($row->flag == 3)
                                        <option value="{{ $row->id }}"
                                            @if ($intervencion->is_hospitalizacion_postqx) @if ($intervencion->id_tiempo_hospitalizacion_postqx == $row->id) selected @endif
                                            @endif>{{ $row->descripcion }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                @else
                    @if ($intervencion->is_hospitalizacion_postqx)
                        <div class="col-md-2">
                            <p>SI</p>
                        </div>
                        <div class="col-md-2">
                            <p>{{ $intervencion->tiempo_hospitalizacion_postqx }}</p>
                        </div>
                    @else
                        <div class="col-md-2">
                            <p>NO</p>
                        </div>
                    @endif
                @endif
            </div>
            <div class="form-group" style="margin-bottom: 12px;">
                <div class="col-md-2">
                    <label for="">F. INTERVENCIÓN</label>
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        @if ($intervencion->es_nuevo_admision_hospitalizacion)
                            <input type="text" class="form-control datepicker input-sm" id="fecha_intervencion"
                                name="fecha_intervencion" readonly
                                value="{{ \Carbon\Carbon::parse($fecha_registro_admision)->format('d-m-Y') }}">
                        @else
                            <!-- <input type="text" class="form-control datepicker input-sm" id="fecha_intervencion" name="fecha_intervencion" readonly value="{{ date('d-m-Y', strtotime($intervencion->fecha_probable)) }}"> -->
                            <input tabindex="6" type="date" class="form-control" name="fecha_intervencion"
                                id="fecha_intervencion"
                                value="{{ \Carbon\Carbon::parse($intervencion->fecha_probable)->format('Y-m-d') }}"
                                style="text-align:left;">
                        @endif
                    @else
                        <p>{{ date('d-m-Y', strtotime($intervencion->fecha_intervencion)) }}</p>
                    @endif
                </div>
                <div id="electiva_div" style="display: none;">
                    <div class="col-md-1" style="">
                        <label for="">INICIO</label>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <input type="time" class="form-control input-sm" name="hora_inicio_intervencion"
                                id="hora_inicio_intervencion" value="{{ $intervencion->hora_inicio_prog_qx }}">
                        @else
                            <p>{{ date('H:i', strtotime($intervencion->hora_inicio_prog_qx)) }}</p>
                        @endif
                    </div>
                    <div class="col-md-1" style="">
                        <label for="">FIN</label>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                            <input type="time" class="form-control input-sm" name="hora_fin_intervencion"
                                id="hora_fin_intervencion" value="{{ $intervencion->hora_fin_prog_qx }}">
                        @else
                            <p>{{ date('H:i', strtotime($intervencion->hora_fin_prog_qx)) }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-2" style="">
                    <label for="">DURACIÓN</label>
                    <input type="text" id="duracion_cirugia_calculada" class="form-control" disabled>
                </div>
                <div class="col-md-2">
                    <label for="">SALA INTERVENCIÓN</label>
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <select name="id_sala_intervencion" id="id_sala_intervencion" class="form-control input-sm">
                            <option value="0">Seleccione</option>
                            @foreach ($cq_salas as $row)
                                <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                            @endforeach
                        </select>
                    @else
                        <p>{{ $intervencion->sala_intervencion }}</p>
                    @endif
                </div>
                <div class="col-md-4" style="">
                    <label for="">TIPO ANESTESIA</label>
                    @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                        <select name="id_tipo_anestesia" id="id_tipo_anestesia" class="form-control input-sm">
                            <option value="0">Seleccione</option>
                            @foreach ($tipos_anestesia as $row)
                                <option value="{{ $row->id }}">{{ $row->descripcion }}</option>
                            @endforeach
                        </select>
                    @else
                        <p>{{ $intervencion->tipo_anestesia }}</p>
                    @endif
                </div>
            </div>
            @endif

            @if (!$is_origen_emergencia && !$es_traslado_hospitalizacion_acqx)
                @if ($intervencion->es_nuevo_admision_hospitalizacion)
                    <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>DIAGNOSTICO DE INTERVENCIÓN</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                <table>
                                    <tr>
                                        <td>
                                            <button type="button" id="addDiagnosticoQuirurgico"
                                                class="btn btn-primary" data-toggle="modal"
                                                data-target="#addClassModal">Agregar
                                                Diagnostico</button>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                            <br />
                            <table id="tblDiagnosticoQuirurgico" cellspacing="0" width="100%"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="50%">NOMBRE</th>
                                        <th width="15%">TIPO</th>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($diagnosticos_cqx as $key => $row)
                                        <tr>
                                            <?php $tipo = '';
                                            switch ($row->id_tipo_diagnostico):
                                                case 1:
                                                    $tipo = 'PRESUNTIVO';
                                                    break;
                                                case 2:
                                                    $tipo = 'DEFINITIVO';
                                                    break;
                                                case 3:
                                                    $tipo = 'REITERATIVO';
                                                    break;
                                            endswitch;
                                            ?>
                                            <td style="width:60%; 1px solid gray;">
                                                <p>{{ $row->codigo_diagnostico }} -
                                                    {{ $row->descripcion_diagnostico }}</p>
                                            </td>
                                            <td style="width:10%; 1px solid gray;">{{ $tipo }}</td>
                                            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                                            @endif
                                            <?php $i++; ?>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                @if (!$intervencion->es_nuevo_admision_hospitalizacion)
                    <div class="panel panel-info panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center"><b>PROCEDIMIENTOS DE INTERVENCIÓN</b></div>
                        </div>
                        <div class="panel-body">
                            <!-- @if (!$is_origen_emergencia) -->
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table class="display" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Código y Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($procedimientos as $p)
                                                <tr>
                                                    <td>{{ $p->codigo_cpms }} - {{ $p->descripcion_cpms }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--
@else
-->
                            <!-- <div class="form-group">
                                        <div class="col-sm-2">
                                            <button type="button" id="addProcedimientoIntervencion" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClassModal">Agregar Procedimiento</button>
                                        </div>
                                    </div>
                                    <div class="form-group" style="display:none">
                                        <select id="cmbProcedimientoIntervencionTemp">
                                            <option value="0">--Seleccionar--</option>
                                            @foreach ($procedimientos as $p)
<option value="{{ $p->id }}">{{ $p->codigo }} - {{ $p->descripcion }}</option>
@endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                        <table id="table_procedimiento_intervencion" class="display" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Código y Descripción</th>
                                                <th style="width:10%;padding-left:30px;">Eliminar</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div> -->
                            <!-- @endif -->
                        </div>
                    </div>
                @endif

                <!-- Incio PROFESIONALES QUE INTERVIENEN -->
                <div class="panel panel-info panel-sm">
                    <div class="panel-heading">
                        <div class="panel-title text-center"><b>PROFESIONALES QUE INTERVIENEN</b></div>
                    </div>
                    <div class="panel-body">
                        <!-- INICIO CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN -->
                        @if (!$is_origen_emergencia)
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table class="display" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Apellidos y Nombres</th>
                                                <th style="width:30%">Cargo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($profesionales_quirugicos as $pq)
                                                <tr>
                                                    <td>{{ $pq->paterno }} {{ $pq->materno }},
                                                        {{ $pq->nombre }}</td>
                                                    <td>{{ $pq->tipo_profesional }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button tabindex="75" type="button" id="addMedicoCirujano"
                                        class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#addClassModal">Agregar Cirujano que intervienen en la
                                        operación</button>
                                </div>
                            </div>
                            <div class="form-group" style="display:none">
                                <select id="cmbProfesionalesCirujanosTodosTemp">
                                    <option value="">--Seleccionar--</option>
                                    @foreach ($lista_profesionales_cirujanos as $pa)
                                        <option value="{{ $pa->id }}">{{ $pa->paterno }}
                                            {{ $pa->materno }}, {{ $pa->nombre }}</option>
                                    @endforeach
                                </select>
                                <select id="cmbTipoProfesionalCirujanoTemp">
                                    <option value="0">--Seleccionar--</option>
                                    @foreach ($cqx_tipo_profesionales as $tp)
                                        @if ($tp->flag_profesional == 1)
                                            <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table id="table_medico_cirujano" class="display" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>Apellidos y Nombres</th>
                                                <th style="width:30%;padding-left:20px;">Cargo</th>
                                                <th style="width:10%;padding-left:30px;">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        <!-- FIN CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN -->

                        <!-- INICIO ANESTESIOLOGOS QUE INTERVIENEN EN LA OPERACIÓN -->
                        <hr>
                        @if ($intervencion->is_validated == null || !$intervencion->is_validated)

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button tabindex="75" type="button" id="addMedicoAnestesiologo"
                                        class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#addClassModal">Agregar Anestesiólogo que intervienen en la
                                        operación</button>
                                </div>
                            </div>
                            <div class="form-group" style="display:none">
                                <select id="cmbProfesionalesAntesiologiaTemp">
                                    <option value="">--Seleccionar--</option>
                                    @foreach ($lista_profesionales_anestesiologos as $pa)
                                        <option value="{{ $pa->id }}">{{ $pa->paterno }}
                                            {{ $pa->materno }}, {{ $pa->nombre }}</option>
                                    @endforeach
                                </select>
                                <select id="cmbTipoProfesionalAnestesiologoTemp">
                                    <option value="0">--Seleccionar--</option>
                                    @foreach ($cqx_tipo_profesionales as $tp)
                                        @if ($tp->flag_profesional == 2)
                                            <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table id="table_medico_anestesiologo" class="display" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>Apellidos y Nombres</th>
                                                <th style="width:30%">Cargo</th>
                                                <th style="width:10%">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table class="display" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Apellidos y Nombres</th>
                                                <th style="width:30%">Cargo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($profesionales_anestesiologos as $pq)
                                                <tr>
                                                    <td>{{ $pq->paterno }} {{ $pq->materno }},
                                                        {{ $pq->nombre }}</td>
                                                    <td>{{ $pq->tipo_profesional }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        <!-- FIN ANESTESIOLOGOS QUE INTERVIENEN EN LA OPERACIÓN -->
                    </div>
                </div>
                <!-- Fin PROFESIONALES QUE INTERVIENEN -->
            @endif
            @if ($intervencion->is_validated == null || !$intervencion->is_validated)
                <div class="panel panel-info panel-bordered panel-sm panel-body">
                    <div class="form-group">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                            <label>Sugerencia</label>
                        </div>
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                            <input type="hidden" id="is_sugerencia_medica" name="is_sugerencia_medica"
                                value="0">
                            <button tabindex="110" type="button" id="addSugerenciaMedica"
                                class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                                autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                    </div>
                    <div id="fsSugerenciaMedica" style="display: none">
                        <fieldset class="panel panel-info panel-bordered panel-sm">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control" id="sugerencia_medica" name="sugerencia_medica" rows="4" cols="50"></textarea>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            @else
                <div class="panel panel-info panel-bordered panel-sm panel-body">
                    <div class="form-group">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                            <label>Sugerencia</label>
                        </div>
                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                            <button tabindex="110" type="button"
                                class="btn btn-lg btn-toggle {{ $intervencion->is_sugerencia_medica ? 'active' : '' }}"
                                data-toggle="button"
                                aria-pressed="{{ $intervencion->is_sugerencia_medica ? 'true' : 'false' }}"
                                autocomplete="off" disabled>
                                <div class="handle"></div>
                            </button>
                        </div>
                    </div>
                    @if ($intervencion->is_sugerencia_medica)
                        <fieldset class="panel panel-info panel-bordered panel-sm">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control" rows="4" cols="50" disabled style="white-space: pre-wrap; resize: none;">{{ !empty($intervencion->sugerencia_medica) ? $intervencion->sugerencia_medica : '' }}</textarea>
                                </div>
                            </div>
                        </fieldset>
                    @endif
                </div>

            @endif
        </div>
</div>
<!--  -->

<div class="row">
    <div class="col-sm-12 text-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Cerrar
        </button>
        @if (!$intervencion->is_validated)
            <button type="button" class="btn btn-success" id="btnValidar">
                Validar
            </button>
        @endif
    </div>
</div>
</div>
</form>
</div>
