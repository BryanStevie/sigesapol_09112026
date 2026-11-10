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
                Demanda No Satisfecha- {{$nombre_farmacia}}              
                <span class="pull-right">
                    <a href="/stocksbyFamarcias/{{$id_farmacia}}" style="color:white">
                        <i class="fa fa-arrow-left"></i> Ir al Stock
                    </a>
                </span>                           
            </div>            
        </div>
        <div class="panel-body">
            <form class="" method="post" action="{{ route('form_demanda_nosatisfecha')}}" id="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-6">
                    <label class="help-block">Fecha de Inicio <i class="fa fa-calendar"></i></label>  
                    <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required=""> 
                    <input id="id_user" name="id_farmacia" type="hidden" value="{{$id_farmacia}}"> 
                    
                </div>
                <div class="col-md-6">
                    <label class="help-block">Fecha Fin <i class="fa fa-calendar"></i></label>  
                    <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="">                     
                </div>
                <div class="col-md-6 pull-right" style="margin-top:10px">                    
                    <button class="form-control btn btn-info" type="submit">Buscar</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
