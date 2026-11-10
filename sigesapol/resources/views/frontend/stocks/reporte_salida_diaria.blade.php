@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
    #global {        
        width: auto;        
        margin: 15px 15px 15px 15px;        
    }
    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

</style>

<script>
    //-------------------------------------------------------------
    $("#btnReg").on("click", function () {
        if (confirm("Antes de Aceptar revise si los datos estan correctos. ¿Desea Grabar?") == true) {
            $("#frmReceta").submit();
        }
    });

    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------
    $(".chosen-select").chosen({max_selected_options: 4});
    //--------------------------------------------------------------------------
    (function ($) {

        $(function () {

            var addFormGroup = function (event) {
                event.preventDefault();
                $(".chosen-select").chosen("destroy");
                var $formGroup = $(this).closest('.form-group');
                var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
                var $formGroupClone = $formGroup.clone();
                $(this)
                        .toggleClass('btn-success btn-add btn-danger btn-remove')
                        .html('–');
                $formGroupClone.find('select').val('');
                $formGroupClone.find('.nro_solicitado').val('');
                //$formGroupClone.find('.concept').text('Phone');
                $formGroupClone.insertAfter($formGroup);

                var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
                if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
                    $lastFormGroupLast.find('.btn-add').attr('disabled', true);

                }
                $(".chosen-select").chosen();

                //$(".chosen-select").trigger("chosen:updated");
            };
            var removeFormGroup = function (event) {
                event.preventDefault();
                var $formGroup = $(this).closest('.form-group');
                var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
                var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
                if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
                    $lastFormGroupLast.find('.btn-add').attr('disabled', false);
                }
                $formGroup.remove();
            };
            var selectFormGroup = function (event) {
                event.preventDefault();
                var $selectGroup = $(this).closest('.input-group-select');
                var param = $(this).attr("href").replace("#", "");
                var concept = $(this).text();
                $selectGroup.find('.concept').text(concept);
                $selectGroup.find('.input-group-select-val').val(param);
            }
            var countFormGroup = function ($form) {
                return $form.find('.form-group').length;
            };
            $(document).on('click', '.btn-add', addFormGroup);
            $(document).on('click', '.btn-remove', removeFormGroup);
        });
    })(jQuery);
</script>
@endsection
@section('content')
<div class="container">
    <h4>
        {{$farma->nombre}}
        <a href="{{ url('/stocks/create') }}" class="btn btn-primary pull-right btn-sm" style="display:none">
            <i class="fa fa-plus"></i> Agregar Stock
        </a>
    </h4>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">BUSQUEDA DE REPORTE DE CONSOLIDADOS DIARIOS</h3>
            </div>
            <div style="padding-top:30px" class="panel-body" > 
                <form class="form-horizontal" method="post" action="{{ route('reporte_salida_diaria_ok')}}" id="frmReceta">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label class="col-md-1 control-label" for="textinput">Fecha</label>
                    <div class="col-md-2">
                        <input class="form-control datepicker" name="fecha_salida" id="fecha_salida" required="">
                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                        <input type="hidden" value="{{$farma->nombre}}" name="nombre_farmacia"> 
                        <input type="hidden" value="{{$farma->id}}" name="id_farmacia">   
                    </div>
                    <div class="col-md-1">
                        <input type="submit" value="Buscar" class="form-control btn-info">
                    </div>
                </form>
                @if (count($salidas) > 0)
                <div class="col-md-12" style=" border-bottom: 1px solid gray; margin-bottom: 20px; margin-top: 20px">
                    <h4>Reporte del Consolidado de entrega de Medicamendos del <label>{{$fecha}}</label></h4>
                </div>
                <div class="col-md-2" style="margin-bottom: 10px;">
                    Fecha: <label>{{$fecha}}</label>
                </div>
                <div class="col-md-10" style="margin-bottom: 10px;">
                    Total Recetas: <label>{{$cant_gral_receta}}</label>
                </div>                                         
                <div class="col-md-1" style="margin-bottom: 10px; border-bottom: 1px solid #001F3F; border-top: 1px solid #001F3F">
                    <label>Código</label>
                </div>
                <div class="col-md-4" style="margin-bottom: 10px; border-bottom: 1px solid #001F3F; border-top: 1px solid #001F3F">
                    <label>Medicamento</label>
                </div>
                <div class="col-md-7" style="margin-bottom: 10px; border-bottom: 1px solid #001F3F; border-top: 1px solid #001F3F">
                    <label>Cantidad</label>
                </div>
                @foreach($salidas as $s)
                <div class="col-md-1" style="margin-bottom: 10px;">
                    {{$s->codigo_producto}}
                </div>
                <div class="col-md-4" style="margin-bottom: 10px;">
                    {{$s->nombre_producto}}
                </div>
                <div class="col-md-7" style="margin-bottom: 10px;">
                    {{$s->cantidad}}
                </div>
                @endforeach
                @endif

                <div class="col-md-12" style="border-top: 1px solid #e5e5e5; margin-top: 15px; padding-top: 15px">
                    <div class="col-md-2 pull-right">
                        <a class="form-control btn btn-success" href="/stocksbyFamarcia/{{ Auth::user()->id }}" tabindex="6">
                            <i class="fa fa-file-excel-o"></i> Exportar
                        </a>
                    </div>         
                    <div class="col-md-2 pull-right">
                        <a class="form-control btn btn-danger" href="/stocksbyFamarcia/{{ Auth::user()->id }}" tabindex="6">
                            <i class="fa fa-arrow-circle-left"></i> Ir al Stock
                        </a>
                    </div>
                </div>  
            </div>

        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
