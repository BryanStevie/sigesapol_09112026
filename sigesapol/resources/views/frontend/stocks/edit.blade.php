@extends('frontend.layouts.master')
@section('content')
<div class="container">

    <h1>Editar Stock</h1>
    <hr/>

    {!! Form::model($stock, [
    'method' => 'PATCH',
    'url' => ['/stocks', $stock->id],
    'class' => 'form-horizontal'
    ]) !!}

    <?php
    $cantidad_inicial = $stock->monto_inicial;
    $cantidad_actual = $stock->monto_actual;
    ?>

    @if($cantidad_inicial==$cantidad_actual)
    <div class="form-group {{ $errors->has('monto_inicial') ? 'has-error' : ''}}">        
        <label class="col-sm-3 control-label">Cantidad Inicial</label>
        <div class="col-sm-6">                    
            <input name="monto_inicial" type="text" class="form-control" value="{{$stock->monto_inicial}}"> 
            {!! $errors->first('monto_inicial', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('monto_actual') ? 'has-error' : ''}}">        
        <label class="col-sm-3 control-label">Cantidad Actual</label>
        <div class="col-sm-6">
            <input name="monto_actual" type="text" class="form-control" value="{{$stock->monto_actual}}" disabled=""> 
            {!! $errors->first('monto_actual', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('monto_consumido') ? 'has-error' : ''}}">
        {!! Form::label('monto_consumido', trans('stocks.monto_consumido'), ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            <input name="monto_consumido" type="text" class="form-control" value="{{$stock->monto_consumido}}" disabled="">                   
            {!! $errors->first('monto_consumido', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('estado') ? 'has-error' : ''}}">
        {!! Form::label('estado', trans('stocks.estado'), ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">                    
            <select name="estado" class="form-control">
                <option value="1">Activar</option>
                <option value="2">Desactivar</option>
            </select>
            {!! $errors->first('estado', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Actualizar', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    @else
    <div class="form-group {{ $errors->has('monto_inicial') ? 'has-error' : ''}}">        
        <label class="col-sm-3 control-label">Cantidad Inicial</label>
        <div class="col-sm-6">                    
            <input name="monto_inicial" type="text" class="form-control" value="{{$stock->monto_inicial}}" disabled=""> 
            {!! $errors->first('monto_inicial', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('monto_actual') ? 'has-error' : ''}}">        
        <label class="col-sm-3 control-label">Cantidad Actual</label>
        <div class="col-sm-6">
            <input name="monto_actual" type="text" class="form-control" value="{{$stock->monto_actual}}" disabled=""> 
            {!! $errors->first('monto_actual', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('monto_consumido') ? 'has-error' : ''}}">
        {!! Form::label('monto_consumido', trans('stocks.monto_consumido'), ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            <input name="monto_consumido" type="text" class="form-control" value="{{$stock->monto_consumido}}" disabled="">                   
            {!! $errors->first('monto_consumido', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <p>¡No se puede modificar, por motivo que ya se entregaron medicamentos!</p>
            <a href="/stocksbyuser/{{ Auth::user()->id }}" class="btn btn-primary">Retrocer</a>
        </div>
    </div>
    @endif
    <!--<div class="form-group {{ $errors->has('fecha_registro') ? 'has-error' : ''}}">
        {!! Form::label('fecha_registro', trans('stocks.fecha_registro'), ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">                    
            <input class="form-control datepicker" id="fecha_registro" name="fecha_registro" value="">
            {!! $errors->first('fecha_registro', '<p class="help-block">:message</p>') !!}
        </div>
    </div>-->

    {!! Form::close() !!}

    @if ($errors->any())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif

</div>
@include('frontend.includes.footer')
@endsection