<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PRESTACION MEDICA</title>
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
            font-size: 14px; 
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
    <table style="margin:0px;padding:0px;padding-bottom:0px;" border="0">
        <tr>
          <td width="20%" rowspan="2">
              
            </td>
            <td width="70%">
                <table style="margin:0px;padding:0px;padding-bottom:0px;" border="0">
                    <tr>
                        <td width="100%" style="text-align:center; vertical-align:middle;">
                            <h2 style="text-transform: uppercase; font-size: 20px; margin: 0; padding: 0;">
                                <span><b>ATENCIÓN QUIRÚRGICA</b></span>
                            </h2>
                            <h2 style="text-transform: uppercase; font-size: 16px; margin: 2px 0; padding: 0;">
                                CUPS: 
                                <span style="margin: 0; font-weight: 100; font-size: 16px;">
                                    {{$prestacion->codigo_cups}}
                                </span>
                            </h2>
                            <h3 style="padding-top: 0.2rem; margin: 0; font-size: 14px;">
                                PRESTACIÓN MÉDICA Nº <?php echo $prestacion->id ?>
                            </h3>
                            <span style="margin: 0; font-weight: bold; font-size: 12px;">
                                COD.SITEDS 
                                @if(isset($prestacion->codigo_siteds_cqx))
                                    {{$prestacion->codigo_siteds_cqx}}
                                @else 
                                    {{'-------'}}
                                @endif
                            </span>
                        </td>
                    </tr>
                   
                </table>
            </td>
            <td width="10%" rowspan="1" style="padding: 0; margin: 0; text-align: center; vertical-align: middle;">
                <img src="images/logo_pnp.png" width="80" style="display: block; margin: 0 auto; padding: 0;" />
            </td>
            
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
                @if(!empty($cama->nombre))
                <td style="width: 100px">
                    <b>Cama</b>
                </td>
                @endif
                
            </tr>
            <tr>
                <td style="height:22px !important"> {{$prestacion->paterno}}</td>
                <td>{{$prestacion->materno}}</td>
                <td>{{$prestacion->nombre}}</td>
                <td>{{$prestacion->nro_historia}}</td>
                <td>{{!empty($cqx_atencion->servicio)?$cqx_atencion->servicio:''}}</td>
                @if(!empty($cama->nombre))
                <td>{{!empty($cama->nombre)?$cama->nombre:'' }}</td>
                @endif
            </tr>
        </table>
    </footer>
        
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
            <td colspan="5" class="subtitulo_2"><b>I.- FILIACIÓN</b></td>
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
            <td colspan="2">Apellidos y Nombres del titular: <b>{{ isset($titular) && $titular ? $titular->paterno : '' }} {{ isset($titular) && $titular ? $titular->materno : '' }} {{ isset($titular) && $titular ? $titular->nombre : '' }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Grado de Situación del titular: <b>{{ isset($grado) && $grado ? $grado->descripcion_corta : '' }}</b></td>
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
            <td colspan="2">Código SITEDS: <b>{{$prestacion->codigo_siteds_cqx}}</b></td>
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
        <tr>
            <td colspan="2">{{ !empty($cama->nombre)?'CAMA:':'' }} <b>{{ !empty($cama->nombre)?$cama->nombre:'' }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    
    <table border="0">
        <tr>
            <td class="subtitulo_1"><b>II.- RECETA VALE</b></td>
        </tr>
    </table>
    @if(isset($recetas) && (is_array($recetas) || $recetas instanceof \Illuminate\Support\Collection) && count($recetas) > 0)
    <table border="1" style="font-size:10px;">
        @foreach ($recetas as $receta)
            <tr>
                <td colspan="6" style="padding: 6px;"><b>FARMACIA: </b>{{$receta->farmacia}}</td>
                <td style="padding: 6px;"><b>N°: </b>{{$receta->nro_receta}}</td>
            </tr>
            <tr>
                <th style="padding: 6px;">N°</th>
                <th style="padding: 6px;">PRODUCTO</th>
                <th style="padding: 6px;">UNIDAD<br>MEDIDA</th>
                <th style="padding: 6px;">CANT.</th>
                <th style="padding: 6px;">DÍAS</th>
                <th style="padding: 6px;">VÍA<br>ADMINISTRACIÓN</th>
                <th style="padding: 6px;">POSOLOGÍA</th>
            </tr>
            <?php $i=1; ?>
            @foreach ($receta_productos as $row)
                @if ($receta->id == $row->id_receta)
                <tr>
                    <td style="padding: 6px;">{{$i}}.-</td>
                    <td style="padding: 6px;">{{$row->codigo}} - {{$row->nombre}}
                        <?php 
                            if($row->codigo=='59075700'):
                            echo "(".$row->cantidad_prescrita."L)";
                            endif; 
                        ?>
                    </td>
                    <td style="text-align:center; padding: 6px;">{{$row->abreviatura}}</td>
                    <td style="text-align:center"><?php if($row->codigo=='59075700' || $row->codigo=='PF00718'):
                        echo $row->cantidad_prescrita/1000;
                        else:
                        echo $row->cantidad_prescrita;
                        endif; ?>
                    </td>
                    <td style="text-align:center; padding: 6px;">{{$row->tiempo_periodo}}</td>
                    <td style="text-align:center; padding: 6px;">{{$row->via}}</td>
                    <td style="padding: 6px;">{{$row->posologia}}</td>
                </tr>
                <?php $i++; ?>
                @endif
            @endforeach
            <tr>
                <td colspan="7">&nbsp;</td>
            </tr>
        @endforeach
    </table>
    @endif

    <table border="0">
        <tr>
            <td class="subtitulo_1"><b>III.- PROGRAMACIÓN QUIRÚRGICA</b></td>
        </tr>
    </table>
    <table border="1" style="font-size:10px;">
        <tr>
            <td style="padding: 6px;"><b>PRIORIDAD DE PROGRAMACION EN SEMANAS:</b></td>
            <td style="padding: 6px;">{{ $intervencion->prioridad_programacion}}</td>
            <td style="padding: 6px;"><b>REQUIERE INTERNAMIENTO PRE QX:</b></td>
            <td style="padding: 6px;">
                    @if ($intervencion->is_requiere_internamiento_preqx)
                    SI {{ $intervencion->tiempo_internamiento_preqx }}
                @else
                    NO
                @endif
            </td>
            <td style="padding: 6px;"><b>DIAS DE HOSPITALIZADO POST QX:</b></td>
            <td colspan="3" style="padding: 6px;">

            @if ($intervencion->is_hospitalizacion_postqx)
                SI {{ $intervencion->tiempo_hospitalizacion_postqx }}
            @else
                NO
            @endif
            </td>
        </tr>
        <tr>
            <td style="padding: 6px;"><b>A SER OPERADO EN:</b></td>
            <td style="padding: 6px;">
                <?php $lugar_operacion = "";
                switch ($intervencion->id_lugar_operacion) {
                    case 1: $lugar_operacion = "HOSPITALIZADO"; break;
                    case 2: $lugar_operacion = "CLÍNICA DE DÍA"; break;
                }
                ?>
                {{ $lugar_operacion }}
            </td>
            <td style="padding: 6px;"><b>FECHA PROBABLE DE INTERVENCIÓN:</b></td>
            <td style="padding: 6px;">{{ $intervencion->fecha_probable }}</td>
            <td style="padding: 6px;"><b>FECHA DE INTERVENCION:</b></td>
            <td colspan="3" style="padding: 6px;">
            {{ date('d-m-Y', strtotime($intervencion->fecha_intervencion)) }}
            </td>
        </tr>
        <tr>
            <td style="padding: 6px;"><b>HORA DE INICIO:</b></td>
            <td style="padding: 6px;">
                {{ date('H:i', strtotime($intervencion->hora_inicio_prog_qx)) }}
            </td>
            <td style="padding: 6px;"><b>HORA DE FIN:</b></td>
            <td style="padding: 6px;">
                {{ date('H:i', strtotime($intervencion->hora_fin_prog_qx)) }}
            </td>
            <td style="padding: 6px;"><b>SALA DE INTERVENCIÓN:</b></td>
            <td style="padding: 6px;">{{ $intervencion->sala_intervencion }}</td>
            <td style="padding: 6px;"><b>TIPO DE ANESTESIA:</b></td>
            <td style="padding: 6px;">{{ $intervencion->tipo_anestesia }}</td>
        </tr>
    
    </table>

    <table border="0">
        <tr>
            <td class="subtitulo_1"><b>IV.- PROCEDIMIENTO QUIRURGICO</b></td>
        </tr>
    </table>
    <table border="1" style="font-size:10px;">
        <tr>
            <td style="padding: 6px;"><b>TIPO DE ANESTESIA:</b></td>
            <td style="padding: 6px;">
                @if(!empty($intraoperatorio)){{ $intraoperatorio->descripcion }} @endif
            </td>
            <td style="padding: 6px;"><b>INICIO DE ANESTESIA:</b></td>
            <td style="padding: 6px;">
                @if(!empty($intraoperatorio)){{ $intraoperatorio->anestesia_inicio }} @endif      
            </td>
            <td style="padding: 6px;"><b>TÉRMINO DE ANESTESIA</b></td>
            <td style="padding: 6px;">
                @if(!empty($intraoperatorio)){{ $intraoperatorio->anestesia_termino }}@endif  
            </td>
        </tr>
            <tr>
            <td style="padding: 6px;"><b>INICIO DE CIRUGÍA</b></td>
            <td style="padding: 6px;">
                @if(!empty($intraoperatorio)){{ $intraoperatorio->cirugia_inicio }} @endif  
            </td>
            <td style="padding: 6px;"><b>TÉRMINO DE CIRUGÍA</b></td>
            <td style="padding: 6px;">
                @if(!empty($intraoperatorio)){{ $intraoperatorio->cirugia_termino }}@endif  
            </td>
            <td style="padding: 6px;"><b>TIEMPO REAL</b></td>
            <td style="padding: 6px;">
                @if(!empty($intraoperatorio)) {{ $intraoperatorio->tipo_real }}@endif
            </td>
        </tr>
        <tr>
            <td style="padding: 6px;"><b>HORA LLEGADA URPA</b></td>
            <td style="padding: 6px;" colspan="5">
                @if(!empty($intraoperatorio)){{ $intraoperatorio->hora_llegada_urpa }}@endif
            </td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 6px;"><b>COMPLICACIÓN</b></td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 6px;">
                @if(!empty($cqx_atenciones)) {{ $cqx_atenciones->complicacion_medica }}@endif
            </td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 6px;"><b>HALLAZGOS OPERATORIO</b></td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 6px;">
                @if(!empty($cqx_atenciones)) {{ $cqx_atenciones->allazgo_operatorio }}@endif
            </td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 6px;"><b>EXAMEN BACTERIOLOGICO</b></td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 6px;">
                @if(!empty($examen_bacteriologico)) {{ $cqx_atenciones->examen_bacteriologico }}@endif
            </td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 6px;"><b>EXAMEN PATOLOGICO</b></td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 6px;">
                    @if(!empty($examen_patologico)) {{ $cqx_atenciones->examen_patologico }}@endif
            </td>
        </tr>
    </table>
    <table border="0" style="font-size:10px;">
        <tr>
            <th class="subtitulo_1"><b>DIAGNÓSTICOS PRE OPERATORIO</b></th>
        </tr>
    </table>
    <table style="font-size:10px;" border="1">
        <tr>
        <th style="padding: 6px;">N°</th>
        <th style="padding: 6px;">CIE - 10</th>
        <th  style="padding: 6px;">DESCRIPCIÓN DEL DIAGNÓSTICO</th>
        <th colspan="3" style=" padding: 6px;"> TIPO DE DIAGNÓSTICO</th>
        </tr>
        @if($datos['pre_diagonosticos_emergencia'])
            @foreach($datos['pre_diagonosticos_emergencia'] as $key=> $dig)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$dig['codigo']}}</td>
                    <td>{{$dig['nombre']}}</td>
                    <td>
                        <div class="contenedor">
                            <div class="valor" style="font-size: 14px;">P</div>
                            <div class="marca"><?php if($dig['tipo']=='PRESUNTIVO'){echo 'X';}?></div>
                        </div>
                    </td>
                    <td>
                        <div class="contenedor">
                            <div class="valor" style="font-size: 12px;">D</div>
                            <div class="marca"><?php if($dig['tipo']=='DEFINITIVO'){echo 'X';}?></div>
                        </div>
                    </td>
                    <td>
                        <div class="contenedor">
                            <div class="valor" style="font-size: 12px;">R</div>
                            <div class="marca"><?php if($dig['tipo']=='REITERATIVO'){echo 'X';}?></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @elseif($datos['pre_diagonosticos_consultaexterna'])
            @foreach($datos['pre_diagonosticos_consultaexterna'] as $key=> $diag)
                <tr>
                    <td style="padding: 6px;">{{$key+1}}</td>
                    <td style="padding: 6px;">{{$diag['codigo']}}</td>
                    <td style="padding: 6px;">{{$diag['nombre']}}</td>
                    <td style="padding: 6px;">
                        <div class="contenedor">
                            <div class="valor" style="font-size: 10px;">P</div>
                            <div class="marca"><?php if($diag['tipo']=='PRESUNTIVO'){echo 'X';}?></div>
                        </div>
                    </td>
                    <td style="padding: 6px;">
                        <div class="contenedor">
                            <div class="valor" style="font-size: 10px;">D</div>
                            <div class="marca"><?php if($diag['tipo']=='DEFINITIVO'){echo 'X';}?></div>
                        </div>
                    </td>
                    <td style="padding: 6px;">
                        <div class="contenedor">
                            <div class="valor" style="font-size: 10px;">R</div>
                            <div class="marca"><?php if($diag['tipo']=='REITERATIVO'){echo 'X';}?></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>

    <table border="0" style="font-size:10px;">
        <tr>
            <th style="padding: 6px;"><b>DIAGNÓSTICOS POST OPERATORIO</b></th>
        </tr>
    </table>
    <table style="font-size:10px;" border="1">
            <tr>
            <th style="padding: 6px;">N°</th>
            <th style="padding: 6px;">CIE - 10</th>
            <th style="padding: 6px;">DESCRIPCIÓN DEL DIAGNÓSTICO</th>
            <th colspan="3" style="padding: 6px;"> TIPO DE DIAGNÓSTICO</th>
            </tr>
            @foreach($datos['post_diagnosticos'] as $key=> $dig)
            <tr>
            <td style="padding: 6px;">{{$key+1}}</td>
            <td style="padding: 6px;">{{$dig['codigo']}}</td>
            <td style="padding: 6px;">{{$dig['nombre']}}</td>
            <td style="padding: 6px;">
                <div class="contenedor">
                <div style="font-size: 10px;"> P </div>
                <div class="marca"><?php if($dig['tipo']=='PRESUNTIVO'){echo 'X';}?></div>
                </div>
            </td>
            <td style="padding: 6px;">
                <div class="contenedor">
                <div class="valor"  style="font-size: 10px;">D</div>
                <div class="marca"><?php if($dig['tipo']=='DEFINITIVO'){echo 'X';}?></div>
                </div>
            </td>
            <td style="padding: 6px;">
                <div class="contenedor">
                <div class="valor"  style="font-size: 10px;">R</div>
                <div class="marca"><?php if($dig['tipo']=='REPETITIVO'){echo 'X';}?></div>
                </div>
                </td>
            </tr>
            @endforeach
    </table>

    <table border="0" style="font-size:10px;">
        <tr>
            <th style="padding: 6px;"><b>PROCEDIMIENTOS</b></th>
        </tr>
    </table>
    <table style="font-size:10px;" border="1">
            <tr>
            <th style="padding: 6px;">N°</th>
            <th  style="padding: 6px;">CÓDIGO</th>
            <th  colspan="9" style="padding: 6px;">  NOMBRE DEL PROCEDIMIENTO</th>
            <th  colspan="2" style="padding: 6px;">FASE OPERATORIA</th>
            </tr>
            @foreach($datos['procedimientos'] as $key=> $proc)
                @if(!$proc['flag_examen_patologia'])
            <tr>
            <td style="padding: 6px;">{{$key+1}}</td>
            <td style="padding: 6px;">{{$proc['codigo']}}</td>
            <td colspan="9" style="text-align: left;" style="padding: 6px;">{{$proc['descripcion']}}</td>
            <td style="padding: 6px;">
                <div class="contenedor">
                    <div class="valor"  style="font-size: 10px;">PRE</div>
                    <div class="marca"><?php if($proc['flag_pre_post']==1){echo 'X';}?></div>
                </div>
            </td>
            <td style="padding: 6px;">
                <div class="contenedor">
                    <div class="valor"  style="font-size: 10px;">POST</div>
                    <div class="marca"><?php if($proc['flag_pre_post']==2){echo 'X';}?></div>
                </div>
            </td>
                @endif
            @endforeach
            </tr>
    </table>

    <table border="0">
        <tr>
            <td class="subtitulo_1"><b>V.- PROFESIONALES QUE INTERVIENEN EN LA OPERACIÓN</b></td>
        </tr>
    </table>
    
    <table border="0" style="font-size:10px;">
        <tr>
            <th style="padding: 6px;"><b>CIRUJANOS</b></th>
        </tr>
    </table>
    <table style="font-size:10px;" border="1">
        <tr>
            <th style="width: 3%; padding: 6px;">N°</th>
            <th  style="padding: 6px;">NOMBRE</th>
            <th  style="padding: 6px;">TIPO</th>
        </tr>
        @if(!empty($cirujanos))
            @foreach($cirujanos as $key => $row)
            <tr>
                <td style="padding: 6px;">
                    {{$key+1}} 
                </td>
                <td style="padding: 6px;">
                    {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}} 
                </td>
                <td style="padding: 6px;">
                    {{$row->descripcion}}
                </td>
            </tr>
            @endforeach
        @endif
    </table>
    <table border="0" style="font-size:10px;">
        <tr>
            <th style="padding: 6px;"><b>ANESTESIOLOGOS</b></th>
        </tr>
    </table>
    <table border="1" style="font-size:10px;">
        <tr>
            <th style="width: 3%; padding: 6px;">N°</th>
            <th  style="padding: 6px;">NOMBRE</th>
            <th  style="padding: 6px;">TIPO</th>
        </tr>
        @if(!empty($anestesiologos))
            @foreach($anestesiologos as $key => $row)
            <tr>
                <td style="padding: 6px;">{{$key+1}}</td>
                <td style="padding: 6px;">
                    {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}} 
                </td>
                <td style="padding: 6px;">
                    {{$row->descripcion}}
                </td>
            </tr>
            @endforeach
        @endif
    </table>
    <table border="0" style="font-size:10px;">
        <tr>
            <th style="padding: 6px;"><b>ENF. INSTRUMENTISTA</b></th>
        </tr>
    </table>
    <table border="1" style="font-size:10px;">
        <tr>
            <th style=" width: 3%; padding: 6px;">N°</th>
            <th style="padding: 6px;">NOMBRE</th>
            <th style="padding: 6px;">TIPO</th>
        </tr>
        @if(!empty($instrumentistas))
            @foreach($instrumentistas as $key => $row)
            <tr>
                <td style="padding: 6px;">{{$key+1}}</td>
                <td style="padding: 6px;">{{$row->nombre}}   {{$row->paterno}}  {{$row->materno}}</td>
                <td style="padding: 6px;">{{$row->descripcion}}</td>
            </tr>
            @endforeach
        @endif
    </table>

    <table border="0">
        <tr>
            <td class="subtitulo_1"><b>VI.- ESTADO Y DESTINO DEL PACIENTE</b></td>
        </tr>
    </table>
    <table border="1" style="font-size:10px;">
        @if($tipo_egreso=='DOMICILIARIA')
        <tr>
            <td style="padding: 6px;"><b>ESTADO:</b></td>
            <td style="padding: 6px;">{{$tipo_egreso}}</td>
            <td style="padding: 6px;">CONDICIÓN</td>
            <td style="padding: 6px;"><b>{{$condicion_alta}}</b></td>
        </tr>
        @else
        <tr>
            <td style="padding: 6px;"><b>ESTADO:</b></td>
            <td style="padding: 6px;">{{$tipo_egreso}}</td>
            <td style="padding: 6px;"><b>CONDICIÓN</b></td>
            <td style="padding: 6px;">{{$condicion_alta}}</td>
            <td style="padding: 6px;"><b>SERVICIO DESTINO</b></td>
            <td style="padding: 6px;">{{$servicio_destino}}</td>
            <td style="padding: 6px;"><b>CAMA</b></td>
            <td style="padding: 6px;">{{!empty($cama->nombre)?$cama->nombre:''}}</td>
        </tr>
        @endif
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
            <td style=" padding-top: 5px;"><b>PROFESIÓN:</b></td>
            <td style="padding-top: 5px;">{{ $prestacion->profesional_profesion }}</td>
            <td style="width:17%"><b>N° COLEGIATURA:</b></td>
            <td style="width:10%">{{ $prestacion->profesional_colegiatura }}</td>
        </tr>
    </table>
      
</body>
</html>
