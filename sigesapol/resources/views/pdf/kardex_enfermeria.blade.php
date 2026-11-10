<?php
    use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDF-KARDEX-ENFERMERIA</title>
  <link rel="stylesheet" type="text/css" href="css/pdf.css" />
  <style>
    .td_left {
      text-align: left !important;
    }

    .td_ancho_n {
      width: 4%;
      text-align: center
    }

    .cantidad {
      width: 11%;
    }

    .td_ancho_espacios {
      width: 5%;
    }

    .td_ancho_codigo {
      width: 10%;
    }

    .td_ancho_especialidad {
      width: 25%;
    }

    td {
      padding-left: 2px !important;
    }

    table {
      font-size: 11px;
      font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    .ancho_nro {
      width: 5%;
    }

    .ancho_producto {
      width: 45%;
    }

    .titulos {
      padding: 0px 10px;
    }

    h2 {
      font-size: 15px !important
    }

    .titulo_principal {
      width: 60%;
    }

    .logo {
      width: 15%;
    }

    .grado {
      width: 14%;
    }

    .dni {
      width: 8%;
    }

    .rectangulo {
      padding: 3px
    }

    footer { 
      border: 0px;
      margin-left: 10px;
      font-size: 7px !important;
      font-family: Arial, Helvetica, sans-serif
    }

    .strikethrough {
      color: #dc3545;
      text-decoration: line-through;
    }
  </style>
</head>
<body style="font-family: arial; padding: 0px; background-color:white;">

  <table width="100%" style="margin: 0px;">
      <tr>
        <td width="5%"><div style="margin-top: -5.5%"><img src="images/logo_sanidad.png" width="40rem"/></div></td>
        <td width="65%" style="text-align: right">
          <div style="margin-top: -5%">
            <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0px; font-weight: bold; text-transform: uppercase;color: red;">{{$enfermeria->establecimiento}}</span>
            <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0px; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE EMERGENCIA</span>
          </div>
        </td>
        <td width="20%" style="padding: 0px;">
          <table style="font-size: 1rem; font-weight: bold; margin: 0% 0px 0px 0px;">
            <tr>
              <td width="50%" style="text-align:left; margin: 0;">H.C:</td>
              <td width="50%" style="text-align:left; margin: 0;">{{$enfermeria->historia_clinica}}</td>
            </tr>
            <tr>
              <td width="50%" style="text-align:left; margin: 0;">H.C.A:</td>
              <td width="50%" style="text-align:left; margin: 0;">{{$enfermeria->historia_clinica}}</td>
            </tr>
            <tr>
              <td width="50%" style="text-align:left; margin: 0;">EMERGENCIA:</td>
              <td width="50%" style="text-align:left; margin: 0;">{{$enfermeria->nro_emergencia}}</td>
            </tr>
          </table>
        </td> 
      </tr>
  </table>

  <table width="100%" style="margin: 0px;">
    <tbody>
        <tr width="100%">
          <td style="text-align: center">
            <span style="margin: 0; font-weight: bold; font-size: 20px;">KARDEX DE ENFERMERIA</span><br>
          </td>
        </tr>
        <tr width="100%">
          <td style="text-align: center">
            <span style="margin: 0; font-weight: bold; font-size: 20px;">{{sprintf('%08d', $kardexEnfermeria->id)}}</span>
          </td>
        </tr>
    </tbody>
  </table>

  <p style="font-weight: bold; font-size: 15px; padding: 0px 0px 0px 0px;">DATOS DEL PACIENTE</p>
  <table width="100%" style="margin: 0px;">
    <tbody>
      <tr>
        <td width="55%" style="text-align: left; margin: 0px;">
          <span style="font-size: 11px; margin: 0px;"><b>DNI {{$enfermeria->dni}} - {{$enfermeria->asegurado_nombre}} {{$enfermeria->asegurado_paterno}} {{$enfermeria->asegurado_materno}}</b></span>
        </td>
        <td width="10%">
          <span style="margin: 0px 20px 0px 0px;">SEXO: <b>{{$enfermeria->sexo}}</b></span>
        </td>
        <td width="35%" style="text-align: left; font-size: 11px; margin: 0px; text-align: right">
          <span style="margin: 0px; text-align: right;">EDAD: <b>{{$enfermeria->asegurado_edad_anios}} AÑOS CON {{$enfermeria->asegurado_edad_meses}} MESES Y {{$enfermeria->asegurado_edad_dias}} DÍAS</b></span>
        </td>
      </tr>
    </tbody>
  </table>
  
  <table width="100%" style="margin: 0px;">
    <tbody>
      <tr>
        <td width="25%" style="text-align: left;">F.NACIMIENTO: <b>{{Carbon::parse($enfermeria->asegurado_fecha_nacimiento)->format('d-m-Y')}}</b></td>
        <td width="25%" style="text-align: left;">TIPO BENEF: <b>{{$enfermeria->asegurado_tipo_beneficiario}}</b></td>
      </tr>
      <tr>
        <td width="25%" style="text-align: left;">TALLA: <b>{{$enfermeria->asegurado_talla}} CM</b></td>
        <td width="25%" style="text-align: left;">PESO: <b>{{$enfermeria->asegurado_peso}} KG</b></td>
        <td width="25%" style="text-align: left;">TIPO DE SANGRE: <b>{{$enfermeria->asegurado_tipo_sangre}}</b></td>
      </tr>
    </tbody>
  </table>
  
  <table width="100%" style="margin: 3px 0px;font-size: 10px">
    <tr><td width="100%" style="text-align: left;">ACOMPAÑADO POR: <b>{{$enfermeria->asegurado_acompañante}}</b></td></tr>
  </table>

{{--   <p style="font-weight: bold; font-size: 15px; padding: 0px 0px 0px 0px; border-top: 2px solid #000000;">DATOS DE INGRESO</p>
  <table width="100%" style="margin: 0px;font-size: 10px">
    <tr>
      <td width="40%" style="text-align: left;">FECHA INTERNAMIENTO: <b>{{Carbon::parse($enfermeria->fecha_registro_ingreso)->format('d-m-Y H:i')}}</b></td>
      <td width="20%" style="text-align: left;">NIVEL IPRESS: <b>{{$enfermeria->nivel_ipress}}</b></td>
      <td width="40%" style="text-align: right;">CÓDIGO IPRESS: <b>{{$enfermeria->codigo_renipress}}</b></td>
    </tr>
    <tr>
      <td width="40%" style="text-align: left;">SERVICIO: <b>{{$enfermeria->servicio}}</b></td>
      <td width="30%" style="text-align: left;">PABELLON: <b>{{$enfermeria->pabellon}}</b></td>
      <td width="30%" style="text-align: right;">PISO / CUARTO / N° CAMA: <b>{{$enfermeria->piso}} / {{$enfermeria->cuarto}} / {{$enfermeria->cama}}</b></td>
    </tr>
  </table>

  <p style="font-weight: bold; font-size: 15px; padding: 0px 0px 0px 0px; margin: 0px; border-top: 2px solid #000000;">USUARIO RESPONSABLE DEL TRIAJE</p>
  <table width="100%" style="margin: 0px;font-size: 10px">
    <tr>
      <td width="40%" style="text-align: left;"><b>{{$enfermeria->nombre_medico}} {{$enfermeria->paterno_medico}} {{$enfermeria->materno_medico}}</b></td>
      <td width="20%" style="text-align: left;">DNI: <b>{{$enfermeria->dni_medico}}</b></td>
      <td width="40%" style="text-align: right;">ESPECIALIDAD <b>{{$enfermeria->especialidad_medico}}</b></td>
    </tr>
  </table> --}}
  
{{--   <p style="font-weight: bold; font-size: 15px; padding: 5px 0px 0px 0px; border-top: 2px solid #000000;">DIAGNOSTICOS DE ATENCIÓN</p>
  <table width="100%" style="margin: 0px 0px 10px 0px; border-collapse: collapse;font-size: 10px">
    <tr>
      <th width="5%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">N</th>
      <th width="15%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">CODIGO</th>
      <th width="50%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">DESCRIPCIÓN</th>
      <th width="15%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">TIPO</th>
    </tr>
    @if (isset($diagnosticos) && is_array($diagnosticos) && count($diagnosticos) > 0)
      @foreach ($diagnosticos as $index => $diagnostico)
        <tr>
          <td style="text-align: left;border: 1px solid #000000;">{{$index}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{$diagnostico->codigo_diagnostico}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{$diagnostico->descripcion_diagnostico}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{$diagnostico->descripcion_tipo_diagnosticos}}</td>
        </tr>
      @endforeach
    @endif
  </table> --}}

{{--   <p style="font-weight: bold; font-size: 15px; padding: 5px 0px 0px 0px; border-top: 2px solid #000000;">TRATAMIENTO Y/O TERAPEUTICA</p>
  <div style="margin: 10px 0px"><span style="margin: 0px">ESTO ES UNA PRUEBA PARA VER COMO QUEDA EN EL MODULO DE KARDEX</span></div> --}}

  <p style="font-weight: bold; font-size: 15px; padding: 5px 0px 0px 0px; border-top: 2px solid #000000;">&nbsp;KARDEX</p>
  <table width="100%" style="margin: 0px 0px 10px 0px; border-collapse: collapse;font-size: 10px">
    <tr>
      <th width="22%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">&nbsp;MEDICACIÓN-TRATAMIENTO</th>
      {{-- <th width="20%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">HORAS QUE SE VA SUMINISTRAR EL MEDICAMENTO</th> --}}
      <th width="40%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">&nbsp;HORARIO</th>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">&nbsp;VIA</th>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">FRECUENCIA</th>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">&nbsp;DÍAS</th>
      <th width="8%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">&nbsp;ESTADO</th>
    </tr>
    @if (is_array($kardex_medicamento_enfermeria_group) && !empty($kardex_medicamento_enfermeria_group))
      @foreach ($kardex_medicamento_enfermeria_group as $index => $medicamento_group)
        <tr>
          <td colspan="6" style="text-align: center;border: 1px solid #000000; background-color: #000000; color: #ffffff;">
            <?php
              switch (intval($index)) {
                case 0:
                  echo "<b>HIDRATACIONES</b>";
                  break;
                case 1:
                    echo "<b>INFUSIONES</b>";
                    break;
                case 2:
                    echo "<b>ANTIBIÓTICOS</b>";
                    break;
                case 3:
                    echo "<b>OTROS</b>";
                    break;
                case 4:
                    echo "<b>INHALADORES</b>";
                    break;
                case 5:
                    echo "<b>TRANSFUSIONES</b>";
                    break;
                case 6:
                    echo "<b>HEMOGLUCOTESTES</b>";
                    break;
                default:
                    echo "<b>CATEGORÍA DESCONOCIDA</b>";
                    break;
              }
            ?>
          </td>
        </tr>
        @foreach ($medicamento_group as $medicamento)
          <tr>
            <td style="width: 22%;text-align: left;border: 1px solid #000000;">{{isset($medicamento->medicamento) ? $medicamento->medicamento : $medicamento->infusion_descripcion}}</td>
            <td style="width: 40%;text-align: left;border: 1px solid #000000;">
              @if (!$medicamento->horarios->isEmpty())
                  <span>
                    @foreach ($medicamento->horarios as $index => $horarioItem)
                        <b class='{{ intval($horarioItem->aplicacion) === 1 ? "strikethrough" : ""}}'>
                          {{Carbon::parse($horarioItem->hora)->format('H:i')}}
                        </b><b>@if (count($medicamento->horarios) > $index + 1) -@endif</b>
                    @endforeach
                  </span>
              @endif
            </td>
            <td style="width: 10%;text-align: left;border: 1px solid #000000;">{{$medicamento->via_administracion_descripcion}}</td>
            <td style="width: 10%;text-align: left;border: 1px solid #000000;">C/ {{isset($medicamento->frecuencia) ? $medicamento->frecuencia : "0"}}</td>
            <td style="width: 10%;text-align: left;border: 1px solid #000000;">{{isset($medicamento->dias_administracion) ? $medicamento->dias_administracion : "0"}} DÍAS</td>
            <td style="text-align: center; width: 8%;border: 1px solid #000000;">{{$medicamento->estado ? 'Activo' : 'Suspendido'}}</td>
          </tr>
        @endforeach
      @endforeach
    @endif
    {{-- @if (!$kardex_medicamento_enfermeria->isEmpty())
        @foreach ($kardex_medicamento_enfermeria as $medicamento)
          <tr>
            <td style="width: 30%;text-align: left;border: 1px solid #000000;">{{isset($medicamento->medicamento) ? $medicamento->medicamento : $medicamento->infusion_descripcion}}</td>
            <td style="width: 40%;text-align: left;border: 1px solid #000000;">
              @if (!$medicamento->horarios->isEmpty())
                  <span>
                    @foreach ($medicamento->horarios as $index => $horarioItem)
                        <b class='{{ intval($horarioItem->aplicacion) === 1 ? "strikethrough" : ""}}'>
                          {{Carbon::parse($horarioItem->hora)->format('H:i')}}
                        </b><b>@if (count($medicamento->horarios) > $index + 1) -@endif</b>
                    @endforeach
                  </span>
              @endif
            </td>
            <td style="width: 10%;text-align: left;border: 1px solid #000000;">{{$medicamento->via_administracion_descripcion}}</td>
            <td style="width: 10%;text-align: left;border: 1px solid #000000;">C/ {{isset($medicamento->frecuencia) ? $medicamento->frecuencia : "0"}}</td>
            <td style="width: 10%;text-align: left;border: 1px solid #000000;">{{isset($medicamento->dias_administracion) ? $medicamento->dias_administracion : "0"}} DÍAS</td>
          </tr>
        @endforeach
    @endif --}}
  </table>

  <p style="font-weight: bold; font-size: 15px; padding: 5px 0px 0px 0px; border-top: 2px solid #000000;">EXAMENES AUXILIARES</p>
  <table width="100%" style="margin: 0px 0px 10px 0px; border-collapse: collapse; font-size: 10px">
    <tr>
      <th colspan="6" width="20%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">EXAMEN DE LABORATORIO</th>
    </tr>
    <tr>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">FECHA</th>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">HORA</th>
      <th width="30%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">NOMBRE DEL EXAMEN</th>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">TOMA DE MUESTRA </th>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">RESULTADO</th>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">SIN  REACTIVO</th>
    </tr>
    @if (!$examenLaboratorioKardexes->isEmpty())
      @foreach ($examenLaboratorioKardexes as $examenLaboratorio)
        <tr>
          <td style="text-align: left;border: 1px solid #000000;">{{Carbon::parse($examenLaboratorio->fecha)->format('d-m-Y')}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{Carbon::parse($examenLaboratorio->hora)->format('H:i')}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{$examenLaboratorio->nombre_procedimiento}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{intval($examenLaboratorio->toma_muestra) === 1 ? "SI" : "NO"}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{intval($examenLaboratorio->resultado) === 1 ? "SI" : "NO"}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{intval($examenLaboratorio->reactivo) === 1 ? "SI" : "NO"}}</td>
        </tr>
      @endforeach
    @endif
  </table>
  <table width="100%" style="margin: 0px 0px 10px 0px; border-collapse: collapse;">
    <tr>
      <th colspan="4" width="20%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">EXAMEN DE IMAGENOLOGIA</th>
    </tr>
    <tr>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">FECHA</th>
      <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">HORA</th>
      <th width="50%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">NOMBRE DEL EXAMEN</th>
      <th width="15%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">TOMA DE  IMAGEN</th>
    </tr>
    @if (!$examenImagenKardexes->isEmpty())
      @foreach ($examenImagenKardexes as $examenImagen)
        <tr>
          <td style="text-align: left;border: 1px solid #000000;">{{Carbon::parse($examenImagen->fecha)->format('d-m-Y')}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{Carbon::parse($examenImagen->hora)->format('H:i')}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{$examenImagen->nombre_imagen}}</td>
          <td style="text-align: left;border: 1px solid #000000;">{{intval($examenImagen->toma_imagen) === 1 ? "SI" : "NO"}}</td>
        </tr>
      @endforeach
    @endif
  </table>

  @if (isset($cuidadoEnfermeros))
  <p style="font-weight: bold; font-size: 15px; padding: 5px 0px 0px 0px; border-top: 2px solid #000000;">CUIDADOS DEL ENFERMERO</p>
  <table width="100%">
    <tr>
      <td width="50%"><table width="100%" style="margin: 0px"><tr><td>CONTROL DE FUNCIONES VITALES  (CFV) : </td><td><b>{{intval($cuidadoEnfermeros->cfv) === 1 ? "SI" : "NO"}}</b></td></tr></table></td>
      <td width="50%"><table width="100%" style="margin: 0px"><tr><td>OXIGENOTERAPIA : </td><td><b>{{$cuidadoEnfermeros->oxigeno_terapia_descripcion}} - {{$cuidadoEnfermeros->oxigenoterapia_cantidad}}</b></td></tr></table></td>
    </tr>
    <tr>
      <td width="50%"><table width="100%" style="margin: 0px"><tr><td>BALANCE HÍDRICO ESTRICTO  (BHE) : </td><td><b>{{intval($cuidadoEnfermeros->bhe) === 1 ? "SI" : "NO"}}</b></td></tr></table></td>
      <td width="50%"><table width="100%" style="margin: 0px"><tr><td>MOVILIZACION DE CADA 2 HORAS : </td><td><b>{{intval($cuidadoEnfermeros->movilizacion) === 1 ? "SI" : "NO"}}</b></td></tr></table></td>
    </tr>
    <tr>
      <td width="50%"><table width="100%" style="margin: 0px"><tr><td>CABECERA : </td><td><b>{{$cuidadoEnfermeros->cabecera != null ? $cuidadoEnfermeros->cabecera : '-'}}</b></td></tr></table></td>
      {{-- <td width="50%"><table width="100%" style="margin: 0px"><tr><td>HEMOGLUCOTEST : </td><td><b>{{$cuidadoEnfermeros->hemoglucotest}}</b></td></tr></table></td> --}}
      <td width="50%">
        <table width="100%" style="margin: 0px">
            <tr>
                <td>HEMOGLUCOTEST : </td>
                <td>
                    <b>
                        {{ implode(', ', json_decode($cuidadoEnfermeros->hemoglucotest, true) ?: []) ?: '-' }}
                    </b>
                </td>
            </tr>
        </table>
      </td>
    </tr>
  </table>
  @endif

  <p style="font-weight: bold; font-size: 15px; padding: 5px 0px 0px 0px; border-top: 2px solid #000000;">INTERCONSULTA</p>
  <table width="100%" style="margin: 0px 0px 10px 0px; border-collapse: collapse; font-size: 10px">
    <tr>
      <th width="40%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">NOMBRE DEL SERVICIO</th>
      <th width="15%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">FECHA DE ENVIO</th>
      <th width="15%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">HORA ENVIO</th>
      <th width="15%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">FECHA RESPUESTA</th>
      <th width="15%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">HORA RESPUESTA</th>
    </tr>
    @if (!$interconsultas->isEmpty())
        @foreach ($interconsultas as $interconsulta)
          <tr>
            <td style="text-align: left;border: 1px solid #000000;">{{$interconsulta->consultorio_nombre}}</td>
            <td style="text-align: left;border: 1px solid #000000;">{{Carbon::parse($interconsulta->fecha_envio)->format('d-m-Y')}}</td>
            <td style="text-align: left;border: 1px solid #000000;">{{Carbon::parse($interconsulta->hora_envio)->format('H:i')}}</td>
            <td style="text-align: left;border: 1px solid #000000;">{{Carbon::parse($interconsulta->fecha_respuesta)->format('d-m-Y')}}</td>
            <td style="text-align: left;border: 1px solid #000000;">{{Carbon::parse($interconsulta->hora_respuesta)->format('H:i')}}</td>
          </tr>
        @endforeach
    @endif
  </table>
</body>
</html>