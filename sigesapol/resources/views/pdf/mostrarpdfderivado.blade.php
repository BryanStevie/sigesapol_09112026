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
                        <right><img src="images/logo_pnp.png" width="70" /></right>
                    </td>
                    <td colspan="4" class="td_left logo">
                       <h2> SIGESAPOL - FEMA</h2>
                    </td>
                    <td colspan="1" class="td_left logo">
                        <right><img src="images/logo_sanidad.png" width="80" /></right>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="6" class="titulo_principal" align="center">
                        <h2>FICHA DE DERIVACION</h2><h4>FICHA MEDICA GENERAL <?php echo date("Y",strtotime($personal->created_at));?></h4><h4>“IPRESS PNP” -  {{$locales->nombre}}</h4>
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
           
                @if(count($derivado)>0)
                <tr>
                    <th colspan="6">
                        <h2> DERIVADO POR EPIDEMILIOGIA : </h2>
                    <th>
                </tr>
                <tr>
                    @if ($verttatu>0)
                      <th colspan="2">
                    @else
                      <th colspan="3">
                    @endif
                        <h2> DIAGNÓSTICO: <p>
                            @if($deri ='1')
                                @foreach($derivado as $deri)
                                <?php echo $deri->codigo;?><p>
                                @endforeach
                            @endif
                        </h2>
                    </th>        
                    @if ($verttatu>0)
                      <th colspan="2">
                    @else
                      <th colspan="3">
                    @endif
                    <h2> SERVICIO 1: <p>
                        @if($deri ='1')
                            @foreach($derivado as $deri)
                            <?php echo $deri->consulto1;?><p>
                            @endforeach
                        @endif
                    </h2>
                    </th>  
                    @if ($verttatu>0)
                    <th colspan="2">
                    <h2> SERVICIO 2: <p>
                        @if($deri ='1')
                            @foreach($derivado as $deri)
                            <?php echo $deri->consulto2;?><p>
                            @endforeach
                        @endif
                    </h2>
                    </th> 
                    @endif 
                </tr>
                @endif
        </table>
        <br/><br/>
        <table>
          <tr>
              <td>
                <h5> Al termino de su tratamiento y/o estabilización deberá retornar al Área de Epidemiologia UNIEVMED DIRSAPOL PNP, con la constancia de atención original y copia, para el levantamiento de la OBSERVACION.</h5>
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
