<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PDF - CIERRE DE FICHA FEMA</title>
        <link rel="stylesheet" type="text/css" href="assets/css/pdf2.css"  />
        <style>
            table{
                font-size: smaller;
                font-family: Roboto,"Helvetica Neue",Helvetica,Arial,sans-serif;
            }
            .ancho_producto{
                width: 45%;
            }
            .titulos{
                font-weight: bold;
				padding:0px 10px;
            }
            h1{
                font-size: 20px;
            }
            h2{
                font-size: 80%;
            }
            .titulo_principal{
                 width: 100%;
            }
            .logo{
                 width: 20%;
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
        </style>
    </head>
    <body style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px">
        <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tbody>
                <tr>
                    <td colspan="1" class="td_left logo">
                        <right><img src="images/logo_pnp.png" width="60" /></right>
                    </td>
                    <td colspan="4" class="td_left logo">
                       <h2> SIGESAPOL - FEMA</h2>
                    </td>
                    <td colspan="1" class="td_left logo">
                        <right><img src="images/logo_sanidad.png" width="60" /></right>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="6" class="titulo_principal" align="center">
                        <h2>CONSTANCIA DE CONCLUSIÓN DE PROCESO</h2><h4>FICHA MEDICA GENERAL <?php echo date("Y",strtotime($personal->created_at));?></h4><br>
                        <h4>IPRESS PNP DE APERTURA:  {{$locales->nombre}}</h4>
                        <h4>IPRESS PNP DE CIERRE:  {{$localescierre->nombre}}</h4>
                   
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
                <tr>
                    <th colspan="6">
                        <h2>@if($personal->grado!="")<?php echo $personal->grado ;?> : @endif <?php echo $personal->nombre; ?> <?php echo $personal->paterno; ?> <?php echo $personal->materno; ?></h2>
                        <h3>@if ($personal->grado!="") CIP : <?php echo $personal->carnet; ?> @endif DNI : <?php echo $personal->nro_doc_ident; ?> </h3>
                    </th>  
                </tr>
                <tr>
                    <th colspan="6">
                    <h2> FECHA DE INICIO: <?php echo $personal->created_at;?></h2>
                    <h2> FECHA DE TERMINO: <?php echo $personal->cierre_updated_at;?></h2>
                    </th>    
                </tr>
               
        </table>

        <table>
            <tr>
              <th>
             <!-- <h2>APTITUD: <b><?php echo $personal->aptitud_maspe;?> </b></h2></h3>CONFORME LO REGISTRADO POR LA JUNTA DE SANIDAD PNP, EN EL SIGCP-DIRREHUM</h3> -->
             <h2>APTITUD: <b><?php echo $personal->aptitud_maspe;?> </b></h2></h3>CONFORME LO REGISTRADO EN EL SIGCP DIRREHUM</h3>
              </th>    
          </tr>   
        </table>

        <table>
            <tr>
                
                <td colspan="1">
                  @if ($verttatu>0)
                    <h4> Al termino de su tratamiento y/o estabilización deberá retornar al Área de Epidemiologia UNIEVMED DIRSAPOL PNP,<br> con la constancia de atención original y copia, para el levantamiento de la OBSERVACION.</h4>
                  @endif
                  </td>     
            </tr> 
            <tr>
              <th colspan="1">
                  <h3> <p>La presente no constituye certificacion de aptitud.<p> La aptitud es automatica al termino del cronograma establecido por DIRREHUM </h3>
              </th> 
            </tr> 
        </table>

        <table  style="margin:0px;padding:0px;" >
      <tr style="font-size: 6px !important;">
          <td style="text-align: left">  
      <span style="display: block; font-size: 0.9rem; text-align: right; margin: 0; padding-top: 0.3rem;text-align: right"> FECHA DE IMPRESION: <b>{{$fechaActual}} {{$horaActual}}</b></span>
          </td>   
      </tr>   
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
