@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $("#id_prioridad").select2({
            width: 'resolve'
        });
    $("#id_servicio").select2({
            width: 'resolve'
        });
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_triaje_topico"><i class="fa fa-table fa-fw"></i> Tópico</a></li>
                <li class="active"><a href="/create_prioridad_triaje"><i class="fa fa-plus"></i> Nuevo </a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nuevo Tópico</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmTriaje" class="form-horizontal" role="form" method="post"  action="{{ route('store_triaje_topico') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Prioridad</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="id_prioridad" id="id_prioridad" required="" placeholder="Seleccionar una Prioridad" style="width: 100%">
                                        <option value="">Seleccionar</option>
                                            @foreach($prioridades as $m)
                                            <option value="{{$m->id}}">{{$m->prioridad}}</option>
                                            @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Servicio</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="id_servicio" id="id_servicio" required="" placeholder="Seleccionar un Servicio" style="width: 100%">
                                            <option value="">Seleccionar</option>
                                            @foreach($servicios as $m)
                                            <option value="{{$m->id}}">{{$m->nombre}}</option>
                                            @endforeach
                                </select>
                            </div>
                        </div>                   
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls"><br/>
                                <input type="submit" value="Guardar" class="btn btn-success">
                            </div>
                        </div> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection