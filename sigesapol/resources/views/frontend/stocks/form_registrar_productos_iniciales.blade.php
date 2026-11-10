@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $tipo = $("#frm_carga").find("#id_producto");
    $tipo.select2();
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
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Registrar Productos de la {{$farmacia->nombre}}</h3>
                    <a class="pull-right btn btn-danger btn-xs" href="/cerrar_carga_inicial/{{ Auth::user()->id_farmacia }}/{{ Auth::user()->id }}"><i class="fa fa-close"></i> <b>Cerrar Carga Inicial</i></a>
                </div>
                <div class="panel-body">
                    <form id="frm_carga" method="POST" action="{{ route('add_registrar_productos_iniciales')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12">Fecha de Carga Inicial: <b>{{$datos_carga->fecha_corte}} </b> </div>
                        <div class="col-md-12 etiqueta">                                
                            <label>Producto</label>
                            <select name="id_producto" class="form-control" id="id_producto" required="">
                                <option value="">--Seleccionar--</option>
                                @foreach($productos as $p)
                                <option value="{{$p->id}}">{{$p->codigo}} - {{$p->nombre}} - {{$p->abreviatura}}</option>
                                @endforeach
                            </select>
                            <input value="{{ Auth::user()->id_farmacia }}" class="form-control" name="id_farmacia" type="hidden">
                            <input value="{{ $farmacia->nombre}}" class="form-control" name="nombre_farmacia" type="hidden">
                        </div>
                        <div class="col-md-3 etiqueta">                                
                            <label>Lote</label>
                            <input value="" class="form-control" name="lote" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-md-3 etiqueta">                                
                            <label>Reg. Sanitario</label>
                            <input value="" class="form-control" name="registro_sanitario" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-md-3 etiqueta">                                
                            <label>Fec. vencimiento</label>
                            <input value="" class="form-control datepicker" name="fecha_vencimiento" type="text"> 
                            <input value="{{$datos_carga->fecha_corte}}" name="fecha_ingreso" type="hidden">
                        </div>
                        <div class="col-md-3 etiqueta">                                
                            <label>Cantidad</label>
                            <input value="" class="form-control" name="cantidad" type="text">
                        </div>
                    
                        <div class="col-md-2 etiqueta pull-right">
                            <input class="btn btn-success form-control" value="Registrar" type="submit"> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Productos Farmacéuticos y Dispositivos Médicos</h3>                      
                </div>
                <div class="panel-body">
                    <table class="table" style="font-size:12px">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Codigo</th>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th>R.S.</th>
                                <th>Cantidad</th>
                                <th>F. Vencimiento</th>
                                <th>F. Ingreso</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n=1;?>
                            @foreach($lotes as $lo)
                            <tr>
                                
                                <td><?php echo $n++;?></td>
                                <td>{{$lo->codigo}}</td>
                                <td>{{$lo->nombre}} - {{$lo->unidad}}</td>
                                <td>{{$lo->lote}}</td>
                                <td>{{$lo->registro_sanitario}}</td>
                                <td>{{$lo->cantidad}}</td>
                                <td>
                                    <?php 
                                        $originalDate2 = $lo->fecha_vencimiento;
                                        $fecha_vencimiento = date("d-m-Y", strtotime($originalDate2));
                                        echo $fecha_vencimiento;
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $originalDate3 = $lo->fecha_ingreso;
                                        $fecha_ingreso = date("d-m-Y", strtotime($originalDate3));
                                        echo $fecha_ingreso;
                                    ?>
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection