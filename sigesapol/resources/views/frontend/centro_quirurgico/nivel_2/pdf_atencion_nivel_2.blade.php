<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="centro_quirurgico/nivel2/css/pdf_atencion_nivel_2.css" />
    <title>Document</title>
</head>

<body>
  <div class="page">
    <table class="no-border" style="margin-top: {{ isset($is_firma) && $is_firma ? "60px" : "0px" }}">
      <tr>
        <td class="no-border logo-sanidad-cell">
          <img src="images/logo_sanidad.png" width="100rem" />
        </td>
        <td class="no-border middle-cell center">
          <div class="header-title"><span>{{ $intervencion->establecimiento_nombre }}</span></div>
          <br>
          <div><span>DEPARTAMENTO DE CENTRO QUIRÚRGICO</span></div>
          <br>
          <div><span>SERVICIO DE HOSPITALIZACIÓN - REPORTE OPERATORIO</span></div>
        </td>
        <td class="no-border logo-pnp-cell">
            <img src="images/logo_pnp.png" width="100rem" />
        </td>
      </tr>
    </table>
    <br>
    <!-- REPORTE OPERATORIO / INSTITUCIÓN -->
    <table>
      <tr><td class="section-title" colspan="2">REPORTE OPERATORIO</td></tr>
      <tr><td class="section-title" colspan="2">DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</td></tr>
      <tr>
        <td class="sub-section-title">CÓDIGO ÚNICO DE RENIPRESS</td>
        <td class="sub-section-title">NOMBRE DE LA IPRESS QUE REALIZA LA ATENCIÓN</td>
      </tr>
      <tr>
        <td class="center">{{ $intervencion->establecimiento_codigo }}</td>
        <td class="center">{{ $intervencion->establecimiento_nombre }}</td>
      </tr>
    </table>

    <!-- DATOS DE IDENTIFICACIÓN DEL PACIENTE -->
    <table style="margin-top: 10px;">
      <tr><td class="section-title" colspan="9">DATOS DE IDENTIFICACIÓN DEL PACIENTE</td></tr>
      <tr>
        <td class="sub-section-title" style="width: 20%;" colspan="2">IDENTIDAD</td>
        <td class="sub-section-title" style="width: 20%;">HISTORIA CLÍNICA</td>
        <td class="sub-section-title" style="width: 20%;" colspan="2">SEXO</td>
        <td class="sub-section-title" style="width: 20%;" colspan="3">EDAD</td>
        <td class="sub-section-title" style="width: 20%;">SERVICIO</td>
      </tr>
      <tr>
        <td class="sub-section-title" style="width: 5%;">TDI</td>
        <td class="sub-section-title" style="width: 18%;">N° DOCUMENTO DE IDENTIDAD</td>
        <td class="center" rowspan="2">{{ $intervencion->asegurado_historia_nro_historia }}</td>
        <td class="center small bold">MASCULINO</td>
        <td class="center">{{ $intervencion->asegurado_grado_abrev == "M" ? "X" : "" }}</td>
        <td class="center small bold">DIA</td>
        <td class="center small bold">MES</td>
        <td class="center small bold">AÑO</td>
        <td class="center" rowspan="2">{{ $intervencion->servicio }}</td>
      </tr>
      <tr>
        <td class="center">{{ $intervencion->tipo_documento_identidad }}</td>
        <td class="center">{{ $intervencion->asegurado_nro_doc_ident }}</td>
        <td class="center small bold">FEMENINO</td>
        <td class="center">{{ $intervencion->asegurado_grado_abrev == "F" ? "X" : "" }}</td>
        <td class="center">{{ $intervencion->edad_dias }}</td>
        <td class="center">{{ $intervencion->edad_meses }}</td>
        <td class="center">{{ $intervencion->edad_anios }}</td>
      </tr>
    </table>

    <!-- Apellidos y Nombres -->
    <table style="margin-top: 10px;">
      <tr>
        <td class="sub-section-title" style="width: 50%;">APELLIDO PATERNO</td>
        <td class="sub-section-title" style="width: 50%;">APELLIDO MATERNO</td>
      </tr>
      <tr>
        <td class="center">{{ $intervencion->asegurado_paterno }}</td>
        <td class="center">{{ $intervencion->asegurado_materno }}</td>
      </tr>
      <tr><td class="sub-section-title" colspan="2">NOMBRES</td></tr>
      <tr><td colspan="2" class="center">{{ $intervencion->asegurado_nombre }}</td></tr>
    </table>

    <!-- TIPO DE ANESTESIA / HORAS -->
    <table style="margin-top: 10px;">
      <tr>
        <td class="sub-section-title" style="width: 20%;">TIPO DE ANESTESIA</td>
        <td class="sub-section-title" style="width: 30%;">HORA DE INICIO DE INTERVENCIÓN</td>
        <td class="sub-section-title" style="width: 25%;">HORA FIN DE INTERVENCIÓN</td>
        <td class="sub-section-title" style="width: 25%;">TIEMPO OPERATORIO</td>
      </tr>
      <tr>
        <td class="center">{{ $intervencion->tipo_anestesia }}</td>
        <td class="center">{{ $intervencion->hora_inicio_intervencion }}</td>
        <td class="center">{{ $intervencion->hora_fin_intervencion }}</td>
        <td class="center">{{ $intervencion->horas_programadas }}</td>
      </tr>
    </table>

    <!-- DIAGNÓSTICO PRE OPERATORIO -->
    <table style="margin-top: 10px;">
      <tr><td class="section-title" colspan="6">DIAGNÓSTICO PRE OPERATORIO</td></tr>
      <tr>
        <td class="sub-section-title" style="width: 5%;">N°</td>
        <td class="sub-section-title" style="width: 10%;">CIE - 10</td>
        <td class="sub-section-title" style="width: 70%;">DESCRIPCIÓN DEL DIAGNÓSTICO</td>
        <td class="sub-section-title" style="width: 21%;" colspan="3">TIPO DE DIAGNÓSTICO</td>
      </tr>
      @if ($pre_diagnosticos && count($pre_diagnosticos) > 0)
        @foreach ($pre_diagnosticos as $i => $pre_diagnostico)
          <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td class="center">{{ $pre_diagnostico->codigo }}</td>
            <td>{{ $pre_diagnostico->nombre }}</td>
            <td ></td>
            <td class="center small bold">D</td>
            <td></td>
          </tr>
        @endforeach
      @endif
    </table>

    <!-- DIAGNÓSTICO POST OPERATORIO -->
    <table style="margin-top: 10px;">
      <tr><td class="section-title" colspan="6">DIAGNÓSTICO POST OPERATORIO</td></tr>
      <tr>
        <td class="sub-section-title" style="width: 5%;">N°</td>
        <td class="sub-section-title" style="width: 15%;">CIE - 10</td>
        <td class="sub-section-title" style="width: 60%;">DESCRIPCIÓN DEL DIAGNÓSTICO</td>
        <td class="sub-section-title" style="width: 20%;" colspan="3">TIPO DE DIAGNÓSTICO</td>
      </tr>
      @if ($post_diagnosticos && count($post_diagnosticos) > 0)
        @foreach ($post_diagnosticos as $i => $post_diagnostico)
          <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td class="center">{{ $post_diagnostico->diagnostico_codigo }}</td>
            <td>{{ $post_diagnostico->diagnostico_nombre }}</td>
            <td class="center small bold">{{ intval($post_diagnostico->tipo_diagnostico_id) === 1 ? "P" : "" }}</td>
            <td class="center small bold">{{ intval($post_diagnostico->tipo_diagnostico_id) === 2 ? "D" : "" }}</td>
            <td class="center small bold">{{ intval($post_diagnostico->tipo_diagnostico_id) === 3 ? "R" : "" }}</td>
          </tr>
        @endforeach
      @endif
    </table>

    <!-- PROCEDIMIENTOS -->
    <table style="margin-top: 10px;">
      <tr><td class="section-title" colspan="5">PROCEDIMIENTOS</td></tr>
      <tr>
        <td class="sub-section-title" style="width: 5%;">N°</td>
        <td class="sub-section-title" style="width: 20%;">Código</td>
        <td class="sub-section-title" style="width: 55%;">DESCRIPCIÓN DEL DIAGNÓSTICO</td>
        <td class="sub-section-title" style="width: 10%;">CANTIDAD</td>
        <td class="sub-section-title" style="width: 10%;">TIEMPO</td>
      </tr>
      @if ($procedimientos && count($procedimientos) > 0)
        @foreach ($procedimientos as $i => $procedimiento)
          <tr>
            <td class="center">{{ $i + 1}}</td>
            <td class="center">{{ $procedimiento->procedimiento_codigo }}</td>
            <td>{{ $procedimiento->procedimiento_descripcion }}</td>
            <td class="center">{{ $procedimiento->cantidad }}</td>
            <td class="center">{{ $procedimiento->tiempo }}</td>
          </tr>
        @endforeach
      @endif
    </table>

    <!-- PROCEDIMIENTOS (CARGO / PROFESIONAL) -->
    <table style="margin-top: 10px;">
      <tr><td class="section-title" colspan="3">PROFESIONALES</td></tr>
      <tr>
        <td class="sub-section-title" style="width: 5%;">N°</td>
        <td class="sub-section-title" style="width: 45%;">CARGO</td>
        <td class="sub-section-title" style="width: 50%;">PROFESIONAL</td>
      </tr>
      @if ($profesionales && count($profesionales) > 0)
        @foreach ($profesionales as $i => $profesional)
          <tr>
            <td class="center">{{ $i + 1}}</td>
            <td>{{ $profesional->cqx_tipo_professional }}</td>
            <td>{{ $profesional->medico_nombre }} {{ $profesional->medico_paterno }} {{ $profesional->medico_materno }}</td>
          </tr>
        @endforeach
      @endif
    </table>
  </div>
</body>
</html>
