@extends('frontend.layouts.master')
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('navs.home') }}</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <legend style="font-size: 15px">
						<?php 
						
						//$sistema
						/*
						$msgBienvenida = 'Bienvenido al aplicativo web "Gestión de Farmacias"';
						if(Session::get('nombreSistema') == "admision")$nombreSistema = "Gesti&oacute;n de Admisi&oacute;n";
						if(Session::get('nombreSistema') == "programacion")$nombreSistema = "Gesti&oacute;n de Programaci&oacute;n Medico";
						if(Session::get('nombreSistema') == "farmacia")$nombreSistema = "Gesti&oacute;n de Farmacias";
						*/
						
						$nombreSis = "de Registro de Receta Vale";
						if(Session::get('nombreSistema'))$nombreSis = Session::get('nombreSistema');
						$msgBienvenida = 'Bienvenido al aplicativo web '.$nombreSis.'';
						echo $msgBienvenida;
						?>
						
						
                        
						<!-- de productos farmacéuticos y dispositivos médicos para las IPRESS PNP"-->
                    </legend>                                        
                </div>              
                <!--@role('Dispensador_recetas')
                <div class="col-md-4">
                    <a href="#" class="btn btn-primary">
                        <i class="fa fa-bitbucket fa-3x"> Stock</i>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#" class="btn btn-success">
                        <i class="fa fa-file-text-o fa-3x"> Recetas</i>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#" class="btn btn-warning">
                        <i class="fa fa-line-chart fa-3x"> Gestión</i>
                    </a>
                </div>
                @endauth-->
                
            </div>
        </div><!-- panel -->
    </div><!-- col-md-10 -->
</div><!-- row -->
@endsection
@section('after-scripts-end')
<script>
    //Being injected from FrontendController
    console.log(test);
</script>
@stop