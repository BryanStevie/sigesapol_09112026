@extends('frontend.layouts.master')
@section('renderjs')

<style>
@media print{

      .oculto-impresion, .oculto-impresion *{

        display: none !important;

      }
      .invoice {
            position: relative;
            background: #fff;
            border: 1px solid #f4f4f4;
            padding: 20px;
            margin: 10px 25px;
        }
        
        .invoice-title{margin-top:0}
        
        .invoice-col{float:left;width:33.3333333%}

        .address {
            margin-bottom: 20px;
            font-style: normal;
            line-height: 1.42857143;
        }

    }
</style>
<script>
    
    $("#btnReg").on("click", function () {                          
        if ($('#frmAgregarProducto').find("#codigo_producto").val() == "") {
            alert("Falta Seleccionar el Producto");
            return false;
        }
        if ($('#frmAgregarProducto').find("#cantidad").val() <= 0) {
            alert("La cantidad tiene que ser mayor a cero y entero positivo");
            return false;
        }                                 
    });

    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }
    
    
    $(document).ready(function () {
        $producto = $("#frmAgregarProducto").find("#codigo_producto");
        $("#codigo_producto").select2({
            width: 'resolve'
        });
        $producto.change(function () {
            $("#frmAgregarProducto").find("#cantidad").val("");
        });
      });
    
</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-10">
            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$farmacia->nombre}}</h3>
                    <div class="pull-right oculto-impresion">                        
                        <a class="btb btn-default btn-xs" href="/ver_requerimiento/{{ Auth::user()->id }}">     
                            <i class="fa fa-arrow-left"></i> Ver requerimientos
                        </a>
                    </div>
                </div>
                <div class="panel-body invoice">
                    <div class="col-sm-12 ">
                        <label class="text-left">Destino: {{$requerimiento->nom_destino}}</label>
                    </div>
                    <div class="col-md-3 invoice-col" style="margin-top:10px">
                        <label>Número:<br>{{$requerimiento->contador}}-{{$requerimiento->anio_reque}}</label>
                        <input value="{{$requerimiento->numero}}" class="form-control" name="numeo"type="hidden">
                    </div>                        
                    <div class="col-md-5 invoice-col" style="margin-top:10px">
                        <label>Documento:<br>{{$requerimiento->documento}}</label>
                    </div>  
                    <div class="col-md-2 invoice-col" style="margin-top:10px">
                        <label>Creado: <br>
                            <?php
                            $originalDate2 = $requerimiento->created_at;
                            $fecha_creacion = date("d-m-Y", strtotime($originalDate2));
                            echo $fecha_creacion
                            ?> 
                        </label>
                    </div>                 
                        
                        
                            
                           
                        
          
 @if($requerimiento->estado!=2)
                        <div class="col-md-2" style="margin-top:10px">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#squarespaceModal"><i class="fa fa-edit"></i> Editar Requerimiento</button>
                        </div>
                    @endif
                    @if($requerimiento->estado==2)
                        <div class="col-md-2" style="margin-top:10px">
                            <label>Enviado:<br>
                                <?php
                                $originalDate3 = $requerimiento->fecha;
                                $fecha_envio = date("d-m-Y", strtotime($originalDate3));
                                echo $fecha_envio
                                ?> 
                            </label>
                        </div>                         
                    @endif
                    @if($requerimiento->estado!=2 or Auth::user()->flagresponsablefarmacia==1)
                    <div class="col-md-12 oculto-impresion" style="margin-top:15px">
                        <legend style="text-transform: uppercase; font-size: 15px">
                           Agregar {{$tipo_producto->nombre}}
                        </legend>
                    </div>
                    
                    <form method="post" action="{{ route('add_producto_al_pedido')}}" id="frmAgregarProducto">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">                        
                        <div class="col-md-8 oculto-impresion " style="margin-top:0px">
                            <label>Producto Farmacéutico o Dispositivo Médico</label>
                            <select id="codigo_producto" name="codigo_producto" required="" class="form-control">
                                <option value="">-Seleccionar-</option>
                                @foreach($productos as $p)
                                <option value="{{$p->codigo}}">{{$p->codigo}} - {{$p->nombre}} - {{$p->unidad}}</option>
                                @endforeach
                            </select>
                            <input value="{{$requerimiento->id}}" name="id_requerimiento"  type="hidden">
                            <input value="{{ Auth::user()->id_farmacia }}" name="id_farmacia"  type="hidden"> {{$requerimiento->fecha}}
                            <input value="{{ $requerimiento->tipo_producto_egestion }}" name="tipo_producto_egestion"  type="hidden">
                            <input value="{{ $p->id }}" name="id_producto"  type="hidden">
                        </div>
                        <div class="col-md-2 oculto-impresion" style="margin-top:0px">
                            <label>cantidad</label>
                            <input onKeyPress="return soloNumeros(event)" name="cantidad" id="cantidad" type="text" required="" class="form-control" maxlength="9" autocomplete="off">
                        </div>
                        <div class="col-md-2 oculto-impresion" style="margin-top:0px">
                            <label>Agregar</label>
                            <input type="submit" class="btn btn-primary form-control" value="Agregar" id="btnReg">                            
                        </div>
                    </form>
                    @endif
                    <form method="post" action="{{ route('enviar_pedido_almacen')}}" id="frmAgregarProducto">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">  
                        <input value="{{ $requerimiento->id }}" name="id_requerimiento" type="hidden">
                        <input value="{{ Auth::user()->id_farmacia }}" name="id_farmacia" type="hidden">
                        <!---------------------------------------------------------------------------------------------->
                        <input value="{{ $requerimiento->tipo_producto_egestion }}" name="codtipoproducto" type="hidden">
                        <input value="{{ $establecimiento->id_egestion }}" name="cod_unidad" type="hidden">
                        <input value="{{$requerimiento->documento}}" name="documento" type="hidden">
                        <div class="col-md-12 oculto-impresion" style="margin-top:30px;">
                            <legend>
                                <samp style="text-transform: uppercase; font-size: 15px">Lista de {{$tipo_producto->nombre}}</samp>
                         
@if($requerimiento->estado!=2)
                                <button class="btn btn-success btn-xs pull-right" type="submit">
                                    <i class="fa fa-send fa-1x"></i> Finalizar Requerimiento<!--Enviar requerimiento al Almacen-->
                                </button>
                                @else
                                <button class="btn btn-success btn-xs pull-right" type="submit" disabled="">
                                    <i class="fa fa-send fa-1x"></i> Requerimiento Finalizado<!--Requerimiento enviado al almacen-->
                                </button>
                                @endif

                            </legend>
                        </div> 
                        @if(count($productosbyrequerimientos)>0)
                        <table class="table" style="font-size:12px">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    @if($requerimiento->estado!=2)
                                    <th class="oculto-impresion">Editar</th>
                                    <th class="oculto-impresion">Eliminar</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 0; $total=0;?>
                                @foreach($productosbyrequerimientos as $producto)
                                <tr>
                                    <td><?php $n = $n + 1;
                                echo $n;
                                ?></td>
                                    <td>
                                        {{$producto->codigo_producto}} - {{$producto->nombre}} - {{$producto->abreviatura}}
                                        <input value="{{$producto->codigo_producto}}" type="hidden" name="codigo_producto[]">
                                    </td>
                                    <td align="right">
                                        {{$producto->cantidad}}
                                        <input value="{{$producto->cantidad}}" type="hidden" name="cantidad[]">
                                    </td>
                                    @if($requerimiento->estado!=2 or Auth::user()->flagresponsablefarmacia==1)
                                    <td class="oculto-impresion">
                                        <a class="btn btn-primary btn-xs" href="/editar_producto_del_pedido/{{$producto->id}}/{{$requerimiento->id}}/{{$requerimiento->tipo_producto_egestion}}/{{ Auth::user()->id_farmacia }}">Editar</a>
                                    </td>
                                    <td class="oculto-impresion">
                                        <a class="btn btn-danger btn-xs" href="/eliminar_producto_del_pedido/{{$producto->id}}/{{$requerimiento->id}}/{{$requerimiento->tipo_producto_egestion}}/{{ Auth::user()->id_farmacia }}">Eliminar</a>
                                    </td>
                                    @endif
                                </tr>                            
                                <?php $total=$producto->cantidad+$total;?>
                                @endforeach
                                <tr><td colspan="2" align="right"><strong>TOTAL:</strong></td><td  align="right"><?php echo $total;?></td><td colspan="2"></td></tr>
                            </tbody>
                        </table>
                        @else                    
                        <div class="col-md-12 alert alert-danger"  style="margin-top:5px;">
                            <p>
                                <i class="fa fa-warning"></i> Falta agregar productos.
                            </p>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
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
                    <input type="hidden"  value="{{$requerimiento->id}}" id="id_requerimiento" name="id_requerimiento">
                    <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{ Auth::user()->id_farmacia}}">
                    <div class="form-group" style="margin-bottom: 8px">
                        <label>Lugar de Destino</label>
                        <select name="id_destino" id="id_destino" class="form-control" required="">
                            <option value="">-Seleccione-</option>
                            <?php
                            $iddestino = $requerimiento->id_destino;
                            foreach($destino as $d){
                                $iddes = $d->id_farmacia;
                            ?>
                            <option value="<?php echo $d->id_farmacia?>" <?php if($iddestino == $iddes) echo " selected"?>><?php echo $d->nombre;?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 8px">
                        <label>Tipo de Producto</label>
                        <select id="tipo_producto_egestion" name="tipo_producto_egestion" class="form-control" required="">
                            <option value="">-Seleccionar-</option>
                            <?php 
                                $idtipreque = $requerimiento->tipo_producto_egestion;
                                foreach($tipo_productos as $tp){
                                    $idtip = $tp->id_egestion;
                                ?>
                                <option value="<?php echo $tp->id_egestion;?>" <?php if($idtipreque == $idtip) echo " selected"?>><?php echo $tp->nombre;?></option>
                            <?php }?>
                        </select>                        
                    </div>   
                    <div class="form-group" style="margin-bottom: 8px">
                        <label>Documento</label>
                        <input type="text" class="form-control" id="documento" name="documento" value="{{$requerimiento->documento}}" required="">                                                
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
