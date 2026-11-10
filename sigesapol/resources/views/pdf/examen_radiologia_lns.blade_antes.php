<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>INFORME DE RADIOLOGIA</title>
        <style>
            .informe-parrafo {
                font-size: 12px;
                line-height: 1.5;
                margin-bottom: 15px;
            }
            .informe-parrafo p {
                margin: 0;
                padding: 0;
            }
            .informe-parrafo ul {
                margin-left: 20px;
            }
            .informe-parrafo li {
                margin-bottom: 5px;
            }

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
                margin: 1cm 2cm;
                font-family: Arial;
            }
            body {
                margin-bottom: 40px;
            }

            footer {
                border: 1px solid #A4A4A4;
                width: 100%;
                height: 30px;
                bottom: 0;
                border-top: 1px solid #C1CED9;
                margin-bottom: 0px;
                padding: 0px;
                position: fixed;
                text-align: left;
                border: 0px;
                font-size: 10px !important;
                font-style: italic;
                font-family: "Times New Roman", Times, serif;
            }
            .custom-hr {
                width: 75%;   
                border: none;
                height: 2px;
                background-color: black; 
                margin: 0 ; 
            }
            
        </style>
    </head>
    <body>
        <table style="margin:0px;padding:0px;padding-bottom:0px;" border="0">
            <tr>
                <td width="10%" rowspan="2">
                    <img src="images/logo_pnp.png" width="70" />
                </td>
                <td width="60%">
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
                <td width="10%" rowspan="2">
                    <img src="images/logo_sanidad.png" width="70" />
                </td>
            </tr>
        </table>
        <hr>
        <table style="margin:0px;padding:0px;padding-bottom:10px;" border="0">
            <tr>
                <td style="font-weight: bold;" width="100px">N° DOCUMENTO</td>
                <th style="font-weight: bold;text-align: left;border: 0px solid;">:</th>
                <td width="400px; font-size:12px !important;"><strong>{{$prestacion->tipo_doc_ident}}</strong> - <?php  echo  isset($prestacion->nro_doc_ident) ? $prestacion->nro_doc_ident : ''; ?></td>
                <td style="font-weight: bold;" width="50px">FECHA</td>
                <th style="font-weight: bold;text-align: left;border: 0px solid;">:</th>
                <td width="65px">
                <?php
                    if ($procradiologia[0]->validated_at != null):
                        $fecha_validacion = date("d/m/Y", strtotime($procradiologia[0]->validated_at));
                    else:
                        $fecha_validacion = "-";
                    endif;
                    echo $fecha_validacion;
                ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;" width="100px">N° HIST. CLÍNICA</td>
                <th style="font-weight: bold;text-align: left;border: 0px solid;">:</th>
                <td width="400px">{{$prestacion->nro_historia}}</td>
            </tr>
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
                {{$p->codigo . ' - ' . $p->nombre}}<br>
                @endforeach
                </td>
            </tr>
        </table>
        <hr>
        <!--?php dd($procradiologia)   ?-->
        <table style="margin:0px;padding:0px;padding-bottom:0;" border="0">
            <tr>
                <h3 style="font-weight: bold;vertical-align:top; text-align: center;margin:0px;">ADENDA</h3>
            </tr>
        </table>
        <hr>
            <!--table style="background-color:white !important; font-size: 12px" border="0" -->
                <!-- <tr>
                    <td>{!!$procradiologia[0]->informe_parrafo!!}</td>
                </tr> -->
                <!-- <tr>
                    <td></td>
                </tr> -->
                <table style="background-color:white !important; font-size: 12px" border="0" >
                    <tr>
                        <div class="informe-parrafo">
                            {!! str_replace('&nbsp;', ' ', $procradiologia[0]->informe_parrafo) !!}
                        </div>
                    </tr>
                </table>
            <!--/table-->
        <hr>
        <table border="0">
            <tr>
            {{-- <td> Medico informante: 
                    @if (!empty($procradiologia[0]->dni_medico_validacion))
                    {{$medico->paterno}} {{$medico->materno}} {{$medico->nombre}}
                    @else 
                    INFORME NO VALIDADO
                    @endif 
                </td> --}}
                <td></td>
                <td style="text-align:right">TM:
                    <?php
                    if ($procradiologia[0]->transcribed_us <> ''):
                        echo strtoupper(substr($procradiologia[0]->name,0,1)).strtoupper(substr($procradiologia[0]->paterno,0,1)).strtoupper(substr($procradiologia[0]->materno,0,1));
                    else:
                        echo "TRANSCRIPTOR NO REGISTRADO";
                    endif; 
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-bottom:15px;">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">
                <table>
                    <tr>
                    @if (!empty($procradiologia[0]->dni_medico_validacion_act))
                        <td style="text-align:right;vertical-align:middle">
                            <img src="images/sello_depdiagimag.png" width="100" />
                        </td>
                        <td style="text-align:left;" width="210px">
                        @if($medico->nombre_firma != null) 
                            <img src="images/profesionales/{{$medico->nombre_firma}}" alt="" style="max-width:210px;" >
                        @endif 
                        </td>
                        <!-- <td style="text-align:right;text-align:left;" width="210px">
                                <table class="table-firmas style24" width="100%"  >
                                <tbody>
                                    <tr>
                                        <td width="40%"><p align="right"><img src="images/logo_sanidad.png" width="55px;" /></p></td>
                                        <td width="60%"><p align="left">Firmado digitalmente por: <br/><!?php echo $medico->nombre." ".$medico->paterno." ".$medico->materno ?><br/>Motivo:Soy el autor del documento<br/>Fecha: <!?php $fecha_firma = date("d/m/Y h:i:s ", strtotime($procradiologia[0]->fecha_firma));
                                            echo $fecha_firma; ?></p></td>
                                    </tr>
                                </tbody>
                                </table>
                        </td> -->
                    @else
                        <td></td>
                        <td></td>
                    @endif    
                    </tr>
                </table>
                </td>
            </tr>
        </table>
        <!-- <footer>“Las conclusiones o recomendaciones contenidas en el presente informe se basan en la representación de las imágenes producto<br>
        del estudio realizado. En este sentido, el diagnostico final y el tratamiento basado en esto lo efectuara el médico tratante, utilizando<br>
        para ello los resultados de los estudios auxiliares, el cuadro clínico y la evaluación física realizada”</footer> -->
        <footer>
            <hr class="custom-hr">
            “No válido para trámite judicial, solo para trámites administrativos.”
        </footer>
        <script type="text/php">
            if (isset($pdf)) {
                $x = 490;
                $y = 790;
                $text = "Pagina {PAGE_NUM} de {PAGE_COUNT}";
                $font = null;
                $size = 10;
                $color = array(255,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
    </body>
</html>



