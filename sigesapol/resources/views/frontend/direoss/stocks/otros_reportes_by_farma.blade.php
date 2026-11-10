<div id="blur-container">
@extends('frontend.layouts.master')
@section('renderjs')
<script type="text/javascript">

    function validacion(valor) {
        $('#divDni').hide();
        $('#dni').attr('disabled', true).hide();
        if (valor == 13) {
            $('#divDni').show();
            $('#dni').attr('disabled', false).show();
        }
    }

    function validarDni() {
        var dni = $('#dni').val();
        var len = dni.length;
        /*if(len!=8){
         bootbox.alert("En Dni tiene que ser de 8 digitos");
         return false;
         }*/
    }

    $('#id_farmaciaBus').select2();
    $('#id_producto').select2();

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

    function reporteVarios(idUser) {

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
                var tipo = $('#tipo').val();
                if (fecha_inicio == "")fecha_inicio = 0;
                if (fecha_fin == "")fecha_fin = 0;
                if (id_farmacia == "")id_farmacia = 0;
                if (tipo == "")tipo = 0;
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
                <?php echo $titulo_menu ?> - {{$nombre_farmacia}}
                <!--
<span class="pull-right">
    <a href="/stocksbyFamarcias/{{$id_farmacia}}" style="color:white">
        <i class="fa fa-arrow-left"></i> Ir al Stock
    </a>
</span>
                -->
            </div>
        </div>
        <div class="panel-body">
            <form class="" method="post" action="{{ route('otros_resporte_by_farmacia')}}" id="frmIci" <?php if ($tipo == 13): ?> onsubmit="return validarDni()" <?php endif; ?> >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <?php if ($count_f_inicio_operaciones > 0): ?>
                    <div class="col-md-4">
                        <label class="help-block">Fecha de Inicio <i class="fa fa-calendar"></i></label>
                        <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required="" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"/>
                        <?php
                        $originalDate = $f_inicio_operaciones;
                        $fecha_operaciones = date("d-m-Y", strtotime($originalDate));
                        ?> 
                        <input id="fecha_inicio_operaciones" name="fecha_inicio_operaciones" type="hidden" value="<?php echo $fecha_operaciones; ?>">
                        <input id="id_user" name="id_farmacia" type="hidden" value="{{$id_farmacia}}">
                    </div>
                    <div class="col-md-4">
                        <label class="help-block">Fecha Fin <i class="fa fa-calendar"></i></label>
                        <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_actual}}" <?php if ($tipo <> 11) { ?>data-date-end-date="0d"<?php } ?> autocomplete="Off" readonly="" maxlength="10"/>
                    </div>
                    <input type="hidden" name="tipo" value="<?php echo $tipo ?>" />
                    <?php if ($tipo == 13): ?>
                        <div style="padding:0px;margin:0px;" id="divDni" class="col-md-12">
                            <div class="col-md-4 pull-left">
                                <label class="help-block">Nro. Documento</label>
                                <input id="dni" name="dni" type="text" placeholder="" class="form-control input-md" required="" value="" maxlength="12">
                            </div>

                            <div class="col-md-6 pull-left">
                                <label class="help-block">Farmacia</label>
                                <select class="form-control" name="id_farmaciaBus" id="id_farmaciaBus" style="width:100%">
                                    <option value="">Todas las Farmacias</option>
                                    @foreach($farmacias as $f)
                                    <option value="<?php echo $f->id ?>"><?php echo $f->nombre ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($tipo == 15): ?>
                        <div style="padding:0px;margin:0px;" id="divDni" class="col-md-12">
                            <div class="col-md-12">
                                <label class="help-block">Producto</label>
                                <select class="form-control" name="id_producto" id="id_producto">
                                    <option value="">--Seleccionar--</option>
                                    @foreach($productosnivel as $pro)
                                    <option value="<?php echo $pro->id ?>"><?php echo $pro->codigo . " " . $pro->nombre ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="col-md-8 pull-left" style="margin-top: 10px; font-size: 10px;clear:both">
                    <span>
                        <?php
                        if ($count_f_inicio_operaciones == 0) {
                            echo "Nota Importante: La " . $nombre_farmacia . " aun no usan el aplicativo web, por el cual no puede generar un Reporte.";
                        } else {
                            ?>                            
                            Nota Importante: Inicio del uso del aplicativo web el 
                            <?php
                            echo $fecha_operaciones . " para la " . $nombre_farmacia;
                        }
                        ?> 
                    </span>
                </div>
                <div class="col-md-4 pull-right" style="margin-top: 10px">
                    @if($count_f_inicio_operaciones>0)
                    
                    <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteVarios('{{ Auth::user()->id }}')" >
                        <i class="fa fa-search" aria-hidden="true"></i> Buscar
                                  </a>
                    @else
                    <button class="form-control btn btn-info" type="submit" disabled="">Buscar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
