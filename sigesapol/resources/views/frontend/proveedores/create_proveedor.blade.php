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

        if ($('#frmMedico').find("#nombre").val() == "") {
            bootbox.alert("El nombre del médico no puede estar en vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        if ($('#frmMedico').find("#paterno").val() == "") {
            bootbox.alert("El apellido paterno del médico no puede estar vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        if ($('#frmMedico').find("#materno").val() == "") {
            bootbox.alert("El apellido materno del médico no puede estar vacío.");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        if ($('#frmMedico').find("#id_especializacion").val() == "") {
            bootbox.alert("Debe seleccionar la especialidad del médico.");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        if ($('#frmMedico').find("#tipo_contrato").val() == "") {
            bootbox.alert("Debe seleccionar el tipo de contrato del médico");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        if ($('#frmMedico').find("#id_profesion").val() == "") {
            bootbox.alert("Debe seleccionar la profesión del médico");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        if ($('#frmMedico').find("#id_profesion").val() == "4") {
            if ($('#frmMedico').find("#rne").val() == "") {
                bootbox.alert("Selecciono MEDICO ESPECIALISTA, portanto deberá ingresar el Nro. RNE");
                $('#btnReg').prop('disabled', false);
                return false;
            }
        }


        var ind = $('#example tbody tr').length;
        if(ind > 0){
            var fila_medicamentos=$('#example tbody').children('tr');            
            var fila_medicamento = "";
            var valmedentre = "";
            var nind = 0;
            for (var i = 0; i < ind; i++) {
                fila_medicamento=fila_medicamentos[i];
                valmedentre = $("#consultorio"+nind).val();
                if(valmedentre == ""){
                    bootbox.alert("No selecciono uno de los servicios agregados");
                    $('#btnReg').prop('disabled', false);
                    return false;
                }
                nind++;
            }
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
                        $("#frmMedico").submit();
                    } else {
                        $('#btnReg').prop('disabled', false);
                    }
                }
            });
    }

    function AddFila(){
		
	var newRow = "";
	
	var ind = $('#example tbody tr').length;
	
        var item_servicio 	= "";
        $('#id_consultoriotemp option').each(function(){
                item_servicio += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
        });
	newRow +='<tr>';
	newRow +='<td class="col-sm-9"><select class="form-control consultorio" id="consultorio'+ind+'" name="id_consultorio[]" style="width:100%" >'+item_servicio+'</select></td>';
	newRow +='<td class="col-sm-3"><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='</tr>';
	//alert(newRow);
	$('#example tbody').append(newRow);
        $("#consultorio"+ind).select2({max_selected_options: 4}).focus();
        
        var nind = 0;
        $("#consultorio"+ind).on("change", function (e) {
            var flagx = 0;
            cmb = $(this);
            id_consultorio = $("#consultorio"+ind).val();

            $('.consultorio').each(function(){
                    var ind_tmp = $(this).val();
                    if($(this).val() == id_consultorio)flagx++;
            });

            if(flagx > 1){
                    bootbox.alert("El servicio de atención, ya ha sido seleccionado");
                    $("#consultorio"+ind).val("").trigger("change");
                    return false;
            }
        });
    }
    $(document).ready(function () {
        
        $("#id_establecimiento").select2({
            width: 'resolve', // need to override the changed default
            max_selected_options: 4
        });
        
        $("#id_establecimiento").trigger('change');
        
        $('#addRow').on('click', function () {
            AddFila();
        });

        $('#example tbody').on('click', 'button.deleteFila', function () {
            var obj = $(this);
                obj.parent().parent().remove();
        });
        
        AddFila();
    });

</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/listar_proveedores"><i class="fa fa-table fa-fw"></i> Personal de Salud</a></li>
                <li class="active"><a href="/formulario_proveedor"><i class="fa fa-plus"></i> Nuevo </a></li>   
                <!--<li class="active"><a href="/create_medico/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>-->
                <!--<li><a href="/formatoxls_personal_medico/{{Auth::user()->id}}"><i class="fa fa-download"></i> Descargar Formato</a></li>-->
                <!--<li><a href="/importaciones_medicos/{{Auth::user()->id}}"><i class="fa fa-info-circle"></i> Importarciones</a></li>-->
            </ul>
        </div>
        <div class="col-md-7 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Agregar Proveedor de Salud</h3>
                    </div>
                    <div style="margin-top: 20px">
                        <form id="frmMedico" class="form-horizontal" role="form" method="post"  action="{{ route('store_proveedor') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="col-md-6">
                                    <label>RUC</label>
                                    <input autofocus="" id="ruc_proveedor" name="ruc_proveedor" type="text" class="form-control input-sm" required="" maxlength="11"  onkeypress="return IsNumber(event)">
                                    <input id="id_user" name="id_user" type="hidden" value="{{Auth::user()->id}}">
                                </div>
                                <div class="col-md-6">
                                    <label>E-mail</label>
                                    <input id="email" name="email" type="email" placeholder="" class="form-control input-sm">
                                </div>
                                <div class="col-md-12">
                                    <label>Nombre o Descripcion del Proveedor</label>
                                    <textarea id="nombre_proveedor" name="nombre_proveedor" type="text" class="form-control input-sm" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" ></textarea> 
                                </div>
                                <div class="col-md-12">
                                    <label>Direccion del Proveedor</label>
                                    <textarea id="direccion_proveedor" name="direccion_proveedor" type="text" class="form-control input-sm" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Telefono</label>
                                    <input id="telefono" name="telefono" type="text" maxlength="9"  class="form-control input-sm" required="" style="text-transform:uppercase;" onkeypress="return IsNumber(event)">
                                </div>
                                <div class="col-md-6">
                                    <label>Fax</label>
                                    <input id="fax" name="fax"  type="text" placeholder="" maxlength="9" class="form-control input-sm" onkeypress="return IsNumber(event)">
                                </div>
                                
                                <hr/>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                    <a href="/listar_proveedores/{{ Auth::user()->id }}" class="form-control btn btn-danger" >Cancelar</a>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                    <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">Guardar</button>
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