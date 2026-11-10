@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $consul = $("#frmReceta").find("#id_consultorio");
    $consul.select2();
    //--------------------------------------------------------------------------
    $(".chosen-select").chosen();
    //--------------------------------------------------------------------------    
    $(document).ready(function () {
        $("#nueva_cantidad").change(function (e) {
            //alert("Estoy aqui");            
            saldo = $("#saldo").val();
            nueva_cantidad = $("#nueva_cantidad").val();
            if (nueva_cantidad > saldo) {
                alert("La nueva entrega no puede ser mayor al saldo actual");
                $("#nueva_cantidad").val("");
                e.preventDefault();
            }
        });
    });
</script>
<style>
    .search-form .form-group {
        float: left !important;
        transition: all 0.35s, border-radius 0s;
        width: 32px;
        height: 32px;
        background-color: #fff;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        border-radius: 25px;
        border: 1px solid #ccc;
        margin-left: 0px;
    }
    .search-form .form-group input.form-control {
        padding-right: 20px;
        border: 0 none;
        background: transparent;
        box-shadow: none;
        display:block;
    }
    .search-form .form-group input.form-control::-webkit-input-placeholder {
        display: none;
    }
    .search-form .form-group input.form-control:-moz-placeholder {
        /* Firefox 18- */
        display: none;
    }
    .search-form .form-group input.form-control::-moz-placeholder {
        /* Firefox 19+ */
        display: none;
    }
    .search-form .form-group input.form-control:-ms-input-placeholder {
        display: none;
    }
    .search-form .form-group:hover,
    .search-form .form-group.hover {
        width: 60px;
        border-radius: 4px 25px 25px 4px;
    }
    .search-form .form-group span.form-control-feedback {
        position: absolute;
        top: -1px;
        right: -2px;
        z-index: 2;
        display: block;
        width: 34px;
        height: 34px;
        line-height: 34px;
        text-align: center;
        color: #3596e0;
        left: initial;
        font-size: 14px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
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
                                        <span class="glyphicon glyphicon-plus text-primary"></span><a href="/nueva_receta/{{ Auth::user()->id }}">Nueva Receta</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/buscar_receta/{{ Auth::user()->id }}"> Buscar Receta</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-9">
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">DISPENSAR RECETAS</h3>
                    </div>
                    <div style="margin-left: 10px; margin-bottom: 20px">
                        <form class="form-horizontal" method="post" action="{{ route('dispensar_receta')}}" id="frmReceta">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <img src="../../images/logo.png" width="180" height="auto">
                                </div>
                                <div class="col-md-7" style="text-align: center">
                                    <label>{{$recetas[0]->ipress}}</label><br>
                                    <label style="font-size: 24px">RECETA - VALE</label><br>
                                    <label>AMBULATORIO</label>
                                </div>
                                <div class="col-md-2">
                                    <label>N°</label>
                                    <input type="text" class="form-control" value="{{$recetas[0]->nro_receta}}" name="nro_receta" autofocus="" tabindex="1" disabled="">  
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>DNI</label>
                                    <input value="{{$recetas[0]->dni_beneficiario}}" class="form-control" disabled="">
                                </div>
                                <div class="col-md-5">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <input type="text" class="form-control" value="{{$recetas[0]->nombre_beneficiario}} {{$recetas[0]->paterno_beneficiario}}, {{$recetas[0]->materno_beneficiario}}" name="paterno_beneficiario" disabled="">
                                </div>
                                <div class="col-md-3">
                                    <label>TIPO BENEFICIARIO</label>
                                    <input value="{{$recetas[0]->tipo_beneficiario}}" class="form-control" disabled="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align: left; font-size: 18px">
                                    DISPENSAR MEDICAMENTOS
                                </div>
                            </div>
                            <div class="form-group" style="text-align: left; font-size: 10px">
                                <div class="col-md-4">
                                    <label>MEDICAMENTOS</label>
                                </div>
                                <!--<div class="col-md-1">
                                    <label>VER STOCK</label>
                                </div>-->
                                <div class="col-md-1">
                                    <label>CANTIDAD PRESCRITA</label>
                                </div>
                                <div class="col-md-1">
                                    <label>CANTIDAD DISPENSADA</label>
                                </div>
                                <div class="col-md-2">
                                    <label>FECHA ULTIMA ACTUALIZACION</label>
                                </div>
                                <div class="col-md-1">
                                    <label>SALDO</label>
                                </div>
                                <div class="col-md-1">
                                    <label>NUEVA ENTREGA</label>
                                </div>
                                <div class="col-md-1">
                                    <label>ENTREGAR</label>
                                </div>
                            </div>
                            @foreach($medicamentos as $med)
                            <div class="form-group">
                                <div class="col-md-4">                                    
                                    <!--<input type="hidden" class="form-control" value="{{$med->id_producto}}" disabled="" name="id_producto[]">-->
                                    <input type="hidden" class="form-control" value="{{$med->id_producto_receta}}"  name="id_producto_receta[]" id="id_producto_receta[]">
                                    <input type="text" class="form-control" value="{{$med->nombre}}" disabled="">
                                    <input type="text" class="form-control" value="{{$med->codigo}}" disabled="">
                                    <input type="hidden" class="form-control" value="{{$med->id_producto}}" name="id_producto[]" id="id_producto[]">
                                    <input type="hidden" value="{{$recetas[0]->id_farmacia}}" name="id_farmacia"> 
                                    <input type="hidden" value="{{$recetas[0]->id}}" name="id_receta_vale"> 
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                </div>
                                <!--<div class="col-md-1 search-form">
                                    <div class="form-group has-feedback">
                                        <label for="search" class="sr-only">Search</label>
                                        <input type="text" class="form-control" name="search" id="search" placeholder="" disabled="">
                                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                    </div>
                                </div>-->
                                <div class="col-md-1">
                                    <input type="text" class="form-control" value="{{$med->cantidad_prescrita}}" disabled="">
                                    <input type="hidden" class="form-control" value="{{$med->cantidad_prescrita}}" name="cantidad_prescrita[]" id="cantidad_prescrita[]">
                                </div>
                                <div class="col-md-1">
                                    <input type="text" class="form-control" value="{{$med->cantidad_dispensada}}" disabled="" >
                                    <input type="hidden" class="form-control" value="{{$med->cantidad_dispensada}}" name="cantidad_dispensada[]" id="cantidad_dispensada[]">
                                </div>                                
                                <?php
                                $originalDate2 = $med->updated_at;
                                $newDate2 = date("d-m-Y", strtotime($originalDate2));
                                ?>                                
                                <div class="col-md-2">
                                    <input type="text" class="form-control" value="<?php echo $newDate2; ?>" disabled="" >                                   
                                </div>

                                <div class="col-md-1">
                                    <input type="text" class="form-control" value="{{$med->saldo}}" disabled="" >
                                    <input type="hidden" class="form-control" value="{{$med->saldo}}"  name="saldo[]" id="saldo[]">
                                </div>
                                <div class="col-md-1">
                                    <input type="text" class="form-control" value="" name="nueva_cantidad[]" id="nueva_cantidad[]">
                                </div>
                                <div class="col-md-1">
                                    <input type="submit" value="+" class="form-control btn btn-success">
                                </div>
                            </div>
                            <!-- line modal -->
                            @endforeach
                            <div class="form-group">
                                <div class="col-md-2">
                                    <a><i class="fa fa-plus"></i> Observaciones</a>
                                </div>
                            </div>
                            <div class="form-group" style="border: 1px solid #006699; margin-right: 20px; margin-left: 15px; padding-top: 10px; padding-bottom: 10px; border-radius: 4px;">
                                <div class="col-md-6">
                                    <a href="/nueva_receta/{{ Auth::user()->id }}" class="form-control btn btn-danger" tabindex="18">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <!--<a href="/imprimir_receta/{{$recetas[0]->nro_receta}}/{{$recetas[0]->id_farmacia}}" class="form-control btn btn-warning" tabindex="17" target="_blank">
                                        IMPRIMIR <i class="fa fa-print"></i>
                                    </a>-->
                                    <div class="center">
                                        <a data-toggle="modal" data-target="#squarespaceModal" class="btn btn-warning center-block">
                                            IMPRIMIR <i class="fa fa-print"></i>
                                        </a>
                                    </div>
                                </div>
                                <!--<div class="col-md-4">
                                    <input class="form-control btn btn-success" type="submit" value="ATENDER" tabindex="16">
                                </div>-->
                            </div>
                            <!-- line modal -->

                        </form>
                        <div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                            <h3 class="modal-title" id="lineModalLabel">Observación</h3>
                                        </div>
                                        <div class="modal-body">                                        
                                            <div class="form-group">                                                
                                                <textarea class="form-control" name="observacion"></textarea>
                                                <input type="hidden" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                                                <input type="hidden" name="id_farmacia" value="{{$recetas[0]->id_farmacia}}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"  role="button">Cerrar</button>
                                                </div>
                                                <div class="btn-group btn-delete hidden" role="group">
                                                    <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <a href="/imprimir_receta/{{$recetas[0]->nro_receta}}/{{$recetas[0]->id_farmacia}}/observacion" class="form-control btn btn-warning" tabindex="17" target="_blank">
                                                        IMPRIMIR <i class="fa fa-print"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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