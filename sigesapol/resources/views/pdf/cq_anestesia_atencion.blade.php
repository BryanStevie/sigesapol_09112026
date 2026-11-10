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

    </style>
</head>

<body >
    <table style="margin:0px;padding:0px;padding-bottom:0px;">
        <tbody>
          <tr class="row_qr">
            <td width="70%"></td>
            {{-- <td width="30%" style="margin-top:0px;padding-top:0px;">
              <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
              <span style="font-size: 3px">
              </span>
            </td> --}}
            <td width="30%" style="text-align: center; vertical-align: top; padding: 0; margin: 0;">
              <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
              <span class="span_url_descarga" style="font-size: 3px; display: block; margin-top: 5px;">
                  {{ $url_descarga }}
              </span>
          </td>
          </tr>
          {{-- <tr>
            <td width="70%"></td>
            <td width="30%" style="margin-top:0px;padding-top:0px;">
              <span style="font-size: 4.5px">
              </span>
            </td>
          </tr> --}}
        </tbody>
      </table>
    
      <table style="margin:0px;padding:0px;padding-bottom:0px;">
        <tbody>
          <tr>
            <td width="100%" style="">
              <h3 style="margin: 0; padding: 0; margin-bottom: 6px; text-align: center;">
                <span style="margin: 0;color: rgb(196, 54, 54)">HOSPITALIZACION</span> - CUPS: <span style="margin: 0; font-weight: 100;">{{$prestacion->cups_pnp}}</span>
              </h3>
            </td>
          </tr>
          <tr>
            <td width="100%" style="">
              <h3 style="margin: 0; padding: 0; margin-bottom: 6px; text-align: center;">
                PRESTACION MEDICA Nº <?php echo $prestacion->id ?>
              </h3>
            </td>
          </tr>
        </tbody>
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
            <td colspan="5" class="subtitulo_2"><b>1.- FILIACIÓN</b></td>
        </tr>
        <tr>
            <td colspan="2">Apellidos y Nombres: <b>{{$asegurado->paterno}} {{$asegurado->materno}} {{$asegurado->nombre}}</b></td>
            <td></td>
            <td><b>DNI</b></td>
            <td>{{$asegurado->nro_doc_ident}}</td>
        </tr>
        <tr>
            <?php 
                $fechaNacimientoObj = DateTime::createFromFormat('d/m/Y', $asegurado->fecha_nac);
                $fechaActual = new DateTime();
                $edad = $fechaNacimientoObj->diff($fechaActual)->y;
            ?>
            <td>Edad: <b>{{$edad}}</b></td>
            <td>Sexo: <b>{{$asegurado->sexo}}</b></td>
            <td></td>
            <td><b>CIP</b></td>
            <td>@if($persona_datos_adicionales != null) {{$persona_datos_adicionales->cip}} @endif</td>
        </tr>
        <tr>
            <td>Raza: <b>{{$asegurado->etnia}}</b></td>
            <td>Estado civil: <b>{{$asegurado->estado_civil}}</b></td>
            <td></td>
            <td><b>HC</b></td>
            <td>{{$asegurado_historia->nro_historia}}</td>
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
            <td colspan="2">Apellidos y Nombres del titular: <b>@if($titular != null){{$titular->paterno}} {{$titular->materno}} {{$titular->nombre}}@endif</b></td>
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
            <td colspan="2">Parentesco con el paciente: <b>{{$asegurado->tipo_beneficiario}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de Historia Clínica: <b>{{date("d/m/Y H:i a", strtotime($prestacion->created_at))}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Código SITEDS: <b>{{--$hospitalizacion->cod_siteds_susalud--}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">CUPS: <b><?php echo $prestacion->cups_pnp; ?></b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </table>

    <table style="width: 100%;" border="0" class="tbl_diagnostico" >
        <tr>
            <td colspan="5" class="subtitulo_1"><b>DIAGNÓSTICO</b></td>
        </tr>
        <tr>
            <th></th>
            <th>CIE</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Prioridad</th>
        </tr>
        <?php
        $i = 1;
        ?>
        @foreach($diagnosticos as $dx)
        <tr>
            <?php
            $tipo = "";
            switch($dx->id_tipo_diagnostico): 
                case 1: $tipo = "PRESUNTIVO"; break;
                case 2: $tipo = "DEFINITIVO"; break;
                case 3: $tipo = "REITERATIVO"; break;
            endswitch;
            $categoria = "";
            switch($dx->id_categoria): 
                case 1: $categoria = "PRINCIPAL"; break;
                case 2: $categoria = "SECUNDARIO"; break;
            endswitch;
            ?>
            <td style="width:5%; border-bottom: 1px solid gray;"><b>{{ $i }}.-</b></td>
            <td style="width:10%; border-bottom: 1px solid gray;">{{ $dx->cod_dx }}</td>
            <td style="width:55%; border-bottom: 1px solid gray;"><p>{{ $dx->nombre_dx }}</p></td>
            <td style="width:15%; border-bottom: 1px solid gray;">{{ $tipo }}</td>
            <td style="width:15%; border-bottom: 1px solid gray;">{{ $categoria }}</td>
            <?php
            $i++;
            ?>
        </tr>
        @endforeach
    </table>

    <table border="0" class="tbl_diagnostico">
        <tr>
            <td colspan="4" class="subtitulo_2"><b>PROCEDIMIENTO</b></td>
        </tr>
        <?php
        $i = 1;
        ?>
        @foreach($procedimientos as $cpms)
        <tr>
            <td style="width:5%; border-bottom: 1px solid gray;"><b>{{ $i }}.-</b></td>
            <td style="width:10%; border-bottom: 1px solid gray;">{{ $cpms->cod_cpms }}</td>
            <td style="width:70%; border-bottom: 1px solid gray;"><p>{{ $cpms->descripcion_cpms }}</p></td>
            <td style="width:15%; border-bottom: 1px solid gray;"><b>Cantidad:</b> {{ $cpms->cantidad }}</td>
            <?php
            $i++;
            ?>
        </tr>
        @endforeach
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
            <td colspan="5" class="subtitulo_2"><b>2.- PROGRAMACIÓN</b></td>
        </tr>
        <tr>
            <td>Fecha de Intervención : <b>{{$anestesia_quirurgica->fecha_intervencion}}</b></td>
            <td>Hora de Inicio : <b>{{$anestesia_quirurgica->hora_inicio_intervencion}}</b></td>
            <td colspan="3">Hora de Término : <b>{{$anestesia_quirurgica->hora_fin_intervencion}}</b></td>
        </tr>
        <tr>
            <td>Sala de Intervención : <b>{{$sala_intervencion->nombre}}</b></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="5" class="subtitulo_2"><b>3.- TIPO ANESTESIA</b></td>
        </tr>
        <tr>
            <td colspan="5">
                @foreach ($tipoAnestesiacq as $tacq)
                    <b>{{$tacq->descripcion}}</b><br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td colspan="5" class="subtitulo_2"><b>4.- ANESTESIOLOGOS</b></td>
        </tr>
        <tr>
            <td colspan="5">Profesionales : </td>
        </tr>
        <tr>
            <td colspan="5">
                @foreach ($anetesiologos as $a)
                    <b>{{$a->nombre}}, {{$a->paterno}} {{$a->materno}}</b> - <b>{{$a->descripcion}}</b><br>
                @endforeach
            </td>
        </tr>
    </table>

    <!-- SE QUITO LA SECCION -->

    <table border="0" >
        <tr>
            <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
        </tr>
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

</body>

</html>