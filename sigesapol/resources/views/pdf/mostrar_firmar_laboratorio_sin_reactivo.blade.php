<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RUA Sin Reactivo</title>

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

        .page-break {
            page-break-after: always;
        }

        .tramite_reembolso {
            border: 1px solid black;
            font-size: 14px;
            margin: 0;
            margin-top: 50px;
            margin-left: 30px;
            padding: 25px;
            width: 300px;
        }

        .tramite_reembolso td.first_column {
            vertical-align: top;
            text-align: center;
            width: 5% !important;
        }

        .tramite_reembolso td.second_column {
            width: 95% !important;
        }

  </style>
</head>
<body>
 


<div class="content">
  <!-- Tu contenido aquí -->
  <table border="1" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;margin-bottom: 0px; padding-bottom: 0px;border-bottom: solid 0px;">
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
            <font face="Arial" color="#FFFFFF"><?php
              if (isset($establecimiento) && !empty($establecimiento[0]->nombre)) {
                  echo $establecimiento[0]->nombre;
              }
              ?></font>
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
            <b><font face="Arial">REGISTRO ÚNICO DE ATENCIÓN (RUA)
              <?php
                      if(isset($prestacion->id_tipo_atencion)){
                        if ($prestacion->id_tipo_atencion == 2) {
                            echo " DE EMERGENCIA";
                        } elseif ($prestacion->id_tipo_atencion == 3) {
                            echo " DE HOSPITALIZACION";
                        } else {
                          echo " DE CONSULTA AMBULATORIA";
                        }
                      }else{
                        echo " DE CONSULTA AMBULATORIA";
                      }
              ?>  
            </font></b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="4" align="center" valign="middle">
            <font face="Arial">Página:</font>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle">
            <font face="Arial">
              <?php
              if ($consultorio->nombre == 'CONSULTA AMBULATORIA') {
                  echo "1 de 4";
              } elseif ($consultorio->nombre == 'EMERGENCIA') {
                  echo "2 de 4";
              } elseif ($consultorio->nombre == 'CUIDADOS INTENSIVOS') {
                  echo "3 de 4";
              } elseif ($consultorio->nombre == 'HOSPITALIZACION') {
                  echo "4 de 4";
              } elseif ($consultorio->nombre == 'EMERGENCIA PEDIÁTRICA') {
                  echo "1 de 45";
              }else {
                echo "1";
              }
              ?></font>
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
              <b>{{$establecimiento[0]->codigo}}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>REGIÓN</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
              <b><?php
                // O más completo, verificando primero si el array existe
                if (isset($Provincia) && isset($Provincia[0]->descripcion)) {
                    // existe y no está vacío
                    echo $Provincia[0]->descripcion;
                }else{
                  echo "Lima";
                }
                ?></b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>N° DE RUA</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
              <b>  <?php
                if (isset($laboratorio) && isset($laboratorio->rua)) {
                    echo htmlspecialchars($laboratorio->rua, ENT_QUOTES, 'UTF-8');
                }
                ?></b>
                
          </td>
      </tr>
      <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
              <b>SERVICIO</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="3" align="center" valign="middle" bgcolor="#FFFFFF">
              <b>{{$consultorio->nombre}}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="3" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>FECHA DE ATENCIÓN</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
              <b>{{ date('d-m-Y', strtotime($fecha_atencion)) }}</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>PRÓXIMA CITA</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
              <b><?php
                $proxima_cita_texto = (isset($proxima_cita) && 
                                       (isset($proxima_cita->dia) || isset($proxima_cita->hora)))
                                       ? $proxima_cita->dia . ' ' . $proxima_cita->hora
                                       : '';     
                echo "<b>{$proxima_cita_texto}</b>";
                ?></b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>N° DE CAMA</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
              <b>
                <?php
                if (isset($prestacion) && isset($prestacion->nmro_cama)) {
                    echo htmlspecialchars($prestacion->nmro_cama, ENT_QUOTES, 'UTF-8');
                }
                ?>
              </b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>UBICACIÓN</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
              <b>
                  <?php
                  if ($prestacion->id_tipo_atencion == 3) { // Hospitalizado
                      if (isset($prestacion->servicio) && isset($prestacion->pabellon)) {
                          echo htmlspecialchars($prestacion->servicio . " " . $prestacion->pabellon, ENT_QUOTES, 'UTF-8');
                      } elseif (isset($prestacion->ubicacion)) {
                          echo htmlspecialchars($prestacion->ubicacion, ENT_QUOTES, 'UTF-8');
                      }
                  } elseif ($prestacion->id_tipo_atencion == 2) { // Emergencia
                      if (isset($prestacion->ubicacion)) {
                          echo htmlspecialchars($prestacion->ubicacion, ENT_QUOTES, 'UTF-8');
                      }
                  } else {
                      if (isset($prestacion->ubicacion)) {
                          echo htmlspecialchars($prestacion->ubicacion, ENT_QUOTES, 'UTF-8');
                      }
                  }
                  ?>

              </b>
          </td>
      </tr>
      <tr>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
              <b>NOMBRES Y APELLIDOS</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="10" align="center" valign="middle" bgcolor="#FFFFFF">
              <b><?php
                if (isset($prestacion) &&
                      isset($prestacion->asegurado_nombre) &&
                      isset($prestacion->asegurado_paterno) &&
                      isset($prestacion->asegurado_materno)) {

                      // Sanitiza cada propiedad antes de mostrarla
                      $nombre = htmlspecialchars($prestacion->asegurado_nombre, ENT_QUOTES, 'UTF-8');
                      $paterno = htmlspecialchars($prestacion->asegurado_paterno, ENT_QUOTES, 'UTF-8');
                      $materno = htmlspecialchars($prestacion->asegurado_materno, ENT_QUOTES, 'UTF-8');

                      echo "$nombre, $paterno $materno";
                  }
                ?></b>
          </td>
  
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>GRADO</b>
        </td>
      
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="4" align="center" valign="middle" bgcolor="#FFFFFF">
              <b>
                <?php
                  if(isset($prestacion->grado)){
                    echo htmlspecialchars($prestacion->grado, ENT_QUOTES, 'UTF-8');
                  }

                ?>
              </b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" align="center" valign="middle" bgcolor="#BFBFBF">
              <b>CONDICIÓN</b>
          </td>
          <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
              <b>
                <?php
                if(isset($prestacion->parentesco)){
                  echo htmlspecialchars($prestacion->parentesco, ENT_QUOTES, 'UTF-8');
                }
                ?>
              </b>
          </td>
      </tr>
      <tr>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
            <b>HISTORIA CLÍNICA</b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="6" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>
              <?php
              if(isset($prestacion->nro_historia)){
                  echo htmlspecialchars($prestacion->nro_historia, ENT_QUOTES, 'UTF-8');
                }
              ?>
            </b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
          <b>DNI</b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">
          <b>
              <?php
              if(isset($prestacion->nro_doc_ident)){
                  echo htmlspecialchars($prestacion->nro_doc_ident, ENT_QUOTES, 'UTF-8');
                }
              ?>
          </b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="2" align="center" valign="middle" bgcolor="#BFBFBF">
          <b>EDAD</b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="4" align="center" valign="middle" bgcolor="#FFFFFF">
            <b>
              <?php
              if(isset($prestacion->edadpaciente)){
                  echo htmlspecialchars($prestacion->edadpaciente, ENT_QUOTES, 'UTF-8');
                }
              ?>
            </b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" align="center" valign="middle" bgcolor="#BFBFBF">
            <b>SEXO</b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="5" align="center" valign="middle" bgcolor="#FFFFFF">
            <b> F:<?php
              if($prestacion->sexo == 'F'){
                echo "X";
              } 
              else echo "__";
              ?>          
              M:<?php
              if($prestacion->sexo == 'M') {
                echo " X";
              }
              else echo "__";

              ?>
              </b>
        </td>
      </tr>
  
      <tr>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="1" align="left" valign="middle" bgcolor="#BFBFBF">
            <b>DIAGNÓSTICO N° 1</b>
        </td>
        <td style="border: 1px solid #000; height: 10px; font-size: 9px; padding: 0;" colspan="16" align="center" valign="middle" bgcolor="#FFFFFF">
          <table style="width: 100%;  border-collapse: collapse; margin: 0; padding: 0;border: none;">
            <tr>
                <td style="border: none; padding: 0;">
                    <table style="margin-left: 10px; max-height:9px;  border:none;">
                        <tr>
                          <td 
                              @if (isset($diagnostico))
                                  @if (count($diagnostico)>0)
                                      @if ($diagnostico[0]->id_tipo_diagnostico == "2") 
                                          style="background: #c6c6c6; font-weight: 700; font-size: 9px;" 
                                      @endif
                                  @endif
                              @endif
                              >D
                          </td>
                          <td 
                              @if (isset($diagnostico))
                                  @if (count($diagnostico)>0)
                                      @if ($diagnostico[0]->id_tipo_diagnostico == "1") 
                                          style="background: #c6c6c6; font-weight: 700;" 
                                      @endif
                                  @endif
                              @endif
                              >P
                          </td>
                          <td style="border:none; padding-left: 5px;">
                            @if (isset($diagnostico))
                                  @if (count($diagnostico)>0)
                                      {{$diagnostico[0]->nombre}}
                                  @endif
                            @endif
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
            <b> @if (isset($diagnostico))
                    @if (count($diagnostico)>0)
                        {{$diagnostico[0]->codigo}}
                    @endif
                @endif
            </b>
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
                            <td
                                @if (isset($diagnostico))
                                    @if (count($diagnostico)>1)
                                        @if ($diagnostico[1]->id_tipo_diagnostico == "2") 
                                            style="background: #c6c6c6; font-weight: 700; " 
                                        @endif
                                    @endif
                                @endif
                                >D
                            </td>
                            <td 
                                @if (isset($diagnostico))
                                    @if (count($diagnostico)>1)
                                        @if ($diagnostico[1]->id_tipo_diagnostico == "1") 
                                            style="background: #c6c6c6; font-weight: 700;" 
                                        @endif
                                    @endif
                                @endif
                                >P
                            </td>
                            <td style="border:none; padding-left: 5px;">
                                @if (isset($diagnostico))
                                    @if (count($diagnostico)>1)
                                        {{$diagnostico[1]->nombre}}
                                    @endif
                                @endif
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
            <b> @if (isset($diagnostico))
                    @if (count($diagnostico)>1)
                        {{$diagnostico[1]->codigo}}
                    @endif
                @endif
            </b>
        </td>
      </tr>
    </tbody>
  </table>
  <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; padding-bottom: 0px;border-top: solid 0px;">
    <tbody>  
        {{--CONTENIDO PRINCIPAL--}}
        <tr>
            <td class="style10" colspan="26">SOLICITUD DE ANÁLISIS CLÍNICOS DE LABORATORIO</td>
        </tr>
      <!--inicio-->
      <tr class="">
        <?php  
            $paddingListaExamenes = "";
            // if ($consultorio->reporte_columnas_lab == 2) {
                $paddingListaExamenes = " padding: 0.2rem;";
            // } elseif ($consultorio->reporte_columnas_lab == 3) {
            //     $paddingListaExamenes = " padding: 0.2rem;";
            // } elseif ($consultorio->reporte_columnas_lab == 4) {
            //     $paddingListaExamenes = " padding: 0.1rem;";
            // } else {
            //     $paddingListaExamenes = " padding: 0.1rem;";
            // }
            $cantidadExamenes = 0;
        ?>
<?php
$grupos = array_chunk($lista_rua, ceil(count($lista_rua) / $num_columna));

// Rellenar con arrays vacíos si faltan columnas
while (count($grupos) < $num_columna) {
    $grupos[] = [];
}
?>
@for($col = 0; $col < $num_columna; $col++)
    <?php
        switch ($num_columna) {
            case 2: $colspan = 13; break;
            case 3: $colspan = [7, 9, 10][$col]; break;
            case 4: $colspan = [6, 7, 7, 6][$col]; break;
            default: $colspan = [6, 7, 7, 6][$col]; break;
        }
        echo '<td class="style-columnas" colspan="'.$colspan.'" style="vertical-align: top;">';
    ?>

    <table class="examenes" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <thead>
            <tr class="row11">
                <td style="background: #c6c6c6; font-weight: 700; font-size: 6.5pt; width: 17%; padding: 0.2rem;">CPMS</td>
                <td style="background: #c6c6c6; font-weight: 700; font-size: 6.5pt; width: 5%; {{ $paddingListaExamenes }}"></td>
                <td style="background: #c6c6c6; font-weight: 700; font-size: 6.5pt; width: 70%; {{ $paddingListaExamenes }}" colspan="10">NOMBRE</td>
                <td style="background: #c6c6c6; font-weight: 700; font-size: 6.5pt; width: 8%; {{ $paddingListaExamenes }}"></td>
            </tr>
        </thead>
        <tbody>
        @foreach($grupos[$col] as $e)
            @if (is_null($e->confirmar_toma) && is_null($e->pendiente_laboratorio))
            <tr class="row11">
                <td style="font-size: 6.5pt; vertical-align:top; padding: 0.2rem;">{{ $e->codigo }}</td>
                <td style="font-size: 10pt; font-weight: bold; vertical-align:top; padding: 0 0.2rem; @if ($e->id_rua != null) background: #c6c6c6; @endif">
                    @if ($e->id_rua != null)
                        <?php $cantidadExamenes++; ?> x
                    @endif
                </td>
                <td style="font-size: 6.5pt; text-align: left; {{ $paddingListaExamenes }}" colspan="10">{{ $e->descripcion_corta }}</td>
                <td style="font-size: 6.5pt; text-align: center;"><b>SR</b></td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>
    </td>
@endfor
        <!--final columna-->
                    
    </tr>
      <!--fin-->
      
      
        <!--fin-->
    </tbody>
  </table>
</div>

  <!-- Tabla al final -->
  <table class="footer-table" style="font-family: Arial, sans-serif;">
    <tbody>
        <tr>
            <td colspan="26" class="nota" style="text-align: center; border: 1px solid #000; height: 10px; font-size: 9px;"  valign="middle" bgcolor="#BFBFBF">
                <span class="bold">NOTA</span>: Toda modificación del presente formato por personal no autorizado, 
                <span class="bold">INVALIDARÁ</span> la atención del paciente en el Servicio de Pre-analíticos.
            </td>
        </tr>
        <tr>
            <td rowspan="2" style=" width:30px ; border: 1px solid #000; height: 10px; font-size: 9px;"  valign="middle" bgcolor="#BFBFBF">N. DE EXÁMENES</td>
            <td colspan="6" style="border: 1px solid #000; height: 10px; font-size: 9px; text-align: center;"  valign="middle" bgcolor="#BFBFBF">MÉDICO TRATANTE</td>
            <td colspan="8" style="border: 1px solid #000; height: 10px; font-size: 9px; text-align: center;"  valign="middle" bgcolor="#BFBFBF">ENCARGADO DE LA ATENCIÓN DEL LABORATORIO</td>
            <td colspan="11" style="border: 1px solid #000; height: 10px; font-size: 9px; text-align: center;"  valign="middle" bgcolor="#BFBFBF">PACIENTE O APODERADO</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="6" class="signature-space">
              <div align="center" style="height: 80px;">
                <?php if($firmado ==1){?>
                <table class="table-firmas style24" width="100%">
                  <tbody>
                      <tr>
                          <td width="40%"><p align="right"><img src="images/logo_sanidad.png" width="55px;" /></p></td>
                          <td width="60%"><p align="left">Firmado digitalmente por: <br/><?php echo $prestacion->medico_nombre." ".$prestacion->medico_paterno." ".$prestacion->medico_materno ?><br/>Motivo:Soy el autor del documento<br/>Fecha: <?php $fecha_firma = date("d/m/Y h:i:s ", strtotime($laboratorio->fecha_firma));
                            echo $fecha_firma; ?></p></td>
                      </tr>
                  </tbody>
                </table>
                <?php }?>
              </div>
            </td>
            <td rowspan="2" colspan="8" class="signature-space"></td>
            <td rowspan="2" colspan="11" class="signature-space"></td>
        </tr>
        <tr>
            <td rowspan="2" ><span class="bold" style="margin-left: 18px;">{{ $cantidadExamenes }}</span></td>
        </tr>
        <tr>
            <td colspan="6"  class="center-text" style="border-top:0px;font-size: 9px;">
              <span class="bold">ESPE. </span>
               {{ $prestacion->especializacion }}<span class="bold"> CMP:</span>{{$prestacion->colegiatura}} <span class="bold">RNE:</span>{{$prestacion->rne}}
            </td>
            <td colspan="8" class="center-text" style="border-top:0px;font-size: 9px;">Firma, Sello</td>
            <td colspan="11" class="center-text" style="font-size: 9px;">Nombres y Apellidos:</td>
        </tr>
    </tbody>
</table>

    <!-- Salto de página -->
    <div class="page-break"></div>

    <div class="tramite_reembolso">
        <table border="0" width="100%">
            <tr>
                <td colspan="2" class="center-text" style="font-size: 12px !important;"><b><u>TRÁMITE DE REEMBOLSO DE SALUDPOL PARA</u></b></td>
            </tr>
            <tr>
                <td colspan="2" class="center-text" style="font-size: 12px !important;"><b><u>ANÁLISIS DE LABORATORIO CLÍNICO</u></b></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="first_column">1.</td>
                <td class="second_column">Verifique su formato RUA, el cual debe tener, el sello y firma del profesional de laboratorio, así como la fecha y hora de la atención. <b>(El formato RUA que no cuente con esta información no procede para REEMBOLSO)</b></td>
            </tr>
            <tr>
                <td class="first_column">2.</td>
                <td class="second_column">Elija el laboratorio de su preferencia</td>
            </tr>
            <tr>
                <td class="first_column">3.</td>
                <td class="second_column">Al pagar solicite la factura con la siguiente Razón social de SALUDPOL<br><b style="font-size: 15px;">RUC: 20178922581</b></td>
            </tr>
            <tr>
                <td class="first_column">4.</td>
                <td class="second_column">Reúna los siguientes documentos: Factura Original, Formato RUA Original y Copia, DNI.</td>
            </tr>
            <tr>
                <td class="first_column">5.</td>
                <td class="second_column">Realice el trámite de validación en el área de REEMBOLSO DE LABORATORIO <b>(Sótano del Hospital Histórico)</b>, donde se visará sus documentos.</td>
            </tr>
            <tr>
                <td class="first_column">6.</td>
                <td class="second_column">Diríjase a las oficinas de SALUDPOL para iniciar su trámite de REEMBOLSO.</td>
            </tr>
        </table>
    </div>
</body>
</html>