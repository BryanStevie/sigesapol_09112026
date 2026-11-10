@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $pro = $("#frmAgregarProductos").find("#id_producto");
    $pro.select2();
    
    $pro.change(function () {
        //alert("Hola"); exit;
        $("#frmAgregarProductos").find("#registro_sanitario").val("");
        $("#frmAgregarProductos").find("#lote").val("");
        $("#frmAgregarProductos").find("#fecha_vencimiento").val("");
        $("#frmAgregarProductos").find("#cantidad").val("");  
    });
    
    
   function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) )
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
        
        
    $("#btnReg").on("click", function () { 
       if ($('#frmAgregarProductos').find("#id_producto").val() == "") {
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
        }
        if ($('#frmAgregarProductos').find("#cantidad").val() == "") {
                alert("Falta Digitar la cantidad del producto");
                return false;
        }
        if ($('#frmAgregarProductos').find("#cantidad").val() == 0) {
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
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Agregar Productos a la Carga Inicial</h3>    
                    <a class="btn btn-default btn-xs pull-right" href="/from_registrar_cabecera_carga_inicial/{{ Auth::user()->id_farmacia }}">
                        <i class="fa fa-arrow-left"></i> Volver</a>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-6 etiqueta">                                
                        <b>Farmacia:</b> {{$farmacia->nombre}}
                    </div>
                    <div class="col-md-3 etiqueta">                                
                        <b>Fecha de carga:</b> 
                        <?php
                        $originalDate2 = $cabecera->fecha_corte;
                        $fecha = date("d-m-Y", strtotime($originalDate2));
                        echo $fecha;
                        ?>
                    </div>
                    <div class="col-md-3 etiqueta">                                
                        <b>Estado:</b>
                        <?php
                        if ($cabecera->estado == 1) {
                            echo "Agregando productos";
                        } else {
                            echo "Carga completa y terminada";
                        }
                        ?>
                    </div>
                    @if($cabecera->estado==1)
                    <form id="frmAgregarProductos" method="post" action="{{ route('agregar_producto_tabla_cargas')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <legend style="font-size:14px">Agregar productos</legend>
                        <div class="col-md-4 etiqueta">                                
                            <label>Productos</label>
                            <select  class="form-control" name="id_producto" id="id_producto">
                                <option value="">--Seleccionar--</option>
                                @foreach($productos as $pro)
                                <option value="{{$pro->id}}">{{$pro->codigo}} - {{$pro->nombre}} - {{$pro->unidad}} </option>
                                @endforeach
                            </select>
                            <input id="id_user" name="id_user" value="{{ Auth::user()->id }}" type="hidden">
                            <input id="id_farmacia" name="id_farmacia" value="{{ Auth::user()->id_farmacia }}" type="hidden">
                            <input id="id_carga_inicial" name="id_carga_inicial" value="{{$cabecera->id}}" type="hidden">
                            <input id="fecha_corte" name="fecha_corte" value="{{$cabecera->fecha_corte}}" type="hidden">
                        </div>
                        <div class="col-md-2 etiqueta">
                            <label>Lote</label>
                            <input id="lote" name="lote" type="text" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" maxlength="20">
                        </div>
                        <div class="col-md-2 etiqueta">
                            <label>Registro Sanitario</label>
                            <input id="registro_sanitario" name="registro_sanitario" type="text"  class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" maxlength="20">
                        </div>
                        <div class="col-md-2 etiqueta">
                            <label>Fecha vencimiento</label>
                            <input id="fecha_vencimiento" name="fecha_vencimiento" type="text"  class="form-control datepicker">
                        </div>
                        <div class="col-md-2 etiqueta">
                            <label>Cantidad</label>
                            <input id="cantidad" name="cantidad"  class="form-control"  onkeypress="return justNumbers(event);" type="text" autocomplete="off">
                        </div>
                        <div class="col-md-2 etiqueta pull-right">
                            <input class="btn btn-success btn-xs form-control" value="Agregar" type="submit" id="btnReg">
                        </div>
                    </form>
                    @endif
                    <div class="col-md-12">
                        <form id="frmCargarStock" method="post" action="{{ route('insert_productos_tabla_stock_farmacias')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="id_farmacia" id="id_farmacia" value="{{ Auth::user()->id_farmacia }}" type="hidden"> 
                            <input name="id_carga_incial" id="id_carga_incial" value="{{ $cabecera->id }}" type="hidden">
                            @if($cabecera->estado==1)
                            <div class="col-md-10 etiqueta">
                                <legend style="font-size:14px">Agregar productos</legend>
                            </div>
                            @else
                            <div class="col-md-12 etiqueta">
                                <legend style="font-size:14px">Productos agregados al stock</legend>
                            </div>
                            @endif
                            @if($cabecera->estado==1)
                            <div class="col-md-2 pull-right etiqueta">
                                <button type="submit" class="btn btn-default btn-xs "><i class="fa fa-arrow-down"></i> Finalizar y cargar al Stock</button>
                            </div>
                            @endif
                            <table class="table" style="font-size: 11px">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>codigo</th>
                                        <th>Producto</th>
                                        <th>Unidad</th>
                                        <th>Lote</th>
                                        <th>Registro Sanitario</th>
                                        <th>Fecha de Vencimiento</th>
                                        <th>Cantidad</th>
					<th>Precio Promedio</th>
                                        @if($cabecera->estado==1)
                                        <th>Editar</th>
                                        <th>Eliminar</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n = 0; ?>
                                    @foreach($productos_agregados as $productos)
                                    <tr>
                                        <td><?php $n = $n + 1;
                                    echo $n;
                                    ?></td>
                                        <td>
                                            {{$productos->codigo_producto}}
                                            <input name="id_producto[]" id="id_producto" value="{{$productos->id_producto}}" type="hidden">
                                            <input name="codigo_producto[]" id="codigo_producto" value="{{$productos->codigo_producto}}" type="hidden">
                                        </td>
                                        <td>
                                            {{$productos->nombre_producto}}
                                            <input name="nombre_producto[]" id="nombre_producto" value="{{$productos->nombre_producto}}" type="hidden">                                            
                                        </td>
                                        <td>
                                            {{$productos->unidad}}                                                
                                        </td>
                                        <td>
                                            {{$productos->lote}}
                                            <input name="lote[]" id="lote" value="{{$productos->lote}}" type="hidden">
                                        </td>
                                        <td>
                                            {{$productos->registro_sanitario}}
                                            <input name="registro_sanitario[]" id="registro_sanitario" value="{{$productos->registro_sanitario}}" type="hidden">
                                        </td>
                                        <td>
                                            <?php
                                            $originalDate3 = $productos->fecha_vencimiento;
                                            $fecha_vencimiento = date("d-m-Y", strtotime($originalDate3));
                                            echo $fecha_vencimiento;
                                            ?>
                                            <input name="fecha_vencimiento[]" id="fecha_vencimiento" value="{{$productos->fecha_vencimiento}}" type="hidden">
                                            <input name="fecha_ingreso[]" id="fecha_ingreso" value="{{$productos->fecha_ingreso}}" type="hidden">
                                        </td>
                                        <td>
                                            {{$productos->cantidad}}
                                            <input name="cantidad[]" id="cantidad" value="{{$productos->cantidad}}" type="hidden">
                                        </td>
										<td align="center">
										
											{{$productos->precio_promedio}}
											<?php
											/*
											if(App::environment('desarrollo')):
												$client = new SoapClient("http://192.168.0.243/monitoreo/webservices/server.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
											elseif(App::environment('local')):
												$client = new SoapClient("http://10.19.39.10:8088/webservices/server.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
											endif;
											
											$PRECIOUNITARIO_COSTOPROMEDIO = $productos->precio_promedio;
											if($productos->precio_promedio=="" || $productos->precio_promedio==0){
												$result = $client->ObtenerPrecioPromedio($productos->codigo_producto);
												$PRECIOUNITARIO_COSTOPROMEDIO=$result->PRECIOUNITARIO_COSTOPROMEDIO;
											}
											
											echo $PRECIOUNITARIO_COSTOPROMEDIO;
											*/
											?>

                                            <input name="precio_promedio[]" id="precio_promedio" value="<?php echo $productos->precio_promedio?>" type="hidden">
                                        </td>
                                        @if($cabecera->estado==1)
                                        <td><a class="btn btn-primary btn-xs" href="/editar_producto_cargado_tabla_cargas/{{$productos->id}}">Editar</a></td>
                                        <td><a class="btn btn-danger btn-xs" href="/eliminar_producto_cargado_tabla_cargas/{{$productos->id}}/{{$productos->id_carga_inicial}}/{{ Auth::user()->id_farmacia }}">Eliminar</a></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($cabecera->estado==1)
                            <div class="col-md-2 pull-right etiqueta">
                                <button type="submit" class="btn btn-default btn-xs " href=""><i class="fa fa-arrow-down"></i> Finalizar y cargar al Stock</button>
                            </div>
                            @endif
                        </form>
						<?php //print_r($codigo_producto);?>
						<?php //echo "<br><br>";?>
						<?php //print_r($cantidad);exit();?>
						<script type="text/javascript">
							/*
							var keywords = document.getElementsByName('cantidad[]');
							var msg = "";
							for (var i=0; i<keywords.length; i++)
							{
							msg+="\t"+keywords[i].value;
							}
							alert(msg);			
							*/
						</script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection