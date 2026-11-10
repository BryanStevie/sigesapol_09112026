<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
    <style>
        .alert-warning {
            padding: 4px 6px 4px 6px;
            margin: 0px;
            background: #3c8dbc !important;
            font-weight: normal !important;
            color: #FFFFFF
        }

        .table-sortable tbody tr {
            cursor: move;
        }

        #global {
            min-height: 650px !important;
            /*width: auto;*/
            width: 99%;
            border: 1px solid #ddd;
            margin: 15px
                /* background: #f1f1f1;*/
                /*overflow-y: scroll !important;*/
        }

        .margin {

            margin-bottom: 20px;
        }

        .margin-buscar {
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .row {
            margin-top: 10px;
            padding: 0 10px;
        }

        .clickable {
            cursor: pointer;
        }

        .panel-heading div {
            margin-top: -18px;
            font-size: 15px;
        }

        .panel-heading div span {
            margin-left: 5px;
        }

        .panel-body {
            display: block;
        }

        .dataTables_filter {
            display: none;
        }

        .help-block {
            display: block;
            margin-top: 1px;
            margin-bottom: 10px;
            color: #737373;
        }

        .modal-dialog {
            width: 40% !important;
        }

        .checkout-button {
            width: 130px;
            height: 35px;
            background: #333;
            border: none;
            vertical-align: top;
            margin-left: 35px;
            cursor: pointer;
            color: #fff;
        }

        .checkout-button:disabled {
            background: #999;
            color: #555;
            cursor: not-allowed;
        }

        body,
        button {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        .my-toast {
            max-width: 350px !important;
        }

        .icon-center {
            position: absolute !important;
            top: 50%;
            margin-top: -20px !important;
        }

        .left-gap {
            margin-left: 4em !important;
        }

        .swal2-modal {
            min-height: 300px;
        }

        .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal {
            margin: 100;
        }

        .tamanioincon {
            width: 5px;
            height: 2px;
        }
/* 
        .swal2-icon-content {
            width: 50%;
            height: 40%;
        } */

        .swal2-image {
            width: 50%;
            height: 40%;
        }

        .swal2-cancel {
            margin-left: 20px;
            border-color: #eea236;
            background-image: -webkit-linear-gradient(45deg, #eea236 50%, transparent 50%);
            background-image: linear-gradient(45deg, #eea236 50%, transparent 50%);
            background-position: 100%;
            background-size: 400%;
            -webkit-transition: background 300ms ease-in-out;
            transition: background 300ms ease-in-out;
        }

        .swal2-cancel:hover {
            background-position: 0;
        }

        .swal2-confirm {
            border-color: #3c763d;
            box-shadow: 0 0 40px 40px #3c763d inset, 0 0 0 0 #3c763d;
            -webkit-transition: all 150ms ease-in-out;
            transition: all 150ms ease-in-out;
        }

        .swal2-confirm:hover {
            box-shadow: 0 0 10px 0 #3c763d inset, 0 0 10px 4px #3c763d;
        }

        .btnsiguiente {
            border-color: #4cae4c;
            border-radius: 0;
            color: #4cae4c;
            position: relative;
            overflow: hidden;
            z-index: 1;
            -webkit-transition: color 150ms ease-in-out;
            transition: color 150ms ease-in-out;
        }

        .btnsiguiente:after {
            content: '';
            position: absolute;
            display: block;
            top: 0;
            left: 50%;
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
            width: 0;
            height: 100%;
            background: #4cae4c;
            z-index: -1;
            -webkit-transition: width 150ms ease-in-out;
            transition: width 150ms ease-in-out;
        }

        .btnsiguiente:hover {
            color: #fff;
        }

        .btnsiguiente:hover:after {
            width: 110%;
        }

        .my-actions {
            margin: 0 2em;
        }

        .order-1 {
            order: 1;
            margin-left: 20px;
            border-color: #eea236;
            background-image: -webkit-linear-gradient(45deg, #eea236 50%, transparent 50%);
            background-image: linear-gradient(45deg, #eea236 50%, transparent 50%);
            background-position: 100%;
            background-size: 400%;
            -webkit-transition: background 300ms ease-in-out;
            transition: background 300ms ease-in-out;
        }

        .order-1:hover {
            background-position: 0;
            border-color: #3c763d;
            box-shadow: 0 0 40px 40px #3c763d inset, 0 0 0 0 #3c763d;
            -webkit-transition: all 150ms ease-in-out;
            transition: all 150ms ease-in-out;
        }

        .order-2 {
            order: 2;
            margin-right: 50px;
            border-color: #3c763d;
            box-shadow: 0 0 40px 40px #3c763d inset, 0 0 0 0 #3c763d;
            -webkit-transition: all 150ms ease-in-out;
            transition: all 150ms ease-in-out;
        }

        .order-2:hover {
            box-shadow: 0 0 10px 0 #3c763d inset, 0 0 10px 4px #3c763d;
        }

        .order-3 {
            order: 3;
            margin-right: 44px;
            margin-top: 16px;
            border-color: #3c763d;
            -webkit-transition: all 150ms ease-in-out;
            transition: all 150ms ease-in-out;
        }

        .order-3:hover {
            background-position: 0;
            border-color: #3c763d;
            -webkit-transition: all 150ms ease-in-out;
            transition: all 150ms ease-in-out;
        }

        .order-4 {
            order: 4;
            margin-right: 44px;
            margin-top: 16px;
            border-color: #3c763d;
            -webkit-transition: all 150ms ease-in-out;
            transition: all 150ms ease-in-out;
        }

        .order-4:hover {
            background-position: 0;
            border-color: #3c763d;
            -webkit-transition: all 150ms ease-in-out;
            transition: all 150ms ease-in-out;
        }


        .right-gap {
            margin-right: auto;
        }

        .swal2-html-container {
            padding-bottom: 25px;
        }

        a.animated-button:link,
        a.animated-button:visited {
            position: relative;
            display: block;
            margin: 30px auto 0;
            padding: 14px 15px;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            text-transform: uppercase;
            overflow: hidden;
            letter-spacing: .08em;
            border-radius: 0;
            text-shadow: 0 0 1px rgba(0, 0, 0, 0.2), 0 1px 0 rgba(0, 0, 0, 0.2);
            -webkit-transition: all 1s ease;
            -moz-transition: all 1s ease;
            -o-transition: all 1s ease;
            transition: all 1s ease;
        }

        a.animated-button:link:after,
        a.animated-button:visited:after {
            content: "";
            position: absolute;
            height: 0%;
            left: 50%;
            top: 50%;
            width: 150%;
            z-index: -1;
            -webkit-transition: all 0.75s ease 0s;
            -moz-transition: all 0.75s ease 0s;
            -o-transition: all 0.75s ease 0s;
            transition: all 0.75s ease 0s;
        }

        a.animated-button:link:hover,
        a.animated-button:visited:hover {
            color: #FFF;
            text-shadow: none;
        }

        a.animated-button:link:hover:after,
        a.animated-button:visited:hover:after {
            height: 450%;
        }

        a.animated-button:link,
        a.animated-button:visited {
            position: relative;
            display: block;
            margin: 30px auto 0;
            padding: 14px 15px;
            color: #fff;
            font-size: 14px;
            border-radius: 0;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            text-transform: uppercase;
            overflow: hidden;
            letter-spacing: .08em;
            text-shadow: 0 0 1px rgba(0, 0, 0, 0.2), 0 1px 0 rgba(0, 0, 0, 0.2);
            -webkit-transition: all 1s ease;
            -moz-transition: all 1s ease;
            -o-transition: all 1s ease;
            transition: all 1s ease;
        }

        a.animated-button.order-3 {
            color: #fff;
            cursor: pointer;
            display: block;
            position: relative;
            border: 2px solid #F7CA18;
            transition: all 0.4s cubic-bezier(0.42, 0, 0.58, 1);
        }

        a.animated-button.order-3:hover {
            color: #000 !important;
            background-color: transparent;
            text-shadow: nthree;
        }

        a.animated-button.order-3:hover:before {
            left: 0%;
            right: auto;
            width: 100%;
        }

        a.animated-button.order-3:before {
            display: block;
            position: absolute;
            top: 0px;
            right: 0px;
            height: 100%;
            width: 0px;
            z-index: -1;
            content: '';
            color: #000 !important;
            background: #F7CA18;
            transition: all 0.4s cubic-bezier(0.42, 0, 0.58, 1);

        }

        .cerrar {
            display: block;
        }

        .ocuto {
            display: none;
        }

        .swal-wide-form{
            width: 500px !important;
            padding: 0px 20px !important;
        }

        .swal-wide{
            width: 1000px !important;
            padding: 0px 20px !important;
        }

        .swal2-icon.swal2-warning {
            font-size: 15px !important; 
        }
        
    </style>

    <script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
    <script>
        var listaparallamar = [];
        var listaPresencial = [];
        var cantidadLlamada = 0;
        var primeraposicion = 0;
        var nuevaPosicion = 0;
        var indicePosponer;
        var pararMientras;

        var ids;
        var boleanovacio;
        var autollamada;
        var autollamadanext = 1;
        var disableBoton = true;
        var soloIpressLuisNSaes;
        var fechaActualString;
        var mantenerCantidad = false;
        var VariableApoyo;
        var idnestor = 0;
        var flagllamadacita;
        var soloIpressLuisNSaesn2;
        var farmacia;
        var cantidadllamadasTimer;
        var downloadTimer;
        var dni_beneficiario2;
        var validacionEstado;
        var timeleft;
        var validacionFechasHoy;

        var posponer = 0;


        //--------------------------------------------------------------------------
        $(document).ready(function() {


            $('#openModalHistoria').on('hidden.bs.modal', function() {
                location.reload();
            })

            $('#openModalSiteds').on('hidden.bs.modal', function() {
                location.reload();
            })


            $consul = $("#frmRequerimiento").find("#id_requerimiento");
            $consul.select2();

            $('#fecha_ini').appendDtpicker({
                "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
            });
            $('#fecha_fin').appendDtpicker({
                "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
                //"dateFormat": "YYYY-MM-DD hh:mm",
            });
            $('#fecha_ini').click(function() {
                var fechaDesdeIni = $('#fecha_ini').val();
                $('#fecha_ini').change(function() {
                    var fechaDesde = $('#fecha_ini').val();
                    var fechaHasta = $('#fecha_fin').val();
                    f1 = fechaDesde.split("-");
                    f2 = fechaHasta.split("-");
                    f3 = new Date();
                    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[
                        0])); //30 de noviembre de 2014

                    if (f1 > f2) {
                        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                        $('#fecha_ini').val(fechaDesdeIni);
                        return false;
                    }
                });
            });
            $('#fecha_fin').click(function() {
                var fechaHastaIni = $('#fecha_fin').val();
                $('#fecha_fin').change(function() {
                    var fechaDesde = $('#fecha_ini').val();
                    var fechaHasta = $('#fecha_fin').val();
                    f1 = fechaDesde.split("-");
                    f2 = fechaHasta.split("-");
                    f3 = new Date();
                    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[
                        0])); //30 de noviembre de 2014

                    if (f2 < f1) {
                        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                        $('#fecha_fin').val(fechaHastaIni);
                        return false;
                    }

                    /*if (f2 > f3) {
                    bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
                    $('#fecha_fin').val(fechaHastaIni);
                    return false;
                    }*/
                });
            });
            $('#fecha_fin').trigger('change');
            $('#fecha_ini').trigger('change');
            var activeSystemClass = $('.list-group-item.active');

            console.log('ready(function() = ' + ids);
        });


        /*
        $(document).ready(function () {
        $('#example').DataTable({
        "language": {
        "emptyTable": "No se encontraron resultados"
        }
        });
        $("#system-search").keyup(function() {
        var dataTable = $('#example').dataTable();
        dataTable.fnFilter(this.value);
        });
        $("#iddispensador").select2({
        width: 'resolve'
        });
        $("#iddispensador").trigger('change');
        });
        */
        function arrMove(arr, oldIndex, newIndex) {
            if (newIndex >= arr.length) {
                let i = newIndex - arr.length + 1;
                while (i--) {
                    arr.push(undefined);
                }
            }
            arr.splice(newIndex, 0, arr.splice(oldIndex, 1)[0]);
            return arr;
        };


        function reinicioFinalCola(valorFinalCola) {
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": "/Reinicio",
                "data": {
                    valorFinalCola: valorFinalCola
                },
                "success": function(result) {
                    console.log(JSON.stringify(result));

                },
                "error": function(msg, textStatus, errorThrown) {
                    //location.href="login";
                }
            });
        }

        function llamarTv() {



            if (dni_beneficiario2 != "") {
                clearInterval(downloadTimer);
                document.getElementById("countdown").innerHTML = "filtro personalizado(Pausa)";
                return;
            }

            autollamadanext = 0;
            let continua = false;
            console.log(JSON.stringify(boleanovacio));
            if (boleanovacio == 0) {
                continua = true;
                Swal.fire({
                    title: "No hay pacientes por atender!",
                    text: "Esta ventana se cerrara en 3 segundos",
                    timer: 3000,
                    onOpen: function() {
                        Swal.showLoading()
                    }
                }).then(function(result) {
                    if (result.dismiss === "timer") {
                        console.log("Esta ventana se cerrar en 5 segundos")

                    }
                })
            }
            if (continua) {
                return;
            }
            validacionFechasHoy = listaPresencial.filter(e => e.dia == fechaActualString);
            if (validacionFechasHoy.length != listaPresencial.length) {
                Swal.fire({
                    title: "No se puede llamar pacientes de otros dias",
                    text: "Esta ventana se cerrara en 5 segundos",
                    timer: 3000,
                    onOpen: function() {
                        Swal.showLoading()
                    }
                }).then(function(result) {
                    if (result.dismiss === "timer") {
                        console.log("Esta ventana se cerrar en 5 segundos")

                    }
                })
                return;

            }

            console.log('f llamarTv() = ' + ids);
            actualizacion(ids);
            return autollamadanext;
        }

        function posponerLlamadoTv() {
            var listaPresencial = [];
            listaPresencial = listaparallamar.filter(objeto => objeto.tipo_cita != "TELECONSULTA");
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": "/Posponerllamado",
                "data": {
                    listaPresencial: listaPresencial
                },
                "success": function(result) {


                },
                "error": function(msg, textStatus, errorThrown) {
                    //location.href="login";
                }
            });

        }

        $('#fecha_reg_ini').appendDtpicker({
            "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
        });
        $('#fecha_reg_fin').appendDtpicker({
            "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
            //"dateFormat": "YYYY-MM-DD hh:mm",
        });
        $('#fecha_reg_ini').click(function() {
            var fechaRegDesdeIni = $('#fecha_reg_ini').val();
            $('#fecha_reg_ini').change(function() {
                var fechaRegDesde = $('#fecha_reg_ini').val();
                var fechaRegHasta = $('#fecha_reg_fin').val();
                fr1 = fechaRegDesde.split("-");
                fr2 = fechaRegHasta.split("-");
                fr3 = new Date();
                var fr1 = new Date(parseInt(fr1[2]), parseInt(fr1[1] - 1), parseInt(fr1[0]));
                var fr2 = new Date(parseInt(fr2[2]), parseInt(fr2[1] - 1), parseInt(fr2[
                    0])); //30 de noviembre de 2014

                if (fr1 > fr2) {
                    bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                    $('#fecha_reg_ini').val(fechaRegDesdeIni);
                    return false;
                }
            });
        });
        $('#fecha_reg_fin').click(function() {
            var fechaRegHastaIni = $('#fecha_reg_fin').val();
            $('#fecha_reg_fin').change(function() {
                var fechaRegDesde = $('#fecha_reg_ini').val();
                var fechaRegHasta = $('#fecha_reg_fin').val();
                fr1 = fechaRegDesde.split("-");
                fr2 = fechaRegHasta.split("-");
                fr3 = new Date();
                var fr1 = new Date(parseInt(fr1[2]), parseInt(fr1[1] - 1), parseInt(fr1[0]));
                var fr2 = new Date(parseInt(fr2[2]), parseInt(fr2[1] - 1), parseInt(fr2[
                    0])); //30 de noviembre de 2014

                if (fr2 < fr1) {
                    bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                    $('#fecha_fin').val(fechaRegHastaIni);
                    return false;
                }

            });
        });
        $('#fecha_reg_fin').trigger('change');
        $('#fecha_reg_ini').trigger('change');


        function reporteCita(idUser) {

            var fecha_ini = $('#fecha_ini').val();
            var fecha_fin = $('#fecha_fin').val();
            var fecha_reg_ini = $('#fecha_reg_ini').val();
            var fecha_reg_fin = $('#fecha_reg_fin').val();
            var dni = $('#dni_beneficiario').val();
            var paciente = $('#nombre_beneficiario').val();
            var estado = $('#estado').val();
            var id_servicio = $('#id_servicio').val();
            var id_consultorio = $('#id_consultorio').val();
            var id_medico = $('#id_medico').val();
            var id_condicion_establecimiento = $('#id_condicion_establecimiento').val();
            var id_condicion_servicio = $('#id_condicion_servicio').val();

            if (fecha_ini == "") fecha_ini = 0;
            if (fecha_fin == "") fecha_fin = 0;
            if (fecha_reg_ini == "") fecha_reg_ini = 0;
            if (fecha_reg_fin == "") fecha_reg_fin = 0;
            if (dni == "") dni = 0;
            if (paciente == "") paciente = 0;
            if (estado == "") estado = 0;
            if (id_servicio == "") id_servicio = 0;
            if (id_consultorio == "") id_consultorio = 0;
            if (id_medico == "") id_medico = 0;
            if (id_condicion_establecimiento == "") id_condicion_establecimiento = 0;
            if (id_condicion_servicio == "") id_condicion_servicio = 0;
            location.href = 'exportar_reporte_cita/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + fecha_reg_ini +
                '/' + fecha_reg_fin + '/' + dni + '/' + paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio +
                '/' + id_medico + '/' + id_condicion_establecimiento + '/' + id_condicion_servicio;

        }

        function reporteHistoria(idUser) {

            var fecha_ini = $('#fecha_ini').val();
            var fecha_fin = $('#fecha_fin').val();
            var fecha_reg_ini = $('#fecha_reg_ini').val();
            var fecha_reg_fin = $('#fecha_reg_fin').val();
            var dni = $('#dni_beneficiario').val();
            var paciente = $('#nombre_beneficiario').val();
            var estado = $('#estado').val();
            var id_servicio = $('#id_servicio').val();
            var id_consultorio = $('#id_consultorio').val();
            var id_medico = $('#id_medico').val();

            if (fecha_ini == "") fecha_ini = 0;
            if (fecha_fin == "") fecha_fin = 0;
            if (fecha_reg_ini == "") fecha_reg_ini = 0;
            if (fecha_reg_fin == "") fecha_reg_fin = 0;
            if (dni == "") dni = 0;
            if (paciente == "") paciente = 0;
            if (estado == "") estado = 0;
            if (id_servicio == "") id_servicio = 0;
            if (id_consultorio == "") id_consultorio = 0;
            if (id_medico == "") id_medico = 0;

            var href = 'imprimir_reporte_historia/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + fecha_reg_ini +
                '/' + fecha_reg_fin + '/' + dni + '/' + paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio +
                '/' + id_medico;
            window.open(href, '_blank');

        }

        function ver_det_receta(idUser, nro_receta, id_receta) {
            $('#nrorecmodal').text(nro_receta);
            $.ajax({
                url: '/ver_receta_atendida/' + idUser + '/' + nro_receta + '/' + id_receta,
                async: false,
                success: function(result) {
                    $("#muestra-det-receta").html(result);
                },
                complete: function() {
                    $("#myModalDetRV").modal();
                }
            });
        }



        function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }

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



        function anularCita(id_cita) {

            bootbox.confirm({
                size: "small",
                message: "&iquest;Deseas anular la cita?",
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
                        location.href = "all_citas_medico_paciente";
                    }
                }
            });

        }

        function modalHistoria(id) {

            $(".modal-dialog").css("width", "80%");
            $('#openModalHistoria').modal('show');
            $('#openModalHistoria .modal-body').css('height', 'auto');

            $.ajax({
                url: "modal_historia/" + id,
                type: "GET",
                success: function(result) {
                    $(".modal-historia").html(result);
                }
            });

        }

        function modalSiteds(id,dni) {
            $(".modal-dialog").css("width", "80%");
            $('#openModalSiteds').modal('show');
            $('#openModalSiteds.modal-body').css('height', 'auto');
            $.ajax({
                url: "modal_siteds/" + id+'/'+ dni+'/'+1,
                type: "GET",
                success: function(result) {
                    $(".modal-siteds").html(result);
                }
            });
            console.log("[modalSiteds]");
        }

        function reellamado() {
            clearInterval(downloadTimer);
            fn_ListarBusqueda();
        }


        function modalForm(url) {
            $(".modal-dialog").css("width", "80%");
            $('#modalForm').modal('show');
            $('#modalForm .modal-body').css('height', 'auto');
            $.ajax({
                url: url,
                type: "GET",
                success: function(result) {
                    $("#modalForm .modal-body").html(result);
                }
            });
        }

        function modalTelefono(id) {

            $(".modal-dialog").css("width", "80%");
            $('#openOverlayOpc').modal('show');
            $('#openOverlayOpc .modal-body').css('height', 'auto');

            $.ajax({
                url: "modal_telefono/" + id,
                type: "GET",
                success: function(result) {
                    $("#diveditpregOpc").html(result);
                }
            });

        }

        /*function temporizador(){

            setInterval(() => {
               
                

            }, 300000);

        }*/
        let documento = document.getElementsByClassName("swal2-close");
        $(".swal2-close").click(function() {
            Swal.close()
        });

        function actualizacion(ids) {
            console.log('f actualizacion(ids) = ' + ids);
            posponer = 0;

            var newRow = "";


            var formData = new FormData();
            formData.append('ids', JSON.stringify(ids));
            formData.append('variableapoyo', mantenerCantidad);
            $.ajax({
                url: "/prueba",
                type: "POST",
                data: formData,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                success: function(result) {
                    console.log(`Resultado prueba : ${result}`);
                    cantidadLlamada = result.cantidadLlamadas;
                    indicePosponer = result.posicionfinal
                    nombreasegurado2 = JSON.stringify(result.nombreAsegurado);
                    aseguradoString = nombreasegurado2.slice(1, -1)
                    console.log(aseguradoString);
                    if (cantidadLlamada >= 2) {
                        clearInterval(downloadTimer);
                        fn_ListarBusqueda();


                        swal.fire({
                            title: aseguradoString,
                            text: "Es la llamada nro:" + " " + result.cantidadLlamadas,
                            buttonsStyling: false,
                            showCancelButton: true,
                            showDenyButton: true,
                            showCloseButton: true,
                            html: 'Es la llamada nro <strong></strong> ' + result.cantidadLlamadas +
                                '.<br/><br/>' +
                                '<button  class="btn btn-warning" onclick="  llamarTv()">' +
                                'reellamado!' +
                                '</button><br/><br/>',
                            confirmButtonText: "<i class='fa fa-forward' aria-hidden='true'></i></i>Posponer",
                            cancelButtonText: "<i class='fa fa-stethoscope' aria-hidden='true'></i>Atender",
                            denyButtonText: "<i class='fa fa-stop-circle' aria-hidden=true'></i>Cancelar",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            customClass: {
                                actions: 'my-actions',
                                confirmButton: 'order-2  btn btn-success',
                                cancelButton: 'order-1 right-gap btn btn-success',
                                closeButton: "cerrar",
                                denyButton: 'order-3  btn btn-danger',
                                icon: 'tamanioincon',


                            }

                        }).then(resultado => {

                            if (resultado.isConfirmed) {
                                console.log("el resultado es" + JSON.stringify(resultado))
                                $.ajax({
                                    "dataType": 'json',
                                    "type": "POST",
                                    "url": "/Posponerllamado",
                                    "data": {
                                        listaPresencial: result.ids,
                                        indicePosponer: indicePosponer
                                    },
                                    "success": function(result) {
                                        if (result.indice > 0) {
                                            localStorage.setItem('mantenerCantidad', 1);
                                            if (result.boleanoLastAlert == true) {
                                                Swal.fire({
                                                    title: "se envia al final de la cola",
                                                    confirmButtonText: "Aceptar",
                                                    allowOutsideClick: false,
                                                    allowEscapeKey: false,
                                                    customClass: {
                                                        confirmButton: "btnsiguiente btn-success",


                                                    }
                                                }).then(resultado => {
                                                    if (resultado.value) {
                                                        posponer = 1;
                                                        fn_ListarBusqueda();
                                                    }
                                                });
                                            } else {
                                                posponer = 1;
                                                fn_ListarBusqueda();

                                            }
                                        } else {}
                                    },
                                    "error": function(msg, textStatus, errorThrown) {}
                                });
                            } else if (resultado.isDismissed) {
                                if (resultado.dismiss == 'close') {
                                    Swal.close();
                                } else {
                                    window.location.href = "/formulario_atencion/" + encodeURIComponent(
                                        result.ticket);
                                }
                                //ATENDER


                            } else if (resultado.isDenied) {
                                clearInterval(downloadTimer);
                                console.log(JSON.stringify(result.ids));
                                $.ajax({
                                    "dataType": 'json',
                                    "type": "POST",
                                    "url": "/desaparecervalortv",
                                    "data": {
                                        ids: result.ids,
                                    },
                                    "success": function(result) {

                                        console.log("funciono");
                                    }
                                });
                                document.getElementById("countdown").innerHTML = "cancelado";


                            } else if (resultado.isCancel) {


                            }


                        });
                    } else {
                        clearInterval(downloadTimer);
                        fn_ListarBusqueda();



                        swal.fire({
                            title: aseguradoString,
                            text: "Es la llamada nro:" + " " + result.cantidadLlamadas,
                            buttonsStyling: false,
                            showCancelButton: true,
                            showDenyButton: true,
                            showCloseButton: true,
                            confirmButtonText: "<i class='fa fa-forward' aria-hidden='true'></i></i>Rellamado",
                            cancelButtonText: "<i class='fa fa-stethoscope' aria-hidden='true'></i>Atender",
                            denyButtonText: "<i class='fa fa-stop-circle' aria-hidden=true'></i>Cancelar",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            customClass: {
                                actions: 'my-actions',
                                confirmButton: 'order-2  btn btn-success',
                                cancelButton: 'order-1 right-gap btn btn-success',
                                closeButton: "cerrar",
                                denyButton: 'order-3  btn btn-danger',
                                icon: 'tamanioincon',


                            }

                        }).then(resultado => {

                            if (resultado.isConfirmed) {
                                llamarTv();
                            } else if (resultado.isDismissed) {
                                if (resultado.dismiss == 'close') {
                                    Swal.close();
                                } else {
                                    window.location.href = "/formulario_atencion/" + encodeURIComponent(
                                        result.ticket);
                                }
                                //ATENDER


                            } else if (resultado.isDenied) {
                                clearInterval(downloadTimer);
                                console.log(JSON.stringify(result.ids));
                                $.ajax({
                                    "dataType": 'json',
                                    "type": "POST",
                                    "url": "/desaparecervalortv",
                                    "data": {
                                        ids: result.ids,
                                    },
                                    "success": function(result) {

                                        console.log("funciono");
                                    }
                                });
                                document.getElementById("countdown").innerHTML = "cancelado";


                            } else if (resultado.isCancel) {
                                alert("se cerro");

                            }


                        });


                    }
                }
            });
        }


        function modalAsignarConsultorio() {
            var msg = "";
            var cantht = $('#Grd2 tbody tr td').length;
            var fecha_ini = $("#fecha_ini").val();
            var fecha_fin = $("#fecha_fin").val();

            if (cantht == 0) {
                msg += "Debe seleccionar minimo una Cita <br>";
            }
            if (fecha_ini != fecha_fin) {
                msg += "Para asignar consultorio la Fecha desde y la Fecha hasta debe ser iguales <br>";
            }
            if (msg != "") {
                bootbox.alert(msg);
                return false;
            }

            $(".modal-dialog").css("width", "80%");
            $('#openOverlayOpc').modal('show');
            $('#openOverlayOpc .modal-body').css('height', 'auto');

            $.ajax({
                url: "modal_asignar_consultorio",
                type: "GET",
                success: function(result) {
                    $("#diveditpregOpc").html(result);
                }
            });

        }

        function reanudar() {
            alert("entro");
            e.preventDefault();
            return;
            let tiempoPausa = timeleft;
            validacionFechasHoy = listaPresencial.filter(e => e.dia == fechaActualString);
            if (validacionFechasHoy.length == listaPresencial.length) {
                if (dni_beneficiario2 != "") {
                    clearInterval(downloadTimer);
                    document.getElementById("countdown").innerHTML = "filtro personalizado(Pausa)";
                } else {
                    if (cantidadllamadasTimer < 1 && soloIpressLuisNSaes == true && autollamada == true) {
                        temporizad();
                        document.getElementById("countdown").innerHTML = "llamando";
                    }

                    if (cantidadllamadasTimer >= 1 && soloIpressLuisNSaes == true && autollamada == true) {
                        downloadTimer = setInterval(function() {

                            if (tiempoPausa <= 0) {
                                clearInterval(downloadTimer);
                                document.getElementById("countdown").innerHTML = "llamando";
                                temporizad();
                            } else {
                                document.getElementById("countdown").innerHTML = tiempoPausa +
                                    " segundos de espera";
                            }
                            tiempoPausa -= 1;


                        }, 1000);
                    }
                }

            } else {
                clearInterval(downloadTimer);
                document.getElementById("countdown").innerHTML = "detenido";
                Swal.fire({
                    title: "Solo puedo buscar hacer el llamado a pacientes del dia actual",
                    text: "Esta ventana se cerrara en 5 segundos",
                    timer: 3000,
                    onOpen: function() {
                        Swal.showLoading()
                    }
                }).then(function(result) {
                    if (result.dismiss === "timer") {
                        console.log("Esta ventana se cerrar en 5 segundos")

                    }
                })
                return;
            }
        }



        //$('#pausa').click(function (e) {
        // e.preventDefault();
        //   document.getElementById('pausa').style.display='none' ;

        //     var button = '<a href="javascript:void(0)" onclick="reanudar()" class="btn btn-success btn-xs">';
        //       $('#botones').append(button);
        //   });



        setInterval(function() {
              datatablenew();
          }, 30000);

        function temporizad() {

            //solo deberia funcionar para 2 estados
            if (disableBoton == false) {
                if (autollamada == true && (validacionEstado == "1" || validacionEstado == "10")) {
                    setTimeout(() => {
                        autollamadanext2 = llamarTv();
                        console.log("si es que la llamada es true llama primera vez");
                    }, 1000);

                }


            }


        }

        function contador() {

            let valoraid = idnestor;
            dni_beneficiario2 = $('#dni_beneficiario').val();
            validacionFechasHoy = listaPresencial.filter(e => e.dia == fechaActualString);
            if (soloIpressLuisNSaes == true && (valoraid == 2324 || valoraid == 2327)) {
                if (posponer == 0) {
                    if (validacionFechasHoy.length == listaPresencial.length) {
                        if (dni_beneficiario2 != "") {
                            clearInterval(downloadTimer);
                            document.getElementById("countdown").innerHTML = "filtro personalizado(Pausa)";
                        } else {
                            if (cantidadllamadasTimer < 1 && soloIpressLuisNSaes == true && autollamada == true) {
                                temporizad();
                                document.getElementById("countdown").innerHTML = "llamando";
                            }
                            timeleft = 60;
                            if (cantidadllamadasTimer >= 1 && soloIpressLuisNSaes == true && autollamada == true) {
                                downloadTimer = setInterval(function() {

                                    if (timeleft <= 0) {
                                        clearInterval(downloadTimer);
                                        document.getElementById("countdown").innerHTML = "llamando";
                                        temporizad();
                                    } else {
                                        document.getElementById("countdown").innerHTML = timeleft +
                                            " segundos de espera";
                                    }
                                    timeleft -= 1;


                                }, 1000);
                            }
                        }

                    } else {
                        clearInterval(downloadTimer);
                        document.getElementById("countdown").innerHTML = "detenido";
                        Swal.fire({
                            title: "Solo puedo buscar hacer el llamado a pacientes del dia actual",
                            text: "Esta ventana se cerrara en 5 segundos",
                            timer: 3000,
                            onOpen: function() {
                                Swal.showLoading()
                            }
                        }).then(function(result) {
                            if (result.dismiss === "timer") {
                                console.log("Esta ventana se cerrar en 5 segundos")

                            }
                        })
                        return;
                    }
                } else {
                    if (cantidadllamadasTimer >= 0 && soloIpressLuisNSaes == true && autollamada == true) {
                        temporizad();
                        document.getElementById("countdown").innerHTML = "llamando";
                    }

                }



            }
        }




        $(document).ready(function() {
            var autollamadanext2;
            console.log("la autollamada inicial es +" + "" + autollamadanext);

            $('#btnBuscar').click(function() {
                clearInterval(downloadTimer);
                fn_ListarBusqueda();
            });






            $('#example-select-all').on('click', function() {
                var rows = $('#tblCita').DataTable().rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                addMovimientoAll();
            });

            $("#id_servicio").select2({
                width: 'resolve'
            });
            $("#estado").select2({
                width: 'resolve'
            });
            $("#id_condicion_servicio").select2({
                width: 'resolve'
            });

            $("#id_consultorio").select2({
                width: 'resolve'
            });
            datatablenew();

            <?php if($id_consultorio_actual > 0){?>
            $("#id_servicio").val("<?php echo $id_consultorio_actual; ?>").prop('disabled', true).select2();
            //$("#id_consultorio").val("0").prop('disabled',true).select2();
            getSubConsultorio();
            <?php }?>
        });

        var m = 0;

        function datatablenew() {
            var oTable1 = $('#tblCita').dataTable({
                "bServerSide": true,
                "sAjaxSource": "/listar_cita_ajax",
                "bProcessing": true,
                "sPaginationType": "full_numbers",
                //"paging":false,
                "bFilter": false,
                "bSort": false,
                "info": true,
                //"responsive": true,
                "language": {
                    "url": "/js/Spanish.json"
                },
                "autoWidth": false,
                "bLengthChange": true,
                "destroy": true,
                "lengthMenu": [
                    [10, 50, 100, 200, 60000],
                    [10, 50, 100, 200, "Todos"]
                ],
                "aoColumns": [{}, ],
                "dom": '<"top">rt<"bottom"flpi><"clear">',
                "fnDrawCallback": function(json) {
                    $('[data-toggle="tooltip"]').tooltip();
                },

                "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                    var sEcho = aoData[0].value;
                    var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value))
                        .toFixed();
                    var iCantMostrar = aoData[4].value;

                    var fecha_ini = $('#fecha_ini').val();
                    var fecha_fin = $('#fecha_fin').val();
                    var fecha_reg_ini = $('#fecha_reg_ini').val();
                    var fecha_reg_fin = $('#fecha_reg_fin').val();
                    var dni_beneficiario = $('#dni_beneficiario').val();
                    var nombre_beneficiario = $('#nombre_beneficiario').val();
                    var estado = $('#estado').val();
                    var id_establecimiento = {{ Auth::user()->id_establecimiento }};
                    var id_servicio = $('#id_servicio').val();
                    var id_consultorio = $('#id_consultorio').val();
                    var id_medico = $('#id_medico').val();
                    var id_condicion_establecimiento = $('#id_condicion_establecimiento').val();
                    var id_condicion_servicio = $('#id_condicion_servicio').val();
                    var _token = $('#_token').val();
                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        //"contentType": "application/json; charset=utf-8",
                        "type": "POST",
                        "url": sSource,
                        "data": {
                            NumeroPagina: iNroPagina,
                            NumeroRegistros: iCantMostrar,
                            fecha_ini: fecha_ini,
                            fecha_fin: fecha_fin,
                            fecha_reg_ini: fecha_reg_ini,
                            fecha_reg_fin: fecha_reg_fin,
                            dni_beneficiario: dni_beneficiario,
                            nombre_beneficiario: nombre_beneficiario,
                            estado: estado,
                            id_establecimiento: id_establecimiento,
                            id_servicio: id_servicio,
                            id_consultorio: id_consultorio,
                            id_medico: id_medico,
                            id_condicion_establecimiento: id_condicion_establecimiento,
                            id_condicion_servicio: id_condicion_servicio,
                            _token: _token
                        },
                        "success": function(result) {
                            flagllamadacita = result.flagllamadacita;
                            console.log('-------IRWIN--------- ' + flagllamadacita);
                            console.log(result);
                            if (result.tipo_listado[0] == 1) {
                                let result3 = [];
                                result2 = result.aaData[0];
                                result3 = result.aaData;
                                let cantidad = result3.length;
                                if (cantidad != 0) {
                                    result2.soloIpressLuisNSaes = result.soloIpressLuisNSaes;
                                    result2.idNestor = result.idNestor
                                    idnestor = result.idNestor
                                    soloIpressLuisNSaesn2 = result.soloIpressLuisNSaes;
                                    farmacia = result.farmacia;
                                    idrutty = result.idrutty;
                                    let idapoyo = idnestor;
                                }

                                console.log(result.idNestor);
                            }


                            fnCallback(result);
                            const button = document.getElementById('checkout-button');
                            disableBoton = result.disabledButton;
                            console.log("el boton sera " + disableBoton);

                            if (result.tipo_listado[0] == 1) {
                                listaPresencial = result.aaData;
                            }
                            ids = result.ids;
                            let fechahoy = new Date();
                            fechaActualString = (fechahoy.getDate()).toString().padStart(2, '0') +
                                "/" + (fechahoy.getMonth() + 1).toString().padStart(2, '0') + "/" +
                                fechahoy.getFullYear()

                            //si es 0 es que esta vacio
                            boleanovacio = ids.length;



                            autollamada = result.autollamada;

                            cantidadllamadasTimer = result.cantidadllamadas;

                            soloIpressLuisNSaes = result.soloIpressLuisNSaes;
                            if (disableBoton == true) {
                                document.getElementById("btn_tv_llamar").style.display = "none";
                            }
                            // if(farmacia ==121){
                            //    document.getElementById("atenderpip").style.display = "none";
                            // }

                            if (soloIpressLuisNSaes == true) {
                                document.getElementById("estadoRe").innerHTML = "Totem";
                                //   document.getElementById("estadoRePr").style.display = "none";
                            }
                            validacionEstado = $('#estado').val();
                            if (validacionEstado != "1" && validacionEstado != "10") {
                                document.getElementById("btn_tv_llamar").disabled = true;
                                clearInterval(downloadTimer);
                                document.getElementById("countdown").innerHTML = "detenido";
                            } else {
                                document.getElementById("btn_tv_llamar").disabled = false;
                            }


                            $("#ipressCorrecta").val(soloIpressLuisNSaes);
                            contador();





                        },
                        "error": function(msg, textStatus, errorThrown) {
                            //location.href="login";
                        }
                    });
                },

                "aoColumnDefs": [{
                        "mRender": function(data, type, row) {

                            var id = "";
                            if (row.id != null) id = row.id;
                            var newRow = "<input type='checkbox' value='" + row.id +
                                "' name='id_cita[]' codigo='" + id + "' id='movimientos[" + m +
                                "].id' onchange=addMovimiento(" + row.id + ",'" + id + "',this) />";
                            m++;
                            return newRow;
                        },
                        "bSortable": false,
                        "className": "dt-center",
                        "aTargets": [0],
                        "visible": <?php echo $flagemergenciareferencia == 1 ? 'true' : 'false'; ?>,
                    },
                    {
                        "mRender": function(data, type, row) {
                            var id = "";
                            if (row.id != null) id = row.id;
                            return id;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var dia = "";
                            if (row.dia != null) dia = row.dia;
                            return dia;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var hora = "";
                            if (row.hora != null) hora = row.hora;
                            return hora;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var dia_registro = "";
                            if (row.dia_registro != null) dia_registro = row.dia_registro;
                            return dia_registro;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var hora_ticket = "";
                            if (row.hora_ticket != null) hora_ticket = row.hora_ticket;
                            return hora_ticket;
                        },
                        "bSortable": false,
                        "aTargets": [5],
                        "visible": <?php echo Auth::user()->id_establecimiento == 76 ? 'true' : 'false'; ?>
                    },
                    {
                        "mRender": function(data, type, row) {
                            var tipo_doc_ident = "";
                            if (row.tipo_doc_ident != null) tipo_doc_ident = row.tipo_doc_ident;
                            return tipo_doc_ident;
                        },
                        "bSortable": false,
                        "aTargets": [6]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var nro_doc_ident = "";
                            if (row.nro_doc_ident != null) nro_doc_ident = row.nro_doc_ident;
                            return nro_doc_ident;
                        },
                        "bSortable": false,
                        "aTargets": [7],
                        "className": 'control'
                    },
                    {
                        "mRender": function(data, type, row) {
                            var nro_historia = "";
                            var newRow = "";
                            if (row.nro_historia != null) newRow += row.nro_historia;
                            <?php //if($flagemergenciareferencia == 1):?>
                            // newRow +=
                            //     '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">';
                            // newRow += '<a href="javascript:void(0)" onclick="modalHistoria(' + row
                            //     .id_asegurado_historia + ')" class="btn btn-success btn-xs">';
                            // newRow += '<i class="fa fa-edit"></i></a></div>';
                            <?php //endif;?>
                            return newRow;
                        },
                        "bSortable": false,
                        "aTargets": [8]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var asegurado = "";
                            if (row.asegurado_paterno != null) asegurado += row.asegurado_paterno;
                            if (row.asegurado_materno != null) asegurado += " " + row.asegurado_materno;
                            if (row.asegurado_nombre != null) asegurado += ", " + row.asegurado_nombre;
                            return asegurado;
                        },
                        "bSortable": false,
                        "aTargets": [9]
                    },
                    {
                        "mRender": function(data, type, row) {

                            //var telefono = "";
                            //if(row.telefono!= null)telefono = row.telefono;
                            //return telefono;
                            var telefono = "";
                            var newRow = "";
                            if (row.telefono != null) newRow += row.telefono;
                            <?php if($flagemergenciareferencia == 1):?>
                            newRow +=
                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">';
                            newRow += '<a href="javascript:void(0)" onclick="modalTelefono(' + row
                                .id_asegurado_historia + ')" class="btn btn-success btn-xs">';
                            newRow += '<i class="fa fa-edit"></i></a></div>';
                            <?php endif;?>
                            return newRow;
                        },
                        "bSortable": false,
                        "aTargets": [10]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var telefono2 = "";
                            var newRow = "";
                            if (row.telefono2 != null) newRow += row.telefono2;
                            <?php if($flagemergenciareferencia == 1):?>
                            newRow +=
                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">';
                            newRow += '<a href="javascript:void(0)" onclick="modalTelefono(' + row
                                .id_asegurado_historia + ')" class="btn btn-success btn-xs">';
                            newRow += '<i class="fa fa-edit"></i></a></div>';
                            <?php endif;?>
                            return newRow;
                        },
                        "bSortable": false,
                        "aTargets": [11]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var usuario_cita = "";
                            if (row.usuario_cita != null) usuario_cita = row.usuario_cita;
                            return usuario_cita;
                        },
                        "bSortable": false,
                        "aTargets": [12]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var servicio = "";
                            if (row.servicio != null) servicio = row.servicio;
                            return servicio;
                        },
                        "bSortable": false,
                        "aTargets": [13]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var medico = "";
                            if (row.medico_paterno != null) medico += row.medico_paterno;
                            if (row.medico_materno != null) medico += " " + row.medico_materno;
                            if (row.medico_nombre != null) medico += ", " + row.medico_nombre;
                            return medico;
                        },
                        "bSortable": false,
                        "aTargets": [14]

                    },
                    {
                        "mRender": function(data, type, row) {
                            var consultorio = "";
                            if (row.consultorio != null) consultorio = row.consultorio;
                            return consultorio;
                        },
                        "bSortable": false,
                        "aTargets": [15]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var tipo_cita = "";
                            if (row.tipo_cita != null) tipo_cita = row.tipo_cita;
                            return tipo_cita;
                        },
                        "bSortable": false,
                        "aTargets": [16]
                    },
                    {
                        "mRender": function(data, type, row) {
                            var referido = "";
                            if (row.referido != null) referido += row.referido;
                            if (row.establecimiento_referencia != 0 && row.establecimiento_referencia !=
                                null) referido += " - " + row.establecimiento_referencia;

                            return referido;
                        },
                        "bSortable": false,
                        "aTargets": [17]
                    },
                    {
                        "mRender": function(data, type, row) {
                            // const dateCita = moment(row.dia, "DD/MM/YYYY").format("YYYY-MM-DD");
                            // const currentDate = moment();
                            // const diferenciaEnDias = parseInt(currentDate.diff(dateCita, 'days')) || 0;
                            // const fecha_actualizacion_siteds = moment(row.fecha_actualizacion_siteds, "DD/MM/YYYY") || currentDate.format("DD/MM/YYYY");

                            let typeLabel = "label-success";
                            let showTooltip = "";
                            let htmlString = "";
                            let titleBtn = "Generar codigo siteds";

                            if (String(row.codigo_siteds).includes("T") || String(row.codigo_siteds).length <= 10) {
                                typeLabel = "label-warning";
                                showTooltip = 'data-toggle="tooltip" data-placement="top" title="Código Siteds Temporal"';
                                titleBtn = "Regularizar codigo siteds";
                            }

                            if (row.codigo_siteds != null) {
                                htmlString += `<p ${showTooltip}><span class="label ${typeLabel}" style='font-weight: lighter;'">${String(row.codigo_siteds).toUpperCase()}</span></p>`;
                            }

                            if (!row.codigo_siteds || String(row.codigo_siteds).includes("T") || String(row.codigo_siteds).length <= 10 ) {
                                /* htmlString += `
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="${titleBtn}" style="float:right">
                                        <a href="javascript:void(0)" onclick="generarCodigoSiteds(${row.id}, ${row.id_asegurado})" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                    </div>`; */
                                htmlString += `
                                    <button type="button" class="btn btn-success btn-xs"onclick="onClickRegularizeSiteds(${row.id}, ${row.id_asegurado}, ${row.consultorio_id}, '${row.dia}')" data-toggle="tooltip" data-placement="top" data-html="true" title="${titleBtn}">
                                        <i class="fa fa-edit"></i>
                                    </button>`;
                            }

                            return htmlString;

                            
                            
                            // const currentDate = moment();
                            //const diferenciaEnDias = parseInt(currentDate.diff(fecha_actualizacion_siteds, 'days')) || 0;
                           /*  if (diferenciaEnDias > 0) {
                                newRow = "";
                                row.codigo_siteds = null;
                            }
                            if (!row.codigo_siteds || String(row.codigo_siteds).includes("T")) {
                                <?php if($flagemergenciareferencia == 1):?>
                                    if (String(row.dia) === String(currentDate.format("DD/MM/YYYY"))) {
                                        if (row.codigo_siteds == null){
                                        newRow += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">';
                                            // newRow += '<a href="javascript:void(0)" onclick="modalSiteds(' + row.id +',' + row.id_asegurado +')" class="btn btn-success btn-xs">';
                                            newRow += '<a href="javascript:void(0)" onclick="generarCodigoSiteds(' + row.id + ', ' + row.id_asegurado +', ' + row.consultorio_id + ')" class="btn btn-success btn-xs">';
                                            newRow += '<i class="fa fa-edit"></i></a></div>';
                                        } else{
                                            if (row.id_estado_cita == 4){
                                            newRow +=
                                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">';
                                            //newRow += '<a href="javascript:void(0)" onclick="modalSiteds(' + row.id +',' + row.id_asegurado +')" class="btn btn-warning btn-xs">';
                                            newRow += '<a href="javascript:void(0)" onclick="generarCodigoSiteds(' + row.id + ', ' + row.id_asegurado +', ' + row.consultorio_id + ')" class="btn btn-warning btn-xs">';
                                            newRow += '<i class="fa fa-edit"></i></a></div>';

                                            }
                                            else{
                                                newRow +=
                                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>" style="float:right">';
                                                //newRow += '<a href="javascript:void(0)" onclick="modalSiteds(' + row.id +',' + row.id_asegurado +')" class="btn btn-warning btn-xs">';
                                                newRow += '<a href="javascript:void(0)" onclick="generarCodigoSiteds(' + row.id + ', ' + row.id_asegurado +', ' + row.consultorio_id + ')" class="btn btn-warning btn-xs">';
                                                newRow += '<i class="fa fa-edit"></i></a></div>';
                                            }
                                        }
                                    }  
                                <?php endif;?> 
                            }

                           
                            return newRow; */
                        },
                        "bSortable": false,
                        "aTargets": [18]
                    },
                    {

                        "mRender": function(data, type, row) {
                            let result3 = [];
                            result2 = row;
                            if (result2 != null) {
                                result2.soloIpressLuisNSaes = soloIpressLuisNSaesn2;
                                result2.idNestor = idnestor
                                result2.farmacia = farmacia;
                            }
                            var html = "";
                            if (row.soloIpressLuisNSaes == true) {
                                if (row.id_estado_cita == 1 || row.id_estado_cita == 8 || row
                                    .id_estado_cita == 9) {
                                    if (row.estado_presencia == '1' || row.estado_presencia == '2') {
                                        html = "<span class='presente'>Presente</span>";
                                    } else if (row.estado_presencia == false) {
                                        html = "<span class='presente'>Ausente</span>";
                                    }
                                }
                            } else {
                                if (row.id_estado_cita == 1 || row.id_estado_cita == 8 || row
                                    .id_estado_cita == 9) {
                                    if (row.estado_presencia == '1') {
                                        html = "<span class='presente'>En Triaje</span>";
                                    } else if (row.estado_presencia == '2') {
                                        html = "<span class='presente'>Presente</span>";
                                    }
                                }
                            }
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [19]/*,
                        "visible": <?php echo $flagemergenciareferencia == 1 ? 'false' : 'true'; ?>*/
                    },
                    {
                        "mRender": function(data, type, row) {
                            let result3 = [];
                            result2 = row;
                            if (result2 != null) {
                                result2.soloIpressLuisNSaes = soloIpressLuisNSaesn2;
                                result2.idNestor = idnestor
                            }
                            var html = "";
                            if (row.soloIpressLuisNSaes != true) {
                                if (row.id_estado_cita == 1 || row.id_estado_cita == 8 || row
                                    .id_estado_cita == 9 || row.id_estado_cita == 10) {
                                    if (row.estado_presencia == '1' || row.estado_presencia == '2') {
                                        html = "<span class='presente'>En Totem</span>";
                                    } else if (row.estado_presencia == false) {
                                        html = "<span class='presente'>Ausente</span>";
                                    }
                                }
                            }

                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [20],
                        "visible": <?php echo $flagemergenciareferencia == 1 || $soloIpressLuisNSaes == 1 ? 'false' : 'true'; ?>
                    },
                    {
                        "mRender": function(data, type, row) {
                            var newRow = "";
                            <?php if ($flagemergenciareferencia != 1) : ?>
                            if (row.id_estado_cita == 1 || row.id_estado_cita == 8 || row.id_estado_cita ==
                                9 || row.id_estado_cita == 10) {
                                <?php if(Auth::user()->tipo_registro==1): ?>
                                newRow +=
                                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar</b>">';
                                newRow +=
                                    '<a href="javascript:void(0)" onclick="modalForm(\'modal_comentario/' +
                                    row.id + '\')" class="btn btn-info btn-xs">';
                                newRow += '<i class="fa fa-search"></i></a></div>';
                                <?php else: ?>
                                newRow +=
                                    '<button class="btn btn-info btn-xs" disabled=""><i class="fa fa-search"></i></button>';
                                <?php endif; ?>

                            } else {
                                newRow +=
                                    '<button class="btn btn-info btn-xs" disabled=""><i class="fa fa-search"></i></button>';
                            }
                            <?php endif; ?>
                            return newRow;
                        },
                        "bSortable": false,
                        "visible": <?php echo $flagemergenciareferencia == 1 ? 'false' : 'true'; ?>,
                        "aTargets": [21],
                        "className": "text-center"
                    },

                    {
                        "mRender": function(data, type, row) {
                            let result3 = [];
                            result2 = row;
                            if (result2 != null) {
                                result2.soloIpressLuisNSaes = soloIpressLuisNSaesn2;
                                result2.idNestor = idnestor
                                result2.farmacia = farmacia;

                            }
                            var html = "";



                            console.log("flag =>" + flagllamadacita);
                            let tipo_registro = "{{ Auth::user()->tipo_registro }}";
                            console.log(tipo_registro);
                            if (flagllamadacita == 1 && row.estado_presencia == 1 && row.farmacia == 121 &&
                                tipo_registro == 1) {
                                html +=
                                    '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>';
                            } else {
                                html +=
                                    '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Atender Cita</b>">';
                                // Cita de procedimiento programado se atiende en atender_procprogramado
                                const urlAtencion = row.url_atencion_pp ? row.url_atencion_pp : '/formulario_atencion/' + row.id;
                                html += '<a href="' + urlAtencion +
                                    '" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';
                            }


                            /* else {

                                if (row.id_estado_cita == 1 || row.id_estado_cita == 8 || row
                                    .id_estado_cita == 9 || row.id_estado_cita == 10) {
                                    <?php if(Auth::user()->tipo_registro==1): ?>
                                    html +=
                                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Atender Cita</b>">';
                                    html += '<a href="/formulario_atencion/' + row.id +
                                        '" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a></div>';
                                    <?php else: ?>

                                    html +=
                                        '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>';
                                    <?php endif; ?>
                                } else {
                                    html +=
                                        '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>';
                                }

                            } */



                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [22],
                        "visible": <?php echo $flagemergenciareferencia == 1 ? 'false' : 'true'; ?>,
                        "className": "text-center",
                    },
                    {
                        "mRender": function(data, type, row) {
                            var html = "";
                            // if (row.id_estado_cita == 1 || row.id_estado_cita == 8 || row.id_estado_cita ==
                            //     9 || row.id_estado_cita == 10) {
                            //     html +=
                            //         '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Cita</b>">';
                            //     html +=
                            //         '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularCita(' +
                            //         row.id + ')"><i class="fa fa-remove"></i></a></div>';
                            // } else {
                            //     html +=
                            //         '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
                            // }
                            if (row.id_estado_cita == 1 || row.id_estado_cita == 8 || row.id_estado_cita == 9 || row.id_estado_cita == 10) {
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Cita</b>">';
                                if (row.consultorio_id == 114) {
                                    html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularCitaradiologia(' + row.id + ')"><i class="fa fa-remove"></i></a></div>';
                                } else {
                                    html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="anularCita(' + row.id + ')"><i class="fa fa-remove"></i></a></div>';
                                }
                            } else {
                                html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
                            }
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [23],
                        //"visible": (((<?php echo $flagemergenciareferencia == 1; ?>==1) && ($('#estado').val()==0 || $('#estado').val()==7)) || (<?php echo $flagemergenciareferencia == 1; ?>!=1))?true:false,
                        //"visible": (((1==1) && ($('#estado').val()==0 || $('#estado').val()==7)) || (1!=1))?true:false,
                        "visible": <?php echo $flagemergenciareferencia == 1 ? 'false' : 'true'; ?>,
                        "className": "text-center",
                    },
                    {
                        "mRender": function(data, type, row) {
                            var html = "";
                            if (row.id_estado_cita == 1 || row.id_estado_cita == 8 || row.id_estado_cita ==
                                9 || row.id_estado_cita == 10) {
                                <?php if(Auth::user()->tipo_registro==9): ?>
                                if (row.id_estado_cita != 9) {
                                    if (row.estado_triaje == '1') {
                                        html +=
                                            '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Triaje</b>">';
                                        html +=
                                            '<a href="javascript:void(0)" class="btn btn-success btn-xs" onClick="modalTriaje(' +
                                            row.id + ')"><i class="fa fa-stethoscope"></i></a></div>';
                                    } else {
                                        html +=
                                            '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa fa-stethoscope"></i></button>';
                                    }
                                } else {
                                    html +=
                                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar Triaje</b>">';
                                    html +=
                                        '<a href="javascript:void(0)" class="btn btn-warning btn-xs" onClick="modalEditarTriaje(' +
                                        row.id + ')"><i class="fa fa-stethoscope"></i></a></div>';
                                }
                                <?php else: ?>
                                html +=
                                    '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa fa-stethoscope"></i></button>';
                                <?php endif; ?>
                            } else {
                                html +=
                                    '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa fa-stethoscope"></i></button>';
                            }
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [24],
                        "visible": <?php echo Auth::user()->tipo_registro == 9 && $flagemergenciareferencia == 0 ? 'true' : 'false'; ?>
                    }

                ]


            });

        }

        function fn_ListarBusqueda() {
            clearInterval(downloadTimer);
            datatablenew();
        };

        function modalTriaje(id_cita) {
            $.ajax({
                url: '/modal_ficha_triaje/' + id_cita,
                type: "GET",
                success: function(result) {
                    $("#divModalTriaje").html(result);
                }
            });
            $(".modal-dialog").css("width", "80%");
            $('#modalForm2').modal('show');
        }


        function modalEditarTriaje(id_cita) {
            $.ajax({
                url: '/modal_ficha_triaje/' + id_cita,
                type: "GET",
                success: function(result) {
                    $("#divModalTriaje").html(result);
                }
            });
            $(".modal-dialog").css("width", "80%");
            $('#modalForm2').modal('show');
        }


        function addMovimiento(id, codigo, obj) {

            var newRow = "";

            if ($(obj).is(':checked')) {
                var pht = 0;
                $("input[name='mov']").each(function() {
                    var rs = $(this).val().toString()
                    if (rs == id) {
                        pht++;
                    }
                });

                if (pht == 0) {
                    newRow +=
                        "<td style='padding-right:0px;float:left;border:0px'><input name='mov' type='hidden' value='" + id +
                        "' /><span class='label alert-warning'>" + codigo + "</span></td>";
                    $('#Grd2 tbody tr').append(newRow);
                }

                var cantht = $('#Grd2 tbody tr td').length;
                $('#spanContador').html(cantht);

            } else {
                var cantht = $('#Grd2 tbody tr td').length;
                if (cantht > 0) {
                    $("input[name='mov']").each(function() {
                        var rs = $(this).val().toString()
                        if (rs == id) {
                            $(this).parent().remove();
                        }
                    });
                }
                cantht = $('#Grd2 tbody tr td').length;
                $('#spanContador').html(cantht);
            }

        }

        function addMovimientoAll() {

            var newRow = "";

            if ($("input[name='select_all']").is(':checked')) {
                var pht = 0;
                $("input[name='id_cita[]']").each(function() {
                    var id = $(this).val().toString();
                    var codigo = $(this).attr("codigo").toString();
                    newRow = "<td style='padding-right:0px;float:left'><input name='mov' type='hidden' value='" +
                        id + "' /><span class='label alert-warning'>" + codigo + "</span></td>";
                    $('#Grd2 tbody tr').append(newRow);
                });

            } else {
                var cantht = $('#Grd2 tbody tr td').length;
                if (cantht > 0) {
                    $("input[name='id_cita[]']").each(function() {
                        var id = $(this).val().toString();
                        $("input[name='mov']").each(function() {
                            var rs = $(this).val().toString()
                            if (rs == id) {
                                $(this).parent().remove();
                            }
                        });
                    });
                }
            }

            var cantht = $('#Grd2 tbody tr td').length;
            $('#spanContador').html(cantht);

        }

        function completarConCeros(numero, longitud) {
          let numeroString = numero.toString();
          while (numeroString.length < longitud) {
            numeroString = '0' + numeroString;
          }
          return numeroString;
        }

        function esEntero(evt){     
            // code is the decimal ASCII representation of the pressed key.
            var code = (evt.which) ? evt.which : evt.keyCode;
            
            if(code==8) { // backspace.
            return true;
            } else if(code>=48 && code<=57) { // is a number.
            return true;
            } else{ // other keys.
            return false;
            }
        }

        function validateSitedsFrm(){   

            var msg = "";
            var cod_siteds= $('#codigo_siteds').val();  
            if ($('#frmsiteds').find("#codigo_siteds").val() == "") {
                bootbox.alert("Recuerde que tiene que Ingresar el CÓDIGO SITEDS", function(){     
                document.getElementById("codigo_siteds").click();
                  setTimeout(function (){ 
                    $('#codigo_siteds').focus();
                }, 500);
              });
              return false;
            }else{
              ///tomamos el numero ingresado en el codigo siteds
              $("#codigo_siteds").val(completarConCeros(cod_siteds,12));  
            }
            
            $("#frmsiteds").submit();
            /*var codSiteds= $('#codigo_siteds').val();  
              $.ajax({
                url:'{{url('validar_dato_siteds_ambulatorio/')}}/'+codSiteds,
                type:'GET',
                dataType:'JSON', 
                  success: function(data){   
                    if(data!=null){ 
                      console.log('data[0].cantidad_siteds:'+ data.cantidad_siteds); 
                      if(data.cantidad_siteds!=0){ 
                          Swal.fire({
                            title: '<div class="titulo-padding"><p>Alerta de Registro </p></div>',
                            html: '<p class="descripcion-alert"><span>EL código SITEDS <b style="color:red;">'+codSiteds+'</b> YA EXISTE, verifique que sea correcto y vuelva a intentarlo.<br><br></span></p>', 
                            allowOutsideClick: false,
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                          }); 
                          $('#codigo_siteds').val(''); 
                          setTimeout(function (){ 
                              $('#codigo_siteds').focus();
                          }, 2500);
                      } else{ 
                        $("#frmsiteds").submit();
                        return false;
                      } 
                    } else{ 
                      $("#frmsiteds").submit();
                      return false;
                    }
                  }
              });*/
        };


        function getSubConsultorio() {

            var id_establecimiento = $('#id_establecimiento').val();
            var id_servicio = $('#id_servicio').val();
            //var id_tipo_atencion = $('#id_tipo_atencion').val();
            $.ajax({
                url: '/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/' + id_establecimiento + '/' +
                    id_servicio,
                success: function(result) {
                    var newOption = "";
                    var reg = result.length;
                    if (reg >= 2) {
                        newOption = "<option value='0'>--Seleccionar-</option>";
                    }
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                    });
                    $('#id_consultorio').html(newOption);
                    $("#id_consultorio").select2({
                        max_selected_options: 4
                    });

                }
            });

        }

        function generarRptParaCallcenter(idUser) {

            let estado = $("#estado").val();

            if (estado == 1) {
                var fecha_ini = $('#fecha_ini').val();
                var fecha_fin = $('#fecha_fin').val();
                var fecha_reg_ini = $('#fecha_reg_ini').val();
                var fecha_reg_fin = $('#fecha_reg_fin').val();
                var dni = $('#dni_beneficiario').val();
                var paciente = $('#nombre_beneficiario').val();
                var id_servicio = $('#id_servicio').val();
                var id_consultorio = $('#id_consultorio').val();
                var id_medico = $('#id_medico').val();
                var id_condicion_establecimiento = $('#id_condicion_establecimiento').val();
                var id_condicion_servicio = $('#id_condicion_servicio').val();

                if (fecha_ini == "") fecha_ini = 0;
                if (fecha_fin == "") fecha_fin = 0;
                if (fecha_reg_ini == "") fecha_reg_ini = 0;
                if (fecha_reg_fin == "") fecha_reg_fin = 0;
                if (dni == "") dni = 0;
                if (paciente == "") paciente = 0;
                if (estado == "") estado = 0;
                if (id_servicio == "") id_servicio = 0;
                if (id_consultorio == "") id_consultorio = 0;
                if (id_medico == "") id_medico = 0;
                if (id_condicion_establecimiento == "") id_condicion_establecimiento = 0;
                if (id_condicion_servicio == "") id_condicion_servicio = 0;
                location.href = 'exportar_reporte_para_callcenter/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + fecha_reg_ini +
                    '/' + fecha_reg_fin + '/' + dni + '/' + paciente + '/' + estado + '/' + id_servicio + '/' + id_consultorio +
                    '/' + id_medico + '/' + id_condicion_establecimiento + '/' + id_condicion_servicio;
            } else {
                bootbox.alert('La exportación solo se encuentra habilitada para paciente Programados.');
            }
        }
        
        async function onClickRegularizeSiteds(id, aseguradoId, servicioId, dateCita) {
            const dateCitaMoment = moment(dateCita, "DD/MM/YYYY");
            const currentDate = moment();
            if (dateCitaMoment.isSame(currentDate, 'day')) {
                generarCodigoSiteds(id, aseguradoId, servicioId);
            } else {
                const session = {!! json_encode(Auth::user()) !!}
                const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
                if (hasPermissionUpdateSitedsManual) {
                    const resultModal = await showModalSitedsManual();
                    if (resultModal && resultModal.isConfirmed && resultModal.value) {
                        handlerSaveCodeSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.", 200);
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>PLAZO DE ACREDITACIÓN EXPIRADO</span></div>",
                        html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Lamentamos informarle que el plazo para completar la acreditación ha expirado. La fecha límite para la acreditación era el <b>${dateCita}</b>.</span></div>`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                }
            }
        }

        // TAGSITEDS
        function generarCodigoSiteds(id, aseguradoId, servicioId) {
            Swal.fire ({
                title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
                html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            })
            const id_establecimiento = $("#id_establecimiento").val();
            const body = {
                "idCorrelativo": id,
                "aseguradoId": aseguradoId,
                "establecimientoId": id_establecimiento,
                "tipoAtencionId": "19",
                "servicioId": servicioId
            };
            $.ajax({
                url:'/v2/ws-dirsapol-siteds',
                type:'POST',
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify(body),
                success: (result, textStatus, jqXHR) => {
                    if (result && result.nuAutorizacion && (parseInt(jqXHR.status) === 200 || parseInt(jqXHR.status) === 201)) {
                        handlerSaveCodeSiteds(id, result.nuAutorizacion, result.message, jqXHR.status);
                    } else {
                        const session = {!! json_encode(Auth::user()) !!}
                        const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
                        if (hasPermissionUpdateSitedsManual) {
                          showModalSitedsManual()
                          .then((resultModal) => {
                            if (resultModal && resultModal.isConfirmed && resultModal.value) {
                              handlerSaveCodeSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.", 200);
                            }
                          })
                        } else {
                          Swal.fire({
                            icon: 'error',
                            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                            html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                                    <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para generar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                                    </div>`,
                            confirmButtonText: 'ENTENDIDO',
                          });
                        }
                    }
                },
                error: function(xhr, status, error){
                    const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                    console.log("[xhr.responseJSON] => ", xhr.responseJSON);
                    Swal.fire({
                        icon: 'error',
                        title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                        html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                }, 
                complete: function(xhr, status) {
                    Swal.hideLoading();
                }
            })
        }

        function handlerSaveCodeSiteds(id, nuAutorizacion, message = null, status = null) {
            Swal.fire ({
                title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
                html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Guardando el codigo siteds...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            })
            const auth = {!! json_encode(Auth::user()) !!};
            const actionUrl = "{{ route('update_siteds') }}";
            let frmsiteds = $('<form/>', {
                id: 'frmsiteds',
                name: 'frmsiteds',
                method: 'POST',
                action: actionUrl,
            });

            frmsiteds.append($('<input>', { type: 'hidden', name: "_token", value: "{{ csrf_token() }}" }));
            frmsiteds.append($('<input>', { type: 'text', id: 'id', name: 'id', value: id }));
            frmsiteds.append($('<input>', { type: 'text', id: 'id_user', name: 'id_user', value: auth.id }));
            frmsiteds.append($('<input>', { type: 'text', id: 'codigo_siteds', name: 'codigo_siteds', value: nuAutorizacion }));
            frmsiteds.append($('<input>', { type: 'text', id: 'retorno', name: 'retorno', value: "1" }));

            $('body').append(frmsiteds);

            frmsiteds.on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: frmsiteds.attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: (parseInt(status) === 201 || parseInt(status) === 200) ? "success" : 'warning',
                            title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>CÓDIGO SITEDS</span></div>",
                            html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>${message || " Operación Exitosa"}</span></div>`,
                            confirmButtonText: 'ENTENDIDO',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        });
                        fn_ListarBusqueda();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {},
                    complete: function(xhr, status) {
                        Swal.hideLoading();
                        frmsiteds.remove();
                    }
                }); 
            });

            frmsiteds.trigger('submit');
        };

          // TAGSITEDS
        function generarCodigoSitedsMassive() {
            Swal.fire ({
                title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZACIÓN DE CÓDIGO SITEDS MASIVO</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading ()
                },
            })
            $.ajax({
            url:'/generate-siteds-massive-citas',
            type:'POST',
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({}),
            success: (result) => {
                let htmlString = `
                    <p style="font-family: Arial, Helvetica, sans-serif; font-weight: light; font-size: 16px">${result.message || " - "}</p>
                    <hr>
                    <div style="display: flex; flex-direction: row; justify-content: center; font-size: 16px">
                        <table style="width: 70%; border-collapse: separate; border-spacing: 15px;">
                            <tr style="text-align: left">
                                <td style="border-bottom: 1px solid #060606; width: 55%">
                                    <strong><i class="fa fa-list"></i> REGISTROS TOTALES:</strong>
                                </td>
                                <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_total ?? 0}</td>
                            </tr>

                            <tr style="text-align: left">
                                <td style="border-bottom: 1px solid #060606; width: 55%">
                                    <strong><i class="fa fa-check-square-o"></i> CASOS EXITOSOS:</strong>
                                </td>
                                <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_generados_exito ?? 0}</td>
                            </tr>

                            <tr style="text-align: left">
                                <td style="border-bottom: 1px solid #060606; width: 55%">
                                    <strong><i class="fa fa-exclamation-triangle"></i> CASOS FALLIDOS:</strong>
                                </td>
                                <td style="border-bottom: 1px solid #060606; text-align: center; width: 15%">${result.cantidad_generados_error ?? 0}</td>
                            </tr>
                        </table>
                    </div>
                    
                `;
                let customClass = "";
                if (result && result.respuesta_error && result.respuesta_error.length > 0) {
                    customClass = "swal-wide";
                    let tableBodyString = "";
                    result.respuesta_error.forEach((element, index) => {
                    tableBodyString += `<tr>
                                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${index + 1}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${element.cita_id || "-"}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center">${element.nroDocIdent || "-"}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center; color: red ">${element.message || "-"}</td>
                                        </tr>`;
                    });
                    htmlString += `<div id="div_errors" >
                                    <a href="#" id="btn_show_errors" style="text-decoration: none; font-family: Arial, Helvetica, sans-serif; font-weight: 500; font-size: 14px; margin-bottom: 20px">
                                        <i class="fa fa-eye"></i> MOSTRAR DETALLES DE LAS OBSERVACIONES
                                    </a>
                                    <hr>
                                    <div style="max-height: 300px; overflow-y: auto;">
                                        <table hidden id="table_errors" style="width: 100%; text-align: center; font-size: 16px; border-collapse: collapse">
                                        <thead style="background-color: #f2f2f2; position: sticky; top: -2px; z-index: 10;">
                                            <tr>
                                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 5%">#</th>
                                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 15%">NRO. CITA</th>
                                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 15%">NRO.DOC.ASEGURADO</th>
                                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 65%">OBSERVACIÓN</th>
                                            </tr>
                                        </thead>
                                            <tbody id="table_errors_body" style="text-align: left">${tableBodyString}</tbody>
                                        </table>
                                    </div>
                                </div>`;             
                }
                Swal.fire({
                    icon: parseInt(result.cantidad_generados_exito) === 0 ? "warning" : "success",
                    title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZACIÓN DE CÓDIGO SITEDS MASIVO</span></div>",
                    html: htmlString,
                    confirmButtonText: 'ENTENDIDO',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: customClass,
                    didOpen: function() {
                        $("#btn_show_errors").click(function() {
                            $("#table_errors").toggle();
                            if ($("#table_errors").is(":visible")) {
                                $(this).html('<i class="fa fa-eye-slash"></i> OCULTAR DETALLES DE LAS OBSERVACIONES');
                            } else {
                                $(this).html('<i class="fa fa-eye"></i> MOSTRAR DETALLES DE LAS OBSERVACIONES');
                            }
                        });
                    }
                });
                fn_ListarBusqueda();
            },
            error: function(xhr, status, error){
                const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                Swal.fire({
                    icon: 'error',
                    title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
                    html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                    confirmButtonText: 'ENTENDIDO',
                });
            }, 
            complete: function(xhr, status) {
                Swal.hideLoading();
            }
            })
        }

        function showModalSitedsManual(id) {
            return new Promise((resolve, reject) => {
                Swal.fire({
                    title: "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>Regularización Manual del SITEDS</span></div>",
                    html: "<div style='padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Le informamos que, para regularizar su código SITEDS, debe generarlo en la página oficial de SITEDS. Una vez generado, ingréselo en este formulario para continuar con el proceso.</span></div>",
                    input: 'text',
                    inputLabel: 'Código SITEDS',
                    inputPlaceholder: "000000000001",
                    showCancelButton: true,
                    cancelButtonText: 'CANCELAR',
                    confirmButtonText: 'GUARDAR',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger',
                        popup: 'swal-wide-form',
                    },
                    inputAttributes: {
                        maxlength: 12,
                        pattern: '[A-Za-z0-9 ]*'
                    },
                    buttonsStyling: false,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Ingresa el código SITED para continuar';
                        } else if (value.length < 12) {
                            return 'El código SITEDS debe tener 12 caracteres';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        resolve(result);
                    } else {
                        reject(result);
                    }
                }).catch((error) => {
                    reject(error);
                });
            });
        }
    </script>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="panel panel-primary filterable">
        <div class="panel-heading" style="padding: 0;">
        <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(109, 218, 132); color: green;">CE</span> Lista Citas Medicas - {{ $farmacia->nombre }}</h3>
            </div>
            <div class="panel-body">

                <form class="form-horizontal" method="post" action="{{ route('listar_citas_medico_paciente') }}"
                    id="frmCita">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                    <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{ $farmacia->id }}">
                    <input type="hidden" name="id_establecimiento" id="id_establecimiento"
                        value="{{ $farmacia->id_establecimiento }}">
                    <input type="hidden" name="id_medico" id="id_medico" value="<?php echo $medico; ?>">


                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Fecha Cita Desde</label>
                            <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini"
                                placeholder="Fecha Cita Desde" value="<?php echo $fechaDesde; ?>" maxlength="10">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Fecha Cita Hasta</label>
                            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin"
                                placeholder="Fecha Cita Hasta" value="<?php echo $fechaDesde; ?>" maxlength="10">
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Fecha Registro Desde</label>
                            <input class="form-control input-sm" type="text" name="fecha_reg_ini" id="fecha_reg_ini"
                                placeholder="Fecha Registro Desde" value="<?php //echo $fechaDesde
                                ?>" maxlength="10">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Fecha Registro Hasta</label>
                            <input class="form-control input-sm" type="text" name="fecha_reg_fin" id="fecha_reg_fin"
                                placeholder="Fecha Registro Hasta" value="<?php //echo $fechaDesde
                                ?>" maxlength="10">
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Condici&oacute;n UPSS</label>
                            <select class="form-control input-sm" name="id_condicion_servicio" id="id_condicion_servicio"
                                required="">
                                <option value="0">-Todos-</option>
                                <option value="NUEVO">NUEVO</option>
                                <option value="CONTINUADOR">CONTINUADOR</option>
                                <option value="REINGRESO">REINGRESO</option>
                            </select>
                        </div>
                        <input type="hidden" name="id_condicion_establecimiento" id="id_condicion_establecimiento"
                            value="0" />
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
                            <div class="col-md-6">
                                <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                                    onclick="reporteCita('{{ Auth::user()->id }}')">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                </a>
                            </div>
                            <?php if($flagemergenciareferencia == 1):?>
                            <div class="col-md-6" style="padding-right:0px;padding-left:0px;">
                                <!--<a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteAtencionDetallado('{{ Auth::user()->id }}')" >
                                                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Detallado__
                                                            </a>-->
                                <a href="javascript:void(0)" class="btn btn-danger form-control input-sm" target="_blank"
                                    onclick="reporteHistoria('{{ Auth::user()->id }}')">
                                    Reporte de Historias <i class="fa fa-file-pdf-o"></i>
                                </a>
                            </div>
                            <?php endif;?>
                            <div class="col-md-6" id="botones">
                                <button type="button" id="btn_tv_llamar" class="btn btn-warning form-control input-sm"
                                    onclick="llamarTv()">Llamar</button>

                                <div id="countdown"></div>
                            </div>
                            {{-- TAGSITEDS --}}
                            @if(intval(Auth::user()->flagfiscalizador) === 1 && intval(Auth::user()->flagestablecimientos) === 1 && intval(Auth::user()->flagfarmacias) === 1 && intval(Auth::user()->flagservicios) === 1)
                            <div class="col-md-6" style="padding-right:0px;padding-left:0px">
                                <a class="btn btn-info form-control input-sm" href="javascript:void(0)" onclick="generarCodigoSitedsMassive()">
                                <i class="fa fa-cogs" aria-hidden="true"></i> Regularizar Siteds Masivo
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Nro. Documento</label>
                            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
                                class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario; ?>">
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Nombres</label>
                            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                                class="form-control input-sm" maxlength="60" value="<?php echo $nombre_beneficiario; ?>">
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Servicio</label>
                            <select class="form-control input-sm" name="id_servicio" id="id_servicio"
                                onchange="getSubConsultorio()">
                                <option value="0">-Seleccionar-</option>
                                <?php foreach($consultorio as $row):?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->nombre; ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Consultorio</label>
                            <select class="form-control input-sm" name="id_consultorio" id="id_consultorio"
                                required="">
                                <option value="0">-Seleccionar-</option>
                            </select>
                        </div>

                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Estado</label>
                            <select class="form-control input-sm" id="estado" name="estado" tabindex="5"
                                style="width: 100%">
                                <option value="1" <?php if (isset($estado) && $estado == 1) {
                                    echo "selected='selected'";
                                } ?>>Programado</option>
                                <option value="10" <?php if (isset($estado) && $estado == 10) {
                                    echo "selected='selected'";
                                } ?>>Re-Programado</option>
                                <option value="2" <?php if (isset($estado) && $estado == 2) {
                                    echo "selected='selected'";
                                } ?>>Confirmado</option>
                                <option value="3" <?php if (isset($estado) && $estado == 3) {
                                    echo "selected='selected'";
                                } ?>>Derivado</option>
                                <option value="4" <?php if (isset($estado) && $estado == 4) {
                                    echo "selected='selected'";
                                } ?>>Atendido</option>
                                <option value="5" <?php if (isset($estado) && $estado == 5) {
                                    echo "selected='selected'";
                                } ?>>Anulado</option>
                                <option value="6" <?php if (isset($estado) && $estado == 6) {
                                    echo "selected='selected'";
                                } ?>>Reservado</option>
                                <option value="7" <?php if (isset($estado) && $estado == 7) {
                                    echo "selected='selected'";
                                } ?>>Observado</option>
                                <option value="8" <?php if (isset($estado) && $estado == 8) {
                                    echo "selected='selected'";
                                } ?>>Migrado</option>
                                <option value="9" <?php if (isset($estado) && $estado == 9) {
                                    echo "selected='selected'";
                                } ?>>Atendido con Triaje</option>
                                <option value="0" <?php if (isset($estado) && $estado == 0) {
                                    echo "selected='selected'";
                                } ?>>Todos</option>
                            </select>
                        </div>


                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
                            <div class="col-md-6">
                                <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar"
                                    type="button">
                            </div>

                            <?php if($flagemergenciareferencia == 1):?>
                            <div class="col-md-6" style="padding-right:0px;padding-left:0px">
                                <input type='button' class='form-control btn btn-primary dropdown-toggle btn-sm'
                                    data-toggle='dropdown' value='Asignar Consultorio'
                                    onclick="modalAsignarConsultorio()" />
                            </div>
                            <?php endif;?>
                            
                            @if (Auth::user()->tipo_registro == 3)
                            <div class="col-md-6" style="padding-right:0px;padding-left:0px">
                                <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="generarRptParaCallcenter('{{ Auth::user()->id }}')"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Call center</a>
                            </div>
                            @endif

                        </div>

                        <?php if($flagemergenciareferencia == 1):?>
                        <span id="spanContador" class="badge badge-light"
                            style="margin-top: 10px;margin-right: 10px;float: right"></span>
                        <div class="pull-right" style="margin-right:10px;">
                            <table id="Grd2" class="table" style="margin-bottom:0px">
                                <tbody>
                                    <tr>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php endif;?>

                </form>
                <!-- <div class="col-md-12" id="global"> -->
                <div class="table-responsive" id="global">
                    <table id="tblCita" class="table table-bordered">
                        <thead>
                            <tr>
                                <th
                                    style="text-align:left; padding-bottom:0px;padding-right:5px;margin-bottom: 0px; vertical-align: middle">
                                    Todos<br>
                                    <input type="checkbox" name="select_all" value="1" id="example-select-all">
                                </th>
                                <th>Cod</th>
                                <th>Fecha Cita</th>
                                <th>Hora Cita</th>
                                <th>Fecha Registro</th>
                                <th>Hora Ticket</th>
                                <th>Tipo Doc</th>
                                <th>Numero Doc</th>
                                <th>Numero Historia</th>
                                <th>Paciente</th>
                                <th>Tel&eacute;fono1</th>
                                <th>Tel&eacute;fono2</th>
                                <th>Respons. Cita</th>
                                <th>Servicio</th>
                                <th>Medico</th>
                                <th>Consultorio</th>
                                <th>Tipo Cita</th>
                                <th>Referido</th>
                                <th>Cod SITEDS</th>
                                {{-- se agrego un campo --}}
                                <th id="estadoRe">Estado</th>
                                <?php if($flagemergenciareferencia != 1):?>
                                <th id="estadoRePr">Presente</th>
                                <?php endif;?>
                                <th>Observación</th>
                                <!--<th>Estado</th>-->
                                <!--<th>Ver Cita</th>-->
                                <?php if($flagemergenciareferencia != 1):?>
                                <th id="atenderpip">Atender</th>
                                <th>Anular</th>
                                <?php endif;?>
                                <?php if(Auth::user()->tipo_registro==9 && $flagemergenciareferencia != 1):?>
                                <th>Triaje</th>
                                <?php endif;?>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    <input type="hidden" name="soloIpressLuisNSaes" id="soloIpressLuisNSaes" value="<?php echo $soloIpressLuisNSaes; ?>">
    <input type="hidden" name="farmacia" id="farmacia" value="<?php echo $farmaciavalor; ?>">
    <input id="ipressCorrecta" name="ipressCorrecta" type="hidden">
    <div id="modalForm" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <div id="openModalHistoria" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-hc">
            <div id="" class="modal-content" style="padding: 0px;margin: 0px;">
                <div class="modal-body modal-historia">
                </div>
            </div>
        </div>
    </div>

    <div id="openModalSiteds" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-hc">
            <div id="" class="modal-content" style="padding: 0px;margin: 0px;">
                <div class="modal-body modal-siteds">
                </div>
            </div>
        </div>
    </div>
    <div id="openOverlayOpc" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">

                <!--<div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><small style="font-size: 22px; color: #fff">Listado de medicos por dia</small></h4>
                                        </div>
                                        -->
                <div class="modal-body">

                    <div id="diveditpregOpc"></div>

                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="myModalDetRV" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">DETALLE DE RECETA VALE NRO. <span id="nrorecmodal"></span></h5>
                </div>
                <div class="modal-body">
                    <div id="muestra-det-receta"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modalForm2" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">REGISTRO DE TRIAJE</h5>
                </div>
                <div class="modal-body">
                    <div id="divModalTriaje"></div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.includes.footer')
@endsection
