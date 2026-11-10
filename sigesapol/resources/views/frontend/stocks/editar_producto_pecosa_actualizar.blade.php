@extends('frontend.layouts.master')
@section('renderjs')

<style>

</style>

<script>
    function soloNumeros(e)
    {
        var key = window.Event ? e.which : e.keyCode;
        //alert(key);
        return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    $("#btnReg").on("click", function () {
        if ($('#frmPecosa').find("#cantidad").val() == 0) {
            alert("Cantidad debe ser entero positivo y mayor que cero");
            return false;
        }

    });

    function validateFloatKeyPress(el, evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        //just one dot
        if (number.length > 1 && charCode == 46) {
            return false;
        }
        //get the carat position
        var caratPos = getSelectionStart(el);//cantidad de caracteres no valida nada
        var dotPos = el.value.indexOf(".");
        if (charCode != 8){
            if (caratPos > dotPos && dotPos > -1 && (number[1].length > 4)) {
                return false;
            }
        }
        return true;
    }

    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '')
                return o.value.length
            return o.value.lastIndexOf(r.text)
        } else
            return o.selectionStart
    }


</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-sm-8 col-md-10">
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Editar datos del Producto: {{$productos->nombre}}, {{$productos->unidad}}
                    </h3>
                    <div class="pull-right">
                        <a class="btn btn-info btn-xs" href="/recepcionar_productos_pecosa/{{$productos->id_guia_entrada}}/{{ Auth::user()->id }}"> <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar</a>                            
                    </div>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{ route('actualizar_producto_pecosa_directo')}}" id="frmPecosa">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">  
                            <label>Código</label>
                            <input id="" name="text" type="text"  class="form-control input-md" readonly="" value="{{$productos->codigo}}"> 
                            <input value="{{$productos->id}}" type="hidden" name="id_carga_pecosa">
                            <input value="{{$productos->id_producto}}" type="hidden" name="id_producto">
                            <input value="{{$farmacia->id}}" type="hidden" name="id_farmacia">
                            <input value="{{$productos->id_guia_entrada}}" type="hidden" name="id_guia_entrada">
                            <input value="{{ Auth::user()->id }}" type="hidden" name="id_user">
                        </div>
                        <div class="col-md-6">  
                            <label>Producto</label>
                            <input id="" name="text" type="text"  class="form-control input-md" readonly="" value="{{$productos->nombre}}">                     
                        </div>
                        <div class="col-md-3">  
                            <label>Unidad</label>
                            <input id="" name="text" type=""  class="form-control input-md" readonly="" value="{{$productos->unidad}}">                     
                        </div>
                        <div class="col-md-3" style="margin-top:10px">  
                            <label>Lote</label>
                            <input id="" name="lote" type="text"  class="form-control input-md" required="" value="{{$productos->lote}}" >                     
                        </div>
                        <div class="col-md-3" style="margin-top:10px">  
                            <label>Registro Sanitario</label>
                            <input id="" name="registro_sanitario" type="text"  class="form-control input-md" required="" value="{{$productos->registro_sanitario}}" >                     
                        </div>
                        <div class="col-md-3" style="margin-top:10px">  
                            <label>Fecha de Vencimiento</label>
                            <?php
                            $newDate2 = "";
                            if ($productos->fecha_vencimiento != ""):
                                $originalDate2 = $productos->fecha_vencimiento;
                                $newDate2 = date("d-m-Y", strtotime($originalDate2));
                            endif;
                            ?> 
                            <input id="" name="fecha_vencimiento" type="text"  class="form-control input-md datepicker" required="" value="<?php echo $newDate2 ?>" >                     
                        </div>
                        <div class="col-md-3" style="margin-top:10px">  
                            <label>Cantidad</label>
                            <input autocomplete="off" onKeyPress="return soloNumeros(event)" id="cantidad" name="cantidad" type="text"  class="form-control input-md" required="" value="<?php if($productos->id_producto==11045 || $productos->id_producto==15938 || $productos->id_producto==14607 || $productos->id_producto==14608):echo ($productos->cantidad)/1000;else:echo $productos->cantidad;endif;?>">                     
                        </div>

                        <div class="col-md-3" style="margin-top:10px">  
                            <label>Precio Promedio</label>
                            <!--<input type="number" step="0.001" id="precio_promedio" name="precio_promedio"  min="0" required="" value="{{$productos->precio_promedio}}" maxlength="10" onkeydown="return soloDinero(this, event);">-->
                            <input class="form-control" type="text" id="precio_promedio" name="precio_promedio" required="" value="{{$productos->precio_promedio}}" maxlength="10" onkeypress="return validateFloatKeyPress(this,event);" autocomplete="off">

                        </div>
                        @if($productos->presentacion==1)
                        <div class="col-md-3" style="margin-top:10px">  
                            <label>Presentacion Comercial</label>
                            <input autocomplete="off" onKeyPress="return soloNumeros(event)" id="presentacion_comercial" name="presentacion_comercial" type="text"  class="form-control input-md" required="" value="{{$productos->presentacion_comercial}}">                     
                        </div>
                        @endif

                        <div class="col-md-12" style="margin-top:10px">  
                            <label>Observaci&oacute;n</label>
                            <textarea id="observacion" name="observacion" class="form-control input-md" ><?php echo nl2br($productos->observacion) ?></textarea>
                        </div>


                        <!--<div class="col-md-12" style="margin-top:10px">  
                            <label>Observaci&oacute;n Generada</label>
                            <label style="text-align:left;display:block"><?php echo nl2br($productos->observacion) ?></label>
                        </div>-->


                        <div class="col-md-12 pull-right" style="margin-top:10px">                    
                            <input type="submit" class="btn btn-info form-control" value="Actualizar" id="btnReg">                     
                        </div>
                </div>
                </form>
            </div>
        </div> 
    </div>
</div>
@include('frontend.includes.footer')
@endsection
