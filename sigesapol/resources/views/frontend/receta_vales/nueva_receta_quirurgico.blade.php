@extends('frontend.layouts.master')
@section('renderjs')
<style>
    legend{
        font-size: 15px;
    }
</style>
<script>
    $cmbUni = $("#frmcrear_prod").find("#id_unidad");
    $cmbUni.select2();
    function soloNumeros(e)
    {
        var tipdoc =  $("#tipo_doc").val();
        if(tipdoc == "1"){
            var key = window.Event ? e.which : e.keyCode;
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }
    }


    $(document).keypress(function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var tipo = $('input[name=tipo]').val();
            if (tipo == "admision") {
                $("#btnBuscarAdmision").click();
            } else {
                $("#btnBuscar").click();
            }
        }
    });

    $("#dni_beneficiario").keyup(function (event) {
        if (event.keyCode == 13) {
            var tipo = $('input[name=tipo]').val();
            //document.getElementById("frmBuscarBeneficiario").submit();
            if (tipo == "admision") {
                $("#btnBuscarAdmision").click();
            } else {
                $("#btnBuscar").click();
            }
        }
    });

    $("#btnBuscar").on("click", function () {
        if ($('#frmBuscarBeneficiario').find("#dni_beneficiario").val() == "") {
            $("#respuesta").html("");
            $("#respuesta").append(
                    '<p> Falta digitar el DNI</p>'
                    );
            $('#squarespaceModal').modal();
            return false;
        }
    });

    $("#btnBuscarAdmision").on("click", function () {
        var msg = "";
        if ($('#frmBuscarBeneficiario').find("#dni_beneficiario").val() == "") {
            $("#respuesta").html("");
            msg += '<p> Falta digitar el DNI</p>';
        }

        if ($('#frmBuscarBeneficiario').find("#nro_admision").val() == "") {
            msg += '<p> Falta seleccionar la Admisión</p>';
        }

        if (msg != "") {
            $("#respuesta").html("");
            $("#respuesta").append(msg);
            $('#squarespaceModal').modal();
            return false;
        }

    });



    $(document).ready(function () {
        //$("#nro_admision").select2({max_selected_options: 4});

        /*
         $("#dni_beneficiario").keypress(function(event) {
         if(event.keyCode == 13){
         getAdmision();
         }
         });
         */
        $("#dni_beneficiario").blur(function (event) {
            getAdmision();
        });

    });


    function getAdmision() {

        var dni_beneficiario = $('#dni_beneficiario').val();
        var id_farmacia = $('#id_farmacia').val();
        $('#nro_admision').html("<option value=''>--Seleccionar-</option>");

        $.ajax({
            url: '/obtener_admision_por_idfarmacia_dni/' + id_farmacia + '/' + dni_beneficiario,
            success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    var turno = "";
                    newOption += "<option value='" + oo.nro_admision + "'>FECHA: " + oo.fecha_admision + " - SERVICIO: " + oo.consultorio + " - TIPO DE ATENCION: " + oo.tipo_admision + " - TURNO: " + oo.turnos + " - MEDICO:	" + oo.nombre + ", " + oo.paterno + " " + oo.materno + "</option>";
                });
                $('#nro_admision').html(newOption);
                //$("#nro_admision").select2({max_selected_options: 4});

            }
        });

    }


    function maxlength_doc_usu() {

        $('#dni_beneficiario').val('');

        if ($("#tipo_doc").val() == "1") {
            $("#dni_beneficiario").attr('maxlength', '8');
        } else {
            $("#dni_beneficiario").attr('maxlength', '12');
        }
        document.getElementById("dni_beneficiario").focus();
    }


</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <!--
<div class="col-sm-3 col-md-3">
    <div class="panel-group" id="accordion">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                        </span>Atención de Recetas</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-plus text-primary"></span>
                                <a href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia }}"> Nueva Receta</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-search text-primary"></span>
                                <a href="/buscar_receta/{{ Auth::user()->id }}"> Buscar Receta</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-list text-primary"></span>
                                <a href="/recetas_atendidas/{{ Auth::user()->id }}"> Receta atendidas</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-list text-primary"></span>
                                <a href="/recetas_invalidadas/{{ Auth::user()->id_farmacia }}/{{ Auth::user()->id }}"> Recetas Invalidadas</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-tasks text-primary"></span>
                                <a href="/resumen_diario/{{ Auth::user()->id }}"> Resumen Diario</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-arrow-left text-primary"></span>
                                <a href="/stocksbyFamarcia/{{ Auth::user()->id }}"> Ir al Stock</a>
                            </td>
                        </tr>
                                                        
                                                        @role('Almacenero_recetas')
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-search text-primary"></span>
                                <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}">Recetas atendidas</a>
                            </td>
                        </tr>
                        @endauth
                                                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
        -->
        <div class="col-sm-9 col-md-9 col-md-offset-2">
            <div class="row">
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">Registrar nueva receta del Centro Quirurgico</h3>                
                    </div> 
                    <legend style="text-align: center; text-transform: uppercase;">{{$farmacia->nombre}}</legend>

                    <div class="" style="margin-left: 20px; margin-bottom: 20px">                        

                        <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>

                            <?php if ($farmacia->modo_validacion == 2): ?>
                                <form class="form-horizontal" method="post" action="{{ route('formulario_receta_quirurgico')}}" id="frmBuscarBeneficiario">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="ruta" id="ruta" value="<?php echo $ruta ?>">
                                    <legend>ATENDER RECETA A TRAVÉS DEL RUA</legend>
                                    <div class="form-group">
                                        <div style="float:left; margin-bottom: 10px" class="col-md-6">
                                            <select style="width: 200px" required="">
                                                <option value="1">Registro Único de Atención</option>
                                            </select> -
                                            <!--<input type="text" name="serie" id="serie" placeholder="Serie" value="" style="width: 50px" required="">-->
                                            <select id="serie" name="serie" style="width: 50px" required="">
                                                <option value="001">001</option>
                                            </select>-
                                            <input type="text" name="nro_ticket" id="nro_ticket" placeholder="Ticket" value="" style="width: 90px" required="" autofocus="">                                    
                                            <input type="hidden" name="id_user" id="id_user" value="{{$usuario}}">
                                            <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{ Auth::user()->id_farmacia }}">     
                                            <input type="hidden" name="tipo" id="tipo" value="rua">
                                        </div>
                                        <div class="col-md-6">                    
                                            <input class="btn btn-success btn-circle text-uppercase" type="submit" id="submitComment" value="Atender">
                                        </div>
                                    </div>            
                                </form>
                            <?php endif; ?>

                            @if(Auth::user()->id==1)
                            <form class="form-horizontal" method="post" action="{{ route('formulario_receta_temporal')}}" id="frmBuscarBeneficiario">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="ruta" id="ruta" value="<?php echo $ruta ?>">
                                <legend>ATENDER RECETA A TRAVÉS DEL DNI (TEMPORAL)</legend>
                                <div class="form-group">
                                    <label class="col-md-1 control-label" for="textinput">DNI</label>  
                                    <div class="col-md-3">
                                        <input onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control" maxlength="8" autofocus="">
                                        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id_user }}">
                                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{ Auth::user()->id_farmacia }}"> 
                                        <input type="hidden" name="tipo" value="dni" id="btnBuscar">
                                    </div>
                                    <div class="col-md-8">
                                        <button class="btn btn-info btn-circle" type="submit" id="btnBuscar">
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endif

                            @if(Auth::user()->id_farmacia==28 || Auth::user()->id_farmacia==31)
                            <form class="form-horizontal" method="post" action="{{ route('formulario_receta_cirugia_alterno')}}" id="frmBuscarBeneficiario">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="ruta" id="ruta" value="<?php echo $ruta ?>">
                                <legend>ATENDER RECETA A TRAVÉS DEL DNI (CIRUGIA)</legend>
                                <div class="form-group">
                                    <label class="col-md-1 control-label" for="textinput">DNI</label>  
                                    <div class="col-md-3">
                                        <input onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control" maxlength="8" autofocus="">
                                        <input type="hidden" name="id_user" id="id_user" value="{{$usuario}}">
                                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}"> 
                                        <input type="hidden" name="tipo" value="dni">
                                    </div>
                                    <div class="col-md-8">
                                        <button class="btn btn-info btn-circle" type="submit" id="btnBuscar">
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endif

                            <?php if ($farmacia->modo_validacion == 1): ?>
                                <form class="form-horizontal" method="post" action="{{ route('formulario_receta_quirurgico')}}" id="frmBuscarBeneficiario">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="ruta" id="ruta" value="<?php echo $ruta ?>">
                                    <legend>ATENDER RECETA A TRAVÉS DE DOCUMENTO DE IDENTIDAD</legend>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="tipo">TIPO DOC.</label>
                                            <select class="form-control input-sm" id="tipo_doc" name="tipo_doc" onchange="maxlength_doc_usu()">
                                                <option value="1" selected="">DNI: DOCUMENTO NACIONAL DE IDENTIDAD</option>
                                                <option value="2">CE: CARNET DE EXTRANJERIA</option>
                                                <option value="3">PAS: PASAPORTE</option>
                                                <option value="4">DIE: DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="dni_beneficiario">NRO DOC.</label>
                                            <input onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-sm text-uppercase" maxlength="8" autofocus="">
                                            <input type="hidden" name="id_user" id="id_user" value="{{$usuario}}">
                                            <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}"> 
                                            <input type="hidden" name="tipo" value="dni">
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-info btn-circle input-sm" type="submit" id="btnBuscar" value="Buscar">
                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>


                            <?php if ($farmacia->modo_validacion == 3): ?>
                                <form class="form-horizontal" method="post" action="{{ route('formulario_receta_admision')}}" id="frmBuscarBeneficiario">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="ruta" id="ruta" value="<?php echo $ruta ?>">
                                    <legend>ATENDER RECETA A TRAVÉS DEL DNI Y ADMISI&Oacute;N</legend>
                                    <div class="form-group">
                                        <label class="col-md-1 control-label" for="textinput">DNI</label>  
                                        <div class="col-md-3">
                                            <input onKeyPress="return soloNumeros(event)" id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control" maxlength="8" autofocus="">
                                            <input type="hidden" name="id_user" id="id_user" value="{{$usuario}}">
                                            <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}"> 
                                            <input type="hidden" name="tipo" value="admision">
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-1 control-label" for="textinput">Admisi&oacute;n</label>  
                                        <div class="col-md-9">
                                            <select class="form-control" id="nro_admision" name="nro_admision" >
                                                <option value="">Seleccionar Admisi&oacute;n</option>
                                            </select>  
                                        </div>
                                        <div class="col-md-2">
                                            <input class="btn btn-info btn-circle" type="submit" id="btnBuscarAdmision" value="Buscar">
                                        </div>
                                    </div>

                                </form>

                            <?php endif; ?>



                        <?php endif; ?>


                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header" id="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="lineModalLabel">Sistema de Gestion de Farmacia</h4>                    
                </div>
                <div class="modal-body" id="respuesta">                    
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection