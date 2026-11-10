
<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-6">
            <div id="postlist">
                <div class="panel">
                    <div class="panel-body">                        
                        <hr>
                        <p>Estimado <b>Sr(a). <?php echo $cita->asegurado_nombre." ".$cita->asegurado_paterno." ".$cita->asegurado_materno?></b></p>
                        <hr>
                        <p>Se ha realizado satisfactoriamente la programaci&oacute;n de su Cita:</p>
					
						<table class="tg" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<th style="text-align:left">DNI</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->nro_doc_ident?></td>
							</tr>
							<tr>
								<th style="text-align:left">NOMBRE Y APELLIDOS</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->asegurado_nombre." ".$cita->asegurado_paterno." ".$cita->asegurado_materno?></td>
							</tr>
							<tr>
								<th style="text-align:left">PARENTESCO</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->parentesco?></td>
							</tr>
							<tr>
								<th style="text-align:left">GRADO</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->grado?></td>
							</tr>
							<tr>
								<th style="text-align:left">ESTABLECIMIENTO</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->establecimiento?></td>
							</tr>
							<tr>
								<th style="text-align:left">SERVICIO</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->servicio?></td>
							</tr>
							<tr>
								<th style="text-align:left">MEDICO</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->medico_nombre." ".$cita->medico_paterno." ".$cita->medico_materno?></td>
							</tr>
							<tr>
								<th style="text-align:left">FECHA DE CITA</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->dia?></td>
							</tr>
							<tr>
								<th style="text-align:left">HORA DE CITA</th>
								<td style="text-align:left;padding-left:10px"><?php echo $cita->hora?></td>
							</tr>
						</table>
					
                    </div>
					
					
					<br />
					<br />
					<br />
					<br />
					<br />
					
                </div>
            </div>

        </div>

    </div>
</div>