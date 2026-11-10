<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<script src="https://unpkg.com/pdf-lib/dist/pdf-lib.min.js"></script>

<style>
    .table-sortable tbody tr {
        cursor: move;
    }
    /*
#global {        
    width: 95%;        
    margin: 15px 15px 15px 15px;     
    height: 380px !important;        
    border: 1px solid #ddd;
    overflow-y: scroll !important;
}
    */
    #global {
        height: auto;
        /* width: auto; */
        width: 100%;
        border: 1px solid #ddd;
        /* margin:15px */
            /* background: #f1f1f1;*/
            /*overflow-y: scroll !important;*/
    }

    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .row{
        margin-top:10px;
        padding: 0 10px;
    }
    .clickable{
        cursor: pointer;   
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;        
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .panel-body{
        display: block;
    }

    .dataTables_filter {
        display: none;
    }
    
    .help-block {
        display: block;
        margin-top: 1px;
        margin-bottom: 1px;
        color: #737373;
    }
    
    .modal-dialog{
        width: 70% !important;
    }
    
    .titulo-padding {
            padding: 1rem 0 0 0;
        }

        .titulo-padding p {
            color: rgb(255, 255, 255);
            margin: 0;
            font-size: 2.5rem;
        }

        .descripcion-alert {
            font-size: 1.5rem;
            padding: 0 1rem;
            margin: 0;
        }
        
        /*************Styles de sweetAlert2*************/

        .swal2-popup {
            width: 1200px !important;
        }

        .swal2-popup,
        .swal2-header {
            padding: 0 !important;
        }

        .swal2-header-generar {
            background: #3c763d;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 1rem 0 !important;
        }

        .descripcion-alert span {
            font-weight: 700;
        }

        .swal2-modal {
            min-height: auto;
        }

        .swal2-actions {
            margin-bottom: 2rem;
        }

        .custom-swal-button-cerrar {
            background-color: #3c763d !important;
            color: white;
        }

        .custom-swal-button-cerrar:hover {
            -webkit-box-shadow: 0px 0px 10px -1px rgba(0,0,0,0.75);
            -moz-box-shadow: 0px 0px 10px -1px rgba(0,0,0,0.75);
            box-shadow: 0px 0px 10px -1px rgba(0,0,0,0.75);
        }
        /*********************************************/
        /***************Efecto blur para Alert********/
        #blur-container {
            position: relative;
        }

        .blur-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Color de fondo semitransparente */
            backdrop-filter: blur(5px); /* Efecto de desenfoque */
            z-index: 999; /* Asegúrate de que el desenfoque esté sobre otros elementos */
        }

        .custom-header-class {
            background-color: #3c763d;
            color: white;
        }
        /*********************************************/
        /*===============Estilos de botón Cambiar estado de Orden INICIO======================*/
        .custom-header-cambiar-estado {
            background-color: #d9534f !important;
            border-color: #d43f3a !important;
        }
        /*===============Estilos de botón Cambiar estado de Orden FIN======================*/

        .select2-container {
        width: 100% !important; 
        }
        .swal2-popup{
          padding: 10px 15px !important;
        }
</style>

<script>

    //--------------------------------------------------------------------------
    $(document).ready(function () {

    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
    //$('#fechaDesde').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true/*"timeOnly": true*/});
    //$('#fechaHasta').appendDtpicker({"autodateOnStart": false,"minuteInterval": 5,"amPmInTimeList": true});

    $('#fechaDesde').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
    });
    $('#fechaHasta').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
            //"dateFormat": "YYYY-MM-DD hh:mm",
    });
    $('#fechaDesde').click(function () {
    var fechaDesdeIni = $('#fechaDesde').val();
    $('#fechaDesde').change(function () {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    f1 = fechaDesde.split("-");
    f2 = fechaHasta.split("-");
    f3 = new Date();
    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    if (f1 > f2) {
    bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
    $('#fechaDesde').val(fechaDesdeIni);
    return false;
    }
    });
    });
    $('#fechaHasta').click(function () {
    var fechaHastaIni = $('#fechaHasta').val();
    $('#fechaHasta').change(function () {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    f1 = fechaDesde.split("-");
    f2 = fechaHasta.split("-");
    f3 = new Date();
    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

    if (f2 < f1) {
    bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }
    /*
    if (f2 > f3) {
    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
    $('#fechaHasta').val(fechaHastaIni);
    return false;
    }
    */
    });
    });
    $('#fechaHasta').trigger('change');
    $('#fechaDesde').trigger('change');
    var activeSystemClass = $('.list-group-item.active');

        $("#tbl_ordenes_toma_muestra").DataTable();
    });
    $(document).ready(function () {
    $('#example').DataTable({
      ordering: false,
    "language": {
    "emptyTable": "No se encontraron resultados"
    }
    });
    $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable({ordering: false});
    dataTable.fnFilter(this.value);
    });

    function soloNumeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    });

    function mofificarPruebasEnviadasLNS(id_laboratorio, id_tipo_atencion){
    
        bootbox.confirm({ 
        size: "small",
        //size: "short",
        message: "&iquest;Desea modificar la toma de muestra de las pruebas de Laboratorio?", 
        callback: function(result){
            if (result==true) {
                location.href="formulario_edicion_atencion_laboratorio_lns/" + id_laboratorio + "/" + id_tipo_atencion;
            }
        }
        });
        
    }

    $(document).ready(function () {
        let tipo_perfil = $('#tipo_perfil').val();
        if (tipo_perfil == 1) { //Fiscalizador responsable de laboratorio
            $("#tipo_atencion").change(function (e) { 
                e.preventDefault();
                let id_establecimiento = $('#id_establecimiento').val();
                let id_tipo_atencione = $(this).val();
                if (id_tipo_atencione==2) {
                    
                    $('#id_prioridad').html("");
                    $.ajax({
                    url: '/laboratorioObtenerTopicos/' + id_establecimiento,
                    success: function(result) {
                        var newOption = "";
                        newOption = "<option value='0'>--Seleccionar--</option>";
                        $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id_servicio + "'>" + oo.descripcion + "</option>";
                        });
                        $('#id_servicio').html(newOption);
                        $("#id_servicio").select2({
                        max_selected_options: 4
                        });
                    }
                    });
                }else if(id_tipo_atencione == 1 || id_tipo_atencione == 3){
                  $.ajax({
                    url: '/obtener_consultorio_tipo_atencion/' + id_establecimiento + '/' + id_tipo_atencione,
                    success: function(result) {
                        var newOption = "";
                        newOption = "<option value='0'>--Seleccionar--</option>";
                        $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                        });
                        $('#id_servicio').html(newOption);
                        $("#id_servicio").select2({
                        max_selected_options: 4
                        });
                    }
                    });
                }
                else {
                    $('#id_servicio').html("");
                }


                if(id_tipo_atencione == 1){
                  $(".fecha-inicio").html("Fecha cita Inicio");
                    $(".fecha-fin").html("Fecha cita hasta");
                }else{
                  $(".fecha-inicio").html("Fecha de atención Inicio");
                  $(".fecha-fin").html("Fecha de atención hasta");
                }
            });
        }
        if (tipo_perfil == '0') { //Tecnico de laboratorio por farmacia
            let tipo_atencion = $("#tipo_atencion").val();
            if (tipo_atencion == 1) {
                let id_establecimiento = $('#id_establecimiento').val();
                let id_tipo_atencione = tipo_atencion;
                console.log(id_tipo_atencione);
                if (id_tipo_atencione == "1") {
                    $('#id_prioridad').html("");
                    $.ajax({
                    url: '/obtener_consultorio_tipo_atencion/' + id_establecimiento + '/' + id_tipo_atencione,
                    success: function(result) {
                        var newOption = "";
                        newOption = "<option value='0'>--Seleccionar--</option>";
                        $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                        });
                        $('#id_servicio').html(newOption);
                        $("#id_servicio").select2({
                        max_selected_options: 4
                        });
                    }
                    });
                } else {
                    $('#id_servicio').html("");
                }
            }
        }
    });
    
    function detalleAtencionLaboratorio(id_tipo_atencion,id_lab, nro_presta){
    $('#nropresmodal').text(nro_presta);
    $.ajax({
    url: '/ver_atencion_laboratorio/'+ id_tipo_atencion + '/' + id_lab,
            async: false,
            success: function(result){
            $("#muestra-det-laboratorio").html(result);
            },
            complete: function () {
            $("#myModalDetRV").modal();
            }
    });
    }

    //Contenedor Prioridades para emergencia
    $(document).ready(function () {
        let tipo_perfil = $('#tipo_perfil').val();
        if (tipo_perfil == 1) { //Fiscalizador responsable de laboratorio
            $("#tipo_atencion").change(function (e) { 
                e.preventDefault();
                const id_tipo_atencion = parseInt($(this).val());
                $("#divPrioridad").hide();
                $("#divIndicacionHoraTomaMuestra").hide();
                $("#divUbicacionHospital").hide();

                if (id_tipo_atencion == 2) { // Emergencia
                    $.ajax({
                        url: "obtener_prioridades_emergencia",
                        success: function (response) {
                            var newOption = "";
                            newOption = "<option value='0'>--Seleccionar--</option>";
                            $(response).each(function(ii, oo) {
                                newOption += "<option value='" + oo.id + "'>" + oo.descripcion + "</option>";
                            });
                            $('#id_prioridad').html(newOption);
                            $("#id_prioridad").select2({
                                max_selected_options: 4
                            });
                        }
                    });
                    $("#divPrioridad").show();

                } else if (id_tipo_atencion == 3) { // Hospitalizacion
                    $("#divIndicacionHoraTomaMuestra").show();
                    $("#divUbicacionHospital").show();
                }
            });
        }
        /*if (tipo_perfil == '0') { //Tecnico de laboratorio por farmacia
            let tipo_atencion = $("#tipo_atencion").val();
            if (tipo_atencion == 2) {
                $.ajax({
                    url: "obtener_prioridades_emergencia",
                    success: function (response) {
                        var newOption = "";
                        newOption = "<option value='0'>--Seleccionar--</option>";
                        $(response).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.descripcion + "</option>";
                        });
                        $('#id_prioridad').html(newOption);
                        $("#id_prioridad").select2({
                        max_selected_options: 4
                        });
                    }
                });
                $("#divPrioridad").show();
            } else {
                $("#divPrioridad").hide();
            }
        }*/
    });
    // $("#divReferidos").show();
    //Contenedor Referidos
    $(document).ready(function () {
        let tipo_perfil = $('#tipo_perfil').val();
        if (tipo_perfil == '1') { //Fiscalizador responsable de laboratorio
            $("#tipo_atencion").change(function (e) { 
                e.preventDefault();
                if ($(this).val() !== 0) {
                    // $("#divReferidos").show();
                } else {
                    $("#divReferidos").hide();
                }
            });
        }
        if (tipo_perfil == '0') { //Tecnico de laboratorio por farmacia
            let tipo_atencion = $("#tipo_atencion").val();
            if (tipo_atencion !== 0) {
                // $("#divReferidos").show();
            } else {
                $("#divReferidos").hide();
            }
        }
    });    

    $("#btnBuscar").click(function (e) { 
        e.preventDefault();
        let btnBuscar = document.getElementById("btnBuscar");
        btnBuscar.disabled = true;
        let url = "";
        let id_user = "";
        let id_establecimiento = "";
        let fechaDesde = "";
        let fechaHasta = "";
        let dni_beneficiario = "";
        let apellido_nombres = "";
        let referido = "";
        let tipo_atencion = "";
        let id_servicio = "";
        let nro_examen = "";
        let id_prioridad = "";
        let estado = "";
        let farmacia = "";
        let id_hora_toma_muestra = "";
        let id_ubicacion_hospital = "";
        id_user = $("#id_user").val();
        id_establecimiento = $("#id_establecimiento").val();
        fechaDesde = $("#fechaDesde").val();
        fechaHasta = $("#fechaHasta").val();
        dni_beneficiario = $("#dni_beneficiario").val();
        apellido_nombres = $("#apellido_nombres").val();
        referido = $("#referido").val();
        tipo_atencion = $("#tipo_atencion").val();
        id_servicio = $("#id_servicio").val();
        nro_examen = $("#nro_examen").val();
        id_prioridad = $("#id_prioridad").val();
        estado = $("#estado").val();
        farmacia = $("#id_farmacia").val();
        id_hora_toma_muestra = $("#id_hora_toma_muestra").val();
        id_ubicacion_hospital = $("#id_ubicacion_hospital").val();
        dataArray = [
            id_user,
            id_establecimiento,
            fechaDesde,
            fechaHasta,
            dni_beneficiario,
            referido,
            tipo_atencion,
            id_servicio,
            nro_examen,
            id_prioridad,
            estado,
            farmacia,
            apellido_nombres,
            id_hora_toma_muestra,
            id_ubicacion_hospital
        ];

        // console.log("[dataArray] => ", dataArray);

        url = "{{ route('listar_examenes_laboratorios',['dataArray' => ':dataArray']) }}";


        dataString = dataArray.join(',');

        url = url.replace(':dataArray',dataString);

        $.ajax({
                type: "GET",
                url: url,
                beforeSend: function() {
                    // Aquí tu función antes de enviar
                    $("#divLista").html("<center><h3>Cargando...</h1></center>");
                },
                success: function(response) {
                    $("#divLista").html(response);
                    btnBuscar.disabled = false;
                },
                error: function(e) {
                    // Maneja los errores
                    console.log(e.message);
                    btnBuscar.disabled = false;
                }
            });
    });

    $("#id_prioridad").change(function (e) { 
        e.preventDefault();
        let id_prioridad = $(this).val();
        $.ajax({
            url: "/get_topico_por_id_prioridad/" + id_prioridad,
            success: function (response) {
                var newOption = "";
                newOption = "<option value='0'>--Seleccionar--</option>";
                $(response).each(function(ii, oo) {
                newOption += "<option value='" + oo.id + "'>" + oo.descripcion + "</option>";
                });
                $('#id_servicio').html(newOption);
                $("#id_servicio").select2({
                max_selected_options: 4
                });
            }
        });
    });

    

    $("#reporteLaboratorio").click(function (e) { 
        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                let url = "";
                let id_user = "";
                let id_establecimiento = "";
                let fechaDesde = "";
                let fechaHasta = "";
                let dni_beneficiario = "";
                let apellido_nombres = "";
                let referido = "";
                let tipo_atencion = "";
                let id_servicio = "";
                let nro_examen = "";
                let id_prioridad = "";
                let estado = "";
                let id_farmacia = "";
                let id_hora_toma_muestra = "";
                let id_ubicacion_hospital = "";

                id_user = $("#id_user").val();
                id_establecimiento = $("#id_establecimiento").val();
                fechaDesde = $("#fechaDesde").val();
                fechaHasta = $("#fechaHasta").val();
                dni_beneficiario = $("#dni_beneficiario").val();
                apellido_nombres = $("#apellido_nombres").val();
                referido = $("#referido").val();
                tipo_atencion = $("#tipo_atencion").val();
                id_servicio = $("#id_servicio").val();
                nro_examen = $("#nro_examen").val();
                id_prioridad = $("#id_prioridad").val();
                estado = $("#estado").val();
                id_farmacia = $("#id_farmacia").val();
                id_hora_toma_muestra = $("#id_hora_toma_muestra").val();
                id_ubicacion_hospital = $("#id_ubicacion_hospital").val();

                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "exportar_reporte_laboratorio",
                    data: {
                        "id_user" : id_user,
                        "id_establecimiento" : id_establecimiento,
                        "fechaDesde" : fechaDesde,
                        "fechaHasta" : fechaHasta,
                        "dni_beneficiario" : dni_beneficiario,
                        "apellido_nombres" : apellido_nombres,
                        "referido" : referido,
                        "tipo_atencion" : tipo_atencion,
                        "id_servicio" : id_servicio,
                        "nro_examen" : nro_examen,
                        "id_prioridad" : id_prioridad,
                        "estado" : estado,
                        "id_farmacia" : id_farmacia,
                        "id_hora_toma_muestra" : id_hora_toma_muestra,
                        "id_ubicacion_hospital" : id_ubicacion_hospital
                    },
                    success: function (response) {
                        // console.log(response);
                        // console.log(response.excelNameResponse);
                        if (response.excelNameResponse) {
                            
                            // Obtener la URL del archivo del response
                            let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                            
                            // Crear un enlace de descarga
                            let link = document.createElement('a');
                            link.href = fileUrl;
                            link.download = 'Exportar Reportes de Laboratorio.xlsx';
                            link.innerText = 'Descargar archivo';

                            // Simular un clic en el enlace para iniciar la descarga
                            let divLinkReporte = document.getElementById('link-reporte');
                            divLinkReporte.appendChild(link);
                            link.click();
                            divLinkReporte.removeChild(link);

                            Swal.hideLoading();
                            Swal.fire({
                                icon: 'success',
                                title: 'El reporte se ha generado correctamente!',
                                showConfirmButton: false,
                                showConfirmButton: false,
                                timer: 1500,
                                onClose: () => {
                                    $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                                }
                                
                            });
                        }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al generar el reporte',
                            text: 'Por favor, inténtelo de nuevo más tarde',
                            confirmButtonText: 'Cerrar',
                            customClass: {
                                confirmButton : 'custom-swal-button-cerrar'
                            },
                            onClose: () => {
                                $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                            }
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '#firmarResultadoLaboratorio', function(e) {
    e.preventDefault();
    firmarOrdenesLaboratorio();
    });

    const firmarOrdenesLaboratorio = () => {
    
    let id_user = $("#id_user").val();
    let id_establecimiento = $("#id_establecimiento").val();
    let fechaDesde = $("#fechaDesde").val();
    let fechaHasta = $("#fechaHasta").val();
    let dni_beneficiario = $("#dni_beneficiario").val();
    let referido = $("#referido").val();
    let tipo_atencion = $("#tipo_atencion").val();
    let id_servicio = $("#id_servicio").val();
    let nro_examen = $("#nro_examen").val();
    let id_prioridad = $("#id_prioridad").val();
    let estado = $("#estado").val();

    let postData = { 
        id_user : id_user, 
        id_establecimiento : id_establecimiento,
        fechaDesde : fechaDesde,
        fechaHasta : fechaHasta,
        dni_beneficiario : dni_beneficiario,
        referido : referido,
        tipo_atencion : tipo_atencion,
        id_servicio : id_servicio,
        nro_examen : nro_examen,
        id_prioridad : id_prioridad,
        estado : estado
    };

    $.ajax({
        url: "<?php echo url()?>/genera_csv_ordenes_laboratorio",
        type: "post",
        //data: p,
        data: postData,
        //timeout: 30000,
        dataType: 'json'
    })
    .done(function(resultado) {
        console.log(resultado);
        if (resultado.sw) {
            var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
            $("#doc_firmar_laboratorio").val(`${resultado.csv}.7z`);
            $("#opt_firma_laboratorio").val(`${resultado.opt}`);
            $("#flagServicioFirma").val("2");
            initInvoker('W');
        } else {
            //$("#dialogError").html('<p>Ocurrio un error en el sistema</p>');
            //dialogError(function () {});
        }
    })
    .fail(function(jqXHR, textStatus) {
        //$("#dialogError").html('<p>Error de Coneccion, verifique si tiene Internet</p>');
        //dialogError(function () {});
        console.log("Request failed: " + textStatus)
    })
    .always(function() {
        //bootbox.hideAll();
    });
    }

    /*===============Estilos de botón Cambiar estado de Orden INICIO ======================*/
    
    $(document).on('click', '#btnEstadoOrdenLab, #btnObservarOrdenLab', function(e) {
        e.preventDefault();
        cambiarEstadoDeOrdenLab($(this).data('tipo-atencion'),$(this).data('id'), $(this).data('estado-toma-muestra'), $(this).data('accion'));
    });

    const cambiarEstadoDeOrdenLab = (tipoAtencion, id, estadoTomaMuestra, accion) => {
        $('#tipo_atencion_generico').val(tipoAtencion);
        $('#id_generico').val(id);
        $('#accion_asistencia_paciente').val(accion);

        if (accion === 'observar') {
            $('#tituloModalCambiarEstadoLab').text('Observar Orden de Laboratorio');
            $('#labelMotivoModalCambiarEstadoLab').text('Ingrese el motivo de la Observación:');
        } else {
            $('#tituloModalCambiarEstadoLab').text('Anular Orden de Laboratorio');
            $('#labelMotivoModalCambiarEstadoLab').text('Ingrese el motivo de la Anulación:');
        }

        if (String(estadoTomaMuestra) === "1") {
            $('#nota_orden_con_toma_muestra').show();
        } else {
            $('#nota_orden_con_toma_muestra').hide();
        }
        
        //console.log(tipoAtencion,id);
        $("#modalCambiarEstadoLab").modal();
    }
    
    $(document).on('click', '#btnGuardarFormAsistenciaPaciente', function(e) {
    e.preventDefault();
        validarFormObsDeAsistenciaDelPaciente();
    });

    const validarFormObsDeAsistenciaDelPaciente = () => {
        const obs_asistencia_del_paciente = $('#obs_asistencia_del_paciente').val().trim();
        const accion = $('#accion_asistencia_paciente').val();
        const esObservar = accion === 'observar';

        if(obs_asistencia_del_paciente === ""){
            Swal.fire({
                icon: 'error',
                title: 'Debe ingresar el motivo de la ' + (esObservar ? 'Observación' : 'Anulación') + ' para continuar.',
                showConfirmButton: false,
                timer: 2000
            });
        return;
        }

        $.ajax({
            url: $('#frmObsAsistenciaPaciente').attr('action'),
            type: 'POST',
            data: $('#frmObsAsistenciaPaciente').serialize(),
            success: function(response) {
                console.log(response.success);
                if(response.success == 'true'){
                    $('#modalCambiarEstadoLab').modal('hide');
                    //============Limpiamos los campos=========
                    $('#tipo_atencion_generico').val('');
                    $('#id_generico').val('');
                    $('#accion_asistencia_paciente').val('');
                    $('#obs_asistencia_del_paciente').val('');
                    //=========================================
                    Swal.fire({
                        icon: 'success',
                        title: 'La Orden de Laboratorio fue ' + (esObservar ? 'observada' : 'anulada') + '.',
                        showConfirmButton: false,
                        timer: 2000,
                        onClose: function() {
                            $('#btnBuscar').click(); // Hacer clic en el botón #btnBuscar para actualizar la lista
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                // Manejar los errores aquí
                console.error(xhr.responseText);
                var errorMessage = JSON.parse(xhr.responseText).error;
                // Muestra el mensaje de error en algún lugar de tu interfaz de usuario
                alert("Error: " + errorMessage);
            }
        });
    }

    $(document).ready(function() {
        $('#modalCambiarEstadoLab').on('hidden.bs.modal', function() {
            // Limpiamos los campos de entrada al ocultar el modal
            $('#obs_asistencia_del_paciente').val('');
            $('#tipo_atencion_generico').val('');
            $('#id_generico').val('');
            $('#accion_asistencia_paciente').val('');
        });
    });
    /*===============Estilos de botón Cambiar estado de Orden FIN ======================*/

    function fn_anularCita(id_cita) {
      $.ajax({
          url: '/anular_cita/' + id_cita,
          dataType: "json",
          success: function(result) {
              if (result.sw == true) {
                  // location.href = "all_citas_medico_paciente";
              }
          }
      });
    }


        var v_id_establecimiento = $("#id_establecimiento").val();
        var v_id_tipo_atencion = $('#tipo_atencion').val();
        $.ajax({
                    url: '/obtener_consultorio_tipo_atencion/' + v_id_establecimiento + '/' + v_id_tipo_atencion,
                    success: function(result) {
                        var newOption = "";
                        newOption = "<option value='0'>--Seleccionar--</option>";
                        $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                        });
                        $('#id_servicio').html(newOption);
                        $("#id_servicio").select2({
                        max_selected_options: 4
                        });
                    }
        });

         $('#tipo_atencion').select2({
            placeholder: 'Seleccione una opción',
            width: '100%' // Asegura que se adapte al contenedor
        });

function fusionarPDFsDesdeURLs(url1, url2) {
    const nuevaVentana = window.open('', '_blank'); // abrir antes de bloqueos

    $('#pdf-loader').show(); // mostrar loader

    const { PDFDocument } = PDFLib;

    Promise.all([
        fetch(url1).then(r => r.arrayBuffer()),
        fetch(url2).then(r => r.arrayBuffer())
    ]).then(async ([res1, res2]) => {
        const doc1 = await PDFDocument.load(res1);
        const doc2 = await PDFDocument.load(res2);

        const pdfFinal = await PDFDocument.create();

        const pages1 = await pdfFinal.copyPages(doc1, doc1.getPageIndices());
        pages1.forEach(p => pdfFinal.addPage(p));

        const pages2 = await pdfFinal.copyPages(doc2, doc2.getPageIndices());
        pages2.forEach(p => pdfFinal.addPage(p));

        const mergedBytes = await pdfFinal.save();
        const blob = new Blob([mergedBytes], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);

        nuevaVentana.location.href = url;
        $('#pdf-loader').hide(); // ocultar loader
    }).catch(error => {
        $('#pdf-loader').hide();
        alert("Hubo un error al fusionar los PDF.");
        console.error(error);
    });
}


</script>
@endsection
@section('content')

<div id="pdf-loader" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); text-align: center; padding-top: 200px;">
    <div style="font-size: 18px; color: #333;">
        ⏳ Generando PDF ...<br><br>
        <span class="spinner" style="font-size: 30px;">🔄</span>
    </div>
</div>

<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading">
            <h3 class="panel-title">Buscar Examenes de Laboratorio - {{$farmacia->nombre}}</h3>
        </div>  

        <div class="panel-body" style="padding: 15px;">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">BÚSQUEDA</a></li>
                <li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">REGISTROS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tab1">
                    <!-- <div class="row"> -->
                        <!-- <br> -->
                        <!-- Admin Configuration Options -->
                        <?php 
                        if(auth()->user()->flagfiscalizador==true){
                            echo '<div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                    <button id="btnShowConfigOptions" class="btn btn-success btn-block">
                                        <i class="fa fa-cog"></i> Configuración
                                    </button>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                    <button id="btnAddNewOption" class="btn btn-success btn-block">
                                        <i class="fa fa-plus"></i> Agregar Nueva Configuración
                                    </button>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                    <button id="btnLaboratorioConfig" class="btn btn-primary btn-block">
                                        <i class="fa fa-flask"></i> Configuración de Laboratorio
                                    </button>
                                    </div>
                                </div>
                            </div>';
                        }
                        ?>

                        <!-- Hidden fields -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_user" id="id_user" value="{{ $id_user }}">
                        <input type="hidden" id="id_establecimiento" name="id_establecimiento" value="{{ $id_establecimiento }}">
                        <input type="hidden" id="tipo_perfil" name="tipo_perfil" value="{{ $tipo_perfil }}">

                        <div class="col-md-10">
                            <div class="row">
                            <!-- Tipo Atención -->
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label class="control-label">Tipo Atención</label>
                                    <select class="form-control input-sm" name="tipo_atencion" id="tipo_atencion">
                                        <?php foreach ($tipo_atenciones as $row) : ?>
                                        <option value="<?php echo $row->id ?>"><?php echo $row->nombre ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Fecha Desde -->
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label class="control-label fecha-inicio">Fecha cita Desde</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" 
                                            placeholder="dd/mm/aaaa" value="<?php echo $fechaDesde ?>" maxlength="10">
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha Hasta -->
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label class="control-label fecha-fin">Fecha cita Hasta</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" 
                                            placeholder="dd/mm/aaaa" value="<?php echo $fechaHasta ?>" maxlength="10">
                                    </div>
                                </div>
                            </div>

                            <!-- Nro. Documento -->
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label class="control-label">Nro. Doc.</label>
                                    <input id="dni_beneficiario" name="dni_beneficiario" type="text" 
                                        class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label class="control-label">Apellidos y Nombres</label>
                                    <input id="apellido_nombres" name="apellido_nombres" type="text" class="form-control input-sm" value="">
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label class="control-label">Estado</label>
                                    <select class="form-control input-sm" name="estado" id="estado">
                                        <option value="1">Órdenes pendientes</option>
                                        <option value="2">Atendido</option>
                                        <option value="3" selected="selected">Todos</option>
                                        <option value="6">Anulados</option>
                                        <option value="8">Observados</option>
                                        <option value="7">Pendientes de envío</option>
                                        <?php if (auth()->user()->flagfiscalizador == 1) : ?>
                                            <!-- <option value="4">Atendido LabCore</option>
                                            <option value="5">Firmado</option> -->
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            </div> <!-- .row -->

                            <div class="row">
                            <!-- Especialidad -->
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label class="control-label">Especialidad</label>
                                    <select class="form-control input-sm" name="id_servicio" id="id_servicio">
                                        <!-- Options will be loaded dynamically -->
                                    </select>
                                </div>
                            </div>

                            <div id="divReferidos" class="col-sm-6 col-md-3 col-lg-2" style="display: none">
                                <div class="form-group">
                                    <label class="control-label">Referido</label>
                                    <select class="form-control input-sm" name="referido" id="referido">
                                        <option value="0">-Seleccionar-</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 col-lg-2" style="display: none">
                                <div class="form-group">
                                    <label class="control-label">Nro. Ex Laboratorio</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-flask"></i></span>
                                        <input id="nro_examen" name="nro_examen" type="text" 
                                            class="form-control input-sm" value="<?php echo $nro_examen ?>" 
                                            onKeyPress="return soloNumeros(event)" maxlength="12">
                                    </div>
                                </div>
                            </div>

                            <div id="divPrioridad" class="col-sm-6 col-md-3 col-lg-2" style="display: none">
                                <div class="form-group">
                                    <label class="control-label">Prioridad</label>
                                    <select class="form-control input-sm" name="id_prioridad" id="id_prioridad">
                                        <!-- Options will be loaded dynamically -->
                                    </select>
                                </div>
                            </div>

                            <div id="divIndicacionHoraTomaMuestra" class="col-sm-6 col-md-3 col-lg-2" style="display: none">
                                <div class="form-group">
                                    <label class="control-label">Indicación de Toma de Muestra</label>
                                    <select class="form-control input-sm" name="id_hora_toma_muestra" id="id_hora_toma_muestra">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="1">05:00 AM</option>
                                        <option value="2">09:00 AM</option>
                                        <option value="3">16:00 PM</option>
                                        <option value="4">22:00 PM</option>
                                        <option value="0">OTRO</option>
                                    </select>
                                </div>
                            </div>

                            <div id="divUbicacionHospital" class="col-sm-6 col-md-3 col-lg-2" style="display: none">
                                <div class="form-group">
                                    <label class="control-label">Ubicación</label>
                                    <select class="form-control input-sm" name="id_ubicacion_hospital" id="id_ubicacion_hospital">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="1">Histórico</option>
                                        <option value="2">PIP</option>
                                    </select>
                                </div>
                            </div>

                            </div> <!-- .row -->
                        </div>

                        <!-- Action buttons -->
                        <div class="col-md-2">
                            <div class="col-sm-6 col-md-12">
                                <div class="form-group">
                                    <label for="">&nbsp;</label>
                                    <button id="btnBuscar" type="button" class="btn btn-primary btn-block input-sm">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-12">
                                <div class="form-group">
                                    <label for="">&nbsp;</label>
                                    <button id="reporteLaboratorio" type="button" class="btn btn-success btn-block input-sm">
                                        <i class="fa fa-file-excel-o"></i> Exportar
                                    </button>
                                </div>
                            </div>
                            <?php if (auth()->user()->flagfiscalizador == 1) : ?>
                            <div class="col-sm-6 col-md-12">
                                <button id="firmarResultadoLaboratorio" type="button" class="btn btn-danger btn-block input-sm">
                                    <i class="fa fa-pencil"></i> Firmar
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>


                        <div class="row w-100" style=margin-top:0px;">
                            <div id="divLista"></div>
                        </div>
                    <!-- </div> -->
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab2">
                    <br>
                    <div class="table-responsive">
                        <table class="table table-condensed" id="tbl_ordenes_toma_muestra">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>N° Orden</th>
                                    <th>Tipo atención</th>
                                    <th>Servicio</th>
                                    <th>Fecha de Atención</th>
                                    <th>Profesional</th>
                                    <th>N° Documento Paciente</th>
                                    <th>Paciente</th>
                                    <th>Sexo del Paciente</th>
                                    <th>Usuario que Toma Muestra</th>
                                    <th>Fecha Hora Registro Toma Muestra</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @if (!empty($ordenes_toma_muestra))
                                    @foreach($ordenes_toma_muestra as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $row->cod_orden_lab }}</td>
                                            <td>{{ $row->tipo_atencion }}</td>
                                            <td>{{ $row->servicio }}</td>
                                            <td>{{ date("d/m/Y", strtotime($row->fecha_hora_atencion)) }}</td>
                                            <td>{{ $row->profesional_paterno }} {{ $row->profesional_materno }}, {{ $row->profesional_nombre }}</td>
                                            <td>{{ $row->nro_doc_ident }}</td>
                                            <td>{{ $row->paciente_paterno }} {{ $row->paciente_materno }}, {{ $row->paciente_nombre }}</td>
                                            <td>{{ $row->sexo }}</td>
                                            <td>{{ $row->usuario_paterno }} {{ $row->usuario_materno }}, {{ $row->usuario_nombre }}</td>
                                            <td>{{ date("d/m/Y H:i:s", strtotime($row->fecha_registro_toma_muestra)) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="argumentos_laboratorio" value="" />
        <input type="hidden" id="doc_firmar_laboratorio" value="" />
        <input type="hidden" id="opt_firma_laboratorio" value="" />
        <!-- </div> -->
    </div>
</div>

<div class="modal fade" id="myModalDetRV" role="dialog">
    <div class="modal-dialog modal-lg" role="document"> 
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DE ANALISIS DE LABORATORIO. <span id="nropresmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-laboratorio"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCambiarEstadoLab" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header custom-header-cambiar-estado">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="font-size: 2rem" id="tituloModalCambiarEstadoLab">Anular Orden de Laboratorio<span id="nropresmodal"></span></h5>
            </div>
            <form name="frmObsAsistenciaPaciente" id="frmObsAsistenciaPaciente" class="form-horizontal" role="form" method="post"  action="{{ route('registrar_observacion_asistencia_paciente') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="tipo_atencion_generico" id="tipo_atencion_generico">
                <input type="hidden" name="id_generico" id="id_generico">
                <input type="hidden" name="accion_asistencia_paciente" id="accion_asistencia_paciente">
                <div class="modal-body">
                    <label for="" id="labelMotivoModalCambiarEstadoLab">Ingrese el motivo de la Anulación:</label>
                    <input type="text" name="obs_asistencia_del_paciente" id="obs_asistencia_del_paciente" class="form-control">
                    <br>
                    <span id="nota_orden_con_toma_muestra" style="color: red">La orden ya cuenta con Registro de Toma de Muestra, por lo tanto la anulación solo será efectivo para el SIGESAPOL.</span>
                </div>
                <div class="modal-footer">
                    <div style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnGuardarFormAsistenciaPaciente" class="btn btn-success">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection