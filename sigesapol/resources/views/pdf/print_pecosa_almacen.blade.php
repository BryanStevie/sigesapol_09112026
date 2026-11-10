<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DIRECCION DE SANIDAD POLICIAL</title>
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
    <body style="font-size: 12px; font-family: arial;padding:10px">
        <table style="margin:0px;padding:0px;padding-bottom:10px" width="100%" >
            <tbody>
                <tr>
                    <td width="20%"; colspan="2" class="td_left logo">
                        <img src="{{Request::root()}}/images/logo_sanidad.png" width="60" />
                    </td>
                    <td width="40%"; colspan="2" class="titulo_principal">
                        <?php if($transferencia->estado==0) : ?>
                            <?php if($transferencia->anulado==0) : ?>
                                <h3 align="center"><font color='red'>
                                     PEDIDO - COMPROBANTE DE SALIDA ANULADA
                                    </font>
                                </h3>
                            <?php else: ?>
                                <h3 align="center"><font color='red'>
                                     PEDIDO - COMPROBANTE DE SALIDA ELIMINADA
                                    </font>
                                </h3>
                            <?php endif; ?>                                    
                        <?php else: ?>
                                <h3 align="center">
                                    @if($transferencia->id_motitransferencia==13)
                                        PEDIDO - COMPROBANTE DE SALIDA
                                    @else
                                        NOTA DE SALIDA
                                    @endif
                                </h3>
                        <?php endif; ?>
                    </td>
                    <td width="160px" colspan="2" class="titulos" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @if($transferencia->id_motitransferencia==13)
                                Nro_Pecosa: <br/><?php echo $transferencia->nro_acta; ?>
                            @else
                                Nro_Nota_Salida: <br/> <?php echo $transferencia->nro_acta; ?>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table border="0px">
            <tr>
                <td><p style="font-size:10px" align="left"><b>Fecha : </b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo date("d-m-Y",strtotime($transferencia->fecha));?></p></td>  
                <td><p style="font-size:10px" align="left"><b>Motivo : </b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo ($transferencia->motivo!="")?$transferencia->motivo:"&nbsp;"?></p></td>                              
            </tr>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Lugar de Origen : </b></p></td>
                <td colspan="3"><p style="font-size:10px" align="left"><?php echo $transferencia->farmacia_origen?></p></td>                
            </tr>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Lugar Destino : </b></p></td>
                <td colspan="3"><p style="font-size:10px" align="left"><?php echo ($transferencia->farmacia_destino!="")?$transferencia->farmacia_destino:$transferencia->consultorio_destino?></p></td>
            </tr>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Responsable de lugar destino : </b></p></td>
                <td colspan="3"><p style="font-size:10px" align="left"><?php echo $transferencia->nombre_jefe_servicio;?></p></td>                
            </tr> 
            <tr>
                <td><p style="font-size:10px" align="left"><b>Observacion : </b></p></td>
                <td colspan="3"><p style="font-size:10px" align="left"><?php echo ($transferencia->observacion!="")?$transferencia->observacion:"&nbsp;"?></p></td>                
            </tr>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Responsable del Registro:</b></p></td>
                <td colspan="3"><p style="font-size:10px" align="left"><?php echo ($transferencia->nombre_jefe_farmacia_local!="")?$transferencia->nombre_jefe_farmacia_local:"&nbsp;"?></p></td>                
            </tr>
            
        </table>        
        <hr>
		<?php $n = 0; $total=0; $total_sub=0; $x=1;
            $count_registro=count($productos); $total_cantidad=0; $monto=0;
        ?>
                
        <?php $i=1;?>
            <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
            <tbody>
                <tr>
                    <td style="border:1px solid #A4A4A4;font-size:10px; font-style:italic" width="5px">#</td>
                    <td class="ancho_producto titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic" width="50%">Descripcion</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic">Reg. San.</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic">Lote</td>                    
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic">Vence</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic" width="1%">Cantidad</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic" width="1%">Precio</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic" width="1%">Total</td>  
                </tr>
        <?php foreach($productos as $p):
                    $total_cantidad=$total_cantidad + $p->cantidad_transferida*$p->presentacion_comercial;
                    $monto=$monto + $p->cantidad_transferida*$p->presentacion_comercial*$p->precio_unitario;
                ?>
                <tr>
                    <td style="border:1px solid #A4A4A4"><?php echo $i++;?></td>
                    <td class="td_left" style="border:1px solid #A4A4A4"><p style="font-size:10px">
                    <?php 
                        if($p->presentacion==1)
                            echo $p->codigo_producto . " - " . $p->nombre_producto." - ".$p->unidad. " ( " .$p->cantidad_transferida. " X " .$p->presentacion_comercial. " ) ";
                        else
                            echo $p->codigo_producto . " - " . $p->nombre_producto." - ".$p->unidad; 
                    ?></p>
                    </td>                        
                    <td style="border:1px solid #A4A4A4"><p style="font-size:10px"><?php echo $p->registro_sanitario?></p></td>
                    <td style="border:1px solid #A4A4A4"><p style="font-size:10px"><?php echo $p->lote?></p></td>                    
					<td style="border:1px solid #A4A4A4"><p style="font-size:10px">
						<?php
							echo $p->fecha_vencimiento;
						?></p>
					</td>
					<td style="border:1px solid #A4A4A4"><p style="font-size:10px"><?php echo $p->cantidad_transferida*$p->presentacion_comercial?></p></td>
                    <td style="border:1px solid #A4A4A4"><p style="font-size:10px"><?php echo $p->precio_unitario?></p></td>
                    <td style="border:1px solid #A4A4A4"><p style="font-size:10px"><?php $subtotal=$p->cantidad_transferida*$p->presentacion_comercial*$p->precio_unitario;  echo $subtotal?></p></td>
                </tr>
            <?php 
        endforeach;?>
                        <tr><td colspan="5"><strong>Total</strong></td><td><strong><?php echo $total_cantidad;?></strong></td><td></td><td><strong><?php echo $monto;?></strong></td></tr>

                        </tbody>
                    </table>

                    <br/><br/><br/><br/><br/><br/>

                    <table border="0px" style="background-color:white !important">
                        <tr>
                            <td class="grado titulos">
                                <p align="center">_____________________________________________</p>
                            </td>
                            <td class="grado titulos">
                                <p align="center">_____________________________________________</p>
                            </td>
                            <td class="grado titulos">
                                <p align="center">_____________________________________________</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="td_center"><p align="center" style="font-size:10px">Jefe Almacen</p></td>
                            <td class="td_center"><p align="center" style="font-size:10px">Responsable de la Distribucion</p></td>                
                            <td class="td_center"><p align="center" style="font-size:10px">Recibi Conforme</p></td>                
                        </tr>
                    </table>
                    
        <footer>
            <script type="text/php">
            if (isset($pdf)) {
                $x = 510;
                $y = 810;
                $text = "Pagina {PAGE_NUM} de {PAGE_COUNT}";
                $font = null;
                $size = 8;
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
		</footer>
    </body>
</html>


