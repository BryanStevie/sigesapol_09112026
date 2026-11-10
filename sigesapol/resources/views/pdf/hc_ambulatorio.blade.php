<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ATENCION CONSULTA EXTERNA</title>
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
            <td colspan="3" style="font-size: 18px;"><b>HISTORIA CLÍNICA CONSULTA EXTERNA</b></td>
        </tr>
    </table>
   
   
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
                        <b>Servicio</b>
                    </td>
                    <td style="width: 100px">
                        <b>Consultorio</b>
                    </td>
                  
                </tr>
                <tr>
                    <td style="height:22px !important"> {{$prestacion->paterno}}</td>
                    <td>{{$prestacion->materno}}</td>
                    <td>{{$prestacion->nombre}}</td>
                    <td>{{$prestacion->nro_historia}}</td>
                    <td>{{$prestacion->servicio}}</td>
                    <td>{{$prestacion->consultorio}}</td>
                  
                </tr>
            </table>
        </footer>
          
        
        
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
                <td colspan="2">Apellidos y Nombres: <b>{{$prestacion->paterno}} {{$prestacion->materno}} {{$prestacion->nombre}}</b></td>
                <td></td>
                <td><b>DNI</b></td>
                <td><?php echo $prestacion->nro_doc_ident;?></td>
            </tr>
            <tr>
                <td>Edad: <b>{{ $prestacion->edadpaciente }}</b></td>
                <td>Sexo: <b>{{ $prestacion->sexo }}</b></td>
                <td></td>
                <td><b>CIP</b></td>
                <td>
                    @if(isset($persona_datos_adicionales) && $persona_datos_adicionales != null)
                    {{$persona_datos_adicionales->cip}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Raza: <b>{{$prestacion->etnia}}</b></td>
                <td>Estado civil: <b>{{$prestacion->estado_civil}}</b></td>
                <td></td>
                <td><b>HC</b></td>
                <td>{{$prestacion->nro_historia}}</td>
            </tr>
            <tr>
                <td>Grupo sanguineo: <b>{{ $prestacion->grupo_sangre }}</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Grado de Instrucción: <b>{{$prestacion->grado_instruccion}}</b></td>
                <td>Ocupación: <b>{{$prestacion->ocupacion}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Lugar de Nacimiento: <b>{{$prestacion->departamento_nacimiento2}} / {{$prestacion->provincia_nacimiento2}} / {{$prestacion->distrito_nacimiento2}} / {{strtoupper($prestacion->direccion_nacimiento)}}</b></td>
                <td>Fecha de nacimiento: <b>{{$prestacion->fecha_nac}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Procedencia: <b>{{$prestacion->departamento_procedencia2}} / {{$prestacion->provincia_procedencia2}} / {{$prestacion->distrito_procedencia2}} / {{strtoupper($prestacion->direccion_procedencia)}}</b></td>
                <td>Religión: <b>{{$prestacion->religion}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Domicilio: <b>{{strtoupper($prestacion->domicilio)}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Distrito: <b>{{$prestacion->distrito}}</b></td>
                <td>Departamento: <b>{{$prestacion->departamento}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Teléfono fijo: <b>{{$prestacion->telefono}}</b></td>
                <td>Celular: <b>{{$prestacion->telefono2}}</b></td>
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
                <td colspan="2">Parentesco con el paciente: <b>{{$prestacion->tipo_beneficiario}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Fecha y hora de ingreso: <b>{{$prestacion->fecha_ingreso}} {{$prestacion->hora_ingreso}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Fecha y hora de Historia Clínica: <b>{{$prestacion->created_at}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Código SITEDS: <b>{{$prestacion->codigo_siteds}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">CUPS: <b>{{$prestacion->cups_pnp}}</b></td>
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
                <td style="width:100%;" colspan="3"><b>Motivo de la consulta / Tiempo de enfermedad / Relato cronologico<b>
                    <p>{{$prestacion->motivo_visita}}</p>
                    <p>{{$prestacion->tiempo_enfermedad}}</p>
                    <p>{{$prestacion->anamnesis}}</p>
                </td>
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
                <td ><b>ANTECEDENTE</b></td>
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
        <table border="1" class="tbl_antecedentes">
            <tr>
                <td><b>ALERGIA A MEDICAMENTOS</b></td>
            </tr>
            <tr>
                <td><p style="margin:0;">{!! isset($alergias->descripcion_alergia) ? nl2br(e($alergias->descripcion_alergia)) : '' !!}
                </p></td>
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
                <td>Presión arterial: <b>{{ $prestacion->presion_arterial_x }} / {{$prestacion->presion_arterial_y}}</b></td>
                <td>Frecuencia Cardiaca: <b>{{ $prestacion->frecuencia_cardiaca }}</b></td>
                <td>Frecuencia Respiratoria: <b>{{ $prestacion->frecuencia_respiratoria }}</b></td>
                <td>Temperatura: <b>{{ $prestacion->temperatura }}</b></td>
                <td>Talla: <b>{{ $prestacion->talla_cm }}</b></td>
            </tr>
            <tr>
                <td>Peso: <b>{{ $prestacion->peso_kg }}</b></td>
                <td>Tamaño de Cintura: <b>{{ $prestacion->tamano_cintura_cm }}</b></td>
                <td>SaO2: <b>{{ $prestacion->sa02_porcentaje }}</b></td>
                <td>FiO2: <b>{{ $prestacion->fi02 }}</b></td>
                <td>Frecuencia Cardiaca Fetal: <b>{{ $prestacion->frecuencia_cardiaca_fetal }}</b></td>
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
        <br>
        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>4.- EXAMENES AUXILIARES</b></td>
            </tr>
        </table>
        <table border="1" class="tbl_antecedentes">
            <tr>
                <td><p>{!! nl2br(e($prestacion->examen_auxiliar)) !!}</p></td>
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
              <td><?php echo $row->codigo;?></td>
              <td ><?php echo $row->nombre;?></td>
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
                <td class="subtitulo_1"><b>V.- PROCEDIMIENTOS</b></td>
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
                <td style="width:10%; border-bottom: 1px solid gray;">{{ $cpms->codigo }}</td>
                <td style="width:70%; border-bottom: 1px solid gray;"><p>{{ $cpms->nombre }}</p></td>
                <td style="width:15%; border-bottom: 1px solid gray;"><b>Cantidad:</b> {{ $cpms->cantidad }}</td>
                <?php
                $i++;
                ?>
            </tr>
            @endforeach
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

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
            </tr>
        </table>
        <table border="1" class="tbl_antecedentes">
            <tr>
                <td><b>APELLIDOS Y NOMBRES:</b></td>
                <td colspan="3">{{ $prestacion->medico_paterno }} {{ $prestacion->medico_materno }} {{ $prestacion->medico_nombre }}</td>
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
