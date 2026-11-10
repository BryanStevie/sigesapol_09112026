<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RUA</title>
    <style>
      @page { margin-left: 0.15in; margin-right: 0.15in; margin-top: 0.11in; margin-bottom: 0.11in; }
      /* body { margin-left: 0.15in; margin-right: 0.15in; margin-top: 0.11in; margin-bottom: 0.11in; } */
      


      table, td, th{
          border: 1px solid black;
          border-collapse: collapse;
      }

      table.examenes td{
          border: 1px solid black;
      }

      table.table-firmas tr {
            border: none;
            font-size: 7pt; 
        }

        table.table-firmas td {
            border: none;
            font-size: 7pt; 
        }

    

      .n { text-align:right }

      .s { text-align:left }

      .pa{
          padding: 5px
      }

      .img-escudo {
          height: 15px;
      }

      .img-firma {
          height: 80px;
      }

      td.style1 {
          width: 50px;
          padding-top: 4px;
          padding-bottom: 4px;
      }
      td.style2 {
          background-color: red; 
          font-size: 7pt; 
          color: white;
      }
      td.style3 {
          background-color: rgb(75, 75, 75); 
          font-size: 7pt; 
          color: white;
      }
      td.style3-1 {
          background-color: rgb(102, 102, 102); 
          font-size: 7pt; 
          color: white;
      }
      td.style3-2 {
          background-color: rgb(133, 133, 133); 
          font-size: 7pt; 
          color: white;
      }
      td.style4 {
          background: #c6c6c6; 
          font-size: 6pt; 
          color: white;
      }
      td.style5 {
          font-size: 10pt;
          font-weight: 700;
      }
      /**style6 y style7 comparten el padding equitativamente para que no genere el error */
      td.style6 {
          font-size: 6pt;
          font-weight: 700;
          padding-top: 3px;
          padding-bottom: 2px;
      }
      td.style7 {
          font-size: 6pt;
          font-weight: 700;
          padding-top: 2px;
          padding-bottom: 3px;
      }
      td.style8 {
          font-size: 6pt;
          font-weight: 700;
          padding-top: 2px;
          padding-bottom: 2px;
          background: #b5da8e;
      }
      td.style9{
          font-size: 5pt;
          font-weight: 700;
          background: #b5da8e;
      }
      td.style10{
         text-align: center;
          background: #c6c6c6;
          font-size: 7pt;
          font-weight: 700;
      }
      td.style11{
          text-align: start;
          font-size: 7pt;
          font-weight: 700;
          height: 15px;
      }
      td.style11 span{
          text-decoration: underline;
      }
      td.style12{
          font-size: 6pt;
          font-weight: 700;
          background: #c6c6c6;
      }
      td.style13{
          font-size: 6pt;
          font-weight: 700;
          background: #c6c6c6;
      }
      td.style14{
          font-size: 6pt;
          font-weight: 700;
          background: #c6c6c6;
      }
      td.style15{
          font-size: 6pt;
          font-weight: 700;
          background: #c6c6c6;
      }
      td.style16{
          height: 50px;
          border-left: none;
          border-bottom: none;
      }
      td.style17{
          font-weight: 700;
          border-top: none;
      }
      td.style18{
          font-weight: 700;
          border-top: none;
      }
      td.style19{
          font-weight: 700;
          text-align: start;
          border-top: none;
      }
      td.style20{
          font-weight: 700;
          text-align: start;
          border-right: none;
          border-bottom: none;
      }
      td.style21{
          border-bottom: none;
      }

      td.style-columnas{
          vertical-align: top; 
          padding: 5px 5px 5px 5px; 
          height: 465px;
          border-right: none;
          border-left: none
      }

      td.style26 { vertical-align:bottom; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }

      td.style74 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }

      td.style76 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }

      td.style43 { vertical-align:middle; text-align:center; padding-right:0px; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }

      td.style22 { vertical-align:middle; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }

      td.style23 { vertical-align:middle; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }



      .footer-table {
            position: fixed;
            bottom: 110;
            left: 0;
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            border: 1px solid #000;
          
        }
        
        .footer-table .bold {
            font-weight: bold;
        }
        .signature-space {
            height: 90px;
        }
       
      /* Estilos para el header */
      .header-table {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: 1px solid #000;
          
        }

        /* Estilos para el contenido */
        .center-text {
            text-align: center;
        }
  </style>
</head>
<body>
  <?php 
    $quests = [
      1 => "¿TIENE EXAMEN DE ORINA POSITIVO A ITU?",
      2 => "EL PACIENTE TIENE COMORBILIDAD?",
      3 => "¿PACIENTE CON ANTIBIOTICOS LAS ULTIMAS 48 HORAS Y PERSISTE LOS SINTOMAS DE ITU?",
      4 => "¿EL PACIENTE ES O FUE PORTADOR DE SONDA FOLEY RECIENTEMENTE?",
      5 => "¿ES PACIENTE GESTANTE?",
      6 => "¿PACIENTE CON FIEBRE?",
    ]  
  ?>
  <div class="content">
    <table border="1"style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;margin-bottom: 0px; padding-bottom: 0px;border-bottom: solid 0px;">
      <tbody>
        <tr>
            <td style="border: 1px solid #000; height: 25px; font-size: 9px;" colspan="2" rowspan="2" align="center" valign="bottom">
              <img src="images/escudo_republica_peru.png" alt="Escudo Perú" style="width: 40px; height: 28px;">
            </td>
            <td style="border: 1px solid #000; height: 25px; font-size: 9px;" colspan="2" rowspan="2" align="center" valign="middle" bgcolor="#C00000">
              <b><font face="Arial" color="#FFFFFF">PERÚ</font></b>
            </td>
            <td style="border: 1px solid #000; height: 25px; font-size: 9px;padding-left: 5px;" colspan="3" rowspan="2" align="left" valign="middle" bgcolor="#404040">
              <font face="Arial" color="#FFFFFF">Ministerio<br>del Interior</font>
            </td>
            <td style="border: 1px solid #000; height: 25px; font-size: 9px; padding-left: 5px;" colspan="4" rowspan="2" align="left" valign="middle" bgcolor="#595959">
              <font face="Arial" color="#FFFFFF">Policía<br>Nacional del Perú</font>
            </td>
            <td style="border: 1px solid #000; height: 25px; font-size: 9px; padding-left: 5px;" colspan="4" rowspan="2" align="left" valign="middle" bgcolor="#767171">
              <font face="Arial" color="#FFFFFF">{{isset($establecimiento) && !empty($establecimiento[0]->nombre) ? $establecimiento[0]->nombre : ""}}</font>
            </td>
            <td style="border: 1px solid #000; height: 25px; font-size: 9px; padding-left: 5px;" colspan="4" rowspan="2" align="left" valign="middle" bgcolor="#AEAAAA">
              <font face="Arial" color="#FFFFFF">Departamento de<br>Patología Clínica</font>
            </td>
            <td style="border: 1px solid #000; height: 25px; font-size: 9px; " colspan="1" rowspan="4" align="center" valign="middle">
              <img src="images/logo_sanidad_firma.png" alt="Escudo Hospital" style="width: 30px; height: 45px;">
            </td>
            <td style="border: 1px solid #000; height: 25px; font-size: 9px;" colspan="6" rowspan="2" align="center" valign="middle">
              <b><font face="Arial" color="#000000">SGC-DEPPACLI-CAL-FOR.09</font></b>
            </td>
        </tr>
        <tr></tr>
        <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="19" align="center" valign="middle" bgcolor="#3A804D">
            <b><font face="Arial">FORMATO</font></b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="4" align="center" valign="middle">
            <font face="Arial">Versión:</font>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle">
            <font face="Arial">02</font>
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="19" align="center" valign="middle">
            <b>
              <font face="Arial">REGISTRO ÚNICO DE ATENCIÓN (RUA)
                @if (isset($prestacion->id_tipo_atencion))
                  @if (intval($prestacion->id_tipo_atencion) === 2)
                    DE EMERGENCIA
                  @elseif (intval($prestacion->id_tipo_atencion) === 3)
                    DE HOSPITALIZACION
                  @else
                    DE CONSULTA AMBULATORIA
                  @endif
                @else
                  DE CONSULTA AMBULATORIA
                @endif
              </font>
            </b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="4" align="center" valign="middle">
            <font face="Arial">Página:</font>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle">
            @if (strtoupper($prestacion->servicio) === 'CONSULTA AMBULATORIA')
              <font face="Arial">{{'1 de 4'}}</font>
            @elseif(strtoupper($prestacion->servicio) === 'EMERGENCIA')
              <font face="Arial">{{'2 de 4'}}</font>
            @elseif(strtoupper($prestacion->servicio) === 'CUIDADOS INTENSIVOS')
              <font face="Arial">{{'3 de 4'}}</font>
            @elseif(strtoupper($prestacion->servicio) === 'HOSPITALIZACION')
              <font face="Arial">{{'4 de 4'}}</font>
            @elseif(strtoupper($prestacion->servicio) === 'EMERGENCIA PEDIÁTRICA')
              <font face="Arial">{{'1 de 45'}}</font>
            @else
              <font face="Arial">{{'1 de 4'}}</font>
            @endif
          </td>
        </tr>
      </tbody>
    </table>
    <table border="1" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;margin-top: 0px;padding-bottom: 0px;border-top: solid 0px;padding-bottom: 0px;border-bottom: solid 0px;">
      <tbody>
        <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px; width: 125px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
            <b>UNIDAD EJECUTORA</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="3" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>020</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="3" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>CÓDIGO RENAES</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ $establecimiento[0]->codigo }}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>REGIÓN</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
            {{ isset($provincia) && isset($provincia[0]->descripcion) ? $provincia[0]->descripcion : "Lima"}}
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>N° DE RUA</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ isset($prestacion) && isset($prestacion->rua) ? htmlspecialchars($prestacion->rua, ENT_QUOTES, 'UTF-8') : "" }}</b>
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
            <b>SERVICIO</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="3" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ $prestacion->servicio }}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="3" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>FECHA DE ATENCIÓN</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
            @if (strtoupper($prestacion->servicio) === 'EMERGENCIA')
              {{ isset($prestacion->fecha_evaluacion) ? $prestacion->fecha_evaluacion : "" }}
            @else
              {{ isset($prestacion->fecha_atencion) ? $prestacion->fecha_atencion : "" }}
            @endif
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>PRÓXIMA CITA</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
            <b><?php $proxima_cita_texto = isset($proxima_cita) && (isset($proxima_cita->dia) || isset($proxima_cita->hora)) ? $proxima_cita->dia . ' ' . $proxima_cita->hora : '';
              echo "<b>{$proxima_cita_texto}</b>"; ?></b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>N° DE CAMA</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ isset($prestacion) && isset($prestacion->nmro_cama) ? htmlspecialchars($prestacion->nmro_cama, ENT_QUOTES, 'UTF-8') : "" }}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>UBICACIÓN</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
            @if ($prestacion->id_tipo_atencion == 3)
              @if (isset($prestacion->servicio) && isset($prestacion->pabellon))
                <b>{{ htmlspecialchars($prestacion->servicio . ' ' . $prestacion->pabellon, ENT_QUOTES, 'UTF-8') }}</b>
              @elseif ($prestacion->ubicacion)
                <b>{{ htmlspecialchars($prestacion->ubicacion, ENT_QUOTES, 'UTF-8') }}</b>
              @endif
            @elseif ($prestacion->id_tipo_atencion == 2)
              <b>{{ isset($prestacion->ubicacion) ? htmlspecialchars($prestacion->ubicacion, ENT_QUOTES, 'UTF-8') : "" }}</b>
            @else
              <b>{{ isset($prestacion) && isset($prestacion->ubicacion) ? htmlspecialchars($prestacion->ubicacion, ENT_QUOTES, 'UTF-8') : "" }}</b>
            @endif
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left"valign="middle" bgcolor="#BFBFBF">
            <b>NOMBRES Y APELLIDOS</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="10" align="center" valign="middle" bgcolor="#FFFFFF">
            @if (isset($prestacion) && isset($prestacion->asegurado_nombre) && isset($prestacion->asegurado_paterno) && isset($prestacion->asegurado_materno))
              <b>
                {{ htmlspecialchars($prestacion->asegurado_nombre, ENT_QUOTES, 'UTF-8') }}, 
                {{ htmlspecialchars($prestacion->asegurado_paterno, ENT_QUOTES, 'UTF-8') }} 
                {{ htmlspecialchars($prestacion->asegurado_materno, ENT_QUOTES, 'UTF-8') }}
              </b>
            @endif
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>GRADO</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="4" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ isset($prestacion->grado) ? htmlspecialchars($prestacion->grado, ENT_QUOTES, 'UTF-8') : "" }}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>CONDICIÓN</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ isset($prestacion->parentesco) ? htmlspecialchars($prestacion->parentesco, ENT_QUOTES, 'UTF-8') : "" }}</b>
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
            <b>HISTORIA CLÍNICA</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="6" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ isset($prestacion->nro_historia) ? htmlspecialchars($prestacion->nro_historia, ENT_QUOTES, 'UTF-8') : "" }}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>DNI</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ isset($prestacion->nro_doc_ident) ? htmlspecialchars($prestacion->nro_doc_ident, ENT_QUOTES, 'UTF-8') : "" }}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>EDAD</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="4" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ isset($prestacion->edadpaciente) ? htmlspecialchars($prestacion->edadpaciente, ENT_QUOTES, 'UTF-8') : "" }}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>SEXO</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
            @if ($prestacion->sexo == 'F')
              <b>F: X</b>
            @else
              <b>F: __</b>
            @endif

            @if ($prestacion->sexo == 'M')
              <b>M: X</b>
            @else
              <b>M: __</b>
            @endif
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
            <b>DIAGNÓSTICO N° 1</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="16" align="center" valign="middle" bgcolor="#FFFFFF">
            <table style="width: 100%;  border-collapse: collapse; margin: 0; padding: 0;border: none;">
              <tr>
                <td style="border: none; padding: 0;">
                  <table style="margin-left: 10px; max-height:9px;  border:none;">
                    <tr>
                      <td @if (isset($diagnostico)) @if (count($diagnostico) > 0 && $diagnostico[0]->id_tipo_diagnostico == '2') style="background: #c6c6c6; font-weight: 700; font-size: 9px;" @endif @endif >D</td>
                      <td @if (isset($diagnostico)) @if (count($diagnostico) > 0 && $diagnostico[0]->id_tipo_diagnostico == '1') style="background: #c6c6c6; font-weight: 700;" @endif @endif>P</td>
                    </tr>
                  </table>
                </td>
                <td style="border:none;">
                  @if (isset($diagnostico) && count($diagnostico) > 0)
                    {{ $diagnostico[0]->nombre }}
                  @endif
                </td>
              </tr>
            </table>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>CIE-10</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
            @if (isset($diagnostico) && count($diagnostico) > 0)
              <b>{{ $diagnostico[0]->codigo }}</b>
            @endif
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
            <b>DIAGNÓSTICO N° 2</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px; padding: 0;" colspan="16" align="center" valign="middle" bgcolor="#FFFFFF">
            <table style="width: 100%;  border-collapse: collapse; margin: 0; padding: 0;border: none;">
              <tr>
                <td style="border: none; padding: 0;">
                  <table style="margin-left: 10px; font-size: 9px; border:none;">
                    <tr>
                      <td @if (isset($diagnostico)) @if (count($diagnostico) > 1 && $diagnostico[1]->id_tipo_diagnostico == '2') style="background: #c6c6c6; font-weight: 700;" @endif @endif>D</td>
                      <td @if (isset($diagnostico)) @if (count($diagnostico) > 1 && $diagnostico[1]->id_tipo_diagnostico == '1') style="background: #c6c6c6; font-weight: 700;" @endif @endif>P</td>
                      <td style="border:none;">
                        <b>{{ isset($diagnostico) && count($diagnostico) > 1 ? $diagnostico[1]->nombre : "" }}</b>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>CIE-10</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>{{ isset($diagnostico) && count($diagnostico) > 1 ? $diagnostico[1]->codigo : "" }}</b>
          </td>
        </tr>
      </tbody>
    </table>
    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; padding-bottom: 0px;border-top: solid 0px;">
      <tbody>
        <tr>
          <td class="style10" colspan="26">SOLICITUD DE ANÁLISIS CLÍNICOS DE LABORATORIO</td>
        </tr>
      <tbody>
    </table>
    
    <table style="width: 100%; table-layout: fixed;">
      <tr>
        @if ($array_lista_rua && $array_lista_rua > 0)
          @for ($i = 0; $i < count($array_lista_rua); $i++)
            <td style="width: 33.3%; vertical-align: top; padding: 0 5px;">
              @if ($array_lista_rua[$i] && count($array_lista_rua[$i]) > 0)
                <table style="width: 100%; font-size: 8px;">
                  <colgroup>
                    <col style="width: 10%;">
                    <col style="width: 5%;">
                    <col style="width: 80%;">
                    <col style="width: 5%;">
                  </colgroup>
                  <tbody>
                    @foreach ($array_lista_rua[$i] as $key => $proc)
                      @if ($proc->is_header)
                        <tr style="background-color: #91dd91">
                          <td style="text-align: center;"><b>CPMS</b></td>
                          <td style="text-align: center;" colspan="3"><b>{{ $proc->categoria_rua_nombre }}</b></td>
                        </tr>
                      @else
                        <tr>
                          <td style="text-align: center;">{{ $proc->procedimiento_codigo }}</td>
                          <td style="text-align: center; font-weight: bold; {{ isset($proc->id_rua) ? 'background-color: #ACACAC;' : '' }}">{{ isset($proc->id_rua) ? "X" : "" }}</td>
                          <td style="text-align: center; ">{{ $proc->descripcion_corta }}</td>
                          <td style="text-align: center; "></td>
                        </tr>
                        @if (isset($proc->respuestas_urocultivo) && !empty($proc->respuestas_urocultivo))
                          <tr>
                            <td colspan="4">
                              <table style="background-color: #d1e7ea">
                                <tr>
                                  <td colspan="5" style="text-align: center"><span><b>Para solicitar UROCULTIVO LLENAR LA INFORMACIÓN SIGUIENTE QUE CUMPLA CRITERIO:</b></span></td>
                                </tr>
                                <?php $respuestas_urocultivo_array = json_decode($proc->respuestas_urocultivo, true); ?>
                                @foreach ($respuestas_urocultivo_array as $quest)
                                  <tr>
                                    <td>{{ $quests[intval($quest['pregunta'])] }}</td>
                                    <td style="text-align: center;"><b>SI</b></td>
                                    <td style="text-align: center;">{{ $quest['respuesta'] === "SI" ? "X" : "" }}</td>
                                    <td style="text-align: center;"><b>NO</b></td>
                                    <td style="text-align: center;">{{ $quest['respuesta'] === "NO" ? "X" : "" }}</td>
                                  </tr>
                                @endforeach
                              </table>
                            </td>
                          </tr>
                        @endif
                      @endif
                    @endforeach
                  </tbody>
                </table>
              @endif
            </td>
          @endfor
        @endif
      </tr>
    </table >

    <table class="footer-table" style="font-family: Arial, sans-serif;">
      <tbody>
        <tr>
          <td colspan="26" class="nota" style="text-align: center; border: 1px solid #000; height: 10px; font-size: 9px;"  valign="middle" bgcolor="#BFBFBF">
            <span class="bold">NOTA</span>: Toda modificación del presente formato por personal no autorizado, 
            <span class="bold">INVALIDARÁ</span> la atención del paciente en el Servicio de Pre-analíticos.
          </td>
        </tr>
        <tr>
          <td rowspan="2" style=" width:60px ; border: 1px solid #000; height: 10px; font-size: 9px;"  valign="middle" bgcolor="#BFBFBF">N. DE EXÁMENES</td>
          <td colspan="6" style="border: 1px solid #000; height: 10px; font-size: 9px; text-align: center;"  valign="middle" bgcolor="#BFBFBF">MÉDICO TRATANTE</td>
          <td colspan="8" style="border: 1px solid #000; height: 10px; font-size: 9px; text-align: center;"  valign="middle" bgcolor="#BFBFBF">ENCARGADO DE LA ATENCIÓN DEL LABORATORIO</td>
          <td colspan="11" style="border: 1px solid #000; height: 10px; font-size: 9px; text-align: center;"  valign="middle" bgcolor="#BFBFBF">PACIENTE O APODERADO</td>
        </tr>
        <tr>
          <td rowspan="2" colspan="6" class="signature-space">
            <div align="center" style="height: 60px;">
                <table class="table-firmas style24" width="100%" >
                  <tbody>
                      <tr>
                          <td width="40%"><p align="right"><img src="images/logo_sanidad.png" width="55px;" /></p></td>
                          <td width="60%"><p align="left">Firmado digitalmente por: <br/>
                            <?php echo $prestacion->medico_nombre." ".$prestacion->medico_paterno." ".$prestacion->medico_materno ?><br/>
                            Motivo:Soy el autor del documento<br/>Fecha: <?php $fecha_firma = date("d/m/Y h:i:s ", strtotime($laboratorio->fecha_firma));echo $fecha_firma; ?></p>
                          </td>
                      </tr>
                  </tbody>
                </table>
            </div>
          </td>
          <td rowspan="2" colspan="8" class="signature-space"></td>
          <td rowspan="2" colspan="11" class="signature-space"></td>
        </tr>
        <tr>
          <td rowspan="2" >
            <span style="font-size: 8px;"> Process :</span> 
            <span class="bold" >{{ $count_examns_processed }} </span><br> 
            <span style="font-size: 8px;"> SR. :</span>
            <span class="bold" > {{ $count_sin_reactivo }}</span>
          </td>
        </tr>
        <tr>
          <td colspan="6"  class="center-text" style="border-top:0px;font-size: 9px;">
            <span class="bold">ESPE. </span>{{ $prestacion->especializacion }}<span class="bold"> CMP:</span>{{$prestacion->colegiatura}} <span class="bold">RNE:</span>{{$prestacion->rne}}
          </td>
          <td colspan="8" class="center-text" style="border-top:0px;font-size: 9px;">Firma, Sello</td>
          <td colspan="11" class="center-text" style="font-size: 9px;">Nombres y Apellidos:</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>
