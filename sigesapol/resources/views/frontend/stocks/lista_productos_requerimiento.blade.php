@extends('frontend.layouts.master')
@section('renderjs')

<style>
@media print{

      .oculto-impresion, .oculto-impresion *{

        display: none !important;

      }
      .invoice {
            position: relative;
            background: #fff;
            border: 1px solid #f4f4f4;
            padding: 20px;
            margin: 10px 25px;
        }
        
        .invoice-title{margin-top:0}
        
        .invoice-col{float:left;width:33.3333333%}

        .address {
            margin-bottom: 20px;
            font-style: normal;
            line-height: 1.42857143;
        }

    }
</style>

@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-10">
            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$farmacia->nombre}}</h3>
                    <div class="pull-right oculto-impresion">                        
                        <a class="btb btn-default btn-xs" href="/all_requerimientos_recibidos">     
                            <i class="fa fa-arrow-left"></i> Ver requerimientos
                        </a>
                    </div>
                </div>
                <div class="panel-body invoice">
                    <div class="col-sm-12 ">
                        <label class="text-left">Destino: {{$requerimiento->nom_destino}}</label>
                    </div>
                    <div class="col-md-3 invoice-col" style="margin-top:10px">
                        <label>Número:<br>{{$requerimiento->contador}}-{{$requerimiento->anio_reque}}</label>
                        <input value="{{$requerimiento->numero}}" class="form-control" name="numeo"type="hidden">
                    </div>                        
                    <div class="col-md-5 invoice-col" style="margin-top:10px">
                        <label>Documento:<br>{{$requerimiento->documento}}</label>
                    </div>  
                    <div class="col-md-2 invoice-col" style="margin-top:10px">
                        <label>Creado: <br>
                            <?php
                            $originalDate2 = $requerimiento->created_at;
                            $fecha_creacion = date("d-m-Y", strtotime($originalDate2));
                            echo $fecha_creacion
                            ?> 
                        </label>
                    </div>
                    <div class="col-md-12 oculto-impresion" style="margin-top:30px;">
                        <legend>
                            <samp style="text-transform: uppercase; font-size: 15px">Lista de {{$tipo_producto->nombre}}</samp>
                        </legend>
                    </div> 
                    <table class="table" style="font-size:12px">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 0; $total=0;?>
                            @foreach($productosbyrequerimientos as $producto)
                            <tr>
                                <td><?php $n = $n + 1;
                            echo $n;
                            ?></td>
                                <td>
                                    {{$producto->codigo_producto}} - {{$producto->nombre}} - {{$producto->abreviatura}}
                                    @if($producto->codigo_producto=='59075700')
                                        ({{$producto->cantidad*1000}} L)
                                    @endif
                                    <input value="{{$producto->codigo_producto}}" type="hidden" name="codigo_producto[]">
                                </td>
                                <td align="right">
                                    {{$producto->cantidad}}
                                    <input value="{{$producto->cantidad}}" type="hidden" name="cantidad[]">
                                </td>
                            </tr>                            
                            <?php $total=$producto->cantidad+$total;?>
                            @endforeach
                            <tr><td colspan="2" align="right"><strong>TOTAL:</strong></td><td  align="right"><?php echo $total;?></td><td colspan="2"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>       
    </div>
</div>
@include('frontend.includes.footer')
@endsection
