@extends('frontend.layouts.master3')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.css" integrity="sha512-72McA95q/YhjwmWFMGe8RI3aZIMCTJWPBbV8iQY3jy1z9+bi6+jHnERuNrDPo/WGYEzzNs4WdHNyyEr/yXJ9pA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
    .opciones_form {
        border: 2px solid #dbdbdb;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 10px;
    }
    
    .dataTables_info, .dataTables_filter{
        display: none;
    }
    .hoverable{
        cursor: pointer;
    }
    #seleccionarServicio_opcion, .select2-container, .select2-container--default{
        width: 100% !important;
    }
    .pad-top-10px{
        padding-top: 10px;
    }
    .pad-10px{
        padding: 10px;
    }
</style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>

<script>
    /* INICIO DOCUMENT READY */
    $(document).ready(function() {
        $('#fecha_ini_salud_consulta').datepicker({
            useCurrent: false,
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            orientation: "auto auto"
        })
        $('#fecha_fin_salud_consulta').datepicker({
            useCurrent: false,
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            orientation: "bottom right"
        })
        let idFarmacia = $("#id_farmacia").val();
        

        function cargarServiciosByIpress(idFarmacia){

            $('#seleccionarServicio_opcion').html('<option value="0">--SELECCIONAR--</option>');

            $.ajax({
            type: "GET",
            datatype: 'JSON',
            url: "getServiciosByIpress/" + idFarmacia,
            success: function(datos_ipress_query) {
                $.each(datos_ipress_query, function(index, servicio) {
                    $('#seleccionarServicio_opcion').select2();
                    $('#seleccionarServicio_opcion').append(`
                            <option value="${servicio.id_consultorio}">${servicio.nombre}</option>
                    `);
                });
                /* console.log(datos_ipress_query) */
            }
        });
        }
        cargarServiciosByIpress(idFarmacia);

        function realizarConsultaDNI() {
            Swal.fire({
                html: '<h5>OBTENIENDO DATOS DEL ASEGURADO</h5>',
                showLoaderOnConfirm: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            let campo_de_busqueda = $('#campo_de_busqueda').val();
            let nro_documento = $('#dni_consulta').val().trim();
            $.ajax({
                type: "GET",
                datatype: 'JSON',
                url: `getAseguradoDataByDocument/${campo_de_busqueda}/${nro_documento}`,
                success: function(afiliado) {
                    let nombres_paciente =
                        `${afiliado.nomafiliado} ${afiliado.apepatafiliado} ${afiliado.apematafiliado}`;
                    /* asignar valor al inpt hiddden */
                    $('#dni_consulta_hd').val(afiliado.nrodocafiliado);
                    /* settear text */

                    //DECODIFICAR CARACTERES ESPECIALES
                    const pacienteIsoCaracteres = decodeURIComponent(escape(nombres_paciente));

                    $('#nombresPaciente').text(pacienteIsoCaracteres);
                    $('#datosPaciente').slideDown('slow');
                    Swal.close();
                },
                error: function(xhr, status, error) {
                    const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr
                        .responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                    const messageErrorTitle = "Error de búsqueda"
                    Swal.fire({
                        icon: 'error',
                        title: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'></span>${messageErrorTitle.toUpperCase()}</div>`,
                        html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                    $('#datosPaciente').slideUp('slow');
                    $('#dni_consulta_hd').val(0);
                },
                complete: function() {
                    Swal.hideLoading();
                }
            });
        }

        function showModalCita(id_cita){
            Swal.fire({
                html: '<h5>CARGANDO DATOS ...</h5>',
                showLoaderOnConfirm: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                type: "GET",
                datatype: 'JSON',
                url: `getCitaById/${id_cita}`,
                success: function(data) {
                    let obj = JSON.parse(data);
                    $('#fecha_cita').val(obj.cita_fecha);
                    $('#hora_cita').val(obj.cita_hora);
                    $('#servicio_cita').val(obj.cita_servicio);
                    $('#consultorio_cita').val(obj.cita_consultorio);
                    $('#medico_cita').val(obj.medico_nombres);
                    $('#tipo_cita').val(obj.cita_tipo_cita);
                    $('#dni_cita').val(obj.ase_dni);
                    $('#nombres_cita').val(`${obj.ase_nombre} ${obj.ase_paterno} ${obj.ase_materno}`);
                    $('#parentesco_cita').val(obj.cita_parentesco);
                    $('#grado_cita').val(obj.ase_grado);
                    $('#correos_cita').val(obj.ase_correo);
                    $('#establecimiento_cita').val(obj.establecimiento);
                    Swal.close();
                },
                error: function(xhr, status, error) {
                        const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                        const messageErrorTitle = "Error de búsqueda"
                        Swal.fire({
                            icon: 'error',
                            title: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'></span>${messageErrorTitle.toUpperCase()}</div>`,
                            html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                            confirmButtonText: 'ENTENDIDO',
                        });
                },
                complete: function() {
                        Swal.hideLoading();
                }
            });
        }
        function limpiarMdlCredenciales(){
            $('#username_atenciones').val('');
            $('#password_atenciones').val('');
            $('#motivo_consulta_atenciones').val('');
        }
        function loadReauthAtencion(id_atencion, username, pass, motivo ){
            Swal.fire({
                html: '<h5>Validando credenciales</h5>',
                showLoaderOnConfirm: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                type: "POST",
                url: "{{route('authPDF')}}",
                data: {
                    email: username,
                    password: pass,
                    id_atencion: id_atencion,
                    motivo: motivo
                },
                dataType: "json",
                success: function (res) {
                    if(res.response == 'AUTH'){
                        let urlApp = window.location.origin;
                        let urlPdf = res.message.ruta;
                        let url_redirect = '';
                        if(urlPdf){
                            url_redirect = `${urlApp}/${urlPdf}`;
                            window.open(url_redirect, '_blank');
                            $('#cerrarModalAtencion').click();
                            Swal.close();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'></span>ERROR AL UBICAR EL PDF</div>`,
                                html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>No ha sido posible encontrar la ruta del archivo PDF.</span></div>`,
                                confirmButtonText: 'ENTENDIDO',
                            });
                        }

                    }
                }, 
                error: function (xhr, status, err){
                    console.log(xhr.responseJSON);
                    const objError = xhr.responseJSON;
                    let messageError = '';

                    if(objError.email || objError.motivo || objError.password){
                        $.each(objError, function (i, value) { 
                            messageError = `${messageError} ${value[0]} <br> <br>`
                        });
                    }else{
                        messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                    }
                    const messageErrorTitle = "ERROR"
                    Swal.fire({
                        icon: 'error',
                        title: `<div style='margin: 0px 0px;'><span style='color: #000000; font-size: 20px'></span>${messageErrorTitle.toUpperCase()}</div>`,
                        html: `<div style='margin: 0px 0px; padding: 0px 20px;'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                },complete: function() {
                    Swal.hideLoading();
                }
                
            });
            
        }
        $('#realizarConsulta').click(function(e) {
            mostrarTabla(0);
            limpiarChecks();
            realizarConsultaDNI();
        });

        /* DESHABILITA/HABILITA CHECK TODOS IPRESS*/
        $('#todosIpress_checkbox').click(function(e) {
            if ($('#todosIpress_checkbox').is(":checked")) {
                $("#seleccionarIpress_opcion").prop('disabled', true);
                cargarServiciosByIpress(0)
                
            } else {
                $("#seleccionarIpress_opcion").prop('disabled', false);
                cargarServiciosByIpress(idFarmacia)
            }
        });

        /* DESHABILITA/HABILITA CHECK TODOS SERVICIO*/
        $('#todosServicio_checkbox').click(function(e) {
            if ($('#todosServicio_checkbox').is(":checked")) {
                $("#seleccionarServicio_opcion").prop('disabled', true);
            } else {
                $("#seleccionarServicio_opcion").prop('disabled', false);
            }
        });

        /* MUESTRA U OCULTA LAS TABLAS SEGUN RADIOCHECK */
        function mostrarTabla(valueId) {

            $('#tabla_citas').hide();
            $("#tabla_atenciones_medicas").hide();
            $("#tabla_procedimientos").hide();
            $("#tabla_recetas").hide();
            $("#tabla_examenes_auxiliares").hide();
            $("#tabla_examenes_auxiliares_laboratorio").hide();

            switch (valueId) {
                case 1:
                    $('#tabla_citas').show();
                    break;
                case 2:
                    $("#tabla_atenciones_medicas").show();
                    break;
                case 3:
                    $("#tabla_procedimientos").show();
                    break;
                case 4:
                    $("#tabla_recetas").show();
                    break;
                case 5:
                    $("#tabla_examenes_auxiliares").show();
                    break;
                case 6:
                    $("#tabla_examenes_auxiliares_laboratorio").show();
                    break;
            }
        }
        function limpiarChecks(){
            $('#radio_citas').attr('checked', false);
            $('#radio_atenciones_medicas').attr('checked', false);
            $('#radio_procedimientos').attr('checked', false);
            $('#radio_recetas').attr('checked', false);
            $('#radio_examenes_auxiliares').attr('checked', false);
            $('#radio_examenes_auxiliares_laboratorio').attr('checked', false);
        }   
        function cargarCitas() {
            /* DATATABLE */
            var tabla_citas = $('#miTabla_citas').DataTable({
                processing: true,
                serverSide: true,
                paging:true,
                pagingType: 'full_numbers',
                language: {
                    "url": "/js/Spanish.json",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    zeroRecords: "No se encontraron registros",
                },
                destroy: true,
                lengthMenu: [
                    [10, 50, 100, 200, 60000],
                    [10, 50, 100, 200, "Todos"]
                ],
                columns: [{
                        data: 'id',
                        orderable: false
                    },
                    {
                        data: 'establecimiento',
                        orderable: false
                    },
                    {
                        data: 'dia',
                        orderable: false
                    },
                    {
                        data: 'hora',
                        orderable: false
                    },
                    {
                        data: 'servicio',
                        orderable: false
                    },
                    {
                        data: 'medico_nombre',
                        orderable: false
                    },
                    {
                        data: 'estado_cita',
                        orderable: false
                    },
                    {
                        data: 'estado_cita',
                        orderable: false
                    },
                ],
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                fnServerData: function(sSource, aoData, fnCallback, oSettings) {

                    let ipressCita = $('#todosIpress_checkbox').is(":checked") ? 0 : $("#seleccionarIpress_opcion").val();
                    let fechaInicio = $("#fecha_ini_salud_consulta").val();
                    let fechaFinal = $("#fecha_fin_salud_consulta").val();
                    let dni_consulta = $("#dni_consulta_hd").val();
                    let id_servicio = $('#todosServicio_checkbox').is(":checked") ? 0 : $("#seleccionarServicio_opcion").val();
                    /* console.log(aoData) */
                    let iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                    let iCantMostrar = aoData[4].value;

                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": "{{ route('listarCitas') }}",
                        "data": {
                            NumeroPagina: iNroPagina,
                            NumeroRegistros: iCantMostrar,
                            fechaInic: fechaInicio,
                            fechaFin: fechaFinal,
                            dni: dni_consulta,
                            idServicio: id_servicio,
                            idIpress: ipressCita
                        },
                        "success": function(result) {
                            /* console.log(result); */
                            fnCallback(result);
                        },
                        "error": function(msg, textStatus, errorThrown) {
                        }
                    });
                },
                aoColumnDefs: [
                    {
                    "mRender": function(data, type, row) {
                        let dataMedico = '';
                        if(row.medico_paterno && row.medico_materno && row.medico_nombre){
                            dataMedico = `${row.medico_paterno} ${row.medico_materno} ${row.medico_nombre}`;
                        }else{
                            dataMedico ='-'
                        }
                        return dataMedico;
                    },
                    "aTargets": [5]
                },
                {
                    "mRender": function(data, type, row) {
                        let atendido = '-';
                        if (row.estado_cita == 'Programado') atendido = `<i class="fa fa-file-text-o fa-lg hoverable btn_cita" data-toggle="modal" data-target="#modal_cita" aria-hidden="false" data-id = "${row.id}"></i>`;
                        return atendido;
                    },
                    "aTargets": [7]
                }],
            });
        }
        function cargarAtencionesMedicas() {

            /* DATATABLE */
            var tabla_atenciones_medicas = $('#miTablaAtenciones').DataTable({
                processing: true,
                serverSide: true,
                paging:true,
                pagingType: 'full_numbers',
                language: {
                    "url": "/js/Spanish.json",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    zeroRecords: "No se encontraron registros",
                },
                destroy: true,
                lengthMenu: [
                    [10, 50, 100, 200, 60000],
                    [10, 50, 100, 200, "Todos"]
                ],
                columns: [{
                        data: 'id',
                        orderable: false
                    },
                    {
                        data: 'establecimiento',
                        orderable: false
                    },
                    {
                        data: 'fecha_atencion',
                        orderable: false
                    },
                    {
                        data: 'horario',
                        orderable: false
                    },
                    {
                        data: 'nro_historia',
                        orderable: false
                    },
                    {
                        data: 'servicio',
                        orderable: false
                    },
                    {
                        data: 'medico_nombre',
                        orderable: false
                    },
                    {
                        data: 'id_estado_reg',
                        orderable: false
                    },
                    {
                        data: 'id_estado_reg',
                        orderable: false
                    },
                ],
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                fnServerData: function(sSource, aoData, fnCallback, oSettings) {

                    let ipressCita = $('#todosIpress_checkbox').is(":checked") ? 0 : $("#seleccionarIpress_opcion").val();
                    let fechaInicio = $("#fecha_ini_salud_consulta").val();
                    let fechaFinal = $("#fecha_fin_salud_consulta").val();
                    let dni_consulta = $("#dni_consulta_hd").val();
                    let id_servicio = $('#todosServicio_checkbox').is(":checked") ? 0 : $("#seleccionarServicio_opcion").val();
                    let iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                    let iCantMostrar = aoData[4].value;

                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": "{{ route('listarAtencionesMedicas') }}",
                        "data": {
                            NumeroPagina: iNroPagina,
                            NumeroRegistros: iCantMostrar,
                            fechaInic: fechaInicio,
                            fechaFin: fechaFinal,
                            dni: dni_consulta,
                            idServicio: id_servicio,
                            idIpress: ipressCita
                        },
                        "success": function(result) {
                            fnCallback(result);
                        },
                        "error": function(msg, textStatus, errorThrown) {
                        }
                    });
                },
                aoColumnDefs: [
                    {
                    "mRender": function(data, type, row) {
                        let dataMedico = '';
                        if(row.medico_paterno && row.medico_materno && row.medico_nombre){
                            dataMedico = `${row.medico_paterno} ${row.medico_materno} ${row.medico_nombre}`;
                        }else{
                            dataMedico ='-'
                        }
                        return dataMedico;
                    },
                    "aTargets": [6]
                },
                {
                    "mRender": function(data, type, row) {
                        let id_estado_registro = row.id_estado_reg;
                        let estado = '';
                        switch(id_estado_registro){
                            case 0:
                                estado = 'ANULADO';
                                break;
                            case 1:
                                estado = 'ATENDIDO';
                                break;
                            case 2:
                                estado = 'MODIFICADO';
                                break;
                            default:
                                estado = '-';
                                break;
                        }
                        return estado;
                    },
                    "aTargets": [7]
                },
                {
                    "mRender": function(data, type, row) {
                        let id_estado_registro = row.id_estado_reg;
                        let btnEstado = '';
                        switch(id_estado_registro){
                            case 0:
                                btnEstado = '-';
                                break;
                            case 1:
                                btnEstado = `<i class="fa fa-file-text-o fa-lg hoverable btn_atenciones" data-toggle="modal" data-target="#modal_atencion" aria-hidden="false" data-id = "${row.id}"></i>`;
                                break;
                            case 2:
                                btnEstado = '-';
                                break;
                            default:
                                btnEstado = '-';
                                break;
                        }
                        return btnEstado;
                    },
                    "aTargets": [8]
                }],
            });
        }
        function cargarProcedimientos() {

            /* DATATABLE */
            var tabla_procedimientos = $('#miTablaProcedimientos').DataTable({
                processing: true,
                serverSide: true,
                paging:true,
                pagingType: 'full_numbers',
                language: {
                    "url": "/js/Spanish.json",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    zeroRecords: "No se encontraron registros",
                },
                destroy: true,
                lengthMenu: [
                    [10, 50, 100, 200, 60000],
                    [10, 50, 100, 200, "Todos"]
                ],
                columns: [{
                        data: 'id_prestacion',
                        orderable: false
                    },
                    {
                        data: 'establecimiento',
                        orderable: false
                    },
                    {
                        data: 'fecha_atencion',
                        orderable: false
                    },
                    {
                        data: 'hora_atencion',
                        orderable: false
                    },
                    {
                        data: 'tipo_atencion',
                        orderable: false
                    },
                    {
                        data: 'servicio',
                        orderable: false
                    },
                    {
                        data: 'id_prestacion_procedimiento',
                        orderable: false
                    },
                    {
                        data: 'medico_nombre',
                        orderable: false
                    },
                    {
                        data: 'codigo_procedimiento',
                        orderable: false
                    },
                    {
                        data: 'nombre_procedimiento',
                        orderable: false
                    }
                ],
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                fnServerData: function(sSource, aoData, fnCallback, oSettings) {

                    let ipressCita = $('#todosIpress_checkbox').is(":checked") ? 0 : $("#seleccionarIpress_opcion").val();
                    let fechaInicio = $("#fecha_ini_salud_consulta").val();
                    let fechaFinal = $("#fecha_fin_salud_consulta").val();
                    let dni_consulta = $("#dni_consulta").val();
                    let id_servicio = $('#todosServicio_checkbox').is(":checked") ? 0 : $("#seleccionarServicio_opcion").val();
                    let iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                    let iCantMostrar = aoData[4].value;

                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": "{{ route('listarProcedimientosConsultor') }}",
                        "data": {
                            NumeroPagina: iNroPagina,
                            NumeroRegistros: iCantMostrar,
                            fechaInic: fechaInicio,
                            fechaFin: fechaFinal,
                            dni: dni_consulta,
                            idServicio: id_servicio,
                            idIpress: ipressCita
                        },
                        "success": function(result) {
                            fnCallback(result);
                        },
                        "error": function(msg, textStatus, errorThrown) {
                        }
                    });
                },
                aoColumnDefs: [
                    {
                    "mRender": function(data, type, row) {
                        let datafecha = row.fecha_atencion;

                        let momentFecha = moment(datafecha, 'YYYY-MM-DD HH:mm:ss');
                        let fechaFormateada = momentFecha.format('DD/MM/YYYY');

                        return fechaFormateada;
                    },
                    "aTargets": [2]
                },
                {
                    "mRender": function(data, type, row) {
                        let dataMedico = '';
                        if(row.medico_paterno && row.medico_materno && row.medico_nombre){
                            dataMedico = `${row.medico_paterno} ${row.medico_materno} ${row.medico_nombre}`;
                        }else{
                            dataMedico ='-'
                        }
                        return dataMedico;
                    },
                    "aTargets": [7]
                }],
            });
        }
        function cargarRecetas() {

            /* DATATABLE */
            var tabla_recetas = $('#miTablaRecetas').DataTable({
                processing: true,
                serverSide: true,
                pagingType: 'full_numbers',
                ordering: true,
                paging:true,
                language: {
                    "url": "/js/Spanish.json",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    zeroRecords: "No se encontraron registros",
                },
                destroy: true,
                lengthMenu: [
                    [10, 50, 100, 200, 60000],
                    [10, 50, 100, 200, "Todos"]
                ],
                columns: [{
                        data: 'id_receta_vale',
                        orderable: false
                    },
                    {
                        data: 'establecimiento',
                        orderable: false
                    },
                    {
                        data: 'fecha_registro',
                        orderable: false,
                        type: 'dd/mm/yyyy'
                    },
                    {
                        data: 'fecha_dispensacion',
                        orderable: false
                    },
                    {
                        data: 'nro_receta',
                        orderable: false
                    },
                    {
                        data: 'tipo_atencion',
                        orderable: false
                    },
                    {
                        data: 'nom_consultorio',
                        orderable: false
                    },
                    {
                        data: 'nom_medico',
                        orderable: false
                    },
                    {
                        data: 'nro_estado',
                        orderable: false
                    },
                    {
                        data: 'nro_estado',
                        orderable: false
                    },
                ],
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                "fnDrawCallback": function(json) {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                fnServerData: function(sSource, aoData, fnCallback, oSettings) {

                    let ipressCita = $('#todosIpress_checkbox').is(":checked") ? 0 : $("#seleccionarIpress_opcion").val();
                    let fechaInicio = $("#fecha_ini_salud_consulta").val();
                    let fechaFinal = $("#fecha_fin_salud_consulta").val();
                    let dni_consulta = $("#dni_consulta_hd").val();
                    let id_servicio = $('#todosServicio_checkbox').is(":checked") ? 0 : $("#seleccionarServicio_opcion").val();
                    let iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                    let iCantMostrar = aoData[4].value;

                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": "{{ route('listarRecetas') }}",
                        "data": {
                            NumeroPagina: iNroPagina,
                            NumeroRegistros: iCantMostrar,
                            fechaInic: fechaInicio,
                            fechaFin: fechaFinal,
                            dni: dni_consulta,
                            idServicio: id_servicio,
                            idIpress: ipressCita
                        },
                        "success": function(result) {
                            fnCallback(result);
                        },
                        "error": function(msg, textStatus, errorThrown) {
                        }
                    });
                },
                aoColumnDefs: [
                {
                    "mRender": function(data, type, row) {
                        let dataMedico = '';
                        if(row.apepat_medico && row.apemat_medico && row.nom_medico){
                            dataMedico = `${row.apepat_medico} ${row.apemat_medico} ${row.nom_medico}`;
                        }else{
                            dataMedico ='-'
                        }
                        return dataMedico;
                    },
                    "aTargets": [7]
                },
                {
                    "mRender": function(data, type, row) {
                        let nro_estado = row.nro_estado;
                        let estado = '';
                        switch(nro_estado){
                            case 0:
                                estado = 'ANULADO';
                                break;
                            case 1:
                                estado = 'DISPENSADO';
                                break;
                            case 2:
                                estado = 'PRESCRITO';
                                break;
                            default:
                                estado = '-';
                                break;
                        }
                        return estado;
                    },
                    "aTargets": [8]
                },
                {
                    "mRender": function(data, type, row) {
                        let nro_estado = row.nro_estado;
                        let btnEstado = '';
                        switch(nro_estado){
                            case 0:
                                btnEstado = '-';
                                break;
                            case 1:
                                btnEstado = `<a href="/imprimir_receta_vale/${row.id_receta_vale}/${row.id_farmacia}" target= "_blank"><i class = "fa fa-file-text-o fa-lg hoverable btn_recetas" data-toggle="modal" data-target="#modal_recetas" aria-hidden="false" data-id = "${row.id_receta_vale}" data-estado="${row.nro_estado}"></i></a>`;
                                break;
                            case 2:
                                btnEstado = `<i class = "fa fa-file-text-o fa-lg hoverable btn_recetas" data-toggle="modal" data-target="#modal_recetas" aria-hidden="false" data-id = "${row.id_receta_vale}" data-estado = "${row.nro_estado}"></i>`;
                                break;
                            default:
                                btnEstado = '-';
                                break;
                        }
                        return btnEstado;
                    },
                    "aTargets": [9]
                }],
                order: [[2, 'desc']]
            });
        }
        function cargarExamenes(){
            var tabla_examenes = $('#miTablaExamenes').DataTable({
                processing: true,
                serverSide: true,
                pagingType: 'full_numbers',
                ordering: true,
                paging:true,
                language: {
                    "url": "/js/Spanish.json",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    zeroRecords: "No se encontraron registros",
                },
                destroy: true,
                lengthMenu: [
                    [10, 50, 100, 200, 60000],
                    [10, 50, 100, 200, "Todos"]
                ],
                columns: [{
                        data: 'nro_prestacion',
                        orderable: false
                    },
                    {
                        data: 'establecimiento',
                        orderable: false
                    },
                    {
                        data: 'fecha_orden',
                        orderable: false,
                    },
                    {
                        data: 'hora_orden',
                        orderable: false
                    },
                    {
                        data: 'nro_exam_lab',
                        orderable: false
                    },
                    {
                        data: 'tipo_atencion',
                        orderable: false
                    },
                    {
                        data: 'consultorio',
                        orderable: false
                    },
                    {
                        data: 'nombre_medico',
                        orderable: false
                    },
                    {
                        data: 'estado',
                        orderable: false
                    }
                ],
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                "fnDrawCallback": function(json) {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                fnServerData: function(sSource, aoData, fnCallback, oSettings) {

                    let ipressCita = $('#todosIpress_checkbox').is(":checked") ? 0 : $("#seleccionarIpress_opcion").val();
                    let fechaInicio = $("#fecha_ini_salud_consulta").val();
                    let fechaFinal = $("#fecha_fin_salud_consulta").val();
                    let dni_consulta = $("#dni_consulta_hd").val();
                    let id_servicio = $('#todosServicio_checkbox').is(":checked") ? 0 : $("#seleccionarServicio_opcion").val();
                    let iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                    let iCantMostrar = aoData[4].value;

                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": "{{ route('listarExamenesAuxiliaresConsultor') }}",
                        "data": {
                            NumeroPagina: iNroPagina,
                            NumeroRegistros: iCantMostrar,
                            fechaInic: fechaInicio,
                            fechaFin: fechaFinal,
                            dni: dni_consulta,
                            idServicio: id_servicio,
                            idIpress: ipressCita
                        },
                        "success": function(result) {
                            console.log(result);
                            fnCallback(result);
                        },
                        "error": function(msg, textStatus, errorThrown) {
                        }
                    });
                },
                aoColumnDefs: [
                    {
                    "mRender": function(data, type, row) {
                        let datafecha = row.fecha_orden;

                        let momentFecha = moment(datafecha, 'YYYY-MM-DD');
                        let fechaFormateada = momentFecha.format('DD/MM/YYYY');

                        return fechaFormateada;
                    },
                    "aTargets": [2]
                },
                {
                    "mRender": function(data, type, row) {
                        let horafecha = row.hora_orden;

                        let momentHora = moment(horafecha, '"HH:mm:ss"');
                        let fechaFormateada = momentHora.format('HH:mm A');

                        return fechaFormateada;
                    },
                    "aTargets": [3]
                },
                {
                    "mRender": function(data, type, row) {
                        let dataMedico = '';
                        if(row.paterno_medico && row.materno_medico && row.nombre_medico){
                            dataMedico = `${row.paterno_medico} ${row.materno_medico} ${row.nombre_medico}`;
                        }else{
                            dataMedico ='-'
                        }
                        return dataMedico;
                    },
                    "aTargets": [7]
                }
                ],
                order: [[2, 'desc']]
            });
        }

        function cargarExamenesLaboratorio() {
            let nro_doc_paciente = $("#dni_consulta_hd").val();
            $.ajax({
                url: "{{ url('/') }}/modal_historial_laboratorio2/" + nro_doc_paciente,
                type: "GET",
                success: function (result) {  
                    $("#tabla_examenes_auxiliares_laboratorio").html(result);
                    $('#tablemodalm1').DataTable({
                        "sPaginationType": "full_numbers",
                        "language": {"url": "/js/Spanish.json"},
                    });
                }
            });
        }

        $('#radio_citas').click(function(e) {
            mostrarTabla(1); //
            cargarCitas(); //
        });
        $('#radio_atenciones_medicas').click(function(e) {
            mostrarTabla(2); //
            cargarAtencionesMedicas();
        });
        $('#radio_procedimientos').click(function(e) {
            mostrarTabla(3); 
            cargarProcedimientos();  // EN PROCESO
        });
        $('#radio_recetas').click(function(e) {
            mostrarTabla(4); //
            cargarRecetas();
        });
        $('#radio_examenes_auxiliares').click(function(e) {
            mostrarTabla(5); //
            cargarExamenes();
        });
        $('#radio_examenes_auxiliares_laboratorio').click(function(e) {
            mostrarTabla(6);
            cargarExamenesLaboratorio();
        });
        $(document).on('click', '.btn_cita', function() {
            const id = $(this).data('id'); // Obtencion del ID desde el atributo data-id
            showModalCita(id);
        });
        $(document).on('click', '.btn_atenciones', function() {
            let id_atencion = $(this).data('id');
            $('#id_atencion_for_modal_hd').val(id_atencion); // Enviar la data del atributo data-id al hidden

            limpiarMdlCredenciales();
        });
        $(document).on('click', '#validar_reauth', function() {

            let id_atencion = $('#id_atencion_for_modal_hd').val();
            let user = $('#username_atenciones').val().trim();
            let pass = $('#password_atenciones').val();
            let motivo = $('#motivo_consulta_atenciones').val().trim();

            loadReauthAtencion(id_atencion, user, pass, motivo);
        });
        $(document).on('click', '.btn_recetas', function() {
            let id_receta = $(this).data('id');
            let is_prescrita = $(this).data('estado');
            if(is_prescrita != 1){
            Swal.fire({
                html: '<h5>Cargando receta...</h5>',
                showLoaderOnConfirm: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                type: "get",
                url: `getUrlPdfRecetaById/${id_receta}`,
                dataType: "json",
                success: function (res) {
                    let urlApp = window.location.origin;
                    let urlPdf = res.message.url_receta;
                    let flag = res.message.flag_receta;
                    url_redirect = `${urlApp}/${flag}/${urlPdf}`;
                    window.open(url_redirect, '_blank');
                    Swal.close();
                }, 
                error: function (xhr, status, err){
                    const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr
                        .responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                    const messageErrorTitle = "ERROR"
                    Swal.fire({
                        icon: 'error',
                        title: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'></span>${messageErrorTitle.toUpperCase()}</div>`,
                        html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                },complete: function() {
                    Swal.hideLoading();
                }
                
            });
            }
        });
    });
    /* FIN DOCUMENT READY */
</script>
<?php 
	$vistas_consultor = Auth::user()->vistas_consultor;
	$json_vistas_consultor = json_decode($vistas_consultor);

    $access_citas = $json_vistas_consultor->flag_citas_consultor;
    $access_atenciones = $json_vistas_consultor->flag_atenciones_consultor;
    $access_procedimientos = $json_vistas_consultor->flag_procedimientos_consultor;
    $access_recetas = $json_vistas_consultor->flag_recetas_consultor;
    $access_examenes = $json_vistas_consultor->flag_examenes_consultor;
    if (!empty($json_vistas_consultor->flag_examenes_lab_consultor)) {
        $access_examen_lab = $json_vistas_consultor->flag_examenes_lab_consultor;
    } else {
        $access_examen_lab = 0;
    }

?> 
@section('content')
    <div class="container-fluid" style="padding: 0px 30px">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <h3 class="panel-title" style="padding-top: 5px;">REGISTRO DE SALUD DEL PACIENTE </h3>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    {{-- inpt --}}
                    <input type="hidden" name="dni_consulta_hd" id="dni_consulta_hd">
                    {{-- <input type="hidden" name="paciente_id" id="paciente_id"> --}}
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <label>Tipo Documento:</label>
                                    <select class="form-control input-sm" name="campo_de_busqueda"
                                        id="campo_de_busqueda">
                                        <option value="1">DNI</option>
                                        <option value="2">CARNET DE EXTRANJERIA</option>
                                        <option value="3">PASAPORTE</option>
                                        <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <label id="datos_consulta" name="dni_consulta">N° Documento:</label>

                                    <div id="campo_buscar_input" {{-- name="campo_buscar_input" --}}>
                                        <input type="text" name="valor_de_busqueda" id="dni_consulta"
                                            style="text-transform: uppercase;" class="form-control input-sm"
                                            autocomplete="off" value="" maxlength="8">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <label class="form-control-sm">Fecha Desde:</label>
                                    <input class="form-control input-sm" type="text"
                                        name="fecha_ini_salud_consulta" id="fecha_ini_salud_consulta"
                                        placeholder="Fecha Desde"
                                        value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" maxlength="10">
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <label class="form-control-sm">Fecha Hasta:</label>
                                    <input class="form-control input-sm" type="text"
                                        name="fecha_fin_salud_consulta" id="fecha_fin_salud_consulta"
                                        placeholder="Fecha Hasta"
                                        value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" maxlength="10">
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <label style="visibility: hidden;">CONSULTAR</label> <br>
                                    <a class="btn btn-success btn-sm btn-block" id="realizarConsulta"
                                        name="realizarConsulta">CONSULTAR</a>
                                </div>
                            </div>    
                        </div>
                    </div>

                        <!------------------  INICIO DATOS IPRESS - SERVICIO DEL PACIENTE      ------------------>
                    <div class="datosPaciente" id = "datosPaciente" style="display: none;">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h2 class="panel-title text-center" id="nombresPaciente" style="font-size:30px"> Nombres y Apellidos del Paciente</h2>
                            </div>
                            <div class="panel-body">
                                <div class="form-horizontal">
                                    <div class="row" style="">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-md-1 control-label">IPRESS</label>
                                            <div class="col-sm-6 col-md-4">
                                                <input type="hidden" value={{ Auth::user()->id_farmacia }} id="id_farmacia">
                                                </input>
                                                <select class="form-control input-sm" name="seleccionarIpress_opcion"
                                                    id="seleccionarIpress_opcion" {{-- onchange="getServicioByIpress() --}}>

                                                    <option value={{ Auth::user()->id_establecimiento }} id="option_ipress">
                                                        <?php
                                                        $model_farma = new \App\Models\Farmacia();
                                                        $ipress = $model_farma->GetIpress(Auth::user()->id_farmacia);
                                                        if ($ipress) {
                                                            echo $ipress->nombre;
                                                        }
                                                        ?>
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-check col-sm-2 col-md-1">
                                                <input class="form-check-input" type="checkbox" value="" id="todosIpress_checkbox">
                                                <label class="form-check-label" for="todosIpress_checkbox"> TODOS
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-md-1 control-label">Servicio</label>
                                            <div class="col-sm-6 col-md-4">
                                                <select class="form-control input" name="seleccionarServicio_opcion" id="seleccionarServicio_opcion">
                                                    <option value="0">--SELECCIONAR--</option>
                                                </select>
                                            </div>
                                            <div class="form-check col-sm-2 col-md-1">
                                                <input class="form-check-input" type="checkbox" value="" id="todosServicio_checkbox">
                                                <label class="form-check-label" for="todosServicio_checkbox"> TODOS
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="">
                                        {{-- CITAS--}}
                                        @if($access_citas == 1)
                                        <div class="col-sm-4 col-md-2">
                                            <label>
                                                <input type="radio" id="radio_citas" name="opciones" value="1">
                                                Citas
                                            </label>
                                        </div>
                                        @endif
                                        {{-- ATENCIONES MEDICAS--}}
                                        @if($access_atenciones == 1)
                                        <div class="col-sm-4 col-md-2">
                                            <label>
                                                <input type="radio" id="radio_atenciones_medicas" name="opciones"
                                                    value="2">
                                                Atenciones médicas
                                            </label>
                                        </div>
                                        @endif
                                        {{-- PROCEDIMIENTOS --}}
                                        @if($access_procedimientos == 1)
                                        <div class="col-sm-4 col-md-2">
                                            <label>
                                                <input type="radio" id="radio_procedimientos" name="opciones" value="3">
                                                Procedimientos
                                            </label>
                                        </div>
                                        @endif
                                        {{-- RECETAS --}}
                                        @if($access_recetas == 1)
                                        <div class="col-sm-4 col-md-2">
                                            <label>
                                                <input type="radio" id="radio_recetas" name="opciones" value="4">
                                                Recetas
                                            </label>
                                        </div>
                                        @endif
                                        {{-- EXAMENES --}}
                                        @if($access_examenes == 1)
                                        <div class="col-sm-4 col-md-2">
                                            <label>
                                                <input type="radio" id="radio_examenes_auxiliares" name="opciones"
                                                    value="5">
                                                Examenes auxiliares
                                            </label>
                                        </div>
                                        @endif
                                        @if ($access_examen_lab == 1)
                                        <div class="col-sm-4 col-md-2">
                                            <label>
                                                <input type="radio" id="radio_examenes_auxiliares_laboratorio" name="opciones" value="6"> Ordenes Laboratorio
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------      FIN DATOS IPRESS - SERVICIO DEL PACIENTE      -------------------------->
                    {{-- <!------------------ INICIO TABLA CITAS   ------------------> --}}
                    @if($access_citas == 1)
                        <div id = "tabla_citas" style="margin: 0 auto; display: none; width: 95%">
                            <div class="row">
                                <div class="col-12">
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover" id="miTabla_citas"
                                            style="font-size: 15px; text-align: center; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">N°
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 20%;">
                                                        IPRESS</th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">
                                                        Fecha Cita
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">
                                                        Hora Cita
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">
                                                        Servicio
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">
                                                        Medico</th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">
                                                        ESTADO</th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">Ver
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="lista_tabla_citas">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <!------------------ FIN TABLA CITAS   ------------------> --}}

                    {{-- <!------------------    INCIO TABLA ATENCIONES MEDICAS  ------------------> --}}
                    @if($access_atenciones == 1)
                        <div id = "tabla_atenciones_medicas" style="display: none;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-body">
                                        <table class="table table-bordered dt-responsive " id="miTablaAtenciones"
                                            style=" 
                                    overflow: auto;width:100%; font-size: 15px;  text-align: center ">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">N°</th>
                                                    <th class="text-center" style="font-size: 12px; width: 20%;">IPRESS
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">Fecha
                                                        Atención</th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">Hora
                                                        Atención</th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">N°
                                                        Historia</th>
                                                    <th class="text-center" style="font-size: 12px; width: 20%;">Servicio
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">Medico
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">
                                                        Estado
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">
                                                        Ver
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="lista_atenciones_medicas">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <!------------------   FIN TABLA ATENCIONES MEDICAS    ------------------> --}}

                    {{-- <!------------------    INCIO TABLA PROCEDIMIENTOS  ------------------> --}}
                    @if($access_procedimientos == 1)
                        <div id = "tabla_procedimientos" style="display: none;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover" id="miTablaProcedimientos"
                                            style="font-size: 15px; text-align: center; width:100%; ">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">N°</th>
                                                    <th class="text-center" style="font-size: 12px; width: 20%;">IPRESS
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">Fecha
                                                        Atención</th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">Hora
                                                        Atenciónn</th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">Tipo
                                                        Atención
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">Servicio
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">N°
                                                        Prestación
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">Médico
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">
                                                        Código de Procedimiento
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 18%;">
                                                        Des. Procedimiento
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="lista_procedimientos">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <!------------------   FIN TABLA PROCEDIMIENTOS    ------------------> --}}

                    {{-- <!------------------    INCIO TABLA RECETAS  ------------------> --}}
                    @if($access_recetas == 1)
                        <div id = "tabla_recetas" style="display: none;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-body">
                                        <table class="table table-bordered dt-responsive table-hover" id="miTablaRecetas"
                                            style="
                                    overflow: auto; width:100%; font-size: 15px;  text-align: center ">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">N°</th>
                                                    <th class="text-center" style="font-size: 12px; width: 20%;">IPRESS
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">Fecha
                                                        Registro</th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">Fecha
                                                        dispensación</th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">N° Receta
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">Tipo
                                                        Atención</th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">Servicio
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 20%;">Médico
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">
                                                        Estado
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 5%;">
                                                        Ver
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="lista_tabla_recetas">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <!------------------   FIN TABLA RECETAS    ------------------> --}}

                    {{-- <!------------------    INCIO TABLA EXAMENES AUXILIARES  ------------------> --}}
                    @if($access_examenes == 1)
                        <div id = "tabla_examenes_auxiliares" style="display: none;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-body">
                                        <table class="table table-bordered dt-responsive " id="miTablaExamenes"
                                            style="
                                    overflow: auto; width:100%; font-size: 15px;  text-align: center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="font-size: 12px; width: 3%;">N°</th>
                                                    <th class="text-center" style="font-size: 12px; width: 20%;">IPRESS
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">Fecha
                                                        Orden</th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">Hora
                                                        Orden</th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">N° Examen
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">Tipo
                                                        Atención</th>
                                                    <th class="text-center" style="font-size: 12px; width: 10%;">Servicio
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 15%;">Médico
                                                    </th>
                                                    <th class="text-center" style="font-size: 12px; width: 3%;">
                                                        Estado
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="lista_tabla_examenes_auxiliares">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <!------------------   FIN TABLA EXAMENES AUXILIARES    ------------------> --}}

                    {{-- <!------------------    INCIO TABLA EXAMENES AUXILIARES LABORATORIO ------------------> --}}
                    @if($access_examen_lab == 1)
                        <div id="tabla_examenes_auxiliares_laboratorio" style="display: none;">
                        </div>
                    @endif
                    {{-- <!------------------   FIN TABLA EXAMENES AUXILIARES LABORATORIO   ------------------> --}}

                </div>
            </div>
            {{-- <!------------------   MODAL CITA  ------------------> --}}
            @if($access_citas == 1)
                <div class="modal fade" id="modal_cita" role="dialog" data-backdrop="static">
                    <div class="modal-dialog" style="width: 900px;">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h2 class="panel-title">CITAS</h2>                    
                                </div> 
                                <div class="panel-body">
                                    {{-- row 1 --}}
                                    <div class="row pad-top-10px">
                                        <div class="md_fecha_cita col-lg-3">
                                            <label for="fecha_cita">FECHA CITA</label>
                                            <input id = "fecha_cita" class="form-control input-success" disabled></input>
                                        </div>
                                        <div class="md_hora_cita col-lg-3">
                                            <label for="hora_cita">HORA CITA</label>
                                            <input id = "hora_cita" class="form-control input-success" disabled></input>
                                        </div>
                                        <div class="md_servicio_cita col-lg-6">
                                            <label for="servicio_cita">SERVICIO</label>
                                            <input id = "servicio_cita" class="form-control input-success" disabled></input>
                                        </div>
                                    </div>
                                    {{-- row 2 --}}
                                    <div class="row pad-top-10px">
                                        <div class="md_consultorio_cita col-lg-6">
                                            <label for="consultorio_cita">CONSULTORIO</label>
                                            <input id = "consultorio_cita" class="form-control input-success" disabled></input>
                                        </div>
                                        <div class="md_medico_cita col-lg-4">
                                            <label for="medicoa_cita">MEDICO</label>
                                            <input id = "medico_cita" class="form-control input-success" disabled></input>
                                        </div>
                                        <div class="md_tipo_cita col-lg-2">
                                            <label for="tipo_cita">TIPO CITA</label>
                                            <input id = "tipo_cita" class="form-control input-success" disabled></input>
                                        </div>
                                    </div>
                                    {{-- row 3 --}}
                                    <div class="row pad-top-10px">
                                        <div class="md_dni_cita col-lg-4">
                                            <label for="dni_cita">DNI</label>
                                            <input id = "dni_cita" class="form-control input-success" disabled></input>
                                        </div>
                                        
                                        <div class="md_nombres col-lg-8">
                                            <label for="nombres_cita">NOMBRE Y APELLIDOS</label>
                                            <input id = "nombres_cita" class="form-control input-success" disabled></input>
                                        </div>
                                    </div>
                                    {{-- row 4 --}}
                                    <div class="row pad-top-10px">
                                        <div class="md_parentesco_cita col-lg-3">
                                            <label for="parentesco_cita">PARENTESCO</label>
                                            <input id = "parentesco_cita" class="form-control input-success" disabled></input>
                                        </div>
                                        
                                        <div class="md_grado_cita col-lg-3">
                                            <label for="grado_cita">GRADO</label>
                                            <input id = "grado_cita" class="form-control input-success" disabled></input>
                                        </div>
                                        <div class="md_correo col-lg-6">
                                            <label for="correo_cita">CORREO</label>
                                            <input id = "correos_cita" class="form-control input-success" disabled></input>
                                        </div>
                                    </div>
                                    {{-- row 5 --}}
                                    <div class="row pad-top-10px">
                                        <div class="md_establecimiento_cita col-lg-12">
                                            <label for="establecimiento_cita">ESTABLECIMIENTO</label>
                                            <input id = "establecimiento_cita" class="form-control input-success" disabled></input>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer" style="text-align: right">
                                    <button type="button" class="btn btn-danger pad-10px" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        {{-- </div> --}}
                    </div>
                </div>
            @endif
            {{-- MODAL BOTON LOGIN ATENCIONES --}}
            @if($access_atenciones == 1)
                <div class="modal fade" id="modal_atencion" role="dialog" data-backdrop="static">
                    <div class="modal-dialog" style="width: 600px;">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Confirmar Sesión</h2>                    
                                </div> 
                                <div class="panel-body">
                                    {{-- row 1 --}}
                                    <div class="row pad-top-10px">
                                        <div class="form-floating col-lg-6">
                                            <label for="username_atenciones" for="email">E-mail</label>                                          
                                            <input id = "username_atenciones" class="form-control input-success" placeholder="correo@example.com" type="email" name="email" autocomplete="off" required></input>
                                        </div>
                                        <div class="form-floating col-lg-6">
                                            <div class="input_and_button">
                                                <label for="password_atenciones" for="password">Contraseña</label> 
                                                <input id = "password_atenciones" class="form-control input-pass" placeholder="Password" type="password" name="password" autocomplete="new-password" required></input>
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="row pad-top-10px">
                                        <div class="form-floating col-lg-12">
                                            <label for="motivo_consulta_atenciones" for="motivo_consulta_atenciones">Motivo:</label>                                          
                                            <input id = "motivo_consulta_atenciones" class="form-control input-success" placeholder="Motivo o razón por la cual solicita acceso al archivo ..." type="text" name="motivo_consulta_atenciones" autocomplete="off" required></input>
                                        </div>
                                    </div>
                                    <div class="row pad-top-10px">
                                        <div class="col-lg-6">
                                            <button type="button" class="btn btn-success pad-10px" id = "validar_reauth">Ver</button>
                                            <input type="hidden" id = "id_atencion_for_modal_hd">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer" style="text-align: right">
                                    <button type="button" class="btn btn-danger pad-10px" data-dismiss="modal" id="cerrarModalAtencion">Cerrar</button>
                                </div>
                            </div>
                        {{-- </div> --}}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
