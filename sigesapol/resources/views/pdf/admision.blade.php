<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>USO DE INSUMOS</title>
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
                            ADMISIÓN
                        </h2>
                    </td>
                    <td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->nro_admision?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $admision->codigoipress?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php 
						$fecha_admision = date("d-m-Y", strtotime($admision->fecha_admision));
						echo $fecha_admision;
						?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $admision->tipo_admision?>
					</div>
				</td>
				<td class="grado titulos" style="padding-right:0px">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php 
						$turno = "";
						if($admision->turno == "M")$turno = "MAÑANA";
						if($admision->turno == "T")$turno = "TARDE";
						if($admision->turno == "N")$turno = "NOCHE";
						if($admision->turno == "MD")$turno = "MADRUGADA";
						echo $turno?>
					</div>
				</td>
            </tr>
            <tr>
                <td class="td_center">CODIGO IPRESS</td>
                <td class="td_center">FECHA DE ATENCIÓN</td>
                <td class="td_center">TPO DE ATENCIÓN</td>
				<td class="td_center">TURNO</td>
            </tr>
        </table>
    
       <table border="0px" style="background-color:white !important">
            <tr>
                <td class="titulos" width="3.5%">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $admision->dni_beneficiario?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $admision->paterno_beneficiario." ".$admision->materno_beneficiario.", ".$admision->nombre_beneficiario?>
					</div>
				</td>
				<td class="titulos" width="5.8%" style="padding-right:0px">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $admision->nro_historia?>
					</div>
				</td>
            </tr>
            <tr>
                <td class="td_center">DNI DEL PACIENTE</td>
                <td class="td_center">APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</td>
				<td class="td_center">HISTORIA CLINICA</td>
            </tr>
        </table>
		
		
		<table border="0px" style="background-color:white !important">
            <tr>
                <td class="titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $admision->consultorio?>
					</div>
				</td>
                <td class="titulos" width="50.5%" style="padding-right:0px">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $admision->dni_medico." - ".$admision->paterno_medico." ".$admision->materno_medico.", ".$admision->nombre_medico?>
					</div>
				</td>
            </tr>
            <tr>
                <td class="td_center">SERVICIO</td>
                <td class="td_center">PROFESIONAL PRESCRIPTOR</td>
            </tr>
        </table>
		
        <table style="margin-top: 10px">
            <tr>
                <td class="td_ancho_espacios"></td>
                <td class="td_ancho_espacios"></td>
            </tr>
        </table>
		
		<footer>
		* CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE ADMISIÓN
		</footer>
    </body>
</html>