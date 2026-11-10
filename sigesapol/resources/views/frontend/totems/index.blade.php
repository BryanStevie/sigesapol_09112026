@extends('frontend.layouts.master_tv')
@section('renderjs')

<style type="text/css">
    .container-fluid {
        padding: initial !important;
    }

    .fs-large {
        font-size: 28px !important;
        text-align: center;
    }

    /* form {
        margin-top: 100px;
    } */

    .container {
        min-height: auto;
    }

    button {
        height: 85px !important;
        padding: 12px 24px !important;
        font-size: 42px !important;
    }

    .form-control {
        height: 85px !important;
        padding: 12px 24px !important;
        font-size: 42px !important;
    }
    /*height: 102px !important;
        padding: 12px 24px !important;
        font-size: 42px !important;*/

    .control-label {
        font-size: 28px !important;
    }

    .mr-40 {
        margin-right: 40px;
    }

    .mt-50 {
        margin-top: 50px;
    }

    .mb-50 {
        margin-bottom: 50px;
    }

    .mt-150 {
        margin-top: 150px;
    }
    
    /* .col-xs-2 {
        padding-left: 50px !important;
    } */

    /* Sticky footer styles
    -------------------------------------------------- */
    html {
    position: relative;
    min-height: 100%;
    }
    body {
    margin-bottom: 85px; /* Margin bottom by footer height */
    }
    .footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 85px; /* Set the fixed height of the footer here */
    }


    /* Custom page CSS
    * Not required for template or sticky footer method.
    * -------------------------------------------------- */

    /* .container {
    width: auto;
    max-width: 680px;
    padding: 0 15px;
    } */
    .container2 .text-muted {
        margin: 20px 0;
    }
    /* #steps button {
        margin-bottom: 50px;
    } */

    /* #steps table { */
    #steps table {
        font-size: 28px !important;
    }
    /* #steps table th, 
    #steps table td { */
    #steps table th, 
    #steps table td {
        text-align:center !important;
    }

    /* #steps table td { */
    #steps table td {
        vertical-align: middle;
    }

    .container button i {
        text-align: left !important;
    }

    #number-keyboard {
        text-align: center !important;
    }
    #number-keyboard table {
        display:inline-block;
    }
    #number-keyboard td {
        padding: 5px !important;
    }

    #number-keyboard td button {
        width: 85px;
    }

</style>

<style type="text/css" media="print">
    .mt-50, 
    .mt-150 {
        margin-top: 15px;
    }

    .mb-50 {
        margin-bottom: 0px;
    }

    .noprint {
      display: none;
    }
</style>
<script>
    let pantalla_completa = false;

    $(document).ready(function() {
        $('#step-index').show();
        $("#steps").html('');
        $("#steps").hide();
    });

    function maxlength_doc_usu() {
        $('#nro_documento').val('');

        if ($("#tipo_documento").val() == "1") {
            $("#nro_documento").attr('maxlength', '8');
        } else {
            $("#nro_documento").attr('maxlength', '12');
        }
        $("#nro_documento").focus();
    }

    function push(number) {
        let nro_documento = $("#nro_documento").val();
        let tipo_documento = $("#tipo_documento").val();
        switch (number) {
            case "reset":
                longitud = nro_documento.length;
                if (longitud > 0) {
                    nro_documento = nro_documento.substring(0, longitud-1);
                    $("#nro_documento").val(nro_documento);
                }
                $('#nro_documento').focus();
                break;
            default:
                longitud = nro_documento.length;
                if (tipo_documento == "1") {
                    if (longitud < 8) {
                        $("#nro_documento").val(nro_documento + number);
                    }
                } else {
                    if (longitud < 12) {
                        $("#nro_documento").val(nro_documento + number);
                    }
                }
                $('#nro_documento').focus();
                break;
        }
        
    }

    function validarDocumento() { //antes siguiente()
        $('#mensaje').html('');

        let tipo_documento = $("#tipo_documento").val();
        let nro_documento = $("#nro_documento").val();
        let id_totem = $("#id_totem").val();
        let id_dispositivo = $("#id_dispositivo").val();
        let ip = $("#ip").val();

        if (nro_documento == "") {
            $('#mensaje').html('Ingrese el N° de documento.');
            $('#nro_documento').focus();
            return 0;
        }

        if (tipo_documento == "1") {
            if (nro_documento.length != 8) {
                $('#mensaje').html('El N° DNI debe tener 8 dígitos.');
                $('#nro_documento').focus();
                return 0;
            }
        }

        $.ajax({
            url: "/totem_validar_documento",
            type: "POST",
            data: "tipo_documento="+tipo_documento+"&nro_documento="+nro_documento+"&id_totem="+id_totem+"&id_dispositivo="+id_dispositivo+"&ip="+ip,
            success: function(result) {
                $('#step-index').hide();
                $("#steps").html(result);
                $("#steps").show();

                $('#spanish-text').html('Totem');
                $('#english-text').html('Totem');
            },
            complete: function () {
                //
            }
        });

    }

    function maximizarYMinimizarPantalla() {
        if (!pantalla_completa) {
            var docElm = document.documentElement;
            if (docElm.requestFullscreen) {
                docElm.requestFullscreen();
            }
            else if (docElm.mozRequestFullScreen) {
                docElm.mozRequestFullScreen();
            }
            else if (docElm.webkitRequestFullScreen) {
                docElm.webkitRequestFullScreen();
            }
            pantalla_completa = true;
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            }
            else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
            pantalla_completa = false;
        }
    }

    function menu_citas_medicas(parent) { // antes ir_totem()
        $.ajax({
            url: '/totem_citas/'+parent,
            async: false,
            success: function(result){
                $('#step-index').hide();
                $("#steps").html(result);
                $("#steps").show();

                $('#spanish-text').html('Verificación del Paciente');
                $('#english-text').html('Patient Verification');
            },
            complete: function () {
                $("#nro_documento").val('');
                $('#nro_documento').focus();
            }
        });
    }

    function menu_otros_tramites(parent) {
        $.ajax({
            url: '/menu_otros_tramites/'+parent,
            async: false,
            success: function(result){
                $('#step-index').hide();
                $("#steps").html(result);
                $("#steps").show();

                $('#spanish-text').html('Menu Otros Tramites');
                $('#english-text').html('Menu Otros Tramites');
            },
            complete: function () {
                // $("#nro_documento").val('');
                // $('#nro_documento').focus();
            }
        });
    }

    function sub_menu_indicar_presencia() {
        $.ajax({
            url: '/buscar_cita_dia',
            async: false,
            success: function(result){
                $('#step-index').hide();
                $("#steps").html(result);
                $("#steps").show();

                $('#spanish-text').html('Sub Menu Citas Medicas');
                $('#english-text').html('Sub Menu Citas Medicas');
            },
            complete: function () {
                // $("#nro_documento").val('');
                // $('#nro_documento').focus();
            }
        });
        // window.location.href = "{{ url('/buscar_cita_dia') }}";
    }

    function indicarPresencia(id_cita) {
        //let _token = document.getElementById("_token").value;
        $.ajax({
            url: "/indicar_presencia_cita",
            type: "POST",
            data: "id_cita="+id_cita,
            success: function(result) {
                
                $("#steps").html(result);
                $("#steps").show();
            }
        });
    }

    function ir_inicio() {
        window.location.href = "{{ url('/totem/') }}";
    }

    function menu_principal() {
        $.ajax({
            url: '/totem_menu_principal',
            async: false,
            success: function(result){
                $('#step-index').hide();
                $("#steps").html(result);
                $("#steps").show();

                $('#spanish-text').html('Menú principal');
                $('#english-text').html('Menú principal');
            },
            complete: function () {
                // $("#nro_documento").val('');
                // $('#nro_documento').focus();
            }
        });
    }

    function sub_menu_recepcion(id_opcion) {
        $.ajax({
            url: '/generar_ticket/'+id_opcion+'/0',
            async: false,
            success: function(result){
                $('#step-index').hide();
                $("#steps").html(result);
                $("#steps").show();

                $('#spanish-text').html('Menú principal');
                $('#english-text').html('Menú principal');
            },
            complete: function () {
                setTimeout("redireccionar_index()", 5000);
            }
        });
    }

    function sub_menu_triaje(id_opcion, id_cita) {
        $.ajax({
            url: '/generar_ticket/'+id_opcion+'/'+id_cita,
            async: false,
            success: function(result){
                $('#step-index').hide();
                $("#steps").html(result);
                $("#steps").show();

                $('#spanish-text').html('Menú principal');
                $('#english-text').html('Menú principal');
            },
            complete: function () {
                setTimeout("redireccionar_index()", 5000);
            }
        });
    }

    function redireccionar_index() {
        window.location = "{{ url('/totem/') }}";
    }

    maxlength_doc_usu();

</script>
@endsection
@section('content')
    <!-- <style>
        table#header td{
            width:0px;
        }
        table#header img{
            width:0px;
        }
    </style> -->
    <div style="background-color:rgb(26,115,232); padding: 1rem">
        <table id="header" style="" width="100%" class="noprint">
            <tr>
                <td rowspan="3" width="70px"><img src="{{ asset('images/logo/dirsapol.png') }}" id="logo-left" class="noprint" width="60px" alt="" /></td>
                <td><span style="color:white;font-weight:bold" id="spanish-text" class="noprint">Verificación del Paciente</span></td>
                <td style="color:white;font-weight:bold;text-align:right;"><span>{{$establecimiento->nombre}}</span></td>
                <td rowspan="3" width="70px" style="text-align:right;"><a onclick="maximizarYMinimizarPantalla()"><img src="{{ asset('images/logo/dirsapol.png') }}" width="60px" alt=""></a></td>
            </tr>
            <tr>
                <td><span style="color:white;font-weight:bold" id="english-text" class="noprint">Patient Verification</span></td>
            </tr>
            <tr>
                <td><span style="color:white;font-weight:bold">&nbsp;</span></td>
                <input type="hidden" value="{{$id_totem}}" id="id_totem">
                <input type="hidden" value="{{$id_dispositivo}}" id="id_dispositivo">
                <input type="hidden" value="{{$ip}}" id="ip">
            </tr>
        </table>
    </div>
    
    <div id="step-index">
        <div class="container">
            <div class="row mt-50 mb-50" >
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    <p class="fs-large">Teclee su número de documento y presione "[SIGUIENTE]".</p>
                    <p class="fs-large">Enter your document number and press "[NEXT]"</p>
                </div>
            </div>
                    
            <div class="row">
                <div class="col-xs-offset-1 col-xs-5 ">
                    <div class="form-group">
                        <label for="" class="control-label">DOCUMENTO / DOCUMENT</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-control" onchange="maxlength_doc_usu()">
                            <option value="1">DNI</option>
                            <option value="2">PAS</option>
                            <option value="3">CE</option>
                            <option value="4">DIE</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-5">
                    <div class="form-group">
                        <label for="" class="control-label">NÚMERO / NUMBER</label>
                        <input type="text" name="nro_documento" class="form-control" id="nro_documento" maxlength="8">
                    </div>
                </div>
            </div>
            <div class="row mt-50">
                <p class="fs-large" style="color:red" id="mensaje"></p>
            </div>
            <div class="row">
                <div id="number-keyboard">
                    <table>
                        <tr>
                            <td><button type="button" class="btn btn-success" onclick="push('7')"> 7 </button></td>
                            <td><button type="button" class="btn btn-success" onclick="push('8')"> 8 </button></td>
                            <td><button type="button" class="btn btn-success" onclick="push('9')"> 9 </button></td>
                        </tr>
                        <tr>
                            <td><button type="button" class="btn btn-success" onclick="push('4')"> 4 </button></td>
                            <td><button type="button" class="btn btn-success" onclick="push('5')"> 5 </button></td>
                            <td><button type="button" class="btn btn-success" onclick="push('6')"> 6 </button></td>
                        </tr>
                        <tr>
                            <td><button type="button" class="btn btn-success" onclick="push('1')"> 1 </button></td>
                            <td><button type="button" class="btn btn-success" onclick="push('2')"> 2 </button></td>
                            <td><button type="button" class="btn btn-success" onclick="push('3')"> 3 </button></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><button type="button" class="btn btn-success" onclick="push('0')"> 0 </button></td>
                            <td><button type="button" class="btn btn-success" onclick="push('reset')"><i class="fa fa-arrow-left"></i></button></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-150">
                <div class="col-xs-offset-1 col-xs-10 ">
                    <!-- <button type="button" class="btn btn-success" onclick="ir_inicio()">ATRAS / [BACK]</button> -->
                    <button type="button" class="btn btn-success pull-right" onclick="validarDocumento()">SIGUIENTE / [NEXT]</button>
                </div>
            </div>
        </div>
        
    </div>

    <div id="steps">
        <!-- pantallas -->
    </div>
    
@endsection
