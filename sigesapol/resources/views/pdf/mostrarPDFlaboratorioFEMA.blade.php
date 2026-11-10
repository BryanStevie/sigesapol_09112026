<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PDF - RESULTADOS DE LABORATORIO</title>
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
                <h2> SIGESAPOL - FEMA - LABORATORIO</h2>
            </td>
            <td colspan="1" class="td_left logo">
                <right><img src="images/logo_sanidad.png" width="60" /></right>
            </td>
        </tr>
    </tbody>
</table>

<table>
    <tr>
        <th colspan="1"></th>
        <th colspan="4" style="text-align: left;">
            <h3>
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="text-align: left; width: 33%; font-size: 14px;">FECHA DE TOMA DE MUESTRA : <?php echo $personal->laboratorio_updated_at; ?></ </td>
                        
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 33%; font-size: 14px;"> PACIENTE : <?php echo $personal->nombre; ?> <?php echo $personal->paterno; ?> <?php echo $personal->materno; ?> </td>
                        <td style="text-align: center; width: 34%; font-size: 14px;"></td>
                        <td style="text-align: right; width: 33%; font-size: 14px;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 33%; font-size: 14px;"> GRADO : @if($personal->grado!="")<?php echo $personal->grado ;?> @endif </td>
                        <td style="text-align: center; width: 34%; font-size: 14px;"></td>
                        <td style="text-align: right; width: 33%; font-size: 14px;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 33%; font-size: 14px;">DNI: <?php echo $personal->nro_doc_ident; ?></td>
                        <td style="text-align: center; width: 34%; font-size: 14px;">SEXO: <?php echo $personal->sexo; ?></td>
                        <td style="text-align: right; width: 33%; font-size: 14px;">EDAD: <?php echo $personal->edad; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 33%; font-size: 14px;">  @if ($personal->grado!="") CIP : <?php echo $personal->carnet; ?> @endif </td>
                        <td style="text-align: center; width: 34%; font-size: 14px;"></td>
                        <td style="text-align: right; width: 33%; font-size: 14px;"></td>
                    </tr>
                </table>
            </h3>
        </th>
    </tr>
    <tr>
        <th colspan="6">
            <h3> EXAMEN DE LABORATORIO </h3>
        </th>
    </tr>
    <tr>   
        <th colspan="2">
            <h3> ANALISIS</h3>
        </th>  
        <th colspan="2">
            <h3> RESULTADOS DE ANALISIS </h3>
        </th>  
        <th colspan="2">
            <h3> RANGOS DE REFERENCIA</h3>
        </th>  
    </tr>
    <tr>   
        <th colspan="2">
            <h3> GLUCOSA : </h3>
        </th>  
        <th colspan="2">
            <h3> <?php echo $laboratorio->glucosa; ?>  mg/dl </h3>
        </th>  
        <th colspan="2">
            <h3> Normal: 74 - 115 mg/dl </h3>
        </th>  
    </tr>
    <tr>   
        <th colspan="2">
            <h3> COLESTEROL TOTAL :</h3>
        </th>  
        <th colspan="2">
            <h3> <?php echo $laboratorio->colesterol; ?>  mg/dl </h3>
        </th>  
        <th colspan="2">
            <h3> Normal: menor a 200 mg/dl<br>
                Moderado: entre 200 a 240 mg/dl<br>
                Alto : mayor a 240 mg/dl
            </h3>
        </th>  
    </tr>
    <tr>   
        <th colspan="2">
            <h3> TRIGLICERIDOS :</h3>
        </th>  
        <th colspan="2">
            <h3> <?php echo $laboratorio->trigliceridos; ?>  mg/dl</h3>
        </th>  
        <th colspan="2">
            <h3> Normal: menor a 150 mg/dl<br>
                Moderado: entre 150 a 230 mg/dl<br>
                Alto : mayor a 230 mg/dl
            </h3>
        </th>  
    </tr>
    <br>
    </table>
    <table>
    <tr>
        <th colspan="6">
            <h3> INMUNOLOGÌA</h3>
        </th>
    </tr>
    <tr>   
        <th colspan="2">
            <h3> ANALISIS</h3>
        </th>  
        <th colspan="2">
            <h3> RESULTADOS DE ANALISIS </h3>
        </th>  
 
    </tr>
    <tr>   
        <th colspan="2">
            <h3> HIV 1-2 : <br>(Prueba rápida-cualitativo)</h3>
        </th>  
        <th colspan="2">
            <h3> <?php echo $laboratorio->hiv; ?> </h3>
        </th>  
    </tr>
    <tr>   
        <th colspan="2">
            <h3> RPR – CARBON :</h3>
        </th>  
        <th colspan="2">
            <h3> <?php echo $laboratorio->vdrl; ?> </h3>
        </th>  
    </tr>
    @if ($personal->edad > 39):
    <tr>   
        <th colspan="2">
            <h3> PSA* (Antígeno prostático especifico) :<br>Prueba rápida-cualitativo</h3>
        </th>  
        <th colspan="2">
            <h3> <?php echo $laboratorio->columna; ?> </h3>
        </th>  
    </tr>
    @endif:
</table>
<table>
    <tr>
        <th colspan="3">
            <h3><strong>PERSONAL RESPONSABLE:</strong> <?php echo $profesional->nombre; ?>, <?php echo $profesional->paterno; ?> <?php echo $profesional->materno; ?> </h3>
        </th> 
    </tr>
    <tr>
        <th colspan="3">
            <h3><strong>FECHA RESULTADOS:</strong> <?php echo $laboratorio->created_at; ?> </h3>
        </th> 
    </tr>
</table>

        <table>
            <tr>
              <th>
            </h3>*Análisis solo aplica para el personal masculino mayores de 40 años. En caso de salir Positivo, se sugiere pasar por consultorio de urología, <br>para el   respectivo descarte y confirmación.</h3>
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
