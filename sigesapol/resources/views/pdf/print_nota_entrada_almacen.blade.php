<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DIRECCION DE SANIDAD POLICIAL</title>
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
        <table style="margin:0px;padding:0px;padding-bottom:10px"  width="100%" >
            <tbody>
                <tr>
                    <td width="30%"; colspan="2" class="td_left logo">
                        <img src="{{Request::root()}}/images/logo_sanidad.png" width="60" />
                    </td>
                    <td  width="40%" colspan="2" class="titulo_principal">
                        <?php 
                            switch ($transferencia->concepto) {
                                case 1:
                                    $titulo_principal="ORDEN DE COMPRA";
                                    break;                                                                                                           
                                default:
                                    $titulo_principal= "NEA";
                                    break;
                            }
                        ?> 
                        <?php if($transferencia->anulado==0) : ?>
                            <h3 align="center"><font color='red'>
                                 <?php echo $titulo_principal;?> ANULADA
                                </font>
                            </h3>
                        <?php else: ?>
                            
                            <?php if($transferencia->estado==0) : ?>
                                <h3 align="center"><font color='red'>
                                    <?php echo $titulo_principal;?> ELIMINADA
                                </font></h3>
                            <?php else: ?>
                                <h3 align="center">
                                    <?php echo $titulo_principal;?>
                                </h3>
                            <?php endif; ?>        
                            
                        <?php endif; ?>                            
                    </td>
                    <td width="30%"; colspan="3" class="titulos"style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            Nro: <?php echo $transferencia->nota_entrada . " - " . $transferencia->ano_nota ?>
                        </div>
                    </td>
                    
                </tr>
            </tbody>
        </table>

        <hr>
        <table border="0px">
            <tr>
                <td><p style="font-size:10px" align="left"><b>Fecha : </b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo date("d-m-Y",strtotime($transferencia->fecha_entrada));?></p></td>  
                <td><p style="font-size:10px" align="left"><b>Concepto :</b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo $transferencia->descripcion_conceptos;"&nbsp;"?></p></td>                              
            </tr>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Lugar de Origen : </b></p></td>
                <td colspan="3"><p style="font-size:10px" align="left"><?php
                                foreach($destino as $con){
                                    if($transferencia->lugar_origen==$con->id){
                                        echo $con->nombre;
                                        break;
                                    }
                                }
                            ?></p></td>                
            </tr>  

            <?php if($transferencia->concepto==1){ ?>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Proveedor :</b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo $transferencia->ruc_proveedor.'-'.$transferencia->nombre_proveedor; ?></p></td>
            </tr>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Certificacion :</b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo $transferencia->referencia ?></p></td>
            </tr>
            
            <tr>
                <td><p style="font-size:10px" align="left"><b>Nro Contrato :</b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo $transferencia->nro_contrato; ?></p></td>
                <td><p style="font-size:10px" align="left"><b>Nro SIAF :</b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo $transferencia->nro_exp_siaf ?></p></td>
            </tr>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Proceso :</b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo $transferencia->descripcion_tipo_proceso ?> - <?php echo $transferencia->nro_proceso ?></p></td>
                <td><p style="font-size:10px" align="left"><b>Nro Entrega :</b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo $transferencia->nro_entrega.' de '.$transferencia->total_entrega ?></p></td>
            </tr>
            <?php }else{ ?>
            <tr>
                <td><p style="font-size:10px" align="left"><b>Referencia :</b></p></td>
                <td><p style="font-size:10px" align="left"><?php echo $transferencia->referencia ?></p></td>
            </tr>
            <?php }?>

        </table>        
        <hr>
        
        
        

       
<!--<table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">-->
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
            <tbody>
                <tr>
                    <td style="border:1px solid #A4A4A4;font-size:10px; font-style:italic" width="5px">#</td>
                    <td class="ancho_producto titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic" width="50%">Descripcion</td>
                    @if($transferencia->concepto==1 || $transferencia->concepto==7 || $transferencia->concepto==8)
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic">Marca</td>
                    @endif                    
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic">Reg. San.</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic">Lote</td>
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic">Vence</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic" width="1%">Cantidad</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic" width="1%">Precio</td>  
                    <td class="titulos" style="border:1px solid #A4A4A4;font-size:10px;font-style:italic" width="1%">Total</td>  
                </tr>

<?php $n = 0; $total = 0; $cant=0;?>
<?php foreach ($productos as $p): ?>
                    <tr>
                        <td style="border:1px solid #A4A4A4"><?php $n++; echo $n; ?></td>
                        <?php if ($tiporep == "1") {?>
                        <td class="td_left" style="border:1px solid #A4A4A4"><?php echo $p->codigo_producto . " - " . $p->nombre_producto . " - " . $p->unidad ?></td>
                        <?php } else {?>
                        <td class="td_left" style="border:1px solid #A4A4A4">
                            <?php 
                                if($p->presentacion==1)
                                    echo $p->codigo . " - " . $p->nombre. " - ". $p->unidad . " ( " .$p->cantidad. " X " .$p->presentacion_comercial. " )";
                                else
                                    echo $p->codigo . " - " . $p->nombre . " - " . $p->unidad ?>
                        </td>
                        <?php } ?>
                        @if($transferencia->concepto==1 || $transferencia->concepto==7 || $transferencia->concepto==8)
                            <td style="border:1px solid #A4A4A4"><?php echo $p->marca ?></td>
                        @endif
                        <td style="border:1px solid #A4A4A4"><?php echo $p->registro_sanitario ?></td>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->lote ?></td>                        
                        <td style="border:1px solid #A4A4A4">
                            <?php
                           /* $fecha_vencimiento = "";
                            if ($p->fecha_vencimiento != ""):
                                $originalDate = $p->fecha_vencimiento;
                                $fecha_vencimiento = date("d-m-Y", strtotime($originalDate));
                            endif;*/
                            echo $p->fecha_vencimiento;
                            ?>
                        </td>
                        <?php if ($tiporep == "1") {?>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->cantidad_transferida*$p->presentacion_comercial ?></td>
                        <?php } else {?>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->cantidad*$p->presentacion_comercial ?></td>
                        <?php } ?>
                        <?php if ($tiporep == "1") {?>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->precio_unitario ?></td>
                        <?php } else {?>
                        <td style="border:1px solid #A4A4A4"><?php echo $p->precio_promedio ?></td>
                        <?php } ?>

                        <?php if ($tiporep == "1") {?>
                        <td style="border:1px solid #A4A4A4"><?php $cantidad = $p->cantidad_transferida*$p->presentacion_comercial;
                                                    $cant+=$cantidad;
                                                    $precio_unitario = $p->precio_unitario;
                                                    $subtotal = $cantidad * $precio_unitario;
                                                    $total += $subtotal;

                                                    echo $subtotal; ?></td>
                        <?php } else {?>
                        <td style="border:1px solid #A4A4A4"><?php $cantidad = $p->cantidad*$p->presentacion_comercial;
                                                    $cant+=$cantidad;
                                                    $precio_unitario = $p->precio_promedio;
                                                    $subtotal = $cantidad * $precio_unitario;
                                                    $total += $subtotal;

                                                    echo $subtotal; ?></td>
                        <?php } ?>
                        
                        
                    </tr>
<?php endforeach; ?>
                
            </tbody>
            <tfoot>
                    <tr>
                        @if($transferencia->concepto==1 || $transferencia->concepto==7 || $transferencia->concepto==8)
                            <td colspan="6"></td>
                        @else
                            <td colspan="5"></td>
                        @endif
                        <td><?php echo $cant ?>
                        <td></td>
                        <td class="text-center"><?php echo $total ?></td>
                    </tr>
                </tfoot>
        </table>
        <br/>
        <br/>
        <br/>
        <p align="center">_________________________</p>
        <p align="center">JEFE ALMACEN</p>

        
        <script type="text/javascript"> 
            window.addEventListener("load", window.print());
        </script>
    </body>
</html>