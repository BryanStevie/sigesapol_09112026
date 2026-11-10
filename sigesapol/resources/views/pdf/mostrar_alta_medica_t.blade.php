<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PDF - ALTA MEDICA</title>
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
                    <td colspan="2" class="td_left logo">
                       
                        <right><img src="images/logo_sanidad.png" width="60" /></right>
                    </td>
                    <td colspan="6" class="td_left logo">
                       <h2> ALTA - MEDICA</h2>
                    </td>
                    <td colspan="2" class="td_left logo">
                    <right><img src="images/logo_pnp.png" width="60" /></right>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="10" class="titulo_principal" align="center">
                    <b>EPICRISIS - TRASLADO</b>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
                <tr>
                    <th colspan="1">
                    <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p><?php echo $prestacion->tipo_atencion?></p>
                    </th>  
                    <th colspan="1">
                    <label for="dni_beneficiario">FECHA DE ATENCION</label>
                        <p><?php echo $prestacion->fecha_atencion?></p>
                    </th>    
                    <th colspan="1">
                    <label>PRIORIDAD</label>
                        <p><?php echo $emergencia->id_prioridad?></p>
                        </th>  
                 </tr>
                 <tr>
                 <th colspan="1">
                    <label>AREA</label>
                        <p><?php echo $emergencia->id_triaje?></p>
                        </th> 
                <th colspan="1">
                    <label>N° HISTORIA CLINICA</label>
                        <p><?php echo $prestacion->nro_historia?></p>
                        </th> 
                <th colspan="1">
                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                        <p><?php echo $prestacion->establecimiento?></p>
                        </th>  
                 </tr>
                 
                <tr>
                <th colspan="6">
                <br><label for="tipo">INFORMACIÓN DEL PACIENTE</label>
                <br>
                </th>
                </tr>
                <tr>
                <br>
                    <th colspan="1">
                    <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->tipo_doc_ident?></p>
                        </th>    
                    <th colspan="1">
                    <label for="dni_beneficiario">N° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->nro_doc_ident?></p>
                        </th>
                        <th colspan="1">
                    <label for="dni_beneficiario">PARENTESCO</label>
                        <p><?php echo $prestacion->parentesco?></p>
                    </th> 
                </tr>
                <tr>    
                    <th colspan="1">
                    <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $prestacion->sexo?></p>
                    </th>   
                    <th colspan="1">
                    <label for="dni_beneficiario">TELEFONO</label>
                        
                    </th>  
                    <th colspan="1">
                    <label for="dni_beneficiario">FECHA NACIMIENTO</label>
                        
                    </th> 
                </tr>
                <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title"><b>DIAGNOSTICOS  DE EGRESO CABECERA </b></h3>
                            </div>
                            
                                     
                                        <div class="panel-heading">
                                          
                                        </div>
                                        <div class="panel-body">
                                          <table>
                                            <tr>
                                              <td>
                                                <button type="button" id="addDiagnostico" class="btn btn-primary"
                                                  data-toggle="modal" data-target="#addClassModal">Agregar
                                                  Diagnostico</button>
                                              </td>
                                            </tr>
                                          </table>
                                          <br />
                                          <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                                            style="margin-top:5px;">
                                            <thead>
                                              <tr>
                                                <th width="90%"></th>
                                                <th width="10%"></th>
                                              </tr>
                                            </thead>
                                            <tfoot>
                                            </tfoot>
                                            <tbody>
                                            </tbody>
                                          </table>
                                        </div>
                                     
                              
                        </div>      
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
