@extends('frontend.layouts.master')
@section('renderjs')

<style>
    body {
        background-color: #eee;
    }

    *[role="form"] {
        max-width: 530px;
        padding: 15px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 0.3em;
    }

    *[role="form"] h2 {
        margin-left: 5em;
        margin-bottom: 1em;
    }
</style>

<script>

</script>
@endsection
@section('content')
<div class="container">
    <form class="form-horizontal" method="post" action="{{ route('update_receta_personalizada')}}"  role="form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h2>EDITAR</h2>
        <div class="form-group">
            <label class="col-sm-3 control-label">Farmacia</label>
            <div class="col-sm-9">
                <input type="text" id="nombre_farmacia" value="{{$receta->nombre_farmacia}}" disabled="" class="form-control">
                <input type="hidden" name="id_farmacia" id="id_stock_farmacia" value="{{$id_farmacia}}">
                <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Codigo Producto</label>
            <div class="col-sm-9">
               <input type="text" id="" value="{{$receta->codigo_producto}}"  class="form-control" name="codigo_producto">
               <input type="hidden" id="" value="{{$receta->id}}"  class="form-control" name="id_kardex">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nombre Producto</label>
            <div class="col-sm-9">
               <input type="text" id="" value="{{$receta->nombre_producto}}"  class="form-control" name="nombre_producto">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nro Recta</label>
            <div class="col-sm-9">
                <input type="text" id="" value="{{$receta->numero_movimiento}}"  class="form-control" name="numero_movimiento">
            </div>
        </div>   
         <div class="form-group">
            <label class="col-sm-3 control-label">Cantidad</label>
            <div class="col-sm-9">
                <input type="text" id="" value="{{$receta->cantidad}}"  class="form-control" name="cantidad">
                <input type="hidden" id="" value="{{$receta->cantidad}}"  class="form-control" name="cantidad_antigua">
            </div>
        </div> 
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <button type="submit" class="btn btn-primary btn-block">Buscar</button>
            </div>
        </div>
    </form> <!-- /form -->
</div> <!-- ./container -->
@include('frontend.includes.footer')
@endsection
