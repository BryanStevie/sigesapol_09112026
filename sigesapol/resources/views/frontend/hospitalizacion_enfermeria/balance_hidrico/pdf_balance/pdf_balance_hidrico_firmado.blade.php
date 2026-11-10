<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>BALANCE HIDRICO</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
       
    <style>
        @page {
          margin-left: 0.10in;
          margin-right: 0.10in;
          margin-top: 0.40in;
          margin-bottom: 0.40in;
          size: 8.6in 14.9in;
        }

        td {
            padding-left: 2px !important;
        }

        table {
            font-size: 11px;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        h2 {
            font-size: 15px !important
        }

        .logo {
            width: 15%;
        } 

        footer {
            border: 0px;
            margin-left: 10px;
            font-size: 8px !important;
            font-family: Arial, Helvetica, sans-serif
        }
    </style>
   
</head>

<body >
  <table style="margin-top:70px;padding:0px;padding-bottom:0px;">
    <tbody>
        <tr>
            <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px">
                <?php echo $url_descarga?>
            </td>
            <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px">
                <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
            </td>
        </tr>
    </tbody>
</table>
    <table style="margin:0px;padding:0px;padding-bottom:-10px;">
        <tbody>
          <tr>
            <td width="10%">
              <img src="images/logo_sanidad.png" width="40rem"/>
            </td>
            <td  class="" width="90%">
              <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{$nombre_establecimiento}}</span>
              <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE ENFERMERIA</span>
            </td>
            <td width="30%">
            
            </td> 
          </tr>
          <tr>
            <td width="10%">
             
            </td>
            <td  class="" width="90%">
             
            </td>
            <td width="30%">
              <table>
                <tbody>                                                  
                  <tr >
                      <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span></td>
                      <td width="70%" style="text-align:left;"><span style="margin: 0; font-size: 1rem; font-weight: bold;">  
                        <b>{{$datos_atencion[0]->historia_clinica}}</b>
                      </span></td>
                  </tr>
                  <tr>
                      <td width="50%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                      <td width="70%" style="text-align:left;" ><span style="margin: 0; font-size: 1rem; font-weight: bold;">  
                        <b>{{$datos_atencion[0]->historia_clinica}}</b>
                      </span>
                      </td>
                  </tr>
                  <tr>
                      <td width="100%" style="text-align:left;width: 100px;" ><span style="margin: 0; font-size:1rem; font-weight: bold;">N° {{$tipo_atencion}}:</span></td>
                      <td width="100%" style="text-align:left;"> 
                        <b style="font-size: 1rem;">{{$datos_atencion[0]->nro_emergencia}}</b>
                      </td>
                  </tr>
                </tbody>
            </table>
            </td> 
          </tr>
        </tbody>
      </table>
    
      <table style="margin:0px;padding-bottom:-20px;">
        <tbody>
            <tr> 
                <td colspan="2" width="50%" >
                    <span style="margin: 0; font-weight: bold; font-size: 20px !important; padding-bottom: -20px !important;">
                        BALANCE HIDRICO
                    </span><br>
                    <span style="margin: 0; font-weight: bold; font-size: 15px !important;"> 
                      {{str_pad($balance_cabecera->id, 8, '0', STR_PAD_LEFT)}}
                    </span>
                </td> 
            </tr>
        </tbody>
      </table>
      <br><br>
      <b style="font-size: 14px !important;">DATOS DEL PACIENTE</b>
      <table  style="margin:0px;padding-bottom:-6px;" >
        <tr> 
          <td width="80%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              DNI: 
              <b>{{$datos_atencion[0]->dni}}</b> 
            </span>
          </td>  
          <td width="100%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
            SEXO: 
              <b> {{$datos_atencion[0]->sexo}} </b> 
            </span>
          </td> 
          <td width="80%" style="vertical-align:top;" colspan="2">
            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              EDAD: 
              <b> {{$datos_atencion[0]->asegurado_edad_anios}} AÑOS CON {{$datos_atencion[0]->asegurado_edad_meses}} MESES Y {{$datos_atencion[0]->asegurado_edad_dias}} DÍAS</b>
            </span>
          </td> 
      </tr>
        <tr> 
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                F.NACIMIENTO: 
                <b><?php echo date('d/m/Y',strtotime($datos_atencion[0]->asegurado_fecha_nacimiento))?></b> 
              </span>
            </td>  
            <td width="100%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                TIPO BENEFICIARIO: 
                <b> {{$datos_atencion[0]->asegurado_tipo_beneficiario}} </b> 
              </span>
            </td> 
              
            
        </tr>
        <tr> 
          <td width="40%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              <?php if(!empty($datos_atencion[0]->asegurado_talla)){ echo 'TALLA:';?>
              <b>{{$datos_atencion[0]->asegurado_talla}}</b> 
              <?php } ?>
            </span>
          </td>  
          <td width="40%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              <?php if(!empty($datos_atencion[0]->asegurado_peso)){ echo 'PESO:';?> 
              <b>{{$datos_atencion[0]->asegurado_peso}}</b> 
              <?php } ?>
            </span>
          </td>  
          <td width="80%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
             ACOMPAÑADO POR: 
              <b><?php if(!empty($datos_atencion[0]->asegurado_acompañante)){echo $datos_atencion[0]->asegurado_acompañante;}?></b>
            </span>
          </td>
        </tr>
      </table>
      <br>
      <hr>
      <b style="font-size: 14px !important;">DATOS DE INGRESO</b>
      <table  style="margin:0px;padding-bottom:-6px;" >
        <tr> 
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                FECHA REGISTRO INGRESO: 
                <b>{{$datos_atencion[0]->fecha_registro_ingreso}}</b> 
              </span>
            </td>  
            <td width="100%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                NIVEL IPRESS:
                <b> {{$datos_atencion[0]->nivel_ipress}} </b> 
              </span>
            </td> 
              
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                CODIGO IPRESS: 
                <b>{{$datos_atencion[0]->codigo_renipress}}</b>
              </span>
            </td> 
            
        </tr>
        <tr> 
          <td width="80%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              SERVICIO: 
              <b>{{$datos_atencion[0]->servicio}}</b> 
            </span>
          </td>  
          <td width="100%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              PABELLON:
              <b>{{$datos_atencion[0]->pabellon}}</b> 
            </span>
          </td> 
            
          <td width="160%" style="vertical-align:top;">
            <span style="display: block; font-size: 1.1rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
              PISO: {{$datos_atencion[0]->piso}} /CUARTO: {{$datos_atencion[0]->cuarto}} /Nª CAMA: {{$datos_atencion[0]->cama}}
              <b>  </b>
            </span>
          </td> 
          
      </tr>
      </table>
      <br>
      <hr>
      <b style="font-size: 14px !important;">USUARIO RESPONSABLE</b>
      <table  style="margin:0px;padding-bottom:-6px;" >
        <tr> 
            <td width="80%" style="vertical-align:top;">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                <b>{{$datos_atencion[0]->nombre_medico}} {{$datos_atencion[0]->paterno_medico}} {{$datos_atencion[0]->materno_medico}}</b> 
              </span>
            </td>  
            <td width="100%" style="vertical-align:top;">
              DNI PROFESIONAL TRIAJE:
                <b>{{$datos_atencion[0]->dni_medico}}</b> 
              </span>
            </td> 
            
        </tr>
      </table>
      <br>
      <hr>
      {{-- BALANCE HIDRICO --}}
     
      <table>
        <tr>
          <td>
            <table class="table table-striped">
              <thead>
                  <tr>
                      <td colspan="6" style="font-weight: bold;">INGRESOS</td>
                  </tr>
                  <tr>
                      <td style="font-weight: bold;">DESCRIPCIÓN</td>
                      <td style="font-weight: bold;">M</td>
                      <td style="font-weight: bold;">T</td>
                      <td style="font-weight: bold;">N</td>
                      <td style="font-weight: bold;">MD</td>
                      <td style="font-weight: bold;">TOTAL</td>
                  </tr>
              </thead>
              <tbody>
                <?php 
                  $total_m=0;
                  $total_t=0;
                  $total_n=0;
                  $total_md=0;
                  $totales=0;

                  $total_m2=0;
                  $total_t2=0;
                  $total_n2=0;
                  $total_md2=0;
                  $totales2=0;
                  foreach($egresos as $egreso){
                    $total_m2 += intval($egreso->total_m); 
                    $total_t2 += intval($egreso->total_t); 
                    $total_n2 += intval($egreso->total_n); 
                    $total_md2 += intval($egreso->total_md);
                    $totales2 += intval($egreso->total_m) + intval($egreso->total_t) + intval($egreso->total_n) + intval($egreso->total_md);
                  }
                 foreach($ingresos as $ingreso){
                  $total_m += intval($ingreso->total_m); 
                  $total_t += intval($ingreso->total_t); 
                  $total_n += intval($ingreso->total_n); 
                  $total_md += intval($ingreso->total_md);
                  $totales += intval($ingreso->total_m) + intval($ingreso->total_t) + intval($ingreso->total_n) + intval($ingreso->total_md);
                ?>
                    <tr>
                        <td style="text-align: left;">{{ $ingreso->caracter_fisico }}</td>
                        <td>{{ intval($ingreso->total_m) }}</td>
                        <td>{{ intval($ingreso->total_t) }}</td>
                        <td>{{ intval($ingreso->total_n) }}</td>
                        <td>{{ intval($ingreso->total_md) }}</td>
                        <td>{{ intval($ingreso->total_m) + intval($ingreso->total_t) + intval($ingreso->total_n) + intval($ingreso->total_md) }}</td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="text-align: left;">TOTAL</td>
                    <td style="font-weight: bold;"><?php echo $total_m; ?></td>
                    <td style="font-weight: bold;"><?php echo $total_t; ?></td>
                    <td style="font-weight: bold;"><?php echo $total_n; ?></td>
                    <td style="font-weight: bold;"><?php echo $total_md; ?></td>
                    <td style="font-weight: bold;"><?php echo $totales; ?></td>
                </tr>
                <tr>
                  <td style="text-align: left;">BALANCE</td>
                  <td style="font-weight: bold;"><?php echo $total_m-$total_m2; ?></td>
                  <td style="font-weight: bold;"><?php echo $total_t-$total_t2; ?></td>
                  <td style="font-weight: bold;"><?php echo $total_n-$total_n2; ?></td>
                  <td style="font-weight: bold;"><?php echo $total_md-$total_md2; ?></td>
                  <td style="font-weight: bold;"><?php echo $totales-$totales2; ?></td>
              </tr>
              </tbody>
          </table>
          </td>
          <td></td>
          <td>
            <table class="table table-striped">
              <thead>
                  <tr>
                      <td colspan="6" style="font-weight: bold;">EGRESOS</td>
                  </tr>
                  <tr>
                      <td style="font-weight: bold;">DESCRIPCIÓN</td>
                      <td style="font-weight: bold;">M</td>
                      <td style="font-weight: bold;">T</td>
                      <td style="font-weight: bold;">N</td>
                      <td style="font-weight: bold;">MD</td>
                      <td style="font-weight: bold;">TOTAL</td>
                  </tr>
              </thead>
              <tbody>
                <?php 
                $total_m2_egreso=0;
                $total_t2_egreso=0;
                $total_n2_egreso=0;
                $total_md2_egreso=0;
                $totales2_egreso=0;
               foreach($egresos as $egreso){
                $total_m2_egreso += intval($egreso->total_m); 
                $total_t2_egreso += intval($egreso->total_t); 
                $total_n2_egreso += intval($egreso->total_n); 
                $total_md2_egreso += intval($egreso->total_md);
                $totales2_egreso += intval($egreso->total_m) + intval($egreso->total_t) + intval($egreso->total_n) + intval($egreso->total_md);
              ?>
                  <tr>
                      <td style="text-align: left;">{{ $egreso->caracter_fisico }}</td>
                      <td>{{ intval($egreso->total_m) }}</td>
                      <td>{{ intval($egreso->total_t) }}</td>
                      <td>{{ intval($egreso->total_n) }}</td>
                      <td>{{ intval($egreso->total_md) }}</td>
                      <td>{{ intval($egreso->total_m) + intval($egreso->total_t) + intval($egreso->total_n) + intval($egreso->total_md) }}</td>
                  </tr>
              <?php } ?>
              <tr>
                  <td style="text-align: left;">TOTAL</td>
                  <td style="font-weight: bold;"><?php echo $total_m2_egreso; ?></td>
                  <td style="font-weight: bold;"><?php echo $total_t2_egreso; ?></td>
                  <td style="font-weight: bold;"><?php echo $total_n2_egreso; ?></td>
                  <td style="font-weight: bold;"><?php echo $total_md2_egreso; ?></td>
                  <td style="font-weight: bold;"><?php echo $totales2_egreso; ?></td>
              </tr>
              <tr>
                <td style="text-align: left;">BH 24 HORAS</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td style="font-weight: bold;"><?php echo $totales-$totales2; ?></td>
            </tr>
              </tbody>
          </table>
          </td>
        </tr>
        
      </table>
    <hr>
    
 
  
    <footer style=" color: black; height: 25px;">
        <table > 
            <tr style="font-size: 6px !important;">
                <td style="text-align: left">
                    <b style=" font-size: 6px !important;font-weight: 200;">PACIENTE: </b> 
                    <b style="text-align: right; padding-right: 10px;">
                        
                        DNI - 
                       <?php echo  $datos_atencion[0]->asegurado_nombre . ' ' . $datos_atencion[0]->asegurado_paterno . ' ' . $datos_atencion[0]->asegurado_materno;?>
                      </b>
                    <br>

                    <i><b>CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE EVOLUCIÓN DE ENFERMERÍA</b></i>
                </td>
                <td style="text-align: right; padding-right: 10px;">
                    USUARIO RESPOSABLE: <b> {{$balance_cabecera->name}} {{$balance_cabecera->paterno}} {{$balance_cabecera->materno}}</b>
                     <br>
                    FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
                    HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> 
                </td>
            </tr>
        </table>
    </footer>


</body>

</html>
FECHA DE IMPRESIÓN: <b>{{$fechaActual}}</b>  <br>
HORA DE IMPRESIÓN: <b>{{$horaActual}}</b> 