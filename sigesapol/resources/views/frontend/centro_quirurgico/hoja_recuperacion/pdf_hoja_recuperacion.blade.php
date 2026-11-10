

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>HOJA DE RECUPERACIÓN</title> 
    <style>  
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

        /* Margen de la página para PDF */
        @page {
            margin: 1cm;
        }

        body {
          margin: 1cm 1.5cm !important;
          color: #001028;
          background: #FFFFFF;
          font-family: Arial, sans-serif;
          font-size: 10px;
          line-height: 1.3;
          margin-bottom: 178px;
        }

        td {
            padding-left: 2px !important;
        }

        
        table {
          width: 100%;
          border-spacing: 0;
          font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
      }

        h2 {
            font-size: 15px !important
        }

        .logo {
            width: 15%;
        } 

        footer {
          position: fixed;
          bottom: 40px;
          left: 10px;
          right: 10px;
          top:10px;
          height: 178px; /* Ajusta según el tamaño de la tabla */
          
          text-align: center;
          font-size: 10px;
        }
        #tbl_intraoperatorio_footer td {
            border: 1px solid black !important;
            font-size: 9px;
            padding: 1px;
            text-align: center;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
  <footer>
    <div style="position: absolute; bottom: 10; width: 100%;">
      <table id="tbl_intraoperatorio_footer">
        <tr>
            <td colspan="3" class="td_firma"><br><br><br><br></td>
        </tr>
        <tr>
            <td>
                <b>Apellido Paterno</b>
            </td>
            <td>
                <b>Apellido Materno</b>
            </td>
            <td>
                <b>Nombres</b>
            </td>
        </tr>
        <tr>
          <td style="height:22px !important"> {{$datos_paciente_intervencion['paterno']}}</td>
          <td>{{$datos_paciente_intervencion['materno']}}</td>
          <td>{{$datos_paciente_intervencion['nombre']}} </td>
        </tr>
    </table>
    </div>
    <script type="text/php">
      if ( isset($pdf) ) {
          $pdf->page_script('
              $pdf->text(560, 20, "$PAGE_NUM/$PAGE_COUNT", "Arial", 8);
          ');
      }
    </script>
  </footer>

  <div>
    <table id="tbl_header">
      <tr>
          <td style="padding-left: 0; text-align: left;">
              <div style="padding-left: 60px;">
                  <img src="images/logo_pnp.png" alt="Logo PNP" style="width: 40px;" />
              </div>
              <p><b>MINISTERIO DEL INTERIOR<br>POLICÍA NACIONAL DEL PERÚ</b></p>
          </td>
          <td></td>
          <td  style="padding-left: 0; text-align:right;">
              <p><br><br><br><b><div style="padding-right: 60px;">DIRECCIÓN DE SANIDAD</div>HOSPITAL NACIONAL PNP "LUIS N. SÁENZ"</b></p>
          </td>
      </tr>
      
    </table>

    <table>
      <tr>
        <td colspan="3" style="font-size: 12px; text-align: center;"><b> HOSPITALIZACIÓN: CUPS: {{$codigo_cups}}</b></p></td>
      </tr>
    </table>
    
    <table>
      <tr>
        <td colspan="3" style="font-size: 18px; text-align: center;"><b>HOJA DE RECUPERACIÓN</b></td>
      </tr>
    </table>

    <table style="margin:0px;" >
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="text-align:right !important">
          <span style="display: block; margin: 0; font-size: 0.9rem; text-align:left;">
            <b>FECHA / HORA:</b> {{ $fechaActual }} {{ date('h:i A', strtotime($horaActual)) }}</span>
        </td>
      </tr>
    </table>
    <br><br>
    
    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;" colspan="2">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight:bold;">
            <b>I.-PACIENTE:</b> 
            {{ $datos_paciente_intervencion['paciente']}}</span>
           
        </td> 
      </tr>
      <tr>
        <td width="20%">
          <span style="display: block; font-size: 1.1rem; padding-left: 5px; text-align: left; margin: 0;">
            <b>EDAD:</b>  {{ $datos_paciente_intervencion['fec_nacim_descrip']}}</span> 
        </td>
        <td width="20%">
          <span style="display: block; margin: 0; font-size: 0.9rem; padding-left: 15px;text-transform: uppercase;">
           <b> CAMA: </b> {{$datos_paciente_intervencion['cama']}} </span>
        </td>
        <td width="20%">
          <span style="display: block; margin: 0; font-size: 0.9rem; padding-left: 15px;text-transform: uppercase;">
           <b> SALA: </b> {{ $datos_paciente_intervencion['sala']}} </span>
        </td>
      </tr>
      <tr>
        <td width="100%" style="padding-left: 10px; vertical-align:top;" colspan="3">
          <span style="display: block; font-size: 1.1rem;text-align: left; margin: 0;">
            <b>DIAGNOSTICO OPERATORIO:</b> </span>
          <ul style="list-style: none; padding: 0;">
              <!-- Encabezados para la lista -->
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd; font-weight: bold;">
                  <span style="width: 20%; display: inline-block;">Código</span>
                  <span style="width: 40%; display: inline-block;">Nombre</span>
                  <span style="width: 20%; display: inline-block;">Tipo</span>
              </li>
              <!-- Datos de cada diagnóstico -->
              <?php foreach($diagnostico_operatorio as $key=> $row): ?>
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                  <span style="width: 20%; display: inline-block;">{{$key+1}}. {{ $row->codigo }}</span>
                  <span style="width: 40%; display: inline-block;">{{ $row->nombre }}</span>
                  <span style="width: 20%; display: inline-block;">{{ $row->descripcion }}</span>
              </li>
              <?php endforeach; ?>
          </ul>
        </td> 
      </tr>
      <tr>
        <td width="100%" style="vertical-align:top;" colspan="3">
          <span style="display: block; font-size: 1.1rem;text-align: left; margin: 0;">
            <b>ANTECEDENTES:</b> </span>
            <ul style="list-style: none; padding: 0;">
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                <span style="width: 100%; display: inline-block;">1. {{ $datos_paciente_intervencion['antecedente_personal'] }}</span>
              </li>
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                <span style="width: 100%; display: inline-block;">2. {{ $datos_paciente_intervencion['antecedente_familiar'] }}</span>
              </li>
            </ul>
        </td> 
      </tr>
      <tr>
        <td width="100%" style="padding-left: 10px; vertical-align:top;" colspan="3">
          <span style="display: block; font-size: 1.1rem;text-align: left; margin: 0;">
            <b> TIPO DE ANESTESIA:</b> </span>
          <ul style="list-style: none; padding: 0;">
              <?php foreach($tipos_anestesia_aq as $key=> $row): ?>
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                  <span style="width: 100%; display: inline-block;">{{$key+1}}. {{ $row->descripcion }}</span>
              </li>
              <?php endforeach; ?>
          </ul>
        </td> 
      </tr>
      <tr>
        <td width="100%" style="padding-left: 10px; vertical-align:top;" colspan="3">
         <span style="display: block; font-size: 1.1rem;text-align: left; margin: 0;">
          <b>CIRUJANOS:</b> </span>
          <ul style="list-style: none; padding: 0;">
              <!-- Encabezados para la lista -->
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd; font-weight: bold;">
                  <span style="width: 70%; display: inline-block;">Nombre</span>
                  <span style="width: 20%; display: inline-block;">Tipo</span>
              </li>
              <!-- Datos de cada diagnóstico -->
              <?php foreach($cirujanos as $key=> $row): ?>
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                  <span style="width: 70%; display: inline-block;">{{$key+1}}. {{ $row->medico }}</span>
                  <span style="width: 20%; display: inline-block;">{{ $row->tipo }}</span>
              </li>
              <?php endforeach; ?>
          </ul>
        </td> 
      </tr>
      <tr>
        <td width="100%" style="padding-left: 10px; vertical-align:top;" colspan="3">
          <span style="display: block; font-size: 1.1rem;text-align: left; margin: 0;">
            <b>ANESTESIOLOGOS:</b> </span>
          <ul style="list-style: none; padding: 0;">
              <!-- Encabezados para la lista -->
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd; font-weight: bold;">
                  <span style="width: 70%; display: inline-block;">Nombre</span>
                  <span style="width: 20%; display: inline-block;">Tipo</span>
              </li>
              <!-- Datos de cada diagnóstico -->
              <?php foreach($anestesiologos as $key=> $row): ?>
              <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                  <span style="width: 70%; display: inline-block;">{{$key+1}}. {{ $row->medico }}</span>
                  <span style="width: 20%; display: inline-block;">{{ $row->tipo }}</span>
              </li>
              <?php endforeach; ?>
          </ul>
        </td> 
      </tr>
    </table>
    <br>
    {{-- BALANCE HIDRICO --}}
    <b>II. BALANCE HIDRICO</b><br>
    <div style="border: 1px solid #000; padding: 10px;">
      <table>
        <tr>
          <td style="vertical-align: top;">
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
                  <td style="text-align: left;" colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td style="text-align: left;" colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td style="text-align: left;" colspan="6">&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left;"><b>TOTAL</b></td>
                    <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_m; ?></td>
                    <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_t; ?></td>
                    <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_n; ?></td>
                    <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_md; ?></td>
                    <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $totales; ?></td>
                </tr>
                <tr>
                  <td style="text-align: left;"><b>BALANCE POR TURNOS</b></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_m-$total_m2; ?></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_t-$total_t2; ?></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_n-$total_n2; ?></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_md-$total_md2; ?></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $totales-$totales2; ?></td>
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
                <td style="text-align: left;" colspan="6">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left;" colspan="6">&nbsp;</td>
              </tr>
              <tr>
                  <td style="text-align: left;"><b>TOTAL</b></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_m2_egreso; ?></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_t2_egreso; ?></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_n2_egreso; ?></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_md2_egreso; ?></td>
                  <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $totales2_egreso; ?></td>
              </tr>
              <tr>
                <td style="text-align: left;"><b>BH 24 HORAS</b></td>
                <td style="border-top: 1px solid black;">-</td>
                <td style="border-top: 1px solid black;">-</td>
                <td style="border-top: 1px solid black;">-</td>
                <td style="border-top: 1px solid black;">-</td>
                <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $totales-$totales2; ?></td>
            </tr>
              </tbody>
          </table>
          </td>
        </tr>
        
      </table>
    </div>
    <br>
    <b style="font-weight: bold;">III. TRATAMIENTO</b><br>
    <div style="border: 1px solid #000; padding: 10px;">
      {{ $hoja_recuperacion->descripcion_tratamiento }}
    </div>
    <br>
    <b style="font-weight: bold;">IV. FUNCIONES VITALES</b><br>
    <div style="width: 100%;">
      <table   class="table">
        
            <tr>
                @foreach ($encabezado_fv as $encabezado)
                    <th style="background-color: #f2f2f2; color: #030202; text-align: center; padding: 5px; border: 1px solid #070606; vertical-align: middle;">
                        {{ $encabezado->descripcion_corta }}
                    </th>
                @endforeach
            </tr>
        
        
            @foreach ($funciones_vitales as $fila)
                <tr style="text-align: center; page-break-inside: avoid;">
                    @foreach ($fila['registros'] as $registro)
                        @if ($registro['secuencia'] == 1)
                            <td style="padding: 5px; border: 1px solid #070606; vertical-align: middle;">
                                {{ $registro['hora_registro'] }}
                            </td>
                        @else
                            <td style="padding: 5px; border: 1px solid #070606; vertical-align: middle;">
                                {{ $registro['valor_caracter_fisico'] }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        
      </table>
    </div>
  
  
    <br>
    <b style="font-weight: bold;">V. DIAGNOSTICOS DE ENFERMERIA</b><br>
    <fieldset>
      <table width="100%"  style="text-align: left;">
        <tr>
          <td style="padding: 10px;  text-align: left;">1.- PATRÓN RESPIRATORIO INEFICAZ RELACIONADO CON DOLOR QUE SE EVIDENCIA POR DISNEA:</td>
          <td style="padding: 10px;  text-align: left;"><input type="checkbox" id="campo1_diagnostico_enfermeria" name="campo1_diagnostico_enfermeria" @if($hoja_recuperacion['campo1_diagnostico_enfermeria']==1) checked @endif></td>
        </tr>
        <tr>
          <td style="padding: 10px;  text-align: left;"> 2.- LIMPIEZA INEFICAZ DE LAS VÍAS AÉREAS RELACIONADO CON LA RETENCIÓN DE LAS SECRECIONES SECUNDARIO A<br>
            ESTADO DE INCONSCIENCIA:</td>
          <td style="padding: 10px;  text-align: left;"><input type="checkbox" id="campo2_diagnostico_enfermeria" name="campo2_diagnostico_enfermeria" @if($hoja_recuperacion['campo2_diagnostico_enfermeria']==1) checked @endif></td>
        </tr>
        <tr>
          <td style="padding: 10px;  text-align: left;"> 3.- TERMORREGULACIÓN INEFICAZ RELACIONADO CON TEMPERATURA DEL AMBIENTE LUCTUANTE:</td>
          <td style="padding: 10px;  text-align: left;"> <input type="checkbox" id="campo3_diagnostico_enfermeria" name="campo3_diagnostico_enfermeria" @if($hoja_recuperacion['campo3_diagnostico_enfermeria']==1) checked @endif></td>
        </tr>
      </table>
    </fieldset>
  </div>
</body>

</html>
 
