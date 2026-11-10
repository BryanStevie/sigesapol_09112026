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
    
    $("#btnReg").on("click", function () {
        /*if (confirm("Atención: Revise bien los datos ingresados antes de dar click en Aceptar, recuerde que NO se registraran el ingreso de las cantidades recibidas que son mayores a las\n\
 cantidades solicitadas. ¿Desea Grabar?") == true) {
            $("#frmRequerimiento").submit();
        }*/
        if (confirm("Atención: Revise bien los datos ingresados antes Grabar. ¿Desea Grabar?") == true) {
            $("#frmRequerimiento").submit();
        }
    });
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
    <div class="row col-md-12">
        <div class="panel panel-primary filterable" >
            <div class="panel-heading">
                <h3 class="panel-title">INGRESO REGULAR DE PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</h3>
            </div>
            <form class="form-horizontal" method="post" action="{{ route('add_guia')}}" id="frmReceta">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="padding-top:30px" class="panel-body" >
                    @if($estado=="atendido")
                    <legend>Productos Ingresados</legend>
                    @else
                    <legend>Paso 2. Recepcionar e Ingresar los Productos</legend>
                    @endif
                    <div class="col-md-3" style="margin-bottom: 10px">
                        <label>Nro. Pecosa</label>
                        <input id="nro_guia" name="nro_guia" type="number"  class="form-control input-md" required="" tabindex="1" autofocus="" value="{{$getRequerimiento->nro_pecosa}}">
                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                        <input type="hidden" value="{{$farma->id}}" name="id_farmacia">
                    </div> 
                    <div class="col-md-3" style="margin-bottom: 10px">
                        <label>Fecha</label>
                        <input id="fecha" name="fecha" type="text" value="" class="form-control input-md datepicker">
                        <!--<input id="textinput" name="textinput" type="text" value="{{$fecha}}" class="form-control input-md" disabled="">-->                        
                        <!--<input type="hidden" value="{{$fecha}}" name="fecha">-->
                        <input type="hidden" name="id_requerimiento" value="{{$requerimiento}}">
                    </div>
                    <div class="col-md-12" style=" border-bottom: 1px solid gray; margin-bottom: 20px">
                        <h4>Productos</h4>
                    </div>
                    <div class="form-group multiple-form-group input-group col-md-12" style="font-size:11px">          
                        <div class="col-md-1"> 
                            <label>N</label>
                        </div>
                        <div class="col-md-1"> 
                            <label>CODIGO</label>
                        </div>
                        <div class="col-md-3"> 
                            <label>DESCRIPCION</label>
                        </div>
                        <div class="col-md-1"> 
                            <label>UNIDAD</label>
                        </div>
                        <div class="col-md-1"> 
                            <label>CANTIDAD SOLICITADA</label>
                        </div>
                        <div class="col-md-1"> 
                            <label>CANTIDAD RECIBIDA</label>
                        </div>
                    </div>
                    <?php $n = 0; ?>
                    @foreach($productos as $p)
                    <div class="form-group multiple-form-group input-group col-md-12" style="font-size:11px">             
                        <div class="col-md-1"> 
                            <?php $n++; echo $n; ?>
                            <input value="{{$p->id}}" type="hidden" name="id_req_producto[]">
                        </div>
                        <div class="col-md-1"> 
                            {{$p->codigo_producto}}
                            <input value="{{$p->codigo_producto}}" name="codigo_producto[]"  id="codigo_producto[]" type="hidden">
                        </div>
                        <div class="col-md-3"> 
                            {{$p->nombre}}
                        </div>
                        <div class="col-md-1"> 
                            {{$p->abreviatura}}
                        </div>
                        <div class="col-md-1"> 
                            {{$p->cantidad}}
                            <input value="{{$p->cantidad}}" name="cantidad_solicitada[]"  id="cantidad_solicitada" type="hidden">
                        </div>
                        <div class="col-md-1">                         
                            <input type="number" name="cantidad[]" id="cantidad[]" class="nro_solicitado" required="" style="width:60px" value="{{$p->cantidad_recibida}}">                                                   
                        </div> 
                    </div>                  
                    @endforeach
                    <div class="col-md-12" style="border-top: 1px solid #e5e5e5; margin-top: 15px; padding-top: 15px; font-size: 11px ">                        
                        <div class="col-md-2 pull-right">
                            @if($estado=="atendido")
                            <input class="form-control btn btn-default" value="Ingresar" type="submit" tabindex="5" id="btnReg" disabled="">
                            @else
                            <input class="form-control btn btn-success" value="Ingresar" type="submit" tabindex="5" id="btnReg">
                            @endif
                        </div>         
                        <!--<div class="col-md-2 pull-right">
                            <a class="form-control btn btn-danger" href="/stocksbyFamarcia/{{ Auth::user()->id }}" tabindex="6">
                               Stock 
                            </a>
                        </div>-->
                        <div class="col-md-3 pull-right">
                            <a class="form-control btn btn-primary" href="/ver_requerimiento/{{ Auth::user()->id }}" tabindex="6">
                                <i class="fa fa-list"></i> Listar los Requerimientos
                            </a>
                        </div>
                        @if($estado=="atendido")                        
                        <div class="col-md-3 pull-right">                            
                            <a class="form-control btn btn-success" href="/imprimir_add_medicamentos/{{ Auth::user()->id }}/{{$requerimiento}}" tabindex="6">
                                <i class="fa fa-file-excel-o"></i> Imprimir Pecosa recepcionada
                            </a>
                        </div>    
                        @else
                        <div class="col-md-7 pull-right" style="font-size: 9px">
                            (*)Recuerde que todos los casilleros de "CANTIDAD RECIBIDA" deben contener un valor obligatorio, en el caso que No se recibió la cantidad de un determinado 
                            producto solicitado se debe colocar el valor de 0 (cero).
                        </div>
                        @endif
                    </div>  
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
