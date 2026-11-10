<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
    <title>Ficha de Información del Personal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header-image {
            width: 100%;
            margin-bottom: 10px;
            text-align: center;
        }
        .header-image img {
            max-width: 100%;
            height: auto;
        }
        .subtitle {
            text-align: center;
            font-style: italic;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
        }
        .section-title {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #000;
            font-size: 10px;
        }
        .field-label {
            width: 35%;
            padding: 3px 8px;
            border: 1px solid #000;
            font-weight: bold;
        }
        .field-value {
            width: 65%;
            padding: 3px 8px;
            border: 1px solid #000;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }
        .history-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 3px;
            border: 1px solid #000;
            font-size: 10px;
        }
        .history-table td {
            padding: 5px;
            border: 1px solid #000;
            font-size: 10px;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .footer-content {
            display: table;
            width: 100%;
        }
        .footer-left {
            text-align: left;
            width: 33%;
        }
        .footer-center {
            text-align: center;
            width: 34%;
        }
        .footer-right {
            text-align: right;
            width: 33%;
        }
    </style>
</head>
<body>
    <div class="header-image">
      <img src="{{ public_path('images/Logo-Hospital.png') }}" alt="Cabecera">
    </div>

    <div class="subtitle">
      "Año de la Recuperación y Consolidación de la Economía Peruana"
    </div>

    <div class="title">
      <u>CONSTANCIA DE TRABAJO</u>
    </div>

    <div class="footer">
        <div class="footer-content">
          <table>
            <tr>
              <td class="footer-left">
                DNI de Registrado: {{ $medico->dni }}
              </td>
              <td class="footer-center">
                Usuario de Impresión: {{ $usuario_dni }}
              </td>
              <td class="footer-right">
                Fecha y Hora de Impresión: {{ $fecha_hora_impresion }}
              </td>
            </tr>
            <tr>
              <td>Fecha de registro Médico: {{ $fechaCreacion }}</td>
              <td></td>
              <td></td>
            </tr>
          </table>
        </div>
    </div>
</body>
</html>