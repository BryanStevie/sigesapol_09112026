@extends('frontend.layouts.master')
@section('renderjs')
<style>


</style>
<script src='../../funciones/function.js'></script>
<script>
    function jsDecimals(e) {

        var evt = (e) ? e : window.event;
        var key = (evt.keyCode) ? evt.keyCode : evt.which;
        if (key != null) {
            key = parseInt(key, 10);
            if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
                //Aca tenemos que reemplazar "Decimals" por "NoDecimals" si queremos que no se permitan decimales
                if (!jsIsUserFriendlyChar(key, "Decimals")) {
                    return false;
                }
            } else {
                if (evt.shiftKey) {
                    return false;
                }
            }
        }
        return true;
    }
    function jsIsUserFriendlyChar(val, step) {
        // Backspace, Tab, Enter, Insert, y Delete
        if (val == 8 || val == 9 || val == 13 || val == 45 || val == 46) {
            return true;
        }
        // Ctrl, Alt, CapsLock, Home, End, y flechas
        if ((val > 16 && val < 21) || (val > 34 && val < 41)) {
            return true;
        }
        if (step == "Decimals") {
            if (val == 190 || val == 110) {  //Check dot key code should be allowed
                return true;
            }
        }
        // The rest
        return false;
    }

    function IsNumeric(valor)
    {
        var log = valor.length;
        var sw = "S";
        for (x = 0; x < log; x++)
        {
            v1 = valor.substr(x, 1);
            v2 = parseInt(v1);
//Compruebo si es un valor numérico 
            if (isNaN(v2)) {
                sw = "N";
            }
        }
        if (sw == "S") {
            return true;
        } else {
            return false;
        }
    }
    var primerslap = false;
    var segundoslap = false;

    $cmbopcion = $("#frmEstablecimiento").find("#id_nivel");
    $cmbopcion.select2();

    function validateFrm(){
        $('#btnReg').prop('disabled', true);

        if ($('#frmEstablecimiento').find("#nombre_establecimiento").val() == "") {
            bootbox.alert("El nombre de la establecimiento no puede estar vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        submitFrm();
        return false;
    }
    
    function submitFrm() {
            bootbox.confirm({
                message: "Aviso: Se guardarán los registros ingresados. ¿Desea continuar?.",
                className: 'title-dialog',
                buttons: {
                    confirm: {
                        label: 'Si',
                        className: 'btn-success btn-lg'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger btn-lg'
                    }
                },
                callback: function (result) {
                    if (result==true) {
                        $("#frmEstablecimiento").submit();
                    } else {
                        $('#btnReg').prop('disabled', false);
                    }
                }
            });
    }

</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/listar_establecimientos"><i class="fa fa-table fa-fw"></i> Establecimiento de Salud</a></li>
                <li class="active"><a href="/formulario_establecimiento"><i class="fa fa-plus"></i> Nuevo </a></li>   
            </ul>
        </div>
        <div class="col-md-7 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Agregar Establecimientos de Salud</h3>
                    </div>
                    <div style="margin-top: 20px">
                        <form id="frmEstablecimiento" class="form-horizontal" role="form" method="post"  action="{{ route('store_establecimiento') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>                                
                                    <div class="col-md-4" style="margin-top: 10px; margin-bottom: 10px">
                                        <label>Código</label><br/>
                                        <input  type="text"tabindex="1"  class="form-control"  onkeypress="return noEspaciosInicioSoloNumero(event,'selector_codigo_establecimiento');" id="selector_codigo_establecimiento" name="codigo" value="" required="" placeholder="CÓDIGO" maxlength="8">
                                    </div>
                                    <div class="col-md-12"><br/>
                                        <label>Nombre o Descripcion del Establecimiento</label>
                                        <textarea id="nombre_establecimiento" onkeypress="return noEspaciosInicioSoloLetras(event,'nombre_establecimiento');" name="nombre_establecimiento" tabindex="2" type="text" class="form-control input-sm" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" ></textarea>
                                    </div>
                                    <div class="col-md-12"><br/>
                                        <label>Dirección del establecimiento</label>
                                        <input  type="text"tabindex="3"  class="form-control" name="direccion_establecimiento" id="direccion_establecimiento" onkeypress="return noEspaciosInicioSoloLetras(event,'direccion_establecimiento');" value="" required="" placeholder="Dirección del establecimiento" maxlength="100">
                                    </div>  
                                    <div class="col-md-12">
                                    <div class="col-md-4">
                                        <br/>
                                        <label>UBIGEO</label>
                                        <input  type="text" class="form-control" name="ubigeo"  id="ubigeo_establecimiento" onkeypress="return noEspaciosInicioSoloNumero(event,'ubigeo_establecimiento');" tabindex="4" value="" placeholder="CÓDIGO UBIGEO" maxlength="6">
                                    </div>                             
                                    <div class="col-md-4">
                                        <br/>
                                        <label>RED/REGION</label>
                                        <input  type="text" class="form-control" name="red" id="red_establecimiento"  onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'red_establecimiento');" value="" tabindex="5" placeholder="RED/REGION" maxlength="100">
                                    </div>
                                    <div class="col-md-4">
                                            <br/>
                                            <label>NIVEL</label>
                                            <select name="id_nivel" autofocus  tabindex="6" id="id_nivel" class="form-control" required="">
                                                <option value="0">0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">2</option>
                                        </select>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="col-md-4">
                                        <br/>
                                        <label>Intervalo de días en cita</label>
                                        <input type="text" tabindex="5" class="form-control" name="intervalo_cita" maxlength="1" required>
                                    </div>
                                    <div class="col-md-4">
                                        <br/>
                                        <label>Firma Electronica</label></br>
                                        <input type="checkbox" value="1" name="flag_firma_electronica" />
                                    </div>
                                    <div class="col-md-4"><br/>
                                    </div>
                                    </div>    
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/listar_establecimientos/{{ Auth::user()->id }}" class="form-control btn btn-danger" >Cancelar</a>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection