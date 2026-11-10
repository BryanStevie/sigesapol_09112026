<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="tipo_estrategia">Tipo de Estrategia:</label>
            <div>
                <label class="radio-inline">
                    <input type="radio" name="tipo_estrategia" value="1" checked> Individual
                </label>
                <label class="radio-inline">
                    <input type="radio" name="tipo_estrategia" value="2"> Masivo
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-3" id="divcant_personas" style="display:none">
        <div class="form-group">
            <label for="cant_personas">Cant. Personas Aten:</label>
            <input type="number" name="cant_personas" id="cant_personas" class="form-control" placeholder="">
        </div>
    </div>
    <div class="col-md-3" id="divEstadoEstrategia">
        <div class="form-group">
            <label for="estado_estrategia">Estado estrategia:</label>
            <select name="estado_estrategia_form" id="estado_estrategia_form" class="form-control">
                <option value="">Seleccionar</option>
                <option value="1">Protegido</option>
                <option value="2">Vacunado</option>
            </select>
        </div>
    </div>
</div>

<style>
    .swal-custom-title {
        padding-top: 20px;
        /* Ajusta esto según necesites */
        padding-bottom: 10px;
    }
</style>



<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="establecimiento">Nombre de la IPRESS</label>
            <select id="id_establecimiento_programacion" name="id_establecimiento_programacion"
                class="form-control select2" required style="width: 250px;" disabled>
                <option value="" {{ empty($id_establecimiento) ? 'selected' : '' }}>- Seleccionar IPRESS -
                </option>
                @foreach ($establecimientos as $e)
                    <option value="{{ $e->id }}" {{ $e->id == $id_establecimiento ? 'selected' : '' }}>
                        {{ $e->nombre }}
                    </option>
                    break;
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="tipo_atencion">Tipo Atención</label>
            <select name="tipo_atencion" id="tipo_atencion" class="form-control select2" required disabled>
                @foreach ($tiposAtencion as $item)
                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="ugipress">UPRESS</label>
            <select id="ugipress" name="ugipress" class="form-control select2" required style="width: 250px;" disabled>
                @foreach ($ugipress as $e)
                    <option value="{{ $e->id }}">
                        {{ $e->nombre }}
                    </option>
                    break;
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="nro_registro">Nro Registro</label>
            <input type="text" id="nro_registro" name="nro_registro" class="form-control" placeholder="Nro Registro"
                maxlength="20" readonly>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label for="periodo">Periodo</label>
            <input type="text" id="periodo" name="periodo" class="form-control" placeholder="mm/aaaa"
                maxlength="7" required>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="id_upss_ind">UPSS</label>
            <select name="id_upss_ind" id="id_upss_ind" class="form-control select2" required>
                <option value="">Seleccione...</option>
                @foreach (App\Models\Upss::where('flag_estrategias_sanitarias', 1) /*->where("estado", 1) */->orderBy('descripcion_upss')->get() as $item)
                    <option value="{{ $item->id }}">{{ $item->codigo }} - {{ $item->descripcion_upss }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="fecha_atencion_ind">Fecha Atención</label>
            <input type="date" name="fecha_atencion_ind" id="fecha_atencion_ind" class="form-control" required>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="fecha_registro">Fecha Registro</label>
            <input type="date" name="fecha_registro" id="fecha_registro" class="form-control"
                value="{{ date('Y-m-d') }}" required disabled>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6" id="divPaciene">
        <div class="panel panel-default">
            <div class="panel-heading bg-lightgreen">
                <strong>Información del Paciente</strong>
            </div>
            <div class="panel-body">
                <div class="row" style=" display: block;margin-top: -10px; ">
                    <div class="col-md-6">
                        <label>Tipo.DOC.</label>
                        <select name="tipoDocPaciente" id="tipoDocPaciente" class="form-control select2">
                            <option value="">Seleccione...</option>
                            @foreach ($tipos_documento as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Numero DOC.:</label>
                        <div class="input-group">
                            <input type="text" name="nroPaciente" id="nroPaciente" class="form-control">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary" id="btnBuscarPaciente">Buscar</button>
                            </span>
                        </div>
                        <input type="hidden" name="idEstPaciente" id="idEstPaciente" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Apellidos y Nombres:</label>
                        <span id="paciente_nombre"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>N° HC:</label>
                        <span id="paciente_hc"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <label>Parentesco:</label>
                        <span id="paciente_parentesco"></span>
                    </div>
                    <div class="col-md-5">
                        <label>FEC.NAC.:</label>
                        <span id="paciente_fec_nac"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <label>Sexo:</label>
                        <span id="paciente_sexo"></span>
                    </div>
                    <div class="col-md-5">
                        <label>EDAD:</label>
                        <span id="paciente_edad"></span>
                    </div>
                </div>
                <hr style="border: none; border-top: 1px solid #ccc; margin: 10px 0;">

                <div class="row">
                    <div class="col-md-6">
                        <label>Titular</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Apellidos y Nombres:</label>
                        <span id="titular_nombre"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Grado:</label>
                        <span id="titular_grado"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Situación:</label>
                        <span id="titular_situacion"></span>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="col-md-6" id="divMedico">
        <div class="panel panel-default">
            <div class="panel-heading bg-lightgreen">
                <strong>Información del Responsable de atención</strong>
            </div>
            <div class="panel-body">
                <div class="row" style="">
                    <div class="col-md-12">
                        <label>T.DOC.</label>
                        <span>{{ /* $medico->tipo_documento_descripcion */'DNI' }}</span>
                    </div>
                </div>
                <div class="row" style=" display: block;margin-top: -10px; ">
                    <div class="col-md-6">
                        <label>N.DOC.</label>
                        <span>{{ $item->dniMedico }}</span>
                    </div>
                </div>
                <div class="row" style="">
                    <div class="col-md-12">
                        <label>Apeliidos y Nombres</label>
                        <span>{{ $medico->paterno }} {{ $medico->materno }} {{ $user->name }}</span>
                    </div>
                </div>
                @if ($medico)
                <div class="row" style="">
                    <div class="col-md-6">
                        <label>Colegio</label>
                        <span>{{ $medico->colegio_descripcion }}</span>
                    </div>
                </div>
                <div class="row" style="">
                    <div class="col-md-6">
                        <label>N° Col</label>
                        <span>{{ $medico->colegiatura }}</span>
                    </div>
                </div>
                <div class="row" style="">
                    <div class="col-md-6">
                        <label>N° RNE</label>
                        <span>{{ $medico->rne }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>



    </div>
</div>

<!--div class="form-group">
    <label for="observacion_ind">Observación</label>
    <textarea name="observacion_ind" id="observacion_ind" rows="3" class="form-control"></textarea>
</div-->

<style>
    .modal {
        padding-right: 0 !important;
        padding-left: 0 !important;
    }
</style>


<div id="formIndividual" style="display: block;">
    @include('frontend.atencion_estrategia_sanitaria.components._diagnostico')
</div>



@include('frontend.atencion_estrategia_sanitaria.components._estrategia_sanitaria')
@include('frontend.atencion_estrategia_sanitaria.modal_registrar_asegurado')


<script>
    $(function() {
        $('#modalRegistrarAseguradoEstrategia').on('hidden.bs.modal', function() {
            if ($('.modal.in').length > 0) {
                // Si todavía hay modales abiertos, vuelve a agregar 'modal-open' al body
                $('body').addClass('modal-open');
            }
        });

        function mostrarSweetAlert(mensaje, accion, keyword) {
            Swal.fire({
                title: `¿Deseas crear ${keyword == 'historia' ? 'una historia?' : 'un usuario nuevo?'}`,
                text: mensaje,
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                reverseButtons: true,
                customClass: {
                    title: 'swal-custom-title'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(
                        `El usuario quiere crear ${keyword == 'historia' ? 'una historia.' : 'un usuario nuevo.'}.`
                    );
                    if (keyword == 'historia') {
                        registrarHistoria(accion);
                    } else if (keyword == 'usuario') {
                        registrarUsuario(accion);
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {}
            });
        }

        function registrarHistoria(accion) {
            console.log("Se registrara un numero de historia");
            $("#campos_historia").removeClass("hidden");
            $("#campos_asegurado").addClass("hidden");
            $("#modalRegistrarAseguradoEstrategia").modal("show");
            $("#modalLabelES").html("Registro de Numero de Historia");
            $("#tipo_registro").val("H");
            $("#nro_historia").val("");
            $("#nombre_asegurado_es").val("");
            $("#paterno_es").val("");
            $("#materno_es").val("");
            $("#dni_es").val("");
        }

        function registrarUsuario(accion) {
            console.log("Se registrara un usuario");
            $("#campos_asegurado").removeClass("hidden");
            $("#campos_historia").addClass("hidden");
            $("#modalRegistrarAseguradoEstrategia").modal("show");
            $("#modalLabelES").html("Registro de Numero de Asegurado");
            $("#tipo_registro").val("U");
            $("#nro_historia").val("");
            $("#nombre_asegurado_es").val("");
            $("#paterno_es").val("");
            $("#materno_es").val("");
            $("#dni_es").val("");
            $("#dni_es").val($("#nroPaciente").val());
        }

        function formatearFechaDate(fecha) {
            const dia = String(fecha.getDate()).padStart(2, '0');
            const mes = String(fecha.getMonth() + 1).padStart(2, '0');
            const año = fecha.getFullYear();

            return `${dia}/${mes}/${año}`;
        }

        function calcularEdad(fechaNacimientoStr) {
            const [year, month, day] = fechaNacimientoStr.split('-').map(Number);
            const hoy = new Date();
            let edad = hoy.getFullYear() - year;

            const mesActual = hoy.getMonth() + 1;
            const diaActual = hoy.getDate();
            if (mesActual < month || (mesActual === month && diaActual < day)) {
                edad--;
            }

            return edad;
        }

        function cargarInfoAsegurado(res) {
            console.log(res)
            const data = res.data.asegurado;
            const nro_historia = res.data.nro_historia;
            $('#paciente_nombre').text((data.paterno || '') + ' ' + (data
                .materno || '') + ' ' + (data.nombre || ''));
            $('#paciente_hc').text(nro_historia || '');
            $('#paciente_parentesco').text(data.tipo_beneficiario_nombre || '');
            $('#paciente_sexo').text(data.sexo || '');
            $('#paciente_fec_nac').text(data.fecha_nac || '');
            $('#paciente_edad').text(data.edad || '');
            $('#paciente_titular').text(data.titular || '');
            if (
                !data.paterno_titular &&
                !data.materno_titular &&
                !data.nombre_titular
            ) {
                // Mostrar nombre del paciente
                $('#titular_nombre').text(
                    (data.paterno || '') + ' ' +
                    (data.materno || '') + ' ' +
                    (data.nombre || '')
                );
            } else {
                // Mostrar nombre del titular
                $('#titular_nombre').text(
                    (data.paterno_titular || '') + ' ' +
                    (data.materno_titular || '') + ' ' +
                    (data.nombre_titular || '')
                );
            }


            $('#titular_grado').text(data.grado || '');
            $('#titular_situacion').text(data.situacion_paciente || '');
            $('#idEstPaciente').val(data.id_asegurado || '');
        }

        $("#btnGuardarNroHistoriaAsegurado").on("click", function(e) {
            let nroHistoria = $("#nro_historia").val();
            let asegurado_doc = $("#nroPaciente").val();
            let asegurado_dni_registrar = $("#dni_es").val();
            let asegurado_materno = $("#materno_es").val();
            let asegurado_paterno = $("#paterno_es").val();
            let asegurado_nombre = $("#nombre_asegurado_es").val();
            let asegurado_sexo = $("#sexo_es").val();
            let asegurado_nacimiento = $("#nacimiento_es").val();
            let tipo_registro = $("#tipo_registro").val();

            if (tipo_registro == "H") {
                $.ajax({
                    url: "/es/registrar-historia", // <-- ajusta la URL de tu endpoint
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"), // si usas Laravel
                        nro_historia: nroHistoria,
                        asegurado_doc: asegurado_doc,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $("#modalRegistrarAseguradoEstrategia").modal("hide");

                            Swal.fire({
                                icon: "success",
                                title: "Éxito",
                                text: "La historia se registró correctamente"
                            });
                            const res = {
                                data: {
                                    asegurado: {
                                        ...response.asegurado
                                    },
                                    nro_historia: response.nro_historia
                                }
                            };
                            cargarInfoAsegurado(res);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message ||
                                    "No se pudo registrar la historia"
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Ocurrió un problema en el servidor"
                        });
                        console.error(xhr.responseText);
                    }
                });
            }

            if (tipo_registro == "U") {
                $('#paciente_nombre').text(
                    `${asegurado_paterno} ${asegurado_materno}, ${asegurado_nombre}`);
                $('#paciente_hc').wrapInner('<i style="font-size: 10px;">No disponible por condicion de CIVIL.</i>');
                $('#paciente_sexo').text(asegurado_sexo == "M" ? "MASCULINO" : "FEMENINO");
                $('#paciente_parentesco').text('CIVIL');
                $('#paciente_edad').text(calcularEdad(asegurado_nacimiento));
                $('#paciente_fec_nac').text(formatearFechaDate(new Date(asegurado_nacimiento)));
                $("#modalRegistrarAseguradoEstrategia").modal("hide");
                $('#titular_nombre').wrapInner('<i style="font-size: 10px;">No disponible por condicion de CIVIL.</i>');
                $('#titular_grado').wrapInner('<i style="font-size: 10px;">No disponible por condicion de CIVIL.</i>');
                $('#titular_situacion').wrapInner('<i style="font-size: 10px;">No disponible por condicion de CIVIL.</i>');
            }
        });

        $('input[name="tipo_estrategia"]').on('change', function() {
            if ($(this).val() == '1') {
                $('#formIndividual').show();
                $('#formMasivo').hide();
                $('#divPaciene').show();
                $("#divcant_personas").hide();
                $("#cant_personas").val(1);
                $('#divMedico').removeClass('col-md-12') // Eliminamos la clase normal
                    .addClass('col-md-6'); // Aplicamos ancho completo;
            } else {
                $("#cant_personas").val("");
                $("#divcant_personas").show();
                $('#divPaciene').hide();
                $('#divMedico').removeClass('col-md-6') // Eliminamos la clase normal
                    .addClass('col-md-12'); // Aplicamos ancho completo;
                $('#formIndividual').hide();
                $('#formMasivo').show();
            }
        });

        $('#btnBuscarPaciente').on('click', function() {
            var tipoDoc = $('#tipoDocPaciente').val();
            var nroDoc = $('#nroPaciente').val();
            if (!tipoDoc || !nroDoc) {
                alert('Ingrese Tipo y Número de Documento');
                return;
            }
            $.ajax({
                url: '/paciente/datos', // Ajusta la ruta según tu backend
                method: 'GET',
                data: {
                    tipo_doc: tipoDoc,
                    nro_doc: nroDoc
                },
                success: function(res) {
                    if (res.data.tiene_historia) {
                        const data = res.data.asegurado;
                        const nro_historia = res.data.nro_historia;
                        // Asume que el backend retorna un objeto con los campos requeridos
                        $('#paciente_nombre').text((data.paterno || '') + ' ' + (data
                            .materno || '') + ' ' + (data.nombre || ''));
                        $('#paciente_hc').text(nro_historia || '');
                        $('#paciente_parentesco').text(data.tipo_beneficiario_nombre || '');
                        $('#paciente_sexo').text(data.sexo || '');
                        $('#paciente_fec_nac').text(data.fecha_nac || '');
                        $('#paciente_edad').text(data.edad || '');
                        $('#paciente_titular').text(data.titular || '');
                        if (
                            !data.paterno_titular &&
                            !data.materno_titular &&
                            !data.nombre_titular
                        ) {
                            // Mostrar nombre del paciente
                            $('#titular_nombre').text(
                                (data.paterno || '') + ' ' +
                                (data.materno || '') + ' ' +
                                (data.nombre || '')
                            );
                        } else {
                            // Mostrar nombre del titular
                            $('#titular_nombre').text(
                                (data.paterno_titular || '') + ' ' +
                                (data.materno_titular || '') + ' ' +
                                (data.nombre_titular || '')
                            );
                        }


                        $('#titular_grado').text(data.grado || '');
                        $('#titular_situacion').text(data.situacion_paciente || '');
                        $('#idEstPaciente').val(data.id_asegurado || '');
                        return;
                    }
                    mostrarSweetAlert(res.message, res.modal.accion, res.keyword);
                    return;
                },
                error: function(xhr, status, error) {
                    $('#paciente_nombre').text('');
                    $('#paciente_hc').text('');
                    $('#paciente_parentesco').text('');
                    $('#paciente_fec_nac').text('');
                    $('#paciente_edad').text('');
                    $('#paciente_sexo').text('');
                    $('#paciente_titular').text('');
                    $('#titular_nombre').text('');
                    $('#titular_grado').text('');
                    $('#titular_situacion').text('');
                    $('#idEstPaciente').val('');
                    const res = xhr.responseJSON;
                    mostrarSweetAlert(res.message, res.modal.accion, res.keyword);
                }
            });
        });
    });


    $(document).ready(function() {
        // Configurar fecha actual
        $('#fecha_registro').val(new Date().toISOString().split('T')[0]);

        // Manejar envío del formulario
        $('#formAtencionEstrategia').on('submit', function(e) {
            e.preventDefault();

            // Validar campos requeridos
            if (!validarFormulario()) {
                return false;
            }

            // Recolectar datos del formulario
            let formData = {
                tipo_estrategia: $('input[name="tipo_estrategia"]:checked').val(),
                tipo_registro: $('#tipo_registro').val(),
                id_establecimiento: $('#id_establecimiento_programacion').val(),
                tipo_atencion: $('#tipo_atencion').val(),
                ugipress: $('#ugipress').val(),
                id_upss_ind: $('#id_upss_ind').val(),
                fecha_atencion_ind: $('#fecha_atencion_ind').val(),
                periodo: $('#periodo').val(),
                estado_estrategia: $('#estado_estrategia_form').val(),
                cant_personas: $('#cant_personas').val() || 1,
                id_medico: '{{ $medico->id }}',
                id_registro_edit: $("#id_registro_edit").val(),
                nro_registro: $("#nro_registro").val()
            };

            // Solo para atenciones individuales
            if (formData.tipo_estrategia == 1) {
                if ($("#tipo_registro").val() == "U") {
                    formData.tipo_registro = "U";
                    formData.nombre_asegurado_es = $("#nombre_asegurado_es").val();
                    formData.paterno_es = $("#paterno_es").val();
                    formData.materno_es = $("#materno_es").val();
                    formData.dni_es = $("#dni_es").val();
                    formData.sexo_es = $("#sexo_es").val();
                    formData.nacimiento_es = $("#nacimiento_es").val();
                } else {
                    formData.idEstPaciente = $('#idEstPaciente').val();
                }

                // Recolectar diagnósticos
                formData.diagnosticos = [];
                $('#tablaDiagnostico tbody tr').each(function() {
                    formData.diagnosticos.push({
                        id: $(this).find('input[name*="[id_diagnostico]"]').val(),
                        tipo: $(this).find('input[name*="[tipo]"]').val(),
                        observacion: $(this).find('input[name*="[observacion]"]').val()
                    });
                });
            }

            // Recolectar estrategias sanitarias
            formData.estrategias = [];
            $('#tablaEstrategias tbody tr').each(function() {
                formData.estrategias.push({
                    codigo: $(this).find('input[name*="[codigo]"]').val(),
                    observacion: $(this).find('input[name*="[observacion]"]').val()
                });
            });

            // Enviar datos al servidor
            $.ajax({
                url: '{{ route('atencion.estrategia.guardar') }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('Registro guardado correctamente. N° de registro: ' + response
                            .nro_registro);
                        // Redirigir o limpiar formulario según necesidad
                        window.location.href = '{{ route('atencion.estrategia.index') }}';
                    } else {
                        alert('Error al guardar: ' + response.error);
                    }
                },
                error: function(xhr) {
                    alert('Error en la solicitud: ' + xhr.responseText);
                }
            });
        });

        // Función para validar el formulario
        function validarFormulario() {
            let tipoEstrategia = $('input[name="tipo_estrategia"]:checked').val();
            let isValid = true;

            // Validar campos comunes
            if (!$('#periodo').val()) {
                alert('El campo Periodo es requerido');
                isValid = false;
            }

            if (!$('#id_upss_ind').val()) {
                alert('El campo UPSS es requerido');
                isValid = false;
            }

            if (!$('#fecha_atencion_ind').val()) {
                alert('El campo Fecha de Atención es requerido');
                isValid = false;
            }

            // Validar campos específicos para individual
            if (tipoEstrategia == 1) {
                if (!$('#idEstPaciente').val() && $("#tipo_registro").val() != "U") {
                    alert('Debe seleccionar un paciente válido');
                    isValid = false;
                }

                /* if ($('#tablaDiagnostico tbody tr').length == 0) {
                    alert('Debe agregar al menos un diagnóstico');
                    isValid = false;
                } */
            } else {
                // Validar campos para masivo
                if (!$('#cant_personas').val() || $('#cant_personas').val() < 1) {
                    alert('Debe especificar una cantidad válida de personas atendidas');
                    isValid = false;
                }
            }

            // Validar que haya al menos una estrategia sanitaria
            if ($('#tablaEstrategias tbody tr').length == 0) {
                alert('Debe agregar al menos una estrategia sanitaria');
                isValid = false;
            }

            return isValid;
        }

        // Formatear automáticamente el campo periodo (MM/AAAA)
        $('#periodo').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 6);
            }
            $(this).val(value);
        });
    });
</script>
