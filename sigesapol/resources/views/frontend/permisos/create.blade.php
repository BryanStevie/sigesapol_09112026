@extends('frontend.layouts.master')
@section('renderjs')

@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_usuario"><i class="fa fa-table fa-fw"></i> Usuario</a></li>
                <li class="active"><a href="/create_usuario"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Usuario</div>
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="" class="form-horizontal" role="form" method="post"  action="{{ route('store_usuario') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Documento de Identidad</label>
							<div class="col-lg-10">
								<input type="text" value="" name="dni" required="" class="form-control" placeholder="DNI">
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('name', trans('validation.attributes.name'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<input type="text" value="" name="name" required="" class="form-control" placeholder="Nombre Completo">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Apellido Paterno</label>
							<div class="col-lg-10">
								<input type="text" value="" name="paterno" required="" class="form-control" placeholder="Apellido Paterno">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Apellido Materno</label>
							<div class="col-lg-10">
								<input type="text" value="" name="materno" required="" class="form-control" placeholder="Apellido Materno">
							</div>
						</div>        
						<div class="form-group">
							{!! Form::label('email', trans('validation.attributes.email'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<input type="email" value="" name="email" required="" class="form-control" placeholder="E-mail">
							</div>
						</div>
									   
						<div class="form-group">
							{!! Form::label('password', trans('validation.attributes.password'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<input type="password" value="" name="password" required="" class="form-control" placeholder="">
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('password_confirmation', trans('validation.attributes.password_confirmation'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<input type="password" value="" name="password_confirmation" required="" class="form-control" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Farmacia o Dirección</label>
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
								<input type="text" value="" name="celular" required="" class="form-control" placeholder="Celular">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">{{ trans('validation.attributes.active') }}</label>
							<div class="col-lg-1">
								<input type="checkbox" value="1" name="status" checked="checked" />
							</div>
						</div>
				
						<div class="form-group">
							<label class="col-lg-2 control-label">{{ trans('validation.attributes.confirmed') }}</label>
							<div class="col-lg-1">
								<input type="checkbox" value="1" name="confirmed" checked="checked" />
							</div>
						</div>

                        <div style="margin-top:10px" class="form-group">
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
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