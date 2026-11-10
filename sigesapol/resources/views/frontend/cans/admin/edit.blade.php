@extends('frontend.layouts.master')
@section('renderjs')
<script></script>
<script src='../../funciones/function.js'></script> 
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_cans"><i class="fa fa-table fa-fw"></i> CAN</a></li>
                <li class="active"><a href="/create_can"><i class="fa fa-plus"></i> Nuevo </a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Actualizar CAN</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_can') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                        <input type="hidden" name="id_can" value="<?php echo $can->id ?>">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">CAN</label>
                            <div class="col-lg-10">
                                <input name="nombre_can" id="nombre_can" value="{{$can->nombre_can}}" onkeypress="return noEspaciosInicioLetrasNumerosOtros(event,'nombre_can');" type="text" class="form-control" required="" placeholder="Descripción del CAN" autocomplete="OFF">             
                            </div>
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
