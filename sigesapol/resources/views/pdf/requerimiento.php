<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>REQUERIMIENTOS</title>
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
                font-size: 11px !important; 
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
    <body style="font-size: 11px; font-family: arial;border:1px solid #A4A4A4;padding:10px">
        <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tbody>
                <tr>
                    <td colspan="2" class="td_left" align="center">
                        <img src="images/logo_sanidad.png" width="60" />
                    </td>
                    <td colspan="2" class="td_left" align="center">
                        <strong>DIRECCION DE SANIDAD POLICIAL -
                        POLICIA NACIONAL DEL PERÚ</strong>
                    </td>
                </tr>
            </tbody>
        </table>
        <br/><br/>
        <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tbody>
                <tr>
                    <td class="titulo_principal">
                        <h2>
                            REQUERIMIENTO NRO: <?php 
                            $array = explode("-", $requerimiento->numero);
                            echo $array[2].'-'.$array[3]; ?>
                        </h2>
                    </td>
                </tr>
            </tbody>
        </table>
        <br/><br/>
        <table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
                    <?php if($requerimiento->id_consultorio==0): ?>
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $farmacia->nombre ?>
                    </div>
                    <?php else: ?>
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $requerimiento->nombre_consultorio ?>
                    </div>
                    <?php endif; ?>
                    
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $farmacia2->nombre ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td_center">DEPENDENCIA DE ORIGEN</td>
                <td class="td_center">DEPENDENCIA DE DESTINO</td>
            </tr>
        </table>
        <table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php if(is_null($requerimiento->nombre_user_update)){
                            echo $requerimiento->nombre_user_create.','.$requerimiento->apellido_paterno_create.' '.$requerimiento->apellido_materno_create;
                        }
                        else
                        {
                            echo $requerimiento->nombre_user_update.','.$requerimiento->apellido_paterno_update.' '.$requerimiento->apellido_materno_update;
                        }  
                        ?>
                    </div>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo date("d-m-Y", strtotime($requerimiento->fecha)) ?>
                    </div>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $requerimiento->documento ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td_center">USUARIO REQUERIMIENTO</td>
                <td class="td_center">FECHA</td>
                <td class="td_center">DOCUMENTO</td>
            </tr>
        </table>
        <br/>
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
            <tbody>
                <tr>
                    <td height="25px" style="border:1px solid #A4A4A4;font-style:italic" width="1%">Nº</td>
                    <td height="25px" style="border:1px solid #A4A4A4;font-style:italic" width="2%">CODIGO</td>
                    <td height="25px" style="border:1px solid #A4A4A4;font-style:italic"width="15%">PRODUCTO</td>
                    <td height="25px" style="border:1px solid #A4A4A4;font-style:italic" width="2%">CANTIDAD</td>
                </tr>
                <?php 
                $n = 0;
                $total=0;
                foreach ($productosbyrequerimientos as $p): 
                    $n++; $total=$p->cantidad + $total;
                    ?>
                    <tr>
                        <td height="25px" style="border:1px solid #A4A4A4" width="1%"><?php echo $n?></td>
                        <td height="25px" style="border:1px solid #A4A4A4" width="2%"><?php echo $p->codigo_producto?></td>
                        <td height="25px" class="td_left" style="border:1px solid #A4A4A4" width="15%"><?php echo $p->nombre ?> - <?php echo $p->abreviatura ?></td>
                        <td height="25px" style="border:1px solid #A4A4A4"width="2%" ><?php echo $p->cantidad ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr><td height="25px"></td><td></td><td></td><td></td></tr>
                <tr>
                    <td class="td_left" colspan="2">
                        <strong>Total de Item:</strong> <?php echo $n ?>
                    </td>
                    
                    <td></td>
                    <td align="rigth">
                       <strong><?php echo $total ?></strong>
                    </td>
                </tr>

            </tbody>
        </table>        
        
        <script type="text/php">
            if (isset($pdf)) {
                $x = 490;
                $y = 790;
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
    </body>
</html>