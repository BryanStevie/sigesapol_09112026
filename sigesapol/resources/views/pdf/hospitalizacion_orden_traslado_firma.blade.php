<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HOSPITALIZACION - ORDEN TRASLADO</title>
    <style>
        .title {
            text-align: center; 
            font-size: 15px;
            font-weight: bold;
        }
        body {
            color: #001028;
            background: #FFFFFF; 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 8px; 
            padding: 0px; 
        }
        table {
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
            text-align: left;
        }
    </style>
</head>
<body>
    <table  width="100%" >
        <tr style="text-align: right">
            <td width="60%">
            </td>
            <td width="5%">
                <div><img src="images/logo_sanidad.png" width="40rem"/></div>
            </td>
            <td width="35%" style="text-align: right">
                <div style="margin-top: -0.5%">
                    <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0px; font-weight: bold; text-transform: uppercase;color: red;"><?php echo $nombre_establecimiento ?></span>
                    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0px; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO DE HOSPITALIZACIÓN</span>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <br>
    <p class="title">ORDEN DE TRANSFERENCIA</p>

    <span style="font-weight: bold">I.- DATOS DEL PACIENTE</span>
    <table  width="100%" style="border-collapse: collapse; margin-top: 5px">
        <tr>
            <td width="50%"><b>NOMBRE DEL PACIENTE:  </b>{{$prestacion->asegurado_paterno}} {{$prestacion->asegurado_materno}} {{$prestacion->asegurado_nombre}}</td>
        </tr>
        <tr>
            <td width="50%"><b>DNI: </b>{{$prestacion->nro_doc_ident_asegurado}}</td>
            <td width="50%"><b>HISTORIA CLÍNICA: </b>{{$prestacion->nro_historia}}</td>
        </tr>
        {{-- <tr>
            <td width="50%"><b>Parentesco: </b>{{$prestacion->tipo_beneficiario_descripcion}}</td>
            <td width="50%"><b>H.C. Gral: </b>{{$prestacion->nro_historia}}</td>
        </tr> --}}
    </table>

    {{-- <span style="display: block; height: 5px;"></span> 
    <div style="border-top: 1px solid #060606;"></div>
    <span style="display: block; height: 5px;"></span>  --}}

    {{-- <span style="font-weight: bold">II.- DATOS DEL TRASLADO</span>
    <table width="100%" style="border-collapse: collapse; margin-top: 5px">
        <tr>
            <td colspan="3"><b>ORIGEN</b></td>
        </tr>
        <tr>
            <td colspan="2"><b>UPSS A CARGO: </b>{{$traslado->upss_encargado_origen_codigo}} - {{$traslado->upss_encargado_origen_descripcion}}</td>
            <td width="40%"><b>SERVICIO A CARGO: </b>{{$traslado->servicio_encargado_origen_nombre}}</td>
        </tr>
        <tr>
            <td width="40%"><b>SERVICIO FÍSICO: </b>{{$traslado->servicio_origen_nombre}}</td>
            <td width="20%"><b>CAMA: </b>{{$traslado->cama_origen_nombre}}</td>
            <td width="40%"><b>FECHA Y HORA DE INGRESO: </b>{{$lastTraslado->fecha_atencion}}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3"><b>DESTINO</b></td>
        </tr>
        <tr>
            <td colspan="2"><b>UPSS A CARGO: </b>{{$traslado->upss_encargado_destino_codigo}} - {{$traslado->upss_encargado_destino_descripcion}}</td>
            <td width="40%"><b>SERVICIO A CARGO: </b>{{$traslado->servicio_encargado_destino_nombre}}</td>
        </tr>
        <tr>
            <td width="40%"><b>SERVICIO FÍSICO: </b>{{$traslado->servicio_destino_nombre}}</td>
            <td width="20%"><b>CAMA: </b>{{$traslado->cama_destino_nombre}}</td>
            <td width="40%"><b>FECHA Y HORA DE TRANSFERENCIA:  </b> {{$traslado->fecha_cambio_formateado}} {{$traslado->hora_cambio}}</td>
        </tr>
    </table> --}}

    <span style="display: block; height: 5px;"></span> 
    <div style="border-top: 1px solid #060606;"></div>
    <span style="display: block; height: 5px;"></span> 

    <span style="font-weight: bold">II.- DIAGNOSTICO DE INGRESO</span>
    <table width="100%" style="border-collapse: collapse; margin-top: 5px">
        <tr>
            <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">CÓDIGO</th>
            <th width="90%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">NOMBRE</th>
        </tr>
        @if (isset($diagnosticos_ingreso) && is_array($diagnosticos_ingreso) && count($diagnosticos_ingreso) > 0)
            @foreach ($diagnosticos_ingreso as $diagnostico_ingreso)
                <tr>
                    <td style="text-align: left;border: 1px solid #000000;">{{$diagnostico_ingreso->codigo}}</td>
                    <td style="text-align: left;border: 1px solid #000000;">{{$diagnostico_ingreso->nombre}}</td>
                </tr>
            @endforeach
        @endif
    </table>

    <span style="display: block; height: 8px;"></span> 
    <div style="border-top: 1px solid #060606;"></div>
    <span style="display: block; height: 10px;"></span> 

    <span style="font-weight: bold">III.- DIAGNOSTICO DE TRANSFERENCIA</span>
    <table width="100%" style="border-collapse: collapse; margin-top: 5px">
        <tr>
            <th width="10%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">CÓDIGO</th>
            <th width="90%" style="text-align: left; border: 1px solid black; font-weight: bold; color: #000000;">NOMBRE</th>
        </tr>
        @if (isset($diagnosticos_traslado) && is_array($diagnosticos_traslado) && count($diagnosticos_traslado) > 0)
            @foreach ($diagnosticos_traslado as $diagnostico_traslado)
                <tr>
                    <td style="text-align: left;border: 1px solid #000000;">{{$diagnostico_traslado->codigo}}</td>
                    <td style="text-align: left;border: 1px solid #000000;">{{$diagnostico_traslado->nombre}}</td>
                </tr>
            @endforeach
        @endif
    </table>

    <span style="display: block; height: 5px;"></span> 
    <div style="border-top: 1px solid #060606;"></div>
    <span style="display: block; height: 5px;"></span> 

    <span style="font-weight: bold">IV.- DATOS DEL TRASLADO</span>
    <table width="100%" style="border-collapse: collapse; margin-top: 5px">
        <tr>
            {{-- <td width="40%"><b>FECHA Y HORA DE INGRESO AL SERVICIO: </b>{{$prestacion->fecha_atencion}}</td> --}}
            <td width="40%"><b>FECHA Y HORA DE INGRESO AL SERVICIO: </b>
              {{ \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $prestacion->fecha_atencion)->format('d/m/Y H:i') }}
            </td>
        </tr>
        <tr>
            {{-- <td width="40%"><b>FECHA Y HORA DE TRANSFERENCIA:  </b> {{$traslado->fecha_cambio_formateado}} {{$traslado->hora_cambio}}</td> --}}
            <td width="40%"><b>FECHA Y HORA DE TRANSFERENCIA: </b>
              {{ \Carbon\Carbon::parse($traslado->fecha_cambio_formateado)->format('d/m/Y') }} {{$traslado->hora_cambio}}
            </td>
        </tr>
    </table>

    <span style="display: block; height: 5px;"></span> 
    <div style="border-top: 1px solid #060606;"></div>
    <span style="display: block; height: 5px;"></span> 

    <span style="font-weight: bold">V.- SALE DEL SERVICIO DE</span>
    <table width="100%" style="border-collapse: collapse; margin-top: 5px">
        <tr>
            <td width="40%"><b>SERVICIO A CARGO: </b>{{$traslado->upss_encargado_origen_descripcion}}</td>
        </tr>
        <tr>
            <td width="40%"><b>SERVICIO FÍSICO: </b>{{$traslado->servicio_origen_nombre}}</td>
        </tr>
    </table>

    <span style="display: block; height: 5px;"></span> 
    <div style="border-top: 1px solid #060606;"></div>
    <span style="display: block; height: 5px;"></span> 

    <span style="font-weight: bold">VI.- VA AL SERVICIO DE</span>
    <table width="100%" style="border-collapse: collapse; margin-top: 5px">
        <tr>
            <td width="40%"><b>SERVICIO A CARGO: </b>{{$traslado->upss_encargado_destino_descripcion}}</td>
        </tr>
        <tr>
            <td width="40%"><b>SERVICIO FÍSICO: </b>{{$traslado->servicio_destino_nombre}}</td>
        </tr>
    </table>
    
    <span style="display: block; height: 40px;"></span> 
    
    
</body>
</html>