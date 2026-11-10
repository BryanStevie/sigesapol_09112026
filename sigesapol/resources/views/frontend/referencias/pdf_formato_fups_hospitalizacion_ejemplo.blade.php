<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Anexo 02: FORMATO ÚNICO DE PRESTACIONES DE SALUD (FUPS) EMERGENCIA</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
        @page { margin-left: 0.75in; margin-right: 0.15in; margin-top: 0.35in; margin-bottom: 0.11in; }
        body { margin-left: 0.15in; margin-right: 0.15in; margin-top: 0.11in; margin-bottom: 0.11in; }
        .td_left {
            text-align: left !important;
        } 

        td {
            padding-left: 2px !important;
        }

        table {
            font-size: 10px !important;
            font-family: Arial, Helvetica, sans-serif;
        }

        .titulos {
            font-weight: bold;
        }

        h2 {
            font-size: 13px !important;
            font-family: Arial, Helvetica, sans-serif;
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
            padding: 2px
        }

        footer {
            text-align: left;
            border: 0px;
            margin-left: 10px;
        }
    </style>
    <style>
        .td_left {
            text-align: left !important;
        }

        .td_ancho_n {
            width: 4%; 
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

        .rectangulo {
            padding: 1.5px
        }

        footer {
            border: 0px;
            margin-left: 10px;
            font-size: 7px !important;
            font-family: Arial, Helvetica, sans-serif
        }
    </style>
    <style>
        table {
            border-collapse: collapse;
        }
        body {
            position: relative
        }
        
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            #planoCartesiano,
            .imagenCapturada {
                visibility: visible;
                width: 100%;
                /* Ajusta el ancho de la imagen capturada para que llene la página al imprimir */
                height: 100%;
                /* Ajusta la altura de la imagen capturada para mantener la proporción */
            }
        }

        .content {
            width: 90%;
            height: 90%;
            margin: 10mm;
            margin-top: 15mm;
            margin-bottom: 10mm;
            box-sizing: border-box;
        }

        td {
            height: 15px;
            font-size: 11.5px; 
        }

        th {
            font-size: 11.5px;
            text-align: left;
            /border: 1px black solid;/
            height: 15px;
            padding: 1px
        }

        td {
            border-top-width: 1px;
            border-bottom-width: 1px;
        }

        .tamanio{
            font-size: 5pt !important;
        }

        td.lineapuntos {
            border-bottom: 1px dotted black
        }

        .imagencelda {
            width: 140px;
            height: 150px;
        }

        .imagencelda2 {
            position: relative;
            padding-bottom: 3px;
            padding-top: 5px;
            width: 220px;
            height: 60px;
            left: 8mm
        }

        .imagen-esquina {
            position: absolute;
            top: -3mm;
            /* Ajusta la distancia desde el borde superior (cambia 10mm según necesites) */
            left: 10mm;
            /* Ajusta la distancia desde el borde izquierdo (cambia 10mm según necesites) */
            /* Ajusta el tamaño de la imagen (cambia width y height según necesites) */
            width: 40px;
            height: 50px;
        }

        .imagen-esquina-derecha {
            position: absolute;
            top: -3mm;
            /* Ajusta la distancia desde el borde superior (cambia 10mm según necesites) */
            right: 10mm;
            /* Ajusta la distancia desde el borde derecho (cambia 10mm según necesites) */
            /* Ajusta el tamaño de la imagen (cambia width y height según necesites) */
            width: 40px;
            height: 50px;
        }

        /*<?php for ($i = 1; $i < 7; $i++) {
            echo '<td>';
            echo $i;
            echo '</td>';
        } ?>*/
        #planoCartesiano {
            width: 162px;
            height: 180px;
            position: relative;
            background-image: url('/images/persona.jpeg');
            background-size: 162px 180px;
        }

        .coordenada {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: red;
            cursor: pointer;
        }

        .tabla {
    display: flex;
    justify-content: space-between;
    width: 100%;
  }
  .celda {
    width: 30%; /* Ajusta el ancho de las celdas según tus necesidades */
    background-color: lightgray;
    padding: 10px;
    border: 1px solid black;
  }
    </style>
</head>

<body style="font-family: arial;padding:-30px;font-family:Arial, Helvetica, sans-serif;">

    <!--<table>
        <tbody>
          <tr>
            <td colspan="2" class="" width="10%">
              <img src="images/logo_sanidad.png" width="40rem"/>
            </td>
          </tr>
        </tbody>
      </table>-->
    
      <!--<table style="text-align:center; ">
        <tbody>
            <tr> 
                <td colspan="2" width="50%" >
                    <span style="margin: 0; font-weight: bold; font-size: 13px !important; padding-bottom: -20px !important;">
                        Anexo 02: FORMATO ÚNICO DE PRESTACIONES DE SALUD (FUPS)
                    </span>
                </td> 
            </tr>
        </tbody>
      </table>-->

      <br>
      <center style="background-color:#3c763d;">
        <b class="tamanio">FORMATO ÚNICO DE PRESTACIONES DE SALUD - FUPS</b>
      </center>
      

        <div style="padding-left: 148px; ">
            <table style="margin:0px;padding-bottom:-12px; width:185px;" border="1">
                <tr> 
                    <td colspan="10" class="tamanio">
                        <center>
                            NÚMERO DE FORMATO 
                        </center>                
                    </td>                       
                </tr>
                <tr>
                    <td colspan="2" class="tamanio">00013591  
                    </td>
                    <td colspan="2" class="tamanio">24
                    </td>
                    <td colspan="6" class="tamanio">00000003</td>               
                </tr>
              </table>
        </div>

        
      <br>

    <center style="background-color:#3c763d;">
       <b class="tamanio">DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</b> 
    </center>
      
      <table  style="margin:0px;padding-bottom:-13px;" border="1">
        <tr> 
            <td colspan="3" >
              <span>
                <b class="tamanio"> CÓDIGO UNICO DE RENIPRESS </b>
            </td>
            <td colspan="6">
                <span>
                  <b class="tamanio"> NOMBRE DE LA IPRESS QUE REALIZA LA ATENCIÓN  </b>
              </td>                         
        </tr>
        <tr>
            <td colspan="3" class="tamanio">00013591</td>
            <td colspan="6" class="tamanio">HOSPITAL NACIONAL PNP LUIS N.SAENZ</td>              
        </tr>
      </table><br>

      <table  style="margin:0px;padding-bottom:-13px;" border="1">
        <tr> 
            <td colspan="3" >
                <b class="tamanio"> LUGAR DE ATENCIÓN </b>
            </td>
            <td colspan="3">
                  <b class="tamanio"> TIPOS DE INGRESO </b>
            </td>
            <td colspan="12">
                  <b class="tamanio"> REFERENCIA REALIZADA POR </b>
            </td>                         
        </tr>
        <tr>
            <td colspan="2"><b class="tamanio">INTRAMURAL</b></td>
            <td colspan="1" class="tamanio">X</td>
            <td colspan="2"><b class="tamanio">INTRAMURAL AMBULATORIA(I/E)</b></td>
            <td colspan="1" class="tamanio"> </td>             
            <td colspan="3"><b class="tamanio">CÓD. IPRESS</b></td>
            <td colspan="6"><b class="tamanio">NOMBRE DE LA IPRESS U OFERTA FLEXIBLE</b></td>
            <td colspan="3"><b class="tamanio">N° HOJA DE REFERENCIA</b></td>             
        </tr>
        <tr>
            <td colspan="2"><b class="tamanio">EXTRAMURAL</b></td>
            <td colspan="1" class="tamanio"></td>
            <td colspan="2"><b class="tamanio">REFERENCIA(E)</b></td>
            <td colspan="1" class="tamanio"></td> 
            <td colspan="3" rowspan="2" class="tamanio"></td>  
            <td colspan="6" rowspan="2" class="tamanio"></td>
            <td colspan="3" rowspan="2" class="tamanio"></td>           
        </tr>
        <tr>
            <td colspan="2" class="tamanio"> </td>
            <td colspan="1" class="tamanio"> </td>
            <td colspan="2"><b class="tamanio">EMERGENCIA(I)</b></td> 
            <td colspan="1" class="tamanio"></td>             
        </tr>
      </table>
      <br>
      
      <center style="background-color:#3c763d;">
        <b class="tamanio">DEL  ASEGURADO/USUARIO</b> 
      </center>
      <table style="margin:0px;padding-bottom:-13px;" border="1">
        <tr> 
            <td colspan="3" style="width: 145px;"><b class="tamanio"> IDENTIFICACIÓN</b></td>
            <td colspan="3" style="width: 188px;"><b class="tamanio"> HISTORIA CLINICA</b></td>
            <td colspan="6"><b class="tamanio"> SEXO</b></td>
            <td colspan="6"><b class="tamanio"> FECHA NAC.</b></td>
            <td colspan="6"><b class="tamanio"> FECHA FALLEC.</b></td>
        </tr>
        <tr>
            <td colspan="1"><b class="tamanio"> TDI</b></td>
            <td colspan="2"><b class="tamanio"> N° DOCUMENTO DE IDENTIDAD</b></td>
            <td colspan="3" rowspan="2" class="tamanio"></td>
            <td colspan="3"><b class="tamanio"> MASCULINO</b></td>
            <td colspan="3" class="tamanio">x</td>
            <td colspan="2"><b class="tamanio"> DÍA</b></td>
            <td colspan="2"><b class="tamanio"> MES</b></td>
            <td colspan="2"><b class="tamanio"> AÑO</b></td>
            <td colspan="2"><b class="tamanio"> DÍA</b></td>
            <td colspan="2"><b class="tamanio"> MES</b></td>
            <td colspan="2"><b class="tamanio"> AÑO</b></td>
        </tr>
        <tr>
            <td colspan="1" class="tamanio">DNI</td>
            <td colspan="2" class="tamanio">46012631</td>
            <td colspan="3"><b class="tamanio"> FEMENINO</b></td>
            <td colspan="3" class="tamanio"></td>
            <td colspan="2" class="tamanio">18</td>
            <td colspan="2" class="tamanio">07</td>
            <td colspan="2" class="tamanio">1989</td>
            <td colspan="2" class="tamanio"></td>
            <td colspan="2" class="tamanio"></td>
            <td colspan="2" class="tamanio"></td>
        </tr>
      </table>
      <br>

      <table style="margin:0px;padding-bottom:-13px;" border="1">
        <tr>
            <td colspan="3" style="width: 50px;"><b class="tamanio"> APELLIDO MATERNO</b></td>
            <td colspan="3" style="width: 50px;"><b class="tamanio">APELLIDO PATERNO</b></td>
        </tr>
        <tr>
            <td colspan="3" class="tamanio"> YPARRAGUIRRE</td>
            <td colspan="3" class="tamanio"> FLORES</td>
        </tr>
      </table>
      <br>

      <table style="margin:0px;padding-bottom:-13px;" border="1">
        <tr>
            <td colspan="3" style="width: 50px;"><b class="tamanio"> PRIMER NOMBRE</b></td>
            <td colspan="3" style="width: 50px;"><b class="tamanio"> OTROS NOMBRES</b></td>
        </tr>
        <tr>
            <td colspan="3" class="tamanio"> RICARDO</td>
            <td colspan="3" class="tamanio"> JUNIORS</td>
        </tr>
      </table>
      <br>
      
      <center style="background-color:#3c763d;">  <!--  rgb(89, 218, 200)-->
        <b class="tamanio">SALUD MATERNA</b> 
      </center>
      <table style="margin:0px;padding-bottom:-13px;" border="1">
        <tr>
            <td colspan="6"><b class="tamanio"> ETAPA</b></td>
            <td colspan="3" rowspan="3"><b class="tamanio"> FECHA PROBABLE DE PARTO/FECHA DE PARTO</b></td>
            <td colspan="4"><b class="tamanio"> DÍA</b></td>
            <td colspan="4"><b class="tamanio"> MES</b></td>
            <td colspan="8"><b class="tamanio"> AÑO</b></td>
            <td colspan="6"><b class="tamanio"> DNI/CNV/AFILIACIÓN DEL RN 1</b></td>
            <td colspan="2" class="tamanio"></td>
        </tr>
        <tr>
            <td colspan="3"><b class="tamanio"> GESTANTE</b></td>
            <td colspan="3" class="tamanio"></td>
            <td colspan="2" rowspan="2" class="tamanio"></td>
            <td colspan="2" rowspan="2" class="tamanio"></td>
            <td colspan="2" rowspan="2" class="tamanio"></td>
            <td colspan="2" rowspan="2" class="tamanio"></td>
            <td colspan="2" rowspan="2" class="tamanio"></td>
            <td colspan="2" rowspan="2" class="tamanio"></td>
            <td colspan="2" rowspan="2" class="tamanio"></td>
            <td colspan="2" rowspan="2" class="tamanio"></td>
            <td colspan="6"><b class="tamanio"> DNI/CNV/AFILIACIÓN DEL RN 2</b></td>
            <td colspan="2" class="tamanio"></td>
        </tr>
        <tr>
            <td colspan="3"><b class="tamanio"> PUERPERA</b></td>
            <td colspan="3" class="tamanio"> </td>
            <td colspan="6"><b class="tamanio"> DNI/CNV/AFILIACIÓN DEL RN 3</b></td>
            <td colspan="2" class="tamanio"></td>
        </tr>
      </table>
      <br>
      
      <center style="background-color:#3c763d;">
        <b class="tamanio">DE LA ATENCIÓN</b> 
      </center>
      <div style="height:297px;"> <!-- height: 280pt -->
        <table border="1" style=" height:15px;">
            {{-- <tr>
                <td colspan="4"></td>
            </tr> --}}
            <tr>
                <td style="padding-bottom: 327px;"><!-- 340 -->
                    <div style=" width: 126px; padding-top: 10px;">
                        <table border="1" style="background-color: aquamarine; width:130px; margin-bottom: 117px;"> 
                            <tr>
                                <td colspan="2">
                                    <b class="tamanio">TIPO DE PRESTACION</b></td>
                            </tr>
                            <tr>
                                <td colspan="1"><b class="tamanio"> 1= Extra Hospitalario</b></td>
                                <td colspan="1" class="tamanio">X</td>          
                            </tr>
                            <tr>
                                <td colspan="1"><b class="tamanio"> 4= Ambulatorio</b></td>
                                <td colspan="1" class="tamanio">X</td>          
                            </tr>
                            <tr>
                                <td colspan="1"><b class="tamanio"> 5= Hospitalario</b></td>
                                <td colspan="1" class="tamanio">X</td>
                            </tr>
                            <tr>
                                <td colspan="1"><b class="tamanio"> 6= Emergencia</b></td>
                                <td colspan="1" class="tamanio">X</td>
                            </tr>
                        </table>
                    </div>
                    <div style="padding-bottom:0px;">
                         <table border="1" style="width:137px;">
                            <tr>
                                <td colspan="1"><b class="tamanio"> UPSS</b></td>
                                <td colspan="1"><b class="tamanio">DESCRIPCIÓN</b></td> 
                            </tr>
                            <tr>
                                <td colspan="1" style="padding-top: 1.8em; padding-right: 0.8em;"><b class="tamanio">245300</b></td>
                                <td colspan="1" style="padding-top: 1.8em; padding-right: 0.8em;"><b class="tamanio">HOSPITALIZACION DE MEDICINA-NEUMOLOGÍA</b></td> 
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="background-color: bisque; padding-bottom: 290px; padding-top: 2px;"> <!-- padding-bottom: 200px -->
                    <div style="padding-bottom: 169px; height:-87px; padding-top:5px; padding-left: -87px; padding-right: 8px;"><!-- padding-top:5px; height:-47px; padding-bottom: 169px -->
                        <table border="1" style="padding-left:87px; padding-right: 19px; padding-bottom:5px; padding-top:12px;">  <!-- padding-left:87px; padding-right: 19px; padding-bottom:5px; -->
                            <tr>
                                <td>
                                    <b class="tamanio">(APLICA SOLO PARA TIPO DE PRESTACIÓN 1 Y 4 )</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="height: 55px;">
                                        <table>
                                            <tr>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div style="height: 53px; padding-top:-6px; padding-bottom: 15px;">
                                                        <table border="1" style="padding-left: 1px; width: 203px;">
                                                            <tr>
                                                                <td colspan="5" rowspan="2" style="width:3pt;"><b class="tamanio"> FECHA ATENCIÓN/ PRESTACIÓN </b></td>
                                                                <td colspan="3" style="width:22px;"><b class="tamanio"> DÍA</b></td>
                                                                <td colspan="3" style="width:22px;"><b class="tamanio"> MM</b></td>
                                                                <td><b class="tamanio"> AÑO</b></td>                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <td colspan="3"></td>
                                                                <td >2014</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <div style="padding-top:-6px; padding-bottom: 12px;">
                                                        <table border="1" style="padding-right:9px;">
                                                            <tr>
                                                                <td colspan="2"><b class="tamanio">HORA</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </div>                                  
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="padding-bottom:31px; padding-top:11px; height:179px; margin-top: 1px;"> <!-- padding-bottom:40px padding-top:21pt; height: 257px; -->   
                        <table border="1" style="background-color: aquamarine; height:99px; padding-top: 2px;">  <!-- height:145px -->                                                                                                                                                                    
                            <tr>
                                <td>
                                    <b class="tamanio">LLENAR EN CASO DE INTERNAMIENTO (APLICA SOLO PARA LA 5 Y 6 )</b>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <table style="height:30px;"> <!-- height:30px -->
                                        <tr>
                                            <td>
                                                <div style="padding-top: 5px; padding-bottom: -10px;">
                                                    <table border="1">
                                                        <tr>
                                                            <td colspan="5" rowspan="2" style="width:4pt;"><b class="tamanio"> FECHA DE INGRESO </b></td>
                                                            <td colspan="3" style="width:15px;"><b class="tamanio"> DÍA</b></td>
                                                            <td colspan="3" style="width:15px;"><b class="tamanio"> MM</b></td>
                                                            <td colspan="4" style="width:28px;"><b class="tamanio"> AÑO</b></td>                                                    
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"></td>
                                                            <td colspan="3"></td>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                            <td>
                                                <div >
                                                    <table border="1">
                                                        <tr>
                                                            <td colspan="2"><b class="tamanio">HORA</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr> 
                                        
                                        <tr>
                                            <td>
                                                <div>
                                                    <table border="1" style="padding-bottom:-11px;">
                                                        <tr>
                                                            <td style="width: 207px;">
                                                                <b class="tamanio">CORTE ADMINISTRATIVO (APLICA SOLO PARA TIPO 5 )</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div style="height: 55px; padding-top:5px;">
                                                                    <table>
                                                                        <!--<tr>
                                                                            <td></td>
                                                                        </tr>-->
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <div style="height: 87px; padding-bottom:6px;">
                                                                                    <table border="1">
                                                                                        <tr>
                                                                                            <td style="width:4pt;"></td>
                                                                                            <td colspan="3" style="width:15px;"><b class="tamanio"> DÍA</b></td>
                                                                                            <td colspan="3" style="width:15px;"><b class="tamanio"> MM</b></td>
                                                                                            <td colspan="4" style="width:28px;"><b class="tamanio"> AÑO</b></td>                                                    
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="width: 45px; text-align: left;">Inicio C.A.</td>
                                                                                            <td colspan="3"></td>
                                                                                            <td colspan="3"></td>
                                                                                            <td colspan="4"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="width: 45px; text-align: left;">Cierre C.A.</td>
                                                                                            <td colspan="3"></td>
                                                                                            <td colspan="3"></td>
                                                                                            <td colspan="4"></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <!--<td></td>-->
                                                                            <td>
                                                                                <div style="padding-bottom: 17px;">
                                                                                    <table border="1">
                                                                                        <tr>
                                                                                            <td colspan="2" style="text-align: center;"><b class="tamanio">HORA</b></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td></td>
                                                                                            <td></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td></td>
                                                                                            <td></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <!--<tr>
                                                                            <td></td>
                                                                        </tr>-->
                                                                    </table>
                                                                </div>                                  
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr> 
                                        <tr>   
                                            <td>
                                                <div style="padding-bottom:-17px; margin-bottom:17px;">
                                                    <table border="1">
                                                        <tr>
                                                            <td colspan="5" rowspan="2"><b class="tamanio"> FECHA DE EGRESO </b></td>
                                                            <td colspan="3" style="width:15px;"><b class="tamanio"> DÍA</b></td>
                                                            <td colspan="3" style="width:15px;"><b class="tamanio"> MM</b></td>
                                                            <td colspan="4" style="width:28px;"><b class="tamanio"> AÑO</b></td>                                                    
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"></td>
                                                            <td colspan="3"></td>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                            <!--<td></td>-->
                                            <td>
                                                <div>
                                                    <table border="1">
                                                        <tr>
                                                            <td colspan="2"><b class="tamanio">HORA</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </table>                                                                                                                                                                                                
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width:5px;"></td>
                <td style="padding-bottom: 300px;">
                    <div style="padding-top:70px;">
                        <table border="1">
                            <tr>
                                <td colspan="3" style=""><b class="tamanio"> N° DE FUPS A VINCULAR</b></td>
                            </tr>
                            <tr>
                                <td  style="width:4px;"><b class="tamanio"> RENIPRESS</b></td>
                                <td  style="width:25px;"><b class="tamanio"> LOTE</b></td>
                                <td style="width:15px;"><b class="tamanio"> CORELATIVO</b></td>           
                            </tr>
                            <tr>
                                <td ><b class="tamanio"> </b></td>
                                <td ><b class="tamanio"> </b></td>
                                <td><b class="tamanio"> </b></td>           
                            </tr>
                        </table>
                    </div>
                    <div >
                        <table border="1">
                            <tr>
                                <td style="height: 150px; font-size: 14px;"><b>INSTRUCCIONES DE LLENADO</b><BR>
                                    <span style="text-align: center;">
                                    Verificar que las fechas ingresadas sean
                                    consistentes en una línea de tiempo.<BR>
                                    Todas las prestaciones derivadas de una
                                    principal, deben vincularse con el FUPS inicial
                                    o anterior correspondiente.</span><br></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
      </div>
      <br><br><br><br><br><br>
      {{-- <table style="margin:0px;padding-bottom:0px; margin-top: 0px;" border="1" >  --}}
        {{-- <tr>
            <td colspan="25" style="background-color:#3c763d;">
                <b class="tamanio">SE REFIERE/CONTRARREFIERE A:</b> 
            </td>
        </tr> --}}
        {{-- <tr> 
            <td colspan="5"><b class="tamanio"> TIPO DESTINO </b></td> 
            <td colspan="5"><b class="tamanio"> CÓDIGO RENAES DE LA IPRESS </b></td>
            <td colspan="9"><b class="tamanio"> NOMBRE DE LA IPRESS A LA QUE SE REFIERE/CONTRARREFIERE </b></td>
            <td colspan="6"><b class="tamanio"> N° HOJA REFER/CONTRARR. </b></td>    
        </tr>
        <tr>
            <td colspan="2"><b class="tamanio"> Referencia: </b></td>
            <td colspan="3">prueba </td>
            <td colspan="5" rowspan="2"></td>
            <td colspan="9" rowspan="2"></td>
            <td colspan="6" rowspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"><b class="tamanio"> Contrarreferencia: </b></td>
            <td colspan="3">prueba </td>
        </tr>
      </table>
      <br>  --}}
      
      {{-- <center style="background-color:#3c763d;">
        <b class="tamanio">DIAGNÓSTICOS</b> 
      </center>
      <table style="margin:0px;padding-bottom:0px;" border="1">
        <tr>
            <td colspan="2"><b class="tamanio"> N° </b></td>
            <td colspan="4"><b class="tamanio"> CIE-10 </b></td>
            <td colspan="8"><b class="tamanio"> DESCRIPCIÓN DEL DIAGNÓSTICO </b></td>
            <td colspan="6"><b class="tamanio"> TIPO DE DIAGNÓSTICO </b></td>
            <td colspan="4"><b class="tamanio"> INGRESO/EGRESO </b></td>
        </tr>
        <tr>
            <td colspan="2">1</td>
            <td colspan="4"></td>
            <td colspan="8"></td>
            <td colspan="2">P</td>
            <td colspan="2">D</td>
            <td colspan="2">R</td>
            <td colspan="2">I</td>
            <td colspan="2">E</td>
        </tr>
        <tr>
            <td colspan="2">2</td>
            <td colspan="4"></td>
            <td colspan="8"></td>
            <td colspan="2">P</td>
            <td colspan="2">D</td>
            <td colspan="2">R</td>
            <td colspan="2">I</td>
            <td colspan="2">E</td>
        </tr>
        <tr>
            <td colspan="2">3</td>
            <td colspan="4"></td>
            <td colspan="8"></td>
            <td colspan="2">P</td>
            <td colspan="2">D</td>
            <td colspan="2">R</td>
            <td colspan="2">I</td>
            <td colspan="2">E</td>
        </tr>
        <tr>
            <td colspan="2">4</td>
            <td colspan="4"></td>
            <td colspan="8"></td>
            <td colspan="2">P</td>
            <td colspan="2">D</td>
            <td colspan="2">R</td>
            <td colspan="2">I</td>
            <td colspan="2">E</td>
        </tr>
        <tr>
            <td colspan="2">5</td>
            <td colspan="4"></td>
            <td colspan="8"></td>
            <td colspan="2">P</td>
            <td colspan="2">D</td>
            <td colspan="2">R</td>
            <td colspan="2">I</td>
            <td colspan="2">E</td>
        </tr>
      </table>
      <br>

      <center style="background-color:#3c763d;">
        <b class="tamanio">RESPONSABLE DE LA ATENCIÓN</b> 
      </center>
      <table style="margin:0px;padding-bottom:0px;" border="1" >
        <tr> 
            <td colspan="2"><b class="tamanio">TIPO DOC</b></td> 
            <td colspan="8"><b class="tamanio">N° DE DOCUMENTO</b></td>
            <td colspan="18"><b class="tamanio">NOMBRE DEL RESPONSABLE DE LA ATENCIÓN</b></td>
            <td colspan="12"><b class="tamanio">TIPO DE PROFESIONAL</b></td>
            <td colspan="12"><b class="tamanio">N° DE COLEGIATURA</b></td>    
        </tr>
        <tr>
            <td colspan="2" class="tamanio">DNI</td> 
            <td colspan="8" class="tamanio">46012631</td>
            <td colspan="18" class="tamanio">RICARDO JUNIORS YPARRAGUIRRE FLORES</td>
            <td colspan="12" class="tamanio">Bach. Ingeniería de Sistemas e Informática</td>
            <td colspan="12" rowspan="2" class="tamanio">N° DE COLEGIATURA</td>   
        </tr>
        <tr>
            <td colspan="8"><b class="tamanio">ESPECIALIDAD: </b></td>
            <td colspan="20" class="tamanio"> INGENIERÍA DE SISTEMAS E INFORMÁTICA </td>
            <td colspan="4"><b class="tamanio">N° RNE: </b></td>
            <td colspan="8"></td>
        </tr>
      </table>
      <br><br>
      <br><br>
      
      <center>
        ______________________________________________<br>
        <b class="tamanio">FIRMA Y SELLO DEL RESPONSABLE DE LA ATENCIÓN</b>
      </center> --}}
      


      {{-- <table style="text-align:center; ">
        <tr> 
            <td colspan="2" width="50%" >
                <span style="margin: 0; font-weight: bold; font-size: 15px !important; padding-bottom: -20px !important;">
                    <b class="tamanio">CONSUMO DURANTE LA PRESTACIÓN DE SALUD</b>
                </span><br>
            </td> 
        </tr>
      </table> --}}
    
      {{-- <br>
      <center style="background-color:#3c763d;">
        <b class="tamanio">PRODUCTOS FARMACEÚTICOS/MEDICAMENTOS</b> 
      </center>
      
      <table  style="margin:0px;padding-bottom:-13px;" border="1">
        <tr> 
            <td colspan="2"><b class="tamanio">N°</b></td>
            <td colspan="6"><b class="tamanio">CÓDIGO</b></td> 
            <td colspan="10"><b class="tamanio">NOMBRE</b></td>  
            <td colspan="6"><b class="tamanio">FORMA FARMACÉUTICA</b></td>
            <td colspan="4"><b class="tamanio">CONCENTRACIÓN</b></td>
            <td colspan="4"><b class="tamanio">PRESCRITO</b></td>
            <td colspan="8"><b class="tamanio">ENTREGADO</b></td>
            <td colspan="4"><b class="tamanio">DX</b></td>                      
        </tr>
        <tr>
            <td colspan="2" class="tamanio">1</td>
            <td colspan="6" class="tamanio">0810781</td> 
            <td colspan="10" class="tamanio">AMOXICILINA</td>  
            <td colspan="6" class="tamanio">C2HO4</td>
            <td colspan="4" class="tamanio">PRUEBA</td>
            <td colspan="4" class="tamanio">SI</td>
            <td colspan="8" class="tamanio">SI</td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">2</td>
            <td colspan="6" class="tamanio">1234567</td> 
            <td colspan="10" class="tamanio">SABUTAMOL</td>  
            <td colspan="6" class="tamanio">zn(oh)</td>
            <td colspan="4" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">SI</td>
            <td colspan="8" class="tamanio">SI</td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">3</td>
            <td colspan="6" class="tamanio">prueba</td> 
            <td colspan="10" class="tamanio">prieba</td>  
            <td colspan="6" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">prueba</td>
            <td colspan="8" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">4</td>
            <td colspan="6" class="tamanio">0630057</td> 
            <td colspan="10" class="tamanio">COLAGENO</td>  
            <td colspan="6" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">1 lt</td>
            <td colspan="4" class="tamanio">prueba</td>
            <td colspan="8" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">5</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">6</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">7</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">8</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">9</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">10</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
      </table><br><br>
      
      <center style="background-color:#3c763d;">
        <b class="tamanio">DISPOSITIVOS MÉDICOS / PRODUCTOS SANITARIOS</b> 
      </center>
      <table  style="margin:0px;padding-bottom:-13px;" border="1">
        <tr> 
            <td colspan="2"><b class="tamanio">N°</b></td>
            <td colspan="6"><b class="tamanio">CÓDIGO</b></td> 
            <td colspan="10"><b class="tamanio">NOMBRE</b></td>  
            <td colspan="6"><b class="tamanio">FORMA FARMACÉUTICA</b></td>
            <td colspan="4"><b class="tamanio">CONCENTRACIÓN</b></td>
            <td colspan="4"><b class="tamanio">PRESCRITO</b></td>
            <td colspan="8"><b class="tamanio">ENTREGADO</b></td>
            <td colspan="4"><b class="tamanio">DX</b></td>                      
        </tr>
        <tr>
            <td colspan="2" class="tamanio">1</td>
            <td colspan="6" class="tamanio">0810781</td> 
            <td colspan="10" class="tamanio">AMOXICILINA</td>  
            <td colspan="6" class="tamanio">C2HO4</td>
            <td colspan="4" class="tamanio">PRUEBA</td>
            <td colspan="4" class="tamanio">SI</td>
            <td colspan="8" class="tamanio">SI</td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">2</td>
            <td colspan="6" class="tamanio">1234567</td> 
            <td colspan="10" class="tamanio">SABUTAMOL</td>  
            <td colspan="6" class="tamanio">zn(oh)</td>
            <td colspan="4" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">SI</td>
            <td colspan="8" class="tamanio">SI</td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">3</td>
            <td colspan="6" class="tamanio">prueba</td> 
            <td colspan="10" class="tamanio">prieba</td>  
            <td colspan="6" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">prueba</td>
            <td colspan="8" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">4</td>
            <td colspan="6" class="tamanio">0630057</td> 
            <td colspan="10" class="tamanio">COLAGENO</td>  
            <td colspan="6" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">1 lt</td>
            <td colspan="4" class="tamanio">prueba</td>
            <td colspan="8" class="tamanio">prueba</td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">5</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">6</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">7</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">8</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">9</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
        <tr>
            <td colspan="2" class="tamanio">10</td>
            <td colspan="6" class="tamanio"></td> 
            <td colspan="10" class="tamanio"></td>  
            <td colspan="6" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="4" class="tamanio"></td>
            <td colspan="8" class="tamanio"></td>
            <td colspan="4" class="tamanio">DX</td>                
        </tr>
      </table><br><br>

      <center style="background-color:#3c763d;">
        <b class="tamanio">PROCEDIMIENTOS/DIAGNÓSTICO POR IMÁGENES/LABORATORIO</b> 
      </center>
      <table style="margin:0px;padding-bottom:-13px;" border="1" >
        <tr> 
            <td colspan="1"><b class="tamanio">N°</b></td> 
            <td colspan="3"><b class="tamanio">CÓDIGO</b></td>
            <td colspan="18"><b class="tamanio">NOMBRE DEL PROCEDIMIENTO</b></td>
            <td colspan="2"><b class="tamanio">INDICADO</b></td>  
            <td colspan="2"><b class="tamanio">EJECUTADO</b></td>
            <td colspan="2"><b class="tamanio">DX</b></td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">1</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">2</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">3</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">4</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">5</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">6</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">7</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">8</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">9</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
        <tr> 
            <td colspan="1" class="tamanio">10</td> 
            <td colspan="3" class="tamanio">CÓDIGO</td>
            <td colspan="18" class="tamanio">NOMBRE DEL PROCEDIMIENTO</td>
            <td colspan="2" class="tamanio">INDICADO</td>  
            <td colspan="2" class="tamanio">EJECUTADO</td>
            <td colspan="2" class="tamanio">DX</td>  
        </tr>
      </table>
      <br><br>
      
      <center style="background-color:#3c763d;">
        <b class="tamanio">OBSERVACIONES</b> 
      </center>
      <table style="margin:0px;padding-bottom:-13px;" border="1" >
        <tr> 
            <td colspan="10" class="tamanio"></td>    
        </tr>
        <tr>
            <td colspan="10" class="tamanio"></td>   
        </tr>
        <tr>
            <td colspan="10" class="tamanio"></td>
        </tr>
      </table>
      <br><br>
      <br><br>
      <br><br>
      <br><br>

      <center class="tamanio">
        _______________________________________________<br>
        Firma y sello del Responsable de<br>
        Procedimiento y/o Farmacia y/o Laboratorio
      </center> --}}
      

</body>

</html> 