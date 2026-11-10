<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PDF HOJA TERAPEUTICA</title>
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

        #table_principal td {
            padding: 5px;
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

        table.tbl_diagnostico th {
            text-align: left;
        }
        table.tbl_diagnostico td {
            vertical-align: top;
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
                <p><br><br><br><b>DIRECCIÓN DE SANIDAD<br>HOSPITAL NACIONAL PNP "LUIS N. SÁENZ"</b></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 18px;"><b>HOJA DE TERAPEUTICA</b></td>
        </tr>
    </table>
    
    <footer>
        
        <table id="tbl_hospitalizacion_footer">
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
                <!-- <td style="width: 50px">
                    <b>Episodio</b>
                </td> -->
            </tr>
            <tr>
                <td style="height:22px !important">{{$evolucion->paciente_paterno}}</td>
                <td>{{$evolucion->paciente_materno}}</td>
                <td>{{$evolucion->paciente_nombre}}</td>
                <td>{{$evolucion->nro_historia}}</td>
                <td>{{$evolucion->servicio_sala}}</td>
                <td>{{$evolucion->cama}}</td>
                <!-- <td></td> -->
            </tr>
        </table>
    </footer>
    
    <table border="1" id="table_principal">
        <tr>
            <th rowspan="2" style="height:90px;width:30%">N° NOMBRE DEL MEDICAMENTO</th>
            <th rowspan="2" style="width:10%">PRESENT</th>
            <th rowspan="2" style="width:10%">DOSIS Y FRECUENCIA</th>
            <th rowspan="2" style="width:10%">VIA DE ADMINIST</th>
            <th colspan="4" style="width:40%">FECHA Y HORA</th>
        </tr>
        <tr>
            <td >&nbsp;<br></td>
            <td >&nbsp;<br></td>
            <td >&nbsp;<br></td>
            <td >&nbsp;<br></td>
        </tr>
        
        @foreach ($recetas as $receta)
            <?php $i=1; ?>
            @foreach ($receta_productos as $row)
                @if ($receta->id == $row->id_receta)
                <tr>
                    <td style="font-size: 10px">{{$row->codigo}} - {{$row->nombre}}
                        <?php 
                            if($row->codigo=='59075700'):
                            echo "(".$row->cantidad_prescrita."L)";
                            endif; 
                        ?>
                    </td>
                    <td style="text-align:center; font-size: 10px">{{$row->abreviatura}}</td>
                    <td style="font-size: 10px">{{$row->posologia}}</td>
                    <td style="text-align:center; font-size: 10px">{{$row->via}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php $i++; ?>
                @endif
            @endforeach
        @endforeach
        <tr>
            <td colspan="8">
                <p class="subtitulo_2"><b>DIETA</b></p>
                <p>{!! nl2br(e($evolucion->terapeutica_dieta)) !!}</p>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <p class="subtitulo_2"><b>INDICACION COMPLEMENTARIA</b></p>
                <p>{!! nl2br(e($evolucion->tratamiento_auxiliar)) !!}</p>
            </td>
        </tr>
        <!-- <tr>
            <td colspan="5"><br><br></td>
            <td></td>
            <td></td>
            <td></td>
        </tr> -->
    </table>
    
</body>

</html>