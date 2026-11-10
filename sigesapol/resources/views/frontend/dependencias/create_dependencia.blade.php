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

    $cmbopcion = $("#frmDependencia").find("#id_opcion");
    $cmbopcion.select2();

    function validateFrm(){
        $('#btnReg').prop('disabled', true);

        if ($('#frmDependencia').find("#nombre_dependencia").val() == "") {
            bootbox.alert("El nombre de la dependencia no puede estar vacío.");
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
                        $("#frmDependencia").submit();
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
                <li><a href="/listar_dependencias"><i class="fa fa-table fa-fw"></i> Dependencia de Salud</a></li>
                <li class="active"><a href="/formulario_dependencia"><i class="fa fa-plus"></i> Nuevo </a></li>   
            </ul>
        </div>
        <div class="col-md-7 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Agregar Dependencias de Salud</h3>
                    </div>
                    <div style="margin-top: 20px">
                        <form id="frmDependencia" class="form-horizontal" role="form" method="post"  action="{{ route('store_dependencia') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>                                
                                    <div class="col-md-12">
                                        <label>Nombre o Descripcion de la Dependencia</label>
                                        <textarea id="nombre_dependencia" name="nombre_dependencia" type="text" class="form-control input-sm" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" ></textarea> 
                                    </div>                               
                                
                                    <div class="col-md-6">
                                            <br/>
                                            <label>OPCION</label>
                                            <select name="id_opcion" autofocus  tabindex="1" id="id_opcion" class="form-control" required="">
                                                <option value="" disabled selected style="color: #888888">Seleccionar</option>
                                                <option value="I">INGRESO</option>
                                                <option value="S">SALIDA</option>
                                                <option value="A">AMBOS</option>
                                                
                                        </select>
                                    
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/listar_dependencias/{{ Auth::user()->id }}" class="form-control btn btn-danger" >Cancelar</a>
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