@extends('frontend.layouts.master')
@section('renderjs')
<script>

</script>
<style>

</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Productos Ingresados</h3>

            </div>
            <div class="panel-body">
                <legend>
                    PECOSA: {{$numero}}
                    <div style="text-align:right">                    
                        <a class="btn btn-default btn-xs" href="/registrar_pecosa/{{ Auth::user()->id_farmacia}}"> Regresar</a><br>
                        Fecha: {{$fecha}} 
                    </div>
                </legend>
                <table class="table">
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Origen</th>
                            <th>Destino</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($pecosas as $pe)
                        <tr>
                            <td><?php $n = $n + 1;
                        echo $n; ?></td>
                            <td>{{$pe->codigo_producto}}</td>
                            <td>{{$pe->nombre_producto}}</td>
                            <td>{{$pe->unidad}}</td>
                            <td>{{$pe->cantidad}}</td>
                            <td>Almacen Central</td>
                            <td>{{$farmacia->nombre}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection