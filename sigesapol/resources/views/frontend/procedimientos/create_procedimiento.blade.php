@extends('frontend.layouts.master')
@section('renderjs')
<style>
.obligatorio{
  color:red;
}
.borde-alerta {
  border-color: red !important;
  border: 2px red solid !important;
  outline: 0;
  border-radius: 4px;
  box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important; 
}
</style>
<script src='../../funciones/function.js'></script> 
<script>
    $cmbgrupo = $("#frmProcedimiento").find("#grupo");
    $cmbgrupo.select2();
    $cmbseccion = $("#frmProcedimiento").find("#seccion");
    $cmbseccion.select2();
    $cmbsubseccion = $("#frmProcedimiento").find("#subseccion");
    $cmbsubseccion.select2();

    $(document).ready(function () {

        $(".js-example-basic-multiple").select2({
            width: 'resolve', // need to override the changed default
            max_selected_options: 4
        }); 

        $cmbgrupo.change(function () {
            $this = $(this);
            cmbgrupo = $cmbgrupo.val();  
            if($('#grupo').val() =='') {
              cmbgrupo=0;
              $('#grupo').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
            } else {
              $('#grupo').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
            }    
            option = {
                url: '/cargarseccion/' + cmbgrupo,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            
            $.ajax(option).done(function (data) {   
                cargarComboDestino($cmbseccion, data);
                $cmbseccion.val(0).trigger("change");
            });
        });

        $cmbseccion.change(function () {
            $this = $(this);
            cmbseccion = $cmbseccion.val();
            if($('#seccion').val() =='') {
              cmbseccion=0;
              $('#seccion').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
            } else {
              $('#seccion').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
            }

            if(cmbseccion==null){
              cmbseccion=0;
            }
                  
            option = {
                url: '/cargarsubseccion/' + cmbseccion,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            
            $.ajax(option).done(function (data) { 
                cargarComboDestino($cmbsubseccion, data);
                $cmbsubseccion.val(0).trigger("change");
            });
        });

        $cmbsubseccion.change(function () {
          if($('#subseccion').val() =='') {
            $('#subseccion').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
          } else {
            $('#subseccion').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
          } 
        });
        
    });

    function cargarComboDestino($select, data) {
        $select.html('');
        $select.append('<option value="">-Seleccionar-</option>')
        $(data).each(function (ii, oo) {
            $select.append('<option value="' + oo.id + '">' + oo.nombre + '</option>')
        });
    } 


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

        if($('#grupo').val() =='') {
          $('#grupo').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
          return false;
        } else {
          $('#grupo').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }
        
        if($('#seccion').val() == null ||$('#seccion').val() == '' ) {
          $('#seccion').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
          return false;
        } else {
          $('#seccion').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }
        
        if($('#subseccion').val() == null ||$('#subseccion').val() == '') {
          $('#subseccion').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
          return false;
        } else {
          $('#subseccion').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        if ($('#codigo').val() == "") {
          $('#codigo').addClass('borde-alerta');
          $("#codigo").focus();
          $('#btnReg').prop('disabled', false);
          return false;
        }else{
          $('#codigo').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        if ($('#descripcion').val() == "") {
          $('#descripcion').addClass('borde-alerta');
          $("#descripcion").focus();
          $('#btnReg').prop('disabled', false);
          return false;
        }else{
          $('#descripcion').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        if ($('#t_nivel1').val() == "") {
          $('#t_nivel1').addClass('borde-alerta');
          $("#t_nivel1").focus();
          $('#btnReg').prop('disabled', false);
          return false;
        }else{
          $('#t_nivel1').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        if ($('#t_nivel2').val() == "") {
          $('#t_nivel2').addClass('borde-alerta');
          $("#t_nivel2").focus();
          $('#btnReg').prop('disabled', false);
          return false;
        }else{
          $('#t_nivel2').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        if ($('#t_nivel3').val() == "") {
          $('#t_nivel3').addClass('borde-alerta');
          $("#t_nivel3").focus();
          $('#btnReg').prop('disabled', false);
          return false;
        }else{
          $('#t_nivel3').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        if ($('#descripcion').val() == "") {
          $('#descripcion').addClass('borde-alerta');
          $("#descripcion").focus();
          $('#btnReg').prop('disabled', false);
          return false;
        }else{
          $('#descripcion').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }
        
        if($('#genero').val() =='') {
          $('#genero').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
          return false;
        } else {
          $('#genero').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
        }

        if($('#convenio').val() =='') {
          $('#convenio').next('.select2-container').find('.select2-selection--single').addClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
          return false;
        } else {
          $('#convenio').next('.select2-container').find('.select2-selection--single').removeClass('borde-alerta');
          $('#btnReg').prop('disabled', false);
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
                        $("#frmProcedimiento").submit();
                    } else {
                        $('#btnReg').prop('disabled', false);
                    }
                }
            });
    }
$('#genero').select2();
$('#convenio').select2();
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/listar_procedimientos"><i class="fa fa-table fa-fw"></i> Procedimientos</a></li>
                <li class="active"><a href="/formulario_procedimiento"><i class="fa fa-plus"></i> Nuevo </a></li>   
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Agregar Procedimiento</h3>
                    </div>
                    <div style="margin-top: 20px">
                        <form id="frmProcedimiento" class="form-horizontal" role="form" method="post"  action="{{ route('store_procedimiento') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="col-md-6"><br/>
                                    <label>Grupo <span class="obligatorio">(*)</span></label>
                                    <select class="form-control" id="grupo" name="grupo" tabindex="1" style="width: 100%">
                                        <option value="" selected>-Seleccionar-</option>
                                        <option value="1">Servicios médicos y estomatológicos</option>
                                        <option value="2">Servicios de salud colectiva</option>
                                    </select>
                                </div>
                                <div class="col-md-6"><br/>
                                    <label>Seccion <span  class="obligatorio">(*)</span></label>
                                    <select id="seccion" name="seccion" class="form-control" tabindex="2"  required="required">
                                        <option value="" disabled selected style="color: #888888">Seleccionar uno</option>
                                    </select>
                                </div>
                                <div class="col-md-12"><br/>
                                    <label>Sub Seccion <span  class="obligatorio">(*)</span></label>
                                    <select id="subseccion" name="subseccion" class="form-control"  tabindex="3" required="required">
                                        <option value="" disabled selected style="color: #888888">Seleccionar uno</option>
                                    </select>
                                </div>                                
                            </fieldset>
                            <fieldset>                                
                                <div class="row">
                                    <div class="col-md-12"><br/>
                                        <div class="col-md-3">
                                            <label>Código <span  class="obligatorio">(*)</span></label>
                                            <input name="codigo" id="codigo" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'codigo');" tabindex="4" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                        </div>  
                                    </div>
                                    <div class="col-md-12"><br/>
                                        <div class="col-md-12">
                                            <label>Nombre o Descripcion del Procedimiento <span  class="obligatorio">(*)</span></label>
                                            <input name="descripcion" id="descripcion" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'descripcion');" required="" class="form-control" tabindex="5" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                        </div>  
                                    </div>
                                    <div class="col-md-12"><br/>
                                        <div class="col-md-3">
                                            <label>Abreviatura</label>
                                            <input name="codigo" required="" tabindex="6" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Tarifa Nivel 1 <span  class="obligatorio">(*)</span></label>
                                            <input name="t_nivel1" id="t_nivel1" onkeypress="return noEspaciosInicioSoloPrecios(event,'t_nivel1',2);" required="" tabindex="7" class="form-control" style="text-transform:uppercase;">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Tarifa Nivel 2 <span  class="obligatorio">(*)</span></label>
                                            <input name="t_nivel2" id="t_nivel2" onkeypress="return noEspaciosInicioSoloPrecios(event,'t_nivel2',2);" required="" tabindex="8" class="form-control" style="text-transform:uppercase;">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Tarifa Nivel 3 <span  class="obligatorio">(*)</span></label>
                                            <input name="t_nivel3" id="t_nivel3" onkeypress="return noEspaciosInicioSoloPrecios(event,'t_nivel3',2);" required="" tabindex="9" class="form-control" style="text-transform:uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-12"><br/>                                        
                                        <div class="col-md-6">
                                            <label>Genero <span  class="obligatorio">(*)</span></label>
                                            <select class="form-control" id="genero" name="genero" tabindex="10" style="width: 100%">
                                                <option value="" selected>-Seleccionar-</option>
                                                <option value="1">MASCULINO</option>
                                                <option value="2">FEMENINO</option>
                                                <option value="3">AMBOS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Convenio <span  class="obligatorio">(*)</span></label>
                                            <select class="form-control" id="convenio" name="convenio" tabindex="11" style="width: 100%">
                                                <option value="" selected>-Seleccionar-</option>
                                                <option value="1">SI</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 3rem; margin-bottom: 3rem;">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Servicios Afiliados<span class="obligatorio">(*)</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control js-example-basic-multiple" name="id_servicio[]" id="id_servicio" required="" style="width: 100%" multiple="multiple">
                                            @foreach($servicios as $f)
                                            <option value="{{$f->id}}">{{$f->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                            <a href="/listar_procedimientos/{{ Auth::user()->id }}" class="form-control btn btn-danger" >Cancelar</a>
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