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
        <span class="pull-right">Hoy: {{$fecha}}</span>
    </h4>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">REGISTRAR SALIDAS DIARIAS</h3>                   
            </div>
            <form class="form-horizontal" method="post" action="{{ route('add_consumo_diario')}}" id="frmReceta" style="font-size: 11px">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="padding-top:30px" class="panel-body" >                                                        
                    <div class="col-md-12">
                        <div class="col-md-1"><b>CODIGO</b></div>
                        <div class="col-md-3"><b>NOMBRE</b></div>
                        <div class="col-md-1"><b>CANTIDAD</b></div>
                    </div>
                    @foreach($medicamentos as $med)
                    <div class="col-md-12" style="margin-top: 3px">
                        <div class="col-md-1">                                                                        
                            <span>{{$med->codigo_producto}}</span>                            
                            <input type="hidden" name="codigo_producto[]" id="codigo_producto[]" value="{{$med->codigo_producto}}" class="form-control"> 
                            <input type="hidden" value="{{$farma->nombre}}" name="nombre_farmacia">
                            <input type="hidden" value="{{$fecha}}" name="fecha">
                            <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                        </div>
                        <div class="col-md-3">                                            
                            <span>{{$med->nombre_producto}}</span>
                            <input type="hidden" name="nombre_producto[]" id="nombre_producto[]" value="{{$med->nombre_producto}}" class="form-control"> 
                        </div>                                                                            
                        <div class="col-md-1">                                            
                            <input type="number" name="cantidad[]" id="cantidad[]" value="" style="width: 55px" tabindex="1" autofocus=""> 
                        </div>
                    </div>
                    @endforeach
                    <div class="col-md-12" style="border-top: 1px solid #e5e5e5; margin-top: 15px; padding-top: 15px">                                 
                        <div class="col-md-3">
                            <a class="form-control btn btn-danger" href="/stocksbyFamarcia/{{ Auth::user()->id }}">
                                <i class="fa fa-arrow-circle-left"></i> Ir al Stock
                            </a>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control btn btn-success" value="Guardar" type="submit">
                        </div>
                    </div>  
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
