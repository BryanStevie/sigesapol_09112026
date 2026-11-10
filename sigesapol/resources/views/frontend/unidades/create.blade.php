@extends('frontend.layouts.master')
@section('renderjs')

@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_unidades"><i class="fa fa-table fa-fw"></i> Unidades</a></li>
                <li class="active"><a href="/create_unidad"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Unidad</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="" class="form-horizontal" role="form" method="post"  action="{{ route('store_unidad') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div style="margin-bottom: 25px">
                            <input  type="text" class="form-control" name="abreviatura" value="" placeholder="Abrevitura" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">                                        
                        </div>
                        <div style="margin-bottom: 25px" >
                            <input type="text" class="form-control solomayusculas" name="nombre" placeholder="Descripción" required="" >
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_unidades" class="btn btn-danger">Cancelar</a>
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