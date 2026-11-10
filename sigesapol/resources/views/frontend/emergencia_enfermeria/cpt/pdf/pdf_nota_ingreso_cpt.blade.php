<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>REGISTRO CPT/CPMS</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
        table {
            border-collapse: collapse;
        }

        @page {
            margin: 0.5in 1in 1in 1in;
            /* Ajusta los márgenes según tus necesidades */
        }

        footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 5px;
            padding-bottom: 20px;
        }

        .content {
            margin-bottom: 1in;
            /* Asegura que el contenido no se sobreponga al footer */
        }

        td {
            height: 15px;
            font-size: 11.5px;
        }

        th {
            font-size: 11.5px;
            text-align: left;
            /border: 1px black solid;/ height: 15px;
            padding: 1px
        }

        td {
            border-top-width: 1px;
            border-bottom-width: 1px;
        }
    </style>
</head>

<body style="font-family: arial;padding:-30px;font-family:Arial, Helvetica, sans-serif;">
    <div class="content">
        <table style="margin:0px;padding:0px;padding-bottom:-10px;">
            <tbody>
                <tr>
                    <td colspan="2" class="" width="10%">
                        <img src="images/logo_sanidad.png" width="40rem" />
                    </td>
                    <td colspan="2" class="" width="80%">
                        <span
                            style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">{{ $nombre_establecimiento }}</span>
                        <span
                            style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">DEPARTAMENTO
                            DE ENFERMERIA</span>
                    </td>
                    <td colspan="2" width="50%">
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="margin:0px;padding-bottom:-20px;">
            <tbody>
                <tr>
                    <td colspan="2" width="50%">
                        <span
                            style="margin: 0; font-weight: bold; font-size: 20px !important; padding-bottom: -20px !important;">
                            EVOLUCIÓN DE ENFERMERÍA
                        </span><br>
                        <span style="margin: 0; font-weight: bold; font-size: 15px !important;">
                            {{ str_pad($cpt_cabecera[0]->id, 8, '0', STR_PAD_LEFT) }}
                        </span>
                        <br>
                        <span style="margin: 0; font-weight: bold; font-size: 15px !important;">
                            CUPS: <span
                                style="font-size: 10px; font-weight: 200; padding: 0; margin: 0;">{{ str_pad($codigo_cups, 8, '0', STR_PAD_LEFT) }}</span>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="margin:0px;padding:0px;padding-bottom:-5px;">
            <tbody>
                <tr>
                    <td colspan="2" width="50%" style="vertical-align:bottom;">
                    </td>
                    <td colspan="2" width="50%">
                    </td>
                    <td colspan="2" width="40%">
                        <div style="margin:0px;padding:0px;">
                            <table style="margin:0px;padding:0px;">
                                <tbody>
                                    <tr>
                                        <td width="50%" style="text-align:left;"><span
                                                style="margin: 0; font-size: 1rem; font-weight: bold;">H.C:</span></td>
                                        <td width="70%" style="text-align:left;"><span
                                                style="margin: 0; font-size: 1rem; font-weight: bold;">
                                                <b>{{ $datos_paciente->nro_historia }}</b>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td width="50%" style="text-align:left;"><span
                                                style="margin: 0; font-size: 1rem; font-weight: bold;">H.C.A:</td>
                                        <td width="70%" style="text-align:left;"><span
                                                style="margin: 0; font-size: 1rem; font-weight: bold;">
                                                <b>{{ $datos_paciente->nro_doc_ident }}</b>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="150%" style="text-align:left;"><span
                                                style="margin: 0; font-size:1.0rem; font-weight: bold;">
                                                <?php
                                    if($id_tipo_atencion==2){
                                    ?>
                                                N° EMERGENCIA:
                                                <?php } ?>
                                                <?php
                                    if($id_tipo_atencion==3){
                                    ?>
                                                N° HOSPITALIZACIÓN:
                                                <?php } ?>
                                            </span></td>
                                        <td width="70%" style="text-align:left;"> <b style="font-size: 0.9rem;">
                                                {{ str_pad($cpt_cabecera[0]->id_ext_enfermeria, 8, '0', STR_PAD_LEFT) }}
                                            </b></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <b style="font-size: 12px !important;">DATOS DEL PACIENTE</b>
        <table style="margin:0px;padding-bottom:-6px;">
            <tr>
                <td width="60%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        NOMBRES Y APELLIDOS:
                        <b>{{ $datos_paciente->nombre . ' ' . $datos_paciente->paterno . ' ' . $datos_paciente->materno }}</b>
                    </span>
                </td>
                <td width="40%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        DNI:
                        <b>{{ $datos_paciente->nro_doc_ident }}</b>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="60%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        F.NACIMIENTO:
                        <b>{{ $datos_paciente->fecha_nacimiento }}</b>
                    </span>
                </td>
                <td width="40%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        PARENTESGO:
                        <b> {{ $datos_paciente->parentesgo }} </b>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="60%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        SEXO:
                        <b> {{ $datos_paciente->sexo }} </b>

                        EDAD:
                        <b> {{ $datos_paciente->edad_corta }} </b>
                    </span>
                </td>
                <td width="40%" style="vertical-align:top;">
                    <span
                        style="display: block; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        TIPO DE SANGRE:
                        <b style=" font-size: 0.9rem !important;"> {{ $datos_paciente->tipo_sangre }} </b>
                    </span>
                </td>
            </tr>
        </table>
        <hr>


        <b style="font-size: 12px !important;">DATOS DE LA ATENCIÓN</b>
        <table style="margin:0px;padding-bottom:-10px;padding-top:-3px;">
            <tr>
                <td width="50%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        TIPO DE ATENCIÓN:
                        <b> {{ $cpt_cabecera[0]->descripcion_tipo_atenciones }}</b>
                    </span>
                </td>
                <td width="50%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        TURNO:
                        <b>{{ $cpt_cabecera[0]->turno }}</b>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="50%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: right; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        UBICACION:
                        <b>
                            @if ($id_tipo_atencion == 2)
                                {{ '' }}
                            @elseif($id_tipo_atencion == 3)
                                {{ $datos_atencion->nombre_cuarto }} - {{ $datos_atencion->nombre_cama }}
                            @endif
                        </b>
                    </span>
                </td>
                <td width="50%" style="vertical-align:top;">
                    <span></span>
                </td>
            </tr>
            <tr>
                <td width="50%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        FECHA DE ATENCIÓN:
                        <b>{{ $cpt_cabecera[0]->fecha_cpt }}</b>
                    </span>
                </td>
                <td width="50%" style="vertical-align:top;">
                    <span
                        style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-transform: uppercase;">
                        HORA DE ATENCIÓN:
                        <b>{{ $cpt_cabecera[0]->hora_cpt }}</b>
                    </span>
                </td>
            </tr>
        </table>
        <br>
        <hr>
        <table id="" style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <thead>
                <tr>
                    <th style="width: 40%; border: 1px solid #000; padding: 4px;">Procedimiento</th>
                    <th style="width: 40%; border: 1px solid #000; padding: 4px;">Diagnósticos</th>
                    <th style="width: 20%; border: 1px solid #000; padding: 4px;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cpt_cpms as $item)
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold;">
                            {{ $item->nombre_procedimiento }}
                        </td>
                        <td style="border: 1px solid #000; padding: 4px;">
                            {{ $item->nombre_diagnostico }}
                        </td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: center;">
                            {{ $item->cantidad }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <footer>
        <table>
            <tr style="font-size: 6px !important;">
                <td style="text-align: left">
                    <b style=" font-size: 6px !important;font-weight: 200;">PACIENTE: </b>
                    <b style="text-align: right; padding-right: 10px;">

                        {{ $datos_paciente->nro_doc_ident }} -
                        <?php echo $datos_paciente->paterno . ' ' . $datos_paciente->materno . ' ' . $datos_paciente->nombre; ?>
                    </b>
                    <br>

                    <i><b>CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE EVOLUCIÓN DE ENFERMERÍA</b></i>
                </td>
                <td style="text-align: right; padding-right: 10px;">
                    USUARIO RESPOSABLE:
                    <b> {{ $cpt_cabecera[0]->nombre_usuario }}
                        {{ $cpt_cabecera[0]->paterno_usuario }}
                        {{ $cpt_cabecera[0]->materno_usuario }}</b> <br>
                    FECHA DE IMPRESIÓN: <b>{{ $fechaActual }}</b> <br>
                    HORA DE IMPRESIÓN: <b>{{ $horaActual }}</b>
                </td>
            </tr>
        </table>

    </footer>
    <script type="text/php">
      if ( isset($pdf) ) { 
         
          $pdf->page_script('
              if ($PAGE_COUNT > 1) {
                 $pdf->text(520, 800, "Pagina " . $PAGE_NUM . " de " . $PAGE_COUNT, 7,7);
              } else {
                $pdf->text(520, 800, "Pagina " . $PAGE_NUM, 7, 7);
              }
          ');
      }
  </script>
</body>

</html>
FECHA DE IMPRESIÓN: <b>{{ $fechaActual }}</b> <br>
HORA DE IMPRESIÓN: <b>{{ $horaActual }}</b>
