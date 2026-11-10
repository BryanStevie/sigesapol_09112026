@extends('frontend.layouts.master')
@section('renderjs')
<style>
.solomayusculas{
     
       text-transform:uppercase;
    }
 </style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_laboratorios"><i class="fa fa-table fa-fw"></i> Laboratorios</a></li>
                <li><a href="/create_laboratorio"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Laboratorio</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="" class="form-horizontal" role="form" method="post"  action="{{ route('update_laboratorio') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div style="margin-bottom: 25px">
                            <input type="hidden" class="form-control" name="id"  value="{{$laboratorios->id}}"> 
                            <input type="hidden" class="form-control" name="ruc_actual"  value="{{$laboratorios->ruc}}"> 
                            <input type="text" class="form-control" name="ruc" required="" placeholder="RUC" maxlength="11" value="{{$laboratorios->ruc}}">                                        
                        </div>
                        <div style="margin-bottom: 25px" >
                            <input type="text" class="form-control solomayusculas" name="nombre" placeholder="Nombre del Laboratorio" required="" value="{{$laboratorios->nombre}}" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value=" Actualizar " class="btn btn-success">
                                <a href="/all_laboratorios" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
@endsection