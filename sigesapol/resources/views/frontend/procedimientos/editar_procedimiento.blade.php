@extends('frontend.layouts.master')
@section('renderjs')
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
        
        const selectedValues = new Array();
        <?php 
            $n = 0;
            foreach ($servicios_elegidos as $rowConsul):
                ?>
                selectedValues[<?php echo $n?>] = "<?php echo $rowConsul->id_servicio?>";
                <?php
                $n++;
            endforeach;
        ?>
        $('.js-example-basic-multiple').val(selectedValues).trigger('change');

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
        $(data).each(function (ii, oo) {
            $select.append('<option value="' + oo.id + '">' + oo.nombre + '</option>')
        });
    }

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
                message: "Aviso: Se actualizarán los registros ingresados. ¿Desea continuar?.",
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
    $('#genero').select2();
    $('#convenio').select2();
</script>
<script src='../../funciones/function.js'></script> 
@endsection
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
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Editar Procedimientos</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="{{ route('update_procedimiento')}}" id="frmProcedimiento" name="frmProcedimiento">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input id="id" name="id" value="{{$procedimientos->id}}" type="hidden">
                        <fieldset>
                            <div class="col-md-6"><br/>
                                <label>Grupo <span class="obligatorio">(*)</span></label>
                                <select class="form-control" id="grupo" name="grupo" tabindex="1" style="width: 100%">
                                    <option value="" selected>-Seleccionar-</option>
                                    <option value="1"<?php if($procedimientos->codigo_grupo == '1')echo "selected='selected'";?>>Servicios médicos y estomatológicos</option>
                                    <option value="2"<?php if($procedimientos->codigo_grupo == '2')echo "selected='selected'";?>>Servicios de salud colectiva</option>
                                </select>
                            </div>
                            <div class="col-md-6 etiqueta"><br/>
                                <label>Seccion <span class="obligatorio">(*)</span></label>
                                <select id="seccion" name="seccion" class="form-control"  tabindex="2" required="required">
                                    <option value="" disabled selected style="color: #888888">Seleccionar uno</option>
                                    @foreach($secciones as $sec)
                                        <?php 
                                            $idsec= $sec->codigo;?>
                                            <option value="{{$sec->id}}" <?php if($procedimientos->codigo_seccion == $idsec) {echo " selected";}?>>{{$sec->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12"><br/>
                                <label>Sub Seccion <span class="obligatorio">(*)</span></label>
                                <select id="subseccion" name="subseccion" class="form-control" tabindex="3" required="required">
                                    <option value="" disabled selected style="color: #888888">Seleccionar uno</option>
                                    @foreach($subsecciones as $sec)
                                        <?php 
                                            $idsubsec= $sec->codigo;?>
                                            <option value="{{$sec->id}}" <?php if($procedimientos->codigo_subseccion == $idsubsec) {echo " selected";}?>>{{$sec->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12"><br/>
                                    <div class="col-md-3">
                                        <label>Código <span class="obligatorio">(*)</span></label>
                                        <input name="codigo" id="codigo" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'codigo');" value="{{ $procedimientos->codigo }}" tabindex="4" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    </div>  
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12"><br/>
                                        <label>Nombre o Descripcion del Procedimiento <span class="obligatorio">(*)</span></label>
                                        <input name="descripcion" id="descripcion" id="descripcion" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'descripcion');" value="{{ $procedimientos->descripcion }}" tabindex="5" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    </div>  
                                </div>
                                <div class="col-md-12"><br/>
                                    <div class="col-md-3">
                                            <label>Abreviatura</label>
                                            <input name="codigo" value="{{ $procedimientos->codigo }}" required="" tabindex="6"  class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tarifa Nivel 1 <span class="obligatorio">(*)</span></label>
                                        <input name="t_nivel1" id="t_nivel1" onkeypress="return noEspaciosInicioSoloPrecios(event,'t_nivel1',2);" value="{{ $procedimientos->t_nivel1 }}" required="" tabindex="7" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tarifa Nivel 2 <span class="obligatorio">(*)</span></label>
                                        <input name="t_nivel2" id="t_nivel2" onkeypress="return noEspaciosInicioSoloPrecios(event,'t_nivel2',2);"  value="{{ $procedimientos->t_nivel2 }}" required="" tabindex="8" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tarifa Nivel 3 <span class="obligatorio">(*)</span></label>
                                        <input name="t_nivel3"  id="t_nivel3" onkeypress="return noEspaciosInicioSoloPrecios(event,'t_nivel3',2);" value="{{ $procedimientos->t_nivel3 }}" required="" tabindex="9" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12"><br/> 
                                    <div class="col-md-6">
                                        <label>Genero <span class="obligatorio">(*)</span></label>
                                        <select class="form-control" id="genero" name="genero" tabindex="10" style="width: 100%">
                                            <option value="" selected>-Seleccionar-</option>
                                            <option value="1" <?php if($procedimientos->genero == 1)echo "selected='selected'";?>>MASCULINO</option>
                                            <option value="2" <?php if($procedimientos->genero == 2)echo "selected='selected'";?>>FEMENINO</option>
                                            <option value="3" <?php if($procedimientos->genero == 3)echo "selected='selected'";?>>AMBOS</option>
                                        </select>
                                    </div>                           
                                    <div class="col-md-6">
                                        <label>Convenio <span class="obligatorio">(*)</span></label>
                                        <select class="form-control" id="convenio" name="convenio" tabindex="11" style="width: 100%">
                                            <option value="" selected>-Seleccionar-</option>
                                            <option value="1" <?php if($procedimientos->convenio == 1)echo "selected='selected'";?>>SI</option>
                                            <option value="0" <?php if($procedimientos->convenio == 0)echo "selected='selected'";?>>NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

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

                        <div class="col-md-12">
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <a href="/listar_procedimientos" class="form-control btn btn-danger" >Cancelar</a>
                            </div>

                            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                                <input id="btnReg" class="btn btn-success form-control" tabindex="12" value="Actualizar" type="button" onclick="validateFrm()">
                            </div>
                        </div>
                    </form>
                </div>       
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
