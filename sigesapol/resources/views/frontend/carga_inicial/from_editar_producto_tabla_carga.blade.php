@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $consul = $("#frmEditarProductos").find("#id_producto");
    $consul.select2();
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
    
    
     $("#btnReg").on("click", function () { 
       /*if ($('#frmEditarProductos').find("#id_producto").val() == "") {
                alert("Falta Seleccionar el producto");
                return false;
        }
        if ($('#frmAgregarProductos').find("#lote").val() == "") {
                alert("Falta Seleccionar el lote del producto");
                return false;
        }
        
        if ($('#frmAgregarProductos').find("#registro_sanitario").val() == "") {
                alert("Falta Seleccionar el Registro Sanitario del producto");
                return false;
        }
        if ($('#frmAgregarProductos').find("#fecha_vencimiento").val() == "") {
                alert("Falta Seleccionar la Fecha de Vencimiento");
                return false;
        }*/
        if ($('#frmEditarProductos').find("#fecha_vencimiento").val().length < 10) {
                alert("Falta Digitar la fecha de caducidad");
                return false;
        }
        if ($('#frmEditarProductos').find("#cantidad").val() == "") {
                alert("Falta Digitar la cantidad del producto");
                return false;
        }
        if ($('#frmEditarProductos').find("#cantidad").val() == 0) {
                alert("La cantidad de ser mayor a cero y entero positivo");
                return false;
        }
                
    });
</script>
<style>
    .etiqueta{margin-top: 5px; margin-bottom: 5px; font-size: 13px}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 pull-right">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Actulizar Producto a la Carga Inicial</h3>   
                    <a class="btn btn-default btn-xs pull-right" href="/get_insert_cabecera_carga_inicial/{{ Auth::user()->id_farmacia }}/{{$producto->id_carga_inicial}}">
                        <i class="fa fa-arrow-left"></i> Volver</a>
                </div>
                <div class="panel-body">
                    <form id="frmEditarProductos" method="post" action="{{ route('update_producto_tabla_cargas')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12">
                            <label>Productos</label>
                            <select name="id_producto" id="id_producto" required="" class="form-control">
                                <option value="{{$producto->id_producto}}">{{$producto->codigo_producto}} - {{$producto->nombre_producto}} - {{$producto->unidad}}</option>
                                <!--@foreach($productos as $p)
                                <option value="{{$p->id}}">{{$p->codigo}} - {{$p->nombre}} - {{$p->unidad}} </option>
                                @endforeach-->
                            </select>
                            <input name="id_carga" id="id_carga" type="hidden" value="{{$producto->id}}">
                            <input name="id_carga_inicial" id="id_carga_inicial" type="hidden" value="{{$producto->id_carga_inicial}}">
                            <input name="id_farmacia" id="id_farmacia" type="hidden" value="{{ Auth::user()->id_farmacia }}">
                        </div>
                        <div class="col-md-6">
                            <label>Lote</label>
                            <input value="{{$producto->lote}}" name="lote" id="lote" type="text" maxlength="20" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-md-6">
                            <label>Registro Sanitario</label>
                            <input value="{{$producto->registro_sanitario}}" name="registro_sanitario" id="registro_sanitario" type="text" maxlength="20" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-md-6">
                            <label>Fecha Vencimiento</label>
                            <?php
                            $originalDate2 = $producto->fecha_vencimiento;
                            $fecha = date("d-m-Y", strtotime($originalDate2));
                            ?>
                            <input value="<?php echo $fecha; ?>"  name="fecha_vencimiento" id="fecha_vencimiento" type="text" class="datepicker form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Cantidad</label>
                            <input value="{{$producto->cantidad}}"  name="cantidad" id="cantidad"  type="text" class="form-control" onkeydown="return jsDecimals(event);">
                        </div>

                        <div class="col-md-6">
                            <label>Precio Promedio</label>
							
							<?php
								/*									
								if(App::environment('desarrollo')):
									$client = new SoapClient("http://192.168.0.243/monitoreo/webservices/server.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
								elseif(App::environment('local')):
									$client = new SoapClient("http://10.19.39.10:8088/webservices/server.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
								endif;
								*/
								
								$PRECIOUNITARIO_COSTOPROMEDIO = $producto->precio_promedio;
								/*
								if($producto->precio_promedio=="" || $producto->precio_promedio==0){
									$result = $client->ObtenerPrecioPromedio($producto->codigo_producto);
									$PRECIOUNITARIO_COSTOPROMEDIO=$result->PRECIOUNITARIO_COSTOPROMEDIO;
								}
								*/
							?>

                            <input value="<?php echo $PRECIOUNITARIO_COSTOPROMEDIO?>"  name="precio_promedio" id="precio_promedio"  type="text" class="form-control" onkeydown="return jsDecimals(event);">
                        </div>

                        <div class="col-md-12 etiqueta" >
                            <input class="btn btn-primary btn-xs form-control" type="submit" value="Actualizar" id="btnReg">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('frontend.includes.footer')
@endsection