<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Consolidados de Movimiento por Producto</title>
        <style type="text/css">
            table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
      }

      table tr:nth-child(2n-1) td {
        
      }

      table th,
      table td {
        text-align: center;

      }

      table th {
        padding: 0px 0px;
        color: #5D6975;
        border-bottom: 0.5px solid #C1CED9;
        white-space: nowrap;        
        
      }

      table .service,
      table .desc {
        text-align: left;
      }

      table td {
        padding: 0px;
        text-align: center;
      }

      table td.service,
      table td.desc {
        border-bottom:0.5px solid #5D6975;
        font-size: 0.7em;
      }

      table td.unit,
      table td.qty,
      table td.total {
        font-size: 0.7em;
        border-bottom:0.5px solid #5D6975;

      }

      table td.grand {
        border-top: 1px solid #5D6975;
      }

        </style>        

    </head>
    <body>  
      <div class="row">
        <div class="col-xs-12">
          <h3 alig="center" class="page-header">
           <i class="fa fa-globe"></i> CONSOLIDADO DE MOVIMIENTO POR PRODUCTO
          </h3>
        </div>
      </div>
      <div class="row invoice-info">
       <div class="col-sm-4 invoice-col">
         <address>
           <strong>
             {{$nombre_farmacia}}</strong><br>
             DE <?php echo $fechaDesde; ?> A <?php echo $fechaHasta; ?> - <?php echo $hora = date('H:i:s'); ?><p align="right">
             <strong>
              ANAQUEL:</strong>
              <?php  $cont=0; if ($id_anaquel==0){
                                    foreach ($anaqueles as $key => $anaquel):
                                             echo $anaquel->nombre;
                                             $cont++;
                                             if($cont==1)
                                              $name_anaquel=$anaquel->nombre;
                                             break;

                                   endforeach;
                                    }
                                  else
                                  {
                                    foreach ($anaqueles as $key => $anaquel):
                                        if($anaquel->id==$id_anaquel){
                                            $cont++;
                                            echo $anaquel->nombre;
                                            if($cont==1)
                                              $name_anaquel=$anaquel->nombre;
                                            break;
                                        }
                                    endforeach;

                                  }
                                  ?>
              </p>
         </address>
       </div>
       <!-- /.row -->

                <div class="box-body">
                <?php $anaquel_anterior=0; $i=1; $total=0; $x=0;?>
                @foreach ($kardex as $key => $rowKardex)
                
                    <?php 
                        
                        if($anaquel_anterior!=$rowKardex->anaquel){
                            $total=0;
                            $x=0;
                            if($anaquel_anterior!=0){ ?>
                                </tbody>
                            </table>
                            <div style="page-break-after:always;"></div>
                            <!--imagen-->
                           <div class="row">
                            <div class="col-xs-12">
                              <h3 alig="center">
                               <i class="fa fa-globe"></i> CONSOLIDADO DE MOVIMIENTO POR PRODUCTOS
                              </h3>
                            </div>
                          </div>
                          <div class="row invoice-info">
                           <div class="col-sm-4 invoice-col">
                             <address>
                               <strong>
                                 {{$nombre_farmacia}}</strong><br>
                                 DE <?php echo $fechaDesde; ?> A <?php echo $fechaHasta; ?> - <?php echo $hora = date('H:i:s'); ?><p align="right">
                                 <strong>
                                  ANAQUEL:</strong>
                                  {!!$rowKardex->anaquel!!}</p>
                             </address>
                           </div>
                          </div>
                           <!-- /.row -->
                    <?php
                            }
                            $anaquel_anterior=$rowKardex->anaquel;

                    ?>
                    
                    
                    <table class="table table-condensed">
                        <thead>
                            <tr style='font-size: 12px;'>
                                <th style="border:1px solid #999999">N.</th>
                                <th style="border:1px solid #999999">C&oacute;digo</th>
                                <th style="border:1px solid #999999">Descripcion</th>
                                <th style="border:1px solid #999999">U.Medida</th>
                                <th style="border:1px solid #999999">Stock Actual</th>
                                <th style="border:1px solid #999999">P.Unit</th>
                                <th style="border:1px solid #999999">Total</th>
                                <th style="border:1px solid #999999">F. Venc</th>
                            </tr>
                        </thead>                
                        <tbody>
                    <?php  } ?>  

                      @if($rowKardex->stock_hasta>0 )    
                      <?php $x++;?>
                        <tr>
                            <td height="20" class="desc"><?php echo ($x) ?></td>   
                            <td class="total"><?php echo $rowKardex->codigo_producto ?></td>
                            <td class="desc"><?php echo $rowKardex->nombre_producto ?></td>
                            <td class="total"><?php echo $rowKardex->unidad ?></td>
                            <?php if($rowKardex->id_producto==11045):?>
                                <td class="total"><?php echo $rowKardex->stock_hasta/1000 ?></td>
                            <?php else:?>
                                <td class="total"><?php echo $rowKardex->stock_hasta ?></td>
                            <?php endif;?>
                            <td class="total"><?php echo number_format($rowKardex->precio_unitario, 2, '.', ','); ?></td>
                            <td height="20" class="total"><?php 
                            $total_fila=$rowKardex->stock_hasta*$rowKardex->precio_unitario;
                            echo number_format($total_fila, 2, '.', ',');
                            $total=$total+$total_fila;
                             ?>
                            </td>
                            <td class="total"><?php echo $rowKardex->fecha_vencimiento ?></td>
                        </tr>
                        @endif
                    @endforeach
                        
                        </tbody>
                            </table>
                <br/><br/>
        <script type="text/php">
            if (isset($pdf)) {
                $x = 510;
                $y = 800;
                $text = "Pagina {PAGE_NUM} de {PAGE_COUNT}";
                $font = null;
                $size = 10;
                $color = array(255,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }            
        </script>
        <script type="text/javascript"> 
  window.addEventListener("load", window.print());
</script>
    </body>
</html>

