<?php 
use Carbon\Carbon;
use App\Models\Kardexe;
?>
@extends('frontend.layouts.master')
@section('renderjs')

<style>
    .filterable {
        margin-top: 15px;
    }
    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }
    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }
    .alert-message-success {
        background-color: #f4fdf0;
        border-color: #3c763d;
    }
    .alert-message {
        border-left: 3px solid #eee;
        font-size: 11px;
        margin: 5px 0;
        padding: 5px;

    }

    @media print{
              .oculto-impresion, .oculto-impresion *{
                display: none !important;
              }
            }

</style>

<script>
    $pro = $("#frmAgregarProdcutos").find("#id_producto");
    $pro.select2();
    var sw_presentacion;

    $pro.change(function () {
        //alert("Hola"); exit;
        $("#frmAgregarProdcutos").find("#registro_sanitario").val("");
        $("#frmAgregarProdcutos").find("#lote").val("");
        $("#frmAgregarProdcutos").find("#fecha_vencimiento").val("");
        $("#frmAgregarProdcutos").find("#cantidad").val("");

    });


    function soloNumeros(e)
    {
        var key = window.Event ? e.which : e.keyCode;
        //alert(key);
        return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    $("#btnRegistrar").on("click", function () {
        if ($('#frmAgregarProdcutos').find("#id_producto").val() == "") {
            //alert("Falta Digitar el DNI");
            $("#respuesta").html("");
            $("#respuesta").append(
                    '<p> Falta seleccionar el producto</p>'
                    );
            $('#squarespaceModalValidador').modal();
            return false;
        }
        if (sw_presentacion==1 && $('#frmAgregarProdcutos').find("#registro_sanitario").val() == "") {
            //alert("Falta Digitar el DNI");
            $("#respuesta").html("");
            $("#respuesta").append(
                    '<p> Falta digitar la Presentacion Comercial</p>'
                    );
            $('#squarespaceModalValidador').modal();
            return false;
        }

        if ($('#frmAgregarProdcutos').find("#registro_sanitario").val() == "") {
            //alert("Falta Digitar el DNI");
            $("#respuesta").html("");
            $("#respuesta").append(
                    '<p> Falta digitar el Registro Sanitario</p>'
                    );
            $('#squarespaceModalValidador').modal();
            return false;
        }

        if ($('#frmAgregarProdcutos').find("#lote").val() == "") {
            //alert("Falta Digitar el DNI");
            $("#respuesta").html("");
            $("#respuesta").append(
                    '<p> Falta digitar el lote de producto</p>'
                    );
            $('#squarespaceModalValidador').modal();
            return false;
        }

        if ($('#frmAgregarProdcutos').find("#fecha_vencimiento").val() == "") {
            //alert("Falta Digitar el DNI");
            $("#respuesta").html("");
            $("#respuesta").append(
                    '<p> Falta seleccionar la Fecha de Vencimiento</p>'
                    );
            $('#squarespaceModalValidador').modal();
            return false;
        }

        if ($('#frmAgregarProdcutos').find("#cantidad").val() == "") {
            //alert("Falta Digitar el DNI");
            $("#respuesta").html("");
            $("#respuesta").append(
                    '<p> Falta agregar la cantidad</p>'
                    );
            $('#squarespaceModalValidador').modal();
            return false;
        }
        if ($('#frmAgregarProdcutos').find("#cantidad").val() <= 0) {
            //alert("Falta Digitar el DNI");
            $("#respuesta").html("");
            $("#respuesta").append(
                    '<p> La cantidad ingresada solo debe ser entero positivo y mayor a cero</p>'
                    );
            $('#squarespaceModalValidador').modal();
            return false;
        }

    });

    function validarIngreso() {

        var flag = true;
        var j = 0;
        $("input[name='registro_sanitario\\[\\]']").each(function () {
            var rs = $(this).val().toString()
            if (rs == "0" || rs == "") {
                j++;
            }

        });

        if (j > 0) {
            flag = false;
            alert("Debe cargar el registro sanitario de todos los items");
        }

        return flag;

    }

    function validateFrm() {
        var msg = "";
        var sw = true;
        var nota_entrada = $('#nota_entrada').val();
        var nota_entrada_actu = $('#nota_entrada_actu').val();
        var flag = true;
        var j = 0;
        var nprod = $('#nprod').val();

        $("input[name='registro_sanitario\\[\\]']").each(function () {
            var rs = $(this).val().toString()
            if (rs == "0" || rs == "") {
                j++;
            }

        });

        if (j > 0) {
            msg += "Debe cargar el registro sanitario de todos los items.<br/>";
            sw = false;
        }

        if (nprod == 0) {
            msg += "Debe de registrar al menos un producto para finalizar el ingreso.<br/>";
            sw = false;
        }


        /*if (nota_entrada == "0") {
            msg += "La nota de entrada debe ser diferente a 0.<br/>";
            sw = false
        }

        if (esEntero(nota_entrada) == false) {
            msg += "La nota de entrada debe ser un número entero.<br/>";
            sw = false
        }
        if (parseInt(nota_entrada) < parseInt(nota_entrada_actu)) {
            msg += "La nota de entrada no debe ser menor al último correlativo generado.<br/>";
            sw = false
        }
        */

        if (sw == false) {
            bootbox.alert(msg);
            return sw;
        } else {
            submitFrm();
        }
        return false;
    }

    function submitFrm() {
        bootbox.confirm({
            message: "Se recepcionarán todos los productos mostrados, ¿Está seguro de continuar?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result == true) {
                    document.frmUpdateRecepcion.submit();
                }
            }
        });
    }

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
        if (charCode != 8) {
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

    $('#divPresentacion').hide();

    function muestra_campo(){
        
        var id_producto = $("#id_producto").val();
        
        $.ajax({
            url: '/obtener_producto_presentacion_comercial/' + id_producto ,
            dataType: "json",
            success: function(result){
                if(result.sw == true){     
                    sw_presentacion=1;               
                    $('#divPresentacion').show();
                }
                else
                {
                    sw_presentacion=0;
                    $('#divPresentacion').hide();
                }
            }
            
        });
    }
    $(document).ready(function () {

        /*$('#fecha_vencimiento').click(function () {
            var fechaVencimientoIni = $('#fecha_vencimiento').val();
            $('#fecha_vencimiento').change(function () {
                var fechaVencimiento = $('#fecha_vencimiento').val();
                f1 = fechaVencimiento.split("-");
                f3 = new Date();
                var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                
                if (f1 < f3) {
                    bootbox.alert("La Fecha de Vencimiento no debe ser menor a la Fecha actual");
                    $('#fecha_vencimiento').val(fechaVencimientoIni);
                    return false;
                }
            });
        });
        $('#fecha_vencimiento').trigger('change');*/
   });

</script>
@endsection
@section('content')
<div class="container" style="width:100%">
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Ingreso desde <?php if($dato_pecosa->nom_origen == ""){echo "Almacen Central del SALUDPOL";}else{echo $dato_pecosa->nom_origen;}?> - PECOSA Nro. {{$dato_pecosa->numero}} - {{$dato_pecosa->ano_nota}}                

                </h3>
                
            </div>
            <div class="panel-body">
                <legend>
                    {{$nombre_farmacia}}
                    <div class="pull-right oculto-impresion">
                        <a class="btn btn-default btn-xs" href="/registrar_pecosa/{{ Auth::user()->id_farmacia}}"> <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar</a>                            
                    </div>
                </legend>
                <div class="pull-right">Fecha Recepción:  
                    <?php
                    $originalDate1 = $dato_pecosa->fecha_entrada;
                    $newDate1 = date("d-m-Y", strtotime($originalDate1));
                    echo $newDate1;
                    ?> 
                </div>

                <form class="form-horizontal" method="post" action="{{ route('add_producto_pecosa')}}" id="frmAgregarProdcutos">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div  class="row oculto-impresion">
                        <div class="col-sm-8 col-md-8">   
                            <label>Producto</label>
                            <select onchange="muestra_campo()" class="form-control" id="id_producto" name="id_producto">
                                <option value="">Seleccionar Producto)</option> 
                                <?php if(count($productos > 0)){?>
                                @foreach($productos as $pf)
                                <option value="{{$pf->id}}">{{$pf->codigo}} - {{$pf->nombre}}</option>
                                @endforeach
                                <?php }?>
                            </select>
                            <p><small>Escoger el producto que desea ingresar</small></p>
                        </div>
                        <div class="col-sm-2 col-md-2" id="divPresentacion">
                            <label>Presentacion Comercial</label> 
                            <input class="form-control" type="text" id="presentacion_comercial" name="presentacion_comercial" value="" maxlength="10" onkeypress="return validateFloatKeyPress(this, event);" autocomplete="off">
                            <p style="color: red"><small>Llena campo solo nutrientes</small></p>
                        </div>
                    </div>
                    <div  class="row oculto-impresion">
                        <div class="col-md-2">
                            <label>Reg. Sanitario</label> 
                            <input value="" name="registro_sanitario" id="registro_sanitario" class="form-control"  type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" maxlength="20">
                            <input value="{{$dato_pecosa->numero}}" name="numero_movimiento" class="form-control" type="hidden">
                            <input value="{{$dato_pecosa->id}}" name="id_pecosa" class="form-control" type="hidden">
                            <input value="" name="nombre_farmacia" class="form-control" type="hidden">
                            <input value="{{ Auth::user()->id_farmacia}}" name="id_farmacia" class="form-control" type="hidden">
                            <input value="{{ Auth::user()->id}}" name="id_user" class="form-control" type="hidden">
                            <input value="{{$dato_pecosa->fecha_entrada}}" name="fecha" type="hidden">
                            <input value="{{$dato_pecosa->id}}" name="id_guia_entrada" type="hidden">

                        </div>
                        <div class="col-md-2">
                            <label>Lote</label> 
                            <input value="" name="lote"  id="lote" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" maxlength="20">
                        </div>
                        <div class="col-md-2">
                            <label>Fec. Vencimiento</label> 
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control date" maxlength="10">

                        </div>
                        <div class="col-sm-2 col-md-2">
                            <label>Cant.</label> 
                            <input value="" autocomplete="off" onKeyPress="return soloNumeros(event)" name="cantidad" id="cantidad" class="form-control"  type="text" maxlength="7">
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <label>Precio</label> 
                            <!--<input type="number" step="0.001" value="" min="0" required="" name="precio" id="precio" class="form-control" maxlength="10">-->
                            <input class="form-control" type="text" id="precio" name="precio" required="" value="" maxlength="10" onkeypress="return validateFloatKeyPress(this, event);" autocomplete="off">
                        </div>
                            @if($dato_pecosa->estado==1)
                            <div class="col-md-1" style="margin-top: 20px">
                                <input class="btn btn-primary" type="submit" value="Agregar" id="btnRegistrar">
                            </div>
                            @else 
                                <div class="col-md-1" style="margin-top: 20px">
                                    <input class="btn btn-primary" type="submit" value="Agregar" disabled="">
                                </div>                                  
                            @endif

                        

                    </div>
                </form>


                <form name="frmUpdateRecepcion" id="frmUpdateRecepcion" method="post" action="{{ route('insert_producto_pecosa')}}" onsubmit="return validateFrm();"> <!-- onsubmit="return validarIngreso()">-->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input value="{{$dato_pecosa->id}}" name="id_guia_entrada" type="hidden">
                    <input value="{{$dato_pecosa->numero}}" name="numero_movimiento" class="form-control" type="hidden">
                    <input value="{{ Auth::user()->id_farmacia }}" type="hidden" name="id_farmacia">
                    <input value="{{ Auth::user()->id }}" type="hidden" name="id_user">
                    <input value="{{$dato_pecosa->fecha_entrada}}" name="fecha_entrada" type="hidden">
                    <input value="{{$nombre_farmacia}}" type="hidden" name="nombre_farmacia">
                    <input type="hidden" name="nota_entrada_actu" id="nota_entrada_actu" value="{{$dato_pecosa->nota_entrada}}"/>

                    <input type="hidden" name="nota_entrada_actu" id="nota_entrada_actu" value="{{$nea->nro_nea}}"/>
                    <div  class="col-md-12">
                        @if($dato_pecosa->estado==1)
                        <div class="alert-message alert-success oculto-impresion">
                            <h5><b>Aviso Importante</b></h5>
                            <p>
                                Termine de ingresar los productos que contenga la Nota de entrada que esta registrando, luego dar clic en el boton <b>Finalizar Ingreso</b>, sino realiza este último paso no se actualizará el Stock de los productos que esta registrando.
                            </p>
                        </div>
                        @else
                            @if($dato_pecosa->estado==2)
                                @if(Auth::user()->flagresponsablefarmacia==1)   
                                    @if($tiempo<=3)
                                    <!--div class="alert-message  alert-message-danger oculto-impresion"-->
                                    <div class="alert-message alert-info oculto-impresion">
                                        <h5><b>Aviso Importante</b></h5>
                                        <p>
                                            El ingreso ya ha finalizado; pero usted como responsable tiene <b><?php echo 3-$tiempo; ?> dias </b> para hacer correcciones, despues de ese periodo de tiempo no podra hacer modificacion alguna a la nota de entrada. Puede modificar o eliminar un producto ingresado que no tenga movimiento.
                                        </p>
                                    </div>
                                    @endif
                                @endif
                            @endif
                        @endif
                            <legend style="margin-top:15px">
                                Lista de productos agregados
                                @if($dato_pecosa->estado==1)
                                <div class="pull-right oculto-impresion">
                                    Nota de Entrada &nbsp;&nbsp;
                                    <input autofocus onkeypress="return justNumbers(event);" type="text" name="nota_entrada" id="nota_entrada" style="width: 80px; height: 22px; text-align: center;" required="" value="{{$nea->nro_nea}}" autocomplete="OFF">
                                    -<?php echo date("Y") ?>&nbsp;
                                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-key"></i> Finalizar Ingreso</button>
                                </div>
                                @else
                                <div class="pull-right oculto-impresion" style="font-size: 13px; color:#FF6600">
                                    <i class="fa fa-warning"></i> Ingreso Finalizado
                                </div>  
                                @endif
                            </legend>

                    </div>

                    <table class="table" style="font-size:11px">
                        <thead>
                            <tr class="filters">
                                <th>N°</th>
                                <th>Codigo</th>
                                <th>Producto Farmacéutico o Dispositivo Médico</th>
                                <th>Unidad</th>                            
                                <th>Lote</th>
                                <th>Reg. San.</th>
                                <th>F. Vencimiento</th>                            
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th class="oculto-impresion">Obs.</th>
                                @if($dato_pecosa->estado==1)
                                    <th class="oculto-impresion" >Editar</th>
                                    <th class="oculto-impresion">Eliminar</th>
                                @else
                                    @if($dato_pecosa->estado==2)
                                         @if(Auth::user()->flagresponsablefarmacia==1) 
                                                <th class="oculto-impresion" >Editar</th>    
                                                <th class="oculto-impresion">Eliminar</th>
                                        @else
                                            <th class="oculto-impresion">Editar</th>
                                        @endif
                                    @endif
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 0; $cant=0; $total=0; $x=0;?>
                            <input type="hidden" id="nprod" name="nprod" value="{{ $nprod }}">
                            @foreach($productos_recpecionados as $prod)
                            <?php 
                                $model_kardex = new Kardexe();
                                $encontrado = $model_kardex->GetBuscaMovimiento($prod->id_producto, $id_farmacia, $prod->fecha_carga, $prod->registro_sanitario, $prod->lote, $prod->fecha_vencimiento, $prod->precio_promedio);
                            ?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <tr>      
                            <td style="width:2%"><?php $n = $n + 1;
                            echo $n;
                            ?></td>
                            <td style="width:8%">
                                <input value="{{$prod->id}}" type="hidden" name="id_carga_pecosa[]">
                                <input value="{{ Auth::user()->id }}" type="hidden" name="id_user">

                                <input value="{{ $prod->id_guia_entrada }}" type="hidden" name="id_guia_entrada">
                                <input value="{{ $prod->id_producto }}" type="hidden" name="id_producto[]">
                                {{$prod->codigo}}                                
                            </td>
                            <td style="width:30%">{{$prod->nombre}}
                                @if($prod->presentacion==1)
                                    <p style="color: red"><small>({{$prod->cantidad}} x {{$prod->presentacion_comercial}})
                                    </small></p>
                                @endif

                                @if($prod->id_producto==11045 || $prod->id_producto==15938 || $prod->id_producto==14607 || $prod->id_producto==14608)
                                    <p style="color: red"><small>({{$prod->cantidad}} L)
                                    </small></p>
                                @endif
                            </td>
                            <td style="width:10%">{{$prod->unidad}}</td>
                            <td style="width:10%">
                                {{$prod->lote}}
                                <input value="{{$prod->lote}}" type="hidden" name="lote[]" class="" style="width:100%">                                    
                            </td>
                            <td style="width:10%">
                                {{$prod->registro_sanitario}}
                                <input value="{{$prod->registro_sanitario}}" type="hidden" name="registro_sanitario[]" class=""  style="width:100%"> 
                            </td>
                            <td style="width:10%">
                                <?php
                                $newDate4 = '';
                                $newDate2 = '';
                                if ($prod->fecha_vencimiento != '') {
                                    $originalDate2 = $prod->fecha_vencimiento;
                                    $newDate4 = date("d-m-Y", strtotime($originalDate2));
                                    $newDate2 = date("m-Y", strtotime($originalDate2));
                                }
                                echo $newDate2;
                                ?> 
                                <input value="<?php echo $newDate4 ?>" type="hidden" name="fecha_vencimiento[]" class="datepicker"  style="width:100%">
                            </td>
                            <td style="width:10%">
                                <input value="{{$prod->cantidad}}" type="hidden" name="cantidad[]" style="width:100%">
                                @if($prod->id_producto==11045 || $prod->id_producto==15938 || $prod->id_producto==14607 || $prod->id_producto==14608)
                                    {{($prod->cantidad*$prod->presentacion_comercial)/1000}}
                                    <?php $cant=($prod->cantidad*$prod->presentacion_comercial)/1000+$cant;?>
                                @else
                                    {{($prod->cantidad*$prod->presentacion_comercial)}}
                                    <?php $cant=$prod->cantidad*$prod->presentacion_comercial+$cant;?>
                                @endif
                            </td>
                            <td style="width:10%">
                                <?php echo number_format($prod->precio_promedio, 5, '.', ''); ?>
                                <input value="{{$prod->precio_promedio}}" type="hidden" name="precio_promedio[]" style="width:100%">
                            </td>
                            <td style="width:10%">
                                @if($prod->id_producto==11045 || $prod->id_producto==15938 || $prod->id_producto==14607 || $prod->id_producto==14608)
                                    <?php $sub_total=($prod->cantidad*$prod->presentacion_comercial)/1000*$prod->precio_promedio; echo number_format($sub_total, 5, '.', ''); $total+=$sub_total; ?>
                                @else
                                    <?php $sub_total=$prod->cantidad*$prod->presentacion_comercial*$prod->precio_promedio; echo number_format($sub_total, 5, '.', ''); $total+=$sub_total; ?>
                                @endif
                                <input value="{{$prod->precio_promedio}}" type="hidden" name="precio_promedio[]" style="width:100%">
                            </td>
                            <td class="oculto-impresion" style="width:10%">{{$prod->observacion}}</td>
                            @if($dato_pecosa->estado==1)
                            <td class="oculto-impresion" style="width:5%; text-align: center">                
                                <a class="btn btn-primary btn-md" href="/editar_producto_carga_pecosa/{{$prod->id}}/{{ Auth::user()->id_farmacia }}"><i class="fa fa-edit"></i></a>
                            </td>
                            <td class="oculto-impresion" style="width:5%; text-align: center">
                                <a class="btn btn-danger btn-xs" href="/eliminar_producto_carga_pecosa/{{$prod->id}}/{{ $prod->id_guia_entrada }}/{{ Auth::user()->id }}"><i class="fa fa-trash"></i></a>
                            </td>
                            @else
                                @if($dato_pecosa->estado==2)
                                    @if(Auth::user()->flagresponsablefarmacia==1)
                                        @if($encontrado->mov==0)
                                            @if($tiempo>30)
                                                <td class="oculto-impresion" style="width:5%; text-align: center">
                                                    <a class="btn btn-primary btn-md" href="/editar_producto_pecosa_actualizar/{{$prod->id}}/{{ Auth::user()->id_farmacia }}"><i class="fa fa-edit"></i></a>
                                                </td>
                                                <td class="oculto-impresion" style="width:5%; text-align: center">
                                                    <a class="btn btn-danger btn-md" href="/eliminar_producto_pecosa_actualizar/{{$prod->id}}/{{ $prod->id_guia_entrada }}/{{ Auth::user()->id }}/{{ $prod->id_producto }}"><i class="fa fa-trash"></i></a>
                                                </td>
                                            @else
                                                @if(Auth::user()->flagestablecimientos==1)
                                                    <td class="oculto-impresion" style="width:5%; text-align: center">
                                                        <a class="btn btn-primary btn-md" href="/editar_producto_pecosa_actualizar/{{$prod->id}}/{{ Auth::user()->id_farmacia }}"><i class="fa fa-edit"></i></a>
                                                    </td>
                                                    <td class="oculto-impresion" style="width:5%; text-align: center">
                                                        <a class="btn btn-danger btn-md" href="/eliminar_producto_pecosa_actualizar/{{$prod->id}}/{{ $prod->id_guia_entrada }}/{{ Auth::user()->id }}/{{ $prod->id_producto }}"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                @else
                                                    <td class="oculto-impresion" style="width:5%; text-align: center">
                                                        <a class="btn btn-primary btn-md" disabled="disabled" href="#"><i class="fa fa-edit"></i></a>
                                                    </td>
                                                    <td class="oculto-impresion" style="width:5%; text-align: center">
                                                        <a class="btn btn-danger btn-md" disabled="disabled"  href="#"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                @endif
                                            @endif
                                        @else
                                            @if(Auth::user()->flagestablecimientos==1)
                                                    <td class="oculto-impresion" style="width:5%; text-align: center">
                                                        <a class="btn btn-warning btn-md" href="/editar_producto_pecosa_actualizar/{{$prod->id}}/{{ Auth::user()->id_farmacia }}"><i class="fa fa-edit"></i></a>
                                                    </td>
                                                    <td class="oculto-impresion" style="width:5%; text-align: center">
                                                        <a class="btn btn-danger btn-md" disabled="disabled"  href="#"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                @else
                                                    <td class="oculto-impresion" style="width:5%; text-align: center">
                                                        <a class="btn btn-primary btn-md" disabled="disabled" href="#"><i class="fa fa-edit"></i></a>
                                                    </td>
                                                    <td class="oculto-impresion" style="width:5%; text-align: center">
                                                        <a class="btn btn-danger btn-md" disabled="disabled"  href="#"><i class="fa fa-trash"></i></a>
                                                    </td>
                                            @endif
                                        @endif
                                    @else
                                        <td class="oculto-impresion" style="width:5%; text-align: center">
                                            <button class="btn btn-primary btn-xs" type="submit" disabled=""><i class="fa fa-edit"></i></button>
                                        </td>
                                    @endif
                                @endif
                            @endif
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" align="right"><strong>Total</strong></td>
                                <td><?php echo $cant ?>
                                <td></td>
                                <td><?php echo number_format($total, 6, '.', ''); ?></td>
                                <td colspan="3"></td>
                                
                            </tr>
                        </tfoot>
                    </table>

                    <?php if ($dato_pecosa->estado == 2): ?>
                        <?php if ($almacen == 1): ?>
                            <a href="/print_nota_entrada/<?php echo $dato_pecosa->id ?>" class="btn btn-danger pull-right oculto-impresion" target="_blank">
                                Imprimir NEA <i class="fa fa-file-pdf-o"></i>
                            </a>
                        <?php else: ?>
                            <a href="/imprimir_nota_entrada_pecosa/<?php echo $dato_pecosa->id ?>" class="btn btn-danger pull-right oculto-impresion" target="_blank">
                                Imprimir NEA <i class="fa fa-file-pdf-o"></i>
                            </a>
                        <?php endif; ?>    
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModalValidador" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
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

<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="lineModalLabel">Editar Requerimiento</h4>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('update_pedido')}}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </div> 
                    <div class="form-group" style="text-align:right; ">
                        <input id="btnInvalidar" class="btn btn-primary" type="submit" value="Guardar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
