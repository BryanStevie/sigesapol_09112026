<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="centro_quirurgico/nivel2/css/pdf_papeleta_egreso_nivel_2.css" />
    <title>Document</title>
</head>

<body>
  <table style="margin-top: {{ isset($is_firma) && $is_firma ? "60px" : "0px" }}">
    <tr>
      <td class="logo-sanidad-cell">
        <img src="images/logo_sanidad.png" width="100rem" />
      </td>
      <td class="middle-cell">
        <div class="header-title">
          <span>PAPELETA DE EGRESO</span>
        </div>
        <br>
        <div class="header-content">
          <span>CUPSS PNP: <b>{{ $hospitalizacion->cups_pnp }}</b></span><br>
          <span>COD.SITEDS: <b>{{ $hospitalizacion->cod_siteds_susalud }}</b></span>
        </div>
      </td>
      <td class="logo-pnp-cell">
        <img src="images/logo_pnp.png" width="100rem" />
      </td>
    </tr>
  </table>
  <br>
  <table>
    <tr>
      <td style="width: 30%"></td>
      <td style="width: 30%"></td>
      <td style="width: 40%">
        <div class="header-content">
          <span>H.C: <b>{{ $hospitalizacion->paciente_ndocumento }}</b></span><br>
          <span>H.CC.A: <b>{{ $hospitalizacion->paciente_nhistoria }}<</b></span><br>
          <span>N°ORDEN <b>{{ str_pad($hospitalizacion->secuencia, 8, '0', STR_PAD_LEFT) }}</b></span><br>
          <span>FECHA Y HORA DE INGRESO: <b>{{ $hospitalizacion->fecha_ingreso}} {{ date('H:i:s', strtotime($hospitalizacion->hora_ingreso)) }}</b></span><br>
        </div>
      </td>
    </tr>
  </table>
  <br>
  <div class="divider"></div>
  <br>
  <table>
    <tr><td><span><b>DATOS DEL PACIENTE</b></span></td></tr>
  </table>
  <br>
  <table>
    <tr><td colspan="3">APELLIDOS Y NOMBRES: <b>{{ $hospitalizacion->paciente_paterno }} {{ $hospitalizacion->paciente_materno }} {{ $hospitalizacion->paciente_nombre }}</b></td></tr>
    @if ($titular != null && $titular->dni_titular != $hospitalizacion->paciente_ndocumento)
      <tr><td colspan="3">APELLIDOS Y NOMBRES DEL TITULAR: <b>{{ $titular->paterno }} {{ $titular->materno }} {{ $titular->nombre }}</b></td></tr>
    @endif
    <tr><td colspan="3">PARENTESCO CON EL PACIENTE: <b>{{ $hospitalizacion->paciente_parentesco }}</b></td></tr>
    <tr>
      <td style="width: 33%">EDAD: <b>{{ $hospitalizacion->paciente_edad_ingreso }}</b></td>
      <td style="width: 33%">SEXO: <b>{{ $hospitalizacion->paciente_sexo }}</b></td>
      <td style="width: 33%">DNI: <b> {{ $hospitalizacion->paciente_ndocumento }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%">RAZA: <b>{{ $asegurado->etnia }}</b></td>
      <td style="width: 33%">ESTADO CIVIL: <b>{{ $asegurado->estado_civil }}</b></td>
      <td style="width: 33%">CIP: {{ isset($persona_datos_adicionales->cip) ? $persona_datos_adicionales->cip : "-" }}</td>
    </tr>
    <tr>
      <td style="width: 33%">GRADO DE INSTRUCCIÓN: <b>{{ $asegurado->grado_instruccion }}</b></td>
      <td colspan="2" style="width: 33%">OCUPACIÓN: <b>{{ $asegurado->ocupacion }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%">LUGAR DE NACIMIENTO: <b>{{ $asegurado->departamento_nacimiento2 }} / {{ $asegurado->provincia_nacimiento2 }} / {{ $asegurado->distrito_nacimiento2 }} / {{ strtoupper($asegurado->direccion_nacimiento) }}</b></td>
      <td style="width: 33%">FECHA DE NACIMIENTO: <b>{{ $asegurado->fecha_nac }}</b></td>
      <td style="width: 33%">RELIGIÓN: <b>{{ $asegurado->religion }}</b></td>
    </tr>
    <tr><td colspan="3">DOMICILIO: <b>{{ $asegurado->ocupacion }}</b></td></tr>
    <tr>
      <td style="width: 33%">DISTRITO: <b>{{ $asegurado->distrito }}</b></td>
      <td colspan="2" style="width: 33%">DEPARTAMENTO: <b>{{ $asegurado->departamento }}</b></td>
    </tr>
    <tr><td colspan="3">TELEFONO: <b>{{ $asegurado->telefono }}</b></td></tr>
  </table>
  <br>
  <br>
  <table>
    <tr><td>FECHA Y HORA DE INGRESO: <b>{{ $hospitalizacion->fecha_ingreso }} {{ date("H:i:s", strtotime($hospitalizacion->hora_ingreso)) }}</b></td></tr>
    <tr><td>FECHA Y HORA DE INTERVENCIÓN: <b>{{ $intervencion->fecha_intervencion }}</b></td></tr>
    <tr><td>CODIGO SITEDS: <b>{{ $hospitalizacion->cod_siteds_susalud }}</b></td></tr>
    <tr><td>CUPS: <b>{{ $hospitalizacion->cups_pnp }}</b></td></tr>
  </table>
  <br>
  <table>
    <tr><td>DIAS DE ESTANCIA: <b>{{$dias_estancia}} DIAS</b></td></tr>
  </table>
  <br>
  <div class="divider"></div>
  <br>
  <table>
    <tr><td><span><b>DATOS DEL EGRESO</b></span></td></tr>
  </table>
  <br>
  <table>
    <tr><td colspan="4">MEDICO: <b>{{$hospitalizacion->user_nombre_medico_alta}} {{$hospitalizacion->user_paterno_medico_alta }} {{$hospitalizacion->user_materno_medico_alta}}</b></td></tr>
    <tr><td colspan="4">UPSS ENCARGADO: <b>{{ $hospitalizacion->upss_acargo }}</b></td></tr>
    <tr><td colspan="4">FECHA Y HORA EGRESO MEDICO: <b>{{$hospitalizacion->fecha_alta_medica}} {{ date("H:i:s", strtotime($hospitalizacion->hora_alta_medica)) }}</td></tr>
    <tr><td colspan="4">TIPO DE EGRESO: <b>{{ $hospitalizacion->tipo_egreso }}</b></td></tr>
    <tr><td colspan="4">CONDICIÓN:</td></tr>
    <tr>
      <td style="width: 25%"><b>Curado</b> ( @if($hospitalizacion->id_condicion_alta == 2)<b><u>X</u></b>@endif )</td>
      <td style="width: 25%"><b>Aliviado</b> ( @if($hospitalizacion->id_condicion_alta == 1)<b><u>X</u></b>@endif )</td>
      <td style="width: 25%"><b>No tratado</b> ( @if($hospitalizacion->id_condicion_alta == 4)<b><u>X</u> </b>@endif )</td>
      <td style="width: 25%"><b>Fallecido</b> ( @if($hospitalizacion->id_condicion_alta == 6) <b><u>X</u></b>@endif )</td>
    </tr>
  </table>
  <br>
  <div class="divider"></div>
  <br>
  <table>
    <tr><td><span><b>DIAGNOSTICO DE EGRESO</b></span></td></tr>
  </table>
  <br>
  <table class="table-diagnosticos">
    <tr>
      <th>N</th>
      <th>Código</th>
      <th>Descripción</th>
      <th>Tipo</th>
    </tr>
    <?php $tipo = ''; ?>
    @if (isset($diagnosticos) && count($diagnosticos) > 0)
      @foreach ($diagnosticos as $i => $diagnostico)
        <?php
          $tipo = '';
          if ($diagnostico->id_tipo_diagnostico == 1) $tipo = 'PRESUNTIVO';
          if ($diagnostico->id_tipo_diagnostico == 2) $tipo = 'DEFINITIVO';
          if ($diagnostico->id_tipo_diagnostico == 3) $tipo = 'REITERATIVO';
        ?>
        <tr>
          <td style="text-align: center">{{ $i + 1 }}</td>
          <td style="text-align: center">{{ $diagnostico->codigo }}</td>
          <td>{{ $diagnostico->nombre }}</td>
          <td style="text-align: center">{{ $tipo }}</td>
        </tr>
      @endforeach
    @endif
  </table>
  <br>
  <table class="table-footer">
    <tr>
      <td style="width: 70%">
        <div>
          <span>USUARIO REGISTRO: <b>{{$hospitalizacion->user_paterno}} {{$hospitalizacion->user_materno}}, {{$hospitalizacion->user_nombre}}</b></span><br>
          <span>USUARIO DE IMPRESIÓN: <b>{{$user_actual->paterno}} {{$user_actual->materno}}, {{$user_actual->name}}</b></span><br>
        </div>
      </td>
      <td style="width: 30%">
        <span>FECHA DE IMPRESIÓN <b>{{$fechaActual}}</b></span><br>
        <span>HORA DE IMPRESIÓN: <b>{{$horaActual}}</b></span><br>
      </td>
    </tr>
  </table>
</body>

</html>