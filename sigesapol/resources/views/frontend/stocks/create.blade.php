@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $cmbPro = $("#id_producto");
    $cmbPro.select2();
</script>
@endsection
@section('content')
<div class="container">

    <div class="row">
        <div class="panel panel-primary">            
            <div class="panel-heading" style="margin-bottom: 30px">
                <h3 class="panel-title">Agregar Stock</h3>                
            </div>                        

            {!! Form::open(['url' => '/stocks', 'class' => 'form-horizontal']) !!}

            <div class="form-group {{ $errors->has('id_producto') ? 'has-error' : ''}}">
                <label class="col-sm-3 control-label">Producto</label>
                <div class="col-sm-6">            
                    <select id="id_producto" name="id_producto" class="form-control">
                        <option value="">Buscar por código o nombre</option>
                        @foreach($productos as $p)
                        <option value="{{$p->id}}">{{$p->codigo}} - {{$p->nombre}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('id_producto', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('monto_inicial') ? 'has-error' : ''}}">
                <label class="col-sm-3 control-label">Cantidad</label>
                <div class="col-sm-6">
                    {!! Form::text('monto_inicial', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('monto_inicial', '<p class="help-block">:message</p>') !!}
                </div>
            </div> 
            <div class="form-group">
                <label class="col-sm-3 control-label">Fecha de Vencimiento</label>
                <div class="col-sm-6">
                    <input class="form-control datepicker" name="fecha_vencimiento" id="fecha_vencimiento" required="">
                </div>
            </div>
           
           
            <input type="hidden" name="estado" value="1">
            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-3">            
                    <input type="submit" value="Agregar" class="btn btn-primary form-control">
                </div>
                <div class="col-sm-offset-0 col-sm-3">            
                    <a href="/stocksbyuser/{{ Auth::user()->id }}" class="form-control btn btn-danger">Cancelar</a>
                </div>
            </div>
            {!! Form::close() !!}

            @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif


        </div>
    </div>

</div>
@include('frontend.includes.footer')
@endsection