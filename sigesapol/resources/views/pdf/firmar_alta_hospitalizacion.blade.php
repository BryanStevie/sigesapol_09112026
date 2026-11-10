<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ALTA DE HOSPITALIZACION</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 1cm 1.5cm 2cm 1.5cm !important;
            color: #001028;
            background: #FFFFFF; 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            font-family: Arial;
            /* border: 1px solid red; */
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px !important;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        footer {               
            position: fixed;
            bottom: 2cm;
            left: 1.5cm;
            right: 1.5cm;
            width: 100%;
            text-align: center; 
            overflow: auto;
        }
        #tbl_header {
            text-align: center;
        }
        #tbl_hospitalizacion td,
        #tbl_hospitalizacion_atencion td,
        .tbl_antecedente td {
            text-align: left;
            vertical-align: top;
        }

        .tbl_diagnostico td {
            text-align: left;
            vertical-align: top;
        }
        table.tbl_diagnostico th {
            text-align: left;
        }
        
        #tbl_hospitalizacion_footer td {
            border: 1px black solid !important;
            font-size: 9px;
            padding-top: 1px;
            padding-bottom: 1px;
            text-align: center;
            margin-bottom: 0 !important;
        }

        /**
        Para los subtitulos que tengan Numero Romanos
         */
        .subtitulo_1 {
            font-size: 14px; 
            padding-bottom:10px;
        }

        /**
        Para los subtitulos que tengan Numero enteros
         */
        .subtitulo_2 {
            font-size: 13px; 
            padding-bottom:1px;
        }

        /**
        Estilos para la tabla de ordenes opcionales (Recetas Vale, Ordenes de Laboratorio,
        Ordenes de Imagenologia e Interconsultas).
         */
        .tbl_ordenes td {
            padding: 3px;
        }

        table.tbl_antecedente {
            margin-bottom: 0 !important;
        }

        table.tbl_antecedente td {
            font-weight: 600;
            padding-top: 5px;
            padding-right: 5px;
           
        }
        table.tbl_antecedente td span,
        table.tbl_antecedente td p {
            font-weight: normal;
        }

        .page_break {
        page-break-before: always;
        }

    </style>
</head>

<body >
    <br/>
    <table id="tbl_header">
        <tr>
            <th style="width:30%"></th>
            <th style="width:30%"></th>
            <th style="width:40%"></th>
        </tr>
        <tr>
            <td>
                <!--img src="images/logo_pnp.png" alt="" width="40px;"/>
                <p><b>MINISTERIO DEL INTERIOR<br>POLICÍA NACIONAL DEL PERÚ</b></p-->
            </td>
            <td></td>
            <td>
                <p><br><br><br><br><b>DIRECCIÓN DE SANIDAD<br>{{$nombre_establecimiento}}</b></p>
            </td> 
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 18px;"><b>ALTA DE HOSPITALIZACIÓN</b></td>
        </tr>
    </table>

    <footer>
        <table id="tbl_hospitalizacion_footer"  width="100%">
            <tr>
                <td>
                    <b>Apellido Paterno</b>
                </td>
                <td>
                    <b>Apellido Materno</b>
                </td>
                <td>
                    <b>Nombres</b>
                </td>
                <td style="width: 80px">
                    <b>Historia Clínica</b>
                </td>
                <td style="width: 150px">
                    <b>Servicio</b>
                </td>
                <td style="width: 60px">
                    <b>N° de Cama</b>
                </td>
                <!-- <td style="width: 50px">
                    <b>Episodio</b>
                </td> -->
            </tr>
            <tr>
                <td style="height:22px !important">{{$hospitalizacion->paterno_beneficiario}}</td>
                <td>{{$hospitalizacion->materno_beneficiario}}</td>
                <td>{{$hospitalizacion->nombre_beneficiario}}</td>
                <td>{{$hospitalizacion->nro_historia}}</td>
                <td>{{$hospitalizacion->servicio_sala}}</td>
                <td>{{$hospitalizacion->cama}}</td>
                <!-- <td></td> -->
            </tr>
        </table>
        <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_script('
                    $pdf->text(560, 20, "$PAGE_NUM/$PAGE_COUNT", "Arial", 8);
                ');
            }
        </script> 
    </footer> 
    
    <table border="0" id="tbl_hospitalizacion" >
        <tr>
            <th style="width:38%"></th>
            <th style="width:38%"></th>
            <th style="width:2%"></th>
            <th style="width:7%"></th>
            <th style="width:15%"></th>
        </tr>
        <tr>
            <td colspan="5" class="subtitulo_2"><b>DATOS DEL PACIENTE</b></td>
        </tr>
        <tr>
            <td colspan="2">Apellidos y Nombres: <b>{{$hospitalizacion->paterno_beneficiario}} {{$hospitalizacion->materno_beneficiario}} {{$hospitalizacion->nombre_beneficiario}}</b></td>
            <td></td>
            <td><b>DNI</b></td>
            <td>{{$hospitalizacion->dni_beneficiario}}</td>
        </tr>
        <tr>
            <td>Edad: <b>{{$hospitalizacion->edad}}</b></td>
            <td>Sexo: <b>{{$hospitalizacion->sexo}}</b></td>
            <td></td>
            <td><b>CIP</b></td>
            <td>@if($persona_datos_adicionales != null) {{$persona_datos_adicionales->cip}} @endif</td>
        </tr>
        <tr>
            <td>Raza: <b>{{$asegurado->etnia}}</b></td>
            <td>Estado civil: <b>{{$asegurado->estado_civil}}</b></td>
            <td></td>
            <td><b>HC</b></td>
            <td>{{$hospitalizacion->nro_historia}}</td>
        </tr>
        <tr>
            <td>Grado de Instrucción: <b>{{$asegurado->grado_instruccion}}</b></td>
            <td>Ocupación: <b>{{$asegurado->ocupacion}}</b></td>
            <td></td>
            <td><b>RX N°</b></td>
            <td>-</td>
        </tr>
        <tr>
            <td>Lugar de Nacimiento: <b>{{$asegurado->departamento_nacimiento2}} / {{$asegurado->provincia_nacimiento2}} / {{$asegurado->distrito_nacimiento2}} / {{strtoupper($asegurado->direccion_nacimiento)}}</b></td>
            <td>Fecha de nacimiento: <b>{{$asegurado->fecha_nac}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Procedencia: <b>{{$asegurado->departamento_procedencia2}} / {{$asegurado->provincia_procedencia2}} / {{$asegurado->distrito_procedencia2}} / {{strtoupper($asegurado->direccion_procedencia)}}</b></td>
            <td>Religión: <b>{{$asegurado->religion}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Domicilio: <b>{{strtoupper($asegurado->domicilio)}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Distrito: <b>{{$asegurado->distrito}}</b></td>
            <td>Departamento: <b>{{$asegurado->departamento}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Teléfono fijo: <b>{{$asegurado->telefono}}</b></td>
            <td>Celular: <b>{{$asegurado->telefono2}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if (!empty($titular))
        <tr>
            <td colspan="2">Apellidos y Nombres del titular: <b>{{$titular->paterno}} {{$titular->materno}} {{$titular->nombre}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endif
        <tr>
            <td colspan="2">Grado de Situación del titular: <b>@if($grado != null) {{$grado->descripcion_corta}} @endif</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Parentesco del paciente: <b>{{$hospitalizacion->tipo_beneficiario}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de ingreso: <b>{{$hospitalizacion->fecha_ingreso}} {{$hospitalizacion->hora_ingreso}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de Historia Clínica: <b>{{$hospitalizacion->created_at}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Código SITEDS: <b>{{$hospitalizacion->cod_siteds_susalud}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">CUPS: <b>{{$hospitalizacion->cups_pnp}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </table>
    <hr>
    


    <?php
    $tipo_egreso = "";
    if($hospitalizacion->id_tipo_egreso=="1"){
        $tipo_egreso = "ALTA";
    }else if($hospitalizacion->id_tipo_egreso=="2"){
        $tipo_egreso = "HOSPITALIZACION";
    }else if($hospitalizacion->id_tipo_egreso=="3"){
        $tipo_egreso = "TRASNFERIDO";
    }else if($hospitalizacion->id_tipo_egreso=="4"){
        $tipo_egreso = "FALLECIDO";
    }else if($hospitalizacion->id_tipo_egreso=="7"){
        $tipo_egreso = "RETIRO VOLUNTARIO";
    }else if($hospitalizacion->id_tipo_egreso=="6"){
        $tipo_egreso = "FUGA";
    }

    $condicion_egreso = "";
    if($hospitalizacion->id_condicion_alta=="1"){
        $condicion_egreso = "MEJORADO";
    }else if($hospitalizacion->id_condicion_alta=="2"){
        $condicion_egreso = "CURADO";
    }else if($hospitalizacion->id_condicion_alta=="3"){
        $condicion_egreso = "TRASLADO";
    }else if($hospitalizacion->id_condicion_alta=="4"){
        $condicion_egreso = "RETIRO VOLUNTARIO";
    }else if($hospitalizacion->id_condicion_alta=="5"){
        $condicion_egreso = "FUGADO";
    }else if($hospitalizacion->id_condicion_alta=="6"){
        $condicion_egreso = "FALLECIDO";
    }
    
    ?>

        <table border="0" id="" >
            <tr>
                <td class="subtitulo_2"><b>RESUMEN DE LA ENFERMEDAD ACTUAL, EXAMEN FÍSICO, EXÁMENES AUXILIARES, EVOLUCIÓN Y TRATAMIENTO</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($hospitalizacion->egreso_resumen)) !!}</p></td>
            </tr>
        </table>
    
        <table border="0" id="" >
            <tr>
                <td class="subtitulo_2"><b>COMPLICACIONES</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($hospitalizacion->egreso_complicacion)) !!}</p></td>
            </tr>
        </table>

        <table border="1" class="tbl_antecedentes">
            <tr>
                <td colspan="4" class="subtitulo_2"><b>DIAGNÓSTICO</b></td>
            </tr>
            @if($hospitalizacion->id_diag_cab != null)
            <tr>
                <td style="width:5%"><b>1.-</b></td>
                <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_1 }}</p></td>
                <td style="width:10%"><b>CIE:</b> {{ $hospitalizacion->codigo_cie_egreso_1 }}</td>
                <td style="width:15%">PRINCIPAL</td>
            </tr>
            @endif
            @if($hospitalizacion->id_diag_cuer1 != null)
            <tr>
                <td style="width:5%"><b>2.-</b></td>
                <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_2 }}</p></td>
                <td style="width:10%"><b>CIE:</b> {{ $hospitalizacion->codigo_cie_egreso_2 }}</td>
                <td style="width:15%">PRINCIPAL</td>
            </tr>
            @endif
            @if($hospitalizacion->id_diag_cuer2 != null)
            <tr>
                <td style="width:5%"><b>3.-</b></td>
                <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_3 }}</p></td>
                <td style="width:10%"><b>CIE:</b> {{ $hospitalizacion->codigo_cie_egreso_3 }}</td>
                <td style="width:15%">PRINCIPAL</td>
            </tr>
            @endif
        </table>

        <table border="1" class="tbl_antecedentes">
            <tr>
                <td style="width:200px"><b>FECHA Y HORA DE EGRESO</b></td>
                <td style="width:150px"><b>TIPO DE EGRESO</b></td>
                <td style="width:150px"><b>CONDICION DE EGRESO</b></td>
            </tr>
            <tr>
                <td>{{ date("d/m/Y", strtotime($hospitalizacion->fecha_salida)) }} {{ date("H:i:s", strtotime($hospitalizacion->hora_egreso)) }}</b></td>
                <td>{{ $tipo_egreso }}</td>
                <td>{{ $condicion_egreso }}</td>
            </tr>
            @if ($hospitalizacion->id_tipo_egreso == 3)
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="2"><b>ESTABLECIMIENTO DE TRASLADO</b></td>
                <td colspan="2"><b>COMENTARIO DEL TRASLADO</b></td>
            </tr>
            <tr>
                <td colspan="2">{{ $hospitalizacion->establecimiento_traslado }}</td>
                <td colspan="2">{{ $hospitalizacion->obs_traslado }}</td>
            </tr>
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="1"><b>ESPECIALIDAD DE DESTINO</b></td>
                <td colspan="3"><b>DETALLE</b></td>
            </tr>
            <tr>
                <td colspan="1">{{ $hospitalizacion->especialida_destino }}</td>
                <td colspan="3">{{ $hospitalizacion->especialidad_destino_otro }}</td>
            </tr>
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="4"><b>CONDICION DEL PACIENTE A LA HORA DEL TRASLADO</b></td>
            </tr>
            <tr>
                <td colspan="4">{{ $hospitalizacion->condicion_traslado }}</td>
            </tr>
            @elseif ($hospitalizacion->id_tipo_egreso == 4)
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="4"><b>FECHA Y HORA DE DEFUNCIÓN</b></td>
            </tr>
            <tr>
                <td colspan="4">{{ date("d/m/Y", strtotime($hospitalizacion->fecha_defuncion)) }} {{ date("H:i:s", strtotime($hospitalizacion->hora_defuncion)) }}</td>
            </tr>
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td><b>SE REALIZÓ NECROPSIA</b></td>
                <td colspan="3"><b>CAUSAS DE LA MUERTE</b></td>
            </tr>
            <tr>
                <td>{{ $hospitalizacion->necropsia }}</td>
                <td colspan="3">{{ $hospitalizacion->causas_muerte }}</td>
            </tr>
            @if ($hospitalizacion->id_servicio_sala_egreso == 77)
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="4"><b>MUERTE PERINATAL</b></td>
            </tr>
            <tr>
                <td colspan="4"><b>{{ $hospitalizacion->muerte_perinatal }}</b></td>
            </tr>
            @endif
            @elseif ($hospitalizacion->id_tipo_egreso == 7 || $hospitalizacion->id_tipo_egreso == 8)
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="4"><b>OBSERVACION</b></td>
            </tr>
            <tr>
                <td colspan="2">{{ $hospitalizacion->observacion_egreso }}</td>
            </tr>
            @endif
        </table>

        <table border="0" >
            <tr>
                <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
            </tr>
        </table>

        <table border="1" style="" >
            <tr>
                <td><b>APELLIDOS Y NOMBRES:</b></td>
                <td colspan="3">{{ $hospitalizacion->profesional_egreso_paterno }} {{ $hospitalizacion->profesional_egreso_materno }} {{ $hospitalizacion->profesional_egreso_nombre }}</td>
            </tr>
            <tr>
                <td style="width:23%; padding-top: 5px;"><b>PROFESIÓN:</b></td>
                <td style="">NO INDICA</td>
                <td style="width:17%"><b>N° COLEGIATURA:</b></td>
                <td style="width:10%">{{ $hospitalizacion->profesional_egreso_colegiatura }}</td>
            </tr>
        </table>
        
</body>
</html>
