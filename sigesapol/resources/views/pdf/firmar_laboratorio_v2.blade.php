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
          height: 405px;
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
            bottom: 25;
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
 


<div class="content"><br/><br/><br/><br/><br/><br/><br/><br/>
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
                      if (!empty($es_proc_prog)) {
                        echo " - PROC. PROG.";
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
              <b>
              <?php 
             if ($consultorio->nombre == 'EMERGENCIA') {
                  if (isset($prestacion->fecha_evaluacion)) {
                      echo $prestacion->fecha_evaluacion;
                  } 
              } else {
                  if (isset($prestacion->fecha_atencion)) {
                      echo $prestacion->fecha_atencion;
                  }
              }
              ?>
                
               
                

              </b>
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
                
                if($prestacion->id_tipo_atencion == 3){//Hospitalizado
                  if (isset($prestacion->servicio) && isset($prestacion->pabellon)) {
                    echo htmlspecialchars(($prestacion->servicio. " ".$prestacion->pabellon), ENT_QUOTES, 'UTF-8');
                  }
                  elseif($prestacion->ubicacion){
                    echo htmlspecialchars(($prestacion->ubicacion), ENT_QUOTES, 'UTF-8');
                  }
                }
                elseif($prestacion->id_tipo_atencion == 2){//EMERGENCIA
                  if (isset($prestacion->ubicacion) ) {
                    echo htmlspecialchars(($prestacion->ubicacion), ENT_QUOTES, 'UTF-8');
                  }
                }
                else  {
                  if(isset($prestacion) && isset($prestacion->ubicacion)){
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
        <td style="border: 1px solid #000; height: 10px; font-size: 9px;" colspan="16" align="center" valign="middle" bgcolor="#FFFFFF">
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
                        </tr>
                    </table>
                </td>
                <td style="border:none;">
                  @if (isset($diagnostico))
                        @if (count($diagnostico)>0)
                            {{$diagnostico[0]->nombre}}
                        @endif
                  @endif
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
                            <td style="border:none;">
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
        <?php
        // Definir los datos de pruebas médicas - usando la variable dinámica
        // dd($lista_rua);
        $medicalTests = $lista_rua;
        
        // Verificamos si hay elementos
        if (empty($medicalTests) || !is_array($medicalTests)) {
            $medicalTests = []; // Array vacío si no hay datos o no es un array
        }
        
        // Número total de elementos
        $totalItems = count($medicalTests);
        
        // Definimos número máximo de columnas (4)
        $maxColumns = 4;
        
        // Si hay muy pocos elementos, ajustamos el número de columnas
        $actualColumns = ($totalItems > 0) ? min($maxColumns, $totalItems) : 1;
        
        // Calculamos elementos por columna
        $itemsPerColumn = ($totalItems > 0) ? ceil($totalItems / $actualColumns) : 0;
        $actualColumns = 4;
        // Crear las columnas con distribución equitativa
        $columns = [];
        for ($i = 0; $i < $actualColumns; $i++) {
            $start = $i * $itemsPerColumn;
            $length = min($itemsPerColumn, $totalItems - $start);
            if ($length > 0) {
                $columns[$i] = array_slice($medicalTests, $start, $length);
            } else {
                $columns[$i] = []; // Columna vacía si no hay suficientes elementos
            }
        }
      
        ?>
        
        <tr id="container_dibuje">
          <!-- Tabla principal con columnas dinámicas -->
          <table border="0" cellspacing="0" cellpadding="5" style="width: 100%; border: 0px solid;">
              <tr>
                  @foreach ($columns as $columnIndex => $columnTests)
                      <td style="width: {{ 100 / $actualColumns }}%; vertical-align: top; padding: 5px;border: 0px solid;">
                        @if (count($columnTests) > 0)
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px; border: 0px solid;">
                      
                          <tbody>
                              @foreach ($columnTests as $test)
                                  <tr>
                                      <td style="border: 1px solid #000; padding: 3px;width: 18px;">{{ $test->codigo}}</td>
                                      <td style="border: 1px solid #000; padding: 3px;width: 8px;">X</td>
                                      <td style="border: 1px solid #000; padding: 3px;">{{ $test->descripcion_corta }}</td>
                                      <td style="border: 1px solid #000; padding: 3px; width: 8px;">
                                 
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                        </table>
                        @endif
                      </td>
                  @endforeach
              </tr>
          </table>
        </tr>
      
      
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
                <td colspan="2" style="text-align: center; background: #8ed048;"><span class="sub-titulo-item-resumen-hc" style="width: 100%;">N. DE EXÁMENES :</span></td>
                <td colspan="2" style="text-align: center;">{{$total_examenes_prestacion_procedimientos}}</td>
                <td colspan="22" style="text-align: center;"></td>
            </tr>
    </tbody>
</table>

</body>
</html>