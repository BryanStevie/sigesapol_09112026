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
                                                    @if($id_farmacia==25)
                                                    <th colspan="4">TITULARES PNP</th>
                                                    @else
                                                        @if($id_farmacia==107)
                                                            <th colspan="8">TITULARES PNP</th>
                                                        @else
                                                            <th colspan="6">TITULARES PNP</th>
                                                        @endif
                                                    @endif
                                                    @if($id_farmacia==25)
                                                    <th colspan="4">FAMILIARES PNP</th>
                                                    @else
                                                        @if($id_farmacia==107)
                                                            <th colspan="8">FAMILIARES PNP</th>
                                                        @else
                                                            <th colspan="6">FAMILIARES PNP</th>
                                                        @endif
                                                    @endif
                                                    @if($id_farmacia==25)
                                                    <th colspan="4">SUB TOTAL</th>
                                                    @else
                                                        @if($id_farmacia==107)
                                                            <th colspan="8">SUB TOTAL</th>
                                                        @else
                                                            <th colspan="6">SUB TOTAL</th>
                                                        @endif
                                                    @endif
                                                    <th colspan="4">TOTAL</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th colspan="2">AMB</th>
                                                    @if($id_farmacia!=25)
                                                    <th colspan="2">HOSP</th>
                                                    <th colspan="2">EMER</th>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <th colspan="2">CQ</th>
                                                    @endif
                                                    <th colspan="2">AMB</th>
                                                    @if($id_farmacia!=25)
                                                    <th colspan="2">HOSP</th>
                                                    <th colspan="2">EMER</th>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <th colspan="2">CQ</th>
                                                    @endif
                                                    <th colspan="2">AMB</th>
                                                    @if($id_farmacia!=25)
                                                    <th colspan="2">HOSP</th>
                                                    <th colspan="2">EMER</th>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <th colspan="2">CQ</th>
                                                    @endif
                                                    <th colspan="4"></th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>NUMERO DE RECETAS</td>
                                                    <td colspan="2">{{$nro_recetas_titular_amb}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td colspan="2">{{$nro_recetas_titular_hos}}</td>
                                                    <td colspan="2">{{$nro_recetas_titular_eme}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td colspan="2">{{$nro_recetas_titular_cq}}</td>
                                                    @endif
                                                    <td colspan="2">{{$nro_recetas_familiar_amb}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td colspan="2">{{$nro_recetas_familiar_hos}}</td>
                                                    <td colspan="2">{{$nro_recetas_familiar_eme}}</td>  
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td colspan="2">{{$nro_recetas_familiar_cq}}</td>  
                                                    @endif
                                                    <td colspan="2">{{$nrototal_recetas_amb}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td colspan="2">{{$nrototal_recetas_hos}}</td>
                                                    <td colspan="2">{{$nrototal_recetas_eme}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td colspan="2">{{$nrototal_recetas_cq}}</td>
                                                    @endif
                                                    <td colspan="3">{{$nrototal_recetas_amb+$nrototal_recetas_hos+$nrototal_recetas_eme+$nrototal_recetas_cq}}</td>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @if($id_farmacia!=25)
                                                    <th>M</th>
                                                    <th>D</th>
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @endif
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @if($id_farmacia!=25)
                                                    <th>M</th>
                                                    <th>D</th>                                                    
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @endif
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @if($id_farmacia!=25)
                                                    <th>M</th>
                                                    <th>D</th>
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <th>M</th>
                                                    <th>D</th>
                                                    @endif
                                                    <th colspan="2">SUBTOTAL</th>
                                                    <th >TOTAL</th>
                                                </tr>
                                                <tr>                     
                                                    <td>ITEM PRESCRITO</td>                                                    
                                                    <td>{{$item_prescrito_titular_amb_medicamentos}}</td>
                                                    <td>{{$item_prescrito_titular_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$item_prescrito_titular_hos_medicamentos}}</td>
                                                    <td>{{$item_prescrito_titular_hos_dispositivos}}</td>
                                                    <td>{{$item_prescrito_titular_eme_medicamentos}}</td>
                                                    <td>{{$item_prescrito_titular_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$item_prescrito_titular_cq_medicamentos}}</td>
                                                    <td>{{$item_prescrito_titular_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$item_prescrito_familiar_amb_medicamentos}}</td>
                                                    <td>{{$item_prescrito_familiar_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$item_prescrito_familiar_hos_medicamentos}}</td>
                                                    <td>{{$item_prescrito_familiar_hos_dispositivos}}</td>
                                                    <td>{{$item_prescrito_familiar_eme_medicamentos}}</td>
                                                    <td>{{$item_prescrito_familiar_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$item_prescrito_familiar_cq_medicamentos}}</td>
                                                    <td>{{$item_prescrito_familiar_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$total_prescrito_medicamentos_amb}}</td>
                                                    <td>{{$total_prescrito_dispositivos_amb}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$total_prescrito_medicamentos_hos}}</td>
                                                    <td>{{$total_prescrito_dispositivos_hos}}</td>
                                                    <td>{{$total_prescrito_medicamentos_eme}}</td>
                                                    <td>{{$total_prescrito_dispositivos_eme}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$total_prescrito_medicamentos_cq}}</td>
                                                    <td>{{$total_prescrito_dispositivos_cq}}</td>
                                                    @endif
                                                    <td>{{$total_prescrito_medicamentos_amb+$total_prescrito_medicamentos_hos+$total_prescrito_medicamentos_eme+$total_prescrito_medicamentos_cq}}</td>
                                                    <td>{{$total_prescrito_dispositivos_amb+$total_prescrito_dispositivos_hos+$total_prescrito_dispositivos_eme+$total_prescrito_dispositivos_cq}}</td>
                                                    <td>{{$total_prescrito_medicamentos_amb+$total_prescrito_medicamentos_hos+$total_prescrito_medicamentos_eme+$total_prescrito_medicamentos_cq+$total_prescrito_dispositivos_amb+$total_prescrito_dispositivos_hos+$total_prescrito_dispositivos_eme+$total_prescrito_dispositivos_cq}}</td>
                                                </tr>
                                                <tr>
                                                    <td>ITEM ATENDIDO</td>
                                                    <td>{{$item_atendido_titular_amb_medicamentos}}</td>
                                                    <td>{{$item_atendido_titular_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$item_atendido_titular_hos_medicamentos}}</td>
                                                    <td>{{$item_atendido_titular_hos_dispositivos}}</td>
                                                    <td>{{$item_atendido_titular_eme_medicamentos}}</td>
                                                    <td>{{$item_atendido_titular_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$item_atendido_titular_cq_medicamentos}}</td>
                                                    <td>{{$item_atendido_titular_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$item_atendido_familiar_amb_medicamentos}}</td>
                                                    <td>{{$item_atendido_familiar_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$item_atendido_familiar_hos_medicamentos}}</td>
                                                    <td>{{$item_atendido_familiar_hos_dispositivos}}</td>
                                                    <td>{{$item_atendido_familiar_eme_medicamentos}}</td>
                                                    <td>{{$item_atendido_familiar_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$item_atendido_familiar_cq_medicamentos}}</td>
                                                    <td>{{$item_atendido_familiar_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$total_atendido_medicamentos_amb}}</td>
                                                    <td>{{$total_atendido_dispositivos_amb}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$total_atendido_medicamentos_hos}}</td>
                                                    <td>{{$total_atendido_dispositivos_hos}}</td>
                                                    <td>{{$total_atendido_medicamentos_eme}}</td>
                                                    <td>{{$total_atendido_dispositivos_eme}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$total_atendido_medicamentos_cq}}</td>
                                                    <td>{{$total_atendido_dispositivos_cq}}</td>
                                                    @endif
                                                    <td>{{$total_atendido_medicamentos_amb+$total_atendido_medicamentos_hos+$total_atendido_medicamentos_eme+$total_atendido_medicamentos_cq}}</td>
                                                    <td>{{$total_atendido_dispositivos_amb+$total_atendido_dispositivos_hos+$total_atendido_dispositivos_eme+$total_atendido_dispositivos_cq}}</td>
                                                    <td>{{$total_atendido_medicamentos_amb+$total_atendido_medicamentos_hos+$total_atendido_medicamentos_eme+$total_atendido_medicamentos_cq+$total_atendido_dispositivos_amb+$total_atendido_dispositivos_hos+$total_atendido_dispositivos_eme+$total_atendido_dispositivos_cq}}</td>
                                                </tr>
                                                <tr>
                                                    <td>UNIDAD PRESCRITA</td>
                                                    <td>{{$unidad_prescrita_titular_amb_medicamentos}}</td>
                                                    <td>{{$unidad_prescrita_titular_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$unidad_prescrita_titular_hos_medicamentos}}</td>
                                                    <td>{{$unidad_prescrita_titular_hos_dispositivos}}</td>
                                                    <td>{{$unidad_prescrita_titular_eme_medicamentos}}</td>
                                                    <td>{{$unidad_prescrita_titular_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$unidad_prescrita_titular_cq_medicamentos}}</td>
                                                    <td>{{$unidad_prescrita_titular_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$unidad_prescrita_familiar_amb_medicamentos}}</td>
                                                    <td>{{$unidad_prescrita_familiar_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$unidad_prescrita_familiar_hos_medicamentos}}</td>
                                                    <td>{{$unidad_prescrita_familiar_hos_dispositivos}}</td>
                                                    <td>{{$unidad_prescrita_familiar_eme_medicamentos}}</td>
                                                    <td>{{$unidad_prescrita_familiar_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$unidad_prescrita_familiar_cq_medicamentos}}</td>
                                                    <td>{{$unidad_prescrita_familiar_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$total_prescrita_amb_medicamentos}}</td>
                                                    <td>{{$total_prescrita_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$total_prescrita_hos_medicamentos}}</td>
                                                    <td>{{$total_prescrita_hos_dispositivos}}</td>
                                                    <td>{{$total_prescrita_eme_medicamentos}}</td>
                                                    <td>{{$total_prescrita_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$total_prescrita_cq_medicamentos}}</td>
                                                    <td>{{$total_prescrita_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$total_prescrita_amb_medicamentos+$total_prescrita_hos_medicamentos+$total_prescrita_eme_medicamentos+$total_prescrita_cq_medicamentos}}</td>
                                                    <td>{{$total_prescrita_amb_dispositivos+$total_prescrita_hos_dispositivos+$total_prescrita_eme_dispositivos+$total_prescrita_cq_dispositivos}}</td>
                                                    <td>{{$total_prescrita_amb_medicamentos+$total_prescrita_hos_medicamentos+$total_prescrita_eme_medicamentos+$total_prescrita_cq_medicamentos+$total_prescrita_amb_dispositivos+$total_prescrita_hos_dispositivos+$total_prescrita_eme_dispositivos+$total_prescrita_cq_dispositivos}}</td>
                                                </tr>                                                
                                                <tr>
                                                    <td>UNIDAD ATENDIDA</td>
                                                    <td>{{$unidad_atendida_titular_amb_medicamentos}}</td>
                                                    <td>{{$unidad_atendida_titular_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$unidad_atendida_titular_hos_medicamentos}}</td>
                                                    <td>{{$unidad_atendida_titular_hos_dispositivos}}</td>
                                                    <td>{{$unidad_atendida_titular_eme_medicamentos}}</td>
                                                    <td>{{$unidad_atendida_titular_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$unidad_atendida_titular_cq_medicamentos}}</td>
                                                    <td>{{$unidad_atendida_titular_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$unidad_atendida_familiar_amb_medicamentos}}</td>
                                                    <td>{{$unidad_atendida_familiar_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$unidad_atendida_familiar_hos_medicamentos}}</td>
                                                    <td>{{$unidad_atendida_familiar_hos_dispositivos}}</td>
                                                    <td>{{$unidad_atendida_familiar_eme_medicamentos}}</td>
                                                    <td>{{$unidad_atendida_familiar_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$unidad_atendida_familiar_cq_medicamentos}}</td>
                                                    <td>{{$unidad_atendida_familiar_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$total_atendida_amb_medicamentos}}</td>
                                                    <td>{{$total_atendida_amb_dispositivos}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td>{{$total_atendida_hos_medicamentos}}</td>
                                                    <td>{{$total_atendida_hos_dispositivos}}</td>
                                                    <td>{{$total_atendida_eme_medicamentos}}</td>
                                                    <td>{{$total_atendida_eme_dispositivos}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td>{{$total_atendida_cq_medicamentos}}</td>
                                                    <td>{{$total_atendida_cq_dispositivos}}</td>
                                                    @endif
                                                    <td>{{$total_atendida_amb_medicamentos+$total_atendida_hos_medicamentos+$total_atendida_eme_medicamentos+$total_atendida_cq_medicamentos}}</td>
                                                    <td>{{$total_atendida_amb_dispositivos+$total_atendida_hos_dispositivos+$total_atendida_eme_dispositivos+$total_atendida_cq_dispositivos}}</td>
                                                    <td>{{$total_atendida_amb_medicamentos+$total_atendida_hos_medicamentos+$total_atendida_eme_medicamentos+$total_atendida_cq_medicamentos+$total_atendida_amb_dispositivos+$total_atendida_hos_dispositivos+$total_atendida_eme_dispositivos+$total_atendida_cq_dispositivos}}</td>
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
                                        <td colspan="4" class="col-sm-3">
                                            {{$total_recetas_amb}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-sm-3 text-right" style="background:#d9edf7"></td>
                                        <td style="background:#d9edf7">
                                            AMB
                                        </td>
                                        @if($id_farmacia!=25)
                                        <td style="background:#d9edf7">
                                            HOS
                                        </td>
                                        <td style="background:#d9edf7">
                                            EME
                                        </td>
                                        @endif
                                        @if( $id_farmacia==25 || $id_farmacia==107)
                                        <td style="background:#d9edf7">
                                            CQ
                                        </td>
                                        @endif
                                    </tr>
                                    <?php $m = 1; $cont=0;
                                        $nombre='consultorio';
                                        $contador_ambulatorio=0;$contador_hospitalizacion=0;$contador_emergencia=0;$contador_centro_quirurgico=0;
                                    ?>
                                    @foreach($consultorios as $c)
                                    
                                        <?php 

                                        if($c->tipo_receta=='AMBULATORIO'){
                                            if($nombre==$c->nombre){
                                                $contador_ambulatorio= $c->contador ;
                                            }else
                                            {
                                                if($nombre!='consultorio'){ ?>
                                                <tr>
                                                    <td class="col-sm-3 text-right" style="background:#d9edf7"><label for="family">{{$nombre}}</label></td>
                                                    <td class="col-sm-3">{{$contador_ambulatorio}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td class="col-sm-3">{{$contador_hospitalizacion}}</td>
                                                    <td class="col-sm-3">{{$contador_emergencia}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td class="col-sm-3">{{$contador_centro_quirurgico}}</td>
                                                    @endif
                                                </tr>
                                                  <?php
                                                  $contador_ambulatorio=0;$contador_hospitalizacion=0;$contador_emergencia=0;$contador_centro_quirurgico=0;
                                                  $contador_ambulatorio= $c->contador ;
                                                  $nombre=$c->nombre;  
                                                  $m++;
                                                }
                                                else
                                                {
                                                  $nombre=$c->nombre;  
                                                  $contador_ambulatorio= $c->contador ;
                                                }
                                            }
                                          }
                                        ?>
                                        <?php if($c->tipo_receta=='HOSPITALIZACION'){
                                            if($nombre==$c->nombre){
                                                $contador_hospitalizacion= $c->contador ;
                                            }else
                                            {
                                                if($nombre!='consultorio'){ ?>
                                                <tr>
                                                    <td class="col-sm-3 text-right" style="background:#d9edf7"><label for="family">{{$nombre}}</label></td>
                                                    <td class="col-sm-3">{{$contador_ambulatorio}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td class="col-sm-3">{{$contador_hospitalizacion}}</td>
                                                    <td class="col-sm-3">{{$contador_emergencia}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td class="col-sm-3">{{$contador_centro_quirurgico}}</td>
                                                    @endif
                                                </tr>
                                                  <?php
                                                  $contador_ambulatorio=0;$contador_hospitalizacion=0;$contador_emergencia=0;$contador_centro_quirurgico=0;
                                                  $contador_hospitalizacion= $c->contador ;
                                                  $nombre=$c->nombre;  
                                                  $m++;
                                                }
                                                else
                                                {
                                                  $nombre=$c->nombre;  
                                                  $contador_hospitalizacion= $c->contador ;
                                                }
                                            }
                                          }
                                        ?>
                                        <?php if($c->tipo_receta=='EMERGENCIA'){
                                            if($nombre==$c->nombre){
                                                $contador_emergencia= $c->contador ;$col=1;  
                                            }else
                                            {
                                                if($nombre!='consultorio'){ ?>
                                                <tr>
                                                    <td class="col-sm-3 text-right" style="background:#d9edf7"><label for="family">{{$nombre}}</label></td>
                                                    <td class="col-sm-3">{{$contador_ambulatorio}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td class="col-sm-3">{{$contador_hospitalizacion}}</td>
                                                    <td class="col-sm-3">{{$contador_emergencia}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td class="col-sm-3">{{$contador_centro_quirurgico}}</td>
                                                    @endif
                                                </tr>
                                                  <?php
                                                  $contador_ambulatorio=0;$contador_hospitalizacion=0;$contador_emergencia=0;$contador_centro_quirurgico=0;
                                                  $contador_emergencia= $c->contador ;
                                                  $nombre=$c->nombre;  
                                                  $m++;
                                                }
                                                else
                                                {
                                                  $nombre=$c->nombre;  
                                                  $contador_emergencia= $c->contador ;
                                                }
                                            }                                    }
                                        ?>
                                        <?php if($c->tipo_receta=='CENTRO QUIRURGICO'){
                                            if($nombre==$c->nombre){
                                                $contador_centro_quirurgico= $c->contador ;$col=1;  
                                            }else
                                            {
                                                if($nombre!='consultorio'){ ?>
                                                <tr>
                                                    <td class="col-sm-3 text-right" style="background:#d9edf7"><label for="family">{{$nombre}}</label></td>
                                                    <td class="col-sm-3">{{$contador_ambulatorio}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td class="col-sm-3">{{$contador_hospitalizacion}}</td>
                                                    <td class="col-sm-3">{{$contador_emergencia}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td class="col-sm-3">{{$contador_centro_quirurgico}}</td>
                                                    @endif
                                                </tr>
                                                  <?php
                                                  $contador_ambulatorio=0;$contador_hospitalizacion=0;$contador_emergencia=0;$contador_centro_quirurgico=0;
                                                  $contador_centro_quirurgico= $c->contador ;
                                                  $nombre=$c->nombre;  
                                                  $m++;
                                                }
                                                else
                                                {
                                                  $nombre=$c->nombre;  
                                                  $contador_centro_quirurgico= $c->contador ;
                                                }
                                            }                                    }
                                        ?>
                                    
                                    @endforeach
                                    <tr>
                                        <td class="col-sm-3 text-right" style="background:#d9edf7"><label for="family">{{$nombre}}</label></td>
                                                    <td class="col-sm-3">{{$contador_ambulatorio}}</td>
                                                    @if($id_farmacia!=25)
                                                    <td class="col-sm-3">{{$contador_hospitalizacion}}</td>
                                                    <td class="col-sm-3">{{$contador_emergencia}}</td>
                                                    @endif
                                                    @if( $id_farmacia==25 || $id_farmacia==107)
                                                    <td class="col-sm-3">{{$contador_centro_quirurgico}}</td>
                                                    @endif
                                    </tr>

                                </table>

                            </div> 
                            <div class="col-md-6" style="margin-bottom: 10px">
                                    <a class="btn btn-success form-control" href="/exportar_reporte_por_consultorios2/{{$fecha_inicio}}/{{$fecha_fin}}/{{Auth::user()->id_farmacia}}">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                                    </a>                        
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