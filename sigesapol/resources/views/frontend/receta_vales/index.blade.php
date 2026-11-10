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
                                        <a href="/recetas_atendidas/{{ Auth::user()->id }}"> Recetas atendidas</a>
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
                        <h3 class="panel-title">Modulo de Gestión de Recetas</h3>                
                    </div> 
                    <div style="margin-left: 20px; margin-bottom: 20px">
                        Bienvenidos al Modulo de Gestión de Recetas
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection