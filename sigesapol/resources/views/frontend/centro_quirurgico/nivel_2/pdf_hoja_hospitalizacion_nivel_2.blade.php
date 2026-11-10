<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="centro_quirurgico/nivel2/css/pdf_hoja_hospitalizacion_nivel_2.css" />
    <title>Hoja de Hospitalizacion</title>
</head>

<body>
  <table class="no-border" style="margin-top: {{ isset($is_firma) && $is_firma ? '60px' : '0px' }}">
    <tr>
      <td class="no-border logo-sanidad-cell">
        <img src="images/logo_sanidad.png" width="70rem" />
      </td>
      <td class="no-border middle-cell">
        <span class="name-ipress">{{ $hospitalizacion->establecimiento_nombre }}</span><br>
        <span class="name-service">SERVICIO DE HOSPITALIZACION - ADMISION</span>
      </td>
      <td></td>
    </tr>
  </table>
  <br><br>
  <div class="header">
    <span class="header-title">HOJA DE HOSPITALIZACION</span><br>
    <span class="header-sub-title">DEPARTAMENTO DE CENTRO QUIRURGICO</span>
    <div class="header-description">
      <span>CUPSS PNP: <b>{{ $hospitalizacion->cups_pnp }}</b></span><br>
      <span>COD. SITEDS: <b>{{ $hospitalizacion->cod_siteds_susalud }}</b></span>
    </div>
  </div>
  <table class="table-header">
    <tr>
      <td style="width: 35%"></td>
      <td style="width: 35%"></td>
      <td style="width: 30%">H.C: <b>{{ $hospitalizacion->asegurado_historia_nro }}</b></td>
    </tr>
    <tr>
      <td style="width: 35%"></td>
      <td style="width: 35%"></td>
      <td style="width: 30%">H.CC.A: <b>{{ $hospitalizacion->asegurado_historia_nro }}</b></td>
    </tr>
    <tr>
      <td style="width: 35%"></td>
      <td style="width: 35%"></td>
      <td style="width: 30%">N°ORDEN: <b>{{ $hospitalizacion->asegurado_historia_nro }}</b></td>
    </tr>
    <tr>
      <td style="width: 35%"></td>
      <td style="width: 35%"></td>
      <td style="width: 30%; margin-left: -74.5px !important">FECHA Y HORA DE INGRESO: <b>{{ $hospitalizacion->hospitalizacion_fecha_hora_ingreso }}</b></td>
    </tr>
  </table>
  <br>
  <div class="divider"></div>
  <br>
  <P class="title-section">I. DATOS DEL PACIENTE</P>
  <table class="no-border">
    <tr>
      <td colspan="3">APELLIDOS Y NOMBRES: <b>{{ $hospitalizacion->asegurado_paterno }} {{ $hospitalizacion->asegurado_materno }} {{ $hospitalizacion->asegurado_nombre }}</b></td>
    </tr>
    @if (intval($hospitalizacion->tipo_beneficiario_id) !== 8)
    <tr>
      <td colspan="3">APELLIDOS Y NOMBRES DEL TITULAR: <b>{{ $hospitalizacion->asegurado_titular_paterno }} {{ $hospitalizacion->asegurado_titular_materno }} {{ $hospitalizacion->asegurado_titular_nombre }}</b></td>
    </tr>
    <tr>
      <td colspan="3">PARENTESCO CON EL PACIENTE: <b>{{ $hospitalizacion->asegurado_tipo_parentesco }}</b></td>
    </tr>
    @endif
    <tr>
      <td style="width: 33%;">EDAD: <b>{{ $hospitalizacion->edad }}</b></td>
      <td style="width: 33%;">SEXO: <b>{{ $hospitalizacion->asegurado_sexo }}</b></td>
      <td style="width: 33%;">DNI: <b>{{ $hospitalizacion->asegurado_nro_doc_ident }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%;">RAZA: <b>{{ $hospitalizacion->asegurado_raza }}</b></td>
      <td style="width: 33%;">ESTADO CIVIL: <b>{{ $hospitalizacion->asegurado_estado_civil }}</b></td>
      <td style="width: 33%;">CIP: <b>{{ $hospitalizacion->asegurado_cip }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%;">GRADO DE INSTRUCCION: <b>{{ $hospitalizacion->asegurado_grado }}</b></td>
      <td style="width: 33%;">OCUPACION: <b>{{ $hospitalizacion->asegurado_ocupacion }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%;">LUGAR DE NACIMIENTO: <b>{{ $hospitalizacion->asegurados_provincia_nacimiento }}</b></td>
      <td style="width: 33%;">FECHA NACIMIENTO: <b>{{ $hospitalizacion->fecha_nac }}</b></td>
      <td style="width: 33%;">RELIGION: <b>{{ $hospitalizacion->asegurado_religion }}</b></td>
    </tr>
    <tr>
      <td>DOMICILIO: <b>{{ $hospitalizacion->asegurado_direccion_nacimiento }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%;">DISTRITO: <b>{{ $hospitalizacion->asegurados_distrito_nacimiento }}</b></td>
      <td style="width: 33%;">DEPARTAMENTO: <b>{{ $hospitalizacion->asegurados_departamento_nacimiento }}</b></td>
    </tr>
    <tr>
      <td>TELEFONO: <b>{{ $hospitalizacion->asegurado_telefono }}</b></td>
    </tr>
  </table>
  <br><br>
  <table class="no-border">
    <tr>
      <td>FECHA Y HORA DE INGRESO: <b>{{ $hospitalizacion->hospitalizacion_fecha_hora_ingreso }}</b></td>
    </tr>
    <tr>
      <td>CODIGO SITEDS: <b>{{ $hospitalizacion->cod_siteds_susalud }}</b></td>
    </tr>
    <tr>
      <td>CUPS: <b>{{ $hospitalizacion->cups_pnp }}</b></td>
    </tr>
  </table>
  <br>
  <div class="divider"></div>
  <br>
  <P class="title-section">II. DATOS DEL TITULAR</P>
  <table class="no-border">
    <tr>
      <td style="width: 33%;">NOMBRES Y APELLIDOS: <b>{{ $hospitalizacion->asegurado_titular_nombre }} {{ $hospitalizacion->asegurado_titular_paterno }} {{ $hospitalizacion->asegurado_titular_materno }}</b></td>
      <td style="width: 33%;">DNI: <b>{{ $hospitalizacion->asegurado_titular_nro_doc_ident }}</b></td>
      <td style="width: 33%;">GRADO: <b>{{ $hospitalizacion->asegurado_titular_grado }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%;">CIP: <b>{{ $hospitalizacion->asegurado_titular_cip }}</b></td>
      <td style="width: 33%;">SITUACION: <b>{{ $hospitalizacion->asegurado_titular_situacion_paciente }}</b></td>
    </tr>
  </table>
  <br>
  <div class="divider"></div>
  <br>
  <P class="title-section">III. INGRESO</P>
  <table class="no-border">
    <tr>
      <td>ORIGEN: <b>{{ $hospitalizacion->servicio_origen_nombre }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%;">UPSS ENCARGADO: <b>{{ $hospitalizacion->descripcion_upss }}</b></td>
      <td style="width: 33%;">SERVICIO ENCARGADO: <b>{{ $hospitalizacion->servicio_encargado }}</b></td>
    </tr>
  </table>
  <br>
  <div class="divider"></div>
  <br>
  <P class="title-section">IV. PERSONAL QUE ORDENA LA INTERVENCION QUIRURGICA</P>
  <table class="no-border">
    <tr>
      <td colspan="2">NOMBRE Y APELLIDOS: <b>{{ $hospitalizacion->medico_responsable_cqx_nombre }} {{ $hospitalizacion->medico_responsable_cqx_paterno }} {{ $hospitalizacion->medico_responsable_cqx_materno }}</b></td>
      <td>DNI: <b>{{ $hospitalizacion->medico_responsable_cqx_dni }}</b></td>
    </tr>
    <tr>
      <td style="width: 33%;">PROFESION: <b>{{ $hospitalizacion->medico_responsable_cqx_profesion }}</b></td>
      <td style="width: 33%;">CMP: <b>{{ $hospitalizacion->medico_responsable_cqx_colegiatura }}</b></td>
      <td style="width: 33%;">RNE: <b>{{ $hospitalizacion->medico_responsable_cqx_rne }}</b></td>
    </tr>
  </table>
  <br>
  <div class="divider"></div>
  <br>
  <P class="title-section">V. DIAGNOSTICO</P>
  <table>
    <tr>
      <th class="tb-diag-cell">N°</th>
      <th class="tb-diag-cell">CODIGO</th>
      <th class="tb-diag-cell">DESCRIPCION</th>
      <th class="tb-diag-cell">TIPO</th>
    </tr>
    @if (isset($diagnosticos) && count($diagnosticos) > 0)
        @foreach ($diagnosticos as $key => $diagnostico)
            <tr>
              <td class="tb-diag-cell">{{ $key + 1 }}</td>
              <td class="tb-diag-cell">{{ $diagnostico->codigo }}</td>
              <td class="tb-diag-cell">{{ $diagnostico->nombre }}</td>
              <td class="tb-diag-cell">DEFINITIVO<</td>
            </tr>
        @endforeach
    @endif
  </table>
  <table class="footer">
    <tr>
      <td style="width: 50%">USUARIO REGISTRO: <b>{{ $hospitalizacion->hospitalizacion_medico_nombre }} {{ $hospitalizacion->hospitalizacion_medico_paterno }} {{ $hospitalizacion->hospitalizacion_medico_materno }}</b></td>
      <td style="width: 50%">USUARIO DE IMPRESIÓN: <b>{{ $user->name }} {{ $user->paterno }} {{ $user->materno }}</b></td>
    </tr>
    <tr>
      <td style="width: 50%;">FECHA DE IMPRESION:  <b>{{ $dateCurrent }}</b></td>
      <td style="width: 50%;">HORA DE IMPRESION: <b>{{ $hourCurrent }}</b></td>
    </tr>
  </table>
</body>

</html>

