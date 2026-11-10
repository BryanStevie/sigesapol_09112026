<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>ALTA PACIENTE</title>
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
                                PACIENTE DE ALTA
                            </h2>
                            <h2>
                                <?php echo $admision->ipress?>
                            </h2>
                        </td>
                        <td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php echo $admision->nro_hospitalizacion?>
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
    						$fecha_admision = date("d-m-Y H:i:s", strtotime($admision->fecha_ingreso));
                            echo $fecha_admision;
    						?>
    					</div>
    				</td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php 
                            $fecha_salida = date("d-m-Y H:i:s", strtotime($admision->fecha_salida));
                            echo $fecha_salida;
                            ?>
                        </div>
                    </td>
                    <td class="grado titulos">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision->tipo_beneficiario?>
    					</div>
    				</td>
                    @if($nivel==2)
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
                    @endif
                </tr>
                <tr>
                        <td class="td_center">CODIGO IPRESS</td>
                        <td class="td_center">FECHA INGRESO</td>
                        <td class="td_center">FECHA SALIDA</td>
                        <td class="td_center">TIPO DE BENEFICIARIO</td>
                    @if($nivel==2)
                        <td class="td_center">TURNO</td>
                    @endif
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

            @if($admision->tipo_beneficiario!='TITULAR')
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="titulos" width="3.5%">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->dni_titular?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->paterno_titular." ".$admision->materno_titular.", ".$admision->nombre_titular?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center">DNI DEL TITULAR</td>
                    <td class="td_center">APELLIDOS Y NOMBRES COMPLETOS DEL TITULAR</td>
                    
                </tr>
            </table>
            @endif

    		
    		
    		<table border="0px" style="background-color:white !important">
                <tr>
                    <td class="titulos">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision->consultorio?>
    					</div>
    				</td>
                    @if($id_user_consultorio!=17)
                        <td class="titulos">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php echo $admision->consultorio_hospitalizacion?>
                            </div>
                        </td>
                    @else
                        <td class="titulos">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php echo " HEMODIALISIS";?>
                            </div>
                        </td>
                    @endif

                    <td class="titulos" width="50.5%" style="padding-right:0px">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision->paterno_medico." ".$admision->materno_medico.", ".$admision->nombre_medico?>
    					</div>
    				</td>

                </tr>
                <tr>
                    <td class="td_center">SERVICIO</td>
                    <td class="td_center">SALA</td>
                    <td class="td_center">PROFESIONAL MEDICO</td>
                </tr>
            </table>
            @if($nivel==3)
                
                <table border="0px" style="background-color:white !important">
                    <tr>
                        @if($id_user_consultorio!=17)
                        <td class="titulos">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php 
                                    $tarifa = "";
                                    if($admision->tarifa == "T1")$tarifa = "TARIFA 1";
                                    if($admision->tarifa == "T2")$tarifa= "TARIFA 2";
                                    if($admision->tarifa == "T3")$tarifa= "TARIFA 3";
                                    if($admision->tarifa == "T4")$tarifa= "TARIFA 4";
                                    echo $tarifa?>
                            </div>
                        </td>
                        <td class="titulos">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php 
                                    $origen = "";
                                    if($admision->origen == "O1")$origen= "MOPRI";
                                    if($admision->origen == "O2")$origen= "EMERGENCIA";
                                    if($admision->origen == "O3")$origen= "CONSULTORIO EXTERNO";                                
                                    echo $origen?>
                            </div>
                        </td>
                        @endif
                        <td class="titulos">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php 
                                    $origen = "";
                                    if($admision->condicion == "AL")$condicion = "ALIVIADO";
                                    if($admision->condicion == "NT")$condicion = "NO TRATADO";
                                    if($admision->condicion == "CU")$condicion = "CURADO";
                                    if($admision->condicion == "FU")$condicion = "FUGADO";
                                    if($admision->condicion == "FA")$condicion = "FALLECIDO";                                
                                    echo $condicion?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        @if($id_user_consultorio!=17)
                        <td class="td_center">TARIFA</td>
                        <td class="td_center">ORIGEN</td>
                        @endif    
                        <td class="td_center">CONDICION</td>
                    </tr>
                </table>
                
            @endif
            
            @if($id_user_consultorio!=17)
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->pabellon?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->piso?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->cuarto?>
                        </div>
                    </td>
                    <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->cama?>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td class="td_center">PABELLON</td>
                    <td class="td_center">PISO</td>
                    <td class="td_center">HABITACION</td>
                    <td class="td_center">CAMA</td>
                </tr>

            </table>
            @endif
            
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="grado titulos">
                        <div class="rectangulo" width="20.5%" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->cie?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo"  width="70.5%"  style="border: 1px solid #A4A4A4;">
                            <?php echo $admision->nombre_diagnostico?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center" width="20.5%">CODIGO</td>
                    <td class="td_center" width="70.5%">DIAGNOSTICO</td>
                </tr>
            </table>
            
            <table style="margin-top: 10px">
                <tr>
                    <td class="td_ancho_espacios"></td>
                    <td class="td_ancho_espacios"></td>
                </tr>
            </table>
		<footer>
		* CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE ALTA DEL PACIENTE
		</footer>
    </body>
</html>