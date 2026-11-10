@extends('frontend.layouts.master')
@section('renderjs')
<script>
    
</script>
<style>    
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <div class="panel-group" id="accordion">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                                </span>Atención de Recetas</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <table class="table">
                                @role('Dispensador_recetas')
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-plus text-primary"></span><a href="/nueva_receta/{{ Auth::user()->id }}">Nueva Receta</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/buscar_receta/{{ Auth::user()->id }}"> Buscar Receta</a>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list text-primary"></span>
                                        <a href="/recetas_atendidas/{{ Auth::user()->id }}"> Receta atendidas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-tasks text-primary"></span>
                                        <a href="/resumen_diario/{{ Auth::user()->id }}"> Resumen Diario</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-arrow-left text-primary"></span>
                                        <a href="/stocksbyFamarcia/{{ Auth::user()->id }}"> Ir al Stock</a>
                                    </td>
                                </tr>
                                @endauth
                                @role('Almacenero_recetas')
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}">Recetas atendidas</a>
                                    </td>
                                </tr>
                                @endauth
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">EDITAR DATOS BASICOS DE LA RECETA</div>                                                   
                <div class="panel-body" style="padding-top:30px; font-size: 13px"> 
                    <form  id="frmActualizarReceta" class="form-horizontal" method="post" action="{{ route('actualizar_receta_datosbasicos')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3" style="margin-top:10px">
                            <label>Nro Receta</label>
                            <input name="nro_receta" value="{{$recetas[0]->nro_receta}}" class="form-control">
                            <input name="id_receta" value="{{$recetas[0]->id}}" class="form-control" type="hidden">
                            <input name="id_farmacia" value="{{$recetas[0]->id_farmacia}}" class="form-control" type="hidden">
                            <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden">
                        </div>   
                        <div class="col-md-6" style="margin-top:10px">
                            <label>DNI - Paciente</label>
                            <input name="dni_beneficiario" value="({{$recetas[0]->dni_beneficiario}})- {{$recetas[0]->nombre_beneficiario}} {{$recetas[0]->paterno_beneficiario}} {{$recetas[0]->materno_beneficiario}}" class="form-control">
                        </div>
                        <div class="col-md-3" style="margin-top:10px">
                            <?php
                            $originalDate2 = $recetas[0]->fecha_registro;
                            $fecha = date("d-m-Y", strtotime($originalDate2));
                            ?>
                            <label>Fecha Registro</label>
                            <input class="datepicker form-control" name="fecha_registro" value="<?php echo $fecha; ?>">
                        </div>  
                        <!--<div class="col-md-12" style="margin-top:10px">
                            @foreach($productos_receta as $pr)
                            <input value="{{$pr->codigo}}">
                            <input value="{{$pr->nombre}}">
                            <input value="{{$pr->cantidad_prescrita}}" >
                            <input value="{{$pr->cantidad_dispensada}}"><br>
                            @endforeach
                        </div>-->
                        <div class="col-md-12" style="margin-top: 15px">
                            <input type="submit" value="Actualizar" class="btn btn-primary">
                        </div>
                    </form>
                </div>     
            </div>         
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection