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
                <li><a href="/all_productos"><i class="fa fa-table fa-fw"></i>Productos</a></li>
                <li><a href="/create_producto"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Producto</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmcrear_prod" class="form-horizontal" role="form" method="post"  action="{{ route('update_producto') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div style="margin-bottom: 25px">
                            <input value="{{$productos->id}}" name="id" type="hidden">
                            <input value="{{$productos->codigo}}" name="codigo_actual" type="hidden">
                            <input  type="text" class="form-control" name="codigo" value="{{$productos->codigo}}" placeholder="CÓDIGO" required="" maxlength="8">                                        
                        </div>
                        <div style="margin-bottom: 25px">
                            <input  type="text" class="form-control solomayusculas" name="nombre" value="{{$productos->nombre}}" placeholder="Nombre" required="" >                                        
                        </div>
                        <div style="margin-bottom: 25px" >                            
                            <select class="form-control" required="" name="id_unidad" id="id_unidad">
                                <option value="">--Seleccionar Unidad--</option>
                                @foreach($datos[0] as $u)
                                <option value="{{$u->id}}" <?php if($productos->id_unidad==$u->id) echo 'selected';?>>({{$u->abreviatura}}) {{$u->nombre}}</option>
                                @endforeach
                            </select> 
                            <span style="font-size: 10px">Unidad actual: {{$datos[2]->abreviatura}}</span>
                        </div>
                        <div style="margin-bottom: 25px" >                            
                            <select class="form-control" required="" name="id_tipo" id="id_tipo">
                                <option value="">--Seleccionar Tipo de Producto--</option>
                                @foreach($datos[1]  as $t)
                                <option value="{{$t->id}}" <?php if($productos->id_tipo==$t->id) echo 'selected';?> >{{$t->nombre}}</option>
                                @endforeach
                            </select> 
                            <span style="font-size: 10px">Tipo de producto actual: {{$datos[3]->nombre}}</span>
                        </div>
                        @if(Auth::user()->id==115)
                        <div style="margin-bottom: 25px" >    
                            Activo: <input type="checkbox" value="1" name="estado" <?php if($productos->estado == 1)echo 'checked="checked"';?>/>
                        </div>
                        @endif
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Actualizar" class="btn btn-success">
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