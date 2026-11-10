<!DOCTYPE html>
<html>
<head>
  <title>INTRAOPERATORIO</title>
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
        line-height: 1.9;
        margin-bottom: 178px;
      }
      
      /* Estilo para las tablas */
      table {
          width: 100%;
          border-spacing: 0;
          font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
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
    </style>
</head>
<body>

  <footer>
    <div style="position: absolute; bottom: 10; width: 100%;">
      <table id="tbl_intraoperatorio_footer">
        <tr>
            <td colspan="4" class="td_firma"><br><br><br><br></td>
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
            <td style="width: 150px">
                <b>Sala</b>
            </td>
        </tr>
        <tr>
            <td style="height:22px !important"> {{$datos_paciente->asegurado_paterno}}</td>
            <td>{{$datos_paciente->asegurado_materno}}</td>
            <td>{{$datos_paciente->asegurado_nombre}} </td>
            <td> {{$datos_paciente->sala}}</td>
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
    <!-- <table style="margin:0px;padding:0px;padding-bottom:0px;">
      <tbody>
          <tr>
              <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px">
                  <?php //echo $url_descarga?>
              </td>
              <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px">
                  <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
              </td>
          </tr>
      </tbody>
    </table> -->
    
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
      <tr>
          <td colspan="3">&nbsp;</td>
      </tr>
    </table>
    <table>
      <tr>
        <td colspan="3" style="font-size: 12px; text-align: center;"><b> HOSPITALIZACIÓN: CUPS: {{$codigo_cups}}</b></p> </td>
      </tr>
    </table>
    <table id="tbl_header">
      <tr>
        <td colspan="3" style="font-size: 18px; text-align: center;"><b>REGISTRO INTRAOPERATORIO</b></td>
      </tr>
    </table>

    <table style="margin:0px;" >
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="text-align:right !important">
          <span style="display: block; margin: 0; font-size: 0.9rem; text-align:left;">
            FECHA / HORA: <b>{{ date('d/m/Y', strtotime($data['fecha_registro'])) }} {{ date('h:i A', strtotime($data['hora_registro'])) }}</b></span>
        </td>
      </tr>
    </table>

    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight: bold;">
            I.- PACIENTE: 
            {{$datos_paciente->dni}} {{$datos_paciente->asegurado_nombre}} {{$datos_paciente->asegurado_paterno}} {{$datos_paciente->asegurado_materno}}</span>
           
        </td> 
        <td width="65%">
          <span style="display: block; margin: 0; font-size: 0.9rem; text-align: right; padding-left: 15px;text-transform: uppercase;">
            EDAD: <b>  {{$datos_paciente->asegurado_edad_anios}} AÑOS CON {{$datos_paciente->asegurado_edad_meses}} MESES Y {{$datos_paciente->asegurado_edad_dias}} DÍAS</b></span>
        </td>
      </tr>
    </table>

    <table style="margin:0px;padding:0px;" >
      <tr> 
        <td width="30%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;">PARENTESCO: <b> {{$datos_paciente->asegurado_tipo_beneficiario}}</b></span>
        </td>
        <td width="15%"><span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: left; ">SEXO : <b> {{$datos_paciente->sexo}}</b></span></td>
        <td width="30%">
          <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: left">ESTADO CIVIL: 
            <b> {{$datos_paciente->estado_civil}}</b>
          </span>
        </td>
      </tr>
      <tr>
        <td colspan="3" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;padding-left: 0px;">
            DIRECCIÓN: 
            <b>{{$datos_paciente->domicilio}} - ({{$datos_paciente->departamento}}/{{$datos_paciente->provincia}}/{{$datos_paciente->distrito}})</b>
          </span>
        </td>  
      </tr>
    </table>

    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight: bold;">
            II.- REGISTRO EN CENTRO QUIRÚRGICO
           </span>
        </td> 
      </tr>
    </table>

    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">ANESTESIOLOGOS: <b>  @if(!empty($anestesiologos) && (is_array($anestesiologos) || is_object($anestesiologos)))  {{$anestesiologos->medicos_nombres}} @endif</b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">CIRUJANOS: <b>  @if(!empty($cirujanos) && (is_array($cirujanos) || is_object($cirujanos))) {{$cirujanos->medicos_nombres}} @endif </b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            @if(!empty($instrumentistas) && (is_array($instrumentistas) || is_object($instrumentistas)))
              @foreach($instrumentistas as $key => $row)
              <tr>
                <td width="20%"> <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">{{$row->descripcion}}:</span></td>
                <td width="80%"> <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;"><b>{{$row->nombre}}   {{$row->paterno}}  {{$row->materno}} </b></span></td>
              </tr>
              @endforeach
            @endif
          </table>

          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">CAJAS DE ANESTESIA: <b>  {{$data['cajas_instrumental_descripcion']}}</b></span>
              </td>
            </tr>
          </table>

          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">MAQUINA DE ANESTESIA: <b>  {{$data['maquina_anestesia_descripcion']}} </b></span>
              </td>
            </tr>
          </table>
        </td>
        <td width="50%" style="vertical-align:top;">
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">RECEPCIÓN DEL PACIENTE: <b> {{$data['recepcion_paciente_descripcion']}}</b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">TIPO DE ANESTESIA: <b> {{$data['recepcion_paciente_descripcion']}}</b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">INICIO DE ANESTESIA: <b>  {{$data['anestesia_inicio']}}</b></span>
              </td>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">TERMINO: <b>   {{$data['anestesia_termino']}}</b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">INICIO DE CIRUGIA: <b>  {{$data['cirugia_inicio']}}</b></span>
              </td>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">TERMINO: <b>  {{$data['cirugia_termino']}}</b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">TIEMPO REAL: <b>  {{$data['tipo_real']}}</b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">{{$data['tipo_operacion']}}: <b>  </b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">OPERACIÓN REALIZADA: <b>  </b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">SALIDA DEL PACIENTE: <b> {{$data['salida_paciente']}} </b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">COMPLICACIONES: <b> {{$data['complicaciones']}} </b></span>
              </td>
            </tr>
          </table>
          <table style="margin:0px;padding:0px;" >
            <tr>
              <td width="30%" style="vertical-align:top;">
                <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">OXIGENO: <b>{{$data['oxigeno_descripcion']}} </b></span>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="80%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.8rem; text-align: left; margin: 0; padding-top: 0.3rem;">COMENTARIOS: <b> {{$data['comentarios']}}</b></span>
        </td>
      </tr>
    </table>

    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight: bold;">
            III.-DIAGNOSTICOS E INTERVENCIÓN DE ENFERMERIA
           </span>
        </td> 
      </tr>
    </table>
    
    <table style="margin:0px;padding:0px; width: 100%; border-collapse: collapse;">
      <tr>
        <td width="100%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DIAGNOSTICOS DE ENFERMERIA</b></td>
      </tr>
      @if(!empty($diagnosticos_enfermeria) && (is_array($diagnosticos_enfermeria) || is_object($diagnosticos_enfermeria)))
        @foreach ($diagnosticos_enfermeria as $key => $diagnostico)
        <tr  style="border: 1px solid black;">
          <td width="99%" style="font-family: 'DejaVu Sans', sans-serif; border: 1px solid black;"> {{ $diagnostico->descripcion }}  @if($diagnostico->checked)
            (✔)
        @else
            ( )
        @endif
        </tr>
        @endforeach
      @endif
    </table>
    <br>

    <table style="margin:0px;padding:0px; width: 100%; border-collapse: collapse;">
      <tr>
        <td width="100%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>INTERVENCION DE ENFERMERIA (NIC)</b></td>
      </tr>
      @if(!empty($intervencion_enfermeria) && (is_array($intervencion_enfermeria) || is_object($intervencion_enfermeria)))
        @foreach ($intervencion_enfermeria as $nic)
            <tr>
                <td style="width: 100%; vertical-align: top; padding-top: 0px; border: 1px solid black;">
                    <strong>{{ $nic->descripcion }}</strong>
                </td>
            </tr>
            @if(!empty($nic->hijos) && (is_array($nic->hijos) || is_object($nic->hijos)))
                @foreach ($nic->hijos as $hijo)
                    <tr>
                        <td style="font-family: 'DejaVu Sans', sans-serif; border: 1px solid black;">
                            {{ $hijo['descripcion'] }} 
                            @if($hijo['checked'] == true)
                                (✔)
                            @else
                                ( )
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        @endforeach
      @endif
  </table>
  
  
  
    <table style="margin:0px;padding:0px;" >
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight: bold;">
            IV.-BALANCE DE FLUIDOS
           </span>
        </td> 
      </tr>
    </table>

    <table style="margin: 0px; padding: 0px; border-collapse: collapse; width: 100%; border: 1px solid black;">
      <tr>
          <th style="text-align: center; width: 35%; border: 1px solid black; padding: 5px;">INGRESO</th>
          <th style="text-align: center; width: 35%; border: 1px solid black; padding: 5px;">EGRESO</th>
          <th style="text-align: center; width: 30%; border: 1px solid black; padding: 5px;">TERAPEUTICA</th>
      </tr>
      @if(!empty($balance_fluidos_enfermeria) && (is_array($balance_fluidos_enfermeria) || is_object($balance_fluidos_enfermeria)))
        @foreach ($balance_fluidos_enfermeria as $key => $bf)
            <tr>
                <td style="text-align: center; width: 35%; border: 1px solid black; padding: 5px;">{{ $bf->ingreso_fluido }}</td>
                <td style="text-align: center; width: 35%; border: 1px solid black; padding: 5px;">{{ $bf->egreso_fluido }}</td>
                <td style="text-align: center; width: 30%; border: 1px solid black; padding: 5px;">{{ $bf->terapeutica_fluido }}</td>
            </tr>
        @endforeach
      @endif
    </table>

    <table style="margin:0px;padding:0px;">
      <tr>
        <td width="100%" style="vertical-align:top;">
          <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight: bold;">
            V.-RECUENTO DE GASAS
           </span>
        </td> 
      </tr>
    </table>

    <table style="margin: 0px; padding: 0px; border-collapse: collapse; width: 100%; border: 1px solid black;">
      <tr>
          <th style="text-align: center; width: 35%; border: 1px solid black; padding: 5px;">ENFERMERÍA I</th>
          <th style="text-align: center; width: 35%; border: 1px solid black; padding: 5px;">ENFERMERÍA II</th>
          <th style="text-align: center; width: 30%; border: 1px solid black; padding: 5px;">TOTAL</th>
      </tr>
      @if(!empty($recuento_gasas_enfermeria) && (is_array($recuento_gasas_enfermeria) || is_object($recuento_gasas_enfermeria)))
        @foreach ($recuento_gasas_enfermeria as $key => $rg)
          <tr>
              <td style="text-align: center; width: 35%; border: 1px solid black; padding: 5px;">{{ $rg->enfermeria1 }}</td>
              <td style="text-align: center; width: 35%; border: 1px solid black; padding: 5px;">{{ $rg->enfermeria2 }}</td>
              <td style="text-align: center; width: 30%; border: 1px solid black; padding: 5px;">{{ $rg->total }}</td>
          </tr>
        @endforeach
      @endif
    </table>
  </div>
</body>
</html>
