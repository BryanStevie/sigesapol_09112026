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
        {{$nombre_farmacia}}
        <a href="{{ url('/stocks/create') }}" class="btn btn-primary pull-right btn-sm" style="display:none">
            <i class="fa fa-plus"></i> Agregar Stock
        </a>
    </h4>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">INGRESO REGULAR DE PRODUCTOS FARMACÉTICOS Y DISPOSITIVOS MÉDICOS</h3>
            </div>
            <form class="form-horizontal" method="post" action="{{ route('add_productos_sinrequerimiento')}}" id="frmReceta">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="padding-top:30px" class="panel-body" >                 
                    <div class="col-md-3" style="margin-bottom: 10px">
                        <label>Nro. Pecosa</label>
                        <input id="nro_guia" name="nro_guia" type="number"  class="form-control input-md" required="" tabindex="1" autofocus="">
                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                        <input type="hidden" value="{{$id_farmacia}}" name="id_farmacia">
                        <input type="hidden" value="{{$nombre_farmacia}}" name="nombre_farmacia">
                    </div> 
                    <div class="col-md-3" style="margin-bottom: 10px">
                        <label>Fecha</label>
                        <input id="textinput" name="textinput" type="text" value="{{$fecha}}" class="form-control input-md" disabled="">
                        <input type="hidden" value="{{$fecha}}" name="fecha">
                    </div>
                    <div class="col-md-12" style=" border-bottom: 1px solid gray; margin-bottom: 20px">
                        <h4>PRODUCTOS FARMACEUTICOS Y DISPOSITIVOS MEDICOS</h4>
                    </div>
                    <div class="form-group multiple-form-group input-group col-md-12">
                        <div class="col-md-9">                                            
                            <select class="form-control chosen-select" tabindex="2" id="codigo_producto[]" name="codigo_producto[]" required="">
                                <option value="">Seleccionar Medicamento</option>                                                                                                                                                            
                                @foreach($productos as $p)
                                <option value="{{$p->codigo}}">{{$p->nombre}}, {{$p->abreviatura}}</option>         
                                @endforeach
                            </select>                               
                        </div>
                        <div class="col-md-2">                                            
                            <input type="number" name="cantidad[]" id="cantidad[]" class="form-control nro_solicitado" required="" tabindex="3" placeholder="Cantidad">
                        </div>                                        
                        <div class="col-md-1">                                            
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success btn-add" tabindex="4">+</button>
                            </span>
                        </div>                                      
                    </div>
                    <div class="col-md-12" style="border-top: 1px solid #e5e5e5; margin-top: 15px; padding-top: 15px">
                        <div class="col-md-2 pull-right">
                            <input class="form-control btn btn-success" value="Agregar" type="submit" tabindex="5">
                        </div>         
                        <div class="col-md-2 pull-right">
                            <a class="form-control btn btn-danger" href="/stocksbyFamarcia/{{ Auth::user()->id }}" tabindex="6">
                                <i class="fa fa-arrow-circle-left"></i> Ir al Stock
                            </a>
                        </div>
                    </div>  
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
