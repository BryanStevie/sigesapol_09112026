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
            margin: 1cm 1.5cm 3cm 1.5cm !important;
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

        #tbl_header {
            text-align: center;
        }

        footer {               
            position: fixed;
            bottom: 3cm;
            left: 1.5cm;
            right: 1.5cm;
            width: 100%;
            text-align: center; 
            overflow: auto;
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
                <img src="images/logo_pnp.png" alt="" width="40px;"/>
                <p><b>MINISTERIO DEL INTERIOR<br>POLICÍA NACIONAL DEL PERÚ</b></p>
            </td>
            <td></td>
            <td>
                <p><br><br><br><b>DIRECCIÓN DE SANIDAD<br>{{$nombre_establecimiento}}</b></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 18px;"><b>EVOLUCIÓN MÉDICA</b></td>
        </tr>
    </table>
    
    <footer>
        
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
                <td style="height:22px !important">{{$evolucion->paciente_paterno}}</td>
                <td>{{$evolucion->paciente_materno}}</td>
                <td>{{$evolucion->paciente_nombre}}</td>
                <td>{{$evolucion->nro_historia}}</td>
                <td>{{$evolucion->servicio_sala}}</td>
                <td>{{$evolucion->cama}}</td>
                <td></td>
            </tr>
        </table>
    </footer>
    
    <table border="0" id="" >
        <tr>
            <td>Fecha y Hora de Evolución de la atención: <b>{{ date("d/m/Y H:i:s", strtotime($evolucion->created_at))}}</b></td>
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

    <table border="0" >
        <tr>
            <td colspan="3" class="subtitulo_1"><b>DIAGNÓSTICO</b></td>
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
    <table border="0">
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
    <table border="0">
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

    <table border="0">
        <tr>
            <td colspan="4" class="subtitulo_2"><b>PROCEDIMIENTO</b></td>
        </tr>
        @foreach($procedimientos as $cpms)
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
        @endforeach
    </table>

    <table border="0" >
        <tr>
            <td colspan="2" class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
        </tr>
        <!-- 2RA PROPUESTA -->
        <!-- <tr>
            <td></td>
            <td style="width:200px; text-align:center; font-size:10px;">{{ $evolucion->profesional_paterno }} {{ $evolucion->profesional_materno }}, {{ $evolucion->profesional_nombre }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size:10px;">N° COLEGIATURA: {{ $evolucion->profesional_colegiatura }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size:10px; border-top: 1px solid black;">PROFESIONAL DE SALUD ENCARGADO</td>
        </tr> -->
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
        <!-- <tr>
            <td style="padding-top: 5px;"><b>ESPECIALIDAD</b></td>
            <td>{{ $evolucion->profesional_profesion }}</td>
            <td><b>N° ESPECIALIDAD:</b></td>
            <td>{{ $evolucion->profesional_colegiatura }}</td>
        </tr> -->
    </table>

    <table border="0" >
        <tr>
            <td class="subtitulo_2"><b>RECETAS VALE</b></td>
        </tr>
    </table>

    <table border="1" style="font-size:10px;" >
        @foreach ($recetas as $receta)
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
            @foreach ($receta_productos as $row)
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
                    <td style="text-align:center"><?php if($row->codigo=='59075700' || $row->codigo=='PF00718'):
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
        @endforeach
    </table>

    <table border="0" >
        <tr>
            <td class="subtitulo_2"><b>ORDEN DE LABORATORIO</b></td>
        </tr>
    </table>

    <table border="1" style="font-size:10px;" >
        @foreach ($ordenes_laboratorio as $orden_lab)
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
            @foreach ($orden_lab_examenes as $examen)
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
        @endforeach
    </table>

    <table border="0" >
        <tr>
            <td class="subtitulo_2"><b>ORDEN DE IMAGENOLOGIA</b></td>
        </tr>
    </table>

    <table border="1" style="font-size:10px;" >
        @foreach ($ordenes_imagenologia as $orden_img)
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
            @foreach ($orden_img_examenes as $examen)
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
        @endforeach
    </table>

    <table border="0" >
        <tr>
            <td class="subtitulo_2"><b>INTERCONSULTA</b></td>
        </tr>
    </table>

    <table border="1" style="font-size:10px;" >
        <?php $i=1; ?>
        @foreach ($interconsultas as $interconsulta)
            <tr>
                <td rowspan="3"><b>{{$i}}.-</b></td>
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
                <td colspan="3">&nbsp;</td>
            </tr>
            <?php $i++; ?>
        @endforeach
    </table>
    
</body>

</html>