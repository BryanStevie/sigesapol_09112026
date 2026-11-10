@extends('frontend.layouts.master')
@section('renderjs')
<style>


</style>

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

    function validateFrm(){
        $('#btnReg').prop('disabled', true);

        if ($('#frmDiagnostico').find("#nombre").val() == "") {
            bootbox.alert("La descripcion del diagnostico no puede estar vacío.");
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
                        $("#frmDiagnostico").submit();
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
                <li><a href="/listar_diagnosticos"><i class="fa fa-table fa-fw"></i> Diagnosticos</a></li>
                <li class="active"><a href="/formulario_diagnostico"><i class="fa fa-plus"></i> Nuevo </a></li>   
            </ul>
        </div>
        
        <div class="col-md-7 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Agregar Diagnostico</h3>
                    </div>
                    <div style="margin-top: 20px">
                        <form id="frmDiagnostico" class="form-horizontal" role="form" method="post"  action="{{ route('store_diagnostico') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <label>Nombre o Descripcion del Diagnostico</label>
                                            <input name="nombre" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">                                        
                                        </div>  
                                        <div class="col-md-2">
                                                <label>Abreviatura</label>
                                                <input name="codigo" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Genero</label>
                                            <select class="form-control" id="genero" name="genero" tabindex="5" style="width: 100%">
                                                <option value="" selected>-Seleccionar-</option>
                                                <option value="1">MASCULINO</option>
                                                <option value="2">FEMENINO</option>
                                                <option value="3">AMBOS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/listar_diagnosticos" class="form-control btn btn-danger" >Cancelar</a>
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