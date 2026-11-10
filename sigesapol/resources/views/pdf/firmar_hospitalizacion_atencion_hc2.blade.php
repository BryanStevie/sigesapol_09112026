<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ATENCION HOSPITALIZACION</title>
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
            margin: 1cm 1.5cm 1cm 1.5cm !important;
            color: #001028;
            background: #FFFFFF; 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            font-family: Arial;
            /* border: 1px solid red; */
        }
        
        table {
            width: 100%;
            /* border-collapse: collapse; */
            border-spacing: 0;
            margin-bottom: 15px !important;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        footer {               
            /* position: fixed; */
            bottom: 3cm;
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
        #tbl_hospitalizacion_atencion td {
            text-align: left;
        }

        .tbl_diagnostico td {
            text-align: left;
        }
        
        table#tbl_hospitalizacion_footer {
            margin-bottom: 0 !important;
        }

        #tbl_hospitalizacion_footer td {
            border: 1px black solid !important;
            font-size: 9px;
            padding-top: 1px;
            padding-bottom: 1px;
            text-align: center;
            margin-bottom: 0 !important;
        }

        table.tbl_evolucion_footer {
            margin-bottom: 0 !important;
        }

        .tbl_evolucion_footer td {
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
        Para los espacios entre las filas de un table, solo para la seccion de alta
         */
        .interlineado_tabla_seccion_alta {
            font-size: 5px;
        }

        /**
        Estilos para la tabla de ordenes opcionales (Recetas Vale, Ordenes de Laboratorio,
        Ordenes de Imagenologia e Interconsultas).
         */
        .tbl_ordenes td {
            padding: 3px;
        }
    </style>
</head>

<body >

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
                <p><br><br><br><br><b>DIRECCIÓN DE SANIDAD<br>HOSPITAL NACIONAL PNP "LUIS N. SÁENZ"</b></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 18px;"><b>HISTORIA CLÍNICA DE HOSPITALIZACIÓN</b></td>
        </tr>
    </table>
    
    
    
    <table border="0" id="tbl_hospitalizacion" >
        <tr>
            <th style="width:38%"></th>
            <th style="width:38%"></th>
            <th style="width:2%"></th>
            <th style="width:7%"></th>
            <th style="width:15%"></th>
        </tr>
        <tr>
            <td colspan="5" class="subtitulo_1"><b>I.- ANAMNESIS</b></td>
        </tr>
        <tr>
            <td colspan="5" class="subtitulo_2"><b>1.- FILIACIÓN</b></td>
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
        <tr>
            <td colspan="2">Apellidos y Nombres del titular: <b>{{$titular->paterno}} {{$titular->materno}} {{$titular->nombre}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Grado de Situación del titular: <b>@if($grado != null) {{$grado->descripcion_corta}} @endif</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Parentesco con el paciente: <b>{{$hospitalizacion->tipo_beneficiario}}</b></td>
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

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <th style="width:100%"></th>
        </tr>
        <tr>
            <td class="subtitulo_2"><b>2.- ENFERMEDAD ACTUAL</b></td>
        </tr>
        <tr>
            <td><p>{!! nl2br(e($prestacion->tiempo_enfermedad)) !!}</p></td>
        </tr>
    </table>

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <td class="subtitulo_2"><b>3.- ANTECEDENTES</b></td>
        </tr>
        <tr>
            <td><b>PERSONALES</b></td>
        </tr>
        <tr>
            <td><p>{!! nl2br(e($prestacion->antecedente)) !!}</p></td>
        </tr>
    </table>

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <td><b>FAMILIARES</b></td>
        </tr>
        <tr>
            <td><p style="margin:0;">{!! nl2br(e($prestacion->antecedente_familiar)) !!}</p></td>
        </tr>
    </table>

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <td colspan="4" class="subtitulo_1"><b>II.- EXAMEN FÍSICO</b></td>
        </tr>
        <tr>
            <td colspan="4" class="subtitulo_2"><b>1.- FUNCIONES VITALES</b></td>
        </tr>
        <tr>
            <td>Presión arterial: <b>{{ $prestacion->presion_arterial_x }} / {{$prestacion->presion_arterial_y}}</b></td>
            <td>Frecuencia Cardiaca: <b>{{ $prestacion->frecuencia_cardiaca }}</b></td>
            <td>Frecuencia Respiratoria: <b>{{ $prestacion->frecuencia_respiratoria }}</b></td>
            <td>Temperatura: <b>{{ $prestacion->temperatura }}</b></td>
        </tr>
        <tr>
            <td>Talla: <b>{{ $prestacion->talla_cm }}</b></td>
            <td>Peso: <b>{{ $prestacion->peso_kg }}</b></td>
            <td>Tamaño de Cintura: <b>{{ $prestacion->tamano_cintura_cm }}</b></td>
            <td>SaO2: <b>{{ $prestacion->sa02_porcentaje }}</b></td>
        </tr>
        <tr>
            <td>FiO2: <b>{{ $prestacion->fi02 }}</b></td>
            <td>Frecuencia Cardiaca Fetal: <b>{{ $prestacion->frecuencia_cardiaca_fetal }}</b></td>
        </tr>
    </table>

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <td class="subtitulo_2"><b>2.- EXAMEN GENERAL</b></td>
        </tr>
        <tr>
            <td><p>{!! nl2br(e($prestacion->examen_clinico)) !!}</p></td>
        </tr>
    </table>

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <td class="subtitulo_2"><b>3.- EXAMEN REGIONAL</b></td>
        </tr>
        <tr>
            <td><p>{!! nl2br(e($prestacion->examen_clinico_regional)) !!}</p></td>
        </tr>
    </table>

    <table style="width: 100%;" border="0" class="tbl_diagnostico" >
        <tr>
            <td colspan="3" class="subtitulo_1"><b>III.- DIAGNÓSTICO</b></td>
        </tr>
        <tr>
            <td colspan="3"><b>DIAGNOSTICOS PRESUNTIVOS</b></td>
        </tr>
        @foreach($diagnosticos as $dx)
        @if($dx->id_tipo_diagnostico == 1)
        <tr>
            <?php
            $i = 1;
            $categoria = "";
            switch($dx->id_categoria): 
                case 1: $categoria = "PRINCIPAL"; break;
                case 2: $categoria = "SECUNDARIO"; break;
            endswitch
            ?>
            <td style="width:5%"><b>{{ $i }}.-</b></td>
            <td style="width:70%"><p>{{ $dx->nombre_dx }}</p></td>
            <td style="width:10%"><b>CIE:</b> {{ $dx->cod_dx }}</td>
            <td style="width:15%">{{ $categoria }}</td>
            <?php
            $i++;
            ?>
        </tr>
        @endif
        @endforeach
    </table>
    <table style="width: 100%;" border="0" class="tbl_diagnostico" >
        <tr>
            <td colspan="3"><b>DIAGNOSTICOS DEFINITIVOS</b></td>
        </tr>
        @foreach($diagnosticos as $dx)
        @if($dx->id_tipo_diagnostico == 2)
        <tr>
            <?php
            $i = 1;
            $categoria = "";
            switch($dx->id_categoria): 
                case 1: $categoria = "PRINCIPAL"; break;
                case 2: $categoria = "SECUNDARIO"; break;
            endswitch
            ?>
            <td style="width:5%"><b>{{ $i }}.-</b></td>
            <td style="width:70%"><p>{{ $dx->nombre_dx }}</p></td>
            <td style="width:10%"><b>CIE:</b> {{ $dx->cod_dx }}</td>
            <td style="width:15%">{{ $categoria }}</td>
            <?php
            $i++;
            ?>
        </tr>
        @endif
        @endforeach
    </table>
    <table style="width: 100%;" border="0" class="tbl_diagnostico" >
        <tr>
            <td colspan="3"><b>DIAGNOSTICOS REITERATIVOS</b></td>
        </tr>
        @foreach($diagnosticos as $dx)
        @if($dx->id_tipo_diagnostico == 3)
        <tr>
            <?php
            $i = 1;
            $categoria = "";
            switch($dx->id_categoria): 
                case 1: $categoria = "PRINCIPAL"; break;
                case 2: $categoria = "SECUNDARIO"; break;
            endswitch
            ?>
            <td style="width:5%"><b>{{ $i }}.-</b></td>
            <td style="width:70%"><p>{{ $dx->nombre_dx }}</p></td>
            <td style="width:10%"><b>CIE:</b> {{ $dx->cod_dx }}</td>
            <td style="width:15%">{{ $categoria }}</td>
            <?php
            $i++;
            ?>
        </tr>
        @endif
        @endforeach
    </table>

    <table border="0" style="" >
        <tr>
            <td><b>APELLIDOS Y NOMBRES:</b></td>
            <td colspan="3">{{ $prestacion->profesional_paterno }} {{ $prestacion->profesional_materno }} {{ $prestacion->profesional_nombre }}</td>
        </tr>
        <tr>
            <td style="width:23%; padding-top: 5px;"><b>PROFESIÓN:</b></td>
            <td style="">{{ $prestacion->profesional_profesion }}</td>
            <td style="width:17%"><b>N° COLEGIATURA:</b></td>
            <td style="width:10%">{{ $prestacion->profesional_colegiatura }}</td>
        </tr>
    </table>

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table id="tbl_hospitalizacion_footer">
            <tr>
                <td colspan="7" class="td_firma"><br><br><br><br></td>
            </tr>
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
                <td style="width: 50px">
                    <b>Episodio</b>
                </td>
            </tr>
            <tr>
                <td style="height:22px !important">{{$hospitalizacion->paterno_beneficiario}}</td>
                <td>{{$hospitalizacion->materno_beneficiario}}</td>
                <td>{{$hospitalizacion->nombre_beneficiario}}</td>
                <td>{{$hospitalizacion->nro_historia}}</td>
                <td>{{$hospitalizacion->servicio_sala}}</td>
                <td>{{$hospitalizacion->cama}}</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div style="page-break-after:always;"></div>

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <td colspan="4" class="subtitulo_1"><b>VI.- EVOLUCIONES</b></td>
        </tr>
    </table>
    @foreach($evoluciones as $evolucion)
        <table border="0" id="" >
            <tr>
                <td>Fecha y Hora de Evolución de la atención: <b>{{ date("d/m/Y H:i:s", strtotime($evolucion->created_at))}}</b></td>
            </tr>
            <tr>
                <td>Código SITEDS: <b>{{ $evolucion->cod_siteds_susalud}}</b></td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td colspan="4" class="subtitulo_2"><b>FUNCIONES VITALES</b></td>
            </tr>
            <tr>
                <td>Presión arterial: <b>{{ $evolucion->presion_arterial_x }} / {{$evolucion->presion_arterial_y}}</b></td>
                <td>Frecuencia Cardiaca: <b>{{ $evolucion->frecuencia_cardiaca }}</b></td>
                <td>Frecuencia Respiratoria: <b>{{ $evolucion->frecuencia_respiratoria }}</b></td>
                <td>Temperatura: <b>{{ $evolucion->temperatura }}</b></td>
            </tr>
            <tr>
                <td>Talla: <b>{{ $evolucion->talla_cm }}</b></td>
                <td>Peso: <b>{{ $evolucion->peso_kg }}</b></td>
                <td>Tamaño de Cintura: <b>{{ $evolucion->tamano_cintura_cm }}</b></td>
                <td>SaO2: <b>{{ $evolucion->sa02_porcentaje }}</b></td>
            </tr>
            <tr>
                <td>FiO2: <b>{{ $evolucion->fi02 }}</b></td>
                <td>Frecuencia Cardiaca Fetal: <b>{{ $evolucion->frecuencia_cardiaca_fetal }}</b></td>
            </tr>
        </table>

        <table border="0" id="" >
            <tr>
                <th style="width:100%"></th>
            </tr>
            <tr>
                <td class="subtitulo_2"><b>APRECIACIÓN OBJETIVA</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($evolucion->apreciacion_objetiva)) !!}</p></td>
            </tr>
        </table>

        <table border="0" id="" >
            <tr>
                <td class="subtitulo_2"><b>APRECIACIÓN SUBJETIVA</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($evolucion->apreciacion_subjetiva)) !!}</p></td>
            </tr>
        </table>

        <table border="0" id="" >
            <tr>
                <td class="subtitulo_2"><b>VERIFICACIÓN DEL TRATAMIENTO Y DIETA</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($evolucion->verificacion_tratamiento)) !!}</p></td>
            </tr>
        </table>

        <table border="0" id="" >
            <tr>
                <td class="subtitulo_2"><b>INTERPRETACIÓN DE EXAMENES Y COMENTARIO</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($evolucion->interpretacion_examenes)) !!}</p></td>
            </tr>
        </table>

        <table border="0" id="" >
            <tr>
                <td class="subtitulo_2"><b>TERAPEUTICA Y PLAN DE TRABAJO</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($evolucion->plan_trabajo)) !!}</p></td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td colspan="3" class="subtitulo_1"><b>DIAGNÓSTICO</b></td>
            </tr>
            <tr>
                <td colspan="3"><b>DIAGNOSTICOS PRESUNTIVOS</b></td>
            </tr>
            @foreach($diagnosticos_evoluciones as $dx)
            @if($evolucion->id == $dx->id_evolucion && $dx->id_tipo_diagnostico == 1)
            <tr>
                <?php
                $i = 1;
                $categoria = "";
                switch($dx->id_categoria): 
                    case 1: $categoria = "PRINCIPAL"; break;
                    case 2: $categoria = "SECUNDARIO"; break;
                endswitch
                ?>
                <td style="width:5%"><b>{{ $i }}.-</b></td>
                <td style="width:70%"><p>{{ $dx->nombre_dx }}</p></td>
                <td style="width:10%"><b>CIE:</b> {{ $dx->cod_dx }}</td>
                <td style="width:15%">{{ $categoria }}</td>
                <?php
                $i++;
                ?>
            </tr>
            @endif
            @endforeach
        </table>
        <table border="0">
            <tr>
                <td colspan="3"><b>DIAGNOSTICOS DEFINITIVOS</b></td>
            </tr>
            @foreach($diagnosticos_evoluciones as $dx)
            @if($evolucion->id == $dx->id_evolucion && $dx->id_tipo_diagnostico == 2)
            <tr>
                <?php
                $i = 1;
                $categoria = "";
                switch($dx->id_categoria): 
                    case 1: $categoria = "PRINCIPAL"; break;
                    case 2: $categoria = "SECUNDARIO"; break;
                endswitch
                ?>
                <td style="width:5%"><b>{{ $i }}.-</b></td>
                <td style="width:70%"><p>{{ $dx->nombre_dx }}</p></td>
                <td style="width:10%"><b>CIE:</b> {{ $dx->cod_dx }}</td>
                <td style="width:15%">{{ $categoria }}</td>
                <?php
                $i++;
                ?>
            </tr>
            @endif
            @endforeach
        </table>
        <table border="0">
            <tr>
                <td colspan="3"><b>DIAGNOSTICOS REITERATIVOS</b></td>
            </tr>
            @foreach($diagnosticos_evoluciones as $dx)
            @if($evolucion->id == $dx->id_evolucion && $dx->id_tipo_diagnostico == 3)
            <tr>
                <?php
                $i = 1;
                $categoria = "";
                switch($dx->id_categoria): 
                    case 1: $categoria = "PRINCIPAL"; break;
                    case 2: $categoria = "SECUNDARIO"; break;
                endswitch
                ?>
                <td style="width:5%"><b>{{ $i }}.-</b></td>
                <td style="width:70%"><p>{{ $dx->nombre_dx }}</p></td>
                <td style="width:10%"><b>CIE:</b> {{ $dx->cod_dx }}</td>
                <td style="width:15%">{{ $categoria }}</td>
                <?php
                $i++;
                ?>
            </tr>
            @endif
            @endforeach
        </table>

        <table border="0">
            <tr>
                <td colspan="4" class="subtitulo_2"><b>PROCEDIMIENTO</b></td>
            </tr>
            @foreach($procedimientos_evoluciones as $cpms)
            @if($evolucion->id == $cpms->id_evolucion)
            <tr>
                <?php
                $i = 1;
                ?>
                <td style="width:5%; vertical-align:top;"><b>{{ $i }}.-</b></td>
                <td style="padding-left:2px;"><p>{{ $cpms->descripcion_cpms }}</p></td>
                <td style="width:15%; vertical-align:top;"><b>CPMS:</b> {{ $cpms->cod_cpms }}</td>
                <td style="width:15%; vertical-align:top;"><b>Cantidad:</b> {{ $cpms->cantidad }}</td>
                <?php
                $i++;
                ?>
            </tr>
            @endif
            @endforeach
        </table>

        <table border="0" >
            <tr>
                <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
            </tr>
        </table>

        <table border="0" style="" >
            <!-- <tr>
                <td><b>Apellido Paterno</b></td>
                <td><b>Apellido Materno</b></td>
                <td><b>Nombres</b></td>
                <td style="width:15%"><b>N° Colegiatura</b></td>
            </tr>
            <tr>
                <td>{{ $evolucion->profesional_paterno }}</td>
                <td>{{ $evolucion->profesional_materno }}</td>
                <td>{{ $evolucion->profesional_nombre }}</td>
                <td>{{ $evolucion->profesional_colegiatura }}</td>
            </tr> -->
            <tr>
                <td><b>APELLIDOS Y NOMBRES:</b></td>
                <td colspan="3">{{ $evolucion->profesional_paterno }} {{ $evolucion->profesional_materno }} {{ $evolucion->profesional_nombre }}</td>
            </tr>
            <tr>
                <td style="width:23%; padding-top: 5px;"><b>PROFESIÓN:</b></td>
                <td style="">{{ $evolucion->profesional_profesion }}</td>
                <td style="width:17%"><b>N° COLEGIATURA:</b></td>
                <td style="width:10%">{{ $evolucion->profesional_colegiatura }}</td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>RECETAS VALE</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;" class="tbl_ordenes">
            @foreach ($recetas_evolucion as $receta)
                @if ($evolucion->id == $receta->id_evaluacion)
                    <tr>
                        <td colspan="6"><b>FARMACIA: </b>{{$receta->farmacia}}</td>
                        <td><b>N°: </b>{{$receta->nro_receta}}</td>
                    </tr>
                    <tr>
                        <th>N°</th>
                        <th>PRODUCTO</th>
                        <th>UNIDAD<br>MEDIDA</th>
                        <th>CANT.</th>
                        <th>DÍAS</th>
                        <th>VÍA<br>ADMINISTRACIÓN</th>
                        <th>POSOLOGÍA</th>
                    </tr>
                    <?php $i=1; ?>
                    @foreach ($receta_productos_evolucion as $row)
                        @if ($receta->id == $row->id_receta)
                        <tr>
                            <td>{{$i}}.-</td>
                            <td>{{$row->codigo}} - {{$row->nombre}}
                                <?php 
                                    if($row->codigo=='59075700'):
                                    echo "(".$row->cantidad_prescrita."L)";
                                    endif; 
                                ?>
                            </td>
                            <td style="text-align:center">{{$row->abreviatura}}</td>
                            <td style="text-align:center"><?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                echo $row->cantidad_prescrita/1000;
                                else:
                                echo $row->cantidad_prescrita;
                                endif; ?>
                            </td>
                            <td style="text-align:center">{{$row->tiempo_periodo}}</td>
                            <td style="text-align:center">{{$row->via}}</td>
                            <td>{{$row->posologia}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                @endif
            @endforeach
        </table>

        <table border="0" >
            <tr>
                <td class="subtitulo_2"><b>ORDEN DE LABORATORIO</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;" class="tbl_ordenes">
            @foreach ($ordenes_laboratorio_evolucion as $orden_lab)
                @if ($evolucion->id == $orden_lab->id_evaluacion)
                <tr>
                    <td colspan="4"></td>
                    <td><b>N°: </b>{{$orden_lab->id}}</td>
                </tr>
                <tr>
                    <th>N°</th>
                    <th>CÓDIGO</th>
                    <th>NOMBRE EXAMEN</th>
                    <th>OBSERVACIÓN</th>
                    <th>CANTIDAD</th>
                </tr>
                <?php $i=1; ?>
                @foreach ($orden_lab_examenes_evolucion as $examen)
                    @if ($orden_lab->id == $examen->id_orden)
                    <tr>
                        <td>{{$i}}.-</td>
                        <td>{{$examen->codigo}}</td>
                        <td>{{$examen->nombre}}</td>
                        <td>{{$examen->observacion}}</td>
                        <td>{{$examen->cantidad}}</td>
                    </tr>
                    <?php $i++; ?>
                    @endif
                @endforeach
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                @endif
            @endforeach
        </table>

        <table border="0" >
            <tr>
                <td class="subtitulo_2"><b>ORDEN DE IMAGENOLOGIA</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;" class="tbl_ordenes">
            @foreach ($ordenes_imagenologia_evolucion as $orden_img)
                @if ($evolucion->id == $orden_img->id_evaluacion)
                    <tr>
                        <td colspan="6"></td>
                        <td><b>N°: </b>{{$orden_img->id}}</td>
                    </tr>
                    <tr>
                        <th>N°</th>
                        <th>CÓDIGO</th>
                        <th>NOMBRE EXAMEN</th>
                        <th>COMPARATIVO</th>
                        <th>LADO</th>
                        <th>OBSERVACIÓN</th>
                        <th>CANTIDAD</th>
                    </tr>
                    <?php $i=1; ?>
                    @foreach ($orden_img_examenes_evolucion as $examen)
                        @if ($orden_img->id == $examen->id_orden)
                        <tr>
                            <td><b>{{$i}}.-</b></td>
                            <td>{{$examen->codigo}}</td>
                            <td>{{$examen->nombre}}</td>
                            <td>
                            <?php 
                            if($examen->comparativo_radio!=null):
                                if($examen->comparativo_radio==1):
                                    echo "SI";
                                else:
                                    echo "NO";
                                endif;
                            endif;
                            ?>
                            </td>
                            <td>
                            <?php 
                            if($examen->lado_radio != null):
                                if($examen->lado_radio == 1):
                                    echo "Izquierdo";
                                else:
                                    echo "Derecho";
                                endif;
                            endif;
                            ?>
                            </td>
                            <td>
                            <?php 
                            if($examen->observacion!='0'):
                                echo $examen->observacion;
                            endif;
                            ?>
                            </td>
                            <td>{{$examen->cantidad}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                @endif
            @endforeach
        </table>

        <table border="0" >
            <tr>
                <td class="subtitulo_2"><b>INTERCONSULTA</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;" class="tbl_ordenes">
            <?php $i=1; ?>
            @foreach ($interconsultas_evolucion as $interconsulta)
                @if ($evolucion->id == $interconsulta->id_evolucion)
                <tr>
                    <td colspan="2"><b>{{$i}}.-</b></td>
                </tr>
                <tr>
                    <td><b>SERVICIO: </b></td>
                    <td>{{$interconsulta->codigo_interconsulta}} - {{$interconsulta->servicio}}</th>
                </tr>
                <tr>
                    <td><b>MOTIVO: </b></td>
                    <td>{{$interconsulta->motivo_interconsulta}}</td>
                </tr>
                <tr>
                    <td><b>MEDICO: </b></td>
                    <td>{{$interconsulta->profesional_paterno}} {{$interconsulta->profesional_materno}} {{$interconsulta->profesional_nombre}}</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <?php $i++; ?>
                @endif
            @endforeach
        </table>

        <div style="position: absolute; bottom: 0; width: 100%;">
            <table border="0" class="tbl_evolucion_footer">
                <tr>
                    <td colspan="7" class="td_firma"><br><br><br><br></td>
                </tr>
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
                    <td style="width: 50px">
                        <b>Episodio</b>
                    </td>
                </tr>
                <tr>
                    <td style="height:22px !important;">{{$hospitalizacion->paterno_beneficiario}}</td>
                    <td>{{$hospitalizacion->materno_beneficiario}}</td>
                    <td>{{$hospitalizacion->nombre_beneficiario}}</td>
                    <td>{{$hospitalizacion->nro_historia}}</td>
                    <td>{{$evolucion->servicio_sala}}</td>
                    <td>{{$evolucion->cama}}</td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div style="page-break-after:always;"></div>
    @endforeach

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
    <table border="0">
        <tr>
            <td colspan="4" class="subtitulo_1"><b>V.- ALTA</b></td>
        </tr>
    </table>

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

    <table border="0">
        <tr>
            <td colspan="3" class="subtitulo_2"><b>DIAGNÓSTICO</b></td>
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

    <table>
        <tr>
            <td style="width:200px"><b>FECHA Y HORA DE EGRESO</b></td>
            <td>&nbsp;</td>
            <td style="width:150px"><b>TIPO DE EGRESO</b></td>
            <td style="width:150px"><b>CONDICION DE EGRESO</b></td>
        </tr>
        <tr>
            <td>{{ date("d/m/Y", strtotime($hospitalizacion->fecha_salida)) }} {{ date("H:i:s", strtotime($hospitalizacion->hora_egreso)) }}</b></td>
            <td></td>
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

    <table border="0" style="" >
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

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table id="tbl_hospitalizacion_footer">
            <tr>
                <td colspan="7" class="td_firma"><br><br><br><br></td>
            </tr>
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
                <td style="width: 50px">
                    <b>Episodio</b>
                </td>
            </tr>
            <tr>
                <td style="height:22px !important">{{$hospitalizacion->paterno_beneficiario}}</td>
                <td>{{$hospitalizacion->materno_beneficiario}}</td>
                <td>{{$hospitalizacion->nombre_beneficiario}}</td>
                <td>{{$hospitalizacion->nro_historia}}</td>
                <td>{{$hospitalizacion->servicio_sala_egreso}}</td>
                <td>{{$hospitalizacion->cama_egreso}}</td>
                <td></td>
            </tr>
        </table>
    </div>

    <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $pdf->text(560, 20, "$PAGE_NUM/$PAGE_COUNT", "Arial", 8);
        ');
    }
</script>
</body>

</html>