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
        
        cantidad=parseInt($('#frmPecosa').find("#cantidad").val());
        cantidad_total=parseInt($('#frmPecosa').find("#total_cantidad").val());
        concepto=$('#frmPecosa').find("#concepto").val();

        if (cantidad == 0) {
            alert("Cantidad debe ser entero positivo y mayor que cero");
            return false;
        }

        if(concepto==1){
            if (cantidad_total<cantidad) {
                alert("Cantidad Total debe ser mayor a la cantidad");
                return false;
            }            
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
            if (caratPos > dotPos && dotPos > -1 && (number[1].length > 8)) {
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

    $("#cantidad").blur(function (event) {
        var concepto=$('#concepto').val();
            if(concepto==1)
                getValidaCantidad();
    });

    function getValidaCantidad() {

            
            var id_pecosa = $("#id_guia_entrada").val();
            var id_producto = $("#id_producto").val();
            var cantidad = $("#cantidad").val();
            var concepto=$('#concepto').val();
            var id_carga_pecosa=$('#id_carga_pecosa').val();

            $.ajax({
                url: '/valida_productos_guia_entrada_modificado/' + id_pecosa + '/' + id_producto  + '/' + cantidad   + '/' + id_carga_pecosa,
                success: function (result) {

                    if(result[0]==1){   
                        
                        bootbox.alert("La suma de las cantidades ingresadas del producto debe ser menor a la cantidad total.");
                        $('#btnReg').prop('disabled', true);

                    }
                    else
                    {
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
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-sm-8 col-md-10">
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Editar datos del Producto: {{$productos->nombre}}, {{$productos->unidad}}
                    </h3>
                    <div class="pull-right">
                        <a class="btn btn-info btn-md" href="/recepcionar_productos_guia_entrada/{{$productos->id_guia_entrada}}/{{ Auth::user()->id }}"> <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar</a>                            
                    </div>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{ route('actualizar_producto_nota_entrada_directo')}}" id="frmPecosa">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">  
                            <label>Código</label>
                            <input id="" name="text" type="text"  class="form-control input-md" readonly="" value="{{$productos->codigo}}"> 
                            <input value="{{$productos->id}}" type="hidden" name="id_carga_pecosa" id="id_carga_pecosa">
                            <input value="{{$productos->id_producto}}" type="hidden" name="id_producto" id="id_producto">
                            <input value="{{$productos->id_guia_entrada}}" type="hidden" id="id_guia_entrada" name="id_guia_entrada">
                            <input value="{{$productos->presentacion}}" type="hidden" id="presentacion" name="presentacion">                       
                            <input value="{{Auth::user()->id_farmacia}}" type="hidden" name="id_farmacia">
                            <input value="{{ Auth::user()->id }}" type="hidden" name="id_user">
                            <input value="{{ $concepto }}" type="hidden" name="concepto">
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
                            <input class="form-control" type="text" id="precio_promedio" name="precio_promedio" required="" value="{{$productos->precio_promedio}}" maxlength="12" onkeypress="return validateFloatKeyPress(this,event);" autocomplete="off">

                        </div> 
                        @if($productos->presentacion==1)
                        <div class="col-md-3" style="margin-top:10px">  
                            <label>Presentacion Comercial</label>
                            <input class="form-control" type="text" id="presentacion_comercial" name="presentacion_comercial" required="" value="{{$productos->presentacion_comercial}}" maxlength="10" onkeypress="return validateFloatKeyPress(this,event);" autocomplete="off">
                        </div>
                        @endif
                        @if($concepto==7 || $concepto==8 || $concepto==1)
                            <div class="col-sm-3 col-md-3" style="margin-top:10px">  
                                <label>Factura</label> 
                                <input class="form-control" type="text" id="factura" name="factura" required="" value="<?php echo $productos->factura; ?>" maxlength="10" autocomplete="off">
                            </div>
                            <div class="col-sm-3 col-md-3" style="margin-top:10px">                              
                                <label>Nro Guia</label> 
                                <input class="form-control" type="text" id="nro_guia" name="nro_guia" required="" value="<?php echo $productos->guia_entrada; ?>" maxlength="10" autocomplete="off">
                            </div>
                            @if($concepto==1)
                            <div class="col-sm-3 col-md-3" style="margin-top:10px">                              
                                <label>Total Cantidad</label> 
                                <input class="form-control" type="text" id="total_cantidad" name="total_cantidad" required="" value="<?php echo $productos->total_cantidad; ?>" maxlength="10" autocomplete="off">
                            </div>
                            @endif
                            <div class="col-sm-3 col-md-3" style="margin-top:10px">                              
                                <label>Marca</label> 
                                <input class="form-control" type="text" id="marca" name="marca" required="" value="<?php echo $productos->marca; ?>" maxlength="100" autocomplete="off">
                            </div>
                        @endif
                        <div class="col-md-12" style="margin-top:10px">  
                            <label>Observaci&oacute;n</label>
                            <textarea id="observacion" name="observacion" class="form-control input-md" ><?php echo nl2br($productos->observacion) ?></textarea>
                        </div>
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
