<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PDF - ANEXO_03</title>
        <link rel="stylesheet" type="text/css" href="assets/css/pdf2.css"  />
        <style>
            body{
                background-color:white !important;
            }
            .td_left {
                text-align: left !important;
            }
    
            .td_ancho_n {
                width: 4%;
                text-align: center
            }
      
            td {
                padding-left: 2px !important;
                background-color:white !important;
            }
    
            table {
                background-color:white !important;
                font-size: 10px !important;
                font-family: Arial, Helvetica, sans-serif; 
            }
     
    
            h2 {
                font-size: 20px !important;
                font-family: Arial, Helvetica, sans-serif;
            }
    
            .titulo_principal {
                width: 60%;
            }
    
            .logo {
                width: 600%;
            } 

            .rectangulo {
                padding: 2px
            }

            .titulos {
                font-weight: bold; 
            }
    
            footer {  
                text-align: left;
                border: 0px;
                margin-left: 10px;  
            }
            .tamanio {
              width: 50%;
              height: 5%;
            }
            .texto{
              font-size: 20px !important;
              font-family: Arial, Helvetica, sans-serif;
              padding: 3px;
            }
        </style>
    </head>
    <body  style="font-family: arial;padding:-10px;font-family:Arial, Helvetica, sans-serif;">
        <table style="margin:0px;padding:0px;">
            <tbody >
                <tr>
                  <td>
                    <img src="images/logo_sanidad.png" class="logo" />
                  <td>  
                </tr>
                <tr>
                  <td><br>
                    <div  class="texto">
                      DIRECCION DE SANIDAD POLICIAL      
                    </div>
                    <div  class="texto">
                      FORMATO DE CITA PARA PACIENTE
                    </div>
                  <div  class="texto">
                    ANEXO N°03
                  </div>
                  <br><br>
                  </td>
                </tr>
                
            </tbody>
        </table> 
        
        <table border="1px" style="padding-top: 0px;"> 
            <tr>
                <td class="tamanio texto">
                    <div>
                        APELLIDOS Y NOMBRES DEL PACIENTE
                    </div>
                </td>
                <td class="tamanio texto">
                    <div>
                       <?php echo $paciente;?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tamanio texto">
                    <div>
                       NOMBRE DE IPRESS DE ORIGEN
                    </div>
                </td>
                <td class="tamanio texto">
                    <div>
                       <?php echo $ipress_origen;?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tamanio texto">
                    <div>
                        NOMBRE DE IPRESS DE DESTINO
                    </div>
                </td>
                <td class="tamanio texto">
                    <div>
                       <?php echo $ipress_destino;?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tamanio texto">
                    <div>
                      SERVICIO DONDE SERA ATENDIDO
                    </div>
                </td>
                <td class="tamanio texto">
                    <div>
                       <?php echo $servicio_referencia;?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tamanio texto">
                    <div>
                        NOMBRE DEL PROFESIONAL DE SALUD QUE LO ATENDERA
                    </div>
                </td>
                <td class="tamanio texto">
                    <div>
                    <?php echo $medico_atencion_cita;?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tamanio texto">
                    <div>
                       DIA QUE SERA ATENDIDO
                    </div>
                </td>
                <td class="tamanio texto">
                    <div>
                    <?php echo date('Y-m-d', strtotime($detalle_cita->fecha_hora)); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tamanio texto">
                    <div>
                      HORA QUE SERA ATENDIDO
                    </div>
                </td>
                <td class="tamanio texto">
                    <div>
                       <?php echo $hora;?>
                    </div>
                </td>
            </tr>
            <tr>
              <td class="tamanio texto">
                  <div>
                    N° DE CONSULTORIO O SALA DE PROCEDIMIENTO DONDE SERA ATENDIDO
                  </div>
              </td>
              <td class="tamanio texto">
                  <div>
                     <?php echo $numero_consultorio_sala_atencion;?>
                  </div>
              </td>
          </tr>
            
        </table>
        <table style="margin:0px;padding:0px;">
          <tbody >
              <tr>
                <td class="td_left texto">
                  FECHA: <?php echo $fechaActual;?>
                </td>        
              </tr>
              <tr>
                <td class="td_left texto">
                TERMINALISTA:  <?php echo $responsable_cita->name .', '.$responsable_cita->paterno .' '.$responsable_cita->materno;?>
                </td>          
              </tr>
          </tbody>
      </table> 
        
       

		<?php $i=1;?>

        <footer>
            <script type="text/php">
            if (isset($pdf)) {
                $x = 760;
                $y = 570;
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
		
		</footer>
    </body>
</html>
