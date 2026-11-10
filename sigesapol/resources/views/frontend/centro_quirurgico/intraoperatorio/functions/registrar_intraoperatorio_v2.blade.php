<script>
    var listIdDiagnosticoObjetos_REG = [];

    function configurarAutocompleteProcedimiento(ind) {
        var nivel = $('#id_nivel_intra').val();

        var sexo = $('#sexo').val();

        var genero = sexo == 'MASCULINO' ? 1 : 2;

        $("#nombre_procedimiento" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {

                $.ajax({

                    url: '../obtener_procedimiento_convenio/' +
                        request.term + '/' + nivel + '/' + genero + '/1',

                    dataType: 'json',

                    success: function(data) {

                        response(data);
                    }
                });
            },
            select: function(e, ui) {
                $('#id_procedimiento' + ind)
                    .val(ui.item.id);
                $('#nombre_procedimiento' + ind)
                    .val(ui.item.value);
                return false;
            }
        });
    }

    function cargarGasas(datos) {
        $.each(datos, function(i, item) {
            $('#enfermeriauno' + i).val(item.enfermeria1);
            $('#enfermeriados' + i).val(item.enfermeria2);
            $('#id_gasa' + i).val(item.id);
            var total =
                (parseInt(item.enfermeria1) || 0) +
                (parseInt(item.enfermeria2) || 0);
            $('#total' + i).val(total);
            $('#totalx' + i).val(total);
        });
    }

    function cargarDiagnosticos(ind, idDiagnosticoSeleccionado) {
        var lista_diagnosticos = $("#diagnosticos_prestacion").data("json");

        var listIdDiagnosticoObjetos = [];

        $.each(lista_diagnosticos, function(key, value) {

            listIdDiagnosticoObjetos.push({
                id: value.id,
                nombre: value.codigo + ' - ' + value.nombre
            });

        });
        const selectElement = document.getElementById('miSelect' + ind);

        selectElement.innerHTML = '';

        listIdDiagnosticoObjetos.forEach(function(item) {

            let option = document.createElement('option');

            option.value = item.id;
            option.textContent = item.nombre;

            if (item.id == idDiagnosticoSeleccionado) {
                option.selected = true;
            }

            selectElement.appendChild(option);
        });
    }

    /* function calcularDuracion() {
        const inicio = document.getElementById('cirugia_inicio').value;
        const termino = document.getElementById('cirugia_termino').value;

        if (inicio && termino) {
            // Convertir ambos tiempos a milisegundos desde la medianoche
            const inicioMilis = convertirATiempoEnMilisegundos(inicio);
            const terminoMilis = convertirATiempoEnMilisegundos(termino);

            // Calcular la diferencia en milisegundos
            const diferenciaMilis = terminoMilis - inicioMilis;

            if (diferenciaMilis >= 0) {
                // Convertir la diferencia en formato HH:MM
                const duracion = convertirMilisegundosATiempo(diferenciaMilis);
                document.getElementById('tiempo_real').value = duracion;
            } else {
                alert("El término de la cirugía debe ser después del inicio.");
                document.getElementById('tipo_real').value = "";
            }
        }
    } */

    function calcularDuracion() {
        const inicio = document.getElementById('cirugia_inicio').value;
        const termino = document.getElementById('cirugia_termino').value;

        if (inicio && termino) {
            const inicioMilis = convertirATiempoEnMilisegundos(inicio);
            const terminoMilis = convertirATiempoEnMilisegundos(termino);

            const diferenciaMilis = terminoMilis - inicioMilis;

            if (diferenciaMilis >= 0) {
                document.getElementById('tiempo_real').value = convertirMilisegundosATiempo(diferenciaMilis);
            } else {
                alert("El término de la cirugía debe ser después del inicio.");
                document.getElementById('tiempo_real').value = "";
            }
        }
    }

    function convertirATiempoEnMilisegundos(hora) {
        const [horas, minutos] = hora.split(':').map(Number);
        return (horas * 60 + minutos) * 60 * 1000;
    }

    /* function convertirATiempoEnMilisegundos(hora) {
        const partes = hora.split(':');
        const horas = parseInt(partes[0], 10);
        const minutos = parseInt(partes[1], 10);
        return (horas * 60 * 60 * 1000) + (minutos * 60 * 1000);
    } */

    function convertirMilisegundosATiempo(milis) {
        const horas = Math.floor(milis / (1000 * 60 * 60));
        const minutos = Math.floor((milis % (1000 * 60 * 60)) / (1000 * 60));

        // Asegurar que el formato sea HH:MM
        return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}`;
    }

    function setCurrentTime() {
        var now = new Date();
        var hours = now.getHours().toString().padStart(2, '0'); // Asegurar dos dígitos para la hora
        var minutes = now.getMinutes().toString().padStart(2, '0'); // Asegurar dos dígitos para los minutos
        var formattedTime = hours + ':' + minutes;
        document.getElementById('intra_hora-reg').value = formattedTime;
    }

    function setTodayDate() {
        var today = new Date();
        var year = today.getFullYear();
        var month = (today.getMonth() + 1).toString().padStart(2, '0');
        var day = today.getDate().toString().padStart(2, '0');
        var formattedDate = year + '-' + month + '-' + day;
        document.getElementById('intra_fecha-reg').value = formattedDate;
    }

    function AddCirujanoIntraoperatorio() {
        var newRow = "";
        var ind = $('#tblCirujano tbody tr').length;

        if (ind > 0) {
            $('.id_cirujano').each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
        }

        newRow += '<tr>';
        newRow += '<td style="width:60%;">' +
            '<input type="hidden" name="flag_personales_cirujano[]" value="0"/>' +
            '<input type="text" name="nombre_cirujano[]" ' + 'id="nombre_cirujano' + ind +
            '" class="form-control form-control-2" style="font-weight: 800;" required/>' +
            '<input type="hidden" class="form-control form-control-2 typeahead tt-query id_cirujano" id="id_cirujano' +
            ind + '" name="id_cirujano[]"  data-id=' + ind + '></td>';
        newRow += '<td style="width:20%;"><select id="tipo_profesional_cirujano' + ind +
            '" name="tipo_profesional_cirujano[]" class="form-control form-control-2" style="font-weight: 800;"><option value="">Seleccione un tipo</option></select></td>';
        newRow +=
            '<td style="width:15%; vertical-align: middle;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow += '</tr>';

        $('#tblCirujano tbody').append(newRow);

        var tipo_profesional_interviene = $("#tipo_profesional-cirujano").data("json");
        var $select = $('#tipo_profesional_cirujano' + ind);
        $.each(tipo_profesional_interviene, function(index, value) {
            $select.append($('<option></option>').val(value.id).text(value.descripcion));
        });

        $("#nombre_cirujano" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {
                url_consulta = '{{ url('profesional_que_interviene/') }}/' + request.term;
                $.ajax({
                    url: url_consulta,
                    dataType: "json",
                    success: function(data) {
                        let listaCirujanosSeleccionado = $(".id_cirujano").map(function() {
                            return parseInt($(this).val());
                        }).get();

                        let listaAnestesiologosSelecionado = $(".id_anestesiologo").map(
                            function() {
                                return parseInt($(this).val());
                            }).get();

                        // Filtro que excluye los elementos que estén en ambas listas
                        let filtro = data.filter((element) => {
                            return !listaCirujanosSeleccionado.includes(element.id) &&
                                !listaAnestesiologosSelecionado.includes(element.id);
                        });

                        // Aplicar el límite de los primeros 10 elementos
                        let limitedData = filtro.slice(0, 10);
                        response(limitedData);
                    }
                });
            },
            select: function(e, ui) {
                var id_relacionado = ui.item.id;
                $("#id_cirujano" + ind).val(id_relacionado);
                $("#nombre_cirujano" + ind).prop("readonly", true);
            }
        });
    }

    function AddAnestesiologo() {
        var newRow = "";
        var ind = $('#tblAnestesiologo tbody tr').length;

        if (ind > 0) {
            $('.id_anestesiologo').each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
        }

        newRow += '<tr>';
        newRow += '<td style="width:60%;">' +
            '<input type="hidden" name="flag_personales_anestesiologo[]" value="1" />' +
            '<input type="text" data-toggle="tooltip" data-placement="top"  name="nombre_anestesiologo[]" ' +
            'id="nombre_anestesiologo' + ind +
            '" class="form-control form-control-2" style="font-weight: 800;" required/>' +
            '<input type="hidden" class="form-control form-control-2 typeahead tt-query id_anestesiologo" id="id_anestesiologo' +
            ind +
            '" name="id_anestesiologo[]"  data-id=' + ind + '></td>';
        newRow += '<td style="width:20%;"><select id="tipo_profesional_anestesiologo' + ind +
            '" name="tipo_profesional_anestesiologo[]" class="form-control form-control-2" style="font-weight: 800;"><option value="">Seleccione un tipo</option></select></td>';
        newRow +=
            '<td style="width:15%; vertical-align: middle;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow += '</tr>';
        $('#tblAnestesiologo tbody').append(newRow);

        var tipo_profesional_interviene = $("#tipo_profesional-anestesiologo").data("json");
        var $select = $('#tipo_profesional_anestesiologo' + ind + '');
        $.each(tipo_profesional_interviene, function(index, value) {
            $select.append($('<option></option>').val(value.id).text(value.descripcion));
        });

        $("#nombre_anestesiologo" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {
                url_consulta = '{{ url('profesional_que_interviene/') }}/' + request.term;
                $.ajax({
                    url: url_consulta,
                    dataType: "json",
                    success: function(data) {
                        let listaSirujanosSeleccionado = $(".id_cirujano").map(function() {
                            return parseInt($(this).val());
                        }).get();

                        let listaAnestesiologosSelecionado = $(".id_anestesiologo").map(
                            function() {
                                return parseInt($(this).val());
                            }).get();

                        // Filtro que excluye los elementos que estén en ambas listas
                        let filtro = data.filter((element) => {
                            return !listaSirujanosSeleccionado.includes(element.id) &&
                                !listaAnestesiologosSelecionado.includes(element.id);
                        });

                        // Aplicar el límite de los primeros 10 elementos
                        let limitedData = filtro.slice(0, 10);
                        response(limitedData);
                    }
                });
            },
            select: function(e, ui) {
                var id_anestesiologo = ui.item.id;
                $("#id_anestesiologo" + ind).val(id_anestesiologo);
                $("#nombre_anestesiologo" + ind).prop("readonly", true);
            }
        });
    }

    function AddEnfermeriaInstrumentista() {
        let id_especialidad = 8;
        var newRow = "";
        var ind = $('#tblEnfermeriaInstrumentista tbody tr').length;

        if (ind > 0) {
            $('.id_enfermeria_instrumentista').each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
        }

        newRow += '<tr>';
        newRow += '<td style="width:60%;">' +
            '<input type="hidden" name="flag_personales_enfermero[]" value="2" />' +
            '<input type="text" data-toggle="tooltip" data-placement="top"  name="nombre_enfermeria_instrumentista[]"  ' +
            'id="nombre_enfermeria_instrumentista' + ind +
            '"  class="form-control form-control-2" style="font-weight: 800;" required />' +
            '<input type="hidden" class="form-control form-control-2 typeahead tt-query id_enfermeria_instrumentista" id="id_enfermeria_instrumentista' +
            ind + '" name="id_enfermeria_instrumentista[]"  data-id=' + ind + '></td>';
        newRow += '<td style="width:20%;"><select id="tipo_instrumentista' + ind +
            '" name="tipo_instrumentista[]" class="form-control form-control-2" style="font-weight: 800;"><option value="">Seleccione un tipo</option></select></td>';
        newRow +=
            '<td style="width:15%; vertical-align: middle;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow += '</tr>';
        $('#tblEnfermeriaInstrumentista tbody').append(newRow);

        var tipo_instrumentista = $("#tipo_profesional-instrumentalista").data("json");
        var $select = $('#tipo_instrumentista' + ind + '');
        $.each(tipo_instrumentista, function(index, value) {
            $select.append($('<option></option>').val(value.id).text(value.descripcion));
        });

        $("#nombre_enfermeria_instrumentista" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {
                url_consulta = '{{ url('profesional_que_interviene/') }}/' + request.term + '/' +
                    id_especialidad;
                $.ajax({
                    url: url_consulta,
                    dataType: "json",
                    success: function(data) {
                        let listaInstrumentistaSelecionado = $(".id_enfermeria_instrumentista")
                            .map(function() {
                                return parseInt($(this).val());
                            }).get();
                        let filtro = data.filter((element) => !listaInstrumentistaSelecionado
                            .includes(element.id));
                        response(filtro);
                    }
                });
            },
            select: function(e, ui) {
                var id_enfermeria_instrumentista = ui.item.id;
                $("#id_enfermeria_instrumentista" + ind).val(id_enfermeria_instrumentista);
                $("#nombre_enfermeria_instrumentista" + ind).prop("readonly", true);
            }
        });
    }

    function AddProcedimiento() {
        var newRow = "";
        var ind = $('#tblAddProcedimiento tbody tr').length;
        if (ind > 0) {
            $('.id_procedimiento').each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
        }

        newRow += '<tr>';
        newRow +=
            '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento' +
            ind + '" class="form-control" style="height: 28px !important;font-weight: 800;" required />';
        newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' +
            ind +
            '" name="id_procedimiento[]"  data-id=' + ind + '></td>';
        newRow +=
            '<td><input type="number" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="" id="procedimiento_cantidad' +
            ind + '" name="procedimiento_cantidad[]" value="1" required></td>';
        newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind +
            '" name="miSelect[]"></select></td>';
        newRow +=
            '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow += '</tr>';
        $('#tblAddProcedimiento tbody').append(newRow);

        const selectElement = document.getElementById('miSelect' + ind);
        let defaultOption = document.createElement('option');
        var i = 0;
        listIdDiagnosticoObjetos_REG.forEach((item) => {
            let option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.nombre;
            if (i == 0) {
                option.setAttribute("selected", "selected");
                i++;
            }
            selectElement.appendChild(option);
        });

        var nivel = $('#id_nivel_intra').val();
        var sexo = $('#intra_aseg_sexo-reg').val();
        let genero = "";

        if (sexo === 'M') {
            genero = 1;
        } else if (sexo === 'F') {
            genero = 2;
        }

        $("#nombre_procedimiento" + ind).autocomplete({
            minLength: 1,
            delay: 400,
            source: function(request, response) {
                $.ajax({
                    url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' +
                        genero + '/' + 1,
                    dataType: "json",
                    success: function(data) {
                        let listaProcedimientoSelecionado = $(".id_procedimiento").map(
                            function() {
                                return parseInt($(this).val());
                            }).get();
                        let filtro = data.filter((element) => !listaProcedimientoSelecionado
                            .includes(element.id));
                        response(filtro);
                    }
                });
            },
            select: function(e, ui) {
                var id_procedimiento = ui.item.id;
                $("#id_procedimiento" + ind).val(id_procedimiento);
                $("#nombre_procedimiento" + ind).prop("readonly", true);
            }
        });
    }

    function agregarDiagnosticosAprocedimientos() {
        var lista_diagnosticos = $("#diagnosticos_prestacion").data("json");
        var cuerpo_DIAGNOSTICOS_PRESTACION = '';
        $.each(lista_diagnosticos, function(key, value) {
            cuerpo_DIAGNOSTICOS_PRESTACION += '<option value="' + value.id_diagnostico + '">' + value.codigo +
                " - " + value.nombre +
                '</option>';
        });
        $(".select-diagnostico").html(cuerpo_DIAGNOSTICOS_PRESTACION);
    }

    function AddBalanceFluidos() {
        var newRow = "";
        var ind = $('#tblBalanceFluidos tbody tr').length;

        if (ind > 0) {
            $('.id_balance_ingreso').each(function() {
                var ind_tmp = $(this).data("id");
                if (ind_tmp => ind) {
                    ind = Number(ind_tmp) + 1;
                }
            });
        }

        newRow += '<tr>';
        newRow += '<td>' +
            '<input type="text" data-toggle="tooltip" data-placement="top" name="balance_ingreso[]" id="balance_ingreso' +
            ind + '" class="form-control input-sm" style="text-align:left" />' +
            '<input type="hidden" class="form-control typeahead tt-query id_balance_ingreso" id="id_balance_ingreso' +
            ind + '" name="id_balance_ingreso[]"  data-id=' + ind + '></td>';
        newRow += '<td style="padding-left: 1rem;">' +
            '<input type="text" data-toggle="tooltip" data-placement="top"  name="balance_egreso[]" id="balance_egreso' +
            ind + '" class="form-control input-sm" style="text-align:left" /></td>';
        newRow += '<td style="padding-left: 1rem;">' +
            '<input type="text" data-toggle="tooltip" data-placement="top" name="tareapeutica[]"  id="tareapeutica' +
            ind + '" class="form-control input-sm" style="text-align:left" /></td>';
        newRow +=
            '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
        newRow += '</tr>';
        $('#tblBalanceFluidos tbody').append(newRow);
    }

    function sumarValores(enfermeriaunoID, enfermeriadosID, totalID, totalxID) {
        var valorUno = parseFloat($(enfermeriaunoID).val()) || 0;
        var valorDos = parseFloat($(enfermeriadosID).val()) || 0;
        var suma = valorUno + valorDos;
        $(totalID).val(suma);
        $(totalxID).val(suma);
    }

    function validaCampos(response) {
        $.each(response.errors, function(field, error) {
            if (field.startsWith('balance_ingreso')) {
                $('input[id^="balance_ingreso"]').each(function() {
                    let inputId = $(this).attr('id');
                    let match = inputId.match(/(\D+)(\d+)/);
                    if (match) {
                        index = match[2];
                        var $inputField = $('#balance_ingreso' + index);
                        if ($inputField.length) {
                            $inputField.addClass('is-invalid');
                        }
                    }
                });
            } else if (field.startsWith('balance_egreso')) {
                $('input[id^="balance_egreso"]').each(function() {
                    let inputId = $(this).attr('id');
                    let match = inputId.match(/(\D+)(\d+)/);
                    if (match) {
                        index = match[2];
                        var $inputField = $('#balance_egreso' + index);
                        if ($inputField.length) {
                            $inputField.addClass('is-invalid');
                        }
                    }
                });
            } else if (field.startsWith('tareapeutica')) {
                $('input[id^="tareapeutica"]').each(function() {
                    let inputId = $(this).attr('id');
                    let match = inputId.match(/(\D+)(\d+)/);
                    if (match) {
                        index = match[2];
                        var $inputField = $('#tareapeutica' + index);
                        if ($inputField.length) {
                            $inputField.addClass('is-invalid');
                        }
                    }
                });
            } else if (field.startsWith('enfermeriauno')) {
                $('input[id^="enfermeriauno"]').each(function() {
                    let inputId = $(this).attr('id');
                    let match = inputId.match(/(\D+)(\d+)/);
                    if (match) {
                        index = match[2];
                        var $inputField = $('#enfermeriauno' + index);
                        if ($inputField.length) {
                            $inputField.addClass('is-invalid');
                        }
                    }
                });
            } else if (field.startsWith('enfermeriados')) {
                $('input[id^="enfermeriados"]').each(function() {
                    let inputId = $(this).attr('id');
                    let match = inputId.match(/(\D+)(\d+)/);
                    if (match) {
                        index = match[2];
                        var $inputField = $('#enfermeriados' + index);
                        if ($inputField.length) {
                            $inputField.addClass('is-invalid');
                        }
                    }
                });
            } else if (field.startsWith('enfermeriatres')) {
                $('input[id^="enfermeriatres"]').each(function() {
                    let inputId = $(this).attr('id');
                    let match = inputId.match(/(\D+)(\d+)/);
                    if (match) {
                        index = match[2];
                        var $inputField = $('#enfermeriatres' + index);
                        if ($inputField.length) {
                            $inputField.addClass('is-invalid');
                        }
                    }
                });
            } else if (field.startsWith('diagnostico_estado')) {
                let checkboxesSeleccionados = $('input[name="diagnostico_estado[]"]:checked').length;
                if (checkboxesSeleccionados === 0) {
                    $('input[name="diagnostico_estado[]"]').addClass('is-invalid');
                }
            } else if (field.startsWith('nic_estado')) {
                let minimi_un_hijo_por_cada_padre = false;
                $('input[id^="nic_estado"]').each(function() {
                    let checkboxName = $(this).attr('name');
                    let match = checkboxName.match(/nic_estado\[(\d+)\]/);
                    if (match) {
                        let padreId = match[1];
                        let checkboxesHijos = $(`input[name^="nic_estado[${padreId}"]`);
                        let hijosChequeados = checkboxesHijos.filter(':checked').length;
                        if (hijosChequeados === 0) {
                            checkboxesHijos.addClass('is-invalid');
                            minimi_un_hijo_por_cada_padre = true;
                            console.log(hijosChequeados);
                        } else {
                            console.log('no chekeado');
                            checkboxesHijos.removeClass('is-invalid');
                            minimi_un_hijo_por_cada_padre = false;
                        }
                    }
                });
                if (minimi_un_hijo_por_cada_padre) {
                    $('#nicError').html(
                        'Debe seleccionar al menos una intervención en fermería por cada sección').show();
                } else {
                    $('#nicError').html('').hide();
                }
            } else {
                console.log(field);
                var $inputField = $('#' + field);
                if ($inputField.length) {
                    $inputField.addClass('is-invalid');
                    $inputField.next('.invalid-feedback').text(error[0]).show();
                }
            }
        });

        let cantidadCirujano = 0;
        let cantidadAnestesiologo = 0;
        let cantidadInstrumentista = 0;
        let cantidadProcedimiento = 0;
        let cantidadBalanceFluidos = 0;
        let cantidadDiagnosticosEnfermeriaCkekeado = 0;
        let cantidadNicCkekeado = 0;

        $('input[name="nombre_enfermeria_instrumentista[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
            } else {
                cantidadInstrumentista = 1;
                $(this).removeClass('is-invalid');
            }
        });

        $('select[name="tipo_instrumentista[]"]').each(function(index) {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
                if ($('input[name="tipo_instrumentista[]"]').eq(index).val() !== '') {

                }
            }
        });

        $('input[name="nombre_procedimiento[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
            } else {
                cantidadProcedimiento = 1;
                $(this).removeClass('is-invalid');
            }
        });

        $('input[name="procedimiento_cantidad[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        $('select[name="miSelect[]"]').each(function(index) {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
                if ($('input[name="miSelect[]"]').eq(index).val() !== '') {

                }
            }
        });

        if (cantidadProcedimiento == 0) {
            $('#addProcedimiento').addClass('is-invalid');
            $('#addProcedimiento').next('.invalid-feedback').text('Debe de ingresar por lo menos un procedimiento')
                .show();
        } else {
            $('#addProcedimiento').removeClass('is-invalid');
            $('#addProcedimiento').removeClass('invalid-feedback');
        }

        $('input[name="balance_ingreso[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
            } else {
                cantidadBalanceFluidos = 1;
                $(this).removeClass('is-invalid');
            }
        });

        $('input[name="balance_egreso[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
            } else {
                cantidadBalanceFluidos = 1;
                $(this).removeClass('is-invalid');
            }
        });

        $('input[name="tareapeutica[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
            } else {
                cantidadBalanceFluidos = 1;
                $(this).removeClass('is-invalid');
            }
        });

        $('input[name="diagnostico_estado[]"]').each(function() {
            if ($(this).is(':checked')) {
                cantidadDiagnosticosEnfermeriaCkekeado++;
            }
        });
    }

    $(document).ready(function() {
        document.getElementById('cirugia_inicio').addEventListener('change', calcularDuracion);
        document.getElementById('cirugia_termino').addEventListener('change', calcularDuracion);
        setTodayDate();
        setCurrentTime();
    });

    $('#btnGuardarIntraOperatorio').on("click", function(e) {
        e.preventDefault();
        Swal.fire({
            title: '<div class="titulo-padding"><p>Procesando el envío...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            backdrop: true,
            customClass: {
                header: 'swal2-header-generar'
            },
            didOpen: () => {
                Swal.showLoading();
                $('#formIntraoperario').submit();
            }
        });
    });

    $('#formIntraoperario').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        const id_intraoperatorio = $("#id_intraoperatorio").val();
        const esParaActualizar = id_intraoperatorio !== "" ? true : false;

        $.ajax({
            url: esParaActualizar ? "/actualizar_intraoperatorio_v2" : $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.close();
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Los datos se han guardado correctamente.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        $.ajax({
                            url: "/detalle_intraoperario_ajax/" + response
                                .id_intraoperatorio,
                            type: "GET",
                            data: {
                                _token: $('input[name="_token"]').val()
                            },
                            success: function(result) {
                                cargarInformacionDetalleIntraoperario(result
                                    .data);
                                $('#modal-intraoperatorio').one(
                                    'hidden.bs.modal',
                                    function() {
                                        $('#modal-detalle-intraoperatorio')
                                            .modal('show');
                                    });
                                $('#modal-intraoperatorio').modal('hide');
                            }
                        });
                    });
                } else {
                    validaCampos(response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Por favor, ingrese los datos correctos en los campos requeridos del formulario.'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });
    });

    //ANULAR CIRUJANOS
    $('body').on('click', '.AnularFilaCirujano', function() {
        var id_medico = $(this).closest('tr').find('.AnularFilaCirujano').attr('data-id_cirujano_registro');
        var $tr = $(this).closest('tr');
        $('#modalAnularProfesional').data('tr', $tr);
        $('#modalAnularProfesional').data('cirujano', id_medico);
        $('#anularFormularioProfesional').trigger("reset");
        $('#tituloFormularioProfesional-anular').html("Anular profesional que interviene en la operación");
        $('#id_medico_anular').val(id_medico);
        $('#modalAnularProfesional').modal('show');
    });

    //ANULAR ANESTESIOLOGOS
    $('body').on('click', '.AnularFilaAnestesiologos', function() {
        var id_medico = $(this).closest('tr').find('.AnularFilaAnestesiologos').attr(
            'data-id_anestesiologo_registro');
        var $tr = $(this).closest('tr');
        $('#modalAnularProfesional').data('tr', $tr);
        $('#modalAnularProfesional').data('anestesiologo', id_medico);
        $('#anularFormularioProfesional').trigger("reset");
        $('#tituloFormularioProfesional-anular').html("Anular profesional que interviene en la operación");
        $('#id_medico_anular').val(id_medico);
        $('#modalAnularProfesional').modal('show');
    });

    //ANULAR INSTRUMENTISTA
    $('body').on('click', '.AnularFilaInstrumentista', function() {
        var id_medico = $(this).closest('tr').find('.AnularFilaInstrumentista').attr(
            'data-id_instrumentista_registro');
        var $tr = $(this).closest('tr');
        $('#modalAnularProfesional').data('tr', $tr); // Guardar el <tr> en el modal
        $('#modalAnularProfesional').data('instrumentista', id_medico);
        $('#tituloFormularioProfesional-anular').html("Anular profesional que interviene en la operación");
        $('#modalAnularProfesional').modal('show');
        $('#id_medico_anular').val(id_medico);
    });

    $('#GuardarAnularProfesional').click(function(e) {
        e.preventDefault();
        var motivoAnulacion = $('#anulacion_motivo').val();

        if (motivoAnulacion == null || motivoAnulacion.length <= 5) {
            Swal.fire({
                title: "Verifique los campos ingresados",
                text: "Proceso cancelado. Ingrese un motivo de la anulación mayor a 5 caracteres para el campo motivo de anulación.",
                icon: "error",
                showConfirmButton: false,
                timer: 2500
            });

            $('#anulacion_motivo').addClass('borde-alerta');
            setTimeout(function() {
                $('#anulacion_motivo').focus();
            }, 1000);
            return;
        } else {
            $('#anulacion_motivo').removeClass('borde-alerta');
        }

        var token = $('#_token').val();
        var route = '{{ url('motivo_anulacion_cirujano') }}';
        var $tr = $('#modalAnularProfesional').data('tr');
        var cirujano = $('#modalAnularProfesional').data('cirujano');
        var anestesiologo = $('#modalAnularProfesional').data('anestesiologo');
        var instrumentalista = $('#modalAnularProfesional').data('instrumentalista');

        Swal.fire({
            title: "¿Desea Anular el registro?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, anular",
            cancelButtonText: "Cancelar",
            dangerMode: true,
            customClass: {
                confirmButton: 'btn-red',
                cancelButton: 'btn-cancel'
            }
        }).then((willDelete) => {
            if (willDelete) {
                var formData = new FormData($("#anularFormularioProfesional")[0]);
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
                            if (data != 0) {
                                $('#anularFormularioProfesional').trigger("reset");
                                $('#modalAnularProfesional').modal('hide');
                                $tr.addClass('eliminado');

                                if (cirujano) {
                                    $('button[data-id_cirujano_registro="' + cirujano +
                                        '"]').hide();
                                }

                                if (anestesiologo) {
                                    $('button[data-id_anestesiologo_registro="' +
                                        anestesiologo + '"]').hide();
                                }

                                if (instrumentalista) {
                                    $('button[data-id_instrumentista_registro="' +
                                        instrumentalista + '"]').hide();
                                }

                                Swal.fire({
                                    buttons: false,
                                    title: "Anulación Exitosa",
                                    text: "El registro fue anulado exitosamente.",
                                    icon: "success",
                                    timer: 1500,
                                });
                            } else {
                                Swal.fire({
                                    buttons: false,
                                    title: "Error de Servicio de Registros",
                                    text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                                    icon: "error",
                                    timer: 2500,
                                });
                            }
                        } else {
                            Swal.fire({
                                buttons: false,
                                title: "Error de Servicio de Registros",
                                text: "Verifique que todos los datos ingresados sean los correctos, e caso que el problema persista, comunicarse con el área de informática.",
                                icon: "error",
                                timer: 2500,
                            });
                        }
                    }
                });

            } else {
                swal({
                    buttons: false,
                    title: "Operación Cancelada",
                    text: "Proceso de Registro Cancelado.",
                    icon: "error",
                    timer: 1500,
                });
            }
        });
    });

    $('#btnCancelar').on('click', function() {
        $('#modalAnularProfesional').modal('hide');
    });

    $('#btnCancelar').on('hidden.bs.modal', function() {
        $('body').addClass('modal-open');
    });

    $('#modalAnularProfesional').on('hidden.bs.modal', function() {
        $('body').addClass('modal-open');
    });

    $('#modal-intraoperatorio').on('hidden.bs.modal', function() {
        $('#formIntraoperario')[0].reset();
        setTodayDate();
        setCurrentTime();
        $('a[href="#tab1default"]').tab('show');
        $("#tblEnfermeriaInstrumentistaRows").empty();
        $("#tblAddProcedimientoRows").empty();
        $("#tblBalanceFluidosRows").empty();
        $("#id_asegurado").val("");
        $("#id_intervencion").val("");
        $("#id_nivel_intra").val("");
        $("#id_intraoperatorio").val("");
        $("#id_prestacion").val("");
        $("#tipo_real").val("");
        $("#id_hospitalizacion").val("");
        $("#nro_doc_ident").val("");
        $("#id_gasa0").val("");
        $("#id_gasa1").val("");
    });

    $('#addCirujanoIntraoperatorio').on('click', function() {
        AddCirujanoIntraoperatorio();
    });

    $('#addAnestesiologo').on('click', function() {
        AddAnestesiologo();
    });

    $('#addEnfermeriaInstrumentista').on('click', function() {
        AddEnfermeriaInstrumentista();
    });

    $('#addProcedimientoPlus').on('click', function() {
        AddProcedimiento();
        agregarDiagnosticosAprocedimientos();
    });

    $('#addBalanceFluidos').on('click', function() {
        AddBalanceFluidos();
    });

    $('#enfermeriauno0, #enfermeriados0').on('input', function() {
        sumarValores('#enfermeriauno0', '#enfermeriados0', '#total0', '#totalx0');
    });
    $('#enfermeriauno1, #enfermeriados1').on('input', function() {
        sumarValores('#enfermeriauno1', '#enfermeriados1', '#total1', '#totalx1');
    });

    $('#tblCirujano tbody, #tblAnestesiologo tbody, #tblEnfermeriaInstrumentista tbody, #tblBalanceFluidos tbody, #tblAddProcedimiento tbody')
        .on('click', 'button.deleteFila', function() {
            $(this).closest('tr').remove();
        });
</script>