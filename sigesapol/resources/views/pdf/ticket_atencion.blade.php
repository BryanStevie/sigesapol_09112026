<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Ticket de Atencion</title>
        <link rel="stylesheet" type="text/css" href="assets/css/pdf.css"  />
        <style>
        </style>
    </head>

    <body style="font: 13px/2em  'Roboto', 'Noto', sans-serif; letter-spacing: 1">
        <div class="clearfix" style="border: 0px solid black; text-align: center; width: 300px">
            <div style="text-transform:uppercase">       
                {{$recetas[0]->ipress}}<br>              
            </div>
            <!--<div> <img src="images/hospital.png" width="150px" height="auto"/> </div>-->
            <div style="border-bottom: 1px solid black; margin-bottom: 5px">       
                COMPROBANTE DE ENTREGA<br>
                RECETA N° {{$recetas[0]->nro_receta}} <br>

                <?php
                date_default_timezone_set('America/Lima');
                ?>
                <?php
                $arrayMeses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
                    'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
                $arrayDias = array('DOMINGO', 'LUNES', 'MARTES',
                    'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO');
                echo $arrayDias[date('w')] . " " . date('d') . " DE " . $arrayMeses[date('m') - 1] . " DE " . date('Y');
                
                ?><br>
                <?php
                echo "HORA: ".date('g:ia');
                ?>
            </div>
            <div style="text-align: left; border-bottom: 0px solid black; margin-bottom: 10px">           
                {{$recetas[0]->nombre_beneficiario}} {{$recetas[0]->paterno_beneficiario}} {{$recetas[0]->materno_beneficiario}}<br>
                DNI: {{$recetas[0]->dni_beneficiario}} ({{$recetas[0]->tipo_beneficiario}})
            </div>

            <div style="text-align:left; border-bottom: 1px solid black">
                MEDICAMENTOS (CANT. DISPENSADA)
            </div>
            @foreach($medicamentos as $med)
            <div style="text-align:left">
                {{$med->nombre}} --- ({{$med->cantidad_dispensada}})
            </div>
            @endforeach

            <!--<div>
                <table class="tg">
                    <tr>
                        <th class="tg-yw4l">Medicamento</th>
                        <th class="tg-yw4l">Cant. Prescrita</th>
                        <th class="tg-yw4l">Cant. Dispensada</th>
                        <th class="tg-yw4l">Saldo</th>
                    </tr>
                    @foreach($medicamentos as $med)
                    <tr>
                        <td class="tg-yw4l">{{$med->nombre}}</td>
                        <td class="tg-yw4l">{{$med->cantidad_prescrita}}</td>
                        <td class="tg-yw4l">{{$med->cantidad_dispensada}}</td>
            <?php
            $saldo = $med->cantidad_prescrita - $med->cantidad_dispensada;
            ?>
                        <td class="tg-yw4l"><?php echo $saldo ?></td>
                    </tr>
                    @endforeach
                </table>

            </div>-->
            <div style="text-align: center; border-top: 1px solid black; margin-top: 10px; margin-bottom: 50px; font-size: 11px">
                POLICLÍNICO PNP SAN MARTÍN DE PORRES - UBAPOL SMP
            </div>

        </div>
    </body>
</html>