<div id="blur-container">
@extends('frontend.layouts.master')
@section('renderjs')
<style>

</style>

<script>
function reporteApertura(idUser) {

        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                var fecha_inicio = $('#fecha_inicio').val();
                var fecha_fin = $('#fecha_fin').val();
                var id_farmacia = $('#id_farmacia').val();

                if (fecha_inicio == "")fecha_inicio = 0;
                if (fecha_fin == "")fecha_fin = 0;
                if (id_farmacia == "")id_farmacia = 0;
                Swal.showLoading();
                $("#frmIci").submit();
            }
        });

        }

</script>
@endsection
@section('content')
<div class="container col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Reporte ICI
            </div>            
        </div>
        <div class="panel-body">
            <form class="" method="post" action="{{ route('generar_ici')}}" id="frmIci">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input id="id_farmacia" name="id_farmacia" type="hidden" value="{{$id_farmacia}}">
                <div class="col-sm-4">
                    <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required=""  value="{{$fecha_actual}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"> <span class="help-block">Fecha Desde</span>  
                </div>
                <div class="col-sm-4">
                    <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_actual}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"> 
                    <span class="help-block">Fecha Hasta</span>  
                </div>
                <div class="col-sm-1">
                    <i class="fa fa-calendar fa-2x"></i>
                </div>
                <div class="col-sm-3">
                    @if($count_f_inicio_operaciones>0)
                    <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteApertura('{{ Auth::user()->id }}')" >
                        <i class="fa fa-search" aria-hidden="true"></i> Buscar
                                  </a>
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
