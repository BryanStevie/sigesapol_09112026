
<?php 
	if(count($turno) > 0):
	?>
		<input type="hidden" name="opc" value="2"/>
	<?php
		$arraTurno["M"]  = "Manana";
		$arraTurno["T"]  = "Tarde";
		$arraTurno["N"]  = "Noche";
		$arraTurno["MD"] = "Madrugada";
		$fechaServerTmp = "";
		foreach($turno as $key=>$rowTurno):
			$fechaServer=$rowTurno->fecha_atencion;
			if($fechaServer == $fechaServerTmp):
					?>
					<td width="9%">
						<input type="text" size="7" style="text-align:right" value="<?php echo ($rowTurno->numero_atencion > 0)?$rowTurno->numero_atencion:""?>" name="turno<?php echo $arraTurno[$rowTurno->turno]?>[<?php echo $fechaServer?>]" <?php if($rowTurno->estado == 1)echo "checked='checked'";?>>
						<input type="hidden" name="id<?php echo $arraTurno[$rowTurno->turno]?>[<?php echo $fechaServer?>]" value="<?php echo $rowTurno->id?>" />
						</td>
					<?php
					if($rowTurno->turno=="MD"):echo "</tr>";endif;
			else:
		?>	
			<input type="hidden" name="fecha[<?php echo $fechaServer?>]" id="fecha" value="<?php echo $fechaServer?>" />
			<tr>
				<td width="62%">
				<?php 
				$fecha_atencion = date("d/m/Y", strtotime($rowTurno->fecha_atencion));
				echo $fecha_atencion;
				?>
				</td>
				<td width="9%">
					<input type="text" size="7" style="text-align:right" value="<?php echo ($rowTurno->numero_atencion > 0)?$rowTurno->numero_atencion:""?>" name="turnoManana[<?php echo $fechaServer?>]" <?php if($rowTurno->estado == 1)echo "checked='checked'";?>>
					<input type="hidden" name="idManana[<?php echo $fechaServer?>]" value="<?php echo $rowTurno->id?>" />
					</td>
				
	<?php
			endif;
			$fechaServerTmp=$fechaServer;
		endforeach;
		
	else:
		?>
		<input type="hidden" name="opc" value="1"/>
		<?php
		for($i=1;$i<=$diaFin;$i++):
		$dia = str_pad((int) $i,2,"0",STR_PAD_LEFT);
		$fecha = $dia."/".$mes."/".$ano;
		$fechaServer = $dia."-".$mes."-".$ano;
		?>
		<input type="hidden" name="fecha[<?php echo $fechaServer?>]" id="fecha" value="<?php echo $fechaServer?>" />
	<tr>
		<td width="62%"><?php echo $fecha?></td>
		<td width="9%"><input type="text" size="7" style="text-align:right" value=""  name="turnoManana[<?php echo $fechaServer?>]" ></td>
		<td width="9%"><input type="text" size="7" style="text-align:right" value=""  name="turnoTarde[<?php echo $fechaServer?>]"></td>
		<td width="9%"><input type="text" size="7" style="text-align:right" value=""  name="turnoNoche[<?php echo $fechaServer?>]"></td>
		<td width="9%"><input type="text" size="7" style="text-align:right" value="" name="turnoMadrugada[<?php echo $fechaServer?>]"></td>
	</tr>
	<?php endfor;

	endif;
?>
