@extends('frontend.layouts.master')

@section('rendercss')
    <link href='js/fullcalendar-2.3.2/fullcalendar.css' rel='stylesheet' />
    <link href='js/fullcalendar-2.3.2/fullcalendar.print.css' rel='stylesheet' media='print' />

    {!! HTML::style('plugins/jQuery/jquery-ui.css') !!}

    <style>
        .fc-sun {
            background-color: #DCE2E8;
        }

        .title-dialog {
            font-size: 18px;
            font-family: Arial, Helvetica, sans-serif;
        }

        /*.fc-event-container{margin:20px!important}*/
        .fc-day-grid-event {
            /*width:150px;*/
            /*margin:10px;*/
            text-align: left;
            /*margin-top:20px;*/
            /*background:#5cb85c*/
            background: transparent;
            color: #000000
        }

        .fc-day-grid-event :hover {
            color: #000000
        }

        .fc-content {
            padding: 0px !important;
        }

        .fc-title {
            text-align: left;
            font-size: 10px
        }

        .suggest-element {
            margin-left: 5px;
            margin-top: 5px;
            width: 350px;
            cursor: pointer;
        }

        #suggestions {
            width: 350px;
            height: 150px;
            overflow: auto;
        }

        .loader {
            width: 100%;
            height: 100%;
            /*height: 1500px;*/
            overflow: hidden;
            top: 0px;
            left: 0px;
            z-index: 10000;
            text-align: center;
            position: absolute;
            background-color: #000;
            opacity: 0.6;
            filter: alpha(opacity=40);
            display: none;
        }

        .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 500px !important;
            font-size: 1.7em;
            border: 0px;
            margin-left: -17% !important;
            text-align: center;
            background: #3c8dbc;
            color: #FFFFFF;
        }

        .fc-unthemed .fc-today {
            background: #E1D097 !important;
        }

        #tblOrdenesImagenes tbody td {
            font-size: 1.2rem;
        }

        .btn-app {
            padding: 1px 5px;
            font-size: 10px;
            line-height: 1.5;
            border-radius: 5px;
            position: relative;
            text-align: center;
            color: #FFF;
            background-color: #c9302c;
        }

        .btn-app-g {
            padding: 1px 5px;
            font-size: 10px;
            line-height: 1.5;
            border-radius: 5px;
            position: relative;
            text-align: center;
            color: #FFF;
            background-color: #529e53;
        }

        .swal-icon-size {
            font-size: 24px !important;
            width: 50px !important;
            height: 50px !important;
        }


        .btn-cpms {
            background-color: #7CB342;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-cpms:hover {
            background-color: #689F38;
            color: white;
        }

        /* Estilos personalizados para SweetAlert */
        .swal2-popup {
            border-radius: 10px !important;
            padding: 0 !important;
        }

        .cpms-header {
            background-color: #7CB342;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0;
            border-radius: 10px 10px 0 0;
        }

        .cpms-content {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .cpms-item {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            align-items: flex-start;
        }

        .cpms-item:last-child {
            border-bottom: none;
        }

        .cpms-codigo {
            font-weight: bold;
            color: #333;
            min-width: 80px;
            margin-right: 20px;
            font-size: 14px;
        }

        .cpms-descripcion {
            text-align: left;
            color: #555;
            flex: 1;
            font-size: 14px;
            line-height: 1.4;
        }

        .swal-noafiliado .swal2-icon {
            transform: scale(0.5);
            margin: 0;
            font-size: 32px!important;
        }
        .swal-noafiliado{
            border-radius:12px;
            border: 3px solid orange;
        }

        .swal-title{
            font-size:23px !important;
            font-weight:700;
        }


        .swal-content{
            font-size:17px !important;
            color:#444;
        }

        .swal2-confirm{
            font-size:15px !important;
            padding:10px 28px !important;
        }

        .swal-afiliado_a_vencer{
            border-radius:12px;
            border: 3px solid skyblue;
        }

        .swal-afiliado_a_vencer .swal2-icon {
            transform: scale(0.5);
            margin: 0;
            font-size: 32px!important;
        }
        .swal-noafiliadosp .swal2-icon {
            transform: scale(0.5);
            margin: 0;
            font-size: 32px!important;
        }
        .swal-noafiliadosp .swal2-icon.swal2-warning {
            border-color: #F2421F;
            color: #F2421F;
        }
        .swal-noafiliadosp .swal2-icon.swal2-warning .swal2-icon-content {
            color: #F2421F;
        }
        .swal-noafiliadosp{
            border-radius:12px;
            border: 3px solid #F2421F;
        }
        .swal-afiliacion {
            width: 350px !important;
            height: 300px !important;
            padding: 1rem !important;
            display: flex !important;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }
        
        .swal-afiliacion .swal2-header {
            padding: 0 !important;
        }

        .swal-afiliacion .swal2-icon {
            font-size: 15px !important;
            margin: 0 auto 6px auto !important;
        }

        .swal-afiliacion .swal2-title {
            font-size: 20px !important;
            margin: 0 0 7px 0 !important;
            padding: 7px !important;
        }

        .swal-afiliacion .swal2-content,
        .swal-afiliacion .swal2-html-container {
            font-size: 17px !important;
            line-height: 1.3 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .swal-afiliacion .swal2-actions {
            margin: 10px auto 0 auto !important;
        }

        .swal-afiliacion .swal2-confirm {
            padding: 5px 25px !important;
            font-size: 15px !important;
            border-radius: 5px !important;
            background-color: #1b5e20 !important; 
            color: #fff !important;
        }

        .swal-afiliacion .swal2-confirm:hover {
            background-color: #144a18 !important;
        }

        .swal-afiliacion .swal2-confirm:focus {
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.35) !important;
        }

    </style>
@endsection

@section('renderjs')
    <script src='js/fullcalendar-2.3.2/lib/moment.min.js'></script>
    <script src='js/fullcalendar-2.3.2/fullcalendar.min.js'></script>
    <script src='js/fullcalendar-2.3.2/lang/es.js'></script>
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://unpkg.com/tippy.js@5"></script>
    {!! HTML::script('plugins/jQuery/jquery-ui.js') !!}

    <script>
        $(document).ready(function() {

            $cmbcons_referencia = $("#frmCita").find("#consultorio_referencia");
            $cmbcons_referencia.select2();
            $cmbest_referencia = $("#frmCita").find("#establecimiento_referencia");
            $cmbest_referencia.select2();
            $cmbserv_referencia = $("#frmCita").find("#servicio_referencia");
            $cmbserv_referencia.select2();



            $('input[name="parent"]').on("change", function(e) {
                getServicio();
            });

            $('#id_establecimiento').on("change", function(e) {
                fullCalendar();
            });

            $('#id_servicio').on("change", function(e) {
                //let isValidSexo = validarSexo();
                //if (!isValidSexo) {
                //    return;
                //}
                let isValidPediatria = validarPediatria();
                if (!isValidPediatria) {
                    return;
                }
                validarPacienteConIntervencionQuirurgica();
            });

            $('#btnCita').on("click", function(e) {
                guardarCitaAutomatico();
            });

        });

        function mostrar(referido) {
            if (referido == "SI") {
                $("#establereferencia").show();
                $("#consultorio_x_servicio").hide();
            } else {
                $("#establereferencia").hide();
                $("#consultorio_x_servicio").show();
            }
        }

        $("#dni_beneficiario").on("keydown", function(e) {
            var code = e.keyCode || e.which;
            if (code == '9' || code == '13') {
                obtenerBeneficiario();
            }
        });

        function validarPediatria() {
            let isValid = true;
            const id_servicio = $("#id_servicio").val();
            const edadafiliado = $("#edadafiliado").val();
            const pediatriasIds = [35, 41, 49, 117, 136, 258, 262]
            if (id_servicio && pediatriasIds.includes(parseInt(id_servicio)) && parseInt(edadafiliado) > 14) {
                isValid = false;
                swal({
                    buttons: false,
                    title: "Mensaje de Advertencia",
                    text: "El servicio seleccionado es de PEDIATRÍA, pero la edad del paciente es mayor a 14 años.",
                    icon: "warning",
                    timer: 2500,
                });
                $('#id_servicio').val('').trigger('change');
            }
            return isValid;
        }

        function validarSexo() {
            let isValid = true;
            const id_servicio = $("#id_servicio").val();
            const nomsexo = $("#nomsexo").val();
            const gineObstrIds = [7, 34, 77, 227, 259, 65, 131, 162, 165, 243, 244, 245]
            if (id_servicio && gineObstrIds.includes(parseInt(id_servicio)) && nomsexo.toUpperCase() === 'M') {
                isValid = false;
                swal({
                    buttons: false,
                    title: "Mensaje de Advertencia",
                    text: "El servicio seleccionado es de GINECOLOGÍA OBSTETRICIA, pero el paciente es de sexo masculino.",
                    icon: "warning",
                    timer: 2500,
                });
                $('#id_servicio').val('').trigger('change');
            }
            return isValid;
        }


        function guardarCitaAutomatico() {

            var msg = "";
            var id_establecimiento = $('#id_establecimiento').val();
            var id_servicio = $('#id_servicio').val();
            var fecha_atencion = $('#fecha_atencion').val();
            var dni_beneficiario = $("#dni_beneficiario").val();
            let id_tipo_cita = $("#id_tipo_cita").val();

            if (dni_beneficiario == "") msg += "Debe ingresar el Numero de Documento <br>";
            if (id_establecimiento == "") msg += "Debe seleccionar un Establecimiento<br>";
            if (id_servicio == "") msg += "Debe ingresar un Servicio<br>";
            if (id_tipo_cita == "") msg += "Debe ingresar un tipo de cita<br>";
            if ($('input[name=horario]').is(':checked') == false) msg += "Debe seleccionar un Turno<br>";

            if ($('input[name=horario]').is(':checked') == true) {
                var horario = $('input[name=horario]:checked').val();
                var data = horario.split("#");
                var fecha_cita = data[0];
                var id_medico = data[1];
            }

            if (msg != "") {
                bootbox.alert(msg);
                return false;
            } else {
                fn_save_cita_automatico(id_medico, fecha_cita);
            }
        }

        function fn_save_cita_automatico(id_medico, fecha_cita) {
            var correo = $('#correo').val();
            var msgLoader = "";
            if (correo == "") msgLoader = "Procesando, espere un momento por favor";
            else msgLoader = "Enviado correo, espere un momento por favor";

            $('#btnCita').prop("disabled", true);

            var heightBrowser = $(window).width() / 2;

            $('.loader').css("opacity", "0.8").css("height", heightBrowser).html(
                "<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>" +
                msgLoader + "</div></div>");
            $('.loader').show();

            var fecha_atencion_original = $('#fecha_atencion').val();
            var id_user = $('#id_user').val();
            $.ajax({
                url: "registrar_cita",
                type: "POST",
                data: $("#frmCita").serialize() + "&id_medico=" + id_medico + "&fecha_cita=" + fecha_cita,
                success: function(result) {
                    $('#btnCita').prop("disabled", false);
                    $('.loader').hide();
                    console.log(result);
                    location.href = "ver_cita/" + id_user + "/" + result;
                }
            });
        }

        function modalProximaCita(tipodoc_beneficiario, dni_beneficiario) {
            $("#showProximasCitas .modal-dialog").css("width", "45%");

            $.ajax({
                url: "modal_proxima_cita/" + tipodoc_beneficiario + "/" + dni_beneficiario,
                type: "GET",
                success: function(result) {
                    $('#diveditpreg').html(result);
                    $('#showProximasCitas').modal('show');
                }
            });

        }

        function getarea_servicio() {
            var ubicacion = $('#establecimiento_referencia').val();

            if (ubicacion != '') {

                var servicios_consultorios = '/busca_ubicacion/' + ubicacion;
                $.ajax({
                    url: servicios_consultorios,
                    success: function(result) {
                        $('#servicio_referencia').empty();
                        $('#servicio_referencia').append("<option value=''>--SELECCIONAR--</option>");
                        console.log(result);
                        var new_id = 0;
                        $(result).each(function(id, oo) {

                            $('#servicio_referencia').append("<option value='" + oo.id + "'>" + oo.nombre + "</option>");
                        });

                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                    }
                });
            } else {
                $('#servicio_referencia').empty();
                $('#servicio_referencia').append("<option value=''>--SELECCIONAR--</option>");
                $('#servicio_referencia').val("").change();
            }
        }

        function modalValidacionGeriatrico(beneficiario) {
            if (beneficiario.edadafiliado < 60) {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: 'El beneficiario es menor de 60 años, no se puede registrar una cita.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                return false;
            }
            return true;
        }

        function formatearFechaDMY(fecha) {
          if (!fecha) {
            return '';
          }
          // FORMATO dd/mm/yyyy
          if (/^\d{2}\/\d{2}\/\d{4}$/.test(fecha)) {
            return fecha;
          }
          // FORMATO yyyy-mm-dd
          if (/^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
            const [y, m, d] = fecha.split('-');
            return `${d}/${m}/${y}`;
          }
          console.error('Formato de fecha no reconocido:', fecha);
          return '';
        }

        function obtenerBeneficiario() {

            var id_tiporeceta = 1;
            var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
            var dni_beneficiario = $("#dni_beneficiario").val();
            let usarApi = "{{ $flag_api_sp ? 'api' : 'ws' }}";
            var origen = 'citas'
            console.log(usarApi);
            var msg = "";

            if (dni_beneficiario === '') {
                limpiarContenedoresApoyoDiagRegCitas(1);
                msg += 'Digite el número de documento';
            }

            if (msg != "") {
                $('#nrodocafiliado').val("");
                $('#tipo_nrodocafiliado').val("");
                $('#beneficiario').val("");
                $('#tipo_beneficiario').val("");
                $('#nombre_beneficiario').val("");
                $('#paterno_beneficiario').val("");
                $('#materno_beneficiario').val("");
                $('#tipodoc_beneficiario').val("");
                $('#cip_beneficiario').val("");
                $('#grado').val("");
                $('#fecnacafiliado').val("");
                $('#nomsexo').val("");
                $('#nro_historia').val("");
                $('#domicilio').val("");
                $('#telefono').val("");
                $('#telefono2').val("");
                $('#correo').val("");
                $('#tipodoc_titular').val("");
                $('#nrodoctitular').val("");
                $('#nombre_titular').val("");
                $('#paterno_titular').val("");
                $('#materno_titular').val("");
                $('#id_establecimiento').html('');
                $('#id_servicio').html('');
                $('#situacion_titular').val("");
                $('#calendar').fullCalendar('destroy');
                bootbox.alert(msg);
                return false;
            }

            limpiarContenedoresApoyoDiagRegCitas(1);

            $.ajax({
                url: '/obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario + '/' + usarApi + '/' + origen,
                dataType: "json",
                beforeSend: function() {
                    Swal.fire({
                        html: '<p style="margin: 20px 0px 0px 0px"><b>Buscando datos del paciente</b></p>',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(result) {
                    console.log("[result] => ", result);

                    $('#nrodocafiliado').val("");
                    $('#tipo_nrodocafiliado').val("");
                    $('#beneficiario').val("");
                    $('#tipo_beneficiario').val("");
                    $('#nombre_beneficiario').val("");
                    $('#paterno_beneficiario').val("");
                    $('#materno_beneficiario').val("");
                    $('#tipodoc_beneficiario').val("");
                    $('#cip_beneficiario').val("");
                    $('#grado').val("");
                    $('#situacion_titular').val("");

                    $('#fecnacafiliado').val("");
                    $('#nomsexo').val("");
                    $('#nro_historia').val("");
                    $('#domicilio').val("");
                    $('#telefono').val("");
                    $('#telefono2').val("");
                    $('#correo').val("");
                    $('#tipodoc_titular').val("");
                    $('#nrodoctitular').val("");
                    $('#nombre_titular').val("");
                    $('#paterno_titular').val("");
                    $('#materno_titular').val("");

                    if (result.sw == false) {
                        if ($.LoadingOverlay) {
                            $.LoadingOverlay("hide");
                        }

                        var msgAlerta = result.msg || 'No se puede continuar con la atención.';
                        var esNoAfiliado = msgAlerta.indexOf('No se encuentra afiliado') !== -1;

                        Swal.fire({
                            icon: 'warning',
                            title: 'ALERTA',
                            html: `
                                <div style="font-size:16px; line-height:1.6; text-align:center;">
                                    <strong>${msgAlerta}</strong>
                                    ${esNoAfiliado ? `
                                    <br>
                                    Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación
                                    antes de continuar con la atención.
                                    ` : ''}
                                </div>
                            `,
                            confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                            confirmButtonColor: '#3085d6',
                            width: '38em',
                            customClass: {
                                popup: 'swal-noafiliado',
                                title: 'swal-title',
                                htmlContainer: 'swal-content'
                            }
                        }).then(() => {

                            $('#nrodocafiliado').val("");
                            $('#tipo_nrodocafiliado').val("");
                            $('#beneficiario').val("");
                            $('#tipo_beneficiario').val("");
                            $('#nombre_beneficiario').val("");
                            $('#paterno_beneficiario').val("");
                            $('#materno_beneficiario').val("");
                            $('#tipodoc_beneficiario').val("");
                            $('#cip_beneficiario').val("");
                            $('#grado').val("");
                            $('#situacion_titular').val("");

                            $('#fecnacafiliado').val("");
                            $('#nomsexo').val("");
                            $('#nro_historia').val("");
                            $('#domicilio').val("");
                            $('#telefono').val("");
                            $('#telefono2').val("");
                            $('#correo').val("");
                            $('#tipodoc_titular').val("");
                            $('#nrodoctitular').val("");
                            $('#nombre_titular').val("");
                            $('#paterno_titular').val("");
                            $('#materno_titular').val("");

                        });

                        return;
                    }

                    if (result.beneficiario && result.beneficiario.success === false) {
                        if ($.LoadingOverlay) {
                            $.LoadingOverlay("hide");
                        }
                        if (Swal.isLoading()) {
                            Swal.close();
                        }

                        var status = result.beneficiario.status;

                        if (status === 300) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'ALERTA',
                                html: `
                                    El paciente <strong>NO CUENTA CON COBERTURA DE SEGURO VIGENTE.</strong>
                                    <br>
                                    Debe acercarse a <strong>SALUDPOL</strong> para regularizar su afiliación
                                    antes de continuar con la atención.
                                `,
                                confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                                confirmButtonColor: '#3085d6',
                                width: '38em',
                                customClass: {
                                    popup: 'swal-noafiliado',
                                    title: 'swal-title',
                                    htmlContainer: 'swal-content'
                                }
                            });

                        } else if (status === 404) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'ALERTA',
                                html: `
                                    <div style="font-size:16px; line-height:1.6; text-align:center;">
                                        <strong>NO SE ENCUENTRA REGISTRO</strong>
                                        del paciente en <strong>SALUDPOL</strong>.<br>
                                        Debe acercarse a las oficinas de SALUDPOL para regularizar
                                        su registro.
                                    </div>
                                `,
                                confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                                confirmButtonColor: '#3085d6',
                                width: '38em',
                                customClass: {
                                    popup: 'swal-noafiliadosp',
                                    title: 'swal-title',
                                    htmlContainer: 'swal-content'
                                }
                            });

                        } else if (status === 401) {
                            Swal.fire({
                                icon: 'error',
                                title: 'CREDENCIALES INVÁLIDAS',
                                html: `
                                    <div style="font-size:16px; line-height:1.6; text-align:center;">
                                        No se pudo autenticar con el servicio de <strong>SALUDPOL</strong>.
                                        <br>
                                        Comuníquese con el área de soporte técnico.
                                    </div>
                                `,
                                confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                                confirmButtonColor: '#3c763d',
                                width: '38em',
                                customClass: { popup: 'swal-afiliacion' }
                            });

                        } else if (status === 400) {
                            Swal.fire({
                                icon: 'error',
                                title: 'DATOS INVÁLIDOS',
                                text: result.beneficiario.message,
                                confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                                confirmButtonColor: '#3c763d',
                                customClass: { popup: 'swal-afiliacion' }
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'ERROR AL CONSULTAR SALUDPOL',
                                html: `
                                    <div style="font-size:16px; line-height:1.6; text-align:center;">
                                        ${result.beneficiario.message}
                                        <br>
                                        Si el problema persiste, comuníquese con soporte técnico.
                                    </div>
                                `,
                                confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                                confirmButtonColor: '#3c763d',
                                width: '38em',
                                customClass: { popup: 'swal-afiliacion' }
                            });
                        }

                        return;
                    }

                    if (parseInt($("#id_establecimiento_current").val()) == 75) {
                        const resModalGeriatrico = modalValidacionGeriatrico(result.beneficiario);
                        if (!resModalGeriatrico) {
                            return;
                        }
                    }

                    var beneficiario = result.beneficiario.apepatafiliado + " " + result.beneficiario
                        .apematafiliado + " " +
                        result.beneficiario.apecasafiliado + ", " + result.beneficiario.nomafiliado;
                    $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
                    $('#tipo_nrodocafiliado').val(result.beneficiario.nomtipdocafiliado + " - " + result
                        .beneficiario.nrodocafiliado);
                    $('#beneficiario').val(beneficiario);
                    $('#tipo_beneficiario').val(result.beneficiario.parentesco);
                    $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
                    $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
                    $('#materno_beneficiario').val(result.beneficiario.apematafiliado + " " + result
                        .beneficiario.apecasafiliado);
                    $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
                    $('#cip_beneficiario').val(result.beneficiario.cip);
                    $('#grado').val(result.beneficiario.grado);
                    $('#fecnacafiliado').val(result.beneficiario.fecnacafiliado);
                    $('#nomsexo').val(result.beneficiario.nomsexo);
                    $('#edadafiliado').val(result.beneficiario.edadafiliado);

                    if (result.numero_historia != null) {
                        if (result.numero_historia != '')
                            $('#nro_historia').val(result.numero_historia);
                        else
                            $('#nro_historia').val(result.beneficiario.nrodocafiliado);
                    } else
                        $('#nro_historia').val(result.beneficiario.nrodocafiliado);

                    $('#domicilio').val(result.domicilio);
                    $('#telefono').val(result.telefono);
                    $('#telefono2').val(result.telefono2);
                    $('#correo').val(result.correo);
                    $('#cip_beneficiario').val(result.beneficiario.cip);

                    $('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
                    $('#nrodoctitular').val(result.beneficiario.nrodoctitular);
                    $('#nombre_titular').val(result.beneficiario.nomtitular);
                    $('#paterno_titular').val(result.beneficiario.apepattitular);
                    $('#materno_titular').val(result.beneficiario.apemattitular + " " + result.beneficiario
                        .apecastitular);

                    var parentesco = result.beneficiario.parentesco;
                    var ubigeo = result.beneficiario.ubigeo;
                    var establecimiento = result.establecimiento;
                    var cita = result.cita;
                    var id_establecimiento = 0;

                    if (establecimiento[0] != undefined) {
                        id_establecimiento = establecimiento[0].id_establecimiento;
                        nombre_establecimiento = establecimiento[0].establecimiento;
                        $('#nombre_establecimiento').html(nombre_establecimiento);

                    }

                    var id_servicio = '';
                    if (cita[0] != undefined) {
                        if (cita[0].id_estado_cita == 1) { // ESTADO PENDIENTE AUN NO HA SIDO ATENDIDO LA CITA
                            //bootbox.alert("El beneficiario tiene una cita programada");
                            //return false;
                            //var id_servicio = 8; ---- se comenta para evitar confusión
                            //$("#parent1").prop("checked", true);
                            //var id_servicio = cita[0].id_servicio;
                            //$("#parent2").prop("checked", true);
                        }
                        if (cita[0].id_estado_cita == 3) { //ESTADO DERIVADO
                            var id_servicio = cita[0].id_sub_consultorio;
                            //$('#parent').val("2");
                            //$('#nombre_parent').html("DERIVADO");
                            //var parent = $('input[name="parent"]:checked').val();
                            $("#parent2").prop("checked", true);
                        }
                    } else {
                        //var id_servicio = 8; ---- se comenta para evitar confusión
                        //$('#parent').val("1");
                        //$('#nombre_parent').html("GENERAL");
                        $("#parent1").prop("checked", true);
                    }

                    id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
                    obtenerEstablecimiento(parentesco, ubigeo, id_establecimiento, id_servicio);

                    if (result.cantidad_cita != null && result.cantidad_cita > 0) modalProximaCita(
                        tipodoc_beneficiario, dni_beneficiario);

                    modalHistorial();
                    modalHistorialImagenes();
                    modalHistorialLaboratorio();
                    $("#divTurno").html("");

                    $('#calendar').fullCalendar('destroy');
                    fullCalendar();
                    verificarPendienteAlta(dni_beneficiario);
                    if (result && result.beneficiario && result.beneficiario.caducidad) {
                        alertVencimientoSeguro(result.beneficiario.caducidad);
                    }

                    //verificarProgramacionDeProcedimientosPendiente(dni_beneficiario);
                    cargarOrdenesProcedimientosPendientes(dni_beneficiario);
                },
                complete: function() {
                    // Swal.close();
                    if (Swal.isLoading()) {
                        Swal.close();
                    }
                },

            });

        }

        function anularCita(id_cita) {

            bootbox.confirm({
                size: "small",
                message: "¿Deseas anular la cita?",
                callback: function(result) {
                    if (result == true) {
                        fn_anularCita(id_cita);
                    }
                }
            });

        }

        function fn_anularCita(id_cita) {

            $.ajax({
                url: '/anular_cita/' + id_cita,
                dataType: "json",
                success: function(result) {

                    if (result.sw == true) {
                        obtenerBeneficiario();
                        $('.nav-tabs a[href="#tab1default"]').tab('show');
                    } else if (result.msg) {
                        Swal.fire({
                            icon: "error",
                            title: result.msg,
                            showConfirmButton: true,
                            customClass: { popup: 'swal-afiliacion' }
                        });
                    }

                }

            });

        }

        // para historial de imágenes
        function anularCitaradiologia(id_cita) {

            bootbox.confirm({
                size: "small",
                message: "¿Deseas anular la cita?",
                callback: function(result) {
                    if (result == true) {
                        fn_anularCita_rad(id_cita);
                    }
                }
            });

        }

        function fn_anularCita_rad(id_cita) {

            $.ajax({
                url: '/anular_cita_radiologia/' + id_cita,
                dataType: "json",
                success: function(result) {

                    if (result.sw == true) {
                        obtenerBeneficiario();
                        $('.nav-tabs a[href="#tab1default"]').tab('show');
                    }

                }

            });

        }


        function obtenerEstablecimiento(parentesco, ubigeo, id_establecimiento, id_servicio) {
            var newOption = "";
            newOption = "<option value=''>--Seleccionar--</option>";
            $('#id_establecimiento').html(newOption);
            if (ubigeo == "") ubigeo = 0;
            $.ajax({
                url: '/obtener_establecimiento_cita/' + parentesco + "/" + ubigeo,
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar--</option>";
                    $(result).each(function(ii, oo) {
                        var sel = "";
                        if (id_establecimiento == oo.id) sel = "selected='selected'";
                        newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre +
                            "</option>";
                    });
                    $('#id_establecimiento').html(newOption);
                    $("#id_establecimiento").select2({
                        max_selected_options: 4
                    });
                    getServicio(id_servicio);
                }
            });
        }

        function limpiarBeneficiario() {

            var dni_beneficiario = $('#dni_beneficiario').val();
            var nrodocafiliado = $('#nrodocafiliado').val();
            if (dni_beneficiario != nrodocafiliado) {
                $('#beneficiario').val("");
                $('#tipo_beneficiario').val("");
                $('#nombre_beneficiario').val("");
                $('#paterno_beneficiario').val("");
                $('#materno_beneficiario').val("");
                $('#grado').val("");
                $('#nro_historia').val("");
                $('#tipodoc_beneficiario').val("");
                $('#cip_beneficiario').val("");
                $('#id_admision').val("");
                $('#grado').val("");
                $('#situacion_titular').val("");
            }

        }

        function getServicio(id_servicio) {
            var newOption = "";
            newOption = "<option value=''>--Seleccionar--</option>";
            $('#id_servicio').html(newOption);
            var parent = 2; //$('input[name="parent"]:checked').val();
            var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
            var dni_beneficiario = $("#dni_beneficiario").val();
            var id_establecimiento = $('#id_establecimiento').val();

            if (parent == 1) url = '/obtener_consultorio_cita_general/' + id_establecimiento + '/' + tipodoc_beneficiario +
                '/' +
                dni_beneficiario;
            if (parent == 2) url = '/obtener_consultorio_cita/' + id_establecimiento + '/' + tipodoc_beneficiario + '/' +
                dni_beneficiario;

            $.ajax({
                url: url,
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar--</option>";
                    $(result).each(function(ii, oo) {
                        var sel = "";
                        if (id_servicio == oo.id) sel = "selected='selected'";
                        newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre +
                            "</option>";
                    });
                    $('#id_servicio').html(newOption);
                    $("#id_servicio").select2({
                        max_selected_options: 4
                    });

                    console.log('Listando los servicios...');
                    const id_servicio2 = $('#id_servicio').val();
                    console.log('servicio : ' + id_servicio2);

                    if (id_servicio2 !== '113' && id_servicio2 !== '114' && id_servicio2 !== '') {
                        console.log('Entro en codicion 113 y 114...');
                        mostrarOrdenesLaboratorioCitaOrigen();
                    }
                    fullCalendar();
                }
            });
        }

        function getcargaservicio() {
            var newOption = "";
            newOption = "<option value=''>--Seleccionar--</option>";
            $('#id_servicio').html(newOption);
            var parent = $('input[name="parent"]:checked').val();
            var id_establecimiento = $('#id_establecimiento').val();

            if (parent == 1) url = '/obtener_consultorio_cita_general/' + id_establecimiento + '/' + tipodoc_beneficiario +
                '/' +
                dni_beneficiario;
            if (parent == 2) url = '/obtener_consultorio_cita/' + id_establecimiento + '/' + tipodoc_beneficiario + '/' +
                dni_beneficiario;

            $.ajax({
                url: url,
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar--</option>";
                    $(result).each(function(ii, oo) {
                        var sel = "";
                        if (id_servicio == oo.id) sel = "selected='selected'";
                        newOption += "<option value='" + oo.id + "' " + sel + " >" + oo.nombre +
                            "</option>";
                    });
                    $('#id_servicio').html(newOption);
                    $("#id_servicio").select2({
                        max_selected_options: 4
                    });
                    fullCalendar();

                }
            });

        }

        async function fullCalendar() {
            var fecha = new Date(2022, 4, 10);
            var feriados = await getFeriados(moment().format("YYYY"));

            //var SITEURL = "{{ url('/') }}";
            var iIdMovimientoCompra = $('#hdIdMovimientoCompra').val();
            var iIdTipoCompra = $('#hdIdTipoCompra').val();

            var id_servicio = $('#id_servicio').val();
            var id_ipress = $('#id_establecimiento').val();
            $("#divHorario").html("");
            $('#calendar').fullCalendar({
                header: {
                    left: 'today',
                    center: 'title',
                    right: 'prev,next'
                },
                editable: false,
                annotations: [{
                    start: new Date(2000, 1, 1),
                    end: new Date(2022, 4, 13),
                    title: 'Blocked Day',
                    cls: 'open',
                    color: '#777777',
                    background: '#000'
                }],
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    if (start.format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')) {

                        bootbox.alert("No se puede registrar citas con fechas pasadas");
                        return;
                    } else {
                        dateObj = new Date(start);
                        dateIntNTZ = dateObj.getTime() + dateObj.getTimezoneOffset() * 60 * 1000;
                        dateObjNTZ = new Date(dateIntNTZ);
                        start = dateObjNTZ.toISOString().slice(0, 10);
                        start = $.trim(start);
                        var sValidaciones = "";
                        modalDelegar(start);
                    }
                },
                dayRender: function(date, cell) {
                    var enabled = true;
                    if (date < moment()) {
                        cell.addClass("app-fullcalendar-day-disabled");
                        enabled = false
                    }
                    feriados.forEach(element => {
                        if (date.isSame(element.feriado)) {
                            cell.addClass("app-fullcalendar-feriado");
                            enabled = false
                        }
                    });
                    if (enabled)
                        cell.addClass("app-fullcalendar-day-enabled");
                }
            });
        }

        function fn_RegistrarNuevoCronograma(start, iIdMovimientoCompra, iIdTipoCompra) {
            $('#showAtencionesMensual').modal();
        }

        async function modalDelegar(start) {
            console.log(start);
            var id_servicio = $('#id_servicio').val();
            if (id_servicio == '') {
                bootbox.alert('Seleccione un servicio para buscar horario disponible!');
                return;
            }

            var tieneMensaje = false;
            let validacion = JSON.parse(await validateProgramacionCitas(start));


            var id_ipress = $('#id_establecimiento').val();
            var dni_beneficiario = $('#dni_beneficiario').val();
            const id_servicio_estudio = $('#id_servicio_estudio').length ? $('#id_servicio_estudio').val() : 0;


            // if (id_servicio == '114') {
            //   if ($('#id_pp').val() == '') {
            //     tieneMensaje = true;
            //     bootbox.alert('Seleccione el examen para registrar la cita de imagenes!');
            //   }
            // }

            if (validacion['validacion'] == '1') {
                $('#es_asegurado').val(1);
            } else if (validacion['validacion'] == '90') {
                $('#es_asegurado').val(0);
            } else {
                tieneMensaje = true;
                bootbox.alert(validacion['validacion']);
            }

            if (!tieneMensaje) {
                let url_horario = "modal_cita_horario/" + id_ipress + "/" + id_servicio + "/" + start + "/" +
                    dni_beneficiario + "/" + id_servicio_estudio;
                if ($('#cita_pp').val() == '1') {
                    url_horario = "/modal_cita_horario_pp/" + id_ipress + "/" + id_servicio + "/" + start + "/" +
                        dni_beneficiario + "/" + $('#id_procedimiento_pp').val();
                }
                $.ajax({
                    url: url_horario,
                    type: "GET",
                    beforeSend: function() {
                        Swal.fire({
                            html: '<p style="margin: 20px 0px 0px 0px"><b>BUSCANDO HORARIOS DISPONIBLES</b></p>',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(result) {
                        $("#divHorario").html(result);
                    },
                    complete: function() {
                        Swal.close();
                    },
                });
            }
        }


        function modalTurnos() {
            var id_servicio = $('#id_servicio').val();
            var id_ipress = $('#id_establecimiento').val();
            var start = "<?php echo $fechaServidor; ?>";
            $("#divTurno").html("");
            $.ajax({
                url: "modal_turnos/" + id_ipress + "/" + id_servicio + "/" + start,
                type: "GET",
                success: function(result) {
                    $("#divTurno").html(result);
                }
            });

        }

        function modalHistorial() {
            var dni_beneficiario = $("#dni_beneficiario").val();
            $.ajax({
                url: "modal_historial_cita/" + dni_beneficiario,
                type: "GET",
                success: function(result) {
                    $("#divHistorial").html(result);
                }
            });

        }

        function modalHistorialImagenes() {
            var dni_beneficiario = $("#dni_beneficiario").val();
            $.ajax({
                url: "modal_historial_cita_imagenes/" + dni_beneficiario,
                type: "GET",
                success: function(result) {
                    $("#divHistorialImagenes").html(result);
                }
            });

        }

        function modalHistorialLaboratorio() {
            var dni_beneficiario = $("#dni_beneficiario").val();
            $.ajax({
                url: "modal_historial_cita_laboratorio/" + dni_beneficiario,
                type: "GET",
                success: function(result) {
                    $("#divHistoriallaboratorio").html(result);
                }
            });

        }

        async function getFeriados(anio) {
            let feriados;
            try {
                feriados = await $.ajax({
                    url: "listar_feriados/" + anio,
                    type: "GET"
                });
                return JSON.parse(feriados);
            } catch (error) {
                console.log(error);
            }
        }


        async function validateProgramacionCitas(fechaCita) {
            try {
                let validacion = await $.ajax({
                    url: "valida_programacion_cita",
                    data: {
                        _token: $("#_token").val(),
                        nro_documento: $("#nrodocafiliado").val(),
                        id_establecimiento: $("#id_establecimiento").val(),
                        id_sub_consultorio: $("#id_servicio").val(),
                        fecha: fechaCita,
                    },
                    type: "POST"
                });
                return validacion;
            } catch (error) {}
        }

        function maxlength_doc_usu() {
            $('#dni_beneficiario').val('');

            if ($("#tipodoc_beneficiario_bus").val() == "1") {
                // Condicionar maximo 8 caracteres
                $("#dni_beneficiario").attr('maxlength', '8');
            } else {
                // Condicionar maximo 12 caracteres
                $("#dni_beneficiario").attr('maxlength', '12');
            }

            document.getElementById("dni_beneficiario").focus();
        }

        function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }

        function validacionTipoYNumDocumento(event) {
            var tipoDocumento = $("#tipodoc_beneficiario_bus").val();
            var key = window.Event ? event.which : event.keyCode;

            // Permitir números solo si el tipo de documento es 1 o 2
            if ((tipoDocumento == '1') && (key < 48 || key > 57)) {
                event.preventDefault(); // Evitar que se escriban caracteres no permitidos
            }

            // Permitir números y letras si el tipo de documento es 3 o 4
            if ((tipoDocumento == '2' || tipoDocumento == '3' || tipoDocumento == '4') && !((key >= 48 && key <= 57) || (
                    key >= 65 && key <= 90) || (key >= 97 && key <= 122))) {
                event.preventDefault(); // Evitar que se escriban caracteres no permitidos
            }
        }

        function validarFormatoEmail(email) {
            // Expresión regular para validar el formato de un correo electrónico
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        const limpiarContenedoresApoyoDiagRegCitas = (tipo) => {
            // //=========================================================
            $('#id_cita_origen_radiologia').val('');
            $('#id_pp').val('');
            //=========================================================
            if (tipo == 1) {
                $('#id_servicio_estudio').html('');
                $('#contServicioEstudio').hide();
                $('#tblOrdenesImagenes tbody').html('');
                $('#listOrdenesImagenes').hide();
            }
            //listar citas con orden de laboratorio para amarrar su cita de la especialidad
            $('#id_cita_origen_laboratorio').val('');
            $('#tblOrdenesLaboratorioCitaOrigen tbody').html('');
            $('#listOrdenesLaboratorioCitaOrigen').hide();
            //=======================================================
            $('#id_cita_origen_cita_resultado_laboratorio').val('');
            $('#tblOrdenesLaboratorioCitaOrigenCitaResultado tbody').html('');
            $('#listOrdenesLaboratorioCitaOrigenCitaResultado').hide();
        }

        $(document).on('change', '#id_servicio', function() {
            const id_servicio = $('#id_servicio').val();
            console.log('change, #id_servicio');
            console.log(id_servicio);
            if (id_servicio != '') {
                console.log('El id_servicio es : ' + id_servicio);
                const num_id_servicio = parseInt(id_servicio, 10);
                switch (num_id_servicio) {
                    case 114: // Diagnostico por Imagenes
                        console.log('ENtro en imagenes...');
                        $.ajax({
                            url: "/lista_servicios_imagenes",
                            success: function(response) {
                                let newOption = "";
                                newOption = "<option value='0'>--Todos--</option>";
                                $(response).each(function(ii, oo) {
                                    newOption +=
                                        `<option value="${oo.id_servicio}">${oo.nombre}</option>`;
                                });
                                $('#id_servicio_estudio').html(newOption);
                                $("#id_servicio_estudio").select2({
                                    max_selected_options: 4
                                });
                                $('#contServicioEstudio').show();
                                $('#id_servicio_estudio').trigger('change');
                            }
                        });
                        break;
                    case 113: // Laboratorio
                        console.log('ENtro en laboratorio...');
                        limpiarContenedoresApoyoDiagRegCitas(1);
                        mostrarOrdenesLaboratorioCitaOrigenCitaResultado();
                        break;

                    default:
                        console.log('ENtro por default...');
                        limpiarContenedoresApoyoDiagRegCitas(1);
                        mostrarOrdenesLaboratorioCitaOrigen();
                        break;
                }
            } else {
                console.log('Esta vacio el id_servicio...');
                limpiarContenedoresApoyoDiagRegCitas(1);
            }
        });

        const mostrarOrdenesLaboratorioCitaOrigenCitaResultado = () => {
            const establecimiento = {!! json_encode($establecimiento) !!}
            const nivelEstablecimiento = establecimiento && establecimiento.nivel ? parseInt(establecimiento.nivel) : 3;
            const dni_beneficiario = $("#dni_beneficiario").val();
            $.ajax({
                url: `/listar_ordenes_laboratorio_cita_origen_cita_resultado/${dni_beneficiario}`,
                success: function(response) {
                    console.log(response);
                    if (response.length === 0) {
                        return;
                    } else {
                        let newOption = "";
                        let tipoAtencion = "";
                        let servicio = "";
                        let medico = "";
                        let radiologiaPortatil = "";
                        $(response).each(function(ii, oo) {
                            tipoAtencion = oo.nombre_tipo_atencion;
                            tipoAtencion += oo.flag_cq ? "<br><span style='color: red;'>( MOPRI )</span>" : "";
                            servicio = oo.especialidad_prestacional;
                            medico = oo.medico_prestacional;
                            
                            newOption += `<tr>
                                            <td>
                                                <div
                                                    data-toggle="tooltip" 
                                                    data-placement="right" 
                                                    data-html="true" 
                                                    title= "id-cita : ${oo.id_cita}">${oo.id}
                                                </div>
                                            </td>
                                            <td>${oo.created_at}</td>
                                            <td>${tipoAtencion}</td>
                                            <td>${servicio}</td>
                                            <td>${oo.consultorio}</td>
                                            <td>${medico}</td>`;
                            if (oo.flag_cita_resultado == 1) {
                                newOption +=
                                            `<td>
                                                <div class="btn-app-g">
                                                    <span>${oo.fecha_cita_resultados}</span>
                                                </div>
                                            </td>`;
                            } else {
                                newOption +=
                                            `<td>
                                                <div class="btn-app">
                                                    <span>PENDIENTE</span>
                                                </div>
                                            </td>`;
                            }
                            // newOption += `<td>${oo.codigo_rad} - ${oo.nombre_examen}</td>`;


                            if (oo.ruta_laboratorio == null || oo.ruta_laboratorio == "") {
                                newOption +=
                                            `<td>
                                                <div class="btn-app">
                                                    <span>PENDIENTE FIRMAR</span>
                                                </div>
                                            </td>`;
                            } else {
                                if (oo.flag_cita_resultado == 1 || nivelEstablecimiento !== 3) {
                                    newOption +=
                                            `<td>
                                                <input type="checkbox" name="uniqueCheckbox" value="${oo.id_cita}" class="single-checkbox-lab-cita-ini-result">
                                            </td>`;
                                } else {
                                    newOption +=
                                            `<td>
                                                <div class="btn-app">
                                                    <span>PENDIENTE</span>
                                                </div>
                                            </td>`;
                                }
                            }

                            newOption +=    `<td>
                                                <div class="btn btn-sm btn-primary" onclick="mostrarCPMSDesdeTabla(${JSON.stringify(oo.cpms).replace(/"/g, '&quot;')})" title="Ver CPMS">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </div>
                                            </td>
                                        </tr>`;
                        });
                        $('#tblOrdenesLaboratorioCitaOrigenCitaResultado tbody').html(newOption);
                        $('#listOrdenesLaboratorioCitaOrigenCitaResultado').show();

                        $('.single-checkbox-lab-cita-ini-result').on('change', function() {
                            if ($('#id_servicio').val() == 113) {

                            } else {
                                // Asegura que solo un checkbox esté seleccionado a la vez
                                $('.single-checkbox-lab-cita-ini-result').not(this).prop('checked',
                                    false);
                            }

                        });
                    }
                }
            });
        }

        /*$(document).on('change', '.single-checkbox-lab-cita-ini-result', function() {
            if ($(this).is(':checked')) {
                // Si se marca el checkbox, permitimos la acción
                $(this).prop('checked', true);
            } else {
                // Si se intenta desmarcar, revertimos la acción
                $(this).prop('checked', true);
            }
        });*/

        $(document).on('change', '.single-checkbox-lab-cita-ini-result', function() {
            // Obtener el valor del checkbox seleccionado
            const checkboxValue = $(this).val();

            if ($("#id_servicio").val() == 113) {
                // Crear un array para almacenar los valores de los checkboxes marcados
                let selectedValues = [];

                // Recorrer todos los checkboxes marcados con la clase especificada
                $('.single-checkbox-lab-cita-ini-result:checked').each(function() {
                    selectedValues.push($(this).val());
                });

                // Unir los valores con comas y establecerlos en el campo
                $('#id_cita_origen_cita_resultado_laboratorio').val(selectedValues.join(','));
            } else {
                $('#id_cita_origen_cita_resultado_laboratorio').val(checkboxValue);
            }
        });

        const mostrarOrdenesLaboratorioCitaOrigen = () => {
            const dni_beneficiario = $("#dni_beneficiario").val();
            const id_servicio = $('#id_servicio').val();
            console.log(`id_servicio : ${id_servicio}`);
            const num_id_servicio = parseInt(id_servicio, 10);
            const establecimiento = {!! json_encode($establecimiento) !!}
            const nivelEstablecimiento = establecimiento && establecimiento.nivel ? parseInt(establecimiento.nivel) : 3;
            $.ajax({
                url: `/listar_ordenes_laboratorio_cita_origen/${dni_beneficiario}/${num_id_servicio}`,
                success: function(response) {
                    console.log(response);
                    if (response.length === 0) {
                        return;
                    } else {
                        let newOption = "";
                        let tipoAtencion = "";
                        let servicio = "";
                        let medico = "";
                        let radiologiaPortatil = "";
                        $(response).each(function(ii, oo) {
                            newOption += `<tr>`;
                            newOption += `<td>
                            <div
                            data-toggle="tooltip" 
                            data-placement="right" 
                            data-html="true" 
                            title= "id-cita : ${oo.id_cita}">`;
                            newOption += `${oo.id}`;
                            newOption += `</div></td>`;
                            newOption += `<td>${oo.fecha_creacion}</td>`;
                            tipoAtencion = oo.nombre_tipo_atencion;
                            servicio = oo.especialidad_prestacional;
                            newOption += `<td>
                              ${tipoAtencion}
                            </td>`;
                            newOption += `<td>
                              ${servicio}
                            </td>`;
                            medico = oo.medico_prestacional;
                            newOption += `<td>${oo.consultorio}</td>`;
                            newOption += `<td>${medico}</td>`;
                            // newOption += `<td>${oo.codigo_rad} - ${oo.nombre_examen}</td>`;
                            newOption += `<td>`;
                            newOption += `<div class="btn btn-sm btn-primary" onclick="mostrarCPMSDesdeTabla(${JSON.stringify(oo.cpms).replace(/"/g, '&quot;')})" title="Ver CPMS"><i class="fa fa-search" aria-hidden="true"></i>
</div>`;
                            newOption += `</td>`;
                            if (oo.ruta_laboratorio == null || oo.ruta_laboratorio == "") {
                                newOption +=
                                    `<td><div class="btn-app"><span>PENDIENTE FIRMAR</span></div></td>`;
                            } else {
                                newOption +=
                                    `<td><input type="checkbox" name="uniqueCheckbox" value="${oo.id_cita}" class="single-checkbox-lab-cita-ini"></td>`; // Id Cita de la Atencion que solicita la intervencion
                            }
                            newOption += `</tr>`;
                        });
                        $('#tblOrdenesLaboratorioCitaOrigen tbody').html(newOption);
                        $('#listOrdenesLaboratorioCitaOrigen').show();
                        // Asegura que solo un checkbox esté seleccionado a la vez
                        $('.single-checkbox-lab-cita-ini').on('change', function() {
                            $('.single-checkbox-lab-cita-ini').not(this).prop('checked', false);
                        });
                    }
                }
            });
        }


        function mostrarCPMS(cpms = null) {
            // Si no se pasan datos, usar los datos de ejemplo
            var datos = cpms || cpmsData;

            // Construir el contenido HTML
            var contenidoHTML = '<div class="cpms-header">LISTA DE EXÁMENES SOLICITADOS</div>';
            contenidoHTML += '<div class="cpms-content">';

            $.each(datos, function(index, item) {
                contenidoHTML += '<div class="cpms-item">';
                contenidoHTML += '<div class="cpms-codigo">' + item.codigo_lab + '</div>';
                contenidoHTML += '<div class="cpms-descripcion">' + item.descripcion_corta + '</div>';
                contenidoHTML += '</div>';
            });

            contenidoHTML += '</div>';

            // Mostrar SweetAlert
            Swal.fire({
                html: contenidoHTML,
                showCloseButton: true,
                showConfirmButton: false,
                width: '600px',
                customClass: {
                    popup: 'cpms-popup'
                }
            });
        }

        // Función para usar con tu código existente
        function mostrarCPMSDesdeTabla(cpmsArray) {
            mostrarCPMS(cpmsArray);
        }

        $(document).on('change', '.single-checkbox-lab-cita-ini', function() {
            if ($(this).is(':checked')) {
                // Si se marca el checkbox, permitimos la acción
                $(this).prop('checked', true);
            } else {
                // Si se intenta desmarcar, revertimos la acción
                $(this).prop('checked', true);
            }
        });

        $(document).on('change', '.single-checkbox-lab-cita-ini', function() {
            // Obtener el valor del checkbox seleccionado
            const checkboxValue = $(this).val();
            $('#id_cita_origen_laboratorio').val(checkboxValue);

        });

        tippy('.tippy', {
            content: "I'm a Tippy tooltip!"
        });

        $(document).on('change', '#id_servicio_estudio', function() {
            const dni_beneficiario = $("#dni_beneficiario").val();
            const id_servicio_estudio = $('#id_servicio_estudio').val();

            $('#id_cita_origen_radiologia').val('');
            $('#id_pp').val('');

            limpiarContenedoresApoyoDiagRegCitas(0);
            $('#divHorario').html('');
            $.ajax({
                url: `/listar_ordenes_imagenes_pendientes/${dni_beneficiario}/${id_servicio_estudio}`,
                success: function(response) {

                    console.log(response);

                    let newOption = "";
                    let tipoAtencion = "";
                    let servicio = "";
                    let medico = "";
                    let radiologiaPortatil = "";
                    let comparativo_radio = "";
                    let tienePendientes = response.some(oo => !oo
                        .ruta_radiologia); // Verifica si hay pendientes
                    $(response).each(function(ii, oo) {
                        newOption += `<tr>`;
                        newOption += `<td>${oo.id}</td>`;
                        newOption += `<td>${oo.created_at}</td>`;
                        if (oo.id_tipo_atencion == 1) {
                            tipoAtencion = oo.nombre_tipo_atencion;
                            servicio = oo.especialidad_prestacional;
                        } else if (oo.id_tipo_atencion == 2) {
                            if (oo.id_evaluacion == '0') {
                                tipoAtencion = oo.nombre_tipo_atencion;
                                servicio = oo.especialidad_emergencia;
                            } else {
                                tipoAtencion = `${oo.nombre_tipo_atencion} - EVALUACIÓN`;
                                servicio = oo.especialidad_emergencia;
                            }
                        } else if (oo.id_tipo_atencion == 3) {
                            if (oo.id_evaluacion == '0') {
                                tipoAtencion = oo.nombre_tipo_atencion;
                                servicio = oo.especialidad_hospitalizacion;
                            } else {
                                tipoAtencion = `${oo.nombre_tipo_atencion} - EVOLUCIÓN`;
                                servicio = oo.especialidad_hospitalizacion;
                            }
                        }
                        newOption += `<td>
                              ${tipoAtencion}
                            </td>`;
                        newOption += `<td>
                              ${servicio}
                            </td>`;
                        if (oo.id_tipo_atencion == 1) {
                            medico = oo.medico_prestacional;
                        } else if (oo.id_tipo_atencion == 2) {
                            if (oo.id_evaluacion == '0') {
                                medico = oo.medico_prestacional;
                            } else {
                                medico = oo.medico_evaluacion;
                            }
                        } else if (oo.id_tipo_atencion == 3) {
                            if (oo.id_evaluacion == '0') {
                                medico = oo.medico_prestacional;
                            } else {
                                medico = oo.medico_evolucion;
                            }
                        }
                        if (oo.radiologia_portatil == 'SI') {
                            radiologiaPortatil = oo.radiologia_portatil;
                        } else {
                            radiologiaPortatil = 'NO';
                        }
                        newOption += `<td>${medico}</td>`;
                        newOption += `<td>${oo.servicio_estudio}</td>`;
                        newOption += `<td>${oo.codigo_rad} - ${oo.nombre_examen}</td>`;
                        if (oo.observacion_cita_imagenes_por_pp == null || oo.observacion_cita_imagenes_por_pp == "") {
                            newOption += `<td></td>`;
                        } else {
                            newOption +=
                                `<td>Comentario: <br><strong>${oo.observacion_cita_imagenes_por_pp}</strong><br> Fecha:<br><strong>${oo.observ_citmul_at}</strong> </td>`;
                        }
                        newOption += `<td>${radiologiaPortatil}</td>`;
                        if (oo.comparativo_radio == 2) {
                            comparativo_radio = 'SI';
                        } else {
                            comparativo_radio = 'NO';
                        }
                        newOption += `<td>${comparativo_radio}</td>`;

                        //Columna "Ver PDF"
                        if (oo.ruta_radiologia == null || oo.ruta_radiologia == "") {
                            newOption +=
                                `<td><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></td>`;
                        } else {
                            newOption +=
                                `<td><a href="/ver_radiologia_imprimir/${oo.id}" target="_blank" class="btn btn-primary btn-xs" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div></td>`;
                        }


                        if (oo.ruta_radiologia == null || oo.ruta_radiologia == "") {
                            newOption +=
                                `<td><div class="btn-app"><span>PENDIENTE FIRMAR</span></div></td>`;
                            tienePendientes =
                                true; // Si hay al menos una orden pendiente, marcamos esta variable
                        } else {
                            newOption +=
                                `<td><input type="checkbox" name="uniqueCheckbox" value="${oo.pres_proce}" data-id-pp="${oo.pres_proce}" class="single-checkbox"></td>`;
                        }
                        newOption += `</tr>`;
                    });
                    tippy('.tooltip-trigger', {
                        content: '<strong>Titulo tooltip</strong>'
                    });
                    let table = $('#tblOrdenesImagenes').DataTable();
                    table.clear().rows.add($(newOption)).draw();
                    $('#listOrdenesImagenes').show();
                    // Asegura que solo un checkbox esté seleccionado a la vez
                    // $('.single-checkbox').on('change', function() {
                    //     $('.single-checkbox').not(this).prop('checked', false);
                    // });
                    // Permite seleccionar múltiples checkboxes
                    $('.single-checkbox').on('change', function() {
                        // Obtener el valor del checkbox seleccionado
                        const checkboxValue = $(this).val();

                        if ($("#id_servicio").val() == 114) {
                            // Crear un array para almacenar los valores de los checkboxes marcados
                            let selectedValues = [];
                            // Recorrer todos los checkboxes marcados con la clase especificada
                            $('.single-checkbox:checked').each(function() {
                                selectedValues.push($(this).val());
                            });
                            console.log("Valores seleccionados :", selectedValues);
                            // Unir los valores con comas y establecerlos en el campo
                            $('#id_cita_origen_radiologia').val(selectedValues.join(','));
                        } else {
                            console.log("Valor seleccionado (Otros servicios):", checkboxValue);
                            $('#id_cita_origen_radiologia').val(checkboxValue);
                        }
                    });
                    if (tienePendientes) {
                        bootbox.alert({
                            message: `<div style="text-align: center;">
                                  <i class="fa fa-times-circle" style="font-size: 80px; color: #d9534f;"></i>
                                  <p style="margin-top: 10px; font-weight: bold;">Los exámenes pendientes deben estar firmados antes de acceder a una cita</p>
                                </div>`,
                            buttons: {
                                ok: {
                                    label: 'Cerrar',
                                    className: 'btn-success'
                                }
                            },
                            callback: function() {
                                console.log("Alerta cerrada");

                            }
                        });

                        // Ajuste del estilo del modal para hacerlo cuadrado y centrarlo
                        setTimeout(() => {
                            $(".bootbox.modal").css({
                                "display": "flex",
                                "align-items": "center",
                                "justify-content": "center"
                            });

                            $(".modal-dialog").css({
                                "max-width": "350px", // Ajusta el tamaño
                                "width": "100%",
                                "border-radius": "10px" // Bordes más cuadrados
                            });

                            $(".modal-content").css({
                                "border-radius": "10px" // Más cuadrado aún
                            });
                        }, 100);
                    }
                }
            });
        });

        $(document).on('change', '.single-checkbox', function() {
            if ($(this).is(':checked')) {
                // Si se marca el checkbox, permitimos la acción
                $(this).prop('checked', true);
            } else {
                // Si se intenta desmarcar, revertimos la acción
                $(this).prop('checked', true);
            }
        });

        $(document).on('change', '.single-checkbox', function() {
            // Obtener el valor del checkbox seleccionado
            // const checkboxValue = $(this).val();
            // const dataIdPp = $(this).data('id-pp');
            const $this = $(this);
            const isChecked = $this.is(':checked');

            // Obtener el id_servicio_estudio del checkbox marcado
            const selectedServicioEstudio = $this.closest('tr').find('td').eq(5).text().trim();

            if (isChecked) {
                // Desmarcar los checkboxes que no coinciden con el mismo servicio_estudio
                $('.single-checkbox').each(function() {
                    const $checkbox = $(this);
                    const servicioEstudio = $checkbox.closest('tr').find('td').eq(5).text().trim();
                    if (servicioEstudio !== selectedServicioEstudio) {
                        $checkbox.prop('checked', false);
                    }
                });
            }

            // Obtener todos los checkboxes marcados
            const selectedValues = [];
            $('.single-checkbox:checked').each(function() {
                selectedValues.push($(this).val());
            });

            //$('#id_pp').val(checkboxValue);

            // Mostrar en consola
            console.log("Valores seleccionados:", selectedValues);

            // Asignar valores según id_servicio
            if ($("#id_servicio").val() == 114) {
                $('#id_cita_origen_radiologia').val(selectedValues.join(','));
            } else {
                $('#id_cita_origen_radiologia').val(selectedValues[0] || '');
            }

            // Actualizar id_pp si se necesita
            $('#id_pp').val($this.val());
        });


        $(document).ready(function() {
            $('#tblOrdenesImagenes').DataTable({
                "language": {
                    "emptyTable": "No se encontraron resultados"
                },
                "ordering": true, // Habilita el ordenamiento
                "order": [
                    [0, "asc"]
                ],
                "destroy": true
            });
        });

        function validarPacienteConIntervencionQuirurgica() {
            $("#id_intervencion_quirurgica").val(0);
            let tipodoc_beneficiario = $("#tipodoc_beneficiario").val();
            let nrodocafiliado = $("#nrodocafiliado").val();
            let id_establecimiento = $("#id_establecimiento").val();
            let id_servicio = $("#id_servicio").val();
            if (id_servicio != '') {
                $.ajax({
                    url: "/validar_paciente_con_intervencion_quirurgica/" + tipodoc_beneficiario + "/" +
                        nrodocafiliado + "/" + id_establecimiento + "/" + id_servicio,
                    type: "GET",
                    dataType: "json",
                    success: function(result) {
                        if (result != null && result.id > 0) {
                            // Si el paciente tiene una solicitud de intervención pendiente
                            bootbox.confirm({
                                message: "El paciente cuenta con una solicitud de Intervencion Quirúrgica del <b>" +
                                    result.fecha_solicitud +
                                    "</b> en el servicio seleccionado aún sin culminar.<br><b>¿La cita a registrar pertenece a dicha solicitud?</b>",
                                buttons: {
                                    confirm: {
                                        label: 'SI PERTENECE',
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: 'NO PERTENECE',
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function(userResponse) {
                                    if (userResponse) {
                                        $("#id_intervencion_quirurgica").val(result.id);
                                    }
                                    fullCalendar();
                                }
                            });
                        } else {
                            fullCalendar();
                        }
                    }
                });
            }
        }

        function alertVencimientoSeguro(fechaVigencia) {
            if (fechaVigencia) {
                formato = "YYYYMMDD";
                if (moment(fechaVigencia, "DD/MM/YYYY", true).isValid()) {
                    formato = "DD/MM/YYYY";
                } else if (moment(fechaVigencia, "YYYYMMDD", true).isValid()) {
                    formato = "YYYYMMDD";
                }
                const fechaVigenciaMoment = moment(fechaVigencia, formato, true).startOf('day');
                if (fechaVigenciaMoment) {
                    const fechaActual = moment().startOf('day');
                    const diferenciaDias = fechaVigenciaMoment.diff(fechaActual, "days");
                    if (diferenciaDias > -1 && diferenciaDias <= 30) {
                        let htmlString =
                            `<span><i class="fa fa-exclamation-triangle"></i> <b>El seguro del paciente vencerá en ${diferenciaDias} días. Es importante informarle.</b></span>`;
                        Swal.fire({
                            icon: 'info',
                            title: 'AVISO',
                            html: `
                                <div style="font-size:16px; line-height:1.6; text-align:center;">
                                    <b>El seguro del paciente vencerá en ${diferenciaDias} ${diferenciaDias === 1 ? 'día' : 'días'}.</b><br>
                                    Se recomienda acercarse a <strong>SALUDPOL</strong> para gestionar la renovación de su afiliación y evitar inconvenientes en futuras atenciones.
                                </div>
                            `,
                            confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                            confirmButtonColor: '#3085d6',
                            width: '38em',
                            customClass: {
                                popup: 'swal-afiliado_a_vencer',
                                title: 'swal-title',
                                htmlContainer: 'swal-content'
                            }
                        });
                        if (diferenciaDias === 0) {
                            htmlString =
                                `<span><i class="fa fa-exclamation-triangle"></i> <b>El seguro del paciente vence hoy. Es importante informarle.</b></span>`;
                                Swal.fire({
                                    icon: 'info',
                                    title: 'AVISO',
                                    html: `
                                    <div style="font-size:16px; line-height:1.6; text-align:center;">
                                        <b>El seguro del paciente vence hoy.</b><br>
                                        Se recomienda acercarse a <strong>SALUDPOL</strong> para gestionar la renovación de su afiliación y evitar inconvenientes en futuras atenciones.
                                    </div>
                                    `,
                                    confirmButtonText: '<i class="fa fa-check"></i> Aceptar',
                                    confirmButtonColor: '#3085d6',
                                    width: '38em',
                                    customClass: {
                                    popup: 'swal-afiliado_a_vencer',
                                    title: 'swal-title',
                                    htmlContainer: 'swal-content'
                                    }
                                });
                        }
                        $("#alertVigenciaSeguro").show();
                        $("#alertVigenciaSeguro").html(htmlString);
                    } else {
                        $("#alertVigenciaSeguro").hide();
                    }
                } else {
                    $("#alertVigenciaSeguro").hide();
                }
            }
        }

        function verificarPendienteAlta(dni_beneficiario) {
            $.ajax({
                type: "get",
                url: "/verifica_pendiente_alta_emergencia_hospitalizacion_por_dni",
                data: {
                    "dni": dni_beneficiario
                },
                success: function(result) {
                    if (result.lista_pendiente_alta != 0) {
                        $('#FormularioAlertaAtencionesPendienteAlta').trigger("reset");
                        $('#modalAlertaAtencionesPendienteAlta').modal('show');
                        $("#listaAlertaAtencionesPendienteAlta").empty();
                        var valor_alta = '';
                        var cnt_reg_pa = 0;
                        $.each(result.lista_pendiente_alta, function(key, value) {
                            cnt_reg_pa++;
                            valor_alta = valor_alta +
                                '<tr>' +
                                '<td><b style="text-transform: uppercase;">' + value
                                .establecimiento_descripcion + '</b></td>' +
                                '<td><b style="text-transform: uppercase;">' + value.tipo +
                                '</b></td>' +
                                '<td><b style="text-transform: uppercase;">' + value
                                .servicio_descripcion + '</b></td>' +
                                '<td><b style="text-transform: uppercase;">' + value
                                .fecha_registro_ingreso + ' - ' + value.hora_registro_ingreso +
                                '</b></td>';

                            valor_alta = valor_alta +
                                '<td><b style="text-transform: uppercase;color:red;">PENDIENTE DE ALTA</b></td>' +
                                '</tr>';

                        });
                        $("#nombre_de_paciente_pa").html($('#beneficiario').val());
                        $("#cantidad_de_registros_pa").html(cnt_reg_pa);
                        $("#listaAlertaAtencionesPendienteAlta").html(valor_alta);
                    }

                    if (result.pendiente_alta_emergencia === true && result.pendiente_alta_hospitalizacion ===
                        true) {
                        Swal.fire({
                            title: '<p>Atención</p>',
                            html: '<p class="descripcion-alert text-center"><span>SE HA DETECTADO QUE EL PACIENTE CONSULTADO PRESENTA <br><b style="color:red;">ALTA PENDIENTE EN EMERGENCIA Y HOSPITALIZACION.</b><br>RECUERDE REGULARIZAR SU ESTADO.</span></p>',
                            icon: 'warning',
                            allowOutsideClick: false,
                            confirmButtonColor: '#3c763d',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            customClass: {
                                icon: "swal-icon-size"
                            }
                        });
                    } else if (result.pendiente_alta_emergencia) {
                        Swal.fire({
                            title: '<p>Atención</p>',
                            html: '<p class="descripcion-alert text-center"><span>SE HA DETECTADO QUE EL PACIENTE CONSULTADO PRESENTA <br><b style="color:red;">ALTA PENDIENTE EN EMERGENCIA.</b><br>RECUERDE REGULARIZAR SU ESTADO.</span></p>',
                            icon: 'warning',
                            allowOutsideClick: false,
                            confirmButtonColor: '#3c763d',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            customClass: {
                                icon: "swal-icon-size"
                            }
                        });
                    } else if (result.pendiente_alta_hospitalizacion) {
                        Swal.fire({
                            title: '<p>Atención</p>',
                            html: '<p class="descripcion-alert text-center"><span>SE HA DETECTADO QUE EL PACIENTE CONSULTADO PRESENTA <br><b style="color:red;">ALTA PENDIENTE EN HOSPITALIZACIÓN.</b><br>RECUERDE REGULARIZAR SU ESTADO.</span></p>',
                            icon: 'warning',
                            allowOutsideClick: false,
                            confirmButtonColor: '#3c763d',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            customClass: {
                                icon: "swal-icon-size"
                            }
                        });
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function limpiarSeleccionProcedimientoPp() {
            $('#cita_pp').val('0');
            $('#id_solicitud_procedimiento_detalle').val('');
            $('#id_procedimiento_pp').val('');
            $('.chk-orden-pp').prop('checked', false);
        }

        var ordenesPpPaciente = [];
        var serviciosProcedimientoPp = [];

        function cargarOrdenesProcedimientosPendientes(dni_beneficiario) {
            ordenesPpPaciente = [];
            mostrarOrdenesProcedimientosPorServicio();
            $.when(
                $.ajax({ url: `/listar_ordenes_procedimientos_pendientes/${dni_beneficiario}`, type: "GET", dataType: "json" }),
                $.ajax({ url: '/pp_servicios_procedimiento', type: "GET", dataType: "json" })
            ).done(function(rOrdenes, rServicios) {
                ordenesPpPaciente = rOrdenes[0] || [];
                serviciosProcedimientoPp = (rServicios[0] || []).map(Number);
                mostrarOrdenesProcedimientosPorServicio();
            }).fail(function(response) {
                console.log(response);
            });
        }

        $(document).on('change', '#id_servicio', function() {
            mostrarOrdenesProcedimientosPorServicio();
        });

        function mostrarOrdenesProcedimientosPorServicio() {
            limpiarSeleccionProcedimientoPp();
            if ($.fn.DataTable.isDataTable('#tblOrdenesProcedimientos')) {
                $('#tblOrdenesProcedimientos').DataTable().clear().destroy();
            }
            $('#tblOrdenesProcedimientos tbody').html('');
            $('#listOrdenesProcedimientos').hide();

            const id_servicio_sel = parseInt($('#id_servicio').val(), 10) || 0;
            if (!id_servicio_sel || ordenesPpPaciente.length == 0) return;
            const esServicioProcedimiento = serviciosProcedimientoPp.indexOf(id_servicio_sel) !== -1;

            const response = ordenesPpPaciente.filter(function(oo) {
                const reevalPendiente = parseInt(oo.requiere_cita, 10) === 2 && !oo.fecha_cita_reevaluacion;
                if (reevalPendiente) return parseInt(oo.id_servicio_solicitante, 10) === id_servicio_sel;
                const idsServicio = (oo.ids_servicio_procedimiento || '').split(',').map(Number);
                return esServicioProcedimiento && idsServicio.indexOf(id_servicio_sel) !== -1;
            });
            if (response.length == 0) return;

            (function() {
                    let filas = '';
                    $(response).each(function(ii, oo) {
                        let procedimiento = `${oo.codigo} - ${oo.descripcion}`;
                        if (parseInt(oo.cantidad, 10) > 1) {
                            procedimiento += `<br><b>Programados:</b> ${oo.cantidad} <b>Pendientes:</b> ${oo.pendientes}`;
                        }
                        const requiereReeval = parseInt(oo.requiere_cita, 10) === 2;
                        const tieneReeval = !!oo.fecha_cita_reevaluacion;
                        let reevaluacion = '-';
                        if (requiereReeval && tieneReeval) {
                            reevaluacion = `<div class="btn-app-g"><span>TIENE CITA REEVALUACIÓN</span></div><div style="margin-top:4px; font-size:11px; font-weight:bold;">${oo.fecha_cita_reevaluacion}</div>`;
                        } else if (requiereReeval) {
                            reevaluacion = `<div class="btn-app" title="Se asigna al citar al paciente en ${oo.servicio_solicitante}"><span>PENDIENTE CITA DE REEVALUACIÓN</span></div>`;
                        }
                        let accion = '';
                        if (requiereReeval && !tieneReeval) {
                            accion = `<input type="checkbox" disabled style="transform: scale(1.4);"
                                title="Primero debe tener cita de reevaluación en ${oo.servicio_solicitante}">`;
                        } else {
                            accion = `<input type="checkbox" class="chk-orden-pp" style="transform: scale(1.4);"
                                data-id-detalle="${oo.id_detalle}" data-id-procedimiento="${oo.id_procedimiento}">`;
                        }
                        filas += `<tr>
                            <td class="text-center">${oo.nro_orden}</td>
                            <td class="text-center">${oo.fecha_orden}</td>
                            <td class="text-center">${oo.tipo_atencion ? oo.tipo_atencion : ''}</td>
                            <td class="text-center">${oo.servicio_solicitante ? oo.servicio_solicitante : ''}</td>
                            <td class="text-center">${oo.personal_salud ? oo.personal_salud : ''}</td>
                            <td class="text-center" style="font-size: 11px;">${procedimiento}</td>
                            <td class="text-center">${reevaluacion}</td>
                            <td class="text-center">${accion}</td>
                        </tr>`;
                    });
                    $('#tblOrdenesProcedimientos tbody').html(filas);
                    $('#tblOrdenesProcedimientos').DataTable({
                        "language": {
                            "emptyTable": "No se encontraron resultados",
                            "lengthMenu": "Mostrar _MENU_ items",
                            "search": "Buscar:",
                            "info": "Mostrando _START_ al _END_ de _TOTAL_ registros",
                            "infoEmpty": "Mostrando 0 al 0 de 0 registros",
                            "infoFiltered": "(filtrado de _MAX_ registros)",
                            "zeroRecords": "No se encontraron resultados",
                            "paginate": {
                                "previous": "Previo",
                                "next": "Siguiente"
                            }
                        },
                        "ordering": true,
                        "order": [],
                        "destroy": true
                    });
                    $('#listOrdenesProcedimientos').show();
            })();
        }

        $(document).on('change', '.chk-orden-pp', function() {
            const marcado = $(this).is(':checked');
            $('.chk-orden-pp').not(this).prop('checked', false);
            if (marcado) {
                $('#cita_pp').val('1');
                $('#id_solicitud_procedimiento_detalle').val($(this).data('id-detalle'));
                $('#id_procedimiento_pp').val($(this).data('id-procedimiento'));
            } else {
                limpiarSeleccionProcedimientoPp();
            }
            $('#divHorario').html('');
        });

        // function verificarProgramacionDeProcedimientosPendiente(dni_beneficiario) {
        //     $.ajax({
        //         type: "get",
        //         url: `/verifica_pendiente_pp_por_dni/${dni_beneficiario}`,
        //         success: function(result) {
        //             $('#FormularioPp').trigger("reset");
        //             $("#listaPp").empty();
        //             var valor_pp = '';
        //             var countPp = 0;
        //             const data = JSON.parse(result);
        //             /* if (data.length > 0) {
        //                 $('#modalAlertaPp').modal('show');
        //             } */
        //             for (let i = 0; i < data.length; i++) {
        //                 if (data[i].pendientes == 0) continue;
        //                 countPp++;
        //                 valor_pp = valor_pp +
        //                     '<tr>' +
        //                     '<td style="vertical-align: middle;"><b style="text-transform: uppercase;">' + (
        //                         data[
        //                             i]
        //                         .establecimiento ?? 'Sin data') + '</b></td>' +
        //                     '<td style="vertical-align: middle;">' +
        //                     `${data[i].codigo} - ${data[i].descripcion} <br><b style="color: red;">FECHA SOLICITUD: ${data[i].fecha_solicitud}</b><br><span><b>PROGRAMADOS: </b>${data[i].cantidad}  <b>PENDIENTES DE CITA: </b>${data[i].pendientes}</span>` +
        //                     '</td>' +
        //                     `<td style="vertical-align: middle; text-align: center;"><a href="/formulario_cita_pp/${data[i].id_pp}"` +
        //                     (data[i].sin_consultorios ?
        //                         `style="pointer-events: none; color: gray;" onclick="event.preventDefault();">Procedimiento sin consultorio asignado` :
        //                         'class="btn-sm btn-primary"><b><i class="fa fa-calendar" aria-hidden="true"></i></b>'
        //                     ) + `</a></td>` +
        //                     '</tr>';
        //             }
        //             if (valor_pp !== "") {
        //                 $('#modalAlertaPp').modal('show');
        //             }
        //             $("#nombre_de_paciente_pp").html($('#beneficiario').val());
        //             $("#cantidad_de_registros_pp").html(countPp);
        //             $("#listaPp").html(valor_pp);
        //         },
        //         error: function(response) {
        //             console.log(response);
        //         }
        //     });
        // }

        $(document).on('click', '.verPdf', function() {
            event.preventDefault();
            event.stopPropagation();

            let idRadiologia = $(this).data('id-rad');
            let idServicioRadiologia = $(this).data('id-servicio-rad');
            let nombreServicioRadiologia = $(this).data('nombre-servicio-rad');
            let id_pp = $(this).data('id-pp');

            console.log({
                idRadiologia,
                idServicioRadiologia,
                nombreServicioRadiologia,
                id_pp
            });

            $('#nropresmodal').text(nombreServicioRadiologia);

            $.ajax({
                url: `/obtener_pdfs_detalle_radiologia/${idRadiologia}/${idServicioRadiologia}/${id_pp}`,
                async: true, // Asíncrono para no bloquear el navegador
                success: function(result) {
                    let listPdfs = `<div style="display: flex; flex-direction: column;">`;

                    $(result).each(function(ii, oo) {
                        listPdfs += `
                        <div class="col-sm-12">
                            <div class="col-sm-10" style="padding-top:5px;">
                                <label>${oo.codigo} - ${oo.nombre}</label>
                            </div>
                            <div class="col-sm-2" style="padding-top:5px;">
                                <a href="/informe_imagenes/${idRadiologia}/${idServicioRadiologia}/${oo.id}/0/${oo.flag}/${oo.ruta_prestacion_procedimiento}" 
                                target="_blank" 
                                class="btn btn-success btn-xs">
                                <i class="fa fa-download"></i>
                                </a>
                            </div>
                        </div>
                    `;

                        $(oo.informes_adicionales).each(function(iii, ooo) {
                            let disabledStyle = ooo.ruta_informe_radiologia_adicional ?
                                '' :
                                'pointer-events: none; opacity: 0.5; cursor: default;';
                            let downloadUrl = ooo.ruta_informe_radiologia_adicional ?
                                `/informe_imagenes/${idRadiologia}/${idServicioRadiologia}/${oo.id}/${ooo.id_informe_adicional}/${ooo.flag}/${ooo.ruta_informe_radiologia_adicional}` :
                                '#';

                            listPdfs += `
                            <div class="col-sm-12">
                                <div class="col-sm-10" style="padding-top:5px;">
                                    <label>${oo.codigo} - ${oo.nombre} - Adenda</label>
                                </div>
                                <div class="col-sm-2" style="padding-top:5px;">
                                    <a href="${downloadUrl}" 
                                    target="_blank" 
                                    class="btn btn-xs" 
                                    style="background-color: #005b80; ${disabledStyle}">
                                    <i style="color: white;" class="fa fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        `;
                        });
                    });

                    listPdfs += `</div>`;
                    $("#detalle-pdf").html(listPdfs);
                    $("#myModalDetPdfRadiologia").modal();
                }
            });
        });

    </script>
@endsection

@section('content')
    <div class="loader"></div>
    <div class="container-fluid">
        <div class="row">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span
                                style="padding: 9px; background-color: rgb(109, 218, 132); color: green;margin-right: 10px">CE</span>MÓDULO
                            DE GESTIÓN DE CITAS</h3>
                    </div>
                    <div class="panel-body">
                        <form class="app-form" method="post" action="{{ route('registrar_receta_rua') }}" id="frmCita"
                            autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="es_asegurado" name="es_afiliado" value="">
                            <input type="hidden" name="id_nivel" id="id_nivel" value="<?php //echo $id_nivel
                            ?>">
                            <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                            <input type="hidden" value="{{ Auth::user()->id_establecimiento }}" name="id_establecimiento_current"
                                id="id_establecimiento_current">
                            <input type="hidden" value="" name="id_pp" id="id_pp">
                            <input type="hidden" value="" name="id_cita_origen_laboratorio"
                                id="id_cita_origen_laboratorio">
                            <input type="hidden" value="" name="id_cita_origen_cita_resultado_laboratorio"
                                id="id_cita_origen_cita_resultado_laboratorio">
                            <input type="hidden" value="" name="id_cita_origen_radiologia"
                                id="id_cita_origen_radiologia">
                            <input type="hidden" value="0" name="cita_pp" id="cita_pp">
                            <input type="hidden" value="" name="id_solicitud_procedimiento_detalle" id="id_solicitud_procedimiento_detalle">                            <input type="hidden" value="" name="id_procedimiento_pp" id="id_procedimiento_pp">
                            <div class="alert alert-warning" role="alert" id="alertVigenciaSeguro"
                                style="padding-left: 12px" hidden></div>
                            <div class="app-panel-title app-mt-4 app-mb-3" style="display:flex; align-items:center; justify-content:space-between;">
                                <strong>INFORMACIÓN DEL PACIENTE</strong>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="tipo">TIPO DOC.</label>
                                        <select class="form-control input-sm" id="tipodoc_beneficiario_bus"
                                            name="tipodoc_beneficiario_bus" onchange="maxlength_doc_usu()" tabindex="3">
                                            <option value="1" selected="">DNI</option>
                                            <option value="2">CARNET DE EXTRANJERIA</option>
                                            <option value="3">PASAPORTE</option>
                                            <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                        </select>
                                        <input type="hidden" value="" name="tipodoc_beneficiario"
                                            id="tipodoc_beneficiario" class="form-control">
                                        <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <label for="dni_beneficiario">NRO DOC.</label>
                                    <div class="input-group">
                                        <input onKeyPress="return validacionTipoYNumDocumento(event)" id="dni_beneficiario"
                                            name="dni_beneficiario" type="text" placeholder=""
                                            class="form-control input-sm text-uppercase" maxlength="8" autofocus=""
                                            tabindex="4">
                                        <span class="input-group-btn" style="height:28px!important;">
                                            <button type="button" id="btnBuscar" onclick="obtenerBeneficiario()"
                                                class="btn btn-info"
                                                style="height:28px!important;line-height:10px">Buscar</button>
                                        </span>
                                    </div>
                                    <input type="hidden" class="form-control" value="" name="cip_beneficiario"
                                        id="cip_beneficiario" readonly="" />
                                    <input type="hidden" class="form-control" value="" name="nrodocafiliado"
                                        id="nrodocafiliado" readonly="" />
                                    <input type="hidden" class="form-control" value="" name="nrodoctitular"
                                        id="nrodoctitular" readonly="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>APELLIDOS Y NOMBRES</label>
                                        <input readonly="" type="text" class="form-control" value=""
                                            name="beneficiario" id="beneficiario" style="text-align:left">
                                        <input type="hidden" class="form-control" value=""
                                            name="nombre_beneficiario" id="nombre_beneficiario" />
                                        <input type="hidden" class="form-control" value=""
                                            name="paterno_beneficiario" id="paterno_beneficiario" />
                                        <input type="hidden" class="form-control" value=""
                                            name="materno_beneficiario" id="materno_beneficiario" />
                                        <input type="hidden" class="form-control" value="" name="nombre_titular"
                                            id="nombre_titular" />
                                        <input type="hidden" class="form-control" value="" name="paterno_titular"
                                            id="paterno_titular" />
                                        <input type="hidden" class="form-control" value="" name="materno_titular"
                                            id="materno_titular" />
                                        <input type="hidden" class="form-control" value="" name="fecnacafiliado"
                                            id="fecnacafiliado" />
                                        <input type="hidden" class="form-control" value="" name="nomsexo"
                                            id="nomsexo" />
                                        {{-- <input type="hidden" class="form-control" value="" name="nro_historia" id="nro_historia"> --}}
                                        <input type="hidden" value="0" name="id_intervencion_quirurgica"
                                            id="id_intervencion_quirurgica">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>DOCUMENTO</label>
                                        <input type="text" class="form-control" value=""
                                            name="tipo_nrodocafiliado" id="tipo_nrodocafiliado" readonly="" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>PARENTESCO</label>
                                        <input readonly value="" type="text" name="tipo_beneficiario"
                                            id="tipo_beneficiario" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>GRADO</label>
                                        <input readonly value="" type="text" name="grado" id="grado"
                                            class="form-control">
                                        <input readonly type="hidden" class="col-xs-5 text-12  input-sin-borde "  name="situacion_titular" id="situacion_titular" style="display:inline-block;"> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>HISTORIA CLINICA</label>
                                        <input value="" type="text" name="nro_historia" id="nro_historia"
                                            class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>TELÉFONO</label>
                                        <input value="" type="text" name="telefono" id="telefono"
                                            class="form-control" maxlength="9" onKeyPress="return soloNumeros(event)">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>TELÉFONO 2</label>
                                        <input value="" type="text" name="telefono2" id="telefono2"
                                            class="form-control" maxlength="9" onKeyPress="return soloNumeros(event)">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>DOMICILIO</label>
                                        <input value="" type="text" name="domicilio" id="domicilio"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>CORREO</label>
                                        <input value="" type="text" name="correo" id="correo"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="app-panel-title app-mt-4 app-mb-3"><strong>INFORMACIÓN DE LA PRESCRIPCION Y
                                    SERVICIO</strong></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="id_establecimiento">ABSCRIPCIÓN</label>
                                                <select class="form-control" onchange="getcargaservicio()"
                                                    name="id_establecimiento" id="id_establecimiento" tabindex="8"
                                                    style="width: 100%" readonly="" selected>
                                                    <option value="">--Seleccionar--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                    SERVICIO
                                                    {{--
                         &nbsp;
                        <label class="radio-inline">
                          <input class="app-radio-fix01" type="radio" name="parent" id="parent1" value="1"> GENERAL
                        </label>
                        <label class="radio-inline">
                          <input class="app-radio-fix01" type="radio" name="parent" id="parent2" value="2"> ESPECIALIDAD
                        </label>
                      --}}
                                                </label>
                                                <input type="hidden" name="parent" id="parent2" value="2">
                                                <select class="form-control" name="id_servicio" id="id_servicio"
                                                    tabindex="8" selected>
                                                    <option value="">--Seleccionar--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="contServicioEstudio" style="display: none;">
                                            <div class="form-group">
                                                <label>
                                                    SERVICIO DE ESTUDIO
                                                </label>
                                                <select class="form-control" name="id_servicio_estudio"
                                                    id="id_servicio_estudio" tabindex="8">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="tipo_cita">TIPO CITA</label>
                                                <select class="form-control" name="tipo_cita" id="id_tipo_cita"
                                                    tabindex="8">
                                                    <option value="">--Seleccionar--</option>
                                                    <option value="1" selected>Presencial</option>
                                                    <option value="2">Teleconsulta</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--referencia-->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="tipo_cita">REFERIDO</label>
                                                <select class="form-control" name="referido" id="referido"
                                                    tabindex="10" onChange="mostrar(this.value);">
                                                    <option value="">--Seleccionar--</option>
                                                    <option value="NO" selected>NO</option>
                                                    <option value="SI">SI</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="establereferencia" style="display: none;">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="tipo_cita">ESTABLECIMIENTO DE LA REFERENCIA</label>
                                                    <select class="form-control" name="establecimiento_referencia"
                                                        id="establecimiento_referencia" tabindex="10"
                                                        onchange="getarea_servicio();">
                                                        <option value="">--Seleccionar--</option>
                                                        <?php foreach($estableciemientoreferencia as $row):  ?>
                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->nombre; ?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="tipo_cita">SERVICIO DE LA REFERENCIA</label>
                                                    <select class="form-control" name="servicio_referencia"
                                                        id="servicio_referencia" tabindex="10">
                                                        <option value="" selected="">--SELECCIONAR--</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="tipo_cita"><b>DOCUMENTO DE LA REFERENCIA</b></label>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="col-md-6">
                                                    <label for="tipo_cita">TIPO DE DOCUMENTO</label>
                                                    <select class="form-control" name="tipo_doc" id="tipo_doc"
                                                        tabindex="10">
                                                        <option value="0">--Seleccionar--</option>
                                                        <option value="1">OFICIO</option>
                                                        <option value="2">ACTA</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="tipo_cita"># DE DOCUMENTO</label>
                                                    <input value="" type="text" name="numero_doc"
                                                        id="numero_doc" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="tipo_cita">AÑO</label>
                                                    <input value="<?php echo $anio; ?>" type="text" name="ano"
                                                        id="ano" class="form-control" readonly="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="origen_doc">DEPENDENCIA</label>
                                                    <select class="form-control" name="origen_doc" id="origen_doc"
                                                        tabindex="10">
                                                        <option value="0">--Seleccionar--</option>
                                                        <option value="1">/DIRSAPOL -</option>
                                                        <option value="2">/DIRSAPOL/SUBDIR -</option>
                                                        <option value="3">/DIRSAPOL/SECRETARIA - </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="tipo_cita">ESTABLECIMIENTO</label>
                                                    <select class="form-control" name="codigo_renipres"
                                                        id="codigo_renipres" tabindex="10">
                                                        <option value="">--Seleccionar--</option>
                                                        <?php foreach($estableciemientoreferencia as $row):  ?>
                                                        <option value="<?php echo $row->codigo; ?>"><?php echo $row->concatenado; ?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="consultorio_x_servicio">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="tipo_cita">CONSULTORIO DE PROCEDENCIA</label>
                                                    <select class="form-control" name="consultorio_referencia"
                                                        id="consultorio_referencia" tabindex="10"
                                                        onchange="getarea_servicio();">
                                                        <option value="">--Seleccionar--</option>
                                                        <?php foreach($servicio as $row):  ?>
                                                        <option value="<?php echo $row->nombre; ?>"><?php echo $row->nombre; ?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-12">
                                            <label for="tipo_cita">OBSERVACION</label>
                                            <input value="" type="text" name="obs_registro_cita"
                                                id="obs__registro_cita" class="form-control">
                                        </div>
                                        <div class="col-md-12" style="padding-top: 2rem;">
                                            <div class="panel panel-success" id="listOrdenesProcedimientos"
                                                style="display: none;">
                                                <div class="panel-heading text-center">
                                                    <h3 class="panel-title size-titulo"><b>ÓRDENES DE PROCEDIMIENTOS PENDIENTES</b></h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table id="tblOrdenesProcedimientos" class="table" cellspacing="0"
                                                            width="100%" style="font-size: 12px;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Nº de Orden</th>
                                                                    <th class="text-center">Fecha de Orden</th>
                                                                    <th class="text-center">Tipo de Atención</th>
                                                                    <th class="text-center">Servicio Solicitante</th>
                                                                    <th class="text-center">Personal de Salud</th>
                                                                    <th class="text-center">Procedimiento Solicitado</th>
                                                                    <th class="text-center">Cita de Reevaluación</th>
                                                                    <th class="text-center">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <small class="text-muted">En el servicio de procedimientos programados, marque el procedimiento y elija la fecha en el calendario.
                                                        Si figura "Pendiente cita de reevaluación", la cita que registre en el servicio solicitante quedará como su reevaluación; luego podrá citar el procedimiento.</small>
                                                </div>
                                            </div>
                                            <div class="panel panel-info" id="listOrdenesImagenes"
                                                style="display: none;">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title size-titulo">
                                                        Ordenes de Imagenes Pendientes
                                                    </h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table id="tblOrdenesImagenes" class="table" cellspacing="0"
                                                            width="100%" style="">
                                                            <thead>
                                                                <tr>
                                                                    <th style="text-align: left;">Nro. Orden</th>
                                                                    <th style="text-align: left;">Fecha Orden</th>
                                                                    <th style="text-align: left;">Tipo Atención</th>
                                                                    <th style="text-align: left;">Servicio</th>
                                                                    <th style="text-align: left;">Médico</th>
                                                                    <th style="text-align: left;">Servicio de Estudio</th>
                                                                    <th style="text-align: left;">CPMS</th>
                                                                    <th style="text-align: left;">Observacion</th>
                                                                    <th style="text-align: left;">Portátil</th>
                                                                    <th style="text-align: left;">Comparativo</th>
                                                                    <th style="text-align: left;">Visualizar Orden</th>
                                                                    <th style="text-align: left;">&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- ORDENES DE LABORATORIO 1ERO CITA ORIGEN --}}
                                            <div class="panel panel-info" id="listOrdenesLaboratorioCitaOrigen"
                                                style="display: none;">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title size-titulo">
                                                        Citas con Orden de Laboratorio Inicial
                                                    </h3>
                                                </div>
                                                <div class="panel-body" style="overflow-y: scroll;">
                                                    <table id="tblOrdenesLaboratorioCitaOrigen" class="table"
                                                        cellspacing="0" width="100%" style="font-size: 12px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: left;">Nro. Orden</th>
                                                                <th style="text-align: left;">Fecha Orden</th>
                                                                <th style="text-align: left;">Tipo Atención</th>
                                                                <th style="text-align: left;">Servicio</th>
                                                                <th style="text-align: left;">Consultorio</th>
                                                                <th style="text-align: left;">Médico</th>
                                                                <th style="text-align: left;">CPMS</th>
                                                                <th style="text-align: left;">Visualizar Orden</th>
                                                                <th style="text-align: left;">&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            {{-- ORDENES DE LABORATORIO 2DO CITA ORIGEN CON CITA RESULTADO --}}
                                            <div class="panel panel-info"
                                                id="listOrdenesLaboratorioCitaOrigenCitaResultado" style="display: none;">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title size-titulo">
                                                        Citas con Orden de Laboratorio Inicial con Cita de Resultado
                                                    </h3>
                                                </div>
                                                <div class="panel-body" style="    overflow-y: scroll;">
                                                    <table id="tblOrdenesLaboratorioCitaOrigenCitaResultado"
                                                        class="table" cellspacing="0" width="100%" style="font-size: 12px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: left;">Nro. Orden</th>
                                                                <th style="text-align: left;">Fecha Orden</th>
                                                                <th style="text-align: left;">Tipo Atención</th>
                                                                <th style="text-align: left;">Servicio</th>
                                                                <th style="text-align: left;">Consultorio</th>
                                                                <th style="text-align: left;">Médico</th>
                                                                <th style="text-align: left;">Próxima Cita</th>
                                                                <th style="text-align: left;">CPMS</th>
                                                                <th style="text-align: center;">Visualizar Orden</th>
                                                                <!-- <th style="text-align: left;">&nbsp;</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id='calendar'></div>
                                </div>
                            </div>
                            <ul class="nav nav-tabs app-mt-4" role="tablist">
                                <li role="presentation" class="active"><a href="#tabcita" aria-controls="tabcita"
                                        role="tab" data-toggle="tab">CITAS DE EMERGENCIA Y REFERENCIA</a></li>
                                <li role="presentation"><a href="#tabhistorial" aria-controls="tabhistorial"
                                        role="tab" data-toggle="tab">HISTORIAL DE CITA</a></li>
                                <li role="presentation"><a href="#tabhistorialimagenes"
                                        aria-controls="tabhistorialimagenes" role="tab" data-toggle="tab">HISTORIAL
                                        DE CITA DE IMAGENES</a></li>
                                <li role="presentation"><a href="#tabhistoriallaboratorio"
                                        aria-controls="tabhistorialimagenes" role="tab" data-toggle="tab">HISTORIAL
                                        LABORATORIO</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="tabcita">
                                    {{-- <div class="app-panel-title app-mt-4 app-mb-3"><strong>PROGRAMACIONES MEDICAS</strong></div> --}}
                                    <div id='divHorario'></div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="tabhistorial">
                                    {{-- <div class="app-panel-title"><strong>INFORMACIÓN DE LAS CITAS DEL ASEGURADO</strong></div> --}}
                                    <div class="form-group">
                                        <div id='divHistorial'></div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="tabhistorialimagenes">
                                    {{-- <div class="app-panel-title"><strong>INFORMACIÓN DE LAS CITAS DEL ASEGURADO</strong></div> --}}
                                    <div class="form-group">
                                        <div id='divHistorialImagenes'></div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="tabhistoriallaboratorio">
                                    {{-- <div class="app-panel-title"><strong>INFORMACIÓN DE LAS CITAS DEL ASEGURADO</strong></div> --}}
                                    <div class="form-group">
                                        <div id='divHistoriallaboratorio'></div>
                                    </div>
                                </div>
                            </div>
                            <hr style="clear: both;">
                            <input class="btn btn-primary pull-right" value="CREAR" name="crea" type="button"
                                form="prestacionescrea" id="btnCita" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="showProximasCitas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">

            <div class="modal-body">
                <div id="diveditpreg"></div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="myModalDetPdfRadiologia" role="dialog">
        <div class="modal-dialog modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">VER PDFS DE <span id="nropresmodal"></span></h5>
                </div>
                <div class="modal-body">
                    <div id="detalle-pdf"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>



    @include('frontend.includes.footer')
@endsection
