@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $("#btnReg").on("click", function () {               
        if ($('#frmCarga').find("#fecha_corte").val() == "") {
                alert("Falta Seleccionar la Fecha");
                return false;
        }
        var hoy = new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1; //hoy es 0!
        var yyyy = hoy.getFullYear();        
        if(dd<10) {
            dd='0'+dd
        } 
        if(mm<10) {
            mm='0'+mm
        } 
        hoy = dd+'-'+mm+'-'+yyyy;        
        fecha_corte=$('#frmCarga').find("#fecha_corte").val();                        
        var xMonth=fecha_corte.substring(3, 5);                 
        var xDay=fecha_corte.substring(0, 2);  
        var xYear=fecha_corte.substring(6,10);  
        var yMonth=hoy.substring(3, 5);          
        var yDay=hoy.substring(0, 2);  
        var yYear=hoy.substring(6,10);         
        if (xYear> yYear) {  
                alert("La fecha seleccionada no puede ser mayor a la de hoy");
                return false; 
            }  
        else{  
            if (xYear == yYear){   
                if (xMonth> yMonth){  
                    alert("La fecha seleccionada no puede ser mayor a la de hoy");
                    return false;  
                }  
                else{   
                    if (xMonth == yMonth){  
                        if (xDay> yDay){  
                            alert("La fecha seleccionada no puede ser mayor a la de hoy");
                            return false;  
                        }
                    }
                }  
            }
        }
    });
</script>
<style>
    .ancho{width: 85px}
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">REGISTRAR STOCK INICIAL</h3>                    
                </div>
                <div class="panel-body">
                    @if(count($cabecera)>0)
					<?php foreach($cabecera as $cabecer):?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Farmacia</th>
                                <th>Fecha carga inicial</th>
                                <th>Estado</th>               
                                <th>Ver</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$farmacia->nombre}}</td>
                                <td>
                                    <?php
                                        $originalDate2 = $cabecer->fecha_corte;
                                        $fecha = date("d-m-Y", strtotime($originalDate2));
                                        echo $fecha;
                                    ?>
                                </td>
                                <td>
                                    @if($cabecer->estado==1)
                                    <p style="color: green">
                                        <i class="fa fa-folder-open-o"></i> Agregando productos
                                    </p>
                                    @else
                                    <p style="color: red">
                                        <i class="fa fa-key"></i> Carga completa y finalizada
                                    </p>
                                    @endif
                                </td>
                                <td>
                                    @if($establecimiento->nivel==0)
                                        @if($id_farmacia!=46)
                                            <a href="#" class="btn btn-danger btn-xs"> {{$establecimiento->nombre}} no es una IPRESS</a>
                                        @else
                                            <a href="/get_insert_cabecera_carga_inicial/{{ Auth::user()->id_farmacia }}/{{$cabecer->id}}" class="btn btn-primary btn-xs"> Ver</a>
                                        @endif
                                    @else
                                    <a href="/get_insert_cabecera_carga_inicial/{{ Auth::user()->id_farmacia }}/{{$cabecer->id}}" class="btn btn-primary btn-xs"> Ver</a>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
					<?php endforeach;?>
                    @else
                    <form id="frmCarga" method="POST" action="{{ route('insert_cabecera_carga_inicial')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-8 etiqueta">                                
                            <label>Farmacia</label>
                            <input value="{{$farmacia->nombre}}" class="form-control" name="nombre_farmacia" type="text" readonly="">
                            <input value="{{ Auth::user()->id_farmacia }}" class="form-control" name="id_farmacia" type="hidden">
                            <input value="{{ Auth::user()->id }}" class="form-control" name="id_user" type="hidden">
                        </div>
                        <div class="col-md-4 etiqueta">                                
                            <label>Fecha Carga Inicial</label>
                            <input value="" class="form-control datepicker" name="fecha_corte" id="fecha_corte" type="text" autocomplete="off">
                            <input value="{{$fecha_hoy}}" class="" name="fecha_hoy" id="fecha_hoy" type="hidden">
                        </div>
                        <div class="col-md-4 etiqueta pull-right">
                            <input class="btn btn-success form-control" value="Registrar" type="submit" id="btnReg">
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection