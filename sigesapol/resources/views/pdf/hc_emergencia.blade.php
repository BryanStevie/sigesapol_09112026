<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ATENCION EMERGENCIA</title>
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
            margin: 1cm 1.5cm 3cm 1.5cm !important; /* Asegurar espacio suficiente para el footer */
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px !important;
            font-family: Arial, sans-serif;
        }

        .footer {
            position: fixed;
            bottom: 1.8cm;
            left: 1.5cm;
            right: 1.5cm;
            width: 100%;
            text-align: center;
        }

        #tbl_hospitalizacion_footer td {
            border: 1px black solid !important;
            font-size: 9px;
            padding: 3px;
            text-align: center;
        }
        .pagina-siguiente {
            page-break-before: always;
        }
        .marca {
            position: absolute;
            top: -0.2%;
            right: 30%;
            z-index: 2;
            color: rgba(22, 125, 189, 0.1);
            opacity: 0.7; /* Opacidad del 50% */
            font-size: 18px; 
            transform: translate(-50%, -50%); 
            font-weight: lighter;
            font-family: Helvetica,Arial,sans-serif;
        }
        .contenedor {
            position: relative;
            width: 30px; /* Ajusta el ancho según sea necesario */
            height: 17px; /* Ajusta la altura según sea necesario */
        }
        .valor {
            top: 1px;
            position: relative;
            z-index: 1;
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
                <p><br><br><br><br><b>DIRECCIÓN DE SANIDAD<br>HOSPITAL NACIONAL PNP "LUIS N. SÁENZ"</b></p>
            </td> 
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 18px;"><b>HISTORIA CLÍNICA DE EMERGENCIA</b></td>
        </tr>
    </table>
   
        @if(!isset($autoPrint))
        <footer class="footer">
            <table id="tbl_hospitalizacion_footer"  width="100%">
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
                        <b>Prioridad</b>
                    </td>
                    <td style="width: 100px">
                        <b>Area</b>
                    </td>
                    @if($sw==1)
                    <td style="width: 100px">
                       <b>Cama</b>
                    </td>
                    @endif
                </tr>
                <tr>
                    <td style="height:22px !important"> {{$emergencia->paterno_beneficiario}}</td>
                    <td>{{$emergencia->materno_beneficiario}}</td>
                    <td>{{$emergencia->nombre_beneficiario}}</td>
                    <td>{{$emergencia->nro_historia}}</td>
                    <td>{{$prestacion->servicio}}</td>
                    <td>{{$prestacion->consultorio}}</td>
                    @if($sw==1)
                    <td>{{$datoadicional->cama}}</td>
                    @endif
                </tr>
            </table>
        </footer>
        @endif
        
        
        {{-- I.- ANAMNESIS --}}
        <br>
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
                <td colspan="2">Apellidos y Nombres: <b>{{$emergencia->paterno_beneficiario}} {{$emergencia->materno_beneficiario}} {{$emergencia->nombre_beneficiario}}</b></td>
                <td></td>
                <td><b>DNI</b></td>
                <td>{{$emergencia->dni_beneficiario}}</td>
            </tr>
            <tr>
                <td>Edad: <b>{{$emergencia->edadpaciente}}</b></td>
                <td>Sexo: <b>{{$emergencia->sexo}}</b></td>
                <td></td>
                <td><b>CIP</b></td>
                <td>
                    @if(isset($persona_datos_adicionales) && $persona_datos_adicionales != null)
                    {{$persona_datos_adicionales->cip}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Raza: <b>{{$asegurado->etnia}}</b></td>
                <td>Estado civil: <b>{{$asegurado->estado_civil}}</b></td>
                <td></td>
                <td><b>HC</b></td>
                <td>{{$emergencia->nro_historia}}</td>
            </tr>
            <tr>
                <td>Grado de Instrucción: <b>{{$asegurado->grado_instruccion}}</b></td>
                <td>Ocupación: <b>{{$asegurado->ocupacion}}</b></td>
                <td></td>
                <td></td>
                <td></td>
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
                <td colspan="2">Parentesco con el paciente: <b>{{$emergencia->tipo_beneficiario}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Fecha y hora de ingreso: <b>{{$emergencia->fecha_ingreso}} {{$emergencia->hora_ingreso}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Fecha y hora de Historia Clínica: <b>{{$emergencia->created_at}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Código SITEDS: <b>{{$emergencia->cod_siteds_susalud}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">CUPS: <b>{{$emergencia->cups_pnp}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

        </table>

        <table border="0">
            <tr>
                <td colspan="3" class="subtitulo_2"><b>2.- ENFERMEDAD ACTUAL</b></td>
            </tr>
        </table>
        
        <table border="1" class="tbl_antecedentes">
            <tr>
                <td style="width:100%;" colspan="3">Tiempo de enfermedad / Relato cronologico: <span>{{$prestacion->tiempo_enfermedad}}</span></td>
            </tr>
        </table>
        <br>
        <table border="0"> 
            <tr>
                <td class="subtitulo_2" colspan="3"><b>3.- ANTECEDENTES PERSONALES</b></td>
            </tr>
        </table>

        <table border="1" class="tbl_antecedentes">
            <tr>
                <td ><b>Antecedente</b></td>
            </tr>
            <tr>
                <td style="width:100%;"><span>{!! nl2br(e($prestacion->antecedente)) !!}</span></td>
            </tr>
        </table>
        <table border="1" class="tbl_antecedentes">
            <tr>
                <td><b>FAMILIARES</b></td>
            </tr>
            <tr>
                <td><p style="margin:0;">{!! nl2br(e($prestacion->antecedente_familiar)) !!}</p></td>
            </tr>
        </table>
        {{-- FIN I.- ANAMNESIS --}}
        <br>
        {{-- II.- EXAMEN FÍSICO --}}
        <table border="0">
            <tr>
                <td colspan="4" class="subtitulo_1"><b>II.- EXAMEN FÍSICO</b></td>
            </tr>
        </table>
        
        <table border="0">
            <tr>
                <td colspan="4" class="subtitulo_2"><b>1.- FUNCIONES VITALES</b></td>
            </tr>
        </table>
        
        <table border="1" id="tbl_hospitalizacion_atencion" class="tbl_antecedentes">
            <tr>
                <td>Presión arterial: <b>{{ $emergencia->presion_arterial_x }} / {{$emergencia->presion_arterial_y}}</b></td>
                <td>Frecuencia Cardiaca: <b>{{ $emergencia->frecuencia_cardiaca }}</b></td>
                <td>Frecuencia Respiratoria: <b>{{ $emergencia->frecuencia_respiratoria }}</b></td>
                <td>Temperatura: <b>{{ $emergencia->temperatura }}</b></td>
                <td>Talla: <b>{{ $emergencia->talla_cm }}</b></td>
            </tr>
            <tr>
                <td>Peso: <b>{{ $emergencia->peso_kg }}</b></td>
                <td>Tamaño de Cintura: <b>{{ $emergencia->tamano_cintura_cm }}</b></td>
                <td>SaO2: <b>{{ $emergencia->sa02_porcentaje }}</b></td>
                <td>FiO2: <b>{{ $emergencia->fi02 }}</b></td>
                <td>Frecuencia Cardiaca Fetal: <b>{{ $emergencia->frecuencia_cardiaca_fetal }}</b></td>
            </tr>
        </table>
        <br>
        <table>
            <tr>
                <td colspan="4" class="subtitulo_2" style="text-align: left;"><b>2.- DESCRIPCION EXAMEN FÍSICO</b></td>
            </tr>
        </table>
        <table border="1" class="tbl_antecedentes">
            <tr>
                <td><p>{!! nl2br(e($prestacion->examen_fisico)) !!}</p></td> 
            </tr>
            
        </table>
      
        <br>
        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>3.- EXAMEN CLINICO</b></td>
            </tr>
        </table>
        <table border="1" class="tbl_antecedentes">
            <tr>
                <td><p>{!! nl2br(e($prestacion->examen_clinico)) !!}</p></td>
            </tr>
        </table>
        {{-- FIN II.- EXAMEN FÍSICO --}}

        {{-- III.- DIAGNÓSTICO --}}
        <table border="0">
            <tr>
                <td colspan="3" class="subtitulo_1"><b>III.- DIAGNÓSTICO</b></td>
            </tr>
        </table>
        
        <table style="width: 100%;" border="1">
            <tr>
              <td  class="subtitulo_2" style="width: 1%; text-align: center;"><b>N°</b></td>
              <td  class="subtitulo_2" style="width: 10%; text-align: center;"><b>CIE - 10</b></td>
              <td  class="subtitulo_2" style="text-align: center;"><b>DESCRIPCIÓN DEL DIAGNÓSTICO</b></td>
              <td  class="subtitulo_2" colspan="3"  style="width: 20%; text-align: center;"> <b>TIPO DE DIAGNÓSTICO</b></td>
            </tr>
            <?php 
            if(!empty($diagnosticos)):
            $n=1;
            foreach($diagnosticos as $row):?>
            <tr>
              <td><?php echo $n++;?></td>
              <td><?php echo $row->cod_dx;?></td>
              <td ><?php echo $row->nombre_dx;?></td>
              <td>
                <div class="contenedor">
                  <div class="valor"  style="text-align: center; font-size: 12px;"> P </div>
                  <div class="marca"><?php if($row->id_tipo_diagnostico==1){echo 'X';}?></div>
                </div>
              </td>
              <td>
                <div class="contenedor">
                <div class="valor"  style="text-align: center; font-size: 12px;">D</div>
                <div class="marca"><?php if($row->id_tipo_diagnostico==2){echo 'X';}?></div>
                </div>
              </td>
              <td>
                <div class="contenedor">
                  <div class="valor"  style="text-align: center; font-size: 12px;">R</div>
                  <div class="marca"><?php if($row->id_tipo_diagnostico==3){echo 'X';}?></div>
                </div>

               </td>
            </tr>
            <?php endforeach;
              endif;
            ?>
        </table>
        {{-- FIN III.- DIAGNÓSTICO --}}

        {{-- IV.- TRATAMIENTO --}}
        <table border="0">
            <tr>
                <td class="subtitulo_1"><b>IV.- TRATAMIENTO</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($prestacion->tratamiento_auxiliar)) !!}</p></td>
            </tr>
        </table>
        {{-- FIN IV.- TRATAMIENTO --}}

         {{-- V.- PLAN DE TRABAJO --}}
        <table border="0">
            <tr>
                <td class="subtitulo_1"><b>V.- PLAN DE TRABAJO</b></td>
            </tr>
            <tr>
                <td class="subtitulo_2"><b>1.- EXÁMENES DE AYUDA DIAGNÓSTICA</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($prestacion->examen_ayuda_diagnostica)) !!}</p></td>
            </tr>
        </table>
        
        <table border="1" class="tbl_antecedentes">
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

        <table border="1" class="tbl_antecedentes">
            <tr>
                <td class="subtitulo_2"><b>PROCEDIMIENTOS MÉDICO - QUIRÚRGICOS</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($prestacion->procedimiento_medico_quirurgico)) !!}</p></td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>2.- INTERCONSULTAS</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($prestacion->interconsulta)) !!}</p></td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
            </tr>
        </table>

        <table border="1" class="tbl_antecedentes">
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

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>RECETAS VALE</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;">
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

        <table border="0">
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

        <table border="0" style="font-size:8px;">
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
                        <td><p>{{$examen->nombre}}</p></td>
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
                        <td><p>
                        <?php 
                        if($examen->observacion!='0'):
                            echo $examen->observacion;
                        endif;
                        ?>
                        </p></td>
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
            <?php foreach ($interconsultas as $interconsulta){?>
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
            <?php }?>
        </table>
         {{-- FIN V.- PLAN DE TRABAJO --}}

        {{-- VI.- EVALUACIONES --}}
        <div style="page-break-after:always;"></div>

        <table border="0" id="tbl_hospitalizacion_atencion" >
            <tr>
                <td colspan="4" class="subtitulo_1"><b>VI.- EVALUACIONES </b></td>
            </tr>
        </table>
        {{-- EVALUACIONES --}}
        <?php foreach($evaluaciones as $key=> $evaluacion){?>
            <table border="0" id="" >
                <tr>
                    <td><b>EVALUACIÓN  N° {{ $key+1 }}</b></td>
                </tr>
            </table>
            <table border="0" id="" >
                <tr>
                    <td>Fecha y Hora de Evolución de la atención: <b>{{ date("d/m/Y H:i:s", strtotime($evaluacion->created_at))}}</b></td>
                </tr>
                <tr>
                    <td>Código SITEDS: <b>{{ $evaluacion->cod_siteds_susalud}}</b></td>
                </tr>
            </table>
    
            
            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td>Presión arterial: <b>{{ $evaluacion->presion_arterial_x }} / {{$evaluacion->presion_arterial_y}}</b></td>
                    <td>Frecuencia Cardiaca: <b>{{ $evaluacion->frecuencia_cardiaca }}</b></td>
                    <td>Frecuencia Respiratoria: <b>{{ $evaluacion->frecuencia_respiratoria }}</b></td>
                    <td>Temperatura: <b>{{ $evaluacion->temperatura }}</b></td>
                </tr>
                <tr>
                    <td>Talla: <b>{{ $evaluacion->talla_cm }}</b></td>
                    <td>Peso: <b>{{ $evaluacion->peso_kg }}</b></td>
                    <td>Tamaño de Cintura: <b>{{ $evaluacion->tamano_cintura_cm }}</b></td>
                    <td>SaO2: <b>{{ $evaluacion->sa02_porcentaje }}</b></td>
                </tr>
                <tr>
                    <td>FiO2: <b>{{ $evaluacion->fi02 }}</b></td>
                    <td>Frecuencia Cardiaca Fetal: <b>{{ $evaluacion->frecuencia_cardiaca_fetal }}</b></td>
                    <td>Diuresis: <b>{{ $evaluacion->diuresis }}</b></td>
                    <td>Balance Hídrico: <b>{{ $evaluacion->balance_hidrico }}</b></td>
                </tr>
            </table>

            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td class="subtitulo_2"><b>TERAPEUTICA Y PLAN DE TRABAJO</b></td>
                </tr>
                <tr>
                    <td><p>{!! nl2br(e($evaluacion->plan_trabajo)) !!}</p></td>
                </tr>
            </table>

            <table border="0">
                <tr>
                    <td colspan="3" class="subtitulo_1"><b>DIAGNÓSTICO</b></td>
                </tr>
            </table>
            <table style="width: 100%;" border="1">
                <tr>
                  <td  class="subtitulo_2" style="width: 1%; text-align: center;"><b>N°</b></td>
                  <td  class="subtitulo_2" style="width: 10%; text-align: center;"><b>CIE - 10</b></td>
                  <td  class="subtitulo_2" style="text-align: center;"><b>DESCRIPCIÓN DEL DIAGNÓSTICO</b></td>
                  <td  class="subtitulo_2" colspan="3"  style="width: 20%; text-align: center;"> <b>TIPO DE DIAGNÓSTICO</b></td>
                </tr>
                <?php 
                if(!empty($diagnosticos_evaluaciones)):
                $n=1;
                foreach($diagnosticos_evaluaciones as $row):?>
              
                @if($evaluacion->id == $row->id_evaluacion)
                <tr>
                  <td><?php echo $n++;?></td>
                  <td><?php echo $row->cod_dx;?></td>
                  <td ><?php echo $row->nombre_dx;?></td>
                  <td>
                    <div class="contenedor">
                      <div class="valor"  style="text-align: center; font-size: 12px;"> P </div>
                      <div class="marca"><?php if($row->id_tipo_diagnostico==1){echo 'X';}?></div>
                    </div>
                  </td>
                  <td>
                    <div class="contenedor">
                    <div class="valor"  style="text-align: center; font-size: 12px;">D</div>
                    <div class="marca"><?php if($row->id_tipo_diagnostico==2){echo 'X';}?></div>
                    </div>
                  </td>
                  <td>
                    <div class="contenedor">
                      <div class="valor"  style="text-align: center; font-size: 12px;">R</div>
                      <div class="marca"><?php if($row->id_tipo_diagnostico==3){echo 'X';}?></div>
                    </div>
                   </td>
                </tr>
                @endif
                <?php endforeach;
                  endif;
                ?>
            </table>
    
            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td colspan="4" class="subtitulo_2"><b>PROCEDIMIENTO</b></td>
                </tr>
                @foreach($procedimientos_evaluaciones as $cpms)
                @if($evaluacion->id == $cpms->id_evaluacion)
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

            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td class="subtitulo_2"><b>INDICACIONES TERAPÉUTICAS</b></td>
                </tr>
                <tr>
                    <td><p>{!! nl2br(e($evaluacion->tratamiento_auxiliar)) !!}</p></td>
                </tr>
            </table>

            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
                </tr>
            </table>
    
            <table border="1" style="" >
                <!-- <tr>
                    <td><b>Apellido Paterno</b></td>
                    <td><b>Apellido Materno</b></td>
                    <td><b>Nombres</b></td>
                    <td style="width:15%"><b>N° Colegiatura</b></td>
                </tr>
                <tr>
                    <td>{{ $evaluacion->profesional_paterno }}</td>
                    <td>{{ $evaluacion->profesional_materno }}</td>
                    <td>{{ $evaluacion->profesional_nombre }}</td>
                    <td>{{ $evaluacion->profesional_colegiatura }}</td>
                </tr> -->
                <tr>
                    <td><b>APELLIDOS Y NOMBRES:</b></td>
                    <td colspan="3">{{ $evaluacion->profesional_paterno }} {{ $evaluacion->profesional_materno }} {{ $evaluacion->profesional_nombre }}</td>
                </tr>
                <tr>
                    <td style="width:23%; padding-top: 5px;"><b>PROFESIÓN:</b></td>
                    <td style="">{{ $evaluacion->profesional_profesion }}</td>
                    <td style="width:17%"><b>N° COLEGIATURA:</b></td>
                    <td style="width:10%">{{ $evaluacion->profesional_colegiatura }}</td>
                </tr>
            </table>
        
            <table border="0">
                <tr>
                    <td class="subtitulo_2"><b>RECETAS VALE</b></td>
                </tr>
            </table>
    
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                @foreach ($recetas_evaluaciones as $receta)
                    @if ($evaluacion->id == $receta->id_evaluacion)
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
                        @foreach ($receta_productos_evaluaciones as $row)
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
                @foreach ($ordenes_laboratorio_evaluaciones as $orden_lab)
                    @if ($evaluacion->id == $orden_lab->id_evaluacion)
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
                    @foreach ($orden_lab_examenes_evaluaciones as $examen)
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
            <br>
            <table border="1" style="font-size:10px;" >
                <tbody>
                <tr>
                    <td width="300px" style="font-weight:bold;text-align:center;"> <!-- style="text-align:left;padding-top:7px;padding-bottom:7px;font-weight:bold;background-color:rgb(213,213,213);" border="1" -->
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">EXAMEN</div>
                    </td>
                    <td width="100px" style="font-weight:bold;text-align:center;">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">RESULTADO</div>
                    </td>
                    <td style="font-weight:bold;text-align:center;">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">RANGO REFERENCIAL</div>
                    </td>
                    <td width="80px" style="font-weight:bold;text-align:center;">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;">UNIDADES</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-weight:bold;text-align:center;font-size:12px;">
                    <div class="rectangulo" style="border: 1px solid #A4A4A4;"><?php //echo $emergencia->servicio_encargado_nombre; ?></div>
                    </td>
                </tr>
    
                <?php
                foreach ($ordenes_laboratorio_evaluaciones as $orden_lab){
                    $resultados1 = $results = DB::table('laboratorio_resultados')
                                                ->select([
                                                    'p_nombre',
                                                    'l_resultado',
                                                    DB::raw("(CASE WHEN ra_texto <> '' THEN ra_texto ELSE CONCAT(l_ref_inf,' - ',l_ref_sup) END) AS rango_referencial_ant"),
                                                    'l_unidades',
                                                    'g_nombre',
                                                    'usr_name',
                                                    'l_resultcomp AS Compuesto',
                                                    'p_comentario',
                                                    DB::raw("(CASE WHEN l_ralt_id = '0' THEN CONCAT(l_ref_inf,' - ',l_ref_sup) ELSE ra_texto END)::TEXT AS rango_referencial")
                                                ])
                                                ->where('l_estado', '>=', 4)
                                                ->where('h_numero', $emergencia->dni_beneficiario)
                                                ->where('o_numero', $orden_lab->id)
                                                ->where('l_resultcomp', '')
                                                ->orderBy('l_orden_imp')
                                                ->get();
                    if ($resultados1 != null){
                        foreach ($resultados1 as $obj) {
                        $rango_referencial = "";
                        if ($obj->rango_referencial == "0 - 0"){
                            $rango_referencial = "";
                        }else{
                            $rango_referencial = $obj->rango_referencial;
                            $rango_referencial = str_replace("&", "&amp;", $rango_referencial);
                            $rango_referencial = str_replace("<", "&lt;", $rango_referencial);
                            $rango_referencial = str_replace(">", "&gt;", $rango_referencial);
                            $rango_referencial = str_replace("\n"," <br>",$rango_referencial);
                        };
                ?>
                        <tr>
                            <td><?php echo $obj->p_nombre; ?></td> 
                            <td>&nbsp;&nbsp;<?php echo $obj->l_resultado; ?></td> 
                            <td><?php echo $rango_referencial."<br>".($obj->p_comentario==""? "" : $obj->p_comentario."<br><br>") ?></td>
                            <td><?php echo $obj->l_unidades; ?></td> 
                        </tr>
                <?php
                        }
                    }
                }
                ?>
            </table>
    
            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>ORDEN DE IMAGENOLOGIA</b></td>
                </tr>
            </table>
    
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                @foreach ($ordenes_imagenologia_evaluaciones as $orden_img)
                    @if ($evaluacion->id == $orden_img->id_evaluacion)
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
                        @foreach ($orden_img_examenes_evaluaciones as $examen)
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
                @foreach ($interconsultas_evaluaciones as $interconsulta)
                    @if ($evaluacion->id == $interconsulta->id_evaluacion)
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
        <?php 
        }?>

    @if(isset($autoPrint) && $autoPrint)
        <script>
            window.onload = function() { window.print(); };
        </script>
    @endif
</body>
</html>
