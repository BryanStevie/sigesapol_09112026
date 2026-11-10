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
            bottom: 168px;
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
              ?>   </font></b>
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



   <!-- Fila 1: N° DE RUA, FECHA DE ATENCIÓN -->
    <tr>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>N° DE RUA</b></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center"><?php echo isset($prestacion->nro_rua) ? $prestacion->nro_rua : ''; ?></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>FECHA DE ATENCIÓN</b></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center">{{ date("d/m/Y", strtotime($laboratorio->fecha_hora_atencion)) }}</td>
    </tr>

    <!-- Fila 2: N° DOC, HISTORIA CLÍNICA -->
    <tr>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>N° DE DOCUMENTO</b></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center"><?php echo isset($prestacion->nro_doc_ident) ? $prestacion->nro_doc_ident : ''; ?></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>HISTORIA CLÍNICA</b></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center"><?php echo isset($prestacion->nro_historia) ? $prestacion->nro_historia : ''; ?></td>
    </tr>

    <!-- Fila 3: APELLIDOS Y NOMBRES, EDAD -->
    <tr>
        <td colspan="3" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>APELLIDOS Y NOMBRES</b></td>
        <td colspan="3" style="border:1px solid #000; font-size:9px;" align="center">
           
                <?php
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
                ?>
            
        </td>
        <td colspan="1" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>EDAD</b></td>
        <td colspan="1" style="border:1px solid #000; font-size:9px;" align="center">
            <?php
                   if(isset($prestacion->edadpaciente)){
                  echo htmlspecialchars($prestacion->edadpaciente, ENT_QUOTES, 'UTF-8');
                }
            ?>
        </td>
    </tr>

    <!-- Fila 4: SEXO, CONDICIÓN -->
    <tr>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>SEXO</b></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center">
            F:<?php
              if($prestacion->sexo == 'F'){
                echo "X";
              } 
              else echo "__";
              ?>          
              M:<?php
              if($prestacion->sexo == 'M') {
                echo "X";
              }
              else echo "__";

              ?>
        </td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>CONDICIÓN</b></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center">
            <?php echo isset($prestacion->referido) ? ($prestacion->referido == 1 ? 'REFERIDO' : 'NO REFERIDO') : ''; ?>
        </td>
    </tr>

    <!-- Fila 5: SERVICIO, UBICACIÓN -->
    <tr>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>SERVICIO</b></td>
        <td colspan="3" style="border:1px solid #000; font-size:9px;" align="center">
            {{$consultorio->nombre}}
        </td>
        <td colspan="1" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>UBICACIÓN</b></td>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center">
            <?php
               echo htmlspecialchars($prestacion->consultorio, ENT_QUOTES, 'UTF-8');
            ?>
        </td>
    </tr>

    <!-- Fila 6: DIAGNÓSTICO 1 -->
    <tr>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>DIAGNÓSTICO N° 1</b></td>
        <td colspan="4" style="border:1px solid #000; font-size:9px;" align="center">
         
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
        <td colspan="1" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>CIE 10</b></td>
        <td colspan="1" style="border:1px solid #000; font-size:9px;" align="center">
             <b> @if (isset($diagnostico))
                    @if (count($diagnostico)>0)
                        {{$diagnostico[0]->codigo}}
                    @endif
                @endif
            </b>
        </td>
    </tr>
      <!-- Fila 6: DIAGNÓSTICO 2 -->
    <tr>
        <td colspan="2" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>DIAGNÓSTICO N° 2</b></td>
        <td colspan="4" style="border:1px solid #000; font-size:9px;" align="center">
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
        <td colspan="1" style="border:1px solid #000; font-size:9px;" align="center" bgcolor="#BFBFBF"><b>CIE 10</b></td>
        <td colspan="1" style="border:1px solid #000; font-size:9px;" align="center">
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



<table style="width: 100%; padding-top: 10px; font-size: 11px;">
  <thead>
    <tr>
      <th>N°</th>
      <th>CPMS</th>
      <th>NOMBRE EXAMEN</th>
      <th>ESTADO</th>

    </tr>
  </thead>
  <tbody>
    <?php $cantidadExamenes =0; ?>
    @foreach ($lista_rua as $e)
    
      <?php $cantidadExamenes++; ?>
        <tr class="row11">
             
                <td style="font-size: 6.5pt;text-align: center;" width="3%" >{{$cantidadExamenes}}</td>
                <td width="5%" style="font-weight: 700; background: #c6c6c6; text-align: center;  ">{{$e->cpms_personalizado}} </td>
                <td style="font-size: 6pt; text-align: left;" width="40%" >&nbsp;&nbsp;{{$e->descripcion_corta}}</td>
                <td style="font-size: 6.5pt;text-align: center;" width="5%" class="bg-registro">
                  <span class="reactivo">SR</span>                    
                </td>
                  
          </tr>
    @endforeach
   
   
  </tbody>
</table>




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
            <td rowspan="2" colspan="6" class="signature-space"></td>
            <td rowspan="2" colspan="8" class="signature-space"></td>
            <td rowspan="2" colspan="11" class="signature-space"></td>
        </tr>
        <tr>
            <td rowspan="2" style="margin-left: 18px;"><span class="bold" style="margin-left: 18px;">{{$cantidadExamenes}}</span></td>
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

</body>