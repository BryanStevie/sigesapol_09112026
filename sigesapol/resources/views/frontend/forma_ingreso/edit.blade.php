@extends('frontend.layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_forma_ingreso"><i class="fa fa-table fa-fw"></i> Forma de Ingreso</a></li>
                <li class="active"><a href="/create_forma_ingreso"><i class="fa fa-plus"></i> Nuevo</a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Editar Forma de Ingreso - Emergencia</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmFormaIngreso" class="form-horizontal" role="form" method="post"  action="{{ route('update_forma_ingreso') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						
						
						<div style="margin-bottom: 25px">
                            <input value="{{$formaIngreso->id}}" type="hidden" name="id">
                            <input name="descripcion" type="text" class="form-control" required="" value="{{$formaIngreso->descripcion}}" placeholder="Descripción" onkeypress="return solo_letra(event)" maxlength="15">
                        </div>
						
						<!--
                        <div style="margin-bottom: 25px">
                            <select id="nombre" name="nombre" class="form-control" tabindex="11"  style="margin-bottom: 10px" disabled="true" required="">
                                <option value=""  selected style="color: #888888">Selecionar nombre de la Farmacia</option>      
                            </select>
                        </div>
						-->
						
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_forma_ingreso" class="btn btn-danger">Cancelar</a>
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