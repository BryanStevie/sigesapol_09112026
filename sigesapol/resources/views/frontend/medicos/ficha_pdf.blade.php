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
      <u>FICHA DE INFORMACION DEL PERSONAL QUE LABORA EN EL "HN PNP LNS"</u>
    </div>

    <table>
        <tr>
            <td colspan="2" class="section-title">I. INFORMACION PERSONAL</td>
        </tr>
        <tr>
            <td class="field-label">Condición</td>
            <td class="field-value">{{ $medico->condicion }}</td>
        </tr>
        <tr>
            <td class="field-label">Grado</td>
            <td class="field-value">{{ $medico->grado ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">Tipo y N° de documento</td>
            <td class="field-value">{{ $medico->tipo_documento }} {{ $medico->dni }}</td>
        </tr>
        <tr>
            <td class="field-label">N° de CIP</td>
            <td class="field-value">{{ $medico->cip ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">Nombres y Apellidos</td>
            <td class="field-value">{{ $medico->nombre_completo }}</td>
        </tr>
        <tr>
            <td class="field-label">Fecha de nacimiento</td>
            <td class="field-value">{{ date('d/m/Y', strtotime($medico->fecha_nacimiento)) }}</td>
        </tr>
        <tr>
            <td class="field-label">Edad</td>
            <td class="field-value">{{ $medico->edad_detallada }}</td>
        </tr>
        <tr>
            <td class="field-label">Sexo</td>
            <td class="field-value">{{ $medico->sexo == 'M' ? 'MASCULINO' : 'FEMENINO' }}</td>
        </tr>
        <tr>
            <td class="field-label">Estado civil</td>
            <td class="field-value">{{ $medico->estado_civil ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">N° de Hijos</td>
            <td class="field-value">{{ $medico->nro_hijos ?: '-' }}</td>
        </tr>
        <tr>
          <td class="field-label">N° de celular</td>
          <td class="field-value">{{ $medico->celular_censurado }}</td>
        </tr>
        <tr>
          <td class="field-label">N° de contacto de emergencia</td>
          <td class="field-value">{{ $medico->cel_emergencia_censurado }}</td>
        </tr>
        <tr>
          <td class="field-label">Correo electrónico</td>
          <td class="field-value">{{ $medico->email_censurado }}</td>
        </tr>
        <tr>
          <td class="field-label">Correo institucional</td>
          <td class="field-value">{{ $medico->email_institucional_censurado }}</td>
        </tr>
        <tr>
          <td class="field-label">Domicilio</td>
          <td class="field-value">{{ $medico->domicilio_censurado }}</td>
        </tr>
        <tr>
            <td class="field-label">Departamento/Provincia/Distrito</td>
            <td class="field-value">{{ $medico->departamento }} / {{ $medico->provincia }} / {{ $medico->distrito }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2" class="section-title">II. INFORMACION LABORAL</td>
        </tr>
        <tr>
            <td class="field-label">Grupo de trabajo</td>
            <td class="field-value">{{ $medico->grupo_trabajo }}</td>
        </tr>
        <tr>
            <td class="field-label">F. inicio y Fin de labores</td>
            <td class="field-value">
                {{ $medico->fecha_inicio ? date('d/m/Y', strtotime($medico->fecha_inicio)) : '-' }} - 
                {{ $medico->fecha_cese ? date('d/m/Y', strtotime($medico->fecha_cese)) : '-' }}
            </td>
        </tr>
        <tr>
            <td class="field-label">Situacion laboral</td>
            <td class="field-value">{{ $medico->situacion_laboral ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">Condicion laboral</td>
            <td class="field-value">{{ $medico->condicion_laboral ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">Profesion</td>
            <td class="field-value">{{ $medico->profesion ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">Especialidad</td>
            <td class="field-value">{{ $medico->especializacion ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">Colegio Profesional</td>
            <td class="field-value">{{ $medico->colegio_profesional ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">N° CMP / N° RNE</td>
            <td class="field-value">{{ $medico->colegiatura ?: '-' }} / {{ $medico->rne ?: '-' }}</td>
        </tr>
        <tr>
            <td class="field-label">Estado de colegiatura</td>
            <td class="field-value">{{ $medico->estado_colegiatura }}</td>
        </tr>
        <tr>
            <td class="field-label">F. Inicio y Fin de habilidad</td>
            <td class="field-value">
                {{ $medico->fecha_inicio_habilidad ? date('d/m/Y', strtotime($medico->fecha_inicio_habilidad)) : '-' }} - 
                {{ $medico->fecha_termino_habilidad ? date('d/m/Y', strtotime($medico->fecha_termino_habilidad)) : '-' }}
            </td>
        </tr>
        <tr>
            <td class="field-label">Cargo</td>
            <td class="field-value">{{ $medico->cargo ?: '-' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="section-title">III. HISTORIAL DE MOVIMIENTO</td>
        </tr>
    </table>
    
    <table class="history-table" style="margin-bottom: 5px;">
        <thead>
            <tr>
                <th>Inicio</th>
                <th>F. Fin</th>
                <th>Establecimiento</th>
                <th>Subunidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $medico->fecha_inicio ? date('d/m/Y', strtotime($medico->fecha_inicio)) : '-' }}</td>
                <td>{{ $medico->fecha_cese ? date('d/m/Y', strtotime($medico->fecha_cese)) : '-' }}</td>
                <td>{{ $medico->establecimiento ?: '-' }}</td>
                <td>{{ $medico->subunidad ?: '-' }}</td>
            </tr>
        </tbody>
    </table>
    
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