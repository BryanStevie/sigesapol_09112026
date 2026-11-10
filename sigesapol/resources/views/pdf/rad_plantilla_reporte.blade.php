<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IMAGENES</title>

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
        
        table.table-cpms td{
            height: 10px;
        }

        td{
            font-size: 6pt;
            font-family: Helvetica;
            text-align: center;
            height: 15px;
            /*padding: 3px*/
        }

        .n { text-align:right }

        .s { text-align:left }

        .pa{
            padding: 5px
        }

        .img-escudo {
            height: 30px;
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
            border-bottom:none;
        }
        td.style7_1 {
            font-size: 6pt;
            font-weight: 700;
            padding-top: 2px;
            padding-bottom: 3px;
            border-top:none;
        }
        td.style8 {
            font-size: 6pt;
            font-weight: 700;
            padding-top: 2px;
            padding-bottom: 2px;
            background: #8ed048;
        }
        td.style9{
            font-size: 5pt;
            font-weight: 700;
            background: #8ed048;
        }
        td.style10{
            background: #c6c6c6;
            font-size: 7pt;
            font-weight: 700;
        }
        td.style11{
            text-align: center;
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
            border-left: none !important;
            border-bottom: none !important;
        }
        td.style17{
            font-weight: 700;
            border-top: none !important;
        }
        td.style18{
            font-weight: 700;
            border-top: none !important;
        }
        td.style19{
            font-weight: 700;
            text-align: center;
            border-top: none !important;
        }
        td.style20{
            font-weight: 700;
            text-align: start;
            border-right: none !important;
            border-bottom: none !important;
        }
        td.style21{
            border-bottom: none !important;
        }

        .style-columnas{
            vertical-align: top !important;
            padding: 5px 5px 5px 5px !important;
            height: 595px !important;
            border-right: none !important;
            border-left: none !important;
            border-bottom: none !important;
        }

        td.style26 { vertical-align:bottom; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }

        td.style74 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }

        td.style76 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }

        td.style43 { vertical-align:middle; text-align:center; padding-right:0px; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }

        td.style22 { vertical-align:middle; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }

        td.style23 { vertical-align:middle; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }

        .page-break {
            page-break-after: always;
        }

        .fixed-height-table {
            height: 750px !important; /* Altura fija para la tabla */
            overflow: hidden !important; /* Ocultar contenido que se desborde */
            margin: 0;
        }

        .fixed-height-table-two {
            height: 750px !important; /* Altura fija para la tabla */
            overflow: hidden !important; /* Ocultar contenido que se desborde */
            margin: 0;
        }

        .item-resumen-hc {
            padding: 0; 
            margin: 0; 
            overflow: hidden !important;
            text-align: left !important;
        }

        .sub-titulo-item-resumen-hc {
            padding: 0; 
            margin: 0; 
            font-weight: 700;
        }

        .descripcion-footer {
            text-align: left !important;
            border-top: none !important;
        }
    </style>
</head>
<body>
    
    <table class="examenes fixed-height-table" style="width: 100%;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td class="style1" rowspan="3">
                    <img class="img-escudo" src="images/escudo_republica_peru.png" alt="escudo_republica_peru">
                </td>
                <td class="style2" rowspan="3">Perú</td>
                <td class="style3" rowspan="3" colspan="2">Ministerio del Interior</td>
                <td class="style3-1" rowspan="3" colspan="4">Fondo de Aseguramiento en Salud de la Policia Nacional del Perú - SALUDPOL</td>
                <td class="style5" rowspan="3" colspan="14">REGISTRO ÚNICO DE ATENCIÓN - {{$titulo_reporte}}</td>
                <td class="style6" colspan="4">Edición: 01</td>
            </tr>
            <tr>
                <td class="style7" colspan="4">Página 1/1</td>
            </tr>
            <tr>
                <td class="style7_1" colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td class="style8" rowspan="2" colspan="3">{{$establecimiento[0]->nombre}}</td>
                <td class="style9" colspan="4">UNIDAD EJECUTORA</td>
                <td class="style9" colspan="4">CODIGO RENAES</td>
                <td class="style9" colspan="4">FECHA DE ATENCIÓN</td>
                <td class="style9" colspan="4">N° DE RUA</td>
                <td class="style9" colspan="7">REGIÓN</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
                <td colspan="7">&nbsp;</td>
                
            </tr>
            <tr>
                <td class="style9">DNI</td>
                <td colspan="2">&nbsp;</td>
                <td class="style9" colspan="11">NOMBRE Y APELLIDOS DEL PACIENTE</td>
                <td class="style9" colspan="2">EDAD</td>
                <td class="style9" colspan="2">SEXO</td>
                <td class="style9" colspan="4">CONDICIÓN</td>
                <td class="style9" colspan="4">GRADO</td>
            </tr>
            <tr>
                <td class="style9">HC</td>
                <td colspan="2">&nbsp;</td>
                <td colspan="11">&nbsp;</td>
                <td colspan="2">&nbsp;</td>
                <td>F</td>
                <td>M</td>

                <td>T</td>
                <td>C</td>
                <td>H</td>
                <td>P</td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td class="style9">SERVICIO</td>
                <td colspan="6">{{$consultorio->nombre}}</td>
                <td colspan="19">&nbsp;</td>
            </tr>
            <tr>
                <td class="style9">
                @if ($tipo_atencion == 1)
                    PROXIMA CITA
                @elseif ($tipo_atencion == 2)
                    CAMA
                @else
                    CAMA
                @endif
                </td>
                <td colspan="6">&nbsp;</td>
                <td colspan="19">&nbsp;</td>
            </tr>
            <tr>
                <td class="style9" colspan="2">DIAGNOSTICO 1</td>
                <td>D</td>
                <td>P</td>
                <td colspan="20"></td>
                <td class="style9">CIE-10</td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td class="style9" colspan="2">DIAGNOSTICO 2</td>
                <td>D</td>
                <td>P</td>
                <td colspan="20"></td>
                <td class="style9">CIE-10</td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td class="style10" colspan="26">RESUMEN DE HISTORIA CLINICA</td>
            </tr>
            <tr>
                {{--T.E : Maximo de caracteres 2345--}}
                <td colspan="26"><p class="item-resumen-hc" style="height: 100px !important;"><span class="sub-titulo-item-resumen-hc">T.E :</span></p></td>
            </tr>
            <tr>
                {{--ANAMNESIS : Maximo de caracteres 5550--}}
                <td colspan="26"><p class="item-resumen-hc" style="height: 200px !important;"><span class="sub-titulo-item-resumen-hc">ANAMNESIS :</span></p></td>
            </tr>
            <tr>
                {{--EXAMEN FISICO : Maximo de caracteres 895--}}
                <td colspan="26"><p class="item-resumen-hc" style="height: 40px !important;"><span class="sub-titulo-item-resumen-hc">EXAMEN FISICO :</span></p></td>
            </tr>
            <tr>
                {{--EXAMENES AUXILIARES : Maximo de caracteres 4354--}}
                <td colspan="26"><p class="item-resumen-hc" style="height: 150px !important;"><span class="sub-titulo-item-resumen-hc">EXAMENES AUXILIARES :</span></p></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; background: #8ed048;"><span class="sub-titulo-item-resumen-hc" style="width: 100%;">COMPARATIVAS :</span></td>
                <td colspan="2" style="text-align: center;">SI</td>
                <td colspan="2" style="text-align: center;">NO</td>
                <td colspan="2" style="text-align: center; background: #8ed048;"><span class="sub-titulo-item-resumen-hc" style="width: 100%">LADO :</span></td>
                <td colspan="2" style="text-align: center;">DER</td>
                <td colspan="2" style="text-align: center;">IZQ</td>
                <td colspan="14" style="text-align: left;">&nbsp;</td>
            </tr>
        </tbody>
    </table>
    {{--CONTENIDO PRINCIPAL--}}
    <div class="page-break"></div>
    <table class="examenes fixed-height-table-two" cellpadding="0" cellspacing="0" width="100%" >
        <tbody>
            <tr>
                <td class="style10" colspan="26">SOLICITUD DE DIAGNOSTICO POR IMÁGENES</td>
            </tr>
            <!--inicio-->
            <tr class="row11">
            <?php  
                $paddingListaExamenes = "";
                if ($consultorio->reporte_columnas_rad == 2) {
                    $paddingListaExamenes = " padding: 0.5rem 0.2rem;";
                } elseif ($consultorio->reporte_columnas_rad == 3) {
                    $paddingListaExamenes = " padding: 0.2rem;";
                } elseif ($consultorio->reporte_columnas_rad == 4) {
                    $paddingListaExamenes = " padding: 0.1rem;";
                } else {
                    $paddingListaExamenes = " padding: 0.1rem;";
                }
            ?>
            <!-- inicio columna columna!-->
            <?php $fila=1; $ultimo=0; $inicio=1; $titulo=''; $flag=0;?>
            @for($col=1;$col<=$consultorio->reporte_columnas_rad;$col++)
                <td class="style-columnas" colspan="{{ $consultorio->reporte_columnas_rad == 2 ? 13 : ($consultorio->reporte_columnas_rad == 3 ? ($col == 1 ? 7 : ($col == 2 ? 9 : 10)) : ($col == 1 || $col == 4 ? 6 : 7)) }}">
                <?php $i=1; ?>
                @foreach ($lista_rad as $rad)
                <?php
                    
                    if($fila>$filas_por_columnas){
                    $fila=1; $ultimo=$inicio-1; $inicio=1;
                    break;
                    }
                ?>  
                    
                        <table class="table-cpms" border="0" cellpadding="0" cellspacing="0" width="100%" >
                            <tbody>
                                <?php 
                                if($inicio>$ultimo){
                                    if($rad->nombre!=$titulo){
                                    $titulo=$rad->nombre;
                                    $flag=1; 
                                    $fila++;
                                    
                                ?>
                                    <tr class="row11">
                                        <td style="background: #c6c6c6; font-weight: 700; font-size: 6.5pt;<?php echo $paddingListaExamenes;?>" width="10%" >CPT</td>
                                        <td style="background: #c6c6c6; font-weight: 700;  font-size: 6.5pt;" width="5%" ></td>
                                        <td style="background: #c6c6c6; font-weight: 700;  font-size: 6.5pt;<?php echo $paddingListaExamenes;?>" width="85%" colspan="11">{{$rad->nombre}}</td>
                                    </tr>
                                    <tr class="row11">
                                        <td style="font-size: 6.5pt;<?php echo $paddingListaExamenes;?>" width="10%" >{{$rad->codigo}}</td>
                                        <td width="5%" ></td>
                                        <td style="font-size: 6pt; text-align: left;<?php echo $paddingListaExamenes;?>" width="85%" colspan="11">&nbsp;&nbsp;{{$rad->descripcion_corta}}</td>
                                    </tr>
                                <?php  
                                    
                                    }
                                    else{
                                ?>
                                    <tr class="row11">
                                        <td style="font-size: 6.5pt;<?php echo $paddingListaExamenes;?>" width="10%" >{{$rad->codigo}}</td>
                                        <td width="5%"></td>
                                        <td style="font-size: 6pt; text-align: left;<?php echo $paddingListaExamenes;?>" width="85%" colspan="11">&nbsp;&nbsp;{{$rad->descripcion_corta}}</td>
                                    </tr>
                                <?php
                                    
                                    }
                                    $fila++;
                                ?>
                                <?php
                                }
                                $inicio++;
                                $i++;
                            ?>
                            </tbody>
                        </table>
                    
                @endforeach
                </td>
            @endfor
            <!--final columna-->      
            </tr>
            <!--fin-->
            <tr>
                <td class="descripcion-footer" colspan="26">TM:tomografia;RM:resonancia magnetica;CC: con contraste;SC: sin contraste;ECO:ecografía;Rx: rayos x;SUP:superior; INF:inferior;DER: derecho; IZQ:izquierda;INC:incidencias;</td>
            </tr>
            <tr>
                <td class="style11" colspan="26"><span>NOTA</span>: Toda modificación del presente formato por personal no autorizado, INVALIDARÁ la atención del paciente en el Servicio de Diagnóstico por Imagen.</td>
            </tr>
            <tr>
                <td class="style12" rowspan="2">N. DE EXÁMENES</td>
                <td class="style13" colspan="6">MÉDICO TRATANTE</td>
                <td class="style14" colspan="6">V°B° JEFE DEPARTAMENTO QUE REFIERE</td>
                <td class="style15" colspan="8">ENCARGADO DE LA TOMA DEL EXAMEN</td>
                <td class="style15" colspan="5">FECHA Y HORA DE TOMA DEL EXAMEN</td>
                
            </tr>
            <tr>
                <td class="style21" rowspan="2" colspan="6">
                    <div style="height: 60px;"></div>
                </td>
                <td class="style21" rowspan="2" colspan="6">&nbsp;</td>
                <td class="style16" rowspan="2" colspan="8">&nbsp;</td>
                <td class="style16" rowspan="2" colspan="5">&nbsp;</td>
            </tr>

            <tr>
                <td rowspan="2">&nbsp;</td>
            </tr>

            <tr>
                <td class="style17" colspan="6">Firma, Sello, Colegiatura</td>
                <td class="style18" colspan="6">Firma, Sello, Colegiatura</td>
                <td class="style19" colspan="8">Firma, Sello, CTMP</td>
                <td class="style19" colspan="5">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</body>
</html>