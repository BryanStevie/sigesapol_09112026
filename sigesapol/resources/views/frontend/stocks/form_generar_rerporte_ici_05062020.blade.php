@extends('frontend.layouts.master')
@section('renderjs')
<style>

</style>

<script>

</script>
@endsection
@section('content')
<div class="container col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Reporte ICI
                <!--
<span class="pull-right">
    <a href="/stocksbyFamarcia/{{ Auth::user()->id }}" style="color:white">
        <i class="fa fa-arrow-left"></i> Ir al Stock
    </a>
</span>
                -->
            </div>            
        </div>
        <div class="panel-body">
            <form class="" method="post" action="{{ route('generar_ici')}}" id="frmIci">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input id="id_user" name="id_user" type="hidden" value="{{$usuario}}"> 
                <!--
<div class="col-md-4">
    <input id="fecha_inicio" name="fecha_inicio" type="text"  class="form-control input-md datepicker" required=""> 
                <?php
                //$originalDate = $f_inicio_operaciones;
                //$fecha_operaciones = date("d-m-Y", strtotime($originalDate));
                ?>
    <input id="fecha_inicio_operaciones" name="fecha_inicio_operaciones" type="hidden" value="<?php //echo $fecha_operaciones;   ?>"> 
    <span class="help-block">Fecha de Inicio</span>  
</div>
                
<div class="col-md-1">
    <i class="fa fa-calendar fa-2x"></i>
</div>
                -->
                <div class="col-sm-4">
                    <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_actual}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"> 
                    <span class="help-block">Fecha Hasta</span>  
                </div>
                <div class="col-sm-1">
                    <i class="fa fa-calendar fa-2x"></i>
                </div>
                <div class="col-sm-3">
                    @if($count_f_inicio_operaciones>0)
                    <button class="form-control btn btn-info" type="submit">Buscar</button>
                    @else
                    <button class="form-control btn btn-info" type="submit" disabled="">Buscar</button>
                    @endif
                </div>
                <div class="col-md-12 pull-left" style="margin-top: 10px; font-size: 10px;clear:both">
                    <span>
                        <?php
                        //echo "Nota Importante: Inicio del uso del aplicativo web el 21-04-2018 para la ". $nombre_farmacia;
                        if ($count_f_inicio_operaciones == 0) {
                            echo "Nota Importante: La " . $nombre_farmacia . " aun no usan el aplicativo web, por el cual no puede generar un Reporte ICI.";
                        } else {
                            $originalDate = $f_inicio_operaciones;
                            $fecha_operaciones = date("d-m-Y", strtotime($originalDate));
                            ?>
                            Nota Importante: El Inicio del uso del aplicativo web es el 
                            <?php
                            echo $fecha_operaciones . " para la " . $nombre_farmacia;
                        }
                        ?> 
                    </span>
                </div>
            </form>

        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
