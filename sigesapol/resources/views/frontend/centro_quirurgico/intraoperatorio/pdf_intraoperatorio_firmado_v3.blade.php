<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<style>
  body {
    font-family: Arial, sans-serif;
    font-size: 11px;
  }
  table {
    border-collapse: collapse;
  }
  td {
    vertical-align: top;
  }
  .col-30 { width: 30%; }
  .col-50 { width: 50%; }
  .col-70 { width: 70%; }
  .col-100 { width: 100%; }
</style>
<body>
  <h2 style="text-align: center; margin-top: 100px"><b>REGISTRO DE ENFERMERIA INTRAOPERATORIO</b></h2>
  <br>
  <table style="width: 100%;">
    <tr>
      <td class="col-50">
        <table style="width: 100%;">
          <tr><td class="col-100"><span>Fecha: <b>{{ isset($data->fecha_registro) ? $data->fecha_registro : "-" }}</b></span></td></tr>
          {{-- <tr>
            <td class="col-50"><span>Fecha: <b>{{ isset($data->fecha_probable) ? $data->fecha_probable : "-" }}</b></span></td>
            <td class="col-50"><span>Tarifa: <b>{{ "__________" }}</b></span></td>
          </tr> --}}
          <tr>
            <td class="col-70"><span>Sala: <b>{{ isset($data->sala_intervencion) ? $data->sala_intervencion : "-" }}</b></span></td>
            <td class="col-30"><span>Edad: <b>{{ isset($datos_paciente->asegurado_edad_anios) ? $datos_paciente->asegurado_edad_anios : "-" }}</b></span></td>
          </tr>
          <tr><td class="col-100"><span>Nombre del Paciente: <b>{{$datos_paciente->asegurado_nombre}} {{$datos_paciente->asegurado_paterno}} {{$datos_paciente->asegurado_materno}}</b></span></td></tr>
          <tr><td class="col-100"><span>Número de Documento: <b>{{$datos_paciente->dni}}</b></span></td></tr>
          <tr><td class="col-100"><span>Tipo de Documento: <b>{{$datos_paciente->tipo_documento}}</b></span></td></tr>
          <tr>
            <td class="col-100" colspan="2">
              <span>Anestesiólogia: <b>
              @if(!empty($anestesiologos))
                {{$anestesiologos->medicos_nombres}}
              @endif  
              </b></span>
            </td>
          </tr>
          <tr>
            <td class="col-100" colspan="2">
              <span>Cirujanos: <b>
              @if(!empty($cirujanos))
                {{$cirujanos->medicos_nombres}}
              @endif  
              </b></span>
            </td>
            </tr>
            @if(!empty($instrumentistas) && (is_array($instrumentistas) || is_object($instrumentistas)))
              @foreach($instrumentistas as $key => $row)
                <tr>
                  <td class="col-100" colspan="2">
                    <span>{{$row->descripcion}}: <b>{{$row->nombre}}   {{$row->paterno}}  {{$row->materno}}</b></span>
                  </td>
                </tr>
              @endforeach
            @endif
          <tr>
            <td class="col-100" colspan="2">
              <span><b>Disponibilidad de </b></span>
              <span>Equipos ( @if($data->cajas_instrumental_equipo) x @endif ) </span>
              <span>Insumos ( @if($data->cajas_instrumental_insumos) x @endif ) </span>
              <span>Instrumental ( @if($data->cajas_instrumental_instrumental) x @endif )</span>
            </td>
          </tr>
          <tr>
            <td class="col-100" colspan="2">
              <span><b>Enf I y Enf II verifica y controla:</b> </span>
              <span>Insumos ( @if($data->enf1_enf2_vc_insumos) x @endif ) </span>
              <span>Instrumental ( @if($data->enf1_enf2_vc_instrumental) x @endif ) </span>
            </td>
          </tr>
        </table>
      </td>

      <td class="col-50">
        <table style="width: 100%;">
          <tr><td class="col-100"><span>Recepción del paciente: <b>{{$data['recepcion_paciente_descripcion']}}</b></span></td></tr>
          <tr><td class="col-100"><span>Tipo de Anestesia: <b>{{$data->tipo_anestesia}}</b></span></td></tr>
          <tr>
            <td class="col-50"><span>Inicio de Anestesia: <b>{{$data['anestesia_inicio']}}</b></span></td>
            <td class="col-50"><span>Término: <b>{{$data['anestesia_termino']}}</b></span></td>
          </tr>
          <tr>
            <td class="col-50"><span>Inicio de Cirugia: <b>{{$data['cirugia_inicio']}}</b></span></td>
            <td class="col-50"><span>Término: <b>{{$data['cirugia_termino']}}</b></span></td>
          </tr>
          <tr><td class="col-100" colspan="2"><span>Tiempo Real: <b>{{{$data['tipo_real']}}}</b></span></td></tr>
          <tr><td class="col-100" colspan="2"><span>Operación Programada: <b>{{isset($data->operaciones_programadas) ? $data->operaciones_programadas : "" }}</b></span></td></tr>
          <tr><td class="col-100" colspan="2"><span>Operación de Emergencia: <b>{{isset($data->operaciones_emergencia) ? $data->operaciones_emergencia : "" }}</b></span></td></tr>
          <tr><td class="col-100" colspan="2"><span>Operación Realizada: <b>{{isset($data->operaciones_realizadas) ? $data->operaciones_realizadas : "" }}</b></span></td></tr>
          <tr><td class="col-100" colspan="2"><span>Salida del Paciente: <b>{{$data['salida_paciente']}}</b></span></td></tr>
          <tr><td class="col-100" colspan="2"><span>Hora Llegada URPA: <b>{{$data->hora_llegada_urpa}}</b></span></td></tr>
          <tr><td class="col-100" colspan="2"><span><b>COMPLICACIONES: <b>{{$data['complicaciones']}}</b></b></span></td></tr>
          <tr><td class="col-100" colspan="2"><span>Maquina de Anestesia: <b>{{$data->maquina_anestesia_descripcion}}</b></span></td></tr>
          <tr><td class="col-100" colspan="2"><span>Oxigeno: <b>{{$data['oxigeno_descripcion']}}</b></span></td></tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <div>
    <p>Comentarios: <b>{{ $data->comentarios }}</b></p>
  </div>
  <br>
  <table style="width: 100%;">
    <tr>
      <td class="col-50">
        <p style="text-align: center; font-size: 12px; font-weight: 700">DIAGNOSTICOS DE ENFERMERIA</p>
        <table style="width: 100%;">
          @if(!empty($diagnosticos_enfermeria) && (is_array($diagnosticos_enfermeria) || is_object($diagnosticos_enfermeria)))
            @foreach ($diagnosticos_enfermeria as $key => $diagnostico)
            <tr>
              <td width="95%"> {{ $diagnostico->descripcion }}</td>
              <td width="5%"> 
                @if($diagnostico->checked)
                    (x)
                @else
                    ( )
                @endif
              </td>
            </tr>
            @endforeach
          @endif
        </table>
      </td>

      <td class="col-50">
        <p style="text-align: center; font-size: 12px; font-weight: 700">INTERVENCIÓN DE ENFERMERIA (NIC)</p>
        <table style="width: 100%;">
          @if(!empty($intervencion_enfermeria) && (is_array($intervencion_enfermeria) || is_object($intervencion_enfermeria)))
            @foreach ($intervencion_enfermeria as $key => $nic)
            <tr>
              <td width="5%"><span>{{ $nic->descripcion }}</span></td>
              <td width="95%">
                @if(!empty($nic->hijos))
                  @foreach ($nic->hijos as $hijo)
                    <span>                                
                      {{ $hijo['descripcion'] }} 
                      @if($hijo['checked']==true)
                          (x)
                      @else
                          ( )
                      @endif
                    </span>
                  @endforeach
                @endif
              </td>
            </tr>
            @endforeach
          @endif
        </table>
      </td>
    </tr>
  </table>
  <br>
  <table style="width: 100%;">
    <tr>
      <td class="col-50">
        <p style="text-align: center; font-size: 12px; font-weight: 700">BALANCE DE FLUIDOS</p>
        <table style="width: 100%; border: 1px solid #000; font-size: 10px">
          <tr>
            <th style="text-align: center;">INGRESO</th>
            <th style="text-align: center;">EGRESO</th>
            <th style="text-align: center;">TERAPEUTICA</th>
          </tr>
          @if(!empty($balance_fluidos_enfermeria) && (is_array($balance_fluidos_enfermeria) || is_object($balance_fluidos_enfermeria)))
            @foreach ($balance_fluidos_enfermeria as $key => $bf)
              <tr>
                <td>{{ $bf->ingreso_fluido }}</td>
                <td>{{ $bf->egreso_fluido }}</td>
                <td>{{ $bf->terapeutica_fluido }}</td>
              </tr>
            @endforeach
          @endif
        </table>
      </td>
      <td class="col-50">
        <p style="text-align: center; font-size: 12px; font-weight: 700">RECUENTO DE GASAS</p>
        <table style="width: 100%; border: 1px solid #000; font-size: 10px">
          <tr>
            <th style="text-align: center; border: 1px solid #000;"></th>
            <th style="text-align: center; border: 1px solid #000;">ENF. I</th>
            <th style="text-align: center; border: 1px solid #000;">ENF. II</th>
            <th style="text-align: center; border: 1px solid #000;">TOTAL</th>
          </tr>
          @if(!empty($recuento_gasas_enfermeria) && (is_array($recuento_gasas_enfermeria) || is_object($recuento_gasas_enfermeria)))
            @foreach ($recuento_gasas_enfermeria as $key => $rg)
              <tr>
                @if (intval($key) === 0)
                  <td style="border: 1px solid #000;">GASA GRANDE</td>
                @endif
                @if (intval($key) === 1)
                  <td style="border: 1px solid #000;">GASA CHICA</td>
                @endif
                <td style="border: 1px solid #000; text-align: center;">{{ $rg->enfermeria1 }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $rg->enfermeria2 }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $rg->total }}</td>
              </tr>
            @endforeach
          @endif
        </table>
      </td>
    </tr>
  </table>
</body>
</html>