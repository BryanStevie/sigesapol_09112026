<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>FORMATO DE CONTRARREFERENCIA</title>
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
    </style>
</head>

<body style="font-family: arial;padding:-30px;font-family:Arial, Helvetica, sans-serif;">

      <br>
      <center>
        <b>HOJA DE CONTRARREFERENCIA</b>
      </center><br>

    <div style="padding-left: 489px; height: 43px;">
        <table style="padding-left: 10px;">
            <tr>
                <td style="text-align: right; width: 23px; padding-bottom: 12px;">N°: </td>
                <td>
                    <table border="1">
                        <tr>
                            <td>0</td>
                            <td>0</td>
                            <td>6</td>
                            <td>9</td>
                            <td>5</td>
                            <td>4</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <label style="font-size: 10px;">1.-  DATOS GENERALES</label>
    <br><br>
    <div style="padding-bottom: -19px;">
        <table style="padding-bottom: -19px;">
            <tr>
                <td style="padding-bottom: 3px; width: 33px; padding-left: 2px;">Fecha: </td>
                <td>
                    <table border="1">
                        <tr>
                            <td>día</td>
                            <td>mes</td>
                            <td>año</td>
                        </tr>
                        <tr>
                            <td>22</td>
                            <td>03</td>
                            <td>24</td>
                        </tr>
                    </table>
                </td>                
                <td style="padding-bottom: 3px; text-align: right;">Hora: </td>
                <td>
                    <div style="padding-top: 13px;">
                        <table border="1">
                            <tr>
                                <td>1</td>
                                <td>4</td>
                                <td>5</td>
                                <td>5</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="text-align: right;">Asegurado:<br>Tipo: pueba</td>
                <td>
                    <div style="padding-top: 7px;">
                        <table border="1">
                            <tr>
                                <td>Si</td>
                                <td>No</td>
                            </tr>
                        </table>
                </td>
                <td style="padding-bottom: -1px;"></td>
                <td style="text-align: right;">N° de Referencia: </td>
                <td>
                    <div style="padding-top: 13px;">
                        <table border="1">
                            <tr>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                            </tr>
                        </table>
                    </div>
                </td>    
            </tr>
        </table>
    </div>
    <br>
    <div style="padding-bottom:-17px; padding-top: -19px;">
        <table style="margin-right: 4px;">
            <tr>
                <td style="width: 13px; text-align: left; padding-right: 5px;">Establecimiento que Contrarrefiere: </td>
                <td style="width: 173px; padding-top: 11px; padding-bottom:-13px;">
                    <table border="1">
                        <tr>
                            <td style="padding-right: 35px;">HNPNV</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;padding-top: 7px;">Establecimiento Destino de la Contrarreferencia: </td>
                <!--<td style="width: 173px; padding-top: 9px; padding-bottom: -19px;">-->
                <td style="width: 170px; padding-top: 9px; padding-bottom: -19px;">
                    <table border="1">
                        <tr>
                            <td style="padding-right: 35px;">Amonira</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <br>
    
    <div style="padding-top: -15px;">
        <table>
            <tr>
                <td style="text-align:left; width: 187px; padding-top:-37px;">2.-  IDENTIFICACION DEL USUARIO</td>
                <td>
                    <table border="1">
                         <tr>
                            <td colspan="8">Número de DNI: </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>6</td>
                            <td>0</td>
                            <td>1</td>
                            <td>2</td>
                            <td>6</td>
                            <td>3</td>
                            <td>1</td>
                        </tr>
                     </table>
                </td>
                <td>
                    <table border="1">
                        <tr>
                            <td colspan="8">Nº HISTORIA CLINICA </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>6</td>
                            <td>0</td>
                            <td>1</td>
                            <td>2</td>
                            <td>6</td>
                            <td>3</td>
                            <td>1</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div style="padding-top: -31px;">
        <table border="1">
            <tr>
                <td>Apellido Paterno</td>
                <td>Apellido Materno</td>
                <td colspan="2">Nombres</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>   
            <tr>
                <td colspan="4">
                    <div style="height: 24px;">
                        <table>
                            <tr>
                                <td style="text-align: left; padding-top:-23px; padding-left:-27px; width: 31px;">Sexo: </td>
                                <td>
                                    <div>
                                        <table border="1" style="padding-top:3px;">
                                            <tr>
                                                <td>F</td>
                                                <td>M</td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td colspan="15"></td>
                                <td style="text-align: left; padding-top:-23px; padding-left:-27px; width: 37px;">Edad</td>
                                <td style="text-align: left; padding-top:-23px; padding-left:-27px; width: 37px;">Años</td>
                                <td>
                                    <div>
                                        <table border="1" style="padding-top:3px;">
                                            <tr>
                                                <td>5</td>
                                                <td>2</td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td style="text-align: left; padding-top:-23px; padding-left:-27px; width: 40px;">Meses</td>
                                <td>
                                    <div>
                                        <table border="1" style="padding-top:3px;">
                                            <tr>
                                                <td>0</td>
                                                <td>7</td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td style="text-align: right; padding-top:-23px; padding-left:-27px; width: 40px;">Días</td>
                                <td>
                                    <div>
                                        <table border="1" style="padding-top:3px;">
                                            <tr>
                                                <td>1</td>
                                                <td>8</td>
                                             </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>  
            <tr>
                <td colspan="4">
                    <div style="height: 23px;">
                        <table>
                            <tr>
                                <td style="text-align: left;">Direccìón: Av. Jose de Sucre #378</td>
                                <td>Distrito: Comas</td>
                                <td>Departamento: Lima</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>   
        </table>
    </div>
    
    <label style="font-size: 10px;">3.- RESUMEN DE LA HISTORIA CLINICA</label>
    <div>
        <table border="1" style="height: 227px;">
            <tr>
                <td colspan="7">
                    <div style="height: 32px;">
                        <table>
                            <tr>
                                <td style="padding-top: -17px; width: 91px; padding-bottom: -4px;">Fecha de Ingreso: </td>
                                <td>
                                    <table border="1" style="padding-top: 8px;">
                                        <tr>
                                            <td>19</td>
                                            <td>03</td>
                                            <td>2014</td>
                                        </tr>
                                    </table>
                                </td>
                                <td></td>
                                <td style="padding-top: -17px; width: 91px; padding-bottom: -4px;">Fecha de Egreso: </td>
                                <td>
                                    <table border="1" style="padding-top: 8px;">
                                        <tr>
                                            <td>19</td>
                                            <td>03</td>
                                            <td>2014</td>
                                        </tr>
                                    </table>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div style="height: 47px;">
                        <table>
                            <tr>
                                <td colspan="1" style="padding-top: 7px; text-align: left;">Diagnóstico de Ingreso</td>
                                <td colspan="6" style="padding-top: 7px;">Esto es una prueba para ver el detalle qe va viajar desde la base de datos hacia el sistema SIGESAPOL</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7" style="padding-top:-1px; padding-bottom: -35px;"> <!--esto es para bajar-->
                    <div style="padding-top:-1px; padding-bottom:-2px;">
                        <table>
                            <tr>
                                <td colspan="1" style="padding-top:-45px; text-align: left;">Diagnóstico de Egreso</td>
                                <td colspan="3" style="padding-top: -35px;">Esto es una prueba para ver el detalle qe va viajar desde la base de datos hacia el sistema SIGESAPOL</td>
                                <td>
                                    <table border="1">
                                        <tr>
                                            <td colspan="5">CIE-10</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>6</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </td>
                                <td></td>
                                <td>
                                    <table border="1">
                                        <tr>
                                            <td>D</td>
                                            <td>P</td>
                                            <td>R</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div style="height: 93px; margin-top: 2px;">
                        <table>
                            <tr>
                                <td colspan="1" style="padding-top: -2px; text-align: left; width: 89px;">Tratamiento y/o procedimientos realizados</td>
                                <td colspan="6" style="padding-top: -2px;">Esto es una prueba para ver el detalle qe va viajar desde la base de datos hacia el sistema SIGESAPOL</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="1" style="padding-top: 1px; text-align: left; width: 89px;">Adjuntar informes y reportes de consentimiento</td>
                                <td colspan="6" style="padding-top: -2px;">Esto es una prueba para ver el detalle qe va viajar desde la base de datos hacia el sistema SIGESAPOL</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>        
    </div>


    <label style="font-size: 10px;">4.- DATOS DE LA CONTRARREFERENCIA</label>
    <div>
        <table border="1">
            <tr>
                <td>Origen de la Referencia</td>
            </tr>
            <tr>
                <td style="padding-bottom: -19px;">
                    <div>
                        <table>
                            <tr>
                                <td style="text-align: left; width: 93px;">De la Comunidad<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td></td>
                                <td style="text-align: left; width: 157px;">De un Establecimiento de Salud<input type="radio" name="origenreferencia" value="establecimientosalud"></td>
                                <td></td>
                                <td style="text-align: left; width: 87px;">Autorreferencia<input type="radio" name="origenreferencia" value="autorreferencia"></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table border="1" style="padding-top: -16px;">
            <tr>
                <td>Calificacion preliminar de la Referencia</td>
            </tr>
            <tr>
                <td style="padding-bottom: -19px;">
                    <div>
                        <table>
                            <tr>
                                <td></td>
                                <td style="text-align: left; width: 93px;">Justificada<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td></td>
                                <td style="text-align: left; width: 93px;">No Justificada<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table border="1" style="padding-top: -16px;">
            <tr>
                <td>UPS que orden la Contrarreferencia</td>
            </tr>
            <tr>
                <td style="padding-bottom: -19px;">
                    <div>
                        <table>
                            <tr>
                                <td></td>
                                <td style="text-align: left; width: 93px;">Consulta Ext.<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Emergencia<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Apoyo al Dx<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Hospitalización<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table border="1" style="padding-top: -16px;">
            <tr>
                <td>Especialidad que Ordena la Contrarreferencia</td>
            </tr>
            <tr>
                <td style="padding-bottom: -19px;">
                    <div>
                        <table>
                            <tr>
                                <td style="text-align: left; width: 93px;">Pediatría<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Medicina<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Cirugía<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Gineco-Obst<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Lab.<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Dx Imag<input type="radio" name="origenreferencia" value="comunidad"></td>
                                <td style="text-align: left; width: 93px;">Otros<input type="radio" name="origenreferencia" value="comunidad"></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table border="1" style="padding-top: -16px;">
            <tr>
                <td>Recomendaciones e Indicaciones para el Seguimiento</td>
            </tr>
            <tr>
                <td style="padding-bottom:19px;">Esto es una prueba para ver el detalle qe va viajar desde la base de datos hacia el sistema SIGESAPOL</td>
            </tr>
        </table>
    </div>
    <div>
        <table border="1">
            <tr>
                <td colspan="3">Responsable de la Contrarreferencia</td>
            </tr>
            <tr>
                <td>
                    <table border="1" style="width:257px;padding-left: 10px; margin-top: 5px;">
                        <tr>
                            <td>Condición del usuario para la contrarreferencia</td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;">Curado</td>
                                            <td><input type="checkbox" name="cb-autos" value="gusta"></td>
                                            <td></td>
                                            <td style="text-align: left;">Deserción</td>
                                            <td style="width: 10px;"><input type="checkbox" name="cb-autos" value="gusta"></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Mejorado</td>
                                            <td><input type="checkbox" name="cb-autos" value="gusta"></td>
                                            <td></td>
                                            <td style="text-align: left;">Retiro voluntario</td>
                                            <td><input type="checkbox" name="cb-autos" value="gusta"></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Atendido x Apoyo al Dx</td>
                                            <td><input type="checkbox" name="cb-autos" value="gusta"></td>
                                            <td></td>
                                            <td style="text-align: left;">Fallecido</td>
                                            <td><input type="checkbox" name="cb-autos" value="gusta"></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td></td>
                <td>
                    <table>
                        <tr>
                            <td style="text-align: left;">Nombre: </td>
                            <td style="text-align: left;">Ricardo Juniors Yparraguirre Flores</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">N° de colegiatura</td>
                            <td style="text-align: left;">1047961532</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><br>_________________________<br>
                                Firma y Sello</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div></div>

</body>

</html> 