@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $consul = $("#frmReceta").find("#id_consultorio");
    $consul.select2();
    //--------------------------------------------------------------------------
    $(".chosen-select").chosen();
    //--------------------------------------------------------------------------    
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

    .table {
        padding: 8px;
        line-height: 1.428571429;
        vertical-align: top;
        border-top: 1px solid #ddd;
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
                                @role('Dispensador_recetas')
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
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list text-primary"></span>
                                        <a href="/recetas_atendidas/{{ Auth::user()->id }}"> Receta atendidas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-tasks text-primary"></span>
                                        <a href="/resumen_diario/{{ Auth::user()->id }}"> Resumen Diario</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-arrow-left text-primary"></span>
                                        <a href="/stocksbyFamarcia/{{ Auth::user()->id }}"> Ir al Stock</a>
                                    </td>
                                </tr>
                                @endauth
                                @role('Almacenero_recetas')
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}">Recetas atendidas</a>
                                    </td>
                                </tr>
                                @endauth
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">RECETA</div>     
                @if(count($recetas)>0)                                
                <div class="panel-body" style="padding-top:30px; font-size: 13px">   
                    @if(count($medicamentos)>0)
                    <div class="col-md-3">
                        <img src="../../images/logo.png" width="120" height="auto">
                    </div>
                    <div class="col-md-7" style="text-align: center">
                        <h4>{{$recetas[0]->ipress}}</h4>          
                        <!--<label>AMBULATORIO</label>-->
                    </div>
                    <div class="col-md-2">
                        <label>Nro Receta</label>                        
                        <p>{{$recetas[0]->nro_receta}}</p>
                    </div>
                    <div class="col-md-2">
                        <label>DNI</label>
                        <p>{{$recetas[0]->dni_beneficiario}}</p>

                    </div>
                    <div class="col-md-8">
                        <label>NOMBRE Y APELLIDOS</label>                        
                        <p>{{$recetas[0]->nombre_beneficiario}} {{$recetas[0]->paterno_beneficiario}} {{$recetas[0]->materno_beneficiario}}</p>
                    </div>
                    <div class="col-md-2">
                        <label>BENEFICIARIO</label>                        
                        <p>{{$recetas[0]->tipo_beneficiario}}</p>
                    </div>
                    <div class="col-md-12" style="text-align: left; font-size: 18px; margin-top: 15px">
                        <legend>DISPENSAR MEDICAMENTOS</legend>
                    </div>
                    <div class="col-md-6" style="font-size: 9px;">
                        <label>MEDICAMENTOS</label>
                    </div>
                    <div class="col-md-1" style="font-size: 9px">
                        <label>STOCK ACTUAL</label>
                    </div>
                    <div class="col-md-1" style="font-size: 9px">
                        <label>CANT. PRESCRITA</label>
                    </div>
                    <div class="col-md-1" style="font-size: 9px">
                        <label>CANT. DISPENSADA</label>
                    </div>
                    <!--<div class="col-md-1" style="font-size: 9px">
                        <label>FECHA ACTUALIZADA</label>
                    </div>-->
                    <div class="col-md-1" style="font-size: 9px">
                        <label>CANT. NO ATE.</label>
                    </div>
                    <div class="col-md-1" style="font-size: 9px">
                        <label>ENTREGAR CANTIDAD</label>
                    </div>
                    <div class="col-md-1" style="font-size: 9px">
                        <label></label>
                    </div>
                    @foreach($medicamentos as $med)
                    <form class="form-horizontal" method="post" action="{{ route('dispensar_receta')}}" id="frmReceta" style="font-size:10px;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-6" style=" margin-bottom: 5px">
                            <spam>{{$med->nombre}}</spam>
                            <input type="hidden" class="form-control" value="{{$med->id_producto_receta}}"  name="id_producto_receta[]" id="id_producto_receta[]">
                            <input type="hidden" class="form-control" value="{{$med->codigo}}" name="codigo_producto">
                            <input type="hidden" class="form-control" value="{{$med->id_producto}}" name="id_producto[]" id="id_producto[]">
                            <input type="hidden" value="{{$recetas[0]->id_farmacia}}" name="id_farmacia"> 
                            <input type="hidden" value="{{$recetas[0]->id}}" name="id_receta_vale"> 
                            <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                            <input type="hidden" value="{{$recetas[0]->nro_receta}}" name="nro_receta">
                        </div>
                        <div class="col-md-1" style=" margin-bottom: 5px">
                            <!--<input type="text" class="" name="observacion" style="width: 180px">-->
                            @foreach($stocks as $s)
                            @if($s[0]==$med->codigo)
                            <span>{{$s[1]->cantidad}}</span>
                            @endif
                            @endforeach
                        </div>
                        <div class="col-md-1" style=" margin-bottom: 5px">
                            <input type="text" class="" value="{{$med->cantidad_prescrita}}" disabled="" style="width: 50px">
                            <input type="hidden" class="" value="{{$med->cantidad_prescrita}}" name="cantidad_prescrita" id="cantidad_prescrita">
                        </div>
                        <div class="col-md-1" style=" margin-bottom: 5px">
                            <input type="text" class="" value="{{$med->cantidad_dispensada}}" disabled="" style="width: 50px">
                            <input type="hidden" class="" value="{{$med->cantidad_dispensada}}" name="cantidad_dispensada" id="cantidad_dispensada">
                        </div>
                        <?php
                        $originalDate2 = $med->updated_at;
                        $newDate2 = date("d-m-Y", strtotime($originalDate2));
                        ?>                                
                        <!--<div class="col-md-1"  style="margin-bottom: 5px">
                            <input type="text" class="" value="*<?php $newDate2; ?>" disabled="" style="width: 60px" >                                   
                        </div>-->
                        <div class="col-md-1"  style="margin-bottom: 5px" >
                            @if($med->cantidad_prescrita!=$med->saldo)
                            @if($med->saldo>0)
                            <input type="text" class="" value="{{$med->saldo}}" disabled="" style="width: 50px">
                            <input type="hidden" class="" value="{{$med->saldo}}"  name="saldo" id="saldo">
                            @else
                            <label>----</label>
                            @endif
                            @else
                            <label>----</label>
                            @endif
                        </div>
                        <div class="col-md-1"  style="margin-bottom: 5px" >   
                            <!--@if($med->saldo>0) 
                            <input type="text" class="" value="" name="nueva_cantidad" id="nueva_cantidad" style="width: 50px" required="" disabled="">                      
                            @else
                            <input type="text" class="" value="" name="nueva_cantidad" id="nueva_cantidad" style="width: 50px" required="">
                            @endif-->
                            <input type="text" class="" value="" name="nueva_cantidad" id="nueva_cantidad" style="width: 50px" required="">
                        </div>
                        <div class="col-md-1" style="margin-bottom: 5px" >
                            @if($med->saldo>0)                            
                            <input value="+" type="submit" class="btn-info">
                            @else                            
                            <i class="fa fa-check"></i>
                            @endif
                        </div>                                                
                    </form>                    
                    @endforeach
                    <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">
                        <a href="/imprimir_receta/{{$recetas[0]->nro_receta}}/{{$recetas[0]->id_farmacia}}/Hola" class="btn btn-warning pull-right" target="_blank">Generar Ticket</a>
                    </div>
                    @else
                    <div class="col-md-12">
                        No se encuentra el numero de receta que digito.
                    </div>
                    @endif
                </div>
                @else
                <div class="panel-body" style="padding-top:10px; font-size: 13px">
                    No se encuentra el numero de receta que digito.
                </div>
                @endif
            </div>         
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection