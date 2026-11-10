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
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Atender Receta <b> RUA-{{$atencion[0]->serie}}-{{$atencion[0]->nro_ticket}}</b></h3>
        </div>

        <div class="tabbable-panel">
            <div class="tabbable-line" style="border: 1px solid #B5B5B5; background-color: #F9F9F9">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_default_1">
                        <form action="{{ route('add_datos_receta')}}" method="post" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <table class="table table-striped" style="width:100%">
                                <tr>
                                    <td>
                                        <table align="center" class="table table-bordered">      
                                            <caption>Datos del Beneficiario</caption>
                                            <tr>
                                                <td>DNI</td>
                                                <td>
                                                    <input id="dni_afiliado" name="dni_afiliado" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->dni}}">
                                                    <input id="nro_historia" name="nro_historia" value="{{$atencion[0]->nro_historia}}" type="hidden">
                                                    <input id="serie" name="serie" value="{{$atencion[0]->serie}}" type="hidden">
                                                    <input id="nro_ticket" name="nro_ticket" value="{{$atencion[0]->nro_ticket}}" type="hidden">
                                                </td>
                                                <td>Nombre(s) y Apellidos</td>
                                                <td colspan="7">
                                                    <input  id="" name="" type="text" class="form-control input-medium" required="" value="{{$atencion[0]->nombre}} {{$atencion[0]->ape_paterno}} {{$atencion[0]->ape_materno}}">
                                                    <input  id="nombre_afiliado" name="nombre_afiliado" type="hidden" class="form-control input-medium" value="{{$atencion[0]->nombre}}">
                                                    <input  id="ape_paterno" name="ape_paterno" type="hidden" value="{{$atencion[0]->ape_paterno}}">
                                                    <input  id="ape_materno" name="ape_materno" type="hidden" value="{{$atencion[0]->ape_materno}}">
                                                    <input  id="id_farmacia" name="id_farmacia" type="hidden"  value="{{$id_farmacia}}">
                                                    <input  id="id_user" name="id_user" type="hidden"  value="{{ Auth::user()->id }}">
                                                </td>                                                    
                                            </tr>
                                            <tr>
                                                <td>Sexo</td>
                                                <td><input id="sexo" name="sexo" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->sexo}}"disabled=""></td>
                                                <td>Fecha de Nacimiento-(Edad)</td>
                                                <td>
                                                    <input id="edad" name="edad" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->fecha_nac}} - ({{$atencion[0]->edad}})" disabled="">
                                                    <input  id="fecha_nac" name="fecha_nac" type="hidden" value="{{$atencion[0]->fecha_nac}}">
                                                    <input  id="edad" name="edad" type="hidden" value="{{$atencion[0]->edad}}">
                                                </td>                                                
                                                <td>Email</td>
                                                <td><input id="e_mail" name="e_mail" type="text"class="form-control input-medium"></td>
                                                <td><a class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
                                                <td>Celular</td>
                                                <td><input id="telefono" name="telefono" type="text"class="form-control input-medium"></td>   
                                                <td><a class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
                                            </tr>
                                        </table>
                                        <table align="center" class="table table-bordered">      
                                            <caption>Datos del Médico</caption>
                                            <tr>
                                                <td>CMP</td>
                                                <td><input id="cmp" name="cmp" type="text"class="form-control input-medium" value="{{$atencion[0]->cmp}}"></td>
                                                <td>Nombre(s)</td>
                                                <td><input id="nombres_medico" name="nombres_medico" type="text"class="form-control input-medium"  value="{{$atencion[0]->nombres_medico}}"></td>
                                                <td>Apellidos</td>
                                                <td colspan="3"><input id="ape_paterno_medico" name="ape_paterno_medico" type="text"class="form-control input-medium" value="{{$atencion[0]->ape_paterno_medico}}"></td>

                                            </tr>
                                        </table>
                                        <table align="center" class="table table-bordered">      
                                            <caption>Datos de la Receta</caption>
                                            <tr>
                                                <td>Especialidad</td>
                                                <td>                                                        
                                                    <input id="id_consultorio" name="id_consultorio" type="hidden" required="">
                                                    <input id="especialidad" name="especialidad" type="text"class="form-control input-medium" required="" value="{{$atencion[0]->especialidad}}" >
                                                </td>
                                                <td>Servicio</td>
                                                <td>
                                                    <input id="id_producto" name="id_producto" type="hidden" required="">
                                                    <input id="descripcion" name="descripcion" type="text"class="form-control input-medium" required="" value="{{$servicio[0]->descripcion}}" >
                                                </td>                                                    
                                            </tr>
                                        </table>

                                        <table align="center" class="table table-bordered">      
                                            <tr>
                                                <td style="text-align:center">
                                                    <input type="submit" class="form-control btn btn-success" value="Guardar datos y Entregar Medicamentos">
                                                </td>
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
@include('frontend.includes.footer')
@endsection