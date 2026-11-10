@extends('frontend.layouts.master')
@section('renderjs')
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/add_producto_cpm"><i class="fa fa-table fa-fw"></i> Consumo Promedio Mensual</a></li>                
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Producto</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_producto_cpm') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id"  value="{{$consumo_promedio->id}}"> 

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Producto</label>
                            <div class="col-lg-10">
                                <input name="nombre" readonly type="text" class="form-control" value="{{$consumo_promedio->nombre}}" placeholder="Producto" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">CPM</label>
                            <div class="col-lg-10">
                                <input name="cpm" type="text" class="form-control" value="{{$consumo_promedio->cpm}}" required="" placeholder="CPM" >             
                            </div>
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/add_producto_cpm" class="btn btn-danger">Cancelar</a>
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