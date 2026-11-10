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
    <form class="form-horizontal" method="post" action="{{ route('anadir_kardex_sinicial')}}"  role="form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h2>EDITAR</h2>
        <div class="form-group">
            <label class="col-sm-3 control-label">codigo_producto</label>
            <div class="col-sm-9">
                <input type="text" id="" name="codigo_producto" value="59860848" class="form-control">                                
                <input type="hidden" id="" name="id_user" value=" {{ Auth::user()->id }}" class="form-control">                                               
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">nombre_producto</label>
            <div class="col-sm-9">
                <input type="text" id="" name="nombre_producto" value="SODIO CLORURO 0.9% 20 ML" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">tipo_movimiento</label>
            <div class="col-sm-9">
                <input type="text" id="" name="tipo_movimiento" value="S. Inicial" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">numero_movimiento</label>
            <div class="col-sm-9">
                <input type="text" id="" name="numero_movimiento" value="00000001" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">origen</label>
            <div class="col-sm-9">
                <input type="text" id="" name="origen" value="LIMA" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">destino</label>
            <div class="col-sm-9">
                <input type="text" id="" name="destino" value="Farmacia del Policlínico PNP San Martín de Porres" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">estado</label>
            <div class="col-sm-9">
                <input type="text" id="" name="estado" value="1" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">cantidad</label>
            <div class="col-sm-9">
                <input type="text" id="" name="cantidad" value="" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">nombre_farmacia</label>
            <div class="col-sm-9">
                <input type="text" id="" name="nombre_farmacia" value="Farmacia del Policlínico PNP San Martín de Porres" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">fecha_movimiento</label>
            <div class="col-sm-9">
                <input type="text" id="" name="fecha_movimiento" value="" class="form-control datepicker">                                
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">id_tipo_movimiento</label>
            <div class="col-sm-9">
                <input type="text" id="" name="id_tipo_movimiento" value="1" class="form-control">                                
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
        </div>
    </form> <!-- /form -->
</div> <!-- ./container -->
@include('frontend.includes.footer')
@endsection
