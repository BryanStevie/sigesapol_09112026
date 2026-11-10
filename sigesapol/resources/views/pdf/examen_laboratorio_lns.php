<?php
function ObtenerRango($ra_texto, $l_ref_inf, $l_ref_sup, $comentario = '', $codigo_proveedor)
{
  if (!empty($ra_texto)) {
    return htmlspecialchars($ra_texto);
  }

  $resultado = '';

  // Convertir valores a string para evitar problemas con NULL
  // $l_ref_inf = (string) $l_ref_inf;
  // $l_ref_sup = (string) $l_ref_sup;

  // Escapar HTML en los valores para evitar conflictos con etiquetas <>
  $l_ref_inf = htmlspecialchars(trim($l_ref_inf));
  $l_ref_sup = htmlspecialchars(trim($l_ref_sup));

  $l_ref_inf = str_replace("\n", "<br>", $l_ref_inf);
  $l_ref_sup = str_replace("\n", "<br>", $l_ref_sup);

  // Si ambos valores existen, concatenarlos con el guion
  if (strlen($l_ref_inf) > 0 && strlen($l_ref_sup) > 0) {
    $resultado = $l_ref_inf . ' - ' . $l_ref_sup;
    if ($resultado == '0 - 0'):
      $resultado = "";
    endif;
  } elseif (strlen($l_ref_inf) > 0) {
    // Si solo el valor inferior tiene contenido
    $resultado = $l_ref_inf;
  } elseif (strlen($l_ref_sup) > 0) {
    // Si solo el valor superior tiene contenido
    $resultado = $l_ref_sup;
  }

  // Si hay comentario, agregarlo en una nueva línea
  if ($codigo_proveedor != "01"): // Si el proveedor es Labcore no considerar este campo
    if (!empty($comentario)) {
      if (!empty($resultado)) {
        $resultado .= "<br>";
      }
      $resultado .= nl2br(htmlspecialchars($comentario));
    }
  endif;

  return $resultado;
}


function explotarLinea($linea)
{
  $separadores = ['¬5¬', '¬4¬', '¬¬'];

  foreach ($separadores as $sep) {
    $partes = explode($sep, $linea);
    if (count($partes) > 1) {
      return $partes;
    }
  }

  // Si no encontró ningún separador válido, devuelve la línea completa como único elemento
  return [$linea];
}

function explotarLineaSegundaParte($texto)
{
  $separadores = ['¬7¬', '¬8¬', '¬'];

  foreach ($separadores as $sep) {
    $partes = explode($sep, $texto);
    if (count($partes) > 1) {
      return $partes;
    }
  }

  return [$texto];
}

?>
<!DOCTYPE html>
<html lang="es">
<?php
include 'barcode.php';
?>

<head>
  <meta charset="utf-8">
  <title>EXAMEN DE LABORATORIO</title>
  <!-- <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" /> -->
  <style>
    * {
      margin: 0;
      padding: 0;
    }

    @page {
      margin: 204px 0.5cm 1cm 0.5cm !important; /* el margen top debe ser +20px del header */
      font-family: 'DejaVu Sans Condensed', sans-serif, Arial;
      font-size: 9px;
    }

    header {
      position: fixed;
      top: -184px;
      height: 184px;
    }

    footer {
      border: 1px solid red;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 30px; /* altura del footer */
      text-align: center;
      font-size: 12px;
    }

  </style>
</head>

<body>
  <header>
    <div style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td style="text-align: center;" width="45px" height="45px">
              <img src="images/escudo_republica_peru.png" alt="Peru Shield" width="40px">
            </td>
            <td width="50px" bgcolor="#dc2626" style="color: white; font-weight: bold; font-size: 12px; text-align: center;">PERÚ</td>
            <td width="90px" bgcolor="#374151" style="color: white; padding-left: 4px;">Ministerio Del Interior</td>
            <td width="120px" bgcolor="#4b5563" style="color: white; padding-left: 4px;">Policia Nacional del Perú</td>
            <td width="150px" bgcolor="#6b7280" style="color: white; padding-left: 4px;">Hospital Nacional PNP "LUIS N. SÁENZ"</td>
            <td bgcolor="#9ca3af" style="color: white; padding-left: 4px;">Departamento Patología Clínica</td>
            <td rowspan="3" width="50px" style="border-left: 1 px solid black;">
              <img src="images/logo_sanidad.png" alt="Hospital Logo" style="width: 50px; height: auto; display: block; position: absolute; margin-top: 5px;">
            </td>
          </tr>
          <tr>
            <td colspan="6" style="background-color: #006400; line-height: 5px;">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; padding: 2px 0;">
              INFORME DE RESULTADOS DE LABORATORIO CLÍNICO
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <?php if (count($resultados) != 0): ?>
      <div style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;">
        <table width="100%" style="padding: 3px 0 0 0;" cellpadding="0" cellspacing="0" border="0">
          <tbody>
            <tr>
              <td width="75%" style="padding-left: 5px; vertical-align: middle;">
                <div style="padding-bottom: 1px;">
                  <strong>Fecha de Registro:</strong>
                  <?php echo $resultados[0]->l_fecha_crea . ' ' . $resultados[0]->l_hora_crea; ?>
                </div>
                <div>
                  <strong>Fecha Atención:</strong>
                  <?php echo $resultados[0]->l_fecha; ?>
                </div>
              </td>
              <td width="25%" style="text-align: center;">
                <?php
                  barcode('images/laboratorio/barcode/' . $resultados[0]->o_numero . '.png', $resultados[0]->o_numero, 23, 'horizontal', 'code128', false);
                ?>
                <img src="images/laboratorio/barcode/<?php echo $resultados[0]->o_numero; ?>.png" />
                <span style="font-size: 14px; padding: 0px; margin: 0px;"><b><?php echo $resultados[0]->o_numero ?></b></span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 2px 0;">
          <tbody>
            <tr>
              <td width="90px" style="padding-left: 5px; text-align: left;"><b>PACIENTE:</b></td>
              <td colspan="3" style="text-align: left;"><?php echo $resultados[0]->h_nombres; ?></td>
              <td style="text-align: right;"><b>CONDICIÓN:</b></td>
              <td width="240px">&nbsp;
                <?php
                  $parentesco = isset($asegurado_data[0]) ? $asegurado_data[0]->tipo_beneficiario_nombre : '';
                  echo $parentesco;
                ?>
              </td>
            </tr>
            <tr>
              <td style="padding-left: 5px;"><b>Nº HISTORIA / DNI:</b></td>
              <td colspan="3"><?php echo $asegurado_data[0]->nro_historia . ' / ' . $resultados[0]->h_numero; ?></td>
              <td style="text-align: right;"><b>PROCEDENCIA:</b></td>
              <td>&nbsp;<?php echo $resultados[0]->pr_descripcion ?></td>
            </tr>
            <tr>
              <td style="padding-left: 5px;"><b>SEXO:</b></td>
              <td width="120px">
                <?php
                  if ($resultados[0]->h_sexo == "F"):
                    echo "FEMENINO";
                  elseif ($resultados[0]->h_sexo == "M"):
                    echo "MASCULINO";
                  endif;
                ?>
              </td>
              <td width="120px" style="text-align: right;"><b>EDAD:</b></td>
              <td width="85px">&nbsp;
                <?php
                  $edad = "";
                  $fnac_string = $resultados[0]->h_fecha_nacimiento;
                  list($dia, $mes, $anio) = explode("/", $fnac_string);
                  $fnac_date = $anio . "-" . $mes . "-" . $dia;

                  $ftoma_string = $resultados[0]->o_fecha_toma;
                  list($dia, $mes, $anio) = explode("/", $ftoma_string);
                  $ftoma_date = $anio . "-" . $mes . "-" . $dia;

                  $date1 = new DateTime($fnac_date);
                  $date2 = new DateTime($ftoma_date);
                  $interval = $date1->diff($date2);

                  if ($interval->y > 0): $edad = $interval->y . " Años";
                  elseif ($interval->m > 0): $edad = $interval->m . " Meses";
                  elseif ($interval->d > 0): $edad = $interval->d . " Días";
                  endif;
                  echo $edad;
                ?>
              </td>
              <td style="text-align: right;"><b>SERVICIO:</b></td>
              <td style="vertical-align: top; font-size: 7px;">&nbsp;<?php echo $resultados[0]->s_nombre ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <div style="border: 1px solid black;">
      <table width="100%">
        <tbody>
          <tr style="">
            <td width="300px" style="text-align:center;"><b>EXAMEN</b></td>
            <td width="100px"><b>RESULTADO</b></td>
            <td><b>VALORES DE REFERENCIA</b></td>
            <td width="90px" style="text-align:center;"><b>UNIDADES</b></td>
          </tr>
        </tbody>
      </table>
    </div>
  </header>

  <?php
  if ($group_servicio_profesional != null):
    $cant = 0;

    foreach ($group_servicio_profesional as $servicio_profesional):
  ?>
      <div style="border: 1px solid black;">
        <table width="100%" style="" border="0">
          <tr>
            <td style="text-align: center;">
              <b><?php echo $servicio_profesional->g_nombre; ?></b>
            </td>
          </tr>
        </table>
      </div>
      <table width="100%" border="0" style=""> <!-- background-color: yellow; -->
        <tbody>
          <tr>
            <td width="300px">&nbsp;</td> <!-- Examen -->
            <td width="100px">&nbsp;</td> <!-- Resultado -->
            <td>&nbsp;</td> <!-- Rango Referencial -->
            <td width="90px">&nbsp;</td> <!-- Unidad -->
          </tr>
          <?php

          $resultados_servicio_y_profesional = [];

          $resultados_servicio_y_profesional = array_filter($resultados, function ($row) use ($servicio_profesional) {
            return $row->g_nombre === $servicio_profesional->g_nombre && $row->usr_login === $servicio_profesional->usr_login;
          });

          $resultados_servicio_y_profesional = array_values($resultados_servicio_y_profesional);

          //$resultados_compuesto = $laboratorio_resultado_ne->MostrarResultadosReportexServicioCompuesto($servicio->h_numero, $servicio->g_nombre, $servicio->o_numero, $servicio->usr_login);

          $group_examen = [];

          foreach ($resultados_servicio_y_profesional as $item) {
            $e_nombre = $item->e_nombre;
            if (!isset($group_examen[$e_nombre])) {
              $group_examen[$e_nombre] = [];
            }
            $group_examen[$e_nombre][] = $item;
          }
          $fecha_val = "";

          // Mostrar el resultado
          foreach ($group_examen as $key_nombre_examen => $pruebas): // $examen as $nombre_examen => $pruebas

             // Examen con varios items de resultados                
              echo '
                <tr>
                  <td><b>' . $key_nombre_examen . '</b></td> <!-- Examen -->
                  <td></td> <!-- Resultado -->
                  <td></td> <!-- Rango Referencial -->
                  <td></td> <!-- Unidad -->
                </tr>
              ';

              $examen_observacion_general = "";
              $examen_contador_items = 0;
              foreach ($pruebas as $value): // Pruebas
                $fecha_val = $value->l_fecha_val . " " . $value->l_hora_val;
                $examen_observacion_general .= $value->o_observacion_val;
                $examen_contador_items ++;

                $rango_referencial = "";
                if ($value->rango_referencial == "0 - 0"):
                  $rango_referencial = "";
                else:
                  $rango_referencial = $value->rango_referencial;
                  $rango_referencial = str_replace("&", "&amp;", $rango_referencial);
                  $rango_referencial = str_replace("<", "&lt;", $rango_referencial);
                  $rango_referencial = str_replace(">", "&gt;", $rango_referencial);
                  $rango_referencial = str_replace("\n", " <br>", $rango_referencial);
                endif;

                $l_unidades = strval($value->l_unidades);

                echo '
                  <tr>
                    <td style="padding-left:5px; text-align:left; vertical-align: top;">' . $value->p_nombre . '</td>
                    <td style="text-align:left; vertical-align: top;"><b>' . $value->l_resultado . '</b></td>
                    <td style="text-align:left; vertical-align: top;">' . 
                      ObtenerRango($value->ra_texto, $value->l_ref_inf, $value->l_ref_sup, $value->p_comentario, $value->codigo_proveedor) . 
                    '</td>
                    <td style="text-align:center; vertical-align: top;">' . $l_unidades . '</td>
                  </tr>';

                if (!empty($value->p_item_comentario)):
                  $prueba_comentario_especifico = "";
                  $prueba_comentario_especifico = $value->rango_referencial;
                  $prueba_comentario_especifico = str_replace("&", "&amp;", $value->p_item_comentario);
                  $prueba_comentario_especifico = str_replace("<", "&lt;", $value->p_item_comentario);
                  $prueba_comentario_especifico = str_replace(">", "&gt;", $value->p_item_comentario);
                  $prueba_comentario_especifico = str_replace("\n", " <br>", $value->p_item_comentario);
                  
                  echo '<tr><td colspan="4" style="padding-left: 10px;">' . $value->p_item_comentario . '</td></tr>';
                endif;

                // Cuando es el ultimo item del Examen 
                if (count($pruebas) == $examen_contador_items):
                  if (!empty($examen_observacion_general)):
                    $examen_observacion_general = str_replace("&", "&amp;", $examen_observacion_general);
                    $examen_observacion_general = str_replace("<", "&lt;", $examen_observacion_general);
                    $examen_observacion_general = str_replace(">", "&gt;", $examen_observacion_general);
                    $examen_observacion_general = str_replace("\n", " <br>", $examen_observacion_general);

                    echo '<tr><td colspan="4" style="padding-left: 10px;">' . $examen_observacion_general . '</td></tr>';
                  endif;
                endif;

                $compuesto = $value->compuesto;
                $separado_por_salto = explode("\n", $compuesto);
                $texto1 = "";
                $texto2 = "";
                $texto3 = "";

                foreach ($separado_por_salto as $lineas):
                  // $solo_una_columna = false;
                  $texto1 = "";
                  $texto2 = "";
                  $texto3 = "";
                  $separado_por_comas = explotarLinea($lineas);

                  $texto1 = $separado_por_comas[0];

                  if (isset($separado_por_comas[1])):
                    $segunda_parte = $separado_por_comas[1];

                    // Segundo nivel de separación
                    $separado_por_comas2 = explotarLineaSegundaParte($segunda_parte); // Separamos lógica para mantener limpio

                    $texto2 = isset($separado_por_comas2[0]) ? $separado_por_comas2[0] : '';
                    $texto3 = isset($separado_por_comas2[1]) ? $separado_por_comas2[1] : '';
                  endif;

                  $texto1 = str_replace("&", "&amp;", $texto1);
                  $texto1 = str_replace("<", "&lt;", $texto1);
                  $texto1 = str_replace(">", "&gt;", $texto1);
                  $texto1 = str_replace("\n", "<br>", $texto1);

                  $texto2 = str_replace("&", "&amp;", $texto2);
                  $texto2 = str_replace("<", "&lt;", $texto2);
                  $texto2 = str_replace(">", "&gt;", $texto2);
                  $texto2 = str_replace("\n", "<br>", $texto2);

                  $texto3 = str_replace("&", "&amp;", $texto3);
                  $texto3 = str_replace("<", "&lt;", $texto3);
                  $texto3 = str_replace(">", "&gt;", $texto3);
                  $texto3 = str_replace("\n", "<br>", $texto3);

                  echo '
                    <tr>
                      <td style="padding-left:5px; text-align:left;" >' . $texto1 . '</td>
                      <td style="text-align:left;">' . $texto2 . '</td>  
                      <td style="text-align:left;">' . $texto3 . '</td>
                      <td></td>
                    </tr>';

                endforeach; // $separado_por_salto

                if ($value->comentarios != null):

                  $comentarios = str_replace("&", "&amp;", $value->comentarios);
                  $comentarios = str_replace("<", "&lt;", $comentarios);
                  $comentarios = str_replace(">", "&gt;", $comentarios);
                  $comentarios = str_replace("\n", " <br>", $comentarios);

                  echo '
                    <tr>
                      <td colspan="2">' . $comentarios . '</td>
                        <td style="text-align:left;" ></td>
                      <td style="text-align:left;" ></td>
                    </tr>';

                endif;

              endforeach; // $pruebas
          endforeach; // $group_examen
          ?>

          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4">
              <table width="100%">
                <tr>
                  <td width="60%" style="vertical-align:middle;">
                    <table width="100%">
                      <tbody>
                        <tr>
                          <td style="text-align:right;"><b>Fecha Validación:</b></td>
                        </tr>
                        <tr>
                          <td style="text-align:right;"><?php echo $fecha_val; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td width="15%" style="text-align: right;">
                    <img src="images/sello_deppacli.png" width="100px"/>
                  </td>
                  <td width="25%" style="text-align: left; vertical-align:middle;">
                    
                      <?php
                        // $data_firma = $laboratorio_resultado_ne->ObtenerFirmaResultadoM($servicio->h_numero, $servicio->o_numero, $servicio->usr_login);
                        $data_firma = $resultados_servicio_y_profesional;
                        $base64 = null;
                        $validador_img = false;

                        if (!empty($data_firma)) {

                          $rutaImagenSalida = "images/laboratorio/firma/" . $data_firma[0]->usr_login . ".png";

                          if (file_exists($rutaImagenSalida) && is_readable($rutaImagenSalida)) {
                            $rutaFinal = $rutaImagenSalida; // Usar la imagen guardada
                          } else {

                            if (!empty($data_firma[0]->usr_image)) {

                              // Validar si la imagen es Base64
                              if (base64_encode(base64_decode($data_firma[0]->usr_image, true)) === $data_firma[0]->usr_image) {
                                $validador_img = true;
                              }

                              // $imagenEnBase64 = $data_firma[0]->usr_image;
                              // $rutaImagenSalida = "images/laboratorio/firma/" . $data_firma[0]->usr_login . ".png";

                              // Verificar si el archivo ya existe
                              /*if (file_exists($rutaImagenSalida) && is_readable($rutaImagenSalida)) {
                                        $rutaFinal = $rutaImagenSalida; // Usar la imagen guardada
                                    } else {*/
                              if ($validador_img) {

                                $imagenEnBase64 = $data_firma[0]->usr_image;
                                // Decodificar Base64 y guardar la imagen
                                $base64 = base64_decode($imagenEnBase64);
                                $bytes = file_put_contents($rutaImagenSalida, $base64);

                                if ($bytes !== false) {
                                  $rutaFinal = $rutaImagenSalida; // Usar la imagen guardada
                                } else {
                                  $rutaFinal = "data:image/png;base64," . $imagenEnBase64; // Mostrar en Base64 si falla el guardado
                                }
                              } else {
                                echo "No es Base64.";
                                $rutaFinal = "/ruta_de_imagen_por_defecto.png"; // Imagen por defecto si no es Base64
                              }
                            } else {
                              // echo $servicio_profesional->usr_name;
                              $rutaFinal = "/ruta_de_imagen_por_defecto.png";
                            }
                          }
                        } else {
                          // echo $servicio->usr_name;
                          $rutaFinal = "/ruta_de_imagen_por_defecto.png";
                        }

                        if ($rutaFinal != "/ruta_de_imagen_por_defecto.png" && file_exists($rutaFinal)) {

                          // Verificamos que realmente sea una imagen y esté sana
                          $esImagenValida = @getimagesize($rutaFinal);

                          if ($esImagenValida !== false) {
                            echo '<img id="foto_paciente" src="' . $rutaFinal . '" width="150">';
                          } else {
                            echo '<img id="foto_paciente" src="/ruta_de_imagen_por_defecto.png" width="150">';
                          }
                        } else {
                          echo '<img id="foto_paciente" src="/ruta_de_imagen_por_defecto.png" width="150">';
                        }
                      ?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
      <?php
      $cant++;

      //if (count($group_servicio_profesional) > $cant):
      ?>
        <!-- <footer style="display: none;">
          <div class="footer">
            <table class="footer-table" border="0">
              <tr>
                <td class="center" style='font-size: 11px;line-height:12px;'>
                  El resultado de este examen no constituye diagnóstico. Debe ser<br>
                  complementado con la interpretación clínica por su médico tratante.
                </td>
                <td width="120px" style="text-align: right;">
                  Fecha de Informe:
                </td>
                <td width="150px" class="left">
                  <table>
                    <tbody>
                      <tr>
                        <td><?php echo date('d/m/Y') ?></td>
                      </tr>
                      <tr>
                        <td><?php echo date('H:i:s') ?></td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </footer> -->
  <?php
      // endif;
    endforeach; //fin del foreach servicios
  endif;
  ?>

  <script type="text/php">
    if (isset($pdf)) {
      $fontMetrics = new Font_Metrics(); 

      $x = 555;
      $y = 815;
      $text = "{PAGE_NUM} / {PAGE_COUNT}";
      $font = $fontMetrics->get_font("helvetica", "normal");
      $size = 8;
      $color = array(0,0,0);
      $word_space = 0.0;  //  default
      $char_space = 0.0;  //  default
      $angle = 0.0;   //  default
      $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

      // Texto en dos líneas a la izquierda
      $x_left = 20; // margen izquierdo
      $y_left = 815; // posición vertical de la primera línea
      $texto1 = html_entity_decode("El resultado de este examen no constituye diagnóstico. Debe ser", ENT_QUOTES, 'UTF-8');
      $texto2 = html_entity_decode("complementado con la interprestación clínica por su médico tratante.", ENT_QUOTES, 'UTF-8');

      $pdf->page_text($x_left, $y_left, $texto1, $font, $size, $color);
      $pdf->page_text($x_left, $y_left + 8, $texto2, $font, $size, $color); // +12 mueve la segunda línea hacia abajo

      $x_print = 380; // margen izquierdo
      $y_print = 815;
      $texto3 = html_entity_decode("Fecha de impresión: ", ENT_QUOTES, 'UTF-8');
      $fechaHora = date("d/m/Y H:i:s"); 
      $pdf->page_text($x_print, $y_print, $texto3 . $fechaHora, $font, $size, $color);
    }
  </script>
</body>

</html>