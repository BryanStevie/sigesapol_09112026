@extends('frontend.layouts.master')
@section('renderjs')
<script>

    $(document).ready(function () {

        $('#fecha_inicio').datepicker().on('hide', function (ev) {
            var fechaFinIni = $('#fecha_fin').val();
            var fechaIni = $('#fecha_inicio').val();
            f1 = fechaIni.split("-");
            var fechaFin = $('#fecha_fin').val();
            f2 = fechaFin.split("-");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
            if (f1 > f2) {
                $('#fecha_inicio').val(fechaFinIni);
                $('#fecha_inicio').change();
                bootbox.alert("La Fecha de Inicio no debe ser mayor a la Fecha Fin");
                return false;
            }
        });


        $('#fecha_fin').datepicker().on('hide', function (ev) {
            var fechaFinIni = $('#fecha_inicio').val();
            var fechaIni = $('#fecha_inicio').val();
            f1 = fechaIni.split("-");
            var fechaFin = $('#fecha_fin').val();
            f2 = fechaFin.split("-");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
            if (f1 > f2) {
                $('#fecha_fin').val(fechaFinIni);
                $('#fecha_fin').change();
                bootbox.alert("La Fecha Fin no debe ser menor a la Fecha de Inicio");
                return false;
            }
        });

    });

</script>
<style>
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <!--
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
        
        -->
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Res&uacutemen de Recetas Dispensadas - {{$farmacia->nombre}}</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <form method="post" action="{{ route('post_resumen_diario')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                                    <div class="col-sm-4">
                                        <label class="help-block">Fecha de Inicio <i class="fa fa-calendar"></i></label>
                                        <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_inicio}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="help-block">Fecha Fin <i class="fa fa-calendar"></i></label><!--fecha_expedicion-->
                                        <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_fin}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="help-block">&nbsp;</label>
                                        <input class="btn btn-primary form-control" type="submit" value="Buscar">
                                    </div>    
                                </form>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-info" style="margin-top:10px">
                                        <div class="panel-heading">Del {{$fecha_inicio}} al {{$fecha_fin}}</div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>TITULARES PNP</th>
                                                    <th>FAMILIARES PNP</th>
                                                    <th>TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>NUMERO DE RECETAS</td>
                                                    <td>{{$nro_recetas_titular}}</td>
                                                    <td>{{$nro_recetas_familiar}}</td>
                                                    <td>{{$nrototal_recetas}}</td>
                                                </tr>
                                                <tr>                     
                                                    <td>ITEM PRESCRITO</td>
                                                    <td>{{$item_prescrito_titular}}</td>
                                                    <td>{{$item_prescrito_familiar}}</td>
                                                    <td>{{$total_prescrito}}</td>
                                                </tr>
                                                <tr>
                                                    <td>ITEM ATENDIDO</td>
                                                    <td>{{$item_atendido_titular}}</td>
                                                    <td>{{$item_atendido_familiar}}</td>
                                                    <td>{{$total_atendido}}</td>
                                                </tr>
                                                <tr>
                                                    <td>UNIDAD PRESCRITA</td>
                                                    <td>{{$unidad_prescrita_titular}}</td>
                                                    <td>{{$unidad_prescrita_familiar}}</td>
                                                    <td>{{$total_prescrita}}</td>
                                                </tr>
                                                <tr>
                                                    <td>UNIDAD ATENDIDA</td>
                                                    <td>{{$unidad_atendida_titular}}</td>
                                                    <td>{{$unidad_atendida_familiar}}</td>
                                                    <td>{{$total_atendida}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:5px">

                                <table class="table table-bordered">
                                    <tr>
                                        <td class="col-sm-3 text-right" style="background:#d9edf7">
                                            <label for="name1">TOTAL DE RECETAS</label>
                                        </td>
                                        <td class="col-sm-3">
                                            {{$total_recetas}}
                                        </td>
                                    </tr>
                                    @foreach($consultorios as $c)
                                    <tr>
                                        <td class="col-sm-3 text-right" style="background:#d9edf7">
                                            <label for="family">{{$c->nombre}}</label>
                                        </td>
                                        <td class="col-sm-3">
                                            {{$c->contador}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>

                            </div> 
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection