<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>RECETA VALE</title>
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
            .ancho_nro{
                width: 5%;
            }
            .ancho_producto{
                width: 45%;
            }
            .titulos{
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
    <body style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px;background-color:white !important">
		
		<table style="margin:0px;padding:0px;padding-bottom:0px;">
            <tbody>
                <tr>
                    <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px">
                        <?php echo $url_descarga?>
                    </td>
                    <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px">
                        <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
                    </td>
                </tr>
            </tbody>
        </table>
		
        <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tbody>
                <tr>
                    <td colspan="2" class="td_left logo">
                        <img src="images/logo_sanidad.png" width="60" />
                    </td>
                    <td colspan="2" width="90%" style="padding-right:80px;">
                        <h2>
                            RECETA VALE Nº <?php echo $receta->nro_receta ?>
                        </h2>
                    </td>
                    <!--<td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php //echo $receta->nro_receta ?>
                        </div>
                    </td>-->
                </tr>
            </tbody>
        </table>

        <table border="0px" style="background-color:white !important">
            <tr>
                <!--<td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php //echo $receta->codigoipress ?>
                    </div>
                </td>-->
				<td class="titulos" width="5%">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        @if($receta->tipo_receta=='QUIRURGICO')
                            CENTRO QUIRURGICO
                        @else
                            @if($receta->tipo_receta=='NUTRICIONAL')
                                SOPORTE NUTRICIONAL
                            @else
                                <?php echo $receta->tipo_receta ?>    
                            @endif
                        @endif
                        
                    </div>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $receta->farmacia ?>
                    </div>
                </td>
                <td class="titulos" width="4.5%" style="padding-right:0px">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php
                        if($receta->fecha_registro <> ""){
                        $fecha_admision = date("d-m-Y", strtotime($receta->fecha_registro));
                        echo $fecha_admision;
                        } else {
                            echo "---";
                        }
                        ?>
                    </div>
                </td>
                <!--<td class="grado titulos" style="padding-right:0px">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php
                        /*$turno = "--";
                        if ($receta->turno == "M")
                            $turno = "MAÑANA";
                        if ($receta->turno == "T")
                            $turno = "TARDE";
                        if ($receta->turno == "N")
                            $turno = "NOCHE";
                        if ($receta->turno == "MD")
                            $turno = "MADRUGADA";
                        echo $turno
                        */?>
                    </div>
                </td>-->
            </tr>
            <tr>
                <!--<td class="td_center">CODIGO IPRESS</td>-->
				<td class="td_center"><strong>TIPO DE ATENCIÓN</strong></td>
                <td class="td_center"><strong>FARMACIA</strong></td>
                <td class="td_center"><strong>FECHA DE EXPEDICIÓN</strong></td>
                <!--<td class="td_center">TURNO</td>-->
            </tr>
        </table>

        <table border="0px" style="background-color:white !important">
            <tr>
                <td class="titulos" width="5%">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $receta->dni_beneficiario ?>
                    </div>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $receta->paterno_beneficiario . " " . $receta->materno_beneficiario . ", " . $receta->nombre_beneficiario ?>
                    </div>
                </td>
				<td class="titulos" width="4.5%" style="padding-right:0px">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $receta->nro_historia."&nbsp;" ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td_center"><strong><?php echo $receta->tipodoc_beneficiario ?> DEL PACIENTE</strong></td>
                <td class="td_center"><strong>APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</strong></td>
				<td class="td_center"><strong>HISTORIA CLINICA</strong></td>
            </tr>
        </table>


        <table border="0px" style="background-color:white !important">
            <tr>
                <td class="titulos" width="5%">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $receta->consultorio ?>
                    </div>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php echo $receta->nombre_medico." ".$receta->paterno_medico." ".$receta->materno_medico ?>
                    </div>
                </td>
				<td class="titulos" width="4.5%" style="padding-right:0px">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php
                        $turno = "--";
                        if ($receta->turno == "M")
                            $turno = "MAÑANA";
                        if ($receta->turno == "T")
                            $turno = "TARDE";
                        if ($receta->turno == "N")
                            $turno = "NOCHE";
                        if ($receta->turno == "MD")
                            $turno = "MADRUGADA";
                        echo $turno
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td_center"><strong>SERVICIO</strong></td>
                <td class="td_center"><strong>MEDICO</strong></td>
				<td class="td_center"><strong>TURNO</strong></td>
            </tr>
        </table>

		<table border="0px" style="background-color:white !important">
            <tr>
                <td class="titulos" width="9%" style="padding-right:0px">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php 
							$cad = "";
							foreach($diagnosticos as $row):
								$cad .= $row->codigo." - ".$row->nombre."<br>";
							endforeach;
							echo $cad;
						?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td_center"><strong>DIAGNOSTICO (CIE 10)</strong></td>
            </tr>
        </table>
		
        <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
            <tr><td width="250" valign="top">
                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
                        <tbody>
                            <tr>
                                <td colspan="6" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;"><b>PRESCRIPCIÓN</b></td>
                                <!--<td colspan="2" class="titulos" style="border:1px solid #A4A4A4;font-size:8px!important;font-style:italic;text-align:left;font-weight:bold">FECHA DE DISPENSACIÓN : <?php echo date("d-m-Y", strtotime($receta->fecha_expedicion)) ?></td>-->
                            </tr>
                            <tr>
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">N°</td>
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="4%">PRODUCTO</td>
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="2%">UN. MED.</td>
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">CANT.</td>  
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="0.5%">DÍAS</td>
								<td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="2%">POSOLOGÍA</td>
                                <!--<td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold" width="1%!important">CANT. DADO</td>
                                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">OBSERV.</td>-->
                            </tr>
<?php $n = 0; ?>
                            @foreach($productos as $p)
                            <?php $n++;?>
                            <tr>
                                <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                                <td class="td_left_" style="border:1px solid #A4A4A4">{{$p->codigo}} - {{$p->nombre}}</td>
                                <td style="border:1px solid #A4A4A4">{{$p->abreviatura}}</td>
                                <td style="border:1px solid #A4A4A4">{{$p->cantidad_prescrita}}</td>
                                <td style="border:1px solid #A4A4A4">{{$p->tiempo_periodo}}</td>
								<td style="border:1px solid #A4A4A4">{{$p->posologia}}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </td>
            </tr>
        </table>


        <table style="margin-top: 10px">
            <tr>
                <td class="td_ancho_espacios"></td>
                <td class="td_ancho_espacios"></td>
            </tr>
        </table>

        <footer>
            * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE RECETA
        </footer>
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



