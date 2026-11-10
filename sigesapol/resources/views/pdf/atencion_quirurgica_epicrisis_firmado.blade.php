<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>PDF EPICRISIS</title>
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
            margin: 1cm 1.5cm 2cm 1.5cm !important;
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
            /* margin-bottom: 15px !important; */
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        #tbl_header {
            text-align: center;
        }

        footer {               
            position: fixed;
            bottom: 2cm;
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

        div.border {
            border: 1px solid black;
        }

        /* Espaciado a todas las tablas con excepcion del encabezado */
        .tbl_ordenes td {
            padding: 3px;
        }

        /**
        Para los subtitulos que tengan Numero Romanos
         */
        /* .subtitulo_1 {
            font-size: 14px; 
            padding-bottom:10px;
        } */

        /**
        Para los subtitulos que tengan Numero enteros
         */
        /* .subtitulo_2 {
            font-size: 13px; 
            padding-bottom:1px;
        }

        table.tbl_diagnostico th {
            text-align: left;
        }
        table.tbl_diagnostico td {
            vertical-align: top;
        } */
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
            <td colspan="3" style="font-size: 18px;"><b>EPICRISIS</b></td>
        </tr>
    </table>
    
    <footer>
        
        <table id="tbl_hospitalizacion_footer">
            <!-- <tr>
                <td colspan="6" class="td_firma"><br><br><br><br></td>
            </tr> -->
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
                <!-- <td style="width: 60px">
                    <b>N° de Cama</b>
                </td> -->
            </tr>
            <tr>
                <td style="height:22px !important">{{$hospitalizacion->paterno_beneficiario}}</td>
                <td>{{$hospitalizacion->materno_beneficiario}}</td>
                <td>{{$hospitalizacion->nombre_beneficiario}}</td>
                <td>{{$hospitalizacion->nro_historia}}</td>
                <td>{{$hospitalizacion->servicio_encargado_nombre}}</td>
                
            </tr>
        </table>
    </footer>
    
    <div class="border">
    <table border="0" class="tbl_ordenes">
        <tr>
            <td style="width:30%"><b>FECHA INGRESO: </b><span>{{date("d/m/Y", strtotime($hospitalizacion->fecha_ingreso))}}</span></td>
            <td><b>HORA: </b><span>{{date("H:i a", strtotime($hospitalizacion->hora_ingreso))}}</span></td>
            <td style="width:50%">
                <b>SERVICIO A CARGO: </b><span>{{$hospitalizacion->servicio_encargado_nombre}}</span><br>
               
            </td>
        </tr>
    </table>
    </div>

   
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>RESUMEN DE LA ENFERMEDAD ACTUAL</b></td>
            </tr>
            <tr>
                <td colspan="2"><p>{{$epicrisis->egreso_resumen}}</p></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>EXAMEN CLÍNICO</b></td>
            </tr>
            <tr>
                <td colspan="2"><p>{{$epicrisis->egreso_examen_clinico}}</p></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>EXAMENES AUXILIARES</b></td>
            </tr>
            <tr>
                <td colspan="2"><p>{{$epicrisis->egreso_examen_auxiliar}}</p></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>EVOLUCIÓN</b></td>
            </tr>
            <tr>
                <td colspan="2"><p>{{$epicrisis->egreso_evolucion}}</p></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>TRATAMIENTO</b></td>
            </tr>
            <tr>
                <td colspan="2"><p>{{$epicrisis->egreso_tratamiento}}</p></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td><b>PROCEDIMIENTOS TERAPÉUTICOS Y/O DIAGNÓSTICOS</b></td>
            </tr>
            <tr>
                <td><p>{{$epicrisis->egreso_procedimiento}}</p></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>COMPLICACIONES</b></td>
            </tr>
            <tr>
                <td colspan="2"><p>{{$epicrisis->egreso_complicacion}}</p></td>
            </tr>
        </table>
    </div>
    
    <div class="border">
        <table border="0" class="tbl_ordenes">
            <tr>
                <td><b>FECHA EGRESO: </b><span>{{date("d/m/Y", strtotime($hospitalizacion->fecha_alta_medica))}}</span></td>
                <td><b>HORA: </b><span>{{date("H:i a", strtotime($hospitalizacion->hora_alta_medica))}}</span></td>
                <td style="width:30%"><b>ESTADÍA TOTAL: </b><span>{{ $dia_estancia }} DIAS</span></td>
            </tr>
        </table>
    </div>
    <?php
    $tipo_egreso = "";
    if($hospitalizacion->id_tipo_egreso=="1"){
        $tipo_egreso = "ALTA";
    }else if($hospitalizacion->id_tipo_egreso=="2"){
        $tipo_egreso = "HOSPITALIZACION";
    }else if($hospitalizacion->id_tipo_egreso=="3"){
        $tipo_egreso = "TRANSFERIDO";
    }else if($hospitalizacion->id_tipo_egreso=="4"){
        $tipo_egreso = "FALLECIDO";
    }else if($hospitalizacion->id_tipo_egreso=="7"){
        $tipo_egreso = "RETIRO VOLUNTARIO";
    }else if($hospitalizacion->id_tipo_egreso=="6"){
        $tipo_egreso = "FUGA";
    }

    $condicion_egreso = "";
    if($hospitalizacion->id_condicion_alta=="1"){
        $condicion_egreso = "MEJORADO";
    }else if($hospitalizacion->id_condicion_alta=="2"){
        $condicion_egreso = "CURADO";
    }else if($hospitalizacion->id_condicion_alta=="3"){
        $condicion_egreso = "TRASLADO";
    }else if($hospitalizacion->id_condicion_alta=="4"){
        $condicion_egreso = "RETIRO VOLUNTARIO";
    }else if($hospitalizacion->id_condicion_alta=="5"){
        $condicion_egreso = "FUGADO";
    }else if($hospitalizacion->id_condicion_alta=="6"){
        $condicion_egreso = "FALLECIDO";
    }
    
    ?>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="3"><b>TIPO EGRESO: </b><span>{{$tipo_egreso}}</span></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="3"><b>CONDICION ALTA: </b><span>{{$condicion_egreso}}</span></td>
            </tr>
        </table>
    </div>
    
    <div class="border">
        <table border="0" class="tbl_ordenes">
            <tr>
                <td style="width:3%"><b>#</b></td>
                <td style="width:92%"><b>DIAGNÓSTICOS DE ALTA</b></td>
                <td style="width:8%"><b>CIE</b></td>
            </tr>
            <?php $i = 1;?>
            @foreach($diagnosticos_egreso as $key=> $row)
             <tr>
                <td><b>{{ $i }}</b></td>
                <td><b>{{ $row->nombre }}</b></td>
                <td>{{ $row->codigo }}</td>
            </tr>
             <?php $i++; ?>
            @endforeach
            

        </table>
    </div>
    <?php
    $necropsia = "NO";
    if ($hospitalizacion->necropsia == 1 || $hospitalizacion->necropsia == "SI") {
        $necropsia = "SI";
    }
    ?>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>NECROPSIA: </b><span>{{ $necropsia }}</span></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>MEDIDAS GENERALES AL ALTA:</b></td>
            </tr>
            <tr>
                <td colspan="2"><p>{{$epicrisis->egreso_medidas_generales}}</p></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>MEDICAMENTOS PRESCRITOS AL ALTA:</b></td>
            </tr>
            <tr>
                <td colspan="2"><p>{{$epicrisis->egreso_medicamentos_prescritos}}</p></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>PRONÓSTICO: </b><span>{{$epicrisis->egreso_pronostico}}</span></td>
            </tr>
        </table>
    </div>
    <div class="border">
        <table class="tbl_ordenes">
            <tr>
                <td colspan="2"><b>CONCURRENCIAS: </b>
                <span>
                    @if ($servicios_concurrencia != null)
                        @for ($i=0; $i < count($servicios_concurrencia); $i++)
                            @if ($i==0)
                                {{ $servicios_concurrencia[$i]->servicio }}
                            @else
                                , {{$servicios_concurrencia[$i]->servicio }}
                            @endif
                        @endfor
                    @endif
                </span></td>
            </tr>
        </table>
    </div>
</body>

</html>