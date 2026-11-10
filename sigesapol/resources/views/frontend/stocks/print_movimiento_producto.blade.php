<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MOVIMIENTO DE PRODUCTOS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/pdf.css"  />
        <style>
            

            .td_left{
                text-align: left !important;
            }
            .td_ancho_n{
                width: 4%;
                text-align: center
            }
            .cantidad{
                width: 11%;
            }
            .td_ancho_espacios{
                width: 5%;
            }
            .td_ancho_codigo{
                width: 10%;
            } 
            .td_ancho_especialidad{
                width: 25%;
            }
            td{
                padding-left: 2px !important;
            }
            table{
                font-size: 10px !important; 
                font-family: Roboto,"Helvetica Neue",Helvetica,Arial,sans-serif;
            }
            .ancho_producto{
                width: 45%;
            }
            .titulos{
                font-weight: bold;
                padding:0px 10px;
            }
            h2{
                font-size: 15px !important
            }
            .titulo_principal{
                 width: 60%;
            }
            .logo{
                 width: 15%;
            }
            .grado{
                width: 14%;
            }
            .dni{
                width: 8%;
            }
            .rectangulo{
                padding:3px
            }
            footer{
                margin-bottom:0px;
                padding:0px;
                position: fixed;
                height:60px;
                line-height:60px;
                text-align:left;
                border:0px;
                margin-left:10px;
                font-size: 7px !important;
                font-family:Arial, Helvetica, sans-serif
            }
        </style>
    </head>
    <body style="font-size: 10px; font-family: arial;padding:10px">
        @if($id_producto!='')
            @if(count($ingresos_compra)>0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><p>N</p></th>                            
                            <th><p>F. Orden</p></th>
                            <th><p>F. Registro</p></th>
                            <th><p>Nro Orden</p></th> 
                            <th><p>Proveedor</p></th> 
                            <th><p>Cod</p></th>                            
                            <th><p>Producto</p></th>                            
                            <th><p>Total Entrega</p></th> 
                            <th><p>Cant. Girada</p></th>
                            <th><p>Precio Unit.</p></th>
                            <th><p>Total Orden</p></th>                                                     
                        </tr>
                    </thead>
                    <tbody>                            
                        <?php $n = 0; ?>
                        @foreach($ordenes_compra as $r)
                        <?php 
                            if($r->pc!=0)
                                $descripcion =  $r->nombre_producto.' ('.$r->total_cantidad.' X '.$r->presentacion_comercial.') ';
                            else
                                $descripcion =  $r->nombre_producto;
                        ?>
                       
                        <tr>
                            <td><?php
                                $n++; echo $n; ?>                            
                            </td>
                            <td><?php
                                $originalRec = $r->fecha_orden;
                                $fechao = date("d-m-Y", strtotime($originalRec));
                                echo $fechao;
                                ?>                                    
                                </td>
                            <td>
                                <?php
                                $originalDate = $r->fecha_registro;
                                $fecha = date("d-m-Y", strtotime($originalDate));
                                echo $fecha;
                                ?> 
                            </td>
                            <td><?php
                                echo $r->num_orden; ?>                            
                            </td>
                            <td>{{$r->proveedor}}</td>                            
                            <td>{{$r->cod_producto}}</td>
                            <td>{{$descripcion}}</td>
                            <td>{{$r->total_entrega}}</td>
                            <td>{{$r->total_cantidad*$r->presentacion_comercial}}</td>
                            <td>{{$r->precio_compra}}</td>                                    
                            <td>{{$r->total_orden}}</td>
                        </tr>
                        @endforeach                                                 
                    </tbody>
                </table>   
            @else
                No hay datos de ingreso de compras
            @endif 
        @endif 
        <br/>
        <br/>
        <br/>
        <hr>
        <?php $n = 0; $cantidad_total=0; $total=0;?>
        @if($id_producto!='')
            @if(count($ingresos_compra)>0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><p>N</p></th>
                            <th><p>Numero</p></th> 
                            <th><p>F. Intern.</p></th>                                
                            <th><p>Nro Orden</p></th> 
                            <th><p>Nro Entrega</p></th>                                 
                            <th><p>Proveedor</p></th> 
                            <th><p>Codigo</p></th> 
                            <th><p>Producto</p></th> 
                            <th><p>Lote</p></th> 
                            <th><p>R.Sanitario</p></th> 
                            <th><p>F. Vence</p></th> 
                            <th><p>Cant Internada</p></th>
                            <th><p>Precio Unit.</p></th>
                            <th><p>Total Orden</p></th>                                                     
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingresos_compra as $c)
                        <?php 
                            if($r->pc!=0)
                                $descripcion =  $c->nombre_producto.' ('.$c->cantidad.' X '.$c->presentacion_comercial.') ';
                            else
                                $descripcion =  $c->nombre_producto;
                        ?>
                        <tr>
                            <td><?php
                                $n++; echo $n; ?>                            
                            </td>
                            <td><?php
                                echo $c->numero; ?>                            
                            </td>
                            <td>
                                <?php
                                $originalDate = $c->fecha_ingreso;
                                $fecha = date("d-m-Y", strtotime($originalDate));
                                echo $fecha;
                                ?> 
                            </td>
                            <td><?php
                                echo $c->num_orden; ?>                            
                            </td>
                            <td>{{$c->nro_entrega}}</td>   
                            <td>{{$c->proveedor}}</td>                            
                            <td>{{$c->cod_producto}}</td>
                            <td>{{$descripcion}}</td>
                            <td>{{$c->lote}}</td>
                            <td>{{$c->registro_sanitario}}</td>   
                            <td>
                                <?php
                                $originalDate = $c->fecha_vencimiento;
                                $fechav = date("d-m-Y", strtotime($originalDate));
                                echo $fechav;
                                ?> 
                            </td>                         
                            <td><?php $subcantidadtotal=$c->cantidad*$c->presentacion_comercial;
                                      $cantidad_total += $subcantidadtotal;
                                      echo $subcantidadtotal;
                                ?>                                
                            </td>                                 
                            <td>{{$c->precio_promedio}}</td>                            
                            <td><?php $subtotal=$c->cantidad*$c->precio_promedio; 
                                      $total += $subtotal;
                                      echo $subtotal;
                            ?></td> 
                        </tr>
                        @endforeach                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="11"><strong>TOTAL</strong></td>
                            <td><b><?php echo $cantidad_total; ?></b></td>
                            <td></td>
                            <td><b><?php echo number_format($total, 6, '.', ''); ?></b></td>                            
                        </tr>
                    </tfoot>
                </table>
            @else
                No hay datos detalles de ingreso de compras
            @endif
        @endif
        <br/>
        <br/>
        <br/>
        <hr>
        <?php $n2 = 0; $cantidad_total2=0; $total2=0;$subtotal2=0;$canttotal=0;?>          
        @if($id_producto!='')
            @if(count($ingresos_varios)>0)
                <table class="table table-hover">
                    <thead>
                        <tr>                            
                            <th><p>N</p></th>                            
                            <th><p>Numero</p></th> 
                            <th><p>F. Ingreso</p></th>
                            <th><p>Concepto</p></th> 
                            <th><p>Origen</p></th>
                            <th><p>Codigo</p></th>                            
                            <th><p>Producto</p></th>  
                            <th><p>Lote</p></th> 
                            <th><p>R. Sanitario</p></th> 
                            <th><p>F. Vence</p></th>
                            <th><p>Cantidad</p></th>
                            <th><p>Precio</p></th>
                            <th><p>Total</p></th>                                                     
                        </tr>
                    </thead>
                    <tbody>                   
                        <?php $n2 = 0; ?>
                        @foreach($ingresos_varios as $r)
                        <?php 
                            if($r->pc!=0)
                                $descripcion =  $r->nombre_producto.' ('.$r->cantidad.' X '.$r->presentacion_comercial.') ';
                            else
                                $descripcion =  $r->nombre_producto;
                        ?>
                        <tr>
                            <td><?php
                                $n2++; echo $n2; ?>                            
                            </td>
                            <td><?php
                                echo $r->numero; ?>                            
                            </td>
                            <td>
                                <?php
                                $originalDate = $r->fecha_entrada;
                                $fecha = date("d-m-Y", strtotime($originalDate));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>{{$r->conceptos}}</td>
                            <?php 
                            if($r->tipo_concepto==11){
                                  $lugar=$r->lugar_origen_ipress;
                              }
                              else
                              {   if($r->tipo_concepto==7 || $r->tipo_concepto==8){
                                      $lugar=$r->lugar_origen_prov;
                                  }
                                  else
                                  {
                                      $lugar=$r->lugar_origen_dep;
                                  }
                              }
                              ?>                                                     
                            <td>{{$lugar}}</td>
                            <td>{{$r->cod_producto}}</td>
                            <td>{{$descripcion}}</td>    
                            <td>{{$r->lote}}</td>
                            <td>{{$r->registro_sanitario}}</td>
                            <td>
                                <?php
                                $originalDate = $r->fecha_vencimiento;
                                $fechav = date("d-m-Y", strtotime($originalDate));
                                echo $fechav;
                                ?> 
                            </td>                
                            <td>{{$r->cantidad*$r->presentacion_comercial}}</td>
                            <td>{{$r->p_unitario}}</td>                            
                            <td><?php $subtotal2=$r->cantidad*$r->p_unitario*$r->presentacion_comercial; 
                                      $total2 += $subtotal2;
                                      $canttotal+=$r->cantidad*$r->presentacion_comercial;
                                      echo $subtotal2;
                            ?></td>                            
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10"><strong>TOTAL</strong></td>
                            <td><b><?php echo $canttotal; ?></b></td>
                            <td></td>
                            <td><b><?php echo number_format($total2, 8, '.', ''); ?></b></td>                            
                        </tr>
                    </tfoot>                    
                </table>   
            @else
                No hay datos de ingresos registrados
            @endif                      
        @endif 
        <br/>
        <br/>
        <br/>
        <hr>      
        <?php $n1 = 0; $cantidad_totals3=0; $totals3=0; $subtotals3=0;$subcantidadtotals3=0;?>
        @if($id_producto!='')                
            @if(count($ordenes_salidas)>0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><p>N</p></th>
                            <th><p>Numero Pecosa</p></th> 
                            <th><p>F. Pecosa</p></th>
                            <th><p>F. Atencion</p></th>
                            <th><p>Destino</p></th> 
                            <th><p>Codigo</p></th> 
                            <th><p>Producto</p></th>
                            <th><p>Lote</p></th> 
                            <th><p>Registro Sanitario</p></th> 
                            <th><p>F. Vencimiento</p></th> 
                            <th><p>Cantidad Entregada</p></th>                            
                            <th><p>Precio Unit.</p></th>                            
                            <th><p>Total</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordenes_salidas as $s)
                        <?php 
                            if($s->pc!=0)
                                $descripcion =  $s->nombre_producto.' ('.$s->cantidad.' X '.$s->presentacion_comercial.') ';
                            else
                                $descripcion =  $s->nombre_producto;
                        ?>
                        <tr>
                            <td><?php
                                $n1++; echo $n1; ?>                            
                            </td>
                            <td>{{$s->nro_acta}}</td>
                            <td>
                                <?php
                                $originalDate = $s->fecha_salida;
                                $fecha = date("d-m-Y", strtotime($originalDate));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $originalDate = $s->fecha_salida;
                                $fecha = date("d-m-Y", strtotime($originalDate));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>
                                <?php 
                                        if($s->lugar_destino_establecimiento){
                                            echo $s->lugar_destino_establecimiento; 
                                        }                                            
                                        else
                                        {   if($s->lugar_destino_farmacia){
                                                if($s->lugar_destino_farmacia!=0)
                                                    echo $s->lugar_destino_farmacia;
                                            }
                                            else
                                            {   if($s->lugar_destino_dependencia)
                                                   echo $s->lugar_destino_dependencia;
                                            }
                                        }
                                                                               
                                    ?>
                            </td>
                            <td>{{$s->cod_producto}}</td>
                            <td>{{$descripcion}}</td>
                            <td>{{$s->lote}}</td>
                            <td>{{$s->registro_sanitario}}</td>                                
                            <td>
                                <?php
                                $originalDate2 = $s->fecha_vencimiento;
                                $fechav = date("d-m-Y", strtotime($originalDate2));
                                echo $fechav;
                                ?> 
                            </td>
                            <td><?php $subcantidadtotals3=$s->cantidad*$s->presentacion_comercial;
                                      $cantidad_totals3 += $subcantidadtotals3;
                                      echo $subcantidadtotals3;
                                ?>                                
                            </td> 
                            <td>{{$s->p_unitario}}</td>                            
                            <td><?php $subtotals3=$s->p_unitario*$s->cantidad*$s->presentacion_comercial; 
                                      $totals3 += $subtotals3;
                                      echo number_format($subtotals3, 6, '.', '');
                            ?></td>                            
                        </tr>
                        @endforeach          
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10"><strong>TOTAL</strong></td>
                            <td><b><?php echo $cantidad_totals3; ?></b></td>
                            <td></td>
                            <td><b><?php echo number_format($totals3, 6, '.', ''); ?></b></td>                            
                        </tr>
                    </tfoot>
                </table>   
            @else
                No hay datos de salidas registradas
            @endif
        @endif
        <br/>
        <br/>
        <br/>
        <hr>
        @if($id_producto!='')
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <td><p>Saldo Inicial</p></td>                            
                        <td><?php $saldo=$saldo_inicial;
                                    echo $saldo;
                                ?></td>
                    </tr>
                    <tr>
                        <td><p>Total Ingresos</p></td>                            
                        <td><?php echo  $cantidad_total+$canttotal; ?></td>
                    </tr>
                    <tr>
                        <td><p>Total Salidas</p></td>                            
                        <td><?php echo  $cantidad_totals3; ?></td>
                    </tr>
                    <tr>                           
                        <td><p>Stock Actual</p></td>                            
                        <td><?php echo  $saldo_final; ?></td>                            
                    </tr>
                </tbody>
            </table>
        @else
            No hay datos registrados
        @endif
        <script type="text/javascript"> window.addEventListener("load", window.print());</script>
    </body>
</html>