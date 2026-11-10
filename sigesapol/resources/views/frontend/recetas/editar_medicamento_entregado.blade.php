@extends('frontend.layouts.master')
@section('renderjs')
<style>

</style>
<script>
    $(document).ready(function () {
        $("#asigando").change(function (e) {
            //alert("Estoy aqui");            
            cantidad_faltante=$("#faltante").val();
            cantidad_asiganada=$("#asigando").val();
            if(cantidad_asiganada>cantidad_faltante){
                alert("La cantidad asignada no puede ser mayor a la cantidad faltante");
                 $("#asigando").val("");
                 e.preventDefault();
                 }
        });
    });
</script>
@endsection
@section('content')
<div class="container">   
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Receta: RUA-{{$medicamentos->serie}}-{{$medicamentos->nro_ticket}}</h3>
        </div>
        <div class="panel-body">
            <div class="row ">
                <div class="col-md-12" style="border-left:1px solid #ccc;height:230px">
                    <form id="" action="{{route('update_medicamento_atendido')}}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <fieldset>
                            <!------------------------------------------------>
                            <input id="solicitado" name="solicitado" type="hidden" placeholder="" value="{{$medicamentos->solicitados}}">
                            <input id="atendido" name="atendido" type="hidden" value="{{$medicamentos->atendidos}}">                            
                            <input id="id_receta_stock" name="id_receta_stock" type="hidden" value="{{$medicamentos->id_receta_stock}}">  
                            <!------------------------------------------------>
                            <div class="col-md-12">
                                <label>Medicamento</label>
                                <input disabled="" id="" name="" type="text" placeholder="" value="{{$medicamentos->codigo}} - {{$medicamentos->nombre}} - Cantidad en stock ({{$medicamentos->monto_actual}}) unidades" class="form-control input-md">
                            </div>
                            <div class="col-md-6" style="margin-top:10px">
                                <label>Cantidad Solicitad</label>
                                <input id="cantidad_solicitada" name="cantidad_solicitada" type="text" value="{{$medicamentos->solicitados}}" class="form-control input-md" disabled="">
                            </div>
                            <div class="col-md-6" style="margin-top:10px">
                                <label>Cantidad Atendida</label>
                                <input id="" name="" type="text" class="form-control input-md" value="{{$medicamentos->atendidos}}" disabled="">
                            </div>                            
                            <div class="col-md-6" style="margin-top:10px">
                                <label>Cantidad Faltante</label>
                                <?php $falta=$medicamentos->solicitados-$medicamentos->atendidos; ?>
                                <input id="" name="" type="text"  class="form-control input-md" value="<?php echo $falta ?>" disabled="">
                                <input id="faltante" name="faltante" type="hidden" value="<?php echo $falta ?>">
                            </div>
                            @if(($medicamentos->solicitados==$medicamentos->atendidos))
                            <div class="col-md-6" style="margin-top:10px">
                                <label>Asignar nueva cantidad</label>
                                <input id="asigando" name="asigando" type="text" placeholder="" class="form-control input-md" disabled="">
                            </div> 
                            <div class="col-md-12" style="margin-top:10px">
                                <input value="Actualizar" class="btn btn-success pull-right" type="submit" disabled="">
                            </div>
                            @else
                            <div class="col-md-6" style="margin-top:10px">
                                <label>Asignar nueva cantidad</label>
                                <input id="asigando" name="asigando" type="text" placeholder="" class="form-control input-md" required="">
                            </div>                            
                            <div class="col-md-12" style="margin-top:10px">
                                <input value="Actualizar" class="btn btn-success pull-right" type="submit">
                            </div>
                            @endif                            
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection