@extends('frontend.layouts.master')
@section('renderjs')

<style>

</style>

<script>

</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-10 pull-right">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Editar datos del Lote: {{$lote->lote}} ({{$lote->nombre_producto}})</h3>
                    <div class="pull-right">                        
                        <a class="btb btn-default btn-xs" href="/asignar_lote_vencimiento/{{$lote->id_stock_farmacia}}">
                            <i class="fa fa-arrow-left"></i> Ver Lote(s) del producto
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="post" action="{{ route('update_lote')}}" id="frmlotizar">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-2" style="margin-top:10px">
                            <label>Código</label>
                            <input value="{{$lote->codigo_producto}}" class="form-control" readonly="">
                            <input value="{{$lote->id_lote}}" class="form-control" type="hidden" name="id_lote">
                            <input value="{{$lote->id_farmacia}}" class="form-control" type="hidden" name="id_farmacia">
                            <input value="{{$lote->id_stock_farmacia}}" class="form-control" type="hidden" name="id_stock_farmacia">
                        </div>
                        <div class="col-md-8" style="margin-top:10px">
                            <label>Producto</label>
                            <input value="{{$lote->nombre_producto}}" class="form-control" readonly="">
                        </div>
                        <div class="col-md-2" style="margin-top:10px">
                            <label>Unidad</label>
                            <input value="{{$lote->unidad}}" class="form-control" readonly="">
                        </div>
                        <div class="col-md-3" style="margin-top:10px">
                            <label>Cantidad</label>
                            <input value="{{$lote->cantidad_lote}}" class="form-control" readonly="">
                        </div>
                        <div class="col-md-3" style="margin-top:10px">
                            <label>Lote</label>
                            <input value="{{$lote->lote}}" class="form-control" type="text" name="lote">
                        </div>
                        <div class="col-md-3" style="margin-top:10px">
                            <label>Registro Sanitario</label>
                            <input value="{{$lote->registro_sanitario}}" class="form-control" type="text" name="registro_sanitario">
                        </div>
                        <div class="col-md-3" style="margin-top:10px">
                            <label>Fecha de vencimiento</label>
                            <?php
                            $originalDate2 = $lote->fecha_vencimiento;
                            $fecha = date("d-m-Y", strtotime($originalDate2));                            
                            ?>
                            <input value="<?php echo $fecha;?>" class="form-control datepicker" type="text" name="fecha_vencimiento">
                        </div>
                        <div class="col-md-3 pull-right" style="margin-top:10px">
                            <input class="btn btn-primary form-control" value="Actualizar" type="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>       
    </div>
</div>
@include('frontend.includes.footer')
@endsection
