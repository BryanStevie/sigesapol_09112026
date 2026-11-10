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
                <span class="pull-right">
                    <a href="/stocksbyFamarcia/{{ Auth::user()->id }}" style="color:white">
                        <i class="fa fa-arrow-left"></i> Ir al Stock
                    </a>
                </span>                           
            </div>            
        </div>
        <div class="panel-body">
            <form class="" method="post" action="{{ route('generar_ici')}}" id="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-4">
                    <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required=""> 
                    <input id="id_user" name="id_user" type="hidden" value="{{$usuario}}"> 
                    <span class="help-block">Fecha de Inicio</span>  
                </div>
                <div class="col-md-1">
                    <i class="fa fa-calendar fa-2x"></i>
                </div>
                <div class="col-md-4">
                    <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required=""> 
                    <span class="help-block">Fecha Fin</span>  
                </div>
                <div class="col-md-1">
                    <i class="fa fa-calendar fa-2x"></i>
                </div>
                <!--<div class="col-md-2">                    
                    <select class="form-control" id="nro_meses" name="nro_meses">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="12">12</option>
                    </select>
                    <span class="help-block">Mes(es)</span>  
                </div>-->
                <div class="col-md-2">                    
                    <button class="form-control btn btn-info" type="submit">Buscar</button>
                </div>
            </form>

        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
