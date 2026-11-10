@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .clickable
    {
        cursor: pointer;
    }

    .clickable .glyphicon
    {
        background: rgba(0, 0, 0, 0.15);
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px
    }

    .panel-heading span
    {
        margin-top: -23px;
        font-size: 15px;
        margin-right: -9px;
    }
    a.clickable { color: inherit; }
    a.clickable:hover { text-decoration:none; }
</style>
<script>
    $(document).on('click', '.panel-heading span.clickable', function (e) {
        var $this = $(this);
        if (!$this.hasClass('panel-collapsed')) {
            $this.parent('.panel').children('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');

        } else {
            $this.parent('.panel').children('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
        }
    });

    $(document).on('click', '.panel div.clickable', function (e) {
        var $this = $(this);
        if (!$this.hasClass('panel-collapsed')) {
            $this.parent('.panel').children('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
        } else {
            $this.parent('.panel').children('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
        }
    });

    //--------------------------------------------------------------------------
    $(".chosen-select").chosen();
    //--------------------------------------------------------------------------
     $(document).ready(function () {
        $("#cantidad_atendida").change(function (e) {
            //alert("Estoy aqui");            
            cantidad_solicitada=$("#cantidad_solicitada").val();
            cantidad_atendida=$("#cantidad_atendida").val();
            if(cantidad_atendida>cantidad_solicitada){
                alert("La cantidad atendida no puede ser mayor a la cantidad solicitada");
                 $("#cantidad_atendida").val("");
                 e.preventDefault();
                 }
        });
    });
</script>
@endsection
@section('content')
  
<div class="container"> 
    <div style="margin-bottom: 50px">
        <a class="btn btn-success pull-right" href="/atender_receta/{{ Auth::user()->id }}">
             Buscar nueva receta <i class="fa fa-search"></i>
        </a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading clickable">
                    <h3 class="panel-title"> 
                        Receta: RUA-{{$datos_receta->serie}}-{{$datos_receta->nro_ticket}}
                    </h3>                    
                    <span class="pull-right ">{{$farmacia->nombre}} <i class="glyphicon glyphicon-minus"></i></span>
                </div>
                <div class="panel-body">
                    <!---fila 01----------------------------------------------->
                    <div class="col-md-2"  style="margin-bottom: 10px">
                        <b>DNI:</b> {{$datos_receta->dni_afiliado}}
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px">
                        <b>Paciente:</b> {{$datos_receta->nombre_afiliado}}  {{$datos_receta->paterno_afiliado}} {{$datos_receta->materno_afiliado}}
                    </div>
                    <div class="col-md-3" style="margin-bottom: 10px">
                        <b>Especialidad:</b> {{$datos_receta->consultorio}}
                    </div>
                    <div class="col-md-3" style="margin-bottom: 10px">
                        <b>Servicio:</b> {{$datos_receta->producto}}
                    </div>
                    <!---fila 02----------------------------------------------->
                    <div class="col-md-2">
                        <b>CMP:</b> {{$datos_receta->cmp}}
                    </div>
                    <div class="col-md-4">
                        <b>Médico:</b> {{$datos_receta->nombre_medico}} {{$datos_receta->paterno_medico}}
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading clickable">
                    <h3 class="panel-title">
                        Atender Medicamentos</h3>
                    <span class="pull-right "><i class="glyphicon glyphicon-minus"></i></span>
                </div>
                <div class="panel-body">
                    <form id="frmReceta" action="{{route('medicamento_entregado')}}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="contacts">
                                    <div class="form-group multiple-form-group input-group">
                                        <!--------------------------------------------->
                                        <div class="col-md-7">Medicamentos</div>
                                        <div class="col-md-2">Cantidad solicitada</div>
                                        <div class="col-md-2">Cantidad atendida</div>
                                        <div class="col-md-1">Atender</div>
                                        <!--------------------------------------------->
                                        <div class="col-md-7">
                                            <select name="id_medicamento" id="id_medicamento" class="form-control chosen-select" required="">
                                                <option value="">Seleccionar Medicamento</option>
                                                @foreach($productos as $pro)
                                                @if ($pro->monto_actual==0)
                                                <option value="{{$pro->id_stock_farmacia}}" style="color: red">
                                                    {{$pro->nombre}} - Sin stock ({{$pro->monto_actual}} unidades)
                                                </option>
                                                @else
                                                <option value="{{$pro->id_stock_farmacia}}">
                                                    {{$pro->nombre}} - Con stock ({{$pro->monto_actual}} unidades)
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>  
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="cantidad_solicitada" id="cantidad_solicitada" class="form-control" required="" placeholder="Cantidad Solicitada">                    
                                            <input type="hidden" name="id_receta" id="id_receta" value="{{$datos_receta->id}}">
                                            <input type="hidden" name="id_user" id="id_user" value="{{$datos_receta->id_user}}">
                                            <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$datos_receta->id_farmacia}}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="cantidad_atendida" id="cantidad_atendida" class="form-control"  placeholder="Cantidad Atendida">
                                        </div>
                                        <div class="col-md-1">                                            
                                            <input type="submit" class="btn btn-success btn-add" value="+">
                                        </div>
                                        <!--------------------------------------------->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>        
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading clickable">
                    <h3 class="panel-title">Medicamentos Atendidos</h3>
                    <span class="pull-right "><i class="glyphicon glyphicon-minus"></i></span>
                </div>
                <div class="panel-body">
                    <div class="col-md-3">
                        <label>Medicamento</label><br>                                             
                    </div>
                    <div class="col-md-2">
                        <label>Cantidad Solicitada</label><br>
                    </div>
                    <div class="col-md-2">
                        <label>Cantidad Atendida</label><br>
                    </div>                    
                    <div class="col-md-2">
                        <label>Faltante</label><br>
                    </div>
                    <div class="col-md-2">
                        <label>Fecha de entrega</label><br>
                    </div>
                    <div class="col-md-1">
                        <label>Atender</label><br>                        
                    </div>
                    @foreach($medicamentos as $med)                    
                    <div class="col-md-3" style="margin-top:5px">
                        {{$med->codigo}} - {{$med->nombre}}                                                
                    </div>
                    <div class="col-md-2" style="margin-top:5px">
                        {{$med->solicitados}}
                    </div>
                    <div class="col-md-2" style="margin-top:5px">
                        {{$med->atendidos}} 
                    </div>                    
                    <div class="col-md-2" style="margin-top:5px">
                        <?php
                        $falta = ($med->solicitados) - ($med->atendidos);
                        echo $falta;
                        ?>
                    </div>
                    <div class="col-md-2" style="margin-top:5px">
                        {{$med->created_at}} 
                    </div>
                    <div class="col-md-1"  style="margin-top:5px">
                        <a href="editar_medicamento/{{$med->id_receta_stock}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    </div>                    
                    @endforeach
                </div>
            </div>
        </div>        
    </div>
</div>
@include('frontend.includes.footer')
@endsection