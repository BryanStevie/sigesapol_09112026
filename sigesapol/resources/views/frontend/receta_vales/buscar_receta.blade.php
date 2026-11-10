@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $cmbUni = $("#frmcrear_prod").find("#id_unidad");
    $cmbUni.select2();
</script>
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
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-plus text-primary"></span>
                                        <a href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia }}"> Nueva Receta</a>
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
                                        <span class="glyphicon glyphicon-list text-primary"></span>
                                        <a href="/recetas_invalidadas/{{ Auth::user()->id_farmacia }}/{{ Auth::user()->id }}"> Recetas Invalidadas</a>
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
            <div class="row">
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">Buscar receta <i class="fa fa-search"></i></h3>                
                    </div> 
                    
                    <div class="" style="margin-left: 20px; margin-bottom: 20px">
                        <fieldset>
                            <!-- Text input-->
                            <div class="form-group">
                                <form class="form-horizontal" method="post" action="{{ route('busqueda_dni')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <label class="col-md-1 control-label" for="textinput">DNI</label>  
                                    <div class="col-md-2">
                                        <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder="" class="form-control input-md" maxlength="8" required="">
                                        <input type="hidden" name="id_user" id="id_user" value="{{$usuario}}">
                                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info" type="submit">
                                            Buscar
                                        </button>
                                    </div>
                                </form>
                                <form class="form-horizontal" method="post" action="{{ route('busqueda_nro_receta')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <label class="col-md-2 control-label" for="textinput">Nro. Receta</label>  
                                    <div class="col-md-2">
                                        <input id="nro_receta" name="nro_receta" type="text" placeholder="" class="form-control input-md" required="">
                                        <input type="hidden" name="id_user" id="id_user" value="{{$usuario}}">
                                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-warning" type="submit">
                                            Buscar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection