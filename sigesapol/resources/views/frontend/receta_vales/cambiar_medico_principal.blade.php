@extends('frontend.layouts.master')
@section('renderjs')
<style>


</style>
<script>
 $consul = $("#frmCambiaMedico").find("#id_medico");
    $consul.select2();
</script>
@endsection
@section('content')
<div class="container">
    <div class="row col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title">Cambiar Profesionales Médicos</h2>
            </div>
            <div class="panel-body">
                <form method="post" action="{{ route('update_medico_principal_receta_vale')}}" id="frmCambiaMedico">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-8">
                        <label>Profesional Actual</label>
                        <input type="text" value="{{$datos_profesional->nombre_medico}} {{$datos_profesional->paterno}} {{$datos_profesional->materno}}" class="form-control">
                        <input type="hidden" value="{{$id_receta_vale}}" name="id_receta_vale"> 
                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user"> 
                        <input type="hidden" value="{{ Auth::user()->id_farmacia }}" name="id_farmacia"> 
                    </div>
                    <div class="col-md-4">
                        <label>Especialidad</label>
                        <input type="text" value="{{$datos_profesional->nombre}}" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label>Seleccionar Nuevo Profesional</label>
                        <select class="form-control" name="id_medico" id="id_medico">
                            <option value="" selected="">Seleccionar</option>
                            @foreach($profesionales as $pro)
                            <option value="{{$pro->id}}">{{$pro->nombre}} {{$pro->paterno}} {{$pro->materno}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12" style="margin-top:10px">
                        <input type="submit" value="Actualizar"  class="form-control btn btn-primary"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection