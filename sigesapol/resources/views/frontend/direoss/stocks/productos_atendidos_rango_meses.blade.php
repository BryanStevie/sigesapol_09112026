@extends('frontend.layouts.master')
@section('renderjs')
<style>

</style>

<script>
$(document).ready(function () {
        $('#example').DataTable();
    });
</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">MESES</h3>                    
                </div>
                <div class="panel-body">
                    <table class="table" id="example" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>CODIGO</th>
                                <th>PRODUCTO</th>
                                <?php
                                    $valores = array();
                                    foreach($mes_anio as $m_a){
                                        $meses_anios = explode('_',$m_a);
                                        $valores[$m_a] = 0;
                                    ?>
                                     <th> <?php echo $meses[$meses_anios[0]].' - '.$meses_anios[1];?></th>
                                    <?php
                                    }
                                    ?>
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                         <?php
                          
                           $productoGeneral = array();
                           foreach ($productos as $producto) {
                               if(!array_key_exists($producto->codigo_producto, $productoGeneral))
                               {
                                   $productoGeneral[$producto->codigo_producto] = array('producto' =>$producto,'valores'=>$valores,$producto->codigo_producto);
                                   
                               }
                               $productoGeneral[$producto->codigo_producto]['valores'][$producto->fecha_anio] = $producto->total;
                                                           
                           }
                          
                         ?>
                        <tbody>
                             <?php
                              $fila = 1;
                             foreach ($productoGeneral as $productoItem) {
                                 $total = 0;
                                  ?>
                             
                           <tr>                              
                                <td><?php echo $fila ?></td>
                                <td><?php echo $productoItem['producto']->codigo_producto ?></td>
                                <td><?php echo $productoItem['producto']->nombre_producto ?></td>
                                <?php
                                     foreach ($productoItem['valores'] as $valores) {
                                         $total += floatval($valores);
                                        ?>
                                        <td><?php echo $valores ?></td>
                                 <?php
                                     }
                                ?>
                                <td><?php echo $total ?></td>
                            </tr>
                            <?php
                            $fila += 1;
                            }
                          
                         ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>       
    </div>
</div>

@include('frontend.includes.footer')
@endsection
