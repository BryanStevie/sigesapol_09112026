<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>LISTA DE HISTORIAS - SERVICIOS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/pdf2.css"  />
        <style>
			

            .td_left{
                text-align: left !important;
            }
            .td_ancho_n{
                width: 4%;
                text-align: left
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
                font-size: 12px !important; 
                font-family: Roboto,"Helvetica Neue",Helvetica,Arial,sans-serif;
            }
            .ancho_producto{
                width: 45%;
            }
            .titulos{
                font-weight: bold;
				padding:0px 10px;
            }
            h2{
                font-size: 25px !important
            }
            .titulo_principal{
                 width: 80%;
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
    <body style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px">
        <table style="margin:0px;padding:0px;padding-bottom:10px">
            <tbody>
                <tr>
                    <td colspan="2" class="td_left logo">
                        <img src="images/logo_sanidad.png" width="60" />
                    </td>
                    <td colspan="2" class="titulo_principal">
                        <?php if($movimiento_historias->estado==0) : ?>
                            <h3><font color='red'>
                                LISTA DE HISTORIAS - SERVICIOS INVALIDADA
                                </font>
                            </h3>
                        <?php else: ?>
                            <h3>LISTA DE HISTORIAS - SERVICIOS</h2>
                        <?php endif; ?>
                    </td>
                    <td colspan="2" class="titulos" width="12%" style="padding-right:0px">
                        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            Nro Orden <br/><?php echo $movimiento_historias->nro_movimiento; ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo date("d-m-Y",strtotime($movimiento_historias->fecha));?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $origen->nombre?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php 
						echo $movimiento_historias->responsable_origen;
						?>
					</div>
				</td>
            </tr>
            <tr>
                <td class="td_center">FECHA</td>
                <td class="td_center">LUGAR DE ORIGEN</td>
                <td class="td_center">RESPONSABLE DE LUGAR ORIGEN</td>
            </tr>
        </table>
    
       <table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						{{$destino->nombre}}
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php 
                                    switch ($movimiento_historias->id_area) { 
                                      case 1: echo "CONSULTORIO 1"; break;
                                      case 2: echo "CONSULTORIO 2"; break;
                                      case 3: echo "CONSULTORIO 3"; break;
                                    }
                        ?>
					</div>
				</td>
                <td class="grado titulos">
					<div class="rectangulo" style="border: 1px solid #A4A4A4;">
						<?php echo $movimiento_historias->responsable_destino;?>
					</div>
				</td>
            </tr>
            <tr>
                <td class="td_center">LUGAR DE DESTINO</td>
                <td class="td_center">AREA</td>
                <td class="td_center">RESPONSABLE DE LUGAR DESTINO</td>
            </tr>
        </table>
		<table border="0px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php 
                                    switch ($movimiento_historias->id_turno) { 
                                      case 1: echo "M"; break;
                                      case 2: echo "T"; break;
                                      case 3: echo "N"; break;
                                      case 4: echo "MD"; break;
                                    }
                            ?>
                    </div>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        <?php 
                                switch ($movimiento_historias->id_tipo_citado) { 
                                  case 1: echo "DIRECTA 1"; break;
                                  case 2: echo "INDIRECTA 2"; break;
                                  
                                }
                        ?>
                    </div>
                </td>
                <td class="grado titulos">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                        {{$movimiento_historias->observacion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td_center">TURNO</td>
                <td class="td_center">TIPO CITADO</td>
                <td class="td_center">OBSERVACION</td>
            </tr>
        </table>
		
		<?php $i=1;?>
        <table  style="font-size: 11px">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo Doc</th>
                    <th>Nro Documento</th>
                    <th>Paciente</th>
                    <th>Nro Historia</th>
                </tr>
            </thead>
            <tbody>
               <?php $x=1; ?>
                @foreach($historias_asignadas as $hc)
                <tr>
                    <td><?php echo $x++; ?></td>
                    <td>{{$hc->tipo_doc_ident}}</td>
                    <td>{{$hc->nro_doc_ident}}</td>
                    <td class="td_left">{{$hc->nombre}}, {{$hc->paterno}} {{$hc->materno}}</td>
                    <td>{{$hc->nro_historia}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

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
		<?php if($movimiento_historias->estado==0) : ?>
<font color='red'> * SE HA INVALIDADO LA PRESENTE ORDEN - Fecha Registro <?php echo $movimiento_historias->updated_at ; ?> </font>
        <?php else: ?>
        * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE ORDEN - Fecha Registro <?php echo $movimiento_historias->updated_at ; ?>
        <?php endif; ?>
		</footer>
    </body>
</html>