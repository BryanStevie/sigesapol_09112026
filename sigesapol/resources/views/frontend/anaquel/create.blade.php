@extends('frontend.layouts.master')
@section('renderjs')
<script>
        
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/all_anaquel"><i class="fa fa-table fa-fw"></i> Anaquel</a></li>
                <li class="active"><a href="/create_anaquel"><i class="fa fa-plus"></i> Nuevo </a></li>
            </ul>
        </div>
        <div class="col-md-9 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Crear Nuevo Anaquel</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_anaquel') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_farmacia" value="<?php echo $id_farmacia ?>">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Anaquel</label>
                            <div class="col-lg-10">
                                <input name="nombre" type="text" class="form-control" required="" placeholder="Nombre del Anaquel" autocomplete="OFF">             
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