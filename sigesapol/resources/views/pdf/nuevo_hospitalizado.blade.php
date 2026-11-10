
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>NUEVO HOSPITALIZADO</title>
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
            .cuadrado-3 {
                 width: 20px; 
                 height: 20px; 
                 border: 3px solid #555;
                 background: #fff;
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
                                HOSPITALIZACIÓN
                            </h2>
                            <h2>
                                <?php echo $admision[0]->ipress?>
                            </h2>
                        </td>
                        <td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php echo $admision[0]->nro_hospitalizacion?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="grado titulos">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision[0]->codigoipress?>
    					</div>
    				</td>
                    <td class="grado titulos">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php 
    						$fecha_admision = date("d-m-Y", strtotime($admision[0]->fecha_ingreso));
    						echo $fecha_admision;
    						?>
    					</div>
    				</td>

                    <td class="grado titulos">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision[0]->nro_historia?>
    					</div>
    				</td>

                    @if($nivel==2)
                    <td class="grado titulos" style="padding-right:0px">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php 
    						$turno = "";
    						if($admision[0]->turno == "M")$turno = "MAÑANA";
    						if($admision[0]->turno == "T")$turno = "TARDE";
    						if($admision[0]->turno == "N")$turno = "NOCHE";
    						if($admision[0]->turno == "MD")$turno = "MADRUGADA";
    						echo $turno?>
    					</div>
    				</td>
                    @endif
                    <tr>
                        <td class="td_center">CODIGO IPRESS</td>
                        <td class="td_center">FECHA INTERNAMIENTO</td>
                        <td class="td_center">H. C.</td>
                    @if($nivel==2)
                        <td class="td_center">TURNO</td>
                    @endif
                    </tr>
                </tr>
            </table>
            
           <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="titulos" width="3.5%">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision[0]->dni_beneficiario?>
    					</div>
    				</td>
                    <td class="grado titulos">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision[0]->paterno_beneficiario." ".$admision[0]->materno_beneficiario.", ".$admision[0]->nombre_beneficiario?>
    					</div>
    				</td>
    				<td class="titulos" width="5.8%" style="padding-right:0px">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision[0]->tipo_beneficiario?>
    					</div>
    				</td>
                    <td class="titulos" width="5.8%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            
                            <?php echo $admision[0]->edad?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center">DNI DEL PACIENTE</td>
                    <td class="td_center">APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</td>
    				<td class="td_center">TIPO DE BENEFICIARIO</td>
                    <td class="td_center">EDAD</td>
                </tr>
            </table>
            @if($admision[0]->tipo_beneficiario!='TITULAR')
            <!--table border="0px" style="background-color:white !important">
                <tr>
                    <td class="titulos" width="3.5%">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php //echo $admision[0]->dni_titular?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php //echo $admision[0]->paterno_titular." ".$admision[0]->materno_titular.", ".$admision[0]->nombre_titular?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center">DNI DEL TITULAR</td>
                    <td class="td_center">APELLIDOS Y NOMBRES COMPLETOS DEL TITULAR</td>
                    
                </tr>
            </table-->
            @endif
    		
    		<table border="0px" style="background-color:white !important">
                <tr>
                    @if($nivel==3)
                    <!--td class="titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php //echo $admision[0]->consultorio?>
                        </div>
                    </td-->
                    @endif
                    <td class="titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->consultorio_hospitalizacion?>
                        </div>
                    </td>
                    <td class="titulos" width="50.5%" style="padding-right:0px">
    					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
    						<?php echo $admision[0]->paterno_medico." ".$admision[0]->materno_medico.", ".$admision[0]->nombre_medico?>
    					</div>
    				</td>
                </tr>
                <tr>
                    @if($nivel==3)
                    <!--td class="td_center">SERVICIO</td-->
                    @endif
                    <td class="td_center">SALA</td>
                    <td class="td_center">MEDICO RESPONSABLE DE HOSPITALIZACION</td>
                </tr>
            </table>

            @if($nivel==3)
                <table border="0px" style="background-color:white !important">
                    <tr>
                        <td class="titulos">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php 
                                    $tarifa = "";
                                    if($admision[0]->tarifa == "T1")$tarifa = "TARIFA 1";
                                    if($admision[0]->tarifa == "T2")$tarifa= "TARIFA 2";
                                    if($admision[0]->tarifa == "T3")$tarifa= "TARIFA 3";
                                    if($admision[0]->tarifa == "T4")$tarifa= "TARIFA 4";
                                    echo $tarifa?>
                            </div>
                        </td>
                        <td class="titulos">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php 
                                    $origen = "";
                                    if($admision[0]->origen == "O1")$origen= "MOPRI";
                                    if($admision[0]->origen == "O2")$origen= "EMERGENCIA";
                                    if($admision[0]->origen == "O3")$origen= "CONSULTORIO EXTERNO";                                
                                    echo $origen?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td_center">TARIFA</td>
                        <td class="td_center">ORIGEN</td>
                    </tr>
                </table>
            @endif

            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->pabellon?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->piso?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->cuarto?>
                        </div>
                    </td>
                    <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->cama?>
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
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="grado titulos">
                        <div class="rectangulo" width="30%" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->cie?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo"  width="70%"  style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->nombre_diagnostico?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center" width="10%">CODIGO</td>
                    <td class="td_center" width="70%">DIAGNOSTICO</td>
                </tr>
            </table>
            <table style="margin-top: 10px">
                <tr>
                    <td class="td_ancho_espacios"></td>
                    <td class="td_ancho_espacios"></td>
                </tr>
            </table>
            <footer>
            * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE HOSPITALIZACIÓN
            </footer>




@if($nivel==3)
<div style="display:block;page-break-before:always;"></div>



            <table style="margin:0px;padding:0px;padding-bottom:10px">
                <tbody>
                    <tr>
                        <td colspan="2" class="td_left logo">
                            <img src="images/logo_sanidad.png" width="60" />
                        </td>
                        <td colspan="2" class="titulo_principal">
                            <h2>
                                PAPELETA DE EGRESO
                            </h2>
                            <h2>
                                <?php echo $admision[0]->ipress?>
                            </h2>
                        </td>
                        <td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                                <?php echo $admision[0]->nro_hospitalizacion?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->codigoipress?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php 
                            $fecha_admision = date("d-m-Y", strtotime($admision[0]->fecha_ingreso));
                            echo $fecha_admision;
                            ?>
                        </div>
                    </td>
                
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->tipo_beneficiario?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php 
                                $tarifa = "";
                                if($admision[0]->tarifa == "T1")$tarifa = "TARIFA 1";
                                if($admision[0]->tarifa == "T2")$tarifa= "TARIFA 2";
                                if($admision[0]->tarifa == "T3")$tarifa= "TARIFA 3";
                                if($admision[0]->tarifa == "T4")$tarifa= "TARIFA 4";
                                echo $tarifa?>
                        </div>
                    </td>
                
                </tr>
                <tr>

                        <td class="td_center">CODIGO IPRESS</td>
                        <td class="td_center">FECHA INTERNAMIENTO</td>
                        <td class="td_center">TIPO DE BENEFICIARIO</td>
                        <td class="td_center">TARIFA</td>
                
                </tr>
                
            </table>
            
           <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="titulos" width="3.5%">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->dni_beneficiario?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->paterno_beneficiario." ".$admision[0]->materno_beneficiario.", ".$admision[0]->nombre_beneficiario?>
                        </div>
                    </td>
                    <td class="titulos" width="5.8%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->nro_historia?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center">DNI DEL PACIENTE</td>
                    <td class="td_center">APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</td>
                    <td class="td_center">HISTORIA CLINICA</td>
                </tr>
            </table>

            @if($admision[0]->tipo_beneficiario!='TITULAR')
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="titulos" width="3.5%">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->dni_titular?>
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <?php echo $admision[0]->paterno_titular." ".$admision[0]->materno_titular.", ".$admision[0]->nombre_titular?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center">DNI DEL TITULAR</td>
                    <td class="td_center">APELLIDOS Y NOMBRES COMPLETOS DEL TITULAR</td>
                    
                </tr>
            </table>
            @endif

            <br/><br/><br/><br/>
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            
                        </div>
                    </td>
                    <td class="titulos">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            
                        </div>
                    </td>
                    <td class="titulos" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center">SERVICIO<br/><br/></td>
                    <td class="td_center">SALA</td>
                    <td class="td_center">CAMA</td>
                </tr>
            </table>

            <table border="0px" style="background-color:white !important">
                <tr>
                    <td class="grado titulos">
                        <div class="rectangulo" width="30%" style="border: 1px solid #A4A4A4;">
                            
                        </div>
                    </td>
                    <td class="grado titulos">
                        <div class="rectangulo"  width="70%"  style="border: 1px solid #A4A4A4;">
                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_center" width="10%">CODIGO CIE</td>
                    <td class="td_center" width="70%">DIAGNOSTICO</td>
                </tr>
            </table>
            <br/>
            <h2>EGRESO:</h2>
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td >
                        <h2>Curado</h2>
                    </td>
                    <td width="1%">
                        <div class="cuadrado-3">
                        </div>
                    </td>
                    <td >
                        <h2>Aliviado</h2>
                    </td>
                    <td width="1%">
                        <div class="cuadrado-3">
                        </div>
                    </td><td >
                        <h2>No Tratado</h2>
                    </td>
                    <td width="1%">
                        <div class="cuadrado-3">
                        </div>
                    </td><td >
                        <h2>Fugado</h2>
                    </td>
                    <td width="1%">
                        <div class="cuadrado-3">
                        </div>
                    </td><td >
                        <h2>Fallecido</h2>
                    </td>
                    <td width="1%">
                        <div class="cuadrado-3">
                        </div>
                    </td>
                </tr>
            </table>
            <br/>
            <h2>Necesita _________________ dias de descanso___________________debe presentarse al Consultorio de <br/><br/>_______________________________para su control</h2>
            <br/><br/>
            <div align="right"><h2>Lima, _____ de ________ de 20_____</h2></div>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
            <table border="0px" style="background-color:white !important">
                <tr>
                    <td width="2%">
                    </td>
                    <td width="18%">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            
                        </div>
                    </td>
                    <td width="20%">
                    </td>
                    <td width="15%">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            
                        </div>
                    </td>
                    <td width="2%">
                    </td>
                </tr>
                <tr>
                    <td width="2%">
                    </td>
                    <td >
                        Jefe del Dpto de Registros Medicos <br/>Estadisticas del Hospital Central SPE
                    </td>
                    <td width="20%">
                    </td>
                    <td >
                        Jefe de Servicio
                    </td>
                    <td width="2%">
                    </td>
                </tr>
            </table>
		@endif
    </body>
</html>