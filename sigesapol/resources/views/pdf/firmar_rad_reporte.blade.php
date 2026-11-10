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

        
        table.table-firmas tr {
            border: none;
        }

        table.table-firmas td {
            border: none;
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

        td.style24{
            border-left: none !important;
            border-right: none !important;
            border-top: none !important;
            border-bottom: none !important;
        }

        .page-break {
            page-break-after: always;
        }

        .fixed-height-table {
            height: 590px !important; /* Altura fija para la tabla */
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
    <br/><br/><br/><br/><br/><br/><br/><br/>
    
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
                <td colspan="4" style="font-weight: 700;">020</td>
                <td colspan="4" style="font-weight: 700;">
                    @if ($prestacion->id_tipo_atencion == 1)
                        {{$prestacion->codigo_renaes}}
                    @elseif ($prestacion->id_tipo_atencion == 2)
                        {{$prestacion->codigo}}
                    @else
                        {{$prestacion->servicio}}
                    @endif
                </td>
                <td colspan="4" style="font-weight: 700;">
                    @if(!empty($radiologia->id_interconsulta) && !empty($fecha_aten_inter))
                        {{ \Carbon\Carbon::parse($fecha_aten_inter)->format('d-m-Y') }}
                    @else
                        {{$prestacion->fecha_atencion}}
                    @endif
                </td>
                <td colspan="4" style="font-weight: 700;">{{$radiologia->id}}</td>
                <td colspan="7" style="font-weight: 700;">LIMA</td>
                
            </tr>
            <tr>
                <td class="style9">DNI</td>
                <td colspan="2" style="font-weight: 700;">{{$prestacion->nro_doc_ident}}</td>
                <td class="style9" colspan="11">NOMBRE Y APELLIDOS DEL PACIENTE</td>
                <td class="style9" colspan="2">EDAD</td>
                <td class="style9" colspan="2">SEXO</td>
                <td class="style9" colspan="4">CONDICIÓN</td>
                <td class="style9" colspan="4">GRADO</td>
            </tr>
            <tr>
                <td class="style9">HC</td>
                <td colspan="2" style="font-weight: 700;">{{$prestacion->nro_historia}}</td>
                <td colspan="11" style="font-weight: 700;">{{$prestacion->asegurado_nombre}}, {{$prestacion->asegurado_paterno}} {{$prestacion->asegurado_materno}}</td>
                <td colspan="2" style="font-weight: 700;">{{$prestacion->edadpaciente}}</td>
                <td @if ($prestacion->sexo == 'F') style="background: #c6c6c6; font-weight: 700;" @endif>F</td>
                <td @if ($prestacion->sexo == 'M') style="background: #c6c6c6; font-weight: 700;" @endif>M</td>

                <td @if ($prestacion->parentesco == 'TITULAR') style="background: #c6c6c6; font-weight: 700;" @endif>T</td>
                <td @if ($prestacion->parentesco == 'CONYUGE') style="background: #c6c6c6; font-weight: 700;" @endif>C</td>
                <td @if ($prestacion->parentesco == 'HIJO INCAPACITADO' || $prestacion->parentesco == 'HIJO NO INCAPACITADO') style="background: #c6c6c6; font-weight: 700;" @endif>H</td>
                <td @if ($prestacion->parentesco == 'PADRES') style="background: #c6c6c6; font-weight: 700;" @endif>P</td>
                <td colspan="4" style="font-weight: 700;">{{ $prestacion->grado }}</td>
            </tr>
            <tr>
                <td class="style9">
                @if ($prestacion->id_tipo_atencion == 1)
                    SERVICIO
                @else
                    UBICACIÓN DEL PACIENTE 
                @endif
                </td>
                <td colspan="25" style="font-weight: 700; text-align: left; padding-left: 10px;">
                    @if ($prestacion->id_tipo_atencion == 1)
                        {{$prestacion->servicio}}
                    @elseif ($prestacion->id_tipo_atencion == 2)
                        {{$prestacion->ubicacion}}  / &nbsp; {{$camas->pabellon}} - PISO {{$camas->piso}} - {{$camas->cuarto}} - CAMA {{str_ireplace('CAMA ', '', $camas->cama)}}
                    @else
                        {{$prestacion->servicio}}   / &nbsp; {{$camas->pabellon}} - PISO {{$camas->piso}} - {{$camas->cuarto}} - CAMA {{str_ireplace('CAMA ', '', $camas->cama)}}
                    @endif
                </td>
                {{-- <td colspan="19">&nbsp;</td> --}}
            </tr>
            {{-- <tr>
                <td class="style9">
                @if ($prestacion->id_tipo_atencion == 1)
                    PROXIMA CITA
                @elseif ($prestacion->id_tipo_atencion == 2)
                    CAMA
                @else
                    CAMA
                @endif
                </td>
                <td colspan="25" style="text-align: left; font-weight: 700;">
                    @if ($prestacion->id_tipo_atencion == 1)
                        -
                    @elseif ($prestacion->id_tipo_atencion == 2)
                        &nbsp; {{$camas->pabellon}} - PISO {{$camas->piso}} - {{$camas->cuarto}} - CAMA {{$camas->cama}}
                    @else
                        &nbsp; {{$camas->pabellon}} - PISO {{$camas->piso}} - {{$camas->cuarto}} - CAMA {{$camas->cama}}
                    @endif
                    
                </td>
            </tr> --}}
            {{-- <tr>
                <td class="style9" colspan="2">DIAGNOSTICO 1</td>
                <td 
                    @if (isset($diagnostico))
                        @if (count($diagnostico)>0)
                            @if ($diagnostico[0]->id_tipo_diagnostico == "2") 
                                style="background: #c6c6c6; font-weight: 700;" 
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
                <td colspan="20" style="font-weight: 700;">
                    @if (isset($diagnostico))
                        @if (count($diagnostico)>0)
                            {{$diagnostico[0]->nombre}}
                        @endif
                    @endif
                </td>
                <td class="style9">CIE-10</td>
                <td colspan="1" style="font-weight: 700;">
                    @if (isset($diagnostico))
                        @if (count($diagnostico)>0)
                            {{$diagnostico[0]->codigo}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td class="style9" colspan="2">DIAGNOSTICO 2</td>
                <td 
                    @if (isset($diagnostico))
                        @if (count($diagnostico)>1)
                            @if ($diagnostico[1]->id_tipo_diagnostico == "2") 
                                style="background: #c6c6c6; font-weight: 700;" 
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
                <td colspan="20" style="font-weight: 700;">
                    @if (isset($diagnostico))
                        @if (count($diagnostico)>1)
                            {{$diagnostico[1]->nombre}}
                        @endif
                    @endif
                </td>
                <td class="style9">CIE-10</td>
                <td colspan="1" style="font-weight: 700;">
                    @if (isset($diagnostico))
                        @if (count($diagnostico)>1)
                            {{$diagnostico[1]->codigo}}
                        @endif
                    @endif
                </td>
            </tr> --}}
            <tr>
                <td class="style10" colspan="26">DATOS CLÍNICOS RELACIONADOS A LA SOLICITUD DEL EXAMEN</td>
            </tr>
            @if(!empty($radiologia->id_interconsulta))
                <tr>
                   {{--RESUMEN INTERCONSULTA : Maximo de caracteres 2345--}}
                    <td colspan="26"><p class="item-resumen-hc" @if(!empty($anamnesis_rad)) style="height: 90px !important;font-size:5.7" @else style="height: 350px !important;font-size:5.7" @endif><span class="sub-titulo-item-resumen-hc">RESUMEN INTERCONSULTA :</span><br>{{$interconsulta->evaluacion}}</p></td>
                </tr>
                @if(!empty($anamnesis_rad))
                    {{--ANAMNESIS : Maximo de caracteres 895--}}
                <tr>
                    <td colspan="26"><p class="item-resumen-hc" style="height: 110px !important;font-size:5.7"><span class="sub-titulo-item-resumen-hc"> ANAMNESIS :</span>{{$anamnesis_rad->anamnesis}}</p></td>
                </tr>
                <tr>
                    {{--EXAMEN FISICO : Maximo de caracteres 895--}}
                    <td colspan="26"><p class="item-resumen-hc" style="height: 60px !important;font-size:5.7"><span class="sub-titulo-item-resumen-hc"> EXAMEN FISICO :</span> {{$anamnesis_rad->examen_fisico}} </p></td>
                </tr>
                <tr>
                    {{--EXAMENES AUXILIARES : Maximo de caracteres 4354--}}
                    <td colspan="26"><p class="item-resumen-hc" style="height: 90px !important;font-size:5.7" ><span class="sub-titulo-item-resumen-hc"> EXAMENES AUXILIARES :</span>{{$anamnesis_rad->examen_auxiliar}}</p></td>
                </tr>
                @endif
            @else
                @if(/*!empty($tipo_atencion == 3) &&*/ !empty($anamnesis_rad))
                    {{-- @if($radiologia->id_evaluacion == 0)
                    <tr>
                        <td colspan="26"><p class="item-resumen-hc" style="height: 90px !important;"><span class="sub-titulo-item-resumen-hc">T.E :</span> {{ $prestacion->t_enfermedad }} {{ $prestacion->periodo }}</p></td>
                    </tr>
                    @endif --}}
                    <tr>
                        <td colspan="26"><p class="item-resumen-hc" style="height: 140px !important;" >@if($radiologia->id_evaluacion == 0)<span class="sub-titulo-item-resumen-hc">T.E :</span> {{ $prestacion->t_enfermedad }} {{ $prestacion->periodo }}<br>@endif <span class="sub-titulo-item-resumen-hc">ANAMNESIS :</span> {{$anamnesis_rad->anamnesis}}</p></td>
                    </tr>
                    <tr>
                        {{--EXAMEN FISICO : Maximo de caracteres 895--}}
                        <td colspan="26"><p class="item-resumen-hc" style="height: 130px !important;" ><span class="sub-titulo-item-resumen-hc"> EXAMEN FISICO :</span> {{$anamnesis_rad->examen_fisico}}</p></td>
                    </tr>
                    <tr>
                        {{--EXAMENES AUXILIARES : Maximo de caracteres 4354--}}
                        <td colspan="26"><p class="item-resumen-hc" style="height: 120px !important;" ><span class="sub-titulo-item-resumen-hc"> EXAMENES AUXILIARES :</span> {{$anamnesis_rad->examen_auxiliar}}</p></td>
                    </tr>
                @else
                <tr>
                    {{--T.E : Maximo de caracteres 2345--}}
                    <td colspan="26"><p class="item-resumen-hc" style="height: 90px !important;"><span class="sub-titulo-item-resumen-hc">T.E :</span>{{$prestacion->t_enfermedad}} {{$prestacion->periodo}} / {{$prestacion->tiempo_enfermedad}}</p></td>
                </tr>
                <tr>
                    {{--ANAMNESIS : Maximo de caracteres 5550--}}
                    <td colspan="26"><p class="item-resumen-hc" style="height: 110px !important;"><span class="sub-titulo-item-resumen-hc">ANAMNESIS :</span>{{$prestacion->anamnesis}}</p></td>
                </tr>
                <tr>
                    {{--EXAMEN FISICO : Maximo de caracteres 895--}}
                    <td colspan="26"><p class="item-resumen-hc" style="height: 60px !important;"><span class="sub-titulo-item-resumen-hc">EXAMEN FISICO :</span>{{$prestacion->examen_fisico}}</p></td>
                </tr>
                <tr>
                    {{--EXAMENES AUXILIARES : Maximo de caracteres 4354--}}
                    <td colspan="26"><p class="item-resumen-hc" style="height: 90px !important;"><span class="sub-titulo-item-resumen-hc">EXAMENES AUXILIARES :</span>{{$prestacion->examen_auxiliar}}</p></td>
                </tr>
                @endif
            @endif
            <tr>
                <!-- <td colspan="2" style="text-align: center; background: #8ed048;"><span class="sub-titulo-item-resumen-hc" style="width: 100%;">COMPARATIVAS :</span></td>
                <td colspan="2" style="text-align: center;">SI</td>
                <td colspan="2" style="text-align: center;">NO</td>
                <td colspan="2" style="text-align: center; background: #8ed048;"><span class="sub-titulo-item-resumen-hc" style="width: 100%">LADO :</span></td>
                <td colspan="2" style="text-align: center;">DER</td>
                <td colspan="2" style="text-align: center;">IZQ</td> -->
                <td colspan="26" style="text-align: left;background: #c6c6c6;">&nbsp;</td>
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
                    <td class="style-columnas" colspan="26">
                        <?php
                        $contador = 0;
                        $cantidad_por_pagina = 11; // más/menos filas por página
                        $titulo = '';
                        $need_header = false; 
                        if (isset($lista_rad) && count($lista_rad) > 0) {

                            foreach ($lista_rad as $rad) {

                                if ($rad->nombre != $titulo || $need_header) {
                                    $titulo = $rad->nombre;
                                    $need_header = false;

                                    echo '<table class="table-cpms" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>';
                                    echo '<tr class="row11">';
                                    echo '<td style="background:#8ed048;font-weight:700;font-size:5pt; text-align:center;padding: 0.2rem;" width="6%">CPT</td>';
                                    echo '<td style="background:#8ed048;font-weight:700;font-size:5pt; text-align:center;padding: 0.2rem;" width="30%">'.$titulo.'</td>'; // anterior era 50%
                                    echo '<td style="background:#8ed048;font-weight:700;font-size:5pt; text-align:center;padding: 0.2rem;" width="6%">LADO</td>';
                                    echo '<td style="background:#8ed048;font-weight:700;font-size:5pt; text-align:center;padding: 0.2rem;" width="26%">INDICACIONES</td>';
                                    echo '<td style="background:#8ed048;font-weight:700;font-size:5pt; text-align:center;padding: 0.2rem;" width="6%">DX(CIE-10)</td>';
                                    echo '<td style="background:#8ed048;font-weight:700;font-size:5pt; text-align:center;padding: 0.2rem;" width="20%">DIAGNÓSTICO</td>';
                                    echo '<td style="background:#8ed048;font-weight:700;font-size:5pt; text-align:center;padding: 0.2rem;" width="6%">TIPO DE DX</td>';
                                    echo '</tr>';
                                    echo '</tbody></table>';
                                }

                                echo '<table class="table-cpms" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>';
                                echo '<tr class="row11">';

                                echo '<td style="font-size:6pt; text-align:center; padding: 0.2rem;" width="6%">';
                                echo (isset($rad->codigo) ? $rad->codigo : '');
                                echo '</td>';

                                echo '<td style="font-size:6pt; text-align:left; padding: 0.2rem;" width="30%">';
                                echo '&nbsp;'.(isset($rad->descripcion_corta) ? $rad->descripcion_corta : '');
                                echo '</td>';

                                $ladoTexto = "NINGUNO";
                                if (isset($rad->lado_radio)) {
                                    switch ((int)$rad->lado_radio) {
                                        case 1:
                                            $ladoTexto = "IZQUIERDO";
                                            break;
                                        case 2:
                                            $ladoTexto = "DERECHO";
                                            break;
                                        case 3:
                                            $ladoTexto = "AMBOS";
                                            break;
                                    }
                                }
                                echo '<td style="font-size:6pt; text-align:center; padding: 0.2rem;" width="6%">';
                                echo '&nbsp;'.$ladoTexto;
                                echo '</td>';
                                
                                echo '<td style="font-size:6pt; text-align:left; padding-left:4px;" width="26%">';
                                echo (isset($rad->observacion) ? $rad->observacion : '');
                                echo '</td>';

                                echo '<td style="font-size:6pt; text-align:center; padding: 0.2rem;" width="6%">';
                                echo '&nbsp;'.(isset($rad->codigo_diag) ? $rad->codigo_diag : '');
                                echo '</td>';

                                echo '<td style="font-size:6pt; text-align:center; padding: 0.2rem;" width="20%">';
                                echo '&nbsp;'.(isset($rad->nombre_diag) ? $rad->nombre_diag : '');
                                echo '</td>';

                                echo '<td style="font-size:6pt; text-align:center; padding: 0.2rem;" width="6%">';
                                echo '&nbsp;'.(isset($rad->tipo_diag) ? $rad->tipo_diag : '');
                                echo '</td>';

                                echo '</tr>';
                                echo '</tbody></table>';

                                $contador++;
                                if ($contador % $cantidad_por_pagina == 0) {
                                    echo '</td></tr></tbody></table>';
                                    echo '</main>'; 
                                    echo '<div style="page-break-after: always;"></div>';
                                    echo '<main style="margin-top: 325px; margin-bottom: 0px;">';
                                    echo '<table style="width: 100%; padding-top: 1px;" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr class="row11">
                                                    <td class="style-columnas" colspan="26">';
                                    $need_header = true;
                                }
                            }
                        }
                        ?>
                    </td>
                </tr>
            <!--fin-->
            <tr>
                <td class="descripcion-footer" colspan="26">TM:tomografia;RM:resonancia magnetica;CC: con contraste;SC: sin contraste;ECO:ecografía;Rx: rayos x;SUP:superior; INF:inferior;DER: derecho; IZQ:izquierda;INC:incidencias;</td>
            </tr>
            <tr>
                <td class="style11" colspan="26"><span>NOTA</span>: Toda modificación del presente formato por personal no autorizado, INVALIDARÁ la atención del paciente en el Servicio de Diagnóstico por Imagen.</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; background: #8ed048;"><span class="sub-titulo-item-resumen-hc" style="width: 100%;">N. DE EXÁMENES :</span></td>
                <td colspan="2" style="text-align: center;">{{$total_examenes_prestacion_procedimientos}}</td>
                <td colspan="22" style="text-align: center;"></td>
            </tr>
            <!--tr>
                <td class="style12" rowspan="2">N. DE EXÁMENES</td>
                <td class="style13" colspan="6">MÉDICO TRATANTE</td>
                <td class="style14" colspan="6">V°B° JEFE DEPARTAMENTO QUE REFIERE</td>
                <td class="style15" colspan="8">ENCARGADO DE LA TOMA DEL EXAMEN</td>
                <td class="style15" colspan="5">FECHA Y HORA DE TOMA DEL EXAMEN</td>
                
            </tr>
            <tr>
                <td class="style24" rowspan="2" colspan="6" align="center">
                    <div align="center" style="height: 60px;">
                        <table class="table-firmas style24" width="100%" >
                          <tbody>
                              <tr>
                                  <td width="40%"><p align="right"><img src="images/logo_sanidad.png" width="55px;" /></p></td>
                                  <td width="60%"><p align="left">Firmado digitalmente por: <br/><?php echo $prestacion->medico_nombre." ".$prestacion->medico_paterno." ".$prestacion->medico_materno ?><br/>Motivo:Soy el autor del documento<br/>Fecha: <?php $fecha_firma = date("d/m/Y h:i:s ", strtotime($radiologia->fecha_firma));
                                    echo $fecha_firma; ?></p></td>
                              </tr>
                          </tbody>
                        </table>
                    </div>
                </td>
                <td class="style21" rowspan="2" colspan="6">&nbsp;</td>
                <td class="style16" rowspan="2" colspan="8">&nbsp;</td>
                <td class="style16" rowspan="2" colspan="5">&nbsp;</td>
            </tr>

            <tr>
                <td rowspan="2" style="font-weight: 700;">{{$total_examenes_prestacion_procedimientos}}</td>
            </tr>

            <tr>
                <td class="style17" colspan="6">Firma, Sello, Colegiatura</td>
                <td class="style18" colspan="6">Firma, Sello, Colegiatura</td>
                <td class="style19" colspan="8">Firma, Sello, CTMP</td>
                <td class="style19" colspan="5">&nbsp;</td>
            </tr-->
        </tbody>
    </table>
</body>
</html>