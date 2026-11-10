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
                Reporte ICI - {{$nombre_farmacia}}

            </div>            
        </div>
        <div class="panel-body">
            <form class="" method="post" action="{{ route('generar_reporte_ici_by_farma')}}" id="frmIci">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input id="id_user" name="id_user" type="hidden" value="{{ Auth::user()->id }}">
                <input id="id_farmacia" name="id_farmacia" type="hidden" value="{{$id_farmacia}}">

                <?php if ($count_f_inicio_operaciones > 0): ?>

                    <div class="col-md-4">
                        <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_actual}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"> 
                        <span class="help-block">Fecha Hasta</span>  
                    </div>
                    <div class="col-md-1">
                        <i class="fa fa-calendar fa-2x"></i>
                    </div>
                    <div class="col-md-2">  
                        @if($count_f_inicio_operaciones>0)
                        <button class="form-control btn btn-info" type="submit">Buscar</button>
                        @else
                        <button class="form-control btn btn-info" type="submit" disabled="">Buscar</button>
                        @endif
                    </div>
                <?php endif; ?>


                <div class="col-md-12">                    
                    <span style="font-size: 11px">
                        <?php
                        if ($count_f_inicio_operaciones == 0) {
                            echo "Nota Importante: La " . $nombre_farmacia . " aun no usan el aplicativo web, por el cual no puede generar un Reporte ICI.";
                        } else {
                            $originalDate = $f_inicio_operaciones;
                            $fecha_operaciones = date("d-m-Y", strtotime($originalDate));
                            ?>                            
                            Nota Importante: El Inicio del uso del aplicativo web es el 
                            <?php
                            echo $f_inicio_operaciones . " para la " . $nombre_farmacia;
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
