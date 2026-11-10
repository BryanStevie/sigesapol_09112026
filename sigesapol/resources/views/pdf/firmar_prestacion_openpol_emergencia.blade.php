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
		
		

		<!--<div style="width:800px; margin-left: 60px;page-break-after: always;">-->
		
		<?php 
		//print_r($historia);
		//print_r($asegurado);
		
		$data = array();
		foreach($historia as $row){
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_RFE_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_RFE_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_DISEASETIME_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_DISEASETIME_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_RELATOCHRONOLOGICO_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_RELATOCHRONOLOGICO_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_PERSONAL_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_PERSONAL_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_FAMILIARES_ANTECE_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_FAMILIARES_ANTECE_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_QUIRURGICOS_ANTECE_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_QUIRURGICOS_ANTECE_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_ALERGIAS_ANTECE_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_ALERGIAS_ANTECE_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_MEDICAMENTOS_ANTECE_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_MEDICAMENTOS_ANTECE_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_OTHER_ANTECE_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_OTHER_ANTECE_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_TERAPEUTICA_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_TERAPEUTICA_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_WORKPLAN_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_WORKPLAN_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_EVOLUTION_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_EVOLUTION_EMER"]=$row->value;
			
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_GENERAL_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_GENERAL_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_SKIN_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_SKIN_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_TCSC_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_TCSC_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_LYMPH_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_LYMPH_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_THORAX_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_THORAX_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_CARDIO_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_CARDIO_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_ABDOMEN_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_ABDOMEN_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_URINARY_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_URINARY_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_MUSCULAR_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_MUSCULAR_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_NERVOUS_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_NERVOUS_EMER"]=$row->value;
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_SUMMARY_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_SUMMARY_EMER"]=$row->value;
			
			if($row->type=="ITEM_TYPE_PEDIATRICADMISSION_DISEASETIME_EMER")$data["ITEM_TYPE_PEDIATRICADMISSION_DISEASETIME_EMER"]=$row->value;
			
			if($row->type=="ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_SYSTOLIC_PRESSURE_RIGHT")$data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_SYSTOLIC_PRESSURE_RIGHT"]=$row->value;
			if($row->type=="ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_DIASTOLIC_PRESSURE_RIGHT")$data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_DIASTOLIC_PRESSURE_RIGHT"]=$row->value;
			if($row->type=="ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_HEARTH_FREQUENCY")$data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_HEARTH_FREQUENCY"]=$row->value;
			if($row->type=="[GENERAL.ANAMNESE]ITEM_TYPE_RESPIRATORY_FRENQUENCY")$data["[GENERAL.ANAMNESE]ITEM_TYPE_RESPIRATORY_FRENQUENCY"]=$row->value;
			if($row->type=="[GENERAL.ANAMNESE]ITEM_TYPE_TEMPERATURE")$data["[GENERAL.ANAMNESE]ITEM_TYPE_TEMPERATURE"]=$row->value;
			if($row->type=="[GENERAL.ANAMNESE]ITEM_TYPE_SATURATION")$data["[GENERAL.ANAMNESE]ITEM_TYPE_SATURATION"]=$row->value;
			if($row->type=="ITEM_TYPE_FIO2")$data["ITEM_TYPE_FIO2"]=$row->value;
		
		}
		
		?>
		
		<table style="margin:0px;padding:0px;padding-bottom:10px;">
            <tbody>
                <tr>
                    <td rowspan="2" colspan="2" class="td_left logo">
                        <img src="images/logo_sanidad.png" width="60" />
                    </td>
					<td colspan="2" width="90%" style="padding-right:20px;">
						<h3><?php if(isset($atencion[0]->ipress))echo $atencion[0]->ipress?></h3>
					</td>
					</tr>
					<tr>
                    <td colspan="2" width="90%" style="padding-right:80px;">
                        <h2>
                            HOJA CLÍNICA DE EMERGENCIA Nº 
							<?php //echo $idTransaccion ?>
							<?php if(isset($atencion[0]->correlativo))echo $atencion[0]->correlativo?>
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
		
		<table cellspacing="1">
		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<u style="font-size: 16px;font-weight: bold;padding-left:0px;"  >SERVICIO DE ADMISIÓN</u>
   			</td>
			<td style="text-align:left">
			<?php if(isset($atencion[0]->servicioEspe))echo $atencion[0]->servicioEspe?>
			</td>
			<tr>
			<td>&nbsp;</td>
			</tr>
   		</tr>
		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<u style="font-size: 16px;font-weight: bold;padding-left:0px"  >PACIENTE </u>
   			</td>
   			<td>
   				
   			</td>
   			<td colspan="2" height="20px">
   				
   			</td>
   		</tr>
   		
   		<tr>
   			
   			<td width="20%" height="20px">
   				
   			</td>
   			
   			<td width="30%" height="20px">
   			
   			</td>
   			
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 20px;font-weight: bold;"  >Nro. HC: </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   			<b style="font-size: 20px;font-weight: bold;"  > 
   			 <?php //echo $asegurado->nrodocafiliado
			 if(isset($paciente[0]->nrodocafiliado))echo $paciente[0]->nrodocafiliado
			 ?>
			 </b>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 15px;font-weight: bold;"  >Nombre: </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   			<b style="font-size: 15px;font-weight: bold;">
				<?php //echo utf8_decode($asegurado->apepatafiliado)." ".utf8_decode($asegurado->apematafiliado)." ".utf8_decode($asegurado->apecasafiliado)." ".utf8_decode($asegurado->nomafiliado)
				if(isset($paciente[0]->apepatafiliado))echo $paciente[0]->apepatafiliado." ".$paciente[0]->apematafiliado." ".$paciente[0]->nomafiliado
				?>
			</b> 
   			 
   			</td>
   			
   				<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 20px;font-weight: bold;" >Documento </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left"> 
   			<b style="font-size: 20px;font-weight: bold;" >
			<?php //echo $asegurado->nomtipdocafiliado." - ".$asegurado->nrodocafiliado
			if(isset($paciente[0]->nomtipdocafiliado))echo $paciente[0]->nomtipdocafiliado." ".$paciente[0]->nrodocafiliado
			?>
   			</b> 
   			</td>
   		</tr>
	  		
   		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;"  >Parentesco: </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   				<?php //echo $asegurado->parentesco
				if(isset($paciente[0]->parentesco))echo $paciente[0]->parentesco
				?>
   			</td>
   			
   		
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 20px;font-weight: bold;"  >Nro CIP </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   			<b style="font-size: 20px;font-weight: bold;"  >  
   			<?php //echo $asegurado->cip?>
			<?php if(isset($paciente[0]->CIPAFILIADO))echo $paciente[0]->CIPAFILIADO?>
   			</b>
   			</td>
   			
   			
   			
   		</tr>
   	   		
   	   		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;"  >Fecha de Nacimiento </b>
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   				<?php //echo $asegurado->fecnacafiliado
				if(isset($paciente[0]->fecnacafiliado))echo $paciente[0]->fecnacafiliado
				?>
   			</td>
   			
   			
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;">Género </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   			<?php //echo $asegurado->nomsexo?> <!--&nbsp; &nbsp; &nbsp; Edad: &nbsp;--><?php //echo $asegurado->edadafiliado
			if(isset($paciente[0]->nomsexo))echo $paciente[0]->nomsexo?> &nbsp; &nbsp; &nbsp; Edad: &nbsp; <?php if(isset($paciente[0]->edadafiliado))echo $paciente[0]->edadafiliado
			?>
   			</td>
   		</tr>
   	   		
   	   		
   		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;" >Dirección Ref: </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   				<!--AdminPerson.getCelDirec2(activePatient.personid)-->
				<?php if(isset($paciente[0]->DIRECCION))echo $paciente[0]->DIRECCION?>
   			</td>
   			
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;" >Telefono </b>
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   			<!--AdminPerson.getCelContacto2(activePatient.personid)-->
			<?php if(isset($paciente[0]->CELULAR))echo $paciente[0]->CELULAR?>
   			</td>
   		</tr>
   	
   		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;" >Distrito Ref: </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   				<!--AdminPerson.getDistritoGeografico2(activePatient.personid)-->
				<?php if(isset($paciente[0]->DISTRITO))echo $paciente[0]->DISTRITO?>
   			</td>
   			
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;" >Conducido por: </b> 
   			</td>
   			
   			<td width="30%" height="20px" style="text-align:left">
   				<!--categoriaEncounter--> 
				<?php if(isset($atencion[0]->nameAcompanante))echo $atencion[0]->nameAcompanante?>
   			</td>
   		</tr>
   		
   		
   		
   		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<u style="font-size: 16px;font-weight: bold;padding-left:0px">TITULAR </u>
   			</td>
   			<td>
   				
   			</td>
   			<td colspan="2" height="20px" style="text-align:left">
   				
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="20%" height="20px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;">Nombre: </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			<b style="font-size: 10px;font-weight: bold;"  >
   			 <?php //echo utf8_decode($asegurado->apepattitular)." ".utf8_decode($asegurado->apemattitular)." ".utf8_decode($asegurado->apecastitular)." ".utf8_decode($asegurado->nomtitular)
			 if(isset($paciente[0]->apepattitular))echo $paciente[0]->apepattitular." ".$paciente[0]->apemattitular." ".$paciente[0]->nomtitular
			 ?>
   			</b>
   			</td>
   			
   			<td width="20%" height="15px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;">Estado </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			<b style="font-size: 13px;font-weight: bold;">
			<?php //echo $asegurado->situacion
			if(isset($paciente[0]->situacion))echo $paciente[0]->situacion
			?>
			</b>
   			</td>
   		</tr>
   		<tr>
   			<td width="20%" height="15px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;">Grado: </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			 <b style="font-size: 13px;font-weight: bold;"  >
			 <?php //echo $asegurado->grado
			 if(isset($paciente[0]->grado))echo $paciente[0]->grado
			 ?>
			 </b> 
   			</td>
   			
   			<td width="20%" height="15px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;">Nro CIP </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			 <b style="font-size: 13px;font-weight: bold;">
			 <?php //echo $asegurado->cip?>
			 <?php if(isset($paciente[0]->CIPCONDICION))echo $paciente[0]->CIPCONDICION?>
			 </b> 
   			
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="20%" height="15px" style="text-align:left">
   				<b style="font-size: 10px;font-weight: bold;">Unidad: </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			 <!--<b style="font-size: 13px;font-weight: bold;"  >AdminPerson.getunidadTitularPatient(activePatient.personid) </b>-->
			 <?php if(isset($paciente[0]->UNIDADTITU))echo $paciente[0]->UNIDADTITU?>
   			</td>
   			
   			<td width="20%" height="15px" style="text-align:left">
   				<b style="font-size: 13px;font-weight: bold;">Documento: </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			<b style="font-size: 13px;font-weight: bold;">
			<?php //echo $asegurado->nomtipdoctitular." - ".$asegurado->nrodoctitular
			if(isset($paciente[0]->nomtipdoctitular))echo $paciente[0]->nomtipdoctitular." - ".$paciente[0]->nrodoctitular
			?>
			</b> 
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="20%" height="15px" style="text-align:left">
   				<b style="font-size: 13px;font-weight: bold;">Médico de Ingreso: </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			<!--<b style="font-size: 13px;font-weight: bold;">AdminPerson.getIngresoMedico(idEncounterTransac)</b>-->
			<?php if(isset($atencion[0]->medicIngreso))echo $atencion[0]->medicIngreso?>
   			</td>
   				<td width="20%" height="15px" style="text-align:left">
   				<b style="font-size: 13px;font-weight: bold;">Fecha Ingreso: </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			<!--<b style="font-size: 13px;font-weight: bold;">AdminPerson.getFechaIngresoEmergencia2Nuevo(idEncounterTransac) +"  "+AdminPerson.getFechaHoraIngresoEmergencia2Nuevo_02(idEncounterTransac)</b>-->
			<?php if(isset($atencion[0]->fechaIngreso))echo $atencion[0]->fechaIngreso?> 
			<?php if(isset($atencion[0]->horaIngreso))echo $atencion[0]->horaIngreso?>
   			</td>
   			
   		</tr>
   		
   		<tr>
   			<td width="25%" height="15px" style="text-align:left">
   				<b style="font-size: 13px;font-weight: bold;">Médico de Egreso: </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   				<!--<b style="font-size: 13px;font-weight: bold;">AdminPerson.getSalidaMedico(idEncounterTransac)</b>-->
				<?php if(isset($atencion[0]->medicEgreso))echo $atencion[0]->medicEgreso?>
   			</td>
   			</td>
   				<td width="20%" height="15px" style="text-align:left">
   				<b style="font-size: 13px;font-weight: bold;">Fecha Egreso: </b> 
   			</td>
   			
   			<td width="30%" height="15px" style="text-align:left">
   			<!--<b style="font-size: 13px;font-weight: bold;">AdminPerson.getFechaEgresoEmergencia2Nuevo(idEncounterTransac)+"  "+AdminPerson.getFechaHoraEgresoEmergencia2Nuevo_02(idEncounterTransac)</b>-->
			<?php if(isset($atencion[0]->fechaAlta))echo $atencion[0]->fechaAlta?> 
			<?php if(isset($atencion[0]->horaAlta))echo $atencion[0]->horaAlta?>
   			</td>
   			
   		</tr>
   		
   		   			
   	</table>
   		
   		<table cellspacing="1">
   		<tr>
   			<td width="5%" height="25px" style="text-align:left">
   				<u style="font-size: 15px;font-weight:bold;padding-left:0px" align="left" > ANAMNESIS</u>  
   			</td>
   			
			<td colspan="3" width="110%" height="25px">
   				 
   			</td>
   		</tr>
   		
   			<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > MOTIVO DE LA ATENCIÓN:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
			<?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_RFE_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_RFE_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > TIEMPO DE ENFERMEDAD:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_DISEASETIME_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_DISEASETIME_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > RELATO CRONOLÓGICO:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_RELATOCHRONOLOGICO_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_RELATOCHRONOLOGICO_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td colspan="4" height="20px">&nbsp;</td>
   		</tr>
   		
   		<tr>
   			<td colspan="4" height="20px" style="text-align:left">
   				<u style="font-size: 15px;font-weight: bold;" align="left" > ANTECEDENTES</u>  
   			</td>
   		</tr>
   		<tr><td colspan="4">&nbsp;</td></tr>
   		
   		<tr>
   			<td height="20px" style="text-align:left;" valign="top">
   				<b style="font-size: 12px;font-weight: bold;" align="left" > PERSONALES:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_PERSONAL_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_PERSONAL_EMER"]?>
   			</td>
   		</tr>
   		<tr>
   			
   			<td height="20px" style="text-align:left;" valign="top">
   				<b style="font-size: 12px;font-weight: bold;" align="left" > FAMILIARES:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left;">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_FAMILIARES_ANTECE_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_FAMILIARES_ANTECE_EMER"]?>
   			</td>
   		</tr>
   		<tr>
   			<td height="20px" style="text-align:left;" valign="top">
   				<b style="font-size: 12px;font-weight: bold;" align="left" > QUIRURGICOS:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left;">
				<?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_QUIRURGICOS_ANTECE_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_QUIRURGICOS_ANTECE_EMER"]?>
   			</td>
   			
   		</tr>
   		<tr>
   			
   			<td height="20px" style="text-align:left;" valign="top">
   				<b style="font-size: 12px;font-weight: bold;" align="left" > ALERGIAS:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left;">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_ALERGIAS_ANTECE_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_ALERGIAS_ANTECE_EMER"]?>
   			</td>
   		</tr>
   		<tr>
   			<td height="20px" style="text-align:left;" valign="top">
   				<b style="font-size: 12px;font-weight: bold;" align="left" > MEDICAMENTOS:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left;">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_MEDICAMENTOS_ANTECE_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_MEDICAMENTOS_ANTECE_EMER"]?>
   			</td>
   			
   		</tr>
   		<tr>
   			<td height="20px" style="text-align:left;" valign="top">
   				<b style="font-size: 12px;font-weight: bold;" align="left" > OTROS:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left;">
				  <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_OTHER_ANTECE_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_OTHER_ANTECE_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr><td colspan="4">&nbsp;</td></tr>
   		
   		<tr>
   			<td width="35%" height="30px" style="text-align:left">
   			<u style="font-size: 15px;font-weight: bold;padding-left:0px" align="left" > EXAMEN CLÍNICO </u> 
   			</td>
   			<td colspan="3" height="30px">
   				
   			</td>
   		</tr>
   		
   		<tr> 
   			<td colspan="4" height="40px" style="text-align:left">
   				<b style="font-size: 13px;font-weight: bold;"  >
				PA: 
				<?php if(isset($data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_SYSTOLIC_PRESSURE_RIGHT"]))echo $data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_SYSTOLIC_PRESSURE_RIGHT"]?> mmHg / 
				<?php if(isset($data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_DIASTOLIC_PRESSURE_RIGHT"]))echo $data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_DIASTOLIC_PRESSURE_RIGHT"]?> mmHg
				</b> 
   				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  <b style="font-size: 13px;font-weight: bold;"  >
				FC: 
				<?php if(isset($data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_HEARTH_FREQUENCY"]))echo $data["ITEM_TYPE_CARDIAL_CLINICAL_EXAMINATION_HEARTH_FREQUENCY"]?>
				/min
				</b>
   				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  <b style="font-size: 13px;font-weight: bold;"  >
				FR: 
				<?php if(isset($data["[GENERAL.ANAMNESE]ITEM_TYPE_RESPIRATORY_FRENQUENCY"]))echo $data["[GENERAL.ANAMNESE]ITEM_TYPE_RESPIRATORY_FRENQUENCY"]?>
				/min
				</b>
   				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;   <b style="font-size: 13px;font-weight: bold;"  >
				T: 
				<?php if(isset($data["[GENERAL.ANAMNESE]ITEM_TYPE_TEMPERATURE"]))echo $data["[GENERAL.ANAMNESE]ITEM_TYPE_TEMPERATURE"]?>
				°C
				</b>
   				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;   <b style="font-size: 13px;font-weight: bold;"  >
				SaO2: 
				<?php if(isset($data["[GENERAL.ANAMNESE]ITEM_TYPE_SATURATION"]))echo $data["[GENERAL.ANAMNESE]ITEM_TYPE_SATURATION"]?>
				%
				</b>
   				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;   <b style="font-size: 13px;font-weight: bold;"  >
				FiO2: 
				<?php if(isset($data["ITEM_TYPE_FIO2"]))echo $data["ITEM_TYPE_FIO2"]?>
				%
				</b>
   			</td>
   			
   		</tr>
   		
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > ASPECTO GENERAL:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_GENERAL_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_GENERAL_EMER"]?>
   			</td>
   		</tr>
   		    
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > PIEL Y ANEXOS:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_SKIN_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_SKIN_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > T.C.S.C.:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_TCSC_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_TCSC_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > LINFÁTICO:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_LYMPH_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_LYMPH_EMER"]?>
   			</td>
   		</tr>
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > RESPIRATORIO:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_THORAX_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_THORAX_EMER"]?>
   			</td>
   		</tr>
   		     
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > CARDIOVASCULAR:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_CARDIO_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_CARDIO_EMER"]?>
   			</td>
   		</tr>
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > DIGESTIVO:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_ABDOMEN_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_ABDOMEN_EMER"]?>
   			</td>
   		</tr>
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" > GENITO-URINARIO:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_URINARY_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_URINARY_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" >MUSCULO-ESQUELETICO:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_MUSCULAR_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_MUSCULAR_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" >NERVIOSO:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_NERVOUS_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_NERVOUS_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="20px" style="text-align:left">
   				<b style="font-size: 12px;font-weight: bold;" align="center" >OTROS:</b>  
   			</td>
   			<td colspan="3" height="20px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_SUMMARY_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_SUMMARY_EMER"]?>
   			</td>
   		</tr>
   		
   		 	<tr>
   			<td width="35%" height="10px">&nbsp;
   				
   			</td>
   			
   			<td width="15%" height="10px">
   			
   			</td>
   			
   			<td width="20%" height="10px">
   				
   			</td>
   			
   			<td width="30%" height="10px">
   			
   			</td>
   		</tr>	
   		</table>
   		
   		<table cellspacing="1">
   		<tr>
   			<td width="35%" height="40px" style="text-align:left">
   				<u style="font-size: 15px;font-weight: bold;padding-left:0px" align="center" > TERAPEUTICA:</u>  
   			</td>
   			<td colspan="3" height="40px" style="text-align:left">
			 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_TERAPEUTICA_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_TERAPEUTICA_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="30px">&nbsp;
   				
   			</td>
   			
   			<td width="15%" height="30px">
   			
   			</td>
   			
   			<td width="20%" height="30px">
   				
   			</td>
   			
   			<td width="30%" height="30px">
   			
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="40px" style="text-align:left">
   				<u style="font-size: 15px;font-weight: bold;padding-left:0px" align="center" > PLAN DE TRABAJO</u>  
   			</td>
   			<td colspan="3" height="40px" style="text-align:left">
			 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_WORKPLAN_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_WORKPLAN_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="30px">&nbsp;
						
   			</td>
   			
   			<td width="15%" height="30px">
			
   			</td>
   			
   			<td width="20%" height="30px">
			
   			</td>
   			
   			<td width="30%" height="30px">
   			
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="40px" style="text-align:left">
   				<u style="font-size: 15px;font-weight: bold;padding-left:0px" align="center" >EVOLUCIÓN</u>  
   			</td>
   			<td colspan="3" height="40px" style="text-align:left">
				 <?php if(isset($data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_EVOLUTION_EMER"]))echo $data["ITEM_TYPE_PEDIATRICADMISSION_CLINICALEXAM_EVOLUTION_EMER"]?>
   			</td>
   		</tr>
   		
   		<tr>
   			<td width="35%" height="30px">&nbsp;
   				
   			</td>
   			
   			<td width="15%" height="30px">
   			
   			</td>
   			
   			<td width="20%" height="30px">
   				
   			</td>
   			
   			<td width="30%" height="30px">
   			
   			</td>
   		</tr>
   		 
   		<tr>
   			<td colspan="2" height="40px" style="text-align:left">
   				<u style="font-size: 15px;font-weight: bold;padding-left:0px" align="center" > DIAGNÓSTICOS:</u>  
   			</td>
   			<td>
   				
   			</td>
   			<td colspan="2" height="40px" style="text-align:left">
   				 
   			</td>
   		</tr>
   		
   		<tr>
   			<td colspan="4" >
   				 <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
					<tbody>
						<tr>
							<td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
							<td width="15%" class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CIE10</td>
							<td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">DESCRIPCION</td>
						</tr>
						<?php $n = 0; 
							if(!isset($diagnosticoActual[0]->Error)){
							foreach($diagnosticoActual as $row){
						$n++;?>
						<tr>
							<td style="border:1px solid #A4A4A4"><?php echo $n?></td>
							<td style="border:1px solid #A4A4A4;text-align:left;padding-left:10px!important"><?php echo $row->codeIcd10?></td>
							<td style="border:1px solid #A4A4A4;text-align:left;padding-left:10px!important"><?php echo $row->descrIcd10?></td>
						</tr>
						<?php }
						}
						?>

					</tbody>
				</table>
   			</td>
   			
   		</tr>
   		
   		<tr>
   			<td colspan="5" height="20px" style="text-align:left">
   				--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   			</td>
   			
   		</tr>
   		
   		<tr>
   			<td width="35%" height="20px">
   				
   			</td>
   			
   			<td width="15%" height="20px">
   			
   			</td>
   			
   			<td width="20%" height="20px">
   				
   			</td>
   			
   			<td width="30%" height="20px">
   			
   			</td>
   		</tr>
   		
   		<tr>
   			<td colspan="2" height="40px" style="text-align:left">
   				<u style="font-size: 15px;font-weight: bold;padding-left:0px" align="center" > EXAMENES DE LABORATORIO:</u>  
   			</td>
   			<td>
   				
   			</td>
   			<td colspan="2" height="40px">
   				 
   			</td>
   		</tr>
   	
   		<tr>
   			<td colspan="4" >
   				 <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
					<tbody>
						<tr>
							<td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
							<td width="15%" class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CPT</td>
							<td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">DESCRIPCION</td>
						</tr>
						<?php $n = 0; 
							if(!isset($laboratorio[0]->Error)){
							foreach($laboratorio as $row){
						$n++;?>
						<tr>
							<td style="border:1px solid #A4A4A4"><?php echo $n?></td>
							<td style="border:1px solid #A4A4A4;text-align:left;padding-left:10px!important"><?php echo $row->CPT?></td>
							<td style="border:1px solid #A4A4A4;text-align:left;padding-left:10px!important"><?php echo $row->DESCRIPTION?></td>
						</tr>
						<?php }
							}
						?>

					</tbody>
				</table>
   			</td>
   			
   		</tr>
   		<tr>
   			<td colspan="5" height="20px" style="text-align:left">
   				--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   			</td>
   			
   		</tr>
   		<tr>
   			<td width="35%" height="20px">
   				
   			</td>
   			
   			<td width="15%" height="20px">
   			
   			</td>
   			
   			<td width="20%" height="20px">
   				
   			</td>
   			
   			<td width="30%" height="20px">
   			
   			</td>
   		</tr>
   		<tr>
   			<td colspan="2" height="40px" style="text-align:left">
   				<u style="font-size: 15px;font-weight: bold;padding-left:0px" align="center" > EXAMENES DE IMAGEN:</u>  
   			</td>
   			<td>
   				
   			</td>
   			<td colspan="2" height="40px">
   				 
   			</td>
   		</tr>
   	
   		<tr>
   			<td colspan="4" style="text-align:left">
   				 <table style="background-color:white !important;border-collapse:separate;border-spacing:1px;">
					<tbody>
						<tr>
							<td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
							<td width="15%" class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CPT</td>
							<td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">DESCRIPCION</td>
						</tr>
						<?php $n = 0; 
							if(!isset($imagen[0]->Error)){
							foreach($imagen as $row){
						$n++;?>
						<tr>
							<td style="border:1px solid #A4A4A4"><?php echo $n?></td>
							<td style="border:1px solid #A4A4A4;text-align:left;padding-left:10px!important"><?php echo $row->CPT?></td>
							<td style="border:1px solid #A4A4A4;text-align:left;padding-left:10px!important"><?php echo $row->DESCRIPTION?></td>
						</tr>
						<?php }
						}
						?>

					</tbody>
				</table>
   			</td>
   			
   		</tr>
   	
   	<tr>
   			<td colspan="5" height="20px" style="text-align:left">
   				--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   			</td>
   			
   		</tr>
   		<tr>
   			<td width="35%" height="20px">
   				
   			</td>
   			
   			<td width="15%" height="20px">
   			
   			</td>
   			
   			<td width="20%" height="20px">
   				
   			</td>
   			
   			<td width="30%" height="20px">
   			
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
            * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE HOJA CLINICA
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



