<?php if (isset($aHorario['config']['opc'])) : ?>
  <input type="hidden" name="opc" value="<?php echo $aHorario['config']['opc']; ?>" />
<?php endif; ?>
<?php foreach ($aHorario as $kHorario => $vHorario) : ?>
  <?php
  if ($kHorario == 'config') :
    continue;  
  endif;
  ?>
  <tr<?php if($kHorario == date ('Y-m-d') ): echo ' class="warning"'; endif; ?>>
    <?php if (isset($vHorario['input_fecha']) && $vHorario['input_fecha'] == true) : ?>
      <input type="hidden" name="fecha[<?php echo $kHorario ?>]" id="fecha" value="<?php echo $kHorario ?>" />
    <?php endif; ?>
    <td><strong><?php echo $vHorario['fecha']; ?></strong></td>
    <?php if (isset($vHorario['turno'])) : ?>
      <?php foreach ($vHorario['turno'] as $kTurno => $vTurno) : ?>
        <td class="text-center app-turno">
          <?php foreach ($vTurno as $kInput => $vInput) : ?>
            <?php
            $inputAtributos = '';
            $show = true;
            if (isset($aTurnosProgramados[1][$kTurno . '-' . $kHorario]) && $kInput != 'hidden') :
              $inputAtributos .= ' disabled title="Médico: ' . $aTurnosProgramados[1][$kTurno . '-' . $kHorario] . '"';
            else :
              $data = isset($aTurnosProgramados[2][$kTurno . '-' . $kHorario]) ? $aTurnosProgramados[2][$kTurno . '-' . $kHorario] : null;
              if (!empty($data) && $kInput == 'checkbox' && !isset($vInput['checked'])):
                $inputAtributos .= ' disabled title="Médico: ' . $aTurnosProgramados[2][$kTurno . '-' . $kHorario] . '"';
              else:
                if (isset($aTurnosMedico[$kTurno . '-' . $kHorario]) && $kInput == 'checkbox' && isset($vInput['checked']) && !$vInput['checked'] && !$vInput['disabled']): 
                    $inputAtributos .= ' disabled'; 
                    $show = false; 
                endif; 
                if ($show && isset($vInput['disabled']) && $vInput['disabled'] == true && $kInput != 'hidden') :
                  $inputAtributos .= ' disabled'; 
                endif; 
              endif;
            endif;
            if (isset($vInput['checked']) && $vInput['checked'] == true) :
              $inputAtributos .= ' checked="checked"';
            endif;
            if (isset($vInput['class']) && $vInput['class'] == true) :
              $inputAtributos .= ' class="' . $vInput['class'] . '"';
            endif;
            ?>
            <input type="<?php echo $kInput; ?>" name="<?php echo $vInput['name']; ?>" value="<?php echo $vInput['value']; ?>" <?php echo $inputAtributos; ?> />
          <?php endforeach; ?>
        </td>
      <?php endforeach; ?>
    <?php else : ?>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    <?php endif; ?>
  </tr>
<?php endforeach; ?>