@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $producto = $("#frmEditarProducto").find("#codigo_producto");
    $producto.select2();
    
    $("#btnReg").on("click", function () {                          
        if ($('#frmEditarProducto').find("#cantidad").val() <= 0) {
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
     /*$(document).ready(function () {
        $producto = $("#frmEditarProducto").find("#codigo_producto");
        $producto.select2();
        $producto.change(function () {
            $("#frmEditarProducto").find("#cantidad").val("");
        });
      });*/
</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Requerimiento: {{$requerimiento->numero}}</h3>
                    <div class="pull-right">                        
                        <a class="btb btn-info btn-xs" href="/ver_productos_del_pedido/{{$requerimiento->id}}/{{$tipo_producto_egestion}}/{{ Auth::user()->id_farmacia }}">     
                            <i class="fa fa-arrow-left"></i> Ver requerimiento
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="post" id="frmEditarProducto" action="{{ route('update_producto_del_pedido')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12" style="margin-top:10px">
                            <label>Nuevo Producto</label>
                            <select class="form-control" name="codigo_producto" id="codigo_producto">
                                <option value="{{$producto->codigo}}" selected="">{{$producto->codigo}} - {{$producto->nombre}}</option>
                                <!--@foreach($productos as $pr)
                                <option value="{{$pr->codigo}}">{{$pr->codigo}} - {{$pr->nombre}} - {{$pr->unidad}}</option>
                                @endforeach-->
                            </select>   
                            <input name="id_req_producto" value="{{$req_producto->id}}" type="hidden">
                            <input name="id_requerimiento" value="{{$requerimiento->id}}" type="hidden">
                            <input name="tipo_producto_egestion" value="{{$tipo_producto_egestion}}" type="hidden">
                            <input name="id_farmacia" value="{{ Auth::user()->id_farmacia }}" type="hidden">
                        </div> 
                        <div class="col-md-12" style="margin-top:10px">
                            <label>Cantidad</label>
                            <input onKeyPress="return soloNumeros(event)" value="{{$req_producto->cantidad}}" class="form-control" id="cantidad" name="cantidad" type="text" maxlength="9" >    
                        </div>
                        <div class="col-md-12" style="margin-top:10px">
                            <input class="btn btn-success btn-xs form-control" value="Actualizar" type="submit" id="btnReg">   
                        </div>
                    </form>
                </div>
            </div>
        </div>       
    </div>
</div>
@include('frontend.includes.footer')
@endsection
