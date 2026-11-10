@extends('frontend.layouts.master')
@section('renderjs')
<script></script>
<script src='../../funciones/function.js'></script> 
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Consultorios en el Establecimiento</div>                    
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('update_consultorio_can') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                        <input type="hidden" name="id_can" value="<?php echo $id_can ?>">
                        <input type="hidden" name="id_establecimiento" value="<?php echo $id_establecimiento ?>">
                        <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo?>">
                        <input type="hidden" name="redirigir" id="redirigir" value="<?php echo $redirigir?>">
                        <input type="hidden" name="id_monitoreo" id="id_monitoreo" value="<?php echo $id_monitoreo?>">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Medicina Interna</label>
                            <div class="col-lg-2">
                                <input name="medicina_interna" id="medicina_interna" value="{{$medicina_interna}}" type="number" class="form-control" required="" placeholder="Medicina Interna:" autocomplete="OFF">             
                            </div>
                            <label class="col-lg-2 control-label">Odontología</label>
                            <div class="col-lg-2">
                                <input name="odontologia" id="odontologia" value="{{$odontologia}}" type="number" class="form-control" required="" placeholder="Odontología:" autocomplete="OFF">             
                            </div>
                            <label class="col-lg-2 control-label">Obstetricia</label>
                            <div class="col-lg-2">
                                <input name="obstetricia" id="obstetricia" value="{{$obstetricia}}" type="number" class="form-control" required="" placeholder="Obstetricia:" autocomplete="OFF">             
                            </div>
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                                <a href="/all_cans" class="btn btn-danger">Cancelar <i class="glyphicon glyphicon-remove"></i></a>
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
