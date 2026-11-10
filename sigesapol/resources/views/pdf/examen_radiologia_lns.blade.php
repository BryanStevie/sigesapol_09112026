<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>INFORME DE RADIOLOGIA</title>
        <style>
            .td_left{
                text-align: left !important;
            }
            .td_ancho_n{
                width: 4%;
                text-align: center
            }
            .cantidad{
                width: 11%;
            }
            .td_ancho_espacios{
                width: 5%;
            }
            .td_ancho_codigo{
                width: 10%;
            } 
            .td_ancho_especialidad{
                width: 25%;
            }
            td{
                padding-left: 2px !important;
            }
            table{
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                font-size: 11px; 
                font-family: "Times New Roman", Times, serif;
            }
            .ancho_nro{
                width: 5%;
            }
            .ancho_producto{
                width: 45%;
            }
            .titulos{
                padding:0px 10px;
            }
            h2{
                font-size: 15px !important
            }
            .titulo_principal{
                 width: 60%;
            }
            .logo{
                 width: 15%;
            }
            .grado{
                width: 14%;
            }
            .dni{
                width: 8%;
            }
            .rectangulo{
                padding:3px
            }
            @page {
                margin: 1.7cm 2cm 2.2cm 2cm;
            }
            body {

                padding: 0; 
                margin: 0; 
                /* margin-bottom: 40px; */
            }
            /* p {
                margin: 0;
                padding: 0;
                line-height: 1.2;
            } */
            /* .informe-parrafo {
                font-size: 12px;
                line-height: 1.2;
                margin-bottom: 10px;
            }/
            .informe-parrafo p {
                font-size: 12px;
                line-height: 1.2;
                margin: 0;
                padding: 0;
                text-align: justify;
            } */


            /* .informe-parrafo {
                font-size: 12px;
                line-height: 1.2;
                margin: 0;
                padding: 0;
                text-align: justify;
            } */
            p {
                margin: 0 0 2px 0;
                padding: 0;
                text-align: justify;
            }
            .informe-parrafo {
                font-size: 12px;
                line-height: 1.3;
                word-break: break-word;
                overflow-wrap: break-word;
                white-space: normal;
            }
        </style>
    </head>
    <body>
        <table style="margin:0px;padding:0px;padding-bottom:0px;" border="0">
            <tr>
                <td width="20%" rowspan="2">
                    <!--img src="images/logo_sanidad.png" style="margin-top: -1cm;" width="70" /-->
                </td>
                <td width="70%">
                    <table style="margin:0px;padding:0px;padding-bottom:0px;" border="0">
                        <tr>
                            <td width="100%" style="text-align:center; vertical-align:middle;">
                                <h2>{{$prestacion->establecimiento}}</h2>
                                <h3>DEPARTAMENTO DE DIAGNÓSTICO POR IMÁGENES</h3>
                                <h3>SERVICIO DE {{$procradiologia[0]->servicio_radiologia}}</h3>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td width="100%" style="text-align:center; vertical-align:middle;">
                                <span style="font-size:10px;font-style: italic;">No válido para trámite judicial válido solo para trámites administrativos.</span>
                            </td>
                        </tr> -->
                    </table>
                </td>
                <td width="10%" rowspan="1" style="padding: 0; margin: 0; text-align: center; vertical-align: middle;">
                    <img src="images/logo_pnp.png" width="80" style="display: block; margin: 0 auto; padding: 0;" />
                </td>
            </tr>
        </table>
        <hr style="margin: 0; text-align: center; vertical-align: middle;">
        <table style="margin:0px;padding:0px;padding-bottom:0;" border="0">
            <tr>
                <td style="font-weight: bold; font-size: 12px; white-space: nowrap;">N° DOCUMENTO</td>
                <td style="text-align: left;">:</td>
                <td style="font-size: 12px; white-space: nowrap;">
                    <strong>{{ isset($prestacion->tipo_doc_ident) ? $prestacion->tipo_doc_ident : '' }}</strong> -
                    {{ isset($prestacion->nro_doc_ident) ? $prestacion->nro_doc_ident : '' }}
                </td>
                <td style="font-weight: bold; font-size: 12px; white-space: nowrap;">N° HIST. CLÍNICA</td>
                <td style="text-align: left;">:</td>
                <td style="font-size: 12px; white-space: nowrap;">
                    {{ isset($prestacion->nro_historia) ? $prestacion->nro_historia : '' }}
                </td>
                <td style="font-weight: bold; font-size: 12px; white-space: nowrap;">FECHA</td>
                <td style="text-align: left;">:</td>
                <td style="font-size: 12px; white-space: nowrap;">
                    {{ isset($procradiologia[0]->confirmar_toma_created_at) && $procradiologia[0]->confirmar_toma_created_at 
                        ? date('d/m/Y', strtotime($procradiologia[0]->confirmar_toma_created_at)) 
                        : '-' 
                    }}
                </td>
            </tr>
        </table>
        <table style="margin:0px;padding:0px;padding-bottom:10px;" border="0">
            <tr>
                <td style="font-weight: bold;">NOMBRES</td>
                <th style="font-weight: bold;text-align: left;border: 0px solid;">:</th>
                <td style="font-size:12px !important;" colspan="3"><strong>{{$prestacion->asegurado_paterno . " " . $prestacion->asegurado_materno . ", " . $prestacion->asegurado_nombre}}</strong></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">FECHA NAC.</td>
                <th style="font-weight: bold;text-align: left;border: 0px solid;">:</th>
                <td colspan="2">
                <?php  echo isset($prestacion->fecha_nac) ? $prestacion->fecha_nac : ''; ?>
                - <strong>EDAD (
                    <?php
                        $fecha_nac = DateTime::createFromFormat('d/m/Y', $prestacion->fecha_nac);
                        $hoy = new DateTime();

                        if ($fecha_nac !== false) {
                            $edad = $fecha_nac->diff($hoy);
                            echo $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                        } else {
                            echo "Formato de fecha inválido";
                        }
                    ?>
                )</strong>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">SEXO</td>
                <th style="font-weight: bold;text-align: left;border: 0px solid;">:</th>
                <td width="400px"><?php echo isset($prestacion->sexo) ? ($prestacion->sexo == "F" ? "FEMENINO":"MASCULINO") : ''; ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">T. BENEFICIARIO</td>
                <th style="font-weight: bold;text-align: left;border: 0px solid;">:</th>
                <td ><?php echo isset($prestacion->parentesco) ? $prestacion->parentesco : ''; ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;vertical-align:top">EXAMEN</td>
                <th style="font-weight: bold;text-align: left;border: 0px solid;">:</th>
                <td colspan="3">
                @foreach($procradiologia as $p)
                {{$p->codigo . ' - ' . $p->descripcion_larga}}<br>
                @endforeach
                </td>
            </tr>
        </table>
        <hr style="margin: 0; text-align: center; vertical-align: middle;">
                <!-- {!! preg_replace(['/(<p[^>]*>\s*<br\s*\/?>\s*<\/p>\s*)+$/i', '/(<br\s*\/?>\s*)+$/i'],'', str_replace('&nbsp;', ' ', $procradiologia[0]->informe_parrafo)) !!} -->
        <!-- <div class="informe-parrafo">
            {!! preg_replace(['/<span[^>]*>|<\/span>/i','/<font[^>]*>|<\/font>/i','/<p[^>]*>\s*<br\s*\/?>\s*<\/p>/i','/(<br\s*\/?>\s*){2,}/i'],['', '', '', '<br>'],str_replace('&nbsp;', ' ', $procradiologia[0]->informe_parrafo)) !!}
        </div> -->
        <div class="informe-parrafo">
            {!! preg_replace(
                [
                    '/<span[^>]*>|<\/span>/i',
                    '/<font[^>]*>|<\/font>/i',
                    '/<p[^>]*>\s*<br\s*\/?>\s*<\/p>/i',
                    '/(<br\s*\/?>\s*){2,}/i'
                ],
                ['', '', '', '<br>'],
                str_replace('&nbsp;', ' ', $procradiologia[0]->informe_parrafo)
            ) !!}
        </div>

        <div class="contenedor-firma">
            <table style="width: 100%;margin: 0;">
                <tr>
                @if (!empty($procradiologia[0]->dni_medico_validacion)) 
                    <td style="text-align:right;vertical-align:middle;margin: 0;">
                        <img src="images/sello_depdiagimag.png" style="max-width:80px;margin: 0;" />
                    </td>
                    <td style="text-align:left;margin: 0;" width="210px">
                        @if (!empty($medico) && !empty($medico->nombre_firma))
                            <?php
                                $firmaPath = public_path('images/profesionales/' . $medico->nombre_firma);
                            ?>
                            @if (file_exists($firmaPath))
                                <img src="file://{{ $firmaPath }}" alt="Firma del médico" style="max-width:135px;margin: 0;">
                            @endif
                        @endif
                    </td>
                @else
                    <td style="text-align:right;vertical-align:middle;margin: 0;">
                        <img src="images/sello_depdiagimag.png" style="max-width:80px;margin: 0;" />
                    </td>
                    <td style="text-align:left;margin: 0;" width="210px">
                        <?php
                            $dniUsuario = Auth::user()->dni;
                            $nombreFirma = DB::table('medicos')
                                            ->where('dni', $dniUsuario)
                                            ->value('nombre_firma');

                            $firmaPath = public_path('images/profesionales/' . $nombreFirma);
                        ?>

                        @if ($nombreFirma && file_exists($firmaPath))
                            <img src="file://{{ $firmaPath }}" alt="Firma del médico" style="max-width:135px;margin: 0;">
                        @endif
                    </td>
                @endif            
                </tr>
            </table>
        </div>
        <!-- <footer>“Las conclusiones o recomendaciones contenidas en el presente informe se basan en la representación de las imágenes producto<br>
        del estudio realizado. En este sentido, el diagnostico final y el tratamiento basado en esto lo efectuara el médico tratante, utilizando<br>
        para ello los resultados de los estudios auxiliares, el cuadro clínico y la evaluación física realizada”</footer> -->
        <script type="text/php">
            if (isset($pdf)) {
                $line = str_repeat("_", 107);
               $pdf->page_text(50, 768, $line, null, 9, [0, 0, 0]);

                $pdf->page_text(50, 784, html_entity_decode('"Las conclusiones o recomendaciones contenidas en el presente informe se basan en la representación de las imágenes producto del', ENT_QUOTES, 'UTF-8'), null, 9, [0, 0, 0]);
                $pdf->page_text(50, 796, html_entity_decode('estudio realizado. En este sentido, el diagnóstico final y el tratamiento basado en esto lo efectuará el médico tratante, utilizando para ', ENT_QUOTES, 'UTF-8'), null, 9, [0, 0, 0]);
                $pdf->page_text(50, 808, html_entity_decode('ello los resultados de los estudios auxiliares, el cuadro clínico y la evaluación física realizada."', ENT_QUOTES, 'UTF-8'), null, 9, [0, 0, 0]);

                $pdf->page_text(510, 25, html_entity_decode("Página {PAGE_NUM} de {PAGE_COUNT}"), null, 11, [255, 0, 0]);
            }
        </script>
        
    </body>
</html>



