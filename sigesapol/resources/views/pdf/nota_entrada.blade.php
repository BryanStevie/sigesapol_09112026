<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>REGISTRO DE INGRESO</title>
        <link rel="stylesheet" type="text/css" href="assets/css/pdf.css"  />
        <style>
			html{margin:12px 0px 12px 40px;padding:0px!important;}

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
                font-size: 8px !important; 
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
    <body style="font-size: 8px; font-family: arial;border:1px solid #A4A4A4;padding:10px">
        <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tbody>
                <tr>
                    <td colspan="2" class="td_left logo">
                        <img src="images/logo_sanidad.png" width="60" />
                    </td>
                    <td colspan="2" class="titulo_principal">
                        <h2>
                            <?php echo "NOTA DE ENTRADA";?>
                            
                        </h2>
                    </td>
                    
                    <td colspan="3" class="titulos" width="15%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            Nro. Doc: <?php echo $transferencia->nota_entrada . " - " . $transferencia->ano_nota ?>
                        </div>
                    </td>
                    
                </tr>
            </tbody>
        </table>
        
        

        <table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo date("d-m-Y", strtotime($transferencia->fecha_entrada)); ?>
                    </div>
                </td>
                <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php
                                switch($transferencia->tipo){
                                    case 2 : echo "SERVICIOS"; break;
                                    case 4 : echo "INGRESO EXTERNA NO PNP"; break;
                                    case 5 : echo "INGRESO PNP"; break;
                                }
                            ?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php
                                switch($transferencia->tipo){
                                    case 2 : echo $transferencia->consultorio; break;
                                    case 4 : echo $transferencia->nombre_dependencia; break;
                                    case 5 : echo $transferencia->nombre_est; break;
                                }
                            ?>
                        </div>
                    </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php  echo $transferencia->responsable;?>
                    </div>
                </td>
                
                
            </tr>
            <tr>
                <td class="td_center">FECHA</td>
                <td class="td_center">TIPO</td>
                <td class="td_center">LUGAR DE ORIGEN</td>
                <td class="td_center">RESPONSABLE DE LUGAR ORIGEN</td>

            </tr>
        </table>

        <table border="0px" style="background-color:white !important">
            <tr>
                
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $transferencia->nom_origen ; ?>
                    </div>
                </td>  
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php  echo $transferencia->name.','.$transferencia->paterno.' '.$transferencia->materno;?>
                    </div>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $transferencia->motivo_nombre; ?>
                    </div>
                </td>                    
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $transferencia->referencia; ?>

                        
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td_center">LUGAR DE DESTINO</td>
                <td class="td_center">RESPONSABLE DE LUGAR DESTINO</td>
                <td class="td_center">MOTIVO</td>
                <td class="td_center">OBSERVACION</td>                
                
            </tr>
        </table>
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
            <tbody>
                <tr>
                    <td colspan="8" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;"><b>PRODUCTOS INGRESADOS</b></td>
                </tr>
                <tr>
                    <td  style="border:1px solid #A4A4A4;font-style:italic">#</td>
                    <td class="ancho_producto titulos" style="border:1px solid #A4A4A4;font-style:italic">PRODUCTO</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">LOTE</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">REG. SAN.</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic">FEC. VENC.</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">CANTIDAD</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">PRECIO</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic" width="1%">TOTAL</td>  
                </tr>
                <?php $n = 0; $total = 0; $cant=0;?>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td style="border:1px solid #A4A4A4"><?php $n++; echo $n; ?></td>
                        <?php if ($tiporep == "1") {?>
                        <td class="td_left" style="border:1px solid #A4A4A4"><?php echo $p->codigo_producto . " - " . $p->nombre_producto . " - " . $p->unidad ?></td>
                        <?php } else {?>
                        <td class="td_left" style="border:1px solid #A4A4A4"><?php 
                                if($p->presentacion==1):
                                    echo $p->codigo . " - " . $p->nombre. " ( " .$p->cantidad. " X " .$p->presentacion_comercial. " ) ";
                                else:
                                    if($p->codigo=='59075700'):
                                        echo $p->codigo . " - " . $p->nombre. " ( " .($p->cantidad). " L ) ";
                                    else:
                                        echo $p->codigo . " - " . $p->nombre;
                                    endif; 
                                endif;
                                 ?></td>
                        <?php } ?>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->lote ?></td>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->registro_sanitario ?></td>
                        <td style="border:1px solid #A4A4A4">
                            <?php
                            echo $p->fecha_vencimiento;
                            ?>
                        </td>
                    <?php if ($tiporep == "1") {?>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->cantidad_transferida*$p->presentacion_comercial ?></td>
                    <?php } else {
                            if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608): ?>
                                <td style="border:1px solid #A4A4A4"><?php echo ($p->cantidad*$p->presentacion_comercial)/1000 ?></td>
                            <?php else: ?>
                                <td style="border:1px solid #A4A4A4"><?php echo $p->cantidad*$p->presentacion_comercial ?></td>
                            <?php endif; ?>
                        <?php } ?>
                        <?php if ($tiporep == "1") {?>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->precio_unitario ?></td>
                        <?php } else {?>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->precio_promedio ?></td>
                        <?php } ?>

                        <?php if ($tiporep == "1") {?>
                        <td style="border:1px solid #A4A4A4">
                            <?php   $cantidad = $p->cantidad_transferida*$p->presentacion_comercial;
                                    $cant+=$cantidad;
                                    $precio_unitario = $p->precio_unitario;
                                    $subtotal = $cantidad * $precio_unitario;
                                    $total += $subtotal;
                                    echo $subtotal; 
                            ?>            
                        </td>
                        <?php } else {?>
                        <td style="border:1px solid #A4A4A4">
                            <?php   if($p->id_producto==11045 || $p->id_producto==15938 || $p->id_producto==14607 || $p->id_producto==14608): 
                                        $cantidad = ($p->cantidad*$p->presentacion_comercial/1000);
                                    else:
                                        $cantidad = $p->cantidad*$p->presentacion_comercial;
                                    endif;
                                    $cant+=$cantidad;
                                    $precio_unitario = $p->precio_promedio;
                                    $subtotal = $cantidad * $precio_unitario;
                                    $total += $subtotal;
                                    echo $subtotal; ?>
                        </td>
                        <?php } ?>
                    </tr>
<?php endforeach; ?>
                
            </tbody>
            <tfoot>
                    <tr>
                        <td colspan="5"></td>
                        <td><?php echo $cant ?>
                        <td></td>
                        <td class="text-center"><?php echo $total ?></td>
                    </tr>
                </tfoot>
        </table>


        <table style="margin-top: 10px">
            <tr>
                <td class="td_ancho_espacios"></td>
                <td class="td_ancho_espacios"></td>
            </tr>
        </table>
        <table border="0px" style="background-color:white !important">
            <tr>
                <td>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">                       
                    </div>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td class="td_center"></td>
                <td class="td_center">JEFE ALMACEN </td>
                <td class="td_center"></td>
            </tr>
        </table>

        <footer>
            * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE REGISTRO DE INGRESO, Registrado el <?php echo $transferencia->ingreso?>
        </footer>
    </body>
</html>