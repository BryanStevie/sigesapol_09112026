<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="centro_quirurgico/nivel2/css/pdf_alta_crisis_nivel_2.css" />
    <title>Document</title>
</head>

<body>
    <table class="header-table" style="margin-top: {{ isset($is_firma) && $is_firma ? "60px" : "0px" }}">
      <tr>
        <td class="header-cell left-cell">
          <table class="inner-table">
            <tr><td class="inner-logo"><img src="{{ public_path('images/logo_pnp.png') }}" class="logo-header"></td></tr>
            <tr><td class="inner-text">MINISTERIO DEL INTERIOR<br>POLICIA NACIONAL DEL PERÚ</td></tr>
          </table>
        </td>
        <td class="header-cell right-cell">
          <table class="inner-table">
            <tr><td class="inner-logo"><img src="{{ public_path('images/logo_sanidad.png') }}" class="logo-header"></td></tr>
            <tr><td class="inner-text">DIRECCIÓN DE SANIDAD<br>{{ $nombre_establecimiento }}</td></tr>
          </table>
        </td>
      </tr>
    </table>
    <p class="title">EPICRISIS</p>
    <table class="table-content">
      <tr class="row-border">
        <td style="width: 25%"><b>FECHA INGRESO:</b> {{ date('d/m/Y', strtotime($hospitalizacion->fecha_ingreso)) }}</td>
        <td style="width: 25%"><b>HORA:</b> {{ date('H:i a', strtotime($hospitalizacion->hora_ingreso)) }}</td>
        <td style="width: 50%"><b>SERVICIO A CARGO:</b> {{ $hospitalizacion->servicio_encargado_nombre }}</td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>RESUMEN DE LA ENFERMEDAD ACTUAL</b> <br>
          <span>{{ $epicrisis->egreso_resumen }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>EXAMEN CLINICO</b> <br>
          <span>{{ $epicrisis->egreso_examen_clinico }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>EXAMENES AUXILIARES</b> <br>
          <span>{{ $epicrisis->egreso_examen_auxiliar }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>EVOLUCION</b> <br>
          <span>{{ $epicrisis->egreso_evolucion }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>TRATAMIENTO</b> <br>
          <span>{{ $epicrisis->egreso_tratamiento }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>PROCEDIMIENTOS TERAPEUTICOS Y/O DIAGNOSTICOS</b>
          <br> <span>{{ $epicrisis->egreso_procedimiento }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>COMPLICACIONES</b><br>
          <span>{{ $epicrisis->egreso_complicacion }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 25%"><b>FECHA EGRESO:</b> {{date("d/m/Y", strtotime($hospitalizacion->fecha_alta_medica))}}</td>
        <td style="width: 25%"><b>HORA:</b> {{date("H:i a", strtotime($hospitalizacion->hora_alta_medica))}}</td>
        <td style="width: 50%"><b>ESTADIA TOTAL:</b> {{ $dia_estancia }} DIAS</td>
      </tr>
      <?php
        $tipo_egreso = "";
        if($hospitalizacion->id_tipo_egreso=="1"){
            $tipo_egreso = "ALTA";
        }else if($hospitalizacion->id_tipo_egreso=="2"){
            $tipo_egreso = "HOSPITALIZACION";
        }else if($hospitalizacion->id_tipo_egreso=="3"){
            $tipo_egreso = "TRANSFERIDO";
        }else if($hospitalizacion->id_tipo_egreso=="4"){
            $tipo_egreso = "FALLECIDO";
        }else if($hospitalizacion->id_tipo_egreso=="7"){
            $tipo_egreso = "RETIRO VOLUNTARIO";
        }else if($hospitalizacion->id_tipo_egreso=="6"){
            $tipo_egreso = "FUGA";
        }

        $condicion_egreso = "";
        if($hospitalizacion->id_condicion_alta=="1"){
            $condicion_egreso = "MEJORADO";
        }else if($hospitalizacion->id_condicion_alta=="2"){
            $condicion_egreso = "CURADO";
        }else if($hospitalizacion->id_condicion_alta=="3"){
            $condicion_egreso = "TRASLADO";
        }else if($hospitalizacion->id_condicion_alta=="4"){
            $condicion_egreso = "RETIRO VOLUNTARIO";
        }else if($hospitalizacion->id_condicion_alta=="5"){
            $condicion_egreso = "FUGADO";
        }else if($hospitalizacion->id_condicion_alta=="6"){
            $condicion_egreso = "FALLECIDO";
        }
      ?>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>TIPO DE EGRESO:</b> {{ $tipo_egreso }}</td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>CONDICIÓN DE ALTA:</b> {{ $condicion_egreso }}</td>
      </tr>
    </table>
    <table class="table-content-diagnostico">
      <tr>
        <td style="width: 20%;">#</td>
        <td style="width: 80%"><b>DIAGNOSTICO DE ALTA</b></td>
      </tr>
      @if ($diagnosticos_egreso && count($diagnosticos_egreso) > 0)
        @foreach ($diagnosticos_egreso as $i => $diagnostico)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $diagnostico->nombre }}</td>
          </tr>
        @endforeach
      @endif
    </table>
    <?php
      $necropsia = "NO";
      if ($hospitalizacion->necropsia == 1 || $hospitalizacion->necropsia == "SI") {
        $necropsia = "SI";
      }
    ?>
    <table class="table-content">
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>NECROPSIA:</b> {{ $necropsia }}</td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>MEDIDAS GENERALES AL ALTA</b> <br>
          <span>{{ $epicrisis->egreso_medidas_generales }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>MEDICAMENTOS PRESCRITOS AL ALTA</b> <br>
          <span>{{ $epicrisis->egreso_medicamentos_prescritos }}</span>
        </td>
      </tr>
      <tr class="row-border">
        <td style="width: 100%" colspan="3"><b>PRONOSTICO</b> {{ $epicrisis->egreso_pronostico }}</td>
      </tr>
    </table>
    <br>
    <table class="table-content">
      <tr class="row-border-two">
        <td style="width: 20%"><b>APELLIDO PATERNO</b></td>
        <td style="width: 20%"><b>APELLIDO MATERNO</b></td>
        <td style="width: 20%"><b>APELLIDO NOMBRES</b></td>
        <td style="width: 20%"><b>HISTORIA CLINICA</b></td>
        <td style="width: 20%"><b>SERVICIO</b></td>
      </tr>
      <tr class="row-border-two">
        <td style="width: 20%">{{ $hospitalizacion->paterno_beneficiario }}</td>
        <td style="width: 20%">{{ $hospitalizacion->materno_beneficiario }}</td>
        <td style="width: 20%">{{ $hospitalizacion->nombre_beneficiario }}</td>
        <td style="width: 20%">{{ $hospitalizacion->nro_historia }}</td>
        <td style="width: 20%">{{ $hospitalizacion->servicio_encargado_nombre }}</td>
      </tr>
    </table>
</body>

</html>
