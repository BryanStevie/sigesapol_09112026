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
                <li><a href="/all_unidades"><i class="fa fa-table fa-fw"></i> Unidades</a></li>
                <li><a href="/create_unidad"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Usuario</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="" class="form-horizontal" role="form" method="post"  action="{{ route('update_usuario') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" class="form-control" name="id"  value="{{$usuarios->id}}"> 
						
                        <div class="form-group">
							<label class="col-lg-2 control-label">Documento de Identidad</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->dni?>" name="dni" required="" class="form-control" placeholder="DNI">
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('name', trans('validation.attributes.name'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->name?>" name="name" required="" class="form-control" placeholder="Nombre Completo">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Apellido Paterno</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->paterno?>" name="paterno" required="" class="form-control" placeholder="Apellido Paterno">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Apellido Materno</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->materno?>" name="materno" required="" class="form-control" placeholder="Apellido Materno">
							</div>
						</div>        
						<div class="form-group">
							{!! Form::label('email', trans('validation.attributes.email'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<input type="email" value="<?php echo $usuarios->email?>" name="email" required="" class="form-control" placeholder="E-mail">
							</div>
						</div>
									   
						<div class="form-group">
							<label class="col-lg-2 control-label">Farmacia o Direcci&oacute;n</label>
							<div class="col-lg-10">
								<select class="form-control" name="id_farmacia" id="id_farmacia">
									@foreach($farmacias as $f)
									<option value="{{$f->id}}">{{$f->nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Celular</label>
							<div class="col-lg-10">
								<input type="text" value="<?php echo $usuarios->celular?>" name="celular" required="" class="form-control" placeholder="Celular">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">{{ trans('validation.attributes.active') }}</label>
							<div class="col-lg-1">
								<input type="checkbox" value="1" name="status" <?php if($usuarios->status == 1)echo 'checked="checked"';?>  />
							</div>
						</div>
				
						<div class="form-group">
							<label class="col-lg-2 control-label">{{ trans('validation.attributes.confirmed') }}</label>
							<div class="col-lg-1">
								<input type="checkbox" value="1" name="confirmed" <?php if($usuarios->confirmed == 1)echo 'checked="checked"';?> />
							</div>
						</div>
						
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value=" Actualizar " class="btn btn-success">
                                <a href="/all_usuario" class="btn btn-danger">Cancelar</a>
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
