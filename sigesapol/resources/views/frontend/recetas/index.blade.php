@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
</style>
<script>
    //--------------------------------------------------------------------------
    $(".chosen-select").chosen();
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
            //$(document).on('click', '.dropdown-menu a', selectFormGroup);

        });
    })(jQuery);
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Atender Receta <b style="color: #FF851B">N° {{$atencion[0]->nro_historia}}</b></h3>
            <div class="tabbable-panel">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_default_1" data-toggle="tab">
                                Receta 
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_default_1">
                            <form>
                                <table class="table table-striped" style="width:100%">
                                    <tr>
                                        <td>

                                            <table align="center" class="table table-bordered">      
                                                <caption>Datos del Beneficiario</caption>
                                                <tr>
                                                    <td>DNI</td>
                                                    <td><input id="dni_afiliado" name="dni_afiliado" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->dni}}" disabled=""></td>
                                                    <td colspan="2">Nombre(s) y Apellidos</td>
                                                    <td colspan="6">
                                                        <input disabled="" id="nombre_afiliado" name="nombre_afiliado" type="text" class="form-control input-medium" required="" value="{{$atencion[0]->nombre}} {{$atencion[0]->ape_paterno}} {{$atencion[0]->ape_materno}}">
                                                    </td>                                                    
                                                </tr>
                                                <tr>
                                                    <td>Sexo</td>
                                                    <td><input id="sexo" name="sexo" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->sexo}}"disabled=""></td>
                                                    <td colspan="2">Fecha de Nacimiento-(Edad)</td>
                                                    <td><input id="edad" name="edad" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->fecha_nac}} - ({{$atencion[0]->edad}} años)" disabled=""></td>
                                                  
                                                    <td>Email</td>
                                                    <td><input id="email" name="email" type="text"class="form-control input-medium" required=""></td>
                                                    <td>Celular</td>
                                                    <td><input id="telefono" name="telefono" type="text"class="form-control input-medium" required=""></td>       
                                                </tr>
                                            </table>
                                            <table align="center" class="table table-bordered">      
                                                <caption>Datos del Médico</caption>
                                                <tr>
                                                    <td>CMP</td>
                                                    <td><input id="cmp" name="cmp" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->cmp}}"></td>
                                                    <td>Nombre(s)</td>
                                                    <td><input id="nombre_medico" name="nombre_medico" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->nombres_medico}}"></td>
                                                    <td>Apellidos</td>
                                                    <td colspan="3"><input id="paterno_medico" name="paterno_medico" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->ape_paterno_medico}}"></td>

                                                </tr>
                                            </table>
                                            <table align="center" class="table table-bordered">      
                                                <caption>Datos de la Receta</caption>
                                                <tr>
                                                    <td>Especialidad</td>
                                                    <td>                                                        
                                                        <input id="cod_consultorio" name="cod_consultorio" type="hidden" required="">
                                                        <input id="consultorio" name="consultorio" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->especialidad}}">
                                                    </td>
                                                    <td>Servicio</td>
                                                    <td>
                                                        <input id="cod_producto" name="cod_producto" type="hidden" required="">
                                                        <input id="producto" name="producto" type="text"class="form-control input-medium" required="" value="{{$servicio[0]->descripcion}}">
                                                    </td>
                                                    <!--<td>Paterno</td>
                                                    <td><input id="paterno_medico" name="paterno_medico" type="text"class="form-control input-medium" required=""></td>
                                                    <td>Materno</td>
                                                    <td><input id="materno_medico" name="materno_medico" type="text"class="form-control input-medium" required=""></td>-->
                                                </tr>
                                            </table>
                                            <form id="frmReceta">
                                                <h3>Atender Medicamentos</h3>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="contacts">
                                                            <div class="form-group multiple-form-group input-group">
                                                                <div class="col-md-6">
                                                                    <select name="sel0" class="form-control chosen-select">
                                                                        <option value="">Seleccionar Medicamento</option>
                                                                        @foreach($productos as $pro)
                                                                            @if ($pro->monto_actual==0)
                                                                            <option value="{{$pro->id}}" disabled="" style="color: red">
                                                                                    {{$pro->nombre}} - Sin stock
                                                                            </option>
                                                                            @else
                                                                                <option value="{{$pro->id}}">
                                                                                    {{$pro->nombre}} - Con stock
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>                               
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text" name="nro_med_solictados" id="nro_med_solictados" class="form-control" required="" placeholder="Cantidad Solicitada">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text" name="nro_med_solictados" id="nro_med_solictados" class="form-control" required="" placeholder="Cantidad Atendida">
                                                                </div>
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-success btn-add">+</button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <table align="center" class="table table-bordered">      
                                                <tr>
                                                    <td colspan="8" style="text-align:center"><button id="" name="" class="form-control btn btn-primary">Atender Receta</button></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@include('frontend.includes.footer')
@endsection