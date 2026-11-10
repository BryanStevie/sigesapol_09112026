<?php
if (count($turno) > 0):
    ?>
    <input type="hidden" name="opc" value="2"/>
    <?php
    $arraTurno["MD"] = "Madrugada";
    $arraTurno["M"] = "Manana";
    $arraTurno["T"] = "Tarde";
    $arraTurno["N"] = "Noche";    
    $fechaServerTmp = "";
    foreach ($turno as $key => $rowTurno):
        $fechaServer = $rowTurno->fecha_atencion;

        $disabled = "";
        if ($fechaServidor > $fechaServer)
            $disabled = "disabled='disabled'";

        if ($fechaServer == $fechaServerTmp):
            ?>
            <td width="15%">
               
                <input type="checkbox" value="<?php echo $rowTurno->turno ?>" name="turno<?php echo $arraTurno[$rowTurno->turno] ?>[<?php echo $fechaServer ?>]" <?php if ($rowTurno->estado == 1) echo "checked='checked'"; ?> <?php echo $disabled ?> class="<?php echo $rowTurno->turno ?>">
               

                <input type="hidden" name="id<?php echo $arraTurno[$rowTurno->turno] ?>[<?php echo $fechaServer ?>]" value="<?php echo $rowTurno->id ?>" />
            </td>
            <?php
            //if ($rowTurno->turno == "MD"):echo "</tr>";
            //endif;
        else:
            ?>	
            <input type="hidden" name="fecha[<?php echo $fechaServer ?>]" id="fecha" value="<?php echo $fechaServer ?>" />
            <tr>
                <td width="40%">
                    <?php
                    $fecha_atencion = date("d/m/Y", strtotime($rowTurno->fecha_atencion));
                    echo $fecha_atencion;
                    ?>
                </td>
                <td width="15%">
                    <!--input type="checkbox" value="M" name="turnoManana[<?php //echo $fechaServer ?>]" <?php //if ($rowTurno->estado == 1) echo "checked='checked'"; ?> class="M">
                    <input type="hidden" name="idManana[<?php //echo $fechaServer ?>]" value="<?php //echo $rowTurno->id ?>" /-->
                    <input type="checkbox" value="MD" name="turnoMadrugada[<?php echo $fechaServer ?>]" <?php if ($rowTurno->estado == 1) echo "checked='checked'"; ?> <?php echo $disabled ?> class="MD">
                    <input type="hidden" name="idMadrugada[<?php echo $fechaServer ?>]" value="<?php echo $rowTurno->id ?>" /-->
                    
                </td>

            <?php
            endif;
            $fechaServerTmp = $fechaServer;
        endforeach;

    else:
        ?>
    <input type="hidden" name="opc" value="1"/>
    <?php
    for ($i = 1; $i <= $diaFin; $i++):
        $dia = str_pad((int) $i, 2, "0", STR_PAD_LEFT);
        $fecha = $dia . "/" . $mes . "/" . $ano;
        $fechaServer = $dia . "-" . $mes . "-" . $ano;
        $fechaServer_ = $ano . "-" . $mes . "-" . $dia;
        $disabled = "";
        if ($fechaServidor > $fechaServer_)
            $disabled = "disabled='disabled'";
			//print_r($turno_disponible);
        ?>
        <input type="hidden" name="fecha[<?php echo $fechaServer ?>]" id="fecha" value="<?php echo $fechaServer ?>" />
        <tr>
                <td width="40%"><?php echo $fecha ?></td>
				
                <td width="15%"><input type="checkbox" value="MD"  name="turnoMadrugada[<?php echo $fechaServer ?>]" <?php echo $disabled ?>  class="MD"></td>
                <td width="15%"><input type="checkbox" value="M"  name="turnoManana[<?php echo $fechaServer ?>]" <?php echo $disabled ?>  class="M"></td>
                <td width="15%"><input type="checkbox" value="T"  name="turnoTarde[<?php echo $fechaServer ?>]" <?php echo $disabled ?> class="T"></td>
                <td width="15%"><input type="checkbox" value="N"  name="turnoNoche[<?php echo $fechaServer ?>]" <?php echo $disabled ?> class="N"></td>
            <!--<td width="9%"><input type="checkbox" value="MD" name="turnoMadrugada[<?php //echo $fechaServer?>]" <?php //echo $disabled?> class="MD"></td>-->
        </tr>
    <?php
    endfor;

endif;
?>
