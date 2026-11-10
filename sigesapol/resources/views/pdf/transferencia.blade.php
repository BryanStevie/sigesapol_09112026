<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TRANSFERENCIAS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/pdf2.css"  />
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
                font-size: 12px !important; 
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
                font-size: 25px !important
            }
            .titulo_principal{
                 width: 80%;
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
    <body style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px">
        <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tbody>
                <tr>
                    <td colspan="2" class="td_left logo">
                        <img src="images/logo_sanidad.png" width="60" />
                    </td>
                    <td colspan="2" class="titulo_principal">
                        @if($id_farmacia==46)
                        <h2>
                            @if($transferencia->id_motitransferencia==13)
                                PEDIDO - COMPROBANTE DE SALIDA
                            @else
                                NOTA DE SALIDA
                            @endif
                        </h2>
                        @else
                        <?php if($transferencia->estado==0) : ?>
                            <h2><font color='red'>
                                TRANSFERENCIA INVALIDADA
                                </font>
                            </h2>
                        <?php else: ?>
                            <h2>
                                TRANSFERENCIA
                            </h2>
                        <?php endif; ?>
                        @endif
                    </td>
                    <td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @if($transferencia->id_motitransferencia==13)
                                Nro Pecosa <br/><?php echo $transferencia->nro_acta; ?>
                            @else
                                Nro Nota Salida <br/> <?php echo $transferencia->nro_acta; ?>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo date("d-m-Y",strtotime($transferencia->fecha));?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $transferencia->farmacia_origen?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php 
						echo $transferencia->nombre_jefe_farmacia_local;
						?>
					</div>
				</td>
            </tr>
            <tr>
                <td class="td_center">FECHA</td>
                <td class="td_center">LUGAR DE ORIGEN</td>
                <td class="td_center">RESPONSABLE DE LUGAR ORIGEN</td>
            </tr>
        </table>
    
       <table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php 
						$tipo = "";
						if($transferencia->tipo == 1)$tipo = "Transferencia Interna EESS";
                        if($transferencia->tipo == 2)$tipo = "Transferencia Interna Servicios";
                        if($transferencia->tipo == 3)$tipo = "Transferencia Externa EESS PNP";
                        if($transferencia->tipo == 4)$tipo = "Transferencia NO PNP";
						echo $tipo;
						?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo ($transferencia->farmacia_destino!="")?$transferencia->farmacia_destino:$transferencia->consultorio_destino?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $transferencia->nombre_jefe_servicio;?>
					</div>
				</td>
            </tr>
            <tr>
                <td class="td_center">TIPO</td>
                <td class="td_center">LUGAR DE DESTINO</td>
                <td class="td_center">RESPONSABLE DE LUGAR DESTINO</td>
            </tr>
        </table>
		
		<table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo ($transferencia->motivo!="")?$transferencia->motivo:"&nbsp;"?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo ($transferencia->observacion!="")?$transferencia->observacion:"&nbsp;"?>
					</div>
				</td>
            </tr>
            <tr>
                <td class="td_center">MOTIVO</td>
                <td class="td_center">OBSERVACI&Oacute;N</td>
            </tr>
        </table>
		
		<?php $n = 0; $total=0; $total_sub=0; $x=1; $cant=0;
            $count_registro=count($productos); $total_total1=0; $total_subtotal1=0;
        ?>
                
        <?php $i=1;?>
        <?php foreach($productos as $p):
                    $total=$total + $p->cantidad_transferida;
        
            if ($i==1){
                ?>
            <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
            <tbody>
                <tr>
                    <td colspan="8" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;"><b>PRODUCTOS TRANSFERIDOS</b></td>
                </tr>
                <tr>
                    <td style="border:1px solid #A4A4A4;font-style:italic" width="10px">#</td>
                    <td class="ancho_producto titulos" style="border:1px solid #A4A4A4;font-style:italic" width="50%">PRODUCTO</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">LOTE</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">REG. SANITARIO</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">FECHA VENCIMIENTO</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">CANTIDAD</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">PRECIO</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">TOTAL</td>  
                </tr>
                <tr>
                    <td style="border:1px solid #A4A4A4"><?php echo $i++;?></td>
                    <td class="td_left" style="border:1px solid #A4A4A4">
                        <?php 
                                if($p->presentacion==1):
                                    echo $p->codigo_producto . " - " . $p->nombre_producto. " - " . $p->unidad . "( " .$p->cantidad_transferida. " X " .$p->presentacion_comercial. " ) ";
                                endif;
                                if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608):
                                    echo $p->codigo_producto . " - " . $p->nombre_producto . " - " . $p->unidad . "( " . $p->cantidad_transferida . " L ) ";
                                else:
                                    echo $p->codigo_producto . " - " . $p->nombre_producto . " - " . $p->unidad ;
                                endif;
                        ?>
                    </td>                        
                    <td style="border:1px solid #A4A4A4"><?php echo $p->lote?></td>
                    <td style="border:1px solid #A4A4A4"><?php echo $p->registro_sanitario?></td>
					<td style="border:1px solid #A4A4A4">
						<?php
							echo $p->fecha_vencimiento;
						?>
					</td>
					<td style="border:1px solid #A4A4A4">
                        @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                                <?php echo $p->cantidad_transferida*$p->presentacion_comercial/1000 ?>
                            @else
                                <?php echo $p->cantidad_transferida*$p->presentacion_comercial ?>
                            @endif
                    </td>
                    <td style="border:1px solid #A4A4A4"><?php echo $p->precio_unitario?></td>
                    <td style="border:1px solid #A4A4A4"><?php 
                                if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608):
                                    $cantidad = $p->cantidad_transferida*$p->presentacion_comercial/1000;
                                else:
                                    $cantidad = $p->cantidad_transferida*$p->presentacion_comercial;
                                endif;
                                
                                $cant+=$cantidad;
                                $precio_unitario = $p->precio_unitario;
                                $subtotal = $cantidad * $precio_unitario;
                                $total += $subtotal;
                                echo $subtotal; 
                        ?>  
                    </td>
                </tr>
            <?php }else { ?>
                <tr>
                    <td style="border:1px solid #A4A4A4"><?php echo $i++;?></td>
                    <td class="td_left" style="border:1px solid #A4A4A4">

                        <?php 
                                if($p->presentacion==1):
                                    echo $p->codigo_producto . " - " . $p->nombre_producto. " - " . $p->unidad . "( " .$p->cantidad_transferida. " X " .$p->presentacion_comercial. " ) ";
                                endif;
                                if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608):
                                    echo $p->codigo_producto . " - " . $p->nombre_producto . " - " . $p->unidad . "( " . $p->cantidad_transferida . " L ) ";
                                else:
                                    echo $p->codigo_producto . " - " . $p->nombre_producto . " - " . $p->unidad ;
                                endif;
                        ?>
                    </td>
                    <td style="border:1px solid #A4A4A4"><?php echo $p->lote?></td>
                    <td style="border:1px solid #A4A4A4"><?php echo $p->registro_sanitario?></td>
                    <td style="border:1px solid #A4A4A4">
                        <?php
                            echo $p->fecha_vencimiento;
                        ?>
                    </td>
                    <td style="border:1px solid #A4A4A4">
                            @if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608)
                                <?php echo $p->cantidad_transferida*$p->presentacion_comercial/1000 ?>
                            @else
                                <?php echo $p->cantidad_transferida*$p->presentacion_comercial ?>
                            @endif
                    </td>
                    <td style="border:1px solid #A4A4A4"><?php echo $p->precio_unitario?></td>
                    <td style="border:1px solid #A4A4A4">
                        <?php 
                                if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608):
                                    $cantidad = $p->cantidad_transferida*$p->presentacion_comercial/1000;
                                else:
                                    $cantidad = $p->cantidad_transferida*$p->presentacion_comercial;
                                endif;
                                
                                $cant+=$cantidad;
                                $precio_unitario = $p->precio_unitario;
                                $subtotal = $cantidad * $precio_unitario;
                                $total += $subtotal;
                                echo $subtotal; 
                        ?>        
                    </td>
                </tr>
            <?php } ?>
                <?php 
                    $total_sub=$total_sub + $subtotal;
                    $total_total1=$total_sub + $total_total1;
                ?>
        @if( $id_farmacia==46)            
            @if( $count_registro == $i-1)
                @if($x < 15)
                        <tr><td colspan="5"><strong>SubTotal</strong></td><td><strong><?php echo $total;?></strong></td><td></td><td><strong><?php echo $total_sub;?></strong></td></tr>
                        <tr><td colspan="5"><strong>Total</strong></td><td><strong><?php echo $total_total1 + $total;?></strong></td><td></td><td><strong><?php echo $total_total1;?></strong></td></tr>

                        </tbody>
                    </table>

                    <br/><br/><br/><br/>

                    <table border="0px" style="background-color:white !important">
                        <tr>
                            <td class="grado titulos">
                                <div class="rectangulo" style="border: 1px solid #A4A4A4;">                        
                                </div>
                            </td>
                            <td class="grado titulos">
                                <div class="rectangulo" style="border: 1px solid #A4A4A4;">                       
                                </div>
                            </td>
                            <td class="grado titulos">
                                <div class="rectangulo" style="border: 1px solid #A4A4A4;">                       
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td_center">JEFE ALMACEN</td>
                            <td class="td_center">RESPONSABLE DE LA DISTRIBUCION</td>                
                            <td class="td_center">RECIBI CONFORME</td>                
                        </tr>
                    </table>
                    <br/><br/><br/><br/>
                <?php $x=1; $total=0; $total_sub=0; 
                ?>
                @endif
            @else
                @if($i == 15)

                        <tr><td colspan="5"><strong>Sub Total</strong></td><td><strong><?php echo $total;?></strong></td><td></td><td><strong><?php echo $total_sub;?></strong></td></tr>
                        </tbody>
                    </table>

                    <br/><br/>

                    <table border="0px" style="background-color:white !important">
                        <tr>
                            <td class="grado titulos">
                                <div class="rectangulo" style="border: 1px solid #A4A4A4;">                        
                                </div>
                            </td>
                            <td class="grado titulos">
                                <div class="rectangulo" style="border: 1px solid #A4A4A4;">                       
                                </div>
                            </td>
                            <td class="grado titulos">
                                <div class="rectangulo" style="border: 1px solid #A4A4A4;">                       
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td_center">JEFE ALMACEN</td>
                            <td class="td_center">RESPONSABLE DE LA DISTRIBUCION</td>                
                            <td class="td_center">RECIBI CONFORME</td>                
                        </tr>
                    </table>
                    <?php $total=0; $total_sub=0; 

                    ?>
                    <div style="page-break-after:always;"></div>

                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
                        <tbody>
                            <tr>
                                <td colspan="8" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;"><b>PRODUCTOS TRANSFERIDOS</b></td>
                            </tr>
                            <tr>
                                <td width="10px" style="border:1px solid #A4A4A4;font-style:italic">#</td>
                                <td class="ancho_producto titulos" style="border:1px solid #A4A4A4;font-style:italic">PRODUCTO</td>
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">LOTE</td>
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">REG. SANITARIO</td>
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">FECHA VENCIMIENTO</td>  
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">CANTIDAD</td>  
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">PRECIO</td>  
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">TOTAL</td>  
                            </tr>
                        

                @endif

            @endif    
        @endif

        <?php 
        endforeach;?>

        <footer>
            <script type="text/php">
            if (isset($pdf)) {
                $x = 760;
                $y = 570;
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
		<?php if($transferencia->estado==0) : ?>
<font color='red'> * SE HA INVALIDADO LA PRESENTE TRANSFERENCIA - Fecha Registro <?php echo $transferencia->updated_at ; ?> </font>
        <?php else: ?>
        * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE TRANSFERENCIA - Fecha Registro <?php echo $transferencia->updated_at ; ?>
        <?php endif; ?>
		</footer>
    </body>
</html>