<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Seguimiento de Expediente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .logo {
            position: absolute;
            left: 0px;
            top: 20px;
            width: 130px;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
            margin-top: 40px;
        }

        .row {
            width: 100%;
            margin-bottom: 10px;
        }

        .col-33 {
            width: 32%;
            display: inline-block;
            vertical-align: top;
        }

        .col-50 {
            width: 49%;
            display: inline-block;
        }

        .label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        td {
            padding: 5px;
        }

        .table-header th {
            font-size: 10px;
        }

        .empty-box {
            height: 100px;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
        }

        .footer div {
            width: 32%;
            display: inline-block;
        }
    </style>
</head>

<body>

    <!-- Logo -->
    <img src="{{ public_path('images/logo_pnp_nom.png') }}" class="logo">

    <!-- Header -->
    <div class="header">
        <div class="title">SEGUIMIENTO DE EXPEDIENTE</div>
    </div>

    <!-- Datos principales -->
    <div class="row">
        <div class="col-33"><span class="label">Nro. Requerimiento:</span> {{ $requerimiento['numero_requerimiento'] }}
        </div>
        <div class="col-33"><span class="label">Nro. HT / SGD:</span> {{ $requerimiento['numero_sgd_ht'] }}</div>
        <div class="col-33"><span class="label">Nro. Pedido SIGA:</span> {{ $requerimiento['numero_pedido_siga'] }}
        </div>
    </div>

    <div class="row">
        <div class="col-33"><span class="label">Nro. Oficio:</span> {{ $requerimiento['numero_oficio'] }}</div>
        <div class="col-33"><span class="label">Nro. Informe:</span> {{ $requerimiento['numero_informe'] }}</div>
    </div>

    <div class="row">
        <div class="col-50"><span class="label">Tipo de requerimiento:</span>
            {{ $tipos_requerimiento[$requerimiento['id_tipo_requerimiento']] }}</div>
        <div class="col-50">
            <span class="label">Prioridad:</span>
            <span style="color: {{ $prioridades_color[$requerimiento['prioridad']] }}; font-weight: bold;">
                {{ $prioridades[$requerimiento['prioridad']] }}
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-50"><span class="label">Procedimiento de Selección:</span>
            {{ $tipos_seleccion[$requerimiento['id_procedimiento_seleccion']] }}
        </div>
        <div class="col-50"><span class="label">Tipo de Proceso:</span>
            {{ $requerimiento['id_tipo_proceso'] != null ? $tipos_proceso[$requerimiento['id_tipo_proceso']] : 'Sin registro.' }}
        </div>
    </div>

    <div class="row">
        <div class="col-50"><span class="label">Denominación del Requerimiento:</span>
            {{ $requerimiento['denominacion'] }}</div>
        <div class="col-50"><span class="label">Tipo de Producto:</span> {{ $requerimiento['tp_nombre'] }}</div>
    </div>

    <!-- Tabla productos -->
    <table>
        <thead class="table-header">
            <tr>
                <th>N°</th>
                <th>COD. PROD.</th>
                <th>DESCRIPCIÓN</th>
                <th>UNID. MED.</th>
                <th>N° ENTREGAS</th>
                <th>CANTIDAD TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles_consolidado as $i => $item)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td style="text-align: center;">{{ $item['codigo_producto'] }}</td>
                    <td style="text-align: center;">{{ $item['descripcion_producto'] }}</td>
                    <td style="text-align: center;">{{ $item['unidad_medida'] }}</td>
                    <td style="text-align: center;">{{ $item['total_entregas'] }}</td>
                    <td style="text-align: center;">{{ $item['cantidad_total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tabla seguimiento -->
    <table style="width:100%; border-collapse: collapse;">
        <thead class="table-header">
            <tr>
                <th>N°</th>
                <th>FECHA INICIO</th>
                <th>FECHA FIN</th>
                <th>UBICACION</th>
                <th>RESPONSABLE ENVIO</th>
                <th>ETAPA</th>
                <th>ESTADO</th>
                <th>OBS.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movimientos_requerimiento as $i => $movimiento)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td style="text-align: center;">{{ $movimiento['fecha_ingreso'] }}</td>
                    <td style="text-align: center;">
                        @if ($movimiento['fecha_salida'] != null)
                            {{ $movimiento['fecha_salida'] }}
                        @else
                            <span style="font-style: italic;">
                                {{ 'Pendiente' }}
                            </span>
                        @endif
                      <td style="text-align: center;">{{ $movimiento['ubicacion']['nombre'] }}</td> 
                    <td style="text-align: center;">
                        {{ $movimiento['responsable_nombres'] . ' ' . $movimiento['responsable_paterno'] . ' ' . $movimiento['responsable_materno'] }}
                    </td>
                   <td style="text-align: center;">{{ $movimiento['etapa']['nombre'] }}</td> 
                    <td style="text-align: center;">
                        <span style="font-weight: bold;">
                             {{ $movimiento['estado']['nombre'] }}
                        </span>
                    </td>
                    <td
                        style="
                            text-align: center;
                            word-wrap: break-word;
                            overflow-wrap: break-word;
                            word-break: break-word;
                            white-space: normal;
                            max-width: 180px;
                            ">
                        {{ $movimiento['observacion'] }}
                        @if ($i == count($movimientos_requerimiento) - 1 && $movimiento['estado_int_mov'])
                            <br>

                            <span style="color:red;
                        font-weight:bold;">
                                OBSERVADO:
                            </span>

                            {{ $movimiento['ultima_observacion'] }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        {{-- <div>USUARIO DE IMPRESIÓN: {{ $requerimiento['usuario'] }}</div>
        <div>FECHA DE IMPRESIÓN: {{ date('d/m/Y') }}</div>
        <div>HORA DE IMPRESIÓN: {{ date('H:i') }}</div> --}}
    </div>

</body>

</html>
