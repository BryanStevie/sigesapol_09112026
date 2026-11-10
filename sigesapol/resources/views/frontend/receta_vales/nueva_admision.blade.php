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
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }
    
    
    $(document).keypress(function(e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            $("#btnBuscarAdmision").click();
        }
    });
    
    $("#dni_beneficiario").keyup(function(event){
        if(event.keyCode == 13){
            $("#btnBuscarAdmision").click();
        }
    });
    
    
    $("#btnBuscar").on("click", function () {
        if ($('#frmBuscarBeneficiario').find("#dni_beneficiario").val() == "") {
            $("#respuesta").html("");
            $("#respuesta").append(
                '<p> Falta digitar el Numero del documento</p>'
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
        
        var citas = $('#nro_cita option').length;
        
        if ($('#frmBuscarBeneficiario').find("#nro_cita").val() == "" && citas > 1) {
            msg += '<p> Falta seleccionar la Cita</p>';
        }
        
        if(msg != ""){
            $("#respuesta").html("");
            $("#respuesta").append(msg); 
            $('#squarespaceModal').modal();
            return false;
        }
        
    });
    
    
    
    $(document).ready(function () {
        /*
        $("#nro_cita").select2({max_selected_options: 4});
        $("#dni_beneficiario").blur(function(event) {
            getCita();
        });
        */
    });
    
    
    function getCita(){
        
        var dni_beneficiario = $('#dni_beneficiario').val();
        var id_farmacia = $('#id_farmacia').val();
        $('#nro_cita').html("<option value=''>--Seleccionar-</option>");
        
        $.ajax({
            url: '/obtener_cita_por_idfarmacia_dni/' + id_farmacia+ '/' + dni_beneficiario,
            success: function(result){                    
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    var turno = "";
                    newOption += "<option value='"+oo.nro_cita+"'>FECHA: "+oo.fecha_cita+" - SERVICIO: "+oo.consultorio+" - TIPO DE ATENCION: "+oo.tipo_cita+" - TURNO: "+oo.turnos+" - MEDICO: "+oo.nombre+", "+oo.paterno+" "+oo.materno+"</option>";
                });                 
                $('#nro_cita').html(newOption);
                $("#nro_cita").select2({max_selected_options: 4});
            
            }
        });
        
    }
    
    
    
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
    
        <!--
        <div class="col-sm-3 col-md-3">
            <div class="panel-group" id="accordion">
            
                <!--
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
        <div class="col-sm-2 col-md-2">
        </div>
        <div class="col-sm-8 col-md-8">
            <div class="row">
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">Registrar Nuevo Hospitalizado</h3>                
                    </div> 
                    <legend style="text-align: center; text-transform: uppercase;">{{$farmacia->nombre}}</legend>
                     
                    <div class="" style="margin-left: 20px; margin-bottom: 20px">                        
                      
                        <form class="form-horizontal" method="post" action="{{ route('formulario_admision')}}" id="frmBuscarBeneficiario">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="tipo">TIPO DOC.</label>
                                    <select class="form-control input-sm" id="tipo"
                                        name="tipo">
                                        <option value="1" selected="">DNI</option>
                                        <option value="2">CARNET DE EXTRANJERIA</option>
                                        <option value="3">PASAPORTE</option>
                                        <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                    </select>
                                    <input type="hidden" name="id_user" id="id_user" value="{{$usuario}}">
                                    <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}"> 
                                </div>
                                <div class="col-md-6">
                                    <label for="dni_beneficiario">NRO DOC.</label>
                                    <div class="input-group">
                                        <input onKeyPress="return soloNumeros(event)" id="dni_beneficiario"
                                            name="dni_beneficiario" type="text" placeholder=""
                                            class="form-control input-sm text-uppercase" maxlength="9"
                                            autofocus="" tabindex="4">
                                        <span class="input-group-btn" style="height:28px!important;">
                                            <input class="btn btn-info btn-circle" type="submit" id="btnBuscarAdmision" value="Buscar">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--
                            <div class="form-group">
                                <label class="col-md-1 control-label" for="textinput">Cita</label>  
                                <div class="col-md-9">
                                <select class="form-control" id="nro_cita" name="nro_cita" >
                                    <option value="">Seleccionar Cita</option>
                                </select>  
                                </div>
                                <?php //if($permiso->crear == 1 || $permiso->modificar == 1):?>
                                <div class="col-md-2">
                                    <input class="btn btn-info btn-circle" type="submit" id="btnBuscarAdmision" value="Buscar">
                                </div>
                                <?php //endif;?>
                                
                            </div>
                            -->
                            
                        </form>

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
                    <h4 class="modal-title" id="lineModalLabel">Validación</h4>                    
                </div>
                <div class="modal-body" id="respuesta">                    
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection