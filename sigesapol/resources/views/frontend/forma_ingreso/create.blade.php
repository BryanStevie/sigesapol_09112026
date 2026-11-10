@extends('frontend.layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_forma_ingreso"><i class="fa fa-table fa-fw"></i> Forma de Ingreso</a></li>
                <li class="active"><a href="/create_forma_ingreso"><i class="fa fa-plus"></i> Nuevo </a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nueva Forma de Ingreso</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmFormaIngreso" class="form-horizontal" role="form" method="post"  action="{{ route('store_forma_ingreso') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div style="margin-bottom: 25px">
                            <input name="codigo" type="text" class="form-control" required="" placeholder="Código" onkeypress="" autocomplete="OFF" maxlength="2">                                
                        </div> 

                        <div style="margin-bottom: 25px">
                            <input name="descripcion" type="text" class="form-control" required="" placeholder="Descripción" onkeypress="return solo_letra(event)" autocomplete="OFF" maxlength="15">                                
                        </div>                       
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
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