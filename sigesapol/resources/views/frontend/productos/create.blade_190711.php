@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $cmbUni = $("#frmcrear_prod").find("#id_unidad");
    $cmbUni.select2();
    $cmbTip = $("#frmcrear_prod").find("#id_tipo");
    $cmbTip.select2();
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_productos"><i class="fa fa-table fa-fw"></i> Productos</a></li>
                <li class="active"><a href="/create_producto"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Producto</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmcrear_prod" class="form-horizontal" role="form" method="post"  action="{{ route('store_producto') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div style="margin-bottom: 25px">
                            <input  type="text" class="form-control" name="codigo" value="" placeholder="CÓDIGO" required="" maxlength="8">                                        
                        </div>
                        <div style="margin-bottom: 25px">
                            <input  type="text" class="form-control solomayusculas" name="nombre" value="" placeholder="Nombre" required="" >                                        
                        </div>
                        <div style="margin-bottom: 25px" >                            
                            <select class="form-control" required="" name="id_unidad" id="id_unidad">
                                <option value="">--Seleccionar Unidad--</option>
                                @foreach($unidades as $u)
                                <option value="{{$u->id}}">({{$u->abreviatura}}) {{$u->nombre}}</option>
                                @endforeach
                            </select> 
                        </div>
                        <div style="margin-bottom: 25px" >                            
                            <select class="form-control" required="" name="id_tipo" id="id_tipo">
                                <option value="">--Seleccionar Tipo de Producto--</option>
                                @foreach($tipos as $t)
                                <option value="{{$t->id}}">{{$t->nombre}}</option>
                                @endforeach
                            </select> 
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_productos" class="btn btn-danger">Cancelar</a>
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