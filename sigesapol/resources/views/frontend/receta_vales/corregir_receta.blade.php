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
    <form class="form-horizontal" method="post" action="{{ route('busqueda_receta_personalizada')}}"  role="form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h2>EDITAR</h2>
        <div class="form-group">
            <label class="col-sm-3 control-label">Farmacia</label>
            <div class="col-sm-9">
                <input type="text" name="nombre_farmacia" value="{{$nombre_farmacia}}"  class="form-control">
                <input type="hidden" name="id_farmacia" id="" value="{{$id_farmacia}}">
                <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Codigo Producto</label>
            <div class="col-sm-9">
               <input type="text" id="" value=""  class="form-control" name="codigo_producto">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nro Recta</label>
            <div class="col-sm-9">
                <input type="text" id="" value=""  class="form-control" name="nro_receta">
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
